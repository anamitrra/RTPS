<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Nokapi extends frontend {

    private $serviceId = "NOKIN";

    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('nextofkin/registration_model');
        $this->load->config('spconfig');  
        $this->load->helper('smsprovider');
        $this->load->helper('log');
    }//End of __construct()

    // public function update_data() { 

    //     $applId =  $this->input->post("applId");
    //     $wsResponse =  $this->input->post("wsResponse");
    //     $decodedText = stripslashes(html_entity_decode($wsResponse));
    //     $resObj = json_decode($decodedText);

    //     if(strlen($applId) < 7){
            
    //     }

    //     // $json = json_encode($this->input->post("encryptKey"));
    //     //     $buffer = preg_replace( "/\r|\n/", "", $json );
    //     //     $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
    //     //     fwrite($myfile, $buffer);
    //     //     fclose($myfile);
    //     //      die;

    //     $dbrow = $this->registration_model->get_row(array('service_data.appl_id'=>(int)$applId));

    //     if(count((array)$dbrow)) {

    //         $processing_history = $dbrow->processing_history??array();
    //         $appl_ref_no = $dbrow->service_data->appl_ref_no;
    //         $status = $resObj->status??'';
    //         $remarks = $resObj->remark??'';
    //         $certificate = $resObj->certificate??'';

    //         if($status === 'QS') {
    //             $processing_history[] = array(
    //                 "processed_by" => "Query raised by Department",
    //                 "action_taken" => "Query raised",
    //                 "remarks" => (isset($remarks)?$remarks: ""),
    //                 "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
    //             );
    //             $data = array(
    //                 'service_data.appl_status' => $status,
    //                 'processing_history' => $processing_history
    //             );
    //             $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

    //             //Sending Query SMS
    //             $sms=array(
    //                 "mobile" => (int)$dbrow->form_data->mobile,
    //                 "applicant_name" => $dbrow->form_data->applicant_name,
    //                 "service_name" => 'Next of Kin Certificate',
    //                 "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
    //                 "app_ref_no" => $dbrow->service_data->appl_ref_no
    //             );
    //             sms_provider("query",$sms);
    //             $resPost = array(
    //                 'encryptKey' => $this->input->post("encryptKey"),
    //                 'status' => "true"
    //             );
    //         } elseif($status === 'D') {
    //             $fileName = str_replace('/', '-', $appl_ref_no).'.pdf';
    //             $dirPath = 'storage/docs'.$this->serviceId.'/';
    //             if (!is_dir($dirPath)) {
    //                 mkdir($dirPath, 0777, true);
    //                 file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for NOK only</body></html>');
    //             }
    //             $filePath = $dirPath.$fileName;
    //             file_put_contents(FCPATH.$filePath, base64_decode($certificate));
    //             //echo '<a href="'.base_url($filePath).'" class="btn btn-success" target="_blank">View File</a>'; die;
    //             //Update status and certificate
    //             $processing_history[] = array(
    //                 "processed_by" => "Application delivered by Department",
    //                 "action_taken" => "Application delivered",
    //                 "remarks" => (isset($remarks)?$remarks: ""),
    //                 "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
    //             );
    //             $data = array(
    //                 'service_data.appl_status' => $status,
    //                 'form_data.certificate' => $filePath,
    //                 'processing_history' => $processing_history
    //             );
    //             $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

    //             //Sending delivered SMS
    //             $sms=array(
    //                 "mobile" => (int)$dbrow->form_data->mobile,
    //                 "applicant_name" => $dbrow->form_data->applicant_name,
    //                 "service_name" => 'Next of kin Certificate',
    //                 "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
    //                 "app_ref_no" => $dbrow->service_data->appl_ref_no,
    //             );
    //             sms_provider("delivery",$sms);

    //             $resPost = array(
    //                 'encryptKey' => $this->input->post("encryptKey"),
    //                 'status' => "true"
    //             );
    //         } elseif($status === 'F') {
    //             $processing_history[] = array(
    //                 "processed_by" => "Forwarded",
    //                 "action_taken" => "Forwarded",
    //                 "remarks" => (isset($remarks)?$remarks: ""),
    //                 "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
    //             );
    //             $data = array(
    //                 'service_data.appl_status' => $status,
    //                 'processing_history' => $processing_history
    //             );

    //             $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

    //             $resPost = array(
    //                 'encryptKey' => $this->input->post("encryptKey"),
    //                 'status' => "true"
    //             );
    //         } elseif($status === 'R') {
    //             $processing_history[] = array(
    //                 "processed_by" => "Rejected",
    //                 "action_taken" => "Rejected",
    //                 "remarks" => (isset($remarks)?$remarks: ""),
    //                 "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
    //             );
    //             $data = array(
    //                 'service_data.appl_status' => $status,
    //                 'processing_history' => $processing_history
    //             );
    //             $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

    //             //Sending Query SMS
    //             $sms=array(
    //                 "mobile" => (int)$dbrow->form_data->mobile,
    //                 "applicant_name" => $dbrow->form_data->applicant_name,
    //                 "service_name" => 'Next of Kin Certificate',
    //                 "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
    //                 "app_ref_no" => $dbrow->service_data->appl_ref_no
    //             );
    //             sms_provider("rejection",$sms);
    //             $resPost = array(
    //                 'encryptKey' => $this->input->post("encryptKey"),
    //                 'status' => "true"
    //             );
    //         } else {
    //             $resPost = array(
    //                 'encryptKey' => $this->input->post("encryptKey"),
    //                 'status' => "false"
    //             );
    //         }//End of if else
    //     } else {
    //         $resPost = array(
    //             'encryptKey' => $this->input->post("encryptKey"),
    //             'status' => "false"
    //         );
    //     }//End of if else
        
    //     $json_obj = json_encode($resPost);
    //     return $this->output
    //         ->set_content_type('application/json')
    //         ->set_status_header(200)
    //         ->set_output($json_obj);
    // }//End of update_data()

    public function update_data()
    {
        log_response($this->input->post("applId"), $_POST);
        $applId =  $this->input->post("applId");
        $wsResponse =  $this->input->post("wsResponse");
        $decodedText = stripslashes(html_entity_decode($wsResponse));
        $resObj = json_decode($decodedText);

        if (!empty($resObj)) {
            $status = $resObj->status ?? '';
            $remarks = $resObj->remark ?? '';
            $certificate = $resObj->certificate ?? '';

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
                            "service_name" => 'Next of Kin Certificate',
                            "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
                            "app_ref_no" => $dbrow->service_data->appl_ref_no
                        );
                        sms_provider("query", $sms);
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
            } elseif ($status === 'D') {
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
                        $fileName = str_replace('/', '-', $dbrow->service_data->appl_ref_no) . '.pdf';
                        $dirPath = 'storage/docs' . $this->serviceId . '/';
                        if (!is_dir($dirPath)) {
                            mkdir($dirPath, 0777, true);
                            file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for Next of Kin only</body></html>');
                        }
                        $filePath = $dirPath . $fileName;
                        file_put_contents(FCPATH . $filePath, base64_decode($certificate));
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
                            'form_data.certificate' => $filePath,
                            'processing_history' => $processing_history
                        );
                        $this->registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

                        //Sending delivered SMS
                        $sms = array(
                            "mobile" => (int)$dbrow->form_data->mobile,
                            "applicant_name" => $dbrow->form_data->applicant_name,
                            "service_name" => 'Next of Kin Certificate',
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
                            "service_name" => 'Next of Kin Certificate',
                            "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
                            "app_ref_no" => $dbrow->service_data->appl_ref_no,
                            "submission_office" => "the department."
                        );
                        sms_provider("rejection", $sms);
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
        } //End of if else

        $json_obj = json_encode($resPost);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($json_obj);
    } //End of update_data()

    public function repost_application($appl_ref_no = null){
        $appl_ref_no = $this->input->get("appl_ref_no");
        if($appl_ref_no){

            $filter = array(
                "service_data.appl_ref_no" => $appl_ref_no,
                "service_data.appl_status" => "submitted"
            );
            $dbRow = $this->registration_model->get_row($filter);

                //procesing data
                $processing_history = $dbRow->processing_history??array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by ".$dbRow->form_data->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by ".$dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
                
                $FamilyDetails = array();
                if(count($dbRow->form_data->family_details)) {
                    foreach($dbRow->form_data->family_details as $key => $family_detail) {
    
                        $family_detail = array(
                            "nameOfKin" => $family_detail->name_of_kin,
                            "relationOfKin" => $family_detail->relation,
                            "ageOfKinYear" => $family_detail->age_y_on_the_date_of_application,
                            "ageOfKinMonths" =>$family_detail->age_m_on_the_date_of_application,
                        );
                        $FamilyDetails[] = $family_detail;
                    }//End of foreach()        
                }//End of if
    
                $postdata=array(
                    "dateOfBirth" => $dbRow->form_data->dob,
                    "cscid" => "RTPS1234",
                    "cscoffice" => "NA",
                    "circleOffice" => $dbRow->form_data->revenue_circle,
                    "applicantName" => $dbRow->form_data->applicant_name,
                    "applicantGender" => $dbRow->form_data->applicant_gender,
                    "applicantMobileNo" => $dbRow->form_data->mobile,
                    "emailId" => $dbRow->form_data->email,
                    "districtofDeceased" => $dbRow->form_data->deceased_district,
                    "subdivisionDeceased" => $dbRow->form_data->deceased_sub_division,
                    "villageofDeceased" => $dbRow->form_data->deceased_village,
                    "townPermanent" => $dbRow->form_data->deceased_town,
                    "pinPermanent" => $dbRow->form_data->deceased_pin_code,
                    "FamilyDetails" => $FamilyDetails,
                    "application_ref_no" => $dbRow->service_data->appl_ref_no,
                    "service_type" => "NOK",
                    "district" => $dbRow->form_data->district,
                    "subDivision" => $dbRow->form_data->sub_division,
                    "revenueCircleofDeceased" => $dbRow->form_data->deceased_revenue_circle,
                    "policeStationPermanent" => $dbRow->form_data->deceased_police_station,
                    "postOfficePermanent" => $dbRow->form_data->deceased_post_office,
                    "mauzaPermanent" => $dbRow->form_data->deceased_mouza,
                    "state" => "Assam",
                    "panNo" => $dbRow->form_data->pan_no,
                    "aadharNo" => $dbRow->form_data->aadhar_no,
                    "DeceasedName" => $dbRow->form_data->name_of_deceased,
                    "deceasedGender" => $dbRow->form_data->deceased_gender,
                    "dateOfDeath" => $dbRow->form_data->deceased_dod,
                    "DeathReason" => $dbRow->form_data->reason_of_death,
                    "PlaceofDeath" => $dbRow->form_data->place_of_death,
                    "fatherofDeceased" => $dbRow->form_data->father_name_of_deceased,
                    "fatherName" => $dbRow->form_data->father_name,
                    "motherName" => $dbRow->form_data->mother_name,
                    "husbandName" => $dbRow->form_data->spouse_name,
                    "Relation" => $dbRow->form_data->relationship_with_deceased,
                    "fillUpLanguage" => "English",
    
                    'spId'=>array('applId'=>$dbRow->service_data->appl_id)
                );
    
                if(!empty($dbRow->form_data->affidavit)){
                    $affidavit = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->affidavit));
    
                    $attachment_one = array(
                        "encl" =>  $affidavit,
                        "docType" => "application/pdf",
                        "enclFor" => "Affidavit",
                        "enclType" => $dbRow->form_data->affidavit_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
    
                    $postdata['AttachmentOne'] = $attachment_one;
                }

                if(!empty($dbRow->form_data->others)){
                    $others = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->others));

                    $attachment_four = array(
                        "encl" =>  $others,
                        "docType" => "application/pdf",
                        "enclFor" => "Others",
                        "enclType" => $dbRow->form_data->others_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentFour'] = $attachment_four;
                }
    
                if(!empty($dbRow->form_data->death_proof)){
                    $death_proof = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->death_proof));
    
                    $attachment_three = array(
                        "encl" =>  $death_proof,
                        "docType" => "application/pdf",
                        "enclFor" => "Death Proof",
                        "enclType" => $dbRow->form_data->death_proof_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
    
                    $postdata['AttachmentThree'] = $attachment_three;
                }
    
                if(!empty($dbRow->form_data->doc_for_relationship)){
                    $doc_for_relationship = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->doc_for_relationship));
    
                    $attachment_two = array(
                        "encl" => $doc_for_relationship,
                        "docType" => "application/pdf",
                        "enclFor" => "Document for relationship proof",
                        "enclType" => $dbRow->form_data->doc_for_relationship_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
    
                    $postdata['AttachmentTwo'] = $attachment_two;
                }
    
                // if(!empty($dbRow->form_data->soft_copy)){
                //     $soft_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->soft_copy));
    
                //     $attachment_zero = array(
                //         "encl" => $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload the Soft  Copy of Application Form",
                //         "enclType" => "Upload the Soft  Copy of Application Form",
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );
    
                //     $postdata['AttachmentZero'] = $attachment_zero;
                // }

                //  $json = json_encode($postdata);
                // $buffer = preg_replace( "/\r|\n/", "", $json );
                // $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $buffer);
                // fclose($myfile);
                //  die;
    
                $url=$this->config->item('next_of_kin_url');
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                
                curl_close($curl);
    
                log_response($dbRow->service_data->appl_ref_no, $response);

                if($response){
                    $response = json_decode($response);
                    
                    //pre($response);
                    if($response->ref->status === "success"){
                        $data_to_update=array(
                            'form_data.edistrict_ref_no' => $response->ref->edistrict_ref_no,
                            'service_data.appl_status'=>'submitted',
                            'service_data.submission_date'=>new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            'processing_history'=>$processing_history
                        );
                        $this->registration_model->update($obj,$data_to_update);

                        pre("success");

                        //Sending submission SMS
                        // $nowTime = date('Y-m-d H:i:s');
                        // $sms = array(
                        //     "mobile" => (int)$dbRow->form_data->mobile,
                        //     "applicant_name" => $dbRow->form_data->applicant_name,
                        //     "service_name" => 'Next of Kin Certificate',
                        //     "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                        //     "app_ref_no" => $dbRow->service_data->appl_ref_no
                        // );
                        // sms_provider("submission", $sms);
                        // redirect('spservices/applications/acknowledgement/' . $obj);

                        // return $this->output
                        // ->set_content_type('application/json')
                        // ->set_status_header(200)
                        // ->set_output(json_encode(array("status"=>true)));
                    }else{
                        pre($response);

                        // return $this->output
                        // ->set_content_type('application/json')
                        // ->set_status_header(401)
                        // ->set_output(json_encode(array("status"=>false)));
                    }
                }else{
                    pre("Unable to post data");
                }
        }

        pre($appl_ref_no);
    }
}//End of Necapi