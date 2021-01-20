<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . '/dompdf/dompdf_config.inc.php');

class Pdf
{
    function createPDF($html, $filename='', $download=TRUE,$time = '', $paper='A4', $orientation='portrait'){
 
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper($paper, $orientation);
        $dompdf->render();
         if($download){
            $dompdf->stream($filename.'.pdf', array('Attachment' => 1));
         }else{
            $dompdf->stream($filename.'.pdf', array('Attachment' => 0));  }
    
        if($time>=300){ file_put_contents(PDF_PATH.$filename.'.pdf', $dompdf->output());}
       
    }
    
    function createPDF_TAG($html, $filename='', $download=TRUE, $paper='A4', $orientation='portrait'){
     
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $customPaper = array(0,0,300,300);
        $dompdf->set_paper($customPaper);
        $dompdf->render();
        if($download)
            $dompdf->stream($filename.'.pdf', array('Attachment' => 1));
        else
            $dompdf->stream($filename.'.pdf', array('Attachment' => 0));

     
    }
}
?>