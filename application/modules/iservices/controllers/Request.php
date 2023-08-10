<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Request extends Frontend
{

  public function __construct()
  {
    parent::__construct();
      $this->load->model('intermediator_model');
      $this->load->library('AES');
      $this->config->load('rtps_services');
  }

  public function create(){
    $encryption_key = $this->config->item("encryption_key");
    $data=array("pageTitle" => "Request");
    $request_data=$_POST;
 
    if($request_data){
      $input = ($request_data['data']);
    }
    // var_dump($data['data']);die;
    if(!empty($input)){
      //decrypt the user input

      $aes = new AES($input, $encryption_key);
      $desc_response = $aes->decrypt();
     
      $get_array=json_decode($desc_response);//var_dump($get_array->rtps_trans_id);die;
      $this->submit( $get_array);
      return;
      $data['return_url']=$get_array->response_url;
      $data['DEPARTMENT_ID']=$get_array->rtps_trans_id;
    }

    $this->load->view('request',$data);

  }
  public function submit($data){
    $data = (array) $data;
    $encryption_key = $this->config->item("encryption_key");
    $return_url=$data['response_url'];
    $userDetails=array();
    array_push($userDetails,
    array(
      "gender"=>"male",
      "e-mail"=>"promit_2480@testmail.com",
      "applicant_name"=>"Pritish Ranjan Barman",
      "fathers_name"=>"Lt Pulin Barman",
      "mobile_number"=>"9864098640",
      "address_line_1"=>"Vill-Gadain Raji",
      "address_line_2"=>"Town- Haflong",
      "country"=>"India",
      "state"=>"ASSAM",
      "district"=>"Kamrup",
      "pin_code"=>"788819"
    )
  );
    $output=array(
      "portal_no"=>$data['portal_no'],
      "rtps_trans_id"=>$data['rtps_trans_id'],
      "service_id"=>$data['service_id'],
      "app_ref_no"=>"RTPS-".uniqid(),
      "submission_date"=>"24/10/2021",
      "user_id"=>$data['user_id'],
      "payment_mode"=>"online",
      "payment_ref_no"=>"",
      "payment_date"=>"",
      "amount"=>"500.00",
      'status'=>"S",
      "applicant_details"=>$userDetails,
      "application_details"=>[],

    );
    $input=json_encode(array("response_data"=>$output,'status'=>"S"));
    //$this->intermediator_model->update_payment_status($DEPARTMENT_ID,array("status"=>$status));
    $aes = new AES($input, $encryption_key);

  $enc = $aes->encrypt();
  if($return_url){
    $data['action']=$return_url;
    $data['data']=$enc;
    $this->load->view('retry',$data);
  }

  }
  public function action($rtps_id){
    $encryption_key = $this->config->item("encryption_key");
    $status="S";//$this->input->post("btn");//$_POST["mybutton"]
    // $return_url=$this->input->post("return_url");
    $return_url=base_url('iservices/admin/Transoprt_response/create_response_new');
    // $DEPARTMENT_ID=$this->input->post("DEPARTMENT_ID");

            $userDetails=array();
            $payment_details[]=array(
          'DEPT_CODE'=>'LRS',
          'OFFICE_CODE' =>  'LRS317',
          'REC_FIN_YEAR' =>  '2022-2023',
          'FROM_DATE' =>  '01/04/2022',
          'TO_DATE' =>  '31/03/2099' ,
          'PERIOD' =>  'O' ,
          'TAX_ID' =>  'tin123' ,
          'PAN_NO' =>  '          ' ,
          'PARTY_NAME' =>  'dfgfd' ,
          'ADDRESS1' =>  'ggf' ,
          'ADDRESS2' =>  'hfghgf' ,
          'ADDRESS3' =>  'fghghf',
          'PIN_NO' =>  '781003',
          'MOBILE_NO' =>  '9435347177     ' ,
          'REMARKS' =>  'Purpose of challan',
          'FORM_ID' =>  '' ,
          'PAYMENT_TYPE' =>  '03' ,
          'TREASURY_CODE' =>  'AKM',
          'MAJOR_HEAD' =>  '0070' ,
          'AMOUNT1' =>  '500.00' ,
          'HOA1' =>  '0070-00-800-0000-000-01' ,
          'AMOUNT2' =>  '' ,
          'HOA2' =>  '' ,
          'AMOUNT3' =>  '' ,
          'HOA3' =>  '',
          'AMOUNT4' =>  '',
          'HOA4' =>  '',
          'AMOUNT5' =>  '',
          'HOA5' =>  '' ,
          'AMOUNT6' =>  '',
          'HOA6' =>  '',
          'AMOUNT7' =>  '' ,
          'HOA7' =>  '' ,
          'AMOUNT8' =>  '' ,
          'HOA8' =>  '' ,
          'AMOUNT9' =>  '' ,
          'HOA9' =>  '' ,
          'CHALLAN_AMOUNT' =>  '500.00',
          'responseurl' =>  'https://ilrms.nic.in/noctest/index.php/usercontrol/rtpspayresponse' 
            );
            array_push($userDetails,
            array(
              "gender"=>"male",
              "e-mail"=>"promit_2480@testmail.com",
              "applicant_name"=>"Pritish Ranjan Barman",
              "fathers_name"=>"Lt Pulin Barman",
              "mobile_number"=>"9864098640",
              "address_line_1"=>"Vill-Gadain Raji",
              "address_line_2"=>"Town- Haflong",
              "country"=>"India",
              "state"=>"ASSAM",
              "district"=>"Kamrup",
              "pin_code"=>"788819"
            )
          );
          $application_details=array(
            "slno"=>"445454",
          );
          array_push($application_details,array(
            "land"=>"dksh"
          ));
            $output=array(
              "portal_no"=>"1",
              "rtps_trans_id"=>$rtps_id,
              "service_id"=>"11",
              "app_ref_no"=>"NOC/07/33797/2022",
              "submission_date"=>"24/10/2021",
              "user_id"=>"999999999",
              "payment_mode"=>"online",
              "payment_ref_no"=>"",
              "payment_date"=>"",
              "amount"=>"500.00",
              "applicant_details"=>$userDetails,
              "application_details"=>$application_details,
              "payment_details"=>$payment_details

            );
            if($status === "S"){
                $output["status"]="S";
                $input=json_encode(array("response_data"=>$output,'status'=>"S"));
            }elseif ($status === "P") {
                $output["status"]="P";
                $input=json_encode(array("response_data"=>$output,'status'=>"P"));
            }else {
                $output["status"]="F";
                $input=json_encode(array("response_data"=>$output,'status'=>"F"));
            }
            //$this->intermediator_model->update_payment_status($DEPARTMENT_ID,array("status"=>$status));
            $aes = new AES($input, $encryption_key);

          $enc = $aes->encrypt();
          //var_dump($enc);die;
          if($return_url){
          //  $return_url=$return_url."?data=".urlencode($enc)."&DEPARTMENT_ID=".$DEPARTMENT_ID;
            //var_dump($return_url);die;
            //redirect($return_url);
            $data['action']=$return_url;
            $data['data']=$enc;
            $this->load->view('retry',$data);
          }


  }
  public function add(){
    $this->load->model('admin/pfc_payment_history_model');
    $data_to_save=array();
    $address=array();
    array_push($address,array(
      'address_line_1'=>"addr1",
      'address_line_2'=>"addr2",
      'pin'=>"787878",
    ));
    array_push($address,array(
      'address_line_1'=>"guwhati",
      'address_line_2'=>"dispur",
      'pin'=>"787101",
    ));
    $data_to_save['name']="John";
    $data_to_save['phone']="9898989898";
    $data_to_save['address']=$address;
    $this->pfc_payment_history_model->insert($data_to_save);
  }
  public function send_sms(){
    $number="9742447516";
    $message_body="Your test OPT is 123456";
      if ($number != "" && !empty($number) && $message_body != "" && !empty($message_body)) {
        $msisdn = intval($number);
        $msg = urlencode($message_body);
        $smsGatewayUrl="https://smsgw.sms.gov.in/failsafe/MLink?username=rtpsasm.otp&pin=P4%26fO3%23xF4&message=".$msg."&mnumber=".$msisdn."&signature=ARTPSA&dlt_entity_id=1001367290000017171&dlt_template_id=1007160707760375551";
      //  pre( $url);
        //$smsGatewayUrl = "https://smsgw.sms.gov.in/failsafe/MLink?username=rtpsasm.otp&password=P4&fO3#xF4&signature=ARTPSA&to=" . $msisdn . "&msg=" . $msg . "";
        $url = $smsGatewayUrl;
        $ch = curl_init();                       // initialize CURL
        curl_setopt($ch, CURLOPT_POST, false);    // Set CURL Post Data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);                         // Close CURL
        pre($output);
        
    } else {
        echo "no number found";
    }
    
  }
  public function url_replace(){
    $str_to_insert='admin/';
    $url="https://localhost/rtps/iservices/sarathi/guidelines/2/2";
    $newstr = str_replace("iservices","iservices/admin",$url);
    echo $newstr;
  }

  public function dec(){
    $encryption_key = $this->config->item("encryption_key");
    // pre($encryption_key);
    // $input="AMNRaPa38i5fz39donI93gmK2mPck3viYONmOwL1nfGP6JbzFVXtQXWrTORR1WP34MfDNEZW6iRTimCS9eK8lXoI56NshSeA4Z4z/Ikcfd8V3wr3xii0FfURtwg+ZS/ZBoKci+Id9XbBoGHhfMJElnUT8TkTI2Ax6tpRJQ4BYJKHBMbI2IgqwyNb5Z5XWIwVIj/M+N/1Vt5YIvNeR7vyIz+s8eJWZOe9ivcZMTWrihewigwcxVrEQf9xJTGIGMa4wiAy4XG7DKvtCYLkujA5nmOLfuQJE+8QjtzI5afOKAUCVhjAPTq4NGJq0bK2hkHuUdih3CZy40L2X0+NPJfqTQ==";
    $input="AMNRaPa38i5fz39donI93lU0ecQylZVEIXNksZjWRcQ9h31yUwbUo119o%2ByWLiqMo2%2Bxp2rndBFTtkYLfZVGNWj2D4DUB7Oe8BrHTRjsPvc6WDERkeVmsXvFyYBx021I26Au5iAZTITd5r5uAdg96S2wHEUEz4tbszpdXaCvER7brCHOZrcbxevLqNm8KYOtkp%2BhNXNADyK1kc31YqWB8UqWMtEmXpO1WCFfVOrTPCjAvUrf%2FD4OX%2BWmrhsILH4DdlQhf1skciVb%2BA7LB2SrVBK1KdyNcZFxC7zW%2By1XOuBpD2LBmLbzrcXSzzAi8J%2Bb897fO1l01WFjSPUwoAPFnw%3D%3D";
  //  urldecode
    $aes = new AES(urldecode($input), $encryption_key);
    $desc_response = $aes->decrypt();
    $response=json_decode($desc_response);
    pre($response);
    $get_array=json_decode($desc_response);//var_dump($get_array->rtps_trans_id);die;
    pre($get_array);
  }




  public function umang($number=false){
    $this->load->library('AESrathi');
  


    $encryption_key = $this->config->item("agencyKey");
    $status="S";
    $return_url=base_url('spservices/umang/payment/request');
      if(!$number){
        $number=uniqid();
      }    
    $payment_details=array(
      'DEPT_CODE'=>'ARI',
      'OFFICE_CODE' =>  'ARI000',
      'TAX_ID' =>  '' ,
      'PAN_NO' =>  '          ' ,
      'PARTY_NAME' =>  'Rohit' ,
      'ADDRESS1' =>  'Guwahati' ,
      'ADDRESS2' =>  '' ,
      'ADDRESS3' =>  '',
      'PIN_NO' =>  '781003',
      'MOBILE_NO' =>  '9435347177     ' ,
      'REMARKS' =>  '',
      'FORM_ID' =>  '' ,
      'PAYMENT_TYPE' =>  '' ,
      'TREASURY_CODE' =>  '',
      'MAJOR_HEAD' =>  '' ,
      'AMOUNT1' =>  '' ,
      'HOA1' =>  '' ,
      'AMOUNT2' =>  '' ,
      'HOA2' =>  '' ,
      'AMOUNT3' =>  '' ,
      'HOA3' =>  '',
      'AMOUNT4' =>  '',
      'HOA4' =>  '',
      'AMOUNT5' =>  '',
      'HOA5' =>  '' ,
      'AMOUNT6' =>  '',
      'HOA6' =>  '',
      'AMOUNT7' =>  '' ,
      'HOA7' =>  '' ,
      'AMOUNT8' =>  '' ,
      'HOA8' =>  '' ,
      'AMOUNT9' =>  '' ,
      'HOA9' =>  '' ,
      'CHALLAN_AMOUNT' =>  '0'
        );
            $output=array(
              "ep_token_number"=>$number,
              "return_url"=>"https://umang/cal_back",
              "payment_config"=>$payment_details

            );
           
            
            $input=json_encode($output);
           
            //$this->intermediator_model->update_payment_status($DEPARTMENT_ID,array("status"=>$status));
            $aes = new AES($input, $encryption_key);

          $enc = $aes->encrypt();
          // var_dump($enc);die;
          if($return_url){
          //  $return_url=$return_url."?data=".urlencode($enc)."&DEPARTMENT_ID=".$DEPARTMENT_ID;
            //var_dump($return_url);die;
            //redirect($return_url);
            $data['action']=$return_url;
            $data['data']=$enc;
            $this->load->view('retry',$data);
          }


  }

  public function umang_new($number=false){
    $this->load->library('AESrathi');
    $encryption_key = $this->config->item("agencyKey");
    $status="S";
    $return_url=base_url('spservices/umang/payment/request');
    $umang_callback=base_url('iservices/request/umang_callback');
      if(!$number){
        $number="6476f7f428b723c95b1c24eb";
      }    
    
            $output=array(
              "rtps_token_number"=>$number,
              "return_url"=>$umang_callback,
            );
           
            
            $input=json_encode($output);
          // pre($input);
            //$this->intermediator_model->update_payment_status($DEPARTMENT_ID,array("status"=>$status));
            $aes = new AES($input, $encryption_key);

          $enc = $aes->encrypt();
          // var_dump($enc);die;
          if($return_url){
          //  $return_url=$return_url."?data=".urlencode($enc)."&DEPARTMENT_ID=".$DEPARTMENT_ID;
            //var_dump($return_url);die;
            //redirect($return_url);
            $data['action']=$return_url;
            $data['data']=$enc;
            $this->load->view('retry',$data);
          }


  }

  public function umang_callback(){
    $this->load->library('AESrathi');
    $encryption_key = $this->config->item("agencyKey");
    $status="S";
  
    $aes = new AES($_POST['data'], $encryption_key);

    $dec = $aes->decrypt();
    pre(( $dec));

  }



}
