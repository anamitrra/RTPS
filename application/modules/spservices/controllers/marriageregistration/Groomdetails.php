<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Groomdetails extends Rtps {

    private $serviceName = "Application for Marriage Registration";

    public function __construct() {
        parent::__construct();        
        $this->load->model('marriageregistration/countries_model');
        $this->load->model('marriageregistration/marriageregistrations_model');
        $this->load->model('marriageregistration/lac_model');      
        
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
                $this->load->view('marriageregistration/groomdetails_view', $data);
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
        $groom_disability = $this->input->post("groom_disability");
        $groom_present_country = $this->input->post("groom_present_country");
        $groom_permanent_country = $this->input->post("groom_permanent_country");
        $this->form_validation->set_rules('groom_prefix', 'Prefix', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('groom_first_name', 'First name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('groom_middle_name', 'Midle name', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('groom_last_name', 'Last name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('groom_father_prefix', 'Prefix', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_father_first_name', 'First name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_father_middle_name', 'Middle name', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_father_last_name', 'Last name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_mother_prefix', 'Prefix', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_mother_first_name', 'First name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_mother_middle_name', 'Middle name', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_mother_last_name', 'Last name', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('groom_status', 'Satus', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_occupation', 'Occupation', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_category', 'Category', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_dob', 'DOB', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_mobile_number', 'Mobile number', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_email_id', 'Email', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_disability', 'Disability', 'trim|required|xss_clean|strip_tags');
        if($groom_disability == 1) {
            $this->form_validation->set_rules('groom_disability_type', 'Type', 'trim|required|xss_clean|strip_tags');
        } else {
            $this->form_validation->set_rules('groom_disability_type', 'Type', 'trim|xss_clean|strip_tags');
        }//End of if else       
        
        $this->form_validation->set_rules('groom_present_country', 'Country', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_present_city', 'City', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_present_pin', 'PIN', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_present_period_years', 'Period', 'trim|required|xss_clean|strip_tags');
        if($groom_present_country === 'India') {
            $this->form_validation->set_rules('groom_present_state', 'State', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('groom_present_district', 'District', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('groom_present_ps', 'Police station', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('groom_present_po', 'Post Office', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('groom_lac', 'LAC', 'trim|xss_clean|strip_tags');
        } else {
            $this->form_validation->set_rules('groom_present_state_foreign', 'State', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('groom_present_address1', 'Address1', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('groom_present_address2', 'Address2', 'trim|required|xss_clean|strip_tags');            
        }//End of if else
        
        $this->form_validation->set_rules('groom_address_same', 'Is same address', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_permanent_country', 'Country', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_permanent_city', 'City', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('groom_permanent_pin', 'PIN', 'trim|required|xss_clean|strip_tags');
        if($groom_permanent_country === 'India') {
            $this->form_validation->set_rules('groom_permanent_state', 'State', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('groom_permanent_district', 'District', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('groom_permanent_ps', 'Police station', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('groom_permanent_po', 'Post Office', 'trim|required|xss_clean|strip_tags');
        } else {
            $this->form_validation->set_rules('groom_permanent_state_foreign', 'State', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('groom_permanent_address1', 'Address1', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('groom_permanent_address2', 'Address2', 'trim|required|xss_clean|strip_tags');            
        }//End of if else

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->index($objId);
        } else {

            $groom_child_first_name = $this->input->post("groom_child_first_name");
            $groom_child_middle_name = $this->input->post("groom_child_middle_name");
            $groom_child_last_name = $this->input->post("groom_child_last_name");
            $groom_child_dob = $this->input->post("groom_child_dob");
            $groom_child_address = $this->input->post("groom_child_address");
            $groom_children = array();

            if (count($groom_child_first_name)) {
                foreach ($groom_child_first_name as $k => $groom_child_first_name) {
                    $groom_child = array(
                        "groom_child_first_name" => $groom_child_first_name,
                        "groom_child_middle_name" => $groom_child_middle_name[$k],
                        "groom_child_last_name" => $groom_child_last_name[$k],
                        "groom_child_dob" => (strlen($groom_child_dob[$k])==10)?date("Y-m-d", strtotime($groom_child_dob[$k])):'',
                        "groom_child_address" => $groom_child_address[$k]
                    );
                    $groom_children[] = $groom_child;
                }//End of foreach()        
            }//End of if

            $groom_dependent_first_name = $this->input->post("groom_dependent_first_name");
            $groom_dependent_middle_name = $this->input->post("groom_dependent_middle_name");
            $groom_dependent_last_name = $this->input->post("groom_dependent_last_name");
            $groom_dependent_dob = $this->input->post("groom_dependent_dob");
            $groom_dependent_address = $this->input->post("groom_dependent_address");
            $groom_dependents = array();

            if (count($groom_dependent_first_name)) {
                foreach ($groom_dependent_first_name as $k => $groom_dependent_first_name) {
                    $groom_dependent = array(
                        "groom_dependent_first_name" => $groom_dependent_first_name,
                        "groom_dependent_middle_name" => $groom_dependent_middle_name[$k],
                        "groom_dependent_last_name" => $groom_dependent_last_name[$k],
                        "groom_dependent_dob" => (strlen($groom_dependent_dob[$k])==10)?date("Y-m-d", strtotime($groom_dependent_dob[$k])):'',
                        "groom_dependent_address" => $groom_dependent_address[$k]
                    );
                    $groom_dependents[] = $groom_dependent;
                }//End of foreach()        
            }//End of if
            
            if($groom_disability == 1) {
                $groom_disability_type = $this->input->post("groom_disability_type");
            } else {
                $groom_disability_type = '';
            }//End of if else
        
            if($groom_present_country === 'India') {
                $groom_present_state = $this->input->post("groom_present_state");
                $groom_present_state_name = $this->input->post("groom_present_state_name");
                $groom_present_district = $this->input->post("groom_present_district");
                $groom_present_ps = $this->input->post("groom_present_ps");
                $groom_present_po = $this->input->post("groom_present_po");
                $groom_lac = $this->input->post("groom_lac");                
                $groom_present_state_foreign = '';
                $groom_present_address1 = '';
                $groom_present_address2 = '';
            } else {
                $groom_present_state = '';
                $groom_present_state_name = '';
                $groom_present_district = '';
                $groom_present_ps = '';
                $groom_present_po = '';
                $groom_lac = '';
                $groom_present_state_foreign = $this->input->post("groom_present_state_foreign");
                $groom_present_address1 = $this->input->post("groom_present_address1");
                $groom_present_address2 = $this->input->post("groom_present_address2");           
            }//End of if else
            
            if($groom_permanent_country === 'India') {
                $groom_permanent_state = $this->input->post("groom_permanent_state");
                $groom_permanent_state_name = $this->input->post("groom_permanent_state_name");
                $groom_permanent_district = $this->input->post("groom_permanent_district");
                $groom_permanent_ps = $this->input->post("groom_permanent_ps");
                $groom_permanent_po = $this->input->post("groom_permanent_po");
                $groom_lac = $this->input->post("groom_lac");                
                $groom_permanent_state_foreign = '';
                $groom_permanent_address1 = '';
                $groom_permanent_address2 = '';
            } else {
                $groom_permanent_state = '';
                $groom_permanent_state_name = '';
                $groom_permanent_district = '';
                $groom_permanent_ps = '';
                $groom_permanent_po = '';
                $groom_lac = '';
                $groom_permanent_state_foreign = $this->input->post("groom_permanent_state_foreign");
                $groom_permanent_address1 = $this->input->post("groom_permanent_address1");
                $groom_permanent_address2 = $this->input->post("groom_permanent_address2");           
            }//End of if else

            $inputs = [
                'groom_prefix' => $this->input->post("groom_prefix"),
                'groom_first_name' => $this->input->post("groom_first_name"),
                'groom_middle_name' => $this->input->post("groom_middle_name"),
                'groom_last_name' => $this->input->post("groom_last_name"),
                'groom_father_prefix' => $this->input->post("groom_father_prefix"),
                'groom_father_first_name' => $this->input->post("groom_father_first_name"),
                'groom_father_middle_name' => $this->input->post("groom_father_middle_name"),
                'groom_father_last_name' => $this->input->post("groom_father_last_name"),
                'groom_mother_prefix' => $this->input->post("groom_mother_prefix"),
                'groom_mother_first_name' => $this->input->post("groom_mother_first_name"),
                'groom_mother_middle_name' => $this->input->post("groom_mother_middle_name"),
                'groom_mother_last_name' => $this->input->post("groom_mother_last_name"),
                'groom_status' => $this->input->post("groom_status"),
                'groom_occupation' => $this->input->post("groom_occupation"),
                'groom_category' => $this->input->post("groom_category"),
                'groom_dob' => $this->input->post("groom_dob"),
                'groom_mobile_number' => $this->input->post("groom_mobile_number"),
                'groom_email_id' => $this->input->post("groom_email_id"),
                'groom_disability' => $groom_disability,
                'groom_disability_type' => $groom_disability_type,
                'groom_children' => $groom_children,
                'groom_dependents' => $groom_dependents,
                'groom_dependent_income' => $this->input->post("groom_dependent_income"),
                'groom_address_same' => $this->input->post("groom_address_same"),
                'groom_present_country' => $groom_present_country,
                'groom_present_state' => $groom_present_state,
                'groom_present_state_name' => $groom_present_state_name,
                'groom_present_state_foreign' => $groom_present_state_foreign,
                'groom_present_district' => $groom_present_district,
                'groom_present_city' => $this->input->post("groom_present_city"),
                'groom_present_ps' => $groom_present_ps,
                'groom_present_po' => $groom_present_po,
                'groom_present_address1' => $groom_present_address1,
                'groom_present_address2' => $groom_present_address2,
                'groom_present_pin' => $this->input->post("groom_present_pin"),
                'groom_lac' => json_decode(html_entity_decode($this->input->post("groom_lac"))),
                'groom_present_period_years' => $this->input->post("groom_present_period_years"),
                'groom_present_period_months' => $this->input->post("groom_present_period_months"),
                'groom_permanent_country' => $groom_permanent_country,
                'groom_permanent_state' => $groom_permanent_state,
                'groom_permanent_state_name' => $groom_permanent_state_name,
                'groom_permanent_state_foreign' => $groom_permanent_state_foreign,
                'groom_permanent_district' => $groom_permanent_district,
                'groom_permanent_city' => $this->input->post("groom_permanent_city"),
                'groom_permanent_ps' => $groom_permanent_ps,
                'groom_permanent_po' => $groom_permanent_po,
                'groom_permanent_address1' => $groom_permanent_address1,
                'groom_permanent_address2' => $groom_permanent_address2,
                'groom_permanent_pin' => $this->input->post("groom_permanent_pin")
            ];
            $this->marriageregistrations_model->update_where(['_id' => new ObjectId($objId)], $inputs);
            $this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/marriageregistration/fileuploads/index/' . $objId);
        }//End of if else
    }//End of submit()

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Groomdetails