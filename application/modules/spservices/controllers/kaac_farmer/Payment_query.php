<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Payment_query extends Rtps
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('applications_model');
        $this->load->model('kaac/kaac_registration_model');
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
        $service_ids = array("FCERT");

        if(in_array($service_id, $service_ids)){ 
            $ref=modules::load('spservices/kaac_farmer/registration');
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
            $ref = modules::load('spservices/kaac_farmer/Payment_query_response');
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

        $dbRow = $this->kaac_registration_model->get_by_doc_id($obj_id);


        if (count((array) $dbRow)) {

            if (property_exists($dbRow->form_data, 'query_payment_status') && $dbRow->form_data->query_payment_status == 'Y') {
                $this->application_submission($obj_id, $dbRow->form_data->service_id);
                $this->session->set_flashdata('pay_message', 'Payment already and hence the status has updated');
                $this->my_transactions();
            }

            if (!empty($dbRow->form_data->query_payment_params) && !empty($dbRow->form_data->query_department_id)) {                
                $res = $this->check_query_payment_status($dbRow->form_data->query_department_id);
                if ($res) {
                    $this->application_submission($obj_id, $dbRow->form_data->service_id);
                }
            }

            $this->kaac_registration_model->update_where(['_id' => new ObjectId($obj_id)], array('service_data.appl_status'=>'FRS')); //Update status            
            $this->payment_make($obj_id);
        } else {
            $this->session->set_flashdata('pay_message', 'No records found');
            $this->my_transactions();
        }//End of if else
    }//End of initiate()

    private function payment_make($obj_id = null)
    {
        $dbRow = $this->kaac_registration_model->get_by_doc_id($obj_id);

        if (count((array) $dbRow)) {

            $uniqid = uniqid();
            $DEPARTMENT_ID = $uniqid . time();
            $data = array();
            $data['appl_ref_no'] = $dbRow->service_data->appl_ref_no;

            $total_amount = $dbRow->form_data->frs_request->amount;
            $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
            $data['query_payment'] = true;


                $data['department_data'] = array(
                    "DEPT_CODE" => $this->config->item('kaac_dept_code'), 
                    "OFFICE_CODE" => $this->config->item('kaac_office_code'),
                    "REC_FIN_YEAR" => getFinYear(), //dynamic
                    "HOA1" => "",
                    "FROM_DATE" => firstDateFinYear(),
                    "TO_DATE" => $this->config->item("to_date"),
                    "PERIOD" => "O", // O for one-time payment
                    "CHALLAN_AMOUNT" => "0",
                    "DEPARTMENT_ID" => $DEPARTMENT_ID,
                    "MOBILE_NO" => $dbRow->form_data->mobile,
                    "SUB_SYSTEM" => "ARTPS-SP|" . base_url('spservices/kaac_farmer/payment_query_response/response'),
                    "PARTY_NAME" => $dbRow->form_data->applicant_name ?? 'RTPS TEAM',
                    "PIN_NO" => $dbRow->form_data->resPinCode ?? '781005',
                    "ADDRESS1" => $dbRow->form_data->village_town ?? 'NIC',
                    "ADDRESS2" => $dbRow->form_data->post_office ?? 'TEAM',
                    "ADDRESS3" => $dbRow->form_data->district ?? 'Kamrup',
                    "MULTITRANSFER" => "Y",
                    "NON_TREASURY_PAYMENT_TYPE" => "02", //$dbRowGras->NON_TREASURY_PAYMENT_TYPE,
                    "TOTAL_NON_TREASURY_AMOUNT" => $total_amount,
                    "AC1_AMOUNT" => $total_amount,
                    "ACCOUNT1" => $this->config->item('kaac_query_account'),
                );

            


            
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
        $data_to_update=array('form_data.query_department_id'=>$payment_params['DEPARTMENT_ID'],'form_data.query_payment_params'=>$payment_params);
        $data_to_update['form_data.query_application_charge']=$application_charge;

        $result=$this->kaac_registration_model->update_payment_status($appl_ref_no,$data_to_update);

        if ($result->getMatchedCount()) {
            if (isset($data['query_payment'])) {
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
          
            $application = $this->kaac_registration_model->get_row($filter);
          //  pre($application->form_data->pfc_payment_status);
            if(property_exists($application->form_data,'form_data.query_payment_status')  && $application->form_data->query_payment_status == 'Y'){
              $this->my_transactions();
              return;
            }
            
            if(!empty($application->form_data->query_payment_params) && !empty($application->form_data->query_department_id)){
              $res=$this->check_query_payment_status($application->form_data->query_department_id);
              if($res){
                $this->application_submission($obj_id,$application->form_data->service_id);
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