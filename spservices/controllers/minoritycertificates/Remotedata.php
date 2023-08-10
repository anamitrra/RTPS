<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Remotedata extends Frontend {
    
    private $serviceName = "Application for Minority Community Certificate";
    private $serviceId = "MCC";

    public function __construct() {
        parent::__construct();
        $this->load->model('minoritycertificates/registrations_model');
    }//End of __construct()
    
    public function index() {
        echo "Wroking fine...";
    }//End of index()
    
    public function postrequest(){
        //$testPdf = base64_encode(file_get_contents(FCPATH.'storage/1MB.pdf')); die($testPdf);
        $applicant_name = $this->input->post("applicant_name");
        if(strlen($applicant_name)) {
            $id_proof = $this->input->post("id_proof");
            $address_proof = $this->input->post("address_proof");
            $age_proof = $this->input->post("age_proof");
            $passport_photo = $this->input->post("passport_photo");

            $rtps_trans_id = $this->getID(7);
            $data = array(
                'rtps_trans_id' => $rtps_trans_id,
                'applied_user_type' => '',
                'applied_user_id' => '',
                'service_name' => $this->serviceName,
                'service_id' => $this->serviceId,           
                'status' => "SUBMITTED",

                'aadhaar_verify_status' => $this->input->post("aadhaar_verify_status"),
                'mobile_verify_status' => $this->input->post("mobile_verify_status"),
                'applicant_name' => $applicant_name,
                'father_name' => $this->input->post("father_name"),
                'mother_name' => $this->input->post("mother_name"),
                'contact_number' => $this->input->post("contact_number"),
                'emailid' => $this->input->post("emailid"),
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
                'pa_district_name' => $this->input->post("pa_district_name"),
                'pa_circle' => $this->input->post("pa_circle"),

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

                'id_proof_type' => $this->input->post("id_proof_type"),
                'address_proof_type' => $this->input->post("address_proof_type"),
                'age_proof_type' => $this->input->post("age_proof_type"),
                'passport_photo_type' => $this->input->post("passport_photo_type"),

                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            );

            $dirPath = 'storage/'.$this->serviceId.'/';
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
                file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for MC only</body></html>');
            }
            if(strlen($id_proof)) {
                $fileName = str_replace('/', '-', $rtps_trans_id).'-idProof.pdf';
                $filePath = $dirPath.$fileName;
                file_put_contents(FCPATH.$filePath, base64_decode($id_proof));
                $data['id_proof'] = $filePath;
                //echo '<a href="'.base_url($filePath).'" class="btn btn-success" target="_blank">View File</a>'; die;
            }
            if(strlen($address_proof)) {
                $fileName = str_replace('/', '-', $rtps_trans_id).'-addressProof.pdf';
                $filePath = $dirPath.$fileName;
                file_put_contents(FCPATH.$filePath, base64_decode($address_proof));
                $data['address_proof'] = $filePath;
            }
            if(strlen($age_proof)) {
                $fileName = str_replace('/', '-', $rtps_trans_id).'-ageProof.pdf';
                $filePath = $dirPath.$fileName;
                file_put_contents(FCPATH.$filePath, base64_decode($address_proof));
                $data['age_proof'] = $filePath;
            }
            if(strlen($passport_photo)) {
                $fileName = str_replace('/', '-', $rtps_trans_id).'-passportPhoto.jpg';
                $filePath = $dirPath.$fileName;
                file_put_contents(FCPATH.$filePath, base64_decode($passport_photo));
                $data['passport_photo'] = $filePath;
            }

            $insert=$this->registrations_model->insert($data);
            if($insert){
                $objectId=$insert['_id']->{'$id'};
                $resPost = array(
                    'rtps_trans_id' => $rtps_trans_id, 
                    'obj_id' => $objectId, 
                    'status' => true, 
                    'message' => 'Data has been submitted successfully'
                );
            } else {
                $resPost = array('status' => false, 'message' => 'Unable to submit data!!! Please try again.');
            }//End of if else
        } else {            
            $resPost = array('status' => false, 'message' => 'Data cannot be empty!!! Please try again.');
        }//End of if else       
        $json_obj = json_encode($resPost);
        $this->output->set_content_type('application/json')->set_output($json_obj);     
    }//End of postrequest()
    
    function getID($length) {
        $rtps_trans_id = $this->generateID($length);
        while ($this->registrations_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        }//End of while()
        return $rtps_trans_id;
    }//End of getID()

    public function generateID($length) {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-MC/".date('Y')."/".$number;
        return $str;
    }//End of generateID()

}//End of Remotedata
