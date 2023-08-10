<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps {
    
    private $serviceName = "Issue of Credit Points for attending CME";
    Private $serviceId = "CPCME";

    public function __construct() {
        parent::__construct();
        $this->load->model('acmr_cp_cme/registration_model');
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
        $data=array("pageTitle" => "Issue of Credit Points for attending CME");
        $filter = array(
            "_id" =>new ObjectId($obj_id), 
            "service_data.appl_status" => "DRAFT"
        );
        $data["dbrow"] = $this->registration_model->get_row($filter);

        $data['usser_type']=$this->slug;
       
        $this->load->view('includes/frontend/header');
        $this->load->view('acmr_cp_cme/acmr_cp_cme',$data);
        $this->load->view('includes/frontend/footer');
    }//End of index()

    public function submit(){
      // pre($this->input->post());
        $objId = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");
        
        $this->form_validation->set_rules('applying_org', 'Name Applying Organization', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('applicant_name', 'Name of person responsible for applying', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|integer|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|strip_tags|valid_email');
        $this->form_validation->set_rules('per_address', 'Permanent Address', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('corres_address', 'Correspondence Address', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('conference_title', 'Title of the Conference/CME/Workshop', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('start_date', 'start date', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('end_date', 'end date', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('academic_day1', 'Time of start of academic session of Day 1', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('conclusion_day1', 'Time of conclusion of academic session of Day 1', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('academic_day2', 'Time of start of academic session of Day 2', 'trim|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('conclusion_day2', 'Time of conclusion of academic session of Day 2', 'trim|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('academic_day3', 'Time of start of academic session of Day 3', 'trim|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('conclusion_day3', 'Time of conclusion of academic session of Day 3', 'trim|xss_clean|strip_tags');  
        $this->form_validation->set_rules('conference_location', 'Location of the Conference/CME/Workshop', 'trim|required|xss_clean|strip_tags'); 
       // $this->form_validation->set_rules('conference_organised_by', 'Conference/CME/Workshop organised by', 'trim|required|xss_clean|strip_tags');


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
                    //"service_data.service_id" => $serviceId
                );
                $rows = $this->registration_model->get_row($filter);
                
                if($rows == false)
                    break;
            }

            // var_dump($rows);
            // exit();

            $service_data = [
                "department_id" => "100031",
                "department_name" => "Assam Council of Medical Registration",
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => "Assam Council of Medical Registration (Guwahati)", // office name
                "submission_date" =>new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
                "service_timeline" => "7 working Days",
                "appl_status" => "DRAFT",
                "district" => "Guwahati",
            ];
        //    pre(strtotime( $this->input->post("academic_day1") ) );
        //    43200
        //32400

    
// Creating DateTime objects
            // $dateTimeObject1 = date_create( $this->input->post("academic_day1").":00" ); 
            // $dateTimeObject2 = date_create( $this->input->post("conclusion_day1").":00" ); 

            // $dateTimeObject3 = date_create( $this->input->post("academic_day2").":00" ); 
            // $dateTimeObject4 = date_create( $this->input->post("conclusion_day2").":00" );

            // $dateTimeObject5 = date_create( $this->input->post("academic_day3").":00" ); 
            // $dateTimeObject6 = date_create( $this->input->post("conclusion_day3").":00" );
            
// Calculating the difference between DateTime objects
                // $interval = date_diff($dateTimeObject1, $dateTimeObject2); 

                // $interval2 = date_diff($dateTimeObject3, $dateTimeObject4);

                // $interval3 = date_diff($dateTimeObject5, $dateTimeObject6);
                

                // $minutes1 = $interval->days * 24 * 60;
                // $minutes1 += $interval->h * 60;
                // $minutes1 += $interval->i;

                // $minutes2 = $interval2->days * 24 * 60;
                // $minutes2 += $interval2->h * 60;
                // $minutes2 += $interval2->i;

                // $minutes3 = $interval3->days * 24 * 60;
                // $minutes3 += $interval3->h * 60;
                // $minutes3 += $interval3->i;


                // $minutes = $minutes1 + $minutes2 + $minutes3 ;
                


                //Printing result in minutes
                // echo("Difference in minutes is:");
                // $total = (int)($minutes/60).' hours :'.($minutes%60).' minutes';
                // die;

            $form_data = [     
                'applying_org' => $this->input->post("applying_org"),
                'applicant_name' => $this->input->post("applicant_name"),
                'mobile' => $this->input->post("mobile"),
                'email' => $this->input->post("email"),                
                'per_address' => $this->input->post("per_address"),
                'corres_address' => trim($this->input->post("corres_address")),

                'conference_title' => $this->input->post("conference_title"),
                'start_date' => $this->input->post("start_date"),
                'end_date' => $this->input->post("end_date"),

                'academic_day1' => $this->input->post("academic_day1"),
                'conclusion_day1' => $this->input->post("conclusion_day1"),
                'academic_day2' => $this->input->post("academic_day2"),
              
                'conclusion_day2' => $this->input->post("conclusion_day2"),
                'academic_day3' => $this->input->post("academic_day3"),
                'conclusion_day3' => $this->input->post("conclusion_day3"),
                'conference_location' => $this->input->post("conference_location"),
                'conference_organised_by' => $this->input->post("conference_organised_by"),
                'live_workshop' => $this->input->post("live_workshop"),
                
                'user_id' => $sessionUser['userId']->{'$id'},    
                'user_type' => $this->slug,
                'total_hours' =>  $total,

                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];
    // pre(     $form_data);


            if(strlen($objId)) {
                $form_data["draft_copy_type"] = $this->input->post("draft_copy_type");
                $form_data["draft_copy"] = $this->input->post("draft_copy");
                $form_data["request_letter_type"] = $this->input->post("request_letter_type");
                $form_data["request_letter"] = $this->input->post("request_letter");
                $form_data["cme_program_type"] = $this->input->post("cme_program_type");
                $form_data["cme_program"] = $this->input->post("cme_program");
                
                $form_data["soft_copy_type"] = $this->input->post("soft_copy_type");
                $form_data["soft_copy"] = $this->input->post("soft_copy");

                if (!empty($this->input->post("others_type"))) {
                    $form_data["others_type"] = $this->input->post("others_type");
                    $form_data["others"] = $this->input->post("others");
                }
            }

            $inputs = [
                'service_data'=>$service_data,
                'form_data' => $form_data
            ];
                
            if(strlen($objId)) {

                //pre($inputs);
                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                $this->session->set_flashdata('success','Your application has been successfully submitted');
                redirect('spservices/acmr_cp_cme/registration/fileuploads/'.$objId);
            } else {
                $insert=$this->registration_model->insert($inputs);
                if($insert){
                    $objectId=$insert['_id']->{'$id'};
                    $this->session->set_flashdata('success','Your application has been successfully submitted');
                    redirect('spservices/acmr_cp_cme/registration/fileuploads/'.$objectId);
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
            $this->load->view('acmr_cp_cme/acmr_cp_cmeuploads_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/acmr_cp_cme/registration');
        }//End of if else
    }//End of fileuploads()

    public function submitfiles() { 
        //pre($this->input->post("others_type"));       
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $this->form_validation->set_rules('request_letter_type', 'Request Letter for Physical CME on Letter head ', 'required');
        $this->form_validation->set_rules('cme_program_type', 'CME Program/Schedule', 'required');
        $this->form_validation->set_rules('draft_copy_type', 'Draft copy/copies of Certificate(s) to be issued to doctors', 'required');


        if (empty($this->input->post("request_letter_old"))) {
            if(((empty($this->input->post("request_letter_type"))) && (($_FILES['request_letter']['name'] != "") || (!empty($this->input->post("request_letter_temp"))))) || ((!empty($this->input->post("request_letter_type"))) && (($_FILES['request_letter']['name'] == "") && (empty($this->input->post("request_letter_temp")))))) {
            
                $this->form_validation->set_rules('request_letter_type', 'Request Letter Type', 'required');
                $this->form_validation->set_rules('request_letter', 'Request Letter for Physical', 'required');
            }
        }

        if (empty($this->input->post("cme_program_old"))) {
            if(((empty($this->input->post("cme_program_type"))) && (($_FILES['cme_program']['name'] != "") || (!empty($this->input->post("cme_program_temp"))))) || ((!empty($this->input->post("cme_program_type"))) && (($_FILES['cme_program']['name'] == "") && (empty($this->input->post("cme_program_temp")))))) {
            
                $this->form_validation->set_rules('cme_program_type', 'cme_program Type', 'required');
                $this->form_validation->set_rules('cme_program', 'cme_program', 'required');
            }
        }

        if (empty($this->input->post("draft_copy_old"))) {
            if(((empty($this->input->post("draft_copy_type"))) && (($_FILES['affidavit']['name'] != "") || (!empty($this->input->post("draft_copy_temp"))))) || ((!empty($this->input->post("draft_copy_type"))) && (($_FILES['draft_copy']['name'] == "") && (empty($this->input->post("draft_copy_temp")))))) {
            
                $this->form_validation->set_rules('draft_copy_type', 'draft_copy Type', 'required');
                $this->form_validation->set_rules('draft_copy', 'draft_copy', 'required');
            }
        }



        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if (strlen($this->input->post("request_letter_temp")) > 0) {
            $requestLetter = movedigilockerfile($this->input->post('request_letter_temp'));
            $request_letter = $requestLetter["upload_status"]?$requestLetter["uploaded_path"]:null;
        } else {
            $requestLetter = cifileupload("request_letter");
            $request_letter = $requestLetter["upload_status"]?$requestLetter["uploaded_path"]:null;
        }

        if (strlen($this->input->post("cme_program_temp")) > 0) {
            $cmeProgram = movedigilockerfile($this->input->post('cme_program_temp'));
            $cme_program = $cmeProgram["upload_status"]?$cmeProgram["uploaded_path"]:null;
        } else {
            $cmeProgram = cifileupload("cme_program");
            $cme_program = $cmeProgram["upload_status"]?$cmeProgram["uploaded_path"]:null;
        }
        if (strlen($this->input->post("draft_copy_temp")) > 0) {
            $draftCopy = movedigilockerfile($this->input->post('draft_copy_temp'));
            $draft_copy = $draftCopy["upload_status"]?$draftCopy["uploaded_path"]:null;
        } else {
            $draftCopy = cifileupload("draft_copy");
            $draft_copy = $draftCopy["upload_status"]?$draftCopy["uploaded_path"]:null;
        }

        $uploadedFiles = array(
            "request_letter_old" => strlen($request_letter)?$request_letter:$this->input->post("request_letter_old"),
            "cme_program_old" => strlen($cme_program)?$cme_program:$this->input->post("cme_program_old"),
            "draft_copy_old" => strlen($draft_copy)?$draft_copy:$this->input->post("draft_copy_old"),
        
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.request_letter_type' => $this->input->post("request_letter_type"),
                'form_data.cme_program_type' => $this->input->post("cme_program_type"),
                'form_data.draft_copy_type' => $this->input->post("draft_copy_type"),
                'form_data.request_letter' => strlen($request_letter)?$request_letter:$this->input->post("request_letter_old"),
                'form_data.cme_program' => strlen($cme_program)?$cme_program:$this->input->post("cme_program_old"),
                'form_data.draft_copy' => strlen($draft_copy)?$draft_copy:$this->input->post("draft_copy_old"),
                
            );

            if (!empty($this->input->post("others_type"))) {
                $data["form_data.others_type"] = $this->input->post("others_type");
                $data["form_data.others"] = strlen($others)?$others:$this->input->post("others_old");
            }
            
            $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success','Your application has been successfully submitted');
            redirect('spservices/acmr_cp_cme/registration/preview/'.$objId);
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
            $this->load->view('acmr_cp_cme/acmr_cp_cmepreview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/acmr_cp_cme/registration');
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
            $this->load->view('acmr_cp_cme/acmr_cp_cmeapplication_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/acmr_cp_cme/registration');
        }//End of if else
    }

    public function track($objId=null) {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
                "service_data.service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('acmr_cp_cme/acmr_cp_cmecertificatetrack_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/acmr_cp_cme/');
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
                $this->load->view('acmr_cp_cme/acmr_cp_cmequery_view',$data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found in query mode against object id : '.$objId);
                redirect('spservices/acmr_cp_cme/registration');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/acmr_cp_cme/registration');
        }//End of if else
    }//End of query()

    public function querysubmit() { 
        // pre($this->input->post("appl_ref_no"));
        // return;       
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");

        $this->form_validation->set_rules('applying_org', 'Request Letter for Physical CME on Letter head ', 'required');
        

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        $requestLetter = cifileupload("request_letter");
        $request_letter = $requestLetter["upload_status"]?$requestLetter["uploaded_path"]:$this->input->post("request_letter_old");

        $cmeProgram = cifileupload("cme_program");
        $cme_program = $cmeProgram["upload_status"]?$cmeProgram["uploaded_path"]:$this->input->post("cme_program_old");

        $draftCopy = cifileupload("draft_copy");
        $draft_copy = $draftCopy["upload_status"]?$draftCopy["uploaded_path"]:$this->input->post("draft_copy_old");
        
        $uploadedFiles = array(
            "request_letter_old" => strlen($request_letter)?$request_letter:$this->input->post("request_letter_old"),
            "cme_program_old" => strlen($cme_program)?$cme_program:$this->input->post("cme_program_old"),
            "draft_copy_old" => strlen($draft_copy)?$draft_copy:$this->input->post("draft_copy_old"),
        
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.request_letter_type' => $this->input->post("request_letter_type"),
                'form_data.cme_program_type' => $this->input->post("cme_program_type"),
                'form_data.draft_copy_type' => $this->input->post("draft_copy_type"),
                //'form_data.others_type' => $this->input->post("others_type"),
                //'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),
                'form_data.request_letter' => strlen($request_letter)?$request_letter:$this->input->post("request_letter_old"),
                'form_data.cme_program' => strlen($cme_program)?$cme_program:$this->input->post("cme_program_old"),
                'form_data.draft_copy' => strlen($draft_copy)?$draft_copy:$this->input->post("draft_copy_old"),
                //'form_data.others' => strlen($others)?$others:$this->input->post("others_old"),
                //'form_data.soft_copy' => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old")
            );
            $dbRow = $this->registration_model->get_by_doc_id($objId);
            if (true) {
                // $data["form_data.others_type"] = $this->input->post("others_type");
                // $data["form_data.others"] = strlen($others)?$others:$this->input->post("others_old");
            
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
                redirect('spservices/acmr_cp_cme/registration/preview/'.$objId);
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
        $str = "RTPS-CPCME/" . $date."/" .$number;
        return $str;
    }//End of generateID()    

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
                $this->registration_model->update($obj,$data_to_update);

                //Sending submission SMS
                $nowTime = date('Y-m-d H:i:s');
                $sms = array(
                    "mobile" => (int)$dbRow->form_data->mobile,
                    "applicant_name" => $dbRow->form_data->applicant_name,
                    "service_name" => 'Credit Points for attending CME',
                    "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                    "app_ref_no" => $dbRow->service_data->appl_ref_no
                );
                // sms_provider("submission", $sms);
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

    public function output_certificate($obj_id=null) {
        $this->load->view('acmr_cp_cme/output_certificate');
    }//End of index()
}//End of Castecertificate
