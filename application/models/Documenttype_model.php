<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Commonarea_model (document_type Model)
 * document_type model class to get to handle document_type related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Documenttype_model extends CI_Model
{ 
    /**
     * This function is used to get the document_type listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($module,$condominium)
    { 
        $this->db->select('dt.id, dt.module, dt.code, dt.name, dt.status,dt.created_at,m.code as \'code_module\' ');    
        $this->db->from('document_type as dt');    
        $this->db->join('module as m', 'm.id = dt.module'); 
        $this->db->where('dt.condominium',$condominium);
        if(!empty($module)){
            $this->db->where("dt.module", $module);
        }
        $this->db->order_by('dt.id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    public function getListing($module='',$condominium)
    {  
        $this->db->select('dt.id, dt.module, dt.code, dt.name, dt.created_at,m.code as \'code_module\' ');    
        $this->db->from('document_type as dt');    
        $this->db->join('module as m', 'm.id = dt.module'); 
        $this->db->where('dt.condominium',$condominium);
        if(!empty($module)){
            $this->db->where("dt.module", $module);
        }
        $this->db->where("dt.status",1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $document_typeId : This is document_type id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($code, $id = 0,$condominium)
    {
        $this->db->select("code");
        $this->db->from("document_type");
        $this->db->where("code", $code);   
        $this->db->where('condominium',$condominium);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    } 
    /**
     * This function is used to add new document_type to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($document_typeInfo)
    {
        $this->db->trans_start();
        $this->db->insert('document_type', $document_typeInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    /**
     * This function used to get document_type information by id
     * @param number $document_typeId : This is document_type id
     * @return array $result : This is document_type information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('document_type'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }
  
    /**
     * This function is used to update the document_type information
     * @param array $document_typeInfo : This is document_types updated information
     * @param number $document_typeId : This is document_type id
     */
    function edit($document_typeInfo, $document_typeId,$condominium)
    {
        $this->db->where('id', $document_typeId);
        $this->db->where('condominium',$condominium);
        $this->db->update('document_type', $document_typeInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the document_type information
     * @param number $document_typeId : This is document_type id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($document_typeId,$condominium)
    {
        $this->db->where('id', $document_typeId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('document_type'); 
        return $this->db->affected_rows();
    } 
}

  