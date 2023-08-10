<?php

/**
 * Description of Dashboard
 *
 * @author 
 *
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends Rtps
{
  //put your code here
  public function __construct()
  {
    parent::__construct();
  //  $this->load->model("dashboard_model");
  }
  /**
   * index
   *
   * @return void
   */
  public function index()
  {
    if(!empty($this->session->userdata('role'))){
      $this->load->view("includes/header", array("pageTitle" => "Dashboard"));
      $this->load->view("superadmin/dashboards/dashboard");
      $this->load->view("includes/footer");
    }else{
      redirect(base_url('iservices/admin/login/logout'));
    }
   
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
      $this->load->view("includes/header", array("pageTitle" => "Appeal Dashboard"));
      $this->load->view("dashboards/master_dashboard", $pageData);
      $this->load->view("includes/footer");

  }
  /**
   * access_denied
   *
   * @return void
   */
  public function access_denied()
  {
    $this->load->view("includes/header", array("pageTitle" => "Dashboard"));
    //$this->load->view("dashboards/master_dashboard", $pageData);
    $this->load->view("admin/dashboards/access_denied");
    $this->load->view("includes/footer");
  }
  public function logout()
  {
    parent::logout();
  }


}
