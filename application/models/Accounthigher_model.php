<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : account_higher (account_higher Model)
 * account_higher model class to get to handle account_higher related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Accounthigher_model extends CI_Model
{ 
    /**
     * This function is used to get the account_higher listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($account_control,$condominium)
    { 
        $this->db->select('ah.id, ah.condominium, ah.code, ah.name, ah.account_control, ah.status, ah.created_at,acc.account as \'code_control\', acc.name as \'name_control\' ');    
        $this->db->from('account_higher as ah');    
        $this->db->join('account_control as acc', 'acc.id = ah.account_control AND acc.condominium = ah.condominium'); 
        $this->db->where('ah.condominium',$condominium);
        if(!empty($account_control)){
            $this->db->where("ah.account_control", $account_control);
        }
        $this->db->order_by('ah.status', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    public function getListing($account_control='',$condominium)
    {  
        $this->db->select('ah.id, ah.condominium, ah.code, ah.name, ah.account_control, ah.status, ah.created_at,acc.account as \'code_control\', acc.name as \'name_control\' ');    
        $this->db->from('account_higher as ah');    
        $this->db->join('account_control as acc', 'acc.id = ah.account_control AND acc.condominium = ah.condominium'); 
        $this->db->where('ah.condominium',$condominium);
        if(!empty($account_control)){
            $this->db->where("ah.account_control", $account_control);
        }
        
        $this->db->where('ah.status',1);
        $this->db->order_by('ah.status', 'DESC'); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $account_higherId : This is account_higher id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($code, $id = 0,$condominium)
    {
        $this->db->select("code");
        $this->db->from("account_higher");
        $this->db->where("code", $code);   
        $this->db->where('condominium',$condominium);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    } 
    /**
     * This function is used to add new account_higher to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($account_higherInfo)
    {
        $this->db->trans_start();
        $this->db->insert('account_higher', $account_higherInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    /**
     * This function used to get account_higher information by id
     * @param number $account_higherId : This is account_higher id
     * @return array $result : This is account_higher information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('account_higher'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getInfoCode($code,$condominium)
    { 
        $this->db->from('account_higher'); 
        $this->db->where('code', $code);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }
  
    /**
     * This function is used to update the account_higher information
     * @param array $account_higherInfo : This is account_highers updated information
     * @param number $account_higherId : This is account_higher id
     */
    function edit($account_higherInfo, $account_higherId,$condominium)
    {
        $this->db->where('id', $account_higherId);
        $this->db->where('condominium',$condominium);
        $this->db->update('account_higher', $account_higherInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the account_higher information
     * @param number $account_higherId : This is account_higher id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($account_higherId,$condominium)
    {
        $this->db->where('id', $account_higherId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('account_higher'); 
        return $this->db->affected_rows();
    } 
}

  