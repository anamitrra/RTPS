<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Payment extends Rtps
{
    private $serviceId = "SCTZN";
    private $convenience_fee;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('applications_model');
        $this->load->model('senior_citizen_model');
        $this->load->model('iservices/admin/users_model');
        // $this->load->config('iservices/rtps_services');
        $this->load->config('spconfig');
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else
        $this->convenience_fee = $this->config->item('rtps_convenience_fee');
        $this->convenience_fee_account = $this->config->item('rtps_convenience_fee_account');
    } //End of __construct()

    public function application_submission($obj_id, $service_id)
    {
        if ($service_id === $this->serviceId) {
            $ref = modules::load('spservices/seniorcitizen/scc');
            $ref->submit_to_backend($obj_id);
        }
    }

    private function my_transactions()
    {
        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }

    private function check_pfc_payment_status($DEPARTMENT_ID = null)
    {
        if ($DEPARTMENT_ID) {
            $min = $this->applications_model->checkPFCPaymentIntitateTimeNew($DEPARTMENT_ID);
            // pre( $min);
            if ($min !== 'N' && $min < 6) {
                $this->session->set_flashdata('pay_message', 'Please verify payment status after 5 minutes');
                $this->my_transactions();
                return;
            }
            $ref = modules::load('spservices/seniorcitizen/Payment_response');
            $grndata = $ref->checkgrn($DEPARTMENT_ID, false, true);

            if (!empty($grndata)) {
                if ($grndata['STATUS'] === 'Y') {
                    $this->session->set_flashdata('pay_message', 'Payment mode is already in Y');
                    $this->my_transactions();
                    return true;
                }
                $ar = array('N', 'A');
                if (!empty($grndata['GRN']) && !in_array($grndata['STATUS'], $ar)) {
                    //$this->session->set_flashdata('pay_message', 'Payment status is either in N or A');
                    $this->session->set_flashdata('pay_message', 'Payment status is not updated. please retry after sometime.!');
                    $this->my_transactions();
                    return;
                }
            }
        }
        return false;
    }

    public function initiate($obj_id = null)
    {

        $dbRow = $this->senior_citizen_model->get_by_doc_id($obj_id);
        if (count((array) $dbRow)) {

            if (property_exists($dbRow->form_data, 'pfc_payment_status') && $dbRow->form_data->pfc_payment_status == 'Y') {
                $this->application_submission($obj_id, $dbRow->service_data->service_id);
                $this->session->set_flashdata('pay_message', 'Payment already and hence the status has updated');
                $this->my_transactions();
            }

            if (!empty($dbRow->form_data->payment_params) && !empty($dbRow->form_data->department_id)) {
                $res = $this->check_pfc_payment_status($dbRow->form_data->department_id);
                if ($res) {
                    $this->application_submission($dbRow->service_data->service_id);
                }
            }

            $this->senior_citizen_model->update_where(['_id' => new ObjectId($obj_id)], array('service_data.appl_status' => 'payment_initiated')); //Update status            
            $data = array("pageTitle" => "Make Payment");
            $data["dbrow"] = $dbRow;

            if (!empty($this->session->userdata('role'))) {

                $data['service_charge'] = $this->config->item('service_charge');
                $data['rtps_convenience_fee'] = $this->convenience_fee;
                $data['objid'] = $obj_id;
                $data['no_printing_page'] = isset($data["dbrow"]->form_data->no_printing_page) ? $data["dbrow"]->form_data->no_printing_page :  '';
                $data['no_scanning_page'] = isset($data["dbrow"]->form_data->no_scanning_page) ? $data["dbrow"]->form_data->no_scanning_page :  '';
                $data['appl_ref_no'] = isset($data["dbrow"]->service_data->appl_ref_no) ? $data["dbrow"]->service_data->appl_ref_no :  '';
                $this->load->view('includes/frontend/header');
                $this->load->view('seniorcitizen/kiosk_payment', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->payment_make($obj_id);
            } //End of if else
        } else {
            $this->session->set_flashdata('pay_message', 'No records found');
            $this->my_transactions();
        } //End of if else
    } //End of initiate()

    private function payment_make($obj_id = null)
    {
        $dbRow = $this->senior_citizen_model->get_by_doc_id($obj_id);
        if (count((array) $dbRow)) {
            $uniqid = uniqid();
            $DEPARTMENT_ID = $uniqid . time();
            $data = array();
            $data['appl_ref_no'] = $dbRow->service_data->appl_ref_no;
            $data['convenience_fee'] = $this->convenience_fee;
            $dept_code = 'ARI';
            $office_code = "ARI000";

            if ($this->slug === "user") {
                $data['department_data'] = array(
                    "DEPT_CODE" => $dept_code,
                    "OFFICE_CODE" => $office_code,
                    "REC_FIN_YEAR" => $this->config->item('egras_fin_year'), //dynamic
                    "HOA1" => "",
                    "FROM_DATE" => "01/04/2023",
                    "TO_DATE" => "31/03/2099",
                    "PERIOD" => "O", // O for one-time payment
                    "CHALLAN_AMOUNT" => "0",
                    "DEPARTMENT_ID" => $DEPARTMENT_ID,
                    "MOBILE_NO" => $dbRow->form_data->mobile,
                    "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/seniorcitizen/Payment_response/response'),
                    "PARTY_NAME" => $dbRow->form_data->applicant_name ?? 'RTPS TEAM',
                    "PIN_NO" => $dbRow->form_data->pin_code ?? '781005',
                    "ADDRESS1" => $dbRow->form_data->village_town ?? 'NIC',
                    "ADDRESS2" => $dbRow->form_data->post_office ?? 'TEAM',
                    "ADDRESS3" => $dbRow->form_data->district ?? 'Kamrup',
                    "MULTITRANSFER" => "Y",
                    "NON_TREASURY_PAYMENT_TYPE" => "02",
                    "TOTAL_NON_TREASURY_AMOUNT" => $this->convenience_fee,
                    "AC1_AMOUNT" => $this->convenience_fee,
                    "ACCOUNT1" => $this->convenience_fee_account, //this is convenient fee Account code from spconfig file
                );
                //pre($data);
            } else {
                if ($this->slug === "CSC") {
                    $account = $this->config->item('csc_account');
                } else {
                    $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
                    $account = $user->account1;
                }

                $data['service_charge'] = $this->config->item('service_charge');
                $data['scanning_charge_per_page'] = $this->config->item('scanning_charge');
                $data['printing_charge_per_page'] = $this->config->item('printing_charge');

                $data['appl_ref_no'] = $this->input->post('appl_ref_no');
                $data['no_printing_page'] = $this->input->post('no_printing_page');
                $data['no_scanning_page'] = $this->input->post('no_scanning_page');
                $data['pfc_payment'] = true;
                $total_amount = $data['service_charge'];

                if (!empty($data['no_printing_page']) && (intval($data['no_printing_page']) < 0 || !is_numeric($data['no_printing_page']))) {
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


                if ($this->slug !== "CSC") {
                    if ($this->session->userdata('role')->role_name == "PFC") {
                        if ($data['no_printing_page'] > 0) {
                            $total_amount += intval($data['no_printing_page']) * floatval($data['printing_charge_per_page']);
                        }
                        if ($data['no_scanning_page'] > 0) {
                            $total_amount += intval($data['no_scanning_page']) * floatval($data['scanning_charge_per_page']);
                        }
                    }
                }


                // if ($this->session->userdata('role') === "CSC") {
                //     $account = $this->config->item('csc_account');
                //     if ($data['no_printing_page'] > 0) {
                //         $total_amount += intval($data['no_printing_page']) * floatval($data['printing_charge_per_page']);
                //     }
                //     if ($data['no_scanning_page'] > 0) {
                //         $total_amount += intval($data['no_scanning_page']) * floatval($data['scanning_charge_per_page']);
                //     }
                // } else {
                //     $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
                //     $account = $user->account1;
                // } //End of if else

                //pre($total_amount);

                $data['department_data'] = array(
                    "DEPT_CODE" => $dept_code,
                    "OFFICE_CODE" => $office_code,
                    "REC_FIN_YEAR" => $this->config->item('egras_fin_year'), //dynamic
                    "HOA1" => "",
                    "FROM_DATE" => "01/04/2023",
                    "TO_DATE" => "31/03/2099",
                    "PERIOD" => "O", // O for one-time payment
                    "CHALLAN_AMOUNT" => "0",
                    "DEPARTMENT_ID" => $DEPARTMENT_ID,
                    "MOBILE_NO" => $dbRow->form_data->mobile,
                    "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/seniorcitizen/Payment_response/response'),
                    "PARTY_NAME" => $dbRow->form_data->applicant_name ?? 'RTPS TEAM',
                    "PIN_NO" => $dbRow->form_data->resPinCode ?? '781005',
                    "ADDRESS1" => $dbRow->form_data->village_town ?? 'NIC',
                    "ADDRESS2" => $dbRow->form_data->post_office ?? 'TEAM',
                    "ADDRESS3" => $dbRow->form_data->district ?? 'Kamrup',
                    "MULTITRANSFER" => "Y",
                    "NON_TREASURY_PAYMENT_TYPE" => "02",
                    //"TOTAL_NON_TREASURY_AMOUNT" => $total_amount + $this->convenience_fee,
                    //"AC1_AMOUNT" => $total_amount,
                    //"ACCOUNT1" => $account,
                    //"AC2_AMOUNT" => $this->convenience_fee,
                    //"ACCOUNT2" => $this->config->item('rtps_convenience_fee_account'),
                );



                if ($this->slug === "CSC") {
                    $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = $total_amount + $this->convenience_fee;
                    $data['department_data']['AC1_AMOUNT'] = $total_amount;
                    $data['department_data']['ACCOUNT1'] = $account;
                    $data['department_data']['AC2_AMOUNT'] = $this->convenience_fee;
                    $data['department_data']['ACCOUNT2'] = $this->config->item('rtps_convenience_fee_account');
                } else {
                    if ($user->type_of_kiosk == "eDistrict") {
                        $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = $total_amount + $this->convenience_fee;
                        $data['department_data']['AC1_AMOUNT'] = $total_amount;
                        $data['department_data']['ACCOUNT1'] = $account;
                        $data['department_data']['AC2_AMOUNT'] = $this->convenience_fee;
                        $data['department_data']['ACCOUNT2'] = $this->config->item('rtps_convenience_fee_account');
                    } else {
                        $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = $total_amount + $this->convenience_fee;
                        $data['department_data']['AC1_AMOUNT'] = $total_amount + $this->convenience_fee;
                        $data['department_data']['ACCOUNT1'] = $account;
                    }
                }


            }

            $res = $this->update_pfc_payment_amount($data);
            //pre($res);
            if ($res) {
                $this->load->view('iservices/basundhara/payment', $data);
            } else {
                $this->session->set_flashdata('pay_message', 'Error in payment status updating');
                $this->my_transactions();
            } //End of if else
        } else {
            $this->session->set_flashdata('pay_message', 'No records found');
            $this->my_transactions();
        } //End of if else
    } //End of payment_make()

    public function update_pfc_payment_amount($data)
    {
        //pre($application_charge);
        $payment_params = $data['department_data'];
        $appl_ref_no = $data['appl_ref_no'];
        $data_to_update = array('form_data.department_id' => $payment_params['DEPARTMENT_ID'], 'form_data.payment_params' => $payment_params);
        if (isset($data['pfc_payment'])) {
            $data_to_update['form_data.service_charge'] = $data['service_charge'];
            $data_to_update['form_data.scanning_charge_per_page'] = $data['scanning_charge_per_page'];
            $data_to_update['form_data.printing_charge_per_page'] = $data['printing_charge_per_page'];
            $data_to_update['form_data.no_printing_page'] = $data['no_printing_page'];
            $data_to_update['form_data.no_scanning_page'] = $data['no_scanning_page'];
        }
        $data_to_update['form_data.convenience_fee'] = $data['convenience_fee'];

        $result = $this->senior_citizen_model->update_payment_status($appl_ref_no, $data_to_update);

        if ($result->getMatchedCount()) {
            if (isset($data['pfc_payment'])) {
                $this->load->model('iservices/admin/pfc_payment_history_model');
                $data_to_update_history['rtps_trans_id'] = $appl_ref_no;
                $data_to_update_history['form_data']['department_id'] = $payment_params['DEPARTMENT_ID'];
                $data_to_update_history['form_data']['payment_params'] = $data['department_data'];
                $data_to_update_history['form_data']['service_charge'] = $data['service_charge'] || 'NA';
                $data_to_update_history['form_data']['scanning_charge_per_page'] = $data['scanning_charge_per_page'] || 'NA';
                $data_to_update_history['form_data']['printing_charge_per_page'] = $data['printing_charge_per_page'] || 'NA';
                $data_to_update_history['form_data']['no_printing_page'] = $data['no_printing_page'] || 'NA';
                $data_to_update_history['form_data']['no_scanning_page'] = $data['no_scanning_page'] || 'NA';

                $data_to_update['createdDtm'] = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
                $this->pfc_payment_history_model->insert($data_to_update_history);
            }
            return true;
        } else {
            return false;
        }
    }

    public function submit()
    {
        $this->form_validation->set_rules('objid', 'objid', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('appl_ref_no', 'appl_ref_no', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('no_printing_page', 'No printing page', 'trim|required|xss_clean|strip_tags|numeric');

        $this->form_validation->set_rules('no_scanning_page', 'No scanning page', 'trim|required|xss_clean|strip_tags|numeric');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = $this->input->post('objid');
            if ($obj_id) {
                $this->initiate($obj_id);
            } else {
                redirect(base_url('iservices/transactions'));
            }
        } else {
            $obj_id = $this->input->post('objid');
            $this->payment_make($obj_id);
        } //End of if else
    }

    public function verify($obj_id = null)
    {
        // pre($obj_id);
        // die;
        check_application_count_for_citizen();
        if ($obj_id) {
            $filter = array("_id" => new ObjectId($obj_id));

            $application = $this->senior_citizen_model->get_row($filter);
            //  pre($application->form_data->pfc_payment_status);
            if (property_exists($application->form_data, 'pfc_payment_status')  && $application->form_data->pfc_payment_status == 'Y') {
                $this->my_transactions();
                return;
            }

            if (!empty($application->form_data->payment_params) && !empty($application->form_data->department_id)) {
                $res = $this->check_pfc_payment_status($application->form_data->department_id);
                if ($res) {
                    $this->application_submission($obj_id, $application->service_data->service_id);
                } else {

                    $this->initiate($obj_id);
                }
                //check grn;
            } else {
                $this->initiate($obj_id);
            }
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }
}//End of Payment