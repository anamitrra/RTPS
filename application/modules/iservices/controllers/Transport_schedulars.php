<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
class Transport_schedulars extends Frontend
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('intermediator_model');
    $this->config->load('rtps_services');
    $this->load->library('AES');
    
  }
  

  //scheduler for pending vahan application 

  public function update_sarathi_app(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $applications=$this->intermediator_model->get_sarathi_pending_application();
    foreach ($applications as $key => $value) {
      if(isset($value->applied_by) && (!isset($value->pfc_payment_status) || $value->pfc_payment_status !=='Y' ) ){
          //pfc payment is pendig so skip for now
      }else{
        $app_ref_no=isset($value->app_ref_no)?$value->app_ref_no:false;
        if($app_ref_no){
          $data = $this->fetch_transport_app_status($app_ref_no);
          if($data){
              $data_to_save=array(
                  "delivery_status"=>$data['initiated_data']['status'],
                  "execution_data"=>$data['execution_data']
              );
              $this->intermediator_model->update_row(array("app_ref_no"=>$app_ref_no),$data_to_save);
          }
        }
      }
    }

  }

  public function update_vahan_app(){
    ini_set('max_execution_time', 0);
    ini_set('memory_limit', '-1');
    $applications=$this->intermediator_model->get_vahan_pending_application();
    foreach ($applications as $key => $value) {
    if(isset($value->applied_by) && (!isset($value->pfc_payment_status) || $value->pfc_payment_status !=='Y' ) ){
        //pfc payment is pendig so skip for now
    }else{
      $app_ref_no=isset($value->vahan_app_no)?$value->vahan_app_no:false;
      
      if($app_ref_no){
        $data=$this->fetch_transport_app_status($app_ref_no);
        if($data){
          $data_to_save=array(
              "delivery_status"=>$data['initiated_data']['status'],
              "execution_data"=>$data['execution_data']
          );
          $this->intermediator_model->update_row(array("vahan_app_no"=>$app_ref_no),$data_to_save);
         
          }
      }
    }
     
    }

  }

  private function fetch_transport_app_status($app_ref_no=null){
    
    if(!empty($app_ref_no)){
            
            $data=array(
                "app_ref_no"=>$app_ref_no,
                "secret"=>"rtpsapi#!@"
            );
            $url="https://rtps.assam.gov.in/tools/rtps_id_labels/src/api/external_apis.php/get_mis_data";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer |0VW?z,-w2w"6;{b8v}K6A5+Fdf@l-',
                'Content-Type: application/json'
            ));
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if($response ){
                $response= json_decode($response,true);
                if($response['status'] && !empty($response['data'])){
                    return $response['data'];
                }
            }
            return false;
          
    }else{
      return false;
    }

  }



}
