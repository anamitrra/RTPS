<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if(!function_exists('generate_n_send_mail_with_ack_attachment')){

    function generate_n_send_mail_with_ack_attachment($appeal_id,$appeal_type=null){
        $CI =& get_instance();
        $CI->load->model("ams_model");
        $appealApplication = $CI->ams_model->get_with_related_by_appeal_id($appeal_id);
        if(empty($appealApplication)){
            $appealApplication = $CI->appeal_application_model->get_with_related_by_appeal_id_no_application_data($appeal_id);
        }
        if($appeal_type === "second"){
          $emailBody=$CI->load->view('email/second_appeal_ack',array('appealApplication'=>$appealApplication),true);
          $templateFileName='second_appeal_acknowledgement';
        }else {
          $emailBody=$CI->load->view('email/first_appeal_ack',array('appealApplication'=>$appealApplication),true);
          $templateFileName='first_appeal_acknowledgement';
        }
           // echo $emailBody;die;
        if(isset($appealApplication[0]->email_id)){
            $email[] = $appealApplication[0]->email_id;
        }
        if($appealApplication[0]->contact_in_addition_email && isset($appealApplication[0]->additional_email_id)){
            $email[] = $appealApplication[0]->additional_email_id;
        }
        if(!empty($email)){
            $email = implode(',',$email);
    //        $email="prasn2009@gmail.com,prasenjit.89@supportgov.in";
            $CI->load->library('pdf');
            $file=$CI->pdf->save($emailBody,$templateFileName);

            $CI->remail->sendemail($email, "Appeal Acknowledgment", $emailBody,$file);
        }
    }
}
