<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\UTCDateTime;

class External_pull_api extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("digilocker/application_model");
        $this->load->library('digilocker_externalapi');
    }

    public function index()
    {
        $postData = file_get_contents('php://input');
        $xml = simplexml_load_string($postData);
        $ts = (string)$xml->attributes()->{'ts'};
        $txn = (string)$xml->attributes()->{'txn'};
        $transcation_data = [
            'ts' => $ts,
            'txn' => $txn
        ];

        $doctype = (string)$xml->DocDetails->DocType;
        $config = (array)$this->mongo_db->where(array('doctype' => $doctype))->get('digilocker_service_settings');

        if (count($config)) {
            $hmac = getallheaders()['x-digilocker-hmac'];
            $hash = hash_hmac('sha256', $postData, $config[0]->api_key);
            $base64_hash = base64_encode($hash);
            // if hmac is matched
            if ($base64_hash === $hmac) {
                if ($config[0]->useAPI) {
                    $db_name = $config[0]->db ?? '';
                    $collection_name = $config[0]->collection ?? '';
                    $user_defined_parameters = (array)$config[0]->udfs;
                    $multiple_services = isset($config[0]->multi_services) ? (array)$config[0]->multi_services : [];
                    $api_parameters = (array)$config[0]->api_parameters;
                    $api = $config[0]->api_url ?? '';
                    $postFields = '';
                    $filter = [];

                    foreach ($api_parameters as $key => $val) {
                        $x = (strlen($postFields)) ? '&' . $key . '=' : $key . '=';
                        if (array_key_exists((string)$xml->DocDetails->$val, $multiple_services)) {
                            $postFields = $postFields . $x . $multiple_services[(string)$xml->DocDetails->$val];
                        } else {
                            $postFields = $postFields . $x . (string)$xml->DocDetails->$val;
                        }
                    }

                    if (strlen($db_name)) {
                        foreach ($user_defined_parameters as $key => $val) {
                            $filter[$key] = (string)$xml->DocDetails->$val;
                        }
                        $aadhaarName = (string)$xml->DocDetails->FullName ?? '';
                        $application_data = (array)$this->application_model->get_data($filter, $db_name, $collection_name);
                        if (count($application_data)) {
                            // fetch api from library
                            $response = json_decode($this->digilocker_externalapi->process_api($api, $postFields, $doctype), true);
                            if (count($response)) {
                                $this->process_response($application_data, $response, $aadhaarName, $doctype, $transcation_data, $xml->DocDetails);
                            } else {
                                // echo 'certificate not found.';
                            }
                        } else {
                            // echo 'application  not found.';
                        }
                    } else {
                        // echo 'no db selected.';
                    }
                } else {
                    // no need to check in db and call api;
                }
            } else {
                // echo 'hmac not match.';
            }
        } else {
            // echo 'invalid doctype';
        }
        $this->process_response(null, null, null, null, $transcation_data, null);
    }

    function process_response($application_data, $certificate_data, $aname, $doctype, $txn_data, $xmlData)
    {
        $data['ts'] = $txn_data['ts'];
        $data['txn'] = $txn_data['txn'];
        $data['status'] = 0;
        $data['name'] = '';
        $data['dob'] = '';
        $data['gender'] = '';
        $data['phone'] = '';
        $data['encodedPdf'] = '';
        $data['uri'] = '';
        if ($application_data != null) {
            $name = isset($application_data['name']) ? $application_data['name'] : (isset($application_data[0]->initiated_data->attribute_details->applicant_name) ? $application_data[0]->initiated_data->attribute_details->applicant_name : $application_data[0]->applicant_details[0]->applicant_name);
            if (strcasecmp($name, $aname) == 0) {
                $file = base64_decode($certificate_data['certificate'], true);
                if (strpos($file, '%PDF') !== 0) {
                    // throw new Exception('Missing the PDF file signature');
                    $this->send_response($data);
                } else {
                    $rtps_no = isset($application_data['application_no']) ? $application_data['application_no'] : (isset($application_data[0]->initiated_data->appl_ref_no) ? $application_data[0]->initiated_data->appl_ref_no : $application_data[0]->app_ref_no);
                    if (strpos($rtps_no, 'RTPS') !== false) {
                        $sub_rtps_no =  substr($rtps_no, 5);
                    } else {
                        $sub_rtps_no =  $rtps_no;
                    }
                    $random_number = $this->generateserialNumber(5);
                    $doc_id = str_replace("/", "", $sub_rtps_no) . $random_number;
                    $upload_dir = FCPATH . 'storage/docs/digilocker_certificates/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    $filepath = $upload_dir . $doc_id . '.pdf';
                    file_put_contents($filepath, $file);
                    $uri = 'in.gov.assam.rtps-' . $doctype . '-' . $doc_id;
                    $data['status'] = 1;
                    $data['name'] = $name;
                    $data['dob'] = '';
                    $data['gender'] = isset($application_data[0]->initiated_data->attribute_details->applicant_gender) ?  $application_data[0]->initiated_data->attribute_details->applicant_gender : '';
                    $data['phone'] = isset($application_data[0]->initiated_data->attribute_details->mobile_number) ? $application_data[0]->initiated_data->attribute_details->mobile_number : '';
                    $data['encodedPdf'] = $certificate_data['certificate'];
                    $data['uri'] = $uri;

                    $data_to_save = [
                        'rtps_no' => $rtps_no,
                        'name' => $name,
                        'file_path' => $filepath,
                        'uri' => $uri,
                        'doc_type' => $doctype,
                        'basundhara_type' => (string)$xmlData->param2 ?? '',
                        'create_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        'updated_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                    ];
                    $this->application_model->save_uri($data_to_save);
                }
            } else {
                // echo 'name not match';
            }
        }
        $this->send_response($data);
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

    public function send_response($data)
    {
        header("Content-type: application/xml");
        echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        echo '<PullURIResponse xmlns:ns2="http://tempuri.org/">';
        echo '<ResponseStatus Status="' . $data['status'] . '" ts="' . $data['ts'] . '" txn="' . $data['txn'] . '">' . $data['status'] . '</ResponseStatus>'; //1-Success //0-Failure //9-Pending
        echo '<DocDetails>';
        echo '<IssuedTo>';
        echo '<Persons>';
        echo '<Person name="' . $data['name'] . '" dob="' . $data['dob'] . '" gender="' . $data['gender'] . '" phone="' . $data['phone'] . '">';
        echo '</Person>';
        echo '</Persons>';
        echo '</IssuedTo>';
        echo '<URI>' . $data['uri'] . '</URI>';
        echo '<DocContent>' . $data['encodedPdf'] . '</DocContent>';
        echo '<DataContent></DataContent>';
        echo '</DocDetails>';
        echo '</PullURIResponse>';
        exit;
    }
}
