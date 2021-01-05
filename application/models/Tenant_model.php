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


    public function getListing($condominium)
    {  
        $this->db->select('t.id, t.name, t.document'); 
        $this->db->from('tenant as t');  
        $this->db->where("t.status",1);
        $this->db->where('t.condominium',$condominium); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 

    function Assignment($id,$condominium,$_id ='')
    { 
        $this->db->select("asi.id, asi.edifice, asi.apartment, asi.tenant, asi.status, asi.created_at, asi.updated_at, asi.observation
                          ,e.code as 'edifice_code',a.apartment as 'apartment_code'");
        $this->db->from('assigning_apartment as asi');     
        $this->db->join('edifice as e', 'e.id = asi.edifice  AND e.condominium = asi.condominium');
        $this->db->join('apartment as a', 'a.id = asi.apartment  AND a.condominium = asi.condominium');
        $this->db->where('asi.condominium',$condominium);
        $this->db->where('asi.tenant',$id);
        if(!empty($_id)){
            $this->db->where('asi.id',$_id);
            $query = $this->db->get(); 
            return $query->row();
        }else{
            $this->db->order_by(' asi.status,asi.id', 'DESC'); 
            $query = $this->db->get(); 
            $result = $query->result();        
            return $result;
        }
    
    }

    function AssignmentActive($id,$condominium,$_id ='')
    { 
        $this->db->select("asi.id ");
        $this->db->from('assigning_apartment as asi');   
        $this->db->where('asi.condominium',$condominium);
        $this->db->where('asi.tenant',$id);
        $this->db->where('asi.status',1);
        if(!empty($_id)){
            $this->db->where('asi.id',$_id);
            $query = $this->db->get(); 
            return $query->row();
        }else{
            $this->db->order_by(' asi.status,asi.id', 'DESC'); 
            $query = $this->db->get(); 
            $result = $query->result();        
            return $result;
        }
    
    }

    function getAssignment($id,$condominium, $edifice='')
    { 
        $this->db->select("asi.id, asi.edifice, asi.apartment, asi.tenant, asi.status ,e.code as 'edifice_code',a.apartment as 'apartment_code'");
        $this->db->from('assigning_apartment as asi');     
        $this->db->join('edifice as e', 'e.id = asi.edifice  AND e.condominium = asi.condominium');
        $this->db->join('apartment as a', 'a.id = asi.apartment  AND a.condominium = asi.condominium');
        $this->db->where('asi.condominium',$condominium);
        if (!empty($edifice)) {
            $this->db->where("asi.edifice",$edifice);
        }
        $this->db->where('asi.tenant',$id);   
        $this->db->where('asi.status',1); 
        $this->db->order_by(' asi.status,asi.id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result; 
    }
    
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $Id : This is tenant id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($document, $id = 0,$condominium)
    {
        $this->db->select("name,document,type,password");
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
    function addNew($Info)
    {
        $this->db->trans_start();
        $this->db->insert('tenant', $Info);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
  
    /**
     * This function used to get tenant information by id
     * @param number $Id : This is tenant id
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
        $this->db->where("UPPER(document) = UPPER('$document')");
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }
    
    
    
    /**
     * This function is used to update the tenant information
     * @param array $Info : This is tenants updated information
     * @param number $Id : This is tenant id
     */
    function edit($Info, $Id,$condominium)
    {
        $this->db->where('id', $Id);
        $this->db->where('condominium',$condominium);
        $this->db->update('tenant', $Info); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the tenant information
     * @param number $Id : This is tenant id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($Id,$condominium)
    {
        $this->db->where('id', $Id); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('tenant'); 
        return $this->db->affected_rows();
    } 
      /**
     * This function is used to add new tenant to system
     * @return number $insert_id : This is last inserted id
     */
    function set_asignation($Info)
    {
        $this->db->trans_start();
        $this->db->insert('assigning_apartment', $Info);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    function edit_asingnation($Info, $Id,$condominium)
    {
        $this->db->where('id', $Id);
        $this->db->where('condominium',$condominium);
        $this->db->update('assigning_apartment', $Info); 
        return $this->db->affected_rows();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $Id : This is tenant id
     * @return {mixed} $result : This is searched result
     */
    function checkExistsAsignation($edifice, $apartment , $tenant ,$condominium)
    {
        $this->db->select("id");
        $this->db->from("assigning_apartment");
        $this->db->where("edifice", $edifice);   
        $this->db->where("apartment", $apartment);  
        $this->db->where("tenant", $tenant);  
        $this->db->where('condominium',$condominium);
        $this->db->where('status',1);
        $query = $this->db->get(); 
        return $query->result();
    } 

    function checkExistsAsignationOwner($edifice, $apartment, $condominium)
    {
        $this->db->select("asi.id");
        $this->db->from("assigning_apartment as asi");
        $this->db->join('tenant as t', 't.id = asi.tenant  AND t.condominium = asi.condominium');
        $this->db->where("asi.edifice", $edifice);   
        $this->db->where("asi.apartment", $apartment);   
        $this->db->where('asi.condominium',$condominium);
        $this->db->where('asi.status',1); 
        $this->db->where('t.type',ROLE_OWNER); 
        $query = $this->db->get(); 
        return $query->result();
    } 

    function getInfoAssignment($id,$condominium)
    { 
        $this->db->select("asi.id, asi.edifice, asi.apartment, asi.tenant, asi.status");
        $this->db->from('assigning_apartment as asi'); 
        $this->db->where('asi.condominium',$condominium);
        $this->db->where('asi.id',$id);   
        $this->db->order_by(' asi.status,asi.id', 'DESC'); 
        $query = $this->db->get(); 
        return $query->row();
    }
}

  