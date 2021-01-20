<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : vehicles (vehiclesController)
 * vehicles Class to control all vehicles related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Vehicles extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('vehicles_model');
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
     * This function used to load the first screen of the vehicles
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("tablero", $this->globalApp, null, null);
    }

    public function getDataTag($id = '')
    {
        $this->load->model('tag_model');
        return $this->tag_model->getInfo($id, $this->condominium);

    }

    public function getDataTenant()
    {
        $this->load->model('tenant_model');
        return $this->tenant_model->getListing($this->condominium);
    }

    /**
     * This function is used to load the vehicles list
     */
    public function listing()
    {
        if (checkForPermissions('vehicles', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {

            $data['vehiclesRecords'] = $this->vehicles_model->Listing($this->rol > ROLE_VIGILANT ? $this->id : "", $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'vehicles'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de vehiculos';
            $this->loadViews("vehicles/vehicles", $this->globalApp, $data, null);
        }
    }
    /**
     * This function is used to load the vehicles list
     */
    public function asignation_vehicle($id = null)
    {
        if (checkForPermissions('vehicles', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('vehiculos');
            }
            $getDataTag = $this->getDataTag($id);
            $data['tag'] = $getDataTag;
            $data['vehiclesRecords'] = $this->vehicles_model->ListingforAparment($getDataTag->apartment, $this->condominium);

            $data['permissions'] = $this->getPermissions()->{'vehicles'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Asignacion de vehiculo';
            $this->loadViews("vehicles/asignation", $this->globalApp, $data, null);
        }
    }
    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('vehicles', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo vehiculo';
            $this->loadViews("vehicles/addNew", $this->globalApp, null, null);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    public function checkExists()
    {
        $id = $this->input->post("id");
        $licence_plate = $this->input->post("licence_plate");
        if (empty($id)) {
            $result = $this->vehicles_model->checkExists($licence_plate, 0, $this->condominium);
        } else {
            $result = $this->vehicles_model->checkExists($licence_plate, $id, $this->condominium);
        }
        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new vehicles to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('vehicles', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('licence_plate', 'Docuento de identidad', 'trim|required|max_length[20]');
            $this->form_validation->set_rules('model', 'Apartamento donde trabajara', 'trim|required|max_length[20]');
            $this->form_validation->set_rules('year', 'Nombre completo', 'trim|required|max_length[4]');
            $this->form_validation->set_rules('brand', 'Telefono Movil', 'trim|required|max_length[20]');
            $this->form_validation->set_rules('colour', 'Inicio a trabajar', 'trim|required|max_length[20]');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $id = (strtoupper($this->security->xss_clean($this->input->post('tenant'))));
                $licence_plate = strtoupper($this->security->xss_clean($this->input->post('licence_plate')));
                $model = (strtoupper($this->security->xss_clean($this->input->post('model'))));
                $year = strtoupper($this->security->xss_clean($this->input->post('year')));
                $brand = (strtoupper($this->security->xss_clean($this->input->post('brand'))));
                $colour = (strtoupper($this->security->xss_clean($this->input->post('colour'))));
                $condition_car = (strtoupper($this->security->xss_clean($this->input->post('condition_car'))));

                $check = $this->vehicles_model->checkExists($licence_plate, 0, $this->condominium);

                if (empty($check)) {
                    $info = array('licence_plate' => $licence_plate, 'tenant' => $id, 'model' => $model, 'year' => $year, 'brand' => $brand,
                        'condominium' => $this->condominium, 'colour' => $colour, 'condition_car' => $condition_car);
                    $result = $this->vehicles_model->addNew($info);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'vehiculo creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del vehiculo');
                    }
                } else {
                    $this->session->set_flashdata('error', 'La placa del vehiculo ya existe, por favor compruebe e intente de nuevo');
                }
                redirect('agregarvehiculos');
            }
        }
    }

    /**
     * This function is used load vehicles edit information
     * @param number $vehiclesId : Optional : This is vehicles id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('vehicles', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('vehiculos');
            }
            $data['permissions'] = $this->getPermissions()->{'vehicles'};
            $data['vehiclesInfo'] = $this->vehicles_model->getInfo($id, $this->condominium);
            if ($data['vehiclesInfo'] == null) {
                redirect('vehiculos');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar vehiculo';

            $this->loadViews("vehicles/editOld", $this->globalApp, $data, null);
        }
    }
    /**
     * This function is used load vehicles edit information
     * @param number $vehiclesId : Optional : This is vehicles id
     */
    public function getInformation($id = null)
    {
        if (checkForPermissions('vehicles', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('etiquetas');
            }
            $data['permissions'] = $this->getPermissions()->{'vehicles'};
            $data['vehiclesInfo'] = $this->vehicles_model->getInfo($id, $this->condominium);
            if ($data['vehiclesInfo'] == null) {
                redirect('etiquetas');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : informacion del vehiculo';

            $this->loadViews("vehicles/view_info", $this->globalApp, $data, null);
        }
    }
    /**
     * This function is used to edit the vehicles information
     */
    public function edit()
    {
        if (checkForPermissions('vehicles', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $id = $this->input->post('id');
            $this->form_validation->set_rules('licence_plate', 'Docuento de identidad', 'trim|required|max_length[20]');
            $this->form_validation->set_rules('model', 'Apartamento donde trabajara', 'trim|required|max_length[20]');
            $this->form_validation->set_rules('year', 'Nombre completo', 'trim|required|max_length[4]');
            $this->form_validation->set_rules('brand', 'Telefono Movil', 'trim|required|max_length[20]');
            $this->form_validation->set_rules('colour', 'Inicio a trabajar', 'trim|required|max_length[20]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $tenant = (strtoupper($this->security->xss_clean($this->input->post('tenant'))));
                $licence_plate = strtoupper($this->security->xss_clean($this->input->post('licence_plate')));
                $model = (strtoupper($this->security->xss_clean($this->input->post('model'))));
                $year = strtoupper($this->security->xss_clean($this->input->post('year')));
                $brand = (strtoupper($this->security->xss_clean($this->input->post('brand'))));
                $colour = (strtoupper($this->security->xss_clean($this->input->post('colour'))));
                $condition_car = (strtoupper($this->security->xss_clean($this->input->post('condition_car'))));
                $check = $this->vehicles_model->checkExists($licence_plate, $id, $this->condominium);

                if (empty($check)) {
                    $info = array('licence_plate' => $licence_plate, 'tenant' => $tenant, 'model' => $model, 'year' => $year, 'brand' => $brand,
                        'colour' => $colour, 'condition_car' => $condition_car);
                    $result = $this->vehicles_model->edit($info, $id, $this->condominium);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'vehiculo actualizado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la actualizacion del vehiculo');
                    }
                } else {
                    $this->session->set_flashdata('error', 'La placa del vehiculo ya existe, por favor compruebe e intente de nuevo');
                }
                redirect("editarvehiculos/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the vehicles using vehiclesId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('vehicles', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL cliente TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->vehicles_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('vehicles', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $vehiclesId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $vehiclesInfo = array('status' => $isactive);
            $result = $this->vehicles_model->edit($vehiclesInfo, $vehiclesId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

}
