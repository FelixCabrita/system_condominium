<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Reservation (ReservationController)
 * Reservation Class to control all Reservation related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Reservation extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reservation_model');
        $this->isLoggedIn();
        $this->getDataCondominium();
    }

    public function getDataCondominium()
    {
        if($this->rol==ROLE_ADMIN){
            $this->load->model('condominium_model');
            $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing($this->id);
        }
    }

    public function getDataEdifices($code = '')
    {
        $this->load->model('edifice_model');
        if (empty($code)) {
            return $this->edifice_model->getListing($this->condominium);
        } else {
            return $this->edifice_model->getInfoCode($code, $this->condominium);
        }
    }

    public function getApartmentsAssignation($role, $id)
    {
        if ($role > ROLE_VIGILANT) {
            $this->load->model('tenant_model');
            return $this->tenant_model->getAssignment($id, $this->condominium);
        } else {   
            $this->load->model('apartment_model');
            return $this->apartment_model->getListing("", $this->condominium); 
        }
    }

    public function getDataCommonArea()
    {
        $this->load->model('commonarea_model');
        return $this->commonarea_model->getListing($this->condominium);

    }

    public function getDatareservations($edifice)
    {
        $this->load->model('reservation_model');
        echo json_encode($this->reservation_model->getListing($edifice, $this->condominium));
    }

    /**
     * This function used to load the first screen of the Reservation
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    public function listing()
    {
        if (checkForPermissions('reservations', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['ReservationRecords'] = $this->reservation_model->Listing($this->rol<ROLE_OWNER?"":$this->id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'reservations'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de reservaciones';
            $this->loadViews("reservations/reservations", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('reservations', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo reservacion';
            $data['edificeRecords'] = $this->getDataEdifices(); 
            $data['commonareaRecords'] = $this->getDataCommonArea();
            $data['apartmentRecords'] = $this->getApartmentsAssignation($this->rol,$this->id);
            $data['reservations'] = json_encode($this->reservation_model->getListing($this->condominium));
            $this->loadViews("reservations/addNew", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    public function checkExists()
    {
        $id = $this->input->post("id");
        $code = $this->input->post("code");

        if (empty($id)) {
            $result = $this->reservation_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->reservation_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new Reservation to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('reservations', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {

            $edifice = (($this->security->xss_clean($this->input->post('edifice'))));
            $apartment = ($this->security->xss_clean($this->input->post('apartment')));
            $quantity_persons = ($this->security->xss_clean($this->input->post('quantity_persons')));
            $commonarea = strtoupper($this->security->xss_clean($this->input->post('commonarea')));
            $start_time = ($this->security->xss_clean($this->input->post('start_time')));
            $ending_time = strtoupper($this->security->xss_clean($this->input->post('ending_time')));
            $reservation_date = strtoupper($this->security->xss_clean($this->input->post('reservation_date')));
            
            $check = $this->reservation_model->checkExists($apartment, $commonarea, $reservation_date, strtotime($reservation_date.' '.$start_time) ,strtotime($reservation_date.' '.$ending_time), $this->condominium);

            if ($check<1) {
                $dataEdifice = $this->getDataEdifices($edifice);
                $info = array('apartment' => $apartment, 'commonarea' => $commonarea, 'start_time' => $start_time,  'start_timestamp' => strtotime( $reservation_date.' '.$start_time) ,
                 'condominium' => $this->condominium, 'quantity_persons' => $quantity_persons, 'ending_time' => $ending_time, 'ending_timestamp' => strtotime( $reservation_date.' '.$ending_time) , 'reservation_date' => $reservation_date);
                $result = $this->reservation_model->addNew($info);

                if ($result>0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false,'error')));}
            } else {
                $this->session->set_flashdata('error', 'El codigo de reservacion ya existe, por favor compruebe e intente de nuevo');
                echo (json_encode(array('status' => false,'error'=>'Su reservacion coincide con una ya realizada, por favor verifique!')));
            } 
        }

    }

    /**
     * This function is used load Reservation edit information
     * @param number $ReservationId : Optional : This is Reservation id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('reservations', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('reservaciones');
            }
            $data['permissions'] = $this->getPermissions()->{'reservations'};
            $data['ReservationInfo'] = $this->reservation_model->getInfo($id, $this->condominium);
            $data['edificeRecords'] = $this->getDataEdifices();
            if ($data['ReservationInfo'] == null) {
                redirect('reservaciones');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar reservaciones';

            $this->loadViews("reservations/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the Reservation information
     */
    public function edit()
    {
        if (checkForPermissions('reservations', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');

            $this->form_validation->set_rules('number_parking', 'Numero de parqueo', 'trim|required|max_length[10]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $number_parking = ($this->security->xss_clean($this->input->post('number_parking')));
                $conditions = strtoupper($this->security->xss_clean($this->input->post('conditions')));

                $info = array('number_parking' => $number_parking, 'conditions' => $conditions);

                $result = $this->reservation_model->edit($info, $id, $this->condominium);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'reservacion actualizado');
                } else {
                    $this->session->set_flashdata('error', 'Fallo actualización');
                }
                redirect("editarreservacion/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the Reservation using ReservationId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('reservations', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL reservacion TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->reservation_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('reservations', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $observation = str_replace("setfecha()" ,date('Y-m-d H:i:s'),$this->input->post('observation'));  
            $reservation = $this->reservation_model->getInfo($id, $this->condominium); 
            $observation = (empty($reservation->observations)?"":$reservation->observations." | ").$observation; 

            $reservationInfo = array('status' => $isactive,'observations' => $observation); 
            $result = $this->reservation_model->edit($reservationInfo, $id, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }
}
