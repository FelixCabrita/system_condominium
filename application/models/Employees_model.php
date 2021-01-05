<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class : employees_model (employees Model)
 * employees model class to get to handle employees related data
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Employees_model extends CI_Model
{
    /**
     * This function is used to get the employees listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    public function Listing($apartment='',$condominium)
    {
        $this->db->select("em.id, em.name, em.document, em.observation, em.started_work, em.work_type, em.created_at, em.status, a.apartment,e.code");
        $this->db->from('employees em');
        $this->db->join('apartment as a', 'a.id = em.apartment AND a.condominium = em.condominium');
        $this->db->join('edifice as e', 'e.id = a.edifice  AND e.condominium = a.condominium'); 
        $this->db->where('em.condominium', $condominium);
        if(!empty($apartment)){
            $this->db->where("em.apartment", $apartment);
        }
        $this->db->order_by('em.created_at', 'DESC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getListing($condominium)
    {  
        $this->db->select('em.id, em.name, em.document'); 
        $this->db->from('employees as em');  
        $this->db->where("em.status",1);
        $this->db->where('em.condominium',$condominium); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $employeesId : This is employees id
     * @return {mixed} $result : This is searched result
     */
    public function checkExists($document, $id= 0,$apartment , $condominium)
    {
        $this->db->select("document");
        $this->db->from("employees");
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
     * This function is used to add new employees to system
     * @return number $insert_id : This is last inserted id
     */
    public function addNew($employeesInfo)
    {
        $this->db->trans_start();
        $this->db->insert('employees', $employeesInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get employees information by id
     * @param number $employeesId : This is employees id
     * @return array $result : This is employees information
     */
    public function getDocument($document, $condominium)
    {
        $this->db->select(" em.document,  em.apartment as 'apto_id', a.apartment,e.code,e.id as 'edifice'");
        $this->db->from('employees em');
        $this->db->join('apartment as a', 'a.id = em.apartment AND a.condominium = em.condominium');
        $this->db->join('edifice as e', 'e.id = a.edifice  AND e.condominium = a.condominium');
        $this->db->where('em.condominium', $condominium);
        $this->db->where('em.document', $document);
        $query = $this->db->get(); 
        return $query->row();
    }

    public function getInfo($id, $condominium)
    {
        $this->db->select("em.id, em.name, em.document, em.mobile, em.observation, em.started_work, em.work_type, em.created_at, em.status,em.apartment as 'apto_id', a.apartment,e.code");
        $this->db->from('employees em');
        $this->db->join('apartment as a', 'a.id = em.apartment AND a.condominium = em.condominium');
        $this->db->join('edifice as e', 'e.id = a.edifice  AND e.condominium = a.condominium');
        $this->db->where('em.condominium', $condominium);
        $this->db->where('em.id', $id);
        $query = $this->db->get(); 
        return $query->row();
    }


    /**
     * This function is used to update the employees information
     * @param array $employeesInfo : This is employeess updated information
     * @param number $employeesId : This is employees id
     */
    public function edit($employeesInfo, $employeesId, $condominium)
    {
        $this->db->where('id', $employeesId);
        $this->db->where('condominium', $condominium);
        $this->db->update('employees', $employeesInfo);
        return $this->db->affected_rows();
    }

    /**
     * This function is used to delete the employees information
     * @param number $employeesId : This is employees id
     * @return boolean $result : TRUE / FALSE
     */
    public function delete($employeesId, $condominium)
    {
        $this->db->where('id', $employeesId);
        $this->db->where('condominium', $condominium);
        $this->db->delete('employees');
        return $this->db->affected_rows();
    }
}
