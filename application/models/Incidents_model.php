<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : incidents_model (incidents Model)
 * incidents model class to get to handle incidents related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Incidents_model extends CI_Model
{ 
    protected $db="";

    public function __construct()
    {
        parent::__construct();  
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
    }

    /**
     * This function is used to get the incidents listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($id,$condominium)
    { 
        $this->db->select("i.id, i.user,(CASE WHEN i.user_type < 6 THEN (SELECT u.name FROM users as u  WHERE u.id = i.user) 
                                                              ELSE (SELECT CONCAT('(',t.document,') ',t.name) FROM tenant as t WHERE t.id = i.user)  END) as 'name', 
                            i.user_type, i.description, i.photo_1, i.photo_2, i.status, i.observations, i.created_at, i.updated_at");
        $this->db->from('incidents as i');     
        $this->db->where('i.condominium',$condominium);
        if(!empty($id)){
            $this->db->where("i.user", $id);
        }
        $this->db->order_by('i.status, i.id', 'ASC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }
 
    public function getInfoFull($id,$condominium)
    {  
        $this->db->select("i.id, i.user,(CASE WHEN i.user_type < 6 THEN (SELECT u.name FROM users as u  WHERE u.id = i.user) 
                ELSE (SELECT CONCAT('(',t.document,') ',t.name) FROM tenant as t WHERE t.id = i.user)  END) as 'name', 
        i.user_type, i.description, i.photo_1, i.photo_2, i.status,apartment, i.observations, i.created_at, i.updated_at");
        $this->db->from('incidents as i');     
        $this->db->where('i.condominium',$condominium);
        $this->db->where('i.id', $id);
        $query = $this->db->get(); 
        return $query->row();
    } 

    function ListingDashboard($id,$condominium)
    { 
        $this->db->select("i.id, i.user,(CASE WHEN i.user_type < 6 THEN (SELECT u.name FROM users as u  WHERE u.id = i.user) 
                                                              ELSE (SELECT CONCAT('(',t.document,') ',t.name) FROM tenant as t WHERE t.id = i.user)  END) as 'name', 
                            i.user_type, i.description, i.photo_1, i.photo_2, i.status, i.observations, i.created_at, i.updated_at");
        $this->db->from('incidents as i');     
        $this->db->where('i.condominium',$condominium);
        if(!empty($id)){
            $this->db->where("i.user", $id);
        } 
        $this->db->where("i.status <", 2);
        $this->db->order_by('i.status, i.id', 'ASC'); 
        $this->db->limit(4);
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to add new Apartment to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($Info)
    {
        $this->db->trans_start();
        $this->db->insert('incidents', $Info);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
     /**
     * This function used to get incidents information by id
     * @param number $incidentsId : This is incidents id
     * @return array $result : This is incidents information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('incidents'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }
 
    /**
     * This function is used to update the Apartment information
     * @param array $Info : This is Apartments updated information
     * @param number $ApartmentId : This is Apartment id
     */
    function edit($Info, $ApartmentId,$condominium)
    {
        $this->db->where('id', $ApartmentId);
        $this->db->where('condominium',$condominium);
        $this->db->update('incidents', $Info); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the Apartment information
     * @param number $ApartmentId : This is Apartment id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($ApartmentId,$condominium)
    {
        $this->db->where('id', $ApartmentId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('incidents'); 
        return $this->db->affected_rows();
    } 
}

  