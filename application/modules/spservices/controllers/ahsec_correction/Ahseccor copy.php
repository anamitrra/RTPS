<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Ahseccor extends Rtps
{
    private $serviceName;
    private $serviceId;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ahsec_correction/ahsec_correction_model');
        $this->load->model('duplicatecertificateahsec/ahsecregistration_model');
        $this->load->model('duplicatecertificateahsec/ahsecmarksheet_model');
        //$this->load->model('necprocessing_model');
        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->helper('smsprovider');
        $this->load->library('phpqrcode/qrlib');
        $this->load->helper("log");
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
    } //End of __construct()

    public function index($obj_id = null)
    {
        //check_application_count_for_citizen(); 
        // if ($obj_id == "AHSECCRC") {
        //     $data = array("pageTitleId" => "AHSECCRC", "pageTitle" => "Application for Correction in Registration Card");
        //     $data["dbrow"] = null;
        // } else if ($obj_id == "AHSECCADM") {
        //     $data = array("pageTitleId" => "AHSECCADM", "pageTitle" => "Application for Correction in Admit Card");
        //     $data["dbrow"] = null;
        // } else if ($obj_id == "AHSECCMRK") {
        //     $data = array("pageTitleId" => "AHSECCMRK", "pageTitle" => "Application for Correction in Marksheet");
        //     $data["dbrow"] = null;
        // } else if ($obj_id == "AHSECCPC") {
        //     $data = array("pageTitleId" => "AHSECCPC", "pageTitle" => "Application for Correction in Passcertificate");
        //     $data["dbrow"] = null;
        // } else 
        if ($this->checkObjectId($obj_id)) {
            $filter = array("_id" => new ObjectId($obj_id), "service_data.appl_status" => "DRAFT");
            $data["dbrow"] = $this->ahsec_correction_model->get_row($filter);
        } else {
            $this->my_transactions();
        }
        $data['usser_type'] = $this->slug;
        $data["sessions"] = $this->fetchsessions(15);
        $data["years"] = $this->fetchyear(37);
        $this->load->view('includes/frontend/header');
        $this->load->view('ahsec_correction/correction_form', $data);
        $this->load->view('includes/frontend/footer');
    } //End of index()
    public function getlocation()
    {
        $id = $_GET['id'];
        if ($id) {
            $data = $this->ahsec_correction_model->get_sro_list($id);
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
        if (strlen($objId)) {
            $this->serviceName = $this->input->post("service_name");
            $this->serviceId = $this->input->post("service_id");
            $this->form_validation->set_rules('applicant_name', 'Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
            $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('father_name', 'Father name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
            $this->form_validation->set_rules('mother_name', 'Mother name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
            $this->form_validation->set_rules('mobile', 'Contact number', 'trim|integer|required|exact_length[10]|xss_clean|strip_tags');
            $this->form_validation->set_rules('email', 'Email id', 'valid_email|required|trim|xss_clean|strip_tags');
            $this->form_validation->set_rules('p_comp_permanent_address', 'Complete address', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('p_state', 'State', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('p_district', 'District', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('p_police_st', 'Police st', 'trim|xss_clean|strip_tags');
            $this->form_validation->set_rules('p_post_office', 'Post office', 'trim|xss_clean|strip_tags');
            $this->form_validation->set_rules('p_pin_code', 'PIN code', 'trim|required|integer|exact_length[6]|xss_clean|strip_tags');

            $this->form_validation->set_rules('checker', 'Same as permanent address', 'trim|integer|exact_length[1]|xss_clean|strip_tags');

            $this->form_validation->set_rules('c_comp_permanent_address', 'Complete address', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('c_state', 'State', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('c_district', 'District', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('c_police_st', 'Police st', 'trim|xss_clean|strip_tags');
            $this->form_validation->set_rules('c_post_office', 'Post office', 'trim|xss_clean|strip_tags');
            $this->form_validation->set_rules('c_pin_code', 'PIN code', 'trim|required|integer|exact_length[6]|xss_clean|strip_tags');

            if ($this->serviceId == "AHSECCPC") {
                $this->form_validation->set_rules('results', 'Results field', 'trim|required|xss_clean|strip_tags');
            }
            $this->form_validation->set_rules('candidate_name_checkbox', 'Candidate name checkbox', 'trim|integer|exact_length[1]|xss_clean|strip_tags');
            $this->form_validation->set_rules('father_name_checkbox', 'Father name checkbox', 'trim|integer|exact_length[1]|xss_clean|strip_tags');
            $this->form_validation->set_rules('mother_name_checkbox', 'Mother name checkbox', 'trim|integer|exact_length[1]|xss_clean|strip_tags');

            if ($this->input->post("candidate_name_checkbox") == "1") {
                $this->form_validation->set_rules('incorrect_candidate_name', 'Candidate name(as per current record)', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
                $this->form_validation->set_rules('correct_candidate_name', 'Candidate name(to be corrected as)', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
            }
            if ($this->input->post("father_name_checkbox") == "2") {
                $this->form_validation->set_rules('incorrect_father_name', 'Father name(as per current record)', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
                $this->form_validation->set_rules('correct_father_name', 'Father name(to be corrected as)', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
            }
            if ($this->input->post("mother_name_checkbox") == "3") {
                $this->form_validation->set_rules('incorrect_mother_name', 'Mother name(as per current record)', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
                $this->form_validation->set_rules('correct_mother_name', 'Mother name(to be corrected as)', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
            }
            $this->form_validation->set_rules('delivery_mode', 'Delivery preference', 'trim|required|xss_clean|strip_tags');

            // $this->form_validation->set_rules('inputcaptcha', 'Captcha', 'required|exact_length[6]');
            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $obj_id = strlen($objId) ? $objId : $this->serviceId;
                $this->index($obj_id);
            } else {
                $form_data = [
                    'form_data.applicant_name' => $this->input->post("applicant_name"),
                    'form_data.applicant_gender' => $this->input->post("applicant_gender"),
                    'form_data.father_name' => $this->input->post("father_name"),
                    'form_data.mother_name' => $this->input->post("mother_name"),
                    'form_data.mobile' => $this->input->post("mobile"),
                    'form_data.email' => $this->input->post("email"),
                    'form_data.p_comp_permanent_address' => $this->input->post("p_comp_permanent_address"),
                    'form_data.p_state' => $this->input->post("p_state"),
                    'form_data.p_district' => $this->input->post("p_district"),
                    'form_data.p_police_st' => $this->input->post("p_police_st"),
                    'form_data.p_post_office' => $this->input->post("p_post_office"),
                    'form_data.p_pin_code' => $this->input->post("p_pin_code"),
                    'form_data.same_as_p_address' => $this->input->post("checker"),
                    'form_data.c_comp_permanent_address' => trim($this->input->post("c_comp_permanent_address")),
                    'form_data.c_state' => $this->input->post("c_state"),
                    'form_data.c_district' => $this->input->post("c_district"),
                    'form_data.c_police_st' => $this->input->post("c_police_st"),
                    'form_data.c_post_office' => $this->input->post("c_post_office"),
                    'form_data.c_pin_code' => $this->input->post("c_pin_code"),

                    'form_data.results' => $this->input->post("results"),

                    'form_data.candidate_name_checkbox' => $this->input->post("candidate_name_checkbox"),
                    'form_data.father_name_checkbox' => $this->input->post("father_name_checkbox"),
                    'form_data.mother_name_checkbox' => $this->input->post("mother_name_checkbox"),

                    'form_data.incorrect_candidate_name' => $this->input->post("incorrect_candidate_name"),
                    'form_data.incorrect_father_name' => $this->input->post("incorrect_father_name"),
                    'form_data.incorrect_mother_name' => $this->input->post("incorrect_mother_name"),

                    'form_data.correct_candidate_name' => $this->input->post("correct_candidate_name"),
                    'form_data.correct_father_name' => $this->input->post("correct_father_name"),
                    'form_data.correct_mother_name' => $this->input->post("correct_mother_name"),
                    'form_data.delivery_mode' => $this->input->post("delivery_mode"),
                    'form_data.created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                ];

                if (strlen($objId)) {

                    $form_data["form_data.passport_photo_type"] = $this->input->post("passport_photo_type");
                    $form_data["form_data.passport_photo"] = $this->input->post("passport_photo");
                    $form_data["form_data.signature_type"] = $this->input->post("signature_type");
                    $form_data["form_data.signature"] = $this->input->post("signature");
                    $form_data["other_doc_type"] = $this->input->post("other_doc_type");
                    $form_data["other_doc"] = $this->input->post("other_doc");
                    $form_data["form_data.affidavit_type"] = $this->input->post("affidavit_type");
                    $form_data["form_data.affidavit"] = $this->input->post("affidavit");
                    $form_data["form_data.registration_card_type"] = $this->input->post("registration_card_type");
                    $form_data["form_data.registration_card"] = $this->input->post("registration_card");
                    $form_data["form_data.admit_card_type"] = $this->input->post("admit_card_type");
                    $form_data["form_data.admit_card"] = $this->input->post("admit_card");
                    if (!empty($this->input->post("marksheet_type"))) {
                        $form_data["form_data.marksheet_type"] = $this->input->post("marksheet_type");
                        $form_data["form_data.marksheet"] = $this->input->post("marksheet");
                    }
                    $form_data["form_data.pass_certificate_type"] = $this->input->post("pass_certificate_type");
                    $form_data["form_data.pass_certificate"] = $this->input->post("pass_certificate");
                    $form_data["form_data.soft_copy_type"] = $this->input->post("soft_copy_type");
                    $form_data["form_data.soft_copy"] = $this->input->post("soft_copy");
                }


                $this->ahsec_correction_model->update_where(['_id' => new ObjectId($objId)], $form_data);

                // $this->mongo_db->set($form_data);
                // $this->mongo_db->where(['_id' => new ObjectId($objId)]);
                // $status= $this->mongo_db->update_all('sp_applications');

                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/ahsec_correction/ahseccor/fileuploads/' . $objId);
            } //End of if else
        } else {
            $this->my_transactions();
        }
    } //End of submit()

    public function fileuploads($objId = null)
    {
        // pre("Files upload");
        $dbRow = $this->ahsec_correction_model->get_by_doc_id($objId);
        // pre($dbRow);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $dbRow->service_data->service_name,
                "pageTitle" => "Attached Enclosures for " . $dbRow->service_data->service_name,
                "pageTitleId" => $dbRow->service_data->service_id,
                "obj_id" => $objId,
                "dbrow" => $dbRow
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('ahsec_correction/correctionuploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/ahsec_correction/ahseccor/');
        } //End of if else
    } //End of fileuploads()

    public function submitfiles()
    {
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $dbrow = $this->ahsec_correction_model->get_by_doc_id($objId);

        $this->form_validation->set_rules('passport_photo_type', 'Passport photo type', 'required');
        $this->form_validation->set_rules('signature_type', 'Applicant signature', 'required');
        $this->form_validation->set_rules('registration_card_type', 'Registration card type', 'required');
        $this->form_validation->set_rules('admit_card_type', 'Admit card type', 'required');

        if (empty($this->input->post("registration_card_old"))) {
            if ((empty($this->input->post("registration_card_temp"))) && (($_FILES['registration_card']['name'] == ""))) {
                $this->form_validation->set_rules('registration_card', 'Registration card document', 'required');
            }
        }

        if (empty($this->input->post("admit_card_old"))) {
            if ((empty($this->input->post("admit_card_temp"))) && (($_FILES['admit_card']['name'] == ""))) {
                $this->form_validation->set_rules('admit_card', 'Admit card document', 'required');
            }
        }

        if ($dbrow->service_data->service_id == "AHSECCRC") {

            if (empty($this->input->post("other_doc_old"))) {
                if ((empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] != "") || (!empty($this->input->post("other_doc_temp"))))) {
                    $this->form_validation->set_rules('other_doc_type', 'Admit card type', 'required');
                }

                if ((!empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] == "") && (empty($this->input->post("other_doc_temp"))))) {
                    $this->form_validation->set_rules('other_doc', 'HS admit card', 'required');
                }
            }

            if (empty($this->input->post("affidavit_old"))) {
                if ((empty($this->input->post("affidavit_type"))) && (($_FILES['affidavit']['name'] != "") || (!empty($this->input->post("affidavit_temp"))))) {
                    $this->form_validation->set_rules('affidavit_type', 'Affidavit type', 'required');
                }

                if ((!empty($this->input->post("affidavit_type"))) && (($_FILES['affidavit']['name'] == "") && (empty($this->input->post("affidavit_temp"))))) {
                    $this->form_validation->set_rules('affidavit', 'Affidavit document', 'required');
                }
            }
        }


        if ($dbrow->service_data->service_id == "AHSECCPC") {
            $this->form_validation->set_rules('pass_certificate_type', 'Pass certificate type', 'required');
            if (empty($this->input->post("pass_certificate_old"))) {
                if ((empty($this->input->post("pass_certificate_temp"))) && (($_FILES['pass_certificate']['name'] == ""))) {
                    $this->form_validation->set_rules('pass_certificate', 'Pass certificate document', 'required');
                }
            }
        }

        if ($dbrow->service_data->service_id == "AHSECCPC" || $dbrow->service_data->service_id == "AHSECCMRK") {
            $this->form_validation->set_rules('marksheet_type', 'Marksheet type', 'required');
            if (empty($this->input->post("marksheet_old"))) {
                if ((empty($this->input->post("marksheet_type"))) && (($_FILES['marksheet']['name'] != "") || (!empty($this->input->post("marksheet_temp"))))) {
                    $this->form_validation->set_rules('marksheet_type', 'Marksheet type', 'required');
                }

                if ((!empty($this->input->post("marksheet_type"))) && (($_FILES['marksheet']['name'] == "") && (empty($this->input->post("marksheet_temp"))))) {
                    $this->form_validation->set_rules('marksheet', 'Marksheet', 'required');
                }
            }
        }

        // if (empty($this->input->post("soft_copy_old")) && $this->slug !== 'user') {
        //     if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {

        //         $this->form_validation->set_rules('soft_copy_type', 'Soft Copy Type required', 'required');
        //         $this->form_validation->set_rules('soft_copy', 'Soft Copy document required', 'required');
        //     }
        // }

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

        $registration_card = "";
        if ($_FILES['registration_card']['name'] != "") {
            $registrationCard = cifileupload("registration_card");
            $registration_card = $registrationCard["upload_status"] ? $registrationCard["uploaded_path"] : null;
        }
        if (!empty($this->input->post("registration_card_temp"))) {
            $registrationCard = movedigilockerfile($this->input->post('registration_card_temp'));
            $registration_card = $registrationCard["upload_status"] ? $registrationCard["uploaded_path"] : null;
        }

        $admit_card = "";
        if ($_FILES['admit_card']['name'] != "") {
            $admitUpload = cifileupload("admit_card");
            $admit_card = $admitUpload["upload_status"] ? $admitUpload["uploaded_path"] : null;
        }
        if (!empty($this->input->post("admit_card_temp"))) {
            $admitUpload = movedigilockerfile($this->input->post('admit_card_temp'));
            $admit_card = $admitUpload["upload_status"] ? $admitUpload["uploaded_path"] : null;
        }

        $affidavit = "";
        $other_doc = "";
        if ($dbrow->service_data->service_id == "AHSECCRC") {
            if ($_FILES['other_doc']['name'] != "") {
                $otherDoc = cifileupload("other_doc");
                $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;
            }
            if (!empty($this->input->post("other_doc_temp"))) {
                $otherDoc = movedigilockerfile($this->input->post('other_doc_temp'));
                $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;
            }

            if ($_FILES['affidavit']['name'] != "") {
                $affidavitUpload = cifileupload("affidavit");
                $affidavit = $affidavitUpload["upload_status"] ? $affidavitUpload["uploaded_path"] : null;
            }
            if (!empty($this->input->post("affidavit_temp"))) {
                $affidavitUpload = movedigilockerfile($this->input->post('affidavit_temp'));
                $affidavit = $affidavitUpload["upload_status"] ? $affidavitUpload["uploaded_path"] : null;
            }
        }

        $pass_certificate = "";
        if ($dbrow->service_data->service_id == "AHSECCPC") {
            if ($_FILES['pass_certificate']['name'] != "") {
                $passCertificateUpload = cifileupload("pass_certificate");
                $pass_certificate = $passCertificateUpload["upload_status"] ? $passCertificateUpload["uploaded_path"] : null;
            }
            if (!empty($this->input->post("pass_certificate_temp"))) {
                $passCertificateUpload = movedigilockerfile($this->input->post('pass_certificate_temp'));
                $pass_certificate = $passCertificateUpload["upload_status"] ? $passCertificateUpload["uploaded_path"] : null;
            }
        }

        $marksheet = "";
        if ($dbrow->service_data->service_id == "AHSECCPC" || $dbrow->service_data->service_id == "AHSECCMRK") {
            if ($_FILES['marksheet']['name'] != "") {
                $marksheetUpload = cifileupload("marksheet");
                $marksheet = $marksheetUpload["upload_status"] ? $marksheetUpload["uploaded_path"] : null;
            }
            if (!empty($this->input->post("marksheet_temp"))) {
                $marksheetUpload = movedigilockerfile($this->input->post('marksheet_temp'));
                $marksheet = $marksheetUpload["upload_status"] ? $marksheetUpload["uploaded_path"] : null;
            }
        }

        $uploadedFiles = array(
            "passport_photo_old" => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
            "signature_old" => strlen($signature) ? $signature : $this->input->post("signature_old"),
            "other_doc_old" => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
            "affidavit_old" => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
            "marksheet_old" => strlen($marksheet) ? $marksheet : $this->input->post("marksheet_old"),
            "pass_certificate_old" => strlen($pass_certificate) ? $pass_certificate : $this->input->post("pass_certificate_old"),
            "admit_card_old" => strlen($admit_card) ? $admit_card : $this->input->post("admit_card_old"),
            "registration_card_old" => strlen($registration_card) ? $registration_card : $this->input->post("registration_card_old"),
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.passport_photo_type' => $this->input->post("passport_photo_type"),
                'form_data.signature_type' => $this->input->post("signature_type"),
                'form_data.admit_card_type' => $this->input->post("admit_card_type"),
                'form_data.registration_card_type' => $this->input->post("registration_card_type"),

                'form_data.passport_photo' => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
                'form_data.signature' => strlen($signature) ? $signature : $this->input->post("signature_old"),
                'form_data.admit_card' => strlen($admit_card) ? $admit_card : $this->input->post("admit_card_old"),
                'form_data.registration_card' => strlen($registration_card) ? $registration_card : $this->input->post("registration_card_old"),
            );

            if ($dbrow->service_data->service_id == "AHSECCRC") {
                if (!empty($this->input->post("other_doc_type"))) {
                    $data["form_data.other_doc_type"] = $this->input->post("other_doc_type");
                    $data["form_data.other_doc"] = strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old");
                }

                if (!empty($this->input->post("affidavit_type"))) {
                    $data["form_data.affidavit_type"] = $this->input->post("affidavit_type");
                    $data["form_data.affidavit"] = strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old");
                }
            }

            if ($dbrow->service_data->service_id == "AHSECCPC" || $dbrow->service_data->service_id == "AHSECCMRK") {
                if (!empty($this->input->post("marksheet_type"))) {
                    $data["form_data.marksheet_type"] = $this->input->post("marksheet_type");
                    $data["form_data.marksheet"] = strlen($marksheet) ? $marksheet : $this->input->post("marksheet_old");
                }
            }

            if ($dbrow->service_data->service_id == "AHSECCPC") {
                if (!empty($this->input->post("pass_certificate_type"))) {
                    $data["form_data.pass_certificate_type"] = $this->input->post("pass_certificate_type");
                    $data["form_data.pass_certificate"] = strlen($pass_certificate) ? $pass_certificate : $this->input->post("pass_certificate_old");
                }
            }
            $this->ahsec_correction_model->update_where(['_id' => new ObjectId($objId)], $data);
            //$this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/ahsec_correction/ahseccor/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()

    public function preview($objId = null)
    {
        $dbRow = $this->ahsec_correction_model->get_by_doc_id($objId);
        //var_dump($dbRow); die;
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $dbRow->service_data->service_name,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('ahsec_correction/correctionpreview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/ahsec_correction/ahseccor/');
        } //End of if else
    } //End of preview()

    public function finalsubmition($obj = null)
    {
        if ($obj) {
            // pre($obj);
            $dbRow = $this->ahsec_correction_model->get_by_doc_id($obj);
            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }
            //procesing data
            $processing_history = $dbRow->processing_history ?? array();
            $processing_history[] = array(
                "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                "action_taken" => "Application Submition",
                "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            );

            $this->load->model('upms/users_model');
            $userFilter = array('user_services.service_code' => $dbRow->service_data->service_id, 'user_roles.role_code' => 'DA_AHSEC');
            $userRows = $this->users_model->get_rows($userFilter);
            //pre($userRows);
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

            // pre($current_users);

            $data_to_update = array(
                'service_data.appl_status' => 'submitted',
                'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                'current_users' => $current_users,
                'processing_history' => $processing_history
            );
            // pre($data_to_update);
            $this->ahsec_correction_model->update($obj, $data_to_update);
            //Sending submission SMS
            $nowTime = date('Y-m-d H:i:s');
            $sms = array(
                "mobile" => (int)$dbRow->form_data->mobile,
                "applicant_name" => $dbRow->form_data->applicant_name,
                "service_name" => $dbRow->service_data->service_name,
                "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                "app_ref_no" => $dbRow->service_data->appl_ref_no
            );
            sms_provider("submission", $sms);
            redirect('spservices/applications/acknowledgement/' . $obj);
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
        $dbRow = $this->ahsec_correction_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_data.service_name" => $dbRow->service_data->service_id,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('ahsec_correction/correctiontrack_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/ahsec_correction/ahseccor/');
        } //End of if else
    } //End of track()

    function getID($length)
    {
        $rtps_trans_id = $this->generateID($length);
        while ($this->ahsec_correction_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
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
        $str = "RTPS-" . $this->serviceId . "/" . $date . "/" . $number;
        return $str;
    } //End of generateID()

    public function queryform($objId = null)
    {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->ahsec_correction_model->get_row(array("_id" => new ObjectId($objId), "service_data.appl_status" => "QS"));
            if ($dbRow) {
                $data = array(
                    "dbrow" => $dbRow
                );
                $data["sessions"] = $this->fetchsessions(15);
                $data["years"] = $this->fetchyear(37);
                $this->load->view('includes/frontend/header');
                $this->load->view('ahsec_correction/ahsecquery_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/ahsec_correction/ahseccor');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/ahsec_correction/ahseccor');
        } //End of if else
    } //End of query()

    public function querysubmit()
    {
        $objId = $this->input->post("obj_id");
        // pre($objId);
        if (empty($objId)) {
            $this->my_transactions();
        }
        $dbrow = $this->ahsec_correction_model->get_by_doc_id($objId);
        if (count((array)$dbrow)) {
            $this->form_validation->set_rules('passport_photo_type', 'Passport photo type', 'required');
            $this->form_validation->set_rules('signature_type', 'Applicant signature', 'required');
            $this->form_validation->set_rules('registration_card_type', 'Registration card type', 'required');
            $this->form_validation->set_rules('admit_card_type', 'Admit card type', 'required');

            if (empty($this->input->post("registration_card_old"))) {
                if ((empty($this->input->post("registration_card_temp"))) && (($_FILES['registration_card']['name'] == ""))) {
                    $this->form_validation->set_rules('registration_card', 'Registration card document', 'required');
                }
            }

            if (empty($this->input->post("admit_card_old"))) {
                if ((empty($this->input->post("admit_card_temp"))) && (($_FILES['admit_card']['name'] == ""))) {
                    $this->form_validation->set_rules('admit_card', 'Admit card document', 'required');
                }
            }


            if ($dbrow->service_data->service_id == "AHSECCRC") {
                if (empty($this->input->post("other_doc_old"))) {
                    if ((empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] != "") || (!empty($this->input->post("other_doc_temp"))))) {
                        $this->form_validation->set_rules('other_doc_type', 'Admit card type', 'required');
                    }
    
                    if ((!empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] == "") && (empty($this->input->post("other_doc_temp"))))) {
                        $this->form_validation->set_rules('other_doc', 'HS admit card', 'required');
                    }
                }

                if (empty($this->input->post("affidavit_old"))) {
                    if ((empty($this->input->post("affidavit_type"))) && (($_FILES['affidavit']['name'] != "") || (!empty($this->input->post("affidavit_temp"))))) {
                        $this->form_validation->set_rules('affidavit_type', 'Affidavit type', 'required');
                    }

                    if ((!empty($this->input->post("affidavit_type"))) && (($_FILES['affidavit']['name'] == "") && (empty($this->input->post("affidavit_temp"))))) {
                        $this->form_validation->set_rules('affidavit', 'Affidavit document', 'required');
                    }
                }
            }



            if ($dbrow->service_data->service_id == "AHSECCPC") {
                $this->form_validation->set_rules('pass_certificate_type', 'Pass certificate type', 'required');
                if (empty($this->input->post("pass_certificate_old"))) {
                    if ((empty($this->input->post("pass_certificate_temp"))) && (($_FILES['pass_certificate']['name'] == ""))) {
                        $this->form_validation->set_rules('pass_certificate', 'Pass certificate document', 'required');
                    }
                }
            }

            if ($dbrow->service_data->service_id == "AHSECCPC" || $dbrow->service_data->service_id == "AHSECCMRK") {
                $this->form_validation->set_rules('marksheet_type', 'Marksheet type', 'required');
                if (empty($this->input->post("marksheet_old"))) {
                    if ((empty($this->input->post("marksheet_type"))) && (($_FILES['marksheet']['name'] != "") || (!empty($this->input->post("marksheet_temp"))))) {
                        $this->form_validation->set_rules('marksheet_type', 'Marksheet type', 'required');
                    }

                    if ((!empty($this->input->post("marksheet_type"))) && (($_FILES['marksheet']['name'] == "") && (empty($this->input->post("marksheet_temp"))))) {
                        $this->form_validation->set_rules('marksheet', 'Marksheet', 'required');
                    }
                }
            }

            // if (empty($this->input->post("soft_copy_old")) && $this->slug !== 'user') {
            //     if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {

            //         $this->form_validation->set_rules('soft_copy_type', 'Soft Copy Type required', 'required');
            //         $this->form_validation->set_rules('soft_copy', 'Soft Copy document required', 'required');
            //     }
            // }

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

            $registration_card = "";
            if ($_FILES['registration_card']['name'] != "") {
                $registrationCard = cifileupload("registration_card");
                $registration_card = $registrationCard["upload_status"] ? $registrationCard["uploaded_path"] : null;
            }
            if (!empty($this->input->post("registration_card_temp"))) {
                $registrationCard = movedigilockerfile($this->input->post('registration_card_temp'));
                $registration_card = $registrationCard["upload_status"] ? $registrationCard["uploaded_path"] : null;
            }

            $admit_card = "";
            if ($_FILES['admit_card']['name'] != "") {
                $admitUpload = cifileupload("admit_card");
                $admit_card = $admitUpload["upload_status"] ? $admitUpload["uploaded_path"] : null;
            }
            if (!empty($this->input->post("admit_card_temp"))) {
                $admitUpload = movedigilockerfile($this->input->post('admit_card_temp'));
                $admit_card = $admitUpload["upload_status"] ? $admitUpload["uploaded_path"] : null;
            }

            $affidavit = "";
            $other_doc = "";
            if ($dbrow->service_data->service_id == "AHSECCRC") {
                if ($_FILES['other_doc']['name'] != "") {
                    $otherDoc = cifileupload("other_doc");
                    $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;
                }
                if (!empty($this->input->post("other_doc_temp"))) {
                    $otherDoc = movedigilockerfile($this->input->post('other_doc_temp'));
                    $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;
                }

                if ($_FILES['affidavit']['name'] != "") {
                    $affidavitUpload = cifileupload("affidavit");
                    $affidavit = $affidavitUpload["upload_status"] ? $affidavitUpload["uploaded_path"] : null;
                }
                if (!empty($this->input->post("affidavit_temp"))) {
                    $affidavitUpload = movedigilockerfile($this->input->post('affidavit_temp'));
                    $affidavit = $affidavitUpload["upload_status"] ? $affidavitUpload["uploaded_path"] : null;
                }
            }

            $pass_certificate = "";
            if ($dbrow->service_data->service_id == "AHSECCPC") {
                if ($_FILES['pass_certificate']['name'] != "") {
                    $passCertificateUpload = cifileupload("pass_certificate");
                    $pass_certificate = $passCertificateUpload["upload_status"] ? $passCertificateUpload["uploaded_path"] : null;
                }
                if (!empty($this->input->post("pass_certificate_temp"))) {
                    $passCertificateUpload = movedigilockerfile($this->input->post('pass_certificate_temp'));
                    $pass_certificate = $passCertificateUpload["upload_status"] ? $passCertificateUpload["uploaded_path"] : null;
                }
            }

            $marksheet = "";
            if ($dbrow->service_data->service_id == "AHSECCPC" || $dbrow->service_data->service_id == "AHSECCMRK") {
                if ($_FILES['marksheet']['name'] != "") {
                    $marksheetUpload = cifileupload("marksheet");
                    $marksheet = $marksheetUpload["upload_status"] ? $marksheetUpload["uploaded_path"] : null;
                }
                if (!empty($this->input->post("marksheet_temp"))) {
                    $marksheetUpload = movedigilockerfile($this->input->post('marksheet_temp'));
                    $marksheet = $marksheetUpload["upload_status"] ? $marksheetUpload["uploaded_path"] : null;
                }
            }

            $uploadedFiles = array(
                "passport_photo_old" => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
                "signature_old" => strlen($signature) ? $signature : $this->input->post("signature_old"),
                "other_doc_old" => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
                "affidavit_old" => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
                "marksheet_old" => strlen($marksheet) ? $marksheet : $this->input->post("marksheet_old"),
                "pass_certificate_old" => strlen($pass_certificate) ? $pass_certificate : $this->input->post("pass_certificate_old"),
                "admit_card_old" => strlen($admit_card) ? $admit_card : $this->input->post("admit_card_old"),
                "registration_card_old" => strlen($registration_card) ? $registration_card : $this->input->post("registration_card_old"),
            );

            if (empty($uploadedFiles['passport_photo_old']) || empty($uploadedFiles['signature_old']) || empty($uploadedFiles['admit_card_old']) || empty($uploadedFiles['registration_card_old'])) {
                //$this->session->set_flashdata('uploaded_files', $uploadedFiles);
                $this->session->set_flashdata('fail', 'Some filetype you are attempting to upload is not allowed.!');
                $this->fileuploads($objId);
                return;
            }

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $this->queryform($objId);
            } else {

                $data = array(
                    'form_data.passport_photo_type' => $this->input->post("passport_photo_type"),
                    'form_data.signature_type' => $this->input->post("signature_type"),
                    'form_data.admit_card_type' => $this->input->post("admit_card_type"),
                    'form_data.registration_card_type' => $this->input->post("registration_card_type"),

                    'form_data.passport_photo' => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
                    'form_data.signature' => strlen($signature) ? $signature : $this->input->post("signature_old"),
                    'form_data.admit_card' => strlen($admit_card) ? $admit_card : $this->input->post("admit_card_old"),
                    'form_data.registration_card' => strlen($registration_card) ? $registration_card : $this->input->post("registration_card_old"),
                );

                if ($dbrow->service_data->service_id == "AHSECCRC") {
                    if (!empty($this->input->post("other_doc_type"))) {
                        $data["form_data.other_doc_type"] = $this->input->post("other_doc_type");
                        $data["form_data.other_doc"] = strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old");
                    }
                    if (!empty($this->input->post("affidavit_type"))) {
                        $data["form_data.affidavit_type"] = $this->input->post("affidavit_type");
                        $data["form_data.affidavit"] = strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old");
                    }
                }

                if ($dbrow->service_data->service_id == "AHSECCPC" || $dbrow->service_data->service_id == "AHSECCMRK") {
                    if (!empty($this->input->post("marksheet_type"))) {
                        $data["form_data.marksheet_type"] = $this->input->post("marksheet_type");
                        $data["form_data.marksheet"] = strlen($marksheet) ? $marksheet : $this->input->post("marksheet_old");
                    }
                }

                if ($dbrow->service_data->service_id == "AHSECCPC") {
                    if (!empty($this->input->post("pass_certificate_type"))) {
                        $data["form_data.pass_certificate_type"] = $this->input->post("pass_certificate_type");
                        $data["form_data.pass_certificate"] = strlen($pass_certificate) ? $pass_certificate : $this->input->post("pass_certificate_old");
                    }
                }

                $processing_history = $dbrow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Query submitted by " . $dbrow->form_data->applicant_name,
                    "action_taken" => "Query submitted",
                    "remarks" => "Query submitted by " . $dbrow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
                $data["service_data.appl_status"] = "QA";
                $data["status"] = "QUERY_ANSWERED";
                $data["processing_history"] = $processing_history;
                $this->ahsec_correction_model->update_where(['_id' => new ObjectId($objId)], $data);
                $this->session->set_flashdata('success', 'Your application has been successfully updated.');
                redirect('spservices/ahsec_correction/ahseccor/preview/' . $objId);
            } //End of if else
        } else {
            $this->session->set_flashdata('fail', 'Unable to update data, please try again!');
            $this->queryform($objId);
        }
    } //End of querysubmit()

    public function submit_to_backend($obj, $show_ack = false)
    {
        if ($obj) {
            $dbRow = $this->ahsec_correction_model->get_by_doc_id($obj);

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
                $userFilter = array('user_services.service_code' => $dbRow->service_data->service_id, 'user_roles.role_code' => 'DA_AHSEC');
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

                // pre($current_users);

                $data_to_update = array(
                    'service_data.appl_status' => 'submitted',
                    'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'current_users' => $current_users,
                    'processing_history' => $processing_history
                );
                // pre($data_to_update);
                $this->ahsec_correction_model->update($obj, $data_to_update);
                //Sending submission SMS
                $nowTime = date('Y-m-d H:i:s');
                $sms = array(
                    "mobile" => (int)$dbRow->form_data->mobile,
                    "applicant_name" => $dbRow->form_data->applicant_name,
                    "service_name" => $dbRow->service_data->service_name,
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
        redirect('iservices/transactions');
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
        $dbRow = $this->ahsec_correction_model->get_by_doc_id($objId);
        //var_dump($dbRow); die;
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $dbRow->service_data->service_name,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('ahsec_correction/view_form_data', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/ahsec_correction/ahseccor/');
        } //End of if else
    } //End of preview()

    public function download_certificate($objId = null)
    {
        if (!empty($objId)) {
            $dbRowArray = array();
            $dbRow = $this->ahsec_correction_model->get_by_doc_id($objId);
            $dbRowArray[] = $dbRow;
            $qrcode_path = 'storage/docs/ahsec_common_qr/';
            $filename = str_replace("/", "-", $objId);
            $qrname = $filename . ".png";
            $file_name = $qrcode_path . $qrname;

            $filter1 = array(
                "registration_number" => $dbRow->form_data->ahsec_reg_no,
                "registration_session" => $dbRow->form_data->ahsec_reg_session,
            );
            $ahsecregistration_data = $this->ahsecregistration_model->get_row($filter1);
            if ($dbRow->service_data->service_id == "AHSECCADM" || $dbRow->service_data->service_id == "AHSECCMRK" || $dbRow->service_data->service_id == "AHSECCPC") {
                $filter2 = array(
                    "Registration_No" => $dbRow->form_data->ahsec_reg_no,
                    "Registration_Session" => $dbRow->form_data->ahsec_reg_session,
                    "Roll" => $dbRow->form_data->ahsec_admit_roll,
                    "No" => $dbRow->form_data->ahsec_admit_no,
                );
                $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);

                $marksheet_data['Mark_Sheet_No'] = $ahsecmarksheet_data->Mark_Sheet_No;
                $marksheet_data['Stream'] = $ahsecmarksheet_data->Stream;
                $marksheet_data['Date'] = $ahsecmarksheet_data->Marksheet_Date;
                $marksheet_data['Candidate_Name'] = $ahsecmarksheet_data->Candidate_Name;
                $marksheet_data['School_Name'] = $ahsecmarksheet_data->School_College_Name;
                $marksheet_data['Roll'] = $ahsecmarksheet_data->Roll;
                $marksheet_data['No'] = $ahsecmarksheet_data->No;
                $marksheet_data['Remarks1'] = $ahsecmarksheet_data->Remarks1;
                $marksheet_data['Year_of_Examination'] = $ahsecmarksheet_data->Year_of_Examination;
                $marksheet_data['commencing_day'] = $ahsecmarksheet_data->commencing_day ?? '';
                $marksheet_data['commencing_month'] = $ahsecmarksheet_data->commencing_month ?? '';
                $j = 1;
                for ($i = 1; $i <= 16; $i++) {
                    if (!empty($ahsecmarksheet_data->{'Sub' . $i . '_Code'})) {
                        $marksheet_data['Sub' . $j . '_Code'] = $ahsecmarksheet_data->{'Sub' . $i . '_Code'};
                        $marksheet_data['Sub' . $j . '_Pap_Indicator'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pap_Indicator'};
                        $marksheet_data['Sub' . $j . '_Name'] = $ahsecmarksheet_data->{'Sub' . $i . '_Name'};
                        $marksheet_data['Sub' . $j . '_Th_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Th_Marks'};
                        $marksheet_data['Sub' . $j . '_Pr_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pr_Marks'};
                        $marksheet_data['Sub' . $j . '_Tot_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Tot_Marks'};
                        $j++;
                    }
                }
                $marksheet_data['total_sub'] = --$j;
                $marksheet_data['Total_Marks_in_Words'] = $ahsecmarksheet_data->Total_Marks_in_Words;
                $marksheet_data['Total_Marks_in_Figure'] = $ahsecmarksheet_data->Total_Marks_in_Figure;
                $marksheet_data['Total_Grace_in_Figure'] = $ahsecmarksheet_data->Total_Grace_in_Figure;
                $marksheet_data['Result'] = $ahsecmarksheet_data->Result;
                $marksheet_data['Remarks2'] = $ahsecmarksheet_data->Remarks2;
                $marksheet_data['ENVE_Grade'] = $ahsecmarksheet_data->ENVE_Grade;
                $marksheet_data['Core_Indicator'] = $ahsecmarksheet_data->Core_Indicator;
            }
            if ((($dbRowArray[0]->service_data->service_id == 'AHSECCRC') || ($dbRowArray[0]->service_data->service_id == 'AHSECCADM') || ($dbRowArray[0]->service_data->service_id == 'AHSECCMRK') || ($dbRowArray[0]->service_data->service_id == 'AHSECCPC')) && $dbRowArray[0]->service_data->appl_status == "D") {
                $this->load->view('includes/frontend/header');
                if ($dbRowArray[0]->service_data->service_id == 'AHSECCRC') {
                    $this->load->view('ahsec_correction/output_certificate_rc', array('data' => $dbRowArray, 'qr' => $file_name, 'certificate_no' => $dbRowArray[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'user_type' => 'citizen'));
                } else if ($dbRowArray[0]->service_data->service_id == 'AHSECCADM') {
                    $this->load->view('ahsec_correction/output_certificate_adm', array('data' => $dbRowArray, 'qr' => $file_name, 'certificate_no' => $dbRowArray[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'citizen'));
                } else if ($dbRowArray[0]->service_data->service_id == 'AHSECCMRK') {
                    $this->load->view('ahsec_correction/output_certificate_mrk', array('data' => $dbRowArray, 'qr' => $file_name, 'certificate_no' => $dbRowArray[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'citizen'));
                } else if ($dbRowArray[0]->service_data->service_id == 'AHSECCPC') {
                    $this->load->view('ahsec_correction/output_certificate_pc', array('data' => $dbRowArray, 'qr' => $file_name, 'certificate_no' => $dbRowArray[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'citizen'));
                }
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('errmessage', 'Something went wrong!, Please try later.');
                $this->my_transactions();
            }
        }
    } //End of download_certificate()

    public function searchdetails($obj_id = null)
    {
        //check_application_count_for_citizen(); 
        if ($obj_id == "AHSECCRC") {
            $data = array("pageTitleId" => "AHSECCRC", "pageTitle" => "Application for Correction in Registration Card");
            $data["dbrow"] = null;
        } else if ($obj_id == "AHSECCADM") {
            $data = array("pageTitleId" => "AHSECCADM", "pageTitle" => "Application for Correction in Admit Card");
            $data["dbrow"] = null;
        } else if ($obj_id == "AHSECCMRK") {
            $data = array("pageTitleId" => "AHSECCMRK", "pageTitle" => "Application for Correction in Marksheet");
            $data["dbrow"] = null;
        } else if ($obj_id == "AHSECCPC") {
            $data = array("pageTitleId" => "AHSECCPC", "pageTitle" => "Application for Correction in Pass Certificate");
            $data["dbrow"] = null;
        } else {
            $this->my_transactions();
        }
        $data['usser_type'] = $this->slug;
        $data["sessions"] = $this->fetchsessions(15);
        $data["years"] = $this->fetchyear(37);
        $this->load->view('includes/frontend/header');
        $this->load->view('ahsec_correction/search_details', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function searchdetails_submit()
    {
        $this->serviceName = $this->input->post("service_name");
        $this->serviceId = $this->input->post("service_id");
        $submissionMode = $this->input->post("submission_mode");
        $this->form_validation->set_rules('ahsec_reg_session', 'AHSEC reg. session', 'trim|required|xss_clean|max_length[255]');
        $this->form_validation->set_rules('ahsec_reg_no', 'AHSEC reg. no', 'trim|required|xss_clean|strip_tags');
        if ($this->serviceId != "AHSECCRC" || ($this->serviceId == "AHSECCRC" && $this->input->post("ahsec_yearofappearing") != "Not yet appeared")) {
            $this->form_validation->set_rules('ahsec_admit_roll', 'AHSEC admit roll', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('ahsec_admit_no', 'AHSEC admit no', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('ahsec_yearofappearing', 'Final examination appearing/passing year', 'trim|required|xss_clean|strip_tags');
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->searchdetails($this->serviceId);
        } else {
            $reg_data_count = 0;
            $filter1 = array(
                "registration_number" => (int) $this->input->post("ahsec_reg_no"),
                "registration_session" => $this->input->post("ahsec_reg_session"),
            );
            $ahsecregistration_data = $this->ahsecregistration_model->get_row($filter1);
            $reg_data_count = count((array) $this->ahsecregistration_model->get_rows($filter1));

            if ($reg_data_count > 1) {
                $this->session->set_flashdata('error', 'We have found multiple records! Unable to verify');
                $this->searchdetails($this->serviceId);
            }

            $ahsecmarksheet_data = "";
            $marksheet_data_count = 0;
            if ((!empty($this->input->post("ahsec_admit_roll"))) &&  (!empty($this->input->post("ahsec_admit_no"))) && $this->serviceId != "AHSECCRC") {
                $filter2 = array(
                    "Registration_No" => (int) $this->input->post("ahsec_reg_no"),
                    "Registration_Session" => $this->input->post("ahsec_reg_session"),
                    "Roll" => (int) $this->input->post("ahsec_admit_roll"),
                    "No" => (int) $this->input->post("ahsec_admit_no"),
                    "Year_of_Examination" => (int) $this->input->post("ahsec_yearofappearing"),
                );
                $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);
                $marksheet_data_count = count((array) $this->ahsecmarksheet_model->get_rows($filter2));

                if ($marksheet_data_count > 1) {
                    $this->session->set_flashdata('error', 'We have found multiple records! Unable to verify');
                    $this->searchdetails($this->serviceId);
                }
            }

            $Roll = $this->input->post("ahsec_admit_roll") ?? '';
            $No = $this->input->post("ahsec_admit_no") ?? '';
            $Year_of_Examination = $this->input->post("ahsec_yearofappearing") ?? '';

            // if (((!empty($ahsecregistration_data)) && (!empty($ahsecmarksheet_data)) && (($this->serviceId != "AHSECCRC"))) || ((!empty($ahsecregistration_data)) && (empty($ahsecmarksheet_data)) && (($this->serviceId == "AHSECCRC")))) {

            if ((!empty($ahsecregistration_data) && !empty($ahsecmarksheet_data) && $this->serviceId != "AHSECCRC") || (!empty($ahsecregistration_data) && $this->serviceId == "AHSECCRC")) {
                //If already record submitted
                $filter = array(
                    "form_data.ahsec_reg_session" => $this->input->post("ahsec_reg_session"),
                    "form_data.ahsec_reg_no" => (int) $this->input->post("ahsec_reg_no"),
                    "service_data.appl_status" => "DRAFT",
                    "service_data.service_id" => $this->serviceId,
                );

                //if ($this->serviceId != "AHSECCRC") {
                $filter["form_data.ahsec_admit_roll"] = (int) $this->input->post("ahsec_admit_roll") ?? '';
                $filter["form_data.ahsec_admit_no"] = (int) $this->input->post("ahsec_admit_no") ?? '';
                $filter["form_data.ahsec_yearofappearing"] = (int) $this->input->post("ahsec_yearofappearing");
                //}



                $dbrow_data = $this->ahsec_correction_model->get_row($filter);

                if (!empty($dbrow_data)) {
                    $objectId = $dbrow_data->_id->{'$id'};
                    $this->session->set_flashdata('success', 'Your data has been verified successfully.!');
                    redirect('spservices/ahsec_correction/ahseccor/index/' . $objectId);
                }
                //////////////
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
                    $rows = $this->ahsec_correction_model->get_row($filter);

                    if ($rows == false)
                        break;
                }

                $service_data = [
                    "department_id" => "5555",
                    "department_name" => "ASSAM HIGHER SECONDARY EDUCATION COUNCIL",
                    "service_id" => $this->serviceId,
                    "service_name" => $this->serviceName,
                    "appl_id" => $app_id,
                    "appl_ref_no" => $appl_ref_no,
                    "submission_mode" => $submissionMode, //kiosk, online, in-person
                    "applied_by" => $apply_by,
                    "submission_location" => "ASSAM HIGHER SECONDARY EDUCATION COUNCIL", // office name
                    "submission_date" => "",
                    "service_timeline" => "7 Days",
                    "appl_status" => "DRAFT",
                ];

                $form_data = [
                    'applicant_name' => $ahsecregistration_data->candidate_name,
                    'father_name' => $ahsecregistration_data->father_name,
                    'mother_name' => $ahsecregistration_data->mother_name,
                    'institution_name' => $ahsecregistration_data->institution_name,
                    'mobile' => $this->session->mobile,

                    'ahsec_reg_session' => $ahsecregistration_data->registration_session,
                    'ahsec_reg_no' => $ahsecregistration_data->registration_number,
                    'ahsec_admit_roll' => ($this->serviceId != "AHSECCRC") ? $ahsecmarksheet_data->Roll : $Roll,
                    'ahsec_admit_no' => ($this->serviceId != "AHSECCRC") ? $ahsecmarksheet_data->No : $No,
                    'ahsec_yearofappearing' => ($this->serviceId != "AHSECCRC") ? $ahsecmarksheet_data->Year_of_Examination : $Year_of_Examination,

                    'user_id' => $sessionUser['userId']->{'$id'},
                    'user_type' => $this->slug,
                    'created_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                ];

                $inputs = [
                    'service_data' => $service_data,
                    'form_data' => $form_data,
                ];
                $insert = $this->ahsec_correction_model->insert($inputs);

                // pre($insert);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $this->session->set_flashdata('success', 'Your data has been verified successfully.!');
                    redirect('spservices/ahsec_correction/ahseccor/index/' . $objectId);
                } else {
                    $this->session->set_flashdata('fail', 'Something went wrong.! Please try again.');
                    $this->searchdetails($this->serviceId);
                }
            } else {
                $this->session->set_flashdata('error', 'No record found.');
                $this->searchdetails($this->serviceId);
                // redirect('spservices/duplicatecertificateahsec/registration/searchdetails/' . $service_id);
            }
        }
    }

    public function fetchsessions($no_of_year)
    {
        $currentYear = date('Y');
        $yearArray = array();
        for ($i = $currentYear; $i >= ($currentYear - $no_of_year); $i--) {
            $present_year = $i;
            $next_year = sprintf("%02d", (substr($i, -2) + 1));
            $yearArray[$i . '-' . ($next_year)] = $i . '-' . ($next_year);
        }
        return $yearArray;
    }

    public function fetchyear($no_of_year)
    {
        $currentYear = date('Y');
        $yearArray = array();
        for ($i = $currentYear; $i >= ($currentYear - $no_of_year); $i--) {
            $yearArray[$i] = $i;
        }
        return $yearArray;
    }

    public function appprivewforbackend($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array(
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
            );

            $this->load->view('includes/frontend/header');
            $this->load->view('ahsec_correction/dc_app_admin_pre', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/ahsec_correction/ahseccor');
        } //End of if else
    }
}//End of Castecertificate
