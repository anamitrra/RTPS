<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Deed_marriage_appointment extends Rtps {

    private $serviceName = "Application form for Appointment - Deed Registration/Marriage";
    Private $serviceId = "NECERTIFICATE";

    public function __construct() {
        parent::__construct();
        $this->load->model('deed_marriage_appointment_model');
    }//End of __construct()

    public function index($rtps_trans_id=null) {
        $data=array("service_name"=>$this->serviceName);
        $filter = array("rtps_trans_id" => $rtps_trans_id, "service_id" => $this->serviceId, "status" => "draft");
        $data["dbrow"] = $this->deed_marriage_appointment_model->get_row($filter);
        $this->load->view('includes/frontend/header');
        $this->load->view('deed_marriage_appointment_reg',$data);
        $this->load->view('includes/frontend/footer');
    }//End of index()

    public function submit(){
        $objId = $this->input->post("obj_id");
        $rtps_trans_id = $this->input->post("rtps_trans_id");
        $submitMode = $this->input->post("submit_mode");
        $this->form_validation->set_rules('applicant_name', 'Name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('father_name', 'Father', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mother_name', 'Mother', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mobile_number', 'Mother', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('address', 'Adress', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('office_location', 'Submission location', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('appointment_type', 'Appointment Type', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('appointment_dt', 'Appointment Date', 'trim|required|xss_clean|strip_tags');

        if($this->input->post("appointment_type") == 'Deed') {
            $this->form_validation->set_rules('deed_type', 'Deep Type', 'trim|required|xss_clean|strip_tags');
        }
        else if($this->input->post("appointment_type") == 'Marriage') {
            $this->form_validation->set_rules('appl_ref_no', 'Application Ref. No.', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('appl_name', 'Applicant Name', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('bride_name', 'Bride Name', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('groom_name', 'Groom Name', 'trim|required|xss_clean|strip_tags');
        }
        
        $this->form_validation->set_rules('inputcaptcha', 'Captcha', 'required|exact_length[6]');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $obj_id = strlen($objId)?$objId:null;
            $this->index($rtps_trans_id);
        } else {
            $inputCaptcha = $this->input->post("inputcaptcha");
            $sessCaptcha = $this->session->userdata('captchaCode');
            
            $rtps_trans_id = $this->getID(7); 
            $sessionUser=$this->session->userdata();
            $inputs = [
                'rtps_trans_id' => $rtps_trans_id,   
                'app_ref_no' => uniqid(),
                'user_id' => $sessionUser['userId']->{'$id'},
                'service_name' => $this->serviceName,
                'service_id' => $this->serviceId,           
                'status' => 'DRAFT',
                 
                'applicant_name' => $this->input->post("applicant_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'father_name' => $this->input->post("father_name"),
                'mother_name' => $this->input->post("mother_name"),
                'mobile_number' => $this->input->post("mobile_number"),
                'email' => $this->input->post("email"),
                'address' => $this->input->post("address"),
                'district' => $this->input->post("district"),
                "office_location" => $this->input->post("office_location"),                
                'appointment_type' => $this->input->post("appointment_type"),
                'appointment_dt' => $this->input->post("appointment_dt"),
                'deed_type' => $this->input->post("deed_type"),
                'appl_ref_no' => $this->input->post("appl_ref_no"),
                'appl_name' => $this->input->post("appl_name"),
                'bride_name' => $this->input->post("bride_name"),
                'groom_name' => $this->input->post("groom_name"),


                'submit_mode' => $submitMode,
                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];            
            if($sessCaptcha !== $inputCaptcha) {
                $this->session->set_flashdata("error", "Captcha does not mached!. Please try again");
                $this->index();
            } else {      
                if(strlen($objId)) {
                    $this->deed_marriage_appointment_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                    $this->session->set_flashdata('success','Your application has been successfully submitted');
                    redirect('spservices/deed_marriage_appointment/preview/'.$rtps_trans_id);
                } else {
                    $insert=$this->deed_marriage_appointment_model->insert($inputs);
                    if($insert){
                        $objectId=$insert['_id']->{'$id'};
                        $this->session->set_flashdata('success','Your application has been successfully submitted');
                        redirect('spservices/deed_marriage_appointment/preview/'.$rtps_trans_id);
                        exit();
                    } else {
                        $this->session->set_flashdata('fail','Unable to submit data!!! Please try again.');
                        $this->index();
                    }//End of if else
                }//End of if else
            }
        }

    }//End of submit()

    function getID($length) {
        $rtps_trans_id = $this->generateID($length);
        while ($this->deed_marriage_appointment_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        }//End of while()
        return $rtps_trans_id;
    }//End of getID()

    public function generateID($length) {
        $date = date('ydm');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "WPTBC" . $date .$number;
        return $str;
    }//End of generateID()

    public function preview($rtps_trans_id=null) {     
        $filter = array("rtps_trans_id" => $rtps_trans_id);
        $dbRow = $this->deed_marriage_appointment_model->get_row($filter); //pre($dbRow);
        if(count((array)$dbRow)) {
            $data=array(
                "pageTitle" => "Application preview for Appointment - Deed Registration/Marriage Application",
                "dbrow"=>$dbRow
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('deed_marriage_appointment_preview',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$rtps_trans_id);
            // redirect('spservices/necertificate/');
        }//End of if else
    }//End of preview()
}
