<?php
use PhpOffice\PhpSpreadsheet\Writer\Ods\Thumbnails;
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Payment_response extends Frontend {

    public function __construct() {
        parent::__construct();
        $this->load->model('registered_deed_model');
        $this->config->load('iservices/rtps_services');
    }

    //payment related

    public function response() {
        $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
        $response = $_POST;
        $this->registered_deed_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_status" => $_POST['STATUS'], "pfc_payment_response" => $response));
        if ($_POST['STATUS'] === 'Y') {
            //check the grn for valid transactions
            //if ($this->checkgrn($DEPARTMENT_ID, true) === $_POST['GRN']) {
            if (true) { //Only for app_test
                $transaction_data = $this->registered_deed_model->get_row(array('department_id' => $DEPARTMENT_ID));
                if ($transaction_data->service_id === "CRCPY") {
                    $ref = modules::load('spservices/certified_copy_landhub/registereddeed');
                    $res = $ref->submit_to_backend($transaction_data->_id->{'$id'}, true);
                    if ($res) {
                        redirect(base_url('spservices/applications/acknowledgement/') . $transaction_data->_id->{'$id'});
                    } else {
                        $this->show_error();
                    }
                }
            } else {
                //grn does not match Something went wrong
                echo "Something wrong in middle";
                $this->registered_deed_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_status" => "P", "pfc_payment_response.STATUS" => 'P'));
                $this->show_error();
            }

            //  $this->show_vahan_acknowledgment($DEPARTMENT_ID);
        } else {
            $this->show_error();
        }
    }

    public function checkgrn($DEPARTMENT_ID = null, $check = false, $verify = false) { // TODO: need to check which are params to update
        $transaction_data = $this->registered_deed_model->get_row(array('department_id' => $DEPARTMENT_ID));
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
                $this->registered_deed_model->update_row(
                        array('department_id' => $DEPARTMENT_ID),
                        array(
                            // "status"=>"WS",
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
                            'pfc_payment_status' => $STATUS
                        )
                );
                //  }

                if ($verify) {
                    return array(
                        'GRN' => $GRN,
                        'STATUS' => $STATUS
                    );
                }
            }
        }
        redirect(base_url('iservices/transactions'));
    }

    public function cin_response() {
        if (!empty($_POST)) {
            if (!empty($_POST['DEPARTMENT_ID'])) {
                $DEPARTMENT_ID = $_POST['DEPARTMENT_ID'];
                $STATUS = $_POST['STATUS'];
                $BANKCIN = $_POST['BANKCIN'];
                $this->registered_deed_model->update_row(array('department_id' => $DEPARTMENT_ID), array(
                    "pfc_payment_response.BANKCIN" => $BANKCIN,
                    "pfc_payment_response.STATUS" => $STATUS,
                    "pfc_payment_response.TAXID" => $_POST['TAXID'],
                    "pfc_payment_response.PRN" => $_POST['PRN'],
                    "pfc_payment_response.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
                    'pfc_payment_status' => $STATUS
                ));
                if ($STATUS === 'Y') {
                    $transaction_data = $this->registered_deed_model->get_row(array('department_id' => $DEPARTMENT_ID));
                    $ref = modules::load('spservices/certified_copy_landhub/registereddeed');
                    $ref->submit_to_backend($transaction_data->_id->{'$id'}, true);
                }
            }
        }

        redirect(base_url('iservices/transactions'));
    }

    public function show_error() {
        $this->load->view('includes/frontend/header');
        $this->load->view('error');
        $this->load->view('includes/frontend/footer');
    }

}
