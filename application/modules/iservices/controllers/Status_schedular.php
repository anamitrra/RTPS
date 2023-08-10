<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
class Status_schedular extends Frontend
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('intermediator_model');
    $this->load->model('spservices/registered_deed_model');
    $this->load->helper('smsprovider');
    $this->config->load('rtps_services');
    $this->load->library('AES');
    
  }
  
private function get_app_status($task_details){
  $data=false;
  $status=array("Reject","reject",'delivered','FINISHED','Delivered','complete','Complete','completed','Completed');
  if($task_details){
      foreach($task_details as $task){
        if(in_array( $task->action,$status )){
        $data=$task;
        break; 
        }
      }
  }
  return $data;
}
 
 public function GMDWSB_application_status(){
    $portal=[9,"9"];
    // $this->application_status_update($portal);

    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
   
    $applications=$this->intermediator_model->get_pending_application_by_portal($portal);
    foreach ($applications as $key => $value) {
       
      $rtps_trans_id=$value->rtps_trans_id;
      $app_ref_no=isset($value->app_ref_no)?$value->app_ref_no:false;
     
      if($app_ref_no){
        $user_mobile=$value->mobile;
        $res=$this->fet_status($app_ref_no, $user_mobile,$value->portal->status_url,$value->portal->portal_no);
        $data_to_save=array();
        if($res){
          $status='P';
          $task=$res->task_details;
          $latest_task=$this->get_app_status($res->task_details);
          if($latest_task){
            $final_task=$latest_task;
          }else{
            $task1=array_reverse($res->task_details);
            $final_task=$task1[0];
          }
    
          if( $final_task){
            if($final_task->action === "Reject" || $final_task->action === "reject")
              $status='R';

              if($value->service_id == 14 || $value->service_id == '14' ){
                if($final_task->action === "Delivered" || $final_task->action === "delivered")
                $status='D';
              }else{
                if($final_task->action === "Complete" || $final_task->action === "complete" || $final_task->action === "completed" || $final_task->action === "Completed")
                $status='D';
              }
              if($final_task->action === "Query" || $final_task->action === "query")
              $status='Q';
              
              if($final_task->action === "Forward" || $final_task->action === "forward")
              $status='F';
          }
         
          $data_to_save['execution_data']=$res->task_details;
          $data_to_save['delivery_status']=$status;
          if(property_exists($final_task,'submission_location')){
            $data_to_save['current_location']=$final_task->submission_location;
          }
          if(property_exists($task[0],'submission_location')){
            $data_to_save['submission_location']=$task[0]->submission_location;
          }
          if(property_exists($task[0],'district')){
            $data_to_save['district']=$task[0]->district;
          }
          if(property_exists($task[0],'circle')){
            $data_to_save['circle']=$task[0]->circle;
          }
          $data_to_save['execution_date']=isset($final_task->executed_time)?$final_task->executed_time : '';
          
          if(!empty($final_task->executed_time)){
            $this->intermediator_model->add_param($rtps_trans_id,$data_to_save);
          }
        }
       // pre($res);
      }  
    }
 }

  //scheduler for updating noc services 

  public function application_status_update($portal=null){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
   if(empty($portal)){
    $portal=[7,"7",9,"9",11,"11"];
   }
    $applications=$this->intermediator_model->get_pending_application_by_portal($portal);
    foreach ($applications as $key => $value) {
       
      $rtps_trans_id=$value->rtps_trans_id;
      $app_ref_no=isset($value->app_ref_no)?$value->app_ref_no:false;
     
      if($app_ref_no){
        $user_mobile=$value->mobile;
        $res=$this->fet_status($app_ref_no, $user_mobile,$value->portal->status_url,$value->portal->portal_no);
        $data_to_save=array();
        if($res){
          $status='P';


          $task=$res->task_details;
          $final_task=array_reverse($res->task_details);
        
          if( $final_task){
            if($final_task[0]->action === "Reject" || $final_task[0]->action === "reject")
              $status='R';

              if($value->service_id == 14 || $value->service_id == '14' ){
                if($final_task[0]->action === "Delivered" || $final_task[0]->action === "delivered")
                $status='D';
              }else{
                if($final_task[0]->action === "Complete" || $final_task[0]->action === "complete")
                $status='D';
              }
         
              if($final_task[0]->action === "Query" || $final_task[0]->action === "query")
              $status='Q';
              
              if($final_task[0]->action === "Forward" || $final_task[0]->action === "forward")
              $status='F';
          }
         
          $data_to_save['execution_data']=$res->task_details;
          $data_to_save['delivery_status']=$status;
          if(property_exists($final_task[0],'submission_location')){
            $data_to_save['current_location']=$final_task[0]->submission_location;
          }
          if(property_exists($task[0],'submission_location')){
            $data_to_save['submission_location']=$task[0]->submission_location;
          }
          if(property_exists($task[0],'district')){
            $data_to_save['district']=$task[0]->district;
          }
          if(property_exists($task[0],'circle')){
            $data_to_save['circle']=$task[0]->circle;
          }
          $data_to_save['execution_date']=isset($final_task[0]->executed_time)?$final_task[0]->executed_time : '';
          if(!empty($final_task[0]->executed_time)){
            $this->intermediator_model->add_param($rtps_trans_id,$data_to_save);
          }
         
        
        }
       // pre($res);
      }
     
    }

  }
  public function rgi(){
   
    $app_ref_no="DA2023189025418806-00002";
    $user_mobile="9954218806";
    $portal_no=8;
    $url='https://crsorgi.gov.in/rtps_tracking/rtpsAPI.php';
   $res= $this->fet_status($app_ref_no,$user_mobile,$url,$portal_no);
   pre(json_encode($res));
  }
  public function get_status(){
    $app_ref_no="SCGN06/00441";
    $user_mobile="6000966220";
    $portal_no=9;
    $url='https://gmdwsb.in/api/rtps_track';
   $res= $this->fet_status($app_ref_no,$user_mobile,$url,$portal_no);
   pre(json_encode($res));
  }
  public function fet_status($app_ref_no=null,$user_mobile=null,$url,$portal_no){
 
    if(!empty($app_ref_no) && !empty($user_mobile)){
      $encryption_key=$this->config->item("encryption_key");
      $status_url = $url;
      $data=array(
        "app_ref_no"=>$app_ref_no,//'NOC/05/143/2020'
        "mobile"=>$user_mobile //"9435347177"
      );
      $input_array=json_encode($data);
    //   pre($input_array);
      $aes = new AES($input_array, $encryption_key);
      $enc = $aes->encrypt();
      //curl request
    
      $post_data=array('data'=>$enc);
      $curl = curl_init($status_url);
      // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      $response = curl_exec($curl);
      $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  
      curl_close($curl);
      if($response){
        $response=json_decode($response);
      }
      

      if($portal_no === "7" || $portal_no === 7){
        if (isset($response->d) && !empty($response->d)) {
          $aes->setData($response->d);
          $dec = $aes->decrypt();
          $outputdata = json_decode($dec);
          return  $outputdata;
        }
      }else{
          //decryption
          if (isset($response->data) && !empty($response->data)) {
            $aes->setData($response->data);
            $dec = $aes->decrypt();
            $outputdata = json_decode($dec);
            return  $outputdata;
          }
      }
     
    }else{
      return false;
    }
 

  }

 
 //payment related 

 public function check_query_payment_grn_history(){
  $rtps_trans_id=$_GET['id'];
  $this->mongo_db->where(array('rtps_trans_id'=>$rtps_trans_id));
  $history=$this->mongo_db->get('pfc_payment_history');
  foreach($history as $trans){
    if(isset($trans->query_department_id) && !empty($trans->query_department_id)){
      $res= $this->findgrn($trans);
      $this->mongo_db->set(array('status'=> $res));
      $this->mongo_db->where(array("query_department_id"=> $trans->query_department_id));
      $this->mongo_db->update('pfc_payment_history');
      if( $res === "Y"){
        // echo "Found a success payment for query payment id "+$trans->query_department_id;
        $ref=modules::load('spservices/Query_payment_response');
        $res=$ref->update_payment_status($trans->query_department_id);

      break;
      }
    }
  
  }

  
  //pre($history);
}

public function findgrn($app){ 
//  pre($app->department_id);
    if($app){
      $OFFICE_CODE=$app->query_payment_params->OFFICE_CODE;
      $am1=isset($app->query_payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $app->query_payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
      $am2=isset($app->query_payment_params->CHALLAN_AMOUNT) ? $app->query_payment_params->CHALLAN_AMOUNT : 0;
      $AMOUNT=$am1+$am2;
      $string_field="DEPARTMENT_ID=".$app->query_department_id."&OFFICE_CODE=".$OFFICE_CODE."&AMOUNT=".$AMOUNT;
      //pre($string_field);
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
      $res=explode("$",$result);
      if($res){
        $STATUS= isset($res[16])?$res[16]:'';
        $GRN= isset($res[4])?$res[4]:'';
      //  var_dump($STATUS);var_dump($GRN);die;
        if($STATUS === "Y"){
          $this->registered_deed_model->update_row(array('rtps_trans_id'=>$app->rtps_trans_id),
          array(
          "query_department_id"=>$app->query_department_id,
          "query_payment_params"=>$app->query_payment_params,
          "query_payment_response.GRN"=>$GRN,
          "query_payment_response.AMOUNT"=>isset($res[6])?$res[6]:'',
          "query_payment_response.PARTYNAME"=>isset($res[18])?$res[18]:'',
          "query_payment_response.TAXID"=>isset($res[20])?$res[20]:'',
          "query_payment_response.DEPARTMENT_ID"=>isset($res[2])?$res[2]:'',
          "query_payment_response.BANKNAME"=>isset($res[22])?$res[22]:'',
          "query_payment_response.BANKCODE"=>isset($res[8])?$res[8]:'',
          "query_payment_response.ENTRY_DATE"=>isset($res[24])?$res[24]:'',
          "query_payment_response.STATUS"=>$STATUS,
          "query_payment_response.PRN"=>isset($res[12])?$res[12]:'',
          "query_payment_response.TRANSCOMPLETIONDATETIME"=>isset($res[14])?$res[14]:'',
          "query_payment_response.BANKCIN"=>isset($res[10])?$res[10]:'',
          'query_payment_status'=>$STATUS));
          
       }
       return $STATUS;
      }
      return "";
    }
     
  
  } 

}
