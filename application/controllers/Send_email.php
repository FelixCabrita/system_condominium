<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';
header('Access-Control-Allow-Origin: *');
/**
 * Class : Send_email (accountcontrolController)
 * Send_email Class to control all Send_email related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Send_email extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('generate_pdf_model');
    }

    public function send_bill()
    {

        $condominium_name = $this->security->xss_clean($this->input->get('condominium_name'));
        $tenant_name = $this->security->xss_clean($this->input->get('tenant_name'));
        $tenant_document = $this->security->xss_clean($this->input->get('tenant_document'));
        $amount = $this->security->xss_clean($this->input->get('amount'));        
        $tenant_email = $this->security->xss_clean($this->input->get('tenant_email'));
        $mensaje = "Buen dia (" . $tenant_document . ") -" . $tenant_name . " en el condominio " . $condominium_name . "  has acumulado hasta la fecha una deuda de <b>" . number_format($amount, 2, ',', '') . "</b>.";
        $sendStatus = $this->enviar($tenant_email, $tenant_name, "DEUDA ACUMULADA HASTA " . date("Y-m-d"), $mensaje);
        echo $sendStatus;
    }

    public function enviar($correo, $nombre, $asunto, $mensaje)
    {
        $this->load->library('email');
        $config['protocol'] = PROTOCOL;
        $config["smtp_host"] = SMTP_HOST;
        $config["smtp_user"] = SMTP_USER;
        $config["smtp_pass"] = SMTP_PASS;
        $config["smtp_port"] = SMTP_PORT;
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = true;
        $config['validate'] = true;
        $this->email->initialize($config);
        $this->email->from(EMAIL_FROM, FROM_NAME);
        $this->email->to($correo, $nombre);
        $this->email->subject($asunto);
        $this->email->set_mailtype("html");
        $this->email->message($mensaje);
        return $resutl = $this->email->send();
    }
}
