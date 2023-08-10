<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *@author Prasenjit Das
 */
class SMS
{

    /**
     * $collection
     *
     * @var undefined
     */
    private $collection;
    /**
     * Constructor
     */
    public function __construct()
    {
        log_message('debug', 'SMS Class Initialized');
        $this->collection = "sms";
        $this->CI = &get_instance();
        $this->CI->load->model('sms_model');
    }
    /**
     * send
     *
     * @param mixed $number
     * @param mixed $message_body
     * @return void
     */
    public function process_sms($number, $msgTemplate)
    {
        if ($number != "" && !empty($number) && $msgTemplate != "" && !empty($msgTemplate)) {
            $msisdn = intval($number);
            $msg = $msgTemplate['msg'];
            $dlt_template_id = $msgTemplate['dlt_template_id'];
            //$api_params = $api_element.'?apikey='.$apikey.'&sender='.$sender.'&to='.$mobileno.'&message='.$textmessage;
            $smsGatewayUrl = "https://smsgw.sms.gov.in/failsafe/MLink?username=rtpsasm.otp&pin=P4%26fO3%23xF4&message=" . $msg . "&mnumber=" . $msisdn . "&signature=ARTPSA&dlt_entity_id=1001367290000017171&dlt_template_id=" . $dlt_template_id;
            // $smsGatewayUrl = "http://103.8.249.55/smsgwam/form_/send_api_eodb_get.php?username=edbgov&password=edbdb@123&groupname=EDBGOV&to=" . $msisdn . "&msg=" . $msg . "";
            $url = $smsGatewayUrl;
            $ch = curl_init();                       // initialize CURL
            curl_setopt($ch, CURLOPT_POST, false);    // Set CURL Post Data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);                         // Close CURL
            //pre($output);
            // Use file get contents when CURL is not installed on server.
            if (!$output) {
                $output =  file_get_contents($smsgatewaydata);
                return $output;
            } else {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    // default $dlt_template_id is for otp
    public function process_sms_sending($number, $messageTemplate)
    {

        $message_body = $messageTemplate['msg'];
        if (!isset($messageTemplate['dlt_template_id'])) {
            $dlt_template_id = '1007161941911271647';
        } else {
            $dlt_template_id = $messageTemplate['dlt_template_id'];
        }
        // $number="91".$number;
        // $message_body = urlencode($message_body);
        //     $curl = curl_init();
        //     $smsGatewayUrl = "https://smsgw.sms.gov.in/failsafe/MLink?username=rtpsasm.otp&pin=P4%26fO3%23xF4&message=".$message_body."&mnumber=".$number."&signature=ARTPSA&dlt_entity_id=1001367290000017171&dlt_template_id=1007160707760375551";
        //    // pre($smsGatewayUrl);
        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL => $smsGatewayUrl,
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_SSL_VERIFYHOST=>false,
        //         CURLOPT_SSL_VERIFYPEER=>false,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'GET',
        //     ));

        //     $response = curl_exec($curl);
        //   //  var_dump(curl_getinfo($curl));die;
        //     // $error_msg = curl_error($curl);
        //     //var_dump($error_msg);die;
        //     curl_close($curl);
        //     if($response){
        //         return true;
        //     }else{
        //         return false;
        //     }

        $ch = curl_init();
        $message_body = str_replace(" ", "%20", $message_body);
        $url = "https://smsgw.sms.gov.in/failsafe/MLink?username=rtpsasm.otp&pin=P4%26fO3%23xF4&message=" . $message_body . "&mnumber=" . $number . "&signature=ARTPSA&dlt_entity_id=1001367290000017171&dlt_template_id=" . $dlt_template_id;
        // $url = "https://smsgw.sms.gov.in/failsafe/HttpLink?username=rurban.sms&pin=xxxxxxx&message=$message&mnumber=$mNumber&signature=RURBAN&dlt_entity_id=1001045918976813988";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        /*echo 'URL : '.$url.'<br>';
    echo 'Curl Error : '.curl_error($ch).'<br>';
    echo 'Curl Exec : '.$head.'<br>';
    echo 'HTTP Code : '.$httpCode.'<br>';*/
        curl_close($ch);
        //echo $httpCode;die;
        return  $head;
    }
    /**
     * generate_otp
     *
     * @param mixed $length
     * @return void
     */
    public function generate_otp($length = 6)
    {
        $otp = "";
        $numbers = "0123456789";
        for ($i = 0; $i < $length; $i++) {
            $otp .= $numbers[rand(0, strlen($numbers) - 1)];
        }
        return $otp;
    }
    /**
     * send
     *
     * @param mixed $number
     * @param mixed $message_body
     * @return void
     */
    public function send($mobile, $message_template)
    {   //pre($this->CI->config->item('sms_queue'));
        if (!is_array($message_template) || !in_array('dlt_template_id', array_keys($message_template)) || !in_array('msg', array_keys($message_template)))
            return true;
        //        if($message_template['dlt_template_id'] == 'NA')
        //        return true;
        if ($this->CI->config->item('sms_queue')) {
            if ($mobile != "" && !empty($mobile) && $message_template != "" && !empty($message_template)) {
                $temp = array(
                    "mobile" => $mobile,
                    "msg" => $message_template['msg'],
                    "dlt_template_id" => $message_template['dlt_template_id'],
                    "sending_status" => "pending",
                    "created_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
                    "status" => 0
                );
                $this->CI->sms_model->insert($temp);
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $this->process_sms($mobile, $message_template);
        }
    }

    /**
     * send_otp
     *
     * @param mixed $mobile
     * @param mixed $param
     * @param null[] $msgTemplate
     * @return void
     */
    public function send_otp($mobile, $param, $msgTemplate = ['msg' => NULL, 'dlt_template_id' => NULL])
    {
            //    $otp = $this->generate_otp();
        $otp = '123456';
        if ($msgTemplate['msg'] != NULL && $msgTemplate['dlt_template_id'] != NULL) {
            $msgTemplate['msg'] = str_replace("{{otp}}", $otp, $msgTemplate['msg']);
            //            $msg = urlencode($msg);
        } else {
            //             $msg = "Dear user, your OTP for EODB portal is $otp. OTP will expire in 10 minutes. On expiry of time please regenerate the OTP";
            $msgTemplate['msg'] = "Dear User, Your OTP for ARTPS portal is " . $otp;
            $msgTemplate['dlt_template_id'] = "1007161941911271647";

            //            $msg = "Your OTP for RTPS Portal is $otp";
            //            $msg = urlencode($msg);
        }
        //            $otp_msg="Dear User, Your OTP for ARTPS portal is ".$otp;
        //            $msg = str_replace(" ", "%20", $otp_msg);// added to sms sending process

        $this->process_sms_sending($mobile, $msgTemplate);
        // $this->sendSms($mobile, $msg);
        $temp = array(
            "param" => $param,
            "mobile" => $mobile,
            "otp" => $otp,
            "msg" => $msgTemplate['msg'],
            "sending_status" => "sent",
            "created_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            "verify_at" => NULL,
            "status" => 0
        );
        //pre($temp);
        $this->CI->sms_model->insert($temp);
    }


    // send generic OTP 
    public function send_generic_otp($mobile, $param, $data, $msgTemplate = ['msg' => NULL, 'dlt_template_id' => NULL])
    {
        if (strpos(base_url(), 'localhost')) {
            $otp = '123456'; // For testing
        } else {
            $otp = $this->generate_otp(); //For production
        } //End of if else

        if ($msgTemplate['msg'] != NULL && $msgTemplate['dlt_template_id'] != NULL) {
            $msgTemplate['msg'] = str_replace("{{otp}}", $otp, $msgTemplate['msg']);
            //            $msg = urlencode($msg);
        } else {
            //             $msg = "Dear user, your OTP for EODB portal is $otp. OTP will expire in 10 minutes. On expiry of time please regenerate the OTP";
            $msgTemplate['msg'] = "Dear " . $data['name'] . ", Your OTP for " . $data['service_name'] . " verification is " . $otp . ". Valid for the next " . $data['time_interval'] . " minutes. Team ARTPS";
            $msgTemplate['dlt_template_id'] = "1007166356826408606";
        }

        $this->process_sms_sending($mobile, $msgTemplate);
        // $this->sendSms($mobile, $msg);
        $temp = array(
            "param" => $param,
            "mobile" => $mobile,
            "otp" => $otp,
            "msg" => $msgTemplate['msg'],
            "sending_status" => "sent",
            "created_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            "verify_at" => NULL,
            "status" => 0
        );
        //pre($temp);
        $this->CI->sms_model->insert($temp);
    }


    /**
     * check_if_otp_time_expired
     *
     * @param mixed $param
     * @param mixed $mobile
     * @param mixed $otp
     * @return void
     */
    public function verify_otp($mobile, $param, $otp)
    {
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
            array(
                '$match' => [
                    '$and' => [
                        ['param' => ['$eq' => $param]],
                        ['mobile' => ['$eq' => $mobile]],
                        ['otp' => ['$eq' => $otp]],

                    ]
                ],
            ),
            array(
                '$sort' => [
                    'created_at' => -1
                ]
            ),
            array(
                '$project' => array(
                    'dateDifference'    => array("\$subtract" => array($current_time, "\$created_at")),
                    'mobile' => 1,
                ),
            )
        );
        //pre($operations);
        $data = $this->CI->mongo_db->aggregate("sms", $operations);
        $response = array();
        if (isset($data->{'0'}) && !empty($data)) {
            if ($data->{0}->dateDifference < 60000) {
                $update_data = array(
                    "verify_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
                    "status" => 1,
                );
                $this->CI->sms_model->update($data->{'0'}->{'_id'}->{'$id'}, $update_data);
                $response['status'] = true;
                return $response;
            } else {
                $response['status'] = false;
                $response['msg'] = 'Otp Time Expired';
                return $response;
            }
        } else {
            $response['status'] = false;
            $response['msg'] = 'Invalid OTP';
            return $response;
        }
    }

    /**
     * send_queue
     *
     * @return void
     */
    public function send_queue()
    {
        $filter = ["sending_status" => "pending"];
        $smses = $this->CI->sms_model->get_all($filter);
        $data = ["sending_status" => "sending", 'sending_date' => new MongoDB\BSON\UTCDateTime(microtime(true) * 1000)];
        $this->CI->sms_model->update_where($filter, $data);
        foreach ($smses as $sms) {
            $sms_debug = $this->process_sms($sms->mobile, $sms->msg);
            if ($sms_debug) {
                $status = 'sent';
                $data = ["sending_status" => $status, 'processed_date' => new MongoDB\BSON\UTCDateTime(microtime(true) * 1000)];
            } else {
                $status = 'failed';
                $data = ["sending_status" => $status, "error" => $sms_debug, 'processed_date' => new MongoDB\BSON\UTCDateTime(microtime(true) * 1000)];
            }

            $this->CI->sms_model->update($sms->{'_id'}->{'$id'}, $data);
        }
    }
    /**
     * retry_queue
     *
     * @return void
     */
    public function retry_queue()
    {
        $this->CI->sms_model->resend_failed_sms();
        log_message('debug', 'Sms queue retrying...');
    }

    public function sendSms($mobile, $msg)
    {

        $curl = curl_init();
        $mobile = "91" . $mobile;
        $smsGatewayUrl = "https://localhost/smsgateway/sendSms.php?number=" . $mobile . "&mgs=" . urlencode($msg) . "";
        //  pre($smsGatewayUrl);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $smsGatewayUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        //pre(  $response );
        return $response;
    }
}
