<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Service_category extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("cat_model");
    }

    public function index()
    {

        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Service Categories"));
        $this->load->view("admin/categories");
        $this->load->view("admin/includes/footer");
    }

    // Create/Update
    public function add_cat($ob_id = null)
    {
        $data['action_name'] = 'Add Category';
        $data['action_url'] = 'add_cat_action';

        if ($ob_id !== null) {
            $data['cat_info'] = $this->cat_model->get_cat_by_obID($ob_id);
            $data['action_name'] = 'Update Category';
            $data['action_url'] = 'update_cat_action';
        }

        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | " . (empty($ob_id) ? 'Add' : 'Update') . " Service Category"));
        $this->load->view("admin/add_cat", $data);
        $this->load->view("admin/includes/footer");
    }

    public function add_cat_action()
    {
        /* TODO :: Add Validation
        TODO :: check whether category already exists
         */

        $data['cat_name'] = array(
            'en' => trim($this->input->post('cen', true)),
            'bn' => trim($this->input->post('cbn', true)),
            'as' => trim($this->input->post('cas', true)),
        );

        $data['tag'] = array(
            'en' => trim($this->input->post('ten', true)),
            'bn' => trim($this->input->post('tbn', true)),
            'as' => trim($this->input->post('tas', true)),
        );

        $config['upload_path'] = FCPATH . 'storage/PORTAL/images/';
        $config['allowed_types'] = 'jpeg|jpg|png';
        $config['overwrite'] = false;
        $config['file_ext_tolower'] = true;
        $config['max_size'] = 1024;
        $config['max_width'] = 400;
        $config['max_height'] = 300;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('upload_pic')) {

            // display errors

            $error = array('error' => $this->upload->display_errors());

            $this->session->set_flashdata('error', $error['error']);

            redirect('site/admin/service_category/add_cat');

        } else {

            // success in uploading image

            $file_data = array('upload_data' => $this->upload->data());

            $data['img_path'] = substr($file_data['upload_data']['full_path'], stripos($file_data['upload_data']['full_path'], "storage"));

            $this->cat_model->add_new_cat($data);

            $this->session->set_flashdata('success', 'Category Created Successfully.');
            redirect(base_url("site/admin/service_category/add_cat"));
        }
    }

    public function update_cat_action()
    {
        /* TODO :: Add validation */

        $object_id = $this->input->post("object_id", true);

        $data['cat_name'] = array(
            'en' => trim($this->input->post('cen', true)),
            'bn' => trim($this->input->post('cbn', true)),
            'as' => trim($this->input->post('cas', true)),
        );

        $data['tag'] = array(
            'en' => trim($this->input->post('ten', true)),
            'bn' => trim($this->input->post('tbn', true)),
            'as' => trim($this->input->post('tas', true)),
        );

        if ($_FILES['upload_pic']['size'] == 0) {
            // only update cat_name & tag

            $this->cat_model->update_cat($object_id, $data);

            $this->session->set_flashdata('success', 'Category Updated Successfully.');
            redirect(base_url("site/admin/service_category/add_cat/" . $object_id));

        } else {

            $config['upload_path'] = FCPATH . 'storage/PORTAL/images/';
            $config['allowed_types'] = 'jpeg|jpg|png';
            $config['overwrite'] = false;
            $config['file_ext_tolower'] = true;
            $config['max_size'] = 1024;
            $config['max_width'] = 400;
            $config['max_height'] = 300;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('upload_pic')) {

                // display errors

                $error = array('error' => $this->upload->display_errors());

                $this->session->set_flashdata('error', $error['error']);

                redirect('site/admin/service_category/add_cat/' . $object_id);

            } else {

                // success in uploading image

                $file_data = array('upload_data' => $this->upload->data());

                $data['img_path'] = substr($file_data['upload_data']['full_path'], stripos($file_data['upload_data']['full_path'], "storage"));

                $this->cat_model->update_cat($object_id, $data);

                $this->session->set_flashdata('success', 'Category Updated Successfully.');
                redirect(base_url("site/admin/service_category/add_cat/" . $object_id));
            }

        }
    }

    public function all_cat_api()
    {
        $data = $this->cat_model->get_all_cat();

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    public function delete_categ()
    {
        $this->load->model("site_model");

        $object_id = $this->input->post('object_id', true);
        $cat_id = $this->input->post('cat_id', true);

        // Delete categ pic first
        $db_path = $this->cat_model->get_cat_by_obID($object_id)->img_path;

        $path = parse_url(base_url($db_path), PHP_URL_PATH);
        $file_sys_path = $_SERVER['DOCUMENT_ROOT'] . $path;

        if (unlink($file_sys_path)) {
            // delete categeory
            $this->cat_model->delete($object_id);

            $this->site_model->delete_services_by_filter(array('cat_id' => intval($cat_id)));
            // also delete related services

            $this->session->set_flashdata('success', 'Service Category deleted successfully.');
            redirect('site/admin/service_category');

        } else {
            $this->session->set_flashdata('error', 'Service Category could not be deleted.');
            redirect('site/admin/service_category');

        }

    }
}
