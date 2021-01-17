<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class : vehicles_model (vehicles Model)
 * vehicles model class to get to handle vehicles related data
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Vehicles_model extends CI_Model
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
     * This function is used to get the vehicles listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
    */

    public function Listing($tenant='',$condominium)
    {
        $this->db->select("v.id, v.condominium, v.tenant,  t.document, t.name , v.licence_plate, v.model, v.brand, v.year, v.colour, v.condition_car, v.status, v.updated_at, v.created_at");
        $this->db->from('vehicles v');
        $this->db->join('tenant as t', 't.id = v.tenant AND t.condominium = v.condominium'); 
        $this->db->where('v.condominium', $condominium);
        if(!empty($tenant)){
            $this->db->where("v.tenant", $tenant);
        }
        $this->db->order_by('v.id', 'DESC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }


    public function ListingforAparment($apartment,$condominium)
    {
        $this->db->select("v.id, v.condominium, v.tenant,tg.serial,  t.document, t.name , v.licence_plate, v.model, v.brand, v.year, v.colour, v.condition_car, v.status, v.updated_at, v.created_at");
        $this->db->from('vehicles v');
        $this->db->join("assigning_apartment as as", "as.apartment = '$apartment' AND as.tenant = v.tenant AND asi.condominium = v.condominium"); 
        $this->db->join('tenant as t', 't.id = v.tenant AND t.condominium = v.condominium'); 
        $this->db->join('tag as tg', 'tg.vehicle = v.id  AND tg.condominium = v.condominium','left'); 
        $this->db->where('v.condominium', $condominium);
      
        $this->db->where("as.apartment", $apartment);
        $this->db->where("as.status",1); 
        $this->db->where("v.status",1);
        $this->db->order_by('tg.serial', 'ASC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }


    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $vehiclesId : This is vehicles id
     * @return {mixed} $result : This is searched result
     */
    public function checkExists($licence_plate, $id= 0 , $condominium)
    {
        $this->db->select("licence_plate");
        $this->db->from("vehicles");
        $this->db->where("licence_plate",$licence_plate); 
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    /**
     * This function is used to add new vehicles to system
     * @return number $insert_id : This is last inserted id
     */
    public function addNew($vehiclesInfo)
    {
        $this->db->trans_start();
        $this->db->insert('vehicles', $vehiclesInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get vehicles information by id
     * @param number $vehiclesId : This is vehicles id
     * @return array $result : This is vehicles information
     */
    public function getInfo($id, $condominium)
    {
        $this->db->select("v.id, v.condominium,v.tenant, t.document, t.name, t.type, v.licence_plate, v.model, v.brand, v.year, v.colour, v.condition_car, v.status, v.updated_at, v.created_at");
        $this->db->from('vehicles as v'); 
        $this->db->join('tenant as t', 't.id = v.tenant  AND t.condominium = v.condominium'); 

        $this->db->where('v.condominium', $condominium);
        $this->db->where('v.id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This function is used to update the vehicles information
     * @param array $vehiclesInfo : This is vehicless updated information
     * @param number $vehiclesId : This is vehicles id
     */
    public function edit($vehiclesInfo, $vehiclesId, $condominium)
    {
        $this->db->where('id', $vehiclesId);
        $this->db->where('condominium', $condominium);
        $this->db->update('vehicles', $vehiclesInfo);
        return $this->db->affected_rows();
    }

    /**
     * This function is used to delete the vehicles information
     * @param number $vehiclesId : This is vehicles id
     * @return boolean $result : TRUE / FALSE
     */
    public function delete($vehiclesId, $condominium)
    {
        $this->db->where('id', $vehiclesId);
        $this->db->where('condominium', $condominium);
        $this->db->delete('vehicles');
        return $this->db->affected_rows();
    }
}
