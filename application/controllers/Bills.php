<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Bills (billsController)
 * Bills Class to control all Bills related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Bills extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('bills_model');
        $this->isLoggedIn();
        $this->getDataCondominium();
    }

    public function getDataCondominium()
    {
        $this->load->model('condominium_model');
        $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing();
    }

    public function getDataService($code = '')
    {
        $this->load->model('service_model');
        if (empty($code)) {
            return $this->service_model->getListing($this->condominium);
        } else {
            return $this->service_model->getInfo($code, $this->condominium);
        }

    }

    public function getDataApartment($Code = '')
    {
        $this->load->model('apartment_model');
        return $this->apartment_model->getInfoCode($Code, $this->condominium);
    }

    public function getParameter()
    {
        $this->load->model('condominium_model');
        return $this->condominium_model->getParameter($this->condominium);
    }

    public function getApartmentsAssignation($id)
    {
        $this->load->model('tenant_model');
        return $this->tenant_model->getAssignment($id, $this->condominium);
    }
    /**
     * This function used to load the first screen of the bills
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    public function searchClient()
    {
        if (checkForPermissions('pay', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $document = $this->security->xss_clean($this->input->post('document'));
            $dataClient = $this->bills_model->searchClient($document, $this->condominium);
            $dataBills = $this->bills_model->searchAsignationApartmentClient($dataClient->id, $this->condominium);
            $ApartmentsAssignation = $this->getApartmentsAssignation($dataClient->id);
            if (!empty($dataClient)) {
                echo (json_encode(array('status' => true, 'tenant' => $dataClient, 'ApartmentsAssignation' => $ApartmentsAssignation, "bills" => $dataBills)));
            } else {
                echo (json_encode(array('status' => false)));
            }
        }
    }

    public function calculate_bill_month()
    {
        if (checkForPermissions('pay', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $quantity = $this->security->xss_clean($this->input->post('quantity'));
            $service = $this->security->xss_clean($this->input->post('service'));
            $apartment = $this->security->xss_clean($this->input->post('apartment'));
            $dataService = $this->getDataService($service);
            $dataBills = $this->bills_model->checkBillApartment($apartment, $service, $this->condominium);

            $dateMax = $dataBills->date;
            $bills_month = array();
            for ($i = 1; $i <= $quantity; $i++) {
                $getMonth = $this->bills_model->addMonth($i, $dateMax);

                //$effectiveDate = date('Y-m-d', strtotime("+3 months", strtotime($effectiveDate)));
                $temp = array('id' => -2, 'description' => $getMonth->title . " - " . $dataService->name, 'amount' => $dataService->amount, 'payment_prox' => $getMonth->date);
                array_push($bills_month, $temp);
            }

            echo (json_encode(array('status' => true, 'dateMax' => $dateMax, 'bills_month' => $bills_month)));

        }
    }

    public function listing()
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $data['serviceRecords'] = $this->getDataService();
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de reservaciones';
            $this->loadViews("bills/payment", $this->globalApp, $data, null);
        }
    }

    public function save_payment()
    {
        if (checkForPermissions('pay', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {

            $parameter_daily = $this->getParameter();

            $id = $this->input->post('id');
            $ids = $this->input->post('ids');

            $bills = json_decode($this->input->post('json'));
            $check_number = $this->input->post('check_number');
            $check_transmitter = $this->input->post('check_transmitter');
            $transfer_code = $this->input->post('transfer_code');
            $transfer_date = $this->input->post('transfer_date');
            $payment_type_id = $this->input->post('payment_type_id');

            $accountdetail_debit = "";
            $accountdetail_credit = $parameter_daily->credit_input;
            $credit_deposit_pay = $parameter_daily->credit_deposit_pay;

            switch ($payment_type_id) {
                case 0:
                    $accountdetail_debit = $parameter_daily->debit_cash;
                    break;
                case 1:
                    $accountdetail_debit = $parameter_daily->debit_transfer;
                    break;
                case 2:
                    $accountdetail_debit = $parameter_daily->debit_check;
                    break;
                case 3:
                    $accountdetail_debit = $parameter_daily->debit_card;
                    break;

            }
            if (!empty($ids)) {
                $info = array('status' => 1, 'tenant' => $id, 'payment_date' => date('Y-m-d H:i:s'), 'payment_date_prox' => date('Y-m-d H:i:s'),
                    'payment_type' => $payment_type_id, 'check_number' => $check_number, 'check_transmitter' => $check_transmitter,
                    'transfer_date' => $transfer_date, 'transfer_code' => $transfer_code);
                $result_payment = $this->bills_model->save_payment($info, $ids, $this->condominium);
            }

            $amount = 0;

            foreach ($bills as $bill) {

                if ($bill[5] <= 0) {$amount = $amount + $bill[2];}

                if ($bill[5] > -1) {
                    $code_apartment = $bill[3];
                    $apartment = $this->getDataApartment($code_apartment);
                    if ($bill[5] == 0) {
                        $info = array('status' => 1, 'condominium' => $this->condominium, 'payment_date' => date('Y-m-d H:i:s'), 'payment_date_prox' => date('Y-m-d H:i:s'),
                            "apartment" => $apartment->id, 'tenant' => $id, 'type' => $bill[0],
                            'description' => $bill[1], 'amount' => $bill[2],
                            'payment_type' => $payment_type_id, 'check_number' => $check_number, 'check_transmitter' => $check_transmitter,
                            'transfer_date' => $transfer_date, 'transfer_code' => $transfer_code);
                        $result_payment = $this->bills_model->created_payment($info);
                    }
                }
            }

            $info = array('status' => 1, 'condominium' => $this->condominium, 'module' => 6, "documenttype" => 4, 'description' => 'PAGO DE FACTUAS');
            $daily_id = $this->bills_model->created_daily($info);

            $info = array('daily' => $daily_id, 'condominium' => $this->condominium, 'accountdetail' => $accountdetail_debit, "debit" => $amount, 'credit' => 0);
            $this->bills_model->created_daily_detail($info);

            $info = array('daily' => $daily_id, 'condominium' => $this->condominium, 'accountdetail' => $accountdetail_credit, "debit" => 0, 'credit' => $amount);
            $this->bills_model->created_daily_detail($info);

            /* DEPOSIT */

            $amount = 0;

            foreach ($bills as $bill) {
                if ($bill[5] > -1) {
                    $code_apartment = $bill[3];
                    $apartment = $this->getDataApartment($code_apartment);
                    if ($bill[5] == 0) {

                    } else {
                        $amount = $amount + $bill[2];
                        $info = array('status' => 1, 'condominium' => $this->condominium, 'payment_date' => date('Y-m-d H:i:s'),
                            'payment_date_prox' => $bill[5], "apartment" => $apartment->id,
                            'tenant' => $id, 'type' => $bill[0], 'description' => $bill[1], 'amount' => $bill[2],
                            'payment_type' => $payment_type_id, 'check_number' => $check_number, 'check_transmitter' => $check_transmitter,
                            'transfer_date' => $transfer_date, 'transfer_code' => $transfer_code);
                        $result_payment = $this->bills_model->created_payment($info);
                    }
                }
            }

            $info = array('status' => 1, 'condominium' => $this->condominium, 'module' => 6, "documenttype" => 4, 'description' => 'PAGO DE FACTUAS POR ADELANTADO');
            $daily_id = $this->bills_model->created_daily($info);

            $info = array('daily' => $daily_id, 'condominium' => $this->condominium, 'accountdetail' => $accountdetail_debit, "debit" => $amount, 'credit' => 0);
            $this->bills_model->created_daily_detail($info);

            $info = array('daily' => $daily_id, 'condominium' => $this->condominium, 'accountdetail' => $credit_deposit_pay, "debit" => 0, 'credit' => $amount);
            $this->bills_model->created_daily_detail($info);

            if ($result_payment > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function report_bills_date()
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true || $this->generates_report == 0) {
            $this->loadThis();
        } else {
            $date_report = date('Y-m-d');

            $this->load->library('excel');
            $report_bills = $this->bills_model->report_bills_date($date_report, $this->condominium);

            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle("REPORTE DIARIO -> " . $date_report);
            $count = 0;
            /*CENTER*/
            $style = array(
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ),
            );
            $count++;
            //Le aplicamos ancho las columnas.
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            //Le aplicamos negrita a los títulos de la cabecera.
            $this->excel->getActiveSheet()->getStyle("A{$count}:G{$count}")->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle("A{$count}:G{$count}")->applyFromArray($style);
            //Definimos los títulos de la cabecera.
            $this->excel->getActiveSheet()->setCellValue("A{$count}", '#');
            $this->excel->getActiveSheet()->setCellValue("B{$count}", 'DESCRIPCIÓN');
            $this->excel->getActiveSheet()->setCellValue("C{$count}", 'APARTAMENTO');
            $this->excel->getActiveSheet()->setCellValue("D{$count}", 'QUIEN PAGO');
            $this->excel->getActiveSheet()->setCellValue("E{$count}", 'MONTO');
            $this->excel->getActiveSheet()->setCellValue("F{$count}", 'FECHA DE EMISION');
            $this->excel->getActiveSheet()->setCellValue("G{$count}", 'FECHA DE PAGO');

            foreach ($report_bills as $record) {
                $count++;
                $this->excel->getActiveSheet()->getStyle("A{$count}:G{$count}")->applyFromArray($style);
                //Definimos los títulos de la cabecera.
                $this->excel->getActiveSheet()->setCellValue("A{$count}", str_pad($record->id, 4, "0", STR_PAD_LEFT) . " ");
                $this->excel->getActiveSheet()->setCellValue("B{$count}", $record->description);
                $this->excel->getActiveSheet()->setCellValue("C{$count}", $record->code);
                $this->excel->getActiveSheet()->setCellValue("D{$count}", $record->client);
                $this->excel->getActiveSheet()->setCellValue("E{$count}", number_format($record->amount, 2, ',', ' '));
                $this->excel->getActiveSheet()->setCellValue("F{$count}", $record->created);
                $this->excel->getActiveSheet()->setCellValue("G{$count}", date_format(new DateTime($record->payment_date), 'Y-m-d g:i A'));
            }

            $cur = date('YmdHis');
            $file = "reporte_diario_" . $cur . "_" . $date_report . ".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $file . '"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            //Hacemos una salida al navegador con el archivo Excel.
            $objWriter->save('php://output');
            redirect('dashboard');

        }
    }

    /* REPORT DAILY */

    public function getModule()
    {
        $this->load->model('module_model');
        return $this->module_model->getListing($this->condominium);
    }

    public function getDocumentType()
    {
        $this->load->model('documenttype_model');
        return $this->documenttype_model->getListing("6", $this->condominium);
    }

    public function getAccountDetail()
    {
        $this->load->model('accountdetail_model');
        return $this->accountdetail_model->getListing("", $this->condominium);
    }

    public function listing_daily()
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $data['dailyRecords'] = $this->bills_model->listing_daily($this->condominium);
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de entradas';
            $this->loadViews("bills/daily", $this->globalApp, $data, null);
        }
    }

    public function edit_daily($daily = null)
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($daily == null) {
                redirect('entradas_de_diario');
            }

            $data['permissions'] = $this->getPermissions()->{'bills'};

            $data["moduleRecords"] = $this->getModule();
            $data["documentTypeRecords"] = $this->getDocumentType();
            $data["accountDetailRecords"] = $this->getAccountDetail();
            $header = $this->bills_model->getdaily($this->condominium, $daily);
            $detail = $this->bills_model->getdailyDetail($this->condominium, $daily);
            $data['header'] = $header;
            $data['detail'] = $detail;
            if ($data['header'] == null) {
                redirect('entradas_de_diario');
            }

            $data['max'] = $daily;
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editando entrada #'.str_pad($daily, 4, "0", STR_PAD_LEFT);
            $this->loadViews("bills/report_daily", $this->globalApp, $data, null);
        }
    }

    public function report_daily()
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['permissions'] = $this->getPermissions()->{'bills'};

            $data['max'] = $this->bills_model->getIdMaxDaily($this->condominium)->max+1;
            $data["moduleRecords"] = $this->getModule();
            $data["documentTypeRecords"] = $this->getDocumentType();
            $data["accountDetailRecords"] = $this->getAccountDetail();
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Entrada de diario';
            $this->loadViews("bills/report_daily", $this->globalApp, $data, null);
        }
    }

    public function save_daily()
    {
        if (checkForPermissions('bills', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $report = json_decode($this->input->post('json'));
            $module = $this->input->post('module');
            $date = $this->input->post('date');
            $observation = $this->input->post('observation');
            $documentType = $this->input->post('documentType');
             $id_ = $this->input->post('id_');


            if(empty($id_)){
                $info = array('created_at' => $date, 'status' => 1, 'condominium' => $this->condominium, 'module' => $module, "documenttype" => $documentType, 'description' => empty($observation) ? 'ENTRADA DE DIARIO' : $observation);
                $daily_id = $this->bills_model->created_daily($info);
    
                foreach ($report as $bill) {
                    $info = array('daily' => $daily_id, 'condominium' => $this->condominium, 'accountdetail' => $bill[0], "debit" => $bill[2], 'credit' => $bill[3], 'description' => $bill[4]);
                    $this->bills_model->created_daily_detail($info);
                } 
            } else{
                $info = array('created_at' => $date, 'status' => 1, 'condominium' => $this->condominium, 'module' => $module, "documenttype" => $documentType, 'description' => empty($observation) ? 'ENTRADA DE DIARIO' : $observation);   
                $this->bills_model->update_daily($info,$id_,$this->condominium);

                $this->bills_model->delete_daily($id_,$this->condominium);

                foreach ($report as $bill) {
                    $info = array('daily' => $id_, 'condominium' => $this->condominium, 'accountdetail' => $bill[0], "debit" => $bill[2], 'credit' => $bill[3], 'description' => $bill[4]);
                    $daily_id = $this->bills_model->created_daily_detail($info);
                }  
            }

            if ($daily_id > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }
}
