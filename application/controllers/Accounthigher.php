<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : accounthigher (accounthigherController)
 * accounthigher Class to mayor all accounthigher related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Accounthigher extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('accounthigher_model');
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

    /**
     * This function used to load the first screen of the accounthigher
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    public function getDataAccountControl($code = '')
    {
        $this->load->model('accountcontrol_model');
        if(empty($code)){
            return $this->accountcontrol_model->getListing('', $this->condominium);
        }else{
            return $this->accountcontrol_model->getInfoCode($code, $this->condominium);
        } 
    }
 public function getDataAccountControlId($code = '')
    {
        $this->load->model('accountcontrol_model'); 
        return $this->accountcontrol_model->getInfo($code, $this->condominium);
        
    }
    

    /**
     * This function is used to load the accounthigher list
     */
    public function listing($accountcontrol = '')
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $accountcontrolInfo =!empty($accountcontrol)? $this->getDataAccountControl($accountcontrol):"";
            $data['accountcontrolInfo'] =$accountcontrolInfo;
            $data['accounthigherRecords'] = $this->accounthigher_model->Listing(!empty($accountcontrolInfo)?$accountcontrolInfo->id:"", $this->condominium); 
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de Cuenta mayor';
            if(empty($accountcontrol)){
                $this->loadViews("accounthigher/accounthigher", $this->globalApp, $data, null);
            }else{
                if(!empty($accountcontrolInfo)){
                    $this->loadViews("accounthigher/setaccountcontrol", $this->globalApp, $data, null);
                }else{
                    redirect('cuenta_mayor');
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
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo Cuenta mayor';
            $data['accountcontrolRecords'] = $this->getDataAccountControl();
            $this->loadViews("accounthigher/addNew", $this->globalApp, $data, null);
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
            $result = $this->accounthigher_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->accounthigher_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new accounthigher to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('bills', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $view =  $this->input->post('view') ;

            $this->load->library('form_validation');
            $this->form_validation->set_rules('accountcontrol', 'Cuenta control', 'trim|required|max_length[11]');
            $this->form_validation->set_rules('name', 'Nombre de cuenta', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('code', 'Codigo', 'trim|required|max_length[10]');
            if ($this->form_validation->run() == false) {
                if(!isset($view)){
                    $this->addNew();
                }else{
                    $code_accountcontrol =  $this->input->post('code_accountcontrol') ;
                    redirect('asignacion_cuenta_mayor/'. $code_accountcontrol );
                }
               
            } else { 
                $accountcontrol = (strtoupper($this->security->xss_clean($this->input->post('accountcontrol'))));
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $code = strtoupper($this->security->xss_clean($this->input->post('code')));

                $check = $this->accounthigher_model->checkExists($code, 0, $this->condominium);
                if (empty($check)) { 
                    $checkAccountcontrol= $this->getDataAccountControlId($accountcontrol);
                    $info = array('account_control' => $checkAccountcontrol->id,'heading' => $checkAccountcontrol->heading,'code' => $code, 'name' => $name, 'condominium' => $this->condominium);
                    $result = $this->accounthigher_model->addNew($info);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Cuenta mayor creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del Cuenta mayor');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo ya se encuentra ocupado por otra Cuenta mayor');
                }
              
                if(!isset($view)){
                    redirect('agregarcuenta_mayor');
                }else{
                     $code_accountcontrol =  $this->input->post('code_accountcontrol') ;
                    redirect('asignacion_cuenta_mayor/'. $code_accountcontrol );
                }
                   
            }
        }
    }

    /**
     * This function is used load accounthigher edit information
     * @param number $accounthigherId : Optional : This is accounthigher id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('cuenta_mayor');
            }
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $data['accounthigherInfo'] = $this->accounthigher_model->getInfo($id, $this->condominium);
            $data['accountcontrolRecords'] = $this->getDataAccountControl();
            if ($data['accounthigherInfo'] == null) {
                redirect('cuenta_mayor');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar Cuenta mayor';

            $this->loadViews("accounthigher/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the accounthigher information
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
            $this->form_validation->set_rules('accountcontrol', 'Cuenta control', 'trim|required|max_length[10]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $code = strtoupper($this->security->xss_clean($this->input->post('code'))); 
                $accountcontrol = strtoupper($this->security->xss_clean($this->input->post('accountcontrol')));
                $check = $this->accounthigher_model->checkExists($code, $id, $this->condominium);
                if (empty($check)) {
                    $checkAccountcontrol= $this->getDataAccountControlId($accountcontrol);
                    $info = array('account_control' => $checkAccountcontrol->id,'heading' => $checkAccountcontrol->heading,'code' => $code,  'name' => $name);
                    $result = $this->accounthigher_model->edit($info, $id, $this->condominium);
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Cuenta mayor actualizado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la actulaizacion del Cuenta mayor');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo ya se encuentra ocupado por otro Cuenta mayor');
                }

                redirect("editarcuenta_mayor/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the accounthigher using accounthigherId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('bills', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL Cuenta mayor TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->accounthigher_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('bills', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $accounthigherId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $accounthigherInfo = array('status' => $isactive);

            $result = $this->accounthigher_model->edit($accounthigherInfo, $accounthigherId, $this->condominium);

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

            $createArray = array('CODIGO', 'NOMBRE', 'CODIGO_CUENTA_CONTROL', 'CODIGO_RUBRO');
            $makeArray = array('CODIGO' => 'CODIGO', 'NOMBRE' => 'NOMBRE', 'CODIGO_CUENTA_CONTROL' => 'CODIGO_CUENTA_CONTROL','CODIGO_RUBRO' => 'CODIGO_RUBRO');
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
                    $accountcontrol = $SheetDataKey['CODIGO_CUENTA_CONTROL'];
                    $heading = $SheetDataKey['CODIGO_RUBRO'];
                    $heading = filter_var(trim($allDataInSheet[$i][$heading]), FILTER_SANITIZE_STRING);
                    $accountcontrol = filter_var(trim($allDataInSheet[$i][$accountcontrol]), FILTER_SANITIZE_STRING);
                    $code = filter_var(trim($allDataInSheet[$i][$code]), FILTER_SANITIZE_STRING);
                    $name = filter_var(trim($allDataInSheet[$i][$name]), FILTER_SANITIZE_STRING);

                    if (!empty($code)) { 
                        $check = $this->accounthigher_model->checkExists($code, 0, $this->condominium);
                        if (empty($check)) {
                            $checkAccountcontrol= $this->getDataAccountControl($accountcontrol);
                            $info = array('account_control' => $checkAccountcontrol->id,'heading' => $checkAccountcontrol->heading,'code' => $code, 'name' => $name, 'condominium' => $this->condominium);
                            $result = $this->accounthigher_model->addNew($info);
                        }
                    }

                }
                $this->session->set_flashdata('success', 'Cuentas mayor Importados con exito');

            } else {
                $this->session->set_flashdata('error', 'Error al importar el archivo');

            }
        } else {
            $this->session->set_flashdata('error', $import_xls_file.'No hay datos en el documento');
        }
        redirect('cuenta_mayor');

    }
}
