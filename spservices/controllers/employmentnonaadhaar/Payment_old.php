<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Payment extends Rtps
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('applications_model');
        $this->load->model('employment_nonaadhaar/employment_model');
        $this->load->model('iservices/admin/users_model');
        $this->load->config('spconfig');
        $this->load->helper('payment');
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
            //echo ($this->slug);
        } else {
            $this->slug = "user";
        } //End of if else
    } //End of __construct()

    private function my_transactions()
    {
        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        } //End of if else        
    } //End of my_transactions()

    private function check_payment_status($DEPARTMENT_ID = null)
    {
        if ($DEPARTMENT_ID) {
            // $this->load->model('iservices/intermediator_model');
            $min = $this->employment_model->checkPFCPaymentIntitateTimeNew($DEPARTMENT_ID);

            if ($min !== 'N' && $min < 6) {
                $this->session->set_flashdata('pay_message', 'Please verify payment status after 5 minutes');
                $this->my_transactions();
                return;
            }

            $ref = modules::load('spservices/employmentnonaadhaar/PaymentResponse');
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
    } //End of check_payment_status()

    //NEW CONTROLLERS ADDED
    public function verify($obj_id = null)
    {
        // pre($obj_id);
        // die;
        if ($obj_id) {
            $filter = array("_id" => new ObjectId($obj_id));

            $application = $this->employment_model->get_row($filter);
            //  pre($application->form_data->pfc_payment_status);
            if (property_exists($application, 'form_data.pfc_payment_status')  && $application->form_data->pfc_payment_status == 'Y') {
                $this->my_transactions();
                return;
            }

            if (
                !empty($application->form_data->payment_params) && !empty($application->form_data->department_id)
            ) {
                // pre('hi');
                $res = $this->check_pfc_payment_status($application->form_data->department_id);
                if ($res) {
                    $this->application_submission($obj_id, $application->service_data->service_id);
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

    private function check_pfc_payment_status($DEPARTMENT_ID = null)
    {
        if ($DEPARTMENT_ID) {
            $min = $this->applications_model->checkPFCPaymentIntitateTimeNew($DEPARTMENT_ID);
            if ($min !== 'N' && $min < 6) {
                $this->session->set_flashdata('pay_message', 'Please verify payment status after 5 minutes');
                $this->my_transactions();
                return;
            }
            
            $ref = modules::load('spservices/employmentnonaadhaar/PaymentResponse');
            $grndata = $ref->checkgrn($DEPARTMENT_ID, false, true);
            // pre($grndata);
            if (!empty($grndata)) {
                if ($grndata['STATUS'] === 'Y') {
                    $this->session->set_flashdata('pay_message', 'Payment mode is already in Y');
                    $this->my_transactions();
                    return true;
                }
                $ar = array('N', 'A');
                if (!empty($grndata['GRN']) && !in_array($grndata['STATUS'], $ar)) {
                    //$this->session->set_flashdata('pay_message', 'Payment status is either in N or A');
                    $this->session->set_flashdata('pay_message', 'Payment status is not updated. please retry after sometime.!');
                    $this->my_transactions();
                    return;
                } 
            }
        }
        return false;
    }
    
    public function application_submission($obj_id, $service_id)
    {
        if ($service_id === "EMP_REG_NA") {
            $ref = modules::load('spservices/employmentnonaadhaar/Registration');
            $ref->post_data($obj_id);
        }
        if ($service_id === "EMP_REREG_NA") {
            $ref = modules::load('spservices/employmentnonaadhaar/Reregistration');
            $ref->post_data($obj_id);
        }
    }

    public function ctz_initiate($obj_id = null)
    {
        if ($obj_id) {
            $dbRow = $this->employment_model->get_by_doc_id($obj_id);
            //CHECK IF ALREADY PAID BY PAYMENT_STATUS
            if (property_exists($dbRow, 'payment_status') && $dbRow->form_data->payment_status == 'Y') {
                $this->application_submission($obj_id, $dbRow->service_data->service_id);
                $this->session->set_flashdata('message', 'Payment already and hence the status has updated');
                $this->my_transactions();
            }

            //CHECK FOR PREVIOUS PAYMENT 
            if (!empty($dbRow->form_data->payment_params) && !empty($dbRow->form_data->department_id)) {
                $res = $this->check_payment_status($dbRow->form_data->department_id);
                if ($res) {
                    $this->application_submission($obj_id, $dbRow->service_data->service_id);
                }
            }

            $filter = array("_id" => new ObjectId($obj_id));
            $this->employment_model->update_where($filter, array('service_data.appl_status' => 'payment_initiated'));
            $data = array("pageTitle" => "Make Payment");

            $data["dbrow"] = $dbRow;
            //pre($dbRow);
            $data['convenience_charge'] = 10;
            //$data['application_charge'] = 10;
            $data['objid'] = $obj_id;
            if ($this->slug === "user") {
                $this->load->view('includes/frontend/header');
                $this->load->view('employment_nonaadhaar/ctz_payment', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->payment_make($obj_id);
            }
        } else {
            redirect(base_url('iservices/transactions'));
        }
    } //End of index()   

    public function initiate($obj_id = null)
    {
        if ($obj_id) {
            $dbRow = $this->employment_model->get_by_doc_id($obj_id);
            //CHECK IF ALREADY PAID BY PAYMENT_STATUS
            if (property_exists($dbRow, 'form_data.payment_status') && $dbRow->form_data->payment_status == 'Y') {
                $this->application_submission($obj_id, $dbRow->service_data->service_id);
                $this->session->set_flashdata('message', 'Payment already and hence the status has updated');
                $this->my_transactions();
            }

            //CHECK FOR PREVIOUS PAYMENT 
            if (!empty($dbRow->form_data->payment_params) && !empty($dbRow->form_data->department_id)) {
                $res = $this->check_payment_status($dbRow->form_data->department_id);
                if ($res) {
                    $this->application_submission($obj_id, $dbRow->service_data->service_id);
                }
            }
            $filter = array("_id" => new ObjectId($obj_id));
            $this->employment_model->update_where($filter, array('service_data.appl_status' => 'payment_initiated'));
            $data = array("pageTitle" => "Make Payment");

            $data["dbrow"] = $dbRow;
            $data['service_charge'] = $this->config->item('service_charge');
            $data['application_charge'] = 30;
            $data['objid'] = $obj_id;
            if ($this->slug !== "user") {
                $data['convenience_charge'] = 10;
                $data['no_printing_page'] = isset($data["dbrow"]->form_data->no_printing_page) ? $data["dbrow"]->form_data->no_printing_page :  '';
                $data['no_scanning_page'] = isset($data["dbrow"]->form_data->no_scanning_page) ? $data["dbrow"]->form_data->no_scanning_page :  '';
                $data['appl_ref_no'] = isset($data["dbrow"]->service_data->appl_ref_no) ? $data["dbrow"]->service_data->appl_ref_no :  '';
                $this->load->view('includes/frontend/header');
                $this->load->view('employment_nonaadhaar/kiosk_payment', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->payment_make($obj_id);
            }
        } else {
            redirect(base_url('iservices/transactions'));
        }
    } //End of index()

    public function submit()
    {
        $this->form_validation->set_rules('objid', 'objid', 'trim|required|xss_clean|strip_tags');

        if ($this->slug !== "user") {
            $this->form_validation->set_rules('no_printing_page', 'No printing page', 'trim|required|xss_clean|strip_tags|numeric');
            $this->form_validation->set_rules('no_scanning_page', 'No scanning page', 'trim|required|xss_clean|strip_tags|numeric');
            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = $this->input->post('objid');
            $this->initiate($obj_id);
        } else {
            $obj_id = $this->input->post('objid');
            $this->payment_make($obj_id);
        } //End of if else
    } //End of submit()

    private function payment_make($obj_id = null)
    {
        $dbRow = $this->employment_model->get_by_doc_id($obj_id);
        if (count((array) $dbRow)) {

            $uniqid = uniqid();
            $DEPARTMENT_ID = $uniqid . time();
            $data = array();
            $data['rtps_trans_id'] = $dbRow->form_data->rtps_trans_id;
            $data['pfc_payment'] = true;

            if ($this->slug === "user") {

                $data['convenience_charge'] = $this->config->item("rtps_convenience_fee");
                $data['department_data'] = array(
                    "DEPT_CODE" => $this->config->item("dept_code"),
                    "OFFICE_CODE" => $this->config->item("office_code"),
                    "REC_FIN_YEAR" => getFinYear(),
                    "HOA1" => "",
                    "FROM_DATE" => firstDateFinYear(),
                    "TO_DATE" => $this->config->item("to_date"),
                    "PERIOD" => "O", // O for one-time payment
                    "CHALLAN_AMOUNT" => "0",
                    "DEPARTMENT_ID" => $DEPARTMENT_ID,
                    "MOBILE_NO" => $dbRow->form_data->mobile_number,
                    // "SUB_SYSTEM" => $this->config->item("sub_system"),
                    "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/employmentnonaadhaar/PaymentResponse/response'),
                    "PARTY_NAME" => $dbRow->form_data->applicant_name ?? 'RTPS TEAM',
                    "PIN_NO" => $dbRow->form_data->pin_code ?? '',
                    "ADDRESS1" => $dbRow->form_data->village ?? '',
                    "ADDRESS2" => $dbRow->form_data->post_office ?? '',
                    "ADDRESS3" => $dbRow->form_data->district_name ?? '',
                    "MULTITRANSFER" => "Y",
                    "NON_TREASURY_PAYMENT_TYPE" => $this->config->item("non_trea_pmt_type"),
                    "TOTAL_NON_TREASURY_AMOUNT" => $this->config->item("rtps_convenience_fee"),
                    "AC1_AMOUNT" => $this->config->item("rtps_convenience_fee"),
                    "ACCOUNT1" => $this->config->item("rtps_convenience_fee_account")

                );
            } else {
                $applicationCharge = $this->config->item('service_charge');
                $total_amount = 0;
                if ($this->slug === "CSC") {
                    $account = $this->config->item('csc_account');
                    // $mobile = $this->session->userdata('user')->mobileno;
                } else {
                    $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
                    $account = $user->account1;
                    // $mobile = $user->mobile;
                }
                $data['service_charge'] = $this->config->item('service_charge');
                $data['scanning_charge_per_page'] = $this->config->item('scanning_charge');
                $data['printing_charge_per_page'] = $this->config->item('printing_charge');

                $data['appl_ref_no'] = $this->input->post('appl_ref_no');
                $data['no_printing_page'] = $this->input->post('no_printing_page');
                $data['no_scanning_page'] = $this->input->post('no_scanning_page');

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

                if ($this->slug !== "CSC") { //pfc
                    $serviceCharge = 0;
                    if ($data['no_printing_page'] > 0) {
                        $serviceCharge += intval($data['no_printing_page']) * floatval($data['printing_charge_per_page']);
                    }
                    if ($data['no_scanning_page'] > 0) {
                        $serviceCharge += intval($data['no_scanning_page']) * floatval($data['scanning_charge_per_page']);
                    }
                }
                if ($this->slug === "CSC") {
                    $total_amount = $applicationCharge;
                } else { //PFC
                    $total_amount = $serviceCharge + $applicationCharge + floatval($this->config->item("rtps_convenience_fee"));
                }

                $data['convenience_charge'] = $this->config->item("rtps_convenience_fee");

                if ($this->slug !== "CSC") { //PFC
                    $data['department_data'] = array(
                        "DEPT_CODE" => $this->config->item("dept_code"),
                        "OFFICE_CODE" => $this->config->item("office_code"),
                        "REC_FIN_YEAR" => getFinYear(),
                        "HOA1" => "",
                        "FROM_DATE" => firstDateFinYear(),
                        "TO_DATE" => $this->config->item("to_date"),
                        "PERIOD" => "O", // O for one-time payment
                        "CHALLAN_AMOUNT" => "0",
                        "DEPARTMENT_ID" => $DEPARTMENT_ID,
                        "MOBILE_NO" => $dbRow->form_data->mobile_number,
                        "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/employmentnonaadhaar/PaymentResponse/response'),
                        "PARTY_NAME" => $dbRow->form_data->applicant_name ?? 'RTPS TEAM',
                        "PIN_NO" => $dbRow->form_data->pin_code ?? '',
                        "ADDRESS1" => $dbRow->form_data->village ?? '',
                        "ADDRESS2" => $dbRow->form_data->post_office ?? '',
                        "ADDRESS3" => $dbRow->form_data->district_name ?? '',
                        "MULTITRANSFER" => "Y",
                        "NON_TREASURY_PAYMENT_TYPE" => $this->config->item("non_trea_pmt_type"),
                        "TOTAL_NON_TREASURY_AMOUNT" => $total_amount,
                        "AC1_AMOUNT" => $total_amount,
                        "ACCOUNT1" => $account,
                    );
                }

                if ($this->slug === "CSC") {
                    $data['department_data'] = array(
                        "DEPT_CODE" => $this->config->item("dept_code"),
                        "OFFICE_CODE" => $this->config->item("office_code"),
                        "REC_FIN_YEAR" => getFinYear(),
                        "HOA1" => "",
                        "FROM_DATE" => firstDateFinYear(),
                        "TO_DATE" => $this->config->item("to_date"),
                        "PERIOD" => "O", // O for one-time payment
                        "CHALLAN_AMOUNT" => "0",
                        "DEPARTMENT_ID" => $DEPARTMENT_ID,
                        "MOBILE_NO" => $dbRow->form_data->mobile_number,
                        "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/employmentnonaadhaar/PaymentResponse/response'),
                        "PARTY_NAME" => $dbRow->form_data->applicant_name ?? 'RTPS TEAM',
                        "PIN_NO" => $dbRow->form_data->pin_code ?? '',
                        "ADDRESS1" => $dbRow->form_data->village ?? '',
                        "ADDRESS2" => $dbRow->form_data->post_office ?? '',
                        "ADDRESS3" => $dbRow->form_data->district_name ?? '',
                        "MULTITRANSFER" => "Y",
                        "NON_TREASURY_PAYMENT_TYPE" => $this->config->item("non_trea_pmt_type"),
                        "TOTAL_NON_TREASURY_AMOUNT" => $total_amount + floatval($this->config->item("rtps_convenience_fee")),
                    );
                    if ($this->config->item("rtps_convenience_fee_account") == $account) { //Check if both account codes are same
                        $data['department_data']['AC1_AMOUNT'] = $total_amount + floatval($this->config->item("rtps_convenience_fee"));
                        $data['department_data']['ACCOUNT1'] = $this->config->item("rtps_convenience_fee_account");
                    } else {
                        $data['department_data']['AC1_AMOUNT'] = $total_amount;
                        $data['department_data']['ACCOUNT1'] = $account;
                        $data['department_data']['AC2_AMOUNT'] = $this->config->item("rtps_convenience_fee");
                        $data['department_data']['ACCOUNT2'] = $this->config->item("rtps_convenience_fee_account");
                    }
                }
            }

            $res = $this->payment_update($data);

            if ($res) {
                $this->load->view('spservices/employment_nonaadhaar/payment', $data);
            } else {
                $this->session->set_flashdata('pay_message', 'Error in payment status updating');
                $this->my_transactions();
            }
        } else {
            $this->session->set_flashdata('pay_message', 'No records found');
            $this->my_transactions();
        }
    } //End of payment_make

    public function payment_update($data)
    {

        $payment_params = $data['department_data'];
        $rtps_trans_id = $data['rtps_trans_id'];

        $data_to_update = array('form_data.department_id' => $payment_params['DEPARTMENT_ID'], 'form_data.payment_params' => $payment_params);
        //pre ($rtps_trans_id);
        $data_to_update['form_data.convenience_charge'] = $data['convenience_charge'];

        if (isset($data['pfc_payment'])) {
            if ($this->slug != "user") {
                $data_to_update['form_data.service_charge'] = $data['service_charge'];
                $data_to_update['form_data.scanning_charge_per_page'] = $data['scanning_charge_per_page'];
                $data_to_update['form_data.printing_charge_per_page'] = $data['printing_charge_per_page'];
                $data_to_update['form_data.no_printing_page'] = $data['no_printing_page'];
                $data_to_update['form_data.no_scanning_page'] = $data['no_scanning_page'];
            }
        }
        $data_to_update['form_data.payment_status'] = 'INITIATED';

        //Update registration model
        $result = $this->employment_model->update_payment($rtps_trans_id, $data_to_update);
        //pre ($result);
        //pre($result->getMatchedCount());
        //UPDATE IF KIOSK PAYMENT
        if ($result->getMatchedCount()) {
            $this->load->model('iservices/admin/pfc_payment_history_model');
            $data_to_update_history['rtps_trans_id'] = $rtps_trans_id;
            $data_to_update_history['form_data']['department_id'] = $payment_params['DEPARTMENT_ID'];
            $data_to_update_history['form_data']['payment_params'] = $data['department_data'];
            $data_to_update_history['form_data']['convenience_charge'] = $data['convenience_charge'] || 'NA';
            if (isset($data['pfc_payment'])) {
                if ($this->slug != "user") {
                    $data_to_update_history['form_data']['service_charge'] = $data['service_charge'] || 'NA';
                    $data_to_update_history['form_data']['scanning_charge_per_page'] = $data['scanning_charge_per_page'] || 'NA';
                    $data_to_update_history['form_data']['printing_charge_per_page'] = $data['printing_charge_per_page'] || 'NA';
                    $data_to_update_history['form_data']['no_printing_page'] = $data['no_printing_page'] || 'NA';
                    $data_to_update_history['form_data']['no_scanning_page'] = $data['no_scanning_page'] || 'NA';
                }
            }
            $data_to_update_history['createdDtm'] = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
            //pre($data_to_update_history);
            $this->pfc_payment_history_model->insert($data_to_update_history);
            return true;
        } else {
            return false;
        } //End of if else
    } //End of payment_update()



}//End of Payment