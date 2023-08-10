<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps {
    
    private $serviceName = "PROVISIONAL REGISTRATION CERTIFICATE OF MBBS DOCTOR";
    Private $serviceId = "ACMRPRCMD";

    public function __construct() {
        parent::__construct();
        $this->load->model('acmr_provisional_certificate/registration_model');
        $this->load->model('acmr_provisional_certificate/countries_model');
        $this->load->model('acmr_provisional_certificate/States_model');
        $this->load->model('acmr_provisional_certificate/Districts_model');
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
     public function payment_ack($obj_id=null) {
        //$user = $this->registration_model->get_by_id($this->session->userdata("userId")->{'$id'});
        $applicationRow = $this->registration_model->get_by_doc_id($obj_id);
        $filter = array(
            "_id" => $obj_id ? new ObjectId($obj_id) : NULL
        );

        $dbrow = $this->registration_model->get_row($filter);   
        $data['dbrow'] = $dbrow;         
       
        $this->load->view('includes/frontend/header');
        $this->load->view('acmr_provisional_certificate/payment_ack', $data);
        $this->load->view('includes/frontend/footer');
    }
    public function index($obj_id=null) {
       // pre("here");

         $countries = $this->countries_model->get_rows(array());
       // pre($countries);

        $data=array("pageTitle" => "PROVISIONAL REGISTRATION CERTIFICATE OF MBBS DOCTOR");
        $filter = array(
            "_id" =>new ObjectId($obj_id), 
            "service_data.appl_status" => "DRAFT"
        );
        $data["dbrow"] = $this->registration_model->get_row($filter);
        $data['usser_type']=$this->slug;
       
        $this->load->view('includes/frontend/header');
        $this->load->view('acmr_provisional_certificate/acmr_provisional_certificate',$data);
        $this->load->view('includes/frontend/footer');
    }//End of index()

    public function submit(){

        // pre($this->input->post("statee"));
        // return;
        $this->load->model('acmr_provisional_certificate/states_model');
        $statee = $this->input->post("statee");
        $state_name = '';

        if (!empty($statee)) {
            $states = $this->states_model->get_row(array("slc" => intval($statee)));

            if (!empty($states)) {
                $state_name = $states->state_name_english;
            }
        }


    //    pre($this->input->post());
       // pre($this->input->post("others_type"));
        $objId = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");
        
      $this->form_validation->set_rules('applicant_name', 'Applicant name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('father_name', 'Father name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('mother_name', 'Mother name', 'trim|required|xss_clean|strip_tags|max_length[255]|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|integer|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|strip_tags|valid_email');
        $this->form_validation->set_rules('permanent_addr', 'Permanent Address', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('correspondence_addr', 'Correspondence Address', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|xss_clean|strip_tags|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]|max_length[10]');
        $this->form_validation->set_rules('aadhar_no', 'Aadhar No.', 'trim|xss_clean|strip_tags|integer|exact_length[12]');

        $this->form_validation->set_rules('primary_qualification', 'Primary Qualification', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('primary_qua_doc', 'Date of Completion', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('primary_qua_college_name', 'College Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('primary_qua_course_dur', 'Course Duration', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('primary_qua_doci', 'Date of Completion of Internship*', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('primary_qua_university_award_intership', 'University awarding the Internship*', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('university_roll_no', 'University Roll No.', 'trim|required|xss_clean|strip_tags|integer');

        $this->form_validation->set_rules('study_place', 'Study Place', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('address1', 'Address Line1', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('address2', 'Address Line2', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('country', 'Country', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('state_foreign', 'Foreign State', 'trim|xss_clean|strip_tags');

        $this->form_validation->set_rules('statee', 'State', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('district', 'District', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('pincode', 'Pin Code', 'trim|xss_clean|strip_tags|integer|exact_length[6]');

        $studyPlace = $this->input->post('study_place');

            if ($studyPlace == 1 || $studyPlace == 2) {
                $this->form_validation->set_rules('address1', 'Address Line1', 'trim|required|xss_clean|strip_tags');
                $this->form_validation->set_rules('statee', 'State', 'trim|required|xss_clean|strip_tags');
                $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags');
                $this->form_validation->set_rules('pincode', 'Pin Code', 'trim|required|xss_clean|strip_tags|integer|exact_length[6]');

                // Set other fields to empty values
                $_POST['country'] = '';
                $_POST['state_foreign'] = '';
            } elseif ($studyPlace == 3) {
                $this->form_validation->set_rules('address1', 'Address Line1', 'trim|required|xss_clean|strip_tags');
                $this->form_validation->set_rules('country', 'Country', 'trim|required|xss_clean|strip_tags');
                $this->form_validation->set_rules('state_foreign', 'Foreign State', 'trim|required|xss_clean|strip_tags');

                // Set other fields to empty values
                $_POST['statee'] = '';
                $_POST['district'] = '';
                $_POST['pincode'] = '';
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

            $service_data = [
                "department_id" => "1421",
                "department_name" => "Assam Council of Medical Registration",
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => "Assam Council of Medical Registration (Guwahati)", // office name
                "submission_date" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
                "service_timeline" => "07 Days",
                "appl_status" => "DRAFT",
                "district" => "Guwahati",
            ];
           
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
                'pan_no' => trim($this->input->post("pan_no")),
                'aadhar_no' => $this->input->post("aadhar_no"),
                
                'primary_qualification' => trim($this->input->post("primary_qualification")),
                'primary_qua_doc' => $this->input->post("primary_qua_doc"),
                'primary_qua_college_name' => $this->input->post("primary_qua_college_name"),
                'primary_qua_college_addr' => $this->input->post("primary_qua_college_addr"),
                'primary_qua_course_dur' => $this->input->post("primary_qua_course_dur"),
                'primary_qua_doci' => $this->input->post("primary_qua_doci"),
                'primary_qua_university_award_intership' => $this->input->post("primary_qua_university_award_intership"),
                'university_roll_no' => $this->input->post("university_roll_no"),
                
                
                'study_place' => $this->input->post("study_place"),
                'address1' => $this->input->post("address1"),
                'address2' => $this->input->post("address2"),
                // 'statee' => $this->input->post("statee"),
                'statee' => $state_name,
                'country' => $this->input->post("country"),
                'district' => $this->input->post("district"),
                'pincode' => $this->input->post("pincode"),
                'state_foreign' => $this->input->post("state_foreign"),

                'user_id' => $sessionUser['userId']->{'$id'},    
                'user_type' => $this->slug,

                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];
            if(strlen($objId)) {
                $form_data["admit_card_type"] = $this->input->post("admit_card_type");
                $form_data["admit_card"] = $this->input->post("admit_card");
                $form_data["hs_marksheet_type"] = $this->input->post("hs_marksheet_type");
                $form_data["hs_marksheet"] = $this->input->post("hs_marksheet");
                $form_data["reg_certificate_type"] = $this->input->post("reg_certificate_type");
                $form_data["reg_certificate"] = $this->input->post("reg_certificate");
                $form_data["mbbs_marksheet_type"] = $this->input->post("mbbs_marksheet_type");
                $form_data["mbbs_marksheet"] = $this->input->post("mbbs_marksheet");
                $form_data["pass_certificate_type"] = $this->input->post("pass_certificate_type");
                $form_data["pass_certificate"] = $this->input->post("pass_certificate");
                $form_data["college_noc_type"] = $this->input->post("college_noc_type");
                $form_data["college_noc"] = $this->input->post("college_noc");
                $form_data["director_noc_type"] = $this->input->post("director_noc_type");
                $form_data["director_noc"] = $this->input->post("director_noc");
                $form_data["university_noc_type"] = $this->input->post("university_noc_type");
                $form_data["university_noc"] = $this->input->post("university_noc");
                $form_data["institute_noc_type"] = $this->input->post("institute_noc_type");
                $form_data["institute_noc"] = $this->input->post("institute_noc");
                $form_data["eligibility_certificate_type"] = $this->input->post("eligibility_certificate_type");
                $form_data["eligibility_certificate"] = $this->input->post("eligibility_certificate");
                $form_data["screening_result_type"] = $this->input->post("screening_result_type");
                $form_data["screening_result"] = $this->input->post("screening_result");
                $form_data["passport_visa_type"] = $this->input->post("passport_visa_type");
                $form_data["passport_visa"] = $this->input->post("passport_visa");
                $form_data["all_docs_type"] = $this->input->post("all_docs_type");
                $form_data["all_docs"] = $this->input->post("all_docs");
    
              }
                // $form_data["soft_copy_type"] = $this->input->post("soft_copy_type");
                // $form_data["soft_copy"] = $this->input->post("soft_copy");     

            $inputs = [
                'service_data'=>$service_data,
                'form_data' => $form_data
            ];
                
            if(strlen($objId)) {

                //pre($inputs);
                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                $this->session->set_flashdata('success','Your application has been successfully submitted');
                redirect('spservices/acmr_provisional_certificate/registration/fileuploads/'.$objId);
            } else {
                //pre($inputs);
                $insert=$this->registration_model->insert($inputs);
                if($insert){
                    $objectId=$insert['_id']->{'$id'};
                    $this->session->set_flashdata('success','Your application has been successfully submitted');
                    redirect('spservices/acmr_provisional_certificate/registration/fileuploads/'.$objectId);
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
            $this->load->view('acmr_provisional_certificate/acmr_provisional_certificateuploads_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/acmr_provisional_certificate/registration');
        }//End of if else
    }//End of fileuploads()
  public function querypaymentsubmit($obj=null){
        if($obj){
            $dbRow = $this->registration_model->get_by_doc_id($obj);
            if(count((array)$dbRow)) {

                if ($dbRow->service_data->appl_status !== "FRS") {
                    $this->my_transactions();
                }                  
                

            $uniqid=uniqid();
            $DEPARTMENT_ID = $uniqid . time();
            $data = array();
            $data['rtps_trans_id']=$dbRow->service_data->appl_ref_no;
            $data['department_data'] = array(
                    "DEPT_CODE" => $dbRow->form_data->payment_params->DEPT_CODE,
                    "OFFICE_CODE" => $dbRow->form_data->payment_params->OFFICE_CODE,
                    "REC_FIN_YEAR" => getFinYear(), //dynamic
                    "HOA1" => "",
                    "FROM_DATE" => firstDateFinYear(),
                    "TO_DATE" => $this->config->item("to_date"),
                    "PERIOD" => "O", // O for one-time payment
                    "CHALLAN_AMOUNT" => "0",
                    "DEPARTMENT_ID" => $DEPARTMENT_ID,
                    "MOBILE_NO" => $dbRow->form_data->mobile,
                    "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/acmr_provisional_certificate/payment_query_response/response'),
                    "PARTY_NAME" => $dbRow->form_data->applicant_name ?? 'RTPS TEAM',
                    "PIN_NO" => $dbRow->form_data->pin_code ?? '781005',
                    "ADDRESS1" => $dbRow->form_data->village_town ?? 'NIC',
                    "ADDRESS2" => $dbRow->form_data->post_office ?? 'TEAM',
                    "ADDRESS3" => $dbRow->form_data->district ?? 'Kamrup',
                    "MULTITRANSFER" => "Y",
                    "NON_TREASURY_PAYMENT_TYPE" => "01",
                    "TOTAL_NON_TREASURY_AMOUNT" => $dbRow->processing_history->amount_to_pay ?? '2000',
                    "ACCOUNT1" => "DME88095", //$user->account1,$account = "PFC23362";
                    // "ACCOUNT1" => $dbRowGras->account_code,
                    "AC1_AMOUNT" => $dbRow->processing_history->amount_to_pay ?? '2000',
                   // "ACCOUNT2" => "PFC23362",
                );
                //pre($data);
                $res=$this->update_query_payment_amount($data);
                
                if($res){
                    $this->load->view('iservices/basundhara/payment',$data);
                }else{
                    $this->my_transactions();
                }
        
                }//End of if
                } else {
                    $this->session->set_flashdata('errmessage','Application Ref No : '.$dbRow->service_data->appl_ref_no.' and Payment was successfully done. Please complete payment process');
                    $this->my_transactions();
                } 
        }
        public function update_query_payment_amount($data){
        $payment_params=$data['department_data'];
        $rtps_trans_id=$data['rtps_trans_id'];
        // $data_to_update=array('form_data.query_department_id'=>$payment_params['DEPARTMENT_ID'],'form_data.query_payment_params'=>$payment_params);

        $form_data['query_department_id'] = $payment_params['DEPARTMENT_ID'];
        $form_data['query_payment_params'] = $payment_params;

        $result=$this->mongo_db->where(array('service_data.appl_ref_no' => $rtps_trans_id))->set(['form_data.query_payment_params' => $payment_params, 'form_data.query_department_id'=>$payment_params['DEPARTMENT_ID']])->update('sp_applications');

        // $result=$this->registration_model->update_payment_status($rtps_trans_id,$data_to_update);

           if($result->getMatchedCount()){
             $this->load->model('iservices/admin/pfc_payment_history_model');
            $data_to_update = array('rtps_trans_id'=>$rtps_trans_id, 
            'form_data' => $form_data, 
            'createdDtm' => new MongoDB\BSON\UTCDateTime(microtime(true) * 1000));
             $this->pfc_payment_history_model->insert($data_to_update);
            return true;
           }else {
            return false;
           }

        $this->registration_model->update_where(['_id' => new ObjectId($obj)], $data);
        if($dbRow->query_payment_status == "Y")
        {
        $processing_history = $dbRow->processing_history??array();
        $processing_history[] = array(
                                "processed_by" => "Payment Query submitted by ".$dbRow->form_data->applicant_name,
                                "action_taken" => "Payment Query submitted",
                                "remarks" => "Payment Query submitted by ".$dbRow->form_data->applicant_name,
                                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            );
                              $data = array(
                                "service_data.appl_status" => "QA",
                                'processing_history' => $processing_history,
                                'status' => "QUERY_PAYMENT_ANSWERED"
                            );
                            $this->registration_model->update_where(['_id' => new ObjectId($obj)], $data);
    
                            // $this->session->set_flashdata('success','Your application has been successfully updated');
                            redirect('spservices/acmr_provisional_certificate/registration/payment_acknowledgement/'.$objId);
                        } else {
                            $this->session->set_flashdata('errmessage','Unable to update data!!! Please try again.');
                            $this->my_transactions();
                        }
    }
    public function submitfiles() {   
        $objId = $this->input->post("obj_id");
        $dbrow = $this->registration_model->get_by_doc_id($objId);
        if (empty($objId)) {
            $this->my_transactions();
        }    
  
       if (($dbrow->form_data->study_place == "1") || ($dbrow->form_data->study_place == "2") || ($dbrow->form_data->study_place == "3")) {
          
    $this->form_validation->set_rules('admit_card_type', 'Admit Card Type of the Candidate', 'required');
    if (empty($this->input->post("admit_card_old"))) {
        if (($_FILES['admit_card']['name'] == "") && (empty($this->input->post("admit_card_temp")))) {
            // $this->form_validation->set_rules('admit_birth_type', 'Admit or Birth Certificate Type', 'required');
            $this->form_validation->set_rules('admit_card', 'Admit Card of the Candidate', 'required');
        }
    }

    $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet Type', 'required');
    if (empty($this->input->post("hs_marksheet_old"))) {
        if (($_FILES['hs_marksheet']['name'] == "") && (empty($this->input->post("hs_marksheet_temp")))) {
            // $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet Type', 'required');
            $this->form_validation->set_rules('hs_marksheet', 'HS Final Marksheet', 'required');
        }
    }

    $this->form_validation->set_rules('reg_certificate_type', 'Registration Certificate Type', 'required');
    if (empty($this->input->post("reg_certificate_old"))) {
        if (($_FILES['reg_certificate']['name'] == "") && (empty($this->input->post("reg_certificate_temp")))) {
            // $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet Type', 'required');
            $this->form_validation->set_rules('reg_certificate', 'Registration Certificate', 'required');
        }
    }

    $this->form_validation->set_rules('mbbs_marksheet_type', 'MBBS Marksheet Type', 'required');
    if (empty($this->input->post("mbbs_marksheet_old"))) {
        if (($_FILES['mbbs_marksheet']['name'] == "") && (empty($this->input->post("mbbs_marksheet_temp")))) {
            // $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet Type', 'required');
            $this->form_validation->set_rules('mbbs_marksheet', 'MBBS Marksheet', 'required');
        }
    }

    $this->form_validation->set_rules('pass_certificate_type', 'Pass Certificate Type', 'required');
    if (empty($this->input->post("pass_certificate_old"))) {
        if (($_FILES['pass_certificate']['name'] == "") && (empty($this->input->post("pass_certificate_temp")))) {
            // $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet Type', 'required');
            $this->form_validation->set_rules('pass_certificate', 'Pass Certificate', 'required');
        }
    }

    $this->form_validation->set_rules('annexure_type', 'Annexure II Type', '');
    if (empty($this->input->post("annexure_old"))) {
        if (($_FILES['annexure']['name'] == "") && (empty($this->input->post("annexure_temp")))) {
            // $this->form_validation->set_rules('annexure_type', 'Annexure II Type', 'required');
            $this->form_validation->set_rules('annexure', 'Annexure II', '');
        }
    }
     $this->form_validation->set_rules('ten_pass_certificate_type', 'Class 10 Pass Certificate Type', 'required');
    if (empty($this->input->post("ten_pass_certificate_old"))) {
        if (($_FILES['ten_pass_certificate']['name'] == "") && (empty($this->input->post("ten_pass_certificate_temp")))) {
            // $this->form_validation->set_rules('annexure_type', 'Annexure II Type', 'required');
            $this->form_validation->set_rules('ten_pass_certificate', 'Class 10 Pass Certificate', 'required');
        }
    }
    $this->form_validation->set_rules('photograph_type', 'Photograph Type', 'required');
    if (empty($this->input->post("photograph_old"))) {
        if (($_FILES['photograph']['name'] == "") && (empty($this->input->post("photograph_temp")))) {
            // $this->form_validation->set_rules('annexure_type', 'Annexure II Type', 'required');
            $this->form_validation->set_rules('photograph', 'Photograph', 'required');
        }
    }
    $this->form_validation->set_rules('signature_type', 'Signature Type', 'required');
    if (empty($this->input->post("signature_old"))) {
        if (($_FILES['signature']['name'] == "") && (empty($this->input->post("signature_temp")))) {
            // $this->form_validation->set_rules('annexure_type', 'Annexure II Type', 'required');
            $this->form_validation->set_rules('signature', 'Signature', 'required');
        }
    }
   
//for 2 and 3
if (($dbrow->form_data->study_place == 2) || ($dbrow->form_data->study_place == 3)) {
    $this->form_validation->set_rules('college_noc_type', 'College NOC Type', 'required');
    if (empty($this->input->post("college_noc_old"))) {
        if (($_FILES['college_noc']['name'] == "") && (empty($this->input->post("college_noc_temp")))) {
            $this->form_validation->set_rules('college_noc', 'College NOC', 'required');
        }
    }

    $this->form_validation->set_rules('director_noc_type', 'NOC from Director Type', 'required');
    if (empty($this->input->post("director_noc_old"))) {
        if (($_FILES['director_noc']['name'] == "") && (empty($this->input->post("director_noc_temp")))) {
            $this->form_validation->set_rules('director_noc', 'Director NOC', 'required');
        }
    }

    $this->form_validation->set_rules('university_noc_type', 'NOC from University Type', '');
    if (empty($this->input->post("university_noc_old"))) {
        if (($_FILES['university_noc']['name'] == "") && (empty($this->input->post("university_noc_temp")))) {
            $this->form_validation->set_rules('university_noc', 'University NOC', '');
        }
    }

    $this->form_validation->set_rules('institute_noc_type', 'NOC from Institute Type', 'required');
    if (empty($this->input->post("institute_noc_old"))) {
        if (($_FILES['institute_noc']['name'] == "") && (empty($this->input->post("institute_noc_temp")))) {
            $this->form_validation->set_rules('institute_noc', 'Institute NOC', 'required');
        }
    }
}
//for 3
 if ($dbrow->form_data->study_place == 3) {
        $this->form_validation->set_rules('eligibility_certificate_type', 'Eligibility Certificate Type', '');
        if (empty($this->input->post("eligibility_certificate_old"))) {
            if (($_FILES['eligibility_certificate']['name'] == "") && (empty($this->input->post("eligibility_certificate_temp")))) {
                $this->form_validation->set_rules('eligibility_certificate', 'Eligibility Certificate', '');
            }
        }

        $this->form_validation->set_rules('screening_result_type', 'Screening Test Result Type', 'required');
        if (empty($this->input->post("screening_result_old"))) {
            if (($_FILES['screening_result']['name'] == "") && (empty($this->input->post("screening_result_temp")))) {
                $this->form_validation->set_rules('screening_result', 'Screening Test Result', 'required');
            }
        }

        $this->form_validation->set_rules('passport_visa_type', 'Passport and Visa Type', 'required');
        if (empty($this->input->post("passport_visa_type_old"))) {
            if (($_FILES['passport_visa']['name'] == "") && (empty($this->input->post("passport_visa_temp")))) {
                $this->form_validation->set_rules('passport_visa', 'Passport and Visa', 'required');
            }
        }

        $this->form_validation->set_rules('all_docs_type', 'All Documents Type', 'required');
        if (empty($this->input->post("all_docs_type_old"))) {
            if (($_FILES['all_docs']['name'] == "") && (empty($this->input->post("all_docs_temp")))) {
                $this->form_validation->set_rules('all_docs', 'All Documents', 'required');
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
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->fileuploads($objId);
            return;
        } 
        
        if (strlen($this->input->post("admit_card_temp")) > 0) {
        $admitcard = movedigilockerfile($this->input->post('admit_card_temp'));
        $admit_card = $admitcard["upload_status"]?$admitcard["uploaded_path"]:null;
        } else {
            $admitcard = cifileupload("admit_card");
            $admit_card = $admitcard["upload_status"]?$admitcard["uploaded_path"]:null;
        }
        
        if (strlen($this->input->post("hs_marksheet_temp")) > 0) {
        $hsmarksheet = movedigilockerfile($this->input->post('hs_marksheet_temp'));
        $hs_marksheet = $hsmarksheet["upload_status"]?$hsmarksheet["uploaded_path"]:null;
        } else {
             $hsmarksheet = cifileupload("hs_marksheet");
            $hs_marksheet = $hsmarksheet["upload_status"]?$hsmarksheet["uploaded_path"]:null;
        }

        if (strlen($this->input->post("reg_certificate_temp")) > 0) {
        $regcertificate = movedigilockerfile($this->input->post('reg_certificate_temp'));
        $reg_certificate = $regcertificate["upload_status"]?$regcertificate["uploaded_path"]:null;
        } else {
             $regcertificate = cifileupload("reg_certificate");
             $reg_certificate = $regcertificate["upload_status"]?$regcertificate["uploaded_path"]:null;
        }

        if (strlen($this->input->post("mbbs_marksheet_temp")) > 0) {
        $mbbsmarksheet = movedigilockerfile($this->input->post('mbbs_marksheet_temp'));
        $mbbs_marksheet = $mbbsmarksheet["upload_status"]?$mbbsmarksheet["uploaded_path"]:null;
        } else {
             $mbbsmarksheet = cifileupload("mbbs_marksheet");
             $mbbs_marksheet = $mbbsmarksheet["upload_status"]?$mbbsmarksheet["uploaded_path"]:null;
        }
        if (strlen($this->input->post("college_noc_temp")) > 0) {
        $collegenoc = movedigilockerfile($this->input->post('college_noc_temp'));
        $college_noc = $collegenoc["upload_status"]?$collegenoc["uploaded_path"]:null;
        } else {
             $collegenoc = cifileupload("college_noc");
             $college_noc = $collegenoc["upload_status"]?$collegenoc["uploaded_path"]:null;
        }

        if (strlen($this->input->post("director_noc_temp")) > 0) {
        $directornoc = movedigilockerfile($this->input->post('director_noc_temp'));
        $director_noc = $directornoc["upload_status"]?$directornoc["uploaded_path"]:null;
        } else {
             $directornoc = cifileupload("director_noc");
             $director_noc = $directornoc["upload_status"]?$directornoc["uploaded_path"]:null;
        }

        if (strlen($this->input->post("university_noc_temp")) > 0) {
        $universitynoc = movedigilockerfile($this->input->post('university_noc_temp'));
        $university_noc = $universitynoc["upload_status"]?$universitynoc["uploaded_path"]:null;
        } else {
             $universitynoc = cifileupload("university_noc");
             $university_noc = $universitynoc["upload_status"]?$universitynoc["uploaded_path"]:null;
        }
        
        if (strlen($this->input->post("institute_noc_temp")) > 0) {
        $institutenoc = movedigilockerfile($this->input->post('institute_noc_temp'));
        $institute_noc = $institutenoc["upload_status"]?$institutenoc["uploaded_path"]:null;
        } else {
             $institutenoc = cifileupload("university_noc");
             $institute_noc = $institutenoc["upload_status"]?$institutenoc["uploaded_path"]:null;
        }

        if (strlen($this->input->post("eligibility_certificate_temp")) > 0) {
        $eligibilitycertificate = movedigilockerfile($this->input->post('eligibility_certificate_temp'));
        $eligibility_certificate = $eligibilitycertificate["upload_status"]?$eligibilitycertificate["uploaded_path"]:null;
        } else {
             $eligibilitycertificate = cifileupload("eligibility_certificate");
             $eligibility_certificate = $eligibilitycertificate["upload_status"]?$eligibilitycertificate["uploaded_path"]:null;
        }
        if (strlen($this->input->post("pass_certificate_temp")) > 0) {
        $passcertificate = movedigilockerfile($this->input->post('pass_certificate_temp'));
        $pass_certificate = $passcertificate["upload_status"]?$passcertificate["uploaded_path"]:null;
        } else {
             $passcertificate = cifileupload("pass_certificate");
             $pass_certificate = $passcertificate["upload_status"]?$passcertificate["uploaded_path"]:null;
        }

        if (strlen($this->input->post("screening_result_temp")) > 0) {
        $screeningresult = movedigilockerfile($this->input->post('screening_result_temp'));
        $screening_result = $screeningresult["upload_status"]?$screeningresult["uploaded_path"]:null;
        } else {
             $screeningresult = cifileupload("screening_result");
             $screening_result = $screeningresult["upload_status"]?$screeningresult["uploaded_path"]:null;
        }
        if (strlen($this->input->post("passport_visa_temp")) > 0) {
        $passportvisa = movedigilockerfile($this->input->post('passport_visa_temp'));
        $passport_visa = $passportvisa["upload_status"]?$passportvisa["uploaded_path"]:null;
        } else {
             $passportvisa = cifileupload("passport_visa");
             $passport_visa = $passportvisa["upload_status"]?$passportvisa["uploaded_path"]:null;
        }

        if (strlen($this->input->post("all_docs_temp")) > 0) {
        $alldocs = movedigilockerfile($this->input->post('all_docs_temp'));
        $all_docs = $alldocs["upload_status"]?$alldocs["uploaded_path"]:null;
         } else {
             $alldocs = cifileupload("all_docs");
             $all_docs = $alldocs["upload_status"]?$alldocs["uploaded_path"]:null;
        }
        if (strlen($this->input->post("annexure_temp")) > 0) {
        $annexure = movedigilockerfile($this->input->post('annexure_temp'));
        $annexure = $annexure["upload_status"]?$annexure["uploaded_path"]:null;
        } else {
             $annexure = cifileupload("annexure");
             $annexure = $annexure["upload_status"]?$annexure["uploaded_path"]:null;
        }
        if (strlen($this->input->post("ten_pass_certificate_temp")) > 0) {
        $tenpasscertificate = movedigilockerfile($this->input->post('ten_pass_certificate_temp'));
        $ten_pass_certificate = $tenpasscertificate["upload_status"]?$tenpasscertificate["uploaded_path"]:null;
        } else {
             $tenpasscertificate = cifileupload("ten_pass_certificate");
             $ten_pass_certificate = $tenpasscertificate["upload_status"]?$tenpasscertificate["uploaded_path"]:null;
        }

        //photograph
         if (strlen($this->input->post("photograph_temp")) > 0) {
        $photograph = movedigilockerfile($this->input->post('photograph_temp'));
        $photograph = $photograph["upload_status"]?$photograph["uploaded_path"]:null;
        } else {
             $photograph = cifileupload("photograph");
             $photograph = $photograph["upload_status"]?$photograph["uploaded_path"]:null;
        }

        //signature
         if (strlen($this->input->post("signature_temp")) > 0) {
        $signature = movedigilockerfile($this->input->post('signature_temp'));
        $signature = $signature["upload_status"]?$signature["uploaded_path"]:null;
        } else {
             $signature = cifileupload("signature");
             $signature = $signature["upload_status"]?$signature["uploaded_path"]:null;
        }

        // $softCopy = cifileupload("soft_copy");
        // $soft_copy = $softCopy["upload_status"]?$softCopy["uploaded_path"]:null;

       
        $uploadedFiles = array(
            "admit_card_old" => strlen($admit_card)?$admit_card:$this->input->post("admit_card_old"),
            "hs_marksheet_old" => strlen($hs_marksheet)?$hs_marksheet:$this->input->post("hs_marksheet_old"),
            "reg_certificate_old" => strlen($reg_certificate)?$reg_certificate:$this->input->post("reg_certificate_old"),
            "mbbs_marksheet_old" => strlen($mbbs_marksheet)?$mbbs_marksheet:$this->input->post("mbbs_marksheet_old"),
            "pass_certificate_old" => strlen($pass_certificate)?$pass_certificate:$this->input->post("pass_certificate_old"),
             "college_noc_old" => strlen($college_noc)?$college_noc:$this->input->post("college_noc_old"),
            "director_noc_old" => strlen($director_noc)?$director_noc:$this->input->post("director_noc_old"),
            "university_noc_old" => strlen($university_noc)?$university_noc:$this->input->post("university_noc_old"),
            "institute_noc_old" => strlen($institute_noc)?$institute_noc:$this->input->post("institute_noc_old"),"eligibility_certificate_old" => strlen($eligibility_certificate)?$eligibility_certificate:$this->input->post("eligibility_certificate_old"),
             "screening_result_old" => strlen($screening_result)?$screening_result:$this->input->post("screening_result_old"),
            "passport_visa_old" => strlen($passport_visa)?$passport_visa:$this->input->post("passport_visa"),
            "all_docs_old" => strlen($all_docs)?$all_docs:$this->input->post("all_docs"),
            "annexure_old" => strlen($annexure)?$annexure:$this->input->post("annexure"),
            "ten_pass_certificate_old" => strlen($ten_pass_certificate)?$ten_pass_certificate:$this->input->post("ten_pass_certificate_old"),
            "photograph_old" => strlen($photograph)?$photograph:$this->input->post("photograph"),
            "signature_old" => strlen($signature)?$signature:$this->input->post("signature")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
            $data = array(
                'form_data.admit_card_type' => $this->input->post("admit_card_type"),
                'form_data.hs_marksheet_type' => $this->input->post("hs_marksheet_type"),
                'form_data.reg_certificate_type' => $this->input->post("reg_certificate_type"),
                'form_data.mbbs_marksheet_type' => $this->input->post("mbbs_marksheet_type"),
                'form_data.pass_certificate_type' => $this->input->post("pass_certificate_type"),
                'form_data.ten_pass_certificate_type' => $this->input->post("ten_pass_certificate_type"),
                'form_data.photograph_type' => $this->input->post("photograph_type"),
                'form_data.signature_type' => $this->input->post("signature_type"),
                'form_data.annexure_type' => $this->input->post("annexure_type"),

                'form_data.admit_card' => strlen($admit_card)?$admit_card:$this->input->post("admit_card_old"),
                'form_data.hs_marksheet' => strlen($hs_marksheet)?$hs_marksheet:$this->input->post("hs_marksheet_old"),
                'form_data.reg_certificate' => strlen($reg_certificate)?$reg_certificate:$this->input->post("reg_certificate_old"),
                'form_data.mbbs_marksheet' => strlen($mbbs_marksheet)?$mbbs_marksheet:$this->input->post("mbbs_marksheet_old"),
                'form_data.pass_certificate' => strlen($pass_certificate)?$pass_certificate:$this->input->post("pass_certificate_old"),
                'form_data.ten_pass_certificate' => strlen($ten_pass_certificate)?$ten_pass_certificate:$this->input->post("ten_pass_certificate_old"),
                'form_data.photograph' => strlen($photograph)?$photograph:$this->input->post("photograph_old"),
                'form_data.signature' => strlen($signature)?$signature:$this->input->post("signature_old"),
                'form_data.annexure' => strlen($annexure)?$annexure:$this->input->post("annexure_old"),
               
            );
           // pre($data);

            $data["form_data.college_noc_type"] = "";
            $data["form_data.college_noc"] = "";
            $data["form_data.director_noc_type"] = "";
            $data["form_data.director_noc"] = "";
            $data["form_data.university_noc_type"] = "";
            $data["form_data.university_noc"] = "";
            $data["form_data.institute_noc_type"] = "";
            $data["form_data.institute_noc"] = "";
            $data["form_data.eligibility_certificate_type"] = "";
            $data["form_data.eligibility_certificate"] = "";
            $data["form_data.screening_result_type"] = "";
            $data["form_data.screening_result"] = "";
            $data["form_data.passport_visa_type"] = "";
            $data["form_data.passport_visa"] = "";
            $data["form_data.all_docs_type"] = "";
            $data["form_data.all_docs"] = "";

            if(($dbrow->form_data->study_place == 2) || ($dbrow->form_data->study_place == 3)){
                $data["form_data.college_noc_type"] = $this->input->post("college_noc_type");
                $data["form_data.college_noc"] = strlen($college_noc)?$college_noc:$this->input->post("college_noc_old");
                $data["form_data.director_noc_type"] = $this->input->post("director_noc_type");
                $data["form_data.director_noc"] = strlen($director_noc)?$director_noc:$this->input->post("director_noc_old");
                $data["form_data.university_noc_type"] = $this->input->post("university_noc_type");
                $data["form_data.university_noc"] = strlen($university_noc)?$university_noc:$this->input->post("university_noc_old");
                $data["form_data.institute_noc_type"] = $this->input->post("institute_noc_type");
                $data["form_data.institute_noc"] = strlen($institute_noc)?$institute_noc:$this->input->post("institute_noc_old");
            }
             if($dbrow->form_data->study_place == 3){
                $data["form_data.eligibility_certificate_type"] = $this->input->post("eligibility_certificate_type");
                $data["form_data.eligibility_certificate"] = strlen($eligibility_certificate)?$eligibility_certificate:$this->input->post("eligibility_certificate_old");
                $data["form_data.screening_result_type"] = $this->input->post("screening_result_type");
                $data["form_data.screening_result"] = strlen($screening_result)?$screening_result:$this->input->post("screening_result_old");
                $data["form_data.passport_visa_type"] = $this->input->post("passport_visa_type");
                $data["form_data.passport_visa"] = strlen($passport_visa)?$passport_visa:$this->input->post("passport_visa_old");
                $data["form_data.all_docs_type"] = $this->input->post("all_docs_type");
                $data["form_data.all_docs"] = strlen($all_docs)?$all_docs:$this->input->post("all_docs_old");
             }
            
            $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success','Your application has been successfully submitted');
            redirect('spservices/acmr_provisional_certificate/registration/preview/'.$objId);
        
    }//End of submitfiles()
    }
    public function preview($objId=null) {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('acmr_provisional_certificate/acmr_provisional_certificatepreview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/acmr_provisional_certificate/registration');
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
            $this->load->view('acmr_provisional_certificate/acmr_provisional_certificateapplication_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/acmr_provisional_certificate/registration');
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
            $this->load->view('acmr_provisional_certificate/acmr_provisional_certificatetrack_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/acmr_provisional_certificate/registration');
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
                $this->load->view('acmr_provisional_certificate/acmr_provisional_certificatequery_view',$data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found in query mode against object id : '.$objId);
                redirect('spservices/acmr_provisional_certificate/registration');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/acmr_provisional_certificate/registration');
        }//End of if else
    }//End of query()

    public function querysubmit() { 

        $this->load->model('acmr_provisional_certificate/states_model');
         $objId = $this->input->post("obj_id");
        $dbrow = $this->registration_model->get_by_doc_id($objId);
        $statee = $this->input->post("statee");
        $state_name = '';

        if (!empty($statee)) {
            $states = $this->states_model->get_row(array("slc" => intval($statee)));

            if (!empty($states)) {
                $state_name = $states->state_name_english;
            }
        }
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");

        if (($dbrow->form_data->study_place == "1") || ($dbrow->form_data->study_place == "2") || ($dbrow->form_data->study_place == "3")) {
          
    $this->form_validation->set_rules('admit_card_type', 'Admit Card Type of the Candidate', 'required');
    if (empty($this->input->post("admit_card_old"))) {
        if (($_FILES['admit_card']['name'] == "") && (empty($this->input->post("admit_card_temp")))) {
            // $this->form_validation->set_rules('admit_birth_type', 'Admit or Birth Certificate Type', 'required');
            $this->form_validation->set_rules('admit_card', 'Admit Card of the Candidate', 'required');
        }
    }

    $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet Type', 'required');
    if (empty($this->input->post("hs_marksheet_old"))) {
        if (($_FILES['hs_marksheet']['name'] == "") && (empty($this->input->post("hs_marksheet_temp")))) {
            // $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet Type', 'required');
            $this->form_validation->set_rules('hs_marksheet', 'HS Final Marksheet', 'required');
        }
    }

    $this->form_validation->set_rules('reg_certificate_type', 'Registration Certificate Type', 'required');
    if (empty($this->input->post("reg_certificate_old"))) {
        if (($_FILES['reg_certificate']['name'] == "") && (empty($this->input->post("reg_certificate_temp")))) {
            // $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet Type', 'required');
            $this->form_validation->set_rules('reg_certificate', 'Registration Certificate', 'required');
        }
    }

    $this->form_validation->set_rules('mbbs_marksheet_type', 'MBBS Marksheet Type', 'required');
    if (empty($this->input->post("mbbs_marksheet_old"))) {
        if (($_FILES['mbbs_marksheet']['name'] == "") && (empty($this->input->post("mbbs_marksheet_temp")))) {
            // $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet Type', 'required');
            $this->form_validation->set_rules('mbbs_marksheet', 'MBBS Marksheet', 'required');
        }
    }

    $this->form_validation->set_rules('pass_certificate_type', 'Pass Certificate Type', 'required');
    if (empty($this->input->post("pass_certificate_old"))) {
        if (($_FILES['pass_certificate']['name'] == "") && (empty($this->input->post("pass_certificate_temp")))) {
            // $this->form_validation->set_rules('hs_marksheet_type', 'HS Final Marksheet Type', 'required');
            $this->form_validation->set_rules('pass_certificate', 'Pass Certificate', 'required');
        }
    }

    $this->form_validation->set_rules('annexure_type', 'Annexure II Type', '');
    if (empty($this->input->post("annexure_old"))) {
        if (($_FILES['annexure']['name'] == "") && (empty($this->input->post("annexure_temp")))) {
            // $this->form_validation->set_rules('annexure_type', 'Annexure II Type', 'required');
            $this->form_validation->set_rules('annexure', 'Annexure II', '');
        }
    }
     $this->form_validation->set_rules('ten_pass_certificate_type', 'Class 10 Pass Certificate Type', 'required');
    if (empty($this->input->post("ten_pass_certificate_old"))) {
        if (($_FILES['ten_pass_certificate']['name'] == "") && (empty($this->input->post("ten_pass_certificate_temp")))) {
            // $this->form_validation->set_rules('annexure_type', 'Annexure II Type', 'required');
            $this->form_validation->set_rules('ten_pass_certificate', 'Class 10 Pass Certificate', 'required');
        }
    }
    $this->form_validation->set_rules('photograph_type', 'Photograph Type', 'required');
    if (empty($this->input->post("photograph_old"))) {
        if (($_FILES['photograph']['name'] == "") && (empty($this->input->post("photograph_temp")))) {
            // $this->form_validation->set_rules('annexure_type', 'Annexure II Type', 'required');
            $this->form_validation->set_rules('photograph', 'Photograph', 'required');
        }
    }
    $this->form_validation->set_rules('signature_type', 'Signature Type', 'required');
    if (empty($this->input->post("signature_old"))) {
        if (($_FILES['signature']['name'] == "") && (empty($this->input->post("signature_temp")))) {
            // $this->form_validation->set_rules('annexure_type', 'Annexure II Type', 'required');
            $this->form_validation->set_rules('signature', 'Signature', 'required');
        }
    }
   
//for 2 and 3
if (($dbrow->form_data->study_place == 2) || ($dbrow->form_data->study_place == 3)) {
    $this->form_validation->set_rules('college_noc_type', 'College NOC Type', 'required');
    if (empty($this->input->post("college_noc_old"))) {
        if (($_FILES['college_noc']['name'] == "") && (empty($this->input->post("college_noc_temp")))) {
            $this->form_validation->set_rules('college_noc', 'College NOC', 'required');
        }
    }

    $this->form_validation->set_rules('director_noc_type', 'NOC from Director Type', 'required');
    if (empty($this->input->post("director_noc_old"))) {
        if (($_FILES['director_noc']['name'] == "") && (empty($this->input->post("director_noc_temp")))) {
            $this->form_validation->set_rules('director_noc', 'Director NOC', 'required');
        }
    }

    $this->form_validation->set_rules('university_noc_type', 'NOC from University Type', '');
    if (empty($this->input->post("university_noc_old"))) {
        if (($_FILES['university_noc']['name'] == "") && (empty($this->input->post("university_noc_temp")))) {
            $this->form_validation->set_rules('university_noc', 'University NOC', '');
        }
    }

    $this->form_validation->set_rules('institute_noc_type', 'NOC from Institute Type', 'required');
    if (empty($this->input->post("institute_noc_old"))) {
        if (($_FILES['institute_noc']['name'] == "") && (empty($this->input->post("institute_noc_temp")))) {
            $this->form_validation->set_rules('institute_noc', 'Institute NOC', 'required');
        }
    }
}
//for 3
 if ($dbrow->form_data->study_place == 3) {
        $this->form_validation->set_rules('eligibility_certificate_type', 'Eligibility Certificate Type', '');
        if (empty($this->input->post("eligibility_certificate_old"))) {
            if (($_FILES['eligibility_certificate']['name'] == "") && (empty($this->input->post("eligibility_certificate_temp")))) {
                $this->form_validation->set_rules('eligibility_certificate', 'Eligibility Certificate', '');
            }
        }

        $this->form_validation->set_rules('screening_result_type', 'Screening Test Result Type', 'required');
        if (empty($this->input->post("screening_result_old"))) {
            if (($_FILES['screening_result']['name'] == "") && (empty($this->input->post("screening_result_temp")))) {
                $this->form_validation->set_rules('screening_result', 'Screening Test Result', 'required');
            }
        }

        $this->form_validation->set_rules('passport_visa_type', 'Passport and Visa Type', 'required');
        if (empty($this->input->post("passport_visa_type_old"))) {
            if (($_FILES['passport_visa']['name'] == "") && (empty($this->input->post("passport_visa_temp")))) {
                $this->form_validation->set_rules('passport_visa', 'Passport and Visa', 'required');
            }
        }

        $this->form_validation->set_rules('all_docs_type', 'All Documents Type', 'required');
        if (empty($this->input->post("all_docs_type_old"))) {
            if (($_FILES['all_docs']['name'] == "") && (empty($this->input->post("all_docs_temp")))) {
                $this->form_validation->set_rules('all_docs', 'All Documents', 'required');
            }
        }
    }

        
    
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
       if (strlen($this->input->post("admit_card_temp")) > 0) {
         echo("1");
        $admitcard = movedigilockerfile($this->input->post('admit_card_temp'));
        $admit_card = $admitcard["upload_status"]?$admitcard["uploaded_path"]:null;
        } else {
            echo("2");
            $admitcard = cifileupload("admit_card");
            //pre($admitcard);
            $admit_card= $admitcard["upload_status"]?$admitcard["uploaded_path"]:null;
            //pre($admit_card);
        }
        
        
        if (strlen($this->input->post("hs_marksheet_temp")) > 0) {
        $hsmarksheet = movedigilockerfile($this->input->post('hs_marksheet_temp'));
        $hs_marksheet = $hsmarksheet["upload_status"]?$hsmarksheet["uploaded_path"]:null;
        } else {
             $hsmarksheet = cifileupload("hs_marksheet");
            $hs_marksheet = $hsmarksheet["upload_status"]?$hsmarksheet["uploaded_path"]:null;
        }

        if (strlen($this->input->post("reg_certificate_temp")) > 0) {
        $regcertificate = movedigilockerfile($this->input->post('reg_certificate_temp'));
        $reg_certificate = $regcertificate["upload_status"]?$regcertificate["uploaded_path"]:null;
        } else {
             $regcertificate = cifileupload("reg_certificate");
             $reg_certificate = $regcertificate["upload_status"]?$regcertificate["uploaded_path"]:null;
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

        if (strlen($this->input->post("college_noc_temp")) > 0) {
        $collegenoc = movedigilockerfile($this->input->post('college_noc_temp'));
        $college_noc = $collegenoc["upload_status"]?$collegenoc["uploaded_path"]:null;
        } else {
             $collegenoc = cifileupload("college_noc");
             $college_noc = $collegenoc["upload_status"]?$collegenoc["uploaded_path"]:null;
        }

        if (strlen($this->input->post("director_noc_temp")) > 0) {
        $directornoc = movedigilockerfile($this->input->post('director_noc_temp'));
        $director_noc = $directornoc["upload_status"]?$directornoc["uploaded_path"]:null;
        } else {
             $directornoc = cifileupload("director_noc");
             $director_noc = $directornoc["upload_status"]?$directornoc["uploaded_path"]:null;
        }

        if (strlen($this->input->post("university_noc_temp")) > 0) {
        $universitynoc = movedigilockerfile($this->input->post('university_noc_temp'));
        $university_noc = $universitynoc["upload_status"]?$universitynoc["uploaded_path"]:null;
        } else {
             $universitynoc = cifileupload("university_noc");
             $university_noc = $universitynoc["upload_status"]?$universitynoc["uploaded_path"]:null;
        }
        
        if (strlen($this->input->post("institute_noc_temp")) > 0) {
        $institutenoc = movedigilockerfile($this->input->post('institute_noc_temp'));
        $institute_noc = $institutenoc["upload_status"]?$institutenoc["uploaded_path"]:null;
        } else {
             $institutenoc = cifileupload("university_noc");
             $institute_noc = $institutenoc["upload_status"]?$institutenoc["uploaded_path"]:null;
        }

        if (strlen($this->input->post("eligibility_certificate_temp")) > 0) {
        $eligibilitycertificate = movedigilockerfile($this->input->post('eligibility_certificate_temp'));
        $eligibility_certificate = $eligibilitycertificate["upload_status"]?$eligibilitycertificate["uploaded_path"]:null;
        } else {
             $eligibilitycertificate = cifileupload("eligibility_certificate");
             $eligibility_certificate = $eligibilitycertificate["upload_status"]?$eligibilitycertificate["uploaded_path"]:null;
        }

        if (strlen($this->input->post("screening_result_temp")) > 0) {
        $screeningresult = movedigilockerfile($this->input->post('screening_result_temp'));
        $screening_result = $screeningresult["upload_status"]?$screeningresult["uploaded_path"]:null;
        } else {
             $screeningresult = cifileupload("screening_result");
             $screening_result = $screeningresult["upload_status"]?$screeningresult["uploaded_path"]:null;
        }
        if (strlen($this->input->post("passport_visa_temp")) > 0) {
        $passportvisa = movedigilockerfile($this->input->post('passport_visa_temp'));
        $passport_visa = $passportvisa["upload_status"]?$passportvisa["uploaded_path"]:null;
        } else {
             $passportvisa = cifileupload("passport_visa");
             $passport_visa = $passportvisa["upload_status"]?$passportvisa["uploaded_path"]:null;
        }

        if (strlen($this->input->post("all_docs_temp")) > 0) {
        $alldocs = movedigilockerfile($this->input->post('all_docs_temp'));
        $all_docs = $alldocs["upload_status"]?$alldocs["uploaded_path"]:null;
         } else {
             $alldocs = cifileupload("all_docs");
             $all_docs = $alldocs["upload_status"]?$alldocs["uploaded_path"]:null;
        }
        if (strlen($this->input->post("annexure_temp")) > 0) {
        $annexure = movedigilockerfile($this->input->post('annexure_temp'));
        $annexure = $annexure["upload_status"]?$annexure["uploaded_path"]:null;
        } else {
             $annexure = cifileupload("annexure");
             $annexure = $annexure["upload_status"]?$annexure["uploaded_path"]:null;
        }
          if (strlen($this->input->post("ten_pass_certificate_temp")) > 0) {
        $tenpasscertificate = movedigilockerfile($this->input->post('ten_pass_certificate_temp'));
        $ten_pass_certificate = $tenpasscertificate["upload_status"]?$tenpasscertificate["uploaded_path"]:null;
        } else {
             $tenpasscertificate = cifileupload("ten_pass_certificate");
             $ten_pass_certificate = $tenpasscertificate["upload_status"]?$tenpasscertificate["uploaded_path"]:null;
        }
        if (strlen($this->input->post("photograph_temp")) > 0) {
            
        $photograph = movedigilockerfile($this->input->post('photograph_temp'));
        $photograph = $photograph["upload_status"]?$photograph["uploaded_path"]:null;
        } else {
             $photograph = cifileupload("photograph");
             $photograph = $photograph["upload_status"]?$photograph["uploaded_path"]:null;
        }
        
        if (strlen($this->input->post("signature_temp")) > 0) {
        $signature = movedigilockerfile($this->input->post('signature_temp'));
        $signature = $signature["upload_status"]?$signature["uploaded_path"]:null;
        } else {
            
             $signature = cifileupload("signature");
             $signature = $signature["upload_status"]?$signature["uploaded_path"]:null;
        }
        $uploadedFiles = array(
            "admit_card_old" => strlen($admit_card)?$admit_card:$this->input->post("admit_card_old"),
            "hs_marksheet_old" => strlen($hs_marksheet)?$hs_marksheet:$this->input->post("hs_marksheet_old"),
            "reg_certificate_old" => strlen($reg_certificate)?$reg_certificate:$this->input->post("reg_certificate_old"),
            "mbbs_marksheet_old" => strlen($mbbs_marksheet)?$mbbs_marksheet:$this->input->post("mbbs_marksheet_old"),
            "pass_certificate_old" => strlen($pass_certificate)?$pass_certificate:$this->input->post("pass_certificate_old"),
            "college_noc_old" => strlen($college_noc)?$college_noc:$this->input->post("college_noc_old"),
            "director_noc_old" => strlen($director_noc)?$director_noc:$this->input->post("director_noc_old"),
            "university_noc_old" => strlen($university_noc)?$university_noc:$this->input->post("university_noc_old"),
            "institute_noc_old" => strlen($institute_noc)?$institute_noc:$this->input->post("institute_noc_old"),
            "eligibility_certificate_old" => strlen($eligibility_certificate)?$eligibility_certificate:$this->input->post("eligibility_certificate_old"),
            "screening_result_old" => strlen($screening_result)?$screening_result:$this->input->post("screening_result_old"),
            "passport_visa_old" => strlen($passport_visa)?$passport_visa:$this->input->post("passport_visa_old"),
            "all_docs_old" => strlen($all_docs)?$all_docs:$this->input->post("all_docs_old"),
            "ten_pass_certificate_old" => strlen($ten_pass_certificate)?$ten_pass_certificate:$this->input->post("ten_pass_certificate_old"),
            "photograph_old" => strlen($photograph)?$photograph:$this->input->post("photograph_old"),
            "signature_old" => strlen($signature)?$signature:$this->input->post("signature_old"),
            //"soft_copy_old" => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old")
        );
        //pre($signature);
        //pre($uploadedFiles);
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        
        if ($this->form_validation->run() == FALSE) {
            //
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->queryform($objId);
        } else {            

            $dbRow = $this->registration_model->get_by_doc_id($objId);

            if(count((array)$dbRow)) {
                $data = array(
                    'form_data.admit_card_type' => $this->input->post("admit_card_type"),
                    'form_data.hs_marksheet_type' => $this->input->post("hs_marksheet_type"),
                    'form_data.reg_certificate_type' => $this->input->post("reg_certificate_type"),
                    'form_data.mbbs_marksheet_type' => $this->input->post("mbbs_marksheet_type"),
                    'form_data.pass_certificate_type' => $this->input->post("pass_certificate_type"),
                    'form_data.college_noc_type' => $this->input->post("college_noc_type"),
                    'form_data.director_noc_type' => $this->input->post("director_noc_type"),
                    'form_data.university_noc_type' => $this->input->post("university_noc_type"),
                    'form_data.institute_noc_type' => $this->input->post("institute_noc_type"),
                    'form_data.eligibility_certificate_type' => $this->input->post("eligibility_certificate_type"),
                    'form_data.screening_result_type' => $this->input->post("screening_result_type"),
                    'form_data.passport_visa_type' => $this->input->post("passport_visa_type"),
                    'form_data.all_docs_type' => $this->input->post("all_docs_type"),
                    'form_data.ten_pass_certificate_type' => $this->input->post("ten_pass_certificate_type"),
                    'form_data.photograph_type' => $this->input->post("photograph_type"),
                    'form_data.signature_type' => $this->input->post("signature_type"),

                    'form_data.admit_card' => strlen($admit_card)?$admit_card:$this->input->post("admit_card_old"),
                    'form_data.hs_marksheet' => strlen($hs_marksheet)?$hs_marksheet:$this->input->post("hs_marksheet_old"),
                    'form_data.reg_certificate' => strlen($reg_certificate)?$reg_certificate:$this->input->post("reg_certificate_old"),
                    'form_data.mbbs_marksheet' => strlen($mbbs_marksheet)?$mbbs_marksheet:$this->input->post("mbbs_marksheet_old"), 
                  'form_data.pass_certificate' => strlen($pass_certificate)?$pass_certificate:$this->input->post("pass_certificate_old"),
                    'form_data.college_noc' => strlen($college_noc)?$college_noc:$this->input->post("college_noc"),
                    'form_data.director_noc' => strlen($director_noc)?$director_noc:$this->input->post("director_noc_old"),
                    'form_data.university_noc' => strlen($university_noc)?$university_noc:$this->input->post("university_noc_old"),
                     'form_data.institute_noc' => strlen($institute_noc)?$institute_noc:$this->input->post("institute_noc_old"),
                    'form_data.eligibility_certificate' => strlen($eligibility_certificate)?$eligibility_certificate:$this->input->post("eligibility_certificate_old"),
                    'form_data.screening_result' => strlen($screening_result)?$screening_result:$this->input->post("screening_result_old"),
                    'form_data.passport_visa' => strlen($passport_visa)?$passport_visa:$this->input->post("passport_visa_old"), 
                    'form_data.all_docs' => strlen($all_docs)?$all_docs:$this->input->post("all_docs_old"), 
                    'form_data.ten_pass_certificate' => strlen($ten_pass_certificate)?$ten_pass_certificate:$this->input->post("ten_pass_certificate_old"),
                    'form_data.photograph' => strlen($photograph)?$photograph:$this->input->post("photograph_old"), 
                    'form_data.signature' => strlen($signature)?$signature:$this->input->post("signature_old"), 
                    //'form_data.soft_copy' => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old"),      
                   'form_data.updated_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                );
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
                redirect('spservices/acmr_provisional_certificate/registration/preview/'.$objId);
            } else {
                $this->session->set_flashdata('fail','Unable to update data!!! Please try again.');
                $this->index();
            }
            }//End of if else
        }//End of if else      
    }//End of querysubmit()
     public function payment_query($objId=null) {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->registration_model->get_row(array("_id"=> new ObjectId($objId), "service_data.appl_status"=>"FRS"));
            if($dbRow) {
                $data=array(
                    "service_data.service_name"=>$this->serviceName,
                    "dbrow"=>$dbRow
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('acmr_provisional_certificate/payment/initiate/',$data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found in query mode against object id : '.$objId);
                redirect('spservices/acmr_provisional_certificate/registration');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/acmr_provisional_certificate/registration');
        }//End of if else
    }//End of query()

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
        $str = "RTPS-ACMRPRCMD/" . $date."/" .$number;
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
                    'current_users' => $current_users,
                    'processing_history'=>$processing_history
                );
                $this->registration_model->update($obj, $data_to_update);

                //Sending submission SMS
                $nowTime = date('Y-m-d H:i:s');
                $sms = array(
                    "mobile" => (int)$dbRow->form_data->mobile,
                    "applicant_name" => $dbRow->form_data->applicant_name,
                    "service_name" => 'ACMR - PROVISIONAL REGISTRATION CERTIFICATE OF MBBS DOCTOR',
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
            //var_dump($dbRow); die;
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
        $this->load->view('acmr_provisional_certificate/output_certificate');
    }//End of index()//End of download_certificate()
     function get_states() {
    $field_name = $this->input->post("field_name");
    $country_name = $this->input->post("field_value");
    
    echo '<select name="'.$field_name.'" id="'.$field_name.'" class="form-control">';
    
    if(strlen($country_name)) {
        $this->load->model('acmr_provisional_certificate/states_model');
        $states = $this->states_model->get_distinct_results();
        
        if ($states) {
            echo "<option value=''>Please Select</option>";
            
            // Add Assam
            echo '<option value="assam">Assam</option>';
            
            foreach ($states as $state) {
                echo '<option value="' . $state->slc . '" >' . $state->state_name_english . '</option>';
            }
            
            // Add Meghalaya
            echo '<option value="meghalaya">Meghalaya</option>';
        } else {
            echo "<option value=''>No records found</option>";
        }
    } else {
        echo "<option value=''>Country cannot be empty</option>";
    }
    
    echo '</select>';
}

   function get_districts() {
    $field_name = $this->input->post("field_name");
    $slc = (int)$this->input->post("field_value");
    //die($field_name.' : '.$this->input->post("field_value"));
    echo '<select name="'.$field_name.'" id="'.$field_name.'" class="form-control bride_district">';
    
    if(strlen($slc)) {
        $this->load->model('acmr_provisional_certificate/districts_model');
        $districts = $this->districts_model->get_distinct_results(array("slc" => $slc));
        
        if ($districts) {
            echo "<option value=''>Please Select</option>";
            
            foreach ($districts as $district) {
                echo '<option value="' . $district->district_name_english . '" >' . $district->district_name_english . '</option>';
            }
        } else {
            echo "<option value=''>No records found</option>";
        }
    } else {
        echo "<option value=''>slc ID cannot be empty</option>";
    }
    
    echo '</select>';
}
//End of get_districts()
}//End
