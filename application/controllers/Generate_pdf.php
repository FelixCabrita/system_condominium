<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Generate_pdf (accountcontrolController)
 * Generate_pdf Class to control all Generate_pdf related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Generate_pdf extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('generate_pdf_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the Generate_pdf
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    public function cataloge_account()
    {
        $this->load->library('pdf');

        $data["condominium"] = $this->generate_pdf_model->getCondominium($this->condominium);
        $heading = $this->generate_pdf_model->getHeading($this->condominium);

        $data["data"] = "";
        foreach ($heading as $record_hg) {
            $data["data"] = $data["data"] . " <tr ><td class='account'><b>$record_hg->code</b></td> <td><b>$record_hg->name</b></td>  </tr>";

            $Accountcontrol = $this->generate_pdf_model->getAccountcontrol($record_hg->id, $this->condominium);
            foreach ($Accountcontrol as $record_accc) {
                $data["data"] = $data["data"] . "<tr><td class='account'>$record_accc->code</td>  <td>$record_accc->name</td></tr>";

                $accounthigher = $this->generate_pdf_model->getHigher($record_accc->id, $this->condominium);
                foreach ($accounthigher as $record_acch) {
                    $data["data"] = $data["data"] . "<tr><td class='account'>$record_acch->code</td>  <td>$record_acch->name</td></tr>";

                    $Accountdetail = $this->generate_pdf_model->getAccountdetail($record_acch->id, $this->condominium);
                    foreach ($Accountdetail as $record) {
                        $data["data"] = $data["data"] . " <tr><td class='account'>$record->code</td>  <td>$record->name</td></tr>";
                    }

                }
            }
        }

        $html = $this->load->view('generate_pdf/cataloge_account', $data, true);
        $html = trim(preg_replace('/>\s+</', '><', $html));
        $this->pdf->createPDF($html, 'catalogo_cuentas', false);
    }

    public function daily($id = null)
    {

        $this->load->library('pdf');

        if ($id == null) {
            redirect('');
        }

        $data["condominium"] = $this->generate_pdf_model->getCondominium($this->condominium);

        $header = $this->generate_pdf_model->getdaily($this->condominium, $id);
        $detail = $this->generate_pdf_model->getdailyDetail($this->condominium, $id);

        $data['header'] = $header;
        $data['detail'] = $detail;
        if ($data['header'] == null) {
            redirect('');
        }

        $html = $this->load->view('generate_pdf/daily', $data, true);
        $html = trim(preg_replace('/>\s+</', '><', $html));
        $this->pdf->createPDF($html, 'entrada_de_diario_asiento_' . $id, false);
    }

    public function bill($id = null)
    {

        $this->load->library('pdf');

        if ($id == null) {
            redirect('');
        }

        $data["condominium"] = $this->generate_pdf_model->getCondominium($this->condominium);
        $bill = $this->generate_pdf_model->getInfoBill($id, $this->condominium);
        $data['bill'] = $bill;
        $tenant = $this->generate_pdf_model->getInfoTenant($bill->tenant, $this->condominium);
        $data['client'] = $tenant;
        $bills = $this->generate_pdf_model->getInfoBills($id, $this->condominium);
        $data['bills'] = $bills;

        $user = $this->generate_pdf_model->getInfoUser($bill->user, $this->condominium);
        $data['user'] = $tenant;

        if ($data['bill'] == null) {
            redirect('');
        }

        $html = $this->load->view('generate_pdf/bill', $data, true);
        $html = trim(preg_replace('/>\s+</', '><', $html));
        $this->pdf->createPDF($html, 'factura_' . str_pad($id, 4, "0", STR_PAD_LEFT), false);
    }

    public function bill_daily($id = null)
    {

        $this->load->library('pdf');

        if ($id == null) {
            redirect('');
        }

        $data["condominium"] = $this->generate_pdf_model->getCondominium($this->condominium);
        $bill = $this->generate_pdf_model->getInfoBillDaily($id, $this->condominium);
        $data['bill'] = $bill;
        $tenant = $this->generate_pdf_model->getInfoTenant($bill->tenant, $this->condominium);
        $data['client'] = $tenant;
        $bills = $this->generate_pdf_model->getInfoBillsDaily($id, $this->condominium);
        $data['bills'] = $bills;

        $user = $this->generate_pdf_model->getInfoUser($bill->user, $this->condominium);
        $data['user'] = $tenant;

        if ($data['bill'] == null) {
            redirect('');
        }

        $html = $this->load->view('generate_pdf/bill', $data, true);
        $html = trim(preg_replace('/>\s+</', '><', $html));
        $this->pdf->createPDF($html, 'factura_' . str_pad($id, 4, "0", STR_PAD_LEFT), false);
    }

    public function balance_verification()
    {

        $this->load->library('pdf');
        $data["condominium"] = $this->generate_pdf_model->getCondominium($this->condominium);
        $data["date_start"] = $this->security->xss_clean($this->input->get('date_start'));
        $data["date_ending"] = $this->security->xss_clean($this->input->get('date_ending'));

        $accountcontrol = $this->generate_pdf_model->getAccountBalanceControl($this->condominium, $data["date_start"], $data["date_ending"]);
        $data["data"] ='';
        $debit=0;
        $credit=0;
        foreach ($accountcontrol as $record_acc) {
            $data["data"] = $data["data"] .
                '<tr><td class="account"><b>' . $record_acc->code . '</b></td>
                <td  class="note"><b>' . $record_acc->name . '</td>
                <td  class="amount"><b>' . number_format($record_acc->auxiliary, 2, ',', '') . '</b></td>
                <td  class="amount"><b>' . ($record_acc->auxiliary > 0 ? number_format($record_acc->auxiliary, 2, ',', '') : number_format(0, 2, ',', '')) . '</b></td>
                <td  class="amount"><b>' . ($record_acc->auxiliary > 0 ? number_format(0, 2, ',', '') : number_format(abs($record_acc->auxiliary), 2, ',', '')) . '</b></td>  </tr>';
                $debit = $debit+($record_acc->auxiliary > 0 ?  $record_acc->auxiliary  : 0);
                $credit = $credit+($record_acc->auxiliary > 0 ?  0:$record_acc->auxiliary);
            $accounthigher = $this->generate_pdf_model->getAccountBalanceHeader($record_acc->id,$this->condominium, $data["date_start"], $data["date_ending"]);

            foreach ($accounthigher as $record_hg) {
                $data["data"] = $data["data"] .
                '<tr><td class="account"><b>' . $record_hg->code . '</b></td>
            <td  class="note"><b>' . $record_hg->name . '</td>
            <td  class="amount"><b>' . number_format($record_hg->auxiliary, 2, ',', '') . '</b></td>
            <td  class="amount"><b>' . ($record_hg->auxiliary > 0 ? number_format($record_hg->auxiliary, 2, ',', '') : number_format(0, 2, ',', '')) . '</b></td>
            <td  class="amount"><b>' . ($record_hg->auxiliary > 0 ? number_format(0, 2, ',', '') : number_format(abs($record_hg->auxiliary), 2, ',', '')) . '</b></td>  </tr>';

                $accountdetail = $this->generate_pdf_model->getAccountBalanceDetail($record_hg->id, $this->condominium, $data["date_start"], $data["date_ending"]);
                foreach ($accountdetail as $record_dt) {
                    $data["data"] = $data["data"] .
                    '<tr><td class="account">' . $record_dt->code . '</td>
                <td  class="note">' . $record_dt->name . '</td>
                <td  class="amount">' . number_format($record_dt->auxiliary, 2, ',', '') . '</td>
                <td  class="amount"></td>
                <td  class="amount"></td></tr>';
                }
            }
           
        }
        $data["data"] = $data["data"] .'<tr><td  ></td>
                <td  class="note"> </td>
                <td  class="amount"> </td>
                <td  class="amount" style="border: 1px solid #000"><b>'.  number_format( $debit, 2, ',', '')  . '</b></td>
            <td  class="amount" style="border: 1px solid #000"><b>' .number_format(abs( $credit), 2, ',', '')  . '</b></td>  </tr>';
       
        $html = $this->load->view('generate_pdf/balance_verification', $data, true);
        $html = trim(preg_replace('/>\s+</', '><', $html));
        $this->pdf->createPDF($html, 'balance_comprobacion_' . date("YmdHis"), false); 
    }

    public function cashier_closing($id)
    {
        $this->load->library('pdf');
        $data["condominium"] = $this->generate_pdf_model->getCondominium($this->condominium);
        $data["user"] = $this->generate_pdf_model->getInfoUser($id, $this->condominium);
        $data["detail"] = $this->generate_pdf_model->getInfoCashier($id, $this->condominium);
        $html = $this->load->view('generate_pdf/cashier_closing', $data, true);
        $html = trim(preg_replace('/>\s+</', '><', $html));
        $this->pdf->createPDF($html, 'cierre_caja_' . date("YmdHis"), false);
    }

    public function print_tag($tag = '')
    {
        $this->load->library('pdf');
        if ($tag == null) {
            redirect('etiquetas');
        }
        $data["condominium"] = $this->generate_pdf_model->getCondominium($this->condominium);
        $data["tag"] = $tag;
        $html = $this->load->view('generate_pdf/tag', $data, true);
        $html = trim(preg_replace('/>\s+</', '><', $html));
        $this->pdf->createPDF_TAG($html, 'etiqueta_' . $tag, false);
    }

    public function print_condominium($condominium = '')
    {
        $this->load->library('pdf');
        if ($condominium == null) {
            redirect('condominios');
        }
        $data["condominium"] = $this->generate_pdf_model->getCondominium($condominium);
        $name = $data["condominium"]->name;

        $words = $this->initials($name);
        $data["code"] = $words . str_pad($data["condominium"]->id, 4, '0', STR_PAD_LEFT);

        $html = $this->load->view('generate_pdf/condominium', $data, true);
        $html = trim(preg_replace('/>\s+</', '><', $html));
        $this->pdf->createPDF_TAG($html, 'condominio_' . $data["code"], false);
    }

    public function initials($str)
    {
        $ret = '';
        foreach (explode(' ', $str) as $word) {
            $ret .= strtoupper($word[0]);
        }

        return $ret;
    }
 
    public function accumulated_result()
    {

        $this->load->library('pdf');
        $data["condominium"] = $this->generate_pdf_model->getCondominium($this->condominium);
        $data["date_start"] = $this->security->xss_clean($this->input->get('date_start'));
        $data["date_ending"] = $this->security->xss_clean($this->input->get('date_ending'));
        $heading_1 = $this->security->xss_clean($this->input->get('heading_1'));
        $heading_2 = $this->security->xss_clean($this->input->get('heading_2'));
        
        $heading = $this->generate_pdf_model->getInfoHeading($heading_1,$this->condominium);
        $data["data"] ='';

        $data["data"] = $data["data"] .
        '<tr><td class="account"><b>' . $heading->name . '</b></td> 
        <td  class="amount"></td>  </tr>';

        $accounthigher = $this->generate_pdf_model->getAccountBalanceHeaderHeading($heading->id,$this->condominium, $data["date_start"], $data["date_ending"]);
        
        $total=0; 
        foreach ($accounthigher as $record_acc) {
            if($record_acc->auxiliary>0){
                $data["data"] = $data["data"] .
                '<tr><td class="account"> ' . $record_acc->name . ' </td> 
                <td  class="amount"><b>' . number_format(abs($record_acc->auxiliary), 2, ',', '')  . '</b></td>  </tr>';
                $total = $total+ $record_acc->auxiliary;  
            }
          
 
        }
        $data["data"] = $data["data"] .'<tr>  <td  class="amount">TOTAL '.$heading->name.'</td> 
            <td  class="amount"><b>' .number_format(abs( $total), 2, ',', '')  . '</b></td>  </tr>';


        ///////////////////////////////

        $heading = $this->generate_pdf_model->getInfoHeading($heading_2,$this->condominium);

        $data["data"] = $data["data"] .
        '<tr><td class="account"><b>' . $heading->name . '</b></td> 
        <td  class="amount"></td>  </tr>';

        $accounthigher = $this->generate_pdf_model->getAccountBalanceHeaderHeading($heading->id,$this->condominium, $data["date_start"], $data["date_ending"]);
 
        $total=0; 
        foreach ($accounthigher as $record_acc) {
            $data["data"] = $data["data"] .
                '<tr><td class="account"> ' . $record_acc->name . ' </td>  
                <td  class="amount"><b>' . number_format(abs($record_acc->auxiliary), 2, ',', '')  . '</b></td>  </tr>';
                $total = $total+ $record_acc->auxiliary;  
 
        }
        $data["data"] = $data["data"] .'<tr>  <td class="amount">TOTAL '.$heading->name.'</td> 
            <td  class="amount"><b>' .number_format(abs( $total), 2, ',', '')  . '</b></td>  </tr>';

       $html = $this->load->view('generate_pdf/accumulated_result', $data, true);
        $html = trim(preg_replace('/>\s+</', '><', $html));
        $this->pdf->createPDF($html, 'resultado_acumulado_' . date("YmdHis"), false); 
    }
}
