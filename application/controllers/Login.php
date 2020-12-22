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

    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        if ($this->isLoggedIn()) {
            redirect('/dashboard');
        }

        $this->load->view('login/login');
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

            //TODO: Check function "loginMe" of login_model to fix it
            $UserData = $this->login_model->LoginUser($email, $password);

            $UserValidCode = $this->validPanelUser($UserData);

            if ($UserValidCode == USER_VALID_CODE) {
                $sessionArray =
                array(
                    'id' => $UserData->id,
                    'username' => $UserData->username,
                    'permissions' => $UserData->permissions,
                    'name' => $UserData->name,
                    'rol' => $UserData->type, 'apartment' => 0,
                    'condominium' => $UserData->condominium,
                    'condominium_text' => $UserData->condominium_name,
                    'isLoggedIn' => true,
                );

                $this->session->set_userdata($sessionArray);
                unset($sessionArray['id'], $sessionArray['isLoggedIn']);
                $this->index();
            } else {
                $TenantData = $this->login_model->LoginTenant($email, $password);
                $TenantValidCode = $this->validPanelUser($TenantData);
                if ($TenantValidCode == USER_VALID_CODE) {
                    $sessionArray =
                        array(
                            'id' => $TenantData->id,
                            'username' => $TenantData->document,
                            'permissions' => PERMISSION_TENANT,
                            'name' => $TenantData->name,
                            'rol' =>  $TenantData->type,
                            'condominium' => $TenantData->condominium,
                            'condominium_text' => $TenantData->condominium_name,
                            'isLoggedIn' => true,
                        ); 
                    $this->session->set_userdata($sessionArray);
                    unset($sessionArray['id'], $sessionArray['isLoggedIn']);
                    $this->index();
                } 
                   
                
                $this->displayLoginFormError($UserValidCode);
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
            redirect('/dashboard');
        }

        $this->load->view('user/forgotPassword', null);
    }

    public function resetPasswordUser()
    {
        $status = '';

        if ($this->isValidResetPasswordForm()) {
            $email = strtolower($this->security->xss_clean($this->input->post('login_email')));
            if ($this->login_model->checkEmailExist($email)) {
                $encoded_email = urlencode($email);

                $this->load->helper('string');
                $data['email'] = $email;
                $data['activation_id'] = random_string('alnum', 15);
                $data['createdDtm'] = date('Y-m-d H:i:s');
                $data['agent'] = getBrowserAgent();
                $data['client_ip'] = $this->input->ip_address();

                $save = $this->login_model->resetPasswordUser($data, $codigoempresa);

                if ($save) {
                    $data1['reset_link'] = base_url() . "resetearcontraconfir/" . $data['activation_id'] . "/" . $encoded_email;
                    $userInfo = $this->login_model->getCustomerInfoByEmail($email);
                    $link = base_url() . "resetearcontraconfir/" . $data['activation_id'] . "/" . $encoded_email;
                    if (!empty($userInfo)) {
                        $data1["name"] = $userInfo->name;
                        $data1["email"] = $userInfo->email;
                        $data1["message"] = "Restablecer su contraseña";
                    }

                    $mensaje = "Buen dia " . $userInfo->name . " para restablecer su contraseña de " . NAME_SYSTEM . " por favor acceda a este link $link  y siga los pasos.";
                    $sendStatus = $this->enviar($userInfo->email, $userInfo->name, "Restablecer contraseña", $mensaje);

                    if ($sendStatus > 0) {
                        $status = "success";
                        setFlashData($status, "El enlace para restablecer la contraseña se envió correctamente, verifique los correos.");
                    } else {
                        $status = "error";
                        setFlashData($status, "El correo electrónico ha fallado, intente nuevamente.");
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

    public function resetPasswordConfirmUser($activation_id, $email)
    {
        // Get email and activation code from URL values at index 3-4
        $email = urldecode($email);
        $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);
        $data['email'] = $email;
        $data['activation_code'] = $activation_id;

        if ($is_correct == 1) {
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
            $this->resetPasswordConfirmUser($origin, $activation_id, urlencode($email));
        } else {
            $password = $this->input->post('password');
            $cpassword = $this->input->post('cpassword');

            // Check activation id in database
            $is_correct = $this->login_model->checkActivationDetails($email, $activation_id);

            if ($is_correct == 1) {
                $this->login_model->createPasswordUser($email, $password);

                $status = 'success';
                $message = 'Restablecimiento de contraseña exitosamente';
            } else {
                $status = 'error';
                $message = 'Restablecimiento de contraseña fallo';
            }

            setFlashData($status, $message);

            redirect("/");
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
