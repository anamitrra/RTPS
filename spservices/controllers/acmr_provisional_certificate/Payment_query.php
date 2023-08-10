<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Payment_query extends Rtps
{
    private $serviceId = "ACMRPRCMD";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('applications_model');
        $this->load->model('acmr_provisional_certificate/registration_model');
        //$this->load->model('buildingpermission/gras_pp_town_planning_config_model');
        //$this->load->model('buildingpermission/gras_bp_payment_uat_config_model');
        $this->load->model('iservices/admin/users_model');
        // $this->load->config('iservices/rtps_services');
        $this->load->config('spconfig');
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else
    } //End of __construct()

    public function application_submission($obj_id,$service_id){
        if($service_id === $serviceId){
            $ref=modules::load('spservices/acmr_provisional_certificate/registration');
            $ref->querypaymentsubmit($obj_id);
        }
    }

    private function my_transactions(){
        $user=$this->session->userdata();
        if(isset($user['role']) && !empty($user['role'])){
            redirect(base_url('iservices/admin/my-transactions'));
        }else{
            redirect(base_url('iservices/transactions'));
        }
    }

    private function check_query_payment_status($DEPARTMENT_ID = null) {
        if ($DEPARTMENT_ID) {
            $min = $this->applications_model->checkPFCPaymentIntitateTimeNew($DEPARTMENT_ID);
            // pre( $min);
            if ($min !== 'N' && $min < 6) {
                $this->session->set_flashdata('pay_message', 'Please verify payment status after 5 minutes');
                $this->my_transactions();
                return;
            }
            $ref = modules::load('spservices/acmr_provisional_certificate/Payment_query_response');
            $grndata = $ref->checkgrn($DEPARTMENT_ID, false, true);

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
    }

    public function initiate($obj_id = null) {

        $dbRow = $this->registration_model->get_by_doc_id($obj_id);
        if (count((array) $dbRow)) {

            if (property_exists($dbRow->form_data, 'pfc_payment_status') && $dbRow->form_data->pfc_payment_status == 'Y') {
                $this->application_submission($obj_id, $dbRow->service_data->service_id);
                $this->session->set_flashdata('pay_message', 'Payment already and hence the status has updated');
                $this->my_transactions();
            }

            if (!empty($dbRow->form_data->payment_params) && !empty($dbRow->form_data->department_id)) {
                $res = $this->check_pfc_payment_status($dbRow->form_data->department_id);
                if ($res) {
                    $this->application_submission($obj_id, $dbRow->service_data->service_id);
                }
            }

        if (count((array) $dbRow)) {

            if (property_exists($dbRow->form_data, 'query_payment_status') && $dbRow->form_data->query_payment_status == 'Y') {
                $this->application_submission($obj_id, $dbRow->service_data->service_id);
                $this->session->set_flashdata('pay_message', 'Payment already and hence the status has updated');
                $this->my_transactions();
            }

            if (!empty($dbRow->form_data->query_payment_params) && !empty($dbRow->form_data->query_department_id)) {                
                $res = $this->check_payment_status($dbRow->form_data->query_department_id);
                if ($res) {
                    $this->application_submission($obj_id, $dbRow->service_data->service_id);
                }
            }

            $this->registration_model->update_where(['_id' => new ObjectId($obj_id)], array('service_data.appl_status'=>'FRS')); //Update status            
            $this->payment_make($obj_id);
        } else {
            $this->session->set_flashdata('pay_message', 'No records found');
            $this->my_transactions();
        }//End of if else
        }
    }//End of initiate()

    private function payment_make($obj_id = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($obj_id);
        pre($dbRow);
        $application_charge = 2000;       
        if (count((array) $dbRow)) {

            if($dbRow->form_data->study_place == 3)
                $application_charge = 0;

            $uniqid = uniqid();
            $DEPARTMENT_ID = $uniqid . time();
            $data = array();
            $data['appl_ref_no'] = $dbRow->service_data->appl_ref_no;
            $data['convenience_fee'] = $this->convenience_fee;
            $dept_code = 'ARI';
            $office_code = "ARI000";
            $data['pfc_payment'] = true;

            if ($this->slug === "user") {
                $data['department_data'] = array(
                    "DEPT_CODE" => $dept_code,
                    "OFFICE_CODE" => $office_code,
                    "REC_FIN_YEAR" => $this->config->item('egras_fin_year'), //dynamic
                    "HOA1" => "",
                    "FROM_DATE" => "01/04/2022",
                    "TO_DATE" => "31/03/2099",
                    "PERIOD" => "O", // O for one-time payment
                    "CHALLAN_AMOUNT" => "0",
                    "DEPARTMENT_ID" => $DEPARTMENT_ID,
                    "MOBILE_NO" => $dbRow->form_data->mobile,
                    "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/acmr_provisional_certificate/payment_response/response'),
                    "PARTY_NAME" => $dbRow->form_data->applicant_name ?? 'RTPS TEAM',
                    "PIN_NO" => $dbRow->form_data->pin_code ?? '781005',
                    "ADDRESS1" => $dbRow->form_data->village_town ?? 'NIC',
                    "ADDRESS2" => $dbRow->form_data->post_office ?? 'TEAM',
                    "ADDRESS3" => $dbRow->form_data->district ?? 'Kamrup',
                    "MULTITRANSFER" => "Y",
                    "NON_TREASURY_PAYMENT_TYPE" => "02",
                    "TOTAL_NON_TREASURY_AMOUNT" => $application_charge + $this->config->item('rtps_convenience_fee'),
                   // "AC1_AMOUNT" => $application_charge,
                   // "ACCOUNT1" => "ARI71580", //$user->account1,$account = "PFC23362";
                    // "ACCOUNT1" => $dbRowGras->account_code,
                    "AC2_AMOUNT" => $application_charge + $this->config->item('rtps_convenience_fee'),
                    "ACCOUNT2" => "PFC23362",
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
                    "FROM_DATE" => "01/04/2022",
                    "TO_DATE" => "31/03/2099",
                    "PERIOD" => "O", // O for one-time payment
                    "CHALLAN_AMOUNT" => "0",
                    "DEPARTMENT_ID" => $DEPARTMENT_ID,
                    "MOBILE_NO" => $dbRow->form_data->mobile,
                    "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/acmr_provisional_certificate/payment_response/response'),
                    "PARTY_NAME" => $dbRow->form_data->applicant_name ?? 'RTPS TEAM',
                    "PIN_NO" => $dbRow->form_data->resPinCode ?? '781005',
                    "ADDRESS1" => $dbRow->form_data->village_town ?? 'NIC',
                    "ADDRESS2" => $dbRow->form_data->post_office ?? 'TEAM',
                    "ADDRESS3" => $dbRow->form_data->district ?? 'Kamrup',
                    "MULTITRANSFER" => "Y",
                    "NON_TREASURY_PAYMENT_TYPE" => "02",
                    //"TOTAL_NON_TREASURY_AMOUNT" => $total_amount + $application_charge + $this->convenience_fee,
                    //"AC1_AMOUNT" => $application_charge,
                    //"ACCOUNT1" => $account, //$user->account1,
                    //"AC2_AMOUNT" => $total_amount,
                    //"ACCOUNT2" => $account,
                    //"AC3_AMOUNT" => $this->convenience_fee,
                    //"ACCOUNT3" => "PFC23362",
                );

                if ($this->slug === "CSC") {
                    $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = $total_amount + $application_charge + $this->config->item('rtps_convenience_fee');
                    $data['department_data']['AC1_AMOUNT'] = $application_charge;
                    $data['department_data']['ACCOUNT1'] = $dbRowGras->account;
                    $data['department_data']['AC2_AMOUNT'] = $total_amount;
                    $data['department_data']['ACCOUNT2'] = $account;
                    $data['department_data']['AC3_AMOUNT'] = $this->config->item('rtps_convenience_fee');
                    $data['department_data']['ACCOUNT3'] = $this->config->item('rtps_convenience_fee_account');
                } else {
                    $data['department_data']['TOTAL_NON_TREASURY_AMOUNT'] = $total_amount + $application_charge + $this->config->item('rtps_convenience_fee');
                    $data['department_data']['AC1_AMOUNT'] = $application_charge;
                    $data['department_data']['ACCOUNT1'] = $dbRowGras->account_code;
                    $data['department_data']['AC2_AMOUNT'] = $total_amount + $this->config->item('rtps_convenience_fee');
                    $data['department_data']['ACCOUNT2'] = $account;
                }
            }
            

            $res = $this->update_pfc_payment_amount($data, $application_charge);
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
    } //End of payment_make() //End of payment_make()

    public function update_query_payment_amount($data, $application_charge){
        //pre($application_charge);
        $payment_params=$data['department_data'];
        $appl_ref_no=$data['appl_ref_no'];
        $data_to_update=array('form_data.query_bp_department_id'=>$payment_params['DEPARTMENT_ID'],'form_data.query_bp_payment_params'=>$payment_params);
        $data_to_update['form_data.query_bp_application_charge']=$application_charge;

        $result=$this->registration_model->update_payment_status($appl_ref_no,$data_to_update);

        if ($result->getMatchedCount()) {
            if (isset($data['query_bp_payment'])) {
                $this->load->model('iservices/admin/pfc_payment_history_model');
                $data_to_update_history['rtps_trans_id'] = $appl_ref_no;
                $data_to_update['createdDtm'] = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
                $this->pfc_payment_history_model->insert($data_to_update_history);
            }
            return true;
        } else {
            return false;
        }
    }

    public function verify($obj_id=null){
        // pre($obj_id);
        // // die;
          if($obj_id){
            $filter = array("_id" =>new ObjectId($obj_id));
          
            $application = $this->registration_model->get_row($filter);
          //  pre($application->form_data->pfc_payment_status);
            if(property_exists($application->form_data,'form_data.query_bp_payment_status')  && $application->form_data->query_bp_payment_status == 'Y'){
              $this->my_transactions();
              return;
            }
            
            if(!empty($application->form_data->query_bp_payment_params) && !empty($application->form_data->query_bp_department_id)){
              $res=$this->check_query_payment_status($application->form_data->query_bp_department_id);
              if($res){
                $this->application_submission($obj_id, $application->service_data->service_id);
              }else{
                
                $this->initiate($obj_id);
              }
              //check grn;
            }else{
              $this->initiate($obj_id);
            }
           
        }else{
            $this->my_transactions();
        }
    }
}//End of Payment