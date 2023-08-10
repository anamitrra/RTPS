<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Reregistration extends Rtps
{

    private $serviceName = "Application for Re-registration of employment seeker in Employment Exchange";
    private $serviceId = "EMP_REREG_NA";
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
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper('smsprovider');
        $this->load->helper("employmentcertificate");
        // $this->config->load('spservices/spconfig');
        $this->aadhaarApi = $this->config->item('aadhaar_authentication_api');
        $this->load->model('employment_nonaadhaar/logreports_model');
        $this->load->helper("role");
        $this->load->library('user_agent');
        $this->load->library('digilocker_api');


        if ($this->session->role) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else
    } //End of __construct()

    public function search_reg_details($objId = null)
    {
        // check_application_count_for_citizen();
        $data = array(
            "obj_id" => $objId,
            "service_name" => $this->serviceName
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        $employment_exchange_offices = $this->employment_office_model->get_all_rows();

        $data["employment_exchange_offices"] = [];
        if ($employment_exchange_offices) {
            $data["employment_exchange_offices"] = $employment_exchange_offices;
        }

        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('employment_nonaadhaar/search_registration_details', $data);
        $this->load->view('includes/frontend/footer');
    }

    // submit search registration details
    public function submit_search_reg_details()
    {
        $objId = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");

        $this->form_validation->set_rules("employment_exchange", "Employment Exchange Office", "required|max_length[255]");
        $this->form_validation->set_rules("registration_no", "Registration No", "required|max_length[255]");
        $this->form_validation->set_rules("date_of_reg", "Date of Registration", "required|max_length[10]");
        $this->form_validation->set_rules("type_of_reg", "Type of Registration", "required|max_length[10]");

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->search_reg_details($obj_id);
        } else {

            $oldData = array(
                "form_data.registration_no" => $this->input->post("registration_no"),
                "form_data.date_of_reg" => $this->input->post("date_of_reg"),
                "form_data.service_id" => $this->serviceId
            );
            $oldRecord = $this->employment_model->get_row($oldData);

            if (empty($oldRecord)) {
                $this->session->set_flashdata('success', 'No Record found.');
                redirect('spservices/employment-registration-nonaadhaar-reregistration');
                exit();
            }

            //Fetch data from ServicePlus API
            $registration_no = $this->input->post("registration_no");
            $sub_date1 = explode("-", $this->input->post("date_of_reg"));
            $sub_date = $sub_date1[2]."-".$sub_date1[1]."-".$sub_date1[0];
            // $registration_no = "0000262/9/2021";
            // $sub_date = "2021-09-30";
            $token = '|0VW?z,-w2w"6;{b8v}K6A5+Fdf@l-';
            $ch = curl_init('https://rtps.assam.gov.in/tools/rtps_id_labels/src/api/external_apis.php/get_skill_data?reg_no='.$registration_no.'&sub_date='.$sub_date);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Accept: application/json",
                'Authorization: Bearer ' . $token
            ));
            $data = curl_exec($ch);
            curl_close($ch);
            //  End
            $data = json_decode($data);
            if ($data->status) {

                $func_status = $this->submit_previous_service_plus_data($data->data, $registration_no);
                if($func_status){
                    $this->session->set_flashdata('success', 'Registration Details has been successfully saved.');
                    redirect('spservices/employment-reregistration-nonaadhaar/personal-details/' . $func_status);
                }else{
                    $this->session->set_flashdata('fail','Unable to find!! Please try again.');
                    redirect('spservices/employmentnonaadhaar/reregistration/search_reg_details');
                }
            }else{
                $this->session->set_flashdata('fail','Unable to find!! Please try again.');
                redirect('spservices/employmentnonaadhaar/reregistration/search_reg_details');
            }

            $appl_ref_no_temp = $this->getID(7);
            $sessionUser=$this->session->userdata();

            if($this->slug === "CSC"){
                $apply_by = $sessionUser['userId'];
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            }//End of if else

            while(1){
                $app_id = rand(100000000, 999999999);
                $filter = array( 
                    "service_data.appl_id" => $app_id,
                    //"service_data.service_id" => $this->serviceId
                );
                $rows = $this->employment_model->get_row($filter);
                
                if($rows == false)
                    break;
            }

            $service_data = array(
                "department_id" => $this->departmentId,
                "department_name" => $this->departmentName,
                "service_id" => $this->base_serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no_temp,
                "submission_mode" => $submissionMode,
                "applied_by" => $apply_by,
                "submission_location" => $this->input->post("employment_exchange"),
                "submission_date" => "",
                "service_timeline" => "30 Days",
                "appl_status" => "DRAFT",
                "district" => "",
            );

            $form_data = array(
                "user_id" => $this->session->userdata('userId')->{'$id'},
                "user_type" => $this->slug,
                "service_id" => $this->serviceId,
                'old_appl_ref_no' => $oldRecord->service_data->appl_ref_no,
                "registration_no" => $oldRecord->form_data->registration_no,
                "type_of_reg" => $this->input->post("type_of_reg"),
                "applicant_name" => $oldRecord->form_data->applicant_name,
                "applicant_gender" => $oldRecord->form_data->applicant_gender,
                "e-mail" => $oldRecord->form_data->{'e-mail'},
                "fathers_name" => $oldRecord->form_data->fathers_name,
                "fathers_name__guardians_name" => $oldRecord->form_data->fathers_name__guardians_name,
                "mothers_name" => $oldRecord->form_data->mothers_name,
                "date_of_birth" => $oldRecord->form_data->date_of_birth,
                "caste" => $oldRecord->form_data->caste,
                "husbands_name" => $oldRecord->form_data->husbands_name,
                "whether_ex-servicemen" => $oldRecord->form_data->{'whether_ex-servicemen'},
                "religion" => $oldRecord->form_data->religion,
                "marital_status" => $oldRecord->form_data->marital_status,
                "occupation" => $oldRecord->form_data->occupation,
                "occupation_type" => $oldRecord->form_data->occupation_type,
                "unique_identification_type" => $oldRecord->form_data->unique_identification_type,
                "unique_identification_no" => $oldRecord->form_data->unique_identification_no,
                "prominent_identification_mark" => $oldRecord->form_data->prominent_identification_mark,
                "height__in_cm" => $oldRecord->form_data->height__in_cm,
                "weight__kgs" => $oldRecord->form_data->weight__kgs,
                "eye_sight" => $oldRecord->form_data->eye_sight,
                "chest__inch" => $oldRecord->form_data->chest__inch,
                "are_you_differently_abled__pwd" => $oldRecord->form_data->are_you_differently_abled__pwd,
                "economically_weaker_section" => $oldRecord->form_data->economically_weaker_section,
                "category_of_ex-servicemen" => $oldRecord->form_data->{'category_of_ex-servicemen'},
                "disability_category" => $oldRecord->form_data->disability_category,
                "additional_disability_type" => $oldRecord->form_data->additional_disability_type,
                "disbility_percentage" => $oldRecord->form_data->disbility_percentage,
                "rtps_trans_id" => $appl_ref_no_temp,
                "address__locality_street_etc" => $oldRecord->form_data->address__locality_street_etc,
                "address__locality_street_etc___p" => $oldRecord->form_data->address__locality_street_etc___p,
                "building_no_block_no" => $oldRecord->form_data->building_no_block_no,
                "building_no_block_no__p" => $oldRecord->form_data->building_no_block_no__p,
                "district" => $oldRecord->form_data->district,
                "district_p" => $oldRecord->form_data->district_p,
                "house_no_apartment_no" => $oldRecord->form_data->house_no_apartment_no,
                "house_no_apartment_no_p" => $oldRecord->form_data->house_no_apartment_no_p,
                "name_of_the_house_apartment" => $oldRecord->form_data->name_of_the_house_apartment,
                "name_of_the_house_apartment_p" => $oldRecord->form_data->name_of_the_house_apartment_p,
                "pin_code" => $oldRecord->form_data->pin_code,
                "pin_code_p" => $oldRecord->form_data->pin_code_p,
                "police_station" => $oldRecord->form_data->police_station,
                "police_station_p" => $oldRecord->form_data->police_station_p,
                "post_office" => $oldRecord->form_data->post_office,
                "post_office_p" => $oldRecord->form_data->post_office_p,
                "residence" => $oldRecord->form_data->residence,
                "revenue_circle" => $oldRecord->form_data->revenue_circle,
                "same_as_permanent_address" => $oldRecord->form_data->same_as_permanent_address,
                "sub-division" => $oldRecord->form_data->{'sub-division'},
                "vill_town_ward_city" => $oldRecord->form_data->vill_town_ward_city,
                "vill_town_ward_city_p" => $oldRecord->form_data->vill_town_ward_city_p,
                "education_qualification" => $oldRecord->form_data->education_qualification,
                "highest_educational_level" => $oldRecord->form_data->highest_educational_level,
                "highest_examination_passed" => $oldRecord->form_data->highest_examination_passed,
                "job_preference_key_skills" => $oldRecord->form_data->job_preference_key_skills,
                "languages_known" => $oldRecord->form_data->languages_known,
                "other_qualification_trainings_courses" => $oldRecord->form_data->other_qualification_trainings_courses,
                "skill_qualification" => $oldRecord->form_data->skill_qualification,
                "current_employment_status" => $oldRecord->form_data->current_employment_status,
                "months" => $oldRecord->form_data->months,
                "work_experience" => $oldRecord->form_data->work_experience,
                "years" => $oldRecord->form_data->years,
                "employment_exchange" => $oldRecord->form_data->employment_exchange,
                "enclosures" => $oldRecord->form_data->enclosures
            );

            if (strlen($objId)) {
                $newArray = array_combine(
                    array_map(function ($key) {
                        return 'form_data.' . $key;
                    }, array_keys($form_data)),
                    $form_data
                );
                $this->employment_model->update_where(['_id' => new ObjectId($objId)], $newArray);
                $obj_id = $objId;
                $this->session->set_flashdata('success', 'Registration Details has been successfully saved.');
                redirect('spservices/employment-reregistration-nonaadhaar/personal-details/' . $obj_id);
                exit();
            } else {
                $data = array('service_data' => $service_data, 'form_data' => $form_data);
                $insertedData = $this->employment_model->insert($data);
                if($insertedData){
                    $obj_id = ($insertedData['_id']->{'$id'});
                    $this->session->set_flashdata('success', 'Registration Details has been successfully saved.');
                    redirect('spservices/employment-reregistration-nonaadhaar/personal-details/' . $obj_id);
                    exit();
                } else {
                    $this->session->set_flashdata('fail','Unable to submit data!!! Please try again.');
                    redirect('spservices/employmentnonaadhaar/reregistration/search_reg_details');
                }
            }
        }
    }

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
        $this->load->view('employment_nonaadhaar/repersonal_details_reg_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function index_($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName
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
        $this->load->view('includes/frontend/header');
        $this->load->view('employment_nonaadhaar/registration', $data);
        $this->load->view('includes/frontend/footer');
    }

    // submit_personal_details
    public function submit_personal_details()
    {
        $this->form_validation->set_rules("obj_id", "ID", "required");
        $objId = $this->input->post("obj_id");

        $data = array(
            "obj_id" => $objId,
            "service_name" => $this->serviceName
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        if ($dbRow->form_data->type_of_reg == 3) {
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
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
            // var_dump(validation_errors());
        } else {
            if ($dbRow->form_data->type_of_reg == 3) {
                $appl_ref_no_temp = $this->getID(7);
                $service_data = array(
                    // "department_id" => $this->departmentId,
                    // "department_name" => $this->departmentName,
                    // "service_id" => $this->serviceId,
                    // "service_name" => $this->serviceName,
                    // "appl_id" => $appl_ref_no_temp,
                    // "appl_ref_no" => $appl_ref_no_temp,
                    // "submission_mode" => $this->serviceId,
                    // "applied_by" => $this->session->userdata('userId'),
                    // "submission_location" => $this->serviceId,
                    // "appl_status" => "DRAFT",
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
                } else {
                    $form_data["rtps_trans_id"] = $appl_ref_no_temp;
                    $data = array('service_data' => $service_data, 'form_data' => $form_data);
                    $insertedData = $this->employment_model->insert($data);
                    $$objId = ($insertedData['_id']->{'$id'});
                }
            }
            $this->session->set_flashdata('success', 'Personal Details has been successfully saved.');
            redirect('spservices/employment-reregistration-nonaadhaar/address-section/' . $objId);
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
        $this->load->view('employment_nonaadhaar/readdress_view_reg', $data);
        $this->load->view('includes/frontend/footer');
    }
    // submit_address
    public function submit_address()
    {
        $this->form_validation->set_rules("obj_id", "ID", "required");
        $objId = $this->input->post("obj_id");

        $data = array(
            "obj_id" => $objId,
            "service_name" => $this->serviceName
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        if ($dbRow->form_data->type_of_reg == 3) {
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
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->address($obj_id);
        } else {
            if ($dbRow->form_data->type_of_reg == 3) {
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

                // pre($data);
                $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
            }
            $this->session->set_flashdata('success', 'Address has been successfully saved.');
            redirect('spservices/employment-reregistration-nonaadhaar/education-skill-details/' . $objId);
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
        $this->load->view('employment_nonaadhaar/reskill_education_reg_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    // submit_education_details
    public function submit_education_details()
    {
        $unset_data = '';
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules("obj_id", "Object ID", "required");
        // $this->form_validation->set_rules("appl_status", "Application Status", "required");
        $data = array(
            "obj_id" => $objId,
            "service_name" => $this->serviceName
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow->form_data->type_of_reg == 4) {
            $this->form_validation->set_rules("highest_educational_level", "Highest Educational Level", "required|max_length[255]");
            $this->form_validation->set_rules("highest_examination_passed", "Highest Examination Passed", "required|max_length[255]");

            if (base64_decode($this->input->post("highest_educational_level")) == 'Illiterate') {
                // $this->form_validation->set_rules("other_examination_passed", "Other Examination Passed", "required|max_length[255]");
            } else {
                $this->form_validation->set_rules("examination_passed[]", "Examination Passed", "required|max_length[255]");
                $this->form_validation->set_rules("subjects_other_subjects[]", "Subjects/ Other Subjects", "required|max_length[255]");
                $this->form_validation->set_rules("percentage_of_marks[]", "Percentage of Marks", "required|max_length[255]");
            }
        }
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->skill_education($obj_id);
            // var_dump(validation_errors());
        } else {
            if ($dbRow->form_data->type_of_reg == 4) {
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
            }
            $this->session->set_flashdata('success', 'Education & skill details has been successfully saved.');
            redirect('spservices/employment-reregistration-nonaadhaar/work-experiences/' . $objId);
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
        $this->load->view('employment_nonaadhaar/rework_experience_reg_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    // submit_work_experience
    public function submit_work_experience()
    {
        // $objId = $this->session->userdata('employment_oid');
        $this->form_validation->set_rules("obj_id", "ID", "required");
        $objId = $this->input->post("obj_id");

        $data = array(
            "obj_id" => $objId,
            "service_name" => $this->serviceName
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        // pre($this->input->post());
        if ($dbRow->form_data->type_of_reg == 4) {
            $this->form_validation->set_rules("current_employment_status", "Current Employment Status", "required|max_length[255]");
        }
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->work_experiences($obj_id);
            // var_dump(validation_errors());
        } else {
            if ($dbRow->form_data->type_of_reg == 4) {
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
            }
            redirect('spservices/employment-reregistration-nonaadhaar/slot-booking/' . $objId);
        }
    }

    // employment_exchange page view
    public function employment_exchange($objId = null)
    {
        // $objId = $this->session->userdata('employment_oid');
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
            // "employment_office" => $this->employment_office_model->get_rows(),
        );
        $data['time_slots'] = $this->mongo_db->get('timeslot_master');
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        //pre($dbRow);
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
        // pre($data);
        $this->load->view('includes/frontend/header');
        $this->load->view('employment_nonaadhaar/reemployment_exchange_reg_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    // submit_exchange_office
    public function submit_exchange_office()
    {
        $objId = $this->input->post("obj_id");
        $ref_no = $this->input->post("rtps_ref_no");
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
                redirect('spservices/employment-reregistration-nonaadhaar/enclosures/' . $objId);
            } else {
                $this->session->set_flashdata('error', 'Selected slot is not available. Please select another one.');
                $this->employment_exchange($objId);
            }
        }
    }

    public function save_timeslot($data)
    {
        $this->load->model('employment_nonaadhaar/time_slot_model');
        $this->time_slot_model->insert($data);

        // $filter = [
        //     'booking_date' => $data['booking_date'],
        //     'employment_exchange' => $data['employment_exchange'],
        // ];
        // $timeSlots = (array)$this->time_slot_model->get_rows($filter);
        // die();
        //         $master_slots = array(
        //             '1' => '10:00 am - 11:00 am',
        //             '2' => '11:00 am - 12:00 pm',
        //             '3' => '12:00 pm - 01:00 pm',
        //             '4' => '01:00 pm - 02:00 pm',
        //             '5' => '02:00 pm - 03:00 pm',
        //             '6' => '03:00 pm - 04:00 pm',
        //         );

        //         $slots = [];
        //         $y = 0;
        //         $z = 0;
        //         $a = 0;
        // pre(count($timeSlots[0]));
        //         foreach ($timeSlots as $val) {
        //             $t_slot = $val->time_slot;
        //             // if ($t_slot === '10:00 am - 11:00 am') {
        //             //     $y = $y + 1;
        //             //     $slots[$val->time_slot] = $y;
        //             // } else if ($t_slot === '11:00 am - 12:00 pm') {
        //             //     $z = $z + 1;
        //             //     $slots[$val->time_slot] = $z;
        //             // } else if ($t_slot === '12:00 pm - 01:00 pm') {
        //             //     $a = $a + 1;
        //             //     $slots[$val->time_slot] = $a;
        //             // }
        //         }


        //         pre($slots);





        //         die();
        //         $this->time_slot_model->insert($data);
        //         pre($data);
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
        $this->load->view('employment_nonaadhaar/reenclosures_reg_view', $data);
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
            redirect('spservices/employment-reregistration-nonaadhaar/preview/' . $objId);
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
        $this->load->view('employment_nonaadhaar/reregistration_preview', $data);
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
        $str = "RTPS-EMPREREGNA/" . date('Y') . "/" . $number;
        return $str;
    } //End of generateID()

    public function view($objId = null)
    {
        $dbRow = $this->employment_model->get_by_doc_id($objId);
        if ($dbRow) {
            $data["dbrow"] = $dbRow;
        } else {
            $data["dbrow"] = false;
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('employment_nonaadhaar/reregistration_view', $data);
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
            $this->load->view('employment_nonaadhaar/reregistrationtrack_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            // redirect('spservices/income/inc/');
        } //End of if else
    } 

    public function finalsubmition($objId = null)
    {
        if ($objId) {
            $dbRow = $this->employment_model->get_by_doc_id($objId);
            $unformat_date_sub = new UTCDateTime(strtotime(date('d-m-Y')) * 1000);
            $txts = explode(' ', format_mongo_date($unformat_date_sub));
            $sub_date = $txts[0];

            $processing_history = $dbRow->processing_history ?? array();
            $processing_history[] = array(
                "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                "action_taken" => "Application Submission",
                "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            );

            $this->load->model('upms/users_model');
            $userFilter = array('user_services.service_code' => $this->base_serviceId, 'user_roles.role_code' => 'EEO');
            $userRows = $this->users_model->get_rows($userFilter);

            $current_users = array();
            if ($userRows) {
                foreach ($userRows as $key => $userRow) {
                    $current_user = array(
                        'login_username' => $userRow->login_username,
                        'email_id' => $userRow->email_id,
                        'mobile_number' => $userRow->mobile_number,
                        'user_level_no' => $userRow->user_levels->level_no,
                        'user_fullname' => $userRow->user_fullname,
                    );
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
            redirect('spservices/employment-reregistration-nonaadhaar/acknowledgements/' . $objId);
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
                redirect('spservices/employment-reregistration-nonaadhaar/acknowledgements/' . $objId);
            }
        } else {
            $this->my_transactions();
        }
    }

    public function acknowledgement($objId = null)
    {
        $data = array(
            "obj_id" => $objId
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

    public function queryform($objId = null)
    {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->employment_model->get_row(array("_id" => new ObjectId($objId), "service_data.appl_status" => "QS"));
            if ($dbRow) {
                $data = array(
                    "service_data.service_name" => $this->serviceName,
                    "dbrow" => $dbRow
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('employment_nonaadhaar/reregistration_query_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {

                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('iservices/transactions');
            } //End of if else
        } else {
            echo 'invalid id';

            // $this->session->set_flashdata('error', 'Invalid application id');
            // redirect('spservices/income/inc');
        } //End of if else
    }

    private function checkObjectId($obj)
    {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        } //End of if else
    }
    
    public function checkNumeric()
    {
        $a = '45.3';
        var_dump(is_numeric($a));

        $output = preg_replace('/[^0-9]/', '', 'sdfgfd');
        pre($output);
    }

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

    // aadhaar verification 
    public function otpsend()
    {
        $txn_no = uniqid();

        $this->load->library('session');
        $this->session->set_userdata('txn_no', $txn_no);

        $aadhaar_no = $this->input->post("aadhaar_number");
        $data = array(
            "aadhaar_no" => $aadhaar_no,
            "txn_no" => $txn_no
        );
        $this->aadhaar_request_id = $this->addrecord($this->aadhaar_request_id, $this->aadhaarApi . "send", array('txn_no' => $txn_no));

        $json_obj = json_encode($data); //pre($json_obj);

        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
        );
        if (strpos(base_url(), 'localhost')) { //For local test  
            $resData = array(
                "status" => 1,
                "ret" => array("0" => "y"),
                "msg" => '',
                "txn_no" => '',
                "info" => '',
                "xml" => array()
            );
            $this->aadhaar_request_id = $this->addrecord($this->aadhaar_request_id, $this->aadhaarApi . "send", $resData);
            echo json_encode($resData);
        } else {
            $curl = curl_init($this->aadhaarApi . "send");
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);

            $result = json_decode($response);
            /*$xml = simplexml_load_string($result->status);
            $resData = array(
                "ret" => $xml->attributes()->ret,
                "txn_no" => $txn_no,
                "info" => $xml->attributes()->info,
                "xml" => $xml
            );*/
            if (isset($error_msg)) {
                $resData = array(
                    "status" => 0,
                    "ret" => array("0" => "n"),
                    "msg" => $error_msg,
                    "txn_no" => '',
                    "info" => '',
                    "xml" => array()
                );
            } else {
                $result = json_decode($response);
                $xml = simplexml_load_string($result->status);
                $this->aadhaar_request_id = $this->addrecord($this->aadhaar_request_id, $this->aadhaarApi . "send", $xml);
                $err = $xml->attributes()->err;
                $errorMessage = $this->getErrMsg($err);
                $resData = array(
                    "status" => 1,
                    "ret" => $xml->attributes()->ret,
                    "msg" => $errorMessage,
                    "txn_no" => $xml->attributes()->txn,
                    "info" => $xml->attributes()->info,
                    "xml" => $xml
                );
            } //End of if else
            echo json_encode($resData);
        } //End of if else
    } //End of otpsend()

    public function otpverify()
    {
        // $objId = $this->input->post("obj_id");        
        $aadhaar_no = $this->input->post("aadhaar_number");
        // $txn_no = $this->input->post("txn");
        $txn_no = $this->session->userdata('txn_no');

        $otp = $this->input->post("otp");
        $name = $this->input->post("name");
        $state = $this->input->post("state");
        $data = array(
            "uid" => $aadhaar_no,
            "otp" => $otp,
            "name" => $name,
            "state" => $state,
            "txn_no" => $txn_no
        );
        $this->aadhaar_request_id = $this->addrecord($this->aadhaar_request_id, $this->aadhaarApi . "encrypt", array('txn_no' => $txn_no, 'otp' => $otp));
        $json_obj = json_encode($data); //pre($json_obj);

        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
        );
        if (strpos(base_url(), 'localhost')) { //For local test            
            $resData = array(
                "status" => 1,
                "ret" => array("0" => "y"),
                "msg" => '',
                "txn_no" => '',
                "info" => '',
                "xml" => array()
            );
            $this->aadhaar_request_id = $this->addrecord($this->aadhaar_request_id, $this->aadhaarApi . "encrypt", $resData);
            // echo json_encode($resData);
            $data_to_insert = array(
                'aadhaar_no' => $aadhaar_no,
                'name' => $name,
                'aadhaar_verify_status' => 1,
                "hashed_id" => '124'
            );
            $obj_id = $this->first_reg($data_to_insert);
            $resData['obj_id'] = $obj_id;
            echo json_encode($resData);
        } else {
            $curl = curl_init($this->aadhaarApi . "encrypt");
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);
            if (isset($error_msg)) {
                $resData = array(
                    "status" => 0,
                    "ret" => array("0" => "n"),
                    "msg" => $error_msg,
                    "txn_no" => '',
                    "info" => '',
                    "xml" => array()
                );
            } else {
                $result = json_decode($response);
                $xml = simplexml_load_string($result->status);
                $this->aadhaar_request_id = $this->addrecord($this->aadhaar_request_id, $this->aadhaarApi . "encrypt", $xml);
                $err = $xml->attributes()->err;
                $errorMessage = $this->getErrMsg($err);
                $resData = array(
                    "status" => 1,
                    "ret" => $xml->attributes()->ret,
                    "msg" => $errorMessage,
                    "txn_no" => $xml->attributes()->txn,
                    "info" => $xml->attributes()->info,
                    "xml" => $xml
                );
                // if ($xml->attributes()->ret === 'y') {
                $data_to_insert = array(
                    'aadhaar_no' => $aadhaar_no,
                    'name' => $name,
                    'aadhaar_verify_status' => 1,
                    "hashed_id" => password_hash($aadhaar_no, PASSWORD_DEFAULT) //to be verify by using password_verify($aadhaar_no, $hashed_id)
                );
                $obj_id = $this->first_reg($data_to_insert);
                // pre($obj_id);
                $resData['obj_id'] = $obj_id;
                // $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data_to_update);
                // } //End of if
            } //End of if else                
            echo json_encode($resData);
        } //End of if else
    } //End of otpverify()

    function getErrMsg($errCode)
    {
        switch ($errCode) {
            case '100':
                $errMsg = "Attributes(basic) of demographic data did not match";
                break;
            case '200':
                $errMsg = "Attributes(address) of demographic data did not match";
                break;
            case 'PAYMENT_INITIATED':
                $errMsg = "PAYMENT INITIATED";
                break;
            case '331':
                $errMsg = "Aadhaar locked by Aadhaar number holder for all authentications";
                break;
            case '332':
                $errMsg = "Aadhaar number usage is blocked by Aadhaar number holder";
                break;
            case '400':
                $errMsg = "Invalid OTP value";
                break;
            case '998':
                $errMsg = "Invalid Aadhaar Number/Virtual ID.";
                break;
            default:
                $errMsg = $errCode;
                break;
        } //End of switch
        return $errMsg;
    } // End of getErrMsg()

    public function addrecord($aadhaar_request_id, $aadhaar_request_url, $aadhaar_request_content)
    {
        $data = array(
            "client_ip" => $this->input->server('REMOTE_ADDR'),
            "client_browser" => $this->agent->agent_string(),
            "logged_user_id" => $this->session->userId->{'$id'} ?? '',
            "request_url" => $aadhaar_request_url, //$this->input->server('REQUEST_URI'),
            "request_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "request_content" => $aadhaar_request_content
        );

        if (strlen($aadhaar_request_id)) {
            $data = array(
                "response_url" => $aadhaar_request_url,
                "response_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "response_content" => $aadhaar_request_content
            );
            $this->logreports_model->update_where(['_id' => new ObjectId($aadhaar_request_id)], $data);
            //$this->session->unset_userdata('aadhaar_request_id');
            return ''; //'Respond has been logged successfully';
        } else {
            $insert = $this->logreports_model->insert($data);
            //$this->session->set_userdata('aadhaar_request_id', $insert['_id']->{'$id'});
            return $insert['_id']->{'$id'}; //'Request has been logged successfully';
        } //End of if else        
        //pre($data);
    } //End of addrecord()

    public function submit_previous_service_plus_data($data, $registration_no){
        $encl_cnt=count($data->initiated_data->enclosure_details);
        // pre($data);
        $aadhaar_card='';
        $aadhaar_card_type='';
        $age_proof='';
        $age_proof_type='';
        $educational_qualification='';
        $educational_qualification_type='';
        $caste_certificate='';
        $caste_certificate_type='';
        $proof_of_residence='';
        $proof_of_residence_type='';
        $any_other_document='';
        $any_other_document_type='';
        $ex_servicemen_certificate='';
        $ex_servicemen_certificate_type='';
        $noc_from_current_employeer='';
        $noc_from_current_employeer_type='';
        $other_qualification_certificate='';
        $other_qualification_certificate_type='';
        $passport_photo=isset($data->initiated_data->attribute_details->passport_photo)?$data->initiated_data->attribute_details->passport_photo:'';;
        $passport_photo_type='';
        $previous_employment='';
        $previous_employment_type='';
        $pwd_certificate='';
        $pwd_certificate_type='';
        $signature_photo=isset($data->initiated_data->attribute_details->signature)?$data->initiated_data->attribute_details->signature:'';
        $work_experience=''; 

        for ($x = 0; $x < $encl_cnt; $x++) {
            $data1=$data->initiated_data->enclosure_details[$x];
            $result = [];
            $i=0;
            foreach ($data1 as $key => $value){
                $result[$i] = $value;
                $i=$i+1;
            }
            $name=$result[0];

            if (strpos($name, "Proof of Residence") !== false) {
                $proof_of_residence=$result[2];
                $proof_of_residence_type=$result[1];
            }
            else if(strpos($name,"Age Proof")!== false){
                $age_proof=$result[2];
                $age_proof_type=$result[1];
            }
            else if(strpos($name,"Educational Qualification certificate")!==false){
                $educational_qualification=$result[2];
                $educational_qualification_type=$result[1];
            }
            else if(strpos($name,"Copy of caste certificate")!==false){
                $caste_certificate=$result[2];
                $caste_certificate_type=$result[1];
            }
            else if(strpos($name,"Aadhaar Card")!==false){
                $aadhaar_card=$result[2];
                $aadhaar_card_type=$result[1];
            }
            else if(strpos($name,"Previous employment certificates")!==false){
                $previous_employment=$result[2];
                $previous_employment_type=$result[1];
            }

            else if(strpos($name,"Persons with disability certificate")!==false){
                $pwd_certificate=$result[2];
                $pwd_certificate_type=$result[1];
            }

            else if(strpos($name,"Ex-servicemen certificate")!==false){
                $ex_servicemen_certificate=$result[2];
                $ex_servicemen_certificate_type=$result[1];
            }

            else if(strpos($name,"Any other document")!==false){
                $any_other_document=$result[2];
                $any_other_document_type=$result[1];
            }

            else if(strpos($name,"Work experience")!==false){
                $work_experience=$result[2];
                $work_experience_type=$result[1];
            }

            else if(strpos($name,"Other Qualifications/Trainings/Courses Certificate")!==false){
                $other_qualification_certificate=$result[2];
                $other_qualification_certificate_type=$result[1];
            }

            else if(strpos($name,"Registration Card")!==false){
                $educational_qualification=$result[2];
                $educational_qualification_type=$result[1];
            }

            else if(strpos($name,"NOC from Current Employer")!==false){
                $noc_from_current_employeer=$result[2];
                $noc_from_current_employeer_type=$result[1];
            }
        }

        $enclosures = array(
            "aadhaar_card" => $aadhaar_card,
            "aadhaar_card_type" => $aadhaar_card_type,
            "age_proof" => $age_proof,
            "age_proof_type" => $age_proof_type,
            "educational_qualification"=>$educational_qualification,
            "educational_qualification_type"=>$educational_qualification_type,
            "caste_certificate"=>$caste_certificate,
            "caste_certificate_type"=>$caste_certificate_type,
            "proof_of_residence"=>$proof_of_residence,
            "proof_of_residence_type"=>$proof_of_residence_type,
            "any_other_document"=>$any_other_document,
            "any_other_document_type"=>$any_other_document_type,
            "ex_servicemen_certificate"=>$ex_servicemen_certificate,
            "ex_servicemen_certificate_type"=>$ex_servicemen_certificate_type,
            "noc_from_current_employeer"=>$noc_from_current_employeer,
            "noc_from_current_employeer_type"=>$noc_from_current_employeer_type,
            "other_qualification_certificate"=>$other_qualification_certificate,
            "other_qualification_certificate_type"=>$other_qualification_certificate_type,
            "passport_photo"=>$passport_photo,
            "passport_photo_type"=>$passport_photo_type,
            "previous_employment"=>$previous_employment,
            "previous_employment_type"=>$previous_employment_type,
            "pwd_certificate"=>$pwd_certificate,
            "pwd_certificate_type" => $pwd_certificate_type,
            "signature_photo" => $signature_photo,
            "signature_photo_type" => "Signature",
            "work_experience" => $work_experience,
            "work_experience_type" => "Work Experience Certificate",
        );

        $applicant_name  =$data->initiated_data->attribute_details->applicant_name;
        $applicant_gender=$data->initiated_data->attribute_details->applicant_gender;
        $mobile_number   =$data->initiated_data->attribute_details->mobile_number;
        $e_mail =$data->initiated_data->attribute_details->{'e-mail'};
        $fathers_name =$data->initiated_data->attribute_details->fathers_name;
        $caste =$data->initiated_data->attribute_details->caste;
        $mothers_name =$data->initiated_data->attribute_details->mothers_name;
        $date_of_birth=$data->initiated_data->attribute_details->date_of_birth;
        $whether_ex_servicemen=$data->initiated_data->attribute_details->{'whether_ex-servicemen'};
        $husbands_name=isset($data->initiated_data->attribute_details->husbands_name)?$data->initiated_data->attribute_details->husbands_name:'';
        
        $category_of_ex_servicemen=isset($data->initiated_data->attribute_details->{'category_of_ex-servicemen'})? $data->initiated_data->attribute_details->{'category_of_ex-servicemen'}:'';

        $religion=$data->initiated_data->attribute_details->religion;
        $marital_status=$data->initiated_data->attribute_details->marital_status;
        $occupation=$data->initiated_data->attribute_details->occupation;

        $unique_identification_no=isset($data->initiated_data->attribute_details->unique_identification_no)?$data->initiated_data->attribute_details->unique_identification_no:'';

        $unique_identification_type=isset($data->initiated_data->attribute_details->unique_identification_type)?$data->initiated_data->attribute_details->unique_identification_type:'';

        $prominent_identification_mark=$data->initiated_data->attribute_details->prominent_identification_mark;
        $height__in_cm=isset($data->initiated_data->attribute_details->height__in_cm)?$data->initiated_data->attribute_details->height__in_cm:'';
        $weight__kgs=isset($data->initiated_data->attribute_details->weight__kgs)?$data->initiated_data->attribute_details->weight__kgs:'';
        $eye_sight=isset($data->initiated_data->attribute_details->eye_sight)?$data->initiated_data->attribute_details->eye_sight:'';
        $chest__inch=isset($data->initiated_data->attribute_details->chest__inch)?$data->initiated_data->attribute_details->chest__inch:'';
        $are_you_differently_abled__pwd=isset($data->initiated_data->attribute_details->are_you_differently_abled__pwd)?$data->initiated_data->attribute_details->are_you_differently_abled__pwd:'';
        $disability_category=isset($data->initiated_data->attribute_details->disability_category)?$data->initiated_data->attribute_details->disability_category:'';
        $additional_disability_type=isset($data->initiated_data->attribute_details->additional_disability_type)?$data->initiated_data->attribute_details->additional_disability_type:'';
        $disbility_percentage=isset($data->initiated_data->attribute_details->disbility_percentage)?$data->initiated_data->attribute_details->disbility_percentage:'';
        //page 2
        $name_of_the_house_apartment_p=isset($data->initiated_data->attribute_details->name_of_the_house_apartment_p)?$data->initiated_data->attribute_details->name_of_the_house_apartment_p:'';
        $house_no_apartment_no_p=isset($data->initiated_data->attribute_details->house_no_apartment_no_p)?$data->initiated_data->attribute_details->house_no_apartment_no_p:'';
        $building_no_block_no__p=isset($data->initiated_data->attribute_details->building_no_block_no__p)?$data->initiated_data->attribute_details->building_no_block_no__p:'';
        $address__locality_street_etc___p=isset($data->initiated_data->attribute_details->address__locality_street_etc___p)?$data->initiated_data->attribute_details->address__locality_street_etc___p:'';
        $vill_town_ward_city_p=isset($data->initiated_data->attribute_details->vill_town_ward_city_p)?$data->initiated_data->attribute_details->vill_town_ward_city_p:'';
        $post_office_p=isset($data->initiated_data->attribute_details->post_office_p)?$data->initiated_data->attribute_details->post_office_p:'';
        $police_station_p=isset($data->initiated_data->attribute_details->police_station_p)? $data->initiated_data->attribute_details->police_station_p:'';
        $pin_code_p=isset($data->initiated_data->attribute_details->pin_code_p)?$data->initiated_data->attribute_details->pin_code_p:'';
        $revenue_circle=isset($data->initiated_data->attribute_details->revenue_circle)?$data->initiated_data->attribute_details->revenue_circle:'';
        $sub_division=isset($data->initiated_data->attribute_details->sub_division)?$data->initiated_data->attribute_details->sub_division:'';
        $residence=isset($data->initiated_data->attribute_details->residence)?$data->initiated_data->attribute_details->residence:'';
        $district_p=isset($data->initiated_data->attribute_details->district_p)?$data->initiated_data->attribute_details->district_p:'';

        $same_as_permanent_address=isset($data->initiated_data->attribute_details->same_as_permanent_address)? $data->initiated_data->attribute_details->same_as_permanent_address:'';

        $name_of_the_house_apartment=isset($data->initiated_data->attribute_details->name_of_the_house_apartment)?$data->initiated_data->attribute_details->name_of_the_house_apartment:'';
        $house_no_apartment_no=isset($data->initiated_data->attribute_details->house_no_apartment_no)?$data->initiated_data->attribute_details->house_no_apartment_no:'';
        $building_no_block_no=isset($data->initiated_data->attribute_details->building_no_block_no)?$data->initiated_data->attribute_details->building_no_block_no:'';
        $address__locality_street_etc=isset($data->initiated_data->address__locality_street_etc)?$data->initiated_data->attribute_details->address__locality_street_etc:'';
        $vill_town_ward_city=isset($data->initiated_data->attribute_details->vill_town_ward_city)?$data->initiated_data->attribute_details->vill_town_ward_city:'';
        $post_office=isset($data->initiated_data->attribute_details->post_office)?$data->initiated_data->attribute_details->post_office:'';
        $police_station=isset($data->initiated_data->attribute_details->police_station)?$data->initiated_data->attribute_details->police_station:'';
        $pin_code=isset($data->initiated_data->attribute_details->pin_code)?$data->initiated_data->attribute_details->pin_code:'';
        $district=isset($data->initiated_data->attribute_details->district)?$data->initiated_data->attribute_details->district:'';

        //page 3
        $highest_educational_level=isset($data->initiated_data->attribute_details->highest_educational_level)?$data->initiated_data->attribute_details->highest_educational_level:'';
        $highest_examination_passed=isset($data->initiated_data->attribute_details->highest_examination_passed)?$data->initiated_data->attribute_details->highest_examination_passed:'';

        $edu_cnt=isset($data->initiated_data->attribute_details->education_qualification)?count($data->initiated_data->attribute_details->education_qualification):0;

        if($edu_cnt > 0){
            // $examination_passed_arr=$data->initiated_data->attribute_details->education_qualification;
            $examination_passed_arr=[];
            for ($i = 0; $i < $edu_cnt; $i++) {
                $examination_passed_arr[] = [
                    "examination_passed" => $data->initiated_data->attribute_details->education_qualification[$i]->examination_passed,
                    "other_examination_name" => $data->initiated_data->attribute_details->education_qualification[$i]->other_examination_name,
                    "major__elective_subject" => $data->initiated_data->attribute_details->education_qualification[$i]->major__elective_subject,
                    "subjects__other_subjects" => $data->initiated_data->attribute_details->education_qualification[$i]->subjects__other_subjects,
                    "board__university" => $data->initiated_data->attribute_details->education_qualification[$i]->board__university,
                    "institution__school__college" => $data->initiated_data->attribute_details->education_qualification[$i]->institution__school__college,
                    "date_of_passing" => $data->initiated_data->attribute_details->education_qualification[$i]->date_of_passing,
                    "registration_no" => $data->initiated_data->attribute_details->education_qualification[$i]->reg__no,
                    "percentage_of_marks" => $data->initiated_data->attribute_details->education_qualification[$i]->percentage_of_marks,
                    "class__division" => $data->initiated_data->attribute_details->education_qualification[$i]->class__division,
                ];
            }
        }

        $oedu_cnt=isset($data->initiated_data->attribute_details->other_qualification_trainings_courses)?count($data->initiated_data->attribute_details->other_qualification_trainings_courses):0;

        if($oedu_cnt > 0){
            $other_qualification_trainings_courses=$data->initiated_data->attribute_details->other_qualification_trainings_courses;
        }

        $skill_cnt=isset($data->initiated_data->attribute_details->skill_qualification)?count($data->initiated_data->attribute_details->skill_qualification):0;

        if($skill_cnt > 0){
            $skill_qualification=$data->initiated_data->attribute_details->skill_qualification;
        }
        
        $work_cnt=isset($data->initiated_data->attribute_details->work_experience)?count($data->initiated_data->attribute_details->work_experience):0;
        
        if($work_cnt > 0){
            $work_experience=$data->initiated_data->attribute_details->work_experience;
        }
        
        $submission_location=$data->initiated_data->submission_location;
        $employment_exchange=$data->initiated_data->submission_location;
        //todo
        $job_preference_key_skills='';
        $current_employment_status='';
        $years='';
        $months='';

        //enclosure

        $appl_ref_no_temp = $this->getID(7);
        $sessionUser=$this->session->userdata();
        $created_at = getISOTimestamp();

        if($this->slug === "CSC"){
            $apply_by = $sessionUser['userId'];
        } else {
            $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        }//End of if else

        while(1){
            $app_id = rand(100000000, 999999999);
            $filter = array( 
                "service_data.appl_id" => $app_id,
                //"service_data.service_id" => $this->serviceId
            );
            $rows = $this->employment_model->get_row($filter);
            
            if($rows == false)
                break;
        }

        $first_data = array(
            "department_id" => $this->departmentId,
            "department_name" => $this->departmentName,
            "service_id" => $this->base_serviceId,
            "service_name" => $this->serviceName,
            "appl_id" => $app_id,
            "appl_ref_no" => $appl_ref_no_temp,
            "submission_mode" => "",
            "applied_by" => $apply_by,
            "submission_location" => $employment_exchange,
            "submission_date" => "",
            "service_timeline" => "30 Days",
            "appl_status" => "DRAFT",
            "district" => "",
        );

        $data2 = array(
            "user_type" => $this->slug,
            "applied_user_type" => $this->slug,
            "service_name" => $this->serviceName,
            "service_id" => $this->serviceId,
            "rtps_trans_id" => $appl_ref_no_temp,
            "full_name_as_in_aadhaar_card" => "testname",
            "applicant_name" => "testname",
            "state__only_domicile_of_assam_can_apply" => 'Assam',
            "hashed_id" => "",
            "additional_disability_type"=>$additional_disability_type,
            "applicant_gender"=>$applicant_gender,
            "are_you_differently_abled__pwd"=>$are_you_differently_abled__pwd,
            "caste"=>$caste,
            "category_of_ex-servicemen"=>$category_of_ex_servicemen,
            "chest__inch"=>$chest__inch,
            "mothers_name"=>$mothers_name,
            "e-mail"=>$e_mail,
            "mobile_number"=>$mobile_number,
            "mobile"=>$mobile_number,
            "fathers_name"=>$fathers_name,
            "date_of_birth"=>$date_of_birth,
            "whether_ex-servicemen"=>$whether_ex_servicemen,
            "husbands_name"=>$husbands_name,
            "religion"=>$religion,
            "marital_status"=>$marital_status,
            "occupation"=>$occupation,
            "unique_identification_no"=>$unique_identification_no,
            "unique_identification_type"=>$unique_identification_type,
            "prominent_identification_mark"=>$prominent_identification_mark,
            "height__in_cm"=>$height__in_cm,
            "weight__kgs"=>$weight__kgs,
            "eye_sight"=>$eye_sight,
            "disability_category"=>$disability_category,
            "disbility_percentage"=>$disbility_percentage,
            "name_of_the_house_apartment_p"=>$name_of_the_house_apartment_p,
            "house_no_apartment_no_p"=>$house_no_apartment_no_p,
            "building_no_block_no__p"=>$building_no_block_no__p,
            "address__locality_street_etc___p"=>$address__locality_street_etc___p,
            "vill_town_ward_city_p"=>$vill_town_ward_city_p,
            "post_office_p"=>$post_office_p,
            "police_station_p"=>$police_station_p,
            "pin_code_p"=>$pin_code_p,
            "revenue_circle"=>$revenue_circle,
            "sub_division"=>$sub_division,
            "residence"=>$residence,
            "district_p"=>$district_p,
            "same_as_permanent_address"=>$same_as_permanent_address,
            "name_of_the_house_apartment"=>$name_of_the_house_apartment,
            "house_no_apartment_no"=>$house_no_apartment_no,
            "building_no_block_no"=>$building_no_block_no,
            "address__locality_street_etc"=>$address__locality_street_etc,
            "vill_town_ward_city"=>$vill_town_ward_city,
            "post_office"=>$post_office,
            "police_station"=>$police_station,
            "pin_code"=>$pin_code,
            "district"=>$district,
            "highest_educational_level"=>$highest_educational_level,
            "highest_examination_passed"=>$highest_examination_passed,
            "education_qualification"=>$examination_passed_arr,
            "other_qualification_trainings_courses"=>$other_qualification_trainings_courses,
            "skill_qualification"=>$skill_qualification,
            "work_experience"=>$work_experience,
            "submission_location"=>"",
            "job_preference_key_skills"=>$job_preference_key_skills,
            "current_employment_status"=>$current_employment_status,
            "years"=>$years,
            "months"=>$months,
            "employment_exchange"=>$employment_exchange,
            "enclosures"=>$enclosures,
            "registration_no"=>$registration_no,
        );

        $data = array('service_data' => $first_data, 'form_data' => $data2);
        // pre($data);
        $insert = $this->employment_model->insert($data);
        if ($insert) {
            return $insert['_id']->{'$id'};
        }else{
            return false;
        }
    }
}
