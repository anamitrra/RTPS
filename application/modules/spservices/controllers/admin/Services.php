<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Services extends admin
{
    public function __construct()
    {
        parent::__construct();
        $this->user_type();
        $this->mongo_db->switch_db("iservices");
        $this->load->model('admin/services_model');
    } //End of __construct()

    // List of services
    public function index()
    {
        if ($this->user_type() == 4) {
            $filter = array();
        } elseif ($this->user_type() == 5) {
            $filter = array(
                'dept_info.dept_code' => $this->session->userdata('administrator')['dept_id'],
            );
        }
        $data['services'] = $this->services_model->get_rows($filter);
        $this->load->view('adminviews/includes/header', $data);
        $this->load->view('adminviews/service_list', $data);
        $this->load->view('adminviews/includes/footer');
    } //End of index()

    public function update_service()
    {
        $objId = $this->checkObjectId($this->input->post("objId")) ? $this->input->post("objId") : null;
        $filter = array(
            '_id' => new ObjectId($objId)
        );
        $dbrow = $this->services_model->get_row($filter);
        // pre($dbrow->service_name);
        if ($dbrow->status == 1) {
            $data = array('status' => 0);
            $service_enable = false;
            $msg = 'Service has been suspended.';
        } else {
            $data = array('status' => 1);
            $service_enable = true;
            $msg = 'Service has been activated successfully.';
        }
        $this->services_model->update_where(['_id' => new ObjectId($objId)], $data);
        $this->update_portal_service($dbrow->service_name, $service_enable);
        echo json_encode(array('status' => true, 'msg' => $msg));
    } //End of update_service()

    public function update_portal_service($service_name, $service_enable)
    {
        $this->mongo_db->switch_db("portal");
        $this->mongo_db->where(array('service_name.en' => $service_name))->set(['enabled' => $service_enable])->update('services');
    }

    public function get_services(){
        $dept_code = $this->input->post('dept_code');
        $filter['dept_info.dept_code'] = $dept_code;
        $filter['status'] = 1;
        $res = $this->services_model->get_rows($filter);
        $data = array();
        foreach ($res as $row) {
            $serviceObj = json_encode(array("service_code" => $row->service_code, "service_name" => $row->service_name));
            $data[] = array("service_code" => $row->service_code, "service_name" => $row->service_name, "service_obj" => $serviceObj);
        } //End of foreach()
        $json_obj = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($json_obj);
    }

    private function checkObjectId($obj)
    {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        } //End of if else
    } //End of checkObjectId()
}
