<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Payment_query_response extends Frontend
{
    private $serviceId = "NOCTL";
    private $base_serviceId = "1878";
    private $departmentName = "Karbi Anglong (AC)";
    private $departmentId = "2100";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('kaac/kaac_registration_model');
        $this->config->load('spservices/spconfig');
        $this->load->helper('smsprovider');
        $this->load->model('iservices/admin/users_model');
        // $this->load->config('iservices/rtps_services');
        $this->load->config('spconfig');
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else
    } //End of __construct()

    public function response()
    {

        $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
        $response = $_POST;
        $this->kaac_registration_model->update_row(array('form_data.query_department_id' => $DEPARTMENT_ID), array("form_data.query_payment_status" => $_POST['STATUS'], "form_data.query_payment_response" => $response));
        if ($_POST['STATUS'] === 'Y') {
 
            //check the grn for valid transactions
            if ($this->checkgrn($DEPARTMENT_ID, true) === $_POST['GRN']) {
                $transaction_data = $this->kaac_registration_model->get_row(array('form_data.query_department_id' => $DEPARTMENT_ID));
                if($transaction_data->form_data->service_id == $this->serviceId){ 
                    
                    $ref=modules::load('spservices/kaac_noc_trade_license/registration');
                    // pre($transaction_data->_id->{'$id'});
                    $ref->querypaymentsubmit($transaction_data->_id->{'$id'});
                    //redirect(base_url('spservices/applications/acknowledgement/') . $transaction_data->_id->{'$id'});
                }
            } else {
                //grn does not match Something went wrong
                echo "Something wrong in middle";
                $this->kaac_registration_model->update_row(array('form_data.query_department_id' => $DEPARTMENT_ID), array("form_data.query_payment_status" => "P", "form_data.query_payment_response.STATUS" => 'P'));
                $this->show_error();
            }
            //  $this->show_vahan_acknowledgment($DEPARTMENT_ID);
        } else {
            $this->show_error();
        }
    } //End of response()

    public function checkgrn($DEPARTMENT_ID = null, $check = false, $verify = false)
    { // TODO: need to check which are params to update


        $transaction_data = $this->kaac_registration_model->get_row(array('form_data.query_department_id' => $DEPARTMENT_ID));
        if ($DEPARTMENT_ID) {
            $OFFICE_CODE = $transaction_data->form_data->query_payment_params->OFFICE_CODE;
            $am1 = isset($transaction_data->form_data->query_payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $transaction_data->form_data->query_payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
            $am2 = isset($transaction_data->form_data->query_payment_params->CHALLAN_AMOUNT) ? $transaction_data->form_data->query_payment_params->CHALLAN_AMOUNT : 0;
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
            // pre($res);
            if ($res) {
                $STATUS = isset($res[16]) ? $res[16] : '';
                $GRN = isset($res[4]) ? $res[4] : '';
                //  var_dump($STATUS);var_dump($GRN);die;
                //if($STATUS === "Y"){
                $this->kaac_registration_model->update_row(
                    array('form_data.query_department_id' => $DEPARTMENT_ID),
                    array(
                        // "status"=>"WS",
                        "form_data.query_payment_response.GRN" => $GRN,
                        "form_data.query_payment_response.AMOUNT" => isset($res[6]) ? $res[6] : '',
                        "form_data.query_payment_response.PARTYNAME" => isset($res[18]) ? $res[18] : '',
                        "form_data.query_payment_response.TAXID" => isset($res[20]) ? $res[20] : '',
                        "form_data.query_payment_response.DEPARTMENT_ID" => isset($res[2]) ? $res[2] : '',
                        "form_data.query_payment_response.BANKNAME" => isset($res[22]) ? $res[22] : '',
                        "form_data.query_payment_response.BANKCODE" => isset($res[8]) ? $res[8] : '',
                        "form_data.query_payment_response.ENTRY_DATE" => isset($res[24]) ? $res[24] : '',
                        "form_data.query_payment_response.STATUS" => $STATUS,
                        "form_data.query_payment_response.PRN" => isset($res[12]) ? $res[12] : '',
                        "form_data.query_payment_response.TRANSCOMPLETIONDATETIME" => isset($res[14]) ? $res[14] : '',
                        "form_data.query_payment_response.BANKCIN" => isset($res[10]) ? $res[10] : '',
                        'form_data.query_payment_status' => $STATUS
                    )
                );
                //  }
// pre($verify);
                if ($verify) {
                    return  array(
                        'GRN' => $GRN,
                        'STATUS' => $STATUS
                    );
                }
            }
        }
        $this->my_transactions();
        // redirect(base_url('spservices/applications'));
    }

    public function cin_response()
    {
        // pre($_POST);
        if (!empty($_POST)) {
            if (!empty($_POST['DEPARTMENT_ID'])) {
                $DEPARTMENT_ID = $_POST['DEPARTMENT_ID'];
                $STATUS = $_POST['STATUS'];
                $BANKCIN = $_POST['BANKCIN'];
                $this->kaac_registration_model->update_row(array('form_data.query_department_id' => $DEPARTMENT_ID), array(
                "form_data.query_payment_response.BANKCIN" => $BANKCIN,
                "form_data.query_payment_response.STATUS" => $STATUS,
                "form_data.query_payment_response.TAXID" => $_POST['TAXID'],
                "form_data.query_payment_response.PRN" => $_POST['PRN'],
                "form_data.query_payment_response.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
                'form_data.query_payment_status' => $STATUS
                ));
                if($STATUS === 'Y'){
                    $transaction_data = $this->kaac_registration_model->get_row(array('form_data.query_department_id' => $DEPARTMENT_ID));
                    $ref=modules::load('spservices/kaac_noc_trade_license/registration');
                    $ref->querypaymentsubmit($transaction_data->_id->{'$id'});
                }
            }
        }

        $this->my_transactions();
        // redirect(base_url('iservices/transactions'));
    }

    public function show_error()
    {
        $this->load->view('includes/frontend/header');
        $this->load->view('error');
        $this->load->view('includes/frontend/footer');
    }

    private function my_transactions(){
        $user=$this->session->userdata();
        if(isset($user['role']) && !empty($user['role'])){
            redirect(base_url('iservices/admin/my-transactions'));
        }else{
            redirect(base_url('iservices/transactions'));
        }
    }
}