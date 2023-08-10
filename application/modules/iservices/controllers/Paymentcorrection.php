<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Paymentcorrection extends Frontend {
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
  public function details($rtps_trans_id){
    $transaction_data=$this->intermediator_model->get_by_rtps_id($rtps_trans_id); //pre($transaction_data);
    if(property_exists($transaction_data,"has_payment_issue") && $transaction_data->has_payment_issue){
        if(empty($transaction_data)){
            $this->my_transactions();
          }
          $data=array();
          $data['app_ref_no']=$transaction_data->app_ref_no;
          $data['rtps_trans_id']=$transaction_data->rtps_trans_id;
          $data['amount']=$transaction_data->amount;
          // $payble_amount=$transaction_data->amount;
          // if(property_exists($transaction_data,"applied_by")){
          //     $data['applied_by']="KIOSK";
          //     $data['no_printing_page']=$transaction_data->no_printing_page;
          //     $data['no_scanning_page']=$transaction_data->no_scanning_page;
          //     $data['service_charge']=$transaction_data->service_charge;
          //     $data['printing_charge_per_page']=$transaction_data->printing_charge_per_page;
          //     $data['scanning_charge_per_page']=$transaction_data->scanning_charge_per_page;
          //     $payble_amount += $transaction_data->service_charge;
          //     if($transaction_data->kiosk_type !== "CSC"){
          //         $payble_amount += intval($transaction_data->no_printing_page)* intval($transaction_data->printing_charge_per_page);
          //         $payble_amount += intval($transaction_data->no_scanning_page)* intval($transaction_data->scanning_charge_per_page);
          //     }
              
          // }
          // $data['total_amount']= $payble_amount;
          // $data['paid_amount']= property_exists($transaction_data,"ctz_payment_response") ? $transaction_data->ctz_payment_response->AMOUNT : 0  ;
          // $data['unpaid_amount']= $payble_amount - $data['paid_amount'];
          $this->load->view('includes/frontend/header');
          $this->load->view('correction_payment_details',$data);
          $this->load->view('includes/frontend/footer');
    }else{
        $this->my_transactions();
    }
    
  }
  public function payment($rtps_trans_id){ 
    $app_status=false;
    if(empty($rtps_trans_id)){
      $this->my_transactions();
    }

    $transaction_data=$this->intermediator_model->get_by_rtps_id($rtps_trans_id); //pre($transaction_data);
    if(empty($transaction_data)){
      $this->my_transactions();
    }
  
      if($transaction_data->status !== "S"){
        $this->my_transactions();
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
    if(property_exists($transaction_data,"ctz_department_id") && property_exists($transaction_data,"ctz_payment_params") ){
      $this->check_payment_status($transaction_data->ctz_department_id);
    }
     //for citizen
     $uniqid=uniqid();
     $DEPARTMENT_ID=$uniqid.time();
     $data['rtps_trans_id']=$rtps_trans_id;
     if(empty($transaction_data->payment_details)){
       $this->my_transactions();
     }
     
     $payment_details=is_array($transaction_data->payment_details)?$transaction_data->payment_details[0]:false;
     if(!$payment_details){
       $this->my_transactions();
     }
     $data['department_data']=(array) $payment_details;
     $data['department_data']['DEPARTMENT_ID']=$DEPARTMENT_ID;
     $data['department_data']['SUB_SYSTEM']="ARTPS-SP|".base_url('iservices/Paymentcorrection/rtps_payment_response');
     $data['department_data']['MULTITRANSFER']="N";
     // pre($data);
     $res=$this->update_pfc_payment_amount($data);
     if($res){
       $this->load->view('basundhara/payment',$data);
     }else{
       $this->my_transactions();
     }
    
  }

  public function pfcpayment(){
  
   
    if($this->session->userdata('role') === "csc"){
      $account=$this->config->item('csc_account');
      //$mobile=$this->session->userdata('user')->mobileno;
    }else{
      $user=$this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
      $account=$user->account1;
      //$mobile=$user->mobile;
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
      if(empty($transaction_data)){
        $this->my_transactions();
      }

      $payment_details=is_array($transaction_data->payment_details)?$transaction_data->payment_details[0]:false;
     
      if(!empty($payment_details)){
        $data['department_data']=(array)$payment_details;
        $data['department_data']['DEPARTMENT_ID']=$DEPARTMENT_ID;
        $data['department_data']['SUB_SYSTEM']="ARTPS-SP|".base_url('iservices/transoprt_response/rtps_payment_response');
        $data['department_data']['MULTITRANSFER']="Y";
        $data['department_data']["NON_TREASURY_PAYMENT_TYPE"]="02";
        $data['department_data']["TOTAL_NON_TREASURY_AMOUNT"]=$total_amount;
        $data['department_data']["AC1_AMOUNT"]=$total_amount;
        $data['department_data']["ACCOUNT1"]=$account;
      }else{
        //payment config is required.
        $this->my_transactions();
        // $guidelines=$this->portals_model->get_guidelines($transaction_data->service_id);
        //   if(empty($guidelines)){
        //     return;
        //     exit();
        //   }
        //   $dept_code=$guidelines->dept_code;
        //   $office_code=$guidelines->office_code;
          
        // $data['department_data']=array(
        //   "DEPT_CODE"=>$dept_code,//$user->dept_code,
        //   "OFFICE_CODE"=>$office_code,//$user->office_code,
        //   "REC_FIN_YEAR"=>"2022-2023",//dynamic
        //   "HOA1"=>"",
        //   "FROM_DATE"=>"01/04/2022",
        //   "TO_DATE"=>"31/03/2099",
        //   "PERIOD"=>"O",// O for ontimee payment
        //   "CHALLAN_AMOUNT"=>"0",
        //   "DEPARTMENT_ID"=>$DEPARTMENT_ID,
        //   "MOBILE_NO"=>$mobile,//pfc no
        //   // "SUB_SYSTEM"=>"ARTPS-SP|".base_url('iservices/admin/get/payment-response'),
        //   "SUB_SYSTEM"=>"ARTPS-SP|".base_url('iservices/basundhara/get/payment-response'),
        //   "PARTY_NAME"=>isset($transaction_data->applicant_name) ? $transaction_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
        //   "PIN_NO"=>isset($transaction_data->pin_code) ? $transaction_data->pin_code : "781005",
        //   "ADDRESS1"=>isset($transaction_data->address1) ? $transaction_data->address1 : "NIC",
        //   "ADDRESS2"=>isset($transaction_data->address2) ? $transaction_data->address2 : "",
        //   "ADDRESS3"=>isset($transaction_data->address3) ? $transaction_data->address3 : "781005",
        //   "MULTITRANSFER"=>"Y",
        //   "NON_TREASURY_PAYMENT_TYPE"=>"02",
        //   "TOTAL_NON_TREASURY_AMOUNT"=>$total_amount,
        //   "AC1_AMOUNT"=>$total_amount,
        //   "ACCOUNT1"=>$account, //$user->account1,
        // );
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
      $data_to_update=array('ctz_department_id'=>$payment_params['DEPARTMENT_ID'],
                            'ctz_payment_params'=>$payment_params);
      if(isset($data['pfc_payment'])){
        $data_to_update['service_charge']=$data['service_charge'];
        $data_to_update['scanning_charge_per_page']=$data['scanning_charge_per_page'];
        $data_to_update['printing_charge_per_page']=$data['printing_charge_per_page'];
        $data_to_update['no_printing_page']=$data['no_printing_page'];
        $data_to_update['no_scanning_page']=$data['no_scanning_page'];
      }
     
      $result=$this->intermediator_model->update_payment_status($rtps_trans_id,$data_to_update);
      // pre($result->getMatchedCount());
         if($result->getMatchedCount()){
           $this->load->model('admin/pfc_payment_history_model');
           $data_to_update['rtps_trans_id']=$rtps_trans_id;
           $data_to_update['department_id']=$payment_params['DEPARTMENT_ID'];
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
    $transaction_data = $this->intermediator_model->get_row(array('ctz_department_id' => $DEPARTMENT_ID));
   
    if ($DEPARTMENT_ID) {
      $OFFICE_CODE = $transaction_data->ctz_payment_params->OFFICE_CODE;
      $am1 = isset($transaction_data->ctz_payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $transaction_data->ctz_payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
      $am2 = isset($transaction_data->ctz_payment_params->CHALLAN_AMOUNT) ? $transaction_data->ctz_payment_params->CHALLAN_AMOUNT : 0;
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
          array('ctz_department_id' => $DEPARTMENT_ID),
          array(
            "ctz_payment_response.GRN" => $GRN,
            "ctz_payment_response.AMOUNT" => isset($res[6]) ? $res[6] : '',
            "ctz_payment_response.PARTYNAME" => isset($res[18]) ? $res[18] : '',
            "ctz_payment_response.TAXID" => isset($res[20]) ? $res[20] : '',
            "ctz_payment_response.DEPARTMENT_ID" => isset($res[2]) ? $res[2] : '',
            "ctz_payment_response.BANKNAME" => isset($res[22]) ? $res[22] : '',
            "ctz_payment_response.BANKCODE" => isset($res[8]) ? $res[8] : '',
            "ctz_payment_response.ENTRY_DATE" => isset($res[24]) ? $res[24] : '',
            "ctz_payment_response.STATUS" => $STATUS,
            "ctz_payment_response.PRN" => isset($res[12]) ? $res[12] : '',
            "ctz_payment_response.TRANSCOMPLETIONDATETIME" => isset($res[14]) ? $res[14] : '',
            "ctz_payment_response.BANKCIN" => isset($res[10]) ? $res[10] : '',
            'ctz_payment_status' => $STATUS
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


  public function push_noc_payment_status($DEPARTMENT_ID)
  {
    if ($DEPARTMENT_ID) {
      $application_details = $this->intermediator_model->get_application_details(array("ctz_department_id" => $DEPARTMENT_ID));
      // pre($application_details);
      if ($application_details) {
        $encryption_key = $this->config->item("encryption_key");
        if (property_exists($application_details, 'ctz_payment_response') && !empty($application_details->pfc_payment_response)) {
          $am1 = isset($application_details->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $application_details->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
          $am2 = isset($application_details->payment_params->CHALLAN_AMOUNT) ? $application_details->payment_params->CHALLAN_AMOUNT : 0;
          $AMOUNT =  $am2;
          $params = array(
            'application_no' => $application_details->app_ref_no,
            'department_id' => $DEPARTMENT_ID,
            'grn' => $application_details->pfc_payment_response->GRN,
            'bankcode' => $application_details->pfc_payment_response->BANKCODE,
            'bankcin' => $application_details->pfc_payment_response->BANKCIN,
            'prn' => $application_details->pfc_payment_response->PRN,
            'amount'=>$AMOUNT,
            'status' => $application_details->pfc_payment_response->STATUS,
            'partyname' => $application_details->pfc_payment_response->PARTYNAME,
            'taxid' => $application_details->pfc_payment_response->TAXID,
            'bankname' => $application_details->pfc_payment_response->BANKNAME,
            'entry_date' => $application_details->pfc_payment_response->ENTRY_DATE,
            'transcompletiondatetime'=>$application_details->pfc_payment_response->TRANSCOMPLETIONDATETIME,
          );

          $url = $this->config->item('noc_push_payment_status_url');
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
          //pre($response);
          curl_close($curl);
          if($response){
          
            $data_res=json_decode($response);
            if(isset($data_res->status) && $data_res->status === "Success"){
              $result = $this->intermediator_model->add_param($application_details->rtps_trans_id, array(
                "payment_status_updated_on_noc"=>true
              ));
            }
          }
        
        
        }
      }
    }
  }

public function success($data){
  $data=array(
    'GRN'=>$data->ctz_payment_response->GRN,
    'DEPARTMENT_ID'=>$data->ctz_department_id
  );
  
  $this->load->view('includes/frontend/header');
  $this->load->view('ctz_pay_ack',$data);
  $this->load->view('includes/frontend/footer');
}
public function rtps_payment_response(){
  $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
    $response = $_POST;
    $this->intermediator_model->update_row(array('ctz_department_id' => $DEPARTMENT_ID), array("ctz_payment_status" => $_POST['STATUS'], "ctz_payment_response" => $response));
    if ($_POST['STATUS'] === 'Y') {
      $this->intermediator_model->update_row(array('ctz_department_id' => $DEPARTMENT_ID), array("has_payment_issue" =>false));
      $this->push_noc_payment_status($DEPARTMENT_ID);
      $transaction_data = $this->intermediator_model->get_row(array('ctz_department_id' => $DEPARTMENT_ID));
  
    $this->success( $transaction_data);
    return;
    } else {
      $this->show_error();
    }
}
public function show_error(){
  $this->load->view('includes/frontend/header');
  $this->load->view('error');
  $this->load->view('includes/frontend/footer');
}
  public function get_payment_details(){
    $list=["AS220610A3423584",
    "AS220610A5228104",
    "AS220610A1301284",
    "AS220610A7270270",
    "AS220610A3733045",
    "AS220610A3272747",
    "AS220610A5895356",
    "AS220610A5403856",
    "AS220610A514296",
    "AS220610A5876086",
    "AS220610A7358929",
    "AS220610A2186742",
    "AS220610A2999339",
    "AS220610A8190150",
    "AS220610A3596780",
    "AS220610A7704640",
    "AS220610A3422413",
    "AS220610A2391903",
    "AS220610A6844089",
    "AS220610A2442619",
    "AS220610A4922490",
    "AS220610A9604916",
    "AS220610A6005062",
    "AS220310A4573032",
    "AS220610A4772770",
    "AS220610A6013053",
    "AS220610A8232351",
    "AS220610A6263879",
    "AS220610A1283558",
    "AS220610A8295003",
    "AS220610A3117189",
    "AS220610A1394594",
    "AS220610A7540350",
    "AS220610A5588629",
    "AS220610A4926398",
    "AS220510A4624575",
    "AS220610A6715544",
    "AS220610A5033929",
    "AS220610A2508103",
    "AS220610A2953415",
    "AS220610A5051956",
    "AS220610A6208845",
    "AS220610A4817166",
    "AS220610A8713848",
    "AS220610A4223037",
    "AS220610A3398467",
    "AS220610A5107009",
    "AS220610A7836341",
    "AS220610A5466719",
    "AS220610A9284282",
    "AS220610A7391957",
    "AS220610A8585315",
    "AS220610A3804905",
    "AS220610A2525063",
    "AS220610A7001075",
    "AS220610A3026895",
    "AS220610A2461633",
    "AS220610A113470",
    "AS220310A4690307"
  ];
$url='https://ilrms.nic.in/noc/index.php/usercontrol/rtpsfrmnopay';
  foreach($list as $item){
  //  pre($item);
    $curl=curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl,CURLOPT_URL,$url."?data=".$item);
    $res=curl_exec($curl);

    curl_close($curl);
    if($res){
      $response=json_decode($res,true);
      if($response['status'] ==="success"){
       if(isset($response['data']['payment_details'])){
        //  pre($response['data']['payment_details']);
        $res= $this->intermediator_model->add_param($item,
        array(
          "app_ref_no"=>$response['data']['app_ref_no'],
          "status"=>$response['data']['status'],
          "amount"=>$response['data']['amount'],
          "amountmut"=>$response['data']['amountmut'],
          "amountpart"=>$response['data']['amountpart'],
          "applicant_details"=>$response['data']['applicant_details'],
          "payment_details"=>$response['data']['payment_details']));
         
      
       }
      }
   
    }
  }
  }



  
  public function update_payment_config($rtps_trans_id){
   
$url='https://ilrms.nic.in/noc/index.php/usercontrol/rtpsfrmnopay';

  //  pre($item);
    $curl=curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl,CURLOPT_URL,$url."?data=".$rtps_trans_id);
    $res=curl_exec($curl);

    curl_close($curl);
    if($res){
      $response=json_decode($res,true);
      if($response['status'] ==="success"){
       if(isset($response['data']['payment_details'])){
        //  pre($response['data']['payment_details']);
        $res= $this->intermediator_model->add_param($rtps_trans_id,
        array(
          "app_ref_no"=>$response['data']['app_ref_no'],
          "status"=>$response['data']['status'],
          "amount"=>$response['data']['amount'],
          "amountmut"=>$response['data']['amountmut'],
          "amountpart"=>$response['data']['amountpart'],
          "applicant_details"=>$response['data']['applicant_details'],
          "payment_details"=>$response['data']['payment_details']));
         
      
       }
       echo "updated";
      }else{
        echo "Failed to update";
      }
   
    }else{
      echo "Failed to update";
    }

  }

}
