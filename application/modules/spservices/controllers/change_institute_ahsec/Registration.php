<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps
{
    private $serviceName = "Application for Change of Institute";
    private $serviceId = "AHSECCHINS";
    private $search = array(
        array(
            'field' => 'ahsec_reg_session',
            'label' => 'AHSEC Reg. Session',
            'rules' => 'required',
        ),
        array(
            'field' => 'ahsec_reg_no',
            'label' => 'AHSEC Registration No',
            'rules' => 'required',
        ),
    );

    private $sectionone = array(
        array(
            'field' => 'applicant_name',
            'label' => 'Applicant Name',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]',
        ),
        array(
            'field' => 'applicant_gender',
            'label' => 'Applicant Gender',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[20]',
        ),
        array(
            'field' => 'father_name',
            'label' => 'Father Name',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]',
        ),
        array(
            'field' => 'mother_name',
            'label' => 'Mother Name',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]',
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|xss_clean|strip_tags|valid_email',
        ),
        array(
            'field' => 'comp_permanent_address',
            'label' => 'Complete Permanent Address',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]',
        ),

        array(
            'field' => 'pa_pincode',
            'label' => 'Pincode',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[6]',
        ),

        array(
            'field' => 'pa_state',
            'label' => 'State',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),

        array(
            'field' => 'pa_district',
            'label' => 'District',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),

        array(
            'field' => 'ahsec_reg_session',
            'label' => 'AHSEC Registrtion Session',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'ahsec_reg_no',
            'label' => 'AHSEC Registrtion Number',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),

    );

    private $sectiontwo = array(
        array(
            'field' => 'college_seaking_adm',
            'label' => 'Institute Seeking Admission',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]',
        ),
        array(
            'field' => 'state_seaking_adm',
            'label' => 'State Seeking Admission',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]',
        ),
        array(
            'field' => 'reason_seaking_adm',
            'label' => 'Reason for Changing Institute',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]',
        ),
        array(
            'field' => 'postal',
            'label' => 'Delivery Preference',
            'rules' => 'trim|required',
        ),
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('change_institute_ahsec/registration_model');
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

    public function index($obj_id = null)
    {
        $data = array("pageTitle" => "Application for Change of Institute",
            "PageTiteAssamese" => "প্ৰতিষ্ঠান পৰিৱৰ্তনৰ বাবে আবেদন");
        $filter = array(
            "_id" => new ObjectId($obj_id),
            "service_data.appl_status" => "DRAFT",
        );
        $data["dbrow"] = $this->registration_model->get_row($filter);
        $data['usser_type'] = $this->slug;
        $data["states"] = $this->registration_model->getStates();
        $data["districts"] = //$this->registration_model->getDistricts();
        $data["sessions"]=$this->fetchsessions(15);
        // pre($data);
        // bibhujal bhatachrya

        $this->load->view('includes/frontend/header');
        $this->load->view('change_institute_ahsec/section_one_create', $data);
        $this->load->view('includes/frontend/footer');
    } //End of index()

    public function searchdetails()
    {
        $data = array("pageTitle" => "Application for Change of Institute",
            "PageTiteAssamese" => "প্ৰতিষ্ঠান পৰিৱৰ্তনৰ বাবে আবেদন");
        $data["sessions"] = $this->fetchsessions(15);
        $data['usser_type'] = $this->slug;

        $this->load->view('includes/frontend/header');
        $this->load->view('change_institute_ahsec/search_details', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function searchdetails_submit()
    {
        $this->form_validation->set_rules($this->search);
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
                    "service_data.service_id" => $this->serviceId,
                    "service_data.appl_status" => "DRAFT",
                );

                $dbrow_data = $this->registration_model->get_row($filter);

                if (!empty($dbrow_data)) {
                    $objectId = $dbrow_data->_id->{'$id'};
                    $this->session->set_flashdata('success', 'Your data has been verified successfully!!');
                    redirect('spservices/change_institute_ahsec/registration/index/' . $objectId);
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
                    "service_timeline" => "7 Days",
                    "appl_status" => "DRAFT",
                ];

                $form_data = [
                    'applicant_name' => $ahsecregistration_data->candidate_name,
                    'father_name' => $ahsecregistration_data->father_name ?? '',
                    'mother_name' => $ahsecregistration_data->mother_name ?? '',

                    'ahsec_reg_session' => $ahsecregistration_data->registration_session,
                    'ahsec_reg_no' => $ahsecregistration_data->registration_number,
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
                    redirect('spservices/change_institute_ahsec/registration/index/' . $objectId);
                } else {
                    $this->session->set_flashdata('fail', 'Something went wrong. Please try again.');
                    $this->searchdetails();
                }

            } else {
                $this->session->set_flashdata('error', 'No record found.');
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
                'form_data.applicant_gender' =>  $data['applicant_gender'],

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
                'form_data.ahsec_admit_roll' => $data['ahsec_admit_roll'],
                'form_data.ahsec_admit_no' => $data['ahsec_admit_no'],
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

            $this->form_validation->set_rules($this->sectiontwo);
            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

            $objId = $this->input->post("obj_id");
            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $obj_id = strlen($objId) ? $objId : null;
                $this->sectionTwo($obj_id);
            } else {
                $this->saveSectionTwo($this->input->post());
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/change_institute_ahsec/registration/sectionThree/' . $objId);
            }

        } else {

            $objId = $this->input->post("obj_id");
            $this->form_validation->set_rules($this->sectionone);
            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $obj_id = strlen($objId) ? $objId : null;
                $this->index($obj_id);
            } else {
                $objId = $this->saveSectionOne($this->input->post());
                if (strlen($objId)) {
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    redirect('spservices/change_institute_ahsec/registration/sectionTwo/' . $objId);
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
                "pageTitle" => "Application for Change of Institute",
                "PageTiteAssamese" => "প্ৰতিষ্ঠান পৰিৱৰ্তনৰ বাবে আবেদন",
                "obj_id" => $objId,
                "dbrow" => $dbRow,
            );

            $this->load->view('includes/frontend/header');
            $this->load->view('change_institute_ahsec/section_two_create', $data);
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
                "pageTitle" => "Application for Change of Institute",
                "PageTiteAssamese" => "প্ৰতিষ্ঠান পৰিৱৰ্তনৰ বাবে আবেদন",
                "obj_id" => $objId,
                "dbrow" => $dbRow,
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('change_institute_ahsec/section_three_create', $data);
            $this->load->view('includes/frontend/footer');

        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/change_institute_ahsec/registration');
        } //End of if else
    } //End of sectionThree()

    public function submitfiles()
    {
        $objId = $this->input->post("obj_id");
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (empty($objId)) {
            $this->my_transactions();
        }
        $this->form_validation->set_rules('hslc_marksheet_type', 'HSLC Marksheet Type', 'required');
        $this->form_validation->set_rules('recom_letter_type', 'Recommendation Letter Type', 'required');
        $this->form_validation->set_rules('hs_one_marksheet_type', 'HS 1st Year Marksheet/Valid supporting document', 'required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $recom_letter_card = '';
        $hs_marksheet_card = '';
        $hslc_marksheet_card = '';
        $photo_of_candidate ='';
        $sign_of_candidate ='';

        $hslcmarksheet = isset($dbRow->form_data->hslc_marksheet) ? $dbRow->form_data->hslc_marksheet : '';
        $hsonemarksheet = isset($dbRow->form_data->hs_one_marksheet) ? $dbRow->form_data->hs_one_marksheet : '';
        $recomletter = isset($dbRow->form_data->recom_letter) ? $dbRow->form_data->recom_letter : '';
        $sign_of_candidate= isset($dbRow->form_data->candidate_sign) ? $dbRow->form_data->candidate_sign : '';  

        if (empty($this->input->post("photo_of_the_candidate_old"))) {
            if ((empty($this->input->post("photo_of_the_candidate_data"))) && ($_FILES['photo_of_the_candidate']['name'] == "")) {
                $this->form_validation->set_rules('photo_of_the_candidate', 'Photo of the Candidate', 'required');
            }
        }
        


        if ($sign_of_candidate == null && $_FILES['candidate_sign']['name'] == "") {
            $this->form_validation->set_rules('candidate_sign', 'Signature of the Candidate is Required', 'required');
        }
        if ($hslcmarksheet == null && $_FILES['hslc_marksheet']['name'] == "") {
            $this->form_validation->set_rules('hslc_marksheet', 'HSLC Marksheet is Required', 'required');
        }
        if ($hsonemarksheet == null && $_FILES['hs_one_marksheet']['name'] == "") {
            $this->form_validation->set_rules('hs_one_marksheet', 'HS 1st Year Marksheet is Required', 'required');
        }
        if ($recomletter == null && $_FILES['recom_letter']['name'] == "") {
            $this->form_validation->set_rules('recom_letter', 'Recommendation Letter is Required', 'required');
        }


        if ($_FILES['photo_of_the_candidate']['name'] != "") {
            $photo_of_the_candidate = cifileupload("photo_of_the_candidate");
            $photo_of_candidate = $photo_of_the_candidate["upload_status"] ? $photo_of_the_candidate["uploaded_path"] : null;
        }
        $photo_of_the_candidate_data = $this->input->post("photo_of_the_candidate_data");
        if((strlen($photo_of_candidate)== '') && (strlen($photo_of_the_candidate_data) > 50)) {
            $candidatePhotoData = str_replace('data:image/jpeg;base64,', '', $photo_of_the_candidate_data);
            $candidatePhotoData2 = str_replace(' ', '+', $candidatePhotoData);
            $candidatePhotoData64 = base64_decode($candidatePhotoData2);

            $fileName = uniqid().'.jpeg';
            $dirPath = 'storage/docs/ahsec_chnginstitute/photos/';
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
            }
            $photo_of_candidate = $dirPath.$fileName;
            file_put_contents(FCPATH.$photo_of_candidate, $candidatePhotoData64);
        }
        else
        {
            $photo_of_candidate=  strlen($photo_of_candidate) ? $photo_of_candidate : ( isset($dbRow->form_data->photo_of_the_candidate) ? $dbRow->form_data->photo_of_the_candidate : '');
        }
        if ($_FILES['candidate_sign']['name'] != "") {
            $candidate_sign = cifileupload("candidate_sign");
            $sign_of_candidate = $candidate_sign["upload_status"] ? $candidate_sign["uploaded_path"] : null;
        }
        // $recom_letter_card = '';
        // $hs_marksheet_card = '';
        // $hslc_marksheet_card = '';
        if (strlen($this->input->post("hs_one_marksheet_temp")) > 0) {
            $hsOneMarksheet = movedigilockerfile($this->input->post('hs_one_marksheet_temp'));
            $hs_marksheet_card = $hsOneMarksheet["upload_status"] ? $hsOneMarksheet["uploaded_path"] : null;
        } else {
            $hsOneMarksheet = cifileupload("hs_one_marksheet");
            $hs_marksheet_card = $hsOneMarksheet["upload_status"] ? $hsOneMarksheet["uploaded_path"] : null;
        }

        if (strlen($this->input->post("hslc_marksheet_temp")) > 0) {
            $hslcMarksheet = movedigilockerfile($this->input->post('hslc_marksheet_temp'));
            $hslc_marksheet_card = $hslcMarksheet["upload_status"] ? $hslcMarksheet["uploaded_path"] : null;
        } else {
            $hslcMarksheet = cifileupload("hslc_marksheet");
            $hslc_marksheet_card = $hslcMarksheet["upload_status"] ? $hslcMarksheet["uploaded_path"] : null;
        }

        if (strlen($this->input->post("recom_letter_temp")) > 0) {
            $recomLetter = movedigilockerfile($this->input->post('recom_letter_temp'));
            $recom_letter_card = $recomLetter["upload_status"] ? $recomLetter["uploaded_path"] : null;
        } else {
            $recomLetter = cifileupload("recom_letter");
            $recom_letter_card = $recomLetter["upload_status"] ? $recomLetter["uploaded_path"] : null;
        }
        $uploadedFiles = array(
            "hslc_marksheet" => strlen($hslc_marksheet_card) ? $hslc_marksheet_card : '',
            "hs_one_marksheet" => strlen($hs_marksheet_card) ? $hs_marksheet_card : '',
            "recom_letter" => strlen($recom_letter_card) ? $recom_letter_card : '',
            "photo_of_candidate" => strlen($photo_of_candidate) ? $photo_of_candidate : '',        
            "candidate_sign" => strlen($sign_of_candidate) ? $sign_of_candidate : '',        
            
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->sectionThree($objId);
        } else {
            $data = array(
                'form_data.hslc_marksheet_type' => $this->input->post("hslc_marksheet_type"),
                'form_data.hslc_marksheet' => strlen($recom_letter_card) ? $recom_letter_card : $dbRow->form_data->hslc_marksheet,
                'form_data.hs_one_marksheet_type' => $this->input->post("hs_one_marksheet_type"),
                'form_data.hs_one_marksheet' => strlen($hs_marksheet_card) ? $hs_marksheet_card : $dbRow->form_data->hs_one_marksheet,
                'form_data.hs_one_marksheet_type' => $this->input->post("recom_letter_type"),
                'form_data.recom_letter' => strlen($recom_letter_card) ? $recom_letter_card : $dbRow->form_data->recom_letter,
                'form_data.photo_of_the_candidate_type' => $this->input->post("photo_of_the_candidate_type"),
                'form_data.photo_of_the_candidate' => strlen($photo_of_candidate) ? $photo_of_candidate : $dbRow->form_data->photo_of_the_candidate,
                'form_data.candidate_sign_type' => $this->input->post("candidate_sign_type"),
                'form_data.candidate_sign' => strlen($sign_of_candidate) ? $sign_of_candidate : $dbRow->form_data->candidate_sign,
            
            );
            $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/change_institute_ahsec/registration/preview/' . $objId);
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
            $this->load->view('change_institute_ahsec/preview', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/change_institute_ahsec/registration');
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
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('change_institute_ahsec/apppreview', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/change_institute_ahsec/registration');
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
            $this->load->view('change_institute_ahsec/track_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/change_institute_ahsec/registration');
        } //End of if else
    } //End of track()

    public function queryform($objId = null)
    {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->registration_model->get_row(array("_id" => new ObjectId($objId), "service_data.appl_status" => "QS"));
            if ($dbRow) {
                $data = array(
                    "service_data.service_name" => $this->serviceName,
                    "dbrow" => $dbRow,
                    "pageTitle" => "Application for Migration Certificate",
                    "PageTiteAssamese" => "প্ৰব্ৰজন প্ৰমাণপত্ৰৰ বাবে আবেদন",
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('change_institute_ahsec/query_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/change_institute_ahsec/registration');
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
        $this->form_validation->set_rules('hslc_marksheet_type', 'HSLC Marksheet Type', 'required');
        $this->form_validation->set_rules('recom_letter_type', 'Recommendation Letter Type', 'required');
        $this->form_validation->set_rules('hs_one_marksheet_type', 'HS 1st Year Marksheet/Valid supporting document', 'required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->queryform($objId);
        } else {
        
        // $this->saveSectionTwo($this->input->post());
        // $this->saveSectionOne($this->input->post());

        $dbRow = $this->registration_model->get_by_doc_id($objId);
        /////////////////////////////////////////////////////doc upload
      
        $recom_letter_card = '';
        $hs_marksheet_card = '';
        $hslc_marksheet_card = '';
        $photo_of_candidate ='';
        $sign_of_candidate ='';

        $hslcmarksheet = isset($dbRow->form_data->hslc_marksheet) ? $dbRow->form_data->hslc_marksheet : '';
        $hsonemarksheet = isset($dbRow->form_data->hs_one_marksheet) ? $dbRow->form_data->hs_one_marksheet : '';
        $recomletter = isset($dbRow->form_data->recom_letter) ? $dbRow->form_data->recom_letter : '';
        $sign_of_candidate= isset($dbRow->form_data->candidate_sign) ? $dbRow->form_data->candidate_sign : '';  

        if (empty($this->input->post("photo_of_the_candidate_old"))) {
            if ((empty($this->input->post("photo_of_the_candidate_data"))) && ($_FILES['photo_of_the_candidate']['name'] == "")) {
                $this->form_validation->set_rules('photo_of_the_candidate', 'Photo of the Candidate', 'required');
            }
        }
        


        if ($sign_of_candidate == null && $_FILES['candidate_sign']['name'] == "") {
            $this->form_validation->set_rules('candidate_sign', 'Signature of the Candidate is Required', 'required');
        }
        if ($hslcmarksheet == null && $_FILES['hslc_marksheet']['name'] == "") {
            $this->form_validation->set_rules('hslc_marksheet', 'HSLC Marksheet is Required', 'required');
        }
        if ($hsonemarksheet == null && $_FILES['hs_one_marksheet']['name'] == "") {
            $this->form_validation->set_rules('hs_one_marksheet', 'HS 1st Year Marksheet is Required', 'required');
        }
        if ($recomletter == null && $_FILES['recom_letter']['name'] == "") {
            $this->form_validation->set_rules('recom_letter', 'Recommendation Letter is Required', 'required');
        }


        if ($_FILES['photo_of_the_candidate']['name'] != "") {
            $photo_of_the_candidate = cifileupload("photo_of_the_candidate");
            $photo_of_candidate = $photo_of_the_candidate["upload_status"] ? $photo_of_the_candidate["uploaded_path"] : null;
        }
        $photo_of_the_candidate_data = $this->input->post("photo_of_the_candidate_data");
        if((strlen($photo_of_candidate)== '') && (strlen($photo_of_the_candidate_data) > 50)) {
            $candidatePhotoData = str_replace('data:image/jpeg;base64,', '', $photo_of_the_candidate_data);
            $candidatePhotoData2 = str_replace(' ', '+', $candidatePhotoData);
            $candidatePhotoData64 = base64_decode($candidatePhotoData2);

            $fileName = uniqid().'.jpeg';
            $dirPath = 'storage/docs/ahsec_chnginstitute/photos/';
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
            }
            $photo_of_candidate = $dirPath.$fileName;
            file_put_contents(FCPATH.$photo_of_candidate, $candidatePhotoData64);
        }
        else
        {
            $photo_of_candidate=  strlen($photo_of_candidate) ? $photo_of_candidate : ( isset($dbRow->form_data->photo_of_the_candidate) ? $dbRow->form_data->photo_of_the_candidate : '');
        }
        if ($_FILES['candidate_sign']['name'] != "") {
            $candidate_sign = cifileupload("candidate_sign");
            $sign_of_candidate = $candidate_sign["upload_status"] ? $candidate_sign["uploaded_path"] : null;
        }
        // $recom_letter_card = '';
        // $hs_marksheet_card = '';
        // $hslc_marksheet_card = '';
        if (strlen($this->input->post("hs_one_marksheet_temp")) > 0) {
            $hsOneMarksheet = movedigilockerfile($this->input->post('hs_one_marksheet_temp'));
            $hs_marksheet_card = $hsOneMarksheet["upload_status"] ? $hsOneMarksheet["uploaded_path"] : null;
        } else {
            $hsOneMarksheet = cifileupload("hs_one_marksheet");
            $hs_marksheet_card = $hsOneMarksheet["upload_status"] ? $hsOneMarksheet["uploaded_path"] : null;
        }

        if (strlen($this->input->post("hslc_marksheet_temp")) > 0) {
            $hslcMarksheet = movedigilockerfile($this->input->post('hslc_marksheet_temp'));
            $hslc_marksheet_card = $hslcMarksheet["upload_status"] ? $hslcMarksheet["uploaded_path"] : null;
        } else {
            $hslcMarksheet = cifileupload("hslc_marksheet");
            $hslc_marksheet_card = $hslcMarksheet["upload_status"] ? $hslcMarksheet["uploaded_path"] : null;
        }

        if (strlen($this->input->post("recom_letter_temp")) > 0) {
            $recomLetter = movedigilockerfile($this->input->post('recom_letter_temp'));
            $recom_letter_card = $recomLetter["upload_status"] ? $recomLetter["uploaded_path"] : null;
        } else {
            $recomLetter = cifileupload("recom_letter");
            $recom_letter_card = $recomLetter["upload_status"] ? $recomLetter["uploaded_path"] : null;
        }
        $uploadedFiles = array(
            "hslc_marksheet" => strlen($hslc_marksheet_card) ? $hslc_marksheet_card : '',
            "hs_one_marksheet" => strlen($hs_marksheet_card) ? $hs_marksheet_card : '',
            "recom_letter" => strlen($recom_letter_card) ? $recom_letter_card : '',
            "photo_of_candidate" => strlen($photo_of_candidate) ? $photo_of_candidate : '',        
            "candidate_sign" => strlen($sign_of_candidate) ? $sign_of_candidate : '',        
            
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->sectionThree($objId);
        } else {
            $data = array(
                'form_data.hslc_marksheet_type' => $this->input->post("hslc_marksheet_type"),
                'form_data.hslc_marksheet' => strlen($recom_letter_card) ? $recom_letter_card : $dbRow->form_data->hslc_marksheet,
                'form_data.hs_one_marksheet_type' => $this->input->post("hs_one_marksheet_type"),
                'form_data.hs_one_marksheet' => strlen($hs_marksheet_card) ? $hs_marksheet_card : $dbRow->form_data->hs_one_marksheet,
                'form_data.hs_one_marksheet_type' => $this->input->post("recom_letter_type"),
                'form_data.recom_letter' => strlen($recom_letter_card) ? $recom_letter_card : $dbRow->form_data->recom_letter,
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
            redirect('spservices/change_institute_ahsec/registration/preview/' . $objId);
        }

    }
    } //End of querysubmit()

    // public function querysubmit()
    // {
    //     $objId = $this->input->post("obj_id");
    //     if (empty($objId)) {
    //         $this->my_transactions();
    //     }
    //     $appl_ref_no = $this->input->post("appl_ref_no");
    //     $submissionMode = $this->input->post("submission_mode");
    //     $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
    //     $photoOfTheCandidate = cifileupload("photo_of_the_candidate");
    //     $photo_of_the_candidate = $photoOfTheCandidate["upload_status"] ? $photoOfTheCandidate["uploaded_path"] : null;
    //     $uploadedFiles = array(
    //         "photo_of_the_candidate_old" => strlen($photo_of_the_candidate) ? $photo_of_the_candidate : $this->input->post("photo_of_the_candidate_old"),
    //     );

    //     $this->session->set_flashdata('uploaded_files', $uploadedFiles);

    //     if ($this->form_validation->run() == false) {
    //         $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
    //         $this->queryform($objId);
    //     } else {

    //         $dbRow = $this->registration_model->get_by_doc_id($objId);
    //         if (count((array) $dbRow)) {

    //             $data = array(
    //                 'form_data.photo_of_the_candidate_type' => $this->input->post("photo_of_the_candidate_type"),
    //             );

    //             $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

    //             $processing_history = $dbRow->processing_history ?? array();
    //             $processing_history[] = array(
    //                 "processed_by" => "Query submitted by " . $dbRow->form_data->applicant_name,
    //                 "action_taken" => "Query submitted",
    //                 "remarks" => "Query submitted by " . $dbRow->form_data->applicant_name,
    //                 "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
    //             );

    //             $data = array(
    //                 "service_data.appl_status" => "QA",
    //                 'processing_history' => $processing_history,
    //                 'status' => "QUERY_ANSWERED",
    //             );

    //             $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

    //             $this->session->set_flashdata('success', 'Your application has been successfully updated');
    //             redirect('spservices/change_institute_ahsec/registration/preview/' . $objId);
    //         } else {
    //             $this->session->set_flashdata('fail', 'Unable to update data!!! Please try again.');
    //             $this->index();
    //         } //End of if else
    //     } //End of if else
    // } //End of querysubmit()

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
            $dbRow = $this->registration_model->get_by_doc_id($objId);
            // var_dump($dbRow); die;
            if (count((array) $dbRow) && isset($dbRow->form_data->certificate)) {
                if (file_exists($dbRow->form_data->certificate)) {
                    cifiledownload($dbRow->form_data->certificate);
                }
            } else {
                $this->session->set_flashdata('errmessage', 'Something went wrong!, Please try later.');
                $this->my_transactions();
            }
        }
    } //End of download_certificate()
} //End of Castecertificate
