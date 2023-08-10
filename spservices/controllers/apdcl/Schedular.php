<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Schedular extends frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('apdcl/registration_model');
        
    } //End of __construct()

    public function track_status()
    {
       $applications= $this->get_pending_applications();
      
       if( $applications){
           foreach($applications as $key=>$apps){
            $this->trackAPI( $apps);
           }
       }
     
    } //End of index()
    public function trackAPI($dbrow){
     
        $applNo = isset($dbrow->form_data->application_no) ? $dbrow->form_data->application_no : ''; 
        $subDiv = isset( $dbrow->form_data->sub_division) ?  $dbrow->form_data->sub_division :'';
        
        if((isset($dbrow->service_data->appl_status) && ($dbrow->service_data->appl_status ==="D" || $dbrow->service_data->appl_status==="R")) || empty($applNo) ){
          return;
        }
        //Track API
       $url = 'https://www.apdclrms.com/cbs/onlinecrm/applicationStatus?applNo='.$applNo.'&applType=NSC&subDiv='.$subDiv;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $track = curl_exec($curl);
        curl_close($curl);
        $track = json_decode($track,true);
       
        if(empty($track)){
          return;
        }
        foreach($track as $key)
        {
            $data = array(
              "applStatus" => $key['applStatus'],
              "applStatusId" => $key['applStatusId'],
              "bill_amount" => $key['bill_amount'],
              "billNo" => $key['billNo'], 
              "isBillPaid" => $key['isBillPaid'],
              "document" => $key['document'],
              "paymentLink" => $key['paymentLink'],
              "applView" => $key['applView'],
              "remarks" => $key['remarks'],
              "billDeskMsgUrl" => $key['billDeskMsgUrl'],
              "consNo" => $key['consNo'],
              "applNo" => $key['applNo'],
              "applHistory"=>$key['applHistory'],
              "processing_time" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            );
        }
  
        if($data['applStatusId'] ==="16" || $data['applStatusId'] === 16){
          $st="R";
        }else if($data['applStatus']==="Connection Processed Successfully & CLosed"){
            $st="D";
        }else{
          $st=$dbrow->service_data->appl_status;
        }
        $history=$track[0]['applHistory'];
          $processing_history=array();
          foreach( $history as $his){
              array_push($processing_history,array(
                  "processed_by" => "",
                  "action_taken" => $his['action'],
                  "remarks" => !empty($his['remarks']) ? $his['remarks']: $his['action'],
                  'processing_time' => new UTCDateTime(strtotime( $his['executed_time']) * 1000))
              );
          }
  
        $processing_history_raw[] = $data;
        $data_to_update = [
          'service_data.appl_status'=>$st,
          'processing_history'=> $processing_history,
          'processing_history_raw'=> $processing_history_raw
        ];
      
        $this->registration_model->update($dbrow->_id->{'$id'},$data_to_update);
        return;
    }
    private function get_pending_applications(){
        $operations = 
        array(
            '$and' => array(
              [
              'service_data.service_id'=>'apdcl1',
              'service_data.appl_status'=>array("\$in"=>['submitted','F']),
              'service_data.appl_status'=>array("\$nin"=>['D','R']),
              "form_data.application_no" => array("\$exists" => true,'$nin'=>[null,''])
              ]
            )
               
    );
    
      $collection = 'sp_applications';
    //   $this->mongo_db->select(array("rtps_trans_id","department_id"));
      return $this->mongo_db->get_data_like($operations, $collection);
    }


}//End of reinitiate_applications
