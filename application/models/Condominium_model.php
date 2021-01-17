<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class : Condominium_model (Condominium Model)
 * Condominium model class to get to handle Condominium related data
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Condominium_model extends CI_Model
{

    protected $db="";

    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('', true,null,NAME_DB);
    }
    /**
     * This function is used to get the Condominium listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    public function Listing($id)
    {  
        $this->db->from('condominium'); 
        $this->db->order_by('status,id', 'DESC');   
        $this->db->where("owner", $id);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getListing($id)
    {  
        $this->db->select('id,name'); 
        $this->db->from('condominium'); 
        $this->db->where("owner", $id);
        $this->db->where("status",1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $id : This is Condominium id
     * @return {mixed} $result : This is searched result
     */
    public function checkCodeExists($name)
    { 
        $this->db->select("name");
        $this->db->from("condominium"); 
        $this->db->where("name", $name);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to add new Condominium to system
     * @return number $insert_id : This is last inserted id
     */
    public function addNew($Info)
    { 
        $this->db->trans_start();
        $this->db->insert('condominium', $Info); 
        $insert_id = $this->db->insert_id(); 
        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get Condominium information by id
     * @param number $id : This is Condominium id
     * @return array $result : This is Condominium information
     */
    public function gettInfo($id)
    {  
        $this->db->from('condominium'); 
        $this->db->where('id', $id);
        $query = $this->db->get(); 
        return $query->row();
    }

    public function getParameter($id)
    {   
        $this->db->select("cc.generate_credit,cc.generate_debit,cc.debit_card,cc.debit_cash,cc.debit_check,cc.debit_transfer,cc.credit_input,cc.documenttype,cc.module,
        cc.debit_deposit,cc.credit_deposit,cc.credit_deposit_pay ");  
        $this->db->from('condominium as cc');  
        $this->db->where('cc.id', $id);
        $query = $this->db->get(); 
        return $query->row();
    }

     /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getInfoUser($userId)
    { 
        $this->db->from('users'); 
        $this->db->where('id', $userId);
        $this->db->where('type',3);
        $query = $this->db->get();
        
        return $query->row();
    }
    

    /**
     * This function is used to update the Condominium information
     * @param array $Info : This is condominium updated information
     * @param number $id : This is Condominium id
     */
    public function edit($Info, $id)
    { 
        $this->db->where('id', $id);
        $this->db->update('condominium', $Info);
        return $this->db->affected_rows();
    }

    /**
     * This function is used to delete the Condominium information
     * @param number $id : This is Condominium id
     * @return boolean $result : TRUE / FALSE
     */
    public function delete($id)
    { 
        $this->db->where('id', $id);
        $this->db->delete('condominium'); 
        return $this->db->affected_rows();
    }
}
