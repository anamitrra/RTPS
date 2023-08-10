<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
date_default_timezone_set('Asia/Kolkata');
use MongoDB\BSON\UTCDateTime;

class Pull_uri extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("digilocker/application_model");
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
            $hmac = getallheaders()['X-Digilocker-Hmac'];
            $hash = hash_hmac('sha256', $postData, $config[0]->api_key);
            $base64_hash = base64_encode($hash);
            // if hmac is matched
            if ($base64_hash === $hmac) {
                $db_name = $config[0]->db ?? '';
                $collection_name = $config[0]->collection ?? '';
                $user_defined_parameters = (array)$config[0]->udfs;
                $multiple_services = isset($config[0]->multi_services) ? (array)$config[0]->multi_services : [];
                $filter = [];
                foreach ($user_defined_parameters as $key => $val) {
                    $filter[$key] = (string)$xml->DocDetails->$val;
                    if (array_key_exists((string)$xml->DocDetails->$val, $multiple_services)) {
                        $filter[$key] = $multiple_services[(string)$xml->DocDetails->$val];
                    }
                }
                $aadhaarName = (string)$xml->DocDetails->FullName ?? '';
                $application_data = $this->application_model->get_data($filter, $db_name, $collection_name);
                if (count($application_data)) {
                    $this->process_response($application_data, $aadhaarName, $doctype, $transcation_data, $collection_name);
                } else {
                    // echo 'no data';
                }
            } else {
                // echo 'hmac not match.';
            }
        } else {
            // echo 'invalid doctype';
        }
        $this->process_response(null, null, null, $transcation_data, null);
    }

    public function process_response($rdata, $aname, $doctype, $txn_data, $collection)
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
        if ($rdata != null) {
            if (strcasecmp($rdata[0]->initiated_data->attribute_details->applicant_name, $aname) == 0) {
                // echo 'name match';
                $rtps_no = $rdata[0]->initiated_data->appl_ref_no;
                $certificate = '';
                $certs = $rdata[0]->initiated_data->certs;
                foreach ($certs as $val) {
                    if ($val->application_cert_flag == 'C') {
                        $certificate =  $val->file_name;
                    }
                }
                // $certificate = FCPATH . 'storage/docs/2023/03/16/9101379463_DoVNEZ7pe3.pdf';
                $uri = $this->generate_uri($rtps_no, $doctype);
                $name = $rdata[0]->initiated_data->attribute_details->applicant_name;
                $data['status'] = 1;
                $data['name'] = $name;
                $data['dob'] = isset($rdata[0]->initiated_data->attribute_details->date_of_birth) ? $rdata[0]->initiated_data->attribute_details->date_of_birth : '';
                $data['gender'] = isset($rdata[0]->applicant_gender) ? $rdata[0]->applicant_gender : $rdata[0]->initiated_data->attribute_details->applicant_gender;
                $data['phone'] = isset($rdata[0]->contact_number) ? $rdata[0]->contact_number : $rdata[0]->initiated_data->attribute_details->mobile_number;
                $data['encodedPdf'] = base64_encode(file_get_contents($certificate));
                $data['uri'] = $uri;
                $data_to_save = [
                    'rtps_no' => $rtps_no,
                    'name' => $name,
                    'file_path' => $certificate,
                    'uri' => $uri,
                    'doc_type' => $doctype,
                    'create_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'updated_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                ];
                $this->application_model->save_uri($data_to_save);
            } else {
                // echo 'name not match';
                // $this->response_request($data);
            }
        }
        $this->response_request($data);
    }

    public function generate_uri($rtps_no, $doctype)
    {
        if (strpos($rtps_no, 'RTPS') !== false) {
            $sub_rtps_no =  substr($rtps_no, 5);
        } else {
            $sub_rtps_no =  $rtps_no;
        }
        $random_number = $this->generateserialNumber(5);
        $doc_id = str_replace("/", "", $sub_rtps_no) . $random_number;
        // $doc_id = str_replace("/", "", $sub_rtps_no);
        $uri = 'in.gov.assam.rtps-' . $doctype . '-' . $doc_id;
        return $uri;
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

    public function response_request($data)
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
