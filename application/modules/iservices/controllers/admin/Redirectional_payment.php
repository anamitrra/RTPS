<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Redirectional_payment extends Rtps {
 /**
  * __construct
  *
  * @return void
  */
  function __construct() {
    parent::__construct();
    $this->load->model('admin/users_model');
    $this->load->model('redirectional_model');
    $this->load->model('portals_model');
    $this->config->load('rtps_services');
    if (!empty($this->session->userdata('role'))) {
      $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
  } else {
      $this->slug = "user";
  }
  if(  $this->slug === "CSC" || $this->slug === "user"){
    exit("Service not available");
    return;
  }
  }

  public function index(){
     
      $this->load->view('includes/header');
     
      $this->load->view('admin/redirectional/my_application');
      $this->load->view('includes/footer');
  }

  public function new(){
     
    $data['district_list']=$this->get_district();
    $data['services']=$this->get_services();
    $this->load->view('includes/header');
   
    $this->load->view('admin/redirectional/index',$data);
    $this->load->view('includes/footer');
}

  function generateRandomString($length = 7)
  {
    $number = '';
    for ($i = 0; $i < $length; $i++) {
      $number .= rand(0, 9);
    }
    return (int)$number;
  }

  public function generate_id()
  {
    $date = date('ydm');
    $str = "RED" . $date . "S" . $this->generateRandomString(7);
    return $str;
  }

  public function validateapp($app_ref_no){
    
    if($app_ref_no){
     $res= $this->redirectional_model->is_exist_app_no($app_ref_no);
     if($res){
      $this->form_validation->set_message('app_ref_no','Payment has been collected for this application');
      return false;
     }
    }
    return true;

  }
  
  public function initiate(){

    $this->form_validation->set_rules('service_id', 'Service', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('applicant_name', 'Applicant Name', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('mobile', 'User mobile', 'trim|required|numeric|xss_clean|exact_length[10]');                
    $this->form_validation->set_rules('address1', 'Address 1', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('address2', 'Address 2', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('pin_code', 'Pincode', 'trim|required|xss_clean|strip_tags|exact_length[6]');
    $this->form_validation->set_rules('no_printing_page', 'No of printing page', 'trim|required|xss_clean|strip_tags|numeric');
    $this->form_validation->set_rules('no_scanning_page', 'No of scanning page', 'trim|required|xss_clean|strip_tags|numeric');
    $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('office', 'Office', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('app_ref_no', 'Application ref no', 'trim|required|xss_clean|strip_tags');
    

    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    if ($this->form_validation->run() == FALSE) {
        $this->new();
        return;
    }

    if(!$this->validateapp($this->input->post("app_ref_no"))){
      $this->new();
      return;
    }
    $sessionUser=$this->session->userdata();
    $rtps_trans_id = $this->generate_id();
    A1:
    if ($this->redirectional_model->is_exist_transaction_no($rtps_trans_id)) {
      $rtps_trans_id = $this->generate_id();
      goto A1;
    }

  

    $data_to_save=$this->input->post();
    $data_to_save['rtps_trans_id']=$rtps_trans_id;


    if($this->slug === 'PFC') {
        $applied_by = new ObjectId($this->session->userdata('userId')->{'$id'});
      } elseif($this->slug === 'CSC') {
        $applied_by = $sessionUser['userId'];
      } else{
        redirect(base_url('iservices/admin/my-transactions'));
        return;
      }

  

    $data_to_save = array(
      "service_data"=>array(
        "service_id"=>$this->input->post("service_id"),
        "service_name"=>$this->input->post("service_name"),
        "rtps_trans_id"=>$rtps_trans_id,
        "appl_ref_no"=>$this->input->post("app_ref_no"),
        "applied_by"=> $applied_by,
        "appl_status"=>"initiated"

      ),
      "form_data"=>array(
        "mobile_number"=>$this->input->post("mobile"),
        "applicant_name"=>$this->input->post("applicant_name"),
        "address1"=>$this->input->post("address1"),
        "address2"=>$this->input->post("address2"),
        "pin"=>$this->input->post("pin_code"),
        "district"=>$this->input->post("district"),
        "office"=>$this->input->post("office"),
      ),
      'no_printing_page'=>$this->input->post("no_printing_page"),
      'no_scanning_page'=>$this->input->post("no_scanning_page")
    );

    $res = $this->mongo_db->insert('redirectional_payments',$data_to_save);
   
    if($res ){
        $this->payment($res['service_data']['rtps_trans_id']);
    }
  }
  

  private function get_services(){
      $this->mongo_db->switch_db('portal');
      $filter=array(
          'service_type'=>'NA',
          'ext_service_type'=>'EODB'
      );
      $collection = 'services';
      $this->mongo_db->select(array('service_id','service_name.en'));
      // $this->mongo_db->limit(5);
      $data=$this->mongo_db->get_data_like($filter, $collection);
      $this->mongo_db->switch_db('iservices');
      return $data;
  
  }

  private function get_district(){
    $this->mongo_db->switch_db('mis');
    $filter=array(
      'office_name'=>'META_DIST_LIST'
    );
    $collection = 'office_district_mappings';
    $data=$this->mongo_db->get_data_like($filter, $collection);
    $data= (array) $data;
    $this->mongo_db->switch_db('iservices');
    return $data[0]->district;

}
   
function get_office() {
  $district = $this->input->post("district");

  $this->mongo_db->switch_db('mis');
  $filter=array(
    'district'=> $district,
    'office_name'=>['$ne'=>'META_DIST_LIST']
  );
  $collection = 'office_district_mappings';
  $office_list=$this->mongo_db->get_data_like($filter, $collection);

  $this->mongo_db->switch_db('iservices');

   ?>   
                  
  <select name="office" id="office" class="form-control">
      <option value="">Select a circle </option>
      <?php if($office_list) { 
          foreach($office_list as $off) {
              echo '<option value="'.$off->office_name.'">'.$off->office_name.'</option>';                   
          }//End of foreach()
      }//End of if ?>
  </select><?php
}//End of get_circles()

  private function clean($string) {
    return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
 }
 private function my_transactions(){
  $user=$this->session->userdata();
  if(isset($user['role']) && !empty($user['role'])){
    redirect(base_url('iservices/admin/my-transactions'));
  }else{
    redirect(base_url('iservices/transactions'));
  }
}
 private function check_payment_status($DEPARTMENT_ID=null){

  if($DEPARTMENT_ID){
    $min=$this->redirectional_model->checkPaymentIntitateTime($DEPARTMENT_ID);
  
    if( $min !== 'N' && $min < 6){
      $this->session->set_flashdata('errmessage', 'Please verify payment status after 5 minutes');
      redirect(base_url('iservices/admin/my-transactions'));
      return;
    }
    $grndata=$this->checkgrn($DEPARTMENT_ID);
    if(!empty($grndata)){
        if($grndata['STATUS'] === 'Y'){
          redirect(base_url('iservices/admin/my-transactions'));
          return;
        }
        $ar=array('N','A');
        if(!empty($grndata['GRN']) && !in_array($grndata['STATUS'] , $ar) ){
          redirect(base_url('iservices/admin/my-transactions'));
          return;
        }
    }
 
  }

}
  public function payment($rtps_trans_id){
 
    $financial_year=get_financial_year();
  
    if(empty($rtps_trans_id)){
      redirect(base_url('iservices/admin/my-transactions'));
    }

    $dbFilter = array('service_data.rtps_trans_id' => $rtps_trans_id);
    $sessionUser=$this->session->userdata();

      
    $transaction_data=$this->redirectional_model->get_row($dbFilter); 
    if(empty($transaction_data)){
        redirect(base_url('iservices/admin/my-transactions'));
    }
    if(isset($transaction_data->pfc_payment_status ) && $transaction_data->pfc_payment_status == "Y"){
        redirect(base_url('iservices/admin/my-transactions'));
    }

    if(property_exists($transaction_data,"department_id") && property_exists($transaction_data,"payment_params") ){
      $this->check_payment_status($transaction_data->department_id);
    }
    $dept_code='ARI';
    $office_code='ARI000';
   
    

    if($this->session->userdata('role') === "csc"){
      $account=$this->config->item('csc_account');
    //   $mobile=$this->session->userdata('user')->mobileno;
    }else{
      $user=$this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
      $account=$user->account1;
    //   $mobile=$user->mobile;
    }
   
    $uniqid=uniqid();
    $DEPARTMENT_ID=$uniqid.time();
   
    $data['service_charge']=$this->config->item('service_charge');
    $data['scanning_charge_per_page']=$this->config->item('scanning_charge');
    $data['printing_charge_per_page']=$this->config->item('printing_charge');
  
    $total_amount=$data['service_charge'];
    if($transaction_data->no_printing_page > 0 ){
        $total_amount +=intval($transaction_data->no_printing_page)*floatval($data['printing_charge_per_page']);
    }
  
    if($transaction_data->no_scanning_page  > 0 ){
        $total_amount +=intval($transaction_data->no_scanning_page)*floatval($data['scanning_charge_per_page']);
    }
  

    $data['rtps_trans_id']=$rtps_trans_id;
    $data['department_data']=array(
      "DEPT_CODE"=>$dept_code,//$user->dept_code,
      "OFFICE_CODE"=>$office_code,//$user->office_code,
      "REC_FIN_YEAR"=> $financial_year['financial_year'],//dynamic
      "HOA1"=>"",
      "FROM_DATE"=> $financial_year['from_date'],
      "TO_DATE"=>"31/03/2099",
      "PERIOD"=>"O",// O for ontimee payment
      "CHALLAN_AMOUNT"=>0,
      "DEPARTMENT_ID"=>$DEPARTMENT_ID,
      "MOBILE_NO"=>$transaction_data->form_data->mobile_number,//pfc no
      "SUB_SYSTEM"=>"ARTPS-SP|".base_url('iservices/admin/redirectional_payment_response/response'),
      "PARTY_NAME"=>isset($transaction_data->applicant_name) ? $this->clean($transaction_data->applicant_name) : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
      "PIN_NO"=>isset($transaction_data->pin_code) ? $transaction_data->pin_code : "781005",
      "ADDRESS1"=>isset($transaction_data->address1) ? $transaction_data->address1 : "NIC",
      "ADDRESS2"=>isset($transaction_data->address2) ? $transaction_data->address2 : "",
      "ADDRESS3"=>isset($transaction_data->address3) ? $transaction_data->address3 : "781005",
      "MULTITRANSFER"=>"Y",
      "NON_TREASURY_PAYMENT_TYPE"=>"02",
      "TOTAL_NON_TREASURY_AMOUNT"=>$total_amount,
      "AC1_AMOUNT"=>$total_amount,
      "ACCOUNT1"=>$account,
    );
       
        $res=$this->update_pfc_payment_amount($data);
            
        if($res){
            $this->load->view('basundhara/payment',$data);
        }else{
            $this->my_transactions();
        }
  
  }

  
    public function update_pfc_payment_amount($data){
  
      $payment_params=$data['department_data'];
      $rtps_trans_id=$data['rtps_trans_id'];
      $data_to_update=array('department_id'=>$payment_params['DEPARTMENT_ID'],
                            'payment_params'=>$payment_params);
        $data_to_update['service_charge']=$data['service_charge'];
        $data_to_update['scanning_charge_per_page']=$data['scanning_charge_per_page'];
        $data_to_update['printing_charge_per_page']=$data['printing_charge_per_page'];

      $result=$this->redirectional_model->update_row(array("service_data.rtps_trans_id"=>$rtps_trans_id),$data_to_update);
      
        if($result->getMatchedCount()){
          $this->load->model('admin/pfc_payment_history_model');
          $data_to_update['rtps_trans_id']=$rtps_trans_id;
          $data_to_update['createdDtm']=new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
          $this->pfc_payment_history_model->insert($data_to_update);
          return true;
        }else {
          return false;
        }
  }

  public function checkgrn($DEPARTMENT_ID = null)
  {
    $transaction_data = $this->redirectional_model->get_row(array('department_id' => $DEPARTMENT_ID));
    if ($DEPARTMENT_ID) {
      $OFFICE_CODE = $transaction_data->payment_params->OFFICE_CODE;
      $am1 = isset($transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
      $am2 = isset($transaction_data->payment_params->CHALLAN_AMOUNT) ? $transaction_data->payment_params->CHALLAN_AMOUNT : 0;
      $AMOUNT = $am1 + $am2;
      $string_field = "DEPARTMENT_ID=" . $DEPARTMENT_ID . "&OFFICE_CODE=" . $OFFICE_CODE . "&AMOUNT=" . $AMOUNT;
      $url = $this->config->item('egras_grn_cin_url');
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 3);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $string_field);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      // curl_setopt ($ch, CURLOPT_CAINFO, dirname(FILE)."/123.assam.gov.in.crt");
      curl_setopt($ch, CURLOPT_FAILONERROR, FALSE);
      curl_setopt($ch, CURLOPT_NOBODY, false);
      $result = curl_exec($ch);
      curl_close($ch);
      $res = explode("$", $result); 
    
      if ($res) {
        $STATUS = isset($res[16]) ? $res[16] : '';
        $GRN = isset($res[4]) ? $res[4] : '';
        $this->redirectional_model->update_row(
          array('department_id' => $DEPARTMENT_ID),
          array(
            "pfc_payment_response.GRN" => $GRN,
            "pfc_payment_response.AMOUNT" => isset($res[6]) ? $res[6] : '',
            "pfc_payment_response.PARTYNAME" => isset($res[18]) ? $res[18] : '',
            "pfc_payment_response.TAXID" => isset($res[20]) ? $res[20] : '',
            "pfc_payment_response.DEPARTMENT_ID" => isset($res[2]) ? $res[2] : '',
            "pfc_payment_response.BANKNAME" => isset($res[22]) ? $res[22] : '',
            "pfc_payment_response.BANKCODE" => isset($res[8]) ? $res[8] : '',
            "pfc_payment_response.ENTRY_DATE" => isset($res[24]) ? $res[24] : '',
            "pfc_payment_response.STATUS" => $STATUS,
            "pfc_payment_response.PRN" => isset($res[12]) ? $res[12] : '',
            "pfc_payment_response.TRANSCOMPLETIONDATETIME" => isset($res[14]) ? $res[14] : '',
            "pfc_payment_response.BANKCIN" => isset($res[10]) ? $res[10] : '',
            'pfc_payment_status' => $STATUS,
            'service_data.appl_status' => $STATUS
          )
        );
        return array(
          'GRN'=>$GRN,
          'STATUS'=>$STATUS
        );
      }
    }else{
      return false;
    }
   
  }

  public function get_records()
  {
      $this->load->model('applications_model');
    
      $sessionUser=$this->session->userdata();
      if($this->slug === 'PFC') {
        $apply_by= new ObjectId($this->session->userdata('userId')->{'$id'});
      } elseif($this->slug === 'CSC') {
        $apply_by = $sessionUser['userId'];
      } 

      $columns = array(
          '_id'
      );
      $limit = $this->input->post("length");
      $start = $this->input->post("start");
      $order = $columns[$this->input->post("order")[0]["column"]];
      $dir = $this->input->post("order")[0]["dir"];
      $totalData=0;
      $totalData = $this->redirectional_model->total_app_rows($apply_by);
      $totalFiltered = $totalData;
      if (empty($this->input->post("search")["value"])) {
          $records = $this->redirectional_model->applications_filter($limit, $start, $apply_by , $columns, $dir,);
          
      } else {
          $search = trim($this->input->post("search")["value"]);
          $records = $this->redirectional_model->application_search_rows($limit, $start, $search, $columns, $dir,$apply_by );
          // $totalFiltered = $this->official_details_model->official_details_tot_search_rows($search);
      }
      
      // pre($records );
      $data = array();
      if (!empty($records)) {
          $sl = 1;
          foreach ($records as $rows) {
              $rows=(array ) $rows;
      
              $nestedData["sl_no"] = $sl;
              $obj_id=$rows['_id']->{'$id'};
              $nestedData["service_name"] =  $rows['service_data']->service_name;
              $nestedData["applicant_name"] =  $rows['form_data']->applicant_name;
              $nestedData["mobile"] =  $rows['form_data']->mobile_number;
              $nestedData["rtps_trans_id"] =  $rows['service_data']->rtps_trans_id;
             
              $nestedData["status"] =!empty( $rows['pfc_payment_status']) ?  $rows['pfc_payment_status'] : 'P';
              if($nestedData["status"] === "Y"){
                $btns = '<a target="_blank" href="' . base_url("iservices/admin/redirectional_payment_response/ack?app_ref_no=". $rows['department_id']) .'" data-toggle="tooltip" data-placement="top" title="Acknowledgement" class="btn btn-sm btn-success mb-1" >Acknowledgement</a> ';
              }else{
                $btns = '<a target="_blank" href="' . base_url("iservices/admin/redirectional_payment/payment/". $rows['service_data']->rtps_trans_id) .'" data-toggle="tooltip" data-placement="top" title="Verify Payment" class="btn btn-sm btn-warning mb-1" >Verify Payment</a> ';
              }
              
            
             $nestedData['action']=$btns;
            
              //$nestedData["action"] = $btns;
              $data[] = $nestedData;
              $sl++;
          }
      }
      $json_data = array(
          "draw" => intval($this->input->post("draw")),
          "recordsTotal" => intval($totalData),
          "recordsFiltered" => intval($totalFiltered),
          "data" => $data,
      );
      echo json_encode($json_data);
  }

}
