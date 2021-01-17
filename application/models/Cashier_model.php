<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : cashier (cashier Model)
 * cashier model class to get to handle cashier related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Cashier_model extends CI_Model
{ 
    protected $db="";

    public function __construct()
    {
        parent::__construct();  
        if(!isset($_COOKIE['workstation_fscv_'])){
            $this->session->sess_destroy();
            redirect('login');
        }
        $db = $_COOKIE['workstation_fscv_'];
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
    }

    /**
     * This function is used to get the cashier listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($user="",$condominium)
    { 
        $this->db->select('c.status,c.observation,c.id, u.name, c.user, c.cash_input, c.card_input, c.transfer_input, c.check_input, c.cash_exit, c.transfer_exit, c.check_exit, c.card_exit, c.created_at');    
        $this->db->from('cashier as c');    
        $this->db->join('users as u', 'u.id = c.user AND u.condominium = c.condominium'); 
        $this->db->where('c.condominium',$condominium);
        if(!empty($user)){
            $this->db->where("c.user", $user);
        }
        $this->db->order_by('c.id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }
   
    function getIdMax($condominium)
    {  
        $this->db->select("MAX(id) as max");
        $this->db->from('cashier');  
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row(); 
    }

    function getAmountType($type,$date,$user,$condominium)
    {  
        $this->db->select("IFNULL(SUM(amount),0) as amount");
        $this->db->from('bills');  
        $this->db->where('condominium',$condominium);
        $this->db->where('payment_type',$type);  
        $this->db->where('user',$user);
        $this->db->where("DATE(created_at) = '$date'"); 
        $query = $this->db->get(); 
        return $query->row(); 
    }
 

    function addNew($cashierInfo)
    {
        $this->db->trans_start();
        $this->db->insert('cashier', $cashierInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 
    
    /**
     * This function used to get cashier information by id
     * @param number $cashierId : This is cashier id
     * @return array $result : This is cashier information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('cashier'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getCheck($id,$condominium)
    { 
        $this->db->from('cashier'); 
        $this->db->where('id', $id);
        $this->db->where('status', 1);
        $this->db->where('DATE(created_at) = DATE(now())');
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }


    function getInfoCode($code,$condominium)
    { 
        $this->db->from('cashier'); 
        $this->db->where('code', $code);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }
  
    /**
     * This function is used to update the cashier information
     * @param array $cashierInfo : This is cashiers updated information
     * @param number $cashierId : This is cashier id
     */
    function edit($cashierInfo, $cashierId,$condominium)
    {
        $this->db->where('id', $cashierId);
        $this->db->where('condominium',$condominium);
        $this->db->update('cashier', $cashierInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the cashier information
     * @param number $cashierId : This is cashier id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($cashierId,$condominium)
    {
        $this->db->where('id', $cashierId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('cashier'); 
        return $this->db->affected_rows();
    } 
}

  