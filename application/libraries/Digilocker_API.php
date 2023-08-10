<?php defined('BASEPATH') or exit('No direct script access allowed');
ini_set('MAX_EXECUTION_TIME', -1);
class Digilocker_API
{
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('digilocker/digilocker_model');
        $this->CI->load->config('digilocker/dlconfig');
    }

    public function consent_btn()
    {
        echo "<button class='btn btn-sm  btn-primary' onclick='window.open('" . $this->get_auth_code('consent') . "')'>
            Allow
        </button>";
    }

    public function login_btn()
    {
        $check_token = $this->check_valid_token();
        // pre($check_token);
        if ($check_token == 0) {
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $url = "https://";
            else
                $url = "http://";
            $url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $this->CI->session->set_userdata("enclosure_redirection_path", $url);
?>
            <button class="btn btn-sm  digilogin_btn btn-primary" type="button" onclick="window.location.href='<?= $this->get_auth_code('digilocker_document_fetch') ?>'">
                <img src="<?= base_url('assets/iservices/images/digilocker.png') ?>" alt="" width="20px"> Link your digilocker
            </button>
            <!-- <button class="btn btn-sm  digilogin_btn pull-right" type="button" onclick="window.open('<?= $this->get_auth_code('digilocker_document_fetch') ?>','popUpWindow','height=650,width=600,left=350,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">
                <img src="<?= base_url('assets/iservices/images/digilocker.jpg') ?>" alt="" width="20px"> Link your digilocker
            </button> -->
        <?php } else if ($check_token == 1) {
        ?>
            <button class="btn btn-sm btn-success digilogin_btn" type="button"><i class="fa fa-lock"></i> Account linked successfully.</button>
        <?php } else {
            echo '';
        }
    }

    public function get_auth_code($state)
    {
        return 'https://digilocker.meripehchaan.gov.in/public/oauth2/1/authorize?response_type=code&client_id=' . $this->CI->config->item('clientId') . '&redirect_uri=' . $this->CI->config->item('redirectUri') . '&state=' . $state;
    }


    public function digilocker_fetch_btn($file_class)
    {
        $check_token = $this->check_valid_token();
        ?>
        <p class="mt-2">
            <span class="text-success font-weight-bold <?= $file_class ?>_msg"></span>
            <button class="btn btn-sm  digilocker_fetch_doc" <?= ($check_token == 1) ? '' : 'disabled' ?> type="button" onclick="<?= $this->fetch_btn($file_class) ?>"><img src="<?= base_url('assets/iservices/images/digilocker.jpg') ?>" alt="" width="20px"> Get document from digilocker</button>
        </p>
<?php }

    public function fetch_btn($btn_id)
    {
        return "window.open('" . base_url('digilocker/get_files/' . base64_encode($btn_id)) . "','popUpWindow','height=650,width=600,left=350,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');";
    }

    public function check_valid_token()
    {
        if ($this->CI->session->userdata('token_details')) {
            $session_generated_time = $this->CI->session->userdata('token_details')['session_gen_time'];
            $expire_time = $this->CI->session->userdata('token_details')['expires_in'];
            $curr_time = time();
            $time_diff = ($curr_time - $session_generated_time);
            if ($time_diff >= $expire_time) {
                // token expired
                $res = 0;
            } else {
                // token not expired
                $res = 1;
            }
        } else {
            $res = 0;
        }
        return $res;
    }






    // below methods are used for digilocker scheduler

    // public function pushUriDigilocker($mobile, $rtps_no, $certificate)
    public function pushUriDigilocker($application_data)
    {
        $this->CI->mongo_db->switch_db("iservices");
        $data = (array)$this->CI->mongo_db->where(array('mobile' => $application_data['mobile']))->get('front_users');
        $log = ['rtps_ref_no' => $application_data['ref_no']];
        $log['push_status'] = false;
        if (count($data)) {
            if (isset($data[0]->digilocker_consent) && ($data[0]->digilocker_consent == 1)) {
                $digilockerId = $data[0]->consent_data->digilocker_data->digilockerid;
                $generate_uri = $this->generateURI($application_data);
                if ($generate_uri['status'] != 'error') {
                    $pushSts = false;
                    $error_response = null;
                    if ($generate_uri['status'] == 'success') {
                        $response = $this->push_doc_api($generate_uri, $digilockerId);
                        if (isset($response->error)) {
                            $res = 'error';
                            $error_response = $response->error;
                        } else {
                            $pushSts = true;
                            $this->CI->digilocker_model->save_digilocker_uri($generate_uri);
                            $res = 'success';
                        }
                    } else {
                        $error_response = $generate_uri['msg'];
                        $res = $generate_uri['msg'];
                    }
                    $log['digilocker_id'] = $digilockerId;
                    $log['uri'] =  $generate_uri['data']['uri'];
                    $log['doctype'] =  $generate_uri['data']['doc_type'];
                    $log['description'] = $generate_uri['doc_description'];
                    $log['issue_date'] =  date('dmY');
                    $log['action'] = 'A';
                    $log['ts'] = time();

                    $log['push_status'] = $pushSts;
                    $log['error_response'] = $error_response;
                } else {
                    $res = $generate_uri['msg'];
                    $log['error_response'] = $res;
                }
            } else {
                $res = 'Digilocker Consent was not given by user.';
                $log['error_response'] = $res;
            }
        } else {
            $res = 'No user linked to this mobile number.';
            $log['error_response'] = $res;
        }
        $log['create_at'] = new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000));
        $log['updated_at'] = new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000));
        $this->CI->digilocker_model->save_push_log($log);
        return $log;
    }

    public function generateURI($data)
    {
        $response['status'] = 'error';
        if ($data['certi']) {
            $doctype = $this->checkDoctype($data['service_id']);
            if ($doctype) {
                $random_number = $this->generateserialNumber(5);
                $doc_id = str_replace("/", "", substr($data['ref_no'], 5)) . $random_number;
                $certificate = $data['certi'];
                $user_data = array(
                    "rtps_no" => $data['ref_no'],
                    "name" => $data['name'],
                    "file_path" => FCPATH . $certificate,
                    "uri" => "in.gov.assam.rtps-" . $doctype['doctype'] . "-" . $doc_id,
                    "doc_type" => $doctype['doctype'],
                    "create_at" => new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
                    "updated_at" => new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
                );
                $response['status'] = 'success';
                $response['data'] = $user_data;
                $response['doc_id'] = $doc_id;
                $response['doc_description'] = $doctype['description'];
                $response['msg'] = 'ok';
            } else {
                $response['msg'] = 'Doctype not mapped in db.';
            }
        } else {
            $response['msg'] = 'Certificate not found.';
        }
        return $response;
    }

    public function checkDoctype($service)
    {
        $doctype = (array)$this->CI->mongo_db->where(array('base_service_id' => $service))->get('doctype_service_mapping');
        if ($doctype) {
            $res = array('doctype' => $doctype[0]->doctype, 'description' => $doctype[0]->description);
        } else {
            $alt_doctype = (array)$this->CI->mongo_db->where(array('external_service_id' => $service))->get('doctype_service_mapping');
            if ($alt_doctype) {
                $res = array('doctype' => $alt_doctype[0]->doctype, 'description' => $alt_doctype[0]->description);
            } else{
                $res = false;
            }
        }
        return $res;
    }

    public function generateserialNumber($length)
    {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = $number;
        return $str;
    }

    public function push_doc_api($param, $digilocker_id)
    {
        // $client_id = $this->CI->config->item('clientId');
        $client_id = '123';
        $client_secret = $this->CI->config->item('clientSecret');
        $digilockerId = $digilocker_id;
        // echo $digilockerId = 'cbcfe7f3-6d93-11e9-a85e-9457a5645068';
        $ts = time();
        $uri = $param['data']['uri'];
        $doctype = $param['data']['doc_type'];
        $description = $param['doc_description'];
        $docid = $param['doc_id'];
        $issuedate = date('dmY');
        $action = 'A';
        $beforeHash = $client_secret . $client_id . $digilockerId . $uri . $doctype . $description . $docid . $issuedate . $action . $ts;

        // (client secret, clientid, digilockerid, uri, doctype, description, docid, issuedate, validfrom, validto, action, ts).
        $afterHash = hash('sha256', $beforeHash);
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
        );
        $payload = "clientid=" . $client_id . "&digilockerid=" . $digilockerId . "&uri=" . $uri . "&doctype=" . $doctype . "&description=" . $description . "&docid=" . $docid . "&issuedate=" . $issuedate . "&action=" . $action . "&ts=" . $ts . "&hmac=" . $afterHash;
        $url = curl_init('https://digilocker.meripehchaan.gov.in/public/account/1/pushuri');
        curl_setopt($url, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($url, CURLOPT_POST, 1);
        curl_setopt($url, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($url, CURLOPT_RETURNTRANSFER, TRUE);

        $res = curl_exec($url);
        if (curl_errno($url)) {
            $error_msg = curl_error($url);
            pre($error_msg);
        }
        curl_close($url);
        $data = json_decode($res);
        return json_decode($res);
    }

    // for testing purpose
    public function test_push()
    {
        // $client_id = $this->CI->config->item('clientId');
        echo $client_id = '59BF6A86';
        echo '<br>';
        echo $client_secret = '40d213dc245abf935a2a';
        echo '<br>';
        // $digilockerId = $digilocker_id;
        echo $digilockerId = 'cbcfe7f3-6d93-11e9-a85e-9457a5645068';
        echo '<br>';
        echo $ts = time();
        echo '<br>';
        echo $uri = 'in.gov.assam.rtps-INCER-123';
        echo '<br>';
        echo $doctype = 'INCER';
        echo '<br>';
        echo $description = 'Income Certificate';
        echo '<br>';
        echo $docid = '123';
        echo '<br>';
        echo $issuedate = date('dmY');
        echo '<br>';
        echo $action = 'A';
        echo '<br>';
        echo $beforeHash = $client_secret . $client_id . $digilockerId . $uri . $doctype . $description . $docid . $issuedate . $action . $ts;
        echo '<br>--------<br>';
        // (client secret, clientid, digilockerid, uri, doctype, description, docid, issuedate, validfrom, validto, action, ts).
        echo $afterHash = hash('sha256', $beforeHash);
        echo '<br>---------<br>';

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
        );
        $payload = "clientid=" . $client_id . "&digilockerid=" . $digilockerId . "&uri=" . $uri . "&doctype=" . $doctype . "&description=" . $description . "&docid=" . $docid . "&issuedate=" . $issuedate . "&action=" . $action . "&ts=" . $ts . "&hmac=" . $afterHash;
        $url = curl_init('https://digilocker.meripehchaan.gov.in/public/account/1/pushuri');
        curl_setopt($url, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($url, CURLOPT_POST, 1);
        curl_setopt($url, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($url, CURLOPT_RETURNTRANSFER, TRUE);

        $res = curl_exec($url);
        if (curl_errno($url)) {
            $error_msg = curl_error($url);
            pre($error_msg);
        }
        curl_close($url);
        $data = json_decode($res);
        return json_decode($res);
    }
}
