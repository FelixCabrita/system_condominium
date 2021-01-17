<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : documenttype (documenttypeController)
 * documenttype Class to control all documenttype related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Documenttype extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('documenttype_model');
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

    public function getDataDocumentType($module)
    {
        $this->load->model('documenttype_model'); 
        echo json_encode($this->documenttype_model->getListing($module,$this->condominium)); 
    }
    

    /**
     * This function used to load the first screen of the documenttype
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    public function getDataModule($code = '')
    {
        $this->load->model('module_model');
        if(empty($code)){
            return $this->module_model->getListing( $this->condominium);
        }else{
            return $this->module_model->getInfoCode($code, $this->condominium);
        } 
    }

    /**
     * This function is used to load the documenttype list
     */
    public function listing($module = '')
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $moduleInfo =!empty($module)? $this->getDataModule($module):"";
            $data['moduleInfo'] =$moduleInfo;
            $data['documenttypeRecords'] = $this->documenttype_model->Listing(!empty($moduleInfo)?$moduleInfo->id:"", $this->condominium); 
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de tipos de documentos';
            if(empty($module)){
                $this->loadViews("documenttype/documenttype", $this->globalApp, $data, null);
            }else{
                if(!empty($moduleInfo)){
                    $this->loadViews("documenttype/setmodule", $this->globalApp, $data, null);
                }else{
                    redirect('tipodocumento');
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
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo tipos de documentos';
            $data['moduleRecords'] = $this->getDataModule();
            $this->loadViews("documenttype/addNew", $this->globalApp, $data, null);
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
            $result = $this->documenttype_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->documenttype_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new documenttype to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('bills', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $view =  $this->input->post('view') ;
            $this->load->library('form_validation');
            $this->form_validation->set_rules('module', 'Modulo', 'trim|required|max_length[11]');
            $this->form_validation->set_rules('name', 'Descripcion', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('code', 'Codigo', 'trim|required|max_length[10]');
            if ($this->form_validation->run() == false) {
                if(!isset($view)){
                    $this->addNew();
                }else{
                     $code_heading =  $this->input->post('code_heading') ;
                    redirect('asignacion_documentos/'. $code_heading );
                }
            } else { 
                $module = (strtoupper($this->security->xss_clean($this->input->post('module'))));
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $code = strtoupper($this->security->xss_clean($this->input->post('code')));

                $check = $this->documenttype_model->checkExists($code, 0, $this->condominium);
                if (empty($check)) {
                    $info = array('code' => $code,'module' => $module, 'name' => $name, 'condominium' => $this->condominium);
                    $result = $this->documenttype_model->addNew($info);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'tipos de documentos creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del tipos de documentos');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo ya se encuentra ocupado por otro tipos de documentos');
                }
               
                if(!isset($view)){
                    redirect('agregartipodocumento');
                }else{
                     $code_module =  $this->input->post('code_module') ;
                    redirect('asignacion_documentos/'. $code_module );
                }
                   
            }
        }
    }

    /**
     * This function is used load documenttype edit information
     * @param number $documenttypeId : Optional : This is documenttype id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('tipodocumento');
            }
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $data['documenttypeInfo'] = $this->documenttype_model->getInfo($id, $this->condominium);
            $data['moduleRecords'] = $this->getDataModule();
            if ($data['documenttypeInfo'] == null) {
                redirect('tipodocumento');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar tipos de documentos';

            $this->loadViews("documenttype/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the documenttype information
     */
    public function edit()
    {
        if (checkForPermissions('bills', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');
            $this->form_validation->set_rules('name', 'Descripcion', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('code', 'Codigo', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('module', 'Modulo', 'trim|required|max_length[11]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));  $module = (strtoupper($this->security->xss_clean($this->input->post('module'))));
                $code = strtoupper($this->security->xss_clean($this->input->post('code')));
                $check = $this->documenttype_model->checkExists($code, $id, $this->condominium);
                if (empty($check)) {
                    $info = array('code' => $code, 'module' => $module,'name' => $name);
                    $result = $this->documenttype_model->edit($info, $id, $this->condominium);
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'tipos de documentos actualizado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la actulaizacion del tipos de documentos');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo ya se encuentra ocupado por otro tipos de documentos');
                }

                redirect("editartipodocumento/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the documenttype using documenttypeId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('bills', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL tipos de documentos TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->documenttype_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('bills', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $documenttypeId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $documenttypeInfo = array('status' => $isactive);

            $result = $this->documenttype_model->edit($documenttypeInfo, $documenttypeId, $this->condominium);

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

            $createArray = array('CODIGO', 'DESCRIPCION', 'MODULO');
            $makeArray = array('CODIGO' => 'CODIGO', 'DESCRIPCION' => 'DESCRIPCION', 'MODULO' => 'MODULO');
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
                    $code = $SheetDataKey['CODIGO'];
                    $description = $SheetDataKey['DESCRIPCION'];
                    $module = $SheetDataKey['MODULO'];

                    $module = filter_var(trim($allDataInSheet[$i][$module]), FILTER_SANITIZE_STRING);
                    $code = filter_var(trim($allDataInSheet[$i][$code]), FILTER_SANITIZE_STRING);
                    $description = filter_var(trim($allDataInSheet[$i][$description]), FILTER_SANITIZE_STRING);

                    if (!empty($code)) { 
                        $check = $this->documenttype_model->checkExists($code, 0, $this->condominium);
                        if (empty($check)) {
                            $checkModule = $this->getDataModule($module);
                            $info = array('module' => $checkModule->id,'code' => $code, 'name' => $description, 'condominium' => $this->condominium);
                            $result = $this->documenttype_model->addNew($info);
                        }
                    }

                }
                $this->session->set_flashdata('success', 'Tipos de cuentas Importados con exito');

            } else {
                $this->session->set_flashdata('error', 'Error al importar el archivo');

            }
        } else {
            $this->session->set_flashdata('error', $import_xls_file.'No hay datos en el documento');
        }
        redirect('tipodocumento');

    }
}
