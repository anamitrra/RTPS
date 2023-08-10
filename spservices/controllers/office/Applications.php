<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Applications extends Rtps
{

  //put your code here
  public function __construct()
  {
    parent::__construct();
    $this->load->model("office/application_model");
    if ($this->session->userdata()) {
      if ($this->session->userdata('isAdmin') === TRUE) {
        if($this->session->userdata('role_slug') ==="OFFSU"){
          redirect('spservices/office/dashboard');
        }
      } else {
        $this->session->sess_destroy();
        redirect('spservices/mcc/user-login');
      }
    } else {
      redirect('spservices/mcc/user-login');
    }
  }
  public function index()
  {
    $this->load->view("includes/office_includes/header", array("pageTitle" => " "));
    $this->load->view("office/all_applications");
    $this->load->view("includes/office_includes/footer");
  }
 
  public function get_all_applications()
  {
    $user_obj = $this->session->userdata('userId')->{'$id'};

    $total_submitted = $this->application_model->common_count(['service_data.service_id'=>'MCC']);
    if (!$total_submitted) {
      // list is empty.
      $totalData = 0;
    } else {
      $totalData = $total_submitted[0]->total;
    }

    $filter = [
      'form_data.pa_district_name' => $this->session->userdata('district_name'),
      'form_data.payment_status' => 'PAYMENT_COMPLETED'
    ];


    $match_filter['form_data.pa_district_name'] = $this->session->userdata('district_name');
    $match_filter['form_data.payment_status'] = 'PAYMENT_COMPLETED';

    if (!empty($this->input->post('rtps_no'))) {
      $match_filter['service_data.appl_ref_no'] = trim(strtoupper($this->input->post('rtps_no')));
      $filter = [
        'form_data.pa_district_name' => $this->session->userdata('district_name'),
        'form_data.payment_status' => 'PAYMENT_COMPLETED',
        'service_data.appl_ref_no' => $this->input->post('rtps_no'),
      ];
    }
    if (!empty($this->input->post('status'))) {
      $match_filter['service_data.appl_status'] = trim($this->input->post('status'));
      $filter = [
        'form_data.pa_district_name' => $this->session->userdata('district_name'),
        'form_data.payment_status' => 'PAYMENT_COMPLETED',
        'service_data.appl_status' => $this->input->post('status'),
      ];
    }
    if (!empty($this->input->post('community'))) {
      $match_filter['form_data.community'] = trim($this->input->post('community'));
      $filter = [
        'form_data.pa_district_name' => $this->session->userdata('district_name'),
        'form_data.payment_status' => 'PAYMENT_COMPLETED',
        'form_data.community' => $this->input->post('community'),
      ];
    }
    $limit = $this->input->post("length", TRUE);
    $start = $this->input->post("start", TRUE);

// pre($match_filter);
    $applications = $this->application_model->all_applications($match_filter, $limit, $start);

    $applicationss = $this->application_model->common_count($filter);
    if (!$applicationss) {
      // list is empty.
      $totalFiltered = 0;
    } else {
      $totalFiltered = $applicationss[0]->total;
    }

    $sl = 1;
    $data = array();
    $status = '';
    foreach ($applications as $value) {
      if ($value->service_data->appl_status == 'PAYMENT_COMPLETED') {
        $status = '<span class="text-info text-bold">Submitted</span>';
        $nestedData["certificate"] = '';
      } elseif ($value->service_data->appl_status == "UNDER_PROCESSING") {
        $status = '<span class="text-primary text-bold">Under Process</span>';
        $nestedData["certificate"] = '';
      } elseif ($value->service_data->appl_status == 'DELIVERED') {
        $status = '<span class="text-success text-bold">Delivered</span>';
        $nestedData["certificate"] =  "<a class='btn btn-sm' href='" . base_url($value->execution_data[0]->official_form_details->output_certificate) . "' target='_blank'><i class='fa fa-file-pdf' style='color:red'></i></a>";
      } elseif ($value->service_data->appl_status == 'REJECTED') {
        $status = '<span class="text-danger text-bold">Rejected</span>';
        $nestedData["certificate"] = '';
      } elseif ($value->service_data->appl_status == 'QUERY_ARISE') {
        $status = '<span class="text-warning text-bold">Queried to Applicant</span>';
        $nestedData["certificate"] = '';
      } elseif ($value->service_data->appl_status == 'QUERY_SUBMITTED') {
        $status = '<span class="text-dark text-bold">Query Replied</span>';
        $nestedData["certificate"] = '';
      }
      $badge = '';
      $service_timeline = 15;

      $submission_date = date('Y-m-d', strtotime($this->mongo_db->getDateTime($value->form_data->created_at)));
      $cur_date = date('Y-m-d');
      $datetime1 = date_create($submission_date);
      $datetime2 = date_create($cur_date);
      $interval = date_diff($datetime1, $datetime2);
      $pening_days = $interval->format('%a');

      if (($value->service_data->appl_status != 'DELIVERED') && ($value->service_data->appl_status != 'REJECTED')) {
        if ($pening_days > $service_timeline) {
          $badge = '<span class="badges" style="background-color:tomato;color:#fff; padding:1px 4px;">Beyond time</span>';
        } else {
          $badge = '<span class="badges" style="background-color:#f6e902;color:#000; padding:1px 4px;display:block">Deadline in<br>' . ($service_timeline - $pening_days) . ' days</span>';
        }
      }

      $nestedData["badge"] = $badge;
      $nestedData["sl_no"] = $sl;
      $nestedData["rtps_trans_id"] = $value->service_data->appl_ref_no;
      $nestedData["trans_ide"] = base64_encode($value->service_data->appl_ref_no);
      $nestedData["name"] = $value->form_data->applicant_name;
      $nestedData["mobile_number"] = $value->form_data->mobile_number;
      $nestedData["applicant_gender"] = $value->form_data->applicant_gender;
      $nestedData["father_name"] = $value->form_data->father_name;
      $nestedData["service_name"] = $value->service_data->service_name;
      $nestedData["community"] = $value->form_data->community;
      $nestedData["service_id"] = base64_encode($value->service_data->service_id);
      $nestedData["status"] = $status;
      $nestedData["date"] = format_mongo_date($value->form_data->created_at ?? '');
      if ($value->service_data->appl_status == 'DELIVERED') {
        $nestedData["ddate"] = format_mongo_date($value->execution_data[0]->task_details->executed_time);
      } else {
        $nestedData["ddate"] = '';
      }
      if (((count($value->execution_data) == 1) && ($this->session->userdata('role_slug') == 'DPS')) || ($value->execution_data[0]->task_details->action_taken == 'N') && ($value->execution_data[0]->task_details->user_detail) && ($value->execution_data[0]->task_details->user_detail->user_id == $user_obj)) {
        $nestedData["action"] = "<a class='btn btn-xs' title='Click to process application' href=" . site_url("spservices/office/applications/action/") . base64_encode($value->service_data->appl_ref_no) . "><i class='fa fa-edit' style='color:tomato;font-size:18px'></i></a>";
      } else {
        $nestedData["action"] = '';
      }
      $data[] = $nestedData;
      $sl++;
    }

    // $json_data = array(
    //   "data" => $data,
    // );
    // echo json_encode($json_data);
    $json_data = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data" => $data,
    );
    echo json_encode($json_data);
  }

  public function pending_application()
  {
    $this->load->view("includes/office_includes/header", array("pageTitle" => "Pending Applications"));
    $this->load->view("office/pending_applications");
    $this->load->view("includes/office_includes/footer");
  }

  public function get_pending_applications()
  {
    $applications = $this->application_model->pending_applications();
    // pre($applications);
    $sl = 1;
    $data = array();
    foreach ($applications as $value) {
      $nestedData["sl_no"] = $sl;
      $nestedData["rtps_trans_id"] = $value->service_data->appl_ref_no;
      $nestedData["trans_ide"] = base64_encode($value->service_data->appl_ref_no);
      $nestedData["name"] = $value->form_data->applicant_name;
      $nestedData["mobile_number"] = $value->form_data->mobile_number;
      $nestedData["applicant_gender"] = $value->form_data->applicant_gender;
      $nestedData["father_name"] = $value->form_data->father_name;
      $nestedData["service_name"] = $value->service_data->service_name;
      $nestedData["community"] = $value->form_data->community;
      $nestedData["service_id"] = base64_encode($value->service_data->service_id);
      $nestedData["date"] = date('d/m/Y', strtotime($this->mongo_db->getDateTime($value->form_data->created_at ?? '')));
      $data[] = $nestedData;
      $sl++;
    }
    $json_data = array(
      "data" => $data,
    );
    echo json_encode($json_data);
  }

  public function application_details($id)
  {
    $id = base64_decode($id);
    $data = $this->application_model->get_single_application($id);
    $this->load->view("includes/office_includes/header", array("pageTitle" => "Application Details"));
    $this->load->view("office/application_details", array('data' => $data));
    $this->load->view("includes/office_includes/footer");
  }

  public function application_detail($id)
  {
    $id = base64_decode($id);
    $data = $this->application_model->get_single_application($id);
    // $this->load->view("includes/office_includes/header", array("pageTitle" => "Application Details"));
    $this->load->view("office/application_detail", array('data' => $data));
    // $this->load->view("includes/office_includes/footer");
  }

  // get_dps
  public function get_dps()
  {
    $dist =  $this->input->post('dist');
    $filter['district_name'] = $dist;
    $filter['circle_name'] = "";
    $filter['role_slug_name'] = 'DPS';
    $filter['is_active'] = 1;

    // pre($filter);
    // role_slug_name
    echo json_encode(array('data' => $this->get_users($filter)));
  }

  public function get_da()
  {
    $dist =  $this->input->post('dist');
    // $office_code = $this->input->post('office_code');
    $filter['district_name'] = $dist;
    $filter['circle_name'] = "";
    // $filter['office_code'] = $office_code;
    // $filter['designation'] = 'Dealing Assistant';
    $filter['role_slug_name'] = 'DA';
    $filter['is_active'] = 1;
    // pre($filter);
    // role_slug_name
    echo json_encode(array('data' => $this->get_users($filter)));
  }

  public function get_co_list()
  {
    $dist =  $this->input->post('dist');
    $circle = $this->input->post('location');
    $filter['district_name'] = $dist;
    $filter['circle_name'] = $circle;
    $filter['is_active'] = 1;
    // Revenue Officer
    if ($this->session->userdata('district_name') == 'Karbi Anglong') {
      $data = (array)$this->application_model->get_ro_aro_user($dist, $circle);
    } else {
      $filter['role_slug_name'] = 'CO';
      $data = $this->get_users($filter);
    }
    echo json_encode(array('data' => $data));
  }

  public function get_sk_list()
  {
    $dist =  $this->session->userdata('district_name');
    $circle = $this->session->userdata('circle_name');
    $filter['district_name'] = $dist;
    $filter['circle_name'] = $circle;
    $filter['role_slug_name'] = 'SK';
    $filter['is_active'] = 1;
    // $filter['designation'] = 'Supervisor Kanungo';
    echo json_encode(array('data' => $this->get_users($filter)));
  }

  public function get_lm_list()
  {
    $dist =  $this->session->userdata('district_name');
    $circle = $this->session->userdata('circle_name');
    $filter['district_name'] = $dist;
    $filter['circle_name'] = $circle;
    $filter['role_slug_name'] = 'LM';
    $filter['is_active'] = 1;
    // $filter['designation'] = 'Lot Mondol';
    echo json_encode(array('data' => $this->get_users($filter)));
  }

  // get_lp_list

  public function get_lp_list()
  {
    $dist =  $this->session->userdata('district');
    $circle = $this->session->userdata('circle_name');
    $filter['district'] = $dist;
    $filter['circle_name'] = $circle;
    $filter['role_slug_name'] = 'LP';
    $filter['is_active'] = 1;
    echo json_encode(array('data' => $this->get_users($filter)));
  }
  // get_ro_aro_list
  public function get_ro_aro_list()
  {
    $dist =  $this->session->userdata('district');
    $office_code = $this->session->userdata('office_code');
    $filter['district'] = $dist;
    $filter['office_code'] = $office_code;
    $filter['designation'] = 'Revenue Officer';
    echo json_encode(array('data' => $this->get_users($filter)));
  }

  public function get_co_list_sk()
  {
    $dist =  $this->session->userdata('district_name');
    $circle = $this->session->userdata('circle_name');
    $filter['district_name'] = $dist;
    $filter['circle_name'] = $circle;
    $filter['role_slug_name'] = 'CO';
    // $filter['designation'] = 'Circle Officer';
    echo json_encode(array('data' => $this->get_users($filter)));
  }

  public function get_da_co()
  {
    $dist = $this->session->userdata('district_name');
    $filter['district_name'] = $dist;
    $filter['circle_name'] = "";
    $filter['role_slug_name'] = 'DA';
    echo json_encode(array('data' => $this->get_users($filter)));
  }

  private function get_users($filter)
  {
    return (array)$this->mongo_db->where($filter)->get('office_users');
  }
}
