<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Reinitiate_application_inc extends frontend
{

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('income/income_model');
        $this->load->config('spconfig');
        $this->load->helper('smsprovider');
        $this->load->helper("log");
    } //End of __construct()



    public function post_data($edistrict_ref_no = null)
    {
        if (!empty($edistrict_ref_no)) {
            $filter = array(
                "form_data.edistrict_ref_no" => $edistrict_ref_no,
                "service_data.service_id" => "INC"
            );
            $dbRow = $this->income_model->get_row($filter);

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
                        'aadharNo' => $dbRow->form_data->aadhar_no,
                        'panNo' => $dbRow->form_data->pan_no,
                        'emailId' => $dbRow->form_data->email,
                        'relationship' => $dbRow->form_data->relationship,
                        'relativeName' => $dbRow->form_data->relativeName,
                        'incomeSource' => $dbRow->form_data->incomeSource,
                        'occupation' => $dbRow->form_data->occupation,
                        'totalIncome' => $dbRow->form_data->totalIncome,
                        'relationshipStatus' => $dbRow->form_data->relationshipStatus,

                        'addressLine1' => $dbRow->form_data->address_line1,
                        'addressLine2' => $dbRow->form_data->address_line2,
                        'state' => $dbRow->form_data->state,
                        'district' => $dbRow->form_data->district,
                        'subDivision' => $dbRow->form_data->subdivision,
                        'circleOffice' => $dbRow->form_data->revenuecircle,
                        'mouza' => $dbRow->form_data->mouza,
                        'villageTown' => $dbRow->form_data->village,
                        'policeStation' => $dbRow->form_data->police_st,
                        'postOffice' => $dbRow->form_data->post_office,
                        'pinCode' => $dbRow->form_data->pin_code,

                        'cscid' => "RTPS1234",
                        'fillUpLanguage' => "English",
                        'service_type' => "INC",
                        'cscoffice' => "NA",
                        'spId' => array('applId' => $dbRow->service_data->appl_id)
                    );
                    if($dbRow->service_data->appl_status =="QA"){
                        $postdata['revert'] = "NA";
                    }

                    if (!empty($dbRow->form_data->address_proof)) {
                        $address_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->address_proof));

                        $attachment_zero = array(
                            "encl" =>  $address_proof,
                            "docType" => "application/pdf",
                            "enclFor" => "Address Proof",
                            "enclType" => $dbRow->form_data->address_proof_type,
                            "id" => "65441674",
                            "doctypecode" => "7504",
                            "docRefId" => "7504",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentZero'] = $attachment_zero;
                    }
                    if (!empty($dbRow->form_data->identity_proof)) {
                        $identity_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->identity_proof));

                        $attachment_one = array(
                            "encl" =>  $identity_proof,
                            "docType" => "application/pdf",
                            "enclFor" => "Identity Proof",
                            "enclType" => $dbRow->form_data->identity_proof_type,
                            "id" => "65441673",
                            "doctypecode" => "7503",
                            "docRefId" => "7503",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentOne'] = $attachment_one;
                    }
                    if (!empty($dbRow->form_data->salaryslip)) {
                        $salaryslip = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->salaryslip));

                        $Attachment_two = array(
                            "encl" =>  $salaryslip,
                            "docType" => "application/pdf",
                            "enclFor" => "Salary Slip",
                            "enclType" => $dbRow->form_data->salaryslip_type,
                            "id" => "65441671",
                            "doctypecode" => "7501",
                            "docRefId" => "7501",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentTwo'] = $Attachment_two;
                    }
                    if (!empty($dbRow->form_data->revenuereceipt)) {
                        $revenuereceipt = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->revenuereceipt));

                        $attachment_three = array(
                            "encl" =>  $revenuereceipt,
                            "docType" => "application/pdf",
                            "enclFor" => "Land Revenue Receipt",
                            "enclType" => $dbRow->form_data->revenuereceipt_type,
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

                    $url = $this->config->item('income_url');
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
                            $this->income_model->update($obj, $data_to_update);
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