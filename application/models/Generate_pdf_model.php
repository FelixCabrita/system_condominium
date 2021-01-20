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
    protected $db="";

    
    public function getHeading($condominium)
    {  
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select('id ,UPPER(name) as name,code'); 
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
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("acc.id,acc.account as 'code', LOWER(acc.name) as name");    
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
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select('ah.id,  ah.code, LOWER(ah.name) as name');    
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
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select('ad.id, ad.code, LOWER(ad.name) as name');    
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
        $this->db = $this->load->database('', true,null,NAME_DB);
        $this->db->from('condominium'); 
        $this->db->where('id', $id);
        $query = $this->db->get(); 
        return $query->row();
    }



    function getdaily($condominium,$id)
    {   
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("d.id, d.description, d.created_at, CONCAT(m.code,' - ',m.name) as 'module', CONCAT(dt.code,' - ',dt.name) as 'documentType'");
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
    {   $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("dd.id, dd.daily, dd.accountdetail,CONCAT(ac.code,' - ', ac.name) as 'name_ac', dd.description, dd.debit, dd.credit, dd.created_at");
        $this->db->from('daily_detail as dd');  
        $this->db->where('dd.condominium',$condominium); 
        $this->db->join('account_detail as ac', 'ac.id = dd.accountdetail AND ac.condominium = dd.condominium');
        $this->db->where('dd.daily',$id); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }


    function getInfoTenant($id,$condominium)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->from('tenant'); 
        $this->db->where("id",$id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }

    function getInfoBill($id,$condominium)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("b.tenant,b.type,b.payment_date,b.payment_date_prox,b.payment_type, CONCAT(t.document,' - ',t.name) as 'client',
                        b.transfer_code,b.transfer_date,b.check_number,b.check_transmitter,b.unique_code,b.bill_group,b.user");
        $this->db->from('bills as b');   
        $this->db->where('b.condominium',$condominium);
        $this->db->join('tenant as t', 't.id = b.tenant AND t.condominium = b.condominium');
        $this->db->where('b.bill_group',$id);    
        $query = $this->db->get(); 
        return $query->row();
    }

    function getInfoBills($id,$condominium)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("b.id, b.apartment, b.description,b.amount, b.status, b.created_at,a.code,DATE(b.created_at) as emision,payment_date_prox ");
        $this->db->from('bills as b');           
        $this->db->join('apartment as a', 'a.id = b.apartment AND a.condominium = b.condominium');
        $this->db->where('b.condominium',$condominium);
        $this->db->where('b.bill_group',$id); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result; 
    }

    function getInfoBillDaily($id,$condominium)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("b.tenant,b.type,b.payment_date,b.payment_date_prox,b.payment_type, CONCAT(t.document,' - ',t.name) as 'client',
                        b.transfer_code,b.transfer_date,b.check_number,b.check_transmitter,b.unique_code,b.bill_group,b.user");
        $this->db->from('bills as b');   
        $this->db->where('b.condominium',$condominium);
        $this->db->join('tenant as t', 't.id = b.tenant AND t.condominium = b.condominium');
        $this->db->where('b.unique_code',$id);    
        $query = $this->db->get(); 
        return $query->row();
    }

    function getInfoBillsDaily($id,$condominium)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("b.id, b.apartment, b.description,b.amount, b.status, b.created_at,a.code,DATE(b.created_at) as emision,payment_date_prox ");
        $this->db->from('bills as b');           
        $this->db->join('apartment as a', 'a.id = b.apartment AND a.condominium = b.condominium');
        $this->db->where('b.condominium',$condominium);
        $this->db->where('b.unique_code',$id); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result; 
    }


     /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getInfoUser($id,$condominium)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->from('users'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }
 
    function getInfoCashier($id,$condominium)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->from('cashier'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getInfoHeading($id,$condominium)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->from('heading'); 
        $this->db->where('id', $id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getAccountBalanceControl($condominium,$start_date,$ending_date)
    {   $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("acc.id, acc.name, acc.account as 'code', SUM(dd.debit - dd.credit) as 'auxiliary'");
        $this->db->from('daily_detail as dd');  
        $this->db->join('account_detail as ac', 'ac.id = dd.accountdetail AND ac.condominium = dd.condominium');
        $this->db->join('account_control as acc', 'acc.id = ac.account_control AND acc.condominium = ac.condominium'); 
        $this->db->where('dd.condominium',$condominium); 
        $this->db->where("dd.created_at <= '$ending_date 23:59:59' "); 
        $this->db->group_by("ac.account_control");
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    function getAccountBalanceHeader($control,$condominium,$start_date,$ending_date)
    {   $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("ah.id, ah.name, ah.code, SUM(dd.debit - dd.credit) as 'auxiliary'");
        $this->db->from('daily_detail as dd');  
        $this->db->join('account_detail as ac', 'ac.id = dd.accountdetail AND ac.condominium = dd.condominium');
        $this->db->join('account_higher as ah', 'ah.id = ac.account_higher AND ah.condominium = ac.condominium'); 
        $this->db->where('dd.condominium',$condominium); 
        $this->db->where('ac.account_control',$control); 
        $this->db->where("dd.created_at <= '$ending_date 23:59:59' "); 
        $this->db->group_by("ac.account_higher");
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    function getAccountBalanceDetail($higher,$condominium,$start_date,$ending_date)
    {   $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("ac.code , ac.name , SUM(dd.debit - dd.credit) as 'auxiliary'");
        $this->db->from('daily_detail as dd');   
        $this->db->join('account_detail as ac', 'ac.id = dd.accountdetail AND ac.condominium = dd.condominium');
        $this->db->where('dd.condominium',$condominium); 
        $this->db->where('ac.account_higher',$higher); 
        $this->db->where("dd.created_at <= '$ending_date 23:59:59' "); 
        $this->db->group_by("ac.id"); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }

    function getAccountBalanceHeaderHeading($heading,$condominium,$start_date,$ending_date)
    {   $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("ah.id, ah.name, ah.code, SUM(dd.debit - dd.credit) as 'auxiliary'");
        $this->db->from('daily_detail as dd');  
        $this->db->join('account_detail as ac', 'ac.id = dd.accountdetail AND ac.condominium = dd.condominium');
        $this->db->join('account_higher as ah', 'ah.id = ac.account_higher AND ah.condominium = ac.condominium'); 
        $this->db->where('dd.condominium',$condominium); 
        $this->db->where('ac.heading',$heading); 
        $this->db->where("dd.created_at >= '$start_date 00:00:00'  AND dd.created_at <= '$ending_date 23:59:59' "); 
        $this->db->group_by("ac.account_higher");
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }


    /* EMAIL */


    public function getCondominiumSendEmail()
    {   $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->from('condominium'); 
        $this->db->where('masive', 1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getListingTenant($condominium)
    {  $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select('t.id, t.name, t.document,t.email'); 
        $this->db->from('tenant as t');  
        $this->db->where("t.status",1);
        $this->db->where("t.email <> ''"); 
        $this->db->where('t.condominium',$condominium); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 


    function searchAsignationApartmentClient($id,$condominium)
    { $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("IFNULL(SUM(b.amount),0) as amount");
        $this->db->from('bills as b');          
        $this->db->join('assigning_apartment as asi', 'asi.apartment = b.apartment  AND asi.condominium = b.condominium');
        $this->db->join('services as s', 's.id = b.type AND s.condominium = b.condominium');
        $this->db->where('b.condominium',$condominium);
        $this->db->where('asi.tenant',$id);   
        $this->db->where('b.status',0);  
        $this->db->where('DAY(CURDATE())= DAY(s.date_load)');
        $query = $this->db->get(); 
        return $query->row();
    }
 
    public function getApartment($edifice,$condominium)
    {  
        $this->db->select("a.id,a.code,  a.apartment"); 
        $this->db->from('apartment as a');   
        $this->db->where("a.status",1);
        $this->db->where('a.condominium',$condominium); 
        $this->db->where("a.edifice",$edifice); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
 

    public function getEdifice($condominium)
    {  
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->query("SET lc_time_names = 'es_MX';");
        $this->db->select('e.id,e.code,UPPER(e.block) as block,e.edifice,e.manager, IFNULL(t.name, e.manager) as name,UPPER(MONTHNAME(CURDATE())) as month, YEAR(CURDATE()) as year'); 
        $this->db->from('edifice as e'); 
        $this->db->join('tenant as t', 'UPPER(t.document) = UPPER(e.manager) AND t.condominium = e.condominium','LEFT');
        $this->db->where('e.condominium',$condominium); 
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
 
    function auxiliaryBalanceIni($apartment,$condominium)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("IFNULL(SUM(amount),0) as 'amount'");
        $this->db->from('bills as b');          
        $this->db->join('apartment as a', 'a.id = b.apartment AND a.condominium = b.condominium');
        $this->db->where('b.condominium',$condominium);   
        $this->db->where('b.status',0);         
        $this->db->where('b.apartment',$apartment);  
        $this->db->where('b.created_at < CURDATE()');
        $query = $this->db->get(); 
        return $query->row();
    }
 
    function auxiliaryCxc($apartment,$condominium)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("b.type,s.name as 'name_service',b.type,IFNULL(SUM(b.amount),0) as amount ");
        $this->db->from('bills as b');          
         $this->db->join('services as s', 's.id = b.type AND s.condominium = b.condominium');
        $this->db->where('b.condominium',$condominium);   
        $this->db->where('b.apartment',$apartment);  
        $this->db->where('( (YEAR(CURDATE()) = YEAR(b.created_at) AND MONTH(CURDATE())  = MONTH(b.created_at)) OR 
                            (YEAR(b.payment_date) <=  YEAR(CURDATE()) AND MONTH(b.payment_date) <=  MONTH(CURDATE())))'); 
                        $this->db->group_by("b.type");
        $query = $this->db->get();
        
        $result = $query->result();
        return $result;
    }

    function auxiliaryCXP($apartment,$service,$condominium)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("IFNULL(SUM(b.amount),0) AS amount ");
        $this->db->from('bills as b');           
        $this->db->where('b.condominium',$condominium);   
        $this->db->where('b.apartment',$apartment);   
        $this->db->where('b.type',$service);  
        $this->db->where('b.status',1);   
        $this->db->where('b.payment_date_prox > CURDATE()');
        $query = $this->db->get(); 
        return $query->row();
    }


     function auxiliaryCC($apartment,$service,$condominium)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("IFNULL(SUM(b.amount),0) AS amount ");
        $this->db->from('bills as b');           
        $this->db->where('b.condominium',$condominium);   
        $this->db->where('b.apartment',$apartment);   
        $this->db->where('b.type',$service);  
        $this->db->where('b.status',1);   
        $this->db->where('( (YEAR(CURDATE()) = YEAR(b.created_at) AND MONTH(CURDATE())  = MONTH(b.created_at)) OR 
        (YEAR(b.payment_date) <=  YEAR(CURDATE()) AND MONTH(b.payment_date) <=  MONTH(CURDATE())))');  
        $query = $this->db->get(); 
        return $query->row();
    }



    function auxiliary($apartment,$condominium,$status)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("s.name as 'name_service',b.amount ");
        $this->db->from('bills as b');          
        $this->db->join('assigning_apartment as asi', 'asi.apartment = b.apartment  AND asi.condominium = b.condominium'); 

        $this->db->join('services as s', 's.id = b.type AND s.condominium = b.condominium');
        $this->db->where('b.condominium',$condominium);   
        $this->db->where('b.apartment',$apartment); 
       
        if($status==0){
            $this->db->where('b.status',0);   
        }else if($status == 1){
            $this->db->where('b.status',1);   
            $this->db->where('YEAR(CURDATE())= YEAR(s.date_load) AND MONTH(CURDATE())= MONTH(s.date_load)');
        }else{
            $this->db->where('b.status',1);   
            $this->db->where('s.payment_date_prox > CURDATE()');
        }
        
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    } 
    
    function save_auxiliary($condominium)
    { 
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->select("IFNULL(TIMESTAMPDIFF(SECOND, IFNULL(MAX(`created_at`),0), now()),600) as time");
        $this->db->from('history_auxiliary');           
        $this->db->where('condominium',$condominium);    
        $query = $this->db->get(); 
        return $query->row();
    }


    function addNew_auxiliary($info)
    {
        $this->db->trans_start();
        $this->db->insert('history_auxiliary', $info);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    } 

      function history_auxiliary($condominium)
    {  
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->from('history_auxiliary');  
        $this->db->where("condominium", $condominium);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * This function is used to delete the directive information
     * @param number $directiveId : This is directive id
     * @return boolean $result : TRUE / FALSE
     */
    public function delete_auxiliary($id, $condominium)
    {
        $db = $this->session->userdata('owner');
        $this->db = $this->load->database('', true,null,ALIAS_ADMIN.str_pad($db, 5, '0', STR_PAD_LEFT));
        $this->db->where('id', $id);
        $this->db->where('condominium', $condominium);
        $this->db->delete('history_auxiliary');
        return $this->db->affected_rows();
    }
}

  