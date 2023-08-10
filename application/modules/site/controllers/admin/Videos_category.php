<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Videos_category extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');
    }

    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Document Categories"));
        $this->load->view("admin/video_cat");
        $this->load->view("admin/includes/footer");
    }

    public function add_video_category($short_name = null)
    {

        $data['video_name'] = ($this->settings_model->get_all_video_cat())[0]->categories;
        $data['action'] = 'Add Category';

        if ($short_name != null) {
            $data['action'] = 'Update Category';
            $data['cat_info'] = $this->settings_model->get_video_category($short_name)->{'0'}->categories[0];

            //pre($data['cat_info']);
            //die($data['doc_info']);
        }
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | " . (($short_name) ? "Update Video Category" : "Add Video Category")  ));
        $this->load->view("admin/add_video_cat", $data);
        $this->load->view("admin/includes/footer");
    }

    public function delete_video_cat()
    {
        $this->load->model('video_model');

        $short_name = $this->input->post('short_name');


        // 1. First, delete all videos for that category
        $this->video_model->delete_by_filter(array("category"=>$short_name));


        // 2. Thene delete the category itself

        $this->settings_model->delete_video_category($short_name);

        $this->session->set_flashdata('success', 'Video Category deleted.');
        redirect('site/admin/videos_category');

    }

    public function add_video_category_action()
    {
        /* TODO :: Add Validation */

        $data['title'] = array(
            'en' => $this->input->post('en', true),
            'as' => $this->input->post('as', true),
            'bn' => $this->input->post('bn', true),
        );

        $data['short_name'] = implode('_', explode(' ', strtoupper(trim($this->input->post('short_name', true)))));

        $action = $this->input->post('action', true);

        // pre($action);
        // pre($data);

        if (empty($action)) {
            # insert doc category

            $this->settings_model->add_video_category($data);
        } 
        else {
            // update
            $this->load->model('video_model');

            $this->settings_model->update_video_category(trim($action), $data);

            // Also update the relevant docs
            $this->video_model->update_video_by_categ(trim($action), array("category" => $data['short_name']));
        }

        $this->session->set_flashdata('success', 'Video Category Saved Successfully.');
        redirect(base_url("site/admin/videos_category/add_video_category"));
    }

    public function video_category_api()
    {
        $all_catg = ($this->settings_model->get_all_video_cat())[0]->categories;

        foreach ($all_catg as $key => $vid_cat) {

            // Adding Sl No.
            $vid_cat->{'sl_no'} = $key + 1;
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($all_catg));
    }

    public function check_video_category_api($key)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => true,
                'count' => $this->settings_model->count_video_category($key),
            )));

    }
}
