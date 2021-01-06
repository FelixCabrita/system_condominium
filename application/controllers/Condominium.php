<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : condominium (condominiumController)
 * condominium Class to control all condominium related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class condominium extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('condominium_model');
        $this->isLoggedIn();
        $this->getDataCondominium();
    }

    public function getDataCondominium()
    {
        $this->load->model('condominium_model');
        $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing();
    }

    /**
     * This function used to load the first screen of the condominium
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the condominium list
     */
    public function listing()
    {
        if ($this->getPermissions('condominium', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['permissions'] = $this->getPermissions()->{'condominium'};
            $data['condominiumRecords'] = $this->condominium_model->Listing();
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de condominios';
            $this->loadViews("condominium/condominium", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if ($this->getPermissions('condominium', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo condominio';
            $this->loadViews("condominium/addNew", $this->globalApp, null, null);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    public function checkCodeExistc()
    {
        $code = $this->input->post("code");
        $result = $this->condominium_model->checkCodeExists($code);
        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new condominium to the system
     */
    public function addNewData()
    {
        if ($this->getPermissions('condominium', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('fname', 'Nombre completo', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('address', 'Direccion', 'trim|required');
            $this->form_validation->set_rules('mobile', 'Telefono', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('rcn', 'RCN', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('email', 'Correo electronico', 'trim|required|valid_email|max_length[100]');
            $this->form_validation->set_rules('masive', 'Notificacion', 'trim|required|integer');

            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('fname'))));
                $address = strtoupper(($this->security->xss_clean($this->input->post('address'))));
                $mobile = (($this->security->xss_clean($this->input->post('mobile'))));
                $rcn = (($this->security->xss_clean($this->input->post('rcn'))));
                $email = (strtolower($this->security->xss_clean($this->input->post('email'))));
                $masive = (($this->security->xss_clean($this->input->post('masive'))));

                $condominiumInfo = array('name' => $name, 'mobile' => $mobile, 'masive' => $masive, 'rcn' => $rcn, 'email' => $email, 'address' => $address);
                $condominiumId = $this->condominium_model->addNew($condominiumInfo);

                if ($condominiumId > 0) {
                    $this->session->set_flashdata('success', 'Condominio creado exitosamente');
                    $userInfo = array('condominium' => $result);
                    $this->load->model('user_model');
                    $result = $this->user_model->edit($userInfo, $this->id, $this->condominium);
                    $this->session->set_userdata('condominium_text', $name);
                    $this->session->set_userdata('condominium', $condominiumId);
                    redirect('condominios');
                } else {
                    $this->session->set_flashdata('error', 'Fallo la creacción del condominio');
                    $this->addNew();
                }
            }
        }
    }

    /**
     * This function is used load condominium edit information
     * @param number $condominiumId : Optional : This is condominium id
     */
    public function editOld($id = null)
    {
        if ($this->getPermissions('condominium', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('condominios');
            }

            $data['permissions'] = $this->getPermissions()->{'condominium'};

            $data['condominiumInfo'] = $this->condominium_model->gettInfo($id);
            if ($data['condominiumInfo'] == null) {
                redirect('condominios');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar condominio';

            $this->loadViews("condominium/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the condominium information
     */
    public function edit()
    {
        if ($this->getPermissions('condominium', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $condominiumId = $this->input->post('id');
            $this->form_validation->set_rules('fname', 'Nombre completo', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('address', 'Direccion', 'trim|required');
            $this->form_validation->set_rules('mobile', 'Telefono', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('rcn', 'RCN', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('email', 'Correo electronico', 'trim|required|valid_email|max_length[100]');
            $this->form_validation->set_rules('masive', 'Notificacion', 'trim|required|integer');

            if ($this->form_validation->run() == false) {
                $this->editOld($condominiumId);
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('fname'))));
                $address = strtoupper(($this->security->xss_clean($this->input->post('address'))));
                $mobile = (($this->security->xss_clean($this->input->post('mobile'))));
                $rcn = (($this->security->xss_clean($this->input->post('rcn'))));
                $email = (strtolower($this->security->xss_clean($this->input->post('email'))));
                $masive = (($this->security->xss_clean($this->input->post('masive'))));

                $condominiumInfo = array('name' => $name, 'mobile' => $mobile, 'masive' => $masive, 'rcn' => $rcn, 'email' => $email, 'address' => $address);

                $result = $this->condominium_model->edit($condominiumInfo, $condominiumId);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'Condominio actualizado');
                    if ($condominiumId == $this->condominium) {
                        $this->session->set_userdata('condominium_text', $name);
                    }
                } else {
                    $this->session->set_flashdata('error', 'Fallo actualización');
                }
                redirect('editarcondominio/' . $condominiumId);
            }
        }
    }

    /**
     * This function is used to delete the condominium using condominiumId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if ($this->getPermissions('condominium', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $condominiumId = $this->input->post('id');
            $result = $this->condominium_model->delete($condominiumId);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('condominium', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $condominiumId = $this->input->post('userId');
            $isactive = $this->input->post('isactive');
            $userInfo = array('status' => $isactive);

            $result = $this->condominium_model->edit($userInfo, $condominiumId);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeCondominium()
    {
        if (checkForPermissions('condominium', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');
            $userInfo = array('condominium' => $id);
            $this->load->model('user_model');
            $result = $this->user_model->edit($userInfo, $this->id, $this->condominium);

            $this->load->model('condominium_model');
            $data = $this->condominium_model->gettInfo($id);
            $this->session->set_userdata('condominium_text', $data->name);
            $this->session->set_userdata('condominium', $id);
            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    /**
     * Page not found : error 404
     */
    public function pageNotFound()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : 404 - Page Not Found';

        $this->loadViews("404", $this->globalApp, null, null);
    }

    
 

    public function getDataAccountDetail()
    {
        $this->load->model('accountdetail_model');
        return $this->accountdetail_model->getListing('', $this->condominium);
    }

    public function parameter()
    {
        if ($this->rol > ROLE_VENDOR) {
            $this->loadThis();
        } else {

            $data['permissions'] = $this->getPermissions()->{'bills'};
            $data['info'] = $this->condominium_model->getParameter($this->condominium);
            $data['getDataAccountDetail'] = $this->getDataAccountDetail(); 
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Establecer parametros';
            $this->loadViews("module/parameter", $this->globalApp, $data, null);
        }
    }

    public function changue_parameter()
    { 
        $generate_credit = (($this->security->xss_clean($this->input->post('generate_credit'))));
        $generate_debit = (($this->security->xss_clean($this->input->post('generate_debit'))));

        $debit_card = (($this->security->xss_clean($this->input->post('debit_card'))));
        $debit_cash = (($this->security->xss_clean($this->input->post('debit_cash'))));
        $debit_check = (($this->security->xss_clean($this->input->post('debit_check'))));
        $debit_transfer = (($this->security->xss_clean($this->input->post('debit_transfer'))));
        $credit_input = (($this->security->xss_clean($this->input->post('credit_input'))));

        $debit_deposit = (($this->security->xss_clean($this->input->post('debit_deposit'))));
        $credit_deposit = (($this->security->xss_clean($this->input->post('credit_deposit'))));
        $credit_deposit_pay = (($this->security->xss_clean($this->input->post('credit_deposit_pay'))));
  
        $condominiumInfo = array('generate_credit' => $generate_credit, 'generate_debit' => $generate_debit
        , 'debit_card' => $debit_card, 'debit_cash' => $debit_cash, 'debit_check' => $debit_check
        , 'debit_transfer' => $debit_transfer, 'credit_input' => $credit_input
        , 'debit_deposit' => $debit_deposit, 'credit_deposit' => $credit_deposit
        , 'credit_deposit_pay' => $credit_deposit_pay);

        $result = $this->condominium_model->edit($condominiumInfo, $this->condominium);

        if ($result == true) {
            $this->session->set_flashdata('success', 'parametros actualizados actualizado'); 
        } else {
            $this->session->set_flashdata('error', 'Fallo actualización');
        }
        redirect('establcer_parametros');

    }

}
