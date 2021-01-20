<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Directive (DirectiveController)
 * Directive Class to control all Directive related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Directive extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('directive_model');
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
        if (empty($code)) {
            return $this->edifice_model->getListing($this->condominium);
        } else {
            return $this->edifice_model->getInfoCode($code, $this->condominium);
        }
    }

    public function getDataPeriod()
    {
        $this->load->model('period_model');
        return $this->period_model->getListing($this->condominium);

    }

    /**
     * This function used to load the first screen of the Directive
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("tablero", $this->globalApp, null, null);
    }

    /**
     * This function is used to load the Directive list
     */
    public function listing()
    {
        if (checkForPermissions('directive', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            $data['DirectiveRecords'] = $this->directive_model->Listing($this->id, $this->condominium,0);
            $data['DirectiveRecordsActive'] = $this->directive_model->Listing($this->id, $this->condominium,1);
            $data['periodRecords'] = $this->getDataPeriod();
            $data['permissions'] = $this->getPermissions()->{'directive'};
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Junta directiva';
            $this->loadViews("directive/directive", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to load the add new form
     */
    public function addNew()
    {
        if (checkForPermissions('directive', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Nuevo miembro';
            $data['edificeRecords'] = $this->getDataEdifices();
            $data['periodRecords'] = $this->getDataPeriod();
            $this->loadViews("directive/addNew", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    public function checkExists()
    {
        $id = $this->input->post("id");
        $document = $this->input->post("document");

        if (empty($id)) {
            $result = $this->directive_model->checkExists($document, 0, $this->condominium);
        } else {
            $result = $this->directive_model->checkExists($document, $id, $this->condominium);
        }

        if (empty($result)) {echo ("true");} else {echo ("false");}
    }

    /**
     * This function is used to add new Directive to the system
     */
    public function addNewData()
    {
        if (checkForPermissions('directive', PERMISSION_CREATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $this->ConfigurationLibrary();

            $this->form_validation->set_rules('document', 'Documento', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('position', 'Puesto', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('chargue', 'Cargo', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('period', 'Periodo', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('edifice', 'Edificio', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('apartment', 'Apartamento', 'trim|required|max_length[11]');
            if ($this->form_validation->run() == false) {
                $this->addNew();
            } else {
                $document = (($this->security->xss_clean($this->input->post('document'))));
                $name = ($this->security->xss_clean($this->input->post('name')));
                $position = ($this->security->xss_clean($this->input->post('position')));  
                $chargue = ($this->security->xss_clean($this->input->post('chargue')));
                $period = strtoupper($this->security->xss_clean($this->input->post('period')));
                $edifice = strtoupper($this->security->xss_clean($this->input->post('edifice')));
                $apartment = strtoupper($this->security->xss_clean($this->input->post('apartment')));

                $check = $this->directive_model->checkExists($document, 0, $this->condominium);

                if (empty($check)) {

                    $photo_1 = PHOTO_DEFAULT;

                    if ($this->upload->do_upload('file1')) {
                        $data = $this->upload->data();
                        $this->redimencion($data);
                        $photo_1 = $data["file_name"];
                    }

                    $info = array('document' => $document, 'name' => $name, 'chargue' => $chargue, 'position' => $position,'condominium' => $this->condominium,
                        'period' => $period, 'edifice' => $edifice, 'photo_1' => $photo_1, 'apartment' => $apartment);
                    $result = $this->directive_model->addNew($info);

                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'miembro creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del miembro');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El codigo de miembro ya existe, por favor compruebe e intente de nuevo');
                }
                redirect('agregarmiembro');
            }
        }
    }

    /**
     * This function is used load Directive edit information
     * @param number $DirectiveId : Optional : This is Directive id
     */
    public function editOld($id = null)
    {
        if (checkForPermissions('directive', PERMISSION_READ) != true) {
            $this->loadThis();
        } else {
            if ($id == null) {
                redirect('juntadirectiva');
            }
            $data['permissions'] = $this->getPermissions()->{'directive'};
            $data['DirectiveInfo'] = $this->directive_model->getInfo($id, $this->condominium);
            $data['periodRecords'] = $this->getDataPeriod();
            $data['edificeRecords'] = $this->getDataEdifices();
            if ($data['DirectiveInfo'] == null) {
                redirect('juntadirectiva');
            }
            $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Editar miembros';

            $this->loadViews("directive/editOld", $this->globalApp, $data, null);
        }
    }

    /**
     * This function is used to edit the Directive information
     */
    public function edit()
    {
        if (checkForPermissions('directive', PERMISSION_UPDATE) != true) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');
            $this->ConfigurationLibrary();

            $id = $this->input->post('id');
            $photo_1 = $this->input->post('photo_1');

          

            $this->form_validation->set_rules('document', 'Documento', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('name', 'Nombre', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('position', 'Posicion', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('chargue', 'Cargo', 'trim|required|max_length[250]');
            $this->form_validation->set_rules('period', 'Periodo', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('edifice', 'Edificio', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('apartment', 'Apartamento', 'trim|required|max_length[11]');
            if ($this->form_validation->run() == false) {
                $this->editOld($id);
            } else {
                $document = (($this->security->xss_clean($this->input->post('document'))));
                $name = ($this->security->xss_clean($this->input->post('name')));
                $position = ($this->security->xss_clean($this->input->post('position'))); 
                $chargue = ($this->security->xss_clean($this->input->post('chargue')));
                $period = strtoupper($this->security->xss_clean($this->input->post('period')));
                $edifice = strtoupper($this->security->xss_clean($this->input->post('edifice')));
                $apartment = strtoupper($this->security->xss_clean($this->input->post('apartment')));

                $check = $this->directive_model->checkExists($document, $id, $this->condominium);

                if (empty($result)) { 

                    if ($this->upload->do_upload('file1')) {
                        $data = $this->upload->data();
                        $this->redimencion($data);
                        $photo_1 = $data["file_name"];
                        unlink(PHOTO_PATH_D . $photo_1);
                    } 

                    $info = array('document' => $document, 'name' => $name,'position' => $position, 'chargue' => $chargue, 'condominium' => $this->condominium,
                        'period' => $period, 'edifice' => $edifice, 'photo_1' => $photo_1, 'apartment' => $apartment);
                    $result = $this->directive_model->edit($info, $id, $this->condominium);
                    if ($result > 0) {
                        $this->session->set_flashdata('success', 'miembro creado exitosamente');
                    } else {
                        $this->session->set_flashdata('error', 'Fallo la creacción del miembro');
                    }
                } else {
                    $this->session->set_flashdata('error', 'El documento del miembro ya existe, por favor compruebe e intente de nuevo');
                }
                redirect("editarmiembro/" . $id);
            }
        }
    }

    /**
     * This function is used to delete the Directive using DirectiveId
     * @return boolean $result : TRUE / FALSE
     */
    public function delete()
    {
        if (checkForPermissions('directive', PERMISSION_DELETE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $id = $this->input->post('id');

            /*
            SE DEBE COMPROBAR SI EL miembro TIENEN DATOS ENLAZADOS PARA PODER SER ELIMINADO
             */

            $result = $this->directive_model->delete($id, $this->condominium);

            if ($result) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function changeStatus()
    {
        if (checkForPermissions('directive', PERMISSION_UPDATE) != true) {
            echo (json_encode(array('status' => 'access')));
        } else {
            $DirectiveId = $this->input->post('id');
            $isactive = $this->input->post('isactive');
            $DirectiveInfo = array('status' => $isactive);

            $result = $this->directive_model->edit($DirectiveInfo, $DirectiveId, $this->condominium);

            if ($result > 0) {echo (json_encode(array('status' => true)));} else {echo (json_encode(array('status' => false)));}
        }
    }

    public function redimencion($data)
    {
        $this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = $data['full_path'];
       
        $config['new_image'] =PHOTO_PATH_D;
        $config['maintain_ratio'] = true;
        $config['create_thumb'] = false;
        $config['width'] = PHOTO_W_D;
        $config['height'] = PHOTO_H_D;
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
        $config['upload_path'] = PHOTO_PATH_D;
        $config['allowed_types'] = 'jpg|jpeg';
        $config['file_name'] = $this->UUIDv4() . "_directive";
        $this->load->library('upload', $config);

    }
}
