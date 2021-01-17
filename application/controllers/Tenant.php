<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Tenant (TenantController)
 * Tenant Class to control all Tenant related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Tenant extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tenant_model');
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
     * This function used to load the first screen of the Tenant
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    public function getDataEdifices($code = '')
    {
        $this->load->model('edifice_model');
        if (empty($code)) {
            return $this->edifice_model->getListing($this->condominium);
        } else {
            return $this->edifice_model->getInfoCode($code, $this->condominium);
        }
    }

    /**
     * This function is used to load the Tenant list
     */
    public function listing()
    {
        if (checkForPermissions('tenants', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['TenantRecords'] = $this->tenant_model->Listing($this->id, $this->condominium);
            $data['parameter'] = COUNT($this->tenant_model->getListingCount( $this->condominium));
            $data['permissions'] = $this->getPermissions()->{'tenants'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de clientes';
            $this->loadViews("tenant/tenant", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the Tenant list
     */
    public function assignments($id)
    {
        if (checkForPermissions('tenants', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['AssignmentRecords'] = $this->tenant_model->Assignment($id, $this->condominium);
            $data['AssignmentActive'] = $this->tenant_model->AssignmentActive($id, $this->condominium);
            $data['edificeRecords'] = $this->getDataEdifices();
            $data['TenantInfo'] = $this->tenant_model->getInfo($id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'tenants'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de asignaciones';
            $this->loadViews("tenant/assigning_apartment", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('tenants', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $data['parameter'] = COUNT($this->tenant_model->getListingCount($this->condominium));
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nueva cliente';
            $this->loadViews("tenant/addNew", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    public function checkExists()
    {
        $id = $this->input->post("id");
        $code = $this->input->post("document");

        if (empty($id)) {
            $result = $this->tenant_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->tenant_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to check whether email already exist or not
     */
    public function getTenant()
    {
        $document = $this->input->post("document");
        $result = $this->tenant_model->getInfodocument($document, $this->condominium);
        if ($result) {echo (json_encode(array('status' => true, "tenant" => $result)));} else {echo (json_encode(array('status' => false)));}
    }

    /**
     * This function is used to add new Tenant to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('tenants', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('document', 'Docuento', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('name', 'Nombre completo', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('mobile', 'Telefono Movil', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('landline_phone', 'Telefono residencial', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('email', 'Correo electronico', 'trim|required|valid_email|max_length[100]');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $document = (strtoupper($this->security->xss_clean($this->input->post('document'))));
                $name = strtoupper($this->security->xss_clean($this->input->post('name')));
                $mobile = (strtoupper($this->security->xss_clean($this->input->post('mobile'))));
                $landline_phone = strtoupper($this->security->xss_clean($this->input->post('landline_phone')));
                $email = (strtolower($this->security->xss_clean($this->input->post('email'))));

                $type = (strtolower($this->security->xss_clean($this->input->post('type'))));

                $check = $this->tenant_model->checkExists($document, 0, $this->condominium);

                if (empty($check)) {
                    $password = $this->input->post('user') == 'on' ? getHashedPassword($document) : "";
                    $info = array('document' => $document, 'name' => $name, 'type' => $type, 'password' => $password, 'mobile' => $mobile, 'condominium' => $this->condominium,
                        'landline_phone' => $landline_phone, 'email' => $email);
                    $result = $this->tenant_model->addNew($info);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Clientes creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del clientes');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El documento de clientes ya existe, por favor compruebe e intente de nuevo');
                }
                redirect('agregarclientes');
            }
        }
    }

    /**
     * This function is used load Tenant edit information
     * @param number $TenantId : Optional : This is Tenant id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('tenants', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('clientes');
            }
            $data['permissions'] = $this->getPermissions()->{'tenants'};
            $data['TenantInfo'] = $this->tenant_model->getInfo($id, $this->condominium);
            $data['parameter'] = COUNT($this->tenant_model->getListingCount($this->condominium));
            if ($data['TenantInfo'] == null) {
                redirect('clientes');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar cliente';

            $this->loadViews("tenant/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the Tenant information
     */
    public function edit()
    {
        if (checkForPermissions('tenants', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');
            $this->form_validation->set_rules('document', 'Docuento', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('name', 'Nombre completo', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('mobile', 'Telefono Movil', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('landline_phone', 'Telefono residencial', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('email', 'Correo electronico', 'trim|required|valid_email|max_length[100]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $document = (strtoupper($this->security->xss_clean($this->input->post('document'))));
                $name = strtoupper($this->security->xss_clean($this->input->post('name')));
                $mobile = (strtoupper($this->security->xss_clean($this->input->post('mobile'))));
                $landline_phone = strtoupper($this->security->xss_clean($this->input->post('landline_phone')));
                $email = (strtolower($this->security->xss_clean($this->input->post('email'))));
                $type = (($this->security->xss_clean($this->input->post('type'))));
                $check = $this->tenant_model->checkExists($document, $id, $this->condominium);
                if (empty($check)) {
                    $password = $this->tenant_model->getInfo($id, $this->condominium)->password;
                    if ($this->input->post('user') == 'on' && empty($password)) {
                        $password = getHashedPassword($document);
                    } else {
                        $password = "";
                    }
                    $info = array('document' => $document, 'name' => $name, 'mobile' => $mobile, 'type' => $type, 'condominium' => $this->condominium,
                        'landline_phone' => $landline_phone, 'password' => ($password), 'email' => $email);
                    $result = $this->tenant_model->edit($info, $id, $this->condominium);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'clientes actualizado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la actualizacion del clientes');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El documento de clientes ya existe, por favor compruebe e intente de nuevo');
                }
                redirect("editarclientes/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the Tenant using TenantId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('tenants', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL cliente TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->tenant_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('tenants', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $TenantId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $TenantInfo = array('password' => "");

            $result = $this->tenant_model->edit($TenantInfo, $TenantId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function getApartmentsAssignation()
    {
        $role = $this->input->post('role');
        $id = $this->input->post('id');
        $edifice = $this->input->post('edifice');
        $this->load->model('tenant_model');
        $result = $this->tenant_model->getAssignment($id, $this->condominium, $edifice);
        if ($result > 0) {echo (json_encode(array('status' => true, 'asig' => $result)));} else {echo (json_encode(array('status' => false)));}

    }
    public function set_asignation()
    {
        if (checkForPermissions('tenants', PERMISSION_CREATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $tenant = $this->input->post('id');
            $edifice = $this->input->post('edifice');
            $date_start = $this->input->post('date_start');
            $apartment = $this->input->post('apartment');
            $check = $this->tenant_model->checkExistsAsignation($edifice, $apartment, $tenant, $this->condominium);
            $tenantResult = $this->tenant_model->getInfo($tenant, $this->condominium);
            if (empty($check)) {
                $checkExistsAsignationOwner = $tenantResult->type == ROLE_OWNER ? $this->tenant_model->checkExistsAsignationOwner($edifice, $apartment, $this->condominium) : "";
                if (empty($checkExistsAsignationOwner)) {
                    $info = array('tenant' => $tenant, 'edifice' => $edifice, 'apartment' => $apartment, 'created_at' => $date_start, 'condominium' => $this->condominium);
                    $result = $this->tenant_model->set_asignation($info);
                    if ($result) {
                        echo (json_encode(array('status' => true)));
                    } else {echo (json_encode(array('status' => false, "error" => "")));}
                } else {
                    echo (json_encode(array('status' => false, "error" => "Este apartamento ya esta asignado a otro propietario")));
                }
            } else {
                echo (json_encode(array('status' => false, "error" => "Hay una asignacion similar activa en estos momentos, por favor verifiquela")));
            }
        }
    }

    public function delete_asignation()
    {
        if (checkForPermissions('tenants', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $Id = $this->input->post('id');
            $type = $this->input->post('type');
            if ($type == ROLE_TENANT) {
                $observation = $this->input->post('observation');
                $info = array('observation' => $observation, 'status' => 0, 'updated_at' => date('Y-m-d'));
                $result = $this->tenant_model->edit_asingnation($info, $Id, $this->condominium);

                if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
            } else {
                $document = $this->input->post('observation');
                $tenantResult = $this->tenant_model->getInfodocument($document, $this->condominium);

                if ($tenantResult) {
                    if ($tenantResult->type == ROLE_OWNER) {
                        $getInfoAssignment = $this->tenant_model->getInfoAssignment($Id, $this->condominium);

                        $info = array('observation' => "Traspaso de propiedad", 'status' => 0, 'updated_at' => date('Y-m-d'));
                        $result = $this->tenant_model->edit_asingnation($info, $Id, $this->condominium);

                        if ($result > 0) {
                            $info = array('tenant' => $tenantResult->id, 'edifice' => $getInfoAssignment->edifice, 'apartment' => $getInfoAssignment->apartment, 'created_at' => date('Y-m-d'), 'condominium' => $this->condominium);
                            $result = $this->tenant_model->set_asignation($info);

                            if ($result) {
                                echo (json_encode(array('status' => true)));
                            } else {echo (json_encode(array('status' => false, "error" => "")));}
                        } else {echo (json_encode(array('status' => false, 'error' => "No podemos cambiar el propietario de este apartamento.")));}
                    } else {echo (json_encode(array('status' => false, 'error' => "No podemos cambiar el propietario de este apartamento a un cliente tipo cliente.")));}
                } else {echo (json_encode(array('status' => false, 'error' => "El documento que ha colocado no existe, por favor verifique e intente de nuevo.")));}
            }

        }
    }

    public function getDataAsignation($tenant)
    {
        $this->load->model('apartment_model');
        echo json_encode($this->apartment_model->getListing($edifice, $this->condominium));
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
            $import_xls_file ='';
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

            $createArray = array('DOCUMENTO', 'NOMBRE', 'TIPO_USUARIO', 'TELEFONO_MOVIL', 'TELEFONO_RESIDENCIA', 'CORREO_ELECTRONICO', 'TIENE_USUARIO');
            $makeArray = array('DOCUMENTO' => 'DOCUMENTO', 'NOMBRE' => 'NOMBRE', 'TIPO_USUARIO' => 'TIPO_USUARIO', 'TELEFONO_MOVIL' => 'TELEFONO_MOVIL',
                'TELEFONO_RESIDENCIA' => 'TELEFONO_RESIDENCIA', 'CORREO_ELECTRONICO' => 'CORREO_ELECTRONICO', 'TIENE_USUARIO' => 'TIENE_USUARIO');
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
                    //'DOCUMENTO', 'NOMBRE', 'TIPO_USUARIO', 'TELEFONO_MOVIL'
                    $document = $SheetDataKey['DOCUMENTO'];
                    $name = $SheetDataKey['NOMBRE'];
                    $type = $SheetDataKey['TIPO_USUARIO'];
                    $mobile = $SheetDataKey['TELEFONO_MOVIL'];
                    $landline_phone = $SheetDataKey['TELEFONO_RESIDENCIA'];
                    $email = $SheetDataKey['CORREO_ELECTRONICO'];
                    $user = $SheetDataKey['TIENE_USUARIO'];

                    $document = filter_var(trim($allDataInSheet[$i][$document]), FILTER_SANITIZE_STRING);
                    $name = filter_var(trim($allDataInSheet[$i][$name]), FILTER_SANITIZE_STRING);
                    $type = filter_var(trim($allDataInSheet[$i][$type]), FILTER_SANITIZE_STRING);
                    $mobile = filter_var(trim($allDataInSheet[$i][$mobile]), FILTER_SANITIZE_STRING);
                    $landline_phone = filter_var(trim($allDataInSheet[$i][$landline_phone]), FILTER_SANITIZE_STRING);
                    $email = filter_var(trim($allDataInSheet[$i][$email]), FILTER_SANITIZE_STRING);
                    $user = filter_var(trim($allDataInSheet[$i][$user]), FILTER_SANITIZE_STRING);
                    $type = $this->type_client_import($type);
                    $check = $this->tenant_model->checkExists($document, 0, $this->condominium);
                    if (!empty($document)) {
                        if (empty($check)) {
                            $password = $user == 'SI' ? getHashedPassword($document) : "";
                            $info = array('document' => $document, 'name' => $name, 'type' => $type, 'password' => $password, 'mobile' => $mobile, 'condominium' => $this->condominium,
                                'landline_phone' => $landline_phone, 'email' => $email);
                            $result = $this->tenant_model->addNew($info);
                        }
                    }

                }
                $this->session->set_flashdata('success', 'Clientes Importados con exito');

            } else {
                $this->session->set_flashdata('error', 'Error al importar el archivo');

            }
        } else {
            $this->session->set_flashdata('error', 'Error al importar el archivo');
        }
        redirect('clientes');

    }

    public function type_client_import($type_text)
    {
        switch ($type_text) {
            case 'PROPIETARIO':
                return '6';
                break;
            case 'INQUILINO':
                return '7';
                break;
        }
    }

}
