<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Fileuploads extends Rtps {

    private $serviceName = "Application for Marriage Registration";

    public function __construct() {
        parent::__construct();
        $this->load->model('marriageregistration/marriageregistrations_model');
        $this->load->helper("cifileupload");
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
    }//End of __construct()

    public function index($objId = null) {
        if ($this->checkObjectId($objId)) {
            if($this->slug === "CSC"){                
                $apply_by = $this->session->userId;
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            }//End of if else
            $dbFilter = array(
                '$and' => array(
                    array(
                        '_id'=>new ObjectId($objId),
                        'applied_by' => $apply_by,
                        '$or'=>array(
                            array("status" => "DRAFT"),
                            array("status" => "QS")
                        )
                    )
                )
            );
            $dbRow = $this->marriageregistrations_model->get_row($dbFilter);
            if ($dbRow) {
                $data = array("service_name" => $this->serviceName, "dbrow"=>$dbRow);
                $this->load->view('includes/frontend/header');
                $this->load->view('marriageregistration/fileuploads_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
                redirect('spservices/marriageregistration/');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/marriageregistration/');
        }//End of if else
    }//End of index()

    public function submit() {
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules('bride_idproof_type', 'ID Proof', 'required');
        $this->form_validation->set_rules('groom_idproof_type', 'ID Proof', 'required');
        $this->form_validation->set_rules('bride_ageproof_type', 'Age Proof', 'required');
        $this->form_validation->set_rules('marriage_notice_type', 'Marriage notice', 'required');
        $this->form_validation->set_rules('groom_ageproof_type', 'Age Proof', 'required');
        $this->form_validation->set_rules('bride_presentaddressproof_type', 'Present Address', 'required');
        $this->form_validation->set_rules('bride_permanentaddressproof_type', 'Permanent Address', 'required');
        $this->form_validation->set_rules('groom_presentaddressproof_type', 'Present Address', 'required');
        $this->form_validation->set_rules('groom_permanentaddressproof_type', 'Permanent Address', 'required');
        $this->form_validation->set_rules('bride_sign_type', 'Bride Sign', 'required');
        $this->form_validation->set_rules('groom_sign_type', 'Bridegroom Sign', 'required');
        $this->form_validation->set_rules('declaration_certificate_type', 'Declaration Certificate', 'required');
        //$this->form_validation->set_rules('marriage_card_type', 'IDProof', 'required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        $brideIdproof = cifileupload("bride_idproof");
        if($brideIdproof["upload_status"]) {
            $bride_idproof = $brideIdproof["uploaded_path"];
        } else {
            $bride_idproof = $this->input->post("bride_idproof_old");//$brideIdproof["error"];//die($brideIdproof["error"]);
        }//End of if else

        $groomIdproof = cifileupload("groom_idproof");
        if($groomIdproof["upload_status"]) {
            $groom_idproof = $groomIdproof["uploaded_path"];
        } else {
            $groom_idproof = $this->input->post("groom_idproof_old");//$groomIdproof["error"];
        }//End of if else

        $brideAgeproof = cifileupload("bride_ageproof");
        if($brideAgeproof["upload_status"]) {
            $bride_ageproof = $brideAgeproof["uploaded_path"];
        } else {
            $bride_ageproof = $this->input->post("bride_ageproof_old");//$brideAgeproof["error"];
        }//End of if else

        $marriageNotice = cifileupload("marriage_notice");
        if($marriageNotice["upload_status"]) {
            $marriage_notice = $marriageNotice["uploaded_path"];
        } else {
            $marriage_notice = $this->input->post("marriage_notice_old");//$marriageNotice["error"];
        }//End of if else

        $groomAgeproof = cifileupload("groom_ageproof");
        if($groomAgeproof["upload_status"]) {
            $groom_ageproof = $groomAgeproof["uploaded_path"];
        } else {
            $groom_ageproof = $this->input->post("groom_ageproof_old");//$groomAgeproof["error"];
        }//End of if else

        $bridePresentaddressproof = cifileupload("bride_presentaddressproof");
        if($bridePresentaddressproof["upload_status"]) {
            $bride_presentaddressproof = $bridePresentaddressproof["uploaded_path"];
        } else {
            $bride_presentaddressproof = $this->input->post("bride_presentaddressproof_old");//$bridePresentaddressproof["error"];
        }//End of if else

        $bridePermanentaddressproof = cifileupload("bride_permanentaddressproof");
        if($bridePermanentaddressproof["upload_status"]) {
            $bride_permanentaddressproof = $bridePermanentaddressproof["uploaded_path"];
        } else {
            $bride_permanentaddressproof = $this->input->post("bride_permanentaddressproof_old");//$bridePermanentaddressproof["error"];
        }//End of if else

        $groomPresentaddressproof = cifileupload("groom_presentaddressproof");
        if($groomPresentaddressproof["upload_status"]) {
            $groom_presentaddressproof = $groomPresentaddressproof["uploaded_path"];
        } else {
            $groom_presentaddressproof = $this->input->post("groom_presentaddressproof_old");//$groomPresentaddressproof["error"];
        }//End of if else

        $groomPermanentaddressproof = cifileupload("groom_permanentaddressproof");
        if($groomPermanentaddressproof["upload_status"]) {
            $groom_permanentaddressproof = $groomPermanentaddressproof["uploaded_path"];
        } else {
            $groom_permanentaddressproof = $this->input->post("groom_permanentaddressproof_old");//$groomPermanentaddressproof["error"];
        }//End of if else

        $brideSign = cifileupload("bride_sign");
        if($brideSign["upload_status"]) {
            $bride_sign = $brideSign["uploaded_path"];
        } else {
            $bride_sign = $this->input->post("bride_sign_old");//$brideSign["error"];
        }//End of if else

        $groomSign = cifileupload("groom_sign");
        if($groomSign["upload_status"]) {
            $groom_sign = $groomSign["uploaded_path"];
        } else {
            $groom_sign = $this->input->post("groom_sign_old");//$groomSign["error"];
        }//End of if else

        $declarationCertificate = cifileupload("declaration_certificate");
        if($declarationCertificate["upload_status"]) {
            $declaration_certificate = $declarationCertificate["uploaded_path"];
        } else {
            $declaration_certificate = $this->input->post("declaration_certificate_old");//$declarationCertificate["error"];
        }//End of if else

        $marriageCard = cifileupload("marriage_card");
        if($marriageCard["upload_status"]) {
            $marriage_card = $marriageCard["uploaded_path"];
        } else {
            $marriage_card = $this->input->post("marriage_card_old");//$marriageCard["error"];
        }//End of if else

        $softCopy = cifileupload("soft_copy");
        if($softCopy["upload_status"]) {
            $soft_copy = $softCopy["uploaded_path"];
        } else {
            $soft_copy = $this->input->post("soft_copy_old");//$softCopy["error"];
        }//End of if else
            
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->index($objId);
        } elseif((strlen($bride_idproof) < 10) || (strlen($groom_idproof) < 10)){
            $this->session->set_flashdata('error','ID Proof(s) cannot be empty ');
            $this->index($objId);
        } else {            
            $uploadedFiles = array(
                "bride_idproof_old" => $bride_idproof,
                "groom_idproof_old" => $groom_idproof,
                "bride_ageproof_old" => $bride_ageproof,
                "marriage_notice_old" => $marriage_notice,
                "groom_ageproof_old" => $groom_ageproof,
                "bride_presentaddressproof_old" => $bride_presentaddressproof,
                "bride_permanentaddressproof_old" => $bride_permanentaddressproof,
                "groom_presentaddressproof_old" => $groom_presentaddressproof,
                "groom_permanentaddressproof_old" => $groom_permanentaddressproof,
                "bride_sign_old" => $bride_sign,
                "groom_sign_old" => $groom_sign,
                "declaration_certificate_old" => $declaration_certificate,
                "marriage_card_old" => $marriage_card,
                "soft_copy_old" => $soft_copy
            );
            $this->session->set_flashdata('uploaded_files', $uploadedFiles);

            $data = array(
                'bride_idproof_type' => $this->input->post("bride_idproof_type"),
                "bride_idproof" => $bride_idproof,
                'groom_idproof_type' => $this->input->post("groom_idproof_type"),
                "groom_idproof" => $groom_idproof,
                'bride_ageproof_type' => $this->input->post("bride_ageproof_type"),
                "bride_ageproof" => $bride_ageproof,
                'marriage_notice_type' => $this->input->post("marriage_notice_type"),
                "marriage_notice" => $marriage_notice,
                'groom_ageproof_type' => $this->input->post("groom_ageproof_type"),
                "groom_ageproof" => $groom_ageproof,
                'bride_presentaddressproof_type' => $this->input->post("bride_presentaddressproof_type"),
                "bride_presentaddressproof" => $bride_presentaddressproof,
                'bride_permanentaddressproof_type' => $this->input->post("bride_permanentaddressproof_type"),
                "bride_permanentaddressproof" => $bride_permanentaddressproof,
                'groom_presentaddressproof_type' => $this->input->post("groom_presentaddressproof_type"),
                "groom_presentaddressproof" => $groom_presentaddressproof,
                'groom_permanentaddressproof_type' => $this->input->post("groom_permanentaddressproof_type"),
                "groom_permanentaddressproof" => $groom_permanentaddressproof,
                'bride_sign_type' => $this->input->post("bride_sign_type"),
                "bride_sign" => $bride_sign,
                'groom_sign_type' => $this->input->post("groom_sign_type"),
                "groom_sign" => $groom_sign,
                'declaration_certificate_type' => $this->input->post("declaration_certificate_type"),
                "declaration_certificate" => $declaration_certificate,
                'marriage_card_type' => $this->input->post("marriage_card_type"),
                "marriage_card" => $marriage_card,
                'soft_copy_type' => $this->input->post("soft_copy_type"),
                "soft_copy" => $soft_copy
            );
            $this->marriageregistrations_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/marriageregistration/preview/index/' . $objId);
        }//End of if else
    }//End of submit()

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Fileuploads
