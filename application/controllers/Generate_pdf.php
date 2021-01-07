<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Generate_pdf (accountcontrolController)
 * Generate_pdf Class to control all Generate_pdf related operations.
 * @author : Felix Cabrita
 * @version : 1.1
 * @since : 01 Octubre 2020
 */
class Generate_pdf extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('generate_pdf_model'); 
        $this->isLoggedIn(); 
    }
  
    /**
     * This function used to load the first screen of the Generate_pdf
     */
    public function index()
    {
        $this->globalApp['pageTitle'] = NAME_SYSTEM . ' Escritorio';
        $this->loadViews("dashboard", $this->globalApp, null, null);
    }

    function cataloge_account()
    {  
        $this->load->library('pdf'); 

        $data["condominium"] =$this->generate_pdf_model->getCondominium($this->condominium);
        $heading = $this->generate_pdf_model->getHeading($this->condominium);
      
        $data["data"] = "";
        foreach( $heading as $record){
            $data["data"] = $data["data"]." <tr ><td class='account'><b>$record->code</b></td> <td><b>$record->name</b></td>  </tr>";
            $Accountcontrol = $this->generate_pdf_model->getAccountcontrol($record->id,$this->condominium);  
            foreach( $Accountcontrol as $record){ 
                $data["data"] = $data["data"]." <tr><td class='account'>$record->code</td>  <td>$record->name</td></tr>";

                $accounthigher = $this->generate_pdf_model->getAccountcontrol($record->id,$this->condominium);  
                foreach( $Accountcontrol as $record){ 
                    $data["data"] = $data["data"]."<tr><td class='account'>$record->code</td>  <td>$record->name</td></tr>";

                    $accounthigher = $this->generate_pdf_model->getHigher($record->id,$this->condominium);  
                    foreach( $accounthigher as $record){ 
                        $data["data"] = $data["data"]." <tr><td class='account'>$record->code</td>  <td>$record->name</td></tr>";

                        $Accountdetail = $this->generate_pdf_model->getAccountdetail($record->id,$this->condominium);  
                        foreach( $Accountdetail as $record){ 
                            $data["data"] = $data["data"]." <tr><td class='account'>$record->code</td>  <td>$record->name</td></tr>";
                        }
                    }
                }
            }
        }

        $html = $this->load->view('generate_pdf/cataloge_account', $data, true);
        $html = trim(preg_replace( '/>\s+</', '><', $html ));
        $this->pdf->createPDF($html, 'catalogo_cuentas', false); 
    }


    function daily($id=null){

        $this->load->library('pdf'); 

        if ($id == null) {
            redirect('');
        }

        $data["condominium"] =$this->generate_pdf_model->getCondominium($this->condominium);
        
        $header = $this->generate_pdf_model->getdaily($this->condominium, $id);
        $detail = $this->generate_pdf_model->getdailyDetail($this->condominium, $id);

        $data['header'] = $header;
        $data['detail'] = $detail;
        if ($data['header'] == null) {
            redirect('');
        }


        $html = $this->load->view('generate_pdf/daily', $data, true);
        $html = trim(preg_replace( '/>\s+</', '><', $html ));
        $this->pdf->createPDF($html, 'entrada_de_diario_asiento_'.$id, false); 
    }
}
