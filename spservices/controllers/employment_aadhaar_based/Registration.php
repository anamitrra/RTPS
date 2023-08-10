<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps
{

    private $serviceName = "Registration of employment seeker in Employment Exchange for AADHAAR Based";
    private $serviceId = "EMP_A_REG";
    private $aadhaarApi, $aadhaar_request_id;


    public function __construct()
    {
        parent::__construct();
        $this->load->model('employment_aadhaar_based/employment_model');
        $this->load->model('employment_aadhaar_based/district_model');
        $this->load->model('employment_aadhaar_based/sub_division_model');
        $this->load->model('employment_aadhaar_based/revenue_circle_model');
        $this->load->model('employment_aadhaar_based/functional_roles_model');
        $this->load->model('employment_aadhaar_based/functional_area_model');
        $this->load->model('employment_aadhaar_based/industry_sector_model');
        $this->load->model('employment_aadhaar_based/employment_office_model');
        $this->load->model('employment_aadhaar_based/highest_examination_passed_model');
        $this->load->model('employment_aadhaar_based/examination_passed_model');
        $this->load->model('employment_aadhaar_based/check_aadhaar_model');

        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper('smsprovider');
        $this->load->helper("employmentcertificate");
        $this->load->helper("dateformat");

        // $this->config->load('spservices/spconfig');
        $this->aadhaarApi = $this->config->item('aadhaar_authentication_api');
        $this->load->model('employment_aadhaar_based/logreports_model');
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
        //echo $data["form_status"];      
        //die;
        $this->load->view('includes/frontend/header');
        $this->load->view('employment_aadhaar_based/registration', $data);
        $this->load->view('includes/frontend/footer');
    } //End of index()

    public function first_reg($data_to_insert = null)
    {
        if ($this->slug === "CSC") {
            $apply_by = $sessionUser['userId'];
        } else {
            $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        }

        $appl_ref_no_temp = $this->getID(7);


        $created_at = getISOTimestamp();
        $first_data = array(
            "service_id" => "1859",
            "appl_ref_no" => $appl_ref_no_temp,
            "appl_status" => "DRAFT",
            "created_at" => $created_at,
            "department_name" => "Department of Skill, Employment and Entrepreneurship",
            "department_id" => "2193",
            "service_name" => $this->serviceName,
            "submission_mode" => "online",
            "applied_by" => $apply_by,
            "service_timeline" => "1 Days"
        );
        $data2 = array(
            "user_type" => $this->slug,
            "applied_user_type" => $this->slug,
            "service_name" => $this->serviceName,
            "service_id" => $this->serviceId,
            "rtps_trans_id" => $appl_ref_no_temp,
            "full_name_as_in_aadhaar_card" => $data_to_insert['name'],
            "applicant_name" => $data_to_insert['name'],
            "state__only_domicile_of_assam_can_apply" => 'Assam',
            "aadhaar_verify_status" => $data_to_insert['aadhaar_verify_status'],
            "hashed_id" => $data_to_insert['hashed_id'],
        );
        //pre($first_data)
        $data = array('service_data' => $first_data, 'form_data' => $data2);
        $insert = $this->employment_model->insert($data);
        $obj_id = $insert['_id']->{'$id'};
        $this->session->set_userdata("employment_oid", $obj_id);

        return $obj_id;
        // testing 
        //redirect('spservices/employment-registration/personal-details/' . $obj_id);
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
        $str = "RTPS-REESA/" . date('Y') . "/" . $number;
        return $str;
    } //End of generateID()

    public function personal_details($objId = null)
    {
        $this->load->model('employment_aadhaar_based/disability_type_model');
        $this->load->model('employment_aadhaar_based/disability_category_model');
        // $objId = $this->session->userdata('employment_oid');
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
            "disability_categories" => $this->disability_category_model->get_rows(),
            "disability_types" => $this->disability_type_model->get_rows(),
            // "disability_categories" => (array)$this->mongo_db->order_by('created_at', 'DESC')->get('disability_categories'),
            // "disability_types" => (array)$this->mongo_db->order_by('created_at', 'DESC')->get('disability_types')
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
        $this->load->view('employment_aadhaar_based/personal_details_view', $data);
        $this->load->view('includes/frontend/footer');
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
        $this->load->view('employment_aadhaar_based/registration_preview', $data);
        $this->load->view('includes/frontend/footer');
    }

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
        $this->load->view('employment_aadhaar_based/registration_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    /**
     *  final submission of the application
     * 
     */
    public function finalsubmition($objId = null)
    {
        if ($objId) {
            $curr_timestamp = getCurrentTimestamp();
            $date_ms = strtotime($curr_timestamp);
            $renew_date = new MongoDB\BSON\UTCDateTime(strtotime("+3 year", $date_ms) * 1000);

            //get registration number production
            $url = $this->config->item('emp_certificate_no_url');
            //test
            //$url = 'http://localhost/empex-api/cert_no.php';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($ch);
            $response = json_decode($response);
            curl_close($ch);

            // $unformat_date_sub = new UTCDateTime(strtotime(date('d-m-Y')) * 1000);
            // $txts = explode(' ', format_mongo_date($unformat_date_sub));
            // $sub_date = $txts[0];
            $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
            $hash_code = $dbRow->form_data->hashed_id;
            $employment_exchange = $dbRow->form_data->employment_exchange;


            $district = $this->employment_model->get_district_from_eeo($employment_exchange);

            $dateTime = getISOTimestamp();

            $data_to_update = array(
                'service_data.appl_status' => 'D',
                'service_data.submission_date' => $dateTime,
                'service_data.submission_location' =>  $employment_exchange,
                'service_data.district' =>  $district,
                'form_data.renewal_date' => $renew_date,
                'form_data.registration_no' => $response->cert_no,
                'form_data.submission_date' => $dateTime,
            );

            $this->employment_model->update($objId, $data_to_update);

            $filePath = $this->save_certificate($objId);

            $filepath_to_update = array(
                'form_data.output_certificate' => $filePath,
                'form_data.certificate' => $filePath,
            );
            $this->employment_model->update($objId, $filepath_to_update);
            //----------------to insert Aadhaar hash----------------
            $filter = array();
            $res = $this->check_aadhaar_model->last_where($filter);
            $ret_id = $res->id;
            $new_id = (int)$ret_id + 1;

            if ($hash_code != '') {
                $hash_data_to_update = array(
                    'id' => $new_id,
                    'hash_value' => $hash_code,
                );
                $this->check_aadhaar_model->insert($hash_data_to_update);
            }
            //-------------------------------------------------------
            redirect('spservices/employment-registration/generate_certificate/' . $objId);
        }
    }
    /**
     * save certificate to storage
     * 
     */
    public function save_certificate($objId = null)
    {
        $this->load->library("ciqrcode");
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["response"] = $this->employment_model->get_by_doc_id($objId);
            $html = $this->load->view('employment_aadhaar_based/output_certificate', $data, true);
            $this->load->library('pdf');
            $fullPath = $this->pdf->get_pdf($html, 'EMP', str_replace('/', '-', $dbRow->form_data->rtps_trans_id));
            $fileName = str_replace('/', '-', $dbRow->form_data->rtps_trans_id);
            $filePath = 'storage/docs/EMP';
            return $filePath . '/' . $fileName . '.pdf';
        } else {
            return null;
        }
    }
    //manual not to use
    public function save_certificate_manual($objId = null)
    {

        $this->load->library("ciqrcode");
        // if registtaion no is not generated uncomment below code
        /*
        $url = $this->config->item('emp_certificate_no_url');
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($ch);
            $response = json_decode($response);
            curl_close($ch);

            $data_to_update = array(
                'form_data.registration_no' => $response->cert_no,
            );

            $this->employment_model->update($objId, $data_to_update);
        */
        //ends here

        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["response"] = $this->employment_model->get_by_doc_id($objId);
            //pre($data);
            $html = $this->load->view('employment_aadhaar_based/output_certificate', $data, true);
            $this->load->library('pdf');
            $fullPath = $this->pdf->get_pdf($html, 'EMP', str_replace('/', '-', $dbRow->form_data->rtps_trans_id));
            $fileName = str_replace('/', '-', $dbRow->form_data->rtps_trans_id);
            $filePath = 'storage/docs/EMP';
            pre($filePath . '/' . $fileName . '.pdf');
            return $filePath . '/' . $fileName . '.pdf';
        } else {
            return null;
        }
    }
    //

    /**
     * generate output certificate 
     * 
     */
    public function generate_certificate($objId = null)
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
        $this->load->view('employment_aadhaar_based/ack', $data);
        $this->load->view('includes/frontend/footer');
    }

    // submit_personal_details
    public function submit_personal_details()
    {
        // $objId = $this->session->userdata('employment_oid');
        $objId = $this->input->post("obj_id");
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
            $this->personal_details($obj_id);
            // var_dump(validation_errors());
        } else {
            $data = array(
                // "full_name_as_in_aadhaar_card" => $this->input->post("full_name_as_in_aadhaar_card"),
                // "form_data.applicant_name" => $this->input->post("applicant_name"),
                "form_data.applicant_gender" => $this->input->post("applicant_gender"),
                "form_data.mobile_number" => $this->input->post("mobile_number"),
                "form_data.mobile" => $this->input->post("mobile_number"),
                "form_data.mobile_verify_status" => $this->input->post("mobile_verify_status"),
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
                $data['form_data.economically_weaker_section'] = '';
            } else {
                $data['form_data.economically_weaker_section'] = $this->input->post("economically_weaker_section");
            }
            if ($this->input->post("whether_ex_servicemen") != 'Yes') {
                $data['form_data.category_of_ex-servicemen'] = '';
            } else {
                $data['form_data.category_of_ex-servicemen'] = $this->input->post("ex_servicemen_category");
            }
            if ($this->input->post("are_you_differently_abled_pwd") == 'No') {
                $data['form_data.disability_category'] = '';
                $data['form_data.additional_disability_type'] = '';
                $data['form_data.disbility_percentage'] = '';
            } else {
                $data['form_data.disability_category'] = $this->input->post("disability_category");
                $data['form_data.additional_disability_type'] = $this->input->post("additional_disability_type");
                $data['form_data.disbility_percentage'] = $this->input->post("disbility_percentage");
            }
            // pre($data);
            $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success', 'Personal Details has been successfully saved.');

            redirect('spservices/employment-registration/address-section/' . $objId);
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
        $this->load->view('employment_aadhaar_based/address_view', $data);
        $this->load->view('includes/frontend/footer');
    }
    // submit_address
    public function submit_address()
    {
        // $objId = $this->session->userdata('employment_oid');
        $objId = $this->input->post("obj_id");
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
            $this->session->set_flashdata('success', 'Address has been successfully saved.');
            redirect('spservices/employment-registration/education-skill-details/' . $objId);
        }
    }

    // skill and educational page view
    public function skill_education($objId = null)
    {
        $this->load->model('employment_aadhaar_based/highest_educational_level_model');
        // $objId = $this->session->userdata('employment_oid');
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
        // pre($data);
        $this->load->view('includes/frontend/header');
        $this->load->view('employment_aadhaar_based/skill_education_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    // submit_education_details
    public function submit_education_details()
    {
        $unset_data = '';
        $objId = $this->input->post("obj_id");
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
            $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->unsetdata($objId, $unset_data);
            $this->session->set_flashdata('success', 'Education & skill details has been successfully saved.');
            redirect('spservices/employment-registration/work-experiences/' . $objId);
        }
    }

    // skill and educational page view
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
        $this->load->view('employment_aadhaar_based/work_experience_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    // submit_work_experience
    public function submit_work_experience()
    {
        // $objId = $this->session->userdata('employment_oid');
        $objId = $this->input->post("obj_id");
        // pre($this->input->post());
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
            redirect('spservices/employment-registration/employment-exchange/' . $objId);
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
        //pre($data);
        $this->load->view('includes/frontend/header');
        $this->load->view('employment_aadhaar_based/employment_exchange_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    // submit_exchange_office
    public function submit_exchange_office()
    {
        // $objId = $this->session->userdata('employment_oid');
        $objId = $this->input->post("obj_id");


        

        $this->form_validation->set_rules("employment_exchange", "Employment Exchange", "required|max_length[255]");
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->employment_exchange($obj_id);
            // var_dump(validation_errors());
        } else {
            $data = array(
                "form_data.employment_exchange" => $this->input->post("employment_exchange"),
            );
            // pre($data);
            $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
            redirect('spservices/employment-registration/enclosures/' . $objId);
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
        $this->load->view('employment_aadhaar_based/enclosures_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_enclosures()
    {
        // pre($_FILES);
        $objId = $this->input->post("obj_id");
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        $this->form_validation->set_rules('declaration', 'Declaration', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('proof_of_residence_type', 'Proof of Residence', 'trim|required|xss_clean|strip_tags');
        // if (empty($_FILES['proof_of_residence']['name'])) {
        //     $this->form_validation->set_rules('proof_of_residence', 'Proof of Residence File File', 'trim|required|xss_clean|strip_tags');
        // }
        if (strlen($this->input->post("proof_of_residence_old")) > 0 || (!empty($_FILES['proof_of_residence']['name'])) || (!empty($this->input->post("proof_of_residence_temp")))) {
        } else {
            $this->form_validation->set_rules('proof_of_residence', 'Proof of Residence File File', 'trim|required|xss_clean|strip_tags');
        }

        $this->form_validation->set_rules('age_proof_type', 'Age Proof', 'trim|required|xss_clean|strip_tags');
        if (strlen($this->input->post("age_proof_old")) > 0 || (!empty($_FILES['age_proof']['name'])) || (!empty($this->input->post("age_proof_temp")))) {
        } else {
            $this->form_validation->set_rules('age_proof', 'Age Proof File', 'trim|required|xss_clean|strip_tags');
        }

        // if (empty($_FILES['age_proof']['name'])) {
        //     $this->form_validation->set_rules('age_proof', 'Age Proof File', 'trim|required|xss_clean|strip_tags');
        // }
        // if ($dbRow->form_data->current_employment_status === 'Employed - Fulltime Govt.') {
        //     $this->form_validation->set_rules('noc_from_current_employeer_type', 'NOC from current employeer', 'trim|required|xss_clean|strip_tags');
        //     if (empty($_FILES['noc_from_current_employeer']['name'])) {
        //         $this->form_validation->set_rules('noc_from_current_employeer', 'NOC from current employeer File', 'trim|required|xss_clean|strip_tags');
        //     }
        // }
        if ($dbRow->form_data->caste != 'General') {
            $this->form_validation->set_rules('caste_certificate_type', 'Caste certificate', 'trim|required|xss_clean|strip_tags');
            // if (empty($_FILES['caste_certificate']['name'])) {
            //     $this->form_validation->set_rules('caste_certificate', 'Caste certificate File', 'trim|required|xss_clean|strip_tags');
            // }
            if (strlen($this->input->post("caste_certificate_old")) > 0 || (!empty($_FILES['caste_certificate']['name'])) || (!empty($this->input->post("caste_certificate_temp")))) {
            } else {
                $this->form_validation->set_rules('caste_certificate', 'Caste certificate File', 'trim|required|xss_clean|strip_tags');
            }
        } elseif ($dbRow->form_data->economically_weaker_section === 'Yes') {
            $this->form_validation->set_rules('caste_certificate_type', 'Caste certificate', 'trim|required|xss_clean|strip_tags');
            if (strlen($this->input->post("caste_certificate_old")) > 0 || (!empty($_FILES['caste_certificate']['name'])) || (!empty($this->input->post("caste_certificate_temp")))) {
            } else {
                $this->form_validation->set_rules('caste_certificate', 'Caste certificate File', 'trim|required|xss_clean|strip_tags');
            }
        }
        if ($dbRow->form_data->highest_educational_level != 'Illiterate') {
            $this->form_validation->set_rules('educational_qualification_type', 'Educational qualification', 'trim|required|xss_clean|strip_tags');
            // if (empty($_FILES['educational_qualification']['name'])) {
            //     $this->form_validation->set_rules('educational_qualification', 'Educational qualification File', 'trim|required|xss_clean|strip_tags');
            // }
            if (strlen($this->input->post("educational_qualification_old")) > 0 || (!empty($_FILES['educational_qualification']['name'])) || (!empty($this->input->post("educational_qualification_temp")))) {
            } else {
                $this->form_validation->set_rules('educational_qualification', 'Educational qualification File', 'trim|required|xss_clean|strip_tags');
            }
        }


        // $this->form_validation->set_rules('other_qualification_certificate_type', 'Other qualification certificate', 'trim|required|xss_clean|strip_tags');
        // // if (empty($_FILES['other_qualification_certificate']['name'])) {
        // //     $this->form_validation->set_rules('other_qualification_certificate', 'Other qualification certificate File', 'trim|required|xss_clean|strip_tags');
        // // }

        // $this->form_validation->set_rules('previous_employment_type', 'Previous employment', 'trim|required|xss_clean|strip_tags');
        // // if (empty($_FILES['previous_employment']['name'])) {
        // //     $this->form_validation->set_rules('previous_employment', 'Previous employment File', 'trim|required|xss_clean|strip_tags');
        // // }
        if ($dbRow->form_data->are_you_differently_abled__pwd === 'Yes') {
            $this->form_validation->set_rules('pwd_certificate_type', 'PWD certificate', 'trim|required|xss_clean|strip_tags');
            // if (empty($_FILES['pwd_certificate']['name'])) {
            //     $this->form_validation->set_rules('pwd_certificate', 'PWD certificate File', 'trim|required|xss_clean|strip_tags');
            // }
            if (strlen($this->input->post("pwd_certificate_old")) > 0 || (!empty($_FILES['pwd_certificate']['name'])) || (!empty($this->input->post("pwd_certificate_temp")))) {
            } else {
                $this->form_validation->set_rules('pwd_certificate', 'PWD certificate File', 'trim|required|xss_clean|strip_tags');
            }
        }

        if ($dbRow->form_data->{'whether_ex-servicemen'} === 'Yes') {
            $this->form_validation->set_rules('ex_servicemen_certificate_type', 'Ex-servicemen certificate', 'trim|required|xss_clean|strip_tags');
            // if (empty($_FILES['ex_servicemen_certificate']['name'])) {
            //     $this->form_validation->set_rules('ex_servicemen_certificate', 'Ex-servicemen certificate File', 'trim|required|xss_clean|strip_tags');
            // }
            if (strlen($this->input->post("ex_servicemen_certificate_old")) > 0 || (!empty($_FILES['ex_servicemen_certificate']['name'])) || (!empty($this->input->post("ex_servicemen_certificate_temp")))) {
            } else {
                $this->form_validation->set_rules('ex_servicemen_certificate', 'Ex-servicemen certificate File', 'trim|required|xss_clean|strip_tags');
            }
        }


        // $this->form_validation->set_rules('work_experience_type', 'Work experience', 'trim|required|xss_clean|strip_tags');
        // // if (empty($_FILES['work_experience']['name'])) {
        // //     $this->form_validation->set_rules('work_experience', 'Work experience File', 'trim|required|xss_clean|strip_tags');
        // // }

        // $this->form_validation->set_rules('any_other_document_type', 'Any other document', 'trim|required|xss_clean|strip_tags');
        // // if (empty($_FILES['any_other_document']['name'])) {
        // //     $this->form_validation->set_rules('any_other_document', 'Any other document File', 'trim|required|xss_clean|strip_tags');
        // // }

        $this->form_validation->set_rules('aadhaar_card_type', 'Aadhaar card', 'trim|required|xss_clean|strip_tags');
        // if (empty($_FILES['aadhaar_card']['name'])) {
        //     $this->form_validation->set_rules('aadhaar_card', 'Aadhaar card File', 'trim|required|xss_clean|strip_tags');
        // }
        if (strlen($this->input->post("aadhaar_card_old")) > 0 || (!empty($_FILES['aadhaar_card']['name'])) || (!empty($this->input->post("aadhaar_card_temp")))) {
        } else {
            $this->form_validation->set_rules('aadhaar_card', 'Aadhaar card File', 'trim|required|xss_clean|strip_tags');
        }

        $this->form_validation->set_rules('passport_photo_type', 'Passport photo', 'trim|required|xss_clean|strip_tags');
        // // if (empty($_FILES['passport_photo']['name'])) {

        //     // $this->form_validation->set_rules('passport_photo', 'Passport photo File', 'trim|required|xss_clean|strip_tags');
        // // }
        if (strlen($this->input->post("passport_photo_old")) > 0 || (!empty($_FILES['passport_photo']['name']))) {
        } else {
            $this->form_validation->set_rules('passport_photo', 'Passport photo File', 'trim|required|xss_clean|strip_tags');
        }

        $this->form_validation->set_rules('signature_type', 'Signature', 'trim|required|xss_clean|strip_tags');
        // if (empty($_FILES['signature']['name'])) {
        //     $this->form_validation->set_rules('signature', 'Signature File', 'trim|required|xss_clean|strip_tags');
        // }
        if (strlen($this->input->post("signature_old")) > 0 || (!empty($_FILES['signature']['name']))) {
        } else {
            $this->form_validation->set_rules('signature', 'Signature File', 'trim|required|xss_clean|strip_tags');
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->enclosures($obj_id);
        } else {
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

            if (strlen($this->input->post("aadhaar_card_temp")) > 0) {
                $aadhaarCard = movedigilockerfile($this->input->post('aadhaar_card_temp'));
                $aadhaar_card = $aadhaarCard["upload_status"] ? $aadhaarCard["uploaded_path"] : null;
            } else {
                $aadhaarCard = cifileupload("aadhaar_card");
                $aadhaar_card = $aadhaarCard["upload_status"] ? $aadhaarCard["uploaded_path"] : null;
            }


            $nocFromCurrentEmployeer = cifileupload("noc_from_current_employeer");
            $noc_from_current_employeer = $nocFromCurrentEmployeer["upload_status"] ? $nocFromCurrentEmployeer["uploaded_path"] : null;

            $otherQualificationCertificate = cifileupload("other_qualification_certificate");
            $other_qualification_certificate = $otherQualificationCertificate["upload_status"] ? $otherQualificationCertificate["uploaded_path"] : null;

            $previousEmployment = cifileupload("previous_employment");
            $previous_employment = $previousEmployment["upload_status"] ? $previousEmployment["uploaded_path"] : null;

            $workExperience = cifileupload("work_experience");
            $work_experience = $workExperience["upload_status"] ? $workExperience["uploaded_path"] : null;

            $anyOtherDocument = cifileupload("any_other_document");
            $any_other_document = $anyOtherDocument["upload_status"] ? $anyOtherDocument["uploaded_path"] : null;

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
                "aadhaar_card_old" => strlen($aadhaar_card) ? $aadhaar_card : $this->input->post("aadhaar_card_old"),
                "passport_photo_old" => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
                "signature_old" => strlen($signature_photo) ? $signature_photo : $this->input->post("signature_old")
            );
            $this->session->set_flashdata('uploaded_files', $uploadedFiles);

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

                'form_data.enclosures.aadhaar_card_type' => $this->input->post("aadhaar_card_type"),
                'form_data.enclosures.aadhaar_card' => strlen($aadhaar_card) ? $aadhaar_card : $this->input->post("aadhaar_card_old"),

                'form_data.enclosures.passport_photo_type' => $this->input->post("passport_photo_type"),
                'form_data.enclosures.passport_photo' => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),

                'form_data.enclosures.signature_photo_type' => $this->input->post("signature_type"),
                'form_data.enclosures.signature_photo' => strlen($signature_photo) ? $signature_photo : $this->input->post("signature_old"),
            );

            $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
            redirect('spservices/employment-registration/preview/' . $objId);
            // pre($data);
        }
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
        $data =  (array)$this->highest_examination_passed_model->get_rows($filter);
        echo json_encode($data);
    }
    // get Examination Passed list
    public function get_examination_passed()
    {
        $highest_education =  $this->input->post('highestEducation');
        $filter['highest_educational_level'] = base64_decode($highest_education);
        $data =  (array)$this->examination_passed_model->get_rows($filter);
        echo json_encode($data);
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
            log_response($txn_no, $json_obj, "aadhaar_verification_log");
            $curl = curl_init($this->aadhaarApi . "send");
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($curl);
            log_response($txn_no, $response, "aadhaar_verification_log");
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);

            //$result = json_decode($response);
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
            //-----------------------------Aadhaar check for hash--------------------------------------
            $response_json = json_decode('{"status":1,
                "ret":{"0":"n"},
                "msg":{"0":"403"},
                "txn_no":{"0":"644b8173e33c2"},
                "info":{"0":"04{01111887VYyCjlm2OWkTBI18VK563RCEVfNvzdH7V5Tg5qwKLHVgdKVyj8WjNnZDPxjCzZsZ,A,824570a9e7ee20232d4de4906a18340e5866309233befba6408497084b1a3a5f,0180020108002010,2.0,20230428134949,0,0,0,0,2.5,73bd5abf744d9c0ec0c3c44e9a7c2eaf9cc2fc9e9d3e49f2c11a3b09ee314cc3,57266d21922bc5f567bb4426c4924210afe690a51a98dec2b612612ef76440ca,ee0c0254e3f89b6a0ac539ca75d884ffab6f8b75adee3b154c37cec60fe4b1cb,,E,100,NA,E,NA,NA,NA,NA,NA,,NA,NA,NA,NA,NA,NA}"},
                "xml":{"@attributes":{"code":"55c7358530f1410db40ad4a772353d0b","err":"403","info":"04{01111887VYyCjlm2OWkTBI18VK563RCEVfNvzdH7V5Tg5qwKLHVgdKVyj8WjNnZDPxjCzZsZ,A,824570a9e7ee20232d4de4906a18340e5866309233befba6408497084b1a3a5f,0180020108002010,2.0,20230428134949,0,0,0,0,2.5,73bd5abf744d9c0ec0c3c44e9a7c2eaf9cc2fc9e9d3e49f2c11a3b09ee314cc3,57266d21922bc5f567bb4426c4924210afe690a51a98dec2b612612ef76440ca,ee0c0254e3f89b6a0ac539ca75d884ffab6f8b75adee3b154c37cec60fe4b1cb,,E,100,NA,E,NA,NA,NA,NA,NA,,NA,NA,NA,NA,NA,NA}","ret":"n","ts":"2023-04-28T13:49:50.889+05:30","txn":"644b8173e33c2"},"Signature":{"SignedInfo":{"CanonicalizationMethod":{"@attributes":{"Algorithm":"http:\/\/www.w3.org\/TR\/2001\/REC-xml-c14n-20010315"}},"SignatureMethod":{"@attributes":{"Algorithm":"http:\/\/www.w3.org\/2000\/09\/xmldsig#rsa-sha1"}},"Reference":{"@attributes":{"URI":""},"Transforms":{"Transform":{"@attributes":{"Algorithm":"http:\/\/www.w3.org\/2000\/09\/xmldsig#enveloped-signature"}}},"DigestMethod":{"@attributes":{"Algorithm":"http:\/\/www.w3.org\/2001\/04\/xmlenc#sha256"}},"DigestValue":"N\/gtVY7gGnCcaw6DZ4c18iYNKddjglQXmoF7E1uGa5c="}},"SignatureValue":"TzZgUUzv8NEIUMIPyK4WJwcIOPYG+23ZXYtEwLU+dFw2k7wykrkbjKer8fZi0tm2MHJis2RYwrHjpKVx6ZbzX609wEaYLUIINWDLx94FVVE\/H3aiiM+J2\/3UugYAYhJE2NIICtYo6ollScwTZbk3GCsXwKBJ8QlbuosOuNIp9pqJEoQzgKmtqfbdbrQSZn8cGXSG1TefWa9l4jqODGbTHS6c5Jq2ze47CBDt85a35NDVerM3UvYsU6vFiSEXqlaFjvbYwjfmgbTnrXdLMM00M8Ia2zYFbDIWtfSuKFE4pxpnD893nqt28jMT5Zo8kuWr2vH7etXudI9vFREh0VqHFA=="}},
                "obj_id":"644b81a62137ee6a71065848"}', true);
            $str_response = $response_json['info']['0'];
            //print_r($str_response);
            $str_response1 = substr($str_response, strpos($str_response, "{") + 1);
            $hash_arr = explode(",", $str_response1);
            $hash_code = $hash_arr[0];

            $filter = array(
                "hash_value" => $hash_code,
            );
            $countRow = $this->check_aadhaar_model->get_row($filter);

            if ($countRow == '1') {
                $resData = array(
                    "status" => 0,
                    "ret" => array("0" => "n"),
                    "msg" => 'Your Aadhaar no. already registered with us.',
                    "txn_no" => '',
                    "info" => '',
                    "xml" => array()
                );
                echo json_encode($resData);
                return;
            }
            //-----------------------------Aadhaar check for hash--------------------------------------            
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
                "hashed_id" => $hash_code
            );

            $obj_id = $this->first_reg($data_to_insert);
            $resData['obj_id'] = $obj_id;
            echo json_encode($resData);
        } else {
            log_response($txn_no, $json_obj, "aadhaar_verification_log");

            $curl = curl_init($this->aadhaarApi . "encrypt");
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($curl);
            log_response($txn_no, $response, "aadhaar_verification_log");
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
                //-----------------------------Aadhaar check for hash--------------------------------------
                $str_response = $xml->attributes()->info;
                $str_response1 = substr($str_response, strpos($str_response, "{") + 1);
                $hash_arr = explode(",", $str_response1);
                $hash_code = $hash_arr[0];

                $filter = array(
                    "hash_value" => $hash_code,
                );
                $countRow = $this->check_aadhaar_model->get_row($filter);

                if ($countRow > 0) {
                    $resData = array(
                        "status" => 0,
                        "ret" => array("0" => "n"),
                        "msg" => 'Your Aadhaar no. already registered with us.',
                        "txn_no" => '',
                        "info" => $hash_code,
                        "xml" => array()
                    );
                    echo json_encode($resData);
                    return;
                }
                //-----------------------------Aadhaar check for hash--------------------------------------
                // if ($xml->attributes()->ret == 'y') {
                $data_to_insert = array(
                    'aadhaar_no' => $aadhaar_no,
                    'name' => $name,
                    'aadhaar_verify_status' => 1,
                    "hashed_id" => $hash_code
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
}
