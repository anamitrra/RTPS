<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Necresponse extends frontend {

    private $serviceId = "INC";

    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('necertificates_model');
        $this->load->config('spconfig');
        $this->load->helper('smsprovider');
    }//End of __construct()

    private function show_error() {
        $this->load->view('includes/frontend/header');
        $this->load->view('error');
        $this->load->view('includes/frontend/footer');
    }//End of show_error()

    private function my_transactions() {
        if($this->session->role) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }//End of if else
    }//End of my_transactions()

    public function paymentmade() {
        $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
        $response = $_POST;
        $this->necertificates_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_status" => $_POST['STATUS'], "pfc_payment_response" => $response));
        if ($_POST['STATUS'] === 'Y') {
            //check the grn for valid transactions
            if ($this->checkgrn($DEPARTMENT_ID, true) === $_POST['GRN']) {
                $transaction_data = $this->necertificates_model->get_row(array('department_id' => $DEPARTMENT_ID));
                $this->post_data($DEPARTMENT_ID);
            } else {
                //grn does not match Something went wrong
                echo "Something wrong in middle";
                $this->necertificates_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_status" => "P", "pfc_payment_response.STATUS" => 'P'));
                $this->show_error();
            }
        } else {
            $this->show_error();
        }
    }//End of paymentmade()

    public function post_data($department_id = null) {
        $nowTime = date('Y-m-d H:i:s');
        $entryTime = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $dbrow = $this->necertificates_model->get_row(['department_id' => $department_id]);
        if ($dbrow) {
            $obj_id = $dbrow->{'_id'}->{'$id'};
            $landPatta = isset($dbrow->land_patta) ? base64_encode(file_get_contents(FCPATH . $dbrow->land_patta)) : null;
            $khajnaReceipt = isset($dbrow->khajna_receipt) ? base64_encode(file_get_contents(FCPATH . $dbrow->khajna_receipt)) : null;
            //paid total amount
            $total = 0;
            $total += isset($dbrow->payment_params->CHALLAN_AMOUNT) ? intval($dbrow->payment_params->CHALLAN_AMOUNT):0;
            $total += isset($dbrow->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? intval($dbrow->payment_params->TOTAL_NON_TREASURY_AMOUNT):0;

            $entryDate = date("Y-m-d", strtotime($dbrow->pfc_payment_response->ENTRY_DATE));
            $plots = $dbrow->plots;
            $myPlots = array();
            if (count($plots)) {
                foreach ($plots as $plot) {
                    $myPlot = array(
                        "application_ref_no" => $dbrow->rtps_trans_id,
                        "patta" => $plot->patta_no,
                        "daag" => $plot->dag_no,
                        "area" => $plot->land_area,
                        "pattatype" => $plot->patta_type
                    );
                    $myPlots[] = $myPlot;
                }//End of foreach()
            }//End of if
                        
            $processing_history = $dbrow->processing_history??array();
            $processing_history[] = array(
                "processed_by" => "Payment made by ".$dbrow->applicant_name,
                "action_taken" => "Payment completed",
                "remarks" => "Payment made by ".$dbrow->applicant_name,
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            );
            
            $data["spId"] = array(
                "applId" => $dbrow->{'_id'}->{'$id'}
            );
            $data["Ref"] = array(
                "application_ref_no" => $dbrow->rtps_trans_id,
                "sro_code" => $dbrow->sro_code, //"1267555",
                "applicant_name" => $dbrow->applicant_name,
                "applicants_father_name" => $dbrow->father_name,
                "address" => $dbrow->applicant_address,
                "mobile" => $dbrow->mobile,
                "email" => $dbrow->email,
                "from_date" => $dbrow->searched_from,
                "year_to" => $dbrow->searched_to,
                "land_doc_no" => $dbrow->land_doc_ref_no,
                "land_doc_year" => $dbrow->land_doc_reg_year,
                "application_date_time" => $nowTime,
                "service_mode" => 'G',
                "fee_paid" => $total,
                "circle" => $dbrow->circle,
                "village" => $dbrow->village,
                "plots" => $myPlots,
                "land_doc" => array(
                    "encl" => $landPatta
                ),
                "id_proof" => array(
                    "encl" => $khajnaReceipt
                )
            );
            $data["payment_ref_no"] = $dbrow->pfc_payment_response->GRN;
            $data["payment_date"] = $entryDate;

            $json_obj = json_encode($data); //pre($json_obj);
            $url = $this->config->item('url');
            $curl = curl_init($url . "nec/post_nec.php");
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl); //pre($json_obj);
            if(isset($error_msg)) {
                die("CURL ERROR : ".$error_msg);
            } elseif ($response) {
                $response = json_decode($response, true);
                if (isset($response["ref"]["status"]) && ($response["ref"]["status"] === "success")) {
                    //Update to sp_applications
                    $data_to_update = array(
                        'status' => 'PR',
                        'payment_status' => 'PAID',
                        'payment_time' => $entryTime,
                        'submission_date' => $entryTime,
                        'processing_history' => $processing_history
                    );
                    $this->necertificates_model->update($obj_id, $data_to_update);

                    //Sending submission SMS
                    $sms = array(
                        "mobile" => (int)$dbrow->mobile,
                        "applicant_name" => $dbrow->applicant_name,
                        "service_name" => 'Non-Encumbrance Certificate',
                        "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                        "app_ref_no" => $dbrow->rtps_trans_id,
                        "rtps_trans_id" => $dbrow->rtps_trans_id
                    );
                    sms_provider("submission", $sms);
                    redirect('spservices/applications/acknowledgement/' . $obj_id);
                } else {
                    //pre($response);
                    $this->session->set_flashdata('pay_message', 'Data already submitted');
                    $this->my_transactions();
                }//End of if else
            }//End of if
        } else {
            $this->session->set_flashdata('error', 'No records found against  : ' . $department_id);
            redirect('spservices/necertificate/');
        }//End of if else
    }//End of post_data()

    public function checkgrn($DEPARTMENT_ID = null, $check = false, $verify = false) { // TODO: need to check which are params to update
        $transaction_data = $this->necertificates_model->get_row(array('department_id' => $DEPARTMENT_ID));
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
                $this->necertificates_model->update_row(
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
    
    public function cin_response() {
    if (!empty($_POST)) {
      if (!empty($_POST['DEPARTMENT_ID'])) {
        $DEPARTMENT_ID = $_POST['DEPARTMENT_ID'];
        $STATUS = $_POST['STATUS'];
        $BANKCIN = $_POST['BANKCIN'];
        $this->necertificates_model->update_row(array('department_id' => $DEPARTMENT_ID), array(
          "pfc_payment_response.BANKCIN" => $BANKCIN,
          "pfc_payment_response.STATUS" => $STATUS,
          "pfc_payment_response.TAXID" => $_POST['TAXID'],
          "pfc_payment_response.PRN" => $_POST['PRN'],
          "pfc_payment_response.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
          'pfc_payment_status' => $STATUS
        ));
      }
    }
    $this->session->set_flashdata('pay_message', 'Payment has been checked and updated');
    $this->my_transactions();
  }//End of cin_response()
  
}//End of Necresponse