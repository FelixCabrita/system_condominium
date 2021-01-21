<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Dashboard extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->isLoggedIn();
        $this->getDataCondominium();
    }

    public function getDataCondominium()
    {
        if ($this->rol == ROLE_ADMIN) {
            $this->load->model('condominium_model');
            $this->globalApp['condominiumRecords'] = $this->condominium_model->getListing($this->id);
        }
    }

    public function getReservationListing()
    {
        if (checkForPermissions('reservations', PERMISSION_READ) != true) {
            return "";
        } else {
            $this->load->model('reservation_model');
            return $this->reservation_model->ListingDashboard($this->rol < ROLE_OWNER ? "" : $this->id, $this->condominium);
        }
    }

    public function getIncidentsListing()
    {
        if (checkForPermissions('incidents', PERMISSION_READ) != true) {
            return "";
        } else {
            $this->load->model('incidents_model');
            return $this->incidents_model->ListingDashboard($this->rol < ROLE_OWNER ? "" : $this->id, $this->condominium);
        }
    }

    public function getBill($status)
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            return "";
        } else {
            $this->load->model('bills_model');
            return $this->bills_model->bill_month(($this->rol < ROLE_OWNER ? "" : $this->id),$status, $this->condominium);
        }
    }

    public function getBillCashierType($type)
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            return "";
        } else {
            $this->load->model('bills_model');
            return $this->bills_model->bill_month_cashier_type($type, $this->condominium);
        }
    }

    public function getBillCashier($status)
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            return "";
        } else {
            $this->load->model('bills_model');
            return $this->bills_model->bill_month_cashier($status, $this->condominium);
        }
    }
public function getBillCashieraaa($status)
    {
        if (checkForPermissions('bills', PERMISSION_READ) != true) {
            return "";
        } else {
            $this->load->model('bills_model');
            return $this->bills_model->bill_month_cashieraaa($this->condominium);
        }
    }
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';  
        
        $data["reservation"] = $this->getReservationListing();
        $data["incidents"] = $this->getIncidentsListing();

        if ($this->rol==ROLE_ACCOUNTING || $this->rol ==ROLE_ADMIN || $this->rol ==ROLE_MANAGER ) {
            $data["total_bill"] = $this->getBill(-1);
            $data["pay"] = $this->getBill(1);
            $data["pending"] = $this->getBill(0); 
        }
        if ($this->rol==ROLE_VENDOR) {
            $data["payment_date"] = $this->getBillCashier(-1);
            $data["payment"] = $this->getBillCashier(1);      
             $data["paymentaa"] = $this->getBillCashieraaa(1);
            $data["cash"] = $this->getBillCashierType(0);
            $data["transfer"]= $this->getBillCashierType(1);
            $data["check"]= $this->getBillCashierType(2);
            $data["card"]= $this->getBillCashierType(3);
        }

        if ($this->rol>=ROLE_OWNER) {
            $data["total_bill"] = $this->getBill(-1);
            $data["pay"] = $this->getBill(1);
          
        }
        $this->loadViews("tablero", $this->globalApp, $data, null); 

    }

}
