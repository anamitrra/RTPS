<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Payment extends Rtps {
    public function __construct() {
        parent::__construct();
        $this->load->model('applications_model');
        $this->load->model('delayeddeath/registration_model');
        $this->load->model('iservices/admin/users_model');
        // $this->load->config('iservices/rtps_services');
        $this->load->config('spconfig');
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
          }else{
            $this->slug = "user";
          }

    }//End of __construct()

    private function my_transactions(){
      $user=$this->session->userdata();
      if(isset($user['role']) && !empty($user['role'])){
        redirect(base_url('iservices/admin/my-transactions'));
      }else{
        redirect(base_url('iservices/transactions'));
      }
    }

    public function verify($obj_id=null){
      // pre($obj_id);
      // die;
        if($obj_id){
          $filter = array("_id" =>new ObjectId($obj_id));
        
          $application = $this->registration_model->get_row($filter);
        //  pre($application->form_data->pfc_payment_status);
          if(property_exists($application,'form_data.pfc_payment_status')  && $application->form_data->pfc_payment_status == 'Y'){
            $this->my_transactions();
            return;
          }
          
          if(!empty($application->form_data->payment_params) && !empty($application->form_data->department_id)){
            $res=$this->check_pfc_payment_status($application->form_data->department_id);
            if($res){
              $this->application_submission($obj_id,$application->service_data->service_id);
            }else{
              
              $this->initiate($obj_id);
            }
            //check grn;
          }else{
            $this->initiate($obj_id);
          }
         
      }else{
          redirect(base_url('iservices/transactions'));
      }
    }
    
    public function application_submission($obj_id,$service_id){
        if($service_id === "PDDR"){
          $ref=modules::load('spservices/delayeddeath/registration');
          $ref->submit_to_backend($obj_id);
        }
    }
   
    public function initiate($obj_id=null) {

      // pre($obj_id);
      // die;
       
        if($obj_id){
          $dbRow = $this->registration_model->get_by_doc_id($obj_id);
          if (property_exists($dbRow, 'pfc_payment_status') && $dbRow->form_data->pfc_payment_status == 'Y') {
              $this->application_submission($obj_id,"PDDR");
              //$this->session->set_flashdata('message', 'Payment already and hence the status has updated');
             // $this->my_transactions();
          }

          if (!empty($dbRow->form_data->payment_params) && !empty($dbRow->form_data->department_id)) {                
              $res = $this->check_pfc_payment_status($dbRow->form_data->department_id);
              if ($res) {
                  $this->application_submission($obj_id,"PDDR");
              }
          }

          
            $filter = array("_id" =>new ObjectId($obj_id));
            $this->registration_model->update_where($filter,array('service_data.appl_status'=>'payment_initiated'));
            // pre($filter);
            // die;
            $data=array("pageTitle" => "Make Payment");
           
            $data["dbrow"] = $dbRow;
            $data['service_charge']=$this->config->item('service_charge');
            $data['objid']=$obj_id;
            $data['no_printing_page']=isset($data["dbrow"]->form_data->no_printing_page) ? $data["dbrow"]->form_data->no_printing_page :  '';
            $data['no_scanning_page']=isset($data["dbrow"]->form_data->no_scanning_page) ? $data["dbrow"]->form_data->no_scanning_page :  '';
            $data['appl_ref_no']=isset($data["dbrow"]->service_data->appl_ref_no) ? $data["dbrow"]->service_data->appl_ref_no :  '';
            $this->load->view('includes/frontend/header');
            $this->load->view('delayeddeath/kiosk_payment',$data);
            $this->load->view('includes/frontend/footer');
        }else{
            //redirect(base_url('iservices/transactions'));
            $this->my_transactions();
        }
        
    }//End of index()

    public function submit(){

        $this->form_validation->set_rules('objid', 'objid', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('appl_ref_no', 'appl_ref_no', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('no_printing_page', 'No printing page', 'trim|required|xss_clean|strip_tags|numeric');
        
        $this->form_validation->set_rules('no_scanning_page', 'No scanning page', 'trim|required|xss_clean|strip_tags|numeric');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $obj_id = $this->input->post('objid');
            if($obj_id){
                $this->initiate( $obj_id );
            }else{
                redirect(base_url('iservices/transactions'));
            }
           
        } else {
            // pre($this->input->post());
            // die;
            // $this->load->view('basundhara/payment',$data);
            $this->pfcpayment();
        }              

    }


    private function pfcpayment(){
  
        if($this->slug === "CSC"){
          $account=$this->config->item('csc_account');
          $mobile=$this->session->userdata('user')->mobileno;
        }else{
          $user=$this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
          $account=$user->account1;
          $mobile=$user->mobile;
        }
        
        $uniqid=uniqid();
        $DEPARTMENT_ID=$uniqid.time();
        $data=array();
        $data['service_charge']=$this->config->item('service_charge');
        $data['scanning_charge_per_page']=$this->config->item('scanning_charge');
        $data['printing_charge_per_page']=$this->config->item('printing_charge');
        $data['appl_ref_no']=$this->input->post('appl_ref_no');
        $data['no_printing_page']=$this->input->post('no_printing_page');
        $data['no_scanning_page']=$this->input->post('no_scanning_page');
        $data['pfc_payment']=true;
        $total_amount=$data['service_charge'];

        if(!empty($data['no_printing_page']) &&( intval($data['no_printing_page']) < 0 || !is_numeric($data['no_printing_page'])) ){
          return $this->output
              ->set_content_type('application/json')
              ->set_status_header(200)
              ->set_output(json_encode(array("status"=>false,"message"=>"Number of page can not be a negative value")));
        }
        if(!empty($data['no_scanning_page']) && intval($data['no_scanning_page']) < 0 || !is_numeric($data['no_printing_page'])){
          return $this->output
              ->set_content_type('application/json')
              ->set_status_header(200)
              ->set_output(json_encode(array("status"=>false,"message"=>"Number of page can not be a negative value")));
        }

        if($this->slug === "PFC"){
          if($data['no_printing_page'] > 0 ){
            // console.log("printing ::"+no_printing_page)
              $total_amount +=intval($data['no_printing_page'])*floatval($data['printing_charge_per_page']);
          }
          if($data['no_scanning_page']  > 0 ){
              // console.log("printing ::"+no_scanning_page)
              $total_amount +=intval($data['no_scanning_page'])*floatval($data['scanning_charge_per_page']);
          }
        }
        if($this->input->post('objid')){
         $obj_id= $this->input->post('objid');
         $filter = array("_id" =>new ObjectId($obj_id));
         $transaction_data = $this->registration_model->get_row($filter);
       //  pre($transaction_data);
          if(empty($transaction_data)){
            $this->my_transactions();
          }
          $data['appl_ref_no']=$transaction_data->service_data->appl_ref_no;
    
         
          $dept_code='ARI';
          $office_code="ARI000";
          
        $data['department_data']=array(
          "DEPT_CODE"=>$dept_code,//$user->dept_code,
          "OFFICE_CODE"=>$office_code,//$user->office_code,
          "REC_FIN_YEAR"=>$this->config->item('egras_fin_year'),//dynamic
          "HOA1"=>"",
          "FROM_DATE"=>"01/04/2022",
          "TO_DATE"=>"31/03/2099",
          "PERIOD"=>"O",// O for ontimee payment
          "CHALLAN_AMOUNT"=>"0",
          "DEPARTMENT_ID"=>$DEPARTMENT_ID,
          "MOBILE_NO"=>$mobile,//pfc no
          // "SUB_SYSTEM"=>"REV-SP|".base_url('iservices/admin/get/payment-response'),
          "SUB_SYSTEM"=>"ARTPS-SP|".base_url('spservices/delayeddeath/payment_response/response'),
          "PARTY_NAME"=>isset($transaction_data->form_data->applicant_name) ? $transaction_data->form_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
          "PIN_NO"=>isset($transaction_data->form_data->pin_code) ? $transaction_data->form_data->pin_code : "781005",
          "ADDRESS1"=>isset($transaction_data->form_data->village_town) ? $transaction_data->form_data->village_town : "NIC",
          "ADDRESS2"=>isset($transaction_data->form_data->village_town) ? $transaction_data->form_data->village_town : "",
          "ADDRESS3"=>isset($transaction_data->form_data->village_town) ? $transaction_data->form_data->village_town : "781005",
          "MULTITRANSFER"=>"Y",
          "NON_TREASURY_PAYMENT_TYPE"=>"02",
          "TOTAL_NON_TREASURY_AMOUNT"=>$total_amount,
          "AC1_AMOUNT"=>$total_amount,
          "ACCOUNT1"=>"PFC23362", //$user->account1,
        );
          $res=$this->update_pfc_payment_amount($data);
         
          if($res){
            $this->load->view('iservices/basundhara/payment',$data);
          }else{
            $this->my_transactions();
          }
    
        }else{
          $this->my_transactions();
          return;
        }
       
    
    }
      public function update_pfc_payment_amount($data){
        
          $payment_params=$data['department_data'];
          $appl_ref_no=$data['appl_ref_no'];
          $data_to_update=array('form_data.department_id'=>$payment_params['DEPARTMENT_ID'],'form_data.payment_params'=>$payment_params);
          if(isset($data['pfc_payment'])){
            $data_to_update['form_data.service_charge']=$data['service_charge'];
            $data_to_update['form_data.scanning_charge_per_page']=$data['scanning_charge_per_page'];
            $data_to_update['form_data.printing_charge_per_page']=$data['printing_charge_per_page'];
            $data_to_update['form_data.no_printing_page']=$data['no_printing_page'];
            $data_to_update['form_data.no_scanning_page']=$data['no_scanning_page'];
          }
        
          $result=$this->registration_model->update_payment_status($appl_ref_no,$data_to_update);
          
          if($result->getMatchedCount()){
            //pre($data);
            $this->load->model('iservices/admin/pfc_payment_history_model');
            $data_to_update_history['rtps_trans_id']=$appl_ref_no;
            $data_to_update_history['form_data']['department_id']=$payment_params['DEPARTMENT_ID'];
            $data_to_update_history['form_data']['payment_params']=$data['department_data'];
            $data_to_update_history['form_data']['service_charge']=$data['service_charge'];
            $data_to_update_history['form_data']['scanning_charge_per_page']=$data['scanning_charge_per_page'];
            $data_to_update_history['form_data']['printing_charge_per_page']=$data['printing_charge_per_page'];
            $data_to_update_history['form_data']['no_printing_page']=$data['no_printing_page'];
            $data_to_update_history['form_data']['no_scanning_page']=$data['no_scanning_page'];
            

            $data_to_update_history['createdDtm']=new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
            $this->pfc_payment_history_model->insert($data_to_update_history);
            //die("jhdhd");
           return true;
          }else {
           return false;
          }
      }

      public function update_query_payment_amount($data){
        $payment_params=$data['department_data'];
        $rtps_trans_id=$data['rtps_trans_id'];
        $data_to_update=array('query_department_id'=>$payment_params['DEPARTMENT_ID'],
                              'query_payment_params'=>$payment_params);
    
        $result=$this->registered_deed_model->update_payment_status($rtps_trans_id,$data_to_update);
        // pre($result->getMatchedCount());
           if($result->getMatchedCount()){
             $this->load->model('iservices/admin/pfc_payment_history_model');
             $data_to_update['rtps_trans_id']=$rtps_trans_id;
             $data_to_update['createdDtm']=new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
             $this->pfc_payment_history_model->insert($data_to_update);
            return true;
           }else {
            return false;
           }
    }

    private function check_pfc_payment_status($DEPARTMENT_ID=null){
    
   
      if($DEPARTMENT_ID){
        $min=$this->applications_model->checkPFCPaymentIntitateTimeNew($DEPARTMENT_ID);
        // pre( $min);
        if( $min !== 'N' && $min < 6){
          $this->session->set_flashdata('errmessage', 'Please verify payment status after 5 minutes');
          $this->my_transactions();
          return;
        }
        $ref=modules::load('spservices/delayeddeath/Payment_response');
        $grndata=$ref->checkgrn($DEPARTMENT_ID,false,true);

        //pre($grndata);

        if(!empty($grndata)){
            if($grndata['STATUS'] === 'Y'){
              return true;
            }
            $ar=array('N','A');
            if(!empty($grndata['GRN']) && !in_array($grndata['STATUS'] , $ar) ){
              $this->my_transactions();
              return;
            }
        }
        
      
      }
      return false;
  
    }

    private function check_payment_status($DEPARTMENT_ID=null){
    
   
      if($DEPARTMENT_ID){
        $min=$this->applications_model->checkPaymentIntitateTime($DEPARTMENT_ID);
        // pre( $min);
        if( $min !== 'N' && $min < 6){
          $this->session->set_flashdata('errmessage', 'Please verify payment status after 5 minutes');
          $this->my_transactions();
          return;
        }
        $ref=modules::load('spservices/Query_payment_response');
        $grndata=$ref->checkgrn($DEPARTMENT_ID,false,true);
        if(!empty($grndata)){
            if($grndata['STATUS'] === 'Y'){
              $this->my_transactions();
              return;
            }
            $ar=array('N','A');
            if(!empty($grndata['GRN']) && !in_array($grndata['STATUS'] , $ar) ){
              $this->my_transactions();
              return;
            }
        }
        
      
      }
  
    }
    

      public function querypayment($obj_id){
      
        if($obj_id){
          $transaction_data = $this->registered_deed_model->get_by_doc_id($obj_id);
          // pre($transaction_data);
          if(property_exists($transaction_data,"query_department_id") && property_exists($transaction_data,"query_payment_params") ){
            $this->check_payment_status($transaction_data->query_department_id);
          }

          $query_data=json_decode($transaction_data->query->wsResponse);

          $service_info=$this->applications_model->get_service_info($transaction_data->service_id);
         
          if(empty( $service_info)){
            show_error('No record available for the service.', 403, 'Service Not Found');
            exit(404);
          }
          $office_info=$this->applications_model->get_office_code($transaction_data->sro_code);
          // pre( $office_info);
          if(empty( $office_info->office_code)){
            show_error('No office mapping found.', 403, 'No office mapping found');
            exit(404);
          }
        
          if($query_data){
              $user_fee= $query_data->user_fee;
              $artps_fee= $query_data->artps_fee;

              $uniqid=uniqid();
              $DEPARTMENT_ID=$uniqid.time();
              $data=array();
              $data['rtps_trans_id']=$transaction_data->rtps_trans_id;
    
         
              $dept_code=$service_info->DEPT_CODE;
              $office_code=$office_info->office_code;
              $treasury_code=$office_info->treasury_code;
              $CHALLAN_AMOUNT=0;
             
              if(!empty($user_fee)){
                $CHALLAN_AMOUNT  +=  floatval($user_fee);
              }
             
              if(!empty($artps_fee)){
                $CHALLAN_AMOUNT  +=  floatval($artps_fee);
              }
               if(!empty($service_info->AMOUNT3)){
                $CHALLAN_AMOUNT  +=  floatval($service_info->AMOUNT3);
              }
            $data['department_data']=array(
              "DEPT_CODE"=>$dept_code,//$user->dept_code,
              "PAYMENT_TYPE" => isset($service_info->PAYMENT_TYPE) ? $service_info->PAYMENT_TYPE : "",
              "TREASURY_CODE" => isset($treasury_code) ? $treasury_code : "",
              "OFFICE_CODE"=>$office_code,
              "REC_FIN_YEAR"=>$this->config->item('egras_fin_year'),//dynamic
              "PERIOD"=>"O",// O for ontimee payment
              "FROM_DATE"=>"01/04/2022",
              "TO_DATE"=>"31/03/2099",
              "MAJOR_HEAD" =>  isset($service_info->MAJOR_HEAD) ? $service_info->MAJOR_HEAD : "",
              "AMOUNT1" => isset($user_fee) ? $user_fee : "",
              "HOA1" => isset($service_info->HOA1) ? $service_info->HOA1 : "",
              "AMOUNT2" => isset($artps_fee) ? $artps_fee : "",
              "HOA2" =>isset($service_info->HOA2) ? $service_info->HOA2 : "",
              "AMOUNT3" =>isset($service_info->AMOUNT3) ? $service_info->AMOUNT3 : "",
              "HOA3" => isset($service_info->HOA3) ? $service_info->HOA3 : "",
              "AMOUNT4" =>isset($service_info->AMOUNT4) ? $service_info->AMOUNT4 : "",
              "HOA4" => isset($service_info->HOA4) ? $service_info->HOA4 : "",
              "AMOUNT5" => isset($service_info->AMOUNT5) ? $service_info->AMOUNT5 : "",
              "HOA5" => isset($service_info->HOA5) ? $service_info->HOA5 : "",
              "AMOUNT6" => isset($service_info->AMOUNT6) ? $service_info->AMOUNT6 : "",
              "HOA6" => isset($service_info->HOA6) ? $service_info->HOA6 : "",
              "AMOUNT7" => isset($service_info->AMOUNT7) ? $service_info->AMOUNT7 : "",
              "HOA7" => isset($service_info->HOA7) ? $service_info->HOA7 : "",
              "AMOUNT8" => isset($service_info->AMOUNT8) ? $service_info->AMOUNT8 : "",
              "HOA8" => isset($service_info->HOA8) ? $service_info->HOA8 : "",
              "AMOUNT9" => isset($service_info->AMOUNT9) ? $service_info->AMOUNT9 : "",
              "HOA9" => isset($service_info->HOA9) ? $service_info->HOA9 : "",
              "CHALLAN_AMOUNT" => $CHALLAN_AMOUNT,

              "TAX_ID" => "",
              "PAN_NO" => "",
              "PARTY_NAME"=>isset($transaction_data->applicant_name) ? $transaction_data->applicant_name : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
              "PIN_NO"=>isset($transaction_data->pin_code) ? $transaction_data->pin_code : "781005",
              "ADDRESS1"=>isset($transaction_data->address1) ? $transaction_data->address1 : "NIC",
              "ADDRESS2"=>isset($transaction_data->address2) ? $transaction_data->address2 : "",
              "ADDRESS3"=>isset($transaction_data->address3) ? $transaction_data->address3 : "781005",

              "MOBILE_NO"=>$transaction_data->mobile,
              "DEPARTMENT_ID"=>$DEPARTMENT_ID,
              // "SUB_SYSTEM"=>"REV-SP|".base_url('iservices/admin/get/payment-response'),
              "SUB_SYSTEM"=>"ARTPS-SP|".base_url('spservices/query_payment_response/response'),
              
            );
              // pre($data);
              $res=$this->update_query_payment_amount($data);
             
              if($res){
                $this->load->view('iservices/basundhara/payment',$data);
              }else{
                $this->my_transactions();
              }


          }
          // pre(  $query_data->artps_fee);
      }else{
          redirect(base_url('iservices/transactions'));
      }
      }


    

}//End of Castecertificate
