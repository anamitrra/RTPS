<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notice extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/notice_model');
    }

    // Get all notices new
    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Notices"));
        $data["notices"] = $this->notice_model->get_all_notices();
        // pre($data);

        $this->load->view("admin/notice", $data);
        $this->load->view("admin/includes/footer");
    }

    // Create notice new
    public function add_new_notice()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Add Notice"));
        $this->load->view("admin/add_notice");
        $this->load->view("admin/includes/footer");
    }

    // ADD notice new
    public function add_notice_info_action()
    {

        $data['title'] = array(
            'en' => $this->input->post('notice_en', true),
            'bn' => $this->input->post('notice_bn', true),
            'as' => $this->input->post('notice_as', true),
        );

        $data['desc'] = array(
            'en' => $this->input->post('notice_desc_en', true),
            'bn' => $this->input->post('notice_desc_bn', true),
            'as' => $this->input->post('notice_desc_as', true),
        );

        $data['link'] = array(
            'en' => "Click Here To Proceed",
            'bn' => "এগিয়ে যেতে এখানে ক্লিক করুন",
            'as' => "আগবাঢ়িবলৈ ইয়াত ক্লিক কৰক",
            'url' => $this->input->post("notice_url", true),
        );

        // $data['notice_url'] = $this->input->post("notice_url", true);

        $data['newly_launched'] = boolval($this->input->post("newly_launched", true));


        $res = $this->notice_model->add_new_notice($data);

        $this->session->set_flashdata('success', 'Notice Created Successfully.');
        redirect('site/admin/notice');
    }

    // Delete notice new
    public function delete($index)
    {
        $notice = $this->notice_model->get_single_notice($index);
        // $notice_url = ($data['notice']['notice_url']);
        // pre($notice);

        $this->notice_model->delete_notice($notice->link->url);

        $this->session->set_flashdata('success', 'Notice deleted Successfully.');
        redirect('site/admin/notice');
    }

    // Update notice view new
    public function update($index)
    {

        $data["notice"] = $this->notice_model->get_single_notice($index);
        $data["index"] = $index;

        $this->load->view("admin/includes/header");
        $this->load->view("admin/update_notice", $data);
        $this->load->view("admin/includes/footer");
    }

    // Update notice new
    public function notice_update()
    {
        /* TODO :: Add Validation */

        $edit_url = $this->input->post("edit_url", true);
        $index = $this->input->post("index", true);

        $data['title'] = array(
            'en' => $this->input->post('notice_en', true),
            'bn' => $this->input->post('notice_bn', true),
            'as' => $this->input->post('notice_as', true),
        );

        $data['desc'] = array(
            'en' => $this->input->post('notice_desc_en', true),
            'bn' => $this->input->post('notice_desc_bn', true),
            'as' => $this->input->post('notice_desc_as', true),
        );

        $data['link'] = array(
            'en' => "Click Here To Proceed",
            'bn' => "এগিয়ে যেতে এখানে ক্লিক করুন",
            'as' => "আগবাঢ়িবলৈ ইয়াত ক্লিক কৰক",
            'url' => $this->input->post("notice_url", true)
        );

        $data['newly_launched'] = boolval($this->input->post("newly_launched", true));


        /* $notices = $this->notice_model->get_all_notices();
        for ($x = 0; $x < count($notices); $x++) {
            if ($notices[$x]->link->url === $edit_url) {
                break;
            }
        } */

        $this->notice_model->update_notice($edit_url, $data, $index);

        $this->session->set_flashdata('success', 'Notice Updated Successfully');
        redirect('site/admin/notice');
    }
}
