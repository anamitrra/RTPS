<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Upgradation extends Rtps
{

    private $serviceName = "Application for Upgradation of Contractors";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('registration_of_contractors/employment_model');
        $this->load->model('registration_of_contractors/district_model');
        $this->load->model('zones_model');
        $this->load->model('zonecircles_model');
        $this->load->model('upms/users_model');

        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper('smsprovider');
        $this->load->helper('contractor');

        $this->load->model('registration_of_contractors/logreports_model');
        $this->load->helper("role");
        $this->load->library('user_agent');
        $this->load->library('Digilocker_API');

        if ($this->session->role) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
    } //End of __construct()

    public function index($objId = null)
    {
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        }
        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/search_details_upgr', $data);
        $this->load->view('includes/frontend/footer');
    }

    
    function search_details()
    {
        $this->form_validation->set_rules("regs_no", "Registration No.", "required|max_length[30]");
        $this->form_validation->set_rules("pan_card", "PAN", "required|regex_match[/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/]");

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = null;
            $this->index($obj_id);
            //var_dump(validation_errors());

        } else {
        
            $regs_no = $this->input->post('regs_no');
            $pan_card = $this->input->post('pan_card');
            $data_to_search_draft = array(
                'form_data.registration_no' => $regs_no,
                'form_data.pan_card' => $pan_card,
                'service_data.appl_status' => 'DRAFT'
            );
            $dbRow_D = $this->employment_model->get_row_last($data_to_search_draft);

            if($dbRow_D)
            {
                $obj_id = $dbRow_D->{'_id'}->{'$id'};
                redirect('spservices/upgradation_of_contractors/personal-details/' . $obj_id);
            }
            else {


            $data_to_search = array(
                'form_data.registration_no' => $regs_no,
                'form_data.pan_card' => $pan_card,
                'service_data.appl_status' => 'D'
            );

            $dbRow = $this->employment_model->get_row_last($data_to_search);
            if(!$dbRow)
            {
            $this->session->set_flashdata('fail', 'Record not found. Please check again.');
            $obj_id = null;
            $this->index($obj_id);
            }
            else if($dbRow->form_data->category == 'Class-1(A)')
            {
            $this->session->set_flashdata('fail', 'Upgradation not available. Please check again.');
            $obj_id = null;
            $this->index($obj_id);
            }
            else {

            $formData = $dbRow->form_data;

            $appl_ref_no_temp = $this->getID(7);
            $serviceId = $this->get_serviceID($dbRow->form_data->deptt_name, $dbRow->form_data->category);
            $created_at = getISOTimestamp();
            $deptt_code = $dbRow->form_data->deptt_name;
            $serviceData = array(
                "department_name" => $dbRow->service_data->department_name,
                "service_id" => $serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $appl_ref_no_temp,
                "appl_ref_no" => $appl_ref_no_temp,
                "submission_mode" => 'online',
                "applied_by" => $this->session->userdata('userId')->{'$id'},
                "appl_status" => "DRAFT",
                "created_at" => $created_at,
            );
            unset($formData->declaration);
            unset($formData->convenience_fee);
            unset($formData->department_id);
            unset($formData->payment_params);
            unset($formData->pfc_payment_response);
            unset($formData->pfc_payment_status);
            unset($formData->submission_date);
            unset($formData->certificate);
            
            $formData->user_id = $this->session->userdata('userId')->{'$id'};
            $formData->user_type = $this->slug;
            $formData->applied_user_type = $this->slug;
            $formData->rtps_trans_id = $appl_ref_no_temp;
            $formData->service_name = $this->serviceName;
            $formData->service_id = $serviceId;

            $data = array('service_data' => $serviceData, 'form_data' => $formData);
            $insertedData = $this->employment_model->insert($data);
            $obj_id = ($insertedData['_id']->{'$id'});

            redirect('spservices/upgradation_of_contractors/personal-details/' . $obj_id);
            }
        }
            
        }
    }

    public function personal_details($objId = null)
    {
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else
        
        $regs_no =$dbRow->form_data->registration_no;
        $pan_card =$dbRow->form_data->pan_card;
        $data_to_search = array(
            'form_data.registration_no' => $regs_no,
            'form_data.pan_card' => $pan_card,
            'service_data.appl_status' => 'D'
        );

        $dbRowReg = $this->employment_model->get_row_last($data_to_search);
        $category = $dbRowReg->form_data->category;
        $data["category_reg"] = $category;

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/personal_details_upgr', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_personal_details()
    {
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules("category", "Class & Category", "required");
        $deptt_arr = array("PHED", "PWDB", "WRD");
        if (in_array($this->input->post("deptt_name"), $deptt_arr)) {
            if ($this->input->post("category") == 'Class-II') {
                $this->form_validation->set_rules("zone", "Zone", "required");
                $this->form_validation->set_rules("circle", "Circle", "required");
            } else {
                $this->form_validation->set_rules("zone", "Zone", "required");
            }
        }
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
            //var_dump(validation_errors());

        } else {
            //Security deposit and registration fee
            $deptt_code = $this->input->post("deptt_name");
            $category = $this->input->post("category");
            $category_of_regs = $this->input->post("category_of_regs");
            $caste = $this->input->post("caste") ?? '';
            $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
            $office_payments_reg = $dbRow->form_data->office_payments;
            if($deptt_code == 'WRD') {
                $old_fd_amt = 0;
            } else {
                $old_fd_amt = $office_payments_reg->security_deposit;
            }
            
            
            $res_fee = calcSecurityAmtForContractors($deptt_code, $category, $caste, $category_of_regs);
            $res_fee_arr = explode('|', $res_fee);
            $office_payments_upgr = array();
            $cnt = COUNT($dbRow->form_data->office_payments_upgr) ?? 0;
            if($old_fd_amt > 0) {
                $office_payments_upgr[$cnt]['security_deposit'] = $res_fee_arr[0]-$old_fd_amt;
            } else {
                $office_payments_upgr[$cnt]['security_deposit'] = $res_fee_arr[0];
            }
            $office_payments_upgr[$cnt]['registration_fees'] = $res_fee_arr[1];
            $serviceId = $this->get_serviceID($this->input->post("deptt_name"), $this->input->post("category"));
            $form_data = array(
                "service_data.service_id" => $serviceId,
                "form_data.service_id" => $serviceId,
                "form_data.category" => $this->input->post("category"),
                "form_data.zone" => $this->input->post("zone"),
                "form_data.circle" => $this->input->post("circle"),
                'form_data.office_payments_upgr' => $office_payments_upgr,
            );

            if (strlen($objId)) {
                $this->employment_model->update_where(['_id' => new ObjectId($objId)], $form_data);
                $obj_id = $objId;
            }
            $this->session->set_flashdata('success', 'Personal Details has been successfully saved.');
            redirect('spservices/upgradation_of_contractors/address-section/' . $obj_id);
        }

    }

    public function address($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "districts" => $this->district_model->get_rows(),
        );

        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else  

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/address_details_upgr', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_address_details()
    {
        $objId = $this->input->post("obj_id");
        redirect('spservices/upgradation_of_contractors/work-section/' . $objId);
    }

    public function work($objId = null)
    {

        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else 

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/work_details_upgr', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_work_details()
    {
        $objId = $this->input->post("obj_id");
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        $category = $dbRow->form_data->category;
        $deptt_code = $dbRow->form_data->deptt_name;
        $category_of_regs = $dbRow->form_data->category_of_regs ?? '';
        if($category_of_regs == 'Unemployed Graduate Engineer' || $category_of_regs == 'Unemployed Diploma Engineer') {
        $this->form_validation->set_rules("work_position[]", " ", "max_length[100]");
        $this->form_validation->set_rules("emp_name[]", " ", "max_length[50]");
        $this->form_validation->set_rules("emp_qualification[]", " ", "max_length[50]");
        $this->form_validation->set_rules("total_exp[]", " ", "alpha_numeric_spaces|max_length[30]");
        $this->form_validation->set_rules("with_contractor_exp[]", " ", "alpha_numeric_spaces|max_length[30]");
        } else {
        $this->form_validation->set_rules("work_position[]", " ", "required|max_length[100]");
        $this->form_validation->set_rules("emp_name[]", " ", "required|max_length[50]");
        $this->form_validation->set_rules("emp_qualification[]", " ", "required|max_length[50]");
        $this->form_validation->set_rules("total_exp[]", " ", "required|alpha_numeric_spaces|max_length[30]");
        $this->form_validation->set_rules("with_contractor_exp[]", " ", "required|alpha_numeric_spaces|max_length[30]");
        }

        if($deptt_code != 'WRD') {
            if(($category_of_regs != 'Unemployed Graduate Engineer') && ($category_of_regs != 'Unemployed Diploma Engineer')) {
        $this->form_validation->set_rules("project_name[]", " ", "required|max_length[100]");
        $this->form_validation->set_rules("employer[]", " ", "required|max_length[100]");
        $this->form_validation->set_rules("work_description[]", " ", "required|max_length[100]");
        $this->form_validation->set_rules("con_wo_no[]", " ", "required|max_length[75]");
        $this->form_validation->set_rules("value_contract_p[]", " ", "required|numeric|max_length[15]");
        $this->form_validation->set_rules("wo_date_p[]", " ", "required|regex_match[/\d{2}-\d{2}-\d{4}/]");
        $this->form_validation->set_rules("st_completion_date_p[]", " ", "required|regex_match[/\d{2}-\d{2}-\d{4}/]");
        $this->form_validation->set_rules("actual_completion_date[]", " ", "required|regex_match[/\d{2}-\d{2}-\d{4}/]");
        $this->form_validation->set_rules("remarks_reasons[]", " ", "max_length[100]");
            }
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->work($obj_id);
            //var_dump(validation_errors());

        } else {

        $work_count = count($this->input->post('desc_work'));
        for ($i = 0; $i < $work_count; $i++) {
            $ongoing_works_arr[] = [
                "desc_work" => $this->input->post('desc_work')[$i],
                "place_state" => $this->input->post('place_state')[$i],
                "contract_no_date" => $this->input->post('contract_no_date')[$i],
                "employer_name" => $this->input->post('employer_name')[$i],
                "value_contract" => $this->input->post('value_contract')[$i],
                "wo_date" => $this->input->post('wo_date')[$i],
                "st_completion_date" => $this->input->post('st_completion_date')[$i],
                "work_value_remaining" => $this->input->post('work_value_remaining')[$i],
                "ant_date_completion" => $this->input->post('ant_date_completion')[$i],
            ];
        }

        $work_executed_count = count($this->input->post('project_name'));
        $total_work_value = 0;
        for ($i = 0; $i < $work_executed_count; $i++) {
            $executed_works_arr[] = [
                "project_name" => $this->input->post('project_name')[$i],
                "employer" => $this->input->post('employer')[$i],
                "work_description" => $this->input->post('work_description')[$i],
                "con_wo_no" => $this->input->post('con_wo_no')[$i],
                "value_contract_p" => $this->input->post('value_contract_p')[$i],
                "wo_date_p" => $this->input->post('wo_date_p')[$i],
                "st_completion_date_p" => $this->input->post('st_completion_date_p')[$i],
                "actual_completion_date" => $this->input->post('actual_completion_date')[$i],
                "remarks_reasons" => $this->input->post('remarks_reasons')[$i],
            ];
            $total_work_value = $total_work_value + (int)$this->input->post('value_contract_p')[$i];
        }
        if(($category_of_regs != 'Unemployed Graduate Engineer') && ($category_of_regs != 'Unemployed Diploma Engineer')) {
        $res_amt = calcMinExecutedWorkValue($deptt_code, $category, $total_work_value);
        $res_amt_arr = explode('|', $res_amt);
        $amt = $res_amt_arr[0];
        $flag = $res_amt_arr[1];
        }
        else {
            $flag = true;
        }

        if(!$flag)
        {
            $this->session->set_flashdata('error_valid', 'Minimum work value is Rs.'.$amt.' !');
            $obj_id = strlen($objId) ? $objId : null;
            $this->work($obj_id);
        }
        else {
        
        $quantities_of_works_count = count($this->input->post('work_item'));
        for ($i = 0; $i < $quantities_of_works_count; $i++) {
            $quantities_of_works_arr[] = [
                "work_item" => $this->input->post('work_item')[$i],
                "work_unit" => $this->input->post('work_unit')[$i],
                "fin_years" => $this->input->post('fin_years')[$i],
            ];
        }

        $key_personnel_count = count($this->input->post('work_position'));
        for ($i = 0; $i < $key_personnel_count; $i++) {
            $key_personnel_arr[] = [
                "work_position" => $this->input->post('work_position')[$i],
                "emp_name" => $this->input->post('emp_name')[$i],
                "emp_qualification" => $this->input->post('emp_qualification')[$i],
                "total_exp" => $this->input->post('total_exp')[$i],
                "with_contractor_exp" => $this->input->post('with_contractor_exp')[$i],
            ];
        }

        $data = array(
            'form_data.ongoing_works' => $ongoing_works_arr,
            'form_data.works_executed' => $executed_works_arr,
            'form_data.quantities_of_works_executed' => $quantities_of_works_arr,
            'form_data.key_personnel' => $key_personnel_arr,
        );
        // pre($data);
        $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
        $this->session->set_flashdata('success', 'Work Details has been successfully saved.');
        redirect('spservices/upgradation_of_contractors/machinery-section/' . $objId);
        }
    }
}

    public function machinery($objId = null)
    {

        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else 

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/machinery_details_upgr', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_machinery_details()
    {
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules("machinery[]", " ", "required|max_length[100]");
        $this->form_validation->set_rules("numbers_owned[]", " ", "required|numeric|max_length[50]");
        $this->form_validation->set_rules("avg_age_condition[]", " ", "required|max_length[50]");
        $this->form_validation->set_rules("equipment_reg_no[]", " ", "max_length[50]");
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->machinery($obj_id);
            //var_dump(validation_errors());

        } else {
        $machinery_count = count($this->input->post('machinery'));
        for ($i = 0; $i < $machinery_count; $i++) {
            $machinery_arr[] = [
                "machinery" => $this->input->post('machinery')[$i],
                "numbers_owned" => $this->input->post('numbers_owned')[$i],
                "avg_age_condition" => $this->input->post('avg_age_condition')[$i],
                "equipment_reg_no" => $this->input->post('equipment_reg_no')[$i],
            ];
        }

        $data = array(
            'form_data.machineries_owned' => $machinery_arr,
        );
        // pre($data);
        $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);

        $this->session->set_flashdata('success', 'Machinery Details has been successfully saved.');
        redirect('spservices/upgradation_of_contractors/turnover-section/' . $objId);
    }
}

    public function turnover($objId = null)
    {

        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else 

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/turnover_details_upgr', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_turnover_details()
    {
        $objId = $this->input->post("obj_id");
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        $deptt_code = $dbRow->form_data->deptt_name;
        $category = $dbRow->form_data->category;
        $category_of_regs = $dbRow->form_data->category_of_regs ?? '';
        if(($category_of_regs != 'Unemployed Graduate Engineer') && ($category_of_regs != 'Unemployed Diploma Engineer')) {
        $this->form_validation->set_rules("fin_year_turnover[]", " ", "required|max_length[100]");
        $this->form_validation->set_rules("turnover[]", " ", "required|numeric|max_length[10]");
        }
        else {
        $this->form_validation->set_rules("fin_year_turnover[]", " ", "max_length[100]");
        $this->form_validation->set_rules("turnover[]", " ", "numeric|max_length[10]");
        }
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->turnover($obj_id);
            //var_dump(validation_errors());

        } else {
        $turnover_count = count($this->input->post('fin_year_turnover'));
        $flag = true;
        $amt = '';
        $total_turnover = 0;
        if(($category_of_regs != 'Unemployed Graduate Engineer') && ($category_of_regs != 'Unemployed Diploma Engineer')) {
        for ($i = 0; $i < $turnover_count; $i++) {
            $turnover_arr[] = [
                "fin_year_turnover" => $this->input->post('fin_year_turnover')[$i],
                "turnover" => $this->input->post('turnover')[$i],
            ];
            $total_turnover = $total_turnover + (int)$this->input->post('turnover')[$i];
            
        }

        if($deptt_code == 'PHED') {
            if($category == 'Class-1(A)' && $total_turnover < 20000000)
            {
                $flag = false;
                $amt = '20000000';
            }
            else if($category == 'Class-1(B)' && $total_turnover < 10000000)
            {
                $flag = false;
                $amt = '10000000';
            }
            else if($category == 'Class-1(C)' && $total_turnover < 5000000)
            {
                $flag = false;
                $amt = '5000000';
            }
            else if($category == 'Class-II' && $total_turnover < 1000000)
            {
                $flag = false;
                $amt = '1000000';
            }
        }

        if($deptt_code == 'PWDB') {
            $total_turnover = $total_turnover/$turnover_count;
            if($category == 'Class-1(A)' && $total_turnover < 20000000)
            {
                $flag = false;
                $amt = '20000000';
            }
            else if($category == 'Class-1(B)' && $total_turnover < 10000000)
            {
                $flag = false;
                $amt = '10000000';
            }
            else if($category == 'Class-1(C)' && $total_turnover < 5000000)
            {
                $flag = false;
                $amt = '5000000';
            }
            else if($category == 'Class-II' && $total_turnover < 1000000)
            {
                $flag = false;
                $amt = '1000000';
            }
        }
    }

    if($flag === false)
    {
        if(($category_of_regs != 'Unemployed Graduate Engineer') && ($category_of_regs != 'Unemployed Diploma Engineer')) {
        $this->session->set_flashdata('error_valid', 'Annual Financial Turnover is less than Rs.'.$amt.' !');
        $obj_id = strlen($objId) ? $objId : null;
        $this->turnover($obj_id);
        }
    } 
    else {
        $data = array(
            'form_data.financial_turnover' => $turnover_arr,
        );
        // pre($data);
        $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);

        $this->session->set_flashdata('success', 'Turnover Details has been successfully saved.');
        redirect('spservices/upgradation_of_contractors/history-section/' . $objId);
        }
     }
    }

    public function history($objId = null)
    {

        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else 

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/history_details_upgr', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_history_details()
    {
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules("employer_dispute[]", "Employer", "xss_clean|max_length[50]|regex_match[/^[a-z\.\/\s]+$/i]");
        $this->form_validation->set_rules("cause_of_dispute[]", "Cause of dispute", "xss_clean|max_length[100]|regex_match[/^[a-z\d\.\/\s]+$/i]");
        $this->form_validation->set_rules("status[]", "Status", "xss_clean|max_length[50]|regex_match[/^[a-z\.\/\s]+$/i]");
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->history($obj_id);
            //var_dump(validation_errors());

        } else {
        $history_count = count($this->input->post('employer_dispute'));
        for ($i = 0; $i < $history_count; $i++) {
            $history_arr[] = [
                "employer_dispute" => $this->input->post('employer_dispute')[$i],
                "cause_of_dispute" => $this->input->post('cause_of_dispute')[$i],
                "status" => $this->input->post('status')[$i],
            ];
        }

        $data = array(
            'form_data.litigation_history' => $history_arr,
        );
        // pre($data);
        $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);

        $this->session->set_flashdata('success', 'History has been successfully saved.');
        redirect('spservices/upgradation_of_contractors/attachments-section/' . $objId);
    }
}

    public function attachments($objId = null)
    {

        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else 

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/attachments_up_upgr', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_enclosures()
    {
        $objId = $this->input->post("obj_id");

        if (strlen($this->input->post("photograph_temp")) > 0) {
            $photographFile = movedigilockerfile($this->input->post('photograph_temp'));
            $photograph = $photographFile["upload_status"] ? $photographFile["uploaded_path"] : null;
        } else {
            $photographFile = cifileupload("photograph");
            $photograph = $photographFile["upload_status"] ? $photographFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("copy_pan_card_temp")) > 0) {
            $pancardFile = movedigilockerfile($this->input->post('copy_pan_card_temp'));
            $panCard = $pancardFile["upload_status"] ? $pancardFile["uploaded_path"] : null;
        } else {
            $pancardFile = cifileupload("copy_pan_card");
            $panCard = $pancardFile["upload_status"] ? $pancardFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("caste_cert_temp")) > 0) {
            $casteCertFile = movedigilockerfile($this->input->post('caste_cert_temp'));
            $casteCert = $casteCertFile["upload_status"] ? $casteCertFile["uploaded_path"] : null;
        } else {
            $casteCertFile = cifileupload("caste_cert");
            $casteCert = $casteCertFile["upload_status"] ? $casteCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("pvr_passport_temp")) > 0) {
            $pvrPassportFile = movedigilockerfile($this->input->post('pvr_passport_temp'));
            $pvrPassport = $pvrPassportFile["upload_status"] ? $pvrPassportFile["uploaded_path"] : null;
        } else {
            $pvrPassportFile = cifileupload("pvr_passport");
            $pvrPassport = $pvrPassportFile["upload_status"] ? $pvrPassportFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("gst_reg_cert_temp")) > 0) {
            $gstRegCertFile = movedigilockerfile($this->input->post('gst_reg_cert_temp'));
            $gstRegCert = $gstRegCertFile["upload_status"] ? $gstRegCertFile["uploaded_path"] : null;
        } else {
            $gstRegCertFile = cifileupload("gst_reg_cert");
            $gstRegCert = $gstRegCertFile["upload_status"] ? $gstRegCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("bank_solvency_cert_temp")) > 0) {
            $bankSolvencyCertFile = movedigilockerfile($this->input->post('bank_solvency_cert_temp'));
            $bankSolvencyCert = $bankSolvencyCertFile["upload_status"] ? $bankSolvencyCertFile["uploaded_path"] : null;
        } else {
            $bankSolvencyCertFile = cifileupload("bank_solvency_cert");
            $bankSolvencyCert = $bankSolvencyCertFile["upload_status"] ? $bankSolvencyCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("labour_license_temp")) > 0) {
            $labourLicenseFile = movedigilockerfile($this->input->post('labour_license_temp'));
            $labourLicense = $labourLicenseFile["upload_status"] ? $labourLicenseFile["uploaded_path"] : null;
        } else {
            $labourLicenseFile = cifileupload("labour_license");
            $labourLicense = $labourLicenseFile["upload_status"] ? $labourLicenseFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("key_personnel_temp")) > 0) {
            $keyPersonnelFile = movedigilockerfile($this->input->post('key_personnel_temp'));
            $keyPersonnel = $keyPersonnelFile["upload_status"] ? $keyPersonnelFile["uploaded_path"] : null;
        } else {
            $keyPersonnelFile = cifileupload("key_personnel");
            $keyPersonnel = $keyPersonnelFile["upload_status"] ? $keyPersonnelFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("turnover_cert_temp")) > 0) {
            $turnoverCertFile = movedigilockerfile($this->input->post('turnover_cert_temp'));
            $turnoverCert = $turnoverCertFile["upload_status"] ? $turnoverCertFile["uploaded_path"] : null;
        } else {
            $turnoverCertFile = cifileupload("turnover_cert");
            $turnoverCert = $turnoverCertFile["upload_status"] ? $turnoverCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("machinery_details_temp")) > 0) {
            $machineryDetailsFile = movedigilockerfile($this->input->post('machinery_details_temp'));
            $machineryDetails = $machineryDetailsFile["upload_status"] ? $machineryDetailsFile["uploaded_path"] : null;
        } else {
            $machineryDetailsFile = cifileupload("machinery_details");
            $machineryDetails = $machineryDetailsFile["upload_status"] ? $machineryDetailsFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("tax_clearance_cert_temp")) > 0) {
            $taxClearanceCertFile = movedigilockerfile($this->input->post('tax_clearance_cert_temp'));
            $taxClearanceCert = $taxClearanceCertFile["upload_status"] ? $taxClearanceCertFile["uploaded_path"] : null;
        } else {
            $taxClearanceCertFile = cifileupload("tax_clearance_cert");
            $taxClearanceCert = $taxClearanceCertFile["upload_status"] ? $taxClearanceCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("work_completion_cert_temp")) > 0) {
            $workCompletionCertFile = movedigilockerfile($this->input->post('work_completion_cert_temp'));
            $workCompletionCert = $workCompletionCertFile["upload_status"] ? $workCompletionCertFile["uploaded_path"] : null;
        } else {
            $workCompletionCertFile = cifileupload("work_completion_cert");
            $workCompletionCert = $workCompletionCertFile["upload_status"] ? $workCompletionCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("quantities_work_cert_temp")) > 0) {
            $quantitiesWorkCertFile = movedigilockerfile($this->input->post('quantities_work_cert_temp'));
            $quantitiesWorkCert = $quantitiesWorkCertFile["upload_status"] ? $quantitiesWorkCertFile["uploaded_path"] : null;
        } else {
            $quantitiesWorkCertFile = cifileupload("quantities_work_cert");
            $quantitiesWorkCert = $quantitiesWorkCertFile["upload_status"] ? $quantitiesWorkCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("proof_of_address_temp")) > 0) {
            $proofOfAddressFile = movedigilockerfile($this->input->post('proof_of_address_temp'));
            $proofOfAddress = $proofOfAddressFile["upload_status"] ? $proofOfAddressFile["uploaded_path"] : null;
        } else {
            $proofOfAddressFile = cifileupload("proof_of_address");
            $proofOfAddress = $proofOfAddressFile["upload_status"] ? $proofOfAddressFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("power_attorney_temp")) > 0) {
            $powerOfAttorneyFile = movedigilockerfile($this->input->post('power_attorney_temp'));
            $powerOfAttorney = $powerOfAttorneyFile["upload_status"] ? $powerOfAttorneyFile["uploaded_path"] : null;
        } else {
            $powerOfAttorneyFile = cifileupload("power_attorney");
            $powerOfAttorney = $powerOfAttorneyFile["upload_status"] ? $powerOfAttorneyFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("firm_reg_cert_temp")) > 0) {
            $firmRegCertFile = movedigilockerfile($this->input->post('firm_reg_cert_temp'));
            $firmRegCert = $firmRegCertFile["upload_status"] ? $firmRegCertFile["uploaded_path"] : null;
        } else {
            $firmRegCertFile = cifileupload("firm_reg_cert");
            $firmRegCert = $firmRegCertFile["upload_status"] ? $firmRegCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("deed_maaa_cert_temp")) > 0) {
            $deedMaaaCertFile = movedigilockerfile($this->input->post('deed_maaa_cert_temp'));
            $deedMaaaCert = $deedMaaaCertFile["upload_status"] ? $deedMaaaCertFile["uploaded_path"] : null;
        } else {
            $deedMaaaCertFile = cifileupload("deed_maaa_cert");
            $deedMaaaCert = $deedMaaaCertFile["upload_status"] ? $deedMaaaCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("emp_provident_fund_temp")) > 0) {
            $empProvidentFundFile = movedigilockerfile($this->input->post('emp_provident_fund_temp'));
            $empProvidentFund = $empProvidentFundFile["upload_status"] ? $empProvidentFundFile["uploaded_path"] : null;
        } else {
            $empProvidentFundFile = cifileupload("emp_provident_fund");
            $empProvidentFund = $empProvidentFundFile["upload_status"] ? $empProvidentFundFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("affidavit_cert_temp")) > 0) {
            $affidavitCertFile = movedigilockerfile($this->input->post('affidavit_cert_temp'));
            $affidavitCert = $affidavitCertFile["upload_status"] ? $affidavitCertFile["uploaded_path"] : null;
        } else {
            $affidavitCertFile = cifileupload("affidavit_cert");
            $affidavitCert = $affidavitCertFile["upload_status"] ? $affidavitCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("regs_other_deptt_temp")) > 0) {
            $regsOtherDepttFile = movedigilockerfile($this->input->post('regs_other_deptt_temp'));
            $regsOtherDeptt = $regsOtherDepttFile["upload_status"] ? $regsOtherDepttFile["uploaded_path"] : null;
        } else {
            $regsOtherDepttFile = cifileupload("regs_other_deptt");
            $regsOtherDeptt = $regsOtherDepttFile["upload_status"] ? $regsOtherDepttFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("affidavit_unemployment_temp")) > 0) {
            $affidavitUnemploymentFile = movedigilockerfile($this->input->post('affidavit_unemployment_temp'));
            $affidavitUnemployment = $affidavitUnemploymentFile["upload_status"] ? $affidavitUnemploymentFile["uploaded_path"] : null;
        } else {
            $affidavitUnemploymentFile = cifileupload("affidavit_unemployment");
            $affidavitUnemployment = $affidavitUnemploymentFile["upload_status"] ? $affidavitUnemploymentFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("affidavit_license_expired_temp")) > 0) {
            $affidavitLicenseExpiredFile = movedigilockerfile($this->input->post('affidavit_license_expired_temp'));
            $affidavitLicenseExpired = $affidavitLicenseExpiredFile["upload_status"] ? $affidavitLicenseExpiredFile["uploaded_path"] : null;
        } else {
            $affidavitLicenseExpiredFile = cifileupload("affidavit_license_expired");
            $affidavitLicenseExpired = $affidavitLicenseExpiredFile["upload_status"] ? $affidavitLicenseExpiredFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("any_other_docs_temp")) > 0) {
            $anyOtherDocsFile = movedigilockerfile($this->input->post('any_other_docs_temp'));
            $anyOtherDocs = $anyOtherDocsFile["upload_status"] ? $anyOtherDocsFile["uploaded_path"] : null;
        } else {
            $anyOtherDocsFile = cifileupload("any_other_docs");
            $anyOtherDocs = $anyOtherDocsFile["upload_status"] ? $anyOtherDocsFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("forwarding_letter_temp")) > 0) {
            $forwardingLetterFile = movedigilockerfile($this->input->post('forwarding_letter_temp'));
            $forwardingLetter = $forwardingLetterFile["upload_status"] ? $forwardingLetterFile["uploaded_path"] : null;
        } else {
            $forwardingLetterFile = cifileupload("forwarding_letter");
            $forwardingLetter = $forwardingLetterFile["upload_status"] ? $forwardingLetterFile["uploaded_path"] : null;
        }

        $uploadedFiles = array(
            "photograph_old" => strlen($photograph) ? $photograph : $this->input->post("photograph_old"),
            "copy_pan_card_old" => strlen($panCard) ? $panCard : $this->input->post("copy_pan_card_old"),
            "caste_cert_old" => strlen($casteCert) ? $casteCert : $this->input->post("caste_cert_old"),
            "pvr_passport_old" => strlen($pvrPassport) ? $pvrPassport : $this->input->post("pvr_passport_old"),
            "gst_reg_cert_old" => strlen($gstRegCert) ? $gstRegCert : $this->input->post("gst_reg_cert_old"),
            "bank_solvency_cert_old" => strlen($bankSolvencyCert) ? $bankSolvencyCert : $this->input->post("bank_solvency_cert_old"),
            "labour_license_old" => strlen($labourLicense) ? $labourLicense : $this->input->post("labour_license_old"),
            "key_personnel_old" => strlen($keyPersonnel) ? $keyPersonnel : $this->input->post("key_personnel_old"),
            "turnover_cert_old" => strlen($turnoverCert) ? $turnoverCert : $this->input->post("turnover_cert_old"),
            "machinery_details_old" => strlen($machineryDetails) ? $machineryDetails : $this->input->post("machinery_details_old"),
            "tax_clearance_cert_old" => strlen($taxClearanceCert) ? $taxClearanceCert : $this->input->post("tax_clearance_cert_old"),
            "work_completion_cert_old" => strlen($workCompletionCert) ? $workCompletionCert : $this->input->post("work_completion_cert_old"),
            "quantities_work_cert_old" => strlen($quantitiesWorkCert) ? $quantitiesWorkCert : $this->input->post("quantities_work_cert_old"),
            "proof_of_address_old" => strlen($proofOfAddress) ? $proofOfAddress : $this->input->post("proof_of_address_old"),
            "power_attorney_old" => strlen($powerOfAttorney) ? $powerOfAttorney : $this->input->post("power_attorney_old"),
            "firm_reg_cert_old" => strlen($firmRegCert) ? $firmRegCert : $this->input->post("firm_reg_cert_old"),
            "deed_maaa_cert_old" => strlen($deedMaaaCert) ? $deedMaaaCert : $this->input->post("deed_maaa_cert_old"),
            "emp_provident_fund_old" => strlen($empProvidentFund) ? $empProvidentFund : $this->input->post("emp_provident_fund_old"),
            "affidavit_cert_old" => strlen($affidavitCert) ? $affidavitCert : $this->input->post("affidavit_cert_old"),
            "regs_other_deptt_old" => strlen($regsOtherDeptt) ? $regsOtherDeptt : $this->input->post("regs_other_deptt_old"),
            "affidavit_unemployment_old" => strlen($affidavitUnemployment) ? $affidavitUnemployment : $this->input->post("affidavit_unemployment_old"),
            "affidavit_license_expired_old" => strlen($affidavitLicenseExpired) ? $affidavitLicenseExpired : $this->input->post("affidavit_license_expired_old"),
            "any_other_docs_old" => strlen($anyOtherDocs) ? $anyOtherDocs : $this->input->post("any_other_docs_old"),
            "forwarding_letter_old" => strlen($forwardingLetter) ? $forwardingLetter : $this->input->post("forwarding_letter_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        $data = array(
            'form_data.declaration' => $this->input->post("declaration"),
            'form_data.enclosures.photograph' => strlen($photograph) ? $photograph : $this->input->post("photograph_old"),
            'form_data.enclosures.copy_pan_card' => strlen($panCard) ? $panCard : $this->input->post("copy_pan_card_old"),
            'form_data.enclosures.caste_cert' => strlen($casteCert) ? $casteCert : $this->input->post("caste_cert_old"),
            'form_data.enclosures.pvr_passport' => strlen($pvrPassport) ? $pvrPassport : $this->input->post("pvr_passport_old"),
            'form_data.enclosures.gst_reg_cert' => strlen($gstRegCert) ? $gstRegCert : $this->input->post("gst_reg_cert_old"),
            'form_data.enclosures.bank_solvency_cert' => strlen($bankSolvencyCert) ? $bankSolvencyCert : $this->input->post("bank_solvency_cert_old"),
            'form_data.enclosures.labour_license' => strlen($labourLicense) ? $labourLicense : $this->input->post("labour_license_old"),
            'form_data.enclosures.key_personnel' => strlen($keyPersonnel) ? $keyPersonnel : $this->input->post("key_personnel_old"),
            'form_data.enclosures.turnover_cert' => strlen($turnoverCert) ? $turnoverCert : $this->input->post("turnover_cert_old"),
            'form_data.enclosures.machinery_details' => strlen($machineryDetails) ? $machineryDetails : $this->input->post("machinery_details_old"),
            'form_data.enclosures.tax_clearance_cert' => strlen($taxClearanceCert) ? $taxClearanceCert : $this->input->post("tax_clearance_cert_old"),
            'form_data.enclosures.work_completion_cert' => strlen($workCompletionCert) ? $workCompletionCert : $this->input->post("work_completion_cert_old"),
            'form_data.enclosures.quantities_work_cert' => strlen($quantitiesWorkCert) ? $quantitiesWorkCert : $this->input->post("quantities_work_cert_old"),
            'form_data.enclosures.proof_of_address' => strlen($proofOfAddress) ? $proofOfAddress : $this->input->post("proof_of_address_old"),
            'form_data.enclosures.power_attorney' => strlen($powerOfAttorney) ? $powerOfAttorney : $this->input->post("power_attorney_old"),
            'form_data.enclosures.firm_reg_cert' => strlen($firmRegCert) ? $firmRegCert : $this->input->post("firm_reg_cert_old"),
            'form_data.enclosures.deed_maaa_cert' => strlen($deedMaaaCert) ? $deedMaaaCert : $this->input->post("deed_maaa_cert_old"),
            "form_data.enclosures.emp_provident_fund" => strlen($empProvidentFund) ? $empProvidentFund : $this->input->post("emp_provident_fund_old"),
            "form_data.enclosures.affidavit_cert" => strlen($affidavitCert) ? $affidavitCert : $this->input->post("affidavit_cert_old"),
            "form_data.enclosures.regs_other_deptt" => strlen($regsOtherDeptt) ? $regsOtherDeptt : $this->input->post("regs_other_deptt_old"),
            "form_data.enclosures.affidavit_unemployment" => strlen($affidavitUnemployment) ? $affidavitUnemployment : $this->input->post("affidavit_unemployment_old"),
            "form_data.enclosures.affidavit_license_expired" => strlen($affidavitLicenseExpired) ? $affidavitLicenseExpired : $this->input->post("affidavit_license_expired_old"),
            "form_data.enclosures.any_other_docs" => strlen($anyOtherDocs) ? $anyOtherDocs : $this->input->post("any_other_docs_old"),
            "form_data.enclosures.forwarding_letter" => strlen($forwardingLetter) ? $forwardingLetter : $this->input->post("forwarding_letter_old")

        );

        $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
        redirect('spservices/upgradation_of_contractors/preview/' . $objId);
    }

    function preview($objId = null)
    {
        $data = array(
            "obj_id" => $objId
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/regs_preview_upgr', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_query($objId = null)
    {
        if ($objId) {
            $dbRow = $this->employment_model->get_by_doc_id($objId);
            if (count((array)$dbRow)) {

                $applicant_name = "...";
                if($dbRow->form_data->applicant_type == 'Individual')
                {
                    $applicant_name = $dbRow->form_data->applicant_name;
                }
                else if($dbRow->form_data->applicant_type == 'Proprietorship')
                {
                    $applicant_name = $dbRow->form_data->owner_director_name;
                }
                else
                {
                    $applicant_name = $dbRow->form_data->org_name;
                }

                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Query submitted by " . $applicant_name,
                    "action_taken" => "Query submitted",
                    "remarks" => "Query submitted by " . $applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
                $data = array(
                    "service_data.appl_status" => "QA",
                    'processing_history' => $processing_history,
                    'status' => "QUERY_ANSWERED"
                );
                $this->employment_model->update($objId, $data);
                $this->session->set_flashdata('success', 'Query has been successfully updated');
                $this->my_transactions();
            }
        } else {
            $this->my_transactions();
        }
    }

    public function finalsubmition($objId = null)
    {
        if ($objId) {
            $dbRow = $this->employment_model->get_by_doc_id($objId);
            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }
            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")) {

                $applicant_name = "...";
                if($dbRow->form_data->applicant_type == 'Individual')
                {
                    $applicant_name = $dbRow->form_data->applicant_name;
                }
                else if($dbRow->form_data->applicant_type == 'Proprietorship')
                {
                    $applicant_name = $dbRow->form_data->owner_director_name;
                }
                else
                {
                    $applicant_name = $dbRow->form_data->org_name;
                }

                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by " . $applicant_name,
                    "action_taken" => "Application Submission",
                    "remarks" => "Application submitted by " . $applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
                $deptt_code = $dbRow->form_data->deptt_name;
                $category = $dbRow->form_data->category;
                $serviceId = $this->get_serviceID($deptt_code, $category);
                $zone = $dbRow->form_data->zone;
                
                if($category == 'Class-II') {
                $circle = $dbRow->form_data->circle;
                $userFilter = array('user_services.service_code' => $serviceId, 'zone_info.zone_name' => $zone, 'zone_circle' => $circle, 'dept_info.dept_code' => $deptt_code, 'user_levels.level_no' => 1, 'status' => 1);
                }
                else {
                $userFilter = array('user_services.service_code' => $serviceId, 'zone_info.zone_name' => $zone, 'dept_info.dept_code' => $deptt_code, 'user_levels.level_no' => 1, 'status' => 1);
                }

                $userRows = $this->users_model->get_rows($userFilter);

                if (!$userRows) {
                    $this->session->set_flashdata('pmt_msg', 'Not submitted! Please verify again.');

                    if ($this->session->role) {
                        redirect('iservices/admin/my-transactions');
                    } else {
                        redirect('iservices/transactions');
                    }
                    exit;
                }

                $office_loc = "";
                if($deptt_code == 'PWDB')
                {
                    if($category == 'Class-II') {
                        $office_loc = $dbRow->form_data->circle;
                    } else {
                        $office_loc = $zone;
                    }
                }
                else if($deptt_code == 'PHED')
                {
                    if(in_array($zone, $exc_zone))
                    {
                        $office_loc = $zone;
                    }
                    else {
                        if($category == 'Class-II') {
                            $office_loc = $dbRow->form_data->circle;
                        } else {
                            $office_loc = $zone;
                        }
                    }
                }
                else if($deptt_code == 'WRD' || $deptt_code == 'PWDNH')
                {
                    if($category == 'Class-II') {
                        $office_loc = $dbRow->form_data->circle;
                    } else {
                        $office_loc = 'Head Office';
                    }
                }
                else if($deptt_code == 'GMC')
                {
                    $office_loc = 'Head Office';
                }

                $current_users = array();
                foreach ($userRows as $key => $userRow) {
                    $current_user = array(
                        'login_username' => $userRow->login_username,
                        'email_id' => $userRow->email_id,
                        'mobile_number' => $userRow->mobile_number,
                        'user_level_no' => $userRow->user_levels->level_no,
                        'user_fullname' => $userRow->user_fullname,
                    );
                    $current_users[] = $current_user;
                }

                $validity_date = getFinLastYearDateYMD();
                $rnwDate = $validity_date."T23:59:59.000Z";
                $renewal_date = new MongoDB\BSON\UTCDateTime(strtotime($rnwDate) * 1000);
                $data_to_update = array(
                    'service_data.appl_status' => 'submitted',
                    'service_data.submission_location' => $office_loc,
                    'service_data.district' => "CENTRAL",
                    'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'form_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'current_users' => $current_users,
                    'processing_history' => $processing_history,
                    'form_data.renewal_date' => $renewal_date
                );

                $this->employment_model->update($objId, $data_to_update);

                $smsData = [
                    'applicant_name' => $dbRow->form_data->applicant_name,
                    'mobile' => $dbRow->form_data->mobile,
                    'service_name' => 'Upgradation of Contractors',
                    'submission_date' => format_mongo_date(new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)),
                    'app_ref_no' => $dbRow->service_data->appl_ref_no
                ];
                //sms_provider('submission', $smsData);

                redirect('spservices/registration_of_contractors/acknowledgement/' . $objId);
            } else {
                $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                $this->my_transactions();
            }
        }
    }

    public function view($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
        } else {
            $data["dbrow"] = false;
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/registration_preview_upgr', $data);
        $this->load->view('includes/frontend/footer');
    }

    function getDepttName($deptt_code)
    {
        if ($deptt_code == 'PHED') {
            $departmentName = "Public Health Engineering Department (PHED)";
        } else if ($deptt_code == 'PWDB') {
            $departmentName = "Assam Public Works (Building) Department";
        } else if ($deptt_code == 'WRD') {
            $departmentName = "Water Resource Department";
        } else {
            $departmentName = "DEPTT. NAME NOT AVAILABLE";
        }
        return $departmentName;
    }

    public function unsetdata($objId, $data)
    {
        $this->mongo_db->command(array(
            'update' => 'sp_applications',
            'updates' => [
                array(
                    'q' => array('_id' => new ObjectId($objId)),
                    'u' => array(
                        '$unset' => array($data => ''),
                    )
                ),
            ],
        ));
    }

    public function get_serviceID($deptt, $category)
    {
        $service_id = '';
        if ($deptt == 'PHED') {
            if ($category == 'Class-II') {
                $service_id = 'CON_UPGR_PHED_2';
            } else {
                $service_id = 'CON_UPGR_PHED_1';
            }
        } else if ($deptt == 'PWDB') {
            if ($category == 'Class-II') {
                $service_id = 'CON_UPGR_PWDB_2';
            } else {
                $service_id = 'CON_UPGR_PWDB_1';
            }
        } else if ($deptt == 'WRD') {
            if ($category == 'Class-II') {
                $service_id = 'CON_UPGR_WRD_2';
            } else {
                $service_id = 'CON_UPGR_WRD_1';
            }
        }

        return $service_id;
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

    function getID($length)
    {
        $refID = $this->generateID($length);
        while ($this->employment_model->get_row(["service_data.appl_ref_no" => $refID])) {
            $refID = $this->generateID($length);
        } //End of while()
        return $refID;
    } //End of getID()

    public function generateID($length)
    {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-UPGRCON/" . date('Y') . "/" . $number;
        return $str;
    }

}
