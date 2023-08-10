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
        // $wsResponse =  urldecode($this->input->post("applJson"));
        $wsResponse =  $this->input->post("applJson");
        $hmac =  $this->input->post("hmac");

        if (!empty($wsResponse) && !empty($hmac)) {
            // $decodedText = stripslashes(html_entity_decode($wsResponse));
            $decodedText = $wsResponse;
            $hmac = hash_hmac('sha256', $decodedText, $this->secret_key); //PROD_KYE: s696onad8s8m
            $decodedText = base64_decode($decodedText);
            
            if ($this->input->post("hmac") != $hmac) {
                $resPost = array(
                    'encryptKey' => $this->input->post("encryptKey"),
                    'status' => "false"
                );
            } else {
                $resObj = json_decode($decodedText);
                // pre($resObj);
                if (!empty($resObj)) {
                    $status = $resObj->status ?? '';
                    // $certificate = $resObj->certificate ?? '';
                    $remarks = $resObj->remarks ?? '';
                    $drawingDoc = $resObj->drawingDoc ?? '';
                    $palnningPermitDoc = $resObj->palnningPermitDoc ?? '';
                    $buildingPermitDoc = $resObj->buildingPermitDoc ?? '';
                    $ppAmount = $resObj->ppAmount ?? '';
                    $bpAmount = $resObj->bpAmount ?? '';
                    $labourCess = $resObj->labourCess ?? '';
                    $totalAmount = $resObj->totalAmount ?? '';
                    $ulbPanchayatIdForBp = $resObj->ulbPanchayatIdForBp ?? '';
                    $developmentAuthorityIdForPP = $resObj->developmentAuthorityIdForPP ?? '';
                    $ppPaymentType = $resObj->ppPaymentType ?? '';
                    $bpPaymentType = $resObj->bpPaymentType ?? '';
                    $ppPaymentStatus = $resObj->ppPaymentStatus ?? '';
                    $bpPaymentStatus = $resObj->bpPaymentStatus ?? '';
                    $lcPaymentStatus = $resObj->lcPaymentStatus ?? '';
                    
                    if ($status === 'QS') {
                        if (strlen($applId) < 9) {
                            $fields = array(
                                'status' => $status,
                                'remarks' => $remarks
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
                                'remarks' => $remarks,
                                'ppAmount' => $ppAmount,
                                'bpAmount' => $bpAmount,
                                'ppPaymentTo' => "GMDA",
                                'bpPaymentTo' => "GMC",
                                'ppPaymentStatus' => $ppPaymentStatus,
                                'bpPaymentStatus' => $bpPaymentStatus,
                                'totalAmount' => ($totalAmount - $labourCess),
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

                                if ($dbrow->service_data->appl_status == 'FRS') {
                                    $resPost = array(
                                        'encryptKey' => $this->input->post("encryptKey"),
                                        'status' => "true"
                                    );
                                }

                               $processing_history = $dbrow->processing_history??array();
                               
                                $processing_history[] = array(
                                    "processed_by" => "Payment Query made by Department",
                                    "action_taken" => "Payment Query made raised",
                                    "remarks" => (isset($remarks) ? $remarks : ""),
                                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                                );

                                if($ppPaymentStatus == "UNPAID")
                                    $ppPaymentTypeStatus = false; //true for Paid, false for Un-Paid
                                else
                                    $ppPaymentTypeStatus = true; 

                                if($bpPaymentStatus == "UNPAID")
                                    $bpPaymentTypeStatus = false; //true for Paid, false for Un-Paid
                                else{
                                    $bpPaymentTypeStatus = true;
                                    $bpAmount = 0;
                                }

                                $data = array(
                                    'service_data.appl_status' => $status,
                                    'form_data.frs_request' => array(
                                        'ppAmount' => $ppAmount,
                                        'bpAmount' => $bpAmount,
                                        'labourCess' => $labourCess,
                                        'ulbPanchayatIdForBp' => $ulbPanchayatIdForBp,
                                        'developmentAuthorityIdForPP' => $developmentAuthorityIdForPP,
                                        'ppPaymentType' => $ppPaymentType,
                                        'bpPaymentType' => $bpPaymentType,
                                        'ppPaymentTypeStatus' => $ppPaymentTypeStatus,
                                        'bpPaymentTypeStatus' => $bpPaymentTypeStatus,
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
                                'remarks' => $remarks,
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
                                'remarks' => $remarks
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
                                'remarks' => $remarks
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

                                //Make Primary enable for re-apply when secondary application has rejected
                                if(isset($dbrow->form_data->old_permit_appl_ref_no)){
                                    if(!empty($dbrow->form_data->old_permit_appl_ref_no)){
                                        $filter = array(
                                            "service_data.appl_ref_no" => $dbrow->form_data->old_permit_appl_ref_no,
                                        );
                                        $data_to_update=array(
                                            'form_data.app_record_type'=>""
                                        );
                                        $this->registration_model->update_where($filter, $data_to_update);
                                    }
                                }
        
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

    public function download_app_json(){
        $appl_ref_no = $this->input->get('appl_ref_no');
        if(!empty($appl_ref_no)){

            $filter = array(
                "service_data.appl_ref_no" => $appl_ref_no
            );
            $dbRow = $this->registration_model->get_row($filter);

            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }

            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")){
                //procesing data
                $processing_history = $dbRow->processing_history??array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
                
                $owners = array();
                if(count($dbRow->form_data->owner_details)) {
                    foreach($dbRow->form_data->owner_details as $key => $owner_detail) {
    
                        $owner_detail = array(
                            "ownerName" => $owner_detail->owner_name,
                            "gender" => $owner_detail->owner_gender,
                        );
                        $owners[] = $owner_detail;
                    }//End of foreach()        
                }//End of if

                $recordType = "";
                if((isset($dbRow->form_data->old_permit_appl_ref_no)) && (!empty($dbRow->form_data->old_permit_appl_ref_no))){
                    $recordType = "REAPPLY";
                } else {
                    $recordType = "FRESH";
                }

                $postdata = array(
                    "applicationRefNo" => $dbRow->service_data->appl_ref_no,
                    "rtpId" => $dbRow->form_data->empanelled_reg_tech_person,
                    "applicationTypeId" => $dbRow->form_data->application_type,
                    "caseTypeId" => $dbRow->form_data->case_type,
                    "hasAssistantArchitect" => $dbRow->form_data->ertp,
                    "architectOnRecord" => $dbRow->form_data->technical_person_name,
                    "hasOldPermission" => $dbRow->form_data->any_old_permission,
                    "oldPermissionNo" => $dbRow->form_data->old_permission_no,
                    "villageId" => $dbRow->form_data->revenue_village,
                    "mouzaId" => $dbRow->form_data->mouza,
                    "incomeId" => $dbRow->form_data->monthly_income,
                    "fatherName" => $dbRow->form_data->father_name,
                    "motherName" => $dbRow->form_data->mother_name,
                    "spouseName" => $dbRow->form_data->spouse_name,
                    "panNo" => $dbRow->form_data->pan_no,
                    "owners" => $owners,
                    "wardId" => $dbRow->form_data->ward_no,
                    "houseNo" => $dbRow->form_data->house_no,
                    "roadName" => $dbRow->form_data->name_of_road,
                    "dagNo" => $dbRow->form_data->old_dag_no,
                    "newDagNo" => $dbRow->form_data->new_dag_no,
                    "sitePinCode" => $dbRow->form_data->site_pin_code,
                    "pattaNo" => $dbRow->form_data->old_patta_no,
                    "newPattaNo" => $dbRow->form_data->new_patta_no,
                    "applicantName" => $dbRow->form_data->applicant_name,
                    "mobileNo" => $dbRow->form_data->mobile,
                    "permanentAddress" => $dbRow->form_data->permanent_address,
                    "email" => $dbRow->form_data->email,
                    "pinCode" => $dbRow->form_data->pin_code,
                    "districtId" => $dbRow->form_data->district,
                    "developmentAuthorityId" => $dbRow->form_data->mst_pln_dev_auth,
                    "ulbPanchayatId" => $dbRow->form_data->panchayat_ulb,

                    "recordType" => $recordType,
                    
                    'spId' => array('applId' => $dbRow->service_data->appl_id)
                );

                if((isset($dbRow->form_data->old_permit_appl_ref_no)) && (!empty($dbRow->form_data->old_permit_appl_ref_no))){
                    $postdata['oldApplicationRefNo'] = $dbRow->form_data->old_permit_appl_ref_no;
                }
    
                if(!empty($dbRow->form_data->technical_person_document)){
                    $technical_person_document = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->technical_person_document));

                    $postdata['architectQualificationDoc'] = $technical_person_document;
                }

                if(!empty($dbRow->form_data->old_permission_copy)){
                    $old_permission_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->old_permission_copy));

                    $postdata['oldPermissionDoc'] = $old_permission_copy;
                }

                if(!empty($dbRow->form_data->old_drawing)){
                    $old_drawing = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->old_drawing));

                    $postdata['oldDrawingDoc'] = $old_drawing;
                }

                if(!empty($dbRow->form_data->drawing)){
                    $drawing = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->drawing));

                    $attachment_one = array(
                        "encl" =>  $drawing,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->drawing_type,
                        "enclType" => $dbRow->form_data->drawing_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentOne'] = $attachment_one;
                }

                if(!empty($dbRow->form_data->trace_map)){
                    $trace_map = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->trace_map));

                    $attachment_two = array(
                        "encl" =>  $trace_map,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->trace_map_type,
                        "enclType" => $dbRow->form_data->trace_map_type,
                        "id" => "93964",
                        "doctypecode" => "8257",
                        "docRefId" => "8258",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentTwo'] = $attachment_two;
                }

                if(!empty($dbRow->form_data->key_plan)){
                    $key_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->key_plan));

                    $attachment_three = array(
                        "encl" =>  $key_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->key_plan_type,
                        "enclType" => $dbRow->form_data->key_plan_type,
                        "id" => "93965",
                        "doctypecode" => "8259",
                        "docRefId" => "8260",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentThree'] = $attachment_three;
                }

                if(!empty($dbRow->form_data->site_plan)){
                    $site_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->site_plan));

                    $attachment_four = array(
                        "encl" =>  $site_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->site_plan_type,
                        "enclType" => $dbRow->form_data->site_plan_type,
                        "id" => "93966",
                        "doctypecode" => "8290",
                        "docRefId" => "8261",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentFour'] = $attachment_four;
                }

                if(!empty($dbRow->form_data->building_plan)){
                    $building_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->building_plan));

                    $attachment_five = array(
                        "encl" =>  $building_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->building_plan_type,
                        "enclType" => $dbRow->form_data->building_plan_type,
                        "id" => "93966",
                        "doctypecode" => "8290",
                        "docRefId" => "8261",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentFive'] = $attachment_five;
                }

                if(!empty($dbRow->form_data->certificate_of_supervision)){
                    $certificate_of_supervision = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->certificate_of_supervision));

                    $attachment_six = array(
                        "encl" =>  $certificate_of_supervision,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->certificate_of_supervision_type,
                        "enclType" => $dbRow->form_data->certificate_of_supervision_type,
                        "id" => "93968",
                        "doctypecode" => "8265",
                        "docRefId" => "8292",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentSix'] = $attachment_six;
                }

                if(!empty($dbRow->form_data->area_statement)){
                    $area_statement = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->area_statement));

                    $attachment_seven = array(
                        "encl" =>  $area_statement,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->area_statement_type,
                        "enclType" => $dbRow->form_data->area_statement_type,
                        "id" => "93969",
                        "doctypecode" => "8255",
                        "docRefId" => "8256",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentSeven'] = $attachment_seven;
                }

                if(!empty($dbRow->form_data->amended_byelaws)){
                    $amended_byelaws = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->amended_byelaws));

                    $attachment_eight = array(
                        "encl" =>  $amended_byelaws,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->amended_byelaws_type,
                        "enclType" => $dbRow->form_data->amended_byelaws_type,
                        "id" => "93969",
                        "doctypecode" => "8255",
                        "docRefId" => "8256",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentEight'] = $attachment_eight;
                }

                if(!empty($dbRow->form_data->form_no_six)){
                    $form_no_six = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->form_no_six));

                    $attachment_nine = array(
                        "encl" =>  $form_no_six,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->form_no_six_type,
                        "enclType" => $dbRow->form_data->form_no_six_type,
                        "id" => "93971",
                        "doctypecode" => "8268",
                        "docRefId" => "8269",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentNine'] = $attachment_nine;
                }

                if(!empty($dbRow->form_data->indemnity_bond)){
                    $indemnity_bond = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->indemnity_bond));

                    $attachment_nine = array(
                        "encl" =>  $indemnity_bond,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->indemnity_bond_type,
                        "enclType" => $dbRow->form_data->indemnity_bond_type,
                        "id" => "93972",
                        "doctypecode" => "8270",
                        "docRefId" => "8271",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentTen'] = $attachment_nine;
                }

                if(!empty($dbRow->form_data->undertaking_signed)){
                    $undertaking_signed = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->undertaking_signed));

                    $attachment_eleven = array(
                        "encl" =>  $undertaking_signed,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->undertaking_signed_type,
                        "enclType" => $dbRow->form_data->undertaking_signed_type,
                        "id" => "93972",
                        "doctypecode" => "8270",
                        "docRefId" => "8271",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentEleven'] = $attachment_eleven;
                }

                if(!empty($dbRow->form_data->party_applicant_form)){
                    $party_applicant_form = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->party_applicant_form));

                    $attachment_twelve = array(
                        "encl" =>  $party_applicant_form,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->party_applicant_form_type,
                        "enclType" => $dbRow->form_data->party_applicant_form_type,
                        "id" => "93974",
                        "doctypecode" => "8288",
                        "docRefId" => "8289",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentTwelve'] = $attachment_twelve;
                }

                if(!empty($dbRow->form_data->date_property_tax)){
                    $date_property_tax = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->date_property_tax));

                    $attachment_thirteen = array(
                        "encl" =>  $date_property_tax,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->date_property_tax_type,
                        "enclType" => $dbRow->form_data->date_property_tax_type,
                        "id" => "93975",
                        "doctypecode" => "8275",
                        "docRefId" => "8276",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentThirteen'] = $attachment_thirteen;
                }

                if(!empty($dbRow->form_data->service_plan)){
                    $service_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->service_plan));

                    $attachment_fourteen = array(
                        "encl" =>  $service_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->service_plan_type,
                        "enclType" => $dbRow->form_data->service_plan_type,
                        "id" => "93976",
                        "doctypecode" => "8278",
                        "docRefId" => "8279",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentFourteen'] = $attachment_fourteen;
                }

                if(!empty($dbRow->form_data->parking_plan)){
                    $parking_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->parking_plan));

                    $attachment_fifteen = array(
                        "encl" =>  $parking_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->parking_plan_type,
                        "enclType" => $dbRow->form_data->parking_plan_type,
                        "id" => "93977",
                        "doctypecode" => "8280",
                        "docRefId" => "8281",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentFifteen'] = $attachment_fifteen;
                }

                if(!empty($dbRow->form_data->ownership_document_of_land)){
                    $ownership_document_of_land = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->ownership_document_of_land));

                    $attachment_sixteen = array(
                        "encl" =>  $ownership_document_of_land,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->ownership_document_of_land_type,
                        "enclType" => $dbRow->form_data->ownership_document_of_land_type,
                        "id" => "93978",
                        "doctypecode" => "8282",
                        "docRefId" => "8283",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentSixteen'] = $attachment_sixteen;
                }

                if(!empty($dbRow->form_data->any_other_document)){
                    $any_other_document = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->any_other_document));

                    $attachment_seventeen = array(
                        "encl" =>  $any_other_document,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->any_other_document_type,
                        "enclType" => $dbRow->form_data->any_other_document_type,
                        "id" => "93978",
                        "doctypecode" => "8282",
                        "docRefId" => "8283",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentSeventeen'] = $attachment_seventeen;
                }

                if(!empty($dbRow->form_data->construction_estimate)){
                    $construction_estimate = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->construction_estimate));

                    $attachment_eighteen = array(
                        "encl" =>  $construction_estimate,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->construction_estimate_type,
                        "enclType" => $dbRow->form_data->construction_estimate_type,
                        "id" => "93980",
                        "doctypecode" => "8286",
                        "docRefId" => "8287",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentEighteen'] = $attachment_eighteen;
                }

                // pre($postdata);

                // if(!empty($dbRow->form_data->soft_copy)){
                //     $soft_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->soft_copy));

                //     $attachment_six = array(
                //         "encl" => $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload the Soft  Copy of Application Form",
                //         "enclType" => "Upload the Soft  Copy of Application Form",
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );

                //     $postdata['AttachmentSix'] = $attachment_six;
                // }

                // if(!empty($dbRow->form_data->soft_copy)){
                //     $soft_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->soft_copy));

                //     $attachment_six = array(
                //         "encl" => $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload the Soft  Copy of Application Form",
                //         "enclType" => "Upload the Soft  Copy of Application Form",
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );

                //     $postdata['AttachmentSix'] = $attachment_six;
                // }

                $json = json_encode($postdata);
                pre($json);
                // $buffer = preg_replace( "/\r|\n/", "", $json );
                // $myfile = fopen("D:\\TESTDATA\\newjson1.txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $buffer);
                // fclose($myfile);
                // die;

                // $json_data = json_encode($postdata);
                // $decodedText = stripslashes(html_entity_decode($json_data));
                // $hmac_value = hash_hmac('sha256', $decodedText, $this->secret_key);
    
                // $url = $this->config->item('building_permission_post_url');
                // $curl = curl_init($url);
                // //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                // curl_setopt($curl, CURLOPT_POST, true);
                // curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                //     'applJson' => $decodedText,
                //     'hmac' => urlencode($hmac_value),
                // ));
                // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                // $response = curl_exec($curl);
                // curl_close($curl);
                // pre($response);

                // $var = http_build_query(array(
                //     'applJson' => $decodedText,
                //     'hmac' => urlencode($hmac_value),
                // ));
                // $myfile = fopen("D:\\TESTDATA\\hmac1234555555.txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $var);
                // fclose($myfile);
                // die;

                // log_response($dbRow->service_data->appl_ref_no, $response);

                // if($response){
                //     $response = json_decode($response);

                //     // pre($response);
                    
                //     if($response->ref->status === "success"){
                //         $data_to_update=array(
                //             'service_data.appl_status'=>'submitted',
                //             'service_data.submission_date'=>new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                //             'processing_history'=>$processing_history
                //         );
                //         $this->registration_model->update($dbRow->_id->{'$id'},$data_to_update);

                //         pre($response);

                //         //Sending submission SMS
                //         // $nowTime = date('Y-m-d H:i:s');
                //         // $sms = array(
                //         //     "mobile" => (int)$dbRow->form_data->mobile,
                //         //     "applicant_name" => $dbRow->form_data->applicant_name,
                //         //     "service_name" => 'Appl.for Bulding Permission',
                //         //     "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                //         //     "app_ref_no" => $dbRow->service_data->appl_ref_no
                //         // );
                //         // sms_provider("submission", $sms);
                //         // redirect('spservices/applications/acknowledgement/' . $obj);

                //         // return $this->output
                //         // ->set_content_type('application/json')
                //         // ->set_status_header(200)
                //         // ->set_output(json_encode(array("status"=>true)));
                //     }else{
                //         pre($response);
                //         // $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>'.$dbRow->service_data->appl_ref_no.'</b>, Please try again.');
                //         // $this->my_transactions();
                //         // return;
                //         // return $this->output
                //         // ->set_content_type('application/json')
                //         // ->set_status_header(401)
                //         // ->set_output(json_encode(array("status"=>false)));
                //     }
                // } else {
                //     pre($response);
                // }
            } else {
                pre("Plz. Pay amount");
            }
        } else {
            pre("No appl ref no");
        }

        // $this->my_transactions();
    }
}//End of Necapi