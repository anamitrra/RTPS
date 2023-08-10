<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
ini_set('MAX_EXECUTION_TIME', -1);
ini_set('memory_limit', -1);

use MongoDB\BSON\UTCDateTime;

class Digilocker_scheduler extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Digilocker_API');
    }

    public function index()
    {
        $pushed_data = array();
        $log_applications = (array)$this->mongo_db->where(array('push_status' => true))->get('digilocker_push_log');
        if (count($log_applications)) {
            foreach ($log_applications as $val) {
                $pushed_data[] = $val->rtps_ref_no;
            }
        } else {
            $pushed_data[] = '';
        }

        // $uri_list = (array)$this->mongo_db->get('digilocker_uri');
        // if (count($uri_list)) {
        //     foreach ($uri_list as $val) {
        //         $unpush_data[] = $val->rtps_no;
        //     }
        // } else {
        //     $unpush_data[] = '';
        // }
        $mapped_services = (array)$this->mongo_db->get('doctype_service_mapping');
        pre($mapped_services);
        if (count($mapped_services)) {
            foreach ($mapped_services as $val) {
                if (isset($val->base_service_id)) {
                    $mapped_servicelist[] = $val->base_service_id;
                }
            }
        }
        $this->get_applications($pushed_data, $mapped_servicelist);
    }

    public function get_applications($pushed_data, $mapped_servicelist)
    {
        $dtm = new \DateTime(date('Y-m-d'), new \DateTimeZone('Asia/Kolkata'));
        $dtm->modify('-1 day');
        $this->mongo_db->switch_db("mis");
        $collection = 'applications';
        $operations = [
            [
                '$addFields' => [
                    'deliveredDate' => [
                        '$convert' => [
                            'input' => '$initiated_data.execution_date_str',
                            'to' => 'date',
                            'onError' => 'Null'
                        ]
                    ]
                ]
            ],
            [
                '$match' =>  [
                    'deliveredDate' => ['$gte' => new UTCDateTime($dtm->getTimestamp() * 1000)],
                    'initiated_data.appl_status' => 'D',
                    'initiated_data.submission_mode' => ['$ne' => 'kiosk'],
                    'initiated_data.base_service_id' => [
                        '$in' => $mapped_servicelist
                    ],
                    'initiated_data.appl_ref_no' => [
                        '$nin' => $pushed_data
                    ]
                ]
            ],
            [
                '$project' => [
                    '_id' => 1,
                    'initiated_data.appl_ref_no' => 1,
                    'initiated_data.base_service_id' => 1,
                    'initiated_data.external_service_type' => 1,
                    'initiated_data.service_name' => 1,
                    'initiated_data.attribute_details.applicant_name' => 1,
                    'initiated_data.attribute_details.mobile_number' => 1,
                    'initiated_data.certs' => 1,
                ]
            ]
        ];
        $applications = $this->mongo_db->aggregate($collection, $operations);
        pre($applications);
        die();
        foreach ((array)$applications as $value) {
            $mobile = $value->initiated_data->attribute_details->mobile_number;
            $ref_no = $value->initiated_data->appl_ref_no;
            $service_id = $value->initiated_data->base_service_id;
            $certificate = $this->checkCertificate($value->initiated_data->certs ?? '', $ref_no, $service_id);
            $applicant_name = $value->initiated_data->attribute_details->applicant_name;
            echo '--------------------------------------------------------</br>';
            print_r($this->saveDigilockerId($mobile, $ref_no, $service_id, $applicant_name, $certificate));
            echo '<br>----------------------------------------------------</br>';
        }
    }

    public function checkCertificate($cert_arr, $ref_no, $service_id)
    {
        $certificate = '';
        if (is_array($cert_arr)) {
            foreach ($cert_arr as $val) {
                if ($val->application_cert_flag == 'C') {
                    $certificate =  $val->file_name;
                }
            }
        } else {
            $certificate = $this->get_basundhara_api_data($ref_no, $service_id);
        }
        return $certificate;
    }

    public function saveDigilockerId($mobile_number, $rtps_ref_no, $service_id, $applicant_name, $certificate)
    {
        if (is_array($certificate)) {
            for ($i = 0; $i < count($certificate); $i++) {
                $data = ['mobile' => $mobile_number, 'ref_no' => $rtps_ref_no, 'service_id' => $service_id, 'name' => $applicant_name, 'certi' => $certificate[$i]];
                $res[] = $this->digilocker_api->pushUriDigilocker($data);
            }
        } else {
            $data = ['mobile' => $mobile_number, 'ref_no' => $rtps_ref_no, 'service_id' => $service_id, 'name' => $applicant_name, 'certi' => $certificate];
            $res = $this->digilocker_api->pushUriDigilocker($data);
        }
        return $res;
    }

    public function get_basundhara_api_data($ref_no, $base_service_id)
    {
        $result = [];
        $type = $this->check_basundhara_doctype($base_service_id);
        if (!empty($type)) {
            if (is_array($type)) {
                for ($i = 0; $i < count($type); $i++) {
                    $result[] = $this->call_api($ref_no, $type[$i]);
                }
            } else {
                $result[] = $this->call_api($ref_no, $type);
            }
        }
        return $result;
    }

    public function check_basundhara_doctype($base_service_id)
    {
        $type = '';
        if (in_array($base_service_id, array('243', '244', '245', '246', '247', '248'))) {
            $type = [
                'PATTA',
                'ORDER'
            ];
        } else if ($base_service_id == '249') {
            $type = 'EKHAJANA';
        } else if ($base_service_id == '1054') {
            $type = 'ROR';
        }
        return  $type;
    }

    public function call_api($ref_no, $type)
    {
        $payload = "application_no=" . $ref_no . "&doc_type=" . $type;
        $curl = curl_init('https://basundhara.assam.gov.in/rtpsmb/getDeliveryDoc');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $curl_response = curl_exec($curl);
        if (curl_errno($curl)) {
            $curl_response = curl_error($curl);
        }
        curl_close($curl);
        $response = ((array)json_decode($this->process_file($curl_response, $type)));
        if ($response['status'] == 1) {
            $result = $response['certificate_path'];
        } else {
            $result = '';
        }
        return ($result);
    }

    public function process_file($data, $type = null)
    {
        $decodedData = json_decode($data);
        $certificate_path = '';
        $status = 0;
        $msg = '';
        if ($decodedData->responsetType == 2) {
            if (!empty($decodedData->data[0])) {
                $file = base64_decode($decodedData->data[0], true);
                if (strpos($file, '%PDF') !== 0) {
                    // throw new Exception('Missing the PDF file signature');
                    $msg = 'Unsupported file.';
                    $status = 0;
                } else {
                    $upload_dir = FCPATH . 'storage/docs/digilocker_certificates/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    $file_name = $type . $this->generateserialNumber(15);
                    $filepath = $upload_dir . $file_name . '.pdf';
                    file_put_contents($filepath, $file);
                    $certificate_path = 'storage/docs/digilocker_certificates/' . $file_name . '.pdf';
                    $status = 1;
                    $msg = 'success';
                }
            } else {
            }
        } else {
            $msg = $decodedData;
        }
        return json_encode(array('status' => $status, 'message' => $msg, 'certificate_path' => $certificate_path));
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

    public function singlePush($id = null)
    {
        // $ref_no = base64_decode($id);   
        $ref_no = 'RTPS-SCTZN/2023/9500765';
        $collection = 'sp_applications';
        $operations = [
            [
                '$match' => [
                    '$or' => [
                        ['service_data.appl_status' => 'D'],
                        ['service_data.appl_status' => 'DELIVERED'],
                    ],
                    'form_data.user_type' => 'user',
                    'service_data.appl_ref_no' => $ref_no
                ]
            ],
            [
                '$project' => [
                    '_id' => 1,
                    'service_data.appl_ref_no' => 1,
                    'service_data.service_id' => 1,
                    'form_data.applicant_name' => 1,
                    'form_data.certificate' => 1,
                    'form_data.mobile' => 1,
                    'form_data.mobile_number' => 1
                ]
            ]
        ];
        $applications = $this->mongo_db->aggregate($collection, $operations);
        // pre($applications);

        foreach ((array)$applications as $value) {
            $mobile = isset($value->form_data->mobile) ? $value->form_data->mobile : $value->form_data->mobile_number;
            $ref_no = $value->service_data->appl_ref_no;
            $service_id = $value->service_data->service_id;
            $certificate = isset($value->form_data->certificate) ? $value->form_data->certificate : '';
            $applicant_name = $value->form_data->applicant_name;
            echo '--------------------------------------------------------</br>';
            print_r($this->saveDigilockerId($mobile, $ref_no, $service_id, $applicant_name, $certificate));
            echo '<br>---------------------------------------------------</br>';
        }
    }
}
