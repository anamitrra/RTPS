<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Banners extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/content_model');
    }

    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Banners"));
        $data['banner']=$this->content_model->get_banners()->{'0'}->banners;
       // pre($data);
        $this->load->view("admin/banners",$data);
        $this->load->view("admin/includes/footer");
    }


    public function upload_banners()
    {
        $lang_arr = $this->input->post('lang_arr', true);


        // CI File upload Settings
        $config['upload_path'] = FCPATH . 'storage/PORTAL/images/banners';
        $config['allowed_types'] = 'jpeg|jpg|png';
       // $config['path'] = $config['upload_path'];
       $config['max_size'] = 500;
    //    $config['max_width'] = 1677;
    //    $config['max_height'] = 370;
       $config['overwrite'] = false;
       $config['file_ext_tolower'] = true;


            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('banner')) {
                $error = array('error' => $this->upload->display_errors());

                $this->session->set_flashdata('error', $error['error']);
                redirect('site/admin/banners');

            } else {

                // Getting banner position

                $position = intval(trim($this->input->post('position', true))) - 1;


                $file_data = array('upload_data' => $this->upload->data());
                $path = substr($file_data['upload_data']['full_path'], stripos($file_data['upload_data']['full_path'], "storage"));

                //pre($path);

                // 
                // array(
                //     'lang'=>$lang_arr,
                    
                //     'path'=> substr($file_data['upload_data']['full_path'], stripos($file_data['upload_data']['full_path'], "storage")),
                // )

               

                $this->content_model->update_banners($lang_arr, $path, $position);
               // pre($config['path']);

                $this->session->set_flashdata('success', 'Banner Uploaded Successfully');
                redirect('site/admin/banners');
            }
        

            redirect('site/admin/banners');
        
    }

   

    

    public function delete_banners()
    {
        $doc_path = $this->input->post('doc_path', true);
        $lang_arr = $this->input->post('lang_arr', true);
        $path_url = parse_url(base_url($doc_path), PHP_URL_PATH);
        $file_sys_path = $_SERVER['DOCUMENT_ROOT'] . $path_url;
       //pre($doc_path);

        if (unlink($file_sys_path)) {
            $this->content_model->delete_banners( $doc_path, $lang_arr);

            $this->session->set_flashdata('success', 'Banner deleted successfully.');

        } else {

            $this->session->set_flashdata('error', 'Sorry, the banner could not be deleted');

        }

        redirect('site/admin/banners');
    }
}
