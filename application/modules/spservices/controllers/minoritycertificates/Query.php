<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Query extends Rtps {

    private $serviceName = "Application for Minority Community Certificate";
    private $serviceId = "MCC";

    public function __construct() {
        parent::__construct();
        $this->load->model('minoritycertificates/districts_model');
        $this->load->model('minoritycertificates/circles_model');
        $this->load->model('minoritycertificates/minoritycertificates_model');
        $this->load->model('minoritycertificates/backup_model');
        
        $this->lang->load('mcc_registration', $this->session->mcc_lang);

        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');

        if ($this->session->role) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
        
        if($this->slug === "CSC"){                
            $this->apply_by = $this->session->userId;
        } else {
            $this->apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        }//End of if else
    }//End of __construct()

    public function index($objId = null) {
        if ($this->checkObjectId($objId)) {
            $dbFilter = array(
                '_id'=>new ObjectId($objId),
                'service_data.applied_by' => $this->apply_by,
                'service_data.appl_status' => 'QUERY_ARISE'
            );
            $dbRow = $this->minoritycertificates_model->get_row($dbFilter);
            if ($dbRow) {
                $data = array(
                    "service_name" => $this->serviceName,
                    "dbrow" => $dbRow,
                    "form_status" => "QUERY_ARISE"
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('minoritycertificates/registration_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'Quey does not raise for the application');
                redirect('spservices/minority-certificate');
            }//End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/minority-certificate');
        }//End of if else
    }//End of index()

    public function submit($objId = null) {
        $dbRow = $this->minoritycertificates_model->get_row(array('_id' => new ObjectId($objId), "service_data.appl_status" => "QUERY_ARISE"));
        if ($dbRow) {
            $data1["form_data.payment_status"] = $dbRow->data_before_query->form_data->payment_status??'';
            $data1["form_data.department_id"] = $dbRow->data_before_query->form_data->department_id??'';
            $data1["form_data.payment_params"] = $dbRow->data_before_query->form_data->payment_params??'';
            $data1["form_data.rtps_convenience_fee"] = $dbRow->data_before_query->form_data->rtps_convenience_fee??'';
            $data1["form_data.pfc_payment_response"] = $dbRow->data_before_query->form_data->pfc_payment_response??'';
            $data1["form_data.pfc_payment_status"] = $dbRow->data_before_query->form_data->pfc_payment_status??'';
            
            $data1["execution_data.0.task_details.action_taken"] = 'Y';
            $data1["execution_data.0.task_details.executed_time"] = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
            $data1["applicant_query"] = false;
            $data1["service_data.appl_status"] = "QUERY_SUBMITTED";
            $data1["execution_data.0.applicant_task_details"] = array(
                'query_answered' => $dbRow->form_data->query_answered,
                "query_ans_file" => $dbRow->form_data->query_doc,
            );

            $data1["execution_data.0.old_data"] = array(
                "appl_no" => $dbRow->service_data->appl_ref_no,
                "remarks" => null
            );
            $task_id = $dbRow->execution_data[1]->task_details->task_id??'';
            $task_details = array();
            if ($task_id == 11) {
                $task_details['task_details'] = array(
                    "appl_no" => $dbRow->service_data->appl_ref_no,
                    "remarks" => null,
                    "task_id" => "2",
                    "action_no" => "",
                    "task_name" => "Application received by Designated Public Servant",
                    "user_type" => "Official",
                    "user_name" => $dbRow->execution_data[1]->task_details->user_detail->user_name,
                    "action_taken" => "N",
                    "payment_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    "payment_mode" => null,
                    "pull_user_id" => null,
                    "executed_time" => null,
                    "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    "user_detail" => $dbRow->execution_data[1]->task_details->user_detail
                );
                $task_details['official_form_details'] = array();
            }

            if ($task_id == 12) {
                $task_details['task_details'] = array(
                    "appl_no" => $dbRow->service_data->appl_ref_no,
                    "remarks" => null,
                    "task_id" => "6",
                    "action_no" => "",
                    "task_name" => "Application received by Lot Mondol",
                    "user_type" => "Official",
                    "user_name" => $dbRow->execution_data[1]->task_details->user_detail->user_name,
                    "action_taken" => "N",
                    "payment_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    "payment_mode" => null,
                    "pull_user_id" => null,
                    "executed_time" => null,
                    "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    "user_detail" => $dbRow->execution_data[1]->task_details->user_detail
                );
                $task_details['official_form_details'] = array();
            }

            // for KAAC & NCHILLS
            if ($task_id == 19) {
                $task_details['task_details'] = array(
                    "appl_no" => $dbRow->service_data->appl_ref_no,
                    "remarks" => null,
                    "task_id" => "17",
                    "action_no" => "",
                    "task_name" => "Application received by Lot Patowary",
                    "user_type" => "Official",
                    "user_name" => $dbRow->execution_data[1]->task_details->user_detail->user_name,
                    "action_taken" => "N",
                    "payment_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    "payment_mode" => null,
                    "pull_user_id" => null,
                    "executed_time" => null,
                    "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    "user_detail" => $dbRow->execution_data[1]->task_details->user_detail
                );
                $task_details['official_form_details'] = array();
            }

            $option = array('upsert' => true);
        
            $this->mongo_db->where(array('_id' => new ObjectId($objId)))->set($data1)->update('sp_applications', $option);
            $this->mongo_db->command(array(
                'update' => 'sp_applications',
                'updates' => [
                    array(
                        'q' => array('_id' => new ObjectId($objId)),
                        'u' => array('$push' => array(
                                'execution_data' => array(
                                    '$each' => [$task_details],
                                    '$position' => 0
                                )))
                    ),
                ],
            ));
            $this->session->set_flashdata('success', 'Quey has been succesfully submitted');
            redirect('spservices/minority-certificate-query-acknowledgement');
        } else {
            $this->session->set_flashdata('error', 'Quey does not raise for the application');
            redirect('spservices/minority-certificate');
        }//End of if else
    }//End of submit()
    
    public function acknowledgement() {
        $data = array(
            "msg_title" => "Query Submitted",
            "msg_body" => "Your reply to the query has been submitted successsfully. Thank you"
        );
        $this->load->view('includes/frontend/header');
        $this->load->view('minoritycertificates/submitacknowledgement_view', $data);
        $this->load->view('includes/frontend/footer');
    }//End of acknowledgement()

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Query
