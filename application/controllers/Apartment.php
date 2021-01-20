<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Apartment (ApartmentController)
 * Apartment Class to control all Apartment related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Apartment extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('apartment_model');
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

    public function getDataEdifices($code = '')
    {
        $this->load->model('edifice_model');
        if(empty($code)){
            return $this->edifice_model->getListing( $this->condominium);
        }else{
            return $this->edifice_model->getInfoCode($code, $this->condominium);
        } 
    }

    public function getDataApartments($edifice)
    {
        $this->load->model('apartment_model'); 
        echo json_encode($this->apartment_model->getListing($edifice,$this->condominium)); 
    }
    

    /**
     * This function used to load the first screen of the Apartment
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("tablero", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the Apartment list
     */
    public function listing()
    {
        if (checkForPermissions('apartments', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['ApartmentRecords'] = $this->apartment_model->Listing($this->id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'apartments'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de apartamentos';
            $this->loadViews("apartments/apartments", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('apartments', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo apartamento';
            $data['edificeRecords'] = $this->getDataEdifices();
            $this->loadViews("apartments/addNew", $this->globalApp, $data, null);
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
            $result = $this->apartment_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->apartment_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new Apartment to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('apartments', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('apartment', 'Apartamento', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('edifice', 'Edificio', 'trim|required|max_length[20]');
            $this->form_validation->set_rules('number_parking', 'Numero de parqueo', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('code', 'Codigo Unico', 'trim|required|max_length[30]'); 
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $apartment = (($this->security->xss_clean($this->input->post('apartment'))));
                $edifice = ($this->security->xss_clean($this->input->post('edifice')));
                $number_parking = ($this->security->xss_clean($this->input->post('number_parking')));
                $conditions = strtoupper($this->security->xss_clean($this->input->post('conditions')));
                $code = $edifice.$apartment;
                $conditions = (empty($conditions)?CONDITIONS[0]:$conditions); 
                $check = $this->apartment_model->checkExists($code, 0, $this->condominium);
               
                if (empty($check)) {
                    $dataEdifice = $this->getDataEdifices($edifice);
                    
                    $info = array('apartment' => $apartment, 'edifice' => $dataEdifice->id, 'number_parking' => $number_parking, 'condominium' => $this->condominium,
                     'conditions' => $conditions, 'code' => $code);
                    $result = $this->apartment_model->addNew($info);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'apartamento creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del apartamento');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo de apartamento ya existe, por favor compruebe e intente de nuevo');
                }
                redirect('agregareapartamento');
            }
        }
    }

    /**
     * This function is used load Apartment edit information
     * @param number $ApartmentId : Optional : This is Apartment id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('apartments', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('apartamentos');
            }
            $data['permissions'] = $this->getPermissions()->{'apartments'};
            $data['ApartmentInfo'] = $this->apartment_model->getInfo($id, $this->condominium);
            $data['edificeRecords'] = $this->getDataEdifices();
            if ($data['ApartmentInfo'] == null) {
                redirect('apartamentos');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar apartamentos';

            $this->loadViews("apartments/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the Apartment information
     */
    public function edit()
    {
        if (checkForPermissions('apartments', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');

            $this->form_validation->set_rules('number_parking', 'Numero de parqueo', 'trim|required|max_length[10]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $number_parking = ($this->security->xss_clean($this->input->post('number_parking')));
                $conditions = strtoupper($this->security->xss_clean($this->input->post('conditions')));

                $info = array('number_parking' => $number_parking, 'conditions' => $conditions);

                $result = $this->apartment_model->edit($info, $id, $this->condominium);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'apartamento actualizado');
                } else {
                    $this->session->set_flashdata('error', 'Fallo actualización');
                }
                redirect("editarapartamento/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the Apartment using ApartmentId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('apartments', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL apartamento TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->apartment_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('apartments', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $ApartmentId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $ApartmentInfo = array('status' => $isactive);

            $result = $this->apartment_model->edit($ApartmentInfo, $ApartmentId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function importData() {
        $this->load->library('excel');     
        $this->load->helper(array('form', 'url'));   
            $path = "./assets/excel/";
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls';
            $config['remove_spaces'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('userfile')) {
                $error = array('error' => $this->upload->display_errors());
                
                $this->session->set_flashdata('error',$this->upload->display_errors());
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
            $flag = 0;//Existencia	PRECIO1	PRECIO2	PRECIO3	GRUPO	MARCA	IMPUESTO	COD_BARRA	ACTIVO

            $createArray = array('EDIFICIO', 'APARTAMENTO', 'NRO_PARQUEO', 'CONDICION');
            $makeArray = array('EDIFICIO' => 'EDIFICIO', 'APARTAMENTO' => 'APARTAMENTO', 'NRO_PARQUEO' => 'NRO_PARQUEO', 'CONDICION' => 'CONDICION' );
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
                    //'EDIFICIO', 'EDIFICIO', 'NRO_PARQUEO', 'CONDICION'
                    $edifice = $SheetDataKey['EDIFICIO'];
                    $apartment = $SheetDataKey['APARTAMENTO'];
                    $number_parking = $SheetDataKey['NRO_PARQUEO'];
                    $condition = $SheetDataKey['CONDICION']; 


                    $apartment = filter_var(trim($allDataInSheet[$i][$apartment]), FILTER_SANITIZE_STRING);
                    $edifice = filter_var(trim($allDataInSheet[$i][$edifice]), FILTER_SANITIZE_STRING);
                    $number_parking = filter_var(trim($allDataInSheet[$i][$number_parking]), FILTER_SANITIZE_STRING);
                    $condition = filter_var(trim($allDataInSheet[$i][$condition]), FILTER_SANITIZE_STRING); 

                    if(!empty($apartment)){
                        $code =$edifice.$apartment; 
                        $check = $this->getDataEdifices($edifice);
                        
                        if (($check)) { 
                       
                            $info = array('apartment' => $apartment, 'edifice' => $check->id, 'number_parking' => $number_parking, 'condominium' => $this->condominium,
                            'conditions' => $condition, 'code' => $code);
                           $result = $this->apartment_model->addNew($info);
                        } 
                    }
                   
                }   
                $this->session->set_flashdata('success', 'Apartamentos Importados con exito');
              
            } else {
                $this->session->set_flashdata('error', 'Error al importar el archivo');
               
            } 
        } else {
            $this->session->set_flashdata('error', 'Error al importar el archivo');
        }
           redirect('apartamentos');
        
    }
}
