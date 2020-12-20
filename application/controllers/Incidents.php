<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : incidents (incidentsController)
 * incidents Class to control all incidents related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Incidents extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('incidents_model');
        $this->isLoggedIn();
        $this->getDataCondominium();
    }

    public function getDataCondominium()
    {
        $this->load->model('condominium_model');
        $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing();
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

    public function getUserData($role, $id)
    {
        if ($role < ROLE_OWNER) {
            $this->load->model('user_model');
            return $this->user_model->getInfo($id, $this->condominium);
        } else {
            $this->load->model('user_model');
            return $this->tenant_model->getInfo($id, $this->condominium);
        }
    }

    public function getApartmentsAssignation($role, $id)
    {
        if ($role > ROLE_VIGILANT) {
            $this->load->model('tenant_model');
            return $this->tenant_model->getAssignment($id, $this->condominium);
        } else {
            return null;
        }
    }

    /**
     * This function used to load the first screen of the incidents
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    public function listing()
    {
        if (checkForPermissions('incidents', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['incidentsRecords'] = $this->incidents_model->Listing($this->id, $this->condominium);
            $data['permissions'] = $this->getPermissions()->{'incidents'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Lista de reservaciones';
            $this->loadViews("incidents/incidents", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('incidents', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nueva incidencia';
            $data['user_info'] = $this->getUserData($this->rol, $this->id);
            $data['apt_assignation'] = $this->getApartmentsAssignation($this->rol, $this->id);
            $this->loadViews("incidents/addNew", $this->globalApp, $data, null);
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
            $result = $this->incidents_model->checkExists($code, 0, $this->condominium);
        } else {
            $result = $this->incidents_model->checkExists($code, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new incidents to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('incidents', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->ConfigurationLibrary();

            if ($this->rol > ROLE_VIGILANT) {$this->form_validation->set_rules('apartment', 'Apartamento', 'trim|required');}

            $this->form_validation->set_rules('description', 'Descripcion', 'trim|required|max_length[600]');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $description = (($this->security->xss_clean($this->input->post('description'))));
                $apartment = (($this->security->xss_clean($this->input->post('apartment'))));
                $photo_1 = $photo_2 = PHOTO_DEFAULT;

                if ($this->upload->do_upload('file1')) {
                    $data = $this->upload->data();
                    $this->redimencion($data);
                    $photo_1 = $data["file_name"];
                }

                if ($this->upload->do_upload('file2')) {
                    $data = $this->upload->data();
                    $this->redimencion($data);
                    $photo_2 = $data["file_name"];
                }

                $info = array('apartment' => $apartment, 'description' => $description, 'photo_1' => $photo_1, 'photo_2' => $photo_2, 'user' => $this->id, 'user_type' => $this->rol, 'condominium' => $this->condominium);

                $result = $this->incidents_model->addNew($info);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'Insidencia enviada exitosamente.');
                } else {
                    $this->session->set_flashdata('error', 'Por alguna razon ha fallado en reportar la insidencia.');
                }
                redirect('agregarinsidencias');
            }
        }

    }

    /**
     * This function is used load incidents edit information
     * @param number $incidentsId : Optional : This is incidents id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('incidents', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('insidencias');
            }
            $data['user_info'] = $this->getUserData($this->rol, $this->id);
            $data['apt_assignation'] = $this->getApartmentsAssignation($this->rol, $this->id);
            $data['incidentsInfo'] = $this->incidents_model->getInfo($id, $this->condominium);

            if ($data['incidentsInfo'] == null) {
                redirect('insidencias');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar insidencia';

            $this->loadViews("incidents/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the incidents information
     */
    public function edit()
    {
        if (checkForPermissions('incidents', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->ConfigurationLibrary();
            $id = $this->input->post('id');
            $photo_1 = $this->input->post('photo_1');
            $photo_2 = $this->input->post('photo_2');

            if ($this->rol > ROLE_VIGILANT) {$this->form_validation->set_rules('apartment', 'Apartamento', 'trim|required');}
            $this->form_validation->set_rules('description', 'Descripcion', 'trim|required|max_length[600]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $description = (($this->security->xss_clean($this->input->post('description'))));
                $apartment = (($this->security->xss_clean($this->input->post('apartment'))));

                if ($this->upload->do_upload('file1')) {
                    $data = $this->upload->data();
                    $this->redimencion($data);
                    $photo_1 = $data["file_name"];
                    unlink(PHOTO_PATH . $photo_1);
                }

                if ($this->upload->do_upload('file2')) {
                    $data = $this->upload->data();
                    $this->redimencion($data);
                    $photo_2 = $data["file_name"];
                    unlink(PHOTO_PATH . $photo_2);
                }

                $info = array('apartment' => $apartment, 'description' => $description, 'photo_1' => $photo_1, 'photo_2' => $photo_2, 'user' => $this->id, 'user_type' => $this->rol, 'condominium' => $this->condominium);
                $result = $this->incidents_model->edit($info, $id, $this->condominium);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'Insidencia actualizada');
                } else {
                    $this->session->set_flashdata('error', 'Fallo actualización');
                }
                redirect("editarinsidencias/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the incidents using incidentsId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('incidents', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL reservacion TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->incidents_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function viewInformation($id = null)
    {
        if (checkForPermissions('incidents', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('insidencias');
            }

            $data['apt_assignation'] = $this->getApartmentsAssignation($this->rol, $this->id);
            $data['incidentsInfo'] = $this->incidents_model->getInfoFull($id, $this->condominium);

            if ($data['incidentsInfo'] == null) {
                redirect('insidencias');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar insidencia';

            $this->loadViews("incidents/changeStatus", $this->globalApp, $data, null);
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('incidents', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('observations', 'Observacion', 'trim|required');
            $id = $this->input->post('id');

            if ($this->form_validation->run() == false) {
                $this->viewInformation($id);
            } else {
                $observations = (($this->security->xss_clean($this->input->post('observations'))));

           
                $status = $this->input->post('status'); 

                $observation = "Fecha de reporte: " . date('Y-m-d H:i:s') ." - ".$observations;
                $incidents = $this->incidents_model->getInfo($id, $this->condominium);
                $observation = (empty($incidents->observations) ? "" : $incidents->observations . " <br> ") . $observation;

                $incidentsInfo = array('status' => $isactive, 'observations' => $observation);
                $result = $this->incidents_model->edit($incidentsInfo, $id, $this->condominium);

                if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}

                redirect("ver_informacion_insidencias/" . $id);
            }
        }
    }

    public function redimencion($data)
    {
        $this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = $data['full_path'];
        $urlImag = PHOTO_PATH;
        $config['new_image'] = $urlImag;
        $config['maintain_ratio'] = true;
        $config['create_thumb'] = false;
        $config['width'] = PHOTO_W;
        $config['height'] = PHOTO_H;
        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize()) {

        }
    }

    public function UUIDv4()
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
        $uuid = vsprintf('%s%s_%s_%s_%s_%s%s%s', str_split(bin2hex($bytes), 4));
        return $uuid;
    }

    public function ConfigurationLibrary()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->helper('file');
        $config['upload_path'] = PHOTO_PATH;
        $config['allowed_types'] = 'png|jpg|jpeg';
        $config['file_name'] = $this->UUIDv4() . '_' . $this->id . '_' . $this->rol . "_incidents";
        $this->load->library('upload', $config);

    }

}
