<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Tenant (TenantController)
 * Tenant Class to control all Tenant related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Tenant extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tenant_model');
        $this->isLoggedIn();
        $this->getDataCondominium();
    }

    public function getDataCondominium()
    {
        $this->load->model('condominium_model');
        $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing();
    }

    /**
     * This function used to load the first screen of the Tenant
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
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
     * This function is used to load the Tenant list
     */
    public function listing()
    {
        if (checkForPermissions('tenants', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['TenantRecords'] = $this->tenant_model->Listing($this->id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'tenants'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de clientes';
            $this->loadViews("tenant/tenant", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the Tenant list
     */
    public function assignments($id)
    {
        if (checkForPermissions('tenants', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['AssignmentRecords'] = $this->tenant_model->Assignment($id, $this->condominium);
            $data['edificeRecords'] = $this->getDataEdifices();
            $data['TenantInfo'] = $this->tenant_model->getInfo($id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'tenants'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de asignaciones';
            $this->loadViews("tenant/assigning_apartment", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('tenants', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nueva cliente';
            $this->loadViews("tenant/addNew", $this->globalApp, null, null);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    public function checkExists()
    {
        $id = $this->input->post("id");
        $code = $this->input->post("document");

        if (empty($id)) {
            $result = $this->tenant_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->tenant_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    
    /**
     * This function is used to check whether email already exist or not
     */
    public function getTenant()
    { 
        $document = $this->input->post("document"); 
        $result = $this->tenant_model->getInfodocument($document,  $this->condominium); 
        if ($result) {echo (json_encode(array('status' => true,"tenant"=>$result)));} else {echo (json_encode(array('status' => false)));}
    }


    /**
     * This function is used to add new Tenant to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('tenants', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('document', 'Docuento', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('name', 'Nombre completo', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('mobile', 'Telefono Movil', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('landline_phone', 'Telefono residencial', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('email', 'Correo electronico', 'trim|required|valid_email|max_length[100]');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $document = (strtoupper($this->security->xss_clean($this->input->post('document'))));
                $name = strtoupper($this->security->xss_clean($this->input->post('name')));
                $mobile = (strtoupper($this->security->xss_clean($this->input->post('mobile'))));
                $landline_phone = strtoupper($this->security->xss_clean($this->input->post('landline_phone')));
                $email = (strtolower($this->security->xss_clean($this->input->post('email'))));

                $type = (strtolower($this->security->xss_clean($this->input->post('type'))));

                $check = $this->tenant_model->checkExists($document, 0, $this->condominium);

                if (empty($check)) {
                    $password = $this->input->post('user') == 'on' ? getHashedPassword($document) : "";
                    $info = array('document' => $document, 'name' => $name, 'type' => $type, 'password' => $password, 'mobile' => $mobile, 'condominium' => $this->condominium,
                        'landline_phone' => $landline_phone, 'email' => $email);
                    $result = $this->tenant_model->addNew($info);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Clientes creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del clientes');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El documento de clientes ya existe, por favor compruebe e intente de nuevo');
                }
                redirect('agregarinquilinos');
            }
        }
    }

    /**
     * This function is used load Tenant edit information
     * @param number $TenantId : Optional : This is Tenant id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('tenants', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('inquilinos');
            }
            $data['permissions'] = $this->getPermissions()->{'tenants'};
            $data['TenantInfo'] = $this->tenant_model->getInfo($id, $this->condominium);

            if ($data['TenantInfo'] == null) {
                redirect('inquilinos');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar cliente';

            $this->loadViews("tenant/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the Tenant information
     */
    public function edit()
    {
        if (checkForPermissions('tenants', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');
            $this->form_validation->set_rules('document', 'Docuento', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('name', 'Nombre completo', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('mobile', 'Telefono Movil', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('landline_phone', 'Telefono residencial', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('email', 'Correo electronico', 'trim|required|valid_email|max_length[100]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $document = (strtoupper($this->security->xss_clean($this->input->post('document'))));
                $name = strtoupper($this->security->xss_clean($this->input->post('name')));
                $mobile = (strtoupper($this->security->xss_clean($this->input->post('mobile'))));
                $landline_phone = strtoupper($this->security->xss_clean($this->input->post('landline_phone')));
                $email = (strtolower($this->security->xss_clean($this->input->post('email'))));
                $type = (($this->security->xss_clean($this->input->post('type'))));
                $check = $this->tenant_model->checkExists($document, $id, $this->condominium);
                if (empty($check)) {
                    $password = $this->tenant_model->getInfo($id, $this->condominium)->password;
                    if ($this->input->post('user') == 'on' && empty($password)) {
                        $password = getHashedPassword($document);
                    }else{
                        $password = "";
                    }
                    $info = array('document' => $document, 'name' => $name, 'mobile' => $mobile, 'type' => $type, 'condominium' => $this->condominium,
                        'landline_phone' => $landline_phone, 'password' => ($password), 'email' => $email);
                    $result = $this->tenant_model->edit($info, $id, $this->condominium);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'clientes actualizado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la actualizacion del clientes');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El documento de clientes ya existe, por favor compruebe e intente de nuevo');
                }
                redirect("editarinquilinos/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the Tenant using TenantId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('tenants', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL inquilino TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->tenant_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }
    public function changeStatus()
    {
        if (checkForPermissions('tenants', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $TenantId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $TenantInfo = array('password' => "");

            $result = $this->tenant_model->edit($TenantInfo, $TenantId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function set_asignation()
    {
        if (checkForPermissions('tenants', PERMISSION_CREATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $tenant = $this->input->post('id');
            $edifice = $this->input->post('edifice');
            $date_start = $this->input->post('date_start');
            $apartment = $this->input->post('apartment');
            $check = $this->tenant_model->checkExistsAsignation($edifice, $apartment, $tenant, $this->condominium);
            if (empty($check)) {
                $info = array('tenant' => $tenant, 'edifice' => $edifice, 'apartment' => $apartment, 'created_at' => $date_start, 'condominium' => $this->condominium);
                $result = $this->tenant_model->set_asignation($info);
                if ($result) {
                    echo (json_encode(array('status' => true)));
                } else {echo (json_encode(array('status' => false, "error" => "")));}
            }else{
                echo (json_encode(array('status' => false, "error" => "Hay una asignacion similar activa en estos momentos, por favor verifiquela")));
            }
        }
    }

    public function delete_asignation()
    {
        if (checkForPermissions('tenants', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $Id = $this->input->post('id');
            $observation = $this->input->post('observation');
            $info = array('observation' => $observation, 'status' => 0, 'updated_at' => date('Y-m-d'));
            $result = $this->tenant_model->edit_asingnation($info, $Id, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

}
