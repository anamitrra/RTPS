<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Payment extends Rtps {
 /**
  * __construct
  *
  * @return void
  */
  function __construct() {
    parent::__construct();
    $this->load->model('admin/users_model');
    $this->load->model('intermediator_model');
    $this->load->model('portals_model');
    $this->config->load('rtps_services');
    $this->mb=["243","244","245","246","247","248"];

  }

  private function is_admin(){
    $user=$this->session->userdata();
    if(isset($user['role']) && !empty($user['role'])){
      return true;
    }else{
      return false;
    }
  }
  private function my_transactions(){
    $user=$this->session->userdata();
    if(isset($user['role']) && !empty($user['role'])){
      redirect(base_url('iservices/admin/my-transactions'));
    }else{
      redirect(base_url('iservices/transactions'));
    }
  }
  private function check_payment_status($DEPARTMENT_ID=null){

   
    if($DEPARTMENT_ID){
      $min=$this->intermediator_model->checkPaymentIntitateTime($DEPARTMENT_ID);
      // pre( $min);
      if( $min !== 'N' && $min < 6){
        $this->session->set_flashdata('errmessage', 'Please verify payment status after 5 minutes');
        $this->my_transactions();
        return;
      }
      $grndata=$this->checkgrn($DEPARTMENT_ID);
      if(!empty($grndata)){
          if($grndata['STATUS'] === 'Y'){
            $this->my_transactions();
            return;
          }
          $ar=array('N','A');
          if(!empty($grndata['GRN']) && !in_array($grndata['STATUS'] , $ar) ){
            $this->my_transactions();
            return;
          }
      }
      
      // $ref=modules::load('iservices/basundhara/Basundahara_response');
      // $ref->checkgrn($DEPARTMENT_ID);
    }

  }
  public function bpayment_availibility($app_ref_no){

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://basundhara.assam.gov.in/rtpsmb/Ekhajana/checkRePaymentAPI',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('application_no' => $app_ref_no)
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if($response){
      $res=json_decode($response,true);
      if($res['responseType'] == 2){
        if($res['allowPayment'] === "y"){
          return true;
        }else{
          $this->session->set_flashdata('errmessage', $res['message']);
          $this->my_transactions();
          return;
        }
      }else{
        $this->session->set_flashdata('errmessage', $res['message']);
        $this->my_transactions();
        return;
      }
    }else{
      $this->session->set_flashdata('errmessage', "something went wrong");
      $this->my_transactions();
      return;
    }

    // $this->session->set_flashdata('errmessage', 'Invalid document type');
    // $this->my_transactions();
  }
  public function payment($rtps_trans_id){ 
    $financial_year=get_financial_year();
    $app_status=false;
    if(empty($rtps_trans_id)){
      $this->my_transactions();
    }

    $transaction_data=$this->intermediator_model->get_by_rtps_id($rtps_trans_id); //pre($transaction_data);
    if( in_array($transaction_data->service_id,$this->mb) ){
      $this->my_transactions();
      return false;
    }
    if(empty($transaction_data)){
      $this->my_transactions();
    }
    
    if (property_exists($transaction_data,"pfc_payment_status")  ){
      $app_status="S";
      if($transaction_data->pfc_payment_status === "Y"){
        $this->my_transactions();
      }
      
       
    }else{
      if($transaction_data->status !== "S"){
        $this->my_transactions();
      }
    }
    
    // if(!empty($transaction_data->pfc_payment_status)){
    //   $this->my_transactions();
    // }
    $guidelines=$this->portals_model->get_guidelines($transaction_data->service_id);
   
    if(empty($guidelines)){
      return;
      exit();
    }
    $dept_code=$guidelines->dept_code;
    $office_code=$guidelines->office_code;
    if(property_exists($transaction_data,"department_id") && property_exists($transaction_data,"payment_params") ){
      $this->check_payment_status($transaction_data->department_id);
    }

    //check for e-khajana services

    if($transaction_data->service_id === "249" || $transaction_data->service_id === 249){
      $this->bpayment_availibility($transaction_data->app_ref_no);
    }

    if(property_exists($transaction_data,'applied_by') && !empty($transaction_data->applied_by)){
      if($this->is_admin()){
        $data['ApplicationStatus']=$transaction_data->status;
        $data['service_charge']=$this->config->item('service_charge');
        $data['scanning_charge']=$this->config->item('scanning_charge');
        $data['printing_charge']=$this->config->item('printing_charge');
        $data['rtps_trans_id']=$rtps_trans_id;
        $this->load->view('includes/header');
        $this->load->view('basundhara/pfc_payment',$data);
        $this->load->view('includes/footer');
        
      }
    }else{
      $rtps_account=$this->config->item("rtps_account");
      $rtps_convenience_fee=$this->config->item("rtps_convenience_fee");
      //for citizen
      $uniqid=uniqid();
      $DEPARTMENT_ID=$uniqid.time();
      $data['rtps_trans_id']=$rtps_trans_id;
      $data['rtps_convenience_fee']=$rtps_convenience_fee;
      if(empty($transaction_data->payment_config)){
        $data['department_data']=array(
          "DEPT_CODE"=>$dept_code,//$user->dept_code,
          "OFFICE_CODE"=>$office_code,//$user->office_code,
          "REC_FIN_YEAR"=> $financial_year['financial_year'],//dynamic
          "HOA1"=>"",
          "FROM_DATE"=> $financial_year['from_date'],
          "TO_DATE"=>"31/03/2099",
          "PERIOD"=>"O",// O for ontimee payment
          "CHALLAN_AMOUNT"=>"0",
          "DEPARTMENT_ID"=>$DEPARTMENT_ID,
          "MOBILE_NO"=>$this->session->userdata('mobile'),
          "SUB_SYSTEM"=>"ARTPS-SP|".base_url('iservices/basundhara/get/payment-response'),
          "PARTY_NAME"=>isset($transaction_data->applicant_name) ? $transaction_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
          "PIN_NO"=>isset($transaction_data->pin_code) ? $transaction_data->pin_code : "781005",
          "ADDRESS1"=>isset($transaction_data->address1) ? $transaction_data->address1 : "NIC",
          "ADDRESS2"=>isset($transaction_data->address2) ? $transaction_data->address2 : "",
          "ADDRESS3"=>isset($transaction_data->address3) ? $transaction_data->address3 : "781005",
          "MULTITRANSFER"=>"Y",
          "NON_TREASURY_PAYMENT_TYPE"=>"02",
          "TOTAL_NON_TREASURY_AMOUNT"=> $rtps_convenience_fee,
          "AC1_AMOUNT"=>$rtps_convenience_fee,
          "ACCOUNT1"=> $rtps_account
        );
      }else{
        $applicant_details=is_array($transaction_data->applicant_details)?$transaction_data->applicant_details[0]:false;
  
        $data['department_data']=(array)$transaction_data->payment_config;
        if(array_key_exists("AC1_AMOUNT",$data['department_data']) || array_key_exists("AC2_AMOUNT",$data['department_data'])  ){
          exit("AC1_AMOUNT & AC2_AMOUNT are not allowed");
        }
        $data['department_data']['REC_FIN_YEAR']=$financial_year['financial_year'];
        $data['department_data']['FROM_DATE']=$financial_year['from_date'];

        $data['department_data']['DEPARTMENT_ID']=$DEPARTMENT_ID;
        $data['department_data']['PARTY_NAME']= $applicant_details ? $applicant_details->applicant_name : "";
        $data['department_data']['PIN_NO']=$applicant_details ? $applicant_details->pin_code : "";
        $data['department_data']['ADDRESS1']=$applicant_details ? $applicant_details->address_line_1 :"";
        $data['department_data']['ADDRESS2']=$applicant_details ? $applicant_details->address_line_2 : "";
        $data['department_data']['ADDRESS3']=$applicant_details ? $applicant_details->address_line_2 : "";
  
        $data['department_data']['SUB_SYSTEM']="ARTPS-SP|".base_url('iservices/basundhara/get/payment-response');
        $data['department_data']['MULTITRANSFER']="Y";
        $data['department_data']["NON_TREASURY_PAYMENT_TYPE"]="03";
        $data['department_data']["AC1_AMOUNT"]=$rtps_convenience_fee;
        $data['department_data']["ACCOUNT1"]= $rtps_account;
        $t_n_t_amount=0;
        if(!empty($data['department_data']["AC1_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC1_AMOUNT"];
        }
        if(!empty($data['department_data']["AC2_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC2_AMOUNT"];
        }
        if(!empty($data['department_data']["AC3_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC3_AMOUNT"];
        }
        if(!empty($data['department_data']["AC4_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC4_AMOUNT"];
        }
        if(!empty($data['department_data']["AC5_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC5_AMOUNT"];
        }
        if(!empty($data['department_data']["AC6_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC7_AMOUNT"];
        }
        if(!empty($data['department_data']["AC8_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC8_AMOUNT"];
        }
        $data['department_data']["TOTAL_NON_TREASURY_AMOUNT"]= $t_n_t_amount;
      }

      $res=$this->update_pfc_payment_amount($data);
      if($res){
        $this->load->view('basundhara/payment',$data);
      }else{
        $this->my_transactions();
      }
    }
    
  }

  public function pfcpayment(){
  
    $financial_year=get_financial_year();
    $rtps_account=$this->config->item("rtps_account");
    $rtps_convenience_fee=$this->config->item("rtps_convenience_fee");
    
    if($this->session->userdata('role') === "csc"){
      $account=$this->config->item('csc_account');
      $mobile=$this->session->userdata('user')->mobileno;
    }else{
      $user=$this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
      $account=$user->account1;
      $mobile=$user->mobile;
    }
    
    $uniqid=uniqid();
    $DEPARTMENT_ID=$uniqid.time();
    $data=array();
    $data['service_charge']=$this->config->item('service_charge');
    $data['scanning_charge_per_page']=$this->config->item('scanning_charge');
    $data['printing_charge_per_page']=$this->config->item('printing_charge');
    $data['rtps_trans_id']=$this->input->post('rtps_trans_id');
    $data['no_printing_page']=$this->input->post('no_printing_page');
    $data['no_scanning_page']=$this->input->post('no_scanning_page');
    $data['pfc_payment']=true;
    $data['rtps_convenience_fee']=$rtps_convenience_fee;
    $total_amount=$data['service_charge'];
    if(!empty($data['no_printing_page']) &&( intval($data['no_printing_page']) < 0 || !is_numeric($data['no_printing_page'])) ){
      return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode(array("status"=>false,"message"=>"Number of page can not be a negative value")));
    }
    if(!empty($data['no_scanning_page']) && intval($data['no_scanning_page']) < 0 || !is_numeric($data['no_printing_page'])){
      return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode(array("status"=>false,"message"=>"Number of page can not be a negative value")));
    }
     if(isset($this->session->userdata('role')->slug) && $this->session->userdata('role')->slug === "PFC"){

          if($data['no_printing_page'] > 0 ){
          // console.log("printing ::"+no_printing_page)
            $total_amount +=intval($data['no_printing_page'])*floatval($data['printing_charge_per_page']);
          }
          if($data['no_scanning_page']  > 0 ){
              // console.log("printing ::"+no_scanning_page)
              $total_amount +=intval($data['no_scanning_page'])*floatval($data['scanning_charge_per_page']);
          }
     }
     
    if($data['rtps_trans_id']){
      $transaction_data=$this->intermediator_model->get_by_rtps_id($data['rtps_trans_id']); //pre($transaction_data);
      if( in_array($transaction_data->service_id,$this->mb) ){
        $this->my_transactions();
        return false;
      }
      if(empty($transaction_data)){
        $this->my_transactions();
      }

      if(!empty($transaction_data->payment_config)){
        $data['department_data']=(array)$transaction_data->payment_config;
        if(array_key_exists("AC1_AMOUNT",$data['department_data']) || array_key_exists("AC2_AMOUNT",$data['department_data'])  ){
          exit("AC1_AMOUNT & AC2_AMOUNT are not allowed");
        }
        $data['department_data']['REC_FIN_YEAR']=$financial_year['financial_year'];
        $data['department_data']['FROM_DATE']=$financial_year['from_date'];
        $data['department_data']['PARTY_NAME']=isset($transaction_data->applicant_name) ? $transaction_data->applicant_name : "RTPS TEAM";
        $data['department_data']['PIN_NO']=isset($transaction_data->pin_code) ? $transaction_data->pin_code : "781005";
        $data['department_data']['ADDRESS1']=isset($transaction_data->address1) ? $transaction_data->address1 : "NIC";
        $data['department_data']['ADDRESS2']=isset($transaction_data->address2) ? $transaction_data->address2 : "";
        $data['department_data']['ADDRESS3']=isset($transaction_data->address3) ? $transaction_data->address3 : "781005";

        $data['department_data']['DEPARTMENT_ID']=$DEPARTMENT_ID;
        $data['department_data']['SUB_SYSTEM']="ARTPS-SP|".base_url('iservices/basundhara/get/payment-response');
        $data['department_data']['MULTITRANSFER']="Y";
        $data['department_data']["NON_TREASURY_PAYMENT_TYPE"]="03";
        if($account === $rtps_account){
          $total_amount += floatval($rtps_convenience_fee);
          $data['department_data']["AC1_AMOUNT"]=$total_amount;
          $data['department_data']["ACCOUNT1"]=$account;
        }else{
          $data['department_data']["AC1_AMOUNT"]=$total_amount;
          $data['department_data']["ACCOUNT1"]=$account;
          $data['department_data']["AC2_AMOUNT"]=$rtps_convenience_fee;
          $data['department_data']["ACCOUNT2"]= $rtps_account;
        }



        $t_n_t_amount=0;
        if(!empty($data['department_data']["AC1_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC1_AMOUNT"];
        }
        if(!empty($data['department_data']["AC2_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC2_AMOUNT"];
        }
        if(!empty($data['department_data']["AC3_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC3_AMOUNT"];
        }
        if(!empty($data['department_data']["AC4_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC4_AMOUNT"];
        }
        if(!empty($data['department_data']["AC5_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC5_AMOUNT"];
        }
        if(!empty($data['department_data']["AC6_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC7_AMOUNT"];
        }
        if(!empty($data['department_data']["AC8_AMOUNT"])){
          $t_n_t_amount +=$data['department_data']["AC8_AMOUNT"];
        }
        $data['department_data']["TOTAL_NON_TREASURY_AMOUNT"]= $t_n_t_amount;

      }else{
        $guidelines=$this->portals_model->get_guidelines($transaction_data->service_id);
          if(empty($guidelines)){
            return;
            exit();
          }
          $dept_code=$guidelines->dept_code;
          $office_code=$guidelines->office_code;
          
        $data['department_data']=array(
          "DEPT_CODE"=>$dept_code,//$user->dept_code,
          "OFFICE_CODE"=>$office_code,//$user->office_code,
          "REC_FIN_YEAR"=>$financial_year['financial_year'],//dynamic
          "HOA1"=>"",
          "FROM_DATE"=>$financial_year['from_date'],
          "TO_DATE"=>"31/03/2099",
          "PERIOD"=>"O",// O for ontimee payment
          "CHALLAN_AMOUNT"=>"0",
          "DEPARTMENT_ID"=>$DEPARTMENT_ID,
          "MOBILE_NO"=>$mobile,//pfc no
          // "SUB_SYSTEM"=>"ARTPS-SP|".base_url('iservices/admin/get/payment-response'),
          "SUB_SYSTEM"=>"ARTPS-SP|".base_url('iservices/basundhara/get/payment-response'),
          "PARTY_NAME"=>isset($transaction_data->applicant_name) ? $transaction_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
          "PIN_NO"=>isset($transaction_data->pin_code) ? $transaction_data->pin_code : "781005",
          "ADDRESS1"=>isset($transaction_data->address1) ? $transaction_data->address1 : "NIC",
          "ADDRESS2"=>isset($transaction_data->address2) ? $transaction_data->address2 : "",
          "ADDRESS3"=>isset($transaction_data->address3) ? $transaction_data->address3 : "781005",
          "MULTITRANSFER"=>"Y",
          "NON_TREASURY_PAYMENT_TYPE"=>"02"
        );

        if($account === $rtps_account){
          $total_amount += floatval($rtps_convenience_fee);
          $data['department_data']["TOTAL_NON_TREASURY_AMOUNT"]= $total_amount;
          $data['department_data']["AC1_AMOUNT"]=$total_amount;
          $data['department_data']["ACCOUNT1"]=$account;
        }else{
         
          $data['department_data']["TOTAL_NON_TREASURY_AMOUNT"]=$total_amount + floatval($rtps_convenience_fee);
          $data['department_data']["AC1_AMOUNT"]=$total_amount;
          $data['department_data']["ACCOUNT1"]=$account;
          $data['department_data']["AC2_AMOUNT"]=$rtps_convenience_fee;
          $data['department_data']["ACCOUNT2"]= $rtps_account;
        }

      }
      //pre($data);
      $res=$this->update_pfc_payment_amount($data);
     
      if($res){
        $this->load->view('basundhara/payment',$data);
      }else{
        $this->my_transactions();
      }

    }else{
      $this->my_transactions();
      return;
    }
   

  }
  public function update_pfc_payment_amount($data){
      $payment_params=$data['department_data'];
      $rtps_trans_id=$data['rtps_trans_id'];
      $data_to_update=array('department_id'=>$payment_params['DEPARTMENT_ID'],
                            'payment_params'=>$payment_params);
      if(isset($data['pfc_payment'])){
        $data_to_update['service_charge']=$data['service_charge'];
        $data_to_update['scanning_charge_per_page']=$data['scanning_charge_per_page'];
        $data_to_update['printing_charge_per_page']=$data['printing_charge_per_page'];
        $data_to_update['no_printing_page']=$data['no_printing_page'];
        $data_to_update['no_scanning_page']=$data['no_scanning_page'];
      }
      $data_to_update['rtps_convenience_fee']=$data['rtps_convenience_fee'];
     
      $result=$this->intermediator_model->update_payment_status($rtps_trans_id,$data_to_update);
      // pre($result->getMatchedCount());
         if($result->getMatchedCount()){
           $this->load->model('admin/pfc_payment_history_model');
           $data_to_update['rtps_trans_id']=$rtps_trans_id;
           $data_to_update['createdDtm']=new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
           $this->pfc_payment_history_model->insert($data_to_update);
          return true;
         }else {
          return false;
         }
  }

  public function retry_payment($rtps_trans_id){
    
    if(empty($rtps_trans_id)){
      $this->my_transactions();
    }

    $transaction_data=$this->intermediator_model->get_by_rtps_id($rtps_trans_id);
    if(empty($transaction_data)){
      $this->my_transactions();
    }
    if($transaction_data->status !== "S"){
      $this->my_transactions();
    }

    //for failed only
    if($transaction_data->pfc_payment_status !== "N"){
      $this->my_transactions();
    }
    if(empty($transaction_data->payment_params)){
      $this->my_transactions();
    }
    // $user=$this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
  //  pre($transaction_data->payment_params);
    $uniqid=uniqid();
    $DEPARTMENT_ID=$uniqid.time();

    // $data['service_charge']=$this->config->item('service_charge');
    // $data['scanning_charge']=$this->config->item('scanning_charge');
    // $data['printing_charge']=$this->config->item('printing_charge');
    $data['rtps_trans_id']=$rtps_trans_id;
    $data['department_data']=(array)$transaction_data->payment_params;
    $data['department_data']['DEPARTMENT_ID']=$DEPARTMENT_ID;
    $res=$this->update_pfc_payment_amount($data);
    if($res){
      $this->load->view('basundhara/payment',$data);
    }else{
      $this->my_transactions();
    }
    
  }


  public function checkgrn($DEPARTMENT_ID = null)
  {
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
      $res = explode("$", $result); 
    
      if ($res) {
        $STATUS = isset($res[16]) ? $res[16] : '';
        $GRN = isset($res[4]) ? $res[4] : '';
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
        return array(
          'GRN'=>$GRN,
          'STATUS'=>$STATUS
        );
      }
    }else{
      return false;
    }
   
  }


}
