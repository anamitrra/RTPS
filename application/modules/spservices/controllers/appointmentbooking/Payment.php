<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Payment extends Rtps {

    private $serviceId = "APPOINTMENT_BOOKING";
        
    private $rtps_convenience_fee = 10; //For all payment
    private $rtps_convenience_acc = 'ARI64576';

    public function __construct() {
        parent::__construct();
        $this->load->model('services_model');
        $this->load->model('appointmentbooking/appointmentbookings_model');
        $this->load->model('applications_model');
        $this->load->model('sros_model');
        $this->load->model('iservices/admin/users_model');
        $this->load->config('spconfig');
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
    }//End of __construct()

    private function my_transactions() {
        if ($this->session->role) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }//End of if else
    }//End of my_transactions()

    private function check_pfc_payment_status($DEPARTMENT_ID = null) {
        if ($DEPARTMENT_ID) {
            $min = $this->applications_model->checkPFCPaymentIntitateTime($DEPARTMENT_ID);
            if ($min !== 'N' && $min < 6) {
                $this->session->set_flashdata('pay_message', 'Please verify payment status after 5 minutes');
                $this->my_transactions();
                return;
            }
            $ref = modules::load('spservices/appointmentbooking/paymentresponse');
            $grndata = $ref->checkgrn($DEPARTMENT_ID, false, true);
              // pre($grndata );
            if (!empty($grndata)) {
                if ($grndata['STATUS'] === 'Y') {
                    $this->session->set_flashdata('pay_message', 'Payment mode is already in Y');
                    $this->my_transactions();
                    return true;
                }
                $ar = array('N', 'A');
                if (!empty($grndata['GRN']) && !in_array($grndata['STATUS'], $ar)) {
                    $this->session->set_flashdata('pay_message', 'Payment status is either in N or A');
                    $this->my_transactions();
                    return;
                }
            }
        }
        return false;
    }//End of check_pfc_payment_status()

    public function application_submission($obj_id) {
        $dbrow = $this->appointmentbookings_model->get_row(['_id' => new ObjectId($obj_id)]);
        $paymentresponse = modules::load('spservices/appointmentbooking/paymentresponse');
        $paymentresponse->post_data($dbrow->form_data->department_id);
    }//End of application_submission()

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
        $dbRow = $this->appointmentbookings_model->get_row($dbFilter);
        if ($dbRow) {
            if (property_exists($dbRow->form_data, 'pfc_payment_status') && $dbRow->form_data->pfc_payment_status == 'Y') {
                $this->application_submission($obj_id);
                $this->session->set_flashdata('pay_message', 'Payment already and hence the status has updated');
                $this->my_transactions();
            }

            if (!empty($dbRow->form_data->payment_params) && !empty($dbRow->form_data->department_id) && $dbRow->form_data->pfc_payment_status != 'N') {
                $res = $this->check_pfc_payment_status($dbRow->form_data->department_id);
                if ($res) {
                    $this->application_submission($obj_id);
                }
            }

            $payData = array(
                'service_data.appl_status' => 'payment_initiated',
                'form_data.payment_status' => 'START'
            );
            $this->appointmentbookings_model->update_where(['_id' => new ObjectId($obj_id)], $payData); //Update status            
            $data = array("pageTitle" => "Make Payment");
            $data["dbrow"] = $dbRow;

            if (!empty($this->session->userdata('role'))) {
                $data['service_charge'] = $this->config->item('service_charge');
                $data['objid'] = $obj_id;
                $data['no_printing_page'] = isset($data["dbrow"]->no_printing_page) ? $data["dbrow"]->no_printing_page : '';
                $data['no_scanning_page'] = isset($data["dbrow"]->no_scanning_page) ? $data["dbrow"]->no_scanning_page : '';
                $this->load->view('includes/frontend/header');
                $this->load->view('spservices/appointmentbooking/payment_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->payment_make($obj_id);
            }//End of if else
        } else {
            $this->session->set_flashdata('pay_message', 'No records found');
            $this->my_transactions();
        }//End of if else
    }//End of initiate()

    public function submit() {
        $this->form_validation->set_rules('objid', 'objid', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('no_printing_page', 'No printing page', 'trim|required|xss_clean|strip_tags|numeric');

        $this->form_validation->set_rules('no_scanning_page', 'No scanning page', 'trim|required|xss_clean|strip_tags|numeric');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        $obj_id = $this->input->post('objid');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->initiate($obj_id);
        } else {
            $this->payment_make($obj_id);
        }//End of if else
    }//End of submit()

    private function clean($string) {
        return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
    }//End of clean()

    private function payment_make($obj_id = null) {
        $dbRow = $this->appointmentbookings_model->get_by_doc_id($obj_id); //pre($dbRow);
        if (count((array) $dbRow)) {
            $sroRow = $this->sros_model->get_row(array("org_unit_code" => $dbRow->form_data->sro_code));
            if ($sroRow) {
                $ac1_amount = 0;
                
                $treasuryCode = isset($sroRow->treasury_code) ? $sroRow->treasury_code : '';
                $officeCode = isset($sroRow->office_code) ? $sroRow->office_code : '';
                if (strlen($treasuryCode) && strlen($officeCode)) {
                    $serviceRow = $this->services_model->get_row(array("service_id" => $this->serviceId));
                    if ($serviceRow) {
                        $uniqid = uniqid();
                        $DEPARTMENT_ID = $uniqid . time();
                        $data = array();
                        $data['rtps_trans_id'] = $dbRow->service_data->appl_ref_no;
                        $curretnYear = date('Y');
                        $data['department_data'] = array(
                            "DEPT_CODE" => $serviceRow->DEPT_CODE,
                            "OFFICE_CODE" => $sroRow ? $sroRow->office_code : "",
                            "REC_FIN_YEAR" => $curretnYear.'-'.($curretnYear+1),//"2022-2023", //dynamic
                            "PERIOD" => "O", // O for ontimee payment
                            "FROM_DATE" => '01/04/'.$curretnYear,//"01/04/2022",
                            "TO_DATE" => "31/03/2099",
                            "HOA1" => "",
                            "CHALLAN_AMOUNT" => "0",
                            "PARTY_NAME" => isset($dbRow->form_data->applicant_name) ? $this->clean($dbRow->form_data->applicant_name) : "RTPS TEAM", //"TEAM IGR", //applicant name , address of client
                            "PIN_NO" => isset($dbRow->pin_code) ? $dbRow->pin_code : "781005",
                            "ADDRESS1" => isset($dbRow->address1) ? $dbRow->address1 : "NIC",
                            "ADDRESS2" => isset($dbRow->address2) ? $dbRow->address2 : "",
                            "ADDRESS3" => isset($dbRow->address3) ? $dbRow->address3 : "781005",
                            "MOBILE_NO" => isset($dbRow->form_data->mobile_number)?$dbRow->form_data->mobile_number:'9876543210',
                            "DEPARTMENT_ID" => $DEPARTMENT_ID,
                            // "SUB_SYSTEM"=>"REV-SP|".base_url('iservices/admin/get/payment-response'),
                            "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/appointmentbooking/paymentresponse/paymentmade'),
                        );
                        if ($this->slug === "CSC" || $this->slug === "PFC") {
                            $data['service_charge'] = $this->config->item('service_charge');
                            $data['scanning_charge_per_page'] = $this->config->item('scanning_charge');
                            $data['printing_charge_per_page'] = $this->config->item('printing_charge');
                            $data['no_printing_page'] = $this->input->post('no_printing_page');
                            $data['no_scanning_page'] = $this->input->post('no_scanning_page');
                            $data['pfc_payment'] = true;
                            $ac1_amount = $ac1_amount+floatval($data['service_charge']);

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
                                    $ac1_amount = $ac1_amount+intval($data['no_printing_page']) * floatval($data['printing_charge_per_page']);
                                    
                                }
                                if ($data['no_scanning_page'] > 0) {
                                    $ac1_amount = $ac1_amount+intval($data['no_scanning_page']) * floatval($data['scanning_charge_per_page']);
                                }
                            }
                        }
                        
                        $data['department_data']['MULTITRANSFER'] = "Y";
                        $data['department_data']['NON_TREASURY_PAYMENT_TYPE'] = "02";
                        
                        if ($this->slug === "CSC") {
                            $account1 = $this->config->item('csc_account');
                            $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = floatval($ac1_amount)+floatval($this->rtps_convenience_fee);
                            $data['department_data']['AC1_AMOUNT'] = $ac1_amount;
                            $data['department_data']['ACCOUNT1'] = $account1;
                            $data['department_data']['AC2_AMOUNT'] = $this->rtps_convenience_fee;
                            $data['department_data']['ACCOUNT2'] = $this->rtps_convenience_acc;
                        } elseif($this->slug === "PFC") {
                            $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
                            $account1 = $user->account1;
                            if($account1 === $this->rtps_convenience_acc) {
                                $ac1_amount = $ac1_amount+intval($this->rtps_convenience_fee);                                
                                $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = $ac1_amount;
                                $data['department_data']['AC1_AMOUNT'] = $ac1_amount;
                                $data['department_data']['ACCOUNT1'] = $account1;
                            } else {
                                $ac2_amount = $this->rtps_convenience_fee;
                                $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = floatval($ac1_amount)+floatval($ac2_amount);
                                $data['department_data']['AC1_AMOUNT'] = $ac1_amount;
                                $data['department_data']['ACCOUNT1'] = $account1;
                                $data['department_data']['AC2_AMOUNT'] = $this->rtps_convenience_acc;
                                $data['department_data']['ACCOUNT2'] = $ac2_amount;
                            }//End of if else
                        } else {
                            $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = $this->rtps_convenience_fee;
                            $data['department_data']['AC1_AMOUNT'] = $this->rtps_convenience_fee;
                            $data['department_data']['ACCOUNT1'] = $this->rtps_convenience_acc;
                        }//End of if else
                        //pre($data);                        
                        
                        $res = $this->update_pfc_payment_amount($data); //pre($res);
                        if ($res) {
                            $this->load->view('iservices/basundhara/payment', $data);
                        } else {
                            $this->session->set_flashdata('pay_message', 'Error in payment status updating');
                            $this->my_transactions();
                        }//End of if else
                    } else {
                        $this->session->set_flashdata('pay_message', 'Service details does not exist');
                        $this->my_transactions();
                    }//End of if else
                } else {
                    $data = array(
                        "msg_title" => "Payment configuration",
                        "msg_body" => "Payment configuration is not yet done with the selected office/department.<br> We are working on it. Please try again later.<br><br> Thank you."
                    );
                    $this->load->view('includes/frontend/header');
                    $this->load->view('nec/custominfo_view', $data);
                    $this->load->view('includes/frontend/footer');
                }//End of if else
            } else {
                $data = array(
                    "msg_title" => "Payment configuration",
                    "msg_body" => "Payment configuration is not yet done with the selected office/department.<br> We are working on it. Please try again later.<br><br> Thank you."
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('nec/custominfo_view', $data);
                $this->load->view('includes/frontend/footer');
            }//End of if else
        } else {
            $this->session->set_flashdata('pay_message', 'No records found');
            $this->my_transactions();
        }//End of if else
    }//End of payment_make()

    public function update_pfc_payment_amount($data) {
        $payment_params = $data['department_data'];
        $rtps_trans_id = $data['rtps_trans_id'];
        $data_to_update = array(
            'form_data.department_id' => $payment_params['DEPARTMENT_ID'],
            'form_data.payment_params' => $payment_params,
            'form_data.rtps_convenience_fee' => $this->rtps_convenience_fee,
            'form_data.payment_status' => 'INITIATED'
        );
        
        $payment_history = array(
            'department_id' => $payment_params['DEPARTMENT_ID'],
            'payment_params' => $payment_params,
            'rtps_convenience_fee' => (float)$this->rtps_convenience_fee,
            'payment_status' => 'INITIATED'
        );
        
        if (isset($data['pfc_payment'])) {
            $data_to_update['form_data.service_charge'] = (float)$data['service_charge'];
            $data_to_update['form_data.scanning_charge_per_page'] = (float)$data['scanning_charge_per_page'];
            $data_to_update['form_data.printing_charge_per_page'] = (float)$data['printing_charge_per_page'];
            $data_to_update['form_data.no_printing_page'] =(int) $data['no_printing_page'];
            $data_to_update['form_data.no_scanning_page'] = (int)$data['no_scanning_page'];
            
            $payment_history['service_charge'] = $data['service_charge'];
            $payment_history['scanning_charge_per_page'] = $data['scanning_charge_per_page'];
            $payment_history['printing_charge_per_page'] = $data['printing_charge_per_page'];
            $payment_history['no_printing_page'] = $data['no_printing_page'];
            $payment_history['no_scanning_page'] = $data['no_scanning_page'];
        }

        $result = $this->appointmentbookings_model->update_row(['service_data.appl_ref_no' => $rtps_trans_id], $data_to_update);
        // pre($result->getMatchedCount());
        if ($result->getMatchedCount()) {
            $this->load->model('iservices/admin/pfc_payment_history_model');
            $payment_history['rtps_trans_id'] = $rtps_trans_id;
            $payment_history['createdDtm'] = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
            $this->pfc_payment_history_model->insert($payment_history);
            return true;
        } else {
            return false;
        }
    }
}

//End of Payment