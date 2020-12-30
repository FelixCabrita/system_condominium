<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Commonarea_model (module Model)
 * module model class to get to handle module related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Module_model extends CI_Model
{ 
    /**
     * This function is used to get the module listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($id,$condominium)
    { 
        $this->db->from('module');     
        $this->db->where('condominium',$condominium);
        $this->db->order_by('id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    public function getListing($condominium)
    {  
        $this->db->select('id,code,name'); 
        $this->db->from('module'); 
        $this->db->where('condominium',$condominium);
        $this->db->where("status",1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $moduleId : This is module id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($code, $id = 0,$condominium)
    {
        $this->db->select("code");
        $this->db->from("module");
        $this->db->where("code", $code);   
        $this->db->where('condominium',$condominium);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    } 
    /**
     * This function is used to add new module to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($moduleInfo)
    {
        $this->db->trans_start();
        $this->db->insert('module', $moduleInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    /**
     * This function used to get module information by id
     * @param number $moduleId : This is module id
     * @return array $result : This is module information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('module'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getInfoCode($code,$condominium)
    { 
        $this->db->from('module'); 
        $this->db->where('code', $code);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }
  
    /**
     * This function is used to update the module information
     * @param array $moduleInfo : This is modules updated information
     * @param number $moduleId : This is module id
     */
    function edit($moduleInfo, $moduleId,$condominium)
    {
        $this->db->where('id', $moduleId);
        $this->db->where('condominium',$condominium);
        $this->db->update('module', $moduleInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the module information
     * @param number $moduleId : This is module id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($moduleId,$condominium)
    {
        $this->db->where('id', $moduleId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('module'); 
        return $this->db->affected_rows();
    } 
}

  