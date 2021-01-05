<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Reservation_model (Reservation Model)
 * Reservation model class to get to handle Reservation related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Reservation_model extends CI_Model
{ 
    /**
     * This function is used to get the Reservation listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($id,$condominium)
    {  
        $this->db->select("r.id, r.apartment, r.commonarea, r.quantity_persons, r.start_time, r.ending_time, r.reservation_date, r.status, r.created_at
        , a.code as 'apartment_code',c.max_person ,r.observations");
        $this->db->from('reservation r');       
        $this->db->join('commonarea as c', 'c.id = r.commonarea  AND c.condominium = r.condominium'); 
        $this->db->join('apartment as a', 'a.id = r.apartment  AND a.condominium = r.condominium');  
        if(!empty($id)){
            $this->db->join('assigning_apartment as asi', 'asi.apartment = r.apartment  AND asi.condominium = r.condominium'); 
            $this->db->where("asi.tenant", $id);
        }
        $this->db->where('r.condominium',$condominium);
        $this->db->order_by('r.status, r.id', 'DESC'); 
        $this->db->group_by('r.id');
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }
 
    public function getListing($condominium)
    {  
        $this->db->select("'select' as name, r.start_time, r.ending_time, r.reservation_date as 'date',  a.code as 'code',c.name as 'commonarea'");
        $this->db->from('reservation r');       
        $this->db->join('commonarea as c', 'c.id = r.commonarea  AND c.condominium = r.condominium');
        $this->db->join('apartment as a', 'a.id = r.apartment  AND a.condominium = r.condominium');  
        $this->db->where('r.condominium',$condominium);
        $this->db->where('r.status > -1');
        $this->db->order_by('r.status, r.id', 'DESC'); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $ApartmentId : This is Apartment id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($apartment, $commonarea,$reservation_date,$start_time, $ending_time,$condominium)
    {
        $this->db->select("id");
        $this->db->from("reservation");
        $this->db->where("apartment", $apartment);   
        $this->db->where("commonarea", $commonarea);   
        $this->db->where("reservation_date", $reservation_date);   
        $this->db->where('status >-1');
        $this->db->where("(start_timestamp <= '".$start_time."' AND ending_timestamp >='".$start_time."' OR (start_timestamp <= '".$ending_time."' AND ending_timestamp >='".$ending_time."') )");
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();   
        return $query->num_rows();
    } 
    /**
     * This function is used to add new Apartment to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($ApartmentInfo)
    {
        $this->db->trans_start();
        $this->db->insert('reservation', $ApartmentInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
     /**
     * This function used to get reservation information by id
     * @param number $reservationId : This is reservation id
     * @return array $result : This is reservation information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('reservation'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }
 
    /**
     * This function is used to update the Apartment information
     * @param array $ApartmentInfo : This is Apartments updated information
     * @param number $ApartmentId : This is Apartment id
     */
    function edit($ApartmentInfo, $ApartmentId,$condominium)
    {
        $this->db->where('id', $ApartmentId);
        $this->db->where('condominium',$condominium);
        $this->db->update('reservation', $ApartmentInfo); 
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
        $this->db->delete('reservation'); 
        return $this->db->affected_rows();
    } 
}

  