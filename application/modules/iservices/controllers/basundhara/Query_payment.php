<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Query_payment extends Rtps {
 /**
  * __construct
  *
  * @return void
  */
  function __construct() {
    parent::__construct();
    $this->load->model('admin/users_model');
    $this->load->model('intermediator_model');
    
    $this->load->model('spservices/applications_model');
    $this->load->model('portals_model');
    $this->config->load('rtps_services');

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
      $min=$this->applications_model->checkPaymentIntitateTime($DEPARTMENT_ID);
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
  public function payment($rtps_trans_id){ 
    $financial_year=get_financial_year();
    $app_status=false;
    if(empty($rtps_trans_id)){
      $this->my_transactions();
    }

    $transaction_data=$this->intermediator_model->get_by_rtps_id($rtps_trans_id);// pre($transaction_data);
    if(empty($transaction_data)){
      $this->my_transactions();
    }
    
    if (property_exists($transaction_data,"query_payment_status")  ){
      
      if($transaction_data->query_payment_status === "Y"){
        $this->my_transactions();
      }
      
       
    }else{
      if($transaction_data->status !== "S"){
        $this->my_transactions();
      }
    }

    if (!property_exists($transaction_data,"query_payment_config") && $transaction_data->query_status !=="PQ" ){
        $this->my_transactions();
      }
  
    if(property_exists($transaction_data,"query_department_id") && property_exists($transaction_data,"query_payment_params") ){
      $this->check_payment_status($transaction_data->query_department_id);
    }

    //check check e-Khazana payment status
   
    if($transaction_data->service_id === 249 || $transaction_data->service_id === "249"){
      // $this->check_e_Khazana_payment_status($transaction_data->app_ref_no);
      $this->bpayment_availibility($transaction_data->app_ref_no);
    }
  
    //for citizen
    $uniqid=uniqid();
    $DEPARTMENT_ID=$uniqid.time();
    $data['rtps_trans_id']=$rtps_trans_id;
    if(empty($transaction_data->query_payment_config)){
      $this->my_transactions();
    }
    // $applicant_details=is_array($transaction_data->applicant_details)?$transaction_data->applicant_details[0]:false;

    $data['department_data']=(array)$transaction_data->query_payment_config;
    $data['department_data']['DEPARTMENT_ID']=$DEPARTMENT_ID;
    $data['department_data']['REC_FIN_YEAR']=$financial_year['financial_year'];
    $data['department_data']['FROM_DATE']=$financial_year['from_date'];
    
    $data['department_data']['SUB_SYSTEM']="ARTPS-SP|".base_url('iservices/basundhara/query_payment_response/response');
   
    // $data['department_data']['PERIOD']="O";
    // pre($data);
    $res=$this->update_pfc_payment_amount($data);
    if($res){
      $this->load->view('basundhara/payment',$data);
    }else{
      $this->my_transactions();
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
  }
  private function check_e_Khazana_payment_status($app_ref_no){
    // die("check");
       $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://basundhara.assam.gov.in/rtpsmb/Ekhajana/checkEkhajanaPaymentStatus',
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
        if($res['flag'] === "Y"){
          // die($res['msg']);
          return true;
        }else{
          $this->session->set_flashdata('errmessage', $res['msg']);
          $this->my_transactions();
          return;
        }
      }else{
        return false;
        exit("something went wrong");
      }
  }

  public function update_pfc_payment_amount($data){
      $payment_params=$data['department_data'];
      $rtps_trans_id=$data['rtps_trans_id'];
      $data_to_update=array('query_department_id'=>$payment_params['DEPARTMENT_ID'],
                            'query_payment_params'=>$payment_params);
    
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

 


  public function checkgrn($DEPARTMENT_ID = null)
  {
    $transaction_data = $this->intermediator_model->get_row(array('query_department_id' => $DEPARTMENT_ID));
    if ($DEPARTMENT_ID) {
      $OFFICE_CODE = $transaction_data->query_payment_params->OFFICE_CODE;
      $am1 = isset($transaction_data->query_payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $transaction_data->query_payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
      $am2 = isset($transaction_data->query_payment_params->CHALLAN_AMOUNT) ? $transaction_data->query_payment_params->CHALLAN_AMOUNT : 0;
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
          array('query_department_id' => $DEPARTMENT_ID),
          array(
            "query_payment_response.GRN" => $GRN,
            "query_payment_response.AMOUNT" => isset($res[6]) ? $res[6] : '',
            "query_payment_response.PARTYNAME" => isset($res[18]) ? $res[18] : '',
            "query_payment_response.TAXID" => isset($res[20]) ? $res[20] : '',
            "query_payment_response.DEPARTMENT_ID" => isset($res[2]) ? $res[2] : '',
            "query_payment_response.BANKNAME" => isset($res[22]) ? $res[22] : '',
            "query_payment_response.BANKCODE" => isset($res[8]) ? $res[8] : '',
            "query_payment_response.ENTRY_DATE" => isset($res[24]) ? $res[24] : '',
            "query_payment_response.STATUS" => $STATUS,
            "query_payment_response.PRN" => isset($res[12]) ? $res[12] : '',
            "query_payment_response.TRANSCOMPLETIONDATETIME" => isset($res[14]) ? $res[14] : '',
            "query_payment_response.BANKCIN" => isset($res[10]) ? $res[10] : '',
            'query_payment_status' => $STATUS
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
