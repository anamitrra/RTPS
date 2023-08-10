<?php
use PhpOffice\PhpSpreadsheet\Writer\Ods\Thumbnails;
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Query_payment_response extends Frontend {

    private $ePanjayeenURL = '';
    
    public function __construct() {
        parent::__construct();
        $this->load->model('registered_deed_model');
        $this->config->load('iservices/rtps_services');
        $this->load->config('spconfig');
        $this->ePanjayeenURL = 'https://landhub.assam.gov.in/webapi_test/webapi_land/';//$this->config->item('url');
    }

    //payment related

    public function response() {
        $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
        $response = $_POST;
        $this->registered_deed_model->update_row(array('query_department_id' => $DEPARTMENT_ID), array("query_payment_status" => $_POST['STATUS'], "query_payment_response" => $response));
        if ($_POST['STATUS'] === 'Y') {
            //check the grn for valid transactions
            //if ($this->checkgrn($DEPARTMENT_ID, true) === $_POST['GRN']) {
            if (true) { //Only for app_test   
                $this->update_payment_status($DEPARTMENT_ID);
            } else {
                //grn does not match Something went wrong
                echo "Something wrong in middle";
                $this->registered_deed_model->update_row(array('query_department_id' => $DEPARTMENT_ID), array("query_payment_status" => "P", "query_payment_status.STATUS" => 'P'));
                $this->show_error();
            }

            //  $this->show_vahan_acknowledgment($DEPARTMENT_ID);
        } else {
            $this->show_error();
        }
    }

    public function update_payment_status($DEPARTMENT_ID) {
        // $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');

        if ($DEPARTMENT_ID) {
            $transaction_data = $this->registered_deed_model->get_row(array('query_department_id' => $DEPARTMENT_ID));
            if ($transaction_data && $transaction_data->query_payment_status === 'Y') {
                $postdata = array(
                    "Ref" => array(
                        "fee_paid" => $transaction_data->query_payment_response->GRN,
                        "test_param" => "EGRAS Assam",
                        "application_status" => $transaction_data->status
                    ),
                    "spId" => array(
                        "applId" => $transaction_data->applId
                    )
                );
                $curl = curl_init($this->ePanjayeenURL . "cercpy/fee_paid_status.php");
                // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $res = curl_exec($curl);

                curl_close($curl);

                if ($res) {
                    $res = json_decode($res);

                    if ($res->ref->status === "success") {
                        $this->registered_deed_model->update_row(
                                array('rtps_trans_id' => $transaction_data->rtps_trans_id),
                                array(
                                    "status" => 'PR',
                                    "qps_updated_on_backend" => true
                                )
                        );
                    }
                }

                $this->show_pay_ack($transaction_data);
                return;
            }
        }
        redirect(base_url('iservices/transactions'));
    }

    public function checkgrn($DEPARTMENT_ID = null, $check = false, $return = false) { // TODO: need to check which are params to update
        $transaction_data = $this->registered_deed_model->get_row(array('query_department_id' => $DEPARTMENT_ID));
        if ($DEPARTMENT_ID) {
            $OFFICE_CODE = $transaction_data->query_payment_params->OFFICE_CODE;
            $am1 = isset($transaction_data->query_payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $transaction_data->query_payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
            $am2 = isset($transaction_data->query_payment_params->CHALLAN_AMOUNT) ? $transaction_data->query_payment_params->CHALLAN_AMOUNT : 0;
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
                        array('query_department_id' => $DEPARTMENT_ID),
                        array(
                            "query_payment_response.GRN" => $GRN,
                            "query_payment_response.AMOUNT" => isset($res[6]) ? $res[6] : '',
                            "query_payment_response.PARTYNAME" => isset($res[18]) ? $res[18] : '',
                            "query_payment_response.TAXID" => isset($res[20]) ? $res[20] : '',
                            "query_payment_response.DEPARTMENT_ID" => isset($res[2]) ? $res[2] : '',
                            "query_payment_response.BANKNAME" => isset($res[22]) ? $res[22] : '',
                            "query_payment_response.BANKCODE" => isset($res[8]) ? $res[8] : '',
                            "query_payment_response.ENTRY_DATE" => isset($res[24]) ? $res[24] : '',
                            "query_payment_response.STATUS" => $STATUS,
                            "query_payment_response.PRN" => isset($res[12]) ? $res[12] : '',
                            "query_payment_response.TRANSCOMPLETIONDATETIME" => isset($res[14]) ? $res[14] : '',
                            "query_payment_response.BANKCIN" => isset($res[10]) ? $res[10] : '',
                            'query_payment_status' => $STATUS
                        )
                );

                if ($return) {
                    return array(
                        'GRN' => $GRN,
                        'STATUS' => $STATUS
                    );
                }
                //  }
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
                $this->registered_deed_model->update_row(array('query_department_id' => $DEPARTMENT_ID), array(
                    "query_payment_response.BANKCIN" => $BANKCIN,
                    "query_payment_response.STATUS" => $STATUS,
                    "query_payment_response.TAXID" => $_POST['TAXID'],
                    "query_payment_response.PRN" => $_POST['PRN'],
                    "query_payment_response.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
                    'query_payment_status' => $STATUS
                ));
            }
        }

        redirect(base_url('iservices/transactions'));
    }

    public function show_error() {
        $this->load->view('includes/frontend/header');
        $this->load->view('error');
        $this->load->view('includes/frontend/footer');
    }

    public function show_pay_ack($data) {
        $data = array(
            'GRN' => $data->query_payment_response->GRN,
            'DEPARTMENT_ID' => $data->query_department_id
        );

        $this->load->view('includes/frontend/header');
        $this->load->view('pay_ack', $data);
        $this->load->view('includes/frontend/footer');
    }

}
