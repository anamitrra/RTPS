<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Paymentresponse extends frontend {

    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('appointmentbooking/appointmentbookings_model');
        $this->load->config('spconfig');
        $this->load->helper('smsprovider');
    }//End of __construct()

    private function show_error() {
        $this->load->view('includes/frontend/header');
        $this->load->view('error');
        $this->load->view('includes/frontend/footer');
    }//End of show_error()

    private function my_transactions() {
        if ($this->session->role) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }//End of if else
    }//End of my_transactions()

    public function paymentmade() {
        $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
        $response = $_POST;
        $this->appointmentbookings_model->update_row(array('form_data.department_id' => $DEPARTMENT_ID), array("form_data.pfc_payment_status" => $_POST['STATUS'], "form_data.pfc_payment_response" => $response));
        if ($_POST['STATUS'] === 'Y') {
            //if (true) { //For app_test server
            if ($this->checkgrn($DEPARTMENT_ID, true) === $_POST['GRN']) {
                $this->post_data($DEPARTMENT_ID);
            } else {
                $this->appointmentbookings_model->update_row(array('form_data.department_id' => $DEPARTMENT_ID), array("form_data.pfc_payment_status" => "P", "form_data.pfc_payment_response.STATUS" => 'P'));
                $this->show_error();
            }//End of if else
        } else {
            $this->show_error();
        }//End of if else
    }//End of paymentmade()

    public function post_data($department_id = null) {
        $this->load->model('logreports_model');
        $nowTime = date('Y-m-d H:i:s');
        $entryTime = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $dbrow = $this->appointmentbookings_model->get_row(['form_data.department_id' => $department_id]);
        if (count((array) $dbrow)) {
            $obj_id = $dbrow->{'_id'}->{'$id'};           
            $appl_ref_no_temp = $dbrow->service_data->appl_ref_no;
            $appl_ref_no = "RTPS" . substr($appl_ref_no_temp, 4);     
            
            $url = $this->config->item('url');
            $postUrl = $url . "reg/postdeedapplication.php";
            $newStatus = "PR";
            $data_to_update = array(
                'service_data.appl_ref_no' => $appl_ref_no,
                'service_data.appl_status' => $newStatus,
                'form_data.payment_status' => 'PAID',
                'form_data.payment_time' => $entryTime,
                'service_data.submission_date' => $entryTime
            );                            
            $processing_history = $dbrow->processing_history??array();
            $processing_history[] = array(
                "processed_by" => "Payment made by ".$dbrow->form_data->applicant_name,
                "action_taken" => "Payment completed",
                "remarks" => "Payment made by ".$dbrow->form_data->applicant_name,
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            );
            
            $data = array(
                "application_ref_no" => $appl_ref_no,
                "spId" => array("applId" => $obj_id),
                "sro_code" => $dbrow->form_data->sro_code,
                "date_of_appointment" => $dbrow->form_data->appointment_date,
                "applicant_name" => $dbrow->form_data->applicant_name,
                "applicants_father_name" => $dbrow->form_data->fathers_name,
                "address" => $dbrow->form_data->applicant_address,
                "mobile_no" => $dbrow->form_data->mobile_number,
                "email" => $dbrow->form_data->email_id,
                "deed_type" => $dbrow->form_data->deed_type,
                "appointment_type" => $dbrow->form_data->appointment_type->at_id
            );            
            $json_obj = json_encode($data); //pre($data);
            
            $curl = curl_init($postUrl);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }//End of if
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            if($httpCode == 404) {
                $data1 = array(
                    "log_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    "log_msg" => "NEC : Page not found at ".$postUrl,
                    "appl_ref_no" => $appl_ref_no,
                    "log_status" => 2
                );
                $this->logreports_model->insert($data1);
                die("Page not found");
            } elseif(isset($error_msg)) {
                $data2 = array(
                    "log_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    "log_msg" => "NEC : CURL ERROR : ".$error_msg,
                    "appl_ref_no" => $appl_ref_no,
                    "log_status" => 2
                );
                $this->logreports_model->insert($data2);
                die("Error in server communication ".$error_msg);
            } elseif ($response) {
                $response = json_decode($response);
                $res_status = $response->ref->status??null;
                if(($res_status == "D") || strpos($response, 'Duplicate entry') !== false) {
                    $data_to_update['processing_history'] = $processing_history;
                    $this->appointmentbookings_model->update($obj_id, $data_to_update);
                    $sms = array(
                        "mobile" => (int)$dbrow->form_data->mobile_number,
                        "applicant_name" => $dbrow->form_data->applicant_name,
                        "service_name" => 'Appointment booking',
                        "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                        "app_ref_no" => $appl_ref_no,
                        "rtps_trans_id" => $appl_ref_no
                    );
                    sms_provider("submission", $sms);
                    redirect('spservices/appointmentbooking/registration/acknowledgement/' . $obj_id);
                } else {
                    $start = '{"ref"';
                    $end = '"}}';
                    $start_pos = strpos($response, $start);
                    $end_pos = strpos($response, $end);
                    $filteredRes = substr($response, $start_pos, $end_pos - $start_pos + strlen($end));                
                    $responseObj = json_decode($filteredRes);
                    $refStatus = $responseObj->ref->status??'';
                    if($refStatus == "R") {
                        $data3 = array(
                            "log_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            "log_msg" => "NEC : Failed to submit data to back-end server ".$response,
                            "appl_ref_no" => $appl_ref_no,
                            "log_status" => 2
                        );
                        $this->logreports_model->insert($data3);                    
                        $this->session->set_flashdata('pay_message', 'Failed to submit data to back-end server');
                    } else {
                        $data4 = array(
                            "log_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            "log_msg" => "NEC : Something went wrong. Please try again later ".$response,
                            "appl_ref_no" => $appl_ref_no,
                            "log_status" => 2
                        );
                        $this->logreports_model->insert($data4);                    
                        $this->session->set_flashdata('pay_message', 'Something went wrong. Please try again later');                        
                    }//End of if else
                    $this->my_transactions();                        
                }//End of if else
            }//End of if
        } else {
            $this->session->set_flashdata('error', 'No records found against  : ' . $department_id);
            redirect('spservices/appointmentbooking/');
        }//End of if else
    }//End of post_data()

    public function checkgrn($DEPARTMENT_ID = null, $check = false, $verify = false) { // TODO: need to check which are params to update
        $transaction_data = $this->appointmentbookings_model->get_row(array('form_data.department_id' => $DEPARTMENT_ID));
        if ($DEPARTMENT_ID) {
            $OFFICE_CODE = $transaction_data->form_data->payment_params->OFFICE_CODE;
            $am1 = isset($transaction_data->form_data->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $transaction_data->form_data->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
            $am2 = isset($transaction_data->form_data->payment_params->CHALLAN_AMOUNT) ? $transaction_data->form_data->payment_params->CHALLAN_AMOUNT : 0;
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
                $this->appointmentbookings_model->update_row(
                        array('form_data.department_id' => $DEPARTMENT_ID),
                        array(
                            // "status"=>"WS",
                            "form_data.pfc_payment_response.GRN" => $GRN,
                            "form_data.pfc_payment_response.AMOUNT" => isset($res[6]) ? $res[6] : '',
                            "form_data.pfc_payment_response.PARTYNAME" => isset($res[18]) ? $res[18] : '',
                            "form_data.pfc_payment_response.TAXID" => isset($res[20]) ? $res[20] : '',
                            "form_data.pfc_payment_response.DEPARTMENT_ID" => isset($res[2]) ? $res[2] : '',
                            "form_data.pfc_payment_response.BANKNAME" => isset($res[22]) ? $res[22] : '',
                            "form_data.pfc_payment_response.BANKCODE" => isset($res[8]) ? $res[8] : '',
                            "form_data.pfc_payment_response.ENTRY_DATE" => isset($res[24]) ? $res[24] : '',
                            "form_data.pfc_payment_response.STATUS" => $STATUS,
                            "form_data.pfc_payment_response.PRN" => isset($res[12]) ? $res[12] : '',
                            "form_data.pfc_payment_response.TRANSCOMPLETIONDATETIME" => isset($res[14]) ? $res[14] : '',
                            "form_data.pfc_payment_response.BANKCIN" => isset($res[10]) ? $res[10] : '',
                            'form_data.pfc_payment_status' => $STATUS
                        )
                );

                if ($verify) {
                    return array(
                        'GRN' => $GRN,
                        'STATUS' => $STATUS
                    );
                }
            }
        }
        $this->session->set_flashdata('pay_message', 'Payment grn has been checked and updated');
        $this->my_transactions();
    }//End of checkgrn()

    public function cin_response() { //pre($_POST);
        if (!empty($_POST)) {
            if (!empty($_POST['DEPARTMENT_ID'])) {
                $DEPARTMENT_ID = $_POST['DEPARTMENT_ID'];
                $STATUS = $_POST['STATUS'];
                $BANKCIN = $_POST['BANKCIN'];
                $this->appointmentbookings_model->update_row(array('form_data.department_id' => $DEPARTMENT_ID), array(
                    "form_data.pfc_payment_response.BANKCIN" => $BANKCIN,
                    "form_data.pfc_payment_response.STATUS" => $STATUS,
                    "form_data.pfc_payment_response.TAXID" => $_POST['TAXID'],
                    "form_data.pfc_payment_response.PRN" => $_POST['PRN'],
                    "form_data.pfc_payment_response.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
                    'form_data.pfc_payment_status' => $STATUS
                ));
                if($STATUS === 'Y'){
                    $this->post_data($DEPARTMENT_ID);
                }//End of if
            }//End of if
        }//End of if
        $this->session->set_flashdata('pay_message', 'Payment has been checked and updated');
        $this->my_transactions();
    }//End of cin_response()
}//End of Paymentresponse