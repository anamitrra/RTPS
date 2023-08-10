<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

class Dscsign extends Upms
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('../modules/spservices/controllers/office/sms_scheduler');
        $this->isloggedin();
        $this->load->model('upms/roles_model');
        $this->load->model('upms/levels_model');
        $this->load->model('upms/users_model');
        $this->load->model('upms/applications_model');
    } //End of __construct()

    //Load view (DSC signe required)
    public function index($rtps_trans_id)
    {
        $filter = array("service_data.appl_ref_no" => base64_decode($rtps_trans_id));
        $data = (array)$this->mongo_db->where($filter)->get('sp_applications');
        $reg_con_arr = array("CON_REG_PWDB_1", "CON_REG_PWDB_2", "CON_REG_PHED_1", "CON_REG_PHED_2", "CON_REG_WRD_1", "CON_REG_WRD_2",
         "CON_REN_PWDB_1", "CON_REN_PWDB_2", "CON_REN_PHED_1", "CON_REN_PHED_2", "CON_REN_WRD_1", "CON_REN_WRD_2",
        "CON_UPGR_PWDB_1", "CON_UPGR_PWDB_2", "CON_UPGR_PHED_1", "CON_UPGR_PHED_2", "CON_UPGR_WRD_1", "CON_UPGR_WRD_2","CON_REG_GMC_2","CON_REG_GMC_1");

        if (!empty($data) && $data[0]->service_data->appl_status != "D") {
            //$temp_pdffile_path = strlen($data[0]->form_data->temp_certificate_path) ? base64_encode($data[0]->form_data->temp_certificate_path) : null;
            if ($data[0]->service_data->service_id == "ACMRNOC") {
                $ref = modules::load('spservices/acmrnoc/actions');
                $temp_pdffile_path = base64_encode($ref->generate_certificate($data[0]->service_data->appl_ref_no));
                $dscPosition = $ref->get_dscPosition();
            } else if ($data[0]->service_data->service_id == "ACMRREGAD") {
                $ref = modules::load('spservices/acmr_reg_of_addl_degrees/actions');
                $temp_pdffile_path = base64_encode($ref->generate_certificate($data[0]->service_data->appl_ref_no));
                $dscPosition = $ref->get_dscPosition();
            } else if ($data[0]->service_data->service_id == "CPCME") {
                $ref = modules::load('spservices/acmr_cp_cme/actions');
                $temp_pdffile_path = base64_encode($ref->generate_certificate($data[0]->service_data->appl_ref_no));
                $dscPosition = $ref->get_dscPosition();
            } else if ($data[0]->service_data->service_id == "PROMD") {
                $ref = modules::load('spservices/permanent_registration_mbbs/actions');
                $temp_pdffile_path = base64_encode($ref->generate_certificate($data[0]->service_data->appl_ref_no));
                $dscPosition = $ref->get_dscPosition();
            } else if ($data[0]->service_data->service_id == "ACMRPRCMD") {
                $ref = modules::load('spservices/acmr_provisional_certificate/actions');
                $temp_pdffile_path = base64_encode($ref->generate_certificate($data[0]->service_data->appl_ref_no));
                $dscPosition = $ref->get_dscPosition();
            } else if ($data[0]->form_data->service_id == "EMP_REG_NA") {
                $ref = modules::load('spservices/employmentnonaadhaar/actions');
                $temp_pdffile_path = base64_encode($ref->generate_certificate($data[0]->service_data->appl_ref_no));
                $dscPosition = $ref->get_dscPosition();
            } else if ($data[0]->service_data->service_id == "EMP_REREG_NA") {
                $ref = modules::load('spservices/employmentnonaadhaar/actions');
                $temp_pdffile_path = base64_encode($ref->generate_certificate($data[0]->service_data->appl_ref_no));
                $dscPosition = $ref->get_dscPosition();
            } else if (in_array($data[0]->service_data->service_id, $reg_con_arr)) {
                $ref = modules::load('spservices/registration_of_contractors/actions');
                $temp_pdffile_path = base64_encode($ref->generate_certificate($data[0]->service_data->appl_ref_no));
                if($data[0]->form_data->deptt_name == 'WRD') {
                    $dscPosition = $ref->get_dscPosition_1();
                } else {
                    $dscPosition = $ref->get_dscPosition();
                }
            }

            $user_data = array(
                "objId" => $data[0]->{'_id'}->{'$id'},
                "stampingX" => $dscPosition['stampingX'],
                "stampingY" => $dscPosition['stampingY'],
                "rtps_trans_id" => base64_decode($rtps_trans_id),
                "pdfFile" => base64_decode($temp_pdffile_path)
            );
            $this->load->view('upms/dsign/dscsign_view', $user_data);
        } else {
            $this->session->set_flashdata('success', 'Data has been successfully updated');
            redirect('spservices/upms/myapplications/index/' . $this->session->loggedin_login_username);
        } //End of if else
    } //End of index()

    //Load view (DSC signe not required)
    public function withoutDsign($rtps_trans_id)
    {

        $filter = array("service_data.appl_ref_no" => base64_decode($rtps_trans_id));
        $data = (array)$this->mongo_db->where($filter)->get('sp_applications');
        if (!empty($data) && $data[0]->service_data->appl_status != "D") {

            if ($data[0]->service_data->service_id == "AHSECCRC") {
                $ref = modules::load('spservices/ahsec_correction/actions');
                $ref->preview_certificate($data[0]->service_data->appl_ref_no);
                // pre($preview_certificate);
            } elseif ($data[0]->service_data->service_id == "AHSECCADM") {
                $ref = modules::load('spservices/ahsec_correction/actions');
                $ref->preview_certificate($data[0]->service_data->appl_ref_no);
                // pre($preview_certificate);
            }
            if ($data[0]->service_data->service_id == "AHSECCMRK") {
                $ref = modules::load('spservices/ahsec_correction/actions');
                $ref->preview_certificate($data[0]->service_data->appl_ref_no);
                // pre($preview_certificate);
            }
            if ($data[0]->service_data->service_id == "AHSECCPC") {
                $ref = modules::load('spservices/ahsec_correction/actions');
                $ref->preview_certificate($data[0]->service_data->appl_ref_no);
                // pre($preview_certificate);
            } else if ($data[0]->service_data->service_id == "AHSECDRC") {
                $ref = modules::load('spservices/duplicatecertificateahsec/actions');
                $ref->preview_certificate($data[0]->service_data->appl_ref_no);
                // pre($preview_certificate);
            } else if ($data[0]->service_data->service_id == "AHSECDMRK") {
                $ref = modules::load('spservices/duplicatecertificateahsec/actions');
                $ref->preview_certificate($data[0]->service_data->appl_ref_no);
                // pre($preview_certificate);
            } else if ($data[0]->service_data->service_id == "AHSECDADM") {
                $ref = modules::load('spservices/duplicatecertificateahsec/actions');
                $ref->preview_certificate($data[0]->service_data->appl_ref_no);
                // pre($preview_certificate);
            } else if ($data[0]->service_data->service_id == "AHSECDPC") {
                $ref = modules::load('spservices/duplicatecertificateahsec/actions');
                $ref->preview_certificate($data[0]->service_data->appl_ref_no);
                // pre($preview_certificate);
            }else if ($data[0]->service_data->service_id == "AHSECMIGR") {
                
                $ref = modules::load('spservices/migrationcertificateahsec/actions');
                $ref->preview_certificate($data[0]->service_data->appl_ref_no);
                // pre($preview_certificate);
            }
            else if ($data[0]->service_data->service_id == "AHSECCHINS") {
                $ref = modules::load('spservices/change_institute_ahsec/actions');
                $ref->preview_certificate($data[0]->service_data->appl_ref_no);
                // pre($preview_certificate);
            }
        } else {
            $this->session->set_flashdata('success', 'Data has been successfully updated.!');
            redirect('spservices/upms/myapplications/index/' . $this->session->loggedin_login_username);
        } //End of if else

    }

    //Update DSC signed document details into DB
    public function pdfsigned()
    {
        $rtps_trans_id = $this->input->post("rtps_trans_id");
        $pdf_file = $this->input->post("pdf_file");
        $objId = $this->input->post("token");
        $registration_no = $this->input->post("registration_no");
        $deliver_dt = $this->input->post("deliver_dt");
        $renew_dt = $this->input->post("renew_dt");

        $dbRow = $this->applications_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $b64_signed_pdf = $this->input->post("signed_pdf");
            $pdf_decoded = base64_decode($b64_signed_pdf);
            $pdf = fopen($pdf_file, 'w');
            fwrite($pdf, $pdf_decoded);
            fclose($pdf);
            $loggedInUserData = array(
                "user_fullname" => $this->session->loggedin_user_fullname
            );
            $processing_history = $dbRow->processing_history ?? array();
            $processing_history[] = array(
                "processed_by" => $loggedInUserData["user_fullname"],
                "action_taken" => "Certificate Generated Successfully!",
                "remarks" => "Certificate Delivered",
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            );

            if ($dbRow->form_data->service_id == 'EMP_REG_NA') {
                $dataToupdate = [
                    'form_data.registration_no' => $registration_no,
                    'form_data.date_of_reg' => $deliver_dt,
                    'form_data.renewal_date' => $renew_dt,
                    'service_data.appl_status' => 'D',
                    'form_data.certificate' => $pdf_file,
                    'status' => 'DELIVERED',
                    'processing_history' => $processing_history
                ];
            } else {
                $dataToupdate = [
                    'service_data.appl_status' => 'D',
                    'form_data.certificate' => $pdf_file,
                    'status' => 'DELIVERED',
                    'processing_history' => $processing_history
                ];
            }
            $this->applications_model->update_where(['_id' => new ObjectId($objId)], $dataToupdate);
            $this->sms_scheduler->send_delivery_sms($rtps_trans_id);
            redirect('spservices/upms/myapplications/');
        }
    } //End of pdfsigned()

    //Update Without DSC signed document details into DB
    public function withoutpdfsigned($objId = null)
    {
        $objId = $this->input->post("obj_id");
        $certificate_no = $this->input->post("certificate_no");
        $certificate_path = base64_decode($this->input->post("certificate_path"));
        $dbRow = $this->applications_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            if ($dbRow->service_data->appl_status == "D") {
                $this->session->set_flashdata('success', 'Data has been successfully updated.');
                redirect('spservices/upms/myapplications/index/' . $this->session->loggedin_login_username);
            } else {
                $loggedInUserData = array(
                    "user_fullname" => $this->session->loggedin_user_fullname
                );
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => $loggedInUserData["user_fullname"],
                    "action_taken" => "Certificate Generated Successfully!",
                    "remarks" => "Certificate Delivered",
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $dataToupdate = [
                    'service_data.appl_status' => 'D',
                    'form_data.certificate_no' => $certificate_no,
                    'form_data.certificate' => $certificate_path,
                    'status' => 'DELIVERED',
                    'processing_history' => $processing_history
                ];
                // pre($dataToupdate);
                $this->applications_model->update_where(['_id' => new ObjectId($objId)], $dataToupdate);
                redirect('spservices/upms/myapplications/');
                //$this->sms_scheduler->send_delivery_sms($dbRow->service_data->appl_ref_no);
            }
        }
    } //End of pdfsigned()
}
