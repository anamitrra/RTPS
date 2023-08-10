<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Paymentresponse extends Frontend {

    public function __construct() {
        parent::__construct();
        $this->load->model('minoritycertificates/minoritycertificates_model');
        $this->load->model('minoritycertificates/office_users_model');
        $this->config->load('spservices/spconfig');
        $this->load->helper('smsprovider');
    }//End of __construct()
        
    public function update_and_send_sms($DEPARTMENT_ID=null) {
        $dbRow = $this->minoritycertificates_model->get_row(array('form_data.department_id' => $DEPARTMENT_ID));
        if ($dbRow) {
            $currentTime = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
            $appl_ref_no_temp = $dbRow->service_data->appl_ref_no;
            $objId = $dbRow->{'_id'}->{'$id'};
            $appl_ref_no = "RTPS" . substr($appl_ref_no_temp, 4);
            $task_details1 = array(
                "appl_no" => $appl_ref_no,
                "task_id" => "1",
                "action_no" => "",
                "task_name" => "Application submission",
                "user_type" => "Applicant",
                "user_name" => "",
                "action_taken" => "Y",
                "payment_date" => $currentTime,
                "payment_mode" => null,
                "pull_user_id" => null,
                "executed_time" => $currentTime,
                "received_time" => $currentTime,
                "user_detail" => array()
            );

            $execution_data = array(
                array(
                    "task_details" => $task_details1,
                    "official_form_details" => array()
                )
            );
            $data = array(
                "service_data.appl_ref_no" => $appl_ref_no,
                "applicant_query" => false,
                "service_data.appl_status" => "PAYMENT_COMPLETED",
                "service_data.submission_date" => $currentTime,
                "form_data.payment_status" => "PAYMENT_COMPLETED",
                "payment_date" => $currentTime,
                "execution_data" => $execution_data
            );
            $this->minoritycertificates_model->update_where(['form_data.department_id' => $DEPARTMENT_ID], $data);

            //Sending submission SMS
            $sms = array(
                "mobile" => (int)$dbRow->form_data->mobile_number,
                "applicant_name" => $dbRow->form_data->applicant_name,
                "service_name" => 'Minority Community Certificate',
                "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbRow->service_data->created_at))),
                "app_ref_no" => $appl_ref_no,
                "rtps_trans_id" => $appl_ref_no
            );
            sms_provider("submission", $sms);
            redirect('spservices/minority-certificate-payment-acknowledgement/'.$objId);
        } else {
            die("No resords found against ".$DEPARTMENT_ID);
        }//End of if else
    }//End of update_and_send_sms()

    public function response() {
        $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
        $response = $_POST;
        $this->minoritycertificates_model->update_where(['form_data.department_id' => $DEPARTMENT_ID], array("form_data.pfc_payment_status" => $_POST['STATUS'], "form_data.pfc_payment_response" => $response));
        if ($_POST['STATUS'] === 'Y') {
            $currentTime = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
            if($this->checkgrn($DEPARTMENT_ID, true) === $_POST['GRN']) {
            //if (true) {
                $this->update_and_send_sms($DEPARTMENT_ID);                
            } else {
                echo "Something wrong in middle";
                $this->minoritycertificates_model->update_where(['form_data.department_id' => $DEPARTMENT_ID], ["service_data.appl_status" => "PAYMENT_FAILED", "form_data.payment_status" => "PAYMENT_FAILED"]);
                $this->show_error();
            }//End of if else
        } else {
            $this->show_error();
        }//End of if else
    }//End of response()

    public function checkgrn($DEPARTMENT_ID = null, $check = false, $verify = false) { // TODO: need to check which are params to update
        $transaction_data = $this->minoritycertificates_model->get_row(array('form_data.department_id' => $DEPARTMENT_ID)); //pre($DEPARTMENT_ID.' : '.$transaction_data);
        if ($DEPARTMENT_ID) {
            $OFFICE_CODE = $transaction_data->form_data->payment_params->OFFICE_CODE;
            $AMOUNT = $transaction_data->form_data->payment_params->TOTAL_NON_TREASURY_AMOUNT ?? 0;
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

                $transactionData = array(
                    "form_data.payment_params.GRN" => $GRN,
                    "form_data.payment_params.AMOUNT" => isset($res[6]) ? $res[6] : '',
                    "form_data.payment_params.PARTYNAME" => isset($res[18]) ? $res[18] : '',
                    "form_data.payment_params.TAXID" => isset($res[20]) ? $res[20] : '',
                    "form_data.payment_params.DEPARTMENT_ID" => isset($res[2]) ? $res[2] : '',
                    "form_data.payment_params.BANKNAME" => isset($res[22]) ? $res[22] : '',
                    "form_data.payment_params.BANKCODE" => isset($res[8]) ? $res[8] : '',
                    "form_data.payment_params.ENTRY_DATE" => isset($res[24]) ? $res[24] : '',
                    "form_data.payment_params.STATUS" => $STATUS,
                    "form_data.payment_params.PRN" => isset($res[12]) ? $res[12] : '',
                    "form_data.payment_params.TRANSCOMPLETIONDATETIME" => isset($res[14]) ? $res[14] : '',
                    "form_data.payment_params.BANKCIN" => isset($res[10]) ? $res[10] : '',
                    "form_data.payment_status" => "PAYMENT_INITIATED"
                );
                $this->minoritycertificates_model->update_where(['form_data.department_id' => $DEPARTMENT_ID], $transactionData);

                if ($verify) {
                    return array(
                        'GRN' => $GRN,
                        'STATUS' => $STATUS
                    );
                }
            }
        }
        $this->my_transactions();
    }

    public function cin_response() {
        if (!empty($_POST)) {
            if (!empty($_POST['DEPARTMENT_ID'])) {
                $DEPARTMENT_ID = $_POST['DEPARTMENT_ID'];
                $STATUS = $_POST['STATUS'];
                $BANKCIN = $_POST['BANKCIN'];    
                
                $this->minoritycertificates_model->update_row(array('form_data.department_id' => $DEPARTMENT_ID), array(
                    "form_data.pfc_payment_response.BANKCIN" => $BANKCIN,
                    "form_data.pfc_payment_response.STATUS" => $STATUS,
                    "form_data.pfc_payment_response.TAXID" => $_POST['TAXID'],
                    "form_data.pfc_payment_response.PRN" => $_POST['PRN'],
                    "form_data.pfc_payment_response.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
                    'form_data.pfc_payment_status' => $STATUS
                ));
                
                if ($STATUS === 'Y') {
                    $this->update_and_send_sms($DEPARTMENT_ID);
                }
            }
        }
        $this->my_transactions();
    }//End of cin_response()

    public function show_error() {
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
    
    public function acknowledgement($objId=null) {
        if ($this->checkObjectId($objId)) {
            $dbFilter = array(
                '_id' => new ObjectId($objId),
                'form_data.payment_status' => 'PAYMENT_COMPLETED'
            );                    
            $dbRow = $this->minoritycertificates_model->get_row($dbFilter);
            if($dbRow) {
                $data=array(
                    "back_to_dasboard" => '<a href="' . base_url('iservices/transactions') . '" class="btn btn-primary mb-2 pull-right"  >Back To Dashboard</a>',
                    "dbrow"=>$dbRow,
                    "pageTitle"=> "Payment Acknowledgement"
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('minoritycertificates/paymentacknowledgement_view',$data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','Records does not exist');
                redirect('spservices/minority-certificate');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/minority-certificate/');
        }//End of if else           
    }//End of acknowledgement()
    
    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        }//End of if else
    }//End of checkObjectId()

}
