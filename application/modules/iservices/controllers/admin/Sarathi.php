<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Sarathi extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('intermediator_model');
        $this->load->model('portals_model');
        $this->config->load('rtps_services');
        $this->load->library('AESrathi');
        $this->encryption_key=$this->config->item("agencyKey");
        $this->load->library('form_validation');
    }

    public function index()
    {


    }

    public function guidelines($service_id=null,$portal_no=null)
    {

      if(empty($service_id) || empty($portal_no)){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'response_data' => "Invalid service",

            )));
            return;
      }
      $data=array("pageTitle" => "Guidelines");
      $data['service_id']=$service_id;
      $data['portal_no']=$portal_no;

      $guidelines=$this->portals_model->get_guidelines($service_id);
     
      if($guidelines){
        $data['service_name']=$guidelines->service_name;
        $data['guidelines']=isset($guidelines->guidelines) ? $guidelines->guidelines : array();
        $data['url']=isset($guidelines->url) ? $guidelines->url : '';


        $this->load->view('includes/header');
        $this->load->view('admin/sarathi_guidelines',$data);
        $this->load->view('includes/footer');
      }else {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'response_data' => "Invalid service",

            )));
            return;
      }


    }
    function generateRandomString($length = 7) {
        $number = '';
        for ($i = 0; $i < $length; $i++){
            $number .= rand(0,9);
            }
            return (int)$number;
    }

    public function generate_id(){
    $date=date('ydm');
    $str="AS".$date."S".$this->generateRandomString(7);
    return $str;
    }

    public function proceed(){

     $status=array();
     $service_id=$this->input->post('service_id');
   
     $mobile=$this->input->post('user_mobile');
     $applicant_name=$this->input->post('applicant_name');
     $address1=$this->input->post('address1');
     $address2=$this->input->post('address2');
     $address3=$this->input->post('address3');
     $pin_code=$this->input->post('pin_code');

     $this->form_validation->set_rules('user_mobile', 'user_mobile', 'required|trim');
     $this->form_validation->set_rules('applicant_name', 'applicant_name', 'required|trim');
     $this->form_validation->set_rules('address1', 'address1', 'required|trim');
     $this->form_validation->set_rules('address2', 'address2', 'required|trim');
     $this->form_validation->set_rules('pin_code', 'pin_code', 'required|trim');

       if ($this->form_validation->run() == FALSE) {
         $status["status"] = false;
         $status["error_msg"] = "Please enter input for all require fields";
         return $this->output
           ->set_content_type('application/json')
           ->set_status_header(200)
           ->set_output(json_encode($status));
       }
       $service_details=$this->portals_model->get_guidelines($service_id);
       if(empty($service_details)){
         return false;
       }
       $external_service_id=property_exists($service_details,"external_service_id") ? $service_details->external_service_id : $service_details->service_id;
      $user=$this->session->userdata();

        $status['agId']=$this->config->item('agId');
        $status['agencyPassword']=$this->config->item('agencyPassword');


        $rtps_trans_id=$this->generate_id();
        A1:
        if($this->intermediator_model->is_exist_transaction_no($rtps_trans_id)){
          $rtps_trans_id=$this->generate_id();
          goto A1;
        }
        $status['tkNo']=$rtps_trans_id;
        // agCd = MD5 Digest (agencyPassword + tkNo)
        $status['agCd']=MD5($status['agencyPassword'].$rtps_trans_id);
        $status['kioskId']="1234";
        $status['serCd']=$external_service_id;//$service_id;


      $user_data=array(
        'user_id'=>$user['role'] ==="csc" ? $user['userId']:$user['userId']->{'$id'},

        'mobile'=>$mobile,
        'applicant_name'=>$applicant_name,
        'address1'=>$address1,
        'address2'=>$address2,
        'address3'=>$address3,
        'pin_code'=>$pin_code,

        'rtps_trans_id'=>$rtps_trans_id,
        "service_name"=>$this->input->post('service_name',true),
        "portal_no"=>$this->input->post('portal_no',true),
        "service_id"=>intval($service_id),
        'target_url'=>$this->input->post('url',true),
        'agId'=>$status['agId'],
        'tkNo'=>$status['tkNo'],
        'agCd'=>$status['agCd'],
        'serCd'=>$status['serCd'],
        'kioskId'=>$status['kioskId'],
        "status"=>"",
        "kiosk_type"=> $user['role'] ==="csc" ? "CSC" : "PFC",
        "applied_by"=>$user['role'] ==="csc" ? $user['userId']:new ObjectId($this->session->userdata('userId')->{'$id'}),
        "createdDtm"=> new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
      );
      $res=$this->intermediator_model->insert($user_data);

      if($res){
          $status["status"] = true;
          $status["message"] = "Need to redirect to third party urls";
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


    public function acknowledgement(){

      if(empty($_GET['app_ref_no'])){
        redirect(base_url("iservices/admin/my-transactions"));
      }

      $app_ref_no=$_GET['app_ref_no'];
      $application_details=$this->intermediator_model->get_application_details(array("department_id"=>$app_ref_no));
      // var_dump($application_details);die;
      if($application_details->service_id)
        $departmental_data=$this->portals_model->get_departmental_data($application_details->service_id);
      else
        redirect('iservices/admin/my-transactions');
      $data=array();
      $data['response']=$application_details;
      $data['timeline_days']=$departmental_data->timeline_days;
      $data['department_name']=$departmental_data->department_name;
      $data['service_name']=$departmental_data->service_name;

      $this->load->view('includes/header');
      $this->load->view('sarathi/ack',$data);
      $this->load->view('includes/footer');

    }


    public function retry($rtps_trans_id,$service_id,$portal_no){
      
      if($rtps_trans_id && $service_id){
        $user=$this->session->userdata();
        $request=$this->intermediator_model->get_transaction_detail($rtps_trans_id);
        $agencyPassword=$this->config->item('agencyPassword');
       
        if(!empty($request)){
          if(empty($request->app_ref_no)){
            // $appNo=$this->checkApplicationNoGenerated($rtps_trans_id,$request->agId,$request->agCd);
            $appNo=$this->get_application_details($rtps_trans_id);
          }else{
            $appNo=$request->app_ref_no;
          }
        
          $data=array(
            'action'=>$request->target_url,
            'agId'=>$request->agId,
            'agencyPassword'=>$agencyPassword,
            'tkNo'=>$request->tkNo,
            'agCd'=>$request->agCd,
            'serCd'=>$request->serCd,
            'kioskId'=>isset($request->kioskId)?$request->kioskId:'123',
            'sarAppl'=>!empty($appNo)? $appNo : "",
          );
          $this->load->view("sarathi/retry",$data);
        }else{
          redirect(base_url("iservices/admin/my-transactions"));
        }
      }else {
        redirect(base_url("iservices/admin/my-transactions"));
      }


    }

    private function checkApplicationNoGenerated($rtps_trans_id,$agId,$agCd){
      $appNo=null;
      $getSarApplNoUrl=$this->config->item('getSarApplNoUrl');
      $reqString = trim($agId."|".$agCd."|".$rtps_trans_id);
      // $checkSum=MD5($reqString);
      $aes = new AESrathi($reqString, $this->encryption_key,128);
       $enc = $aes->encrypt();
      // pre ($enc);

       $getSarApplNoUrl .="?encData=".urlencode($enc)."&agencyCode=".$agId;
       $curl = curl_init($getSarApplNoUrl);
       curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
       curl_setopt($curl, CURLOPT_POST, true);
      //  curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       $response = curl_exec($curl);
       curl_close($curl);

       if($response){
        $aes->setData($response);
        $dec = $aes->decrypt();
        if(!empty($dec)){
          $result=explode("|",$dec);
          if($result[0] == 0){
            $tkNo=$result[2];
            $appNo=$result[3];
            $this->intermediator_model->add_param($tkNo,array('app_ref_no'=>$appNo));
            return $appNo;
          }
        }

       }
       return $appNo;
      // $this->load->view("sarathi/get_application_no",$data);

    }

    private function get_application_details($rtps_trans_id){
      $appNo=null;
      if(!empty($rtps_trans_id)){
        $getSarApplDetUrl=$this->config->item("getSarApplDetUrl");
        $agId=$this->config->item("agId");
        $agencyPassword=$this->config->item("agencyPassword");
        $agCd=MD5($agencyPassword.$rtps_trans_id);
        $reqString = trim($agId."|".$agCd."|".$rtps_trans_id);
        $aes = new AESrathi($reqString, $this->encryption_key,128);
        $enc = $aes->encrypt();
         $getSarApplDetUrl .="?encData=".urlencode($enc)."&agencyCode=".$agId;
        //  $curl = curl_init($getSarApplDetUrl);
        //  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        //  curl_setopt($curl, CURLOPT_POST, true);
        // //  curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        //  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //  $response = curl_exec($curl);
        //  curl_close($curl);

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $getSarApplDetUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;
     //   pre($response);
         if(!empty($response) && $response !="Some Exception Occurs" ){
          $aes->setData($response);
          $dec = $aes->decrypt();
          if(!empty($dec)){
            $result=explode("|",$dec);
            $status=explode("@",$result[9]);
            $st="";
            if(!empty($status)){
              if($status[0]==="Pending"){
                $st="P";
              }elseif($status[0]==="Approved"){
                $st="S";
              }elseif($status[0]==="Rejected"){
                $st="R";
              }else{
                $st="";
              }
            }
            $appNo=$result[2];
           // pre( $status );
            if($result[0] == 0){

                  $applicant_details=array();
                  array_push($applicant_details,
                  array(
                    "applicant_name"=>$result[4]." ".$result[5]." ".$result[6] ,
                    "applicant_first_name"=>$result[4],
                    "applicant_middle_name"=>$result[5],
                    "applicant_last_name"=>$result[6],
                    "applicant_date_of_birth"=>$result[8]
                    )
                );
                $appDetails=array(
                  "app_ref_no"=>$result[2], //application no
                  "submission_date"=>$result[3], //application_date
                  // "applicant_first_name"=>$result[4],
                  // "applicant_middle_name"=>$result[5],
                  // "applicant_last_name"=>$result[6],
                  "applicant_name"=>$result[7],
                  // "applicant_date_of_birth"=>$result[8],
                  "status"=>$st,
                  "transaction_name"=>!empty( $status) ? $status[2]:'',
                  "transaction_abbreviation"=>!empty( $status) ? $status[3]:'',
                  "class_of_vehicle_name"=>!empty( $status) ? $status[4]:'',
                  "class_of_vehicle_abbreviation"=>!empty( $status) ? $status[5] : '',
                  "applicant_details"=>$applicant_details
                );

              $this->intermediator_model->add_param($rtps_trans_id,$appDetails);
              return $appNo;
            }
          }

         }
      }

      return $appNo;
    }

   

}
