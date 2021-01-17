<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Commonarea_model (commonarea Model)
 * commonarea model class to get to handle commonarea related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Commonarea_model extends CI_Model
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
     * This function is used to get the commonarea listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($id,$condominium)
    { 
        $this->db->from('commonarea');     
        $this->db->where('condominium',$condominium);
        $this->db->order_by('id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    public function getListing($condominium)
    {  
        $this->db->select('id,name,max_person'); 
        $this->db->from('commonarea'); 
        $this->db->where('condominium',$condominium);
        $this->db->where("status",1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $commonareaId : This is commonarea id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($code, $id = 0,$condominium)
    {
        $this->db->select("code");
        $this->db->from("commonarea");
        $this->db->where("code", $code);   
        $this->db->where('condominium',$condominium);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    } 
    /**
     * This function is used to add new commonarea to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($commonareaInfo)
    {
        $this->db->trans_start();
        $this->db->insert('commonarea', $commonareaInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    /**
     * This function used to get commonarea information by id
     * @param number $commonareaId : This is commonarea id
     * @return array $result : This is commonarea information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('commonarea'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }
  
    /**
     * This function is used to update the commonarea information
     * @param array $commonareaInfo : This is commonareas updated information
     * @param number $commonareaId : This is commonarea id
     */
    function edit($commonareaInfo, $commonareaId,$condominium)
    {
        $this->db->where('id', $commonareaId);
        $this->db->where('condominium',$condominium);
        $this->db->update('commonarea', $commonareaInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the commonarea information
     * @param number $commonareaId : This is commonarea id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($commonareaId,$condominium)
    {
        $this->db->where('id', $commonareaId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('commonarea'); 
        return $this->db->affected_rows();
    } 
}

  