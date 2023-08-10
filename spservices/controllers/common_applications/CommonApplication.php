<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class CommonApplication extends Frontend
{
    public function __construct()
    {
        parent::__construct();
    } //End of __construct()

    public function index($encoded_url)
    {
        $decodedUrl = $this->base64url_decode($encoded_url);
        $parsed_url = (parse_url($decodedUrl));
        $request_host = $parsed_url['host'] ?? '';
        $host_list = ['rtps.assam.gov.in', 'rtps.assam.statedatacenter.in', 'sewasetu.assam.gov.in'];
        if (in_array($request_host, $host_list)) {
            if (strpos($decodedUrl, 'directApply.do?serviceId=0000') !== false) {
                $host = base_url();
                $redirect_url = $host . 'services/loginWindow.do?servApply=N&&lt;csrf:token%20uri=%27loginWindow.do%27/&gt;';
                $this->session->set_userdata("redirectTo", $redirect_url);
            }
            else if (strpos($decodedUrl, 'directApply.do?serviceId') !== false) {
                $this->session->set_userdata("applyIn", 'servicePlus');
            } else {
                $this->session->set_userdata("applyIn", 'spServices');
            }
            $this->session->set_userdata("redirectTo", $decodedUrl);
            redirect('iservices');
        } else if (strpos($parsed_url['path'], 'localhost/rtps') !== false) {
            $this->session->set_userdata("redirectTo", $decodedUrl);
            redirect('iservices');
        } else {
            $this->session->unset_userdata('applyIn');
            redirect($decodedUrl);
        }
    }

    function base64url_decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}