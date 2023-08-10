<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Nclcertificate extends Rtps {

    public function __construct() {
        parent::__construct();
        $this->load->model('portals_model');
        $this->load->model('wptbc/nclcertificates_model');
        $this->load->helper("cifileupload");
    }//End of __construct()

    public function index($rtps_trans_id=null) {    
        $data=array("pageTitle" => "Application for Issuance Of Non Creamy Layer Certificate");
        $filter = array("rtps_trans_id" => $rtps_trans_id, "portal_no" => 8, "status" => "draft");
        $data["dbrow"] = $this->nclcertificates_model->get_row($filter);
        $this->load->view('includes/frontend/header');
        $this->load->view('wptbc/nclcertificate_view',$data);
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
        $this->form_validation->set_rules('spouse_name', 'Spouse name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('dob', 'DOB', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mobile_number', 'Mobile number', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('pa_houseno', 'House no.', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_landmark', 'Landmark', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_state', 'State', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_district', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_subdivision', 'Sub division', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_revenuecircle', 'Revenue circle', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_mouza', 'Mouza', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_village', 'Village', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_ps', 'Police station', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_pincode', 'Pincode', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_po', 'Post office', 'trim|required|xss_clean|strip_tags');
                
        $this->form_validation->set_rules("inputcaptcha", "Captcha", "required|exact_length[6]");
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
                //Service info
                'user_id' => $sessionUser['userId']->{'$id'},
                //'mobile' => $sessionUser['mobile'],
                'service_name' => 'Application for Issuance Of Non Creamy Layer Certificate',
                'portal_no' => '8',
                'service_id' => 'WPTBC-02',
                'service_code' => 'WPTBC', 
                'external_service_id' => 'WPTBC-02',
                'status' => 'draft',
                  
                'applicant_name' => $this->input->post("applicant_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'father_name' => $this->input->post("father_name"),
                'mother_name' => $this->input->post("mother_name"),
                'spouse_name' => $this->input->post("spouse_name"),
                'email' => $this->input->post("email"),
                'dob' => $this->input->post("dob"),
                'mobile_number' => $this->input->post("mobile_number"),
                
                'pa_houseno' => $this->input->post("pa_houseno"),
                'pa_landmark' => $this->input->post("pa_landmark"),
                'pa_state' => $this->input->post("pa_state"),
                'pa_district' => $this->input->post("pa_district"),
                'pa_subdivision' => $this->input->post("pa_subdivision"),
                'pa_revenuecircle' => $this->input->post("pa_revenuecircle"),
                'pa_mouza' => $this->input->post("pa_mouza"),
                'pa_village' => $this->input->post("pa_village"),
                'pa_ps' => $this->input->post("pa_ps"),
                'pa_pincode' => $this->input->post("pa_pincode"),
                'pa_po' => $this->input->post("pa_po"),                               
                
                'caste_name' => $this->input->post("caste_name"),
                'financial_status' => array(
                    'fsearning_sources' => $this->input->post("fsearning_sources"),
                    'organization_types' => $this->input->post("organization_types"),
                    'organization_names' => $this->input->post("organization_names"),
                    'fs_designations' => $this->input->post("fs_designations"),                           
                ),
                
                'income_status' => array(
                    'isearning_sources' => $this->input->post("isearning_sources"),
                    'annual_salary' => $this->input->post("annual_salary"),
                    'other_income' => $this->input->post("other_income"),
                    'total_income' => $this->input->post("total_income"),
                    'is_remarks' => $this->input->post("is_remarks"),
                ),
                    
                'submit_mode' => $submitMode,
                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];            
            if($sessCaptcha !== $inputCaptcha) {
                $this->session->set_flashdata("error", "Captcha does not mached!. Please try again");
                $this->index();
            } else {
                if(strlen($objId)) {
                    $this->nclcertificates_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                    $this->session->set_flashdata('success','Your application has been successfully submitted');
                    redirect('iservices/wptbc/nclcertificate/fileuploads/'.$objId);
                } else {
                    $insert=$this->nclcertificates_model->insert($inputs);
                    if($insert){
                        $objectId=$insert['_id']->{'$id'};
                        $this->session->set_flashdata('success','Your application has been successfully submitted');
                        redirect('iservices/wptbc/nclcertificate/fileuploads/'.$objectId);
                        exit();
                    } else {
                        $this->session->set_flashdata('fail','Unable to submit data!!! Please try again.');
                        $this->index();
                    }//End of if else
                }//End of if else
            }//End of if else 
        }//End of if else
    }//End of submit()
    
    public function fileuploads($objId=null) {
        $dbRow = $this->nclcertificates_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
                "pageTitle" => "Upload Attachments for Application for Issuance Of Non Creamy Layer Certificate",
                "obj_id"=>$objId,
                "rtps_trans_id" => $dbRow->rtps_trans_id
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('wptbc/nclcertificateuploads_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('iservices/wptbc/nclcertificate/');
        }//End of if else
    }//End of fileuploads()
    
    public function submitfiles() {        
        $objId = $this->input->post("obj_id");
        $rtps_trans_id = $this->input->post("rtps_trans_id");
        $this->form_validation->set_rules('residential_proof_type', 'Residential proof', 'required');
        $this->form_validation->set_rules('obc_type', 'OBC', 'required');
        $this->form_validation->set_rules('income_certificate_type', 'Income certificate', 'required');
        $this->form_validation->set_rules('other_doc_type', 'Other', 'required');
        $this->form_validation->set_rules('soft_copy_type', 'Soft copy', 'required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->fileuploads($objId);
        } else {
            $gaonburaReport = cifileupload("residential_proof");
            $residential_proof = $gaonburaReport["upload_status"]?$gaonburaReport["uploaded_path"]:null;

            $obcDoc = cifileupload("obc");
            $obc = $obcDoc["upload_status"]?$obcDoc["uploaded_path"]:null;

            $incomeCertificate = cifileupload("income_certificate");
            $income_certificate = $incomeCertificate["upload_status"]?$incomeCertificate["uploaded_path"]:null;

            $otherDoc = cifileupload("other_doc");
            $other_doc = $otherDoc["upload_status"]?$otherDoc["uploaded_path"]:null;

            $softCopy = cifileupload("soft_copy");
            $soft_copy = $softCopy["upload_status"]?$softCopy["uploaded_path"]:null;
            
            $data = array(
                'residential_proof_type' => $this->input->post("residential_proof_type"),
                "residential_proof" => $residential_proof,
                'obc_type' => $this->input->post("obc_type"),
                "obc" => $obc,
                'income_certificate_type' => $this->input->post("income_certificate_type"),
                "income_certificate" => $income_certificate,
                'other_doc_type' => $this->input->post("other_doc_type"),
                "other_doc" => $other_doc,
                'soft_copy_type' => $this->input->post("soft_copy_type"),
                "soft_copy" => $soft_copy
            );
            $this->nclcertificates_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success','Your application has been successfully submitted');
            redirect('iservices/wptbc/nclcertificate/preview/'.$rtps_trans_id);
        }//End of if else
    }//End of submitfiles()
    
    public function preview($rtps_trans_id=null) {
        $filter = array("rtps_trans_id" => $rtps_trans_id);
        $dbRow = $this->nclcertificates_model->get_row($filter);
        if(count((array)$dbRow)) {
            $data=array(
                "pageTitle" => "Application preview for Issuance Of Non Creamy Layer Certificate",
                "dbrow"=>$dbRow
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('wptbc/nclcertificatepreview_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$rtps_trans_id);
            redirect('iservices/wptbc/nclcertificate/');
        }//End of if else
    }//End of preview()
    
    function createcaptcha() {
        $captchaDir = "storage/captcha/";
        array_map('unlink', glob("$captchaDir*.jpg"));

        $this->load->helper('captcha');
        $config = array(
            'img_path' => './storage/captcha/',
            'img_url' => base_url('storage/captcha/'),
            'font_path' => APPPATH.'sys/fonts/texb.ttf',
            'img_width' => '150',
            'img_height' => 30,
            'expiration' => 7200,
            'word_length' => 6,
            'font_size' => 16,
            'img_id' => 'capimg',
            'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(94, 20, 38),
                'text' => array(0, 0, 0),
                'grid' => array(178, 184, 194)
            )
        );
        $captcha = create_captcha($config);        
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode', $captcha['word']);
        echo $captcha['image'];
    }//End of createcaptcha()
    
    function getID($length) {
        $rtps_trans_id = $this->generateID($length);
        while ($this->nclcertificates_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
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

}//End of Nclcertificate
