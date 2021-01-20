<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Commonarea_model (heading Model)
 * heading model class to get to handle heading related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Heading_model extends CI_Model
{   
    protected $db="";

    public function __construct()
    {
        parent::__construct();  
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
    }
    /**
     * This function is used to get the heading listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($id,$condominium)
    { 
        $this->db->from('heading');     
        $this->db->where('condominium',$condominium);
        $this->db->order_by('status', 'DESC');  
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    public function getListing($condominium)
    {  
        $this->db->select('id ,name,code'); 
        $this->db->from('heading'); 
        $this->db->where('condominium',$condominium);
        $this->db->where("status",1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $headingId : This is heading id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($code, $id = 0,$condominium)
    {
        $this->db->select("code");
        $this->db->from("heading");
        $this->db->where("code", $code);   
        $this->db->where('condominium',$condominium);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    } 
    /**
     * This function is used to add new heading to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($headingInfo)
    {
        $this->db->trans_start();
        $this->db->insert('heading', $headingInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    /**
     * This function used to get heading information by id
     * @param number $headingId : This is heading id
     * @return array $result : This is heading information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('heading'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getInfoCode($code,$condominium)
    { 
        $this->db->from('heading'); 
        $this->db->where('code', $code);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }
  
  
    /**
     * This function is used to update the heading information
     * @param array $headingInfo : This is headings updated information
     * @param number $headingId : This is heading id
     */
    function edit($headingInfo, $headingId,$condominium)
    {
        $this->db->where('id', $headingId);
        $this->db->where('condominium',$condominium);
        $this->db->update('heading', $headingInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the heading information
     * @param number $headingId : This is heading id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($headingId,$condominium)
    {
        $this->db->where('id', $headingId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('heading'); 
        return $this->db->affected_rows();
    } 
}

  