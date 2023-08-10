<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Second_appeal_process extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->helper('role');
        $this->load->library('form_validation');

    }

    public function list()
    {
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
                //  $btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
                $btns = '<a href="' . base_url('appeal/process/view/' . $rows->{'_id'}->{'$id'}) . '" class="btn btn-sm btn-info" title="Process">Process</a> ';
                // <a href="'.base_url("users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
                // <a href="'.base_url("dashboard/users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
                $nestedData["sl_no"] = $sl;
                $nestedData["appeal_id"] = strtoupper($rows->appeal_id);
                $nestedData["name"] = $rows->applicant_name;
                $nestedData["contact_no"] = ($rows->contact_in_addition_contact_number) ? $rows->additional_contact_number : $rows->contact_number;
                $nestedData["appeal_date"] = format_mongo_date($rows->created_at);
                // $nestedData["date_of_application"] = format_mongo_date($rows->date_of_application,'d-m-Y');

                $process_status = getProcessStatus($rows->process_status);
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

    public function index($ref_id)
    {
        // return pre("Hello");
        $this->check_commission_mapping($ref_id);
        $this->load->model('appeal_application_model');
        $this->load->model('users_model');
        $this->load->model('appeal_process_model');
        $appeal = $this->appeal_application_model->get_by_doc_id($ref_id);
        $this->load->helper('model');
        $this->load->model('appeal_application_model');
        $appealApplication = $this->appeal_application_model->get_appeal_details_for_everyone($ref_id);
        // pre($ref_id);
        $appealApplication = getAppealApplications($appeal, $appeal->{'_id'}->{'$id'});
         //pre($appealApplication);//appeal_id
        if (!isset($appealApplication) && empty($appealApplication)) {
            redirect(base_url('appeal/list'));
        }

        $appealApplicationPrevious = isset($appealApplication[0]->ref_appeal_id) ? $this->appeal_application_model->get_with_related_by_appeal_id($appealApplication[0]->ref_appeal_id) : null;
        if(empty($appealApplicationPrevious) && isset($appealApplication[0]->ref_appeal_id)){
            $appealApplicationPrevious = isset($appealApplication[0]->ref_appeal_id) ? $this->appeal_application_model->get_with_related_by_appeal_id_no_application_data($appealApplication[0]->ref_appeal_id) : null;

        }
        // pre($appealApplicationPrevious[0]->appeal_id);//appeal_id
        $appealProcessPreviousList = isset($appealApplication[0]->ref_appeal_id) ? $this->appeal_process_model->get_process_details($appealApplicationPrevious[0]->appeal_id) : null;

        //pre($appealProcessPreviousList);
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

        // Previous
//         if(!empty($appealApplicationPrevious)){
// //            $finalVerdictInfoForPreviousAppeal = $this->appeal_process_model->get_where([
// //                'appeal_id' => $appealApplicationPrevious[0]->appeal_id,
// //                'action' => 'final-verdict'
// //            ]);
//             $finalVerdictInfoForPreviousAppeal = $this->mongo_db->where('appeal_id',$appealApplicationPrevious[0]->appeal_id)->where_in('action',['resolved','rejected'])->get('appeal_processes')->{0};
//         }


        // New
        if(isset($this->mongo_db->where('appeal_id',$appealApplicationPrevious[0]->appeal_id)->where_in('action',['resolved','rejected'])->get('appeal_processes')->{0})){
            $finalVerdictInfoForPreviousAppeal = $this->mongo_db->where('appeal_id',$appealApplicationPrevious[0]->appeal_id)->where_in('action',['resolved','rejected'])->get('appeal_processes')->{0};
        }


        $lastAppealProcess =  $this->appeal_process_model->last_where(array('appeal_id' =>$appealApplication[0]->appeal_id));
        // pre( $lastAppealProcess->action);
        // if(!empty()){
        //     $action=$lastAppealProcess->action;
        // }else{

        // }o
       
        $process_actions=processHierarchy( $lastAppealProcess->action ?? "",$appealApplication[0]->appeal_id,$this->session->userdata("role")->slug);
        //pre( $lastAppealProcess);



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
            'applicationData' => $appealApplication[0]->application_details ?? null,
            'forwardAbleUserList' => $forwardAbleUserList, // don't remove
            'reAssignAbleUserList' => $reAssignAbleUserList, // don't remove
            'appealApplicationProcesses' => $appealApplicationProcesses,
            'finalVerdictInfoForPreviousAppeal' => $finalVerdictInfoForPreviousAppeal??[],
            'process_action'=>$process_actions,
            'active_user_info' => $active_user_info[0],
            
        ];
      //  pre($data);
        $this->load->view('includes/header');
        //pre($appealApplication);
          $this->load->view('ams/process_second_appeal', $data);
        // if (property_exists($appealApplication[0], 'appeal_type') && $appealApplication[0]->appeal_type == 2) {
        //     $this->load->view('ams/process_second_appeal', $data);
        // } else {
        //     $this->load->view('ams/process_appeal', $data);
        // }

        $this->load->view('includes/footer');
    }
    private function check_commission_mapping($ref_id){
        $this->load->model('appeal_application_model');
        $this->load->model('commission_model');
        $appeal_application=$this->appeal_application_model->get_by_doc_id($ref_id);
      
        $activeCommission = $this->commission_model->first_where([]);
        if($appeal_application->process_users){
            foreach($appeal_application->process_users as $key=>$user){
                if($user->role_slug === "RR"){
                    //check registrar
                    if(strval($user->userId) !== strval($activeCommission->registrar)){
                        
                        $this->appeal_application_model->update_where(
                            ['_id'=>new ObjectId($ref_id)],
                            ['process_users.'.$key.'.userId'=>new ObjectId(strval($activeCommission->registrar))]
                        );
                        
                    }
                }

                if($user->role_slug === "RA"){
                    // check reviewing authority
                    if(strval($user->userId) !== strval($activeCommission->reviewing_authority)){
                        $this->appeal_application_model->update_where(
                            ['_id'=>new ObjectId($ref_id)],
                            ['process_users.'.$key.'.userId'=>new ObjectId(strval($activeCommission->reviewing_authority))]
                        );
                    }
                }
            }
        }
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
            'action' => 'second-appeal-seek-info',
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
        $notificationMsg = 'Seeking Information or document' . $appendToMsg;
        $responseMsg = 'Information/Document sought successfully' . $appendToMsg;

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

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-seek-info';
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
//                $notifiableInfo = $this->users_model->get_by_doc_id($appealApplication->dps_details->{'_id'});
                $notifiableInfo = $dpsDetails;
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

        $notificationMsg = 'Appeal hearing date is provided.';
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
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->load->helper("fileupload");

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

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
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-upload-hearing-order',
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
        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-upload-hearing-order';
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
    //    pre($this->input->post());
    //    return;
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->load->helper("fileupload");

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-approve-hearing-order',
            'action_taken_by' => new ObjectId($actionTakenBy),

            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,


            'message' => $this->input->post('remarks'),
//            'approved_action_id' => 'upload_hearing_order',
            'approved_files' => [
                'appellantHearingOrder' => urldecode($this->input->post('appellantHearingOrder')),
                'dpsHearingOrder'       => urldecode($this->input->post('dpsHearingOrder'))
            ],
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
        // todo :: add notification later
//        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});
//        $this->load->helper('app');
//        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Hearing Order Approved.';
        $responseMsg = 'Hearing Order Approved successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-approve-hearing-order';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

//        // applicant notification
//        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
//            $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
//            $msg = urlencode($msg);
//            $this->sms->send($contactMobile, $msg);
//        }
//        // send email
//        foreach ($applicantNotifiable['email'] as $contactEmail) {
//            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
//            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
//        }

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
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);


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
        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-upload-disposal-order',
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
//        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');
        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-upload-disposal-order';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

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
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};
        $this->load->helper("fileupload");

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 

        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-approve-disposal-order',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'message' => $this->input->post('remarks'),
//            'approved_action_id' => 'upload_hearing_order',
            'approved_files' => [
                'disposalOrder' => urldecode($this->input->post('disposalOrder'))
            ],
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
//        $this->form_validation->set_rules('disposalOrder', 'Disposal Order', 'trim|required|xss_clean|strip_tags',
//            ['required' => 'Disposal Order not found!!!']
//        );
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
        // todo :: add notification later
//        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});
//        $this->load->helper('app');
//        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Appeal Resolved.';
        $responseMsg = 'Appeal resolved successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-approve-disposal-order';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

//        // applicant notification
//        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
//            $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
//            $msg = urlencode($msg);
//            $this->sms->send($contactMobile, $msg);
//        }
//        // send email
//        foreach ($applicantNotifiable['email'] as $contactEmail) {
//            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
//            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
//        }

        $this->load->model('order_model');
        $this->order_model->update_where(['appeal_id' => $inputs['appeal_id'],'order_type' => 'disposal-order'],['is_approved' => true]);
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

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 

        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-upload-rejection-order',
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
        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-upload-rejection-order';
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
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);



        $this->load->helper("fileupload");

        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-approve-rejection-order',
            'action_taken_by' => new ObjectId($actionTakenBy),

            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            
            'message' => $this->input->post('remarks'),
//            'approved_action_id' => 'upload_hearing_order',
            'approved_files' => [
                'rejectionOrder' => urldecode($this->input->post('rejectionOrder'))
            ],
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
//        $this->form_validation->set_rules('rejectionOrder', 'Rejection Order', 'trim|required|xss_clean|strip_tags',
//            ['required' => 'Appellant Rejection Order not found!!!']
//        );
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
        // todo :: add notification later
//        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});
//        $this->load->helper('app');
//        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Appeal Rejected. Reason : ' . $inputs['message'];
        $responseMsg = 'Rejection Order Approved successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-approve-rejection-order';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

//        // applicant notification
//        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
//            $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
//            $msg = urlencode($msg);
//            $this->sms->send($contactMobile, $msg);
//        }
//        // send email
//        foreach ($applicantNotifiable['email'] as $contactEmail) {
//            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
//            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
//        }

        $this->load->model('order_model');
        $this->order_model->update_where(['appeal_id' => $inputs['appeal_id'],'order_type' => 'rejection-order'],['is_approved' => true]);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }
    public function process_issue_rejection_order(){

        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'rejected',
            'action_taken_by' => new ObjectId($actionTakenBy),
            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'rejection_order' => moveFile(0, "signed_rejection_order"),
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
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'})[0];
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Appeal rejection order issued.';
        $responseMsg = 'Appeal rejection order issued successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);

        // update signed rejection order in orders
        $this->load->model('order_model');
        $this->order_model->update_where(['appeal_id' => $this->input->post('appeal_id'),'order_type' => 'rejection-order' ],['signed_rejection_order' => $inputs['rejection_order'],'is_issued' => true]);

        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'rejected';
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
    public function process_issue_hearing_order(){

        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $this->load->helper("fileupload");
        // if(!$this->session->has_userdata('signed_order_for_appellant')){
        //     return $this->output
        //         ->set_content_type('application/json')
        //         ->set_status_header(200)
        //         ->set_output(json_encode(array(
        //             'success' => false,
        //             'error_msg' => 'Hearing Order for appellant is not uploaded!!! Please upload the signed Hearing Order first.'
        //         )));
        // }
        // if(!$this->session->has_userdata('signed_order_for_dps')){
        //     return $this->output
        //         ->set_content_type('application/json')
        //         ->set_status_header(200)
        //         ->set_output(json_encode(array(
        //             'success' => false,
        //             'error_msg' => 'Hearing Order for DPS not uploaded!!! Please upload the signed Hearing Order first.'
        //         )));
        // }
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


        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-issue-hearing-order',
            'action_taken_by' => new ObjectId($actionTakenBy),

            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'signed_appellant_hearing_order' => moveFile(0, "signed_order_for_appellant"),
            'signed_dps_hearing_order' => moveFile(0, "signed_order_for_dps"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model_helper');
        // todo :: add notification later
//        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});
//        $this->load->helper('app');
//        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Appeal hearing order issued.';
        $responseMsg = 'Appeal  hearing order issued successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        // update signed disposal order in orders

        $this->load->model('appeal_process_model');
        $data['lastHearingConfirmed'] = $this->appeal_process_model->last_where(['appeal_id' => $this->input->post('appeal_id'),'action' => 'second-appeal-confirm-hearing-date']);
        $this->load->model('order_model');
        $latestHearingOrders = $this->order_model->last_where(['appeal_id' => $inputs['appeal_id'],'order_type' => 'hearing-order','confirmed_hearing_date_process_id' => new ObjectId($data['lastHearingConfirmed']->_id->{'$id'})]);

        $this->load->model('order_model');
        $this->order_model->update_where(['_id' =>  new ObjectId($latestHearingOrders->_id->{'$id'})],
            [
            'signed_order_for_appellant' => $inputs['signed_appellant_hearing_order'],
            'signed_order_for_dps' => $inputs['signed_dps_hearing_order'],
            'is_issued' => true
            ]
        );
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-issue-hearing-order';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

//        // applicant notification
//        foreach ($applicantNotifiable['mobile'] as $contactMobile) {
//            $msg = $notificationMsg . ' Appeal ID : ' . $inputs['appeal_id'] . '.';
//            $msg = urlencode($msg);
//            $this->sms->send($contactMobile, $msg);
//        }
//        // send email
//        foreach ($applicantNotifiable['email'] as $contactEmail) {
//            $emailBody = '<p>' . $notificationMsg . ' Appeal ID :' . $inputs['appeal_id'] . '</p>';
//            $this->remail->sendemail($contactEmail, $notificationMsg, $emailBody);
//        }

        // send email and sms for hearing notice to all relevant ...
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }
    public function process_issue_disposal_order(){
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        
        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'resolved',
            'action_taken_by' => new ObjectId($actionTakenBy),

            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'disposal_order' => moveFile(0, "signed_disposal_order"),
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
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'})[0];
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Appeal disposal order issued.';
        $responseMsg = 'Appeal disposal order issued successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);

        // update signed disposal order in orders
        $this->load->model('order_model');
        $this->order_model->update_where(['appeal_id' => $this->input->post('appeal_id'),'order_type' => 'disposal-order' ],['signed_disposal_order' => $inputs['disposal_order'],'is_issued' => true]);


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
    public function process_seek_info_from_other(){
      echo "process for seeking info ";die;
    }
    public function process_revert_back_to_rr(){
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('revertBackToRRUserId', 'Revert Back To Registrar', 'trim|required|xss_clean|strip_tags',['required' => 'Unable to process!!! Please refresh the page and try again.']);
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
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $revertBackToRRUserId = $this->input->post('revertBackToRRUserId', true);

        $get_revert_back_to_user_info =  (array)$this->users_model->get_by_id($revertBackToRRUserId); 
        // print_r($get_user_info[0]);
        $revert_back_to_user_email = ($get_revert_back_to_user_info[0]->email);
        $revert_back_to_user_mobile = ($get_revert_back_to_user_info[0]->mobile);
        $revert_back_to_user_name = ($get_revert_back_to_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-revert-back-to-rr',
            'action_taken_by' => new ObjectId($actionTakenBy),

            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'revert_back_to' => new ObjectId($revertBackToRRUserId),

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
        $newProcessUser = getProcessUserArrayOnForward($appeal->process_users, $revertBackToRRUserId);

        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});

        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication[0]->contact_number, $appealApplication[0]->email_id, $appealApplication[0]->contact_in_addition_contact_number, $appealApplication[0]->additional_contact_number, $appealApplication[0]->contact_in_addition_email, $appealApplication[0]->additional_email_id);

        $notificationMsg = 'Appeal Reverted Back';
        $responseMsg = 'Appeal reverted back to Registrar.';

        $inputs['previous_process_users'] = $appeal->process_users;
        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);

        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = $inputs['action'];
        $appealApplicationUpdateInputs['process_users']  = $newProcessUser;

        /** update process user and other appeal_applications info **/
        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-revert-back-to-rr';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        // notify DA
        $rrDetails = [];
        foreach ($appealApplication as $appealData){
            foreach ($newProcessUser as $processUsr){
                $userMatch = strval($processUsr->userId) ===  strval($appealData->process_users->userId);
                if($processUsr->role_slug === 'RR' && $processUsr->active && $userMatch){
                    $rrDetails = $appealData->process_users_data;
                }
            }
        }

        $this->load->model('users_model');
        $notifiableInfo = $rrDetails;
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

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }
    public function process_change_hearing_date(){
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $dateOfHearing = new UTCDateTime(strtotime(date("d-m-Y H:i:s",strtotime($this->input->post('date_of_hearing')))) * 1000);
        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-change-hearing-date',
            'action_taken_by' => new ObjectId($actionTakenBy),

            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'date_of_hearing' => $dateOfHearing,
            'message' => $this->input->post('remarks'),
            'documents' => moveFile(0, "file_for_action"),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];

        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('date_of_hearing', 'Date of Hearing', 'trim|required|xss_clean|strip_tags');
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
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'})[0];
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Hearing Date Changed.';
        $responseMsg = 'Hearing Date Changed successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-change-hearing-date';
        $appealApplicationUpdateInputs['date_of_hearing'] = $dateOfHearing;
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
    public function process_confirm_hearing_date(){
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($this->input->post('appeal_id'));

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-confirm-hearing-date',
            'action_taken_by' => new ObjectId($actionTakenBy),

            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'date_of_hearing' => $appeal->date_of_hearing ?? $appeal->tentative_hearing_date,
            'is_final_hearing' => $this->input->post('isFinalHearing') === 'yes',
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

        $this->load->helper('model');
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'})[0];
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Hearing Date Confirmed.';
        $responseMsg = 'Hearing Date Confirmed successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-confirm-hearing-date';
        $appealApplicationUpdateInputs['date_of_hearing'] = $inputs['date_of_hearing'];
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
    public function process_create_bench(){
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $this->load->helper("fileupload");
        $_POST['benchMember'] = json_decode($_POST['benchMember']);
        $_POST['delegateToChairman'] = json_decode($_POST['delegateToChairman']);
        if(!in_array($_POST['delegateToChairman'],$_POST['benchMember'])){
            if ($this->form_validation->run() == FALSE) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array(
                        'success' => false,
                        'error_msg' => 'Unable to process!!! Please refresh the page and try again.'
                    )));
            }
        }
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-create-bench',
            'action_taken_by' => new ObjectId($actionTakenBy),

            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'bench_member' => $_POST['benchMember'],
            'delegate_to_chairman' => $_POST['delegateToChairman'],
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
//pre($appeal->process_users);
        $roleCondition = ['$expr'=>[
            '$in' => ['$slug', ['MOC','RA']]
        ]];
        $this->load->model('roles_model');
        $permissions = $this->roles_model->get_role_wise_permission($roleCondition);
        $mocPermissions = [];
        $raPermissions = [];
        foreach ($permissions as $permission ){
            if($permission->{'_id'}->role_slug === 'MOC'){
                $mocPermissions = $permission->permissions;
            }
            if($permission->{'_id'}->role_slug === 'RA'){
                $raPermissions = $permission->permissions;
            }
        }
        $keyForProcessUserToRemove = [];
        $removedMocAppealProcessUsers = $appeal->process_users;
        foreach ($appeal->process_users as $userKey => $appealProcessUser){
            if(in_array($appealProcessUser->role_slug,['MOC','RA'])){
                $keyForProcessUserToRemove[] = $userKey;
            }
            if($appealProcessUser->role_slug === 'RA'){
                $raProcessUser = $appealProcessUser;
            }
        }
        foreach ($keyForProcessUserToRemove as $userKeyToRemove){
            unset($removedMocAppealProcessUsers[$userKeyToRemove]);
        }
        $process_users = [];
        $isRAinBench = false;
        foreach ($inputs['bench_member'] as $key => $member){
            if($member->slug === 'RA'){
                $isRAinBench = true;
            }
            $process_users[] = [
                'userId' => new ObjectId($member->userId),
                'action' => ($member->slug === 'MOC') ? $mocPermissions : $raPermissions,
                'role_slug' => $member->slug,
                'active' => ($member->userId === $inputs['delegate_to_chairman']->userId)
            ];
        }
        if(!$isRAinBench && isset($raProcessUser)){
            $process_users[] = $raProcessUser;
        }
        $processUserArrayToUpdate = array_merge($removedMocAppealProcessUsers,$process_users);
        $this->load->helper('model');
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'})[0];
        $this->load->helper('app');

        $responseMsg = 'Bench created successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-create-bench';
        $appealApplicationUpdateInputs['process_users'] = $processUserArrayToUpdate;
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

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

        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);


        $reassignedTo = $this->input->post('reassignTo', true);

        $get_reassigned_to_user_info =  (array)$this->users_model->get_by_id($reassignedTo); 
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

    public function process_forward_to_rr()
    {
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('forwardToRRUserId', 'Forward To', 'trim|required|xss_clean|strip_tags');
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

        $forwardTo = $this->input->post('forwardToRRUserId', true);
        $get_forward_to_user_info =  (array)$this->users_model->get_by_id($forwardTo); 
        // print_r($get_user_info[0]);
        $forward_to_email = ($get_forward_to_user_info[0]->email);
        $forward_to_mobile = ($get_forward_to_user_info[0]->mobile);
        $forward_to_name = ($get_forward_to_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-forward-to-registrar',
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
      //  pre($inputs);
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');

        /** modify process user array **/
        $newProcessUser = getProcessUserArrayOnForward($appeal->process_users, $forwardTo);
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});

        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication[0]->contact_number, $appealApplication[0]->email_id, $appealApplication[0]->contact_in_addition_contact_number, $appealApplication[0]->additional_contact_number, $appealApplication[0]->contact_in_addition_email, $appealApplication[0]->additional_email_id);

        $notificationMsg = 'Appeal Forwarded to Registrar';
        $responseMsg = 'Appeal forwarded successfully.';

        $inputs['previous_process_users'] = $appeal->process_users;
        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);

        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-forward-to-registrar';
        $appealApplicationUpdateInputs['process_users']  = $newProcessUser;

        /** update process user and other appeal_applications info **/
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        // notify appellate
        $aaDetails = [];
        $DPSDetails = [];
        foreach ($appealApplication as $appealData){
            if($appealData->process_users->role_slug === 'RR'){
                $aaDetails = $appealData->process_users_data;
            }

            if($appealData->process_users->role_slug === 'DPS'){
                $DPSDetails = $appealData->process_users_data;
            }
        }
        $this->load->model('users_model');
        $notifiableInfo = $aaDetails;
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

        $notifiableInfo = $DPSDetails;
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

    public function process_forward_to_chairman()
    {
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('forwardToRAUserId', 'Forward To', 'trim|required|xss_clean|strip_tags', ['required' => 'Unable to process!!! Please refresh the page and try again.']);
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


        $forwardTo = $this->input->post('forwardToRAUserId', true);

        $get_forward_to_user_info =  (array)$this->users_model->get_by_id($forwardTo); 
        // print_r($get_user_info[0]);
        $forward_to_email = ($get_forward_to_user_info[0]->email);
        $forward_to_mobile = ($get_forward_to_user_info[0]->mobile);
        $forward_to_name = ($get_forward_to_user_info[0]->name);


        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-forward-to-chairman',
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
      //  pre($inputs);
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');

        // get delegate
        $this->load->model('appeal_process_model');
        $benchInfo = $this->appeal_process_model->last_where(['action'=>'second-appeal-create-bench']);

        if(!empty((array)$benchInfo) && property_exists($benchInfo,'delegate_to_chairman') && $forwardTo !== $benchInfo->delegate_to_chairman->userId)
            $forwardToArray = [$forwardTo,$benchInfo->delegate_to_chairman->userId];

        /** modify process user array **/
        $newProcessUser = getProcessUserArrayOnForward($appeal->process_users, $forwardToArray ?? $forwardTo);
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});

        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication[0]->contact_number, $appealApplication[0]->email_id, $appealApplication[0]->contact_in_addition_contact_number, $appealApplication[0]->additional_contact_number, $appealApplication[0]->contact_in_addition_email, $appealApplication[0]->additional_email_id);

        $notificationMsg = 'Appeal Forwarded to Chairman';
        $responseMsg = 'Appeal forwarded successfully.';

        $inputs['previous_process_users'] = $appeal->process_users;
        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);

        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-forward-to-chairman';
        $appealApplicationUpdateInputs['process_users']  = $newProcessUser;

        /** update process user and other appeal_applications info **/
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        // notify appellate
        $aaDetails = [];
        $DPSDetails = [];
        foreach ($appealApplication as $appealData){
            if($appealData->process_users->role_slug === 'RR'){
                $aaDetails = $appealData->process_users_data;
            }

            if($appealData->process_users->role_slug === 'DPS'){
                $DPSDetails = $appealData->process_users_data;
            }
        }
        $this->load->model('users_model');
        $notifiableInfo = $aaDetails;
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

        $notifiableInfo = $DPSDetails;
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

    public function process_forward_to_moc()
    {
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('forwardToMOCUserId', 'Forward To', 'trim|required|xss_clean|strip_tags', ['required' => 'Unable to process!!! Please refresh the page and try again.']);
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

        $forwardTo = $this->input->post('forwardToMOCUserId', true);

        $get_forward_to_user_info =  (array)$this->users_model->get_by_id($forwardTo); 
        // print_r($get_user_info[0]);
        $forward_to_email = ($get_forward_to_user_info[0]->email);
        $forward_to_mobile = ($get_forward_to_user_info[0]->mobile);
        $forward_to_name = ($get_forward_to_user_info[0]->name);


        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-forward-to-moc',
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
      //  pre($inputs);
        $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($inputs['appeal_id']);
        $this->load->helper('model');

        /** modify process user array **/
        $newProcessUser = getProcessUserArrayOnForward($appeal->process_users, $forwardTo);
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'});

        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication[0]->contact_number, $appealApplication[0]->email_id, $appealApplication[0]->contact_in_addition_contact_number, $appealApplication[0]->additional_contact_number, $appealApplication[0]->contact_in_addition_email, $appealApplication[0]->additional_email_id);

        $notificationMsg = 'Appeal Forwarded to Member of Commission';
        $responseMsg = 'Appeal forwarded successfully.';

        $inputs['previous_process_users'] = $appeal->process_users;
        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);

        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-forward-to-moc';
        $appealApplicationUpdateInputs['process_users']  = $newProcessUser;

        /** update process user and other appeal_applications info **/
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        // notify appellate
        $aaDetails = [];
        $DPSDetails = [];
        foreach ($appealApplication as $appealData){
            if($appealData->process_users->role_slug === 'RR'){
                $aaDetails = $appealData->process_users_data;
            }

            if($appealData->process_users->role_slug === 'DPS'){
                $DPSDetails = $appealData->process_users_data;
            }
        }
        $this->load->model('users_model');
        $notifiableInfo = $aaDetails;
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

        $notifiableInfo = $DPSDetails;
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

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);


        $forwardTo = $this->input->post('forwardToAAUserId', true);

        $get_forward_to_user_info =  (array)$this->users_model->get_by_id($forwardTo); 
        // print_r($get_user_info[0]);
        $forward_to_email = ($get_forward_to_user_info[0]->email);
        $forward_to_mobile = ($get_forward_to_user_info[0]->mobile);
        $forward_to_name = ($get_forward_to_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'forward-to-aa',
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

        $notificationMsg = 'Appeal Forwarded to Appellate Authority';
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

            if($appealData->process_users->role_slug === 'DPS'){
                $DPSDetails = $appealData->process_users_data;
            }
        }
        $this->load->model('users_model');
        $notifiableInfo = $aaDetails;
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

        $notifiableInfo = $DPSDetails;
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

        $revert_back_to_user_email = ($get_revert_back_to_user_info[0]->email);
        $revert_back_to_user_mobile = ($get_revert_back_to_user_info[0]->mobile);
        $revert_back_to_user_name = ($get_revert_back_to_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-revert-back-to-da',
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

        $notificationMsg = 'Appeal Reverted Back';
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
        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-revert-back-to-da';
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

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'success' => true,
                'msg' => $responseMsg,
            )));
    }

    public function process_issue_penalty()
    {
        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('order_no', 'Order No.', 'trim|required|xss_clean|strip_tags');
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

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        
        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'penalize',
            'action_taken_by' => new ObjectId($actionTakenBy),

            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,


            'order_no' => $this->input->post('order_no'),
            'penalty_amount' => $this->input->post('penaltyAmount'),
            'penalty_should_pay_within_days' => $this->input->post('penaltyShouldPayWithinDays'),
            'certificate_issued_within_days' => $this->input->post('certificateIssuedWithinDays'),
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

        $notificationMsg = 'Penalty imposed. Penalty amount ' . $inputs['penalty_amount'];
        $responseMsg = 'Penalized successfully.';

        $inputs['penalty_to_user'] = $appealApplication->dps_details->{'_id'};
        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'penalize';
        $this->appeal_application_model->update_where($appealApplicationFilters, $appealApplicationUpdateInputs);

        $this->load->model('users_model');
        $penaltyToUserInfo = $this->users_model->get_by_doc_id($inputs['penalty_to_user']);
        // sms
        $penaltyTo = 'DPS';
        $msg = $notificationMsg . $penaltyTo . ' name: ' . $penaltyToUserInfo->name . ' and penalty should be paid with in ' . $inputs['penalty_should_pay_within_days'] . '.days. Appeal ID : ' . $inputs['appeal_id'] . '.';
        $msg = urlencode($msg);
        $this->sms->send($penaltyToUserInfo->mobile, $msg);

        //email
        $emailBody = '<p>' . $notificationMsg . $penaltyTo . ' name: ' . $penaltyToUserInfo->name . ' and penalty should be paid with in ' . $inputs['penalty_should_pay_within_days'] . '.days. Appeal ID :' . $inputs['appeal_id'] . '</p>';
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

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-issue-rejection-order',
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

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-issue-rejection-order';
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

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-issue-disposal-order',
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

        $appealApplicationUpdateInputs['process_status'] = 'second-appeal-issue-disposal-order';
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
        $data['name'] = urldecode($this->input->post("name"));
        $data['appealId'] = urldecode($this->input->post("appealId"));
        $data['forwardAbleUserOptionList'] = urldecode($this->input->post("forwardAbleUserOptionList"));
        $data['reAssignAbleUserOptionList'] = urldecode($this->input->post("reAssignAbleUserOptionList"));
        $data['aaName']   = urldecode($this->input->post("aaName"));
        $data['aaUserId'] = urldecode($this->input->post("aaUserId"));
        $data['rrName']   = urldecode($this->input->post("rrName"));
        $data['rrUserId'] = urldecode($this->input->post("rrUserId"));
        $data['raName']   = urldecode($this->input->post("raName"));
        $data['raUserId'] = urldecode($this->input->post("raUserId"));
        $data['mocUserId']   = urldecode($this->input->post("mocUserId"));
        $data['mocName'] = urldecode($this->input->post("mocName"));

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

        
//        $data['hearingDate'] = urldecode($this->input->post("hearingDate"));
            $this->load->model('appeal_application_model');
        $appeal = $this->appeal_application_model->get_by_appeal_id($data['appealId']);

        $data['hearingDate'] = format_mongo_date($appeal->date_of_hearing ?? $appeal->tentative_hearing_date,'d-m-Y');

        if(in_array($page,['provide-hearing-date'])){
            // todo : get hearing count if available
            $data['isFirstHearing'] = true; // todo check in db and  change later
            $data['appealDate'] = urldecode($this->input->post("appealDate"));
        }

        if(in_array($page,['second-appeal-revert-back-to-da'])){
            $this->load->model('appeal_process_model');
            $lastProcessByOtherUser = $this->appeal_process_model->last_where(['action' => 'second-appeal-forward-to-registrar']);

            $this->load->model('users_model');
            $lastProcessUser = $this->users_model->get_by_doc_id(strval($lastProcessByOtherUser->{'action_taken_by'}));
            $data['daName']  = $lastProcessUser->name;
            $data['daUserId']  = $lastProcessUser->{'_id'}->{'$id'};
        }
        if(in_array($page,['second-appeal-revert-back-to-rr']) && !isset($data['rrUserId'])){
            $this->load->model('appeal_process_model');
            $lastProcessByOtherUser = $this->appeal_process_model->last_where(['action' => 'second-appeal-forward-to-chairman']);

            $this->load->model('users_model');
            $lastProcessUser = $this->users_model->get_by_doc_id(strval($lastProcessByOtherUser->{'action_taken_by'}));
            $data['rrName']  = $lastProcessUser->name;
            $data['rrUserId']  = $lastProcessUser->{'_id'}->{'$id'};
        }

        if(in_array($page,['upload-hearing-order','second-appeal-upload-hearing-order'])){
            // check and get generated hearing order if available
            $this->load->model('appeal_process_model');
            $noInputToRender = false;
            $processListRelatedToHearing = $this->mongo_db->where_in('action',['generate-hearing-order','modify-hearing-order','confirm-hearing-date'])->where('appeal_id',$data['appealId'])->get('appeal_processes');
            foreach ($processListRelatedToHearing as $hearingRelatedProcess){
                switch ($hearingRelatedProcess->action){
                    case 'generate-hearing-order':
                    case 'modify-hearing-order':
                        $noInputToRender = true;
                        break;
                    case 'confirm-hearing-date':
//                        $noInputToRender = true;
                        break;
                    default:
                        break;
                }
            }
            $data['noInputToRender'] = $noInputToRender;
        }
        if(in_array($page,['second-appeal-approve-hearing-order','second-appeal-issue-hearing-order'])){
            // check and get generated hearing order if available
            $this->load->model('appeal_process_model');
            $data['lastHearingConfirmed'] = $this->appeal_process_model->last_where(['appeal_id' => $data['appealId'],'action' => 'second-appeal-confirm-hearing-date']);
            $this->load->model('order_model');
            $latestHearingOrders = $this->order_model->last_where(['appeal_id' => $data['appealId'],'order_type' => 'hearing-order','confirmed_hearing_date_process_id' => new ObjectId($data['lastHearingConfirmed']->_id->{'$id'})]);

            $this->load->library('pdf');

            $data['appellantHearingOrder_base_64'] = base64_encode($this->pdf->to_string($latestHearingOrders->templateContent->appellant,'order'));
            $data['dpsHearingOrder_base_64'] = base64_encode($this->pdf->to_string($latestHearingOrders->templateContent->dps,'order'));
        }
        if(in_array($page,['upload-disposal-order','second-appeal-upload-disposal-order'])){
            // check and get generated disposal order if available
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $data['appealId'],'action' => 'generate-disposal-order']);
            if($latestProcess){
                $data['disposalOrderTemplates'] = $latestProcess->templateContent;
            }
        }

        if($page === 'second-appeal-approve-disposal-order'){
            // check and get generated disposal order if available
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $data['appealId'],'action' => 'second-appeal-upload-disposal-order']);
            $data['disposalOrder'] = $latestProcess->disposal_order ?? '';
        }
        if($page == 'second-appeal-issue-disposal-order'){
            $this->load->model('order_model');
            $latestDisposalOrder = $this->order_model->last_where(['appeal_id' => $data['appealId'],'order_type' => 'disposal-order']);

            $this->load->library('pdf');

            $data['disposalOrder_base_64'] = base64_encode($this->pdf->to_string($latestDisposalOrder->templateContent->order,'order'));
        }
        if(in_array($page,['upload-rejection-order','second-appeal-upload-rejection-order'])){
            // check and get generated rejection order if available
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $data['appealId'],'action' => 'generate-rejection-order']);
            if($latestProcess){
                $data['rejectionOrderTemplates'] = $latestProcess->templateContent;
            }
        }
        if($page === 'second-appeal-approve-rejection-order'){
            // check and get generated rejection order if available
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $data['appealId'],'action' => 'second-appeal-upload-rejection-order']);
            $data['rejectionOrder'] = $latestProcess->rejection_order ?? '';
        }
        if($page == 'second-appeal-issue-rejection-order'){

            $this->load->model('order_model');
            $latestRejectionOrder = $this->order_model->last_where(['appeal_id' => $data['appealId'],'order_type' => 'rejection-order']);

            $this->load->library('pdf');

            $data['rejectionOrder_base_64'] = base64_encode($this->pdf->to_string($latestRejectionOrder->templateContent->order,'order'));
        }
        if($page === 'second-appeal-create-bench'){
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $data['appealId'],'action' => 'second-appeal-create-bench']);
            $data['previous_bench_members'] = $latestProcess->bench_member ?? '';
            $data['previous_delegate_to_chairman'] = $latestProcess->delegate_to_chairman ?? '';
        }
        echo $this->load->view("ams/action_templates/second_appeal_view_templates/" . $page, $data);
    }

    public function download_generated_template($appealId,$forUserType,$action){
        $this->load->model('appeal_process_model');
        $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $appealId,'action' => $action]);

        if(isset($latestProcess->templateContent)){
            $this->load->library('pdf');
            $this->pdf->generate($latestProcess->templateContent->{$forUserType},$latestProcess->action.'_'.$forUserType.'_','D');
            exit();
        }
        show_404();

    }

    public function process_comment_before_final_verdict(){
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        // print_r($get_user_info[0]);
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'comment-by-bench-member',
            'action_taken_by' => new ObjectId($actionTakenBy),

            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

            'message' => $this->input->post('comment'),
            'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        ];

        $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags',
            ['required' => 'Unable to process!!! Please refresh the page and try again.']
        );
        $this->form_validation->set_rules('comment', 'Comment', 'trim|required|xss_clean|strip_tags');

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
        $appealApplication = getAppealApplications($appeal,$appeal->{'_id'}->{'$id'})[0];
//        if (property_exists($appeal, 'appl_ref_no')) {
//            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id($inputs['appeal_id']);
//        } else {
//            $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id_without_ref($inputs['appeal_id']);
//        }
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

        $notificationMsg = 'Comment Added.';
        $responseMsg = 'Comment added by bench member successfully.';

        $this->load->model('appeal_process_model');
        $this->appeal_process_model->insert($inputs);
        $appealApplicationFilters = array(
            'appeal_id' => $inputs['appeal_id']
        );

        $appealApplicationUpdateInputs['process_status'] = 'comment-by-bench-member';
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

    public function process_final_verdict(){
        $actionTakenBy = $this->session->userdata('userId')->{'$id'};

                
        $get_taken_by_user_info =  (array)$this->users_model->get_by_id($actionTakenBy); 
        $action_taken_by_email = ($get_taken_by_user_info[0]->email);
        $action_taken_by_mobile = ($get_taken_by_user_info[0]->mobile);
        $action_taken_by_name = ($get_taken_by_user_info[0]->name);


        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $this->input->post('appeal_id'),
            'action' => 'second-appeal-final-verdict',
            'action_taken_by' => new ObjectId($actionTakenBy),

            'action_taken_by_email'=> $action_taken_by_email,
            'action_taken_by_mobile'=> $action_taken_by_mobile,
            'action_taken_by_name'=>  $action_taken_by_name,

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
                    'error_msg' => "Some bench members haven't commented yet. So final verdict can't be generated"
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

    public function generate_order_from_repo($order_type,$appeal_id,$userType = 'applicant'){
        $this->load->model('order_model');
        $filter = [
            'order_type' => $order_type,
            'appeal_id' => $appeal_id,
        ];
        $order = $this->order_model->last_where($filter);
        if($order_type === 'hearing-order'){
            $templateToGenerate = $order->templateContent->{$userType};
        }else{
            $templateToGenerate = $order->templateContent->order;
        }
        $this->load->library('pdf');
        $this->pdf->generate($templateToGenerate,'order');
        exit();
    }
}
