<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Reinitiate_application_caste extends frontend
{

    private $serviceId = "CASTE";
    private $serverToken = "080042cad6356ad5dc0a720c18b53b8e53d4c274";

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('castecertificate/registration_model');
        $this->load->config('spconfig');
        $this->load->helper('smsprovider');
        $this->load->helper("log");
    } //End of __construct()

    public function post_data($edistrict_ref_no = null)
    {
        if (!empty($edistrict_ref_no)) {
            $filter = array(
                "form_data.edistrict_ref_no" => $edistrict_ref_no,
                "service_data.service_id" => "CASTE"
            );
            $dbRow = $this->registration_model->get_row($filter);

            if (!empty($dbRow)) {
                $obj = $dbRow->{'_id'}->{'$id'};

                if (($dbRow->service_data->appl_status != "submitted") && ($dbRow->service_data->appl_status != "QA")) {
                    return array(
                        "message" => "Application already in DRAFT mood or landed in eDistrict portal.!",
                        "status" => false
                    );
                }

                if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")) {

                    $caste = $dbRow->form_data->caste;
                    if ($dbRow->form_data->caste == "Manipuri including Manipuri Brahmin &amp; Manipuri Muslim") {
                        $caste = "Manipuri";
                        $data_info = array(
                            'form_data.caste' => $caste,
                        );
                        $this->registration_model->update($obj, $data_info);
                    }

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
                        "applicantSubCaste" => !empty($dbRow->form_data->subcaste)? $dbRow->form_data->subcaste: $caste,
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
    
                    if($dbRow->service_data->appl_status =="QA"){
                        $postdata['revert'] = "NA";
                    }

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
                    
                    log_response($dbRow->service_data->appl_ref_no, $response);
                    if (curl_errno($curl)) {
                        $error_msg = curl_error($curl);
                    }
                    curl_close($curl);
                    if (isset($error_msg)) {
                        die("CURL ERROR : " . $error_msg);
                    } elseif (!empty($response)) {
                        $response = json_decode($response);
                        if (isset($response->ref->status) && $response->ref->status === "success") {
                            $data_to_update = array(
                                //'service_data.appl_status' => 'submitted',
                                'form_data.edistrict_ref_no' => $response->ref->edistrict_ref_no,
                            );
                            $this->registration_model->update($obj, $data_to_update);
                            return array(
                                "edistrict_ref_no" => $response->ref->edistrict_ref_no,
                                "message" => "Successfully submitted.!",
                                "status" => true
                            );
                        } else {
                            return array(
                                "message" => "No response received from the eDistrict POST API!",
                                "status" => false
                            );
                        }
                    }else{
                        return array(
                            "message" => "No response received from the eDistrict POST API!",
                            "status" => false
                        );
                    }
                    
                } else {
                    return array(
                        "message" => "Payment not done.!",
                        "status" => false
                    );
                }
            } else {

                return array(
                    "message" => "No record found.!",
                    "status" => false
                );
            }
        } else {
            return array(
                "message" => "Invalid data.!",
                "status" => false
            );
        }
    }

    private function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            } //End of if 
        } //End of if else
        return $headers;
    } //End of getAuthorizationHeader()

    private function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            } //End of if
        } //End of if
        return null;
    } //End of getBearerToken()
}//End of Necapi