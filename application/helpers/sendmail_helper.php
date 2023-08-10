 <?php
//if (!defined("BASEPATH"))
//    exit("No direct script access allowed");

//if (!function_exists("sendmail")) {
//
//    function sendmail($to, $subject, $body, $attachment = NULL) {
//
//        if (ENVIRONMENT === "development") {
//            //$to = 'hiranya.sarma44.hs@gmail.com';
//            $status = offline($to, $subject, $body, $attachment);
//        } else {
//            $status = online($to, $subject, $body, $attachment);
//        }
//
//        return $status;
//    }

// End of sendmail
//} // End of if
//
//if (!function_exists("online")) {
//
//    function online($to, $subject, $body, $attachment = NULL, $frmName = "RTPS System Admin", $frmMail = "artpsnic@gmail.com") {
//        $ci = & get_instance();
//        $ci->load->library("email");
//        $ci->email->initialize(array(
//            "protocol" => "smtp",
//            "smtp_host" => "ssl://smtp.googlemail.com",
//            "smtp_user" => "artps.nic@gmail.com",
//            "smtp_pass" => "cmieyssbbasfytlv",
//            "smtp_port" => 465,
//            "mailtype" => "html",
//            "charset" => "iso-8859-1",
//            "wordwrap" => TRUE,
//            "crlf" => "\r\n",
//            "newline" => "\r\n"
//        ));
//        $ci->email->from($frmMail, $frmName);
//        $ci->email->to($to);
//        $ci->email->subject($subject);
//        $emailBody = "";
//        $emailBody .= $ci->load->view("email/header", '', TRUE);
//        $emailBody .= $body;
//        $emailBody .= $ci->load->view("email/footer", '', TRUE);
//
//        $ci->email->message($emailBody);
//        $ci->email->set_alt_message($body);
//        if (!is_null($attachment)) {
//            $ci->email->attach($attachment);
//        }
//        if ($ci->email->send()) {
//            return TRUE;
//        } else {
//            return $ci->email->print_debugger();
//        }
//    }
//
////End of online
//}//End of if
//
//if (!function_exists("offline")) {
//
//    function offline($to, $subject, $body, $attachment = NULL, $frmName = "RTPS System Admin", $frmMail = "artpsnic@gmail.com") {
//        $ci = & get_instance();
//        $ci->load->library("email");
//        $ci->email->initialize(array(
//            "protocol" => "smtp",
//            "smtp_host" => "ssl://smtp.googlemail.com",
//            "smtp_user" => "artps.nic@gmail.com",
//            "smtp_pass" => "cmieyssbbasfytlv",
//            "smtp_port" => 465,
//            "mailtype" => "html",
//            "charset" => "iso-8859-1",
//            "wordwrap" => TRUE,
//            "crlf" => "\r\n",
//            "newline" => "\r\n"
//        ));
//        $ci->email->from($frmMail, $frmName);
//        $ci->email->to($to);
//        $ci->email->subject($subject);
//        $emailBody = "";
//        $emailBody .= $ci->load->view("email/header", '', TRUE);
//        $emailBody .= $body;
//        $emailBody .= $ci->load->view("email/footer", '', TRUE);
//
//        $ci->email->message($emailBody);
//        $ci->email->set_alt_message($body);
//        if (!is_null($attachment)) {
//            $ci->email->attach($attachment);
//        }
//        if ($ci->email->send()) {
//            return TRUE;
//        } else {
//            return $ci->email->print_debugger();
//        }
//    }

//End of offline
//}//End of if