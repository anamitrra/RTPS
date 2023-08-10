<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps
{
    private $serviceName = "Application for Registration of employment seeker in Employment Exchange";
    private $serviceId = "EMP_REG_NA";
    private $base_serviceId = "1676";
    private $departmentName = "Department of Skill, Employment and Entrepreneurship";
    private $departmentId = "2193";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('employment_nonaadhaar/employment_model');
        $this->load->model('employment_nonaadhaar/district_model');
        $this->load->model('employment_nonaadhaar/sub_division_model');
        $this->load->model('employment_nonaadhaar/revenue_circle_model');
        $this->load->model('employment_nonaadhaar/functional_roles_model');
        $this->load->model('employment_nonaadhaar/functional_area_model');
        $this->load->model('employment_nonaadhaar/industry_sector_model');
        $this->load->model('employment_nonaadhaar/employment_office_model');
        $this->load->model('employment_nonaadhaar/highest_examination_passed_model');
        $this->load->model('employment_nonaadhaar/examination_passed_model');

        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper('smsprovider');
        $this->load->helper('pras_helper');

        $this->load->helper("employmentcertificate");
        // $this->config->load('spservices/spconfig');
        $this->aadhaarApi = $this->config->item('aadhaar_authentication_api');
        $this->load->model('employment_nonaadhaar/logreports_model');
        $this->load->helper("role");
        $this->load->library('user_agent');
        $this->load->library('Digilocker_API');

        if ($this->session->role) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else
    } //End of __construct()

    public function index($objId = null)
    {
        $this->load->model('employment_nonaadhaar/disability_type_model');
        $this->load->model('employment_nonaadhaar/disability_category_model');
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
            "disability_categories" => $this->disability_category_model->get_rows(),
            "disability_types" => $this->disability_type_model->get_rows(),
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else  
        $this->load->view('includes/frontend/header');
        $this->load->view('employment_nonaadhaar/personal_details_reg_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    // submit_personal_details
    public function submit_personal_details()
    {
        $objId = $this->input->post("obj_id");
        // $this->form_validation->set_rules("appl_status", "Application Status", "required");
        $this->form_validation->set_rules("applicant_name", "Applicant Name", "required|max_length[255]");
        $this->form_validation->set_rules("applicant_gender", "Gender", "required|max_length[255]");
        $this->form_validation->set_rules("mobile_number", "Mobile Number", "required|max_length[10]|regex_match[/^[0-9]{10}$/]");
        $this->form_validation->set_rules("fathers_name", "Fathers Name", "required|max_length[255]");
        $this->form_validation->set_rules("fathers_name_guardians_name", "Father's Name/ Guardian's Name", "required|max_length[255]");
        $this->form_validation->set_rules("mothers_name", "Mother Name", "required|max_length[255]");
        $this->form_validation->set_rules("date_of_birth", "Date of Birth", "required|max_length[255]");
        $this->form_validation->set_rules("caste", "Caste", "required|max_length[255]");
        if ($this->input->post("caste") == 'General') {
            $this->form_validation->set_rules("economically_weaker_section", "Economically weaker section field", "required|max_length[255]");
        }
        $this->form_validation->set_rules("whether_ex_servicemen", "Whether Ex-Servicemen", "required|max_length[255]");
        if ($this->input->post("whether_ex_servicemen") == 'Yes') {
            $this->form_validation->set_rules("ex_servicemen_category", "Ex-servicemen Category", "required|max_length[255]");
        }
        $this->form_validation->set_rules("religion", "Religion", "required|max_length[255]");
        $this->form_validation->set_rules("marital_status", "Marital Status", "required|max_length[255]");
        $this->form_validation->set_rules("occupation", "Occupation", "required|max_length[255]");
        if ($this->input->post("occupation") == 'Other') {
            $this->form_validation->set_rules("occupation_type", "Occupation Type", "required|max_length[255]");
        }
        if (($this->input->post("height_in_cm")) != '') {
            $this->form_validation->set_rules("height_in_cm", "Height", "numeric");
        }
        if (($this->input->post("weight_kgs")) != '') {
            $this->form_validation->set_rules("weight_kgs", "Weight", "numeric");
        }
        if (($this->input->post("chest_inch")) != '') {
            $this->form_validation->set_rules("chest_inch", "Chest", "numeric");
        }

        $this->form_validation->set_rules("prominent_identification_mark", "Prominent Identification Mark", "required|max_length[255]");
        $this->form_validation->set_rules("are_you_differently_abled_pwd", "Are you Differently abled (PwD)?", "required|max_length[255]");
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
            // var_dump(validation_errors());
        } else {
            if ($this->input->post('appl_status') == 'QS') {
                $form_data = array(
                    "form_data.applicant_gender" => $this->input->post("applicant_gender"),
                    "form_data.e-mail" => $this->input->post("e_mail"),
                    "form_data.fathers_name" => $this->input->post("fathers_name"),
                    "form_data.fathers_name__guardians_name" => $this->input->post("fathers_name_guardians_name"),
                    "form_data.mothers_name" => $this->input->post("mothers_name"),
                    "form_data.date_of_birth" => $this->input->post("date_of_birth"),
                    "form_data.caste" => $this->input->post("caste"),
                    "form_data.husbands_name" => $this->input->post("husbands_name"),
                    "form_data.whether_ex-servicemen" => $this->input->post("whether_ex_servicemen"),
                    "form_data.religion" => $this->input->post("religion"),
                    "form_data.marital_status" => $this->input->post("marital_status"),
                    "form_data.occupation" => $this->input->post("occupation"),
                    "form_data.occupation_type" => $this->input->post("occupation_type"),
                    "form_data.unique_identification_type" => $this->input->post("unique_identification_type"),
                    "form_data.unique_identification_no" => $this->input->post("unique_identification_no"),
                    "form_data.prominent_identification_mark" => $this->input->post("prominent_identification_mark"),
                    "form_data.height__in_cm" => $this->input->post("height_in_cm"),
                    "form_data.weight__kgs" => $this->input->post("weight_kgs"),
                    "form_data.eye_sight" => $this->input->post("eye_sight"),
                    "form_data.chest__inch" => $this->input->post("chest_inch"),
                    "form_data.are_you_differently_abled__pwd" => $this->input->post("are_you_differently_abled_pwd"),
                );

                if ($this->input->post("caste") != 'General') {
                    $form_data['form_data.economically_weaker_section'] = '';
                } else {
                    $form_data['form_data.economically_weaker_section'] = $this->input->post("economically_weaker_section");
                }
                if ($this->input->post("whether_ex_servicemen") != 'Yes') {
                    $form_data['form_data.category_of_ex-servicemen'] = '';
                } else {
                    $form_data['form_data.category_of_ex-servicemen'] = $this->input->post("ex_servicemen_category");
                }
                if ($this->input->post("are_you_differently_abled_pwd") == 'No') {
                    $form_data['form_data.disability_category'] = '';
                    $form_data['form_data.additional_disability_type'] = '';
                    $form_data['form_data.disbility_percentage'] = '';
                } else {
                    $form_data['form_data.disability_category'] = $this->input->post("disability_category");
                    $form_data['form_data.additional_disability_type'] = $this->input->post("additional_disability_type");
                    $form_data['form_data.disbility_percentage'] = $this->input->post("disbility_percentage");
                }
                $this->employment_model->update_where(['_id' => new ObjectId($objId)], $form_data);
                $obj_id = $objId;
            } else {
                $appl_ref_no_temp = $this->getID(7);
                $service_data = array(
                    "department_id" => $this->departmentId,
                    "department_name" => $this->departmentName,
                    "service_id" => $this->base_serviceId,
                    "service_name" => $this->serviceName,
                    "appl_id" => $appl_ref_no_temp,
                    "appl_ref_no" => $appl_ref_no_temp,
                    "submission_mode" => 'online',
                    "applied_by" => $this->session->userdata('userId')->{'$id'},
                    "appl_status" => "DRAFT",
                    "created_at" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $form_data = array(
                    "user_id" => $this->session->userdata('userId')->{'$id'},
                    "user_type" => $this->slug,
                    "applied_user_type" => $this->slug,
                    "service_name" => $this->serviceName,
                    "service_id" => $this->serviceId,
                    // "rtps_trans_id" => $appl_ref_no_temp,
                    "applicant_name" => $this->input->post("applicant_name"),
                    "applicant_gender" => $this->input->post("applicant_gender"),
                    "mobile_number" => $this->input->post("mobile_number"),
                    "mobile_verify_status" => $this->input->post("mobile_verify_status"),
                    "e-mail" => $this->input->post("e_mail"),
                    "fathers_name" => $this->input->post("fathers_name"),
                    "fathers_name__guardians_name" => $this->input->post("fathers_name_guardians_name"),
                    "mothers_name" => $this->input->post("mothers_name"),
                    "date_of_birth" => $this->input->post("date_of_birth"),
                    "caste" => $this->input->post("caste"),
                    "husbands_name" => $this->input->post("husbands_name"),
                    "whether_ex-servicemen" => $this->input->post("whether_ex_servicemen"),
                    "religion" => $this->input->post("religion"),
                    "marital_status" => $this->input->post("marital_status"),
                    "occupation" => $this->input->post("occupation"),
                    "occupation_type" => $this->input->post("occupation_type"),
                    "unique_identification_type" => $this->input->post("unique_identification_type"),
                    "unique_identification_no" => $this->input->post("unique_identification_no"),
                    "prominent_identification_mark" => $this->input->post("prominent_identification_mark"),
                    "height__in_cm" => $this->input->post("height_in_cm"),
                    "weight__kgs" => $this->input->post("weight_kgs"),
                    "eye_sight" => $this->input->post("eye_sight"),
                    "chest__inch" => $this->input->post("chest_inch"),
                    "are_you_differently_abled__pwd" => $this->input->post("are_you_differently_abled_pwd"),
                );

                if ($this->input->post("caste") != 'General') {
                    $form_data['economically_weaker_section'] = '';
                } else {
                    $form_data['economically_weaker_section'] = $this->input->post("economically_weaker_section");
                }
                if ($this->input->post("whether_ex_servicemen") != 'Yes') {
                    $form_data['category_of_ex-servicemen'] = '';
                } else {
                    $form_data['category_of_ex-servicemen'] = $this->input->post("ex_servicemen_category");
                }
                if ($this->input->post("are_you_differently_abled_pwd") == 'No') {
                    $form_data['disability_category'] = '';
                    $form_data['additional_disability_type'] = '';
                    $form_data['disbility_percentage'] = '';
                } else {
                    $form_data['disability_category'] = $this->input->post("disability_category");
                    $form_data['additional_disability_type'] = $this->input->post("additional_disability_type");
                    $form_data['disbility_percentage'] = $this->input->post("disbility_percentage");
                }

                if (strlen($objId)) {
                    $newArray = array_combine(
                        array_map(function ($key) {
                            return 'form_data.' . $key;
                        }, array_keys($form_data)),
                        $form_data
                    );
                    $this->employment_model->update_where(['_id' => new ObjectId($objId)], $newArray);
                    $obj_id = $objId;
                } else {
                    $form_data["rtps_trans_id"] = $appl_ref_no_temp;
                    $data = array('service_data' => $service_data, 'form_data' => $form_data);
                    $insertedData = $this->employment_model->insert($data);
                    $obj_id = ($insertedData['_id']->{'$id'});
                }
            }
            $this->session->set_flashdata('success', 'Personal Details has been successfully saved.');
            redirect('spservices/employment-registration-nonaadhaar/address-section/' . $obj_id);
        }
    }

    // address page view
    public function address($objId = null)
    {
        // $objId = $this->session->userdata('employment_oid');
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
            "districts" => $this->district_model->get_rows(),
            "sub_divisions" => $this->sub_division_model->get_rows(),
            "revenue_circles" => $this->revenue_circle_model->get_rows(),
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else  

        $this->load->view('includes/frontend/header');
        $this->load->view('employment_nonaadhaar/address_view_reg', $data);
        $this->load->view('includes/frontend/footer');
    }
    // submit_address
    public function submit_address()
    {
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules("appl_status", "Application Status", "required");
        $this->form_validation->set_rules("vill_town_ward_city_p", "Vill/Town/Ward/City", "required|max_length[255]");
        $this->form_validation->set_rules("post_office_p", "Post Office", "required|max_length[255]");
        $this->form_validation->set_rules("police_station_p", "Police Station", "required|max_length[255]");
        $this->form_validation->set_rules("pin_code_p", "Pin Code", "required|numeric");
        $this->form_validation->set_rules("district_p", "District", "required|max_length[255]");
        $this->form_validation->set_rules("sub_division", "Sub-Division", "required|max_length[255]");
        $this->form_validation->set_rules("revenue_circle", "Revenue Circle", "required|max_length[255]");
        $this->form_validation->set_rules("residence", "Residence", "required|max_length[255]");
        $this->form_validation->set_rules("same_as_permanent_address", "Same as permanent address", "required|max_length[255]");
        $this->form_validation->set_rules("vill_town_ward_city", "Vill/Town/Ward/City (Communication Address)", "required|max_length[255]");
        $this->form_validation->set_rules("post_office", "Post Office (Communication Address)", "required|max_length[255]");
        $this->form_validation->set_rules("police_station", "Police Station (Communication Address)", "required|max_length[255]");
        $this->form_validation->set_rules("pin_code", "PIN Code (Communication Address)", "required|numeric");
        $this->form_validation->set_rules("district", "District (Communication Address)", "required|max_length[255]");

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->address($obj_id);
        } else {
            if ($this->input->post('appl_status') == 'QS') {
                $data = array(
                    "form_data.name_of_the_house_apartment_p" => $this->input->post("name_of_the_house_apartment_p"),
                    "form_data.house_no_apartment_no_p" => $this->input->post("house_no_apartment_no_p"),
                    "form_data.building_no_block_no__p" => $this->input->post("building_no_block_no_p"),
                    "form_data.address__locality_street_etc___p" => $this->input->post("address_locality_street_etc_p"),
                    "form_data.vill_town_ward_city_p" => $this->input->post("vill_town_ward_city_p"),
                    "form_data.post_office_p" => $this->input->post("post_office_p"),
                    "form_data.police_station_p" => $this->input->post("police_station_p"),
                    "form_data.pin_code_p" => $this->input->post("pin_code_p"),
                    "form_data.residence" => $this->input->post("residence"),
                    "form_data.same_as_permanent_address" => $this->input->post("same_as_permanent_address"),
                    "form_data.name_of_the_house_apartment" => $this->input->post("name_of_the_house_apartment"),
                    "form_data.house_no_apartment_no" => $this->input->post("house_no_apartment_no"),
                    "form_data.building_no_block_no" => $this->input->post("building_no_block_no"),
                    "form_data.address__locality_street_etc" => $this->input->post("address_locality_street_etc"),
                    "form_data.vill_town_ward_city" => $this->input->post("vill_town_ward_city"),
                    "form_data.post_office" => $this->input->post("post_office"),
                    "form_data.police_station" => $this->input->post("police_station"),
                    "form_data.pin_code" => $this->input->post("pin_code"),
                );
            } else {
                $data = array(
                    "form_data.name_of_the_house_apartment_p" => $this->input->post("name_of_the_house_apartment_p"),
                    "form_data.house_no_apartment_no_p" => $this->input->post("house_no_apartment_no_p"),
                    "form_data.building_no_block_no__p" => $this->input->post("building_no_block_no_p"),
                    "form_data.address__locality_street_etc___p" => $this->input->post("address_locality_street_etc_p"),
                    "form_data.vill_town_ward_city_p" => $this->input->post("vill_town_ward_city_p"),
                    "form_data.post_office_p" => $this->input->post("post_office_p"),
                    "form_data.police_station_p" => $this->input->post("police_station_p"),
                    "form_data.pin_code_p" => $this->input->post("pin_code_p"),
                    "form_data.district_p" => $this->input->post("district_p"),
                    "form_data.sub-division" => $this->input->post("sub_division"),
                    "form_data.revenue_circle" => $this->input->post("revenue_circle"),
                    "form_data.residence" => $this->input->post("residence"),
                    "form_data.same_as_permanent_address" => $this->input->post("same_as_permanent_address"),
                    "form_data.name_of_the_house_apartment" => $this->input->post("name_of_the_house_apartment"),
                    "form_data.house_no_apartment_no" => $this->input->post("house_no_apartment_no"),
                    "form_data.building_no_block_no" => $this->input->post("building_no_block_no"),
                    "form_data.address__locality_street_etc" => $this->input->post("address_locality_street_etc"),
                    "form_data.vill_town_ward_city" => $this->input->post("vill_town_ward_city"),
                    "form_data.post_office" => $this->input->post("post_office"),
                    "form_data.police_station" => $this->input->post("police_station"),
                    "form_data.pin_code" => $this->input->post("pin_code"),
                    "form_data.district" => $this->input->post("district"),
                );
            }
            $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success', 'Address has been successfully saved.');
            redirect('spservices/employment-registration-nonaadhaar/education-skill-details/' . $objId);
        }
    }

    // skill and educational page view
    public function skill_education($objId = null)
    {
        $this->load->model('employment_aadhaar_based/highest_educational_level_model');
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
            "highest_education_level" => $this->highest_educational_level_model->get_rows(),
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else
        $this->load->view('includes/frontend/header');
        $this->load->view('employment_nonaadhaar/skill_education_reg_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    // submit_education_details
    public function submit_education_details()
    {
        $unset_data = '';
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules("obj_id", "Object ID", "required");
        // $this->form_validation->set_rules("appl_status", "Application Status", "required");
        $this->form_validation->set_rules("highest_educational_level", "Highest Educational Level", "required|max_length[255]");
        $this->form_validation->set_rules("highest_examination_passed", "Highest Examination Passed", "required|max_length[255]");

        if (base64_decode($this->input->post("highest_educational_level")) == 'Illiterate') {
            // $this->form_validation->set_rules("other_examination_passed", "Other Examination Passed", "required|max_length[255]");
        } else {
            $this->form_validation->set_rules("examination_passed[]", "Examination Passed", "required|max_length[255]");
            $this->form_validation->set_rules("subjects_other_subjects[]", "Subjects/ Other Subjects", "required|max_length[255]");
            $this->form_validation->set_rules("percentage_of_marks[]", "Percentage of Marks", "required|max_length[255]");
        }
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->skill_education($obj_id);
            // var_dump(validation_errors());
        } else {

            $education_qualification_arr = [];
            if (base64_decode($this->input->post("highest_educational_level")) == 'Illiterate') {
                $unset_data = 'form_data.education_qualification';
            } else {
                $highest_level = base64_decode($this->input->post("highest_educational_level"));
                $exam_count = count($this->input->post('examination_passed'));
                $twoRows = ['12th Pass', 'Diploma After 10th']; //mandatory 2 rows
                $threeRows = ['Diploma After 12th', 'Graduate']; // mandatory 3 rows
                $fourRows = ['PG Diploma', 'Post Graduate', 'PhD']; //mandatory 4 rows
                if (in_array($highest_level, $twoRows)) {
                    if ($exam_count != 2) {
                        $this->session->set_flashdata('error', 'All education details required upto ' . $highest_level);
                        $obj_id = strlen($objId) ? $objId : null;
                        $this->skill_education($obj_id);
                        return true;
                    }
                } else if (in_array($highest_level, $threeRows)) {
                    if ($exam_count != 3) {
                        $this->session->set_flashdata('error', 'All education details required upto ' . $highest_level);
                        $obj_id = strlen($objId) ? $objId : null;
                        $this->skill_education($obj_id);
                        return true;
                    }
                } else if (in_array($highest_level, $fourRows)) {
                    if ($exam_count != 4) {
                        $this->session->set_flashdata('error', 'All education details required upto ' . $highest_level);
                        $obj_id = strlen($objId) ? $objId : null;
                        $this->skill_education($obj_id);
                        return true;
                    }
                }
                $exam_count = count($this->input->post('examination_passed'));
                for ($i = 0; $i < $exam_count; $i++) {
                    $education_qualification_arr[] = [
                        "examination_passed" => $this->input->post('examination_passed')[$i],
                        "other_examination_name" => $this->input->post('other_examination_name')[$i],
                        "major__elective_subject" => $this->input->post('major_elective_subject')[$i],
                        "subjects__other_subjects" => $this->input->post('subjects_other_subjects')[$i],
                        "board__university" => $this->input->post('board_university')[$i],
                        "institution__school__college" => $this->input->post('institution_school_college')[$i],
                        "date_of_passing" => $this->input->post('date_of_passing')[$i],
                        "registration_no" => $this->input->post('registration_no')[$i],
                        "percentage_of_marks" => $this->input->post('percentage_of_marks')[$i],
                        "class__division" => $this->input->post('class_division')[$i],
                    ];
                }
            }
            // other_qualification_trainings_courses
            $other_qualification_count = count($this->input->post('certificate_name'));
            for ($i = 0; $i < $other_qualification_count; $i++) {
                if (($this->input->post('certificate_name')[$i] == '') && ($this->input->post('issued_by')[$i] == '') && ($this->input->post('duration_in_months')[$i] == '') && ($this->input->post('odate_of_passing')[$i] == '')) {
                    $other_qualification_arr = [];
                } else {
                    $other_qualification_arr[] = [
                        "certificate_name" => $this->input->post('certificate_name')[$i],
                        "issued_by" => $this->input->post('issued_by')[$i],
                        "duration_in_months" => $this->input->post('duration_in_months')[$i],
                        "date_of_passing" => $this->input->post('odate_of_passing')[$i]
                    ];
                }
            }

            $skill_qualification_count = count($this->input->post('exam_diploma_certificate'));
            for ($i = 0; $i < $skill_qualification_count; $i++) {
                if (($this->input->post('exam_diploma_certificate')[$i] == '') && ($this->input->post('sector')[$i] == '') && ($this->input->post('course_job_role')[$i] == '') && ($this->input->post('duration')[$i] == '') && ($this->input->post('certificate_id')[$i] == '') && ($this->input->post('engagement')[$i] == '')) {
                    $skill_qualification_arr = [];
                } else {
                    $skill_qualification_arr[] = [
                        "exam__diploma__certificate" => $this->input->post('exam_diploma_certificate')[$i],
                        "sector" => $this->input->post('sector')[$i],
                        "course__job_role" => $this->input->post('course_job_role')[$i],
                        "duration" => $this->input->post('duration')[$i],
                        "certificate_id" => $this->input->post('certificate_id')[$i],
                        "engagement" => $this->input->post('engagement')[$i],
                    ];
                }
            }

            $languages_known_count = count($this->input->post('languages_known'));
            for ($i = 0; $i < $languages_known_count; $i++) {
                $languages_known_arr[] = [
                    "language" => $this->input->post('languages_known')[$i],
                    "r_option" => isset($this->input->post('read_option')[$i]) ? $this->input->post('read_option')[$i] : '',
                    "w_option" => isset($this->input->post('write_option')[$i]) ? $this->input->post('write_option')[$i] : '',
                    "s_option" => isset($this->input->post('speak_option')[$i]) ? $this->input->post('speak_option')[$i] : '',
                ];
            }

            $data = array(
                'form_data.highest_educational_level' => base64_decode($this->input->post('highest_educational_level')),
                'form_data.highest_examination_passed' => $this->input->post('highest_examination_passed'),
                'form_data.education_qualification' => $education_qualification_arr,
                'form_data.other_qualification_trainings_courses' => $other_qualification_arr,
                'form_data.skill_qualification' => $skill_qualification_arr,
                'form_data.languages_known' => $languages_known_arr,
                'form_data.job_preference_key_skills' => $this->input->post('job_preference_key_skills')
            );
            // pre($data);
            $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->unsetdata($objId, $unset_data);
            $this->session->set_flashdata('success', 'Education & skill details has been successfully saved.');
            redirect('spservices/employment-registration-nonaadhaar/work-experiences/' . $objId);
        }
    }

    // work experience page view
    public function work_experiences($objId = null)
    {
        // $objId = $this->session->userdata('employment_oid');
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
            "functional_role_list" => $this->functional_roles_model->get_rows(),
            "functional_area_list" => $this->functional_area_model->get_rows(),
            "industry_sector_list" => $this->industry_sector_model->get_rows(),

        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        //pre($dbRow);
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else  
        // pre($data);
        $this->load->view('includes/frontend/header');
        $this->load->view('employment_nonaadhaar/work_experience_reg_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    // submit_work_experience
    public function submit_work_experience()
    {
        // $objId = $this->session->userdata('employment_oid');
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules("obj_id", "Object ID", "required");
        $this->form_validation->set_rules("appl_status", "Application Status", "required");
        $this->form_validation->set_rules("current_employment_status", "Current Employment Status", "required|max_length[255]");
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->work_experiences($obj_id);
            // var_dump(validation_errors());
        } else {
            $employer_count = count($this->input->post('employer'));
            for ($i = 0; $i < $employer_count; $i++) {
                if (($this->input->post('employer')[$i] == '') && ($this->input->post('nature_of_work')[$i] == '') && ($this->input->post('from')[$i] == '') && ($this->input->post('to')[$i] == '') && ($this->input->post('duration')[$i] == '')
                    && ($this->input->post('highest_designation')[$i] == '') && ($this->input->post('last_salary_drawn')[$i] == '')
                    && ($this->input->post('functional_roles')[$i] == '') && ($this->input->post('other_functional_roles')[$i] == '')
                    && ($this->input->post('industry_sector')[$i] == '') && ($this->input->post('other_industry_sector')[$i] == '')
                    && ($this->input->post('functional_area')[$i] == '') && ($this->input->post('other_functional_area')[$i] == '')
                ) {
                    $work_experience_arr = [];
                } else {
                    $work_experience_arr[] = [
                        "employer" => $this->input->post('employer')[$i],
                        "nature_of_work" => $this->input->post('nature_of_work')[$i],
                        "from" => $this->input->post('from')[$i],
                        "to" => $this->input->post('to')[$i],
                        "duration" => $this->input->post('duration')[$i],
                        "highest_designation" => $this->input->post('highest_designation')[$i],
                        "last_salary_drawn" => $this->input->post('last_salary_drawn')[$i],
                        "functional_roles" => $this->input->post('functional_roles')[$i],
                        "other_functional_roles" => $this->input->post('other_functional_roles')[$i],
                        "industry__sector" => $this->input->post('industry_sector')[$i],
                        "other_industry__sector" => $this->input->post('other_industry_sector')[$i],
                        "functional_area" => $this->input->post('functional_area')[$i],
                        "other_functional_area" => $this->input->post('other_functional_area')[$i],
                    ];
                }
            }

            $data = array(
                'form_data.work_experience' => $work_experience_arr,
                "form_data.years" => $this->input->post("years"),
                "form_data.months" => $this->input->post("months"),
                "form_data.current_employment_status" => $this->input->post("current_employment_status"),
            );
            // pre($data);
            $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success', 'Work experience details has been successfully saved.');
            if ($this->input->post('appl_status') == 'QS') {
                redirect('spservices/employment-registration-nonaadhaar/enclosures/' . $objId);
            } else {
                redirect('spservices/employment-registration-nonaadhaar/slot-booking/' . $objId);
            }
        }
    }

    // employment_exchange page view
    public function employment_exchange($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
        );
        $data['time_slots'] = $this->mongo_db->order_by(array('slot_id' => 'ASC'))->get('timeslot_master');
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
            $district = $dbRow->form_data->district_p;
            $district_id = $this->district_model->getDistId($dbRow->form_data->district_p)->{'0'}->id;
            $highest_exam_passed = $dbRow->form_data->highest_examination_passed;

            $skill_id = $this->highest_examination_passed_model->get_rows(['highest_examination_passed' => $highest_exam_passed])->{'0'}->skill_status;

            $employment_office = $this->employment_office_model->get_rows(['district_id' => $district_id]);
            $empl_office = [];

            // Dibrugarh district
            if ($district_id === 12) {
                foreach ($employment_office as $eo) {
                    if (($skill_id === 2) && (in_array($eo->employment_id, [18, 19, 20, 21, 22]))) {
                        $empl_office[] = $eo;
                    } else {
                        if (in_array($eo->employment_id, [18, 19, 20, 21])) {
                            $empl_office[] = $eo;
                        }
                    }
                }
            }

            // jorhat district
            elseif ($district_id == 18) {
                foreach ($employment_office as $eo) {
                    if (($skill_id != 2) && (in_array($eo->employment_id, [30]))) {
                        $empl_office[] = $eo;
                    } else {
                        $empl_office[] = $eo;
                    }
                }
            }

            // Kamrup district
            elseif ($district_id === 19) {
                foreach ($employment_office as $eo) {
                    if (($skill_id === 1) && (in_array($eo->employment_id, [33, 35]))) {
                        $empl_office[] = $eo;
                    } else if (($skill_id === 2) && (in_array($eo->employment_id, [32, 33, 34]))) {
                        $empl_office[] = $eo;
                    } else {
                        if (in_array($eo->employment_id, [32, 33])) {
                            $empl_office[] = $eo;
                        }
                    }
                }
            }

            // Kamrup Metro district
            elseif ($district_id === 20) {
                foreach ($employment_office as $eo) {
                    if (($skill_id === 1) && (in_array($eo->employment_id, [38]))) {
                        $empl_office[] = $eo;
                    } else if (($skill_id === 2) && (in_array($eo->employment_id, [36, 37]))) {
                        $empl_office[] = $eo;
                    } else {
                        if (in_array($eo->employment_id, [36])) {
                            $empl_office[] = $eo;
                        }
                    }
                }
            } else {
                foreach ($employment_office as $eo) {
                    $empl_office[] = $eo;
                }
            }

            $data["employment_office"] = $empl_office;
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else  
        $holidays = $this->mongo_db->where(['year' => date('Y')])->get('holiday_master');
        $holiday_list = array();
        foreach ($holidays as $hd) {
            $date = new DateTime($hd->date);
            $holiday_list[] = $date->format('j-n-Y');
        }
        $data['disabled_dates'] = json_encode($holiday_list);
        $this->load->view('includes/frontend/header');
        $this->load->view('employment_nonaadhaar/employment_exchange_reg_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    // submit_exchange_office
    public function submit_exchange_office()
    {
        $objId = $this->input->post("obj_id");
        $ref_no = $this->input->post("rtps_ref_no");
        $this->form_validation->set_rules("obj_id", "Object ID", "required");
        $this->form_validation->set_rules("rtps_ref_no", "Ref. No", "required");
        // $this->form_validation->set_rules("appl_status", "Application Status", "required");
        $this->form_validation->set_rules("booking_date", "Booking Date", "required|max_length[255]");
        $this->form_validation->set_rules("time_slot", "Time slot", "required|max_length[255]");
        $this->form_validation->set_rules("employment_exchange", "Employment Exchange", "required|max_length[255]");
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->employment_exchange($obj_id);
        } else {
            $data = array(
                "form_data.booking_date" => $this->input->post("booking_date"),
                "form_data.time_slot" => $this->input->post("time_slot"),
                "form_data.employment_exchange" => $this->input->post("employment_exchange"),
                "form_data.submission_location" => $this->input->post("employment_exchange")
            );

            $pdata['rtps_ref_no'] = $this->input->post("rtps_ref_no");
            $pdata['service_id'] = 'EMP_REG_NA';
            $pdata['booking_date'] = $this->input->post("booking_date");
            $pdata['time_slot'] = $this->input->post("time_slot");
            $pdata['employment_exchange'] = $this->input->post("employment_exchange");

            $ref = modules::load('spservices/slotbooking/CheckAvailability');
            $isAvailable = $ref->check_available($pdata);
            if ($isAvailable['status'] == 1) {
                $ref->release_slots($ref_no);
                $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
                redirect('spservices/employment-registration-nonaadhaar/enclosures/' . $objId);
            } else {
                $this->session->set_flashdata('error', 'Selected slot is not available. Please select another one.');
                $this->employment_exchange($objId);
            }
        }
    }


    // enclosures page view
    public function enclosures($objId = null)
    {
        // $objId = $this->session->userdata('employment_oid');
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        // pre($dbRow);
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else  
        // pre($data);
        $this->load->view('includes/frontend/header');
        $this->load->view('employment_nonaadhaar/enclosures_reg_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_enclosures()
    {
        // pre($_FILES);
        $objId = $this->input->post("obj_id");
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        $this->form_validation->set_rules('declaration', 'Declaration', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('proof_of_residence_type', 'Proof of Residence', 'trim|required|xss_clean|strip_tags');

        if (empty($this->input->post("proof_of_residence_old"))) {
            if (($_FILES['proof_of_residence']['name'] == "") && (empty($this->input->post("proof_of_residence_temp")))) {
                $this->form_validation->set_rules('proof_of_residence', 'Proof of Residence File', 'trim|required|xss_clean|strip_tags');
            }
        }

        $this->form_validation->set_rules('age_proof_type', 'Age Proof', 'trim|required|xss_clean|strip_tags');
        if (empty($this->input->post("age_proof_old"))) {
            if (($_FILES['age_proof']['name'] == "") && (empty($this->input->post("age_proof_temp")))) {
                $this->form_validation->set_rules('age_proof', 'Age Proof File', 'trim|required|xss_clean|strip_tags');
            }
        }

        if ($dbRow->form_data->caste != 'General') {
            $this->form_validation->set_rules('caste_certificate_type', 'Caste certificate', 'trim|required|xss_clean|strip_tags');
            if (empty($this->input->post("caste_certificate_old"))) {
                if (($_FILES['caste_certificate']['name'] == "") && (empty($this->input->post("caste_certificate_temp")))) {
                    $this->form_validation->set_rules('caste_certificate', 'Caste certificate File', 'trim|required|xss_clean|strip_tags');
                }
            }
        } elseif ($dbRow->form_data->economically_weaker_section === 'Yes') {
            $this->form_validation->set_rules('caste_certificate_type', 'Caste certificate', 'trim|required|xss_clean|strip_tags');
            if (empty($this->input->post("caste_certificate_old"))) {
                if (($_FILES['caste_certificate']['name'] == "") && (empty($this->input->post("caste_certificate_temp")))) {
                    $this->form_validation->set_rules('caste_certificate', 'Caste certificate File', 'trim|required|xss_clean|strip_tags');
                }
            }
        }

        if ($dbRow->form_data->highest_educational_level != 'Illiterate') {
            $this->form_validation->set_rules('educational_qualification_type', 'Educational qualification', 'trim|required|xss_clean|strip_tags');
            if (empty($this->input->post("educational_qualification_old"))) {
                if (($_FILES['educational_qualification']['name'] == "") && (empty($this->input->post("educational_qualification_temp")))) {
                    $this->form_validation->set_rules('educational_qualification', 'Educational qualification File', 'trim|required|xss_clean|strip_tags');
                }
            }
        }

        if ($dbRow->form_data->are_you_differently_abled__pwd === 'Yes') {
            $this->form_validation->set_rules('pwd_certificate_type', 'PWD certificate', 'trim|required|xss_clean|strip_tags');
            if (empty($this->input->post("pwd_certificate_old"))) {
                if (($_FILES['pwd_certificate']['name'] == "") && (empty($this->input->post("pwd_certificate_temp")))) {
                    $this->form_validation->set_rules('pwd_certificate', 'PWD certificate File', 'trim|required|xss_clean|strip_tags');
                }
            }
        }

        if ($dbRow->form_data->{'whether_ex-servicemen'} === 'Yes') {
            $this->form_validation->set_rules('ex_servicemen_certificate_type', 'Ex-servicemen certificate', 'trim|required|xss_clean|strip_tags');
            if (empty($this->input->post("ex_servicemen_certificate_old"))) {
                if (($_FILES['ex_servicemen_certificate']['name'] == "") && (empty($this->input->post("ex_servicemen_certificate_temp")))) {
                    $this->form_validation->set_rules('ex_servicemen_certificate', 'Ex-servicemen certificate File', 'trim|required|xss_clean|strip_tags');
                }
            }
        }

        $this->form_validation->set_rules('unique_document_type', 'Unique Identification Document', 'trim|required|xss_clean|strip_tags');
        if (empty($this->input->post("unique_document_old"))) {
            if (($_FILES['unique_document']['name'] == "") && (empty($this->input->post("unique_document_temp")))) {
                $this->form_validation->set_rules('unique_document', 'Unique Identification Document File', 'trim|required|xss_clean|strip_tags');
            }
        }

        $this->form_validation->set_rules('passport_photo_type', 'Passport photo', 'trim|required|xss_clean|strip_tags');
        if (strlen($this->input->post("passport_photo_old")) > 0 || (!empty($_FILES['passport_photo']['name']))) {
        } else {
            $this->form_validation->set_rules('passport_photo', 'Passport photo File', 'trim|required|xss_clean|strip_tags');
        }

        $this->form_validation->set_rules('signature_type', 'Signature', 'trim|required|xss_clean|strip_tags');
        if (strlen($this->input->post("signature_old")) > 0 || (!empty($_FILES['signature']['name']))) {
        } else {
            $this->form_validation->set_rules('signature', 'Signature File', 'trim|required|xss_clean|strip_tags');
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if (strlen($this->input->post("proof_of_residence_temp")) > 0) {
            $proofOfResidence = movedigilockerfile($this->input->post('proof_of_residence_temp'));
            $proof_of_residence = $proofOfResidence["upload_status"] ? $proofOfResidence["uploaded_path"] : null;
        } else {
            $proofOfResidence = cifileupload("proof_of_residence");
            $proof_of_residence = $proofOfResidence["upload_status"] ? $proofOfResidence["uploaded_path"] : null;
        }

        if (strlen($this->input->post("age_proof_temp")) > 0) {
            $ageProof = movedigilockerfile($this->input->post('age_proof_temp'));
            $age_proof = $ageProof["upload_status"] ? $ageProof["uploaded_path"] : null;
        } else {
            $ageProof = cifileupload("age_proof");
            $age_proof = $ageProof["upload_status"] ? $ageProof["uploaded_path"] : null;
        }

        if (strlen($this->input->post("noc_from_current_employeer_temp")) > 0) {
            $nocFromCurrentEmployeer = movedigilockerfile($this->input->post('noc_from_current_employeer_temp'));
            $noc_from_current_employeer = $nocFromCurrentEmployeer["upload_status"] ? $nocFromCurrentEmployeer["uploaded_path"] : null;
        } else {
            $nocFromCurrentEmployeer = cifileupload("noc_from_current_employeer");
            $noc_from_current_employeer = $nocFromCurrentEmployeer["upload_status"] ? $nocFromCurrentEmployeer["uploaded_path"] : null;
        }

        if (strlen($this->input->post("caste_certificate_temp")) > 0) {
            $casteCertificate = movedigilockerfile($this->input->post('caste_certificate_temp'));
            $caste_certificate = $casteCertificate["upload_status"] ? $casteCertificate["uploaded_path"] : null;
        } else {
            $casteCertificate = cifileupload("caste_certificate");
            $caste_certificate = $casteCertificate["upload_status"] ? $casteCertificate["uploaded_path"] : null;
        }

        if (strlen($this->input->post("educational_qualification_temp")) > 0) {
            $educationalQualification = movedigilockerfile($this->input->post('educational_qualification_temp'));
            $educational_qualification = $educationalQualification["upload_status"] ? $educationalQualification["uploaded_path"] : null;
        } else {
            $educationalQualification = cifileupload("educational_qualification");
            $educational_qualification = $educationalQualification["upload_status"] ? $educationalQualification["uploaded_path"] : null;
        }

        if (strlen($this->input->post("other_qualification_certificate_temp")) > 0) {
            $otherQualificationCertificate = movedigilockerfile($this->input->post('other_qualification_certificate_temp'));
            $other_qualification_certificate = $otherQualificationCertificate["upload_status"] ? $otherQualificationCertificate["uploaded_path"] : null;
        } else {
            $otherQualificationCertificate = cifileupload("other_qualification_certificate");
            $other_qualification_certificate = $otherQualificationCertificate["upload_status"] ? $otherQualificationCertificate["uploaded_path"] : null;
        }

        if (strlen($this->input->post("previous_employment_temp")) > 0) {
            $previousEmployment = movedigilockerfile($this->input->post('previous_employment_temp'));
            $previous_employment = $previousEmployment["upload_status"] ? $previousEmployment["uploaded_path"] : null;
        } else {
            $previousEmployment = cifileupload("previous_employment");
            $previous_employment = $previousEmployment["upload_status"] ? $previousEmployment["uploaded_path"] : null;
        }

        if (strlen($this->input->post("pwd_certificate_temp")) > 0) {
            $pwdCertificate = movedigilockerfile($this->input->post('pwd_certificate_temp'));
            $pwd_certificate = $pwdCertificate["upload_status"] ? $pwdCertificate["uploaded_path"] : null;
        } else {
            $pwdCertificate = cifileupload("pwd_certificate");
            $pwd_certificate = $pwdCertificate["upload_status"] ? $pwdCertificate["uploaded_path"] : null;
        }

        if (strlen($this->input->post("ex_servicemen_certificate_temp")) > 0) {
            $exServicemenCertificate = movedigilockerfile($this->input->post('ex_servicemen_certificate_temp'));
            $ex_servicemen_certificate = $exServicemenCertificate["upload_status"] ? $exServicemenCertificate["uploaded_path"] : null;
        } else {
            $exServicemenCertificate = cifileupload("ex_servicemen_certificate");
            $ex_servicemen_certificate = $exServicemenCertificate["upload_status"] ? $exServicemenCertificate["uploaded_path"] : null;
        }

        if (strlen($this->input->post("work_experience_temp")) > 0) {
            $workExperience = movedigilockerfile($this->input->post('work_experience_temp'));
            $work_experience = $workExperience["upload_status"] ? $workExperience["uploaded_path"] : null;
        } else {
            $workExperience = cifileupload("work_experience");
            $work_experience = $workExperience["upload_status"] ? $workExperience["uploaded_path"] : null;
        }

        if (strlen($this->input->post("any_other_document_temp")) > 0) {
            $anyOtherDocument = movedigilockerfile($this->input->post('any_other_document_temp'));
            $any_other_document = $anyOtherDocument["upload_status"] ? $anyOtherDocument["uploaded_path"] : null;
        } else {
            $anyOtherDocument = cifileupload("any_other_document");
            $any_other_document = $anyOtherDocument["upload_status"] ? $anyOtherDocument["uploaded_path"] : null;
        }

        if (strlen($this->input->post("unique_document_temp")) > 0) {
            $uniqueDocument = movedigilockerfile($this->input->post('unique_document_temp'));
            $unique_document = $uniqueDocument["upload_status"] ? $uniqueDocument["uploaded_path"] : null;
        } else {
            $uniqueDocument = cifileupload("unique_document");
            $unique_document = $uniqueDocument["upload_status"] ? $uniqueDocument["uploaded_path"] : null;
        }

        $passportPhoto = cifileupload("passport_photo");
        $passport_photo = $passportPhoto["upload_status"] ? $passportPhoto["uploaded_path"] : null;
        $passport_photo_data = $this->input->post("passport_photo_data");
        if ((strlen($passport_photo) == 0) && (strlen($passport_photo_data) > 50)) {
            $passportPhotoData = str_replace('data:image/jpeg;base64,', '', $passport_photo_data);
            $passportPhotoData2 = str_replace(' ', '+', $passportPhotoData);
            $passportPhotoData64 = base64_decode($passportPhotoData2);

            $fileName = uniqid() . '.jpeg';
            $dirPath = 'storage/docs/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
                file_put_contents($dirPath . "index.html", '<html><head></head><body>RTPS</body></html>');
            }
            $passport_photo = $dirPath . $fileName;
            file_put_contents(FCPATH . $passport_photo, $passportPhotoData64);
        }

        $signaturePhoto = cifileupload("signature");
        $signature_photo = $signaturePhoto["upload_status"] ? $signaturePhoto["uploaded_path"] : null;
        $signature_photo_data = $this->input->post("signature_photo_data");
        if ((strlen($signature_photo) == 0) && (strlen($signature_photo_data) > 50)) {
            $signaturePhotoData = str_replace('data:image/jpeg;base64,', '', $signature_photo_data);
            $signaturePhotoData2 = str_replace(' ', '+', $signaturePhotoData);
            $signaturePhotoData64 = base64_decode($signaturePhotoData2);

            $fileName = uniqid() . '.jpeg';
            $dirPath = 'storage/docs/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
                file_put_contents($dirPath . "index.html", '<html><head></head><body>RTPS</body></html>');
            }
            $signature_photo = $dirPath . $fileName;
            file_put_contents(FCPATH . $signature_photo, $signaturePhotoData64);
        }

        $uploadedFiles = array(
            "proof_of_residence_old" => strlen($proof_of_residence) ? $proof_of_residence : $this->input->post("proof_of_residence_old"),
            "age_proof_old" => strlen($age_proof) ? $age_proof : $this->input->post("age_proof_old"),
            "noc_from_current_employeer_old" => strlen($noc_from_current_employeer) ? $noc_from_current_employeer : $this->input->post("noc_from_current_employeer_old"),
            "caste_certificate_old" => strlen($caste_certificate) ? $caste_certificate : $this->input->post("caste_certificate_old"),
            "educational_qualification_old" => strlen($educational_qualification) ? $educational_qualification : $this->input->post("educational_qualification_old"),
            "other_qualification_certificate_old" => strlen($other_qualification_certificate) ? $other_qualification_certificate : $this->input->post("other_qualification_certificate_old"),
            "previous_employment_old" => strlen($previous_employment) ? $previous_employment : $this->input->post("previous_employment_old"),
            "pwd_certificate_old" => strlen($pwd_certificate) ? $pwd_certificate : $this->input->post("pwd_certificate_old"),
            "ex_servicemen_certificate_old" => strlen($ex_servicemen_certificate) ? $ex_servicemen_certificate : $this->input->post("ex_servicemen_certificate_old"),
            "work_experience_old" => strlen($work_experience) ? $work_experience : $this->input->post("work_experience_old"),
            "any_other_document_old" => strlen($any_other_document) ? $any_other_document : $this->input->post("any_other_document_old"),
            "unique_document_old" => strlen($unique_document) ? $unique_document : $this->input->post("unique_document_old"),
            "passport_photo_old" => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
            "signature_old" => strlen($signature_photo) ? $signature_photo : $this->input->post("signature_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->enclosures($obj_id);
        } else {
            $data = array(
                'form_data.declaration' => $this->input->post("declaration"),
                'form_data.enclosures.proof_of_residence_type' => $this->input->post("proof_of_residence_type"),
                'form_data.enclosures.proof_of_residence' => strlen($proof_of_residence) ? $proof_of_residence : $this->input->post("proof_of_residence_old"),

                'form_data.enclosures.age_proof_type' => $this->input->post("age_proof_type"),
                'form_data.enclosures.age_proof' => strlen($age_proof) ? $age_proof : $this->input->post("age_proof_old"),

                'form_data.enclosures.noc_from_current_employeer_type' => $this->input->post("noc_from_current_employeer_type"),
                'form_data.enclosures.noc_from_current_employeer' => strlen($noc_from_current_employeer) ? $noc_from_current_employeer : $this->input->post("noc_from_current_employeer_old"),

                'form_data.enclosures.caste_certificate_type' => $this->input->post("caste_certificate_type"),
                'form_data.enclosures.caste_certificate' => strlen($caste_certificate) ? $caste_certificate : $this->input->post("caste_certificate_old"),

                'form_data.enclosures.educational_qualification_type' => $this->input->post("educational_qualification_type"),
                'form_data.enclosures.educational_qualification' => strlen($educational_qualification) ? $educational_qualification : $this->input->post("educational_qualification_old"),

                'form_data.enclosures.other_qualification_certificate_type' => $this->input->post("other_qualification_certificate_type"),
                'form_data.enclosures.other_qualification_certificate' => strlen($other_qualification_certificate) ? $other_qualification_certificate : $this->input->post("other_qualification_certificate_old"),

                'form_data.enclosures.previous_employment_type' => $this->input->post("previous_employment_type"),
                'form_data.enclosures.previous_employment' => strlen($previous_employment) ? $previous_employment : $this->input->post("previous_employment_old"),

                'form_data.enclosures.pwd_certificate_type' => $this->input->post("pwd_certificate_type"),
                'form_data.enclosures.pwd_certificate' => strlen($pwd_certificate) ? $pwd_certificate : $this->input->post("pwd_certificate_old"),

                'form_data.enclosures.ex_servicemen_certificate_type' => $this->input->post("ex_servicemen_certificate_type"),
                'form_data.enclosures.ex_servicemen_certificate' => strlen($ex_servicemen_certificate) ? $ex_servicemen_certificate : $this->input->post("ex_servicemen_certificate_old"),

                'form_data.enclosures.work_experience_type' => $this->input->post("work_experience_type"),
                'form_data.enclosures.work_experience' => strlen($work_experience) ? $work_experience : $this->input->post("work_experience_old"),

                'form_data.enclosures.any_other_document_type' => $this->input->post("any_other_document_type"),
                'form_data.enclosures.any_other_document' => strlen($any_other_document) ? $any_other_document : $this->input->post("any_other_document_old"),

                'form_data.enclosures.unique_document_type' => $this->input->post("unique_document_type"),
                'form_data.enclosures.unique_document' => strlen($unique_document) ? $unique_document : $this->input->post("unique_document_old"),

                'form_data.enclosures.passport_photo_type' => $this->input->post("passport_photo_type"),
                'form_data.enclosures.passport_photo' => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),

                'form_data.enclosures.signature_photo_type' => $this->input->post("signature_type"),
                'form_data.enclosures.signature_photo' => strlen($signature_photo) ? $signature_photo : $this->input->post("signature_old"),
            );
            // pre($data);
            $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
            redirect('spservices/employment-registration-nonaadhaar/preview/' . $objId);
        }
    }

    /**
     *  load preview page
     * 
     */
    public function preview($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
        } else {
            $data["dbrow"] = false;
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('employment_nonaadhaar/registration_preview', $data);
        $this->load->view('includes/frontend/footer');
    }

    function getID($length)
    {
        $refID = $this->generateID($length);
        while ($this->employment_model->get_row(["service_data.appl_ref_no" => $refID])) {
            $refID = $this->generateID($length);
        } //End of while()
        return $refID;
    } //End of getID()

    public function generateID($length)
    {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-RESEE/" . date('Y') . "/" . $number;
        return $str;
    } //End of generateID()

    /**
     *  load view page in track
     * 
     */
    public function view($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
        } else {
            $data["dbrow"] = false;
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('employment_nonaadhaar/registration_preview', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function track($objId = null)
    {
        $dbRow = $this->employment_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_data.service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            // pre($data);
            $this->load->view('includes/frontend/header');
            $this->load->view('employment_nonaadhaar/registrationtrack_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            // redirect('spservices/income/inc/');
        } //End of if else
    } //End of track()

    /**
     *  final submission of the application
     * 
     */
    public function finalsubmition($objId = null)
    {
        if ($objId) {
            $dbRow = $this->employment_model->get_by_doc_id($objId);
            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }
            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")) {
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Application Submission",
                    "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
                //new change
                $userRows = $this->mongo_db->where(array(
                    'user_services.service_code' => $this->base_serviceId,
                    'offices_info.office_name' => $dbRow->form_data->employment_exchange,
                    '$or' => array(
                        ['user_levels.level_no' => 1],
                        ['additional_roles.level_no' => 1]
                    ),
                    'status' => 1
                ))->get('upms_users');


                $current_users = array();
                if ($userRows) {
                    foreach ($userRows as $key => $userRow) {
                        $additionalRoles = $userRow->additional_roles ?? array();
                        if (is_array($additionalRoles) && count($additionalRoles)) {
                            foreach ($additionalRoles as $ar) {
                                if ($ar->level_no == 1) {
                                    $current_user = array(
                                        'login_username' => $userRow->login_username,
                                        'email_id' => $userRow->email_id,
                                        'mobile_number' => $userRow->mobile_number,
                                        'user_level_no' => $ar->level_no,
                                        'user_fullname' => $userRow->user_fullname,
                                    );
                                }
                            }
                        } else {
                            $current_user = array(
                                'login_username' => $userRow->login_username,
                                'email_id' => $userRow->email_id,
                                'mobile_number' => $userRow->mobile_number,
                                'user_level_no' => $userRow->user_levels->level_no,
                                'user_fullname' => $userRow->user_fullname,
                            );
                        }
                        $current_users[] = $current_user;
                    } //End of foreach()
                }

                $data_to_update = array(
                    'service_data.appl_status' => 'submitted',
                    'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'form_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'current_users' => $current_users,
                    'processing_history' => $processing_history
                );

                $this->employment_model->update($objId, $data_to_update);
                $smsData = [
                    'applicant_name' => $dbRow->form_data->applicant_name,
                    'mobile' => $dbRow->form_data->mobile_number,
                    'service_name' => 'Employment Registration',
                    'submission_date' => format_mongo_date(new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)),
                    'app_ref_no' => $dbRow->service_data->appl_ref_no
                ];
                sms_provider('submission', $smsData);
                // redirect('spservices/employment-registration-nonaadhaar/acknowledgement/' . $objId);
                redirect('spservices/applications/acknowledgement/' . $objId);
            } else {
                $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                $this->my_transactions();
            }
        }
    }

    public function submit_query($objId = null)
    {
        if ($objId) {
            $dbRow = $this->employment_model->get_by_doc_id($objId);
            if (count((array)$dbRow)) {
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Query submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Query submitted",
                    "remarks" => "Query submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
                $data = array(
                    "service_data.appl_status" => "QA",
                    'processing_history' => $processing_history,
                    'status' => "QUERY_ANSWERED"
                );
                $this->employment_model->update($objId, $data);
                $this->session->set_flashdata('success', 'Query has been successfully updated');
                $this->my_transactions();
            }
        } else {
            $this->my_transactions();
        }
    }

    public function acknowledgement($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        if ($dbRow) {
            $data["response"] = $this->employment_model->get_by_doc_id($objId);
        } else {
            $data["response"] = false;
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('employment_nonaadhaar/ack', $data);
        $this->load->view('includes/frontend/footer');
    }


    public function checkNumeric()
    {
        $a = '45.3';
        var_dump(is_numeric($a));

        $output = preg_replace('/[^0-9]/', '', 'sdfgfd');
        pre($output);
    }
    private function checkObjectId($obj)
    {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        } //End of if else
    } //End of checkObjectId()
    // get sub-division
    public function get_subdivision()
    {
        $district_name =  $this->input->post('districtName');
        $districtId =  $this->district_model->getDistId($district_name);

        $filter['district_id'] = $districtId->{0}->id;
        $data =  (array)$this->sub_division_model->get_rows($filter);
        echo json_encode($data);
    }

    // get revenue circle
    public function get_revenuecircle()
    {
        $sub_division =  $this->input->post('subDivision');
        $subdivisionId =  $this->sub_division_model->getSubDivId($sub_division);

        $filter['sub_division_id'] = $subdivisionId->{0}->subdivision_id;
        $data =  (array)$this->revenue_circle_model->get_rows($filter);
        echo json_encode($data);
    }


    // get Highest Examination Passed list
    public function get_highest_examination()
    {

        $highest_education =  $this->input->post('highestEducation');
        $filter['highest_educational_level'] = base64_decode($highest_education);
        $data = $this->highest_examination_passed_model->get_rows($filter);
        if ($data) {
            echo json_encode(array("data" => (array)$data, "status" => true));
        } else {
            echo json_encode(array("data" => "", "status" => false));
        }
    }
    // get Examination Passed list
    public function get_examination_passed()
    {
        $highest_education =  $this->input->post('highestEducation');
        $filter['highest_educational_level'] = base64_decode($highest_education);
        $data =  $this->examination_passed_model->get_rows($filter);
        if ($data) {
            echo json_encode(array("data" => (array)$data, "status" => true));
        } else {
            echo json_encode(array("data" => "", "status" => false));
        }
    }

    public function unsetdata($objId, $data)
    {
        $this->mongo_db->command(array(
            'update' => 'sp_applications',
            'updates' => [
                array(
                    'q' => array('_id' => new ObjectId($objId)),
                    'u' => array(
                        '$unset' => array($data => ''),
                    )
                ),
            ],
        ));
    }

    private function my_transactions()
    {
        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }
}
