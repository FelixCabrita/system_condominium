<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : module (moduleController)
 * module Class to control all module related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Module extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('module_model');
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
     * This function used to load the first screen of the module
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("tablero", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the module list
     */
    public function listing()
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['moduleRecords'] = $this->module_model->Listing($this->id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de modulo';
            $this->loadViews("module/module", $this->globalApp, $data, null);
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
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo modulo';
            $this->loadViews("module/addNew", $this->globalApp, null, null);
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
            $result = $this->module_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->module_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new module to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('bills', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Descripcion', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('code', 'Codigo', 'trim|required|max_length[10]');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $code = strtoupper($this->security->xss_clean($this->input->post('code')));

                $check = $this->module_model->checkExists($code, 0, $this->condominium);
                if (empty($check)) {
                    $info = array('code' => $code, 'name' => $name, 'condominium' => $this->condominium);
                    $result = $this->module_model->addNew($info);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'modulo creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del modulo');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo ya se encuentra ocupado por otro modulo');
                }

                redirect('agregarmodulos');
            }
        }
    }

    /**
     * This function is used load module edit information
     * @param number $moduleId : Optional : This is module id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('modulos');
            }
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $data['moduleInfo'] = $this->module_model->getInfo($id, $this->condominium);

            if ($data['moduleInfo'] == null) {
                redirect('modulos');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar modulo';

            $this->loadViews("module/editOld", $this->globalApp, $data, null);
        }
    }

    
    /**
     * This function is used to edit the module information
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
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $code = strtoupper($this->security->xss_clean($this->input->post('code')));
                $check = $this->module_model->checkExists($code, $id, $this->condominium);
                if (empty($check)) {
                    $info = array('code' => $code, 'name' => $name);
                    $result = $this->module_model->edit($info, $id, $this->condominium);
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'modulo actualizado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la actulaizacion del modulo');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo ya se encuentra ocupado por otro modulo');
                }

                redirect("editarmodulos/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the module using moduleId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('bills', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL modulo TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->module_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('bills', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $moduleId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $moduleInfo = array('status' => $isactive);

            $result = $this->module_model->edit($moduleInfo, $moduleId, $this->condominium);

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

            $createArray = array('CODIGO', 'DESCRIPCION');
            $makeArray = array('CODIGO' => 'CODIGO', 'DESCRIPCION' => 'DESCRIPCION');
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

                    $code = filter_var(trim($allDataInSheet[$i][$code]), FILTER_SANITIZE_STRING);
                    $description = filter_var(trim($allDataInSheet[$i][$description]), FILTER_SANITIZE_STRING);

                    if (!empty($code)) {
                        $check = $this->module_model->checkExists($code, 0, $this->condominium);
                        if (empty($check)) {
                            $info = array('code' => $code, 'name' => $description, 'condominium' => $this->condominium);
                            $result = $this->module_model->addNew($info);
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
        redirect('modulos');

    }
  
}
