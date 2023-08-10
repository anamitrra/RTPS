<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Admindashboard extends Frontend
{
    private $dps_designations = ['Assistant Commissioner', 'Additional Deputy Commissioner'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mcc_users/districts_model');
        $this->load->model('mcc_users/circles_model');
        $this->load->model('mcc_users/registrations_model');
        $this->load->model('mcc_users/Office_users_model');
        $this->load->model('mcc_users/Office_user_manage_model');
        $this->load->model('mcc_users/Office_user_roles_model');
        $this->load->model('mcc_users/Office_user_designation_model');
        $this->load->model('mcc_users/Office_admin_model');
        $this->load->model('mcc_users/Minoritycertificatie_model');
        $this->load->model('mcc_users/Transfer_log');
        $this->load->model('mcc_users/application_model');
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
    } //End of __construct()


    public function index()
    {
        // $this->session->userdata('admin')['district'],
        $id = $this->session->userdata('admin')['admin_id']->{'$id'};
        $role = $this->session->userdata('admin')['role'];
        $data['admin_details'] = (array)$this->Office_admin_model->getAdminDetails($id);
        $data['name'] = $this->session->userdata('admin')['name'];
        $mobile_verify = ($data['admin_details'][0]->mobile_verify_sts) ?? 0;
        $data['mobile_verify'] = $mobile_verify;
        if ($role == 'SA') {
            $viewPage = 'mcc_users/admin_home';
        } else {
            $data['counts'] = (array)$this->application_model->get_application_count();
            $viewPage = 'mcc_users/district_dashboard';
        }
        $this->load->view('includes/mcc_users/dashb/header', $data);
        $this->load->view($viewPage, $data);
        $this->load->view('includes/mcc_users/dashb/footer');
    }

    public function profile()
    {
        $id = $this->session->userdata('admin')['admin_id']->{'$id'};
        $role = $this->session->userdata('admin')['role'];
        $data['admin_details'] = (array)$this->Office_admin_model->getAdminDetails($id);
        $data['name'] = $this->session->userdata('admin')['name'];
        $url = 'mcc_users/admin_home';
        $this->load->view('includes/mcc_users/dashb/header', $data);
        $this->load->view($url, $data);
        $this->load->view('includes/mcc_users/dashb/footer');
    }

    function get_circles()
    {
        $input_name = $this->input->post("input_name");
        $fld_name = $this->input->post("fld_name");
        $fld_value = (int)$this->input->post("fld_value");
        $circles = $this->circles_model->get_rows(array($fld_name => $fld_value)); ?>
        <select name="<?= $input_name ?>" id="<?= $input_name ?>" class="form-control">
            <option value="">Select a circle </option>
            <?php if ($circles) {
                foreach ($circles as $circle) {
                    echo '<option value="' . $circle->circle_name . '">' . $circle->circle_name . '</option>';
                } //End of foreach()
            } //End of if 
            ?>
        </select>
    <?php } //End of get_circles()

    function application_transfer()
    {
        $data['role'] = $this->session->userdata('admin')['role'];
        $data['name'] = $this->session->userdata('admin')['name'];
        $data['user_roles'] = (array)$this->Office_user_roles_model->getAllUserRoles();
        $this->load->view('includes/mcc_users/dashb/header', $data);
        $this->load->view('mcc_users/application_transfer', $data);
        $this->load->view('includes/mcc_users/dashb/footer');
    }

    function transfer()
    {
        $data['name'] = $this->session->userdata('admin')['name'];
        $transferFrom = $this->input->post("office_user");
        $data['transferFrom'] = $transferFrom;
        $data['transferTo'] = $this->input->post("active_office_user");
        $this->mongo_db->where('execution_data.0.task_details.user_detail.user_id', $transferFrom);
        $check_applications = (array)$this->mongo_db->where(array('service_data.service_id' => 'MCC', 'execution_data.0.task_details.action_taken' => 'N'))->get('sp_applications');
        $data['applications'] = $check_applications;
        $this->load->view('includes/mcc_users/dashb/header', $data);
        $this->load->view('mcc_users/application_transfer_preview', $data);
        $this->load->view('includes/mcc_users/dashb/footer');
    }

    function process_transfer()
    {
        $data['name'] = $this->session->userdata('admin')['name'];
        $transferFrom = $this->input->post("transfer_from");
        $transferTo = $this->input->post("transfer_to");
        $remarks = $this->input->post("remarks");
        $appl_nos = ($this->input->post('appl_no')) ? $this->input->post('appl_no') : array();
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

        if (count($appl_nos)) {
            for ($i = 0; $i < count($appl_nos); $i++) {
                $this->save_transfer($appl_nos[$i], $next_user_data, $transferFrom, $transferTo, $remarks);
            }
            $this->session->set_flashdata('success', '(' . count($appl_nos) . ') Application transfered successfully!');
        } else {
            $this->session->set_flashdata('warning', 'Selected user has no any pending applications!');
        }
        redirect(base_url() . 'spservices/mcc_users/admindashboard/application_transfer');
    }

    public function save_transfer($ref_no, $data, $transferFrom, $transferTo, $remarks)
    {
        $option = array('upsert' => true);
        $this->mongo_db->where(array('service_data.appl_ref_no' => $ref_no))->set($data)->update('sp_applications', $option);
        // Transfer logs function call
        $this->transfer_logs($transferFrom, $transferTo, $ref_no, $remarks);
    }

    // Transfer logs function
    public function transfer_logs($transferFrom, $transferTo, $ref_no, $remarks)
    {
        $date = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $data = [
            "transfer_from" => $transferFrom,
            "transfer_to" => $transferTo,
            "application_ref_no" => $ref_no,
            "remarks" => $remarks,
            "transfer_date" => $date,
        ];
        $this->Transfer_log->insert($data);
    }

    function office_user_list()
    {
        $district_id = $this->input->post("dist_id");
        $user_role = $this->input->post("user_role");
        if (in_array($user_role, $this->dps_designations)) {
            $role = 'Designated Public Servant';
        } else {
            $role = $user_role;
        }
        $office_users = (array)$this->Office_users_model->getOfficeUsersList($role, $district_id);
    ?>
        <select name="office_user" id="office_user" class="form-control">
            <option value="">Select office user </option>
            <?php if ($office_users) {
                foreach ($office_users as $office_user) {
                    echo '<option value="' . $office_user->_id->{'$id'} . '">' . $office_user->name . '</option>';
                }
            } ?>
        </select>
    <?php
    }

    function office_user_list_by_circle()
    {
        $district_id = $this->input->post("dist_id");
        $user_role = $this->input->post("user_role");
        $user_circle = $this->input->post("circle_name");
        $office_users = (array)$this->Office_users_model->getOfficeUsersListByCircle($user_role, $district_id, $user_circle);
    ?>
        <select name="office_user" id="office_user" class="form-control">
            <option value="">Select office user </option>
            <?php if ($office_users) {
                foreach ($office_users as $office_user) {
                    echo '<option value="' . $office_user->_id->{'$id'} . '">' . $office_user->name . '</option>';
                }
            } ?>
        </select>
    <?php
    }

    // Active office user
    function active_office_user_list()
    {
        $district_id = $this->input->post("dist_id");
        $user_role = $this->input->post("user_role");
        if (in_array($user_role, $this->dps_designations)) {
            $role = 'Designated Public Servant';
        } else {
            $role = $user_role;
        }
        $office_users = (array)$this->Office_users_model->active_office_user_list($role, $district_id); ?>
        <select name="office_user" id="office_user" class="form-control">
            <option value="">Select office user </option>
            <?php if ($office_users) {
                foreach ($office_users as $office_user) {
                    echo '<option value="' . $office_user->_id->{'$id'} . '">' . $office_user->name . '</option>';
                }
            } ?>
        </select>
    <?php
    }
    // Active office user by circle

    function active_office_user_list_by_circle()
    {
        $district_id = $this->input->post("dist_id");
        $user_role = $this->input->post("user_role");
        $user_circle = $this->input->post("circle_name");
        $office_users = (array)$this->Office_users_model->active_office_user_list_by_circle($user_role, $district_id, $user_circle);
    ?>
        <select name="office_user" id="office_user" class="form-control">
            <option value="">Select office user </option>
            <?php if ($office_users) {
                foreach ($office_users as $office_user) {
                    echo '<option value="' . $office_user->_id->{'$id'} . '">' . $office_user->name . '</option>';
                }
            } ?>
        </select>
<?php
    }
}
