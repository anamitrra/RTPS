<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Ams extends frontend
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('role');
        $this->load->library('form_validation');
        $this->load->model('users_model');
    }
    public function isloggedin()
    {
        $otp_status = $this->session->userdata('opt_status');
        $mobile = $this->session->userdata('mobile');
        if (!isset($otp_status) && $otp_status != TRUE) {
            if (!isset($mobile) && $mobile != TRUE) {
                redirect(base_url("appeal/login"));
            }
        }
    }
    // welcome page or page before login
    /**
     * index
     *
     * @return void
     */
    public function login()
    {
        // pre("hdhd");
        $this->load->helper('captcha');
        $cap = generate_n_store_captcha();
        $data = [
            'cap' => $cap
        ];
        $this->load->view('includes/frontend/header');
        $this->load->view('ams/login', $data);
        $this->load->view('includes/frontend/footer');
    }
    /**
     * generate_otp
     *
     * @param mixed $length
     * @return void
     */
    private function generate_otp($length = 6)
    {
        $otp = "";
        $numbers = "0123456789";
        for ($i = 0; $i < $length; $i++) {
            $otp .= $numbers[rand(0, strlen($numbers) - 1)];
        }
        return $otp;
    }
    /**
     * count_appeals
     *
     * @return void
     */
    public function count_appeals()
    {
        $total = $this->appeals_model->total_rows();
        $new = $this->appeals_model->tot_search_rows(array(
            'process_status' => null
        ));
        $resolved = $this->appeals_model->tot_search_rows(array(
            'process_status' => "resolved"
        ));
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'total' => $total ?? 0,
                'new' => $new,
                'processed' => $total - ($new + $resolved),
                'resolved' => $resolved,
            )));
    }
    public function application_month_wise()
    {
        $application_month_wise = array();
        $months = array_reduce(range(1, 12), function ($rslt, $m) {
            $rslt[$m] = date('F', mktime(0, 0, 0, $m, 10));
            return $rslt;
        });
        $collection = "appeal_applications";
        foreach ($months as $num => $month) {
            $operations = array(
                array(
                    '$project' => array(
                        "month" => array("\$month" => "\$created_at")
                    )
                ),
                array(
                    '$match' => array(
                        "month" => $num
                    )
                ),
                array(
                    '$count' => "doc_nos"
                )
            );
            $data_total = $this->mongo_db->aggregate($collection, $operations);
            if (isset($data_total->{'0'})) {
                array_push($application_month_wise, array($month . '(' . $data_total->{'0'}->doc_nos . ')', $data_total->{'0'}->doc_nos, $data_total->{'0'}->doc_nos));
            } else {
                array_push($application_month_wise, array($month . '(0)', 0, 0));
            }
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'application_month_wise' => $application_month_wise
            )));
    }
    /**
     * send_otp
     *
     * @return void
     */
    public function send_otp()
    {
        $status = array();
        $this->load->helper('captcha');
        $validatedCaptcha = validate_captcha();
        if (!$validatedCaptcha['status']) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($validatedCaptcha));
        } else {
            $mobile = strval($this->input->post('contactNumber', true));
            if (isset($mobile) && $mobile != '') {
                $this->load->config('sms_template');
                $msgTemplate = $this->config->item('otp');
//                pre($msgTemplate);
//                $msg = "Your Otp is for new appeal is {{otp}}";
                $this->sms->send_otp($mobile, $mobile, $msgTemplate);
                $status["status"] = true;
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($status));
            } else {
                $status["status"] = false;
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($status));
            }
        }
    }
    /**
     * process_appeal_login
     *
     * @return void
     */
    public function process_appeal_login()
    {
        //$ref_no = $this->input->post("applicationNumber", TRUE);
        $mobile = $this->input->post("contactNumber", TRUE);
        $otp = $this->input->post("otp", TRUE);
        $res = $this->sms->verify_otp($mobile, $mobile, $otp);

        if (!$res['status']) {
            $status["status"] = false;
            $status["msg"] = $res['msg'];
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($status));
        } else {
            $this->session->set_userdata("opt_status", TRUE);
            $this->session->set_userdata("mobile", $mobile);
            $status["status"] = true;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($status));
        }
    }
    /**
     * dashboard
     *
     * @return void
     */
    public function dashboard()
    {
        $this->isloggedin();
        $data = [];
        $this->load->view("userarea/includes/header", array("pageTitle" => "Dashboard"));
        $this->load->view("userarea/dashboard", $data);
        $this->load->view("userarea/includes/footer");
    }
    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        $this->session->unset_userdata('opt_status');
        $this->session->unset_userdata('mobile');
        $this->session->sess_destroy();
        redirect(base_url('appeal/login'));
    }
    public function downloadsign($process_id){

        $this->isloggedin();
        if($process_id){
            $process= $this->mongo_db->where(array('_id'=>new ObjectId($process_id)))->find_one("appeal_processes");
            // Decode base64 string
            $pdf_data = base64_decode($process->documents);
            $dir='storage/DOCUMENTS/tempsign/';
            $output_directory = FCPATH . $dir;
            $output_filename = uniqid() . '.pdf';
            $output_path = $output_directory . $output_filename;

            // Create the output directory if it doesn't exist
            if (!file_exists($output_directory)) {
                mkdir($output_directory);
            }

            // Write the decoded data to a file
            file_put_contents($output_path, $pdf_data);
            // Optionally, you can also force download the PDF file
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="'.$output_filename.'"');
            header('Content-Length: ' . filesize($output_path));
            readfile($output_path);
            unlink($output_path);
        }
    }

    public function myappeals()
    {
        $this->isloggedin();
        $this->load->view("userarea/includes/header", array("pageTitle" => "Dashboard | My Appeals"));
        $this->load->view("userarea/my_appeal_list");
        $this->load->view("userarea/includes/footer");
    }
    public function myappeals_get_records()
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
        $totalData = $this->ams_model->total_rows();
        $totalFiltered = $totalData;
        $filter = array();
        if (empty($this->input->post("search")["value"])) {
            $this->session->set_userdata($filter);
            $records = $this->ams_model->appeals_filter($limit, $start, $filter, $order, $dir);
            $totalFiltered = $this->ams_model->appeals_filter_count();
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->ams_model->appeals_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->ams_model->appeals_tot_search_rows($search);
        }
        $data = array();
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                if(!$rows->appeal_expiry_status){
                    $btns = '<a href="' . base_url('appeal/track/?appeal_no=' . $rows->appeal_id) . '" class="btn btn-outline-warning btn-sm  float-right" title="Process">Track Appeal</a>';
                  if($rows->appeal_type != 2){
                    $this->config->load('second_appeal');
                    $dateOfAppeal = date_create(format_mongo_date($rows->created_at,'d-m-Y'));
                    $dateDiff = date_diff($dateOfAppeal,date_create())->days;
                    $hasProcessTimeExpired = ($dateDiff >= $this->config->item('first_appeal_processing_time'));
                    if((in_array($rows->process_status,['resolved','rejected']) || $hasProcessTimeExpired) && !property_exists($rows,'second_appeal_applied')) {
                        $this->load->config('second_appeal');
                        $processTime = $this->config->item('first_appeal_processing_time');
                        $lowerBound = $this->config->item('sap_relaxation_time');
                        $upperBound = $this->config->item('sap_relaxation_time')+$this->config->item('sap_extension_time');
                        $infoText = 'Second Appeal can be applied if first appeal is not processed for '.$processTime.' days from the date of submission of first appeal. The appeal is under considered if an appeal is applied beyond '.$lowerBound
                            .' days, but not before '.$upperBound.' days from the date of submission of first appeal.';
                        $btns = '<a href="' . base_url('appeal/second/apply/?appeal_no=' . $rows->appeal_id) . '" class="btn btn-outline-primary btn-sm  float-right" data-toggle="tooltip" title="'.$infoText.'">Apply Second Appeal</a> ';
                        $btns .= '<a href="' . base_url('appeal/track/?appeal_no=' . $rows->appeal_id) . '" class="btn btn-outline-warning btn-sm  float-right" title="Process">Track Appeal</a> ';
                    }
                  }

                }else{
                    $btns='Expired Appeal';
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
    public function myapplications()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        $this->isloggedin();
        $this->load->view("userarea/includes/header", array("pageTitle" => "Dashboard | My Appeals"));
        $this->load->view("userarea/my_applications_list");
        $this->load->view("userarea/includes/footer");
    }
    public function myapplications_get_records()
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
        $limit = $this->input->post("length", TRUE);
        $start = $this->input->post("start", TRUE);
        $startDate = $this->input->post("start_date", TRUE);
        $endDate = $this->input->post("end_date", TRUE);
        $service_status = $this->input->post("service_status", TRUE);
        $services = $this->input->post("services", TRUE);
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->ams_model->applications_filter_count();

        $totalFiltered = $totalData;
        if (empty($this->input->post("search")["value"])) {
            $records = $this->ams_model->applications_filter($limit, $start, $order, $dir);
            $totalFiltered = $this->ams_model->applications_filter_count();
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->ams_model->applications_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->ams_model->applications_tot_search_rows($search);
        }
        $data = array();
        //print_r($records);
        if (!empty($records)) {
            foreach ($records as $objs) {
                //echo $objs->{'_id'}->{'$id'};
                $rows = $objs->initiated_data;
                $exc_data = $objs->execution_data;
                $task = "Not Available";
                if (isset($exc_data[0]) && isset($exc_data[0]->task_details->user_name)) {
                    $task = "" . $exc_data[0]->task_details->task_name . "(" . $exc_data[0]->task_details->user_name . ")";
                }
                $this->load->model('appeal_application_model');
                $obj = $this->appeal_application_model->get_by_appl_ref_no($rows->appl_ref_no);
                $btns = 'NA';
                if (!isset($obj) && empty($obj)) {
                    // $btns = '<a href="' . base_url('appeal/ams/preview?id=' . $rows->appl_ref_no) . '" title="View Application" class="btn btn-sm btn-primary">View</a> ';
                    $btns = '<a href="' . base_url('appeal/apply/?id=' . $rows->appl_ref_no) . '" title="Apply For appeal" class="btn btn-sm btn-primary">Apply For appeal</a> ';
                }

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
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($json_data));
    }
    public function check_if_timeline_expired()
    {

        $this->load->model('appeals_model');
        $ref_no = strval($this->input->get('id'));
        $mobile = strval($this->session->userdata('mobile'));
        $value = $this->appeals_model->search($ref_no, $mobile);
        $status = array();
        $status["service_timeline"] = isset($value->service_timeline) ? $value->service_timeline : 0;
        //pre($value);
        if ($value && is_object($value)) {
            if (isset($value->timeline_stat_45) && !empty($value->timeline_stat_45) && $value->timeline_stat_45) {
                if (isset($value->timeline_stat_30) && !empty($value->timeline_stat_30) && $value->timeline_stat_30) {
                    $this->session->set_userdata("appeal_after_30", FALSE);
                } else {
                    $this->session->set_userdata("appeal_after_30", TRUE);
                }
                $status["status"] = true;
            } else {
                $status["status"] = false;
                $status["error_msg"] = "Your application timeline for appeal submission is expired.";
            }
        } else {
            $status["status"] = false;
            $status["error_msg"] = "Application Ref No and Mobile No Does not match any record.";
        }
        return $status;
    }
    private function checkTimeLine($ref_no)
    {

        $this->load->model('appeals_model');
        $value = $this->appeals_model->search($ref_no);
        $data = array(
            'timeline2_expired' => false,
            'timeline1_expired' => false,
        );
        //  pre($value);
        if ($value && is_object($value)) {
            if ($value->Reject) {
                // Rejected
                if ($value->rejected_before_service_timeline) {
                    $data['timeline1_expired'] = true;
                }
            } else {
                // Not Rejected
                if ($value->timeline_1_expired) {
                    $data['timeline1_expired'] = true;
                }
                if ($value->timeline_2_expired) {
                    $data['timeline2_expired'] = true;
                }
            }
            $this->session->set_userdata('timeline1_expired', $data['timeline1_expired']);
            $this->session->set_userdata('timeline2_expired', $data['timeline2_expired']);

            if (isset($data['timeline2_expired']) && $data['timeline2_expired']) {
                // cannot apply
                $data["status"] = false;
                $data['isReasonRequired'] = false;
                $data["error_msg"] = "Your application timeline for appeal submission is expired.";
            } elseif (isset($data['timeline1_expired']) && $data['timeline1_expired']) {
                // apply with reason
                $data['isReasonRequired'] = true;
                $data["status"] = true;
                $data["error_msg"] = "Your application timeline for appeal submission is expired.But is under consideration.";
            } else {
                //can apply
                $data['isReasonRequired'] = false;
                $data["status"] = true;
                $data["error_msg"] = "";
            }
        } else {
            $data['isReasonRequired'] = false;
            $data["status"] = false;
            $data["error_msg"] = "Application Ref No Does not match any record.";
        }

        return $data;
    }
    /**
     * apply_for_appeal
     *
     * @return void
     */
    public function apply_for_appeal()
    {
        $this->load->model('official_details_model');
        $relatedOfficialsFilter = array(
            'service_id'  => new ObjectId('5efd6e2c715354482c4d67d0'),
            'location_id' =>  new ObjectId('5f6841091b172967105d7f28'),
        );
      //  pre($this->official_details_model->get_with_permission_array($relatedOfficialsFilter));
        $ref_no = $this->input->get('id');
        if(!isset($ref_no)){
            show_404();
            exit();
        }
        $this->load->model('applications_model');
        $this->load->model('services_model');
        $this->load->model('location_model');
        $this->load->model('official_details_model');
        $this->load->model('ams_model');
        $this->load->model('users_model');

        $status = $this->checkTimeLine($ref_no);
        if ($status['timeline2_expired']) {

            show_error($status['error_msg'], 403, 'Something Went Wrong!');
        } else {
            if ($status['isReasonRequired']) {
                $this->session->set_userdata("appeal_after_30", TRUE);
            } else {
                $this->session->set_userdata("appeal_after_30", FALSE);
            }
        }

        $applicationData = $this->applications_model->get_by_appl_ref_no($ref_no);
        $submission_date = date('d-m-Y', strtotime($this->mongo_db->getDateTime($applicationData->initiated_data->submission_date)));

        try {
            $service  = $this->services_model->first_where(array('service_id' => $applicationData->initiated_data->base_service_id));
            $location = $this->location_model->first_where(array('location_name' => "" . $applicationData->initiated_data->submission_location . ""));
            //pre($applicationData->initiated_data->submission_location);
            if (!$service || !$location) {
                throw new Exception();
            }
        } catch (Exception $exception) {
            log_message('error', $exception);
            show_error('No record available for the service.', 403, 'Service Not Found');
            exit(404);
        }

        $relatedOfficialsFilter = array(
            'service_id'  => new ObjectId($service->{'_id'}->{'$id'}),
            'location_id' =>  new ObjectId($location->{'_id'}->{'$id'}),
        );
        $relatedOfficials = $this->official_details_model->first_where($relatedOfficialsFilter);
        if (!$relatedOfficials) {
            show_error('No Officials are mapped for this service.', 500);
        }
        //        pre($relatedOfficialsFilter);
        $appealApplication = $this->ams_model->get_by_appl_ref_no($ref_no);
        if (isset($appealApplication)) {
            show_error("You have already applied for appeal of the application. you can not apply appeal for same application", 403, "Error");
        }
        //$applicationData = $this->applications_model->get_by_appl_ref_no($ref_no);
        //$userIdArray = array(convertToMongoObId($relatedOfficials->dps_id), convertToMongoObId($relatedOfficials->appellate_id));
        $dps = $this->users_model->get_by_doc_id($relatedOfficials->dps_id);
        $appalete = $this->users_model->get_by_doc_id($relatedOfficials->appellate_id);
        $this->load->helper('captcha');
        $cap = generate_n_store_captcha();
        $data = [
            'applicationData' => $applicationData,
            'dps' => $dps,
            'appalete' => $appalete,
            'cap' => $cap,
            'isReasonRequired' => $status['isReasonRequired']
        ];
        $this->load->view('userarea/includes/header', array("pageTitle" => "Dashboard | Apply For Appeal"));
        $this->load->view('userarea/apply_for_appeal', $data);
        $this->load->view('userarea/includes/footer');
    }
    /**
     * process
     *
     * @return void
     */
    public function process()
    {
        $this->load->model('ams_model');
        $this->load->model('applications_model');
        $this->load->model('services_model');
        $this->load->model('location_model');
        $this->load->model('official_details_model');
        $this->load->model('users_model');
        if ($this->input->method(true) !== 'POST') {
            show_error('Method Not Allowed', '403', 'Invalid Method');
        }
        if (!$this->session->userdata('opt_status')) {
            redirect(base_url('appeal/login'));
        }
        //        $this->load->helper('captcha');
        //        $this->form_validation->set_rules('captcha', 'Captcha', 'validate_captcha|trim');
        $this->form_validation->set_rules('additionalContactNumber', 'Additional Contact Number', 'trim');
        $this->form_validation->set_rules('additionalEmailId', 'Additional Email ID', 'trim');
        // $this->form_validation->set_rules('addressOfThePerson', 'Address of the Appellant', 'trim|required');
        $this->form_validation->set_rules('groundForAppeal', 'Ground for appeal', 'trim|required');
        $this->form_validation->set_rules('appealDescription', 'Appeal Description/ Relief sought for', 'trim|required');
        $this->form_validation->set_rules('gender', 'Gender', 'required|trim');
        $this->form_validation->set_rules('village', 'Village', 'required|trim');
        $this->form_validation->set_rules('gender', 'Gender', 'required|trim');
        $this->form_validation->set_rules('district', 'District', 'required|trim');
        $this->form_validation->set_rules('policestation', 'Police Station', 'required|trim');
        $this->form_validation->set_rules('circle', 'Circle', 'trim');
        $this->form_validation->set_rules('postoffice', 'Post Office', 'required|trim');
        $this->form_validation->set_rules('pincode', 'Pincode', 'required|trim');

        if ($this->input->post('ReasonRequired')) {
            $this->form_validation->set_rules('appellateReasonFordelay', 'Describing the Reason for delay', 'required|trim');
        }
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("error", validation_errors());
            $this->session->set_flashdata("old", $this->input->post());
            redirect('appeals/apply');
        }
        $app_ref_no = $this->input->post('applicationNumber', true);
        $appealApplication = $this->ams_model->get_by_appl_ref_no($app_ref_no);
        if (isset($appealApplication)) {
            redirect(base_url('appeal/preview-n-track'));
        }
        $applicationData = $this->applications_model->get_by_appl_ref_no($app_ref_no);
        $service  = $this->services_model->first_where(array('service_id' => $applicationData->initiated_data->base_service_id));
        $location = $this->location_model->first_where(array('location_name' => $applicationData->initiated_data->submission_location));
        $relatedOfficialsFilter = array(
            'service_id'  => new ObjectId($service->{'_id'}->{'$id'}),
            'location_id' =>  new ObjectId($location->{'_id'}->{'$id'}),
        );
        $relatedOfficials = $this->official_details_model->first_where($relatedOfficialsFilter);
        $this->load->helper('model');
        $appealApplicationId = checkAndGenerateUniqueId('appeal_id', 'appeal_applications');
        $nowDb = date('d-m-Y H:i');
        $now   = date('d-m-Y g:i a');
        $contactNumber = $this->input->post('contactNumber', true);
        $emailId = $this->input->post('emailId', true);
        $contactInAdditionContactNumber = $this->input->post('contactInAdditionContactNumber', true);
        $additionalContactNumber = $this->input->post('additionalContactNumber', true);
        $contactInAdditionEmail = $this->input->post('contactInAdditionEmail', true);
        $additionalEmailId = $this->input->post('additionalEmailId', true);
        $this->load->helper("fileupload");
        $this->config->load('first_appeal');
        $roleCondition = ['$expr'=>[
            '$in' => ['$slug', ['DA','DPS','AA']]
        ]];
        $this->load->helper('model');
        $process_users = prepareProcessUserList($roleCondition, $relatedOfficials);
        $isDAMapped = false;
        $isDpsMapped = false;
        $isAAMapped = false;
        foreach ($process_users as $p_user){
            if($p_user['role_slug'] === 'DA'){
                $isDAMapped = true;
            }
            if($p_user['role_slug'] === 'DPS'){
                $isDpsMapped = true;
            }
            if($p_user['role_slug'] === 'AA'){
                $isAAMapped = true;
            }
        }
        if(!$isDAMapped || !$isDpsMapped || !$isAAMapped){
            show_error('Sorry. No Official are mapped for the applied service and location!!!');
            exit(404);
        }
        $tentative_date = date_cal();
        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id'                       => $appealApplicationId,
            'appl_ref_no'                     => $app_ref_no,
            'applicant_name'                  => $this->input->post('nameOfThePerson', true),
            'gender'                          => $this->input->post('gender', true),
            'contact_number'                  => ($this->input->post('contactNumber', true) != 'NA') ? $this->input->post('contactNumber', true) : '1234567890', // dummy fallback number
            'contact_in_addition_contact_number' => $this->input->post('contactInAdditionContactNumber', true) ? true : false,
            'additional_contact_number'       => $this->input->post('additionalContactNumber', true),
            'email_id'                        => $this->input->post('emailId', true),
            'contact_in_addition_email'       => $this->input->post('contactInAdditionEmail', true) ? true : false,
            'additional_email_id'             => $this->input->post('additionalEmailId', true),
            'address_of_the_person'           => empty($this->input->post('addressOfThePerson'))? "":$this->input->post('addressOfThePerson'),
            'village'                         => $this->input->post('village'),
            'district'                        => $this->input->post('district'),
            'police_station'                  => $this->input->post('policestation'),
            'circle'                          => $this->input->post('circle'),
            'post_office'                     => $this->input->post('postoffice'),
            'pincode'                         => $this->input->post('pincode'),
            'name_of_service'                 => $this->input->post('nameOfService', true),
            'date_of_application'             => new \MongoDB\BSON\UTCDateTime((strtotime($this->input->post('dateOfApplication', true)) * 1000)),
            'appeal_expiry_status'            => $this->session->userdata("appeal_after_30") ?? false,
            'is_rejected'                     => false,
            'appeal_type'                     => 1,
            //            'date_of_appeal'                  => $this->input->post('dateOfAppeal', true),
            'name_of_PFC'                     => $applicationData->initiated_data->pfc ?? 'NA',
            'applied_by'                      => $this->session->userdata('role')->slug ?? 'appellant',
            'applied_by_user_id'              => empty($this->session->userdata('userId')) ? NULL : new ObjectId($this->session->userdata('userId')->{'$id'}) ?? NULL,
            'ground_for_appeal'               => $this->input->post('groundForAppeal', true),
            'relief_sought_for'               => $this->input->post('reliefSoughtFor', true),
            'appeal_description'              => $this->input->post('appealDescription', true),
            'documents'                       => moveFile(0, "appeal_attachments"),
            'process_status'                  => null,
            'service_id'                      => new ObjectId($service->{'_id'}->{'$id'}),
            'location_id'                     => new ObjectId($location->{'_id'}->{'$id'}),
            'created_at'                      => new MongoDB\BSON\UTCDateTime(strtotime($nowDb) * 1000),
            'reason_for_delay'                => !empty($this->input->post('appellateReasonFordelay', true)) ? $this->input->post('appellateReasonFordelay', true) : "",
            'delay_documents'                 => moveFile(0, "appeal_attachments_delay_reason"),
            'process_users'                   => $process_users,
            'tentative_hearing_date'             =>new \MongoDB\BSON\UTCDateTime(strtotime($tentative_date)*1000),
        ];
//          pre($inputs);
        $this->ams_model->insert($inputs);
        $appealApplication = $this->ams_model->get_with_related_by_appeal_id($appealApplicationId);
//        pre($appealApplication);
        $this->session->set_userdata('appeal_id', $appealApplicationId);
        // TODO :: use scheduling or job , queue for email and sms sending
        $officialUserArray[] = $relatedOfficials->dps_id;
        $officialUserArray[] = $relatedOfficials->appellate_id;
        $officialUsers = $this->users_model->get_where_in('_id',$officialUserArray);
        // official notification
        $officialToMailCSV = '';
        $this->load->config('sms_template');
        foreach ($officialUsers as $official) {
            // send sms
            $msgTemplate = $this->config->item('new_appeal_received');
            $msgTemplate['msg'] = str_replace("{{appeal_id}}", $appealApplicationId, $msgTemplate['msg']);//"New Appeal Received. Appeal ID : $appealApplicationId";
//            $msg = urlencode($msg);
            $this->sms->send($official->mobile, $msgTemplate);
            // to mail
            $officialToMailCSV .= $official->email . ',';
        }
        $officialToMailCSV = trim($officialToMailCSV, ',');
        // send email
        $emailBody = '<p>Dear Ma&amp;am/Sir,</p>
                            <p style="text-indent: 14px">New Appeal is submitted for Application No.' . $app_ref_no . '.</p>
                            <p>Appeal ID : ' . $appealApplicationId . '</p>
                            <p>Submitted on : ' . $now . '</p>';
        $this->remail->sendemail($officialToMailCSV, "New Appeal Received", $emailBody);
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($contactNumber, $emailId, $contactInAdditionContactNumber, $additionalContactNumber, $contactInAdditionEmail, $additionalEmailId);
        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $msgTemplate = $this->config->item('appeal_submitted');
            $msgTemplate['msg'] = str_replace("{{appeal_id}}", $appealApplicationId, $msgTemplate['msg']);
            $this->sms->send($contactMobile, $msgTemplate);
        }
        // send email with  ack attached to mail
        $this->load->helper('appeal');
        generate_n_send_mail_with_ack_attachment($appealApplicationId);
        redirect(base_url('appeal/ack'));
    }
    /**
     * acknowledgement
     *
     * @return void
     */
    public function acknowledgement()
    {
        $this->load->model('ams_model');
        $this->load->model('applications_model');
        $appeal_id = $this->session->userdata('appeal_id');
        //pre($appeal_id);
        $appealApplication = $this->ams_model->get_with_related_by_appeal_id($appeal_id);
        $applicationData = $this->applications_model->get_by_appl_ref_no($appealApplication[0]->appl_ref_no);
//        pre($appealApplication);
        //pre($this->session->userdata);
        if (!$this->session->userdata('opt_status') || !isset($appeal_id) || empty($applicationData)) {

            redirect(base_url('appeal/apply'));
        }
        $data = [
            'appealApplication' => $appealApplication,
            'applicationData' => $applicationData
        ];
        $this->load->view('userarea/includes/header', array("pageTitle" => "Dashboard | Acknowledgement"));
        $this->load->view('userarea/acknowledgement', $data);
        $this->load->view('userarea/includes/footer');
    }
    /**
     * preview_and_track
     *
     * @return void
     */
    public function preview_and_track()
    {
        $this->load->model('appeal_application_model');
        $this->load->model('applications_model');
        $this->load->model('appeal_process_model');
        $this->load->model('users_model');
        $appeal_id = $this->input->get('appeal_no');
        if(empty($this->session->userdata("userId")) ){
          $mobile=$this->session->userdata("mobile");
          $checkValid=$this->appeal_application_model->checkValidApplication($mobile,$appeal_id);
          if(!$checkValid){
            redirect("appeal/logout");
          }
        }

        $appeal = $this->appeal_application_model->get_by_appeal_id($appeal_id);
        $this->load->helper('model');
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});
        $processFilterAppeal = array('appeal_id' => $appealApplication[0]->appeal_id);
        $appealProcessList = $this->appeal_process_model->get_where($processFilterAppeal);
        $userList = $this->users_model->get_all(array());
        $data = [
            'appealApplication' => $appealApplication,
            'applicationData' => $applicationData ?? null,
            'appealProcessList' => $appealProcessList,
            'userList' => $userList
        ];
        if ($appealApplication[0]->appeal_type == '2') {
            $appealApplicationPrevious = $this->appeal_application_model->get_with_related_by_appeal_id($appealApplication[0]->ref_appeal_id);
            $data['appealApplicationPrevious'] = $appealApplicationPrevious;
        }
        $this->load->view('userarea/includes/header');
        $this->load->view('userarea/preview_n_track', $data);
        $this->load->view('userarea/includes/footer');
    }
    public function submit_comment()
    {
        $this->form_validation->set_rules('comment', 'Comment', 'trim|required|xss_clean|strip_tags');
        if ($this->form_validation->run() == false) {
            redirect('appeal/preview-n-track');
        }
        $this->load->model('appeal_application_model');
        $this->load->model('appeal_process_model');

        $appealApplication = $this->appeal_application_model->last_where(['appeal_id' => $this->input->post('appeal_id')]);
        if(property_exists($appealApplication,'ref_appeal_id')){
            $filterForLatestProcess = [
                'appeal_id' => $this->input->post('appeal_id'),
                'action' => 'second-appeal-seek-info',
                'notifiable' => 'appellant',
            ];
        }else{
            $filterForLatestProcess = [
                'appeal_id' => $this->input->post('appeal_id'),
                'action' => 'seek-info',
                'notifiable' => 'appellant',
            ];
        }
        // latest or seek info for appeal

//        $filterForLatestProcess = [
//            'appeal_id' => $this->input->post('appeal_id'),
//            ];
        $latestAppealProcess = $this->appeal_process_model->find_latest_where($filterForLatestProcess);

        if(empty($latestAppealProcess)){
            $latestAppealProcess = $this->appeal_process_model->find_latest_where(array('appeal_id' => $this->input->post('appeal_id')));
        }

        //        pre($latestAppealProcess);
        //        pre($latestAppealProcess->{'_id'}->{'$id'});
        $this->load->helper("fileupload");
        if (!isset($latestAppealProcess->comment)) {
            $appealCommentInput = array(
                'comment'    => $this->input->post('comment'),
                'comment_documents'  => moveFile(0, "file_for_comment"),
                'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            );
            $processUpdateFilter = array('_id' => new ObjectId($latestAppealProcess->{'_id'}->{'$id'}));
            $this->appeal_process_model->update_where($processUpdateFilter, $appealCommentInput);
            $this->session->set_flashdata('success', 'Comment successfully submitted for latest process.');
        } else {
            $this->session->set_flashdata('fail', 'Comment already submitted for latest process.');
        }
        redirect('appeal/track/?appeal_no='.$this->input->post('appeal_id'));
    }

    public function refresh_process_table($appealId)
    {
        $appealProcessList = $this->appeal_process_model->get_process_details($appealId);
        return $this->load->view("ams/appeal_process_table", compact(['appealProcessList']));
    }
    public function show_process_attachment_list($processId)
    {
        //sleep(5);
        $docFieldGet = $this->input->get('docField', true);
        $docField = isset($docFieldGet) ? $docFieldGet : 'documents';
        $process = $this->appeal_process_model->get_by_doc_id($processId);
        $processDocs = (array)$process->{$docField};
        if (count($processDocs) && is_array($processDocs)) {
            $tableContent = '';
            $counter = 0;
            foreach ($processDocs as $doc) {
                $tableContent .= '<a href="' . base_url($doc) . '" class="btn btn-sm btn-outline-warning" target="_blank">VIew Attachment ' . ($counter + 1) . '</a><br/>';
            }
            echo $tableContent;
        } else {
            echo '<h4 class="text-center">No Data Available</h4>';
        }
        exit();
    }

    /**
     * @param $appeal
     * @param array $data
     * @return array
     */
    private function check_second_appeal_timeline($appeal): array
    {


        $lastAppealProcess =  $this->appeal_process_model->last_where(array('appeal_id' => $appeal->appeal_id, 'action' => array('$in' => array('resolved', 'rejected'))));

        if ($appeal && $lastAppealProcess) {
            $lastProcessCreatedAtTimeStr = strtotime(format_mongo_date($lastAppealProcess->created_at, 'd-m-Y H:i:s'));

            $today = date_create();
            date_sub($today, date_interval_create_from_date_string("60 days"));
            $sixtyDaysAgoTimeStr = strtotime(date_format($today, "d-m-Y"));

            $today = date_create();
            date_sub($today, date_interval_create_from_date_string("90 days"));
            $ninetyDaysAgoTimeStr = strtotime(date_format($today, "d-m-Y"));

            // check if the appeal is approved or rejected within 90 days
            if ($ninetyDaysAgoTimeStr <= $lastProcessCreatedAtTimeStr) {
                // check if the appeal is approved or rejected within 60 days
                if ($sixtyDaysAgoTimeStr <= $lastProcessCreatedAtTimeStr) {
                    // allow appeal for submission without restriction
                    $this->session->set_userdata("appeal_expired", false);
                } else {
                    // set as expired appeal
                    $this->session->set_userdata("appeal_expired", true);
                }

                $data["status"] = true;
            } else {
                $data["status"] = false;
                $data["error_msg"] = 'Your application timeline for appeal submission is expired.';
            }
        } else {
            $data["status"] = false;
            $data["error_msg"] = "To Make second appeal first appeal needs to be rejected or resolved and it should pass the 60 days timeline.";
        }
        return $data;
    }
    /**
     * apply_for_appeal
     *
     * @return void
     */
    public function apply_second_appeal(){
        $ref_no = $this->input->get('appeal_no');
        $this->load->model('applications_model');
        $this->load->model('services_model');
        $this->load->model('location_model');
        $this->load->model('official_details_model');
        $this->load->model('ams_model');
        $this->load->model('users_model');
        $this->load->model('appeal_process_model');
        $appealApplicationPrevious = $this->ams_model->get_with_related_by_appeal_id($ref_no);

        $status = $this->check_second_appeal_timeline($appealApplicationPrevious);
        if (!$status['status']) {
            show_error($status['error_msg'], 403, 'Something Went Wrong!');
        }
        if (isset($appealApplicationPrevious->ref_appeal_id)) {
            redirect(base_url('appeal/userarea'));
        }
        $applicationData = $this->applications_model->get_by_appl_ref_no($appealApplicationPrevious->appl_ref_no);

        $service  = $this->services_model->first_where(array('service_id' => $applicationData->initiated_data->base_service_id));
        $this->load->model('location_model');
        $location = $this->location_model->first_where(array('location_name' => "" . $applicationData->initiated_data->submission_location . ""));
        $relatedOfficialsFilter = array(
            'service_id'  => new ObjectId($service->{'_id'}->{'$id'}),
            'location_id' =>  new ObjectId($location->{'_id'}->{'$id'}),
        );
        $relatedOfficials = $this->official_details_model->first_where($relatedOfficialsFilter);
        if (!$relatedOfficials) {
            show_error('No Officials are mapped for this service.', 403, 'Something Went Wrong!');
        }
        $dps = $this->users_model->get_by_doc_id($relatedOfficials->dps_id);
        $appalete = $this->users_model->get_by_doc_id($relatedOfficials->appellate_id);
        $review = $this->users_model->get_by_doc_id($relatedOfficials->reviewing_id);

        //        $this->load->helper('captcha');
        //        $cap = generate_n_store_captcha();
        $data = [
            'applicationData' => $applicationData,
            'appealApplicationPrevious' => $appealApplicationPrevious,
            'dps' => $dps,
            'appalete' => $appalete,
            'review' => $review,
            //            'cap' => $cap,
        ];

        $this->load->view('userarea/includes/header');
        $this->load->view('userarea/second_appeals_apply', $data);
        $this->load->view('userarea/includes/footer');
    }
    public function second_appeal_acknowledgement()
    {
        $this->load->model('ams_model');
        $this->load->model('applications_model');
        $appeal_id = $this->session->userdata('appeal_id');

        if (!$appeal_id) {
            redirect(base_url('appeal/userarea'));
            exit(200);
        }
        $appealApplication = $this->ams_model->get_with_related_by_appeal_id($appeal_id);

        if(!empty($appealApplication)){
            $applicationData = $this->applications_model->get_by_appl_ref_no($appealApplication[0]->appl_ref_no);
        }else{
            $this->load->model('appeal_application_model');
            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id_no_application_data($appeal_id);
        }
        $this->load->model('department_model');

        $department = $this->department_model->get_department_id_by_service_id(strval($appealApplication[0]->service_id));

        $data = [
            'department' => $department,
            'appealApplication' => $appealApplication,
            'applicationData' => $applicationData ?? ''
        ];
        $this->load->view('userarea/includes/header');
        $this->load->view('userarea/acknowledgement_second_appeal', $data);
        $this->load->view('userarea/includes/footer');
    }

    public function refresh_captcha()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->helper('captcha');
            $cap = generate_n_store_captcha();
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'captcha' => $cap,
                    'status' => true,
                )));
        }
    }

    private function check_session(){
// $otp_status = $this->session->userdata('opt_status');
// pre($this->session->userdata('isLoggedIn'));
        if(!empty($this->session->userdata('opt_status')) && $this->session->userdata('opt_status') ){
           return;
        }
        else if(!empty($this->session->userdata('isLoggedIn')) && $this->session->userdata('isLoggedIn') ){
            return;
        }else{
            redirect("appeal/logout");
        }
    }
    public function make_appeal_view()
    {  
        $this->check_session();
        $this->load->model('services_model');
        $serviceList = (array) $this->services_model->get_mapped_service_list();
        $this->load->model('location_model');
        $data = [
            'serviceList' => $serviceList,
            'locationList' => array()
        ];
        $this->load->view('userarea/includes/header');
        $this->load->view('userarea/make_appeal_without_ref_no', $data);
        $this->load->view('userarea/includes/footer');
    }
    public function get_locations_by_service($services_id=''){
        $this->load->model('location_model');
        
        if(!empty($services_id)){
            $locationIds= $this->location_model->get_mapped_location_ids($services_id);
            $location_id=[];
            foreach($locationIds as $loc){
                $location_id[]=new ObjectId(strval($loc->location_id)); 
            }
            $locationList =  $this->location_model->get_location_list_by_ids($location_id);
    
            if( !empty($locationList)){  ?>
                    <select class="select2 form-control" name="location" id="location" required>
                        <option value="">Choose One</option>
                        <?php
                        foreach ($locationList as $location) {
                            ?>
                            <option value="<?= $location->{'_id'}->{'$id'} ?>"><?= $location->location_name ?></option>
                            <?php
                        }
                        ?>
                    </select>
            <?php }else{ ?>
                <select class="select2 form-control" name="location" id="location" required>
                        <option value="">No Result Found</option>
                    </select>
            <?php }
        }else{?>
                    <select class="select2 form-control" name="location" id="location" required>
                        <option value="">No Result Found</option>
                    </select>
        <?php }
    }
    public function fetch_official($serviceId, $locationId)
    {
        $this->load->model('official_details_model');
        $officialFilter = [
            'service_id' => new ObjectId($serviceId),
            'location_id' => new ObjectId($locationId)
        ];
        $officialInfo = $this->official_details_model->get_related($officialFilter);
        if (!empty($officialInfo)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'dps_name' => $officialInfo->dps_details->name,
                    'dps_designation' => $officialInfo->dps_details->designation,
                    'appellate_name' => $officialInfo->appellate_details->name,
                    'appellate_designation' => $officialInfo->appellate_details->designation,
                    'status' => true,
                )));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => false,
                )));
        }
    }
    private function checkDateFormat($date) {
        // $date="31-12-2022";
                if (preg_match('/(0[1-9]|1[0-9]|2[0-9]|3(0|1))-(0[1-9]|1[0-2])-\d{4}$/', $date)){
                     return true; // it matched, return true or false if you want opposite
                }else{
                     return false;
                }
        } 
    private function check_date_greater_than_today($data){
        $date_now = new DateTime();
        $date2    = new DateTime($data);
       
       if (  $date2 > $date_now) {
           return true;
          
       }else{
        return false;
       }
    }
    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
       }else{
           return false;
       }
    }
    public function no_ref_process()
    {
        $this->check_session();
        if (!$this->session->has_userdata('appeal_attachments')) {
            $this->session->set_flashdata('error', 'At least one appeal attachment required.');
            redirect('appeal/make-appeal');
        }

        $this->form_validation->set_rules('applRefNo', 'Application Ref Number', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('nameOfThePerson', 'Appellant Name', 'trim|required|alpha_numeric_spaces');
        $this->form_validation->set_rules('contactNumber', 'Contact Number', 'trim|required|numeric|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('emailId', 'Email Id', 'trim|required|valid_email');
        $this->form_validation->set_rules('additionalContactNumber', 'Additional Contact Number', 'trim|numeric|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('nameOfPFC', 'Name of PFC', 'trim|alpha_numeric_spaces');
        $this->form_validation->set_rules('additionalEmailId', 'Additional Email ID', 'trim|valid_email');
       // $this->form_validation->set_rules('addressOfThePerson', 'Address of the Appellant', 'trim|required');
        $this->form_validation->set_rules('groundForAppeal', 'Ground for appeal', 'trim|required|alpha_numeric_spaces');
        $this->form_validation->set_rules('reliefSoughtFor', 'Relief sought for', 'trim|required|alpha_numeric_spaces');
        $this->form_validation->set_rules('appealDescription', 'Appeal Description', 'trim|required|alpha_numeric_spaces');
        $this->form_validation->set_rules('village', 'Village', 'trim|required|alpha_numeric_spaces');
        $this->form_validation->set_rules('gender', 'Gender', 'required|trim');
        $this->form_validation->set_rules('district', 'District', 'trim|required|alpha_numeric_spaces');
        $this->form_validation->set_rules('policestation', 'Police Station', 'trim|required|alpha_numeric_spaces');
        $this->form_validation->set_rules('circle', 'Circle', 'trim|required|alpha_numeric_spaces');
        $this->form_validation->set_rules('postoffice', 'Post Office', 'trim|required|alpha_numeric_spaces');
        $this->form_validation->set_rules('pincode', 'Pincode', 'trim|required|numeric|min_length[6]|max_length[6]');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('old', $this->input->post());
            $this->session->set_flashdata("error", validation_errors());
            $this->session->set_flashdata("old", $this->input->post());
            // redirect('appeals/apply');
            redirect('appeal/make-appeal');
        }
        if(!$this->checkDateFormat($this->input->post('dateOfApplication'))){
            $this->session->set_flashdata("error", "Invalid Date of application");
            redirect('appeal/make-appeal');
            return;
        } 
        if(!$this->checkDateFormat($this->input->post('dateOfAppeal'))){
            $this->session->set_flashdata("error", "Invalid Date of appeal");
            redirect('appeal/make-appeal');
            return;
        }
        
        if($this->check_date_greater_than_today($this->input->post('dateOfApplication'))){
            $this->session->set_flashdata("error", "Date of Application should not be greater than today Date");
            redirect('appeal/make-appeal');
            return;
        }
        if(!$this->checkObjectId($this->input->post('service'))){
            $this->session->set_flashdata("error", "Invalid Service");
            redirect('appeal/make-appeal');
            return;
        }
        if(!$this->checkObjectId($this->input->post('location'))){
            $this->session->set_flashdata("error", "Invalid Location");
            // $this->form_validation->set_message('location', 'Invalid Location');
            redirect('appeal/make-appeal');
            return;
        }
        $this->load->helper('model');
        $appealApplicationId = checkAndGenerateUniqueId('appeal_id', 'appeal_applications');

        $this->load->model('official_details_model');
        $relatedOfficialsFilter = array(
            'service_id'  => new ObjectId($this->input->post('service')),
            'location_id' =>  new ObjectId($this->input->post('location')),
        );
        $relatedOfficials = $this->official_details_model->first_where($relatedOfficialsFilter);
        $this->load->helper("fileupload");
        $nowDb = date('d-m-Y H:i');
        $now   = date('d-m-Y g:i a');
        $this->config->load('first_appeal');
        $roleCondition = ['$expr'=>[
            '$in' => ['$slug', ['DA','DPS','AA']]
        ]];
        $this->load->helper('model');
        $process_users = prepareProcessUserList($roleCondition, $relatedOfficials);
        $isDAMapped = false;
        $isDpsMapped = false;
        $isAAMapped = false;
        foreach ($process_users as $p_user){
            if($p_user['role_slug'] === 'DA'){
                $isDAMapped = true;
            }
            if($p_user['role_slug'] === 'DPS'){
                $isDpsMapped = true;
            }
            if($p_user['role_slug'] === 'AA'){
                $isAAMapped = true;
            }
        }
        if(!$isDAMapped || !$isDpsMapped || !$isAAMapped){
            show_error('Sorry. No Official are mapped for the applied service and location!!!');
            exit(404);
        }
//        $tentative_date=date('d-m-Y', strtotime("+8 days"));
        $tentative_date = date_cal();
        $appealInputs = [
            'appl_ref_no'                     => $this->input->post('applRefNo'),
            'appeal_id'                       => $appealApplicationId,
            'applicant_name'                  => $this->input->post('nameOfThePerson'),
            'gender'                          => $this->input->post('gender'),
            'contact_number'                  => $this->input->post('contactNumber'),
            'contact_in_addition_contact_number' => $this->input->post('contactInAdditionContactNumber', true) ? true : false,
            'additional_contact_number'       => $this->input->post('additionalContactNumber'),
            'email_id'                        => $this->input->post('emailId'),
            'contact_in_addition_email'       => $this->input->post('contactInAdditionEmail', true) ? true : false,
            'additional_email_id'             => $this->input->post('additional_email_id'),
            'address_of_the_person'           => empty($this->input->post('addressOfThePerson'))? "":$this->input->post('addressOfThePerson'),
            'village'                         => $this->input->post('village'),
            'district'                        => $this->input->post('district'),
            'police_station'                  => $this->input->post('policestation'),
            'circle'                          => $this->input->post('circle'),
            'post_office'                     => $this->input->post('postoffice'),
            'pincode'                         => $this->input->post('pincode'),
            'date_of_application'             => new \MongoDB\BSON\UTCDateTime((strtotime($this->input->post('dateOfApplication', true)) * 1000)),
            'appeal_expiry_status'            => $this->session->userdata("appeal_after_30") ?? false,
            'is_rejected'                     => false,
            'appeal_type'                     => 1,
            'name_of_PFC'                     => $this->input->post('nameOfPFC'),
            'ground_for_appeal'               => $this->input->post('groundForAppeal'),
            'relief_sought_for'               => $this->input->post('reliefSoughtFor'),
            'appeal_description'              => $this->input->post('appealDescription'),
            'reliefSoughtFor'                 => $this->input->post('reliefSoughtFor'),
            'applied_by'                      => $this->session->userdata('role')->slug ?? 'appellant',
            'applied_by_user_id'              => $this->session->has_userdata('userId') ? new ObjectId($this->session->userdata('userId')->{'$id'}) : NULL,
            'documents'                       => moveFile(0, "appeal_attachments"),
            'process_status'                  => 'initiated',
            'name_of_service'                 => $this->input->post('nameOfService', true),
            'service_id'                      => new ObjectId($this->input->post('service')),
            'location_id'                     => new ObjectId($this->input->post('location')),
            'created_at'                      => new MongoDB\BSON\UTCDateTime(strtotime($nowDb) * 1000),
            'process_users'                   => $process_users,
            'official_mapping_id'             =>new ObjectId($relatedOfficials->_id->{'$id'}),
            'reason_for_delay'                => empty($this->input->post('reasonOfDelay')) ? "":$this->input->post('reasonOfDelay'),
            'tentative_hearing_date'             =>new \MongoDB\BSON\UTCDateTime(strtotime($tentative_date)*1000),
            'appeal_expiry_date' => new \MongoDB\BSON\UTCDateTime(strtotime($this->input->post('appeal_exp_date') )*1000),
        ];
        $this->load->model('appeal_application_model');
        $this->appeal_application_model->insert($appealInputs);
        $this->session->set_userdata('appeal_id', $appealApplicationId);
        $this->load->model('users_model');
        $officialUserCondition = [$relatedOfficials->dps_id, $relatedOfficials->appellate_id];
        $officialUsers = $this->users_model->get_where_in('_id',$officialUserCondition);
        // official notification

        $this->load->config('sms_template');
        $msgTemplate = $this->config->item('new_appeal_received');
        $msgTemplate['msg'] = str_replace("{{appeal_id}}", $appealApplicationId, $msgTemplate['msg']);
        $officialToMailCSV = '';
        foreach ($officialUsers as $official) {
            // send sms
            $this->sms->send($official->mobile, $msgTemplate);
            // to mail
            $officialToMailCSV .= $official->email . ',';
        }
        $officialToMailCSV = trim($officialToMailCSV, ',');
        // send email
        $emailBody = '<p>Dear Ma&amp;am/Sir,</p>
                        <p style="text-indent: 14px">New Appeal is submitted.</p>
                        <p>Appeal ID : ' . $appealApplicationId . '</p>
                        <p>Submitted on : ' . $now . '</p>';
        $this->remail->sendemail($officialToMailCSV, "New Appeal Received", $emailBody);
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($this->input->post('contactNumber'), $this->input->post('emailId'), $this->input->post('contactInAdditionContactNumber'), $this->input->post('additionalContactNumber'), $this->input->post('contactInAdditionEmail'), $this->input->post('additionalEmailId'));
        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $msgTemplate = $this->config->item('appeal_submitted');
            $msgTemplate['msg'] = str_replace("{{appeal_id}}", $appealApplicationId, $msgTemplate['msg']);
            $this->sms->send($contactMobile, $msgTemplate);
        }
        // send email
        // TODO :: attach ack to mail
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>Dear Applicant,</p>
                        <p style="text-indent: 14px">Appeal submitted. </p>
                        <p>Appeal ID : ' . $appealApplicationId . '</p>
                        <p>Submitted on : ' . $now . '</p>';
            $this->remail->sendemail($contactEmail, "Appeal Submitted", $emailBody);
        }
        redirect(base_url('appeal/ack-without-ref'));
    }

    public function ack_without_ref()
    {
        $this->check_session();
        $this->load->model('appeal_application_model');
        $appeal_id = $this->session->userdata('appeal_id');
        //pre($appeal_id);
        $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id_no_application_data($appeal_id);
//        pre($appealApplication);
        //pre($this->session->userdata);

        if($this->session->userdata('role') && !in_array($this->session->userdata('role')->slug,['DA','PFC'])){
            if ((!$this->session->userdata('opt_status') || !isset($appeal_id)))  {

                redirect(base_url('appeal/make-appeal'));
            }
        }


        $office_users = $this->appeal_application_model->get_by_appeal_id($appealApplication[0]->appeal_id);
        
        $office_users = $office_users->process_users;


// print_r($office_users);
// return;
$active_user_id = null;

foreach ($office_users as $x) {
    // print_r ($x->active);
    if($x->active === true){
        // echo $x->role_slug;
        $active_user_id =  $x->userId;
    }
    else{
        // $active_user_id = null;
    }
  }

  $active_user_info = $this->users_model->by_id($active_user_id);

  $active_user_info = json_decode(json_encode($active_user_info), true);


        $data = [
            'appealApplication' => $appealApplication,
            'active_user_info' => $active_user_info[0]
        ];
        $this->load->view('userarea/includes/header');
        $this->load->view('ams/view_ack_without_application_ref', $data);
        $this->load->view('userarea/includes/footer');
    }

    public function count_appeal_status()
    {
        $this->load->model('appeals_model');
        //$total = $this->appeals_model->total_rows();
        $total = $this->appeals_model->tot_search_rows(array(
            'contact_number' => $this->session->userdata('mobile')
        ));
        $new = $this->appeals_model->tot_search_rows(array(
            'process_status' => null,
            'contact_number' => $this->session->userdata('mobile')
        ));
        $resolved = $this->appeals_model->tot_search_rows(array(
            'process_status' => "resolved",
            'contact_number' => $this->session->userdata('mobile')
        ));

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'total' => $total ?? 0,
                'new' => $new,
                'processed' => $total - ($new + $resolved),
                'resolved' => $resolved,

            )));
    }
    public function preview(){
        $this->load->model('applications_model');
        $this->load->model('ams_model');
        $ref_no = $this->input->get('id');
        if(!isset($ref_no)){
            show_404();
            exit();
        }
        $appealApplication = $this->applications_model->get_by_appl_ref_no($ref_no);
        $this->load->view('userarea/includes/header', array("pageTitle" => "Dashboard | Applications Details"));
        $this->load->view("applications/preview", array('data' => $appealApplication->initiated_data, 'execution_data' => $appealApplication->execution_data));
        $this->load->view('userarea/includes/footer');

    }
}
