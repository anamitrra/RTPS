<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Site_text extends Rtps
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/content_model');
    }

    public function index()
    {

        $data['all'] = $this->content_model->site_text();

        foreach ($data['all'] as $value) {
            $arr = explode("_", $value->name);
            $value->name_formatted = ucfirst($arr[0]) . " " . ucfirst($arr[1] ?? '');
        }

        // pre($data['all']);

        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Site Text"));

        $data['name'] = '';

        if (!empty($this->input->post("name"))) {
            $data['name'] = $this->input->post("name");
        }

        $name = $this->input->post("name");

        $data['results'] = $this->content_model->get_text($name);

        $this->load->view('admin/site_texts', $data);
        $this->load->view("admin/includes/footer");
    }

    public function update()
    {
        $raw_data = trim($this->input->raw_input_stream);
        // pre($raw_data);

        $arr = explode("jsonbox=", $raw_data);
        // pre($arr);

        $arr =  explode("name=", trim($arr[1]));

        // $json_str = trim($arr[1], " name");
        $data = json_decode($arr[0], true);


        $name = $arr[1];
        
        // pre([$name, $data]);

        $this->content_model->update_text($name, $data);
        // pre($d);

        $this->session->set_flashdata('success', 'Successfully Updated');
        redirect('site/admin/site_text');
    }

    public function add_text()
    {
        $new_set = trim($this->input->raw_input_stream);
        //pre($new_set);

        $arr = explode("new_setting=", $new_set);
        // pre($arr);

        $arr =  explode("name=", trim($arr[1]));


        // $json_str = trim($arr[1], " name");
        $data = json_decode($arr[0], true);
        // pre($data['name']);
        // Must be Unique
        $check_name = $this->content_model->get_name($data['name']);
        // pre($check_name);

        if (count($check_name)) {
            $this->session->set_flashdata('error', "A Document with name $data[name] Already Exists!");
            redirect(base_url("site/admin/site_text"));
        } else {
            $this->content_model->add_text($data);
            $this->session->set_flashdata('success', 'Successfully Added');
            redirect('site/admin/site_text');
        }
    }
}
