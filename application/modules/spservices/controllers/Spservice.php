<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Spservice extends Rtps {
    public function __construct() {
        parent::__construct();
        $this->load->model('spservicesmodel');
    }//End of __construct()

    public function index() {
        $data["services"] = $this->spservicesmodel->get_all_service();
        $this->load->view('includes/frontend/header');
        $this->load->view('sp_service_list',$data);
        $this->load->view('includes/frontend/footer');
    }
    public function create_service() {
        $data['title'] = 'New Service Registration Form';
        $data['action'] = base_url('spservices/spservice/save');
        $data['btn'] = 'SAVE';
        $this->load->view('includes/frontend/header');
        $this->load->view('create_service', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function edit_service($id) {
        $data['title'] = 'Edit Service Registration Form';
        $data['action'] = base_url('spservices/spservice/save_update');
        $data['btn'] = 'UPDATE';
        $data['id'] = $id;
        $data['service_data'] = (array)$this->spservicesmodel->get_service_data($id);

        $this->load->view('includes/frontend/header');
        $this->load->view('create_service', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function save(){
        $service_name = $this->input->post("service_name");
        $service_id = $this->input->post("service_id");
        $stipulated_timeline = $this->input->post("stipulated_timeline");
        $department_name = $this->input->post("department_name");
        $dept_code = $this->input->post("dept_code");
        $payment_type = $this->input->post("payment_type");
        $major_head = $this->input->post("major_head");
        $head_of_account = $this->input->post("head_of_account");
        $hoa1 = $this->input->post("hoa1");
        $hoa2 = $this->input->post("hoa2");
        $hoa3 = $this->input->post("hoa3");
        $non_treasury_payment_type = $this->input->post("non_treasury_payment_type");
        $non_treasury_account = $this->input->post("non_treasury_account");
        $amount3 = $this->input->post("amount3");
        $account1 = $this->input->post("account1");
        $account2 = $this->input->post("account2");

        $this->form_validation->set_rules('service_name', 'Service name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('service_id', 'Service id', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('stipulated_timeline', 'Stipulated timeline', 'required');
        $this->form_validation->set_rules('department_name', 'Department name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('dept_code', 'Department code', 'trim|required|xss_clean|strip_tags');
        /*$this->form_validation->set_rules('payment_type', 'Payment type', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('major_head', 'Major head', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('head_of_account', 'Head of account', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('hoa1', 'hoa1', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('hoa2', 'hoa2', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('hoa3', 'hoa3', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('non_treasury_payment_type', 'Non treasury payment type', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('non_treasury_account', 'Non treasury account', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('amount3', 'amount3', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('account1', 'account1', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('account2', 'account2', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('hoa3', 'hoa3', 'trim|required|xss_clean|strip_tags');*/
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            redirect('spservices/spservice/create_service');
        } else {  
            $data = array(
                "service_id" =>  $service_id,
                "service_name" => $service_name,
                "department_name" =>  $department_name,
                "MAJOR_HEAD" =>  $major_head,
                "PAYMENT_TYPE" =>  $payment_type,
                "HEAD_OF_ACCOUNT_COUNT" => $head_of_account,
                "HOA1" => $hoa1,
                "HOA2" => $hoa2,
                "HOA3" =>  $hoa3,
                "NON_TREASURY_PAYMENT_TYPE" =>  $non_treasury_payment_type,
                "NON_TREASURY_ACCOUNT_COUNT" =>  $non_treasury_account,
                "AMOUNT3" => $amount3,
                "ACCOUNT1" =>  $account1,
                "ACCOUNT2" => $account2,
                "DEPT_CODE" =>  $dept_code,
                "stipulated_timeline" => $stipulated_timeline,
                "registration_url" => $this->input->post('registration_url'),
                "edit_url" => $this->input->post('edit_url'),
                "query_reply_url" => $this->input->post('query_reply_url'),
                "preview_url" => $this->input->post('preview_url'),
                "acknowledgement_url" => $this->input->post('acknowledgement_url'),
                "track_status_url" => $this->input->post('track_status_url'),
                "make_payment_url" => $this->input->post('make_payment_url'),
                "verify_payment_url" => $this->input->post('verify_payment_url'),
                "query_payment_url" => $this->input->post('query_payment_url'),
                "delivered_url" => $this->input->post('delivered_url')
            );
        

        if($this->spservicesmodel->insert($data)){
            $this->session->set_flashdata('success','Record saved successfully.');
            redirect('spservices/spservice');
        }

        }
    }

    public function save_update(){
        $id=base64_decode($this->input->post("id"));
        $service_name = $this->input->post("service_name");
        $service_id = $this->input->post("service_id");
        $stipulated_timeline = $this->input->post("stipulated_timeline");
        $department_name = $this->input->post("department_name");
        $dept_code = $this->input->post("dept_code");
        $payment_type = $this->input->post("payment_type");
        $major_head = $this->input->post("major_head");
        $head_of_account = $this->input->post("head_of_account");
        $hoa1 = $this->input->post("hoa1");
        $hoa2 = $this->input->post("hoa2");
        $hoa3 = $this->input->post("hoa3");
        $non_treasury_payment_type = $this->input->post("non_treasury_payment_type");
        $non_treasury_account = $this->input->post("non_treasury_account");
        $amount3 = $this->input->post("amount3");
        $account1 = $this->input->post("account1");
        $account2 = $this->input->post("account2");

        $this->form_validation->set_rules('id', 'Id is not valid', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('service_name', 'Service name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('service_id', 'Service id', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('stipulated_timeline', 'Stipulated timeline', 'required');
        $this->form_validation->set_rules('department_name', 'Department name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('dept_code', 'Department code', 'trim|required|xss_clean|strip_tags');
        /*$this->form_validation->set_rules('payment_type', 'Payment type', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('major_head', 'Major head', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('head_of_account', 'Head of account', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('hoa1', 'hoa1', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('hoa2', 'hoa2', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('hoa3', 'hoa3', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('non_treasury_payment_type', 'Non treasury payment type', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('non_treasury_account', 'Non treasury account', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('amount3', 'amount3', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('account1', 'account1', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('account2', 'account2', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('hoa3', 'hoa3', 'trim|required|xss_clean|strip_tags');*/
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            redirect('spservices/spservice/create_service');
        } else {  
            $data = array(
                "service_id" =>  $service_id,
                "service_name" => $service_name,
                "department_name" =>  $department_name,
                "MAJOR_HEAD" =>  $major_head,
                "PAYMENT_TYPE" =>  $payment_type,
                "HEAD_OF_ACCOUNT_COUNT" => $head_of_account,
                "HOA1" => $hoa1,
                "HOA2" => $hoa2,
                "HOA3" =>  $hoa3,
                "NON_TREASURY_PAYMENT_TYPE" =>  $non_treasury_payment_type,
                "NON_TREASURY_ACCOUNT_COUNT" =>  $non_treasury_account,
                "AMOUNT3" => $amount3,
                "ACCOUNT1" =>  $account1,
                "ACCOUNT2" => $account2,
                "DEPT_CODE" =>  $dept_code,
                "stipulated_timeline" => $stipulated_timeline,
                "registration_url" => $this->input->post('registration_url'),
                "edit_url" => $this->input->post('edit_url'),
                "query_reply_url" => $this->input->post('query_reply_url'),
                "preview_url" => $this->input->post('preview_url'),
                "acknowledgement_url" => $this->input->post('acknowledgement_url'),
                "track_status_url" => $this->input->post('track_status_url'),
                "make_payment_url" => $this->input->post('make_payment_url'),
                "verify_payment_url" => $this->input->post('verify_payment_url'),
                "query_payment_url" => $this->input->post('query_payment_url'),
                "delivered_url" => $this->input->post('delivered_url')
            );
        

        $this->spservicesmodel->update_where(['_id' => new ObjectId($id)], $data);
        $this->session->set_flashdata('success','Record updated successfully.');
        redirect('spservices/spservice');

        }
    }
    // delete_service
    public function delete_service($id) {
        $obj = base64_decode($id);
        $delete = $this->mongo_db->delete('sp_services', array('_id' =>  new ObjectId($obj)));
        if($delete){
            $this->session->set_flashdata('success','Record deleted successfully.');
            redirect('spservices/spservice');
        }
    }
}