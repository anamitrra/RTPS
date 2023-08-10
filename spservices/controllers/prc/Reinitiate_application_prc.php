<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Reinitiate_application_prc extends frontend
{

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('prc/applications_model');
        $this->load->config('spconfig');
        $this->load->helper('smsprovider');
        $this->load->helper("log");
    } //End of __construct()



    public function post_data($edistrict_ref_no = null)
    {
        if (!empty($edistrict_ref_no)) {
            $filter = array(
                "form_data.edistrict_ref_no" => $edistrict_ref_no,
                "service_data.service_id" => "PRC"
            );
            $dbrow = $this->applications_model->get_row($filter);

            if (!empty($dbrow)) {
                $obj = $dbrow->{'_id'}->{'$id'};

                if (($dbrow->service_data->appl_status != "submitted") && (($dbrow->service_data->appl_status == "DRAFT") || ($dbrow->service_data->appl_status == "payment_initiated"))) {
                    return array(
                        "message" => "Application already in DRAFT mood or landed in eDistrict portal.!",
                        "status" => false
                    );
                }
                if (!empty($dbrow->form_data->pfc_payment_response->GRN) && ($dbrow->form_data->pfc_payment_response->STATUS === "Y")) {


                    if (strlen($dbrow->form_data->pa_month) < 2) {
                        $pa_month = "0" . $dbrow->form_data->pa_month;
                    } else {
                        $pa_month = $dbrow->form_data->pa_month;
                    }
                    if (strlen($dbrow->form_data->pa_year) < 2) {
                        $pa_year = "0" . $dbrow->form_data->pa_year;
                    } else {
                        $pa_year = $dbrow->form_data->pa_year;
                    }
                    if (strlen($dbrow->form_data->ca_month) < 2) {
                        $ca_month = "0" . $dbrow->form_data->ca_month;
                    } else {
                        $ca_month = $dbrow->form_data->ca_month;
                    }
                    if (strlen($dbrow->form_data->ca_year) < 2) {
                        $ca_year = "0" . $dbrow->form_data->ca_year;
                    } else {
                        $ca_year = $dbrow->form_data->ca_year;
                    }

                    $stayDurationPermanent = $pa_month . " " . $pa_year;
                    $stayDurationPresent = $ca_month . " " . $ca_year;

                    $postdata = array(

                        'application_ref_no' => $dbrow->service_data->appl_ref_no,
                        'state' => $dbrow->form_data->pa_state,
                        'district' => $dbrow->form_data->pa_district,
                        'subDivision' => $dbrow->form_data->pa_subdivision,
                        'circleOffice' => $dbrow->form_data->pa_revenuecircle,
                        'applicantName' => $dbrow->form_data->applicant_name,
                        'applicantGender' => $dbrow->form_data->applicant_gender,
                        'applicantMobileNo' => $dbrow->form_data->mobile_number,
                        'applicantMobileNo' => $dbrow->form_data->mobile_number,
                        'emailId' => $dbrow->form_data->email,
                        'panNo' => $dbrow->form_data->pan,
                        'aadharNo' => $dbrow->form_data->aadhar_no,
                        'fatherName' => $dbrow->form_data->father_name,
                        'motherName' => $dbrow->form_data->mother_name,
                        'dateOfBirth' => $dbrow->form_data->dob,
                        'husbandName' => $dbrow->form_data->spouse_name,
                        'passportNumber' => $dbrow->form_data->passport_no,
                        'houseNoPermanent' => $dbrow->form_data->pa_house_no,
                        'townPermanent' => $dbrow->form_data->pa_village,
                        'postOfficePermanent' => $dbrow->form_data->pa_post_office,
                        'pinPermanent' => $dbrow->form_data->pa_pin_code,
                        'policeStationPermanent' => $dbrow->form_data->pa_police_station,
                        'stayDurationPermanent' => $stayDurationPermanent,
                        'mauzaPermanent' => $dbrow->form_data->pa_mouza,
                        'pscodePermanent' => $dbrow->form_data->pa_police_station_code,
                        'pscodePresent' => $dbrow->form_data->ca_police_station_code,
                        'IsPermAddrSame' => $dbrow->form_data->address_same,
                        'houseNoPresent' => $dbrow->form_data->ca_house_no,
                        'townPresent' => $dbrow->form_data->ca_village,
                        'postOfficePresent' => $dbrow->form_data->ca_post_office,
                        'pinPresent' => $dbrow->form_data->ca_pin_code,
                        'statePresent' => $dbrow->form_data->ca_state,
                        'districtPresent' => $dbrow->form_data->ca_district,
                        'policeStationPresent' => $dbrow->form_data->ca_police_station,
                        'subdivisionPresent' => $dbrow->form_data->ca_subdivision,
                        'revenueCirclePresent' => $dbrow->form_data->ca_revenuecircle,
                        'stayDurationPresent' => $stayDurationPresent,
                        'mauzaPresent' => $dbrow->form_data->ca_mouza,
                        'ReasonOfApplication' => $dbrow->form_data->purpose,
                        'lastInsiName' => $dbrow->form_data->institute_name,
                        'isAnyCriminalRecord' => $dbrow->form_data->criminal_rec,
                        'cscid' => "RTPS1234",
                        'fillUpLanguage' => $dbrow->form_data->certificate_language,
                        'service_type' => "PRC",
                        'cscoffice' => "NA",
                        'spId' => array('applId' => $dbrow->service_data->appl_id),
                        'countryPermanent' => $dbrow->form_data->ca_country,
                        'countryPresent' => $dbrow->form_data->pa_country,
                        //'certificate_language'=>$dbrow->form_data->certificate_language,
                        'criminalRecordDetails' => "NA"
                    );

                    if (sizeof($dbrow->processing_history) > 1) {
                        $postdata['revert'] = "NA";
                    }
                    if (!empty($dbrow->form_data->passport_pic)) {
                        $passport_pic = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->passport_pic));

                        $attachment_zero = array(
                            "encl" =>  $passport_pic,
                            "docType" => "image/jpeg",
                            "enclFor" => "Passport size photograph",
                            "enclType" => $dbrow->form_data->passport_pic_type,
                            "id" => "78010001",
                            "doctypecode" => "7801",
                            "docRefId" => "7801",
                            "enclExtn" => "jpeg"
                        );

                        $postdata['photo'] = $attachment_zero;
                    }

                    if (!empty($dbrow->form_data->voter_doc)) {
                        $voter_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->voter_doc));

                        $attachment_one = array(
                            "encl" =>  $voter_doc,
                            "docType" => "application/pdf",
                            "enclFor" => "Certified copy of the voters list to check the linkage",
                            "enclType" => $dbrow->form_data->voter_doc_type,
                            "id" => "78010002",
                            "doctypecode" => "7802",
                            "docRefId" => "7802",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentOne'] = $attachment_one;
                    }

                    if (!empty($dbrow->form_data->property_doc)) {
                        $property_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->property_doc));

                        $attachment_two = array(
                            "encl" =>  $property_doc,
                            "docType" => "application/pdf",
                            "enclFor" => "Records of Immovable Property, if any, with up-to-date Land Revenue Paid receipt.",
                            "enclType" => $dbrow->form_data->property_doc_type,
                            "id" => "78010003",
                            "doctypecode" => "7803",
                            "docRefId" => "7803",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentTwo'] = $attachment_two;
                    }
                    if (!empty($dbrow->form_data->forefathers_doc)) {
                        $forefathers_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->forefathers_doc));

                        $attachment_three = array(
                            "encl" =>  $forefathers_doc,
                            "docType" => "application/pdf",
                            "enclFor" => "Documents related to parents and forefathers having continuously resided in Assam for a minimum period of 50 years or Documents related to guardian having continuously resided in Assam for a minimum period of 20 years",
                            "enclType" => $dbrow->form_data->forefathers_doc_type,
                            "id" => "78010004",
                            "doctypecode" => "7804",
                            "docRefId" => "7804",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentThree'] = $attachment_three;
                    }

                    if (!empty($dbrow->form_data->address_doc)) {
                        $address_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->address_doc));

                        $attachment_four = array(
                            "encl" =>  $address_doc,
                            "docType" => "application/pdf",
                            "enclFor" => "Upload One Address proof documents of Self or Parent’s",
                            "enclType" => $dbrow->form_data->address_doc_type,
                            "id" => "78010005",
                            "doctypecode" => "7805",
                            "docRefId" => "7805",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentFour'] = $attachment_four;
                    }
                    if (!empty($dbrow->form_data->emp_doc)) {
                        $emp_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->emp_doc));

                        $attachment_five = array(
                            "encl" =>  $emp_doc,
                            "docType" => "application/pdf",
                            "enclFor" => "Employment Certificate issued by the employer showing joining in present place of posting, if any",
                            "enclType" => $dbrow->form_data->emp_proof_type,
                            "id" => "78010006",
                            "doctypecode" => "7806",
                            "docRefId" => "7806",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentFive'] = $attachment_five;
                    }
                    if (!empty($dbrow->form_data->passport_doc)) {
                        $passport_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->passport_doc));

                        $attachment_six = array(
                            "encl" =>  $passport_doc,
                            "docType" => "application/pdf",
                            "enclFor" => "Copy of Indian Passport or Certified copy of the NRC 1951",
                            "enclType" => $dbrow->form_data->passport_doc_type,
                            "id" => "78010007",
                            "doctypecode" => "7807",
                            "docRefId" => "7507",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentSix'] = $attachment_six;
                    }

                    if (!empty($dbrow->form_data->birth_doc)) {
                        $birth_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->birth_doc));

                        $attachment_seven = array(
                            "encl" =>  $birth_doc,
                            "docType" => "application/pdf",
                            "enclFor" => "Copy of the Birth Certificate issued by competent authority",
                            "enclType" => $dbrow->form_data->birth_doc_type,
                            "id" => "78010008",
                            "doctypecode" => "7808",
                            "docRefId" => "7808",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentSeven'] = $attachment_seven;
                    }
                    /*if(!empty($dbrow->form_data->soft_doc)){
                           $soft_doc = base64_encode(file_get_contents(FCPATH.$dbrow->form_data->soft_doc));
           
                           $attachment_eight = array(
                               "encl" =>  $soft_doc,
                               "docType" => "application/pdf",
                               "enclFor" => "Upload Scanned Copy of the Application Form",
                               "enclType" => $dbrow->form_data->soft_doc_type,
                               "id" => "78010009",
                               "doctypecode" => "7809",
                               "docRefId" => "7809",
                               "enclExtn" => "pdf"
                           );
           
                           $postdata['AttachmentEight'] = $attachment_eight;
                       }*/

                    if (!empty($dbrow->form_data->prc_doc)) {
                        $prc_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->prc_doc));

                        $attachment_nine = array(
                            "encl" =>  $prc_doc,
                            "docType" => "application/pdf",
                            "enclFor" => "Copy of PRC of any member of family of the Applicant stating relationship, if any",
                            "enclType" => $dbrow->form_data->prc_doc_type,
                            "id" => "78010010",
                            "doctypecode" => "7810",
                            "docRefId" => "7810",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentNine'] = $attachment_nine;
                    }

                    if (!empty($dbrow->form_data->admit_doc)) {
                        $admit_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->admit_doc));

                        $attachment_ten = array(
                            "encl" =>  $admit_doc,
                            "docType" => "application/pdf",
                            "enclFor" => "Copy of HSLC Certificate/Admit Card",
                            "enclType" => $dbrow->form_data->admit_doc_type,
                            "id" => "78010011",
                            "doctypecode" => "7811",
                            "docRefId" => "7811",
                            "enclExtn" => "pdf"
                        );

                        $postdata['AttachmentTen'] = $attachment_ten;
                    }

                    // $json = json_encode($postdata);
                    // $buffer = preg_replace( "/\r|\n/", "", $json );
                    // $myfile = fopen("D:\\TESTDATA\\".$dbrow->form_data->applicant_name.".txt", "a") or die("Unable to open file!");
                    // fwrite($myfile, $buffer);
                    // fclose($myfile); die;

                    $url = $this->config->item('prc_url');
                    $curl = curl_init($url);
        
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                    $response = curl_exec($curl);
                    //pre(json_encode($postdata));

                    log_response($dbrow->service_data->appl_ref_no, $response);
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
                                'form_data.edistrict_ref_no'=>$response->ref->edistrict_ref_no,
                            );
                            $this->applications_model->update($obj, $data_to_update);
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