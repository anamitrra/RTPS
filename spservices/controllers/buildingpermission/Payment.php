<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Payment extends Rtps
{
  private $serviceId = "PPBP";
  private $convenience_fee;
  private $convenience_fee_account;

  public function __construct()
  {
    parent::__construct();
    $this->load->model('applications_model');
    $this->load->model('buildingpermission/registration_model');
    $this->load->model('iservices/admin/users_model');
    $this->load->helper('payment');
    // $this->load->config('iservices/rtps_services');
    $this->load->config('spconfig');
    if (!empty($this->session->userdata('role'))) {
      $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
    } else {
      $this->slug = "user";
    }
    $this->convenience_fee = $this->config->item('rtps_convenience_fee');
    $this->convenience_fee_account = $this->config->item('rtps_convenience_fee_account');
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

  public function verify($obj_id = null)
  {
    // pre($obj_id);
    // die;
    if ($obj_id) {
      $filter = array("_id" => new ObjectId($obj_id));

      $application = $this->registration_model->get_row($filter);
      //  pre($application->form_data->pfc_payment_status);
      if (property_exists($application->form_data, 'form_data.pfc_payment_status')  && $application->form_data->pfc_payment_status == 'Y') {
        $this->my_transactions();
        return;
      }

      if (!empty($application->form_data->payment_params) && !empty($application->form_data->department_id)) {
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

  public function application_submission($obj_id, $service_id)
  {
    if ($service_id === "PPBP") {
      $ref = modules::load('spservices/buildingpermission/registration');
      $ref->submit_to_backend($obj_id);
    }
  }

  public function initiate($obj_id = null)
  {
    if ($obj_id) {
      $dbRow = $this->registration_model->get_by_doc_id($obj_id);
      if (property_exists($dbRow->form_data, 'pfc_payment_status') && $dbRow->form_data->pfc_payment_status == 'Y') {
        $this->application_submission($obj_id, $dbRow->service_data->service_id);
        //$this->session->set_flashdata('message', 'Payment already and hence the status has updated');
        // $this->my_transactions();
      }

      if (!empty($dbRow->form_data->payment_params) && !empty($dbRow->form_data->department_id)) {
        $res = $this->check_pfc_payment_status($dbRow->form_data->department_id);
        if ($res) {
          $this->application_submission($obj_id, $dbRow->service_data->service_id);
        }
      }

      $filter = array("_id" => new ObjectId($obj_id));
        $this->registration_model->update_where($filter, array('service_data.appl_status' => 'payment_initiated'));

      //From primary application for GMDA Service Cancellation Service Only
      $user_record_new = $this->registration_model->get_row($filter);
      if(!empty($user_record_new)){
        if(isset($user_record_new->form_data->old_permit_appl_ref_no)){
          if(!empty($user_record_new->form_data->old_permit_appl_ref_no)){
            $filter_new = array(
              "service_data.appl_ref_no" => $user_record_new->form_data->old_permit_appl_ref_no,
            );
              $data_to_update1=array(
                  'form_data.app_record_type'=>'reapply_done'
              );
              $this->registration_model->update_where($filter_new, $data_to_update1);
            // $this->registration_model->add_param($data_to_update1, $filter_new);
          }
        }
      }
      // pre($user_record_new);
      //End From primary application

        $data = array("pageTitle" => "Make Payment");
        $data["dbrow"] = $dbRow;

      if (!empty($this->session->userdata('role'))) {
        
        $data['service_charge'] = $this->config->item('service_charge');
        $data['objid'] = $obj_id;
        $data['no_printing_page'] = isset($data["dbrow"]->form_data->no_printing_page) ? $data["dbrow"]->form_data->no_printing_page :  '';
        $data['no_scanning_page'] = isset($data["dbrow"]->form_data->no_scanning_page) ? $data["dbrow"]->form_data->no_scanning_page :  '';
        $data['appl_ref_no'] = isset($data["dbrow"]->service_data->appl_ref_no) ? $data["dbrow"]->service_data->appl_ref_no :  '';
        $data['convenience_fee']=10;
        $this->load->view('includes/frontend/header');
        $this->load->view('buildingpermission/kiosk_payment', $data);
        $this->load->view('includes/frontend/footer');
      } else {
        $this->pfcpayment($obj_id);
      }
    } else {
      $this->session->set_flashdata('pay_message', 'No records found');
      $this->my_transactions();
    }
  } //End of index()

  public function submit()
  {

    $this->form_validation->set_rules('objid', 'objid', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('appl_ref_no', 'appl_ref_no', 'trim|required|xss_clean|strip_tags');
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
      // die;
      // $this->load->view('basundhara/payment',$data);
      $obj_id = $this->input->post('objid');
      $this->pfcpayment($obj_id);
    }
  }

  public function pfcpayment($obj_id = null)
  {
    $dbRow = $this->registration_model->get_by_doc_id($obj_id);
    if (count((array) $dbRow)) {
      $uniqid = uniqid();
      $DEPARTMENT_ID = $uniqid . time();
      $data = array();
      $data['appl_ref_no'] = $dbRow->service_data->appl_ref_no;
      $data['convenience_fee'] = $this->convenience_fee;
      // $dept_code = 'ARI';
      // $office_code = "ARI000";
      $data['pfc_payment'] = true;

      if ($this->slug === "user") {
        $data['department_data'] = array(
          "DEPT_CODE" => $this->config->item("dept_code"), //$user->dept_code,
          "OFFICE_CODE" => $this->config->item("office_code"), //$user->office_code,
          "REC_FIN_YEAR" => getFinYear(), //dynamic
          "HOA1" => "",
          "FROM_DATE" => firstDateFinYear(),
          "TO_DATE" => $this->config->item("to_date"),
          "PERIOD" => "O", // O for ontimee payment
          "CHALLAN_AMOUNT" => "0",
          "DEPARTMENT_ID" => $DEPARTMENT_ID,
          "MOBILE_NO" => $dbRow->form_data->mobile, //pfc no
          // "SUB_SYSTEM"=>"REV-SP|".base_url('iservices/admin/get/payment-response'),
          "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/buildingpermission/payment_response/response'),
          "PARTY_NAME" => isset($dbRow->form_data->applicant_name) ? $dbRow->form_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
          "PIN_NO" => isset($dbRow->form_data->pin_code) ? $dbRow->form_data->pin_code : "781005",
          "ADDRESS1" => isset($dbRow->form_data->village_town) ? $dbRow->form_data->village_town : "NIC",
          "ADDRESS2" => isset($dbRow->form_data->village_town) ? $transaction_data->form_data->village_town : "",
          "ADDRESS3" => isset($dbRow->form_data->village_town) ? $dbRow->form_data->village_town : "781005",
          "MULTITRANSFER" => "Y",
          "NON_TREASURY_PAYMENT_TYPE" => $this->config->item("non_trea_pmt_type"),
          "TOTAL_NON_TREASURY_AMOUNT" => $this->convenience_fee,
          "AC1_AMOUNT" => $this->convenience_fee,
          "ACCOUNT1" => $this->convenience_fee_account, //$user->account1,
        );
      } else {
        if ($this->slug === "CSC") {
          $account = $this->config->item('csc_account');
          $mobile = $this->session->userdata('user')->mobileno;
        } else {
          $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
          $account = $user->account1;
          $mobile = $user->mobile;
        }

        $data['service_charge'] = $this->config->item('service_charge');
        $data['scanning_charge_per_page'] = $this->config->item('scanning_charge');
        $data['printing_charge_per_page'] = $this->config->item('printing_charge');
        
        $data['appl_ref_no'] = $this->input->post('appl_ref_no');
        $data['no_printing_page'] = $this->input->post('no_printing_page');
        $data['no_scanning_page'] = $this->input->post('no_scanning_page');
        
        $total_amount = $data['service_charge'];

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
    
        if ($this->slug === "PFC") {
          if ($data['no_printing_page'] > 0) {
            // console.log("printing ::"+no_printing_page)
            $total_amount += intval($data['no_printing_page']) * floatval($data['printing_charge_per_page']);
          }
          if ($data['no_scanning_page']  > 0) {
            // console.log("printing ::"+no_scanning_page)
            $total_amount += intval($data['no_scanning_page']) * floatval($data['scanning_charge_per_page']);
          }
        }

        $data['department_data'] = array(
          "DEPT_CODE" => $this->config->item("dept_code"), //$user->dept_code,
          "OFFICE_CODE" => $this->config->item("office_code"), //$user->office_code,
          "REC_FIN_YEAR" => getFinYear(), //dynamic
          "HOA1" => "",
          "FROM_DATE" => firstDateFinYear(),
          "TO_DATE" => $this->config->item("to_date"),
          "PERIOD" => "O", // O for ontimee payment
          "CHALLAN_AMOUNT" => "0",
          "DEPARTMENT_ID" => $DEPARTMENT_ID,
          "MOBILE_NO" => $mobile, //pfc no
          // "SUB_SYSTEM"=>"REV-SP|".base_url('iservices/admin/get/payment-response'),
          "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/buildingpermission/payment_response/response'),
          "PARTY_NAME" => isset($dbRow->form_data->applicant_name) ? $dbRow->form_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
          "PIN_NO" => isset($transaction_data->form_data->pin_code) ? $dbRow->form_data->pin_code : "781005",
          "ADDRESS1" => isset($dbRow->form_data->village_town) ? $dbRow->form_data->village_town : "NIC",
          "ADDRESS2" => isset($dbRow->form_data->village_town) ? $dbRow->form_data->village_town : "",
          "ADDRESS3" => isset($dbRow->form_data->village_town) ? $dbRow->form_data->village_town : "781005",
          "MULTITRANSFER" => "Y",
          "NON_TREASURY_PAYMENT_TYPE" => $this->config->item("non_trea_pmt_type"),
          "TOTAL_NON_TREASURY_AMOUNT" => $total_amount + $this->convenience_fee,
          // "AC1_AMOUNT" => $total_amount,
          // "ACCOUNT1" => $account, //$user->account1,
          // "AC2_AMOUNT" => $this->convenience_fee,
          // "ACCOUNT2" => $this->convenience_fee_account,
        );

        if ($this->slug === "CSC") {
            $data['department_data']['AC1_AMOUNT'] = $total_amount;
            $data['department_data']['ACCOUNT1'] = $account;
            $data['department_data']['AC2_AMOUNT'] = $this->convenience_fee;
            $data['department_data']['ACCOUNT2'] = $this->convenience_fee_account;
        } else {
            $data['department_data']['AC1_AMOUNT'] = $total_amount + $this->convenience_fee;
            $data['department_data']['ACCOUNT1'] = $account;
        }
      }
      $res = $this->update_pfc_payment_amount($data);
      if ($res) {
        //pre($this->config->item('egras_url'));
        $this->load->view('iservices/basundhara/payment', $data);
      } else {
        $this->session->set_flashdata('pay_message', 'Error in payment status updating');
        $this->my_transactions();
      }
    } else {
      $this->session->set_flashdata('pay_message', 'No records found');
      $this->my_transactions();
    }
  }

  public function update_pfc_payment_amount($data)
  {
    $payment_params = $data['department_data'];
    $appl_ref_no = $data['appl_ref_no'];
    $data_to_update = array('form_data.department_id' => $payment_params['DEPARTMENT_ID'], 'form_data.payment_params' => $payment_params);
    if (isset($data['pfc_payment'])) {
      if ($this->slug != "user") {
        $data_to_update['form_data.service_charge'] = $data['service_charge'];
        $data_to_update['form_data.scanning_charge_per_page'] = $data['scanning_charge_per_page'];
        $data_to_update['form_data.printing_charge_per_page'] = $data['printing_charge_per_page'];
        $data_to_update['form_data.no_printing_page'] = $data['no_printing_page'];
        $data_to_update['form_data.no_scanning_page'] = $data['no_scanning_page'];
      }
    }
    $data_to_update['form_data.convenience_fee'] = $data['convenience_fee'];

    $result = $this->registration_model->update_payment_status($appl_ref_no, $data_to_update);

    if ($result->getMatchedCount()) {
      if (isset($data['pfc_payment'])) {
        $this->load->model('iservices/admin/pfc_payment_history_model');
        $data_to_update_history['rtps_trans_id'] = $appl_ref_no;
        $data_to_update_history['form_data']['department_id'] = $payment_params['DEPARTMENT_ID'];
        $data_to_update_history['form_data']['payment_params'] = $data['department_data'];
        $data_to_update_history['form_data']['convenience_fee'] = $data['convenience_fee'] || 'NA';
        if ($this->slug != "user") {
          $data_to_update_history['form_data']['service_charge'] = $data['service_charge'] || 'NA';
          $data_to_update_history['form_data']['scanning_charge_per_page'] = $data['scanning_charge_per_page'] || 'NA';
          $data_to_update_history['form_data']['printing_charge_per_page'] = $data['printing_charge_per_page'] || 'NA';
          $data_to_update_history['form_data']['no_printing_page'] = $data['no_printing_page'] || 'NA';
          $data_to_update_history['form_data']['no_scanning_page'] = $data['no_scanning_page'] || 'NA';
        }
        $data_to_update_history['createdDtm'] = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $this->pfc_payment_history_model->insert($data_to_update_history);
      }
      return true;
    } else {
      return false;
    }
  }

  private function check_pfc_payment_status($DEPARTMENT_ID = null)
  {
    if ($DEPARTMENT_ID) {
      $min = $this->applications_model->checkPFCPaymentIntitateTimeNew($DEPARTMENT_ID);
      //pre( $min);
      if ($min !== 'N' && $min < 6) {
        $this->session->set_flashdata('errmessage', 'Please verify payment status after 5 minutes');
        $this->my_transactions();
        return;
      }
      $ref = modules::load('spservices/buildingpermission/Payment_response');
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
          $this->session->set_flashdata('pay_message', 'Payment status is not updated. please retry after sometime.!');
          $this->my_transactions();
          return;
        }
      }
    }
    return false;
  }
}//End of Castecertificate