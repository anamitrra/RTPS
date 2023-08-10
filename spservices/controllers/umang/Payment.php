<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Payment extends Frontend
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('applications_model');
    $this->load->model('umang/umang_model');
    $this->config->load('iservices/rtps_services');
    $this->load->library('AESrathi');
    $this->encryption_key=$this->config->item("agencyKey");
  
  } //End of __construct()
  
  public function request()
  {
    $output = $_POST;
    if ($output) {
        $response = ($output['data']);
        if (!empty($response)) {
          //decrypt the user input

          $aes = new AES($response, $this->encryption_key);
          $desc_response = $aes->decrypt();

          $response = json_decode($desc_response);
         
          $validate=$this->validateResponse($response);
          if (!$validate['status']) {
            $this->show_error($validate['mgs']);
            return;
            }
            $rtps_token=$response->rtps_token_number;
            //check if request is allready exist 
            $check_request=$this->umang_model->get_row(array('_id'=>new ObjectId($rtps_token) ));
            if(empty( $check_request)){
              $this->show_error("No records found");
              return;
            }else{
              $data_to_save = array(
                "ep_callback_url" => isset($response->return_url) ? $response->return_url : ""
             );
              $result = $this->umang_model->update_row(array('_id'=>new ObjectId($rtps_token)),$data_to_save);

              if ($result->getMatchedCount()) {
                $this->verify( $rtps_token);
              }else{
                $this->show_error("Unable to complete the request");
              }
             
            }
          }
      }else{
        $this->show_error("No data recieved");
      }
    
  }
  public function show_error($mgs){
    exit($mgs);
  }

  public function validateResponse($response)
  {
    $validation = true;
    $mgs="";
    if (empty($response->rtps_token_number)) {
      $mgs="RTPS Token Number is Required";
      $validation = false;
    }
    if (empty($response->return_url)) {
      $mgs="Umang return url is required";
      $validation = false;
    }
    return array("status"=> $validation,"mgs"=>$mgs );
  }

  public function callBack($obj_id){

    $filter = array("_id" => new ObjectId($obj_id));
   
    $application = $this->umang_model->get_row($filter);
  
    if(property_exists($application->form_data, 'pfc_payment_status')  && $application->form_data->pfc_payment_status === 'Y') {

      if( $application->service_data->service_id === "SCTZN"){
       
        $ref = modules::load('spservices/umang/Seniorcitizen');
      }elseif($application->service_data->service_id === "INC"){
        $ref = modules::load('spservices/umang/Incomecertificate');
      }else{
        $ref=false;
      }
      $stat = $ref->submit_to_backend($application);
      //push the application to backend
      if( $stat['status']){
        $psot_data=array(
          "rtps_token_number"=>$obj_id,
          "appl_ref_no"=>$application->service_data->appl_ref_no,
          "payment_reponse"=> $application->form_data->pfc_payment_response
        );
        // pre(($psot_data));
        $aes = new AES(json_encode($psot_data), $this->encryption_key);
        $enc_response = $aes->encrypt();
        if($application->ep_callback_url){
          
          $result = $this->umang_model->update_row(array("_id" => new ObjectId($obj_id)), array('service_data.appl_status'=>'submitted'));
          if ($result->getMatchedCount()) {
            $data['action']=$application->ep_callback_url;
            $data['data']=$enc_response;
            $this->load->view('iservices/retry',$data);
          }else{
            $this->show_error("Unable to update the app status");
          }
           
          }
      }else{
        $this->show_error("Unable to submit the application: ErrorCode:7676");
      }
      
    }else{
      echo "something went wrong. Try agin";die;
    }
  }
  // public function retry($obj_id){
  //   $filter = array("_id" => new ObjectId($obj_id));
  //   $application = $this->umang_model->get_app_ref($filter);
  //   $this->verify($application->service_data->appl_ref_no);
  // }
  public function verify($obj_id = null)
  {
    if ($obj_id) {
      $filter = array("_id" => new ObjectId($obj_id));
      $application = $this->umang_model->get_row($filter);
   //  pre( $application->form_data->pfc_payment_status);
      if (property_exists($application,'form_data') && property_exists($application->form_data, 'pfc_payment_status')  && $application->form_data->pfc_payment_status === 'Y') {
        $this->callBack($application->_id->{'$id'});
        return;
      }
     

      if (!empty($application->form_data->payment_params) && !empty($application->form_data->department_id)) {
        $res = $this->check_pfc_payment_status($application->form_data->department_id);
        if ($res === "submit") {
          $this->callBack( $application->_id->{'$id'});
        } elseif ($res === "get_cin") {
          $this->check_get_cin($application);
        } else {
          $this->initiate($application);
        }
      } else {
        $this->initiate($application);
      }
    } else {
      $this->show_error("No data recieved");
      return;
    }
  }


  public function initiate($dbRow)
  {
    if ($dbRow) {
      if($dbRow->service_data->service_id !== "SCTZN"){
        $this->show_error("Payment configuration has not setup yet");
        return;
      }
      $financial_year = get_financial_year();
      $rtps_account=$this->config->item("rtps_account");
      $rtps_convenience_fee=$this->config->item("rtps_convenience_fee");
      
      $uniqid = uniqid();
      $DEPARTMENT_ID = $uniqid . time();
      $data = array();
      $data['rtps_convenience_fee'] = $rtps_convenience_fee;
      $data['pfc_payment'] = false;
     
      $data['rtps_token_number'] = $dbRow->_id->{'$id'};
      $dept_code =  'ARI';
      $office_code =  'ARI000';
      
     
      $data['department_data']['DEPT_CODE']=$dept_code;
      $data['department_data']['OFFICE_CODE']= $office_code ;
      $data['department_data']['PERIOD']= 'O' ;
      $data['department_data']['CHALLAN_AMOUNT']= '0' ;
      $data['department_data']['MOBILE_NO']= $dbRow->form_data->mobile ;
      $data['department_data']['PARTY_NAME']= $dbRow->form_data->applicant_name ;
      $data['department_data']['PIN_NO']= $dbRow->form_data->pin_code ;
      $data['department_data']['ADDRESS1']= $dbRow->form_data->address_line1 ;
      
      $data['department_data']['REC_FIN_YEAR']=$financial_year['financial_year'];
      $data['department_data']['FROM_DATE']=$financial_year['from_date'];
      $data['department_data']['TO_DATE']= "31/03/2099";
      $data['department_data']['DEPARTMENT_ID']=$DEPARTMENT_ID;
     
      $data['department_data']['SUB_SYSTEM']="ARTPS-SP|".base_url('spservices/umang/payment_response/response');
      $data['department_data']['MULTITRANSFER']="Y";
      $data['department_data']["NON_TREASURY_PAYMENT_TYPE"]="02";
      $data['department_data']["TOTAL_NON_TREASURY_AMOUNT"]= $rtps_convenience_fee;
      $data['department_data']["AC1_AMOUNT"]=$rtps_convenience_fee;
      $data['department_data']["ACCOUNT1"]= $rtps_account;
      // pre( $data);
        $res = $this->update_pfc_payment_amount($data);
        if ($res) {
          $this->load->view('iservices/basundhara/payment', $data);
        } else {
          $this->show_error("Something went wrong. Please try again");
          return;
        }
      
    } else {
      $this->show_error("No data recieved");
      return;
    }
  }

 
  
 
  public function update_pfc_payment_amount($data)
  {

    $payment_params = $data['department_data'];
    $rtps_token_number = $data['rtps_token_number'];
    $data_to_update = array('form_data.department_id' => $payment_params['DEPARTMENT_ID'], 'form_data.payment_params' => $payment_params);
    if (isset($data['pfc_payment']) && $data['pfc_payment']) {
      $data_to_update['form_data.service_charge'] = $data['service_charge'];
      $data_to_update['form_data.scanning_charge_per_page'] = $data['scanning_charge_per_page'];
      $data_to_update['form_data.printing_charge_per_page'] = $data['printing_charge_per_page'];
      $data_to_update['form_data.no_printing_page'] = $data['no_printing_page'];
      $data_to_update['form_data.no_scanning_page'] = $data['no_scanning_page'];
    }
    $data_to_update['form_data.convenience_fee'] = $data['rtps_convenience_fee'];
    $result = $this->umang_model->update_row(array("_id" => new ObjectId($rtps_token_number)), $data_to_update);


    if ($result->getMatchedCount()) {
      $this->load->model('iservices/admin/pfc_payment_history_model');
      $data_to_update_history['rtps_token_number'] = $rtps_token_number;
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
        $this->show_error("Please verify payment status after 5 minutes");
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
