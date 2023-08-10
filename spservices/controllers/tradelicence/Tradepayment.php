<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Tradelicencepayment extends Rtps
{
    private $serviceId = "TRADE";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('services_model');
        $this->load->model('tradelicence/licence_model');
        //  $this->load->model('applications_model');
        // $this->load->model('necprocessing_model');
        $this->load->model('sros_model');
        $this->load->model('iservices/admin/users_model');
        $this->load->config('spconfig');
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else
    } //End of __construct()

    private function my_transactions()
    {
        if ($this->session->role) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        } //End of if else
    } //End of my_transactions()

    private function check_pfc_payment_status($DEPARTMENT_ID = null)
    {
        if ($DEPARTMENT_ID) {
            $min = $this->applications_model->checkPFCPaymentIntitateTime($DEPARTMENT_ID);
            // pre( $min);
            if ($min !== 'N' && $min < 6) {
                $this->session->set_flashdata('pay_message', 'Please verify payment status after 5 minutes');
                $this->my_transactions();
                return;
            }
            $ref = modules::load('spservices/Payment_response');
            $grndata = $ref->checkgrn($DEPARTMENT_ID, false, true);

            if (!empty($grndata)) {
                if ($grndata['STATUS'] === 'Y') {
                    $this->session->set_flashdata('pay_message', 'Payment mode is already in Y');
                    $this->my_transactions();
                    return true;
                }
                $ar = array('N', 'A');
                if (!empty($grndata['GRN']) && !in_array($grndata['STATUS'], $ar)) {
                    $this->session->set_flashdata('pay_message', 'Payment status is either in N or A');
                    $this->my_transactions();
                    return;
                }
            }
        }
        return false;
    }

    public function application_submission($obj_id)
    {

        $dbrow = $this->income_registration_model->get_row(['_id' => new ObjectId($obj_id)]);
        $ref = modules::load('spservices/tradelicence/paymentresponse');
        $ref->post_data($dbrow->department_id,);
    }

    public function initiate($obj_id = null)
    {
        $dbRow = $this->licence_model->get_by_doc_id($obj_id);

        if (count((array) $dbRow)) {

            if (property_exists($dbRow, 'pfc_payment_status') && $dbRow->pfc_payment_status == 'Y') {
                $this->application_submission($obj_id);
                $this->session->set_flashdata('pay_message', 'Payment already and hence the status has updated');
                $this->my_transactions();
            }

            if (!empty($dbRow->payment_params) && !empty($dbRow->department_id)) {
                $res = $this->check_pfc_payment_status($dbRow->department_id);
                if ($res) {
                    $this->application_submission($obj_id);
                }
            }

            $payData = array(
                'status' => 'payment_initiated',
                'payment_status' => 'START'
            );
            $this->licence_model->update_where(['_id' => new ObjectId($obj_id)], $payData); //Update status            
            $data = array("pageTitle" => "Make Payment");
            $data["dbrow"] = $dbRow;

            if (!empty($this->session->userdata('role'))) {
                $data['service_charge'] = $this->config->item('service_charge');
                $data['objid'] = $obj_id;
                $data['no_printing_page'] = isset($data["dbrow"]->no_printing_page) ? $data["dbrow"]->no_printing_page : '';
                $data['no_scanning_page'] = isset($data["dbrow"]->no_scanning_page) ? $data["dbrow"]->no_scanning_page : '';
                $this->load->view('includes/frontend/header');
                $this->load->view('tradelicence/tradelicencepayment_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->payment_make($obj_id);
            } //End of if else
        } else {
            $this->session->set_flashdata('pay_message', 'No records found');
            $this->my_transactions();
        } //End of if else
    } //End of initiate()

    public function submit()
    {
        $this->form_validation->set_rules('objid', 'objid', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('no_printing_page', 'No printing page', 'trim|required|xss_clean|strip_tags|numeric');

        $this->form_validation->set_rules('no_scanning_page', 'No scanning page', 'trim|required|xss_clean|strip_tags|numeric');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        $obj_id = $this->input->post('objid');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->initiate($obj_id);
        } else {
            $this->payment_make($obj_id);
        } //End of if else
    } //End of submit()

    private function payment_make($obj_id = null)
    {
        $dbRow = $this->licence_model->get_by_doc_id($obj_id); //pre($dbRow);
        if (count((array) $dbRow)) {
            $sroRow = $this->sros_model->get_row(array("org_unit_code" => $dbRow->sro_code));
            if ($sroRow) {
                $treasuryCode = isset($sroRow->treasury_code) ? $sroRow->treasury_code : '';
                $officeCode = isset($sroRow->office_code) ? $sroRow->office_code : '';
                if (strlen($treasuryCode) && strlen($officeCode)) {
                    $serviceRow = $this->services_model->get_row(array("service_id" => $this->serviceId));
                    if ($serviceRow) {
                        $uniqid = uniqid();
                        $DEPARTMENT_ID = $uniqid . time();
                        $data = array();
                        $data['rtps_trans_id'] = $dbRow->rtps_trans_id;

                        //Fees calculation 
                        $searched_from = (int)$dbRow->searched_from;
                        $searched_to = (int)$dbRow->searched_to;
                        $yearDiff = $searched_to - $searched_from;
                        $yearFee = ($yearDiff > 0) ? $yearDiff * 3 : 0;
                        $delivery_mode = $dbRow->delivery_mode;
                        $rtpsFee = 20;
                        if ($delivery_mode === 'delivery_urgent') {
                            $chargeFee = ($yearFee + 5) * 2;
                        } else {
                            $chargeFee = $yearFee + 5;
                        } //End of if else
                        $CHALLAN_AMOUNT = (float) $rtpsFee + (float) $chargeFee;

                        $data['department_data'] = array(
                            "DEPT_CODE" => $serviceRow->DEPT_CODE, //$user->dept_code,
                            "PAYMENT_TYPE" => isset($serviceRow->PAYMENT_TYPE) ? $serviceRow->PAYMENT_TYPE : "",
                            "TREASURY_CODE" => $sroRow ? $sroRow->treasury_code : "",
                            "OFFICE_CODE" => $sroRow ? $sroRow->office_code : "",
                            "REC_FIN_YEAR" => "2022-2023", //dynamic
                            "PERIOD" => "O", // O for ontimee payment
                            "FROM_DATE" => "01/04/2022",
                            "TO_DATE" => "31/03/2099",
                            "MAJOR_HEAD" => isset($serviceRow->MAJOR_HEAD) ? $serviceRow->MAJOR_HEAD : "",
                            "AMOUNT1" => $chargeFee,
                            "HOA1" => isset($serviceRow->HOA1) ? $serviceRow->HOA1 : "",
                            "AMOUNT2" => $rtpsFee,
                            "HOA2" => isset($serviceRow->HOA2) ? $serviceRow->HOA2 : "",
                            "AMOUNT3" => isset($serviceRow->AMOUNT3) ? $serviceRow->AMOUNT3 : "",
                            "HOA3" => isset($serviceRow->HOA3) ? $serviceRow->HOA3 : "",
                            "AMOUNT4" => isset($serviceRow->AMOUNT4) ? $serviceRow->AMOUNT4 : "",
                            "HOA4" => isset($serviceRow->HOA4) ? $serviceRow->HOA4 : "",
                            "AMOUNT5" => isset($serviceRow->AMOUNT5) ? $serviceRow->AMOUNT5 : "",
                            "HOA5" => isset($serviceRow->HOA5) ? $serviceRow->HOA5 : "",
                            "AMOUNT6" => isset($serviceRow->AMOUNT6) ? $serviceRow->AMOUNT6 : "",
                            "HOA6" => isset($serviceRow->HOA6) ? $serviceRow->HOA6 : "",
                            "AMOUNT7" => isset($serviceRow->AMOUNT7) ? $serviceRow->AMOUNT7 : "",
                            "HOA7" => isset($serviceRow->HOA7) ? $serviceRow->HOA7 : "",
                            "AMOUNT8" => isset($serviceRow->AMOUNT8) ? $serviceRow->AMOUNT8 : "",
                            "HOA8" => isset($serviceRow->HOA8) ? $serviceRow->HOA8 : "",
                            "AMOUNT9" => isset($serviceRow->AMOUNT9) ? $serviceRow->AMOUNT9 : "",
                            "HOA9" => isset($serviceRow->HOA9) ? $serviceRow->HOA9 : "",
                            "CHALLAN_AMOUNT" => $CHALLAN_AMOUNT,
                            "TAX_ID" => "",
                            "PAN_NO" => "",
                            "PARTY_NAME" => isset($dbRow->applicant_name) ? $dbRow->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
                            "PIN_NO" => isset($dbRow->pin_code) ? $dbRow->pin_code : "781005",
                            "ADDRESS1" => isset($dbRow->address1) ? $dbRow->address1 : "NIC",
                            "ADDRESS2" => isset($dbRow->address2) ? $dbRow->address2 : "",
                            "ADDRESS3" => isset($dbRow->address3) ? $dbRow->address3 : "781005",
                            "MOBILE_NO" => $dbRow->mobile,
                            "DEPARTMENT_ID" => $DEPARTMENT_ID,
                            // "SUB_SYSTEM"=>"REV-SP|".base_url('iservices/admin/get/payment-response'),
                            "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/tradelicence/traderesponse/paymentmade'),
                        );
                        if ($this->slug === "CSC" || $this->slug === "PFC") {
                            if ($this->slug === "CSC") {
                                $account = $this->config->item('csc_account');
                            } else {
                                $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
                                $account = $user->account1;
                            } //End of if else
                            $data['service_charge'] = $this->config->item('service_charge');
                            $data['scanning_charge_per_page'] = $this->config->item('scanning_charge');
                            $data['printing_charge_per_page'] = $this->config->item('printing_charge');
                            $data['no_printing_page'] = $this->input->post('no_printing_page');
                            $data['no_scanning_page'] = $this->input->post('no_scanning_page');
                            $data['pfc_payment'] = true;

                            $total_amount = $data['service_charge'];

                            if ($this->slug === "PFC") {
                                if (!empty($data['no_printing_page']) && (intval($data['no_printing_page']) < 0 || !is_numeric($data['no_printing_page']))) {
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
                                    $total_amount += intval($data['no_printing_page']) * floatval($data['printing_charge_per_page']);
                                }
                                if ($data['no_scanning_page'] > 0) {
                                    $total_amount += intval($data['no_scanning_page']) * floatval($data['scanning_charge_per_page']);
                                }
                            }

                            $data['department_data']['MULTITRANSFER'] = "Y";
                            $data['department_data']['NON_TREASURY_PAYMENT_TYPE'] = "02";
                            $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = $total_amount;
                            $data['department_data']['AC1_AMOUNT'] = $total_amount;
                            $data['department_data']['ACCOUNT1'] = $account;
                        }

                        //pre($data);
                        $res = $this->update_pfc_payment_amount($data, $CHALLAN_AMOUNT);
                        if ($res) {
                            $this->load->view('iservices/basundhara/payment', $data);
                        } else {
                            $this->session->set_flashdata('pay_message', 'Error in payment status updating');
                            $this->my_transactions();
                        } //End of if else
                    } else {
                        $this->session->set_flashdata('pay_message', 'SRO details does not matched');
                        $this->my_transactions();
                    } //End of if else
                } else {
                    $data = array(
                        "msg_title" => "Payment configuration",
                        "msg_body" => "Payment configuration is not yet done with the selected office/department.<br> We are working on it. Please try again later.<br><br> Thank you."
                    );
                    $this->load->view('includes/frontend/header');
                    $this->load->view('nec/custominfo_view', $data);
                    $this->load->view('includes/frontend/footer');
                } //End of if else
            } else {
                $data = array(
                    "msg_title" => "Payment configuration",
                    "msg_body" => "Payment configuration is not yet done with the selected office/department.<br> We are working on it. Please try again later.<br><br> Thank you."
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('nec/custominfo_view', $data);
                $this->load->view('includes/frontend/footer');
            } //End of if else
        } else {
            $this->session->set_flashdata('pay_message', 'No records found');
            $this->my_transactions();
        } //End of if else
    } //End of payment_make()

    public function update_pfc_payment_amount($data, $CHALLAN_AMOUNT)
    {
        $payment_params = $data['department_data'];
        $rtps_trans_id = $data['rtps_trans_id'];
        $data_to_update = array(
            'amount' => $CHALLAN_AMOUNT,
            'department_id' => $payment_params['DEPARTMENT_ID'],
            'payment_params' => $payment_params,
            'payment_status' => 'INITIATED'
        );
        if (isset($data['pfc_payment'])) {
            $data_to_update['service_charge'] = $data['service_charge'];
            $data_to_update['scanning_charge_per_page'] = $data['scanning_charge_per_page'];
            $data_to_update['printing_charge_per_page'] = $data['printing_charge_per_page'];
            $data_to_update['no_printing_page'] = $data['no_printing_page'];
            $data_to_update['no_scanning_page'] = $data['no_scanning_page'];
        }

        $result = $this->necertificates_model->update_row(['rtps_trans_id' => $rtps_trans_id], $data_to_update);
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

    /*private function payment_check($DEPARTMENT_ID = null) {
        if ($DEPARTMENT_ID) {
            $min = $this->necertificates_model->checkPaymentIntitateTime($DEPARTMENT_ID);
            // pre( $min);
            if ($min !== 'N' && $min < 6) {
                $this->session->set_flashdata('pay_message', 'Please verify payment status after 5 minutes');
                $this->my_transactions();
                return;
            }
            $ref = modules::load('spservices/Query_payment_response');
            $grndata = $ref->checkgrn($DEPARTMENT_ID, false, true);
            if (!empty($grndata)) {
                if ($grndata['STATUS'] === 'Y') {
                    $this->session->set_flashdata('pay_message', 'Payment mode is already in Y');
                    $this->my_transactions();
                    return;
                }
                $ar = array('N', 'A');
                if (!empty($grndata['GRN']) && !in_array($grndata['STATUS'], $ar)) {
                    $this->session->set_flashdata('pay_message', 'Payment status is either in N or A');
                    $this->my_transactions();
                    return;
                }
            }
        }
    }//End of payment_check()*/
}//End of Necpayment