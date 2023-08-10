<?php

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Frontend
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('upms/users_model');
    } //End of __construct()

    public function index()
    {
        $this->load->view('includes/frontend/header');
        $this->load->view('upms/login_view');
        $this->load->view('includes/frontend/footer');
    } //End of index()

    function process()
    {
        $this->load->library("form_validation");
        $this->form_validation->set_rules("signin_id", "Signin ID", "required");
        $this->form_validation->set_rules("signin_pass", "Password", "required");
        $this->form_validation->set_rules("inputcaptcha", "Captcha", "required|exact_length[6]");
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("flashMsg", "Invalid/Empty Input fields!");
            $this->index();
        } else {
            $inputCaptcha = $this->security->xss_clean($this->input->post("inputcaptcha"));
            $sessCaptcha = $this->session->userdata('captchaCode');
            if ($sessCaptcha === $inputCaptcha) {
                $signin_id = $this->security->xss_clean($this->input->post("signin_id"));
                $input_pass = $this->security->xss_clean($this->input->post("signin_pass"));
                $login_role = $this->security->xss_clean($this->input->post("role"));

                $unameRow = $this->users_model->get_row(array("login_username" => $signin_id));
                $emailRow = $this->users_model->get_row(array("email_id" => $signin_id));
                $mobileRow = $this->users_model->get_row(array("mobile_number" => $signin_id));
                if ($unameRow) {
                    $result = $unameRow;
                } elseif ($emailRow) {
                    $result = $emailRow;
                } elseif ($mobileRow) {
                    $result = $mobileRow;
                } else {
                    $result = false;
                } //End of if else
                if ($result) {
                    $user_status = $result->status;
                    $exported_from = $result->exported_from ?? '';
                    $password_set = $result->password_set ?? '';
                    if ($user_status) {
                        if (($exported_from === 'CUSTOM_CSV_FILE') && ($password_set !== 'FROM_FIRST_LOGIN')) {
                            $this->set_pass_and_login($result, $input_pass);
                        } elseif (crypt($input_pass, $result->login_password) == $result->login_password) {
                            $user_type = $result->user_types->utype_id ?? 3; // 1 for developer/superadmin, 2 for stateadmin, 3 for staffs
                            $service_codes = array();
                            $user_services = $result->user_services ?? array();
                            if (count($user_services)) {
                                foreach ($result->user_services as $userServices) {
                                    $service_codes[] = $userServices->service_code;
                                } //End of foreach()
                            } //End of if else

                            $offices_codes = array();
                            $offices_info = $result->offices_info ?? array();
                            if (is_array($offices_info) && count($offices_info)) {
                                foreach ($offices_info as $officesInfo) {
                                    $offices_codes[] = $officesInfo->office_code;
                                } //End of foreach()
                            } //End of if

                            $additional_role_codes = array();
                            $additional_level_nos = array();
                            $additionalRoles = $result->additional_roles ?? array();
                            if (is_array($additionalRoles) && count($additionalRoles)) {
                                foreach ($additionalRoles as $additionalRole) {
                                    $additional_role_codes[] = $additionalRole->role_code;
                                    $additional_level_nos[] = $additionalRole->level_no;
                                } //End of foreach()
                            } //End of if

                            if($login_role){
                                $login_as= json_decode(html_entity_decode($login_role));
                                $loggedInUserInfo = array(
                                    "loggedin_user_service_code" => $service_codes,
                                    "loggedin_user_role_code" => $login_as->role_code ?? '',
                                    "loggedin_login_username" => $result->login_username,
                                    "loggedin_user_level_no" => $login_as->level_no ?? '',
                                    "loggedin_user_fullname" => $result->user_fullname,
                                    "upms_user_type" => $user_type,
                                    "reports_url" => $result->reports_url ?? '',
                                    "offices_codes" => $offices_codes,
                                    "additional_role_codes" => $additional_role_codes,
                                    "additional_level_nos" => $additional_level_nos,
                                    "upms_login_status" => true
                                );
                            }
                            else{
                                $loggedInUserInfo = array(
                                    "loggedin_user_service_code" => $service_codes,
                                    "loggedin_user_role_code" => $result->user_roles->role_code ?? '',
                                    "loggedin_login_username" => $result->login_username,
                                    "loggedin_user_level_no" => $result->user_levels->level_no ?? '',
                                    "loggedin_user_fullname" => $result->user_fullname,
                                    "upms_user_type" => $user_type,
                                    "reports_url" => $result->reports_url ?? '',
                                    "offices_codes" => $offices_codes,
                                    "additional_role_codes" => $additional_role_codes,
                                    "additional_level_nos" => $additional_level_nos,
                                    "upms_login_status" => true
                                );
                            }

                            $this->session->set_userdata($loggedInUserInfo);

                            $this->session->set_flashdata("flashMsg", "Welcome Mr. " . $result->user_fullname . "!");
                            if ($user_type == 1) {
                                redirect(site_url('spservices/upms/dashboard'));
                            } else {
                                redirect(site_url('spservices/upms/dashboard'));
                            } //End of if else
                        } else {
                            $this->session->set_flashdata("flashMsg", "Password does not matched!");
                            redirect(site_url("spservices/upms/login"));
                        } //End of if else
                    } else {
                        $this->session->set_flashdata("flashMsg", "Your account has been deactivated!");
                        redirect(site_url("spservices/upms/login"));
                    } //End of if else                    
                } else {
                    $this->session->set_flashdata("flashMsg", "Sign in id does not exist!");
                    redirect(site_url("spservices/upms/login"));
                } //End of if else
            } else {
                $this->session->set_flashdata("flashMsg", "Captcha does not mached!. Please try again");
                $this->index();
            } //End of if else            
        } // End of if else
    } //End of process()

    private function set_pass_and_login($result, $login_password)
    {
        $user_type = $result->user_types->utype_id ?? 3;
        $service_codes = array();
        $user_services = $result->user_services ?? array();
        if (count($user_services)) {
            foreach ($result->user_services as $userServices) {
                $service_codes[] = $userServices->service_code;
            } //End of foreach()
        } //End of if else        
        $offices_codes = array();
        $offices_info = $result->offices_info ?? array();
        if (is_array($offices_info) && count($offices_info)) {
            foreach ($offices_info as $officesInfo) {
                $offices_codes[] = $officesInfo->office_code;
            } //End of foreach()
        } //End of if

        $additional_role_codes = array();
        $additional_level_nos = array();
        $additionalRoles = $result->additional_roles ?? array();
        if (is_array($additionalRoles) && count($additionalRoles)) {
            foreach ($additionalRoles as $additionalRole) {
                $additional_role_codes[] = $additionalRole->role_code;
                $additional_level_nos[] = $additionalRole->level_no;
            } //End of foreach()
        } //End of if

        $loggedInUserInfo = array(
            "loggedin_user_service_code" => $service_codes,
            "loggedin_user_role_code" => $result->user_roles->role_code ?? '',
            "loggedin_login_username" => $result->login_username,
            "loggedin_user_level_no" => $result->user_levels->level_no ?? '',
            "loggedin_user_fullname" => $result->user_fullname,
            "upms_user_type" => $user_type,
            "reports_url" => $result->reports_url ?? '',
            "offices_codes" => $offices_codes,
            "additional_role_codes" => $additional_role_codes,
            "additional_level_nos" => $additional_level_nos,
            "upms_login_status" => true
        );
        $this->session->set_userdata($loggedInUserInfo);

        $salt = uniqid("", true);
        $algo = "6";
        $rounds = "5050";
        $cryptSalt = '$' . $algo . '$rounds=' . $rounds . '$' . $salt;
        $hashedPassword = crypt($login_password, $cryptSalt);
        $data = array(
            'login_password' => $hashedPassword,
            'password_set' => 'FROM_FIRST_LOGIN'
        );
        $this->users_model->update_where(['login_username' => $result->login_username], $data);

        $this->session->set_flashdata("flashMsg", "Welcome Mr. " . $result->user_fullname . "!");
        redirect(site_url('spservices/upms/dashboard'));
    } //End of set_pass_and_login()

    function logout()
    {
        if ($this->session->upms_login_status) {
            $this->session->unset_userdata("loggedin_user_service_code");
            $this->session->unset_userdata("loggedin_user_role_code");
            $this->session->unset_userdata("loggedin_login_username");
            $this->session->unset_userdata("loggedin_user_level_no");
            $this->session->unset_userdata("loggedin_user_fullname");
            $this->session->unset_userdata("upms_user_type");
            $this->session->unset_userdata("reports_url");
            $this->session->unset_userdata("offices_codes");
            $this->session->unset_userdata("additional_role_codes");
            $this->session->unset_userdata("additional_level_nos");
            $this->session->unset_userdata("upms_login_status");
            $this->session->set_flashdata("flashMsg", "You have been successfully logout!");
        } else {
            $this->session->set_flashdata("flashMsg", "Session does not exist!");
        } // End of if else
        redirect(site_url("spservices/upms/login"));
    } //End of logout()

    public function resetpass($username = null)
    {
        $user = $this->users_model->get_row(array("login_username" => $username));
        if ($user) {
            $salt = uniqid("", true);
            $algo = "6";
            $rounds = "5050";
            $cryptSalt = '$' . $algo . '$rounds=' . $rounds . '$' . $salt;
            $hashedPassword = crypt('admin', $cryptSalt);
            $this->users_model->update_where(['login_username' => $username], ['login_password' => $hashedPassword]);
            redirect('spservices/upms/login/logout');
        } else {
            $this->session->set_flashdata('success', 'User does not exist!');
            redirect('spservices/upms/');
        } // End of if else
    } //End of resetpass()

    function createcaptcha()
    {
        $captchaDir = FCPATH . '/storage/captcha/';
        array_map('unlink', glob("$captchaDir*.jpg")); //Delete all jps files before create a new
        $this->load->helper('captcha');
        $config = array(
            'img_path' => $captchaDir,
            'img_url' => base_url('storage/captcha'),
            'img_width' => '150',
            'img_height' => '30',
            'expiration' => 7200,
            'word_length' => 6,
            'font_path' => FCPATH . 'assets/site/theme1/font/roboto/Roboto-Regular.ttf',
            'font_size' => 16,
            'img_id' => 'capimg',
            //'pool' => '0123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ',
            'pool' => '0123456789',
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 240, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 255, 200)
            )
        );
        $captcha = create_captcha($config);
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode', $captcha['word']);
        echo $captcha['image'];
    } //End of createcaptcha()

    public function checkroles()
    {
        $signin_id = $this->security->xss_clean($this->input->post("username"));

        $unameRow = $this->users_model->get_row(array("login_username" => $signin_id));
        $emailRow = $this->users_model->get_row(array("email_id" => $signin_id));
        $mobileRow = $this->users_model->get_row(array("mobile_number" => $signin_id));
        if ($unameRow) {
            $result = $unameRow;
        } elseif ($emailRow) {
            $result = $emailRow;
        } elseif ($mobileRow) {
            $result = $mobileRow;
        } else {
            $result = false;
        } //End of if else
        if ($result) {
            $user_status = $result->status;
            if ($user_status) {
                $additional_roles = array();
                $additionalRoles = $result->additional_roles ?? array();
                if (is_array($additionalRoles) && count($additionalRoles)) {
                    foreach ($additionalRoles as $additionalRole) {
                        $additional_roles[] = (array)$additionalRole;
                    } //End of foreach()
                    $additional_roles[] = (array)$result->user_roles;
                    $data['roles'] = $additional_roles;
                    $data['status'] = true;
                } //End of if
                else{
                    $data['status'] = false;
                }
                echo json_encode($data);
            }
        }
    }
}//End of Login