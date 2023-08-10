<?php
class LanguageLoader {
    function initialize() {
        $ci = & get_instance();
        $ci->load->helper('language');
        $siteLang = $ci->session->userdata('mcc_lang');
        if ($siteLang) {
            $ci->lang->load('mcc_registration', $siteLang);
        } else {
            $ci->lang->load('mcc_registration', 'english');
        }
    }
}
