<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class PaymentResponse extends Frontend
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('employment_nonaadhaar/employment_model');
        $this->config->load('spservices/spconfig');
        $this->load->helper('smsprovider');
    } //End of __construct()

    public function response()
    {
        $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
        $response = $_POST;
        $this->employment_model->update_where(['form_data.department_id' => $DEPARTMENT_ID], array("form_data.payment_status" => $_POST['STATUS'],"form_data.pfc_payment_status" => $_POST['STATUS'], "form_data.pfc_payment_response" => $response));
        if ($_POST['STATUS'] === 'Y') {
            //for app test make if(true)
            if ($this->checkgrn($DEPARTMENT_ID, true) === $_POST['GRN']) {
                $transaction_data = $this->employment_model->get_row(array('form_data.department_id' => $DEPARTMENT_ID));
                if ($transaction_data->form_data->service_id === "EMP_REG_NA") {
                    $ref = modules::load('spservices/employmentnonaadhaar/Registration');
                    $ref->finalsubmition($transaction_data->_id->{'$id'}, true);
                    //redirect(base_url('spservices/prc/Acknowledgement/acknowledgement/') . $transaction_data->_id->{'$id'});
                }
                if ($transaction_data->service_data->service_id === "EMP_A_RENEW") {
                    $ref = modules::load('spservices/employmentnonaadhaar/Renewal');
                    $ref->finalsubmition($transaction_data->_id->{'$id'}, true);
                    //redirect(base_url('spservices/prc/Acknowledgement/acknowledgement/') . $transaction_data->_id->{'$id'});
                }
                if ($transaction_data->service_data->service_id === "EMP_A_RE_REG") {
                    $ref = modules::load('spservices/employmentnonaadhaar/Reregistration');
                    $ref->finalsubmition($transaction_data->_id->{'$id'}, true);
                    //redirect(base_url('spservices/prc/Acknowledgement/acknowledgement/') . $transaction_data->_id->{'$id'});
                }
                if ($transaction_data->service_data->service_id === "EMP_REREG_NA") {
                    $ref = modules::load('spservices/employmentnonaadhaar/Reregistration');
                    $ref->finalsubmition($transaction_data->_id->{'$id'}, true);
                    //redirect(base_url('spservices/prc/Acknowledgement/acknowledgement/') . $transaction_data->_id->{'$id'});
                }
            } else {
                echo "Something wrong in middle";
                $this->employment_model->update_where(['department_id' => $DEPARTMENT_ID], ["status" => "PAYMENT_COMPLETED", "payment_status" => "PAYMENT_FAILED"]);
                $this->show_error();
            } //End of if else
        } else {
            $this->show_error();
        } //End of if else
    } //End of response()

    public function checkgrn($DEPARTMENT_ID = null, $check = false, $verify = false)
    { // TODO: need to check which are params to update
        $transaction_data = $this->employment_model->get_row(array('form_data.department_id' => $DEPARTMENT_ID));
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
            if ($check) {
                return isset($res[4]) ? $res[4] : '';
            }
            if ($res) {
                $STATUS = isset($res[16]) ? $res[16] : '';
                $GRN = isset($res[4]) ? $res[4] : '';
                //  var_dump($STATUS);var_dump($GRN);die;
                //if($STATUS === "Y"){
                $this->employment_model->update_row(
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
        pre($_POST);
        if (!empty($_POST)) {
            if (!empty($_POST['DEPARTMENT_ID'])) {
                $DEPARTMENT_ID = $_POST['DEPARTMENT_ID'];
                $STATUS = $_POST['STATUS'];
                $BANKCIN = $_POST['BANKCIN'];
                $this->employment_model->update_row(array('form_data.department_id' => $DEPARTMENT_ID), array(
                    "payment_params.BANKCIN" => $BANKCIN,
                    "payment_params.STATUS" => $STATUS,
                    "payment_params.TAXID" => $_POST['TAXID'],
                    "payment_params.PRN" => $_POST['PRN'],
                    "payment_params.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
                    'payment_status' => $STATUS
                ));
                if ($STATUS === 'Y') {
                    $transaction_data = $this->employment_model->get_row(array('department_id' => $DEPARTMENT_ID));
                    $ref = modules::load('spservices/employmentnonaadhaar');
                    $ref->finalsubmition($transaction_data->_id->{'$id'}, true);
                    // $ref->submit_to_backend($transaction_data->_id->{'$id'}, true);
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
}
