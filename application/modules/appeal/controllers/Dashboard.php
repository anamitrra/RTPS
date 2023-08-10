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
    }

    /**
     * index
     *
     * @return void
     */
    // officeUsers
    public function officeUsers()
    {

        $this->load->model('users_model');


        $email = $this->session->userdata("email");

        $result = array($this->users_model->getUserEmailAddress($email));
        $result = json_decode(json_encode($result), true);

        $result = ($result);

        $result["result"] =  $result[0];

    


        $this->load->view("dashboard/office_users", $result);
    }

// Office users by username
    public function officeUsersByUsername()
    {

        $this->load->model('users_model');


        $email = $this->session->userdata("username");

        $result = array($this->users_model->getUserByUsername($email));
        $result = json_decode(json_encode($result), true);

        $result = ($result);

        $result["result"] =  $result[0];


        $this->load->view("dashboard/office_users", $result);
    }

    
    public function index() 
    {
        //hasPermission();
        $role = $this->session->userdata("role");
        $data = [];
        $this->load->view("includes/header", array("pageTitle" => "Dashboard"));
        $this->load->view("dashboard/index", $data);
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
        $this->load->view("dashboard/access_denied");
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
        $htm = $this->load->view("ams/action_templates/notice_for_hearing_to_appellant", "", TRUE);
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
