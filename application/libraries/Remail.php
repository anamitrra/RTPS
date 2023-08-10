<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *@author Prasenjit Das
 */
class Remail extends CI_Email
{

    /**
     * Constructor
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        log_message('debug', 'Email Queue Class Initialized');
        $this->expiration = 60 * 5;
        $this->CI = &get_instance();
        $this->CI->load->model('email_model');
        if (ENVIRONMENT === "development") {
            $this->initialize(array(
                "protocol" => "smtp",
                "smtp_host" => "ssl://smtp.googlemail.com",
                "smtp_user" => "artps.nic@gmail.com",
                "smtp_pass" => "ARTPS@2021", // old : hsyjhanvikcykjyb
                "smtp_port" => 465,
                "mailtype" => "html",
                "charset" => "iso-8859-1",
                "wordwrap" => TRUE,
                "crlf" => "\r\n",
                "newline" => "\r\n",
                "validate" => TRUE
            ));
        } else {
            $this->initialize(array(
                "protocol" => "smtp",
                "smtp_host" => "ssl://smtp.googlemail.com",
                "smtp_user" => "artps.nic@gmail.com",
                "smtp_pass" => "ARTPS@2021", // old : hsyjhanvikcykjyb
                "smtp_port" => 465,
                "mailtype" => "html",
                "charset" => "iso-8859-1",
                "wordwrap" => TRUE,
                "crlf" => "\r\n",
                "newline" => "\r\n",
                "validate" => TRUE
            ));
        }
    }
    /**
     * Get
     *
     * Get queue emails.
     * @return  mixed
     */
    public function get($status)
    {
        $filter = ["status" => $status];
        if ($status != FALSE && $status != "")
            return $this->CI->email_model->get_all($filter);
    }
    /**
     * sendmail
     *
     * @param mixed $to
     * @param mixed $subject
     * @param mixed $body
     * @param mixed $attachment
     * @return void
     */
    public function process_email($to, $cc, $bcc, $subject, $body, $attachment = NULL, $frmName = "RTPS System Admin", $frmMail = "artpsnic@gmail.com")
    {
        $this->clear(TRUE);
        $this->from($frmMail, $frmName);
        $this->to($to);
        $this->subject($subject);
        $emailBody = "";
        $emailBody .= $this->CI->load->view("email/header", '', TRUE);
        $emailBody .= $body;
        $emailBody .= $this->CI->load->view("email/footer", '', TRUE);
        $this->message($emailBody);
        $this->set_alt_message($body);
        if (!is_null($attachment)) {            
            $this->attach($attachment);
        }
        if ($this->send()) {
            return 1;
        } else {
            return $this->print_debugger();
        }
    }
    /**
     * Send
     *
     * Add queue email to database.
     * @return  mixed
     */
    public function sendemail($to, $subject, $body, $attachment = NULL, $cc = NULL, $bcc = NULL)
    {
        if($this->CI->config->item('email_queue')){
            if ($to != "" && $subject != "" && $body != "") {
                $to = is_array($to) ? implode(", ", $to) : $to;
                $cc = is_array($cc) ? implode(", ", $cc) : $cc;
                $bcc = is_array($bcc) ? implode(", ", $bcc) : $bcc;
                $dbdata = array(
                    'to' => $to,
                    'cc' => $cc,
                    'bcc' => $bcc,
                    'subject' => $subject,
                    'message' => $body,
                    'attachment' => $attachment,
                    'status' => 'pending',
                    'date' => new MongoDB\BSON\UTCDateTime(microtime(true) * 1000)
                );
                return $this->CI->email_model->insert($dbdata);
            } else {
                return false;
            }
           
        }else{
            $this->process_email($to, $cc, $bcc, $subject, $body, $attachment);
        }

    }
    /**
     * Send queue
     *
     * Send queue emails.
     * @return  void
     */
    public function send_queue()
    {
        $emails = $this->get("pending");
        //pre($emails);
        $filter = ["status" => "pending"];
        $data = ["status" => "sending", 'sending_date' => new MongoDB\BSON\UTCDateTime(microtime(true) * 1000)];
        $this->CI->email_model->update_where($filter, $data);
        foreach ($emails as $email) {
            $recipients = explode(", ", $email->to);
            $cc = !empty($email->cc) ? explode(", ", $email->cc) : array();
            $bcc = !empty($email->bcc) ? explode(", ", $email->bcc) : array();
            $email_debug = $this->process_email($recipients, $cc, $bcc, $email->subject, $email->message, $email->attachment);
            if ($email_debug == 1) {
                $status = 'sent';
                $data = ["status" => $status, 'processed_date' => new MongoDB\BSON\UTCDateTime(microtime(true) * 1000)];
            } else {
                $status = 'failed';
                $data = ["status" => $status, "error" => $email_debug, 'processed_date' => new MongoDB\BSON\UTCDateTime(microtime(true) * 1000)];
            }

            $this->CI->email_model->update($email->{'_id'}->{'$id'}, $data);
        }
    }
    /**
     * Retry failed emails
     *
     * Resend failed or expired emails
     * @return void
     */
    public function retry_queue()
    {
        $this->CI->email_model->resend_failed_emails();
        log_message('debug', 'Email queue retrying...');
    }
}
