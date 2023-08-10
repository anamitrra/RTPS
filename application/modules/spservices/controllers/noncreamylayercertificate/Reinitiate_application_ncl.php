<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Reinitiate_application_ncl extends frontend
{
    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('noncreamylayercertificate/ncl_model');
        $this->load->config('spconfig');
        $this->load->helper('smsprovider');
        $this->load->helper("log");
    } //End of __construct()

    public function post_data($edistrict_ref_no = null)
    {
        if (!empty($edistrict_ref_no)) {
            $filter = array(
                "service_data.appl_ref_no" => $edistrict_ref_no,
                "service_data.service_id" => "NCL"
            );
            $dbrow = $this->ncl_model->get_row($filter);
            
            if (!empty($dbrow)) {
                $obj = $dbrow->{'_id'}->{'$id'};

                if (($dbrow->service_data->appl_status == "submitted") && (($dbrow->service_data->appl_status == "DRAFT"))) {
                    return array(
                        "message" => "Application already in DRAFT mode or landed in eDistrict portal.!",
                        "status" => false
                    );
                }//if ends

                if (!empty($dbrow->form_data->pfc_payment_response->GRN) && ($dbrow->form_data->pfc_payment_response->STATUS === "Y")) 
                {
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

                    //$api_response=$this->session->userdata("api_response");

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
                        "cscid" => $dbrow->form_data->cscid,
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
                    //pre($data);
                    $json_obj = json_encode($data);
                    $url = $this->config->item('ncl_url');
                    $curl = curl_init($url);
                    
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                    $response = curl_exec($curl);

                    curl_close($curl);

                    if (isset($error_msg)) {
                                die("CURL ERROR : " . $error_msg);
                    } 
                    elseif (!empty($response)) {
                                $response = json_decode($response);
                                if (isset($response->ref->status) && $response->ref->status === "success") {
                                    $data_to_update = array(
                                        'form_data.edistrict_ref_no'=>$response->ref->edistrict_ref_no,
                                    );
                                    $this->ncl_model->update($obj, $data_to_update);
                                    return array(
                                        "edistrict_ref_no" => $response->ref->edistrict_ref_no,
                                        "message" => "Successfully submitted.!",
                                        'processing_history' => $processing_history,
                                        "status" => true
                                    );
                                } else {
                                    return array(
                                        "message" => "No response received from the eDistrict POST API!",
                                        "status" => false
                                    );
                                }
                    } 
                    else {
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
            }else {

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
}//End of api