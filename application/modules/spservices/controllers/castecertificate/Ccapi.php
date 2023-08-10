<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Ccapi extends frontend {

    private $serviceId = "CASTE";

    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('castecertificate/registration_model');
        $this->load->config('spconfig');  
        $this->load->helper('smsprovider');
        $this->load->helper('log');
    }//End of __construct()

    public function update_data()
    {
        log_response($this->input->post("applId"), $_POST);
        $applId =  $this->input->post("applId");
        $wsResponse =  $this->input->post("wsResponse");
        $decodedText = stripslashes(html_entity_decode($wsResponse));
        $resObj = json_decode($decodedText);

        if (!empty($resObj)) {
            $status = $resObj->status ?? '';
            $remarks = $resObj->remark ?? '';
            $certificate = $resObj->certificate ?? '';

            if ($status === 'QS') {
                $dbrow = $this->registration_model->get_row(array('service_data.appl_id' => (int)$applId, "service_data.service_id" => $this->serviceId));

                if (!empty($dbrow)) {
                    $processing_history = $dbrow->processing_history??array();

                    $processing_history[] = array(
                        "processed_by" => "Response from e-District Portal",
                        "action_taken" => "Query raised",
                        "remarks" => (isset($remarks) ? $remarks : ""),
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
                    $data = array(
                        'service_data.appl_status' => $status,
                        'processing_history' => $processing_history
                    );
                    $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

                    //Sending Query SMS
                    $sms = array(
                        "mobile" => (int)$dbrow->form_data->mobile,
                        "applicant_name" => $dbrow->form_data->applicant_name,
                        "service_name" => 'Caste Certificate',
                        "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
                        "app_ref_no" => $dbrow->service_data->appl_ref_no
                    );
                    sms_provider("query", $sms);
                    $resPost = array(
                        'encryptKey' => $this->input->post("encryptKey"),
                        'status' => "true"
                    );
                }else {
                    $resPost = array(
                        'encryptKey' => $this->input->post("encryptKey"),
                        'status' => "false"
                    );
                }
            } elseif ($status === 'D') {
                $dbrow = $this->registration_model->get_row(array('service_data.appl_id' => (int)$applId, "service_data.service_id" => $this->serviceId));

                if (!empty($dbrow)) {
                    $fileName = str_replace('/', '-', $dbrow->service_data->appl_ref_no) . '.pdf';
                    $dirPath = 'storage/docs' . $this->serviceId . '/';
                    if (!is_dir($dirPath)) {
                        mkdir($dirPath, 0777, true);
                        file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for Caste only</body></html>');
                    }
                    $filePath = $dirPath . $fileName;
                    file_put_contents(FCPATH . $filePath, base64_decode($certificate));
                    $processing_history = $dbrow->processing_history??array();
                    $processing_history[] = array(
                        "processed_by" => "Response from e-District Portal",
                        "action_taken" => "Certificate Delivered",
                        "remarks" => (isset($remarks) ? $remarks : ""),
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
                    $data = array(
                        'service_data.appl_status' => $status,
                        'form_data.certificate' => $filePath,
                        'processing_history' => $processing_history
                    );
                    $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

                    //Sending delivered SMS
                    $sms = array(
                        "mobile" => (int)$dbrow->form_data->mobile,
                        "applicant_name" => $dbrow->form_data->applicant_name,
                        "service_name" => 'Caste Certificate',
                        "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
                        "app_ref_no" => $dbrow->service_data->appl_ref_no,
                    );
                    sms_provider("delivery", $sms);

                    $resPost = array(
                        'encryptKey' => $this->input->post("encryptKey"),
                        'status' => "true"
                    );
                } else {
                    $resPost = array(
                        'encryptKey' => $this->input->post("encryptKey"),
                        'status' => "false"
                    );
                }
            } elseif ($status === 'F') {
                $dbrow = $this->registration_model->get_row(array('service_data.appl_id' => (int)$applId, "service_data.service_id" => $this->serviceId));

                if (!empty($dbrow)) {

                    if (($dbrow->service_data->appl_status == "D") || ($dbrow->service_data->appl_status == "R")) {
                        $resPost = array(
                            'encryptKey' => $this->input->post("encryptKey"),
                            'status' => "true"
                        );

                        $json_obj = json_encode($resPost);
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output($json_obj);
                    }

                    $processing_history = $dbrow->processing_history??array();
                    $processing_history[] = array(
                        "processed_by" => "Response from e-District Portal",
                        "action_taken" => "Forwarded",
                        "remarks" => (isset($remarks) ? $remarks : ""),
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
                    $data = array(
                        'service_data.appl_status' => $status,
                        'processing_history' => $processing_history
                    );

                    $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

                    $resPost = array(
                        'encryptKey' => $this->input->post("encryptKey"),
                        'status' => "true"
                    );
                } else {
                    $resPost = array(
                        'encryptKey' => $this->input->post("encryptKey"),
                        'status' => "false"
                    );
                }
            } elseif ($status === 'R') {
                $dbrow = $this->registration_model->get_row(array('service_data.appl_id' => (int)$applId, "service_data.service_id" => $this->serviceId));

                if (!empty($dbrow)) {

                    $processing_history = $dbrow->processing_history??array();
                    $processing_history[] = array(
                        "processed_by" => "Response from e-District Portal",
                        "action_taken" => "Rejected",
                        "remarks" => (isset($remarks) ? $remarks : ""),
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
                    $data = array(
                        'service_data.appl_status' => $status,
                        'processing_history' => $processing_history
                    );
                    $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

                    //Sending Query SMS
                    $sms = array(
                        "mobile" => (int)$dbrow->form_data->mobile,
                        "applicant_name" => $dbrow->form_data->applicant_name,
                        "service_name" => 'Caste Certificate',
                        "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
                        "app_ref_no" => $dbrow->service_data->appl_ref_no,
                        "submission_office" => "."
                    );
                    sms_provider("rejection", $sms);
                    $resPost = array(
                        'encryptKey' => $this->input->post("encryptKey"),
                        'status' => "true"
                    );
                }else {
                    $resPost = array(
                        'encryptKey' => $this->input->post("encryptKey"),
                        'status' => "false"
                    );
                }
            } else {
                $resPost = array(
                    'encryptKey' => $this->input->post("encryptKey"),
                    'status' => "false"
                );
            } //End of if else
        } else {
            $resPost = array(
                'encryptKey' => $this->input->post("encryptKey"),
                'status' => "false"
            );
        } //End of if else

        $json_obj = json_encode($resPost);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($json_obj);
    } //End of update_data()

    public function repost_application()
    {
        $appl_ref_no = $this->input->post("appl_ref_no");
        // pre($app_ref_no);
        if ($appl_ref_no) {
            // pre($applno);
            // $dbRow = $this->registration_model->get_by_doc_id(new ObjectId($obj));
            $filter = array(
                "service_data.appl_ref_no" => $appl_ref_no
            );
   
            $dbRow = $this->registration_model->get_row($filter);
            $obj = $dbRow->{'_id'}->{'$id'};

            // if ($dbRow->service_data->appl_status != "submitted") {
            //     pre("Status : ".$dbRow->service_data->appl_status." : Application isn't submitted yet");
            // }

            // if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")){

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

                if(!empty($dbRow->form_data->soft_copy)){
                    $soft_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->soft_copy));

                    $attachment_six = array(
                        "encl" => $soft_copy,
                        "docType" => "application/pdf",
                        "enclFor" => "Upload the Soft  Copy of Application Form",
                        "enclType" => "Upload the Soft  Copy of Application Form",
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentSix'] = $attachment_six;
                }

                $json = json_encode($postdata);
                pre($json);
                //     $buffer = preg_replace( "/\r|\n/", "", $json );
                //     $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
                //     fwrite($myfile, $buffer);
                //     fclose($myfile);
                //     die;

                // $url = $this->config->item('caste_url');
                // $curl = curl_init($url);
                // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                // curl_setopt($curl, CURLOPT_POST, true);
                // curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                // $response = curl_exec($curl);
                // curl_close($curl);

                // log_response($dbRow->service_data->appl_ref_no, $response);

                // //pre($response);
                // if ($response) {
                //     $response = json_decode($response);
                //     if ($response->ref->status === "success") {
                //         $data_to_update = array(
                //             'form_data.edistrict_ref_no' => $response->ref->edistrict_ref_no,
                //             'service_data.appl_status' => 'submitted',
                //         );
                //         $this->registration_model->update($obj, $data_to_update);

                //         pre($response);
                //     } else {
                //     	pre($response);
                    	
                //         pre("Unable to submit application");
                //     }
                // }
            // } else {
            //     pre("Unable to submit application");
            // }
        }else{
            pre("Pleae! Enter Application Ref No");
        }
    }

    public function checkgrn()
    { // TODO: need to check which are params to update
        $string_field = "DEPARTMENT_ID=63b2823347e4e1672643123&OFFICE_CODE=WPT021&AMOUNT=90";
        $url = "https://assamegras.gov.in/challan/models/frmgetgrn.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 3);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $string_field);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // curl_setopt ($ch, CURLOPT_CAINFO, dirname(FILE)."/123.assam.gov.in.crt");
        curl_setopt($ch, CURLOPT_FAILONERROR, FALSE);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = explode("$", $result); pre($res);
        
        // $transaction_data = $this->registration_model->get_row(array('form_data.department_id' => $DEPARTMENT_ID));
        // if ($DEPARTMENT_ID) {
        // $OFFICE_CODE = $transaction_data->form_data->payment_params->OFFICE_CODE;
        // $am1 = isset($transaction_data->form_data->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $transaction_data->form_data->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
        // $am2 = isset($transaction_data->form_data->payment_params->CHALLAN_AMOUNT) ? $transaction_data->form_data->payment_params->CHALLAN_AMOUNT : 0;
        // $AMOUNT = $am1 + $am2;
        // $string_field = "DEPARTMENT_ID=" . $DEPARTMENT_ID . "&OFFICE_CODE=" . $OFFICE_CODE . "&AMOUNT=" . $AMOUNT;
        // $url = $this->config->item('egras_grn_cin_url');
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, 3);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $string_field);
        // curl_setopt($ch, CURLOPT_HEADER, FALSE);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // // curl_setopt ($ch, CURLOPT_CAINFO, dirname(FILE)."/123.assam.gov.in.crt");
        // curl_setopt($ch, CURLOPT_FAILONERROR, FALSE);
        // curl_setopt($ch, CURLOPT_NOBODY, false);
        // $result = curl_exec($ch);
        // curl_close($ch);
        // $res = explode("$", $result); //pre($res);
        // if ($check) {
        //     return isset($res[4]) ? $res[4] : '';
        // }
        // if ($res) {
        //     $STATUS = isset($res[16]) ? $res[16] : '';
        //     $GRN = isset($res[4]) ? $res[4] : '';
        //     //  var_dump($STATUS);var_dump($GRN);die;
        //     //if($STATUS === "Y"){
        //     $this->registration_model->update_row(
        //     array('form_data.department_id' => $DEPARTMENT_ID),
        //     array(
        //         // "status"=>"WS",
        //         "form_data.pfc_payment_response.GRN" => $GRN,
        //         "form_data.pfc_payment_response.AMOUNT" => isset($res[6]) ? $res[6] : '',
        //         "form_data.pfc_payment_response.PARTYNAME" => isset($res[18]) ? $res[18] : '',
        //         "form_data.pfc_payment_response.TAXID" => isset($res[20]) ? $res[20] : '',
        //         "form_data.pfc_payment_response.DEPARTMENT_ID" => isset($res[2]) ? $res[2] : '',
        //         "form_data.pfc_payment_response.BANKNAME" => isset($res[22]) ? $res[22] : '',
        //         "form_data.pfc_payment_response.BANKCODE" => isset($res[8]) ? $res[8] : '',
        //         "form_data.pfc_payment_response.ENTRY_DATE" => isset($res[24]) ? $res[24] : '',
        //         "form_data.pfc_payment_response.STATUS" => $STATUS,
        //         "form_data.pfc_payment_response.PRN" => isset($res[12]) ? $res[12] : '',
        //         "form_data.pfc_payment_response.TRANSCOMPLETIONDATETIME" => isset($res[14]) ? $res[14] : '',
        //         "form_data.pfc_payment_response.BANKCIN" => isset($res[10]) ? $res[10] : '',
        //         'form_data.pfc_payment_status' => $STATUS
        //     )
        //     );
        //     //  }

        //     if($verify){
        //     return  array(
        //         'GRN'=>$GRN,
        //         'STATUS'=>$STATUS
        //     );
        //     }
        // }
        // }
        // redirect(base_url('spservices/applications'));
    }

    public function get_json()
    {
        $appl_ref_no = $this->input->get("appl_ref_no");
        if ($appl_ref_no) {
            //pre($obj);
            $filter = array(
                "service_data.appl_ref_no" => $appl_ref_no
            );
   
            $dbRow = $this->registration_model->get_row($filter);

            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")){

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

                $json = json_encode($postdata);
                pre($json);
                //     $buffer = preg_replace( "/\r|\n/", "", $json );
                //     $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
                //     fwrite($myfile, $buffer);
                //     fclose($myfile);
                //     die;
            } else {
                pre('Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
            }
        }

        pre("No Ref No");
    }
}//End of Necapi