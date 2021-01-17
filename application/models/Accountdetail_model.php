<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : account_detail (account_detail Model)
 * account_detail model class to get to handle account_detail related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Accountdetail_model extends CI_Model
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
     * This function is used to get the account_detail listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($account_control,$condominium)
    { 
        $this->db->select('ad.id, ad.condominium, ad.code, ad.name, ad.account_higher, ad.status, ad.created_at,ah.code as \'code_higher\', ah.name as \'name_higher\' ');    
        $this->db->from('account_detail as ad');    
        $this->db->join('account_higher as ah', 'ah.id = ad.account_higher AND ah.condominium = ad.condominium'); 
        $this->db->where('ad.condominium',$condominium);
        if(!empty($account_control)){
            $this->db->where("ad.account_higher", $account_control);
        }
        $this->db->order_by('ad.code', 'ASC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    public function getListing($account_control='',$condominium)
    {  
        $this->db->select('ad.id, ad.condominium, ad.code, ad.name, ad.account_higher, ad.status, ad.created_at,ah.code as \'code_higher\', ah.name as \'name_higher\' ');    
        $this->db->from('account_detail as ad');    
        $this->db->join('account_higher as ah', 'ah.id = ad.account_higher AND ah.condominium = ad.condominium'); 
        $this->db->where('ad.condominium',$condominium);
        if(!empty($account_control)){
            $this->db->where("ad.account_higher", $account_control);
        }
        $this->db->where('ad.status',1);
        $this->db->order_by('ad.code', 'ASC'); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $account_detailId : This is account_detail id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($code, $id = 0,$condominium)
    {
        $this->db->select("code");
        $this->db->from("account_detail");
        $this->db->where("code", $code);   
        $this->db->where('condominium',$condominium);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    } 
    /**
     * This function is used to add new account_detail to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($account_detailInfo)
    {
        $this->db->trans_start();
        $this->db->insert('account_detail', $account_detailInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    /**
     * This function used to get account_detail information by id
     * @param number $account_detailId : This is account_detail id
     * @return array $result : This is account_detail information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('account_detail'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getInfoCode($code,$condominium)
    { 
        $this->db->from('account_detail'); 
        $this->db->where('code', $code);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }
  
    /**
     * This function is used to update the account_detail information
     * @param array $account_detailInfo : This is account_details updated information
     * @param number $account_detailId : This is account_detail id
     */
    function edit($account_detailInfo, $account_detailId,$condominium)
    {
        $this->db->where('id', $account_detailId);
        $this->db->where('condominium',$condominium);
        $this->db->update('account_detail', $account_detailInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the account_detail information
     * @param number $account_detailId : This is account_detail id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($account_detailId,$condominium)
    {
        $this->db->where('id', $account_detailId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('account_detail'); 
        return $this->db->affected_rows();
    } 
}

  