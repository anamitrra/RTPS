<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Grm extends frontend {

    public function __construct() {
        parent::__construct();
        $this->load->model('public_grievance_model');
        $this->load->model('view_status_model');
        $this->load->model('site/settings_model');
        $this->load->library('form_validation');
    }//End of __construct()

    public function index() {
        $this->load->model('district_model');
        $districtList = $this->district_model->get_by_desc();
        $this->load->model('services_model');
        $serviceList = $this->services_model->all();

        $data = [
            'districtList' => $districtList,
            'serviceList' => $serviceList,
            'old' => $this->session->flashdata('old') ?? [],
            'sessionUserRole' => $this->session->userdata('role')
        ];
        // $headers = $this->settings_model->get_settings('headers');
        $data['form_label'] = $this->settings_model->get_settings('form_label');

        $this->load->view('includes/frontend/header');
        $this->load->view('grievances/public/index', $data);
        $this->load->view('includes/frontend/footer');
    }//End of index()

    public function submit() {
        $grievance_category = $this->input->post('grievance_category');
        $cpgramServices = array('Service not delivered', 'Delayed service delivery', 'Other service related issue');
        $rtpsServices = array('PFC operator issue','CSC operator issue','OTP not working','Unable to login','Other technical issue');
        
        if (in_array($grievance_category, $cpgramServices)) {
            $this->load->model('services_model');
            $departmentCode = $this->services_model->get_department_by_service_id($this->input->post('service_name'))->department->department_code;
        } else {
            $departmentCode = 'ARTAS';
        }//End of if else
        $this->validate_grievance_form();
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('old', $this->input->post());
            $this->session->set_flashdata('error', validation_errors());
            if ($this->session->userdata('role') != '') {
                redirect(base_url('grm/admin/apply'));
                exit();
            } else {
                redirect(base_url('grm'));
                exit();
            }//End of if else
        }//End of if
        $this->load->helper('model');
        $dateOfReceipt = date('m-d-Y');
        $this->load->helper('model');
        $grievanceRefNum = checkAndGenerateUniqueId('GrievanceReferenceNumber', 'public_grievances');
        $name = $this->input->post('name', true);
        
        $inputs = [
            "GrievanceReferenceNumber" => $grievanceRefNum,
            "Name" => "RTPS-" . $name,
            "Gender" => $this->input->post('gender', true),
            "Address1" => $this->input->post('address1', true),
            "Address2" => $this->input->post('address2', true),
            "Address3" => $this->input->post('address3', true),
            "Pincode" => $this->input->post('pincode', true),
            "District" => $this->input->post('district', true),
            "State" => MY_STATE['state_code'],
            "Country" => MY_COUNTRY['country_code'],
            "EmailAddress" => $this->input->post('emailId', true),
            "MobileNumber" => $this->input->post('mobile_number', true),
            "Language" => "E",
            "SubjectContent" => $this->input->post('grievance_description', true),
            "DateOfReceipt" => $dateOfReceipt,
            "ComplainantIpAddress" => $_SERVER['REMOTE_ADDR'] == '::1' ? '127.0.0.1' : $_SERVER['REMOTE_ADDR'],
            "ExServicemen" => 'N',
            "AutoForwardOrgCode" => $departmentCode
        ];
        if (is_uploaded_file($_FILES['grievance_attachments']['tmp_name'])) {
            $redirectTo = ($this->session->userdata('role') != '') ? base_url('grm/admin/apply') : base_url('grm');
            $uploadedFile = $this->upload_attachment('grievance_attachments', FCPATH . 'storage/uploads/grievance/attachments', $redirectTo);
            $inputs['Document'] = base64_encode(file_get_contents($uploadedFile['upload_data']['full_path']));
        }
        $userData = [
            "name" => $name,
            "gender" => $this->input->post('gender', true),
            "address1" => $this->input->post('address1', true),
            "address2" => $this->input->post('address2', true),
            "address3" => $this->input->post('address3', true),
            "pincode" => $this->input->post('pincode', true),
            "district" => $this->input->post('district', true),
            "email_address" => $this->input->post('emailId', true),
            'updated_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
        ];

        $this->load->model('verified_userdata_model');
        $this->verified_userdata_model->update_where(['mobile_number' => $this->input->post('mobile_number')], $userData);
               
        if(in_array($grievance_category, $cpgramServices)) {
            $this->load->library('CPGRMS_api_client', ['type' => 'register-grievance']);
            $apiResponse = $this->cpgrms_api_client->post($inputs);
            $apiResponseObj = json_decode($apiResponse);
            if (!property_exists($apiResponseObj, 'registration_no') || $apiResponseObj->registration_no === '') {
                $this->session->set_flashdata('fail', 'Unable to submit grievance!!! Please try again later.');
                $this->session->set_flashdata('error', $apiResponseObj->result);
                $this->session->set_flashdata('old', $this->input->post());
                if ($this->session->userdata('role') != '') {
                    redirect(base_url('grm/admin/apply'));
                    exit();
                } else {
                    redirect(base_url('grm'));
                    exit();
                }
            }//End of if
            $inputs['registration_no'] = $apiResponseObj->registration_no;
            $inputs['result'] = $apiResponseObj->result;
            $inputs['data_location'] = "RTPS_AND_CPGRAM";
        } else {
            $inputs['registration_no'] = $this->getID(5);
            $inputs['result'] = 'Grievance lodged successfully';
            $inputs['data_location'] = "RTPS_ONLY";
        }//End of if else       
        
        $inputs['DateOfReceipt'] = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000);
        if ($this->session->userdata('role') != '') {
            switch ($this->session->userdata()) {
                case 'HD':
                    $inputs['complaintSource'] = 'Help Desk';
                    break;
                case 'PFC':
                    $inputs['complaintSource'] = 'PFC';
                    break;
                default:
                    $inputs['complaintSource'] = $this->session->userdata('role')->role_name;
                    break;
            }
            $inputs['userId'] = new ObjectId($this->session->userdata('userId')->{'$id'});
            $inputs['userType'] = $this->session->userdata('role')->slug;
        } else {
            $inputs['complaintSource'] = 'RTPS Portal';
        }
        $inputs['grievanceCategory'] = $this->input->post('grievance_category', true);
        if (in_array($grievance_category, $cpgramServices)) {
            $inputs['serviceId'] = $this->input->post('service_name', true);
        } else {
            $inputs['serviceId'] = '';
        }
        if (isset($_FILES['grievance_attachments']['name']) && isset($uploadedFile)) {
            $inputs['Document'] = $uploadedFile['upload_data']['file_name'];
        }
        
        $inputs["refno"] = $this->input->post('refno', true);
        $inputs["rtpsrefno"] = $this->input->post('rtpsrefno', true);
        
        $this->public_grievance_model->insert($inputs);
        
        $viewStatusInputs = [];
        if(in_array($grievance_category, $cpgramServices)) {
            $inputsR = [
                'RegistrationNumber' => $inputs['registration_no'],
                'EmailOrMobile' => $inputs['MobileNumber']
            ];
            $this->cpgrms_api_client->set_apiURL(['type' => 'view-status']);
            $apiResponseR = $this->cpgrms_api_client->post($inputsR);
            $apiResponseRObj = json_decode($apiResponseR);            
            if (property_exists($apiResponseRObj, 'RegistrationNumber')) {                
                if (isset($apiResponseRObj->GrievanceDocument)) {
                    $file_info = new finfo(FILEINFO_MIME_TYPE);
                    $mime_type = $file_info->buffer(base64_decode($apiResponseRObj->GrievanceDocument));
                    if ($mime_type == 'application/pdf') {
                        $filePath = FCPATH . 'storage/uploads/grievance/attachments/';
                        $fileName = isset($viewStatusLocal) ? $viewStatusLocal->grievance_doc_name : uniqid() . '.pdf';
                        file_put_contents($filePath . $fileName, base64_decode($apiResponseRObj->GrievanceDocument));
                        $viewStatusInputs['grievance_doc_name'] = $fileName;
                    }
                }
                if (isset($apiResponseRObj->ReplyDocument)) {
                    $file_info = new finfo(FILEINFO_MIME_TYPE);
                    $mime_type = $file_info->buffer(base64_decode($apiResponseRObj->ReplyDocument));
                    if ($mime_type == 'application/pdf') {
                        $filePath = FCPATH . 'storage/uploads/grievance/reply_document/';
                        $fileName = isset($viewStatusLocal) ? $viewStatusLocal->reply_doc_name : uniqid() . '.pdf';
                        file_put_contents($filePath . $fileName, base64_decode($apiResponseRObj->ReplyDocument));
                        $viewStatusInputs['reply_doc_name'] = $fileName;
                    }
                }
                unset($apiResponseRObj->GrievanceDocument);
                unset($apiResponseRObj->ReplyDocument);
                $viewStatusInputs = array_merge((array) $apiResponseRObj, $viewStatusInputs);        
            }//End of if
            $this->session->set_userdata('grievanceId', $apiResponseObj->registration_no);
        } else {
            $viewStatusInputs = array_merge($inputs, $viewStatusInputs);
            $this->session->set_userdata('grievanceId', $inputs['registration_no']);
        }//End of if else
        
        $viewStatusInputs['DateOfReceipt'] = new MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y')) * 1000);
        $viewStatusInputs['DateOfAction'] = new MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y')) * 1000);
        $viewStatusInputs['created_at'] = new MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y')) * 1000);
        $this->view_status_model->insert($viewStatusInputs);
        
        $this->sendSms($inputs['MobileNumber'], $name, $inputs['registration_no']);
        redirect('grm/ack');
    }//End of submit()
    
    public function acknowledgement() {
        $data = ['grievanceId' => $this->session->userdata('grievanceId')];

        if ($this->session->userdata('role') != '') {
            $this->load->view('includes/header');
            $this->load->view('grievances/admin/ack', $data);
            $this->load->view('includes/footer');
        } else {
            $this->load->view('includes/frontend/header');
            $this->load->view('grievances/public/ack', $data);
            $this->load->view('includes/frontend/footer');
        }
    }//End of acknowledgement()

    public function view_status() {
        $data = ['grievanceId' => $this->session->userdata('grievanceId')];
        $this->load->view('includes/frontend/header');
        $this->load->view('grievances/public/status', $data);
        $this->load->view('includes/frontend/footer');
    }//End of view_status()

    public function fetch_status() {
        $this->form_validation->set_rules('registration_number', 'Registration Number', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('email_or_mobile', 'Email or Mobile', 'trim|required|xss_clean|strip_tags|max_length[250]'); // |regex_match[/^([\w-\.]+)@((?:[A-Za-z\-]+\.)+)([A-Za-z]{2,15})|\d{10}$/]
        if ($this->form_validation->run() == FALSE) {
            $status["status"] = false;
            $status["msg"] = validation_errors();
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($status));
        }
        $inputs = [
            'RegistrationNumber' => urldecode($this->input->post('registration_number')),
            'EmailOrMobile' => $this->input->post('email_or_mobile')
        ];
        $this->load->library('CPGRMS_api_client', ['type' => 'view-status']);
        $apiResponse = $this->cpgrms_api_client->post($inputs);
        $apiResponseObj = json_decode($apiResponse);
        $viewStatusLocal = $this->view_status_model->first_where(['RegistrationNumber' => $this->input->post('registration_number')]);
        if (property_exists($apiResponseObj, 'RegistrationNumber')) {
            $viewStatusInputs = [];
            if (isset($apiResponseObj->GrievanceDocument)) {
                $file_info = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $file_info->buffer(base64_decode($apiResponseObj->GrievanceDocument));
                if ($mime_type == 'application/pdf') {
                    $filePath = FCPATH . 'storage/uploads/grievance/attachments/';
                    $fileName = isset($viewStatusLocal) ? $viewStatusLocal->grievance_doc_name : uniqid() . '.pdf';
                    file_put_contents($filePath . $fileName, base64_decode($apiResponseObj->GrievanceDocument));
                    $viewStatusInputs['grievance_doc_name'] = $fileName;
                }
            }
            if (isset($apiResponseObj->ReplyDocument)) {
                $file_info = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $file_info->buffer(base64_decode($apiResponseObj->ReplyDocument));
                if ($mime_type == 'application/pdf') {
                    $filePath = FCPATH . 'storage/uploads/grievance/reply_document/';
                    $fileName = isset($viewStatusLocal) ? $viewStatusLocal->reply_doc_name : uniqid() . '.pdf';
                    file_put_contents($filePath . $fileName, base64_decode($apiResponseObj->ReplyDocument));
                    $viewStatusInputs['reply_doc_name'] = $fileName;
                }
            }

            unset($apiResponseObj->GrievanceDocument);
            unset($apiResponseObj->ReplyDocument);
            $viewStatusInputs = array_merge((array) $apiResponseObj, $viewStatusInputs);
            $viewStatusInputs['DateOfReceipt'] = new MongoDB\BSON\UTCDateTime(strtotime($viewStatusInputs['DateOfReceipt']) * 1000);
            $viewStatusInputs['DateOfAction'] = new MongoDB\BSON\UTCDateTime(strtotime($viewStatusInputs['DateOfAction']) * 1000);

            $viewStatusLocal = $this->view_status_model->first_where(['RegistrationNumber' => $apiResponseObj->RegistrationNumber]);

            if (empty($viewStatusLocal)) {
                $viewStatusInputs['created_at'] = new MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y')) * 1000);
                $this->view_status_model->insert($viewStatusInputs);
            } else {
                $viewStatusInputs['updated_at'] = new MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y')) * 1000);
                $this->view_status_model->update_where(['RegistrationNumber' => $apiResponseObj->RegistrationNumber], $viewStatusInputs);
            }
            $status["status"] = true;
            $status["msg"] = 'Status successfully fetched.';
            $status["data"] = $viewStatusInputs;
        } elseif (isset($viewStatusLocal)) {
            unset($apiResponseObj->GrievanceDocument);
            unset($apiResponseObj->ReplyDocument);
            $status["status"] = true;
            $status["msg"] = 'Status successfully fetched from cache.';
            $status["data"] = $viewStatusLocal;
        } else {
            $status["status"] = false;
            $status["msg"] = 'Unable to fetch status. Please try again.';
        }

        if (isset($status['data'])) {
            foreach ($status['data'] as $field => $item) {
                if ($field == 'DateOfReceipt') {
                    $status['data'][$field] = date('d-m-Y g:i a', strtotime($this->mongo_db->getDateTime($item)));
                }
                if ($field == 'DateOfAction') {
                    $status['data'][$field] = date('d-m-Y g:i a', strtotime($this->mongo_db->getDateTime($item)));
                }
            }
        }
        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($status));
    }//End of fetch_status()

    public function send_otp() {
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|xss_clean|strip_tags|min_length[10]|max_length[10]|regex_match[/^[6-9]\d{9}$/]');
        if ($this->form_validation->run() == FALSE) {
            $status["status"] = false;
            $status["msg"] = validation_errors();
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($status));
        }
        $this->load->library('sms');
        $otpMsg = "Dear user, your OTP for EODB portal is {{otp}}. OTP will expire in 10 minutes. On expiry of time please regenerate the OTP";
        $mobileNumber = $this->input->post('mobile_number', true);
        $this->load->config('sms_template');
        $msgTemplate = $this->config->item('otp');
        $this->sms->send_otp($mobileNumber, $mobileNumber, $msgTemplate);
        $status["status"] = true;
        $status["msg"] = 'OTP successfully sent to your mobile number.';
        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($status));
    }//End of send_otp()

    public function verify() {
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|xss_clean|strip_tags|min_length[10]|max_length[10]|regex_match[/^[6-9]\d{9}$/]');
        $this->form_validation->set_rules('otp', 'OTP', 'trim|required|xss_clean|strip_tags|min_length[6]|max_length[6]');
        if ($this->form_validation->run() == FALSE) {
            $status["status"] = false;
            $status["msg"] = validation_errors();

            $this->session->userdata('isMobileVerified', $status['status']);
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($status));
        }
        $this->load->library('sms');
        $otpVerificationStatus = $this->sms->verify_otp($this->input->post('mobile_number', true), $this->input->post('mobile_number', true), $this->input->post('otp', true));

        if ($otpVerificationStatus['status'] == true) {
            // Verified data are stored for future reference
            $userData = [
                'mobile_number' => $this->input->post('mobile_number', true),
                'verified_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];
            $this->load->model('verified_userdata_model');
            $this->verified_userdata_model->insert($userData);

            $status["status"] = true;
            $status["msg"] = 'Mobile Number verified successfully.';

            // get current session verified numbers

            if ($this->session->has_userdata('verified_mobile_numbers')) {
                $temp = $this->session->userdata('verified_mobile_numbers');
                array_push($temp, $this->input->post('mobile_number', true));
                $this->session->set_userdata('verified_mobile_numbers', $temp);
            } else {
                // add verified numbers to current session
                $verified_mobile_numbers[] = $this->input->post('mobile_number', true);
                $this->session->set_userdata('verified_mobile_numbers', $verified_mobile_numbers);
            }
        } else {
            $status = $otpVerificationStatus;
        }
        $this->session->userdata('isMobileVerified', $status['status']);
        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($status));
    }//End of verify()

    public function check_user_data($metaKey, $metaValue) {
        $verified_mobile_numbers = [];
        if ($this->session->has_userdata('verified_mobile_numbers')) {
            $verified_mobile_numbers = $this->session->userdata('verified_mobile_numbers');
        }
        $params = [
            $metaKey => $metaValue
        ];
        $this->load->model('verified_userdata_model');
        $result = $this->verified_userdata_model->first_where($params);

        if (!empty((array) $result)) {
            $status = [
                'status' => false,
                'msg' => $metaKey . ' verified.',
                "name" => $result->name ?? '',
                "gender" => $result->gender ?? '',
                "address1" => $result->address1 ?? '',
                "address2" => $result->address2 ?? '',
                "address3" => $result->address3 ?? '',
                "pincode" => $result->pincode ?? '',
                "district" => $result->district ?? '',
                "email_address" => $result->email_address ?? '',
            ];
            if (in_array($metaValue, $verified_mobile_numbers)) {
                $status['status'] = true;
            }
        } else {
            $status = ['status' => false];
        }
        $this->session->set_userdata('isMobileVerified', $status['status']);
        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($status));
    }//End of check_user_data()

    private function validate_grievance_form(): void {
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('address1', 'Address 1', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pincode', 'Pincode', 'trim|required|xss_clean|strip_tags|regex_match[/^[0-9]\d{5}$/]|max_length[6]');
        $this->form_validation->set_rules('emailId', 'Email ID', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|xss_clean|strip_tags|min_length[10]|max_length[10]|regex_match[/^[6-9]\d{9}$/]');
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|xss_clean|strip_tags|min_length[10]|max_length[10]|regex_match[/^[6-9]\d{9}$/]');
        $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('grievance_category', 'GRM Category', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('grievance_description', 'GRM Description', 'trim|required|max_length[4000]|xss_clean|strip_tags');
        $this->form_validation->set_rules('grievance_attachments', 'GRM Attachments', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('refno', 'Do you have a sewasetu application reference number', 'trim|required|xss_clean|strip_tags');
    }//End of validate_grievance_form()

    private function upload_attachment($fileName, $filePath, $redirectTo = null) {
        $config['upload_path'] = $filePath;
        $config['encrypt_name'] = TRUE;
        $config['allowed_types'] = 'pdf|bmp';
        $config['max_size'] = 2048;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($fileName)) {
            if (isset($redirectTo)) {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect($redirectTo);
                exit();
            } else {
                return false;
            }
        } else {
            return array('upload_data' => $this->upload->data());
        }
    }//End of upload_attachment()

    public function my_grievance() {
        $this->load->view('includes/header');
        $this->load->view('grievances/my_grievance');
        $this->load->view('includes/footer');
    }//End of my_grievance()

    public function get_records() {
        $userId = $this->session->userdata('userId')->{'$id'};
        $this->load->model('public_grievance_model');
        $columns = [
            0 => "registration_no",
            1 => "GrievanceReferenceNumber",
            2 => "MobileNumber",
            3 => "DateOfReceipt",
        ];
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $filter = [
            '$and' => [
                [
                    'userId' => ['$eq' => new ObjectId($userId)],
                    'userType' => ['$eq' => 'PFC'],
                ]
            ]
        ];
        if (empty($this->input->post("search")["value"])) {
            $totalFiltered = $this->public_grievance_model->search_selected_rows($limit, $start, $filter, $order, $dir, $columns);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $filter['$or'] = [
                ["registration_no" => ['$regex' => $search, '$options' => "i"]],
                ["GrievanceReferenceNumber" => ['$regex' => $search, '$options' => "i"]],
                ["MobileNumber" => ['$regex' => $search, '$options' => "i"]],
                ["DateOfReceipt" => ['$regex' => $search, '$options' => "i"]]
            ];
            $totalFiltered = $this->public_grievance_model->search_selected_rows($limit, $start, $filter, $order, $dir, $columns);
        }
        foreach ((array) $totalFiltered as $key => $row) {
            foreach ($row as $field => $item) {
                if ($field == 'DateOfReceipt') {
                    $totalFiltered->{$key}->{$field} = date('d-m-Y', strtotime($this->mongo_db->getDateTime($item)));
                }
            }
        }
        $json_data = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => intval(count((array) $totalFiltered)),
            "recordsFiltered" => intval(count((array) $totalFiltered)),
            "data" => (array) $totalFiltered,
        );
        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($json_data));
    }//End of get_records()

    public function admin_view_status() {
        $this->load->view('includes/header');
        $this->load->view('grievances/admin/status');
        $this->load->view('includes/footer');
    }//End of admin_view_status()

    public function fetch_related_dept_by_service($serviceId) {
        $this->load->model('services_model');
        $serviceWithDepartment = $this->services_model->get_department_by_service_id($serviceId);
        if (!empty((array) $serviceWithDepartment) && property_exists($serviceWithDepartment, 'department')) {
            $data = [
                'status' => true,
                'departmentName' => $serviceWithDepartment->department->department_name
            ];
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($data));
        } else {
            $data = [
                'status' => false,
            ];
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($data));
        }
    }//End of fetch_related_dept_by_service()

    public function refresh_captcha() {
        if ($this->input->is_ajax_request()) {
            $this->load->helper('captcha');
            $cap = generate_n_store_captcha();
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array(
                                'captcha' => $cap,
                                'status' => true,
            )));
        }
    }//End of refresh_captcha()

    public function admin_apply() {
        $this->load->model('district_model');
        $districtList = $this->district_model->get_by_desc();
        $this->load->model('services_model');
        $serviceList = $this->services_model->all();
        $data = [
            'districtList' => $districtList,
            'serviceList' => $serviceList,
            'old' => $this->session->flashdata('old') ?? [],
            'sessionUserRole' => $this->session->userdata('role')
        ];
        $this->load->view('includes/header');
        $this->load->view('grievances/admin/index', $data);
        $this->load->view('includes/footer');
    }//End of admin_apply()

    public function get_records_by_mobile() {

        $mobileNumber = $this->input->post('mobileNumber');
        $columns = array(
            0 => "RegistrationNumber",
            1 => "Name",
            2 => "grievanceCategory",
            3 => "DateOfReceipt"
        );

        $filterArray["MobileNumber"] = $mobileNumber;
        $limit = $this->input->post("length", TRUE);
        $start = $this->input->post("start", TRUE);
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = ($this->input->post("order")[0]["dir"] === 'asc') ? 1 : -1;
        if (empty($this->input->post("search")["value"])) {
            $records = $this->public_grievance_model->get_with_status($filterArray, [], $start, $limit, [$order => $dir]);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $searchArray = [
                'registration_no' => $search,
                'Name' => $search,
                'grievanceCategory' => $search,
                'DateOfReceipt' => $search,
                'CurrentStatus' => $search,
            ];
            $records = $this->public_grievance_model->get_with_status($filterArray, $searchArray, $start, $limit, [$order => $dir]);
        }
        $totalFiltered = count((array) $this->public_grievance_model->get_with_status($filterArray));
        $data = array();
        if (!empty($records)) {
            $sl_no = 0;
            foreach ($records as $row) {
                $nestedData["#"] = ++$sl_no;
                $nestedData["RegistrationNumber"] = $row->registration_no;
                $nestedData["Name"] = $row->Name;
                $grievanceCategory = property_exists($row, 'grievanceCategory') ? $row->grievanceCategory : 'NA';
                $nestedData["grievanceCategory"] = get_grievance_category_text_by_slug($grievanceCategory);
                $nestedData["DateOfReceipt"] = date('d-m-Y g:i a', intval(strval($row->DateOfReceipt)) / 1000);

                switch ($row->grievance_data->CurrentStatus) {
                    case 'Grievance received':
                        $nestedData["CurrentStatus"] = '<span class="badge bg-info">' . $row->grievance_data->CurrentStatus . '</span>';
                        break;
                    case 'Case closed':
                        $nestedData["CurrentStatus"] = '<span class="badge bg-success">' . $row->grievance_data->CurrentStatus . '</span>';
                        break;
                    default:
                        $nestedData["CurrentStatus"] = '<span class="badge bg-warning">' . $row->grievance_data->CurrentStatus . '</span>';
                        break;
                }

                $nestedData["Action"] = '<button class="btn btn-sm btn-outline-primary" onclick="showGrievanceDetails(\'' . urlencode($row->registration_no) . '\',\'' . $mobileNumber . '\')"> <i class="fa fa-eye"></i> View Status</button>';
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        echo json_encode($json_data);
    }//End of get_records_by_mobile()

    public function sendSms($number, $name, $refNo) {
        $message_body = "Dear " . $name . ", your grievance in the RTPS portal has been registered successfully with reference no " . $refNo . ". Please click the link below to track the status of your application. https://rtps.assam.gov.in/grm/trackstatus";
        $dlt_template_id = '1007163825188649244';
        $ch = curl_init();
        $message_body = str_replace(" ", "%20", $message_body);
        $url = "https://smsgw.sms.gov.in/failsafe/MLink?username=rtpsasm.otp&pin=P4%26fO3%23xF4&message=" . $message_body . "&mnumber=" . $number . "&signature=ARTPSA&dlt_entity_id=1001367290000017171&dlt_template_id=" . $dlt_template_id;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $head;
    }//End of sendSms()
    

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
}
