<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Applications extends Rtps {

    //put your code here
    public function __construct()
    {
      parent::__construct();
     $this->load->model("superadmin/application_model");
     $this->load->model("superadmin/action_task_model");
     $this->load->model("superadmin/User_model");
     $this->load->model("superadmin/Task_list_model");
    //  $this->load->library('dom');
    }

    public function index(){
        $this->load->view("includes/header", array("pageTitle" => " "));
        $this->load->view("superadmin/applications/all_applications");
        $this->load->view("includes/footer");
    }

    public function count_applications(){
      $portal_no = '7';
      $counts = $this->application_model->get_application_count($portal_no);
      // echo $counts->{'0'}->total;
      // print_r($counts);
      // die();
      // echo json_encode($counts);
      return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'total' => $counts->{'0'}->total,
                'under_process' => $counts->{'0'}->under_process,
                'delivered' => $counts->{'0'}->delivered,
                'rejected' => $counts->{'0'}->rejected

            )));

    }
    public function get_all_applications(){
     $applications = $this->application_model->all_applications();
    //  pre($applications);
    $data= array();
     $sl=1;
     $data = array();
     foreach($applications as $value){
        $nestedData["sl_no"]=$sl;
        $nestedData["rtps_trans_id"]=$value->rtps_trans_id;
        $nestedData["trans_ide"] =base64_encode($value->rtps_trans_id);
        $nestedData["name"]=$value->applicant_name;
        $nestedData["mobile_number"]=$value->mobile_number;
        $nestedData["applicant_gender"]=$value->applicant_gender;
        $nestedData["father_name"]=$value->father_name;
        $nestedData["service_name"] = $value->service_name;
        $nestedData["service_id"]=base64_encode($value->service_id);
        $nestedData["task_no"]=base64_encode($value->task_no);
        $nestedData["date"]=date('d/m/Y', strtotime($this->mongo_db->getDateTime($value->created_at??'')));
        $data[] = $nestedData;
        $sl++;
     }
    $json_data = array(
      "data" => $data,
    );
  echo json_encode($json_data);
    }

    public function delivered_application(){
      $this->load->view("includes/header", array("pageTitle" => "Delivered Applications"));
      $this->load->view("superadmin/applications/delivered_applications");
      $this->load->view("includes/footer");
    }

    public function get_delivered_applications(){
      $applications = $this->application_model->get_delivered_applications();
      $data= array();
      // pre($applications);
      $sl=1;
      $data = array();
      foreach($applications as $value){
         $nestedData["sl_no"]=$sl;
         $nestedData["rtps_trans_id"]=$value->rtps_trans_id;
         $nestedData["trans_ide"] =base64_encode($value->rtps_trans_id);
         $nestedData["name"]=$value->applicant_name;
         $nestedData["mobile_number"]=$value->mobile_number;
         $nestedData["service_name"] = $value->service_name;
         $nestedData["date"]=date('d/m/Y', strtotime($this->mongo_db->getDateTime($value->created_at??'')));
         $nestedData["doc"]=$value->documents;
         $data[] = $nestedData;
         $sl++;
      }
     $json_data = array(
       "data" => $data,
     );
        echo json_encode($json_data);
     }

     public function forwarded_application(){
      $this->load->view("includes/header", array("pageTitle" => "Forwarded Applications"));
      $this->load->view("superadmin/applications/forwarded_applications");
      $this->load->view("includes/footer");
    }

    public function get_forwarded_applications(){
      $applications = $this->application_model->get_forwarded_applications();
      // pre($applications);
     $data= array();
      $sl=1;
      $data = array();
      foreach($applications as $value){
         $nestedData["sl_no"]=$sl;
         $nestedData["rtps_trans_id"]=$value->rtps_trans_id;
         $nestedData["trans_ide"] =base64_encode($value->rtps_trans_id);
         $nestedData["name"]=$value->applicant_name;
         $nestedData["mobile_number"]=$value->mobile_number;
         $nestedData["service_name"] = $value->service_name;
         $nestedData["date"]=date('d/m/Y', strtotime($this->mongo_db->getDateTime($value->created_at??'')));
         $nestedData["f_date"]=date('d/m/Y', strtotime($this->mongo_db->getDateTime($value->executed_time??'')));
         $data[] = $nestedData;
         $sl++;
      }
     $json_data = array(
       "data" => $data,
     );
        echo json_encode($json_data);
     }
    //  rejected_application
    public function rejected_application(){
      $this->load->view("includes/header", array("pageTitle" => "Rejected Applications"));
      $this->load->view("superadmin/applications/rejected_applications");
      $this->load->view("includes/footer");
    }
    // get_rejected_applications
    public function get_rejected_applications(){
     $applications = $this->application_model->get_rejected_applications();
     $data= array();
      $sl=1;
      $data = array();
      foreach($applications as $value){
         $nestedData["sl_no"]=$sl;
         $nestedData["rtps_trans_id"]=$value->rtps_trans_id;
         $nestedData["trans_ide"] =base64_encode($value->rtps_trans_id);
         $nestedData["name"]=$value->applicant_name;
         $nestedData["mobile_number"]=$value->mobile_number;
         $nestedData["service_name"] = $value->service_name;
         $nestedData["date"]=date('d/m/Y', strtotime($this->mongo_db->getDateTime($value->submission_date??'')));
         $nestedData["action_date"]=date('d/m/Y', strtotime($this->mongo_db->getDateTime($value->executed_time??'')));
         $nestedData["remarks"]=$value->remarks;
         $data[] = $nestedData;
         $sl++;
      }
     $json_data = array(
       "data" => $data,
     );
        echo json_encode($json_data);
     }
    //  revert_applicant_application
    public function revert_applicant_application(){
      $this->load->view("includes/header", array("pageTitle" => "Rejected Applications"));
      $this->load->view("superadmin/applications/revert_applicant_applications");
      $this->load->view("includes/footer");
    }
        // get_rejected_applications
        public function get_revert_applicant_applications(){
          $applications = $this->application_model->get_revert_applicant_applications();
          $data= array();
           $sl=1;
           $data = array();
           foreach($applications as $value){
              $nestedData["sl_no"]=$sl;
              $nestedData["rtps_trans_id"]=$value->rtps_trans_id;
              $nestedData["trans_ide"] =base64_encode($value->rtps_trans_id);
              $nestedData["name"]=$value->applicant_name;
              $nestedData["mobile_number"]=$value->mobile_number;
              $nestedData["service_name"] = $value->service_name;
              $nestedData["date"]=date('d/m/Y', strtotime($this->mongo_db->getDateTime($value->created_at??'')));
              // $nestedData["f_date"]=date('d/m/Y', strtotime($this->mongo_db->getDateTime($value->executed_time??'')));
              $data[] = $nestedData;
              $sl++;
           }
          $json_data = array(
            "data" => $data,
          );
             echo json_encode($json_data);
        }
    public function action_form($id, $service_id, $action){
      $appl_id = base64_decode($id);
      $action_no = base64_decode($action);
      $service = base64_decode($service_id);
      $data = $this->application_model->get_single_application($appl_id);
      $action_data = $this->Task_list_model->get_action_data($service, $action_no);
      $remarks = $this->action_task_model->get_remarks($appl_id);
      // pre($remarks);
      // pre($action_data);
      // echo json_encode($data);
      $this->load->view("includes/header", array("pageTitle" => "Action Form"));
      $this->load->view("superadmin/applications/action_form", array('data'=>$data, 'task_data'=>$action_data, 'remarks'=>$remarks));
      $this->load->view("includes/footer");
    }


    public function application_details($id){
    $id = base64_decode($id);
    $data = $this->application_model->get_single_application($id);
    $this->load->view("includes/header", array("pageTitle" => "Action Form"));
    $this->load->view("superadmin/applications/application_details", array('data'=>$data));
    $this->load->view("includes/footer");
    // echo json_encode($data);
    }

    public function get_da(){
      $dist =  $this->input->post('dist');
      $data = $this->User_model->get_da_list('DA');
      // $result = (array) json_encode($data);
      // die();
      // var_dump($data);
      // $res[] = $data;

      echo json_encode(array('data' => (array)$data));
    }
    
    public function get_co_list(){
      $dist =  $this->input->post('dist');
      $data = $this->User_model->get_co_list($dist);
      echo json_encode(array('data' => (array)$data));
    }

    public function get_sk_list(){
      $dist =  $this->input->post('dist');
      $data = $this->User_model->get_sk_list($dist);
      echo json_encode(array('data' => (array)$data));
    }

    public function get_lm_list(){
      $dist =  $this->input->post('dist');
      $data = $this->User_model->get_lm_list($dist);
      echo json_encode(array('data' => (array)$data));
    }
    public function pdf(){
      $this->load->library('phpqrcode/qrlib');
      $SERVERFILEPATH = 'storage/qrcode/';
  
        $text = 'WPTBC2208';
        $text1= substr($text, 0,9);
        
        $folder = $SERVERFILEPATH;
        $file_name1 = $text1.".png";
        $file_name = $folder.$file_name1;
        QRcode::png($text,$file_name);
        // pre($file_name);
      $data = $this->application_model->get_single_application('WPTBC2208043496531');
      $this->load->view("superadmin/certificates/schedule_caste", array('data'=>$data, 'qr'=>$file_name));
    }



    
    public function pdf2(){
      $data = $this->application_model->get_single_application('WPTBC2208043496531');
      $this->load->library('Pdf');
$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
// $pdf->SetTitle('Pdf Example');
// $pdf->SetHeaderMargin(30);
// $pdf->SetTopMargin(20);
// $pdf->setFooterMargin(20);
// $pdf->SetAutoPageBreak(true);
// $pdf->SetAuthor('Author');
$pdf->SetFont("Uxa", '', 10);
$pdf->SetDisplayMode('real', 'default');
$pdf->AddPage();
$this->load->view('superadmin/certificates/schedule_caste',array('data'=>$data));
    $html = $this->output->get_output();

// $html = '<p class="mb-0">মান প নং<br> Certificate Number</p>
// <label for="">CERT/SC/2022/00001</label>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// $pdf->Write(5, 'CodeIgniter TCPDF Integration');
ob_end_clean();
$pdf->Output('pdfexample.pdf', 'I');
    }


    public function html2pdf(){
		  $this->load->library('phpqrcode/qrlib');
		$this->load->helper('url');

      // echo  base_url('storage/qrcode');die();
    $SERVERFILEPATH = $_SERVER['DOCUMENT_ROOT'].'/rtps_staging/rtps/storage/qrcode/';

			$text = 'ok';
			$text1= substr($text, 0,9);
			
			$folder = $SERVERFILEPATH;
			$file_name1 = $text1."-Qrcode" . rand(2,200) . ".png";
			$file_name = $folder.$file_name1;
			QRcode::png($text,$file_name);
			echo $file_name;
			echo"<center><img src=".base_url().'storage/qrcode/'.$file_name1."></center";
    }
}