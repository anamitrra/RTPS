<?php

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Appeals_demo extends frontend
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('applications_model');
        $this->load->model('appeal_application_model');
        $this->load->model('appeals_model');
        $this->load->model('appeal_process_model');
        $this->load->model('users_model');
        $this->load->model('official_details_model');
        $this->load->model('services_model');
        $this->load->model('location_model');
        $this->load->helper('role');
        $this->load->library('form_validation');
    }
    // welcome page or page before login

    /**
     * welcome
     *
     * @return void
     */
    public function welcome()
    {
        $this->load->view('includes/frontend/header');
        $this->load->view('appeals_demo/welcome');
        $this->load->view('includes/frontend/footer');
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        redirect('appeals/apply');
        $this->load->helper('captcha');
        $cap = generate_n_store_captcha();
        $data = [
            'cap' => $cap
        ];
        $this->load->view('includes/frontend/header');
        $this->load->view('appeals_demo/index', $data);
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
        $ref_no = strval($this->input->post('applicationNumber', true));
        $mobile = strval($this->input->post('contactNumber', true));
        $value = $this->appeals_model->search($ref_no, $mobile);

        $this->load->helper('captcha');
        $validatedCaptcha = validate_captcha();
        if(!$validatedCaptcha['status']){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($validatedCaptcha));
        }

        $status = array();
        //pre($value);
        if ($value && is_object($value)) {
            if (isset($value->timeline_stat_45) && !empty($value->timeline_stat_45) && $value->timeline_stat_45) {
                if (isset($value->timeline_stat_30) && !empty($value->timeline_stat_30) && $value->timeline_stat_30) {
                    $this->session->set_userdata("appeal_after_30", FALSE);
                } else {
                    $this->session->set_userdata("appeal_after_30", TRUE);
                }
                $msg = "Your Otp is for new appeal is {{otp}}";
                $this->sms->send_otp($mobile, $ref_no, $msg);
                $status["status"] = true;
            } else {
                $status["status"] = false;
                $status["error_msg"] = "Your application timeline for appeal submission is expired.";
            }
        } else {
            $status["status"] = false;
            $status["error_msg"] = "Application Ref No and Mobile No Does not match any record.";
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
    }

    /**
     * process_appeal_login
     *
     * @return void
     */
    public function process_appeal_login()
    {
        $ref_no = $this->input->post("applicationNumber", TRUE);
        $mobile = $this->input->post("contactNumber", TRUE);
        $otp = $this->input->post("otp", TRUE);
        // if applied by PFC
        if ($this->session->has_userdata('role') && $this->session->userdata('role')->role_name === 'PFC') {
            $appeal = $this->applications_model->first_where(array('initiated_data.appl_ref_no' => $ref_no));
            if (!$appeal) {
                $status["status"] = false;
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($status));
            }
        } else {
            $res = $this->sms->verify_otp($mobile, $ref_no, $otp);
            if (!$res) {
                $status["status"] = false;
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($status));
            }
        }

        $this->session->set_userdata("opt_status", TRUE);
        $this->session->set_userdata("appl_ref_no", $ref_no);
        $status["status"] = true;
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
    }

    /**
     * apply_for_appeal
     *
     * @return void
     */
    public function apply_for_appeal()
    {
//        if (!$this->session->userdata('opt_status')) {
//            redirect(base_url('appeal/login'));
//        }
        $this->session->set_userdata('appl_ref_no','RTPS-MRG/2020/00001');
        $ref_no = $this->session->userdata('appl_ref_no');

        $applicationData = $this->applications_model->get_by_appl_ref_no($ref_no);
        //        pre($applicationData);
        try {
            $service = $this->services_model->first_where(array('service_id' => $applicationData->initiated_data->base_service_id));
            $location = $this->location_model->first_where(array('location_name' => "" . $applicationData->initiated_data->submission_location . ""));
            if (!$service || !$location) {
                throw new Exception();
            }
        } catch (Exception $exception) {
            log_message('error', $exception);
            show_404();
            exit(404);
        }
        $relatedOfficialsFilter = array(
            'service_id' => new ObjectId($service->{'_id'}->{'$id'}),
            'location_id' => new ObjectId($location->{'_id'}->{'$id'}),
        );

        $relatedOfficials = $this->official_details_model->first_where($relatedOfficialsFilter);
        //pre($relatedOfficials);
        $appealApplication = $this->appeal_application_model->get_by_appl_ref_no($ref_no);

//        if (isset($appealApplication)) {
//            show_error("You have already applied for appeal of the application. you can not apply appeal for same application", 403, "Error");
//        }
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
            'cap' => $cap
        ];
//        $this->load->library('user_agent');
//        if ($this->agent->referrer()) {
//            $data['post'] = $this->input->post();
//        }
        $this->load->view('includes/frontend/header');
        $this->load->view('appeals_demo/apply_for_appeal', $data);
        $this->load->view('includes/frontend/footer');
    }

    /**
     * process
     *
     * @return void
     */
    public function process()
    {
        if ($this->input->method(true) !== 'POST') {
            show_error('Method Not Allowed', '403', 'Invalid Method');
        }
//        if (!$this->session->userdata('opt_status')) {
//            redirect(base_url('appeal/login'));
//        }

//        $this->load->helper('captcha');

//        $this->form_validation->set_rules('captcha', 'Captcha', 'validate_captcha|trim');
        $this->form_validation->set_rules('additionalContactNumber', 'Additional Contact Number', 'trim');
        $this->form_validation->set_rules('additionalEmailId', 'Additional Email ID', 'trim');
        $this->form_validation->set_rules('addressOfThePerson', 'Address of the person', 'trim|required');
        $this->form_validation->set_rules('groundForAppeal', 'Ground for appeal', 'trim|required');
        $this->form_validation->set_rules('appealDescription', 'Appeal Description/ Relief sought for', 'trim|required');

        if($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata("error",validation_errors());
            $this->session->set_flashdata("old",$this->input->post());
            redirect('appeal/demo/apply');
        }

        $app_ref_no = $this->input->post('applicationNumber', true);
        $appealApplication = $this->appeal_application_model->get_by_appl_ref_no($app_ref_no);

//        if (isset($appealApplication)) {
//            redirect(base_url('appeal/preview-n-track'));
//        }

        $applicationData = $this->applications_model->get_by_appl_ref_no($app_ref_no);

        $service = $this->services_model->first_where(array('service_id' => $applicationData->initiated_data->base_service_id));
        $location = $this->location_model->first_where(array('location_name' => "" . $applicationData->initiated_data->submission_location . ""));
        $relatedOfficialsFilter = array(
            'service_id' => new ObjectId($service->{'_id'}->{'$id'}),
            'location_id' => new ObjectId($location->{'_id'}->{'$id'}),
        );
        $relatedOfficials = $this->official_details_model->first_where($relatedOfficialsFilter);
        $this->load->helper('model');
        $appealApplicationId = checkAndGenerateUniqueId('appeal_id', 'appeal_applications');
        $nowDb = date('d-m-Y H:i');
        $now = date('d-m-Y g:i a');

        $contactNumber = $this->input->post('contactNumber', true);
        $emailId = $this->input->post('emailId', true);
        $contactInAdditionContactNumber = $this->input->post('contactInAdditionContactNumber', true);
        $additionalContactNumber = $this->input->post('additionalContactNumber', true);
        $contactInAdditionEmail = $this->input->post('contactInAdditionEmail', true);
        $additionalEmailId = $this->input->post('additionalEmailId', true);

        if ($this->session->has_userdata('role') && $this->session->userdata('role')->role_name === 'PFC') {
            $pfcName = $this->users_model->get_by_doc_id($this->session->userdata('userId')->{'$id'});
            $pfcName->{'_id'} = new ObjectId($pfcName->{'_id'}->{'$id'});
        } else {
            $pfcName = '';
        }

        $this->load->helper("fileupload");
        $inputs = [
            'appeal_id' => $appealApplicationId,
            'appl_ref_no' => $app_ref_no,
            'applicant_name' => $this->input->post('nameOfThePerson', true),
            'contact_number' => ($this->input->post('contactNumber', true) != 'NA') ? $this->input->post('contactNumber', true) : '1234567890', // dummy fallback number
            'contact_in_addition_contact_number' => $this->input->post('contactInAdditionContactNumber', true) ? true : false,
            'additional_contact_number' => $this->input->post('additionalContactNumber', true),
            'email_id' => $this->input->post('emailId', true),
            'contact_in_addition_email' => $this->input->post('contactInAdditionEmail', true) ? true : false,
            'additional_email_id' => $this->input->post('additionalEmailId', true),
            'address_of_the_person' => $this->input->post('addressOfThePerson', true),
            'name_of_service' => $this->input->post('nameOfService', true),
            'date_of_application' => new \MongoDB\BSON\UTCDateTime((strtotime($this->input->post('dateOfApplication', true)) * 1000)),
            'appeal_expiry_status' => $this->session->userdata("appeal_after_30") ?? false,
            'is_rejected' => false,
            'appeal_type' => '1',
            //            'date_of_appeal'                  => $this->input->post('dateOfAppeal', true),
            'name_of_PFC' => $pfcName,
            'ground_for_appeal' => $this->input->post('groundForAppeal', true),
            'appeal_description' => $this->input->post('appealDescription', true),
            'documents' => moveFile(0, "appeal_attachments"),
            'process_status' => null,
            'service_id' => new ObjectId($service->{'_id'}->{'$id'}),
            'location_id' => new ObjectId($location->{'_id'}->{'$id'}),
            'dps_id' => $relatedOfficials->dps_id,
            'appellate_id' => $relatedOfficials->appellate_id,
            'reviewing_id' => $relatedOfficials->reviewing_id,
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime($nowDb) * 1000)
        ];
        $this->appeal_application_model->insert($inputs);
        $this->session->set_userdata('appeal_id', $appealApplicationId);
        // TODO :: use scheduling or job , queue for email and sms sending

        $officialUserCondition = array('_id' => array($relatedOfficials->dps_id, $relatedOfficials->appellate_id));
        $officialUsers = $this->users_model->get_where_in($officialUserCondition);

        // official notification
        $officialToMailCSV = '';
        foreach ($officialUsers as $official) {
            // send sms
            $msg = "New Appeal Received. Appeal ID : $appealApplicationId";
            $msg = urlencode($msg);
            $this->sms->send($official->mobile, $msg);
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
            $msg = "Appeal submitted. Your Appeal ID is $appealApplicationId";
            $msg = urlencode($msg);
            $this->sms->send($contactMobile, $msg);
        }
        // send email
        // TODO :: attach ack to mail
        foreach ($applicantNotifiable['email'] as $contactEmail) {
            $emailBody = '<p>Dear Applicant,</p>
                            <p style="text-indent: 14px">Appeal submitted for Application No. ' . $app_ref_no . '.</p>
                            <p>Appeal ID : ' . $appealApplicationId . '</p>
                            <p>Submitted on : ' . $now . '</p>';

            $this->remail->sendemail($contactEmail, "Appeal Submitted", $emailBody);
        }
        redirect(base_url('appeal/demo/ack'));
    }

    /**
     * acknowledgement
     *
     * @return void
     */
    public function acknowledgement()
    {
        if ($this->input->method(true) !== 'GET') {
            show_error('Method Not Allowed', '403', 'Invalid Method');
        }
        $appeal_id = $this->session->userdata('appeal_id');
        $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id($appeal_id);

        $applicationData = $this->applications_model->get_by_appl_ref_no($appealApplication->appl_ref_no);

        if (!isset($appeal_id) || empty($applicationData)) {
            redirect(base_url('appeal/demo/apply'));
        }
        $data = [
            'appealApplication' => $appealApplication,
            'applicationData' => $applicationData
        ];
        $this->load->view('includes/frontend/header');
        $this->load->view('appeal/appeals/acknowledgement', $data);
        $this->load->view('includes/frontend/footer');
    }

    /**
     * preview_and_track
     *
     * @return void
     */
    public function preview_and_track()
    {
        if ($this->input->method(true) !== 'GET') {
            show_error('Method Not Allowed', '403', 'Invalid Method');
        }
        //pre($this->session->userdata());
        //pre($this->session->userdata('opt_status'));
//        if (!$this->session->userdata('opt_status')) {
//            redirect(base_url('appeals/login'));
//        }

        $appeal_id = $this->session->userdata('appl_no');
        $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id($appeal_id);
        //pre($appealApplication);
        if (empty((array)$appealApplication)) {
            redirect('appeals');
        }
        $applicationData = $this->applications_model->get_by_appl_ref_no($appealApplication->appl_ref_no);
        $processFilterAppeal = array('appeal_id' => $appealApplication->appeal_id);
        $appealProcessList = $this->appeal_process_model->get_where($processFilterAppeal);
        $userList = $this->users_model->get_all(array());
        $data = [
            'appealApplication' => $appealApplication,
            'applicationData' => $applicationData,
            'appealProcessList' => $appealProcessList,
            'userList' => $userList
        ];
        if ($appealApplication->appeal_type == '2') {
            $appealApplicationPrevious = $this->appeal_application_model->get_with_related_by_appeal_id($appealApplication->ref_appeal_id);
            $data['appealApplicationPrevious'] = $appealApplicationPrevious;
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('appeals_demo/preview_n_track', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_comment()
    {
        $this->form_validation->set_rules('comment', 'Comment', 'trim|required|xss_clean|strip_tags');
        if ($this->form_validation->run() == false) {
            redirect('appeal/demo/preview-n-track');
        }
        $latestAppealProcess = $this->appeal_process_model->find_latest_where(array('appeal_id' => $this->input->post('appeal_id')));
        //        pre($latestAppealProcess);
        //        pre($latestAppealProcess->{'_id'}->{'$id'});
        $this->load->helper("fileupload");
        if (!isset($latestAppealProcess->comment)) {
            $appealCommentInput = array(
                'comment' => $this->input->post('comment'),
                'comment_documents' => moveFile(0, "file_for_comment"),
                'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            );
            $processUpdateFilter = array('_id' => new ObjectId($latestAppealProcess->{'_id'}->{'$id'}));
            $this->appeal_process_model->update_where($processUpdateFilter, $appealCommentInput);
            $this->session->set_flashdata('success', 'Comment successfully submitted for latest process.');
        } else {
            $this->session->set_flashdata('fail', 'Comment already submitted for latest process.');
        }
        redirect('appeal/demo/preview-n-track');
    }

    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        $user_data = $this->session->all_userdata();
        $this->session->unset_userdata('opt_status');
        $this->session->unset_userdata('appl_ref_no');
        $this->session->sess_destroy();
        redirect('/');
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
                $tableContent .= '<a href="' . base_url($doc) . '" class="btn btn-sm btn-outline-warning" target="_blank">View Attachment ' . ($counter + 1) . '</a><br/>';
            }
            echo $tableContent;
        } else {
            echo '<h4 class="text-center">No Data Available</h4>';
        }
        exit();
    }

    public function test()
    {
        $this->load->view('includes/printables/header');
        $this->load->view('appeal/action_templates/notice_for_hearing_to_dps');
        $this->load->view('includes/printables/footer');
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


    // welcome page or page before login
    /**
     * login
     *
     * @return void
     */
    public function track_login(){
        redirect('appeal/demo/preview-n-track');
        $this->load->helper('captcha');
        $cap = generate_n_store_captcha();
        $data = [
            'cap' => $cap
        ];
        $this->load->view('includes/frontend/header');
        $this->load->view('track_appeals/index',$data);
        $this->load->view('includes/frontend/footer');
    }

    /**
     * process_appeal_login
     *
     * @return void
     */
    public function process_appeal_track_login()
    {
        $appl_no = strval($this->input->post('appealNumber', true));
        $mobile = $this->input->post("contactNumber", TRUE);
//        $otp=$this->input->post("otp", TRUE);

        // if applied by PFC
        if($this->session->has_userdata('role') && $this->session->userdata('role')->role_name === 'PFC'){
            $appeal = $this->appeal_application_model->first_where(array('appeal_id' => $appl_no));
            if(!$appeal){
                $status["status"] = false;
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($status));
            }
        }else{
            $value=$this->sms->verify_otp($mobile,$appl_no,$otp);
            //pre($value);
            if (!$value) {
                $status["status"] = false;
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($status));
            }
        }

        $this->session->set_userdata("opt_status",true);
        $this->session->set_userdata("appl_no",$appl_no);
        $appealApplication = $this->appeal_application_model->first_where(array('appeal_id' => $appl_no,'contact_number' => $mobile));
        if(isset($appealApplication->ref_appeal_id)){
            $this->session->set_userdata("appeal_type",'2');
        }else{
            $this->session->set_userdata("appeal_type",'1');
        }
        $status["status"] = true;
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
    }
}
