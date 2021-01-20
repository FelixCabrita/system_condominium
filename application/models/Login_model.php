<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class : Login_model (Login Model)
 * Login model class to get to authenticate user credentials
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Login_model extends CI_Model
{

    /**
     * This function used to check the login credentials of the user
     * @param string $email : This is email of the user
     * @param string $password : This is encrypted password of the user
     */
    public function LoginUser($email, $password, $condominium)
    {
        if(!isset( $_COOKIE['workstation_fscv_'])){
            return 0;
        }$db = $_COOKIE['workstation_fscv_'];
        $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("u.id,IFNULL(u.condominium,'0') as 'condominium',u.username,u.password,u.permissions,u.generates_report,u.name,u.status,u.type");
        $this->db->from('users as u');
        $this->db->where('u.username', $email);
        if ($condominium != 'OWNER') {
            $this->db->where('u.condominium', $condominium);
        }

        $query = $this->db->get();

        $user = $query->row();

        if (!empty($user)) {
            if (verifyHashedPassword($password, $user->password)) {
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    public function LoginTenant($email, $password, $condominium)
    {
        if(!isset( $_COOKIE['workstation_fscv_'])){
            return 0;
        }$db = $_COOKIE['workstation_fscv_'];
        $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("t.id, t.condominium, t.document, t.name, t.mobile,   t.password, t.type, t.created_at, t.status");
        $this->db->from('tenant as t'); 
        $this->db->where('t.document', $email);
        $this->db->where('t.condominium', $condominium);
        $query = $this->db->get();
        $user = $query->row();

        if (!empty($user)) {
            if (verifyHashedPassword(strtoupper($password), $user->password)) {
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    public function ejectSQL($sql, $code)
    {
        if(!isset( $_COOKIE['workstation_fscv_'])){
            return 0;
        }$db = $_COOKIE['workstation_fscv_'];
        $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db = $this->load->database('', true, null, $code);
        $this->db->query($sql);
    }

    /**
     * This function used to check email exists or not
     * @param {string} $email : This is users email id
     * @return {boolean} $result : TRUE/FALSE
     */
    public function checkEmailExist($email)
    { 
        if ($_COOKIE['workstation_fscv'] == 'OWNER') {
            $this->db = $this->load->database('', true, null, NAME_DB);
        } else {
            if(!isset( $_COOKIE['workstation_fscv_'])){
                return 0;
            } $db = $_COOKIE['workstation_fscv_'];
            $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($db, 5, '0', STR_PAD_LEFT));
        }
        $this->db->select('id,condominium');
        $this->db->where('username', $email);
        $query = $this->db->get('users'); 
        return $query->row();
    }

    public function checkEmailExistClient($email)
    {
        if(!isset( $_COOKIE['workstation_fscv_'])){
            return 0;
        } $db = $_COOKIE['workstation_fscv_'];
        $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select('id,condominium');
        $this->db->where('email', $email);
        $query = $this->db->get('tenant'); 
        return $query->row();
    }

    /**
     * This function used to insert reset password data
     * @param {array} $data : This is reset password data
     * @return {boolean} $result : TRUE/FALSE
     */
    public function resetPasswordUser($data)
    {

        if ($_COOKIE['workstation_fscv'] == 'OWNER') {
            $this->db = $this->load->database('', true, null, NAME_DB);
        } else { if(!isset( $_COOKIE['workstation_fscv_'])){
            return 0;
        }$db = $_COOKIE['workstation_fscv_'];
            $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($db, 5, '0', STR_PAD_LEFT));
        }
        $result = $this->db->insert('reset_password', $data);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function is used to get customer information by email-id for forget password email
     * @param string $email : Email id of customer
     * @return object $result : Information of customer
     */
    public function getCustomerInfoByEmail($email)
    {

        if ($_COOKIE['workstation_fscv'] == 'OWNER') {
            $this->db = $this->load->database('', true, null, NAME_DB);
        } else { if(!isset( $_COOKIE['workstation_fscv_'])){
            return 0;
        }$db = $_COOKIE['workstation_fscv_'];
            $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($db, 5, '0', STR_PAD_LEFT));
        }
        $this->db->from('users');
        $this->db->where('username', $email);
        $query = $this->db->get(); 
        return $query->row();
    }

    /**
     * This function is used to get customer information by email-id for forget password email
     * @param string $email : Email id of customer
     * @return object $result : Information of customer
     */
    public function getCustomerInfoByEmailTenant($email)
    {
        if(!isset( $_COOKIE['workstation_fscv_'])){
            return 0;
        }$db = $_COOKIE['workstation_fscv_'];
        $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->from('tenant');
        $this->db->where('email', $email);
        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This function used to check correct activation deatails for forget password.
     * @param string $email : Email id of user
     * @param string $activation_id : This is activation string
     */
    public function checkActivationDetails($owner,$email, $activation_id)
    { 
        if ($owner == 'OWNER') {
            $this->db = $this->load->database('', true, null, NAME_DB);
        } else {  
            $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($owner, 5, '0', STR_PAD_LEFT));
        }
        $this->db->from('reset_password');
        $this->db->where('email', $email);
        $this->db->where('activation_id', $activation_id);
        $query = $this->db->get();
        return $query->row();
    }

    // This function used to create new password by reset link
    public function createPasswordUser($email, $password, $condominium)
    {

        if ($_COOKIE['workstation_fscv'] == 'OWNER') {
            $this->db = $this->load->database('', true, null, NAME_DB);
            $this->db->where('username', $email); 
            $this->db->update('users', array('password' => getHashedPassword($password)));


            $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($condominium, 5, '0', STR_PAD_LEFT));
            $this->db->where('username', $email);
            $this->db->where('id', $condominium);
            $this->db->update('users', array('password' => getHashedPassword($password)));
            return $this->db->affected_rows();
        } else {
            if(!isset( $_COOKIE['workstation_fscv'])){
                return 0;
            } 
            $db = $_COOKIE['workstation_fscv'];
            $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($db, 5, '0', STR_PAD_LEFT));
            $this->db->where('username', $email); 
            $this->db->update('users', array('password' => getHashedPassword($password)));
            return $this->db->affected_rows();
        }
       
    }

    // This function used to create new password by reset link
    public function createPasswordTenant($email, $password)
    {

        if ($_COOKIE['workstation_fscv'] == 'OWNER') {
            $this->db = $this->load->database('', true, null, NAME_DB);
        } else { $db = $_COOKIE['workstation_fscv'];
            $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($db, 5, '0', STR_PAD_LEFT));
        }
        $this->db->where('email', $email);
        $this->db->update('tenant', array('password' => getHashedPassword($password)));
        return $this->db->affected_rows();
    }

    /**
     * This function used to save login information of user
     * @param array $loginInfo : This is users login information
     */
    public function lastLogin($loginInfo)
    {$db = $_COOKIE['workstation_fscv_'];
        $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($db, 5, '0', STR_PAD_LEFT));

        $this->db->trans_start();
        $this->db->insert('last_login', $loginInfo);
        $this->db->trans_complete();
    }

    /**
     * This function is used to get last login info by user id
     * @param number $userId : This is user id
     * @return number $result : This is query result
     */
    public function lastLoginInfo($userId)
    {
        $db = $_COOKIE['workstation_fscv_'];
        $this->db = $this->load->database('', true, null, ALIAS_ADMIN . str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select('created_at');
        $this->db->where('id', $userId);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('last_login as BaseTbl');

        return $query->row();
    }

    public function gettInfo($id)
    {
        $this->db = $this->load->database('', true, null, NAME_DB);
        $this->db->from('condominium');
        $this->db->where('code', $id);
        $query = $this->db->get();
        return $query->row();
    }
 

    public function gettInfoAIDC($id)
    {
        $this->db = $this->load->database('', true, null, NAME_DB);
        $this->db->from('condominium');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    /* OWNER */

    public function LoginOWNER($email, $password)
    {
        $this->db = $this->load->database('', true, null, NAME_DB);
        $this->db->select("u.id,IFNULL(u.condominium,'0') as 'condominium',u.username,u.password,u.permissions,1 as generates_report,u.name,u.status,1 as type,
         IFNULL(c.name,'SIN CONDOMINIO') as 'condominium_name',IFNULL(c.max_user_tenant,'0') as 'max_user_tenant',IFNULL(c.generate_debit,'0') as 'generate_debit',IFNULL(c.generate_credit,'0') as 'generate_credit'");
        $this->db->join('condominium as c', 'c.id = u.condominium', 'left');
        $this->db->from('users as u');
        $this->db->where('username', $email);
        $query = $this->db->get();

        $user = $query->row();

        if (!empty($user)) {
            if (verifyHashedPassword($password, $user->password)) {
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

}
