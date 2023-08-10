<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Login extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mcc_users/Office_admin_model');
        $this->load->library('session');
    }

    public function index()
    {
        $this->load->view('includes/mcc_users/admin/header');
        $this->load->view('mcc_users/login');
        $this->load->view('includes/mcc_users/admin/footer');
    }

    public function authenticate()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('login_error', '' . validation_errors());
            $this->index();
        } else {
            $username = $this->input->post('username');
            $admin = (array)$this->Office_admin_model->getByUserName($username);
            if (!empty($admin)) {
                $password = $this->input->post('password');
                if (verifyHashedPassword($password, $admin[0]->password)) {
                    $role = $admin[0]->role;
                    $adminArray['admin_id'] = $admin[0]->_id;
                    $adminArray['name'] = $admin[0]->name;
                    $adminArray['username'] = $admin[0]->username;
                    $adminArray['mobile'] = $admin[0]->mobile;
                    $adminArray['district'] = $admin[0]->district_name ?? '';
                    $adminArray['role'] = $role;
                    $this->session->set_userdata('admin', $adminArray);
                    $url = 'spservices/mcc_users/admindashboard';
                    redirect(base_url() . $url);
                } else {
                    $this->session->set_flashdata('msg', 'Incorrect username or password');
                    redirect('spservices/mcc/admin-login');
                }
            } else {
                $this->session->set_flashdata('msg', 'Incorrect username or password');
                redirect('spservices/mcc/admin-login');
            }
        }
    }

    function logout()
    {
        $this->session->unset_userdata('admin');
        redirect('spservices/mcc/admin-login');
    }
}
