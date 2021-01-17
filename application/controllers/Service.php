<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Service (ServiceController)
 * Service Class to control all Service related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Service extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('service_model');
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
     * This function used to load the first screen of the Service
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the Service list
     */
    public function listing()
    {
        if (checkForPermissions('services', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['ServiceRecords'] = $this->service_model->Listing($this->id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'services'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de servicios';
            $this->loadViews("services/services", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('services', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo servicio';
            $this->loadViews("services/addNew", $this->globalApp, null, null);
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
            $result = $this->service_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->service_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new Service to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('services', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('code', 'Codigo', 'trim|required|max_length[20]');
            $this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('amount', 'Monto', 'trim|required');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $code = (strtoupper($this->security->xss_clean($this->input->post('code'))));
                $name = strtoupper($this->security->xss_clean($this->input->post('name')));
                $amount = ($this->security->xss_clean($this->input->post('amount')));
                $date_load = date("Y-m-d", strtotime($this->security->xss_clean($this->input->post('date_load'))));
                $check = $this->service_model->checkExists($code, 0, $this->condominium);
                if (empty($result)) {
                    $info = array('code' => $code, 'name' => $name, 'amount' => $amount, 'condominium' => $this->condominium, 'date_load' => $date_load);
                    $result = $this->service_model->addNew($info);
                     
                    if ($result > 0) {

                        if($date_load!='1970-01-01'){
                            $this->service_model->create_service($date_load,$result,$this->condominium);
                        }
 
                        $this->session->set_flashdata('success', 'servicio creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del servicio');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo de servicio ya existe, por favor compruebe e intente de nuevo');
                }
                redirect('agregarservicio');
            }
        }
    }

    /**
     * This function is used load Service edit information
     * @param number $ServiceId : Optional : This is Service id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('services', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('servicios');
            }
            $data['permissions'] = $this->getPermissions()->{'services'};
            $data['ServiceInfo'] = $this->service_model->getInfo($id, $this->condominium);

            if ($data['ServiceInfo'] == null) {
                redirect('servicios');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar servicios';

            $this->loadViews("services/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the Service information
     */
    public function edit()
    {
        if (checkForPermissions('services', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id'); 
            $code = $this->input->post('code');
            $this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('amount', 'Monto', 'trim|required');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $name = strtoupper($this->security->xss_clean($this->input->post('name')));
                $amount = ($this->security->xss_clean($this->input->post('amount')));
                $date_load = date("Y-m-d", strtotime($this->security->xss_clean($this->input->post('date_load'))));

                $check = $this->service_model->checkExists($code, $id, $this->condominium);
                if (empty($check)) {
                    $info = array(  'name' => $name, 'amount' => $amount, 'condominium' => $this->condominium, 'date_load' => $date_load);
                    $result = $this->service_model->edit($info, $id, $this->condominium);

                    if ($result > 0) {

                        if($date_load!='1970-01-01'){  
                            $this->service_model->delete_service($id);
                            $this->service_model->create_service($date_load,$id,$this->condominium);
                        }

                        $this->session->set_flashdata('success', 'servicio actualizado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la actualizacion del servicio');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo de servicio ya existe, por favor compruebe e intente de nuevo');
                }
                redirect("editarservicio/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the Service using ServiceId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('services', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL servicio TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->service_model->delete($id, $this->condominium);

            if ($result) {
                $this->service_model->delete_service($id);
                echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('services', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $ServiceId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $ServiceInfo = array('status' => $isactive);

            $result = $this->service_model->edit($ServiceInfo, $ServiceId, $this->condominium);

            if ($result > 0) {
                if($isactive==0){
                    $this->service_model->delete_service($ServiceId);
                }else{
                    $service_data = $this->service_model->getInfo($ServiceId, $this->condominium);
                        
                    if($service_data->date_load!='1970-01-01'){  
                        $this->service_model->delete_service($ServiceId);
                        $this->service_model->create_service($service_data->date_load,$ServiceId,$this->condominium);
                    }
                }
                
                echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    

}
