<?php


if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
class Redirectional_payment_response extends Frontend
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('redirectional_model');
    $this->load->model('portals_model');
    $this->config->load('rtps_services');
    $this->load->library('AES');
  }
  
  public function show_error()
  {
    $this->load->view('includes/frontend/header');
    $this->load->view('error');
    $this->load->view('includes/frontend/footer');
  }

  public function response()
  {
    $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
    $response = $_POST;
    $this->redirectional_model->update_row(array('department_id' => $DEPARTMENT_ID), array("service_data.appl_status" => $_POST['STATUS'],"pfc_payment_status" => $_POST['STATUS'], "pfc_payment_response" => $response));
    if ($_POST['STATUS'] === 'Y') {
      //check the grn for valid transactions
      if ($this->checkgrn($DEPARTMENT_ID, true) === $_POST['GRN']) {
        
        redirect(base_url('iservices/admin/redirectional_payment_response/ack?app_ref_no=') . $DEPARTMENT_ID);
      } else {
        //grn does not match Something went wrong
        echo "Something wrong in middle";
        $this->redirectional_model->update_row(array('department_id' => $DEPARTMENT_ID), array("service_data.appl_status"=>'P',"pfc_payment_status" => "P", "pfc_payment_response.STATUS" => 'P'));
        $this->show_error();
      }

      //  $this->show_vahan_acknowledgment($DEPARTMENT_ID);
    } else {
      $this->show_error();
    }
  }

  public function checkgrn($DEPARTMENT_ID = null, $check = false)
  { // TODO: need to check which are params to update
    $transaction_data = $this->redirectional_model->get_row(array('department_id' => $DEPARTMENT_ID));
    if ($DEPARTMENT_ID) {
      $OFFICE_CODE = $transaction_data->payment_params->OFFICE_CODE;
      $am1 = isset($transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
      $am2 = isset($transaction_data->payment_params->CHALLAN_AMOUNT) ? $transaction_data->payment_params->CHALLAN_AMOUNT : 0;
      $AMOUNT = $am1 + $am2;
      $string_field = "DEPARTMENT_ID=" . $DEPARTMENT_ID . "&OFFICE_CODE=" . $OFFICE_CODE . "&AMOUNT=" . $AMOUNT;
      $url = $this->config->item('egras_grn_cin_url');
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 3);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $string_field);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      // curl_setopt ($ch, CURLOPT_CAINFO, dirname(FILE)."/123.assam.gov.in.crt");
      curl_setopt($ch, CURLOPT_FAILONERROR, FALSE);
      curl_setopt($ch, CURLOPT_NOBODY, false);
      $result = curl_exec($ch);
      curl_close($ch);
      $res = explode("$", $result); //pre($res);
      if ($check) {
        return isset($res[4]) ? $res[4] : '';
      }
      if ($res) {
        $STATUS = isset($res[16]) ? $res[16] : '';
        $GRN = isset($res[4]) ? $res[4] : '';
        //  var_dump($STATUS);var_dump($GRN);die;
        //if($STATUS === "Y"){
        $this->redirectional_model->update_row(
          array('department_id' => $DEPARTMENT_ID),
          array(
            "pfc_payment_response.GRN" => $GRN,
            "pfc_payment_response.AMOUNT" => isset($res[6]) ? $res[6] : '',
            "pfc_payment_response.PARTYNAME" => isset($res[18]) ? $res[18] : '',
            "pfc_payment_response.TAXID" => isset($res[20]) ? $res[20] : '',
            "pfc_payment_response.DEPARTMENT_ID" => isset($res[2]) ? $res[2] : '',
            "pfc_payment_response.BANKNAME" => isset($res[22]) ? $res[22] : '',
            "pfc_payment_response.BANKCODE" => isset($res[8]) ? $res[8] : '',
            "pfc_payment_response.ENTRY_DATE" => isset($res[24]) ? $res[24] : '',
            "pfc_payment_response.STATUS" => $STATUS,
            "pfc_payment_response.PRN" => isset($res[12]) ? $res[12] : '',
            "pfc_payment_response.TRANSCOMPLETIONDATETIME" => isset($res[14]) ? $res[14] : '',
            "pfc_payment_response.BANKCIN" => isset($res[10]) ? $res[10] : '',
            'pfc_payment_status' => $STATUS,
            "service_data.appl_status"=>$STATUS
          )
        );
        //  }
      }
    }
    redirect(base_url('iservices'));
  }

  public function cin_response()
  {
    if (!empty($_POST)) {
      if (!empty($_POST['DEPARTMENT_ID'])) {
        $DEPARTMENT_ID = $_POST['DEPARTMENT_ID'];
        $STATUS = $_POST['STATUS'];
        $BANKCIN = $_POST['BANKCIN'];
        $this->redirectional_model->update_row(array('department_id' => $DEPARTMENT_ID), array(
          "pfc_payment_response.BANKCIN" => $BANKCIN,
          "pfc_payment_response.STATUS" => $STATUS,
          "pfc_payment_response.TAXID" => $_POST['TAXID'],
          "pfc_payment_response.PRN" => $_POST['PRN'],
          "pfc_payment_response.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
          'pfc_payment_status' => $STATUS,
          "service_data.appl_status"=>$STATUS
        ));
      }
    }

    redirect(base_url('iservices'));
  }
  public function ack()
  {

    if (empty($_GET['app_ref_no'])) {
      redirect(base_url("iservices"));
      // exit("Something Went wrong.");
    }
    if(!empty($this->session->userdata('role'))){
      $is_kiosk = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
    }else{
      $is_kiosk = false;
    }
    $app_ref_no = $_GET['app_ref_no'];
    if($is_kiosk && ($is_kiosk === "PFC")){
      $application_details = $this->redirectional_model->get_application_details(array("department_id" => $app_ref_no,"service_data.applied_by"=>new MongoDB\BSON\ObjectId($this->session->userdata("userId")->{'$id'}),"pfc_payment_status"=>"Y"));
    }else if($is_kiosk && ($is_kiosk === "CSC")){
      $application_details = $this->redirectional_model->get_application_details(array("department_id" => $app_ref_no,"service_data.applied_by"=>$this->session->userdata('userId'),"pfc_payment_status"=>"Y"));
    }else{
      redirect('iservices/transactions');
      return;
    }


    
    $data = array(
      "app_ref_no"=>$application_details->service_data->appl_ref_no,
      "service_name"=>$application_details->service_data->service_name,
      "applicant_name"=>$application_details->form_data->applicant_name,
      "GRN"=>isset($application_details->pfc_payment_response->GRN) ? $application_details->pfc_payment_response->GRN : "",
      "AMOUNT"=>isset($application_details->pfc_payment_response->AMOUNT) ? $application_details->pfc_payment_response->AMOUNT : "",
      "BANKNAME"=>isset($application_details->pfc_payment_response->BANKNAME) ? $application_details->pfc_payment_response->BANKNAME : "",
      "BANKCODE"=>isset($application_details->pfc_payment_response->BANKCODE) ? $application_details->pfc_payment_response->BANKCODE : "",
      "ENTRY_DATE"=>isset($application_details->pfc_payment_response->ENTRY_DATE) ? $application_details->pfc_payment_response->ENTRY_DATE : "",
      "STATUS"=>isset($application_details->pfc_payment_response->STATUS) ? $application_details->pfc_payment_response->STATUS : "",
      "PRN"=>isset($application_details->pfc_payment_response->PRN) ? $application_details->pfc_payment_response->PRN : "",
      "BANKCIN"=>isset($application_details->pfc_payment_response->BANKCIN) ? $application_details->pfc_payment_response->BANKCIN : "",
      "TRANSCOMPLETIONDATETIME"=>isset($application_details->pfc_payment_response->TRANSCOMPLETIONDATETIME) ? $application_details->pfc_payment_response->TRANSCOMPLETIONDATETIME : "",
    );
    
    $data['back_to_dasboard'] = '<a href="' . base_url('iservices/admin/my-transactions') . '" class="btn btn-primary mb-2"  >Back To DASHBOARD</a>';


    $this->load->view('includes/frontend/header');
    $this->load->view('admin/redirectional/ack', $data);
    $this->load->view('includes/frontend/footer');
  }

    
 
}
