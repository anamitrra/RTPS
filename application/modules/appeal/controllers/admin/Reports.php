<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Reports extends Rtps
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reports_model');
        $this->load->helper('role');
        $this->load->library('form_validation');
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $this->load->model('services_model');
        $this->load->model('users_model');
        //$dept_id = $this->session->userdata("department_id");
        $services = $this->services_model->all();
        $users = $this->users_model->get_users_of_role();
        $this->load->view('includes/header');
        $this->load->view('reports/total_reports', array('services' => $services, "users" => $users));
        $this->load->view('includes/footer');
    }
    public function total_reports_get_records()
    {   
        $this->load->model("reports_model");
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
        $services = $this->input->post("services", TRUE);
        $user = $this->input->post("user", TRUE);
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->reports_model->total_rows();
        $totalFiltered = $totalData;
        $temp = array();
        if ($user != null && !empty($user)) {
            $temp["user"] = $user;
        }
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
            $records = $this->reports_model->appeals_filter($limit, $start, $temp, $order, $dir);
            $totalFiltered = $this->reports_model->appeals_filter_count($temp);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->reports_model->appeals_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->reports_model->appeals_tot_search_rows($search);
        }
        $data = array();
        //pre($records);
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                //$btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $appeal = $this->reports_model->first_where("appeal_id", $rows->appeal_id);

                //$btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $btns = '<a href="' . base_url('appeal/view/' . $appeal->{'_id'}->{'$id'}) . '" class="btn btn-sm btn-info" title="Process"><i class="far fa-eye"></i> View</a> ';
                // <a href="'.base_url("users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
                // <a href="'.base_url("users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
                $nestedData["sl_no"] = $sl;
                $nestedData["appeal_id"] = strtoupper($rows->appeal_id);
                $nestedData["name"] = $rows->applicant_name;
                $nestedData["contact_no"] = ($rows->contact_in_addition_contact_number) ? $rows->additional_contact_number : $rows->contact_number;
                $nestedData["service_name"] = $rows->name_of_service;
                $nestedData["department"] = !empty($this->session->userdata("department_name")) ? $this->session->userdata("department_name") : '';
                $nestedData["appeal_date"] = format_mongo_date($rows->created_at);
                // $nestedData["date_of_application"] = format_mongo_date($rows->date_of_application,'d-m-Y');
               
                $nestedData["process_status"] = getProcessStatus($rows->process_status);
                $nestedData["action"] = $btns;
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
        //echo json_encode($json_data);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($json_data));
    }

    public function pending()
    {
        $this->load->model('services_model');
        $this->load->model('users_model');
        //$dept_id = $this->session->userdata("department_id");
        $services = $this->services_model->all();
        $users = $this->users_model->get_users_of_role();
        $this->load->view('includes/header');
        $this->load->view('reports/pending_reports', array('services' => $services, "users" => $users));
        $this->load->view('includes/footer');
    }

    public function pending_reports_get_records()
    {   
        $this->load->model("reports_model");
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
        $services = $this->input->post("services", TRUE);
        $user = $this->input->post("user", TRUE);
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->reports_model->total_rows();
        $totalFiltered = $totalData;
        $temp = array();
        if ($user != null && !empty($user)) {
            $temp["user"] = $user;
        }
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
            $records = $this->reports_model->pending_appeals_filter($limit, $start, $temp, $order, $dir);
            $totalFiltered = $this->reports_model->pending_total_rows($temp);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->reports_model->pending_appeals_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->reports_model->pending_appeals_tot_search_rows($search);
        }
        $data = array();
        //pre($records);
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                //$btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $appeal = $this->reports_model->first_where("appeal_id", $rows->appeal_id);

                //$btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $btns = '<a href="' . base_url('appeal/view/' . $appeal->{'_id'}->{'$id'}) . '" class="btn btn-sm btn-info" title="Process"><i class="far fa-eye"></i> View</a> ';
                // <a href="'.base_url("users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
                // <a href="'.base_url("users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
                $nestedData["sl_no"] = $sl;
                $nestedData["appeal_id"] = strtoupper($rows->appeal_id);
                $nestedData["name"] = $rows->applicant_name;
                $nestedData["contact_no"] = ($rows->contact_in_addition_contact_number) ? $rows->additional_contact_number : $rows->contact_number;
                $nestedData["appeal_date"] = format_mongo_date($rows->created_at);
                // $nestedData["date_of_application"] = format_mongo_date($rows->date_of_application,'d-m-Y');
               
                $nestedData["process_status"] = getProcessStatus($rows->process_status);
                $nestedData["action"] = $btns;
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
        //echo json_encode($json_data);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($json_data));
    }
    public function resolved()
    {
        $this->load->model('services_model');
        $this->load->model('users_model');
        //$dept_id = $this->session->userdata("department_id");
        $services = $this->services_model->all();
        $users = $this->users_model->get_users_of_role();
        $this->load->view('includes/header');
        $this->load->view('reports/resolved_reports', array('services' => $services, "users" => $users));
        $this->load->view('includes/footer');
    }

    public function resolved_reports_get_records()
    {   
        $this->load->model("reports_model");
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
        $services = $this->input->post("services", TRUE);
        $user = $this->input->post("user", TRUE);
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->reports_model->total_rows();
        $totalFiltered = $totalData;
        $temp = array();
        if ($user != null && !empty($user)) {
            $temp["user"] = $user;
        }
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
            $records = $this->reports_model->resolved_appeals_filter($limit, $start, $temp, $order, $dir);
            $totalFiltered = $this->reports_model->resolved_total_rows($temp);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->reports_model->resolved_appeals_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->reports_model->resolved_appeals_tot_search_rows($search);
        }
        $data = array();
        //pre($records);
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                //$btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $appeal = $this->reports_model->first_where("appeal_id", $rows->appeal_id);

                //$btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $btns = '<a href="' . base_url('appeal/view/' . $appeal->{'_id'}->{'$id'}) . '" class="btn btn-sm btn-info" title="Process"><i class="far fa-eye"></i> View</a> ';
                // <a href="'.base_url("users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
                // <a href="'.base_url("users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
                $nestedData["sl_no"] = $sl;
                $nestedData["appeal_id"] = strtoupper($rows->appeal_id);
                $nestedData["name"] = $rows->applicant_name;
                $nestedData["contact_no"] = ($rows->contact_in_addition_contact_number) ? $rows->additional_contact_number : $rows->contact_number;
                $nestedData["appeal_date"] = format_mongo_date($rows->created_at);
                // $nestedData["date_of_application"] = format_mongo_date($rows->date_of_application,'d-m-Y');
                
                $nestedData["process_status"] = getProcessStatus($rows->process_status);
                $nestedData["action"] = $btns;
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
        //echo json_encode($json_data);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($json_data));
    }
    public function rejected()
    {
        $this->load->model('services_model');
        $this->load->model('users_model');
        //$dept_id = $this->session->userdata("department_id");
        $services = $this->services_model->all();
        $users = $this->users_model->get_users_of_role();
        $this->load->view('includes/header');
        $this->load->view('reports/rejected_reports', array('services' => $services, "users" => $users));
        $this->load->view('includes/footer');
    }

    public function rejected_reports_get_records()
    {   
        $this->load->model("reports_model");
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
        $services = $this->input->post("services", TRUE);
        $user = $this->input->post("user", TRUE);
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->reports_model->total_rows();
        $totalFiltered = $totalData;
        $temp = array();
        if ($user != null && !empty($user)) {
            $temp["user"] = $user;
        }
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
            $records = $this->reports_model->rejected_appeals_filter($limit, $start, $temp, $order, $dir);
            $totalFiltered = $this->reports_model->rejected_total_rows($temp);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->reports_model->rejected_appeals_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->reports_model->rejected_appeals_tot_search_rows($search);
        }
        $data = array();
        //pre($records);
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                //$btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $appeal = $this->reports_model->first_where("appeal_id", $rows->appeal_id);

                //$btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $btns = '<a href="' . base_url('appeal/view/' . $appeal->{'_id'}->{'$id'}) . '" class="btn btn-sm btn-info" title="Process"><i class="far fa-eye"></i> View</a> ';
                // <a href="'.base_url("users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
                // <a href="'.base_url("users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
                $nestedData["sl_no"] = $sl;
                $nestedData["appeal_id"] = strtoupper($rows->appeal_id);
                $nestedData["name"] = $rows->applicant_name;
                $nestedData["contact_no"] = ($rows->contact_in_addition_contact_number) ? $rows->additional_contact_number : $rows->contact_number;
                $nestedData["appeal_date"] = format_mongo_date($rows->created_at); 
                // $nestedData["date_of_application"] = format_mongo_date($rows->date_of_application,'d-m-Y');              
                $nestedData["process_status"] = getProcessStatus($rows->process_status);
                $nestedData["action"] = $btns;
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
        //echo json_encode($json_data);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($json_data));
    }
    public function disposed_within_30()
    {
        $this->load->model('services_model');
        $this->load->model('users_model');
        //$dept_id = $this->session->userdata("department_id");
        $services = $this->services_model->all();
        $users = $this->users_model->get_users_of_role();
        $this->load->view('includes/header');
        $this->load->view('reports/disposed_within_30', array('services' => $services, "users" => $users));
        $this->load->view('includes/footer');
    }

    public function disposed_within_30_get_records()
    {   
        $this->load->model("reports_model");
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
        $services = $this->input->post("services", TRUE);
        $user = $this->input->post("user", TRUE);
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->reports_model->total_rows();
        $totalFiltered = $totalData;
        $temp = array();
        if ($user != null && !empty($user)) {
            $temp["user"] = $user;
        }
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
            $records = $this->reports_model->disposed_within_30_appeals_filter($limit, $start, $temp, $order, $dir);
            $totalFiltered = $this->reports_model->disposed_within_30_total_rows($temp);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->reports_model->disposed_within_30_appeals_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->reports_model->disposed_within_30_appeals_tot_search_rows($search);
        }
        $data = array();
        //pre($records);
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                //$btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $appeal = $this->reports_model->first_where("appeal_id", $rows->appeal_id);

                //$btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $btns = '<a href="' . base_url('appeal/view/' . $appeal->{'_id'}->{'$id'}) . '" class="btn btn-sm btn-info" title="Process"><i class="far fa-eye"></i> View</a> ';
                // <a href="'.base_url("users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
                // <a href="'.base_url("users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
                $nestedData["sl_no"] = $sl;
                $nestedData["appeal_id"] = strtoupper($rows->appeal_id);
                $nestedData["name"] = $rows->applicant_name;
                $nestedData["contact_no"] = ($rows->contact_in_addition_contact_number) ? $rows->additional_contact_number : $rows->contact_number;
                $nestedData["appeal_date"] = format_mongo_date($rows->created_at);         
                // $nestedData["date_of_application"] = format_mongo_date($rows->date_of_application,'d-m-Y');      
                $nestedData["process_status"] = getProcessStatus($rows->process_status);
                $nestedData["action"] = $btns;
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
        //echo json_encode($json_data);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($json_data));
    }
}
