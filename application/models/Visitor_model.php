<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class : visitor_model (visitor Model)
 * visitor model class to get to handle visitor related data
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Visitor_model extends CI_Model
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
     * This function is used to get the visitor listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    public function Listing($condominium)
    {
        $this->db->select("vr.id, vr.condominium, vr.document, vr.name, vr.mobile, vr.type, vr.status, vr.created_at, vr.observation");
        $this->db->from('visitor as vr'); 
        $this->db->where('vr.condominium', $condominium); 
        $this->db->order_by('vr.id', 'DESC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getListing($condominium)
    {  
        $this->db->select('vr.id, vr.document, vr.name,'); 
        $this->db->from('visitor as vr');  
        $this->db->where("vr.status",1);
        $this->db->where('vr.condominium',$condominium); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $visitorId : This is visitor id
     * @return {mixed} $result : This is searched result
     */
    public function checkExists($document, $id= 0,$apartment , $condominium)
    {
        $this->db->select("document");
        $this->db->from("visitor");
        $this->db->where("document",$document);
        $this->db->where('apartment', $apartment);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    /**
     * This function is used to add new visitor to system
     * @return number $insert_id : This is last inserted id
     */
    public function addNew($visitorInfo)
    {
        $this->db->trans_start();
        $this->db->insert('visitor', $visitorInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get visitor information by id
     * @param number $visitorId : This is visitor id
     * @return array $result : This is visitor information
     */
    public function getInfo($id, $condominium)
    {
        $this->db->select("vr.id, vr.condominium, vr.document, vr.name, vr.mobile, vr.type, vr.status, vr.created_at, vr.observation");
        $this->db->from('visitor as vr'); 
        $this->db->where('vr.condominium', $condominium);
        $this->db->where('vr.id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This function is used to update the visitor information
     * @param array $visitorInfo : This is visitors updated information
     * @param number $visitorId : This is visitor id
     */
    public function edit($visitorInfo, $visitorId, $condominium)
    {
        $this->db->where('id', $visitorId);
        $this->db->where('condominium', $condominium);
        $this->db->update('visitor', $visitorInfo);
        return $this->db->affected_rows();
    }

    /**
     * This function is used to delete the visitor information
     * @param number $visitorId : This is visitor id
     * @return boolean $result : TRUE / FALSE
     */
    public function delete($visitorId, $condominium)
    {
        $this->db->where('id', $visitorId);
        $this->db->where('condominium', $condominium);
        $this->db->delete('visitor');
        return $this->db->affected_rows();
    }
}
