<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Applications extends Rtps{

    public function __construct()
    {
        parent::__construct();
          $this->load->model('applications_model');
    }

    public function applications()
    {
      // var_dump($this->session->userdata());die;
        $this->isloggedin();
        $this->load->view("includes/header", array("pageTitle" => "Dashboard | Applications"));
        $this->load->view("userarea/applications_list");
        $this->load->view("includes/footer");
    }

    public function applications_get_records()
    {
        $this->isloggedin();

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
    //   pre($this->input->post("order")[0]);
        if(!empty($this->input->post("order")[0]) && is_numeric($this->input->post("order")[0])){
            $order = $columns[$this->input->post("order")[0]["column"]];
        }else{
            $order = 0;
        }
    
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->applications_model->all_applications_filter_count();
//        $maxPage = ceil($totalData/(strval($this->input->post('length')) - strval($this->input->post('start'))));
        $maxPage = ceil($totalData/strval($this->input->post('length')));

        if($this->input->post('draw') <1 || $this->input->post('draw') > $maxPage){
            redirect('appeal/login/logout');
        }
        $totalFiltered = $totalData;
        if (empty($this->input->post("search")["value"])) {
            $records = $this->applications_model->all_applications_filter($limit, $start, $order, $dir);
            $totalFiltered = $this->applications_model->all_applications_filter_count();
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
                $task = "Not Available";
                if (isset($exc_data[0])) {
                    $task = "" . $exc_data[0]->task_details->task_name . "(" . $exc_data[0]->task_details->user_name . ")";
                }
                $this->load->model('appeal_application_model');
                $obj = $this->appeal_application_model->get_by_appl_ref_no($rows->appl_ref_no);
                $btns = 'NA';
                if (!isset($obj) && empty($obj)) {
                    $baseUrl = base_url('appeal/apply/?id=' . $rows->appl_ref_no);

                    $this->load->config('first_appeal');
                    $processTime = $this->config->item('first_appeal_processing_time');
                    $lowerBound = $this->config->item('fap_processing_time');
                    $upperBound = $this->config->item('fap_processing_time')+$this->config->item('fap_extension_time');
                    $infoText = 'First Appeal can be applied if the application is not processed for '.$processTime.' days from the date of submission. The appeal is under considered if an appeal is applied beyond '.$lowerBound
                        .' days, but not before '.$upperBound.' days from the date of submission of the application.';
                    if(isset($this->session->userdata('role')->slug)){
                        $baseUrl = base_url('appeal/first/apply/others/?id='. $rows->appl_ref_no);
                    }
                    $btns = '<a href="' . $baseUrl . '" title="'.$infoText.'" data-toggle="tooltip" class="btn btn-sm btn-primary">Apply For appeal</a> ';
                }
                // $btns = '<a href="'.base_url('appeal/first/apply/others/?id='.$rows->appl_ref_no).'" title="Apply For appeal" class="btn btn-sm btn-primary">Apply For appeal</a> ';
                $nestedData["appl_ref_no"] = $rows->appl_ref_no;
                $nestedData["applicant_name"] = $rows->attribute_details->applicant_name;
                $nestedData["sub_date"] = $this->mongo_db->getDateTime($rows->submission_date);
                $nestedData["sub_office"] = $rows->submission_location;
                $nestedData["curr_task"] = $task;
                $nestedData["version"] = $rows->version_no;
                $nestedData["action"] = $btns;
                $data[] = $nestedData;
            }
        }
//        pre($this->input->post());
        $json_data = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($json_data));
    }

    public function my_appeals(){
      $this->isloggedin();
      $this->load->view("includes/header", array("pageTitle" => "Dashboard | My Appeals"));
      $this->load->view("userarea/appeal_list");
      $this->load->view("includes/footer");
    }

    public function my_appeals_get_records()
    {
        $this->isloggedin();
        $this->load->model('ams_model');
        $columns = array(
            0 => "initiated_data.appl_ref_no",
            1 => "initiated_data.applied_by",
            2 => "initiated_data.submission_date",
            3 => "initiated_data.submission_location",
            5 => "initiated_data.version_no",
        );
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->applications_model->my_appeals_count();//$this->ams_model->total_rows();\

        $totalFiltered = $totalData;
        $filter = array();
        if (empty($this->input->post("search")["value"])) {
            $this->session->set_userdata($filter);
            $records = $this->applications_model->my_appeals_filter($limit, $start, $filter, $order, $dir);
            $totalFiltered = $this->applications_model->appeals_filter_count();
            // $records = $this->applications_model->all_applications_filter($limit, $start, $order, $dir);
            // $totalFiltered = $this->applications_model->all_applications_filter_count();
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->applications_model->appeals_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->applications_model->appeals_tot_search_rows($search);
        }
        $data = array();
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                if(!$rows->appeal_expiry_status){
                    $btns = '';
                    $this->config->load('second_appeal');
                    $dateOfAppeal = date_create(format_mongo_date($rows->created_at,'d-m-Y'));
                    $dateDiff = date_diff($dateOfAppeal,date_create())->days;
                    $hasProcessTimeExpired = ($dateDiff >= $this->config->item('first_appeal_processing_time'));
                    if(in_array($rows->process_status,['resolved','rejected']) || $hasProcessTimeExpired){
                        if(!property_exists($rows,'second_appeal_applied')){
                            if(in_array($this->session->userdata('role')->slug,['PFC','DA'])){
                                $btns = '<a href="' . base_url('appeal/second/apply/other/?appeal_no=' . $rows->appeal_id) . '" class="btn btn-outline-primary btn-sm  float-right" title="Process">Apply Second Appeal</a> ';
                            }else {
                                $btns = '<a href="' . base_url('appeal/second/apply/?appeal_no=' . $rows->appeal_id) . '" class="btn btn-outline-primary btn-sm  float-right" title="Process">Apply Second Appeal</a> ';

//                      $btns = '<a href="' . base_url('appeal/process/view/' . $rows->{'_id'}->{'$id'}) . '" class="btn btn-sm btn-info" title="Process">Process</a> ';
                            }
                        }
                    }
                    $btns .= '<a href="' . base_url('appeal/track/?appeal_no=' . $rows->appeal_id) . '" class="btn btn-sm btn-outline-warning float-right" title="Track">Track Appeal</a>';
                    //$btns = '<a href="' . base_url('appeal/second/apply/?appeal_no=' . $rows->appeal_id) . '" class="btn btn-outline-primary btn-sm  float-right" title="Process">Apply Second Appeal</a> ';
                    //$btns .= '<a href="' . base_url('appeal/track/?appeal_no=' . $rows->appeal_id) . '" class="btn btn-outline-warning btn-sm  float-right" title="Process">Track Appeal</a> ';
                    }else{
                        $btns='Delayed Appeal';
                    }
                $nestedData["sl_no"] = $sl;
                $nestedData["appeal_type"] = $rows->appeal_type == 1 ? "First Appeal":"Second Appeal";
                $nestedData["appeal_id"] = strtoupper($rows->appeal_id);
                $nestedData["name"] = $rows->applicant_name;
                $nestedData["contact_no"] = ($rows->contact_in_addition_contact_number) ? $rows->additional_contact_number : $rows->contact_number;
                $nestedData["appeal_date"] = format_mongo_date($rows->created_at);
                switch ($rows->process_status) {
                    case 'reply':
                        $process_status = '<span class="badge badge-info">Reply</span>';
                        break;
                    case 'forward-to-aa':
                    case 'second-appeal-forward-to-aa':
                        $process_status = '<span class="badge badge-secondary">Forwarded to AA</span>';
                        break;
                    case 'second-appeal-forward-to-registrar':
                        $process_status = '<span class="badge badge-secondary">Forwarded To Registrar</span>';
                        break;
                    case 'second-appeal-forward-to-moc':
                        $process_status = '<span class="badge badge-secondary">Forwarded To Member of Commission</span>';
                        break;
                    case 'second-appeal-forward-to-chairman':
                        $process_status = '<span class="badge badge-secondary">Forwarded To Chairman</span>';
                        break;
                    case 'revert-back-to-da':
                    case 'second-appeal-revert-back-to-da':
                        $process_status = '<span class="badge badge-info">Reverted to DA</span>';
                        break;
                    case 'second-appeal-revert-back-to-rr':
                        $process_status = '<span class="badge badge-info">Reverted to Registrar</span>';
                        break;
                    case 'generate-disposal-order':
                        $process_status = '<span class="badge badge-success">Disposal Order Generated</span>';
                        break;
                    case 'upload-disposal-order':
                    case 'second-appeal-upload-hearing-order':
                        $process_status = '<span class="badge badge-success">Disposal Order Uploaded</span>';
                        break;
                    case 'second-appeal-approve-disposal-order':
                        $process_status = '<span class="badge badge-success">Disposal Order Approved</span>';
                        break;
                    case 'resolved':
                    case 'second-appeal-issue-disposal-order':
                        $process_status = '<span class="badge badge-success">Resolved</span>';
                        break;
                    case 'generate-rejection-order':
                        $process_status = '<span class="badge badge-danger">Rejection Order Generated</span>';
                        break;
                    case 'upload-rejection-order':
                        $process_status = '<span class="badge badge-danger">Rejection Order Uploaded</span>';
                        break;
                    case 'second-appeal-approve-rejection-order':
                        $process_status = '<span class="badge badge-success">Rejection Order Approved</span>';
                        break;
                    case 'rejected':
                    case 'second-appeal-issue-rejection-order':
                        $process_status = '<span class="badge badge-danger">Rejected</span>';
                        break;
                    case 'penalize':
                        $process_status = '<span class="badge badge-primary">Penalized</span>';
                        break;
                    case 'generate-penalty-order':
                        $process_status = '<span class="badge badge-warning">Penalty Order Generated</span>';
                        break;
                    case 'upload-penalty-order':
                        $process_status = '<span class="badge badge-warning">Penalty Order Uploaded</span>';
                        break;
                    case 'approve-penalty-order':
                        $process_status = '<span class="badge badge-info">Penalty Order Approved</span>';
                        break;
                    case 'reassign':
                        $process_status = '<span class="badge badge-info">Reassigned</span>';
                        break;
                    case 'remark':
                        $process_status = '<span class="badge badge-warning">Remark</span>';
                        break;
                    case 'in-progress':
                        $process_status = '<span class="badge badge-dark">In Progress</span>';
                        break;
                    case 'provide-hearing-date':
                        $process_status = '<span class="badge badge-info">Hearing Date Provided</span>';
                        break;
                    case 'second-appeal-change-hearing-date':
                        $process_status = '<span class="badge badge-info">Hearing Date Changed</span>';
                        break;
                    case 'second-appeal-confirm-hearing-date':
                        $process_status = '<span class="badge badge-info">Hearing Date Confirmed</span>';
                        break;
                    case 'generate-hearing-order':
                        $process_status = '<span class="badge badge-warning">Hearing Order Generated</span>';
                        break;
                    case 'upload-hearing-order':
                        $process_status = '<span class="badge badge-warning">Hearing Order Uploaded</span>';
                        break;
                    case 'approve-hearing-order':
                    case 'second-appeal-approve-hearing-order':
                        $process_status = '<span class="badge badge-info">Hearing Order Approved</span>';
                        break;
                    case 'second-appeal-issue-hearing-order':
                        $process_status = '<span class="badge badge-success">Hearing Order Issued</span>';
                        break;
                    case 'second-appeal-create-bench':
                        $process_status = '<span class="badge badge-warning">Bench Formed</span>';
                        break;
                    case 'second-appeal-seek-info':
                    case 'seek-info':
                        $process_status = '<span class="badge badge-primary">Seeking Info</span>';
                        break;
                    case 'issue-order':
                        $process_status = '<span class="badge badge-info">Order Issued</span>';
                        $generatedMsgPreText = 'Order Issued to ';
                        break;
                    case 'dps-reply':
                        $process_status = '<span class="badge badge-info">DPS replied</span>';
                        break;
                    case 'appellate-reply':
                        $process_status = '<span class="badge badge-info">Appellate Authority replied</span>';
                        break;
                    default:
                        $process_status = '<span class="badge badge-secondary">initiated</span>';
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
}
