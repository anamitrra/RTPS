<?php
defined("BASEPATH") or exit("No direct script access allowed");

if (!function_exists("cifileuploaddigilocker")) {
    function cifileuploaddigilocker($fieldName, $fileTypes = null, $folder = "docs/digilockeDocs/")
    {
        $folder_path = 'storage/' . $folder . date("Y") . '/' . date("m") . '/' . date("d");
        $pathname = FCPATH . $folder_path;
        if (!is_dir($pathname)) {
            mkdir($pathname, 0777, true);
            file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
        }
        $config['upload_path'] = $pathname;
        $config['allowed_types'] = strlen($fileTypes) ? $fileTypes : 'JPG|JPEG|GIF|PNG|PDF|jpg|jpeg|gif|png|pdf';
        $config['max_size'] = 0;
        $config['encrypt_name'] = TRUE;
        $ci = &get_instance();
        $ci->load->library('upload', $config);
        if ($ci->upload->do_upload($fieldName)) {
            $uploadRes = $ci->upload->data();
            $data = array('upload_status' => 1, 'uploaded_path' => $folder_path . $uploadRes["file_name"]);
            return $data;
        } else {
            $error = array('upload_status' => 0, 'error' => $ci->upload->display_errors());
            return $error;
        } //End of if else
    } //End of cifileuploaddigilocker()
} //End of if

if (!function_exists("movedigilockerfile")) {
    function movedigilockerfile($filePath, $fileTypes = null, $folder = "docs/")
    {
        $folder_path = 'storage/' . $folder . date("Y") . '/' . date("m") . '/' . date("d") . '/';
        $pathname = FCPATH . $folder_path;
        if (!is_dir($pathname)) {
            mkdir($pathname, 0777, true);
            file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
        }
        $fileName = (explode('/', $filePath))[6];
        $final_path_file = $pathname . $fileName;
        if(copy($filePath, $final_path_file)){
            // unlink($filePath);
            $data = array('upload_status' => 1, 'uploaded_path' => $folder_path.$fileName);
            return $data;
        }
        else{
            $error = array('upload_status' => 0, 'error' => 'something went wrong while coppying file.');
            return $error;
        }
        die();
        $config['upload_path'] = $pathname;
        $config['allowed_types'] = strlen($fileTypes) ? $fileTypes : 'JPG|JPEG|GIF|PNG|PDF|jpg|jpeg|gif|png|pdf';
        $config['max_size'] = 0;
        $config['encrypt_name'] = TRUE;
        $ci = &get_instance();
        $ci->load->library('upload', $config);
        if ($ci->upload->do_upload($filePath)) {
            $uploadRes = $ci->upload->data();
            $data = array('upload_status' => 1, 'uploaded_path' => $folder_path . $uploadRes["file_name"]);
            return $data;
        } else {
            $error = array('upload_status' => 0, 'error' => $ci->upload->display_errors());
            return $error;
        } //End of if else
    } //End of cifileuploaddigilocker()
}//End of if