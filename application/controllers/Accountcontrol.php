<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : accountcontrol (accountcontrolController)
 * accountcontrol Class to control all accountcontrol related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Accountcontrol extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('accountcontrol_model');
        $this->isLoggedIn();
        $this->getDataCondominium();
    }

    public function getDataCondominium()
    {
        $this->load->model('condominium_model');
        $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing();
    }

    /**
     * This function used to load the first screen of the accountcontrol
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    public function getDataHeading($code = '')
    {
        $this->load->model('heading_model');
        if(empty($code)){
            return $this->heading_model->getListing( $this->condominium);
        }else{
            return $this->heading_model->getInfoCode($code, $this->condominium);
        } 
    }

    /**
     * This function is used to load the accountcontrol list
     */
    public function listing($heading = '')
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $headingInfo =!empty($heading)? $this->getDataHeading($heading):"";
            $data['headingInfo'] =$headingInfo;
            $data['accountcontrolRecords'] = $this->accountcontrol_model->Listing(!empty($headingInfo)?$headingInfo->id:"", $this->condominium); 
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de Cuenta control';
            if(empty($heading)){
                $this->loadViews("accountcontrol/accountcontrol", $this->globalApp, $data, null);
            }else{
                if(!empty($headingInfo)){
                    $this->loadViews("accountcontrol/setheading", $this->globalApp, $data, null);
                }else{
                    redirect('cuenta_control');
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
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo Cuenta control';
            $data['headingRecords'] = $this->getDataHeading();
            $this->loadViews("accountcontrol/addNew", $this->globalApp, $data, null);
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
            $result = $this->accountcontrol_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->accountcontrol_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new accountcontrol to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('bills', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $view =  $this->input->post('view') ;

            $this->load->library('form_validation');
            $this->form_validation->set_rules('heading', 'Rubro', 'trim|required|max_length[11]');
            $this->form_validation->set_rules('name', 'Nombre de cuenta', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('code', 'Cuenta', 'trim|required|max_length[10]');
            if ($this->form_validation->run() == false) {
                if(!isset($view)){
                    $this->addNew();
                }else{
                     $code_heading =  $this->input->post('code_heading') ;
                    redirect('asignacion_cuenta_control/'. $code_heading );
                }
               
            } else { 
                $heading = (strtoupper($this->security->xss_clean($this->input->post('heading'))));
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $account = strtoupper($this->security->xss_clean($this->input->post('code')));

                $check = $this->accountcontrol_model->checkExists($account, 0, $this->condominium);
                if (empty($check)) {
                    $info = array('account' => $account,'heading' => $heading, 'name' => $name, 'condominium' => $this->condominium);
                    $result = $this->accountcontrol_model->addNew($info);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Cuenta control creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del Cuenta control');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo ya se encuentra ocupado por otra Cuenta control');
                }
              
                if(!isset($view)){
                    redirect('agregarcuenta_control');
                }else{
                     $code_heading =  $this->input->post('code_heading') ;
                    redirect('asignacion_cuenta_control/'. $code_heading );
                }
                   
            }
        }
    }

    /**
     * This function is used load accountcontrol edit information
     * @param number $accountcontrolId : Optional : This is accountcontrol id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('cuenta_control');
            }
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $data['accountcontrolInfo'] = $this->accountcontrol_model->getInfo($id, $this->condominium);
            $data['headingRecords'] = $this->getDataHeading();
            if ($data['accountcontrolInfo'] == null) {
                redirect('cuenta_control');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar Cuenta control';

            $this->loadViews("accountcontrol/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the accountcontrol information
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
            $this->form_validation->set_rules('heading', 'Rubro', 'trim|required|max_length[10]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $code = strtoupper($this->security->xss_clean($this->input->post('code'))); 
                $heading = strtoupper($this->security->xss_clean($this->input->post('heading')));
                $check = $this->accountcontrol_model->checkExists($code, $id, $this->condominium);
                if (empty($check)) {
                    $info = array('account' => $code, 'heading' => $heading,'name' => $name);
                    $result = $this->accountcontrol_model->edit($info, $id, $this->condominium);
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Cuenta control actualizado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la actulaizacion del Cuenta control');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo ya se encuentra ocupado por otro Cuenta control');
                }

                redirect("editarcuenta_control/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the accountcontrol using accountcontrolId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('bills', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL Cuenta control TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->accountcontrol_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('bills', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $accountcontrolId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $accountcontrolInfo = array('status' => $isactive);

            $result = $this->accountcontrol_model->edit($accountcontrolInfo, $accountcontrolId, $this->condominium);

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

            $createArray = array('CUENTA', 'NOMBRE_CUENTA', 'CODIGO_RUBRO');
            $makeArray = array('CUENTA' => 'CUENTA', 'NOMBRE_CUENTA' => 'NOMBRE_CUENTA', 'CODIGO_RUBRO' => 'CODIGO_RUBRO');
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
                    //'CODIGO', 'DESCRIPCION', 'ENCARGADO', 'OBSERVACION'
                    $account = $SheetDataKey['CUENTA'];
                    $name = $SheetDataKey['NOMBRE_CUENTA'];
                    $heading = $SheetDataKey['CODIGO_RUBRO'];

                    $heading = filter_var(trim($allDataInSheet[$i][$heading]), FILTER_SANITIZE_STRING);
                    $account = filter_var(trim($allDataInSheet[$i][$account]), FILTER_SANITIZE_STRING);
                    $name = filter_var(trim($allDataInSheet[$i][$name]), FILTER_SANITIZE_STRING);

                    if (!empty($account)) { 
                        $check = $this->accountcontrol_model->checkExists($account, 0, $this->condominium);
                        if (empty($check)) {
                            $checkHeading= $this->getDataHeading($heading);
                            $info = array('heading' => $checkHeading->id,'account' => $account, 'name' => $name, 'condominium' => $this->condominium);
                            $result = $this->accountcontrol_model->addNew($info);
                        }
                    }

                }
                $this->session->set_flashdata('success', 'Modulos Importados con exito');

            } else {
                $this->session->set_flashdata('error', 'Error al importar el archivo');

            }
        } else {
            $this->session->set_flashdata('error', $import_xls_file.'No hay datos en el documento');
        }
        redirect('cuenta_control');

    }
}
