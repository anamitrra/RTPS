<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Reinitiate_application_pddr extends frontend
{

    private $serviceId = "PDDR";
    private $serverToken = "080042cad6356ad5dc0a720c18b53b8e53d4c274";

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('delayeddeath/registration_model');
        $this->load->config('spconfig');
        $this->load->helper('smsprovider');
        $this->load->helper("log");
    } //End of __construct()

    public function post_data($edistrict_ref_no = null)
    {
        if (!empty($edistrict_ref_no)) {
            $filter = array(
                "form_data.edistrict_ref_no" => $edistrict_ref_no
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

                    $postdata=array(
                        "cscid" => "RTPS1234",
                        "cscoffice" => "NA",
                        "applicantName" => $dbRow->form_data->applicant_name,
                        "applicantMobileNo" => $dbRow->form_data->mobile,
                        "applicantGender" => $dbRow->form_data->applicant_gender,
                        "relationWithDecease" => $dbRow->form_data->relation_with_deceased,
                        //"relationWithDeceasedOther" => $dbRow->form_data->other_relation,
                        //"emailId" => $dbRow->form_data->email,
                        //"panNo" => $dbRow->form_data->pan_no,
                        //"aadharNo" => $dbRow->form_data->aadhar_no,
        
                        "deceasedName" => $dbRow->form_data->name_of_deceased,
                        "deceasedFatherName" => $dbRow->form_data->father_name_of_deceased,
                        "deceasedMotherName" => $dbRow->form_data->mother_name_of_deceased,
                        "dateofDeath" => $dbRow->form_data->deceased_dod,
                        "ageofDeceased	" => $dbRow->form_data->age_of_deceased,
                        "deceasedGender" => $dbRow->form_data->deceased_gender,
                        "placeofDeath" => $dbRow->form_data->place_of_death,
                        //"addressHospital" => $dbRow->form_data->address_of_hospital_home,
                        //"placeofDeathOther" => $dbRow->form_data->other_place_of_death,
                        "reasonForLate" => $dbRow->form_data->reason_for_late,
        
                        "state" => "Assam",
                        "district" => $dbRow->form_data->district,
                        "subDivision" => $dbRow->form_data->sub_division,
                        "circleOffice" => $dbRow->form_data->revenue_circle,
                        "deceasedVillageorTown" => $dbRow->form_data->village_town,
                        "deceasedPin" => $dbRow->form_data->pin_code,
        
                        "application_ref_no" => $dbRow->service_data->appl_ref_no,
                        "service_type" => "PDDR",
                        "fillUpLanguage" => "English",
        
                        'spId'=>array('applId'=>$dbRow->service_data->appl_id)
                    );
        
                    if($dbRow->service_data->appl_status =="QA"){
                        $postdata['revert'] = "NA";
                    }

                    if(!empty($dbRow->form_data->other_relation))
                        $postdata['relationWithDeceasedOther'] = $dbRow->form_data->other_relation;
        
                    if(!empty($dbRow->form_data->place_of_death)){
                        if (($dbRow->form_data->place_of_death == "Hospital") || ($dbRow->form_data->place_of_death == "House")) {
                            $postdata['addressHospital'] = $dbRow->form_data->address_of_hospital_home; 
                        } elseif($dbRow->form_data->place_of_death == "Other"){
                            $postdata['placeofDeathOther'] = $dbRow->form_data->other_place_of_death; 
                        }
                    }
        
                    if(!empty($dbRow->form_data->pan_no))
                        $postdata['panNo'] = $dbRow->form_data->pan_no;
        
                    if(!empty($dbRow->form_data->aadhar_no))
                        $postdata['aadharNo'] = $dbRow->form_data->aadhar_no;
        
                    if(!empty($dbRow->form_data->email))
                        $postdata['emailId'] = $dbRow->form_data->email;
        
                    if(!empty($dbRow->form_data->affidavit)){
                        $affidavit = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->affidavit));
        
                        $attachment_three = array(
                            "encl" =>  $affidavit,
                            "docType" => "application/pdf",
                            "enclFor" => "Affidavit",
                            "enclType" => $dbRow->form_data->affidavit_type,
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
        
                    if(!empty($dbRow->form_data->doctor_certificate)){
                        $doctor_certificate = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->doctor_certificate));
        
                        $attachment_one = array(
                            "encl" =>  $doctor_certificate,
                            "docType" => "application/pdf",
                            "enclFor" => "Hospital or Doctor Certificate regarding Death",
                            "enclType" => $dbRow->form_data->doctor_certificate_type,
                            "id" => "65441673",
                            "doctypecode" => "7503",
                            "docRefId" => "7504",
                            "enclExtn" => "pdf"
                        );
        
                        $postdata['AttachmentOne'] = $attachment_one;
                    }
        
                    if(!empty($dbRow->form_data->proof_residence)){
                        $proof_residence = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->proof_residence));
        
                        $attachment_two = array(
                            "encl" => $proof_residence,
                            "docType" => "application/pdf",
                            "enclFor" => "Proof of Resident",
                            "enclType" => $dbRow->form_data->proof_residence_type,
                            "id" => "65441673",
                            "doctypecode" => "7503",
                            "docRefId" => "7504",
                            "enclExtn" => "pdf"
                        );
        
                        $postdata['AttachmentTwo'] = $attachment_two;
                    }
        
                    if(!empty($dbRow->form_data->soft_copy)){
                        $soft_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->soft_copy));
        
                        $attachment_zero = array(
                            "encl" => $soft_copy,
                            "docType" => "application/pdf",
                            "enclFor" => "Upload the Soft  Copy of Application Form",
                            "enclType" => "Upload the Soft  Copy of Application Form",
                            "id" => "65441673",
                            "doctypecode" => "7503",
                            "docRefId" => "7504",
                            "enclExtn" => "pdf"
                        );
        
                        $postdata['AttachmentZero'] = $attachment_zero;
                    }
        
                    // $json = json_encode($postdata);
                    // $buffer = preg_replace( "/\r|\n/", "", $json );
                    // $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
                    // fwrite($myfile, $buffer);
                    // fclose($myfile);
                    // die;
        
                    $url=$this->config->item('delayed_death_url');
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