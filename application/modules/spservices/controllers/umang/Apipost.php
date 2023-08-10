<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Apipost extends frontend {
    
    private $serverToken = "080042cad6356ad5dc0a720c18b53b8e53d4c274";

    public function __construct() {
        parent::__construct();
        header('Content-Type: application/json; charset=utf8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition,Content-Description');

        $this->load->model('marriageregistration/states_model');
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
                "benfTypeCd" => $json_obj->benfTypeCd??'',
                "benfCategoryCd" => $json_obj->benfCategoryCd??'',
                "gender" => $json_obj->gender??'',
                "orgCd" => $json_obj->orgCd??'',
                "dob" => $json_obj->dob??'',
                "mobNo1" => $json_obj->mobNo1??'',
                "appPostAddr" => $json_obj->appPostAddr??'',
                "appTalukBlock" => $json_obj->appTalukBlock??'',
                "appDistCd" => $json_obj->appDistCd??'',
                "appPin" => $json_obj->appPin??'',
                "eMail" => $json_obj->eMail??'',
                "eduId" => $json_obj->eduId??'',
                "unitLoc" => $json_obj->unitLoc??'',
                "unitPostAddr" => $json_obj->unitPostAddr??'',
                "unitTalukBlock" => $json_obj->unitTalukBlock??'',
                "unitDistCd" => $json_obj->unitDistCd??'',
                "unitPin" => $json_obj->unitPin??'',
                "machineryCost" => $json_obj->machineryCost??'',
                "workingCapital" => $json_obj->workingCapital??'',
                "empEnv" => $json_obj->empEnv??'',
                "bankPostAddr" => $json_obj->bankPostAddr??'',
                "prodDesc" => $json_obj->prodDesc??'',
                "ifscCode" => $json_obj->ifscCode??'',
                "ifscCode2" => $json_obj->ifscCode2??'',
                "timeStamp" => $json_obj->timeStamp??'',
                "actId" => $json_obj->actId??'',
                "indType" => $json_obj->indType??'',
                "activityCd" => $json_obj->activityCd??'',
                "bankName" => $json_obj->bankName??'',
                "branchName" => $json_obj->branchName??'',
                "bankDistrict" => $json_obj->bankDistrict??'',
                "aadharNo" => $json_obj->aadharNo??'',
                "mobNo2" => $json_obj->mobNo2??'',
                "edpInstAddr" => $json_obj->edpInstAddr??'',
                "edpYn" => $json_obj->edpYn??'',
                "panNo" => $json_obj->panNo??'',
                "benfSpecatCd" => $json_obj->benfSpecatCd??''
            );
            
            //Set validation rules
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules("benfTypeCd", "Beneficiary type", "required");
            $this->form_validation->set_rules("benfCategoryCd", "Beneficiary category", "required");
            $this->form_validation->set_rules("gender", "Gender", "required");
            $this->form_validation->set_rules("orgCd", "Ogranization", "required");
            $this->form_validation->set_rules("dob", "DOB", "required");
            $this->form_validation->set_rules("mobNo1", "Mobile no. 1", "required|exact_length[10]|numeric");
            $this->form_validation->set_rules("appPostAddr", "Postal address", "required");
            $this->form_validation->set_rules("appTalukBlock", "Taluka", "required");
            $this->form_validation->set_rules("appDistCd", "App Dist", "required");
            $this->form_validation->set_rules("appPin", "App PIN", "required");
            $this->form_validation->set_rules("eMail", "E-mail", "required|valid_email|max_length[100]");
            $this->form_validation->set_rules("eduId", "Education", "required");
            $this->form_validation->set_rules("unitLoc", "unit LOC", "required");
            $this->form_validation->set_rules("unitPostAddr", "Unit postal address", "required");
            $this->form_validation->set_rules("unitTalukBlock", "Unit taluk block", "required");
            $this->form_validation->set_rules("unitDistCd", "Unit district", "required");
            $this->form_validation->set_rules("unitPin", "Unit PIN", "required");
            $this->form_validation->set_rules("machineryCost", "Machinery cost", "required");
            $this->form_validation->set_rules("workingCapital", "Working capital", "required");
            $this->form_validation->set_rules("empEnv", "Employee Environment", "required");
            $this->form_validation->set_rules("bankPostAddr", "Bank postal address", "required");
            $this->form_validation->set_rules("prodDesc", "Product description", "required");
            $this->form_validation->set_rules("ifscCode", "IFSC", "required");
            $this->form_validation->set_rules("ifscCode2", "IFSC 2", "required");
            $this->form_validation->set_rules("timeStamp", "Time", "required");
            $this->form_validation->set_rules("actId", "Act", "required");
            $this->form_validation->set_rules("indType", "Ind type", "required");
            $this->form_validation->set_rules("activityCd", "Activity", "required");
            $this->form_validation->set_rules("bankName", "Bank name", "required");
            $this->form_validation->set_rules("branchName", "Branch name", "required");
            $this->form_validation->set_rules("bankDistrict", "Bank district", "required");
            $this->form_validation->set_rules("aadharNo", "Aadhaar no.", "required");
            $this->form_validation->set_rules("mobNo2", "Mobile no. 2", "required|exact_length[10]|numeric");
            $this->form_validation->set_rules("edpInstAddr", "edpInstAddr", "required");
            $this->form_validation->set_rules("edpYn", "edpYn", "required");
            $this->form_validation->set_rules("benfSpecatCd", "benfSpecatCd", "required");
            $this->form_validation->set_rules("panNo", "PAN", "alpha_numeric|exact_length[10]");
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
                pre($data);
                //$insert = $this->umang_model->insert($data);
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
            }//End of if else
        }//End of if else 
        
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($resArr));
    }//End of post_data()
    
    public function getstates() {
        $authorizationToken = '43b-b6daab40b04e';
        $statesArr = array();
        if($this->getAuthorizationHeader() !== $authorizationToken) {
            $resArr = array(
                'rs' => "F", //Request Status
                'rc' => 401, //Response Code
                'rd' => "Authorization key does not matched", //Response Description 
                'pd' => $statesArr                               
            );
        } else {
            $json = file_get_contents('php://input');
            $json_arr = json_decode($json, true);
            $this->form_validation->set_data($json_arr);
            $this->form_validation->set_rules("lac", "LAC", "required");
            if ($this->form_validation->run() == FALSE) {
                $resArr = array(
                        'rs' => "F",
                        'rc' => 400,
                        'rd' => 'Validation errors : '.validation_errors(),
                        'pd' => $statesArr                                
                    );
            } else {
               $stateRows = $this->states_model->get_distinct_results();            
                if ($stateRows) {
                    foreach($stateRows as $rows) {
                        $stateRow = array(
                            "stateCd" => $rows->slc,
                            "stateName" => $rows->state_name_english
                        );
                        $statesArr[] = $stateRow;
                    }//End of foreach() 
                    $resArr = array(
                        'rs' => "S",
                        'rc' => "PM0000",
                        'rd' => "Success",
                        'pd' => $statesArr                               
                    );
                } else {
                    $resArr = array(
                        'rs' => "F",
                        'rc' => 400,
                        'rd' => "No records found",
                        'pd' => $statesArr                               
                    );
                }//End of if else 
            }//End of if else
        }//End of if else 
        
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($resArr));
    }//End of post_data()

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

}//End of Apipost