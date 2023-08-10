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
    //  $recived_data=$this->decrypt(array("result"=>$_GET['response']));
      parse_str( $recived_data, $array );
      $response=$array;
     // pre($response);
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
          //   $departmental_data=$this->portals_model->get_departmental_data($service_id);
            // $data=array();
             //$data['response']=$response;
             //$data['timeline_days']=$departmental_data->timeline_days;
          //   $data['department_name']=$departmental_data->department_name;
            // $data['service_name']=$departmental_data->service_name;
             if(isset($response['ApplicationStatus']) && $response['ApplicationStatus'] === "S"){
               redirect(base_url('iservices/admin/pfc-payment/').$rtps_trans_id);
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
  public function pfc_payment($data){
    //$data params should only rtps_trans_id then generate a new DEPARTMENT_ID for payment update againt rtps_trans_id along with payment request as below
    //find the necessary info from pfc profile or user profile
    $uniqid=uniqid();

    echo strtoupper($uniqid.time()); die;
    $data['department_data']=array(
      "DEPT_CODE"=>"IGR",
      "OFFICE_CODE"=>"IGR013",
      "REC_FIN_YEAR"=>"2020-2021",
      "HOA1"=>"",
      "FROM_DATE"=>"01/04/2020",
      "TO_DATE"=>"31/03/2021",
      "PERIOD"=>"A",// O for ontimee payment
      "CHALLAN_AMOUNT"=>"0",
      "DEPARTMENT_ID"=>"AS212201A5754013",//$data['rtps_id'],
      "MOBILE_NO"=>"9876543210",//pfc no
      "SUB_SYSTEM"=>"REV-SP|".base_url('iservices/admin/get/payment-response'),
      "PARTY_NAME"=>"TEAM IGR", //applicant name , address of client
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
    $this->load->view('includes/header');
    $this->load->view('admin/payment/pfc_payment',$data);
    $this->load->view('includes/footer');
  }

  public function update_pfc_payment_amount(){
    $param = $this->input->post("payment_params");
    parse_str( urldecode($param), $param_array );

    // is_numeric
      $no_printing_page=intval($this->input->post('no_printing_page'));
      $no_scanning_page=intval($this->input->post('no_scanning_page'));
      if(!empty($no_printing_page) && $no_printing_page < 0 ){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array("status"=>false,"message"=>"Number of page can not be a negative value")));
      }
      if(!empty($no_scanning_page) && $no_scanning_page < 0){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array("status"=>false,"message"=>"Number of page can not be a negative value")));
      }
      // if($no_printing_page < 0)
      $rtps_trans_id=$this->input->post('rtps_trans_id');
      $data_to_update=array("no_printing_page"=>$no_printing_page,
                            "no_scanning_page"=>$no_scanning_page,
                            'service_charge'=>$this->config->item('service_charge'),
                            'scanning_charge_per_page'=>$this->config->item('scanning_charge'),
                            'printing_charge_per_page'=>$this->config->item('printing_charge'),
                            'department_id'=>$param_array['DEPARTMENT_ID'],
                            'payment_params'=>$param_array);

      $result=$this->intermediator_model->update_payment_status($rtps_trans_id,$data_to_update);
         if($result->getMatchedCount()){
           $this->load->model('admin/pfc_payment_history_model');
           $data_to_update['rtps_trans_id']=$rtps_trans_id;
           $data_to_update['createdDtm']=new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
           $this->pfc_payment_history_model->insert($data_to_update);
           $status["status"] = true;
           return $this->output
               ->set_content_type('application/json')
               ->set_status_header(200)
               ->set_output(json_encode($status));
         }else {
           $status["status"] = false;
           return $this->output
               ->set_content_type('application/json')
               ->set_status_header(200)
               ->set_output(json_encode($status));
         }
  }
  public function update_retry_pfc_payment_amount(){
    $param = $this->input->post("payment_params");
    parse_str( urldecode($param), $param_array );
      $rtps_trans_id=$this->input->post('rtps_trans_id');
      $data_to_update=array(
                            'department_id'=>$param_array['DEPARTMENT_ID'],
                            'payment_params'=>$param_array);

      $result=$this->intermediator_model->update_payment_status($rtps_trans_id,$data_to_update);
         if($result->getMatchedCount()){
           $this->load->model('admin/pfc_payment_history_model');
           $data_to_update['rtps_trans_id']=$rtps_trans_id;
           $data_to_update['createdDtm']=new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
           $this->pfc_payment_history_model->insert($data_to_update);
           $status["status"] = true;
           return $this->output
               ->set_content_type('application/json')
               ->set_status_header(200)
               ->set_output(json_encode($status));
         }else {
           $status["status"] = false;
           return $this->output
               ->set_content_type('application/json')
               ->set_status_header(200)
               ->set_output(json_encode($status));
         }
  }
  public function payment_response(){
    $DEPARTMENT_ID=$this->input->post('DEPARTMENT_ID');
    $response=$_POST;
    $this->intermediator_model->update_row(array('department_id'=>$DEPARTMENT_ID),array("pfc_payment_status"=>$_POST['STATUS'],"pfc_payment_response"=>$response));
    if($_POST['STATUS'] === 'Y'){
      //check the grn for valid transactions
      if($this->checkgrn($DEPARTMENT_ID,true) === $_POST['GRN']){
        redirect(base_url('iservices/admin/get-acknowledgement?app_ref_no=').$DEPARTMENT_ID);
      }else {
        //grn does not match Something went wrong
        echo "Something wrong in middle";
        $this->intermediator_model->update_row(array('department_id'=>$DEPARTMENT_ID),array("pfc_payment_status"=>"P","pfc_payment_response.STATUS"=>'P'));
        $this->show_error();
      }

    //  $this->show_vahan_acknowledgment($DEPARTMENT_ID);
    }else {
        $this->show_error();
    }
  }

  public function acknowledgement(){

    if(empty($_GET['app_ref_no'])){
      redirect(base_url("iservices/admin/my-transactions"));
    }
    if(!empty($this->session->userdata('role'))){
      $is_kiosk = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
    }else{
      $is_kiosk = false;
    }
    
    $app_ref_no=$_GET['app_ref_no'];
   

    if($is_kiosk && ($is_kiosk === "PFC")){
      $application_details=$this->intermediator_model->get_application_details(array("department_id"=>$app_ref_no,
      "applied_by"=>new MongoDB\BSON\ObjectId($this->session->userdata("userId")->{'$id'}),"pfc_payment_status"=>"Y"));
    }else if($is_kiosk && ($is_kiosk === "CSC")){
      $application_details=$this->intermediator_model->get_application_details(array("department_id"=>$app_ref_no,
      "applied_by"=>$this->session->userdata('userId'),"pfc_payment_status"=>"Y"));
    }else{
      $application_details = $this->intermediator_model->get_application_details(array("department_id" => $app_ref_no,'mobile'=>$this->session->userdata('mobile')));
    }

    if($application_details->service_id)
      $departmental_data=$this->portals_model->get_departmental_data($application_details->service_id);
    else
      redirect('iservices/admin/my-transactions');
    $data=array();
    $data['timeline_days']=$departmental_data->timeline_days;
    $data['department_name']=$departmental_data->department_name;
    $data['service_name']=$departmental_data->service_name;
    $data['back_to_dasboard']='<a href="'.base_url('iservices/admin/my-transactions').'" class="btn btn-primary mb-2"  >Back To DASHBOARD</a>';
    if($application_details->portal_no === "2" || $application_details->portal_no === 2) {
      //for vahan only
      $data['response']=(array)$application_details;
      $this->load->view('includes/frontend/header');
      $this->load->view('vahan_acknowledgment',$data);
      $this->load->view('includes/frontend/footer');
    }elseif($application_details->portal_no === "4" || $application_details->portal_no === 4){
      $data['response']=$application_details;
      $this->load->view('includes/frontend/header');
      $this->load->view('sarathi/ack',$data);
      $this->load->view('includes/frontend/footer');
    }
    else {
      $data['response']=$application_details;
      $this->load->view('includes/frontend/header');
      $this->load->view('noc_ack1',$data);
      $this->load->view('includes/frontend/footer');
    }


  }
  public function grn_response(){

    $DEPARTMENT_ID=$_POST['DEPARTMENT_ID'];
    $STATUS=$_POST['STATUS'];
    $GRN=$_POST['GRN'];
    if($STATUS === "Y"){
      $this->intermediator_model->update_payment_status($DEPARTMENT_ID,array("pfc_payment_response.GRN"=>$GRN,
                                                                              "pfc_payment_response.STATUS"=>$STATUS,
                                                                              "pfc_payment_response.TAXID"=>$_POST['TAXID'],
                                                                              "pfc_payment_response.PRN"=>$_POST['PRN'],
                                                                              "pfc_payment_response.TRANSCOMPLETIONDATETIME"=>$_POST['TRANSCOMPLETIONDATETIME'],
                                                                              'pfc_payment_status'=>$STATUS));
    }
    redirect(base_url('iservices/admin/my-transactions'));
  }
  public function checkgrn($DEPARTMENT_ID=null,$check=false){ // TODO: need to check which are params to update
    $transaction_data=$this->intermediator_model->get_row(array('department_id'=>$DEPARTMENT_ID));
    if($DEPARTMENT_ID){
      $OFFICE_CODE=$transaction_data->payment_params->OFFICE_CODE;
      $am1=isset($transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
      $am2=isset($transaction_data->payment_params->CHALLAN_AMOUNT) ? $transaction_data->payment_params->CHALLAN_AMOUNT : 0;
      $AMOUNT=$am1+$am2;
      $string_field="DEPARTMENT_ID=".$DEPARTMENT_ID."&OFFICE_CODE=".$OFFICE_CODE."&AMOUNT=".$AMOUNT;
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
      $res=explode("$",$result);//pre($res);
      if($check){
        return isset($res[4])?$res[4]:'';
      }
      if($res){
        $STATUS= isset($res[16])?$res[16]:'';
        $GRN= isset($res[4])?$res[4]:'';
      //  var_dump($STATUS);var_dump($GRN);die;
        //if($STATUS === "Y"){
          $this->intermediator_model->update_row(array('department_id'=>$DEPARTMENT_ID),
          array(
          "pfc_payment_response.GRN"=>$GRN,
          "pfc_payment_response.AMOUNT"=>isset($res[6])?$res[6]:'',
          "pfc_payment_response.PARTYNAME"=>isset($res[18])?$res[18]:'',
          "pfc_payment_response.TAXID"=>isset($res[20])?$res[20]:'',
          "pfc_payment_response.DEPARTMENT_ID"=>isset($res[2])?$res[2]:'',
          "pfc_payment_response.BANKNAME"=>isset($res[22])?$res[22]:'',
          "pfc_payment_response.BANKCODE"=>isset($res[8])?$res[8]:'',
          "pfc_payment_response.ENTRY_DATE"=>isset($res[24])?$res[24]:'',
          "pfc_payment_response.STATUS"=>$STATUS,
          "pfc_payment_response.PRN"=>isset($res[12])?$res[12]:'',
          "pfc_payment_response.TRANSCOMPLETIONDATETIME"=>isset($res[14])?$res[14]:'',
          "pfc_payment_response.BANKCIN"=>isset($res[10])?$res[10]:'',
          'pfc_payment_status'=>$STATUS));
      //  }
      }
    }
      redirect(base_url('iservices/admin/my-transactions'));

  }
  public function cin_response(){
    if(!empty($_POST)){
      if(!empty($_POST['DEPARTMENT_ID'])){
        $DEPARTMENT_ID=$_POST['DEPARTMENT_ID'];
        $STATUS=$_POST['STATUS'];
        $BANKCIN=$_POST['BANKCIN'];
        $this->intermediator_model->update_row(array('department_id'=>$DEPARTMENT_ID),array("pfc_payment_response.BANKCIN"=>$BANKCIN,
                                                                                "pfc_payment_response.STATUS"=>$STATUS,
                                                                                "pfc_payment_response.TAXID"=>$_POST['TAXID'],
                                                                                "pfc_payment_response.PRN"=>$_POST['PRN'],
                                                                                "pfc_payment_response.TRANSCOMPLETIONDATETIME"=>$_POST['TRANSCOMPLETIONDATETIME'],
                                                                                'pfc_payment_status'=>$STATUS));
      }

    }

    redirect(base_url('iservices/admin/my-transactions'));

  }
  public function show_vahan_acknowledgment($data){
    $this->load->view('includes/header');
    $this->load->view('vahan_acknowledgment',$data);
    $this->load->view('includes/footer');
  }
  public function show_acknowledgment($data){
    $this->load->view('includes/frontend/header');
    $this->load->view('noc_ack1',$data);
    $this->load->view('includes/frontend/footer');
  }
  public function show_error(){
    $this->load->view('includes/header');
    $this->load->view('error');
    $this->load->view('includes/footer');
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
            // "service_id"=>isset($response->service_id) ? $response->service_id : "",
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
           // "portal_no"=>isset($response->portal_no) ? $response->portal_no : "",
            "applicant_details"=>isset($response->applicant_details) ? $response->applicant_details : "",
            "application_details"=>isset($response->application_details) ? $response->application_details : "",
            "payment_details"=>isset($response->payment_details) ? $response->payment_details : ""
          );
          $result=$this->intermediator_model->add_param($response->rtps_trans_id,$data_to_update);
           if($result->getMatchedCount()){
             if(isset($response->status) && $response->status === "S"){
               //check new application or old
                $transaction_data = $this->intermediator_model->get_row(array('rtps_trans_id' =>$response->rtps_trans_id));

                if($response->portal_no === 1 || $response->portal_no === "1"){
                          if(property_exists($transaction_data ,'payment_rtps_end') && $transaction_data->payment_rtps_end){
                            //payment is required
                            redirect(base_url('iservices/rtpspayment/payment/'.$response->rtps_trans_id));
                                  
                            }else{
                              redirect(base_url('iservices/admin/pfc-payment/').$response->rtps_trans_id);
                            }
                }else{
                      redirect(base_url('iservices/admin/pfc-payment/').$response->rtps_trans_id);
                }
           
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

  private function store_response_dump($rtps_trans_id,$response,$encryption_response){
      $this->mongo_db->insert("response_history",array(
        'rtps_trans_id'=>$rtps_trans_id,
        'response'=>$response,
        'encResponse'=>$encryption_response,
        'createdDtm'=>new MongoDB\BSON\UTCDateTime(microtime(true) * 1000)
      ));
  }
}
