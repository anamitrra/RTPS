<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

class Track extends frontend
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('intermediator_model');
        // $this->load->model('portals_model');
        $this->config->load('rtps_services');
        $this->load->library('AES');
        $this->secret = "rtpsapi#!@";
    }
    private function response($data,$http_code){
        return $this->output
        ->set_content_type('application/json')
        ->set_status_header($http_code)
        ->set_output(json_encode($data));
    }

    public function myapplications(){
        
        $filter = file_get_contents('php://input');
      
        $auth = getallheaders();
        $token=isset($auth['authorization']) ? $auth['authorization'] : (isset($auth['Authorization']) ? $auth['Authorization'] : false); 
        if (!empty(  $filter)) {
            $data = json_decode(  $filter, true);
            
            if(!$token  || ($token !==$this->secret) ){
               return $this->response(array("status" => false, "message" => "Unauthorized"),200);
            }
            if (empty($data['user_type'])) {
                return $this->response(array("status" => false, "message" => "Please provide user type"),200);
            }
            if ($data['user_type'] === "USER" && empty($data['mobile'])) {
                return $this->response(array("status" => false, "message" => "Please provide citizen mobile no"),200);
            }

            if ($data['user_type'] === "PFC" && empty($data['pfc_id'])) {
                return $this->response(array("status" => false, "message" => "Please provide pfc Id"),200);
            }
            if ($data['user_type'] === "CSC" && empty($data['csc_id'])) {
                return $this->response(array("status" => false, "message" => "Please provide csc Id"),200);
            }
            $filters=array();
            if($data['user_type'] === "USER"){
                $filters['mobile']=$data['mobile'];
            }elseif($data['user_type'] === "PFC"){
                $filters['applied_by']= new ObjectId($data['pfc_id']);
            }elseif($data['user_type'] === "CSC"){
                $filters['applied_by']= $data['csc_id'];
            }
            if(!empty($data['app_ref_no'])){
                $filters['$or']=array(
                    array("app_ref_no" => $data['app_ref_no']),
                    array("vahan_app_no" => $data['app_ref_no'])
                );
            }else{
                if(!empty($data['from_date'] && !empty($data['end_date']))){
                    // $filters['createdDtm']=['$gte'=> new MongoDB\BSON\UTCDateTime(strtotime($data['from_date']) * 1000),'$lte'=>new MongoDB\BSON\UTCDateTime(strtotime($data['end_date']) * 1000)];
                    $filters['createdDtm']=['$gte'=> new MongoDB\BSON\UTCDateTime(strtotime($data['from_date']) * 1000),'$lte'=>new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($data['end_date'])) * 1000)];
                    $limit=false;
                }else{
                    
                    $from_date=date('Y-m-d',strtotime('-30days') );
                    $end_date=date('Y-m-d');
                    // $filters['createdDtm']=['$gte'=> new MongoDB\BSON\UTCDateTime(strtotime( $from_date) * 1000),'$lte'=>new MongoDB\BSON\UTCDateTime(strtotime($end_date) * 1000)];
                    // $filters['createdDtm']=['$gte'=> new MongoDB\BSON\UTCDateTime(strtotime( $from_date) * 1000),'$lte'=>new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($end_date)) * 1000)];
                  $limit=50;
                }
               
                if($data['status'] === "D"){
                    $filters['delivery_status']="D";
                }else{
                    $filters['$or']=array(
                        array("delivery_status" => array("\$exists" => false)),
                        array("delivery_status" => array("\$exists" => true,'$ne'=>"D")),
                    );
                }
               
            }
            
           $data= $this->intermediator_model->myapps($filters,$limit);
           $response_data=array();
           foreach( $data as $apps){
            $item = $this->format_data($apps);
            if($item )
            array_push(  $response_data,$item);
           }
         
          
           return $this->response(array("status" => true, "data" => $response_data),200);
           
        }else {
            return $this->response(array("status" => false, "message" => "No data found"),200);
        }

    }
    public function status()
    {
        $filter = file_get_contents('php://input');
        if (!empty(  $filter)) {
            $data = json_decode(  $filter, true);
            
            if($data['secret'] !==$this->secret ){
               return $this->response(array("status" => false, "message" => "Unauthorized"),200);
            }
            if (empty($data['app_ref_no'])) {
                return $this->response(array("status" => false, "message" => "No data found"),200);
            }
         

           $data= $this->intermediator_model->apps_ref($data['app_ref_no']);
           $response_data = $this->format_data($data);
           return $this->response(array("status" => true, "data" => $response_data),200);
           
        }else {
            return $this->response(array("status" => false, "message" => "No data found"),200);
        }
        
    }
    private function format_data($data){
        $formated_data=array();
        if($data){
            $app_no= !empty($data->app_ref_no) ? $data->app_ref_no : (!empty($data->vahan_app_no) ? $data->vahan_app_no:"");
            $applicant_name = (!empty($data->applicant_details) && is_array($data->applicant_details) )? $data->applicant_details[0]->applicant_name : "";
        
            if(empty($applicant_name)){
                $applicant_name=isset($data->applicant_name) ? $data->applicant_name : '';
            }
            $payment_status=null;
            if(property_exists($data,'pfc_payment_status')){
                if($data->pfc_payment_status === "Y"){
                    $payment_status="Y";
                }elseif($data->pfc_payment_status === "N"){
                    $payment_status="N";
                }else{
                    $payment_status="P";
                }

            }
            $btns=[];
            array_push($btns,array('name'=>"Detail",'url'=>base_url('iservices/intermediator/detail/').$data->rtps_trans_id));
            $st="Under Process";
            if(!empty($data->delivery_status) && $data->delivery_status === "D"){
                $st="Delivered";
            }
            if(!empty($data->delivery_status) && $data->delivery_status === "R"){
                $st="Rejected";
            }
            $formated_data=array(
                'initiated_data'=>array(
                  'rtps_trans_id'=> $data->rtps_trans_id,
                  'appl_ref_no'=> $app_no,
                  'service_name'=>$data->service_name,
                  'applicant_name'=>$applicant_name,
                  'mobile'=> $data->mobile,
                  'submission_date'=>isset($data->submission_date) ? $data->submission_date :'',
                  'service_timeline'=>!empty($data->portal) ?  $data->portal->timeline_days : null,
                  'status_code'=>$data->status,
                  'payment_status'=>$payment_status,
                  'status'=>$st,
                  'createdDtm'=>format_mongo_date($data->createdDtm,'Y-m-d')
                ),
                'execution_data'=>[],
                'action_data'=>$btns 
                );
        }
        return $formated_data;
    }
    private function create_buttons($data){
        $btns=array();
        if($data){
            $applied_by=property_exists($data,'applied_by') ? 'KIOSK':'USER';
            if($data->portal_no === "5" || $data->portal_no === 5){
                if( $applied_by === "KIOSK"){

                }else{

                }
            }
        }
        return $btns;
    }
}
