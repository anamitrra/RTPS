<?php

/**
 * Description of Mis
 *
 * @author Prasenjit Das
 *
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Mis extends Rtps
{
  //put your code here
  public function __construct()
  {
    parent::__construct();
    $this->load->model("dashboard_model");
  }
  /**
   * index
   *
   * @return void
   */
  public function index()
  {
    //hasPermission();
    $data = $this->load->model("dashboard_model");
    $this->load->view("includes/header", array("pageTitle" => "Mis"));
    $this->load->view("dashboards/master_dashboard");
    $this->load->view("includes/footer");
  }
  public function Servicewise()
  {
    //hasPermission();
   // $data = $this->load->model("dashboard_model");
  $this->load->model("department_model");
    $data['departments']=$this->department_model->get_departments();
    $this->load->view("includes/header", array("pageTitle" => "Mis"));
    $this->load->view("dashboards/servicewise_data",$data);
    $this->load->view("includes/footer");
  }

  public function depertmentwise()
  {
  $this->load->model("department_model");
    $data['departments']=$this->department_model->get_departments();
    $this->load->view("includes/header", array("pageTitle" => "Mis"));
    $this->load->view("dashboards/departmentwise_data",$data);
    $this->load->view("includes/footer");
  }

  public function officewise()
  {
  $this->load->model("department_model");
    $data['departments']=$this->department_model->get_departments();
    $this->load->view("includes/header", array("pageTitle" => "Mis"));
    $this->load->view("dashboards/officewise_data",$data);
    $this->load->view("includes/footer");
  }
  public function get_services($dept_id){
    $this->load->model("department_model");
    $services=$this->department_model->get_service_list_by_department_id($dept_id);
    echo json_encode($services);
  }
  public function districtewise()
  {
  $this->load->model("department_model");
  $this->load->model("offices_model");
    $data['departments']=$this->department_model->get_departments();
    $data['districts']=$this->offices_model->get_districts();
    $this->load->view("includes/header", array("pageTitle" => "Mis"));
    $this->load->view("dashboards/districtwise_data",$data);
    $this->load->view("includes/footer");
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
      $this->load->view("includes/header", array("pageTitle" => "Appeal Mis"));
      $this->load->view("dashboards/master_dashboard", $pageData);
      $this->load->view("includes/footer");
    
  }

  public function rtps_commission(){
    $this->load->view("includes/header", array("pageTitle" => "RTPS Commission"));
    $this->load->view("dashboards/rtps_commission");
    $this->load->view("includes/footer");
  }
  public function search(){
    $this->load->view("includes/header", array("pageTitle" => "Search  Application"));
    $this->load->view("dashboards/search_application");
    $this->load->view("includes/footer");
  }
  public function find_application(){
    $this->load->model('applications_model');
    $app_ref_no=$this->input->post('app_ref_no');
    if(empty($app_ref_no)){
      echo "Application Not Found";
      return;
    }
    $ref_no=$this->applications_model->get_objectid_by_ref($app_ref_no);
    if(empty($ref_no)){
      echo "Application Not Found";
      return;
    }
    $data = $this->applications_model->get_by_doc_id($ref_no);
    echo $this->load->view("applications/view_application", array('data' => $data->initiated_data, 'execution_data' => $data->execution_data));
  }
  /**
   * access_denied
   *
   * @return void
   */
  public function access_denied()
  {
    $this->load->view("includes/header", array("pageTitle" => "Mis"));
    //$this->load->view("dashboards/master_dashboard", $pageData);
    $this->load->view("dashboards/access_denied");
    $this->load->view("includes/footer");
  }
  public function logout()
  {
    parent::logout();
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
