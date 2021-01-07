<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Reportpay_model (Reportpay_model Model)
 * Reportpay_model model class to get to handle Reportpay_model related data 
 * @author : OCTONOVE
 * @version : 1.0
 * @since : 23 Diciembre 2020
 */
class Reportpay_model extends CI_Model
{  


   function listing($condominium,$id='')
    { 
        $this->db->select("rh.id, CONCAT(t.document,' - ',t.name) as 'tenant', rh.item, rh.amount, rh.code_transfer, rh.date_transfer, rh.status, rh.created_at,  rh.photo");
        $this->db->from('report_pay_header as rh');       
        $this->db->join('tenant as t', 't.id = rh.tenant AND t.condominium = rh.condominium');
        $this->db->where('rh.condominium',$condominium);    
        if(!empty($id)){
            $this->db->where('rh.tenant',$id);
        } 
        $this->db->order_by('rh.id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }


    function getReportPay($id,$condominium)
    { 
        $this->db->select("rh.id, CONCAT(t.document,' - ',t.name) as 'tenant',t.id as 'id_tenant',t.type, rh.item, rh.amount, rh.code_transfer, rh.date_transfer, rh.status, rh.created_at,  rh.photo");
        $this->db->from('report_pay_header as rh');       
        $this->db->join('tenant as t', 't.id = rh.tenant AND t.condominium = rh.condominium');
        $this->db->where('rh.condominium',$condominium);
        $this->db->where('rh.id',$id);
        $this->db->order_by('rh.id', 'DESC'); 
        $query = $this->db->get(); 
        return $query->row();
    }

    function listingDetail($condominium,$id)
    {  
        $this->db->select("rp.id,rp.description,rp.amount,a.code,rp.created_at,rp.service,rp.bill,rp.payment_date_prox,rp.apartment");
        $this->db->from('report_pay_detail as rp');     
        $this->db->join('apartment as a', 'a.id = rp.apartment AND a.condominium = rp.condominium');
        $this->db->join('services as s', 's.id = rp.service AND s.condominium = rp.condominium');
        $this->db->where('rp.condominium',$condominium);   
        $this->db->where('rp.report_pay',$id);
        $this->db->order_by('rp.id', 'DESC'); 
        $query = $this->db->get(); 
        $result = $query->result();        
        return $result;
    }




    function searchAsignationApartmentClient($id,$condominium)
    { 
        $this->db->select("b.id, b.apartment, b.type, b.description,b.amount, b.status, b.created_at,a.code,DATE(b.created_at) as emision ");
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
 
    function searchClient($id,$condominium)
    { 
        $this->db->from('tenant'); 
        $this->db->where("id",$id);
        $this->db->where('condominium',$condominium);
        $query = $this->db->get(); 
        return $query->row();
    }

    function addMonth($mounth,$dateMax)
    { 
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
    function save_payment($info, $id,$condominium)
    {
        $this->db->where("id",$id);
        $this->db->where('condominium',$condominium);
        $this->db->update('bills', $info); 
        return $this->db->affected_rows();
    }


    function save_cre_payment($info)
    { 
        $this->db->trans_start();
        $this->db->insert('bills', $info); 
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function created_payment($info)
    {  
        $this->db->trans_start();
        $this->db->insert('report_pay_header', $info); 
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function created_payment_detail($info)
    {  
        $this->db->trans_start();
        $this->db->insert('report_pay_detail', $info); 
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
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
 
    function delete($id,$condominium)
    {
        $this->db->where('id', $id); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('report_pay_header'); 
        return $this->db->affected_rows();
    }

    function delete_detail($id,$condominium)
    {
        $this->db->where('report_pay', $id); 
        $this->db->where('condominium',$condominium);
        $this->db->delete('report_pay_detail'); 
        return $this->db->affected_rows();
    }


     /**
     * This function is used to update the account_higher information
     * @param array $account_higherInfo : This is account_highers updated information
     * @param number $account_higherId : This is account_higher id
     */
    function edit($info, $id,$condominium)
    {
        $this->db->where("id",$id);
        $this->db->where('condominium',$condominium);
        $this->db->update('report_pay_header', $info); 
        return $this->db->affected_rows();
    }
}

  