<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Users extends admin
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
    } //End of __construct()

    // List of office users
    public function index()
    {
        $filter['user_types.utype_id'] = 3;
        if ($this->user_type() == 5) {
            $filter['dept_info.dept_code'] = $this->session->userdata('administrator')['dept_id'];
        }
        $data['users'] = $this->users_model->get_rows($filter);
        $this->load->view('adminviews/includes/header', $data);
        $this->load->view('adminviews/user_list', $data);
        $this->load->view('adminviews/includes/footer');
    } //End of index()

    //End of index()

    // Edit view
    public function user($obj_id = null)
    {
        $data['dbrow'] = $this->users_model->get_by_doc_id($obj_id);
        $this->load->view('adminviews/includes/header', $data);
        $this->load->view('adminviews/user_registration', $data);
        $this->load->view('adminviews/includes/footer');
    } //End of index()


    // Edit user
    public function edit()
    {
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules('dept_info', 'Department', 'required|max_length[255]');
        $this->form_validation->set_rules('user_services[]', 'Service', 'required');
        $this->form_validation->set_rules('user_roles', 'Role', 'required');
        $this->form_validation->set_rules('user_fullname', 'Name', 'required|max_length[255]');
        $this->form_validation->set_rules('mobile_number', 'Mobile', 'integer|exact_length[10]');
        $this->form_validation->set_rules('email_id', 'Email', 'valid_email');
        $this->form_validation->set_rules('login_username', 'Username', 'required|alpha_dash|max_length[20]');
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg', 'Error in inputs : ' . validation_errors());
            // $obj_id = strlen($objId)?$objId:null;
            // $this->index($obj_id);
            redirect("spservices/admin/users/user/$objId");            // return;
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
        $this->load->view('adminviews/includes/header', $data);
        $this->load->view('adminviews/password', $data);
        $this->load->view('adminviews/includes/footer');
    }

    // Edit password
    public function edit_password()
    {
        $objId = $this->input->post('obj_id');
        $this->form_validation->set_rules('login_password', 'Password', 'required|max_length[255]');
        $this->form_validation->set_rules('login_password_conf', 'Password Confirm', 'required|max_length[255]|matches[login_password]');
        $login_password = $this->input->post("login_password");

        $salt = uniqid("", true);
        $algo = "6";
        $rounds = "5050";
        $cryptSalt = '$' . $algo . '$rounds=' . $rounds . '$' . $salt;
        $hashedPassword = crypt($login_password, $cryptSalt);

        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->password($obj_id);
        } else {
            $data['login_password'] = $hashedPassword;
            $this->users_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('flashMsg', 'Password reset successfully');
            redirect("spservices/admin/users");
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
            $data['dbrow'] = $user;
            $this->load->view('adminviews/includes/header', $data);
            $this->load->view('adminviews/userprofile_view', $data);
            $this->load->view('adminviews/includes/footer');
        } else {
            $this->session->set_flashdata('flashMsg', 'User does not exist!');
            redirect('spservices/upms/');
        } //End of if else
    } //End of profile()

    public function check_pending_appl()
    {
        $this->load->model('admin/applications_model');

        $obj_id = $this->input->post('objId');
        $user_data = $this->users_model->get_by_doc_id($obj_id);
        $filter = array(
            "current_users.login_username" => $user_data->login_username,
            "service_data.appl_status" => array('$nin' => array('D', 'R')),
        );
        $appl_count = $this->applications_model->get_rows_count($filter);
        if ($appl_count > 0) {
            $data['status'] = false;
            $data['msg'] = $appl_count . ' application(s) pending in the selected user account. You are not allowed to Deactive the account.';
        } else {
            $data['status'] = true;
            $data['msg'] = 'OK';
        }
        echo json_encode($data);
    }
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


    function get_services()
    {
        $dept_code = $this->input->post("dept_code");
        $rows = $this->services_model->get_rows(array("dept_info.dept_code" => $dept_code));
        $data = array();
        foreach ($rows as $row) {
            $serviceObj = json_encode(array("service_code" => $row->service_code, "service_name" => $row->service_name));
            $data[] = array("service_code" => $row->service_code, "service_name" => $row->service_name, "service_obj" => $serviceObj);
        } //End of foreach()
        $json_obj = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($json_obj);
    } //End of get_services()

    function get_users()
    {
        $dept_code = $this->input->post("dept_code");
        $service_code = $this->input->post("service_code");
        $role_code = $this->input->post("role_code");
        $filter = array(
            'dept_info.dept_code' => $dept_code,
            'user_services.service_code' => $service_code,
            'user_roles.role_code' => $role_code,
        );

        $filter2 = array(
            'dept_info.dept_code' => $dept_code,
            'user_services.service_code' => $service_code,
            'user_roles.role_code' => $role_code,
            'status' => 1
        );
        $all_users = $this->users_model->get_rows($filter);
        $ative_users = $this->users_model->get_rows($filter2);


        $data = array();
        $allusers = array();
        $activeusers = array();

        foreach ($all_users as $row) {
            $userObj = json_encode(array("obj_id" => $row->_id->{'$id'}, "username" => $row->login_username));
            $allusers[] = array("fullname" => $row->user_fullname, "login_username" => $row->login_username, "objId" => $userObj);
        } //End of foreach()

        foreach ($ative_users as $row) {
            $userObj = json_encode(array("obj_id" => $row->_id->{'$id'}, "username" => $row->login_username));
            $activeusers[] = array("fullname" => $row->user_fullname, "login_username" => $row->login_username, "objId" => $userObj);
        } //End of foreach()
        $data = array("all_users" => $allusers, "active_users" => $activeusers);
        $json_obj = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($json_obj);
    } //End of get_services()
}
