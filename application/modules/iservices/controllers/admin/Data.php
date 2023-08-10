<?php

/**
 * Description of Dashboard
 *
 * @author 
 *
 */
defined('BASEPATH') or exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Data extends Rtps
{
  //put your code here
  public function __construct()
  {
    parent::__construct();
   $this->load->model("intermediator_model");
   $this->load->model("spservices/registered_deed_model");
   $this->config->load('rtps_services');
  }
  /**
   * index
   *
   * @return void
   */
  public function index()
  {
      if($this->session->userdata('role')->slug === "SA"){
        $this->load->view("includes/header", array("pageTitle" => "Dashboard"));
        $this->load->view("admin/dashboards/data");
        $this->load->view("includes/footer");
      }
    
   
  }
  public function find(){
    $app_ref_no=$this->input->post('app_ref_no');
    if(empty($app_ref_no)){
      redirect(base_url('iservices/admin/data'));
    }
    $operations = 
    array(
      '$or'=>array(
        array("app_ref_no" => $app_ref_no),
        array("rtps_trans_id" => $app_ref_no),
        array("vahan_app_no" => $app_ref_no),
        array("pfc_payment_response.GRN" => $app_ref_no),
        array("mobile" => $app_ref_no),
      
       
      )
           
);
      $collection = 'intermediate_ids';
         
      $data['preview']=$this->mongo_db->get_data_like($operations, $collection);

    //  if(!empty($app_ref_no)){
    //         $data['preview']=$this->intermediator_model->get_application_details_array(array('app_ref_no'=>$app_ref_no));
          
    //  }else if(!empty($rtps_trans_id)){
    //     $data['preview']=$this->intermediator_model->get_application_details_array(array('rtps_trans_id'=>$rtps_trans_id));
    //  }
    //  else if(!empty(  $grn)){
    //   $data['preview']=$this->intermediator_model->get_application_details_array(array('pfc_payment_response.GRN'=>$grn));
    //  }
     $d=(array) $data['preview'];
    
     if(count( $d) > 0){
       if(property_exists($d[0],'department_id')){
         $this->mongo_db->where("rtps_trans_id",$d[0]->rtps_trans_id);
         $this->mongo_db->select(array("department_id","query_department_id","status"));
         $data['payment_history']=$this->mongo_db->get("pfc_payment_history");
         }
       
       $ref=modules::load('iservices/admin/Transactions');
       $data['intermediate_ids']=$ref->prepare_transactions($data['preview']);
        if( property_exists($d[0],'applied_by')){
           
            $data['page_path']='admin/mytransactions/transactions';
        }else{
            $data['page_path']='mytransactions/transactions';
            
        }

        if(property_exists($d[0],'pfc_payment_response')){
          $data["payment_status"]=$this->get_payment_status($d[0]->pfc_payment_response);
        }else{
          $data['payment_status']="Payment Info Not Found";
        }
        
        $this->load->view("includes/header", array("pageTitle" => "Dashboard"));
        
        $this->load->view("admin/dashboards/application_preview",$data);
        $this->load->view("includes/footer");
     }else{
         echo "No data found";
     }
    
  }
  private function get_payment_status($row){
    if($row){
      if($row->STATUS === "Y")
      $stats="Success";
      if($row->STATUS === "N")
      $stats="Failed";
      if($row->STATUS === "A")
      $stats="Abort";
      if($row->STATUS === "P" || $row->STATUS === "")
      $stats="Pending";
      return $stats;
    }
  }
  public function findspapplication(){
    $app_ref_no=$this->input->post('app_ref_no');
    $operations = 
    array(
      '$or'=>array(
        array("app_ref_no" => $app_ref_no),
        array("rtps_trans_id" => $app_ref_no),
        array("pfc_payment_response.GRN" => $app_ref_no),
        array("mobile" => $app_ref_no),
        array("service_data.appl_ref_no" => $app_ref_no),
        array("form_data.mobile" => $app_ref_no),
        array("form_data.pfc_payment_response.GRN" => $app_ref_no),
      
       
      )
           
      );
// pre($operations);
      $collection = 'sp_applications';
         
      $data['preview']=$this->mongo_db->get_data_like($operations, $collection);

  //  pre($data['preview']);
     $d=(array) $data['preview'];
    
     if(count( $d) > 0){
       if(property_exists($d[0],'department_id') || (property_exists($d[0],'form_data') && property_exists($d[0]->form_data,'department_id'))){
        //  pre($d[0]->form_data);
         $this->mongo_db->where("rtps_trans_id",$app_ref_no);
         $this->mongo_db->select(array("department_id","query_department_id","form_data.department_id","status"));
         $data['payment_history']=$this->mongo_db->get("pfc_payment_history");
         }
         $data['page_path']=null;
       
      //  $ref=modules::load('iservices/admin/Transactions');
      //  $data['intermediate_ids']=$ref->prepare_transactions($data['preview']);
      //   if( property_exists($d[0],'applied_by')){
           
      //       $data['page_path']='admin/mytransactions/transactions';
      //   }else{
      //       $data['page_path']='mytransactions/transactions';
            
      //   }
        
      if(property_exists($d[0],'pfc_payment_response')){
        $data["payment_status"]=$this->get_payment_status($d[0]->pfc_payment_response);
      }else if(property_exists($d[0],'form_data') && property_exists($d[0]->form_data,'pfc_payment_response')){
        $data["payment_status"]=$this->get_payment_status($d[0]->form_data->pfc_payment_response);
      }else{
        $data['payment_status']="Payment Info Not Found";
      }

        $this->load->view("includes/header", array("pageTitle" => "Dashboard"));
        
        $this->load->view("admin/dashboards/application_preview",$data);
        $this->load->view("includes/footer");
     }else{
         echo "No data found";
     }
    
  }
  public function send_sms($type){
    $rtps_trans_id="AS221005A7876407";
    if($rtps_trans_id){
      $data=$this->intermediator_model->get_by_rtps_id($rtps_trans_id);
    }
    if( $data){
      $sms=array(
        "mobile"=>"9742447516",
        "applicant_name"=>property_exists($data,"applicant_details") ? $data->applicant_details[0]->applicant_name : $data->mobile,
        "service_name"=>$data->service_name,
        "submission_date"=>$data->submission_date,
        "app_ref_no"=>$data->app_ref_no,
        "rtps_trans_id"=>$data->rtps_trans_id
      );
    
      $this->sms_provider($type,$sms);
    }
    pre("skks");
  }

  public function sms_provider($type,$data)
  {
     
      switch($type){
          case "submission":
                  // $message_body="Dear ".$data['applicant_name']." Your ARTPS Application for ".$data['service_name']." service has been submitted successfully on [date & time".$data['submission_date']."] .Your Reference No. is ".$data['app_ref_no'].". Please use thisRef.No. for tracking your application & for future communication";
                  $message_body="Dear ".$data['applicant_name']." Your ARTPS Application for ".$data['service_name']." service has been submitted successfully on [date & time".$data['submission_date']."] .Your Reference No. is ".$data['app_ref_no'].". Please use thisRef.No. for tracking your application & for future communication";
               
                  //$message_body="Dear Saheb Your ARTPS Application for Test service has been submitted successfully on [date & time20/01/2022].Your Reference No. is SYED. Please use thisRef.No. for tracking your application & for future communication";
                  $dlt_template_id="1007160707760375551";      
                  $this->sendSms($data['mobile'],$message_body,$dlt_template_id);
                  break;
          case "delivery":
                  $message_body="Dear ".$data['applicant_name']." Your ARTPS Application for ".$data['service_name']." service with Reference No. ".$data['app_ref_no']." submitted on [date & time".$data['submission_date']."] has been delivered successfully";
                  $dlt_template_id="1007160707768151335";
                  $this->sendSms($data['mobile'],$message_body,$dlt_template_id);
                   break;
          case "rejection":
                      $message_body="Dear ".$data['applicant_name']." Your ARTPS Application for  ".$data['service_name']." service with Reference No. ".$data['app_ref_no']." submitted on [date & time".$data['submission_date']."] has been rejected by [office".$data['submission_office']."]";
                      $dlt_template_id="1007160707771234603";
                      $this->sendSms($data['mobile'],$message_body,$dlt_template_id);
                       break;
          case "query":
                      $message_body="Dear ".$data['applicant_name']." , A query has been raised against your application for ".$data['service_name']." service with Application Ref No: ".$data['app_ref_no'].". Please Log on to https://rtps.assam.gov.in/portal,then go to View Status of Application and then click Track application status for further details.";
                      $dlt_template_id="1007161322342756876";
                      $this->sendSms($data['mobile'],$message_body,$dlt_template_id);
                      break;
          default:
                  break;
      }
  }

  function sendSms($number, $message_body,$dlt_template_id) {
            
    $ch = curl_init();
    // $message_body  = urlencode($message_body);
    // $message_body = str_replace("+", "%20", $message_body);
    
    $url = "https://smsgw.sms.gov.in/failsafe/MLink?username=rtpsasm.otp&pin=P4%26fO3%23xF4&message=" . urlencode($message_body) . "&mnumber=" . $number . "&signature=ARTPSA&dlt_entity_id=1001367290000017171&dlt_template_id=" . $dlt_template_id;
 
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $head = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    var_dump($head);die;
    return $head;
    }//End of sendSms()

    //payment related 

    public function payment_info(){
      if($this->session->userdata('role')->slug === "SA"){
        $this->load->view("includes/header", array("pageTitle" => "Dashboard"));
        $this->load->view("admin/dashboards/payment_info");
        $this->load->view("includes/footer");
      }
    
    }

    public function find_payment(){
      $payment_type=$this->input->post('payment_type');
      $app_ref_no=$this->input->post('app_ref_no');
      $rtps_trans_id= $this->input->post('rtps_trans_id');
      $data=array();
      if($payment_type === "KIOSK"){
        if($app_ref_no){
                $app=$this->intermediator_model->get_application_details_array(array('app_ref_no'=>$app_ref_no));    
        }else if($rtps_trans_id){
          $app=$this->intermediator_model->get_application_details_array(array('rtps_trans_id'=>$rtps_trans_id));
          }
          $d=(array) $app;
          if(count($d) > 0)
          $d=$d[0];
      }
      elseif($payment_type === "QUERY"){
         $app=$this->registered_deed_model->get_row(array('rtps_trans_id'=>$rtps_trans_id));
         $d=$app;
      }
    
 
     if(!empty($d)){
       // $data_array=(array)$data['preview'];
      
       $data['rtps_trans_id']= isset($d->rtps_trans_id)?$d->rtps_trans_id:"";
       $data['app_ref_no']= isset($d->app_ref_no)?$d->app_ref_no:"";
        if($payment_type === "KIOSK"){
          if(property_exists( $d,"department_id")){
            $data['payment_data']= $d->pfc_payment_response;
            $data['OFFICE_CODE']= $d->payment_params->OFFICE_CODE;
            $data['department_id']= $d->department_id;
            $data['payment_type']= "KIOSK";
            $data['d']=  $d;
            $data['status']= true;
            
          }
        }elseif($payment_type === "QUERY"){
          if(property_exists( $d,"query_department_id") ){
            $data['payment_data']= $d->query_payment_response;
            $data['payment_type']= "QUERY";
            $data['OFFICE_CODE']= $d->query_payment_params->OFFICE_CODE;
            $data['query_department_id']= $d->query_department_id;
            $data['status']= true;
            $data['d']=  $d;
          }
        }
        $this->load->view("includes/header", array("pageTitle" => "Dashboard"));
        // pre($data);
        $this->load->view("admin/dashboards/payment_preview",$data);
        $this->load->view("includes/footer");
     }else{
         echo "No data found";
     }
    
  }


  public function ack()
  {
    $this->load->model("portals_model");
    if($this->session->userdata('role')->slug === "SA"){
      if (empty($_GET['app_ref_no'])) {
        exit("please provide app_ref_no");
        redirect(base_url("iservices/transactions"));
      }
      $app_ref_no = $_GET['app_ref_no'];
      $application_details = $this->intermediator_model->get_application_details(array("app_ref_no" => $app_ref_no));
      // var_dump($application_details);die;
      if ($application_details->service_id){
        $departmental_data = $this->portals_model->get_departmental_data($application_details->service_id);
        if(isset($departmental_data->payment_required) && $departmental_data->payment_required){
          if($application_details->pfc_payment_status !== "Y"){
            redirect('iservices/transactions');
            return;
          }
        }
      }
      
      else
        redirect('iservices/transactions');
      $data = array();
      $data['response'] = $application_details;
      $data['timeline_days'] = $departmental_data->timeline_days;
      $data['department_name'] = $departmental_data->department_name;
      $data['service_name'] = $departmental_data->service_name;
  
      $this->load->view('includes/frontend/header');
      $this->load->view('noc_ack1', $data);
      $this->load->view('includes/frontend/footer');
    }else{
      exit("Not authorized");
    }

   
  }

  public function submit_crcpy(){
    $ref_no=$_GET['ref'];
    if($ref_no){
      $this->config->load('spservices/spconfig');
      $this->load->model('spservices/registered_deed_model');
      $dbRow = $this->registered_deed_model->get_row(array("rtps_trans_id"=>$ref_no));
      if (property_exists($dbRow, 'pfc_payment_status') && $dbRow->pfc_payment_status == 'Y') {
        if($dbRow->status === "payment_initiated"){
          $obj= $dbRow->_id->{'$id'};
          //procesing data
          $processing_history = $dbRow->processing_history??array();
          $processing_history[] = array(
              "processed_by" => "Application submitted & payment made by KIOSK for ".$dbRow->applicant_name,
              "action_taken" => "Application Submition",
              "remarks" => "Application submitted & payment made by KIOSK for ".$dbRow->applicant_name,
              "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
          );
       
          if(property_exists($dbRow,"serial_registration_not_availabe") && $dbRow->serial_registration_not_availabe === "1"){
             $deedno="Year from: ".$dbRow->year_from.",Year To: ".$dbRow->year_to.",Party Name: ".$dbRow->deed_party_name.",Patta No: ".$dbRow->deed_patta_no.",Daag No: ".$dbRow->deed_dag_no.",Land Area: ".$dbRow->deed_total_land_area;
          }else{
             $deedno="SL No: ".$dbRow->deedno.",Reg No: ".$dbRow->year_of_registration;
          }
         $postdata=array(
             'deed_no'=> $deedno,
             'applicant_name'=>$dbRow->applicant_name,
             'mobile'=>$dbRow->mobile,
             'address'=>$dbRow->address,
             'relation'=>$dbRow->relation,
             'date_of_application'=>date('Y-m-d'),
             'service_mode'=>$dbRow->service_mode,
             'application_ref_no'=>$dbRow->rtps_trans_id,
             'sro_code'=>!empty($dbRow->sro_code) ? $dbRow->sro_code : "",
             'spId'=>array('applId'=>$dbRow->applId)
         );
       
         $url=$this->config->item('url');
          
         $curl = curl_init($url."cercpy/post_certicopy.php");
         // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
         curl_setopt($curl, CURLOPT_POST, true);
         curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
         $response = curl_exec($curl);
         if (curl_errno($curl)) {
          $error_msg = curl_error($curl);
          }
         curl_close($curl);
        if($response){
         $response = json_decode($response);
        
         if($response->ref->status === "success"){
             $data_to_update=array(
                 'status'=>'submitted',
                 'submission_date'=>new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                 'processing_history'=>$processing_history
             );
             $this->registered_deed_model->update($obj,$data_to_update);
              //Add to processing history   
              echo "updated";     
            
         }else{
           echo "err_mgs : ". $error_msg." response from bacjend  : ".$response;
         }
            
        }else{
         echo "err_mgs : ". $error_msg." response from bacjend  : ".$response;
        }




        }else{
          die("payment has not done");
        }
        
    }else{
      die("no payment info found");
    }
      
    }
  }

}
