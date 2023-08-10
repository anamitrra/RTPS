
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
    $this->load->library('phpqrcode/qrlib');
    $this->load->library('../modules/spservices/controllers/office/sms_scheduler');
  }

  // public function test(){
  //   $this->load->library('../modules/spservices/controllers/office/sms_scheduler');
  //   $this->sms_scheduler->send_delivery_sms();
  // }

  public function generate_certificate($appl_no=null)
  {
   //GENERATE QR CODE -START
    $qrcode_path = 'storage/docs/acmr_qr/';
    $pathname = FCPATH . $qrcode_path;
    if (!is_dir($pathname)) {
      mkdir($pathname, 0777, true);
      file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
    }
    // pre($pathname);
    $pdata = (array)$this->application_model->get_single_application($appl_no);
    // $link = base_url('spservices/permanent_registration_mbbs/' . $pdata[0]->_id->{'$id'});
    $link = base_url('spservices/acmr-verify-certificate/' . $pdata[0]->_id->{'$id'});
    // pre($link);
    $file_name = str_replace("/", "-", $pdata[0]->_id->{'$id'});
    $qrname = $file_name . ".png";
    $qr_file_name = $qrcode_path . $qrname;
    // pre($qr_file_name);
    QRcode::png($link, $qr_file_name);
    // pre(QRcode);
    //GENERATE QR CODE -END


      //GENERATE CERTIFICATE NO -START
    $certificate_no = strlen($this->extractRefNo($appl_no)) ? $this->extractRefNo($appl_no): false;
    if(!$certificate_no){
      die('Invalid certificate no!');
    }

    // pre($certificate_no);
    //GENERATE CERTIFICATE NO -END
    $filename = str_replace("/", "-", $appl_no);
    $pdata = (array)$this->application_model->get_single_application($appl_no);
     
    $html = $this->load->view('permanent_registration_mbbs/output_certificate', array('data' => $pdata,'qr' => $qr_file_name,'certificate_no' => $certificate_no), true);

   

    
    $this->load->library('dpdf');
    $pdf_path = $this->dpdf->createPDF($html, $filename, 'A4', 'portrait', 'ACMR/' .$pdata[0]->service_data->service_id, true);
    return $pdf_path;
  
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
    $str = "ACMRPROMD/" . date('Y') . "/" . $number;
    return $str;
  } //End of generateID()

  public function get_dscPosition()
  {
    $posArr = array(
      'stampingX' => 200,
      'stampingY' => 250,
    );
    return $posArr;
  } //End of generateID()

    public function extractRefNo($string)
  {
    $pattern = "/(\d+)$/";
    preg_match($pattern, $string, $matches);

    if (isset($matches[1])) {
      $str = "AMC/1/" . date('Y') . "/" . $matches[1];
      return $str;
    } else {
      return null;
    }
  }
}
