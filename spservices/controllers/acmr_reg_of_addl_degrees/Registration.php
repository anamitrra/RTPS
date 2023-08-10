<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps {
    
    private $serviceName = "Registration of Additional Degrees- ACMR";
    Private $serviceId = "ACMRREGAD";

    public function __construct() {
        parent::__construct();
        $this->load->model('acmr_reg_of_addl_degrees/registration_model');
        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->helper('smsprovider');
        $this->load->helper('log');
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
          }else{
            $this->slug = "user";
          }
    }//End of __construct()

    public function index($obj_id=null) {
        $data=array("pageTitle" => "Registration of Additional Degrees");
        $filter = array(
            "_id" =>new ObjectId($obj_id), 
            "service_data.appl_status" => "DRAFT"
        );
        $data["dbrow"] = $this->registration_model->get_row($filter);
        $data['usser_type']=$this->slug;
       
        $this->load->view('includes/frontend/header');
        $this->load->view('acmr_reg_of_addl_degrees/acmr_reg_of_addl_degrees',$data);
        $this->load->view('includes/frontend/footer');
    }//End of index()

    public function submit(){
       // pre($this->input->post("others_type"));
        $objId = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");
        
        $this->form_validation->set_rules('applicant_name', 'Applicant name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|integer|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|strip_tags|valid_email');
        $this->form_validation->set_rules('permanent_addr', 'Permanent Address', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('correspondence_addr', 'Correspondence Address', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|xss_clean|strip_tags|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]|max_length[10]');
        $this->form_validation->set_rules('aadhar_no', 'Aadhar No.', 'trim|xss_clean|strip_tags|integer|exact_length[12]');
         
        $this->form_validation->set_rules('primary_qualification', 'Primary Qualification', 'trim|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]'); 
        $this->form_validation->set_rules('primary_qua_doc', 'Date of Completion', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('primary_qua_college_name', 'College Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]'); 
        $this->form_validation->set_rules('primary_qua_college_addr', 'College Address', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]'); 
        $this->form_validation->set_rules('primary_qua_course_dur', 'Course Duration', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('primary_qua_doci', 'Date of Completion of Internship*', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('primary_qua_university_award_intership', 'University awarding the Internship*', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]'); 

        $this->form_validation->set_rules('acmrrno', 'Assam Council of Medical Registration Registration Number', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('registration_date', 'Registration Date', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('original_registration_number', 'Original Registration Number', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('addl_qualification[]', 'Additional Qualification', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('addl_college_name[]', 'College Name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('addl_university_name[]', 'University Name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('addl_date_of_qualification[]', 'Date of Qualification', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $obj_id = strlen($objId)?$objId:null;
            $this->index($obj_id);
        } else {            
            $appl_ref_no = $this->getID(7); 
            $sessionUser=$this->session->userdata();
            
            $addl_qualification =  $this->input->post("addl_qualification");
            $addl_college_name =  $this->input->post("addl_college_name");                        
            $addl_university_name =  $this->input->post("addl_university_name");
            $addl_date_of_qualification =  $this->input->post("addl_date_of_qualification");
            $addl_qualification_details = array();
            if(count($addl_college_name)) {
                foreach($addl_college_name as $k=>$addl_college_names) {

                    $addl_qualification_detail = array(
                        "addl_qualification" => $addl_qualification[$k],
                        "addl_college_name" => $addl_college_name[$k],
                        "addl_university_name" => $addl_university_name[$k],
                        "addl_date_of_qualification" => $addl_date_of_qualification[$k]
                    );
                    $addl_qualification_details[] = $addl_qualification_detail;
                }//End of foreach()        
            }//End of if
            
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
                "service_timeline" => "52 Days",
                "appl_status" => "DRAFT",
                "district" => "Guwahati",
            ];
           
            $form_data = [     
                'applicant_name' => $this->input->post("applicant_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'dob' => $this->input->post("dob"),
                'mobile' => $this->input->post("mobile"),
                'email' => $this->input->post("email"),
                'permanent_addr' => $this->input->post("permanent_addr"),
                'correspondence_addr' => $this->input->post("correspondence_addr"),
                'pan_no' => trim($this->input->post("pan_no")),
                'aadhar_no' => $this->input->post("aadhar_no"),
                
                'primary_qualification' => trim($this->input->post("primary_qualification")),
                'primary_qua_doc' => $this->input->post("primary_qua_doc"),
                'primary_qua_college_name' => $this->input->post("primary_qua_college_name"),
                'primary_qua_college_addr' => $this->input->post("primary_qua_college_addr"),
                'primary_qua_course_dur' => $this->input->post("primary_qua_course_dur"),
                'primary_qua_doci' => $this->input->post("primary_qua_doci"),
                'primary_qua_university_award_intership' => $this->input->post("primary_qua_university_award_intership"),
                
                'acmrrno' => $this->input->post("acmrrno"),
                'registration_date' => $this->input->post("registration_date"),
                'original_registration_number'=>$this->input->post("original_registration_number"),

                'addl_qualification_details' => $addl_qualification_details,
                'add_degree_reg_no' => $this->input->post("add_degree_reg_no"),

                'user_id' => $sessionUser['userId']->{'$id'},    
                'user_type' => $this->slug,

                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];

            if(strlen($objId)) {
                $form_data["photo_of_the_candidate_type"] = $this->input->post("photo_of_the_candidate_type");
                $form_data["photo_of_the_candidate"] = $this->input->post("photo_of_the_candidate");-
                $form_data["signature_of_the_candidate_type"] = $this->input->post("signature_of_the_candidate_type");
                $form_data["signature_of_the_candidate"] = $this->input->post("signature_of_the_candidate");

                $form_data["pass_certificate_from_uni_coll_type"] = $this->input->post("pass_certificate_from_uni_coll_type");
                $form_data["pass_certificate_from_uni_coll"] = $this->input->post("pass_certificate_from_uni_coll");
                $form_data["pg_degree_dip_marksheet_type"] = $this->input->post("pg_degree_dip_marksheet_type");
                $form_data["pg_degree_dip_marksheet"] = $this->input->post("pg_degree_dip_marksheet");
                $form_data["prc_acmr_type"] = $this->input->post("prc_acmr_type");
                $form_data["prc_acmr"] = $this->input->post("prc_acmr");
                
                // $form_data["soft_copy_type"] = $this->input->post("soft_copy_type");
                // $form_data["soft_copy"] = $this->input->post("soft_copy");

                if (!empty($this->input->post("other_addl_degree_type"))) {
                    $form_data["other_addl_degree_type"] = $this->input->post("other_addl_degree_type");
                    $form_data["other_addl_degree"] = $this->input->post("other_addl_degree");
                }
            }

            $inputs = [
                'service_data'=>$service_data,
                'form_data' => $form_data
            ];

            // pre($inputs);
                
            if(strlen($objId)) {

                //pre($inputs);
                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                $this->session->set_flashdata('success','Your application has been successfully submitted');
                redirect('spservices/acmr_reg_of_addl_degrees/registration/fileuploads/'.$objId);
            } else {
                //pre($inputs);
                $insert=$this->registration_model->insert($inputs);
                if($insert){
                    $objectId=$insert['_id']->{'$id'};
                    $this->session->set_flashdata('success','Your application has been successfully submitted');
                    redirect('spservices/acmr_reg_of_addl_degrees/registration/fileuploads/'.$objectId);
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
        if(count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "pageTitle" => "Attached Enclosures for ".$this->serviceName,
                "obj_id"=>$objId,               
                "dbrow" => $dbRow
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('acmr_reg_of_addl_degrees/acmr_reg_of_addl_degreesuploads_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found');
            redirect('spservices/acmr_reg_of_addl_degrees/registration');
        }//End of if else
    }//End of fileuploads()

    public function submitfiles() { 
        //pre($this->input->post());       
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }

        $this->form_validation->set_rules('photo_of_the_candidate_type', 'Photo of the Candidate', 'required');
        $this->form_validation->set_rules('signature_of_the_candidate_type', 'Signature of the Candidate', 'required');
        $this->form_validation->set_rules('pass_certificate_from_uni_coll_type', 'Pass Certificate from University/College', 'required');
        $this->form_validation->set_rules('pg_degree_dip_marksheet_type', 'PGDegree/DiplomaMarksheet', 'required');
        $this->form_validation->set_rules('prc_acmr_type', 'Permanent Registration Certificate of Assam Council of Medical Registration', 'required');

                if (empty($this->input->post("photo_of_the_candidate_old"))) {
            if(((empty($this->input->post("photo_of_the_candidate_type"))) && (($_FILES['photo_of_the_candidate']['name'] != "") || (!empty($this->input->post("photo_of_the_candidate_temp"))))) || ((!empty($this->input->post("photo_of_the_candidate_type"))) && (($_FILES['photo_of_the_candidate']['name'] == "") && (empty($this->input->post("photo_of_the_candidate_temp")))))) {
            
                $this->form_validation->set_rules('photo_of_the_candidate_type', 'Passport Photo Type', 'required');
                $this->form_validation->set_rules('photo_of_the_candidate', 'Passport Photo', 'required');
            }
        }


                if (empty($this->input->post("signature_of_the_candidate_old"))) {
            if(((empty($this->input->post("signature_of_the_candidate_type"))) && (($_FILES['signature_of_the_candidate']['name'] != "") || (!empty($this->input->post("signature_of_the_candidate_temp"))))) || ((!empty($this->input->post("signature_of_the_candidate_type"))) && (($_FILES['signature_of_the_candidate']['name'] == "") && (empty($this->input->post("signature_of_the_candidate_temp")))))) {
            
                $this->form_validation->set_rules('signature_of_the_candidate_type', 'Signature Type', 'required');
                $this->form_validation->set_rules('signature_of_the_candidate', 'Signature', 'required');
            }
        }


        if (empty($this->input->post("pass_certificate_from_uni_coll_old"))) {
            if(((empty($this->input->post("pass_certificate_from_uni_coll_type"))) && (($_FILES['pass_certificate_from_uni_coll']['name'] != "") || (!empty($this->input->post("pass_certificate_from_uni_coll_temp"))))) || ((!empty($this->input->post("pass_certificate_from_uni_coll_type"))) && (($_FILES['pass_certificate_from_uni_coll']['name'] == "") && (empty($this->input->post("pass_certificate_from_uni_coll_temp")))))) {
            
                $this->form_validation->set_rules('pass_certificate_from_uni_coll_type', 'Pass Certificate from University/College Document Type', 'required');
                $this->form_validation->set_rules('pass_certificate_from_uni_coll', 'Pass Certificate from University/College Document', 'required');
            }
        }

        if (empty($this->input->post("pg_degree_dip_marksheet_old"))) {
            if(((empty($this->input->post("pg_degree_dip_marksheet_type"))) && (($_FILES['pg_degree_dip_marksheet']['name'] != "") || (!empty($this->input->post("pg_degree_dip_marksheet_temp"))))) || ((!empty($this->input->post("pg_degree_dip_marksheet_type"))) && (($_FILES['pg_degree_dip_marksheet']['name'] == "") && (empty($this->input->post("pg_degree_dip_marksheet_temp")))))) {
            
                $this->form_validation->set_rules('pg_degree_dip_marksheet_type', 'PGDegree/DiplomaMarksheet Document Type', 'required');
                $this->form_validation->set_rules('pg_degree_dip_marksheet', 'PGDegree/DiplomaMarksheet Document', 'required');
            }
        }

        if (empty($this->input->post("prc_acmr_old"))) {
            if(((empty($this->input->post("prc_acmr_type"))) && (($_FILES['prc_acmr']['name'] != "") || (!empty($this->input->post("prc_acmr_temp"))))) || ((!empty($this->input->post("prc_acmr_type"))) && (($_FILES['prc_acmr']['name'] == "") && (empty($this->input->post("prc_acmr_temp")))))) {
            
                $this->form_validation->set_rules('prc_acmr_type', 'Permanent Registration Certificate of Assam Council of Medical Registration Document Type', 'required');
                $this->form_validation->set_rules('prc_acmr', 'Permanent Registration Certificate of Assam Council of Medical Registration Document', 'required');
            }
        }

        // if (empty($this->input->post("soft_copy_old")) && $this->slug !== 'user') {
        //     if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {
                
        //         $this->form_validation->set_rules('soft_copy_type', 'Soft Copy Type required', 'required');
        //         $this->form_validation->set_rules('soft_copy', 'Soft Copy document required', 'required');
        //     }
        // }

        if (empty($this->input->post("other_addl_degree_old"))) {
            if ((empty($this->input->post("other_addl_degree_type"))) && (($_FILES['other_addl_degree']['name'] != "")|| (!empty($this->input->post("other_addl_degree_temp"))))) {
            
                $this->form_validation->set_rules('other_addl_degree_type', 'Other Additional Degrees', 'required');
            }
    
            if ((!empty($this->input->post("other_addl_degree_type"))) && (($_FILES['other_addl_degree']['name'] == "")|| (empty($this->input->post("other_addl_degree_temp"))))) {
                $this->form_validation->set_rules('other_addl_degree', 'Other Additional Degrees', 'required');
            }
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        $photoOfTheCandidate = cifileupload("photo_of_the_candidate");
        $photo_of_the_candidate = $photoOfTheCandidate["upload_status"]?$photoOfTheCandidate["uploaded_path"]:null;

        $signatureOfTheCandidate = cifileupload("signature_of_the_candidate");
        $signature_of_the_candidate = $signatureOfTheCandidate["upload_status"]?$signatureOfTheCandidate["uploaded_path"]:null;

        if (strlen($this->input->post("pass_certificate_from_uni_coll_temp")) > 0) {
            $passCertificateFromUniColl = movedigilockerfile($this->input->post('pass_certificate_from_uni_coll_temp'));
            $pass_certificate_from_uni_coll = $passCertificateFromUniColl["upload_status"]?$passCertificateFromUniColl["uploaded_path"]:null;
        } else {
            $passCertificateFromUniColl = cifileupload("pass_certificate_from_uni_coll");
            $pass_certificate_from_uni_coll = $passCertificateFromUniColl["upload_status"]?$passCertificateFromUniColl["uploaded_path"]:null;
        }

        if (strlen($this->input->post("pg_degree_dip_marksheet_temp")) > 0) {
            $pgDegreeDipMarksheet = movedigilockerfile($this->input->post('pg_degree_dip_marksheet_temp'));
            $pg_degree_dip_marksheet = $pgDegreeDipMarksheet["upload_status"]?$pgDegreeDipMarksheet["uploaded_path"]:null;
        } else {
            $pgDegreeDipMarksheet = cifileupload("pg_degree_dip_marksheet");
            $pg_degree_dip_marksheet = $pgDegreeDipMarksheet["upload_status"]?$pgDegreeDipMarksheet["uploaded_path"]:null;
        }

        if (strlen($this->input->post("prc_acmr_temp")) > 0) {
            $prcAcmr = movedigilockerfile($this->input->post('prc_acmr_temp'));
            $prc_acmr = $prcAcmr["upload_status"]?$prcAcmr["uploaded_path"]:null;
        } else {
            $prcAcmr = cifileupload("prc_acmr");
            $prc_acmr = $prcAcmr["upload_status"]?$prcAcmr["uploaded_path"]:null;
        }

        if (strlen($this->input->post("other_addl_degree_temp")) > 0) {
            $otherAddlAegree = movedigilockerfile($this->input->post('other_addl_degree_temp'));
            $other_addl_degree = $otherAddlAegree["upload_status"]?$otherAddlAegree["uploaded_path"]:null;
        } else {
            $otherAddlAegree = cifileupload("other_addl_degree");
            $other_addl_degree = $otherAddlAegree["upload_status"]?$otherAddlAegree["uploaded_path"]:null;
        }

        // $softCopy = cifileupload("soft_copy");
        // $soft_copy = $softCopy["upload_status"]?$softCopy["uploaded_path"]:null;
        
        $uploadedFiles = array(
            "photo_of_the_candidate_old" => strlen($photo_of_the_candidate)?$photo_of_the_candidate:$this->input->post("photo_of_the_candidate_old"),
            "signature_of_the_candidate_old" => strlen($signature_of_the_candidate)?$signature_of_the_candidate:$this->input->post("signature_of_the_candidate_old"),
            "pass_certificate_from_uni_coll_old" => strlen($pass_certificate_from_uni_coll)?$pass_certificate_from_uni_coll:$this->input->post("pass_certificate_from_uni_coll_old"),
            "pg_degree_dip_marksheet_old" => strlen($pg_degree_dip_marksheet)?$pg_degree_dip_marksheet:$this->input->post("pg_degree_dip_marksheet_old"),
            "prc_acmr_old" => strlen($prc_acmr)?$prc_acmr:$this->input->post("prc_acmr_old"),
            "other_addl_degree_old" => strlen($other_addl_degree)?$other_addl_degree:$this->input->post("other_addl_degree_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.photo_of_the_candidate_type' => $this->input->post("photo_of_the_candidate_type"),
                'form_data.signature_of_the_candidate_type' => $this->input->post("signature_of_the_candidate_type"),
                'form_data.pass_certificate_from_uni_coll_type' => $this->input->post("pass_certificate_from_uni_coll_type"),
                'form_data.pg_degree_dip_marksheet_type' => $this->input->post("pg_degree_dip_marksheet_type"),
                'form_data.prc_acmr_type' => $this->input->post("prc_acmr_type"),
                'form_data.other_addl_degree_type' => $this->input->post("other_addl_degree_type"),
                'form_data.photo_of_the_candidate' => strlen($photo_of_the_candidate)?$photo_of_the_candidate:$this->input->post("photo_of_the_candidate_old"),
                'form_data.signature_of_the_candidate' => strlen($signature_of_the_candidate)?$signature_of_the_candidate:$this->input->post("signature_of_the_candidate_old"),
                'form_data.pass_certificate_from_uni_coll' => strlen($pass_certificate_from_uni_coll)?$pass_certificate_from_uni_coll:$this->input->post("pass_certificate_from_uni_coll_old"),
                'form_data.pg_degree_dip_marksheet' => strlen($pg_degree_dip_marksheet)?$pg_degree_dip_marksheet:$this->input->post("pg_degree_dip_marksheet_old"),
                'form_data.prc_acmr' => strlen($prc_acmr)?$prc_acmr:$this->input->post("prc_acmr_old")
            );

            if (!empty($this->input->post("other_addl_degree_type"))) {
                $data["form_data.other_addl_degree_type"] = $this->input->post("other_addl_degree_type");
                $data["form_data.other_addl_degree"] = strlen($other_addl_degree)?$other_addl_degree:$this->input->post("other_addl_degree_old");
            }
            
            $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success','Your application has been successfully submitted');
            redirect('spservices/acmr_reg_of_addl_degrees/registration/preview/'.$objId);
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
            $this->load->view('acmr_reg_of_addl_degrees/acmr_reg_of_addl_degreespreview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found');
            redirect('spservices/acmr_reg_of_addl_degrees/registration');
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
            $this->load->view('acmr_reg_of_addl_degrees/acmr_reg_of_addl_degreesapplication_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found');
            redirect('spservices/acmr_reg_of_addl_degrees/registration');
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
            $this->load->view('includes/frontend/header');
            $this->load->view('acmr_reg_of_addl_degrees/acmr_reg_of_addl_degreestrack_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found');
            redirect('spservices/acmr_reg_of_addl_degrees/registration');
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
                $this->load->view('acmr_reg_of_addl_degrees/acmr_reg_of_addl_degreesquery_view',$data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found in query mode against object id : '.$objId);
                redirect('spservices/acmr_reg_of_addl_degrees/registration');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/acmr_reg_of_addl_degrees/registration');
        }//End of if else
    }//End of query()

    public function querysubmit() {        
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");

        $this->form_validation->set_rules('photo_of_the_candidate_type', 'Photo of the Candidate', 'required');
        $this->form_validation->set_rules('pass_certificate_from_uni_coll_type', 'Pass Certificate from University/College', 'required');
        $this->form_validation->set_rules('pg_degree_dip_marksheet_type', 'PGDegree/DiplomaMarksheet', 'required');
        $this->form_validation->set_rules('prc_acmr_type', 'Permanent Registration Certificate of Assam Council of Medical Registration', 'required');

        // if (empty($this->input->post("soft_copy_old")) && $this->slug !== 'user') {
        //     if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {
                
        //         $this->form_validation->set_rules('soft_copy_type', 'Soft Copy Type required', 'required');
        //         $this->form_validation->set_rules('soft_copy', 'Soft Copy document required', 'required');
        //     }
        // }

        if (empty($this->input->post("other_addl_degree_old"))) {
            if ((empty($this->input->post("other_addl_degree_type"))) && (($_FILES['other_addl_degree']['name'] != ""))) {
            
                $this->form_validation->set_rules('other_addl_degree_type', 'Other Additional Degrees', 'required');
            }
    
            if ((!empty($this->input->post("other_addl_degree_type"))) && (($_FILES['other_addl_degree']['name'] == ""))) {
                $this->form_validation->set_rules('other_addl_degree', 'Other Additional Degrees', 'required');
            }
        }

        // pre($this->input->post());
        // return;

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        $photoOfTheCandidate = cifileupload("photo_of_the_candidate");
        $photo_of_the_candidate = $photoOfTheCandidate["upload_status"]?$photoOfTheCandidate["uploaded_path"]:null;

        $passCertificateFromUniColl = cifileupload("pass_certificate_from_uni_coll");
        $pass_certificate_from_uni_coll = $passCertificateFromUniColl["upload_status"]?$passCertificateFromUniColl["uploaded_path"]:null;

        $pgDegreeDipMarksheet = cifileupload("pg_degree_dip_marksheet");
        $pg_degree_dip_marksheet = $pgDegreeDipMarksheet["upload_status"]?$pgDegreeDipMarksheet["uploaded_path"]:null;

        $prcAcmr = cifileupload("prc_acmr");
        $prc_acmr = $prcAcmr["upload_status"]?$prcAcmr["uploaded_path"]:null;

        $otherAddlAegree = cifileupload("other_addl_degree");
        $other_addl_degree = $otherAddlAegree["upload_status"]?$otherAddlAegree["uploaded_path"]:null;
        
        $uploadedFiles = array(
            "photo_of_the_candidate_old" => strlen($photo_of_the_candidate)?$photo_of_the_candidate:$this->input->post("photo_of_the_candidate_old"),
            "pass_certificate_from_uni_coll_old" => strlen($pass_certificate_from_uni_coll)?$pass_certificate_from_uni_coll:$this->input->post("pass_certificate_from_uni_coll_old"),
            "pg_degree_dip_marksheet_old" => strlen($pg_degree_dip_marksheet)?$pg_degree_dip_marksheet:$this->input->post("pg_degree_dip_marksheet_old"),
            "prc_acmr_old" => strlen($prc_acmr)?$prc_acmr:$this->input->post("prc_acmr_old"),
            "other_addl_degree_old" => strlen($other_addl_degree)?$other_addl_degree:$this->input->post("other_addl_degree_old")
        );
        // pre($uploadedFiles);

        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->queryform($objId);
        } else {            

            $dbRow = $this->registration_model->get_by_doc_id($objId);
            if(count((array)$dbRow)) {

                $data = array(
                    'form_data.photo_of_the_candidate_type' => $this->input->post("photo_of_the_candidate_type"),

                    'form_data.pass_certificate_from_uni_coll_type' => $this->input->post("pass_certificate_from_uni_coll_type"),
                    'form_data.pg_degree_dip_marksheet_type' => $this->input->post("pg_degree_dip_marksheet_type"),
                    'form_data.prc_acmr_type' => $this->input->post("prc_acmr_type"),

                    'form_data.other_addl_degree_type' => $this->input->post("other_addl_degree_type"),


                    'form_data.photo_of_the_candidate' => strlen($photo_of_the_candidate)?$photo_of_the_candidate:$this->input->post("photo_of_the_candidate_old"),
                    'form_data.pass_certificate_from_uni_coll' => strlen($pass_certificate_from_uni_coll)?$pass_certificate_from_uni_coll:$this->input->post("pass_certificate_from_uni_coll_old"),
                    'form_data.pg_degree_dip_marksheet' => strlen($pg_degree_dip_marksheet)?$pg_degree_dip_marksheet:$this->input->post("pg_degree_dip_marksheet_old"),
                    'form_data.prc_acmr' => strlen($prc_acmr)?$prc_acmr:$this->input->post("prc_acmr_old")
                );
    
                if (!empty($this->input->post("other_addl_degree_type"))) {
                    $data["form_data.other_addl_degree_type"] = $this->input->post("other_addl_degree_type");
                    $data["form_data.other_addl_degree"] = strlen($other_addl_degree)?$other_addl_degree:$this->input->post("other_addl_degree_old");
                }

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
                redirect('spservices/acmr_reg_of_addl_degrees/registration/preview/'.$objId);
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
        $str = "RTPS-ACMRREGAD/" . $date."/" .$number;
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

                // pre($current_users);

                $data_to_update=array(
                    'service_data.appl_status'=>'submitted',
                    'service_data.submission_date'=>new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'current_users' => $current_users,
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
}//End of Castecertificate
