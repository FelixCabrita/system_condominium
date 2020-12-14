<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CommonArea (CommonAreaController)
 * CommonArea Class to control all CommonArea related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class CommonArea extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('commonarea_model');
        $this->isLoggedIn();
        $this->getDataCondominium();
    }

    public function getDataCondominium()
    {
        $this->load->model('condominium_model');
        $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing();
    }

    /**
     * This function used to load the first screen of the CommonArea
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the CommonArea list
     */
    public function listing()
    {
        if (checkForPermissions('commonarea', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['CommonAreaRecords'] = $this->commonarea_model->Listing($this->id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'commonarea'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de Area comun';
            $this->loadViews("commonarea/commonarea", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('commonarea', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nueva Area comun';
            $this->loadViews("commonarea/addNew", $this->globalApp, null, null);
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
            $result = $this->commonarea_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->commonarea_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new CommonArea to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('commonarea', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('max_person', 'Personas permitidas', 'trim|required'); 
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $max_person = strtoupper($this->security->xss_clean($this->input->post('max_person')));   
                $info = array('name' => $name, 'max_person' => $max_person, 'condominium'=>$this->condominium);
                $result = $this->commonarea_model->addNew($info);

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'Area comun creado exitosamente');
                } else {
                    $this->session->set_flashdata('error', 'Fallo la creacción del area comun');
                }
                redirect('agregarareascomunes');
            }
        }
    }

    /**
     * This function is used load CommonArea edit information
     * @param number $CommonAreaId : Optional : This is CommonArea id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('commonarea', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('areascomunes');
            }
            $data['permissions'] = $this->getPermissions()->{'commonarea'};
            $data['CommonAreaInfo'] = $this->commonarea_model->getInfo($id, $this->condominium);

            if ($data['CommonAreaInfo'] == null) {
                redirect('areascomunes');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar Area comun';

            $this->loadViews("commonarea/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the CommonArea information
     */
    public function edit()
    {
        if (checkForPermissions('commonarea', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');
            $this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('max_person', 'Personas permitidas', 'trim|required'); 
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $max_person = strtoupper($this->security->xss_clean($this->input->post('max_person')));   
                $info = array('name' => $name, 'max_person' => $max_person);
                $result = $this->commonarea_model->edit($info, $id, $this->condominium);
                if ($result > 0) {
                    $this->session->set_flashdata('success', 'Area comun creado exitosamente');
                } else {
                    $this->session->set_flashdata('error', 'Fallo la creacción del area comun');
                }
                redirect("editarareascomunes/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the CommonArea using CommonAreaId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('commonarea', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL Area comun TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->commonarea_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('commonarea', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $CommonAreaId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $CommonAreaInfo = array('status' => $isactive);

            $result = $this->commonarea_model->edit($CommonAreaInfo, $CommonAreaId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }
}
