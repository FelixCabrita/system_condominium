<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : accountdetail (accountdetailController)
 * accountdetail Class to detalle all accountdetail related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Accountdetail extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('accountdetail_model');
        $this->isLoggedIn();
        $this->getDataCondominium();
    }

    public function getDataCondominium()
    {
        $this->load->model('condominium_model');
        $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing();
    }

    /**
     * This function used to load the first screen of the accountdetail
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    public function getDataAccountHigher($code = '')
    {
        $this->load->model('accounthigher_model');
        if(empty($code)){
            return $this->accounthigher_model->getListing('', $this->condominium);
        }else{
            return $this->accounthigher_model->getInfoCode($code, $this->condominium);
        } 
    }

    public function getDataAccountHigherId($code = '')
    {
        $this->load->model('accounthigher_model'); 
        return $this->accounthigher_model->getInfo($code, $this->condominium); 
    }

    public function getDataApartment($code='')
    {  
        $this->load->model('apartment_model');
        if(empty($code)){
            return $this->apartment_model->getListing("", $this->condominium); 
        }else{
            return $this->apartment_model->getInfoCode($code, $this->condominium); 
        } 
    } 
    

    /**
     * This function is used to load the accountdetail list
     */
    public function listing($accounthigher = '')
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $accounthigherInfo =!empty($accounthigher)? $this->getDataAccountHigher($accounthigher):"";
            $data['accounthigherInfo'] =$accounthigherInfo;
            $data['accountdetailRecords'] = $this->accountdetail_model->Listing(!empty($accounthigherInfo)?$accounthigherInfo->id:"", $this->condominium); 
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de Cuenta detalle';
            if(empty($accounthigher)){
                $this->loadViews("accountdetail/accountdetail", $this->globalApp, $data, null);
            }else{
                if(!empty($accounthigherInfo)){
                    $data['apartmentRecords'] = $this->getDataApartment();
                    $this->loadViews("accountdetail/setaccounthigher", $this->globalApp, $data, null);
                }else{
                    redirect('cuenta_detalle');
                }
               
            }
          
        }
    }

     

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('bills', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo Cuenta detalle';
            $data['accounthigherRecords'] = $this->getDataAccountHigher();        
            $data['apartmentRecords'] = $this->getDataApartment();
            $this->loadViews("accountdetail/addNew", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    public function checkExists()
    {
        $id = $this->input->post("id");
        $code = $this->input->post("code");

        if (empty($id)) {
            $result = $this->accountdetail_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->accountdetail_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new accountdetail to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('bills', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $view =  $this->input->post('view') ;

            $this->load->library('form_validation');
            $this->form_validation->set_rules('accounthigher', 'Cuenta control', 'trim|required|max_length[11]');
            $this->form_validation->set_rules('name', 'Nombre de cuenta', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('code', 'Codigo', 'trim|required|max_length[10]');       
            if ($this->form_validation->run() == false) {
                if(!isset($view)){
                    $this->addNew();
                }else{
                    $code_accounthigher =  $this->input->post('code_accounthigher') ;
                    redirect('asignacion_cuenta_detalle/'. $code_accounthigher );
                }
               
            } else { 
                $accounthigher = (strtoupper($this->security->xss_clean($this->input->post('accounthigher'))));
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $code = strtoupper($this->security->xss_clean($this->input->post('code')));
                $apartment = strtoupper($this->security->xss_clean($this->input->post('apartment')));

                $check = $this->accountdetail_model->checkExists($code, 0, $this->condominium);
                if (empty($check)) { 
                    $checkAccountHigher= $this->getDataAccountHigherId($accounthigher);
                    $info = array('account_higher' => $checkAccountHigher->id,'account_control' => $checkAccountHigher->account_control,'heading' => $checkAccountHigher->heading,'code' => $code, 'name' => $name, 'apartment' =>  $apartment , 'condominium' => $this->condominium);
                    $result = $this->accountdetail_model->addNew($info);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Cuenta detalle creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del Cuenta detalle');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo ya se encuentra ocupado por otra Cuenta detalle');
                }
              
                if(!isset($view)){
                    redirect('agregarcuenta_detalle');
                }else{
                     $code_accounthigher =  $this->input->post('code_accounthigher') ;
                    redirect('asignacion_cuenta_detalle/'. $code_accounthigher );
                }
                   
            }
        }
    }

    /**
     * This function is used load accountdetail edit information
     * @param number $accountdetailId : Optional : This is accountdetail id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('cuenta_detalle');
            }
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $data['accountdetailInfo'] = $this->accountdetail_model->getInfo($id, $this->condominium);
            $data['accounthigherRecords'] = $this->getDataAccountHigher();
            $data['apartmentRecords'] = $this->getDataApartment();
            if ($data['accountdetailInfo'] == null) {
                redirect('cuenta_detalle');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar Cuenta detalle';

            $this->loadViews("accountdetail/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the accountdetail information
     */
    public function edit()
    {
        if (checkForPermissions('bills', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');
            $this->form_validation->set_rules('name', 'Nombre de la cuenta', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('code', 'Cuenta', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('accounthigher', 'Cuenta control', 'trim|required|max_length[10]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $code = strtoupper($this->security->xss_clean($this->input->post('code'))); 
                $accounthigher = strtoupper($this->security->xss_clean($this->input->post('accounthigher')));   
                $apartment = strtoupper($this->security->xss_clean($this->input->post('apartment')));
                $check = $this->accountdetail_model->checkExists($code, $id, $this->condominium);
                if (empty($check)) {

                    $checkAccountHigher= $this->getDataAccountHigherId($accounthigher);

                    $info = array('account_higher' => $checkAccountHigher->id,'account_control' => $checkAccountHigher->account_control,'heading' => $checkAccountHigher->heading,'code' => $code, 'name' => $name, 'apartment' =>  $apartment);
                    $result = $this->accountdetail_model->edit($info, $id, $this->condominium);
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Cuenta detalle actualizado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la actulaizacion del Cuenta detalle');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo ya se encuentra ocupado por otro Cuenta detalle');
                }

                redirect("editarcuenta_detalle/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the accountdetail using accountdetailId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('bills', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL Cuenta detalle TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->accountdetail_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('bills', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $accountdetailId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $accountdetailInfo = array('status' => $isactive);

            $result = $this->accountdetail_model->edit($accountdetailInfo, $accountdetailId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function importData()
    {
        $this->load->library('excel');
        $this->load->helper(array('form', 'url'));
        $path = "./assets/excel/";
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'xlsx|xls';
        $config['remove_spaces'] = true;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('error', $this->upload->display_errors());
        } else {
            $data = array('upload_data' => $this->upload->data());
        }

        if (!empty($data['upload_data']['file_name'])) {
            $import_xls_file = $data['upload_data']['file_name'];
        } else {
            $import_xls_file = '';
        }
        
        if (!empty($import_xls_file)) { 
            $inputFileName = $path . $import_xls_file;
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                $this->session->set_flashdata('error', 'Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                    . '": ' . $e->getMessage());
            }
            $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

            $arrayCount = count($allDataInSheet);
            $flag = 0; //Existencia    PRECIO1    PRECIO2    PRECIO3    GRUPO    MARCA    IMPUESTO    COD_BARRA    ACTIVO

            $createArray = array('CODIGO', 'NOMBRE', 'CODIGO_CUENTA_MAYOR','CODIGO_CUENTA_CONTROL', 'CODIGO_RUBRO', 'CODIGO_APARTAMENTO');
            $makeArray = array('CODIGO' => 'CODIGO', 'NOMBRE' => 'NOMBRE',  'CODIGO_CUENTA_MAYOR' => 'CODIGO_CUENTA_MAYOR', 
            'CODIGO_CUENTA_CONTROL' => 'CODIGO_CUENTA_CONTROL','CODIGO_RUBRO' => 'CODIGO_RUBRO','CODIGO_APARTAMENTO' => 'CODIGO_APARTAMENTO');
            $SheetDataKey = array();
            foreach ($allDataInSheet as $dataInSheet) {
                foreach ($dataInSheet as $key => $value) {
                    if (in_array(trim($value), $createArray)) {
                        $value = preg_replace('/\s+/', '', $value);
                        $SheetDataKey[trim($value)] = $key;
                    } else {

                    }
                }
            }
            $data = array_diff_key($makeArray, $SheetDataKey);

            if (empty($data)) {
                $flag = 1;
            }
            if ($flag == 1) {
                for ($i = 2; $i <= $arrayCount; $i++) {
                    $addresses = array();
                    //'CODIGO', 'NOMBRE', 'CODIGO_CUENTA_CONTROL', 'CODIGO_RUBRO'
                    $code = $SheetDataKey['CODIGO'];
                    $name = $SheetDataKey['NOMBRE'];
                    $accounthigher = $SheetDataKey['CODIGO_CUENTA_MAYOR'];   
                    $accountcontol = $SheetDataKey['CODIGO_CUENTA_CONTROL'];
                    $heading = $SheetDataKey['CODIGO_RUBRO'];
                    $apartment_code = $SheetDataKey['CODIGO_APARTAMENTO'];

                    $heading = filter_var(trim($allDataInSheet[$i][$heading]), FILTER_SANITIZE_STRING);
                    $accounthigher = filter_var(trim($allDataInSheet[$i][$accounthigher]), FILTER_SANITIZE_STRING);  
                    $accountcontol = filter_var(trim($allDataInSheet[$i][$accountcontol]), FILTER_SANITIZE_STRING);
                    $code = filter_var(trim($allDataInSheet[$i][$code]), FILTER_SANITIZE_STRING);
                    $name = filter_var(trim($allDataInSheet[$i][$name]), FILTER_SANITIZE_STRING);
                    $apartment_code = filter_var(trim($allDataInSheet[$i][$apartment_code]), FILTER_SANITIZE_STRING);
                    $apartment="";
                    if (!empty($code)) { 
                        $check = $this->accountdetail_model->checkExists($code, 0, $this->condominium);
                        if (empty($check)) {
                            $checkAccountHigher= $this->getDataAccountHigher($accounthigher);   
                            if(!empty($apartment_code)){
                                $checkApartment = $this->getDataApartment($apartment_code);
                                $apartment = $checkApartment->id;
                            }     
                            $info = array('account_higher' => $checkAccountHigher->id,'account_control' => $checkAccountHigher->account_control,'heading' => $checkAccountHigher->heading,'code' => $code, 'name' => $name,'apartment' =>  $apartment, 'condominium' => $this->condominium);
                            $result = $this->accountdetail_model->addNew($info);
                        }
                    }

                }
                $this->session->set_flashdata('success', 'Cuentas detalle Importados con exito');

            } else {
                $this->session->set_flashdata('error', 'Error al importar el archivo');

            }
        } else {
            $this->session->set_flashdata('error', $import_xls_file.'No hay datos en el documento');
        }
        redirect('cuenta_detalle');

    }
}
