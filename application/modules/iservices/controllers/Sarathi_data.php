<?php
require_once APPPATH."/third_party/libs/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Sarathi_data extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('intermediator_model');
        $this->config->load('rtps_services');
        $this->load->library('AESrathi');
        $this->encryption_key=$this->config->item("agencyKey");

    }
    public function get_pending_application(){

        $operations = 
          array(
              '$and' => array(
                [
                'portal_no'=>['$in'=>["4",5]],
                'app_ref_no'=>array("\$exists" => true)
                ]
              )
                 
      );
      
        $collection = 'intermediate_ids';
        $this->mongo_db->select(array("rtps_trans_id","mobile","app_ref_no","service_name","submission_date","applicant_details","execution_data","sms_delivery_index"));
        return $this->mongo_db->get_data_like($operations, $collection);
        
      } 
       
  
      public function update_data(){
     
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        $data=$this->get_pending_application();
    // pre($data);
      
        foreach($data as $app){
           
            $status_data=$this->app_status($app);
            $payment_data=$this->app_payment_status($app);
            if($payment_data !='Empty Response'){
                $result=explode("|",$payment_data);
                $appDetails=array(
                  "amount"=>!empty($result[5])? $result[5] : '0', 
                  "portal_payment_status"=>!empty($result[8])? $result[8] : '', 
                  "portal_payment_amount"=>!empty($result[5])? $result[5] : '' , 
                  "portal_payment_receiptno"=>!empty($result[6])? $result[6] : '', 
                  "portal_payment_receiptdate"=>!empty($result[7])? $result[7] : '', 
                  "portal_payment_SarathiTokenNo"=>!empty($result[4])? $result[4] : '', 
                  "track_data"=> $status_data
                );
            }else{
                $appDetails=array(
                    "track_data"=> $status_data
                  );
            }
          

        $this->intermediator_model->add_param($app->rtps_trans_id,$appDetails);
        }
      }



  public function get_data(){
     
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $data=$this->get_pending_application();
// pre($data);
    $objPHPExcel = new Spreadsheet();
    $objPHPExcel->setActiveSheetIndex(0);
    // set Header
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'RTPS Ref NO');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Sarathi App Ref No');
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Status API Data');
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Payment Response');
    $rowCount = 2;
    foreach($data as $app){
       
        $status_data=$this->app_status($app);
        $payment_data=$this->app_payment_status($app);
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $app->rtps_trans_id);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $app->app_ref_no);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount,  $status_data);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount,  $payment_data);
        $rowCount++;
   
    }

    $fileName = 'Sarathi_data.xlsx';
    $writer = new Xlsx($objPHPExcel);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'. $fileName.'"');
    $writer->save('php://output');

  }
 
  

  public function app_status($application_details){
    
    if($application_details){

        $rtps_trans_id=$application_details->rtps_trans_id;
      $getApplStatusDetUrl=$this->config->item("getApplStatusUrl");
      $agId=$this->config->item("agId");
      $agencyPassword=$this->config->item("agencyPassword");
      $agCd=MD5($agencyPassword.$rtps_trans_id);
     if(!empty($application_details->app_ref_no) && !empty($application_details->applicant_details[0]->applicant_date_of_birth)){
            $body_params=array(
              "agentId"=>$agId,
              "agentPwd"=> $agCd,
              "agentIpAddress"=>$this->input->ip_address(),
              "agentServiceName"=>"SarathiMobileService",
              "applNumber"=>property_exists($application_details,'app_ref_no') ?  $application_details->app_ref_no :'',
              "applDob"=>date('d/m/Y',strtotime($application_details->applicant_details[0]->applicant_date_of_birth)) 
            );


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
              CURLOPT_POSTFIELDS =>json_encode($body_params),
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
            // $result=json_decode(($response));
            // pre($result);
        //   if($result->status_code == "00"){
        //     if(!empty((array)$result)){
        //       $data_to_update=array(
        //         'rtoName'=>property_exists($result->ApplStatus_Details[0],'rtoName') ? $result->ApplStatus_Details[0]->rtoName : '',
        //         'dispatch_date'=>property_exists($result->ApplStatus_Details[0],'DispatchDt') ? $result->ApplStatus_Details[0]->DispatchDt : '',
        //         'rtoCd'=>property_exists($result->ApplStatus_Details[0],'rtoCd') ? $result->ApplStatus_Details[0]->rtoCd : '',
        //         'gender'=>property_exists($result->ApplStatus_Details[0],'gender') ? $result->ApplStatus_Details[0]->gender : '',
        //         'dlList'=>property_exists($result->ApplStatus_Details[0],'dlList') ? $result->ApplStatus_Details[0]->dlList : '',
        //         'llList'=>property_exists($result->ApplStatus_Details[0],'llList') ? $result->ApplStatus_Details[0]->llList : '',
                
        //       );  
        //       $this->intermediator_model->add_param($rtps_trans_id,$data_to_update);
        //     }
        //   }
     }
     

      
    }
   // pre($app_ref_no);
  }
  public function app_payment_status($application_details){
    $response_data=false;
    if(!empty($application_details)){
        $rtps_trans_id=$application_details->rtps_trans_id;
        $appNo=$application_details->app_ref_no;
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
        return $dec ? $dec : "Empty Response";
        // if(!empty($dec)){
        //   $result=explode("|",$dec);
        //   if($result[8] === "Success"){
        //     $response_data=true;
        //   }
        
        //   $appDetails=array(
        //     "amount"=>!empty($result[5])? $result[5] : '0', 
        //     "portal_payment_status"=>!empty($result[8])? $result[8] : '', 
        //     "portal_payment_amount"=>!empty($result[5])? $result[5] : '' , 
        //     "portal_payment_receiptno"=>!empty($result[6])? $result[6] : '', 
        //     "portal_payment_receiptdate"=>!empty($result[7])? $result[7] : '', 
        //     "portal_payment_SarathiTokenNo"=>!empty($result[4])? $result[4] : '', 
        //   );

        // $this->intermediator_model->add_param($rtps_trans_id,$appDetails);
        
         
        // }

       }else{
           return "Empty Response";
       }
    }
   

   
  }


  public function get_sarathi_pending_application(){

    $operations = 
      array(
          '$and' => array(
            [
            'portal_no'=>['$in'=>["4",5]],
            'app_ref_no'=>array("\$exists" => true),
            'portal_payment_SarathiTokenNo'=>array("\$exists" => false)
            ]
          )
             
  );
  
    $collection = 'intermediate_ids';
    $this->mongo_db->select(array("rtps_trans_id","mobile","app_ref_no","service_name","submission_date","applicant_details","execution_data","sms_delivery_index"));
    return $this->mongo_db->get_data_like($operations, $collection);
    
  } 
   

  public function update_sarathi_data(){
 
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $data=$this->get_sarathi_pending_application();

  
    foreach($data as $app){
       
        $status_data=$this->app_status($app);
        $payment_data=$this->app_payment_status($app);
        if($payment_data !='Empty Response'){
            $result=explode("|",$payment_data);
            if(!empty($result[4])){
              $appDetails=array(
                "amount"=>!empty($result[5])? $result[5] : '0', 
                "portal_payment_status"=>!empty($result[8])? $result[8] : '', 
                "portal_payment_amount"=>!empty($result[5])? $result[5] : '' , 
                "portal_payment_receiptno"=>!empty($result[6])? $result[6] : '', 
                "portal_payment_receiptdate"=>!empty($result[7])? $result[7] : '', 
                "portal_payment_SarathiTokenNo"=>!empty($result[4])? $result[4] : '', 
                "track_data"=> $status_data
              );
            }
           
        }else{
            $appDetails=array(
                "track_data"=> $status_data
              );
        }
      
        if(!empty($appDetails)){
          $this->intermediator_model->add_param($app->rtps_trans_id,$appDetails);
        }
    
    }
  }
  

 
  


}
