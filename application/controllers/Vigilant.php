<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Vigilant (VigilantController)
 * Vigilant Class to control all Vigilant related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Vigilant extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('vigilant_model');
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

    /**
     * This function used to load the first screen of the Vigilant
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("tablero", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the Vigilant list
     */
    public function listing()
    {
        if (checkForPermissions('vigilant', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['VigilantRecords'] = $this->vigilant_model->Listing($this->condominium);
            $data['permissions'] = $this->getPermissions()->{'vigilant'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Casilla de Vigilancia';
            $this->loadViews("vigilant/vigilant", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the Vigilant list
     */
    public function log($document)
    {
        if (checkForPermissions('vigilant', PERMISSION_READ) != true || $this->generates_report==0) {
            $this->loadThis();
        } else {
            if ($document == null) {
                redirect('casillavigilante');
            }
            $information = $this->vigilant_model->checkExists($document, 0, $this->condominium);
            $data['visitorInfo'] = $information;
            if ($data['visitorInfo'] == null) {
                redirect('casillavigilante');
            }
            $data['VigilantRecords'] = $this->vigilant_model->Listing($this->condominium, $document);
            $data['permissions'] = $this->getPermissions()->{'vigilant'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Visitante -> ' . $information->name;
            $this->loadViews("vigilant/log", $this->globalApp, $data, null);
        }
    }
/**
 * This function is used to load the Vigilant list
 */
    public function generateReport()
    {
        if (checkForPermissions('vigilant', PERMISSION_READ) != true || $this->generates_report==0) {
            $this->loadThis();
        } else {
            $start_date = ($this->security->xss_clean($this->input->post('start_date')));
            $ending_date = ($this->security->xss_clean($this->input->post('ending_date')));
            $type_visitor = ($this->security->xss_clean($this->input->post('type_visitor')));
            $type_input = ($this->security->xss_clean($this->input->post('type_input')));

            $data['VigilantRecords'] = $this->vigilant_model->ListingReport($this->condominium, $start_date, $ending_date, $type_visitor, $type_input);
            $data['start_date'] = $start_date;
            $data['ending_date'] = $ending_date;
            $data['type_visitor'] = $type_visitor;
            $data['type_input'] = $type_input;

            $name_report = "Reporte";
            if (!empty($start_dat)) {
                $name_report = $name_report . " {" . $start_date . " - " . $ending_date . "}";
            }
            if (!empty($type_visitor)) {
                $name_report = $name_report . " {" . checkVisitor($type_visitor) . "}";
            }

            if (!empty($type_input)) {
                $name_report = $name_report . " {" . ($type_input == VIGILANT_INPUT ? "ENTRADA" : "SALIDA") . "}";
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : '.$name_report;
            $this->loadViews("vigilant/report", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('vigilant', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo apartamento';
            $data['edificeRecords'] = $this->getDataEdifices();
            $this->loadViews("vigilant/addNew", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    public function checkExists()
    {

        $code = $this->input->post("document");
        $result = $this->vigilant_model->checkExists($code, 0, $this->condominium);
        if ($result) {echo (json_encode(array('status' => true, "visitor" => $result)));} else {echo (json_encode(array('status' => false)));}
    }

    /**
     * This function is used to add new Vigilant to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('vigilant', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('document', 'Documento de identidad', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('name', 'Nombre completo', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('edifice', 'Edificio', 'trim|required');
            $this->form_validation->set_rules('apartment', 'Apartamento', 'trim|required');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $document = (($this->security->xss_clean($this->input->post('document'))));
                $name = strtoupper($this->security->xss_clean($this->input->post('name')));
                $mobile = ($this->security->xss_clean($this->input->post('mobile')));
                $edifice = ($this->security->xss_clean($this->input->post('edifice')));
                $licence_plate = ($this->security->xss_clean($this->input->post('licence_plate')));
                $apartment = ($this->security->xss_clean($this->input->post('apartment')));
                $observation = strtoupper($this->security->xss_clean($this->input->post('observation')));
                $status = ($this->security->xss_clean($this->input->post('status')));
                $checkVisitor = $this->vigilant_model->checkExistsVisitor($document, $this->condominium);
                $idVisitor = "";

                if (empty($checkVisitor)) {
                    $info = array('document' => $document, 'name' => $name, 'mobile' => $mobile, 'condominium' => $this->condominium);
                    $idVisitor = $this->vigilant_model->addVisitor($info);
                } else {
                    $idVisitor = $checkVisitor->id;
                    if (!empty($name)) {
                        $info = array('name' => $name);
                        $this->vigilant_model->editVisitor($info, $idVisitor, $this->condominium);
                    }

                    if (!empty($mobile)) {
                        $info = array('mobile' => $mobile);
                        $this->vigilant_model->editVisitor($info, $idVisitor, $this->condominium);
                    }
                }

                $info = array('visitor' => $idVisitor, 'licence_plate' => $licence_plate, 'condominium' => $this->condominium,
                    'edifice' => $edifice, 'apartment' => $apartment, 'observation' => $observation, 'status' => $status, 'user' => $this->id);
                $result = $this->vigilant_model->addNew($info);

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'Visita generada exitosamente');
                } else {
                    $this->session->set_flashdata('error', 'Fallo la operacion');
                }
                redirect('entrada_salida');
            }
        }
    }

    /**
     * This function is used load Vigilant edit information
     * @param number $VigilantId : Optional : This is Vigilant id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('vigilant', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('visitantes');
            }
            $data['permissions'] = $this->getPermissions()->{'vigilant'};
            $data['VigilantInfo'] = $this->vigilant_model->getInfo($id, $this->condominium);
            $data['edificeRecords'] = $this->getDataEdifices();
            if ($data['VigilantInfo'] == null) {
                redirect('visitantes');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar visitantes';

            $this->loadViews("vigilant/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the Vigilant information
     */
    public function edit()
    {
        if (checkForPermissions('vigilant', PERMISSION_UPDATE) != true) {
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

                $result = $this->vigilant_model->edit($info, $id, $this->condominium);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'apartamento actualizado');
                } else {
                    $this->session->set_flashdata('error', 'Fallo actualización');
                }
                redirect("editarapartamento/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the Vigilant using VigilantId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('vigilant', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL apartamento TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->vigilant_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('vigilant', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $VigilantId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $VigilantInfo = array('status' => $isactive);

            $result = $this->vigilant_model->edit($VigilantInfo, $VigilantId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }
}
