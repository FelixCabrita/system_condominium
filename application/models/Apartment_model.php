<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Apartment_model (Apartment Model)
 * Apartment model class to get to handle Apartment related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Apartment_model extends CI_Model
{ 
    protected $db="";

    public function __construct()
    {
        parent::__construct();  
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
    }

    /**
     * This function is used to get the Apartment listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($id,$condominium)
    { 
        $this->db->select("a.id,  a.apartment, a.code,  a.conditions, a.number_parking, a.created_at,CONCAT(e.code,' - ',IFNULL(t.name,e.manager)) as 'edifice',e.code as 'code_edifice'");
        $this->db->from('apartment a');       
        $this->db->join('edifice as e', 'e.id = a.edifice AND e.condominium = a.condominium');
        $this->db->join('tenant as t', 't.document = e.manager AND t.condominium = e.condominium','left');
        $this->db->where('a.condominium',$condominium);
        $this->db->order_by('a.code', 'ASC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }


    public function getListing($edifice,$condominium)
    {  
        $this->db->select("a.id,a.code,  a.apartment,e.code as 'code_edifice'"); 
        $this->db->from('apartment as a');  
        $this->db->join('edifice as e', 'e.id = a.edifice  AND e.condominium = a.condominium');
        $this->db->where("a.status",1);
        $this->db->where('a.condominium',$condominium);
        if (!empty($edifice)) {
            $this->db->where("a.edifice",$edifice);
        }
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $code : This is Apartment id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($code, $id = 0,$condominium)
    {
        $this->db->select("id,code");
        $this->db->from("apartment");
        $this->db->where("code", $code);   
        $this->db->where('condominium',$condominium);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    } 
    /**
     * This function is used to add new Apartment to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($ApartmentInfo)
    {
        $this->db->trans_start();
        $this->db->insert('apartment', $ApartmentInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get Apartment information by id
     * @param number $code : This is Apartment id
     * @return array $result : This is Apartment information
     */
    function getInfo($code,$condominium)
    { 
        $this->db->select("a.id,  a.apartment, a.code,  a.conditions, a.number_parking, a.created_at,e.code as 'code_edifice',CONCAT(e.code,' - ',e.manager) as 'edifice'");
        $this->db->from('apartment a');       
        $this->db->join('edifice as e', 'e.id = a.edifice  AND e.condominium = a.condominium');
        $this->db->where('a.condominium',$condominium);
        $this->db->where('a.id', $code); 
        $query = $this->db->get();
        
        return $query->row();
    }
    /**
     * This function used to get Apartment information by id
     * @param number $code : This is Apartment id
     * @return array $result : This is Apartment information
     */
    function getInfoCode($code,$condominium)
    { 
        $this->db->select("a.id,  a.apartment, a.code,  a.conditions, a.number_parking, a.created_at,e.code as 'code_edifice',CONCAT(e.code,' - ',e.manager) as 'edifice'");
        $this->db->from('apartment a');       
        $this->db->join('edifice as e', 'e.id = a.edifice  AND e.condominium = a.condominium');
        $this->db->where('a.condominium',$condominium);
        $this->db->where('a.code', $code); 
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the Apartment information
     * @param array $ApartmentInfo : This is Apartments updated information
     * @param number $code : This is Apartment id
     */
    function edit($ApartmentInfo, $code,$condominium)
    {
        $this->db->where('id', $code);
        $this->db->where('condominium',$condominium);
        $this->db->update('apartment', $ApartmentInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the Apartment information
     * @param number $code : This is Apartment id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($code,$condominium)
    {
        $this->db->where('id', $code); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('apartment'); 
        return $this->db->affected_rows();
    } 
}

  