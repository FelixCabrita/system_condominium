<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Period (PeriodController)
 * Period Class to control all Period related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Period extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('period_model');
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
     * This function used to load the first screen of the Period
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the Period list
     */
    public function listing()
    {
        if (checkForPermissions('period', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['PeriodRecords'] = $this->period_model->Listing($this->id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'period'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de periodos';
            $this->loadViews("periods/periods", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('period', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo periodo';
            $this->loadViews("periods/addNew", $this->globalApp, null, null);
        }
    }

    /**
     * This function is used to add new Period to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('period', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('start', 'Fecha inicio', 'trim|required');
            $this->form_validation->set_rules('end', 'Fecha final', 'trim|required');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $start =  date("Y-m-d",strtotime($this->security->xss_clean($this->input->post('start'))));
                $end = date("Y-m-d",strtotime($this->security->xss_clean($this->input->post('end')))); 
                
                $info = array('name' => $name,'start' => $start,'end' => $end,'condominium'=>$this->condominium);
                $result = $this->period_model->addNew($info);

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'Periodo creado exitosamente');
                } else {
                    $this->session->set_flashdata('error', 'Fallo la creacción del periodo');
                }
               
                redirect('agregareperiodos');
            }
        }
    }

    /**
     * This function is used load Period edit information
     * @param number $PeriodId : Optional : This is Period id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('period', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('periodos');
            }
            $data['permissions'] = $this->getPermissions()->{'period'};
            $data['PeriodInfo'] = $this->period_model->getInfo($id, $this->condominium);

            if ($data['PeriodInfo'] == null) {
                redirect('periodos');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar periodos';

            $this->loadViews("periods/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the Period information
     */
    public function edit()
    {
        if (checkForPermissions('period', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');

            $this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('start', 'Fecha inicio', 'trim|required');
            $this->form_validation->set_rules('end', 'Fecha final', 'trim|required');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $start =  date("Y-m-d",strtotime($this->security->xss_clean($this->input->post('start'))));
                $end = date("Y-m-d",strtotime($this->security->xss_clean($this->input->post('end'))));  
                $info = array('name' => $name,'start' => $start,'end' => $end,'condominium'=>$this->condominium);

                $result = $this->period_model->edit($info, $id, $this->condominium);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'edificio actualizado');
                } else {
                    $this->session->set_flashdata('error', 'Fallo actualización');
                }
                redirect("editarperiodos/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the Period using PeriodId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('period', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id'); 

            /*
                SE DEBE COMPROBAR SI EL EDIFICIO TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
            */ 

            $result = $this->period_model->delete($id ,$this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('period', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $PeriodInfo = array('status' => 0);
            $result = $this->period_model->edit($PeriodInfo, "", $this->condominium);

            $PeriodId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $PeriodInfo = array('status' => $isactive);

            $result = $this->period_model->edit($PeriodInfo, $PeriodId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }
}
