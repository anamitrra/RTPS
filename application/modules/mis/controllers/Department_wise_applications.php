<?php

use MongoDB\BSON\ObjectId;

defined('BASEPATH') or exit('No direct script access allowed');
class Department_wise_applications extends Rtps
{
    private $dept_id;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('department_wise_application_model');
    }
    // department wise View
    public function index()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type' => 8));
        if ($result) {
            $val = array();
            $val['status'] = FALSE;
            $this->load->view('includes/header');
            $this->load->view('applications/department_wise', array('data'=>$result->data));
            $this->load->view('includes/footer');
        }else{
            die("No Record Found in Database");
        }
    }
    // department wise list
    public function show($deptId)
    {
        $this->load->model('department_model');
        $this->load->model('services_model');
        $departmentInfo = $this->department_model->get_department_by_id($deptId);
        // pre($departmentInfo);
        $services = $this->services_model->all();
        $data = array(
            'services' => $services,
            'departmentInfo' => $departmentInfo
        );
        $this->load->view('includes/header');
        $this->load->view('applications/department_wise_list', $data);
        $this->load->view('includes/footer');
    }
    // get records department wise
    public function get_records($dept_id)
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
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->department_wise_application_model->total_rows_dept_wise($dept_id);
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
        if (empty($this->input->post("search")["value"])) {
            $this->session->set_userdata($temp);
            $records = $this->department_wise_application_model->applications_filter($dept_id, $limit, $start, $temp, $order, $dir);
            $totalFiltered = $this->department_wise_application_model->applications_filter_count($dept_id, $temp);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->department_wise_application_model->applications_search_rows($dept_id, $limit, $start, $search, $order, $dir);
            $totalFiltered = $this->department_wise_application_model->applications_tot_search_rows($dept_id, $search);
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
                    $task = "" . $exc_data[0]->task_details->task_name . "(" . $exc_data[0]->task_details->user_name . ")";
                }
                $btns = '<a href="#!" data-appl_ref_no="' . $rows->appl_ref_no . '" data-id="' . $objs->{'_id'}->{'$id'} . '" title="View" class="modal-show"><span class="fa fa-eye" aria-hidden="true"></span></a> ';
                // <a href="'.base_url("users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
                // <a href="'.base_url("users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
                $nestedData["appl_ref_no"] = $rows->appl_ref_no;
                $nestedData["applicant_name"] = $rows->applied_by;
                $nestedData["sub_date"] = $this->mongo_db->getDateTime($rows->submission_date);
                $nestedData["sub_office"] = $rows->submission_location;
                $nestedData["curr_task"] = $task;
                $nestedData["version"] = $rows->version_no;
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
    public function dept()
    {
        $dept = $this->department_model->get_by_doc_id("5ed4c0d5666c5f7ea25b51f1");
        $data = array(
            'department' => [
                'department_id' => $dept->department_id,
                'department_name' => $dept->department_name,
                'department_short_name' => $dept->department_short_name,
                '_id' => new ObjectId($dept->{'_id'}->{'$id'}),
            ],
            'application_received' => $total,
            'application_pending' => ($total - ($deliver + $reject)),
            'application_delivered' => $deliver,
            'application_rejected' => $reject
        );
        //pre($data);
        $this->department_wise_application_model->insert($data);
        die;
        pre($data);
    }
}
