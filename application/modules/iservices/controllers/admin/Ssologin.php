<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ssologin extends Frontend
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('epramaan');
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $this->isLoggedIn();
    }

    /**
     * This function used to check the user is logged in or not
     */

    function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            $this->load->view('includes/frontend/header');
            $this->load->view('admin/epramaan_login_msg');
            $this->load->view('includes/frontend/footer');
        } else {
            redirect('iservices/admin/dashboard');
        }
    }

    /**
     * This function used to logged in user
     */
    public function loginMe()
    {
        $this->load->library("encryption_custom");
        $password = $this->encryption_custom->decrypt($this->input->post("password"), 'asdf-ghjk-qwer-tyui');
        $email = $this->encryption_custom->decrypt($this->input->post("email"), 'asdf-ghjk-qwer-tyui');

        //pre($password);
        $this->load->model('admin/login_model');
        $this->load->model('admin/roles_model');

        $this->load->helper('captcha');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('captcha', 'Captcha', 'validate_captcha|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("error", validation_errors());
            redirect(base_url() . 'iservices/admin/login');
        }

        if ($email == "" || $password == "") {
            $this->index();
        } else {

            $result = $this->login_model->loginMe($email, $password);
            //  pre($result);
            if (isset($result) && $result != NULL) {
                $dept = $this->login_model->getDepartment($result->department[0]);
                $sessionArray = array(
                    'userId' => $result->_id,
                    'role' => $this->roles_model->get_role_info($result->roleId),
                    'image' => (isset($result->photo)) ? base_url($result->photo) : base_url("storage/images/avatar.png"),
                    'name' => $result->name,
                    'isLoggedIn' => TRUE,
                    'department_name' => $dept->department_name,
                    'department_id' => $dept->department_id,
                );
                //pre($sessionArray);
                $this->session->set_userdata($sessionArray);
                redirect(base_url() . 'iservices/admin/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Email or password mismatch');
                redirect(base_url() . 'iservices/admin/login');
            }
        }
    }

    /**
     * process_login
     *
     * @return void
     */
    public function process_login()
    {
        $this->load->model('admin/login_model');
        $this->load->model('admin/roles_model');
        $email = strval($this->input->post('contactEmail', true));
        $otp = $this->input->post("otp", TRUE);
        $user = $this->login_model->getUserMobileByEmail($email);

        $mobile = $user->mobile;
        $value = $this->sms->verify_otp($mobile, $mobile, $otp);
        if (!$value['status']) {
            $status["status"] = false;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($status));
        }

        $sessionArray = array(
            'userId' => $user->_id,
            'role' => $this->roles_model->get_role_info($user->roleId),
            'image' => (isset($user->photo)) ? base_url($user->photo) : base_url("storage/images/avatar.png"),
            'name' => $user->name,
            'isLoggedIn' => TRUE,
        );
        // pre($sessionArray);
        $this->session->set_userdata($sessionArray);
        $status["status"] = true;
        if (!empty($this->session->userdata('redirectTo'))) {
            $url = $this->session->userdata('redirectTo');
            if (strpos($url, "basundhara") == true) {
                $status["url"] = $url;
            } else {
                if (strpos($url, "admin") == false) {
                    $status["url"] = str_replace("iservices", "iservices/admin", $url);
                } else {
                    $status["url"] = $url;
                }
            }

            $this->session->unset_userdata('redirectTo');
        } else {
            $status["url"] = base_url() . 'iservices/admin/dashboard';
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
    }


    public function logout()
    {
        $user_data = $this->session->all_userdata();
        foreach ($user_data as $key => $value) {
            if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                $this->session->unset_userdata($key);
            }
        }
        $this->session->sess_destroy();
        redirect(base_url());
        // redirect('iservices/login');
    }

    public function elogout()
    {
        if ($this->session->userdata('pfc_epramaan_data')) {
            $this->epramaan->epramaan_slo('pfc');
        } else {
            $this->logout();
        }
    }
}
