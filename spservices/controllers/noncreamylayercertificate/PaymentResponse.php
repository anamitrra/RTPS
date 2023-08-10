<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class PaymentResponse extends Frontend
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('noncreamylayercertificate/ncl_model');
        $this->config->load('spservices/spconfig');
        $this->load->helper('smsprovider');
    } //End of __construct()

    public function response()
    {
        $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
        $response = $_POST;
        $this->ncl_model->update_where(['form_data.department_id' => $DEPARTMENT_ID], array("form_data.pfc_payment_status" => $_POST['STATUS'], "form_data.pfc_payment_response" => $response));
        //pre($_POST);
        //pre($this->checkgrn($DEPARTMENT_ID, true));
        if ($_POST['STATUS'] === 'Y') {
            //pre($this->checkgrn($DEPARTMENT_ID, true));
            //pre("OK");
            if ($this->checkgrn($DEPARTMENT_ID, true) === $_POST['GRN']) {
                //pre("OK");
                //if (true) {
                // $dbrow = $this->ncl_model->get_row(array('form_data.department_id' => $DEPARTMENT_ID));
                // $rtps_trans_id_temp = $dbrow->service_data->rtps_trans_id;
                // $rtps_trans_id = "RTPS" . substr($rtps_trans_id_temp, 4);
                // $obj_id = $dbrow->{'_id'}->{'$id'};
                // $task_details1 = array(
                //     "appl_no" => $rtps_trans_id,
                //     "task_id" => "1",
                //     "action_no" => "",
                //     "task_name" => "Application submission",
                //     "user_type" => "Applicant",
                //     "user_name" => "",
                //     "action_taken" => "Y",
                //     "payment_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                //     "payment_mode" => null,
                //     "pull_user_id" => null,
                //     "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                //     "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                //     "user_detail" => array()
                // );

                // $execution_data = array(
                //     array(
                //         "task_details" => $task_details1,
                //         "official_form_details" => array()
                //     )
                // );
                //IF payment success but data has not been post yet
                /*$data = array(
                    "service_data.appl_status" => "PS",
                );
                $this->ncl_model->update_where(['form_data.department_id' => $DEPARTMENT_ID], $data);
                */
                // //Sending submission SMS
                // $sms = array(
                //     "mobile" => (int)$dbrow->form_data->mobile_number,
                //     "applicant_name" => $dbrow->form_data->applicant_name,
                //     "service_name" => 'Non Creamy Layer Certificate',
                //     "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->form_data->created_at))),
                //     "app_ref_no" => $rtps_trans_id,
                //     "rtps_trans_id" => $rtps_trans_id
                // );
                // sms_provider("submission", $sms);
                $transaction_data = $this->ncl_model->get_row(array('form_data.department_id' => $DEPARTMENT_ID));
                if ($transaction_data->service_data->service_id === "NCL") {
                    $ref = modules::load('spservices/noncreamylayercertificate/registration');
                    $ref->post_data($transaction_data->_id->{'$id'}, true);
                    // pre($result->final_output);
                    // if ($result->final_output->status === true) {
                    redirect(base_url('spservices/noncreamylayercertificate/applications/acknowledgement/') . $transaction_data->_id->{'$id'});
                    // } else {
                    //     $this->session->set_flashdata('error', 'Something went wrong please try again.');
                    //     redirect('spservices/noncreamylayercertificate/registration/index/' . $transaction_data->_id->{'$id'});
                    // }
                }

                // redirect('spservices/applications/acknowledgement/' . $obj_id);
                // redirect('spservices/noncreamylayercertificate/registration/index/' . $transaction_data->_id->{'$id'});
            } else {
                $this->ncl_model->update_where(['form_data.department_id' => $DEPARTMENT_ID], ["service_data.appl_status" => "PAYMENT_COMPLETED", "form_data.pfc_payment_status" => "PAYMENT_FAILED"]);
                // echo "Something wrong in middle";
                // $this->show_error();
                $this->session->set_flashdata('error', 'Something went wrong please try again.');
                redirect('iservices/transactions');
            } //End of if else
        } else {
            $this->show_error();
        } //End of if else
    } //End of response()

    public function checkgrn($DEPARTMENT_ID = null, $check = false, $verify = false)
    { // TODO: need to check which are params to update
        $transaction_data = $this->ncl_model->get_row(array('form_data.department_id' => $DEPARTMENT_ID));
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
            $res = explode("$", $result);
            //var_dump($res);
            if ($check) {
                return isset($res[4]) ? $res[4] : '';
            }
            if ($res) {
                $STATUS = isset($res[16]) ? $res[16] : '';
                $GRN = isset($res[4]) ? $res[4] : '';
                //  var_dump($STATUS);var_dump($GRN);die;
                //if($STATUS === "Y"){
                $this->ncl_model->update_row(
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
                //  }

                if ($verify) {
                    return  array(
                        'GRN' => $GRN,
                        'STATUS' => $STATUS
                    );
                }
            }
        }
        redirect(base_url('spservices/applications'));
    }

    public function cin_response()
    {
        if (!empty($_POST)) {
            if (!empty($_POST['DEPARTMENT_ID'])) {
                $DEPARTMENT_ID = $_POST['DEPARTMENT_ID'];
                $STATUS = $_POST['STATUS'];
                $BANKCIN = $_POST['BANKCIN'];
                $this->ncl_model->update_row(array('form_data.department_id' => $DEPARTMENT_ID), array(
                    "form_data.payment_params.BANKCIN" => $BANKCIN,
                    "form_data.payment_params.STATUS" => $STATUS,
                    "form_data.payment_params.TAXID" => $_POST['TAXID'],
                    "form_data.payment_params.PRN" => $_POST['PRN'],
                    "form_data.payment_params.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
                    'form_data.pfc_payment_status' => $STATUS
                ));
                if ($STATUS === 'Y') {
                    $transaction_data = $this->ncl_model->get_row(array('department_id' => $DEPARTMENT_ID));
                    $ref = modules::load('spservices/Registereddeed');
                    $ref->submit_to_backend($transaction_data->_id->{'$id'}, true);
                }
            }
        }

        redirect(base_url('spservices/applications'));
    }

    public function show_error()
    {
        $this->load->view('includes/frontend/header');
        $this->load->view('error');
        $this->load->view('includes/frontend/footer');
    }

    public function acknowledgement($objId = null)
    {
        $dbRow = $this->ncl_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "back_to_dasboard" => '<a href="' . base_url('iservices/transactions') . '" class="btn btn-primary mb-2 pull-right"  >Back To Dashboard</a>',
                "dbrow" => $dbRow,
                "pageTitle" => "Payment Acknowledgement"
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('minoritycertificates/paymentacknowledgement_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'Records does not exist');
            redirect('spservices/minority-certificate');
        } //End of if else

    }
}