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
  //  $this->load->model("dashboard_model");
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
    $this->load->view("includes/header", array("pageTitle" => "Dashboard"));
    $this->load->view("admin/dashboards/master_dashboard");
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
    $this->load->view("dashboards/access_denied");
    $this->load->view("includes/footer");
  }
  public function logout()
  {
    parent::logout();
  }


}
