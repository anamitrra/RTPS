
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
    $this->load->library('../modules/spservices/controllers/office/sms_scheduler');
    $this->load->library("ciqrcode");
  }

  public function generate_certificate($appl_no = null, $service_id = null)
  {
    $reg_con_arr = array("CON_REG_PWDB_1", "CON_REG_PWDB_2", "CON_REG_PHED_1", "CON_REG_PHED_2", "CON_REG_WRD_1", "CON_REG_WRD_2","CON_REG_GMC_1","CON_REG_GMC_2");
    $ren_con_arr = array("CON_REN_PWDB_1", "CON_REN_PWDB_2", "CON_REN_PHED_1", "CON_REN_PHED_2", "CON_REN_WRD_1", "CON_REN_WRD_2");
    $upgr_con_arr = array("CON_UPGR_PWDB_1", "CON_UPGR_PHED_1", "CON_UPGR_WRD_1");

    $filename = str_replace("/", "-", $appl_no);
    $pdata = (array)$this->application_model->get_single_application($appl_no);
    if (in_array($pdata[0]->service_data->service_id, $reg_con_arr)) {
      $html = $this->load->view('registration_of_contractors/output_certificate', array('response' => $pdata[0]), true);
    } else if (in_array($pdata[0]->service_data->service_id, $upgr_con_arr)) {
      $html = $this->load->view('registration_of_contractors/output_certificate_upgr', array('response' => $pdata[0]), true);
    } else if (in_array($pdata[0]->service_data->service_id, $ren_con_arr)) {
      $html = $this->load->view('registration_of_contractors/renewal_output_certificate', array('response' => $pdata[0]), true);
    }

    $this->load->library('dpdf');
    $pdf_path = $this->dpdf->createPDF($html, $filename, 'A4', 'portrait', 'docs/' . $pdata[0]->service_data->service_id, true);
    return $pdf_path;
  }

  public function get_dscPosition()
  {
    $posArr = array(
      'stampingX' => 180,
      'stampingY' => 360,
    );
    return $posArr;
  }

  public function get_dscPosition_1()
  {
    $posArr = array(
      'stampingX' => 160,
      'stampingY' => 230,
    );
    return $posArr;
  }

}
