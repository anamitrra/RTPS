<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Payment_bp_query extends Rtps
{
    private $serviceId = "PPBP";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('applications_model');
        $this->load->model('buildingpermission/registration_model');
        $this->load->model('buildingpermission/gras_pp_town_planning_config_model');
        $this->load->model('buildingpermission/gras_bp_payment_uat_config_model');
        $this->load->model('iservices/admin/users_model');
        $this->load->helper('payment');
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
            $ref=modules::load('spservices/buildingpermission/registration');
            $payment_type = "bp";
            $ref->querypaymentsubmit($obj_id, $payment_type);
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
            $ref = modules::load('spservices/buildingpermission/Payment_bp_query_response');
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

        //For Non-Treasury
        if($dbRow->form_data->frs_request->bpPaymentType == "Non-Treasury"){
            if ($dbRow->form_data->frs_request->bpPaymentStatus == "UNPAID"){
                $dbRowBpGras = $this->gras_bp_payment_uat_config_model->get_row(array("ulb_panchayat_id" => (string)$dbRow->form_data->frs_request->ulbPanchayatIdForBp));
                if (empty($dbRowBpGras)) {
    
                    $this->session->set_flashdata('pay_message', $dbRow->form_data->frs_request->ulbPanchayatIdForBp . ' is not activated for payment!');
                    $this->my_transactions();
                }
            }
        }

        //For Treasury
        if($dbRow->form_data->frs_request->bpPaymentType == "Treasury"){
            if ($dbRow->form_data->frs_request->bpPaymentStatus == "UNPAID"){
                $dbRowBpGras = $this->gras_pp_town_planning_config_model->get_row(array("town_planning_id" => (string)$dbRow->form_data->frs_request->ulbPanchayatIdForBp));
                if (empty($dbRowBpGras)) {
    
                    $this->session->set_flashdata('pay_message', $dbRow->form_data->frs_request->ulbPanchayatIdForBp . ' is not activated for payment!');
                    $this->my_transactions();
                }
            }
        }

        if (count((array) $dbRow)) {

            if (property_exists($dbRow->form_data, 'query_bp_payment_status') && $dbRow->form_data->query_bp_payment_status == 'Y') {
                $this->application_submission($obj_id, $dbRow->service_data->service_id);
                $this->session->set_flashdata('pay_message', 'Payment already and hence the status has updated');
                $this->my_transactions();
            }

            if (!empty($dbRow->form_data->query_bp_payment_params) && !empty($dbRow->form_data->query_bp_department_id)) {                
                $res = $this->check_query_payment_status($dbRow->form_data->query_bp_department_id);
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
    }//End of initiate()

    private function payment_make($obj_id = null)
    {
        $dbRow = $this->registration_model->get_by_doc_id($obj_id);

        $bpPaymentAmount = 0;
        //$gmcorpanchayatcode = "HUA22069";

        $dbRowBpGrasCodes = "";
        //For Non-Treasury
        if($dbRow->form_data->frs_request->bpPaymentType == "Non-Treasury"){
            if ($dbRow->form_data->frs_request->bpPaymentStatus == "UNPAID"){
                $bpPaymentAmount = $dbRow->form_data->frs_request->bpAmount;
                $dbRowBpGras = $this->gras_bp_payment_uat_config_model->get_row(array("ulb_panchayat_id" => (string)$dbRow->form_data->frs_request->ulbPanchayatIdForBp));
                if (!empty($dbRowBpGras)){
                    $dbRowBpGrasCodes = $dbRowBpGras;
                } 
                else {
                    $this->session->set_flashdata('pay_message', $dbRow->form_data->frs_request->ulbPanchayatIdForBp . ' is not activated for payment!');
                    $this->my_transactions();
                }
            }
        }

        //For Treasury
        if($dbRow->form_data->frs_request->bpPaymentType == "Treasury"){
            if ($dbRow->form_data->frs_request->bpPaymentStatus == "UNPAID"){
                $bpPaymentAmount = $dbRow->form_data->frs_request->bpAmount;
                $dbRowBpGras = $this->gras_pp_town_planning_config_model->get_row(array("town_planning_id" => (string)$dbRow->form_data->frs_request->ulbPanchayatIdForBp));
                if (!empty($dbRowBpGras)){
                    $dbRowBpGrasCodes = $dbRowBpGras;
                } 
                else {
                    $this->session->set_flashdata('pay_message', $dbRow->form_data->frs_request->ulbPanchayatIdForBp . ' is not activated for payment!');
                    $this->my_transactions();
                }
            }
        }

        if (count((array) $dbRow)) {

            $uniqid = uniqid();
            $DEPARTMENT_ID = $uniqid . time();
            $data = array();
            $data['appl_ref_no'] = $dbRow->service_data->appl_ref_no;

            $total_amount = $dbRow->form_data->frs_request->bpAmount;
            $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
            $data['query_bp_payment'] = true;

            if($dbRow->form_data->frs_request->bpPaymentType == "Non-Treasury"){
                $data['department_data'] = array(
                    "DEPT_CODE" => $dbRowBpGrasCodes->dept_code,
                    "OFFICE_CODE" => $dbRowBpGrasCodes->office_code,
                    "REC_FIN_YEAR" => getFinYear(), //dynamic
                    "HOA1" => "",
                    "FROM_DATE" => firstDateFinYear(),
                    "TO_DATE" => $this->config->item("to_date"),
                    "PERIOD" => "O", // O for one-time payment
                    "CHALLAN_AMOUNT" => "0",
                    "DEPARTMENT_ID" => $DEPARTMENT_ID,
                    "MOBILE_NO" => $dbRow->form_data->mobile,
                    "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/buildingpermission/payment_bp_query_response/response'),
                    "PARTY_NAME" => $dbRow->form_data->applicant_name ?? 'RTPS TEAM',
                    "PIN_NO" => $dbRow->form_data->resPinCode ?? '781005',
                    "ADDRESS1" => $dbRow->form_data->village_town ?? 'NIC',
                    "ADDRESS2" => $dbRow->form_data->post_office ?? 'TEAM',
                    "ADDRESS3" => $dbRow->form_data->district ?? 'Kamrup',
                    "MULTITRANSFER" => "Y",
                    "NON_TREASURY_PAYMENT_TYPE" => $dbRowBpGrasCodes->payment_type,
                    "TOTAL_NON_TREASURY_AMOUNT" => $total_amount,
                    "AC1_AMOUNT" => $bpPaymentAmount,
                    "ACCOUNT1" => $dbRowBpGrasCodes->account_code, //GMDA Planning Permit,
                    // "AC2_AMOUNT" => $bpPaymentAmount,
                    // "ACCOUNT2" => $bPPanchayatCode, //GMC or Panchayat Building Permit,
                    // "ACCOUNT2" => "HUA14380", //GMC or Panchayat Building Permit,
                    // "AC3_AMOUNT" => $labourCessPaymentAmount,
                    // "ACCOUNT3" => "HUA14380", //Labour Cess,
                );
            }

            if($dbRow->form_data->frs_request->bpPaymentType == "Treasury"){
                $data['department_data'] = array(
                    "DEPT_CODE" => $dbRowBpGrasCodes->dept_code,
                    "PAYMENT_TYPE" => $dbRowBpGrasCodes->payment_type,
                    "TREASURY_CODE" => $dbRowBpGrasCodes->treasury_code,
                    "OFFICE_CODE" => $dbRowBpGrasCodes->office_code,
                    "REC_FIN_YEAR" => getFinYear(), //dynamic
                    "FROM_DATE" => firstDateFinYear(),
                    "TO_DATE" => $this->config->item("to_date"),
                    "PERIOD" => "O", // O for one-time payment
                    "MAJOR_HEAD" => $dbRowBpGrasCodes->major_head,
                    "AMOUNT1" => $total_amount,
                    "HOA1" => $dbRowBpGrasCodes->hoa1,
                    "CHALLAN_AMOUNT" => $total_amount,
                    "TAX_ID" => "",
                    "PAN_NO" => "",
                    "DEPARTMENT_ID" => $DEPARTMENT_ID,
                    "MOBILE_NO" => $dbRow->form_data->mobile,
                    "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/buildingpermission/payment_bp_query_response/response'),
                    "PARTY_NAME" => $dbRow->form_data->applicant_name ?? 'RTPS TEAM',
                    "PIN_NO" => $dbRow->form_data->pin_code ?? '781005',
                    "ADDRESS1" => $dbRow->form_data->village_town ?? 'NIC',
                    "ADDRESS2" => $dbRow->form_data->post_office ?? 'TEAM',
                    "ADDRESS3" => $dbRow->form_data->district ?? 'Kamrup',
                );
            }
            
            $res = $this->update_query_payment_amount($data, $total_amount);
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
                $data_to_update['rtps_trans_id'] = $appl_ref_no;
                $data_to_update['createdDtm'] = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
                $this->pfc_payment_history_model->insert($data_to_update);
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