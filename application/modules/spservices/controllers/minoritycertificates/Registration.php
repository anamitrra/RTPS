<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps {
    
    private $deptName = "Welfare of Minorities and Development Department";
    Private $deptId = "WMADD";
    private $serviceName = "Application for Minority Community Certificate";
    private $serviceId = "MCC";

    public function __construct() {
        parent::__construct();
        $this->load->model('minoritycertificates/districts_model');
        $this->load->model('minoritycertificates/circles_model');
        $this->load->model('minoritycertificates/minoritycertificates_model');
        $this->load->model('minoritycertificates/office_users_model');
        
        $this->lang->load('mcc_registration', $this->session->mcc_lang);
        
        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');       
        
        if($this->session->role){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
        
        if($this->slug === "CSC"){                
            $this->apply_by = $this->session->userId;
        } else {
            $this->apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        }//End of if else
    }//End of __construct()

    public function index($objId=null) {
        check_application_count_for_citizen();
        $data=array(
            "obj_id" => $objId,
            "service_name"=>$this->serviceName
        );
        if ($this->checkObjectId($objId)) {
            $dbFilter = array(
                '_id'=>new ObjectId($objId),
                'service_data.applied_by' => $this->apply_by,
                'service_data.appl_status' => 'DRAFT'
            );
            $data["dbrow"] = $this->minoritycertificates_model->get_row($dbFilter);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "FRESH";
        }//End of if else       
        $this->load->view('includes/frontend/header');
        $this->load->view('minoritycertificates/registration_view',$data);
        $this->load->view('includes/frontend/footer');
    }//End of index()
    
    public function submit(){
        $objId = $this->input->post("obj_id");        
        $this->form_validation->set_rules('applicant_name', 'Applicant name', 'trim|required|alpha_numeric_spaces|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('father_name', 'Father', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mother_name', 'Mother', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mobile_number', 'Contact number', 'trim|integer|required|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('email_id', 'Email id', 'valid_email|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('dob', 'DOB', 'trim|required|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('community', 'Community', 'trim|required|xss_clean|strip_tags');
        
        $this->form_validation->set_rules('pa_house_no', 'House no.', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_street', 'Street', 'trim|required|xss_clean|strip_tags');                
        $this->form_validation->set_rules('pa_village', 'Village', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_post_office', 'Post oddice', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_pin_code', 'Pin code', 'trim|required|integer|exact_length[6]|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_state', 'State', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_district_id', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_circle', 'Circle', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_police_station', 'Police station', 'trim|required|xss_clean|strip_tags');
        
        $this->form_validation->set_rules('address_same', 'Is same address', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_house_no', 'House no.', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_street', 'Street', 'trim|required|xss_clean|strip_tags');                
        $this->form_validation->set_rules('ca_village', 'Village', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_post_office', 'Post oddice', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_pin_code', 'Pin code', 'trim|required|integer|exact_length[6]|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_state', 'State', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_district_id', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_circle', 'Circle', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_police_station', 'Police station', 'trim|required|xss_clean|strip_tags');
        
        $this->form_validation->set_rules('id_proof_type', 'ID Proof', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('address_proof_type', 'Address Proof', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('age_proof_type', 'Age Proof', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('passport_photo_type', 'Photo', 'trim|required|xss_clean|strip_tags');
        
        //$this->form_validation->set_rules('aadhaar_consent_status', 'Aadhaar consent', 'trim|required|greater_than_equal_to[1]|xss_clean|strip_tags');        
        $this->form_validation->set_rules('mobile_verify_status', 'Mobile verify status', 'trim|required|greater_than_equal_to[1]|xss_clean|strip_tags');
        
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        $idProof = cifileupload("id_proof");
        $id_proof = $idProof["upload_status"]?$idProof["uploaded_path"]:null;

        $addressProof = cifileupload("address_proof");
        $address_proof = $addressProof["upload_status"]?$addressProof["uploaded_path"]:null;

        $ageProof = cifileupload("age_proof");
        $age_proof = $ageProof["upload_status"]?$ageProof["uploaded_path"]:null;

        $passportPhoto = cifileupload("passport_photo");
        $passport_photo = $passportPhoto["upload_status"]?$passportPhoto["uploaded_path"]:null;
                
        $passport_photo_data = $this->input->post("passport_photo_data");
        if((strlen($passport_photo)== 0) && (strlen($passport_photo_data) > 50)) {
            $passportPhotoData = str_replace('data:image/jpeg;base64,', '', $passport_photo_data);
            $passportPhotoData2 = str_replace(' ', '+', $passportPhotoData);
            $passportPhotoData64 = base64_decode($passportPhotoData2);

            $fileName = uniqid().'.jpeg';
            $dirPath = 'storage/docs/NECERTIFICATE/PHOTOS/';
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
                file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for NEC only</body></html>');
            }
            $passport_photo = $dirPath.$fileName;
            file_put_contents(FCPATH.$passport_photo, $passportPhotoData64);
        }
        $queryDoc = cifileupload("query_doc");
        $query_doc = $queryDoc["upload_status"]?$queryDoc["uploaded_path"]:null;
        
        $idProofFinal = strlen($id_proof)?$id_proof:$this->input->post("id_proof_old");
        $addressProofFinal = strlen($address_proof)?$address_proof:$this->input->post("address_proof_old");
        $ageProoFinal = strlen($age_proof)?$age_proof:$this->input->post("age_proof_old");
        $passportPhotoFinal = strlen($passport_photo)?$passport_photo:$this->input->post("passport_photo_old");
        
        $uploadedFiles = array(
            "id_proof_old" => $idProofFinal,
            "address_proof_old" => $addressProofFinal,
            "age_proof_old" => $ageProoFinal,
            "passport_photo_old" => $passportPhotoFinal,
            "query_doc_old" => strlen($query_doc)?$query_doc:$this->input->post("query_doc_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        
        $officeUserFilter = array(
            "district_id" => $this->input->post("pa_district_id")
            //"circle_name" => $this->input->post("pa_circle")
        );
        $officeUser = $this->office_users_model->get_row($officeUserFilter);
        
        if((strlen($idProofFinal) <= 10) || (strlen($addressProofFinal) <= 10) || (strlen($ageProoFinal) <= 10) || (strlen($passportPhotoFinal) <= 10)) {
            $this->session->set_flashdata('error','Please upload all required files');
            $obj_id = strlen($objId)?$objId:null;
            $this->index($obj_id);
        } elseif ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $obj_id = strlen($objId)?$objId:null;
            $this->index($obj_id);
        } elseif(($this->input->post("mobile_verify_status") == 0) && ($this->input->post("mobile_number") !== $this->session->verified_mobile_numbers[0])) { 
            $this->session->set_flashdata('error','Mobile no. '.$this->input->post("mobile_number").' does not matched with the OTP verified number : '.$this->session->verified_mobile_numbers[0]);
            $obj_id = strlen($objId)?$objId:null;
            $this->index($obj_id);
        } elseif($officeUser == false) {
            $data = array(
                "msg_title" => "Please note",
                "msg_body" => "Subimission of applications to the ".$this->input->post("pa_circle")." circle office is not enabled currently.<br>Please reach out to RTPS helpdesk at 1800-345-3574 or rtps-assam@assam.gov.in for any further queries.",
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('minoritycertificates/submitacknowledgement_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {           
            $pa_district_name = $this->input->post("pa_district_name");            
            $form_data = array(
                "user_id"=> $this->apply_by,
                'user_type' => $this->slug,                        
                'aadhaar_consent_status' => $this->input->post("aadhaar_consent_status"),
                'mobile_verify_status' => $this->input->post("mobile_verify_status"),
                'applicant_name' => $this->input->post("applicant_name"),
                'father_name' => $this->input->post("father_name"),
                'mother_name' => $this->input->post("mother_name"),
                'mobile_number' => $this->input->post("mobile_number"),
                'email_id' => $this->input->post("email_id"),
                'dob' => $this->input->post("dob"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'community' => $this->input->post("community"),
                        
                'pa_house_no' => $this->input->post("pa_house_no"),
                'pa_street' => $this->input->post("pa_street"),
                'pa_village' => $this->input->post("pa_village"),
                'pa_post_office' => $this->input->post("pa_post_office"),
                'pa_pin_code' => $this->input->post("pa_pin_code"),
                'pa_state' => $this->input->post("pa_state"),
                'pa_district_id' => $this->input->post("pa_district_id"),
                'pa_district_name' => $pa_district_name,
                'pa_circle' => $this->input->post("pa_circle"),
                'pa_police_station' => $this->input->post("pa_police_station"),
                 
                'address_same' => $this->input->post("address_same"),
                'ca_house_no' => $this->input->post("ca_house_no"),
                'ca_street' => $this->input->post("ca_street"),
                'ca_village' => $this->input->post("ca_village"),
                'ca_post_office' => $this->input->post("ca_post_office"),
                'ca_pin_code' => $this->input->post("ca_pin_code"),
                'ca_state' => $this->input->post("ca_state"),
                'ca_district_id' => $this->input->post("ca_district_id"),
                'ca_district_name' => $this->input->post("ca_district_name"),
                'ca_circle' => $this->input->post("ca_circle"),
                'ca_police_station' => $this->input->post("ca_police_station"),
                        
                'id_proof_type' => $this->input->post("id_proof_type"),
                'id_proof' => $idProofFinal,
                'address_proof_type' => $this->input->post("address_proof_type"),
                'address_proof' => $addressProofFinal,
                'age_proof_type' => $this->input->post("age_proof_type"),
                'age_proof' => $ageProoFinal,
                'passport_photo_type' => $this->input->post("passport_photo_type"),
                'passport_photo' => $passportPhotoFinal,
                'query_answered' => $this->input->post("query_answered"),
                'query_doc' => strlen($query_doc)?$query_doc:$this->input->post("query_doc_old"),                        
                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            );
            
            if(strlen($objId)) {
                $data = array(
                    "service_data.applied_by" => $this->apply_by,
                    "service_data.district" => $pa_district_name,
                    "form_data" => $form_data
                );
                $dbRow = $this->minoritycertificates_model->get_row(array('_id' => new ObjectId($objId), "service_data.appl_status" => "QUERY_ARISE"));
                if($dbRow) {
                    //Backup old data to minoritycertificates_backup collection
                    $backupRow = (array)$dbRow;
                    unset($backupRow["_id"]);
                    $data["data_before_query"] = $backupRow;
                }
                $this->minoritycertificates_model->update_where(['_id' => new ObjectId($objId)], $data);
                $this->session->set_flashdata('success','Your application has been successfully updated');
                redirect('spservices/minority-certificate-preview/'.$objId);
            } else {
                $service_data = array(
                    "department_id" => $this->deptId,
                    "department_name" => $this->deptName,
                    "submission_location" => $this->deptName,
                    "service_id" => $this->serviceId,
                    "service_name" => $this->serviceName,
                    "applied_by" => $this->apply_by,
                    "appl_ref_no" => $this->getID(7),
                    "created_at" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    "district" => $pa_district_name,
                    "appl_status" => "DRAFT",
                    "submission_mode" => $this->slug,
                    "service_timeline" => "15 Days"
                );
                $data = array('service_data' => $service_data, 'form_data' => $form_data);
                $insert=$this->minoritycertificates_model->insert($data);
                if($insert){
                    $objectId=$insert['_id']->{'$id'};
                    $this->minoritycertificates_model->update_where(['_id' => new ObjectId($objectId)], array('service_data.appl_id'=>$objectId));
                    $this->session->set_flashdata('success','Your application has been successfully submitted');
                    redirect('spservices/minority-certificate-preview/'.$objectId);
                    exit();
                } else {
                    $this->session->set_flashdata('fail','Unable to submit data!!! Please try again.');
                    $this->index();
                }//End of if else
            }//End of if else
        }//End of if else
    }//End of submit()
        
    public function preview($objId=null) { //die($objId);
        $dbRow = $this->minoritycertificates_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('minoritycertificates/registrationpreview_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','Records does not exist');
            redirect('spservices/minority-certificate');
        }//End of if else
    }//End of preview()
    
    function get_circles() {
        $input_name = $this->input->post("input_name");
        $fld_name = $this->input->post("fld_name");
        $fld_value = (int)$this->input->post("fld_value");
        $circles = $this->circles_model->get_rows(array($fld_name=>$fld_value)); ?>                   
        <select name="<?=$input_name?>" id="<?=$input_name?>" class="form-control">
            <option value="">Select a circle </option>
            <?php if($circles) { 
                foreach($circles as $circle) {
                    echo '<option value="'.$circle->circle_name.'">'.$circle->circle_name.'</option>';                   
                }//End of foreach()
            }//End of if ?>
        </select><?php
    }//End of get_circles()
        
    function getID($length) {
        $refID = $this->generateID($length);
        while ($this->minoritycertificates_model->get_row(["service_data.appl_ref_no" => $refID])) {
            $refID = $this->generateID($length);
        }//End of while()
        return $refID;
    }//End of getID()

    public function generateID($length) {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "TEMP-MCC/".date('Y')."/".$number;
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

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        }//End of if else
    }//End of checkObjectId()

}//End of Registration
