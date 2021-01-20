<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : cashier (cashierController)
 * cashier Class to control all cashier related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Cashier extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cashier_model');
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

    /**
     * This function used to load the first screen of the cashier
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("tablero", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the cashier list
     */
    public function listing()
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['cashierRecords'] = $this->cashier_model->Listing('', $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Cierres de caja';
            $this->loadViews("cashier/cashier", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('bills', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            if($this->rol == ROLE_VENDOR){
                $check = $this->cashier_model->getCheck($this->id, $this->condominium);
                redirect('cierredecaja');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Cierre de caja';
            $this->loadViews("cashier/addNew", $this->globalApp, null, null);
        }
    }

    public function getAmount()
    {
        if (checkForPermissions('bills', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $date = $this->input->post('date');
            $result = $this->cashier_model->getCheck($this->id, $date, $this->condominium);
            $cash = $this->cashier_model->getAmountType(0, $date, $this->id, $this->condominium);
            $transfer = $this->cashier_model->getAmountType(1, $date, $this->id, $this->condominium);
            $check = $this->cashier_model->getAmountType(2, $date, $this->id, $this->condominium);
            $card = $this->cashier_model->getAmountType(3, $date, $this->id, $this->condominium);
            echo (json_encode(array('status' => true, "cash" => $cash->amount, "transfer" => $transfer->amount, "check" => $check->amount, "card" => $card->amount)));

        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    public function checkExists()
    {
        $id = $this->input->post("id");
        $document = $this->input->post("document");
        $apartment = $this->input->post("apartment");

        if (empty($id)) {
            $result = $this->cashier_model->checkExists($document, 0, $apartment, $this->condominium);
        } else {
            $result = $this->cashier_model->checkExists($document, $id, $apartment, $this->condominium);
        }
        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new cashier to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('bills', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('date', 'Fecha', 'trim|required');
            $this->form_validation->set_rules('cash_input', 'Efectivo', 'trim|required');
            $this->form_validation->set_rules('transfer_input', 'Transferencia', 'trim|required');
            $this->form_validation->set_rules('card_input', 'Tarjeta', 'trim|required');
            $this->form_validation->set_rules('check_input', 'Cheque', 'trim|required');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $cash_input = (($this->security->xss_clean($this->input->post('cash_input'))));
                $transfer_input = ($this->security->xss_clean($this->input->post('transfer_input')));
                $card_input = (($this->security->xss_clean($this->input->post('card_input'))));
                $check_input = ($this->security->xss_clean($this->input->post('check_input')));
                $date = ($this->security->xss_clean($this->input->post('date')));
                $observation = ($this->security->xss_clean($this->input->post('observation')));
                $cash_exit = $this->cashier_model->getAmountType(0, $date, $this->id, $this->condominium)->amount;
                $transfer_exit = $this->cashier_model->getAmountType(1, $date, $this->id, $this->condominium)->amount;
                $check_exit = $this->cashier_model->getAmountType(2, $date, $this->id, $this->condominium)->amount;
                $card_exit = $this->cashier_model->getAmountType(3, $date, $this->id, $this->condominium)->amount;
                
                $tota_input = $cash_input+$transfer_input+$card_input +$check_input; 
                $tota_exit = $cash_exit+$transfer_exit+$card_exit +$check_exit;

                if($tota_input ==  $tota_exit){
                    $info = array( 'observation' => $observation,'cash_input' => $cash_input, 'transfer_input' => $transfer_input, 'card_input' => $card_input, 'check_input' => $check_input,
                    'cash_exit' => $cash_exit, 'transfer_exit' => $transfer_exit, 'check_exit' => $check_exit, 'card_exit' => $card_exit, "user"=> $this->id,
                                   'condominium' => $this->condominium);
                    $result = $this->cashier_model->addNew($info);
    
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Cierre de caja creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del cierre de caja');
                    }
                }else{
                    $this->session->set_flashdata('error', 'El cierre de caja no coincide con el del sistema');
                }

                
                 
                redirect('cierredecaja');
            }
        }
    }

    /**
     * This function is used load cashier edit information
     * @param number $cashierId : Optional : This is cashier id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('cierre de caja');
            }
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $data['cashierInfo'] = $this->cashier_model->getInfo($id, $this->condominium);
            $data['apartmentRecord'] = $this->getDataApartment();
            if ($data['cashierInfo'] == null) {
                redirect('cierre de caja');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar empleado';

            $this->loadViews("cashier/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the cashier information
     */
    public function edit()
    {
        if (checkForPermissions('bills', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');
            $this->form_validation->set_rules('document', 'Docuento de identidad', 'trim|required');
            $this->form_validation->set_rules('apartment', 'Apartamento donde trabajara', 'trim|required');
            $this->form_validation->set_rules('name', 'Nombre completo', 'trim|required');
            $this->form_validation->set_rules('mobile', 'Telefono Movil', 'trim|required');
            $this->form_validation->set_rules('started_work', 'Inicio a trabajar', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('work_type', 'Tipo de labor', 'trim|required|max_length[20]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $document = (($this->security->xss_clean($this->input->post('document'))));
                $name = ($this->security->xss_clean($this->input->post('name')));
                $mobile = (($this->security->xss_clean($this->input->post('mobile'))));
                $started_work = ($this->security->xss_clean($this->input->post('started_work')));
                $work_type = (strtolower($this->security->xss_clean($this->input->post('work_type'))));
                $apartment = (strtolower($this->security->xss_clean($this->input->post('apartment'))));
                $check = $this->cashier_model->checkExists($document, $id, $apartment, $this->condominium);

                if (empty($check)) {
                    $info = array('document' => $document, 'name' => $name, 'started_work' => $started_work, 'work_type' => $work_type, 'mobile' => $mobile,
                        'condominium' => $this->condominium, 'apartment' => $apartment);
                    $result = $this->cashier_model->edit($info, $id, $this->condominium);

                    $this->load->model('vigilant_model');
                    $info = array('document' => $document, 'name' => $name, 'mobile' => $mobile, 'condominium' => $this->condominium);
                    $idCashier = $this->vigilant_model->addCashier($info);
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Empleado actualizado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la actualizacion del empleado');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El documento de empleado ya existe, por favor compruebe e intente de nuevo');
                }
                redirect("editarcierre de caja/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the cashier using cashierId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('bills', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL cliente TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->cashier_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }
    public function changeStatus()
    {
        if (checkForPermissions('bills', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id'); 
            $observation = $this->input->post('observation'); 
            $Info = array('status' => 0, 'observation_status' => $observation);
            $result = $this->cashier_model->edit($Info, $id, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

}
