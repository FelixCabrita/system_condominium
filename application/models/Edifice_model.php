<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Edifice_model (Edifice Model)
 * Edifice model class to get to handle Edifice related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Edifice_model extends CI_Model
{ 
    /**
     * This function is used to get the Edifice listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($id,$condominium)
    { 
        $this->db->select(" e.id, e.condominium, e.code, e.edifice, e.block, e.manager, IFNULL(t.name,'') as 'name', e.observation, e.created_at , e.status");
        $this->db->from('edifice as e');     
        $this->db->join('tenant as t', 'UPPER(t.document) = UPPER(e.manager)  AND t.condominium = e.condominium','left');
        $this->db->where('e.condominium',$condominium);
        $this->db->order_by('e.id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $EdificeId : This is Edifice id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($code, $id = 0,$condominium)
    {
        $this->db->select("code");
        $this->db->from("edifice");
        $this->db->where("code", $code);   
        $this->db->where('condominium',$condominium);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    } 
    /**
     * This function is used to add new Edifice to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($EdificeInfo)
    {
        $this->db->trans_start();
        $this->db->insert('edifice', $EdificeInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    public function getListing($condominium)
    {  
        $this->db->select('e.id,e.code,e.manager, IFNULL(t.name, e.manager) as name'); 
        $this->db->from('edifice as e'); 
        $this->db->join('tenant as t', 'UPPER(t.document) = UPPER(e.manager) AND t.condominium = e.condominium','LEFT');
        $this->db->where('e.condominium',$condominium);
        $this->db->where("e.status",1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    /**
     * This function used to get Edifice information by id
     * @param number $EdificeId : This is Edifice id
     * @return array $result : This is Edifice information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('edifice'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getInfoCode($code,$condominium)
    { 
        $this->db->from('edifice'); 
        $this->db->where('code', $code);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    
    /**
     * This function is used to update the Edifice information
     * @param array $EdificeInfo : This is Edifices updated information
     * @param number $EdificeId : This is Edifice id
     */
    function edit($EdificeInfo, $EdificeId,$condominium)
    {
        $this->db->where('id', $EdificeId);
        $this->db->where('condominium',$condominium);
        $this->db->update('edifice', $EdificeInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the Edifice information
     * @param number $EdificeId : This is Edifice id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($EdificeId,$condominium)
    {
        $this->db->where('id', $EdificeId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('edifice'); 
        return $this->db->affected_rows();
    } 
}

  