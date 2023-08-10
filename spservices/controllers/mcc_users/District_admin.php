<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class District_admin extends Frontend
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('mcc_users/districts_model');
        $this->load->model('mcc_users/Dist_admin_model');
        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->model('mcc_users/Office_user_manage_model');

        $this->load->library('session');
        $admin = $this->session->userdata('admin');

        if (empty($admin)) {
            $this->session->set_flashdata('msg', 'Your session has been expired!');
            redirect(base_url() . 'spservices/mcc/admin-login');
        }
    } //End of __construct()



    // Add distrct admin & list of distrct admin
    public function index()
    {

        $data['admins'] = (array)$this->Dist_admin_model->getDistAdmin();
        // print_r($data['admins']);
        $data['name'] = $this->session->userdata('admin')['name'];
        $this->load->view("includes/mcc_users/dashb/header", $data, array("pageTitle" => "Dashboard"));
        $this->load->view('mcc_users/district_admin');
        $this->load->view('includes/mcc_users/dashb/footer');
    } //End of index()


    // Create district admin
    public function create()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('district_name', 'District', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|strip_tags');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required|exact_length[10]xss_clean|strip_tags');
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('dist_adimin_creation_error', '' . validation_errors());
            $this->index(null);
        } else {
            // Dist admin exist?
            $getDistName =  (array)$this->Dist_admin_model->getDistNames($this->input->post("district_name"));
            if (count($getDistName) >= 1) {
                $this->session->set_flashdata('dist_adimin_creation_error', 'Admin for ' . $this->input->post("district_name") . ' district already exists.' . validation_errors());
                $this->index(null);
                return;
            }
            $checkUsername =  (array)$this->Dist_admin_model->checkUserName($this->input->post("username"));
            if (count($checkUsername) >= 1) {
                $this->session->set_flashdata('dist_adimin_creation_error', 'Username already in use. Please use unique username.' . validation_errors());
                $this->index(null);
                return;
            }

            $data = array(
                'district_name' => $this->input->post("district_name"),
                'name' => $this->input->post("name"),
                'mobile' => $this->input->post("mobile"),
                'email' => $this->input->post("email"),
                'username' => $this->input->post("username"),
                'password' => getHashedPassword($this->input->post("password")),
                'role' => 'DA'
            );
            $insert = $this->Dist_admin_model->insert($data);
            if ($insert) {
                $this->session->set_flashdata('dist_adimin_creation_success', 'Admin for ' . $this->input->post("district_name") . ' has been successfully created.');
                redirect(base_url('spservices/mcc_users/district_admin'));
            }
        }
    }

    // Dist admin edit view
    public function view($id)
    {
        $data['admins'] = (array)$this->Dist_admin_model->getDistAdmin();
        $data['admin'] = (array)$this->Dist_admin_model->get_single_dist_admin($id);
        $data['name'] = $this->session->userdata('admin')['name'];
        $this->load->view("includes/mcc_users/dashb/header", $data, array("pageTitle" => "Dashboard"));
        $this->load->view('mcc_users/district_admin_edit', $data);
        $this->load->view('includes/mcc_users/dashb/footer');
    }

    // Dist admin update
    public function update($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('district_name', 'District', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|strip_tags');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required|exact_length[10]xss_clean|strip_tags');
        // $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('dist_admin_update_error', '' . validation_errors());
            $this->view($id);
        } else {
            // $checkUsername =  (array)$this->Dist_admin_model->checkUserName($this->input->post("username"));
            // if (count($checkUsername) >= 1) {
            //     if ($checkUsername[0]->mobile != $this->input->post('mobile')) {
            //         $this->session->set_flashdata('dist_admin_update_error', 'Username already in use. Please use unique username.' . validation_errors());
            //         $this->view($id);
            //         return;
            //     }
            // }
            $userData =  (array)$this->Dist_admin_model->get_single_dist_admin($id);
            $old_mobile = $userData[0]->mobile;
            if ($old_mobile == $this->input->post("mobile")) {
                $data = array(
                    'district_name' => $this->input->post("district_name"),
                    'name' => $this->input->post("name"),
                    'email' => $this->input->post("email"),
                );
            } else {
                $data = array(
                    'district_name' => $this->input->post("district_name"),
                    'name' => $this->input->post("name"),
                    'mobile' => $this->input->post("mobile"),
                    'email' => $this->input->post("email"),
                    'mobile_verify_sts' => 0
                    // 'username' => $this->input->post("username"),
                );
            }
            $insert = $this->Dist_admin_model->updateAdmin($data, $id);
            if ($insert) {
                $this->session->set_flashdata('dist_admin_update_success', 'District admin has been successfully updated');
                redirect(base_url('spservices/mcc_users/district_admin'));
            }
        }
    }
}
