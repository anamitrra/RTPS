<?php

use PhpOffice\PhpSpreadsheet\Writer\Ods\Thumbnails;

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
class Basundahara_response extends Frontend
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('intermediator_model');
    $this->load->model('portals_model');
    $this->config->load('rtps_services');
    $this->load->library('AES');
    $this->load->helper('smsprovider');
  }

  public function create_response()
  {
    $output = $_POST;

    $data = array();
    if ($output) {
      $response = ($output['data']);

      $encryption_key = $this->config->item("encryption_key");

      if (!empty($response)) {
        //decrypt the user input

        $aes = new AES($response, $encryption_key);
        $desc_response = $aes->decrypt();

        $response = json_decode($desc_response);
        // pre( $response->response_data->payment_config);
        if (!isset($response->response_data)) {
          $this->show_error();
          return;
        }

        $status = isset($response->status) ? $response->status : '';

        $response = $response->response_data;
        if (!$this->validateResponse($response)) {
          return;
        }
        if (isset($response->portal_no)) {
          if (isset($response->amount) && !empty($response->amount)) {
            $app_amount = $response->amount;
          } else if (isset($response->payment_config) && $response->payment_config) {
            $app_amount = $response->payment_config->CHALLAN_AMOUNT;
          } else {
            $app_amount = "";
          }

          $data_to_update = array(
            //  "service_id"=>isset($response->service_id) ? $response->service_id : "",
            "app_ref_no" => isset($response->app_ref_no) ? $response->app_ref_no : "",
            "status" => isset($response->status) ? $response->status : "",
            "submission_date" => isset($response->submission_date) ? $response->submission_date : "",
            "submission_location" => isset($response->submission_location) ? $response->submission_location : "",
            "district" => isset($response->district) ? $response->district : "",
            "circle" => isset($response->circle) ? $response->circle : "",
            "payment_mode" => isset($response->payment_mode) ? $response->payment_mode : "",
            "payment_ref_no" => isset($response->payment_ref_no) ? $response->payment_ref_no : "",
            "payment_status" => isset($response->payment_status) ? $response->payment_status : "",
            "payment_date" => isset($response->payment_date) ? $response->payment_date : "",
            "amount" => $app_amount,
            // "portal_no" => isset($response->portal_no) ? $response->portal_no : "",
            "applicant_details" => isset($response->applicant_details) ? $response->applicant_details : "",
            "application_details" => isset($response->application_details) ? $response->application_details : "",
            "payment_config" => isset($response->payment_config) ? $response->payment_config : ""
          );
          $departmental_data = $this->portals_model->get_departmental_data_by_portal($response->service_id, $response->portal_no);
          // pre( $departmental_data);
          $intermediateID = $response->rtps_trans_id;
          //// TODO:  update should be base on two params rtps_id,user_id
          $result = $this->intermediator_model->add_param($response->rtps_trans_id, $data_to_update);
          if ($result->getMatchedCount()) {
            if (isset($response->status) && $response->status === "S") {
              // payment is required for all 
              redirect(base_url('iservices/basundhara/payment/') . $response->rtps_trans_id);

              // // check for payment if exist otherwise show ack
              // if (isset($response->payment_config) && $response->payment_config) {
              //   //payment is required
              //   redirect(base_url('iservices/basundhara/payment/') . $response->rtps_trans_id);
              // } else {

              //   $transaction_data = $this->intermediator_model->get_by_rtps_id($response->rtps_trans_id);
              //   if (property_exists($transaction_data, 'applied_by') && !empty($transaction_data->applied_by)) {
              //     redirect(base_url('iservices/basundhara/payment/') . $response->rtps_trans_id);
              //   } else {
              //     $this->send_submission_sms($response->rtps_trans_id,null);
              //     $data = array();
              //     $data['response'] = $response;
              //     $data['timeline_days'] = $departmental_data->timeline_days;
              //     $data['department_name'] = $departmental_data->department_name;
              //     $data['service_name'] = $departmental_data->service_name;
              //     $this->show_acknowledgment($data);
              //   }
              // }
            } else {
              $this->show_error();
            }
          } else {
            $this->show_error();
            return;
          }
        } else {
          $this->show_error();
          return;
        }
      } else {
        $this->show_error();
        return;
      }
    } else {
      $this->show_error();
      return;
    }
  }


  public function show_acknowledgment($data)
  {
    $this->load->view('includes/frontend/header');
    $this->load->view('noc_ack1', $data);
    $this->load->view('includes/frontend/footer');
  }
  public function show_error()
  {
    $this->load->view('includes/frontend/header');
    $this->load->view('error');
    $this->load->view('includes/frontend/footer');
  }

  public function validateResponse($response)
  {
    // service_id,app_ref_no,status,submission_date,applicant_details
    $validation = true;
    if (empty($response->rtps_trans_id)) {
      $validation = false;
    }
    if (empty($response->portal_no)) {
      $validation = false;
    }
    if (empty($response->service_id)) {
      $validation = false;
    }
    if (empty($response->app_ref_no)) {
      $validation = false;
    }
    if (empty($response->status)) {
      $validation = false;
    }
    if (empty($response->submission_date)) {
      $validation = false;
    }
    if (empty($response->applicant_details)) {
      $validation = false;
    }
    if ($validation) {
      return true;
    } else {
      $this->show_error();
    }
  }

  public function send_submission_sms($rtps_trans_id=null,$DEPARTMENT_ID=null){
    if($rtps_trans_id || $DEPARTMENT_ID){
      if($rtps_trans_id){
        $data=$this->intermediator_model->get_by_rtps_id($rtps_trans_id);
      }
      if($DEPARTMENT_ID){
         $data=$this->intermediator_model->get_application_details(array("department_id" => $DEPARTMENT_ID));
      }
      if( $data){
        $sms=array(
          "mobile"=>$data->mobile,
          "applicant_name"=>property_exists($data,"applicant_details") ? $data->applicant_details[0]->applicant_name : $data->mobile,
          "service_name"=>$data->service_name,
          "submission_date"=>$data->submission_date,
          "app_ref_no"=>$data->app_ref_no,
          "rtps_trans_id"=>$data->rtps_trans_id
        );
        sms_provider("submission",$sms);
      }
     
    }

  }

  public function push_payment_status($DEPARTMENT_ID)
  {
    if ($DEPARTMENT_ID) {
      $application_details = $this->intermediator_model->get_application_details(array("department_id" => $DEPARTMENT_ID));
      if ($application_details) {
        $encryption_key = $this->config->item("encryption_key");
        if (property_exists($application_details, 'pfc_payment_response') && !empty($application_details->pfc_payment_response)) {
          $am1 = isset($application_details->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $application_details->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
          $am2 = isset($application_details->payment_params->CHALLAN_AMOUNT) ? $application_details->payment_params->CHALLAN_AMOUNT : 0;
          $AMOUNT = $am1 + $am2;
          $params = array(
            'application_no' => $application_details->app_ref_no,
            'grn' => $application_details->pfc_payment_response->GRN,
            'bankcode' => $application_details->pfc_payment_response->BANKCODE,
            'bankcin' => $application_details->pfc_payment_response->BANKCIN,
            'prn' => $application_details->pfc_payment_response->PRN,
            'total_amount'=>$AMOUNT,
            'status' => $application_details->pfc_payment_response->STATUS,
            'partyname' => $application_details->pfc_payment_response->PARTYNAME,
            'taxid' => $application_details->pfc_payment_response->TAXID,
            'bankname' => $application_details->pfc_payment_response->BANKNAME,
            'transcompletiondatetime' => $application_details->pfc_payment_response->TRANSCOMPLETIONDATETIME
          );
          $svc_id=strval($application_details->service_id);
          $stat_services=["243","244","245","246","247","248","249"];
      
         
          
         
          $input_array = json_encode($params);
          $aes = new AES($input_array, $encryption_key);
          $enc = $aes->encrypt();
          // pre( $enc);
          //curl request
      
         
          if(in_array($svc_id,$stat_services)){
            $url="https://basundhara.assam.gov.in/rtpsmb/Epayment/updatePayment";
            $post_data = array('data' => $enc );
          }else{
            $url = $this->config->item('basundhara_push_payment_status_url');
            $post_data = array('data' => json_encode($enc) );
          }
          $curl = curl_init($url);
          // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
          $response = curl_exec($curl);
          curl_close($curl);
          if($response){
            $data_res=json_decode($response);
            if($data_res->responseType === 2 || $data_res->responseType === "2"){
              $result = $this->intermediator_model->add_param($application_details->rtps_trans_id, array(
                "payment_status_updated_on"=>true
              ));
            }
          }
        
        
        }
      }
    }
  }
  //payment related

  public function payment_response()
  {
    $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
    $response = $_POST;
    $this->intermediator_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_status" => $_POST['STATUS'], "pfc_payment_response" => $response));
    if ($_POST['STATUS'] === 'Y') {
      //check the grn for valid transactions
      if ($this->checkgrn($DEPARTMENT_ID, true) === $_POST['GRN']) {
        $this->push_payment_status($DEPARTMENT_ID);
        $this->send_submission_sms(null,$DEPARTMENT_ID);
        redirect(base_url('iservices/basundhara/get-acknowledgement?app_ref_no=') . $DEPARTMENT_ID);
      } else {
        //grn does not match Something went wrong
        echo "Something wrong in middle";
        $this->intermediator_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_status" => "P", "pfc_payment_response.STATUS" => 'P'));
        $this->show_error();
      }

      //  $this->show_vahan_acknowledgment($DEPARTMENT_ID);
    } else {
      $this->show_error();
    }
  }

  public function checkgrn($DEPARTMENT_ID = null, $check = false)
  { // TODO: need to check which are params to update
    $transaction_data = $this->intermediator_model->get_row(array('department_id' => $DEPARTMENT_ID));
    if ($DEPARTMENT_ID) {
      $OFFICE_CODE = $transaction_data->payment_params->OFFICE_CODE;
      $am1 = isset($transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
      $am2 = isset($transaction_data->payment_params->CHALLAN_AMOUNT) ? $transaction_data->payment_params->CHALLAN_AMOUNT : 0;
      $AMOUNT = $am1 + $am2;
      $string_field = "DEPARTMENT_ID=" . $DEPARTMENT_ID . "&OFFICE_CODE=" . $OFFICE_CODE . "&AMOUNT=" . $AMOUNT;
      $url = $this->config->item('egras_grn_cin_url');
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 3);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $string_field);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      // curl_setopt ($ch, CURLOPT_CAINFO, dirname(FILE)."/123.assam.gov.in.crt");
      curl_setopt($ch, CURLOPT_FAILONERROR, FALSE);
      curl_setopt($ch, CURLOPT_NOBODY, false);
      $result = curl_exec($ch);
      curl_close($ch);
      $res = explode("$", $result); //pre($res);
      if ($check) {
        return isset($res[4]) ? $res[4] : '';
      }
      if ($res) {
        $STATUS = isset($res[16]) ? $res[16] : '';
        $GRN = isset($res[4]) ? $res[4] : '';
        //  var_dump($STATUS);var_dump($GRN);die;
        //if($STATUS === "Y"){
        $this->intermediator_model->update_row(
          array('department_id' => $DEPARTMENT_ID),
          array(
            "pfc_payment_response.GRN" => $GRN,
            "pfc_payment_response.AMOUNT" => isset($res[6]) ? $res[6] : '',
            "pfc_payment_response.PARTYNAME" => isset($res[18]) ? $res[18] : '',
            "pfc_payment_response.TAXID" => isset($res[20]) ? $res[20] : '',
            "pfc_payment_response.DEPARTMENT_ID" => isset($res[2]) ? $res[2] : '',
            "pfc_payment_response.BANKNAME" => isset($res[22]) ? $res[22] : '',
            "pfc_payment_response.BANKCODE" => isset($res[8]) ? $res[8] : '',
            "pfc_payment_response.ENTRY_DATE" => isset($res[24]) ? $res[24] : '',
            "pfc_payment_response.STATUS" => $STATUS,
            "pfc_payment_response.PRN" => isset($res[12]) ? $res[12] : '',
            "pfc_payment_response.TRANSCOMPLETIONDATETIME" => isset($res[14]) ? $res[14] : '',
            "pfc_payment_response.BANKCIN" => isset($res[10]) ? $res[10] : '',
            'pfc_payment_status' => $STATUS
          )
        );
        //  }
      }
    }
    redirect(base_url('iservices'));
  }

  public function cin_response()
  {
    if (!empty($_POST)) {
      if (!empty($_POST['DEPARTMENT_ID'])) {
        $DEPARTMENT_ID = $_POST['DEPARTMENT_ID'];
        $STATUS = $_POST['STATUS'];
        $BANKCIN = $_POST['BANKCIN'];
        $this->intermediator_model->update_row(array('department_id' => $DEPARTMENT_ID), array(
          "pfc_payment_response.BANKCIN" => $BANKCIN,
          "pfc_payment_response.STATUS" => $STATUS,
          "pfc_payment_response.TAXID" => $_POST['TAXID'],
          "pfc_payment_response.PRN" => $_POST['PRN'],
          "pfc_payment_response.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
          'pfc_payment_status' => $STATUS
        ));
      }
    }

    redirect(base_url('iservices'));
  }
  public function acknowledgement()
  {

    if (empty($_GET['app_ref_no'])) {
      redirect(base_url("iservices"));
      // exit("Something Went wrong.");
    }
    if(!empty($this->session->userdata('role'))){
      $is_kiosk = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
    }else{
      $is_kiosk = false;
    }
    $app_ref_no = $_GET['app_ref_no'];
   
    if($is_kiosk && ($is_kiosk === "PFC")){
      $application_details = $this->intermediator_model->get_application_details(array("department_id" => $app_ref_no,"applied_by"=>new MongoDB\BSON\ObjectId($this->session->userdata("userId")->{'$id'}),"pfc_payment_status"=>"Y"));
    }else if($is_kiosk && ($is_kiosk === "CSC")){
      $application_details = $this->intermediator_model->get_application_details(array("department_id" => $app_ref_no,"applied_by"=>$this->session->userdata('userId'),"pfc_payment_status"=>"Y"));
    }else{
      $application_details = $this->intermediator_model->get_application_details(array("department_id" => $app_ref_no,'mobile'=>$this->session->userdata('mobile')));
      if(property_exists($application_details ,"applied_by")){
        if($application_details->pfc_payment_status !== "Y"){
          redirect('iservices/transactions');
          return;
        }
      }
    }

    if ($application_details->service_id){
      $departmental_data = $this->portals_model->get_departmental_data($application_details->service_id);
      if(!$is_kiosk){
        if(isset($departmental_data->payment_required) && $departmental_data->payment_required){
          if($application_details->pfc_payment_status !== "Y"){
            redirect('iservices');
            return;
          }
        }
      }
    }
    else
      redirect('iservices');
    $data = array();
    $data['timeline_days'] = $departmental_data->timeline_days;
    $data['department_name'] = $departmental_data->department_name;
    $data['service_name'] = $departmental_data->service_name;
    if (property_exists($application_details, 'applied_by') && !empty($application_details->applied_by)) {
      $data['back_to_dasboard'] = '<a href="' . base_url('iservices/admin/my-transactions') . '" class="btn btn-primary mb-2"  >Back To DASHBOARD</a>';
    } else {
      $data['back_to_dasboard'] = '<a href="' . base_url('iservices/transactions') . '" class="btn btn-primary mb-2"  >Back To DASHBOARD</a>';
    }

    $data['response'] = $application_details;
    // pre($data['response']->applicant_details);
    $this->load->view('includes/frontend/header');
    $this->load->view('noc_ack1', $data);
    $this->load->view('includes/frontend/footer');
  }

    public function saheb($DEPARTMENT_ID)
  {
    if ($DEPARTMENT_ID) {
      $application_details = $this->intermediator_model->get_application_details(array("department_id" => $DEPARTMENT_ID));
      // pre($application_details);
      if ($application_details) {
        $encryption_key = $this->config->item("encryption_key");
        if (property_exists($application_details, 'pfc_payment_response') && !empty($application_details->pfc_payment_response)) {
          $am1 = isset($application_details->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $application_details->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
          $am2 = isset($application_details->payment_params->CHALLAN_AMOUNT) ? $application_details->payment_params->CHALLAN_AMOUNT : 0;
          $AMOUNT = $am1 + $am2;
          $params = array(
            'application_no' => $application_details->app_ref_no,
            'grn' => $application_details->pfc_payment_response->GRN,
            'bankcode' => $application_details->pfc_payment_response->BANKCODE,
            'bankcin' => $application_details->pfc_payment_response->BANKCIN,
            'prn' => $application_details->pfc_payment_response->PRN,
            'total_amount'=>$AMOUNT,
            'status' => $application_details->pfc_payment_response->STATUS,
            'partyname' => $application_details->pfc_payment_response->PARTYNAME,
            'taxid' => $application_details->pfc_payment_response->TAXID,
            'bankname' => $application_details->pfc_payment_response->BANKNAME,
            'transcompletiondatetime' => $application_details->pfc_payment_response->TRANSCOMPLETIONDATETIME
          );

          $url = $this->config->item('basundhara_push_payment_status_url');
          $input_array = json_encode($params);
          $aes = new AES($input_array, $encryption_key);
          $enc = $aes->encrypt();
          // pre( $enc);
          //curl request
      
          $post_data = array('data' => json_encode($enc) );
          $curl = curl_init($url);
          // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
          $response = curl_exec($curl);
          curl_close($curl);
          if($response){
            $data_res=json_decode($response);
            pre( $data_res);
            if($data_res->responseType === 2 || $data_res->responseType === "2"){
              $result = $this->intermediator_model->add_param($application_details->rtps_trans_id, array(
                "payment_status_updated_on"=>true
              ));
            }
          }
        
        
        }
      }
    }
  }
  //update application  status
  public function update_application_status()
  {
    $encryption_key = $this->config->item("encryption_key");
    $url = "https://basundhara.assam.gov.in/rtpsdemo/rest/trackapplication";
    $mobile = "6000411027";
    $application_no = "RTPS/NSTR/2022/713";
    $data = array(
      "application_no" => $application_no,
      "mobile" => $mobile
    );
    $input_array = json_encode($data);

    $aes = new AES($input_array, $encryption_key);

    $enc = $aes->encrypt();
    // pre($enc);
    //curl request

    $post_data = array('data' => $enc);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 3);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // curl_setopt ($ch, CURLOPT_CAINFO, dirname(FILE)."/123.assam.gov.in.crt");
    curl_setopt($ch, CURLOPT_FAILONERROR, FALSE);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    $result = curl_exec($ch);
    curl_close($ch);
    pre($result);
  }
}
