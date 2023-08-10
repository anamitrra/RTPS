<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Payment_response extends Frontend {

    public function __construct() {
        parent::__construct();
        $this->load->model('intermediator_model');
        $this->load->model('portals_model');
        $this->config->load('rtps_services');
        $this->load->library('AES');
        $this->load->library('AESrathi');
        $this->encryption_key = $this->config->item("agencyKey");
    }

    public function payment_response() {
        $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
        $response = $_POST;
        $this->intermediator_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_status" => $_POST['STATUS'], "pfc_payment_response" => $response));
        if ($_POST['STATUS'] === 'Y') {
            //check the grn for valid transactions
            if ($this->checkgrn($DEPARTMENT_ID, true) === $_POST['GRN']) {
                redirect(base_url('iservices/wptbc/payment_response/acknowledgement/'.$DEPARTMENT_ID));
            } else {
                //grn does not match Something went wrong
                echo "Something wrong in middle";
                $this->intermediator_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_status" => "P", "pfc_payment_response.STATUS" => 'P'));
                $this->show_error();
            }

            //  $this->show_vahan_acknowledgment($DEPARTMENT_ID);
        } else {
            $this->show_error();
        }
    }

    public function acknowledgement($app_ref_no=null) {
        if (empty($app_ref_no)) {
            redirect(base_url("iservices/transactions"));
            exit();
        } else {
            $application_details = $this->intermediator_model->get_application_details(array("department_id" => $app_ref_no));
            if ($application_details->service_id) {
                $updateData = array(
                    "task_no" => "1",
                    "assigned_user" => "DPS",
                    "status" => "submitted"
                );
                $this->intermediator_model->update_where(['rtps_trans_id' => $application_details->rtps_trans_id], $updateData);

                $departmental_data = $this->portals_model->get_departmental_data($application_details->service_id);
                $data = array();
                $data['timeline_days'] = "30 days";//$departmental_data->timeline_days;
                //$data['department_name'] = $application_details->department_name;
                //$data['service_name'] = $application_details->service_name;
                $data['back_to_dasboard'] = '<a href="' . base_url('iservices/transactions') . '" class="btn btn-primary mb-2"  >Back To DASHBOARD</a>';
                $data['response'] = $application_details;
                $this->load->view('includes/frontend/header');
                $this->load->view('wptbc/ack', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                redirect('iservices/transactions');
            }//ENd of if else
        }//End of if else          
    }

    public function grn_response() {

        $DEPARTMENT_ID = $_POST['DEPARTMENT_ID'];
        $STATUS = $_POST['STATUS'];
        $GRN = $_POST['GRN'];
        if ($STATUS === "Y") {
            $this->intermediator_model->update_payment_status($DEPARTMENT_ID, array("pfc_payment_response.GRN" => $GRN,
                "pfc_payment_response.STATUS" => $STATUS,
                "pfc_payment_response.TAXID" => $_POST['TAXID'],
                "pfc_payment_response.PRN" => $_POST['PRN'],
                "pfc_payment_response.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
                'pfc_payment_status' => $STATUS));
        }
        redirect(base_url('iservices/transactions'));
    }

    public function checkgrn($DEPARTMENT_ID = null, $check = false) { // TODO: need to check which are params to update
        $transaction_data = $this->intermediator_model->get_row(array('department_id' => $DEPARTMENT_ID));
        if ($DEPARTMENT_ID) {
            $OFFICE_CODE = $transaction_data->payment_params->OFFICE_CODE;
            $AMOUNT = $transaction_data->pfc_payment_response->AMOUNT;
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
                $this->intermediator_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_response.GRN" => $GRN,
                    "pfc_payment_response.STATUS" => $STATUS,
                    "pfc_payment_response.TAXID" => isset($res[20]) ? $res[20] : '',
                    //"pfc_payment_response.PRN"=>$res[12],
                    "pfc_payment_response.TRANSCOMPLETIONDATETIME" => isset($res[14]) ? $res[14] : '',
                    'pfc_payment_status' => $STATUS));

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
                $this->intermediator_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_response.BANKCIN" => $BANKCIN,
                    "pfc_payment_response.STATUS" => $STATUS,
                    "pfc_payment_response.TAXID" => $_POST['TAXID'],
                    "pfc_payment_response.PRN" => $_POST['PRN'],
                    "pfc_payment_response.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
                    'pfc_payment_status' => $STATUS));
            }
        }

        redirect(base_url('iservices/transactions'));
    }

    public function show_acknowledgment($data) {
        $this->load->view('includes/frontend/header');
        $this->load->view('noc_ack1', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function show_error() {
        $this->load->view('includes/header');
        $this->load->view('error');
        $this->load->view('includes/footer');
    }

    public function GRN() {
        $this->load->view('includes/frontend/header');
        $this->load->view('grn');
        $this->load->view('includes/frontend/footer');
    }

    public function decrypt($data) {
        $url = $this->config->item("decrypt_url");
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
