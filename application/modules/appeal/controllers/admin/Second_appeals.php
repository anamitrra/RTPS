<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Second_appeals extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('role');
        $this->load->library('form_validation');

    }

    /**
     * apply for appeal by others
     *
     * @return void
     */
    public function apply_for_first_appeal_by_others()
    {
        pre('ok');
        $this->load->model('applications_model');
        $this->load->model('services_model');
        $this->load->model('location_model');
        $this->load->model('official_details_model');
        $this->load->model('ams_model');
        $this->load->model('users_model');
        
        $ref_no = $this->input->get('id');
        $applicationData = $this->applications_model->get_by_appl_ref_no($ref_no);
         $submission_date=date('d-m-Y', strtotime($this->mongo_db->getDateTime($applicationData->initiated_data->submission_date)));
         // var_dump($applicationData);die;
        $checkApplicable=$this->checkTimeLine($ref_no);
        if ($checkApplicable['timeline2_expired']) {

            show_error($checkApplicable['error_msg'], 403, 'Service not available!');
        } else {
            if ($checkApplicable['isReasonRequired']) {
                $this->session->set_userdata("appeal_after_30", TRUE);
            } else {
                $this->session->set_userdata("appeal_after_30", FALSE);
            }
        }
        try {
            $service  = $this->services_model->first_where(array('service_id' => $applicationData->initiated_data->base_service_id));
            $location = $this->location_model->first_where(array('location_name' => "" . $applicationData->initiated_data->submission_location . ""));
            //pre($applicationData->initiated_data->submission_location);
            if (!$service || !$location) {
                throw new Exception();
            }
        } catch (Exception $exception) {
            log_message('error', $exception);
            show_error('No record available for the service.',403,'Service Not Found');
            exit(404);
        }
        // var_dump($service);die;
        $relatedOfficialsFilter = array(
            'service_id'  => new ObjectId($service->{'_id'}->{'$id'}),
            'location_id' =>  new ObjectId($location->{'_id'}->{'$id'}),
        );
        $relatedOfficials = $this->official_details_model->first_where($relatedOfficialsFilter);
        if (!$relatedOfficials) {
            show_error('No Officials are mapped for this service.', 500);
        }
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
            'isReasonRequired'=>$checkApplicable['isReasonRequired']
        ];
        $this->load->view('includes/header', array("pageTitle" => "Dashboard | Apply For Appeal"));
        $this->load->view('admin/apply_for_appeal', $data);
        $this->load->view('includes/footer');
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
                    $this->session->set_userdata("appeal_after_30",FALSE);
                }else{
                    $this->session->set_userdata("appeal_after_30",TRUE);
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

    private function checkTimeLine($ref_no){

      $this->load->model('appeals_model');
      $value = $this->appeals_model->search($ref_no);
      $data=array(
        'timeline2_expired'=>false,
        'timeline1_expired'=>false,
      );
    //  pre($value);
      if ($value && is_object($value)) {
          if($value->Reject){
              // Rejected
              if($value->rejected_before_service_timeline){
                  $data['timeline1_expired'] = true;
              }

          }else{
              // Not Rejected
              if($value->timeline_1_expired){
                  $data['timeline1_expired'] = true;
              }
              if($value->timeline_2_expired){
                  $data['timeline2_expired'] = true;
              }

          }
          $this->session->set_userdata('timeline1_expired',$data['timeline1_expired']);
          $this->session->set_userdata('timeline2_expired',$data['timeline2_expired']);

          if(isset($data['timeline2_expired']) && $data['timeline2_expired']){
              // cannot apply
              $data["status"] = false;
              $data['isReasonRequired']=false;
              $data["error_msg"] = "Your application timeline for appeal submission is expired.";
          }elseif (isset($data['timeline1_expired']) && $data['timeline1_expired']){
              // apply with reason
              $data['isReasonRequired']=true;
              $data["status"] = true;
              $data["error_msg"] = "Your application timeline for appeal submission is expired.But is under consideration.";
          }else {
            //can apply
              $data['isReasonRequired']=false;
              $data["status"] = true;
              $data["error_msg"] = "";
          }

      } else {
          $data['isReasonRequired']=false;
          $data["status"] = false;
          $data["error_msg"] = "Application Ref No Does not match any record.";
      }

      return $data;
    }

    /**
     * process
     *
     * @return void
     */
    public function process()
    {
        if($this->session->userdata('role')->slug !== 'DA'){
            if(!$this->session->userdata('isMobileVerifiedForFirstAppeal')){
                $this->session->set_flashdata("error", 'Mobile number not verified.');
                $url=base_url('appeal/first/apply/others/?id='.$this->input->post('applicationNumber', true));
                redirect($url);
            }
            $this->session->set_userdata('isMobileVerifiedForFirstAppeal',false);
        }

        $this->load->model('ams_model');
        $this->load->model('applications_model');
        $this->load->model('services_model');
        $this->load->model('location_model');
        $this->load->model('official_details_model');
        $this->load->model('users_model');

        if (!$this->session->userdata("isLoggedIn")) {
            redirect(base_url('appeal/admin/login'));
        }
        //        $this->load->helper('captcha');
        //        $this->form_validation->set_rules('captcha', 'Captcha', 'validate_captcha|trim');
        $this->form_validation->set_rules('additionalContactNumber', 'Additional Contact Number', 'trim');
        $this->form_validation->set_rules('additionalEmailId', 'Additional Email ID', 'trim');
        $this->form_validation->set_rules('addressOfThePerson', 'Address of the Appellant', 'trim|required');
        $this->form_validation->set_rules('groundForAppeal', 'Ground for appeal', 'trim|required');
        $this->form_validation->set_rules('appealDescription', 'Appeal Description', 'trim|required');
        $this->form_validation->set_rules('reliefSoughtFor', 'Relief sought for', 'trim|required');
        $this->form_validation->set_rules('gender', 'Gender', 'required|trim');
        if($this->input->post('ReasonRequired')){
          $this->form_validation->set_rules('appellateReasonFordelay', 'Describing the Reason for delay', 'required|trim');
        }
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("error", validation_errors());
            $this->session->set_flashdata("old", $this->input->post());
            $url=base_url('appeal/first/apply/others/?id='.$this->input->post('applicationNumber', true));
            redirect($url);
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
//        if ($this->session->has_userdata('role') && $this->session->userdata('role')->role_name === 'PFC') {
//            $pfcName = $this->users_model->get_by_doc_id($this->session->userdata('userId')->{'$id'});
//            $pfcName->{'_id'} = new ObjectId($pfcName->{'_id'}->{'$id'});
//        } else {
//            $pfcName = '';
//        }
        $this->load->helper("fileupload");
        $this->config->load('first_appeal');
        $process_users = [];
        $officialUserArray = [];
        foreach ($relatedOfficials->da_id_array as $daId){
            $officialUserArray[] = $daId;
            $process_users[] = [
                'userId'    => $daId,
                'action'    => $this->config->item('da_action'),
                'role_slug' => 'DA',
                'active'    => true
            ];
        }

        $process_users[]= [
            'userId' => $relatedOfficials->dps_id,
            'action' => $this->config->item('dps_action'),
            'role_slug' => 'DPS',
            'active' => false
        ];
        $process_users[]= [
            'userId' => $relatedOfficials->appellate_id,
            'action' => $this->config->item('aa_action'),
            'role_slug' => 'AA',
            'active' => false
        ];
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
            'address_of_the_person'           => $this->input->post('addressOfThePerson', true),
            'name_of_service'                 => $this->input->post('nameOfService', true),
            'date_of_application'             => new UTCDateTime((strtotime($this->input->post('dateOfApplication', true)) * 1000)),
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
            'created_at'                      => new UTCDateTime(strtotime($nowDb) * 1000),
            'reason_for_delay'                => !empty($this->input->post('appellateReasonFordelay', true)) ? $this->input->post('appellateReasonFordelay', true) : "",
            'delay_documents'                 => moveFile(0, "appeal_attachments_delay_reason"),
            'process_users'                   => $process_users
        ];
        $this->ams_model->insert($inputs);
//        $this->applications_model->update($this->input->post("_id"),array('first_appeal_applied'=>true));
        $this->session->set_userdata('appeal_id', $appealApplicationId);
        // TODO :: use scheduling or job , queue for email and sms sending
        $officialUsers = $this->users_model->get_where_in('_id',array($relatedOfficials->dps_id, $relatedOfficials->appellate_id));
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
        // send email with  ack attached to mail
        $this->load->helper('appeal');
        generate_n_send_mail_with_ack_attachment($appealApplicationId);
        redirect(base_url('appeal/admin/ack'));
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
                    //pre($applicationData);
            //pre($this->session->userdata);
        if (!$this->session->userdata('isLoggedIn') || !isset($appeal_id) || empty($applicationData)) {
            $url=base_url('appeal/first/apply/others/?id='.$appealApplication[0]->appl_ref_no);
            redirect($url);
        }

        $data = [
            'appealApplication' => $appealApplication,
            'applicationData' => $applicationData
        ];
        $this->load->view('includes/header', array("pageTitle" => "Dashboard | Acknowledgement"));
        $this->load->view('userarea/acknowledgement', $data);
        $this->load->view('includes/footer');
    }


    /**
     * @param $appeal
     * @param array $data
     * @return array
     */
    private function check_second_appeal_timeline($appeal): array
    {
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
        echo "Work in progress";
        $ref_no = $this->input->get('appeal_no');
        $this->load->model('applications_model');
        $this->load->model('services_model');
        $this->load->model('location_model');
        $this->load->model('official_details_model');
        $this->load->model('ams_model');
        $this->load->model('users_model');
        $this->load->model('appeal_process_model');
        $appealApplicationPrevious = $this->ams_model->get_with_related_by_appeal_id($ref_no);
        // var_dump($appealApplicationPrevious);die;
        $status=$this->check_second_appeal_timeline($appealApplicationPrevious);
        if(!$status['status']){
            show_error($status['error_msg'],403,'Something Went Wrong!');
        }
        if(isset($appealApplicationPrevious->ref_appeal_id)){
            redirect(base_url('appeal/applications/myappeals'));
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
            show_error('No Officials are mapped for this service.',403,'Something Went Wrong!');
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

        $this->load->view('includes/header');
        $this->load->view('admin/second_appeals_apply',$data);
        $this->load->view('includes/footer');
    }
    public function second_appeal_acknowledgement(){
        $this->load->model('ams_model');
        $this->load->model('applications_model');
        $appeal_id = $this->session->userdata('appeal_id');

        if(!$appeal_id){
            redirect(base_url('appeal/applications/myappeals'));
            exit(200);
        }
        $appealApplication = $this->ams_model->get_with_related_by_appeal_id($appeal_id);

        $applicationData = $this->applications_model->get_by_appl_ref_no($appealApplication->appl_ref_no);

        $data = [
            'appealApplication' => $appealApplication,
            'applicationData' => $applicationData
        ];
        $this->load->view('includes/header');
        $this->load->view('userarea/acknowledgement_second_appeal',$data);
        $this->load->view('includes/footer');
    }
    /**
     * preview_and_track
     *
     * @return void
     */
    public function preview_and_track()
    {
        $this->load->model('ams_model');
        $this->load->model('applications_model');
        $this->load->model('appeal_process_model');
        $this->load->model('users_model');
        $appeal_id = $this->input->get('appeal_no');
        $appealApplication = $this->ams_model->get_with_related_by_appeal_id($appeal_id);
        //pre($appealApplication);
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
            $appealApplicationPrevious = $this->ams_model->get_with_related_by_appeal_id($appealApplication->ref_appeal_id);
            $data['appealApplicationPrevious'] = $appealApplicationPrevious;
        }
        $this->load->view('includes/header');
        $this->load->view('userarea/preview_n_track', $data);
        $this->load->view('includes/footer');
    }

    public function send_sms_otp_to_verify_user(){
        $appl_ref_no = $this->input->get('appl_ref_no');
        $this->load->model('applications_model');
        $applicationData = $this->applications_model->first_where('initiated_data.appl_ref_no',$appl_ref_no);
        if(isset($applicationData) && !empty($applicationData)){
            $mobile_number = $applicationData->initiated_data->attribute_details->mobile_number;
            if(isset($mobile_number)){
                $this->load->helper('app');
                $status = array();
                $mobile = $mobile_number;
                if (isset($mobile) && $mobile != '') {
                    $this->lang->load('sms');
                    $msg = str_replace('{{appl_ref_no}}',$appl_ref_no,$this->lang->line('appeal_authorize_msg_with_otp'));
                    $this->sms->send_otp($mobile, $mobile, $msg);
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
    }

    public function verfiy_user(){
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|xss_clean|strip_tags|min_length[10]|max_length[10]|regex_match[/^[6-9]\d{9}$/]');
        $this->form_validation->set_rules('otp', 'OTP', 'trim|required|xss_clean|strip_tags|min_length[6]|max_length[6]');
        if ($this->form_validation->run() == FALSE) {
            $status["status"] = false;
            $status["msg"] = validation_errors();

            $this->session->userdata('isMobileVerifiedForFirstAppeal',$status['status']);
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($status));
        }
        $this->load->library('sms');
        $this->sms->verify_otp($this->input->post('mobile_number',true),'',$this->input->post('otp',true));

        // Verified data are stored for future reference
        $userData = [
            'mobile_number' => $this->input->post('mobile_number',true),
            'verified_at'   => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
        ];
        // $this->load->model('verified_userdata_model');
        // $this->verified_userdata_model->insert($userData);

        $status["status"] = true;
        $status["msg"] = 'Mobile Number verified successfully.';

        $this->session->set_userdata('isMobileVerifiedForFirstAppeal',$status['status']);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
    }
}
