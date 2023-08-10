<?php

/**
 * Description of Dashboard
 *
 * @author 
 *
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Find_application extends Rtps
{
  //put your code here
  public function __construct()
  {
    parent::__construct();
   $this->load->model("intermediator_model");
   $this->load->model("spservices/registered_deed_model");
   $this->config->load('rtps_services');
   $this->load->helper('appstatus');
  }
  /**
   * index
   *
   * @return void
   */
  public function index()
  {
    $this->load->view("includes/header", array("pageTitle" => "Dashboard"));
    $this->load->view("admin/dashboards/find_application");
    $this->load->view("includes/footer");
  }
  public function find(){
    $data=array();
    if(!empty($_POST) && !empty($_POST['app_ref_no'])){
       
        $app_ref_no=$this->input->post('app_ref_no');
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
        $d=(array) $data['preview'];
         if(count( $d) > 0){
           $ref=modules::load('iservices/admin/Transactions');
           $data['intermediate_ids']=$ref->prepare_transactions($data['preview']);
            if( property_exists($d[0],'applied_by')){
               
                $data['page_path']='admin/mytransactions/transactions';
            }else{
                $data['page_path']='mytransactions/transactions';
                
            }
           
         }else{
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
              $collection = 'sp_applications';
              $data['preview']=$this->mongo_db->get_data_like($operations, $collection);
              
              $d=(array) $data['preview'];
              $data['intermediate_ids']=array();
              if(count( $d) > 0){
                  $service_id=$d[0]->service_data->service_id;
                  $ref=$this->get_page($service_id);
                    if($ref){
                        $data['page_path']=$ref['path'];
                        $data[$ref['name']]=$data['preview'];
                    }
                  
              }
         }



    }

    $this->load->view("includes/header", array("pageTitle" => "Dashboard"));
    $this->load->view("admin/dashboards/find_application",$data);
    $this->load->view("includes/footer");
   
    
  }
  public function get_page($service_id){
        switch($service_id){
            case "INC":
                $action=array(
                    "path"=>"spservices/applications_view/incomecertificate_view",
                    "name"=>"incomecertificate",
                );
               
                break;
            default :
                $action=array();
            break;
        }
        return $action;
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
      $collection = 'sp_applications';
      $data['preview']=$this->mongo_db->get_data_like($operations, $collection);
      $d=(array) $data['preview'];
    
     if(count( $d) > 0){
       if(property_exists($d[0],'department_id') || (property_exists($d[0],'form_data') && property_exists($d[0]->form_data,'department_id'))){
        //  pre($d[0]->form_data);
         $this->mongo_db->where("rtps_trans_id",$app_ref_no);
         $this->mongo_db->select(array("department_id","query_department_id","form_data.department_id","status"));
         $data['payment_history']=$this->mongo_db->get("pfc_payment_history");
         }
         $data['page_path']=null;
       
     
        
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
  

}
