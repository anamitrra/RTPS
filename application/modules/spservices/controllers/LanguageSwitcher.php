<?php
if (!defined('BASEPATH')) exit('Direct access allowed');
class LanguageSwitcher extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }//End of __construct()

    function switchLang($language = "") {
        $language = ($language != "") ? $language : "english";
        $this->session->set_userdata('mcc_lang', $language);
        redirect($_SERVER['HTTP_REFERER']);
    }//End of switchLang()
}//End of LanguageSwitcher
