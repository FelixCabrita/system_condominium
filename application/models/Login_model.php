<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

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
    function LoginUser($email, $password)
    {  
        $this->db->select("u.id,IFNULL(u.condominium,'0') as 'condominium' ,u.username,u.password,u.permissions,u.name,u.status,u.type, IFNULL(c.name,'SIN CONDOMINIO') as 'condominium_name'"); 
        $this->db->join('condominium as c', 'c.id = u.condominium','left');
        $this->db->from('users as u'); 
        $this->db->where('username', $email); 
        $query = $this->db->get();
        
        $user = $query->row();
        
        if(!empty($user)){
            if(verifyHashedPassword($password, $user->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    function LoginTenant($email, $password)
    {  
        $this->db->select("t.id, t.condominium, t.document, t.name, t.mobile,  t.password, t.type, t.created_at, t.status"); 
        $this->db->join('condominium as c', 'c.id = t.condominium');
        $this->db->from('tenant as t'); 
        $this->db->where('t.document', $email); 
        $query = $this->db->get();
        
        $user = $query->row();
        
        if(!empty($user)){
            if(verifyHashedPassword(strtoupper($password), $user->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }


    function ejectSQL($sql,$code)
    {
        $this->db = $this->load->database('', true,null,$code);
        $this->db->query($sql);
    }
 
    /**
     * This function used to check email exists or not
     * @param {string} $email : This is users email id
     * @return {boolean} $result : TRUE/FALSE
     */
    function checkEmailExist($email)
    { 
        $this->db->select('id');
        $this->db->where('username', $email); 
        $query = $this->db->get('users');

        if ($query->num_rows() > 0){
            return true;
        } else {
            return false;
        }
    }


    /**
     * This function used to insert reset password data
     * @param {array} $data : This is reset password data
     * @return {boolean} $result : TRUE/FALSE
     */
    function resetPasswordUser($data)
    {
         
        $result = $this->db->insert('reset_password', $data);

        if($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * This function is used to get customer information by email-id for forget password email
     * @param string $email : Email id of customer
     * @return object $result : Information of customer
     */
    function getCustomerInfoByEmail($email)
    { 
        $this->db->from('users'); 
        $this->db->where('username', $email);
        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This function used to check correct activation deatails for forget password.
     * @param string $email : Email id of user
     * @param string $activation_id : This is activation string
     */
    function checkActivationDetails($email, $activation_id)
    {
        
        $this->db->select('id');
        $this->db->from('reset_password');
        $this->db->where('email', $email);
        $this->db->where('activation_id', $activation_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // This function used to create new password by reset link
    function createPasswordUser($email, $password)
    {
        
        $this->db->where('username', $email); 
        $this->db->update('users', array('password'=>getHashedPassword($password))); 
    }

    /**
     * This function used to save login information of user
     * @param array $loginInfo : This is users login information
     */
    function lastLogin($loginInfo)
    {
        
        $this->db->trans_start();
        $this->db->insert('last_login', $loginInfo);
        $this->db->trans_complete();
    }

    /**
     * This function is used to get last login info by user id
     * @param number $userId : This is user id
     * @return number $result : This is query result
     */
    function lastLoginInfo($userId)
    {
        
        $this->db->select('created_at');
        $this->db->where('id', $userId);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('last_login as BaseTbl');

        return $query->row();
    }
}

?>