<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Service_category extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("cat_model");
        $this->load->model("site_model");
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

    // public function add_cat_action()
    // {

    //     $data['cat_name'] = array(
    //         'en' => trim($this->input->post('cen', true)),
    //         'bn' => trim($this->input->post('cbn', true)),
    //         'as' => trim($this->input->post('cas', true)),
    //     );

    //     $config['upload_path'] = FCPATH . 'storage/PORTAL/images/';
    //     $config['allowed_types'] = 'jpeg|jpg|png';
    //     $config['overwrite'] = false;
    //     $config['file_ext_tolower'] = true;
    //     $config['max_size'] = 1024;
    //     $config['max_width'] = 400;
    //     $config['max_height'] = 300;

    //     $this->load->library('upload', $config);

    //     if (!$this->upload->do_upload('upload_pic')) {
    //         $error = array('error' => $this->upload->display_errors());
    //         $this->session->set_flashdata('error', $error['error']);
    //         // redirect('site/admin/service_category/add_cat');
    //     } else {
    //         // success in uploading image
    //         $file_data = array('upload_data' => $this->upload->data());
    //         $data['img_path'] = substr($file_data['upload_data']['full_path'], stripos($file_data['upload_data']['full_path'], "storage"));

    //         // upload sub-category images
    //         $sub_cat_images = $_FILES['img_pathh'];
    //         $config['upload_path'] = FCPATH . 'storage/PORTAL/images/category_images/';
    //         $config['file_name'] = '';
    //         $data['sub_categories'] = [];
    //         $this->load->library('upload', $config);
    //         // loop through all sub-categories
    //         foreach($this->input->post('en') as $key => $en_name) {

    //             $filename='';
    //             // handle sub-category image upload
    //             if (!empty($sub_cat_images['name'][$key])) {
    //                 // $config['file_name'] = $sub_cat_images['name'][$key];
    //                 // $this->load->library('upload', $config1);

    //                 if ($this->upload->do_upload('img_pathh['.$key.']')) {
    //                     echo "-".$key.'-success-------';

    //                     $file_data =array('upload_data' => $this->upload->data());
    //                     $filename = substr($file_data['full_path'], stripos($file_data['full_path'], "storage"));
    //                 } else {

    //                     echo "-".$key.'error--------';
    //                     // $error = array('error' => $this->upload->display_errors());
    //                     // $this->session->set_flashdata('error', $error['error']);
    //                 }

    //             }
    //                 array_push($data['sub_categories'],
    //                 array(
    //                 'en' => trim($en_name),
    //                 'bn' => trim($this->input->post('bn')[$key]),
    //                 'as' => trim($this->input->post('as')[$key]),
    //                 'img_path'=> $filename
    //             ));

    //         }

    //             $this->cat_model->add_new_cat($data);

    //         // $this->cat_model->add_new_sub_cat($data, $category_id);

    //     }
    //     die();
    //     $this->session->set_flashdata('success', 'Category Created Successfully.');
    //     redirect(base_url("site/admin/service_category/add_cat"));

    // }

    public function add_cat_action()
    {
        $data['cat_name'] = array(
            'en' => trim($this->input->post('cen', true)),
            'bn' => trim($this->input->post('cbn', true)),
            'as' => trim($this->input->post('cas', true)),
        );

        $config['upload_path'] = FCPATH . 'storage/PORTAL/images/';
        $config['allowed_types'] = 'jpeg|jpg|png';
        $config['overwrite'] = false;
        $config['file_ext_tolower'] = true;
        $config['max_size'] = 1024;
        $config['max_width'] = 400;
        $config['max_height'] = 300;

        $this->load->library('upload', $config); // Categoty image upload

        if (!$this->upload->do_upload('upload_pic')) {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('error', $error['error']);
            redirect('site/admin/service_category/add_cat');
        } else {
            // success in uploading image
            $file_data = array('upload_data' => $this->upload->data());
            $data['img_path'] = substr($file_data['upload_data']['full_path'], stripos($file_data['upload_data']['full_path'], "storage"));

            // upload sub-category images
            $config['upload_path'] = FCPATH . 'storage/PORTAL/images/category_images/';

            $this->upload->initialize($config);
            $data['sub_categories'] = [];
            foreach ($this->input->post('en') as $key => $en_name) {
                $filename = '';
                // handle sub-category image upload
                if (!empty($_FILES['img_pathh_' . $key]['name'])) {

                    if ($this->upload->do_upload('img_pathh_' . $key)) {
                        $file_data = array('upload_data' => $this->upload->data());
                        $filename = substr($file_data['upload_data']['full_path'], stripos($file_data['upload_data']['full_path'], "storage"));
                    } else {
                        // $error = array('error' => $this->upload->display_errors());
                        // $this->session->set_flashdata('error', $error['error']);
                        // redirect('site/admin/service_category/add_cat');
                    }
                }

                array_push($data['sub_categories'], array(
                    'en' => trim($en_name),
                    'bn' => trim($this->input->post('bn')[$key]),
                    'as' => trim($this->input->post('as')[$key]),
                    'img_path' => $filename,
                ));
            }

            $this->cat_model->add_new_cat($data);
            $this->session->set_flashdata('success', 'Category Created Successfully.');
            redirect(base_url("site/admin/service_category/add_cat"));
        }
    }

    public function update_cat_action()
    {
        
        $object_id = $this->input->post("object_id", true);
        $category = $this->cat_model->get_cat_by_obID($object_id);
        $data['cat_name'] = array(
            'en' => trim($this->input->post('cen', true)),
            'bn' => trim($this->input->post('cbn', false)),
            'as' => trim($this->input->post('cas', false)),
        );

            $config['upload_path'] = FCPATH . 'storage/PORTAL/images/';
            $config['allowed_types'] = 'jpeg|jpg|png';
            $config['overwrite'] = false;
            $config['file_ext_tolower'] = true;
            $config['max_size'] = 1024;
            $config['max_width'] = 400;
            $config['max_height'] = 300;

            $this->load->library('upload', $config);


        if ($_FILES['upload_pic']['size'] == 0) {
            // echo "0.1--------------<br/>";
            // only update cat_name & tag
            // $this->cat_model->update_cat($object_id, $data);
            // $this->session->set_flashdata('success', 'Category Updated Successfully.');
            // redirect(base_url("site/admin/service_category/add_cat/" . $object_id));
        } else {
            

            if (!$this->upload->do_upload('upload_pic')) {
                // echo "1--------------<br/>";
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('error', $error['error']);
                redirect('site/admin/service_category/add_cat/' . $object_id);

            } else {
                // echo "2--------------<br/>";

                // success in uploading image
                $file_data = array('upload_data' => $this->upload->data());
                $data['img_path'] = substr($file_data['upload_data']['full_path'], stripos($file_data['upload_data']['full_path'], "storage"));

                // $this->cat_model->update_cat($object_id, $data);
                // $this->session->set_flashdata('success', 'Category Updated Successfully.');
                // redirect(base_url("site/admin/service_category/add_cat/" . $object_id));
            }
        } 

            
            
            foreach ($category->sub_categories as $key => $value) {
                $services = $this->site_model->get_services_from_sub($category->cat_id, $value->en);
                if ($services) {
                // echo "3--------------<br/>";

                    foreach ($services as $key1 => $value1) {
                        $data1 = [
                            'sub_cat' => trim($this->input->post('en')[$key]),
                        ];
                        $this->site_model->update_sub($value1->{'_id'}->{'$id'}, $data1);

                    }
                }
            }

             // upload sub-category images
             $config['upload_path'] = FCPATH . 'storage/PORTAL/images/category_images/';
             $this->upload->initialize($config);
            $data['sub_categories'] = [];
            foreach ($this->input->post('en') as $key2 => $en_name) {
                $filename = '';
                // echo "4--------------<br/>";

                if (!empty($_FILES['img_pathh_' . $key2]['name'])) {

                    if ($this->upload->do_upload('img_pathh_' . $key2)) {
                        $file_data = array('upload_data' => $this->upload->data());
                        $filename = substr($file_data['upload_data']['full_path'], stripos($file_data['upload_data']['full_path'], "storage"));
                    } else {

                    }
                }
                
                array_push($data['sub_categories'], array(
                    'en' => trim($en_name),
                    'bn' => trim($this->input->post('bn')[$key2]),
                    'as' => trim($this->input->post('as')[$key2]),
                    'img_path' => $filename ? $filename :( isset($category->sub_categories[$key2]) ? $category->sub_categories[$key2]->img_path : ''),
                ));
            }
            // echo "5--------------<br/>";
                $this->cat_model->update_cat($object_id, $data);

            // pre($data);

                $this->session->set_flashdata('success', 'Category Updated Successfully.');
                redirect(base_url("site/admin/service_category/add_cat/" . $object_id));
            
        
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

    public function excel_upload()
    {
        
    }
}
