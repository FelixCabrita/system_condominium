<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Period_model (User Model)
 * User model class to get to handle user related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Period_model extends CI_Model
{ 
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($id,$condominium)
    { 
        $this->db->from('period');     
        $this->db->where('condominium',$condominium);
        $this->db->order_by('id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }
  
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('period', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getInfo($userId,$condominium)
    { 
        $this->db->from('period'); 
        $this->db->where('id', $userId);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function edit($userInfo, $userId,$condominium)
    {
        $this->db->where('id', $userId);
        $this->db->where('condominium',$condominium);
        $this->db->update('period', $userInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($userId,$condominium)
    {
        $this->db->where('id', $userId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('period'); 
        return $this->db->affected_rows();
    } 
}

  