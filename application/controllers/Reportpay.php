<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Reportpay (reportpayController)
 * Reportpay Class to control all Reportpay related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Reportpay extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reportpay_model');
        $this->isLoggedIn();
        $this->getDataCondominium();
    }

    public function getDataCondominium()
    {
        if($this->rol==ROLE_ADMIN){
            $this->load->model('condominium_model');
            $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing($this->id);
        }
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
     * This function used to load the first screen of the reportpay
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    public function searchClient()
    {
        if (checkForPermissions('Payments', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {

            $dataClient = $this->reportpay_model->searchClient($this->id, $this->condominium);
            $dataReportpay = $this->reportpay_model->searchAsignationApartmentClient($this->id, $this->condominium);
            $ApartmentsAssignation = $this->getApartmentsAssignation($this->id);
            if (!empty($dataClient)) {
                echo (json_encode(array('status' => true, 'tenant' => $dataClient, 'ApartmentsAssignation' => $ApartmentsAssignation, "reportpay" => $dataReportpay)));
            } else {
                echo (json_encode(array('status' => false)));
            }
        }
    }

    public function calculate_bill_month()
    {
        if (checkForPermissions('Payments', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $quantity = $this->security->xss_clean($this->input->post('quantity'));
            $service = $this->security->xss_clean($this->input->post('service'));
            $apartment = $this->security->xss_clean($this->input->post('apartment'));
            $dataService = $this->getDataService($service);
            $dataReportpay = $this->reportpay_model->checkBillApartment($apartment, $service, $this->condominium);
            $dateMax = $dataReportpay->date;
            $reportpay_month = array();
            $dateMax =$dateMax=='0000-00-00 00:00:00'?date("Y-m-d"):$dateMax;
            for ($i = 0; $i < $quantity; $i++) {
                $getMonth = $this->reportpay_model->addMonth($i, $dateMax);
                $temp = array('id' => -2, 'description' => $getMonth->title . " - " . $dataService->name, 'amount' => $dataService->amount, 'payment_prox' => $getMonth->date);
                array_push($reportpay_month, $temp);
            }

            echo (json_encode(array('status' => true, 'countreportpay' => $countreportpay, 'reportpay_month' => $reportpay_month)));

        }
    }

    public function generate_report()
    {
        if (checkForPermissions('Payments', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['permissions'] = $this->getPermissions()->{'Payments'};
            $data['serviceRecords'] = $this->getDataService();
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de reservaciones';
            $this->loadViews("reportpay/payment", $this->globalApp, $data, null);
        }
    }

    public function listing()
    {
        if (checkForPermissions('Payments', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['permissions'] = $this->getPermissions()->{'Payments'};
            $data['reportpayRecords'] = $this->reportpay_model->listing($this->condominium, $this->rol > ROLE_VIGILANT ? $this->id : "");
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de reportes de pago';
            $this->loadViews("reportpay/reportpay", $this->globalApp, $data, null);
        }
    }
    public function report($id)
    {
        if (checkForPermissions('Payments', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {

            if ($id == null) {
                redirect('reportesdepago');
            }

            $data['permissions'] = $this->getPermissions()->{'Payments'};
            $data['reportpaydetailsRecords'] = $this->reportpay_model->listingDetail($this->condominium, $id);
            $data['reportpayRecords'] = $this->reportpay_model->getReportPay($id, $this->condominium);

            if ($data['reportpayRecords'] == null) {
                redirect('reportesdepago');
            }

            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Reporte de pago #' . str_pad($id, 4, "0", STR_PAD_LEFT);
            $this->loadViews("reportpay/view_pay", $this->globalApp, $data, null);
        }
    }

    public function save_payment()
    {
        if (checkForPermissions('Payments', PERMISSION_CREATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $this->ConfigurationLibrary();
            $parameter_daily = $this->getParameter();
            $photo = PHOTO_DEFAULT;
            $residue = $this->input->post('residue');
            $amount = $this->input->post('amount');

            $reportpay = json_decode($this->input->post('json'));
            $transfer_code = $this->input->post('transfer_code');
            $transfer_date = $this->input->post('transfer_date');
            $payment_type_id = $this->input->post('payment_type_id');

            if ($this->upload->do_upload('photo')) {
                $data = $this->upload->data();
                $this->redimencion($data);
                $photo = $data["file_name"];
            }

            $item = COUNT($reportpay);

            $info = array('status' => 0, 'condominium' => $this->condominium,
                'tenant' => $this->id, 'item' => $item, 'amount' => $amount,
                'date_transfer' => $transfer_date, 'code_transfer' => $transfer_code, 'photo' => $photo);

            $result_payment = $this->reportpay_model->created_payment($info);

            foreach ($reportpay as $bill) {
                $code_apartment = $bill[3];
                $apartment = $this->getDataApartment($code_apartment);
                // condominium, report_pay, apartment, service, description, amount, bill,
                if ($bill[5] == -1) {
                    $info = array('bill' => $bill[0], 'report_pay' => $result_payment, 'condominium' => $this->condominium,
                        'apartment' => $apartment->id, 'service' => $bill[6], 'description' => $bill[1], 'amount' => $bill[2]);
                } else if ($bill[5] == 0) {
                    $info = array('report_pay' => $result_payment, 'condominium' => $this->condominium,
                        'apartment' => $apartment->id, 'service' => $bill[0], 'description' => $bill[1], 'amount' => $bill[2]);
                } else {
                    $info = array('report_pay' => $result_payment, 'condominium' => $this->condominium,
                        'apartment' => $apartment->id, 'payment_date_prox' => $bill[5], 'service' => $bill[0], 'description' => $bill[1], 'amount' => $bill[2]);
                }
                $de = $this->reportpay_model->created_payment_detail($info);
            }
            if ($result_payment > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function report_reportpay_date()
    {
        if (checkForPermissions('Payments', PERMISSION_READ) != true || $this->generates_report == 0) {
            $this->loadThis();
        } else {
            $date_report = date('Y-m-d');

            $this->load->library('excel');
            $report_reportpay = $this->reportpay_model->report_reportpay_date($date_report, $this->condominium);

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

            foreach ($report_reportpay as $record) {
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

    public function redimencion($data)
    {
        $this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = $data['full_path'];
        $urlImag = PHOTO_PATH_PAY;
        $config['new_image'] = $urlImag;
        $config['maintain_ratio'] = true;
        $config['create_thumb'] = false;
        $config['width'] = PHOTO_W_PAY;
        $config['height'] = PHOTO_H_PAY;
        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize()) {

        }
    }

    public function UUIDv4()
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
        $uuid = vsprintf('%s%s_%s_%s_%s_%s%s%s', str_split(bin2hex($bytes), 4));
        return $uuid;
    }

    public function ConfigurationLibrary()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->helper('file');
        $config['upload_path'] = PHOTO_PATH_PAY;
        $config['allowed_types'] = 'png|jpg|jpeg';
        $config['file_name'] = $this->UUIDv4() . '_' . $this->id . '_' . $this->rol . "_payment";
        $this->load->library('upload', $config);

    }

    public function delete()
    {

        $id = $this->input->post('id');
        $photo = $this->input->post('photo');

        $result = $this->reportpay_model->delete($id, $this->condominium);
        $result = $this->reportpay_model->delete_detail($id, $this->condominium);

        if ($result) {
            echo (json_encode(array('status' => true)));
        } else {
            echo (json_encode(array('status' => false)));
        }

    }

    public function changestatus()
    {
        if (checkForPermissions('services', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $unique_code = $id."adfadasfdasdfasdfadf";// $id.date("Ymdhis");
            $bill_group = $id.date("Ymdhis");
            if ($isactive == -1) {
                $info = array('status' => $isactive);
                $result = $this->reportpay_model->edit($info, $id, $this->condominium);
                if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
            } else { 
                $info = array('status' => $isactive);
                $result = $this->reportpay_model->edit($info, $id, $this->condominium);
                $header = $this->reportpay_model->getReportPay($id, $this->condominium);
                $detail = $this->reportpay_model->listingDetail($this->condominium, $id);
                $amount = 0;
                $amount_deposit = 0;
            
                if (!empty($detail)) {
                    foreach ($detail as $record) {
                        if (!empty($record->bill)) {
                            $info = array('status' => 1, 'tenant' => $header->id_tenant, 'payment_date' => date('Y-m-d H:i:s'), 'payment_date_prox' => date('Y-m-d H:i:s'),
                                'payment_type' => "1", 'check_number' => "", 'check_transmitter' => "",'unique_code' => $unique_code,'user' => $this->id, 'bill_group' => $bill_group,
                                'transfer_date' => $header->date_transfer, 'transfer_code' => $header->code_transfer);
                            $result_payment = $this->reportpay_model->save_payment($info, $record->bill, $this->condominium);
                        } else {
                            $info = array('status' => 1, 'condominium' => $this->condominium, 'payment_date' => date('Y-m-d H:i:s'),
                                'payment_date_prox' => $record->payment_date_prox, "apartment" => $record->apartment,'unique_code' => $unique_code,'user' => $this->id,
                                'tenant' => $header->id_tenant, 'type' => $record->service, 'description' => $record->description, 'amount' => $record->amount,
                                'payment_type' => "1", 'check_number' => "", 'check_transmitter' => "", 'bill_group' => $bill_group,
                                'transfer_date' => $header->date_transfer, 'transfer_code' => $header->code_transfer);
                            $result_payment = $this->reportpay_model->save_cre_payment($info);
                        }

                        if ($record->payment_date_prox=='0000-00-00') {
                            $amount = $amount + $record->amount;
                        } else {
                            $amount_deposit = $amount_deposit + $record->amount;
                        }
                    }

                    $this->load->model('bills_model');  
                    $parameter_daily = $this->getParameter();  
                    $accountdetail_debit = "";
                    $accountdetail_credit = $parameter_daily->credit_input;
                    $accountdetail_debit = $parameter_daily->debit_transfer;
                    $credit_deposit_pay = $parameter_daily->credit_deposit_pay;

                   if($amount>0){
                        $info = array('status' => 1, 'condominium' => $this->condominium, 'module' => 6, "documenttype" => 4, 'description' => 'PAGO DE FACTUAS');
                        $daily_id = $this->bills_model->created_daily($info);
                 

                        $info = array('daily' => $daily_id, 'condominium' => $this->condominium, 'accountdetail' => $accountdetail_debit, "debit" => $amount, 'credit' => 0);
                        $this->bills_model->created_daily_detail($info);

                        $info = array('daily' => $daily_id, 'condominium' => $this->condominium, 'accountdetail' => $accountdetail_credit, "debit" => 0, 'credit' => $amount);
                        $this->bills_model->created_daily_detail($info);

                        $info = array( 'unique_code' => $daily_id);
                        $this->bills_model->update_payment($info, $unique_code, $this->condominium);
                   }
                  
                    if($amount_deposit>0){
                        $info = array('status' => 1, 'condominium' => $this->condominium, 'module' => 6, "documenttype" => 4, 'description' => 'PAGO DE FACTUAS POR ADELANTADO');
                        $daily_id = $this->bills_model->created_daily($info);
        
                        $info = array('daily' => $daily_id, 'condominium' => $this->condominium, 'accountdetail' => $accountdetail_debit, "debit" => $amount_deposit, 'credit' => 0);
                        $this->bills_model->created_daily_detail($info);
        
                        $info = array('daily' => $daily_id, 'condominium' => $this->condominium, 'accountdetail' => $credit_deposit_pay, "debit" => 0, 'credit' => $amount_deposit);
                        $this->bills_model->created_daily_detail($info);

                        $info = array( 'unique_code' => $daily_id);
                        $this->bills_model->update_payment($info, $unique_code, $this->condominium);
                        
                    }

                    if ($result_payment > 0) {echo (json_encode(array('status' => true,'daily' => $bill_group)));} else {echo (json_encode(array('status' => false)));}

                }

            }
        }
    }

}
