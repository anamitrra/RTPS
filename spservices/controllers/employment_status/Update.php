<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Update extends Rtps {
    
    private $serviceName = "Employment Status service";
    private $serviceId = "EMPLUP";

    public function __construct() {
        parent::__construct();
        $this->load->model('employment_status/Employment_status_model');
        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper('smsprovider'); 
        $this->load->helper("cifileuploaddigilocker");      
        
        if($this->session->role){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
    }//End of __construct()

    public function index($objId=null) {
        //check_application_count_for_citizen();
        $data=array(
            "obj_id" => $objId,
            "service_name"=>$this->serviceName
        );
        $dbRow = $this->Employment_status_model->get_row(array('_id' => new ObjectId($objId)));

        if($dbRow) {
            $data["dbrow"] = $this->Employment_status_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        }//End of if else  
        //echo $data["form_status"];      
        //die;
        $this->load->view('includes/frontend/header');
        $this->load->view('employment_status/update',$data);
        $this->load->view('includes/frontend/footer');
    }//End of index()
    
    public function submit(){
     $objId = $this->input->post("obj_id");

            $this->form_validation->set_rules('reg_no', 'Registration No', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('reg_date', 'Registration Date', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('contact_number', 'Contact number', 'trim|integer|required|exact_length[10]|xss_clean|strip_tags');
            $this->form_validation->set_rules('employment', 'Type of employment', 'trim|required|xss_clean|strip_tags');

            $employment_type = $this->input->post('employment');

            if ($this->input->post('employment') != 'Self Employed') {
                $this->form_validation->set_rules('department_name', 'Department Name', 'trim|required|xss_clean|strip_tags');
                $this->form_validation->set_rules('designation', 'Designation', 'trim|required|xss_clean|strip_tags');

                $this->form_validation->set_rules('organization', 'Organization', 'required');
                if ($this->input->post('organization') == 'Others') {
                    $this->form_validation->set_rules('otherOrganization', 'Specify Other Organization', 'required');
                } else {
                    $this->form_validation->set_rules('otherOrganization', 'Specify Other Organization', '');
                }
                $this->form_validation->set_rules('skill_used', 'Skill used for the post', 'required');
                if ($this->input->post('skill_used') === 'Yes') {
                    $this->form_validation->set_rules('other_skill', 'Enter the Skill', 'required');
                } else {
                    $this->form_validation->set_rules('other_skill', 'Enter the Skill', '');
                }
                $this->form_validation->set_rules('notice_period', 'Are you in notice period', 'required');
                if ($this->input->post('notice_period') === 'Yes') {
                    $this->form_validation->set_rules('notice_period_value', 'Enter the notice period', 'required');
                } else {
                    $this->form_validation->set_rules('notice_period_value', 'Enter the notice period', '');
                }

                $this->form_validation->set_rules('joining_date', 'Joining Date', 'trim|required|xss_clean|strip_tags');

                $this->form_validation->set_rules('current_salary', 'Current Salary', 'trim|required|numeric');
                $_POST['natureOfSelfEmployment'] = '';
                $_POST['otherNatureOfSelfEmployment'] = '';

                $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
            } else {
                $_POST['department_name'] = '';
                $_POST['designation'] = '';
                $_POST['organization'] = '';
                $_POST['otherOrganization'] = '';
                $_POST['skill_used'] = '';
                $_POST['other_skill'] = '';
                $_POST['notice_period'] = '';
                $_POST['notice_period_value'] = '';
                $_POST['joining_date'] = '';
                $this->form_validation->set_rules('natureOfSelfEmployment', 'Nature of Self-Employment', 'required');
                if ($this->input->post('natureOfSelfEmployment') === 'Other') {
                    $this->form_validation->set_rules('otherNatureOfSelfEmployment', 'Description of Self Employment', 'required');
                }
            }
        //    $this->form_validation->set_rules('mobile_verify_status', 'Mobile not verified', 'trim|required|greater_than[0]|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) { 
             $str=validation_errors();
            
            if (strpos($str, "The Mobile not verified") !== false) {
                    $this->session->set_flashdata('error', "Mobile number not verfied");
            } else {
                    $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            }
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
        } 
        else {
             
           $reg_no = $this->input->post("reg_no");
           $reg_date = $this->input->post("reg_date");
           $dob = $this->input->post("dob");
           $ack_data=(object) array();
           //1. Check in mis.applications 
           $ee_record = $this->Employment_status_model->check_record_mis($reg_no,$reg_date,$dob);
           if (empty($ee_record)) { 
           // 2. check in sp applications 
                $sp_record = $this->Employment_status_model->check_record_iservices($reg_no, $reg_date, $dob);
                if (empty($sp_record)) {
                    $this->session->set_flashdata('error', 'Record not found. Please try again.');
                    redirect('spservices/employment_status/Update/index/');
                } 
                else {
                    $objid = $sp_record[0]->_id->{'$id'};

                    if (isset($sp_record[0]->service_data) && isset($sp_record[0]->form_data)) {
                        $ack_data->ref_no = $sp_record[0]->service_data->appl_ref_no;
                        $ack_data->appl_name = $sp_record[0]->form_data->applicant_name;
                        $ack_data->mobile_number=$this->input->post("contact_number");

                        $insert_to_mis = array('value' => false);
                        $updated_data = $sp_record[0]->updated_data??array();
                        // Create the updated_data array
                        $updated_data[] = array(
                            'department_name' => $this->input->post("department_name"),
                            'designation' => $this->input->post("designation"),
                            'joining_date' => $this->input->post("joining_date"),
                            'current_salary' => $this->input->post("current_salary"),
                            'other_skill' => $this->input->post("other_skill"),
                            'skill_used' => $this->input->post("skill_used"),
                            'dob' => $this->input->post("dob"),
                            'employment' => $this->input->post("employment"),
                            'organization' => $this->input->post("organization"),
                            'otherOrganization' => $this->input->post("otherOrganization"),
                            'notice_period' => $this->input->post("notice_period"),
                            'notice_period_value' => $this->input->post("notice_period_value"),
                            'natureOfSelfEmployment' => $this->input->post("natureOfSelfEmployment"),
                            'otherNatureOfSelfEmployment' => $this->input->post("otherNatureOfSelfEmployment"),
                            "updated_by" => $this->input->post("contact_number"),
                            "updated_at" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        );
                    
                        $data = array(
                                'insert_to_mis' => $insert_to_mis,
                                'updated_data' => $updated_data
                        );
                        $this->Employment_status_model->update_Employment_Status(['_id' => new ObjectId($objid)],array(
                            'form_data.current_employment_status'=>$this->input->post("employment")));

                        $this->Employment_status_model->update_where_iservices(['_id' => new ObjectId($objid)], $data);

                        log_response("EMPLUP",$ack_data,"employment_status_log");
                        
                        //Sending submission SMS
                        $nowTime = date('Y-m-d H:i:s');
                        $sms = array(
                                    "mobile" => (int)$ack_data->mobile_number,
                                    "applicant_name" => $ack_data->appl_name,
                                    "service_name" => 'Employment Status Service',
                                    "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                                    "app_ref_no" => $ack_data->ref_no
                        );
                        sms_provider("submission", $sms);

                        redirect('spservices/employment-status/acknowledgementsp/' . $objid);
                    }
            }}//end for SP
            else {
                    $objid=$ee_record[0]->_id->{'$id'};
                    $ack_data->ref_no=$ee_record[0]->initiated_data->appl_ref_no;
                    $ack_data->appl_name=$ee_record[0]->initiated_data->attribute_details->applicant_name;
                    $ack_data->mobile_number=$this->input->post("contact_number");

                    if($objid!=null) {  
                        $updated_data = $ee_record[0]->updated_data??array();             
                        $updated_data[] = array(
                            'department_name' => $this->input->post("department_name"),
                            'designation' => $this->input->post("designation"),
                            'joining_date' => $this->input->post("joining_date"),
                            'current_salary' => $this->input->post("current_salary"),
                            'other_skill' => $this->input->post("other_skill"),
                            'skill_used' => $this->input->post("skill_used"),
                            'dob'=>$this->input->post("dob"),
                            'employment'=>$this->input->post("employment"),
                            'organization'=>$this->input->post("organization"),
                            'otherOrganization'=>$this->input->post("otherOrganization"),
                            'notice_period'=>$this->input->post("notice_period"),
                            'notice_period_value'=>$this->input->post("notice_period_value"),
                            'natureOfSelfEmployment'=>$this->input->post("natureOfSelfEmployment"),
                            'otherNatureOfSelfEmployment'=>$this->input->post("otherNatureOfSelfEmployment"),
                            "updated_by" => $this->input->post("contact_number"),
                            "updated_at" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        );
                        
                        $data = array(
                            'updated_data' => $updated_data
                        );
                        $this->Employment_status_model->updateEmploymentStatus(
                                            ['_id' => new ObjectId($objid)],array(
                                            'initiated_data.attribute_details.current_employment_status'=>$this->input->post("employment")));
                                            
                        $this->Employment_status_model->update_where_mis(['_id' => new ObjectId($objid)], $data);  

                        if (true) {
                                log_response("EMPLUP",$ack_data,"employment_status_log");
                                //Sending submission SMS
                                $nowTime = date('Y-m-d H:i:s');
                                $sms = array(
                                    "mobile" => (int)$ack_data->mobile_number,
                                    "applicant_name" => $ack_data->appl_name,
                                    "service_name" => 'Employment Status Service',
                                    "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                                    "app_ref_no" => $ack_data->ref_no
                                );
                                sms_provider("submission", $sms);
                                redirect('spservices/employment-status/acknowledgement/' . $objid);
                        } else {
                                $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                                $this->index();
                        }
                    }//End of if else
                }
        }

    }//end of submit

    public function acknowledgement($objId = null)
    {
        $dbRow = $this->Employment_status_model->get_row_mis(array('_id' => new ObjectId($objId)));
        $ack_data=(object) array();
        $ack_data->ref_no=$dbRow->initiated_data->appl_ref_no;
        $ack_data->appl_name=$dbRow->initiated_data->attribute_details->applicant_name;
        if(isset($dbRow->execution_data[0]->official_form_details->registration_no)){
            $ack_data->appl_reg_no=$dbRow->execution_data[0]->official_form_details->registration_no;
        }
        else{
            $ack_data->appl_reg_no=$dbRow->initiated_data->attribute_details->registration_no;
        }
        $size = count($dbRow->updated_data);
        $ack_data->appl_date=$dbRow->updated_data[$size-1]->updated_at;

        $this->load->view('includes/frontend/header');
        $this->load->view('employment_status/ack',array('ack_data' => $ack_data,'dbRow'=>$dbRow));
        $this->load->view('includes/frontend/footer');
    }

    public function acknowledgementsp($objId = null)
    {
        $dbRow = $this->Employment_status_model->get_row(array('_id' => new ObjectId($objId)));

        // pre($dbRow);
        $ack_data=(object) array();
        $ack_data->ref_no=$dbRow->service_data->appl_ref_no;
        $ack_data->appl_name=$dbRow->form_data->applicant_name;
        $ack_data->appl_reg_no=$dbRow->form_data->registration_no;

        $size = count($dbRow->updated_data);
        $ack_data->appl_date=$dbRow->updated_data[$size-1]->updated_at;
        $this->load->view('includes/frontend/header');
        $this->load->view('employment_status/ack',array('ack_data' => $ack_data,'dbRow'=>$dbRow));
        $this->load->view('includes/frontend/footer');
    }
}