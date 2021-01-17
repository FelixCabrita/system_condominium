<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : account_control (account_control Model)
 * account_control model class to get to handle account_control related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Accountcontrol_model extends CI_Model
{ 
    protected $db="";

    public function __construct()
    {
        parent::__construct();  
        if(!isset($_COOKIE['workstation_fscv_'])){
            $this->session->sess_destroy();
            redirect('login');
        }
        $db = $_COOKIE['workstation_fscv_'];
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
    }

    /**
     * This function is used to get the account_control listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($heading,$condominium)
    { 
        $this->db->select('acc.id, acc.condominium, acc.account, acc.name, acc.heading, acc.status, acc.created_at,h.code as \'code_heading\', h.name as \'name_heading\' ');    
        $this->db->from('account_control as acc');    
        $this->db->join('heading as h', 'h.id = acc.heading AND h.condominium = acc.condominium'); 
        $this->db->where('acc.condominium',$condominium);
        if(!empty($heading)){
            $this->db->where("acc.heading", $heading);
        }
        $this->db->order_by('acc.status', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    public function getListing($heading='',$condominium)
    {  
        $this->db->select(' acc.id, acc.condominium, acc.account, acc.name, acc.heading, acc.status, acc.created_at,h.code as \'code_heading\', h.name as \'name_heading\' ');    
        $this->db->from('account_control as acc');    
        $this->db->join('heading as h', 'h.id = acc.heading AND h.condominium = acc.condominium'); 
        $this->db->where('acc.condominium',$condominium);
        if(!empty($heading)){
            $this->db->where("acc.heading", $heading);
        }
        $this->db->order_by('acc.status', 'DESC'); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $account_controlId : This is account_control id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($code, $id = 0,$condominium)
    {
        $this->db->select("account");
        $this->db->from("account_control");
        $this->db->where("account", $code);   
        $this->db->where('condominium',$condominium);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    } 
    /**
     * This function is used to add new account_control to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($account_controlInfo)
    {
        $this->db->trans_start();
        $this->db->insert('account_control', $account_controlInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    /**
     * This function used to get account_control information by id
     * @param number $account_controlId : This is account_control id
     * @return array $result : This is account_control information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('account_control'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }
    function getInfoCode($code,$condominium)
    { 
        $this->db->from('account_control'); 
        $this->db->where('account', $code);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }
    /**
     * This function is used to update the account_control information
     * @param array $account_controlInfo : This is account_controls updated information
     * @param number $account_controlId : This is account_control id
     */
    function edit($account_controlInfo, $account_controlId,$condominium)
    {
        $this->db->where('id', $account_controlId);
        $this->db->where('condominium',$condominium);
        $this->db->update('account_control', $account_controlInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the account_control information
     * @param number $account_controlId : This is account_control id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($account_controlId,$condominium)
    {
        $this->db->where('id', $account_controlId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('account_control'); 
        return $this->db->affected_rows();
    } 
}

  