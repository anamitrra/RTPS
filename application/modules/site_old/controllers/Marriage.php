<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Marriage extends Site_Controller
{
    private $settings;

    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
        $this->load->model('settings_model');


        $this->settings = $this->settings_model->get_settings('marriage_cert');

        // pre($this->lang);
    }
    public function index()
    {
        $data['settings'] = $this->settings;
        // $data['captcha_img'] = $this->create_captcha()['filename'];

        $this->render_view_new('marriage', $data);
    }

    public function verify_applicant()
    {
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|xss_clean|strip_tags|min_length[10]|max_length[10]|regex_match[/^[6-9]\d{9}$/]');
        $this->form_validation->set_rules('app_ref_no', 'Application Ref. Number', 'trim|required|xss_clean|strip_tags|min_length[6]');

        if ($this->form_validation->run() == FALSE) {

            $this->index();  // load the form again
        } else {
            // Verify mobile & appl_ref_no
            $mobile_number = trim($this->input->post('mobile_number', true));
            $app_ref_no = trim($this->input->post('app_ref_no', true));

            /// Too lazy to use a Model ///

            $this->mongo_db->switch_db('mis');

            // Get only marriage applications
            $rowCount = $this->mongo_db->mongo_like_count(array('initiated_data.base_service_id' => ['$in' => ['12050010', '1205']], 'initiated_data.appl_ref_no' => $app_ref_no, 'initiated_data.attribute_details.mobile_number' => $mobile_number), 'application_bkup');

            // Switch back to Portal DB
            $this->mongo_db->switch_db('portal');

            if ($rowCount) {
                $this->session->set_userdata('app_ref_no', $app_ref_no);
                $this->session->set_userdata('mobile_number', $mobile_number);

                // Application found. Verify mobile no. and send OTP
                $this->send_otp($mobile_number);
            } else {
                // No applications found
                $this->session->set_flashdata('errorMsg', $this->settings->messages[0]->{"$this->lang"});
                $this->session->set_flashdata('error_no_record', true);
                // redirect(base_url('site/marriage/index'));
                $this->index();
            }
        }
    }


    private function send_otp($mobileNumber)
    {
        $this->load->library('sms');
        // $this->load->config('sms_template');
        // $msgTemplate = $this->config->item('otp');

        $this->sms->send_otp($mobileNumber, $mobileNumber);

        // $_SESSION['mobileNumber'] = $mobileNumber;

        $this->session->set_flashdata('success_otp', $this->settings->messages[1]->{"$this->lang"});
        $this->session->set_flashdata('otp_sent', true);
        // redirect('site/marriage/index');
        $this->index();
    }


    public function verify_otp()
    {
        $this->form_validation->set_rules('otp_number', 'OTP', 'trim|required|xss_clean|strip_tags|min_length[6]|max_length[6]');
        if ($this->form_validation->run() == FALSE) {
            $status["status"] = false;
            $status["msg"] = validation_errors('<small class="text-danger fw-bold text-capitalize">', '</small>');

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($status));
        }

        $this->load->library('sms');
        $otpVerificationStatus = $this->sms->verify_otp($this->session->userdata('mobile_number'), $this->session->userdata('mobile_number'), $this->input->post('otp_number', true));
        date_default_timezone_set('Asia/Kolkata');

        // pre($otpVerificationStatus);

        if ($otpVerificationStatus['status'] == true) {
            // Verified data are stored for future reference
            $userData = [
                'mobile_number' => $this->session->userdata('mobile_number'),
                'verified_at'   => new MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];
            $this->load->model('verified_userdata_model');
            $this->verified_userdata_model->insert($userData);

            $otpVerificationStatus['msg'] = '<small class="text-capitalize fw-bold text-success">' .  $this->settings->messages[2]->{"$this->lang"}  . '</small>';
        } else {

            $otpVerificationStatus['msg'] = '<small class="text-capitalize fw-bold text-danger">' . $otpVerificationStatus['msg'] . '!</small>';
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($otpVerificationStatus));
    }


    public function download_cert()
    {
        $ref = $this->session->userdata('app_ref_no');

        $key = 'mrg_secret_key';

        $headers = array(
            'Content-Type:application/json',
            //'Authorization: Basic '. base64_encode("user:password") // place your auth details here
        );
        $payload = array(
            'id' => 1,
        );

        // $process = curl_init("http://10.177.0.33:800/rtps_all_api/mrgapi.php?application_ref_no=$ref"); //your API url
        $process = curl_init("http://localhost/NIC/rtps_all_api/mrgapi.php?application_ref_no=$ref"); //your API url
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($process);

        if (curl_errno($process) || curl_getinfo($process)['http_code'] >= 400) {
            $this->session->set_flashdata('download_cert_error', $this->settings->messages[3]->{"$this->lang"});
            redirect(base_url('site/marriage/index'));
        } elseif ($return != 'Failure') {

            $data = json_decode($return, true);
            $certi = base64_decode($data['certi']);
            $hash =  base64_decode($data['hash']);

            if (empty($certi)) {

                $this->session->set_flashdata('download_cert_error', $this->settings->messages[3]->{"$this->lang"});
                redirect(base_url('site/marriage/index'));
            }

            $options = 0;

            // Non-NULL Initialization Vector for decryption
            $decryption_iv = '1234567891011121';

            $ciphering = "AES-128-CTR";


            // Use openssl_decrypt() function to decrypt the data
            $decryption = openssl_decrypt(
                $certi,
                $ciphering,
                $key,
                $options,
                $decryption_iv
            );

            //var_dump($decryption);die;

            $auth = sha1($decryption, $key);

            $valid = strcmp($hash, $auth);

            if ($valid == 0) {

                //finally print your API response
                // echo '<PRE>';
                // header('Content-Type: application/pdf');
                // echo $decryption;
                // echo '</PRE>';

                // Get the mimetype
                $finfo = finfo_open();
                $mime_type = finfo_buffer($finfo, $decryption, FILEINFO_MIME_TYPE);
                finfo_close($finfo);
                $ext = (get_file_extension($mime_type) == 'N/A') ? '' : get_file_extension($mime_type);

                // redirect output to client browser
                header("Content-type: {$mime_type}");
                header('Content-Disposition: attachment;filename="marriage_cert_' . $ref . ".{$ext}");
                header('Cache-Control: max-age=0');
                echo $decryption;
            } else {
                // Invalid cert checksum

                echo 'Access Denied';
            }
        } elseif ($return == 'Failure') {
            $this->session->set_flashdata('download_cert_error', $this->settings->messages[4]->{"$this->lang"});
            redirect(base_url('site/marriage/index'));
        } else {
            $this->session->set_flashdata('download_cert_error', $this->settings->messages[3]->{"$this->lang"});
            redirect(base_url('site/marriage/index'));
        }
    }

    private function create_captcha()
    {
        $captchaDir = FCPATH . 'storage/PORTAL/captcha/';
        // Delete old captcha images
        array_map('unlink', glob("$captchaDir*.jpg"));

        $this->load->helper('captcha');
        $config = array(
            'img_path' => $captchaDir,
            'img_url' => base_url('storage/PORTAL/captcha/'),
            'font_path' => APPPATH . 'sys/fonts/texb.ttf',
            'img_width' => '150',
            'img_height' => 30,
            'expiration' => 7200,
            'word_length' => 6,
            'font_size' => 16,
            'img_id' => 'capimg',
            'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(94, 20, 38),
                'text' => array(0, 0, 0),
                'grid' => array(178, 184, 194)
            )
        );
        $captcha = create_captcha($config);
        $this->session->unset_userdata('captchaCodePortal');
        $this->session->set_userdata('captchaCodePortal', $captcha['word']);

        if ($this->input->is_ajax_request()) {
            $status["capth_img_file"] = base_url('storage/PORTAL/captcha/' . $captcha['filename']);
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($status));
        } else {
            return $captcha;
        }
    } //End of createcaptcha()
}
