<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Service_model (services Model)
 * services model class to get to handle services related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Service_model extends CI_Model
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
     * This function is used to get the services listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function Listing($id,$condominium)
    { 
        $this->db->from('services');     
        $this->db->where('condominium',$condominium);
        $this->db->order_by('id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    function getListing($condominium)
    { 
        $this->db->from('services');     
        $this->db->where('condominium',$condominium);
        $this->db->where('status',1);
        $this->db->order_by('id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

 
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $servicesId : This is services id
     * @return {mixed} $result : This is searched result
     */
    function checkExists($code, $id = 0,$condominium)
    {
        $this->db->select("id,code");
        $this->db->from("services");
        $this->db->where("code", $code);   
        $this->db->where('condominium',$condominium);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    } 
    /**
     * This function is used to add new services to system
     * @return number $insert_id : This is last inserted id
     */
    function addNew($servicesInfo)
    {
        $this->db->trans_start();
        $this->db->insert('services', $servicesInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
 
    /**
     * This function used to get services information by id
     * @param number $servicesId : This is services id
     * @return array $result : This is services information
     */
    function getInfo($id,$condominium)
    { 
        $this->db->from('services'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getInfoCode($code,$condominium)
    { 
        $this->db->from('services'); 
        $this->db->where('code', $code);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }
    
    
    
    /**
     * This function is used to update the services information
     * @param array $servicesInfo : This is servicess updated information
     * @param number $servicesId : This is services id
     */
    function edit($servicesInfo, $servicesId,$condominium)
    {
        $this->db->where('id', $servicesId);
        $this->db->where('condominium',$condominium);
        $this->db->update('services', $servicesInfo); 
        return $this->db->affected_rows();
    }
     
    /**
     * This function is used to delete the services information
     * @param number $servicesId : This is services id
     * @return boolean $result : TRUE / FALSE
     */
    function delete($servicesId,$condominium)
    {
        $this->db->where('id', $servicesId); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('services'); 
        return $this->db->affected_rows();
    }   
    
    function create_service($date_load,$id,$condominium)
    {
        $this->db->query("CREATE DEFINER=".USER_BD."@`localhost` 
        EVENT ".$id."_SERVICE"." ON SCHEDULE EVERY 1 MONTH STARTS '$date_load 22:20:00' ON COMPLETION NOT PRESERVE ENABLE DO 
        CALL `setBills`($id,$condominium);"); 
     
    } 
    
    function delete_service($id)
    {
        $this->db->query("DROP EVENT IF EXISTS ".$id."_SERVICE"); 
     
    } 
}

  