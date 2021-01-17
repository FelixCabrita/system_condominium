<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : employees (employeesController)
 * employees Class to control all employees related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Employees extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('employees_model');
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
     * This function used to load the first screen of the employees
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    public function getDataApartment()
    {
        if ($this->rol > ROLE_VIGILANT) {
            $this->load->model('tenant_model');
            return $this->tenant_model->getAssignment($id, $this->condominium);
        } else {   
            $this->load->model('apartment_model');
            return $this->apartment_model->getListing("", $this->condominium); 
        }
    } 
    
    public function getDataEmployee($document)
    { 
        echo json_encode($this->employees_model->getDocument($document,$this->condominium)); 
    }

    /**
     * This function is used to load the employees list
     */
    public function listing()
    {
        if (checkForPermissions('employees', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['employeesRecords'] = $this->employees_model->Listing($this->rol > ROLE_VIGILANT?$this->id:"", $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'employees'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de empleados';
            $this->loadViews("employees/employees", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('employees', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $data['apartmentRecord'] = $this->getDataApartment();
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo empleado';
            $this->loadViews("employees/addNew", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    public function checkExists()
    {
        $id = $this->input->post("id");
        $document = $this->input->post("document");
        $apartment = $this->input->post("apartment");

        if (empty($id)) {
            $result = $this->employees_model->checkExists($document, 0, $apartment, $this->condominium);
        } else {
            $result = $this->employees_model->checkExists($document, $id, $apartment, $this->condominium);
        }
        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new employees to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('employees', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('document', 'Docuento de identidad', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('apartment', 'Apartamento donde trabajara', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('name', 'Nombre completo', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('mobile', 'Telefono Movil', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('started_work', 'Inicio a trabajar', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('work_type', 'Tipo de labor', 'trim|required|max_length[20]');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $document = (strtoupper($this->security->xss_clean($this->input->post('document'))));
                $name = strtoupper($this->security->xss_clean($this->input->post('name')));
                $mobile = (strtoupper($this->security->xss_clean($this->input->post('mobile'))));
                $started_work = strtoupper($this->security->xss_clean($this->input->post('started_work')));
                $work_type = (strtolower($this->security->xss_clean($this->input->post('work_type'))));
                $apartment = (strtolower($this->security->xss_clean($this->input->post('apartment'))));
                $check = $this->employees_model->checkExists($document, 0, $apartment, $this->condominium);

                if (empty($check)) {
                    $info = array('document' => $document, 'name' => $name, 'started_work' => $started_work, 'work_type' => $work_type, 'mobile' => $mobile,
                        'condominium' => $this->condominium, 'apartment' => $apartment);
                    $result = $this->employees_model->addNew($info);

                    $this->load->model('vigilant_model');
                    $info = array('document' => $document, 'name' => $name, 'mobile' => $mobile , 'type'=> VISITOR_EMPLOYEES, 'condominium' => $this->condominium );
                    $idVisitor = $this->vigilant_model->addVisitor($info); 

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Empleado creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del empleado');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El documento de empleado ya existe, por favor compruebe e intente de nuevo');
                }
                redirect('agregarempleados');
            }
        }
    }

    /**
     * This function is used load employees edit information
     * @param number $employeesId : Optional : This is employees id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('employees', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('empleados');
            }
            $data['permissions'] = $this->getPermissions()->{'employees'};
            $data['employeesInfo'] = $this->employees_model->getInfo($id, $this->condominium);
            $data['apartmentRecord'] = $this->getDataApartment();
            if ($data['employeesInfo'] == null) {
                redirect('empleados');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar empleado';

            $this->loadViews("employees/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the employees information
     */
    public function edit()
    {
        if (checkForPermissions('employees', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');
            $this->form_validation->set_rules('document', 'Docuento de identidad', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('apartment', 'Apartamento donde trabajara', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('name', 'Nombre completo', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('mobile', 'Telefono Movil', 'trim|required|max_length[100]');
            $this->form_validation->set_rules('started_work', 'Inicio a trabajar', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('work_type', 'Tipo de labor', 'trim|required|max_length[20]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $document = (strtoupper($this->security->xss_clean($this->input->post('document'))));
                $name = strtoupper($this->security->xss_clean($this->input->post('name')));
                $mobile = (strtoupper($this->security->xss_clean($this->input->post('mobile'))));
                $started_work = strtoupper($this->security->xss_clean($this->input->post('started_work')));
                $work_type = (strtolower($this->security->xss_clean($this->input->post('work_type'))));
                $apartment = (strtolower($this->security->xss_clean($this->input->post('apartment'))));
                $check = $this->employees_model->checkExists($document, $id ,$apartment , $this->condominium);

                if (empty($check)) {
                    $info = array('document' => $document, 'name' => $name, 'started_work' => $started_work, 'work_type' => $work_type, 'mobile' => $mobile,
                        'condominium' => $this->condominium, 'apartment' => $apartment);
                    $result = $this->employees_model->edit($info, $id, $this->condominium);

                 
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'Empleado actualizado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la actualizacion del empleado');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El documento de empleado ya existe, por favor compruebe e intente de nuevo');
                }
                redirect("editarempleados/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the employees using employeesId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('employees', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL cliente TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->employees_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }
    public function changeStatus()
    {
        if (checkForPermissions('employees', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else { 
            $employeesId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $observation = $this->input->post('observation');  
            $employees = $this->employees_model->getInfo($employeesId, $this->condominium); 
            $observation = (empty($employees->observations)?"":$employees->observation." | ").$observation; 

            $employeesInfo = array('status' => $isactive,'observation' => $observation); 
            $result = $this->employees_model->edit($employeesInfo, $employeesId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }
 
}
