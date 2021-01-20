<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require APPPATH . '/libraries/BaseController.php';
/**
 * Class : Login (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */

const USER_VALID_CODE = 0;
const USER_NOT_EXISTS = 1;
const USER_NOT_ACTIVE = 2;
const USER_INVALID_ROLE = 3;

class Login extends BaseController
{
    protected $workstation = "";protected $workstation_name = "";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->library('form_validation');

        $this->load->helper('cookie');
      
        $this->workstation = !isset($_COOKIE['workstation_fscv']) ? '' : $_COOKIE['workstation_fscv'];
        $this->workstation_name = !isset($_COOKIE['workstation_name_fscv']) ? '' : $_COOKIE['workstation_name_fscv'];

    }

    public function index()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');

        $data["workstation"] = $this->workstation;
        $data["workstation_name"] = $this->workstation_name;
        if (empty($this->workstation)) {
            $this->valid_condominium();
        } else {
            if ($this->isLoggedIn()) {
                redirect('/tablero');
            }

            $this->load->view('login/login', $data, false);
        }

    }

    public function valid_condominium()
    { 
        setcookie("workstation_fscv",  '', time() - (86400 * 15),'/'); 
        setcookie("workstation_name_fscv", '', time() - (86400 * 15),'/');
       setcookie("workstation_fscv_", '', time() - (86400 * 15),'/');
        $this->session->sess_destroy();
        $this->load->view('login/setworking');
    }

    public function condominium_owner()
    {
        setcookie("workstation_name_fscv", "OWNER", time() + (86400 * 1),'/');
        setcookie("workstation_fscv", "OWNER", time() + (86400 * 1),'/');
        redirect('login');
    }
    public function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        return isset($isLoggedIn) && $isLoggedIn === true;
    }

    public function logUser()
    {
        if ($this->IsValidLoginForm()) {
            $email = strtolower($this->security->xss_clean($this->input->post('username')));
            $password = $this->input->post('password');
            $isclient = $this->input->post('isclient') == 'on' ? 1 : 0;
            $log = true;
            if ($isclient == 0) { 
                if ($this->workstation == "OWNER") {
                    $UserData = $this->login_model->LoginOWNER($email, $password);
                    if (!empty($UserData)) {
                        if ($UserData->type != 1) {
                            $log = false;
                        }
                        setcookie("workstation_fscv_", $UserData->id, time() + (86400 * 15),'/');
                    }

                }else{
                    $UserData = $this->login_model->LoginUser($email, $password, $this->workstation);
                }

               
                $UserValidCode = $this->validPanelUser($UserData);
               
                $log = !empty($UserData);
                if ($UserValidCode == STATUS_ACTIVE && $log) {

                    $iar = $this->login_model->gettInfoAIDC($UserData->condominium);

                    $sessionArray =
                    array(
                        'id' => $UserData->id,
                        'username' => $UserData->username,
                        'permissions' =>  $this->workstation == "OWNER"?PERMISSION_DEFAULT:$UserData->permissions,
                        'name' => $UserData->name,
                        'rol' => $UserData->type, 'apartment' => 0,
                        'condominium' => $UserData->condominium,
                        'generate_credit' =>  !empty($iar)?$iar->generate_credit:0,
                        'owner' =>  $this->workstation == "OWNER"?$UserData->id:$iar->owner,
                        'generate_debit' => !empty($iar)?$iar->generate_debit:0,   
                        'max_user_tenant' => !empty($iar)?$iar->max_user_tenant:0,
                        'generates_report' => $UserData->generates_report,
                        'condominium_text' => !empty($iar)?$iar->name:'SIN CONDOMINIO',
                        'isLoggedIn' => true
                    ); 
  
                    setcookie("workstation_fscv",!empty($iar)? $iar->id:0, time() + (86400 * 15),'/');
                    setcookie("workstation_name_fscv", !empty($iar)?$iar->name:'SIN CONDOMINIO', time() + (86400 * 15),'/');
                    setcookie("workstation_fscv_", $UserData->id, time() + (86400 * 15),'/');

                    $this->session->set_userdata($sessionArray);
                    unset($sessionArray['id'], $sessionArray['isLoggedIn']);
                    $this->index(); 
                }
 
                
                $this->displayLoginFormError($UserValidCode);
                $this->index();
            } else {
              
                $TenantData = $this->login_model->LoginTenant($email, $password, $this->workstation);
                $TenantValidCode = $this->validPanelUser($TenantData);
               
                $log = !empty($TenantData);
                if ($TenantValidCode == STATUS_ACTIVE && $log) {
                    $iar = $this->login_model->gettInfoAIDC($TenantData->condominium);
                    
                  //  t.id, t.condominium, t.document, t.name, t.mobile,   t.password, t.type, t.created_at, t.status
                    $sessionArray =
                    array(
                        'id' => $TenantData->id,
                        'username' => $TenantData->document,
                        'permissions' => PERMISSION_TENANT,
                        'name' => $TenantData->name,
                        'rol' => $TenantData->type,
                        'condominium' => $iar->id,
                        'owner' => $iar->owner,
                        'condominium_text' => $iar->name,
                        'isLoggedIn' => true,
                    );
                    setcookie("workstation_fscv",!empty($iar)? $iar->id:0, time() + (86400 * 15),'/');
                    setcookie("workstation_name_fscv", !empty($iar)?$iar->name:'SIN CONDOMINIO', time() + (86400 * 15),'/');
                    setcookie("workstation_fscv_", $iar->owner, time() + (86400 * 15),'/');

                    $this->session->set_userdata($sessionArray);
                    unset($sessionArray['id'], $sessionArray['isLoggedIn']);
                    $this->index(); 
                }

                $this->displayLoginFormError(USER_INVALID_ROLE);
                $this->index();
            }

        } else {
            $this->index();
        }

    }

    private function isValidLoginForm()
    {
        $this->form_validation->set_rules('username', 'Usuario', 'required|max_length[128]|trim');
        $this->form_validation->set_rules('password', 'Contraseña', 'required|max_length[32]');

        return $this->form_validation->run() === true;
    }

    private function displayLoginFormError($UserValidCode)
    {
        if ($UserValidCode == USER_NOT_EXISTS) {
            $this->session->set_flashdata('error', 'Usuario o Contraseña incorrectos');
        } else if ($UserValidCode == USER_NOT_ACTIVE) {
            $this->session->set_flashdata('error', 'Este usuario no esta activo');
        } else if ($UserValidCode == USER_INVALID_ROLE) {
            $this->session->set_flashdata('error', 'Usuario o Clave incorrectos');
        }

    }

    private function validPanelUser($UserData)
    {
        if (empty($UserData)) {
            return USER_NOT_EXISTS;
        } else if ($UserData->status != STATUS_ACTIVE) {
            return USER_NOT_ACTIVE;
        } else {
            return STATUS_ACTIVE;
        }

    }

    private function createLoginFormErrorMessage($UserValidCode)
    {
        switch ($UserValidCode) {
            case USER_NOT_EXISTS:
                $this->session->set_flashdata('error', 'Correo o Clave incorrectos');
                $this->index();
                break;
            case USER_NOT_ACTIVE:
                $this->session->set_flashdata('error', 'Usuario inactivo');
                $this->index();
                break;
            case USER_INVALID_ROLE:
                $this->session->set_flashdata('error', 'Este usuario no posee los permisos necesarios');
                $this->index();
                break;
        }
    }

    public function forgotPassword()
    {
        if ($this->isLoggedIn()) {
            redirect('/tablero');
        }

        $this->load->view('user/forgotPassword', null);
    }

    public function resetPasswordUser()
    {
        $status = '';

        if ($this->isValidResetPasswordForm()) {
            $email = strtolower($this->security->xss_clean($this->input->post('login_email')));
            $isclient = strtolower($this->security->xss_clean($this->input->post('isclient'))) == 'on' ? 1 : 0;

            $check = false;

            if ($isclient == 0) {
                $check = $this->login_model->checkEmailExist($email);
            } else {
                $check = $this->login_model->checkEmailExistClient($email);
            }

            if (!empty($check)) {
                $encoded_email = urlencode($email);
                $iar = $this->login_model->gettInfoAIDC($check->condominium);
                $this->load->helper('string');
                $data['email'] = $email;
                $data['activation_id'] = random_string('alnum', 15);
                $data['createdDtm'] = date('Y-m-d H:i:s');
                $data['agent'] = $isclient; 
                $data['createdBy'] =$_COOKIE['workstation_fscv']=='OWNER'?$check->id:$iar->owner;
                $data['client_ip'] = $this->input->ip_address();

                $save = $this->login_model->resetPasswordUser($data);

                if ($save) {
                   
                    if($_COOKIE['workstation_fscv']=='OWNER'){
                        $data1['reset_link'] = base_url() . "resetearcontraconfir/OWNER/" . $data['activation_id'] . "/" . $encoded_email;
                    }else{
                        $data1['reset_link'] = base_url() . "resetearcontraconfir/".$data['createdBy']."/" . $data['activation_id'] . "/" . $encoded_email;
                    }
                    if ($isclient == 0) {
                        $userInfo = $this->login_model->getCustomerInfoByEmail($email);
                    } else {
                        $userInfo = $this->login_model->getCustomerInfoByEmailTenant($email);
                    }
                    $link = $data1['reset_link'];
                    if (!empty($userInfo)) { 
                        $data1["name"] = $userInfo->name;
                        $data1["email"] = $email;
                        $data1["message"] = "Restablecer su contraseña";
                    }

                    $mensaje = "Buen dia " . $userInfo->name . " para restablecer su contraseña de " . NAME_SYSTEM . " por favor acceda a este link $link  y siga los pasos.";
                    $sendStatus = $this->enviar($email, $userInfo->name, "Restablecer contraseña", $mensaje);

                    if ($sendStatus > 0) {
                        $status = "success";
                        setFlashData($status, "El enlace para restablecer la contraseña se envió correctamente, verifique los correos.");
                    } else {
                        $status = "error";
                        setFlashData($status, "El correo electrónico ha fallado, intente nuevamente.".$data1['reset_link']);
                    }
                } else {
                    $status = 'error';
                    setFlashData($status, "Parece un error al enviar sus datos, intente nuevamente.");
                }
            } else {
                $status = 'error';
                setFlashData($status, "Este correo electrónico no está registrado con nosotros.");
            }
            redirect("/recuperar");

        } else {
            redirect("/recuperar");
        }

    }

    private function isValidResetPasswordForm()
    {
        $this->form_validation->set_rules('login_email', 'Email', 'required|valid_email|max_length[128]|trim');

        return $this->form_validation->run() === true;
    }

    public function resetPasswordConfirmUser($owner,$activation_id, $email)
    {
        // Get email and activation code from URL values at index 3-4
        $email = urldecode($email);  
        $this->load->helper('cookie'); 
        setcookie("workstation_fscv", $owner, time() + (86400 * 15),'/'); 
        $is_correct = $this->login_model->checkActivationDetails($owner,$email, $activation_id);
        $data['email'] = $email;
        $data['activation_code'] = $activation_id;

     if (!empty($is_correct)) {
            $this->load->view('user/newPassword', $data);
        } else {
            redirect("/");
        }
    }

    public function createPasswordUser()
    {
        $status = '';
        $message = '';
        $email = strtolower($this->input->post("email"));
        $activation_id = $this->input->post("activation_code");

        $this->load->library('form_validation');

        $this->form_validation->set_rules('password', 'Contraseña', 'required|max_length[20]');
        $this->form_validation->set_rules('cpassword', 'Confirma contraseña', 'trim|required|matches[password]|max_length[20]');

        if ($this->form_validation->run() == false) {
            $this->resetPasswordConfirmUser($_COOKIE['workstation_fscv'],$activation_id, urlencode($email));
        } else {
            $password = $this->input->post('password');
            $cpassword = $this->input->post('cpassword');

            // Check activation id in database
            $is_correct = $this->login_model->checkActivationDetails($_COOKIE['workstation_fscv'],$email, $activation_id);

            if (!empty($is_correct)) {
                $isCliente = $is_correct->agent;
                $createdBy = $is_correct->createdBy;
                $update = "";
                if ($isCliente == '0') {
                    $update = $this->login_model->createPasswordUser($email, $password,$createdBy);

                } else {
                    $update = $this->login_model->createPasswordTenant($email, $password);
                }

                if ($update > 0) {
                    $status = 'success';
                    $message = 'Restablecimiento de contraseña exitosamente';
                } else {
                    $status = 'error';
                    $message = 'Restablecimiento de contraseña fallo';
                }

            } else {
                $status = 'error';
                $message = 'Restablecimiento de contraseña fallo';
            }

            setFlashData($status, $message);
            redirect("validar_condominio");
        }
    }

    public function logcondominium()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('code_condominium', 'Codigo del condominio', 'required|max_length[700]|trim');
        if ($this->form_validation->run() == false) {
            $this->index();
        } else {
            $code_condominium = $this->security->xss_clean($this->input->post('code_condominium'));
            $result = $this->login_model->gettInfo($code_condominium);

            if ($result) { 
                setcookie("workstation_fscv", $result->id,1);
                setcookie("workstation_name_fscv", $result->name,1); 
                setcookie("workstation_fscv_", $result->name,1);
                
                setcookie("workstation_fscv", $result->id, time() + (86400 * 15),'/');
                setcookie("workstation_name_fscv", $result->name, time() + (86400 * 15),'/');
                setcookie("workstation_fscv_", $result->owner, time() + (86400 * 15),'/');
                redirect('login');
            } else {
                $this->session->set_flashdata('error', 'Este codigo de condominio no existe');
                $this->index();
            }
        }
    }

    public function enviar($correo, $nombre, $asunto, $mensaje)
    {
        $this->load->library('email');
        $config['protocol'] = PROTOCOL;
        $config["smtp_host"] = SMTP_HOST;
        $config["smtp_user"] = SMTP_USER;
        $config["smtp_pass"] = SMTP_PASS;
        $config["smtp_port"] = SMTP_PORT;
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = true;
        $config['validate'] = true;
        $this->email->initialize($config);
        $this->email->from(EMAIL_FROM, FROM_NAME);
        $this->email->to($correo, $nombre);
        $this->email->subject($asunto);
        $this->email->set_mailtype("html");
        $this->email->message($mensaje);
        return $resutl = $this->email->send();
    }
}
