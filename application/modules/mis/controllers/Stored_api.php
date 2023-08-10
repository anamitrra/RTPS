<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
error_reporting(E_ALL);
ini_set('display_errors', 1);


class Stored_api extends frontend
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Generate
     * @return Void
     */
    public function generate()
    {
        $this->application_count();

        $this->officewise();
        $this->gender_wise_application_count();
        $this->departmentewise_application_count();
        $this->servicewise_application_count();
        $this->calulate_popular_services();
        $this->all_service_status();

        $this->top_services_last_month();
        
        echo "Data Generated Successfully" . PHP_EOL;
        die;
    }
    public function officewise()
    {
        $this->load->model("officewise_application_count_model");
        $received = $this->officewise_application_count_model->officewise_application_count();
        $rejected = $this->officewise_application_count_model->total_services_rejected_group_by_service();
        // pre($received);
        $delivered = $this->officewise_application_count_model->total_services_delivered_group_by_service();
        $pit = $this->officewise_application_count_model->check_timeline_for_all_services_pending_in_time_group_by();
        //pre($pit);
        $timely_delivered = $this->officewise_application_count_model->check_timeline_for_all_services();
        $rejected_in_time = $this->officewise_application_count_model->rejected_in_time_all_services();
        $applicant = $this->officewise_application_count_model->applicant();
        // pre($applicant);
        if (!is_array($received)) $received = (array)$received;
        if (!is_array($rejected)) $rejected = (array)$rejected;
        if (!is_array($pit)) $pit = (array)$pit;
        if (!is_array($delivered)) $delivered = (array)$delivered;
        if (!is_array($timely_delivered)) $timely_delivered = (array)$timely_delivered;
        if (!is_array($rejected_in_time)) $rejected_in_time = (array)$rejected_in_time;
        if (!is_array($applicant)) $applicant = (array)$applicant;

        foreach ($received as $key => $r_val) {
            foreach ($delivered as $key => $d_val) {
                if ($d_val->_id == $r_val->_id) {
                    $r_val->delivered = isset($d_val->count) ? $d_val->count : 0;
                }
            }
            foreach ($rejected as $key => $re_val) {
                if ($re_val->_id == $r_val->_id) {
                    $r_val->rejected = isset($re_val->count) ? $re_val->count : 0;
                }
            }
            foreach ($timely_delivered as $key => $t_val) {
                if ($t_val->_id == $r_val->_id) {
                    $r_val->timely_delivered = isset($t_val->count) ? $t_val->count : 0;
                }
            }
            foreach ($rejected_in_time as $key => $rit_val) {
                if ($rit_val->_id == $r_val->_id) {
                    $r_val->rit = isset($rit_val->count) ? $rit_val->count : 0;
                }
            }
            foreach ($pit as $key => $pit_val) {
                if ($pit_val->_id == $r_val->_id) {
                    $r_val->pit = isset($pit_val->count) ? $pit_val->count : 0;
                }
            }
            foreach ($applicant as $key => $pit_val) {
                if ($pit_val->_id == $r_val->_id) {
                    $r_val->paa = isset($pit_val->count) ? $pit_val->count : 0;
                }
            }

            if (!isset($r_val->rejected)) {
                $r_val->rejected = 0;
            }
            if (!isset($r_val->delivered)) {
                $r_val->delivered = 0;
            }
            if (!isset($r_val->timely_delivered)) {
                $r_val->timely_delivered = 0;
            }
            if (!isset($r_val->pit)) {
                $r_val->pit = 0;
            }
            if (!isset($r_val->rit)) {
                $r_val->rit = 0;
            }
            if (!isset($r_val->paa)) {
                $r_val->paa = 0;
            }

            $r_val->pending = $r_val->total_received - ($r_val->delivered + $r_val->rejected);
        }

        // pre($received);

        $this->load->model("stored_api_model");
        $this->stored_api_model->insert(array(
            'created_at' => new UTCDateTime(strtotime(date('d-m-Y h:i A')) * 1000),
            'type' => 4,
            'data' => $received
        ));
    }
    public function departmentewise()
    { //die('here');
        $this->load->model("departmentwise_application_count_model");
        $received = $this->departmentwise_application_count_model->count();
        $rejected = $this->departmentwise_application_count_model->total_services_rejected_group_by_service();
        //pre($received);
        $delivered = $this->departmentwise_application_count_model->total_services_delivered_group_by_service();
        $pit = $this->departmentwise_application_count_model->check_timeline_for_all_services_pending_in_time_group_by();
        //pre($pit);
        $timely_delivered = $this->departmentwise_application_count_model->check_timeline_for_all_services();
        $applicant = $this->departmentwise_application_count_model->applicant();
        // pre($timely_delivered);
        if (!is_array($received)) $received = (array)$received;
        if (!is_array($rejected)) $rejected = (array)$rejected;
        if (!is_array($pit)) $pit = (array)$pit;
        if (!is_array($delivered)) $delivered = (array)$delivered;
        if (!is_array($timely_delivered)) $timely_delivered = (array)$timely_delivered;
        if (!is_array($applicant)) $applicant = (array)$applicant;
        foreach ($received as $key => $r_val) {
            foreach ($delivered as $key => $d_val) {
                if ($d_val->_id == $r_val->_id) {
                    $r_val->delivered = isset($d_val->count) ? $d_val->count : 0;
                }
            }
            foreach ($rejected as $key => $re_val) {
                if ($re_val->_id == $r_val->_id) {
                    $r_val->rejected = isset($re_val->count) ? $re_val->count : 0;
                }
            }
            foreach ($timely_delivered as $key => $t_val) {
                if ($t_val->_id == $r_val->_id) {
                    $r_val->timely_delivered = isset($t_val->count) ? $t_val->count : 0;
                }
            }
            foreach ($pit as $key => $pit_val) {
                if ($pit_val->_id == $r_val->_id) {
                    $r_val->pit = isset($pit_val->count) ? $pit_val->count : 0;
                }
            }
            foreach ($applicant as $key => $pit_val) {
                if ($pit_val->_id == $r_val->_id) {
                    $r_val->paa = isset($pit_val->count) ? $pit_val->count : 0;
                }
            }
            if (!isset($r_val->rejected)) {
                $r_val->rejected = 0;
            }
            if (!isset($r_val->delivered)) {
                $r_val->delivered = 0;
            }
            if (!isset($r_val->timely_delivered)) {
                $r_val->timely_delivered = 0;
            }
            if (!isset($r_val->pit)) {
                $r_val->pit = 0;
            }
            if (!isset($r_val->paa)) {
                $r_val->paa = 0;
            }
            $r_val->pending = $r_val->total_received - ($r_val->delivered + $r_val->rejected);
        }

        // pre($received);

        $this->load->model("stored_api_model");
        $this->stored_api_model->insert(array(
            'created_at' => new UTCDateTime(strtotime(date('d-m-Y h:i A')) * 1000),
            'type' => 8,
            'data' => $received
        ));
    }
    public function all_service_status()
    {
        $this->load->model("api_model");
        $this->load->model("services_model");
        $received = $this->api_model->total_services_group_by_service();
        $delivered = $this->api_model->total_services_delivered_group_by_service();
        $timely_delivered = $this->api_model->check_timeline_for_all_delivered_services();
        $rejected = $this->api_model->total_services_rejected_group_by_service();
        $rejected_in_time = $this->api_model->rejected_in_time_all_services();
        $pit = $this->api_model->pending_in_time_applications();
        $applicant = $this->api_model->applicant();
        // pre($applicant);
        $services = $this->services_model->get_all([]);



        if (!is_array($received)) $received = (array)$received;
        if (!is_array($rejected)) $rejected = (array)$rejected;
        if (!is_array($delivered)) $delivered = (array)$delivered;
        if (!is_array($timely_delivered)) $timely_delivered = (array)$timely_delivered;
        if (!is_array($rejected_in_time)) $rejected_in_time = (array)$rejected_in_time;
        if (!is_array($pit)) $pit = (array)$pit;
        if (!is_array($applicant)) $applicant = (array)$applicant;

        //echo '<pre>';
        foreach ($received as $key => $r_val) {
            $gender_data = $this->api_model->total_services_group_by_service_gender_wise($r_val->_id);
            $gender_array = array();
            $gender_array['Male'] = 0;
            $gender_array['Female'] = 0;
            $gender_array['Others'] = 0;
            $gender_array['NA'] = 0;
            //echo $r_val->_id;
            //print_r($gender_data);
            foreach ($gender_data as $key => $val) {
                if ($val->_id == 'Male' || $val->_id == 'Male / পুৰুষ') {
                    $gender_array['Male'] += $val->total_received;
                } else if ($val->_id == 'Female' || $val->_id == 'Female / মহিলা') {
                    $gender_array['Female'] += $val->total_received;
                } else if ($val->_id == 'Others / অন্যান্য' || $val->_id == 'Others') {
                    $gender_array['Others'] += $val->total_received;
                } else {
                    $gender_array['NA'] += $val->total_received;
                }
            }
            $r_val->gender_data = $gender_array;
            // pre($gender_array);
            foreach ($delivered as $key => $d_val) {
                if ($d_val->_id == $r_val->_id) {
                    $r_val->delivered = isset($d_val->count) ? $d_val->count : 0;
                }
            }
            foreach ($rejected as $key => $re_val) {
                if ($re_val->_id == $r_val->_id) {
                    $r_val->rejected = isset($re_val->count) ? $re_val->count : 0;
                }
            }
            foreach ($timely_delivered as $key => $t_val) {
                if ($t_val->_id == $r_val->_id) {
                    $r_val->timely_delivered = isset($t_val->count) ? $t_val->count : 0;
                }
            }
            foreach ($rejected_in_time as $key => $rit_val) {
                if ($rit_val->_id == $r_val->_id) {
                    $r_val->rit = isset($rit_val->count) ? $rit_val->count : 0;
                }
            }
            foreach ($pit as $key => $pit_val) {
                if ($pit_val->_id == $r_val->_id) {
                    $r_val->pit = isset($pit_val->count) ? $pit_val->count : 0;
                }
            }

            

            foreach ($applicant as $key => $app) {

                if ($app->_id == $r_val->_id) {
                    $r_val->paa = isset($app->count) ? $app->count : 0;
                }
            }

            // print_r($applicant);
            // die;


            if (!isset($r_val->rejected)) {
                $r_val->rejected = 0;
            }
            if (!isset($r_val->delivered)) {
                $r_val->delivered = 0;
            }
            if (!isset($r_val->timely_delivered)) {
                $r_val->timely_delivered = 0;
            }
            if (!isset($r_val->rit)) {
                $r_val->rit = 0;
            }
            if (!isset($r_val->pit)) {
                $r_val->pit = 0;
            }
            if (!isset($r_val->paa)) {
                $r_val->paa = 0;
            }
            $r_val->pending = $r_val->total_received - ($r_val->delivered + $r_val->rejected);
        }
        foreach ($services as $val) {
            foreach ($received as $r_val) {
                ($val->service_id == $r_val->_id) ? $r_val->service_name = $val->service_name : "";
            }
        }

        // pre($received);

        $this->load->model("stored_api_model");
        $this->stored_api_model->insert(array(
            'created_at' => new UTCDateTime(strtotime(date('d-m-Y h:i A')) * 1000),
            'type' => 3,
            'data' =>  $received
        ));
    }
    public function application_count()
    {
        $this->load->model("applications_model");
        $this->load->model("stored_api_model");
        $total = $this->applications_model->total_rows();
        // $deliver = $this->applications_model->tot_search_rows(array(
        //     'execution_data.0.official_form_details.action' =>
        //     'Deliver',
        // ));
        // $reject = $this->applications_model->tot_search_rows(array(
        //     'execution_data.0.official_form_details.action' =>
        //     'Reject',
        // ));

        $deliver = $this->applications_model->tot_search_rows(array(
            'initiated_data.appl_status' => 'D',
        ));
        $reject = $this->applications_model->tot_search_rows(array(
            'initiated_data.appl_status' => 'R',
        ));

        // $pendingIT = $this->calculate_pending_time();
        // $timelyDelivered = $this->calculate_timely_delivery();
        // $rit = $this->applications_model->check_timeline_for_rejected_applications();

        $pendingIT = $this->applications_model->tot_search_rows(array(
            'initiated_data.appl_status' => ['$nin' => ["D", "R"]],
            "initiated_data.pit" => 1,
        ));
        $timelyDelivered = $this->applications_model->tot_search_rows(array(
            'initiated_data.appl_status' => 'D',
            "initiated_data.dit" => 1,
        ));
        $rit = $this->applications_model->tot_search_rows(array(
            'initiated_data.appl_status' => 'R',
            "initiated_data.rit" => 1,
        ));


        $this->stored_api_model->insert(array(
            'created_at' => new UTCDateTime(strtotime(date('d-m-Y h:i A')) * 1000),
            'type' => 1,
            'data' => array(
                'received' => $total,
                'pending' => ($total - ($reject + $deliver)),
                'pending_in_time' => $pendingIT, //pending in time
                'delivered' => $deliver,
                'rejected' => $reject,
                'timely_delivered' => $timelyDelivered,
                'rit' => $rit  // rejected in time
            )
        ));
    }
    public function calculate_pending_time()
    {
        $this->load->model("applications_model");
        // if ($this->applications_model->check_timeline_for_pending_applications()) {
        //     return $this->applications_model->check_timeline_for_pending_applications()->pass;
        // } else {
        //     return 0;
        // }

        return $this->applications_model->calculate_pending_in_time();
    }
    public function calculate_timely_delivery()
    {
        $this->load->model("applications_model");
        return $this->applications_model->check_timeline()->pass;
    }
    public function gender_wise_application_count()
    {
        $this->load->model("applications_model");
        $this->load->model("stored_api_model");
        $total_received_gender_wise = $this->applications_model->total_received_gender_wise();
        $gender_array = array();
        $gender_array['Male'] = 0;
        $gender_array['Female'] = 0;
        $gender_array['Others'] = 0;
        $gender_array['NA'] = 0;
        //pre($total_received_gender_wise);
        foreach ($total_received_gender_wise as $key => $val) {
            if ($val->gender == 'Male' || $val->gender == 'Male / পুৰুষ') {
                $gender_array['Male'] += $val->total_received;
            } else if ($val->gender == 'Female' || $val->gender == 'Female / মহিলা') {
                $gender_array['Female'] += $val->total_received;
            } else if ($val->gender == 'Others' || $val->gender == 'Others / অন্যান্য') {
                $gender_array['Others'] += $val->total_received;
            } else {
                $gender_array['NA'] += $val->total_received;
            }
        }
        $this->stored_api_model->insert(array(
            'created_at' => new UTCDateTime(strtotime(date('d-m-Y h:i A')) * 1000),
            'type' => 2,
            'data' => $gender_array
        ));
    }


    public function officewise_appilcation_for_ovca()
    {
        $master_array = array();
        //$temp=array();
        $this->load->model("officewise_application_count_model");
        $received = $this->officewise_application_count_model->officewise_application_count_for_ovca();
        $ovca_total = $this->officewise_application_count_model->total_ovca_count();
        $master_total = 0;
        foreach ($received as $key => $value) {
            $temp = array();
            $temp['office_name'] = $value->_id;
            if ($temp['office_name'] == 'District Administration( DISTRICT - KAMRUP )') {
                $temp['total'] = $ovca_total;
            } else {
                $temp['total'] = $value->total_applications;
            }
            array_push($master_array, $temp);
        }
        $pending_applications = $this->officewise_application_count_model->officewise_application_pending_count__for_ovca();
        //pre($master_total);
        //pre($pending_applications);
        $pending_count = 0;
        foreach ($pending_applications as $key => $val) {
            foreach ($master_array as $key => $value) {
                if ($value['office_name'] == $val->office_name) {
                    $master_array[$key]['pending'] = $val->total_applications;
                } else if ($val->office_name == NULL && $val->total_applications > 0) {
                    if ($master_array[$key]['office_name'] == 'District Administration( DISTRICT - KAMRUP )') {
                        $master_array[$key]['pending'] = $val->total_applications;
                        //$master_array[$key]['total']=$ovca_total;
                    } else {
                        $master_array[$key]['pending'] = 0;
                    }
                }
            }
        }
        //pre($master_array);
        $rejected = $this->officewise_application_count_model->officewise_application_rejected_count__for_ovca();
        foreach ($rejected as $key => $val) {
            foreach ($master_array as $key => $value) {
                if ($value['office_name'] == $val->office_name) {
                    $master_array[$key]['rejected'] = $val->total_applications;
                } else {
                    $master_array[$key]['rejected'] = 0;
                }
            }
        }
        //pre($master_array);
        $delivered = $this->officewise_application_count_model->officewise_application_delivered_count__for_ovca();
        foreach ($delivered as $key => $val) {
            foreach ($master_array as $key => $value) {
                if ($value['office_name'] == $val->office_name) {
                    $master_array[$key]['approved'] = $val->total_applications;
                } else {
                    $master_array[$key]['approved'] = 0;
                }
            }
        }
        foreach ($master_array as $key => $value) {
            if (!isset($value['rejected'])) {
                $master_array[$key]['rejected'] = 0;
            }
            $master_array[$key]['processed'] = $value['total'] - $value['pending'];
        }
        foreach ($master_array as $key => $value) {
            if (!isset($value['approved'])) {
                $master_array[$key]['approved'] = 0;
            }
            //$master_array[$key]['processed']=$value['total']-$value['pending'];
        }
        // pre($master_array);
        $this->load->model("stored_api_model");
        $this->stored_api_model->insert(array(
            'created_at' => new UTCDateTime(strtotime(date('d-m-Y h:i A')) * 1000),
            'type' => 5,
            'data' => $master_array
        ));
        // return $this->output
        //     ->set_content_type('application/json')
        //     ->set_status_header(200)
        //     ->set_output(json_encode(array(
        //         'status' => TRUE,
        //         'data' => $master_array
        //     )));
    }
    public function officewise_appilcation_for_ovca_v2()
    {
        $master_array = array();
        //$temp=array();
        $this->load->model("officewise_application_count_model");
        $received = $this->officewise_application_count_model->officewise_application_count_for_ovca();
        $ovca_total = $this->officewise_application_count_model->total_ovca_count();
        //$withOutExecutionData=$this->officewise_application_count_model->officewise_application_count_without_execution_data();
        $withOutExecutionData = 0;
        foreach ($received as $key => $value) {
            $temp = array();
            $temp['office_name'] = $value->_id;
            if ($temp['office_name'] == 'District Administration( DISTRICT - KAMRUP )') {
                $temp['total'] = $ovca_total;
            } else {
                $temp['total'] = $value->total_applications;
            }
            array_push($master_array, $temp);
        }
        $pending_applications = $this->officewise_application_count_model->officewise_application_pending_count__for_ovca();
        //pre($master_array);
        //pre($pending_applications);
        //echo '<pre>';
        foreach ($pending_applications as $key => $val) {
            //var_dump($val->_id);
            if ($val->_id == NULL && $val->total_applications > 0) {
                $withOutExecutionData += $val->total_applications;
                // if($master_array[$key]['office_name']=='District Administration( DISTRICT - KAMRUP )'){
                //     $master_array[$key]['pending']=$val->total_applications;
                //     print_r($val->total_applications);
                //     //pre($val->total_applications+$withOutExecutionData);
                //     //$master_array[$key]['total']=$ovca_total;
                // }else{
                //     $master_array[$key]['pending']=0;
                // }
            }
            foreach ($master_array as $key => $value) {
                if ($value['office_name'] == $val->office_name) {
                    $master_array[$key]['pending'] = $val->total_applications;
                }
            }
        }
        //pre($withOutExecutionData);
        $rejected = $this->officewise_application_count_model->officewise_application_rejected_count__for_ovca();
        foreach ($rejected as $key => $val) {
            foreach ($master_array as $key => $value) {
                if ($value['office_name'] == $val->office_name) {
                    $master_array[$key]['rejected'] = $val->total_applications;
                } else {
                    $master_array[$key]['rejected'] = 0;
                }
            }
        }
        //pre($master_array);
        $delivered = $this->officewise_application_count_model->officewise_application_delivered_count__for_ovca();
        foreach ($delivered as $key => $val) {
            foreach ($master_array as $key => $value) {
                if ($value['office_name'] == $val->office_name) {
                    $master_array[$key]['approved'] = $val->total_applications;
                } else {
                    $master_array[$key]['approved'] = 0;
                }
            }
        }
        //pre($master_array);
        foreach ($master_array as $key => $value) {
            if (!isset($value['rejected'])) {
                $master_array[$key]['rejected'] = 0;
            }
            if ($master_array[$key]['office_name'] == 'District Administration( DISTRICT - KAMRUP )') {
                $master_array[$key]['pending'] = $value['pending'] + $withOutExecutionData;
            }
        }
        foreach ($master_array as $key => $value) {
            if (!isset($value['approved'])) {
                $master_array[$key]['approved'] = 0;
            }
            //$master_array[$key]['processed']=$value['total']-$value['pending'];
        }
        $pending_count = 0;
        $deliver_count = 0;
        $reject_count = 0;
        //pre($master_array);
        foreach ($master_array as $key => $value) {
            $pending_count += $value['pending'];
            $deliver_count += $value['approved'];
            $reject_count += $value['rejected'];
        }
        $total_data = array(
            'total' => $ovca_total,
            'pending_count' => $pending_count,
            'deliver_count' => $deliver_count,
            'reject_count' => $reject_count,
        );
        $this->load->model("stored_api_model");
        $this->stored_api_model->insert(array(
            'created_at' => new UTCDateTime(strtotime(date('d-m-Y h:i A')) * 1000),
            'type' => 6,
            'data' => $master_array
        ));
        $this->stored_api_model->insert(array(
            'created_at' => new UTCDateTime(strtotime(date('d-m-Y h:i A')) * 1000),
            'type' => 7,
            'data' => $total_data
        ));
    }
    public function gender_wise_service_count()
    {
        $this->load->model("api_model");
        $received = $this->api_model->total_services_group_by_service_gender_wise();
    }

    public function departmentewise_application_count()
    {
        $this->load->model("departmentwise_application_count_model");
        $received = $this->departmentwise_application_count_model->count();
        $rejected = $this->departmentwise_application_count_model->total_services_rejected_group_by_service();
        $delivered = $this->departmentwise_application_count_model->total_services_delivered_group_by_service();
        $pit = $this->departmentwise_application_count_model->check_timeline_for_all_services_pending_in_time_group_by();
        $timely_delivered = $this->departmentwise_application_count_model->check_timeline_for_all_services();
        $timely_rejected = $this->departmentwise_application_count_model->check_timeline_for_all_services_rejected_in_time_group_by();
        $applicant = $this->departmentwise_application_count_model->applicant();
        $mean = $this->departmentwise_application_count_model->department_mean();




        if (!is_array($received)) $received = (array)$received;
        if (!is_array($rejected)) $rejected = (array)$rejected;
        if (!is_array($pit)) $pit = (array)$pit;
        if (!is_array($delivered)) $delivered = (array)$delivered;
        if (!is_array($timely_delivered)) $timely_delivered = (array)$timely_delivered;
        if (!is_array($timely_rejected)) $timely_rejected = (array)$timely_rejected;
        if (!is_array($applicant)) $applicant = (array)$applicant;
        // pre($timely_rejected);

        foreach ($received as $key => $r_val) {
            foreach ($delivered as $key => $d_val) {
                if ($d_val->_id == $r_val->_id) {
                    $r_val->delivered = isset($d_val->count) ? $d_val->count : 0;
                }
            }
            foreach ($rejected as $key => $re_val) {
                if ($re_val->_id == $r_val->_id) {
                    $r_val->rejected = isset($re_val->count) ? $re_val->count : 0;
                }
            }
            foreach ($timely_delivered as $key => $t_val) {
                if ($t_val->_id == $r_val->_id) {
                    $r_val->timely_delivered = isset($t_val->count) ? $t_val->count : 0;
                }
            }

            foreach ($timely_rejected as $key => $t_val) {
                if ($t_val->_id == $r_val->_id) {
                    $r_val->timely_rejected = isset($t_val->count) ? $t_val->count : 0;
                }
            }
            // pre( $r_val->$timely_rejected);
            foreach ($pit as $key => $pit_val) {
                if ($pit_val->_id == $r_val->_id) {
                    $r_val->pit = isset($pit_val->count) ? $pit_val->count : 0;
                }
            }
            foreach ($mean as $key => $mean_val) {
                if ($mean_val->_id == $r_val->_id) {
                    $r_val->mean = isset($mean_val->min) ? $mean_val->min : 0;
                }
            }
            foreach ($applicant as $key => $applicant_val) {
                if ($applicant_val->_id == $r_val->_id) {
                    $r_val->paa = isset($applicant_val->count) ? $applicant_val->count : 0;
                }
            }

            $median = $this->departmentwise_application_count_model->department_median($r_val->_id);
            $r_val->median = $median ?? 0;
            if (!isset($r_val->rejected)) {
                $r_val->rejected = 0;
            }
            if (!isset($r_val->delivered)) {
                $r_val->delivered = 0;
            }
            if (!isset($r_val->pit)) {
                $r_val->pit = 0;
            }
            if (!isset($r_val->timely_delivered)) {
                $r_val->timely_delivered = 0;
            }
            if (!isset($r_val->timely_rejected)) {
                $r_val->timely_rejected = 0;
            }
            if (!isset($r_val->paa)) {
                $r_val->paa = 0;
            }
            $r_val->pending = $r_val->total_received - ($r_val->delivered + $r_val->rejected);

            // Pending beyond time
            $r_val->pbt = $r_val->pending - $r_val->pit;
        }

        // pre($received);

        $this->load->model("department_wise_application_model");
        $this->department_wise_application_model->upsert_deptwise_applications($received);
    }


    public function servicewise_application_count()
    {
        $this->load->model("api_model");
        $this->load->model("services_model");
        $this->load->model("servicewise_application_count_model");
        $received = $this->api_model->total_services_group_by_service();
        $delivered = $this->api_model->total_services_delivered_group_by_service();
        $timely_delivered = $this->api_model->check_timeline_for_all_delivered_services();
        $rejected = $this->api_model->total_services_rejected_group_by_service();
        $rejected_in_time = $this->api_model->rejected_in_time_all_services();
        $pit = $this->api_model->pending_in_time_applications();
        $applicant = $this->api_model->applicant();
        $services = $this->services_model->get_all([]);

        if (!is_array($received)) $received = (array)$received;
        if (!is_array($rejected)) $rejected = (array)$rejected;
        if (!is_array($delivered)) $delivered = (array)$delivered;
        if (!is_array($timely_delivered)) $timely_delivered = (array)$timely_delivered;
        if (!is_array($rejected_in_time)) $rejected_in_time = (array)$rejected_in_time;
        if (!is_array($pit)) $pit = (array)$pit;
        if (!is_array($applicant)) $applicant = (array)$applicant;

        foreach ($received as $key => $r_val) {

            $mean = $this->servicewise_application_count_model->service_mean($r_val->_id);
            $r_val->min = $mean[0]->min ?? '-';
            $median = $this->servicewise_application_count_model->service_median($r_val->_id);
            $r_val->median = $median[2] ?? 0;
            $r_val->minimum = $median[0] ?? 0;
            $r_val->maximum = $median[1] ?? 0;
            $r_val->dept_name = $median[3] ?? 0;


            foreach ($delivered as $key => $d_val) {
                if ($d_val->_id == $r_val->_id) {
                    $r_val->delivered = isset($d_val->count) ? $d_val->count : 0;
                }
            }
            foreach ($rejected as $key => $re_val) {
                if ($re_val->_id == $r_val->_id) {
                    $r_val->rejected = isset($re_val->count) ? $re_val->count : 0;
                }
            }
            foreach ($timely_delivered as $key => $t_val) {
                if ($t_val->_id == $r_val->_id) {
                    $r_val->timely_delivered = isset($t_val->count) ? $t_val->count : 0;
                }
            }
            foreach ($rejected_in_time as $key => $rit_val) {
                if ($rit_val->_id == $r_val->_id) {
                    $r_val->rit = isset($rit_val->count) ? $rit_val->count : 0;
                }
            }
            foreach ($pit as $key => $pit_val) {
                if ($pit_val->_id == $r_val->_id) {
                    $r_val->pit = isset($pit_val->count) ? $pit_val->count : 0;
                }
            }
            foreach ($applicant as $key => $pit_val) {
                if ($pit_val->_id == $r_val->_id) {
                    $r_val->paa = isset($pit_val->count) ? $pit_val->count : 0;
                }
            }

            if (!isset($r_val->rejected)) {
                $r_val->rejected = 0;
            }
            if (!isset($r_val->delivered)) {
                $r_val->delivered = 0;
            }
            if (!isset($r_val->timely_delivered)) {
                $r_val->timely_delivered = 0;
            }
            if (!isset($r_val->rit)) {
                $r_val->rit = 0;
            }
            if (!isset($r_val->pit)) {
                $r_val->pit = 0;
            }
            if (!isset($r_val->paa)) {
                $r_val->paa = 0;
            }

            $r_val->pending = $r_val->total_received - ($r_val->delivered + $r_val->rejected);
            // Pending beyond time
            $r_val->pbt = $r_val->pending - $r_val->pit;

            foreach ($services as $key => $serv_val) {

                ($serv_val->service_id == $r_val->_id) ? $r_val->service_timeline = $serv_val->service_timeline : "";

                //if ($serv_val->service_id != $r_val->_id)  { echo $serv_val->service_id . " : " . $r_val->_id . PHP_EOL;  }

            }
        }

        // pre($received);

        $this->load->model("servicewise_applications_model");
        $this->servicewise_applications_model->upsert_servicewise_applications($received);
    }

    // Cache popular services data
    public function calulate_popular_services()
    {
        $this->load->model("api_model");
        $data = $this->api_model->get_popular_services();

        $this->load->model("stored_api_model");
        $this->stored_api_model->insert(array(
            'created_at' => new UTCDateTime(strtotime(date('d-m-Y h:i A')) * 1000),
            'type' => 100,   // New types, starts at 100
            'data' => $data
        ));
    }

    public function top_services_last_month()
    {
        $this->load->model("api_model");
        $data = $this->api_model->get_top_services_last_month();

        $this->load->model("stored_api_model");
        $this->stored_api_model->insert(array(
            'created_at' => new UTCDateTime(strtotime(date('Y-m-d h:i A')) * 1000),
            'type' => 101,
            'data' => $data
        ));
    }
}
