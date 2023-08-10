<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Document_category extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');
    }

    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Document Categories"));
        $this->load->view("admin/doc_cat");
        $this->load->view("admin/includes/footer");
    }

    public function add_doc_category($short_name = null)
    {

        $data['cat_name'] = ($this->settings_model->get_all_cat())[0]->categories;
        $data['action'] = 'Add Category';

        if ($short_name != null) {
            $data['action'] = 'Update Category';
            $data['cat_info'] = $this->settings_model->get_doc_category($short_name)->{'0'}->categories[0];

            //pre($data['cat_info']);
            //die($data['doc_info']);
        }
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | " . (($short_name) ? "Update Document Category" : "Add Dcoument Category")  ));
        $this->load->view("admin/add_doc_cat", $data);
        $this->load->view("admin/includes/footer");
    }

    public function delete_doc_cat()
    {
        $this->load->model('docs_model');

        $short_name = $this->input->post('short_name');

        $all_docs = (array) $this->docs_model->get_where(array("category" => $short_name));

        // pre($all_docs);

        // 1. First, delete all docs for that category

        foreach ($all_docs as $doc) {

            $path_url = parse_url(base_url($doc->path), PHP_URL_PATH);
            $file_sys_path = $_SERVER['DOCUMENT_ROOT'] . $path_url;

            if (unlink($file_sys_path)) {

                // delete db record
                $this->docs_model->delete($doc->{'_id'}->{'$id'});
            }

        }

        // 2. Thene delete the category itself

        $this->settings_model->delete_doc_category($short_name);

        $this->session->set_flashdata('success', 'Document Category deleted.');
        redirect('site/admin/document_category');

    }

    public function add_doc_category_action()
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

            $this->settings_model->add_doc_category($data);
        } 
        else {
            // update
            $this->load->model('docs_model');

            $this->settings_model->update_doc_category(trim($action), $data);

            // Also update the relevant docs
            $this->docs_model->update_docs_by_categ(trim($action), array("category" => $data['short_name']));
        }

        $this->session->set_flashdata('success', 'Document Category Saved Successfully.');
        redirect(base_url("site/admin/document_category/add_doc_category"));
    }

    public function doc_category_api()
    {
        $all_catg = ($this->settings_model->get_all_cat())[0]->categories;

        foreach ($all_catg as $key => $doc_cat) {

            // Adding Sl No.
            $doc_cat->{'sl_no'} = $key + 1;
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($all_catg));
    }

    public function check_doc_category_api($key)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => true,
                'count' => $this->settings_model->count_doc_category($key),
            )));

    }
}
