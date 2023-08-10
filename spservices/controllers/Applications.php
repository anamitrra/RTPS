<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Applications extends Rtps {

    public function __construct() {
        parent::__construct();
        $this->load->model('applications_model'); 
        $this->load->model('necertificates_model');
        $this->load->config('iservices/rtps_services');
        $this->load->model('services_model');
        $this->load->helper("appstatus");
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
        $this->user = $this->session->userdata();
    }

    public function index() {
        if ($this->slug == "user") {
            if (!isset($this->user['mobile'])) {
                redirect(base_url('iservices/login/logout'));
            }
            $mobile = $this->user['mobile'];
            $fillter=array("mobile" => $mobile);
        } else {
            //for pfc csss
            if (!isset($this->user['role'])) {
                redirect(base_url('iservices/login/logout'));
            }
          
            $fillter=array("applied_by" =>new ObjectId($this->user['userId']->{'$id'}));
        }

      
        $data['applications'] = $this->applications_model->get_rows($fillter);
        $data['applications'] = $this->prepare_transactions($data['applications']);
        $data["necertificaties"] = $this->necertificates_model->get_rows(array("mobile"=> $mobile));
        $data['pageTitle'] = "Applications";
        $this->load->view('includes/frontend/header');
        $this->load->view('applications', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function acknowledgement($objid) {
        $applicationRow = $this->applications_model->get_by_doc_id($objid);
        // pre($applicationRow);
        if ($applicationRow) {
            if(isset($applicationRow->service_id)){
                $service_id = $applicationRow->service_id;
                $data['response'] = $applicationRow;
            }else{
                // for formated data
                $service_id = $applicationRow->service_data->service_id;

                if ($service_id == 'apdcl1') {
                    $rtps_trans_id = $applicationRow->service_data->appl_ref_no." and APDCL Application No.: ".$applicationRow->form_data->application_no;
                }elseif (($service_id == 'CASTE') || ($service_id == 'INC') || ($service_id == 'PDDR') || ($service_id == 'NOKIN') || ($service_id == 'SCTZN') || ($service_id == 'PDBR') || ($service_id == 'BAKCL')){
                    $rtps_trans_id = (!empty($applicationRow->form_data->edistrict_ref_no))? $applicationRow->service_data->appl_ref_no." and eDistrict Ref. No.: ".$applicationRow->form_data->edistrict_ref_no: $applicationRow->service_data->appl_ref_no;
                } else {
                    $rtps_trans_id = $applicationRow->service_data->appl_ref_no;
                }

                $applicationRowData= [
                    "rtps_trans_id" => $rtps_trans_id,
                    "submission_date" => $applicationRow->service_data->submission_date,
                    "applicant_name" => $applicationRow->form_data->applicant_name,
                    "application_no" => isset($applicationRow->form_data->application_no) ? $applicationRow->form_data->application_no:'',
                    "service_charge" => isset($applicationRow->form_data->service_charge)? $applicationRow->form_data->service_charge: "",
                    "rtps_convenience_fee" => isset($applicationRow->form_data->convenience_fee)? $applicationRow->form_data->convenience_fee: "",
                    "no_printing_page" => isset($applicationRow->form_data->no_printing_page)? $applicationRow->form_data->no_printing_page: "",
                    "printing_charge_per_page" => isset($applicationRow->form_data->printing_charge_per_page)? $applicationRow->form_data->printing_charge_per_page: "",
                    "no_scanning_page" => isset($applicationRow->form_data->no_scanning_page)? $applicationRow->form_data->no_scanning_page: "",
                    "scanning_charge_per_page" => isset($applicationRow->form_data->scanning_charge_per_page)? $applicationRow->form_data->scanning_charge_per_page: "",
                    "pfc_payment_status" => isset($applicationRow->form_data->pfc_payment_status)? $applicationRow->form_data->pfc_payment_status: "",
                    

                ];

                if (isset($applicationRow->form_data->application_charge)) {
                    if ($applicationRow->form_data->application_charge != "0") {
                        $applicationRowData['amount'] = $applicationRow->form_data->application_charge;
                    }
                }

                $data['response'] = (object) $applicationRowData;
            }
            // pre($data['response']);
            $data['service_row'] = $this->services_model->get_row(array("service_id"=>$service_id));
            $data['back_to_dasboard'] = '<a href="' . base_url('spservices/applications/') . '" class="btn btn-primary mb-2 pull-right"  >Back To Dashboard</a>';
            $data['pageTitle'] = "Acknowledgement";
            $this->load->view('includes/frontend/header');
            $this->load->view('ack', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            redirect('spservices/applications/');
        }//End of if else
    }

    public function payment_acknowledgement($objid) {
        $applicationRow = $this->applications_model->get_by_doc_id($objid);
        if ($applicationRow) {

            $service_id = $applicationRow->service_data->service_id;
            $rtps_trans_id = $applicationRow->service_data->appl_ref_no;
            $applicant_name = $applicationRow->form_data->applicant_name;
            $fee1 = "";
            $fee2 = "";
            $fee3 = "";
            $fee4 = "";
            $fee5 = "";
            $fee6 = "";
            $fee7 = "";
            $fee8 = "";
            $fee9 = "";

            if ($service_id == 'PPBP') {

                $fee1 = isset($applicationRow->form_data->query_application_charge)? "<b>Application Charge: </b>".$applicationRow->form_data->query_application_charge: "";

                $total_amount = $applicationRow->form_data->query_application_charge + 0;

                $applicationRowData= [
                    "rtps_trans_id" => $rtps_trans_id,
                    "payment_date" => $applicationRow->form_data->query_payment_response->ENTRY_DATE,
                    "payment_ref_no" => $applicationRow->form_data->query_payment_response->GRN,
                    "applicant_name" => $applicant_name,
                    "fee1" => $fee1,
                    "fee2" => "",
                    "fee3" => "",
                    "fee4" => "",
                    "fee5" => "",
                    "fee6" => "",
                    "fee7" => "",
                    "fee8" => "",
                    "fee9" => "",
                    "total_amount" => $total_amount
                ];

                $data['response'] = (object) $applicationRowData;
            } 
        //    pre($data['response']);
            $data['service_row'] = $this->services_model->get_row(array("service_id"=>$service_id));
            $data['back_to_dasboard'] = '<a href="' . base_url('spservices/applications/') . '" class="btn btn-primary mb-2 pull-right"  >Back To Dashboard</a>';
            $data['pageTitle'] = "Payment Acknowledgement";
            $this->load->view('includes/frontend/header');
            $this->load->view('payment_ack', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            redirect('spservices/applications/');
        }//End of if else
    }

    public function archived_transactions() {
        $user = $this->session->userdata();
        if ($this->is_admin()) {
            if ($user['role'] === "csc") {
                $apply_by = $user['userId'];
                $role = "csc";
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
                $role = "pfc";
            }
            $data['intermediate_ids'] = $this->intermediator_model->get_admin_archived_transactions($apply_by, $role);
            $data['pageTitle'] = "Archived Transactions";
            $this->load->view('includes/header');
            // $this->load->view('transactions',$data);
            $this->load->view('mytransactions/archived_transactions', $data);
            $this->load->view('includes/footer');
        } else {
            $data['intermediate_ids'] = $this->intermediator_model->get_my_archived_transactions($this->session->userdata('mobile'));
            $data['pageTitle'] = "Archived Transactions";
            $this->load->view('includes/frontend/header');
            // $this->load->view('transactions',$data);
            $this->load->view('mytransactions/archived_transactions', $data);
            $this->load->view('includes/frontend/footer');
        }
        // pre($data['intermediate_ids']);
    }

    private function prepare_transactions($data) {
        $registered_deed = array();
        $necertificates = array();
        $other_services = array();
        if (!empty($data)) {
            foreach ($data as $trans) {
                if ($trans->service_id === "CRCPY") {
                    array_push($registered_deed, $trans);
                } elseif ($trans->service_id === "NECERTIFICATE") {
                    array_push($necertificates, $trans);
                } else {
                    array_push($other_services, $trans);
                }
            }
        }
        return array(
            "registered_deed" => $registered_deed,
            "necertificates" => $necertificates,
            "other_services" => $other_services
        );
    }

    private function is_admin() {
        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
            return true;
        } else {
            return false;
        }
    }

    private function my_transactions() {
        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }

    //delete appliated
    public function archive_application($rtps_trans_id) {
        //soft delete
        $transaction_data = $this->intermediator_model->get_by_rtps_id($rtps_trans_id);
        if ($this->is_admin()) {
            $fillter = array('rtps_trans_id' => $rtps_trans_id, "applied_by" => new ObjectId($this->session->userdata('userId')->{'$id'}));
        } else {
            $fillter = array('rtps_trans_id' => $rtps_trans_id, 'mobile' => $this->session->userdata('mobile'));
        }

        if (!empty($transaction_data)) {
            if ($transaction_data->status !== "S") {
                if (empty($transaction_data->app_ref_no)) {
                    $result = $this->intermediator_model->update_row($fillter, array('is_archived' => true));
                    if ($result) {
                        $this->session->set_flashdata('message', 'Application Archived Successfuly');
                        $this->session->set_flashdata('status_code', 'success');
                    }
                }
            }
        }
        $this->my_transactions();
    }

    //delete appliated
    public function unarchive_application($rtps_trans_id) {
        //soft delete
        $transaction_data = $this->intermediator_model->get_by_rtps_id($rtps_trans_id);
        if ($this->is_admin()) {
            $fillter = array('rtps_trans_id' => $rtps_trans_id, "applied_by" => new ObjectId($this->session->userdata('userId')->{'$id'}));
        } else {
            $fillter = array('rtps_trans_id' => $rtps_trans_id, 'mobile' => $this->session->userdata('mobile'));
        }

        if (!empty($transaction_data)) {
            $result = $this->intermediator_model->update_row($fillter, array('is_archived' => false));
            if ($result) {
                $this->session->set_flashdata('message', 'Application Unarchived Successfuly');
                $this->session->set_flashdata('status_code', 'success');
            }
        }
        redirect(base_url('iservices/archived-transactions'));
    }

}
