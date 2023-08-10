<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');

class Download_data extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/content_model');
    }

    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Services"));

        $data = array();   // data to pass to view
        $dir_path = FCPATH . 'storage/MIS_DATA';

        // Check if directory MIS_DATA exists
        if (!is_dir($dir_path)) {

            $data['error'] = 'MIS_DATA Directory Not Found!';
        } else {
            // Read the files inside.
            $files = scandir($dir_path, SCANDIR_SORT_DESCENDING);
            if (empty($files)) {

                $data['error'] = 'Cannot read the content of MIS_DATA Directory!';
            }

            $files = array_filter($files, function ($f) use ($dir_path) {
                return (preg_match('/^\.+/imu', $f) !== 1) && (is_file("{$dir_path}/{$f}"));
            });

            $data['files'] = $files;
        }

        $this->load->view("admin/data_download", $data);
        $this->load->view("admin/includes/footer");
    }

    public function dl_action($file_name = '')
    {
        $path = FCPATH . 'storage/MIS_DATA/' . trim($file_name);

        if (!is_file($path)) {
            $this->session->set_flashdata('error', "File Does Not Exists!!");
            redirect(base_url('site/admin/download_data/index'));
        }

        $this->load->library('zip');
        $this->zip->compression_level = 7;     // default is 2
        $this->zip->read_file($path);
        $this->zip->download("{$file_name}.zip");
    }
}
