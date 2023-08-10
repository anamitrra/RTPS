<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPHtmlParser\Dom;

class Service_plus extends Rtps
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(){
        $this->load->view('includes/header');
        $this->load->view('index');
        $this->load->view('includes/footer');
    }

    public function get_records(){
        $limit = $this->input->post('length');
        $offset = $this->input->post('start');
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $payment_status = $this->input->post('payment_status');
        $user_type = $this->input->post('user_type');
        $payment_log = $this->getApplicationUserPaymentLogMergedResults($user_type,$payment_status,[$startDate, $endDate],$limit,$offset);
        $payment_log = $this->add_hoa_amount_to_result($payment_log);
        $json_data = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => count($payment_log),
            "recordsFiltered" => count($payment_log),
            "data" => $payment_log,
        );
        echo json_encode($json_data);
    }

    function dump(){
        $limit = $this->input->post('length');
        $offset = $this->input->post('start');
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $application_status = $this->input->post('application_status');
        $user_type = $this->input->post('user_type');
        $countTotalRows = $this->egras_txn_log_assam_model->get_payment_log(null,null,false,false,true);
        pre($countTotalRows);
        $select = 'user_id,transaction_amount,user_name,sign_role,schm_sp.egras_txn_log_assam.service_id,appl_ref_no,dv_txn_status as payment_status,request_html';
        $payment_log = $this->egras_txn_log_assam_model->get_payment_log($select,null,10);

        $payment_log = $this->add_hoa_amount_to_result($payment_log);
    }
    /**
     * @param $payment_log
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\ContentLengthException
     * @throws \PHPHtmlParser\Exceptions\LogicalException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    private function add_hoa_amount_to_result($payment_log)
    {
        return array_map(function($log){
            if ($log['request_html'] === 'ERROR')
                return false;
            $dom = new Dom();
            $dom->loadStr($log['request_html']);
            $hoaAmountArray = [];
            for ($i = 1; $i <= 9; $i++) {
                $amountInput = $dom->find('input[name="AMOUNT' . $i . '"]')[0];
                $hoaInput = $dom->find('input[name="HOA' . $i . '"]')[0];
                if ($amountInput->value && $hoaInput->value) {
//                    $hoaAmountArray[] = [
//                        'HOA' => $hoaInput->value,
//                        'AMOUNT' => $amountInput->value
//                    ];
                    $hoaAmountArray[] = '<div>HOA'.$i.' : '.$hoaInput->value.', </div><div>AMOUNT'.$i.' : '.$amountInput->value.'</div>';
                }
            }
            $log['hoaAmount'] = $hoaAmountArray;
            return $log;
        },$payment_log);
    }

    /**
     * @param $signRole
     * @param $payment_status
     * @param string $appl_last_modified_date_range
     * @param bool $limit
     * @param int $offset
     * @return array
     */
    private function getApplicationUserPaymentLogMergedResults($signRole,$payment_status , $appl_last_modified_date_range = '',$limit = false, $offset = 0){
        if($appl_last_modified_date_range === '' || ($appl_last_modified_date_range[0] == '' && $appl_last_modified_date_range[1] == '')){
            $appl_last_modified_date_range = [date('Y-m-d',strtotime('-1 Months')),date('Y-m-d')];
        }else{
            $startDateTimeStamp = strtotime($appl_last_modified_date_range[0]);
            $startDate = date('d-m-Y',strtotime($appl_last_modified_date_range[0]));
            $endDateTimeStamp = strtotime($appl_last_modified_date_range[1]);//date('d-m-Y',strtotime($appl_last_modified_date_range[1]));
            $dateDiff = $endDateTimeStamp - $startDateTimeStamp;
            $dateDiffInDays = floor($dateDiff/(60 * 60 * 24));
            if($dateDiffInDays > 30){
                $appl_last_modified_date_range = [$appl_last_modified_date_range[0],date('Y-m-d',strtotime($startDate.' +1 month'))];
            }
        }
        $this->load->model('user_model');
        $selectUserColumn = 'user_id, user_name, sign_role';
        $userWhereConditions = ['sign_role' => $signRole];
        $user_list = $this->user_model->my_get_where($selectUserColumn,$userWhereConditions);
        $userIDArray = array_map(function($val){
            return $val->user_id;
        },$user_list);

        if(empty($userIDArray))
            return [];

        $this->load->model('egras_txn_log_assam_model');
        $selectPaymentLog = 'transaction_amount, user_id, service_id, application_id, dv_txn_status as payment_status, request_html';
//pre($userIDArray);
        $paymentLogWhereConditions['user_id'] = $userIDArray;

        $paymentLogWhereConditions['appl_last_modified_date >='] = $appl_last_modified_date_range[0];
        $paymentLogWhereConditions['appl_last_modified_date <='] = $appl_last_modified_date_range[1];
//        pre($paymentLogWhereConditions);
        if($payment_status)
        $paymentLogWhereConditions['dv_txn_status'] = ($payment_status !== 'NA') ? $payment_status : '';

        $payment_log = $this->egras_txn_log_assam_model->my_get_where($selectPaymentLog,$paymentLogWhereConditions);

//        pre($payment_log);

        $applicationIdArray = array_map(function($val){
            return $val->application_id;
        },$payment_log);

        if(empty($applicationIdArray))
            return [];

        $this->load->model('application_detail_page_model');
        $selectApplicationDetails = 'appl_ref_no, application_id, availed_date';
        // avail_date
        $applicationDetailsWhereConditions = ['application_id' => $applicationIdArray];
        $application_details = $this->application_detail_page_model->my_get_where($selectApplicationDetails,$applicationDetailsWhereConditions);

        $serviceIdArray = [];
        $userPaymentLogMergedResults = [];
        $applicationUserPaymentLogMergedResults = [];
        foreach ($user_list as $user) {
            foreach ($payment_log as $log){
                if($user->user_id === $log->user_id ){
                    $serviceIdArray[] = substr($log->service_id,0,4);
                    $userPaymentLogMergedResults[] = array_merge((array)$user,(array)$log);
                }
            }
        }
        $serviceIdArray = array_values(array_unique($serviceIdArray));

        $sl = 0;
        foreach ($userPaymentLogMergedResults as $mergedResult){
            foreach ($application_details as $application){
                if($application->application_id === $mergedResult['application_id'])
                    $applicationUserPaymentLogMergedResults[] = array_merge((array)$mergedResult,(array)$application,['sl' => ++$sl]);;
            }
        }

        $this->load->model('service_model');
        $serviceList = $this->service_model->get_where_in('service_id',$serviceIdArray,['service_id','service_name.en']);

        $applicationUserPaymentLogServiceMergedResults = [];
        foreach ($applicationUserPaymentLogMergedResults as $mergedResult){
            foreach ($serviceList as $service){
                if(substr($mergedResult['service_id'],0,4) === $service->service_id)
                    $applicationUserPaymentLogServiceMergedResults[] = array_merge((array)$mergedResult,['service_name' => $service->service_name->en]);
            }
        }

        if($limit)
            $applicationUserPaymentLogServiceMergedResults = array_slice($applicationUserPaymentLogServiceMergedResults,$offset,$limit);
        return $applicationUserPaymentLogServiceMergedResults;
    }
}