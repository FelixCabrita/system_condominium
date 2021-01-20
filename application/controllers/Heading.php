<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : heading (headingController)
 * heading Class to control all heading related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Heading extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('heading_model');
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

    public function getDataHeading()
    {
        $this->load->model('apartment_model'); 
        echo json_encode($this->heading_model->getListing($this->condominium)); 
    }

    /**
     * This function used to load the first screen of the heading
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("tablero", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the heading list
     */
    public function listing()
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['headingRecords'] = $this->heading_model->Listing($this->id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de rubros';
            $this->loadViews("heading/heading", $this->globalApp, $data, null);
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
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo rubros';
            $this->loadViews("heading/addNew", $this->globalApp, null, null);
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
            $result = $this->heading_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->heading_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new heading to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('bills', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Concepto', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('code', 'Codigo', 'trim|required|max_length[10]');
            if ($this->form_validation->run() == false) {
                redirect('rubros');
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('name'))));
                $code = (strtoupper($this->security->xss_clean($this->input->post('code'))));
                $check = $this->heading_model->checkExists($code, 0, $this->condominium);
                if (empty($check)) {
                    $info = array('code' => $code, 'name' => $name, 'condominium' => $this->condominium);
                    $result = $this->heading_model->addNew($info);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Rubro creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del rubro');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo ya se encuentra ocupado por otro rubro');
                }

                redirect('rubros');
            }
        }
    }

    /**
     * This function is used load heading edit information
     * @param number $headingId : Optional : This is heading id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('rubross');
            }
            $data['permissions'] = $this->getPermissions()->{'bills'};
            $data['headingInfo'] = $this->heading_model->getInfo($id, $this->condominium);

            if ($data['headingInfo'] == null) {
                redirect('rubross');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar rubros';

            $this->loadViews("heading/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the heading information
     */
    public function edit()
    {
        if (checkForPermissions('bills', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');
            $this->form_validation->set_rules('name', 'Descripcion', 'trim|required|max_length[200]');
            if ($this->form_validation->run() == false) {
                echo json_encode(array('status' => false, "error" => 'Fallo la actulaizacion del rubros'));
            } else {
                $name = (strtoupper($this->security->xss_clean($this->input->post('name')))); 
                $info = array('name' => $name);
                $result = $this->heading_model->edit($info, $id, $this->condominium);
                if ($result > 0) {
                    $this->session->set_flashdata('success', 'rubros actualizado exitosamente');
                    echo json_encode(array('status' => true));
                } else {
                    $this->session->set_flashdata('error', 'Fallo la actulaizacion del rubros');
                    echo json_encode(array('status' => false, "error" => 'Fallo la actulaizacion del rubros'));
                }
                
            }
        }
    }

    /**
     * This function is used to delete the heading using headingId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('bills', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL rubros TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->heading_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('bills', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $headingId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $headingInfo = array('status' => $isactive);

            $result = $this->heading_model->edit($headingInfo, $headingId, $this->condominium);

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

            $createArray = array('CODIGO', 'CONCEPTO');
            $makeArray = array('CODIGO' => 'CODIGO', 'CONCEPTO' => 'CONCEPTO');
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

                    $code = $SheetDataKey['CODIGO'];
                    $name = $SheetDataKey['CONCEPTO'];
                    $code = filter_var(trim($allDataInSheet[$i][$code]), FILTER_SANITIZE_STRING);
                    $name = filter_var(trim($allDataInSheet[$i][$name]), FILTER_SANITIZE_STRING);

                    if (!empty($name)) {
                        $check = $this->heading_model->checkExists($code, 0, $this->condominium);
                        if (empty($check)) {
                            $info = array('code' => $code, 'name' => $name, 'condominium' => $this->condominium);
                            $result = $this->heading_model->addNew($info);
                        }
                    }

                }
                $this->session->set_flashdata('success', 'Rubros Importados con exito');

            } else {
                $this->session->set_flashdata('error', 'Error al importar el archivo');

            }
        } else {
            $this->session->set_flashdata('error', $import_xls_file . 'No hay datos en el documento');
        }
        redirect('rubros');

    }

}
