<?php
defined('BASEPATH') or exit('No direct script access allowed');

/** Generates and stores captcha to session and returns the result */
if (!function_exists('generate_n_store_captcha')) {
    function generate_n_store_captcha($word_length = 6)
    {
        $ci = get_instance();
/*         if($ci->session->has_userdata['captcha']){
            $captchaPath = FCPATH."storage/captcha/".$ci->session->userdata['captcha']['filename'];
        } */
        // $ci->load->library('sms');
        // $word = $ci->sms->generate_otp($word_length);

        $word = "";
        $numbers = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        for ($i = 0; $i < $word_length; $i++) {
            $word .= $numbers[rand(0, strlen($numbers) - 1)];
        }
        

        $vals = array(
            'word' => $word,
            'img_path' => FCPATH.'/storage/captcha/',
            'img_url' => base_url('storage/captcha'),
            //'font_path' => './path/to/fonts/texb.ttf',
            'img_width' => '150',
            'img_height' => 30,
            'expiration' => 7200,
            'word_length' => $word_length,
            'font_size' => 30,
            'img_id' => 'captchaImgId',

            // White background and border, black text and red grid
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(250, 182, 204)
            )
        );

        if (isset($captchaPath) && file_exists($captchaPath))
            unlink($captchaPath);

        $cap = create_captcha($vals);
        $ci->session->set_userdata('captcha', $cap);
        return $cap;
    }
}

/** Validate Captcha */
if (!function_exists('validate_captcha')) {
    function validate_captcha()
    {
        $ci = get_instance();
        if ($ci->session->has_userdata('captcha')) {
            $captchaPath = FCPATH . "storage/captcha/" . $ci->session->userdata['captcha']['filename'];
        }

        $captcha = strval($ci->input->post('captcha', true));
        if ($ci->session->userdata('captcha')['word'] != $captcha) {

            if (isset($captchaPath) && file_exists($captchaPath))
                unlink($captchaPath);

            if($ci->input->is_ajax_request()){
                $status["status"] = false;
                $status["error_msg"] = "Security code doesn't match.";
                return $status;
            }else{

                $ci->load->library('form_validation');
                $ci->form_validation->set_message('validate_captcha', 'Wrong captcha code!!!');
                return false;
            }
        }

        if(isset($captchaPath) && file_exists($captchaPath))
            unlink($captchaPath);

        if($ci->input->is_ajax_request()) {
            return ['status' => true];
        }else{
            return true;
        }

    }
}

/** Non ajax Validate Captcha */
//if (!function_exists('non_ajax_validate_captcha')) {
//    function non_ajax_validate_captcha()
//    {
//        $ci = get_instance();
//        if ($this->input->post('captcha') != $this->session->userdata['captcha']) {
//            $this->form_validation->set_message('validate_captcha', 'Wrong captcha code, hmm are you the Terminator?');
//            return false;
//        } else {
//            return true;
//        }
//
//    }
//}