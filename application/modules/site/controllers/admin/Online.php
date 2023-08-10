<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Online extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('site_model');
    }

    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Services"));
        $this->load->view("admin/services");
        $this->load->view("admin/includes/footer");
    }

    // Create/Update
    public function add_new_service($ob_id = null)
    {
        $this->load->model('admin/dept_model');
        $this->load->model('cat_model');
        $data['dept_list'] = $this->dept_model->get_all_depts();
        $data['cat_list'] = $this->cat_model->get_all_cat();

        $data['action_name_service'] = 'Add Service';
        $data['action_name_guide'] = 'Add Guidelines';
        $data['action_name_req'] = 'Add Requirements';
        $data['action_name_notice'] = 'Add Notice';

        if ($ob_id === 'create') {
            // Delete already existing services IDs from session,  if any
            $this->session->unset_userdata('new_service_id');

            $ob_id = null;
        } elseif ($ob_id !== null) {
            $data['service_info'] = $this->site_model->get_service_by_objectID($ob_id);

            // Delete already existing services IDs from session,  if any
            $this->session->unset_userdata('new_service_id');

            $data['action_name_service'] = 'Update Service';
            $data['action_name_guide'] = 'Update Guidelines';
            $data['action_name_req'] = 'Update Requirements';
            $data['action_name_notice'] = 'Update Notice';
        }

        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | " . ($ob_id !== null ? 'Update Service' : 'Add Service')));
        $this->load->view("admin/add_service", $data);
        $this->load->view("admin/includes/footer");
    }

    public function add_service_info_action()
    {
        /* TODO :: Add Validation */

        $this->load->helper('portal');

        $object_id = $this->input->post("object_id", true); // In case of UPDATE

        $data['service_id'] = $this->input->post("service_id", true);

        // Check if this service already exists in case of insert

        // count($this->site_model->get_service_by_serviceID($data['service_id']))
        // service_ids for eodb/rtps services may be the same.

        if (empty($object_id) and false) {

            $this->session->set_flashdata('error', "Service with ID $data[service_id] already exists!");
            redirect('site/admin/online/add_new_service');
        } else {
            $data['service_name'] = array(
                'en' => $this->input->post('service_en', true),
                'bn' => $this->input->post('service_bn', true),
                'as' => $this->input->post('service_as', true),
            );

            $data['department_id'] = $this->input->post("dept_id", true);

            $data['cat_id'] = intval($this->input->post("cat_id", true));
            $data['sub_cat'] = trim($this->input->post("sub_cat", true));

            // Generate automatically from 'service_en'
            $data['seo_url'] = generate_seo_url($data['service_name']['en']);

            $data['online'] = boolval($this->input->post("online", true));
            $data['is_new'] = boolval($this->input->post("is_new", true));
            $data['enabled'] = boolval($this->input->post("enable", true));

            if (!empty($this->input->post("service_url", true))) {
                $data['service_url'] = $this->input->post("service_url", true);
            }

            if (!empty($this->input->post("mis_id", true))) {
                $data['mis_id'] = $this->input->post("mis_id", true);
            }

            $data['kiosk_availability'] = strtoupper($this->input->post("kiosk", true));

            $data['service_type'] = strtoupper($this->input->post("service_type", true));

            $data['ext_service_type'] = strtoupper($this->input->post("ext_service_type", true));


            if (empty($object_id)) {
                # Insert

                $res = $this->site_model->add_new_service($data);


                //    Storing ObjectId in session
                $this->session->set_userdata('new_service_id', $res['_id']->{'$id'});

                $this->session->set_flashdata('success', 'Service Created Successfully. Now you can add Requirements, Guidelines and Documents.');
                redirect('site/admin/online/add_new_service');
            } else {
                # Update
                $this->site_model->update_service_info($object_id, $data);

                $this->session->set_flashdata('success', 'Service Updated Successfully');
                redirect('site/admin/online/add_new_service/' . $object_id);
            }
        }
    }
    public function add_service_guidelines_action()
    {
        $data = array(
            'en' => htmlspecialchars($_POST['guidelines-en']),
            'as' => htmlspecialchars($_POST['guidelines-as']),
            'bn' => htmlspecialchars($_POST['guidelines-bn']),
        );

        $object_id = $this->input->post("object_id", true); // in case of UPDATE
        $service_ob_id = $this->session->userdata('new_service_id'); // in case of INSERT

        if (!empty($service_ob_id)) {

            $this->site_model->update_service_guidelines($service_ob_id, $data);

            $this->session->set_flashdata('success', 'Service Guidelines Added');
            redirect('site/admin/online/add_new_service');
        } elseif (!empty($object_id)) {
            $this->site_model->update_service_guidelines($object_id, $data);

            $this->session->set_flashdata('success', 'Service Guidelines Updated');
            redirect('site/admin/online/add_new_service/' . $object_id);
        } else {
            $this->session->set_flashdata('error', 'Please create the Service first.');
            redirect('site/admin/online/add_new_service');
        }
    }

    public function add_service_requirements_action()
    {
        $data = array(
            'en' => htmlspecialchars($_POST['requirements-en']),
            'as' => htmlspecialchars($_POST['requirements-as']),
            'bn' => htmlspecialchars($_POST['requirements-bn']),
        );

        $object_id = $this->input->post("object_id", true); // in case of UPDATE
        $service_ob_id = $this->session->userdata('new_service_id'); // in case of INSERT

        if (!empty($service_ob_id)) {

            $this->site_model->update_service_requirements($service_ob_id, $data);

            $this->session->set_flashdata('success', 'Service Requirements Added');
            redirect('site/admin/online/add_new_service');
        } elseif (!empty($object_id)) {
            $this->site_model->update_service_requirements($object_id, $data);

            $this->session->set_flashdata('success', 'Service Requirements Updated');
            redirect('site/admin/online/add_new_service/' . $object_id);
        } else {
            $this->session->set_flashdata('error', 'Please create the Service first.');
            redirect('site/admin/online/add_new_service');
        }
    }

    public function add_service_notice_action()
    {
        $data = array(
            'en' => htmlspecialchars($_POST['notice-en']),
            'as' => htmlspecialchars($_POST['notice-as']),
            'bn' => htmlspecialchars($_POST['notice-bn']),
        );

        $object_id = $this->input->post("object_id", true); // in case of UPDATE
        $service_ob_id = $this->session->userdata('new_service_id'); // in case of INSERT

        if (!empty($service_ob_id)) {

            $this->site_model->update_service_notice($service_ob_id, $data);

            $this->session->set_flashdata('success', 'Service Notice Added');
            redirect('site/admin/online/add_new_service');
        } elseif (!empty($object_id)) {
            $this->site_model->update_service_notice($object_id, $data);

            $this->session->set_flashdata('success', 'Service Notice Updated');
            redirect('site/admin/online/add_new_service/' . $object_id);
        } else {
            $this->session->set_flashdata('error', 'Please create the Service first.');
            redirect('site/admin/online/add_new_service');
        }
    }

    public function upload_service_document()
    {
        $doc_name['en'] = $this->input->post('doc_name_en', true);
        $doc_name['as'] = $this->input->post('doc_name_as', true);
        $doc_name['bn'] = $this->input->post('doc_name_bn', true);

        // CI File upload Settings
        $config['upload_path'] = FCPATH . 'storage/PORTAL/documents/';
        $config['allowed_types'] = 'jpeg|jpg|png|pdf';
        $config['max_size'] = 0;
        $config['overwrite'] = false;
        $config['file_ext_tolower'] = true;

        $object_id = $this->input->post("object_id", true); // in case of UPDATE
        $service_ob_id = $this->session->userdata('new_service_id'); // in case of INSERT

        if (!empty($service_ob_id) || !empty($object_id)) {

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('service-doc')) {
                $error = array('error' => $this->upload->display_errors());

                $this->session->set_flashdata('error', $error['error']);
                redirect('site/admin/online/add_new_service/' . $object_id ?? '');
            } else {
                $file_data = array('upload_data' => $this->upload->data());

                // Document's objectID
                $_id = $service_ob_id ?? $object_id;

                $this->site_model->update_service_documents($_id, array(
                    'name' => $doc_name,
                    'path' => substr($file_data['upload_data']['full_path'], stripos($file_data['upload_data']['full_path'], "storage")),
                ));

                $this->session->set_flashdata('success', 'Document Uploaded Successfully');
                redirect('site/admin/online/add_new_service/' . $object_id ?? '');
            }
        } else {
            $this->session->set_flashdata('error', 'Please create the Service first.');

            redirect('site/admin/online/add_new_service');
        }
    }

    public function all_services_api()
    {
        $all_services = (array) $this->site_model->get_all_services();
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($all_services));
    }

    public function delete_service()
    {
        $ob_id = $this->input->post('object_id', true);

        $service_data = $this->site_model->get_service_by_objectID($ob_id);

        if (property_exists($service_data, 'documents')) {
            // Delete service documents, if any

            foreach ($service_data->documents as $doc) {

                $path_url = parse_url(base_url($doc->path), PHP_URL_PATH);
                $file_sys_path = $_SERVER['DOCUMENT_ROOT'] . $path_url;

                unlink($file_sys_path);
            }
        }

        $this->site_model->delete($ob_id);

        $this->session->set_flashdata('success', 'Service deleted successfully.');
        redirect('site/admin/online');
    }

    public function delete_service_document()
    {
        $object_id = $this->input->post('object_id', true);
        $doc_path = $this->input->post('doc_path', true);
        $path_url = parse_url(base_url($doc_path), PHP_URL_PATH);
        $file_sys_path = $_SERVER['DOCUMENT_ROOT'] . $path_url;

        if (unlink($file_sys_path)) {
            $this->site_model->delete_service_doc($object_id, $doc_path);

            $this->session->set_flashdata('success', 'Service document deleted successfully.');
        } else {

            $this->session->set_flashdata('error', 'Sorry, the document could not be deleted');
        }

        redirect('site/admin/online/add_new_service/' . $object_id);
    }

    public function sub_categories_api($cat_id)
    {
        $sub_categories = (array) $this->site_model->get_sub_categories(intval($cat_id));

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($sub_categories[0]));
    }


    //test purpose

}
