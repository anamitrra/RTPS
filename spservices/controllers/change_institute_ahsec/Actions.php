
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
    $this->load->model('upms/levels_model');
    $this->load->model('upms/users_model');
    $this->load->model('upms/applications_model');
    $this->load->model('duplicatecertificateahsec/ahsecregistration_model');
    $this->load->model('migrationcertificateahsec/registration_model');
    $this->load->helper("cifileupload");
    $this->load->library('../modules/spservices/controllers/office/sms_scheduler');
  }
  public function preview_certificate($appl_no = null)
  {
    $certificate_no = $this->getID($appl_no);
    $updateCertificateNo = [
      'form_data.certificate_no' => $certificate_no
    ];
    $this->applications_model->update_where(['service_data.appl_ref_no' => $appl_no], $updateCertificateNo);
    $qrcode_path = 'storage/docs/common_qr/';
    $pathname = FCPATH . $qrcode_path;
    if (!is_dir($pathname)) {
      mkdir($pathname, 0777, true);
      file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
    }
    $pdata = (array)$this->application_model->get_single_application($appl_no);
    // $link = base_url('spservices/verify-certificate/' . $pdata[0]->_id->{'$id'});
    $link = base_url('https://sewasetu.assam.gov.in/');
    $filename = str_replace("/", "-", $pdata[0]->_id->{'$id'});
    $qrname = $filename . ".png";
    $file_name = $qrcode_path . $qrname;
    // QRcode::png($link, $file_name);
    $base64_certificate_path = base64_encode('spservices/change_institute_ahsec/actions/download_certificate/' . $pdata[0]->_id->{'$id'});

    $this->load->view('upms/includes/header');
    if($this->extractServiceId($appl_no) =="AHSECCHINS"){
      $this->load->view('change_institute_ahsec/certificate', array('dbrow' => $pdata[0], 'qr' => $file_name, 'certificate_no' => $certificate_no,'certificate_path' => $base64_certificate_path,  'user_type' => 'official'));
    }
    $this->load->view('upms/includes/footer');
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

  $this->load->view('upms/includes/header');
    if($this->extractServiceId($appl_no) =="AHSECCHINS"){
      $this->load->view('change_institute_ahsec/certificate', array('dbrow' => $pdata[0], 'qr' => $file_name, 'certificate_no' => $pdata[0]->form_data->certificate_no,'certificate_path' => $pdata[0]->form_data->certificate, 'qr' => $file_name,  'user_type' => ''));
    }
    $this->load->view('upms/includes/footer');
}
  public function generate_certificate($appl_no = null)
  {
    $certificate_no = $this->getID(7);
    $filename = str_replace("/", "-", $appl_no);
    $pdata = (array)$this->application_model->get_single_application($appl_no);
    $html = $this->load->view('acmr_reg_of_addl_degrees/output_certificate', array('data' => $pdata, 'certificate_no' => $certificate_no), true);
    $this->load->library('dpdf');
    $pdf_path = $this->dpdf->createPDF($html, $filename, 'A4', 'landscape', 'ACMR/' . $pdata[0]->service_data->service_id, true);
    return $pdf_path;
  }
  private function my_transactions()
  {
      $user = $this->session->userdata();
      if (isset($user['role']) && !empty($user['role'])) {
          redirect(base_url('iservices/admin/my-transactions'));
      } else {
          redirect(base_url('iservices/transactions'));
      }
  }

  function getID($length)
  {
    $certificate_no = $this->generateID($length);
    while ($this->application_model->get_row(["service_data.certificate_no" => $certificate_no])) {
      $certificate_no = $this->generateID($length);
    } //End of while()
    return $certificate_no;
  } //End of getID()

  public function generateID($length)
  {
    $number = '';
    for ($i = 0; $i < $length; $i++) {
      $number .= rand(0, 9);
    }
    $str = "ACMRREGAD/" . date('Y') . "/" . $number;
    return $str;
  } //End of generateID()

  function extractServiceId($appl_ref_no) {
    $pattern = '/RTPS-([A-Z]+)\/\d{4}\/\d+/'; // Regular expression pattern

    if (preg_match($pattern, $appl_ref_no, $matches)) {
        // $matches[0] will contain the entire matched pattern
        // $matches[1] will contain the service ID captured by the first group in parentheses
        return $matches[1];
    } else {
        return "Service ID not found";
    }
}
  public function get_dscPosition()
  {
    $posArr = array(
      'stampingX' => 200,
      'stampingY' => 250,
    );
    return $posArr;
  } //End of generateID()


  public function app_pre_admin($objId = null)
{
  $dbRow = $this->registration_model->get_by_doc_id($objId);

  $filter1 = array(
    "registration_number" => $dbRow->form_data->ahsec_reg_no,
    "registration_session" => $dbRow->form_data->ahsec_reg_session,
  );


  $commencing_dates=$this->registration_model->getCommencings();
    
    if(isset($ahsecmarksheet_data->Stream))
    {
      $filter_strem= array(
      "stream" => $ahsecmarksheet_data->Stream ?? null,
    );
      $subjects=$this->registration_model->get_stream_subjects($filter_strem);
    }else
    {
      $subjects=$this->registration_model->getSubjects();
    }
  $reg_data = $this->ahsecregistration_model->get_row($filter1);
  $this->load->view('upms/includes/header');
  $this->load->view('change_institute_ahsec/edit_admin_preview', array('dbrow' => $dbRow, 'reg_data' => $reg_data, 'commencing_dates'=> $commencing_dates ,'subjects'=>$subjects ));
  $this->load->view('upms/includes/footer');

}

  public function eCopy_preview($objId = null)
  {
      
      $dbRow = $this->registration_model->get_by_doc_id($objId);
      $pdata[] = $dbRow;
      $appl_no = $dbRow->service_data->appl_ref_no;     
     

      $this->load->view('upms/includes/header');
      if($this->extractServiceId($appl_no) =="AHSECCHINS"){
        $this->load->view('change_institute_ahsec/certificate', array('dbrow' => $pdata[0], 'qr' => '', 'certificate_no' => $pdata[0]->form_data->certificate_no ?? '' ,'certificate_path' => $pdata[0]->form_data->certificate ?? '',  'user_type' => ''));
      }
      $this->load->view('upms/includes/footer');

  }

  public function update_reg_master_data($objId = null){
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

    if ($this->form_validation->run() == false) {
      $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
      redirect('spservices/change_institute_ahsec/actions/app_pre_admin/' . $objId);
    } else {

      $new_issue_date = explode('-', $this->input->post("date"));
      $new_issue_date1 = $new_issue_date[2]."/".$new_issue_date[1]."/".$new_issue_date[0];

      $form_data = [
        'sl_no' => (int) $this->input->post("sl_no"),
        'institution_code' => (int) $this->input->post("code_no"),
        'candidate_name' => $this->input->post("candidate_name"),
        'father_name' => $this->input->post("father_name"),
        'mother_name' => $this->input->post("mother_name"),
        'institution_name' => $this->input->post("institution_name"),
        'registration_number' => (int) $this->input->post("reg_no"),
        'issue_date' => $new_issue_date1,
        'registration_session' => $this->input->post("session"),
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
      $this->session->set_flashdata('success', 'Your application has been successfully submitted');
      redirect('spservices/change_institute_ahsec/actions/app_pre_admin/' . $objId);
    }
  }
}
