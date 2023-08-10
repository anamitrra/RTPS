<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Payment extends Rtps {

    private $rtps_convenience_fee = 10; //For all payment
    private $rtps_convenience_acc = 'ARI64576';

    public function __construct() {
        parent::__construct();
        $this->load->model('applications_model');
        $this->load->model('registered_deed_model');
        $this->load->model('iservices/admin/users_model');
        // $this->load->config('iservices/rtps_services');
        $this->load->config('spconfig');
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
    }

//End of __construct()

    private function my_transactions() {
        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }

    public function verify($obj_id = null) {
        if ($obj_id) {
            $filter = array("_id" => new ObjectId($obj_id));

            $application = $this->registered_deed_model->get_row($filter);
            //   pre($application->pfc_payment_status);
            if (property_exists($application, 'pfc_payment_status') && $application->pfc_payment_status == 'Y') {
                if($application->status === "payment_initiated"){
                    $this->application_submission($obj_id, $application->service_id);
                }
                $this->my_transactions();
                return;
            }

            if (!empty($application->payment_params) && !empty($application->department_id)) {
                $res = $this->check_pfc_payment_status($application->department_id);
                if ($res) {
                    $this->application_submission($obj_id, $application->service_id);
                } else {
                    $this->initiate($obj_id);
                }
                //check grn;
            } else {
                $this->initiate($obj_id);
            }
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }

    public function application_submission($obj_id, $service_id) {
        if ($service_id === "CRCPY") {
            $ref = modules::load('spservices/Registereddeed');
            $ref->submit_to_backend($obj_id);
        }
    }

    public function initiate($obj_id = null) {
        if ($obj_id) {
            $dbFilter = array('_id' => new ObjectId($obj_id));
            $sessionUser=$this->session->userdata();
            if($this->slug === "user"){                
                $dbFilter['mobile'] = $this->session->mobile;
            } elseif($this->slug === 'PFC') {
                $dbFilter['applied_by'] = new ObjectId($this->session->userdata('userId')->{'$id'});
            } elseif($this->slug === 'CSC') {
                $dbFilter['applied_by'] = $sessionUser['userId'];
            } else {
                $this->session->set_flashdata('message', 'You are not authorized to make the payment');
                $this->my_transactions();
            }//End of if else
            $dbRow = $this->registered_deed_model->get_row($dbFilter);
            //$dbRow = $this->registered_deed_model->get_by_doc_id($obj_id); //pre($dbRow);
            if (property_exists($dbRow, 'pfc_payment_status') && $dbRow->pfc_payment_status == 'Y') {
                $this->application_submission($obj_id, "CRCPY");
                $this->session->set_flashdata('message', 'Payment already and hence the status has updated');
                $this->my_transactions();
            }

            if (!empty($dbRow->payment_params) && !empty($dbRow->department_id)) {
                $res = $this->check_pfc_payment_status($dbRow->department_id);
                if ($res) {
                    $this->application_submission($obj_id, "CRCPY");
                }
            }


            $filter = array("_id" => new ObjectId($obj_id));
            $this->registered_deed_model->add_param($filter, array('status' => 'payment_initiated'));
            $data = array("pageTitle" => "Make Payment");
            $data["dbrow"] = $dbRow;
            
            if (!empty($this->session->userdata('role'))) {
                $data['service_charge'] = $this->config->item('service_charge');
                $data['objid'] = $obj_id;
                $data['no_printing_page'] = isset($data["dbrow"]->no_printing_page) ? $data["dbrow"]->no_printing_page : '';
                $data['no_scanning_page'] = isset($data["dbrow"]->no_scanning_page) ? $data["dbrow"]->no_scanning_page : '';
                $this->load->view('includes/frontend/header');
                $this->load->view('kiosk_payment', $data);
                $this->load->view('includes/frontend/footer');
            } else {                
                $this->pfcpayment($obj_id);
            }//End of if else            
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }//End of initiate()

    public function submit() {
        $this->form_validation->set_rules('objid', 'objid', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('no_printing_page', 'No printing page', 'trim|required|xss_clean|strip_tags|numeric');
        $this->form_validation->set_rules('no_scanning_page', 'No scanning page', 'trim|required|xss_clean|strip_tags|numeric');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = $this->input->post('objid');
            if ($obj_id) {
                $this->initiate($obj_id);
            } else {
                redirect(base_url('iservices/transactions'));
            }
        } else {
            // pre($this->input->post());
            // $this->load->view('basundhara/payment',$data);
            $this->pfcpayment();
        }
    }

    private function pfcpayment($objId = null) {
        if(strlen($objId)) {
            $obj_id = $objId;
        } elseif($this->input->post('objid')) {
            $obj_id = $this->input->post('objid');
        }else {
            $obj_id = null;
            $this->session->set_flashdata('pay_message', 'No records found');
            $this->my_transactions(); 
            exit();
        }//End of if else
        
        /*if ($this->slug === "CSC") {
            $account = $this->config->item('csc_account');
            $mobile = $this->session->userdata('user')->mobileno;
        } else {
            $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
            $account = $user->account1;
            $mobile = $user->mobile;
        }*/

        $uniqid = uniqid();
        $DEPARTMENT_ID = $uniqid . time();
        $data = array();
        $data['service_charge'] = $this->config->item('service_charge');
        
        if ($this->slug === "CSC" || $this->slug === "PFC") {
            $data['scanning_charge_per_page'] = $this->config->item('scanning_charge');
            $data['printing_charge_per_page'] = $this->config->item('printing_charge');
            $data['rtps_trans_id'] = $this->input->post('rtps_trans_id');
            $data['no_printing_page'] = $this->input->post('no_printing_page');
            $data['no_scanning_page'] = $this->input->post('no_scanning_page');
            $data['pfc_payment'] = true;
            $ac1_amount = floatval($data['service_charge']);
        }
        if ($this->slug === "PFC") {
            if (!empty($data['no_printing_page']) && ( intval($data['no_printing_page']) < 0 || !is_numeric($data['no_printing_page']))) {
                return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(array("status" => false, "message" => "Number of page can not be a negative value")));
            }
            if (!empty($data['no_scanning_page']) && intval($data['no_scanning_page']) < 0 || !is_numeric($data['no_printing_page'])) {
                return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(array("status" => false, "message" => "Number of page can not be a negative value")));
            }
            if ($data['no_printing_page'] > 0) {
                // console.log("printing ::"+no_printing_page)
                $ac1_amount = $ac1_amount + intval($data['no_printing_page']) * floatval($data['printing_charge_per_page']);
            }
            if ($data['no_scanning_page'] > 0) {
                // console.log("printing ::"+no_scanning_page)
                $ac1_amount = $ac1_amount + intval($data['no_scanning_page']) * floatval($data['scanning_charge_per_page']);
            }
        }
        //echo "Here : "; pre($obj_id);
        if (strlen($obj_id)) {
            $filter = array("_id" => new ObjectId($obj_id));
            $transaction_data = $this->registered_deed_model->get_row($filter);
            //pre($transaction_data);
            if (empty($transaction_data)) {
                $this->my_transactions();
            }
            $data['rtps_trans_id'] = $transaction_data->rtps_trans_id;

            $dept_code = 'ARI';
            $office_code = "ARI000";
            $curretnYear = date('Y');
            $data['department_data'] = array(
                "DEPT_CODE" => $dept_code, //$user->dept_code,
                "OFFICE_CODE" => $office_code, //$user->office_code,
                "REC_FIN_YEAR" => $curretnYear.'-'.($curretnYear+1),
                "HOA1" => "",
                "FROM_DATE" => '01/04/'.$curretnYear,
                "TO_DATE" => "31/03/2099",
                "PERIOD" => "O", // O for ontimee payment
                "CHALLAN_AMOUNT" => "0",
                "DEPARTMENT_ID" => $DEPARTMENT_ID,
                "MOBILE_NO" => $transaction_data->mobile, //pfc no
                // "SUB_SYSTEM"=>"REV-SP|".base_url('iservices/admin/get/payment-response'),
                "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/certified_copy_landhub/payment_response/response'),
                "PARTY_NAME" => isset($transaction_data->applicant_name) ? $transaction_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
                "PIN_NO" => isset($transaction_data->pin_code) ? $transaction_data->pin_code : "781005",
                "ADDRESS1" => isset($transaction_data->address1) ? $transaction_data->address1 : "NIC",
                "ADDRESS2" => isset($transaction_data->address2) ? $transaction_data->address2 : "",
                "ADDRESS3" => isset($transaction_data->address3) ? $transaction_data->address3 : "781005"
            );
                
            $data['department_data']['MULTITRANSFER'] = "Y";
            $data['department_data']['NON_TREASURY_PAYMENT_TYPE'] = "02";

            if ($this->slug === "CSC") {
                $account1 = $this->config->item('csc_account');
                $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = floatval($ac1_amount) + floatval($this->rtps_convenience_fee);
                $data['department_data']['AC1_AMOUNT'] = $ac1_amount;
                $data['department_data']['ACCOUNT1'] = $account1;
                $data['department_data']['AC2_AMOUNT'] = $this->rtps_convenience_fee;
                $data['department_data']['ACCOUNT2'] = $this->rtps_convenience_acc;
            } elseif ($this->slug === "PFC") {
                $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
                $account1 = $user->account1;
                if ($account1 === $this->rtps_convenience_acc) {
                    $ac1_amount = $ac1_amount + intval($this->rtps_convenience_fee);
                    $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = $ac1_amount;
                    $data['department_data']['AC1_AMOUNT'] = $ac1_amount;
                    $data['department_data']['ACCOUNT1'] = $account1;
                } else {
                    $ac2_amount = $this->rtps_convenience_fee;
                    $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = floatval($ac1_amount) + floatval($ac2_amount);
                    $data['department_data']['AC1_AMOUNT'] = $ac1_amount;
                    $data['department_data']['ACCOUNT1'] = $account1;
                    $data['department_data']['AC2_AMOUNT'] = $this->rtps_convenience_acc;
                    $data['department_data']['ACCOUNT2'] = $ac2_amount;
                }//End of if else
            } else {
                $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = $this->rtps_convenience_fee;
                $data['department_data']['AC1_AMOUNT'] = $this->rtps_convenience_fee;
                $data['department_data']['ACCOUNT1'] = $this->rtps_convenience_acc;
            }//End of if else
            //pre($data);
            
            $res = $this->update_pfc_payment_amount($data);

            if ($res) {
                $this->load->view('iservices/basundhara/payment', $data);
            } else {
                $this->my_transactions();
            }
        } else {
            $this->my_transactions();
            return;
        }
    }

    public function update_pfc_payment_amount($data) {
        $payment_params = $data['department_data'];
        $rtps_trans_id = $data['rtps_trans_id'];
        $data_to_update = array(
            'department_id' => $payment_params['DEPARTMENT_ID'],
            'rtps_convenience_fee' => $this->rtps_convenience_fee,
            'payment_params' => $payment_params);
        if (isset($data['pfc_payment'])) {
            $data_to_update['service_charge'] = $data['service_charge'];
            $data_to_update['scanning_charge_per_page'] = $data['scanning_charge_per_page'];
            $data_to_update['printing_charge_per_page'] = $data['printing_charge_per_page'];
            $data_to_update['no_printing_page'] = $data['no_printing_page'];
            $data_to_update['no_scanning_page'] = $data['no_scanning_page'];
        }

        $result = $this->registered_deed_model->update_payment_status($rtps_trans_id, $data_to_update);
        // pre($result->getMatchedCount());
        if ($result->getMatchedCount()) {
            $this->load->model('iservices/admin/pfc_payment_history_model');
            $data_to_update['rtps_trans_id'] = $rtps_trans_id;
            $data_to_update['createdDtm'] = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
            $this->pfc_payment_history_model->insert($data_to_update);
            return true;
        } else {
            return false;
        }
    }

    public function update_query_payment_amount($data) {
        $payment_params = $data['department_data'];
        $rtps_trans_id = $data['rtps_trans_id'];
        $data_to_update = array('query_department_id' => $payment_params['DEPARTMENT_ID'],
            'query_payment_params' => $payment_params);

        $result = $this->registered_deed_model->update_payment_status($rtps_trans_id, $data_to_update);
        // pre($result->getMatchedCount());
        if ($result->getMatchedCount()) {
            $this->load->model('iservices/admin/pfc_payment_history_model');
            $data_to_update['rtps_trans_id'] = $rtps_trans_id;
            $data_to_update['createdDtm'] = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
            $this->pfc_payment_history_model->insert($data_to_update);
            return true;
        } else {
            return false;
        }
    }

    private function check_pfc_payment_status($DEPARTMENT_ID = null) {


        if ($DEPARTMENT_ID) {
            $min = $this->applications_model->checkPFCPaymentIntitateTime($DEPARTMENT_ID);
            // pre( $min);
            if ($min !== 'N' && $min < 6) {
                $this->session->set_flashdata('errmessage', 'Please verify payment status after 5 minutes');
                $this->my_transactions();
                return;
            }
            $ref = modules::load('spservices/Payment_response');
            $grndata = $ref->checkgrn($DEPARTMENT_ID, false, true);

            if (!empty($grndata)) {
                if ($grndata['STATUS'] === 'Y') {
                    return true;
                }
                $ar = array('N', 'A');
                if (!empty($grndata['GRN']) && !in_array($grndata['STATUS'], $ar)) {
                    $this->my_transactions();
                    return;
                }
            }
        }
        return false;
    }

    private function check_payment_status($DEPARTMENT_ID = null) {


        if ($DEPARTMENT_ID) {
            $min = $this->applications_model->checkPaymentIntitateTime($DEPARTMENT_ID);
            // pre( $min);
            if ($min !== 'N' && $min < 6) {
                $this->session->set_flashdata('errmessage', 'Please verify payment status after 5 minutes');
                $this->my_transactions();
                return;
            }
            $ref = modules::load('spservices/Query_payment_response');
            $grndata = $ref->checkgrn($DEPARTMENT_ID, false, true);
            if (!empty($grndata)) {
                if ($grndata['STATUS'] === 'Y') {
                    $this->my_transactions();
                    return;
                }
                $ar = array('N', 'A');
                if (!empty($grndata['GRN']) && !in_array($grndata['STATUS'], $ar)) {
                    $this->my_transactions();
                    return;
                }
            }
        }
    }

    public function querypayment($obj_id) {

        if ($obj_id) {
            $transaction_data = $this->registered_deed_model->get_by_doc_id($obj_id);
            // pre($transaction_data);
            if (property_exists($transaction_data, "query_payment_status") && $transaction_data->query_payment_status === "Y") {
                $this->my_transactions();
                return;
            }
            if (property_exists($transaction_data, "query_payment_status") && $transaction_data->query_payment_status === "N") {
                
            } else {
                if (property_exists($transaction_data, "query_department_id") && property_exists($transaction_data, "query_payment_params")) {
                    $this->check_payment_status($transaction_data->query_department_id);
                }
            }


            $query_data = json_decode($transaction_data->query->wsResponse);

            $service_info = $this->applications_model->get_service_info($transaction_data->service_id);

            if (empty($service_info)) {
                show_error('No record available for the service.', 403, 'Service Not Found');
                exit(404);
            }
            $office_info = $this->applications_model->get_office_code($transaction_data->sro_code);
            // pre( $office_info);
            if (empty($office_info->office_code)) {
                show_error('No office mapping found.', 403, 'No office mapping found');
                exit(404);
            }

            if ($query_data) {
                $user_fee = $query_data->user_fee;
                $artps_fee = $query_data->artps_fee;

                $uniqid = uniqid();
                $DEPARTMENT_ID = $uniqid . time();
                $data = array();
                $data['rtps_trans_id'] = $transaction_data->rtps_trans_id;

                $dept_code = $service_info->DEPT_CODE;
                $office_code = $office_info->office_code;
                $treasury_code = $office_info->treasury_code;
                $CHALLAN_AMOUNT = 0;

                if (!empty($user_fee)) {
                    $CHALLAN_AMOUNT += floatval($user_fee);
                }

                if (!empty($artps_fee)) {
                    $CHALLAN_AMOUNT += floatval($artps_fee);
                }
                if (!empty($service_info->AMOUNT3)) {
                    $CHALLAN_AMOUNT += floatval($service_info->AMOUNT3);
                }
                $curretnYear = date('Y');
                $data['department_data'] = array(
                    "DEPT_CODE" => $dept_code, //$user->dept_code,
                    "PAYMENT_TYPE" => isset($service_info->PAYMENT_TYPE) ? $service_info->PAYMENT_TYPE : "",
                    "TREASURY_CODE" => isset($treasury_code) ? $treasury_code : "",
                    "OFFICE_CODE" => $office_code,
                    "REC_FIN_YEAR" => $curretnYear.'-'.($curretnYear+1),
                    "PERIOD" => "O", // O for ontimee payment
                    "FROM_DATE" => '01/04/'.$curretnYear,
                    "TO_DATE" => "31/03/2099",
                    "MAJOR_HEAD" => isset($service_info->MAJOR_HEAD) ? $service_info->MAJOR_HEAD : "",
                    "AMOUNT1" => isset($user_fee) ? $user_fee : "",
                    "HOA1" => isset($service_info->HOA1) ? $service_info->HOA1 : "",
                    "AMOUNT2" => isset($artps_fee) ? $artps_fee : "",
                    "HOA2" => isset($service_info->HOA2) ? $service_info->HOA2 : "",
                    "AMOUNT3" => isset($service_info->AMOUNT3) ? $service_info->AMOUNT3 : "",
                    "HOA3" => isset($service_info->HOA3) ? $service_info->HOA3 : "",
                    "AMOUNT4" => isset($service_info->AMOUNT4) ? $service_info->AMOUNT4 : "",
                    "HOA4" => isset($service_info->HOA4) ? $service_info->HOA4 : "",
                    "AMOUNT5" => isset($service_info->AMOUNT5) ? $service_info->AMOUNT5 : "",
                    "HOA5" => isset($service_info->HOA5) ? $service_info->HOA5 : "",
                    "AMOUNT6" => isset($service_info->AMOUNT6) ? $service_info->AMOUNT6 : "",
                    "HOA6" => isset($service_info->HOA6) ? $service_info->HOA6 : "",
                    "AMOUNT7" => isset($service_info->AMOUNT7) ? $service_info->AMOUNT7 : "",
                    "HOA7" => isset($service_info->HOA7) ? $service_info->HOA7 : "",
                    "AMOUNT8" => isset($service_info->AMOUNT8) ? $service_info->AMOUNT8 : "",
                    "HOA8" => isset($service_info->HOA8) ? $service_info->HOA8 : "",
                    "AMOUNT9" => isset($service_info->AMOUNT9) ? $service_info->AMOUNT9 : "",
                    "HOA9" => isset($service_info->HOA9) ? $service_info->HOA9 : "",
                    "CHALLAN_AMOUNT" => $CHALLAN_AMOUNT,
                    "TAX_ID" => "",
                    "PAN_NO" => "",
                    "PARTY_NAME" => isset($transaction_data->applicant_name) ? $transaction_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
                    "PIN_NO" => isset($transaction_data->pin_code) ? $transaction_data->pin_code : "781005",
                    "ADDRESS1" => isset($transaction_data->address1) ? $transaction_data->address1 : "NIC",
                    "ADDRESS2" => isset($transaction_data->address2) ? $transaction_data->address2 : "",
                    "ADDRESS3" => isset($transaction_data->address3) ? $transaction_data->address3 : "781005",
                    "MOBILE_NO" => $transaction_data->mobile,
                    "DEPARTMENT_ID" => $DEPARTMENT_ID,
                    // "SUB_SYSTEM"=>"REV-SP|".base_url('iservices/admin/get/payment-response'),
                    "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/certified_copy_landhub/query_payment_response/response'),
                );                        
                $res = $this->update_query_payment_amount($data);
                if ($res) {
                    $this->load->view('iservices/basundhara/payment', $data);
                } else {
                    $this->my_transactions();
                }
            }
            // pre(  $query_data->artps_fee);
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }

}

//End of Castecertificate
