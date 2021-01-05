<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class User extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->isLoggedIn();
        $this->getDataCondominium();
    }

    public function getDataCondominium()
    {
        $this->load->model('condominium_model');
        $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the user list
     */
    public function userListing()
    {
        if (checkForPermissions('users', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['userRecords'] = $this->user_model->Listing($this->id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'users'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de usuario';
            $this->loadViews("user/users", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('users', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo usuario';
            $this->loadViews("user/addNew", $this->globalApp, null, null);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    public function checkEmailExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if (empty($userId)) {
            $result = $this->user_model->checkEmailExists($email, 0, $this->condominium);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new user to the system
     */
    public function addNewUser()
    {
        if (checkForPermissions('users', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('fname', 'Nombre completo', 'trim|required|max_length[128]');
            $this->form_validation->set_rules('email', 'Correo', 'trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('mobile', 'Telefono Movil', 'trim|required|max_length[30]');
            $this->form_validation->set_rules('password', 'Contraseña', 'required|max_length[20]');
            $this->form_validation->set_rules('cpassword', 'Confirmar contraseña', 'trim|required|matches[password]|max_length[20]');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $mobile = strtolower($this->security->xss_clean($this->input->post('mobile')));
                $type = strtolower($this->security->xss_clean($this->input->post('type')));
                $password = $this->input->post('password');
                $generates_report =  ($this->security->xss_clean($this->input->post('generates_report')));
                $userInfo = array('username' => $email, 'mobile' => $mobile, 'condominium' => $this->condominium, 'type' => $type, 'generates_report' => $generates_report, 'password' => getHashedPassword($password), 'name' => ucwords($name), 'permissions' => getPermission($type));
                $result = $this->user_model->addNew($userInfo);

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'Usuario creado exitosamente');
                } else {
                    $this->session->set_flashdata('error', 'Fallo la creacción del usuario');
                }

                redirect('addNew');
            }
        }
    }

    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    public function editOld($userId = null)
    {
        if (checkForPermissions('users', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($userId == null) {
                redirect('usuarios');
            }
            $data['permissions'] = $this->getPermissions()->{'users'};
            $data['userInfo'] = $this->user_model->getInfo($userId, $this->condominium);

            if ($data['userInfo'] == null) {
                redirect('usuarios');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar usuario';

            $this->loadViews("user/editOld", $this->globalApp, $data, null);
        }
    }

    public function editPermissions($userId = null)
    {
        if (checkForPermissions('users', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($userId == null) {
                redirect('usuarios');
            }
            $data['permissions'] = json_decode($this->user_model->getInfo($userId)->permissions, $this->condominium);
            $data['id'] = $this->user_model->getInfo($userId)->id;
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar permisos';

            $this->loadViews("user/permissions", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the user information
     */
    public function editUser()
    {
        if (checkForPermissions('users', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $userId = $this->input->post('userId');

            $this->form_validation->set_rules('fname', 'Nombre completo', 'trim|required|max_length[128]');
            $this->form_validation->set_rules('email', 'Correo', 'trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('mobile', 'Telefono Movil', 'trim|required|max_length[30]');
            $password = $this->input->post('password');
            if (!empty($password)) {
                $this->form_validation->set_rules('password', 'Clave', 'required|max_length[20]');
                $this->form_validation->set_rules('cpassword', 'Confirmar Clave', 'trim|required|matches[password]|max_length[20]');
            }
            if ($this->form_validation->run() == false) {
                $this->editOld($userId);
            } else {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $mobile = ($this->security->xss_clean($this->input->post('mobile')));
                $type = ($this->security->xss_clean($this->input->post('type')));
                $password = $this->input->post('password');
                $generates_report =  ($this->security->xss_clean($this->input->post('generates_report')));
                $userInfo = array();
                if (empty($password)) {
                    $userInfo = array('username' => $email, 'mobile' => $mobile, 'type' => $type, 'generates_report' => $generates_report,'name' => ucwords($name));
                } else {
                    $userInfo = array('username' => $email, 'mobile' => $mobile, 'type' => $type, 'generates_report' => $generates_report,'permissions' => getPermission($type), 'password' => getHashedPassword($password), 'name' => ucwords($name));
                }

                $result = $this->user_model->edit($userInfo, $userId, $this->condominium);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'Usuario actualizado');
                } else {
                    $this->session->set_flashdata('error', 'Fallo actualización');
                }
                redirect("editSelectedUser/" . $userId);
            }
        }
    }

    /**
     * This function is used to edit the user information
     */
    public function editUserPermissions()
    {
        if (checkForPermissions('users', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $userId = $this->input->post('id');
            $permissions = '{"vendors":{"C":' . ($this->input->post('VC') == "on" ? 1 : 0) . ',"R":' . ($this->input->post('VR') == "on" ? 1 : 0) . ',"U":' . ($this->input->post('VU') == "on" ? 1 : 0) . ',"D":' . ($this->input->post('VD') == "on" ? 1 : 0) . '},"clients":{"C":' . ($this->input->post('CC') == "on" ? 1 : 0) . ',"R":' . ($this->input->post('CR') == "on" ? 1 : 0) . ',"U":' . ($this->input->post('CU') == "on" ? 1 : 0) . ',"D":' . ($this->input->post('CD') == "on" ? 1 : 0) . '},"products":{"C":' . ($this->input->post('PC') == "on" ? 1 : 0) . ',"R":' . ($this->input->post('PR') == "on" ? 1 : 0) . ',"U":' . ($this->input->post('PU') == "on" ? 1 : 0) . ',"D":' . ($this->input->post('PD') == "on" ? 1 : 0) . '},"brands":{"C":' . ($this->input->post('BC') == "on" ? 1 : 0) . ',"R":' . ($this->input->post('BR') == "on" ? 1 : 0) . ',"U":' . ($this->input->post('BU') == "on" ? 1 : 0) . ',"D":' . ($this->input->post('BD') == "on" ? 1 : 0) . '},"groups":{"C":' . ($this->input->post('GC') == "on" ? 1 : 0) . ',"R":' . ($this->input->post('GR') == "on" ? 1 : 0) . ',"U":' . ($this->input->post('GU') == "on" ? 1 : 0) . ',"D":' . ($this->input->post('GD') == "on" ? 1 : 0) . '},"routes":{"C":' . ($this->input->post('RC') == "on" ? 1 : 0) . ',"R":' . ($this->input->post('RR') == "on" ? 1 : 0) . ',"U":' . ($this->input->post('RU') == "on" ? 1 : 0) . ',"D":' . ($this->input->post('RD') == "on" ? 1 : 0) . '},"visits":{"C":' . ($this->input->post('VC') == "on" ? 1 : 0) . ',"R":' . ($this->input->post('VR') == "on" ? 1 : 0) . ',"U":' . ($this->input->post('VU') == "on" ? 1 : 0) . ',"D":' . ($this->input->post('VD') == "on" ? 1 : 0) . '},"users":{"C":' . ($this->input->post('UC') == "on" ? 1 : 0) . ',"R":' . ($this->input->post('UR') == "on" ? 1 : 0) . ',"U":' . ($this->input->post('UU') == "on" ? 1 : 0) . ',"D":' . ($this->input->post('UD') == "on" ? 1 : 0) . '},"payments":{"C":' . ($this->input->post('PAC') == "on" ? 1 : 0) . ',"R":' . ($this->input->post('PAR') == "on" ? 1 : 0) . ',"U":' . ($this->input->post('PAU') == "on" ? 1 : 0) . ',"D":' . ($this->input->post('PAD') == "on" ? 1 : 0) . '},"orders_headers":{"C":' . ($this->input->post('OC') == "on" ? 1 : 0) . ',"R":' . ($this->input->post('OR') == "on" ? 1 : 0) . ',"U":' . ($this->input->post('OU') == "on" ? 1 : 0) . ',"D":' . ($this->input->post('OD') == "on" ? 1 : 0) . '},"orders_details":{"C":' . ($this->input->post('OC') == "on" ? 1 : 0) . ',"R":' . ($this->input->post('OR') == "on" ? 1 : 0) . ',"U":' . ($this->input->post('OU') == "on" ? 1 : 0) . ',"D":' . ($this->input->post('OD') == "on" ? 1 : 0) . '},"settings":{"C":' . ($this->input->post('SC') == "on" ? 1 : 0) . ',"R":' . ($this->input->post('SR') == "on" ? 1 : 0) . ',"U":' . ($this->input->post('SU') == "on" ? 1 : 0) . ',"D":' . ($this->input->post('SD') == "on" ? 1 : 0) . '}}';
            $userInfo = array('permissions' => trim($permissions));
            $result = $this->user_model->edit($userInfo, $userId, $this->condominium);

            if ($result == true) {
                $this->session->set_flashdata('success', 'Permisos de usuario actualizado');
            } else {
                $this->session->set_flashdata('error', 'Fallo actualización');
            }

            $this->editPermissions($userId);

        }
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    public function deleteUser()
    {
        if (checkForPermissions('users', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $userId = $this->input->post('userId');
            $result = $this->user_model->delete($userId, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('users', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $userId = $this->input->post('userId');
            $isactive = $this->input->post('isactive');
            $userInfo = array('status' => $isactive);

            $result = $this->user_model->edit($userInfo, $userId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    /**
     * Page not found : error 404
     */
    public function pageNotFound()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' 404 - Page Not Found';

        $this->loadViews("404", $this->globalApp, null, null);
    }

    /**
     * This function used to show login history
     * @param number $userId : This is user id
     */
    public function loginHistoy($userId = null)
    {
        if ($this->isAdmin() == true) {
            $this->loadThis();
        } else {
            $userId = ($userId == null ? 0 : $userId);

            $searchText = $this->input->post('searchText');
            $fromDate = $this->input->post('fromDate');
            $toDate = $this->input->post('toDate');

            $data["userInfo"] = $this->user_model->getInfoById($userId);

            $data['searchText'] = $searchText;
            $data['fromDate'] = $fromDate;
            $data['toDate'] = $toDate;

            $this->load->library('pagination');

            $count = $this->user_model->loginHistoryCount($userId, $searchText, $fromDate, $toDate);

            $returns = $this->paginationCompress("login-history/" . $userId . "/", $count, 10, 3);

            $data['userRecords'] = $this->user_model->loginHistory($userId, $searchText, $fromDate, $toDate, $returns["page"], $returns["segment"]);

            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' User Login History';

            $this->loadViews("login/loginHistory", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to show users profile
     */
    public function profile($active = 'details')
    {
        $data["userInfo"] = $this->user_model->getInfo($this->id);
        $data["active"] = $active;
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Mi Perfil';
        $this->loadViews("user/profile", $this->globalApp, $data, null);
    }

    /**
     * This function is used to update the user details
     * @param text $active : This is flag to set the active tab
     */
    public function profileUpdate($active = "details")
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Nombre completo', 'trim|required|max_length[128]');
        $this->form_validation->set_rules('username', 'Correo electronico', 'trim|required|valid_email|max_length[128]|callback_emailExists');

        if ($this->form_validation->run() == false) {
            $this->profile($active);
        } else {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('name'))));
            $username = strtolower($this->security->xss_clean($this->input->post('username')));

            $userInfo = array('name' => $name, 'username' => $username);
            $result = $this->user_model->edit($userInfo, $this->id, $this->condominium);

            if ($result > 0) {
                $this->session->set_userdata('name', $name);
                $this->session->set_flashdata('success', 'Perfil actualizado');
            } else {
                $this->session->set_flashdata('error', 'Fallo actualización');
            }

            redirect('profile/' . $active);
        }
    }

    /**
     * This function is used to change the password of the user
     * @param text $active : This is flag to set the active tab
     */
    public function changePassword($active = "changepass")
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('oldPassword', 'Old password', 'required|max_length[20]');
        $this->form_validation->set_rules('newPassword', 'New password', 'required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword', 'Confirm new password', 'required|matches[newPassword]|max_length[20]');

        if ($this->form_validation->run() == false) {
            $this->profile($active);
        } else {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');

            $resultPas = $this->user_model->matchOldPassword($this->id, $oldPassword, $this->condominium);

            if (empty($resultPas)) {
                $this->session->set_flashdata('nomatch', 'Your old password is not correct');
                redirect('profile/' . $active);
            } else {
                $usersData = array('password' => getHashedPassword($newPassword));

                $result = $this->user_model->changePassword($this->id, $usersData, $this->condominium);

                if ($result > 0) {$this->session->set_flashdata('success', 'Password updation successful');} else { $this->session->set_flashdata('error', 'Password updation failed');}

                redirect('profile/' . $active);
            }
        }
    }

    /**
     * This function is used to check whether email already exist or not
     * @param {string} $email : This is users email
     */
    public function emailExists($email)
    {
        $userId = $this->id;
        $return = false;

        if (empty($userId)) {
            $result = $this->user_model->checkEmailExists($email, 0, $this->condominium);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId, $this->condominium);
        }

        if (empty($result)) {$return = true;} else {
            $this->form_validation->set_message('emailExists', 'El {field} ya existe');
            $return = false;
        }

        return $return;
    }
}
