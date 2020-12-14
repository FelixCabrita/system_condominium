<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Edifice (EdificeController)
 * Edifice Class to control all Edifice related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Edifice extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('edifice_model');
        $this->isLoggedIn();
        $this->getDataCondominium();
    }

    public function getDataCondominium()
    {
        $this->load->model('condominium_model');
        $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing();
    }
     
    /**
     * This function used to load the first screen of the Edifice
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the Edifice list
     */
    public function listing()
    {
        if (checkForPermissions('edifices', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['EdificeRecords'] = $this->edifice_model->Listing($this->id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'edifices'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de edificios';
            $this->loadViews("edifices/edifices", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('edifices', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo edificio';
            $this->loadViews("edifices/addNew", $this->globalApp, null, null);
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
            $result = $this->edifice_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->edifice_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new Edifice to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('edifices', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('edifice', 'Edificio', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('block', 'Manzana', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('manager', 'Encargado', 'trim|required|max_length[250]');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $edifice = (strtoupper($this->security->xss_clean($this->input->post('edifice'))));
                $block = strtoupper($this->security->xss_clean($this->input->post('block')));
                $manager = strtoupper($this->security->xss_clean($this->input->post('manager')));
                $observation = strtolower($this->security->xss_clean($this->input->post('observation')));
                $code = $edifice.$block;

                $check = $this->edifice_model->checkExists($code, 0, $this->condominium);
                if (empty($result)) { 
                    $info = array('edifice' => $edifice,'code' => $code,'block' => $block,'condominium'=>$this->condominium, 'manager' => $manager, 'observation' => $observation);
                    $result = $this->edifice_model->addNew($info);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'edificio creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del edificio');
                    }
                }else{
                    $this->session->set_flashdata('error', 'El codigo de edificio ya existe, por favor compruebe e intente de nuevo');
                }
                redirect('agregaredificio');
            }
        }
    }

    /**
     * This function is used load Edifice edit information
     * @param number $EdificeId : Optional : This is Edifice id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('edifices', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('edificios');
            }
            $data['permissions'] = $this->getPermissions()->{'edifices'};
            $data['EdificeInfo'] = $this->edifice_model->getInfo($id, $this->condominium);

            if ($data['EdificeInfo'] == null) {
                redirect('edificios');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar edificios';

            $this->loadViews("edifices/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the Edifice information
     */
    public function edit()
    {
        if (checkForPermissions('edifices', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');

            $this->form_validation->set_rules('manager', 'Encargado', 'trim|required|max_length[250]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $manager = strtoupper($this->security->xss_clean($this->input->post('manager')));
                $observation = strtolower($this->security->xss_clean($this->input->post('observation')));

             
                $info = array('manager' => $manager, 'observation' => $observation);
                

                $result = $this->edifice_model->edit($info, $id, $this->condominium);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'edificio actualizado');
                } else {
                    $this->session->set_flashdata('error', 'Fallo actualización');
                }
                redirect("editaredificio/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the Edifice using EdificeId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('edifices', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id'); 

            /*
                SE DEBE COMPROBAR SI EL EDIFICIO TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
            */ 

            $result = $this->edifice_model->delete($id ,$this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('edifices', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $EdificeId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $EdificeInfo = array('status' => $isactive);

            $result = $this->edifice_model->edit($EdificeInfo, $EdificeId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }
}
