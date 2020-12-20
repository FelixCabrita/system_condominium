<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class : vigilant_model (vigilant Model)
 * vigilant model class to get to handle vigilant related data
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Vigilant_model extends CI_Model
{
    /**
     * This function is used to get the vigilant listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    public function Listing($condominium,$document='')
    {
        $this->db->select("d.id, d.name, d.document, d.observation, d.created_at, d.status, a.apartment,e.code");
        $this->db->from('vigilant d');
        $this->db->join('edifice as e', 'e.id = d.edifice');
        $this->db->join('apartment as a', 'a.id = d.apartment');
        $this->db->where('d.condominium', $condominium);
        if(!empty($document)){
            $this->db->where("document", $document);
        }
        $this->db->order_by('d.created_at', 'DESC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $vigilantId : This is vigilant id
     * @return {mixed} $result : This is searched result
     */
    public function checkExists($document, $id = 0, $condominium)
    {
        $this->db->select("name,document,status,edifice,apartment,observation,
        (select IFNULL(v.mobile,'') as 'mobile' from vigilant v where v.document like '%" . $document . "%' AND v.mobile <> ''  AND v.condominium = $condominium LIMIT 1)  as 'mobile'");
        $this->db->from("vigilant");
        $this->db->where("document like '%" . $document . "%'");
        $this->db->where('condominium', $condominium);
      
        $query = $this->db->get();return $query->row();
    }
    /**
     * This function is used to add new vigilant to system
     * @return number $insert_id : This is last inserted id
     */
    public function addNew($vigilantInfo)
    {
        $this->db->trans_start();
        $this->db->insert('vigilant', $vigilantInfo);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get vigilant information by id
     * @param number $vigilantId : This is vigilant id
     * @return array $result : This is vigilant information
     */
    public function getInfo($id, $condominium)
    {
        $this->db->select("d.id, d.name, d.edifice, d.document, d.chargue, d.period, d.created_at, d.status, d.apartment,
                            concat(e.code,' - ',e.manager) as 'edifice_code', a.apartment as 'apartment_code'");
        $this->db->from('vigilant d');
        $this->db->join('edifice as e', 'e.id = d.edifice');
        $this->db->join('apartment as a', 'a.id = d.apartment');
        $this->db->where('d.condominium', $condominium);
        $this->db->where('d.id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This function is used to update the vigilant information
     * @param array $vigilantInfo : This is vigilants updated information
     * @param number $vigilantId : This is vigilant id
     */
    public function edit($vigilantInfo, $vigilantId, $condominium)
    {
        $this->db->where('id', $vigilantId);
        $this->db->where('condominium', $condominium);
        $this->db->update('vigilant', $vigilantInfo);
        return $this->db->affected_rows();
    }

    /**
     * This function is used to delete the vigilant information
     * @param number $vigilantId : This is vigilant id
     * @return boolean $result : TRUE / FALSE
     */
    public function delete($vigilantId, $condominium)
    {
        $this->db->where('id', $vigilantId);
        $this->db->where('condominium', $condominium);
        $this->db->delete('vigilant');
        return $this->db->affected_rows();
    }
}
