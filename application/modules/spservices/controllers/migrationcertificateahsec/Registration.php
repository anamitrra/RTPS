<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps
{
    private $serviceName = "Application for Migration Certificate";
    private $serviceId = "AHSECMIGR";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('applications_model');
        $this->load->model('services_model');

        $this->load->model('migrationcertificateahsec/registration_model');
        $this->load->model('duplicatecertificateahsec/ahsecregistration_model');
        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->helper('smsprovider');
        $this->load->helper('log');
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
    } //End of __construct()

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
    public function index($obj_id = null)
    {
        if ($obj_id == null) {
            $this->my_transactions();
        } else {
            $data = array("pageTitle" => "Application for Migration Certificate",
                "PageTiteAssamese" => "প্ৰব্ৰজন প্ৰমাণপত্ৰৰ বাবে আবেদন");
            $filter = array(
                "_id" => new ObjectId($obj_id),
                "service_data.appl_status" => "DRAFT",
            );
            $data["dbrow"] = $this->registration_model->get_row($filter);
            $data['usser_type'] = $this->slug;
            $data["states"] = $this->registration_model->getStates();
            $data["districts"] = []; //$this->registration_model->getDistricts();
            $data["sessions"] = $this->fetchsessions(15);
            $data["years"] = $this->fetchyear(38);

            // pre($data["dbrow"]);
            $this->load->view('includes/frontend/header');
            $this->load->view('migrationcertificateahsec/section_one_create', $data);
            $this->load->view('includes/frontend/footer');
        }

    } //End of index()

    public function searchdetails()
    {
        //check_application_count_for_citizen();
        $data = array("pageTitle" => "Application for Migration Certificate",
            "PageTiteAssamese" => "প্ৰব্ৰজন প্ৰমাণপত্ৰৰ বাবে আবেদন",
            // "sessions"=>$sessions
        );
        $data["sessions"] = $this->fetchsessions(38);
        $data["years"] = $this->fetchyear(38);

        $data['usser_type'] = $this->slug;
        // pre($data);
        $this->load->view('includes/frontend/header');
        $this->load->view('migrationcertificateahsec/search_details', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function searchdetails_submit()
    {
        $this->form_validation->set_rules('ahsec_reg_session', 'AHSEC reg. session', 'trim|required|xss_clean|max_length[255]');
        $this->form_validation->set_rules('ahsec_reg_no', 'AHSEC reg. no', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ahsec_yearofpassing', 'Year of Appearing H.S. Final Year Examination', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->searchdetails();
        } else {

            $lower_limit = "2004-05";
            $upper_limit = "2014-15";
            $ahsecregistration_data = "";
            $reg_data_count = 0;

            if (($this->input->post("ahsec_reg_session") >= $lower_limit) && ($this->input->post("ahsec_reg_session") <= $upper_limit)) {
                $filter1 = array(
                    "registration_number" => (int) $this->input->post("ahsec_reg_no"),
                    "registration_session" => $this->input->post("ahsec_reg_session"),
                );
                $ahsecregistration_data = $this->ahsecregistration_model->get_row($filter1);
                $reg_data_count = $this->ahsecregistration_model->rows_count($filter1);

                if ($reg_data_count > 1) {
                    $this->session->set_flashdata('error', 'We have found multiple records! Unable to verify');
                    $this->searchdetails($service_id);
                }
            }

            //////// For Migration - An applicant can apply for migration certificate only once..
            $filter45 = array(
                "form_data.ahsec_reg_session" => $this->input->post("ahsec_reg_session"),
                "form_data.ahsec_reg_no" => (int) $this->input->post("ahsec_reg_no"),
                "service_data.service_id" => "AHSECMIGR",
            );

            $dbrow_mig_data45_cnt = $this->registration_model->rows_count($filter45);
            if ($dbrow_mig_data45_cnt > 0) {
                $this->session->set_flashdata('error', 'You have already applied for migration certificate. Migration Certificate can apply once. !!');
                redirect('spservices/migrationcertificateahsec/registration/searchdetails/');
                exit();
            }
            ///////

            //If already record submitted
            $filter = array(
                "form_data.ahsec_reg_session" => $this->input->post("ahsec_reg_session"),
                "form_data.ahsec_reg_no" => (int) $this->input->post("ahsec_reg_no"),
                "service_data.service_id" => $this->serviceId,
                "service_data.appl_status" => "DRAFT",
                // "form_data.ahsec_admit_roll" => (int) $this->input->post("ahsec_admit_roll"),
                // "form_data.ahsec_admit_no" => (int) $this->input->post("ahsec_admit_no")
            );

            $dbrow_data = $this->registration_model->get_row($filter);

            if (!empty($dbrow_data)) {
                $objectId = $dbrow_data->_id->{'$id'};
                $this->session->set_flashdata('success', 'Your data has been verified successfully!!');
                redirect('spservices/migrationcertificateahsec/registration/index/' . $objectId);
            }
            //////////////
            $appl_ref_no = $this->getID(7, $service_id);
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
                $rows = $this->registration_model->get_row($filter);

                if ($rows == false) {
                    break;
                }
            }

            $submissionMode = $this->input->post("submission_mode");

            //Fetch old data if applicant already apply for a service
            $filter = array(
                "form_data.ahsec_reg_session" => $this->input->post("ahsec_reg_session"),
                "form_data.ahsec_reg_no" => (int) $this->input->post("ahsec_reg_no"),
            );
            $dbrow_data = $this->registration_model->get_row($filter);
            // End

            $service_data = [
                "department_id" => "5555",
                "department_name" => "ASSAM HIGHER SECONDARY EDUCATION COUNCIL",
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => "ASSAM HIGHER SECONDARY EDUCATION COUNCIL",
                "submission_date" => "",
                "service_timeline" => "5",
                "appl_status" => "DRAFT",
                "district" => "KAMRUP METRO"
            ];

            $form_data = [
                'applicant_name' => $ahsecregistration_data->candidate_name ?? '',
                'father_name' => $ahsecregistration_data->father_name ?? '',
                'mother_name' => $ahsecregistration_data->mother_name ?? '',
                'applicant_gender' => $dbrow_data->form_data->applicant_gender ?? "",
                'mobile' => $this->session->mobile,

                'comp_permanent_address' => $dbrow_data->form_data->comp_permanent_address ?? "",
                'pa_state' => $dbrow_data->form_data->pa_state ?? "",
                'pa_district' => $dbrow_data->form_data->pa_district ?? "",
                'pa_pincode' => $dbrow_data->form_data->pa_pincode ?? "",

                'comp_postal_address' => $dbrow_data->form_data->comp_postal_address ?? "",
                'pos_state' => $dbrow_data->form_data->pos_state ?? "",
                'pos_district' => $dbrow_data->form_data->pos_district ?? "",
                'pos_pincode' => $dbrow_data->form_data->pos_pincode ?? "",

                'ahsec_yearofpassing' => $this->input->post("ahsec_yearofpassing") ?? '',
                'ahsec_reg_session' => $ahsecregistration_data->registration_session ?? $this->input->post("ahsec_reg_session"),
                'ahsec_reg_no' => $ahsecregistration_data->registration_number ?? (int) $this->input->post("ahsec_reg_no"),
                'ahsec_inst_name' => $ahsecregistration_data->institution_name ?? '',
                'user_id' => $sessionUser['userId']->{'$id'},
                'user_type' => $this->slug,
                'created_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            ];
            $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data,
            ];
            $insert = $this->registration_model->insert($inputs);

            // pre($insert);
            if ($insert) {
                $objectId = $insert['_id']->{'$id'};
                $this->session->set_flashdata('success', 'Your data has been verified successfully!!');
                redirect('spservices/migrationcertificateahsec/registration/index/' . $objectId);
            } else {
                $this->session->set_flashdata('fail', 'Something went wrong. Please try again.');
                $this->searchdetails();
            }

        }
    }

    public function saveSectionOne($data)
    {
        $objId = $data['obj_id'];
        $sessionUser = $this->session->userdata();
        if ($this->slug === "CSC") {
            $apply_by = $sessionUser['userId'];
        } else {
            $filter = array(
                "_id" => new ObjectId($objId),
                "service_data.appl_status" => "DRAFT",
            );

            $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        } //End of if else

        if (strlen($objId)) {

            $dbrow = $this->registration_model->get_row($filter);
            $reg_no = $dbrow->form_data->ahsec_reg_no;

            $form_data = [
                'form_data.applicant_name' => $data['applicant_name'],
                'form_data.father_name' => $data['father_name'],
                'form_data.mother_name' => $data['mother_name'],
                'form_data.mobile' => $data['mobile'],
                'form_data.email' => $data['email'],
                'form_data.applicant_gender' => $data['applicant_gender'],

                'form_data.comp_permanent_address' => $data['comp_permanent_address'],
                'form_data.pa_pincode' => $data['pa_pincode'],
                'form_data.pa_state' => $data['pa_state'],
                'form_data.pa_district' => $data['pa_district'],

                'form_data.comp_postal_address' => $data['comp_postal_address'],
                'form_data.pos_pincode' => $data['pos_pincode'],
                'form_data.pos_state' => $data['pos_state'],
                'form_data.pos_district' => $data['pos_district'],

                'form_data.ahsec_reg_session' => $data['ahsec_reg_session'],
                'form_data.ahsec_reg_no' => $reg_no ?? $data['ahsec_reg_no'],
                'form_data.ahsec_yearofpassing' => $data['ahsec_yearofpassing'],
                'form_data.ahsec_admit_roll' => $data['ahsec_admit_roll'] ?? '',
                'form_data.ahsec_admit_no' => $data['ahsec_admit_no'] ?? '',
                'form_data.ahsec_inst_name' => $data['ahsec_inst_name'],

                'form_data.user_id' => $sessionUser['userId']->{'$id'},
                'form_data.user_type' => $this->slug,
                'form_data.updated_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            ];

            $this->registration_model->update_where(['_id' => new ObjectId($objId)], $form_data);
            return $objId;

        } else {
            $this->index($objId);
        } //End of if else
    }

    public function saveSectionTwo($data)
    {
        $objId = $data['obj_id'];
        $form_data = [
            'form_data.board_seaking_adm' => $data['board_seaking_adm'],
            'form_data.course_seaking_adm' => $data['course_seaking_adm'],
            'form_data.state_seaking_adm' => $data['state_seaking_adm'],
            'form_data.reason_seaking_adm' => $data['reason_seaking_adm'],
            'form_data.postal' => $data['postal'],
            'form_data.ahsec_country_seeking' => $data['ahsec_country_seeking'],

        ];
        $this->registration_model->update_where(['_id' => new ObjectId($objId)], $form_data);
    }
    public function submit()
    {
        if ($this->input->post("step") == 2) {

            $this->form_validation->set_rules('board_seaking_adm', 'Board Seeking Admission', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('course_seaking_adm', 'Course Seeking Admission', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            // $this->form_validation->set_rules('state_seaking_adm', 'State Seeking Admission', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('reason_seaking_adm', 'Describe Reason for Seeking Migration', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('postal', 'Delivery Preference', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

            $objId = $this->input->post("obj_id");

            if ($this->input->post('state_seaking_adm') != null && $this->input->post('ahsec_country_seeking') != null) {
                $this->session->set_flashdata('error', 'State and Country can not be selected At once!!');
                $obj_id = strlen($objId) ? $objId : null;
                redirect($_SERVER['HTTP_REFERER']);
            }

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $obj_id = strlen($objId) ? $objId : null;
                $this->sectionTwo($obj_id);
            } else {

                $this->saveSectionTwo($this->input->post());
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/migrationcertificateahsec/registration/sectionThree/' . $objId);
            }

        } else {

            $objId = $this->input->post("obj_id");
            // $appl_ref_no = $this->input->post("appl_ref_no");
            // $submissionMode = $this->input->post("submission_mode");
            $this->form_validation->set_rules('applicant_name', 'Applicant name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            // $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean|strip_tags');

            $this->form_validation->set_rules('father_name', 'Father Name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('mother_name', 'Mother name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|strip_tags|valid_email');
            $this->form_validation->set_rules('comp_permanent_address', 'Complete Permanent Address', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('pa_pincode', 'Pincode', 'trim|required|xss_clean|strip_tags|max_length[6]');
            $this->form_validation->set_rules('pa_state', 'State', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('pa_district', 'District', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('applicant_gender', 'Applicant Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');

            $this->form_validation->set_rules('ahsec_inst_name', 'AHSEC Board', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('ahsec_reg_session', 'AHSEC Registrtion Session', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('ahsec_reg_session', 'AHSEC Registrtion Session', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('ahsec_reg_no', 'Valid AHSEC Registrtion Number', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('ahsec_yearofpassing', 'Years of Passing of H.S. 2nd Year Examination', 'trim|required|xss_clean|strip_tags');
            // $this->form_validation->set_rules('ahsec_admit_roll', 'H.S. 2nd Year Admit Roll', 'trim|required|xss_clean|strip_tags|max_length[255]');
            // $this->form_validation->set_rules('ahsec_admit_no', 'H.S. 2nd Year Admit Number', 'trim|required|xss_clean|strip_tags|max_length[255]');

            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $obj_id = strlen($objId) ? $objId : null;
                $this->index($obj_id);
            } else {

                $objId = $this->saveSectionOne($this->input->post());

                if (strlen($objId)) {
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    redirect('spservices/migrationcertificateahsec/registration/sectionTwo/' . $objId);
                } else {
                    $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                    $this->index();
                    exit;
                }

            } //End of if else

        }
    } //End of submit()

    public function sectionTwo($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);

        if (count((array) $dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "pageTitle" => "Application for Migration Certificate",
                "PageTiteAssamese" => "প্ৰব্ৰজন প্ৰমাণপত্ৰৰ বাবে আবেদন",
                "obj_id" => $objId,
                "dbrow" => $dbRow,
                "states" => $this->registration_model->getStates(),
                "countries" => $this->registration_model->getCountries(),
            );

            $this->load->view('includes/frontend/header');
            $this->load->view('migrationcertificateahsec/section_two_create', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/migrationcertificateahsec/registration');
        } //End of if else
    } //End of sectionTwo()

    public function sectionThree($objId = null)
    {
        $filter = array(
            "_id" => new ObjectId($objId),
            "service_data.appl_status" => "DRAFT",
        );

        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "pageTitle" => "Application for Migration Certificate",
                "PageTiteAssamese" => "প্ৰব্ৰজন প্ৰমাণপত্ৰৰ বাবে আবেদন",
                "obj_id" => $objId,
                "dbrow" => $dbRow,
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('migrationcertificateahsec/section_three_create', $data);
            $this->load->view('includes/frontend/footer');

        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/migrationcertificateahsec/registration');
        } //End of if else
    } //End of sectionThree()

    public function submitfiles()
    {
        $objId = $this->input->post("obj_id");
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (empty($objId)) {
            $this->my_transactions();
        }
        $this->form_validation->set_rules('hs_marksheet_type', 'H.S. Final Year Marksheet Type', 'required');
        $this->form_validation->set_rules('hs_reg_card_type', 'H.S. Registration Card Type', 'required');
        $this->form_validation->set_rules('photo_of_the_candidate_type', 'Photo of the Candidate Type', 'required');
        $this->form_validation->set_rules('candidate_sign_type', 'Signature of the Candidate Type', 'required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $photo_of_candidate = '';
        $sign_of_candidate = '';
        $hs_registration_card = '';
        $hs_marksheet_card = '';

        $reg_card = isset($dbRow->form_data->hs_reg_card) ? $dbRow->form_data->hs_reg_card : '';
        $marksheet = isset($dbRow->form_data->hs_marksheet) ? $dbRow->form_data->hs_marksheet : '';
        $sign_of_candidate = isset($dbRow->form_data->candidate_sign) ? $dbRow->form_data->candidate_sign : '';

        if (empty($this->input->post("hs_reg_card_old"))) {
            if (((empty($this->input->post("hs_reg_card_type"))) && (($_FILES['hs_reg_card']['name'] != "") || (!empty($this->input->post("hs_reg_card_temp"))))) || ((!empty($this->input->post("hs_reg_card_type"))) && (($_FILES['hs_reg_card']['name'] == "") && (empty($this->input->post("hs_reg_card_temp")))))) {

                $this->form_validation->set_rules('hs_reg_card', 'HS Registration Card Document', 'required');
            }
        }
        if (empty($this->input->post("hs_marksheet_old"))) {
            if (((empty($this->input->post("hs_marksheet_type"))) && (($_FILES['hs_marksheet']['name'] != "") || (!empty($this->input->post("hs_marksheet_temp"))))) || ((!empty($this->input->post("hs_marksheet_type"))) && (($_FILES['hs_marksheet']['name'] == "") && (empty($this->input->post("hs_marksheet_temp")))))) {

                $this->form_validation->set_rules('hs_marksheet', 'HS Registration Card Document', 'required');
            }
        }
        if (empty($this->input->post("photo_of_the_candidate_old"))) {
            if ((empty($this->input->post("photo_of_the_candidate_data"))) && ($_FILES['photo_of_the_candidate']['name'] == "")) {
                $this->form_validation->set_rules('photo_of_the_candidate', 'Photo of the Candidate', 'required');
            }
        }

        if ($sign_of_candidate == null && $_FILES['candidate_sign']['name'] == "") {
            $this->form_validation->set_rules('candidate_sign', 'Signature of the Candidate is Required', 'required');
        }
        if ($_FILES['photo_of_the_candidate']['name'] != "") {
            $photo_of_the_candidate = cifileupload("photo_of_the_candidate");
            $photo_of_candidate = $photo_of_the_candidate["upload_status"] ? $photo_of_the_candidate["uploaded_path"] : null;
        }

        $photo_of_the_candidate_data = $this->input->post("photo_of_the_candidate_data");

        if ((strlen($photo_of_candidate) == '') && (strlen($photo_of_the_candidate_data) > 50)) {
            $candidatePhotoData = str_replace('data:image/jpeg;base64,', '', $photo_of_the_candidate_data);
            $candidatePhotoData2 = str_replace(' ', '+', $candidatePhotoData);
            $candidatePhotoData64 = base64_decode($candidatePhotoData2);

            $fileName = uniqid() . '.jpeg';
            $dirPath = 'storage/docs/ahsec_migration/photos/';
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);

            }
            $photo_of_candidate = $dirPath . $fileName;
            file_put_contents(FCPATH . $photo_of_candidate, $candidatePhotoData64);
        } else {
            $photo_of_candidate = strlen($photo_of_candidate) ? $photo_of_candidate : (isset($dbRow->form_data->photo_of_the_candidate) ? $dbRow->form_data->photo_of_the_candidate : '');

        }

        //////////////////////////////////////////////
        if ($_FILES['candidate_sign']['name'] != "") {
            $candidate_sign = cifileupload("candidate_sign");

            $sign_of_candidate = $candidate_sign["upload_status"] ? $candidate_sign["uploaded_path"] : null;

        }

        if (strlen($this->input->post("hs_reg_card_temp")) > 0) {
            $hsRegCard = movedigilockerfile($this->input->post('hs_reg_card_temp'));
            $hs_registration_card = $hsRegCard["upload_status"] ? $hsRegCard["uploaded_path"] : null;
        } else {
            $hsRegCard = cifileupload("hs_reg_card");
            $hs_registration_card = $hsRegCard["upload_status"] ? $hsRegCard["uploaded_path"] : null;
        }

        if (strlen($this->input->post("hs_marksheet_temp")) > 0) {
            $hsMarksheet = movedigilockerfile($this->input->post('hs_marksheet_temp'));
            $hs_marksheet_card = $hsMarksheet["upload_status"] ? $hsMarksheet["uploaded_path"] : null;
        } else {
            $hsMarksheet = cifileupload("hs_marksheet");
            $hs_marksheet_card = $hsMarksheet["upload_status"] ? $hsMarksheet["uploaded_path"] : null;
        }

        // pre($sign_of_candidate);
        $uploadedFiles = array(
            "hs_reg_card" => strlen($hs_registration_card) ? $hs_registration_card : '',
            "photo_of_candidate" => strlen($photo_of_candidate) ? $photo_of_candidate : '',
            "candidate_sign" => strlen($sign_of_candidate) ? $sign_of_candidate : '',
            "hs_marksheet" => strlen($hs_marksheet_card) ? $hs_marksheet_card : '',
        );

        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->sectionThree($objId);
        } else {

            $data = array(
                'form_data.hs_reg_card_type' => $this->input->post("hs_reg_card_type"),
                'form_data.hs_reg_card' => strlen($hs_registration_card) ? $hs_registration_card : $dbRow->form_data->hs_reg_card,
                'form_data.hs_marksheet_type' => $this->input->post("hs_marksheet_type"),
                'form_data.hs_marksheet' => strlen($hs_marksheet_card) ? $hs_marksheet_card : $dbRow->form_data->hs_marksheet,

                'form_data.photo_of_the_candidate_type' => $this->input->post("photo_of_the_candidate_type"),
                'form_data.photo_of_the_candidate' => strlen($photo_of_candidate) ? $photo_of_candidate : $dbRow->form_data->photo_of_the_candidate,
                'form_data.candidate_sign_type' => $this->input->post("candidate_sign_type"),
                'form_data.candidate_sign' => strlen($sign_of_candidate) ? $sign_of_candidate : $dbRow->form_data->candidate_sign,
            );

            $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

            $this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/migrationcertificateahsec/registration/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()
    public function preview($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('migrationcertificateahsec/preview', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/migrationcertificateahsec/registration');
        } //End of if else
    } //End of preview()

    public function applicationpreview($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
                "preview" => 1,
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('migrationcertificateahsec/apppreview', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/migrationcertificateahsec/registration');
        } //End of if else
    }

    public function track($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);

        if (count((array) $dbRow)) {
            $data = array(
                "service_data.service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('migrationcertificateahsec/track_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/migrationcertificateahsec/registration');
        } //End of if else
    } //End of track()

    public function queryform($objId = null)
    {
        if ($this->checkObjectId($objId)) {
            // $data["states"] = $this->registration_model->getStates();

            $dbRow = $this->registration_model->get_row(array("_id" => new ObjectId($objId), "service_data.appl_status" => "QS"));
            if ($dbRow) {
                $data = array(
                    "service_data.service_name" => $this->serviceName,
                    "dbrow" => $dbRow,
                    "pageTitle" => "Application for Migration Certificate",
                    "PageTiteAssamese" => "প্ৰব্ৰজন প্ৰমাণপত্ৰৰ বাবে আবেদন",
                    'states' => $this->registration_model->getStates(),
                    "countries" => $this->registration_model->getCountries(),
                );
                $data["sessions"] = $this->fetchsessions(15);

                $this->load->view('includes/frontend/header');
                $this->load->view('migrationcertificateahsec/query_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/migrationcertificateahsec/registration');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/nextofkin/registration');
        } //End of if else
    } //End of query()

    public function querysubmit()
    {
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }

        // $this->form_validation->set_rules('hs_marksheet_type', 'H.S. 2nd Year Marksheet Type', 'required');
        $this->form_validation->set_rules('hs_reg_card_type', 'H.S. Registration Card Type', 'required');
        $this->form_validation->set_rules('photo_of_the_candidate_type', 'Photo of the Candidate Type', 'required');
        $this->form_validation->set_rules('candidate_sign_type', 'Signature of the Candidate Type', 'required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->queryform($objId);
        } else {

            // $this->saveSectionTwo($this->input->post());
            // $this->saveSectionOne($this->input->post());

            $dbRow = $this->registration_model->get_by_doc_id($objId);
            /////////////////////////////////////////////////////doc upload
            $photo_of_candidate = '';
            $sign_of_candidate = '';
            $hs_registration_card = '';
            $hs_marksheet_card = '';

            $reg_card = isset($dbRow->form_data->hs_reg_card) ? $dbRow->form_data->hs_reg_card : '';
            // $marksheet= isset($dbRow->form_data->hs_marksheet) ? $dbRow->form_data->hs_marksheet : '';
            $photo_of_candidate = isset($dbRow->form_data->photo_of_the_candidate) ? $dbRow->form_data->photo_of_the_candidate : '';
            $sign_of_candidate = isset($dbRow->form_data->candidate_sign) ? $dbRow->form_data->candidate_sign : '';

            if ($reg_card == null && $_FILES['hs_reg_card']['name'] == "") {
                $this->form_validation->set_rules('hs_reg_card', 'H.S. Registration Card is Required', 'required');
            }
            // if ($marksheet == null && $_FILES['hs_marksheet']['name'] == "") {
            //     $this->form_validation->set_rules('hs_marksheet', 'H.S. 2nd Year Marksheet is Required', 'required');
            // }
            if ($photo_of_candidate == null && $_FILES['photo_of_the_candidate']['name'] == "") {
                $this->form_validation->set_rules('photo_of_the_candidate', 'Photo of the Candidate is Required', 'required');
            }
            if ($sign_of_candidate == null && $_FILES['candidate_sign']['name'] == "") {
                $this->form_validation->set_rules('candidate_sign', 'Signature of the Candidate is Required', 'required');
            }

            if ($_FILES['photo_of_the_candidate']['name'] != "") {
                $photo_of_the_candidate = cifileupload("photo_of_the_candidate");
                $photo_of_candidate = $photo_of_the_candidate["upload_status"] ? $photo_of_the_candidate["uploaded_path"] : null;
            }
            if ($_FILES['candidate_sign']['name'] != "") {
                $candidate_sign = cifileupload("candidate_sign");
                $sign_of_candidate = $candidate_sign["upload_status"] ? $candidate_sign["uploaded_path"] : null;
            }
            if ($_FILES['hs_reg_card']['name'] != "") {
                $hs_reg_card = cifileupload("hs_reg_card");
                $hs_registration_card = $hs_reg_card["upload_status"] ? $hs_reg_card["uploaded_path"] : null;
            }
            // if ($_FILES['hs_marksheet']['name'] != "") {
            //     $hs_marksheet = cifileupload("hs_marksheet");
            //     $hs_marksheet_card = $hs_marksheet["upload_status"] ? $hs_marksheet["uploaded_path"] : null;
            // }
            $uploadedFiles = array(
                "hs_reg_card" => strlen($hs_registration_card) ? $hs_registration_card : '',
                "photo_of_candidate" => strlen($photo_of_candidate) ? $photo_of_candidate : '',
                "candidate_sign" => strlen($sign_of_candidate) ? $sign_of_candidate : '',
                // "hs_marksheet" => strlen($hs_marksheet_card) ? $hs_marksheet_card : '',
            );
            $this->session->set_flashdata('uploaded_files', $uploadedFiles);

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $this->sectionThree($objId);
            } else {
                $data = array(
                    'form_data.hs_reg_card_type' => $this->input->post("hs_reg_card_type"),
                    'form_data.hs_reg_card' => strlen($hs_registration_card) ? $hs_registration_card : $dbRow->form_data->hs_reg_card,
                    // 'form_data.hs_marksheet_type' => $this->input->post("hs_marksheet_type"),
                    // 'form_data.hs_marksheet' => strlen($hs_marksheet_card) ? $hs_marksheet_card : $dbRow->form_data->hs_marksheet,

                    'form_data.photo_of_the_candidate_type' => $this->input->post("photo_of_the_candidate_type"),
                    'form_data.photo_of_the_candidate' => strlen($photo_of_candidate) ? $photo_of_candidate : $dbRow->form_data->photo_of_the_candidate,
                    'form_data.candidate_sign_type' => $this->input->post("candidate_sign_type"),
                    'form_data.candidate_sign' => strlen($sign_of_candidate) ? $sign_of_candidate : $dbRow->form_data->candidate_sign,
                );
                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

                //////////////////////////////////////////////
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Query submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Query submitted",
                    "remarks" => "Query submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $new_data = array(
                    "service_data.appl_status" => "QA",
                    'processing_history' => $processing_history,
                    'status' => "QUERY_ANSWERED",
                );

                // pre($new_data);
                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $new_data);

                $this->session->set_flashdata('success', 'Your application has been successfully updated');
                redirect('spservices/migrationcertificateahsec/registration/preview/' . $objId);
            }

        }
    } //End of querysubmit()

    public function request_for_recorrection($objId = null)
    {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->registration_model->get_row(array("_id" => new ObjectId($objId), "service_data.appl_status" => "D"));
            if ($dbRow) {
                $data = array(
                    "dbrow" => $dbRow,
                );
                $data["sessions"] = $this->fetchsessions(20);
                $this->load->view('includes/frontend/header');
                $this->load->view('migrationcertificateahsec/duplicate_cert_recorrection_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                $this->my_transactions();
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            $this->my_transactions();
        } //End of if else
    } //End of query()

    public function request_for_recorrection_submit()
    {
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $appl_ref_no = $this->input->post("appl_ref_no");
        $dbrow = $this->registration_model->get_by_doc_id($objId);
        $this->form_validation->set_rules('wrongly_generate_certi_type', 'Upload copy of wrongly generate certificate Type', 'required');
        $this->form_validation->set_rules('applicant_remarks', 'Applicant Remarks', 'required');
        if (empty($_FILES['wrongly_generate_certi']['name'])) {
            $this->form_validation->set_rules('wrongly_generate_certi', 'Upload copy of wrongly generate certificate Document', 'required');
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->request_for_recorrection($objId);
        } else {

            $wronglyGenerateCerti = cifileupload("wrongly_generate_certi");
            $wrongly_generate_certi = $wronglyGenerateCerti["upload_status"] ? $wronglyGenerateCerti["uploaded_path"] : null;

            $dbRow = $this->registration_model->get_by_doc_id($objId);
            if (count((array) $dbRow)) {
                $data = array(
                    'form_data.applicant_remarks' => $this->input->post("applicant_remarks"),
                    'form_data.wrongly_generate_certi_type' => $this->input->post("wrongly_generate_certi_type"),
                    'form_data.wrongly_generate_certi' => strlen($wrongly_generate_certi) ? $wrongly_generate_certi : "",
                );

                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Call request sent by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Call request submitted",
                    "remarks" => "Call request by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $this->load->model('upms/users_model');

                if ($dbRow->service_data->service_id == "AHSECDRC") {
                    $userFilter = array('user_services.service_code' => $dbRow->service_data->service_id, 'user_roles.role_code' => 'DS_AHSEC');
                } else {
                    $userFilter = array('user_services.service_code' => $dbRow->service_data->service_id, 'user_roles.role_code' => 'DCE_AHSEC');
                }

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

                $data = array(
                    "service_data.appl_status" => "CR",
                    'processing_history' => $processing_history,
                    'status' => "QUERY_ANSWERED",
                    'current_users' => $current_users,
                );

                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

                $this->session->set_flashdata('success', 'Your request has been sent successfully updated');
                redirect('spservices/migrationcertificateahsec/registration/applicationpreview/' . $objId);
            } else {
                $this->session->set_flashdata('fail', 'Unable to update data!!! Please try again.');
                $this->request_for_recorrection($objId);
            } //End of if else
        } //End of if else
    } //End of querysubmit()

    public function getID($length)
    {
        $rtps_trans_id = $this->generateID($length);
        while ($this->registration_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
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

    private function checkObjectId($obj)
    {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        } //End of if else
    } //End of checkObjectId()

    public function submit_to_backend($obj, $show_ack = false)
    {
        if ($obj) {
            $dbRow = $this->registration_model->get_by_doc_id($obj);

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
                $userFilter = array('user_services.service_code' => $this->serviceId, 'user_roles.role_code' => 'DA_AHSEC');
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

                $data_to_update = array(
                    'service_data.appl_status' => 'submitted',
                    'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'current_users' => $current_users,
                    'processing_history' => $processing_history,
                );
                $this->registration_model->update($obj, $data_to_update);

                //Sending submission SMS
                $nowTime = date('Y-m-d H:i:s');
                $sms = array(
                    "mobile" => (int) $dbRow->form_data->mobile,
                    "applicant_name" => $dbRow->form_data->applicant_name,
                    "service_name" => 'ACMR - Registration of Additional Degrees',
                    "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                    "app_ref_no" => $dbRow->service_data->appl_ref_no,
                );
                sms_provider("submission", $sms);
                redirect('spservices/migrationcertificateahsec/registration/acknowledgement/' . $obj);
            } else {
                $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                $this->my_transactions();
            }
        }
        redirect('iservices/transactions');
    }

    public function acknowledgement($objid)
    {
        $applicationRow = $this->applications_model->get_by_doc_id($objid);
        if ($applicationRow) {
            if (isset($applicationRow->service_id)) {
                $service_id = $applicationRow->service_id;
                $data['response'] = $applicationRow;
            } else {
                // for formated data
                $service_id = $applicationRow->service_data->service_id;

                $rtps_trans_id = $applicationRow->service_data->appl_ref_no;
                $applicationRowData = [
                    "rtps_trans_id" => $rtps_trans_id,
                    "submission_date" => $applicationRow->service_data->submission_date,
                    "service_timeline" => $applicationRow->service_data->service_timeline,
                    "applicant_name" => $applicationRow->form_data->applicant_name,

                    "application_no" => isset($applicationRow->form_data->application_no) ? $applicationRow->form_data->application_no : '',
                    "service_charge" => isset($applicationRow->form_data->service_charge) ? $applicationRow->form_data->service_charge : "",
                    "rtps_convenience_fee" => isset($applicationRow->form_data->convenience_fee) ? $applicationRow->form_data->convenience_fee : "",
                    "no_printing_page" => isset($applicationRow->form_data->no_printing_page) ? $applicationRow->form_data->no_printing_page : "",
                    "printing_charge_per_page" => isset($applicationRow->form_data->printing_charge_per_page) ? $applicationRow->form_data->printing_charge_per_page : "",
                    "no_scanning_page" => isset($applicationRow->form_data->no_scanning_page) ? $applicationRow->form_data->no_scanning_page : "",
                    "scanning_charge_per_page" => isset($applicationRow->form_data->scanning_charge_per_page) ? $applicationRow->form_data->scanning_charge_per_page : "",
                    "pfc_payment_status" => isset($applicationRow->form_data->pfc_payment_status) ? $applicationRow->form_data->pfc_payment_status : "",

                ];

                if (isset($applicationRow->form_data->application_charge)) {
                    if ($applicationRow->form_data->application_charge != "0") {
                        $applicationRowData['amount'] = $applicationRow->form_data->application_charge;
                    }
                }

                $data['response'] = (object) $applicationRowData;
            }
            // pre($data['response']);
            $data['service_row'] = $this->services_model->get_row(array("service_id" => $service_id));
            $data['back_to_dasboard'] = '<a href="' . base_url('spservices/applications/') . '" class="btn btn-primary mb-2 pull-right"  >Back To Dashboard</a>';
            $data['pageTitle'] = "Acknowledgement";
            $this->load->view('includes/frontend/header');
            $this->load->view('/migrationcertificateahsec/ack', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            redirect('spservices/applications/');
        } //End of if else
    }

    private function my_transactions()
    {
        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }

    public function download_certificate($objId = null)
    {
        if (!empty($objId)) {
            $dbRowArray = array();
            $dbRow = $this->registration_model->get_by_doc_id($objId);
            $dbRowArray[] = $dbRow;
            $qrcode_path = 'storage/docs/common_qr/';
            $filename = str_replace("/", "-", $objId);
            $qrname = $filename . ".png";
            $file_name = $qrcode_path . $qrname;

            $filter1 = array(
                "registration_number" => $dbRow->form_data->ahsec_reg_no,
                "registration_session" => $dbRow->form_data->ahsec_reg_session,
            );
            $ahsecregistration_data = $this->ahsecregistration_model->get_row($filter1);

            if ($dbRowArray[0]->service_data->service_id == 'AHSECMIGR') {

                $this->load->view('includes/frontend/header');
                $this->load->view('migrationcertificateahsec/certificate', array('dbrow' => $dbRow, 'certificate_no' => $dbRowArray[0]->form_data->certificate_no, 'certificate_path' => null, 'qr' => $file_name, 'user_type' => 'citizen'));
                $this->load->view('includes/frontend/footer');

            }
        }
    }

    public function get_districts()
    {
        $field_name = $this->input->post("field_name");
        $state_name = $this->input->post("field_value");
        $selected_value = $this->input->post("selected_value");

        $slc = $this->registration_model->get_state(array("state_name_english" => $state_name))->slc ?? '';
        // pre($state->slc);

        echo '<select name="' . $field_name . '" id="' . $field_name . '" class="form-control">';

        if (strlen($slc)) {

            $districts = $this->registration_model->get_distinct_results(array("slc" => $slc));

            if ($districts) {
                echo "<option value=''>Please Select</option>";

                foreach ($districts as $district) {

                    if ($district->district_name_english == $selected_value) {
                        echo '<option selected value="' . $district->district_name_english . '" >' . $district->district_name_english . '</option>';

                    } else {
                        echo '<option value="' . $district->district_name_english . '" >' . $district->district_name_english . '</option>';

                    }
                }
            } else {
                echo "<option value=''>No records found</option>";
            }
        } else {
            echo "<option value=''>slc ID cannot be empty</option>";
        }

        // echo '</select>';
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
            $this->load->view('migrationcertificateahsec/admin_preview', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/migrationcertificateahsec/registration');
        } //End of if else
    }
} //End of Castecertificate
