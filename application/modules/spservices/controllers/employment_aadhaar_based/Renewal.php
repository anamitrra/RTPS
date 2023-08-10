<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Renewal extends Rtps
{
    private $serviceName = "Renewal of Registration Card of Employment Seeker in Employment Exchange";
    private $serviceId = "EMP_A_RENEW";

    public function __construct(){
        parent::__construct();
        $this->load->model('employment_aadhaar_based/employment_model');

        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper('smsprovider');
        $this->load->helper("employmentcertificate");
        $this->load->model('employment_aadhaar_based/logreports_model');
        $this->load->helper("role");
        $this->load->library('user_agent');

        if ($this->session->role) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else
    } //End of __construct()

    public function index($objId = null){
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
        $this->load->view('employment_aadhaar_based/renewal', $data);
        $this->load->view('includes/frontend/footer');
    } //End of index()

    public function previous_registration_details($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
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
        $this->load->view('employment_aadhaar_based/previous_registartion_view', $data);
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
        $str = "RTPS-REREA/" . date('Y') . "/" . $number;
        return $str;
    } //End of generateID()

    /**
     *  load preview page
     * 
     */
    public function preview(){
        //get previous data
        $reg_no   = $this->input->post("reg_no");
        $reg_date = $this->input->post("reg_date");
    
        $reg_date_old = str_replace("/", "-", $reg_date);

        // step1: date
        if(strtotime($reg_date_old) > strtotime('-3 year') ){
            $this->session->set_flashdata('fail','Renewal date invalid,Please try after 3 years');
            redirect('spservices/employment_aadhaar_based/Renewal/index/'); 
        }

        // step2: check in mongodb
        $dbrow = $this->employment_model->get_previous_data($reg_no,$reg_date);

        if(count((array)$dbrow)==0){
            $dbrow1 = $this->employment_model->get_previous_data_nonaadhar($reg_no,$reg_date);
            if(count((array)$dbrow1) !=0){
                $dbrow=$dbrow1;
            }
        }

        if($this->slug === "CSC"){
            $apply_by = $sessionUser['userId'];
        }else{
            $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        }

        /*
        * if not found check in service plus  database
        * else check in sp applications
        */
        if(count((array)$dbrow)==0){
                //pre("Have to check in postgress db");
                $old_date = explode('-', $reg_date); 
                $formatted_reg_date = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];

                $curl = curl_init();
                $url='https://rtps.assam.gov.in/tools/rtps_id_labels/src/api/external_apis.php/get_skill_data';

                $dataArray = array(   
                    'reg_no' => $reg_no,
                    'sub_date' => $reg_date
                 );

                $data = http_build_query($dataArray);
                $getUrl = $url."?".$data;

                curl_setopt_array($curl, array(
                CURLOPT_URL => $getUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,    // disable SSL certificate verification
                CURLOPT_SSL_VERIFYHOST => false,    // disable hostname verification
                  
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT_MS => 5000,
                  
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer |0VW?z,-w2w"6;{b8v}K6A5+Fdf@l-'
                   ),
                ));

                $response = curl_exec($curl);
                if (curl_errno($curl) ||  curl_getinfo($curl, CURLINFO_HTTP_CODE) >= 400) {
                        //echo 'HTTP Error';
                        $this->session->set_flashdata('fail','Data not found');
                        redirect('spservices/employment_aadhaar_based/Renewal/index/'); 
                }

                curl_close($curl);
                $response = json_decode($response);
                //pre($response->data->initiated_data->enclosure_details);

                $encl_cnt=count($response->data->initiated_data->enclosure_details);
                //pre($encl_cnt);
                
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
                $passport_photo=isset($response->data->initiated_data->attribute_details->passport_photo)?$response->data->initiated_data->attribute_details->passport_photo:'';;
                $passport_photo_type='';
                $previous_employment='';
                $previous_employment_type='';
                $pwd_certificate='';
                $pwd_certificate_type='';
                $signature_photo=isset($response->data->initiated_data->attribute_details->signature)?$response->data->initiated_data->attribute_details->signature:'';
                $work_experience=''; 

                for ($x = 0; $x < $encl_cnt; $x++) {
                    $data=$response->data->initiated_data->enclosure_details[$x];
                    $result = [];
                    $i=0;
                    foreach ($data as $key => $value){
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

                $applicant_name  =$response->data->initiated_data->attribute_details->applicant_name;
                $applicant_gender=$response->data->initiated_data->attribute_details->applicant_gender;
                $mobile_number   =$response->data->initiated_data->attribute_details->mobile_number;
                $e_mail =$response->data->initiated_data->attribute_details->{'e-mail'};
                $fathers_name =$response->data->initiated_data->attribute_details->fathers_name;
                $caste =$response->data->initiated_data->attribute_details->caste;
                $mothers_name =$response->data->initiated_data->attribute_details->mothers_name;
                $date_of_birth=$response->data->initiated_data->attribute_details->date_of_birth;
                $whether_ex_servicemen=$response->data->initiated_data->attribute_details->{'whether_ex-servicemen'};
                $husbands_name=isset($response->data->initiated_data->attribute_details->husbands_name)?$response->data->initiated_data->attribute_details->husbands_name:'';
                
                $category_of_ex_servicemen=isset($response->data->initiated_data->attribute_details->{'category_of_ex-servicemen'})? $response->data->initiated_data->attribute_details->{'category_of_ex-servicemen'}:'';

                $religion=$response->data->initiated_data->attribute_details->religion;
                $marital_status=$response->data->initiated_data->attribute_details->marital_status;
                $occupation=$response->data->initiated_data->attribute_details->occupation;

                $unique_identification_no=isset($response->data->initiated_data->attribute_details->unique_identification_no)?$response->data->initiated_data->attribute_details->unique_identification_no:'';

                $unique_identification_type=isset($response->data->initiated_data->attribute_details->unique_identification_type)?$response->data->initiated_data->attribute_details->unique_identification_type:'';

                $prominent_identification_mark=$response->data->initiated_data->attribute_details->prominent_identification_mark;
                $height__in_cm=isset($response->data->initiated_data->attribute_details->height__in_cm)?$response->data->initiated_data->attribute_details->height__in_cm:'';
                $weight__kgs=isset($response->data->initiated_data->attribute_details->weight__kgs)?$response->data->initiated_data->attribute_details->weight__kgs:'';
                $eye_sight=isset($response->data->initiated_data->attribute_details->eye_sight)?$response->data->initiated_data->attribute_details->eye_sight:'';
                $chest__inch=isset($response->data->initiated_data->attribute_details->chest__inch)?$response->data->initiated_data->attribute_details->chest__inch:'';
                $are_you_differently_abled__pwd=isset($response->data->initiated_data->attribute_details->are_you_differently_abled__pwd)?$response->data->initiated_data->attribute_details->are_you_differently_abled__pwd:'';
                $disability_category=isset($response->data->initiated_data->attribute_details->disability_category)?$response->data->initiated_data->attribute_details->disability_category:'';
                $additional_disability_type=isset($response->data->initiated_data->attribute_details->additional_disability_type)?$response->data->initiated_data->attribute_details->additional_disability_type:'';
                $disbility_percentage=isset($response->data->initiated_data->attribute_details->disbility_percentage)?$response->data->initiated_data->attribute_details->disbility_percentage:'';
                //page 2
                $name_of_the_house_apartment_p=isset($response->data->initiated_data->attribute_details->name_of_the_house_apartment_p)?$response->data->initiated_data->attribute_details->name_of_the_house_apartment_p:'';
                $house_no_apartment_no_p=isset($response->data->initiated_data->attribute_details->house_no_apartment_no_p)?$response->data->initiated_data->attribute_details->house_no_apartment_no_p:'';
                $building_no_block_no__p=isset($response->data->initiated_data->attribute_details->building_no_block_no__p)?$response->data->initiated_data->attribute_details->building_no_block_no__p:'';
                $address__locality_street_etc___p=isset($response->data->initiated_data->attribute_details->address__locality_street_etc___p)?$response->data->initiated_data->attribute_details->address__locality_street_etc___p:'';
                $vill_town_ward_city_p=isset($response->data->initiated_data->attribute_details->vill_town_ward_city_p)?$response->data->initiated_data->attribute_details->vill_town_ward_city_p:'';
                $post_office_p=isset($response->data->initiated_data->attribute_details->post_office_p)?$response->data->initiated_data->attribute_details->post_office_p:'';
                $police_station_p=isset($response->data->initiated_data->attribute_details->police_station_p)? $response->data->initiated_data->attribute_details->police_station_p:'';
                $pin_code_p=isset($response->data->initiated_data->attribute_details->pin_code_p)?$response->data->initiated_data->attribute_details->pin_code_p:'';
                $revenue_circle=isset($response->data->initiated_data->attribute_details->revenue_circle)?$response->data->initiated_data->attribute_details->revenue_circle:'';
                $sub_division=isset($response->data->initiated_data->attribute_details->sub_division)?$response->data->initiated_data->attribute_details->sub_division:'';
                $residence=isset($response->data->initiated_data->attribute_details->residence)?$response->data->initiated_data->attribute_details->residence:'';
                $district_p=isset($response->data->initiated_data->attribute_details->district_p)?$response->data->initiated_data->attribute_details->district_p:'';

                $same_as_permanent_address=isset($response->data->initiated_data->attribute_details->same_as_permanent_address)? $response->data->initiated_data->attribute_details->same_as_permanent_address:'';

                $name_of_the_house_apartment=isset($response->data->initiated_data->attribute_details->name_of_the_house_apartment)?$response->data->initiated_data->attribute_details->name_of_the_house_apartment:'';
                $house_no_apartment_no=isset($response->data->initiated_data->attribute_details->house_no_apartment_no)?$response->data->initiated_data->attribute_details->house_no_apartment_no:'';
                $building_no_block_no=isset($response->data->initiated_data->attribute_details->building_no_block_no)?$response->data->initiated_data->attribute_details->building_no_block_no:'';
                $address__locality_street_etc=isset($response->data->initiated_data->address__locality_street_etc)?$response->data->initiated_data->attribute_details->address__locality_street_etc:'';
                $vill_town_ward_city=isset($response->data->initiated_data->attribute_details->vill_town_ward_city)?$response->data->initiated_data->attribute_details->vill_town_ward_city:'';
                $post_office=isset($response->data->initiated_data->attribute_details->post_office)?$response->data->initiated_data->attribute_details->post_office:'';
                $police_station=isset($response->data->initiated_data->attribute_details->police_station)?$response->data->initiated_data->attribute_details->police_station:'';
                $pin_code=isset($response->data->initiated_data->attribute_details->pin_code)?$response->data->initiated_data->attribute_details->pin_code:'';
                $district=isset($response->data->initiated_data->attribute_details->district)?$response->data->initiated_data->attribute_details->district:'';

                //page 3
                $highest_educational_level=isset($response->data->initiated_data->attribute_details->highest_educational_level)?$response->data->initiated_data->attribute_details->highest_educational_level:'';
                $highest_examination_passed=isset($response->data->initiated_data->attribute_details->highest_examination_passed)?$response->data->initiated_data->attribute_details->highest_examination_passed:'';

                $edu_cnt=isset($response->data->initiated_data->attribute_details->education_qualification)?count($response->data->initiated_data->attribute_details->education_qualification):0;

                if($edu_cnt > 0){
                    $examination_passed_arr=$response->data->initiated_data->attribute_details->education_qualification;
                }

                $oedu_cnt=isset($response->data->initiated_data->attribute_details->other_qualification_trainings_courses)?count($response->data->initiated_data->attribute_details->other_qualification_trainings_courses):0;

                if($oedu_cnt > 0){
                    $other_qualification_trainings_courses=$response->data->initiated_data->attribute_details->other_qualification_trainings_courses;
                }

                $skill_cnt=isset($response->data->initiated_data->attribute_details->skill_qualification)?count($response->data->initiated_data->attribute_details->skill_qualification):0;

                if($skill_cnt > 0){
                    $skill_qualification=$response->data->initiated_data->attribute_details->skill_qualification;
                }
                
                $work_cnt=isset($response->data->initiated_data->attribute_details->work_experience)?count($response->data->initiated_data->attribute_details->work_experience):0;
                
                if($work_cnt > 0){
                    $work_experience=$response->data->initiated_data->attribute_details->work_experience;
                }
                
                $submission_location=$response->data->initiated_data->submission_location;
                $employment_exchange=$response->data->initiated_data->submission_location;
                //todo
                $job_preference_key_skills='';
                $current_employment_status='';
                $years='';
                $months='';

            //enclosure

            
                $appl_ref_no_temp = $this->getID(7);
                $created_at = getISOTimestamp();

                $first_data = array(
                    "service_id" => "1861",
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
                    "submission_location"=>$submission_location,
                    "job_preference_key_skills"=>$job_preference_key_skills,
                    "current_employment_status"=>$current_employment_status,
                    "years"=>$years,
                    "months"=>$months,
                    "employment_exchange"=>$employment_exchange,
                    "enclosures"=>$enclosures,
                    "registration_no"=>isset($response->data->initiated_data->attribute_details->registration_no)?$response->data->initiated_data->attribute_details->registration_no:$reg_no,
            );

            $data = array('service_data' => $first_data, 'form_data' => $data2);
            //pre($data);

            $insert = $this->employment_model->insert($data);
            $obj_id = $insert['_id']->{'$id'};
            $this->session->set_userdata("employment_oid", $obj_id);

             $data_new = array(
                "obj_id" => $obj_id,
                "serviceservice_name" => $this->serviceName,
            );

            $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($obj_id)));

            if ($dbRow) {
                $data_new["dbrow"] = $this->employment_model->get_by_doc_id($obj_id);
            } else {
                $data_new["dbrow"] = false;
            }

            $this->load->view('includes/frontend/header');
            $this->load->view('employment_aadhaar_based/renewal_preview', $data_new);
            $this->load->view('includes/frontend/footer');

        }
        else{
                $appl_ref_no_temp = $this->getID(7);
                $created_at = getISOTimestamp();
                $first_data = array(
                    "service_id" => "1861",
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
           
                $old_form_data=$dbrow->{0}->form_data;
        
                unset($old_form_data->convenience_charge);
                unset($old_form_data->department_id);
                unset($old_form_data->payment_params);
                unset($old_form_data->payment_status);
                unset($old_form_data->pfc_payment_response);
                //unset($old_form_data->registration_no);
                unset($old_form_data->renewal_date);
                unset($old_form_data->submission_date);
                unset($old_form_data->output_certificate);
                unset($old_form_data->pfc_payment_status);
                unset($old_form_data->rtps_trans_id);
                unset($old_form_data->user_type);
                unset($old_form_data->applied_user_type);
                unset($old_form_data->service_name);
                unset($old_form_data->service_id);

                $data = array('service_data' => $first_data, 'form_data' => $old_form_data);
                $insert = $this->employment_model->insert($data);
                $obj_id = $insert['_id']->{'$id'};
                $this->session->set_userdata("employment_oid", $obj_id);

        
                $data_to_update = array(
                        'form_data.user_type' => $this->slug,
                        'form_data.applied_user_type' => $this->slug,
                        'form_data.service_name' => $this->serviceName,
                        'form_data.service_id' => $this->serviceId,
                        'form_data.rtps_trans_id' => $appl_ref_no_temp,
                );

                $this->employment_model->update($obj_id, $data_to_update);
                
                $data_new = array(
                    "obj_id" => $obj_id,
                    "old_obj_id"=>$dbrow->{0}->_id->{'$id'},
                    "serviceservice_name" => $this->serviceName,
                );
                $data_new["dbrow"]=$dbrow->{0};

                $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($obj_id)));

                if ($dbRow) {
                    $data_new["dbrow"] = $this->employment_model->get_by_doc_id($obj_id);
                } else {
                    $data_new["dbrow"] = false;
                }

                //add form data from old to new
                $this->load->view('includes/frontend/header');
                $this->load->view('employment_aadhaar_based/renewal_preview', $data_new);
                $this->load->view('includes/frontend/footer');
        }
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
    public function finalsubmition($objId = null){
        if ($objId) {
            $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
            $employment_exchange = $dbRow->form_data->employment_exchange;
            $sub_date = getISOTimestamp();

            // $d = strtotime("+3 year");
            // $renew_date = new UTCDateTime(strtotime(date("d-m-Y", $d)) * 1000);
            $district = $this->employment_model->get_district_from_eeo($employment_exchange);

            $curr_timestamp = getCurrentTimestamp();
            $date_ms = strtotime($curr_timestamp);
            $renew_date = new MongoDB\BSON\UTCDateTime(strtotime("+3 year",$date_ms) * 1000);
            $data_to_update = array(
                'service_data.appl_status' => 'D',
                'service_data.submission_date' => $sub_date,
                'service_data.submission_location' => $employment_exchange,
                'form_data.renewal_date' => $renew_date,
                'form_data.submission_date' => $sub_date,
                'service_data.district' =>  $district,

            );

            $this->employment_model->update($objId, $data_to_update);

            $filePath = $this->save_certificate($objId);

            $filepath_to_update = array(
                'form_data.output_certificate' => $filePath,
                'form_data.certificate' => $filePath,
            );
            $this->employment_model->update($objId, $filepath_to_update);
            redirect('spservices/employment-registration/generate_certificate/' . $objId);
        }
    }
    /**
     * save certificate to storage
     * 
     */
    public function save_certificate($objId = null){
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

    /**
     * generate output certificate 
     * 
     */
    public function generate_certificate($objId = null){
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

}
