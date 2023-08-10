<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Documents extends Rtps
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');
        $this->load->model('docs_model');
    }

    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Documents"));
        $this->load->view("admin/documents");
        $this->load->view("admin/includes/footer");
    }

    public function upload_document($ob_id = null)
    {
        $data['cat_list'] = ($this->settings_model->get_all_cat())[0]->categories;
        $data['action'] = 'add';

        if ($ob_id != null) {
            $data['action'] = 'update';
            $data['doc_info'] = $this->docs_model->get_by_doc_id($ob_id);
        }

        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Upload Document"));
        $this->load->view("admin/upload_doc", $data);
        $this->load->view("admin/includes/footer");
    }

    public function delete_document()
    {
        $ob_id = $this->input->post('object_id');

        // pre($ob_id);
        // get the file path from db
        $db_path = $this->docs_model->get_by_doc_id($ob_id)->path;

        // pre($db_path);

        // delete file from the filesystem
        $path = parse_url(base_url($db_path), PHP_URL_PATH);
        $file_sys_path = $_SERVER['DOCUMENT_ROOT'] . $path;

        if (unlink($file_sys_path)) {

            // delete db record
            $this->docs_model->delete($ob_id);

            $this->session->set_flashdata('success', 'Document deleted successfully.');
            redirect('site/admin/documents');
        } else {

            $this->session->set_flashdata('error', 'Document could not be deleted.');
            redirect('site/admin/documents');
        }

    }

    public function upload_document_action()
    {
        $data_to_save['name'] = array(
            'en' => $this->input->post('en'),
            'bn' => $this->input->post('bn'),
            'as' => $this->input->post('as'),
        );
        $data_to_save['category'] = $this->input->post('cat_name');
        $data_to_save['sub_category'] = array();

        $ob_id = $this->input->post('object_id');

        if ($ob_id && $_FILES['upload_doc']['size'] == 0) {
            // only update doc_name & category

            $this->docs_model->update_doc_info($ob_id, $data_to_save);

            $this->session->set_flashdata('success', 'Information Updated Successfully');
            redirect('site/admin/documents/upload_document/' . $ob_id ?? '');

        } else {

            // CI File upload Settings
            $config['upload_path'] = FCPATH . 'storage/PORTAL/documents/';
            $config['allowed_types'] = 'jpeg|jpg|png|pdf';
            $config['max_size'] = 0;
            $config['overwrite'] = FALSE;
            $config['file_ext_tolower'] = TRUE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('upload_doc')) {

                $error = array('error' => $this->upload->display_errors());

                $this->session->set_flashdata('error', $error['error']);

                redirect('site/admin/documents/upload_document/' . $ob_id ?? '');

            } else {
                $data = array('upload_data' => $this->upload->data());

                $data_to_save['path'] = substr($data['upload_data']['full_path'], stripos($data['upload_data']['full_path'], "storage"));

                $this->docs_model->upload_document($ob_id, $data_to_save);

                $this->session->set_flashdata('success', 'Document Uploaded Successfully');
                redirect('site/admin/documents/upload_document/' . $ob_id ?? '');
            }

        }

    }

    public function all_docs_api()
    {
        $all_docs = $this->docs_model->get_all_docs();
        $all_catg = ($this->settings_model->get_all_cat())[0]->categories;
        //pre($all_catg);

        foreach ($all_docs as $key => $doc) {

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
            ->set_output(json_encode($all_docs));
    }

}
