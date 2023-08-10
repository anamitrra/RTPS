
<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Actions extends Upms
{

    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->isloggedin();
        $this->load->model('upms/roles_model');
        $this->load->model('upms/levels_model');
        $this->load->model('upms/users_model');
        $this->load->model('upms/applications_model');
        $this->load->helper("cifileupload");
        $this->load->model('duplicatecertificateahsec/ahsecmarksheet_model');
        $this->load->model('duplicatecertificateahsec/ahsecregistration_model');
        $this->load->model('duplicatecertificateahsec/ahsecolddata_model');
        $this->load->model('migrationcertificateahsec/registration_model');
        $this->load->library('../modules/spservices/controllers/office/sms_scheduler');
        $this->load->library('phpqrcode/qrlib');
    }

    public function fetchsessions($no_of_year)
    {
        $currentYear = date('Y');
        $yearArray = array();
        for ($i = $currentYear; $i >= ($currentYear - $no_of_year); $i--) {
            $present_year = $i;
            $next_year = sprintf("%02d", (substr($i, -2) + 1));
            $yearArray[$i . '-' . ($next_year)] = $i . '-' . ($next_year);
        }
        return $yearArray;
    }

    public function preview_certificate($appl_no = null)
    {
        $certificate_no = $this->getID($appl_no);
        $updateCertificateNo = [
            'form_data.certificate_no' => $certificate_no,
        ];
        $this->applications_model->update_where(['service_data.appl_ref_no' => $appl_no], $updateCertificateNo);
        $qrcode_path = 'storage/docs/common_qr/';
        $pathname = FCPATH . $qrcode_path;
        if (!is_dir($pathname)) {
            mkdir($pathname, 0777, true);
            file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
        }
        $pdata = (array) $this->application_model->get_single_application($appl_no);
        // $link = base_url('spservices/verify-certificate/' . $pdata[0]->_id->{'$id'});
        $link = base_url('https://sewasetu.assam.gov.in/');
        $filename = str_replace("/", "-", $pdata[0]->_id->{'$id'});
        $qrname = $filename . ".png";
        $file_name = $qrcode_path . $qrname;
        QRcode::png($link, $file_name);
        $base64_certificate_path = base64_encode('spservices/migrationcertificateahsec/actions/download_certificate/' . $pdata[0]->_id->{'$id'});

        $this->load->view('upms/includes/header');
        if ($this->extractServiceId($appl_no) == "AHSECMIGR") {
            $this->load->view('migrationcertificateahsec/certificate', array('dbrow' => $pdata[0], 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'user_type' => 'official'));
        }
        $this->load->view('upms/includes/footer');
    }

    public function getID($appl_no)
    {
        $certificate_no = $this->generateID($appl_no);
        while ($this->application_model->get_row(["form_data.certificate_no" => $certificate_no])) {
            $certificate_no = $this->generateID($appl_no);
        } //End of while()
        return $certificate_no;
    } //End of getID()

    public function generateID($appl_no)
    {
        $number = '';
        for ($i = 0; $i < 7; $i++) {
            $number .= rand(0, 9);
        }
        $str = $this->extractServiceId($appl_no) . "/" . date('Y') . "/" . $number;
        return $str;
    } //End of generateID()

    public function extractServiceId($appl_ref_no)
    {
        $pattern = '/RTPS-([A-Z]+)\/\d{4}\/\d+/'; // Regular expression pattern

        if (preg_match($pattern, $appl_ref_no, $matches)) {
            // $matches[0] will contain the entire matched pattern
            // $matches[1] will contain the service ID captured by the first group in parentheses
            return $matches[1];
        } else {
            return "Service ID not found";
        }
    }

    public function download_certificate($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        $pdata[] = $dbRow;
        $appl_no = $dbRow->service_data->appl_ref_no;

        //$pdata = (array)$this->application_model->get_single_application($appl_no);
        $qrcode_path = 'storage/docs/common_qr/';
        $filename = str_replace("/", "-", $pdata[0]->{'_id'}->{'$id'});
        $qrname = $filename . ".png";
        $file_name = $qrcode_path . $qrname;

        $filter1 = array(
            "registration_number" => $pdata[0]->form_data->ahsec_reg_no,
            "registration_session" => $pdata[0]->form_data->ahsec_reg_session,
        );
        $ahsecregistration_data = $this->ahsecregistration_model->get_row($filter1);

        $this->load->view('upms/includes/header');
        if ($this->extractServiceId($appl_no) == "AHSECMIGR") {
            $this->load->view('migrationcertificateahsec/certificate', array('dbrow' => $pdata[0], 'qr' => $file_name, 'certificate_no' => $pdata[0]->form_data->certificate_no, 'certificate_path' => $pdata[0]->form_data->certificate, 'qr' => $file_name, 'user_type' => '', 'reg_data' => $ahsecregistration_data));
        }
        $this->load->view('upms/includes/footer');
    }

    public function app_pre_admin($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        
        $filter1 = array(
            "registration_number" => $dbRow->form_data->ahsec_reg_no,
            "registration_session" => $dbRow->form_data->ahsec_reg_session,
        );

        $reg_data_cnt = $this->ahsecregistration_model->rows_count($filter1);
        $reg_data = $this->ahsecregistration_model->get_row($filter1);
        $commencing_dates = $this->registration_model->getCommencings();

        if (isset($ahsecmarksheet_data->Stream)) {
            $filter_strem = array(
                "stream" => $ahsecmarksheet_data->Stream ?? null,
            );
            $subjects = $this->registration_model->get_stream_subjects($filter_strem);
        } else {
            $subjects = $this->registration_model->getSubjects();
        }
       
        $this->load->view('upms/includes/header');
        $this->load->view('migrationcertificateahsec/edit_admin_preview', array('dbrow' => $dbRow, 'reg_data' => $reg_data, 'commencing_dates' => $commencing_dates, 'reg_data_cnt' => $reg_data_cnt, 'subjects' => $subjects));
        $this->load->view('upms/includes/footer');

    }

    public function eCopy_preview($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        $pdata[] = $dbRow;
        $appl_no = $dbRow->service_data->appl_ref_no;

        $filter1 = array(
            "registration_number" => $pdata[0]->form_data->ahsec_reg_no,
            "registration_session" => $pdata[0]->form_data->ahsec_reg_session,
        );
        $ahsecregistration_data = $this->ahsecregistration_model->get_row($filter1);

        $this->load->view('upms/includes/header');
        if ($this->extractServiceId($appl_no) == "AHSECMIGR") {
            $this->load->view('migrationcertificateahsec/certificate', array('dbrow' => $pdata[0], 'qr' => '', 'certificate_no' => $pdata[0]->form_data->certificate_no ?? '', 'certificate_path' => $pdata[0]->form_data->certificate ?? '', 'user_type' => '', 'reg_data' => $ahsecregistration_data));
        }
        $this->load->view('upms/includes/footer');

    }

    public function update_reg_master_data($objId = null)
    {
        // pre($this->input->post());
        $this->form_validation->set_rules('sl_no', 'Sl No', 'trim|required|xss_clean|strip_tags|max_length[255]|numeric');
        $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required|xss_clean|strip_tags|max_length[255]|numeric');
        $this->form_validation->set_rules('code_no', 'Code No', 'trim|required|xss_clean|strip_tags|max_length[255]|numeric');
        $this->form_validation->set_rules('candidate_name', 'Candidate Name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('father_name', 'Father\'s Name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mother_name', 'Mother\'s Name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required|xss_clean|strip_tags|min_length[10]|max_length[10]|numeric');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('institution_name', 'Institution Name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('reg_no', 'Registration Number', 'trim|required|xss_clean|strip_tags|max_length[255]|numeric');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $this->form_validation->set_rules('current_date', 'New Registered Date', 'required');
        $this->form_validation->set_rules('session', 'Session', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('core_sub_1', 'Core Subject 1', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('core_sub_2', 'Core Subject 2', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('elective_sub_3', 'Elective Subject 1', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('elective_sub_4', 'Elective Subject 2', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('elective_sub_5', 'Elective Subject 3', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('elective_sub_6', 'Elective Subject 4', 'trim|required|xss_clean|strip_tags|max_length[255]');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            redirect('spservices/migrationcertificateahsec/actions/app_pre_admin/' . $objId);
        } else {
          
            $this->add_old_data_history($objId, $this->input->post("reg_no"), $this->input->post("session"));

            $new_issue_date = explode('-', $this->input->post("date"));
            $new_issue_date1 = $new_issue_date[2] . "-" . $new_issue_date[1] . "-" . $new_issue_date[0];

            $form_data = [
                $form_data['sl_no'] = (int) $this->input->post("sl_no"),
                'institution_code' => (int) $this->input->post("code_no"),
                'candidate_name' => $this->input->post("candidate_name"),
                'father_name' => $this->input->post("father_name"),
                'mother_name' => $this->input->post("mother_name"),
                'gender' => $this->input->post("gender"),
                'institution_name' => $this->input->post("institution_name"),
                // 'registration_number' => (int) $this->input->post("reg_no"),
                'issue_date' => $new_issue_date1,
                'new_issued_date' => $this->input->post("current_date"),
                'mobile_no' => (int) $this->input->post("mobile_no"),
                'sub_1' => $this->input->post("core_sub_1"),
                'sub_2' => $this->input->post("core_sub_2"),
                'sub_3' => $this->input->post("elective_sub_3"),
                'sub_4' => $this->input->post("elective_sub_4"),
                'sub_5' => $this->input->post("elective_sub_5"),
                'sub_6' => $this->input->post("elective_sub_6"),
            ];

            $filter1 = array(
                "registration_number" => (int) $this->input->post("reg_no"),
                "registration_session" => $this->input->post("session"),
            );

            $this->ahsecregistration_model->update_where($filter1, $form_data);
           
            $filter22 = array(
                "Registration_No" => (int) $this->input->post("reg_no"),
                "Registration_Session" => $this->input->post("session"),
            );
            $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_rows($filter22);
            $ahsecmarksheet_data_cnt = $this->ahsecmarksheet_model->rows_count($filter22);

            if ($ahsecmarksheet_data_cnt > 0) {
                if ($ahsecmarksheet_data) {
                    foreach ($ahsecmarksheet_data as $key => $value) {

                        $this->add_old_data_history($objId, $this->input->post("reg_no"), $this->input->post("session"));

                        $filter23 = array(
                            "Registration_No" => (int) $this->input->post("reg_no"),
                            "Registration_Session" => $this->input->post("session"),
                            "Roll" => (int) $value->Roll,
                            "No" => (int) $value->No,
                            "Year_of_Examination" => (int) $value->Year_of_Examination,
                        );

                        $marks_data = [
                            'School_College_Name' => $this->input->post("institution_name"),
                            'Candidate_Name' => $this->input->post("candidate_name"),
                            'Father_Name' => $this->input->post("father_name"),
                            'Mother_Name' => $this->input->post("mother_name"),
                        ];

                        $this->ahsecmarksheet_model->update_where($filter23, $marks_data);
                    }
                }
            }
            
            $this->session->set_flashdata('success', 'Master record has been submitted successfully');
            redirect('spservices/migrationcertificateahsec/actions/app_pre_admin/' . $objId);
        }
    }

    public function insert_reg_master_data($objId = null)
    {
        // pre($this->input->post());
        $this->form_validation->set_rules('sl_no', 'Sl No', 'trim|required|xss_clean|strip_tags|max_length[255]|numeric');
        $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required|xss_clean|strip_tags|max_length[255]|numeric');
        $this->form_validation->set_rules('code_no', 'Code No', 'trim|required|xss_clean|strip_tags|max_length[255]|numeric');
        $this->form_validation->set_rules('candidate_name', 'Candidate Name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('father_name', 'Father\'s Name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required|xss_clean|strip_tags|min_length[10]|max_length[10]|numeric');
        $this->form_validation->set_rules('mother_name', 'Mother\'s Name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('institution_name', 'Institution Name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('reg_no', 'Registration Number', 'trim|required|xss_clean|strip_tags|max_length[255]|numeric');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $this->form_validation->set_rules('session', 'Session', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('core_sub_1', 'Core Subject 1', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('core_sub_2', 'Core Subject 2', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('elective_sub_3', 'Elective Subject 1', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('elective_sub_4', 'Elective Subject 2', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('elective_sub_5', 'Elective Subject 3', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('elective_sub_6', 'Elective Subject 4', 'trim|required|xss_clean|strip_tags|max_length[255]');

        if ($this->input->post("service_id") != "AHSECDRC") {

            $this->form_validation->set_rules('stream', 'Stream', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('roll', 'Roll', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('no', 'No', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('exam_year', 'Year of Examination', 'trim|required|xss_clean|strip_tags|max_length[255]');
        }

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            redirect('spservices/duplicatecertificateahsec/actions/app_pre_admin/' . $objId);
        } else {

            $filter1 = array(
                "registration_number" => (int) $this->input->post("reg_no"),
                "registration_session" => $this->input->post("session"),
            );
            $reg_data_cnt = $this->ahsecregistration_model->rows_count($filter1);

            // Insert data into reg. master database
            $form_data = [
                'institution_code' => (int) $this->input->post("code_no"),
                'candidate_name' => $this->input->post("candidate_name"),
                'father_name' => $this->input->post("father_name"),
                'mother_name' => $this->input->post("mother_name"),
                'gender' => $this->input->post("gender"),
                'institution_name' => $this->input->post("institution_name"),
                'registration_number' => (int) $this->input->post("reg_no"),
                "registration_session" => $this->input->post("session"),
                'issue_date' => $this->input->post("date"),
                'mobile_no' => (int) $this->input->post("mobile_no"),
                'sub_1' => $this->input->post("core_sub_1"),
                'sub_2' => $this->input->post("core_sub_2"),
                'sub_3' => $this->input->post("elective_sub_3"),
                'sub_4' => $this->input->post("elective_sub_4"),
                'sub_5' => $this->input->post("elective_sub_5"),
                'sub_6' => $this->input->post("elective_sub_6"),
                'photo_key_value' => 123456789,
                'new_inserted_data' => 1,
            ];
            if ($this->input->post("service_id") == "AHSECDRC") {
                $form_data['sl_no'] = (int) $this->input->post("sl_no");
            }
            $this->ahsecregistration_model->insert($form_data);
            // End

            // Insert data into marksheet master database
            if ($this->input->post("service_id") == "AHSECDRC") {
                $marks_data = [
                    "Registration_No" => (int) $this->input->post("reg_no"),
                    "Registration_Session" => $this->input->post("session"),
                    'School_College_Name' => $this->input->post("institution_name"),
                    'Candidate_Name' => $this->input->post("candidate_name"),
                    'Father_Name' => $this->input->post("father_name"),
                    'Mother_Name' => $this->input->post("mother_name"),
                    'new_inserted_data' => 1,
                ];
                $this->ahsecmarksheet_model->insert($marks_data);
            }

            if ($this->input->post("service_id") != "AHSECDRC") {
                $marks_data = [
                    "Registration_No" => (int) $this->input->post("reg_no"),
                    "Registration_Session" => $this->input->post("session"),
                    "Roll" => (int) $this->input->post("roll"),
                    "No" => (int) $this->input->post("no"),
                    "Year_of_Examination" => (int) $this->input->post("exam_year"),
                    'Stream' => $this->input->post("stream"),
                    'School_College_Name' => $this->input->post("institution_name"),
                    'Candidate_Name' => $this->input->post("candidate_name"),
                    'Father_Name' => $this->input->post("father_name"),
                    'Mother_Name' => $this->input->post("mother_name"),
                    'commencing_day' => $this->input->post("commencing_day"),
                    'commencing_month' => $this->input->post("commencing_month"),
                    'new_inserted_data' => 1,
                ];

                if ($this->input->post("service_id") == "AHSECDADM") {
                    $marks_data['Admit_Card_Serial_No'] = (int) $this->input->post("sl_no");
                }

                if ($this->input->post("service_id") == "AHSECDPC") {
                    $marks_data['Certificate_Serial_No'] = (int) $this->input->post("sl_no");
                }

                $this->ahsecmarksheet_model->insert($marks_data);
            }
            // End

            $this->session->set_flashdata('success', 'Master record has been submitted successfully');
            redirect('spservices/migrationcertificateahsec/actions/app_pre_admin/' . $objId);
        }
    }

    public function preview($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {

            $master_data = "";
            $filter = array(
                "registration_number" => (int) $dbRow->form_data->ahsec_reg_no,
                "registration_session" => $dbRow->form_data->ahsec_reg_session,
            );
            $master_data = $this->ahsecregistration_model->get_row($filter);


            $data = array(
                "dbrow" => $dbRow,
                "master_data" => $master_data,
                "pageTitle" => "Application for Migration Certificate",
                "PageTiteAssamese" => "প্ৰব্ৰজন প্ৰমাণপত্ৰৰ বাবে আবেদন"
            );


            $this->load->view('includes/frontend/header');
            $this->load->view('migrationcertificateahsec/preview_admin', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/migrationcertificateahsec/registration');
        } //End of if else
    }

    public function add_old_data_history($objId, $reg_no, $session)
    {
        //Store old data
        $registration_data = "";
        $filter33 = array(
            "registration_number" => (int) $reg_no,
            "registration_session" => $session,
        );
        $registration_data = $this->ahsecregistration_model->get_rows($filter33);

        if ($registration_data) {
            foreach ($registration_data as $key => $value) {
                unset($value->_id);
            }
        }

        $dbRow = $this->registration_model->get_by_doc_id($objId);

        $inputs = [
            'appl_ref_no' => $dbRow->service_data->appl_ref_no,
            'registration_data' => $registration_data,
        ];
        $this->ahsecolddata_model->insert($inputs);
        // End of Store old data
    }

    public function officeCopy($service_id = null, $obj_id = null)
    {
        if ($service_id == "AHSECMIGR") {
            $data = array("pageTitleId" => "AHSECMIGR", "pageTitle" => "Application for Migration Certificate");
        } else {
            pre("It's not a formatted record.");
        }

        $filter = array("_id" => new ObjectId($obj_id), "service_data.service_id" => $service_id);
        $data['data'] = $data;

        $data["dbrow"] = $this->registration_model->get_row($filter);
        $dbRow = $this->registration_model->get_row($filter);

        $filter1 = array(
            "Registration_No" => $dbRow->form_data->ahsec_reg_no,
            "Registration_Session" => $dbRow->form_data->ahsec_reg_session,
        );
        $ahsecmarksheet_data_cnt = $this->ahsecmarksheet_model->rows_count($filter1);
        if ($ahsecmarksheet_data_cnt > 1) {
            pre("We have found multiple record againts this Registration No: " . $dbRow->form_data->ahsec_reg_no . " and Registration_Session: " . $dbRow->form_data->ahsec_reg_session);
        } else {
            $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter1);
            $data["marksheet_data"] = $ahsecmarksheet_data;
            $this->load->view('migrationcertificateahsec/officecopy', $data);
        }
    }
}
