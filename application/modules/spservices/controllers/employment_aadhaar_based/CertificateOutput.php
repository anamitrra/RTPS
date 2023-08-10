<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class CertificateOutput extends Rtps
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('employment_aadhaar_based/employment_model');
        $this->load->model('employment_aadhaar_based/district_model');
        $this->load->model('employment_aadhaar_based/sub_division_model');
        $this->load->model('employment_aadhaar_based/revenue_circle_model');
        $this->load->model('employment_aadhaar_based/functional_roles_model');
        $this->load->model('employment_aadhaar_based/functional_area_model');
        $this->load->model('employment_aadhaar_based/industry_sector_model');
        $this->load->model('employment_aadhaar_based/employment_office_model');
        $this->load->model('employment_aadhaar_based/highest_examination_passed_model');
        $this->load->model('employment_aadhaar_based/examination_passed_model');

        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper('smsprovider');
        $this->load->helper("employmentcertificate");
        // $this->config->load('spservices/spconfig');
        $this->aadhaarApi = $this->config->item('aadhaar_authentication_api');
        $this->load->model('employment_aadhaar_based/logreports_model');
        $this->load->helper("role");
        $this->load->library('user_agent');
        $this->load->library('Digilocker_API');

        if ($this->session->role) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else

    } //End of __construct()

    public function output($objId = null)
    {

        $filePath = $this->save_certificate($objId);

        $filepath_to_update = array(
            'form_data.output_certificate' => $filePath,
            'form_data.certificate' => $filePath,
        );
        $this->employment_model->update($objId, $filepath_to_update);
        redirect('spservices/employment-re-registration/generate_certificate/' . $objId);
    } //End of index()


    public function save_certificate($objId = null)
    {
        $this->load->library("ciqrcode");
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["response"] = $this->employment_model->get_by_doc_id($objId);
            $html = $this->load->view('employment_aadhaar_based/output_certificate', $data, true);
            $this->load->library('pdf');
            $fullPath = $this->pdf->get_pdf($html, 'EMP', str_replace('/', '-', $dbRow->form_data->rtps_trans_id));
            $fileName = str_replace('/', '-', $dbRow->form_data->rtps_trans_id);
            $filePath = 'storage/docs/EMP';
            return $filePath . '/' . $fileName . '.pdf';
        } else {
            return null;
        }
    }

}
?>