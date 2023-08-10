<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Reinitiate_application_scc extends frontend
{

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('senior_citizen_model');
        $this->load->config('spconfig');
        $this->load->helper("log");
    } //End of __construct()



    public function post_data($edistrict_ref_no = null)
    {
        if (!empty($edistrict_ref_no)) {
            $filter = array(
                "form_data.edistrict_ref_no" => $edistrict_ref_no,
                "service_data.service_id" => "SCTZN"
            );
            $dbRow = $this->senior_citizen_model->get_row($filter);

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
                        'fathersName' => $dbRow->form_data->father_name,
                        'applicantMobileNo' => $dbRow->form_data->mobile,
                        'emailId' => $dbRow->form_data->email,
                        'dateOfBirth' => $dbRow->form_data->dob,
                        'panNo' => $dbRow->form_data->pan_no,
                        'aadharNo' => $dbRow->form_data->aadhar_no,
                        'nameOfSpouse' => $dbRow->form_data->spouse_name,
                        'serviceType' => $dbRow->form_data->service_type,
                        'addressLine1' => $dbRow->form_data->address_line1,
                        'addressLine2' => $dbRow->form_data->address_line2,
                        'state' => $dbRow->form_data->state,
                        'district' => $dbRow->form_data->district,
                        'subDivision' => $dbRow->form_data->subdivision,
                        'circleOffice' => $dbRow->form_data->revenuecircle,
                        'mouza' => $dbRow->form_data->mouza,
                        'village' => $dbRow->form_data->village,
                        'policeStation' => $dbRow->form_data->police_st,
                        'postOffice' => $dbRow->form_data->post_office,
                        'houseNo' => $dbRow->form_data->house_no,
                        'pinCode' => $dbRow->form_data->pin_code,
                        'occupation' => $dbRow->form_data->occupation,
                        'identificationMark' => $dbRow->form_data->identification_mark,
                        'bloodGroup' => $dbRow->form_data->blood_group,
                        'isMinority' => $dbRow->form_data->minority,
                        'isFallingUnderBPL' => $dbRow->form_data->under_bpl,
                        'caste' => $dbRow->form_data->caste,
                        'isExserviceman' => $dbRow->form_data->ex_serviceman,
                        'gettingAllowance' => $dbRow->form_data->allowance,
                        'allowanceDetails' => $dbRow->form_data->allowance_details,
                        'cscid' => "RTPS1234",
                        'fillUpLanguage' => "English",
                        'service_type' => "SCC",
                        'cscoffice' => "NA",
                        'spId' => array('applId' => $dbRow->service_data->appl_id)
                    );

                    if ($dbRow->service_data->appl_status == "QA") {
                        $postdata['revert'] = "NA";
                    }

                    if (!empty($dbRow->form_data->passport_photo)) {
                        $passport_photo = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->passport_photo));

                        $photo = array(
                            "encl" =>  $passport_photo,
                            "docType" => "image/jpeg",
                            "enclFor" => "Passport size photograph",
                            "enclType" => $dbRow->form_data->passport_photo_type,
                            "id" => "65441671",
                            "doctypecode" => "7501",
                            "docRefId" => "7501",
                            "enclExtn" => "jpeg"
                        );

                        $postdata['photo'] = $photo;
                    }
                    if (!empty($dbRow->form_data->proof_of_retirement)) {
                        $proof_of_retirement = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->proof_of_retirement));

                        $attachment_one = array(
                            "encl" =>  $proof_of_retirement,
                            "docType" => "application/pdf",
                            "enclFor" => "Proof of retirement",
                            "enclType" => $dbRow->form_data->proof_of_retirement_type,
                            "id" => "65441672",
                            "doctypecode" => "7502",
                            "docRefId" => "7502",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentOne'] = $attachment_one;
                    }
                    if (!empty($dbRow->form_data->age_proof)) {
                        $age_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->age_proof));

                        $attachment_two = array(
                            "encl" =>  $age_proof,
                            "docType" => "application/pdf",
                            "enclFor" => "Age proof",
                            "enclType" => $dbRow->form_data->age_proof_type,
                            "id" => "65441673",
                            "doctypecode" => "7503",
                            "docRefId" => "7503",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentTwo'] = $attachment_two;
                    }
                    if (!empty($dbRow->form_data->address_proof)) {
                        $address_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->address_proof));

                        $attachment_three = array(
                            "encl" =>  $address_proof,
                            "docType" => "application/pdf",
                            "enclFor" => "Address proof",
                            "enclType" => $dbRow->form_data->address_proof_type,
                            "id" => "65441674",
                            "doctypecode" => "7504",
                            "docRefId" => "7504",
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

                        $attachment_five = array(
                            "encl" =>  $soft_copy,
                            "docType" => "application/pdf",
                            "enclFor" => "Upload Scanned Copy of the Application Form",
                            "enclType" => $dbRow->form_data->soft_copy_type,
                            "id" => "65441673",
                            "doctypecode" => "7503",
                            "docRefId" => "7504",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentFive'] = $attachment_five;
                    }

                    // $json = json_encode($postdata);
                    // $buffer = preg_replace( "/\r|\n/", "", $json );
                    // $myfile = fopen("D:\\TESTDATA\\".$dbRow->form_data->applicant_name.".txt", "a") or die("Unable to open file!");
                    // fwrite($myfile, $buffer);
                    // fclose($myfile);

                    $url = $this->config->item('senior_citizen_url');
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
                            $this->senior_citizen_model->update($obj, $data_to_update);
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