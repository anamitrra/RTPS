<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\UTCDateTime;

class Dscsign extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('../modules/spservices/controllers/office/sms_scheduler');
        if ($this->session->userdata()) {
            if ($this->session->userdata('isAdmin') === TRUE) {
            } else {
              $this->session->sess_destroy();
              redirect('spservices/mcc/user-login');
            }
          } else {
            redirect('spservices/mcc/user-login');
          }

    } //End of __construct()

    public function index($rtps_trans_id, $pdffile)
    {
        $filter = array("service_data.appl_ref_no" => base64_decode($rtps_trans_id));
        $data = (array)$this->mongo_db->where($filter)->get('sp_applications');
        if ($data) {
            $user_data = array(
                "rtps_trans_id" => base64_decode($rtps_trans_id),
                "pdfFile" => base64_decode($pdffile)
            );
            $this->load->view('office/certificates/dscsign_view', $user_data);
        } else {
            die('No records found against object id : ' . $rtps_trans_id);
            $this->session->set_flashdata('error', 'No records found against object id : ' . $rtps_trans_id);
        } //End of if else
    } //End of index()

    public function pdfsigned()
    {
        $rtps_trans_id = $this->input->post("rtps_trans_id");
        $pdf_file = $this->input->post("pdf_file");
        $b64_signed_pdf = $this->input->post("signed_pdf");
        $pdf_decoded = base64_decode($b64_signed_pdf);
        $pdf = fopen($pdf_file, 'w');
        fwrite($pdf, $pdf_decoded);
        fclose($pdf);

        // $application_data = (array)$this->mongo_db->where(['rtps_trans_id'=>$rtps_trans_id])->get('minoritycertificates');

        // $option = array('upsert' => true);
        // $this->mongo_db->where(array('rtps_trans_id' =>$rtps_trans_id))->set($data)->update('minoritycertificates', $option);

        // $this->update_existing($appl_no, $dataToupdate, $dataToupdate_official_form);

        // $this->castecertificates_model->update_where(['rtps_trans_id' => $rtps_trans_id], $data);
        // echo "After Signed call : ".$rtps_trans_id;

        $dataToupdate = [
            'service_data.appl_status' => 'DELIVERED',
            'form_data.certificate' => $pdf_file,
            'execution_data.0.task_details.action_taken' =>  'Y',
            'execution_data.0.task_details.executed_time' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
        ];
        $dataToupdate_official_form = [
            "execution_data.0.official_form_details" => [
                "action" => "Delivered",
                "output_certificate" => $pdf_file,
                "remarks" => $this->input->post('remarks'),
                "status" => "D"
            ]
        ];
        $option = array('upsert' => true);
        $this->mongo_db->where(array('service_data.appl_ref_no' => $rtps_trans_id))->set($dataToupdate)->update('sp_applications', $option);
        $this->mongo_db->where(array('service_data.appl_ref_no' => $rtps_trans_id))->set($dataToupdate_official_form)->update('sp_applications');
        $this->sms_scheduler->send_delivery_sms($rtps_trans_id);

        // return $status = 'Yes';
        // echo "After Signed call : ".$rtps_trans_id;


    } //End of pdfsigned()

    public function demo(){
        print("Hello");
        return;
    }

}
