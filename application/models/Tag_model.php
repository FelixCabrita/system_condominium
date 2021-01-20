<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Tag_model (tag Model)
 * tag model class to get to handle tag related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Tag_model extends CI_Model
{ 
    protected $db="";

    public function __construct()
    {
        parent::__construct();  
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
    }
    
    /**
     * This function is used to get the tag listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($id,$condominium)
    { 
        $this->db->select('t.id, t.serial,v.licence_plate,t.apartment,a.code ,t.created_at,t.vehicle,  t.status, t.observations');     
        $this->db->from('tag as t');      
        $this->db->join('vehicles as v', 'v.id = t.vehicle  AND v.condominium = t.condominium','left'); 
        $this->db->join('apartment as a', 'a.id = t.apartment  AND a.condominium = t.condominium');
        $this->db->where('t.condominium',$condominium);
        $this->db->order_by('t.id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $tagId : This is tag id
     * @return {mixed} $result : This is searched result
     */
    function checkExists( $id_apartment,$condominium)
    {
        $this->db->select("IFNULL(MAX(id),0) as 'max'");
        $this->db->from("tag"); 
        $this->db->where('condominium',$condominium); 
        $this->db->where("apartment", $id_apartment); 
        $query = $this->db->get(); 
        return $query->row();
    } 
    /**
     * This function is used to add new tag to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($tagInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tag', $tagInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    /**
     * This function used to get tag information by id
     * @param number $tagId : This is tag id
     * @return array $result : This is tag information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('tag'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }
  
    /**
     * This function is used to update the tag information
     * @param array $tagInfo : This is tags updated information
     * @param number $tagId : This is tag id
     */
    function edit($tagInfo, $tagId,$condominium)
    {
        $this->db->where('id', $tagId);
        $this->db->where('condominium',$condominium);
        $this->db->update('tag', $tagInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the tag information
     * @param number $tagId : This is tag id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($tagId,$condominium)
    {
        $this->db->where('id', $tagId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('tag'); 
        return $this->db->affected_rows();
    } 
}

  