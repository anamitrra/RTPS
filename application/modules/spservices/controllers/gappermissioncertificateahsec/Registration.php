<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps
{
    private $serviceName = "Application for Gap Permission";
    private $serviceAssameseName = "গেপ অনুমতিৰ বাবে আবেদন";
    private $serviceId = "AHSECGAP";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gappermissioncertificateahsec/registration_model');
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
        $currentYear = date('Y') - 3;
        // var_dump($currentYear);
        $yearArray = array();
        for ($i = $currentYear; $i >= ($currentYear - $no_of_year); $i--) {
            $present_year = $i;
            $next_year = sprintf("%02d", (substr($i, -2) + 1));
            $yearArray[$i . '-' . ($next_year)] = $i . '-' . ($next_year);
        }
        // var_dump($yearArray);
        return $yearArray;
    }
    public function index($obj_id = null)
    {
        if ($obj_id == null) {
            $this->my_transactions();
        } else {
            $data = array(
                "pageTitle" => $this->serviceName,
                "PageTiteAssamese" => $this->serviceAssameseName,
            );
            $filter = array(
                "_id" => new ObjectId($obj_id),
                "service_data.appl_status" => "DRAFT",
            );
            $data["dbrow"] = $this->registration_model->get_row($filter);
            $data['usser_type'] = $this->slug;
            $data["states"] = $this->registration_model->getStates();
            $data["districts"] = $this->registration_model->getDistricts();
            $data["sessions"] = $this->fetchsessions(15);
            // pre($data["dbrow"]);
            // echo "asdasd";
            // exit();
            $this->load->view('includes/frontend/header');
            $this->load->view('gappermissioncertificateahsec/section_one_create', $data);
            $this->load->view('includes/frontend/footer');
        }
    } //End of index()
    public function details()
    {
        //check_application_count_for_citizen();
        $data = array(
            "pageTitle" => $this->serviceName,
            "PageTiteAssamese" => $this->serviceAssameseName,
            // "sessions"=>$sessions
        );
        $data["sessions"] = $this->fetchsessions(15);
        $data['usser_type'] = $this->slug;
        // pre($data);
        $this->load->view('includes/frontend/header');
        $this->load->view('gappermissioncertificateahsec/search_details', $data);
        $this->load->view('includes/frontend/footer');
    }
    public function searchdetails_submit()
    {
        {
            // echo $service_id;
            // exit();
            $this->form_validation->set_rules('ahsec_reg_session', 'AHSEC reg. session', 'trim|required|xss_clean|max_length[255]');
            $this->form_validation->set_rules('ahsec_reg_no', 'AHSEC reg. no', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $this->searchdetails($service_id);
            } else {
                $filter1 = array(
                    "registration_number" => (int) $this->input->post("ahsec_reg_no"),
                    "registration_session" => $this->input->post("ahsec_reg_session"),
                );
                $ahsecregistration_data = $this->ahsecregistration_model->get_row($filter1);
                if (!empty($ahsecregistration_data)) {
                    //If already record submitted
                    $filter = array(
                        "form_data.ahsec_reg_session" => $this->input->post("ahsec_reg_session"),
                        "form_data.ahsec_reg_no" => (int) $this->input->post("ahsec_reg_no"),
                        //newly added
                        "service_data.service_id" => "AHSECGAP",
                        // "form_data.ahsec_admit_roll" => (int) $this->input->post("ahsec_admit_roll"),
                        // "form_data.ahsec_admit_no" => (int) $this->input->post("ahsec_admit_no")
                    );
                    $dbrow_data = $this->registration_model->get_row($filter);
                    // var_dump($dbrow_data);
                    // exit();
                    if (!empty($dbrow_data)) {
                        $objectId = $dbrow_data->_id->{'$id'};
                        $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                        redirect('spservices/gappermissioncertificateahsec/registration/index/' . $objectId);
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
                    $service_data = [
                        "department_id" => "1469",
                        "department_name" => "Assam Higher Secondary Education Council",
                        "service_id" => $this->serviceId,
                        "service_name" => $this->serviceName,
                        "appl_id" => $app_id,
                        "appl_ref_no" => $appl_ref_no,
                        "submission_mode" => $submissionMode, //kiosk, online, in-person
                        "applied_by" => $apply_by,
                        "submission_location" => "Assam Higher Secondary Education Council",
                        "submission_date" => "",
                        "service_timeline" => "30 Days",
                        "appl_status" => "DRAFT",
                        "district" => "Guwahati",
                    ];
                    // pre($service_data);
                    $form_data = [
                        'applicant_name' => $ahsecregistration_data->candidate_name,
                        'father_name' => $ahsecregistration_data->father_name ?? '',
                        'mother_name' => $ahsecregistration_data->mother_name ?? '',
                        'verify_status' => 'verified',
                        'ahsec_reg_session' => $ahsecregistration_data->registration_session,
                        'ahsec_reg_no' => $ahsecregistration_data->registration_number,
                        'ahsec_inst_name' => $ahsecregistration_data->institution_name,
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
                        $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                        redirect('spservices/gappermissioncertificateahsec/registration/index/' . $objectId);
                    } else {
                        $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                        redirect('spservices/gappermissioncertificateahsec/registration/index/' . $service_id);
                    }
                } else {
                    $this->session->set_flashdata('error', 'No record found.');
                    $this->searchdetails();
                    // redirect('spservices/migrationcertificateahsec/registration/searchdetails/' . $service_id);
                }
            }
        }
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
    public function submit()
    {
        if ($this->input->post("step") == 2) {
            // $this->form_validation->set_rules('board_seaking_adm', 'Board Seeking Admission', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            // $this->form_validation->set_rules('course_seaking_adm', 'Course Seeking Admission', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            // $this->form_validation->set_rules('state_seaking_adm', 'State Seeking Admission', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('reason_gap', 'Describe Reason for GAP', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('postal', 'Delivery Preference', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
            $objId = $this->input->post("obj_id");
            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $obj_id = strlen($objId) ? $objId : null;
                $this->sectionTwo($obj_id);
            } else {
                $this->saveSectionTwo($this->input->post());
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/gappermissioncertificateahsec/registration/sectionThree/' . $objId);
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
            $this->form_validation->set_rules('comp_permanent_address', 'Complete Permanent Address', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
            $this->form_validation->set_rules('pa_pincode', 'Pincode', 'trim|required|xss_clean|strip_tags|max_length[6]');
            $this->form_validation->set_rules('pa_state', 'State', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('pa_district', 'District', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('ahsec_inst_name', 'AHSEC Board', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('ahsec_reg_session', 'AHSEC Registrtion Session', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('ahsec_reg_session', 'AHSEC Registrtion Session', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('ahsec_reg_no', 'Valid AHSEC Registrtion Number', 'trim|required|xss_clean|strip_tags|max_length[255]');
            // $this->form_validation->set_rules('ahsec_yearofpassing', 'Years of Passing of H.S. 2nd Year Examination', 'trim|required|xss_clean|strip_tags');
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
                    redirect('spservices/gappermissioncertificateahsec/registration/sectionTwo/' . $objId);
                } else {
                    $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                    $this->index();
                    exit;
                }
            } //End of if else
        }
    } //End of submit()
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
                // 'form_data.ahsec_yearofpassing' =>  $data['ahsec_yearofpassing'],
                // 'form_data.ahsec_admit_roll' =>  $data['ahsec_admit_roll'],
                // 'form_data.ahsec_admit_no' =>  $data['ahsec_admit_no'],
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
            // 'form_data.board_seaking_adm' => $data['board_seaking_adm'],
            // 'form_data.course_seaking_adm' => $data['course_seaking_adm'],
            // 'form_data.state_seaking_adm' => $data['state_seaking_adm'],
            'form_data.reason_gap' => $data['reason_gap'],
            'form_data.postal' => $data['postal'],
            // 'form_data.ahsec_country_seeking' => $data['ahsec_country_seeking'],
        ];
        $this->registration_model->update_where(['_id' => new ObjectId($objId)], $form_data);
    }
    public function sectionTwo($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "pageTitle" => $this->serviceName,
                "PageTiteAssamese" => $this->serviceAssameseName,
                "obj_id" => $objId,
                "dbrow" => $dbRow,
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('gappermissioncertificateahsec/section_two_create', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/change_institute_ahsec/registration');
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
                "pageTitle" => $this->serviceName,
                "PageTiteAssamese" => $this->serviceAssameseName,
                "obj_id" => $objId,
                "dbrow" => $dbRow,
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('gappermissioncertificateahsec/section_three_create', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/gappermissioncertificateahsec/registration');
        } //End of if else
    } //End of sectionThree()
    public function submitfiles()
    {
        $objId = $this->input->post("obj_id");
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (empty($objId)) {
            $this->my_transactions();
        }
        // $this->form_validation->set_rules('hs_marksheet_type', 'H.S. 2nd Year Marksheet Type', 'required');
        $this->form_validation->set_rules('hs_reg_card_type', 'H.S. Registration Card Type', 'required');
        $this->form_validation->set_rules('photo_of_the_candidate_type', 'Photo of the Candidate Type', 'required');
        $this->form_validation->set_rules('candidate_sign_type', 'Signature of the Candidate Type', 'required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        $photo_of_candidate = '';
        $sign_of_candidate = '';
        $hs_registration_card = '';
        // $hs_marksheet_card ='';
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
            $this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/gappermissioncertificateahsec/registration/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()
}
