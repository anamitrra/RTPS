<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Payment extends Rtps {
    private $serviceName = "Application for Minority Community Certificate";
    private $serviceId = "MCC";
    private $degs_fee = 10;
    private $rtps_convenience_fee = 10; //For all payment
    private $rtps_convenience_acc = 'ARI64576';

    public function __construct() {
        parent::__construct();
        $this->load->model('minoritycertificates/minoritycertificates_model');
        $this->load->model('minoritycertificates/districts_model');
        $this->load->model('iservices/admin/users_model');
        $this->load->config('spconfig');
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
    }//End of __construct()

    private function my_transactions() {
        if($this->session->role) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }//End of if else        
    }//End of my_transactions()

    private function check_payment_status($DEPARTMENT_ID = null) {
        if ($DEPARTMENT_ID) {
            $this->load->model('iservices/intermediator_model');
            $min = $this->intermediator_model->checkPaymentIntitateTime($DEPARTMENT_ID);
            if ($min !== 'N' && $min < 6) {
                $this->session->set_flashdata('pay_message', 'Please verify payment status after 5 minutes');
                $this->my_transactions();
                return;
            }
            
            $ref=modules::load('spservices/minoritycertificates/paymentresponse');
            $grndata = $ref->checkgrn($DEPARTMENT_ID,false,true);
            
            if (!empty($grndata)) {
                if ($grndata['STATUS'] === 'Y') {
                    $this->session->set_flashdata('pay_message', 'Payment mode is already in Y');
                    $this->my_transactions();
                    return;
                }
                $ar = array('N', 'A');
                if (!empty($grndata['GRN']) && !in_array($grndata['STATUS'], $ar)) {
                    $this->session->set_flashdata('pay_message', 'Unable to verify payment status');
                    $this->my_transactions();
                    return;
                }
            }
        }
    }//End of check_payment_status()
    
    public function updateAndSendSms($objId=null) {
        if($this->checkObjectId($objId)) {
            $dbRow = $this->minoritycertificates_model->get_by_doc_id($objId);
            if (count((array) $dbRow)) {
                $paymentresponse = modules::load('spservices/minoritycertificates/paymentresponse');
                $paymentresponse->update_and_send_sms($dbRow->form_data->department_id);
            } else {
                die("No resords found against ".$objId);
            }//End of if else
        } else {
            die("invalid Object ID");
        }//End of if else
    }//End of updateAndSendSms()
    
    public function initiate($obj_id = null) {
        if($this->slug === "CSC"){                
            $apply_by = $this->session->userId;
        } else {
            $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        }//End of if else

        $dbFilter = array(
            '_id' => new ObjectId($obj_id),
            'service_data.applied_by' => $apply_by
        );
        $dbRow = $this->minoritycertificates_model->get_row($dbFilter);
        if ($dbRow) {
            $pfc_payment_status = $dbRow->form_data->pfc_payment_status??'';
            if (property_exists($dbRow->form_data, 'pfc_payment_status') && $pfc_payment_status == 'Y') {
                $this->updateAndSendSms($obj_id);
                $this->session->set_flashdata('pay_message', 'Payment already made and hence the status has updated');
                $this->my_transactions();
            }

            if (!empty($dbRow->form_data->payment_params) && !empty($dbRow->form_data->department_id) && $pfc_payment_status != 'N') {
                $res = $this->check_payment_status($dbRow->form_data->department_id);
                if ($res) {
                    $this->updateAndSendSms($obj_id);
                }
            }
            
            $payData = array(
                //'aadhaar_verify_status' => 1,
                'service_data.appl_status' => 'PAYMENT_INITIATED',
                'form_data.payment_status' => 'PAYMENT_INITIATED'
            );
            $this->minoritycertificates_model->update_where(['_id' => new ObjectId($obj_id)], $payData); //Update status            
            $data = array("pageTitle" => "Make Payment");
            $data["dbrow"] = $dbRow;
            if ($this->slug !== "user") {
                $data['service_charge'] = $this->config->item('service_charge');
                $data['objid'] = $obj_id;
                $data['no_printing_page'] = isset($data["dbrow"]->no_printing_page) ? $data["dbrow"]->no_printing_page : '';
                $data['no_scanning_page'] = isset($data["dbrow"]->no_scanning_page) ? $data["dbrow"]->no_scanning_page : '';
                $this->load->view('includes/frontend/header');
                $this->load->view('minoritycertificates/payment_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->payment_make($obj_id);
            }//End of if else
        } else {
            $this->session->set_flashdata('pay_message', 'No records found against '.$obj_id);
            $this->my_transactions();
        }//End of if else
    }//End of initiate()

    public function submit() {
        $this->form_validation->set_rules('objid', 'objid', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('no_printing_page', 'No printing page', 'trim|required|xss_clean|strip_tags|numeric');

        $this->form_validation->set_rules('no_scanning_page', 'No scanning page', 'trim|required|xss_clean|strip_tags|numeric');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = $this->input->post('objid');
            $this->initiate($obj_id);
        } else {
            $obj_id = $this->input->post('objid');
            $this->payment_make($obj_id);
        }//End of if else
    }//End of submit()

    private function payment_make($obj_id = null) {
        $dbRow = $this->minoritycertificates_model->get_by_doc_id($obj_id);
        if (count((array) $dbRow)) {
            $ac2_amount = 0;
            $districtRow = $this->districts_model->get_row(array("district_id" => (int) $dbRow->form_data->pa_district_id));
            $pfc_payment_status = $dbRow->form_data->pfc_payment_status??'';
            if (property_exists($dbRow->form_data, "department_id") && property_exists($dbRow->form_data, "payment_params") && ($pfc_payment_status !=="N")) {
                $this->check_payment_status($dbRow->form_data->department_id);
            }
            
            $uniqid = uniqid();
            $DEPARTMENT_ID = $uniqid . time();
            $data = array();
            $data['appl_ref_no'] = $dbRow->service_data->appl_ref_no;

            if ($this->slug === "user") {
                $curretnYear = date('Y');
                $data['department_data'] = array(
                    "DEPT_CODE" => "ARI",
                    "OFFICE_CODE" => "ARI000",
                    "REC_FIN_YEAR" => $curretnYear.'-'.($curretnYear+1),//"2022-2023", //dynamic
                    "HOA1" => "",
                    "FROM_DATE" => '01/04/'.$curretnYear,//"01/04/2022",
                    "TO_DATE" => "31/03/2099",
                    "PERIOD" => "O", // O for one-time payment
                    "CHALLAN_AMOUNT" => "0",
                    "DEPARTMENT_ID" => $DEPARTMENT_ID,
                    "MOBILE_NO" => $dbRow->form_data->mobile_number,
                    "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/minority-certificate-payment-response'),
                    "PARTY_NAME" => $dbRow->form_data->applicant_name ?? 'RTPS TEAM',
                    "PIN_NO" => $dbRow->form_data->pa_pin_code ?? '781005',
                    "ADDRESS1" => $dbRow->form_data->pa_village ?? 'NIC',
                    "ADDRESS2" => $dbRow->form_data->pa_post_office ?? 'TEAM',
                    "ADDRESS3" => $dbRow->form_data->pa_district_name ?? 'Kamrup'
                );
            } else {
                $data['service_charge'] = $this->config->item('service_charge');
                $data['scanning_charge_per_page'] = $this->config->item('scanning_charge');
                $data['printing_charge_per_page'] = $this->config->item('printing_charge');

                $data['no_printing_page'] = $this->input->post('no_printing_page');
                $data['no_scanning_page'] = $this->input->post('no_scanning_page');
                $data['pfc_payment'] = true;
                $ac2_amount = $ac2_amount+$data['service_charge'];
                
                if ($this->slug === "PFC") {
                    if (!empty($data['no_printing_page']) && ( intval($data['no_printing_page']) < 0 || !is_numeric($data['no_printing_page']))) {
                        return $this->output
                                        ->set_content_type('application/json')
                                        ->set_status_header(200)
                                        ->set_output(json_encode(array("status" => false, "message" => "Number of page can not be a negative value")));
                    }
                    if (!empty($data['no_scanning_page']) && intval($data['no_scanning_page']) < 0 || !is_numeric($data['no_printing_page'])) {
                        return $this->output
                                        ->set_content_type('application/json')
                                        ->set_status_header(200)
                                        ->set_output(json_encode(array("status" => false, "message" => "Number of page can not be a negative value")));
                    }
                    if ($data['no_printing_page'] > 0) {
                        $ac2_amount = $ac2_amount + intval($data['no_printing_page']) * floatval($data['printing_charge_per_page']);
                    }
                    if ($data['no_scanning_page'] > 0) {
                        $ac2_amount = $ac2_amount + intval($data['no_scanning_page']) * floatval($data['scanning_charge_per_page']);
                    }
                }
                $curretnYear = date('Y');
                $data['department_data'] = array(
                    "DEPT_CODE" => "ARI",
                    "OFFICE_CODE" => "ARI000",
                    "REC_FIN_YEAR" => $curretnYear.'-'.($curretnYear+1),//"2022-2023", //dynamic
                    "HOA1" => "",
                    "FROM_DATE" => '01/04/'.$curretnYear,//"01/04/2022",
                    "TO_DATE" => "31/03/2099",
                    "PERIOD" => "O", // O for one-time payment
                    "CHALLAN_AMOUNT" => "0",
                    "DEPARTMENT_ID" => $DEPARTMENT_ID,
                    "MOBILE_NO" => $dbRow->form_data->mobile_number,
                    "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/minority-certificate-payment-response'),
                    "PARTY_NAME" => $dbRow->form_data->applicant_name ?? 'RTPS TEAM',
                    "PIN_NO" => $dbRow->form_data->pa_pin_code ?? '781005',
                    "ADDRESS1" => $dbRow->form_data->pa_village ?? 'NIC',
                    "ADDRESS2" => $dbRow->form_data->pa_post_office ?? 'TEAM',
                    "ADDRESS3" => $dbRow->form_data->pa_district_name ?? 'Kamrup'
                );
            }
                     
            $data['department_data']['MULTITRANSFER'] = "Y";
            $data['department_data']['NON_TREASURY_PAYMENT_TYPE'] = "02";
            if ($this->slug === "CSC") {
                $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = floatval($this->degs_fee) + floatval($ac2_amount) + floatval($this->rtps_convenience_fee);
                $data['department_data']['AC1_AMOUNT'] = $this->degs_fee;
                $data['department_data']['ACCOUNT1'] = $districtRow->gras_account_code;                
                $data['department_data']['AC2_AMOUNT'] = $ac2_amount;
                $data['department_data']['ACCOUNT2'] = $this->config->item('csc_account');
                $data['department_data']['AC3_AMOUNT'] = $this->rtps_convenience_fee;
                $data['department_data']['ACCOUNT3'] = $this->rtps_convenience_acc;
            } elseif ($this->slug === "PFC") {
                $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
                $account2 = $user->account1;
                if ($account2 === $this->rtps_convenience_acc) {
                    $ac2_amount = $ac2_amount + intval($this->rtps_convenience_fee);
                    $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = floatval($this->degs_fee) + floatval($ac2_amount);
                    $data['department_data']['AC1_AMOUNT'] = $this->degs_fee;
                    $data['department_data']['ACCOUNT1'] = $districtRow->gras_account_code;
                    $data['department_data']['AC2_AMOUNT'] = $ac2_amount;
                    $data['department_data']['ACCOUNT2'] = $account2;
                } else {
                    $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = floatval($this->degs_fee) + floatval($ac2_amount) + floatval($this->rtps_convenience_acc);
                    $data['department_data']['AC1_AMOUNT'] = $this->degs_fee;
                    $data['department_data']['ACCOUNT1'] = $districtRow->gras_account_code;                    
                    $data['department_data']['AC2_AMOUNT'] = $ac2_amount;
                    $data['department_data']['ACCOUNT2'] = $account2;                    
                    $data['department_data']['AC3_AMOUNT'] = $this->rtps_convenience_fee;
                    $data['department_data']['ACCOUNT3'] = $this->rtps_convenience_acc;
                }//End of if else
            } else {
                $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = floatval($this->degs_fee) + floatval($this->rtps_convenience_fee);
                $data['department_data']['AC1_AMOUNT'] = $this->degs_fee;
                $data['department_data']['ACCOUNT1'] = $districtRow->gras_account_code;
                $data['department_data']['AC2_AMOUNT'] = $this->rtps_convenience_fee;
                $data['department_data']['ACCOUNT2'] = $this->rtps_convenience_acc;
            }//End of if else                        
            //pre($data);
            
            $res = $this->payment_update($data);
            if ($res) {
                $this->load->view('iservices/basundhara/payment', $data);
            } else {
                $this->session->set_flashdata('pay_message', 'Error in payment status updating');
                $this->my_transactions();
            }//End of if else
        } else {
            
            $this->session->set_flashdata('pay_message', 'No records found');
            $this->my_transactions();
        }//End of if else
    }//End of payment_make()

    public function payment_update($data) {
        $payment_params = $data['department_data'];
        $appl_ref_no = $data['appl_ref_no'];
        $data_to_update = array(
            'form_data.department_id' => $payment_params['DEPARTMENT_ID'], 
            'form_data.payment_params' => $payment_params,
            'form_data.rtps_convenience_fee' => (float)$this->rtps_convenience_fee,            
            'form_data.payment_status' => 'PAYMENT_INITIATED'
        );
        
        $payment_history = array(
            'department_id' => $payment_params['DEPARTMENT_ID'],
            'payment_params' => $payment_params,
            'rtps_convenience_fee' => (float)$this->rtps_convenience_fee
        );
        
        if (isset($data['pfc_payment'])) {
            $data_to_update['form_data.service_charge'] = (float)$data['service_charge'];
            $data_to_update['form_data.scanning_charge_per_page'] = (float)$data['scanning_charge_per_page'];
            $data_to_update['form_data.printing_charge_per_page'] = (float)$data['printing_charge_per_page'];
            $data_to_update['form_data.no_printing_page'] = (int)$data['no_printing_page'];
            $data_to_update['form_data.no_scanning_page'] = (int)$data['no_scanning_page'];
            
            $payment_history['service_charge'] = $data['service_charge'];
            $payment_history['scanning_charge_per_page'] = $data['scanning_charge_per_page'];
            $payment_history['printing_charge_per_page'] = $data['printing_charge_per_page'];
            $payment_history['no_printing_page'] = $data['no_printing_page'];
            $payment_history['no_scanning_page'] = $data['no_scanning_page'];
        }

        $result = $this->minoritycertificates_model->update_payment($appl_ref_no, $data_to_update);
        if ($result->getMatchedCount()) {
            $this->load->model('iservices/admin/pfc_payment_history_model');
            $data_to_update['rtps_trans_id'] = $appl_ref_no;
            $data_to_update['createdDtm'] = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
            $this->pfc_payment_history_model->insert($payment_history);
            return true;
        } else {
            return false;
        }//End of if else
    }//End of payment_update()

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Payment