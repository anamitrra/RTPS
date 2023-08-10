<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps
{

    private $serviceName = "Issuance of Non Creamy Layer Certificate";
    private $serviceId = "NCL";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('noncreamylayercertificate/ncl_model');
        $this->load->helper("cifileupload");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->helper("log");
        $this->load->helper('smsprovider'); 
        $this->load->helper("cifileuploaddigilocker");
        $this->load->library('Digilocker_API');

        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
    } //End of __construct()

    public function index($obj_id = null){
        //here in case of back from file to form 
        if($obj_id!=null){
            //pre($obj_id);
            $data = array("pageTitle" => "Non Creamy Layer Certificate");
            $filter = array(
               "_id" => new ObjectId($obj_id),
               "service_data.appl_status" => "DRAFT"
            );
            $data["dbrow"] = $this->ncl_model->get_row($filter);
            $data['user_type'] = $this->slug;
            //$data['response'] = $response;
        
            $this->load->view('includes/frontend/header');
            $this->load->view('noncreamylayercertificate/noncreamylayer_view.php', $data);
            $this->load->view('includes/frontend/footer');

        }
        //first time
        else{
            $this->load->view('includes/frontend/header');
            $this->load->view('noncreamylayercertificate/noncreamylayer_validate_view.php');
            $this->load->view('includes/frontend/footer');
        }
    } //End of index()

 

    public function register($cert_no=null,$obj_id=null){
  
       if($cert_no==null){
            $cert_no   = $this->input->post("cert_no");
            $this->session->set_userdata('cert_no', $cert_no);
       }

       $curl = curl_init();
       //$url = $this->config->item('ncl_edistrict_fetch_url');
       
       //for testing
       $url='http://103.8.249.110:9080/RTPSWebService/getApplicationCertificateData';

       $dataArray = array(   
            'apiKey' => "hndr5lqiifz0ki3y8iyy",
            'certificateNo' => $cert_no
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
        ));

        $response1 = curl_exec($curl);
        if (curl_errno($curl) ||  curl_getinfo($curl, CURLINFO_HTTP_CODE) >= 400) {
                echo 'HTTP Error';
        }

        curl_close($curl);
        $response = json_decode($response1);
        //$this->api_response = $response;
        $this->session->set_userdata('api_response', $response);
        //condition 1
        if(isset($response->status) && $response->status=="invalid"){
            $this->session->set_flashdata('error', 'Digital Certificate is not valid');
            redirect('spservices/noncreamylayercertificate/registration');
        }
        //condition 2
        else if(isset($response)&& ($response->caste == "ST(P)" 
                                    || $response->caste == "SC" || $response->caste=="ST(H)")){

            $this->session->set_flashdata('error', 'Non Creamy Layer Certificate can only be Applied for OBC/MOBC Certificate holder');
            redirect('spservices/noncreamylayercertificate/registration');

        }
        //condition 3
        else{
            
            $data = array("pageTitle" => "Non Creamy Layer Certificate");
            $filter = array(
               "_id" => new ObjectId($obj_id),
               "service_data.appl_status" => "DRAFT"
            );
            $data["dbrow"] = $this->ncl_model->get_row($filter);
            $data['user_type'] = $this->slug;
            $data['response'] = $response;
        
            $this->load->view('includes/frontend/header');
            $this->load->view('noncreamylayercertificate/noncreamylayer_view.php', $data);
            $this->load->view('includes/frontend/footer');
        }
    } //End of validate

    public function submit()
    {
        //pre("OK");
        $api_response=$this->session->userdata("api_response");
        //pre($api_response->applicantName);

        $objId = $this->input->post("obj_id");
        $applied_user_type = $this->slug;
        $rtpsTransId = $this->input->post("rtps_trans_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");
        $appl_status = $this->input->post("appl_status");

        //$this->form_validation->set_rules('certificate_language', 'Certificate Language', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('relation[]', 'Relation', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('organization_types[]', 'Organization type', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('organization_names[]', 'Organization name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('fs_designations[]', 'Designation', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('annual_salary[]', 'Gross annual salary', 'trim|required|xss_clean|strip_tags|numeric');
        $this->form_validation->set_rules('other_income[]', 'Income form other sources', 'trim|required|xss_clean|strip_tags|numeric');
        $this->form_validation->set_rules('total_income[]', 'Total income', 'trim|required|xss_clean|strip_tags|numeric');

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $cert_no=$this->session->userdata("cert_no");
            //incase some error reload the form 
            $this->register($cert_no,$obj_id);
        } else {
            $appl_ref_no = $this->getID(7);
            $sessionUser = $this->session->userdata();

            // $rtps_trans_id = strlen($objId) ? $rtpsTransId : $this->getID(7);
            if ($applied_user_type === "CSC") {
                $apply_by = $sessionUser['userId'];
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            } //End of if else

            while (1) {
                $app_id = rand(1000000, 9999999);
                $filter = array(
                    "service_data.appl_id" => $app_id
                );
                $rows = $this->ncl_model->get_row($filter);

                if ($rows == false)
                    break;
            }

            $service_data = [
                'rtps_trans_id' => $appl_ref_no,
                "department_id" => "1552",
                "department_name" => "Department of Social Justice & Empowerment",
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                // "appl_id" => strlen($objId) ? $objId : "",
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => $this->input->post("district"), // office name
                "submission_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "service_timeline" => "15 Days",
                "appl_status" => $appl_status,
            ];

            $form_data = [
                'user_id' => $sessionUser['userId']->{'$id'},
                'user_type' => $this->slug,
                'certificate_language' => $this->input->post("certificate_language"),
                'applicant_name' => $api_response->applicantName,
                'applicant_gender' => $api_response->applicantGender,
                'father_name' => $api_response->fatherName,
                'mother_name' => $api_response->motherName,
                'spouse_name' => $api_response->husbandName,
                'email' => $api_response->mailId,
                'dob' => $api_response->dateOfBirth,
                'mobile_number_api' => $api_response->mobileNo,
                'mobile_number' => $this->input->post("mobile_number"),
                'aadhaar_number' => $api_response->aadharNo,
                'pan_number' => $api_response->panNo,
                'cscid' => $api_response->cscId,
                'fillUpLanguage' => $api_response->fillUpLanguage,

                'house_no' => $this->input->post("house_no"),
                'state' => $this->input->post("state"),
                'district' => $district = $api_response->districtName,
                'district_id' => $this->input->post("district_id"),
                'sub_division' => $api_response->subDivisionName,
                'sub_division_id' => $this->input->post("sub_division_id"),
                'revenue_circle' => $api_response->revenueCircleName,
                'revenue_circle_id' => $this->input->post("revenue_circle_id"),
                'mouza' => $api_response->mauzaPermanent,
                'village' => $api_response->townPermanent,
                'police_station' => $api_response->policeStationPermanent,
                'pin_code' => $api_response->pinPermanent,
                'post_office' => $api_response->postOfficePermanent,

                'caste_name' => $api_response->caste,
                'community_name' => $api_response->nameOfCommunity,
                'financial_status' => array(
                    'relation' => $this->input->post("relation"),
                    'organization_types' => $this->input->post("organization_types"),
                    'organization_names' => $this->input->post("organization_names"),
                    'fs_designations' => $this->input->post("fs_designations"),
                    'annual_salary' => $this->input->post("annual_salary"),
                    'other_income' => $this->input->post("other_income"),
                    'total_income' => $this->input->post("total_income"),
                ),

                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];

            if (strlen($objId)) {
                $form_data["residential_proof_type"] = $this->input->post("residential_proof_type");
                $form_data["residential_proof"] = $this->input->post("residential_proof");
                $form_data["obc_type"] = $this->input->post("obc_type");
                $form_data["obc"] = $this->input->post("obc");
                $form_data["income_certificate_type"] = $this->input->post("income_certificate_type");
                $form_data["income_certificate"] = $this->input->post("income_certificate");
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

            if (strlen($objId)) {
                $this->ncl_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/noncreamylayercertificate/registration/fileuploads/' . $objId);
            } else {
                $insert = $this->ncl_model->insert($inputs);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    // $this->ncl_model->update_where(['_id' => new ObjectId($insert['_id']->{'$id'})], array("service_data.appl_id" => $insert['_id']->{'$id'}));
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    redirect('spservices/noncreamylayercertificate/registration/fileuploads/' . $objectId);
                    exit();
                } else {
                    $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                    $this->index();
                } //End of if else
            } //End of if else
        } //End of if else
    } //End of submit()

    public function fileuploads($objId = null)
    {
        $dbRow = $this->ncl_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "pageTitle" => "Attached Enclosures for " . $this->serviceName,
                "obj_id" => $objId,
                "dbrow" => $dbRow
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('noncreamylayercertificate/noncreamylayer_uploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/nextofkin/registration');
        } //End of if else
    } //End of fileuploads()

    public function submitfiles()
    {

        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules('residential_proof_type', "Residential proof", 'required');
        $this->form_validation->set_rules('obc_type', 'OBC', 'required');
        $this->form_validation->set_rules('income_certificate_type', "Income certificate", 'required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        
        if (strlen($this->input->post("residential_proof_temp")) > 0) {
            $residentialProof = movedigilockerfile($this->input->post('residential_proof_temp'));
            $residential_proof = $residentialProof["upload_status"] ? $residentialProof["uploaded_path"] : null;
        }
        else{
            $residentialProof = cifileupload("residential_proof");
            $residential_proof = $residentialProof["upload_status"] ? $residentialProof["uploaded_path"] : null;
        }
        if (strlen($this->input->post("obc_temp")) > 0) {
            $obcDoc = movedigilockerfile($this->input->post('obc_temp'));
            $obc = $obcDoc["upload_status"] ? $obcDoc["uploaded_path"] : null;
        }
        else{
            $obcDoc = cifileupload("obc");
            $obc = $obcDoc["upload_status"] ? $obcDoc["uploaded_path"] : null;
        }
        if (strlen($this->input->post("income_certificate_temp")) > 0) {
            $incomeCertificate = movedigilockerfile($this->input->post('income_certificate_temp'));
            $income_certificate = $incomeCertificate["upload_status"] ? $incomeCertificate["uploaded_path"] : null;
        }
        else{
            $incomeCertificate = cifileupload("income_certificate");
            $income_certificate = $incomeCertificate["upload_status"] ? $incomeCertificate["uploaded_path"] : null;
        }
        if (strlen($this->input->post("other_doc_temp")) > 0) {
            $otherDoc = movedigilockerfile($this->input->post('other_doc_temp'));
            $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;
        }
        else{
            $otherDoc = cifileupload("other_doc");
            $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;
        }

        $softCopy = cifileupload("soft_copy");
        $soft_copy = $softCopy["upload_status"] ? $softCopy["uploaded_path"] : null;


        $uploadedFiles = array(
            "residential_proof_old" => strlen($residential_proof) ? $residential_proof : $this->input->post("residential_proof_old"),
            "obc_old" => strlen($obc) ? $obc : $this->input->post("obc_old"),
            "income_certificate_old" => strlen($income_certificate) ? $income_certificate : $this->input->post("income_certificate_old"),
            "other_doc_old" => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
            "soft_copy_old" => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
        );

        if(empty($uploadedFiles["residential_proof_old"])){
             $this->form_validation->set_rules('Residential proof', 'Residential proof Document', 'required');
        }
        if(empty($uploadedFiles["obc_old"])){
             $this->form_validation->set_rules('OBC', 'OBC Document', 'required');
        }
        if(empty($uploadedFiles["income_certificate_old"])){
             $this->form_validation->set_rules('Income Certificate', 'Income Certificate  Document', 'required');
        }


        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                // 'service_data.appl_id' => $objId,
                'form_data.residential_proof_type' => $this->input->post("residential_proof_type"),
                'form_data.income_certificate_type' => $this->input->post("income_certificate_type"),
                'form_data.obc_type' => $this->input->post("obc_type"),
                'form_data.other_doc_type' => $this->input->post("other_doc_type"),
                'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),
                'form_data.residential_proof' => strlen($residential_proof) ? $residential_proof : $this->input->post("residential_proof_old"),
                'form_data.income_certificate' => strlen($income_certificate) ? $income_certificate : $this->input->post("income_certificate_old"),
                'form_data.obc' => strlen($obc) ? $obc : $this->input->post("obc_old"),
                'form_data.other_doc' => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
                'form_data.soft_copy' => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
            );

            $this->ncl_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/noncreamylayercertificate/registration/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()

    public function preview($objId = null)
    {
        $dbRow = $this->ncl_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('noncreamylayercertificate/preview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/noncreamylayercertificate/registration');
        } //End of if else
    } //End of preview()

    public function view($objId = null)
    {
        $dbRow = $this->ncl_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('noncreamylayercertificate/application_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/noncreamylayercertificate/registration');
        } //End of if else
    } //End of preview()

    public function query($obj_id = null)
    {
        if ($this->checkObjectId($obj_id)) {
            $filter = array("_id" => new ObjectId($obj_id), "service_data.appl_status" => "QS", "service_data.service_id" => $this->serviceId);
            $dbRow = $this->ncl_model->get_row($filter);
            if ($dbRow) {
                $data = array(
                    "service_name" => $this->serviceName,
                    "pageTitle" => "Issuance of Non Creamy Layer Certificate",
                    "dbrow" => $dbRow
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('noncreamylayercertificate/nclquery_view.php', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $obj_id);
                redirect('spservices/noncreamylayercertificate/registration');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/noncreamylayercertificate/registration');
        } //End of if else
    } //End of index()


    public function querysubmit()
    {
        $objId = $this->input->post("obj_id");
        $sessionUser = $this->session->userdata();

        $this->form_validation->set_rules('certificate_language', 'Certificate Language', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('relation[]', 'Relation', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('organization_types[]', 'Organization type', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('organization_names[]', 'Organization name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('fs_designations[]', 'Designation', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('annual_salary[]', 'Gross annual salary', 'trim|required|xss_clean|strip_tags|numeric');
        $this->form_validation->set_rules('other_income[]', 'Income form other sources', 'trim|required|xss_clean|strip_tags|numeric');
        $this->form_validation->set_rules('total_income[]', 'Total income', 'trim|required|xss_clean|strip_tags|numeric');
        $this->form_validation->set_rules('remarks', 'Remark', 'trim|xss_clean|strip_tags');

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
        } else {

            //SAVE A BACKUP
            $dbrow = $this->ncl_model->get_by_doc_id($objId);
            $backupRow = (array)$dbrow;
            //pre($dbrow->form_data->payment_params);

            $form_data = [
                'user_id' => $sessionUser['userId']->{'$id'},
                'user_type' => $this->slug,
                'certificate_language' => $dbrow->form_data->certificate_language,
                'applicant_name' => $dbrow->form_data->applicant_name,
                'applicant_gender' => $dbrow->form_data->applicant_gender,
                'father_name' => $dbrow->form_data->father_name,
                'mother_name' => $dbrow->form_data->mother_name,
                'spouse_name' => $dbrow->form_data->spouse_name,
                'email' => $dbrow->form_data->email,
                'dob' => $dbrow->form_data->dob,
                'mobile_number' => $dbrow->form_data->mobile_number,
                'aadhaar_number' => $dbrow->form_data->aadhaar_number,
                'pan_number' => $dbrow->form_data->pan_number,

                'house_no' => $dbrow->form_data->house_no,
                'state' => $dbrow->form_data->state,
                'district' => $dbrow->form_data->district,
                'district_id' => $this->input->post("district_id"),
                'sub_division' => $dbrow->form_data->sub_division,
                'sub_division_id' => $this->input->post("sub_division_id"),
                'revenue_circle' => $dbrow->form_data->revenue_circle,
                'revenue_circle_id' => $this->input->post("revenue_circle_id"),
                'mouza' => $dbrow->form_data->mouza,
                'village' => $dbrow->form_data->village,
                'police_station' => $dbrow->form_data->police_station,
                'pin_code' => $dbrow->form_data->pin_code,
                'post_office' => $dbrow->form_data->post_office,
                'remarks' => $this->input->post("remarks"),

                'caste_name' => $dbrow->form_data->caste_name,
                'community_name' => $dbrow->form_data->community_name,
                'financial_status' => array(
                    'relation' => $dbrow->form_data->financial_status->relation,
                    'organization_types' => $dbrow->form_data->financial_status->organization_types,
                    'organization_names' => $dbrow->form_data->financial_status->organization_names,
                    'fs_designations' => $dbrow->form_data->financial_status->fs_designations,
                    'annual_salary' => $dbrow->form_data->financial_status->annual_salary,
                    'other_income' => $dbrow->form_data->financial_status->other_income,
                    'total_income' => $dbrow->form_data->financial_status->total_income,
                ),
                'residential_proof_type' => $this->input->post("residential_proof_type"),
                'residential_proof' => $this->input->post("residential_proof"),
                'obc_type' => $this->input->post("obc_type"),
                'obc' => $this->input->post("obc"),
                'income_certificate_type' => $this->input->post("income_certificate_type"),
                'income_certificate' => $this->input->post("income_certificate"),
                'other_doc_type' => $this->input->post("other_doc_type"),
                'other_doc' => $this->input->post("other_doc"),
                'soft_copy_type' => $this->input->post("soft_copy_type"),
                'soft_copy' => $this->input->post("soft_copy"),

                'updated_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            ];


            unset($backupRow["_id"]);
            if (count((array)$dbrow)) {
                $form_data["department_id"] = $dbrow->form_data->department_id;
                if ($this->slug !== "user") {
                    $form_data["no_printing_page"] = strlen($dbrow->form_data->no_printing_page) ? $dbrow->form_data->no_printing_page : "";
                    $form_data["no_scanning_page"] = strlen($dbrow->form_data->no_scanning_page) ? $dbrow->form_data->no_scanning_page : "";
                    $form_data["printing_charge_per_page"] = strlen($dbrow->form_data->printing_charge_per_page) ? $dbrow->form_data->printing_charge_per_page : "";
                    $form_data["scanning_charge_per_page"] = strlen($dbrow->form_data->scanning_charge_per_page) ? $dbrow->form_data->scanning_charge_per_page : "";
                    $form_data["service_charge"] = strlen($dbrow->form_data->service_charge) ? $dbrow->form_data->service_charge : "";
                }
                $form_data["payment_params"] = $dbrow->form_data->payment_params;
                $form_data["pfc_payment_status"] = $dbrow->form_data->pfc_payment_status;
                $form_data["pfc_payment_response"] = $dbrow->form_data->pfc_payment_response;
                $data_before_query = $backupRow;
                $service_data = $dbrow->service_data;
                $processing_history = $dbrow->processing_history;
                $status = $dbrow->status;
                $submission_date = $dbrow->submission_date;
            }

            $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data,
                'data_before_query' => $data_before_query,
                'processing_history' => $processing_history,
                'status' => $status,
                'submission_date' => $submission_date
            ];


            $this->ncl_model->update_where(['_id' => new ObjectId($objId)], $inputs);
            //pre("OK");
            $this->session->set_flashdata('success', 'Your application has been successfully updated');
            redirect('spservices/noncreamylayercertificate/registration/fileuploads/' . $objId);

            // $inputs = [
            //     'service_data' => $service_data,
            //     'form_data' => $form_data
            // ];
        }
    }

    function getID($length)
    {
        $rtps_trans_id = $this->generateID($length);
        while ($this->ncl_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        } //End of while()
        return $rtps_trans_id;
    } //End of getID()

    function getresponse()
    {
        pre($this->api_response);
        return $this->api_response;
    }

    public function generateID($length)
    {
        $date = date('Y');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-NCL/" . $date . "/" . $number;
        return $str;
    } //End of generateID()    

    public function get_age()
    {
        $dob = $this->input->post("dob");
        if (strlen($dob) == 10) {
            $date_of_birth = new DateTime($dob);
            $nowTime = new DateTime();
            $interval = $date_of_birth->diff($nowTime);
            echo $interval->format('%y Years %m Months and %d Days');
        } else {
            echo "Invalid date format";
        } //End of if else
    } //End of get_age()

    public function post_data($objId = null)
    {
        $nowTime  = date('Y-m-d H:i:s');
        $entryTime = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $dbrow = $this->ncl_model->get_by_doc_id($objId);
        if (count((array)$dbrow)) {
            $obj_id = $dbrow->{'_id'}->{'$id'};

            $processing_history = $dbrow->processing_history ?? array();
            $processing_history[] = array(
                "processed_by" => ($this->slug === 'user' ? "Application submitted by " : "Application submitted by KIOSK for ") . $dbrow->form_data->applicant_name,
                "action_taken" => "Application Submition",
                "remarks" => ($this->slug === 'user' ? "Application submitted by " : "Application submitted by KIOSK for ") . $dbrow->form_data->applicant_name,
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            );

            $financeDetails = array();
            $isearningSources = (isset($dbrow->form_data->financial_status->relation) && is_array($dbrow->form_data->financial_status->relation)) ? count($dbrow->form_data->financial_status->relation) : 0;
            if ($isearningSources > 0) {
                for ($i = 0; $i < $isearningSources; $i++) {
                    $dataEle = array(
                        "earningSourceRelation" => $dbrow->form_data->financial_status->relation[$i],
                        "organizationType" => $dbrow->form_data->financial_status->organization_types[$i],
                        "organizationName" => $dbrow->form_data->financial_status->organization_names[$i],
                        "designation" => $dbrow->form_data->financial_status->fs_designations[$i],
                        "annualSalary" => $dbrow->form_data->financial_status->annual_salary[$i],
                        "otherIncomeSource" => $dbrow->form_data->financial_status->other_income[$i],
                        "totalIncome" => $dbrow->form_data->financial_status->total_income[$i],
                    );

                    array_push($financeDetails, $dataEle);
                }
            }

            // pre($financeDetails);
            $api_response=$this->session->userdata("api_response");


            $data = array(
                "application_ref_no" => $dbrow->service_data->appl_ref_no, //$dbrow->email,
                "state" => $dbrow->form_data->state,
                "district" => $dbrow->form_data->district,
                "subDivision" => $dbrow->form_data->sub_division,
                "circleOffice" => $dbrow->form_data->revenue_circle,
                "applicantName" => $dbrow->form_data->applicant_name,
                "applicantGender" => $dbrow->form_data->applicant_gender,
                "applicantMobileNo" => $dbrow->form_data->mobile_number,
                "emailId" => $dbrow->form_data->email,
                "cscoffice" => "NA",
                "panNo" => $dbrow->form_data->pan_number,
                "aadharNo" => $dbrow->form_data->aadhaar_number,
                "cscid" => $api_response->cscId,
                "dateOfBirth" => $dbrow->form_data->dob,
                "nameOfCaste" => $dbrow->form_data->caste_name,
                "fatherName" => $dbrow->form_data->father_name,
                "motherName" => $dbrow->form_data->mother_name,
                "husbandName" => $dbrow->form_data->spouse_name,
                "houseNoPermanent" => $dbrow->form_data->house_no,
                "mauzaPermanent" => $dbrow->form_data->mouza,
                "townPermanent" => $dbrow->form_data->village,
                "postOfficePermanent" => $dbrow->form_data->post_office,
                "policeStationPermanent" => $dbrow->form_data->police_station,
                "pinPermanent" => $dbrow->form_data->pin_code,
                "nameOfCommunity" => $dbrow->form_data->community_name,
                "service_type" => $dbrow->service_data->service_id,
                "spId" => array("applId" => $dbrow->service_data->appl_id),
                "fillUpLanguage" => $dbrow->form_data->certificate_language,
                "FinancialDetailsofFamilyMembers" => $financeDetails,

            );

            //Document creation
            if (!empty($dbrow->form_data->residential_proof)) {
                $residential_proof = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->residential_proof));

                $attachment_zero = array(
                    "encl" =>  $residential_proof,
                    "docType" => "application/pdf",
                    "enclFor" => "Permanent resident certificate or any other proof of residency",
                    "enclType" => $dbrow->form_data->residential_proof_type,
                    // "enclType" => "Permanent resident certificate or any other proof of residency",
                    "id" => "65441671",
                    "doctypecode" => "7501",
                    "docRefId" => "7501",
                    "enclExtn" => "pdf"
                    // "enclExtn" => "jpg/jpeg"
                );

                $data['AttachmentZero'] = $attachment_zero;
            }

            if (!empty($dbrow->form_data->obc)) {
                $obc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->obc));


                $attachment_one = array(
                    "encl" =>  $obc,
                    "docType" => "application/pdf",
                    "enclFor" => "OBC / MOBC certificate issued by competent authority",
                    "enclType" => $dbrow->form_data->obc_type,
                    // "enclType" => "OBC / MOBC certificate issued by competent authority",
                    "id" => "65441672",
                    "doctypecode" => "7502",
                    "docRefId" => "7502",
                    "enclExtn" => "pdf"
                    // "enclExtn" => "jpg/jpeg"
                );

                $data['AttachmentOne'] = $attachment_one;
            }

            if (!empty($dbrow->form_data->income_certificate)) {
                $income_certificate = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->income_certificate));


                $attachment_two = array(
                    "encl" =>  $income_certificate,
                    "docType" => "application/pdf",
                    "enclFor" => "Income certificate of parents",
                    "enclType" => $dbrow->form_data->income_certificate_type,
                    // "enclType" => "Income certificate of parents",
                    "id" => "65441673",
                    "doctypecode" => "7503",
                    "docRefId" => "7503",
                    "enclExtn" => "pdf"
                    // "enclExtn" => "jpg/jpeg"
                );

                $data['AttachmentTwo'] = $attachment_two;
            }

            if (!empty($dbrow->form_data->soft_copy)) {
                $soft_copy = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->soft_copy));

                $attachment_three = array(
                    "encl" =>  $soft_copy,
                    "docType" => "application/pdf",
                    "enclFor" => "Upload the Soft copy of the applicant form",
                    "enclType" => $dbrow->form_data->soft_copy_type,
                    // "enclType" => "Upload the Soft copy of the applicant form",
                    "id" => "65441674",
                    "doctypecode" => "7504",
                    "docRefId" => "7504",
                    "enclExtn" => "pdf"
                    // "enclExtn" => "jpg/jpeg"
                );

                $data['AttachmentThree'] = $attachment_three;
            }

            if (!empty($dbrow->form_data->other_doc)) {
                $other_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->other_doc));

                $attachment_four = array(
                    "encl" =>  $other_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Any other document",
                    "enclType" => $dbrow->form_data->other_doc_type,
                    "Any other document",
                    "id" => "65441675",
                    "doctypecode" => "7505",
                    "docRefId" => "7505",
                    "enclExtn" => "pdf"
                    // "enclExtn" => "jpg/jpeg"
                );

                $data['AttachmentFour'] = $attachment_four;
            }

            $json_obj = json_encode($data);
            //pre($json_obj);
            $url = $this->config->item('edistrict_base_url');
            $curl = curl_init($url . "postApplicationRTPSServices?apiKey=gsknka48jy46kdxjrqzd");
            // pre($curl);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
            curl_close($curl);
            if ($response) {
                $response = json_decode($response);
                if ($response->ref->status === "success") {
                    
                    log_response($dbrow->service_data->rtps_trans_id,$response);
                    //Update to sp_applications
                    $data_to_update = array(
                        'status' => 'submitted',
                        'submission_date' => $entryTime,
                        'service_data.appl_status' => 'submitted',
                        'form_data.edistrict_ref_no'=>$response->ref->edistrict_ref_no,
                        'processing_history' => $processing_history
                    );
                    $this->ncl_model->update($obj_id, $data_to_update);

                    if ($dbrow->service_data->appl_status === "PS") {
                         //Sending submission SMS
                        $nowTime = date('Y-m-d H:i:s');
                        $sms = array(
                            "mobile" => (int)$dbrow->form_data->mobile_number,
                            "applicant_name" => $dbrow->form_data->applicant_name,
                            "service_name" => 'Non creamy Layer Certificate',
                            "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                            "app_ref_no" => $dbrow->service_data->rtps_trans_id
                        );
                        sms_provider("submission", $sms);
                        //ends
                        redirect('spservices/noncreamylayercertificate/applications/acknowledgement/' . $obj_id);
                    } else {
                        return true;
                        // return $this->output
                        //     ->set_content_type('application/json')
                        //     ->set_status_header(401)
                        //     ->set_output(json_encode(array("status" => true)));
                    }
                } else {
                    // $this->session->set_flashdata('error', 'Something went wrong please try again.');
                    // redirect('spservices/noncreamylayercertificate/registration/index/' . $obj_id);
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(401)
                        ->set_output(json_encode(array("status" => false)));
                } //End of if else
            } //End of if
        } else {
            $this->session->set_flashdata('error', 'No records found against  : ' . $objId);
            redirect('spservices/noncreamylayercertificate/');
        } //End of if else
    } //End of post_data()

    public function post_query_respose_data($objId = null)
    {
        $nowTime  = date('Y-m-d H:i:s');
        $entryTime = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $dbrow = $this->ncl_model->get_by_doc_id($objId);
        //pre($dbrow);
        if (count((array)$dbrow)) {
            $obj_id = $dbrow->{'_id'}->{'$id'};

            $processing_history = $dbrow->processing_history ?? array();
            $processing_history[] = array(
                "processed_by" => ($this->slug === "user" ? "Query submitted by " : "Query submitted by KIOSK for ") . $dbrow->form_data->applicant_name,
                "action_taken" => "Query submitted",
                "remarks" => ($this->slug === "user" ? "Query submitted by " : "Query submitted by KIOSK for ") . $dbrow->form_data->applicant_name,
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            );

            $financeDetails = array();
            $isearningSources = (isset($dbrow->form_data->financial_status->relation) && is_array($dbrow->form_data->financial_status->relation)) ? count($dbrow->form_data->financial_status->relation) : 0;
            if ($isearningSources > 0) {
                for ($i = 0; $i < $isearningSources; $i++) {
                    $dataEle = array(
                        "earningSourceRelation" => $dbrow->form_data->financial_status->relation[$i],
                        "organizationType" => $dbrow->form_data->financial_status->organization_types[$i],
                        "organizationName" => $dbrow->form_data->financial_status->organization_names[$i],
                        "designation" => $dbrow->form_data->financial_status->fs_designations[$i],
                        "annualSalary" => $dbrow->form_data->financial_status->annual_salary[$i],
                        "otherIncomeSource" => $dbrow->form_data->financial_status->other_income[$i],
                        "totalIncome" => $dbrow->form_data->financial_status->total_income[$i],
                    );

                    array_push($financeDetails, $dataEle);
                }
            }
            //pre("OK");
            $data = array(
                "application_ref_no" => $dbrow->service_data->appl_ref_no, //$dbrow->email,
                "state" => $dbrow->form_data->state,
                "district" => $dbrow->form_data->district,
                "subDivision" => $dbrow->form_data->sub_division,
                "circleOffice" => $dbrow->form_data->revenue_circle,
                "applicantName" => $dbrow->form_data->applicant_name,
                "applicantGender" => $dbrow->form_data->applicant_gender,
                "applicantMobileNo" => $dbrow->form_data->mobile_number,
                "emailId" => $dbrow->form_data->email,
                "cscoffice" => "NA",
                "panNo" => $dbrow->form_data->pan_number,
                "aadharNo" => $dbrow->form_data->aadhaar_number,
                "cscid" => "RTPS1234",
                "dateOfBirth" => $dbrow->form_data->dob,
                "nameOfCaste" => $dbrow->form_data->caste_name,
                "fatherName" => $dbrow->form_data->father_name,
                "motherName" => $dbrow->form_data->mother_name,
                "husbandName" => $dbrow->form_data->spouse_name,
                "houseNoPermanent" => $dbrow->form_data->house_no,
                "mauzaPermanent" => $dbrow->form_data->mouza,
                "townPermanent" => $dbrow->form_data->village,
                "postOfficePermanent" => $dbrow->form_data->post_office,
                "policeStationPermanent" => $dbrow->form_data->police_station,
                "pinPermanent" => $dbrow->form_data->pin_code,
                "nameOfCommunity" => $dbrow->form_data->community_name,
                "service_type" => $dbrow->service_data->service_id,
                "spId" => array("applId" => $dbrow->service_data->appl_id),
                //"fillUpLanguage" => "English",
                "fillUpLanguage" => $dbrow->form_data->certificate_language,
                // "name_prefix" => $dbrow->form_data->name_prefix,

                // "application_ref_no" => '', //$dbrow->email,
                // "spId" => array("applId" => $dbrow->rtps_trans_id),
                // "payment_ref_no" => time(),
                // "payment_date" => date("Y-m-d"),
                "FinancialDetailsofFamilyMembers" =>  $financeDetails,
                "revert" => "NA"
            );

            //Document creation
            if (!empty($dbrow->form_data->residential_proof)) {
                $residential_proof = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->residential_proof));

                $attachment_zero = array(
                    "encl" =>  $residential_proof,
                    "docType" => "application/pdf",
                    "enclFor" => "Permanent resident certificate or any other proof of residency",
                    "enclType" => $dbrow->form_data->residential_proof_type,
                    "id" => "65441671",
                    "doctypecode" => "7501",
                    "docRefId" => "7501",
                    "enclExtn" => "pdf"
                    // "enclExtn" => "jpg/jpeg"
                );

                $data['AttachmentZero'] = $attachment_zero;
            }

            if (!empty($dbrow->form_data->obc)) {
                $obc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->obc));


                $attachment_one = array(
                    "encl" =>  $obc,
                    "docType" => "application/pdf",
                    "enclFor" => "OBC / MOBC certificate issued by competent authority",
                    "enclType" => $dbrow->form_data->obc_type,
                    "id" => "65441672",
                    "doctypecode" => "7502",
                    "docRefId" => "7502",
                    "enclExtn" => "pdf"
                    // "enclExtn" => "jpg/jpeg"
                );

                $data['AttachmentOne'] = $attachment_one;
            }

            if (!empty($dbrow->form_data->income_certificate)) {
                $income_certificate = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->income_certificate));


                $attachment_two = array(
                    "encl" =>  $income_certificate,
                    "docType" => "application/pdf",
                    "enclFor" => "Income certificate of parents",
                    "enclType" => $dbrow->form_data->income_certificate_type,
                    "id" => "65441673",
                    "doctypecode" => "7503",
                    "docRefId" => "7503",
                    "enclExtn" => "pdf"
                    // "enclExtn" => "jpg/jpeg"
                );

                $data['AttachmentTwo'] = $attachment_two;
            }

            if (!empty($dbrow->form_data->soft_copy)) {
                $soft_copy = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->soft_copy));

                $attachment_three = array(
                    "encl" =>  $soft_copy,
                    "docType" => "application/pdf",
                    "enclFor" => "Upload the Soft copy of the applicant form",
                    "enclType" => $dbrow->form_data->soft_copy_type,
                    "id" => "65441674",
                    "doctypecode" => "7504",
                    "docRefId" => "7504",
                    "enclExtn" => "pdf"
                    // "enclExtn" => "jpg/jpeg"
                );

                $data['AttachmentThree'] = $attachment_three;
            }

            if (!empty($dbrow->form_data->other_doc)) {
                $other_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->other_doc));

                $attachment_four = array(
                    "encl" =>  $other_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Any other document",
                    "enclType" => $dbrow->form_data->other_doc_type,
                    "id" => "65441675",
                    "doctypecode" => "7505",
                    "docRefId" => "7505",
                    "enclExtn" => "pdf"
                    // "enclExtn" => "jpg/jpeg"
                );

                $data['AttachmentFour'] = $attachment_four;
            }
            //pre($data);
            $json_obj = json_encode($data);
            $url = $this->config->item('edistrict_base_url');
            $curl = curl_init($url . "postApplicationRTPSServices?apiKey=gsknka48jy46kdxjrqzd");
            // pre($curl);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
            curl_close($curl);
            if ($response) {
                $response1 = json_decode($response);
                if ($response1->ref->status === "success") {
                    //Update to sp_applications
                    $data_to_update = array(
                        'status' => 'QA',
                        'form_data.updated_at' => $entryTime,
                        'service_data.appl_status' => 'Submitted',
                        'processing_history' => $processing_history
                    );

                    $this->ncl_model->update($obj_id, $data_to_update);
                    //pre("OK111");
                    // redirect('spservices/noncreamylayercertificate/applications/acknowledgement/' . $obj_id);
                    $this->session->set_flashdata('success', 'Your query response has been submitted successfully.');
                    redirect('iservices/transactions');
                } else {
                    //pre("Not OK");
                    $this->session->set_flashdata('error', 'Something went wrong please try again.');
                    redirect('spservices/noncreamylayercertificate/registration/query/' . $obj_id);
                    // return $this->output
                    //     ->set_content_type('application/json')
                    //     ->set_status_header(401)
                    //     ->set_output(json_encode(array("status" => false)));
                } //End of if else
            } //End of if
        } else {
            $this->session->set_flashdata('fail', 'Unable to update data!!! Please try again.');
            redirect('spservices/noncreamylayercertificate/');
        } //End of if else
    } //End of post_data()


    private function checkObjectId($obj)
    {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        } //End of if else
    } //End of checkObjectId()

}//End of Non creamy layer certificate