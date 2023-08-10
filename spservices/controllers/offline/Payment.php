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
    $this->load->model('offline/acknowledgement_model');
    $this->load->model('iservices/admin/users_model');
    $this->load->config('spconfig');
    if (!empty($this->session->userdata('role'))) {
      $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
    } else {
      $this->slug = "user";
    }
  } //End of __construct()

  private function my_transactions()
  {
    $user = $this->session->userdata();
    if (isset($user['role']) && !empty($user['role'])) {
      redirect(base_url('iservices/admin/my-transactions'));
    } else {
      redirect(base_url('iservices/transactions'));
    }
  }

  private function is_admin()
  {
    $user = $this->session->userdata();
    if (isset($user['role']) && !empty($user['role'])) {
      return true;
    } else {
      return false;
    }
  }
  public function verify($obj_id = null)
  {
    if ($obj_id) {
      $filter = array("_id" => new ObjectId($obj_id));
      $application = $this->acknowledgement_model->get_row($filter);
      if (property_exists($application, 'form_data.pfc_payment_status')  && $application->form_data->pfc_payment_status == 'Y') {
        if ($application->service_data->appl_status !== "submitted") {
          $this->application_submission($obj_id);
        }
        $this->my_transactions();
        return;
      }

      if (!empty($application->form_data->payment_params) && !empty($application->form_data->department_id)) {
        $res = $this->check_pfc_payment_status($application->form_data->department_id);
        if ($res === "submit") {
          $this->application_submission($obj_id);
        } elseif ($res === "get_cin") {
          $this->check_get_cin($application);
        } else {
          $this->initiate($obj_id);
        }
      } else {
        $this->initiate($obj_id);
      }
    } else {
      redirect(base_url('iservices/transactions'));
    }
  }

  public function application_submission($obj_id)
  {
    $ref = modules::load('spservices/offline/acknowledgement');
    $ref->form_submit($obj_id);
  }

  public function initiate($obj_id = null)
  {
    if ($obj_id) {
      $dbRow = $this->acknowledgement_model->get_by_doc_id($obj_id);
      if (property_exists($dbRow, 'pfc_payment_status') && $dbRow->form_data->pfc_payment_status == 'Y') {
        if ($dbRow->service_data->appl_status === "submitted") {
          $this->session->set_flashdata('message', 'Payment has been already made.');
          $this->my_transactions();
        } else {
          $this->application_submission($obj_id);
        }
      }
      if (!empty($dbRow->form_data->payment_params) && !empty($dbRow->form_data->department_id)) {
        $res = $this->check_pfc_payment_status($dbRow->form_data->department_id);
        if ($res === "submit") {
          $this->application_submission($obj_id);
        }
        if ($res === "get_cin") {
          $this->check_get_cin($dbRow);
        }
      }
      if ($this->is_admin()) {
        $filter = array("_id" => new ObjectId($obj_id));
        $this->acknowledgement_model->update_where($filter, array('service_data.appl_status' => 'payment_initiated'));
        $data = array("pageTitle" => "Make Payment");
        $data["dbrow"] = $dbRow;
        $data['service_charge'] = $this->config->item('service_charge');
        $data['objid'] = $obj_id;
        $data['no_printing_page'] = isset($data["dbrow"]->form_data->no_printing_page) ? $data["dbrow"]->form_data->no_printing_page :  '';
        $data['no_scanning_page'] = isset($data["dbrow"]->form_data->no_scanning_page) ? $data["dbrow"]->form_data->no_scanning_page :  '';
        $data['appl_ref_no'] = isset($data["dbrow"]->service_data->appl_ref_no) ? $data["dbrow"]->service_data->appl_ref_no :  '';
        $this->load->view('includes/frontend/header');
        $this->load->view('offline/kiosk_payment', $data);
        $this->load->view('includes/frontend/footer');
      } else {
        $this->citizen_payment($obj_id);
      }
    } else {
      $this->my_transactions();
    }
  }

  public function submit()
  {
    $this->form_validation->set_rules('objid', 'objid', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('appl_ref_no', 'appl_ref_no', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('no_printing_page', 'No printing page', 'trim|xss_clean|strip_tags|numeric');
    $this->form_validation->set_rules('no_scanning_page', 'No scanning page', 'trim|xss_clean|strip_tags|numeric');
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
      $this->pfcpayment();
    }
  }
  public function citizen_payment($obj_id)
  {
    $financial_year = get_financial_year();
    $rtps_account = $this->config->item("rtps_convenience_fee_account");
    $rtps_convenience_fee = $this->config->item("rtps_convenience_fee");
    $mobile = $this->session->userdata("mobile");
    $uniqid = uniqid();
    $DEPARTMENT_ID = $uniqid . time();
    $data = array();
    $data['rtps_convenience_fee'] = $rtps_convenience_fee;
    $data['pfc_payment'] = false;
    if ($obj_id) {
      $filter = array("_id" => new ObjectId($obj_id));
      $transaction_data = $this->acknowledgement_model->get_row($filter);
      if (empty($transaction_data)) {
        $this->my_transactions();
      }
      $data['appl_ref_no'] = $transaction_data->service_data->appl_ref_no;
      $dept_code = 'ARI';
      $office_code = "ARI000";
      $data['department_data'] = array(
        "DEPT_CODE" => $dept_code, //$user->dept_code,
        "OFFICE_CODE" => $office_code, //$user->office_code,
        "REC_FIN_YEAR" => $financial_year['financial_year'], //dynamic
        "HOA1" => "",
        "FROM_DATE" => $financial_year['from_date'],
        "TO_DATE" => "31/03/2099",
        "PERIOD" => "O", // O for ontimee payment
        "CHALLAN_AMOUNT" => "0",
        "DEPARTMENT_ID" => $DEPARTMENT_ID,
        "MOBILE_NO" => $mobile,
        "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/offline/payment_response/response'),
        "PARTY_NAME" => isset($transaction_data->form_data->applicant_name) ? $transaction_data->form_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
        "PIN_NO" => isset($transaction_data->form_data->pin_code) ? $transaction_data->form_data->pin_code : "781005",
        "ADDRESS1" => isset($transaction_data->form_data->village_town) ? $transaction_data->form_data->village_town : "NIC",
        "ADDRESS2" => isset($transaction_data->form_data->village_town) ? $transaction_data->form_data->village_town : "",
        "ADDRESS3" => isset($transaction_data->form_data->village_town) ? $transaction_data->form_data->village_town : "781005",
        "MULTITRANSFER" => "Y",
        "NON_TREASURY_PAYMENT_TYPE" => "02",
        "TOTAL_NON_TREASURY_AMOUNT" => $rtps_convenience_fee,
        "AC1_AMOUNT" => $rtps_convenience_fee,
        "ACCOUNT1" => $rtps_account

      );
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
  private function pfcpayment()
  {
    $financial_year = get_financial_year();
    $rtps_account = $this->config->item("rtps_convenience_fee_account");
    $rtps_convenience_fee = $this->config->item("rtps_convenience_fee");
    if ($this->slug === "CSC") {
      $account = $this->config->item('csc_account');
      $mobile = $this->session->userdata('user')->mobileno;
    } else {
      $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
      $account = $user->account1;
      $mobile = $user->mobile;
    }
    $uniqid = uniqid();
    $DEPARTMENT_ID = $uniqid . time();
    $data = array();
    $data['service_charge'] = $this->config->item('service_charge');
    $data['scanning_charge_per_page'] = $this->config->item('scanning_charge');
    $data['printing_charge_per_page'] = $this->config->item('printing_charge');
    $data['appl_ref_no'] = $this->input->post('appl_ref_no');
    $data['no_printing_page'] = $this->input->post('no_printing_page');
    $data['no_scanning_page'] = $this->input->post('no_scanning_page');
    $data['rtps_convenience_fee'] = $rtps_convenience_fee;
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
      if ($data['no_scanning_page']  > 0) {
        $total_amount += intval($data['no_scanning_page']) * floatval($data['scanning_charge_per_page']);
      }
    }
    if ($this->input->post('objid')) {
      $obj_id = $this->input->post('objid');
      $filter = array("_id" => new ObjectId($obj_id));
      $transaction_data = $this->acknowledgement_model->get_row($filter);
      if (empty($transaction_data)) {
        $this->my_transactions();
      }
      $data['appl_ref_no'] = $transaction_data->service_data->appl_ref_no;
      $dept_code = 'ARI';
      $office_code = "ARI000";

      $data['department_data'] = array(
        "DEPT_CODE" => $dept_code, //$user->dept_code,
        "OFFICE_CODE" => $office_code, //$user->office_code,
        "REC_FIN_YEAR" => $financial_year['financial_year'], //dynamic
        "HOA1" => "",
        "FROM_DATE" => $financial_year['from_date'],
        "TO_DATE" => "31/03/2099",
        "PERIOD" => "O", // O for ontimee payment
        "CHALLAN_AMOUNT" => "0",
        "DEPARTMENT_ID" => $DEPARTMENT_ID,
        "MOBILE_NO" => $mobile,
        "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/offline/payment_response/response'),
        "PARTY_NAME" => isset($transaction_data->form_data->applicant_name) ? $transaction_data->form_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
        "PIN_NO" => isset($transaction_data->form_data->pin_code) ? $transaction_data->form_data->pin_code : "781005",
        "ADDRESS1" => isset($transaction_data->form_data->village_town) ? $transaction_data->form_data->village_town : "NIC",
        "ADDRESS2" => isset($transaction_data->form_data->village_town) ? $transaction_data->form_data->village_town : "",
        "ADDRESS3" => isset($transaction_data->form_data->village_town) ? $transaction_data->form_data->village_town : "781005",
        "MULTITRANSFER" => "Y",
        "NON_TREASURY_PAYMENT_TYPE" => "02"
      );

      if ($account === $rtps_account) {
        $total_amount += floatval($rtps_convenience_fee);
        $data['department_data']["TOTAL_NON_TREASURY_AMOUNT"] = $total_amount;
        $data['department_data']["AC1_AMOUNT"] = $total_amount;
        $data['department_data']["ACCOUNT1"] = $account;
      } else {
        $data['department_data']["TOTAL_NON_TREASURY_AMOUNT"] = $total_amount + floatval($rtps_convenience_fee);
        $data['department_data']["AC1_AMOUNT"] = $total_amount;
        $data['department_data']["ACCOUNT1"] = $account;
        $data['department_data']["AC2_AMOUNT"] = $rtps_convenience_fee;
        $data['department_data']["ACCOUNT2"] = $rtps_account;
      }
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
  public function update_pfc_payment_amount($data)
  {

    $payment_params = $data['department_data'];
    $appl_ref_no = $data['appl_ref_no'];
    $data_to_update = array('form_data.department_id' => $payment_params['DEPARTMENT_ID'], 'form_data.payment_params' => $payment_params);
    if (isset($data['pfc_payment']) && $data['pfc_payment']) {
      $data_to_update['form_data.service_charge'] = $data['service_charge'];
      $data_to_update['form_data.scanning_charge_per_page'] = $data['scanning_charge_per_page'];
      $data_to_update['form_data.printing_charge_per_page'] = $data['printing_charge_per_page'];
      $data_to_update['form_data.no_printing_page'] = $data['no_printing_page'];
      $data_to_update['form_data.no_scanning_page'] = $data['no_scanning_page'];
    }
    $data_to_update['form_data.convenience_fee'] = $data['rtps_convenience_fee'];
    $result = $this->acknowledgement_model->update_row(array("service_data.appl_ref_no" => $appl_ref_no), $data_to_update);


    if ($result->getMatchedCount()) {
      $this->load->model('iservices/admin/pfc_payment_history_model');
      $data_to_update_history['rtps_trans_id'] = $appl_ref_no;
      $data_to_update_history['form_data']['department_id'] = $payment_params['DEPARTMENT_ID'];
      $data_to_update_history['form_data']['payment_params'] = $data['department_data'];
      if (isset($data['pfc_payment']) && $data['pfc_payment']) {
        $data_to_update_history['form_data']['service_charge'] = $data['service_charge'];
        $data_to_update_history['form_data']['scanning_charge_per_page'] = $data['scanning_charge_per_page'];
        $data_to_update_history['form_data']['printing_charge_per_page'] = $data['printing_charge_per_page'];
        $data_to_update_history['form_data']['no_printing_page'] = $data['no_printing_page'];
        $data_to_update_history['form_data']['no_scanning_page'] = $data['no_scanning_page'];
      }
      $data_to_update_history['form_data']['convenience_fee'] = $data['rtps_convenience_fee'];
      $data_to_update_history['createdDtm'] = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
      $this->pfc_payment_history_model->insert($data_to_update_history);

      return true;
    } else {
      return false;
    }
  }
  
  private function check_pfc_payment_status($DEPARTMENT_ID = null)
  {
    if ($DEPARTMENT_ID) {
      $min = $this->applications_model->checkPFCPaymentIntitateTimeNew($DEPARTMENT_ID);
      if ($min !== 'N' && $min < 6) {
        $this->session->set_flashdata('errmessage', 'Please verify payment status after 5 minutes');
        $this->my_transactions();
        return;
      }
      $ref = modules::load('spservices/offline/Payment_response');
      $grndata = $ref->checkgrn($DEPARTMENT_ID, false, true);
      if (!empty($grndata)) {
        if ($grndata['STATUS'] === 'Y') {
          return "submit";
        }
        $ar = array('N', 'A');
        if (!empty($grndata['GRN']) && !in_array($grndata['STATUS'], $ar)) {
          return "get_cin";
        }
      }
    }
    return false;
  }
  public function check_get_cin($application = null)
  {
    $DEPARTMENT_ID = $application->form_data->department_id;
    $data['department_data'] = array(
      "DEPARTMENT_ID" => $DEPARTMENT_ID,
      "OFFICE_CODE" => $application->form_data->payment_params->OFFICE_CODE,
      "AMOUNT" => $application->form_data->pfc_payment_response->AMOUNT,
      "ACTION_CODE" => "GETCIN",
      "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/offline/payment_response/cin_response'),
    );
    $this->load->view('offline/bank_cin', $data);
  }
}
