<?php
defined("BASEPATH") or exit("No direct script access allowed");
if (!function_exists("fileupload")) {
    function fileUpload($field_name = NULL)
    {
        if(empty($_FILES[$field_name])){
            pre('<!DOCTYPE html>
                    <html>
                        <head>
	                        <title>403 Forbidden</title>
                        </head>
                        <body>
                            <p style="text-align: center">Directory access is forbidden.</p>
                        </body>
                    </html>');
        }
        $files = $_FILES;
        $errors = [];
        $config_pre=[];
        $cpt = count($_FILES[$field_name]['name']);
        $pathname = FCPATH . 'storage/temps/';
        $destination = $pathname;
        $ci = &get_instance();
        $config['upload_path'] = $destination;
        $config['allowed_types'] = 'JPG|JPEG|GIF|PNG|PDF|jpg|jpeg|gif|png|pdf';
        $config['max_size'] = 0;
        $config['encrypt_name'] = TRUE;
        $ci->load->library('upload', $config);
        for ($i = 0; $i < $cpt; $i++) {
            $name = time() . $files[$field_name]['name'][$i];
            $_FILES['file_to_be_uploaded_new']['name'] = $name;
            $_FILES['file_to_be_uploaded_new']['type'] = $files[$field_name]['type'][$i];
            //echo $_FILES['file_to_be_uploaded_new']['type'];
            $_FILES['file_to_be_uploaded_new']['tmp_name'] = $files[$field_name]['tmp_name'][$i];
            $_FILES['file_to_be_uploaded_new']['error'] = $files[$field_name]['error'][$i];
            $_FILES['file_to_be_uploaded_new']['size'] = $files[$field_name]['size'][$i];
            if (!($ci->upload->do_upload('file_to_be_uploaded_new')) || $files[$field_name]['error'][$i] != 0) {
                print_r($ci->upload->display_errors());
                $errors[] = $files[$field_name]['name'][$i];
                $preview[]="";
            } else {
                $data = $ci->upload->data();
                $newFileUrl = base_url("storage/temps/") . $data["file_name"];
                $preview[] = '<embed src="' . $newFileUrl . '" type="' . $data["file_type"] . '"  height="100%" width="100%">
                ';
                $deleteurl = base_url("upload/deletefile/") . $data["file_name"] . '/' . $field_name;
                $config_pre[] = [
                    'key' => $data["file_name"],
                    'caption' => $data["orig_name"],
                    'size' => $data["file_size"],    //
                    'downloadUrl' => $newFileUrl, // the url to download the file
                    'url' => $deleteurl, // server api to delete the file based on key
                ];
                if ($ci->session->userdata($field_name)) {
                    $tempArray = $ci->session->userdata($field_name);
                    array_push($tempArray, $data["file_name"]);
                    $ci->session->set_userdata($field_name, $tempArray);
                } else {
                    $tempArray = array($data["file_name"]);
                    $ci->session->set_userdata($field_name, $tempArray);
                }
            }
            $out = ['initialPreview' => $preview, 'initialPreviewConfig' => $config_pre, 'append' => true, 'initialPreviewAsData' => false];
            if (!empty($errors)) {
                $img = count($errors) === 1 ? 'file "' . $errors[0]  . '" ' : 'files: "' . implode('", "', $errors) . '" ';
                $out['error'] = 'Oh snap! We could not upload the ' . $img . 'now. Please try again later.';
            }
            return $ci->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($out));
        }
    }
}
if (!function_exists("moveFile")) {
    function moveFile($folder, $sessionfilename = NULL)
    {
        $folders = array(
            0 => "DOCUMENTS",
            1=>"PORTAL"
        );
        $ci = &get_instance();
        $folder_path = 'storage/' . $folders[$folder] . '/' . date("Y") . '/' . date("m") . '/' . date("d") . '/';
        $pathname = FCPATH . $folder_path;
        if (!is_dir($pathname)) {
            mkdir($pathname, 0777, true);
            file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
        }
        $session_files = $ci->session->userdata($sessionfilename);
        if ($session_files != NULL) {
            $finalUplodedFile = array();
            foreach ($session_files as $key => $file) {
                $tempfile = FCPATH . 'storage/temps/' . $file;
                if (file_exists($tempfile)) {
                    $newfile = $pathname . $file;
                    if (rename($tempfile, $newfile)) {
                        $temporary_file = $folder_path . $file;
                        array_push($finalUplodedFile, $temporary_file);
                        //unlink($tempfile);
                    }
                }
            }
            $ci->session->unset_userdata($sessionfilename);
            return $finalUplodedFile;
        }else{        
            return false;
        } //End of if else
    }
}
