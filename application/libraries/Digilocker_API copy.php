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

    public function pushUriDigilocker($mobile, $rtps_no, $certificate)
    {
        $data = (array)$this->CI->mongo_db->where(array('mobile' => $mobile))->get('front_users');
        $log = ['rtps_ref_no' => $rtps_no];
        $log['push_status'] = false;
        if (count($data)) {
            if (isset($data[0]->digilocker_consent) && ($data[0]->digilocker_consent == 1)) {
                $digilockerId = $data[0]->consent_data->digilocker_data->digilockerid;
                $certificate_data = $this->checkCertificate($rtps_no);
                if ($certificate_data['status'] != 'error') {
                    $pushSts = false;
                    $error_response = null;
                    if ($certificate_data['status'] == 'success') {
                        // $response = $this->push_doc_api($certificate_data, $digilockerId);
                        // // if (property_exists($response, 'error')) {
                        // if (isset($response->error)) {
                        //     $res = 'error';
                        //     $error_response = $response->error;
                        // } else {
                        //     $pushSts = true;
                        //     $this->CI->digilocker_model->save_digilocker_uri($certificate_data);
                        //     $res = 'success';
                        // }
                    } else {
                        $error_response = $certificate_data['msg'];
                        $res = $certificate_data['msg'];
                    }
                    $log['digilocker_id'] = $digilockerId;
                    $log['uri'] =  $certificate_data['data']['uri'];
                    $log['doctype'] =  $certificate_data['data']['doc_type'];
                    $log['description'] = $certificate_data['doc_description'];
                    $log['issue_date'] =  date('dmY');
                    $log['action'] = 'A';
                    $log['ts'] = time();

                    $log['push_status'] = $pushSts;
                    $log['error_response'] = $error_response;
                } else {
                    $res = $certificate_data['msg'];
                    $log['error_response'] = $res;
                }
            } else {
                $res = 'Digilocker Consent was not given by user.';
                $log['error_response'] = $res;
            }
        } else {
            $res = 'No user linked to this mobile number.';
        }
        $log['create_at'] = new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000));
        $log['updated_at'] = new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000));
        $this->CI->digilocker_model->save_push_log($log);
        return $log;
    }

    public function checkCertificate($id)
    {
        $data = (array)$this->CI->mongo_db
            ->where(array(
                'service_data.appl_ref_no' => $id,
                '$or' => array(
                    array('service_data.appl_status' => 'DELIVERED'),
                    array('service_data.appl_status' => 'D')
                )
            ))
            ->get('sp_applications');
        $response['status'] = 'error';

        if (count($data)) {
            $doctype = $this->checkDoctype($data[0]->service_data->service_id);

            if ($doctype) {
                $doc_id = str_replace("/", "", substr($data[0]->service_data->appl_ref_no, 5));
                // $certificate = isset($data[0]->execution_data[0]->official_form_details->output_certificate) ? $data[0]->execution_data[0]->official_form_details->output_certificate : $data[0]->form_data->certificate;
                $certificate = $data[0]->form_data->certificate;
                $user_data = array(
                    "rtps_no" => $data[0]->service_data->appl_ref_no,
                    "name" => $data[0]->form_data->applicant_name,
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
            $response['msg'] = 'Application not found or not delivered.';
        }
        return $response;
    }

    public function checkDoctype($service)
    {
        $doctype = (array)$this->CI->mongo_db->where(array('service_id' => $service))->get('doctype_service_mapping');
        if ($doctype) {
            $res = array('doctype' => $doctype[0]->doctype, 'description' => $doctype[0]->description);
        } else {
            $res = false;
        }
        return $res;
    }

    public function push_doc_api($param, $digilocker_id)
    {
        die();
        // pre($param);
        echo 'cid - ' . $client_id = $this->CI->config->item('clientId');
        echo '<br>';
        echo 'csec - ' . $client_secret = $this->CI->config->item('clientSecret');
        echo '<br>';

        echo $digilockerId = $digilocker_id;
        echo '<br>';

        echo 'ts' . $ts = time();
        echo '<br>';

        echo $uri = $param['data']['uri'];
        echo '<br>';

        echo $doctype = $param['data']['doc_type'];
        echo '<br>';

        echo $description = $param['doc_description'];
        echo '<br>';

        echo $docid = $param['doc_id'];
        echo '<br>';

        echo $issuedate = date('dmY');
        echo '<br>';

        echo $action = 'A';
        echo '<br>';

        // pre('o');
        echo $beforeHash = $client_secret . $client_id . $digilockerId . $uri . $doctype . $description . $docid . $issuedate . $action . $ts;
        echo '<br>';

        // (client secret, clientid, digilockerid, uri, doctype, description, docid, issuedate, validfrom, validto, action, ts).
        echo    'hash - ' .    $afterHash = hash('sha256', $beforeHash);
        echo '<br>----------------------------';

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
