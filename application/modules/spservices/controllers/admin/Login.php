<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/admin_model');
        $this->load->library('session');
        $this->load->helper('captcha');
    }

    public function isloggin()
    {
        if ($this->session->userdata('administrator')) {
            if ($this->session->userdata('administrator')['is_administrator']) {
                redirect('spservices/admin/dashboard');
            } else {
                $this->session->set_flashdata('er_msg', 'Session expired.');
                $this->login();
            }
        } else {
            $this->login();
        }
    }
    public function index()
    {
        $this->isloggin();
    }

    public function login()
    {
        $this->load->view('includes/frontend/header');
        $this->load->view('adminviews/login');
        $this->load->view('includes/frontend/footer');
    }


    function createcaptcha()
    {
        $config = array(
            'img_path' => FCPATH . '/storage/captcha/',
            'img_url' => base_url('storage/captcha'),
            'img_width' => '150',
            'img_height' => 33,
            'expiration' => 7200,
            'word_length' => 6,
            'font_size' => 16,
            'img_id' => 'capimg',
            'pool' => '0123456789',
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(94, 20, 38),
                'text' => array(0, 0, 0),
                'grid' => array(178, 184, 194)
            )
        );
        $captcha = create_captcha($config);
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode', $captcha['word']);
        echo $captcha['image'];
        exit();
    } //End of createcaptcha()

    public function authenticate()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules("captcha", "Captcha", "trim|required");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('login_error', '' . validation_errors());
            $this->login();
        } else {
            $sessCaptcha = $this->session->userdata('captchaCode');
            $inputCaptcha = $this->input->post("captcha");
            if ($sessCaptcha !== $inputCaptcha) {
                $this->session->set_flashdata('login_error', 'Captcha does not mached!.');
                $this->login();
            } else {
                $filter = [
                    'login_username' => $this->input->post('username'),
                    'status' => 1
                ];
                $admin = (array)$this->admin_model->get_row($filter);
                // pre($admin);
                if (!empty($admin)) {
                    $password = $this->input->post('password');
                    if (verifyHashedPassword($password, $admin['login_password'])) {
                        $adminArray['admin_id'] = $admin['_id'];
                        $adminArray['name'] = $admin['user_fullname'];
                        $adminArray['username'] = $admin['login_username'];
                        $adminArray['mobile'] = $admin['mobile_number']; 
                        $adminArray['dept_id'] = $admin['dept_info']->dept_code ?? '';
                        $adminArray['dept_name'] = $admin['dept_info']->dept_name ?? '';
                        $adminArray['user_type'] = $admin['user_types']->utype_id;
                        $adminArray['is_administrator'] = true;
                        $adminArray['is_login'] = true;
                        $this->session->set_userdata('administrator', $adminArray);
                        $url = 'spservices/admin/dashboard';
                    } else {
                        $this->session->set_flashdata('er_msg', 'Incorrect username or password.');
                        $url = 'spservices/admin/login';
                    }
                } else {
                    $this->session->set_flashdata('er_msg', 'Invalid user or user is deactivated.');
                    $url = 'spservices/admin/login';
                }
                redirect($url);
            }
        }
    }

    function logout()
    {
        $user_data = $this->session->all_userdata();
        foreach ($user_data as $key => $value) {
            if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                $this->session->unset_userdata($key);
            }
        }
        $this->session->sess_destroy();
        redirect('spservices/admin/login');
    }
}
