<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Dashboard extends admin
{

    public function __construct()
    {
        parent::__construct();
        $this->user_type();
        $this->load->model('admin/admin_model');
        $this->load->model('admin/users_model');
    } //End of __construct()

    public function index()
    {
        if ($this->user_type() == 4) {
            $this->stateadmin();
        } elseif ($this->user_type() == 5) {
            $this->dept_admin();
        }

    }

    public function stateadmin()
    {
        $data['counts'] = (array)$this->users_model->get_counts();
        $this->load->view('adminviews/includes/header', $data);
        $this->load->view('adminviews/state_dashboard', $data);
        $this->load->view('adminviews/includes/footer');
    }

    public function dept_admin()
    {
        $dept = $this->session->userdata('administrator')['dept_name'];
        $data['counts'] = (array)$this->users_model->get_counts(['dept_info.dept_name' => $dept]);
        $this->load->view('adminviews/includes/header', $data);
        $this->load->view('adminviews/dept_dashboard');
        $this->load->view('adminviews/includes/footer');
    }

    // public function profile()
    // {
    //     $id = $this->session->userdata('admin')['admin_id']->{'$id'};
    //     $role = $this->session->userdata('admin')['role'];
    //     $data['admin_details'] = (array)$this->Office_admin_model->getAdminDetails($id);
    //     $data['name'] = $this->session->userdata('admin')['name'];
    //     $url = 'mcc_users/admin_home';
    //     $this->load->view('includes/mcc_users/dashb/header', $data);
    //     $this->load->view($url, $data);
    //     $this->load->view('includes/mcc_users/dashb/footer');
    // }
}
