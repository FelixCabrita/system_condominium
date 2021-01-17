<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : tag (tagController)
 * tag Class to control all tag related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Tag extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tag_model');
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

    public function getDataApartment($code)
    {
        $this->load->model('apartment_model');
        return $this->apartment_model->getInfo($code, $this->condominium);
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
     * This function used to load the first screen of the tag
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the tag list
     */
    public function listing()
    {
        if (checkForPermissions('tags', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['tagRecords'] = $this->tag_model->Listing($this->id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'tags'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de etiqueta';
            $this->loadViews("tag/tag", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('tags', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $data['edificeRecords'] = $this->getDataEdifices();
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nueva etiqueta';
            $this->loadViews("tag/addNew", $this->globalApp, $data, null);
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
            $result = $this->tag_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->tag_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new tag to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('tags', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('serial', 'Seriales', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('apartment', 'Apartamento', 'trim|required'); 
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $serial = (strtoupper($this->security->xss_clean($this->input->post('serial'))));
                $id_apartment = strtoupper($this->security->xss_clean($this->input->post('apartment')));   
                $apartment = $this->getDataApartment($id_apartment);
                $max_id = $this->tag_model->checkExists($apartment->id,$this->condominium);
                $start = 1;
                if($max_id){
                    $start =$max_id->max;
                }
                for($i= $start ;$i<($serial+$max_id->max);$i++){
                    $code_serial =  $apartment->code.str_pad($i, 6, '0', STR_PAD_LEFT);
                    $info = array('serial' => $code_serial, 'apartment' => $id_apartment, 'condominium'=>$this->condominium);
                    $result = $this->tag_model->addNew($info);
                }  
                if ($result > 0) {
                    $this->session->set_flashdata('success', 'Etiqueta creado exitosamente');
                } else {
                    $this->session->set_flashdata('error', 'Fallo la creacción del etiqueta');
                }
                redirect('agregaretiquetas');
            }
        }
    }

    /**
     * This function is used load tag edit information
     * @param number $tagId : Optional : This is tag id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('tags', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('areascomunes');
            }
            $data['permissions'] = $this->getPermissions()->{'tags'};
            $data['tagInfo'] = $this->tag_model->getInfo($id, $this->condominium);

            if ($data['tagInfo'] == null) {
                redirect('areascomunes');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar etiqueta';

            $this->loadViews("tag/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the tag information
     */
    public function edit()
    {
        if (checkForPermissions('tags', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id'); 
            $this->form_validation->set_rules('observation', 'Observacion', 'trim|required'); 
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $observation = (strtoupper($this->security->xss_clean($this->input->post('observation')))); 
                $info = array('observation' => $name);
                $result = $this->tag_model->edit($info, $id, $this->condominium);
                if ($result > 0) {
                    $this->session->set_flashdata('success', 'Etiqueta actualizada exitosamente');
                } else {
                    $this->session->set_flashdata('error', 'Fallo la actualizacion del etiqueta');
                }
                redirect("editarareascomunes/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the tag using tagId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('tags', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL etiqueta TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO

             */

            $result = $this->tag_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('tags', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $tagId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $observation = $this->input->post('observation');  
            $tag = $this->tag_model->getInfo($tagId, $this->condominium); 
            $observation = (empty($tag->observations)?"":$tag->observations." | ").$observation; 

            $tagInfo = array('status' => $isactive,'observations' => $observation); 
            $result = $this->tag_model->edit($tagInfo, $tagId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function undoVehicle()
    {
        if (checkForPermissions('tags', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $tagId = $this->input->post('tag_id'); 
            $observation = $this->input->post('observation');  
            $tag = $this->tag_model->getInfo($tagId, $this->condominium); 
            $observation = (empty($tag->observations)?"":$tag->observations." | ").$observation; 

            $tagInfo = array('vehicle' => '','observations' => $observation); 
            $result = $this->tag_model->edit($tagInfo, $tagId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }


    public function changeVehicle()
    {
        if (checkForPermissions('tags', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $tag_id = $this->input->post('tag_id');
            $vehicle = $this->input->post('vehicle'); 
            $tagInfo = array('vehicle' => $vehicle); 
            $result = $this->tag_model->edit($tagInfo, $tag_id, $this->condominium); 
            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }
}
