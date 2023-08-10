<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rtps extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->isLoggedIn();
        $this->load->helper("role");
        $this->load->library('user_agent');
        $input_data = array();
        $input_data['session_id'] = isset($this->session->userdata('userId')->{'$id'}) ? $this->session->userdata('userId')->{'$id'} : $this->session->userdata('userId');
        $input_data['request_uri'] = $this->input->server('REQUEST_URI');
        $input_data['timestamp'] = date("Y-m-d H:i:s");
        // $input_data['client_ip'] = $this->input->server('REMOTE_ADDR');
        $input_data['client_user_agent'] = $this->agent->agent_string();
        $input_data['referer_page'] = $this->agent->referrer();

        // Modified for logging activities on Site's admin panel
        $input_data['mongo_date'] = new MongoDB\BSON\UTCDateTime(strtotime("now") * 1000);
        $input_data['client_ip'] =  getallheaders()['X-Forwarded-For'] ?? $this->input->server('REMOTE_ADDR');
        $input_data['role'] =  $this->session->userdata('designation') ?? 'System Administrator';

        $this->mongo_db->insert("user_logs", $input_data);
        //echo '<pre>';print_r($input_data);die;
    }
    public function isLoggedIn()
    {

        // $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "https") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        // $isLoggedIn = $this->session->userdata('isLoggedIn');
        // $redirect_data = $this->checkRedirection($url);
        // if ($redirect_data['need_login']) {
        //     if (!isset($isLoggedIn) || $isLoggedIn != true) {
        //         if (!$this->input->is_ajax_request()) {
        //             $this->session->set_userdata("redirectTo", $redirect_data['redirectUrl']);
        //         }
        //         $module = $this->uri->segment(1, 0);
        //         if ($module === "iservices" || $module === "spservices") {
        //             redirect('iservices/login');
        //             // redirect('iservices/ssologin');

        //         } else {
        //             redirect(base_url($module . '/login/logout'));
        //         }
        //     }
        // } else {
        //     // echo 'no need login';
        //     redirect($redirect_data['redirectUrl']);
        // }



        // previous data 
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "https") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        if (!isset($isLoggedIn) || $isLoggedIn != true) {
            if (!$this->input->is_ajax_request()) {
                $this->session->set_userdata("redirectTo", $url);
            }
            $module = $this->uri->segment(1, 0);
            if ($module === "iservices" || $module === "spservices") {
                redirect('iservices/login');
                // redirect('iservices/citizenlogin');
            } else {
                redirect(base_url($module . '/login/logout'));
            }
        }
    }

    private function checkRedirection($url)
    {
        $currentUrl = $url;
        $redirect_url = '';
        $need_login = false;
        // echo '<br>';
        if (strpos($currentUrl, 'commonapplication') !== false) {
            // need to redirect epramaan and do some checking 
            $token = "service/";
            $result = "";
            $index = strpos($currentUrl, $token);
            if ($index !== false) {
                $result = substr($currentUrl, $index + strlen($token));
            }
            $decodedUrl = $this->base64url_decode($result);
            $parsed_url = (parse_url($decodedUrl));

            $host_list = ['rtps.assam.gov.in', 'rtps.assam.statedatacenter.in', 'sewasetu.assam.gov.in'];

            if (in_array($parsed_url['host'], $host_list)) {
                if (strpos($decodedUrl, 'directApply.do?serviceId=0000') !== false) {
                    $host = base_url();  // sewasetu, rtps
                    $redirect_url = $host . 'services/loginWindow.do?servApply=N&&lt;csrf:token%20uri=%27loginWindow.do%27/&gt;';
                }
                $redirect_url = $decodedUrl;
                $need_login = true;

                // redirect('iservices/login');
            } else {
                // echo 'no need to login. redirect externally';
                // echo '<br>';
                $redirect_url = $decodedUrl;
                $need_login = false;

                // redirect('iservices/login');

                // redirect($decodedUrl);
                // echo $url;
            }
        } else {
            // no need to any check, we can redirect directly
            // $common_application = false;
            // redirect($currentUrl);
            $redirect_url = $currentUrl;
            $need_login = false;
        }

        return array('redirectUrl' => $redirect_url, 'need_login' => $need_login);
    }
    function base64url_decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
