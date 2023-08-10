<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dscsign extends Frontend {
    public function __construct() {
        parent::__construct();
        $this->load->model('wptbc/castecertificates_model');
    }//End of __construct()

    public function index($rtps_trans_id) {
        $filter = array("rtps_trans_id" => $rtps_trans_id);
        $dbRow = $this->castecertificates_model->get_row($filter);        //var_dump($dbRow); die;
        if($dbRow) {
            $data=array(
                "rtps_trans_id" => $rtps_trans_id,
                "pdfFile"=>$dbRow->documents
            );
            $this->load->view('wptbc/dscsign_view', $data);
        } else { die('No records found against object id : '.$rtps_trans_id);
            $this->session->set_flashdata('error','No records found against object id : '.$rtps_trans_id);
            redirect('iservices/wptbc/castecertificate/');
        }//End of if else
    }//End of index()
    
    public function pdfsigned(){
        $rtps_trans_id = $this->input->post("rtps_trans_id");     
        $pdf_file = $this->input->post("pdf_file");
        $b64_signed_pdf = $this->input->post("signed_pdf");
        $pdf_decoded = base64_decode ($b64_signed_pdf);
        $pdf = fopen ($pdf_file,'w');
        fwrite ($pdf,$pdf_decoded);
        fclose ($pdf);
        
        $data = array(
            "is_dsc_signed" => "YES"
        );
        $this->castecertificates_model->update_where(['rtps_trans_id' => $rtps_trans_id], $data);
        echo "After Signed call : ".$rtps_trans_id;
        
    }//End of pdfsigned()

}
