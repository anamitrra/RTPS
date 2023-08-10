<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Sarathi_response extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('intermediator_model');
        $this->config->load('rtps_services');
        $this->load->library('AESrathi');
        $this->encryption_key=$this->config->item("agencyKey");

    }

   public function response(){
      //pre($_GET['token_no']);
     if(!empty($_GET['token_no'])){
      $token=($_GET['token_no']);
      // pre( urldecode($token));// not required
      $aes = new AESrathi($token, $this->encryption_key,128);
      $rtps_trans_id = $aes->decrypt();
      // $rtps_trans_id = "AS212807S6945081";
     // pre($rtps_trans_id);
      if(!empty ($rtps_trans_id)){
        $transaction_details=$this->intermediator_model->get_transaction_detail($rtps_trans_id);
        if(!empty($transaction_details)){
             $appNo= $this->get_application_details($rtps_trans_id);
            // pre($appNo);
            if(property_exists($transaction_details,"applied_by") && !empty($transaction_details->applied_by)){
                if($appNo){
                    $payment_status=$this->get_payment_details($rtps_trans_id,$appNo['appNo']);
                    if($appNo['status'] =="S"){
                      //pre(  $appNo);
                      redirect(base_url('iservices/admin/pfc-payment/').$rtps_trans_id);
                    }else{
                      if($payment_status){
                        redirect(base_url("iservices/pay-acknowledgement?app_ref_no=".$appNo['appNo']));
                        return;
                      }else{
                        $this->admin_show_error($appNo['appDetails']);
                        return;
                      }
                      
                    }
                   }
                  
                redirect(base_url("iservices/admin/my-transactions"));
                
            }else{
             
                if($appNo){
                    //check for payment 
                    $payment_status=$this->get_payment_details($rtps_trans_id,$appNo['appNo']);
                   
                    if($appNo['status'] == "S"){
                      //redirect(base_url("iservices/sarathi-acknowledgement?app_ref_no=".$appNo['appNo']));
                      
                    // collect convenience fee
                    redirect(base_url('iservices/convenience_fee/payment/').$rtps_trans_id);
                    }
                    else{
                      if($payment_status){
                        redirect(base_url("iservices/pay-acknowledgement?app_ref_no=".$appNo['appNo']));
                        return;
                      }else{
                        $this->show_error($appNo['appDetails']);
                        return;
                      }
                     // pre("err");
                     
                    }
                   }else{
                    redirect(base_url("iservices/transactions"));
                   }
            }
        }
     
      }
   
     }
    
     redirect(base_url("iservices"));
   }

   private function get_application_details($rtps_trans_id){
  
    $tans_result=array();
    if(!empty($rtps_trans_id)){
      $getSarApplDetUrl=$this->config->item("getSarApplDetUrl");
      $agId=$this->config->item("agId");
      $agencyPassword=$this->config->item("agencyPassword");
      $agCd=MD5($agencyPassword.$rtps_trans_id);
      $reqString = trim($agId."|".$agCd."|".$rtps_trans_id);
      $aes = new AESrathi($reqString, $this->encryption_key,128);
      $enc = $aes->encrypt();
       $getSarApplDetUrl .="?encData=".urlencode($enc)."&agencyCode=".$agId;
     

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
   // pre($response);
       if(!empty($response) && $response !="Some Exception Occurs" ){
        $aes->setData($response);
        $dec = $aes->decrypt();
        if(!empty($dec)){
          $result=explode("|",$dec);
          $status=explode("@",$result[9]);
          $appNo=$result[2];
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
                "dispatch_date"=>'',
                "transaction_name"=>!empty( $status) ? $status[2]:'',
                "transaction_abbreviation"=>!empty( $status) ? $status[3]:'',
                "class_of_vehicle_name"=>!empty( $status) ? $status[4]:'',
                "class_of_vehicle_abbreviation"=>!empty( $status) ? $status[5] : '',
                "applicant_details"=>$applicant_details
              );

            $this->intermediator_model->add_param($rtps_trans_id,$appDetails);
            $tans_result['appNo']=$appNo;
            $tans_result['appDetails']=$appDetails;
            $tans_result['status']=$st;
            return $tans_result;
          }
        }

       }
    }

    return $tans_result;
  }

  public function admin_show_error($data){
    $this->load->view('includes/header');
    // $this->load->view('error');
    $this->load->view('sarathi/pending_ack',$data);
    $this->load->view('includes/footer');
    
  }
  public function show_error($data){
    //pre($data);
    $this->load->view('includes/frontend/header');
    $this->load->view('sarathi/pending_ack',$data);
    $this->load->view('includes/frontend/footer');
    
  }

  public function get_payment_details($rtps_trans_id,$appNo,$dataset=false){
    $response_data=false;
    if(!empty($rtps_trans_id)){
      $getSarApplDetUrl=$this->config->item("SarPayRequest");
      $agId=$this->config->item("agId");
      $agencyPassword=$this->config->item("agencyPassword");
      $agCd=MD5($agencyPassword.$rtps_trans_id);
      $Cscid="rtps";

      $reqString = trim("rtps|".$agencyPassword."|".$Cscid."|".$appNo."|".$rtps_trans_id);
      $checkSum=MD5($reqString);
      $reqString .="|checkSum=".$checkSum;
      $aes = new AESrathi($reqString, $this->encryption_key,128);
      $enc = $aes->encrypt();
    //  echo $enc."<br/>";
       $getSarApplDetUrl .="?encData=".urlencode($enc)."&agencycode=".$agId;
      
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
      // echo $response."<br/><br/>";
       if(!empty($response) && $response !="Some Exception Occurs" ){
        $aes->setData($response);
        $dec = $aes->decrypt();
        if(!empty($dec)){
          $result=explode("|",$dec);
          if($result[8] === "Success"){
            $response_data=true;
          }
        
          $appDetails=array(
            "amount"=>!empty($result[5])? $result[5] : '0', 
            "portal_payment_status"=>!empty($result[8])? $result[8] : '', 
            "portal_payment_amount"=>!empty($result[5])? $result[5] : '' , 
            "portal_payment_receiptno"=>!empty($result[6])? $result[6] : '', 
            "portal_payment_receiptdate"=>!empty($result[7])? $result[7] : '', 
            "portal_payment_SarathiTokenNo"=>!empty($result[4])? $result[4] : '', 
          );

        $this->intermediator_model->add_param($rtps_trans_id,$appDetails);
        
         
        }

       }
    }
    if($dataset){
      return array(
        'status'=>$response_data,
        "data"=>!empty($appDetails)?$appDetails : array()
      );
    }else{
      return $response_data;
    }

   
  }

  // public function de(){
  //   // $str="sXf%2F4dgi8KEdy0BKLxk7uXTpOLXIk9B0vlP";
  //   $str=$_GET['token'];
  //   pre($str);
  //   $aes = new AESrathi($str, $this->encryption_key,128);
  //   $rtps_trans_id = $aes->decrypt();
  //   echo $rtps_trans_id;
  // }

  public function update_applications(){
    $this->load->model('sarathi_model');
    $data=$this->sarathi_model->get_pending_applications();
   // pre($data);
    if(!empty($data)){
      foreach($data as $tras){
        $rtps_trans_id=$tras->rtps_trans_id;
        $appNo=$this->get_application_details($rtps_trans_id);
        if($appNo){
          $this->get_payment_details($rtps_trans_id,$appNo['appNo']);
        }
      }
    }
    $this->update_status();
    
  }
  private function update_status(){
    $this->load->model('sarathi_model');
    $data=$this->sarathi_model->get_approve_applications_not_dispatched();
    if($data){
      foreach($data as $app){
        $this->update_status_action($app->rtps_trans_id);
      }
    }
   

  }
  private function update_status_action($rtps_trans_id){
    if($rtps_trans_id){
      $getApplStatusDetUrl=$this->config->item("getApplStatusDetUrl");
      $agId=$this->config->item("agId");
      $agencyPassword=$this->config->item("agencyPassword");
      $agCd=MD5($agencyPassword.$rtps_trans_id);
      $reqString = trim($agId."|".$agCd."|".$rtps_trans_id);
      $aes = new AESrathi($reqString, $this->encryption_key,128);
      $enc = $aes->encrypt();
       $getApplStatusDetUrl .="?encData=".urlencode($enc)."&agencyCode=".$agId;
       $curl = curl_init();
  
      curl_setopt_array($curl, array(
        CURLOPT_URL => $getApplStatusDetUrl,
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
      $result=json_decode(($response));
      if(!empty((array)$result)){
        $data_to_update=array(
          'rtoName'=>property_exists($result->ApplStatus_Details[0],'rtoName') ? $result->ApplStatus_Details[0]->rtoName : '',
          'dispatch_date'=>property_exists($result->ApplStatus_Details[0],'DispatchDt') ? $result->ApplStatus_Details[0]->DispatchDt : '',
          'rtoCd'=>property_exists($result->ApplStatus_Details[0],'rtoCd') ? $result->ApplStatus_Details[0]->rtoCd : '',
          'gender'=>property_exists($result->ApplStatus_Details[0],'gender') ? $result->ApplStatus_Details[0]->gender : '',
          'dlList'=>property_exists($result->ApplStatus_Details[0],'dlList') ? $result->ApplStatus_Details[0]->dlList : '',
          'llList'=>property_exists($result->ApplStatus_Details[0],'llList') ? $result->ApplStatus_Details[0]->llList : '',
          
        );
        $this->intermediator_model->add_param($rtps_trans_id,$data_to_update);
      }
    
    }
  }
  public function refresh_applications($rtps_trans_id){
    if(isset($rtps_trans_id)){
      $appNo=$this->get_application_details($rtps_trans_id);
   
      if($appNo){
        $this->get_payment_details($rtps_trans_id,$appNo['appNo']);      
      }
      $this->update_status_action($rtps_trans_id);
    }
    
    redirect($_SERVER['HTTP_REFERER']);
  }
  
}
