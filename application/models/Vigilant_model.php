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
    public function Listing($condominium, $document = '')
    {
        $this->db->select("d.id, vs.name, vs.document, d.observation, d.created_at, d.licence_plate, d.status, a.apartment,e.code");
        $this->db->from('vigilant d');
        $this->db->join('edifice as e', 'e.id = d.edifice AND e.condominium = d.condominium');
        $this->db->join('apartment as a', 'a.id = d.apartment AND a.condominium = d.condominium');
        $this->db->join('visitor as vs', 'vs.id = d.visitor  AND vs.condominium = d.condominium');
        $this->db->where('d.condominium', $condominium);
        if (!empty($document)) {
            $this->db->where("vs.document", $document);
        }
        $this->db->order_by('d.created_at', 'DESC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }


    public function ListingReport($condominium, $start_date = '', $ending_date = '', $type_visitor = '', $type_input = '')
    {
        $this->db->select("d.id, vs.name, vs.document, d.observation, d.created_at, d.licence_plate, d.status, a.apartment,e.code");
        $this->db->from('vigilant d');
        $this->db->join('edifice as e', 'e.id = d.edifice AND e.condominium = d.condominium');
        $this->db->join('apartment as a', 'a.id = d.apartment AND a.condominium = d.condominium');
        $this->db->join('visitor as vs', 'vs.id = d.visitor  AND vs.condominium = d.condominium');
        $this->db->where('d.condominium', $condominium);

        if (!empty($type_visitor)) {
            $this->db->where("vs.type", $type_visitor);
        }
        if (!empty($type_input)) {
            $this->db->where("d.status", $type_input);
        }

        if (!empty($start_date)) {
            $this->db->where("d.created_at >= '$start_date 00:00:00' AND d.created_at <='$ending_date 23:59:59'");
        }


        $this->db->order_by('d.created_at', 'DESC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 
    public function checkExists($document, $id = 0, $condominium)
    { 
        $this->db->select("vs.name,vs.document,vs.status as 'visitorStatus',vs.observation as 'visitorObservation',vg.status,vg.edifice,vg.licence_plate,vg.apartment,vg.observation, vs.mobile,vs.type");
        $this->db->from("visitor  as vs");
        $this->db->join('vigilant as vg', 'vg.visitor = vs.id  AND vg.condominium = vs.condominium','left'); 
        $this->db->where("vs.document = '" . $document . "'");
        $this->db->where('vs.condominium', $condominium);
        $this->db->order_by('vg.created_at ', "DESC"); 
        $query = $this->db->get();
        return $query->row();
    }
    
    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $id : This is vigilant id
     * @return {mixed} $result : This is searched result
     */
    public function checkExistsVisitor($document, $condominium)
    {
        $this->db->select("id");
        $this->db->from("visitor");  
        $this->db->where("document",$document);  
        $query = $this->db->get();
        return $query->row();
    }
    /**
     * This function is used to add new vigilant to system
     * @return number $insert_id : This is last inserted id
     */
    public function addNew($Info)
    {
        $this->db->trans_start();
        $this->db->insert('vigilant', $Info);

        $insert_id = $this->db->insert_id();

        $this->db->trans_complete();

        return $insert_id;
    }

/**
     * This function is used to add new vigilant to system
     * @return number $insert_id : This is last inserted id
     */
    public function addVisitor($Info)
    {
        $this->db->trans_start();
        $this->db->insert('visitor', $Info); 
        $insert_id = $this->db->insert_id(); 
        $this->db->trans_complete(); 
        return $insert_id;
    }

    /**
     * This function used to get vigilant information by id
     * @param number $id : This is vigilant id
     * @return array $result : This is vigilant information
     */
    public function getInfo($id, $condominium)
    {
        $this->db->select("d.id, d.name, d.edifice, d.document, d.chargue, d.period, d.created_at, d.status, d.apartment,
                            concat(e.code,' - ',e.manager) as 'edifice_code', a.apartment as 'apartment_code'");
        $this->db->from('vigilant d');
        $this->db->join('edifice as e', 'e.id = d.edifice  AND e.condominium = d.condominium');
        $this->db->join('apartment as a', 'a.id = d.apartment  AND a.condominium = d.condominium');
        $this->db->where('d.condominium', $condominium);
        $this->db->where('d.id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This function is used to update the vigilant information
     * @param array $Info : This is vigilants updated information
     * @param number $id : This is vigilant id
     */
    public function edit($Info, $id, $condominium)
    {
        $this->db->where('id', $id);
        $this->db->where('condominium', $condominium);
        $this->db->update('vigilant', $Info);
        return $this->db->affected_rows();
    }

    /**
     * This function is used to update the vigilant information
     * @param array $Info : This is vigilants updated information
     * @param number $id : This is vigilant id
     */
    public function editVisitor($Info, $id, $condominium)
    {
        $this->db->where('id', $id);
        $this->db->where('condominium', $condominium);
        $this->db->update('visitor', $Info);
        return $this->db->affected_rows();
    }


    /**
     * This function is used to delete the vigilant information
     * @param number $id : This is vigilant id
     * @return boolean $result : TRUE / FALSE
     */
    public function delete($id, $condominium)
    {
        $this->db->where('id', $id);
        $this->db->where('condominium', $condominium);
        $this->db->delete('vigilant');
        return $this->db->affected_rows();
    }
}
