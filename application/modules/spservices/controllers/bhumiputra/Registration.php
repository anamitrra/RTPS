<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps {

    private $serviceName = "Application for Caste Certificate";
    private $serviceId = "CASTE";
    private $departmentId = "5655";
    private $departmentName = "WPTBC";


    public function __construct() {
        parent::__construct();
        $this->load->model('bhumiputra/caste_model');
        $this->load->helper("cifileupload");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
          }else{
            $this->slug = "user";
          }
    }//End of __construct()

  

    public function index($obj_id=null) {
        // pre($obj_id);
        $data = array("pageTitle" => "Application for Caste Certificate");
        $filter = array(
            "_id" => $obj_id ? new ObjectId($obj_id) : NULL,
            "service_data.appl_status" => "DRAFT"
        );

        $data["dbrow"] = $this->caste_model->get_row($filter);
        $data['usser_type'] = $this->slug;
       
        $this->load->view('includes/frontend/header');
        $this->load->view('bhumiputra/registration',$data);
        $this->load->view('includes/frontend/footer');
    } //End of index()

    public function submit()
    {

        //pre($this->input->post());
        $objId = $this->input->post("obj_id");
        $rtpsTransId = $this->input->post("rtps_trans_id");
        $submitMode = $this->input->post("submit_mode");
         
        $this->form_validation->set_rules('certificate_language', 'certificate_language', 'trim|required|xss_clean|strip_tags|max_length[30]');
        $this->form_validation->set_rules('application_for', 'application_for', 'trim|required|xss_clean|strip_tags|max_length[30]');

        $this->form_validation->set_rules('applicant_name', 'applicant_name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('district_id', 'district', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('applicant_gender', 'applicant_gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mobile', 'mobile', 'trim|required|xss_clean|strip_tags|max_length[10]|numeric');
        $this->form_validation->set_rules('pan_no', 'pan_no', 'trim|xss_clean|strip_tags|max_length[10]');
        $this->form_validation->set_rules('email', 'email', 'trim|xss_clean|strip_tags|valid_email');
        $this->form_validation->set_rules('epic_no', 'epic_no', 'trim|xss_clean|strip_tags|max_length[20]');
       
        $this->form_validation->set_rules('date', 'date', 'trim|required|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_rules('fatherName', 'fatherName', 'trim|required|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('motherName', 'motherName', 'trim|required|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('husbandName', 'husbandName', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('age', ' age', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('addressLine1', 'addressLine1', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('addressLine1', 'addressLine1', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('addressLine2', 'addressLine2', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('village', ' village', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('mouza', ' mouza', 'trim|xss_clean|strip_tags|max_length[100]');

        $this->form_validation->set_rules('postOffice', 'postOffice', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('policeStation', 'policeStation', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('pinCode', 'pinCode', 'trim|xss_clean|strip_tags');

        $this->form_validation->set_rules('resState', ' resState', 'trim|xss_clean|strip_tags|max_length[50]|required');
        $this->form_validation->set_rules('resAddressLine1', 'resAddressLine1', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('resAddressLine2', ' resAddressLine2', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('resVillageTown', ' resVillageTown', 'trim|xss_clean|strip_tags|max_length[100]|required');
        $this->form_validation->set_rules('resMouza', ' resMouza', 'trim|xss_clean|strip_tags|max_length[100]|required');
        $this->form_validation->set_rules('resPostOffice', ' resPostOffice', 'trim|xss_clean|strip_tags|max_length[100]|required');
        $this->form_validation->set_rules('resPoliceStation', 'resPoliceStation', 'trim|xss_clean|strip_tags|max_length[100]|required');
        $this->form_validation->set_rules('resPinCode', 'resPinCode', 'trim|required|xss_clean|strip_tags|max_length[6]|numeric');

        $this->form_validation->set_rules('applicantCaste', 'applicantCaste', 'trim|required|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('applicantSubCaste', 'applicantSubCaste', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('applicantReligion', 'applicantReligion', 'trim|xss_clean|strip_tags|max_length[30]');
        $this->form_validation->set_rules('occupationOfForefather', 'policeStation', 'trim|required|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('isFatherMotherNameInVoterList', 'isFatherMotherNameInVoterList', 'trim|required|xss_clean|strip_tags|max_length[5]');
        $this->form_validation->set_rules('reasonForApplying', 'reasonForApplying', 'trim|required|xss_clean|strip_tags|max_length[4096]');

        $this->form_validation->set_rules('resDistrict', 'resDistrict', 'trim|xss_clean|strip_tags|max_length[50]|required');
        $this->form_validation->set_rules('resSubdivision', 'resSubdivision', 'trim|xss_clean|strip_tags|max_length[50]|required');
        $this->form_validation->set_rules('resCircleOffice', 'resCircleOffice', 'trim|xss_clean|strip_tags|max_length[50]|required');
        $this->form_validation->set_rules('houseNumber', 'houseNumber', 'trim|xss_clean|strip_tags|max_length[50]');
        $this->form_validation->set_rules('fillUpLanguage', 'fillUpLanguage', 'trim|xss_clean|strip_tags|max_length[12]');
        $this->form_validation->set_rules('fatherOrAncestName', 'fatherOrAncestName', 'trim|xss_clean|strip_tags|max_length[128]');
        $this->form_validation->set_rules('fatherOrAncestRelation', 'fatherOrAncestRelation', 'trim|xss_clean|strip_tags|max_length[128]');
        $this->form_validation->set_rules('fatherOrAncestAddressLine1', 'fatherOrAncestAddressLine1', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('fatherOrAncestAddressLine2', 'fatherOrAncestAddressLine2', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('fatherOrAncestState', 'fatherOrAncestState', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('fatherOrAncestDistrict', 'fatherOrAncestDistrict', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('fatherOrAncestSubdivision', 'fatherOrAncestSubdivision', 'trim|xss_clean|strip_tags|max_length[100]');

        $this->form_validation->set_rules('fatherOrAncestCircleOffice', 'fatherOrAncestCircleOffice', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('fatherOrAncestMouza', 'fatherOrAncestMouza', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('fatherOrAncestVillage', 'fatherOrAncestVillage', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('fatherOrAncestPoliceStation', 'fatherOrAncestPoliceStation', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('fatherOrAncestPostOffice', 'fatherOrAncestPostOffice', 'trim|xss_clean|strip_tags|max_length[100]');

        $this->form_validation->set_rules('fatherOrAncestPincode', 'fatherOrAncestPincode', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('subCasteOfAncestors', 'subCasteOfAncestors', 'trim|xss_clean|strip_tags|max_length[100]');
        $this->form_validation->set_rules('epic', 'epic', 'trim|xss_clean|strip_tags|max_length[100]');

        

      
        
         $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
        } else {
            //$applicantPhoto = cifileupload("applicant_photo");
           // $applicant_photo = $applicantPhoto["upload_status"] ? $applicantPhoto["uploaded_path"] : null;

           // $inputCaptcha = $this->input->post("inputcaptcha");
           // $sessCaptcha = $this->session->userdata('captchaCode');

            $appl_ref_no = $this->getID(7);
            $rtps_trans_id =  strlen($objId) ? $rtpsTransId : $this->getID(7);

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
                );
                $rows = $this->caste_model->get_row($filter);
                if($rows === false) break;
            }

            $service_data = [
                "department_id" => $this->departmentId,
                "department_name" => $this->departmentName,
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $appl_id,
                'status' => 'DRAFT',
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submitMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => "WPTBC", // office name
                "submission_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "service_timeline" => "30",
                "appl_status" => "DRAFT",
                "rtps_trans_id" => $rtps_trans_id,

            ];

            $form_data = [

                'user_id' => $sessionUser['userId']->{'$id'},
                'user_type' => $this->slug,

                'certificate_language' => $this->input->post("certificate_language"),
                'application_for' => $this->input->post("application_for"),
                 
                'applicant_name' => $this->input->post("applicant_name"),
                'district_id' => $this->input->post("district_id"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'mobile' => $this->input->post("mobile"),
                'pan_no' => $this->input->post("pan_no"),
                'email' => $this->input->post("email"),
                'epic_no' => $this->input->post("epic_no"),
                'date' => $this->input->post("date"),
                //'state' => $this->input->post("state"),
                'fatherName' => $this->input->post("fatherName"),
                'motherName' => $this->input->post("motherName"),
                'husbandName' => $this->input->post("husbandName"),
                'age' => $this->input->post("age"),
                'addressLine1' => $this->input->post("addressLine1"),
                'addressLine2' => $this->input->post("addressLine2"),
                'village' => $this->input->post("village"),
                'mouza' => $this->input->post("mouza"),
                'postOffice' => $this->input->post("postOffice"),
                'policeStation' => $this->input->post("policeStation"),
                
                'pinCode' => $this->input->post("pinCode"),

                'resState' => $this->input->post("resState"),
                'resAddressLine1' => $this->input->post("resAddressLine1"),
                'resAddressLine2' => $this->input->post("resAddressLine2"),
                'resVillageTown' => $this->input->post("resVillageTown"),
                'resMouza' => $this->input->post("resMouza"),
                'resPostOffice' => $this->input->post("resPostOffice"),
                'resPoliceStation' => $this->input->post("resPoliceStation"),
                'resPinCode' => $this->input->post("resPinCode"),
                'applicantCaste' => $this->input->post("applicantCaste"),
                'applicantSubCaste' => $this->input->post("applicantSubCaste"),
                'applicantReligion' => $this->input->post("applicantReligion"),


                'occupationOfForefather' => $this->input->post("occupationOfForefather"),
                'isFatherMotherNameInVoterList' => $this->input->post("isFatherMotherNameInVoterList"),
                'reasonForApplying' => $this->input->post("reasonForApplying"),
                'resDistrict' => $this->input->post("resDistrict"),
                'resSubdivision' => $this->input->post("resSubdivision"),
                'resCircleOffice' => $this->input->post("resCircleOffice"),
                'houseNumber' => $this->input->post("houseNumber"),
                'fillUpLanguage' => $this->input->post("fillUpLanguage"),
                'fatherOrAncestName' => $this->input->post("fatherOrAncestName"),
                'fatherOrAncestRelation' => $this->input->post("fatherOrAncestRelation"),

                'fatherOrAncestAddressLine1' => $this->input->post("fatherOrAncestAddressLine1"),

                'fatherOrAncestAddressLine2' => $this->input->post("fatherOrAncestAddressLine2"),
                'fatherOrAncestState' => $this->input->post("fatherOrAncestState"),
                'fatherOrAncestDistrict' => $this->input->post("fatherOrAncestDistrict"),
                'fatherOrAncestSubdivision' => $this->input->post("fatherOrAncestSubdivision"),
                'fatherOrAncestCircleOffice' => $this->input->post("fatherOrAncestCircleOffice"),
                'fatherOrAncestMouza' => $this->input->post("fatherOrAncestMouza"),
                'fatherOrAncestVillage' => $this->input->post("fatherOrAncestVillage"),
                'fatherOrAncestPoliceStation' => $this->input->post("fatherOrAncestPoliceStation"),
                'fatherOrAncestPostOffice' => $this->input->post("fatherOrAncestPostOffice"),
                'fatherOrAncestPincode' => $this->input->post("fatherOrAncestPincode"),
                'subCasteOfAncestors' => $this->input->post("subCasteOfAncestors"),

                'epic' => $this->input->post("epic"),
                    // 'submit_mode' => $submitMode,
                    'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];

            if (strlen($objId)) {
                $form_data["applicant_photo_type"] = $this->input->post("applicant_photo_type");
                $form_data["applicant_photo"] = $this->input->post("applicant_photo");
                $form_data["proof_dob_type"] = $this->input->post("proof_dob_type");
                $form_data["proof_dob"] = $this->input->post("proof_dob");
                $form_data["proof_res_type"] = $this->input->post("proof_res_type");
                $form_data["proof_res"] = $this->input->post("proof_res_type");
                $form_data["caste_certificate_type"] = $this->input->post("caste_certificate_type");
                $form_data["caste_certificate"] = $this->input->post("caste_certificate");
                $form_data["recommendation_certificate"] = $this->input->post("recommendation_certificate");
                $form_data["recommendation_certificate_type"] = $this->input->post("recommendation_certificate_type");
                $form_data["other_doc_type"] = $this->input->post("other_doc_type");
                $form_data["other_doc"] = $this->input->post("other_doc");
                $form_data["soft_copy_type"] = $this->input->post("soft_copy_type");
                $form_data["soft_copy"] = $this->input->post("soft_copy");
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
                    $this->caste_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    redirect('spservices/bhumiputra/registration/fileuploads/'. $objId);
                } else {
                    $insert = $this->caste_model->insert($inputs);
                    if ($insert) {
                        $objectId = $insert['_id']->{'$id'};
                        $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                        redirect('spservices/bhumiputra/registration/fileuploads/' . $objectId);
                        exit();
                    } else {
                        $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                        $this->index();
                    } //End of if else
                } //End of if else
           // } //End of if else 
        } //End of if else
    } //End of submit()

    public function fileuploads($objId = null)
    {
        //pre("hi");
        $dbRow = $this->caste_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "pageTitle" => "Upload Attachments for Application for Issuance Of Caste Certificate",
                "obj_id" => $objId,
                "rtps_trans_id" => $dbRow->service_data->rtps_trans_id
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('bhumiputra/registrationuploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/bhumiputra/registrationuploads_view/' );
        } //End of if else
    } //End of fileuploads()

    public function finalsubmition($obj = null)
    {
        // $obj = $this->input->post('obj');
        if ($obj) {
            $dbRow = $this->caste_model->get_by_doc_id(new ObjectId($obj));
            //procesing data
            $processing_history = $dbRow->processing_history ?? array();
            $processing_history[] = array(
                "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                "action_taken" => "Application Submition",
                "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            );

            //$postdata = [];
            $postdata = array(
                'application_ref_no' => $dbRow->service_data->appl_ref_no,
                 //'applicant_name' => $dbRow->form_data->applicant_name,
    //'applicant_gender' => $dbRow->form_data->applicant_gender,
    //'mobile' => $dbRow->form_data->mobile, //set_value("mobile");
    //'pan_no' => $dbRow->form_data->pan_no,
    //'email' => $dbRow->form_data->email,
    //'epic_no' => $dbRow->form_data->epic_no,
    //'date' => $dbRow->form_data->date,
                'fatherName' => $dbRow->form_data->fatherName,

                'motherName' => $dbRow->form_data->motherName,
                'husbandName' => $dbRow->form_data->husbandName,
                'age' => $dbRow->form_data->age,
                'addressLine1' => $dbRow->form_data->addressLine1,
                'addressLine2' => $dbRow->form_data->addressLine2,
                'village' => $dbRow->form_data->village,
                'mouza' => $dbRow->form_data->mouza,
                'postOffice' => $dbRow->form_data->postOffice,
                'policeStation' => $dbRow->form_data->policeStation,
                'pinCode' => $dbRow->form_data->pinCode,
                'resState' => $dbRow->form_data->resState,
                'resAddressLine1' => $dbRow->form_data->resAddressLine1,
                'resAddressLine2' => $dbRow->form_data->resAddressLine2,
                'resVillageTown' => $dbRow->form_data->resVillageTown,
                'resMouza' => $dbRow->form_data->resMouza,
                'resPostOffice' => $dbRow->form_data->resPostOffice,
                'resPoliceStation' => $dbRow->form_data->resPoliceStation,
                'resPinCode' => $dbRow->form_data->resPinCode,
                'applicantCaste' => $dbRow->form_data->applicantCaste,
                'applicantSubCaste' => $dbRow->form_data->applicantSubCaste,
                'applicantReligion' => $dbRow->form_data->applicantReligion,
                'occupationOfForefather' => $dbRow->form_data->occupationOfForefather,
                'isFatherMotherNameInVoterList' => $dbRow->form_data->isFatherMotherNameInVoterList,
                'reasonForApplying' => $dbRow->form_data->reasonForApplying,

                'resDistrict' => $dbRow->form_data->resDistrict,
                'resSubdivision' => $dbRow->form_data->resSubdivision,
                'resCircleOffice' => $dbRow->form_data->resCircleOffice,
                'houseNumber' => $dbRow->form_data->houseNumber,
                'fillUpLanguage' => $dbRow->form_data->fillUpLanguage,
                'fatherOrAncestName' => $dbRow->form_data->fatherOrAncestName,
                'fatherOrAncestRelation' => $dbRow->form_data->fatherOrAncestRelation,
                'fatherOrAncestAddressLine1' => $dbRow->form_data->fatherOrAncestAddressLine1,
                'fatherOrAncestAddressLine2' => $dbRow->form_data->fatherOrAncestAddressLine2,
                'fatherOrAncestState' => $dbRow->form_data->fatherOrAncestState,
                'fatherOrAncestDistrict' => $dbRow->form_data->fatherOrAncestDistrict,
                'fatherOrAncestSubdivision' => $dbRow->form_data->fatherOrAncestSubdivision,
                'fatherOrAncestCircleOffice' => $dbRow->form_data->fatherOrAncestCircleOffice,

                'fatherOrAncestMouza' => $dbRow->form_data->fatherOrAncestMouza,
                'fatherOrAncestVillage' => $dbRow->form_data->fatherOrAncestVillage,
                'fatherOrAncestPoliceStation' => $dbRow->form_data->fatherOrAncestPoliceStation,
                'fatherOrAncestPostOffice' => $dbRow->form_data->fatherOrAncestPostOffice,
                'fatherOrAncestPincode' => $dbRow->form_data->fatherOrAncestPincode,
                'subCasteOfAncestors' => $dbRow->form_data->subCasteOfAncestors,
                'epic' => $dbRow->form_data->epic,
                'cscid' => "RTPS1234",
                'fillUpLanguage' => "English",
                'service_type' => "CASTE",
                'cscoffice' => "NA",
                'spId' => array('applId' => $dbRow->service_data->appl_id)
            );

            if (!empty($dbRow->form_data->applicant_photo)) {
                $applicant_photo = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->applicant_photo));

                $photo = array(
                    "encl" =>  $applicant_photo,
                    "docType" => "application/pdf",
                    "enclFor" => "Applicant Photo",
                    "enclType" => $dbRow->form_data->applicant_photo_type,
                    "id" => "65441671",
                    "doctypecode" => "7501",
                    "docRefId" => "7501",
                    "enclExtn" => "jpg","jpeg","png"
                );

                $postdata['photo'] = $photo;
            }
            if (!empty($dbRow->form_data->caste_certificate)) {
                $caste_certificate = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->caste_certificate));

                $attachment_one = array(
                    "encl" =>  $caste_certificate,
                    "docType" => "application/pdf",
                    "enclFor" => "Caste Certificate of Father",
                    "enclType" => $dbRow->form_data->caste_certificate_type,
                    "id" => "65441672",
                    "doctypecode" => "7502",
                    "docRefId" => "7502",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentOne'] = $attachment_one;
            }
            if (!empty($dbRow->form_data->proof_res)) {
                $proof_res = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->proof_res));

                $attachment_three = array(
                    "encl" =>  $proof_res,
                    "docType" => "application/pdf",
                    "enclFor" => "Proof of Residence",
                    "enclType" => $dbRow->form_data->proof_res_type,
                    "id" => "65441673",
                    "doctypecode" => "7503",
                    "docRefId" => "7503",
                    "enclExtn" => "pdf"
                );

                $postdata['Attachmentthree'] = $attachment_three;
            }
            if (!empty($dbRow->form_data->other_doc)) {
                $other_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->other_doc));

                $attachment_four = array(
                    "encl" =>  $other_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Any Other Document",
                    "enclType" => $dbRow->form_data->other_doc_type,
                    "id" => "65441674",
                    "doctypecode" => "7504",
                    "docRefId" => "7504",
                    "enclExtn" => "pdf"
                );

                $postdata['Attachmentfour'] = $attachment_four;
            }


            if (!empty($dbRow->form_data->recommendation_certificate)) {
                $recommendation_certificate = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->recommendation_certificate));

                $attachment_five = array(
                    "encl" =>  $recommendation_certificate,
                    "docType" => "application/pdf",
                    "enclFor" => "Recommendation Certificate",
                    "enclType" => $dbRow->form_data->recommendation_certificate_type,
                    "id" => "65441672",
                    "doctypecode" => "7502",
                    "docRefId" => "7502",
                    "enclExtn" => "pdf"
                );

                $postdata['Attachmentfive'] = $attachment_five;
            }
            if (!empty($dbRow->form_data->soft_copy)) {
                $soft_copy = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->soft_copy));

                $attachment_six = array(
                    "encl" =>  $soft_copy,
                    "docType" => "application/pdf",
                    "enclFor" => "Any other document",
                    "enclType" => $dbRow->form_data->soft_copy_type,
                    "id" => "65441675",
                    "doctypecode" => "7505",
                    "docRefId" => "7505",
                    "enclExtn" => "pdf"
                );
                $postdata['AttachmentSix'] = $attachment_six;
                
            }

            $url = $this->config->item('edistrict_base_url');
            $curl = curl_init($url . "postApplicationRTPSServices?apiKey=hndr5lqiifz0ki3y8iyy&rprm=xyz");
            //$json = json_encode($postdata);
            //pre($json);
            // $buffer = preg_replace( "/\r|\n/", "", $json );
            // $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
            // fwrite($myfile, $buffer);
            // fclose($myfile);
            //  die;
            //echo $curl;
            //pre(json_encode($postdata));
            

            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
            curl_close($curl);
            //pre($response);
            if ($response) {
                $response = json_decode($response);
                if ($response->ref->status === "success") {
                    $data_to_update = array(
                        'service_data.appl_status' => 'submitted',
                        'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        'processing_history' => $processing_history
                    );
                    $this->caste_model->update($obj, $data_to_update);
                    return true;
                    //return $this->output
                    //    ->set_content_type('application/json')
                    //    ->set_status_header(200)
                    //    ->set_output(json_encode(array("status" => true)));
                } else {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(401)
                        ->set_output(json_encode(array("status" => false)));
                }
            }
        }
    }






    public function submitfiles()
    {
       // pre($_FILES);
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules('applicant_photo_type', 'Applicant Photo ', 'required');
        // $this->form_validation->set_rules('proof_dob', 'Proof of Date of Birth', 'required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $applicant_photo = cifileupload("applicant_photo");
        $applicant_photo = $applicant_photo["upload_status"] ? $applicant_photo["uploaded_path"] : null;

        $proof_dob = cifileupload("proof_dob");
        $proof_dob = $proof_dob["upload_status"] ? $proof_dob["uploaded_path"] : null;

        $proof_res = cifileupload("proof_res");
        $proof_res = $proof_res["upload_status"] ? $proof_res["uploaded_path"] : null;

        $caste_certificate = cifileupload("caste_certificate");
        $caste_certificate= $caste_certificate["upload_status"] ? $caste_certificate["uploaded_path"] : null;

        

        $recommendation_certificate = cifileupload("recommendation_certificate");
        $recommendation_certificate = $recommendation_certificate["upload_status"] ? $recommendation_certificate["uploaded_path"] : null;



        $other_doc = cifileupload("other_doc");
        $other_doc = $other_doc["upload_status"] ? $other_doc["uploaded_path"] : null;

        $soft_copy = cifileupload("soft_copy");
        $soft_copy = $soft_copy["upload_status"] ? $soft_copy["uploaded_path"] : null;

        $uploadedFiles = array(
            "applicant_photo" => strlen($applicant_photo) ? $applicant_photo : $this->input->post("applicant_photo"),
            "proof_dob" => strlen($proof_dob) ? $proof_dob : $this->input->post("proof_dob"),
            "caste_certificate" => strlen($caste_certificate) ? $caste_certificate : $this->input->post("caste_certificate"),
         
            "recommendation_certificate" => strlen($recommendation_certificate) ? $recommendation_certificate : $this->input->post("recommendation_certificate"),
            "other_doc" => strlen($other_doc) ? $other_doc : $this->input->post("other_doc"),
            "soft_copy" => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.applicant_photo_type' => $this->input->post('applicant_photo_type'),
                'form_data.applicant_photo' => strlen($applicant_photo) ? $applicant_photo : $this->input->post("applicant_photo"),
                'form_data.proof_dob_type'=>$this->input->post('proof_dob_type'),
                'form_data.proof_dob' => strlen($proof_dob) ? $proof_dob : $this->input->post("proof_dob"),
                'form_data.proof_res_type' => $this->input->post('proof_res_type'),
                'form_data.proof_res' => strlen($proof_res) ? $proof_res : $this->input->post("proof_res"),
                'form_data.caste_certificate_type' => $this->input->post('caste_certificate_type'),
                'form_data.caste_certificate' => strlen($caste_certificate) ? $caste_certificate : $this->input->post("caste_certificate"),
                  
                'form_data.recommendation_certificate_type' => $this->input->post('recommendation_certificate_type'),
                'form_data.recommendation_certificate' => strlen($recommendation_certificate) ? $recommendation_certificate : $this->input->post("recommendation_certificate"),

                'form_data.other_doc_type' => $this->input->post('other_doc_type'),
                'form_data.other_doc' => strlen($other_doc) ? $other_doc : $this->input->post("other_doc"),
                'form_data.soft_copy_type' => $this->input->post('soft_copy_type'),
                'form_data.soft_copy' => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy"),
                //'land_patta' => strlen($land_patta) ? $land_patta : $this->input->post("land_patta_old"),
                //'khajna_receipt' => strlen($khajna_receipt) ? $khajna_receipt : $this->input->post("khajna_receipt_old"),
                //'form_data.soft_copy' => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
            );
            $this->caste_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/bhumiputra/registration/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()

    public function preview($objId = null)
    {
        // pre($objId);
        $dbRow = $this->caste_model->get_by_doc_id($objId);
        //pre($dbRow);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('bhumiputra/registrationpreview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/bhumiputra/registration/');
        } //End of if else
    }//End of preview()
    
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
        while ($this->caste_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
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

    public function track($objId = null)
    {
        $dbRow = $this->caste_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('bhumiputra/castetrack_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/necertificate/');
        } //End of if else
    }//End of track()

}//End of Castecertificate
