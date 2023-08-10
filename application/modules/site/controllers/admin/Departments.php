<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Departments extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/dept_model");
    }

    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Departments"));
        $this->load->view("admin/departments");
        $this->load->view("admin/includes/footer");
    }

    // Create/Update dept
    public function add_dept($ob_id = null)
    {
        $data['action_name'] = 'Add Department';
        $data['action_url'] = 'add_dept_action';

        if ($ob_id !== null) {
            $data['dept_info'] = $this->dept_model->get_dept_by_obID($ob_id);
            $data['action_name'] = 'Update Department';
            $data['action_url'] = 'update_dept_action';
        }

        $this->load->view("admin/includes/header", array("pageTitle" => ($ob_id == NULL) ? "ARTPS | Add Deartment" : "ARTPS | Update Deartment"));
        $this->load->view("admin/add_dept", $data);
        $this->load->view("admin/includes/footer");
    }

    public function add_dept_action()
    {
        /* TODO :: Add Validation */

        $data['department_id'] = trim($this->input->post("department_id", true));
        $data['department_short_name'] = strtoupper(trim($this->input->post("department_short_name", true)));


        // Check if the department already exists
        $check_dept = $this->dept_model->get_dpt_id($data['department_id']);

        if (count($check_dept)) {
            $this->session->set_flashdata('error', "A Department with ID $data[department_id] Already Exists!");
            redirect(base_url("site/admin/departments/add_dept"));
        } else {

            $data['department_name'] = array(
                'en' => $this->input->post('en', true),
                'bn' => $this->input->post('bn', true),
                'as' => $this->input->post('as', true),
            );

            // Must be Unique
            $check_dept_short = $this->dept_model->get_dpt_short($data['department_short_name']);
            // die();

            if (count($check_dept_short)) {
                $this->session->set_flashdata('error', "A Department with short name $data[department_short_name] Already Exists!");
                redirect(base_url("site/admin/departments/add_dept"));
            } else {

                $data['online'] = boolval($this->input->post("online", true));
                $data['ac'] = boolval($this->input->post("ac", true));

                $this->dept_model->add_new_dept($data);

                $this->session->set_flashdata('success', 'Department Created Successfully.');
                redirect(base_url("site/admin/departments/add_dept"));
            }
        }
    }

    public function update_dept_action()
    {
        /* TODO :: Add Validation */

        $this->load->model("site_model");
        
        $old_department_id = $this->input->post("old_department_id", true);
        $old_department_short = $this->input->post("old_department_short", true);
        $object_id = $this->input->post("object_id", true);

        $data['department_id'] = trim($this->input->post("department_id", true));
        $data['department_short_name'] = strtoupper(trim($this->input->post("department_short_name", true)));

        // Must be Unique
        $check_dept_short = $this->dept_model->get_dept_by_short($data['department_short_name'], $old_department_short);
        $check_dept = $this->dept_model->get_dept_by_deptID($data['department_id'], $object_id);

        //  pre($old_department_id);
        //pre($old_department_short);

        if ($check_dept && $check_dept_short) {

            $data['department_name'] = array(
                'en' => $this->input->post('en', true),
                'bn' => $this->input->post('bn', true),
                'as' => $this->input->post('as', true),
            );

            $data['online'] = boolval($this->input->post("online", true));
            $data['ac'] = boolval($this->input->post("ac", true));

            $this->dept_model->update_dept($object_id, $data);


            // Also Update department_id in respective services

            $this->site_model->update_service_by_dept($old_department_id, $data['department_id']);
            
            // Turn services ON/OFF based on dept online status
            $this->site_model->make_services_online($data['department_id'], $data['online']);

            $this->session->set_flashdata('success', 'Department Updated Successfully.');
            redirect(base_url("site/admin/departments/add_dept/" . $object_id));
        } 
        else if (!$check_dept) {
            $this->session->set_flashdata('error', "A department with Department ID $data[department_id] already Exists!");
            redirect(base_url("site/admin/departments/add_dept/" . $object_id));
        }
        else {
            $this->session->set_flashdata('error', "A department with short name $data[department_short_name] already Exists!");
            redirect(base_url("site/admin/departments/add_dept/" . $object_id));
        }
    }
    public function all_depts_api()
    {
        $data = $this->dept_model->get_all_depts();
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    public function delete_department()
    {
        $this->load->model("site_model");

        $ob_id = $this->input->post('object_id', TRUE);
        $dept_id = $this->input->post('department_id', TRUE);

        $this->dept_model->delete($ob_id);      // delete dept
        $this->site_model->delete_services_by_filter(array('department_id' => $dept_id));
        // also delete related services

        $this->session->set_flashdata('success', 'Department deleted successfully.');
        redirect('site/admin/departments');
    }
}
