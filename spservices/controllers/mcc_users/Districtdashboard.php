<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Districtdashboard extends Frontend
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mcc_office_user/districts_model');
        $this->load->model('mcc_office_user/circles_model');
        $this->load->model('mcc_office_user/registrations_model');
        $this->load->model('mcc_office_user/Office_users_model');
        $this->load->model('mcc_office_user/Office_user_manage_model');
        $this->load->model('mcc_office_user/Office_user_roles_model');
        $this->load->model('mcc_office_user/Office_user_designation_model');
        $this->load->model('mccdistrictadmin/Office_admin_model');
        $this->load->model('mcc_office_user/Minoritycertificatie_model');
        $this->load->model('mcc_office_user/Transfer_log');
        $this->load->model('mccdistrictadmin/application_model');
        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');

        $this->load->library('session');
        $this->load->library('form_validation');
        $admin = $this->session->userdata('admin');
        if (empty($admin)) {
            $this->session->set_flashdata('msg', 'Your session has been expired!');
            redirect(base_url() . 'spservices/mcc/admin-login');
        }
        else{
            if($this->session->userdata('admin')['role'] != 'DA'){
                redirect('spservices/mcc/unauthorize-user');   
            }
        }
    } //End of __construct()


    public function index()
    {
        // pre($_SESSION['admin']['admin_id']->{'$id'});
        // This $data['admin_details'] contain all admin information. ex. name, email, phone to display in admin detail page
        // $data['admin_details'] = (array)$this->Office_admin_model->getAdminDetails($_SESSION['admin']['admin_id']->{'$id'});
        // // This $data['admin_name'] contain admin session(name to display in header).
        $data['admin_name'] = $_SESSION['admin']['username'];
        $data['counts'] = (array)$this->application_model->get_application_count();

        $this->load->view('includes/mcc_users/dashb/header', $data);
        $this->load->view('mccdistrictadmin/dashboard', $data);
        $this->load->view('includes/mcc_users/dashb/footer');
    }

    public function profile()
    {
        // pre($_SESSION['admin']['admin_id']->{'$id'});
        // This $data['admin_details'] contain all admin information. ex. name, email, phone to display in admin detail page
        $data['admin_details'] = (array)$this->Office_admin_model->getAdminDetails($_SESSION['admin']['admin_id']->{'$id'});
        // // This $data['admin_name'] contain admin session(name to display in header).
        $data['admin_name'] = $_SESSION['admin']['username'];

        $this->load->view('includes/mcc_office_users/dashb/header', $data);
        $this->load->view('mccdistrictadmin/admin_home', $data);
        $this->load->view('includes/mcc_office_users/dashb/footer');
    }

    function get_circles() {
        $input_name = $this->input->post("input_name");
        $fld_name = $this->input->post("fld_name");
        $fld_value = (int)$this->input->post("fld_value");
        $circles = $this->circles_model->get_rows(array($fld_name=>$fld_value)); ?>                   
        <select name="<?=$input_name?>" id="<?=$input_name?>" class="form-control">
            <option value="">Select a circle </option>
            <?php if($circles) { 
                foreach($circles as $circle) {
                    echo '<option value="'.$circle->circle_name.'">'.$circle->circle_name.'</option>';                   
                }//End of foreach()
            }//End of if ?>
        </select><?php
    }//End of get_circles()




    function application_transfer()
    {

        // This $data['admin_name'] contain admin session(name to display in header).
        $data['admin_name'] = $_SESSION['admin']['username'];

        $data['user_roles'] = (array)$this->Office_user_roles_model->getAllUserRoles();


        $this->load->view('includes/mcc_office_users/dashb/header', $data);
        $this->load->view('mcc_office_users/application_transfer', $data);
        $this->load->view('includes/mcc_office_users/dashb/footer');
    }


    function transfer()
    {
        $transferFrom = $this->input->post("office_user");
        $transferTo = $this->input->post("active_office_user");
        $this->mongo_db->where('execution_data.0.task_details.user_detail.user_id', $transferFrom);
        $check_applications = (array)$this->mongo_db->where('execution_data.0.task_details.action_taken', 'N')->get('minoritycertificates');
        
        if (count($check_applications)) {
            $next_user = (array)$this->Office_user_manage_model->get_single_office_user($transferTo);
            $next_user_data = [
                "execution_data.0.task_details.user_name" => $next_user[0]->name,
                "execution_data.0.task_details.user_detail" => [
                    "user_id" => $next_user[0]->_id->{'$id'},
                    "user_name" => $next_user[0]->name,
                    "sign_no" => "",
                    "mobile_no" =>  $next_user[0]->mobile,
                    "location_id" => "",
                    "location_name" => "",
                    "circle" => $next_user[0]->circle_name,
                    "district" => $next_user[0]->district_name,
                    "email" => $next_user[0]->email,
                    "designation" => $next_user[0]->designation,
                    "role" => $next_user[0]->user_role,
                    "role_slug" => $next_user[0]->role_slug_name,
                ]

            ];
            foreach ($check_applications as $val) {
                $this->save_transfer($val->rtps_trans_id, $next_user_data, $transferFrom, $transferTo);
            }
           
            $this->session->set_flashdata('success', 'Application transfered successfully!');
        } else {
            $this->session->set_flashdata('warning', 'Selected user has no any pending applications!');
        }
        redirect(base_url() . 'spservices/mcc_office_users/admin/application_transfer');
    }

    public function save_transfer($ref_no, $data, $transferFrom, $transferTo)
    {
        $option = array('upsert' => true);
        $this->mongo_db->where(array('rtps_trans_id' => $ref_no))->set($data)->update('minoritycertificates', $option);

        // Transfer logs function call
        $this->transfer_logs($transferFrom,$transferTo,$ref_no);

    }

    // Transfer logs function
    public function transfer_logs($transferFrom,$transferTo,$ref_no){
        $date = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $data = [
            "transfer_from"=>$transferFrom,
            "transfer_to"=>$transferTo,
            "application_ref_no"=>$ref_no,
            "transfer_date"=>$date,           
        ];
        $insert=$this->Transfer_log->insert($data);
    }


    function office_user_list()
    {
        $district_id = $this->input->post("dist_id");
        $user_role = $this->input->post("user_role");



        $office_users = (array)$this->Office_users_model->getOfficeUsersList($user_role, $district_id); ?>



        <select name="office_user" id="office_user" class="form-control">
            <option value="">Select office user </option>
            <?php if ($office_users) {
                foreach ($office_users as $office_user) {
                    echo '<option value="' . $office_user->_id->{'$id'} . '">' . $office_user->name . '</option>';
                }
            } ?>
        </select><?php



                }

                function office_user_list_by_circle()
                {
                    $district_id = $this->input->post("dist_id");
                    $user_role = $this->input->post("user_role");
                    $user_circle = $this->input->post("circle_name");



                    $office_users = (array)$this->Office_users_model->getOfficeUsersListByCircle($user_role, $district_id, $user_circle); ?>



        <select name="office_user" id="office_user" class="form-control">
            <option value="">Select office user </option>
            <?php if ($office_users) {
                        foreach ($office_users as $office_user) {
                            echo '<option value="' . $office_user->_id->{'$id'} . '">' . $office_user->name . '</option>';
                        }
                    } ?>
        </select><?php





                }


                // Active office user

                function active_office_user_list()
                {
                    $district_id = $this->input->post("dist_id");
                    $user_role = $this->input->post("user_role");




                    $office_users = (array)$this->Office_users_model->active_office_user_list($user_role, $district_id); ?>



        <select name="office_user" id="office_user" class="form-control">
            <option value="">Select office user </option>
            <?php if ($office_users) {
                        foreach ($office_users as $office_user) {
                            echo '<option value="' . $office_user->_id->{'$id'} . '">' . $office_user->name . '</option>';
                        }
                    } ?>
        </select><?php



                }



                // Active office user by circle

                function active_office_user_list_by_circle()
                {
                    $district_id = $this->input->post("dist_id");
                    $user_role = $this->input->post("user_role");
                    $user_circle = $this->input->post("circle_name");



                    $office_users = (array)$this->Office_users_model->active_office_user_list_by_circle($user_role, $district_id, $user_circle); ?>



        <select name="office_user" id="office_user" class="form-control">
            <option value="">Select office user </option>
            <?php if ($office_users) {
                        foreach ($office_users as $office_user) {
                            echo '<option value="' . $office_user->_id->{'$id'} . '">' . $office_user->name . '</option>';
                        }
                    } ?>
        </select><?php



                }
            }
