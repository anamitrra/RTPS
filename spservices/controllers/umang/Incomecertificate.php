<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Incomecertificate extends frontend {
    
    private $deptId = "1234";
    private $deptName = "Revenue & Disaster Management Department";
    private $serviceName = "Application for Income Certificate";
    private $serviceId = "INC";
    private $serverToken = "080042cad6356ad5dc0a720c18b53b8e53d4c274";

    public function __construct() {
        parent::__construct();
        header('Content-Type: application/json; charset=utf8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition,Content-Description');

        $this->load->model('income/income_model');
    }//End of __construct()
    
    public function post_data() {
        $bearerToken = $this->getBearerToken();
        if($this->serverToken !== $bearerToken) {
            $resArr = array(
                'rs' => "F", //Request Status
                'rc' => 401, //Response Code
                'rd' => "Authorization key does not matched", //Response Description 
                'pd' => array(
                    'success' => false,
                    'message' => 'Failed to submit data',
                    'id' => null
                )                                
            );
        } else {
            $json = file_get_contents('php://input');
            $json_obj = json_decode($json);
            $data = array(
                "user_id" => $json_obj->user_id??'',
                "user_type" => $json_obj->user_type??'',                
                "fillUpLanguage" => $json_obj->fillUpLanguage??'',
                "applicant_name" => $json_obj->applicant_name??'',
                "applicant_gender" => $json_obj->applicant_gender??'',
                "father_name" => $json_obj->father_name??'',                
                "mobile" => $json_obj->mobile??'',
                "email" => $json_obj->email??'',
                "pan_no" => $json_obj->pan_no??'',
                "relationship" => $json_obj->relationship??'',  
                "relationshipStatus" => $json_obj->relationshipStatus??'',              
                "relativeName" => $json_obj->relativeName??'',
                "incomeSource" => $json_obj->incomeSource??'',
                "occupation" => $json_obj->occupation??'',
                "totalIncome" => $json_obj->totalIncome??'',
                "address_line1" => $json_obj->address_line1??'',
                "address_line2" => $json_obj->address_line2??'',
                "state" => $json_obj->state??'',
                "district" => $json_obj->district??'',
                "subdivision" => $json_obj->subdivision??'',
                "revenuecircle" => $json_obj->revenuecircle??'',
                "mouza" => $json_obj->mouza??'',
                "village" => $json_obj->village??'',
                "police_st" => $json_obj->police_st??'',
                "post_office" => $json_obj->post_office??'',
                "pin_code" => $json_obj->pin_code??'',
                                  
                "address_proof_type" => $json_obj->address_proof_type??'',
                "address_proof" => $json_obj->address_proof??'',                  
                "identity_proof_type" => $json_obj->identity_proof_type??'',
                "identity_proof" => $json_obj->identity_proof??'',              
                "revenuereceipt_type" => $json_obj->revenuereceipt_type??'',
                "revenuereceipt" => $json_obj->revenuereceipt??'',
                "salaryslip_type" => $json_obj->salaryslip_type??'',
                "salaryslip" => $json_obj->salaryslip??'',
                "other_doc_type" => $json_obj->other_doc_type??'',
                "other_doc" => $json_obj->other_doc??'',
                "soft_copy_type" => $json_obj->soft_copy_type??'',
                "soft_copy" => $json_obj->soft_copy??''                
            );
            
            //Set validation rules
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules("user_id", "User id", "required");
            $this->form_validation->set_rules("user_type", "User type", "required");
            
            $this->form_validation->set_rules("fillUpLanguage", "Language", "required");
            $this->form_validation->set_rules("applicant_name", "Applicant name", "required");
            $this->form_validation->set_rules("applicant_gender", "Gender", "required");
            $this->form_validation->set_rules("father_name", "Father name", "required");
            $this->form_validation->set_rules("mobile", "Mobile", "required|exact_length[10]|numeric");
            $this->form_validation->set_rules("email", "Email", "valid_email");
            $this->form_validation->set_rules("pan_no", "PAN", "required|exact_length[10]|alpha_numeric");
            $this->form_validation->set_rules("relationship", "Relationship", "required");
            $this->form_validation->set_rules("relationshipStatus", "Relative prefix", "required");
            $this->form_validation->set_rules("relativeName", "Relative name", "required");
            $this->form_validation->set_rules("incomeSource", "Income source", "required");
            $this->form_validation->set_rules("occupation", "Occupation", "required");
            $this->form_validation->set_rules("totalIncome", "Total income", "required");
            $this->form_validation->set_rules("address_line1", "Address1", "required");
            $this->form_validation->set_rules("address_line2", "Address2", "required");
            $this->form_validation->set_rules("state", "State", "required");
            $this->form_validation->set_rules("district", "District", "required");
            $this->form_validation->set_rules("subdivision", "Sub division", "required");
            $this->form_validation->set_rules("revenuecircle", "Circle", "required");
            $this->form_validation->set_rules("mouza", "Mouza", "required");
            $this->form_validation->set_rules("village", "Village", "required");
            $this->form_validation->set_rules("police_st", "PS", "required");
            $this->form_validation->set_rules("post_office", "PO", "required");
            $this->form_validation->set_rules("pin_code", "PIN", "required|integer|exact_length[6]");
                           
            $this->form_validation->set_rules("address_proof_type", "Address proof type", "required");
            $this->form_validation->set_rules("address_proof", "Address proof", "required");
            $this->form_validation->set_rules("identity_proof_type", "Identity proof type", "required");
            $this->form_validation->set_rules("identity_proof", "Identity proof", "required");            
            $this->form_validation->set_rules("revenuereceipt_type", "Revenue receipt type", "required");
            $this->form_validation->set_rules("revenuereceipt", "Revenue receipt", "required");     
                        
            if ($this->form_validation->run() == FALSE) {
                $resArr = array(
                        'rs' => "F",
                        'rc' => 400,
                        'rd' => "Failure",
                        'pd' => array(
                            'success' => false,
                            'message' => 'Validation errors : '.validation_errors(),
                            'id' => null
                        )                                
                    );
            } else {
                $appl_ref_no = $this->getID(7);
                $filePrefix = str_replace('/', '-', $appl_ref_no);
                
                $address_proof = $this->cifilegenerate($data["address_proof"], $filePrefix.'_address_proof.pdf');
                $identity_proof = $this->cifilegenerate($data["identity_proof"], $filePrefix.'identity_proof.pdf');
                $revenuereceipt = $this->cifilegenerate($data["revenuereceipt"], $filePrefix.'revenuereceipt.pdf');
                $salaryslip = $this->cifilegenerate($data["salaryslip"], $filePrefix.'salaryslip.pdf');
                $other_doc = $this->cifilegenerate($data["other_doc"], $filePrefix.'other_doc.pdf');
                $soft_copy = $this->cifilegenerate($data["soft_copy"], $filePrefix.'soft_copy.pdf');
                
                $service_data = array(
                    "department_id" => $this->deptId,
                    "department_name" => $this->deptName,
                    "service_id" => $this->serviceId,
                    "service_name" => $this->serviceName,
                    "appl_ref_no" => $appl_ref_no,
                    "appl_id" => uniqid(),
                    "submission_mode" => $data["user_type"], //kiosk, online, in-person
                    "applied_by" => $data["user_id"],
                    "submission_location" => $this->deptName,
                    "district" => $data["district"],
                    "submission_date" => "",
                    "service_timeline" => "7 Days",
                    "appl_status" => "submitted",
                );
                $inputs = array('service_data' => $service_data);
                
                $form_data = $data;
                $form_data["address_proof"] = $address_proof;
                $form_data["identity_proof"] = $identity_proof;
                $form_data["revenuereceipt"] = $revenuereceipt;
                $form_data["salaryslip"] = $salaryslip;
                $form_data["other_doc"] = $other_doc;
                $form_data["soft_copy"] = $soft_copy;
                $form_data["created_at"] = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
                
                $inputs['form_data'] = $form_data;
               /*$submitStatus = $this->submit_to_backend($inputs);
                if($submitStatus['status']) {*/
                if(true) {
                    $insert = $this->income_model->insert($inputs);
                    if($insert) {
                        $resArr = array(
                            'rs' => "S",
                            'rc' => "PM0000",
                            'rd' => "Success",
                            'pd' => array(
                                'success' => true,
                                'message' => 'Successfully saved',
                                'id' => $insert['_id']->{'$id'}
                            )                              
                        );
                    } else {
                        $resArr = array(
                            'rs' => "F",
                            'rc' => 400,
                            'rd' => "Failure",
                            'pd' => array(
                                'success' => false,
                                'message' => 'Unable to save data',
                                'id' => null
                            )                                
                        );
                    }//End of if else
                } else {
                    $resArr = array(
                        'rs' => "F",
                        'rc' => 400,
                        'rd' => "Failure",
                        'pd' => array(
                            'success' => false,
                            'message' => $submitStatus['msg']??"Error in curl request",
                            'id' => null
                        )                                
                    );
                }//End of if else                    
            }//End of if else
        }//End of if else 
        
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($resArr));
    }//End of post_data()
    
    public function get_records() {
        $bearerToken = $this->getBearerToken();
        if($this->serverToken !== $bearerToken) {
            $resArr = array(
                'rs' => "F", //Request Status
                'rc' => 401, //Response Code
                'rd' => "Authorization key does not matched", //Response Description 
                'pd' => array(
                    'success' => false,
                    'message' => 'Failed to submit data',
                    'id' => null
                )                                
            );
        } else {
            $json = file_get_contents('php://input');
            $json_obj = json_decode($json);
            $data = array(
                "service_id" => $this->serviceId,
                "mobile" => $json_obj->mobile??'',
                "user_type" => $json_obj->user_type??''
            );
            //Set validation rules
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules("mobile", "Mobile", "required|exact_length[10]|numeric");    
            $this->form_validation->set_rules("user_type", "User type", "required");                    
            if ($this->form_validation->run() == FALSE) {
                $resArr = array(
                    'rs' => "F",
                    'rc' => 400,
                    'rd' => "Failure",
                    'pd' => array(
                        'success' => false,
                        'message' => 'Validation errors : '.validation_errors(),
                        'id' => null
                    )                                
                );
            } else {
                $filter = array(
                    "service_data.service_id" => $this->serviceId,
                    "form_data.mobile" => strval($data["mobile"]),
                    "form_data.user_type" => $data["user_type"]
                );
                $records = $this->income_model->get_rows($filter);
                if($records) {
                    $resArr = array(
                       'rs' => "S",
                       'rc' => "PM0000",
                       'rd' => "Success",
                       'pd' => array(
                           'success' => true,
                           'message' => 'Successfully fetched resords',
                           'records' => $records
                       )                               
                   );
                } else {
                    $resArr = array(
                        'rs' => "F",
                        'rc' => 400,
                        'rd' => "Failure",
                        'pd' => array(
                            'success' => false,
                            'message' => 'No records found',
                            'id' => null
                        )                                
                    );
                }//End of if else
            }//End of if else
        }//End of if else
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($resArr));
    }//End of get_records()
    
    private function getAuthorizationHeader() {
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
            }//End of if 
        }//End of if else
        return $headers;
    }//End of getAuthorizationHeader()

    private function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }//End of if
        }//End of if
        return null;
    }//End of getBearerToken()

    private function getID($length) {
        $rtps_trans_id = $this->generateID($length);
        while ($this->income_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        } //End of while()
        return $rtps_trans_id;
    }//End of getID()

    private function generateID($length) {
        $date = date('Y');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-INC/" . $date . "/" . $number;
        return $str;
    }//End of generateID()
    
    private function cifilegenerate($fileContent=null, $fName=null) {
        if(strlen($fileContent)) {
            $dirPath = 'storage/docs/'. date("Y") . '/' . date("m") . '/' . date("d") . '/';
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
                file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for MC only</body></html>');
            }//End of if
            $fileName = strlen($fName)?$fName:uniqid();
            $filePath = $dirPath.$fileName;
            file_put_contents(FCPATH.$filePath, base64_decode($fileContent));
            //return '<a href="'.base_url($filePath).'" class="btn btn-success" target="_blank">View File</a>';
            return $filePath;
        } else {
            return null;
        }//End of if else
    }//End of cifilegenerate()

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        } //End of if else
    }//End of checkObjectId()
    
    public function submit_to_backend($records) {
        if(count((array)$records)) {
            $postdata = array(
                'application_ref_no' => $records->service_data->appl_ref_no,
                'applicantName' => $records->form_data->applicant_name,
                'applicantGender' => $records->form_data->applicant_gender,
                'applicantMobileNo' => $records->form_data->mobile,
                'aadharNo' => $records->form_data->aadhar_no??'',
                'panNo' => $records->form_data->pan_no,
                'emailId' => $records->form_data->email,
                'relationship' => $records->form_data->relationship,
                'relativeName' => $records->form_data->relativeName,
                'incomeSource' => $records->form_data->incomeSource,
                'occupation' => $records->form_data->occupation,
                'totalIncome' => $records->form_data->totalIncome,
                'relationshipStatus' => $records->form_data->relationshipStatus,
                'addressLine1' => $records->form_data->address_line1,
                'addressLine2' => $records->form_data->address_line2,
                'state' => $records->form_data->state,
                'district' => $records->form_data->district,
                'subDivision' => $records->form_data->subdivision,
                'circleOffice' => $records->form_data->revenuecircle,
                'mouza' => $records->form_data->mouza,
                'villageTown' => $records->form_data->village,
                'policeStation' => $records->form_data->police_st,
                'postOffice' => $records->form_data->post_office,
                'pinCode' => $records->form_data->pin_code,
                'fillUpLanguage' => $records->form_data->fillUpLanguage,
                'cscid' => "RTPS1234",
                'cscoffice' => "NA",
                'service_type' => "INC",
                'spId' => array('applId' => $records->service_data->appl_id)
            );

            if (!empty($records->form_data->address_proof)) {
                $address_proof = base64_encode(file_get_contents(FCPATH . $records->form_data->address_proof));
                $attachment_zero = array(
                    "encl" =>  $address_proof,
                    "docType" => "application/pdf",
                    "enclFor" => "Address Proof",
                    "enclType" => $records->form_data->address_proof_type,
                    "id" => "65441674",
                    "doctypecode" => "7504",
                    "docRefId" => "7504",
                    "enclExtn" => "pdf"
                );
                $postdata['AttachmentZero'] = $attachment_zero;
            }
            if (!empty($records->form_data->identity_proof)) {
                $identity_proof = base64_encode(file_get_contents(FCPATH . $records->form_data->identity_proof));
                $attachment_one = array(
                    "encl" =>  $identity_proof,
                    "docType" => "application/pdf",
                    "enclFor" => "Identity Proof",
                    "enclType" => $records->form_data->identity_proof_type,
                    "id" => "65441673",
                    "doctypecode" => "7503",
                    "docRefId" => "7503",
                    "enclExtn" => "pdf"
                );
                $postdata['AttachmentOne'] = $attachment_one;
            }
            if (!empty($records->form_data->salaryslip)) {
                $salaryslip = base64_encode(file_get_contents(FCPATH . $records->form_data->salaryslip));
                $Attachment_two = array(
                    "encl" =>  $salaryslip,
                    "docType" => "application/pdf",
                    "enclFor" => "Salary Slip",
                    "enclType" => $records->form_data->salaryslip_type,
                    "id" => "65441671",
                    "doctypecode" => "7501",
                    "docRefId" => "7501",
                    "enclExtn" => "pdf"
                );
                $postdata['AttachmentTwo'] = $Attachment_two;
            }
            if (!empty($records->form_data->revenuereceipt)) {
                $revenuereceipt = base64_encode(file_get_contents(FCPATH . $records->form_data->revenuereceipt));
                $attachment_three = array(
                    "encl" =>  $revenuereceipt,
                    "docType" => "application/pdf",
                    "enclFor" => "Land Revenue Receipt",
                    "enclType" => $records->form_data->revenuereceipt_type,
                    "id" => "65441672",
                    "doctypecode" => "7502",
                    "docRefId" => "7502",
                    "enclExtn" => "pdf"
                );
                $postdata['AttachmentThree'] = $attachment_three;
            }
            if (!empty($records->form_data->other_doc)) {
                $other_doc = base64_encode(file_get_contents(FCPATH . $records->form_data->other_doc));
                $attachment_four = array(
                    "encl" =>  $other_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Any other document",
                    "enclType" => $records->form_data->other_doc_type,
                    "id" => "65441675",
                    "doctypecode" => "7505",
                    "docRefId" => "7505",
                    "enclExtn" => "pdf"
                );
                $postdata['AttachmentFour'] = $attachment_four;
            }
            $url = $this->config->item('income_url');
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }//End of if
            curl_close($curl);
            if(isset($error_msg)) {
                return array("status"=>false, "msg"=>"Error in back-end server communication : ".$error_msg);
            } elseif ($response) {
                $response = json_decode($response);
                $refStatus = $response->ref->status??'';
                if($refStatus === "success") {
                    $sms = array(
                        "mobile" => (int)$records->form_data->mobile,
                        "applicant_name" => $records->form_data->applicant_name,
                        "service_name" => 'Income Certificate',
                        "submission_date" => date("d-m-Y h:i a"),
                        "app_ref_no" => $records->service_data->appl_ref_no
                    );
                    sms_provider("submission", $sms);
                    return array("status"=>true, "msg"=>"Data submitted successfully to the back-end server");
                } else {
                    return array("status"=>false, "msg"=>"Data submission to the back-end server is not success");
                }//End of if else
            } else {
                return array("status"=>false, "msg"=>"Response not found from the back-end server");
            }//End of if else
        } else {
            return array("status"=>false, "msg"=>"Data cannot be empty");
        }//End of if else  
    }//End of submit_to_backend()
    
}//End of Incomecertificate