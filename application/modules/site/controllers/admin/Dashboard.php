<?php

/**
 * Description of Dashboard
 *
 * @author Prasenjit Das
 *
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends Rtps
{
  //put your code here
  public function __construct()
  {
    parent::__construct();
    $this->load->model("admin/dashboard_model");
  }
  /**
   * index
   *
   * @return void
   */
  public function index()
  {
    
    //hasPermission();
    // $data = $this->load->model("dashboard_model");
    $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Dashboard"));
    $this->load->view("admin/dashboards/dashboard");
    $this->load->view("admin/includes/footer");
  }
  /**
   * load_dashboard
   *
   * @param mixed $type
   * @return void
   */
  public function load_dashboard($type = NULL)
  {
    $pageData = array();
      $this->load->view("admin/includes/header", array("pageTitle" => "Appeal Dashboard"));
      $this->load->view("admin/dashboards/master_dashboard", $pageData);
      $this->load->view("admin/includes/footer");
    
  }
  /**
   * access_denied
   *
   * @return void
   */
  public function access_denied()
  {
    $this->load->view("admin/includes/header", array("pageTitle" => "Dashboard"));
    //$this->load->view("dashboards/master_dashboard", $pageData);
    $this->load->view("admin/dashboards/access_denied");
    $this->load->view("admin/includes/footer");
  }
 

  public function pdf()
  {
    $this->load->library('Pdf');
    $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetHeaderMargin(10);
    $pdf->SetTopMargin(10);
    $pdf->setFooterMargin(10);
    $pdf->SetAuthor('Prasenjit Das');
    //$pdf->setFooterData(array(0, 65, 0), array(0, 65, 127));
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('helvetica', '', 8, '', true);
    $pdf->AddPage();
    $htm=$this->load->view("ams/action_templates/notice_for_hearing_to_appellant","",TRUE);
//    echo $htm;die;
//    pre($htm);
    $pdf->writeHTML($htm, true, 0, true, 0);


    $pdf->lastPage();

    $filename = 'sample' . date("YmdHis", time()) . '.pdf';
    $pdf->Output($filename, 'I');
    //Close and output PDF document
    //$pdf->Output('example_021.pdf', 'I');
  }
}
