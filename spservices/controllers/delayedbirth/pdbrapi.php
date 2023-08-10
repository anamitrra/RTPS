<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Pdbrapi extends frontend
{

    private $serviceId = "PDBR";

    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('delayedbirth/delayed_birth_model');
        $this->load->config('spconfig');
        $this->load->helper('smsprovider');
        $this->load->helper("log");
    } //End of __construct()

    public function update_data()
    {
        log_response($this->input->post("applId"),$_POST);
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
                    } else if ($response) {
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
                    $dbrow = $this->delayed_birth_model->get_row(array('service_data.appl_id' => (int)$applId, "service_data.service_id" => $this->serviceId));
                    if (!empty($dbrow)) {
                        $processing_history = $dbrow->processing_history ?? array();
                        $processing_history[] = array(
                            "processed_by" => "Response from e-District Portal",
                            "action_taken" => "Query raised",
                            "remarks" => (isset($remarks) ? $remarks : ""),
                            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        );
                        $data = array(
                            'service_data.appl_status' => $status,
                            'processing_history' => $processing_history
                        );
                        $this->delayed_birth_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

                        //Sending Query SMS
                        $sms = array(
                            "mobile" => (int)$dbrow->form_data->mobile,
                            "applicant_name" => $dbrow->form_data->applicant_name,
                            "service_name" => 'PermissionFor DelayedBirth Reg',
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
                    } else if ($response) {
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
                    $dbrow = $this->delayed_birth_model->get_row(array('service_data.appl_id' => (int)$applId, "service_data.service_id" => $this->serviceId));
                    if (!empty($dbrow)) {
                        $processing_history = $dbrow->processing_history ?? array();
                        $fileName = str_replace('/', '-', $dbrow->service_data->appl_ref_no) . '.pdf';
                        $dirPath = 'storage/docs' . $this->serviceId . '/';
                        if (!is_dir($dirPath)) {
                            mkdir($dirPath, 0777, true);
                            file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for Delayed Birth Certificates only</body></html>');
                        }
                        $filePath = $dirPath . $fileName;
                        file_put_contents(FCPATH . $filePath, base64_decode($certificate));
                        //echo '<a href="'.base_url($filePath).'" class="btn btn-success" target="_blank">View File</a>'; die;
                        //Update status and certificate
                        $processing_history = $dbrow->processing_history ?? array();
                        $processing_history[] = array(
                            "processed_by" => "Response from e-District Portal",
                            "action_taken" => "Certificate Delivered",
                            "remarks" => (isset($remarks) ? $remarks : ""),
                            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        );
                        $data = array(
                            'service_data.appl_status' => $status,
                            'form_data.certificate' => $filePath,
                            'processing_history' => $processing_history
                        );
                        $this->delayed_birth_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

                        //Sending delivered SMS
                        $sms = array(
                            "mobile" => (int)$dbrow->form_data->mobile,
                            "applicant_name" => $dbrow->form_data->applicant_name,
                            "service_name" => 'PermissionFor DelayedBirth Reg',
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
                    if (curl_errno($curl)) {
                        $error_msg = curl_error($curl);
                    }
                    curl_close($curl);
                    if (isset($error_msg)) {
                        die("CURL ERROR : " . $error_msg);
                    } else if ($response) {
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
                    $dbrow = $this->delayed_birth_model->get_row(array('service_data.appl_id' => (int)$applId, "service_data.service_id" => $this->serviceId));
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

                        $processing_history = $dbrow->processing_history ?? array();
                        $processing_history[] = array(
                            "processed_by" => "Response from e-District Portal",
                            "action_taken" => "Forwarded",
                            "remarks" => (isset($remarks) ? $remarks : ""),
                            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        );
                        $data = array(
                            'service_data.appl_status' => $status,
                            'processing_history' => $processing_history
                        );

                        $this->delayed_birth_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

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
                    } else if ($response) {
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
                    $dbrow = $this->delayed_birth_model->get_row(array('service_data.appl_id' => (int)$applId, "service_data.service_id" => $this->serviceId));
                    if (!empty($dbrow)) {
                        $processing_history = $dbrow->processing_history ?? array();
                        $processing_history[] = array(
                            "processed_by" => "Response from e-District Portal",
                            "action_taken" => "Rejected",
                            "remarks" => (isset($remarks) ? $remarks : ""),
                            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        );
                        $data = array(
                            'service_data.appl_status' => $status,
                            'processing_history' => $processing_history
                        );
                        $this->delayed_birth_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

                        //Sending Query SMS
                        $sms = array(
                            "mobile" => (int)$dbrow->form_data->mobile,
                            "applicant_name" => $dbrow->form_data->applicant_name,
                            "service_name" => 'PermissionFor DelayedBirth Reg',
                            "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
                            "app_ref_no" => $dbrow->service_data->appl_ref_no,
                            "submission_office" => '.'
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
}//End of Necapi