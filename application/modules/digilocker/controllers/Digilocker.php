<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Digilocker extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('digilocker_model');
        $this->load->library('Digilocker_API');
        $this->load->config('dlconfig');
    }

    public function index()
    {
        $this->load->view('get_auth_code');
    }

    public function userConsent()
    {
        $data['url'] = $this->digilocker_api->get_auth_code('consent');
        $checkDigilockerConsent = $this->digilocker_model->check_consent($this->session->userdata('mobile'));
        if (strlen($checkDigilockerConsent)) {
            // echo 'exist. 0 or 1';
            if ($checkDigilockerConsent == 1) {
                // echo 'consent 1 given ';
                if (!empty($this->session->userdata('redirectTo'))) {
                    redirect($this->session->userdata('redirectTo'));
                    $this->session->unset_userdata('redirectTo');
                } else {
                    redirect(base_url('iservices/transactions'));
                }
            } else {
                // echo 'consent 0 given';
                $this->load->view('digilocker_consent', $data);
            }
        } else {
            // echo 'not exist. need user consent';
            $this->load->view('digilocker_consent', $data);
        }
    }

    public function cancel_consent()
    {
        $this->digilocker_model->save_digilocker_consent(0);
        if (!empty($this->session->userdata('redirectTo'))) {
            redirect($this->session->userdata('redirectTo'));
            $this->session->unset_userdata('redirectTo');
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }

    public function response()
    {
        $data = [];
        $data['status'] = false;
        if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY)) {
            $url = parse_url($_SERVER['REQUEST_URI']);
            parse_str($url['query'], $params);
            if (isset($params['error'])) {
                redirect(base_url('digilocker/userConsent'));
            } else {
                $authorization_code = $params['code'];
                $data['state'] = $params['state'];
                $token_data = json_decode($this->get_token($authorization_code));
                if (property_exists($token_data, 'error')) {
                    echo 'Something went wrong. Please try after some time. <a href="' . base_url('digilocker/consent') . '">Click to go back</a>';
                } else {
                    $to_add_session = (array)$token_data;
                    $to_add_session['session_gen_time'] = time();
                    $this->session->set_userdata('token_details', (array)$to_add_session);

                    // saveDigilockerConsent
                    $this->digilocker_model->save_digilocker_consent(1);
                    $data['status'] = true;
                }
            }
        }
        $this->load->view('response', $data);
    }

    public function response_test()
    {
        $data = [];
        $data['status'] = false;
        $authorization_code = 'a1be3f8a1fc9e7ce7ec412e63b365fa2f75b89de';
        $data['state'] = 'consent';
        $token_data = json_decode($this->get_token($authorization_code));
        if (property_exists($token_data, 'error')) {
            echo 'Something went wrong. Please try after some time. <a href="' . base_url('digilocker/consent') . '">Click to go back</a>';
        } else {
            $to_add_session = (array)$token_data;
            $to_add_session['session_gen_time'] = time();
            $this->session->set_userdata('token_details', (array)$to_add_session);

            // saveDigilockerConsent
            $this->digilocker_model->save_digilocker_consent(1);
            $data['status'] = true;
        }
        $this->load->view('response', $data);

    }

    public function test($i)
    {
        $issued_files = ($this->get_issued_files());
        pre($issued_files);
        // $x = $this->digilocker_model->save_digilocker_consent($i);
        // print_r($checkDigilockerId);
        // pre($x);
        // // $this->mongo_db->switch_db("iservices");
        // pre($this->session->userdata());
        // $checkDigilockerId = $this->digilocker_model->check_consent($this->session->userdata('mobile'));
        // pre($checkDigilockerId);
        $y = $this->mongo_db->where(array('mobile' => $this->session->userdata('mobile')))->get('front_users');
        pre($y);
    }
    public function get_token($auth_code)
    {
        $client_id = $this->config->item('clientId');
        $client_secret = $this->config->item('clientSecret');
        $redirect_uri = $this->config->item('redirectUri');
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded'
        );
        $payload = "code=" . $auth_code . "&grant_type=authorization_code&client_id=" . $client_id . "&client_secret=" . $client_secret . "&redirect_uri=" . $redirect_uri;
        $curl = curl_init('https://digilocker.meripehchaan.gov.in/public/oauth2/1/token');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $return =  curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            pre($error_msg);
        }
        curl_close($curl);
        return $return;
    }


    public function get_files($id = '')
    {
        $data['status'] = false;
        $data['token_expire'] = false;
        if (strlen($id) && ($this->session->userdata('token_details'))) {
            $data['fileId'] = $id;
            $session_generated_time = $this->session->userdata('token_details')['session_gen_time'];
            $expire_time = $this->session->userdata('token_details')['expires_in'];
            $curr_time = time();
            $time_diff = ($curr_time - $session_generated_time);
            if ($time_diff >= $expire_time) {
                // echo 'expired';
                $data['token_expire'] = true;
                $data['status'] = false;
            } else {
                // echo 'not expire';
                $issued_files = json_decode($this->get_issued_files());
                $uploaded_files = json_decode($this->get_saved_files());

                $data['issued_files'] = $issued_files;
                $upload_files = array();
                $dir_files = array();
                $upload_files = array();
                foreach ($uploaded_files->items as $file) {
                    if ($file->type == 'dir') {
                        $dir_files[] = json_decode($this->get_saved_files($file->id));
                    } else {
                        $upload_files[] = $file;
                    }
                }
                $data['upload_files'] = $upload_files;
                $data['dir_files'] = $dir_files;
                $data['status'] = true;
            }
        }
        // pre($this->session->userdata());
        $this->load->view('document_list', $data);
    }

    public function get_issued_files()
    {
        $access_token = $this->session->userdata('token_details')['access_token'];
        $headers = "Authorization: Bearer " . $access_token;
        $url = curl_init('https://digilocker.meripehchaan.gov.in/public/oauth2/2/files/issued');
        curl_setopt($url, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $headers));
        curl_setopt($url, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($url);
        if (curl_error($url)) {
            return curl_error($url);
        }
        curl_close($url);
        return $return;
    }

    public function get_saved_files($id = '')
    {
        $access_token = $this->session->userdata('token_details')['access_token'];
        $headers = "Authorization: Bearer " . $access_token;
        $url = curl_init('https://digilocker.meripehchaan.gov.in/public/oauth2/1/files/' . $id);
        curl_setopt($url, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $headers));
        curl_setopt($url, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($url);
        curl_close($url);
        return $return;
    }

    public function get_document()
    {
        $service = '';
        $prefix = ($this->session->userdata('mobile')) ? $this->session->userdata('mobile') : '123';
        if (strlen($prefix)) {
            $access_token = $this->session->userdata('token_details')['access_token'];
            $file_uri = $this->input->post('file_uri');
            $file_name = trim($this->input->post('file_name'));
            $headers = "Authorization: Bearer " . $access_token;
            $url = curl_init('https://digilocker.meripehchaan.gov.in/public/oauth2/1/file/' . $file_uri);
            curl_setopt($url, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $headers));
            curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($url);
            $contentType = curl_getinfo($url, CURLINFO_CONTENT_TYPE);
            $file_type = explode('/', $contentType)[1];
            curl_close($url);
            $folder_path = 'storage/docs/digilockerDocs/' . $service . date("Y") . '/' . date("m") . '/' . date("d") . '/';
            $pathname = FCPATH . $folder_path;
            if (!is_dir($pathname)) {
                mkdir($pathname, 0777, true);
            }
            $get_random = $this->generateRandomString();
            if ($file_type == 'pdf' || $file_type == 'PDF') {
                $document_name = $prefix . '_' . $get_random . '.' . $file_type;
                $file = fopen($pathname . $document_name, "w");
                $path = $folder_path . $document_name;
                $res = array();
                if ($file != false) {
                    fwrite($file, $response);
                    fclose($file);
                    $res['status'] = 'success';
                    $res['file_path'] = $path;
                    $res['format'] = false;
                } else {
                    $res['status'] = 'error';
                }
            } else {
                $res['format'] = false;
                $res['status'] = 'error';
            }
        } else {
            $res['status'] = 'error';
        }
        echo json_encode($res);
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function refresh_token()
    {
        print_r($this->session->userdata());
        $client_id = $this->clientId;
        $client_secret = $this->clientSecret;
        $refToken = $this->session->userdata('token_details')['refresh_token'];

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . base64_encode($client_id . ":" . $client_secret)
        );
        $payload = "refresh_token=" . $refToken . "&grant_type=refresh_token";
        $url = curl_init('https://digilocker.meripehchaan.gov.in/public/oauth2/1/token');
        curl_setopt($url, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($url, CURLOPT_POST, 1);
        curl_setopt($url, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($url, CURLOPT_RETURNTRANSFER, TRUE);

        $return = curl_exec($url);
        curl_close($url);
        // $token = json_decode($return);
        // $to_add_session = (array)$token;
        // $to_add_session['session_gen_time'] = time();
        // $this->session->set_userdata('token_details', (array)$to_add_session);
        // $this->session->set_userdata('token_details', (array)$token);
        pre($return);
    }

    public function saveDigilockerId()
    {
        pre($this->digilocker_api->pushUriDigilocker('9101379463', 'RTPS-INC/2022/6603790'));
    }
}
