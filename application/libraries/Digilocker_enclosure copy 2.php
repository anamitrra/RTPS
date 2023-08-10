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

    public function consent_btn()
    {
        $check_token = $this->check_valid_token();
        // pre($check_token);
        if ($check_token == 0) { ?>
            <button class="btn btn-sm  btn-primary" type="button" onclick="window.open('<?= $this->get_auth_code('consent') ?>','popUpWindow','height=650,width=600,left=350,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">
                 Allow
            </button>
        <?php } ?>
        <button class="btn btn-sm  btn-primary" type="button" onclick="window.open('<?= $this->get_auth_code('consent') ?>','popUpWindow','height=650,width=600,left=350,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">
                 Allow
            </button>
            <?php
    }

    public function login_btn()
    {
        $check_token = $this->check_valid_token();
        // pre($check_token);
        if ($check_token == 0) { ?>
            <button class="btn btn-sm  digilogin_btn pull-right" type="button" onclick="window.open('<?= $this->get_auth_code('digilogin') ?>','popUpWindow','height=650,width=600,left=350,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">
                <img src="<?= base_url('assets/iservices/images/digilocker.jpg') ?>" alt="" width="20px"> Link your digilocker
            </button>
        <?php } else if ($check_token == 1) { ?>
            <button class="btn btn-sm btn-info digilogin_btn pull-right" type="button"><i class="fa fa-lock"></i> Account linked successfully.</button>
        <?php } else {
            echo '';
        }
    }

    public function get_auth_code($state)
    {
        return 'https://digilocker.meripehchaan.gov.in/public/oauth2/1/authorize?response_type=code&client_id=' . $this->clientId . '&redirect_uri=' . $this->redirectUri . '&state='.$state;
    }


    public function digilocker_fetch_btn($file_class)
    {
        $check_token = $this->check_valid_token();
        ?>
        <p class="mt-2">
            <span class="text-success font-weight-bold <?= $file_class ?>_msg"></span>
            <button class="btn btn-sm  digilocker_fetch_doc" <?= ($check_token == 1) ? '' : 'disabled' ?> type="button" onclick="<?= $this->fetch_btn($file_class) ?>"><img src="<?= base_url('assets/iservices/images/digilocker.jpg') ?>" alt="" width="20px"> Fetch from digilocker</button>
        </p>
<?php }

    public function fetch_btn($btn_id)
    {
        return "window.open('" . base_url('digilocker/get_files/' . base64_encode($btn_id)) . "','popUpWindow','height=650,width=600,left=350,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');";
    }

    public function saveDigilockerId()
    {
        // if ($this->CI->session->userdata('token_details') && $this->CI->session->userdata('mobile')) {
        if ($this->CI->session->userdata('token_details')) {

            $user_mobile = ($this->CI->session->userdata('mobile')) ? $this->CI->session->userdata('mobile') : rand(10, 100);
            $user_data = array(
                "user_mobile" => $user_mobile,
                "digilocker_id" => $this->CI->session->userdata('token_details')['digilockerid'],
                "digilocker_data" => $this->CI->session->userdata('token_details'),
                "createdDtm" => new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
            );
            $data = (array)$this->CI->mongo_db->where(array('user_mobile' => $user_mobile))->get('digilocker_users');

            // $data = (array)$this->CI->mongo_db->where(array('user_mobile' => $this->CI->session->userdata('mobile')))->get('digilocker_users');
            if (!$data) {
                $this->CI->digilocker_model->insert($user_data);
            }
            // $this->CI->session->unset_userdata('token_details');
        } else {
            echo 'no session found';
        }
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

    public function pushUriDigilocker($mobile, $rtps_no)
    {
        $data = (array)$this->CI->mongo_db->where(array('user_mobile' => intval($mobile)))->get('digilocker_users');
        if (count($data)) {
            $digilockerId = $data[0]->digilocker_id;
            $URI = $this->checkCertificate($rtps_no);
            // pre($URI);
            if ($URI['status'] == 'success') {
                $this->CI->digilocker_model->save_digilocker_uri($URI);
                $response = $this->push_doc_api($URI, $digilockerId);
                // pre($response);
                // if (property_exists($response, 'error')) {
                if (isset($response->error)) {
                    $res = 'error';
                } else {
                    $res = 'success';
                }
                // pre($response);
            } else {
                $res = $URI['msg'];
            }
        } else {
            $res = 'No user linked to this mobile number.';
        }
        return $res;
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
        // pre($data);
        if (count($data)) {
            $doctype = $this->checkDoctype($data[0]->service_data->service_id);
            // pre($doctype);
            if ($doctype) {
                $doc_id = str_replace("/", "", substr($data[0]->service_data->appl_ref_no, 5));
                $certificate = isset($data[0]->execution_data[0]->official_form_details->output_certificate) ? $data[0]->execution_data[0]->official_form_details->output_certificate : $data[0]->form_data->certificate;
                $user_data = array(
                    "rtps_no" => $data[0]->service_data->appl_ref_no,
                    "name" => $data[0]->form_data->applicant_name,
                    "file_path" => $certificate,
                    "uri" => "in.gov.assam.rtps-" . $doctype['doctype'] . "-" . $doc_id,
                    // "uri" => "in.gov.assam.rtps-RECER-SHLTC202214761PATTA",
                    "doc_type" => $doctype['doctype'],
                    // "doc_type" => "RECER",

                    "create_at" => new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
                    "updated_at" => new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
                );
                $response['status'] = 'success';
                $response['data'] = $user_data;
                $response['doc_id'] = $doc_id;
                // $response['doc_id'] = "SHLTC202214761PATTA";

                // $response['doc_description'] = $doctype['description'];
                $response['doc_description'] = 'Registration Certificate';

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
        // pre($param);
        echo $client_id = $this->clientId;
        echo '<br>';
        echo $client_secret = $this->clientSecret;
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
        echo        $afterHash = hash('sha256', $beforeHash);
        echo '<br>----------------------------';

        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
        );
        $payload = "clientid=" . $client_id . "&digilockerid=" . $digilockerId . "&uri=" . $uri . "&doctype=" . $doctype . "&description=" . $description . "&docid=" . $docid . "&issuedate=" . $issuedate . "&action=" . $action . "&ts=" . $ts . "&hmac=" . $afterHash;

        $payload_new = array(
            "clientid" => $client_id,
            "digilockerid" => $digilockerId,
            "uri" => $uri,
            "doctype" => $doctype,
            "description" => $description,
            "docid" => $docid,
            "issuedate" => $issuedate,
            "action" => $action,
            "ts" => $ts,
            "hmac" => $afterHash
        );
        // pre($payload_new);
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
