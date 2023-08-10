<?php defined('BASEPATH') or exit('No direct script access allowed');

class Digilocker_enclosure
{
    public $clientId = '00B44039';
    public $clientSecret = '3516318033467b492e67';
    public $redirectUri = 'http://localhost/rtps/digilocker/response';

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('digilocker/digilocker_model');
    }

    public function login_btn()
    {
        if ($this->CI->session->userdata('token_details')) {
            $session_generated_time = $this->CI->session->userdata('token_details')['session_gen_time'];
            $expire_time = $this->CI->session->userdata('token_details')['expires_in'];
            $curr_time = time();
            $time_diff = ($curr_time - $session_generated_time);
            if ($time_diff >= $expire_time) { ?>
                <button class="btn btn-sm  digilogin_btn pull-right" type="button" onclick="window.open('<?= $this->get_auth_code() ?>','popUpWindow','height=650,width=600,left=350,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">
                    <img src="<?= base_url('assets/iservices/images/digilocker.jpg') ?>" alt="" width="20px"> Link your digilocker
                </button>
            <?php } else { ?>
                <button class="btn btn-sm btn-info digilogin_btn pull-right" type="button"><i class="fa fa-lock"></i> Account linked successfully.</button>
            <?php }
            ?>
        <?php
        } else { ?>
            <button class="btn btn-sm  digilogin_btn pull-right" type="button" onclick="window.open('<?= $this->get_auth_code() ?>','popUpWindow','height=650,width=600,left=350,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">
                <img src="<?= base_url('assets/iservices/images/digilocker.jpg') ?>" alt="" width="20px"> Link your digilocker
            </button>
        <?php }
    }

    public function get_auth_code()
    {
        return 'https://digilocker.meripehchaan.gov.in/public/oauth2/1/authorize?response_type=code&client_id=' . $this->clientId . '&redirect_uri=' . $this->redirectUri . '&state=digilogin';
    }


    public function digilocker_fetch_btn($file_class)
    { ?>
        <p class="mt-2">
            <span class="text-success font-weight-bold <?= $file_class ?>_msg"></span>
            <button class="btn btn-sm  digilocker_fetch_doc" <?= ($this->CI->session->userdata('token_details')) ? '' : 'disabled' ?> type="button" onclick="<?= $this->fetch_btn($file_class) ?>"><img src="<?= base_url('assets/iservices/images/digilocker.jpg') ?>" alt="" width="20px"> Fetch from digilocker</button>
        </p>
<?php }

    public function fetch_btn($btn_id)
    {
        return "window.open('" . base_url('digilocker/get_files/' . base64_encode($btn_id)) . "','popUpWindow','height=650,width=600,left=350,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');";
    }

    public function save_digilocker_user()
    {
        if ($this->CI->session->userdata('token_details') && $this->CI->session->userdata('mobile')) {
            $user_data = array(
                "user_mobile" => $this->CI->session->userdata('mobile'),
                "digilocker_data" => $this->CI->session->userdata('token_details'),
                "createdDtm" => new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
            );
            $data = (array)$this->CI->mongo_db->where(array('user_mobile'=>$this->CI->session->userdata('mobile')))->get('digilocker_users');
            if(!$data){
                $this->CI->digilocker_model->insert($user_data);
            }
        }
        else{
            echo 'no session found';
        }
    }

    public function check_valid_token(){
        $session_generated_time = $this->CI->session->userdata('token_details')['session_gen_time'];
        $expire_time = $this->CI->session->userdata('token_details')['expires_in'];
        $curr_time = time();
        $time_diff = ($curr_time - $session_generated_time);
        if ($time_diff >= $expire_time) { 
            $res = 0;
        }
        else{
            $res = 1;
        }
        echo $res.'-'.$time_diff;
    }
}
