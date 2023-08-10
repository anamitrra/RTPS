<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Reinitiate_application_pdbr extends frontend
{

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('delayedbirth/delayed_birth_model');
        $this->load->config('spconfig');
        $this->load->helper("log");
    } //End of __construct()



    public function post_data($edistrict_ref_no = null)
    {
        if (!empty($edistrict_ref_no)) {
            $filter = array(
                "form_data.edistrict_ref_no" => $edistrict_ref_no,
                "service_data.service_id" => "PDBR"
            );
            $dbRow = $this->delayed_birth_model->get_row($filter);

            if (!empty($dbRow)) {
                $obj = $dbRow->{'_id'}->{'$id'};

                if (($dbRow->service_data->appl_status != "submitted") && ($dbRow->service_data->appl_status != "QA")) {
                    return array(
                        "message" => "Application already in DRAFT mood or landed in eDistrict portal.!",
                        "status" => false
                    );
                }

                if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")) {

                    //$postdata = [];
                    $postdata = array(
                        'application_ref_no' => $dbRow->service_data->appl_ref_no,
                        'applicantName' => $dbRow->form_data->applicant_name,
                        'applicantGender' => $dbRow->form_data->applicant_gender,
                        'applicantMobileNo' => $dbRow->form_data->mobile,
                        'emailId' => $dbRow->form_data->email,
                        'relationWithNewBorn' => $dbRow->form_data->newborn_relation,
                        'panNo' => $dbRow->form_data->pan_no,
                        'aadharNo' => $dbRow->form_data->aadhar_no,
                        'newBornName' => $dbRow->form_data->newborn_name,
                        'dateofBirth' => $dbRow->form_data->dob,
                        'newBornGender' => $dbRow->form_data->newborn_gender,
                        'fathersName' => $dbRow->form_data->father_name,
                        'mothersName' => $dbRow->form_data->mother_name,
                        'placeofBirth' => $dbRow->form_data->newborn_birthplace,
                        'reasonForLate' => $dbRow->form_data->late_reason,
                        'state' => $dbRow->form_data->state,
                        'district' => $dbRow->form_data->district,
                        'subDivision' => $dbRow->form_data->subdivision,
                        'circleOffice' => $dbRow->form_data->revenuecircle,
                        'newBornVillageorTown' => $dbRow->form_data->village,
                        'newBornPin' => $dbRow->form_data->pin_code,
                        'cscid' => "RTPS1234",
                        'fillUpLanguage' => "English",
                        'service_type' => "PDBR",
                        'cscoffice' => "NA",
                        'spId' => array('applId' => $dbRow->service_data->appl_id)
                    );

                    if($dbRow->service_data->appl_status =="QA"){
                        $postdata['revert'] = "NA";
                    }

                    if (!empty($dbRow->form_data->other_relation))
                        $postdata['relationWithNewBornOther'] = $dbRow->form_data->other_relation;

                    if ($dbRow->form_data->newborn_birthplace == "Hospital") {
                        $postdata['nameOfHospital'] = $dbRow->form_data->hospital_name;
                        $postdata['addressHospital'] = $dbRow->form_data->address_hospital;
                    } else if ($dbRow->form_data->newborn_birthplace == "House") {
                        $postdata['addressHospital'] = $dbRow->form_data->address_hospital;
                    } else if ($dbRow->form_data->newborn_birthplace == "Other") {
                        $postdata['placeofBirthOther'] = $dbRow->form_data->other_placeofbirth;
                    }


                    if (!empty($dbRow->form_data->age_proof)) {
                        $age_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->age_proof));

                        $attachment_one = array(
                            "encl" =>  $age_proof,
                            "docType" => "application/pdf",
                            "enclFor" => "Register Hospital Govt. / Pvt. Certificate regarding Birth or Age Proof (any)",
                            "enclType" => $dbRow->form_data->age_proof_type,
                            "id" => "65441673",
                            "doctypecode" => "7503",
                            "docRefId" => "7503",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentOne'] = $attachment_one;
                    }
                    if (!empty($dbRow->form_data->address_proof)) {
                        $address_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->address_proof));

                        $attachment_two = array(
                            "encl" =>  $address_proof,
                            "docType" => "application/pdf",
                            "enclFor" => "School Certificate/Admit Card (for age 6 and above) or parent's details",
                            "enclType" => $dbRow->form_data->address_proof_type,
                            "id" => "65441674",
                            "doctypecode" => "7504",
                            "docRefId" => "7504",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentTwo'] = $attachment_two;
                    }
                    if (!empty($dbRow->form_data->affidavit)) {
                        $affidavit = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->affidavit));

                        $attachment_three = array(
                            "encl" =>  $affidavit,
                            "docType" => "application/pdf",
                            "enclFor" => "Affidavit",
                            "enclType" => $dbRow->form_data->affidavit_type,
                            "id" => "65441672",
                            "doctypecode" => "7502",
                            "docRefId" => "7502",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentThree'] = $attachment_three;
                    }
                    if (!empty($dbRow->form_data->other_doc)) {
                        $other_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->other_doc));

                        $attachment_four = array(
                            "encl" =>  $other_doc,
                            "docType" => "application/pdf",
                            "enclFor" => "Any other document",
                            "enclType" => $dbRow->form_data->other_doc_type,
                            "id" => "65441675",
                            "doctypecode" => "7505",
                            "docRefId" => "7505",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentFour'] = $attachment_four;
                    }
                    if (!empty($dbRow->form_data->soft_copy)) {
                        $soft_copy = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->soft_copy));

                        $attachment_zero = array(
                            "encl" =>  $soft_copy,
                            "docType" => "application/pdf",
                            "enclFor" => "Upload Scanned Copy of the Application Form",
                            "enclType" => $dbRow->form_data->soft_copy_type,
                            "id" => "65441673",
                            "doctypecode" => "7503",
                            "docRefId" => "7504",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentZero'] = $attachment_zero;
                    }

                    // $json = json_encode($postdata);
                    // $buffer = preg_replace( "/\r|\n/", "", $json );
                    // $myfile = fopen("D:\\TESTDATA\\".$dbRow->form_data->applicant_name.".txt", "a") or die("Unable to open file!");
                    // fwrite($myfile, $buffer);
                    // fclose($myfile);

                    $url = $this->config->item('delayed_birth_url');
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
                            $this->delayed_birth_model->update($obj, $data_to_update);
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
                    } else {
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
}//End of Necapi