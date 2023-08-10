<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\UTCDateTime;

class Misapi extends frontend
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('intermediator_model');
        $this->load->model('portals_model');
        $this->load->library('AES');
        $this->encryption_key = $this->config->item("encryption_key");
    }
    public function update_app_status()
    {
        $filter = file_get_contents('php://input');
        if (!empty(  $filter)) {
            $data = json_decode(  $filter, true);
            if($data['secret'] !=="rtpsapi#!@" ){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "Unauthorized")));
            }
            if (isset($data['app_ref_no'])) {
                $dbRow = $this->intermediator_model->get_row_data_by_app_ref_no($data['app_ref_no'],array('app_ref_no','portal_no','mobile'));
                if (empty($dbRow)) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no")));
                }

              $ref=modules::load('iservices/Schedulers');
              if($dbRow->portal_no === "5" || $dbRow->portal_no === 5){
               $r= $ref->basundhara_status_by_app($data['app_ref_no']);
              
              } elseif($dbRow->portal_no === "1" || $dbRow->portal_no === 1){
                $r= $ref->noc_status_by_app($data['app_ref_no']);
              }

              if( $r){
                $row = $this->intermediator_model->get_row(array('app_ref_no'=>$data['app_ref_no']));
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array("data"=> array("status"=>$row->delivery_status,"execution_data"=>$row->execution_data),"status" => true)));
               }else{
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false)));
               }


            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array("status" => false, "message" => "No data found")));
            }
        }else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "No data found")));
        }
        
    }

    public function update_app_statusv2()
    {
        $filter = file_get_contents('php://input');
        if (!empty(  $filter)) {
            $data = json_decode(  $filter, true);
            if($data['secret'] !=="rtpsapi#!@" ){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "Unauthorized")));
            }
            if (isset($data['app_ref_no'])) {
                $dbRow = $this->intermediator_model->get_row_data_by_app_ref_no($data['app_ref_no'],array('app_ref_no','portal_no','mobile',"delivery_status"));
                if (empty($dbRow)) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no")));
                }
                if( property_exists($dbRow,"delivery_status") && ($dbRow->delivery_status === "D" || $dbRow->delivery_status === "R") ){
                    $r=true;
                }else{
                   
                    $ref=modules::load('iservices/Schedulers');
                    if($dbRow->portal_no === "5" || $dbRow->portal_no === 5){
                     $r= $ref->basundhara_status_by_app($data['app_ref_no']);
                    
                    } elseif($dbRow->portal_no === "1" || $dbRow->portal_no === 1){
                      $r= $ref->noc_status_by_app($data['app_ref_no']);
                    }else{
                      $r= $ref->others_status_by_app($data['app_ref_no']);
                    }
                }
              
             
                $row = $this->intermediator_model->get_row(array('app_ref_no'=>$data['app_ref_no']));
                $service_timeline=$this->portals_model->get_data(array('service_id'=> strval( $row->service_id)),array('timeline_days'));
                $execuation_data=array();
               if(!empty($row->execution_data)){
                   foreach($row->execution_data as $execution){
                       array_push($execuation_data,array(
                           'processed_by'=>$execution->user_name,
                           'action_taken'=>$execution->action,
                           "remarks"=>$execution->remark,
                           "processing_time"=> !empty($execution->executed_time) ? date("Y-m-d H:i:s",strtotime($execution->executed_time) ) : ""
                       ));
                   }
               }

                $response_data=array(
                    'status'=>true,
                    "data"=>array(
                        "initiated_data"=>array(
                            "appl_ref_no"=>$data['app_ref_no'],
                            "submission_date"=> !empty( $row->submission_date) ? date("Y-m-d H:i:s",strtotime( $row->submission_date) )  : '',
                            "status"=>!empty($row->delivery_status)? $row->delivery_status: "P",
                            "applicant_name"=>!empty($row->applicant_details) ? $row->applicant_details[0]->applicant_name : '',
                            "service_name"=>!empty($row->service_name)? $row->service_name: "",
                            "service_id"=>!empty($row->service_id)? $row->service_id: "",
                            "service_timeline"=> $service_timeline ?  $service_timeline->timeline_days : false

                        ),
                        "execution_data"=>$execuation_data
                        ),
                        
                );

                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($response_data));
               


            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no")));
            }
        }else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "No data found")));
        }
        
    }
    private function get_row($app_ref_no){
        if($app_ref_no){
            $this->mongo_db->select(array('form_data.edistrict_ref_no','form_data.applicant_name','processing_history','service_data'));
            $this->mongo_db->where(array("service_data.appl_ref_no"=>$app_ref_no));
            return $this->mongo_db->find_one("sp_applications");
        }
       
    }
    public function get_edistric_app_data(){
        $filter = file_get_contents('php://input');
       
        if (!empty(  $filter)) {
            $data = json_decode(  $filter, true);
            if($data['secret'] !=="rtpsapi#!@" ){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "Unauthorized")));
            }
            if (isset($data['app_ref_no'])) {
                $dbRow = $this->get_row($data['app_ref_no']);
               
                if (empty($dbRow) || empty($dbRow->form_data->edistrict_ref_no)) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no")));
                }

                //
                $this->load->helper('trackstatus');
                fetchEdistrictData($dbRow->form_data->edistrict_ref_no);
                $row = $this->get_row($data['app_ref_no']);
                // pre( $row );




              if( $row ){
                  foreach($row->processing_history  as $item){
                    $item->processing_time =  format_mongo_date( $item->processing_time,'Y-m-d H:i:s');    
                }
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array("data"=> array("status"=>$row->service_data->appl_status,"execution_data"=>$row->processing_history),"status" => true)));
               }else{
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false)));
               }


            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array("status" => false, "message" => "No data found")));
            }
        }else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "No data found")));
        }
    }

    public function get_edistric_app_status(){
        $filter = file_get_contents('php://input');
       
        if (!empty(  $filter)) {
            $data = json_decode(  $filter, true);
            if($data['secret'] !=="rtpsapi#!@" ){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "Unauthorized")));
            }
            if (isset($data['app_ref_no'])) {
                $dbRow = $this->get_row($data['app_ref_no']);
               
                if (empty($dbRow) || empty($dbRow->form_data->edistrict_ref_no)) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no")));
                }

                //
                $this->load->helper('trackstatus');
                fetchEdistrictData($dbRow->form_data->edistrict_ref_no);
                $row = $this->get_row($data['app_ref_no']);
                // pre( $row );




              if( $row ){
                  foreach($row->processing_history  as $item){
                    $item->processing_time =  format_mongo_date( $item->processing_time,'Y-m-d H:i:s');    
                }

                $response_data=array(
                    'status'=>true,
                    "data"=>array(
                        "initiated_data"=>array(
                            "appl_ref_no"=>$data['app_ref_no'],
                            "submission_date"=> !empty( $row->service_data->submission_date) ? format_mongo_date($row->service_data->submission_date,"Y-m-d H:i:s" )  : '',
                            "status"=>!empty($row->service_data->appl_status)? $row->service_data->appl_status: "P",
                            "applicant_name"=>!empty($row->form_data->applicant_name) ? $row->form_data->applicant_name : '',
                            "service_name"=>!empty($row->service_data->service_name)? $row->service_data->service_name: "",
                            "service_id"=>!empty($row->service_data->service_id)? $row->service_data->service_id: "",
                            "service_timeline"=>!empty($row->service_data->service_timeline)  ?  $row->service_data->service_timeline : false

                        ),
                        "execution_data"=>$row->processing_history
                        ),
                        
                );

                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode( $response_data));
               }else{
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false,"message" => "No records found with this app ref no")));
               }


            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no")));
            }
        }else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "No data found")));
        }
    }


    public function apdclTrackAPI($obj_id){
        $this->load->model("spservices/apdcl/registration_model");
        $dbrow = $this->registration_model->get_by_doc_id($obj_id);
        if(!isset($dbrow->form_data->application_no)){
            return false;
        }
        if(isset($dbrow->service_data->appl_status) && ( $dbrow->service_data->appl_status==="D" || $dbrow->service_data->appl_status==="R")){
            $history= (array) $dbrow->processing_history_raw[0];
            return  $history;
        }
        $applNo = $dbrow->form_data->application_no; 
        $subDiv = $dbrow->form_data->sub_division;
        //Track API
       $url = 'https://www.apdclrms.com/cbs/onlinecrm/applicationStatus?applNo='.$applNo.'&applType=NSC&subDiv='.$subDiv;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $track = curl_exec($curl);
        curl_close($curl);
        $track = json_decode($track,true);
        if(empty( $track )){
            return false;
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
            // pre(date("Y-m-d",strtotime($his['executed_time'])));
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
            'processing_history'=>$processing_history,
            'processing_history_raw'=> $processing_history_raw
          ];
         
          $this->registration_model->update($obj_id,$data_to_update);
          
          return  $data_to_update['processing_history_raw'][0];
    }
  
    //apdcl api
    public function get_apdcl_app_status(){
        $filter = file_get_contents('php://input');
       
        if (!empty(  $filter)) {
            $data = json_decode(  $filter, true);
            if($data['secret'] !=="rtpsapi#!@" ){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "Unauthorized")));
            }
            if (isset($data['app_ref_no'])) {
                $dbRow = $this->get_row($data['app_ref_no']);
                if (empty($dbRow)) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no")));
                }

               $res=$this->apdclTrackAPI($dbRow->_id->{'$id'});
               $history=array();
               if( $res ){
                 $history=$res['applHistory'];
                $processing_history_raw=array();
                  foreach($history as $items){ 
                    $item = is_array( $items) ?  $items : (array)  $items;
                    array_push( $processing_history_raw,array(
                        'processed_by'=>'',
                        'action_taken'=>$item['action'],
                        'remarks'=>!empty($item['remarks']) ? $item['remarks']: $item['action'],
                        'processing_time'=>date("Y-m-d H:i:s",strtotime( $item['executed_time']))
                    ));
                }
         
                if($res['applStatusId'] ==="16" || $res['applStatusId'] === 16){
                    $st="R";
                }else if($res['applStatus']==="Connection Processed Successfully & CLosed"){
                    $st="D";
                }else{
                    $st="F";
                }
               
                $response_data=array(
                    'status'=>true,
                    "data"=>array(
                        "initiated_data"=>array(
                            "appl_ref_no"=>$data['app_ref_no'],
                            "submission_date"=> !empty( $dbRow->service_data->submission_date) ? format_mongo_date($dbRow->service_data->submission_date,"Y-m-d H:i:s" )  : '',
                            "status"=>$st,
                            "applicant_name"=>!empty($dbRow->form_data->applicant_name) ? $dbRow->form_data->applicant_name : '',
                            "service_name"=>!empty($dbRow->service_data->service_name)? $dbRow->service_data->service_name: "",
                            "service_id"=>!empty($dbRow->service_data->service_id)? $dbRow->service_data->service_id: "",
                            "service_timeline"=>!empty($dbRow->service_data->service_timeline)  ?  $dbRow->service_data->service_timeline : false,
                            "application_no"=>!empty($res['applNo'])  ?  $res['applNo'] : false,
                            "consNo"=>!empty($res['consNo'])  ?  $res['consNo'] : false,
                        ),
                        "execution_data"=>$processing_history_raw
                        ),
                        
                );

                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode( $response_data));
               }else{
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false,"message" => "No records found with this app ref no")));
               }


            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no")));
            }
        }else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "No data found")));
        }
    }


}
