<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class TrackAppeals extends frontend{

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('appeal_application_model');
        $this->load->model('appeals_model');
        $this->load->model('appeal_process_model');
        $this->load->module('dashboard')->model('users_model');
        $this->load->model('track_appeal_model');
    }

    // welcome page or page before login
    /**
     * login
     *
     * @return void
     */
    public function login(){
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
     * send_otp
     *
     * @return void
     */
    public function send_otp()
    {
        $appl_no = strval($this->input->post('appealNumber', true));
        $mobile = strval($this->input->post('contactNumber', true));
        $value = $this->track_appeal_model->searchAppeal($appl_no, $mobile);
        $status = array();

        $this->load->helper('captcha');
        $validatedCaptcha = validate_captcha();
        if(!$validatedCaptcha['status']){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($validatedCaptcha));
        }

        $status['appl_no'] = $appl_no;
        $status['m_no'] = $mobile;
//        pre($value);
        if (!empty($value)) {
            $status["msg"] = "Entered";
            $msg = "Your Otp for track appeal is {{otp}}";
            $this->sms->send_otp($mobile, $appl_no, $msg);            
            $status["status"] = true;
            
//            return $status;

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($status));
        } else {
//            return true;
            $status["status"] = false;
            $status["msg"] = "Something went wrong!!!";
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($status));
        }
    }

    /**
     * process_appeal_login
     *
     * @return void
     */
    public function process_appeal_login()
    {
        $appl_no = strval($this->input->post('appealNumber', true));
        $mobile = $this->input->post("contactNumber", TRUE);
        $otp=$this->input->post("otp", TRUE);

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
 

    /**
     * process
     *
     * @return void
     */
    // public function process(){
    //     if(!$this->session->userdata('opt_status')){
    //         redirect(base_url('appeal/login'));
    //     }
    //     $app_ref_no = $this->input->post('applicationNumber',true);
    //     $appealApplication = $this->appeal_application_model->get_by_appl_ref_no($app_ref_no);
    //     if(isset($appealApplication)){
    //         redirect(base_url('appeal/preview-n-track'));
    //     }
    //     $appealApplicationId = 'RTPS/APPEAL/'.substr(uniqid(),'6').'/'.date('d/m/Y'); // todo :: change letter
    //     $inputs = [
    //         'appeal_id' => $appealApplicationId,
    //         'appl_ref_no' => $app_ref_no,
    //         'applicant_name' => $this->input->post('nameOfThePerson',true),
    //         'contact_number' => $this->input->post('contactNumber',true),
    //         'contact_in_addition_contact_number' => $this->input->post('contactInAdditionContactNumber',true) ? true: false,
    //         'additional_contact_number' => $this->input->post('additionalContactNumber',true),
    //         'email_id' => $this->input->post('emailId',true),
    //         'contact_in_addition_email' => $this->input->post('contactInAdditionEmail',true)? true: false,
    //         'additional_email_id' => $this->input->post('additionalEmailId',true),
    //         'address_of_the_person' => $this->input->post('addressOfThePerson',true),
    //         'name_of_service' => $this->input->post('nameOfService',true),
    //         'date_of_application' => $this->input->post('dateOfApplication',true),
    //         'date_of_appeal' => $this->input->post('dateOfAppeal',true),
    //         'name_of_PFC' => $this->input->post('nameOfPFC',true),
    //         'appeal_description' => $this->input->post('appealDescription',true),
    //         'dps_name' => $this->input->post('DPSName',true),
    //         'dps_position' => $this->input->post('DPSPosition',true),
    //         'appellate_authority_name' => $this->input->post('appellateAuthorityName',true),
    //         'appellate_authority_designation' => $this->input->post('appellateAuthorityDesignation',true),
    //         'created_at' => new MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y H:i')) * 1000)
    //     ];
    //     $this->appeal_application_model->insert($inputs);
    //     redirect(base_url('appeal/preview-n-track'));
    // }

    /**
     * preview_and_track
     *
     * @return void
     */
    // public function preview_and_track(){
    //     if(!$this->session->userdata('opt_status')){
    //         redirect(base_url('appeal/login'));
    //     }
    //     $app_ref_no = $this->session->userdata('appl_ref_no');

    //     $appealApplication = $this->appeal_application_model->get_by_appl_ref_no($app_ref_no);
    //     $applicationData = $this->applications_model->get_by_appl_ref_no($appealApplication->appl_ref_no);

    //     $processFilterAppeal = array('appeal_id' => $appealApplication->appeal_id);
    //     $appealProcessList = $this->appeal_process_model->get_where($processFilterAppeal);
    //     $userList = $this->users_model->get_all();
    //     $data = [
    //         'appealApplication' => $appealApplication,
    //         'applicationData' => $applicationData,
    //         'appealProcessList' => $appealProcessList,
    //         'userList' => $userList
    //     ];
    //     $this->load->view('includes/frontend/header');
    //     $this->load->view('appeals/preview_n_track',$data);
    //     $this->load->view('includes/frontend/footer');
    // }

    // public function logout(){

    //     $user_data = $this->session->all_userdata();
    //     $this->session->unset_userdata('opt_status');
    //     $this->session->unset_userdata('appl_ref_no');
    //     $this->session->sess_destroy();

    //     redirect('appeals');
    // }
}