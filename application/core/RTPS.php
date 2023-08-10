<?php
class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }
}
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
        $input_data['session_id'] = $this->session->userdata('userId')->{'$id'};
        $input_data['request_uri'] = $this->input->server('REQUEST_URI');
        $input_data['timestamp'] = date("Y-m-d H:i:s");
        $input_data['client_ip'] = $this->input->server('REMOTE_ADDR');
        $input_data['client_user_agent'] = $this->agent->agent_string();
        $input_data['referer_page'] = $this->agent->referrer();
        $this->mongo_db->insert("user_logs", $input_data);
        //echo '<pre>';print_r($input_data);die;
    }
    public function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        if (!isset($isLoggedIn) || $isLoggedIn != true) {
            redirect('login/logout');
        }
    }
}
class frontend extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
}
