<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Globalapi extends frontend {
    private $serverToken = "080042cad6356ad5dc0a720c18b53b8e53d4c274";
    private $gadApi = 'https://rtps.assam.gov.in/apis/gad_apis/';

    public function __construct() {
        parent::__construct();
        header('Content-Type: application/json; charset=utf8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition,Content-Description');

        $this->load->model('income/income_model');
    }//End of __construct()
    
    public function get_sub_divisions() {
        $bearerToken = $this->getBearerToken();
        if($this->serverToken !== $bearerToken) {
            $resArr = array(
                'rs' => "F", //Request Status
                'rc' => 401, //Response Code
                'rd' => "Authorization key does not matched", //Response Description 
                'pd' => array(
                    'success' => false,
                    'message' => 'Failed to fetched data',
                    'records' => array()
                )                                
            );
        } else {
            $json = file_get_contents('php://input');
            $json_obj = json_decode($json);
            $data = array(
                "district_name" => $json_obj->district_name??''          
            );
            
            //Set validation rules
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules("district_name", "District name", "required");
                        
            if ($this->form_validation->run() == FALSE) {
                $resArr = array(
                    'rs' => "F",
                    'rc' => 400,
                    'rd' => "Failure",
                    'pd' => array(
                        'success' => false,
                        'message' => 'Validation errors : '.validation_errors(),
                        'records' => array()
                    )                                
                );
            } else {
                $json_obj = json_encode(array("district_name"=>$data['district_name']));
                $getUrl = $this->gadApi . "sub_division_list.php?jsonbody=".$json_obj; //die($getUrl);
                $curl = curl_init($getUrl);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }//End of if
                curl_close($curl); //pre($json_obj);        
                if(isset($error_msg)) {
                    $resArr = array(
                        'rs' => "F",
                        'rc' => 400,
                        'rd' => "Failure",
                        'pd' => array(
                            'success' => false,
                            'message' => 'Something went wrong : '.$error_msg,
                            'records' => array()
                        )                                
                    );
                } elseif ($response) { //pre($response);
                    $response = json_decode($response);
                    if(isset($response->records) && count($response->records)) {
                        $resArr = array(
                            'rs' => "S",
                            'rc' => "PM0000",
                            'rd' => "Success",
                            'pd' => array(
                                'success' => true,
                                'message' => 'Successfully fetched records',
                                'records' => $response->records??array()
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
                                'records' => array()
                            )                              
                        );
                    }//End of if else
                }//End of if else                
            }//End of if else
        }//End of if else 
        
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($resArr));
    }//End of get_sub_divisions()
    
    public function get_revenue_circles() {
        $bearerToken = $this->getBearerToken();
        if($this->serverToken !== $bearerToken) {
            $resArr = array(
                'rs' => "F", //Request Status
                'rc' => 401, //Response Code
                'rd' => "Authorization key does not matched", //Response Description 
                'pd' => array(
                    'success' => false,
                    'message' => 'Failed to fetched data',
                    'records' => array()
                )                                
            );
        } else {
            $json = file_get_contents('php://input');
            $json_obj = json_decode($json);
            $data = array(
                "subdiv_name" => $json_obj->subdiv_name??''          
            );
            
            //Set validation rules
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules("subdiv_name", "Sub-division name", "required");
                        
            if ($this->form_validation->run() == FALSE) {
                $resArr = array(
                    'rs' => "F",
                    'rc' => 400,
                    'rd' => "Failure",
                    'pd' => array(
                        'success' => false,
                        'message' => 'Validation errors : '.validation_errors(),
                        'records' => array()
                    )                                
                );
            } else {
                $json_obj = json_encode(array("subdiv_name"=>$data['subdiv_name']));
                $getUrl = $this->gadApi . "revenue_circle_list.php?jsonbody=".$json_obj; //die($getUrl);
                $curl = curl_init($getUrl);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }//End of if
                curl_close($curl); //pre($json_obj);        
                if(isset($error_msg)) {
                    $resArr = array(
                        'rs' => "F",
                        'rc' => 400,
                        'rd' => "Failure",
                        'pd' => array(
                            'success' => false,
                            'message' => 'Something went wrong : '.$error_msg,
                            'records' => array()
                        )                                
                    );
                } elseif ($response) { //pre($response);
                    $response = json_decode($response);
                    if(isset($response->records) && count($response->records)) {
                        $resArr = array(
                            'rs' => "S",
                            'rc' => "PM0000",
                            'rd' => "Success",
                            'pd' => array(
                                'success' => true,
                                'message' => 'Successfully fetched records',
                                'records' => $response->records??array()
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
                                'records' => array()
                            )                              
                        );
                    }//End of if else
                }//End of if else                
            }//End of if else
        }//End of if else 
        
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($resArr));
    }//End of get_revenue_circles()
    
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
    
}//End of Globalapi