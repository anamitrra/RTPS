<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api extends frontend {
    protected $encryptionKey = 'K^揄i��`���Q.��';
    public function __construct() {
        parent::__construct();
        $this->load->model('public_grievance_model');
        $this->load->library('AES');
    }//End of __construct()

    public function authenticate_api_request() {
        $rawInputStream = $this->input->raw_input_stream;
        $stream_clean = $this->security->xss_clean($rawInputStream);
        $encryptedRequest = json_decode($stream_clean);
        $decodedString = $this->decryptMe($encryptedRequest->encryptedString);
        $request = json_decode($decodedString);
        if (gettype($request) !== 'object') {
            $request = json_decode($request);
        }
        if (isset($request) && property_exists($request, 'SystemID')) {
            if ($this->input->method(TRUE) === 'POST') {
                $_POST = (array) $request;
            }
            return ['status' => 200];
        } else {
            return ['status' => 401, 'data' => ['msg' => 'Unauthorized Request.']];
        }
    }//End of authenticate_api_request()

    public function post_grievance() {               
        $authenticate = $this->authenticate_api_request();
        if ($authenticate['status'] != 200 && array_key_exists('data', $authenticate)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header($authenticate['status'])
                ->set_output(json_encode(['encryptedResponse' => $this->encryptMe(json_encode($authenticate['data']))]));
        }
        
        $cpgramServices = array('Service not delivered', 'Delayed service delivery', 'Other service related issue');
        $grievance_category = $this->input->post('GrievanceCategory');
        $serviceId = $this->input->post('ServiceId');
        
        $this->load->library('form_validation');
        $this->validate_grievance_form();
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(400)
                            ->set_output(json_encode([
                                'encryptedResponse' => $this->encryptMe(json_encode(['msg' => 'Bad Request', 'errors' => validation_errors()]))
            ]));
        }
        $this->load->helper('model');
        $grievanceRefNum = checkAndGenerateUniqueId('GrievanceReferenceNumber', 'public_grievances');
        
        if (in_array($grievance_category, $cpgramServices)) {
            $this->load->model('services_model');            
            $service = $this->services_model->get_department_by_service_id($serviceId);
            if (empty($service)) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['encryptedResponse' => $this->encryptMe(json_encode(['msg' => 'Bad Request', 'errors' => 'Invalid Service ID']))
                ]));
            } else {
                $departmentCode = $service->department->department_code;
            }//End of if else
        } else {
            $departmentCode = 'ARTAS';
        }//End of if else
        
        $removeChars = array( '`', '~', '!', '@', '#', '$', '%', '^', '&', '&', '*', '(', ')', '-', '+', '=', '[', ']', '{', '}', '<', '>', '/', '?');
        $subjectContent  = $this->input->post('SubjectContent', true);
        
        $grievanceCpgramApiInput = [
            "GrievanceReferenceNumber" => $grievanceRefNum,
            "Name" => $this->input->post('Name', true),
            "Gender" => $this->input->post('Gender', true),
            "Address1" => $this->input->post('Address1', true),
            "Address2" => $this->input->post('Address2', true),
            "Address3" => $this->input->post('Address3', true),
            "Pincode" => $this->input->post('Pincode', true),
            "District" => $this->input->post('District', true),
            "State" => MY_STATE['state_code'],
            "Country" => MY_COUNTRY['country_code'],
            "EmailAddress" => $this->input->post('EmailAddress', true),
            "MobileNumber" => $this->input->post('MobileNumber', true),
            "Language" => "E",
            "SubjectContent" => str_replace($removeChars, ' ', $subjectContent),//$this->input->post('SubjectContent', true),
            "DateOfReceipt" => date('m-d-Y'), //mm-dd-yyyy for cpgram
            "ComplainantIpAddress" => $_SERVER['REMOTE_ADDR'] == '::1' ? '127.0.0.1' : $_SERVER['REMOTE_ADDR'], //$this->input->post('ComplainantIpAddress',true),
            "ExServicemen" => 'N', // as it is not applicable for now,
            "AutoForwardOrgCode" => $departmentCode, //$this->input->post('AutoForwardOrgCode',true), // as it is not applicable for now,
        ];

        $grievanceLocalInput = [
            'GrievanceCategory' => $grievance_category, // ServiceRelated,PFCRelated,PortalRelated,Other
            'ServiceId' => $serviceId, // if GrievanceCategory is ServiceRelated
            'userId' => $this->input->post('userId'), // unique ID for every api user
            'userType' => $this->input->post('userType'), // type of the user helpdesk or call center
            'subject_content' => $subjectContent // SubjectContent without remove any characters
        ];
        if ($this->input->post('Document')) {
            $decoded = base64_decode($this->input->post('Document'));
            $grievanceCpgramApiInput['Document'] = $this->input->post('Document');
            $finfo = new finfo(FILEINFO_MIME);
            $mimeType = explode(';', $finfo->buffer(base64_decode($this->input->post('Document'))) . PHP_EOL)[0];
            $size_in_bytes = (int) (strlen(rtrim($this->input->post('Document'), '=')) * 3 / 4);
            $size_in_kb = $size_in_bytes / 1024;
            $size_in_mb = $size_in_kb / 1024;
            if ($size_in_mb >= 2) {
                return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(400)
                                ->set_output(json_encode([
                                    'encryptedResponse' => $this->encryptMe(json_encode(['msg' => 'Bad Request', 'errors' => 'Maximum size of Document should be 2MB.']))
                ]));
            }
            if ($mimeType === 'application/pdf') {
                $fileName = md5(uniqid()) . '.pdf';
                $filePath = FCPATH . 'storage/uploads/grievance/attachments/' . $fileName;
                file_put_contents($filePath, $decoded);
                $grievanceLocalInput['Document'] = $fileName;
            } else {
                return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(400)
                                ->set_output(json_encode([
                                    'encryptedResponse' => $this->encryptMe(json_encode([
                                        'msg' => 'Bad Request', 'errors' => 'Only PDF files are allowed.'
                                    ]))
                ]));
            }
        }
        
        if(in_array($grievance_category, $cpgramServices)) {
            $this->load->library('CPGRMS_api_client', ['type' => 'register-grievance']);
            $apiResponse = $this->cpgrms_api_client->post($grievanceCpgramApiInput);
            $apiResponseObj = json_decode($apiResponse);
            if (array_key_exists('Document', $grievanceCpgramApiInput)) {
                unset($grievanceCpgramApiInput['Document']);
            }
            
            $inputs = array_merge($grievanceCpgramApiInput, $grievanceLocalInput);
            if (!isset($apiResponseObj) || !property_exists($apiResponseObj, 'registration_no')) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode([
                        'encryptedResponse' => $this->encryptMe(json_encode([
                            'msg' => 'Internal Server Error.',
                            'errors' => $apiResponseObj->result ?? 'Failed',
                            'fail' => 'Unable to submit grievance!!! Please try again later.'
                        ]))
                ]));
            }
            
            $inputs['registration_no'] = $apiResponseObj->registration_no;
            $inputs['result'] = $apiResponseObj->result;
            $inputs['data_location'] = "RTPS_AND_CPGRAM";
        } else {
            $inputs = array_merge($grievanceCpgramApiInput, $grievanceLocalInput);
            $inputs['registration_no'] = $this->getID(5);
            $inputs['result'] = 'Grievance lodged successfully';
            $inputs['data_location'] = "RTPS_ONLY";
        }//End of if else
                
        $inputs['DateOfReceipt'] = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000); //For mongodb date
        $inputs['complaintSource'] = 'CC';
        $inputs['serviceName'] = $this->input->post('ServiceName', true);
        $inputs['concernAuthority'] = $this->input->post('ConcernAuthority', true);
        $inputs["refno"] = $this->input->post('refno', true);
        $inputs["rtpsrefno"] = $this->input->post('rtpsrefno', true);
        $this->public_grievance_model->insert($inputs);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'encryptedResponse' => $this->encryptMe(json_encode([
                    'msg' => 'success',
                    'GrievanceReferenceNumber' => $grievanceRefNum,
                    'RegistrationNumber' => $inputs['registration_no']
                ]))
        ]));
    }//End of post_grievance()

    private function validate_grievance_form(): void {
        $this->form_validation->set_rules('Name', 'Name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('Gender', 'Gender', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('Address1', 'Address1', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('Pincode', 'Pincode', 'trim|required|xss_clean|strip_tags|regex_match[/^[0-9]\d{5}$/]|max_length[6]');
        $this->form_validation->set_rules('District', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('EmailAddress', 'EmailAddress', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('MobileNumber', 'MobileNumber', 'trim|required|xss_clean|strip_tags|min_length[10]|max_length[10]|regex_match[/^[6-9]\d{9}$/]');
        $this->form_validation->set_rules('GrievanceCategory', 'Grievance Category', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('SubjectContent', 'SubjectContent', 'trim|required|max_length[4000]|xss_clean|strip_tags');
        $this->form_validation->set_rules('Document', 'Document', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('AutoForwardOrgCode', 'AutoForwardOrgCode', 'trim|xss_clean|strip_tags|regex_match[/^[A-Z a-z 0-9]{5}$/]');
    }//End of validate_grievance_form()

    public function get_district_list() {
        $this->load->model('district_model');
        $data = [
            'DistrictList' => $this->district_model->get_selected([], ['_id'])
        ];
        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['encryptedResponse' => $this->encryptMe(json_encode($data))]));
    }//End of get_district_list()

    public function get_service_list() {
        $this->load->model('services_model');
        $data = [
            'ServiceList' => $this->services_model->get_selected([], ['_id', 'updated_at', 'created_at'])
        ];
        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['encryptedResponse' => $this->encryptMe(json_encode($data))]));
    }//End of get_service_list()
    
    function getID($length) {
        $registration_no = $this->generateID($length);
        while ($this->public_grievance_model->get_filtered_rows(["registration_no" => $registration_no])) {
            $registration_no = $this->generateID($length);
        }//End of while()
        return $registration_no;
    }//End of getID()

    public function generateID($length) {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS/I/" . date('Y') . "/" . $number;
        return $str;
    }//End of generateID()

    private function encryptMe($plainText) {
        $aesObject = new AES($plainText, $this->encryptionKey);
        return $aesObject->encrypt();
    }//End of encryptMe()

    private function decryptMe($cypherText) {
        $aesObject = new AES($cypherText, $this->encryptionKey);
        return $aesObject->decrypt();
    }//End of decryptMe()
}
