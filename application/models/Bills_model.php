<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Bills_model (Bills_model Model)
 * Bills_model model class to get to handle Bills_model related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Bills_model extends CI_Model
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

    function searchAsignationApartmentClient($id,$condominium)
    { 
        $this->db->select("b.id, b.apartment, b.description,b.amount, b.status, b.created_at,a.code,DATE(b.created_at) as emision ");
        $this->db->from('bills as b');          
        $this->db->join('assigning_apartment as asi', 'asi.apartment = b.apartment  AND asi.condominium = b.condominium');
        $this->db->join('apartment as a', 'a.id = b.apartment AND a.condominium = b.condominium');
        $this->db->where('b.condominium',$condominium);
        $this->db->where('asi.tenant',$id);   
        $this->db->where('b.status',0);  
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result; 
    }

    function check_report_pay($id,$condominium)
    { 
        $this->db->select("rh.id");
        $this->db->from('report_pay_header as rh');       
        $this->db->where('rh.condominium',$condominium);
        $this->db->where('rh.tenant',$id);
        $this->db->where('rh.status',0);
        $this->db->order_by('rh.id', 'DESC'); 
        $query = $this->db->get(); 
        return $query->row();
    }

    function checkBillApartment($apartment,$service,$condominium)
    {  
        $this->db->select("IFNULL(MAX(payment_date_prox),DATE_ADD(now(), INTERVAL -1 MONTH)) as date");
        $this->db->from('bills as b');
        $this->db->where('b.apartment',$apartment);  
        $this->db->where('b.type',$service); 
        $this->db->where('b.condominium',$condominium); 
        $query = $this->db->get(); 
        return $query->row();
    }
 
    function searchClient($document,$condominium)
    { 
        $this->db->from('tenant'); 
        $this->db->where("UPPER(document) = UPPER('$document')");
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }

    function addMonth($mounth,$dateMax)
    { 
        $this->db->query("SET lc_time_names = 'es_MX';"); 
        $this->db->SELECT("DATE(DATE_ADD('$dateMax', INTERVAL $mounth MONTH)) as 'date', UPPER(MONTHNAME(DATE_ADD('$dateMax', INTERVAL $mounth MONTH))) as 'title'");  
        $query = $this->db->get(); 
        return $query->row();
    }


    function getInfo($id,$condominium)
    { 
        $this->db->from('tenant'); 
        $this->db->where("id",$id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }
    
    

     /**
     * This function is used to update the account_higher information
     * @param array $account_higherInfo : This is account_highers updated information
     * @param number $account_higherId : This is account_higher id
     */
    function save_payment($info, $ids,$condominium)
    {
        $this->db->where("id IN ($ids)");
        $this->db->where('condominium',$condominium);
        $this->db->update('bills', $info); 
        return $this->db->affected_rows();
    }

     /**
     * This function is used to update the account_higher information
     * @param array $account_higherInfo : This is account_highers updated information
     * @param number $account_higherId : This is account_higher id
     */
    function update_payment($info, $unique_code,$condominium)
    {
        $this->db->where("unique_code",$unique_code);
        $this->db->where('condominium',$condominium);
        $this->db->update('bills', $info); 
        return $this->db->affected_rows();
    }


    function created_payment($info)
    {  
        $this->db->trans_start();
        $this->db->insert('bills', $info); 
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }


    function getIdMaxDaily($condominium)
    {  
        $this->db->select("MAX(id) as max");
        $this->db->from('daily');  
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row(); 
    }

    function getIdMaxBill($condominium)
    {  
        $this->db->select("iFNULL(MAX(bill_group),0) as max");
        $this->db->from('bills');  
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row(); 
    }

    function listing_daily($condominium)
    {  
        $this->db->select("d.id, d.description, d.created_at,
         CONCAT(m.code,' - ',m.name) as 'module', CONCAT(dt.code,' - ',dt.name) as 'documentType'");
        $this->db->from('daily as d'); 
        $this->db->join('module as m', 'm.id = d.module  AND m.condominium = d.condominium');       
        $this->db->join('document_type as dt', 'dt.id = d.documenttype  AND dt.condominium = d.condominium');   
        $this->db->where('d.condominium',$condominium);
        $this->db->order_by('d.id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    function getBills($id,$condominium)
    { 
        $this->db->select("SUM(b.amount) as 'amount', b.tenant, b.bill_group, b.type, b.payment_date, b.payment_date_prox, b.payment_type, CONCAT(t.document, ' - ', t.name) as 'client', b.transfer_code, b.transfer_date, b.check_number, b.check_transmitter, b.unique_code,b.created_at");
        $this->db->from('bills as b');          
        $this->db->join('tenant as t', 't.id = b.tenant AND t.condominium = b.condominium'); 
        $this->db->where('b.condominium',$condominium);
        if(!empty($id)){
            $this->db->where('b.tenant',$id);
        } 
        $this->db->order_by('b.bill_group', 'DESC');  
        $this->db->order_by('b.status', 1); 
        $this->db->group_by("b.bill_group");
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    function getdaily($condominium,$id)
    {   
        $this->db->from('daily as d');  
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

    function created_daily($info)
    {  
        $this->db->trans_start();
        $this->db->insert('daily', $info); 
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    /**
     * This function is used to update the tenant information
     * @param array $Info : This is tenants updated information
     * @param number $Id : This is tenant id
     */
    function update_daily($Info, $Id,$condominium)
    {
        $this->db->where('id', $Id);
        $this->db->where('condominium',$condominium);
        $this->db->update('daily', $Info); 
        return $this->db->affected_rows();
    }

    function delete_daily($id,$condominium)
    {
        $this->db->where('daily', $id); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('daily_detail'); 
        return $this->db->affected_rows();
    } 

    function created_daily_detail($info)
    {  
        $this->db->trans_start();
        $this->db->insert('daily_detail', $info); 
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }


     /**
     * This function is used to update the tenant information
     * @param array $Info : This is tenants updated information
     * @param number $Id : This is tenant id
     */
    function save_balace_tenant($Info, $Id,$condominium)
    {
        $this->db->where('id', $Id);
        $this->db->where('condominium',$condominium);
        $this->db->update('tenant', $Info); 
        return $this->db->affected_rows();
    }
     
    function report_bills_date($date, $condominium)
    { 
        $this->db->select("b.id, b.apartment,b.type,a.code,CONCAT(t.document,' - ', t.name) as 'client',
         b.description,b.amount, b.status, b.created_at,
          DATE(b.created_at) as created,  b.payment_date ");
        $this->db->from('bills as b'); 
        $this->db->join('apartment as a', 'a.id = b.apartment AND a.condominium = b.condominium');
        $this->db->join('tenant as t', 't.id = b.tenant AND t.condominium = b.condominium');
        $this->db->where('b.condominium',$condominium);  
        $this->db->where('DATE(b.created_at) = DATE(now())');  
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result; 
    }
 

}

  