<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Upload extends frontend
{
    function index()
    {
        
        $this->load->helper("fileupload");
        $file_name=$this->input->post("filename");
        fileUpload($file_name);
    } //End of index()

    function test()
    {
        $field_name = 'file_to_be_uploaded';
        $data = $this->session->userdata($field_name);
        pre($data);
    } //End of index()
    public function test2()
    {
        $this->load->helper("fileupload");
        pre(moveFile(0,"file_to_be_uploaded"));
    }
    public function deletefile($file,$field_name)
    {
        $file_array = $this->session->userdata($field_name);
        if ($file != "*") {
            $pathname = FCPATH . 'storage/temps/' . $file . '';
            if (file_exists($pathname)) {
                if (unlink($pathname)) {
                    if (($key = array_search($file, $file_array)) !== false) {
                        unset($file_array[$key]);
                    }
                    $this->session->set_userdata($field_name,array_values($file_array));
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array("status"=>true)));
                }else{
                    return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array("status"=>false)));
                }
            }
        }
    }

    public function getfile()
    {
        //print_r($_GET);die;
        if (!empty($_GET['req'])) {
            // check if user is logged
            if (!empty($this->session->userdata("is_loggedin"))) {
                $url = $_GET['req'];
                $ptype = 1; // tracking the type of file is being requested
                if (strpos($url, 'report_problem') !== false) {
                    $pdf_name = md5(time()) . '.png';
                    $ptype = 2;
                } elseif (strpos($url, 'Signature') !== false) {
                    $filename = "signature.zip";
                    $ptype = 3;
                } else {
                    $pdf_name = md5(time()) . '.pdf';
                }
                $pdf_file = $_SERVER['DOCUMENT_ROOT'] . $url;
                if (file_exists($pdf_file)) {
                    if ($ptype == 2) {
                        header('Content-Type: image/png');
                        echo file_get_contents($pdf_file);
                    } elseif ($ptype == 3) {
                        //echo $filename.'<br> '.$pdf_file; die;
                        header("Pragma: public");
                        header("Expires: 0");
                        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                        header("Cache-Control: public");
                        header("Content-Description: File Transfer");
                        header("Content-type: application/octet-stream");
                        header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
                        header("Content-Transfer-Encoding: binary");
                        header("Content-Length: " . filesize($pdf_file));
                        ob_end_flush();
                        @readfile($pdf_file);
                    } else {
                        header('Content-Type: application/pdf');
                        echo file_get_contents($pdf_file);
                    }
                    //echo file_get_contents($pdf_file);
                } else {
                    redirect(base_url());
                }
            } else {
                redirect(base_url());
            }
        }
    }
}//End of Upload
