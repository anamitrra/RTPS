<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Bpapi extends frontend {

    private $serviceId = "PPBP";
    private $secret_key = "s786opq56t7x"; //For UAT
    //private $secret_key = "s789wrt56t7z"; //For Prod

    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('buildingpermission/registration_model');
        $this->load->config('spconfig');  
        $this->load->helper('smsprovider');
        $this->load->helper('log');
    }//End of __construct()

    public function update_data()
    {
        log_response($this->input->post("applId"), $_POST);
        $applId =  $this->input->post("applId");
        // $wsResponse =  $this->input->post("wsResponse");
        $wsResponse =  $this->input->post("applJson");
        $hmac =  $this->input->post("hmac");

        if (!empty($wsResponse) && !empty($hmac)) {
            $decodedText = stripslashes(html_entity_decode($wsResponse));
            $hmac = hash_hmac('sha256', $decodedText, $this->secret_key); //PROD_KYE: s696onad8s8m
            if ($this->input->post("hmac") != $hmac) {
                $resPost = array(
                    'encryptKey' => $this->input->post("encryptKey"),
                    'status' => "false"
                );
            } else {
                $resObj = json_decode($decodedText);
                if (!empty($resObj)) {
                    $status = $resObj->status ?? '';
                    $remarks = $resObj->remark ?? '';
                    $drawingDoc = $resObj->drawingDoc ?? '';
                    $palnningPermitDoc = $resObj->palnningPermitDoc ?? '';
                    $buildingPermitDoc = $resObj->buildingPermitDoc ?? '';
                    $ppAmount = $resObj->ppAmount ?? '';
                    $bpAmount = $resObj->bpAmount ?? '';
                    $labourCess = $resObj->labourCess ?? '';
                    $totalAmount = $resObj->totalAmount ?? '';
                    $ulbPanchayatIdForBp = $resObj->ulbPanchayatIdForBp ?? '';
                    $developmentAuthorityIdForPP = $resObj->developmentAuthorityIdForPP ?? '';
                    $ppPaymentStatus = $resObj->ppPaymentStatus ?? '';
                    $bpPaymentStatus = $resObj->bpPaymentStatus ?? '';
                    $lcPaymentStatus = $resObj->lcPaymentStatus ?? '';
                    
                    if ($status === 'QS') {
                        if (strlen($applId) < 9) {
                            $fields = array(
                                'status' => $status,
                                'remark' => $remarks,
                                'certificate' => $certificate,
                            );
                            $json_data = json_encode($fields);
        
                            $url = $this->config->item('rtps_url');
                            $curl = curl_init($url . "callback.do");
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
                                'applId' => urlencode($applId),
                                'taskId' => urlencode($this->input->post("taskId")),
                                'wsId' => urlencode($this->input->post("wsId")),
                                'encryptKey' => urlencode($this->input->post("encryptKey")),
                                'wsResponse' => $json_data
                            )));
        
                            $response = curl_exec($curl);
                            if (curl_errno($curl)) {
                                $error_msg = curl_error($curl);
                            }
                            curl_close($curl);
                            if (isset($error_msg)) {
                                die("CURL ERROR : " . $error_msg);
                            } elseif ($response) {
                                $response_arr = json_decode($response);
                                if ($response_arr->status == 'true') {
                                    $resPost = array(
                                        'encryptKey' => $this->input->post("encryptKey"),
                                        'status' => "true",
                                    );
                                } else {
                                    $resPost = array(
                                        'encryptKey' => $this->input->post("encryptKey"),
                                        'status' => "false"
                                    );
                                }
                            } else {
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "false"
                                );
                            }
                        } else {
                            $dbrow = $this->registration_model->get_row(array('service_data.appl_id' => (int)$applId, "service_data.service_id" => $this->serviceId));
                            
                            if (!empty($dbrow)) {
                               $processing_history = $dbrow->processing_history??array();
                               
                                $processing_history[] = array(
                                    "processed_by" => "Query raised by Department",
                                    "action_taken" => "Query raised",
                                    "remarks" => (isset($remarks) ? $remarks : ""),
                                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                                );
                                $data = array(
                                    'service_data.appl_status' => $status,
                                    'processing_history' => $processing_history
                                );
                                $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);
        
                                //Sending Query SMS
                                $sms = array(
                                    "mobile" => (int)$dbrow->form_data->mobile,
                                    "applicant_name" => $dbrow->form_data->applicant_name,
                                    "service_name" => 'Appl. for Building Permission',
                                    "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
                                    "app_ref_no" => $dbrow->service_data->appl_ref_no
                                );
                                sms_provider("query", $sms);
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "true"
                                );
                            }else {
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "false"
                                );
                            }
                        }
                    } elseif ($status === 'FRS') {
                        if (strlen($applId) < 9) {
                            $fields = array(
                                'status' => $status,
                                'remark' => $remarks,
                                'ppAmount' => $ppAmount,
                                'bpAmount' => $bpAmount,
                                'ppPaymentTo' => $ppPaymentTo,
                                'bpPaymentTo' => $bpPaymentTo,
                                'ppPaymentStatus' => $ppPaymentStatus,
                                'bpPaymentStatus' => $bpPaymentStatus,
                                'totalAmount' => $totalAmount,
                            );
                            $json_data = json_encode($fields);
        
                            $url = $this->config->item('rtps_url');
                            $curl = curl_init($url . "callback.do");
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
                                'applId' => urlencode($applId),
                                'taskId' => urlencode($this->input->post("taskId")),
                                'wsId' => urlencode($this->input->post("wsId")),
                                'encryptKey' => urlencode($this->input->post("encryptKey")),
                                'wsResponse' => $json_data
                            )));
        
                            $response = curl_exec($curl);
                            if (curl_errno($curl)) {
                                $error_msg = curl_error($curl);
                            }
                            curl_close($curl);
                            if (isset($error_msg)) {
                                die("CURL ERROR : " . $error_msg);
                            } elseif ($response) {
                                $response_arr = json_decode($response);
                                if ($response_arr->status == 'true') {
                                    $resPost = array(
                                        'encryptKey' => $this->input->post("encryptKey"),
                                        'status' => "true",
                                    );
                                } else {
                                    $resPost = array(
                                        'encryptKey' => $this->input->post("encryptKey"),
                                        'status' => "false"
                                    );
                                }
                            } else {
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "false"
                                );
                            }
                        } else {
                            $dbrow = $this->registration_model->get_row(array('service_data.appl_id' => (int)$applId, "service_data.service_id" => $this->serviceId));
                            
                            if (!empty($dbrow)) {
                               $processing_history = $dbrow->processing_history??array();
                               
                                $processing_history[] = array(
                                    "processed_by" => "Payment Query made by Department",
                                    "action_taken" => "Payment Query made raised",
                                    "remarks" => (isset($remarks) ? $remarks : ""),
                                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                                );
                                $data = array(
                                    'service_data.appl_status' => $status,
                                    'form_data.frs_request' => array(
                                        'ppAmount' => $ppAmount,
                                        'bpAmount' => $bpAmount,
                                        'labourCess' => $labourCess,
                                        'ulbPanchayatIdForBp' => $ulbPanchayatIdForBp,
                                        'developmentAuthorityIdForPP' => $developmentAuthorityIdForPP,
                                        'ppPaymentStatus' => $ppPaymentStatus,
                                        'bpPaymentStatus' => $bpPaymentStatus,
                                        'lcPaymentStatus' => $lcPaymentStatus,
                                        'totalAmount' => $totalAmount,
                                    ),
                                    'processing_history' => $processing_history
                                );
                                $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);
        
                                //Sending Query SMS
                                $sms = array(
                                    "mobile" => (int)$dbrow->form_data->mobile,
                                    "applicant_name" => $dbrow->form_data->applicant_name,
                                    "service_name" => 'Appl. for Building Permission',
                                    "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
                                    "app_ref_no" => $dbrow->service_data->appl_ref_no
                                );
                                sms_provider("query", $sms);
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "true"
                                );
                            }else {
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "false"
                                );
                            }
                        }
                    } elseif ($status === 'D') {
                        if (strlen($applId) < 9) {
                            $fields = array(
                                'status' => $status,
                                'remark' => $remarks,
                                'drawingDoc' => $drawingDoc,
                                'palnningPermitDoc' => $palnningPermitDoc,
                                'buildingPermitDoc' => $buildingPermitDoc,
                            );
                            $json_data = json_encode($fields);
        
                            $url = $this->config->item('rtps_url');
                            $curl = curl_init($url . "callback.do");
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
                                'applId' => urlencode($applId),
                                'taskId' => urlencode($this->input->post("taskId")),
                                'wsId' => urlencode($this->input->post("wsId")),
                                'encryptKey' => urlencode($this->input->post("encryptKey")),
                                'wsResponse' => $json_data
                            )));
        
                            $response = curl_exec($curl);
                            if (curl_errno($curl)) {
                                $error_msg = curl_error($curl);
                            }
                            curl_close($curl);
                            if (isset($error_msg)) {
                                die("CURL ERROR : " . $error_msg);
                            } elseif ($response) {
                                $response_arr = json_decode($response);
                                if ($response_arr->status == 'true') {
                                    $resPost = array(
                                        'encryptKey' => $this->input->post("encryptKey"),
                                        'status' => "true",
                                    );
                                } else {
                                    $resPost = array(
                                        'encryptKey' => $this->input->post("encryptKey"),
                                        'status' => "false"
                                    );
                                }
                            } else {
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "false"
                                );
                            }
                        } else {
        
                            $dbrow = $this->registration_model->get_row(array('service_data.appl_id' => (int)$applId, "service_data.service_id" => $this->serviceId));
        
                            if (!empty($dbrow)) {
                                $fileNameDrawingDoc = str_replace('/', '-', $dbrow->service_data->appl_ref_no). '_drawing.pdf';
                                $dirPath = 'storage/docs' . $this->serviceId . '/';
                                if (!is_dir($dirPath)) {
                                    mkdir($dirPath, 0777, true);
                                    file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for Building Permission only</body></html>');
                                }
                                $filePathDrawingDoc = $dirPath . $fileNameDrawingDoc;
                                file_put_contents(FCPATH . $filePathDrawingDoc, base64_decode($drawingDoc));
        
                                $fileNamePalnningPermitDoc = str_replace('/', '-', $dbrow->service_data->appl_ref_no). '_planning.pdf';
                                $dirPath = 'storage/docs' . $this->serviceId . '/';
                                if (!is_dir($dirPath)) {
                                    mkdir($dirPath, 0777, true);
                                    file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for Building Permission only</body></html>');
                                }
                                $filePathPalnningPermitDoc = $dirPath . $fileNamePalnningPermitDoc;
                                file_put_contents(FCPATH . $filePathPalnningPermitDoc, base64_decode($palnningPermitDoc));
        
                                $fileNameBuildingPermitDoc = str_replace('/', '-', $dbrow->service_data->appl_ref_no). '_building.pdf';
                                $dirPath = 'storage/docs' . $this->serviceId . '/';
                                if (!is_dir($dirPath)) {
                                    mkdir($dirPath, 0777, true);
                                    file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for Building Permission only</body></html>');
                                }
                                $filePathBuildingPermitDoc = $dirPath . $fileNameBuildingPermitDoc;
                                file_put_contents(FCPATH . $filePathBuildingPermitDoc, base64_decode($buildingPermitDoc));
                                //echo '<a href="'.base_url($filePath).'" class="btn btn-success" target="_blank">View File</a>'; die;
                                //Update status and certificate
                                $processing_history = $dbrow->processing_history??array();
                                $processing_history[] = array(
                                    "processed_by" => "Application delivered by Department",
                                    "action_taken" => "Application delivered",
                                    "remarks" => (isset($remarks) ? $remarks : ""),
                                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                                );
                                $data = array(
                                    'service_data.appl_status' => $status,
                                    'form_data.certificates' => array(
                                        'drawingDoc' => $filePathDrawingDoc,
                                        'palnningPermitDoc' => $filePathPalnningPermitDoc,
                                        'buildingPermitDoc' => $filePathBuildingPermitDoc,
                                    ),
                                    'processing_history' => $processing_history
                                );
                                $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);
        
                                //Sending delivered SMS
                                $sms = array(
                                    "mobile" => (int)$dbrow->form_data->mobile,
                                    "applicant_name" => $dbrow->form_data->applicant_name,
                                    "service_name" => 'Appl. for Building Permission',
                                    "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
                                    "app_ref_no" => $dbrow->service_data->appl_ref_no,
                                );
                                sms_provider("delivery", $sms);
        
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "true"
                                );
                            } else {
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "false"
                                );
                            }
                        }
                    } elseif ($status === 'F') {
                        if (strlen($applId) < 9) {
                            $fields = array(
                                'status' => $status,
                                'remark' => $remarks,
                                'certificate' => $certificate,
                            );
                            $json_data = json_encode($fields);
        
                            $url = $this->config->item('rtps_url');
                            $curl = curl_init($url . "callback.do");
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
                                'applId' => urlencode($applId),
                                'taskId' => urlencode($this->input->post("taskId")),
                                'wsId' => urlencode($this->input->post("wsId")),
                                'encryptKey' => urlencode($this->input->post("encryptKey")),
                                'wsResponse' => $json_data
                            )));
        
                            $response = curl_exec($curl);
                            // print($response);
                            // die;
                            if (curl_errno($curl)) {
                                $error_msg = curl_error($curl);
                            }
                            curl_close($curl);
                            if (isset($error_msg)) {
                                die("CURL ERROR : " . $error_msg);
                            } elseif ($response) {
                                $response_arr = json_decode($response);
                                if ($response_arr->status == 'true') {
                                    $resPost = array(
                                        'encryptKey' => $this->input->post("encryptKey"),
                                        'status' => "true",
                                    );
                                } else {
                                    $resPost = array(
                                        'encryptKey' => $this->input->post("encryptKey"),
                                        'status' => "false"
                                    );
                                }
                            } else {
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "false"
                                );
                            }
                        } else {
                            $dbrow = $this->registration_model->get_row(array('service_data.appl_id' => (int)$applId, "service_data.service_id" => $this->serviceId));
        
                            if (!empty($dbrow)) {
        
                                if (($dbrow->service_data->appl_status == "D") || ($dbrow->service_data->appl_status == "R")) {
                                    $resPost = array(
                                        'encryptKey' => $this->input->post("encryptKey"),
                                        'status' => "true"
                                    );
            
                                    $json_obj = json_encode($resPost);
                                    return $this->output
                                        ->set_content_type('application/json')
                                        ->set_status_header(200)
                                        ->set_output($json_obj);
                                }
        
                                $processing_history = $dbrow->processing_history??array();
                                $processing_history[] = array(
                                    "processed_by" => "Forwarded",
                                    "action_taken" => "Forwarded",
                                    "remarks" => (isset($remarks) ? $remarks : ""),
                                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                                );
                                $data = array(
                                    'service_data.appl_status' => $status,
                                    'processing_history' => $processing_history
                                );
        
                                $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);
        
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "true"
                                );
                            } else {
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "false"
                                );
                            }
                        }
                    } elseif ($status === 'R') {
                        if (strlen($applId) < 9) {
                            $fields = array(
                                'status' => $status,
                                'remark' => $remarks,
                                'certificate' => $certificate,
                            );
                            $json_data = json_encode($fields);
        
                            $url = $this->config->item('rtps_url');
                            $curl = curl_init($url . "callback.do");
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
                                'applId' => urlencode($applId),
                                'taskId' => urlencode($this->input->post("taskId")),
                                'wsId' => urlencode($this->input->post("wsId")),
                                'encryptKey' => urlencode($this->input->post("encryptKey")),
                                'wsResponse' => $json_data
                            )));
        
                            $response = curl_exec($curl);
                            if (curl_errno($curl)) {
                                $error_msg = curl_error($curl);
                            }
                            curl_close($curl);
                            if (isset($error_msg)) {
                                die("CURL ERROR : " . $error_msg);
                            } elseif ($response) {
                                $response_arr = json_decode($response);
                                if ($response_arr->status == 'true') {
                                    $resPost = array(
                                        'encryptKey' => $this->input->post("encryptKey"),
                                        'status' => "true",
                                    );
                                } else {
                                    $resPost = array(
                                        'encryptKey' => $this->input->post("encryptKey"),
                                        'status' => "false"
                                    );
                                }
                            } else {
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "false"
                                );
                            }
                        } else {
                            $dbrow = $this->registration_model->get_row(array('service_data.appl_id' => (int)$applId, "service_data.service_id" => $this->serviceId));
        
                            if (!empty($dbrow)) {
                                $processing_history = $dbrow->processing_history??array();
                                $processing_history[] = array(
                                    "processed_by" => "Rejected",
                                    "action_taken" => "Rejected",
                                    "remarks" => (isset($remarks) ? $remarks : ""),
                                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                                );
                                $data = array(
                                    'service_data.appl_status' => $status,
                                    'processing_history' => $processing_history
                                );
                                $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);
        
                                //Sending Query SMS
                                $sms = array(
                                    "mobile" => (int)$dbrow->form_data->mobile,
                                    "applicant_name" => $dbrow->form_data->applicant_name,
                                    "service_name" => 'Appl. for Building Permission',
                                    "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
                                    "app_ref_no" => $dbrow->service_data->appl_ref_no,
                                    "submission_office" => "the department."
                                );
                                sms_provider("rejection", $sms);
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "true"
                                );
                            }else {
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "false"
                                );
                            }
                        }
                    } else {
                        $resPost = array(
                            'encryptKey' => $this->input->post("encryptKey"),
                            'status' => "false"
                        );
                    } //End of if else
                } else {
                    $resPost = array(
                        'encryptKey' => $this->input->post("encryptKey"),
                        'status' => "false"
                    );
                } 
            }
        } else {
            $resPost = array(
                'encryptKey' => $this->input->post("encryptKey"),
                'status' => "false"
            );
        }

        $json_obj = json_encode($resPost);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($json_obj);
    } //End of update_data()

    public function push_data(){

        $json = file_get_contents('php://input');
        if (!empty($json)) {
            $data = json_decode($json);

            $service_data1 = array("department_id", "department_name", "service_id", "service_name", "appl_id", "appl_ref_no", "submission_mode", "applied_by", "submission_location", "submission_date", "service_timeline", "appl_status", "district");
            $service_data = [];
            foreach($data->service_data as $key => $value){
                if (in_array($key, $service_data1) != true) {
                    $resPost = array(
                        'msg' => $key." isn't match with data set",
                        'status' => "false"
                    );
            
                    $json_obj = json_encode($resPost);
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output($json_obj);
                }else {
                    $service_data[$key] = $value;
                }
            }

            $form_data1 = array("application_type", "case_type", "ertp", "any_old_permission", "technical_person_name", "old_permission_no", "empanelled_reg_tech_person", "house_no_landmak", "mouza", "name_of_road", "panchayat", "revenue_village", "dag_no", "zone", "new_dag_no", "ward_no", "patta_no", "site_pin_code", "new_patta_no", "applicant_name", "applicant_gender", "father_name", "mother_name", "spouse_name", "permanent_address", "pin_code", "mobile", "monthly_income", "pan_no", "email", "owner_details", "user_id", "user_type", "service_type", "created_at", "frs_request", "query_application_charge", "query_payment_status", "technical_person_document", "technical_person_document_type", "old_permission_copy_type", "old_permission_copy", "old_drawing_type", "old_drawing", "drawing_type", "drawing", "trace_map_type", "trace_map", "key_plan_type", "key_plan", "site_plan_type", "site_plan", "building_plan_type", "building_plan", "certificate_of_supervision_type", "certificate_of_supervision", "area_statement_type", "area_statement", "amended_byelaws_type", "amended_byelaws", "form_no_six_type", "form_no_six", "indemnity_bond_type", "indemnity_bond", "undertaking_signed_type", "undertaking_signed", "party_applicant_form_type", "party_applicant_form", "date_property_tax_type", "date_property_tax", "service_plan_type", "service_plan", "parking_plan_type", "parking_plan", "ownership_document_of_land_type", "ownership_document_of_land", "any_other_document_type", "any_other_document", "construction_estimate_type", "construction_estimate", "certificates");
            $form_data = [];
            foreach($data->form_data as $key => $value){
                if (in_array($key, $form_data1) != true) {
                    $resPost = array(
                        'msg' => $key." isn't match with data set",
                        'status' => "false"
                    );
            
                    $json_obj = json_encode($resPost);
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output($json_obj);
                }else{

                    if (("technical_person_document" == $key) || ("old_permission_copy" == $key) || ("old_drawing" == $key) || ("drawing" == $key) || ("trace_map" == $key) || ("key_plan" == $key) || ("site_plan" == $key) || ("building_plan" == $key) || ("certificate_of_supervision" == $key) || ("area_statement" == $key) || ("amended_byelaws" == $key) || ("form_no_six" == $key) || ("indemnity_bond" == $key) || ("undertaking_signed" == $key) || ("party_applicant_form" == $key) || ("date_property_tax" == $key) || ("service_plan" == $key) || ("parking_plan" == $key) || ("ownership_document_of_land" == $key) || ("any_other_document" == $key) || ("construction_estimate" == $key)) {
                        $bin = base64_decode($value, true);
                        if (strpos($bin, '%PDF') !== 0) {
                            $resPost = array(
                                'msg' => "Technical Person Qualification Document, Missing the PDF file signature",
                                'status' => "false"
                            );
                    
                            $json_obj = json_encode($resPost);
                            return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output($json_obj);
                        }
                        $folder_path = 'storage/docs/'. date("Y") . '/' . date("m") . '/' . date("d") . '/';
                        $pathname = FCPATH . $folder_path;
                        if (!is_dir($pathname)) {
                            mkdir($pathname, 0777, true);
                        }
                        $time = md5(uniqid());
                        file_put_contents($pathname.$time.'.pdf', $bin);
                        $form_data[$key] = $folder_path.$time.'.pdf';
                    } elseif ($key == "certificates"){
                        // pre($value->drawingDoc."\n".$value->palnningPermitDoc);
                        if (isset($value->drawingDoc) && (!empty($value->drawingDoc))) {
                             $fileNameDrawingDoc = str_replace('/', '-', $data->service_data->appl_ref_no). '_drawing.pdf';
                            $dirPath = 'storage/docsPPBP/';
                            if (!is_dir($dirPath)) {
                                mkdir($dirPath, 0777, true);
                            }
                            $filePathDrawingDoc = $dirPath . $fileNameDrawingDoc;
                            file_put_contents(FCPATH . $filePathDrawingDoc, base64_decode($value->drawingDoc));
                            $form_data[$key]['drawingDoc'] = $filePathDrawingDoc;
                        } 

                        if (isset($value->palnningPermitDoc) && (!empty($value->palnningPermitDoc))) {
                            $fileNamePalnningPermitDoc = str_replace('/', '-', $data->service_data->appl_ref_no). '_planning.pdf';
                            $dirPath = 'storage/docsPPBP/';
                            if (!is_dir($dirPath)) {
                                mkdir($dirPath, 0777, true);
                            }
                            $filePathPalnningPermitDoc = $dirPath . $fileNamePalnningPermitDoc;
                            file_put_contents(FCPATH . $filePathPalnningPermitDoc, base64_decode($value->palnningPermitDoc));
                            $form_data[$key]['palnningPermitDoc'] = $filePathPalnningPermitDoc;
                        } 

                        if (isset($value->palnningPermitDoc) && (!empty($value->buildingPermitDoc))) {
                             $fileNameBuildingPermitDoc = str_replace('/', '-', $data->service_data->appl_ref_no). '_building.pdf';
                             $dirPath = 'storage/docsPPBP/';
                             if (!is_dir($dirPath)) {
                                 mkdir($dirPath, 0777, true);
                             }
                             $filePathBuildingPermitDoc = $dirPath . $fileNameBuildingPermitDoc;
                             file_put_contents(FCPATH . $filePathBuildingPermitDoc, base64_decode($value->buildingPermitDoc));
                             $form_data[$key]['buildingPermitDoc'] = $filePathBuildingPermitDoc;
                        }
                    }else {
                        $form_data[$key] = $value;
                    }
                }
            }

            $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data
            ];

            $insert = $this->registration_model->insert($inputs);
            if ($insert) {
                $objectId = $insert['_id']->{'$id'};
                $resPost = array(
                    'msg' => $objectId." Your application has been successfully submitted'",
                    'status' => "true"
                );
        
                $json_obj = json_encode($resPost);
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output($json_obj);
            } else {
                $resPost = array(
                    'msg' => " Unable to submit data!!! Please try again'",
                    'status' => "false"
                );
        
                $json_obj = json_encode($resPost);
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output($json_obj);
            } //End of if else
        }

        $resPost = array(
            'msg' => "Data is empty",
            'status' => "false"
        );

        $json_obj = json_encode($resPost);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($json_obj);
    }
}//End of Necapi