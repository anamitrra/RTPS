<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Noc extends Rtps
{
    private $serviceName = "Issuance of No Objection Certificate - ACMR";
    private $serviceId = "ACMRNOC"; //"ACMRNOC";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('acmrnoc/acmrnoc_model');
        //$this->load->model('necprocessing_model');
        $this->load->helper("cifileupload");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->helper('smsprovider');
        $this->load->helper("log");
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
    } //End of __construct()

    public function index($obj_id = null)
    {
        $data = array("pageTitle" => "Application Form For No Objection Certificate - ACMR");
        $filter = array("_id" => new ObjectId($obj_id), "service_data.appl_status" => "DRAFT");
        $data["dbrow"] = $this->acmrnoc_model->get_row($filter);
        $data['usser_type'] = $this->slug;

        $this->load->view('includes/frontend/header');
        $this->load->view('acmrnoc/acmr_noc', $data);
        $this->load->view('includes/frontend/footer');
    } //End of index()
    public function getlocation()
    {
        $id = $_GET['id'];
        if ($id) {
            $data = $this->acmrnoc_model->get_sro_list($id);
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(array());
            }
        }
    }
    public function submit()
    {
        $objId = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");
        $this->form_validation->set_rules('applicant_name', 'Applicant Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('father_name', 'Father name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('mobile', 'Contact number', 'trim|integer|required|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('dob', 'DOB', 'trim|required|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('email', 'Email id', 'valid_email|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('pan_no', 'PAN no', 'trim|xss_clean|strip_tags|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]');

        $this->form_validation->set_rules('permanent_address', 'Permanent Address', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('correspondence_address', 'Correspondence Address', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('permanent_reg_no', 'Permanent Reg No', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('permanent_reg_date', 'Permanent Reg Date', 'trim|required|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('additional_degree_reg_no', 'Additional Degree Reg No', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('additional_degree_reg_date', 'Additional Degree Reg Date', 'trim|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('registering_smc', 'Relocating or Duly Registering State Medical Council', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('relocating_reason', 'Relocation Reason', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('working_place_add', 'Working Place Address', 'trim|xss_clean|strip_tags');

        // $this->form_validation->set_rules('inputcaptcha', 'Captcha', 'required|exact_length[6]');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
        } else {

            $appl_ref_no = $this->getID(7);
            $sessionUser = $this->session->userdata();

            if ($this->slug === "CSC") {
                $apply_by = $sessionUser['userId'];
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            } //End of if else

            while (1) {
                $app_id = rand(100000000, 999999999);
                $filter = array(
                    "service_data.appl_id" => $app_id,
                    //"service_data.service_id" => $this->serviceId
                );
                $rows = $this->acmrnoc_model->get_row($filter);

                if ($rows == false)
                    break;
            }

            $service_data = [
                "department_id" => "5555",
                "department_name" => "Assam Council of Medical Registration",
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => "Assam Council of Medical Registration", // office name
                "submission_date" => "",
                "district" => explode("/", $this->input->post("district"))[0],
                "service_timeline" => "7 Days",
                "appl_status" => "DRAFT",
            ];

            $form_data = [
                'user_id' => $sessionUser['userId']->{'$id'},
                'user_type' => $this->slug,
                'applicant_name' => $this->input->post("applicant_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'father_name' => $this->input->post("father_name"),
                'mobile' => $this->input->post("mobile"),
                'dob' => $this->input->post("dob"),
                'email' => $this->input->post("email"),
                'pan_no' => $this->input->post("pan_no"),
                'permanent_address' => $this->input->post("permanent_address"),
                'correspondence_address' => $this->input->post("correspondence_address"),
                'permanent_reg_no' => $this->input->post("permanent_reg_no"),
                'permanent_reg_date' => $this->input->post("permanent_reg_date"),
                'additional_degree_reg_no' => $this->input->post("additional_degree_reg_no"),
                'additional_degree_reg_date' => $this->input->post("additional_degree_reg_date"),
                'registering_smc' => $this->input->post("registering_smc"),
                'relocating_reason' => $this->input->post("relocating_reason"),
                'working_place_add' => $this->input->post("working_place_add"),
                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];

            if (strlen($objId)) {
                $form_data["passport_photo_type"] = $this->input->post("passport_photo_type");
                $form_data["passport_photo"] = $this->input->post("passport_photo");
                $form_data["signature_type"] = $this->input->post("signature_type");
                $form_data["signature"] = $this->input->post("signature");
                $form_data["ug_pg_diploma_type"] = $this->input->post("ug_pg_diploma_type");
                $form_data["ug_pg_diploma"] = $this->input->post("ug_pg_diploma");

                $form_data["prc_type"] = $this->input->post("prc_type");
                $form_data["prc"] = $this->input->post("prc");

                if (!empty($this->input->post("mbbs_certificate_type"))) {
                    $form_data["mbbs_certificate_type"] = $this->input->post("mbbs_certificate_type");
                    $form_data["mbbs_certificate"] = $this->input->post("mbbs_certificate");
                }

                if (!empty($this->input->post("noc_dme_type"))) {
                    $form_data["noc_dme_type"] = $this->input->post("noc_dme_type");
                    $form_data["noc_dme"] = $this->input->post("noc_dme");
                }

                if (!empty($this->input->post("seat_allt_letter_type"))) {
                    $form_data["seat_allt_letter_type"] = $this->input->post("seat_allt_letter_type");
                    $form_data["seat_allt_letter"] = $this->input->post("seat_allt_letter");
                }
            }

            $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data
            ];

            if (strlen($objId)) {
                $this->acmrnoc_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/acmr-noc/fileuploads/' . $objId);
            } else {
                $insert = $this->acmrnoc_model->insert($inputs);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    redirect('spservices/acmr-noc/fileuploads/' . $objectId);
                    exit();
                } else {
                    $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                    $this->index();
                } //End of if else
            } //End of if else

        } //End of if else
    } //End of submit()

    public function fileuploads($objId = null)
    {
        $dbRow = $this->acmrnoc_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "pageTitle" => "Attached Enclosures for " . $this->serviceName,
                "obj_id" => $objId,
                "dbrow" => $dbRow
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('acmrnoc/acmrnocuploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/acmrnoc/noc/');
        } //End of if else
    } //End of fileuploads()

    public function submitfiles()
    {
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $this->form_validation->set_rules('passport_photo_type', 'Passport photo type', 'required');
        $this->form_validation->set_rules('signature_type', 'Applicant signature', 'required');
        $this->form_validation->set_rules('ug_pg_diploma_type', 'Certificate of UG/PG/ Diploma', 'required');
        $this->form_validation->set_rules('prc_type', 'Permanent Registration certificate of Assam Medical Council', 'required');

        if (empty($this->input->post("ug_pg_diploma_old"))) {
            if (((empty($this->input->post("ug_pg_diploma_type"))) && (($_FILES['ug_pg_diploma']['name'] != "") || (!empty($this->input->post("ug_pg_diploma_temp"))))) || ((!empty($this->input->post("ug_pg_diploma_type"))) && (($_FILES['ug_pg_diploma']['name'] == "") && (empty($this->input->post("ug_pg_diploma_temp")))))) {

                $this->form_validation->set_rules('ug_pg_diploma_type', 'Certificate of UG/PG/ Diploma Document Type', 'required');
                $this->form_validation->set_rules('ug_pg_diploma', 'Certificate of UG/PG/ Diploma Document', 'required');
            }
        }

        if (empty($this->input->post("prc_old"))) {
            if (((empty($this->input->post("prc_type"))) && (($_FILES['prc']['name'] != "") || (!empty($this->input->post("prc_temp"))))) || ((!empty($this->input->post("prc_type"))) && (($_FILES['prc']['name'] == "") && (empty($this->input->post("prc_temp")))))) {

                $this->form_validation->set_rules('prc_type', 'Permanent Registration certificate of Assam Medical Council Document Type', 'required');
                $this->form_validation->set_rules('prc', 'Permanent Registration certificate of Assam Medical Council Document', 'required');
            }
        }

        if (empty($this->input->post("mbbs_certificate_old"))) {
            if ((empty($this->input->post("mbbs_certificate_type"))) && (($_FILES['mbbs_certificate']['name'] != "") || (!empty($this->input->post("mbbs_certificate_temp"))))) {

                $this->form_validation->set_rules('mbbs_certificate_type', 'Rural completion certificate (MBBS) Document Type', 'required');
            }

            if ((!empty($this->input->post("mbbs_certificate_type"))) && (($_FILES['mbbs_certificate']['name'] == "") && (empty($this->input->post("mbbs_certificate_temp"))))) {
                $this->form_validation->set_rules('mbbs_certificate', 'Rural completion certificate (MBBS) Document', 'required');
            }
        }

        if (empty($this->input->post("noc_dme_old"))) {
            if ((empty($this->input->post("noc_dme_type"))) && (($_FILES['noc_dme']['name'] != "") || (!empty($this->input->post("noc_dme_temp"))))) {

                $this->form_validation->set_rules('noc_dme_type', 'NOC from Directorate of Medical Education Document Type', 'required');
            }

            if ((!empty($this->input->post("noc_dme_type"))) && (($_FILES['noc_dme']['name'] == "") && (empty($this->input->post("noc_dme_temp"))))) {
                $this->form_validation->set_rules('noc_dme', 'NOC from Directorate of Medical Education Document', 'required');
            }
        }

        if (empty($this->input->post("seat_allt_letter_old"))) {
            if ((empty($this->input->post("seat_allt_letter_type"))) && (($_FILES['seat_allt_letter']['name'] != "") || (!empty($this->input->post("seat_allt_letter_temp"))))) {

                $this->form_validation->set_rules('seat_allt_letter_type', 'Recomended Document Type', 'required');
            }

            if ((!empty($this->input->post("seat_allt_letter_type"))) && (($_FILES['seat_allt_letter']['name'] == "") && (empty($this->input->post("seat_allt_letter_temp"))))) {
                $this->form_validation->set_rules('seat_allt_letter', 'Recomended Document', 'required');
            }
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");


        $passport_photo = "";
        if ($_FILES['passport_photo']['name'] != "") {
            $passportPhoto = cifileupload("passport_photo");
            $passport_photo = $passportPhoto["upload_status"] ? $passportPhoto["uploaded_path"] : null;
        }

        $signature = "";
        if ($_FILES['signature']['name'] != "") {
            $signatureUpload = cifileupload("signature");
            $signature = $signatureUpload["upload_status"] ? $signatureUpload["uploaded_path"] : null;
        }
        // pre($signatureUpload);
        if (strlen($this->input->post("ug_pg_diploma_temp")) > 0) {
            $ugPgDiploma = movedigilockerfile($this->input->post('ug_pg_diploma_temp'));
            $ug_pg_diploma = $ugPgDiploma["upload_status"] ? $ugPgDiploma["uploaded_path"] : null;
        } else {
            $ugPgDiploma = cifileupload("ug_pg_diploma");
            $ug_pg_diploma = $ugPgDiploma["upload_status"] ? $ugPgDiploma["uploaded_path"] : null;
        }

        if (strlen($this->input->post("prc_temp")) > 0) {
            $prcUpload = movedigilockerfile($this->input->post('prc_temp'));
            $prc = $prcUpload["upload_status"] ? $prcUpload["uploaded_path"] : null;
        } else {
            $prcUpload = cifileupload("prc");
            $prc = $prcUpload["upload_status"] ? $prcUpload["uploaded_path"] : null;
        }

        if (strlen($this->input->post("mbbs_certificate_temp")) > 0) {
            $mbbsCertificateUpload = movedigilockerfile($this->input->post('mbbs_certificate_temp'));
            $mbbs_certificate = $mbbsCertificateUpload["upload_status"] ? $mbbsCertificateUpload["uploaded_path"] : null;
        } else {
            $mbbsCertificateUpload = cifileupload("mbbs_certificate");
            $mbbs_certificate = $mbbsCertificateUpload["upload_status"] ? $mbbsCertificateUpload["uploaded_path"] : null;
        }

        if (strlen($this->input->post("noc_dme_temp")) > 0) {
            $nocDmeUpload = movedigilockerfile($this->input->post('noc_dme_temp'));
            $noc_dme = $nocDmeUpload["upload_status"] ? $nocDmeUpload["uploaded_path"] : null;
        } else {
            $nocDmeUpload = cifileupload("noc_dme");
            $noc_dme = $nocDmeUpload["upload_status"] ? $nocDmeUpload["uploaded_path"] : null;
        }

        if (strlen($this->input->post("seat_allt_letter_temp")) > 0) {
            $seatAlltLetter = movedigilockerfile($this->input->post('seat_allt_letter_temp'));
            $seat_allt_letter = $seatAlltLetter["upload_status"] ? $seatAlltLetter["uploaded_path"] : null;
        } else {
            $seatAlltLetter = cifileupload("seat_allt_letter");
            $seat_allt_letter = $seatAlltLetter["upload_status"] ? $seatAlltLetter["uploaded_path"] : null;
        }

        $uploadedFiles = array(
            "passport_photo_old" => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
            "signature_old" => strlen($signature) ? $signature : $this->input->post("signature_old"),
            "ug_pg_diploma_old" => strlen($ug_pg_diploma) ? $ug_pg_diploma : $this->input->post("ug_pg_diploma_old"),
            "prc_old" => strlen($prc) ? $prc : $this->input->post("prc_old"),
            "mbbs_certificate_old" => strlen($mbbs_certificate) ? $mbbs_certificate : $this->input->post("mbbs_certificate_old"),
            "noc_dme_old" => strlen($noc_dme) ? $noc_dme : $this->input->post("noc_dme_old"),
            "seat_allt_letter_old" => strlen($seat_allt_letter) ? $seat_allt_letter : $this->input->post("seat_allt_letter_old"),
        );

        if (empty($uploadedFiles['passport_photo_old']) || empty($uploadedFiles['signature_old']) || empty($uploadedFiles['ug_pg_diploma_old']) || empty($uploadedFiles['prc_old'])) {
            //$this->session->set_flashdata('uploaded_files', $uploadedFiles);
            $this->session->set_flashdata('fail', 'Some filetype you are attempting to upload is not allowed.!');
            $this->fileuploads($objId);
            return;
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.passport_photo_type' => $this->input->post("passport_photo_type"),
                'form_data.passport_photo' => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
                'form_data.signature_type' => $this->input->post("signature_type"),
                'form_data.signature' => strlen($signature) ? $signature : $this->input->post("signature_old"),
                'form_data.ug_pg_diploma_type' => $this->input->post("ug_pg_diploma_type"),
                'form_data.ug_pg_diploma' => strlen($ug_pg_diploma) ? $ug_pg_diploma : $this->input->post("ug_pg_diploma_old"),
                'form_data.prc_type' => $this->input->post("prc_type"),
                'form_data.prc' => strlen($prc) ? $prc : $this->input->post("prc_old"),
            );

            if (!empty($this->input->post("mbbs_certificate_type"))) {
                $data["form_data.mbbs_certificate_type"] = $this->input->post("mbbs_certificate_type");
                $data["form_data.mbbs_certificate"] = strlen($mbbs_certificate) ? $mbbs_certificate : $this->input->post("mbbs_certificate_old");
            }

            if (!empty($this->input->post("noc_dme_type"))) {
                $data["form_data.noc_dme_type"] = $this->input->post("noc_dme_type");
                $data["form_data.noc_dme"] = strlen($noc_dme) ? $noc_dme : $this->input->post("noc_dme_old");
            }
            if (!empty($this->input->post("seat_allt_letter_type"))) {
                $data["form_data.seat_allt_letter_type"] = $this->input->post("seat_allt_letter_type");
                $data["form_data.seat_allt_letter"] = strlen($seat_allt_letter) ? $seat_allt_letter : $this->input->post("seat_allt_letter_old");
            }

            $this->acmrnoc_model->update_where(['_id' => new ObjectId($objId)], $data);
            //$this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/acmrnoc/noc/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()

    public function preview($objId = null)
    {
        $dbRow = $this->acmrnoc_model->get_by_doc_id($objId);
        //var_dump($dbRow); die;
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('acmrnoc/nocpreview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/acmrnoc/noc/');
        } //End of if else
    } //End of preview()

    public function finalsubmition($obj = null)
    {

        if ($obj) {
            // pre($obj);
            $dbRow = $this->acmrnoc_model->get_by_doc_id($obj);
            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }
            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")) {

                //procesing data
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $this->load->model('upms/users_model');
                $userFilter = array('user_services.service_code' => $this->serviceId, 'user_roles.role_code' => 'LDA');
                $userRows = $this->users_model->get_rows($userFilter);
                $current_users = array();
                if ($userRows) {
                    foreach ($userRows as $key => $userRow) {
                        $current_user = array(
                            'login_username' => $userRow->login_username,
                            'email_id' => $userRow->email_id,
                            'mobile_number' => $userRow->mobile_number,
                            'user_level_no' => $userRow->user_levels->level_no,
                            'user_fullname' => $userRow->user_fullname,
                        );
                        $current_users[] = $current_user;
                    } //End of foreach()
                }

                //pre($current_users);

                $data_to_update = array(
                    'service_data.appl_status' => 'submitted',
                    'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'current_users' => $current_users,
                    'processing_history' => $processing_history
                );
                $this->acmrnoc_model->update($obj, $data_to_update);
                //Sending submission SMS
                $nowTime = date('Y-m-d H:i:s');
                $sms = array(
                    "mobile" => (int)$dbRow->form_data->mobile,
                    "applicant_name" => $dbRow->form_data->applicant_name,
                    "service_name" => 'No Objection Certificate-ACMR',
                    "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                    "app_ref_no" => $dbRow->service_data->appl_ref_no
                );
                sms_provider("submission", $sms);


                redirect('spservices/applications/acknowledgement/' . $obj);
            } else {
                $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                $this->my_transactions();
            }
        }
    }

    function createcaptcha()
    {
        $captchaDir = "storage/captcha/";
        array_map('unlink', glob("$captchaDir*.jpg"));

        $this->load->helper('captcha');
        $config = array(
            'img_path' => './storage/captcha/',
            'img_url' => base_url('storage/captcha/'),
            'font_path' => APPPATH . 'sys/fonts/texb.ttf',
            'img_width' => '150',
            'img_height' => 30,
            'expiration' => 7200,
            'word_length' => 6,
            'font_size' => 16,
            'img_id' => 'capimg',
            'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(94, 20, 38),
                'text' => array(0, 0, 0),
                'grid' => array(178, 184, 194)
            )
        );
        $captcha = create_captcha($config);
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode', $captcha['word']);
        echo $captcha['image'];
    } //End of createcaptcha()

    public function track($objId = null)
    {
        $dbRow = $this->acmrnoc_model->get_by_doc_id($objId);
        if (isset($dbRow->form_data->edistrict_ref_no) && !empty($dbRow->form_data->edistrict_ref_no)) {
            $this->load->helper('trackstatus');
            fetchEdistrictData($dbRow->form_data->edistrict_ref_no);
            $dbRow = $this->acmrnoc_model->get_by_doc_id($objId);
        }
        if (count((array)$dbRow)) {
            $data = array(
                "service_data.service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('acmrnoc/acmrnoctrack_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/acmrnoc/noc/');
        } //End of if else
    } //End of track()

    function getID($length)
    {
        $rtps_trans_id = $this->generateID($length);
        while ($this->acmrnoc_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        } //End of while()
        return $rtps_trans_id;
    } //End of getID()

    public function generateID($length)
    {
        $date = date('Y');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-ACMRNOC/" . $date . "/" . $number;
        return $str;
    } //End of generateID()

    public function queryform($objId = null)
    {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->acmrnoc_model->get_row(array("_id" => new ObjectId($objId), "service_data.appl_status" => "QS"));
            if ($dbRow) {
                $data = array(
                    "service_data.service_name" => $this->serviceName,
                    "dbrow" => $dbRow
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('acmrnoc/acmrnocquery_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/acmrnoc/noc');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/acmrnoc/noc');
        } //End of if else
    } //End of query()

    public function querysubmit()
    {
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");
        $this->form_validation->set_rules('passport_photo_type', 'Passport photo type', 'required');
        $this->form_validation->set_rules('signature_type', 'Applicant signature', 'required');
        $this->form_validation->set_rules('ug_pg_diploma_type', 'Certificate of UG/PG/ Diploma', 'required');
        $this->form_validation->set_rules('prc_type', 'Permanent Registration certificate of Assam Medical Council', 'required');

        if (empty($this->input->post("mbbs_certificate_old"))) {
            if ((empty($this->input->post("mbbs_certificate_type"))) && (($_FILES['mbbs_certificate']['name'] != ""))) {
                $this->form_validation->set_rules('mbbs_certificate_type', 'Rural completion certificate (MBBS)', 'required');
            }

            if ((!empty($this->input->post("mbbs_certificate_type"))) && (($_FILES['mbbs_certificate']['name'] == ""))) {
                $this->form_validation->set_rules('mbbs_certificate', 'Rural completion certificate (MBBS)', 'required');
            }
        }

        if (empty($this->input->post("noc_dme_old"))) {
            if ((empty($this->input->post("noc_dme_type"))) && (($_FILES['noc_dme']['name'] != ""))) {
                $this->form_validation->set_rules('noc_dme_type', 'NOC from Directorate of Medical Education', 'required');
            }

            if ((!empty($this->input->post("noc_dme_type"))) && (($_FILES['noc_dme']['name'] == ""))) {
                $this->form_validation->set_rules('noc_dme', 'NOC from Directorate of Medical Education', 'required');
            }
        }

        if (empty($this->input->post("seat_allt_letter_old"))) {
            if ((empty($this->input->post("seat_allt_letter_type"))) && (($_FILES['seat_allt_letter']['name'] != ""))) {
                $this->form_validation->set_rules('seat_allt_letter_type', 'Recomended document type', 'required');
            }

            if ((!empty($this->input->post("seat_allt_letter_type"))) && (($_FILES['seat_allt_letter']['name'] == ""))) {
                $this->form_validation->set_rules('seat_allt_letter', 'Provisional Seat Allotment letter for All India Quota doctors', 'required');
            }
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $passport_photo = "";
        if ($_FILES['passport_photo']['name'] != "") {
            $passportPhoto = cifileupload("passport_photo");
            $passport_photo = $passportPhoto["upload_status"] ? $passportPhoto["uploaded_path"] : null;
        }

        $signature = "";
        if ($_FILES['signature']['name'] != "") {
            $signatureUpload = cifileupload("signature");
            $signature = $signatureUpload["upload_status"] ? $signatureUpload["uploaded_path"] : null;
        }

        $ugPgDiploma = cifileupload("ug_pg_diploma");
        $ug_pg_diploma = $ugPgDiploma["upload_status"] ? $ugPgDiploma["uploaded_path"] : null;

        $prcUpload = cifileupload("prc");
        $prc = $prcUpload["upload_status"] ? $prcUpload["uploaded_path"] : null;

        $mbbsCertificateUpload = cifileupload("mbbs_certificate");
        $mbbs_certificate = $mbbsCertificateUpload["upload_status"] ? $mbbsCertificateUpload["uploaded_path"] : null;

        $nocDmeUpload = cifileupload("noc_dme");
        $noc_dme = $nocDmeUpload["upload_status"] ? $nocDmeUpload["uploaded_path"] : null;

        $seatAlltLetter = cifileupload("seat_allt_letter");
        $seat_allt_letter = $seatAlltLetter["upload_status"] ? $seatAlltLetter["uploaded_path"] : null;

        $uploadedFiles = array(
            "passport_photo_old" => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
            "signature_old" => strlen($signature) ? $signature : $this->input->post("signature_old"),
            "ug_pg_diploma_old" => strlen($ug_pg_diploma) ? $ug_pg_diploma : $this->input->post("ug_pg_diploma_old"),
            "prc_old" => strlen($prc) ? $prc : $this->input->post("prc_old"),
            "mbbs_certificate_old" => strlen($mbbs_certificate) ? $mbbs_certificate : $this->input->post("mbbs_certificate_old"),
            "noc_dme_old" => strlen($noc_dme) ? $noc_dme : $this->input->post("noc_dme_old"),
            "seat_allt_letter_old" => strlen($seat_allt_letter) ? $seat_allt_letter : $this->input->post("seat_allt_letter_old"),
        );

        if (empty($uploadedFiles['passport_photo_old']) || empty($uploadedFiles['signature_old']) || empty($uploadedFiles['ug_pg_diploma_old']) || empty($uploadedFiles['prc_old'])) {
            //$this->session->set_flashdata('uploaded_files', $uploadedFiles);
            $this->session->set_flashdata('fail', 'Some filetype you are attempting to upload is not allowed.!');
            $this->fileuploads($objId);
            return;
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->queryform($objId);
        } else {
            $dbRow = $this->acmrnoc_model->get_by_doc_id($objId);
            if (count((array)$dbRow)) {
                $data = array(
                    'form_data.passport_photo_type' => $this->input->post("passport_photo_type"),
                    'form_data.passport_photo' => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
                    'form_data.signature_type' => $this->input->post("signature_type"),
                    'form_data.signature' => strlen($signature) ? $signature : $this->input->post("signature_old"),
                    'form_data.ug_pg_diploma_type' => $this->input->post("ug_pg_diploma_type"),
                    'form_data.ug_pg_diploma' => strlen($ug_pg_diploma) ? $ug_pg_diploma : $this->input->post("ug_pg_diploma_old"),
                    'form_data.prc_type' => $this->input->post("prc_type"),
                    'form_data.prc' => strlen($prc) ? $prc : $this->input->post("prc_old"),

                );

                if (!empty($this->input->post("mbbs_certificate_type"))) {
                    $data["form_data.mbbs_certificate_type"] = $this->input->post("mbbs_certificate_type");
                    $data["form_data.mbbs_certificate"] = strlen($mbbs_certificate) ? $mbbs_certificate : $this->input->post("mbbs_certificate_old");
                }

                if (!empty($this->input->post("noc_dme_type"))) {
                    $data["form_data.noc_dme_type"] = $this->input->post("noc_dme_type");
                    $data["form_data.noc_dme"] = strlen($noc_dme) ? $noc_dme : $this->input->post("noc_dme_old");
                }
                if (!empty($this->input->post("seat_allt_letter_type"))) {
                    $data["form_data.seat_allt_letter_type"] = $this->input->post("seat_allt_letter_type");
                    $data["form_data.seat_allt_letter"] = strlen($seat_allt_letter) ? $seat_allt_letter : $this->input->post("seat_allt_letter_old");
                }

                //$this->acmrnoc_model->update_where(['_id' => new ObjectId($objId)], $data);
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Query submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Query submitted",
                    "remarks" => "Query submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
                $data["service_data.appl_status"] = "QA";
                $data["status"] = "QUERY_ANSWERED";
                $data["processing_history"] = $processing_history;
                $this->acmrnoc_model->update_where(['_id' => new ObjectId($objId)], $data);
                $this->session->set_flashdata('success', 'Your application has been successfully updated');
                redirect('spservices/acmrnoc/noc/preview/' . $objId);
            } else {
                $this->session->set_flashdata('fail', 'Unable to update data!!! Please try again.');
                $this->queryform($objId);
            }
        } //End of if else    
    } //End of querysubmit()

    public function submit_to_backend($obj, $show_ack = false)
    {

        if ($obj) {
            // pre($obj);
            $dbRow = $this->acmrnoc_model->get_by_doc_id($obj);
            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }
            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")) {

                //procesing data
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $this->load->model('upms/users_model');
                $userFilter = array('user_services.service_code' => $this->serviceId, 'user_roles.role_code' => 'LDA');
                $userRows = $this->users_model->get_rows($userFilter);
                $current_users = array();
                if ($userRows) {
                    foreach ($userRows as $key => $userRow) {
                        $current_user = array(
                            'login_username' => $userRow->login_username,
                            'email_id' => $userRow->email_id,
                            'mobile_number' => $userRow->mobile_number,
                            'user_level_no' => $userRow->user_levels->level_no,
                            'user_fullname' => $userRow->user_fullname,
                        );
                        $current_users[] = $current_user;
                    } //End of foreach()
                }

                //pre($current_users);

                $data_to_update = array(
                    'service_data.appl_status' => 'submitted',
                    'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'current_users' => $current_users,
                    'processing_history' => $processing_history
                );
                $this->acmrnoc_model->update($obj, $data_to_update);
                //Sending submission SMS
                $nowTime = date('Y-m-d H:i:s');
                $sms = array(
                    "mobile" => (int)$dbRow->form_data->mobile,
                    "applicant_name" => $dbRow->form_data->applicant_name,
                    "service_name" => 'No Objection Certificate-ACMR',
                    "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                    "app_ref_no" => $dbRow->service_data->appl_ref_no
                );
                sms_provider("submission", $sms);
                redirect('spservices/applications/acknowledgement/' . $obj);
            } else {
                $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                $this->my_transactions();
            }
        }
    }

    public function get_age()
    {
        $dob = $this->input->post("dob");
        if (strlen($dob) == 10) {
            $date_of_birth = new DateTime($dob);
            $nowTime = new DateTime();
            $interval = $date_of_birth->diff($nowTime);
            echo $interval->format('%y Years %m Months and %d Days');
        } else {
            echo "Invalid date format";
        } //End of if else
    } //End of get_age()

    private function checkObjectId($obj)
    {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        } //End of if else
    } //End of checkObjectId()

    private function my_transactions()
    {
        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }

    public function applicationpreview($objId = null)
    {
        $dbRow = $this->acmrnoc_model->get_by_doc_id($objId);
        //var_dump($dbRow); die;
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('acmrnoc/view_form_data', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/income/inc/');
        } //End of if else
    } //End of preview()

    public function download_certificate($objId = null)
    {
        if (!empty($objId)) {
            $dbRow = $this->acmrnoc_model->get_by_doc_id($objId);
            // var_dump($dbRow); die;
            if (count((array)$dbRow) && isset($dbRow->form_data->certificate)) {
                if (file_exists($dbRow->form_data->certificate)) {
                    cifiledownload($dbRow->form_data->certificate);

                    //     $doc_file_path =  $dbRow->form_data->certificate;
                    //     $b64encode = base64_encode(file_get_contents($doc_file_path));
                    //     $b64decode = base64_decode($b64encode);

                    //     $finfo = finfo_open();
                    //     $mime_type = finfo_buffer($finfo, $b64decode, FILEINFO_MIME_TYPE);
                    //     finfo_close($finfo);
                    //     $ext = (get_file_extension($mime_type) == 'N/A') ? '' : get_file_extension($mime_type);

                    //     // redirect output to client browser
                    //     header("Content-type: {$mime_type}");
                    //     header('Content-Disposition: attachment;filename="noc_' . $dbRow->service_data->appl_ref_no . ".{$ext}");
                    //     header('Cache-Control: max-age=0');
                    //     echo $b64decode;
                    // }else {
                    //     $this->session->set_flashdata('errmessage', 'Something went wrong.!, Please try later.');
                    //     $this->my_transactions();
                }
            } else {
                $this->session->set_flashdata('errmessage', 'Something went wrong!, Please try later.');
                $this->my_transactions();
            }
        }
    }
}//End of Castecertificate
