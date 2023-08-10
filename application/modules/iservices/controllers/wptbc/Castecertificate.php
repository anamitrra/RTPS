<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Castecertificate extends Rtps {

    public function __construct() {
        parent::__construct();
        $this->load->model('portals_model');
        $this->load->model('wptbc/castecertificates_model');
        $this->load->helper("cifileupload");
    }//End of __construct()

    public function index($rtps_trans_id=null) {
        $data=array("pageTitle" => "Application for Issuance Of Scheduled Caste Certificate");
        $filter = array("rtps_trans_id" => $rtps_trans_id, "portal_no" => 7, "status" => "draft");
        $data["dbrow"] = $this->castecertificates_model->get_row($filter);
        $this->load->view('includes/frontend/header');
        $this->load->view('wptbc/castecertificate_view',$data);
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
        $this->form_validation->set_rules('spouse_name', 'Mother', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('religion', 'Mother', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mobile_number', 'Mother', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('age', 'Mother', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('sub_caste', 'Mother', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('email', 'Mother', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('ancestor_name', 'Name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ancestor_address1', 'Address1', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ancestor_state', 'State', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ancestor_district', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ancestor_subdivision', 'Subdivision', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ancestor_circleoffice', 'Circle Office', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ancestor_mouza', 'Mouza', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ancestor_village', 'VIllage', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ancestor_ps', 'PS', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ancestor_po', 'PO', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ancestor_pin', 'PIN', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ancestor_relation', 'Relation', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ancestor_subcaste', 'SUb caste', 'trim|required|xss_clean|strip_tags');

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

        $this->form_validation->set_rules('ra_houseno', 'House no.', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ra_landmark', 'Landmark', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ra_state', 'State', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ra_district', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ra_subdivision', 'Sub division', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ra_revenuecircle', 'Revenue circle', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ra_mouza', 'Mouza', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ra_village', 'Village', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ra_ps', 'Police station', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ra_pincode', 'Pincode', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ra_po', 'Post office', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('forefather_occupation', 'Occupation', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('certificate_purpose', 'Purpose', 'trim|required|xss_clean|strip_tags');
        
        $this->form_validation->set_rules('inputcaptcha', 'Captcha', 'required|exact_length[6]');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $obj_id = strlen($objId)?$objId:null;
            $this->index($rtps_trans_id);
        } else {                               
            $applicantPhoto = cifileupload("applicant_photo");
            $applicant_photo = $applicantPhoto["upload_status"]?$applicantPhoto["uploaded_path"]:null;
            
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
                'service_name' => 'Application for Issuance Of Scheduled Caste Certificate',
                'portal_no' => '7',
                'service_id' => 'WPTBC-01',
                'service_code' => 'WPTBC',                        
                'external_service_id' => 'WPTBC-01',
                'status' => 'draft',
                 
                'applicant_name' => $this->input->post("applicant_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'father_name' => $this->input->post("father_name"),
                'mother_name' => $this->input->post("mother_name"),
                'spouse_name' => $this->input->post("spouse_name"),
                'religion' => $this->input->post("religion"),
                'mobile_number' => $this->input->post("mobile_number"),
                'age' => $this->input->post("age"),
                'sub_caste' => $this->input->post("sub_caste"),
                'email' => $this->input->post("email"),
                "applicant_photo" => $applicant_photo,
                
                'ancestor_name' => $this->input->post("ancestor_name"),
                'ancestor_address1' => $this->input->post("ancestor_address1"),
                'ancestor_address2' => $this->input->post("ancestor_address2"),
                'ancestor_state' => $this->input->post("ancestor_state"),
                'ancestor_district' => $this->input->post("ancestor_district"),
                'ancestor_subdivision' => $this->input->post("ancestor_subdivision"),
                'ancestor_circleoffice' => $this->input->post("ancestor_circleoffice"),
                'ancestor_mouza' => $this->input->post("ancestor_mouza"),
                'ancestor_village' => $this->input->post("ancestor_village"),
                'ancestor_ps' => $this->input->post("ancestor_ps"),
                'ancestor_po' => $this->input->post("ancestor_po"),
                'ancestor_pin' => $this->input->post("ancestor_pin"),
                'ancestor_relation' => $this->input->post("ancestor_relation"),
                'ancestor_subcaste' => $this->input->post("ancestor_subcaste"),
                
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
                
                'address_same' => $this->input->post("address_same"),
                'ra_houseno' => $this->input->post("ra_houseno"),
                'ra_landmark' => $this->input->post("ra_landmark"),
                'ra_state' => $this->input->post("ra_state"),
                'ra_district' => $this->input->post("ra_district"),
                'ra_subdivision' => $this->input->post("ra_subdivision"),
                'ra_revenuecircle' => $this->input->post("ra_revenuecircle"),
                'ra_mouza' => $this->input->post("ra_mouza"),
                'ra_village' => $this->input->post("ra_village"),
                'ra_ps' => $this->input->post("ra_ps"),
                'ra_pincode' => $this->input->post("ra_pincode"),
                'ra_po' => $this->input->post("ra_po"),
                
                
                'forefather_occupation' => $this->input->post("forefather_occupation"),
                'certificate_purpose' => $this->input->post("certificate_purpose"),
                'parent_voterlist' => $this->input->post("parent_voterlist"),
                'submit_mode' => $submitMode,
                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];            
            if($sessCaptcha !== $inputCaptcha) {
                $this->session->set_flashdata("error", "Captcha does not mached!. Please try again");
                $this->index();
            } else {                
                if(strlen($objId)) {
                    $this->castecertificates_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                    $this->session->set_flashdata('success','Your application has been successfully submitted');
                    redirect('iservices/wptbc/castecertificate/fileuploads/'.$objId);
                } else {
                    $insert=$this->castecertificates_model->insert($inputs);
                    if($insert){
                        $objectId=$insert['_id']->{'$id'};
                        $this->session->set_flashdata('success','Your application has been successfully submitted');
                        redirect('iservices/wptbc/castecertificate/fileuploads/'.$objectId);
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
        $dbRow = $this->castecertificates_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
                "pageTitle" => "Upload Attachments for Application for Issuance Of Scheduled Caste Certificate",
                "obj_id"=>$objId,
                "rtps_trans_id" => $dbRow->rtps_trans_id
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('wptbc/castecertificateuploads_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('iservices/wptbc/castecertificate/');
        }//End of if else
    }//End of fileuploads()
    
    public function submitfiles() {        
        $objId = $this->input->post("obj_id");
        $rtps_trans_id = $this->input->post("rtps_trans_id");
        $this->form_validation->set_rules('caste_certificate_type', 'Caste certificate', 'required');
        $this->form_validation->set_rules('gaonbura_report_type', 'Gaonbura report', 'required');
        $this->form_validation->set_rules('prc_type', 'PRC', 'required');
        $this->form_validation->set_rules('nrc_type', 'NRC', 'required');
        $this->form_validation->set_rules('ajp_type', 'AJP', 'required');
        $this->form_validation->set_rules('other_doc_type', 'Other', 'required');
        $this->form_validation->set_rules('soft_copy_type', 'Soft copy', 'required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->fileuploads($objId);
        } else {
            $casteCertificate = cifileupload("caste_certificate");
            $caste_certificate = $casteCertificate["upload_status"]?$casteCertificate["uploaded_path"]:null;

            $gaonburaReport = cifileupload("gaonbura_report");
            $gaonbura_report = $gaonburaReport["upload_status"]?$gaonburaReport["uploaded_path"]:null;

            $prcDoc = cifileupload("prc");
            $prc = $prcDoc["upload_status"]?$prcDoc["uploaded_path"]:null;

            $nrcDoc = cifileupload("nrc");
            $nrc = $nrcDoc["upload_status"]?$nrcDoc["uploaded_path"]:null;

            $ajpDoc = cifileupload("ajp");
            $ajp = $ajpDoc["upload_status"]?$ajpDoc["uploaded_path"]:null;

            $otherDoc = cifileupload("other_doc");
            $other_doc = $otherDoc["upload_status"]?$otherDoc["uploaded_path"]:null;

            $softCopy = cifileupload("soft_copy");
            $soft_copy = $softCopy["upload_status"]?$softCopy["uploaded_path"]:null;

            $data = array(
                'caste_certificate_type' => $this->input->post("caste_certificate_type"),
                "caste_certificate" => $caste_certificate,
                'gaonbura_report_type' => $this->input->post("gaonbura_report_type"),
                "gaonbura_report" => $gaonbura_report,
                'prc_type' => $this->input->post("prc_type"),
                "prc" => $prc,
                'nrc_type' => $this->input->post("nrc_type"),
                "nrc" => $nrc,
                'ajp_type' => $this->input->post("ajp_type"),
                "ajp" => $ajp,
                'other_doc_type' => $this->input->post("other_doc_type"),
                "other_doc" => $other_doc,
                'soft_copy_type' => $this->input->post("soft_copy_type"),
                "soft_copy" => $soft_copy
            );
            $this->castecertificates_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success','Your application has been successfully submitted');
            redirect('iservices/wptbc/castecertificate/preview/'.$rtps_trans_id);
        }//End of if else
    }//End of submitfiles()
    
    public function preview($rtps_trans_id=null) {     
        $filter = array("rtps_trans_id" => $rtps_trans_id);
        $dbRow = $this->castecertificates_model->get_row($filter); //pre($dbRow);
        if(count((array)$dbRow)) {
            $data=array(
                "pageTitle" => "Application preview for Issuance Of Scheduled Caste Certificate",
                "dbrow"=>$dbRow
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('wptbc/castecertificatepreview_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$rtps_trans_id);
            redirect('iservices/wptbc/castecertificate/');
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
        while ($this->castecertificates_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
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

}//End of Castecertificate
