<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class : BaseController
 * Base Class to control over all the classes
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class BaseController extends CI_Controller
{
    //id, username, password, permissions, name, status,
    protected $id = '';
    protected $name = '';
    protected $username = '';
    protected $permissions = '';
    protected $rol = '';
    protected $globalAppApp = array();
    protected $lastLogin = '';
    protected $isLoggedIn = '';
    /**
     * Takes mixed data and optionally a status code, then creates the response
     *
     * @access public
     * @param array|NULL $data
     *            Data to output to the user
     *            running the script; otherwise, exit
     */
    public function response($data = null)
    {
        $this->output->set_status_header(200)->set_content_type('application/json', 'utf-8')->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
        exit();
    }

    /**
     * This function used to check the user is logged in or not
     */
    public function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');

        if (!isset($isLoggedIn) || $isLoggedIn != true) {
            redirect('login');
        } else {
            //id, username, password, permissions, name, status,
            $this->id = $this->session->userdata('id');
            $this->username = $this->session->userdata('username');
            $this->permissions = $this->session->userdata('permissions');
            $this->name = $this->session->userdata('name');
            $this->rol = $this->session->userdata('rol');
            $this->lastLogin = $this->session->userdata('lastLogin');
            $this->isLoggedIn = $this->session->userdata('isLoggedIn');
            $this->globalApp['id'] = $this->id;
            $this->globalApp['username'] = $this->username;
            $this->globalApp['permissions'] = $this->permissions;
            $this->globalApp['rol'] = $this->rol;
            $this->globalApp['name'] = $this->name;
            $this->globalApp['isLoggedIn'] = $this->isLoggedIn;
        }
	}
	
	public function getPermissions(){
        return json_decode($this->permissions);
    }
 

    /**
     * This function is used to check the access
     */
    public function isAdmin()
    {
        if ($this->role != ROLE_ADMIN) {
            return true;
        } else {
            return false;
        }
    }

    public function isManager()
    {
        if ($this->role != ROLE_MANAGER) {
            return true;
        } else {
            return false;
        }
    }

    public function isEmployee()
    {
        if ($this->role != ROLE_EMPLOYEE && $this->role != ROLE_ADMIN) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function is used to check the access
     */
    public function isTicketter()
    {
        if ($this->role != ROLE_ADMIN || $this->role != ROLE_MANAGER) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function is used to load the set of views
     */
    public function loadThis()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' : Access Denied';

        $this->load->view('includes/header', $this->globalApp);
        $this->load->view('access');
        $this->load->view('includes/footer');
    }

    /**
     * This function is used to logged out user from system
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
    public function logout_w()
    {
        setcookie("workstation_shmmchm", '', time() - (86400 * 15));
        $this->session->sess_destroy();
        redirect('login');
    }
    /**
     * This function used to load views
     * @param {string} $viewName : This is view name
     * @param {mixed} $headerInfo : This is array of header information
     * @param {mixed} $pageInfo : This is array of page information
     * @param {mixed} $footerInfo : This is array of footer information
     * @return {null} $result : null
     */
    public function loadViews($viewName = "", $headerInfo = null, $pageInfo = null, $footerInfo = null)
    {

        $this->load->view('includes/header', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('includes/footer', $footerInfo);
    }

    public function loadViewsOrder($viewName = "", $headerInfo = null, $pageInfo = null, $footerInfo = null)
    {

        $this->load->view('order/header', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('includes/footer', $footerInfo);
    }

    /**
     * This function used provide the pagination resources
     * @param {string} $link : This is page link
     * @param {number} $count : This is page count
     * @param {number} $perPage : This is records per page limit
     * @return {mixed} $result : This is array of records and pagination data
     */
    public function paginationCompress($link, $count, $perPage = 10, $segment = SEGMENT)
    {
        $this->load->library('pagination');

        $config['base_url'] = base_url() . $link;
        $config['total_rows'] = $count;
        $config['uri_segment'] = $segment;
        $config['per_page'] = $perPage;
        $config['num_links'] = 5;
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_tag_open'] = '<li class="arrow">';
        $config['first_link'] = 'Primero';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = 'Anterior';
        $config['prev_tag_open'] = '<li class="arrow">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Siguiente';
        $config['next_tag_open'] = '<li class="arrow">';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="arrow">';
        $config['last_link'] = 'Ultimo';
        $config['last_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $page = $config['per_page'];
        $segment = $this->uri->segment($segment);

        return array(
            "page" => $page,
            "segment" => $segment,
        );
    }
}
