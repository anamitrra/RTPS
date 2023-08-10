<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Deptadmin extends admin
{
    public function __construct()
    {
        parent::__construct();
        $this->user_type();
        $this->load->model('admin/users_model');
        $this->load->model('admin/districts_model');
        $this->load->model('admin/depts_model');
        $this->load->model('admin/services_model');
        $this->load->model('admin/levels_model');
        $this->load->model('admin/admin_model');
    } //End of __construct()

    // List of office users
    public function index($obj_id = null)
    {
        if ($obj_id) {
            $data['dbrow'] = $this->admin_model->get_by_doc_id($obj_id);
        } else {
            $data['dbrow'] = array();
        }
        $data['users'] = $this->admin_model->get_rows(array('user_types.utype_id' => 5));
        $data['depts'] = $this->depts_model->get_rows();
        $this->load->view('adminviews/includes/header', $data);
        $this->load->view('adminviews/dept_admin_list', $data);
        $this->load->view('adminviews/includes/footer');
    } //End of index()

    public function create()
    {
        // Set the validation rules
        $this->form_validation->set_rules('name', 'Name', 'required|max_length[255]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]');
        $this->form_validation->set_rules('department_name', 'Department Name', 'required|max_length[255]');
        $this->form_validation->set_rules('username', 'Username', 'required|max_length[255]');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[255]');
        $this->form_validation->set_rules('designation', 'Designation', 'required|max_length[255]');

        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            // $this->session->set_flashdata('flashMsgl','Error in inputs : '.validation_errors());
            // $obj_id = strlen($objId)?$objId:null;
            $this->index();
        } else {
            $dept_code = $this->input->post('department_name');
            $dept_name = $this->depts_model->get_row(["dept_code" => $dept_code]);
            $dept_name = $dept_name->dept_name;
            $data = array(
                'user_fullname' => $this->input->post('name'),
                'mobile_number' => $this->input->post('mobile'),
                'email_id' => $this->input->post('email'),
                'dept_info' => [
                    "dept_code" => $this->input->post('department_name'),
                    "dept_name" => $dept_name,
                ],
                'login_designation' => $this->input->post('designation'),
                'login_username' => $this->input->post('username'),
                'login_password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'status' => 1,
                "user_types" => [
                    "utype_id" => 5,
                    "utype_name" => "Dept Admin"
                ],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->admin_model->insert($data);
            $this->session->set_flashdata('success', 'Department Admin has been successfully created.');
            redirect("spservices/admin/deptadmin");
        }
    }



    // Edit view
    //     public function edit($obj_id = null)
    //     {
    //         $data['dbrow'] = $this->admin_model->get_by_doc_id($obj_id);
    // // pre($data);
    //         $this->load->view('adminviews/includes/header', $data);
    //         $this->load->view('adminviews/user_registration', $data);
    //         $this->load->view('adminviews/includes/footer');
    //     } //End of index()


    // Edit user
    public function update($obj_id = null)
    {
        // pre("Edit");
        // return;
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules('dept_info', 'Department', 'required|max_length[255]');
        $this->form_validation->set_rules('user_services[]', 'Service', 'required');
        $this->form_validation->set_rules('user_roles', 'Role', 'required');
        $this->form_validation->set_rules('user_fullname', 'Name', 'required|max_length[255]');
        //$this->form_validation->set_rules('user_location', 'Location', 'required');
        $this->form_validation->set_rules('mobile_number', 'Mobile', 'integer|exact_length[10]');
        $this->form_validation->set_rules('email_id', 'Email', 'valid_email');
        $this->form_validation->set_rules('login_username', 'Username', 'required|alpha_dash|max_length[20]');
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
            redirect("spservices/admin/users/user/$id");            // return;
        } else {
            $user_services = array();
            $userServices = $this->input->post("user_services");
            if ($userServices && count($userServices)) {
                foreach ($userServices as $userService) {
                    $user_services[] = json_decode(html_entity_decode($userService));
                } //End of foreach()
            } //End of if

            $offices_info = array();
            $officesInfo = $this->input->post("offices_info");
            if ($officesInfo && count($officesInfo)) {
                foreach ($officesInfo as $officeInfo) {
                    $offices_info[] = json_decode(html_entity_decode($officeInfo));
                } //End of foreach()
            } //End of if

            $user_roles = json_decode(html_entity_decode($this->input->post("user_roles")));

            if (count((array)$user_roles)) {
                $user_levels = array(
                    "level_no" => $user_roles->level_no,
                    "level_name" => $user_roles->level_name
                );
            } else {
                $user_levels = array();
            } //End of if else

            $login_username = $this->input->post("login_username");
            $service_code = $user_services->service_code ?? '';
            $filter = array("login_username" => $login_username);
            if (strlen($objId) == 0) {
                $dbRow = $this->users_model->get_row($filter);
            } else {
                $dbRow = false;
            } //End of if else

            if ((strlen($objId) == 0) && $dbRow) {
                // pre("OK");
                $this->session->set_flashdata('flashMsg', 'Username already exists in the selected service');
                $this->index();
            } else {
                $rights = array();
                $user_rights = $this->input->post("user_rights");
                if ($user_rights && count($user_rights)) {
                    foreach ($user_rights as $right) {
                        $rights[] = json_decode($right);
                    } //End of foreach()
                } //End of if

                $forward_levels = array();
                $forwardLevels = $this->input->post("forward_levels");
                if ($forwardLevels && count($forwardLevels)) {
                    foreach ($forwardLevels as $levels) {
                        $forward_levels[] = json_decode($levels);
                    } //End of foreach()
                } //End of if

                $backward_levels = array();
                $backwardLevels = $this->input->post("backward_levels");
                if ($backwardLevels && count($backwardLevels)) {
                    foreach ($backwardLevels as $levels) {
                        $backward_levels[] = json_decode($levels);
                    } //End of foreach()
                } //End of if

                $generate_certificate_levels = array();
                $generateGertificateLevels = $this->input->post("generate_certificate_levels");
                if ($generateGertificateLevels && count($generateGertificateLevels)) {
                    foreach ($generateGertificateLevels as $levels) {
                        $generate_certificate_levels[] = json_decode($levels);
                    } //End of foreach()
                } //End of if

                $data = array(
                    "dept_info" => json_decode(html_entity_decode($this->input->post("dept_info"))),
                    "user_services" => $user_services,
                    "offices_info" => $offices_info,
                    "user_roles" => $user_roles,
                    "user_levels" => $user_levels,
                    "user_location" => json_decode(html_entity_decode($this->input->post("user_location"))),
                    "district_info" => json_decode(html_entity_decode($this->input->post("district_info"))),
                    "zone_info" => json_decode(html_entity_decode($this->input->post("zone_info"))),
                    "zone_circle" => $this->input->post("zone_circle"),
                    "office_info" => json_decode(html_entity_decode($this->input->post("office_info"))),
                    "user_fullname" => $this->input->post("user_fullname"),
                    "mobile_number" => $this->input->post("mobile_number"),
                    "email_id" => $this->input->post("email_id"),
                    "user_rights" => $rights,
                    "forward_levels" => $forward_levels,
                    "backward_levels" => $backward_levels,
                    "generate_certificate_levels" => $generate_certificate_levels,
                    "user_types" => array("utype_id" => 3, "utype_name" => "Staff/Dept user"), // 1 for developer/superadmin, 2 for stateadmin, 3 for staffs/dept users
                    "registered_by" => $this->session->loggedin_login_username,
                    "registration_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    "reports_url" => $this->input->post("reports_url"),
                    "status" => 1
                );
                // echo '<pre>'; var_dump($data); '</pre>'; die;
                if (strlen($objId)) {
                    $this->users_model->update_where(['_id' => new ObjectId($objId)], $data);
                    $this->session->set_flashdata('flashMsg', 'User has been successfully updated');
                } else {
                    $login_password = $this->input->post("login_password");
                    $salt = uniqid("", true);
                    $algo = "6";
                    $rounds = "5050";
                    $cryptSalt = '$' . $algo . '$rounds=' . $rounds . '$' . $salt;
                    $hashedPassword = crypt($login_password, $cryptSalt);

                    $data["login_username"] = $login_username;
                    $data["login_password"] = $hashedPassword;
                    $this->users_model->insert($data);
                    $this->session->set_flashdata('flashMsg', 'User has been successfully created');
                } //End of if else
                redirect('spservices/admin/users');
            } //End of if else
        } //End of if else
    } //E//End of index()




    // Change password view
    public function password($objId = null)
    {
        $data['dbrow'] = $this->users_model->get_by_doc_id($objId);
        $data['name'] = $this->session->userdata('administrator')['name'];
        $data['login_label'] = $this->login_label;

        $this->load->view('adminviews/includes/header', $data);
        $this->load->view('adminviews/password', $data);
        $this->load->view('adminviews/includes/footer');
    }

    // Edit password
    public function edit_password()
    {

        $this->form_validation->set_rules('login_password', 'Password', 'required|max_length[255]');
        $this->form_validation->set_rules('login_password_conf', 'Password Confirm', 'required|max_length[255]');


        $login_password = $this->input->post("login_password");
        $login_password_conf = $this->input->post("login_password_conf");


        $hash_pass = password_hash($login_password, PASSWORD_DEFAULT);

        // pre($hash_pass);


        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
            redirect("spservices/admin/users/password/$id");            // return;
        }

        // Make sure the password and confirmation match
        if ($login_password === $login_password_conf) {
            // Load the MongoDB library
            $this->load->library('mongo_db');

            // Update the login_password field in the upms_users
            $this->mongo_db->where('_id', new MongoDB\BSON\ObjectId("64ad07563f198a079e312f02"))
                ->set('login_password', $hash_pass)
                ->update('upms_users');

            $this->session->set_flashdata('flashMsg', 'Password updated successfully.');
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
            redirect("spservices/admin/users/password/$id");

            // Check if the update was successful
            // $updateResult = $this->mongo_db->getUpdateBatch();
        } else {
            $this->session->set_flashdata('flashMsg', 'Password does not matched.');
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
            redirect("spservices/admin/users/password/$id");
        }
    }


    // Detail view of office user
    public function profile($objId = null)
    {
        if ($this->checkObjectId($objId)) {
            $user = $this->users_model->get_row(array('_id' => new ObjectId($objId)));
        } else {
            $user = $this->users_model->get_row(array("login_username" => $this->session->loggedin_login_username));
        } //End of if else        
        if ($user) {
            $data['name'] = $this->session->userdata('administrator')['name'];
            $data['login_label'] = $this->login_label;
            $data['dbrow'] = $user;
            $this->load->view('adminviews/includes/header', $data);
            $this->load->view('adminviews/userprofile_view', $data);
            $this->load->view('adminviews/includes/footer');
        } else {
            $this->session->set_flashdata('flashMsg', 'User does not exist!');
            redirect('spservices/upms/');
        } //End of if else
    } //End of profile()

    public function update_user()
    {
        $obj_id = $this->input->post('objId');
        $filter = array(
            '_id' => new ObjectId($obj_id)
        );
        $user = $this->users_model->get_row($filter);
        if ($user) {
            if ($user->status == 1) {
                $data = array('status' => 0);
                $msg = 'User account has been deactivated.';
            } else {
                $data = array('status' => 1);
                $msg = 'User account has been activated successfully.';
            }
            $this->users_model->update_where(['_id' => new ObjectId($obj_id)], $data);
            echo json_encode(array('status' => true, 'msg' => $msg));
        } else {
            echo json_encode(array('status' => false, 'msg' => 'Something went wrong'));
        }
    } //End of updateUser()

    private function checkObjectId($obj)
    {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        } //End of if else
    } //End of checkObjectId()
}
