<?php
// echo $this->session->userdata;
// return;
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class First_appeal_process extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->helper('role');
        $this->load->library('form_validation');
        if(!in_array($this->session->userdata('role')->slug,['DA','RR','RA','MOC','AA','DPS'])){
            redirect('appeal/dashboard');
        }

    }

    public function list()
    {
        // pre($this->session->userdata());
        $this->load->view('includes/header');
        $this->load->view('ams/index');
        $this->load->view('includes/footer');
    }

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
        $this->load->model('appeal_application_model');
        $totalData = $this->appeal_application_model->total_rows();
        $totalFiltered = $totalData;

        // pre($totalFiltered);
        // return;


        $filter = array();

        if (empty($this->input->post("search")["value"])) {
            $this->session->set_userdata($filter);
            $records = $this->appeal_application_model->appeals_filter($limit, $start, $filter, $order, $dir,( $appealType == 'second')?2:1);
            $totalFiltered = $this->appeal_application_model->appeals_filter_count(( $appealType == 'second')?2:1);


        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->appeal_application_model->appeals_search_rows($limit, $start, $search, $order, $dir,( $appealType == 'second')?2:1);
            $totalFiltered = $this->appeal_application_model->appeals_tot_search_rows($search,( $appealType == 'second')?2:1);


        }
        $data = array();
        if (!empty((array)$records)) {
            $sl = 1;
            if($appealType == 'second'){
                $recordsAppealIdArray = [];
                foreach ($records as $rows){
                    $recordsAppealIdArray[] = $rows->appeal_id;
                }
                $appealApplicationList = $this->mongo_db->where_in('appeal_id', $recordsAppealIdArray)->get('appeal_applications');
                $process_list = $this->mongo_db->where_in('appeal_id', $recordsAppealIdArray)
                    ->where_in('action',['second-appeal-approve-hearing-order','second-appeal-final-verdict','comment-by-bench-member','second-appeal-create-bench'])
                    ->get('appeal_processes');
                // get approval and not final verdict && get current user commented
            }
            foreach ($records as $rows) {
                $isCurrentUserActive = false;
                foreach ($rows->process_users as $pUser){
                    if(strval($pUser->userId) === $this->session->userdata('userId')->{'$id'} && $pUser->active)
                        $isCurrentUserActive = true;
                }
                $processButtonLabel = 'Process';
                $processButtonClass = 'btn-info';
                if(in_array($rows->process_status,['resolved','rejected']) || !$isCurrentUserActive){
                    $processButtonLabel = 'View';
                    $processButtonClass = 'btn-warning font-weight-bold';
                }

                if($rows->appeal_type == 2){
                    $isHearingApproved = false;
                    $isFinalVerdictGiven = false;
                    $didCurrentUserComment = false;
                    $isCurrentUserDelegateOrChairman = false;
                    foreach ($process_list as $process){
                        if($process->action === 'second-appeal-approve-hearing-order' && $process->appeal_id == $rows->appeal_id){
                            $isHearingApproved = true;
                        }
                        if($process->action === 'second-appeal-final-verdict' && $process->appeal_id == $rows->appeal_id){
                            $isFinalVerdictGiven = true;
                        }
                        if($process->action === 'comment-by-bench-member' && $process->appeal_id == $rows->appeal_id && strval($process->action_taken_by) === $this->session->userdata('userId')->{'$id'}){
                            $didCurrentUserComment = true;
                        }
                        if(property_exists($process,'delegate_to_chairman') && $process->delegate_to_chairman->userId === $this->session->userdata('userId')->{'$id'}){
                            $isCurrentUserDelegateOrChairman = true;
                        }
                    }
                    foreach ($appealApplicationList as $aPL){
                        foreach($aPL->process_users as $pUser){
                            if($pUser->userId == $this->session->userdata('userId')->{'$id'} && !$pUser->active && in_array($pUser->role_slug,['RA','MOC']) && $isHearingApproved && !$isFinalVerdictGiven && !$didCurrentUserComment && !$isCurrentUserDelegateOrChairman){
                                $processButtonLabel = 'Comment';
                                $processButtonClass = 'btn-success font-weight-bold';
                            }
                        }
                    }
                }

                if($rows->appeal_type == 2){
                  $btns = '<a href="' . base_url('appeal/second/process/view/' . $rows->{'_id'}->{'$id'}) . '" class="btn btn-sm '.$processButtonClass.'" title="'.$processButtonLabel.'">'.$processButtonLabel.'</a> ';
                }else {
                  $btns = '<a href="' . base_url('appeal/process/view/' . $rows->{'_id'}->{'$id'}) . '" class="btn btn-sm '.$processButtonClass.'" title="'.$processButtonLabel.'">'.$processButtonLabel.'</a> ';
                }
                //  $btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';

                // <a href="'.base_url("users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
                // <a href="'.base_url("dashboard/users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
                $nestedData["sl_no"] = $sl;
                $nestedData["appeal_id"] = strtoupper($rows->appeal_id);
                $nestedData["name"] = $rows->applicant_name;
                $nestedData["contact_no"] = ($rows->contact_in_addition_contact_number) ? $rows->additional_contact_number : $rows->contact_number;
                // $nestedData["date_of_application"] = format_mongo_date($rows->date_of_application,'d-m-Y');
                // date of application
                $nestedData["appeal_date"] = format_mongo_date($rows->created_at);                $process_status = getProcessStatus($rows->process_status);
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


    // First appeal view
    public function index($ref_id)
    {
        // First process here
        // return pre("Hello");
        $this->load->model('appeal_application_model');
        $this->load->model('users_model');
        $this->load->model('appeal_process_model');
        $appeal = $this->appeal_application_model->get_by_doc_id($ref_id);
        $this->load->helper('model');
        $appealApplication = getAppealApplications($appeal, $appeal->{'_id'}->{'$id'});
//        pre($appealApplication);
        if (!isset($appealApplication) && empty($appealApplication)) {
            redirect(base_url('appeal/list'));
        }
        //pre($appealApplication[0]->ref_appeal_id);
        // if(count($appealApplication))
        $appealApplicationPrevious = isset($appealApplication[0]->ref_appeal_id) ? $this->appeal_application_model->get_with_related_by_appeal_id($appealApplication[0]->ref_appeal_id) : null;
        $appealProcessPreviousList = isset($appealApplication[0]->ref_appeal_id) ? $this->appeal_process_model->get_process_details($appealApplicationPrevious[0]->appeal_id) : null;
        $forwardAbleUserList = $this->users_model->get_users_of_role(); // don't remove
        // get application processes for dps task verification and show
        $appealApplicationProcesses = $this->appeal_process_model->get_process_details($appealApplication[0]->appeal_id);
        $this->load->model('roles_model');
        $reAssignAbleUserFilter = ['slug' => 'DPS'];
        $forwardAbleUserFilter = ['slug' => 'AA'];
        if (property_exists($appealApplication[0], 'appeal_type') && $appealApplication[0]->appeal_type == 2) {
            $reAssignAbleUserFilter = ['slug' => ['$in' => ['DPS', 'AA']]];
            $forwardAbleUserFilter = ['slug' => 'RA'];
        }
        $reAssignAbleUserList = $this->roles_model->get_role_wise_user_list($reAssignAbleUserFilter);
        $forwardAbleUserList = $this->roles_model->get_role_wise_user_list($forwardAbleUserFilter)->{0};


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
            'applicationData' => $appealApplication[0]->application_data ?? null,
            'forwardAbleUserList' => $forwardAbleUserList, // don't remove
            'reAssignAbleUserList' => $reAssignAbleUserList, // don't remove
            'appealApplicationProcesses' => $appealApplicationProcesses,
            'active_user_info' => $active_user_info[0],
        ];
    //    pre($data);
        $this->load->view('includes/header');
        //pre($appealApplication);
        if (property_exists($appealApplication[0], 'appeal_type') && $appealApplication[0]->appeal_type == 2) {
            $this->load->view('ams/process_second_appeal', $data);
        } else {
            $this->load->view('ams/process_appeal', $data);
            // return pre("First Appeal");
        }

        $this->load->view('includes/footer');
    }

    public function process_hearing()
    {
        if ($this->input->post('date_of_hearing', true)) {
            $date_of_hearing = new UTCDateTime(strtotime($this->input->post('date_of_hearing', true)) * 1000);
        }
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->load->helper("fileupload");

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'hearing',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'notifiable' => $this->input->post('notifiable'),
            'order_no' => $this->input->post('order_no'),
            'date_of_hearing' => $date_of_hearing,
            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];

        $this->validate_hearing_action();
        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');
        $appealApplication = getAppealApplications($appeal, $appeal->{'_id'}->{'$id'});
        if (in_array($inputs['notifiable'], array('both', 'dps'))) {
            $inputs['notifiable_dps'] = $appealApplication->dps_details->{'_id'};
        }
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        // check dps or appellant // common
        switch ($inputs['notifiable']) {
            case 'appellant':
                $appendToMsg = ' to appellant.';
                break;
            case 'dps':
                $appendToMsg = ' to DPS';
                break;
            default:
                $appendToMsg = ' to both appellant and DPS';
                break;
        }
        $notificationMsg = 'Notice for hearing' . $appendToMsg;
        $responseMsg = 'Notice for hearing successfully sent' . $appendToMsg;

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'hearing';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        $this->load->model('users_model');
        switch ($inputs['notifiable']) {
            case 'appellant':
                // applicant notification
                foreach ($applicantNotifiable['mobile'] as $contactMobile) {
                    $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
                    $msg = urlencode($msg);
                    $this->sms->send($contactMobile, $msg);
                }
                // send email
                foreach ($applicantNotifiable['email'] as $contactEmail) {
                    $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
                    $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
                }
                break;
            case 'dps':
                $notifiableInfo = $this->users_model->get_by_doc_id($appealApplication->dps_details->{'_id'});
                // sms
                $msg = $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '.';
                $msg = urlencode($msg);
                $this->sms->send($notifiableInfo->mobile, $msg);

                //email
                $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
                $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);
                break;
            default:
                // applicant notification
                foreach ($applicantNotifiable['mobile'] as $contactMobile) {
                    $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
                    $msg = urlencode($msg);
                    $this->sms->send($contactMobile, $msg);
                }
                // send email
                foreach ($applicantNotifiable['email'] as $contactEmail) {
                    $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
                    $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
                }

                $notifiableInfo = $this->users_model->get_by_doc_id($appealApplication->dps_details->{'_id'});
                // sms
                $msg = $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '.';
                $msg = urlencode($msg);
                $this->sms->send($notifiableInfo->mobile, $msg);

                //email
                $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
                $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);
                break;
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    private function validate_hearing_action()
    {
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('notifiable', 'notifiable', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('order_no', 'order_no', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('date_of_hearing', 'date_of_hearing', 'trim|required|xss_clean|strip_tags');
    }

    public function process_seek_info()
    {
        if ($this->input->post('last_date_of_submission', true)) {
            $last_date_of_submission = new UTCDateTime(strtotime($this->input->post('last_date_of_submission', true)) * 1000);
        }
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'seek-info',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'notifiable' => $this->input->post('notifiable'),
            'last_date_of_submission' => $last_date_of_submission,
            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];

        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('notifiable', 'notifiable', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('last_date_of_submission', 'last_date_of_submission', 'trim|required|xss_clean|strip_tags');
        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});

        if (in_array($inputs['notifiable'], array('both', 'dps'))) {
            foreach ($appealApplication as $key => $appealForProcessUser){
                if($appealForProcessUser->process_users->role_slug === 'DPS'){
                     $dpsDetails = $appealForProcessUser->process_users_data;
                }
            }
            $inputs['notifiable_dps'] = $dpsDetails->{'_id'};
        }
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication[0]->contact_number, $appealApplication[0]->email_id, $appealApplication[0]->contact_in_addition_contact_number, $appealApplication[0]->additional_contact_number, $appealApplication[0]->contact_in_addition_email, $appealApplication[0]->additional_email_id);

        $this->load->config('sms_template');
        // check dps or appellant // common
        switch ($inputs['notifiable']) {
            case 'appellant':
                $appendToMsg = ' to appellant.';
                $notificationMsgTemplate = $this->config->item('seek_info_appellant');
                break;
            case 'dps':
                $appendToMsg = ' to DPS';
                $notificationMsgTemplate = $this->config->item('seek_info_dps');
                break;
            default:
                $appendToMsg = ' to both appellant and DPS';
                $notificationMsgTemplate = $this->config->item('seek_info_both');
                break;
        }

        $this->load->config('sms_template');
        $notificationMsg = $notificationMsgTemplate['msg'];
        $responseMsg = 'Information/Document sought successfully' . $appendToMsg;
        $notificationMsgTemplate['msg'] = str_replace("{{appeal_id}}", $inputs['appeal_id'], $notificationMsgTemplate['msg']);
        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        if(in_array($inputs['notifiable'],['both','dps'])){
            $newProcessUser = $appeal->process_users;
            foreach ($appeal->process_users as $key => $processUser){
                if($processUser->role_slug === 'DPS'){
                    $newProcessUser[$key]->active = true;
                }
            }
            $appealApplicationUpdateInputs['process_users'] = $newProcessUser;
        }

        $appealApplicationUpdateInputs['process_status'] = 'seek-info';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        $this->load->model('users_model');
        switch ($inputs['notifiable']) {
            case 'appellant':
                // applicant notification
                foreach ($applicantNotifiable['mobile'] as $contactMobile) {
                    $this->sms->send($contactMobile, $notificationMsgTemplate);
                }
                // send email
                foreach ($applicantNotifiable['email'] as $contactEmail) {
                    $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
                    $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
                }
                break;
            case 'dps':
//                $notifiableInfo = $this->users_model->get_by_doc_id($appealApplication->dps_details->{'_id'});
                $notifiableInfo = $dpsDetails;
                // sms
                $this->sms->send($notifiableInfo->mobile, $notificationMsgTemplate);

                //email
                $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
                $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);
                break;
            default:
                // applicant notification
                foreach ($applicantNotifiable['mobile'] as $contactMobile) {
                    $this->sms->send($contactMobile, $notificationMsgTemplate);
                }
                // send email
                foreach ($applicantNotifiable['email'] as $contactEmail) {
                    $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
                    $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
                }
                $dpsDetails = '';
                foreach ($appealApplication as $eachProcessUser){
                    if($eachProcessUser->process_users->role_slug === 'DPS'){
                        $dpsDetails = $eachProcessUser->process_users_data;
                    }
                }
                if(isset($dpsDetails) && !empty((array)$dpsDetails)){
                    $notifiableInfo = $this->users_model->get_by_doc_id($dpsDetails->{'_id'});
                    // sms
                    $this->sms->send($notifiableInfo->mobile, $notificationMsgTemplate);

                    //email
                    $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
                    $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);
                }

                break;
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_provide_hearing_date()
    {
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $this->load->helper("fileupload");

        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'provide-hearing-date',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'date_of_hearing' => new UTCDateTime(strtotime($this->input->post('date_of_hearing')) * 1000),
            'is_final_hearing' => $this->input->post('isFinalHearing') === 'yes',
            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('date_of_hearing', 'Date of Hearing', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication[0]->contact_number, $appealApplication[0]->email_id, $appealApplication[0]->contact_in_addition_contact_number, $appealApplication[0]->additional_contact_number, $appealApplication[0]->contact_in_addition_email, $appealApplication[0]->additional_email_id);

        $this->load->config('sms_template');
        $msgTemplate = $this->config->item('provide_hearing_date_by_aa');
        $msgTemplate['msg'] = str_replace("{{appeal_id}}", $inputs['appeal_id'], $msgTemplate['msg']);
        $msgTemplate['msg'] = str_replace("{{date_of_hearing}}", $inputs['date_of_hearing'], $msgTemplate['msg']);
        $notificationMsg = $msgTemplate['msg'];
        $responseMsg = 'Appeal hearing date is provided by Appellate Authority.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'provide-hearing-date';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $this->sms->send($contactMobile, $msgTemplate);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_provide_hearing_date_revert_to_da()
    {
      // TODO: need to optimized
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $this->load->helper("fileupload");

        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'provide-hearing-date',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'date_of_hearing' => new UTCDateTime(strtotime($this->input->post('date_of_hearing')) * 1000),
            'is_final_hearing' => $this->input->post('isFinalHearing') === 'yes',
            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('date_of_hearing', 'Date of Hearing', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication[0]->contact_number, $appealApplication[0]->email_id, $appealApplication[0]->contact_in_addition_contact_number, $appealApplication[0]->additional_contact_number, $appealApplication[0]->contact_in_addition_email, $appealApplication[0]->additional_email_id);
        $hrDate=$this->input->post('date_of_hearing');
        $this->load->config('sms_template');
        $msgTemplate = $this->config->item('provide_hearing_date_by_aa');
        $msgTemplate['msg'] = str_replace("{{appeal_id}}", $inputs['appeal_id'], $msgTemplate['msg']);
        $msgTemplate['msg'] = str_replace("{{date_of_hearing}}", $hrDate, $msgTemplate['msg']);
        $notificationMsg = $msgTemplate['msg'];
        $responseMsg = 'Appeal hearing date is provided by Appellate Authority.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'provide-hearing-date';
        $appealApplicationUpdateInputs['date_of_hearing'] = new UTCDateTime(strtotime($this->input->post('date_of_hearing')) * 1000);
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);


        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $this->sms->send($contactMobile, $msgTemplate);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }

        // return $this->output
        //     ->set_content_type('application/json')
        //     ->set_status_header(200)
        //     ->set_output(json_encode(array(
        //         'success' => true,
        //         'msg' => $responseMsg,
        //     )));

        //revert back to DA
        // $revertBackToDAUserId = $this->input->post('revertBackToDAUserId', true);

        // $get_revert_back_to_user_info =  (array)$this->users_model->get_by_id($revertBackToDAUserId); 
        // // print_r($get_user_info[0]);
        // $revert_back_to_email = ($get_revert_back_to_user_info[0]->email);
        // $revert_back_to_mobile = ($get_revert_back_to_user_info[0]->mobile);
        // $revert_back_to_name = ($get_revert_back_to_user_info[0]->name);

        // $revert_inputs = [
        //     'appeal_id' => $this->input->post('appeal_id'),
        //     'action' => 'revert-back-to-da',
        //     'action_taken_by' => new ObjectId($actionTakenBy),
        //     'action_taken_by_email'=> $action_taken_by_email,
        //     'action_taken_by_mobile'=> $action_taken_by_mobile,
        //     'action_taken_by_name'=>  $action_taken_by_name,

        //     'revert_back_to' => new ObjectId($revertBackToDAUserId),
        //     'revert_back_to_email' => $revert_back_to_email,
        //     'revert_back_to_mobile' => $revert_back_to_email,
        //     'revert_back_to_name' => $revert_back_to_name,

        //     'message' => $this->input->post('remarks'),
        //     'documents' => moveFile(0, "file_for_action"),
        //     'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        // ];

        // // $this->load->model('appeal_application_model');
        // // $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        // // $this->load->helper('model');

        // /** modify process user array **/
        // $newProcessUser = getProcessUserArrayOnForward($appeal->process_users, $revertBackToDAUserId);

        // $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});
        // //

        // $this->load->config('sms_template');
        // $msgTemplate = $this->config->item('revert_back_to_da');
        // $msgTemplate['msg'] = str_replace("{{appeal_id}}", $revert_inputs['appeal_id'], $msgTemplate['msg']);
        // $notificationMsg = $msgTemplate['msg'];
        // $responseMsg = 'Appeal reverted back to Dealing Assistant.';

        // $inputs['previous_process_users'] = $appeal->process_users;
        // // $this->load->model('appeal_process_model');
        // $this->appeal_process_model->insert($revert_inputs);

        // $appealApplicationFilters = array(
        //     'appeal_id' => $inputs['appeal_id']
        // );

        // $appealApplicationUpdateInputs['process_status'] = $revert_inputs['action'];
        // $appealApplicationUpdateInputs['process_users']  = $newProcessUser;

        // /** update process user and other appeal_applications info **/
        // $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        // //Active AA user
        // $appealApplicationFiltersAA=[
        //     'appeal_id' => $inputs['appeal_id'],
        //     'process_users.userId' =>new MongoDB\BSON\ObjectId($actionTakenBy)
        // ];
        // //pre($appealApplicationFiltersAA);
        // $this->appeal_application_model->update_where($appealApplicationFiltersAA,array('process_users.$.active'=>true));
        // // pre('da');// notify DA
        // $DADetails = [];
        // foreach ($appealApplication as $appealData){
        //     foreach ($newProcessUser as $processUsr){
        //         $userMatch = strval($processUsr->userId) ===  strval($appealData->process_users->userId);
        //         if($processUsr->role_slug === 'DA' && $processUsr->active && $userMatch){
        //             $DADetails = $appealData->process_users_data;
        //         }
        //     }
        // }

        // $this->load->model('users_model');
        // $notifiableInfo = $DADetails;
        // // sms
        // $this->sms->send($notifiableInfo->mobile, $msgTemplate);

        // //email
        // $emailBody = '<p>' . $notificationMsg . '. Appeal ID :' . $inputs['appeal_id'] . '</p>';
        // $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);

        // // notify appellant
        // foreach ($applicantNotifiable['mobile'] as $contactMobile) {
        //     $this->sms->send($contactMobile, $msgTemplate);
        // }

        // // send email
        // foreach ($applicantNotifiable['email'] as $contactEmail) {
        //     $emailBody = '<p>' . $notificationMsg . '. Appeal ID :' . $inputs['appeal_id'] . '</p>';
        //     $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        // }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));

    }

    public function process_in_progress()
    {
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'in-progress',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];

        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model_helper');
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});
//        if (property_exists($appeal, 'appl_ref_no')) {
//            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id($inputs['appeal_id']);
//        } else {
//            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id_without_ref($inputs['appeal_id']);
//        }
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Appeal in progress.';
        $responseMsg = 'Appeal set to in progress successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'in-progress';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
            $msg = urlencode($msg);
            $this->sms->send($contactMobile, $msg);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }


    public function process_upload_hearing_order()
    {
        $this->load->model('appeal_process_model');
        $filterLastProcessForHearingGeneration = ['appeal_id' => $this->input->post('appeal_id'),'action' => 'generate-hearing-order'];
        $lastProcessForHearing = $this->appeal_process_model->last_where($filterLastProcessForHearingGeneration);

        if(empty((array)$lastProcessForHearing) || !property_exists($lastProcessForHearing->templateContent,'appellant') || !property_exists($lastProcessForHearing->templateContent,'dps')){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => 'Hearing Order not generated!!! Please generate hearing order for both appellant and DPS first.'
                )));
        }

        if(!$this->session->has_userdata('appellant_hearing_order')){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => 'Hearing Order for appellant is not uploaded!!! Please upload Hearing Order first.'
                )));
        }
        if(!$this->session->has_userdata('dps_hearing_order')){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => 'Hearing Order for DPS not uploaded!!! Please upload Hearing Order first.'
                )));
        }
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->load->helper("fileupload");

        
        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);


        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'upload-hearing-order',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

//            'notifiable' => $this->input->post('notifiable'), // todo :: need in future
//            'order_no' => $this->input->post('order_no'), // todo :: need in future
//            'date_of_hearing' => $date_of_hearing, // todo :: need in future
            'message' => $this->input->post('remarks'),
            'appellant_hearing_order' => moveFile(0, "appellant_hearing_order"),
            'dps_hearing_order' => moveFile(0, "dps_hearing_order"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];


        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
//        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
//        if(moveFile(0, "appellant_hearing_order")){
//            $this->form_validation->set_rules('appellant_hearing_order', 'Hearing Order for Appellant ', 'trim|required|xss_clean|strip_tags');
//        }
//        if(moveFile(0, "dps_hearing_order")){
//            $this->form_validation->set_rules('dps_hearing_order', 'Hearing Order for DPS ', 'trim|required|xss_clean|strip_tags');
//        }
        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');

        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'upload-hearing-order';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        $responseMsg = 'Hearing order uploaded.';// todo :: dummy
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_approve_hearing_order(){

        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->load->helper("fileupload");

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        // die("Hello");

        // pre("hello");
        $this->load->model('appeal_process_model');
        $x =  (array)$this->appeal_process_model->get_process_details($this->input->post('appeal_id')); 
        // print_r($x);
        // return;

        $array = json_decode(json_encode($x), true);

        // pre($array);

$appellantDscDocs = "";
$dpsDscDocs = "";

// Loop through the cursor
foreach ($array as $document) {
    // pre($document);
    // Check the value of the 'action' field for each document
    $action = $document['action'];

    if ($action === 'appellant-dsc-sign-generated') {
                // Decode base64 string
        $pdf_data = base64_decode($document['documents']);

        $dir='storage/DOCUMENTS/firstappeal/';
        $output_directory = FCPATH . $dir;
        $output_filename = uniqid() . '.pdf';
        $output_path = $output_directory . $output_filename;
        // Create the output directory if it doesn't exist
        if (!file_exists($output_directory)) {
            mkdir($output_directory);
        }

        file_put_contents($output_path, $pdf_data);
        $appellantDscDocs = file_put_contents($output_path, $pdf_data);
        // pre($output_path);
        $appellantDscDocs = $dir.$output_filename;
    } elseif ($action === 'dps-dsc-sign-generated') {
        $pdf_data =  base64_decode($document['documents']);
        $dir='storage/DOCUMENTS/secondappeal/';
        $output_directory = FCPATH . $dir;
        $output_filename = uniqid() . '.pdf';
        $output_path = $output_directory . $output_filename;
        // Create the output directory if it doesn't exist
        if (!file_exists($output_directory)) {
            mkdir($output_directory);
        }

        file_put_contents($output_path, $pdf_data);
        $dpsDscDocs = file_put_contents($output_path, $pdf_data);
        // pre($output_path);
        $dpsDscDocs = $dir.$output_filename;  
    }
}




$appellantHearingOrder = $appellantDscDocs ?? urldecode($this->input->post('appellantHearingOrder'));

$dpsHearingOrder = $dpsDscDocs ??  urldecode($this->input->post('dpsHearingOrder'));








        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'approve-hearing-order',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'message' => $this->input->post('remarks'),
//            'approved_action_id' => 'upload_hearing_order',
            // 'approved_files' => [
            //     'appellantHearingOrder' => $appellantDscDocs ,
            //     'dpsHearingOrder'       => $dpsDscDocs
            // ],
            'approved_files' => [
                        'appellantHearingOrder' =>$appellantHearingOrder ,
                        'dpsHearingOrder' => $dpsHearingOrder,
            ],

            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('appellantHearingOrder', 'Appellant Hearing Order', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Appellant Hearing Order not found!!!']
        );
        $this->form_validation->set_rules('dpsHearingOrder', 'DPS Hearing Order', 'trim|required|xss_clean|strip_tags',
            ['required' => 'DPS Hearing Order not found!!!']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model_helper');

        // send sms
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication[0]->contact_number, $appealApplication[0]->email_id, $appealApplication[0]->contact_in_addition_contact_number, $appealApplication[0]->additional_contact_number, $appealApplication[0]->contact_in_addition_email, $appealApplication[0]->additional_email_id);

        $this->load->config('sms_template');
        $msgTemplate = $this->config->item('approve_hearing_order');
        $msgTemplate['msg'] = str_replace("{{appeal_id}}", $inputs['appeal_id'], $msgTemplate['msg']);
        $notificationMsg = $msgTemplate['msg'];
        $responseMsg = 'Hearing Order Approved successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'approve-hearing-order';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        $findOrderFilter = [
            'appeal_id'  => $inputs['appeal_id'],
            'order_type' => 'hearing-order'
        ];
        $inputForOrder = [
            'signed_order_for_appellant' => [$inputs['approved_files']['appellantHearingOrder']],
            'signed_order_for_dps' => [$inputs['approved_files']['dpsHearingOrder']],
            'updated_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
        ];
        $this->load->model('order_model');
        $this->order_model->update_where($findOrderFilter,$inputForOrder);

        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $this->sms->send($contactMobile, $msgTemplate);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));


    }


    public function process_upload_disposal_order()
    {
        $this->load->model('appeal_process_model');
        $filterLastProcessForDisposalGeneration = ['appeal_id' => $this->input->post('appeal_id'),'action' => 'generate-disposal-order'];
        $lastProcessForDisposal = $this->appeal_process_model->last_where($filterLastProcessForDisposalGeneration);

        if(empty((array)$lastProcessForDisposal) || !property_exists($lastProcessForDisposal->templateContent,'order')){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => 'Disposal Order not generated!!! Please generate Disposal order first.'
                )));
        }

        if(!$this->session->has_userdata('disposal_order')){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => 'Disposal Order not uploaded!!! Please upload Disposal order first.'
                )));
        }

        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);
        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'upload-disposal-order',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,
//            'notifiable' => $this->input->post('notifiable'), // todo :: need in future
//            'order_no' => $this->input->post('order_no'), // todo :: need in future
//            'date_of_hearing' => $date_of_hearing, // todo :: need in future
            'message' => $this->input->post('remarks'),
            'disposal_order' => moveFile(0, "disposal_order"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];


        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');
//        $appealApplication = getAppealApplications($appeal, $appeal->{'_id'}->{'$id'});
//
//        $this->load->helper('app');
//        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);
//
//        // check dps or appellant // common
//        switch ($inputs['notifiable']) {
//            case 'appellant':
//                $appendToMsg = ' to appellant.';
//                break;
//            case 'dps':
//                $appendToMsg = ' to DPS';
//                break;
//            default:
//                $appendToMsg = ' to both appellant and DPS';
//                break;
//        }
//        $notificationMsg = 'Notice for hearing' . $appendToMsg;
//        $responseMsg = 'Notice for hearing successfully sent' . $appendToMsg;
//
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'upload-disposal-order';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);
//
//        $this->load->model('users_model');
//        switch ($inputs['notifiable']) {
//            case 'appellant':
//                // applicant notification
//                foreach ($applicantNotifiable['mobile'] as $contactMobile) {
//                    $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
//                    $msg = urlencode($msg);
//                    $this->sms->send($contactMobile, $msg);
//                }
//                // send email
//                foreach ($applicantNotifiable['email'] as $contactEmail) {
//                    $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
//                    $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
//                }
//                break;
//            case 'dps':
//                $notifiableInfo = $this->users_model->get_by_doc_id($appealApplication->dps_details->{'_id'});
//                // sms
//                $msg = $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '.';
//                $msg = urlencode($msg);
//                $this->sms->send($notifiableInfo->mobile, $msg);
//
//                //email
//                $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
//                $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);
//                break;
//            default:
//                // applicant notification
//                foreach ($applicantNotifiable['mobile'] as $contactMobile) {
//                    $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
//                    $msg = urlencode($msg);
//                    $this->sms->send($contactMobile, $msg);
//                }
//                // send email
//                foreach ($applicantNotifiable['email'] as $contactEmail) {
//                    $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
//                    $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
//                }
//
//                $notifiableInfo = $this->users_model->get_by_doc_id($appealApplication->dps_details->{'_id'});
//                // sms
//                $msg = $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '.';
//                $msg = urlencode($msg);
//                $this->sms->send($notifiableInfo->mobile, $msg);
//
//                //email
//                $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
//                $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);
//                break;
//        }

        $responseMsg = 'Disposal order uploaded successfully.';// todo :: dummy
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_approve_disposal_order(){

        // pre("Hi");
        // return;
        // disposal-order-dsc-sign-generated
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->load->helper("fileupload");

        
        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);



        $this->load->model('appeal_process_model');
        $x =  (array)$this->appeal_process_model->get_process_details($this->input->post('appeal_id')); 
     

        $array = json_decode(json_encode($x), true);


$disposalOrder = "";

// Loop through the cursor
foreach ($array as $document) {
    // pre($document);
    // Check the value of the 'action' field for each document
    $action = $document['action'];

    if ($action === 'disposal-order-dsc-sign-generated') {
                // Decode base64 string
        $pdf_data = base64_decode($document['documents']);

        $dir='storage/DOCUMENTS/firstappeal/';
        $output_directory = FCPATH . $dir;
        $output_filename = uniqid() . '.pdf';
        $output_path = $output_directory . $output_filename;
        // Create the output directory if it doesn't exist
        if (!file_exists($output_directory)) {
            mkdir($output_directory);
        }

        file_put_contents($output_path, $pdf_data);
        $disposalOrder = file_put_contents($output_path, $pdf_data);
        // pre($output_path);
        $disposalOrder = $dir.$output_filename;
    } 
}




$disposalOrder = $disposalOrder ?? urldecode($this->input->post('disposalOrder'));





        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'resolved',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'message' => $this->input->post('remarks'),
//            'approved_action_id' => 'upload_hearing_order',
            'approved_files' => [
                'disposalOrder' => $disposalOrder,
            ],
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('disposalOrder', 'Disposal Order', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Disposal Order not found!!!']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model_helper');

        // send sms
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication[0]->contact_number, $appealApplication[0]->email_id, $appealApplication[0]->contact_in_addition_contact_number, $appealApplication[0]->additional_contact_number, $appealApplication[0]->contact_in_addition_email, $appealApplication[0]->additional_email_id);

        $this->load->config('sms_template');
        $msgTemplate = $this->config->item('appeal_resolved');
        $msgTemplate['msg'] = str_replace("{{appeal_id}}", $inputs['appeal_id'], $msgTemplate['msg']);
        $notificationMsg = $msgTemplate['msg'];
        $responseMsg = 'Appeal resolved successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'resolved';
        $appealApplicationUpdateInputs['disposal_date'] = $inputs['created_at'];// may needed
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        $findOrderFilter = [
            'appeal_id'  => $inputs['appeal_id'],
            'order_type' => 'disposal-order'
        ];
        $inputForOrder = [
            'signed_disposal_order' => [$inputs['approved_files']['disposalOrder']],
            'updated_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
        ];
        $this->load->model('order_model');
        $this->order_model->update_where($findOrderFilter,$inputForOrder);

        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $this->sms->send($contactMobile, $msgTemplate);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_upload_rejection_order()
    {
        $this->load->model('appeal_process_model');
        $filterLastProcessForRejectionGeneration = ['appeal_id' => $this->input->post('appeal_id'),'action' => 'generate-rejection-order'];
        $lastProcessForRejection = $this->appeal_process_model->last_where($filterLastProcessForRejectionGeneration);

        if(empty((array)$lastProcessForRejection) || !property_exists($lastProcessForRejection->templateContent,'order')){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => 'Rejection Order not generated!!! Please generate Rejection order first.'
                )));
        }
        if(!$this->session->has_userdata('rejection_order')){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => 'Rejection Order for appellant is not uploaded!!! Please upload Rejection Order first.'
                )));
        }
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($this->session->userdata('userId')->{'$id'}); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);


        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'upload-rejection-order',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,
//            'notifiable' => $this->input->post('notifiable'), // todo :: need in future
//            'order_no' => $this->input->post('order_no'), // todo :: need in future
//            'date_of_hearing' => $date_of_hearing, // todo :: need in future
            'message' => $this->input->post('remarks'),
            'rejection_order' => moveFile(0, "rejection_order"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];


        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');
//        $appealApplication = getAppealApplications($appeal, $appeal->{'_id'}->{'$id'});
//
//        $this->load->helper('app');
//        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);
//
//        // check dps or appellant // common
//        switch ($inputs['notifiable']) {
//            case 'appellant':
//                $appendToMsg = ' to appellant.';
//                break;
//            case 'dps':
//                $appendToMsg = ' to DPS';
//                break;
//            default:
//                $appendToMsg = ' to both appellant and DPS';
//                break;
//        }
//        $notificationMsg = 'Notice for hearing' . $appendToMsg;
//        $responseMsg = 'Notice for hearing successfully sent' . $appendToMsg;
//
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'upload-rejection-order';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);
//
//        $this->load->model('users_model');
//        switch ($inputs['notifiable']) {
//            case 'appellant':
//                // applicant notification
//                foreach ($applicantNotifiable['mobile'] as $contactMobile) {
//                    $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
//                    $msg = urlencode($msg);
//                    $this->sms->send($contactMobile, $msg);
//                }
//                // send email
//                foreach ($applicantNotifiable['email'] as $contactEmail) {
//                    $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
//                    $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
//                }
//                break;
//            case 'dps':
//                $notifiableInfo = $this->users_model->get_by_doc_id($appealApplication->dps_details->{'_id'});
//                // sms
//                $msg = $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '.';
//                $msg = urlencode($msg);
//                $this->sms->send($notifiableInfo->mobile, $msg);
//
//                //email
//                $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
//                $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);
//                break;
//            default:
//                // applicant notification
//                foreach ($applicantNotifiable['mobile'] as $contactMobile) {
//                    $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
//                    $msg = urlencode($msg);
//                    $this->sms->send($contactMobile, $msg);
//                }
//                // send email
//                foreach ($applicantNotifiable['email'] as $contactEmail) {
//                    $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
//                    $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
//                }
//
//                $notifiableInfo = $this->users_model->get_by_doc_id($appealApplication->dps_details->{'_id'});
//                // sms
//                $msg = $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '.';
//                $msg = urlencode($msg);
//                $this->sms->send($notifiableInfo->mobile, $msg);
//
//                //email
//                $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
//                $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);
//                break;
//        }

        $responseMsg = 'Rejection order uploaded successfully.';// todo :: dummy
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_approve_rejection_order(){

        // pre("Here");
        // return;
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($this->session->userdata('userId')->{'$id'}); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);


        $this->load->helper("fileupload");


                $this->load->model('appeal_process_model');
        $x =  (array)$this->appeal_process_model->get_process_details($this->input->post('appeal_id')); 
     

        $array = json_decode(json_encode($x), true);



$rejectionOrder = "";

// Loop through the cursor
foreach ($array as $document) {
    // pre($document);
    // Check the value of the 'action' field for each document
    $action = $document['action'];

    if ($action === 'rejection-order-dsc-sign-generated') {
                // Decode base64 string
        $pdf_data = base64_decode($document['documents']);

        $dir='storage/DOCUMENTS/firstappeal/';
        $output_directory = FCPATH . $dir;
        $output_filename = uniqid() . '.pdf';
        $output_path = $output_directory . $output_filename;
        // Create the output directory if it doesn't exist
        if (!file_exists($output_directory)) {
            mkdir($output_directory);
        }

        file_put_contents($output_path, $pdf_data);
       // $rejectionOrder = file_put_contents($output_path, $pdf_data);
        // pre($output_path);
        $rejectionOrder = $dir.$output_filename;
        // pre( $rejectionOrder);
    } 
}


$rejectionOrder = $rejectionOrder ?? urldecode($this->input->post('rejectionOrder'));

        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'rejected',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'message' => $this->input->post('remarks'),
//            'approved_action_id' => 'upload_hearing_order',
            'approved_files' => [
                'rejectionOrder' => $rejectionOrder,
            ],
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('rejectionOrder', 'Rejection Order', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Appellant Rejection Order not found!!!']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model_helper');

        // send sms
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication[0]->contact_number, $appealApplication[0]->email_id, $appealApplication[0]->contact_in_addition_contact_number, $appealApplication[0]->additional_contact_number, $appealApplication[0]->contact_in_addition_email, $appealApplication[0]->additional_email_id);

        $this->load->config('sms_template');
        $msgTemplate = $this->config->item('appeal_rejected');
        $msgTemplate['msg'] = str_replace("{{appeal_id}}", $inputs['appeal_id'], $msgTemplate['msg']);
        $msgTemplate['msg'] = str_replace("{{reason_for_rejection}}", $inputs['message'], $msgTemplate['msg']);
        $notificationMsg = $msgTemplate['msg'];
        $responseMsg = 'Rejection Order Approved successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'rejected';
        $appealApplicationUpdateInputs['disposal_date'] = $inputs['created_at']; // may needed
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        $findOrderFilter = [
            'appeal_id'  => $inputs['appeal_id'],
            'order_type' => 'rejection-order'
        ];
        $inputForOrder = [
            'signed_rejection_order' => [$inputs['approved_files']['rejectionOrder']],
            'updated_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
        ];
        $this->load->model('order_model');
        $this->order_model->update_where($findOrderFilter,$inputForOrder);

        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $this->sms->send($contactMobile, $msgTemplate);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_issue_order()
    {
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'issue-order',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'notifiable' => $this->input->post('notifiable'),
            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];

        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('notifiable', 'notifiable', 'trim|required|xss_clean|strip_tags');
        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        if (property_exists($appeal, 'appl_ref_no')) {
            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id($inputs['appeal_id']);
        } else {
            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id_without_ref($inputs['appeal_id']);
        }
        if (in_array($inputs['notifiable'], array('both', 'dps'))) {
            $inputs['notifiable_dps'] = $appealApplication->dps_details->{'_id'};
        }
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        // check dps or appellant // common
        switch ($inputs['notifiable']) {
            case 'appellant':
                $appendToMsg = ' to appellant.';
                break;
            case 'dps':
                $appendToMsg = ' to DPS';
                break;
            default:
                $appendToMsg = ' to both appellant and DPS';
                break;
        }
        $notificationMsg = 'Order issued.';
        $responseMsg = 'Order issued successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'issue-order';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        $this->load->model('users_model');
        switch ($inputs['notifiable']) {
            case 'appellant':
                // applicant notification
                foreach ($applicantNotifiable['mobile'] as $contactMobile) {
                    $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
                    $msg = urlencode($msg);
                    $this->sms->send($contactMobile, $msg);
                }
                // send email
                foreach ($applicantNotifiable['email'] as $contactEmail) {
                    $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
                    $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
                }
                break;
            case 'dps':
                $notifiableInfo = $this->users_model->get_by_doc_id($appealApplication->dps_details->{'_id'});
                // sms
                $msg = $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '.';
                $msg = urlencode($msg);
                $this->sms->send($notifiableInfo->mobile, $msg);

                //email
                $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
                $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);
                break;
            default:
                // applicant notification
                foreach ($applicantNotifiable['mobile'] as $contactMobile) {
                    $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
                    $msg = urlencode($msg);
                    $this->sms->send($contactMobile, $msg);
                }
                // send email
                foreach ($applicantNotifiable['email'] as $contactEmail) {
                    $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
                    $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
                }

                $notifiableInfo = $this->users_model->get_by_doc_id($appealApplication->dps_details->{'_id'});
                // sms
                $msg = $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '.';
                $msg = urlencode($msg);
                $this->sms->send($notifiableInfo->mobile, $msg);

                //email
                $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
                $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);
                break;
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_reassign()
    {
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('reassignTo', 'Reassign To', 'trim|required|xss_clean|strip_tags');
        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $reassignedTo = $this->input->post('reassignTo', true);
        $get_reassigned_to_user_info =  (array)$this->users_model->get_by_id($reassignedTo); 
        // print_r($get_user_info[0]);
        $reassigned_to_email = ($get_reassigned_to_user_info[0]->email);
        $reassigned_to_mobile = ($get_reassigned_to_user_info[0]->mobile);
        $reassigned_to_name = ($get_reassigned_to_user_info[0]->name);


        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'reassign',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'reassign_to' => new ObjectId($reassignedTo),
            'reassigned_to_email'=>$reassigned_to_email,
            'reassigned_to_mobile'=>$reassigned_to_mobile,
            'reassigned_to_name'=>$reassigned_to_name,

            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        if (property_exists($appeal, 'appl_ref_no')) {
            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id($inputs['appeal_id']);
        } else {
            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id_without_ref($inputs['appeal_id']);
        }

        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Appeal Reassigned.';
        $responseMsg = 'Appeal reassigned successfully.';

        $inputs['previous_user'] = $appealApplication->dps_details->{'_id'};
        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);

        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'reassign';
        $appealApplicationUpdateInputs['dps_id'] = new ObjectId($reassignedTo);

        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        // notifiy dps
        $this->load->model('users_model');
        $notifiableInfo = $this->users_model->get_by_doc_id($appealApplication->dps_details->{'_id'});
        $msg = 'An appeal is reassigned to you. Appeal ID ' . $inputs['appeal_id'] . '.';
        $msg = urlencode($msg);
        $this->sms->send($notifiableInfo->mobile, $msg);
        $emailBody = '<p>Dear Ma&amp;am/Sir,</p><p>An appeal is reassinged to you. Appeal ID' . $inputs['appeal_id'] . '</p>';
        $this->remail->sendemail($notifiableInfo->email, "Appeal Reassigned", $emailBody);

        // notify Appellate
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
            $msg = urlencode($msg);
            $this->sms->send($contactMobile, $msg);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_forward()
    {
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('forwardTo', 'Forward To', 'trim|required|xss_clean|strip_tags');
        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);


        $forwardTo = $this->input->post('forwardTo', true);
        $get_forward_to_user_info =  (array)$this->users_model->get_by_id($forwardTo); 
        // print_r($get_user_info[0]);
        $forward_to_email = ($get_forward_to_user_info[0]->email);
        $forward_to_mobile = ($get_forward_to_user_info[0]->mobile);
        $forward_to_name = ($get_forward_to_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'forward',
            'action_taken_by' => new ObjectId($actionTakenBy),

            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,


            'forward_to' => new ObjectId($forwardTo),
            
            'forward_to_email'=>$forward_to_email,
            'forward_to_mobile'=>$forward_to_mobile,
            'forward_to_name'=>$forward_to_name,

            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        if (property_exists($appeal, 'appl_ref_no')) {
            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id($inputs['appeal_id']);
        } else {
            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id_without_ref($inputs['appeal_id']);
        }

        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Appeal Forwarded';
        $responseMsg = 'Appeal forwarded successfully.';

        $inputs['previous_user'] = $appealApplication->appellate_details->{'_id'};
        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);

        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'forward';
        $appealApplicationUpdateInputs['appellate_id'] = new ObjectId($forwardTo);

        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        // notifiy appellate
        $this->load->model('users_model');
        $notifiableInfo = $this->users_model->get_by_doc_id($appealApplication->appellate_details->{'_id'});
        // sms
        $msg = $notificationMsg . '. Appeal ID :' . $inputs['appeal_id'] . '.';
        $msg = urlencode($msg);
        $this->sms->send($notifiableInfo->mobile, $msg);

        //email
        $emailBody = '<p>' . $notificationMsg . '. Appeal ID :' . $inputs['appeal_id'] . '</p>';
        $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);

        // notify appellant
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $msg = $notificationMsg . '. Appeal ID : ' . $inputs['appeal_id'] . '.';
            $msg = urlencode($msg);
            $this->sms->send($contactMobile, $msg);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . '. Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }

        $notifiableInfo = $this->users_model->get_by_doc_id($appealApplication->dps_details->{'_id'});
        // sms
        $msg = $notificationMsg . '. Appeal ID :' . $inputs['appeal_id'] . '.';
        $msg = urlencode($msg);
        $this->sms->send($notifiableInfo->mobile, $msg);

        //email
        $emailBody = '<p>' . $notificationMsg . '. Appeal ID :' . $inputs['appeal_id'] . '</p>';
        $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    // Forward to AA
    public function process_forward_to_aa()
    {
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('forwardToAAUserId', 'Forward To', 'trim|required|xss_clean|strip_tags');
        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $forwardTo = $this->input->post('forwardToAAUserId', true);

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($this->session->userdata('userId')->{'$id'}); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);


        $get_forward_to_user_info =  (array)$this->users_model->get_by_id($forwardTo); 
        // print_r($get_user_info[0]);
        $forward_to_email = ($get_forward_to_user_info[0]->email);
        $forward_to_mobile = ($get_forward_to_user_info[0]->mobile);
        $forward_to_name = ($get_forward_to_user_info[0]->name);


        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'forward-to-aa',
            // 'phone'=>'000000000',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'forward_to' => new ObjectId($forwardTo),
            'forward_to_email'=>$forward_to_email,
            'forward_to_mobile'=>$forward_to_mobile,
            'forward_to_name'=>$forward_to_name,


            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');

        /** modify process user array **/
        $newProcessUser = getProcessUserArrayOnForward($appeal->process_users, $forwardTo);

        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});

        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication[0]->contact_number, $appealApplication[0]->email_id, $appealApplication[0]->contact_in_addition_contact_number, $appealApplication[0]->additional_contact_number, $appealApplication[0]->contact_in_addition_email, $appealApplication[0]->additional_email_id);

        $this->load->config('sms_template');
        $msgTemplate = $this->config->item('forward_to_aa');
        $msgTemplate['msg'] = str_replace("{{appeal_id}}", $inputs['appeal_id'], $msgTemplate['msg']);
        $notificationMsg = $msgTemplate['msg'];
        $responseMsg = 'Appeal forwarded successfully.';

        $inputs['previous_process_users'] = $appeal->process_users;
        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);

        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'forward-to-aa';
        $appealApplicationUpdateInputs['process_users']  = $newProcessUser;

        /** update process user and other appeal_applications info **/
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        // notify appellate
        $aaDetails = [];
        $DPSDetails = [];
        foreach ($appealApplication as $appealData){
            if($appealData->process_users->role_slug === 'AA'){
                $aaDetails = $appealData->process_users_data;
            }

//            if($appealData->process_users->role_slug === 'DPS'){
//                $DPSDetails = $appealData->process_users_data;
//            }
        }
        $this->load->model('users_model');
        $notifiableInfo = $aaDetails;
        // sms
        $this->sms->send($notifiableInfo->mobile, $msgTemplate);

        //email
        $emailBody = '<p>' . $notificationMsg . '. Appeal ID :' . $inputs['appeal_id'] . '</p>';
        $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);

        // notify appellant
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $this->sms->send($contactMobile, $msgTemplate);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . '. Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }

//        $notifiableInfo = $DPSDetails;
//        // sms
//        $msg = $notificationMsg . '. Appeal ID :' . $inputs['appeal_id'] . '.';
//        $msg = urlencode($msg);
//        $this->sms->send($notifiableInfo->mobile, $msg);
//
//        //email
//        $emailBody = '<p>' . $notificationMsg . '. Appeal ID :' . $inputs['appeal_id'] . '</p>';
//        $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_revert_back_to_da()
    {
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('revertBackToDAUserId', 'Revert Back To DA', 'trim|required|xss_clean|strip_tags');
        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);


        $revertBackToDAUserId = $this->input->post('revertBackToDAUserId', true);
        $get_revert_back_to_user_info =  (array)$this->users_model->get_by_id($revertBackToDAUserId); 
        // print_r($get_user_info[0]);
        $revert_back_to_user_email = ($get_revert_back_to_user_info[0]->email);
        $revert_back_to_user_mobile = ($get_revert_back_to_user_info[0]->mobile);
        $revert_back_to_user_name = ($get_revert_back_to_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'revert-back-to-da',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'revert_back_to' => new ObjectId($revertBackToDAUserId),
            'revert_back_to_email'=>$revert_back_to_user_email,
            'revert_back_to_mobile'=>$revert_back_to_user_mobile,
            'revert_back_to_name'=>$revert_back_to_user_name,


            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');

        /** modify process user array **/
        $newProcessUser = getProcessUserArrayOnForward($appeal->process_users, $revertBackToDAUserId);

        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});

        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication[0]->contact_number, $appealApplication[0]->email_id, $appealApplication[0]->contact_in_addition_contact_number, $appealApplication[0]->additional_contact_number, $appealApplication[0]->contact_in_addition_email, $appealApplication[0]->additional_email_id);

        $this->load->config('sms_template');
        $msgTemplate = $this->config->item('revert_back_to_da');
        $msgTemplate['msg'] = str_replace("{{appeal_id}}", $inputs['appeal_id'], $msgTemplate['msg']);
        $notificationMsg = $msgTemplate['msg'];
        $responseMsg = 'Appeal reverted back to Dealing Assistant.';

        $inputs['previous_process_users'] = $appeal->process_users;
        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);

        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = $inputs['action'];
        $appealApplicationUpdateInputs['process_users']  = $newProcessUser;

        /** update process user and other appeal_applications info **/
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        // notify DA
        $DADetails = [];
        foreach ($appealApplication as $appealData){
            foreach ($newProcessUser as $processUsr){
                $userMatch = strval($processUsr->userId) ===  strval($appealData->process_users->userId);
                if($processUsr->role_slug === 'DA' && $processUsr->active && $userMatch){
                    $DADetails = $appealData->process_users_data;
                }
            }
        }

        $this->load->model('users_model');
        $notifiableInfo = $DADetails;
        // sms
        $this->sms->send($notifiableInfo->mobile, $msgTemplate);

        //email
        $emailBody = '<p>' . $notificationMsg . '. Appeal ID :' . $inputs['appeal_id'] . '</p>';
        $this->remail->sendemail($notifiableInfo->email, "Appeal Processed", $emailBody);

        // notify appellant
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $this->sms->send($contactMobile, $msgTemplate);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . '. Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_generate_penalty_order()
    {
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('order_no', 'Order No.', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('penaltyShouldPayWithinDays', 'Penalty should be paid within', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('certificateIssuedWithinDays', 'Certificate should be issued within', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('numberOfDaysofDelay', 'Number Of Days delayed', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }

        
        $this->load->model('appeal_process_model');
        $lastProcess = ['appeal_id' => $this->input->post('appeal_id'),'action' => 'generate-penalty-order'];
        $lastProcessData = $this->appeal_process_model->last_where($lastProcess);

        if(empty((array)$lastProcessData) ){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => 'Penalty Order not generated!!! Please generate Penalty order first.'
                )));
        }
        if(!$this->session->has_userdata('file_for_action')){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => 'Penalty Order is not uploaded!!! Please upload Penalty Order first.'
                )));
        }
       


        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->load->helper("fileupload");

        
        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);


        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'upload-penalty-order',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,


            'message' => $this->input->post('remarks'),
            'penalty_order' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->load->model('appeal_application_model');
       
        $this->load->model('appeal_process_model');
        $last_penalty_process=$this->appeal_process_model->find_latest_where(array('action'=>"upload-penalty-order","appeal_id"=>$this->input->post('appeal_id')));
        if(!empty($last_penalty_process)){
           $this->appeal_process_model->update_where(array("_id"=>new ObjectId($last_penalty_process->_id->{'$id'})), $inputs);
        }else{
           $this->appeal_process_model->insert($inputs);
        }

        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );
        $appealApplicationUpdateInputs['process_status'] = 'upload-penalty-order';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        
        $responseMsg="Penalty Order Uploaded Successfully";
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_penalize()
    {
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('order_no', 'Order No.', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('penaltyShouldPayWithinDays', 'Penalty should be paid within', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('certificateIssuedWithinDays', 'Certificate should be issued within', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->load->helper("fileupload");

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);



        $totalPenaltyAmount=$this->input->post('penaltyAmount')*$this->input->post('numberOfDaysofDelay');
        if($totalPenaltyAmount > 25000 ){
            $totalPenaltyAmount=25000;
        }
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'penalize',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,


            // 'order_no' => $this->input->post('order_no'),
            'penalty_amount' => $this->input->post('penaltyAmount'),
            'penalty_should_by_paid_within_days' => $this->input->post('penaltyShouldPayWithinDays'),
            'certificate_to_be_issued_within_days' => $this->input->post('certificateIssuedWithinDays'),
            'number_of_days_of_delay'=>$this->input->post('numberOfDaysofDelay'),
            'total_penalty_amount'=>$totalPenaltyAmount,
            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});
        // $appealApplication=!empty($appealApplication)?$appealApplication[0]:array();
        // pre($appealApplication);
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication[0]->contact_number, $appealApplication[0]->email_id, $appealApplication[0]->contact_in_addition_contact_number, $appealApplication[0]->additional_contact_number, $appealApplication[0]->contact_in_addition_email, $appealApplication[0]->additional_email_id);

        $notificationMsg = 'Penalty imposed. Penalty amount ' . $inputs['total_penalty_amount'];
        $responseMsg = 'Penalized successfully.';
        $dps_id="";
        foreach($appealApplication as $pro_users){
            if($pro_users->process_users->role_slug ==="DPS"){
                $dps_id=strval($pro_users->process_users->userId);
            }
        }
        $inputs['penalty_to_user'] = $dps_id;//$appealApplication->dps_details->{'_id'};
        $this->load->model('appeal_process_model');
        $last_penalty_process=$this->appeal_process_model->find_latest_where(array('action'=>"penalize","appeal_id"=>$this->input->post('appeal_id')));
         if(!empty($last_penalty_process)){
            $this->appeal_process_model->update_where(array("_id"=>new ObjectId($last_penalty_process->_id->{'$id'})), $inputs);
         }else{
            $this->appeal_process_model->insert($inputs);
         }
      
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'penalize';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);
       
        $this->load->model('users_model');
        $penaltyToUserInfo = $this->users_model->get_by_doc_id($inputs['penalty_to_user']);
        // sms
        $penaltyTo = 'DPS';
        $msg = $notificationMsg . $penaltyTo . ' name: ' . $penaltyToUserInfo->name . ' and penalty should be paid with in ' . $inputs['penalty_should_by_paid_within_days'] . '.days. Appeal ID : ' . $inputs['appeal_id'] . '.';
        $msg = urlencode($msg);
        $this->sms->send($penaltyToUserInfo->mobile, $msg);

        //email
        $emailBody = '<p>' . $notificationMsg . $penaltyTo . ' name: ' . $penaltyToUserInfo->name . ' and penalty should be paid with in ' . $inputs['penalty_should_by_paid_within_days'] . '.days. Appeal ID :' . $inputs['appeal_id'] . '</p>';
        $this->remail->sendemail($penaltyToUserInfo->email, $notificationMsg, $emailBody);

        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
            $msg = urlencode($msg);
            $this->sms->send($contactMobile, $msg);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_reject()
    {
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->load->helper("fileupload");

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy);

        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'rejected',
            'order_no' => $this->input->post('order_no'),
            'action_taken_by' => new ObjectId($actionTakenBy),

            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,


            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];

        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('order_no', 'Order No.', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        if (property_exists($appeal, 'appl_ref_no')) {
            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id($inputs['appeal_id']);
        } else {
            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id_without_ref($inputs['appeal_id']);
        }
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Appeal rejected. Reason : ' . $inputs['message'];
        $responseMsg = 'Appeal rejected successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'rejected';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        $this->load->model('users_model');
        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
            $msg = urlencode($msg);
            $this->sms->send($contactMobile, $msg);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_dispose()
    {
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->load->helper("fileupload");

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'resolved',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,


            'order_no' => $this->input->post('order_no'),
            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];

        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('order_no', 'Order No.', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        if (property_exists($appeal, 'appl_ref_no')) {
            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id($inputs['appeal_id']);
        } else {
            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id_without_ref($inputs['appeal_id']);
        }
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Appeal resolved.';
        $responseMsg = 'Appeal resolved successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'resolved';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
            $msg = urlencode($msg);
            $this->sms->send($contactMobile, $msg);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_dps_reply(){
        if($this->session->userdata("role")->slug !== 'DPS'){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false
                )));
        }
        $message = $this->input->post('remarks', true);
        $appeal_id = $this->input->post('appeal_id');
        //        $appeal_id = str_replace('%','/',$appeal_id);
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

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
        $action = 'dps-reply';
        $processInputs = array(
            'appeal_id' => $appeal_id,
            'action' => $action,
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'message' => $message,
            'comment' => null,
            'documents' => moveFile(0, "file_for_dps_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            'updated_at' => null
        );
        $this->load->model('appeal_application_model');
        $appealApplication = $this->appeal_application_model->first_where('appeal_id', $appeal_id);

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($processInputs);
        $appealApplicationFilters = array(
            'appeal_id' => $appeal_id
        );
        $appellateUrlId = '';
        $newProcessUser = $appealApplication->process_users;
        foreach ($appealApplication->process_users as $key => $processUser){
            if($processUser->role_slug === 'DPS'){
                $newProcessUser[$key]->active = false;
            }
            if($processUser->role_slug === 'AA'){
                $appellateUrlId = strval($processUser->userId);
            }
        }
        $appealApplicationUpdateInputs['process_users'] = $newProcessUser;
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
        $this->load->model('users_model');
        $appellateInfo = $this->users_model->get_by_doc_id($appellateUrlId);
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
    }

    public function generate_action_view()
    {
        $page = $this->input->post("view");
        if ($page === "in-progress") {
            $page = "in_progres";
        }
        if ($page === "issue-order") {
            $page = "issue_order";
        }
        if ($page === "issue-penalty-order") {
            $page = "issue_penalty_order";
        }
     
        $data['name'] = urldecode($this->input->post("name"));
        $data['appealId'] = urldecode($this->input->post("appealId"));
        $data['forwardAbleUserOptionList'] = urldecode($this->input->post("forwardAbleUserOptionList"));
        $data['reAssignAbleUserOptionList'] = urldecode($this->input->post("reAssignAbleUserOptionList"));
        $data['aaName'] = urldecode($this->input->post("aaName"));
        $data['aaUserId'] = urldecode($this->input->post("aaUserId"));
        $daArray =html_entity_decode( $this->input->post("daArray"));

        if(   $daArray){
            $jsonData = stripslashes(html_entity_decode($this->input->post("daArray")));

            $daUsers=json_decode($jsonData,true);
            $data['daArrayCheckBox']='';
            foreach($daUsers as $da){
                $data['daArrayCheckBox'] .='<input type="radio"   name="revertBackToDAUserId" value='.$da['_id']['$oid'].' required="required">
                <label for=""> '.$da['name'].' </label><br>';
            }

           
        }
        $this->load->model('appeal_process_model');
        $lastProcessByOtherUser = $this->appeal_process_model->last_where(['action' => 'forward-to-aa']);

        $this->load->model('users_model');
        if(!empty($lastProcessByOtherUser)){
            $lastProcessUser = $this->users_model->get_by_doc_id(strval($lastProcessByOtherUser->{'action_taken_by'}));
        }

        if(in_array($page,['provide-hearing-date'])){
            // todo : get hearing count if available
            $data['isFirstHearing'] = true; // todo check in db and  change later
            $data['appealDate'] = urldecode($this->input->post("appealDate"));
            $data['tentative_hearing_date'] = urldecode($this->input->post("tentative_hearing_date"));
            $data['daUserId']  = $lastProcessUser->{'_id'}->{'$id'};
        }

        if(in_array($page,['revert-back-to-da'])){
            $data['daName']  = $lastProcessUser->name;
            $data['daUserId']  = $lastProcessUser->{'_id'}->{'$id'};
        }

        if(in_array($page,['upload-hearing-order'])){
            // check and get generated hearing order if available
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $data['appealId'],'action' => 'generate-hearing-order']);
            if($latestProcess){
                $data['hearingOrderTemplates'] = $latestProcess->templateContent;
                $data['action']='generate-hearing-order';
            }
        }
        if($page === 'approve-hearing-order'){
            // check and get generated hearing order if available
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $data['appealId'],'action' => 'upload-hearing-order']);
            $data['appellantHearingOrder'] = $latestProcess->appellant_hearing_order ?? '';
            $data['dpsHearingOrder'] = $latestProcess->dps_hearing_order ?? '';
        }

        if(in_array($page,['upload-disposal-order'])){
            // check and get generated disposal order if available
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $data['appealId'],'action' => 'generate-disposal-order']);
            if($latestProcess){
                $data['disposalOrderTemplates'] = $latestProcess->templateContent;
            }
        }

        if($page === 'resolved'){
            // check and get generated disposal order if available
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $data['appealId'],'action' => 'upload-disposal-order']);
            $data['disposalOrder'] = $latestProcess->disposal_order ?? '';
        }
        if(in_array($page,['upload-rejection-order'])){
            // check and get generated rejection order if available
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $data['appealId'],'action' => 'generate-rejection-order']);
            if($latestProcess){
                $data['rejectionOrderTemplates'] = $latestProcess->templateContent;
            }
        }
        if($page === 'rejected'){
            // check and get generated rejection order if available
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $data['appealId'],'action' => 'upload-rejection-order']);
            $data['rejectionOrder'] = $latestProcess->rejection_order ?? '';
        }
        echo $this->load->view("ams/action_templates/view_templates/" . $page, $data);
    }

    public function download_generated_template($appealId,$forUserType,$action=""){

        $this->load->model('appeal_process_model');
        if($action === 'upload-hearing-order'){
            $action = 'generate-hearing-order';
        }
        if($action === 'upload-disposal-order'){
            $action = 'generate-disposal-order';
        }
        if($action === 'upload-rejection-order'){
            $action = 'generate-rejection-order';
        }
        $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $appealId,'action' => $action]);
        
        if(isset($latestProcess->templateContent)){
            $this->load->library('pdf');
            if($forUserType === "penalty"){
                // pre($latestProcess->action );
                $this->pdf->generate($latestProcess->templateContent,$latestProcess->action.'_','D');
            }else{
                $this->pdf->generate($latestProcess->templateContent->{$forUserType},$latestProcess->action.'_'.$forUserType.'_','D');
            }
            
            exit();
        }
        show_404();

    }
    public function process_issue_penalty_order()
    {
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->load->helper("fileupload");

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 

        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);
        
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'approve-penalty-order',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,


            'message' => $this->input->post('remarks'),
            'approved_files' => [
                'penaltyOrder' => urldecode($this->input->post('penaltyOrder'))
            ],
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];

        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        

        $this->load->model('appeal_process_model');
        $this->load->model('appeal_application_model');
        $last_penalty_process=$this->appeal_process_model->find_latest_where(array('action'=>"approve-penalty-order","appeal_id"=>$this->input->post('appeal_id')));
        if(!empty($last_penalty_process)){
            $this->appeal_process_model->update_where(array("_id"=>new ObjectId($last_penalty_process->_id->{'$id'})), $inputs);
        }else{
            $this->appeal_process_model->insert($inputs);
        }
        
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'approve-penalty-order';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        $responseMsg = 'Penalty Order Approved successfully.';

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_final_verdict(){
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->load->helper("fileupload");

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'final-verdict',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'date_of_final_verdict' => new UTCDateTime(strtotime($this->input->post('date_of_final_verdict')) * 1000),
            'message' => $this->input->post('remarks'),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');

        if ($this->form_validation->run() == FALSE) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors('<li>', '</li>')
                )));
        }
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);

        // check if all bench member have commented (other than chairman or authorized member)
        $this->load->model('appeal_process_model');
        $processesToCheck = $this->mongo_db
            ->where_in('action',['comment-by-bench-member','second-appeal-create-bench'])
            ->where('appeal_id', $this->input->post('appeal_id'))
            ->get('appeal_processes');
        $benchMemberCount = 0;
        $otherMOCCount = 0;
        foreach ($appeal->process_users as $appealPUser){
            if(in_array($appealPUser->role_slug,['RA','MOC'])){
                $benchMemberCount++;
            }
            foreach ($processesToCheck as $checkForMocComment){
                if(in_array($appealPUser->role_slug,['RA','MOC']) && $checkForMocComment->action === 'comment-by-bench-member' && strval($appealPUser->userId) == strval($checkForMocComment->action_taken_by)){
                    $otherMOCCount++;
                }
            }
        }
        if($benchMemberCount !== $otherMOCCount+1){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => 'Error #1'
                )));
        }
        $this->load->helper('model_helper');
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'})[0];
//        if (property_exists($appeal, 'appl_ref_no')) {
//            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id($inputs['appeal_id']);
//        } else {
//            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id_without_ref($inputs['appeal_id']);
//        }
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Final verdict submitted.';
        $responseMsg = 'Final verdict submitted successfully.';

        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-final-verdict';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
            $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
            $msg = urlencode($msg);
            $this->sms->send($contactMobile, $msg);
        }
        // send email
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }
}
