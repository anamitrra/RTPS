<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Payments extends Rtps {

    function __construct() {
        parent::__construct();
        $this->load->model('admin/users_model');
        $this->load->model('intermediator_model');
        $this->load->model('portals_model');
        $this->config->load('rtps_services');
    }

    private function is_admin() {
        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
            return true;
        } else {
            return false;
        }
    }

    private function my_transactions() {
        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }

    public function payment($rtps_trans_id) {
        $app_status = false;
        if (empty($rtps_trans_id)) {
            $this->my_transactions();
        }

        $transaction_data = $this->intermediator_model->get_by_rtps_id($rtps_trans_id); //pre($transaction_data);
        if (empty($transaction_data)) {
            $this->my_transactions();
        }
        $dept_code = "ARI"; //$guidelines->dept_code;
        $office_code = "ARI000"; //$guidelines->office_code;

        if (property_exists($transaction_data, 'applied_by') && !empty($transaction_data->applied_by)) {
            if ($this->is_admin()) {
            }
        } else {

            //for citizen
            $uniqid = uniqid();
            $DEPARTMENT_ID = $uniqid . time();
            $data['rtps_trans_id'] = $rtps_trans_id;

            $data['rtps_trans_id'] = $rtps_trans_id;
            $data['department_data'] = array(
                "DEPT_CODE" => $dept_code, //$user->dept_code,
                "OFFICE_CODE" => $office_code, //$user->office_code,
                "REC_FIN_YEAR" => "2020-2021", //dynamic
                "HOA1" => "",
                "FROM_DATE" => "01/04/2020",
                "TO_DATE" => "31/03/2099",
                "PERIOD" => "O", // O for ontimee payment
                "CHALLAN_AMOUNT" => "0",
                "DEPARTMENT_ID" => $DEPARTMENT_ID,
                "MOBILE_NO" => $transaction_data->mobile_number, //pfc no
                "SUB_SYSTEM" => "ARTPS-SP|" . base_url('iservices/wptbc/get/payment-response'),
                "PARTY_NAME" => isset($transaction_data->applicant_name) ? $transaction_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
                "PIN_NO" => isset($transaction_data->pin_code) ? $transaction_data->pin_code : "781005",
                "ADDRESS1" => isset($transaction_data->address1) ? $transaction_data->address1 : "NIC",
                "ADDRESS2" => isset($transaction_data->address2) ? $transaction_data->address2 : "",
                "ADDRESS3" => isset($transaction_data->address3) ? $transaction_data->address3 : "781005",
                "MULTITRANSFER" => "Y",
                "NON_TREASURY_PAYMENT_TYPE" => "01",
                "TOTAL_NON_TREASURY_AMOUNT" => $this->config->item('service_charge'),
                "AC1_AMOUNT" => $this->config->item('service_charge'),
                "ACCOUNT1" => "ARI64576",
            );
            //   pre($data);
            $res = $this->update_pfc_payment_amount($data);
            if ($res) {
                $this->load->view('basundhara/payment', $data);
            } else {
                $this->my_transactions();
            }
        }
    }

    public function update_pfc_payment_amount($data) {
        $payment_params = $data['department_data'];
        $rtps_trans_id = $data['rtps_trans_id'];
        $data_to_update = array('department_id' => $payment_params['DEPARTMENT_ID'],
            'payment_params' => $payment_params);
        if (isset($data['pfc_payment'])) {
            $data_to_update['service_charge'] = $data['service_charge'];
            $data_to_update['scanning_charge_per_page'] = $data['scanning_charge_per_page'];
            $data_to_update['printing_charge_per_page'] = $data['printing_charge_per_page'];
            $data_to_update['no_printing_page'] = $data['no_printing_page'];
            $data_to_update['no_scanning_page'] = $data['no_scanning_page'];
        }

        $result = $this->intermediator_model->update_payment_status($rtps_trans_id, $data_to_update);
        // pre($result->getMatchedCount());
        if ($result->getMatchedCount()) {
            $this->load->model('admin/pfc_payment_history_model');
            $data_to_update['rtps_trans_id'] = $rtps_trans_id;
            $this->pfc_payment_history_model->insert($data_to_update);
            return true;
        } else {
            return false;
        }
    }

    public function retry_payment($rtps_trans_id) {

        if (empty($rtps_trans_id)) {
            $this->my_transactions();
        }

        $transaction_data = $this->intermediator_model->get_by_rtps_id($rtps_trans_id);
        if (empty($transaction_data)) {
            $this->my_transactions();
        }
        if ($transaction_data->status !== "S") {
            $this->my_transactions();
        }

        //for failed only
        if ($transaction_data->pfc_payment_status !== "N") {
            $this->my_transactions();
        }
        if (empty($transaction_data->payment_params)) {
            $this->my_transactions();
        }
        // $user=$this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
        //  pre($transaction_data->payment_params);
        $uniqid = uniqid();
        $DEPARTMENT_ID = $uniqid . time();

        // $data['service_charge']=$this->config->item('service_charge');
        // $data['scanning_charge']=$this->config->item('scanning_charge');
        // $data['printing_charge']=$this->config->item('printing_charge');
        $data['rtps_trans_id'] = $rtps_trans_id;
        $data['department_data'] = (array) $transaction_data->payment_params;
        $data['department_data']['DEPARTMENT_ID'] = $DEPARTMENT_ID;
        $res = $this->update_pfc_payment_amount($data);
        if ($res) {
            $this->load->view('wptbc/payment', $data);
        } else {
            $this->my_transactions();
        }
    }
}