<?php
require_once APPPATH."/third_party/libs/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Feedback extends Frontend
{
      /**
   * __construct
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->library('AES');
    $this->encryption_key="qwertyuiips@#$!123456";
    $this->load->model("feedback_model");
  }
  public function submission(){
    $appl_ref_no = $this->input->get('id',true);
    $aes = new AES($appl_ref_no, $this->encryption_key);
    $id = $aes->decrypt();
    // $id = $appl_ref_no;
    
    $this->load->model('applications_model');
   
    $data=$this->feedback_model->check_feedback_by_appl_ref_no($id,'submission');
    if(isset($data)){
        show_error('Feedback is already provided for this application', '403', 'Invalid Feedback');
    }
    $data=$this->applications_model->get_by_appl_ref_no($id);
    if(!isset($data)){
      show_error('No record found for the application id', '403', 'Invalid Application ID');
    }
    //$this->load->view('includes/frontend/header',array("pageTitle" => "Feedback"));
    $this->load->view('feedback/submission',array('application_data'=>$data));
    //$this->load->view('includes/frontend/footer');
  }
  public function index()
  {
    $appl_ref_no = $this->input->get('id',true);
    $aes = new AES($appl_ref_no, $this->encryption_key);
    $id = $aes->decrypt();
    
    $this->load->model('applications_model');
   
    $data=$this->feedback_model->check_feedback_by_appl_ref_no($id,'delivered');
    if(isset($data)){
        show_error('Feedback is already provided for this application', '403', 'Invalid Feedback');
    }
    $data=$this->applications_model->get_by_appl_ref_no($id);
    if(!isset($data)){
      show_error('No record found for the application id', '403', 'Invalid Application ID');
    }
    //$this->load->view('includes/frontend/header',array("pageTitle" => "Feedback"));
    $this->load->view('feedback/index',array('application_data'=>$data));
    //$this->load->view('includes/frontend/footer');
  }
  public function save()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('stars', 'Rating', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('feedback_text', 'Feedback', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('dept_id', 'Department', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('appl_ref_no', 'Appl Ref No', 'trim|required|xss_clean|strip_tags');
    if ($this->form_validation->run() == FALSE) {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => false,
                'validation_errors' => validation_errors()
            )));
    }
    $stars = $this->input->post('stars', true);
    if($stars < 1){
      return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode(array(
          'status' => false,
          'validation_errors' => '<p>Please give a star</p>'
      )));
    }
    $dept_id = $this->input->post('dept_id', true);
    $service_id = $this->input->post('service_id', true);
    $department_name = $this->input->post('department_name', true);
    $submission_location = $this->input->post('submission_location', true);
    $feedback_text = $this->input->post('feedback_text',true);
    $appl_ref_no = $this->input->post('appl_ref_no',true);
    $feedback_on = $this->input->post('feedback_on',true);
    $feedbackInputs = array(
        'stars' => $stars,
        'dept_id' => $dept_id,
        'service_id' => $service_id,
        'department_name' => $department_name,
        'submission_location' => $submission_location,
        'feedback_text' =>$feedback_text ,
        'service_name' => $this->input->post('service_name',true),
        'appl_ref_no' => $appl_ref_no,
        'feedback_on' => $feedback_on,
        'created_at' => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
    );
    $this->load->model("feedback_model");
    $id=$this->feedback_model->insert($feedbackInputs);
    
    return $this->output
    ->set_content_type('application/json')
    ->set_status_header(200)
    ->set_output(json_encode(array(
        'status' => true,
    )));
  }
  public function generate_url(){
    $appl_ref_no = $this->input->get('id',true);
    if($appl_ref_no){
      $aes = new AES($appl_ref_no, $this->encryption_key);
       $enc = $aes->encrypt();
      //  pre(strlen($enc));
       echo base_url("mis/feedback?id=".urlencode($enc));
    }
  }
  public function list(){
    $this->isLoggedIn();
    $this->load->view('includes/header', array("pageTitle" => "Mis | Feedback"));
    $average=$this->feedback_model->get_average();
    foreach($average as $item){
      if($item->_id === "delivered")
        $data['avg_delivered']=round($item->avg_rating,2);
      if($item->_id === "submission")
        $data['avg_submission']=round($item->avg_rating,2);
     
    }
    $data['overal_avg']=($data['avg_delivered']+$data['avg_submission'])/2;
    // pre($data);
    $this->load->model('location_model');
    $this->load->model('services_model');
    $locationList = $this->location_model->get_where([]);
    $serviceList = $this->services_model->all([]);
    $data['locationList']= $locationList;
    $data['serviceList']= $serviceList;
     

    $this->load->view('feedback/list',$data);
    $this->load->view('includes/footer');
  }
   /**
   * get_records
   *
   * @return void
   */
  public function get_records()
  {
    
    $this->isLoggedIn();
    $columns = array(
      0 => "appl_ref_no",
      1 => "service_name",
      2 => "department_name",
      3 => "submission_location",
      4 => "feedback_on",
      5 => "stars",
      // 6 => "created_at",
    );

    $startDate = $this->input->post('startDate');
    $endDate = $this->input->post('endDate');
    $submission_location = $this->input->post('submission_location');
    $feedback_on = $this->input->post('feedback_on');
    $service_id_filter = $this->input->post('service_id');


    $limit = $this->input->post("length");
    $start = $this->input->post("start");
    $order = $columns[(int) $this->input->post("order")[0]["column"]];
    $dir = $this->input->post("order")[0]["dir"];
    $orderArray = [$order => $dir === 'asc' ? 1 : -1];
    $totalData = $this->feedback_model->total_rows();
    $totalFiltered = $totalData;
    $fillter=array();
   
    $projectionArray = [
      'appl_ref_no' => 1,
      'service_name' => 1,
      'department_name' => 1,
      'submission_location' => 1,
      'feedback_text' => 1,
      'stars' => 1,
      'created_at' => 1,
      'feedback_on' => 1,
    ];
    $matchArray = [];
    // $matchArray[]['service_id'] = "1104" ;
        if($submission_location){
            $matchArray[]['submission_location'] = $submission_location;
        }
        
        if( $service_id_filter){
            $matchArray[]['service_id'] =  $service_id_filter ;
        }
      
        if($feedback_on){
                $matchArray[]['feedback_on'] = $feedback_on;
        }
        if($startDate && $endDate){
            $matchArray[]["created_at"] = [
                "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($startDate) * 1000),
                "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($endDate)) * 1000)
            ];
        }
   
    if (empty($this->input->post("search")["value"])) {
      // pre($matchArray);
      $records = $this->feedback_model->applications_search_rows($limit, $start,'', $order, $dir,$matchArray);
      // $records = $this->feedback_model->get_filtered_feedback($projectionArray,$matchArray,[],$start,$limit,$orderArray);
        // pre($records);
    } else {
      $search = trim($this->input->post("search")["value"]);
      $searchArray = [
        'appl_ref_no' => $search,
        'service_name' => $search,
        'department_name' => $search,
        'submission_location' => $search,
        'feedback_on' => $search,
    ];
    $records = $this->feedback_model->applications_search_rows($limit, $start,$search, $order, $dir,$matchArray);
    //  $records = $this->feedback_model->get_filtered_feedback($projectionArray,$matchArray,$searchArray,$start,$limit,$orderArray);
    //  pre( $records);
    }

    $totalFiltered = count((array)$this->feedback_model->get_filtered_feedback($projectionArray,$matchArray));
    // $totalFiltered = count((array)$this->feedback_model->applications_tot_search_rows(trim($this->input->post("search")["value"]),$matchArray));
    // pre( $totalFiltered );
    $this->session->set_userdata('feedback_info_match_array',$matchArray);

    $data = array();
    if (!empty($records)) {
      foreach ($records as $rows) {
        $nestedData["appl_ref_no"] = $rows->appl_ref_no;
        $nestedData["service_name"] = property_exists($rows,"service_name") ?  $rows->service_name:'';
        $nestedData["department_name"] = property_exists($rows,"department_name")?  $rows->department_name:'';
        $nestedData["submission_location"] =property_exists($rows,"submission_location")?  $rows->submission_location:'';
        $nestedData["feedback_text"] =property_exists($rows,'feedback_text')?  $rows->feedback_text:'';
        $nestedData["stars"] =property_exists($rows,'stars')?  $rows->stars:'';
        $nestedData["created_at"] = format_mongo_date( $rows->created_at);
        $nestedData["feedback_on"] =property_exists($rows,'feedback_on')?  ucfirst($rows->feedback_on):'';
        $data[] = $nestedData;
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


  public function download_excel(){
    $this->isLoggedIn();
    $this->load->model('applications_model');
    $matchArray = $this->session->userdata('feedback_info_match_array');
//        pre($matchArray);
    if(!empty($matchArray)){
        $records = $this->feedback_model->get_filtered(['$and' => $matchArray]);
    }else{
        $records = $this->feedback_model->get_all([]);
    }
    // pre( $records);
    $this->session->unset_userdata('feedback_info_match_array');

    $objPHPExcel = new Spreadsheet();
    $objPHPExcel->setActiveSheetIndex(0);
    // set Header
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Appl Ref No');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Service Name');
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Stars');
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Feedback');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Feedback On');

    $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Department Name');
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Submission Location');
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Date');
  
    // set Row
    $rowCount = 2;
    foreach ($records as $objs) {
        $row = $objs;
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->appl_ref_no);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row->service_name ?? 'Unknown');
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row->stars);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->feedback_text ?? '');
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row->feedback_on);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row->department_name);
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row->submission_location);
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, format_mongo_date($row->created_at,'d-m-Y g:i a'));
       
        $rowCount++;
    }

    $fileName = 'Feedback Info Report.xlsx';
    $writer = new Xlsx($objPHPExcel);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'. $fileName.'"');
    $writer->save('php://output');
}


  private function isLoggedIn()
    {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "https") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        if (!isset($isLoggedIn) || $isLoggedIn != true) {
            if(!$this->input->is_ajax_request()){
                $this->session->set_userdata("redirectTo",$url);
            }
            $module = $this->uri->segment(1, 0);
            redirect(base_url($module.'/login/logout'));
           
            
        }
    }

    public function generate_submission_url($appl_ref_no){
      if($appl_ref_no){
        $aes = new AES($appl_ref_no, $this->encryption_key);
         $enc = $aes->encrypt();
         return urlencode($enc);
      }
    }
    private function validate_mobile($mobile)
      {
          return preg_match('/^[6-9]\d{9}$/', $mobile);
      }
    //cron jobs
    public function send_feedback_sms_on_submission(){
      $dlt_template_id="1007163765378872943";
     $is_test=false;
     $records = $this->feedback_model->get_applications_submitted_on_today($is_test);

     foreach($records as $row){
      
       $encry_id=$this->generate_submission_url($row->initiated_data->appl_ref_no);
       $rep="";
       $sms='Dear '.$row->initiated_data->attribute_details->applicant_name.', thanks for using RTPS portal. Please click the link below to rate your overall experience. https://rtps.assam.gov.in/mis/feedback/submission?id='.$encry_id;
        if( $is_test){
          if($this->validate_mobile("9742447516")){
          $rep=$this->sendSms("9742447516",$sms,$dlt_template_id);
          }
        }else{
          if($this->validate_mobile($row->initiated_data->attribute_details->mobile_number)){
            $rep=$this->sendSms($row->initiated_data->attribute_details->mobile_number,$sms,$dlt_template_id);
          }
        }
       
        $log=date('Y-d-m H:i:s')." : ".$row->initiated_data->appl_ref_no." Sms status for ".$row->initiated_data->attribute_details->mobile_number." : ".$rep.PHP_EOL;
        file_put_contents('./feedback_logs/feeback_sms_on_submission_log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
       
     }
    }

    public function send_feedback_sms_on_delivered(){
      $dlt_template_id="1007163756729860774";
      $is_test=false;
      $records = $this->feedback_model->get_applications_delivered_on_today($is_test);
   
      foreach($records as $row){
        $encry_id=$this->generate_submission_url($row->initiated_data->appl_ref_no);
        $rep="";
        $sms='Dear '.$row->initiated_data->attribute_details->applicant_name.', your application '.$row->initiated_data->appl_ref_no.' submitted at RTPS portal has been disposed of by the competent authority. Please click the link below to rate your overall experience. https://rtps.assam.gov.in/mis/feedback?id='.$encry_id;
        if( $is_test){
          if($this->validate_mobile("9742447516")){
          $rep=$this->sendSms("9742447516",$sms,$dlt_template_id);}
        }else{
          if($this->validate_mobile($row->initiated_data->attribute_details->mobile_number)){
          $rep=$this->sendSms($row->initiated_data->attribute_details->mobile_number,$sms,$dlt_template_id);
          }
        }
       
        $log=date('Y-d-m H:i:s')." : ".$row->initiated_data->appl_ref_no." Sms status for ".$row->initiated_data->attribute_details->mobile_number." : ".$rep.PHP_EOL;
        file_put_contents('./feedback_logs/feeback_sms_on_delivered_log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
      
      }
     
     }


      //cron jobs
    public function send_feedback_sms_all(){
      $dlt_template_id="1007163765378872943";
      ini_set('max_execution_time', 0);
      ini_set('memory_limit', '-1');
    //  $array=array();
     $records = $this->feedback_model->get_applications_all();
   // pre($records);
     foreach($records as $key=>$row){
       $encry_id=$this->generate_submission_url($row->initiated_data->appl_ref_no);
       $sms='Dear '.$row->initiated_data->attribute_details->applicant_name.', thanks for using RTPS portal. Please click the link below to rate your overall experience. https://rtps.assam.gov.in/mis/feedback/submission?id='.$encry_id;
        if($this->validate_mobile($row->initiated_data->attribute_details->mobile_number)){
        $rep=$this->sendSms($row->initiated_data->attribute_details->mobile_number,$sms,$dlt_template_id);
        }else{
          $rep="Invalid No";
        }
        $log=$key." . ".date('Y-d-m H:i:s')." : ".$row->initiated_data->appl_ref_no." Sms status for ".$row->initiated_data->attribute_details->mobile_number." : ".$rep.PHP_EOL;
        file_put_contents('./feeback_sms_on_all_log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
       $this->for_delivered($row);
     }
      
    }
    private function for_delivered($row){
      $dlt_template_id="1007163756729860774";
      if($row->execution_data){
        foreach($row->execution_data as $ex){
       
          if(!empty($ex->official_form_details) && property_exists($ex->official_form_details,'action') && $ex->official_form_details->action === "Deliver"){
            $encry_id=$this->generate_submission_url($row->initiated_data->appl_ref_no);
              $rep="";
              $sms='Dear '.$row->initiated_data->attribute_details->applicant_name.', your application '.$row->initiated_data->appl_ref_no.' submitted at RTPS portal has been disposed of by the competent authority. Please click the link below to rate your overall experience. https://rtps.assam.gov.in/mis/feedback?id='.$encry_id;
              
                if($this->validate_mobile($row->initiated_data->attribute_details->mobile_number)){
                $rep=$this->sendSms($row->initiated_data->attribute_details->mobile_number,$sms,$dlt_template_id);
                }
              
            
              $log=date('Y-d-m H:i:s')." : ".$row->initiated_data->appl_ref_no." Sms status for ".$row->initiated_data->attribute_details->mobile_number." : ".$rep.PHP_EOL;
              file_put_contents('./feeback_sms_on_delivered_all_log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
            
          }
        }
      }
    }



     //send SMS

     private function sendSms($number, $message_body,$dlt_template_id) {
            
      $ch = curl_init();
      $message_body = str_replace(" ", "%20", $message_body);
      $url = "https://smsgw.sms.gov.in/failsafe/MLink?username=rtpsasm.otp&pin=P4%26fO3%23xF4&message=" . $message_body . "&mnumber=" . $number . "&signature=ARTPSA&dlt_entity_id=1001367290000017171&dlt_template_id=" . $dlt_template_id;
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      $head = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      //var_dump($head);
      return $head;
      }//End of sendSms()
}
