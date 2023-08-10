<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps {
    
    private $serviceName = "Permanent registration of MBBS Doctors";
    Private $serviceId = "PROMD";

    public function __construct() {
        parent::__construct();
        $this->load->model('permanent_registration_mbbs/registration_model');
        $this->load->model('acmr_provisional_certificate/States_model');
        $this->load->model('acmr_provisional_certificate/Districts_model');
        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->helper('smsprovider');
        $this->load->library('Digilocker_API');
        $this->load->helper('log');
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
          }else{
            $this->slug = "user";
          }
    }//End of __construct()

    public function index($obj_id=null) {
        $data=array("pageTitle" => "Permanent registration of MBBS Doctors");
        $filter = array(
            "_id" =>new ObjectId($obj_id), 
            "service_data.appl_status" => "DRAFT"
        );
        $data["dbrow"] = $this->registration_model->get_row($filter);
        $data['usser_type']=$this->slug;

        // pre($data["dbrow"]);
       
        $this->load->view('includes/frontend/header');
        $this->load->view('permanent_registration_mbbs/permanent_registration_mbbs',$data);
        $this->load->view('includes/frontend/footer');
    }//End of index()

    public function getStates()
{
      $this->load->model('permanent_registration_mbbs/states_model');
        $states = $this->states_model->get_distinct_results();

    // print_r($states);
    

    // Send the states as JSON response
    header('Content-Type: application/json');
    echo json_encode($states);
}

    public function getCountries()
{
     
        $this->load->model('permanent_registration_mbbs/countries_model');
        $countries = $this->countries_model->get_rows(array());


    // Send the states as JSON response
    header('Content-Type: application/json');
    echo json_encode($countries);
}


function getDistricts() {
  
    $this->load->model('permanent_registration_mbbs/districts_model');

    $slc = $this->input->post('state');

    // pre($slc);

    $districts = $this->districts_model->get_distinct_results(array("slc" => intval($slc)));

    // print_r($districts);

    header('Content-Type: application/json');
    echo json_encode($districts);
}



    public function submit(){
    //    pre($this->input->post("others_type"));
    //    pre($this->input->post("study_place"));
    // pre($this->input->post("email"));
    // return;
    // pre($this->input->post("rn_type"));

        $objId = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");

        
        // Start of applicant details
        $this->form_validation->set_rules('applicant_name', 'Applicant name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');

        $this->form_validation->set_rules('father_name', 'Father name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');

        $this->form_validation->set_rules('mother_name', 'Mother name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');


        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]');

        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|integer|exact_length[10]|xss_clean|strip_tags');

        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|strip_tags|valid_email|required');

        $this->form_validation->set_rules('permanent_addr', 'Permanent Address', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]');

        $this->form_validation->set_rules('correspondence_addr', 'Correspondence Address', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]');

        $this->form_validation->set_rules('aadhar_no', 'Aadhar No.', 'trim|xss_clean|strip_tags|integer|exact_length[12]');

        $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|xss_clean|strip_tags|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]|max_length[10]');
        // End of applicant details


        // Start of primary qualification
        $this->form_validation->set_rules('primary_qualification', 'Primary Qualification', 'trim|xss_clean|required|strip_tags|regex_match[/^[a-zA-Z ]*$/]'); 

        $this->form_validation->set_rules('primary_qua_doc', 'Date of Completion', 'trim|required|xss_clean|strip_tags'); 

        $this->form_validation->set_rules('college_name', 'College Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]'); 

        $this->form_validation->set_rules('primary_qua_college_addr', 'College Address', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]'); 

        $this->form_validation->set_rules('primary_qua_course_dur', 'Course Duration', 'trim|required|xss_clean|strip_tags'); 

        $this->form_validation->set_rules('primary_qua_university_award_intership', 'University awarding the Internship*', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]'); 

        // End of primary qualification



        // Start of medical registration details
        $this->form_validation->set_rules('acmrrno', 'Assam Council of Medical Registration Registration Number', 'trim|required|xss_clean|strip_tags'); 

        $this->form_validation->set_rules('prn', 'Registration Number', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('rn_type', 'Registration Number Type', 'trim|required|xss_clean|strip_tags'); 


        $this->form_validation->set_rules('registration_date', 'Registration Date', 'trim|required|xss_clean|strip_tags');
        // End of medical registration details



        // Start of address of Study Place

        $this->form_validation->set_rules('study_place', 'Please select where the Applicant Studied', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('address1', 'Address 1', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('address2', 'Address 2', 'trim|required|xss_clean|strip_tags');

        // $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('pincode', 'Pincode', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('country', 'Country', 'trim|required|xss_clean|strip_tags');


        if($this->input->post("study_place")=="1" || $this->input->post("study_place")=="2"){
           $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean|strip_tags');
        }

         if($this->input->post("study_place")=="1" || $this->input->post("study_place")=="2"){
           $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags');
        }

         if($this->input->post("study_place")=="3"){
           $this->form_validation->set_rules('state2', 'State', 'trim|required|xss_clean|strip_tags');
        }


        
        if($this->input->post("study_place")=="1" || $this->input->post("study_place")=="2"){
            $updated_state = $this->input->post("state");
        }
        else{
             $updated_state = $this->input->post("state2");
        }





        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $obj_id = strlen($objId)?$objId:null;
            $this->index($obj_id);
        } else {            
            $appl_ref_no = $this->getID(7); 
            $sessionUser=$this->session->userdata();
            
            if($this->slug === "CSC"){
                $apply_by = $sessionUser['userId'];
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            }//End of if else

            while(1){
                $app_id = rand(100000000, 999999999);
                $filter = array( 
                    "service_data.appl_id" => $app_id,
                    //"service_data.service_id" => $this->serviceId
                );
                $rows = $this->registration_model->get_row($filter);
                
                if($rows == false)
                    break;
            }

            if($this->input->post("study_place")=="3"){
                // unset($form_data["state"]);
            }


            $service_data = [
                "department_id" => "1469",
                "department_name" => "Assam Council of Medical Registration",
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => "Assam Council of Medical Registration (Guwahati)", // office name
                "submission_date" => "",
                "service_timeline" => "7 Days",
                "service_timeline_foreign_medical_graduates"=>"45 Days",
                "appl_status" => "DRAFT",
                "district" => "Guwahati",
            ];


            $rn_type = null;
            // $this->input->post("rn_type")
            if($this->input->post("rn_type") == "Provisional Registration Number"){
                $rn_type = "Provisional Registration Number";
                // pre($rn_type);
            }
            if($this->input->post("rn_type") == "Permanent Registration Number"){
                $rn_type = "Permanent Registration Number";
                // pre($rn_type);
            }
           
            $form_data = [     
                'applicant_name' => $this->input->post("applicant_name"),
                'father_name' => $this->input->post("father_name"),
                'mother_name' => $this->input->post("mother_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'dob' => $this->input->post("dob"),
                'mobile' => $this->input->post("mobile"),
                'email' => $this->input->post("email"),
                'permanent_addr' => $this->input->post("permanent_addr"),
                'correspondence_addr' => $this->input->post("correspondence_addr"),
                'aadhar_no' => $this->input->post("aadhar_no"),
                'pan_no' => trim($this->input->post("pan_no")),


                'primary_qualification' => trim($this->input->post("primary_qualification")),
                'primary_qua_doc' => trim($this->input->post("primary_qua_doc")),
                'college_name' => $this->input->post("college_name"),
                'primary_qua_college_addr' => $this->input->post("primary_qua_college_addr"),
                'primary_qua_course_dur' => $this->input->post("primary_qua_course_dur"),
                'primary_qua_university_award_intership' => $this->input->post("primary_qua_university_award_intership"),


                'acmrrno' => $this->input->post("acmrrno"),
                 $rn_type => $this->input->post("prn"),
                //  'prn' => $this->input->post("prn"),
                // 'rn_type' => $this->input->post("rn_type"),
                'registration_date' => $this->input->post("registration_date"),
 
                'study_place' => $this->input->post("study_place"),
                'address1' => $this->input->post("address1"),
                'address2' => $this->input->post("address2"),
                // 'state' => $this->input->post("state"),
                // 'state2' => $this->input->post("state2"),
                'state' => $updated_state,
                'district' => $this->input->post("district"),
                'country' => $this->input->post("country"),
                'pincode' => $this->input->post("pincode"),


                'user_id' => $sessionUser['userId']->{'$id'},    
                'user_type' => $this->slug,
                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];

            pre($form_data);


            // if($this->input->post("study_place")=="3"){
            //     unset($form_data["state"]);
            // }

            // if($this->input->post("study_place")=="1" || "2"){
            //     unset($form_data["state2"]);
            // }
           





            if(strlen($objId)) {
                $form_data["admit_birth_type"] = $this->input->post("admit_birth_type");
                $form_data["admit_birth"] = $this->input->post("admit_birth");

                $form_data["passport_photo_type"] = $this->input->post("passport_photo_type");
                $form_data["passport_photo"] = $this->input->post("passport_photo");

                $form_data["signature_type"] = $this->input->post("signature_type");
                $form_data["signature"] = $this->input->post("signature");

                $form_data["hs_marksheet_type"] = $this->input->post("hs_marksheet_type");
                $form_data["hs_marksheet"] = $this->input->post("hs_marksheet");

                $form_data["reg_certificate_type"] = $this->input->post("reg_certificate_type");
                $form_data["reg_certificate"] = $this->input->post("reg_certificate");
               

                $form_data["reg_certificate_of_concerned_university_type"] = $this->input->post("reg_certificate_of_concerned_university_type");
                $form_data["reg_certificate_of_concerned_university"] = $this->input->post("reg_certificate_of_concerned_university");

                $form_data["mbbs_marksheet_type"] = $this->input->post("mbbs_marksheet_type");
                $form_data["mbbs_marksheet"] = $this->input->post("mbbs_marksheet");

                $form_data["pass_certificate_type"] = $this->input->post("pass_certificate_type");
                $form_data["pass_certificate"] = $this->input->post("pass_certificate");

                $form_data["internship_completion_certificate_type"] = $this->input->post("internship_completion_certificate_type");
                $form_data["internship_completion_certificate"] = $this->input->post("internship_completion_certificate");

                $form_data["provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type"] = $this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type");
                $form_data["provisional_registration_certificate_of_concerned_assam_council_of_medical_registration"] = $this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration");
     
            }

            $inputs = [
                'service_data'=>$service_data,
                'form_data' => $form_data
            ];
                
            if(strlen($objId)) {

                // pre($inputs);
                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                $this->session->set_flashdata('success','Your application has been successfully submitted');
                redirect('spservices/permanent_registration_mbbs/registration/fileuploads/'.$objId);
            } else {
                // pre($inputs);
                $insert=$this->registration_model->insert($inputs);
                if($insert){
                    $objectId=$insert['_id']->{'$id'};
                    $this->session->set_flashdata('success','Your application has been successfully submitted');
                    redirect('spservices/permanent_registration_mbbs/registration/fileuploads/'.$objectId);
                    exit();
                } else {
                    $this->session->set_flashdata('fail','Unable to submit data!!! Please try again.');
                    $this->index();
                }//End of if else
            }//End of if else
        }//End of if else
    }//End of submit()

    public function fileuploads($objId=null) {

       
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        // pre($dbRow->form_data->state);
        if(count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "pageTitle" => "Attached Enclosures for ".$this->serviceName,
                "obj_id"=>$objId,               
                "dbrow" => $dbRow,
                // "state"=> $dbRow->form_data->state
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('permanent_registration_mbbs/permanent_registration_mbbs_uploads_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/permanent_registration_mbbs/registration');
        }//End of if else
    }//End of fileuploads()

    public function submitfiles() { 
    //  return;
    // pre($this->input->post("study_place"));

        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }


        // 1,2,3
        if(($this->input->post("study_place")=="1") || ($this->input->post("study_place")=="2") || ($this->input->post("study_place")=="3")){
            
        $this->form_validation->set_rules('admit_birth_type', 'Admit or Birth Certificate of the Candidate', 'required');

        if (empty($this->input->post("admit_birth_old"))) {
            if(($_FILES['admit_birth']['name'] == "") && (empty($this->input->post("admit_birth_temp")))) {
            
                // $this->form_validation->set_rules('admit_birth_type', 'Admit or Birth Certificate Type', 'required');
                $this->form_validation->set_rules('admit_birth', 'Admit or Birth Certificate of the Candidate', 'required');
            }
        }

        $this->form_validation->set_rules('passport_photo_type', 'Passport Photo', 'required');
        if (empty($this->input->post("passport_photo_old"))) {
            if(($_FILES['passport_photo']['name'] == "") && (empty($this->input->post("passport_photo_temp")))) {
            
                $this->form_validation->set_rules('passport_photo', 'Passport Photo', 'required');
            }
        }

        
        $this->form_validation->set_rules('signature_type', 'Signature', 'required');
        if (empty($this->input->post("signature_old"))) {
            if(($_FILES['signature']['name'] == "") && (empty($this->input->post("signature_temp")))) {
            
                $this->form_validation->set_rules('signature', 'Signature', 'required');
            }
        }

        $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet', 'required');
        if (empty($this->input->post("hs_marksheet_old"))) {
            if(($_FILES['hs_marksheet']['name'] == "") && (empty($this->input->post("hs_marksheet_temp")))) {
            
                // $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet Type', 'required');
                $this->form_validation->set_rules('hs_marksheet', 'HS Final Marksheet', 'required');
            }
        }

        $this->form_validation->set_rules('internship_completion_certificate_type', 'Internship Completion Certificate', 'required');
        if (empty($this->input->post("internship_completion_certificate_old"))) {
            if(($_FILES['internship_completion_certificate']['name'] == "") && (empty($this->input->post("internship_completion_certificate_temp")))) {

                // $this->form_validation->set_rules('internship_completion_certificate_type', 'Internship Completion Certificate Type', 'required');
                $this->form_validation->set_rules('internship_completion_certificate', 'Internship Completion Certificate', 'required');
            }
        }


        $this->form_validation->set_rules('annexure_type', 'Annexure II', 'required');

        if (empty($this->input->post("annexure_old"))) {
            if(($_FILES['annexure']['name'] == "") && (empty($this->input->post("annexure_temp")))) {
                // $this->form_validation->set_rules('annexure_type', 'Annexure II Type', 'required');
                $this->form_validation->set_rules('annexure', 'Annexure II', 'required');
            }
        }


        }


        // 1
        if($this->input->post("study_place")=="1"){

            $this->form_validation->set_rules('reg_certificate_type', 'Registration Certificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State', 'required');

            if (empty($this->input->post("reg_certificate_old"))) {
                if(($_FILES['reg_certificate']['name'] == "") && (empty($this->input->post("reg_certificate_temp")))) {
                
                    // $this->form_validation->set_rules('reg_certificate_type', 'Registration Certificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State Type', 'required');
                    $this->form_validation->set_rules('reg_certificate', 'Registration Certificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State', 'required');
                }
            }

            $this->form_validation->set_rules('pass_certificate_type', 'MBBS Pass Certificate from College/University', 'required');

    
            if (empty($this->input->post("pass_certificate_old"))) {
                if(($_FILES['pass_certificate']['name'] == "") && (empty($this->input->post("pass_certificate_temp")))) {
                
                    // $this->form_validation->set_rules('pass_certificate_type', 'MBBS Pass Certificate from College/University Type', 'required');
                    $this->form_validation->set_rules('pass_certificate', 'MBBS Pass Certificate from College/University', 'required');
                }
            }

            $this->form_validation->set_rules('provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type', 'Provisional Registration Certificate of concerned Assam Council of Medical Registration', 'required');

            if (empty($this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_old"))) {
                if(($_FILES['provisional_registration_certificate_of_concerned_assam_council_of_medical_registration']['name'] == "") && (empty($this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_temp")))) {
                
                    // $this->form_validation->set_rules('provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type', 'Provisional Registration Certificate of concerned Assam Council of Medical Registration Type', 'required');
                    $this->form_validation->set_rules('provisional_registration_certificate_of_concerned_assam_council_of_medical_registration', 'Provisional Registration Certificate of concerned Assam Council of Medical Registration', 'required');
                }
            }




        }
        // 1,2

        if(($this->input->post("study_place")=="1") || ($this->input->post("study_place")=="2") ){

            $this->form_validation->set_rules('mbbs_marksheet_type', 'All Marksheets of MBBS', 'required');

            if (empty($this->input->post("mbbs_marksheet_old"))) {
                if(($_FILES['mbbs_marksheet']['name'] == "") && (empty($this->input->post("mbbs_marksheet_temp")))) {
                
                    // $this->form_validation->set_rules('mbbs_marksheet_type', 'All Marksheets of MBBS Type', 'required');
                    $this->form_validation->set_rules('mbbs_marksheet', 'All Marksheets of MBBS', 'required');
                }
            }
        }

        // 2
        if($this->input->post("study_place")=="2"){

            $this->form_validation->set_rules('reg_certificate_of_concerned_university_type', 'Registration Certificate of concerned University', 'required');

            if (empty($this->input->post("reg_certificate_of_concerned_university_old"))) {
                if(($_FILES['reg_certificate_of_concerned_university']['name'] == "") && (empty($this->input->post("reg_certificate_of_concerned_university_temp")))) {
                
                    // $this->form_validation->set_rules('reg_certificate_of_concerned_university_type', 'Registration Certificate of concerned University Type', 'required');
                    $this->form_validation->set_rules('reg_certificate_of_concerned_university', 'Registration Certificate of concerned University', 'required');
                }
            }

            $this->form_validation->set_rules('mbbs_pass_certificate_from_university_type', 'MBBS Pass Certificate from University', 'required');

            if (empty($this->input->post("mbbs_pass_certificate_from_university_old"))) {
                if(($_FILES['mbbs_pass_certificate_from_university']['name'] == "") && (empty($this->input->post("mbbs_pass_certificate_from_university_temp")))) {
                
                    // $this->form_validation->set_rules('mbbs_pass_certificate_from_university_type', 'MBBS Pass Certificate from University Type', 'required');
                    $this->form_validation->set_rules('mbbs_pass_certificate_from_university', 'MBBS Pass Certificate from University', 'required');
                }
            }
  
        }


        // 3

        if($this->input->post("study_place")=="3"){

            $this->form_validation->set_rules('registration_certificate_from_respective_university_or_equivalent_type', 'Registration Certificate from respective University or equivalent', 'required');
            if (empty($this->input->post("registration_certificate_from_respective_university_or_equivalent_old"))) {
                if(($_FILES['registration_certificate_from_respective_university_or_equivalent']['name'] == "") && (empty($this->input->post("registration_certificate_from_respective_university_or_equivalent_temp")))) {
                
                    // $this->form_validation->set_rules('registration_certificate_from_respective_university_or_equivalent_type', 'Registration Certificate from respective University or equivalent Type', 'required');
                    $this->form_validation->set_rules('registration_certificate_from_respective_university_or_equivalent', 'Registration Certificate from respective University or equivalent', 'required');
                }
            }

 

            $this->form_validation->set_rules('all_marksheets_of_mbbs_or_equivalent_type', 'All Marksheets of MBBS or equivalent Type', 'required');

            if (empty($this->input->post("all_marksheets_of_mbbs_or_equivalent_old"))) {
                if(($_FILES['all_marksheets_of_mbbs_or_equivalent']['name'] == "") && (empty($this->input->post("all_marksheets_of_mbbs_or_equivalent_temp")))) {
                
                    // $this->form_validation->set_rules('all_marksheets_of_mbbs_or_equivalent_type', 'All Marksheets of MBBS or equivalent Type', 'required');
                    $this->form_validation->set_rules('all_marksheets_of_mbbs_or_equivalent', 'All Marksheets of MBBS or equivalent', 'required');
                }
            }



        $this->form_validation->set_rules('mbbs_or_equivalent_degree_pass_certificate_from_university_type', 'MBBS or equivalent Degree Pass Certificate from University', 'required');
            
        if (empty($this->input->post("mbbs_or_equivalent_degree_pass_certificate_from_university_old"))) {
            if(($_FILES['mbbs_or_equivalent_degree_pass_certificate_from_university']['name'] == "") && (empty($this->input->post("mbbs_or_equivalent_degree_pass_certificate_from_university_temp")))) {
            
                // $this->form_validation->set_rules('mbbs_or_equivalent_degree_pass_certificate_from_university_type', 'MBBS or equivalent Degree Pass Certificate from University Type', 'required');
                $this->form_validation->set_rules('mbbs_or_equivalent_degree_pass_certificate_from_university', 'MBBS or equivalent Degree Pass Certificate from University', 'required');
            }
        }



        $this->form_validation->set_rules('eligibility_certificate_from_national_medical_commission_type', 'Eligibility Certificate from National Medical Commission Type', 'required');

            if (empty($this->input->post("eligibility_certificate_from_national_medical_commission_old"))) {
                if(($_FILES['eligibility_certificate_from_national_medical_commission']['name'] == "") && (empty($this->input->post("eligibility_certificate_from_national_medical_commission_temp")))) {
                
                    // $this->form_validation->set_rules('eligibility_certificate_from_national_medical_commission_type', 'Eligibility Certificate from National Medical Commission Type', 'required');
                    $this->form_validation->set_rules('eligibility_certificate_from_national_medical_commission', 'Eligibility Certificate from National Medical Commission', 'required');
                }
            }



            $this->form_validation->set_rules('screening_test_result_from_national_board_of_examination_type', 'Screening Test Result from National Board of Examination Type', 'required');
            if (empty($this->input->post("screening_test_result_from_national_board_of_examination_old"))) {
                if(($_FILES['screening_test_result_from_national_board_of_examination']['name'] == "") && (empty($this->input->post("screening_test_result_from_national_board_of_examination_temp")))) {
                
                    // $this->form_validation->set_rules('screening_test_result_from_national_board_of_examination_type', 'Screening Test Result from National Board of Examination Type', 'required');
                    $this->form_validation->set_rules('screening_test_result_from_national_board_of_examination', 'Screening Test Result from National Board of Examination', 'required');
                }
            }


            $this->form_validation->set_rules('passport_and_visa_with_travel_details_type', 'Passport and Visa with travel details Type', 'required');
            if (empty($this->input->post("passport_and_visa_with_travel_details_old"))) {
            if(($_FILES['passport_and_visa_with_travel_details']['name'] == "") && (empty($this->input->post("passport_and_visa_with_travel_details_temp")))) {
            
                // $this->form_validation->set_rules('passport_and_visa_with_travel_details_type', 'Passport and Visa with travel details Type', 'required');
                $this->form_validation->set_rules('passport_and_visa_with_travel_details', 'Passport and Visa with travel details', 'required');
            }
        }


      
        $this->form_validation->set_rules('all_documents_related_to_medical_college_details_type', 'All documents related to medical college details Type', 'required');
        
        if (empty($this->input->post("all_documents_related_to_medical_college_details_old"))) {
            if(((empty($this->input->post("all_documents_related_to_medical_college_details_type"))) && (($_FILES['all_documents_related_to_medical_college_details']['name'] != "") || (!empty($this->input->post("all_documents_related_to_medical_college_details_temp"))))) || ((!empty($this->input->post("all_documents_related_to_medical_college_details_type"))) && (($_FILES['all_documents_related_to_medical_college_details']['name'] == "") && (empty($this->input->post("all_documents_related_to_medical_college_details_temp")))))) {
            
                $this->form_validation->set_rules('all_documents_related_to_medical_college_details_type', 'All documents related to medical college details Type', 'required');
                $this->form_validation->set_rules('all_documents_related_to_medical_college_details', 'All documents related to medical college details', 'required');
            }
        }
            




        }

        // 2,3

        if(($this->input->post("study_place")=="2") || ($this->input->post("study_place")=="3") ){


            //             if (empty($this->input->post("admit_birth_old"))) {
            //     if(((empty($this->input->post("admit_birth_type"))) && (($_FILES['admit_birth']['name'] != "") || (!empty($this->input->post("admit_birth_temp"))))) || ((!empty($this->input->post("admit_birth_type"))) && (($_FILES['admit_birth']['name'] == "") && (empty($this->input->post("admit_birth_temp")))))) {
                
            //         $this->form_validation->set_rules('admit_birth_type', 'Admit or Birth Certificate Type', 'required');
            //         $this->form_validation->set_rules('admit_birth', 'Admit or Birth Certificate of the Candidate', 'required');
            //     }
            // }

            $this->form_validation->set_rules('permanent_registration_certificate_of_concerned_medical_council_type', 'Permanent Registration Certificate of concerned Medical Council Type', 'required');


            if (empty($this->input->post("permanent_registration_certificate_of_concerned_medical_council_old"))) {
                if(($_FILES['permanent_registration_certificate_of_concerned_medical_council']['name'] == "") && (empty($this->input->post("permanent_registration_certificate_of_concerned_medical_council_temp")))) {
                
                    // $this->form_validation->set_rules('permanent_registration_certificate_of_concerned_medical_council_type', 'Permanent Registration Certificate of concerned Medical Council Type', 'required');
                    $this->form_validation->set_rules('permanent_registration_certificate_of_concerned_medical_council', 'Permanent Registration Certificate of concerned Medical Council', 'required');
                }
            }



    


            // $this->form_validation->set_rules('noc_from_concerned_medical_council_type', 'noc_from_concerned_medical_council', 'required');

            // if (empty($this->input->post("admit_birth_old"))) {
            //     if(((empty($this->input->post("admit_birth_type"))) && (($_FILES['admit_birth']['name'] != "") || (!empty($this->input->post("admit_birth_temp"))))) || ((!empty($this->input->post("admit_birth_type"))) && (($_FILES['admit_birth']['name'] == "") && (empty($this->input->post("admit_birth_temp")))))) {
                
            //         $this->form_validation->set_rules('admit_birth_type', 'Admit or Birth Certificate Type', 'required');
            //         $this->form_validation->set_rules('admit_birth', 'Admit or Birth Certificate of the Candidate', 'required');
            //     }
            // }
            


        $this->form_validation->set_rules('noc_from_concerned_medical_council_type', 'NOC from concerned Medical Council Type', 'required');

        if (empty($this->input->post("noc_from_concerned_medical_council_old"))) {
            if(($_FILES['noc_from_concerned_medical_council']['name'] == "") && (empty($this->input->post("noc_from_concerned_medical_council_temp")))) {
            
                // $this->form_validation->set_rules('noc_from_concerned_medical_council_type', 'NOC from concerned Medical Council Type', 'required');
                $this->form_validation->set_rules('noc_from_concerned_medical_council', 'NOC from concerned Medical Council', 'required');
            }
        }

        }









        



    
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        
        if (strlen($this->input->post("admit_birth_temp")) > 0) {
            $admitbirth = movedigilockerfile($this->input->post('admit_birth_temp'));
            $admit_birth = $admitbirth["upload_status"]?$admitbirth["uploaded_path"]:null;
        } else {
            $admitbirth = cifileupload("admit_birth");
            $admit_birth = $admitbirth["upload_status"]?$admitbirth["uploaded_path"]:null;
        }

        if (strlen($this->input->post("passport_photo_temp")) > 0) {
            $passportphoto = movedigilockerfile($this->input->post('passport_photo_temp'));
            $passport_photo = $passportphoto["upload_status"]?$passportphoto["uploaded_path"]:null;
        } else {
            $passportphoto = cifileupload("passport_photo");
            $passport_photo = $passportphoto["upload_status"]?$passportphoto["uploaded_path"]:null;
        }


        if (strlen($this->input->post("signature_temp")) > 0) {
            $signature = movedigilockerfile($this->input->post('signature_temp'));
            $signature = $signature["upload_status"]?$signature["uploaded_path"]:null;
        } else {
            $signature = cifileupload("signature");
            $signature = $signature["upload_status"]?$signature["uploaded_path"]:null;
        }


    
        if (strlen($this->input->post("hs_marksheet_temp")) > 0) {
            $hsmarksheet = movedigilockerfile($this->input->post('hs_marksheet_temp'));
            $hs_marksheet = $hsmarksheet["upload_status"]?$hsmarksheet["uploaded_path"]:null;
        } else {
            $hsmarksheet = cifileupload("hs_marksheet");
            $hs_marksheet = $hsmarksheet["upload_status"]?$hsmarksheet["uploaded_path"]:null;
        }


        if (strlen($this->input->post("internship_completion_certificate_temp")) > 0) {
            $internshipcompletioncertificate = movedigilockerfile($this->input->post('internship_completion_certificate_temp'));
            $internship_completion_certificate = $internshipcompletioncertificate["upload_status"]?$internshipcompletioncertificate["uploaded_path"]:null;
        } else {
            $internshipcompletioncertificate = cifileupload("internship_completion_certificate");
            $internship_completion_certificate = $internshipcompletioncertificate["upload_status"]?$internshipcompletioncertificate["uploaded_path"]:null;
        }


        if (strlen($this->input->post("reg_certificate_temp")) > 0) {
            $regcertificate = movedigilockerfile($this->input->post('reg_certificate_temp'));
            $reg_certificate = $regcertificate["upload_status"]?$regcertificate["uploaded_path"]:null;
        } else {
            $regcertificate = cifileupload("reg_certificate");
            $reg_certificate = $regcertificate["upload_status"]?$regcertificate["uploaded_path"]:null;
        }


        if (strlen($this->input->post("reg_certificate_of_concerned_university_temp")) > 0) {
            $regcertificateofconcerneduniversity = movedigilockerfile($this->input->post('reg_certificate_of_concerned_university_temp'));
            $reg_certificate_of_concerned_university = $regcertificateofconcerneduniversity["upload_status"]?$regcertificateofconcerneduniversity["uploaded_path"]:null;
        } else {
            $regcertificateofconcerneduniversity = cifileupload("reg_certificate_of_concerned_university");
            $reg_certificate_of_concerned_university = $regcertificateofconcerneduniversity["upload_status"]?$regcertificateofconcerneduniversity["uploaded_path"]:null;
        }



        if (strlen($this->input->post("mbbs_pass_certificate_from_university_temp")) > 0) {
            $mbbspasscertificatefromuniversity = movedigilockerfile($this->input->post('mbbs_pass_certificate_from_university_temp'));
            $mbbs_pass_certificate_from_university = $mbbspasscertificatefromuniversity["upload_status"]?$mbbspasscertificatefromuniversity["uploaded_path"]:null;
        } else {
            $mbbspasscertificatefromuniversity = cifileupload("mbbs_pass_certificate_from_university");
            $mbbs_pass_certificate_from_university = $mbbspasscertificatefromuniversity["upload_status"]?$mbbspasscertificatefromuniversity["uploaded_path"]:null;
        }
        

        // permanent_registration_certificate_of_concerned_medical_council_old

        if (strlen($this->input->post("permanent_registration_certificate_of_concerned_medical_council_temp")) > 0) {
            $permanentregistrationcertificateofconcernedmedicalcouncil = movedigilockerfile($this->input->post('permanent_registration_certificate_of_concerned_medical_council_temp'));
            $permanent_registration_certificate_of_concerned_medical_council = $permanentregistrationcertificateofconcernedmedicalcouncil["upload_status"]?$permanentregistrationcertificateofconcernedmedicalcouncil["uploaded_path"]:null;
        } else {
            $permanentregistrationcertificateofconcernedmedicalcouncil = cifileupload("permanent_registration_certificate_of_concerned_medical_council");
            $permanent_registration_certificate_of_concerned_medical_council = $permanentregistrationcertificateofconcernedmedicalcouncil["upload_status"]?$permanentregistrationcertificateofconcernedmedicalcouncil["uploaded_path"]:null;
        }




              
  
        if (strlen($this->input->post("noc_from_concerned_medical_council_temp")) > 0) {
            $nocfromconcernedmedicalcouncil = movedigilockerfile($this->input->post('noc_from_concerned_medical_council_temp'));
            $noc_from_concerned_medical_council = $nocfromconcernedmedicalcouncil["upload_status"]?$nocfromconcernedmedicalcouncil["uploaded_path"]:null;
        } else {
            $nocfromconcernedmedicalcouncil = cifileupload("noc_from_concerned_medical_council");
            $noc_from_concerned_medical_council = $nocfromconcernedmedicalcouncil["upload_status"]?$nocfromconcernedmedicalcouncil["uploaded_path"]:null;
        }



        if (strlen($this->input->post("registration_certificate_from_respective_university_or_equivalent_temp")) > 0) {
            $registrationcertificatefromrespectiveuniversityorequivalent = movedigilockerfile($this->input->post('registration_certificate_from_respective_university_or_equivalent_temp'));
            $registration_certificate_from_respective_university_or_equivalent = $registrationcertificatefromrespectiveuniversityorequivalent["upload_status"]?$registrationcertificatefromrespectiveuniversityorequivalent["uploaded_path"]:null;
        } else {
            $registrationcertificatefromrespectiveuniversityorequivalent = cifileupload("registration_certificate_from_respective_university_or_equivalent");
            $registration_certificate_from_respective_university_or_equivalent = $registrationcertificatefromrespectiveuniversityorequivalent["upload_status"]?$registrationcertificatefromrespectiveuniversityorequivalent["uploaded_path"]:null;
        }


      
        if (strlen($this->input->post("all_marksheets_of_mbbs_or_equivalent_temp")) > 0) {
            $allmarksheetsofmbbsorequivalent = movedigilockerfile($this->input->post('all_marksheets_of_mbbs_or_equivalent_temp'));
            $all_marksheets_of_mbbs_or_equivalent = $allmarksheetsofmbbsorequivalent["upload_status"]?$allmarksheetsofmbbsorequivalent["uploaded_path"]:null;
        } else {
            $allmarksheetsofmbbsorequivalent = cifileupload("all_marksheets_of_mbbs_or_equivalent");
            $all_marksheets_of_mbbs_or_equivalent = $allmarksheetsofmbbsorequivalent["upload_status"]?$allmarksheetsofmbbsorequivalent["uploaded_path"]:null;
        }

        
        if (strlen($this->input->post("mbbs_or_equivalent_degree_pass_certificate_from_university_temp")) > 0) {
            $mbbsorequivalentdegreepasscertificatefromuniversity = movedigilockerfile($this->input->post('mbbs_or_equivalent_degree_pass_certificate_from_university_temp'));
            $mbbs_or_equivalent_degree_pass_certificate_from_university = $mbbsorequivalentdegreepasscertificatefromuniversity["upload_status"]?$mbbsorequivalentdegreepasscertificatefromuniversity["uploaded_path"]:null;
        } else {
            $mbbsorequivalentdegreepasscertificatefromuniversity = cifileupload("mbbs_or_equivalent_degree_pass_certificate_from_university");
            $mbbs_or_equivalent_degree_pass_certificate_from_university = $mbbsorequivalentdegreepasscertificatefromuniversity["upload_status"]?$mbbsorequivalentdegreepasscertificatefromuniversity["uploaded_path"]:null;
        }



    

        if (strlen($this->input->post("screening_test_result_from_national_board_of_examination_temp")) > 0) {
            $screeningtestresultfromnationalboardofexamination = movedigilockerfile($this->input->post('screening_test_result_from_national_board_of_examination_temp'));
            $screening_test_result_from_national_board_of_examination = $screeningtestresultfromnationalboardofexamination["upload_status"]?$screeningtestresultfromnationalboardofexamination["uploaded_path"]:null;
        } else {
            $screeningtestresultfromnationalboardofexamination = cifileupload("screening_test_result_from_national_board_of_examination");
            $screening_test_result_from_national_board_of_examination = $screeningtestresultfromnationalboardofexamination["upload_status"]?$screeningtestresultfromnationalboardofexamination["uploaded_path"]:null;
        }



        if (strlen($this->input->post("eligibility_certificate_from_national_medical_commission_temp")) > 0) {
            $eligibilitycertificatefromnationalmedicalcommission = movedigilockerfile($this->input->post('eligibility_certificate_from_national_medical_commission_temp'));
            $eligibility_certificate_from_national_medical_commission = $eligibilitycertificatefromnationalmedicalcommission["upload_status"]?$eligibilitycertificatefromnationalmedicalcommission["uploaded_path"]:null;
        } else {
            $eligibilitycertificatefromnationalmedicalcommission = cifileupload("eligibility_certificate_from_national_medical_commission");
            $eligibility_certificate_from_national_medical_commission = $eligibilitycertificatefromnationalmedicalcommission["upload_status"]?$eligibilitycertificatefromnationalmedicalcommission["uploaded_path"]:null;
        }


        if (strlen($this->input->post("passport_and_visa_with_travel_details_temp")) > 0) {
            $passportandvisawithtraveldetails = movedigilockerfile($this->input->post('passport_and_visa_with_travel_details_temp'));
            $passport_and_visa_with_travel_details = $passportandvisawithtraveldetails["upload_status"]?$passportandvisawithtraveldetails["uploaded_path"]:null;
        } else {
            $passportandvisawithtraveldetails = cifileupload("passport_and_visa_with_travel_details");
            $passport_and_visa_with_travel_details = $passportandvisawithtraveldetails["upload_status"]?$passportandvisawithtraveldetails["uploaded_path"]:null;
        }


        if (strlen($this->input->post("all_documents_related_to_medical_college_details_temp")) > 0) {
            $alldocumentsrelatedtomedicalcollegedetails = movedigilockerfile($this->input->post('all_documents_related_to_medical_college_details_temp'));
            $all_documents_related_to_medical_college_details = $alldocumentsrelatedtomedicalcollegedetails["upload_status"]?$alldocumentsrelatedtomedicalcollegedetails["uploaded_path"]:null;
        } else {
            $alldocumentsrelatedtomedicalcollegedetails = cifileupload("all_documents_related_to_medical_college_details");
            $all_documents_related_to_medical_college_details = $alldocumentsrelatedtomedicalcollegedetails["upload_status"]?$alldocumentsrelatedtomedicalcollegedetails["uploaded_path"]:null;
        }


        

        if (strlen($this->input->post("mbbs_marksheet_temp")) > 0) {
            $mbbsmarksheet = movedigilockerfile($this->input->post('mbbs_marksheet_temp'));
            $mbbs_marksheet = $mbbsmarksheet["upload_status"]?$mbbsmarksheet["uploaded_path"]:null;
        } else {
            $mbbsmarksheet = cifileupload("mbbs_marksheet");
            $mbbs_marksheet = $mbbsmarksheet["upload_status"]?$mbbsmarksheet["uploaded_path"]:null;
        }



        if (strlen($this->input->post("pass_certificate_temp")) > 0) {
            $passcertificate = movedigilockerfile($this->input->post('pass_certificate_temp'));
            $pass_certificate = $passcertificate["upload_status"]?$passcertificate["uploaded_path"]:null;
        } else {
            $passcertificate = cifileupload("pass_certificate");
            $pass_certificate = $passcertificate["upload_status"]?$passcertificate["uploaded_path"]:null;
        }

       

        if (strlen($this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_temp")) > 0) {
            $provisionalregistrationcertificateofconcernedassamcouncilofmedicalregistration = movedigilockerfile($this->input->post('provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_temp'));
            $provisional_registration_certificate_of_concerned_assam_council_of_medical_registration = $provisionalregistrationcertificateofconcernedassamcouncilofmedicalregistration["upload_status"]?$provisionalregistrationcertificateofconcernedassamcouncilofmedicalregistration["uploaded_path"]:null;
        } else {
            $provisionalregistrationcertificateofconcernedassamcouncilofmedicalregistration = cifileupload("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration");
            $provisional_registration_certificate_of_concerned_assam_council_of_medical_registration = $provisionalregistrationcertificateofconcernedassamcouncilofmedicalregistration["upload_status"]?$provisionalregistrationcertificateofconcernedassamcouncilofmedicalregistration["uploaded_path"]:null;
        }

        
 
        if (strlen($this->input->post("annexure_temp")) > 0) {
            $annexure = movedigilockerfile($this->input->post('annexure_temp'));
            $annexure = $annexure["upload_status"]?$annexure["uploaded_path"]:null;
        } else {
            $annexure = cifileupload("annexure");
            $annexure = $annexure["upload_status"]?$annexure["uploaded_path"]:null;
        }


        

        
        $uploadedFiles = array(
            "admit_birth_old" => strlen($admit_birth)?$admit_birth:$this->input->post("admit_birth_old"),

            "passport_photo_old" => strlen($passport_photo)?$passport_photo:$this->input->post("passport_photo_old"),


            "signature_old" => strlen($signature)?$signature:$this->input->post("signature"),


            "hs_marksheet_old" => strlen($hs_marksheet)?$hs_marksheet:$this->input->post("hs_marksheet_old"),

            "reg_certificate_old" => strlen($reg_certificate)?$reg_certificate:$this->input->post("reg_certificate_old"),
            
            "reg_certificate_of_concerned_university_old" => strlen($reg_certificate_of_concerned_university)?$reg_certificate_of_concerned_university:$this->input->post("reg_certificate_of_concerned_university_old"),

            "mbbs_pass_certificate_from_university_old" => strlen($mbbs_pass_certificate_from_university)?$mbbs_pass_certificate_from_university:$this->input->post("mbbs_pass_certificate_from_university_old"),

            
            "permanent_registration_certificate_of_concerned_medical_council_old" => strlen($permanent_registration_certificate_of_concerned_medical_council)?$permanent_registration_certificate_of_concerned_medical_council:$this->input->post("permanent_registration_certificate_of_concerned_medical_council_old"),


            "noc_from_concerned_medical_council_old" => strlen($noc_from_concerned_medical_council)?$noc_from_concerned_medical_council:$this->input->post("noc_from_concerned_medical_council_old"),

            
            "registration_certificate_from_respective_university_or_equivalent_old" => strlen($registration_certificate_from_respective_university_or_equivalent)?$registration_certificate_from_respective_university_or_equivalent:$this->input->post("registration_certificate_from_respective_university_or_equivalent_old"),

            "all_marksheets_of_mbbs_or_equivalent_old" => strlen($all_marksheets_of_mbbs_or_equivalent)?$all_marksheets_of_mbbs_or_equivalent:$this->input->post("all_marksheets_of_mbbs_or_equivalent_old"),


            "mbbs_or_equivalent_degree_pass_certificate_from_university_old" => strlen($mbbs_or_equivalent_degree_pass_certificate_from_university)?$mbbs_or_equivalent_degree_pass_certificate_from_university:$this->input->post("mbbs_or_equivalent_degree_pass_certificate_from_university_old"),


            

            "eligibility_certificate_from_national_medical_commission_old" => strlen($eligibility_certificate_from_national_medical_commission)?$eligibility_certificate_from_national_medical_commission:$this->input->post("eligibility_certificate_from_national_medical_commission_old"),


            
            "screening_test_result_from_national_board_of_examination_old" => strlen($screening_test_result_from_national_board_of_examination)?$screening_test_result_from_national_board_of_examination:$this->input->post("screening_test_result_from_national_board_of_examination_old"),

            

            "passport_and_visa_with_travel_details_old" => strlen($passport_and_visa_with_travel_details)?$passport_and_visa_with_travel_details:$this->input->post("passport_and_visa_with_travel_details_old"),

            
            "all_documents_related_to_medical_college_details_old" => strlen($all_documents_related_to_medical_college_details)?$all_documents_related_to_medical_college_details:$this->input->post("all_documents_related_to_medical_college_details_old"),

            

            "mbbs_marksheet_old" => strlen($mbbs_marksheet)?$mbbs_marksheet:$this->input->post("mbbs_marksheet_old"),

            "pass_certificate_old" => strlen($pass_certificate)?$pass_certificate:$this->input->post("pass_certificate_old"),

            "provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_old" => strlen($provisional_registration_certificate_of_concerned_assam_council_of_medical_registration)?$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration:$this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_old"),

            "internship_completion_certificate_old" => strlen($internship_completion_certificate)?$internship_completion_certificate:$this->input->post("internship_completion_certificate_old"),





            "annexure_old" => strlen($annexure)?$annexure:$this->input->post("annexure_old")


        );
        // pre($uploadedFiles);
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->fileuploads($objId);
        } else {
// pre("Ahise");
// pre($dbrow->form_data);
            $data = array(
                'form_data.admit_birth_type' => $this->input->post("admit_birth_type"),

                'form_data.passport_photo_type' => $this->input->post("passport_photo_type"),
                'form_data.signature_type' => $this->input->post("signature_type"),


                'form_data.hs_marksheet_type' => $this->input->post("hs_marksheet_type"),
                'form_data.reg_certificate_type' => $this->input->post("reg_certificate_type"),
                
                'form_data.reg_certificate_of_concerned_university_type' => $this->input->post("reg_certificate_of_concerned_university_type"),

                'form_data.mbbs_pass_certificate_from_university_type' => $this->input->post("mbbs_pass_certificate_from_university_type"),

                'form_data.permanent_registration_certificate_of_concerned_medical_council_type' => $this->input->post("permanent_registration_certificate_of_concerned_medical_council_type"),

                'form_data.noc_from_concerned_medical_council_type' => $this->input->post("noc_from_concerned_medical_council_type"),

                'form_data.registration_certificate_from_respective_university_or_equivalent_type' => $this->input->post("registration_certificate_from_respective_university_or_equivalent_type"),

                
                'form_data.all_marksheets_of_mbbs_or_equivalent_type' => $this->input->post("all_marksheets_of_mbbs_or_equivalent_type"),
                
                'form_data.mbbs_or_equivalent_degree_pass_certificate_from_university_type' => $this->input->post("mbbs_or_equivalent_degree_pass_certificate_from_university_type"),

                
                'form_data.mbbs_marksheet_type' => $this->input->post("mbbs_marksheet_type"),
                'form_data.pass_certificate_type' => $this->input->post("pass_certificate_type"),
                'form_data.internship_completion_certificate_type' => $this->input->post("internship_completion_certificate_type"),
                'form_data.provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type' => $this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type"),

                'form_data.eligibility_certificate_from_national_medical_commission_type' => $this->input->post("eligibility_certificate_from_national_medical_commission_type"),

                'form_data.screening_test_result_from_national_board_of_examination_type' => $this->input->post("screening_test_result_from_national_board_of_examination_type"),

                'form_data.passport_and_visa_with_travel_details_type' => $this->input->post("passport_and_visa_with_travel_details_type"),

                'form_data.all_documents_related_to_medical_college_details_type' => $this->input->post("all_documents_related_to_medical_college_details_type"),

                'form_data.admit_birth' => strlen($admit_birth)?$admit_birth:$this->input->post("admit_birth_old"),

                'form_data.passport_photo' => strlen($passport_photo)?$passport_photo:$this->input->post("passport_photo_old"),

                'form_data.signature' => strlen($signature)?$signature:$this->input->post("signature_old"),

                'form_data.hs_marksheet' => strlen($hs_marksheet)?$hs_marksheet:$this->input->post("hs_marksheet_old"),

                'form_data.reg_certificate' => strlen($reg_certificate)?$reg_certificate:$this->input->post("reg_certificate_old"),
                
                'form_data.reg_certificate_of_concerned_university' => strlen($reg_certificate_of_concerned_university)?$reg_certificate_of_concerned_university:$this->input->post("reg_certificate_of_concerned_university_old"),

                'form_data.mbbs_pass_certificate_from_university' => strlen($mbbs_pass_certificate_from_university)?$mbbs_pass_certificate_from_university:$this->input->post("mbbs_pass_certificate_from_university_old"),

                'form_data.permanent_registration_certificate_of_concerned_medical_council' => strlen($permanent_registration_certificate_of_concerned_medical_council)?$permanent_registration_certificate_of_concerned_medical_council:$this->input->post("permanent_registration_certificate_of_concerned_medical_council_old"),

                'form_data.noc_from_concerned_medical_council' => strlen($noc_from_concerned_medical_council)?$noc_from_concerned_medical_council:$this->input->post("noc_from_concerned_medical_council"),

                'form_data.registration_certificate_from_respective_university_or_equivalent' => strlen($registration_certificate_from_respective_university_or_equivalent)?$registration_certificate_from_respective_university_or_equivalent:$this->input->post("registration_certificate_from_respective_university_or_equivalent"),

                
                'form_data.all_marksheets_of_mbbs_or_equivalent' => strlen($all_marksheets_of_mbbs_or_equivalent)?$all_marksheets_of_mbbs_or_equivalent:$this->input->post("all_marksheets_of_mbbs_or_equivalent"),


                'form_data.mbbs_or_equivalent_degree_pass_certificate_from_university' => strlen($mbbs_or_equivalent_degree_pass_certificate_from_university)?$mbbs_or_equivalent_degree_pass_certificate_from_university:$this->input->post("mbbs_or_equivalent_degree_pass_certificate_from_university"),

                
                'form_data.eligibility_certificate_from_national_medical_commission' => strlen($eligibility_certificate_from_national_medical_commission)?$eligibility_certificate_from_national_medical_commission:$this->input->post("eligibility_certificate_from_national_medical_commission"),

                'form_data.screening_test_result_from_national_board_of_examination' => strlen($screening_test_result_from_national_board_of_examination)?$screening_test_result_from_national_board_of_examination:$this->input->post("screening_test_result_from_national_board_of_examination"),

                'form_data.passport_and_visa_with_travel_details' => strlen($passport_and_visa_with_travel_details)?$passport_and_visa_with_travel_details:$this->input->post("passport_and_visa_with_travel_details"),

                'form_data.all_documents_related_to_medical_college_details' => strlen($all_documents_related_to_medical_college_details)?$all_documents_related_to_medical_college_details:$this->input->post("all_documents_related_to_medical_college_details"),





                'form_data.mbbs_marksheet' => strlen($mbbs_marksheet)?$mbbs_marksheet:$this->input->post("mbbs_marksheet_old"),

                'form_data.pass_certificate' => strlen($pass_certificate)?$pass_certificate:$this->input->post("pass_certificate_old"),

                
                'form_data.internship_completion_certificate' => strlen($internship_completion_certificate)?$internship_completion_certificate:$this->input->post("internship_completion_certificate_old"),

                'form_data.provisional_registration_certificate_of_concerned_assam_council_of_medical_registration' => strlen($provisional_registration_certificate_of_concerned_assam_council_of_medical_registration)?$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration:$this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_old"),

                'form_data.annexure_type' => $this->input->post("annexure_type"),
                'form_data.annexure' => strlen($annexure)?$annexure:$this->input->post("annexure_old"),

            );

            
            $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success','Your application has been successfully submitted');
            redirect('spservices/permanent_registration_mbbs/registration/preview/'.$objId);
        }//End of if else
    }//End of submitfiles()

    public function preview($objId=null) {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('permanent_registration_mbbs/permanent_registration_mbbs_preview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/permanent_registration_mbbs/registration');
        }//End of if else
    }//End of preview()

    public function applicationpreview($objId=null) {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('permanent_registration_mbbs/permanent_registration_mbbs_application_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/permanent_registration_mbbs/registration');
        }//End of if else
    }
    
    public function track($objId=null) {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if(isset($dbRow->form_data->edistrict_ref_no ) && !empty($dbRow->form_data->edistrict_ref_no )){
            $this->load->helper('trackstatus');
            fetchEdistrictData($dbRow->form_data->edistrict_ref_no);
            $dbRow = $this->registration_model->get_by_doc_id($objId);
        }
        if(count((array)$dbRow)) {
            $data=array(
                "service_data.service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            // pre($data);
            $this->load->view('includes/frontend/header');
            $this->load->view('permanent_registration_mbbs/permanent_registration_mbbs_track_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/permanent_registration_mbbs/registration');
        }//End of if else
    }//End of track()

    public function queryform($objId=null) {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->registration_model->get_row(array("_id"=> new ObjectId($objId), "service_data.appl_status"=>"QS"));
            if($dbRow) {
                $data=array(
                    "service_data.service_name"=>$this->serviceName,
                    "dbrow"=>$dbRow
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('permanent_registration_mbbs/permanent_registration_mbbs_query_view',$data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found in query mode against object id : '.$objId);
                redirect('spservices/nextofkin/registration');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/nextofkin/registration');
        }//End of if else
    }//End of query()

    public function querysubmit() {        
        $objId = $this->input->post("obj_id");
        // pre("OK");
        // return;
        if (empty($objId)) {
            $this->my_transactions();
        }

        // print_r($this->input->post());
        // pre($this->input->post('study_place'));

        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");

                // 1,2,3
                if(($this->input->post("study_place")=="1") || ($this->input->post("study_place")=="2") || ($this->input->post("study_place")=="3")){
            
                    $this->form_validation->set_rules('admit_birth_type', 'Admit or Birth Certificate of the Candidate', 'required');
            
                    if (empty($this->input->post("admit_birth_old"))) {
                        if(($_FILES['admit_birth']['name'] == "") && (empty($this->input->post("admit_birth_temp")))) {
                        
                            // $this->form_validation->set_rules('admit_birth_type', 'Admit or Birth Certificate Type', 'required');
                            $this->form_validation->set_rules('admit_birth', 'Admit or Birth Certificate of the Candidate', 'required');
                        }
                    }

                     $this->form_validation->set_rules('passport_photo_type', 'Passport Photo', 'required');
                    if (empty($this->input->post("passport_photo_old"))) {
                        if(($_FILES['passport_photo']['name'] == "") && (empty($this->input->post("passport_photo_temp")))) {
                        
                            // $this->form_validation->set_rules('admit_birth_type', 'Admit or Birth Certificate Type', 'required');
                            $this->form_validation->set_rules('passport_photo', 'Passport Photo', 'required');
                        }
                    }

                    $this->form_validation->set_rules('signature_type', 'Signature', 'required');
                    if (empty($this->input->post("signature_old"))) {
                        if(($_FILES['signature']['name'] == "") && (empty($this->input->post("signature_temp")))) {
                        
                            // $this->form_validation->set_rules('admit_birth_type', 'Admit or Birth Certificate Type', 'required');
                            $this->form_validation->set_rules('signature', 'Signature', 'required');
                        }
                    }
            
                    $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet', 'required');
                    if (empty($this->input->post("hs_marksheet_old"))) {
                        if(($_FILES['hs_marksheet']['name'] == "") && (empty($this->input->post("hs_marksheet_temp")))) {
                        
                            // $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet Type', 'required');
                            $this->form_validation->set_rules('hs_marksheet', 'HS Final Marksheet', 'required');
                        }
                    }
            
                    $this->form_validation->set_rules('internship_completion_certificate_type', 'Internship Completion Certificate', 'required');
                    if (empty($this->input->post("internship_completion_certificate_old"))) {
                        if(($_FILES['internship_completion_certificate']['name'] == "") && (empty($this->input->post("internship_completion_certificate_temp")))) {
            
                            // $this->form_validation->set_rules('internship_completion_certificate_type', 'Internship Completion Certificate Type', 'required');
                            $this->form_validation->set_rules('internship_completion_certificate', 'Internship Completion Certificate', 'required');
                        }
                    }
            
            
                    $this->form_validation->set_rules('annexure_type', 'Annexure II Type', 'required');
            
                    if (empty($this->input->post("annexure_old"))) {
                        if(($_FILES['annexure']['name'] == "") && (empty($this->input->post("annexure_temp")))) {
                            // $this->form_validation->set_rules('annexure_type', 'Annexure II Type', 'required');
                            $this->form_validation->set_rules('annexure', 'Annexure II', 'required');
                        }
                    }
            
            
                    }
            
            
                    // 1
                    if($this->input->post("study_place")=="1"){
            
                        $this->form_validation->set_rules('reg_certificate_type', 'Registration Certificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State', 'required');
            
                        if (empty($this->input->post("reg_certificate_old"))) {
                            if(($_FILES['reg_certificate']['name'] == "") && (empty($this->input->post("reg_certificate_temp")))) {
                            
                                // $this->form_validation->set_rules('reg_certificate_type', 'Registration Certificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State Type', 'required');
                                $this->form_validation->set_rules('reg_certificate', 'Registration Certificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State', 'required');
                            }
                        }
            
                        $this->form_validation->set_rules('pass_certificate_type', 'MBBS Pass Certificate from College/University', 'required');
            
                
                        if (empty($this->input->post("pass_certificate_old"))) {
                            if(($_FILES['pass_certificate']['name'] == "") && (empty($this->input->post("pass_certificate_temp")))) {
                            
                                // $this->form_validation->set_rules('pass_certificate_type', 'MBBS Pass Certificate from College/University Type', 'required');
                                $this->form_validation->set_rules('pass_certificate', 'MBBS Pass Certificate from College/University', 'required');
                            }
                        }
            
                        
            
                        $this->form_validation->set_rules('provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type', 'Provisional Registration Certificate of concerned Assam Council of Medical Registration', 'required');
            
                        if (empty($this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_old"))) {
                            if(($_FILES['provisional_registration_certificate_of_concerned_assam_council_of_medical_registration']['name'] == "") && (empty($this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_temp")))) {
                            
                                // $this->form_validation->set_rules('provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type', 'Provisional Registration Certificate of concerned Assam Council of Medical Registration Type', 'required');
                                $this->form_validation->set_rules('provisional_registration_certificate_of_concerned_assam_council_of_medical_registration', 'Provisional Registration Certificate of concerned Assam Council of Medical Registration', 'required');
                            }
                        }
            
            
            
            
                    }
                    // 1,2
            
                    if(($this->input->post("study_place")=="1") || ($this->input->post("study_place")=="2") ){
            
                        $this->form_validation->set_rules('mbbs_marksheet_type', 'All Marksheets of MBBS', 'required');
            
                        if (empty($this->input->post("mbbs_marksheet_old"))) {
                            if(($_FILES['mbbs_marksheet']['name'] == "") && (empty($this->input->post("mbbs_marksheet_temp")))) {
                            
                                // $this->form_validation->set_rules('mbbs_marksheet_type', 'All Marksheets of MBBS Type', 'required');
                                $this->form_validation->set_rules('mbbs_marksheet', 'All Marksheets of MBBS', 'required');
                            }
                        }
                    }
            
                    // 2
                    if($this->input->post("study_place")=="2"){
            
                        $this->form_validation->set_rules('reg_certificate_of_concerned_university_type', 'Registration Certificate of concerned University', 'required');
            
                        if (empty($this->input->post("reg_certificate_of_concerned_university_old"))) {
                            if(($_FILES['reg_certificate_of_concerned_university']['name'] == "") && (empty($this->input->post("reg_certificate_of_concerned_university_temp")))) {
                            
                                // $this->form_validation->set_rules('reg_certificate_of_concerned_university_type', 'Registration Certificate of concerned University Type', 'required');
                                $this->form_validation->set_rules('reg_certificate_of_concerned_university', 'Registration Certificate of concerned University', 'required');
                            }
                        }
            
                        $this->form_validation->set_rules('mbbs_pass_certificate_from_university_type', 'MBBS Pass Certificate from University', 'required');
            
                        if (empty($this->input->post("mbbs_pass_certificate_from_university_old"))) {
                            if(($_FILES['mbbs_pass_certificate_from_university']['name'] == "") && (empty($this->input->post("mbbs_pass_certificate_from_university_temp")))) {
                            
                                // $this->form_validation->set_rules('mbbs_pass_certificate_from_university_type', 'MBBS Pass Certificate from University Type', 'required');
                                $this->form_validation->set_rules('mbbs_pass_certificate_from_university', 'MBBS Pass Certificate from University', 'required');
                            }
                        }
              
                    }
            
            
                    // 3
            
                    if($this->input->post("study_place")=="3"){
            
                        $this->form_validation->set_rules('registration_certificate_from_respective_university_or_equivalent_type', 'Registration Certificate from respective University or equivalent', 'required');
                        if (empty($this->input->post("registration_certificate_from_respective_university_or_equivalent_old"))) {
                            if(($_FILES['registration_certificate_from_respective_university_or_equivalent']['name'] == "") && (empty($this->input->post("registration_certificate_from_respective_university_or_equivalent_temp")))) {
                            
                                // $this->form_validation->set_rules('registration_certificate_from_respective_university_or_equivalent_type', 'Registration Certificate from respective University or equivalent Type', 'required');
                                $this->form_validation->set_rules('registration_certificate_from_respective_university_or_equivalent', 'Registration Certificate from respective University or equivalent File', 'required');
                            }
                        }
            
             
            
                        $this->form_validation->set_rules('all_marksheets_of_mbbs_or_equivalent_type', 'All Marksheets of MBBS or equivalent Type', 'required');
            
                        if (empty($this->input->post("all_marksheets_of_mbbs_or_equivalent_old"))) {
                            if(($_FILES['all_marksheets_of_mbbs_or_equivalent']['name'] == "") && (empty($this->input->post("all_marksheets_of_mbbs_or_equivalent_temp")))) {
                            
                                // $this->form_validation->set_rules('all_marksheets_of_mbbs_or_equivalent_type', 'All Marksheets of MBBS or equivalent Type', 'required');
                                $this->form_validation->set_rules('all_marksheets_of_mbbs_or_equivalent', 'All Marksheets of MBBS or equivalent', 'required');
                            }
                        }
            
            
            
                    $this->form_validation->set_rules('mbbs_or_equivalent_degree_pass_certificate_from_university_type', 'MBBS or equivalent Degree Pass Certificate from University', 'required');
                        
                    if (empty($this->input->post("mbbs_or_equivalent_degree_pass_certificate_from_university_old"))) {
                        if(($_FILES['mbbs_or_equivalent_degree_pass_certificate_from_university']['name'] == "") && (empty($this->input->post("mbbs_or_equivalent_degree_pass_certificate_from_university_temp")))) {
                        
                            // $this->form_validation->set_rules('mbbs_or_equivalent_degree_pass_certificate_from_university_type', 'MBBS or equivalent Degree Pass Certificate from University Type', 'required');
                            $this->form_validation->set_rules('mbbs_or_equivalent_degree_pass_certificate_from_university', 'MBBS or equivalent Degree Pass Certificate from University', 'required');
                        }
                    }
            
            
            
                    $this->form_validation->set_rules('eligibility_certificate_from_national_medical_commission_type', 'Eligibility Certificate from National Medical Commission Type', 'required');
            
                        if (empty($this->input->post("eligibility_certificate_from_national_medical_commission_old"))) {
                            if(($_FILES['eligibility_certificate_from_national_medical_commission']['name'] == "") && (empty($this->input->post("eligibility_certificate_from_national_medical_commission_temp")))) {
                            
                                // $this->form_validation->set_rules('eligibility_certificate_from_national_medical_commission_type', 'Eligibility Certificate from National Medical Commission Type', 'required');
                                $this->form_validation->set_rules('eligibility_certificate_from_national_medical_commission', 'Eligibility Certificate from National Medical Commission', 'required');
                            }
                        }
            
            
            
                        $this->form_validation->set_rules('screening_test_result_from_national_board_of_examination_type', 'Screening Test Result from National Board of Examination Type', 'required');
                        if (empty($this->input->post("screening_test_result_from_national_board_of_examination_old"))) {
                            if(($_FILES['screening_test_result_from_national_board_of_examination']['name'] == "") && (empty($this->input->post("screening_test_result_from_national_board_of_examination_temp")))) {
                            
                                // $this->form_validation->set_rules('screening_test_result_from_national_board_of_examination_type', 'Screening Test Result from National Board of Examination Type', 'required');
                                $this->form_validation->set_rules('screening_test_result_from_national_board_of_examination', 'Screening Test Result from National Board of Examination', 'required');
                            }
                        }
            
            
                        $this->form_validation->set_rules('passport_and_visa_with_travel_details_type', 'Passport and Visa with travel details Type', 'required');
                        if (empty($this->input->post("passport_and_visa_with_travel_details_old"))) {
                        if(($_FILES['passport_and_visa_with_travel_details']['name'] == "") && (empty($this->input->post("passport_and_visa_with_travel_details_temp")))) {
                        
                            // $this->form_validation->set_rules('passport_and_visa_with_travel_details_type', 'Passport and Visa with travel details Type', 'required');
                            $this->form_validation->set_rules('passport_and_visa_with_travel_details', 'Passport and Visa with travel details', 'required');
                        }
                    }
            
            
                  
                    $this->form_validation->set_rules('all_documents_related_to_medical_college_details_type', 'All documents related to medical college details Type', 'required');
                    
                    if (empty($this->input->post("all_documents_related_to_medical_college_details_old"))) {
                        if(((empty($this->input->post("all_documents_related_to_medical_college_details_type"))) && (($_FILES['all_documents_related_to_medical_college_details']['name'] != "") || (!empty($this->input->post("all_documents_related_to_medical_college_details_temp"))))) || ((!empty($this->input->post("all_documents_related_to_medical_college_details_type"))) && (($_FILES['all_documents_related_to_medical_college_details']['name'] == "") && (empty($this->input->post("all_documents_related_to_medical_college_details_temp")))))) {
                        
                            $this->form_validation->set_rules('all_documents_related_to_medical_college_details_type', 'All documents related to medical college details Type', 'required');
                            $this->form_validation->set_rules('all_documents_related_to_medical_college_details', 'All documents related to medical college details', 'required');
                        }
                    }
                        
            
            
            
            
                    }
            
                    // 2,3
            
                    if(($this->input->post("study_place")=="2") || ($this->input->post("study_place")=="3") ){
            
            
                        //             if (empty($this->input->post("admit_birth_old"))) {
                        //     if(((empty($this->input->post("admit_birth_type"))) && (($_FILES['admit_birth']['name'] != "") || (!empty($this->input->post("admit_birth_temp"))))) || ((!empty($this->input->post("admit_birth_type"))) && (($_FILES['admit_birth']['name'] == "") && (empty($this->input->post("admit_birth_temp")))))) {
                            
                        //         $this->form_validation->set_rules('admit_birth_type', 'Admit or Birth Certificate Type', 'required');
                        //         $this->form_validation->set_rules('admit_birth', 'Admit or Birth Certificate of the Candidate', 'required');
                        //     }
                        // }
            
                        $this->form_validation->set_rules('permanent_registration_certificate_of_concerned_medical_council_type', 'Permanent Registration Certificate of concerned Medical Council Type', 'required');
            
            
                        if (empty($this->input->post("permanent_registration_certificate_of_concerned_medical_council_old"))) {
                            if(($_FILES['permanent_registration_certificate_of_concerned_medical_council']['name'] == "") && (empty($this->input->post("permanent_registration_certificate_of_concerned_medical_council_temp")))) {
                            
                                // $this->form_validation->set_rules('permanent_registration_certificate_of_concerned_medical_council_type', 'Permanent Registration Certificate of concerned Medical Council Type', 'required');
                                $this->form_validation->set_rules('permanent_registration_certificate_of_concerned_medical_council', 'Permanent Registration Certificate of concerned Medical Council', 'required');
                            }
                        }
            
            
            
                
            
            
                        // $this->form_validation->set_rules('noc_from_concerned_medical_council_type', 'noc_from_concerned_medical_council', 'required');
            
                        // if (empty($this->input->post("admit_birth_old"))) {
                        //     if(((empty($this->input->post("admit_birth_type"))) && (($_FILES['admit_birth']['name'] != "") || (!empty($this->input->post("admit_birth_temp"))))) || ((!empty($this->input->post("admit_birth_type"))) && (($_FILES['admit_birth']['name'] == "") && (empty($this->input->post("admit_birth_temp")))))) {
                            
                        //         $this->form_validation->set_rules('admit_birth_type', 'Admit or Birth Certificate Type', 'required');
                        //         $this->form_validation->set_rules('admit_birth', 'Admit or Birth Certificate of the Candidate', 'required');
                        //     }
                        // }
                        
            
            
                    $this->form_validation->set_rules('noc_from_concerned_medical_council_type', 'NOC from concerned Medical Council Type', 'required');
            
                    if (empty($this->input->post("noc_from_concerned_medical_council_old"))) {
                        if(($_FILES['noc_from_concerned_medical_council']['name'] == "") && (empty($this->input->post("noc_from_concerned_medical_council_temp")))) {
                        
                            // $this->form_validation->set_rules('noc_from_concerned_medical_council_type', 'NOC from concerned Medical Council Type', 'required');
                            $this->form_validation->set_rules('noc_from_concerned_medical_council', 'NOC from concerned Medical Council', 'required');
                        }
                    }
            
                    }            
       
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if (strlen($this->input->post("admit_birth_temp")) > 0) {
            $admitbirth = movedigilockerfile($this->input->post('admit_birth_temp'));
            $admit_birth = $admitbirth["upload_status"]?$admitbirth["uploaded_path"]:null;
        } else {
            $admitbirth = cifileupload("admit_birth");
            $admit_birth = $admitbirth["upload_status"]?$admitbirth["uploaded_path"]:null;
        }

        if (strlen($this->input->post("passport_photo_temp")) > 0) {
            $passportphoto = movedigilockerfile($this->input->post('passport_photo_temp'));
            $passport_photo = $passportphoto["upload_status"]?$passportphoto["uploaded_path"]:null;
        } else {
            $passportphoto = cifileupload("passport_photo");
            $passport_photo = $passportphoto["upload_status"]?$passportphoto["uploaded_path"]:null;
        }


        if (strlen($this->input->post("signature_temp")) > 0) {
            $signature = movedigilockerfile($this->input->post('signature_temp'));
            $signature = $signature["upload_status"]?$signature["uploaded_path"]:null;
        } else {
            $signature = cifileupload("signature");
            $signature = $signature["upload_status"]?$signature["uploaded_path"]:null;
        }




    
        if (strlen($this->input->post("hs_marksheet_temp")) > 0) {
            $hsmarksheet = movedigilockerfile($this->input->post('hs_marksheet_temp'));
            $hs_marksheet = $hsmarksheet["upload_status"]?$hsmarksheet["uploaded_path"]:null;
        } else {
            $hsmarksheet = cifileupload("hs_marksheet");
            $hs_marksheet = $hsmarksheet["upload_status"]?$hsmarksheet["uploaded_path"]:null;
        }


        if (strlen($this->input->post("internship_completion_certificate_temp")) > 0) {
            $internshipcompletioncertificate = movedigilockerfile($this->input->post('internship_completion_certificate_temp'));
            $internship_completion_certificate = $internshipcompletioncertificate["upload_status"]?$internshipcompletioncertificate["uploaded_path"]:null;
        } else {
            $internshipcompletioncertificate = cifileupload("internship_completion_certificate");
            $internship_completion_certificate = $internshipcompletioncertificate["upload_status"]?$internshipcompletioncertificate["uploaded_path"]:null;
        }


        if (strlen($this->input->post("reg_certificate_temp")) > 0) {
            $regcertificate = movedigilockerfile($this->input->post('reg_certificate_temp'));
            $reg_certificate = $regcertificate["upload_status"]?$regcertificate["uploaded_path"]:null;
        } else {
            $regcertificate = cifileupload("reg_certificate");
            $reg_certificate = $regcertificate["upload_status"]?$regcertificate["uploaded_path"]:null;
        }


        if (strlen($this->input->post("reg_certificate_of_concerned_university_temp")) > 0) {
            $regcertificateofconcerneduniversity = movedigilockerfile($this->input->post('reg_certificate_of_concerned_university_temp'));
            $reg_certificate_of_concerned_university = $regcertificateofconcerneduniversity["upload_status"]?$regcertificateofconcerneduniversity["uploaded_path"]:null;
        } else {
            $regcertificateofconcerneduniversity = cifileupload("reg_certificate_of_concerned_university");
            $reg_certificate_of_concerned_university = $regcertificateofconcerneduniversity["upload_status"]?$regcertificateofconcerneduniversity["uploaded_path"]:null;
        }



        if (strlen($this->input->post("mbbs_pass_certificate_from_university_temp")) > 0) {
            $mbbspasscertificatefromuniversity = movedigilockerfile($this->input->post('mbbs_pass_certificate_from_university_temp'));
            $mbbs_pass_certificate_from_university = $mbbspasscertificatefromuniversity["upload_status"]?$mbbspasscertificatefromuniversity["uploaded_path"]:null;
        } else {
            $mbbspasscertificatefromuniversity = cifileupload("mbbs_pass_certificate_from_university");
            $mbbs_pass_certificate_from_university = $mbbspasscertificatefromuniversity["upload_status"]?$mbbspasscertificatefromuniversity["uploaded_path"]:null;
        }
        

        // permanent_registration_certificate_of_concerned_medical_council_old

        if (strlen($this->input->post("permanent_registration_certificate_of_concerned_medical_council_temp")) > 0) {
            $permanentregistrationcertificateofconcernedmedicalcouncil = movedigilockerfile($this->input->post('permanent_registration_certificate_of_concerned_medical_council_temp'));
            $permanent_registration_certificate_of_concerned_medical_council = $permanentregistrationcertificateofconcernedmedicalcouncil["upload_status"]?$permanentregistrationcertificateofconcernedmedicalcouncil["uploaded_path"]:null;
        } else {
            $permanentregistrationcertificateofconcernedmedicalcouncil = cifileupload("permanent_registration_certificate_of_concerned_medical_council");
            $permanent_registration_certificate_of_concerned_medical_council = $permanentregistrationcertificateofconcernedmedicalcouncil["upload_status"]?$permanentregistrationcertificateofconcernedmedicalcouncil["uploaded_path"]:null;
        }




              
  
        if (strlen($this->input->post("noc_from_concerned_medical_council_temp")) > 0) {
            $nocfromconcernedmedicalcouncil = movedigilockerfile($this->input->post('noc_from_concerned_medical_council_temp'));
            $noc_from_concerned_medical_council = $nocfromconcernedmedicalcouncil["upload_status"]?$nocfromconcernedmedicalcouncil["uploaded_path"]:null;
        } else {
            $nocfromconcernedmedicalcouncil = cifileupload("noc_from_concerned_medical_council");
            $noc_from_concerned_medical_council = $nocfromconcernedmedicalcouncil["upload_status"]?$nocfromconcernedmedicalcouncil["uploaded_path"]:null;
        }



        if (strlen($this->input->post("registration_certificate_from_respective_university_or_equivalent_temp")) > 0) {
            $registrationcertificatefromrespectiveuniversityorequivalent = movedigilockerfile($this->input->post('registration_certificate_from_respective_university_or_equivalent_temp'));
            $registration_certificate_from_respective_university_or_equivalent = $registrationcertificatefromrespectiveuniversityorequivalent["upload_status"]?$registrationcertificatefromrespectiveuniversityorequivalent["uploaded_path"]:null;
        } else {
            $registrationcertificatefromrespectiveuniversityorequivalent = cifileupload("registration_certificate_from_respective_university_or_equivalent");
            $registration_certificate_from_respective_university_or_equivalent = $registrationcertificatefromrespectiveuniversityorequivalent["upload_status"]?$registrationcertificatefromrespectiveuniversityorequivalent["uploaded_path"]:null;
        }


      
        if (strlen($this->input->post("all_marksheets_of_mbbs_or_equivalent_temp")) > 0) {
            $allmarksheetsofmbbsorequivalent = movedigilockerfile($this->input->post('all_marksheets_of_mbbs_or_equivalent_temp'));
            $all_marksheets_of_mbbs_or_equivalent = $allmarksheetsofmbbsorequivalent["upload_status"]?$allmarksheetsofmbbsorequivalent["uploaded_path"]:null;
        } else {
            $allmarksheetsofmbbsorequivalent = cifileupload("all_marksheets_of_mbbs_or_equivalent");
            $all_marksheets_of_mbbs_or_equivalent = $allmarksheetsofmbbsorequivalent["upload_status"]?$allmarksheetsofmbbsorequivalent["uploaded_path"]:null;
        }

        
        if (strlen($this->input->post("mbbs_or_equivalent_degree_pass_certificate_from_university_temp")) > 0) {
            $mbbsorequivalentdegreepasscertificatefromuniversity = movedigilockerfile($this->input->post('mbbs_or_equivalent_degree_pass_certificate_from_university_temp'));
            $mbbs_or_equivalent_degree_pass_certificate_from_university = $mbbsorequivalentdegreepasscertificatefromuniversity["upload_status"]?$mbbsorequivalentdegreepasscertificatefromuniversity["uploaded_path"]:null;
        } else {
            $mbbsorequivalentdegreepasscertificatefromuniversity = cifileupload("mbbs_or_equivalent_degree_pass_certificate_from_university");
            $mbbs_or_equivalent_degree_pass_certificate_from_university = $mbbsorequivalentdegreepasscertificatefromuniversity["upload_status"]?$mbbsorequivalentdegreepasscertificatefromuniversity["uploaded_path"]:null;
        }



    

        if (strlen($this->input->post("screening_test_result_from_national_board_of_examination_temp")) > 0) {
            $screeningtestresultfromnationalboardofexamination = movedigilockerfile($this->input->post('screening_test_result_from_national_board_of_examination_temp'));
            $screening_test_result_from_national_board_of_examination = $screeningtestresultfromnationalboardofexamination["upload_status"]?$screeningtestresultfromnationalboardofexamination["uploaded_path"]:null;
        } else {
            $screeningtestresultfromnationalboardofexamination = cifileupload("screening_test_result_from_national_board_of_examination");
            $screening_test_result_from_national_board_of_examination = $screeningtestresultfromnationalboardofexamination["upload_status"]?$screeningtestresultfromnationalboardofexamination["uploaded_path"]:null;
        }



        if (strlen($this->input->post("eligibility_certificate_from_national_medical_commission_temp")) > 0) {
            $eligibilitycertificatefromnationalmedicalcommission = movedigilockerfile($this->input->post('eligibility_certificate_from_national_medical_commission_temp'));
            $eligibility_certificate_from_national_medical_commission = $eligibilitycertificatefromnationalmedicalcommission["upload_status"]?$eligibilitycertificatefromnationalmedicalcommission["uploaded_path"]:null;
        } else {
            $eligibilitycertificatefromnationalmedicalcommission = cifileupload("eligibility_certificate_from_national_medical_commission");
            $eligibility_certificate_from_national_medical_commission = $eligibilitycertificatefromnationalmedicalcommission["upload_status"]?$eligibilitycertificatefromnationalmedicalcommission["uploaded_path"]:null;
        }


        if (strlen($this->input->post("passport_and_visa_with_travel_details_temp")) > 0) {
            $passportandvisawithtraveldetails = movedigilockerfile($this->input->post('passport_and_visa_with_travel_details_temp'));
            $passport_and_visa_with_travel_details = $passportandvisawithtraveldetails["upload_status"]?$passportandvisawithtraveldetails["uploaded_path"]:null;
        } else {
            $passportandvisawithtraveldetails = cifileupload("passport_and_visa_with_travel_details");
            $passport_and_visa_with_travel_details = $passportandvisawithtraveldetails["upload_status"]?$passportandvisawithtraveldetails["uploaded_path"]:null;
        }


        if (strlen($this->input->post("all_documents_related_to_medical_college_details_temp")) > 0) {
            $alldocumentsrelatedtomedicalcollegedetails = movedigilockerfile($this->input->post('all_documents_related_to_medical_college_details_temp'));
            $all_documents_related_to_medical_college_details = $alldocumentsrelatedtomedicalcollegedetails["upload_status"]?$alldocumentsrelatedtomedicalcollegedetails["uploaded_path"]:null;
        } else {
            $alldocumentsrelatedtomedicalcollegedetails = cifileupload("all_documents_related_to_medical_college_details");
            $all_documents_related_to_medical_college_details = $alldocumentsrelatedtomedicalcollegedetails["upload_status"]?$alldocumentsrelatedtomedicalcollegedetails["uploaded_path"]:null;
        }


        

        if (strlen($this->input->post("mbbs_marksheet_temp")) > 0) {
            $mbbsmarksheet = movedigilockerfile($this->input->post('mbbs_marksheet_temp'));
            $mbbs_marksheet = $mbbsmarksheet["upload_status"]?$mbbsmarksheet["uploaded_path"]:null;
        } else {
            $mbbsmarksheet = cifileupload("mbbs_marksheet");
            $mbbs_marksheet = $mbbsmarksheet["upload_status"]?$mbbsmarksheet["uploaded_path"]:null;
        }



        if (strlen($this->input->post("pass_certificate_temp")) > 0) {
            $passcertificate = movedigilockerfile($this->input->post('pass_certificate_temp'));
            $pass_certificate = $passcertificate["upload_status"]?$passcertificate["uploaded_path"]:null;
        } else {
            $passcertificate = cifileupload("pass_certificate");
            $pass_certificate = $passcertificate["upload_status"]?$passcertificate["uploaded_path"]:null;
        }

       

        if (strlen($this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_temp")) > 0) {
            $provisionalregistrationcertificateofconcernedassamcouncilofmedicalregistration = movedigilockerfile($this->input->post('provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_temp'));
            $provisional_registration_certificate_of_concerned_assam_council_of_medical_registration = $provisionalregistrationcertificateofconcernedassamcouncilofmedicalregistration["upload_status"]?$provisionalregistrationcertificateofconcernedassamcouncilofmedicalregistration["uploaded_path"]:null;
        } else {
            $provisionalregistrationcertificateofconcernedassamcouncilofmedicalregistration = cifileupload("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration");
            $provisional_registration_certificate_of_concerned_assam_council_of_medical_registration = $provisionalregistrationcertificateofconcernedassamcouncilofmedicalregistration["upload_status"]?$provisionalregistrationcertificateofconcernedassamcouncilofmedicalregistration["uploaded_path"]:null;
        }

        
 
        if (strlen($this->input->post("annexure_temp")) > 0) {
            $annexure = movedigilockerfile($this->input->post('annexure_temp'));
            $annexure = $annexure["upload_status"]?$annexure["uploaded_path"]:null;
        } else {
            $annexure = cifileupload("annexure");
            $annexure = $annexure["upload_status"]?$annexure["uploaded_path"]:null;
        }



        $uploadedFiles = array(
            "admit_birth_old" => strlen($admit_birth)?$admit_birth:$this->input->post("admit_birth_old"),

            "passport_photo_old" => strlen($passport_photo)?$passport_photo:$this->input->post("passport_photo_old"),


            "signature_old" => strlen($signature)?$signature:$this->input->post("signature_old"),


            "annexure_old" => strlen($annexure)?$annexure:$this->input->post("annexure_old"),

            "hs_marksheet_old" => strlen($hs_marksheet)?$hs_marksheet:$this->input->post("hs_marksheet_old"),

            "reg_certificate_old" => strlen($reg_certificate)?$reg_certificate:$this->input->post("reg_certificate_old"),
            
            "reg_certificate_of_concerned_university_old" => strlen($reg_certificate_of_concerned_university)?$reg_certificate_of_concerned_university:$this->input->post("reg_certificate_of_concerned_university_old"),

            "mbbs_pass_certificate_from_university_old" => strlen($mbbs_pass_certificate_from_university)?$mbbs_pass_certificate_from_university:$this->input->post("mbbs_pass_certificate_from_university_old"),

            
            "permanent_registration_certificate_of_concerned_medical_council_old" => strlen($permanent_registration_certificate_of_concerned_medical_council)?$permanent_registration_certificate_of_concerned_medical_council:$this->input->post("permanent_registration_certificate_of_concerned_medical_council_old"),


            "noc_from_concerned_medical_council_old" => strlen($noc_from_concerned_medical_council)?$noc_from_concerned_medical_council:$this->input->post("noc_from_concerned_medical_council_old"),

            
            "registration_certificate_from_respective_university_or_equivalent_old" => strlen($registration_certificate_from_respective_university_or_equivalent)?$registration_certificate_from_respective_university_or_equivalent:$this->input->post("registration_certificate_from_respective_university_or_equivalent_old"),

            "all_marksheets_of_mbbs_or_equivalent_old" => strlen($all_marksheets_of_mbbs_or_equivalent)?$all_marksheets_of_mbbs_or_equivalent:$this->input->post("all_marksheets_of_mbbs_or_equivalent_old"),


            "mbbs_or_equivalent_degree_pass_certificate_from_university_old" => strlen($mbbs_or_equivalent_degree_pass_certificate_from_university)?$mbbs_or_equivalent_degree_pass_certificate_from_university:$this->input->post("mbbs_or_equivalent_degree_pass_certificate_from_university_old"),


            

            "eligibility_certificate_from_national_medical_commission_old" => strlen($eligibility_certificate_from_national_medical_commission)?$eligibility_certificate_from_national_medical_commission:$this->input->post("eligibility_certificate_from_national_medical_commission_old"),


            
            "screening_test_result_from_national_board_of_examination_old" => strlen($screening_test_result_from_national_board_of_examination)?$screening_test_result_from_national_board_of_examination:$this->input->post("screening_test_result_from_national_board_of_examination_old"),

            

            "passport_and_visa_with_travel_details_old" => strlen($passport_and_visa_with_travel_details)?$passport_and_visa_with_travel_details:$this->input->post("passport_and_visa_with_travel_details_old"),

            
            "all_documents_related_to_medical_college_details_old" => strlen($all_documents_related_to_medical_college_details)?$all_documents_related_to_medical_college_details:$this->input->post("all_documents_related_to_medical_college_details_old"),

            

            "mbbs_marksheet_old" => strlen($mbbs_marksheet)?$mbbs_marksheet:$this->input->post("mbbs_marksheet_old"),

            "pass_certificate_old" => strlen($pass_certificate)?$pass_certificate:$this->input->post("pass_certificate_old"),

            "internship_completion_certificate_old" => strlen($internship_completion_certificate)?$internship_completion_certificate:$this->input->post("internship_completion_certificate_old"),



            "provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_old" => strlen($provisional_registration_certificate_of_concerned_assam_council_of_medical_registration)?$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration:$this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_old"),


        );
        // pre($uploadedFiles);
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        // pre($uploadedFiles);

        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            // pre(validation_errors());
            $this->queryform($objId);
        } else {   
            
            // pre($objId);

            
            $dbRow = $this->registration_model->get_by_doc_id($objId);
        //    pre($dbRow);
              //  exit();
            if(count((array)$dbRow)) {

                // pre("OK");
                
                //$backupRow = (array)$dbRow;
                //unset($backupRow["_id"]);

                $data = array(
                    // 'form_data.applicant_name' => $this->input->post("applicant_name"),

                    // 'form_data.admit_birth_type' => $this->input->post("admit_birth_type"),
                    // 'form_data.admit_birth' => $admit_birth,
                    'form_data.admit_birth_type' => $this->input->post("admit_birth_type"),
                    'form_data.admit_birth' => strlen($admit_birth)?$admit_birth:$this->input->post("admit_birth_old"),

                    'form_data.passport_photo_type' => $this->input->post("passport_photo_type"),
                    'form_data.passport_photo' => strlen($passport_photo)?$passport_photo:$this->input->post("passport_photo_old"),

                    'form_data.signature_type' => $this->input->post("signature_type"),
                    'form_data.signature' => strlen($signature)?$signature:$this->input->post("signature_old"),

                    'form_data.hs_marksheet_type' => $this->input->post("hs_marksheet_type"),
                    'form_data.hs_marksheet' => strlen($hs_marksheet)?$hs_marksheet:$this->input->post("hs_marksheet_old"),

                    'form_data.all_documents_related_to_medical_college_details_type' => $this->input->post("all_documents_related_to_medical_college_details_type"),
                    'form_data.all_documents_related_to_medical_college_details' => strlen($all_documents_related_to_medical_college_details)?$all_documents_related_to_medical_college_details:$this->input->post("all_documents_related_to_medical_college_details_old"),

                    'form_data.all_marksheets_of_mbbs_or_equivalent_type' => $this->input->post("all_marksheets_of_mbbs_or_equivalent_type"),
                    'form_data.all_marksheets_of_mbbs_or_equivalent' => strlen($all_marksheets_of_mbbs_or_equivalent)?$all_marksheets_of_mbbs_or_equivalent:$this->input->post("all_marksheets_of_mbbs_or_equivalent_old"),

                    'form_data.eligibility_certificate_from_national_medical_commission_type' => $this->input->post("eligibility_certificate_from_national_medical_commission_type"),
                    'form_data.eligibility_certificate_from_national_medical_commission' => strlen($eligibility_certificate_from_national_medical_commission)?$eligibility_certificate_from_national_medical_commission:$this->input->post("eligibility_certificate_from_national_medical_commission_old"),

                    'form_data.internship_completion_certificate_type' => $this->input->post("internship_completion_certificate_type"),
                    'form_data.internship_completion_certificate' => strlen($internship_completion_certificate)?$internship_completion_certificate:$this->input->post("internship_completion_certificate_old"),

                    'form_data.internship_completion_certificate_type' => $this->input->post("internship_completion_certificate_type"),
                    'form_data.internship_completion_certificate' => strlen($internship_completion_certificate)?$internship_completion_certificate:$this->input->post("internship_completion_certificate_old"),

                    'form_data.mbbs_marksheet_type' => $this->input->post("mbbs_marksheet_type"),
                    'form_data.mbbs_marksheet' => strlen($mbbs_marksheet)?$mbbs_marksheet:$this->input->post("mbbs_marksheet_old"),

                    'form_data.mbbs_or_equivalent_degree_pass_certificate_from_university_type' => $this->input->post("mbbs_or_equivalent_degree_pass_certificate_from_university_type"),
                    'form_data.mbbs_or_equivalent_degree_pass_certificate_from_university' => strlen($mbbs_or_equivalent_degree_pass_certificate_from_university)?$mbbs_or_equivalent_degree_pass_certificate_from_university:$this->input->post("mbbs_or_equivalent_degree_pass_certificate_from_university_old"),

                    'form_data.mbbs_pass_certificate_from_university_type' => $this->input->post("mbbs_pass_certificate_from_university_type"),
                    'form_data.mbbs_pass_certificate_from_university' => strlen($mbbs_pass_certificate_from_university)?$mbbs_pass_certificate_from_university:$this->input->post("mbbs_pass_certificate_from_university_old"),

                    'form_data.noc_from_concerned_medical_council_type' => $this->input->post("noc_from_concerned_medical_council_type"),
                    'form_data.noc_from_concerned_medical_council' => strlen($noc_from_concerned_medical_council)?$noc_from_concerned_medical_council:$this->input->post("noc_from_concerned_medical_council_old"),

                    'form_data.pass_certificate_type' => $this->input->post("pass_certificate_type"),
                    'form_data.pass_certificate' => strlen($pass_certificate)?$pass_certificate:$this->input->post("pass_certificate_old"),

                    'form_data.passport_and_visa_with_travel_details_type' => $this->input->post("passport_and_visa_with_travel_details_type"),
                    'form_data.passport_and_visa_with_travel_details' => strlen($passport_and_visa_with_travel_details)?$passport_and_visa_with_travel_details:$this->input->post("passport_and_visa_with_travel_details_old"),

                    'form_data.permanent_registration_certificate_of_concerned_medical_council_type' => $this->input->post("permanent_registration_certificate_of_concerned_medical_council_type"),
                    'form_data.permanent_registration_certificate_of_concerned_medical_council' => strlen($permanent_registration_certificate_of_concerned_medical_council)?$permanent_registration_certificate_of_concerned_medical_council:$this->input->post("permanent_registration_certificate_of_concerned_medical_council_old"),

                    'form_data.provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type' => $this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type"),
                    'form_data.provisional_registration_certificate_of_concerned_assam_council_of_medical_registration' => strlen($provisional_registration_certificate_of_concerned_assam_council_of_medical_registration)?$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration:$this->input->post("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_old"),

                    'form_data.reg_certificate_type' => $this->input->post("reg_certificate_type"),
                    'form_data.reg_certificate' => strlen($reg_certificate)?$reg_certificate:$this->input->post("reg_certificate_old"),

                    'form_data.reg_certificate_of_concerned_university_type' => $this->input->post("reg_certificate_of_concerned_university_type"),
                    'form_data.reg_certificate_of_concerned_university' => strlen($reg_certificate_of_concerned_university)?$reg_certificate_of_concerned_university:$this->input->post("reg_certificate_of_concerned_university_old"),

                    'form_data.registration_certificate_from_respective_university_or_equivalent_type' => $this->input->post("registration_certificate_from_respective_university_or_equivalent_type"),
                    'form_data.registration_certificate_from_respective_university_or_equivalent' => strlen($registration_certificate_from_respective_university_or_equivalent)?$registration_certificate_from_respective_university_or_equivalent:$this->input->post("registration_certificate_from_respective_university_or_equivalent_old"),

                    'form_data.screening_test_result_from_national_board_of_examination_type' => $this->input->post("screening_test_result_from_national_board_of_examination_type"),
                    'form_data.screening_test_result_from_national_board_of_examination' => strlen($screening_test_result_from_national_board_of_examination)?$screening_test_result_from_national_board_of_examination:$this->input->post("screening_test_result_from_national_board_of_examination_old"),

                    'form_data.annexure_type' => $this->input->post("annexure_type"),
                    'form_data.annexure' => strlen($annexure)?$annexure:$this->input->post("annexure_old"),



                );

                // pre($data);
                // return;

                // pre($data);
                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

                $processing_history = $dbRow->processing_history??array();
                $processing_history[] = array(
                    "processed_by" => "Query submitted by ".$dbRow->form_data->applicant_name,
                    "action_taken" => "Query submitted",
                    "remarks" => "Query submitted by ".$dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $data = array(
                    "service_data.appl_status" => "QA",
                    'processing_history' => $processing_history,
                    'status' => "QUERY_ANSWERED"
                );
        
                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

                $this->session->set_flashdata('success','Your application has been successfully updated');
                redirect('spservices/permanent_registration_mbbs/registration/preview/'.$objId);
            } else {
                $this->session->set_flashdata('fail','Unable to update data!!! Please try again.');
                $this->index();
            }//End of if else
        }//End of if else      
    }//End of querysubmit()

    function getID($length) { 
        $rtps_trans_id = $this->generateID($length);
        while ($this->registration_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        }//End of while()
        return $rtps_trans_id;
    }//End of getID()

    public function generateID($length) {
        $date = date('Y');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-PROMD/" . $date."/" .$number;
        return $str;
    }//End of generateID()    

    public function get_age(){
        $dob = $this->input->post("dob");        
        if(strlen($dob) == 10) {
            $date_of_birth = new DateTime($dob);
            $nowTime = new DateTime();
            $interval = $date_of_birth->diff($nowTime);
            echo $interval->format('%y Years %m Months and %d Days');
        } else {
            echo "Invalid date format";
        }//End of if else
    }//End of get_age()

    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
           return false;
        }//End of if else
    }//End of checkObjectId()

    public function submit_to_backend($obj,$show_ack=false){
        if($obj){
            $dbRow = $this->registration_model->get_by_doc_id($obj);

            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }

            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")){
                //procesing data
                $processing_history = $dbRow->processing_history??array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by ".$dbRow->form_data->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by ".$dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $this->load->model('upms/users_model');
                $userFilter = array('user_services.service_code' => $this->serviceId, 'user_roles.role_code' => 'LDA');
                $userRows = $this->users_model->get_rows($userFilter);

                $current_users = array();
                if($userRows) {
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

                $data_to_update=array(
                    'service_data.appl_status'=>'submitted',
                    'service_data.submission_date'=>new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'current_users'=>$current_users,
                    'processing_history'=>$processing_history
                );
                $this->registration_model->update($obj, $data_to_update);

                //Sending submission SMS
                $nowTime = date('Y-m-d H:i:s');
                $sms = array(
                    "mobile" => (int)$dbRow->form_data->mobile,
                    "applicant_name" => $dbRow->form_data->applicant_name,
                    "service_name" => 'ACMR - Registration of Additional Degrees',
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

    private function my_transactions(){
        $user=$this->session->userdata();
        if(isset($user['role']) && !empty($user['role'])){
          redirect(base_url('iservices/admin/my-transactions'));
        }else{
          redirect(base_url('iservices/transactions'));
        }
    }

    public function download_certificate($objId = null)
    {
        if (!empty($objId)) {
            $dbRow = $this->registration_model->get_by_doc_id($objId);
            // var_dump($dbRow); die;
            if (count((array)$dbRow) && isset($dbRow->form_data->certificate)) {
                if (file_exists($dbRow->form_data->certificate)) {
                    cifiledownload($dbRow->form_data->certificate);
                }
            } else {
                $this->session->set_flashdata('errmessage', 'Something went wrong!, Please try later.');
                $this->my_transactions();
            }
        }
    }//End of download_certificate()

    public function output_certificate($obj_id=null) {
        $this->load->view('permanent_registration_mbbs/output_certificate');
    }//End of index()

    public function test(){

        $this->form_validation->set_rules('obj_id', 'Application Id', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            echo 'error';
        } else {
            echo 'no error';
        }
    }
}//End of Castecertificate
