<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Commonarea_model (heading Model)
 * heading model class to get to handle heading related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Generate_pdf_model extends CI_Model
{ 
     
    public function getHeading($condominium)
    {  
        $this->db->select('id ,name,code'); 
        $this->db->from('heading'); 
        $this->db->where('condominium',$condominium);
        $this->db->where("status",1);
        $this->db->order_by('id', 'ASC'); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
  
    public function getAccountcontrol($heading='',$condominium)
    {  
        $this->db->select("acc.id,acc.account as 'code', acc.name");    
        $this->db->from('account_control as acc');     
        $this->db->where('acc.condominium',$condominium); 
        $this->db->where("acc.heading", $heading); 
        $this->db->order_by('acc.id', 'ASC'); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getHigher($account_control='',$condominium)
    {  
        $this->db->select('ah.id,  ah.code, ah.name');    
        $this->db->from('account_higher as ah');
        $this->db->where('ah.condominium',$condominium); 
        $this->db->where("ah.account_control", $account_control);  
        $this->db->order_by('ah.id', 'ASC'); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getAccountdetail($account_Higher='',$condominium)
    {  
        $this->db->select('ad.id, ad.code, ad.name');    
        $this->db->from('account_detail as ad');    
        $this->db->where('ad.condominium',$condominium); 
        $this->db->where("ad.account_higher", $account_Higher); 
        $this->db->order_by('ad.code', 'ASC'); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getCondominium($id)
    {  
        $this->db->from('condominium'); 
        $this->db->where('id', $id);
        $query = $this->db->get(); 
        return $query->row();
    }

    function getdaily($condominium,$id)
    {   
        $this->db->select("d.id, d.description, d.created_at,
        CONCAT(m.code,' - ',m.name) as 'module', CONCAT(dt.code,' - ',dt.name) as 'documentType'");
       $this->db->from('daily as d'); 
       $this->db->join('module as m', 'm.id = d.module  AND m.condominium = d.condominium');       
       $this->db->join('document_type as dt', 'dt.id = d.documenttype  AND dt.condominium = d.condominium');   
        $this->db->where('d.condominium',$condominium);
        $this->db->where('d.id',$id);
        $this->db->order_by('d.id', 'DESC'); 
        $query = $this->db->get(); 
        return $query->row(); 
    }


    function getdailyDetail($condominium,$id)
    {   
        $this->db->select("dd.id, dd.daily, dd.accountdetail,CONCAT(ac.code,' - ', ac.name) as 'name_ac', dd.description, dd.debit, dd.credit, dd.created_at");
        $this->db->from('daily_detail as dd');  
        $this->db->where('dd.condominium',$condominium); 
        $this->db->join('account_detail as ac', 'ac.id = dd.accountdetail AND ac.condominium = dd.condominium');
        $this->db->where('dd.daily',$id); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }
 
}

  