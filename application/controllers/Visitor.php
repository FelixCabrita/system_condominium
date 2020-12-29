<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : visitor (visitorController)
 * visitor Class to control all visitor related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Visitor extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('visitor_model');
        $this->isLoggedIn();
        $this->getDataCondominium();
    }
    public function getDataCondominium()
    {
        $this->load->model('condominium_model');
        $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing();
    }

    /**
     * This function used to load the first screen of the visitor
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the visitor list
     */
    public function listing()
    {
        if (checkForPermissions('visitor', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['visitorRecords'] = $this->visitor_model->Listing( $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'visitor'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de visitantes';
            $this->loadViews("visitor/visitor", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('visitor', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $data['apartmentRecord'] = $this->getDataApartment();
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo empleado';
            $this->loadViews("visitor/addNew", $this->globalApp, $data, null);
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
            $result = $this->visitor_model->checkExists($document, 0, $apartment, $this->condominium);
        } else {
            $result = $this->visitor_model->checkExists($document, $id, $apartment, $this->condominium);
        }
        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new visitor to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('visitor', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('document', 'Docuento de identidad', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('apartment', 'Apartamento donde trabajara', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('name', 'Nombre completo', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('mobile', 'Telefono Movil', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('started_work', 'Inicio a trabajar', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('work_type', 'Tipo de labor', 'trim|required|max_length[20]');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $document = (strtoupper($this->security->xss_clean($this->input->post('document'))));
                $name = strtoupper($this->security->xss_clean($this->input->post('name')));
                $mobile = (strtoupper($this->security->xss_clean($this->input->post('mobile'))));
                $started_work = strtoupper($this->security->xss_clean($this->input->post('started_work')));
                $work_type = (strtolower($this->security->xss_clean($this->input->post('work_type'))));
                $apartment = (strtolower($this->security->xss_clean($this->input->post('apartment'))));
                $check = $this->visitor_model->checkExists($document, 0, $apartment, $this->condominium);

                if (empty($check)) {
                    $info = array('document' => $document, 'name' => $name, 'started_work' => $started_work, 'work_type' => $work_type, 'mobile' => $mobile,
                        'condominium' => $this->condominium, 'apartment' => $apartment);
                    $result = $this->visitor_model->addNew($info);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Empleado creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del empleado');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El documento de empleado ya existe, por favor compruebe e intente de nuevo');
                }
                redirect('agregarvisitantes');
            }
        }
    }

    /**
     * This function is used load visitor edit information
     * @param number $visitorId : Optional : This is visitor id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('visitor', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('visitantes');
            }
            $data['permissions'] = $this->getPermissions()->{'visitor'};
            $data['visitorInfo'] = $this->visitor_model->getInfo($id, $this->condominium);
            $data['apartmentRecord'] = $this->getDataApartment();
            if ($data['visitorInfo'] == null) {
                redirect('visitantes');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar empleado';

            $this->loadViews("visitor/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the visitor information
     */
    public function edit()
    {
        if (checkForPermissions('visitor', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');
            $this->form_validation->set_rules('document', 'Docuento de identidad', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('apartment', 'Apartamento donde trabajara', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('name', 'Nombre completo', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('mobile', 'Telefono Movil', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('started_work', 'Inicio a trabajar', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('work_type', 'Tipo de labor', 'trim|required|max_length[20]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $document = (strtoupper($this->security->xss_clean($this->input->post('document'))));
                $name = strtoupper($this->security->xss_clean($this->input->post('name')));
                $mobile = (strtoupper($this->security->xss_clean($this->input->post('mobile'))));
                $started_work = strtoupper($this->security->xss_clean($this->input->post('started_work')));
                $work_type = (strtolower($this->security->xss_clean($this->input->post('work_type'))));
                $apartment = (strtolower($this->security->xss_clean($this->input->post('apartment'))));
                $check = $this->visitor_model->checkExists($document, $id, $apartment, $this->condominium);

                if (empty($check)) {
                    $info = array('document' => $document, 'name' => $name, 'started_work' => $started_work, 'work_type' => $work_type, 'mobile' => $mobile,
                        'condominium' => $this->condominium, 'apartment' => $apartment);
                    $result = $this->visitor_model->edit($info, $id, $this->condominium);

                    $this->load->model('vigilant_model');
                    $info = array('document' => $document, 'name' => $name, 'mobile' => $mobile, 'condominium' => $this->condominium);
                    $idVisitor = $this->vigilant_model->addVisitor($info);
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Empleado actualizado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la actualizacion del empleado');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El documento de empleado ya existe, por favor compruebe e intente de nuevo');
                }
                redirect("editarvisitantes/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the visitor using visitorId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('visitor', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL cliente TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->visitor_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }
    public function changeStatus()
    {
        if (checkForPermissions('visitor', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $visitorId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $observation = $this->input->post('observation');
            $visitor = $this->visitor_model->getInfo($visitorId, $this->condominium);
            $observation = (empty($visitor->observation) ? "" : $visitor->observation . " | ") . $observation;

            $visitorInfo = array('status' => $isactive, 'observation' => $observation);
            $result = $this->visitor_model->edit($visitorInfo, $visitorId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

}
