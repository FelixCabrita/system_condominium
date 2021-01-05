<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : directive_model (directive Model)
 * directive model class to get to handle directive related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Directive_model extends CI_Model
{ 
    /**
     * This function is used to get the directive listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($id,$condominium,$status=1)
    { 
        $this->db->select("d.id, d.name, d.edifice, d.position,  d.document, d.chargue, p.name as 'period', d.created_at, d.status, a.apartment,e.code,d.photo_1");
        $this->db->from('directive d');       
        $this->db->join('edifice as e', 'e.id = d.edifice  AND e.condominium = d.condominium');
        $this->db->join('apartment as a', 'a.id = d.apartment  AND a.condominium = d.condominium');
        $this->db->join('period as p', 'p.id = d.period  AND p.condominium = d.condominium');
        $this->db->where('d.condominium',$condominium);
        if($status!=0){
            $this->db->where('p.status',$status);
        } 
        $this->db->order_by('d.position', 'ASC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $directiveId : This is directive id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($document, $id = 0,$condominium)
    {
        $this->db->select("document");
        $this->db->from("directive");
        $this->db->where("document", $document);   
        $this->db->where('condominium',$condominium);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    } 
    /**
     * This function is used to add new directive to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($directiveInfo)
    {
        $this->db->trans_start();
        $this->db->insert('directive', $directiveInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get directive information by id
     * @param number $directiveId : This is directive id
     * @return array $result : This is directive information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->select("d.id, d.name, d.edifice, d.document, d.chargue, d.period, d.created_at, d.status,d.position, d.apartment,
                            concat(e.code,' - ',e.manager) as 'edifice_code', a.apartment as 'apartment_code',d.photo_1");
        $this->db->from('directive d');       
        $this->db->join('edifice as e', 'e.id = d.edifice  AND e.condominium = d.condominium' );
        $this->db->join('apartment as a', 'a.id = d.apartment  AND a.condominium = d.condominium');
        $this->db->where('d.condominium',$condominium);
        $this->db->where('d.id', $id); 
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the directive information
     * @param array $directiveInfo : This is directives updated information
     * @param number $directiveId : This is directive id
     */
    function edit($directiveInfo, $directiveId,$condominium)
    {
        $this->db->where('id', $directiveId);
        $this->db->where('condominium',$condominium);
        $this->db->update('directive', $directiveInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the directive information
     * @param number $directiveId : This is directive id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($directiveId,$condominium)
    {
        $this->db->where('id', $directiveId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('directive'); 
        return $this->db->affected_rows();
    } 
}

  