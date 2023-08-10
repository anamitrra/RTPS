<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Bridedetails extends Rtps {

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
                $this->load->view('marriageregistration_landhub/bridedetails_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
                redirect('spservices/marriageregistration_landhub/');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/marriageregistration_landhub/');
        }//End of if else
    }//End of index()

    public function submit() {
        $objId = $this->input->post("obj_id");        
        $bride_disability = $this->input->post("bride_disability");
        $bride_present_country = $this->input->post("bride_present_country");
        $bride_permanent_country = $this->input->post("bride_permanent_country");
        $this->form_validation->set_rules('bride_prefix', 'Prefix', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('bride_first_name', 'First name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('bride_middle_name', 'Midle name', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('bride_last_name', 'Last name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('bride_father_prefix', 'Prefix', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_father_first_name', 'First name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_father_middle_name', 'Middle name', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_father_last_name', 'Last name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_mother_prefix', 'Prefix', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_mother_first_name', 'First name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_mother_middle_name', 'Middle name', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_mother_last_name', 'Last name', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('bride_status', 'Satus', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_occupation', 'Occupation', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_category', 'Category', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_dob', 'DOB', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_mobile_number', 'Mobile number', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_email_id', 'Email', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_disability', 'Disability', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_disability_type', 'Type', 'trim|required|xss_clean|strip_tags');
        if($bride_disability == 1) {
            $this->form_validation->set_rules('bride_disability_type', 'Disability Type', 'trim|required|xss_clean|strip_tags');
        } else {
            $this->form_validation->set_rules('bride_disability_type', 'Disability Type', 'trim|xss_clean|strip_tags');
        }//End of if else
        $this->form_validation->set_rules('bride_dependent_income', 'Income of Parents', 'required');
        $this->form_validation->set_rules('bride_present_country', 'Country', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_present_city', 'City', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_present_pin', 'PIN', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_present_period_years', 'Period', 'trim|required|xss_clean|strip_tags');
        if($bride_present_country === 'India') {
            $this->form_validation->set_rules('bride_present_state', 'State', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('bride_present_district', 'District', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('bride_present_ps', 'Police station', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('bride_present_po', 'Post Office', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('bride_lac', 'LAC', 'trim|xss_clean|strip_tags');
        } else {
            $this->form_validation->set_rules('bride_present_state_foreign', 'State', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('bride_present_address1', 'Address1', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('bride_present_address2', 'Address2', 'trim|required|xss_clean|strip_tags');            
        }//End of if else
        
        $this->form_validation->set_rules('bride_address_same', 'Is same address', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_permanent_country', 'Country', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_permanent_city', 'City', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('bride_permanent_pin', 'PIN', 'trim|required|xss_clean|strip_tags');
        if($bride_permanent_country === 'India') {
            $this->form_validation->set_rules('bride_permanent_state', 'State', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('bride_permanent_district', 'District', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('bride_permanent_ps', 'Police station', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('bride_permanent_po', 'Post Office', 'trim|required|xss_clean|strip_tags');
        } else {
            $this->form_validation->set_rules('bride_permanent_state_foreign', 'State', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('bride_permanent_address1', 'Address1', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('bride_permanent_address2', 'Address2', 'trim|required|xss_clean|strip_tags');            
        }//End of if else

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->index($objId);
        } else {
            $bride_child_first_name = $this->input->post("bride_child_first_name");
            $bride_child_middle_name = $this->input->post("bride_child_middle_name");
            $bride_child_last_name = $this->input->post("bride_child_last_name");
            $bride_child_dob = $this->input->post("bride_child_dob");
            $bride_child_address = $this->input->post("bride_child_address");
            $bride_children = array();

            if (count($bride_child_first_name)) {
                foreach ($bride_child_first_name as $k => $bride_child_first_name) {
                    $bride_child = array(
                        "bride_child_first_name" => $bride_child_first_name,
                        "bride_child_middle_name" => $bride_child_middle_name[$k],
                        "bride_child_last_name" => $bride_child_last_name[$k],
                        "bride_child_dob" => (strlen($bride_child_dob[$k])==10)?date("Y-m-d", strtotime($bride_child_dob[$k])):'',
                        "bride_child_address" => $bride_child_address[$k]
                    );
                    $bride_children[] = $bride_child;
                }//End of foreach()        
            }//End of if

            $bride_dependent_first_name = $this->input->post("bride_dependent_first_name");
            $bride_dependent_middle_name = $this->input->post("bride_dependent_middle_name");
            $bride_dependent_last_name = $this->input->post("bride_dependent_last_name");
            $bride_dependent_dob = $this->input->post("bride_dependent_dob");
            $bride_dependent_address = $this->input->post("bride_dependent_address");
            $bride_dependents = array();

            if (count($bride_dependent_first_name)) {
                foreach ($bride_dependent_first_name as $k => $bride_dependent_first_name) {
                    $bride_dependent = array(
                        "bride_dependent_first_name" => $bride_dependent_first_name,
                        "bride_dependent_middle_name" => $bride_dependent_middle_name[$k],
                        "bride_dependent_last_name" => $bride_dependent_last_name[$k],
                        "bride_dependent_dob" => (strlen($bride_dependent_dob[$k])==10)?date("Y-m-d", strtotime($bride_dependent_dob[$k])):'',
                        "bride_dependent_address" => $bride_dependent_address[$k]
                    );
                    $bride_dependents[] = $bride_dependent;
                }//End of foreach()        
            }//End of if
            
            if($bride_disability == 1) {
                $bride_disability_type = $this->input->post("bride_disability_type");
            } else {
                $bride_disability_type = '';
            }//End of if else
        
            if($bride_present_country === 'India') {
                $bride_present_state = $this->input->post("bride_present_state");
                $bride_present_state_name = $this->input->post("bride_present_state_name");
                $bride_present_district = $this->input->post("bride_present_district");
                $bride_present_ps = $this->input->post("bride_present_ps");
                $bride_present_po = $this->input->post("bride_present_po");
                $bride_lac = $this->input->post("bride_lac");                
                $bride_present_state_foreign = '';
                $bride_present_address1 = '';
                $bride_present_address2 = '';
            } else {
                $bride_present_state = '';
                $bride_present_state_name = '';
                $bride_present_district = '';
                $bride_present_ps = '';
                $bride_present_po = '';
                $bride_lac = '';
                $bride_present_state_foreign = $this->input->post("bride_present_state_foreign");
                $bride_present_address1 = $this->input->post("bride_present_address1");
                $bride_present_address2 = $this->input->post("bride_present_address2");           
            }//End of if else
            
            if($bride_permanent_country === 'India') {
                $bride_permanent_state = $this->input->post("bride_permanent_state");
                $bride_permanent_state_name = $this->input->post("bride_permanent_state_name");
                $bride_permanent_district = $this->input->post("bride_permanent_district");
                $bride_permanent_ps = $this->input->post("bride_permanent_ps");
                $bride_permanent_po = $this->input->post("bride_permanent_po");
                $bride_lac = $this->input->post("bride_lac");                
                $bride_permanent_state_foreign = '';
                $bride_permanent_address1 = '';
                $bride_permanent_address2 = '';
            } else {
                $bride_permanent_state = '';
                $bride_permanent_state_name = '';
                $bride_permanent_district = '';
                $bride_permanent_ps = '';
                $bride_permanent_po = '';
                $bride_lac = '';
                $bride_permanent_state_foreign = $this->input->post("bride_permanent_state_foreign");
                $bride_permanent_address1 = $this->input->post("bride_permanent_address1");
                $bride_permanent_address2 = $this->input->post("bride_permanent_address2");           
            }//End of if else

            $inputs = [
                'bride_prefix' => $this->input->post("bride_prefix"),
                'bride_first_name' => $this->input->post("bride_first_name"),
                'bride_middle_name' => $this->input->post("bride_middle_name"),
                'bride_last_name' => $this->input->post("bride_last_name"),
                'bride_father_prefix' => $this->input->post("bride_father_prefix"),
                'bride_father_first_name' => $this->input->post("bride_father_first_name"),
                'bride_father_middle_name' => $this->input->post("bride_father_middle_name"),
                'bride_father_last_name' => $this->input->post("bride_father_last_name"),
                'bride_mother_prefix' => $this->input->post("bride_mother_prefix"),
                'bride_mother_first_name' => $this->input->post("bride_mother_first_name"),
                'bride_mother_middle_name' => $this->input->post("bride_mother_middle_name"),
                'bride_mother_last_name' => $this->input->post("bride_mother_last_name"),
                'bride_status' => $this->input->post("bride_status"),
                'bride_occupation' => $this->input->post("bride_occupation"),
                'bride_category' => $this->input->post("bride_category"),
                'bride_dob' => $this->input->post("bride_dob"),
                'bride_mobile_number' => $this->input->post("bride_mobile_number"),
                'bride_email_id' => $this->input->post("bride_email_id"),
                'bride_disability' => $bride_disability,
                'bride_disability_type' => $bride_disability_type,
                'bride_children' => $bride_children,
                'bride_dependents' => $bride_dependents,
                'bride_dependent_income' => $this->input->post("bride_dependent_income"),
                'bride_address_same' => $this->input->post("bride_address_same"),
                'bride_present_country' => $bride_present_country,
                'bride_present_state' => $bride_present_state,
                'bride_present_state_name' => $bride_present_state_name,
                'bride_present_state_foreign' => $bride_present_state_foreign,
                'bride_present_district' => $bride_present_district,
                'bride_present_city' => $this->input->post("bride_present_city"),
                'bride_present_ps' => $bride_present_ps,
                'bride_present_po' => $bride_present_po,
                'bride_present_address1' => $bride_present_address1,
                'bride_present_address2' => $bride_present_address2,
                'bride_present_pin' => $this->input->post("bride_present_pin"),
                'bride_lac' => json_decode(html_entity_decode($this->input->post("bride_lac"))),
                'bride_present_period_years' => $this->input->post("bride_present_period_years"),
                'bride_present_period_months' => $this->input->post("bride_present_period_months"),
                'bride_permanent_country' => $bride_permanent_country,
                'bride_permanent_state' => $bride_permanent_state,
                'bride_permanent_state_name' => $bride_permanent_state_name,
                'bride_permanent_state_foreign' => $bride_permanent_state_foreign,
                'bride_permanent_district' => $bride_permanent_district,
                'bride_permanent_city' => $this->input->post("bride_permanent_city"),
                'bride_permanent_ps' => $bride_permanent_ps,
                'bride_permanent_po' => $bride_permanent_po,
                'bride_permanent_address1' => $bride_permanent_address1,
                'bride_permanent_address2' => $bride_permanent_address2,
                'bride_permanent_pin' => $this->input->post("bride_permanent_pin")
            ]; //pre($inputs);
            $this->marriageregistrations_model->update_where(['_id' => new ObjectId($objId)], $inputs);
            $this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/marriageregistration_landhub/groomdetails/index/' . $objId);
        }//End of if else
    }//End of submit()

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        }//End of if else
    }//End of checkObjectId()

    function get_states() {
            $field_name = $this->input->post("field_name");
            $country_name = $this->input->post("field_value");            
            echo '<select name="'.$field_name.'" id="'.$field_name.'" class="form-control">';
            if(strlen($country_name)) {
                $this->load->model('marriageregistration/states_model');
                $states = $this->states_model->get_distinct_results();
                if ($states) {
                    echo "<option value=''>Please Select</option>";
                    foreach ($states as $state) {
                        echo '<option value="' . $state->slc . '" >' . $state->state_name_english . '</option>';
                    }//End of foreach()
                } else {
                    echo "<option value=''>No records found</option>";
                }//End of if else
            } else {
                echo "<option value=''>Country cannot be empty</option>";
            }//End of if else
            echo '</select>';
    }//End of get_states()

    function get_districts() {
            $field_name = $this->input->post("field_name");
            $slc = (int)$this->input->post("field_value"); 
            echo '<select name="'.$field_name.'" id="'.$field_name.'" class="form-control bride_district">';
            if(strlen($slc)) {
                $this->load->model('marriageregistration/districts_model');
                $districts = $this->districts_model->get_distinct_results(array("slc" => $slc));
                if ($districts) {
                    echo "<option value=''>Please Select</option>";
                    foreach ($districts as $district) {
                        echo '<option value="' . $district->district_name_english . '" >' . $district->district_name_english . '</option>';
                    }//End of foreach()
                } else {
                    echo "<option value=''>No records found</option>";
                }//End of if else
            } else {
                echo "<option value=''>Country cannot be empty</option>";
            }//End of if else
            echo '</select>';
    }//End of get_districts()
}//End of Bridedetails