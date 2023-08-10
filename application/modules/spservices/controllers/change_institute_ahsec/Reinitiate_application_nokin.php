<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Reinitiate_application_nokin extends frontend
{

    private $serviceId = "NOKIN";
    private $serverToken = "080042cad6356ad5dc0a720c18b53b8e53d4c274";

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('nextofkin/registration_model');
        $this->load->config('spconfig');
        $this->load->helper('smsprovider');
        $this->load->helper("log");
    } //End of __construct()

    public function post_data($edistrict_ref_no = null)
    {
        if (!empty($edistrict_ref_no)) {
            $filter = array(
                "form_data.edistrict_ref_no" => $edistrict_ref_no,
                "service_data.service_id" => "NOKIN"
            );
            $dbRow = $this->registration_model->get_row($filter);

            if (!empty($dbRow)) {
                $obj = $dbRow->{'_id'}->{'$id'};

                if (($dbRow->service_data->appl_status != "submitted")&& ($dbRow->service_data->appl_status != "QA")) {
                    return array(
                        "message" => "Application already in DRAFT mood or landed in eDistrict portal.!",
                        "status" => false
                    );
                }

                if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")) {

                    $FamilyDetails = array();
                    if(count($dbRow->form_data->family_details)) {
                        foreach($dbRow->form_data->family_details as $key => $family_detail) {
                            $family_detail = array(
                                "nameOfKin" => $family_detail->name_of_kin,
                                "relationOfKin" => $family_detail->relation,
                                "ageOfKinYear" => $family_detail->age_y_on_the_date_of_application,
                                "ageOfKinMonths" =>$family_detail->age_m_on_the_date_of_application,
                            );
                            $FamilyDetails[] = $family_detail;
                        }//End of foreach()        
                    }//End of if

                    $postdata=array(
                        "dateOfBirth" => $dbRow->form_data->dob,
                        "cscid" => "RTPS1234",
                        "cscoffice" => "NA",
                        "circleOffice" => $dbRow->form_data->revenue_circle,
                        "applicantName" => $dbRow->form_data->applicant_name,
                        "applicantGender" => $dbRow->form_data->applicant_gender,
                        "applicantMobileNo" => $dbRow->form_data->mobile,
                        "emailId" => $dbRow->form_data->email,
                        "districtofDeceased" => $dbRow->form_data->deceased_district,
                        "subdivisionDeceased" => $dbRow->form_data->deceased_sub_division,
                        "villageofDeceased" => $dbRow->form_data->deceased_village,
                        "townPermanent" => $dbRow->form_data->deceased_town,
                        "pinPermanent" => $dbRow->form_data->deceased_pin_code,
                        "FamilyDetails" => $FamilyDetails,
                        "application_ref_no" => $dbRow->service_data->appl_ref_no,
                        "service_type" => "NOK",
                        "district" => $dbRow->form_data->district,
                        "subDivision" => $dbRow->form_data->sub_division,
                        "revenueCircleofDeceased" => $dbRow->form_data->deceased_revenue_circle,
                        "policeStationPermanent" => $dbRow->form_data->deceased_police_station,
                        "postOfficePermanent" => $dbRow->form_data->deceased_post_office,
                        "mauzaPermanent" => $dbRow->form_data->deceased_mouza,
                        "state" => "Assam",
                        "panNo" => $dbRow->form_data->pan_no,
                        "aadharNo" => $dbRow->form_data->aadhar_no,
                        "DeceasedName" => $dbRow->form_data->name_of_deceased,
                        "deceasedGender" => $dbRow->form_data->deceased_gender,
                        "dateOfDeath" => $dbRow->form_data->deceased_dod,
                        "DeathReason" => $dbRow->form_data->reason_of_death,
                        "PlaceofDeath" => $dbRow->form_data->place_of_death,
                        "fatherofDeceased" => $dbRow->form_data->father_name_of_deceased,
                        "fatherName" => $dbRow->form_data->father_name,
                        "motherName" => $dbRow->form_data->mother_name,
                        "husbandName" => $dbRow->form_data->spouse_name,
                        "Relation" => $dbRow->form_data->relationship_with_deceased,
                        "fillUpLanguage" => "English",

                        'spId'=>array('applId'=>$dbRow->service_data->appl_id)
                    );
                    if($dbRow->service_data->appl_status =="QA"){
                        $postdata['revert'] = "NA";
                    }

                    if(!empty($dbRow->form_data->affidavit)){
                        $affidavit = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->affidavit));

                        $attachment_one = array(
                            "encl" =>  $affidavit,
                            "docType" => "application/pdf",
                            "enclFor" => "Affidavit",
                            "enclType" => $dbRow->form_data->affidavit_type,
                            "id" => "65441673",
                            "doctypecode" => "7503",
                            "docRefId" => "7504",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentOne'] = $attachment_one;
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

                    if(!empty($dbRow->form_data->death_proof)){
                        $death_proof = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->death_proof));

                        $attachment_three = array(
                            "encl" =>  $death_proof,
                            "docType" => "application/pdf",
                            "enclFor" => "Death Proof",
                            "enclType" => $dbRow->form_data->death_proof_type,
                            "id" => "65441673",
                            "doctypecode" => "7503",
                            "docRefId" => "7504",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentThree'] = $attachment_three;
                    }

                    if(!empty($dbRow->form_data->doc_for_relationship)){
                        $doc_for_relationship = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->doc_for_relationship));

                        $attachment_two = array(
                            "encl" => $doc_for_relationship,
                            "docType" => "application/pdf",
                            "enclFor" => "Document for relationship proof",
                            "enclType" => $dbRow->form_data->doc_for_relationship_type,
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
                    //  die;

                    $url=$this->config->item('next_of_kin_url');
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