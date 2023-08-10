<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
class Transoprt_response extends Frontend
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('intermediator_model');
    $this->load->model('portals_model');
    $this->config->load('rtps_services');
    $this->load->library('AES');
    $this->load->library('AESrathi');
    $this->encryption_key=$this->config->item("agencyKey");
    $this->load->helper('smsprovider');
  }
  public function vahan(){

  if(isset($_GET['response']) && !empty($_GET['response'])){
      if(strpos($_SERVER["QUERY_STRING"], "+") !== false){
          $_SERVER["QUERY_STRING"] = str_replace("+", "%2B", $_SERVER["QUERY_STRING"]);
          parse_str($_SERVER["QUERY_STRING"], $_GET);
        }
      if(strpos($_SERVER["QUERY_STRING"], "//s") !== false){
          $_SERVER["QUERY_STRING"] = str_replace("//s", "", $_SERVER["QUERY_STRING"]);
          parse_str($_SERVER["QUERY_STRING"], $_GET);
        }
      
      $aes = new AESrathi($_GET['response'], $this->encryption_key,128);
      $recived_data=$aes->decrypt();
      // $recived_data=$this->decrypt(array("result"=>$_GET['response']));
      parse_str( $recived_data, $array );
      $response=$array;
     // pre( $response);
      $portal_no="2";
      if(!$this->validateVahanResponse($response)){
        return;
      }
      if(!empty($response) && !empty($response['rtps_id'])){
           // $service_id=isset($response['purpose_code']) ? $response['purpose_code'] : "";
            $rtps_trans_id=$response['rtps_id'];
            $data_to_update=array(
            //  "service_id"=>$service_id,
              "portal_no"=>$portal_no,
              "vr_purpose_code"=>isset($response['purpose_code']) ? $response['purpose_code'] : "",
              "PurposeDescription"=>isset($response['PurposeDescription']) ? $response['PurposeDescription'] : "",
              "portal_cd"=>isset($response['portal_cd']) ? $response['portal_cd'] : "",
              "regn_no"=>isset($response['regn_no']) ? $response['regn_no'] : "",
              "chassi_no"=>isset($response['chassi_no']) ? $response['chassi_no'] : "",
              // "rtps_id"=>isset($response['rtps_id']) ? $response['rtps_id'] : "",
              "submission_mode"=>isset($response['submission_mode']) ? $response['submission_mode'] : "",
              "service_type"=>isset($response['service_type']) ? $response['service_type'] : "",
              "submission_date"=>isset($response['submission_date']) ? $response['submission_date'] : "",
              "v_user_id"=>isset($response['user_id']) ? $response['user_id'] : "",
              "submission_location"=>isset($response['submission_location']) ? $response['submission_location'] : "",
              "payment_mode"=>isset($response['payment_mode']) ? $response['payment_mode'] : "",
              "payment_ref_no"=>isset($response['payment_ref_no']) ? $response['payment_ref_no'] : "",
              "payment_date"=>isset($response['payment_date']) ? $response['payment_date'] : "",
              "vahan_app_no"=>isset($response['vahan_app_no']) ? $response['vahan_app_no'] : "",
              "vahan_rcpt_no"=>isset($response['vahan_rcpt_no']) ? $response['vahan_rcpt_no'] : "",
              "amount"=>isset($response['amount']) ? $response['amount'] : "",
              "status"=>isset($response['ApplicationStatus']) ? $response['ApplicationStatus']: "",
              "gender"=>isset($response['gender']) ? $response['gender']: "",
              "e-mail"=>isset($response['e-mail']) ? $response['e-mail']: "",
              "applicant_name"=>isset($response['applicant_name']) ? $response['applicant_name']: "",
              "fathers_name"=>isset($response['fathers_name']) ? $response['fathers_name']: "",
              "mobile_number"=>isset($response['mobile_number']) ? $response['mobile_number']: "",
              "address_line_1"=>isset($response['address_line_1']) ? $response['address_line_1']: "",
              "address_line_2"=>isset($response['address_line_2']) ? $response['address_line_2']: "",
              "country"=>isset($response['country']) ? $response['country']: "",
              "state"=>isset($response['state']) ? $response['state']: "",
              "district"=>isset($response['district']) ? $response['district']: "",
              "pin_code"=>isset($response['pin_code']) ? $response['pin_code']: "",
            );

            $result=$this->intermediator_model->add_param($rtps_trans_id,$data_to_update);
           if($result->getMatchedCount()){
             $service_id=$this->intermediator_model->get_transaction_detail($response['rtps_id']);
            //  pre($service_id);
             $service_id=$service_id->service_id;
             $departmental_data=$this->portals_model->get_departmental_data($service_id);
             $data=array();
             $data['response']=$response;
             $data['timeline_days']=$departmental_data->timeline_days;
             $data['department_name']=$departmental_data->department_name;
             $data['service_name']=$departmental_data->service_name;
             if(isset($response['ApplicationStatus']) && $response['ApplicationStatus'] === "S"){
              //  $this->show_vahan_acknowledgment($data);
              // collect convenience fee
              redirect(base_url('iservices/convenience_fee/payment/').$rtps_trans_id);
             }else {
               $this->show_error();
             }

           }else {
             $this->show_error();
           }

      }else {
        $this->show_error();
      }
    }else {
      return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode(array(
              'data' => "No data received",

          )));
          return;
    }

  }
  public function payment_response(){
    $DEPARTMENT_ID=$this->input->post('DEPARTMENT_ID');
    $response=$_POST;
    $app_details=$this->intermediator_model->get_transaction_detail($DEPARTMENT_ID);
    $this->intermediator_model->update_payment_status($DEPARTMENT_ID,array("payment_status"=>$_POST['STATUS'],"payment"=>$response));
//// TODO: acknowledgement base on payment status
    $data['app_ref_no']=$app_details->app_ref_no;
    $data['status']=$_POST['STATUS'];
    $data['url']=base_url("iservices/o-acknowledgement?app_ref_no=").$data['app_ref_no'];
    $this->load->view('includes/frontend/header');
    $this->load->view('payment_response',$data);
    $this->load->view('includes/frontend/footer');

  }
  public function grn_response(){

    $DEPARTMENT_ID=$_POST['DEPARTMENT_ID'];
    $STATUS=$_POST['STATUS'];
    $GRN=$_POST['GRN'];
    if($STATUS === "Y"){
      $this->intermediator_model->update_payment_status($DEPARTMENT_ID,array("payment.GRN"=>$GRN,"payment.STATUS"=>$STATUS));
    }
    redirect(base_url('iservices/transactions'));
  }
  public function checkgrn($DEPARTMENT_ID = null, $check = false)
  { // TODO: need to check which are params to update
    $transaction_data = $this->intermediator_model->get_row(array('department_id' => $DEPARTMENT_ID));
    if ($DEPARTMENT_ID) {
      $OFFICE_CODE = $transaction_data->payment_params->OFFICE_CODE;
      $am1 = 0;//isset($transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
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
  public function cin_response(){

    $DEPARTMENT_ID=$_POST['DEPARTMENT_ID'];
    $STATUS=$_POST['STATUS'];
    $BANKCIN=$_POST['BANKCIN'];
    if($STATUS === "Y"){
      $this->intermediator_model->update_payment_status($DEPARTMENT_ID,array("payment.BANKCIN"=>$BANKCIN,"payment.STATUS"=>$STATUS));
    }
    redirect(base_url('iservices/transactions'));

  }
  public function show_vahan_acknowledgment($data){
    $this->load->view('includes/frontend/header');
    $this->load->view('vahan_acknowledgment',$data);
    $this->load->view('includes/frontend/footer');
  }
  public function show_acknowledgment($data){
    if(!empty($data) && isset($data['response']) && property_exists($data['response'],'rtps_trans_id')){
      $this->send_submission_sms($data['response']->rtps_trans_id);
    }
    $this->load->view('includes/frontend/header');
    $this->load->view('noc_ack1',$data);
    $this->load->view('includes/frontend/footer');
  }
  public function show_error(){
    $this->load->view('includes/frontend/header');
    $this->load->view('error');
    $this->load->view('includes/frontend/footer');
  }
  public function validateVahanResponse($response){
    // rtps_id,purpose_code,submission_date,vahan_app_no,applicant_name,ApplicationStatus
    $validation=true;
    if(empty($response['rtps_id'])){
      $validation=false;
    }
    if(empty($response['purpose_code'])){
      $validation=false;
    }
    if(empty($response['submission_date'])){
      $validation=false;
    }
    if(empty($response['vahan_app_no'])){
      $validation=false;
    }
    if(empty($response['applicant_name'])){
      $validation=false;
    }
    if(empty($response['ApplicationStatus'])){
      $validation=false;
    }
    if($response['service_type'] === "N"){
      //for no transaction in vahan end , all parameters blank
      $validation=false;
      $result=$this->intermediator_model->add_param($response['rtps_id'],array('status'=>'F'));
    }
    if($validation){
      return true;
    }else {
      $this->show_error();
    }
  }
  public function validateResponse($response){
    // service_id,app_ref_no,status,submission_date,applicant_details
    $validation=true;
    if(empty($response->rtps_trans_id)){
      $validation=false;
    }
    if(empty($response->portal_no)){
      $validation=false;
    }
    if(empty($response->service_id)){
      $validation=false;
    }
    if(empty($response->app_ref_no)){
      $validation=false;
    }
    if(empty($response->status)){
      $validation=false;
    }
    if(empty($response->submission_date)){
      $validation=false;
    }
    if(empty($response->applicant_details)){
      $validation=false;
    }
    if($validation){
      return true;
    }else {
      $this->show_error();
    }
  }
  public function create_response_new(){
    $output=$_POST;
    $data=array();
    if($output){
      $response = ($output['data']);

      $encryption_key = $this->config->item("encryption_key");

    if(!empty($response)){
      //decrypt the user input

      $aes = new AES($response, $encryption_key);
      $desc_response = $aes->decrypt();

      $response=json_decode($desc_response);
      if(!isset($response->response_data)){
        $this->show_error();
        // return $this->output
        //     ->set_content_type('application/json')
        //     ->set_status_header(200)
        //     ->set_output(json_encode(array(
        //         'response_data' => "No Data Found",
        //
        //     )));
            return;
      }

      $status=isset($response->status) ? $response->status:'';

      $response=$response->response_data;
      $this->store_response_dump($response->rtps_trans_id,$response,$output);
     if(!$this->validateResponse($response)){
       return;
     }
      if(isset($response->portal_no)){
          $data_to_update=array(
          //  "service_id"=>isset($response->service_id) ? $response->service_id : "",
            "app_ref_no"=>isset($response->app_ref_no) ? $response->app_ref_no : "",
            "status"=>isset($response->status) ? $response->status : "",
            "submission_date"=>isset($response->submission_date) ? $response->submission_date : "",
            "submission_location"=>isset($response->submission_location) ? $response->submission_location : "",
            "district"=>isset($response->district) ? $response->district : "",
            "circle"=>isset($response->circle) ? $response->circle : "",
            "payment_mode"=>isset($response->payment_mode) ? $response->payment_mode : "",
            "payment_ref_no"=>isset($response->payment_ref_no) ? $response->payment_ref_no : "",
            "payment_status"=>isset($response->payment_status) ? $response->payment_status : "",
            "payment_date"=>isset($response->payment_date) ? $response->payment_date : "",
            "amount"=>isset($response->amount) ? $response->amount : "",
            "amountmut"=>isset($response->amountmut) ? $response->amountmut : 0,
            "amountpart"=>isset($response->amountpart) ? $response->amountpart : 0,
            "portal_no"=>isset($response->portal_no) ? $response->portal_no : "",
            "applicant_details"=>isset($response->applicant_details) ? $response->applicant_details : "",
            "application_details"=>isset($response->application_details) ? $response->application_details : "",
            "payment_details"=>isset($response->payment_details) ? $response->payment_details : ""
          );
         
          if($response->portal_no === 1 || $response->portal_no === "1"){
            // for noc portal
            //// TODO:  update should be base on two params rtps_id,user_id
            $result=$this->intermediator_model->add_param($response->rtps_trans_id,$data_to_update);
           if($result->getMatchedCount()){
             //check new application or old
             $transaction_data = $this->intermediator_model->get_row(array('rtps_trans_id' =>$response->rtps_trans_id));
             if(property_exists($transaction_data ,'payment_rtps_end') && $transaction_data->payment_rtps_end){
               
                  //payment is required
                  if(isset($response->status) && $response->status === "S"){
                    redirect(base_url('iservices/rtpspayment/payment/'.$response->rtps_trans_id));
                  }else {
                    $this->show_error();
                  }

             }else{
                    $departmental_data=$this->portals_model->get_departmental_data_by_portal($response->service_id,$response->portal_no);
                    $data=array();
                    $data['response']=$response;
                    $data['timeline_days']=$departmental_data->timeline_days;
                    $data['department_name']=$departmental_data->department_name;
                    $data['service_name']=$departmental_data->service_name;
                    if(isset($response->status) && $response->status === "S"){
                      $this->show_acknowledgment($data);
                    }else {
                      $this->show_error();
                    }

             }
           
           

           }else {
             $this->show_error();
           }

         }else {
           //for other portal
            //var_dump($data_to_update);die;
                 $departmental_data=$this->portals_model->get_departmental_data_by_portal($response->service_id,$response->portal_no);
                  $intermediateID=$response->rtps_trans_id;
                  //// TODO:  update should be base on two params rtps_id,user_id
                  $result=$this->intermediator_model->add_param($response->rtps_trans_id,$data_to_update);
                 if($result->getMatchedCount()){
                   if(isset($response->status) && $response->status === "S"){

                    if($response->portal_no === 8 || $response->portal_no === "8" || $response->portal_no === "16"){
                          //convience fee is required 
                          redirect(base_url('iservices/rtpspayment/convenience_fee/'.$response->rtps_trans_id));
                    }else{
                          if(isset($departmental_data->is_payment_required) && $departmental_data->is_payment_required === "true"){
                            //go for payment
                            die("payment config not found");
                          }else {
                            //show acknowledgments
                            $data=array();
                            $data['response']=$response;
                            $data['timeline_days']=$departmental_data->timeline_days;
                            $data['department_name']=$departmental_data->department_name;
                            $data['service_name']=$departmental_data->service_name;
                            $this->show_acknowledgment($data);
                          }
                    }
                   
                   }else {
                     $this->show_error();
                   }
                 }else {
                    $this->show_error();
                 }

          }
      }else {
        $this->show_error();
      }


    }else {
      // return $this->output
      //     ->set_content_type('application/json')
      //     ->set_status_header(200)
      //     ->set_output(json_encode(array(
      //         'response_data' => "No Data Found",
      //
      //     )));
        $this->show_error();
          return;
    }
  }else {
    // return $this->output
    //     ->set_content_type('application/json')
    //     ->set_status_header(200)
    //     ->set_output(json_encode(array(
    //         'response_data' => "No Data Found",
    //
    //     )));
      $this->show_error();
        return;
  }



  }
  public function GRN(){
    $this->load->view('includes/frontend/header');
    $this->load->view('grn');
    $this->load->view('includes/frontend/footer');
  }
  public function create_response(){
      $data = file_get_contents("php://input");
      $data = json_decode($data, true);
      //var_dump($data);die;
      $encryption_key = $this->config->item("encryption_key");
      $data=$data['data'];
    if(!empty($data)){
      //decrypt the user input

      $aes = new AES($data, $encryption_key);
      $response = $aes->decrypt();

      $response=json_decode($response);
      $this->mongo_db->insert("testapi",
        array("registration_id"=>$response->registration_id,
              "rtps_id"=>$response->rtps_id,
              "user_mobile"=>$response->user_mobile)
      );
    }
  }
  public function verify_response(){
      $data = file_get_contents("php://input");
      $data = json_decode($data, true);
      //var_dump($data);die;
      $encryption_key = $this->config->item("encryption_key");
      $data=$data['data'];
    if(!empty($data)){
      //decrypt the user input

      $aes = new AES($data, $encryption_key);
      $response = $aes->decrypt();

      $response=json_decode($response);
      $this->mongo_db->insert("testapi2",
        array("registration_id"=>$response->registration_id,
              "rtps_id"=>$response->rtps_id,
              "user_mobile"=>$response->user_mobile)
      );
    }
  }
  public function procces_response(){
      $data = file_get_contents("php://input");
      $data = json_decode($data, true);
      //var_dump($data);die;
      $encryption_key = $this->config->item("encryption_key");
      $data=$data['data'];
    if(!empty($data)){
      //decrypt the user input

      $aes = new AES($data, $encryption_key);
      $response = $aes->decrypt();

      $response=json_decode($response);
      $this->mongo_db->insert("testapi3",
        array("registration_id"=>$response->registration_id,
              "rtps_id"=>$response->rtps_id,
              "user_mobile"=>$response->user_mobile)
      );
        //do the db operation
    }
  }


  public function send_submission_sms($rtps_trans_id=null){
    if($rtps_trans_id){
      $data=$this->intermediator_model->get_by_rtps_id($rtps_trans_id);
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
  //scheduler for updating noc services 

  public function noc_status_update(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $applications=$this->intermediator_model->get_noc_pending_application();
   //pre( $applications);
    foreach ($applications as $key => $value) {
      $rtps_trans_id=$value->rtps_trans_id;
      $app_ref_no=isset($value->app_ref_no)?$value->app_ref_no:false;
      if($app_ref_no){
        $user_mobile=$value->mobile;
        $res=$this->update_action_noc($app_ref_no, $user_mobile);
       
        $data_to_save=array();
        if($res){
          $status='P';


          $final_task=array_reverse($res->task_details);
        
          if( $final_task){
            if($final_task[0]->action === "Reject" || $final_task[0]->action === "reject")
              $status='R';
            if($final_task[0]->action === "Complete" || $final_task[0]->action === "complete")
              $status='D';
              if($final_task[0]->action === "Query" || $final_task[0]->action === "query")
              $status='Q';
              if($final_task[0]->action === "Forward" || $final_task[0]->action === "forward")
              $status='F';
          }
          $data_to_save['execution_data']=$res->task_details;
          $data_to_save['delivery_status']=$status;
          $data_to_save['execution_date']=isset($final_task[0]->executed_time)?$final_task[0]->executed_time : '';
          // pre( $data_to_save);
          $this->intermediator_model->add_param($rtps_trans_id,$data_to_save);
          // die("done");
        }
       // pre($res);
      }
     
    }

  }
  public function update_action_noc($app_ref_no=null,$user_mobile=null){
    if(!empty($app_ref_no) && !empty($user_mobile)){
      $encryption_key=$this->config->item("encryption_key");
      $status_url = "https://ilrms.nic.in/noc/index.php/usercontrol/statustrack"; 
      $data=array(
        "app_ref_no"=>$app_ref_no,//'NOC/05/143/2020'
        "mobile"=>$user_mobile //"9435347177"
      );
      $input_array=json_encode($data);
      $aes = new AES($input_array, $encryption_key);
      $enc = $aes->encrypt();
      //curl request
    
      $post_data=array('data'=>$enc);
      $curl = curl_init($status_url);
      // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      $response = curl_exec($curl);
      curl_close($curl);
      if($response){
        $response=json_decode($response);
      }
      
      //decryption
      if(isset($response->data) && !empty($response->data)){
        $aes->setData($response->data);
        $dec=$aes->decrypt();
        $outputdata=json_decode($dec);
        return  $outputdata;
      }
    }else{
      return false;
    }
 

  }

  public function update_vahan_applications(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $applications=$this->intermediator_model->get_vahan_pending_application();//pre($applications);
    foreach ($applications as $key => $value) {
      $rtps_trans_id=$value->rtps_trans_id;
      $app_ref_no=isset($value->vahan_app_no)?$value->vahan_app_no:false;
      if($app_ref_no){
        $res=$this->update_action_vahan($app_ref_no);
       
        $data_to_save=array();
        if($res){
          $data_to_save['delivery_status']=$res->appl_status;
          $data_to_save['execution_date']=$res->execution_date_str;
          // pre( $data_to_save);
          $this->intermediator_model->add_param($rtps_trans_id,$data_to_save);
          // die("done");
        }
       // pre($res);
      }
     
    }
    echo "done";
  }

  public function update_action_vahan($app_ref_no=null){
    
    if(!empty($app_ref_no)){
      $status_url="http://localhost/dashboard/vahanapi/getdata/".$app_ref_no;
     
      $curl = curl_init($status_url);
      curl_setopt($curl, CURLOPT_POST, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($curl);
      curl_close($curl);
    $response = json_decode($response);
    if($response->data){
      return $response->data->initiated_data;
    }else
    return [];
    
      
    }else{
      return false;
    }

  }


  public function rtps_payment_response()
  {
    $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
    $response = $_POST;
    $this->intermediator_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_status" => $_POST['STATUS'], "pfc_payment_response" => $response));
    if ($_POST['STATUS'] === 'Y') {
      //check the grn for valid transactions
      if ($this->checkgrn($DEPARTMENT_ID, true) === $_POST['GRN']) {
       
        $transaction_data = $this->intermediator_model->get_row(array('department_id' => $DEPARTMENT_ID));
        if( $transaction_data->portal_no === 1 || $transaction_data->portal_no === "1" ){
          $this->push_noc_payment_status($DEPARTMENT_ID);
          
        }
        $this->send_submission_sms(null,$DEPARTMENT_ID);
        if(property_exists($transaction_data,'applied_by') && !empty($transaction_data->applied_by)){
          redirect(base_url('iservices/admin/get-acknowledgement?app_ref_no=').$DEPARTMENT_ID);
        }else{
          redirect(base_url('iservices/o-acknowledgement?app_ref_no=') . $transaction_data->app_ref_no);
        }
        
      } else {
        //grn does not match Something went wrong
        echo "Something wrong in middle";
        $this->intermediator_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_status" => "P", "pfc_payment_response.STATUS" => 'P'));
        $this->show_error();
      }
    } else {
      $this->show_error();
    }
  }

  public function push_noc_payment_status($DEPARTMENT_ID)
  {
    if ($DEPARTMENT_ID) {
      $application_details = $this->intermediator_model->get_application_details(array("department_id" => $DEPARTMENT_ID));
      // pre($application_details);
      if ($application_details) {
        $encryption_key = $this->config->item("encryption_key");
        if (property_exists($application_details, 'pfc_payment_response') && !empty($application_details->pfc_payment_response)) {
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
            if($data_res->status === "Success"){
              $result = $this->intermediator_model->add_param($application_details->rtps_trans_id, array(
                "payment_status_updated_on_noc"=>true
              ));
            }
          }
        
        
        }
      }
    }
  }


  public function decrypt($data){
    $url=$this->config->item("decrypt_url");
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }
  private function store_response_dump($rtps_trans_id,$response,$encryption_response){
    $this->mongo_db->insert("response_history",array(
      'rtps_trans_id'=>$rtps_trans_id,
      'response'=>$response,
      'encResponse'=>$encryption_response,
      'createdDtm'=>new MongoDB\BSON\UTCDateTime(microtime(true) * 1000)
    ));
}

}
