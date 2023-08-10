<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps {

    private $serviceName = "Issuance of Caste Certificate";
    private $serviceId = "CASTE";
    private $departmentId = "100002";
    private $departmentName = "Department of Tribal Affairs (Plain)";

    public function __construct() {
        parent::__construct();
        $this->load->model('castecertificate/registration_model');
        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->helper('smsprovider');
        $this->load->helper('log');
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        }else{
            $this->slug = "user";
        }
    }//End of __construct()

    public function index($obj_id=null) {

        check_application_count_for_citizen();

       //pre($obj_id);
        $data = array("pageTitle" => "Application for Caste Certificate");
        $filter = array(
            "_id" => $obj_id ? new ObjectId($obj_id) : NULL,
            "service_data.appl_status" => "DRAFT"
        );

        $data["dbrow"] = $this->registration_model->get_row($filter);
        $data['usser_type'] = $this->slug;
       
        $this->load->view('includes/frontend/header');
        $this->load->view('castecertificate/caste',$data);
        $this->load->view('includes/frontend/footer');
    } //End of index()

    public function submit() {
        //pre($this->input->post());
        $objId = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");

        //$this->form_validation->set_rules('language', 'Language', 'trim|required|xss_clean|strip_tags|max_length[30]');
         
        $this->form_validation->set_rules('application_for', 'Application For', 'trim|required|xss_clean|strip_tags|max_length[30]');
        $this->form_validation->set_rules('applicant_name', 'Applicant Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('applicant_gender', 'Applicant Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mobile', 'Mobile No', 'trim|required|xss_clean|strip_tags|max_length[10]|numeric');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|strip_tags|valid_email');
        $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|xss_clean|strip_tags|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]|max_length[10]');
        $this->form_validation->set_rules('epic_no', 'EPIC No', 'trim|xss_clean|strip_tags|max_length[20]');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('caste', 'Applicant Caste', 'trim|required|xss_clean|strip_tags|max_length[200]');
        //$this->form_validation->set_rules('religion', 'Religion', 'trim|required|xss_clean|strip_tags|max_length[100]');

        if ((!empty($this->input->post("check_caste"))) == "Yes" ) {
            $this->form_validation->set_rules('subcaste', 'Applicant Sub-Caste', 'trim|required|xss_clean|strip_tags');
        }
        $this->form_validation->set_rules('father_name', 'Father Name', 'trim|required|xss_clean|regex_match[/^[a-zA-Z ]*$/]|strip_tags|max_length[100]');
        $this->form_validation->set_rules('mother_name', 'Mother Name', 'trim|required|xss_clean|regex_match[/^[a-zA-Z ]*$/]|strip_tags|max_length[100]');
        //$this->form_validation->set_rules('husband_name', 'Husband Name', 'trim|xss_clean|strip_tags|max_length[20]');

        if ((!empty($this->input->post('application_for'))) &&  (($this->input->post('application_for') == "ST(H)") || ($this->input->post('application_for') == "Plain Tribes in Hills"))) {
            if(!empty($this->input->post('district'))){
                if (($this->input->post('district') == "Dima Hasao") || ($this->input->post('district') == "Karbi Anglong") || ($this->input->post('district') == "West Karbi Anglong")) {
                } else {
                    //pre($this->input->post('district'));
                    $this->session->set_flashdata('fail', 'Please select only Dima Hasao, Karbi Anglong, West Karbi Anglong');
                    $this->index();
                    return;
                }
            }   
        }

        if ((!empty($this->input->post('application_for'))) &&  (($this->input->post('application_for') == "ST(P)") || ($this->input->post('application_for') == "Hill Tribes in Plains"))) {
            if(!empty($this->input->post('district'))){
  
                if (($this->input->post('district') == "Dima Hasao") || ($this->input->post('district') == "Karbi Anglong") || ($this->input->post('district') == "West Karbi Anglong")) {
                    $this->session->set_flashdata('fail', 'You cannot select these district Dima Hasao, Karbi Anglong, West Karbi Anglong');
                    $this->index();
                    return;
                }
            }   
        }

        if ((!empty($this->input->post('caste'))) &&  ((($this->input->post('caste') == "Ganak in Districts of Cachar,Karimganj,Hailakandi")) || ($this->input->post('caste') == "Kiran Sheikh community of Barak Valley") || ($this->input->post('caste') == "Maimal(Muslim Fisherman)"))) {
            if(!empty($this->input->post('district'))){
                if (($this->input->post('district') == "Cachar") || ($this->input->post('district') == "Karimganj") || ($this->input->post('district') == "Hailakandi")) {
                    //pre($this->input->post('district'));
                } else {
                    $this->session->set_flashdata('fail', 'You can only select these district Karimganj, Hailakandi, Cachar');
                    $this->index();
                    return;
                }
            }   
        }

        if ((!empty($this->input->post('caste'))) &&  (($this->input->post('caste') == "Barmans in Cachar") && ($this->input->post('application_for') == "Plain Tribes in Hills"))) {
            if(!empty($this->input->post('district'))){
                if (($this->input->post('district') == "Dima Hasao") || ($this->input->post('district') == "Karbi Anglong") || ($this->input->post('district') == "West Karbi Anglong")) {
                    //pre($this->input->post('district'));
                } else {
                    $this->session->set_flashdata('fail', 'You can only select these district Dima Hasao, Karbi Anglong, West Karbi Anglong');
                    $this->index();
                    return;
                }
            }   
        }

        if ((!empty($this->input->post('caste'))) &&  (($this->input->post('caste') == "Barmans in Cachar") && ($this->input->post('application_for') == "ST(P)"))) {
            if(!empty($this->input->post('district'))){
                if (($this->input->post('district') == "Cachar") || ($this->input->post('district') == "Karimganj") || ($this->input->post('district') == "Hailakandi")) {
                    //pre($this->input->post('district'));
                } else {
                    $this->session->set_flashdata('fail', 'You can only select these district Karimganj, Hailakandi, Cachar');
                    $this->index();
                    return;
                }
            }   
        }

        if ((!empty($this->input->post('caste'))) &&  ($this->input->post('caste') == "Jolha")) {
            if(!empty($this->input->post('district'))){
                if (($this->input->post('district') == "Golaghat") || ($this->input->post('district') == "Jorhat") || ($this->input->post('district') == "Sivasagar") || ($this->input->post('district') == "Biswanath") || ($this->input->post('district') == "Tinsukia") || ($this->input->post('district') == "Charaideo") || ($this->input->post('district') == "Dibrugarh") || ($this->input->post('district') == "Lakhimpur") || ($this->input->post('district') == "Sonitpur") || ($this->input->post('district') == "Nagaon")) {
                    
                } else {
                    $this->session->set_flashdata('fail', 'You can only select these district Golaghat, Jorhat, Sivasagar, Biswanath, Tinsukia, Charaideo, Dibrugarh, Lakhimpur, Sonitpur, Nagaon');
                    $this->index();
                    return;
                }
            }   
        }

        if ((!empty($this->input->post('subcaste'))) &&  ($this->input->post('subcaste') == "Rudra Paul of Cachar/Karimganj/Hailakandi")) {
            if(!empty($this->input->post('district'))){
                if (($this->input->post('district') == "Cachar") || ($this->input->post('district') == "Karimganj") || ($this->input->post('district') == "Hailakandi")) {
                    
                } else {
                    $this->session->set_flashdata('fail', 'You can only select these district Karimganj, Hailakandi, Hailakandi');
                    $this->index();
                    return;
                }
            }   
        }

        $this->form_validation->set_rules('address_line_1', 'Address Line 1 ', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('address_line_2', 'Address Line 2', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('house_no', 'House No', 'trim|required|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('sub_division', 'Sub-Division', 'trim|required|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('circle_office', 'Circle Office', 'trim|required|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('mouza', 'Mouza', 'trim|required|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('village_town', 'Village or Town', 'trim|required|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('police_station', 'Police Station', 'trim|required|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('post_office', 'Post Office', 'trim|required|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('pin_code', 'PIN Code', 'trim|required|xss_clean|strip_tags|integer|exact_length[6]');
        
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
        } else {
            $appl_ref_no = $this->getID(7);
            $sessionUser = $this->session->userdata();

            if($this->slug === "CSC"){
                $apply_by = $sessionUser['userId'];
            }else{
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            }

            //Generate appl_id
            while(1){
                $appl_id = rand(1000000, 9999999);
                $filter = array(
                    "service_data.appl_id" => $appl_id,
                    //"service_data.service_id" => $this->serviceId
                );
                $rows = $this->registration_model->get_row($filter);
                if($rows === false) break;
            }

            $caste = $this->input->post("caste");
            if ($this->input->post("caste") == "Any Mizo (Lushai) tribe") {
                $caste = "Mizo (Lushai) tribe";
            }
            if ($this->input->post("caste") == "Any Naga Tribes") {
                $caste = "Naga tribes";
            }
            if ($this->input->post("caste") == "Barmans in Cachar") {
                $caste = "Barman";
            }
            if ($this->input->post("caste") == "Kiran Sheikh community of Barak Valley") {
                $caste = "Kiran Sheikh";
            }
            if ($this->input->post("caste") == "Manipuri including Manipuri Brahmin &amp; Manipuri Muslim") {
                $caste = "Manipuri";
            }

            $service_data = [
                "department_id" => $this->departmentId,
                "department_name" => $this->departmentName,
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $appl_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => $this->departmentName, // office name
                "submission_date" => "",
                "service_timeline" => "30",
                "appl_status" => "DRAFT",
                "district" => $this->input->post("district"),
            ];

            $form_data = [
                'applicant_name' => $this->input->post("applicant_name"),
                'mobile' => $this->input->post("mobile"),
                'email' => $this->input->post("email"),
                'father_name' => $this->input->post("father_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'application_for' => $this->input->post("application_for"),
                'pan_no' => $this->input->post("pan_no"),
                'epic_no' => $this->input->post("epic_no"),
                'aadhar_no' => $this->input->post("aadhar_no"),
                'dob' => $this->input->post("dob"),
                'caste' => $caste,
                'subcaste' => $this->input->post("subcaste"),
                'mother_name' => $this->input->post("mother_name"),
                //'husband_name' => $this->input->post("husband_name"),
                //'religion' => $this->input->post("religion"),

                'address_line_1' => $this->input->post("address_line_1"),
                'address_line_2' => $this->input->post("address_line_2"),
                'house_no' => $this->input->post("house_no"),
                'state' => $this->input->post("state"),
                'district' => $this->input->post("district"),
                'sub_division' => $this->input->post("sub_division"),
                'circle_office' => $this->input->post("circle_office"),
                'mouza' => $this->input->post("mouza"),
                'village_town' => $this->input->post("village_town"),
                'police_station' => $this->input->post("police_station"),
                'post_office' => $this->input->post("post_office"),
                'pin_code' => $this->input->post("pin_code"),

                'resState' => $this->input->post("state"),
                'resAddressLine1' => $this->input->post("address_line_1"),
                'resAddressLine2' => $this->input->post("address_line_2"),
                'resVillageTown' => $this->input->post("village_town"),
                'resMouza' => $this->input->post("mouza"),
                'resPostOffice' => $this->input->post("post_office"),
                'resPoliceStation' => $this->input->post("police_station"),
                'resPinCode' => $this->input->post("pin_code"),

                'occupationOfForefather' => "NA",
                'isFatherMotherNameInVoterList' => "Yes",
                'reasonForApplying' => "NA",

                'resDistrict' => $this->input->post("district"),
                'resSubdivision' => $this->input->post("sub_division"),
                'resCircleOffice' => $this->input->post("circle_office"),

                'fillUpLanguage' => $this->input->post("language"),

                'fatherOrAncestName' => "NA",
                'fatherOrAncestRelation' => "NA",
                'fatherOrAncestAddressLine1' => "NA",
                'fatherOrAncestAddressLine2' => "NA",
                'fatherOrAncestState' => "NA",
                'fatherOrAncestDistrict' => $this->input->post("district"),
                'fatherOrAncestSubdivision' => $this->input->post("sub_division"),
                'fatherOrAncestCircleOffice' => $this->input->post("circle_office"),
                'fatherOrAncestMouza' => "NA",
                'fatherOrAncestVillage' => "NA",
                'fatherOrAncestPoliceStation' => "NA",
                'fatherOrAncestPostOffice' => "NA",
                'fatherOrAncestPincode' => $this->input->post("pin_code"),
                'subCasteOfAncestors' => "NA",

                'user_id' => $sessionUser['userId']->{'$id'},    
                'user_type' => $this->slug,

                'service_type' => "CASTE",

                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];

            if(strlen($objId)) {
                $form_data["photo_type"] = $this->input->post("photo_type");
                $form_data["photo"] = $this->input->post("photo");
                $form_data["caste_certificate_of_father_type"] = $this->input->post("caste_certificate_of_father_type");
                $form_data["caste_certificate_of_father"] = $this->input->post("caste_certificate_of_father");
                $form_data["recomendation_certificate_type"] = $this->input->post("recomendation_certificate_type");
                $form_data["recomendation_certificate"] = $this->input->post("recomendation_certificate");
                $form_data["proof_of_residence_type"] = $this->input->post("proof_of_residence_type");
                $form_data["proof_of_residence"] = $this->input->post("proof_of_residence");
                $form_data["date_of_birth_type"] = $this->input->post("date_of_birth_type");
                $form_data["date_of_birth"] = $this->input->post("date_of_birth");
                
                $form_data["soft_copy_type"] = $this->input->post("soft_copy_type");
                $form_data["soft_copy"] = $this->input->post("soft_copy");

                if (!empty($this->input->post("others_type"))) {
                    $form_data["others_type"] = $this->input->post("others_type");
                    $form_data["others"] = $this->input->post("others");
                }
            }

            $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data
            ];
            //pre($inputs);
           // if ($sessCaptcha !== $inputCaptcha) {
              //  $this->session->set_flashdata("error", "Captcha does not mached!. Please try again");
               // $this->index();
          //  } else {
                if (strlen($objId)) {
                    $this->registration_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    redirect('spservices/castecertificate/registration/fileuploads/'. $objId);
                } else {
                    $insert = $this->registration_model->insert($inputs);
                    if ($insert) {
                        $objectId = $insert['_id']->{'$id'};
                        $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                        redirect('spservices/castecertificate/registration/fileuploads/' . $objectId);
                        exit();
                    } else {
                        $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                        $this->index();
                    } //End of if else
                } //End of if else
           // } //End of if else 
        } //End of if else
    } //End of submit()

    public function fileuploads($objId = null){
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "pageTitle" => "Attached Enclosures for ".$this->serviceName,
                "obj_id"=>$objId,               
                "dbrow" => $dbRow
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('castecertificate/casteuploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/castecertificate/registration');
        } //End of if else
    } //End of fileuploads()

    public function finalsubmition($obj = null)
    {
        if ($obj) {
            //pre($obj);
            $dbRow = $this->registration_model->get_by_doc_id(new ObjectId($obj));

            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }

            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")){
                //procesing data
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $postdata = array(
                    "application_ref_no" => $dbRow->service_data->appl_ref_no,
                    "state" => $dbRow->form_data->state,
                    "district" => $dbRow->form_data->district,
                    "subDivision" => $dbRow->form_data->sub_division,
                    "circleOffice" => $dbRow->form_data->circle_office,
                    "applicantName" => $dbRow->form_data->applicant_name,
                    "applicantGender" => $dbRow->form_data->applicant_gender,
                    "applicantMobileNo" => $dbRow->form_data->mobile,
                    "emailId" => $dbRow->form_data->email,
                    "cscoffice" => "NA",
                    "panNo" => $dbRow->form_data->pan_no,
                    "aadharNo" => $dbRow->form_data->aadhar_no,
                    "cscid" => "RTPS1234",
                    "fatherName" => $dbRow->form_data->father_name,
                    "motherName" => $dbRow->form_data->mother_name,
                    //"husbandName" => $dbRow->form_data->husband_name,
                    "age" => $dbRow->form_data->dob,
                    "addressLine1" => $dbRow->form_data->address_line_1,
                    "addressLine2" => $dbRow->form_data->address_line_2,
                    "village" => $dbRow->form_data->village_town,
                    "mouza" => $dbRow->form_data->mouza,
                    "postOffice" => $dbRow->form_data->post_office,
                    "policeStation" => $dbRow->form_data->police_station,
                    "pinCode" => $dbRow->form_data->pin_code,
                    "resState" => $dbRow->form_data->state,
                    "resAddressLine1" => $dbRow->form_data->address_line_1,
                    "resAddressLine2" => $dbRow->form_data->address_line_2,
                    "resVillageTown" => $dbRow->form_data->village_town,
                    "resMouza" => $dbRow->form_data->mouza,
                    "resPostOffice" => $dbRow->form_data->post_office,
                    "resPoliceStation" => $dbRow->form_data->police_station,
                    "resPinCode" => $dbRow->form_data->pin_code,
                    "applicantCaste" => $dbRow->form_data->application_for,
                    "applicantSubCaste" => !empty($dbRow->form_data->subcaste)? $dbRow->form_data->subcaste: $dbRow->form_data->caste,
                    //"applicantReligion" => $dbRow->form_data->religion,
                    "occupationOfForefather" => "NA",
                    "isFatherMotherNameInVoterList" => "Yes",
                    "reasonForApplying" => "NA",
                    "resDistrict" => $dbRow->form_data->district,
                    "resSubdivision" => $dbRow->form_data->sub_division,
                    "resCircleOffice" => $dbRow->form_data->circle_office,
                    "houseNumber" => $dbRow->form_data->house_no,
                    "fillUpLanguage" => $dbRow->form_data->fillUpLanguage,
                    "fatherOrAncestName" => "NA",
                    "fatherOrAncestRelation" => "NA",
                    "fatherOrAncestAddressLine1" => "NA",
                    "fatherOrAncestAddressLine2" => "NA",
                    "fatherOrAncestState" => "NA",
                    "fatherOrAncestDistrict" => $dbRow->form_data->district,
                    "fatherOrAncestSubdivision" => $dbRow->form_data->sub_division,
                    "fatherOrAncestCircleOffice" => $dbRow->form_data->circle_office,
                    "fatherOrAncestMouza" => "NA",
                    "fatherOrAncestVillage" => "NA",
                    "fatherOrAncestPoliceStation" => "NA",
                    "fatherOrAncestPostOffice" => "NA",
                    "fatherOrAncestPincode" => $dbRow->form_data->pin_code,
                    "subCasteOfAncestors" => "NA",
                    "epic" => $dbRow->form_data->epic_no,
                    "service_type" => "CASTE",

                    'spId' => array('applId' => $dbRow->service_data->appl_id)
                );

                if(!empty($dbRow->form_data->photo)){
                    $photo = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->photo));

                    $photo = array(
                        "encl" =>  $photo,
                        "docType" => "image/jpeg",
                        "enclFor" => "Photo",
                        "enclType" => $dbRow->form_data->photo_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "jpeg"
                    );

                    $postdata['photo'] = $photo;
                }

                if(!empty($dbRow->form_data->caste_certificate_of_father)){
                    $caste_certificate_of_father = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->caste_certificate_of_father));

                    $attachment_one = array(
                        "encl" =>  $caste_certificate_of_father,
                        "docType" => "application/pdf",
                        "enclFor" => "Caste certificate of father",
                        "enclType" => $dbRow->form_data->caste_certificate_of_father_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentOne'] = $attachment_one;
                }

                if(!empty($dbRow->form_data->proof_of_residence)){
                    $proof_of_residence = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->proof_of_residence));

                    $attachment_three = array(
                        "encl" =>  $proof_of_residence,
                        "docType" => "application/pdf",
                        "enclFor" => "Proof of Residence",
                        "enclType" => $dbRow->form_data->proof_of_residence_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentThree'] = $attachment_three;
                }
                
                if(!empty($dbRow->form_data->others)){
                    $others = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->others));

                    $attachment_four = array(
                        "encl" =>  $others,
                        "docType" => "application/pdf",
                        "enclFor" => "Others",
                        "enclType" => $dbRow->form_data->others_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentFour'] = $attachment_four;
                }

                if(!empty($dbRow->form_data->recomendation_certificate)){
                    $recomendation_certificate = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->recomendation_certificate));

                    $attachment_five = array(
                        "encl" =>  $recomendation_certificate,
                        "docType" => "application/pdf",
                        "enclFor" => "Recomendation Certificate",
                        "enclType" => $dbRow->form_data->recomendation_certificate_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentFive'] = $attachment_five;
                }

                // if(!empty($dbRow->form_data->soft_copy)){
                //     $soft_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->soft_copy));

                //     $attachment_six = array(
                //         "encl" => $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload the Soft  Copy of Application Form",
                //         "enclType" => "Upload the Soft  Copy of Application Form",
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );

                //     $postdata['AttachmentSix'] = $attachment_six;
                // }

                //     $json = json_encode($postdata);
                //    // pre($json);
                //     $buffer = preg_replace( "/\r|\n/", "", $json );
                //     $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
                //     fwrite($myfile, $buffer);
                //     fclose($myfile);
                //     die;

                $url = $this->config->item('caste_url');
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                curl_close($curl);

                log_response($dbRow->service_data->appl_ref_no, $response);

                //pre($response);
                if ($response) {
                    $response = json_decode($response);
                    if ($response->ref->status === "success") {
                        $data_to_update = array(
                            'form_data.edistrict_ref_no' => $response->ref->edistrict_ref_no,
                            'service_data.appl_status' => 'submitted',
                            'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            'processing_history' => $processing_history
                        );
                        $this->registration_model->update($obj, $data_to_update);

                        //Sending submission SMS
                        $nowTime = date('Y-m-d H:i:s');
                        $sms = array(
                            "mobile" => (int)$dbRow->form_data->mobile,
                            "applicant_name" => $dbRow->form_data->applicant_name,
                            "service_name" => 'Appl. for Caste Certificate.',
                            "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                            "app_ref_no" => $dbRow->service_data->appl_ref_no
                        );
                        sms_provider("submission", $sms);
                        

                        redirect('spservices/applications/acknowledgement/' . $obj);
                    } else {
                        $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>'.$dbRow->service_data->appl_ref_no.'</b>, Please try again.');
                        $this->my_transactions();
                    }
                }
            } else {
                $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                $this->my_transactions();
            }
        }

        redirect('iservices/transactions');
    }

    public function submitfiles()
    {
        //pre($_FILES);
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules('photo_type', 'Photo Type', 'required');
        $this->form_validation->set_rules('date_of_birth_type', 'Date of Birth Type', 'required');
        $this->form_validation->set_rules('proof_of_residence_type', 'Proof of Residence', 'required');

        if (empty($this->input->post("date_of_birth_old"))) {
            if(((empty($this->input->post("date_of_birth_type"))) && (($_FILES['date_of_birth']['name'] != "") || (!empty($this->input->post("date_of_birth_temp"))))) || ((!empty($this->input->post("date_of_birth_type"))) && (($_FILES['date_of_birth']['name'] == "") && (empty($this->input->post("date_of_birth_temp")))))) {
            
                $this->form_validation->set_rules('date_of_birth_type', 'Date of Birth Document Type', 'required');
                $this->form_validation->set_rules('date_of_birth', 'Date of Birth Document', 'required');
            }
        }

        if (empty($this->input->post("proof_of_residence_old"))) {
            if(((empty($this->input->post("proof_of_residence_type"))) && (($_FILES['proof_of_residence']['name'] != "") || (!empty($this->input->post("proof_of_residence_temp"))))) || ((!empty($this->input->post("proof_of_residence_type"))) && (($_FILES['proof_of_residence']['name'] == "") && (empty($this->input->post("proof_of_residence_temp")))))) {
            
                $this->form_validation->set_rules('proof_of_residence_type', 'Proof of Residence Document Type', 'required');
                $this->form_validation->set_rules('proof_of_residence', 'Proof of Residence Document', 'required');
            }
        }

        if (($_FILES['caste_certificate_of_father']['name'] == "") && (empty($this->input->post("caste_certificate_of_father_temp"))) && ($_FILES['recomendation_certificate']['name'] == "") && (empty($this->input->post("recomendation_certificate_temp"))) && (empty($this->input->post("caste_certificate_of_father_type"))) && (empty($this->input->post("recomendation_certificate_type")))) {
            $this->form_validation->set_rules('caste_certificate_of_father_type', 'Caste Certificate of Father Type', 'required');
            $this->form_validation->set_rules('recomendation_certificate_type', 'Recomendation Certificate Type', 'required');
            $this->form_validation->set_rules('caste_certificate_of_father', 'Caste Certificate of Father Document ', 'required');
            $this->form_validation->set_rules('recomendation_certificate', 'Recomendation Certificate Document', 'required');
        }

        if (empty($this->input->post("caste_certificate_of_father_old"))) {
            if(((empty($this->input->post("caste_certificate_of_father_type"))) && (($_FILES['caste_certificate_of_father']['name'] != "") || (!empty($this->input->post("caste_certificate_of_father_temp"))))) || ((!empty($this->input->post("caste_certificate_of_father_type"))) && (($_FILES['caste_certificate_of_father']['name'] == "") && (empty($this->input->post("caste_certificate_of_father_temp")))))) {
            
                $this->form_validation->set_rules('caste_certificate_of_father_type', 'Caste Certificate of Father Type', 'required');
                $this->form_validation->set_rules('caste_certificate_of_father', 'Caste Certificate of Father Document', 'required');
            }
        }
        if (empty($this->input->post("recomendation_certificate_old"))) {
            if (((empty($this->input->post("recomendation_certificate_type"))) && (($_FILES['recomendation_certificate']['name'] != "") || (!empty($this->input->post("recomendation_certificate_temp"))))) || ((!empty($this->input->post("recomendation_certificate_type"))) && (($_FILES['recomendation_certificate']['name'] == "") && (empty($this->input->post("recomendation_certificate_temp")))))) {
                
                $this->form_validation->set_rules('recomendation_certificate_type', 'Recomendation Certificate Type', 'required');
                $this->form_validation->set_rules('recomendation_certificate', 'Recomendation Certificate Document', 'required');
            }
        }

        // if (empty($this->input->post("soft_copy_old")) && $this->slug !== 'user') {
        //     if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {
                
        //         $this->form_validation->set_rules('soft_copy_type', 'Soft Copy Type required', 'required');
        //         $this->form_validation->set_rules('soft_copy', 'Soft Copy document required', 'required');
        //     }
        // }

        if (empty($this->input->post("others_old"))) {
            if ((empty($this->input->post("others_type"))) && (($_FILES['others']['name'] != "") || (!empty($this->input->post("others_temp"))))) {
            
                $this->form_validation->set_rules('others_type', 'Others Type', 'required');
            }
    
            if ((!empty($this->input->post("others_type"))) && (($_FILES['others']['name'] == "") && (empty($this->input->post("others_temp"))))) {
                $this->form_validation->set_rules('others', 'Others', 'required');
            }
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $photo = cifileupload("photo");
        $photo = $photo["upload_status"]?$photo["uploaded_path"]:null;

        if (strlen($this->input->post("date_of_birth_temp")) > 0) {
            $dateOfBirth = movedigilockerfile($this->input->post('date_of_birth_temp'));
            $date_of_birth = $dateOfBirth["upload_status"]?$dateOfBirth["uploaded_path"]:null;
        } else {
            $dateOfBirth = cifileupload("date_of_birth");
            $date_of_birth = $dateOfBirth["upload_status"]?$dateOfBirth["uploaded_path"]:null;
        }

        if (strlen($this->input->post("proof_of_residence_temp")) > 0) {
            $proofOfResidence = movedigilockerfile($this->input->post('proof_of_residence_temp'));
            $proof_of_residence = $proofOfResidence["upload_status"]?$proofOfResidence["uploaded_path"]:null;
        } else { 
            $proofOfResidence = cifileupload("proof_of_residence");
            $proof_of_residence = $proofOfResidence["upload_status"]?$proofOfResidence["uploaded_path"]:null;
        }

        if (strlen($this->input->post("recomendation_certificate_temp")) > 0) {
            $recomendationCertificate = movedigilockerfile($this->input->post('recomendation_certificate_temp'));
            $recomendation_certificate = $recomendationCertificate["upload_status"]?$recomendationCertificate["uploaded_path"]:null;
        } else { 
            $recomendationCertificate = cifileupload("recomendation_certificate");
            $recomendation_certificate = $recomendationCertificate["upload_status"]?$recomendationCertificate["uploaded_path"]:null;
        }

        if (strlen($this->input->post("caste_certificate_of_father_temp")) > 0) {
            $casteCertificateOfFather = movedigilockerfile($this->input->post('caste_certificate_of_father_temp'));
            $caste_certificate_of_father = $casteCertificateOfFather["upload_status"]?$casteCertificateOfFather["uploaded_path"]:null;
        } else { 
            $casteCertificateOfFather = cifileupload("caste_certificate_of_father");
            $caste_certificate_of_father = $casteCertificateOfFather["upload_status"]?$casteCertificateOfFather["uploaded_path"]:null;
        }

        if (strlen($this->input->post("others_temp")) > 0) {
            $others = movedigilockerfile($this->input->post('others_temp'));
            $others = $others["upload_status"]?$others["uploaded_path"]:null;
        } else {
            $others = cifileupload("others");
            $others = $others["upload_status"]?$others["uploaded_path"]:null;
        }

        // $softCopy = cifileupload("soft_copy");
        // $soft_copy = $softCopy["upload_status"]?$softCopy["uploaded_path"]:null;

        $uploadedFiles = array(
            "photo_old" => strlen($photo)?$photo:$this->input->post("photo_old"),
            "date_of_birth_old" => strlen($date_of_birth)?$date_of_birth:$this->input->post("date_of_birth_old"),
            "proof_of_residence_old" => strlen($proof_of_residence)?$proof_of_residence:$this->input->post("proof_of_residence_old"),
            "caste_certificate_of_father_old" => strlen($caste_certificate_of_father)?$caste_certificate_of_father:$this->input->post("caste_certificate_of_father_old"),
            "recomendation_certificate_old" => strlen($recomendation_certificate)?$recomendation_certificate:$this->input->post("recomendation_certificate_old"),
            "others_old" => strlen($others)?$others:$this->input->post("others_old"),
            // "soft_copy_old" => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.photo_type' => $this->input->post("photo_type"),
                'form_data.date_of_birth_type' => $this->input->post("date_of_birth_type"),
                'form_data.proof_of_residence_type' => $this->input->post("proof_of_residence_type"),
                //'form_data.caste_certificate_of_father_type' => $this->input->post("caste_certificate_of_father_type"),
                //'form_data.recomendation_certificate_type' => $this->input->post("recomendation_certificate_type"),
                'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),

                'form_data.photo' => strlen($photo)?$photo:$this->input->post("photo_old"),
                'form_data.date_of_birth' => strlen($date_of_birth)?$date_of_birth:$this->input->post("date_of_birth_old"),
                'form_data.proof_of_residence' => strlen($proof_of_residence)?$proof_of_residence:$this->input->post("proof_of_residence_old"),
                //'form_data.caste_certificate_of_father' => strlen($caste_certificate_of_father)?$caste_certificate_of_father:$this->input->post("caste_certificate_of_father_old"),
                //'form_data.recomendation_certificate' => strlen($recomendation_certificate)?$recomendation_certificate:$this->input->post("recomendation_certificate_old"),
                // 'form_data.soft_copy' => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old")
            );

            if (!empty($this->input->post("caste_certificate_of_father_type"))) {
                $data["form_data.caste_certificate_of_father_type"] = $this->input->post("caste_certificate_of_father_type");
                $data["form_data.caste_certificate_of_father"] = strlen($caste_certificate_of_father)?$caste_certificate_of_father:$this->input->post("caste_certificate_of_father_old");
            }

            if (!empty($this->input->post("recomendation_certificate_type"))) {
                $data["form_data.recomendation_certificate_type"] = $this->input->post("recomendation_certificate_type");
                $data["form_data.recomendation_certificate"] = strlen($recomendation_certificate)?$recomendation_certificate:$this->input->post("recomendation_certificate_old");
            }

            if (!empty($this->input->post("others_type"))) {
                $data["form_data.others_type"] = $this->input->post("others_type");
                $data["form_data.others"] = strlen($others)?$others:$this->input->post("others_old");
            }
            
            $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success','Your application has been successfully submitted');
            redirect('spservices/castecertificate/registration/preview/'.$objId);
        }//End of if else //End of if else
    } //End of submitfiles()

    public function queryform($objId=null) {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->registration_model->get_row(array("_id"=> new ObjectId($objId), "service_data.appl_status"=>"QS"));
            if($dbRow) {
                $data=array(
                    "service_data.service_name"=>$this->serviceName,
                    "dbrow"=>$dbRow
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('castecertificate/castecertificatequery_view',$data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found in query mode against object id : '.$objId);
                redirect('spservices/castecertificate/registration');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/castecertificate/registration');
        }//End of if else
    }//End of query()

    public function querysubmit() {        
        $objId = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");

        $this->form_validation->set_rules('photo_type', 'Photo Type', 'required');
        $this->form_validation->set_rules('date_of_birth_type', 'Date of Birth Type', 'required');
        $this->form_validation->set_rules('proof_of_residence_type', 'Proof of Residence', 'required');
        //$this->form_validation->set_rules('caste_certificate_of_father_type', 'Caste Certificate of Father', 'required');
        //$this->form_validation->set_rules('recomendation_certificate_type', 'Recomendation Certificate', 'required');

        if (($_FILES['caste_certificate_of_father']['name'] == "") && ($_FILES['recomendation_certificate']['name'] == "") && (empty($this->input->post("caste_certificate_of_father_type"))) && (empty($this->input->post("recomendation_certificate_type")))) {
            $this->form_validation->set_rules('caste_certificate_of_father_type', 'Caste Certificate of Father Type', 'required');
            $this->form_validation->set_rules('recomendation_certificate_type', 'Recomendation Certificate Type', 'required');
            $this->form_validation->set_rules('caste_certificate_of_father', 'Caste Certificate of Father Document', 'required');
            $this->form_validation->set_rules('recomendation_certificate', 'Recomendation Certificate Document', 'required');
        }

        if (empty($this->input->post("caste_certificate_of_father_old"))) {
            if (((empty($this->input->post("caste_certificate_of_father_type"))) && ($_FILES['caste_certificate_of_father']['name'] != "")) || ((!empty($this->input->post("caste_certificate_of_father_type"))) && ($_FILES['caste_certificate_of_father']['name'] == "")) ) {
            
                $this->form_validation->set_rules('caste_certificate_of_father_type', 'Caste Certificate of Father Type', 'required');
                $this->form_validation->set_rules('caste_certificate_of_father', 'Caste Certificate of Father Document', 'required');
            }
        }
        if (empty($this->input->post("recomendation_certificate_old"))) {
            if (((empty($this->input->post("recomendation_certificate_type"))) && (($_FILES['recomendation_certificate']['name'] != ""))) || ((!empty($this->input->post("recomendation_certificate_type"))) && (($_FILES['recomendation_certificate']['name'] == "")))) {
                
                $this->form_validation->set_rules('recomendation_certificate_type', 'Recomendation Certificate Type', 'required');
                $this->form_validation->set_rules('recomendation_certificate', 'Recomendation Certificate Document', 'required');
            }
        }

        // if (empty($this->input->post("soft_copy_old")) && $this->slug !== 'user') {
        //     if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {
                
        //         $this->form_validation->set_rules('soft_copy_type', 'Soft Copy Type required', 'required');
        //         $this->form_validation->set_rules('soft_copy', 'Soft Copy document required', 'required');
        //     }
        // }

        if (empty($this->input->post("others_old"))) {
            if ((empty($this->input->post("others_type"))) && (($_FILES['others']['name'] != ""))) {
            
                $this->form_validation->set_rules('others_type', 'Others Type', 'required');
            }
    
            if ((!empty($this->input->post("others_type"))) && (($_FILES['others']['name'] == ""))) {
                $this->form_validation->set_rules('others', 'Others', 'required');
            }
        }

        $this->form_validation->set_rules('appl_ref_no', 'Application Ref No.', 'trim|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $photo = cifileupload("photo");
        $photo = $photo["upload_status"]?$photo["uploaded_path"]:$this->input->post("photo_old");

        $dateOfBirth = cifileupload("date_of_birth");
        $date_of_birth = $dateOfBirth["upload_status"]?$dateOfBirth["uploaded_path"]:$this->input->post("date_of_birth_old");

        $proofOfResidence = cifileupload("proof_of_residence");
        $proof_of_residence = $proofOfResidence["upload_status"]?$proofOfResidence["uploaded_path"]:$this->input->post("proof_of_residence_old");

        $recomendationCertificate = cifileupload("recomendation_certificate");
        $recomendation_certificate = $recomendationCertificate["upload_status"]?$recomendationCertificate["uploaded_path"]:$this->input->post("recomendation_certificate_old");

        $casteCertificateOfFather = cifileupload("caste_certificate_of_father");
        $caste_certificate_of_father = $casteCertificateOfFather["upload_status"]?$casteCertificateOfFather["uploaded_path"]:$this->input->post("caste_certificate_of_father_old");

        $others = cifileupload("others");
        $others = $others["upload_status"]?$others["uploaded_path"]:$this->input->post("others_old");

        $softCopy = cifileupload("soft_copy");
        $soft_copy = $softCopy["upload_status"]?$softCopy["uploaded_path"]:null;

        $uploadedFiles = array(
            "photo_old" => strlen($photo)?$photo:$this->input->post("photo_old"),
            "date_of_birth_old" => strlen($date_of_birth)?$date_of_birth:$this->input->post("date_of_birth_old"),
            "proof_of_residence_old" => strlen($proof_of_residence)?$proof_of_residence:$this->input->post("proof_of_residence_old"),
            "caste_certificate_of_father_old" => strlen($caste_certificate_of_father)?$caste_certificate_of_father:$this->input->post("caste_certificate_of_father_old"),
            "recomendation_certificate_old" => strlen($recomendation_certificate)?$recomendation_certificate:$this->input->post("recomendation_certificate_old"),
            "others_old" => strlen($others)?$others:$this->input->post("others_old"),
            "soft_copy_old" => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->queryform($objId);
        } else {            
            $dbRow = $this->registration_model->get_by_doc_id($objId);
            if(count((array)$dbRow)) {

                $caste = $dbRow->form_data->caste;
                if ($dbRow->form_data->caste == "Manipuri including Manipuri Brahmin &amp; Manipuri Muslim") {
                    $caste = "Manipuri";
                }
                
                $data = array(
                    'form_data.caste' =>  $caste,
                    'form_data.photo_type' => $this->input->post("photo_type"),
                    'form_data.date_of_birth_type' => $this->input->post("date_of_birth_type"),
                    'form_data.proof_of_residence_type' => $this->input->post("proof_of_residence_type"),
                    //'form_data.caste_certificate_of_father_type' => $this->input->post("caste_certificate_of_father_type"),
                    //'form_data.recomendation_certificate_type' => $this->input->post("recomendation_certificate_type"),
                    'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),
    
                    'form_data.photo' => strlen($photo)?$photo:$this->input->post("photo_old"),
                    'form_data.date_of_birth' => strlen($date_of_birth)?$date_of_birth:$this->input->post("date_of_birth_old"),
                    'form_data.proof_of_residence' => strlen($proof_of_residence)?$proof_of_residence:$this->input->post("proof_of_residence_old"),
                    'form_data.caste_certificate_of_father' => strlen($caste_certificate_of_father)?$caste_certificate_of_father:$this->input->post("caste_certificate_of_father_old"),
                    'form_data.recomendation_certificate' => strlen($recomendation_certificate)?$recomendation_certificate:$this->input->post("recomendation_certificate_old"),
                    'form_data.soft_copy' => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old"),
                    'form_data.updated_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                );

                if (!empty($this->input->post("caste_certificate_of_father_type"))) {
                    $data["form_data.caste_certificate_of_father_type"] = $this->input->post("caste_certificate_of_father_type");
                    $data["form_data.caste_certificate_of_father"] = strlen($caste_certificate_of_father)?$caste_certificate_of_father:$this->input->post("caste_certificate_of_father_old");
                }
    
                if (!empty($this->input->post("recomendation_certificate_type"))) {
                    $data["form_data.recomendation_certificate_type"] = $this->input->post("recomendation_certificate_type");
                    $data["form_data.recomendation_certificate"] = strlen($recomendation_certificate)?$recomendation_certificate:$this->input->post("recomendation_certificate_old");
                }

                if (!empty($this->input->post("others_type"))) {
                    $data["form_data.others_type"] = $this->input->post("others_type");
                    $data["form_data.others"] = strlen($others)?$others:$this->input->post("others_old");
                }

                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

                $postdata = array(
                    "cscid" => "RTPS1234",
                    "cscoffice" => "NA",
                    "revert" => "NA",

                    "application_ref_no" => $dbRow->service_data->appl_ref_no,
                    "state" => $dbRow->form_data->state,
                    "district" => $dbRow->form_data->district,
                    "subDivision" => $dbRow->form_data->sub_division,
                    "circleOffice" => $dbRow->form_data->circle_office,
                    "applicantName" => $dbRow->form_data->applicant_name,
                    "applicantGender" => $dbRow->form_data->applicant_gender,
                    "applicantMobileNo" => $dbRow->form_data->mobile,
                    "emailId" => $dbRow->form_data->email,
                    "panNo" => $dbRow->form_data->pan_no,
                    "aadharNo" => $dbRow->form_data->aadhar_no,
                    "fatherName" => $dbRow->form_data->father_name,
                    "motherName" => $dbRow->form_data->mother_name,
                    //"husbandName" => $dbRow->form_data->husband_name,
                    "age" => $dbRow->form_data->dob,
                    "addressLine1" => $dbRow->form_data->address_line_1,
                    "addressLine2" => $dbRow->form_data->address_line_2,
                    "village" => $dbRow->form_data->village_town,
                    "mouza" => $dbRow->form_data->mouza,
                    "postOffice" => $dbRow->form_data->post_office,
                    "policeStation" => $dbRow->form_data->police_station,
                    "pinCode" => $dbRow->form_data->pin_code,
                    "resState" => $dbRow->form_data->state,
                    "resAddressLine1" => $dbRow->form_data->address_line_1,
                    "resAddressLine2" => $dbRow->form_data->address_line_2,
                    "resVillageTown" => $dbRow->form_data->village_town,
                    "resMouza" => $dbRow->form_data->mouza,
                    "resPostOffice" => $dbRow->form_data->post_office,
                    "resPoliceStation" => $dbRow->form_data->police_station,
                    "resPinCode" => $dbRow->form_data->pin_code,
                    "applicantCaste" => $dbRow->form_data->application_for,
                    "applicantSubCaste" => !empty($dbRow->form_data->subcaste)? $dbRow->form_data->subcaste: $dbRow->form_data->caste,
                    //"applicantReligion" => $dbRow->form_data->religion,
                    "occupationOfForefather" => "NA",
                    "isFatherMotherNameInVoterList" => "Yes",
                    "reasonForApplying" => "NA",
                    "resDistrict" => $dbRow->form_data->district,
                    "resSubdivision" => $dbRow->form_data->sub_division,
                    "resCircleOffice" => $dbRow->form_data->circle_office,
                    "houseNumber" => $dbRow->form_data->house_no,
                    "fillUpLanguage" => $dbRow->form_data->fillUpLanguage,
                    "fatherOrAncestName" => "NA",
                    "fatherOrAncestRelation" => "NA",
                    "fatherOrAncestAddressLine1" => "NA",
                    "fatherOrAncestAddressLine2" => "NA",
                    "fatherOrAncestState" => "NA",
                    "fatherOrAncestDistrict" => $dbRow->form_data->district,
                    "fatherOrAncestSubdivision" => $dbRow->form_data->sub_division,
                    "fatherOrAncestCircleOffice" => $dbRow->form_data->circle_office,
                    "fatherOrAncestMouza" => "NA",
                    "fatherOrAncestVillage" => "NA",
                    "fatherOrAncestPoliceStation" => "NA",
                    "fatherOrAncestPostOffice" => "NA",
                    "fatherOrAncestPincode" => $dbRow->form_data->pin_code,
                    "subCasteOfAncestors" => "NA",
                    "epic" => $dbRow->form_data->epic_no,
                    "service_type" => "CASTE",
    
                    'spId' => array('applId' => $dbRow->service_data->appl_id)
                );   
                
                if(!empty($photo)){
                    $photo_type = (!empty($this->input->post("photo_type")))?$this->input->post("photo_type"):$dbRow->form_data->photo_type;
                    $photo = strlen($photo)?base64_encode(file_get_contents(FCPATH.$photo)):null;
    
                    $photo = array(
                        "encl" =>  $photo,
                        "docType" => "image/jpeg",
                        "enclFor" => "Photo",
                        "enclType" => $photo_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => ["jpg","jpeg","png"]
                    );
    
                    $postdata['photo'] = $photo;
                }
    
                if(!empty($caste_certificate_of_father)){

                    $caste_certificate_of_father_type = (!empty($this->input->post("caste_certificate_of_father_type")))?$this->input->post("caste_certificate_of_father_type"):$dbRow->form_data->caste_certificate_of_father_type;
                    $caste_certificate_of_father = strlen($caste_certificate_of_father)?base64_encode(file_get_contents(FCPATH.$caste_certificate_of_father)):null;
    
                    $attachment_one = array(
                        "encl" =>  $caste_certificate_of_father,
                        "docType" => "application/pdf",
                        "enclFor" => "Caste certificate of father",
                        "enclType" => $caste_certificate_of_father_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
    
                    $postdata['AttachmentOne'] = $attachment_one;
                }
    
                if(!empty($proof_of_residence)){

                    $proof_of_residence_type = (!empty($this->input->post("proof_of_residence_type")))?$this->input->post("proof_of_residence_type"):$dbRow->form_data->proof_of_residence_type;
                    $proof_of_residence = strlen($proof_of_residence)?base64_encode(file_get_contents(FCPATH.$proof_of_residence)):null;
    
                    $attachment_three = array(
                        "encl" =>  $proof_of_residence,
                        "docType" => "application/pdf",
                        "enclFor" => "Proof of Residence",
                        "enclType" => $proof_of_residence_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
    
                    $postdata['AttachmentThree'] = $attachment_three;
                }

                if(!empty($recomendation_certificate)){

                    $recomendation_certificate_type = (!empty($this->input->post("recomendation_certificate_type")))?$this->input->post("recomendation_certificate_type"):$dbRow->form_data->recomendation_certificate_type;
                    $recomendation_certificate = strlen($recomendation_certificate)?base64_encode(file_get_contents(FCPATH.$recomendation_certificate)):null;
    
                    $attachment_five = array(
                        "encl" =>  $recomendation_certificate,
                        "docType" => "application/pdf",
                        "enclFor" => "Recomendation Certificate",
                        "enclType" => $recomendation_certificate_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
    
                    $postdata['AttachmentFive'] = $attachment_five;
                }
                
                if(!empty($others)){
                    $others_type = (!empty($this->input->post("others_type")))?$this->input->post("others_type"):$dbRow->form_data->others_type;
                    $others = strlen($others)?base64_encode(file_get_contents(FCPATH.$others)):null;
    
                    $attachment_four = array(
                        "encl" =>  $others,
                        "docType" => "application/pdf",
                        "enclFor" => "Others",
                        "enclType" => $dbRow->form_data->others_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
    
                    $postdata['AttachmentFour'] = $attachment_four;
                }
    
                // if(!empty($soft_copy)){
                //     $softCopy = strlen($soft_copy)?base64_encode(file_get_contents(FCPATH.$soft_copy)):null;
    
                //     $attachment_six = array(
                //         "encl" => $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload the Soft  Copy of Application Form",
                //         "enclType" => "Upload the Soft  Copy of Application Form",
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );
    
                //     $postdata['AttachmentSix'] = $attachment_six;
                // }

                // $json = json_encode($postdata);
                // $buffer = preg_replace( "/\r|\n/", "", $json );
                // $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $buffer);
                // fclose($myfile);
                // die;
        
                $json_obj = json_encode($postdata);
                
                $url=$this->config->item('caste_url');
                $curl = curl_init($url); 
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));                 
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }
                curl_close($curl);

                log_response($dbRow->service_data->appl_ref_no, $response);

                // var_dump($response);
                // exit();

                if(isset($error_msg)) {
                    die("CURL ERROR : ".$error_msg);
                } elseif ($response) {
                    $response = json_decode($response, true);  //pre($response);
                    if ($response["ref"]["status"] === "success") {

                        $processing_history = $dbRow->processing_history??array();
                        $processing_history[] = array(
                            "processed_by" => "Query submitted by ".$dbRow->form_data->applicant_name,
                            "action_taken" => "Query submitted",
                            "remarks" => "Query submitted by ".$dbRow->form_data->applicant_name,
                            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        );

                        $data = array(
                            "service_data.appl_status" => "QA",
                            'processing_history' => $processing_history,
                        );
        
                        $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

                        $this->session->set_flashdata('success','Your application has been successfully updated');
                        redirect('spservices/castecertificate/registration/preview/'.$objId);
                    } else {
                        $this->session->set_flashdata('fail','Unable to update data!!! Please try again.');
                        $this->queryform($objId);
                        // return $this->output
                        //     ->set_content_type('application/json')
                        //     ->set_status_header(401)
                        //     ->set_output(json_encode(array("status" => false)));
                    }//End of if else
                }//End of if
            } else {
                $this->session->set_flashdata('fail','Unable to update data!!! Please try again.');
                $this->index();
            }//End of if else
        }//End of if else      
    }//End of querysubmit()

    public function preview($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('castecertificate/castepreview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/castecertificate/registration/');
        } //End of if else
    }//End of preview()

    public function applicationpreview($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('castecertificate/casteapplication_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/castecertificate/registration/');
        } //End of if else
    }
    
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
        while ($this->registration_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        }//End of while()
        return $rtps_trans_id;
    }//End of getID()

    public function generateID($length) {
        $date = date('Y');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-CASTE/" . $date."/" .$number;
        return $str;
    } //End of generateID()

    public function track($objId=null) {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if(isset($dbRow->form_data->edistrict_ref_no ) && !empty($dbRow->form_data->edistrict_ref_no )){
            $this->load->helper('trackstatus');
            fetchEdistrictData($dbRow->form_data->edistrict_ref_no);
            $dbRow = $this->registration_model->get_by_doc_id($objId);
        }
        if(count((array)$dbRow)) {
            $data=array(
                "service_data.service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('castecertificate/castecertificatetrack_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/castecertificate/');
        }//End of if else
    }

    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
           return false;
        }//End of if else
    }//End of checkObjectId()

    private function my_transactions(){
        $user=$this->session->userdata();
        if(isset($user['role']) && !empty($user['role'])){
          redirect(base_url('iservices/admin/my-transactions'));
        }else{
          redirect(base_url('iservices/transactions'));
        }
    }

    public function download_certificate($objId = null)
    {
        if (!empty($objId)) {
            $dbRow = $this->registration_model->get_by_doc_id($objId);
            // var_dump($dbRow); die;
            if (count((array)$dbRow) && isset($dbRow->form_data->certificate)) {
                if (file_exists($dbRow->form_data->certificate)) {
                    cifiledownload($dbRow->form_data->certificate);
                }
            } else {
                $this->session->set_flashdata('errmessage', 'Something went wrong!, Please try later.');
                $this->my_transactions();
            }
        }
    }//End of download_certificate()
}//End of Castecertificate
