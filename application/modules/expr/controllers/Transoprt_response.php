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
      $recived_data=$this->decrypt(array("result"=>$_GET['response']));
      parse_str( $recived_data, $array );
      $response=$array;
      $portal_no="2";
      if(!$this->validateVahanResponse($response)){
        return;
      }
      if(!empty($response) && !empty($response['rtps_id'])){
            $service_id=isset($response['purpose_code']) ? $response['purpose_code'] : "";
            $rtps_trans_id=$response['rtps_id'];
            $data_to_update=array(
              "service_id"=>$service_id,
              "portal_no"=>$portal_no,
              "purpose_code"=>isset($response['purpose_code']) ? $response['purpose_code'] : "",
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
             $departmental_data=$this->portals_model->get_departmental_data($service_id);
             $data=array();
             $data['response']=$response;
             $data['timeline_days']=$departmental_data->timeline_days;
             $data['department_name']=$departmental_data->department_name;
             $data['service_name']=$departmental_data->service_name;
             if(isset($response['ApplicationStatus']) && $response['ApplicationStatus'] === "S"){
               $this->show_vahan_acknowledgment($data);
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
    $data['url']=base_url("expr/o-acknowledgement?app_ref_no=").$data['app_ref_no'];
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
    redirect(base_url('expr/transactions'));
  }
  public function checkgrn($DEPARTMENT_ID=null){
    if($DEPARTMENT_ID){
      exit("Need to update dynamic account");die;
      $AMOUNT=30;
      $string_field="DEPARTMENT_ID=".$DEPARTMENT_ID."&OFFICE_CODE=IGR013&AMOUNT=".$AMOUNT;
      $url = $this->config->item('egras_grn_cin_url');
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL, $url);
      curl_setopt($ch,CURLOPT_POST,3);
      curl_setopt($ch,CURLOPT_POSTFIELDS, $string_field);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      // curl_setopt ($ch, CURLOPT_CAINFO, dirname(FILE)."/123.assam.gov.in.crt");
      curl_setopt($ch, CURLOPT_FAILONERROR, FALSE);
      curl_setopt($ch, CURLOPT_NOBODY,false);
      $result = curl_exec($ch);
      curl_close($ch);
      $res=explode("$",$result);//var_dump($res);die;
      if($res){
        $STATUS=$res[16];
        $GRN=$res[4];
      //  var_dump($STATUS);var_dump($GRN);die;
        if($STATUS === "Y"){
          $this->intermediator_model->update_payment_status($DEPARTMENT_ID,array("payment.GRN"=>$GRN,"payment.STATUS"=>$STATUS));
        }
      }
    }
      redirect(base_url('expr/transactions'));

  }
  public function cin_response(){

    $DEPARTMENT_ID=$_POST['DEPARTMENT_ID'];
    $STATUS=$_POST['STATUS'];
    $BANKCIN=$_POST['BANKCIN'];
    if($STATUS === "Y"){
      $this->intermediator_model->update_payment_status($DEPARTMENT_ID,array("payment.BANKCIN"=>$BANKCIN,"payment.STATUS"=>$STATUS));
    }
    redirect(base_url('expr/transactions'));

  }
  public function show_vahan_acknowledgment($data){
    $this->load->view('includes/frontend/header');
    $this->load->view('vahan_acknowledgment',$data);
    $this->load->view('includes/frontend/footer');
  }
  public function show_acknowledgment($data){
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
     if(!$this->validateResponse($response)){
       return;
     }
      if(isset($response->portal_no)){
          $data_to_update=array(
            "service_id"=>isset($response->service_id) ? $response->service_id : "",
            "app_ref_no"=>isset($response->app_ref_no) ? $response->app_ref_no : "",
            "status"=>isset($response->status) ? $response->status : "",
            "submission_date"=>isset($response->submission_date) ? $response->submission_date : "",
            "payment_mode"=>isset($response->payment_mode) ? $response->payment_mode : "",
            "payment_ref_no"=>isset($response->payment_ref_no) ? $response->payment_ref_no : "",
            "payment_status"=>isset($response->payment_status) ? $response->payment_status : "",
            "payment_date"=>isset($response->payment_date) ? $response->payment_date : "",
            "amount"=>isset($response->amount) ? $response->amount : "",
            "portal_no"=>isset($response->portal_no) ? $response->portal_no : "",
            "applicant_details"=>isset($response->applicant_details) ? $response->applicant_details : "",
            "application_details"=>isset($response->application_details) ? $response->application_details : ""
          );
          if($response->portal_no === 1){
            // for noc portal
            //// TODO:  update should be base on two params rtps_id,user_id
            $result=$this->intermediator_model->add_param($response->rtps_trans_id,$data_to_update);
           if($result->getMatchedCount()){
             $departmental_data=$this->portals_model->get_departmental_data($response->service_id);
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

           }else {
             $this->show_error();
           }

         }else {
           //for other portal
            //var_dump($data_to_update);die;
                  $departmental_data=$this->portals_model->get_departmental_data($response->service_id);
                  $intermediateID=$response->rtps_trans_id;
                  //// TODO:  update should be base on two params rtps_id,user_id
                  $result=$this->intermediator_model->add_param($response->rtps_trans_id,$data_to_update);
                 if($result->getMatchedCount()){
                   if(isset($response->status) && $response->status === "S"){
                     if(isset($departmental_data->is_payment_required) && $departmental_data->is_payment_required === "true"){
                       //go for payment
                       $data=array(
                         "DEPT_CODE"=>"IGR",
                         "OFFICE_CODE"=>"IGR013",
                         "REC_FIN_YEAR"=>"2020-2021",
                         "HOA1"=>"",
                         "FROM_DATE"=>"01/04/2020",
                         "TO_DATE"=>"31/03/2021",
                         "PERIOD"=>"A",
                         "CHALLAN_AMOUNT"=>"0",
                         "DEPARTMENT_ID"=>$intermediateID,
                         "MOBILE_NO"=>"9876543210",
                         "SUB_SYSTEM"=>"REV-SP|".base_url('expr/get/payment-response'),
                         "PARTY_NAME"=>"TEAM IGR",
                         "PIN_NO"=>"781005",
                         "ADDRESS1"=>"NIC DISPUR",
                         "ADDRESS2"=>"LAST GATE",
                         "ADDRESS3"=>"GUWAHATI",
                         "MULTITRANSFER"=>"Y",
                         "NON_TREASURY_PAYMENT_TYPE"=>"01",
                         "TOTAL_NON_TREASURY_AMOUNT"=>"30",
                         "AC1_AMOUNT"=>"30",
                         "ACCOUNT1"=>"IGR63222",
                       );

                       $data['status']=$status;
                       $data['response']=json_encode((array)$response);


                       $this->load->view('includes/frontend/header');
                       $this->load->view('res',$data);
                       $this->load->view('includes/frontend/footer');
                     }else {
                       //show acknowledgments
                       $data=array();
                       $data['response']=$response;
                       $data['timeline_days']=$departmental_data->timeline_days;
                       $data['department_name']=$departmental_data->department_name;
                       $data['service_name']=$departmental_data->service_name;
                        $this->show_acknowledgment($data);
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

}
