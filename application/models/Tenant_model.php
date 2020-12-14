<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : tenant_model (tenant Model)
 * tenant model class to get to handle tenant related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class tenant_model extends CI_Model
{ 
    /**
     * This function is used to get the tenant listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($id,$condominium)
    { 
        $this->db->from('tenant');     
        $this->db->where('condominium',$condominium);
        $this->db->order_by('id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $tenantId : This is tenant id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($document, $id = 0,$condominium)
    {
        $this->db->select("document");
        $this->db->from("tenant");
        $this->db->where("document", $document);   
        $this->db->where('condominium',$condominium);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    } 
    /**
     * This function is used to add new tenant to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($tenantInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tenant', $tenantInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
  
    /**
     * This function used to get tenant information by id
     * @param number $tenantId : This is tenant id
     * @return array $result : This is tenant information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('tenant'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getInfodocument($document,$condominium)
    { 
        $this->db->from('tenant'); 
        $this->db->where('document', $document);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    
    /**
     * This function is used to update the tenant information
     * @param array $tenantInfo : This is tenants updated information
     * @param number $tenantId : This is tenant id
     */
    function edit($tenantInfo, $tenantId,$condominium)
    {
        $this->db->where('id', $tenantId);
        $this->db->where('condominium',$condominium);
        $this->db->update('tenant', $tenantInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the tenant information
     * @param number $tenantId : This is tenant id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($tenantId,$condominium)
    {
        $this->db->where('id', $tenantId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('tenant'); 
        return $this->db->affected_rows();
    } 
}

  