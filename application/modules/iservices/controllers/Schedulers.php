<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
class Schedulers extends Frontend
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('intermediator_model');
    $this->load->helper('smsprovider');
    $this->config->load('rtps_services');
    $this->load->library('AES');
    
  }
  

  //scheduler for sending sms for query 

  public function send_query_sms(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $applications=$this->intermediator_model->get_noc_queryable_application();
    // pre( $applications);
    foreach ($applications as $key => $value) {
      $app_ref_no=isset($value->app_ref_no)?$value->app_ref_no:false;
      
      if($app_ref_no){
        $data=array(
          "mobile"=>$value->mobile,
          "applicant_name"=>property_exists($value,"applicant_details") ? $value->applicant_details[0]->applicant_name : $value->mobile,
          "service_name"=>$value->service_name,
          "submission_date"=>$value->submission_date,
          "app_ref_no"=>$value->app_ref_no,
          "rtps_trans_id"=>$value->rtps_trans_id
        );
        
        sms_provider("query",$data);
        
      }
     
    }

  }


  //schedular for sending delivered sms
  public function send_delivery_sms(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $applications=$this->intermediator_model->get_noc_delivered_application();
    // pre( $applications);
    foreach ($applications as $key => $value) {
      $app_ref_no=isset($value->app_ref_no)?$value->app_ref_no:false;
      
      if($app_ref_no){
        $data=array(
          "mobile"=>$value->mobile,
          "applicant_name"=>property_exists($value,"applicant_details") ? $value->applicant_details[0]->applicant_name : $value->mobile,
          "service_name"=>$value->service_name,
          "submission_date"=>$value->submission_date,
          "app_ref_no"=>$value->app_ref_no,
          "rtps_trans_id"=>$value->rtps_trans_id
        );

        sms_provider("delivery",$data);
        $this->intermediator_model->update_row(array('rtps_trans_id'=>$value->rtps_trans_id),array('is_delivery_sms_send'=>true));
        
      }
     
    }

  }


  //send sms for all action on noc

  public function sms_onchange_action(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $applications=$this->intermediator_model->get_noc_pending_application();
    // pre( $applications);
    foreach ($applications as $key => $value) {
      //pre( $value->execution_data);
      $app_ref_no=isset($value->app_ref_no)?$value->app_ref_no:false;
      $action="";
      $updatable_index="";
      $execution_time="";
      if($app_ref_no){
        if(!empty($value->execution_data)){
          foreach($value->execution_data as $index=>$exe){
            if(property_exists($value,"sms_delivery_index")){
              if( $index > $value->sms_delivery_index){
                $action=$exe->sms_code;
                $updatable_index=$index;
                $execution_time=$exe->executed_time;

                $multi_sms=isset($exe->mult_sms) ? $exe->mult_sms : null;
                $bulksms_code=isset($exe->bulksms_code) ? $exe->bulksms_code : null;
                $bulksms_data=isset($exe->msmsdata) ? $exe->msmsdata : null;
              }
            }else{
              $action=$exe->sms_code;
              $updatable_index=$index;
              $execution_time=$exe->executed_time;
              $multi_sms=isset($exe->mult_sms) ? $exe->mult_sms : null;
              $bulksms_code=isset($exe->bulksms_code) ? $exe->bulksms_code : null;
              $bulksms_data=isset($exe->msmsdata) ? $exe->msmsdata : null;
            }
          }
          if( $multi_sms){
            if(!empty($bulksms_code) && !empty($bulksms_data)){
              $numArray=explode(',',$multi_sms);
              foreach($numArray as $num){
                $mdata=array(
                  "mobile"=>$num,
                  "execution_time"=>$execution_time,
                  "app_ref_no"=>$value->app_ref_no,
                  "applicant"=>isset($value->applicant_details[0]->applicant_name) ? $value->applicant_details[0]->applicant_name : '',
                  "hearing_date"=>isset($bulksms_data[0]->hearingdate) ? $bulksms_data[0]->hearingdate : "",
                  "dag_no"=>isset($bulksms_data[0]->dagno) ? $bulksms_data[0]->dagno : "",
                  "village"=>isset($bulksms_data[0]->village) ? $bulksms_data[0]->village : "",
                  "circle"=>isset($bulksms_data[0]->circle) ? $bulksms_data[0]->circle : "",
                );
                //pre($mdata);
                transaction_sms($bulksms_code,$mdata);
              }
            }

          }
          if($action){
              $data=array(
              "mobile"=>$value->mobile,
              "execution_time"=>$execution_time,
              "app_ref_no"=>$value->app_ref_no
            );
          transaction_sms($action,$data);
          $this->intermediator_model->update_row(array('rtps_trans_id'=>$value->rtps_trans_id),array('sms_delivery_index'=>$updatable_index));
          }
     
        

        }
      }
     
    }
  }

  public function update_payment_status(){
    $applications=$this->intermediator_model->get_payment_pending_applications();
    foreach($applications as $app){
      $this->checkgrn($app);
    }
    
  }

  public function checkgrn($app){ // TODO: need to check which are params to update
//  pre($app->department_id);
    if($app){
      $OFFICE_CODE=$app->payment_params->OFFICE_CODE;
      $am1=isset($app->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $app->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
      $am2=isset($app->payment_params->CHALLAN_AMOUNT) ? $app->payment_params->CHALLAN_AMOUNT : 0;
      $AMOUNT=$am1+$am2;
      $string_field="DEPARTMENT_ID=".$app->department_id."&OFFICE_CODE=".$OFFICE_CODE."&AMOUNT=".$AMOUNT;
      // pre($string_field);
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
     
      if($res){
        $STATUS= isset($res[16])?$res[16]:'';
        $GRN= isset($res[4])?$res[4]:'';
      //  var_dump($STATUS);var_dump($GRN);die;
        //if($STATUS === "Y"){
          $this->intermediator_model->update_row(array('department_id'=>$app->department_id),
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
     

  }


  //scheduler for updating noc services 

  public function noc_status_update(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $applications=$this->intermediator_model->get_noc_pending_application();
  //  pre( $applications);
    foreach ($applications as $key => $value) {
      $rtps_trans_id=$value->rtps_trans_id;
      $app_ref_no=isset($value->app_ref_no)?$value->app_ref_no:false;
      if($app_ref_no){
        $user_mobile=$value->mobile;
        $res=$this->update_action_noc($app_ref_no, $user_mobile);
       
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

          $this->intermediator_model->add_param($rtps_trans_id,$data_to_save);
        
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

  public function update_sudmission_location(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');


    $operations=
    array(
      '$and'=>array(
        [
          'portal_no'=>['$in'=>["1",1]],
          "delivery_status" => 'D'
        ]
      )
        );
        $collection = 'intermediate_ids';

        // service_name,applicant_name,number,submission_date,app_ref_no,submission_office
        $this->mongo_db->select(array("rtps_trans_id","mobile","app_ref_no"));
    

    $applications=$this->mongo_db->get_data_like($operations, $collection);
   
    foreach ($applications as $key => $value) {
      $rtps_trans_id=$value->rtps_trans_id;
      $app_ref_no=isset($value->app_ref_no)?$value->app_ref_no:false;
      if($app_ref_no){
        $user_mobile=$value->mobile;
        $res=$this->update_action_noc($app_ref_no, $user_mobile);
       
        $data_to_save=array();
        if($res){

          $final_task=array_reverse($res->task_details);
        
          if(property_exists($final_task[0],'submission_location')){
            $data_to_save['submission_location']=$final_task[0]->submission_location;
          }
          if(property_exists($final_task[0],'district')){
            $data_to_save['district']=$final_task[0]->district;
          }
          if(property_exists($final_task[0],'circle')){
            $data_to_save['circle']=$final_task[0]->circle;
          }
         
          if($data_to_save){
            $this->intermediator_model->add_param($rtps_trans_id,$data_to_save);
          }
          
          // pre( $data_to_save);
        
        
        }
       // pre($res);
      }
     
    }
  }



  //payment related 

  public function check_payment_grn_history($rtps_trans_id){
      $this->mongo_db->where(array('rtps_trans_id'=>$rtps_trans_id));
      $history=$this->mongo_db->get('pfc_payment_history');
      foreach($history as $trans){
       $res= $this->findgrn($trans);
       $this->mongo_db->set(array('status'=> $res));
       $this->mongo_db->where(array("department_id"=> $trans->department_id));
       $this->mongo_db->update('pfc_payment_history');
       if( $res === "Y"){
       break;
       }
      }
      //pre($history);
  }

  public function findgrn($app){ 
    //  pre($app->department_id);
        if($app){
          $OFFICE_CODE=$app->payment_params->OFFICE_CODE;
          $am1=isset($app->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $app->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
          $am2=isset($app->payment_params->CHALLAN_AMOUNT) ? $app->payment_params->CHALLAN_AMOUNT : 0;
          $AMOUNT=$am1+$am2;
          $string_field="DEPARTMENT_ID=".$app->department_id."&OFFICE_CODE=".$OFFICE_CODE."&AMOUNT=".$AMOUNT;
          // pre($string_field);
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
              $this->intermediator_model->update_row(array('rtps_trans_id'=>$app->rtps_trans_id),
              array(
              "department_id"=>$app->department_id,
              "payment_params"=>$app->payment_params,
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
              
           }
           return $STATUS;
          }
          return "";
        }
         
      
      }


      //scheduler for updating noc services 

  public function basundhara_status_update(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $applications=$this->intermediator_model->get_basundhara_pending_application();
  //  pre( $applications);
    foreach ($applications as $key => $value) {
      $rtps_trans_id=$value->rtps_trans_id;
      $app_ref_no=isset($value->app_ref_no)?$value->app_ref_no:false;
      if($app_ref_no){
        $user_mobile=$value->mobile;
        $res=$this->update_action_basundhara($app_ref_no, $user_mobile);
        // pre($res);
        $data_to_save=array();
        if($res){
          $status='P';


          $final_task=array_reverse($res->task_details);
        
          if( $final_task){
            if($final_task[0]->action === "REJECTED")
              $status='R';
            if($final_task[0]->action === "FINISHED")
              $status='D';
              if($final_task[0]->action === "QUERY")
              $status='Q';
              if($final_task[0]->action === "FORWARDED" )
              $status='F';
          }
         
          $data_to_save['execution_data']=$res->task_details;
          $data_to_save['delivery_status']=$status;
        
          $data_to_save['execution_date']=isset($final_task[0]->executed_time)?$final_task[0]->executed_time : '';
          if(!empty($final_task[0]->executed_time)){
            $this->intermediator_model->add_param($rtps_trans_id,$data_to_save);
          }
        }
       // pre($res);
      }
     
    }

  }
public function end(){
  $str='2023-02-01&2023-02-03';
  echo urlencode($str);
}

  public function basundhara_status_update_by_service($id,$date=null){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $param=urldecode( $date);
    $param_ex=explode('&',$param);

    $start_date=is_array($param_ex) ? $param_ex[0] : null;
    $end_date=is_array($param_ex) ?  $param_ex[1] : null;
    $applications=$this->intermediator_model->get_basundhara_pending_application_service($id,$start_date, $end_date);
  //  pre( $applications);
  // $log='';
    foreach ($applications as $key => $value) {
     
      $rtps_trans_id=$value->rtps_trans_id;
      $app_ref_no=isset($value->app_ref_no)?$value->app_ref_no:false;
      if($app_ref_no){
        // $log .="App Ref No : ".$app_ref_no ;
        
        $user_mobile=$value->mobile;
        $res=$this->update_action_basundhara($app_ref_no, $user_mobile);
        // pre($res);
        $data_to_save=array();
        $data_to_status=array();
        $data_to_status['app_ref_no']=$app_ref_no;
        if($res){
          $data_to_status['response_from_bas']=true;
          $status='P';


          $final_task=array_reverse($res->task_details);
        
          if( $final_task){
            if($final_task[0]->action === "REJECTED")
              $status='R';
            if($final_task[0]->action === "FINISHED")
              $status='D';
              if($final_task[0]->action === "QUERY")
              $status='Q';
              if($final_task[0]->action === "FORWARDED" )
              $status='F';
          }
         
          $data_to_save['execution_data']=$res->task_details;
          $data_to_save['delivery_status']=$status;
        
          $data_to_save['execution_date']=isset($final_task[0]->executed_time)?$final_task[0]->executed_time : '';
          $ex=isset($final_task[0]->executed_time)? $final_task[0]->executed_time : 'Null';
          // $log .="   delivery_status : ".$status."   exection_time : ".$ex ;
         
          $data_to_status['delivery_status']=$status;
          $data_to_status['exection_time']=$ex;
          if(!empty($final_task[0]->executed_time)){
            $upresult =  $this->intermediator_model->add_param($rtps_trans_id,$data_to_save);


            if($upresult->getMatchedCount()){
            
              $data_to_status['is_updated']=true;
            }else {
              $data_to_status['is_updated']=false;
            }

            // $log .=" updated : true".PHP_EOL ;
          }else{
            // $log .=" updated : false".PHP_EOL ;
          }
          
        }else{
          $data_to_status['response_from_bas']=false;
        }
       // pre($res);
      }

        $this->mongo_db->insert('basundhara_status_update_log',$data_to_status);
    }
    // file_put_contents('./basundhara_status_by_service_log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);

  }


  public function noc_status_by_app($app_ref_no){
  
      if($app_ref_no){
        $value=$this->intermediator_model->get_row(array("app_ref_no"=> $app_ref_no));
        $user_mobile=$value->mobile;
        $res=$this->update_action_noc($app_ref_no, $user_mobile);
       
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

          $this->intermediator_model->add_param($value->rtps_trans_id,$data_to_save);
          return true;
        
        }
       // pre($res);
      }else{
        return false;
      }
     
    

  }


  public function fet_others_status($app_ref_no=null,$user_mobile=null,$url,$portal_no){
 
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
  public function others_status_by_app($app_ref_no){
  
    if($app_ref_no){
      $value=$this->intermediator_model->get_others_application($app_ref_no);
      $user_mobile=$value->mobile;
      $res=$this->fet_others_status($app_ref_no, $user_mobile,$value->portal->status_url,$value->portal->portal_no);
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
        if(!empty($task)){
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
        }
       
        $data_to_save['execution_date']=isset($final_task[0]->executed_time)?$final_task[0]->executed_time : '';
        $this->intermediator_model->add_param($value->rtps_trans_id,$data_to_save);
        return true;
      
      }
     // pre($res);
    }else{
      return false;
    }
   
  

}


  public function basundhara_status_by_app_ref(){
    $app_ref_no=$_GET['app_ref_no'];
    if( $app_ref_no){
      $this->basundhara_status_by_app($app_ref_no);
    }
  }
  public function basundhara_status_by_app($app_ref_no){
      
      if($app_ref_no){
        $app=$this->intermediator_model->get_row(array("app_ref_no"=> $app_ref_no));
        if( $app){
           $res= $this->update_action_basundhara($app_ref_no,$app->mobile);
           $data_to_save=array();
           if($res){
             $status='P';
             $final_task=array_reverse($res->task_details);
           
             if( $final_task){
               if($final_task[0]->action === "REJECTED")
                 $status='R';
               if($final_task[0]->action === "FINISHED")
                 $status='D';
                 if($final_task[0]->action === "QUERY")
                 $status='Q';
                 if($final_task[0]->action === "FORWARDED" )
                 $status='F';
             }
           
             $data_to_save['execution_data']=$res->task_details;
             $data_to_save['delivery_status']=$status;
           
             $data_to_save['execution_date']=isset($final_task[0]->executed_time)?$final_task[0]->executed_time : '';

             if(!empty($final_task[0]->executed_time)){
               $this->intermediator_model->add_param($app->rtps_trans_id,$data_to_save);
               return true;
             }
           }
        }else{
          echo "no records found";
          return false;
        }
      }
    
    
  }
  public function update_action_basundhara($app_ref_no=null,$user_mobile=null){
    if(!empty($app_ref_no) && !empty($user_mobile)){
      $encryption_key=$this->config->item("encryption_key");
      $status_url = "https://basundhara.assam.gov.in/rtps/rest/trackapplication"; 
      $data=array(
        "application_no"=>$app_ref_no,//'RTPS/TMAP/2022/144'
        "mobile"=>$user_mobile //"8876081067"
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
      // pre($response->error);
      curl_close($curl);
      if($response){
        $response=json_decode($response);
        if(isset($response->error) && $response->error=="You are not authorized to access"){
          return false;
        }else{
          return $response;
        }
        
      }
     
    }else{
      return false;
    }
 

  }
  public function push_payment_status_on_basundhara(){
    //payment_status_updated_on
    $applications=$this->intermediator_model->get_pending_payment_status_update();
    if( $applications){
      foreach($applications as $value){
        if(isset($value->department_id)){
          
          $ref=modules::load('iservices/basundhara/Basundahara_response');
          $ref->push_payment_status($value->department_id);
        }
        
      }
     
    }
    
  }


  public function push_payment_status_on_noc(){
    //payment_status_updated_on
    $applications=$this->intermediator_model->get_noc_pending_payment_status();
    
    if( $applications){
      foreach($applications as $value){
        if(isset($value->department_id)){
          
          $ref=modules::load('iservices/Transoprt_response');
          $ref->push_noc_payment_status($value->department_id);
        }
        
      }
     
    }
    
  }

  public function update_noc_form_data(){
  //   $filter=array(
  //     "portal_no"=>array('$in'=>[1,"1"]),
  //     "status"=>"S",
  //     "amount"=>['$regex'=>'\d{10}','$options' => 'i']
  //   );
  //   $this->mongo_db->select(array('rtps_trans_id'));
  //  $data=$this->mongo_db->get_data_like($filter, "intermediate_ids");
  //  pre( $data);


    $operations=array(
      [
        '$match'=>[
          "portal_no"=>array('$in'=>[1,"1"]),
          "status"=>"S",
        ]
        ],
        [
          '$set'=>["new_amount"=>['$toString'=>'$amount']]
        ],
        [
          '$match'=>[
            "new_amount"=>['$regex'=>'\d{10}','$options' => 'i']
          ]
          ],
          [
            '$project'=>[
              'rtps_trans_id'=>1,
              'app_ref_no'=>1
            ]
          ]

            );

            $data = $this->mongo_db->aggregate("intermediate_ids", $operations);
        
    foreach($data as $key=>$item){
      $this->fetch_noc_data($item->rtps_trans_id);
    }
    echo "done";
  }
  public function fetch_noc_data($rtps_trans_id){
    // pre($rtps_trans_id);
    $url='https://ilrms.nic.in/noc/index.php/Nocwebservice/nocdet';
    $encryption_key=$this->config->item("encryption_key");
    // $rtps_trans_id=$_GET['id'];
    // $application=$this->intermediator_model->get_row(array("rtps_trans_id"=>$rtps_trans_id));
    // pre($application);
    if(true){
      $data=array(
        "rtps_trans_id"=>$rtps_trans_id,
      );
      $input_array=json_encode($data);
      $aes = new AES($input_array, $encryption_key);
      $enc = $aes->encrypt();
      //curl request
    
      $post_data=array('data'=>$enc);
      $curl = curl_init($url);
      // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      $response = curl_exec($curl);
      // pre($response->error);
      curl_close($curl);
    //  pre($response);die;
      if($response){
      
        $response=json_decode($response);
        if($response->status === "success"){
          if(!empty($response->data)){
            $aes->setData($response->data);
            $dec=$aes->decrypt();
            $dec = json_decode($dec);
            $response = $dec->response_data;
            if(!empty($response) && $this->validateResponse($response)){
              $data_to_update=array();
              if(!empty($response->app_ref_no)){
                $data_to_update['app_ref_no']=$response->app_ref_no;
              }
              if(!empty($response->status)){
                $data_to_update['status']=$response->status;
              }

              if(!empty($response->submission_date)){
                $data_to_update['submission_date']=$response->submission_date;
              }
              if(!empty($response->payment_mode)){
                $data_to_update['payment_mode']=$response->payment_mode;
              }
              if(!empty($response->payment_ref_no)){
                $data_to_update['payment_ref_no']=$response->payment_ref_no;
              }
              if(!empty($response->payment_status)){
                $data_to_update['payment_status']=$response->payment_status;
              }
              if(!empty($response->payment_date)){
                $data_to_update['payment_date']=$response->payment_date;
              }
              if(!empty($response->amount)){
                $data_to_update['amount']=$response->amount;
              }
              if(!empty($response->amountmut)){
                $data_to_update['amountmut']=$response->amountmut;
              }
              if(!empty($response->amountmut)){
                $data_to_update['amountmut']=$response->amountmut;
              }
              if(!empty($response->amountpart)){
                $data_to_update['amountpart']=$response->amountpart;
              }
              if(!empty($response->applicant_details)){
                $data_to_update['applicant_details']=$response->applicant_details;
              }
              if(!empty($response->application_details)){
                $data_to_update['application_details']=$response->application_details;
              } 
              //  if(!empty($response->payment_details)){
              //   $data_to_update['payment_details']=$response->payment_details;
              // }
              $data_to_update['inserted_to_mis']=false;
              $result=$this->intermediator_model->add_param($response->rtps_trans_id,$data_to_update);
            }
          }
        }
    
        
      }
    }
  }

  //update basundhara application whose status has not able to updated on rtps end 
  public function getFailedAppsOnRtpsForBasundhara(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
      $operations = 
      array(
          '$and' => array(
            [
            'is_updated'=>false
            ]
          )
            
    );

    
    $this->mongo_db->select(array("rtps_trans_id","mobile","app_ref_no"));
    $applications = $this->mongo_db->get_data_like($operations, 'basundhara_daily_app_status_log');
   
    if(!is_array($applications)){
      foreach($applications as $item){
        $res=$this->update_action_basundhara($item->app_ref_no,$item->mobile);
        
        $data_to_save=array();
            if($res){
              $status='P';
              $final_task=array_reverse($res->task_details);
            
              if( $final_task){
                if($final_task[0]->action === "REJECTED")
                  $status='R';
                if($final_task[0]->action === "FINISHED")
                  $status='D';
                  if($final_task[0]->action === "QUERY")
                  $status='Q';
                  if($final_task[0]->action === "FORWARDED" )
                  $status='F';
              }
              $data_to_save['execution_data']=$res->task_details;
              $data_to_save['delivery_status']=$status;
            
              $data_to_save['execution_date']=isset($final_task[0]->executed_time)?$final_task[0]->executed_time : '';
           
              if(!empty($final_task[0]->executed_time)){
                $upresult= $this->intermediator_model->add_param($item->rtps_trans_id,$data_to_save);
                if($upresult->getMatchedCount()){
                  $this->mongo_db->set(array('is_updated'=>true));
                  $this->mongo_db->where(array("rtps_trans_id"=> $item->rtps_trans_id));
                  $this->mongo_db->update('basundhara_daily_app_status_log');
                }
              }
            }
      }
    }

  }
  //update basundhara application whose status has been updated on basundhara end
  public function get_basundhara_status_updated_apps(){
       ini_set('max_execution_time', 0);
       ini_set('memory_limit', '-1');

        $date=isset($_GET['date']) ? $_GET['date'] : false; //2022-12-11
        if($date){
          $status_url = "https://basundhara.assam.gov.in/rtps/LocalAPI/getApplicationByDate?date=".$date; 
        }else{
          $status_url = "https://basundhara.assam.gov.in/rtps/LocalAPI/getApplicationByDate";
        }
        $curl = curl_init($status_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        curl_close($curl);
        if($response){
          $response=json_decode($response,true);
          if(!empty( $response['response']) &&  $response['response'] === 2){
            $list=$response['detail'];
            foreach($list as $item){
              // $trans=$this->intermediator_model->get_row_data_by_rtps_trans_no($item['rtps_trans_id'],array("mobile","delivery_status"));
           
              $res=$this->update_action_basundhara($item['application_no'],$item['mobile']);
              $log_data=array("rtps_trans_id"=>$item['rtps_trans_id'],"app_ref_no"=>$item['application_no'],"mobile"=>$item['mobile'],"is_updated"=>false,'delivery_status'=>'');
              $data_to_save=array();
                  if($res){
                    $status='P';
                    $final_task=array_reverse($res->task_details);
                  
                    if( $final_task){
                      if($final_task[0]->action === "REJECTED")
                        $status='R';
                      if($final_task[0]->action === "FINISHED")
                        $status='D';
                        if($final_task[0]->action === "QUERY")
                        $status='Q';
                        if($final_task[0]->action === "FORWARDED" )
                        $status='F';
                    }
                    $log_data['delivery_status']=$status;
                    $data_to_save['execution_data']=$res->task_details;
                    $data_to_save['delivery_status']=$status;
                  
                    $data_to_save['execution_date']=isset($final_task[0]->executed_time)?$final_task[0]->executed_time : '';
                    $log_data['execution_date']=$data_to_save['execution_date'];
                    if(!empty($final_task[0]->executed_time)){
                      $upresult= $this->intermediator_model->add_param($item['rtps_trans_id'],$data_to_save);
                      if($upresult->getMatchedCount()){
                        $log_data['is_updated']=true;
                      }
                    }
                  }
                  $this->mongo_db->insert('basundhara_daily_app_status_log',$log_data);
                 
            }
          }
        
        }
  }

  public function validateResponse($response){
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
    if(empty($response->status) || $response->status !== "S"){
      $validation=false;
    }

    if(empty($response->submission_date)){
      $validation=false;
    }
    if(empty($response->applicant_details)){
      $validation=false;
    }
    return $validation;
  }



  ///non sense worke 

  public function remove_basundhara_status(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
   
 

      $operations = 
      array(
          '$and' => array(
            [
            'portal_no'=>['$in'=>["5",5]],
            'status'=>'S',
            'service_id'=>['$in'=>['242',242]],
            'delivery_status'=>'D',
            'pfc_payment_status'=>['$ne'=>'Y'],
            ]
          )
            
    );

    // pre( $operations);
    $collection = 'intermediate_ids';
    $this->mongo_db->select(array("rtps_trans_id","mobile","app_ref_no"));
    $applications = $this->mongo_db->get_data_like($operations, $collection);

// pre($applications );
    foreach ($applications as $key => $value) {
     
      $rtps_trans_id=$value->rtps_trans_id;
      $app_ref_no=isset($value->app_ref_no)?$value->app_ref_no:false;
      if($app_ref_no){
        
        $user_mobile=$value->mobile;
        $res=$this->update_action_basundhara($app_ref_no, $user_mobile);
        // pre($res);
        $data_to_save=array();
        $data_to_status=array();
        $data_to_status['app_ref_no']=$app_ref_no;
        if($res){
          $data_to_status['response_from_bas']=true;
          $status='P';


          $final_task=array_reverse($res->task_details);
        
          if( $final_task){
            if($final_task[0]->action === "REJECTED")
              $status='R';
            if($final_task[0]->action === "FINISHED")
              $status='D';
              if($final_task[0]->action === "QUERY")
              $status='Q';
              if($final_task[0]->action === "FORWARDED" )
              $status='F';
          }
         
          $data_to_save['execution_data']=$res->task_details;
          $data_to_save['delivery_status']=$status;
        
          $data_to_save['execution_date']=isset($final_task[0]->executed_time)?$final_task[0]->executed_time : '';
          $ex=isset($final_task[0]->executed_time)? $final_task[0]->executed_time : 'Null';
          // $log .="   delivery_status : ".$status."   exection_time : ".$ex ;
        // pre($data_to_save);
          $data_to_status['delivery_status']=$status;
          $data_to_status['exection_time']=$ex;
          $upresult =  $this->intermediator_model->add_param($rtps_trans_id,$data_to_save);


            if($upresult->getMatchedCount()){
            
              $data_to_status['is_updated']=true;
            }else {
              $data_to_status['is_updated']=false;
            }
          
        }else{
          $data_to_status['response_from_bas']=false;
        }
       // pre($res);
      }

        $this->mongo_db->insert('basundhara_remove_d_status_log',$data_to_status);
        
    }
    // file_put_contents('./basundhara_status_by_service_log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);

  }



  //update payment status expect stalement services 
  public function update_bas_payment_status(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
   
    $applications=$this->intermediator_model->get_bas_payment_pending_applications();
    foreach($applications as $app){
      $this->custome_checkgrn($app);
    }
    
  }


  public function custome_checkgrn($app){ // TODO: need to check which are params to update
    //  pre($app->department_id);
        if($app){
          $OFFICE_CODE=$app->payment_params->OFFICE_CODE;
          $am1=isset($app->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $app->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
          $am2=isset($app->payment_params->CHALLAN_AMOUNT) ? $app->payment_params->CHALLAN_AMOUNT : 0;
          $AMOUNT=$am1+$am2;
          $string_field="DEPARTMENT_ID=".$app->department_id."&OFFICE_CODE=".$OFFICE_CODE."&AMOUNT=".$AMOUNT;
          // pre($string_field);
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
         
          if($res){
            $STATUS= isset($res[16])?$res[16]:'';
            $GRN= isset($res[4])?$res[4]:'';
            $data_to_update=  array(
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
              'pfc_payment_status'=>$STATUS);

            if(  $STATUS === "Y"){
              $data_to_update['rtps_trans_id']=$app->rtps_trans_id;
              $this->mongo_db->insert('basundhara_statlement_success_payment_log',$data_to_update);
            }else{
                $this->intermediator_model->update_row(array('department_id'=>$app->department_id),$data_to_update);
            }
          }
        }
         
    
      }

    //update basundhara delivered apps by month 
    public function update_basundhara_delivered_apps_by_month(){
      ini_set('max_execution_time', 0);
      ini_set('memory_limit', '-1');
      $param=$_SERVER['argv'];
      $from_date=$param[3];
      $to_date=$param[4];
      if(empty($from_date) || empty($to_date)){
        die("no date found");
        return;
      }
      $url="https://basundhara.assam.gov.in/rtps/LocalAPI/getDeliveredApplications?from_date=". $from_date."&to_date=".$to_date;
      $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        curl_close($curl);
        if($response){
          $response=json_decode($response,true);
          if(!empty( $response['response']) &&  $response['response'] === 2){
            if( $response['detail']){
              foreach( $response['detail'] as $item){
              // pre($item['rtps_trans_id']);
                $applications=$this->intermediator_model->check_if_pending($item['rtps_trans_id']);
               
                if(!empty($applications) && !empty($applications->app_ref_no)){
                  $app_ref_no=isset($applications->app_ref_no)?$applications->app_ref_no:false;
                  $user_mobile=$applications->mobile;
                  $res=$this->update_action_basundhara($app_ref_no, $user_mobile);
                  $this->save_basundhara_data( $res, $item['rtps_trans_id'],$app_ref_no);
                }
              }
            }
          }
        }

    }

      //update basundhara apps by district 
      public function update_basundhara_apps_by_district($district='BARPETA'){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        $array = array(
          'portal_no' => array('$in' => array(5, "5")),
          'status' => 'S',
          'district' => array('$in' => array("KAMRUP  ","BAJALI","SOUTH SALMARA","TINSUKIA")),
          '$or' => array(
              array('delivery_status' => array('$exists' => false)),
              array('delivery_status' => array('$nin' => array('D', 'R')))
          )
      );

      $collection = 'intermediate_ids';
      $this->mongo_db->select(array("rtps_trans_id","mobile","app_ref_no","service_name","submission_date","applicant_details.applicant_name","execution_data","sms_delivery_index"));
      $applications= $this->mongo_db->get_data_like($array, $collection);
      if($applications){
          foreach ($applications as $key => $value) {
            $rtps_trans_id=$value->rtps_trans_id;
            $app_ref_no=isset($value->app_ref_no)?$value->app_ref_no:false;
            if($app_ref_no){
              $user_mobile=$value->mobile;
              $res=$this->update_action_basundhara($app_ref_no, $user_mobile);
            
              $this->save_basundhara_data( $res, $rtps_trans_id,$app_ref_no);
            }
          
          }
      }
    
      
      }

      private function save_basundhara_data($res,$rtps_trans_id,$app_ref_no){
        $data_to_save=array();
        $data_to_status=array();
        $data_to_status['app_ref_no']=$app_ref_no;
        if($res){
          $status='P';
          $data_to_status['response_from_bas']=true;


          $final_task=array_reverse($res->task_details);
        
          if( $final_task){
            $data_to_status['task_details']=true;
            if($final_task[0]->action === "REJECTED")
              $status='R';
            if($final_task[0]->action === "FINISHED")
              $status='D';
              if($final_task[0]->action === "QUERY")
              $status='Q';
              if($final_task[0]->action === "FORWARDED" )
              $status='F';
          }else{
            $data_to_status['task_details']=false;
          }
          $data_to_status['delivery_status']=$status;
          $data_to_save['execution_data']=$res->task_details;
          $data_to_save['delivery_status']=$status;
        
          $data_to_save['execution_date']=isset($final_task[0]->executed_time)?$final_task[0]->executed_time : '';
          if(!empty($final_task[0]->executed_time)){
            $this->intermediator_model->add_param($rtps_trans_id,$data_to_save);
          }
        }else{
          $data_to_status['response_from_bas']=false;
        }

        $this->mongo_db->insert('basundhara_status_district_save_log',$data_to_status);
      }


      //query payment related 

      //payment related 

  public function check_query_payment_grn_history(){
    $rtps_trans_id=$_GET['rtps'];
    $this->mongo_db->where(array('rtps_trans_id'=>$rtps_trans_id));
    $history=$this->mongo_db->get('pfc_payment_history');
    foreach($history as $trans){
     $res= $this->queryfindgrn($trans);
     $this->mongo_db->set(array('status'=> $res));
     $this->mongo_db->where(array("query_department_id"=> $trans->query_department_id));
     $this->mongo_db->update('pfc_payment_history');
     if( $res === "Y"){
     break;
     }
    }
    //pre($history);
  }

  public function queryfindgrn($app){ 
    //  pre($app->department_id);
        if($app){
          $OFFICE_CODE=$app->query_payment_params->OFFICE_CODE;
          $am1=isset($app->query_payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $app->query_payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
          $am2=isset($app->query_payment_params->CHALLAN_AMOUNT) ? $app->query_payment_params->CHALLAN_AMOUNT : 0;
          $AMOUNT=$am1+$am2;
          $string_field="DEPARTMENT_ID=".$app->query_department_id."&OFFICE_CODE=".$OFFICE_CODE."&AMOUNT=".$AMOUNT;
          // pre($string_field);
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
           
              $data_to_update=array(
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
                'query_payment_status'=>$STATUS);

              $this->mongo_db->set($data_to_update);
              $this->mongo_db->where(array("rtps_trans_id"=> $app->rtps_trans_id));
              $this->mongo_db->update('sp_applications');
              
           }
           return $STATUS;
          }
          return "";
        }
         
      
      }

}
