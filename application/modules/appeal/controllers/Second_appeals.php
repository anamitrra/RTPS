<?php


use MongoDB\BSON\ObjectId;

class Second_appeals extends frontend
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
        $this->load->model('second_appeal_model');
        $this->load->model('appeals_model');
        $this->load->model('location_model');
        $this->load->model('appeal_process_model');
        $this->load->model('users_model');
        $this->load->model('official_details_model');
        $this->load->model('services_model');
        $this->load->helper('role');
        $this->load->library('form_validation');
    }

    /**
     * index
     *
     * @return void
     */
    public function index(){
        $this->load->helper('captcha');
        $cap = generate_n_store_captcha();
        $data = [
            'cap' => $cap
        ];
        $this->load->view('includes/frontend/header');
        $this->load->view('second_appeals/index',$data);
        $this->load->view('includes/frontend/footer');
    }

    public function send_otp(){
        $ref_no = strval($this->input->post('appealReferenceNumber', true));
        $mobile = strval($this->input->post('contactNumber', true));
        $data = array();

        $this->load->helper('captcha');
        $validatedCaptcha = validate_captcha();
        if(!$validatedCaptcha['status']){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($validatedCaptcha));
        }

        $appeal = $this->appeal_application_model->last_where(array('appeal_id' => $ref_no,'contact_number' => $mobile));

        list($allowOtp, $data) = $this->check_second_appeal_timeline($appeal, $data);

        if($allowOtp){
            $msg = "Your Otp is for second appeal is {{otp}}";
            $this->sms->send_otp($mobile, $ref_no, $msg);
            $data["status"] = true;
            $data["error_msg"] = '';
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }
    /**
     * process_appeal_login
     *
     * @return void
     */
    public function process_appeal_login()
    {
        $ref_no = $this->input->post("appealReferenceNumber", TRUE);
        $mobile = $this->input->post("contactNumber", TRUE);
        $otp=$this->input->post("otp", TRUE);
        $data = array();

        $appeal = $this->appeal_application_model->first_where(array('appeal_id' => $ref_no));

        list($allowOtp, $data) = $this->check_second_appeal_timeline($appeal, $data);

        if($data['status'] === false){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($data));
        }
        // if applied by PFC
        if($this->session->has_userdata('role') && $this->session->userdata('role')->role_name === 'PFC'){
            if(!$appeal){
                $data["status"] = false;
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($data));
            }
        }else{
            $res = $this->sms->verify_otp($mobile, $ref_no, $otp);
            if (!$res) {
                $data["status"] = false;
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($data));
            }
        }

        $this->session->set_userdata("opt_status",TRUE);
        $this->session->set_userdata("appeal_id",$ref_no);
        $data["status"] = true;
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    /**
     * apply_for_appeal
     *
     * @return void
     */
    public function apply_for_appeal(){
        if(!$this->session->userdata('opt_status')){
            redirect(base_url('appeal/second'));
        }
        $ref_no = $this->session->userdata('appeal_id');
        $appealApplicationPrevious = $this->appeal_application_model->get_by_appeal_id($ref_no);

        if(isset($appealApplicationPrevious->ref_appeal_id)){
            redirect(base_url('appeal/preview-n-track'));
        }
        $applicationData = $this->applications_model->get_by_appl_ref_no($appealApplicationPrevious->appl_ref_no);

        $service  = $this->services_model->first_where(array('service_id' => $applicationData->initiated_data->base_service_id));
        $this->load->model('location_model');
        $location = $this->location_model->first_where(array('location_name' => "".$applicationData->initiated_data->submission_location.""));
        $relatedOfficialsFilter = array(
            'service_id'  => new ObjectId( $service->{'_id'}->{'$id'}),
            'location_id' =>  new ObjectId($location->{'_id'}->{'$id'}),
        );
        $relatedOfficials = $this->official_details_model->first_where($relatedOfficialsFilter);
        if(!$relatedOfficials) {
            show_error('No Officials are mapped for this service.',500);
        }
        $dps= $this->users_model->get_by_doc_id($relatedOfficials->dps_id);
        $appalete= $this->users_model->get_by_doc_id($relatedOfficials->appellate_id);
        $review= $this->users_model->get_by_doc_id($relatedOfficials->reviewing_id);

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

        $this->load->view('includes/frontend/header');
        $this->load->view('second_appeals/apply',$data);
        $this->load->view('includes/frontend/footer');
    }

    /**
     * process
     *
     * @return void
     */
    public function process(){
        // if(!$this->session->userdata('opt_status')){
        //     redirect(base_url('appeal/dashboard'));
        // }

//        $this->load->helper('captcha');

//        $this->form_validation->set_rules('captcha', 'Captcha', 'validate_captcha|trim');
        $this->form_validation->set_rules('additionalContactNumber', 'Additional Contact Number', 'trim');
        $this->form_validation->set_rules('additionalEmailId', 'Additional Email ID', 'trim');
        // $this->form_validation->set_rules('addressOfThePerson', 'Address of the person', 'trim|required');
        $this->form_validation->set_rules('groundForAppeal', 'Ground for appeal', 'trim|required');
        $this->form_validation->set_rules('appealDescription', 'Appeal Description', 'trim|required');
        $this->form_validation->set_rules('reliefSoughtFor', 'Relief sought for', 'trim|required');
        $this->form_validation->set_rules('village', 'Village', 'required|trim');
        // $this->form_validation->set_rules('gender', 'Gender', 'required|trim');
        $this->form_validation->set_rules('district', 'District', 'required|trim');
        $this->form_validation->set_rules('policestation', 'Police Station', 'required|trim');
        $this->form_validation->set_rules('circle', 'Circle', 'trim');
        $this->form_validation->set_rules('postoffice', 'Post Office', 'required|trim');
        $this->form_validation->set_rules('pincode', 'Pincode', 'required|trim');

        if($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata("error",validation_errors());
            $this->session->set_flashdata("old",$this->input->post());
            redirect(base_url('appeal/userarea'));
            exit('404');
        }

        $previous_appeal_id = $this->input->post('previous_appeal_id',true);
        $appealApplicationPrevious = $this->appeal_application_model->get_by_appeal_id($previous_appeal_id);

        $appealApplication = $this->appeal_application_model->get_where(array('ref_appeal_id' => $previous_appeal_id));

        $applicationData = $this->applications_model->get_by_appl_ref_no($appealApplicationPrevious->appl_ref_no);
        // if(!empty((array)$appealApplication)){
        //     redirect(base_url('appeal/preview-n-track'));
        // }
        if(!empty($applicationData)){
            $service  = $this->services_model->first_where(array('service_id' => $applicationData->initiated_data->base_service_id));
            $location = $this->location_model->first_where(array('location_name' => $applicationData->initiated_data->submission_location));
        }else{
            $service  = $this->services_model->first_where(array('_id' => $appealApplicationPrevious->service_id));
            $location = $this->location_model->first_where(array('_id' => $appealApplicationPrevious->location_id));
        }

        $relatedOfficialsFilter = array(
            'service_id'  => new ObjectId( $service->{'_id'}->{'$id'}),
            'location_id' =>  new ObjectId($location->{'_id'}->{'$id'}),
        );

        $relatedOfficials = $this->official_details_model->first_where($relatedOfficialsFilter);

        if(empty($relatedOfficials)){
            show_error('No Officials are mapped for this service.',500);
        }
        if(empty($relatedOfficials->registrar_id_array)){
            show_error('No Registrar is mapped for this service.',500);
        }
        $this->load->helper('model');
        $appealApplicationId = checkAndGenerateUniqueId('appeal_id','appeal_applications');

        $nowDb = date('d-m-Y H:i');
        $now   = date('d-m-Y g:i a');

        $contactNumber = $this->input->post('contactNumber', true);
        $emailId = $this->input->post('emailId', true);
        $contactInAdditionContactNumber = $this->input->post('contactInAdditionContactNumber', true);
        $additionalContactNumber = $this->input->post('additionalContactNumber', true);
        $contactInAdditionEmail = $this->input->post('contactInAdditionEmail', true);
        $additionalEmailId = $this->input->post('additionalEmailId', true);

//        if($this->session->has_userdata('role') && $this->session->userdata('role')->role_name === 'PFC'){
//            $pfcName = $this->users_model->get_by_doc_id($this->session->userdata('userId')->{'$id'});
//            $pfcName->{'_id'} = new ObjectId($pfcName->{'_id'}->{'$id'});
//        }else{
//            $pfcName = '';
//        }
        $this->load->helper("fileupload");
        $tentative_date = date_cal();
//        $tentative_date=date('d-m-Y', strtotime("+8 days"));
        //var_dump($tentative_date);die;
        $roleCondition = ['$expr'=>[
            '$in' => ['$slug', ['DA','DPS','AA','RR','RA']]
        ]];
        $this->load->helper('model');
        $process_users = prepareProcessUserList($roleCondition, $relatedOfficials,true);

        $inputs = [
            'appeal_id'                          => $appealApplicationId,
            'ref_appeal_id'                      => $appealApplicationPrevious->appeal_id,
            'appl_ref_no'                        => $appealApplicationPrevious->appl_ref_no,
            'gender'                             => $appealApplicationPrevious->gender,
            'applicant_name'                     => $this->input->post('nameOfThePerson',true),
            'contact_number'                     => ($contactNumber != 'NA')? $contactNumber : '1234567890',// dummy fallback number
            'contact_in_addition_contact_number' => $contactInAdditionContactNumber ? true: false,
            'additional_contact_number'          => $additionalContactNumber,
            'email_id'                           => $emailId,
            'contact_in_addition_email'          => $contactInAdditionEmail? true: false,
            'additional_email_id'                => $additionalEmailId,
            // 'address_of_the_person'              => $this->input->post('addressOfThePerson',true),
            'address_of_the_person'              => empty($this->input->post('addressOfThePerson'))? "":$this->input->post('addressOfThePerson'),
            'village'                            => $this->input->post('village'),
            'district'                           => $this->input->post('district'),
            'police_station'                     => $this->input->post('policestation'),
            'circle'                             => $this->input->post('circle'),
            'post_office'                        => $this->input->post('postoffice'),
            'pincode'                            => $this->input->post('pincode'),
            'name_of_service'                    => $this->input->post('nameOfService',true),
            'date_of_application'                => new \MongoDB\BSON\UTCDateTime((strtotime($this->input->post('dateOfApplication', true))*1000)),
            'appeal_type'                        => 2,
            'name_of_PFC'                        => $appealApplicationPrevious->name_of_PFC ?? 'NA',
            'applied_by'                         => $this->session->userdata('role')->slug ?? 'appellant',
            'applied_by_user_id'                 => $this->session->userdata('userId')? new ObjectId($this->session->userdata('userId')->{'$id'}) : NULL,
            'appeal_description'                 => $this->input->post('appealDescription',true),
            'ground_for_appeal'                  => $this->input->post('groundForAppeal', true),
            'relief_sought_for'                  => $this->input->post('reliefSoughtFor', true),
            'documents'                          => moveFile(0, "appeal_attachments"),
            'process_status'                     => null,
            'service_id'                         => new ObjectId($service->{'_id'}->{'$id'}),
            'location_id'                        => new ObjectId($location->{'_id'}->{'$id'}),
            'process_users'                      => $process_users,
            'tentative_hearing_date'             => new \MongoDB\BSON\UTCDateTime(strtotime($tentative_date)*1000),
            'appeal_expiry_status'               => !empty($this->input->post("appealDelayDescription"))? true : false,
            'second_appeal_delay_reason'         => !empty($this->input->post("appealDelayDescription"))? $this->input->post("appealDelayDescription") : '',
            'created_at'                         => new MongoDB\BSON\UTCDateTime(strtotime($nowDb) * 1000)
        ];
        // pre($inputs);
       $this->appeal_application_model->insert($inputs);
       // update first appeal
        $updateFirstAppealData = ['second_appeal_applied' => true ];
       $this->appeal_application_model->update($appealApplicationPrevious->_id->{'$id'},$updateFirstAppealData);
        $this->session->set_userdata('appeal_id',$appealApplicationId);
        // TODO :: use scheduling or job , queue for email and sms sending
        $officialUsers = $this->users_model->get_where_in('_id',array($relatedOfficials->dps_id, $relatedOfficials->appellate_id));
        // official notification
        $officialToMailCSV = '';
        foreach ($officialUsers as $official) {
            // send sms
            $msgTemplate = $this->config->item('second_appeal_received');
            $msgTemplate['msg'] = str_replace("{{appeal_id}}", $appealApplicationId, $msgTemplate['msg']);
            $this->sms->send($official->mobile, $msgTemplate);
            // to mail
            $officialToMailCSV .= $official->email . ',';
        }
        $officialToMailCSV = trim($officialToMailCSV, ',');
        // send email
        $emailBody = '<p>Dear Ma&amp;am/Sir,</p>
                            <p style="text-indent: 14px">Second Appeal is submitted for Application No.' . $appealApplicationPrevious->appl_ref_no . '.</p>
                            <p>Appeal ID : ' . $appealApplicationId . '</p>
                            <p>Submitted on : ' . $now . '</p>
                            <p>Ground of Appeal : ' . $inputs['ground_for_appeal'] . '</p>';
        $this->remail->sendemail($officialToMailCSV, "Second Appeal Received", $emailBody);
        $this->load->helper('app');
        $applicantNotifiable = getApplicantNotifiable($contactNumber, $emailId, $contactInAdditionContactNumber, $additionalContactNumber, $contactInAdditionEmail, $additionalEmailId);
        // applicant notification
        foreach ($applicantNotifiable['mobile'] as $contactMobile) {

            $msgTemplate = $this->config->item('second_appeal_submitted');
            $msgTemplate['msg'] = str_replace("{{appeal_id}}", $appealApplicationId, $msgTemplate['msg']);
            $msgTemplate['msg'] = str_replace("{{ground_for_appeal}}", $inputs['ground_for_appeal'], $msgTemplate['msg']);
            $this->sms->send($official->mobile, $msgTemplate);
        }
        // send email with  ack attached to mail
        $this->load->helper('appeal');
        generate_n_send_mail_with_ack_attachment($appealApplicationId,"second");

        redirect(base_url('appeal/second/ack'));
    }


    public function acknowledgement(){
        if($this->input->method(true) !== 'GET'){
            show_error('Method Not Allowed','403','Invalid Method');
        }

        $appeal_id = $this->session->userdata('appeal_id');

        if(!$appeal_id){
            redirect('/');
            exit(200);
        }
        $appealApplication = $this->appeal_application_model->get_with_related_by_appeal_id($appeal_id);

        $applicationData = $this->applications_model->get_by_appl_ref_no($appealApplication->appl_ref_no);

        $data = [
            'appealApplication' => $appealApplication,
            'applicationData' => $applicationData
        ];
        $this->load->view('includes/frontend/header');
        $this->load->view('second_appeals/acknowledgement',$data);
        $this->load->view('includes/frontend/footer');
    }

    /**
     * @param $appeal
     * @param array $data
     * @return array
     */
    private function check_second_appeal_timeline($appeal, array $data): array
    {
        $allowOtp = false;

        $lastAppealProcess =  $this->appeal_process_model->last_where(array('appeal_id' => $appeal->appeal_id,'action' => array('$in' => array('resolved','rejected'))));

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
                $allowOtp = true;
                $data["status"] = true;
            } else {
                $data["status"] = false;
                $data["error_msg"] = 'Your application timeline for appeal submission is expired.';
            }
        } else {
            $data["status"] = false;
            $data["error_msg"] = "Appeal Reference No and Mobile No Does not match any record.";
        }
        return array($allowOtp, $data);
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
        $appealApplicationPrevious = $this->ams_model->get_where(['appeal_id' => $ref_no]);
        $this->load->helper('model');
        $appealApplicationPrevious = getAppealApplications($appealApplicationPrevious,$appealApplicationPrevious->{0}->{'_id'}->{'$id'});

//        if(empty($appealApplicationPrevious)){
//            $appealApplicationPrevious[0] = $this->appeal_application_model->get_appeal_details_without_ref($ref_no);
//        }
//        pre($appealApplicationPrevious);
        // pre($appealApplicationPrevious[0]->process_users);
        $this->load->helper('model');
        $status = checkTimeLine($ref_no,$appealApplicationPrevious[0]);
        // $appealApplicationPrevious=$appealApplicationPrevious[0];
// var_dump($appealApplicationPrevious->ref_appeal_id);die;
        if (!$status['status']) {
            show_error($status['error_msg'], 403, 'Something Went Wrong!');
        }
//
//        if (!isset($appealApplicationPrevious[0]->appl_ref_no)) {
//            redirect(base_url('appeal/userarea'));
//        }

        $applicationData = $this->applications_model->get_by_appl_ref_no($appealApplicationPrevious[0]->appl_ref_no);
        // pre($applicationData);
        $this->load->model('location_model');
        if(isset($applicationData)){
            $service  = $this->services_model->first_where(array('service_id' => $applicationData->initiated_data->base_service_id));
            $location = $this->location_model->first_where(array('location_name' => "" . $applicationData->initiated_data->submission_location . ""));
        }else{
            $service  = $this->services_model->first_where(array('service_id' => $appealApplicationPrevious[0]->service_details->service_id));
            $location = $this->location_model->first_where(array('location_id' => $appealApplicationPrevious[0]->location_details->location_id));
        }
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
            'delay_reason'=>$status['reason']
            //            'cap' => $cap,
        ];

        $this->load->view('userarea/includes/header');
        $this->load->view('userarea/second_appeals_apply', $data);
        $this->load->view('userarea/includes/footer');
    }
}
