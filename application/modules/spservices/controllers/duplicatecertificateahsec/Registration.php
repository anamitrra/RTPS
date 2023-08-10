<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('duplicatecertificateahsec/registration_model');
        $this->load->model('duplicatecertificateahsec/ahsecregistration_model');
        $this->load->model('duplicatecertificateahsec/ahsecmarksheet_model');
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

    public function fetchyears($no_of_year)
    {
        $currentYear = date('Y');
        $yearArray = array();
        for ($i = 1; $i <= $no_of_year; $i++) {
            $yearArray[$currentYear] = $currentYear;
            $currentYear--;
        }
        return $yearArray;
    }

    public function index($obj_id = null)
    {
        //check_application_count_for_citizen();
        if ($this->checkObjectId($obj_id)) {
            $filter = array("_id" => new ObjectId($obj_id), "service_data.appl_status" => "DRAFT");
            $data["dbrow"] = $this->registration_model->get_row($filter);
            $data["states"] = $this->registration_model->getStates();
            $data["districts"] = $this->registration_model->getDistricts();

            if ($data["dbrow"] == "") {
                $this->my_transactions();
            }

        } else {
            $this->my_transactions();
        }
        $data['usser_type'] = $this->slug;
        $data["sessions"] = $this->fetchsessions(15);
        $data["years"] = $this->fetchyears(35);
        $this->load->view('includes/frontend/header');
        $this->load->view('duplicatecertificateahsec/section_one_create', $data);
        $this->load->view('includes/frontend/footer');
    } //End of index()

    public function searchdetails($obj_id = null)
    {
        //check_application_count_for_citizen();
        if ($obj_id == "AHSECDRC") {
            $data = array("pageTitleId" => "AHSECDRC", "pageTitle" => "Application for Issuance of Duplicate Registration Card");
        } else if ($obj_id == "AHSECDADM") {
            $data = array("pageTitleId" => "AHSECDADM", "pageTitle" => "Application for Issuance of Duplicate Admit Card");
        } else if ($obj_id == "AHSECDMRK") {
            $data = array("pageTitleId" => "AHSECDMRK", "pageTitle" => "Application for Issuance of Duplicate Marksheet");
        } else if ($obj_id == "AHSECDPC") {
            $data = array("pageTitleId" => "AHSECDPC", "pageTitle" => "Application for Issuance of Duplicate Pass Certificate");
        } else {
            $this->my_transactions();
        }
        $data['usser_type'] = $this->slug;
        $data["sessions"] = $this->fetchsessions(55);
        $data["years"] = $this->fetchyears(55);
        $this->load->view('includes/frontend/header');
        $this->load->view('duplicatecertificateahsec/search_details', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function searchdetails_submit()
    {
        $service_id = $this->input->post("service_id");
        $submissionMode = $this->input->post("submission_mode");
        $this->form_validation->set_rules('ahsec_reg_session', 'AHSEC reg. session', 'trim|required|xss_clean|max_length[10]');
        $this->form_validation->set_rules('ahsec_reg_no', 'AHSEC reg. no', 'trim|required|xss_clean|strip_tags');
        if ($service_id != "AHSECDRC") {
            $this->form_validation->set_rules('ahsec_yr_exam', 'Year of appearing/passing in HS Final Examination', 'trim|required|xss_clean|strip_tags');

            $lower_limit_yr = "2006";
            $upper_limit_yr = "2017";
            if (!empty($this->input->post("ahsec_yr_exam"))) {
                if (($this->input->post("ahsec_yr_exam") >= $lower_limit_yr) && ($this->input->post("ahsec_yr_exam") <= $upper_limit_yr)) {
                    $this->form_validation->set_rules('ahsec_admit_roll', 'AHSEC admit roll', 'trim|required|xss_clean|strip_tags|min_length[4]|max_length[4]');
                    $this->form_validation->set_rules('ahsec_admit_no', 'AHSEC admit no', 'trim|required|xss_clean|strip_tags|min_length[5]|max_length[5]');
                }
            } else {
                $this->form_validation->set_rules('ahsec_admit_roll', 'AHSEC admit roll', 'trim|required|xss_clean|strip_tags');
                $this->form_validation->set_rules('ahsec_admit_no', 'AHSEC admit no', 'trim|required|xss_clean|strip_tags');
                $this->form_validation->set_rules('ahsec_yr_exam', 'AHSEC Year of appearing', 'trim|required');
            }
        } else {
            if ($service_id == "AHSECDRC") {
                if ((!empty($this->input->post("ahsec_yr_exam"))) && ($this->input->post("ahsec_yr_exam") != "Not yet appeared")) {
                    $this->form_validation->set_rules('ahsec_admit_roll', 'AHSEC admit roll', 'trim|required|xss_clean|strip_tags');
                    $this->form_validation->set_rules('ahsec_admit_no', 'AHSEC admit no', 'trim|required|xss_clean|strip_tags');
                }
                $this->form_validation->set_rules('ahsec_yr_exam', 'AHSEC Year of appearing', 'trim|required');
            }
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->searchdetails($service_id);
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

            $ahsecmarksheet_data = "";
            $marksheet_data_count = 0;
            $limit_yr_exam = "2018";
            if ((!empty($this->input->post("ahsec_admit_roll"))) && (!empty($this->input->post("ahsec_admit_no"))) && (!empty($this->input->post("ahsec_yr_exam")))) {

                // In case of examination years from 2018, on entering Roll and No by an applicant, the system will check for back-end validation.
                if ($this->input->post("ahsec_yr_exam") >= $limit_yr_exam) {

                    $filter2 = array(
                        "Registration_No" => (int) $this->input->post("ahsec_reg_no"),
                        "Registration_Session" => $this->input->post("ahsec_reg_session"),
                        "Roll" => (int) $this->input->post("ahsec_admit_roll"),
                        "No" => (int) $this->input->post("ahsec_admit_no"),
                        "Year_of_Examination" => (int) $this->input->post("ahsec_yr_exam"),
                    );

                    $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);
                    $marksheet_data_count = $this->ahsecmarksheet_model->rows_count($filter2);

                    if ($marksheet_data_count > 1) {
                        $this->session->set_flashdata('error', 'We have found multiple records! Unable to verify');
                        $this->searchdetails($service_id);
                    }
                }
                // End /////////
            }

            ///////// For Duplicate/Correction
            $filter45 = array(
                "form_data.ahsec_reg_session" => $this->input->post("ahsec_reg_session"),
                "form_data.ahsec_reg_no" => (int) $this->input->post("ahsec_reg_no"),
                "service_data.service_id" => $service_id,
            );

            if ($service_id != "AHSECDRC") {
                $filter45["form_data.ahsec_admit_roll"] = (int) $this->input->post("ahsec_admit_roll");
                $filter45["form_data.ahsec_admit_no"] = (int) $this->input->post("ahsec_admit_no");
                $filter45["form_data.ahsec_yearofpassing"] = (int) $this->input->post("ahsec_yr_exam");
            }
            //

            /// An applicant can apply for any duplicate service maximum thrice.
            $dbrow_data45_cnt = $this->registration_model->rows_count($filter45);
            if ($dbrow_data45_cnt > 3) {
                $this->session->set_flashdata('error', 'You can apply for 3 times only. !!');
                redirect('spservices/duplicatecertificateahsec/registration/searchdetails/' . $service_id);
            }
            //

            //can’t apply for the same service again until his earlierapplication for the service is delivered or rejected.
            $dbrow_data45 = $this->registration_model->get_rows($filter45);
            $dbrow_cnt_data45 = $this->registration_model->rows_count($filter45);
            if ($dbrow_cnt_data45 > 0) {
                if ($dbrow_data45) {
                    $d_r_cnt = 0;
                    foreach ($dbrow_data45 as $key => $value) {
                        if (($value->service_data->appl_status == 'D') || ($value->service_data->appl_status == 'R')) {
                            ++$d_r_cnt;
                        }

                    }
                    if ($d_r_cnt == 0) {
                        $this->session->set_flashdata('error', 'Your previous application isn\'t delivered or rejected yet. So you can\'t apply for new application !!');
                        redirect('spservices/duplicatecertificateahsec/registration/searchdetails/' . $service_id);
                    }
                }
            }
            ///////

            /////////

            //////// For Migration - An applicant can apply for migration certificate only once..
            $filter45 = array(
                "form_data.ahsec_reg_session" => $this->input->post("ahsec_reg_session"),
                "form_data.ahsec_reg_no" => (int) $this->input->post("ahsec_reg_no"),
                "service_data.service_id" => "AHSECMIGR",
            );

            $dbrow_mig_data45_cnt = $this->registration_model->rows_count($filter45);
            if ($dbrow_mig_data45_cnt > 0) {
                $this->session->set_flashdata('error', 'You have already applied for migration certificate. Migration Certificate can apply once. !!');
                redirect('spservices/duplicatecertificateahsec/registration/searchdetails/' . $service_id);
            }
            ///////

            //If already record submitted
            $filter = array(
                "form_data.ahsec_reg_session" => $this->input->post("ahsec_reg_session"),
                "form_data.ahsec_reg_no" => (int) $this->input->post("ahsec_reg_no"),
                "service_data.service_id" => $service_id,
                "service_data.appl_status" => "DRAFT",
            );

            if ($service_id != "AHSECDRC") {
                $filter["form_data.ahsec_admit_roll"] = (int) $this->input->post("ahsec_admit_roll");
                $filter["form_data.ahsec_admit_no"] = (int) $this->input->post("ahsec_admit_no");
                $filter["form_data.ahsec_yearofpassing"] = (int) $this->input->post("ahsec_yr_exam");
            }

            $dbrow_data = $this->registration_model->get_row($filter);

            if (!empty($dbrow_data)) {
                $objectId = $dbrow_data->_id->{'$id'};
                $this->session->set_flashdata('success', 'Your data has been verified successfully !!');
                redirect('spservices/duplicatecertificateahsec/registration/index/' . $objectId);
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

            if ($service_id == "AHSECDRC") {
                $service_name = "Application for Issuance of Duplicate Registration Card";
            } else if ($service_id == "AHSECDADM") {
                $service_name = "Application for Issuance of Duplicate Admit Card";
            } else if ($service_id == "AHSECDMRK") {
                $service_name = "Application for Issuance of Duplicate Marksheet";
            } else if ($service_id == "AHSECDPC") {
                $service_name = "Application for Issuance of Duplicate Pass Certificate";
            }

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
                "service_id" => $service_id,
                "service_name" => $service_name,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => "ASSAM HIGHER SECONDARY EDUCATION COUNCIL", // office name
                "submission_date" => "",
                "service_timeline" => "7 Days",
                "appl_status" => "DRAFT",
                "district" => "KAMRUP METRO",
            ];

            $form_data = [
                'applicant_name' => $ahsecregistration_data->candidate_name ?? ($dbrow_data->form_data->applicant_name ?? ''),
                'father_name' => $ahsecregistration_data->father_name ?? ($dbrow_data->form_data->father_name ?? ''),
                'mother_name' => $ahsecregistration_data->mother_name ?? ($dbrow_data->form_data->mother_name ?? ''),
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

                'ahsec_reg_session' => $ahsecregistration_data->registration_session ?? $this->input->post("ahsec_reg_session"),
                'ahsec_reg_no' => $ahsecregistration_data->registration_number ?? (int) $this->input->post("ahsec_reg_no"),
                'ahsec_admit_roll' => ($service_id != "AHSECDRC") ? ($ahsecmarksheet_data->Roll ?? (int) $this->input->post("ahsec_admit_roll")) : '',
                'ahsec_admit_no' => ($service_id != "AHSECDRC") ? ($ahsecmarksheet_data->No ?? (int) $this->input->post("ahsec_admit_no")) : '',
                'ahsec_yearofpassing' => ($service_id != "AHSECDRC") ? ($ahsecmarksheet_data->Year_of_Examination ?? $this->input->post("ahsec_yr_exam")) : '',
                'institution_name' => $ahsecregistration_data->institution_name ?? ($ahsecmarksheet_data->School_College_Name ?? ""),

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
                $this->session->set_flashdata('success', 'Your data has been verified successfully !!');
                redirect('spservices/duplicatecertificateahsec/registration/index/' . $objectId);
            } else {
                $this->session->set_flashdata('error', 'Something went wrong. Please try again !!');
                $this->searchdetails($service_id);
            }
        }
    }

    public function submit()
    {
        if ($this->input->post("step") == 2) {
            $this->form_validation->set_rules('board_seaking_adm', 'Board Seeking Admission', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('course_seaking_adm', 'Course Seeking Admission', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('state_seaking_adm', 'State Seeking Admission', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('reason_seaking_adm', 'Describe Reason for Seeking Migration', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');

            $this->form_validation->set_rules('condi_of_doc', 'Condition of Documents', 'trim|required');

            $this->form_validation->set_rules('postal', 'Delivery Preference', 'trim|required');

            $objId = $this->input->post("obj_id");

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $obj_id = strlen($objId) ? $objId : null;
                $this->sectionTwo($obj_id);
            } else {

                $form_data = [
                    'form_data.board_seaking_adm' => $this->input->post("board_seaking_adm"),
                    'form_data.course_seaking_adm' => $this->input->post("course_seaking_adm"),
                    'form_data.state_seaking_adm' => $this->input->post("state_seaking_adm"),
                    'form_data.reason_seaking_adm' => $this->input->post("reason_seaking_adm"),
                    'form_data.condi_of_doc' => $this->input->post("condi_of_doc"),
                    'form_data.postal' => $this->input->post("postal"),
                ];

                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $form_data);
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/duplicatecertificateahsec/registration/fileuploads/' . $objId);
            }
        } else {
            $objId = $this->input->post("obj_id");
            $appl_ref_no = $this->input->post("appl_ref_no");
            $submissionMode = $this->input->post("submission_mode");
            $service_id = $this->input->post("service_id");

            $this->form_validation->set_rules('applicant_name', 'Applicant name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('applicant_gender', 'Applicant Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');

            $this->form_validation->set_rules('father_name', 'Father Name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('mother_name', 'Mother name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|strip_tags|valid_email|required');

            $this->form_validation->set_rules('comp_permanent_address', 'Complete Permanent Address', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('pa_pincode', 'Pincode', 'trim|numeric|required|xss_clean|strip_tags|max_length[6]');
            $this->form_validation->set_rules('pa_state', 'State', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('pa_district', 'District', 'trim|required|xss_clean|strip_tags');

            $this->form_validation->set_rules('ahsec_reg_session', 'AHSEC reg. session', 'trim|required|xss_clean|max_length[255]');
            $this->form_validation->set_rules('ahsec_reg_no', 'AHSEC reg. no', 'trim|required|xss_clean|strip_tags');
            if ($service_id != "AHSECDRC") {
                $this->form_validation->set_rules('ahsec_yearofpassing', 'AHSEC year of passing', 'trim|required|xss_clean|strip_tags');
                $this->form_validation->set_rules('ahsec_admit_roll', 'AHSEC admit roll', 'trim|required|xss_clean|strip_tags');
                $this->form_validation->set_rules('ahsec_admit_no', 'AHSEC admit no', 'trim|required|xss_clean|strip_tags');
            }

            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $obj_id = strlen($objId) ? $objId : null;
                $this->index($obj_id);
            } else {
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

                if ($service_id == "AHSECDRC") {
                    $service_name = "Application for Issuance of Duplicate Registration Card";
                } else if ($service_id == "AHSECDADM") {
                    $service_name = "Application for Issuance of Duplicate Admit Card";
                } else if ($service_id == "AHSECDMRK") {
                    $service_name = "Application for Issuance of Duplicate Marksheet";
                } else if ($service_id == "AHSECDPC") {
                    $service_name = "Application for Issuance of Duplicate Pass Certificate";
                }

                if (strlen($objId)) {
                    $form_data = [
                        'form_data.applicant_name' => $this->input->post("applicant_name"),
                        'form_data.applicant_gender' => $this->input->post("applicant_gender"),
                        'form_data.father_name' => $this->input->post("father_name"),
                        'form_data.mother_name' => $this->input->post("mother_name"),
                        'form_data.mobile' => $this->input->post("mobile"),
                        'form_data.email' => $this->input->post("email"),

                        'form_data.comp_permanent_address' => $this->input->post("comp_permanent_address"),
                        'form_data.pa_pincode' => $this->input->post("pa_pincode"),
                        'form_data.pa_state' => $this->input->post("pa_state"),
                        'form_data.pa_district' => $this->input->post("pa_district"),

                        'form_data.comp_postal_address' => $this->input->post("comp_postal_address"),
                        'form_data.pos_pincode' => $this->input->post("pos_pincode"),
                        'form_data.pos_state' => $this->input->post("pos_state"),
                        'form_data.pos_district' => $this->input->post("pos_district"),

                        'form_data.institution_name' => $this->input->post("institution_name"),

                        'form_data.user_id' => $sessionUser['userId']->{'$id'},
                        'form_data.user_type' => $this->slug,
                        'form_data.created_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    ];

                    if ($service_id != "AHSECDRC") {
                        $form_data['form_data.ahsec_yearofpassing'] = (int) $this->input->post("ahsec_yearofpassing");
                    }

                    $this->registration_model->update_where(['_id' => new ObjectId($objId)], $form_data);
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    redirect('spservices/duplicatecertificateahsec/registration/sectionTwo/' . $objId);
                } else {
                    $this->my_transactions();
                } //End of if else
            } //End of if else
        }
    } //End of submit()

    public function sectionTwo($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array(
                "dbrow" => $dbRow,
            );

            $this->load->view('includes/frontend/header');
            $this->load->view('duplicatecertificateahsec/section_two_create', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/duplicatecertificateahsec/registration');
        } //End of if else
    } //End of fileuploads()

    public function fileuploads($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array(
                "obj_id" => $objId,
                "dbrow" => $dbRow,
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('duplicatecertificateahsec/duplicate_cert_uploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/duplicatecertificateahsec/registration');
        } //End of if else
    } //End of fileuploads()

    public function submitfiles()
    {
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $dbrow = $this->registration_model->get_by_doc_id($objId);

        $this->form_validation->set_rules('photo_of_the_candidate_type', 'Passport size photograph', 'required');
        $this->form_validation->set_rules('candidate_sign_type', 'Applicant Signature', 'required');

        $condi_of_doc = $dbrow->form_data->condi_of_doc;

        if (($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST")) {
            $this->form_validation->set_rules('fir_type', 'FIR Copy', 'required');
            if (empty($this->input->post("fir_old"))) {
                if (((empty($this->input->post("fir_type"))) && (($_FILES['fir']['name'] != "") || (!empty($this->input->post("fir_temp"))))) || ((!empty($this->input->post("fir_type"))) && (($_FILES['fir']['name'] == "") && (empty($this->input->post("fir_temp")))))) {
                    $this->form_validation->set_rules('fir', 'FIR Copy Document', 'required');
                }
            }
        }

        if ($condi_of_doc == "LOST") {
            $this->form_validation->set_rules('paper_advertisement_type', 'Paper Advertisement Copy', 'required');
            if (empty($this->input->post("paper_advertisement_old"))) {
                if (((empty($this->input->post("paper_advertisement_type"))) && (($_FILES['paper_advertisement']['name'] != "") || (!empty($this->input->post("paper_advertisement_temp"))))) || ((!empty($this->input->post("paper_advertisement_type"))) && (($_FILES['paper_advertisement']['name'] == "") && (empty($this->input->post("paper_advertisement_temp")))))) {

                    $this->form_validation->set_rules('paper_advertisement', 'Paper Advertisement Copy Document', 'required');
                }
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDRC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            $this->form_validation->set_rules('hslc_tenth_mrksht_type', 'HSLC/10thMarksheet', 'required');
            if (empty($this->input->post("hslc_tenth_mrksht_old"))) {
                if (((empty($this->input->post("hslc_tenth_mrksht_type"))) && (($_FILES['hslc_tenth_mrksht']['name'] != "") || (!empty($this->input->post("hslc_tenth_mrksht_temp"))))) || ((!empty($this->input->post("hslc_tenth_mrksht_type"))) && (($_FILES['hslc_tenth_mrksht']['name'] == "") && (empty($this->input->post("hslc_tenth_mrksht_temp")))))) {

                    $this->form_validation->set_rules('hslc_tenth_mrksht', 'HSLC/10thMarksheet Document', 'required');
                }
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDRC") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            $this->form_validation->set_rules('damage_reg_card_type', 'Damaged portion of the Registration Card Copy', 'required');
            if (empty($this->input->post("damage_reg_card_old"))) {
                if (((empty($this->input->post("damage_reg_card_type"))) && (($_FILES['damage_reg_card']['name'] != "") || (!empty($this->input->post("damage_reg_card_temp"))))) || ((!empty($this->input->post("damage_reg_card_type"))) && (($_FILES['damage_reg_card']['name'] == "") && (empty($this->input->post("damage_reg_card_temp")))))) {

                    $this->form_validation->set_rules('damage_reg_card', 'Damaged portion of the Registration Card Copy Document', 'required');
                }
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDADM") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            $this->form_validation->set_rules('damage_admit_card_type', 'Damaged portion of the Admit Card Copy', 'required');
            if (empty($this->input->post("damage_admit_card_old"))) {
                if (((empty($this->input->post("damage_admit_card_type"))) && (($_FILES['damage_admit_card']['name'] != "") || (!empty($this->input->post("damage_admit_card_temp"))))) || ((!empty($this->input->post("damage_admit_card_type"))) && (($_FILES['damage_admit_card']['name'] == "") && (empty($this->input->post("damage_admit_card_temp")))))) {

                    $this->form_validation->set_rules('damage_admit_card', 'Damaged portion of the Admit Card Copy Document', 'required');
                }
            }
        }

        if ((($dbrow->service_data->service_id == "AHSECDADM") || ($dbrow->service_data->service_id == "AHSECDMRK") || ($dbrow->service_data->service_id == "AHSECDPC")) && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            $this->form_validation->set_rules('hs_reg_card_type', 'HS Registration Card', 'required');
            if (empty($this->input->post("hs_reg_card_old"))) {
                if (((empty($this->input->post("hs_reg_card_type"))) && (($_FILES['hs_reg_card']['name'] != "") || (!empty($this->input->post("hs_reg_card_temp"))))) || ((!empty($this->input->post("hs_reg_card_type"))) && (($_FILES['hs_reg_card']['name'] == "") && (empty($this->input->post("hs_reg_card_temp")))))) {

                    $this->form_validation->set_rules('hs_reg_card', 'HS Registration Card Document', 'required');
                }
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDMRK") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            $this->form_validation->set_rules('damage_mrksht_type', 'Damaged portion of the Marksheet Copy', 'required');
            if (empty($this->input->post("damage_mrksht_old"))) {
                if (((empty($this->input->post("damage_mrksht_type"))) && (($_FILES['damage_mrksht']['name'] != "") || (!empty($this->input->post("damage_mrksht_temp"))))) || ((!empty($this->input->post("damage_mrksht_type"))) && (($_FILES['damage_mrksht']['name'] == "") && (empty($this->input->post("damage_mrksht_temp")))))) {

                    $this->form_validation->set_rules('damage_mrksht', 'Damaged portion of the Marksheet Copy Document', 'required');
                }
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDMRK") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            $this->form_validation->set_rules('hs_admit_card_type', 'HS Admit Card', 'required');
            if (empty($this->input->post("hs_admit_card_old"))) {
                if (((empty($this->input->post("hs_admit_card_type"))) && (($_FILES['hs_admit_card']['name'] != "") || (!empty($this->input->post("hs_admit_card_temp"))))) || ((!empty($this->input->post("hs_admit_card_type"))) && (($_FILES['hs_admit_card']['name'] == "") && (empty($this->input->post("hs_admit_card_temp")))))) {

                    $this->form_validation->set_rules('hs_admit_card', 'HS Admit Card Document', 'required');
                }
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDPC") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            $this->form_validation->set_rules('damage_pass_certi_type', 'Damaged portion of the Pass Certificate Copy', 'required');
            if (empty($this->input->post("damage_pass_certi_old"))) {
                if (((empty($this->input->post("damage_pass_certi_type"))) && (($_FILES['damage_pass_certi']['name'] != "") || (!empty($this->input->post("damage_pass_certi_temp"))))) || ((!empty($this->input->post("damage_pass_certi_type"))) && (($_FILES['damage_pass_certi']['name'] == "") && (empty($this->input->post("damage_pass_certi_temp")))))) {

                    $this->form_validation->set_rules('damage_pass_certi', 'Damaged portion of the Pass Certificate Copy Document', 'required');
                }
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDPC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            $this->form_validation->set_rules('hs_mrksht_type', 'HS Marksheet', 'required');
            if (empty($this->input->post("hs_mrksht_old"))) {
                if (((empty($this->input->post("hs_mrksht_type"))) && (($_FILES['hs_mrksht']['name'] != "") || (!empty($this->input->post("hs_mrksht_temp"))))) || ((!empty($this->input->post("hs_mrksht_type"))) && (($_FILES['hs_mrksht']['name'] == "") && (empty($this->input->post("hs_mrksht_temp")))))) {
                    $this->form_validation->set_rules('hs_mrksht', 'HS Marksheet Document', 'required');
                }
            }
        }

        if (empty($this->input->post("photo_of_the_candidate_old"))) {
            if ((empty($this->input->post("photo_of_the_candidate_data"))) && ($_FILES['photo_of_the_candidate']['name'] == "")) {
                $this->form_validation->set_rules('photo_of_the_candidate', 'Passport size photograph', 'required');
            }
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $photoOfTheCandidate = cifileupload("photo_of_the_candidate");
        $photo_of_the_candidate = $photoOfTheCandidate["upload_status"] ? $photoOfTheCandidate["uploaded_path"] : null;
        // Photo from webcam
        $photo_of_the_candidate_data = $this->input->post("photo_of_the_candidate_data");
        if ((strlen($photo_of_the_candidate) == '') && (strlen($photo_of_the_candidate_data) > 50)) {
            $candidatePhotoData = str_replace('data:image/jpeg;base64,', '', $photo_of_the_candidate_data);
            $candidatePhotoData2 = str_replace(' ', '+', $candidatePhotoData);
            $candidatePhotoData64 = base64_decode($candidatePhotoData2);

            $fileName = uniqid() . '.jpeg';
            $dirPath = 'storage/docs/ahsec_duplicate_certificate/photos/';
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
                // file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files </body></html>');
            }
            $photo_of_the_candidate = $dirPath . $fileName;
            // pre($photo_of_candidate);
            file_put_contents(FCPATH . $photo_of_the_candidate, $candidatePhotoData64);
        }

        $candidateSign = cifileupload("candidate_sign");
        $candidate_sign = $candidateSign["upload_status"] ? $candidateSign["uploaded_path"] : null;

        if (($dbrow->form_data->condi_of_doc == "FULLY DAMAGED") || ($dbrow->form_data->condi_of_doc == "LOST")) {
            if (strlen($this->input->post("fir_temp")) > 0) {
                $fir = movedigilockerfile($this->input->post('fir_temp'));
                $fir = $fir["upload_status"] ? $fir["uploaded_path"] : null;
            } else {
                $fir = cifileupload("fir");
                $fir = $fir["upload_status"] ? $fir["uploaded_path"] : null;
            }
        }

        if ($dbrow->form_data->condi_of_doc == "LOST") {
            if (strlen($this->input->post("paper_advertisement_temp")) > 0) {
                $paperAdvertisement = movedigilockerfile($this->input->post('paper_advertisement_temp'));
                $paper_advertisement = $paperAdvertisement["upload_status"] ? $paperAdvertisement["uploaded_path"] : null;
            } else {
                $paperAdvertisement = cifileupload("paper_advertisement");
                $paper_advertisement = $paperAdvertisement["upload_status"] ? $paperAdvertisement["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDRC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            if (strlen($this->input->post("hslc_tenth_mrksht_temp")) > 0) {
                $hslcTenthMrksht = movedigilockerfile($this->input->post('hslc_tenth_mrksht_temp'));
                $hslc_tenth_mrksht = $hslcTenthMrksht["upload_status"] ? $hslcTenthMrksht["uploaded_path"] : null;
            } else {
                $hslcTenthMrksht = cifileupload("hslc_tenth_mrksht");
                $hslc_tenth_mrksht = $hslcTenthMrksht["upload_status"] ? $hslcTenthMrksht["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDRC") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            if (strlen($this->input->post("damage_reg_card_temp")) > 0) {
                $damageRegCard = movedigilockerfile($this->input->post('damage_reg_card_temp'));
                $damage_reg_card = $damageRegCard["upload_status"] ? $damageRegCard["uploaded_path"] : null;
            } else {
                $damageRegCard = cifileupload("damage_reg_card");
                $damage_reg_card = $damageRegCard["upload_status"] ? $damageRegCard["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDADM") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            if (strlen($this->input->post("damage_admit_card_temp")) > 0) {
                $damageAdmitCard = movedigilockerfile($this->input->post('damage_admit_card_temp'));
                $damage_admit_card = $damageAdmitCard["upload_status"] ? $damageAdmitCard["uploaded_path"] : null;
            } else {
                $damageAdmitCard = cifileupload("damage_admit_card");
                $damage_admit_card = $damageAdmitCard["upload_status"] ? $damageAdmitCard["uploaded_path"] : null;
            }
        }

        if ((($dbrow->service_data->service_id == "AHSECDADM") || ($dbrow->service_data->service_id == "AHSECDMRK") || ($dbrow->service_data->service_id == "AHSECDPC")) && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            if (strlen($this->input->post("hs_reg_card_temp")) > 0) {
                $hsRegCard = movedigilockerfile($this->input->post('hs_reg_card_temp'));
                $hs_reg_card = $hsRegCard["upload_status"] ? $hsRegCard["uploaded_path"] : null;
            } else {
                $hsRegCard = cifileupload("hs_reg_card");
                $hs_reg_card = $hsRegCard["upload_status"] ? $hsRegCard["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDMRK") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            if (strlen($this->input->post("damage_mrksht_temp")) > 0) {
                $damageMrksht = movedigilockerfile($this->input->post('damage_mrksht_temp'));
                $damage_mrksht = $damageMrksht["upload_status"] ? $damageMrksht["uploaded_path"] : null;
            } else {
                $damageMrksht = cifileupload("damage_mrksht");
                $damage_mrksht = $damageMrksht["upload_status"] ? $damageMrksht["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDMRK") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            if (strlen($this->input->post("hs_admit_card_temp")) > 0) {
                $hsAdmitCard = movedigilockerfile($this->input->post('hs_admit_card_temp'));
                $hs_admit_card = $hsAdmitCard["upload_status"] ? $hsAdmitCard["uploaded_path"] : null;
            } else {
                $hsAdmitCard = cifileupload("hs_admit_card");
                $hs_admit_card = $hsAdmitCard["upload_status"] ? $hsAdmitCard["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDMRK") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            if (strlen($this->input->post("damage_pass_certi_temp")) > 0) {
                $damagePassCerti = movedigilockerfile($this->input->post('damage_pass_certi_temp'));
                $damage_pass_certi = $damagePassCerti["upload_status"] ? $damagePassCerti["uploaded_path"] : null;
            } else {
                $damagePassCerti = cifileupload("damage_pass_certi");
                $damage_pass_certi = $damagePassCerti["upload_status"] ? $damagePassCerti["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDPC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            if (strlen($this->input->post("hs_mrksht_temp")) > 0) {
                $hsMrksht = movedigilockerfile($this->input->post('hs_mrksht_temp'));
                $hs_mrksht = $hsMrksht["upload_status"] ? $hsMrksht["uploaded_path"] : null;
            } else {
                $hsMrksht = cifileupload("hs_mrksht");
                $hs_mrksht = $hsMrksht["upload_status"] ? $hsMrksht["uploaded_path"] : null;
            }
        }

        $uploadedFiles = array(
            "photo_of_the_candidate_old" => strlen($photo_of_the_candidate) ? $photo_of_the_candidate : $this->input->post("photo_of_the_candidate_old"),
            "candidate_sign_old" => strlen($candidate_sign) ? $candidate_sign : $this->input->post("candidate_sign_old"),
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.photo_of_the_candidate_type' => $this->input->post("photo_of_the_candidate_type"),
                'form_data.candidate_sign_type' => $this->input->post("candidate_sign_type"),
                'form_data.photo_of_the_candidate' => strlen($photo_of_the_candidate) ? $photo_of_the_candidate : $this->input->post("photo_of_the_candidate_old"),
                'form_data.candidate_sign' => strlen($candidate_sign) ? $candidate_sign : $this->input->post("candidate_sign_old"),
            );

            if (($dbrow->form_data->condi_of_doc == "FULLY DAMAGED") || ($dbrow->form_data->condi_of_doc == "LOST")) {
                if (!empty($this->input->post("fir_type"))) {
                    $data["form_data.fir_type"] = $this->input->post("fir_type");
                    $data["form_data.fir"] = strlen($fir) ? $fir : $this->input->post("fir_old");
                }
            } else {
                $data["form_data.fir_type"] = "";
                $data["form_data.fir"] = "";
            }

            if ($dbrow->form_data->condi_of_doc == "LOST") {
                if (!empty($this->input->post("paper_advertisement_type"))) {
                    $data["form_data.paper_advertisement_type"] = $this->input->post("paper_advertisement_type");
                    $data["form_data.paper_advertisement"] = strlen($paper_advertisement) ? $paper_advertisement : $this->input->post("paper_advertisement_old");
                }
            } else {
                $data["form_data.paper_advertisement_type"] = "";
                $data["form_data.paper_advertisement"] = "";
            }

            if (($dbrow->service_data->service_id == "AHSECDRC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
                if (!empty($this->input->post("hslc_tenth_mrksht_type"))) {
                    $data["form_data.hslc_tenth_mrksht_type"] = $this->input->post("hslc_tenth_mrksht_type");
                    $data["form_data.hslc_tenth_mrksht"] = strlen($hslc_tenth_mrksht) ? $hslc_tenth_mrksht : $this->input->post("hslc_tenth_mrksht_old");
                }
            } else {
                $data["form_data.hslc_tenth_mrksht_type"] = "";
                $data["form_data.hslc_tenth_mrksht"] = "";
            }

            if (($dbrow->service_data->service_id == "AHSECDRC") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
                if (!empty($this->input->post("damage_reg_card_type"))) {
                    $data["form_data.damage_reg_card_type"] = $this->input->post("damage_reg_card_type");
                    $data["form_data.damage_reg_card"] = strlen($damage_reg_card) ? $damage_reg_card : $this->input->post("damage_reg_card_old");
                }
            } else {
                $data["form_data.damage_reg_card_type"] = "";
                $data["form_data.damage_reg_card"] = "";
            }

            if (($dbrow->service_data->service_id == "AHSECDADM") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
                if (!empty($this->input->post("damage_admit_card_type"))) {
                    $data["form_data.damage_admit_card_type"] = $this->input->post("damage_admit_card_type");
                    $data["form_data.damage_admit_card"] = strlen($damage_admit_card) ? $damage_admit_card : $this->input->post("damage_admit_card_old");
                }
            } else {
                $data["form_data.damage_admit_card_type"] = "";
                $data["form_data.damage_admit_card"] = "";
            }

            if ((($dbrow->service_data->service_id == "AHSECDADM") || ($dbrow->service_data->service_id == "AHSECDMRK") || ($dbrow->service_data->service_id == "AHSECDPC")) && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
                if (!empty($this->input->post("hs_reg_card_type"))) {
                    $data["form_data.hs_reg_card_type"] = $this->input->post("hs_reg_card_type");
                    $data["form_data.hs_reg_card"] = strlen($hs_reg_card) ? $hs_reg_card : $this->input->post("hs_reg_card_old");
                }
            } else {
                $data["form_data.hs_reg_card_type"] = "";
                $data["form_data.hs_reg_card"] = "";
            }

            if (($dbrow->service_data->service_id == "AHSECDMRK") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
                if (!empty($this->input->post("damage_mrksht_type"))) {
                    $data["form_data.damage_mrksht_type"] = $this->input->post("damage_mrksht_type");
                    $data["form_data.damage_mrksht"] = strlen($damage_mrksht) ? $damage_mrksht : $this->input->post("damage_mrksht_old");
                }
            } else {
                $data["form_data.damage_mrksht_type"] = "";
                $data["form_data.damage_mrksht"] = "";
            }

            if (($dbrow->service_data->service_id == "AHSECDMRK") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
                if (!empty($this->input->post("hs_admit_card_type"))) {
                    $data["form_data.hs_admit_card_type"] = $this->input->post("hs_admit_card_type");
                    $data["form_data.hs_admit_card"] = strlen($hs_admit_card) ? $hs_admit_card : $this->input->post("hs_admit_card_old");
                }
            } else {
                $data["form_data.hs_admit_card_type"] = "";
                $data["form_data.hs_admit_card"] = "";
            }

            if (($dbrow->service_data->service_id == "AHSECDPC") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
                if (!empty($this->input->post("damage_pass_certi_type"))) {
                    $data["form_data.damage_pass_certi_type"] = $this->input->post("damage_pass_certi_type");
                    $data["form_data.damage_pass_certi"] = strlen($damage_pass_certi) ? $damage_pass_certi : $this->input->post("damage_pass_certi_old");
                }
            } else {
                $data["form_data.damage_pass_certi_type"] = "";
                $data["form_data.damage_pass_certi"] = "";
            }

            if (($dbrow->service_data->service_id == "AHSECDPC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
                if (!empty($this->input->post("hs_mrksht_type"))) {
                    $data["form_data.hs_mrksht_type"] = $this->input->post("hs_mrksht_type");
                    $data["form_data.hs_mrksht"] = strlen($hs_mrksht) ? $hs_mrksht : $this->input->post("hs_mrksht_old");
                }
            } else {
                $data["form_data.hs_mrksht_type"] = "";
                $data["form_data.hs_mrksht"] = "";
            }

            $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/duplicatecertificateahsec/registration/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()

    public function preview($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array(
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('duplicatecertificateahsec/preview', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/duplicatecertificateahsec/registration');
        } //End of if else
    } //End of preview()

    public function applicationpreview($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array(
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('duplicatecertificateahsec/duplicatecertificateahseccapplication_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/duplicatecertificateahsec/registration');
        } //End of if else
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
            $this->load->view('duplicatecertificateahsec/dc_app_admin_pre', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/duplicatecertificateahsec/registration');
        } //End of if else
    }

    public function track($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (isset($dbRow->form_data->edistrict_ref_no) && !empty($dbRow->form_data->edistrict_ref_no)) {
            $this->load->helper('trackstatus');
            fetchEdistrictData($dbRow->form_data->edistrict_ref_no);
            $dbRow = $this->registration_model->get_by_doc_id($objId);
        }
        if (count((array) $dbRow)) {
            $data = array(
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('duplicatecertificateahsec/duplicatecertificateahsectrack_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/duplicatecertificateahsec/registration');
        } //End of if else
    } //End of track()

    public function queryform($objId = null)
    {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->registration_model->get_row(array("_id" => new ObjectId($objId), "service_data.appl_status" => "QS"));
            if ($dbRow) {
                $data = array(
                    "dbrow" => $dbRow,
                );
                $data["sessions"] = $this->fetchsessions(15);
                $this->load->view('includes/frontend/header');
                $this->load->view('duplicatecertificateahsec/duplicate_cart_query_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/duplicatecertificateahsec/registration');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/duplicatecertificateahsec/registration');
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
        $dbrow = $this->registration_model->get_by_doc_id($objId);
        $condi_of_doc = $dbrow->form_data->condi_of_doc;

        $this->form_validation->set_rules('applicant_name', 'Applicant name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('father_name', 'Father Name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('mother_name', 'Mother name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|strip_tags|valid_email');

        $this->form_validation->set_rules('comp_permanent_address', 'Complete Permanent Address', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('pa_pincode', 'Pincode', 'trim|numeric|required|xss_clean|strip_tags|max_length[6]');
        $this->form_validation->set_rules('pa_state', 'State', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_district', 'District', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('ahsec_reg_session', 'AHSEC reg. session', 'trim|required|xss_clean|max_length[255]');
        $this->form_validation->set_rules('ahsec_reg_no', 'AHSEC reg. no', 'trim|required|xss_clean|strip_tags');
        if ($dbrow->service_data->service_id != "AHSECDRC") {
            $this->form_validation->set_rules('ahsec_yearofpassing', 'AHSEC year of passing', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('ahsec_admit_roll', 'AHSEC admit roll', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('ahsec_admit_no', 'AHSEC admit no', 'trim|required|xss_clean|strip_tags');
        }

        $this->form_validation->set_rules('board_seaking_adm', 'Board Seeking Admission', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('course_seaking_adm', 'Course Seeking Admission', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('state_seaking_adm', 'State Seeking Admission', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('reason_seaking_adm', 'Describe Reason for Seeking Migration', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');

        $this->form_validation->set_rules('condi_of_doc', 'Condition of Documents', 'trim|required');

        $this->form_validation->set_rules('postal', 'Delivery Preference', 'trim|required');

        $this->form_validation->set_rules('photo_of_the_candidate_type', 'Passport size photograph', 'required');
        $this->form_validation->set_rules('candidate_sign_type', 'Applicant Signature', 'required');

        if (($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST")) {
            $this->form_validation->set_rules('fir_type', 'FIR Copy', 'required');
            if (empty($this->input->post("fir_old"))) {
                if ($_FILES['fir']['name'] == "") {
                    $this->form_validation->set_rules('fir', 'FIR Copy Document', 'required');
                }
            }
        }

        if ($condi_of_doc == "LOST") {
            if (empty($this->input->post("paper_advertisement_old"))) {
                $this->form_validation->set_rules('paper_advertisement_type', 'Paper Advertisement Copy', 'required');
                if ($_FILES['paper_advertisement']['name'] == "") {
                    $this->form_validation->set_rules('paper_advertisement', 'Paper Advertisement Copy Document', 'required');
                }
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDRC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            $this->form_validation->set_rules('hslc_tenth_mrksht_type', 'HSLC/10thMarksheet', 'required');
            if (empty($this->input->post("hslc_tenth_mrksht_old"))) {
                if ($_FILES['hslc_tenth_mrksht']['name'] == "") {
                    $this->form_validation->set_rules('hslc_tenth_mrksht', 'HSLC/10thMarksheet Document', 'required');
                }
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDADM") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            $this->form_validation->set_rules('damage_admit_card_type', 'Damaged portion of the Admit Card Copy', 'required');
            if (empty($this->input->post("damage_admit_card_old"))) {
                if ($_FILES['damage_admit_card']['name'] == "") {
                    $this->form_validation->set_rules('damage_admit_card', 'Damaged portion of the Admit Card Copy Document', 'required');
                }
            }
        }

        if ((($dbrow->service_data->service_id == "AHSECDADM") || ($dbrow->service_data->service_id == "AHSECDMRK") || ($dbrow->service_data->service_id == "AHSECDPC")) && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            $this->form_validation->set_rules('hs_reg_card_type', 'HS Registration Card', 'required');
            if (empty($this->input->post("hs_reg_card_old"))) {
                if ($_FILES['hs_reg_card']['name'] == "") {
                    $this->form_validation->set_rules('hs_reg_card', 'HS Registration Card Document', 'required');
                }
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDMRK") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            $this->form_validation->set_rules('damage_mrksht_type', 'Damaged portion of the Marksheet Copy', 'required');
            if (empty($this->input->post("damage_mrksht_old"))) {
                if ($_FILES['damage_mrksht']['name'] == "") {
                    $this->form_validation->set_rules('damage_mrksht', 'Damaged portion of the Marksheet Copy Document', 'required');
                }
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDMRK") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            $this->form_validation->set_rules('hs_admit_card_type', 'HS Admit Card', 'required');
            if (empty($this->input->post("hs_admit_card_old"))) {
                if ($_FILES['hs_admit_card']['name'] == "") {
                    $this->form_validation->set_rules('hs_admit_card', 'HS Admit Card Document', 'required');
                }
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDPC") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            $this->form_validation->set_rules('damage_pass_certi_type', 'Damaged portion of the Pass Certificate Copy', 'required');
            if (empty($this->input->post("damage_pass_certi_old"))) {
                if ($_FILES['damage_pass_certi']['name'] == "") {
                    $this->form_validation->set_rules('damage_pass_certi', 'Damaged portion of the Pass Certificate Copy Document', 'required');
                }
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDPC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            $this->form_validation->set_rules('hs_mrksht_type', 'HS Marksheet', 'required');
            if (empty($this->input->post("hs_mrksht_old"))) {
                if ($_FILES['hs_mrksht']['name'] == "") {
                    $this->form_validation->set_rules('hs_mrksht', 'HS Marksheet Document', 'required');
                }
            }
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $photoOfTheCandidate = cifileupload("photo_of_the_candidate");
        $photo_of_the_candidate = $photoOfTheCandidate["upload_status"] ? $photoOfTheCandidate["uploaded_path"] : null;

        $candidateSign = cifileupload("candidate_sign");
        $candidate_sign = $candidateSign["upload_status"] ? $candidateSign["uploaded_path"] : null;

        if (($dbrow->form_data->condi_of_doc == "FULLY DAMAGED") || ($dbrow->form_data->condi_of_doc == "LOST")) {
            if (strlen($this->input->post("fir_temp")) > 0) {
                $fir = movedigilockerfile($this->input->post('fir_temp'));
                $fir = $fir["upload_status"] ? $fir["uploaded_path"] : null;
            } else {
                $fir = cifileupload("fir");
                $fir = $fir["upload_status"] ? $fir["uploaded_path"] : null;
            }
        }

        if ($dbrow->form_data->condi_of_doc == "LOST") {
            if (strlen($this->input->post("paper_advertisement_temp")) > 0) {
                $paperAdvertisement = movedigilockerfile($this->input->post('paper_advertisement_temp'));
                $paper_advertisement = $paperAdvertisement["upload_status"] ? $paperAdvertisement["uploaded_path"] : null;
            } else {
                $paperAdvertisement = cifileupload("paper_advertisement");
                $paper_advertisement = $paperAdvertisement["upload_status"] ? $paperAdvertisement["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDRC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            if (strlen($this->input->post("hslc_tenth_mrksht_temp")) > 0) {
                $hslcTenthMrksht = movedigilockerfile($this->input->post('hslc_tenth_mrksht_temp'));
                $hslc_tenth_mrksht = $hslcTenthMrksht["upload_status"] ? $hslcTenthMrksht["uploaded_path"] : null;
            } else {
                $hslcTenthMrksht = cifileupload("hslc_tenth_mrksht");
                $hslc_tenth_mrksht = $hslcTenthMrksht["upload_status"] ? $hslcTenthMrksht["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDRC") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            if (strlen($this->input->post("damage_reg_card_temp")) > 0) {
                $damageRegCard = movedigilockerfile($this->input->post('damage_reg_card_temp'));
                $damage_reg_card = $damageRegCard["upload_status"] ? $damageRegCard["uploaded_path"] : null;
            } else {
                $damageRegCard = cifileupload("damage_reg_card");
                $damage_reg_card = $damageRegCard["upload_status"] ? $damageRegCard["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDADM") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            if (strlen($this->input->post("damage_admit_card_temp")) > 0) {
                $damageAdmitCard = movedigilockerfile($this->input->post('damage_admit_card_temp'));
                $damage_admit_card = $damageAdmitCard["upload_status"] ? $damageAdmitCard["uploaded_path"] : null;
            } else {
                $damageAdmitCard = cifileupload("damage_admit_card");
                $damage_admit_card = $damageAdmitCard["upload_status"] ? $damageAdmitCard["uploaded_path"] : null;
            }
        }

        if ((($dbrow->service_data->service_id == "AHSECDADM") || ($dbrow->service_data->service_id == "AHSECDMRK") || ($dbrow->service_data->service_id == "AHSECDPC")) && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            if (strlen($this->input->post("hs_reg_card_temp")) > 0) {
                $hsRegCard = movedigilockerfile($this->input->post('hs_reg_card_temp'));
                $hs_reg_card = $hsRegCard["upload_status"] ? $hsRegCard["uploaded_path"] : null;
            } else {
                $hsRegCard = cifileupload("hs_reg_card");
                $hs_reg_card = $hsRegCard["upload_status"] ? $hsRegCard["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDMRK") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            if (strlen($this->input->post("damage_mrksht_temp")) > 0) {
                $damageMrksht = movedigilockerfile($this->input->post('damage_mrksht_temp'));
                $damage_mrksht = $damageMrksht["upload_status"] ? $damageMrksht["uploaded_path"] : null;
            } else {
                $damageMrksht = cifileupload("damage_mrksht");
                $damage_mrksht = $damageMrksht["upload_status"] ? $damageMrksht["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDMRK") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            if (strlen($this->input->post("hs_admit_card_temp")) > 0) {
                $hsAdmitCard = movedigilockerfile($this->input->post('hs_admit_card_temp'));
                $hs_admit_card = $hsAdmitCard["upload_status"] ? $hsAdmitCard["uploaded_path"] : null;
            } else {
                $hsAdmitCard = cifileupload("hs_admit_card");
                $hs_admit_card = $hsAdmitCard["upload_status"] ? $hsAdmitCard["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDMRK") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
            if (strlen($this->input->post("damage_pass_certi_temp")) > 0) {
                $damagePassCerti = movedigilockerfile($this->input->post('damage_pass_certi_temp'));
                $damage_pass_certi = $damagePassCerti["upload_status"] ? $damagePassCerti["uploaded_path"] : null;
            } else {
                $damagePassCerti = cifileupload("damage_pass_certi");
                $damage_pass_certi = $damagePassCerti["upload_status"] ? $damagePassCerti["uploaded_path"] : null;
            }
        }

        if (($dbrow->service_data->service_id == "AHSECDPC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
            if (strlen($this->input->post("hs_mrksht_temp")) > 0) {
                $hsMrksht = movedigilockerfile($this->input->post('hs_mrksht_temp'));
                $hs_mrksht = $hsMrksht["upload_status"] ? $hsMrksht["uploaded_path"] : null;
            } else {
                $hsMrksht = cifileupload("hs_mrksht");
                $hs_mrksht = $hsMrksht["upload_status"] ? $hsMrksht["uploaded_path"] : null;
            }
        }

        $uploadedFiles = array(
            "photo_of_the_candidate_old" => strlen($photo_of_the_candidate) ? $photo_of_the_candidate : $this->input->post("photo_of_the_candidate_old"),
            "candidate_sign_old" => strlen($candidate_sign) ? $candidate_sign : $this->input->post("candidate_sign_old"),
        );

        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->queryform($objId);
        } else {

            $dbRow = $this->registration_model->get_by_doc_id($objId);
            if (count((array) $dbRow)) {

                $data = array(
                    'form_data.photo_of_the_candidate_type' => $this->input->post("photo_of_the_candidate_type"),
                    'form_data.candidate_sign_type' => $this->input->post("candidate_sign_type"),
                    'form_data.photo_of_the_candidate' => strlen($photo_of_the_candidate) ? $photo_of_the_candidate : $this->input->post("photo_of_the_candidate_old"),
                    'form_data.candidate_sign' => strlen($candidate_sign) ? $candidate_sign : $this->input->post("candidate_sign_old"),
                );

                if (($dbrow->form_data->condi_of_doc == "FULLY DAMAGED") || ($dbrow->form_data->condi_of_doc == "LOST")) {
                    if (!empty($this->input->post("fir_type"))) {
                        $data["form_data.fir_type"] = $this->input->post("fir_type");
                        $data["form_data.fir"] = strlen($fir) ? $fir : $this->input->post("fir_old");
                    }
                }

                if ($dbrow->form_data->condi_of_doc == "LOST") {
                    if (!empty($this->input->post("paper_advertisement_type"))) {
                        $data["form_data.paper_advertisement_type"] = $this->input->post("paper_advertisement_type");
                        $data["form_data.paper_advertisement"] = strlen($paper_advertisement) ? $paper_advertisement : $this->input->post("paper_advertisement_old");
                    }
                }

                if (($dbrow->service_data->service_id == "AHSECDRC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
                    if (!empty($this->input->post("hslc_tenth_mrksht_type"))) {
                        $data["form_data.hslc_tenth_mrksht_type"] = $this->input->post("hslc_tenth_mrksht_type");
                        $data["form_data.hslc_tenth_mrksht"] = strlen($hslc_tenth_mrksht) ? $hslc_tenth_mrksht : $this->input->post("hslc_tenth_mrksht_old");
                    }
                }

                if (($dbrow->service_data->service_id == "AHSECDRC") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
                    if (!empty($this->input->post("damage_reg_card_type"))) {
                        $data["form_data.damage_reg_card_type"] = $this->input->post("damage_reg_card_type");
                        $data["form_data.damage_reg_card"] = strlen($damage_reg_card) ? $damage_reg_card : $this->input->post("damage_reg_card_old");
                    }
                }

                if (($dbrow->service_data->service_id == "AHSECDADM") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
                    if (!empty($this->input->post("damage_admit_card_type"))) {
                        $data["form_data.damage_admit_card_type"] = $this->input->post("damage_admit_card_type");
                        $data["form_data.damage_admit_card"] = strlen($damage_admit_card) ? $damage_admit_card : $this->input->post("damage_admit_card_old");
                    }
                }

                if ((($dbrow->service_data->service_id == "AHSECDADM") || ($dbrow->service_data->service_id == "AHSECDMRK") || ($dbrow->service_data->service_id == "AHSECDPC")) && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
                    if (!empty($this->input->post("hs_reg_card_type"))) {
                        $data["form_data.hs_reg_card_type"] = $this->input->post("hs_reg_card_type");
                        $data["form_data.hs_reg_card"] = strlen($hs_reg_card) ? $hs_reg_card : $this->input->post("hs_reg_card_old");
                    }
                }

                if (($dbrow->service_data->service_id == "AHSECDMRK") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
                    if (!empty($this->input->post("damage_mrksht_type"))) {
                        $data["form_data.damage_mrksht_type"] = $this->input->post("damage_mrksht_type");
                        $data["form_data.damage_mrksht"] = strlen($damage_mrksht) ? $damage_mrksht : $this->input->post("damage_mrksht_old");
                    }
                }

                if (($dbrow->service_data->service_id == "AHSECDMRK") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
                    if (!empty($this->input->post("hs_admit_card_type"))) {
                        $data["form_data.hs_admit_card_type"] = $this->input->post("hs_admit_card_type");
                        $data["form_data.hs_admit_card"] = strlen($hs_admit_card) ? $hs_admit_card : $this->input->post("hs_admit_card_old");
                    }
                }

                if (($dbrow->service_data->service_id == "AHSECDPC") && ($condi_of_doc == "PARTIALLY DAMAGED")) {
                    if (!empty($this->input->post("damage_pass_certi_type"))) {
                        $data["form_data.damage_pass_certi_type"] = $this->input->post("damage_pass_certi_type");
                        $data["form_data.damage_pass_certi"] = strlen($damage_pass_certi) ? $damage_pass_certi : $this->input->post("damage_pass_certi_old");
                    }
                }

                if (($dbrow->service_data->service_id == "AHSECDPC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))) {
                    if (!empty($this->input->post("hs_mrksht_type"))) {
                        $data["form_data.hs_mrksht_type"] = $this->input->post("hs_mrksht_type");
                        $data["form_data.hs_mrksht"] = strlen($hs_mrksht) ? $hs_mrksht : $this->input->post("hs_mrksht_old");
                    }
                }

                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Query submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Query submitted",
                    "remarks" => "Query submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $data = array(
                    "service_data.appl_status" => "QA",
                    'processing_history' => $processing_history,
                    'status' => "QUERY_ANSWERED",
                );

                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

                $this->session->set_flashdata('success', 'Your application has been successfully updated');
                redirect('spservices/duplicatecertificateahsec/registration/preview/' . $objId);
            } else {
                $this->session->set_flashdata('fail', 'Unable to update data!!! Please try again.');
                $this->index();
            } //End of if else
        } //End of if else
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
                $this->load->view('duplicatecertificateahsec/duplicate_cert_recorrection_view', $data);
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

                if ($dbRow->service_data->service_id == "AHSECDRC")
                    $userFilter = array('user_services.service_code' => $dbRow->service_data->service_id, 'user_roles.role_code' => 'DS_AHSEC');
                else
                    $userFilter = array('user_services.service_code' => $dbRow->service_data->service_id, 'user_roles.role_code' => 'DCE_AHSEC');
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
                redirect('spservices/duplicatecertificateahsec/registration/applicationpreview/' . $objId);
            } else {
                $this->session->set_flashdata('fail', 'Unable to update data!!! Please try again.');
                $this->request_for_recorrection($objId);
            } //End of if else
        } //End of if else
    } //End of querysubmit()

    public function getID($length, $service_id)
    {
        $rtps_trans_id = $this->generateID($length, $service_id);
        while ($this->registration_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length, $service_id);
        } //End of while()
        return $rtps_trans_id;
    } //End of getID()

    public function generateID($length, $service_id)
    {
        $date = date('Y');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-" . $service_id . "/" . $date . "/" . $number;
        return $str;
    } //End of generateID()

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
                    'processing_history' => $processing_history,
                );
                $this->registration_model->update($obj, $data_to_update);

                //Sending submission SMS
                $nowTime = date('Y-m-d H:i:s');
                $sms = array(
                    "mobile" => (int) $dbRow->form_data->mobile,
                    "applicant_name" => $dbRow->form_data->applicant_name,
                    "service_name" => $dbRow->service_data->service_name,
                    "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                    "app_ref_no" => $dbRow->service_data->appl_ref_no,
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
            $qrcode_path = 'storage/docs/ahsec_common_qr/';
            $filename = str_replace("/", "-", $objId);
            $qrname = $filename . ".png";
            $file_name = $qrcode_path . $qrname;

            $filter1 = array(
                "registration_number" => $dbRow->form_data->ahsec_reg_no,
                "registration_session" => $dbRow->form_data->ahsec_reg_session,
            );
            $ahsecregistration_data = $this->ahsecregistration_model->get_row($filter1);

            if (($dbRowArray[0]->service_data->service_id == "AHSECDADM") || ($dbRowArray[0]->service_data->service_id == "AHSECDMRK") || ($dbRow->service_data->service_id == "AHSECDPC")) {
                $filter2 = array(
                    "Registration_No" => $dbRow->form_data->ahsec_reg_no,
                    "Registration_Session" => $dbRow->form_data->ahsec_reg_session,
                    "Roll" => $dbRow->form_data->ahsec_admit_roll,
                    "No" => $dbRow->form_data->ahsec_admit_no,
                    "Year_of_Examination" => $dbRow->form_data->ahsec_yearofpassing,
                );
                $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);

                $marksheet_data['Mark_Sheet_No'] = $ahsecmarksheet_data->Mark_Sheet_No;
                $marksheet_data['Admit_Card_Serial_No'] = $ahsecmarksheet_data->Admit_Card_Serial_No;
                $marksheet_data['Certificate_Serial_No'] = $ahsecmarksheet_data->Certificate_Serial_No;
                $marksheet_data['Year_of_Examination'] = $ahsecmarksheet_data->Year_of_Examination;
                $marksheet_data['commencing_day'] = $ahsecmarksheet_data->commencing_day;
                $marksheet_data['commencing_month'] = $ahsecmarksheet_data->commencing_month;
                $marksheet_data['Stream'] = $ahsecmarksheet_data->Stream;
                $marksheet_data['Date'] = $ahsecmarksheet_data->Marksheet_Date;
                $marksheet_data['Candidate_Name'] = $ahsecmarksheet_data->Candidate_Name;
                $marksheet_data['School_Name'] = $ahsecmarksheet_data->School_College_Name;
                $marksheet_data['Roll'] = $ahsecmarksheet_data->Roll;
                $marksheet_data['No'] = $ahsecmarksheet_data->No;
                $marksheet_data['Remarks1'] = $ahsecmarksheet_data->Remarks1;
                $j = 1;
                for ($i = 1; $i <= 16; $i++) {
                    if (!empty($ahsecmarksheet_data->{'Sub' . $i . '_Code'})) {
                        $marksheet_data['Sub' . $j . '_Code'] = $ahsecmarksheet_data->{'Sub' . $i . '_Code'};
                        $marksheet_data['Sub' . $j . '_Pap_Indicator'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pap_Indicator'};
                        $marksheet_data['Sub' . $j . '_Name'] = $ahsecmarksheet_data->{'Sub' . $i . '_Name'};
                        $append_grace_mark = (isset($ahsecmarksheet_data->{'Sub' . $i . '_Grace_Marks'}) && !empty($ahsecmarksheet_data->{'Sub' . $i . '_Grace_Marks'})) ? '+' . $ahsecmarksheet_data->{'Sub' . $i . '_Grace_Marks'} : '';
                        $marksheet_data['Sub' . $j . '_Th_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Th_Marks'}. $append_grace_mark;
                        $marksheet_data['Sub' . $j . '_Pr_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pr_Marks'};
                        $marksheet_data['Sub' . $j . '_Grace_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Grace_Marks'};
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

            if ((($dbRowArray[0]->service_data->service_id == 'AHSECDRC') || ($dbRowArray[0]->service_data->service_id == 'AHSECDADM') || ($dbRowArray[0]->service_data->service_id == 'AHSECDMRK') || ($dbRowArray[0]->service_data->service_id == 'AHSECDPC')) && ($dbRowArray[0]->service_data->appl_status == "D")) {
                $this->load->view('includes/frontend/header');
                if ($dbRowArray[0]->service_data->service_id == 'AHSECDRC') {
                    $this->load->view('duplicatecertificateahsec/reg_output_certificate', array('data' => $dbRowArray, 'qr' => $file_name, 'certificate_no' => $dbRowArray[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'user_type' => 'citizen'));
                } else if ($dbRowArray[0]->service_data->service_id == 'AHSECDADM') {
                    $this->load->view('duplicatecertificateahsec/output_certificate_adm', array('data' => $dbRowArray, 'qr' => $file_name, 'certificate_no' => $dbRowArray[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'citizen'));
                } else if ($dbRowArray[0]->service_data->service_id == 'AHSECDMRK') {
                    $this->load->view('duplicatecertificateahsec/marksheet_output_certificate', array('data' => $dbRowArray, 'qr' => $file_name, 'certificate_no' => $dbRowArray[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'citizen'));
                } else if ($dbRowArray[0]->service_data->service_id == 'AHSECDPC') {
                    $this->load->view('duplicatecertificateahsec/output_certificate_pc', array('data' => $dbRowArray, 'qr' => $file_name, 'certificate_no' => $dbRowArray[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'citizen'));
                }
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('errmessage', 'Something went wrong!, Please try later.');
                $this->my_transactions();
            }
        }
    } //End of download_certificate()
} //End of Castecertificate
