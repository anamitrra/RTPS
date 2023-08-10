
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
    $get_appl_data = $this->mongo_db->where(['service_data.appl_ref_no' => $appl_no])->get('sp_applications');






    // $certificate_no = $this->getID(7);
    //GENERATE CERTIFICATE NO -START
        $certificate_no = strlen($this->extractRefNo($appl_no)) ? $this->extractRefNo($appl_no): false;
        if(!$certificate_no){
          die('Invalid certificate no!');
        }
    //GENERATE CERTIFICATE NO -END
    $filename = str_replace("/", "-", $appl_no);



    $pdata = (array)$this->application_model->get_single_application($appl_no);
    $html = $this->load->view('acmr_cp_cme/output_certificate', array('data' => $pdata, 'certificate_no' => $certificate_no), true);
    $this->load->library('dpdf');
    $pdf_path = $this->dpdf->createPDF($html, $filename, 'A4', 'portrait', 'ACMR/' .$pdata[0]->service_data->service_id, true);
        
    return $pdf_path;
    // action taken data 
    // $dataToupdate = [
    //   'form_data.certificate_no' =>  $certificate_no,
    // ];
    // update existing data 
    //$this->update_existing($appl_no, $dataToupdate);
    //redirect('spservices/office/dscsign/index/' . base64_encode($appl_no) . '/' . base64_encode($pdf_path));
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
    $str = "AMC/1/" . date('Y') . "/" . $number;
    return $str;
  } //End of generateID()

  public function get_dscPosition()
  {
    $posArr = array(
      'stampingX' => 200,
      'stampingY' => 365,
    );
    return $posArr;
  } 


  public function extractRefNo($string)
  {
    $pattern = "/(\d+)$/";
    preg_match($pattern, $string, $matches);

    if (isset($matches[1])) {
      $str = "AMC/" . date('Y') . "/CME/" . $matches[1];
      return $str;
    } else {
      return null;
    }
  }

  
}
