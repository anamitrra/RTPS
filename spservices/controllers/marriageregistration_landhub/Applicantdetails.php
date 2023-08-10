<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Applicantdetails extends Rtps {

    private $serviceName = "Application for Marriage Registration";
    Private $serviceId = "MARRIAGE_REGISTRATION";

    public function __construct() {
        parent::__construct();
        $this->load->model('marriageregistration/marriageregistrations_model');
        $this->load->model('sros_model');       
        
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
    }//End of __construct()

    public function index($objId = null) {
        //check_application_count_for_citizen();
        $data = array("service_name" => $this->serviceName);
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
            $data["dbrow"] = $this->marriageregistrations_model->get_row($dbFilter);
        } else {
            $data["dbrow"] = false;
        }//End of if else    
        $data["user_type"] = $this->slug;
        $data["sro_dist_list"] = $this->sros_model->sro_dist_list();
        $this->load->view('includes/frontend/header');
        $this->load->view('marriageregistration_landhub/applicantdetails_view', $data);
        $this->load->view('includes/frontend/footer');
    }//End of index()

    public function submit() {
        $objId = $this->input->post("obj_id");
        $rtps_trans_id = $this->input->post("rtps_trans_id");
        $submitMode = $this->input->post("submit_mode");
        
        $marriageType = json_decode(html_entity_decode($this->input->post("marriage_type")));
        $mt_id = $marriageType->mt_id??'';
        
        $this->form_validation->set_rules('marriage_type', 'Type', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('marriage_act', 'Act', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('applicant_prefix', 'Prefix', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('applicant_first_name', 'First name', 'trim|required|alpha_numeric_spaces|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('applicant_middle_name', 'Middle name', 'trim|alpha_numeric_spaces|xss_clean|strip_tags');
        $this->form_validation->set_rules('applicant_last_name', 'Last name', 'trim|required|alpha_numeric_spaces|xss_clean|strip_tags');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('applicant_mobile_number', 'Mobile', 'trim|required|exact_length[10]|numeric|xss_clean|strip_tags');
        $this->form_validation->set_rules('applicant_email_id', 'Email', 'trim|valid_email|xss_clean|strip_tags');

        $this->form_validation->set_rules('office_district', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('sro_code', 'Office', 'trim|required|xss_clean|strip_tags');

        if ($mt_id == 2) {
            $this->form_validation->set_rules('relationship_before', 'Relationship before marriage', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('ceremony_date', 'Marriage ceremony date', 'trim|required|xss_clean|strip_tags');
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->index($objId);
        } else {
            $sessionUser = $this->session->userdata();
            if($this->slug === "CSC"){                
                $apply_by = $sessionUser['userId'];
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            }//End of if else  
            
            if ($mt_id == 2) {
                $relationship_before = $this->input->post("relationship_before");
                $ceremony_date = $this->input->post("ceremony_date");
            } else {
                $relationship_before = "";
                $ceremony_date = "";
            }//End of if else  
                
            $data = array(
                'user_type' => $this->slug,
                'applied_by' => $apply_by,
                'marriage_type' => $marriageType,
                'marriage_act' => json_decode(html_entity_decode($this->input->post("marriage_act"))),
                'applicant_prefix' => $this->input->post("applicant_prefix"),
                'applicant_first_name' => $this->input->post("applicant_first_name"),
                'applicant_middle_name' => $this->input->post("applicant_middle_name"),
                'applicant_last_name' => $this->input->post("applicant_last_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'applicant_mobile_number' => $this->input->post("applicant_mobile_number"),
                'applicant_email_id' => $this->input->post("applicant_email_id"),
                'office_district' => $this->input->post("office_district"),
                'district_name' => $this->input->post("district_name"),
                'sro_code' => $this->input->post("sro_code"),
                'office_name' => $this->input->post("office_name"),
                'relationship_before' => $relationship_before,
                'ceremony_date' => $ceremony_date
            );
            
            if (strlen($objId)) {
                $this->marriageregistrations_model->update_where(['_id' => new ObjectId($objId)], $data);
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/marriageregistration_landhub/bridedetails/index/' . $objId);
            } else {
                $data['rtps_trans_id'] = $this->getID(7);
                $data['service_name'] = $this->serviceName;
                $data['service_id'] = $this->serviceId;
                $data['created_at'] = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
                $data['status'] = 'DRAFT';
                
                $insert = $this->marriageregistrations_model->insert($data);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    if ($submitMode === 'SAVE_NEXT') {
                        $this->session->set_flashdata('success', 'Your application has been successfully saved');
                        redirect('spservices/marriageregistration_landhub/bridedetails/index/' . $objectId);
                    } else {
                        $this->session->set_flashdata('success', 'Your application has been successfully drafted');
                        redirect('spservices/marriageregistration_landhub/applicantdetails/index/' . $objectId);
                    }//End of if else
                } else {
                    $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                    $this->index();
                }//End of if else
            }//End of if else
        }//End of if else
    }//End of submit()

    function getID($length) {
        $rtps_trans_id = $this->generateID($length);
        while ($this->marriageregistrations_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        }//End of while()
        return $rtps_trans_id;
    }//End of getID()

    public function generateID($length) {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "TEMP-MRG/" . date('Y') . "/" . $number;
        return $str;
    }//End of generateID()

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Applicantdetails