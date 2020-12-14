<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Period_model (Period Model)
 * Period model class to get to handle Period related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Period_model extends CI_Model
{ 
    /**
     * This function is used to get the Period listing count
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

    function getListing($condominium)
    { 
        $this->db->from('period');     
        $this->db->where('condominium',$condominium);
        $this->db->where("status",1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
  
    /**
     * This function is used to add new Period to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($PeriodInfo)
    {
        $this->db->trans_start();
        $this->db->insert('period', $PeriodInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get Period information by id
     * @param number $PeriodId : This is Period id
     * @return array $result : This is Period information
     */
    function getInfo($PeriodId,$condominium)
    { 
        $this->db->from('period'); 
        $this->db->where('id', $PeriodId);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the Period information
     * @param array $PeriodInfo : This is Periods updated information
     * @param number $PeriodId : This is Period id
     */
    function edit($PeriodInfo, $PeriodId,$condominium)
    {
        $this->db->where('id', $PeriodId);
        $this->db->where('condominium',$condominium);
        $this->db->update('period', $PeriodInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the Period information
     * @param number $PeriodId : This is Period id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($PeriodId,$condominium)
    {
        $this->db->where('id', $PeriodId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('period'); 
        return $this->db->affected_rows();
    } 
}

  