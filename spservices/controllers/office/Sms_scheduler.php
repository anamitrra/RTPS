<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Sms_scheduler extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('smsprovider');
        $this->load->model("office/application_model");
    }

    public function index(){
    }
    //schedular for sending delivered sms
    public function send_delivery_sms($ref_no)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        $applications = (array)$this->application_model->get_single_application($ref_no);
        $data = array(
            "mobile" => intval($applications[0]->form_data->mobile_number),
            "applicant_name" => $applications[0]->form_data->applicant_name,
            "service_name" => 'Minority Community Certificate',
            "submission_date" => format_mongo_date($applications[0]->form_data->created_at),
            "app_ref_no" => $applications[0]->service_data->appl_ref_no,
            "rtps_trans_id" => $applications[0]->service_data->appl_ref_no,
        );
        sms_provider("delivery", $data);
    }
    public function send_query_sms($ref_no)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        $applications = (array)$this->application_model->get_single_application($ref_no);
        $data = array(
            "mobile" => intval($applications[0]->form_data->mobile_number),
            "applicant_name" => $applications[0]->form_data->applicant_name,
            "service_name" => 'Minority Community Certificate',
            "app_ref_no" => $applications[0]->service_data->appl_ref_no,
            "obj_id" => $applications[0]->_id->{'$id'},
        );
        sms_provider("mcc_revert_applicant", $data);
    }
    public function send_reject_sms($ref_no)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        $applications = (array)$this->application_model->get_single_application($ref_no);
        $data = array(
            "mobile" => intval($applications[0]->form_data->mobile_number),
            "applicant_name" => $applications[0]->form_data->applicant_name,
            "service_name" => 'Minority Community Certificate',
            "submission_date" => format_mongo_date($applications[0]->form_data->created_at),
            "app_ref_no" => $applications[0]->service_data->appl_ref_no,
            "rtps_trans_id" => $applications[0]->service_data->appl_ref_no,
            "submission_office"=>'',
        );
        sms_provider("rejection", $data);
    }
}
