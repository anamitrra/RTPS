
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
    $this->load->helper("cifileupload");
    $this->load->config('spconfig');
  }

  public function generate_certificate($appl_no = null)
  {
    $delivered_dt = date('d-m-Y');
    $renewal_dt = date('d-m-Y', strtotime('+3 years'));
    $check_reg_no = $this->generate_registration_no();
    if ($check_reg_no['status'] == true) {
      $certificate_no = $check_reg_no['data']->cert_no;
    } else {
      $certificate_no = "";
    }
    $this->load->library("ciqrcode");
    $filename = str_replace("/", "-", $appl_no);
    $pdata = (array)$this->application_model->get_single_application($appl_no);
    $html = $this->load->view('employment_nonaadhaar/output_certificate', array('data' => $pdata, 'certificate_no' => $certificate_no, 'delivered_dt' => $delivered_dt, 'renew_dt' => $renewal_dt), true);
    $this->load->library('dpdf');
    $pdf_path = $this->dpdf->createPDF($html, $filename, FALSE, 'A4', 'portrait', 'docs/' . $pdata[0]->service_data->service_id, true);
    return json_encode(array('service'=>'EMP_REG_NA', 'pdf_path' => $pdf_path, 'certificate_no' => $certificate_no, 'deliver_dt' => $delivered_dt, 'renrew_dt' => $renewal_dt));
  }

  public function get_dscPosition()
  {
    $posArr = array(
      'stampingX' => 220,
      'stampingY' => 200,
    );
    return $posArr;
  } //End of generateID()

  public function generate_registration_no()
  {
    //get registration number production
    $url = $this->config->item('emp_certificate_no_url');

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    if (curl_error($ch)) {
      return array('status' => false, 'data' => curl_error($ch));
    } else {
      $response = json_decode($response);
      return array('status' => true, 'data' => $response);
    }
    curl_close($ch);
  }
}
