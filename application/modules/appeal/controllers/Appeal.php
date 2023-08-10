<?php

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Appeal extends Rtps
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('appeal_application_model');
        $this->load->module('dashboard')->model('applications_model');
        $this->load->model('appeal_process_model');
        $this->load->model('users_model');
        $this->load->model('official_details_model');
        $this->load->model('services_model');
        $this->load->helper('role');
        $this->load->library('form_validation');
        // if(!in_array($this->session->userdata('role')->slug,['DA','RR','RA','MOC'])){
        //     redirect('appeal/dashboard');
        // }
        $this->load->library('user_agent');
    }
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //hasPermission();
        $this->load->view('includes/header');
        $this->load->view('ams/index');
        $this->load->view('includes/footer');
    }
    /**
     * second_appeal_list
     *
     * @return void
     */
    public function second_appeal_list()
    {
        $this->load->view('includes/header');
        $this->load->view('ams/second_appeals_list');
        $this->load->view('includes/footer');
    }
    /**
     * view_processes
     *
     * @param mixed $ref_id
     * @return void
     */
    public function view_processes($ref_id)
    {
        $appeal = $this->appeal_application_model->get_by_doc_id($ref_id);
        if(property_exists($appeal,'appl_ref_no')){
            $appealApplication = $this->appeal_application_model->get_appeal_details($ref_id);
        }else{
            $appealApplication = $this->appeal_application_model->get_appeal_details_without_ref($ref_id);
        }
        if(!isset($appealApplication) && empty($appealApplication)){
            redirect(base_url('appeal/list'));
        }

//        if(count($appealApplication))
        $appealApplicationPrevious = isset($appealApplication->ref_appeal_id) ? $this->appeal_application_model->get_with_related_by_appeal_id($appealApplication->ref_appeal_id) : null;
        $appealProcessPreviousList = isset($appealApplication->ref_appeal_id) ? $this->appeal_process_model->get_process_details($appealApplicationPrevious->appeal_id) : null;
        $forwardAbleUserList =$this->users_model->get_users_of_role(); // don't remove
        // get application processes for dps task verification and show
        $appealApplicationProcesses = $this->appeal_process_model->get_process_details($appealApplication->appeal_id);
        $this->load->model('roles_model');
        $reAssignAbleUserFilter = ['slug' => 'DPS'];
        $forwardAbleUserFilter = ['slug' => 'AA'];
        if(property_exists($appealApplication,'appeal_type') && $appealApplication->appeal_type == 2){
            $reAssignAbleUserFilter = ['slug' => ['$in' => ['DPS','AA']]];
            $forwardAbleUserFilter = ['slug' => 'RA'];
        }
        $reAssignAbleUserList = $this->roles_model->get_role_wise_user_list($reAssignAbleUserFilter);
        $forwardAbleUserList = $this->roles_model->get_role_wise_user_list($forwardAbleUserFilter)->{0};

        $data = [
            'appealApplication' => $appealApplication,
            'appealApplicationPrevious' => $appealApplicationPrevious,
            'appealProcessPreviousList' => $appealProcessPreviousList,
            'applicationData' => $appealApplication->application_details ?? null,
            'forwardAbleUserList' => $forwardAbleUserList, // don't remove
            'reAssignAbleUserList' => $reAssignAbleUserList, // don't remove
            'appealApplicationProcesses' => $appealApplicationProcesses,
        ];
        $this->load->view('includes/header');
        //pre($appealApplication);
        if(property_exists($appealApplication,'appeal_type') && $appealApplication->appeal_type == 2){
            $this->load->view('ams/process_second_appeal', $data);
        }else{
            $this->load->view('ams/process_appeal', $data);
        }

        $this->load->view('includes/footer');
    }
    /**
     * view_processes
     *
     * @param mixed $ref_id
     * @return void
     */
    public function view_appeal($ref_id)
    {
        $appealApplication = $this->appeal_application_model->get_appeal_details_for_everyone($ref_id);
        //pre($appealApplication);
        if(!isset($appealApplication) && empty($appealApplication)){
            redirect(base_url('appeal/list'));
        }

//        if(count($appealApplication))
        $appealApplicationPrevious = isset($appealApplication->ref_appeal_id) ? $this->appeal_application_model->get_by_appeal_id($appealApplication->ref_appeal_id) : null;
        $appealProcessPreviousList = isset($appealApplication->ref_appeal_id) ? $this->appeal_process_model->get_where('appeal_id', $appealApplicationPrevious->appeal_id) : null;
        //$forwardAbleUserList = $this->users_model->get_where_in(array('roleId' => getRoleIdByKey(array('DPS', 'APPELLATE_AUTH', 'REVIEWING_AUTH')))); // don't remove

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
            'appealApplicationPrevious' => $appealApplicationPrevious,
            'appealProcessPreviousList' => $appealProcessPreviousList,
            'applicationData' => property_exists($appealApplication[0],'application_details') ? $appealApplication[0]->application_details : array(),
            //'forwardAbleUserList' => $forwardAbleUserList, // don't remove
            // 'active_user_info' => $active_user_info[0]['email'],
            'active_user_info' => $active_user_info[0],
            
        ];

        // pre($data);
        // echo($appealApplication[0]->appeal_id);
        // return;


        $this->load->view('includes/header');
        $this->load->view('ams/appeal_view', $data);
        $this->load->view('includes/footer');
    }
    /**
     * get_records
     *
     * @param mixed $appealType
     * @return void
     */
    public function get_records($appealType = 'first')
    {
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
        $totalData = $this->appeal_application_model->total_rows();
        $totalFiltered = $totalData;
        $filter = array();
        if (empty($this->input->post("search")["value"])) {
            $this->session->set_userdata($filter);
            $records = $this->appeal_application_model->appeals_filter($limit, $start, $filter, $order, $dir);
            $totalFiltered = $this->appeal_application_model->appeals_filter_count();
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->appeal_application_model->appeals_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->appeal_application_model->appeals_tot_search_rows($search);
        }
        $data = array();
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                if ($appealType == 'first' && $rows->appeal_type == 2) {
                    // ($appealType == 'first' && isset($rows->ref_appeal_id)) replaced by (!$rows->is_second)
                    continue;
                }
                if ($appealType == 'second' && $rows->appeal_type == 1) {
                    // ($appealType == 'second' && !isset($rows->ref_appeal_id)) replaced by $rows->is_second
                    continue;
                }
//                $btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $btns = '<a href="' . base_url('appeal/process/view/' . $rows->{'_id'}->{'$id'}) . '" class="btn btn-sm btn-info" title="Process">Process</a> ';
                // <a href="'.base_url("users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
                // <a href="'.base_url("dashboard/users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
                $nestedData["sl_no"] = $sl;
                $nestedData["appeal_id"] = strtoupper($rows->appeal_id);
                $nestedData["name"] = $rows->applicant_name;
                $nestedData["contact_no"] = ($rows->contact_in_addition_contact_number) ? $rows->additional_contact_number : $rows->contact_number;
                $nestedData["appeal_date"] = format_mongo_date($rows->created_at);
                switch ($rows->process_status) {
                    case 'reply':
                        $process_status = '<span class="badge badge-info">Reply</span>';
                        break;
                    case 'resolved':
                    case 'second-appeal-issue-disposal-order':
                        $process_status = '<span class="badge badge-success">Resolved</span>';
                        break;
                    case 'rejected':
                    case 'second-appeal-issue-rejection-order':
                        $process_status = '<span class="badge badge-danger">Rejected</span>';
                        break;
                    case 'remark':
                        $process_status = '<span class="badge badge-warning">Remark</span>';
                        break;
                    case 'penalize':
                        $process_status = '<span class="badge badge-primary">Penalized</span>';
                        break;
                    case 'forward':
                    case 'forward-to-aa':
                    case 'second-appeal-forward-to-aa':
                    $process_status = '<span class="badge badge-secondary">Forwarded To Appellate Authority</span>';
                    break;
                    case 'second-appeal-forward-to-registrar':
                    $process_status = '<span class="badge badge-secondary">Forwarded To Registrar</span>';
                    break;
                    case 'generate-hearing-order':
                        $process_status = '<span class="badge badge-warning">Hearing Order Generated</span>';
                        break;
                    case 'upload-hearing-order':
                    case 'second-appeal-upload-hearing-order':
                        $process_status = '<span class="badge badge-warning">Hearing Order Uploaded</span>';
                        break;
                    case 'second-appeal-approve-hearing-order':
                    $process_status = '<span class="badge badge-success">Hearing Order Approved</span>';
                    break;
                    case 'second-appeal-issue-hearing-order':
                    $process_status = '<span class="badge badge-success">Hearing Order Issued</span>';
                    break;
                    case 'generate-disposal-order':
                        $process_status = '<span class="badge badge-warning">Disposal Order Generated</span>';
                        break;
                    case 'upload-disposal-order':
                    case 'second-appeal-upload-disposal-order':
                        $process_status = '<span class="badge badge-warning">Disposal Order Uploaded</span>';
                        break;
                    case 'second-appeal-approve-disposal-order':
                    $process_status = '<span class="badge badge-success">Disposal Order Approved</span>';
                    break;
                    case 'generate-rejection-order':
                        $process_status = '<span class="badge badge-warning">Rejection Order Generated</span>';
                        break;
                    case 'upload-rejection-order':
                    case 'second-appeal-upload-rejection-order':
                        $process_status = '<span class="badge badge-warning">Rejection Order Uploaded</span>';
                        break;
                    case 'second-appeal-approve-rejection-order':
                    $process_status = '<span class="badge badge-success">Rejection Order Approved</span>';
                    break;
                    case 'second-appeal-forward-to-chairman':
                        $process_status = '<span class="badge badge-secondary">Forwarded To Chairman</span>';
                        break;
                    case 'revert-back-to-da':
                    case 'second-appeal-revert-back-to-da':
                        $process_status = '<span class="badge badge-info">Reverted To DA</span>';
                        break;
                    case 'second-appeal-change-hearing-date':
                         $process_status = '<span class="badge badge-info">Hearing Date Changed</span>';
                        break;
                    case 'second-appeal-confirm-hearing-date':
                         $process_status = '<span class="badge badge-info">Hearing Date Confirmed</span>';
                        break;
                    case 'reassign':
                        $process_status = '<span class="badge badge-info">Reassigned</span>';
                        break;
                    case 'in-progress':
                        $process_status = '<span class="badge badge-wrapper">In Progress</span>';
                        break;
                    case 'hearing':
                        $process_status = '<span class="badge badge-warning">Hearing</span>';
                        break;
                    case 'seek-info':
                        $process_status = '<span class="badge badge-primary">Seeking Info</span>';
                        break;
                    case 'issue-order':
                        $process_status = '<span class="badge badge-info">Order Issued</span>';
                        break;
                    case 'dps-reply':
                        $process_status = '<span class="badge badge-info">DPS Reply</span>';
                        break;
                    case 'appellate-reply':
                        $process_status = '<span class="badge badge-info">Appellate Authority Reply</span>';
                        break;
                    default:
                        $process_status = '<span class="badge badge-secondary">Initiated</span>';
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
    /**
     * process_dps_action
     *
     * @param mixed $appeal_id
     * @return void
     */

    public function process_dps_action($appeal_id = NULL)
    {
        switch ($this->session->userdata("role")->slug){
            case 'DPS':
                $action = 'dps-reply';
                break;
            case 'AA':
                $action = 'appellate-reply';
                break;
            default:
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array(
                        'success' => false
                    )));
                break;
        }
        $message = $this->input->post('remarks', true);
        $appeal_id = $this->input->post('appeal_id');
        //        $appeal_id = str_replace('%','/',$appeal_id);
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'validation_errors' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->helper("fileupload");
        $processInputs = array(
            'appeal_id' => $appeal_id,
            'action' => $action,
            'action_taken_by' => new ObjectId($actionTakenBy),
            'message' => $message,
            'comment' => null,
            'documents' => moveFile(0, "file_for_dps_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            'updated_at' => null
        );
        $appealApplication = $this->appeal_application_model->first_where('appeal_id', $appeal_id);

        $this->appeal_process_model->insert($processInputs);
        $appealApplicationFilters = array(
            'appeal_id' => $appeal_id
        );
        $appealApplicationUpdateInputs['process_status'] = $action;
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        // applicant notification
        $msg = 'DPS replied to Appeal with ID ' . $appeal_id . '.';
        $msg = urlencode($msg);
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $this->sms->send($contactMobile, $msg);
        }
        // send email
        $emailBody = '<p>' . 'DPS replied to Appeal with ID ' . $appeal_id . '</p>';
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $this->remail->sendemail($contactEmail, "DPS Replied", $emailBody);
        }

        // notify appellate
        $appellateInfo = $this->users_model->get_by_doc_id($appealApplication->dps_id);
        $this->sms->send($appellateInfo->mobile, $msg);
        $this->remail->sendemail($appellateInfo->email, "DPS Replied", $emailBody);


        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => 'Information Submitted',
                'dps_action_done' => true
            )));
        //        }else{
        //            return $this->output
        //                ->set_content_type('application/json')
        //                ->set_status_header(200)
        //                ->set_output(json_encode(array(
        //                    'success' => false,
        //                    'validation_errors' => 'Operation failed!!! Action already submitted.'
        //                )));
        //        }
    }
    /**
     * process_dps_action old
     *
     * @param mixed $appeal_id
     * @return void
     */
    public function process_dps_action_old($appeal_id = NULL)
    {
        $forwardTo = $this->input->post('forwardTo', true);
        $action = $this->input->post('action', true);
        $message = $this->input->post('message', true);
        $appeal_id = $this->input->post('appeal_id');
        //        $appeal_id = str_replace('%','/',$appeal_id);
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->validateProcessAction();
        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'validation_errors' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->helper("fileupload");
        $processInputs = array(
            'appeal_id' => $appeal_id,
            'action' => $action,
            'action_taken_by' => new ObjectId($actionTakenBy),
            'message' => $message,
            'comment' => null,
            'documents' => moveFile(0, "file_for_dps_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            'updated_at' => null
        );
        $appealApplication = $this->appeal_application_model->first_where('appeal_id', $appeal_id);

        $forwardToUser = array();
        if (isset($forwardTo) && $forwardTo!="") {
            $appealApplicationUpdateInputs = array();
            $previousUser = '';
            $processInputs['forward_to'] = $forwardTo;
            $forwardToUser = $this->users_model->get_by_doc_id($forwardTo);
            $roleKey = getRoleKeyById($forwardToUser->roleId);
            switch ($roleKey) {
                case 'DPS':
                    if (property_exists($appealApplication, 'dps_id')) {
                        $appealApplicationUpdateInputs['dps_id'] = new ObjectId($forwardToUser->{'_id'}->{'$id'});
                        $previousUser = $appealApplication->dps_id;
                    }
                    break;
                case 'AA':
                    if (property_exists($appealApplication, 'appellate_id')) {
                        $appealApplicationUpdateInputs['appellate_id'] = new ObjectId($forwardToUser->{'_id'}->{'$id'});
                        $previousUser = $appealApplication->appellate_id;
                    }
                    break;
                case 'RA':
                    if (property_exists($appealApplication, 'reviewing_id')) {
                        $appealApplicationUpdateInputs['reviewing_id'] = new ObjectId($forwardToUser->{'_id'}->{'$id'});
                        $previousUser = $appealApplication->reviewing_id;
                    }
                    break;
                default:
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array(
                            'success' => false,
                            'fail' => 'Unable to process. Please try again.'
                        )));
                    break;
            }
            $processInputs['previous_user'] = $previousUser;
        }
        $filter = array('appeal_id' => $appeal_id, 'action_taken_by' => $actionTakenBy);
        $dpsActionDone = false;
        //        if(!$this->appeal_process_model->is_action_already_submitted($action,$filter)){
        $this->appeal_process_model->insert($processInputs);
        $appealApplicationFilters = array(
            'appeal_id' => $appeal_id
        );
        $appealApplicationUpdateInputs['process_status'] = $action;
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);
        $allPossibleActions = array('remark', 'reply', 'forward');
        if ($this->appeal_process_model->check_all_action_submitted($allPossibleActions, $filter)) {
            $appealApplicationFilter = array('appeal_id' => $appeal_id);
            $dataToUpdate = array('dps_action' => 'done');
            $this->appeal_application_model->update_where($appealApplicationFilter, $dataToUpdate);
            $dpsActionDone = true; // todo need fixing
        }
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);
        switch ($action) {
            case 'remark':
                $notificationMsg = 'Remark submitted by DPS';
                $responseMsg = 'Remark submitted successfully.';
                break;
            case 'reply':
                $notificationMsg = 'Replied by DPS';
                $responseMsg = 'Reply and justification submitted successfully.';
                break;
            case 'forward':
                $notificationMsg = 'Forwarded by DPS';
                $responseMsg = 'Appeal forwarded successfully.';
                break;
            default:
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array(
                        'success' => false,
                        'validation_errors' => 'Invalid Action!!!'
                    )));
                break;
        }
        // notify forwarded To User
        if (count((array)$forwardToUser)) {
            $msg = 'An appeal is forwarded to you. Appeal ID ' . $appeal_id . '.';
            $msg = urlencode($msg);
            $this->sms->send($forwardToUser->mobile, $msg);
            $emailBody = '<p>Dear Ma&amp;am/Sir,</p><p>An appeal is forwarded to you. Appeal ID' . $appeal_id . '</p>';
            $this->remail->sendemail($forwardToUser->email, "Appeal forwarded", $emailBody);
        }
        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $msg = $notificationMsg . ' for your Appeal with ID ' . $appeal_id . '.';
            $msg = urlencode($msg);
            $this->sms->send($contactMobile, $msg);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . ' for your Appeal with ID ' . $appeal_id . '</p>';
            $this->remail->sendemail($contactEmail, "Appeal under processed", $emailBody);
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
                'dps_action_done' => $dpsActionDone
            )));
        //        }else{
        //            return $this->output
        //                ->set_content_type('application/json')
        //                ->set_status_header(200)
        //                ->set_output(json_encode(array(
        //                    'success' => false,
        //                    'validation_errors' => 'Operation failed!!! Action already submitted.'
        //                )));
        //        }
    }
    /**
     * validateProcessAction
     *
     * @return void
     */
    private function validateProcessAction()
    {
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('action', 'Action', 'trim|required|xss_clean|strip_tags');
        if ($this->input->post('action') == 'penalize') {
            $this->form_validation->set_rules('penaltyAmount', 'Penalty Amount', 'trim|required|numeric|xss_clean|strip_tags');
        }
    }
    /**
     * excel_export
     *
     * @return void
     */
    public function excel_export()
    {
        $this->load->model('appeal_reports_model');
        $appeal_type = $this->input->get('appeal_type', true);
        $startDate = $this->input->get('start_date', true);
        $endDate = $this->input->get('end_date', true);
        $services = $this->input->get('services', true);
        $users = $this->input->get('users', true);
        if($this->session->userdata('role')->slug !== 'SA'){
            $my_appeals_only = true;//$this->input->get('my_appeals_only', true);
        }
        $id=$this->session->userdata('userId')->{'$id'};
        if (isset($my_appeals_only)) {
            // set filter for id and role,
            $my_role = getRoleKeyById($this->session->userdata('role')->{'_id'}->{'$id'});
            switch ($my_role) {
                case 'DPS':
                    $filter['dps_id']       = new ObjectId($id);
                    break;
                case 'AA':
                    $filter['appellate_id'] =new ObjectId($id);
                    break;
                case 'RA':
                    $filter['reviewing_id'] = new ObjectId($id);
                    break;
                default:
                    break;
            }
        }
        //  pre($filter);
     
        if (isset($startDate) && isset($endDate)) {
            $filter["created_at"] = array(
                "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($startDate) * 1000),
                "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($endDate)) * 1000)
            );
        }
        if(!empty( $services)){
            $filter['service_id'] = new ObjectId($services);
        }
        if(!empty( $users)){
            $filter['process_users.userId'] = new ObjectId($users);
        }
        if(!empty($appeal_type)){
            $filter['appeal_type'] = intval($appeal_type);
        }else{
            $filter['appeal_type'] = 1;
        }
// pre($filter);
        // if ($appeal_type == 'second') {
        //     // fetch second appeal data
        //     $filter['ref_appeal_id'] = array('$exists' => true);
        //     $appealApplicationList = $this->appeal_application_model->get_where($filter);
        // } else {
        //     // fetch first appeal data
        //     $filter['ref_appeal_id'] = array('$exists' => false);
        //     $appealApplicationList = $this->appeal_application_model->get_where($filter);
        // }

        $appealApplicationList = $this->appeal_reports_model->get_filtered_appeals($filter);
// pre(  $appealApplicationList );
        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        try {
            $objPHPExcel->setActiveSheetIndex(0);
            // set Header
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Appeal ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Application Ref No');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Applicant Name');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Contact Number');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Email Id');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Name Of Service');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'District');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Office');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Appeal Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Application Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Appeal Type');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Tentative Hearing Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Date of hearing');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'Appeal Description');
            $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'Appilate Authority');
            $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'DPS');
            $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'Reviewing Authority(RA)');
            $objPHPExcel->getActiveSheet()->SetCellValue('R1', 'Registrar(RR)');
            $objPHPExcel->getActiveSheet()->SetCellValue('S1', 'Dealing Assistant');
            $objPHPExcel->getActiveSheet()->SetCellValue('T1', 'Active User');
            $objPHPExcel->getActiveSheet()->SetCellValue('U1', 'Process Status');
            // set Row
            $rowCount = 2;
            foreach ($appealApplicationList as $appealApplication) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $appealApplication->appeal_id);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $appealApplication->appl_ref_no);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $appealApplication->applicant_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $appealApplication->contact_number);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $appealApplication->email_id);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $appealApplication->name_of_service);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $appealApplication->district);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $appealApplication->location->location_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, format_mongo_date( $appealApplication->created_at));
                $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, format_mongo_date( $appealApplication->date_of_application));
                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount,  $appealApplication->appeal_type === 1 ? "FIRST" :"SECOND");
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount,  format_mongo_date($appealApplication->tentative_hearing_date));
                $objPHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, isset($appealApplication->date_of_hearing) ?  format_mongo_date($appealApplication->date_of_hearing) : '' );
                $objPHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $appealApplication->appeal_description );
                $objPHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $this->get_user_name($appealApplication->process_users_new,'AA') );
                $objPHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $this->get_user_name($appealApplication->process_users_new,'DPS') );
                $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $this->get_user_name($appealApplication->process_users_new,'RA') );
                $objPHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, $this->get_user_name($appealApplication->process_users_new,'RR') );
                $objPHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, $this->get_user_name($appealApplication->process_users_new,'DA') );
                $objPHPExcel->getActiveSheet()->SetCellValue('T' . $rowCount, $this->get_active_user_name($appealApplication->process_users_new) );
                $objPHPExcel->getActiveSheet()->SetCellValue('U' . $rowCount, $appealApplication->process_status);
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

    private function get_user_name($process_users_new,$slug){
            foreach($process_users_new as $user){
                if($user->role_slug === $slug){
                    if(isset($user->name)){
                        return $user->name;
                    }else{
                        return '';
                    }
                    
                }
            }
    }
    private function get_active_user_name($process_users_new){
        foreach($process_users_new as $user){
            if($user->active){
                if(isset($user->name)){
                    return $user->name;
                }else{
                    return '';
                }
                
            }
        }
}

    /**
     * @param $actionTo
     * @param array $processInputs
     * @param $appealApplication
     * @return array
     */
    public function configureForwardTo($action,$actionTo, array $processInputs, $appealApplication): array
    {
        $appealApplicationUpdateInputs = array();
        $actionToUser = false;
        $actionToException = false;
        if (isset($actionTo) && $actionTo != "") {
            $previousUser = '';
            switch ($action){
                case 'forward':
                    $processInputs['forward_to'] = $actionTo;
                    break;
                case 'reassign':
                    $processInputs['reassign_to'] = $actionTo;
                    break;
                default:
                    $actionToException = true;
                    return array($appealApplicationUpdateInputs, $processInputs, $actionToUser, $actionToException);
            }
            $actionToUser = $this->users_model->get_by_doc_id($actionTo);
            $roleKey = getRoleKeyById($actionToUser->roleId);
            switch ($roleKey) {
                case 'DPS':
                    if (property_exists($appealApplication, 'dps_details')) {
                        $appealApplicationUpdateInputs['dps_id'] = new ObjectId($actionToUser->{'_id'}->{'$id'});
                        $previousUser = $appealApplication->dps_details->{'_id'};
                    }
                    break;
                case 'AA':
                    if (property_exists($appealApplication, 'appellate_details')) {
                        $appealApplicationUpdateInputs['appellate_id'] = new ObjectId($actionToUser->{'_id'}->{'$id'});
                        $previousUser = $appealApplication->appellate_details->{'_id'};
                    }
                    break;
                case 'RA':
                    if (property_exists($appealApplication, 'reviewer_details')) {
                        $appealApplicationUpdateInputs['reviewing_id'] = new ObjectId($actionToUser->{'_id'}->{'$id'});
                        $previousUser = $appealApplication->reviewer_details->{'_id'};
                    }
                    break;
                default:
                    $actionToException = true;
                    break;
            }
            $processInputs['previous_user'] = $previousUser;
        }
        return array($appealApplicationUpdateInputs, $processInputs, $actionToUser, $actionToException);
    }

    public function search(){
        $searchByAppealId = $this->input->post('searchByAppealId');
        $appeal = $this->appeal_application_model->first_where(['appeal_id' => $searchByAppealId]);

        if($appeal){
            redirect('appeal/view/'.$appeal->{'_id'}->{'$id'});
        }else{
            $this->session->set_flashdata('appeal_search_message', 'No Appeal Found');
            redirect($_SERVER['HTTP_REFERER']);
           // show_error('No Appeal Found',404);
        }
    }

}
