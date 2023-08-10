<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Actions extends Upms
{

    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->isloggedin();
        $this->load->model('duplicatecertificateahsec/ahsecregistration_model');
        $this->load->model('duplicatecertificateahsec/ahsecmarksheet_model');
        $this->load->model('duplicatecertificateahsec/registration_model');
        $this->load->model('duplicatecertificateahsec/ahsecolddata_model');
        $this->load->model('upms/roles_model');
        $this->load->model('upms/levels_model');
        $this->load->model('upms/users_model');
        $this->load->model('upms/applications_model');
        $this->load->helper("cifileupload");
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
        $qrcode_path = 'storage/docs/ahsec_common_qr/';
        $pathname = FCPATH . $qrcode_path;
        if (!is_dir($pathname)) {
            mkdir($pathname, 0777, true);
            file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
        }
        $pdata = (array) $this->application_model->get_single_application($appl_no);
        $link = base_url('spservices/ahsec-verify-certificate/' . $pdata[0]->_id->{'$id'});

        $filename = str_replace("/", "-", $pdata[0]->_id->{'$id'});
        $qrname = $filename . ".png";
        $file_name = $qrcode_path . $qrname;
        QRcode::png($link, $file_name);
        // pre($pdata);

        $base64_certificate_path = base64_encode('spservices/duplicatecertificateahsec/actions/download_certificate/' . $pdata[0]->_id->{'$id'});
        $filter1 = array(
            "registration_number" => $pdata[0]->form_data->ahsec_reg_no,
            "registration_session" => $pdata[0]->form_data->ahsec_reg_session,
        );
        $ahsecregistration_data = $this->ahsecregistration_model->get_row($filter1);

        if (($pdata[0]->service_data->service_id == "AHSECDADM") || ($pdata[0]->service_data->service_id == "AHSECDMRK") || ($pdata[0]->service_data->service_id == "AHSECDPC")) {
            $filter2 = array(
                "Registration_No" => $pdata[0]->form_data->ahsec_reg_no,
                "Registration_Session" => $pdata[0]->form_data->ahsec_reg_session,
                "Roll" => $pdata[0]->form_data->ahsec_admit_roll,
                "No" => $pdata[0]->form_data->ahsec_admit_no,
                "Year_of_Examination" => $pdata[0]->form_data->ahsec_yearofpassing,
            );
            $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);

            $marksheet_data['Mark_Sheet_No'] = $ahsecmarksheet_data->Mark_Sheet_No;
            $marksheet_data['Admit_Card_Serial_No'] = $ahsecmarksheet_data->Admit_Card_Serial_No;
            $marksheet_data['Certificate_Serial_No'] = $ahsecmarksheet_data->Certificate_Serial_No;
            $marksheet_data['Year_of_Examination'] = $ahsecmarksheet_data->Year_of_Examination;
            $marksheet_data['commencing_day'] = $ahsecmarksheet_data->commencing_day;
            $marksheet_data['commencing_month'] = $ahsecmarksheet_data->commencing_month;
            $marksheet_data['Stream'] = $ahsecmarksheet_data->Stream;
            $marksheet_data['Date'] = $ahsecmarksheet_data->Marksheet_Date;
            $marksheet_data['Candidate_Name'] = $ahsecmarksheet_data->Candidate_Name;
            $marksheet_data['School_Name'] = $ahsecmarksheet_data->School_College_Name;
            $marksheet_data['Roll'] = $ahsecmarksheet_data->Roll;
            $marksheet_data['No'] = $ahsecmarksheet_data->No;
            $marksheet_data['Remarks1'] = $ahsecmarksheet_data->Remarks1;
            $j = 1;
            for ($i = 1; $i <= 16; $i++) {
                if (!empty($ahsecmarksheet_data->{'Sub' . $i . '_Code'})) {
                    $marksheet_data['Sub' . $j . '_Code'] = $ahsecmarksheet_data->{'Sub' . $i . '_Code'};
                    $marksheet_data['Sub' . $j . '_Pap_Indicator'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pap_Indicator'};
                    $marksheet_data['Sub' . $j . '_Name'] = $ahsecmarksheet_data->{'Sub' . $i . '_Name'};
                    $marksheet_data['Sub' . $j . '_Th_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Th_Marks'};
                    $marksheet_data['Sub' . $j . '_Pr_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pr_Marks'};
                    $marksheet_data['Sub' . $j . '_Grace_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Grace_Marks'};
                    $marksheet_data['Sub' . $j . '_Tot_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Tot_Marks'};
                    $j++;
                }
            }
            $marksheet_data['total_sub'] = --$j;
            $marksheet_data['Total_Marks_in_Words'] = $ahsecmarksheet_data->Total_Marks_in_Words;
            $marksheet_data['Total_Marks_in_Figure'] = $ahsecmarksheet_data->Total_Marks_in_Figure;
            $marksheet_data['Total_Grace_in_Figure'] = $ahsecmarksheet_data->Total_Grace_in_Figure;
            $marksheet_data['Result'] = $ahsecmarksheet_data->Result;
            $marksheet_data['Remarks2'] = $ahsecmarksheet_data->Remarks2;
            $marksheet_data['ENVE_Grade'] = $ahsecmarksheet_data->ENVE_Grade;
            $marksheet_data['Core_Indicator'] = $ahsecmarksheet_data->Core_Indicator;
        }

        $this->load->view('upms/includes/header');
        if ($this->extractServiceId($appl_no) == "AHSECDRC") {
            $this->load->view('duplicatecertificateahsec/reg_output_certificate', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'user_type' => 'official'));
        } else if ($this->extractServiceId($appl_no) == "AHSECDADM") {
            $this->load->view('duplicatecertificateahsec/output_certificate_adm', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'official'));
        } else if ($this->extractServiceId($appl_no) == "AHSECDMRK") {
            $this->load->view('duplicatecertificateahsec/marksheet_output_certificate', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'official'));
        } else if ($this->extractServiceId($appl_no) == "AHSECDPC") {
            $this->load->view('duplicatecertificateahsec/output_certificate_pc', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'official'));
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
        $qrcode_path = 'storage/docs/ahsec_common_qr/';
        $filename = str_replace("/", "-", $pdata[0]->{'_id'}->{'$id'});
        $qrname = $filename . ".png";
        $file_name = $qrcode_path . $qrname;

        $filter1 = array(
            "registration_number" => $pdata[0]->form_data->ahsec_reg_no,
            "registration_session" => $pdata[0]->form_data->ahsec_reg_session,
        );
        $ahsecregistration_data = $this->ahsecregistration_model->get_row($filter1);

        if (($pdata[0]->service_data->service_id == "AHSECDADM") || ($pdata[0]->service_data->service_id == "AHSECDMRK") || ($pdata[0]->service_data->service_id == "AHSECDPC")) {
            $filter2 = array(
                "Registration_No" => $pdata[0]->form_data->ahsec_reg_no,
                "Registration_Session" => $pdata[0]->form_data->ahsec_reg_session,
                "Roll" => $pdata[0]->form_data->ahsec_admit_roll,
                "No" => $pdata[0]->form_data->ahsec_admit_no,
                "Year_of_Examination" => $pdata[0]->form_data->ahsec_yearofpassing,
            );
            $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);

            $marksheet_data['Mark_Sheet_No'] = $ahsecmarksheet_data->Mark_Sheet_No;
            $marksheet_data['Admit_Card_Serial_No'] = $ahsecmarksheet_data->Admit_Card_Serial_No;
            $marksheet_data['Certificate_Serial_No'] = $ahsecmarksheet_data->Certificate_Serial_No;
            $marksheet_data['Year_of_Examination'] = $ahsecmarksheet_data->Year_of_Examination;
            $marksheet_data['commencing_day'] = $ahsecmarksheet_data->commencing_day;
            $marksheet_data['commencing_month'] = $ahsecmarksheet_data->commencing_month;
            $marksheet_data['Stream'] = $ahsecmarksheet_data->Stream;
            $marksheet_data['Date'] = $ahsecmarksheet_data->Marksheet_Date;
            $marksheet_data['Candidate_Name'] = $ahsecmarksheet_data->Candidate_Name;
            $marksheet_data['School_Name'] = $ahsecmarksheet_data->School_College_Name;
            $marksheet_data['Roll'] = $ahsecmarksheet_data->Roll;
            $marksheet_data['No'] = $ahsecmarksheet_data->No;
            $marksheet_data['Remarks1'] = $ahsecmarksheet_data->Remarks1;
            $j = 1;
            for ($i = 1; $i <= 16; $i++) {
                if (!empty($ahsecmarksheet_data->{'Sub' . $i . '_Code'})) {
                    $marksheet_data['Sub' . $j . '_Code'] = $ahsecmarksheet_data->{'Sub' . $i . '_Code'};
                    $marksheet_data['Sub' . $j . '_Pap_Indicator'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pap_Indicator'};
                    $marksheet_data['Sub' . $j . '_Name'] = $ahsecmarksheet_data->{'Sub' . $i . '_Name'};
                    $marksheet_data['Sub' . $j . '_Th_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Th_Marks'};
                    $marksheet_data['Sub' . $j . '_Pr_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pr_Marks'};
                    $marksheet_data['Sub' . $j . '_Grace_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Grace_Marks'};
                    $marksheet_data['Sub' . $j . '_Tot_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Tot_Marks'};
                    $j++;
                }
            }
            $marksheet_data['total_sub'] = --$j;
            $marksheet_data['Total_Marks_in_Words'] = $ahsecmarksheet_data->Total_Marks_in_Words;
            $marksheet_data['Total_Marks_in_Figure'] = $ahsecmarksheet_data->Total_Marks_in_Figure;
            $marksheet_data['Total_Grace_in_Figure'] = $ahsecmarksheet_data->Total_Grace_in_Figure;
            $marksheet_data['Result'] = $ahsecmarksheet_data->Result;
            $marksheet_data['Remarks2'] = $ahsecmarksheet_data->Remarks2;
            $marksheet_data['ENVE_Grade'] = $ahsecmarksheet_data->ENVE_Grade;
            $marksheet_data['Core_Indicator'] = $ahsecmarksheet_data->Core_Indicator;
        }

        $this->load->view('upms/includes/header');
        if ($this->extractServiceId($appl_no) == "AHSECDRC") {
            $this->load->view('duplicatecertificateahsec/reg_output_certificate', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $pdata[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'user_type' => null));
        } else if ($this->extractServiceId($appl_no) == "AHSECDADM") {
            $this->load->view('duplicatecertificateahsec/output_certificate_adm', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $pdata[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
        } else if ($this->extractServiceId($appl_no) == "AHSECDMRK") {
            $this->load->view('duplicatecertificateahsec/marksheet_output_certificate', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $pdata[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
        } else if ($this->extractServiceId($appl_no) == "AHSECDPC") {
            $this->load->view('duplicatecertificateahsec/output_certificate_pc', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $pdata[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
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

        $ahsecmarksheet_data = [];
        $marksheet_data_cnt = 0;
        if ($dbRow->service_data->service_id != "AHSECDRC") {
            $filter2 = array(
                "Registration_No" => $dbRow->form_data->ahsec_reg_no,
                "Registration_Session" => $dbRow->form_data->ahsec_reg_session,
                "Roll" => (int) $dbRow->form_data->ahsec_admit_roll,
                "No" => (int) $dbRow->form_data->ahsec_admit_no,
                "Year_of_Examination" => (int) $dbRow->form_data->ahsec_yearofpassing,
            );

            if ($reg_data) {
                if (isset($reg_data->new_inserted_data)) {
                    $filter2 = array(
                        "Registration_No" => $dbRow->form_data->ahsec_reg_no,
                        "Registration_Session" => $dbRow->form_data->ahsec_reg_session
                    );
                }
            }

            $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);
            $marksheet_data_cnt = $this->ahsecmarksheet_model->rows_count($filter2);
        }
        $commencing_dates = $this->registration_model->getCommencings();

        if (isset($ahsecmarksheet_data->Stream)) {

            $filter_strem = array(
                "stream" => $ahsecmarksheet_data->Stream ?? null,
            );
            $subjects = $this->registration_model->get_stream_subjects($filter_strem);
            // pre($subjects);

        } else {
            $subjects = $this->registration_model->getSubjects();

        }

        $sessions = $this->fetchsessions(15);

        $this->load->view('upms/includes/header');
        $this->load->view('duplicatecertificateahsec/edit_dc_app_admin_pre', array('dbrow' => $dbRow, 'reg_data' => $reg_data, 'marksheet_data' => $ahsecmarksheet_data, 'commencing_dates' => $commencing_dates, 'subjects' => $subjects, 'marksheet_data_cnt' => $marksheet_data_cnt, 'reg_data_cnt' => $reg_data_cnt, 'sessions' => $sessions));
        $this->load->view('upms/includes/footer');
    }

    public function eCopy_preview($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        $certificate_no = '123456';
        $updateCertificateNo = [
            'form_data.certificate_no' => $certificate_no,
        ];
        // $this->applications_model->update_where(['service_data.appl_ref_no' => $dbRow->service_data->appl_ref_no], $updateCertificateNo);
        $qrcode_path = 'storage/docs/ahsec_common_qr/';
        $pathname = FCPATH . $qrcode_path;
        if (!is_dir($pathname)) {
            mkdir($pathname, 0777, true);
            file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
        }
        $pdata = (array) $this->application_model->get_single_application($dbRow->service_data->appl_ref_no);
        $link = base_url('spservices/ahsec-verify-certificate/' . $pdata[0]->_id->{'$id'});
        $filename = str_replace("/", "-", $pdata[0]->_id->{'$id'});
        $qrname = $filename . ".png";
        $file_name = $qrcode_path . $qrname;
        QRcode::png($link, $file_name);
        // pre($pdata);
        $base64_certificate_path = base64_encode('spservices/duplicatecertificateahsec/actions/download_certificate/' . $pdata[0]->_id->{'$id'});
        $filter1 = array(
            "registration_number" => $pdata[0]->form_data->ahsec_reg_no,
            "registration_session" => $pdata[0]->form_data->ahsec_reg_session,
        );
        $ahsecregistration_data = $this->ahsecregistration_model->get_row($filter1);

        $marksheet_data = [];
        if (($pdata[0]->service_data->service_id == "AHSECDADM") || ($pdata[0]->service_data->service_id == "AHSECDMRK") || ($pdata[0]->service_data->service_id == "AHSECDPC")) {
            $filter2 = array(
                "Registration_No" => $pdata[0]->form_data->ahsec_reg_no,
                "Registration_Session" => $pdata[0]->form_data->ahsec_reg_session,
                "Roll" => $pdata[0]->form_data->ahsec_admit_roll,
                "No" => $pdata[0]->form_data->ahsec_admit_no,
                "Year_of_Examination" => $pdata[0]->form_data->ahsec_yearofpassing,
            );
            $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);

            $marksheet_data['Mark_Sheet_No'] = $ahsecmarksheet_data->Mark_Sheet_No;
            $marksheet_data['Admit_Card_Serial_No'] = $ahsecmarksheet_data->Admit_Card_Serial_No;
            $marksheet_data['Certificate_Serial_No'] = $ahsecmarksheet_data->Certificate_Serial_No;
            $marksheet_data['Year_of_Examination'] = $ahsecmarksheet_data->Year_of_Examination;
            $marksheet_data['commencing_day'] = $ahsecmarksheet_data->commencing_day;
            $marksheet_data['commencing_month'] = $ahsecmarksheet_data->commencing_month;
            $marksheet_data['Stream'] = $ahsecmarksheet_data->Stream;
            $marksheet_data['Date'] = $ahsecmarksheet_data->Marksheet_Date;
            $marksheet_data['Candidate_Name'] = $ahsecmarksheet_data->Candidate_Name;
            $marksheet_data['School_Name'] = $ahsecmarksheet_data->School_College_Name;
            $marksheet_data['Roll'] = $ahsecmarksheet_data->Roll;
            $marksheet_data['No'] = $ahsecmarksheet_data->No;
            $marksheet_data['Remarks1'] = $ahsecmarksheet_data->Remarks1;
            $j = 1;
            for ($i = 1; $i <= 16; $i++) {
                if (!empty($ahsecmarksheet_data->{'Sub' . $i . '_Code'})) {
                    $marksheet_data['Sub' . $j . '_Code'] = $ahsecmarksheet_data->{'Sub' . $i . '_Code'};
                    $marksheet_data['Sub' . $j . '_Pap_Indicator'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pap_Indicator'};
                    $marksheet_data['Sub' . $j . '_Name'] = $ahsecmarksheet_data->{'Sub' . $i . '_Name'};
                    $marksheet_data['Sub' . $j . '_Th_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Th_Marks'};
                    $marksheet_data['Sub' . $j . '_Pr_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pr_Marks'};
                    $marksheet_data['Sub' . $j . '_Grace_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Grace_Marks'};
                    $marksheet_data['Sub' . $j . '_Tot_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Tot_Marks'};
                    $j++;
                }
            }
            $marksheet_data['total_sub'] = --$j;
            $marksheet_data['Total_Marks_in_Words'] = $ahsecmarksheet_data->Total_Marks_in_Words;
            $marksheet_data['Total_Marks_in_Figure'] = $ahsecmarksheet_data->Total_Marks_in_Figure;
            $marksheet_data['Total_Grace_in_Figure'] = $ahsecmarksheet_data->Total_Grace_in_Figure;
            $marksheet_data['Result'] = $ahsecmarksheet_data->Result;
            $marksheet_data['Remarks2'] = $ahsecmarksheet_data->Remarks2;
            $marksheet_data['ENVE_Grade'] = $ahsecmarksheet_data->ENVE_Grade;
            $marksheet_data['Core_Indicator'] = $ahsecmarksheet_data->Core_Indicator;
        }

        $this->load->view('upms/includes/header');
        if ($this->extractServiceId($dbRow->service_data->appl_ref_no) == "AHSECDRC") {
            $this->load->view('duplicatecertificateahsec/reg_output_certificate', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
        } else if ($this->extractServiceId($dbRow->service_data->appl_ref_no) == "AHSECDADM") {
            $this->load->view('duplicatecertificateahsec/output_certificate_adm', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
        } else if ($this->extractServiceId($dbRow->service_data->appl_ref_no) == "AHSECDMRK") {
            $this->load->view('duplicatecertificateahsec/marksheet_output_certificate', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
        } else if ($this->extractServiceId($dbRow->service_data->appl_ref_no) == "AHSECDPC") {
            $this->load->view('duplicatecertificateahsec/output_certificate_pc', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
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

            $this->add_old_data_history($objId, $this->input->post("reg_no"), $this->input->post("session"));

            $new_issue_date = explode('-', $this->input->post("date"));
            $new_issue_date1 = $new_issue_date[2] . "-" . $new_issue_date[1] . "-" . $new_issue_date[0];

            $form_data = [
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

            if ($this->input->post("service_id") == "AHSECDRC") {
                $form_data['sl_no'] = (int) $this->input->post("sl_no");
            }

            $filter1 = array(
                "registration_number" => (int) $this->input->post("reg_no"),
                "registration_session" => $this->input->post("session"),
            );

            $this->ahsecregistration_model->update_where($filter1, $form_data);

            if ($this->input->post("service_id") == "AHSECDRC") {
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
            }

            if ($this->input->post("service_id") != "AHSECDRC") {
                $filter2 = array(
                    "Registration_No" => (int) $this->input->post("reg_no"),
                    "Registration_Session" => $this->input->post("session"),
                    "Roll" => (int) $this->input->post("roll"),
                    "No" => (int) $this->input->post("no"),
                    "Year_of_Examination" => (int) $this->input->post("exam_year"),
                );
                $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);

                if ($ahsecmarksheet_data) {

                    $this->add_old_data_history($objId, $this->input->post("reg_no"), $this->input->post("session"));

                    $filter = array(
                        "Registration_No" => (int) $this->input->post("reg_no"),
                        "Registration_Session" => $this->input->post("session"),
                        "Roll" => (int) $this->input->post("roll"),
                        "No" => (int) $this->input->post("no"),
                        "Year_of_Examination" => (int) $this->input->post("exam_year"),
                    );

                    $marks_data = [
                        'Stream' => $this->input->post("stream"),
                        'School_College_Name' => $this->input->post("institution_name"),
                        'Candidate_Name' => $this->input->post("candidate_name"),
                        'Father_Name' => $this->input->post("father_name"),
                        'Mother_Name' => $this->input->post("mother_name"),
                        'commencing_day' => $this->input->post("commencing_day"),
                        'commencing_month' => $this->input->post("commencing_month"),
                    ];

                    if ($this->input->post("service_id") == "AHSECDADM") {
                        $marks_data['Admit_Card_Serial_No'] = (int) $this->input->post("sl_no");
                    }

                    if ($this->input->post("service_id") == "AHSECDPC") {
                        $marks_data['Certificate_Serial_No'] = (int) $this->input->post("sl_no");
                    }

                    $this->ahsecmarksheet_model->update_where($filter, $marks_data);
                }
            }

            $this->session->set_flashdata('success', 'Master record has been submitted successfully');
            redirect('spservices/duplicatecertificateahsec/actions/app_pre_admin/' . $objId);
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
                'new_inserted_data' => 1
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
                    'new_inserted_data' => 1
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
                    'new_inserted_data' => 1
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
            redirect('spservices/duplicatecertificateahsec/actions/app_pre_admin/' . $objId);
        }
    }

    public function officeCopy($service_id = null, $obj_id = null)
    {
        if ($service_id == "AHSECDRC") {
            $data = array("pageTitleId" => "AHSECDRC", "pageTitle" => "Application for Issuance of Duplicate Registration Card");

        } else if ($service_id == "AHSECDADM") {
            $data = array("pageTitleId" => "AHSECDADM", "pageTitle" => "Application for Issuance of Duplicate Admit Card");

        } else if ($service_id == "AHSECDMRK") {
            $data = array("pageTitleId" => "AHSECDMRK", "pageTitle" => "Application for Issuance of Duplicate Marksheet");

        } else if ($service_id == "AHSECDPC") {
            $data = array("pageTitleId" => "AHSECDPC", "pageTitle" => "Application for Issuance of Duplicate Pass Certificate");

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
            $this->load->view('duplicatecertificateahsec/officecopy', $data);
        }
    }

    public function reports()
    {

        $field = "service_data.service_id";
        $inArray = array("AHSECDRC", "AHSECDADM", "AHSECDMRK", "AHSECDPC", "AHSECCRC", "AHSECCADM", "AHSECCMRK", "AHSECCPC", "AHSECMIGR", "AHSECCHINS");

        if ($this->input->post() != null) {
            // pre($user_name);
            $filter = array(
                "service_data.appl_status" => "D",
                "processing_history.processing_time" => array(
                    '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($this->input->post('from_date') . ' ' . $this->input->post('from_time')) * 1000),
                    '$lte' => new MongoDB\BSON\UTCDateTime(strtotime($this->input->post('to_date') . ' ' . $this->input->post('to_time')) * 1000),
                ));

        } else {
            $filter = array(
                "service_data.appl_status" => "D",
            );

        }

        $dbRow = $this->registration_model->get_rows_where_in($filter, $field, $inArray);
        $url = "spservices/duplicatecertificateahsec/actions/reports";
        $this->load->view('upms/includes/header');
        $this->load->view('duplicatecertificateahsec/print_mis', array('dbRow' => $dbRow, 'url' => $url));
        $this->load->view('upms/includes/footer');
    }

    public function reports_print()
    {
        $field = "service_data.service_id";
        $inArray = array("AHSECDRC", "AHSECDADM", "AHSECDMRK", "AHSECDPC", "AHSECCRC", "AHSECCADM", "AHSECCMRK", "AHSECCPC", "AHSECMIGR", "AHSECCHINS");
        // pre($this->session->userdata('loggedin_user_role_code'));
        $user_name = $this->session->userdata("loggedin_user_role_code");
        // pre($user_name);
        if ($this->input->post() != null) {
            $filter = array(
                "service_data.appl_status" => "AF",
                "current_users.user_role_code" => $user_name,
                "processing_history.processing_time" => array(
                    '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($this->input->post('from_date') . ' ' . $this->input->post('from_time')) * 1000),
                    '$lte' => new MongoDB\BSON\UTCDateTime(strtotime($this->input->post('to_date') . ' ' . $this->input->post('to_time')) * 1000),
                ));
        } else {
            $filter = array(
                "service_data.appl_status" => "AF",
                "current_users.user_role_code" => $user_name,
            );

        }

        // pre($filter );
        $dbRow = $this->registration_model->get_rows_where_in($filter, $field, $inArray);

        $url = "spservices/duplicatecertificateahsec/actions/reports_print";

        $this->load->view('upms/includes/header');
        $this->load->view('duplicatecertificateahsec/print_mis', array('dbRow' => $dbRow, 'url' => $url));
        $this->load->view('upms/includes/footer');
    }

    public function update_marksheet_data($objId = null)
    {
        // pre($this->input->post());

        $this->form_validation->set_rules('ms_no', 'Sl No', 'trim|required|xss_clean|strip_tags|max_length[255]|numeric');
        $this->form_validation->set_rules('stream', 'Stream', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('exam_year', 'Exam year', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mrk_issue_date', 'Marksheet date', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mrk_roll', 'Marksheet roll', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mrk_no', 'Marksheet no', 'trim|required|xss_clean|strip_tags|max_length[255]|numeric');
        $this->form_validation->set_rules('mrk_rc_no', 'Registration no in marksheet data', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mrk_rc_session', 'Registration session in marksheet data', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mrk_candidate_name', 'Candidate Name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mrk_school_name', 'Institution/college name Name', 'trim|required|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_rules('Sub1_Code', 'Subject Code1', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub2_Code', 'Subject Code2', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub3_Code', 'Subject Code3', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub4_Code', 'Subject Code4', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub5_Code', 'Subject Code5', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub6_Code', 'Subject Code6', 'trim|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_rules('Sub1_Pap_Indicator', 'Subject1 paper indicator', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub2_Pap_Indicator', 'Subject2 paper indicator', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub3_Pap_Indicator', 'Subject3 paper indicator', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub4_Pap_Indicator', 'Subject4 paper indicator', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub5_Pap_Indicator', 'Subject5 paper indicator', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub6_Pap_Indicator', 'Subject6 paper indicator', 'trim|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_rules('Sub1_Name', 'Subject1 Name', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub2_Name', 'Subject2 Name', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub3_Name', 'Subject3 Name', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub4_Name', 'Subject4 Name', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub5_Name', 'Subject5 Name', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub6_Name', 'Subject6 Name', 'trim|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_rules('core_sub_1', 'Core1 subject code and name', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('core_sub_2', 'Core2 subject code and name', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('elective_sub_1', 'Elective subject1 code and name', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('elective_sub_2', 'Elective subject2 code and name', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('elective_sub_3', 'Elective subject3 code and name', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('elective_sub_4', 'Elective subject4 code and name', 'trim|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_rules('Sub1_Actual_Marks', 'Sub1 th. marks', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub1_Tot_Marks', 'Sub1 total. marks', 'trim|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_rules('Sub2_Actual_Marks', 'Sub2 th. marks', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub2_Tot_Marks', 'Sub2 total. marks', 'trim|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_rules('Sub3_Actual_Marks', 'Sub3 th. marks', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub3_Tot_Marks', 'Sub3 total. marks', 'trim|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_rules('Sub4_Actual_Marks', 'Sub4 th. marks', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub4_Tot_Marks', 'Sub4 total. marks', 'trim|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_rules('Sub5_Actual_Marks', 'Sub5 th. marks', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub5_Tot_Marks', 'Sub5 total. marks', 'trim|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_rules('Sub6_Actual_Marks', 'Sub6 th. marks', 'trim|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('Sub6_Tot_Marks', 'Sub6 total. marks', 'trim|xss_clean|strip_tags|max_length[255]');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            redirect('spservices/ahsec_correction/actions/app_pre_admin/' . $objId);
        } else {

            $this->add_old_data_history($objId, $this->input->post("mrk_rc_no"), $this->input->post("mrk_rc_session"));

            $filter = array(
                "Registration_No" => (int) $this->input->post("mrk_rc_no"),
                "Registration_Session" => $this->input->post("mrk_rc_session"),
                "Roll" => (int) $this->input->post("mrk_roll"),
                "No" => (int) $this->input->post("mrk_no"),
            );
            // $filter = array(
            //   "Registration_No" => 182147,
            //   "Registration_Session" => "2021-22",
            //   "Roll" => 524,
            //   "No" => 10135,
            // );
            $ahsecmarksheet_data = $this->ahsecmarksheet_model->rows_count($filter);

            if ($ahsecmarksheet_data > 1) {
                $this->session->set_flashdata('error', 'ERROR: unable to update records as we have found multiple records for the reg. no and session provided in our database!');
                redirect('spservices/ahsec_correction/actions/app_pre_admin/' . $objId);
            }
            $form_data['Mark_Sheet_No'] = $this->input->post("ms_no");
            $form_data['Stream'] = $this->input->post("stream");
            $form_data['Exam_year'] = $this->input->post("exam_year");
            $form_data['Marksheet_Date'] = date("d-m-Y", strtotime($this->input->post("mrk_issue_date")));
            $form_data['Candidate_Name'] = $this->input->post("mrk_candidate_name");
            $form_data['School_College_Name'] = $this->input->post("mrk_school_name");

            foreach (['1', '2', '3', '4', '5', '6'] as $index) {
                $Sl_id = $this->input->post("Sl" . $index . "_id");
                if (!empty($Sl_id)) {
                    if ($this->input->post("Sub" . $index . "_Code") != "BIOL") {
                        $form_data['Sub' . $Sl_id . '_Code'] = $this->input->post("Sub" . $index . "_Code");
                        $form_data['Sub' . $Sl_id . '_Pap_Indicator'] = $this->input->post("Sub" . $index . "_Pap_Indicator");
                        $form_data['Sub' . $Sl_id . '_Name'] = $this->input->post("Sub" . $index . "_Name");
                        $form_data['Sub' . $Sl_id . '_Th_Marks'] = $this->input->post("Sub" . $index . "_Actual_Marks");
                        $form_data['Sub' . $Sl_id . '_Pr_Marks'] = $this->input->post("Sub" . $index . "_Pr_Marks");
                        $form_data['Sub' . $Sl_id . '_Grace_Marks'] = $this->input->post("Sub" . $index . "_Grace_Marks");
                        $form_data['Sub' . $Sl_id . '_Tot_Marks'] = $this->input->post("Sub" . $index . "_Tot_Marks");
                    } else if ($this->input->post("Sub" . $index . "_Code") == "BIOL") {

                        $form_data['Sub' . $Sl_id . '_Code'] = $this->input->post("Sub" . $index . "_Code");
                        $form_data['Sub' . $Sl_id . '_Pap_Indicator'] = $this->input->post("Sub" . $index . "_Pap_Indicator");
                        $form_data['Sub' . $Sl_id . '_Name'] = $this->input->post("Sub" . $index . "_Name");
                        $subjectCode = substr($this->input->post("elective_sub_" . $index), 0, strpos($this->input->post("elective_sub_" . $index), "|"));
                        $form_data['Sub' . $Sl_id . '_Th_Marks'] = $this->input->post("Sub" . $index . "_Botany_Marks") . '+' . $this->input->post("Sub" . $index . "_Zoology_Marks");
                        $form_data['Sub' . $Sl_id . '_Pr_Marks'] = $this->input->post("Sub" . $index . "_Botany_Pr_Marks") . '+' . $this->input->post("Sub" . $index . "_Zoology_Pr_Marks");

                        $Botany_Grace_Marks = !empty($this->input->post("Sub" . $index . "_Botany_Grace_Marks")) ? $this->input->post("Sub" . $index . "_Botany_Grace_Marks") : 0;
                        $Zoology_Grace_Marks = !empty($this->input->post("Sub" . $index . "_Zoology_Grace_Marks")) ? $this->input->post("Sub" . $index . "_Zoology_Grace_Marks") : 0;

                        $form_data['Sub' . $Sl_id . '_Grace_Marks'] = $Botany_Grace_Marks . '+' . $Zoology_Grace_Marks;

                        $form_data['Sub' . $Sl_id . '_Tot_Marks'] = ($this->input->post("Sub" . $index . "_Botany_Marks") + $Botany_Grace_Marks + $this->input->post("Sub" . $index . "_Botany_Pr_Marks")) . '+' . ($this->input->post("Sub" . $index . "_Zoology_Marks") + $Zoology_Grace_Marks + $this->input->post("Sub" . $index . "_Zoology_Pr_Marks"));
                    }
                }
            }
            $form_data['Total_Marks_in_Words'] = $this->input->post("Total_Marks_in_Words");
            $form_data['Total_Marks_in_Figure'] = $this->input->post("Total_Marks_in_Figure");
            $form_data['Total_Grace_in_Figure'] = $this->input->post("Total_Grace_in_Figure");
            $form_data['Result'] = $this->input->post("Result");
            $form_data['Remarks2'] = $this->input->post("Remarks2");
            $form_data['ENVE_Grade'] = $this->input->post("ENVE_Grade");

            //pre($form_data);
            $this->ahsecmarksheet_model->update_where($filter, $form_data);
            $this->session->set_flashdata('success', 'Data has been successfully updated!');
            redirect('spservices/duplicatecertificateahsec/actions/app_pre_admin/' . $objId);
        }
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
        $registration_data_cnt = $this->ahsecregistration_model->rows_count($filter33);

        if ($registration_data_cnt > 0) {
            if ($registration_data) {
                foreach ($registration_data as $key => $value) {
                    unset($value->_id);
                }
            }
        }

        $marksheet_data = "";
        $filter34 = array(
            "Registration_No" => (int) $reg_no,
            "Registration_Session" => $session,
        );
        $marksheet_data = $this->ahsecmarksheet_model->get_rows($filter34);
        $marksheet_data_cnt = $this->ahsecmarksheet_model->rows_count($filter34);

        if ($marksheet_data_cnt > 0) {
            if ($marksheet_data) {
                foreach ($marksheet_data as $key => $value) {
                    unset($value->_id);
                }
            }
        }

        $dbRow = $this->registration_model->get_by_doc_id($objId);

        $inputs = [
            'service_id' => $dbRow->service_data->service_id,
            'current_users' => $dbRow->current_users,
            'appl_ref_no' => $dbRow->service_data->appl_ref_no,
            'registration_data' => $registration_data,
            'marksheet_data' => $marksheet_data,
            'created_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
        ];
        $this->ahsecolddata_model->insert($inputs);
        // End of Store old data
    }

    public function preview($objId = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {

            $master_data = "";
            if ($dbRow->service_data->service_id == "AHSECDRC") {
                $filter = array(
                    "registration_number" => (int) $dbRow->form_data->ahsec_reg_no,
                    "registration_session" => $dbRow->form_data->ahsec_reg_session,
                );
                $master_data = $this->ahsecregistration_model->get_row($filter);
            }

            if (($dbRow->service_data->service_id != "AHSECDRC") && ($dbRow->service_data->service_id != "AHSECDMRK")) {
                $filter = array(
                    "Registration_No" => (int) $dbRow->form_data->ahsec_reg_no,
                    "Registration_Session" => $dbRow->form_data->ahsec_reg_session,
                    "Roll" => (int) $dbRow->form_data->ahsec_admit_roll,
                    "No" => (int) $dbRow->form_data->ahsec_admit_no,
                    "Year_of_Examination" => (int) $dbRow->form_data->ahsec_yearofpassing,
                );
                $master_data = $this->ahsecmarksheet_model->get_row($filter);
            }

            if ($dbRow->service_data->service_id == "AHSECDMRK") {
                $master_data = array();
                $filter = array(
                    "Registration_No" => (int) $dbRow->form_data->ahsec_reg_no,
                    "Registration_Session" => $dbRow->form_data->ahsec_reg_session,
                    "Roll" => (int) $dbRow->form_data->ahsec_admit_roll,
                    "No" => (int) $dbRow->form_data->ahsec_admit_no,
                    "Year_of_Examination" => (int) $dbRow->form_data->ahsec_yearofpassing,
                );
                $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter);

                $master_data['Mark_Sheet_No'] = $ahsecmarksheet_data->Mark_Sheet_No;
                $master_data['Admit_Card_Serial_No'] = $ahsecmarksheet_data->Admit_Card_Serial_No;
                $master_data['Certificate_Serial_No'] = $ahsecmarksheet_data->Certificate_Serial_No;
                $master_data['Year_of_Examination'] = $ahsecmarksheet_data->Year_of_Examination;
                $master_data['commencing_day'] = $ahsecmarksheet_data->commencing_day;
                $master_data['commencing_month'] = $ahsecmarksheet_data->commencing_month;
                $master_data['Stream'] = $ahsecmarksheet_data->Stream;
                $master_data['Date'] = $ahsecmarksheet_data->Marksheet_Date;
                $master_data['Candidate_Name'] = $ahsecmarksheet_data->Candidate_Name;
                $master_data['School_Name'] = $ahsecmarksheet_data->School_College_Name;
                $master_data['Roll'] = $ahsecmarksheet_data->Roll;
                $master_data['No'] = $ahsecmarksheet_data->No;
                $master_data['Year_of_Examination'] = $ahsecmarksheet_data->Year_of_Examination;
                $master_data['Registration_No'] = $ahsecmarksheet_data->Registration_No;
                $master_data['Registration_Session'] = $ahsecmarksheet_data->Registration_Session;
                $master_data['Remarks1'] = $ahsecmarksheet_data->Remarks1;
                $j = 1;
                for ($i = 1; $i <= 16; $i++) {
                    if (!empty($ahsecmarksheet_data->{'Sub' . $i . '_Code'})) {
                        $master_data['Sub' . $j . '_Code'] = $ahsecmarksheet_data->{'Sub' . $i . '_Code'};
                        $master_data['Sub' . $j . '_Pap_Indicator'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pap_Indicator'};
                        $master_data['Sub' . $j . '_Name'] = $ahsecmarksheet_data->{'Sub' . $i . '_Name'};
                        $master_data['Sub' . $j . '_Th_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Th_Marks'};
                        $master_data['Sub' . $j . '_Pr_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pr_Marks'};
                        $master_data['Sub' . $j . '_Grace_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Grace_Marks'};
                        $master_data['Sub' . $j . '_Tot_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Tot_Marks'};
                        $j++;
                    }
                }
                $master_data['total_sub'] = --$j;
                $master_data['Total_Marks_in_Words'] = $ahsecmarksheet_data->Total_Marks_in_Words;
                $master_data['Total_Marks_in_Figure'] = $ahsecmarksheet_data->Total_Marks_in_Figure;
                $master_data['Total_Grace_in_Figure'] = $ahsecmarksheet_data->Total_Grace_in_Figure;
                $master_data['Result'] = $ahsecmarksheet_data->Result;
                $master_data['Remarks2'] = $ahsecmarksheet_data->Remarks2;
                $master_data['ENVE_Grade'] = $ahsecmarksheet_data->ENVE_Grade;
                $master_data['Core_Indicator'] = $ahsecmarksheet_data->Core_Indicator;
            }

            $data = array(
                "dbrow" => $dbRow,
                "master_data" => $master_data
            );

            $this->load->view('includes/frontend/header');
            $this->load->view('duplicatecertificateahsec/preview_admin', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found');
            redirect('spservices/duplicatecertificateahsec/registration');
        } //End of if else
    } //End of preview()
}
