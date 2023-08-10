
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
    $this->load->model('upms/roles_model');
    $this->load->model('duplicatecertificateahsec/ahsecregistration_model');
    $this->load->model('duplicatecertificateahsec/ahsecmarksheet_model');
    $this->load->model('ahsec_correction/ahsec_correction_model');
    $this->load->model('migrationcertificateahsec/registration_model');
    $this->load->model('duplicatecertificateahsec/ahsecolddata_model');
    $this->load->model('upms/levels_model');
    $this->load->model('upms/users_model');
    $this->load->model('upms/applications_model');
    $this->load->helper("cifileupload");
    $this->load->library('../modules/spservices/controllers/office/sms_scheduler');
    $this->load->library('phpqrcode/qrlib');
  }

  public function preview_certificate($appl_no = null)
  {
    //GENERATE CERTIFICATE NO -START
    $certificate_no = strlen($this->extractRefNo($appl_no)) ? $this->extractRefNo($appl_no) : false;
    if (!$certificate_no) {
      die('Invalid certificate no!');
    }
    //GENERATE CERTIFICATE NO -END
    //GENERATE QR CODE -START
    $qrcode_path = 'storage/docs/ahsec_common_qr/';
    $pathname = FCPATH . $qrcode_path;
    if (!is_dir($pathname)) {
      mkdir($pathname, 0777, true);
      file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
    }
    $pdata = (array)$this->application_model->get_single_application($appl_no);
    $link = base_url('spservices/ahsec-verify-certificate/' . $pdata[0]->_id->{'$id'});
    $filename = str_replace("/", "-", $pdata[0]->_id->{'$id'});
    $qrname = $filename . ".png";
    $file_name = $qrcode_path . $qrname;
    QRcode::png($link, $file_name);
    //GENERATE QR CODE -END

    $base64_certificate_path = base64_encode('spservices/ahsec_correction/actions/download_certificate/' . $pdata[0]->_id->{'$id'});
    $filter1 = array(
      "registration_number" => $pdata[0]->form_data->ahsec_reg_no,
      "registration_session" => $pdata[0]->form_data->ahsec_reg_session,
    );
    $ahsecregistration_data = $this->ahsecregistration_model->get_row($filter1);

    if ($pdata[0]->service_data->service_id == "AHSECCADM" || $pdata[0]->service_data->service_id == "AHSECCMRK" || $pdata[0]->service_data->service_id == "AHSECCPC") {
      $filter2 = array(
        "Registration_No" => $pdata[0]->form_data->ahsec_reg_no,
        "Registration_Session" => $pdata[0]->form_data->ahsec_reg_session,
        "Roll" => $pdata[0]->form_data->ahsec_admit_roll,
        "No" => $pdata[0]->form_data->ahsec_admit_no,
      );
      $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);

      $marksheet_data['Mark_Sheet_No'] = $ahsecmarksheet_data->Mark_Sheet_No;
      $marksheet_data['Stream'] = $ahsecmarksheet_data->Stream;
      $marksheet_data['Date'] = $ahsecmarksheet_data->Marksheet_Date;
      $marksheet_data['Candidate_Name'] = $ahsecmarksheet_data->Candidate_Name;
      $marksheet_data['School_Name'] = $ahsecmarksheet_data->School_College_Name;
      $marksheet_data['Roll'] = $ahsecmarksheet_data->Roll;
      $marksheet_data['No'] = $ahsecmarksheet_data->No;
      $marksheet_data['Remarks1'] = $ahsecmarksheet_data->Remarks1;
      $marksheet_data['Year_of_Examination'] = $ahsecmarksheet_data->Year_of_Examination;
      $marksheet_data['commencing_day'] = $ahsecmarksheet_data->commencing_day ?? '';
      $marksheet_data['commencing_month'] = $ahsecmarksheet_data->commencing_month ?? '';
      $j = 1;
      for ($i = 1; $i <= 16; $i++) {
        if (!empty($ahsecmarksheet_data->{'Sub' . $i . '_Code'})) {
          $marksheet_data['Sub' . $j . '_Code'] = $ahsecmarksheet_data->{'Sub' . $i . '_Code'};
          $marksheet_data['Sub' . $j . '_Pap_Indicator'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pap_Indicator'};
          $marksheet_data['Sub' . $j . '_Name'] = $ahsecmarksheet_data->{'Sub' . $i . '_Name'};
          $marksheet_data['Sub' . $j . '_Th_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Th_Marks'};
          $marksheet_data['Sub' . $j . '_Pr_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pr_Marks'};
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
    // $html = $this->load->view('ahsec_correction/output_certificate_adm', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no,), true);
    // $this->load->library('dpdf');
    // $pdf_path = $this->dpdf->createPDF($html, $filename, 'A4', 'landscape', 'AHSEC/' . $pdata[0]->service_data->service_id, true);
    // pre($pdf_path);

    $this->load->view('upms/includes/header');
    if ($this->extractServiceId($appl_no) == "AHSECCRC") {
      $this->load->view('ahsec_correction/output_certificate_rc', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'user_type' => 'official'));
    } else if ($this->extractServiceId($appl_no) == "AHSECCADM") {
      $this->load->view('ahsec_correction/output_certificate_adm', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'official'));
    } else if ($this->extractServiceId($appl_no) == "AHSECCMRK") {
      $this->load->view('ahsec_correction/output_certificate_mrk', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'official'));
    } else if ($this->extractServiceId($appl_no) == "AHSECCPC") {
      $this->load->view('ahsec_correction/output_certificate_pc', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'official'));
    }
    $this->load->view('upms/includes/footer');
  }

  function getID($appl_no)
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

  function extractServiceId($appl_ref_no)
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
    $dbRow = $this->ahsec_correction_model->get_by_doc_id($objId);
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
    if ($pdata[0]->service_data->service_id == "AHSECCADM" || $pdata[0]->service_data->service_id == "AHSECCMRK" || $pdata[0]->service_data->service_id == "AHSECCPC") {
      $filter2 = array(
        "Registration_No" => $pdata[0]->form_data->ahsec_reg_no,
        "Registration_Session" => $pdata[0]->form_data->ahsec_reg_session,
        "Roll" => $pdata[0]->form_data->ahsec_admit_roll,
        "No" => $pdata[0]->form_data->ahsec_admit_no,
      );
      $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);

      $marksheet_data['Mark_Sheet_No'] = $ahsecmarksheet_data->Mark_Sheet_No;
      $marksheet_data['Stream'] = $ahsecmarksheet_data->Stream;
      $marksheet_data['Date'] = $ahsecmarksheet_data->Marksheet_Date;
      $marksheet_data['Candidate_Name'] = $ahsecmarksheet_data->Candidate_Name;
      $marksheet_data['School_Name'] = $ahsecmarksheet_data->School_College_Name;
      $marksheet_data['Roll'] = $ahsecmarksheet_data->Roll;
      $marksheet_data['No'] = $ahsecmarksheet_data->No;
      $marksheet_data['Remarks1'] = $ahsecmarksheet_data->Remarks1;
      $marksheet_data['Year_of_Examination'] = $ahsecmarksheet_data->Year_of_Examination;
      $marksheet_data['commencing_day'] = $ahsecmarksheet_data->commencing_day ?? '';
      $marksheet_data['commencing_month'] = $ahsecmarksheet_data->commencing_month ?? '';
      $j = 1;
      for ($i = 1; $i <= 16; $i++) {
        if (!empty($ahsecmarksheet_data->{'Sub' . $i . '_Code'})) {
          $marksheet_data['Sub' . $j . '_Code'] = $ahsecmarksheet_data->{'Sub' . $i . '_Code'};
          $marksheet_data['Sub' . $j . '_Pap_Indicator'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pap_Indicator'};
          $marksheet_data['Sub' . $j . '_Name'] = $ahsecmarksheet_data->{'Sub' . $i . '_Name'};
          $marksheet_data['Sub' . $j . '_Th_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Th_Marks'};
          $marksheet_data['Sub' . $j . '_Pr_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pr_Marks'};
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
    // pre($marksheet_data);
    $this->load->view('upms/includes/header');
    if ($this->extractServiceId($appl_no) == "AHSECCRC") {
      $this->load->view('ahsec_correction/output_certificate_rc', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $pdata[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'user_type' => null));
    } else if ($this->extractServiceId($appl_no) == "AHSECCADM") {
      $this->load->view('ahsec_correction/output_certificate_adm', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $pdata[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
    } else if ($this->extractServiceId($appl_no) == "AHSECCMRK") {
      $this->load->view('ahsec_correction/output_certificate_mrk', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $pdata[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
    } else if ($this->extractServiceId($appl_no) == "AHSECCPC") {
      $this->load->view('ahsec_correction/output_certificate_pc', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $pdata[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
    }
    $this->load->view('upms/includes/footer');
  }

  public function app_pre_admin($objId = null)
  {
    $dbRow = $this->ahsec_correction_model->get_by_doc_id($objId);
    $filter1 = array(
      "registration_number" => (int) $dbRow->form_data->ahsec_reg_no,
      "registration_session" => $dbRow->form_data->ahsec_reg_session,
    );

    $reg_row_count = count((array) $this->ahsecregistration_model->get_rows($filter1));
    $reg_data = $this->ahsecregistration_model->get_row($filter1);

    $ahsecmarksheet_data = [];
    $marksheet_row_count = 0;
    if ($dbRow->service_data->service_id != "AHSECCRC") {
      $filter2 = array(
        "Registration_No" => $dbRow->form_data->ahsec_reg_no,
        "Registration_Session" => $dbRow->form_data->ahsec_reg_session,
        "Roll" => (int) $dbRow->form_data->ahsec_admit_roll,
        "No" => (int) $dbRow->form_data->ahsec_admit_no,
        "Year_of_Examination" => $dbRow->form_data->ahsec_yearofappearing
      );
      $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);
      $marksheet_row_count = count((array) $this->ahsecmarksheet_model->get_rows($filter2));
    }

    $commencing_dates = $this->registration_model->getCommencings();
    // $ahsecmarksheet_data = '';
    if (isset($ahsecmarksheet_data->Stream)) {

      $filter_strem = array(
        "stream" => $ahsecmarksheet_data->Stream ?? null,
      );
      $subjects = $this->registration_model->get_stream_subjects($filter_strem);
      // pre($subjects);

    } else {
      $subjects = $this->registration_model->getSubjects();
    }
    $this->load->view('upms/includes/header');
    $this->load->view('ahsec_correction/edit_dc_app_admin_pre', array('dbrow' => $dbRow, 'reg_data' => $reg_data, 'marksheet_data' => $ahsecmarksheet_data, 'commencing_dates' => $commencing_dates, 'subjects' => $subjects, 'marksheet_row_count' => $marksheet_row_count, 'reg_row_count' => $reg_row_count));
    $this->load->view('upms/includes/footer');
  }

  public function eCopy_preview($objId = null)
  {
    $dbRow = $this->ahsec_correction_model->get_by_doc_id($objId);
    //GENERATE CERTIFICATE NO -START
    $certificate_no = strlen($this->extractRefNo($dbRow->service_data->appl_ref_no)) ? $this->extractRefNo($dbRow->service_data->appl_ref_no) : false;
    if (!$certificate_no) {
      die('Invalid certificate no!');
    }
    //GENERATE CERTIFICATE NO -END
    // $this->applications_model->update_where(['service_data.appl_ref_no' => $dbRow->service_data->appl_ref_no], $updateCertificateNo);
    $qrcode_path = 'storage/docs/ahsec_common_qr/';
    $pathname = FCPATH . $qrcode_path;
    if (!is_dir($pathname)) {
      mkdir($pathname, 0777, true);
      file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
    }
    $pdata = (array)$this->application_model->get_single_application($dbRow->service_data->appl_ref_no);
    $link = base_url('spservices/ahsec-verify-certificate/' . $pdata[0]->_id->{'$id'});
    $filename = str_replace("/", "-", $pdata[0]->_id->{'$id'});
    $qrname = $filename . ".png";
    $file_name = $qrcode_path . $qrname;
    QRcode::png($link, $file_name);
    // pre($pdata);
    $base64_certificate_path = base64_encode('spservices/ahsec_correction/actions/download_certificate/' . $pdata[0]->_id->{'$id'});
    $filter1 = array(
      "registration_number" => $pdata[0]->form_data->ahsec_reg_no,
      "registration_session" => $pdata[0]->form_data->ahsec_reg_session,
    );
    $ahsecregistration_data = $this->ahsecregistration_model->get_row($filter1);

    if (($pdata[0]->service_data->service_id == "AHSECCADM") || ($pdata[0]->service_data->service_id == "AHSECCMRK") || ($pdata[0]->service_data->service_id == "AHSECCPC")) {
      $filter2 = array(
        "Registration_No" => $pdata[0]->form_data->ahsec_reg_no,
        "Registration_Session" => $pdata[0]->form_data->ahsec_reg_session,
        "Roll" => $pdata[0]->form_data->ahsec_admit_roll,
        "No" => $pdata[0]->form_data->ahsec_admit_no,
      );
      $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);

      $marksheet_data['Mark_Sheet_No'] = (string) $ahsecmarksheet_data->Mark_Sheet_No;
      $marksheet_data['Stream'] = $ahsecmarksheet_data->Stream;
      $marksheet_data['Date'] = $ahsecmarksheet_data->Marksheet_Date;
      $marksheet_data['Candidate_Name'] = $ahsecmarksheet_data->Candidate_Name;
      $marksheet_data['School_Name'] = $ahsecmarksheet_data->School_College_Name;
      $marksheet_data['Roll'] = $ahsecmarksheet_data->Roll;
      $marksheet_data['No'] = $ahsecmarksheet_data->No;
      $marksheet_data['Remarks1'] = $ahsecmarksheet_data->Remarks1;
      $marksheet_data['Year_of_Examination'] = $ahsecmarksheet_data->Year_of_Examination;
      $marksheet_data['commencing_day'] = $ahsecmarksheet_data->commencing_day ?? '';
      $marksheet_data['commencing_month'] = $ahsecmarksheet_data->commencing_month ?? '';

      $j = 1;
      for ($i = 1; $i <= 16; $i++) {
        if (!empty($ahsecmarksheet_data->{'Sub' . $i . '_Code'})) {
          $marksheet_data['Sub' . $j . '_Code'] = $ahsecmarksheet_data->{'Sub' . $i . '_Code'};
          $marksheet_data['Sub' . $j . '_Pap_Indicator'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pap_Indicator'};
          $marksheet_data['Sub' . $j . '_Name'] = $ahsecmarksheet_data->{'Sub' . $i . '_Name'};
          $marksheet_data['Sub' . $j . '_Th_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Th_Marks'};
          $marksheet_data['Sub' . $j . '_Pr_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pr_Marks'};
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
    if ($this->extractServiceId($dbRow->service_data->appl_ref_no) == "AHSECCRC") {
      $this->load->view('ahsec_correction/output_certificate_rc', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
    } else if ($this->extractServiceId($dbRow->service_data->appl_ref_no) == "AHSECCADM") {
      $this->load->view('ahsec_correction/output_certificate_adm', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
    } else if ($this->extractServiceId($dbRow->service_data->appl_ref_no) == "AHSECCMRK") {
      $this->load->view('ahsec_correction/output_certificate_mrk', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
    } else if ($this->extractServiceId($dbRow->service_data->appl_ref_no) == "AHSECCPC") {
      $this->load->view('ahsec_correction/output_certificate_pc', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no, 'certificate_path' => $base64_certificate_path, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => null));
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


    if ($this->input->post("service_id") != "AHSECCRC") {

      $this->form_validation->set_rules('stream', 'Stream', 'trim|required|xss_clean|strip_tags|max_length[255]');
      $this->form_validation->set_rules('roll', 'Roll', 'trim|required|xss_clean|strip_tags|max_length[255]');
      $this->form_validation->set_rules('no', 'No', 'trim|required|xss_clean|strip_tags|max_length[255]');
    }


    if ($this->form_validation->run() == false) {
      $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
      redirect('spservices/ahsec_correction/actions/app_pre_admin/' . $objId);
    } else {

      $this->add_old_data_history($objId, $this->input->post("reg_no"), $this->input->post("session"));

      $new_issue_date = explode('-', $this->input->post("date"));
      $new_issue_date1 = $new_issue_date[2] . "-" . $new_issue_date[1] . "-" . $new_issue_date[0];

      $form_data = [
        'sl_no' => (int) $this->input->post("sl_no"),
        'institution_code' => (int) $this->input->post("code_no"),
        'candidate_name' => $this->input->post("candidate_name"),
        'father_name' => $this->input->post("father_name"),
        'mother_name' => $this->input->post("mother_name"),
        'institution_name' => $this->input->post("institution_name"),
        // 'registration_number' => (int) $this->input->post("reg_no"),
        'issue_date' => $new_issue_date1,
        // 'registration_session' => $this->input->post("session"),
        'mobile_no' => (int) $this->input->post("mobile_no"),
        'sub_1' => $this->input->post("core_sub_1"),
        'sub_2' => $this->input->post("core_sub_2"),
        'sub_3' => $this->input->post("elective_sub_3"),
        'sub_4' => $this->input->post("elective_sub_4"),
        'sub_5' => $this->input->post("elective_sub_5"),
        'sub_6' => $this->input->post("elective_sub_6"),
      ];

      if ($this->input->post("service_id") == "AHSECCRC")
        $form_data['sl_no'] = (int) $this->input->post("sl_no");

      $filter1 = array(
        "registration_number" => (int) $this->input->post("reg_no"),
        "registration_session" => $this->input->post("session"),
      );

      $this->ahsecregistration_model->update_where($filter1, $form_data);

      if ($this->input->post("service_id") == "AHSECCRC") {
        $filter22 = array(
          "Registration_No" => (int) $this->input->post("reg_no"),
          "Registration_Session" => $this->input->post("session")
        );
        $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_rows($filter22);

        if ($ahsecmarksheet_data) {
          foreach ($ahsecmarksheet_data as $key => $value) {

            $filter23 = array(
              "Registration_No" => (int) $this->input->post("reg_no"),
              "Registration_Session" => $this->input->post("session"),
              "Roll" => (int) $value->Roll,
              "No" => (int) $value->No,
            );

            $marks_data = [
              'Candidate_Name' => $this->input->post("candidate_name"),
              'Father_Name' => $this->input->post("father_name"),
              'Mother_Name' => $this->input->post("mother_name")
            ];
            $this->ahsecmarksheet_model->update_where($filter23, $marks_data);
          }
        }
      }

      if ($this->input->post("service_id") != "AHSECCRC") {
        $filter2 = array(
          "Registration_No" => (int) $this->input->post("reg_no"),
          "Registration_Session" => $this->input->post("session"),
          "Roll" => (int) $this->input->post("roll"),
          "No" => (int) $this->input->post("no"),
        );
        $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);

        if ($ahsecmarksheet_data) {
          $filter = array(
            "Registration_No" => (int) $this->input->post("reg_no"),
            "Registration_Session" => $this->input->post("session"),
            "Roll" => (int) $this->input->post("roll"),
            "No" => (int) $this->input->post("no"),
          );
          $marks_data = [
            'Stream' => $this->input->post("stream"),
            // 'Roll' => (int) $this->input->post("roll"),
            // 'No' => (int) $this->input->post("no"),
            'Candidate_Name' => $this->input->post("candidate_name"),
            'Father_Name' => $this->input->post("father_name"),
            'Mother_Name' => $this->input->post("mother_name"),
            'exam_year' => $this->input->post("exam_year"),
            'commencing_day' => $this->input->post("commencing_day"),
            'commencing_month' => $this->input->post("commencing_month"),
          ];

          if ($this->input->post("service_id") == "AHSECDADM")
            $marks_data['Admit_Card_Serial_No'] = (int) $this->input->post("sl_no");

          if ($this->input->post("service_id") == "AHSECDPC")
            $marks_data['Certificate_Serial_No'] = (int) $this->input->post("sl_no");

          $this->ahsecmarksheet_model->update_where($filter, $marks_data);
        }
      }

      $this->session->set_flashdata('success', 'Your application has been successfully submitted');
      redirect('spservices/ahsec_correction/actions/app_pre_admin/' . $objId);
    }
  }

  public function officeCopy($service_id = null, $obj_id = null)
  {

    //check_application_count_for_citizen(); 
    if ($service_id == "AHSECCRC") {
      $data = array("pageTitleId" => "AHSECCRC", "pageTitle" => "Application for Correction in Registration Card");
    } else if ($service_id == "AHSECCADM") {
      $data = array("pageTitleId" => "AHSECCADM", "pageTitle" => "Application for Correction in Admit Card");
    } else if ($service_id == "AHSECCMRK") {
      $data = array("pageTitleId" => "AHSECCMRK", "pageTitle" => "Application for Correction in Marksheet");
    } else if ($service_id == "AHSECCPC") {
      $data = array("pageTitleId" => "AHSECCPC", "pageTitle" => "Application for Correction in Passcertificate");
    }

    $filter = array("_id" => new ObjectId($obj_id), "service_data.service_id" => $service_id);
    $data['data'] = $data;

    $data["dbrow"] = $this->registration_model->get_row($filter);
    $dbRow = $this->registration_model->get_row($filter);

    $filter1 = array(
      "Registration_No" => $dbRow->form_data->ahsec_reg_no,
      "Registration_Session" => $dbRow->form_data->ahsec_reg_session,
    );
    $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter1);

    $data["marksheet_data"] = $ahsecmarksheet_data;

    // $data['usser_type'] = $this->slug;
    $this->load->view('ahsec_correction/officecopy', $data);
  } //End of index()


  public function update_marksheet_data($objId = null)
  {


    $this->form_validation->set_rules('ms_no', 'Sl No', 'trim|required|xss_clean|strip_tags|max_length[255]|numeric');
    $this->form_validation->set_rules('stream', 'Stream', 'trim|required|xss_clean|strip_tags|max_length[255]');
    $this->form_validation->set_rules('mrk_date', 'Marksheet date', 'trim|required|xss_clean|strip_tags|max_length[255]');
    $this->form_validation->set_rules('mrk_issue_date', 'Marksheet Issued Date', 'trim|required|xss_clean|strip_tags|max_length[255]');
    $this->form_validation->set_rules('mrk_roll', 'Marksheet roll', 'trim|required|xss_clean|strip_tags|max_length[255]');
    $this->form_validation->set_rules('mrk_no', 'Marksheet no', 'trim|required|xss_clean|strip_tags|max_length[255]|numeric');
    $this->form_validation->set_rules('mrk_rc_no', 'Registration no in marksheet data', 'trim|required|xss_clean|strip_tags|max_length[255]');
    $this->form_validation->set_rules('mrk_rc_session', 'Registration session in marksheet data', 'trim|required|xss_clean|strip_tags|max_length[255]');
    $this->form_validation->set_rules('mrk_candidate_name', 'Candidate Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
    $this->form_validation->set_rules('mrk_father_name', 'Father Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
    $this->form_validation->set_rules('mrk_mother_name', 'Mother Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
    $this->form_validation->set_rules('mrk_school_name', 'Institution/college name Name', 'trim|required|xss_clean|strip_tags|max_length[255]');
    $this->form_validation->set_rules('Year_of_Examination', 'Year of examination', 'trim|required|xss_clean|strip_tags|max_length[255]');
    $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean|strip_tags|max_length[255]');

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

    $this->form_validation->set_rules('Sub1_Actual_Marks', 'Sub1 th. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub1_Pr_Marks', 'Sub1 Pr. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub1_Grace_Marks', 'Sub1 Grace marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub1_Tot_Marks', 'Sub1 total. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');

    $this->form_validation->set_rules('Sub2_Actual_Marks', 'Sub2 th. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub2_Pr_Marks', 'Sub2 Pr. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub2_Grace_Marks', 'Sub2 Grace marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub2_Tot_Marks', 'Sub2 total. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');

    $this->form_validation->set_rules('Sub3_Actual_Marks', 'Sub3 th. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub3_Pr_Marks', 'Sub3 Pr. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub3_Grace_Marks', 'Sub3 Grace marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub3_Tot_Marks', 'Sub3 total. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');

    $this->form_validation->set_rules('Sub4_Actual_Marks', 'Sub4 th. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub4_Pr_Marks', 'Sub4 Pr. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub4_Grace_Marks', 'Sub4 Grace marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub4_Tot_Marks', 'Sub4 total. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');

    $this->form_validation->set_rules('Sub5_Actual_Marks', 'Sub5 th. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub5_Pr_Marks', 'Sub5 Pr. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub5_Grace_Marks', 'Sub5 Grace marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub5_Tot_Marks', 'Sub5 total. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');

    $this->form_validation->set_rules('Sub6_Actual_Marks', 'Sub6 th. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub6_Pr_Marks', 'Sub6 Pr. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub6_Grace_Marks', 'Sub6 Grace marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');
    $this->form_validation->set_rules('Sub6_Tot_Marks', 'Sub6 total. marks', 'trim|xss_clean|strip_tags|numeric|max_length[255]');

    if ($this->input->post("status") == "INSERT_DATA") {
      $this->form_validation->set_rules('Admit_Card_Serial_No', 'Admit Card Serial No', 'trim|required|xss_clean|strip_tags|max_length[255]');
      $this->form_validation->set_rules('Certificate_Serial_No', 'Certificate Serial No', 'trim|required|xss_clean|strip_tags|max_length[255]');
      $this->form_validation->set_rules('sub_sl_no[]', 'Subject sl no', 'trim|xss_clean|strip_tags');
      $this->form_validation->set_rules('Sub_Code[]', 'Subject code', 'trim|xss_clean|strip_tags');
      $this->form_validation->set_rules('Sub_Pap_Indicator[]', 'Subject paper indicator', 'trim|xss_clean|strip_tags');
      $this->form_validation->set_rules('Sub_Actual_Marks[]', 'Th. marks', 'trim|xss_clean|strip_tags|integer');
      $this->form_validation->set_rules('Sub_Pr_Marks[]', 'Pr. marks', 'trim|xss_clean|strip_tags|integer');
      $this->form_validation->set_rules('Sub_Grace_Marks[]', 'Grc. marks', 'trim|xss_clean|strip_tags|integer');
      $this->form_validation->set_rules('Sub_Tot_Marks[]', 'Total marks', 'trim|xss_clean|strip_tags|integer');
    }

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
        "Year_of_Examination" => (int) $this->input->post("Year_of_Examination"),
      );
      $filter = array(
        "Registration_No" => 139585,
        "Registration_Session" => "2021-22",
        "Roll" => 715,
        "No" => 10136,
        "Year_of_Examination" => 2023,
      );


      $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_rows($filter);


      // if ((!empty((array)$ahsecmarksheet_data) && count((array)$ahsecmarksheet_data) > 1 && $this->input->post("status") == "UPDATE_DATA") || (empty((array)$ahsecmarksheet_data) && $this->input->post("status") == "INSERT_DATA")) {
      //   $this->session->set_flashdata('error', 'ERROR: unable to update records as we have found multiple records for the reg. no , session, roll and no provided in our database!');
      //   redirect('spservices/ahsec_correction/actions/app_pre_admin/' . $objId);
      // }

      $form_data['Mark_Sheet_No'] = $this->input->post("ms_no");
      $form_data['Stream'] = $this->input->post("stream");
      $form_data['Marksheet_Date'] = date("d-m-Y", strtotime($this->input->post("mrk_date")));
      $form_data['Marksheet_Issue_Date'] = date("d-m-Y", strtotime($this->input->post("mrk_issue_date")));
      $form_data['Candidate_Name'] = $this->input->post("mrk_candidate_name");
      $form_data['Father_Name'] = $this->input->post("mrk_father_name");
      $form_data['Mother_Name'] = $this->input->post("mrk_mother_name");
      $form_data['School_College_Name'] = $this->input->post("mrk_school_name");


      if ($this->input->post("status") == "UPDATE_DATA") {
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
        //START: Registration Data Update
        $registration_data = [
          'candidate_name' => $this->input->post("mrk_candidate_name"),
          'father_name' => $this->input->post("mrk_father_name"),
          'mother_name' => $this->input->post("mrk_mother_name"),
          'institution_name' => $this->input->post("mrk_school_name")
        ];
        $filter_reg = array(
          "registration_number" =>  $this->input->post("mrk_rc_no"),
          "registration_session" => $this->input->post("mrk_rc_session"),
        );
        $this->ahsecregistration_model->update_where($filter_reg, $registration_data);
        //END: Registration Data Update

      } else if ($this->input->post("status") == "INSERT_DATA") {
        $form_data['Admit_Card_Serial_No'] = $this->input->post("Admit_Card_Serial_No");
        $form_data['Certificate_Serial_No'] = $this->input->post("Certificate_Serial_No");

        $sub_sl_no = $this->input->post("sub_sl_no");
        $Sub_Code = $this->input->post("Sub_Code");
        $Sub_Pap_Indicator = $this->input->post("Sub_Pap_Indicator");
        $Sub_Actual_Marks = $this->input->post("Sub_Actual_Marks");
        $Sub_Pr_Marks = $this->input->post("Sub_Pr_Marks");
        $Sub_Grace_Marks = $this->input->post("Sub_Grace_Marks");
        $Sub_Tot_Marks = $this->input->post("Sub_Tot_Marks");

        // Initialize an array to store the combined subject indices
        $combinedIndices = [];

        for ($i = 1; $i <= 16; $i++) {
          $index = array_search($i, $sub_sl_no);
          if ($index !== false) {
            $subjectCodeName = isset($Sub_Code[$index]) ? $this->extractSubjectCodeAndName($Sub_Code[$index]) : '';
            // Check if Subject_Code is "BOTA" or "ZOOL"
            if ($subjectCodeName['Subject_Code'] === "BOTA") {
              $botanyIndex = $index; // Store the index for "Botany"
              $BotanyLabelId = $i;
            } elseif ($subjectCodeName['Subject_Code'] === "ZOOL") {
              $zoologyIndex = $index; // Store the index for "Zoology"
              $ZoologyLabelId = $i;
            } else {
              // For other subjects, process them individually
              $form_data['Sub' . $i . '_Code'] = $subjectCodeName['Subject_Code'];
              $form_data['Sub' . $i . '_Pap_Indicator'] = isset($Sub_Pap_Indicator[$index]) ? $Sub_Pap_Indicator[$index] : '';
              $form_data['Sub' . $i . '_Name'] = $subjectCodeName['Subject_Name'];
              $form_data['Sub' . $i . '_Th_Marks'] = isset($Sub_Actual_Marks[$index]) ? $Sub_Actual_Marks[$index] : '';
              $form_data['Sub' . $i . '_Pr_Marks'] = isset($Sub_Pr_Marks[$index]) ? $Sub_Pr_Marks[$index] : '';
              $form_data['Sub' . $i . '_Grace_Marks'] = isset($Sub_Grace_Marks[$index]) ? $Sub_Grace_Marks[$index] : '';
              $form_data['Sub' . $i . '_Tot_Marks'] = isset($Sub_Tot_Marks[$index]) ? $Sub_Tot_Marks[$index] : '';
            }
          } else {
            // Set default values for non-existent entries
            $form_data['Sub' . $i . '_Code'] = '';
            $form_data['Sub' . $i . '_Pap_Indicator'] = '';
            $form_data['Sub' . $i . '_Name'] = '';
            $form_data['Sub' . $i . '_Th_Marks'] = '';
            $form_data['Sub' . $i . '_Pr_Marks'] = '';
            $form_data['Sub' . $i . '_Grace_Marks'] = '';
            $form_data['Sub' . $i . '_Tot_Marks'] = '';
          }
        }

        // Check if there are combined subjects (Botany and Zoology) to process
        if (isset($BotanyLabelId) && isset($zoologyIndex)) {
          // Set values for the combined entry "Biology"
          $form_data['Sub' . $BotanyLabelId . '_Code'] = "BIOL";
          $form_data['Sub' . $BotanyLabelId . '_Pap_Indicator'] = isset($Sub_Pap_Indicator[$botanyIndex]) ? $Sub_Pap_Indicator[$botanyIndex] : '';
          $form_data['Sub' . $BotanyLabelId . '_Name'] = "Biology";

          // Combine Th_Marks for "Botany" and "Zoology"
          $form_data['Sub' . $BotanyLabelId . '_Th_Marks'] =  (isset($Sub_Actual_Marks[$botanyIndex]) ? $Sub_Actual_Marks[$botanyIndex] : '0') . '+' . (isset($Sub_Actual_Marks[$zoologyIndex]) ? $Sub_Actual_Marks[$zoologyIndex] : '0');

          // Combine Pr_Marks for "Botany" and "Zoology"
          $form_data['Sub' . $BotanyLabelId . '_Pr_Marks'] = (isset($Sub_Pr_Marks[$botanyIndex]) ? $Sub_Pr_Marks[$botanyIndex] : '0') . '+' . (isset($Sub_Pr_Marks[$zoologyIndex]) ? $Sub_Pr_Marks[$zoologyIndex] : '0');
          // Combine Grace_Marks for "Botany" and "Zoology"
          $form_data['Sub' . $BotanyLabelId . '_Grace_Marks'] = (isset($Sub_Grace_Marks[$botanyIndex]) ? $Sub_Grace_Marks[$botanyIndex] : '0') . '+' . (isset($Sub_Grace_Marks[$zoologyIndex]) ? $Sub_Grace_Marks[$zoologyIndex] : '0');

          // Combine Tot_Marks for "Botany" and "Zoology"
          $form_data['Sub' . $BotanyLabelId . '_Tot_Marks'] = (isset($Sub_Tot_Marks[$botanyIndex]) ? $Sub_Tot_Marks[$botanyIndex] : '0') . '+' . (isset($Sub_Tot_Marks[$zoologyIndex]) ? $Sub_Tot_Marks[$zoologyIndex] : '0');


          // Clear the original "Botany" and "Zoology" entries
          $form_data['Sub' . $ZoologyLabelId . '_Code'] = '';
          $form_data['Sub' . $ZoologyLabelId . '_Pap_Indicator'] = '';
          $form_data['Sub' . $ZoologyLabelId . '_Name'] = '';
          $form_data['Sub' . $ZoologyLabelId . '_Th_Marks'] = '';
          $form_data['Sub' . $ZoologyLabelId . '_Pr_Marks'] = '';
          $form_data['Sub' . $ZoologyLabelId . '_Grace_Marks'] = '';
          $form_data['Sub' . $ZoologyLabelId . '_Tot_Marks'] = '';
        }

      }

      $form_data['Total_Marks_in_Words'] = $this->input->post("Total_Marks_in_Words");
      $form_data['Total_Marks_in_Figure'] = $this->input->post("Total_Marks_in_Figure");
      $form_data['Total_Grace_in_Figure'] = $this->input->post("Total_Grace_in_Figure");
      $form_data['Result'] = $this->input->post("Result");
      $form_data['Remarks2'] = $this->input->post("Remarks2");
      $form_data['ENVE_Grade'] = $this->input->post("ENVE_Grade");
      $form_data['Photo ID'] =   123456789;
      pre($form_data);


      if ($this->input->post("status") == "UPDATE_DATA") {
        $this->ahsecmarksheet_model->update_where($filter, $form_data);
      } else if ($this->input->post("status") == "INSERT_DATA") {


      }
      $this->session->set_flashdata('success', 'Data has been successfully updated!');
      redirect('spservices/ahsec_correction/actions/app_pre_admin/' . $objId);
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

    if ($registration_data) {
      foreach ($registration_data as $key => $value) {
        unset($value->_id);
      }
    }

    $marksheet_data = "";
    $filter34 = array(
      "Registration_No" => (int) $reg_no,
      "Registration_Session" => $session,
    );
    $marksheet_data = $this->ahsecmarksheet_model->get_rows($filter34);

    if ($marksheet_data) {
      foreach ($marksheet_data as $key => $value) {
        unset($value->_id);
      }
    }

    $dbRow = $this->registration_model->get_by_doc_id($objId);

    $inputs = [
      'appl_ref_no' => $dbRow->service_data->appl_ref_no,
      'registration_data' => $registration_data,
      'marksheet_data' => $marksheet_data
    ];
    $this->ahsecolddata_model->insert($inputs);
    // End of Store old data
  }

  public function extractRefNo($string)
  {
    $pattern = "/(\d+)$/";
    preg_match($pattern, $string, $matches);

    if (isset($matches[1])) {
      $str = $matches[1];
      return $str;
    } else {
      return null;
    }
  }

  public function extractSubjectCodeAndName($subjectString)
  {
    $parts = explode('|', $subjectString);
    if (count($parts) === 2) {
      $subjectCode = trim($parts[0]);
      $subjectName = trim($parts[1]);
      return array(
        'Subject_Code' => $subjectCode,
        'Subject_Name' => $subjectName
      );
    } else {
      return array(
        'error' => 'Invalid Subject Format'
      );
    }
  }
}
