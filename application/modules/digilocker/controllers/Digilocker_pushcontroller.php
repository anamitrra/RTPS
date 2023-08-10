<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Digilocker_pushcontroller extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Digilocker_API');
    }

    public function index()
    {
        echo 'I am from digilocker push controller.';
    }

    public function saveDigilockerId()
    {
        pre($this->digilocker_api->pushUriDigilocker('9101379463', 'RTPS-INC/2022/6603790'));
    }
}
