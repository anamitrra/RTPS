<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Appeal_reports extends Rtps
{
        /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('appeal_reports_model');
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
        $role = $this->session->userdata("role");
        if (!in_array($role->slug,['SA','ADMIN','RA'])) {
            redirect(base_url("appeal/login/logout"));
        }
        $this->load->model('services_model');
        $this->load->model('users_model');
        //$dept_id = $this->session->userdata("department_id");
        $services = $this->services_model->all();
        // $users = $this->users_model->get_users_of_role();
        $users = $this->users_model->get_users();
        $this->load->view('includes/header');
        $this->load->view('appeal_reports/index',array('services'=>$services,"users"=>$users));
        $this->load->view('includes/footer');
    }
    public function penalty()
    {
        $role = $this->session->userdata("role");
        if (!in_array($role->slug,['SA','ADMIN','RA'])) {
            redirect(base_url("appeal/login/logout"));
        }
        $this->load->model('services_model');
        $this->load->model('users_model');
        $services = $this->services_model->all();
        $users = $this->users_model->get_users_of_role();
        $this->load->view('includes/header');
        $this->load->view('appeal_reports/penalty_reports',array('services'=>$services,"users"=>$users));
        $this->load->view('includes/footer');
    }
    public function get_records($appealType = 'first')
    {
        $this->load->model("appeal_application_model");
        $columns = array(
            0 => "initiated_data.appl_ref_no",
            1 => "initiated_data.applied_by",
            2 => "initiated_data.submission_date",
            3 => "initiated_data.submission_location",
            5 => "initiated_data.version_no",
        );
        $limit = $this->input->post("length",TRUE);
        $start = $this->input->post("start",TRUE);
        $startDate = $this->input->post("start_date", TRUE);
        $endDate = $this->input->post("end_date", TRUE);
        $services = $this->input->post("services", TRUE);
        $appeal_type = $this->input->post("appeal_type", TRUE);
        $user = $this->input->post("user", TRUE);
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->appeal_reports_model->total_rows();
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
        $temp["appeal_type"] = intval($appeal_type) ;
        // pre($temp);
        if (empty($this->input->post("search")["value"])) {
            $this->session->set_userdata($temp);
            // $records = $this->appeal_reports_model->appeals_filter($limit, $start, $temp, $order, $dir);
            
            $records = $this->appeal_reports_model->get_all_appeals($limit, $start, $order, $dir,$temp);
            // pre($records);
            $totalFiltered = $this->appeal_reports_model->appeals_filter_count($temp);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->appeal_reports_model->appeals_search_rows($limit, $start, $search, $order, $dir,$temp);
            $totalFiltered = $this->appeal_reports_model->appeals_tot_search_rows($search);
        }
        $data = array();
        //pre($records);
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
             //   pre($rows);
                //$btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                // $appeal=$this->appeal_application_model->first_where("appeal_id",$rows->appeal_id);
            //    pre( $appeal);
                //$btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $btns = '<a href="' . base_url('appeal/view/' . $rows->{'_id'}->{'$id'}) . '" class="btn btn-sm btn-info" title="Process"><i class="far fa-eye"></i> View</a> ';
                 // <a href="'.base_url("users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
                // <a href="'.base_url("users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
                $nestedData["sl_no"] = $sl;
                $nestedData["appl_ref_no"] = $rows->appl_ref_no;
                $nestedData["appeal_id"] = strtoupper($rows->appeal_id);
                $nestedData["name"] = $rows->applicant_name;
                $nestedData["contact_no"] = ($rows->contact_in_addition_contact_number) ? $rows->additional_contact_number : $rows->contact_number;
                $nestedData["appeal_date"] = format_mongo_date($rows->created_at);
                $nestedData["district"] = $rows->district;
                // $nestedData["date_of_application"] = date("Y-m-d", $rows->date_of_application);
                $nestedData["date_of_application"] = format_mongo_date( $rows->date_of_application);
                $nestedData["appeal_type"] =$rows->appeal_type === 1 ? "First Appeal":"Second Appeal";
                $nestedData["name_of_service"] =$rows->name_of_service;
                $nestedData["location_name"] =$rows->location->location_name;
                // $nestedData["date_of_hearing"] =format_mongo_date($rows->date_of_hearing);
                switch ($rows->process_status) {
                    case 'reply':
                        $process_status = '<span class="badge badge-info">Reply</span>';
                        break;
                    case 'resolved':
                        $process_status = '<span class="badge badge-success">Resolved</span>';
                        break;
                    case 'remark':
                        $process_status = '<span class="badge badge-warning">Remark</span>';
                        break;
                    case 'penalize':
                        $process_status = '<span class="badge badge-primary">Penalized</span>';
                        break;
                    case 'forward':
                        $process_status = '<span class="badge badge-secondary">Forwarded</span>';
                        break;
                    case 'in-progress':
                        $process_status = '<span class="badge badge-wrapper">In Progress</span>';
                        break;  
                    case '':
                        $process_status = '<span class="badge badge-secondary">Initiated</span>';
                        break;
                    default:
                        $process_status = '<span class="badge badge-secondary">'.$rows->process_status.'</span>';
                        break;
                }
                $nestedData["process_status"] = $process_status;
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
    public function get_penalty_records($appealType = 'first')
    {
        $this->load->model("penalty_reports_model");
        $this->load->model("appeal_application_model");
    
        $columns = array(
            0 => "initiated_data.appl_ref_no",
            1 => "initiated_data.applied_by",
            2 => "initiated_data.submission_date",
            3 => "initiated_data.submission_location",
            5 => "initiated_data.version_no",
        );
        $limit = $this->input->post("length",TRUE);
        $start = $this->input->post("start",TRUE);
        $startDate = $this->input->post("start_date", TRUE);
        $endDate = $this->input->post("end_date", TRUE);
        $services = $this->input->post("services", TRUE);
        $user = $this->input->post("user", TRUE);
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->penalty_reports_model->total_rows();
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
            $records = $this->penalty_reports_model->penalty_filter($limit, $start, $temp, $order, $dir);
            $totalFiltered = $this->penalty_reports_model->appeals_filter_count($temp);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->penalty_reports_model->appeals_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->penalty_reports_model->appeals_tot_search_rows($search);
        }
        $data = array();
        //pre($records);
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                //pre($records);
                $appeal=$this->appeal_application_model->first_where("appeal_id",$rows->appeal_id);
                
                //$btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $btns = '<a href="' . base_url('appeal/view/' . $appeal->{'_id'}->{'$id'}) . '" class="btn btn-sm btn-info" title="Process"><i class="far fa-eye"></i> View</a> ';
                // <a href="'.base_url("users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
                // <a href="'.base_url("users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
                $nestedData["sl_no"] = $sl;
                $nestedData["appeal_id"] = strtoupper($rows->appeal_id);
                $nestedData["immposed_to"] = $rows->penalty_to_user->name;
                $nestedData["immposed_by"] = $rows->action_taken_by->name;
                $nestedData["amount"] = ($rows->penalty_amount) ? $rows->penalty_amount :"N/A";
                $nestedData["date"] = format_mongo_date($rows->created_at);
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
    public function excel_export($appeal_type="first")
    {    $temp = array();
        if ($this->session->has_userdata("startDate") || $this->session->has_userdata("endDate") || $this->session->userdata("services")) {
          if ($this->session->has_userdata("startDate") && $this->session->has_userdata("endDate")) {
            $temp["startDate"] = $this->session->userdata("startDate");
            $temp["endDate"] = $this->session->userdata("endDate");
          }
          if ($this->session->has_userdata("services")) {
            $temp["services"] = $this->session->userdata("services");
          }
          //pre($temp);
          $records = $this->appeal_reports_model->applications_filter(200000, 0, $temp, "appl_ref_no", "DESC");
        } else {
          $records = $this->appeal_reports_model->all_rows(200000, 0, "initiated_data.appl_ref_no", "DESC");
        }

        if ($appeal_type == 'second') {
            // fetch second appeal data
            $filter['ref_appeal_id'] = array('$exists' => true);
            $appealApplicationList = $this->appeal_reports_model->get_where($filter);
        } else {
            // fetch first appeal data
            $filter['ref_appeal_id'] = array('$exists' => false);
            $appealApplicationList = $this->appeal_reports_model->get_where($filter);
        }
        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        try {
            $objPHPExcel->setActiveSheetIndex(0);
            // set Header
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Appeal ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Applicant Name');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Contact Number');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Appeal Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Process Status');
            // set Row
            $rowCount = 2;
            foreach ($appealApplicationList as $appealApplication) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $appealApplication->appeal_id);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $appealApplication->applicant_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $appealApplication->contact_number);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $appealApplication->date_of_appeal);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $appealApplication->process_status);
                $rowCount++;
            }
            $filename = "appeal_applications" . date("Y-m-d-H-i-s") . ".csv";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
            $objWriter->save('php://output');
        } catch (PHPExcel_Exception $e) {
            $this->session->set_flashdata('fail', 'Failed to generate excel. Please try again.');
            if ($appeal_type == 'second') {
                redirect(base_url('appeal/list'));
            } else {
                redirect(base_url('appeal/list/second'));
            }
        }
        exit(200);
    }
    public function excel_export_penalty_reports($appeal_type="first")
    {    $temp = array();
        if ($this->session->has_userdata("startDate") || $this->session->has_userdata("endDate") || $this->session->userdata("services")) {
          if ($this->session->has_userdata("startDate") && $this->session->has_userdata("endDate")) {
            $temp["startDate"] = $this->session->userdata("startDate");
            $temp["endDate"] = $this->session->userdata("endDate");
          }
          if ($this->session->has_userdata("services")) {
            $temp["services"] = $this->session->userdata("services");
          }
          //pre($temp);
          $records = $this->appeal_reports_model->applications_filter(200000, 0, $temp, "appl_ref_no", "DESC");
        } else {
          $records = $this->appeal_reports_model->all_rows(200000, 0, "initiated_data.appl_ref_no", "DESC");
        }

        if ($appeal_type == 'second') {
            // fetch second appeal data
            $filter['ref_appeal_id'] = array('$exists' => true);
            $appealApplicationList = $this->appeal_reports_model->get_where($filter);
        } else {
            // fetch first appeal data
            $filter['ref_appeal_id'] = array('$exists' => false);
            $appealApplicationList = $this->appeal_reports_model->get_where($filter);
        }
        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        try {
            $objPHPExcel->setActiveSheetIndex(0);
            // set Header
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Appeal ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Applicant Name');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Contact Number');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Appeal Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Process Status');
            // set Row
            $rowCount = 2;
            foreach ($appealApplicationList as $appealApplication) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $appealApplication->appeal_id);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $appealApplication->applicant_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $appealApplication->contact_number);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $appealApplication->date_of_appeal);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $appealApplication->process_status);
                $rowCount++;
            }
            $filename = "appeal_applications" . date("Y-m-d-H-i-s") . ".csv";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
            $objWriter->save('php://output');
        } catch (PHPExcel_Exception $e) {
            $this->session->set_flashdata('fail', 'Failed to generate excel. Please try again.');
            if ($appeal_type == 'second') {
                redirect(base_url('appeal/list'));
            } else {
                redirect(base_url('appeal/list/second'));
            }
        }
        exit(200);
    }
}