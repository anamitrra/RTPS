<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Miscellaneous extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/dashboard_model");
    }

    public function index()
    {
        $footer_data = $this->dashboard_model->get_settings('footer_new');
        $data['site_alert_model'] = $footer_data->site_alert_model;
        $data['last_update']=$footer_data->last_update->date;
       
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Miscellaneous"));
        $this->load->view("admin/miscellaneous", $data);
        $this->load->view("admin/includes/footer");
    }

    public function set_last_updated_portal()
    {
        $this->form_validation->set_rules('last_update', 'Last updated date', 'trim|required');

        $update_date = trim($this->input->post('last_update', TRUE));

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors('<p class="mb-0">'));
            redirect('site/admin/miscellaneous/index');
        }
        else {
            $this->dashboard_model->update_last_updated_time(
                implode(".", array_reverse(explode("-", $update_date)))
            );
            $this->session->set_flashdata('success', 'Date Updated Successfully');
            redirect(base_url("site/admin/miscellaneous/index"));
        }

        
    }

    public function portal_alert_action()
    {
        $this->form_validation->set_rules('en', 'Alert message in english', 'trim|required');
        $this->form_validation->set_rules('bn', 'Alert message in bangla', 'trim|required');
        $this->form_validation->set_rules('as', 'Alert message in assamese', 'trim|required');
        $this->form_validation->set_rules('enable', 'Alert Action', 'trim|required|is_natural|in_list[0,1]');

        $data['body'] = array(
            'en' => $this->input->post('en', true),
            'bn' => $this->input->post('bn', true),
            'as' => $this->input->post('as', true),
        );
        $data['enable'] = boolval($this->input->post("enable", true));

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors('<p class="mb-0">'));
            redirect('site/admin/miscellaneous/index');
        }
        else {
            $this->dashboard_model->update_portal_alert($data);
            $this->session->set_flashdata('success', 'Portal Alert ' . ($data['enable'] ? 'Enabled.' : 'Disabled.'));
            redirect(base_url("site/admin/miscellaneous/index"));
        }
    }

}
