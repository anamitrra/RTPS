<?php
defined("BASEPATH") or exit("No direct script access allowed");
if(!function_exists("cifileupload")) {
    function cifileupload($fieldName, $fileTypes=null, $folder="docs/") {
        $folder_path = 'storage/' . $folder. date("Y") . '/' . date("m") . '/' . date("d") . '/';
        $pathname = FCPATH . $folder_path;
        if (!is_dir($pathname)) {
            mkdir($pathname, 0777, true);
            file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
        }
        $config['upload_path'] = $pathname;
        $config['allowed_types'] = strlen($fileTypes)?$fileTypes:'JPG|JPEG|GIF|PNG|PDF|jpg|jpeg|gif|png|pdf';
        $config['max_size'] = 0;
        $config['encrypt_name'] = TRUE;
        $ci = &get_instance();
        $ci->load->library('upload', $config);
        if($ci->upload->do_upload($fieldName)) {
            $uploadRes = $ci->upload->data();
            $data = array('upload_status' => 1, 'uploaded_path' => $folder_path.$uploadRes["file_name"]);
            return $data;
        } else {
            $error = array('upload_status' => 0, 'error' => $ci->upload->display_errors());
            return $error;
        }//End of if else
    }//End of cifileupload()
}//End of if


if(!function_exists("cifiledownload")) {
    function cifiledownload($dbFilePath=null) {
        //$filePath = strlen($dbFilePath)?FILE_UPLOAD_DIR.$dbFilePath:null;
        $filePath = strlen($dbFilePath)?$dbFilePath:null;
        if((is_file($filePath))&&(file_exists($filePath))){
            header("Cache-Control: no-cache");
            header("Expires: -1");
            header("Content-Type: application/octet-stream;");
            header("Content-Disposition: attachment; filename=\"" . basename($filePath) . "\";");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . filesize($filePath));
            echo file_get_contents($filePath);
        } else {
            echo "File does not exist";
        }//End of if else
    }//End of cifiledownload()
}//End of if

if(!function_exists("cifileupload_apdcl")) {
    function cifileupload_apdcl($fieldName, $fileTypes=null, $folder="docs/") {
        $folder_path = 'storage/' . $folder. date("Y") . '/' . date("m") . '/' . date("d") . '/';
        $pathname = FCPATH . $folder_path;
        $pathname = FCPATH . $folder_path; //Old path inside project directory
        if (!is_dir($pathname)) {
            mkdir($pathname, 0777, true);
            file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
        }
        $config['upload_path'] = $pathname;
        $config['allowed_types'] = strlen($fileTypes)?$fileTypes:'JPG|JPEG|PNG|PDF|jpg|jpeg|png|pdf';
        $config['max_size'] = 10485760;
        $config['encrypt_name'] = TRUE;
        $ci = &get_instance();
        $ci->load->library('upload', $config);
        if($ci->upload->do_upload($fieldName)) {
            $uploadRes = $ci->upload->data();
            $data = array('upload_status' => 1, 'uploaded_path' => $folder_path.$uploadRes["file_name"]);
            return $data;
        } else {
            $error = array('upload_status' => 0, 'error' => $ci->upload->display_errors());
            return $error;
        }//End of if else
    }//End of cifileupload()
}
