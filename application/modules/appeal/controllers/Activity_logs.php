<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Activity_logs extends Rtps {
  public function __construct() {
    parent::__construct();

    $this->load->model('activity_logs_model');
  }

  public function index() {
    $this->load->view('includes/header', array("pageTitle" => "Activity Logs"));
    $this->load->view('activity_logs/logs_list');
    $this->load->view('includes/footer');
  }

  public function get_records() {
    $this->load->model('users_model');
    $this->load->library("datatables");
    $columns = array("sl_no", "user", "request_uri", "timestamp", "client_ip", "client_user_agent", "refer_page");
    $limit = $this->input->post("length");
    $start = $this->input->post("start");
    $order = $columns[(int) $this->input->post("order")[0]["column"]];
    $dir = $this->input->post("order")[0]["dir"];

    // Load acrivity logs only for loged-in user
    // 'userId' is BSON OBJECT ID , i.e.
    /*  stdClass Object
    (
      [$id] => 5ee357fe0c5c00008a002bdd
    ) */
    $user_id = $this->session->userdata('userId')->{'$id'};

    $totalData = $this->activity_logs_model->total_rows($user_id);
    $totalFiltered = $totalData;

    $records = $this->activity_logs_model->all_rows($limit, $start, $order, $dir, $user_id);

    $data = array();

    if (!empty($records)) {
      $sl = 1;
      foreach ($records as $rows) {

        $nestedData["sl_no"] = $sl;
        $nestedData["user"] = $this->users_model->get_by_doc_id($rows->session_id)->email;
        $nestedData["request_uri"] = $rows->request_uri;
        $nestedData["timestamp"] = $rows->timestamp;
        $nestedData["client_ip"] = $rows->client_ip;
        $nestedData["client_user_agent"] = $rows->client_user_agent;
        $nestedData["refer_page"] = $rows->referer_page;
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
