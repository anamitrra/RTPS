<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Certificate extends Rtps
{

  //put your code here
  public function __construct()
  {
    parent::__construct();
    $this->load->model("office/application_model");
    if ($this->session->userdata()) {
      if ($this->session->userdata('isAdmin') === TRUE) {
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
    $this->load->view("includes/office_includes/header", array("pageTitle" => "Action Form"));
    $this->load->view("office/certificate_list");
    $this->load->view("includes/office_includes/footer");
  }

  public function get_all_certificate()
  {

    $applications = $this->application_model->all_certificate();
    // pre($applications);
    $data = array();
    $sl = 1;
    $data = array();
    foreach ($applications as $value) {
      $nestedData["sl_no"] = $sl;
      $nestedData["rtps_trans_id"] = $value->service_data->appl_ref_no;
      $nestedData["cert_no"] = $value->form_data->certificate_no;
      $nestedData["name"] = $value->form_data->applicant_name;
      $nestedData["mobile_number"] = $value->form_data->mobile_number;
      $nestedData["trans_ide"] = base64_encode($value->service_data->appl_ref_no);
      $nestedData["address"] = $value->form_data->pa_village;
      $nestedData["applicant_gender"] = $value->form_data->applicant_gender;
      $nestedData["service_name"] = $value->service_data->service_name;
      $nestedData["community"] = $value->form_data->community;
      $nestedData["date"] = format_mongo_date($value->form_data->created_at ?? '');
      if ($value->service_data->appl_status == 'DELIVERED') {
        $nestedData["ddate"] = format_mongo_date($value->execution_data[0]->task_details->executed_time);
      } else {
        $nestedData["ddate"] = '';
      }
      $nestedData["certificate"] =  base_url($value->execution_data[0]->official_form_details->output_certificate);
      $data[] = $nestedData;
      $sl++;
    }
    $json_data = array(
      "data" => $data,
    );
    echo json_encode($json_data);
  }
}
