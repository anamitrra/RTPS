<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Kaacapi extends frontend
{

    private $serviceId = "";
    
    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('kaac/kaac_registration_model');
        $this->load->config('spconfig');
        $this->load->helper('smsprovider');
        $this->load->helper('log');
    } //End of __construct()

    public function update_data()
    {

        // pre("hello");
        log_response($this->input->post("applId"), $_POST);
        $applId =  $this->input->post("applId");
        $wsResponse =  $this->input->post("wsResponse");
        $decodedText = stripslashes(html_entity_decode($wsResponse));
        $resObj = json_decode($decodedText);
        $service_ids = array("KBRC","KRBC");
        if (!empty($resObj)) {
            $status = $resObj->status ?? '';
            $remarks = $resObj->remarks ?? '';
            $amount = $resObj->amount ?? '';
            $certificate = $resObj->certificate ?? '';

            if ($status === 'QS') {
                if (strlen($applId) < 9) {
                    $fields = array(
                        'status' => $status,
                        'remarks' => $remarks,
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
                    $dbrow = $this->kaac_registration_model->get_row(array('service_data.appl_id' => (int)$applId));
                    // pre($dbrow);
                    if (!empty($dbrow)) {
                        if (in_array($dbrow->form_data->service_id, $service_ids)) {

                            $processing_history = $dbrow->processing_history ?? array();

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
                            $this->kaac_registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

                            //Sending Query SMS
                            $sms = array(
                                "mobile" => (int)$dbrow->form_data->mobile,
                                "applicant_name" => $dbrow->form_data->first_name . " " . $dbrow->form_data->last_name,
                                "service_name" => $dbrow->service_data->service_name,
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
                        'remarks' => $remarks,
                        
                        'certificate' => $certificate,
                    );
                    $json_data = json_encode($fields);
                    // pre($json_data);

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
                    $dbrow = $this->kaac_registration_model->get_row(array('service_data.appl_id' => (int)$applId));

                    if (!empty($dbrow)) {
                        if (in_array($dbrow->form_data->service_id, $service_ids)) {
                            $fileName = str_replace('/', '-', $dbrow->service_data->appl_ref_no) . '.pdf';
                            $dirPath = 'storage/docs/kaac' . $this->serviceId . '/';
                            if (!is_dir($dirPath)) {
                                mkdir($dirPath, 0777, true);
                                file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for Delayed Death only</body></html>');
                            }
                            $filePath = $dirPath . $fileName;
                            file_put_contents(FCPATH . $filePath, base64_decode($certificate));
                            //echo '<a href="'.base_url($filePath).'" class="btn btn-success" target="_blank">View File</a>'; die;
                            //Update status and certificate
                            $processing_history = $dbrow->processing_history ?? array();
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
                            $this->kaac_registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

                            //Sending delivered SMS
                            $sms = array(
                                "mobile" => (int)$dbrow->form_data->mobile,
                                "applicant_name" => $dbrow->form_data->first_name . " " . $dbrow->form_data->last_name,
                                "service_name" => $dbrow->service_data->service_name,
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
                        'remarks' => $remarks,
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
                    $dbrow = $this->kaac_registration_model->get_row(array('service_data.appl_id' => (int)$applId));

                    if (!empty($dbrow)) {
                        if (in_array($dbrow->form_data->service_id, $service_ids)) {

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
                                "processed_by" => "Forwarded",
                                "action_taken" => "Forwarded",
                                "remarks" => (isset($remarks) ? $remarks : ""),
                                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            );
                            $data = array(
                                'service_data.appl_status' => $status,
                                'processing_history' => $processing_history
                            );

                            $this->kaac_registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

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
                        'remarks' => $remarks,
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
                    $dbrow = $this->kaac_registration_model->get_row(array('service_data.appl_id' => (int)$applId));

                    if (!empty($dbrow)) {
                        if (in_array($dbrow->form_data->service_id, $service_ids)) {
                            $processing_history = $dbrow->processing_history ?? array();
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
                            $this->kaac_registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

                            //Sending Query SMS
                            $sms = array(
                                "mobile" => (int)$dbrow->form_data->mobile,
                                "applicant_name" => $dbrow->form_data->first_name . " " . $dbrow->form_data->last_name,
                                "service_name" => $dbrow->service_data->service_name,
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
                    } else {
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
                        'amount' => $amount,
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
                    $dbrow = $this->kaac_registration_model->get_row(array('service_data.appl_id' => (int)$applId));

                    if (!empty($dbrow)) {
                        if (in_array($dbrow->form_data->service_id, $service_ids)) {

                            if ($dbrow->service_data->appl_status == 'FRS') {
                                $resPost = array(
                                    'encryptKey' => $this->input->post("encryptKey"),
                                    'status' => "true"
                                );
                            }

                            $processing_history = $dbrow->processing_history ?? array();

                            $processing_history[] = array(
                                "processed_by" => "Payment Query made by Department",
                                "action_taken" => "Payment Query made raised",
                                "remarks" => (isset($remarks) ? $remarks : ""),
                                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            );

                            $data = array(
                                'service_data.appl_status' => $status,
                                'form_data.frs_request' => array(
                                    'amount' => $amount,
                                ),
                                'processing_history' => $processing_history
                            );
                            $this->kaac_registration_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $data);

                            //Sending Query SMS
                            $sms = array(
                                "mobile" => (int)$dbrow->form_data->mobile,
                                "applicant_name" => $dbrow->form_data->first_name . " " . $dbrow->form_data->last_name,
                                "service_name" => $dbrow->service_data->service_name,
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
                    else {
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
            //End of if else
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

    // public function repost_application()
    // {

    //     $appl_ref_no = $this->input->post("appl_ref_no");

    //     if ($appl_ref_no) {

    //         $filter = array(
    //             "service_data.appl_ref_no" => $appl_ref_no
    //         );

    //         $dbRow = $this->kaac_registration_model->get_row($filter);
    //         $obj = $dbRow->{'_id'}->{'$id'};

    //         if ($dbRow->service_data->appl_status != "submitted") {
    //             pre("Status : " . $dbRow->service_data->appl_status . " : Application isn't submitted yet");
    //         }

    //         $postdata = array(
    //             "cscid" => "RTPS1234",
    //             "cscoffice" => "NA",
    //             "applicantName" => $dbRow->form_data->first_name . " " . $dbrow->form_data->last_name,
    //             "applicantMobileNo" => $dbRow->form_data->mobile,
    //             "applicantGender" => $dbRow->form_data->applicant_gender,
    //             "relationWithDecease" => $dbRow->form_data->relation_with_deceased,
    //             //"relationWithDeceasedOther" => $dbRow->form_data->other_relation,
    //             //"emailId" => $dbRow->form_data->email,
    //             //"panNo" => $dbRow->form_data->pan_no,
    //             //"aadharNo" => $dbRow->form_data->aadhar_no,

    //             "deceasedName" => $dbRow->form_data->name_of_deceased,
    //             "deceasedFatherName" => $dbRow->form_data->father_name_of_deceased,
    //             "deceasedMotherName" => $dbRow->form_data->father_name_of_deceased,
    //             "dateofDeath" => $dbRow->form_data->deceased_dod,
    //             "ageofDeceased	" => $dbRow->form_data->age_of_deceased,
    //             "deceasedGender" => $dbRow->form_data->deceased_gender,
    //             "placeofDeath" => $dbRow->form_data->place_of_death,
    //             //"addressHospital" => $dbRow->form_data->address_of_hospital_home,
    //             //"placeofDeathOther" => $dbRow->form_data->other_place_of_death,
    //             "reasonForLate" => $dbRow->form_data->reason_for_late,

    //             "state" => "Assam",
    //             "district" => $dbRow->form_data->district,
    //             "subDivision" => $dbRow->form_data->sub_division,
    //             "circleOffice" => $dbRow->form_data->revenue_circle,
    //             "deceasedVillageorTown" => $dbRow->form_data->village_town,
    //             "deceasedPin" => $dbRow->form_data->pin_code,

    //             "application_ref_no" => $dbRow->service_data->appl_ref_no,
    //             "service_type" => "PDDR",
    //             "fillUpLanguage" => "English",

    //             'spId' => array('applId' => $dbRow->service_data->appl_id)
    //         );

    //         if (!empty($dbRow->form_data->other_relation))
    //             $postdata['relationWithDeceasedOther'] = $dbRow->form_data->other_relation;

    //         if (!empty($dbRow->form_data->place_of_death)) {
    //             if (($dbRow->form_data->place_of_death == "Hospital") || ($dbRow->form_data->place_of_death == "House")) {
    //                 $postdata['addressHospital'] = $dbRow->form_data->address_of_hospital_home;
    //             } elseif ($dbRow->form_data->place_of_death == "Other") {
    //                 $postdata['placeofDeathOther'] = $dbRow->form_data->other_place_of_death;
    //             }
    //         }

    //         if (!empty($dbRow->form_data->pan_no))
    //             $postdata['panNo'] = $dbRow->form_data->pan_no;

    //         if (!empty($dbRow->form_data->aadhar_no))
    //             $postdata['aadharNo'] = $dbRow->form_data->aadhar_no;

    //         if (!empty($dbRow->form_data->email))
    //             $postdata['emailId'] = $dbRow->form_data->email;

    //         if (!empty($dbRow->form_data->affidavit)) {
    //             $affidavit = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->affidavit));

    //             $attachment_three = array(
    //                 "encl" =>  $affidavit,
    //                 "docType" => "application/pdf",
    //                 "enclFor" => "Affidavit",
    //                 "enclType" => $dbRow->form_data->affidavit_type,
    //                 "id" => "65441673",
    //                 "doctypecode" => "7503",
    //                 "docRefId" => "7504",
    //                 "enclExtn" => "pdf"
    //             );

    //             $postdata['AttachmentThree'] = $attachment_three;
    //         }

    //         if (!empty($dbRow->form_data->others)) {
    //             $others = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->others));

    //             $attachment_four = array(
    //                 "encl" =>  $others,
    //                 "docType" => "application/pdf",
    //                 "enclFor" => "Others",
    //                 "enclType" => $dbRow->form_data->others_type,
    //                 "id" => "65441673",
    //                 "doctypecode" => "7503",
    //                 "docRefId" => "7504",
    //                 "enclExtn" => "pdf"
    //             );

    //             $postdata['AttachmentFour'] = $attachment_four;
    //         }

    //         if (!empty($dbRow->form_data->doctor_certificate)) {
    //             $doctor_certificate = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->doctor_certificate));

    //             $attachment_one = array(
    //                 "encl" =>  $doctor_certificate,
    //                 "docType" => "application/pdf",
    //                 "enclFor" => "Hospital or Doctor Certificate regarding Death",
    //                 "enclType" => $dbRow->form_data->doctor_certificate_type,
    //                 "id" => "65441673",
    //                 "doctypecode" => "7503",
    //                 "docRefId" => "7504",
    //                 "enclExtn" => "pdf"
    //             );

    //             $postdata['AttachmentOne'] = $attachment_one;
    //         }

    //         if (!empty($dbRow->form_data->proof_residence)) {
    //             $proof_residence = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->proof_residence));

    //             $attachment_two = array(
    //                 "encl" => $proof_residence,
    //                 "docType" => "application/pdf",
    //                 "enclFor" => "Proof of Resident",
    //                 "enclType" => $dbRow->form_data->proof_residence_type,
    //                 "id" => "65441673",
    //                 "doctypecode" => "7503",
    //                 "docRefId" => "7504",
    //                 "enclExtn" => "pdf"
    //             );

    //             $postdata['AttachmentTwo'] = $attachment_two;
    //         }

    //         if (!empty($dbRow->form_data->soft_copy)) {
    //             $soft_copy = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->soft_copy));

    //             $attachment_zero = array(
    //                 "encl" => $soft_copy,
    //                 "docType" => "application/pdf",
    //                 "enclFor" => "Upload the Soft  Copy of Application Form",
    //                 "enclType" => "Upload the Soft  Copy of Application Form",
    //                 "id" => "65441673",
    //                 "doctypecode" => "7503",
    //                 "docRefId" => "7504",
    //                 "enclExtn" => "pdf"
    //             );

    //             $postdata['AttachmentZero'] = $attachment_zero;
    //         }

    //         // $json = json_encode($postdata);
    //         // $buffer = preg_replace( "/\r|\n/", "", $json );
    //         // $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
    //         // fwrite($myfile, $buffer);
    //         // fclose($myfile);
    //         // die;

    //         $url = $this->config->item('delayed_death_url');
    //         $curl = curl_init($url);
    //         curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    //         curl_setopt($curl, CURLOPT_POST, true);
    //         curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
    //         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    //         $response = curl_exec($curl);

    //         curl_close($curl);

    //         log_response($dbRow->service_data->appl_ref_no, $response);

    //         // var_dump($response);
    //         // exit();

    //         if ($response) {
    //             $response = json_decode($response);

    //             if ($response->ref->status === "success") {
    //                 $data_to_update = array(
    //                     'form_data.edistrict_ref_no' => $response->ref->edistrict_ref_no,
    //                     'service_data.appl_status' => 'submitted',
    //                 );
    //                 $this->kaac_registration_model->update($obj, $data_to_update);

    //                 // $nowTime = date('Y-m-d H:i:s');
    //                 // $sms = array(
    //                 //     "mobile" => (int)$dbRow->form_data->mobile,
    //                 //     "applicant_name" => $dbRow->form_data->applicant_name,
    //                 //     "service_name" => $dbrow->service_data->service_name,
    //                 //     "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
    //                 //     "app_ref_no" => $dbRow->service_data->appl_ref_no
    //                 // );
    //                 // sms_provider("submission", $sms);

    //                 pre($response);

    //                 // return $this->output
    //                 // ->set_content_type('application/json')
    //                 // ->set_status_header(200)
    //                 // ->set_output(json_encode(array("status"=>true)));
    //             } else {

    //                 pre("false");
    //                 // return $this->output
    //                 // ->set_content_type('application/json')
    //                 // ->set_status_header(401)
    //                 // ->set_output(json_encode(array("status"=>false)));
    //             }
    //         }
    //     } else {
    //         pre("Pleae! Enter Application Ref No");
    //     }
    //     // return json_encode(array("resp"=>"dd"));
    //     //pre($this->input->post());
    // }
}//End of Necapi