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
      $data['return_url']=$get_array->response_url;
      $data['DEPARTMENT_ID']=$get_array->rtps_trans_id;
    }

    $this->load->view('request',$data);

  }
  public function action(){
    $encryption_key = $this->config->item("encryption_key");
    $status=$this->input->post("btn");//$_POST["mybutton"]
    $return_url=$this->input->post("return_url");
    $DEPARTMENT_ID=$this->input->post("DEPARTMENT_ID");

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
          $application_details=array(
            "slno"=>"445454",
          );
          array_push($application_details,array(
            "land"=>"dksh"
          ));
            $output=array(
              "portal_no"=>3,
              "rtps_trans_id"=>$DEPARTMENT_ID,
              "service_id"=>10,
              "app_ref_no"=>"PAY/KAM/2020/123",
              "submission_date"=>"2020-09-16",
              "user_id"=>"9864098640",
              "payment_mode"=>"online",
              "payment_ref_no"=>"",
              "payment_date"=>"",
              "amount"=>"30",
              "applicant_details"=>$userDetails,
              "application_details"=>$application_details

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


}
