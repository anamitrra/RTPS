<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Videos extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');
        $this->load->model('video_model');
    }

    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Videos"));
        $this->load->view("admin/videos");
        $this->load->view("admin/includes/footer");
    }

    public function upload_video($ob_id = null)
    {
        $data['cat_list'] = ($this->settings_model->get_all_video_cat())[0]->categories;
        $data['action'] = 'add';

        if ($ob_id != null) {
            $data['action'] = 'update';
            $data['video_info'] = $this->video_model->get_by_doc_id($ob_id);
        }

        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Upload Video"));
        $this->load->view("admin/upload_video", $data);
        $this->load->view("admin/includes/footer");
    }

    public function upload_video_action()
    {
        $data['name'] = array(
            'en' => $this->input->post('en', true),
            'bn' => $this->input->post('bn', true),
            'as' => $this->input->post('as', true),
        );
        $data['category'] = $this->input->post('cat_name', true);
        $data['url'] = $this->input->post('url', true);

        $ob_id = $this->input->post('object_id');

        if (!empty($ob_id)) {
            // update video

            $this->video_model->update($ob_id, $data);

            $this->session->set_flashdata('success', 'Information Updated Successfully');
            redirect('site/admin/videos/upload_video/' . $ob_id);

        } else {
            // Insert new video

            $this->video_model->insert($data);

            $this->session->set_flashdata('success', 'Video Added Successfully');
            redirect('site/admin/videos/upload_video');
        }

    }

    public function delete_video()
    {
        $ob_id = $this->input->post('object_id');

        // delete db record
        $this->video_model->delete($ob_id);

        $this->session->set_flashdata('success', 'Video deleted successfully.');
        redirect('site/admin/videos');
    }

    public function all_videos_api()
    {
        $all_videos = $this->video_model->get_all_videos();
        $all_catg = ($this->settings_model->get_all_video_cat())[0]->categories;

        foreach ($all_videos as $key => $doc) {

            // Adding Sl No.
            $doc->{'sl_no'} = $key + 1;

            foreach ($all_catg as $cat) {
                if ($doc->category === $cat->short_name) {
                    $doc->category = $cat;

                    break;
                }
            }

        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($all_videos));
    }

}
