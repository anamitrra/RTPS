<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
class Applications extends Rtps
{
  /**
   * __construct
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->model('applications_model');
    $this->load->model('department_model');
  }

    /**
     * @param $deptId
     * @param $serviceId
     * @param $startDate
     * @param $endDate
     * @param $paymentMode
     * @return array
     */
    public static function prepare_revenue_filter_array($deptId, $serviceId, $startDate, $endDate,$paymentMode): array
    {
        $filterArray = ['initiated_data.department_id' => $deptId, 'initiated_data.base_service_id' => $serviceId];
      //pre(new MongoDB\BSON\UTCDateTime(strtotime($startDate) * 1000));
        if(isset($startDate) && isset($endDate)){
            $filterArray["initiated_data.submission_date"] = [

                "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($startDate) * 1000),
                "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($endDate)) * 1000)
            ];
        }

        switch ($paymentMode) {
            case 'online':
                $filterArray['$and'] = [
                    ['initiated_data.payment_mode' => ['$regex' => '^' . 'EGRAS Assam' . '', '$options' => 'i']],
                ];
                break;
            case 'offline':
                $filterArray['$and'] = [
                    ['initiated_data.payment_mode' => ['$regex' => '^' . 'Cash' . '', '$options' => 'i']],
                ];
                break;
            default:
                $filterArray['$or'] = [
                    ['initiated_data.payment_mode' => ['$regex' => '^' . 'EGRAS Assam' . '', '$options' => 'i']],
                    ['initiated_data.payment_mode' => ['$regex' => '^' . 'Cash' . '', '$options' => 'i']],
                ];
                break;
        }
        return $filterArray;
    }

    /**
   * index
   *
   * @return void
   */
  public function index()
  {
    $this->load->model('services_model');
    $this->load->model('offices_model');
    $offices = $this->offices_model->all();
   // pre($offices);
    //$dept_id = $this->session->userdata("department_id");
    $services = $this->services_model->all();
    $this->load->view('includes/header', array("pageTitle" => "Mis | Applications"));
    $this->load->view('applications/applications_list', array("services" => $services,"office" => $offices));
    $this->load->view('includes/footer');
  }
  /**
   * pending
   *
   * @return void
   */
  public function pending()
  {
    $this->load->model('services_model');
    //$dept_id = $this->session->userdata("department_id");
    $services = $this->services_model->all();
    $this->load->view('includes/header', array("pageTitle" => "Mis | Applications"));
    $this->load->view('applications/applications_pending_list', array("services" => $services));
    $this->load->view('includes/footer');
  }
  /**
   * delivered
   *
   * @return void
   */
  public function delivered()
  {
    $this->load->model('services_model');
    //$dept_id = $this->session->userdata("department_id");
    $services = $this->services_model->all();
    $this->load->view('includes/header', array("pageTitle" => "Mis | Applications"));
    $this->load->view('applications/applications_delivered_list', array("services" => $services));
    $this->load->view('includes/footer');
  }
  /**
   * get_records
   *
   * @return void
   */
  public function get_records()
  {
    $columns = array(
      0 => "initiated_data.appl_ref_no",
      1 => "initiated_data.service_name",
      2 => "initiated_data.submission_date",
      3 => "initiated_data.submission_location",
      4 => "execution_data[0].official_form_details.action",
     // 5 => "initiated_data.version_no",
    );
    $limit = $this->input->post("length", TRUE);
    $start = $this->input->post("start", TRUE);
    $startDate = $this->input->post("start_date", TRUE);
    $endDate = $this->input->post("end_date", TRUE);
    $service_status = $this->input->post("service_status", TRUE);
    $services = $this->input->post("services", TRUE);
    $office = $this->input->post("office", TRUE);
   // die($office);
    $order = $columns[$this->input->post("order")[0]["column"]];
    $dir = $this->input->post("order")[0]["dir"];
    $totalData = $this->applications_model->total_rows();
    $totalFiltered = $totalData;
    $temp = array();
    if ($startDate != null && $endDate != null) {
      $temp["startDate"] = $startDate;
      $temp["endDate"] = $endDate;
    }
    if (isset($service_status) && $service_status != NULL) {
      $temp["service_status"] = $service_status;
    }
    if (isset($services) && $services != NULL) {
      $temp["services"] = $services;
    }
    if (isset($office) && $office != NULL) {
   
      $temp["office"] = $office;
     
    }
    if (empty($this->input->post("search")["value"])) {
      $this->session->set_userdata($temp);
     
      $records = $this->applications_model->applications_filter($limit, $start, $temp, $order, $dir);
      $totalFiltered = $this->applications_model->applications_filter_count($temp);
    } else {
      $search = trim($this->input->post("search")["value"]);
      $records = $this->applications_model->applications_search_rows($limit, $start, $search, $order, $dir);
      $totalFiltered = $this->applications_model->applications_tot_search_rows($search);
    }
    $data = array();
    //print_r($records);
    if (!empty($records)) {
      foreach ($records as $objs) {
        //echo $objs->{'_id'}->{'$id'};
        $rows = $objs->initiated_data;
        $exc_data = $objs->execution_data;
        // $task = "Not Available";
        // if (isset($exc_data[0])) {
        //   $task = "" . $exc_data[0]->task_details->task_name . "(" . $exc_data[0]->task_details->user_name . ")";
        // }
        $btns = '<a href="#!" data-appl_ref_no="' . $rows->appl_ref_no . '" data-id="' . $objs->{'_id'}->{'$id'} . '" title="View" class="modal-show"><span class="fa fa-eye" aria-hidden="true"></span></a> ';
        // <a href="'.base_url("mis/users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
        // <a href="'.base_url("mis/users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
        $nestedData["appl_ref_no"] = $rows->appl_ref_no;
       // $nestedData["applicant_name"] = $rows->applied_by;
       $nestedData["applicant_name"] = $rows->service_name;
        $nestedData["sub_date"] = $this->mongo_db->getDateTime($rows->submission_date);
        $nestedData["sub_office"] = $rows->submission_location;
       // $nestedData["curr_task"] = $task;
        //$nestedData["version"] = $rows->version_no;
       // print_r($exc_data[0]->official_form_details->action);
        error_reporting(0);
        if($exc_data[0]->official_form_details->action == null){
          $nestedData["version"] ='Pending';
        }
        else{
          $nestedData["version"] = $exc_data[0]->official_form_details->action;
        }
        
        
        $nestedData["action"] = $btns;
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
  /**
   * get_records_pending
   *
   * @return void
   */
  public function get_records_pending()
  {
    $columns = array(
      0 => "initiated_data.appl_ref_no",
      1 => "initiated_data.applied_by",
      2 => "initiated_data.submission_date",
      3 => "initiated_data.submission_location",
      5 => "initiated_data.version_no",
    );
    $limit = $this->input->post("length", TRUE);
    $start = $this->input->post("start", TRUE);
    $startDate = $this->input->post("start_date", TRUE);
    $endDate = $this->input->post("end_date", TRUE);
    $service_status = $this->input->post("service_status", TRUE);
    $services = $this->input->post("services", TRUE);
    $office = $this->input->post("office", TRUE);
    $order = $columns[$this->input->post("order")[0]["column"]];
    $dir = $this->input->post("order")[0]["dir"];
    $totalData = $this->applications_model->total_rows();
    $totalFiltered = $totalData;
    $temp = array();
    if ($startDate != null && $endDate != null) {
      $temp["startDate"] = $startDate;
      $temp["endDate"] = $endDate;
    }
    if (isset($service_status) && $service_status != NULL) {
      $temp["service_status"] = $service_status;
    }
    if (isset($services) && $services != NULL) {
      $temp["services"] = $services;
    }
    if (isset($office) && $office != NULL) {
   
      $temp["office"] = $office;
     
    }
    if (empty($this->input->post("search")["value"])) {
      $this->session->set_userdata($temp);
      $records = $this->applications_model->applications_filter_pending($limit, $start, $temp, $order, $dir);
      $totalFiltered = $this->applications_model->applications_filter_count_pending($temp);
    } else {
      $search = trim($this->input->post("search")["value"]);
      $records = $this->applications_model->applications_search_rows_pending($limit, $start, $search, $order, $dir);
      $totalFiltered = $this->applications_model->applications_tot_search_rows_pending($search);
    }
    $data = array();
    ///print_r($records);
    if (!empty($records)) {
      foreach ($records as $objs) {
        //echo $objs->{'_id'}->{'$id'};
       
        $rows = $objs->initiated_data;
        $service_time_line=$this->applications_model->check_timeline_using_appl_ref_no($rows->appl_ref_no);
        $exc_data = $objs->execution_data;
        
        $task = "Not Available";
        if (isset($exc_data[0])) {
          if(isset($exc_data[0]->task_details->user_name )){
            $task = "" . $exc_data[0]->task_details->task_name . "(" . $exc_data[0]->task_details->user_name . ")<br>Received On:" . format_mongo_date($exc_data[0]->task_details->received_time) . "";
          }else{
            $task = "" . $exc_data[0]->task_details->task_name;
          }
          
        }
        if(isset($service_time_line->service_time_line))
        $due_date=date("d-m-Y", strtotime($this->mongo_db->getDateTime($rows->submission_date).'+ '.$service_time_line->service_time_line.'days'));
        
        $curtimestamp1 = strtotime($due_date??date('Y-m-d'));
        $curtimestamp2 = strtotime(date("d-m-Y"));        
        if ($curtimestamp1 > $curtimestamp2)
           $badge='<span class="badge badge-success">'.$due_date??date('Y-m-d').'</span>';
        else
           $badge='<span class="badge badge-danger">'.$due_date??date('Y-m-d').'</span>';
        
        $btns = '<a href="#!" data-appl_ref_no="' . $rows->appl_ref_no . '" data-id="' . $objs->{'_id'}->{'$id'} . '" title="View" class="modal-show"><span class="fa fa-eye" aria-hidden="true"></span></a> ';
        $nestedData["appl_ref_no"] = $rows->appl_ref_no;
        $nestedData["applicant_name"] = $rows->applied_by;
        $nestedData["sub_date"] = $this->mongo_db->getDateTime($rows->submission_date);
        $nestedData["sub_office"] = $rows->submission_location;
        $nestedData["curr_task"] = $task;
        $nestedData["due_date"] = $badge;
        $nestedData["version"] = property_exists($rows,'version_no')? $rows->version_no:"";
        $nestedData["action"] = $btns;
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

  /**
   * get_records_delivered
   *
   * @return void
   */
  public function get_records_delivered()
  {
    $columns = array(
      0 => "initiated_data.appl_ref_no",
      1 => "initiated_data.applied_by",
      2 => "initiated_data.submission_date",
      3 => "initiated_data.submission_location",
      5 => "initiated_data.version_no",
    );
    $limit = $this->input->post("length", TRUE);
    $start = $this->input->post("start", TRUE);
    $startDate = $this->input->post("start_date", TRUE);
    $endDate = $this->input->post("end_date", TRUE);
    $service_status = $this->input->post("service_status", TRUE);
    $services = $this->input->post("services", TRUE);
    $office = $this->input->post("office", TRUE);
    $order = $columns[$this->input->post("order")[0]["column"]];
    $dir = $this->input->post("order")[0]["dir"];
    $totalData = $this->applications_model->total_rows();
    $totalFiltered = $totalData;
    $temp = array();
    if ($startDate != null && $endDate != null) {
      $temp["startDate"] = $startDate;
      $temp["endDate"] = $endDate;
    }
    if (isset($service_status) && $service_status != NULL) {
      $temp["service_status"] = $service_status;
    }
    if (isset($services) && $services != NULL) {
      $temp["services"] = $services;
    }
    if (isset($office) && $office != NULL) {
   
      $temp["office"] = $office;
     
    }
    if (empty($this->input->post("search")["value"])) {
      $this->session->set_userdata($temp);
      $records = $this->applications_model->applications_filter_delivered($limit, $start, $temp, $order, $dir);
      $totalFiltered = $this->applications_model->applications_filter_count_delivered($temp);
    } else {
      $search = trim($this->input->post("search")["value"]);
      $records = $this->applications_model->applications_search_rows_delivered($limit, $start, $search, $order, $dir);
      $totalFiltered = $this->applications_model->applications_tot_search_rows_delivered($search);
    }
    $data = array();
    //print_r($records);
    if (!empty($records)) {
      foreach ($records as $objs) {
        //echo $objs->{'_id'}->{'$id'};
        $rows = $objs->initiated_data;
        $exc_data = $objs->execution_data;
        $task = "Not Available";
        if (isset($exc_data[0])) {
          if(isset($exc_data[0]->task_details->user_name)){
            $task = "" . $exc_data[0]->task_details->task_name . "(" . $exc_data[0]->task_details->user_name . ")";
          }else{
            $task = "" . $exc_data[0]->task_details->task_name ;
          }
          // $task = "" . $exc_data[0]->task_details->task_name . "(" . $exc_data[0]->task_details->user_name . ")";
         
        }
        $btns = '<a href="#!" data-appl_ref_no="' . $rows->appl_ref_no . '" data-id="' . $objs->{'_id'}->{'$id'} . '" title="View" class="modal-show"><span class="fa fa-eye" aria-hidden="true"></span></a> ';
        // <a href="'.base_url("mis/users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
        // <a href="'.base_url("mis/users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
        $nestedData["appl_ref_no"] = $rows->appl_ref_no;
        $nestedData["applicant_name"] = $rows->applied_by;
        $nestedData["sub_date"] = $this->mongo_db->getDateTime($rows->submission_date);
        $nestedData["sub_office"] = $rows->submission_location;
        $nestedData["curr_task"] = $task;
        $nestedData["version"] =property_exists($rows,'version_no')? $rows->version_no:"";
        $nestedData["action"] = $btns;
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

  /**
   * view
   *
   * @return void
   */
  public function view()
  {
    $ref_no = strval($this->input->get('data_id', true));
    $data = $this->applications_model->get_by_doc_id($ref_no);
    //print_r($data);
    $this->load->view("applications/view_application", array('data' => $data->initiated_data, 'execution_data' => $data->execution_data));
  }
  /**
   * get_state
   *
   * @return void
   */
  public function get_state()
  {
    $state = $this->input->get("state");
    $get_district = $this->applications_model->get_dist($state);
    echo '<label for="sel1">From District :</label><select id="from_district" class="form-control form-control-sm">';
    echo '<option value="">Please select</option>';
    foreach ($get_district as $val) {
      echo '<option value="' . $val->district . '">' . $val->district . '</option>';
    }
    echo '</select>';
  }
  /**
   * generatexls
   *
   * @return void
   */
  public function generatexls()
  {
    // create file name
    $fileName = 'data-' . time() . '.xlsx';
    // load excel library
    $this->load->library('excel');
    $temp = array();
    if ($this->session->has_userdata("startDate") || $this->session->has_userdata("endDate") || $this->session->has_userdata("service_status") || $this->session->userdata("services") || $this->session->userdata("office")) {
      if ($this->session->has_userdata("startDate") && $this->session->has_userdata("endDate")) {
        $temp["startDate"] = $this->session->userdata("startDate");
        $temp["endDate"] = $this->session->userdata("endDate");
      }
      if ($this->session->has_userdata("service_status")) {
        $temp["service_status"] = $this->session->userdata("service_status");
      }
      if ($this->session->has_userdata("services")) {
        $temp["services"] = $this->session->userdata("services");
      }
           if ($this->session->has_userdata("office")) {
        $temp["office"] = $this->session->userdata("office");
      }
     // pre($temp);
      $records = $this->applications_model->applications_filter(200000, 0, $temp, "appl_ref_no", "DESC");
     // pre($records);
    } else {
      $records = $this->applications_model->all_rows(200000, 0, "initiated_data.appl_ref_no", "DESC");
    }
    $this->session->unset_userdata('startDate');
    $this->session->unset_userdata('endDate');
    $this->session->unset_userdata('service_status');
    $this->session->unset_userdata('services');
    $this->session->unset_userdata('office');
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    
    // set Header
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Application Ref No');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Service Name');
    $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Submission Date');
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Submission Office');
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Status');
   // $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Version');
    // set Row
    $rowCount = 2;
    foreach ($records as $objs) {
      $list = $objs->initiated_data;
      $exc_data = $objs->execution_data;//pre($exc_data);
      $task = "Not Available";
      // if (isset($exc_data[0])) {
      //   //$task = "" . $exc_data[0]->task_details->task_name . "(" . $exc_data[0]->task_details->user_name . ")";
      //  $task = $exc_data[0]->official_form_details->action;
      // }
      if($exc_data[0]->official_form_details == null){
        $task ='Initiated';
      }
      else{
      if($exc_data[0]->official_form_details->action == null){
        $task ='Initiated';
      }
      else{
        $task = $exc_data[0]->official_form_details->action;
      }
    }
    
      error_reporting(0);
      $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $list->appl_ref_no);
      $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $list->service_name);
      $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $this->mongo_db->getDateTime($list->submission_date));
   
      //$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, get_application_sub_location($list->submission_location));
      $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $list->submission_location);
      $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $task);// pre($rowCount);
     // $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $list->version_no);
      $rowCount++;
    }
   
    $filename = "Applications" . date("Y-m-d-H-i-s") . ".xlsx";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
   // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
    $objWriter->save('php://output');
  }

  /**
   * application_month_wise
   *
   * @return void
   */
  public function application_month_wise()
  {
    $application_month_wise=array();
    $months=array_reduce(range(1,12),function($rslt,$m){ $rslt[$m] = date('F',mktime(0,0,0,$m,10)); return $rslt; });
    $collection="applications";
    foreach($months as $num => $month){
      $operations=array(
        array(
          '$project' => array(
              "month"=> array("\$month"=>"\$initiated_data.submission_date")
          )
          ),
        array(
          '$match' => array(
              "month"=> $num
          )
          ),
        array(
          '$count' => "doc_nos"
         )
      );
      $data_total=$this->mongo_db->aggregate($collection,$operations);
      if(isset($data_total->{'0'})){
        array_push($application_month_wise,array($month.'('.$data_total->{'0'}->doc_nos.')',$data_total->{'0'}->doc_nos,$data_total->{'0'}->doc_nos));
      }else{
        array_push($application_month_wise,array($month.'(0)',0,0));
      }
      
    }
    return $application_month_wise;
  }
  /**
   * count_applications
   *
   * @return void
   */
  public function count_applications()
  {
    $total = $this->applications_model->total_rows();
    $departments = $this->department_model->get_departments();
    $service = 0;
    foreach ($departments as $dept) {
      $serviceList = $this->department_model->get_service_list_by_department_id($dept->department_id);
      $service += count($serviceList);
    }
/*     $forward = $this->applications_model->tot_search_rows(array(
      'execution_data.0.official_form_details.action' =>
      'Forward',
    )); */
    $deliver = $this->applications_model->tot_search_rows(array(
      'execution_data.0.official_form_details.action' =>
      'Deliver',
    ));
    $reject = $this->applications_model->tot_search_rows(array(
      'execution_data.0.official_form_details.action' =>
      'Reject',
    ));

    $deliverIT=$this->calculate_timely_delivery();
    $pendingIT=$this->calculate_pending_time();
    $rejectIT=$this->calculate_reject_time();
    $total_pending=($total-($reject+$deliver));
    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode(array(
        'total' => $total ?? 0,
        'services' => $service ?? 0,
        'departments' => count((array) $departments) ?? 0,
        'application_delivered' => $deliver ?? 0,
        'application_delivered_in_time' => $deliverIT ?? 0,
        'application_delivered_beyond_time' => ($deliver-$deliverIT) ?? 0,
        'application_rejected' => $reject ?? 0,
        'application_rejected_in_time' => $rejectIT ?? 0,
        'application_rejected_beyond_time' => $reject-$rejectIT ?? 0,
        'application_pending' =>  $total_pending ?? 0,
        'application_pending_in_time' => $pendingIT ?? 0,
        'application_pending_beyond_time' => ($total_pending-$pendingIT) ?? 0,
        'application_month_wise' => $this->application_month_wise()
      )));
  }


  // API for Top Services
  /**
   * top_services
   *
   * @return void
   */
  public function top_services()
  {
        $received = $this->applications_model->total_services_group_by_service_top_services();
        $top_services = array();
        $delivered = $this->applications_model->total_services_delivered_group_by_service();
        if (!is_array($received)) $received = (array)$received;
        if (!is_array($delivered)) $delivered = (array)$delivered;

        foreach ($received as $key => $r_val) {
            foreach ($delivered as $key => $d_val) {
                if ($d_val->_id == $r_val->_id) {
                    $r_val->delivered = isset($d_val->count) ? $d_val->count : 0;
                }
            }
            if (!isset($r_val->delivered)) {
                $r_val->delivered = 0;
            }      
            array_push($top_services, array($r_val->service_name,$r_val->delivered ,$r_val->total_received));
        }
       
    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($top_services));
  }
  /**
   * leading_departments
   *
   * @return void
   */
  public function leading_departments()
  {
    $leading_departments = array();
    $departments = $this->department_model->get_departments();
    $service = 0;
    foreach ($departments as $dept) {
      $filter["initiated_data.department_id"] = $dept->department_id;
      $total = $this->applications_model->tot_search_rows($filter);
      $deliver = $this->applications_model->tot_search_rows(array("\$and" => array(
        array(
          'execution_data.0.official_form_details.action' => 'Deliver'
        ),
        array('initiated_data.department_id' => $dept->department_id,)
      )));
      // $reject = $this->applications_model->tot_search_rows(array("\$and" => array(
      //   array(
      //     'execution_data.0.official_form_details.action' => 'Reject'
      //   ),
      //   array('initiated_data.department_id' => $dept->department_id,)
      // )));
      $total = $total ?? 0;
      array_push($leading_departments, array($dept->department_name, $deliver, $total));
    }

    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($leading_departments));
  }
  /**
   * calculate_timely_delivery
   *
   * @return void
   */
  public function calculate_timely_delivery()
  {
      $this->load->model("applications_model");
      if($this->applications_model->check_timeline()){
        return $this->applications_model->check_timeline()->pass;
      }else{
        return 0;
      }
  }
  public function calculate_pending_time()
  {
      $this->load->model("applications_model");
      if($this->applications_model->check_timeline_for_pending_applications()){
        return $this->applications_model->check_timeline_for_pending_applications()->pass;
      }else{
        return 0;
      }
  }
  public function calculate_reject_time()
  {
      $this->load->model("applications_model");
      if($this->applications_model->check_timeline_for_rejected_applications()){
        return $this->applications_model->check_timeline_for_rejected_applications()->pass;
      }else{
        return 0;
      }
      
  }

  public function view_revenue_filter(){
      $this->load->model("department_model");
      $data['departments']=$this->department_model->get_departments();
      $this->load->view('includes/header', array("pageTitle" => "MIS | Revenue Filter"));
      $this->load->view('applications/view_revenue_filter', $data);
      $this->load->view('includes/footer');
  }

  public function get_revenue_collected(){
      $deptId = $this->input->post('deptId');
      $serviceId = $this->input->post('serviceId');
      $startDate = $this->input->post('startDate');
      $endDate = $this->input->post('endDate');

      $paymentMode = $this->input->post('paymentMode');
      $filterArray = self::prepare_revenue_filter_array($deptId, $serviceId, $startDate, $endDate,$paymentMode);
      $this->load->library('mongo_db');
      $filteredRevenues = $this->applications_model->get_filtered_revenue($filterArray,['initiated_data.amount']);
      $revenueCollected = 0;
      foreach ($filteredRevenues as $revenue){
          $revenueCollected += $revenue->initiated_data->amount;
      }
      return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode(array(
              'revenueCollected' => $revenueCollected,
              'status' => true,
          )));
  }

  public function get_records_revenue_filter(){
      $deptId = $this->input->post('deptId');
      $serviceId = $this->input->post('serviceId');
      $startDate = $this->input->post('startDate');
      $endDate = $this->input->post('endDate');
      $paymentMode = $this->input->post('paymentMode');
//      if(!isset($deptId) || !isset($serviceId) || (!isset($startDate) && !isset($endDate) )|| !isset($paymentMode)){
//          return false; // filter not provided
//      }
      $columns = array(
          0 => "initiated_data.appl_ref_no",
          1 => "initiated_data.applied_by",
          2 => "initiated_data.submission_date",
          3 => "initiated_data.submission_location",
          4 => "initiated_data.payment_date",
          5 => "initiated_data.amount",
      );
      $filterArray = self::prepare_revenue_filter_array($deptId, $serviceId, $startDate, $endDate,$paymentMode);
      $limit = $this->input->post("length", TRUE);
      $start = $this->input->post("start", TRUE);
      $order = $columns[$this->input->post("order")[0]["column"]];
      $dir = $this->input->post("order")[0]["dir"];
      $totalData = $this->applications_model->total_rows();
      if (empty($this->input->post("search")["value"])) {
//          echo 'if';
          $records = $this->applications_model->get_filtered_revenue($filterArray,$columns,[],$start,$limit,$order,$dir);
      } else {
//          echo 'else';
          $search = trim($this->input->post("search")["value"]);
          $searchArray = [
              'initiated_data.appl_ref_no' => $search,
              'initiated_data.sub_office' => $search,
              'initiated_data.payment_date' => $search,
              'initiated_data.amount' => $search,
          ];
          $records = $this->applications_model->get_filtered_revenue($filterArray,$columns,$searchArray,$start,$limit,$order,$dir);
      }
      $totalFiltered = count((array)$this->applications_model->get_filtered_revenue($filterArray,['initiated_data.amount']));
//      pre($totalFiltered);
      $data = array();

      if (!empty($records)) {
          $sl_no = 0;
          foreach ($records as $objs) {
              //echo $objs->{'_id'}->{'$id'};
              $rows = $objs->initiated_data;
              $nestedData["#"] = ++$sl_no;
              $nestedData["appl_ref_no"] = $rows->appl_ref_no;
              $nestedData["applicant_name"] = $rows->applied_by;
              $nestedData["sub_date"] = $this->mongo_db->getDateTime($rows->submission_date);
              $nestedData["sub_office"] = $rows->submission_location;
              $nestedData["payment_date"] = $rows->payment_date;
              $nestedData["amount"] = $rows->amount;
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
}
