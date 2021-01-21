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
        $this->loadViews("tablero", $this->globalApp, null, null);
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
        $data["data"] = '';
        $debit = 0;
        $credit = 0;
        foreach ($accountcontrol as $record_acc) {
            $data["data"] = $data["data"] .
            '<tr><td class="account"><b>' . $record_acc->code . '</b></td>
                <td  class="note"><b>' . $record_acc->name . '</td>
                <td  class="amount"><b></b></td>
                <td  class="amount"><b>' . ($record_acc->auxiliary > 0 ? number_format($record_acc->auxiliary, 2, ',', '') : number_format(0, 2, ',', '')) . '</b></td>
                <td  class="amount"><b>' . ($record_acc->auxiliary > 0 ? number_format(0, 2, ',', '') : number_format(abs($record_acc->auxiliary), 2, ',', '')) . '</b></td>  </tr>';
            $debit = $debit + ($record_acc->auxiliary > 0 ? $record_acc->auxiliary : 0);
            $credit = $credit + ($record_acc->auxiliary > 0 ? 0 : $record_acc->auxiliary);
            $accounthigher = $this->generate_pdf_model->getAccountBalanceHeader($record_acc->id, $this->condominium, $data["date_start"], $data["date_ending"]);

            foreach ($accounthigher as $record_hg) {
                $data["data"] = $data["data"] .
                '<tr><td class="account"><b>' . $record_hg->code . '</b></td>
            <td  class="note"><b>' . $record_hg->name . '</td>
            <td  class="amount"><b>' . number_format($record_hg->auxiliary, 2, ',', '') . '</b></td>
            <td  class="amount"><b></b></td>
            <td  class="amount"><b></b></td>  </tr>';

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
        $data["data"] = $data["data"] . '<tr><td  ></td>
                <td  class="note"> </td>
                <td  class="amount"> </td>
                <td  class="amount" style="border: 1px solid #000"><b>' . number_format($debit, 2, ',', '') . '</b></td>
            <td  class="amount" style="border: 1px solid #000"><b>' . number_format(abs($credit), 2, ',', '') . '</b></td>  </tr>';

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

        $this->load->library('pdf');
        $data["condominium"] = $this->generate_pdf_model->getCondominium($this->condominium);
        $data["date_start"] = $this->security->xss_clean($this->input->get('date_start'));
        $data["date_ending"] = $this->security->xss_clean($this->input->get('date_ending'));
        $heading_1 = $this->security->xss_clean($this->input->get('heading_1'));
        $heading_2 = $this->security->xss_clean($this->input->get('heading_2'));

        $heading = $this->generate_pdf_model->getInfoHeading($heading_1, $this->condominium);
        $data["data"] = '';

        $data["data"] = $data["data"] .
        '<tr><td class="account"><b>' . $heading->name . '</b></td>
        <td  class="amount"></td>  </tr>';

        $accounthigher = $this->generate_pdf_model->getAccountAcHeaderHeading($heading->id, $this->condominium, $data["date_start"], $data["date_ending"],"credit");

        $total = 0;
        foreach ($accounthigher as $record_acc) {
            
                $data["data"] = $data["data"] .
                '<tr><td class="account"> ' . $record_acc->name . ' </td>
                <td  class="amount"><b>' . number_format(($record_acc->auxiliary), 2, ',', '') . '</b></td>  </tr>';
                $total = $total + $record_acc->auxiliary;
            

        }
        $data["data"] = $data["data"] . '<tr>  <td  class="amount">TOTAL ' . $heading->name . '</td>
            <td  class="amount"><b>' . number_format(($total), 2, ',', '') . '</b></td>  </tr>';

        ///////////////////////////////

        $heading = $this->generate_pdf_model->getInfoHeading($heading_2, $this->condominium);

        $data["data"] = $data["data"] .
        '<tr><td class="account"><b>' . $heading->name . '</b></td>
        <td  class="amount"></td>  </tr>';

        $accounthigher = $this->generate_pdf_model->getAccountAcHeaderHeading($heading->id, $this->condominium, $data["date_start"], $data["date_ending"],"debit");

        $total = 0;
        foreach ($accounthigher as $record_acc) {
            $data["data"] = $data["data"] .
            '<tr><td class="account"> ' . $record_acc->name . ' </td>
                <td  class="amount"><b>' . number_format(($record_acc->auxiliary), 2, ',', '') . '</b></td>  </tr>';
            $total = $total + $record_acc->auxiliary;

        }
        $data["data"] = $data["data"] . '<tr>  <td class="amount">TOTAL ' . $heading->name . '</td>
            <td  class="amount"><b>' . number_format(($total), 2, ',', '') . '</b></td>  </tr>';

        $html = $this->load->view('generate_pdf/accumulated_result', $data, true);
        $html = trim(preg_replace('/>\s+</', '><', $html));
        $this->pdf->createPDF($html, 'resultado_acumulado_' . date("YmdHis"), false);
    }

    public function auxiliary()
    {

        $this->load->library('pdf');
        $data["condominium"] = $this->generate_pdf_model->getCondominium($this->condominium);
        $data["data"] = '';

        $getEdifice = $this->generate_pdf_model->getEdifice($this->condominium);

        $total = 0;
        foreach ($getEdifice as $record_edif) {

            $data["data"] = $data["data"] . '
            <h4  style="text-align:center;margin-bottom:10px">
            Auxiliar Cuentas por Cobrar Actualizado del 01 al 30 ' . $record_edif->month . ' ' . $record_edif->year . '<br>
            Manzana ' . $record_edif->block . ' Edificio ' . $record_edif->edifice . '<br>
            Representante ' . $record_edif->name . '<br>
            </h4>
            <table id="customers"  cellspacing="1" cellpadding="1">
            <tr class="header">
                <th >CODIGO </th>
                <th >APTO.</th>
                <th >SERVICIO</th>
                <th  class="amount">CTA POR COBRAR</th>
                <th  class="amount">BALANCE A FAVOR</th>
                <th  class="amount">CTA COBRADAS</th>
                <th  class="amount">BALANCE PENDIENTE</th>
            </tr>
            ';
            $cc_g = 0;
            $cxc_g = 0;
            $cxp_g = 0;
            $edific = $record_edif->block . "-" . $record_edif->edifice;
            $apartment = $this->generate_pdf_model->getApartment($record_edif->id, $this->condominium);
            foreach ($apartment as $record_apart) {
                //a.id,a.code,  a.apartment
                /* $auxiliaryBalanceIni = $this->generate_pdf_model->auxiliaryBalanceIni($record_apart->id,$this->condominium);
                $cxc = $cxc+$auxiliaryBalanceIni->amount;*/
                $data["data"] = $data["data"] . '
                    <tr>
                        <td>' . $record_apart->code . '</td>
                        <td>' . $record_apart->apartment . '</td>
                        <td></td>
                        <td  class="amount"></td>
                        <td  class="amount"></td>
                        <td  class="amount"></td>
                        <td  class="amount"></td>
                    </tr>';

                $cc = 0;
                $cxc = 0;
                $cxp = 0;
                $apto = $record_apart->id;
                $auxiliaryService = $this->generate_pdf_model->auxiliaryCxc($apto, $this->condominium);

                foreach ($auxiliaryService as $record_service) {
                    $srv = $record_service->type;
                    //s.name as 'name_service',b.type,amount
                    $amout_ba = $this->generate_pdf_model->auxiliaryCXP($apto, $srv, $this->condominium)->amount;
                    $amout_cc = $this->generate_pdf_model->auxiliaryCC($apto, $srv, $this->condominium)->amount;
                    $sub = $amout_cc - $record_service->amount;
                    $text = $sub > 0 ? "(" . number_format(abs($sub), 2, ',', '') . ")" : number_format(abs($sub), 2, ',', '');
                    $data["data"] = $data["data"] . '
                        <tr>
                            <td></td>
                            <td></td>
                            <td>' . $record_service->name_service . '</td>
                            <td  class="amount">' . number_format($record_service->amount, 2, ',', '') . '</td>
                            <td  class="amount">(' . number_format($amout_ba, 2, ',', '') . ')</td>
                            <td  class="amount">' . number_format($amout_cc, 2, ',', '') . '</td>
                            <td  class="amount">' . $text . '</td>
                        </tr>';

                    $cxp = $cxp + $amout_ba;
                    $cxc = $cxc + $record_service->amount;
                    $cc = $cc + $amout_cc;
                }
                //totales
                $data["data"] = $data["data"] . '
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td  class="amount_line">' . number_format($cxc, 2, ',', '') . '</td>
                            <td  class="amount_line"></td>
                            <td  class="amount_line">' . number_format($cc, 2, ',', '') . '</td>
                            <td  class="amount_line">' . number_format($cxc - $cc, 2, ',', '') . '</td>
                        </tr>';
                $total = $total + ($cxc - $cc);
                $cc_g = $cc_g + $cc;
                $cxc_g = $cxc_g + $cxc;
                $cxp_g = $cxp_g + $cxp;
            }

            $data["data"] = $data["data"] . '
            <tr>
                <td></td>
                <td></td>
                <td><b>TOTALES</b></td>
                <td  class="amount_line"><b>' . number_format($cxc_g, 2, ',', '') . '</b></td>
                <td  class="amount_line"></td>
                <td  class="amount_line"><b>' . number_format($cc_g, 2, ',', '') . '</b></td>
                <td  class="amount_line"><b>' . number_format($cxc_g - $cc_g, 2, ',', '') . '</b></td>
            </tr>';

            $data["data"] = $data["data"] . '
            <tr>
                <td colspan="6" style="text-align: right"><b>Total Balance Inic. pendiente por Cobrar del Edificio ' . $edific . '</b></td>
                <td  class="amount"><b>' . number_format($cxc_g - $cc_g, 2, ',', '') . '</b></td>
            </tr>
            </table>
            <div style="page-break-after:always;"></div>
            ';

        }
        /*echo $data["data"]; */

        $time_save = $this->generate_pdf_model->save_auxiliary($this->condominium)->time;
        $name_file = 'auxiliar_' . date("YmdHis") . "_" . $data["condominium"]->code;

        if ($time_save >= 300) {
            $info = array('file' => $name_file . ".pdf", 'condominium' => $this->condominium);
            $this->generate_pdf_model->addNew_auxiliary($info);
        }

        $html = $this->load->view('generate_pdf/auxiliary', $data, true);
        $html = trim(preg_replace('/>\s+</', '><', $html));
        $this->pdf->createPDF($html, $name_file, false, $time_save);
    }

    public function getDataCondominium()
    {
        if($this->rol==ROLE_ADMIN){
            $this->load->model('condominium_model');
            $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing($this->id);
        }
    }


    //history_auxiliary
    public function history_auxiliary()
    {
        $this->getDataCondominium();
        $data['data'] = $this->generate_pdf_model->history_auxiliary($this->condominium);
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Historial de auxiliares';
        $this->loadViews("generate_pdf/history_auxiliary", $this->globalApp, $data, null);
    }




    public function deleteAuxiliary()
    {
        $id = $this->input->post('id');
        $file = $this->input->post('file');
        $result = $this->generate_pdf_model->delete_auxiliary($id, $this->condominium);
        if ($result) {
            unlink(PDF_PATH.$file);
            echo (json_encode(array('status' => true)));
        } else {
            echo (json_encode(array('status' => false)));
        }

    }
}
