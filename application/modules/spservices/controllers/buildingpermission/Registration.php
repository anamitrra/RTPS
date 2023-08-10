<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps {

    private $serviceName = "Building Permission upto Ground+2 storied under Mukhya Mantrir Sohoj Griha Nirman Achoni";
    private $serviceId = "PPBP";
    private $departmentId = "810";
    private $departmentName = "Department of Housing and Urban Affairs";
    private $secret_key = "s786opq56t7x"; //For UAT
    //private $secret_key = "s789wrt56t7z"; //For Prod

    public function __construct() {
        parent::__construct();
        $this->load->model('buildingpermission/registration_model');
        $this->load->model('buildingpermission/gras_bp_payment_uat_config_model');
        $this->load->helper("cifileupload");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->model('iservices/admin/users_model');
        $this->load->helper('smsprovider');
        $this->load->helper('log');
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        }else{
            $this->slug = "user";
        }
    }//End of __construct()

    // public function get_ref_no() {
    //     $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});

    //     if (isset($user->type_of_kiosk)) {
    //         if ($user->type_of_kiosk == "eDistrict") {
    //             // pre($user->type_of_kiosk);
    //             $this->my_transactions();
    //         }
    //     }
            
       
    //     $this->load->view('includes/frontend/header');
    //     $this->load->view('cancelbuildingpermission/getrefid');
    //     $this->load->view('includes/frontend/footer');
    // } //End of index()

    public function payment_acknowledgement($obj_id=null) {
        $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});

        if (isset($user->type_of_kiosk)) {
            if ($user->type_of_kiosk == "eDistrict") {
                // pre($user->type_of_kiosk);
                $this->my_transactions();
            }
        }

        $filter = array(
            "_id" => $obj_id ? new ObjectId($obj_id) : NULL
        );

        $dbrow = $this->registration_model->get_row($filter);   
        $data['dbrow'] = $dbrow;         
       
        $this->load->view('includes/frontend/header');
        $this->load->view('buildingpermission/ack', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function query_bp_payment_break_down($obj_id=null) {
        $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});

        if (isset($user->type_of_kiosk)) {
            if ($user->type_of_kiosk == "eDistrict") {
                // pre($user->type_of_kiosk);
                $this->my_transactions();
            }
        }

        $filter = array(
            "_id" => $obj_id ? new ObjectId($obj_id) : NULL,
            "service_data.appl_status" => "FRS"
        );

        $dbrow = $this->registration_model->get_row($filter);

        if(!empty($dbrow)){
            $data['obj_id'] = $obj_id;
            $data['type'] = "bp";
            $charge = 0;
            if($dbrow->form_data->frs_request->bpPaymentStatus == "UNPAID"){
                $charge = $dbrow->form_data->frs_request->bpAmount;
                $data['charge'] = $charge;
            }
        }else{
            $this->my_transactions();
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('buildingpermission/query_charge_template', $data);
        $this->load->view('includes/frontend/footer');
    } //End of index()

    public function query_pp_payment_break_down($obj_id=null) {
        $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});

        if (isset($user->type_of_kiosk)) {
            if ($user->type_of_kiosk == "eDistrict") {
                // pre($user->type_of_kiosk);
                $this->my_transactions();
            }
        }

        $filter = array(
            "_id" => $obj_id ? new ObjectId($obj_id) : NULL,
            "service_data.appl_status" => "FRS"
        );

        $dbrow = $this->registration_model->get_row($filter);

        if(!empty($dbrow)){
            $data['obj_id'] = $obj_id;
            $data['type'] = "pp";
            $charge = 0;
            if($dbrow->form_data->frs_request->ppPaymentStatus == "UNPAID"){
                $charge = $dbrow->form_data->frs_request->ppAmount;
                $data['charge'] = $charge;
            }

            if($dbrow->form_data->frs_request->lcPaymentStatus == "UNPAID"){
                $data['lc_charge'] = $dbrow->form_data->frs_request->labourCess;
            }
        }else{
            $this->my_transactions();
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('buildingpermission/query_charge_template', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function cancellation_service_list() {
        $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});

        if (isset($user->type_of_kiosk)) {
            if ($user->type_of_kiosk == "eDistrict") {
                // pre($user->type_of_kiosk);
                $this->my_transactions();
                return;
            }
        }

        $user1=$this->session->userdata();
        $sign_role = "";
        $sign_no = "";
        if(isset($user1['role']) && !empty($user1['role'])){
            if($this->session->userdata('role')->slug == "PFC"){
                $userInfo = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
                $sign_no = $userInfo->email;
                $sign_role = "PFC";
            }
            
            if($this->session->userdata('role')->slug == "CSC"){
                $sign_no = $user1['userId'];
                $sign_role = "CSC";
            }
        }
        else{
            $sign_no = $this->session->userdata("mobile");
            $sign_role = "CTZN";
            if(empty($this->session->userdata("mobile"))){
                $this->session->set_flashdata('error', 'Mobile No doesn\'t exit');
                // $this->my_transactions();
                redirect(base_url('spservices/cancellation-building-permission-service-list'));
                return;
            }
        }

        //Fetch data from local Database
        $filter = array(
            "service_data.service_id" => "PPBP",
            "form_data.mobile" => $this->session->userdata("mobile"),
            "service_data.appl_status" => "D",
            "form_data.app_record_type" => "",
        );
        $db_applications = $this->registration_model->get_rows($filter);

        $user_records = array();
        if(!empty($db_applications)){
            foreach($db_applications as $value){
                $user_record = array(
                    "old_appl_ref_no" => $value->service_data->appl_ref_no,
                    "applicant_name" => $value->form_data->applicant_name,
                    "applicant_mobile" => $value->form_data->mobile 
                );
                $user_records[] = $user_record;
            }
        }

        //Fetch data from ServicePlus API
        $token = "|0VW?z,-w2w\"6;{b8v}K6A5+Fdf@l-";
        $ch = curl_init('https://rtpsprod.assam.statedatacenter.in/tools/rtps_id_labels/src/api/external_apis.php/get_gmda_ref_no?sign_no='.$sign_no.'&sign_role='.$sign_role);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept: application/json",
            'Authorization: Bearer ' . $token
        ));
        $data = curl_exec($ch);
        curl_close($ch);

        if(!empty($data)){
            $data_record = json_decode($data);
            if(isset($data_record->data->applications) && !empty($data_record->data->applications)){
                foreach($data_record->data->applications as $key => $value){
                    $user_record = array(
                        "old_appl_ref_no" => $value->appl_ref_no,
                        "applicant_name" => $value->applicant_name,
                        "applicant_mobile" => $value->mobile_no,
                    );
                    $user_records[] = $user_record;
                }
            }
        }

        // pre($user_records);

        $records['applications_db'] = !empty($user_records)? $user_records: "";
        // $data['applications'] = !empty($user_record)? $user_record: "";
       
        $this->load->view('includes/frontend/header');
        $this->load->view('cancelbuildingpermission/cancellation_service_list', $records);
        $this->load->view('includes/frontend/footer');
    }

    public function index($obj_id=null) {
        
        $data = array("pageTitle" => "Building Permission upto Ground+2 storied under Mukhya Mantrir Sohoj Griha Nirman Achoni");
        $filter = array(
            "_id" => $obj_id ? new ObjectId($obj_id) : NULL,
            "service_data.appl_status" => "DRAFT"
        );

        $data["dbrow"] = $this->registration_model->get_row($filter);
        $data['usser_type'] = $this->slug;

        $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});

        if (isset($user->type_of_kiosk)) {
            if ($user->type_of_kiosk == "eDistrict") {
                // pre($user->type_of_kiosk);
                $this->my_transactions();
            }
        }
        
        // if ((!empty($user->type_of_kiosk)) == "eDistrict"){
        //     pre($user->type_of_kiosk);
        //     $this->my_transactions();
        // }
            
       
        $this->load->view('includes/frontend/header');
        $this->load->view('buildingpermission/buildingpermission',$data);
        $this->load->view('includes/frontend/footer');
    } //End of index()

    public function cancel_form($obj_id=null) {

        $data = array("pageTitle" => "Building Permission upto Ground+2 storied under Mukhya Mantrir Sohoj Griha Nirman Achoni");

        if(!empty($obj_id)){
            //Already in draft mode
            $filter_draft = array(
                "form_data.old_permit_doc_id" => $obj_id ? $obj_id : NULL,
                "service_data.submission_date" =>  ""
            );
            $user_record_draft = $this->registration_model->get_row($filter_draft);

            //From primary application
            $filter_new = array(
                "_id" => $obj_id ? new ObjectId($obj_id) : NULL,
                "service_data.appl_status" => "D"
            );
            $user_record_new = $this->registration_model->get_row($filter_new);

            if (!empty($user_record_draft)) {

                if($user_record_draft->service_data->appl_status == "payment_initiated"){
                    $this->session->set_flashdata('errmessage', $user_record_draft->service_data->appl_ref_no.' Already in payment initiated mode');
                    $this->my_transactions();
                    return;
                }

                $user_record = $user_record_draft;
            }elseif (!empty($user_record_new)) {
                
                //Already in draft mode
                $filter_draft1 = array(
                    "form_data.old_permit_appl_ref_no" => $user_record_new->service_data->appl_ref_no,
                    "service_data.submission_date" =>  ""
                );
                $user_record_draft1 = $this->registration_model->get_row($filter_draft1);

                if(!empty($user_record_draft1)){
                    if($user_record_draft1->service_data->appl_status == "payment_initiated"){
                        $this->session->set_flashdata('errmessage', $user_record_draft1->service_data->appl_ref_no.' Already in payment initiated mode');
                        $this->my_transactions();
                        return;
                    }

                    $user_record = $user_record_draft1;
                } else {
                    $user_record = $user_record_new;
                }

                $appl_ref_no = $this->getID(7);
                $sessionUser = $this->session->userdata();
                                      
                $owner_details = array();
                $owner_detail = $user_record->form_data->owner_details;
                if(count($owner_detail)) {
                    foreach($owner_detail as $key => $value) {

                        $owner_detail1 = array(
                            "owner_name" => $value->owner_name,
                            "owner_gender" => $value->owner_gender
                        );
                        $owner_details[] = $owner_detail1;
                    }//End of foreach()        
                }

                if($this->slug === "CSC"){
                    $apply_by = $sessionUser['userId'];
                }else{
                    $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
                }

                //Generate appl_id
                while(1){
                    $appl_id = rand(100000000, 999999999);
                    $filter = array(
                        "service_data.appl_id" => $appl_id,
                        //"service_data.service_id" => $this->serviceId
                    );
                    $rows = $this->registration_model->get_row($filter);
                    if($rows === false) break;
                }

                $service_data = [
                    "department_id" => $user_record->service_data->department_id,
                    "department_name" => $user_record->service_data->department_name,
                    "service_id" => $user_record->service_data->service_id,
                    "service_name" => $user_record->service_data->service_name,
                    "appl_id" => $appl_id,
                    "appl_ref_no" => $appl_ref_no,
                    "submission_mode" => $user_record->service_data->submission_mode, //kiosk, online, in-person
                    "applied_by" => $apply_by,
                    "submission_location" => "Guwahati Metropolitan Development Authority", // office name
                    "submission_date" => "",
                    "service_timeline" => "30",
                    "appl_status" => "DRAFT",
                    "district" => "KAMRUP METRO",
                ];

                $form_data = [
                    'old_permit_doc_id' => $obj_id,
                    'old_permit_appl_ref_no' => $user_record->service_data->appl_ref_no,
                    'application_type' => $user_record->form_data->application_type,
                    'case_type' => $user_record->form_data->case_type,
                    'ertp' => $user_record->form_data->ertp,
                    'any_old_permission' => $user_record->form_data->any_old_permission,
                    'technical_person_name' => $user_record->form_data->technical_person_name,
                    'old_permission_no' => $user_record->form_data->old_permission_no,
                    'district_emp_tech' => $user_record->form_data->district_emp_tech,
                    'district_emp_tech_name' => $user_record->form_data->district_emp_tech_name,
                    'empanelled_reg_tech_person' => $user_record->form_data->empanelled_reg_tech_person,
                    'empanelled_reg_tech_person_name' => $user_record->form_data->empanelled_reg_tech_person_name,

                    'district' => $user_record->form_data->district,
                    'district_name' => $user_record->form_data->district_name,
                    'house_no' => $user_record->form_data->house_no,
                    'mst_pln_dev_auth' => $user_record->form_data->mst_pln_dev_auth,
                    'mst_pln_dev_auth_name' => $user_record->form_data->mst_pln_dev_auth_name,
                    'name_of_road' => $user_record->form_data->name_of_road,
                    'panchayat_ulb' => $user_record->form_data->panchayat_ulb,
                    'panchayat_ulb_name' => $user_record->form_data->panchayat_ulb_name,
                    'site_pin_code' => $user_record->form_data->site_pin_code,
                    'revenue_village' => $user_record->form_data->revenue_village,
                    'revenue_village_name' => $user_record->form_data->revenue_village_name,
                    'old_dag_no' => $user_record->form_data->old_dag_no,
                    'ward_no' => $user_record->form_data->ward_no,
                    'ward_no_name' => $user_record->form_data->ward_no_name,
                    'new_dag_no' => $user_record->form_data->new_dag_no,
                    'mouza' => $user_record->form_data->mouza,
                    'mouza_name' => $user_record->form_data->mouza_name,
                    'old_patta_no' => $user_record->form_data->old_patta_no,  
                    'new_patta_no' => $user_record->form_data->new_patta_no,
                    
                    'applicant_name' => $user_record->form_data->applicant_name,
                    'applicant_gender' => $user_record->form_data->applicant_gender,
                    'father_name' => $user_record->form_data->father_name,
                    'mother_name' => $user_record->form_data->mother_name,
                    'spouse_name' => $user_record->form_data->spouse_name,
                    'permanent_address' => $user_record->form_data->permanent_address,
                    'pin_code' => $user_record->form_data->pin_code,
                    'mobile' => $sessionUser['mobile'],
                    'monthly_income' => $user_record->form_data->monthly_income,
                    'pan_no' => $user_record->form_data->pan_no,
                    'email' => $user_record->form_data->email,

                    'owner_details' => $owner_details,

                    'user_id' => $sessionUser['userId']->{'$id'},    
                    'user_type' => $this->slug,

                    'service_type' => "PPBP",

                    'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                ];

                $inputs = [
                    'service_data' => $service_data,
                    'form_data' => $form_data
                ];

                $insert = $this->registration_model->insert($inputs);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $filter_new = array(
                        "_id" => $objectId ? new ObjectId($objectId) : NULL
                    );
                    $user_record = $this->registration_model->get_row($filter_new);
                } else {
                    $this->session->set_flashdata('errmessage', 'Unable to make draft of application!!! Please try again.');
                    $this->my_transactions();
                    return;
                }
            } else {
                $this->session->set_flashdata('errmessage', 'No record found');
                $this->my_transactions();
                return;
            }
        }

        if((empty($this->input->get("appl_ref_no"))) && (empty($obj_id))){
            $this->session->set_flashdata('error', 'Application ref. no or ID cannot be empty');
            // $this->my_transactions();
            redirect(base_url('spservices/cancellation-building-permission-service-list'));
            return;
        }

        if(!empty($this->input->get("appl_ref_no"))){

            $obj_id = $this->input->get("appl_ref_no");
            //Already in draft mode
            $filter_draft = array(
                "form_data.old_permit_appl_ref_no" => $obj_id ? $obj_id : NULL,
                "service_data.submission_date" =>  ""
            );
            $user_record_draft = $this->registration_model->get_row($filter_draft);

            //primary service draft to draft mode
            $filter_draft3 = array(
                "service_data.appl_ref_no" => $obj_id ? $obj_id : NULL,
                "service_data.appl_status" => "DRAFT"
            );
            $user_record_draft3 = $this->registration_model->get_row($filter_draft3);

            //Data retrive from primary service
            $filter_new = array(
                "service_data.appl_ref_no" => $obj_id ? $obj_id : NULL,
                "service_data.appl_status" => "D"
                // "service_data.appl_status" => array('$in'=>array("D","DRAFT"))
            );
            $user_record_new = $this->registration_model->get_row($filter_new);

            if (!empty($user_record_draft)) {
                if($user_record_draft->service_data->appl_status == "payment_initiated"){
                    $this->session->set_flashdata('error', $user_record_draft->service_data->appl_ref_no.' Already in payment initiated mode');
                    // $this->my_transactions();
                    redirect(base_url('spservices/cancellation-building-permission-service-list'));
                    return;
                }

                $user_record = $user_record_draft;
            } 
            else if (!empty($user_record_draft3)) {
                $user_record = $user_record_draft3;
            } 
            elseif (!empty($user_record_new)) {
                $user_record = $user_record_new;

                $appl_ref_no = $this->getID(7);
                $sessionUser = $this->session->userdata();
                                      
                $owner_details = array();
                $owner_detail = $user_record->form_data->owner_details;
                if(count($owner_detail)) {
                    foreach($owner_detail as $key => $value) {

                        $owner_detail1 = array(
                            "owner_name" => $value->owner_name,
                            "owner_gender" => $value->owner_gender
                        );
                        $owner_details[] = $owner_detail1;
                    }//End of foreach()        
                }

                if($this->slug === "CSC"){
                    $apply_by = $sessionUser['userId'];
                }else{
                    $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
                }

                //Generate appl_id
                while(1){
                    $appl_id = rand(100000000, 999999999);
                    $filter = array(
                        "service_data.appl_id" => $appl_id,
                    );
                    $rows = $this->registration_model->get_row($filter);
                    if($rows === false) break;
                }

                $service_data = [
                    "department_id" => $user_record->service_data->department_id,
                    "department_name" => $user_record->service_data->department_name,
                    "service_id" => $user_record->service_data->service_id,
                    "service_name" => $user_record->service_data->service_name,
                    "appl_id" => $appl_id,
                    "appl_ref_no" => $appl_ref_no,
                    "submission_mode" => $user_record->service_data->submission_mode, //kiosk, online, in-person
                    "applied_by" => $apply_by,
                    "submission_location" => "Guwahati Metropolitan Development Authority", // office name
                    "submission_date" => "",
                    "service_timeline" => "30",
                    "appl_status" => "DRAFT",
                    "district" => "KAMRUP METRO",
                ];

                $form_data = [
                    'old_permit_doc_id' => "",
                    'old_permit_appl_ref_no' => $user_record->service_data->appl_ref_no,
                    'application_type' => !empty($user_record->form_data->application_type)? $user_record->form_data->application_type: "",
                    'case_type' => !empty($user_record->form_data->case_type)? $user_record->form_data->case_type: "",
                    'ertp' => $user_record->form_data->ertp,
                    'any_old_permission' => !empty($user_record->form_data->any_old_permission)? $user_record->form_data->any_old_permission: "",
                    'technical_person_name' => !empty($user_record->form_data->technical_person_name)? $user_record->form_data->technical_person_name: "",
                    'old_permission_no' => !empty($user_record->form_data->old_permission_no)? $user_record->form_data->old_permission_no: "",
                    'district_emp_tech' => !empty($user_record->form_data->district_emp_tech)? $user_record->form_data->district_emp_tech: "",
                    'district_emp_tech_name' => !empty($user_record->form_data->district_emp_tech_name)? $user_record->form_data->district_emp_tech_name: "",
                    'empanelled_reg_tech_person' => !empty($user_record->form_data->empanelled_reg_tech_person)? $user_record->form_data->empanelled_reg_tech_person: "",
                    'empanelled_reg_tech_person_name' => !empty($user_record->form_data->empanelled_reg_tech_person_name)? $user_record->form_data->empanelled_reg_tech_person_name: "",

                    'district' => !empty($user_record->form_data->district)? $user_record->form_data->district: "",
                    'district_name' => !empty($user_record->form_data->district_name)? $user_record->form_data->district_name: "",
                    'house_no' => !empty($user_record->form_data->house_no)? $user_record->form_data->house_no: "",
                    'mst_pln_dev_auth' => !empty($user_record->form_data->mst_pln_dev_auth)? $user_record->form_data->mst_pln_dev_auth: "",
                    'mst_pln_dev_auth_name' => !empty($user_record->form_data->mst_pln_dev_auth_name)? $user_record->form_data->mst_pln_dev_auth_name: "",
                    'name_of_road' => !empty($user_record->form_data->name_of_road)? $user_record->form_data->name_of_road: "",
                    'panchayat_ulb' => !empty($user_record->form_data->panchayat_ulb)? $user_record->form_data->panchayat_ulb: "",
                    'panchayat_ulb_name' => !empty($user_record->form_data->panchayat_ulb_name)? $user_record->form_data->panchayat_ulb_name: "",
                    'site_pin_code' => !empty($user_record->form_data->site_pin_code)? $user_record->form_data->site_pin_code: "",
                    'revenue_village' => !empty($user_record->form_data->revenue_village)? $user_record->form_data->revenue_village: "",
                    'revenue_village_name' => !empty($user_record->form_data->revenue_village_name)? $user_record->form_data->revenue_village_name: "",
                    'old_dag_no' => !empty($user_record->form_data->old_dag_no)? $user_record->form_data->old_dag_no: "",
                    'ward_no' => !empty($user_record->form_data->ward_no)? $user_record->form_data->ward_no: "",
                    'ward_no_name' => !empty($user_record->form_data->ward_no_name)? $user_record->form_data->ward_no_name: "",
                    'new_dag_no' => !empty($user_record->form_data->new_dag_no)? $user_record->form_data->new_dag_no: "",
                    'mouza' => !empty($user_record->form_data->mouza)? $user_record->form_data->mouza: "",
                    'mouza_name' => !empty($user_record->form_data->mouza_name)? $user_record->form_data->mouza_name: "",
                    'old_patta_no' => !empty($user_record->form_data->old_patta_no)? $user_record->form_data->old_patta_no: "",  
                    'new_patta_no' => !empty($user_record->form_data->new_patta_no)? $user_record->form_data->new_patta_no: "",
                    
                    'applicant_name' => !empty($user_record->form_data->applicant_name)? $user_record->form_data->applicant_name: "",
                    'applicant_gender' => !empty($user_record->form_data->applicant_gender)? $user_record->form_data->applicant_gender: "",
                    'father_name' => !empty($user_record->form_data->father_name)? $user_record->form_data->father_name: "",
                    'mother_name' => !empty($user_record->form_data->mother_name)? $user_record->form_data->mother_name: "",
                    'spouse_name' => !empty($user_record->form_data->spouse_name)? $user_record->form_data->spouse_name: "",
                    'permanent_address' => !empty($user_record->form_data->permanent_address)? $user_record->form_data->permanent_address: "",
                    'pin_code' => !empty($user_record->form_data->pin_code)? $user_record->form_data->pin_code: "",
                    'mobile' => $sessionUser['mobile'],
                    'monthly_income' => !empty($user_record->form_data->monthly_income)? $user_record->form_data->monthly_income: "",
                    'pan_no' => !empty($user_record->form_data->pan_no)? $user_record->form_data->pan_no: "",
                    'email' => !empty($user_record->form_data->email)? $user_record->form_data->email: "",

                    'owner_details' => $owner_details,

                    'user_id' => $sessionUser['userId']->{'$id'},    
                    'user_type' => $this->slug,

                    'service_type' => "PPBP",

                    'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                ];

                $inputs = [
                    'service_data' => $service_data,
                    'form_data' => $form_data
                ];

                $insert = $this->registration_model->insert($inputs);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $filter_new = array(
                        "_id" => $objectId ? new ObjectId($objectId) : NULL
                    );
                    $user_record = $this->registration_model->get_row($filter_new);
                } else {
                    $this->session->set_flashdata('fail', 'Unable to make draft of application!!! Please try again.');
                    // $this->my_transactions();
                    redirect(base_url('spservices/cancellation-building-permission-service-list'));
                    return;
                }
            } else {
                //$obj_id = "RTPS-PPBP/2022/00091";
                // $obj_id = $this->input->post("appl_ref_no");
                $token = "|0VW?z,-w2w\"6;{b8v}K6A5+Fdf@l-";
                $ch = curl_init('https://rtpsprod.assam.statedatacenter.in/tools/rtps_id_labels/src/api/external_apis.php/get_track_data?appl_ref_no='.$obj_id.'&service_id=1886');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Accept: application/json",
                    'Authorization: Bearer ' . $token
                ));
                $data = curl_exec($ch);
                curl_close($ch);

                if(!empty($data)){
                    $user_record = json_decode($data);

                    $filter_draft44 = array(
                        "form_data.old_permit_appl_ref_no" => $user_record->data->initiated_data->appl_ref_no
                    );
                    $user_record_draft44 = $this->registration_model->get_row($filter_draft44);

                    if($user_record_draft44){
                        $this->session->set_flashdata('error', 'You can apply only once');
                        redirect(base_url('spservices/cancellation-building-permission-service-list'));
                        return; 
                    }

                    $objectId = "";
                    if (is_object($user_record->data)){
                        $appl_ref_no = $this->getID(7);
                        $sessionUser = $this->session->userdata();
                                            
                        $owner_details = array();
                        $owner_detail = $user_record->data->initiated_data->attribute_details->ownership_information;
                        if(count($owner_detail) > 0) {
                            foreach($owner_detail as $key => $value) {

                                $owner_detail1 = array(
                                    "owner_name" => !empty($value->owner_name)? $value->owner_name: "",
                                    "owner_gender" => !empty($value->owner_gender)? $value->owner_gender: "",
                                );
                                $owner_details[] = $owner_detail1;
                            }//End of foreach()        
                        }

                        if($this->slug === "CSC"){
                            $apply_by = $sessionUser['userId'];
                        }else{
                            $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
                        }

                        //Generate appl_id
                        while(1){
                            $appl_id = rand(100000000, 999999999);
                            $filter = array(
                                "service_data.appl_id" => $appl_id,
                            );
                            $rows = $this->registration_model->get_row($filter);
                            if($rows === false) break;
                        }

                        //Call district api for ERTP
                        $url = $this->config->item('building_permission_url')."district";
                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                        $response_district = curl_exec($curl);
                        curl_close($curl);

                        $response_district = json_decode($response_district);
                        if(!empty($response_district) && (count($response_district) > 0)){
                            foreach($response_district as $key => $value){
                                if($value->districtName == "KAMRUP (METRO)"){
                                    $district_emp_tech = (string) $value->districtId;
                                    $district_emp_tech_name = $value->districtName;
                                }
                            }
                        } else {
                            $this->session->set_flashdata('error', 'Unable to verify your application! Please try after sometime');
                            // $this->my_transactions();
                            redirect(base_url('spservices/cancellation-building-permission-service-list'));
                            return; 
                        }
                        //End of Call district api for ERTP

                        //Call api for ERTP
                        $url = $this->config->item('building_permission_url')."ertp?districtId=".$district_emp_tech;
                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                        $response_ertp = curl_exec($curl);
                        curl_close($curl);

                        $response_ertp = json_decode($response_ertp);
                        if(!empty($response_ertp) && (count($response_ertp) > 0)){
                            foreach($response_ertp as $key => $value){
                                if($value->rtpName == $user_record->data->initiated_data->attribute_details->empanelled_registered_technical_person){
                                    $empanelled_reg_tech_person = (string) $value->rtpId;
                                    $empanelled_reg_tech_person_name = $value->rtpName;
                                }
                            }
                        } else {
                            $this->session->set_flashdata('error', 'Unable to verify your application! Please try after sometime');
                            // $this->my_transactions();  
                            redirect(base_url('spservices/cancellation-building-permission-service-list'));
                            return; 
                        }
                        //End of Call district api for ERTP

                        $service_data = [
                            "department_id" => $this->departmentId,
                            "department_name" => $this->departmentName,
                            "service_id" => $this->serviceId,
                            "service_name" => $this->serviceName,
                            "appl_id" => $appl_id,
                            "appl_ref_no" => $appl_ref_no,
                            "submission_mode" => "SAVE", //kiosk, online, in-person
                            "applied_by" => $apply_by,
                            "submission_location" => "Guwahati Metropolitan Development Authority", // office name
                            "submission_date" => "",
                            "service_timeline" => "30",
                            "appl_status" => "DRAFT",
                            "district" => "KAMRUP METRO",
                        ];

                        $form_data = [
                            'old_permit_doc_id' => "",
                            'old_permit_appl_ref_no' => $user_record->data->initiated_data->appl_ref_no,
                            'application_type' => !empty($user_record->data->initiated_data->attribute_details->application_type)? $user_record->data->initiated_data->attribute_details->application_type: "",
                            'case_type' => "",
                            'ertp' => !empty($user_record->data->initiated_data->attribute_details->do_you_have_any_technical__person_to_assist_other_than_ertp)? strtolower($user_record->data->initiated_data->attribute_details->do_you_have_any_technical__person_to_assist_other_than_ertp): "",
                            'any_old_permission' => !empty($user_record->data->initiated_data->attribute_details->do_you_have_any_old_permission)? strtolower($user_record->data->initiated_data->attribute_details->do_you_have_any_old_permission): "",
                            'technical_person_name' => !empty($user_record->data->initiated_data->attribute_details->technical_person_name)? $user_record->data->initiated_data->attribute_details->technical_person_name: "",
                            'old_permission_no' => !empty($user_record->data->initiated_data->attribute_details->old_permission_no)? $user_record->data->initiated_data->attribute_details->old_permission_no: "",
                            'district_emp_tech' => !empty($district_emp_tech)? $district_emp_tech: "",
                            'district_emp_tech_name' => !empty($district_emp_tech_name)? $district_emp_tech_name: "",
                            'empanelled_reg_tech_person' => !empty($empanelled_reg_tech_person)? $empanelled_reg_tech_person: "",
                            'empanelled_reg_tech_person_name' => !empty($empanelled_reg_tech_person_name)? $empanelled_reg_tech_person_name: "",

                            'district' => "",
                            'district_name' => "",
                            'house_no' => !empty($user_record->data->initiated_data->attribute_details->house_no_landmark)? $user_record->data->initiated_data->attribute_details->house_no_landmark: "",
                            'mst_pln_dev_auth' => "",
                            'mst_pln_dev_auth_name' => "",
                            'name_of_road' => !empty($user_record->data->initiated_data->attribute_details->name_of_road)? $user_record->data->initiated_data->attribute_details->name_of_road: "",
                            'panchayat_ulb' => "",
                            'panchayat_ulb_name' => "",
                            'site_pin_code' => "",
                            'revenue_village' => "",
                            'revenue_village_name' => "",
                            'old_dag_no' => !empty($user_record->data->initiated_data->attribute_details->dag_no)? $user_record->data->initiated_data->attribute_details->dag_no: "",
                            'ward_no' => "",
                            'ward_no_name' => "",
                            'new_dag_no' => !empty($user_record->data->initiated_data->attribute_details->new_dag_no)? $user_record->data->initiated_data->attribute_details->new_dag_no: "",
                            'mouza' => "",
                            'mouza_name' => "",
                            'old_patta_no' => !empty($user_record->data->initiated_data->attribute_details->patta_no)? $user_record->data->initiated_data->attribute_details->patta_no: "",  
                            'new_patta_no' => !empty($user_record->data->initiated_data->attribute_details->new_patta_no)? $user_record->data->initiated_data->attribute_details->new_patta_no: "",
                            
                            'applicant_name' => !empty($user_record->data->initiated_data->attribute_details->applicant_name)? $user_record->data->initiated_data->attribute_details->applicant_name: "",
                            'applicant_gender' => "",
                            'father_name' => !empty($user_record->data->initiated_data->attribute_details->fathers_name)? $user_record->data->initiated_data->attribute_details->fathers_name: "",
                            'mother_name' => !empty($user_record->data->initiated_data->attribute_details->mother_name)? $user_record->data->initiated_data->attribute_details->mother_name: "",
                            'spouse_name' => !empty($user_record->data->initiated_data->attribute_details->spouse_name)? $user_record->data->initiated_data->attribute_details->spouse_name: "",
                            'permanent_address' => !empty($user_record->data->initiated_data->attribute_details->permanent_address)? $user_record->data->initiated_data->attribute_details->permanent_address: "",
                            'pin_code' => !empty($user_record->data->initiated_data->attribute_details->pin_code)? $user_record->data->initiated_data->attribute_details->pin_code: "",
                            'mobile' => isset($sessionUser['mobile'])? $sessionUser['mobile']: "",
                            'monthly_income' => "",
                            'pan_no' => !empty($user_record->data->initiated_data->attribute_details->pan_number)? $user_record->data->initiated_data->attribute_details->pan_number: "",
                            'email' => "",

                            'owner_details' => $owner_details,

                            'user_id' => $sessionUser['userId']->{'$id'},    
                            'user_type' => $this->slug,

                            'service_type' => "PPBP",

                            'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                        ];

                        $inputs = [
                            'service_data' => $service_data,
                            'form_data' => $form_data
                        ];

                        $insert = $this->registration_model->insert($inputs);
                        if ($insert) {
                            $objectId = $insert['_id']->{'$id'};
                            // $filter_new = array(
                            //     "_id" => $objectId ? new ObjectId($objectId) : NULL
                            // );
                            // $user_record = $this->registration_model->get_row($filter_new);
                            $this->index($objectId);
                            return;
                        } else {
                            $this->session->set_flashdata('fail', 'Unable to make draft of application!!! Please try again.');
                            // $this->my_transactions();
                            redirect(base_url('spservices/cancellation-building-permission-service-list'));
                            return; 
                        }
                    } else {
                        $this->session->set_flashdata('error', 'No record found');
                        // $this->my_transactions();   
                        redirect(base_url('spservices/cancellation-building-permission-service-list'));
                        return; 
                    }
                } else {
                    $this->session->set_flashdata('error', 'No record found');
                    // $this->my_transactions();
                    redirect(base_url('spservices/cancellation-building-permission-service-list'));
                    return; 
                }
            }
        }

        $data['dbrow'] = $user_record;
        $data['usser_type'] = $this->slug;

        $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
        //pre($this->session->userdata("userId")->{'$id'});
        if (isset($user->type_of_kiosk)){
            if($user->type_of_kiosk == "eDistrict"){
                $this->my_transactions();
            }
        }
            
  
        $this->load->view('includes/frontend/header');
        $this->load->view('cancelbuildingpermission/buildingpermission',$data);
        $this->load->view('includes/frontend/footer');
    } //End of index()

    public function submit() {
        //pre($this->input->post("old_permit_no"));
        $objId = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");

        if($objId){
            $filter = array(
                "_id" => $objId ? new ObjectId($objId) : NULL,
                "service_data.appl_status" => "DRAFT"
            );
    
            $dbrow = $this->registration_model->get_row($filter);
        }

        // if ((($this->input->post('panchayat_ulb') !== null)) &&  ($this->input->post('panchayat_ulb') == 0)) {
        //     if ((($this->input->post('ward_no') !== null)) &&  ($this->input->post('ward_no') == 0)) {
        //         $this->session->set_flashdata('fail', 'You cann\'t only select Ward and Panchayat as Not Applicable at same time');
        //         $this->index();
        //         return;
        //     }
        // }

        // if ((($this->input->post('panchayat_ulb') !== null)) &&  ($this->input->post('panchayat_ulb') != 0)) {
        //     if ((($this->input->post('ward_no') !== null)) &&  ($this->input->post('ward_no') != 0)) {
        //         $this->session->set_flashdata('fail', 'You cann\'t only select ward no as Not Applicable');
        //         $this->index();
        //         return;
        //     }
        // }

        $this->form_validation->set_rules('application_type', 'Application Type', 'trim|required|xss_clean|strip_tags|max_length[30]');
        $this->form_validation->set_rules('case_type', 'Case Type', 'trim|required|xss_clean|strip_tags|max_length[30]');
        $this->form_validation->set_rules('house_no', 'House no. should be alpha-numeric', 'trim|xss_clean|strip_tags|regex_match[/^[ 0-9a-zA-Z]*$/]');

        if (!isset($dbrow->form_data->old_permit_appl_ref_no)) {
            $this->form_validation->set_rules('district_emp_tech', 'District for Empanelled Registered Technical Person', 'trim|required|xss_clean|strip_tags|max_length[30]'); 
            $this->form_validation->set_rules('district_emp_tech_name', 'District Name for Empanelled Registered Technical Person', 'trim|required|xss_clean|strip_tags|max_length[200]'); 
            $this->form_validation->set_rules('empanelled_reg_tech_person', 'Empanelled Registered Technical Person', 'trim|required|xss_clean|strip_tags|max_length[30]'); 
            $this->form_validation->set_rules('empanelled_reg_tech_person_name', 'Empanelled Registered Technical Person Name', 'trim|required|xss_clean|strip_tags|max_length[200]'); 
        }
        
        if ((!empty($this->input->post('ertp'))) &&  ($this->input->post('ertp') == "yes")) {
            $this->form_validation->set_rules('technical_person_name', 'Technical Person Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[200]');

            $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('district_name', 'District Name', 'trim|required|xss_clean|strip_tags|max_length[200]');
            // $this->form_validation->set_rules('house_no', 'House No', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('mst_pln_dev_auth', 'Master Plan/Development Authority', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('mst_pln_dev_auth_name', 'Master Plan/Development Authority Name', 'trim|required|xss_clean|strip_tags|max_length[200]');
            $this->form_validation->set_rules('name_of_road', 'Road name should be alpha-numeric', 'trim|required|xss_clean|strip_tags|max_length[200]|regex_match[/^[ 0-9a-zA-Z.,]*$/]');
            $this->form_validation->set_rules('panchayat_ulb', 'ULB/Panchayat', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('panchayat_ulb_name', 'ULB/Panchayat Name', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('site_pin_code', 'Site Pincode is not valid', 'trim|required|xss_clean|strip_tags|min_length[6]|max_length[6]|regex_match[/^(([7][8][0-9][0-9][0-9][0-9]){0,1})/]');
            $this->form_validation->set_rules('revenue_village', 'Revenue Village', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('revenue_village_name', 'Revenue Village Name', 'trim|required|xss_clean|strip_tags|max_length[200]');
            $this->form_validation->set_rules('old_dag_no', 'Old Dag No', 'trim|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('ward_no', 'Ward No', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('ward_no_name', 'Ward No Name', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('new_dag_no', 'New Dag No', 'trim|required|xss_clean|strip_tags|max_length[30]');
            // $this->form_validation->set_rules('old_patta_no', 'Old Patta No', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('new_patta_no', 'New Patta No', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('mouza', 'Mouza', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('mouza_name', 'Mouza Name', 'trim|required|xss_clean|strip_tags|max_length[30]');
        }
        if ((!empty($this->input->post('case_type'))) &&  ($this->input->post('case_type') == "2")) {
            $this->form_validation->set_rules('any_old_permission', 'Any Old Permission Selection', 'trim|required|xss_clean|strip_tags|max_length[30]');  
        }
        if ((!empty($this->input->post('any_old_permission'))) &&  ($this->input->post('any_old_permission') == "yes")) {
            $this->form_validation->set_rules('old_permission_no', 'Old Permission is required', 'trim|required|xss_clean|strip_tags|max_length[30]');  
        }

        $this->form_validation->set_rules('applicant_name', 'Applicant Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('applicant_gender', 'Applicant Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('father_name', 'Father Name', 'trim|required|regex_match[/^[a-zA-Z ]*$/]|xss_clean|strip_tags|max_length[200]');
        $this->form_validation->set_rules('mother_name', 'Mother Name', 'trim|required|regex_match[/^[a-zA-Z ]*$/]|xss_clean|strip_tags|max_length[200]');
        $this->form_validation->set_rules('spouse_name', 'Spouse Name', 'trim|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[200]');
        $this->form_validation->set_rules('permanent_address', 'Permanent Address', 'trim|required|xss_clean|strip_tags|max_length[200]|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('mobile', 'Mobile No', 'trim|required|xss_clean|strip_tags|max_length[10]|numeric');
        $this->form_validation->set_rules('pin_code', 'PIN Code', 'trim|required|xss_clean|strip_tags|min_length[6]|max_length[6]');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|strip_tags|valid_email');
        $this->form_validation->set_rules('pan_no', 'PAN number is alpha-numeric', 'trim|required|xss_clean|strip_tags|max_length[10]|regex_match[/^[a-zA-Z0-9]{10}/]');
        $this->form_validation->set_rules('monthly_income', 'Monthly Income', 'trim|required|xss_clean|strip_tags|max_length[10]');

        $this->form_validation->set_rules('owner_name[]', 'Owner name is mandatory and contains alphabets only', 'trim|required|xss_clean|strip_tags|max_length[100]|regex_match[/^[ a-zA-Z]+$/]');
        $this->form_validation->set_rules('owner_gender[]', 'Owner Genders', 'trim|required|xss_clean|strip_tags|max_length[100]');
        
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
        } else {
            if (!empty($this->input->post('empanelled_reg_tech_person'))) {
                $url = $this->config->item('building_permission_url')."ertp-data?rtpId=".$this->input->post('empanelled_reg_tech_person')."";
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                curl_close($curl);

                if (!empty($response)) {
                    $response = json_decode($response);
                    if ($response->isActive === 'N') {
                        $this->session->set_flashdata('error', 'Selected ERTP is under suspension for 6 months ');
                        $obj_id = strlen($objId) ? $objId : null;
                        $this->index($obj_id);
                        return;
                    }
                }else{
                    $this->session->set_flashdata('error', 'No ERTP found against uploaded ERTP No.');
                    $obj_id = strlen($objId) ? $objId : null;
                    $this->index($obj_id);
                    return;
                }
            }

            $appl_ref_no = $this->getID(7);
            $sessionUser = $this->session->userdata();

            $owner_name =  $this->input->post("owner_name");
            $owner_gender =  $this->input->post("owner_gender");                       
            $owner_details = array();
            if(count($owner_name)) {
                foreach($owner_name as $k=>$on) {

                    $owner_detail = array(
                        "owner_name" => $owner_name[$k],
                        "owner_gender" => $owner_gender[$k]
                    );
                    $owner_details[] = $owner_detail;
                }//End of foreach()        
            }

            if($this->slug === "CSC"){
                $apply_by = $sessionUser['userId'];
            }else{
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            }

            //Generate appl_id
            while(1){
                $appl_id = rand(100000000, 999999999);
                $filter = array(
                    "service_data.appl_id" => $appl_id,
                    //"service_data.service_id" => $this->serviceId
                );
                $rows = $this->registration_model->get_row($filter);
                if($rows === false) break;
            }

            $service_data = [
                "department_id" => $this->departmentId,
                "department_name" => $this->departmentName,
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $appl_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => "Guwahati Metropolitan Development Authority", // office name
                "submission_date" => "",
                "service_timeline" => "30",
                "appl_status" => "DRAFT",
                "district" => "KAMRUP METRO",
            ];

            $form_data = [
                'application_type' => $this->input->post("application_type"),
                'case_type' => $this->input->post("case_type"),
                'ertp' => $this->input->post("ertp"),
                'any_old_permission' => $this->input->post("any_old_permission"),
                'technical_person_name' => $this->input->post("technical_person_name"),
                'old_permission_no' => $this->input->post("old_permission_no"),
                // 'district_emp_tech' => $this->input->post("district_emp_tech"),
                // 'district_emp_tech_name' => $this->input->post("district_emp_tech_name"),
                // 'empanelled_reg_tech_person' => $this->input->post("empanelled_reg_tech_person"),
                // 'empanelled_reg_tech_person_name' => $this->input->post("empanelled_reg_tech_person_name"),

                'district' => $this->input->post("district"),
                'district_name' => $this->input->post("district_name"),
                'house_no' => $this->input->post("house_no"),
                'mst_pln_dev_auth' => $this->input->post("mst_pln_dev_auth"),
                'mst_pln_dev_auth_name' => $this->input->post("mst_pln_dev_auth_name"),
                'name_of_road' => $this->input->post("name_of_road"),
                'panchayat_ulb' => $this->input->post("panchayat_ulb"),
                'panchayat_ulb_name' => $this->input->post("panchayat_ulb_name"),
                'site_pin_code' => $this->input->post("site_pin_code"),
                'revenue_village' => $this->input->post("revenue_village"),
                'revenue_village_name' => $this->input->post("revenue_village_name"),
                'old_dag_no' => $this->input->post("old_dag_no"),
                'ward_no' => $this->input->post("ward_no"),
                'ward_no_name' => $this->input->post("ward_no_name"),
                'new_dag_no' => $this->input->post("new_dag_no"),
                'mouza' => $this->input->post("mouza"),
                'mouza_name' => $this->input->post("mouza_name"),
                'old_patta_no' => $this->input->post("old_patta_no"),  
                'new_patta_no' => $this->input->post("new_patta_no"),
                
                'applicant_name' => $this->input->post("applicant_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'father_name' => $this->input->post("father_name"),
                'mother_name' => $this->input->post("mother_name"),
                'spouse_name' => $this->input->post("spouse_name"),
                'permanent_address' => $this->input->post("permanent_address"),
                'pin_code' => $this->input->post("pin_code"),
                'mobile' => $this->input->post("mobile"),
                'monthly_income' => $this->input->post("monthly_income"),
                'pan_no' => $this->input->post("pan_no"),
                'email' => $this->input->post("email"),

                'owner_details' => $owner_details,

                'user_id' => $sessionUser['userId']->{'$id'},    
                'user_type' => $this->slug,

                'app_record_type' => "",

                'service_type' => "PPBP",

                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];

            if (!isset($dbrow->form_data->old_permit_appl_ref_no)) {
                $form_data["district_emp_tech"] = $this->input->post("district_emp_tech");
                $form_data["district_emp_tech_name"] = $this->input->post("district_emp_tech_name");
                $form_data["empanelled_reg_tech_person"] = $this->input->post("empanelled_reg_tech_person");
                $form_data["empanelled_reg_tech_person_name"] = $this->input->post("empanelled_reg_tech_person_name");
            }else {
                $form_data["district_emp_tech"] = $dbrow->form_data->district_emp_tech;
                $form_data["district_emp_tech_name"] = $dbrow->form_data->district_emp_tech_name;
                $form_data["empanelled_reg_tech_person"] = $dbrow->form_data->empanelled_reg_tech_person;
                $form_data["empanelled_reg_tech_person_name"] = $dbrow->form_data->empanelled_reg_tech_person_name;
            }
            
            if(strlen($objId)) {

                $filter = array(
                    "_id" => $objId ? new ObjectId($objId) : NULL
                );
                $data11 = $this->registration_model->get_row($filter);

                $form_data["old_permit_doc_id"] = $data11->form_data->old_permit_doc_id;
                $form_data["old_permit_appl_ref_no"] = $data11->form_data->old_permit_appl_ref_no;
                $form_data["technical_person_document_type"] = $this->input->post("technical_person_document_type");
                $form_data["technical_person_document"] = $this->input->post("technical_person_document");
                $form_data["old_permission_copy_type"] = $this->input->post("old_permission_copy_type");
                $form_data["old_permission_copy"] = $this->input->post("old_permission_copy");
                $form_data["old_drawing_type"] = $this->input->post("old_drawing_type");
                $form_data["old_drawing"] = $this->input->post("old_drawing");
                
                $form_data["soft_copy_type"] = $this->input->post("soft_copy_type");
                $form_data["soft_copy"] = $this->input->post("soft_copy");

                if (!empty($this->input->post("drawing_type"))) {
                    $form_data["drawing_type"] = $this->input->post("drawing_type");
                    $form_data["drawing"] = $this->input->post("drawing");
                }
                if (!empty($this->input->post("trace_map_type"))) {
                    $form_data["trace_map_type"] = $this->input->post("trace_map_type");
                    $form_data["trace_map"] = $this->input->post("trace_map");
                }
                if (!empty($this->input->post("key_plan_type"))) {
                    $form_data["key_plan_type"] = $this->input->post("key_plan_type");
                    $form_data["key_plan"] = $this->input->post("key_plan");
                }
                if (!empty($this->input->post("site_plan_type"))) {
                    $form_data["site_plan_type"] = $this->input->post("site_plan_type");
                    $form_data["site_plan"] = $this->input->post("site_plan");
                }
                if (!empty($this->input->post("building_plan_type"))) {
                    $form_data["building_plan_type"] = $this->input->post("building_plan_type");
                    $form_data["building_plan"] = $this->input->post("building_plan");
                }
                if (!empty($this->input->post("certificate_of_supervision_type"))) {
                    $form_data["certificate_of_supervision_type"] = $this->input->post("certificate_of_supervision_type");
                    $form_data["certificate_of_supervision"] = $this->input->post("certificate_of_supervision");
                }
                if (!empty($this->input->post("area_statement_type"))) {
                    $form_data["area_statement_type"] = $this->input->post("area_statement_type");
                    $form_data["area_statement"] = $this->input->post("area_statement");
                }
                if (!empty($this->input->post("amended_byelaws_type"))) {
                    $form_data["amended_byelaws_type"] = $this->input->post("amended_byelaws_type");
                    $form_data["amended_byelaws"] = $this->input->post("amended_byelaws");
                }
                if (!empty($this->input->post("form_no_six_type"))) {
                    $form_data["form_no_six_type"] = $this->input->post("form_no_six_type");
                    $form_data["form_no_six"] = $this->input->post("form_no_six");
                }
                if (!empty($this->input->post("indemnity_bond_type"))) {
                    $form_data["indemnity_bond_type"] = $this->input->post("indemnity_bond_type");
                    $form_data["indemnity_bond"] = $this->input->post("indemnity_bond");
                }
                if (!empty($this->input->post("undertaking_signed_type"))) {
                    $form_data["undertaking_signed_type"] = $this->input->post("undertaking_signed_type");
                    $form_data["undertaking_signed"] = $this->input->post("undertaking_signed");
                }
                if (!empty($this->input->post("party_applicant_form_type"))) {
                    $form_data["party_applicant_form_type"] = $this->input->post("party_applicant_form_type");
                    $form_data["party_applicant_form"] = $this->input->post("party_applicant_form");
                }
                if (!empty($this->input->post("date_property_tax_type"))) {
                    $form_data["date_property_tax_type"] = $this->input->post("date_property_tax_type");
                    $form_data["date_property_tax"] = $this->input->post("date_property_tax");
                }
                if (!empty($this->input->post("service_plan_type"))) {
                    $form_data["service_plan_type"] = $this->input->post("service_plan_type");
                    $form_data["service_plan"] = $this->input->post("service_plan");
                }
                if (!empty($this->input->post("parking_plan_type"))) {
                    $form_data["parking_plan_type"] = $this->input->post("parking_plan_type");
                    $form_data["parking_plan"] = $this->input->post("parking_plan");
                }
                if (!empty($this->input->post("ownership_document_of_land_type"))) {
                    $form_data["ownership_document_of_land_type"] = $this->input->post("ownership_document_of_land_type");
                    $form_data["ownership_document_of_land"] = $this->input->post("ownership_document_of_land");
                }
                if (!empty($this->input->post("any_other_document_type"))) {
                    $form_data["any_other_document_type"] = $this->input->post("any_other_document_type");
                    $form_data["any_other_document"] = $this->input->post("any_other_document");
                }
                if (!empty($this->input->post("construction_estimate_type"))) {
                    $form_data["construction_estimate_type"] = $this->input->post("construction_estimate_type");
                    $form_data["construction_estimate"] = $this->input->post("construction_estimate");
                }
            }

            $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data
            ];
            //pre($inputs);
           // if ($sessCaptcha !== $inputCaptcha) {
              //  $this->session->set_flashdata("error", "Captcha does not mached!. Please try again");
               // $this->index();
          //  } else {
                if (strlen($objId)) {
                    $this->registration_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    $filter = array(
                        "_id" => $objId ? new ObjectId($objId) : NULL
                    );
            
                    $appData = $this->registration_model->get_row($filter);
                    // pre($appData);
                    if(($appData->form_data->ertp === 'yes') || (($appData->form_data->case_type === '2') && ($appData->form_data->any_old_permission === 'yes')))
                        redirect('spservices/buildingpermission/registration/fileuploads/'. $objId);
                    else
                        redirect('spservices/buildingpermission/registration/preview/'.$objId);
                    exit();
                } else {
                    $insert = $this->registration_model->insert($inputs);
                    if ($insert) {
                        $objectId = $insert['_id']->{'$id'};
                        $this->session->set_flashdata('success', 'Your application has been successfully submitted');

                        $filter = array(
                            "_id" => $objectId ? new ObjectId($objectId) : NULL
                        );
                
                        $appData = $this->registration_model->get_row($filter);
                        // pre($appData);
                        if(($appData->form_data->ertp === 'yes') || (($appData->form_data->case_type === '2') && ($appData->form_data->any_old_permission === 'yes')))
                            redirect('spservices/buildingpermission/registration/fileuploads/'. $objectId);
                        else
                            redirect('spservices/buildingpermission/registration/preview/'.$objectId);
                        exit();
                    } else {
                        $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                        $this->index();
                    } //End of if else
                } //End of if else
           // } //End of if else 
        } //End of if else
    } //End of submit() 

    public function fileuploads($objId = null){
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "pageTitle" => "Attached Enclosures for ".$this->serviceName,
                "obj_id"=>$objId,               
                "dbrow" => $dbRow
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('buildingpermission/buildingpermissionuploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/buildingpermission/registration');
        } //End of if else
    } //End of fileuploads()

    public function finalsubmition($obj = null){
        $obj = $this->input->post("obj");
        if ($obj) {
            $dbRow = $this->registration_model->get_by_doc_id(new ObjectId($obj));

            if ($dbRow->service_data->appl_status == "submitted") {
                $this->my_transactions();
            }

                //procesing data
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $owners = array();
                if(count($dbRow->form_data->owner_details)) {
                    foreach($dbRow->form_data->owner_details as $key => $owner_detail) {
    
                        $owner_detail = array(
                            "ownerName" => $owner_detail->owner_name,
                            "gender" => $owner_detail->owner_gender,
                        );
                        $owners[] = $owner_detail;
                    }//End of foreach()        
                }//End of if

                $recordType = "";
                if((isset($dbRow->form_data->old_permit_appl_ref_no)) && (!empty($dbRow->form_data->old_permit_appl_ref_no))){
                    $recordType = "REAPPLY";
                } else {
                    $recordType = "FRESH";
                }

                $postdata = array(
                    "applicationRefNo" => $dbRow->service_data->appl_ref_no,
                    "rtpId" => $dbRow->form_data->empanelled_reg_tech_person,
                    "applicationTypeId" => $dbRow->form_data->application_type,
                    "caseTypeId" => $dbRow->form_data->case_type,
                    "hasAssistantArchitect" => $dbRow->form_data->ertp,
                    "architectOnRecord" => $dbRow->form_data->technical_person_name,
                    "hasOldPermission" => $dbRow->form_data->any_old_permission,
                    "oldPermissionNo" => $dbRow->form_data->old_permission_no,
                    "villageId" => $dbRow->form_data->revenue_village,
                    "mouzaId" => $dbRow->form_data->mouza,
                    "incomeId" => $dbRow->form_data->monthly_income,
                    "fatherName" => $dbRow->form_data->father_name,
                    "motherName" => $dbRow->form_data->mother_name,
                    "spouseName" => $dbRow->form_data->spouse_name,
                    "panNo" => $dbRow->form_data->pan_no,
                    "owners" => $owners,
                    "wardId" => $dbRow->form_data->ward_no,
                    "houseNo" => $dbRow->form_data->house_no,
                    "roadName" => $dbRow->form_data->name_of_road,
                    "dagNo" => $dbRow->form_data->old_dag_no,
                    "newDagNo" => $dbRow->form_data->new_dag_no,
                    "sitePinCode" => $dbRow->form_data->site_pin_code,
                    "pattaNo" => $dbRow->form_data->old_patta_no,
                    "newPattaNo" => $dbRow->form_data->new_patta_no,
                    "applicantName" => $dbRow->form_data->applicant_name,
                    "mobileNo" => $dbRow->form_data->mobile,
                    "permanentAddress" => $dbRow->form_data->permanent_address,
                    "email" => $dbRow->form_data->email,
                    "pinCode" => $dbRow->form_data->pin_code,
                    "districtId" => $dbRow->form_data->district,
                    "developmentAuthorityId" => $dbRow->form_data->mst_pln_dev_auth,
                    "ulbPanchayatId" => $dbRow->form_data->panchayat_ulb,

                    "recordType" => $recordType,
                    
                    'spId' => array('applId' => $dbRow->service_data->appl_id)
                );

                if(!empty($dbRow->form_data->technical_person_document)){
                    $technical_person_document = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->technical_person_document));

                    $postdata['architectQualificationDoc'] = $technical_person_document;
                }

                if(!empty($dbRow->form_data->old_permission_copy)){
                    $old_permission_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->old_permission_copy));

                    $postdata['oldPermissionDoc'] = $old_permission_copy;
                }

                if(!empty($dbRow->form_data->old_drawing)){
                    $old_drawing = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->old_drawing));

                    $postdata['oldDrawingDoc'] = $old_drawing;
                }

                if(!empty($dbRow->form_data->drawing)){
                    $drawing = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->drawing));

                    $attachment_one = array(
                        "encl" =>  $drawing,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->drawing_type,
                        "enclType" => $dbRow->form_data->drawing_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentOne'] = $attachment_one;
                }

                if(!empty($dbRow->form_data->trace_map)){
                    $trace_map = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->trace_map));

                    $attachment_two = array(
                        "encl" =>  $trace_map,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->trace_map_type,
                        "enclType" => $dbRow->form_data->trace_map_type,
                        "id" => "93964",
                        "doctypecode" => "8257",
                        "docRefId" => "8258",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentTwo'] = $attachment_two;
                }

                if(!empty($dbRow->form_data->key_plan)){
                    $key_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->key_plan));

                    $attachment_three = array(
                        "encl" =>  $key_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->key_plan_type,
                        "enclType" => $dbRow->form_data->key_plan_type,
                        "id" => "93965",
                        "doctypecode" => "8259",
                        "docRefId" => "8260",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentThree'] = $attachment_three;
                }

                if(!empty($dbRow->form_data->site_plan)){
                    $site_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->site_plan));

                    $attachment_four = array(
                        "encl" =>  $site_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->site_plan_type,
                        "enclType" => $dbRow->form_data->site_plan_type,
                        "id" => "93966",
                        "doctypecode" => "8290",
                        "docRefId" => "8261",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentFour'] = $attachment_four;
                }

                if(!empty($dbRow->form_data->building_plan)){
                    $building_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->building_plan));

                    $attachment_five = array(
                        "encl" =>  $building_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->building_plan_type,
                        "enclType" => $dbRow->form_data->building_plan_type,
                        "id" => "93966",
                        "doctypecode" => "8290",
                        "docRefId" => "8261",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentFive'] = $attachment_five;
                }

                if(!empty($dbRow->form_data->certificate_of_supervision)){
                    $certificate_of_supervision = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->certificate_of_supervision));

                    $attachment_six = array(
                        "encl" =>  $certificate_of_supervision,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->certificate_of_supervision_type,
                        "enclType" => $dbRow->form_data->certificate_of_supervision_type,
                        "id" => "93968",
                        "doctypecode" => "8265",
                        "docRefId" => "8292",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentSix'] = $attachment_six;
                }

                if(!empty($dbRow->form_data->area_statement)){
                    $area_statement = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->area_statement));

                    $attachment_seven = array(
                        "encl" =>  $area_statement,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->area_statement_type,
                        "enclType" => $dbRow->form_data->area_statement_type,
                        "id" => "93969",
                        "doctypecode" => "8255",
                        "docRefId" => "8256",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentSeven'] = $attachment_seven;
                }

                if(!empty($dbRow->form_data->amended_byelaws)){
                    $amended_byelaws = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->amended_byelaws));

                    $attachment_eight = array(
                        "encl" =>  $amended_byelaws,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->amended_byelaws_type,
                        "enclType" => $dbRow->form_data->amended_byelaws_type,
                        "id" => "93969",
                        "doctypecode" => "8255",
                        "docRefId" => "8256",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentEight'] = $attachment_eight;
                }

                if(!empty($dbRow->form_data->form_no_six)){
                    $form_no_six = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->form_no_six));

                    $attachment_nine = array(
                        "encl" =>  $form_no_six,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->form_no_six_type,
                        "enclType" => $dbRow->form_data->form_no_six_type,
                        "id" => "93971",
                        "doctypecode" => "8268",
                        "docRefId" => "8269",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentNine'] = $attachment_nine;
                }

                if(!empty($dbRow->form_data->indemnity_bond)){
                    $indemnity_bond = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->indemnity_bond));

                    $attachment_nine = array(
                        "encl" =>  $indemnity_bond,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->indemnity_bond_type,
                        "enclType" => $dbRow->form_data->indemnity_bond_type,
                        "id" => "93972",
                        "doctypecode" => "8270",
                        "docRefId" => "8271",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentTen'] = $attachment_nine;
                }

                if(!empty($dbRow->form_data->undertaking_signed)){
                    $undertaking_signed = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->undertaking_signed));

                    $attachment_eleven = array(
                        "encl" =>  $undertaking_signed,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->undertaking_signed_type,
                        "enclType" => $dbRow->form_data->undertaking_signed_type,
                        "id" => "93972",
                        "doctypecode" => "8270",
                        "docRefId" => "8271",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentEleven'] = $attachment_eleven;
                }

                if(!empty($dbRow->form_data->party_applicant_form)){
                    $party_applicant_form = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->party_applicant_form));

                    $attachment_twelve = array(
                        "encl" =>  $party_applicant_form,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->party_applicant_form_type,
                        "enclType" => $dbRow->form_data->party_applicant_form_type,
                        "id" => "93974",
                        "doctypecode" => "8288",
                        "docRefId" => "8289",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentTwelve'] = $attachment_twelve;
                }

                if(!empty($dbRow->form_data->date_property_tax)){
                    $date_property_tax = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->date_property_tax));

                    $attachment_thirteen = array(
                        "encl" =>  $date_property_tax,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->date_property_tax_type,
                        "enclType" => $dbRow->form_data->date_property_tax_type,
                        "id" => "93975",
                        "doctypecode" => "8275",
                        "docRefId" => "8276",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentThirteen'] = $attachment_thirteen;
                }

                if(!empty($dbRow->form_data->service_plan)){
                    $service_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->service_plan));

                    $attachment_fourteen = array(
                        "encl" =>  $service_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->service_plan_type,
                        "enclType" => $dbRow->form_data->service_plan_type,
                        "id" => "93976",
                        "doctypecode" => "8278",
                        "docRefId" => "8279",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentFourteen'] = $attachment_fourteen;
                }

                if(!empty($dbRow->form_data->parking_plan)){
                    $parking_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->parking_plan));

                    $attachment_fifteen = array(
                        "encl" =>  $parking_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->parking_plan_type,
                        "enclType" => $dbRow->form_data->parking_plan_type,
                        "id" => "93977",
                        "doctypecode" => "8280",
                        "docRefId" => "8281",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentFifteen'] = $attachment_fifteen;
                }

                if(!empty($dbRow->form_data->ownership_document_of_land)){
                    $ownership_document_of_land = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->ownership_document_of_land));

                    $attachment_sixteen = array(
                        "encl" =>  $ownership_document_of_land,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->ownership_document_of_land_type,
                        "enclType" => $dbRow->form_data->ownership_document_of_land_type,
                        "id" => "93978",
                        "doctypecode" => "8282",
                        "docRefId" => "8283",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentSixteen'] = $attachment_sixteen;
                }

                if(!empty($dbRow->form_data->any_other_document)){
                    $any_other_document = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->any_other_document));

                    $attachment_seventeen = array(
                        "encl" =>  $any_other_document,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->any_other_document_type,
                        "enclType" => $dbRow->form_data->any_other_document_type,
                        "id" => "93978",
                        "doctypecode" => "8282",
                        "docRefId" => "8283",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentSeventeen'] = $attachment_seventeen;
                }

                if(!empty($dbRow->form_data->construction_estimate)){
                    $construction_estimate = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->construction_estimate));

                    $attachment_eighteen = array(
                        "encl" =>  $construction_estimate,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->construction_estimate_type,
                        "enclType" => $dbRow->form_data->construction_estimate_type,
                        "id" => "93980",
                        "doctypecode" => "8286",
                        "docRefId" => "8287",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentEighteen'] = $attachment_eighteen;
                }

                // if(!empty($dbRow->form_data->soft_copy)){
                //     $soft_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->soft_copy));

                //     $attachment_six = array(
                //         "encl" => $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload the Soft  Copy of Application Form",
                //         "enclType" => "Upload the Soft  Copy of Application Form",
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );

                //     $postdata['AttachmentSix'] = $attachment_six;
                // }

                // $json = json_encode($postdata);
                // $buffer = preg_replace( "/\r|\n/", "", $json );
                // $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $buffer);
                // fclose($myfile);
                // die;

                $json_data = json_encode($postdata);
                $decodedText = stripslashes(html_entity_decode($json_data));
                $hmac_value = hash_hmac('sha256', $decodedText, $this->secret_key);

                // $hamc_json = http_build_query(array(
                //     'applJson' => $decodedText,
                //     'hmac' => urlencode($hmac_value),
                // ));

                // $json = json_encode($hamc_json);
                // $buffer = preg_replace( "/\r|\n/", "", $json );
                // $myfile = fopen("D:\\TESTDATA\\hmacjson123.txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $buffer);
                // fclose($myfile);
                // die;

                $url = $this->config->item('building_permission_post_url');
                $curl = curl_init($url);
                //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                    'applJson' => $decodedText,
                    'hmac' => urlencode($hmac_value),
                ));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                curl_close($curl);

                log_response($dbRow->service_data->appl_ref_no, $response);

                if($response){
                    $response = json_decode($response);
                    if($response->ref->status === "success"){
                        $data_to_update=array(
                            'service_data.appl_status'=>'submitted',
                            'service_data.submission_date'=>new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            'processing_history'=>$processing_history
                        );
                        $this->registration_model->update($obj,$data_to_update);
    
                        $nowTime = date('Y-m-d H:i:s');
                        $sms = array(
                            "mobile" => (int)$dbRow->form_data->mobile,
                            "applicant_name" => $dbRow->form_data->applicant_name,
                            "service_name" => 'Appl.for Bulding Permission',
                            "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                            "app_ref_no" => $dbRow->service_data->appl_ref_no
                        );
                        sms_provider("submission", $sms);
    
                        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array("status"=>true)));
                    }else{
                        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(401)
                        ->set_output(json_encode(array("status"=>false)));
                    }
                }
        }

        $this->my_transactions();
    }

    public function querypaymentsubmit($obj = null, $payment_type = null){
        if($obj){
            $dbRow = $this->registration_model->get_by_doc_id($obj);
            if(count((array)$dbRow)) {

                if ($dbRow->service_data->appl_status == "QA") {
                    $this->my_transactions();
                }

                if($payment_type == "bp"){
                    $data1 = array(
                        "form_data.frs_request.bpPaymentTypeStatus" => true
                    );
                    $this->registration_model->update_where(['_id' => new ObjectId($obj)], $data1);

                    $dbRow = $this->registration_model->get_by_doc_id($obj);
                }

                if($payment_type == "pp"){
                    $data1 = array(
                        "form_data.frs_request.ppPaymentTypeStatus" => true
                    );
                    $this->registration_model->update_where(['_id' => new ObjectId($obj)], $data1);

                    $dbRow = $this->registration_model->get_by_doc_id($obj);
                }


                ////Temporay
                // $processing_history = $dbRow->processing_history??array();
                //         $processing_history[] = array(
                //             "processed_by" => "Payment Query submitted by ".$dbRow->form_data->applicant_name,
                //             "action_taken" => "Payment Query submitted",
                //             "remarks" => "Payment Query submitted by ".$dbRow->form_data->applicant_name." and <a href=\"".base_url('spservices/applications/payment_acknowledgement/'.$obj)."\" target=\"_blank\"><b>Payment Acknowledment</b></a>",
                //             "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                //         );
 
                //         $data = array(
                //             "service_data.appl_status" => "QA",
                //             'processing_history' => $processing_history,
                //         );
         
                //         $this->registration_model->update_where(['_id' => new ObjectId($obj)], $data);
 
                //         $this->session->set_flashdata('success','Your application has been successfully updated');
                //         redirect('spservices/applications/payment_acknowledgement/'.$obj);
                    /////Temporay//////
                   
                
                if(($dbRow->form_data->frs_request->bpPaymentTypeStatus == true) && ($dbRow->form_data->frs_request->ppPaymentTypeStatus == true)){

                    $formatted_date_bp = "";
                    if(isset($dbRow->form_data->query_bp_payment_response->ENTRY_DATE) && !empty($dbRow->form_data->query_bp_payment_response->ENTRY_DATE)){
                        $payment_date_bp = $dbRow->form_data->query_bp_payment_response->ENTRY_DATE;
                        $date_bp = DateTime::createFromFormat('d/m/Y', $payment_date_bp);
                        $formatted_date_bp = $date_bp->format('d-m-Y H:i:s');
                    }

                    $formatted_date_pp = "";
                    if(isset($dbRow->form_data->query_pp_payment_response->ENTRY_DATE) && !empty($dbRow->form_data->query_pp_payment_response->ENTRY_DATE)){
                        $payment_date_pp = $dbRow->form_data->query_pp_payment_response->ENTRY_DATE;
                        $date_pp = DateTime::createFromFormat('d/m/Y', $payment_date_pp);
                        $formatted_date_pp = $date_pp->format('d-m-Y H:i:s');
                    }
                    //pre($formatted_date);

                    // "paymentDateBP" => str_replace("/", "-", $dbRow->form_data->query_bp_payment_response->ENTRY_DATE)." ".date("H:i:s"),
                
                    $postdata = array(
                        "paymentRefNoBP" => $dbRow->form_data->query_bp_payment_response->GRN,
                        "paymentRefNoPP" => $dbRow->form_data->query_pp_payment_response->GRN,
                        "paymentDateBP" => $formatted_date_bp,
                        "paymentDatePP" => $formatted_date_pp,
                        "applicationRefNo" => $dbRow->service_data->appl_ref_no,

                        "recordType" => "PAYMENT",
                        
                        'spId' => array('applId' => $dbRow->service_data->appl_id)
                    ); 
    
                    $json = json_encode($postdata);
                    // pre($json);
                    // $buffer = preg_replace( "/\r|\n/", "", $json );
                    // $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
                    // fwrite($myfile, $buffer);
                    // fclose($myfile);
                    // die;

                    $json_data = json_encode($postdata);
                    $decodedText = stripslashes(html_entity_decode($json_data));
                    // pre($decodedText);
                    $hmac_value = hash_hmac('sha256', $decodedText, $this->secret_key);
                    
                    $url=$this->config->item('building_permission_query_submit_url');
                    $curl = curl_init($url); 
                    //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));                 
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                        'applJson' => $decodedText,
                        'hmac' => urlencode($hmac_value),
                    ));
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                    $response = curl_exec($curl);
                    if (curl_errno($curl)) {
                        $error_msg = curl_error($curl);
                    }
                    curl_close($curl);

                    //  pre($response);
    
                    log_response($dbRow->service_data->appl_ref_no, $response);
    
                    if(isset($error_msg)) {
                        die("CURL ERROR : ".$error_msg);
                    } elseif ($response) {
                        $response = json_decode($response, true);  //pre($response);
                        if ($response["ref"]["status"] === "success") {
    
                            $processing_history = $dbRow->processing_history??array();
                            $processing_history[] = array(
                                "processed_by" => "Payment Query submitted by ".$dbRow->form_data->applicant_name,
                                "action_taken" => "Payment Query submitted",
                                "remarks" => "Payment Query submitted by ".$dbRow->form_data->applicant_name,
                                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            );
    
                            $data = array(
                                "service_data.appl_status" => "QA",
                                'processing_history' => $processing_history,
                            );
            
                            $this->registration_model->update_where(['_id' => new ObjectId($obj)], $data);
    
                            // $this->session->set_flashdata('success','Your application has been successfully updated');
                            redirect('spservices/buildingpermission/registration/payment_acknowledgement/'.$obj);
                        } else {
                            $this->session->set_flashdata('errmessage','Unable to update data!!! Please try again.');
                            $this->my_transactions();
                        }//End of if else
                    }//End of if
                } else {
                    $this->session->set_flashdata('errmessage','Application Ref No : '.$dbRow->service_data->appl_ref_no.' and Payment was successfully done. Please complete payment process');
                    $this->my_transactions();
                }
            } else {
                $this->session->set_flashdata('errmessage','Unable to update data!!! Please try again.');
                $this->my_transactions();
            }//End of if else
        }

        $this->my_transactions();
    }

    public function submitfiles(){

        $objId = $this->input->post("obj_id");
        $dbrow = $this->registration_model->get_by_doc_id($objId);

        if(($dbrow->form_data->case_type === '2') && ($dbrow->form_data->any_old_permission === 'yes')) {
            if (empty($this->input->post("old_permission_copy_old"))) {
                if (!empty($this->input->post("old_permission_copy_type"))) {
                    if ($_FILES['old_permission_copy']['name'] == "") {
                        $this->session->set_flashdata('fail', 'Please upload old permission copy document');
                        $this->fileuploads($objId);
                        return;
                    }
                } elseif(empty($this->input->post("old_permission_copy_type"))) {
                    $this->session->set_flashdata('fail', 'Please select old permission copy type');
                    $this->fileuploads($objId);
                    return;
                }
            } elseif(!empty($this->input->post("old_permission_copy_old"))) {
                if(empty($this->input->post("old_permission_copy_type"))) {
                    $this->session->set_flashdata('fail', 'Please select old permission copy type');
                    $this->fileuploads($objId);
                    return;
                }
            }

            if (empty($this->input->post("old_drawing_old"))) {
                if (!empty($this->input->post("old_drawing_type"))) {
                    if ($_FILES['old_drawing']['name'] == "") {
                        $this->session->set_flashdata('fail', 'Please upload old drawing document');
                        $this->fileuploads($objId);
                        return;
                    }
                } elseif(empty($this->input->post("old_drawing_type"))) {
                    $this->session->set_flashdata('fail', 'Please select old drawing type');
                    $this->fileuploads($objId);
                    return;
                }
            } elseif(!empty($this->input->post("old_drawing_old"))) {
                if(empty($this->input->post("old_drawing_type"))) {
                    $this->session->set_flashdata('fail', 'Please select old drawing type');
                    $this->fileuploads($objId);
                    return;
                }
            }
        }

        if($dbrow->form_data->ertp === 'yes'){
            if (empty($this->input->post("technical_person_document_old"))) {
                if (!empty($this->input->post("technical_person_document_type"))) {
                    if ($_FILES['technical_person_document']['name'] == "") {
                        $this->session->set_flashdata('fail', 'Please upload technical person document');
                        $this->fileuploads($objId);
                        return;
                    }
                } elseif(empty($this->input->post("technical_person_document_type"))) {
                    $this->session->set_flashdata('fail', 'Please select technical person type');
                    $this->fileuploads($objId);
                    return;
                }
            } elseif(!empty($this->input->post("technical_person_document_old"))) {
                if(empty($this->input->post("technical_person_document_type"))) {
                    $this->session->set_flashdata('fail', 'Please select technical person document type');
                    $this->fileuploads($objId);
                    return;
                }
            }
        }

        // if (empty($this->input->post("drawing_old"))) {
        //     if (((empty($this->input->post("drawing_type"))) && (($_FILES['drawing']['name'] != ""))) || ((!empty($this->input->post("drawing_type"))) && (($_FILES['drawing']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Drawing Type and upload Drawing Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("trace_map_old"))) {
        //     if (((empty($this->input->post("trace_map_type"))) && (($_FILES['trace_map']['name'] != ""))) || ((!empty($this->input->post("trace_map_type"))) && (($_FILES['trace_map']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Trace Map Type and upload Trace Map Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("key_plan_old"))) {
        //     if (((empty($this->input->post("key_plan_type"))) && (($_FILES['key_plan']['name'] != ""))) || ((!empty($this->input->post("key_plan_type"))) && (($_FILES['key_plan']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Key Plan Type and upload Key Plan Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("site_plan_old"))) {
        //     if (((empty($this->input->post("site_plan_type"))) && (($_FILES['site_plan']['name'] != ""))) || ((!empty($this->input->post("site_plan_type"))) && (($_FILES['site_plan']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Site Plan Type and upload Site Plan Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("building_plan_old"))) {
        //     if (((empty($this->input->post("building_plan_type"))) && (($_FILES['building_plan']['name'] != ""))) || ((!empty($this->input->post("building_plan_type"))) && (($_FILES['building_plan']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Building Plan Type and upload Building Plan Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("certificate_of_supervision_old"))) {
        //     if (((empty($this->input->post("certificate_of_supervision_type"))) && (($_FILES['certificate_of_supervision']['name'] != ""))) || ((!empty($this->input->post("certificate_of_supervision_type"))) && (($_FILES['certificate_of_supervision']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Certificate of Supervision Type and upload Certificate of Supervision Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("area_statement_old"))) {
        //     if (((empty($this->input->post("area_statement_type"))) && (($_FILES['area_statement']['name'] != ""))) || ((!empty($this->input->post("area_statement_type"))) && (($_FILES['area_statement']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Area Statement Type and upload Area Statement Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("amended_byelaws_old"))) {
        //     if (((empty($this->input->post("amended_byelaws_type"))) && (($_FILES['amended_byelaws']['name'] != ""))) || ((!empty($this->input->post("amended_byelaws_type"))) && (($_FILES['amended_byelaws']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Amended Byelaws Type and upload Amended Byelaws Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("form_no_six_old"))) {
        //     if (((empty($this->input->post("form_no_six_type"))) && (($_FILES['form_no_six']['name'] != ""))) || ((!empty($this->input->post("form_no_six_type"))) && (($_FILES['form_no_six']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Form no 6 Type and upload Form no 6 Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("indemnity_bond_old"))) {
        //     if (((empty($this->input->post("indemnity_bond_type"))) && (($_FILES['indemnity_bond']['name'] != ""))) || ((!empty($this->input->post("indemnity_bond_type"))) && (($_FILES['indemnity_bond']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Indemnity Bond Type and upload Indemnity Bond Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("undertaking_signed_old"))) {
        //     if (((empty($this->input->post("undertaking_signed_type"))) && (($_FILES['undertaking_signed']['name'] != ""))) || ((!empty($this->input->post("undertaking_signed_type"))) && (($_FILES['undertaking_signed']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Undertaking Signed Type and upload Undertaking Signed Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("party_applicant_form_old"))) {
        //     if (((empty($this->input->post("party_applicant_form_type"))) && (($_FILES['party_applicant_form']['name'] != ""))) || ((!empty($this->input->post("party_applicant_form_type"))) && (($_FILES['party_applicant_form']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Party applicant form Type and upload Party applicant form Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("date_property_tax_old"))) {
        //     if (((empty($this->input->post("date_property_tax_type"))) && (($_FILES['date_property_tax']['name'] != ""))) || ((!empty($this->input->post("date_property_tax_type"))) && (($_FILES['date_property_tax']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Date of Property Tax Type and upload Date of Property Tax Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("service_plan_old"))) {
        //     if (((empty($this->input->post("service_plan_type"))) && (($_FILES['service_plan']['name'] != ""))) || ((!empty($this->input->post("service_plan_type"))) && (($_FILES['service_plan']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Service Plan Type and upload Date of Service Plan Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("parking_plan_old"))) {
        //     if (((empty($this->input->post("parking_plan_type"))) && (($_FILES['parking_plan']['name'] != ""))) || ((!empty($this->input->post("parking_plan_type"))) && (($_FILES['parking_plan']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Parking Plan Type and upload Date of Parking Plan Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("ownership_document_of_land_old"))) {
        //     if (((empty($this->input->post("ownership_document_of_land_type"))) && (($_FILES['ownership_document_of_land']['name'] != ""))) || ((!empty($this->input->post("ownership_document_of_land_type"))) && (($_FILES['ownership_document_of_land']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Ownership Document of Land Type and upload Ownership Document of Land Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("any_other_document_old"))) {
        //     if (((empty($this->input->post("any_other_document_type"))) && (($_FILES['any_other_document']['name'] != ""))) || ((!empty($this->input->post("any_other_document_type"))) && (($_FILES['any_other_document']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Any other document Type and upload Any other document Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        // if (empty($this->input->post("construction_estimate_old"))) {
        //     if (((empty($this->input->post("construction_estimate_type"))) && (($_FILES['construction_estimate']['name'] != ""))) || ((!empty($this->input->post("construction_estimate_type"))) && (($_FILES['construction_estimate']['name'] == "")))) {
        //         $this->session->set_flashdata('fail', 'Please select Construction estimate Type and upload Construction estimate Document');
        //         $this->fileuploads($objId);
        //         return;
        //     }
        // }
        if (empty($this->input->post("soft_copy_old")) && $this->slug == 'userssssss') {
            if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {
                $this->session->set_flashdata('fail', 'Please select Soft Copy Type and upload Soft Copy Document');
                $this->fileuploads($objId);
                return;
            }
        }

        // $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $technicalPersonDocument = cifileupload("technical_person_document");
        $technical_person_document = $technicalPersonDocument["upload_status"]?$technicalPersonDocument["uploaded_path"]:null;

        $oldPermissionCopy = cifileupload("old_permission_copy");
        $old_permission_copy = $oldPermissionCopy["upload_status"]?$oldPermissionCopy["uploaded_path"]:null;

        $oldDrawing = cifileupload("old_drawing");
        $old_drawing = $oldDrawing["upload_status"]?$oldDrawing["uploaded_path"]:null;

        // $drawing = cifileupload("drawing");
        // $drawing = $drawing["upload_status"]?$drawing["uploaded_path"]:null;

        // $traceMap = cifileupload("trace_map");
        // $trace_map = $traceMap["upload_status"]?$traceMap["uploaded_path"]:null;

        // $keyPlan = cifileupload("key_plan");
        // $key_plan = $keyPlan["upload_status"]?$keyPlan["uploaded_path"]:null;

        // $sitePlan = cifileupload("site_plan");
        // $site_plan = $sitePlan["upload_status"]?$sitePlan["uploaded_path"]:null;

        // $buildingPlan = cifileupload("building_plan");
        // $building_plan = $buildingPlan["upload_status"]?$buildingPlan["uploaded_path"]:null;

        // $certificateOfSupervision = cifileupload("certificate_of_supervision");
        // $certificate_of_supervision = $certificateOfSupervision["upload_status"]?$certificateOfSupervision["uploaded_path"]:null;

        // $areaStatement = cifileupload("area_statement");
        // $area_statement = $areaStatement["upload_status"]?$areaStatement["uploaded_path"]:null;

        // $amendedByelaws = cifileupload("amended_byelaws");
        // $amended_byelaws = $amendedByelaws["upload_status"]?$amendedByelaws["uploaded_path"]:null;

        // $formNoSix = cifileupload("form_no_six");
        // $form_no_six = $formNoSix["upload_status"]?$formNoSix["uploaded_path"]:null;

        // $indemnityBond = cifileupload("indemnity_bond");
        // $indemnity_bond = $indemnityBond["upload_status"]?$indemnityBond["uploaded_path"]:null;

        // $undertakingSigned = cifileupload("undertaking_signed");
        // $undertaking_signed = $undertakingSigned["upload_status"]?$undertakingSigned["uploaded_path"]:null;

        // $partyApplicantForm = cifileupload("party_applicant_form");
        // $party_applicant_form = $partyApplicantForm["upload_status"]?$partyApplicantForm["uploaded_path"]:null;

        // $datePropertyTax = cifileupload("date_property_tax");
        // $date_property_tax = $datePropertyTax["upload_status"]?$datePropertyTax["uploaded_path"]:null;

        // $servicePlan = cifileupload("service_plan");
        // $service_plan = $servicePlan["upload_status"]?$servicePlan["uploaded_path"]:null;

        // $parkingPlan = cifileupload("parking_plan");
        // $parking_plan = $parkingPlan["upload_status"]?$parkingPlan["uploaded_path"]:null;

        // $ownershipDocumentOfLand = cifileupload("ownership_document_of_land");
        // $ownership_document_of_land = $ownershipDocumentOfLand["upload_status"]?$ownershipDocumentOfLand["uploaded_path"]:null;

        // $anyOtherDocument = cifileupload("any_other_document");
        // $any_other_document = $anyOtherDocument["upload_status"]?$anyOtherDocument["uploaded_path"]:null;

        // $constructionEstimate = cifileupload("construction_estimate");
        // $construction_estimate = $constructionEstimate["upload_status"]?$constructionEstimate["uploaded_path"]:null;

        $softCopy = cifileupload("soft_copy");
        $soft_copy = $softCopy["upload_status"]?$softCopy["uploaded_path"]:null;

        $uploadedFiles = array(
            "technical_person_document_old" => strlen($technical_person_document)?$technical_person_document:$this->input->post("technical_person_document_old"),
            "old_permission_copy_old" => strlen($old_permission_copy)?$old_permission_copy:$this->input->post("old_permission_copy_old"),
            "old_drawing_old" => strlen($old_drawing)?$old_drawing:$this->input->post("old_drawing_old"),
            // "drawing_old" => strlen($drawing)?$drawing:$this->input->post("drawing_old"),
            // "trace_map_old" => strlen($trace_map)?$trace_map:$this->input->post("trace_map_old"),
            // "key_plan_old" => strlen($key_plan)?$key_plan:$this->input->post("key_plan_old"),
            // "site_plan_old" => strlen($site_plan)?$site_plan:$this->input->post("site_plan_old"),
            // "building_plan_old" => strlen($building_plan)?$building_plan:$this->input->post("building_plan_old"),
            // "certificate_of_supervision_old" => strlen($certificate_of_supervision)?$certificate_of_supervision:$this->input->post("certificate_of_supervision_old"),
            // "area_statement_old" => strlen($area_statement)?$area_statement:$this->input->post("area_statement_old"),
            // "amended_byelaws_old" => strlen($amended_byelaws)?$amended_byelaws:$this->input->post("amended_byelaws_old"),
            // "form_no_six_old" => strlen($form_no_six)?$form_no_six:$this->input->post("form_no_six_old"),
            // "indemnity_bond_old" => strlen($indemnity_bond)?$indemnity_bond:$this->input->post("indemnity_bond_old"),
            // "undertaking_signed_old" => strlen($undertaking_signed)?$undertaking_signed:$this->input->post("undertaking_signed_old"),
            // "party_applicant_form_old" => strlen($party_applicant_form)?$party_applicant_form:$this->input->post("party_applicant_form_old"),
            // "date_property_tax_old" => strlen($date_property_tax)?$date_property_tax:$this->input->post("date_property_tax_old"),
            // "service_plan_old" => strlen($service_plan)?$service_plan:$this->input->post("service_plan_old"),
            // "parking_plan_old" => strlen($parking_plan)?$parking_plan:$this->input->post("parking_plan_old"),
            // "ownership_document_of_land_old" => strlen($ownership_document_of_land)?$ownership_document_of_land:$this->input->post("ownership_document_of_land_old"),
            // "any_other_document_old" => strlen($any_other_document)?$any_other_document:$this->input->post("any_other_document_old"),
            // "construction_estimate_old" => strlen($construction_estimate)?$construction_estimate:$this->input->post("construction_estimate_old"),
            "soft_copy_old" => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        
        // if ($this->form_validation->run() == FALSE) {
        //     $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
        //     $this->fileuploads($objId);
        // } else {

            $data = array(
                'form_data.technical_person_document_type' => $this->input->post("technical_person_document_type"),
                'form_data.old_permission_copy_type' => $this->input->post("old_permission_copy_type"),
                'form_data.old_drawing_type' => $this->input->post("old_drawing_type"),
                'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),

                'form_data.technical_person_document' => strlen($technical_person_document)?$technical_person_document:$this->input->post("technical_person_document_old"),
                'form_data.old_permission_copy' => strlen($old_permission_copy)?$old_permission_copy:$this->input->post("old_permission_copy_old"),
                'form_data.old_drawing' => strlen($old_drawing)?$old_drawing:$this->input->post("old_drawing_old"),
                'form_data.soft_copy' => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old")
            );

            if (!empty($this->input->post("drawing_type"))) {
                $data["form_data.drawing_type"] = $this->input->post("drawing_type");
                $data["form_data.drawing"] = strlen($drawing)?$drawing:$this->input->post("drawing_old");
            }

            if (!empty($this->input->post("trace_map_type"))) {
                $data["form_data.trace_map_type"] = $this->input->post("trace_map_type");
                $data["form_data.trace_map"] = strlen($trace_map)?$trace_map:$this->input->post("trace_map_old");
            }

            if (!empty($this->input->post("key_plan_type"))) {
                $data["form_data.key_plan_type"] = $this->input->post("key_plan_type");
                $data["form_data.key_plan"] = strlen($key_plan)?$key_plan:$this->input->post("key_plan_old");
            }
            
            if (!empty($this->input->post("site_plan_type"))) {
                $data["form_data.site_plan_type"] = $this->input->post("site_plan_type");
                $data["form_data.site_plan"] = strlen($site_plan)?$site_plan:$this->input->post("site_plan_old");
            }

            if (!empty($this->input->post("building_plan_type"))) {
                $data["form_data.building_plan_type"] = $this->input->post("building_plan_type");
                $data["form_data.building_plan"] = strlen($building_plan)?$building_plan:$this->input->post("building_plan_old");
            }

            if (!empty($this->input->post("certificate_of_supervision_type"))) {
                $data["form_data.certificate_of_supervision_type"] = $this->input->post("certificate_of_supervision_type");
                $data["form_data.certificate_of_supervision"] = strlen($certificate_of_supervision)?$certificate_of_supervision:$this->input->post("certificate_of_supervision_old");
            }

            if (!empty($this->input->post("area_statement_type"))) {
                $data["form_data.area_statement_type"] = $this->input->post("area_statement_type");
                $data["form_data.area_statement"] = strlen($area_statement)?$area_statement:$this->input->post("area_statement_old");
            }

            if (!empty($this->input->post("amended_byelaws_type"))) {
                $data["form_data.amended_byelaws_type"] = $this->input->post("amended_byelaws_type");
                $data["form_data.amended_byelaws"] = strlen($amended_byelaws)?$amended_byelaws:$this->input->post("amended_byelaws_old");
            }

            if (!empty($this->input->post("form_no_six_type"))) {
                $data["form_data.form_no_six_type"] = $this->input->post("form_no_six_type");
                $data["form_data.form_no_six"] = strlen($form_no_six)?$form_no_six:$this->input->post("form_no_six_old");
            }

            if (!empty($this->input->post("indemnity_bond_type"))) {
                $data["form_data.indemnity_bond_type"] = $this->input->post("indemnity_bond_type");
                $data["form_data.indemnity_bond"] = strlen($indemnity_bond)?$indemnity_bond:$this->input->post("indemnity_bond_old");
            }

            if (!empty($this->input->post("undertaking_signed_type"))) {
                $data["form_data.undertaking_signed_type"] = $this->input->post("undertaking_signed_type");
                $data["form_data.undertaking_signed"] = strlen($undertaking_signed)?$undertaking_signed:$this->input->post("undertaking_signed_old");
            }

            if (!empty($this->input->post("party_applicant_form_type"))) {
                $data["form_data.party_applicant_form_type"] = $this->input->post("party_applicant_form_type");
                $data["form_data.party_applicant_form"] = strlen($party_applicant_form)?$party_applicant_form:$this->input->post("party_applicant_form_old");
            }

            if (!empty($this->input->post("date_property_tax_type"))) {
                $data["form_data.date_property_tax_type"] = $this->input->post("date_property_tax_type");
                $data["form_data.date_property_tax"] = strlen($date_property_tax)?$date_property_tax:$this->input->post("date_property_tax_old");
            }

            if (!empty($this->input->post("service_plan_type"))) {
                $data["form_data.service_plan_type"] = $this->input->post("service_plan_type");
                $data["form_data.service_plan"] = strlen($service_plan)?$service_plan:$this->input->post("service_plan_old");
            }

            if (!empty($this->input->post("parking_plan_type"))) {
                $data["form_data.parking_plan_type"] = $this->input->post("parking_plan_type");
                $data["form_data.parking_plan"] = strlen($parking_plan)?$parking_plan:$this->input->post("parking_plan_old");
            }

            if (!empty($this->input->post("ownership_document_of_land_type"))) {
                $data["form_data.ownership_document_of_land_type"] = $this->input->post("ownership_document_of_land_type");
                $data["form_data.ownership_document_of_land"] = strlen($ownership_document_of_land)?$ownership_document_of_land:$this->input->post("ownership_document_of_land_old");
            }

            if (!empty($this->input->post("any_other_document_type"))) {
                $data["form_data.any_other_document_type"] = $this->input->post("any_other_document_type");
                $data["form_data.any_other_document"] = strlen($any_other_document)?$any_other_document:$this->input->post("any_other_document_old");
            }

            if (!empty($this->input->post("construction_estimate_type"))) {
                $data["form_data.construction_estimate_type"] = $this->input->post("construction_estimate_type");
                $data["form_data.construction_estimate"] = strlen($construction_estimate)?$construction_estimate:$this->input->post("construction_estimate_old");
            }

            $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success','Your application has been successfully submitted');

            redirect('spservices/buildingpermission/registration/preview/'.$objId);
        //}//End of if else //End of if else
    } //End of submitfiles()

    public function queryform($objId=null){
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->registration_model->get_row(array("_id"=> new ObjectId($objId), "service_data.appl_status"=>"QS"));
            if($dbRow) {
                $data=array(
                    "service_data.service_name"=>$this->serviceName,
                    "dbrow"=>$dbRow
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('buildingpermission/buildingpermissionquery_view',$data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found in query mode against object id : '.$objId);
                redirect('spservices/buildingpermission/registration');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/buildingpermission/registration');
        }//End of if else
    }//End of query()

    public function querysubmit() {
        //pre($this->input->post());
        $objId = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");

        // if ((($this->input->post('panchayat') !== null)) &&  ($this->input->post('panchayat') == 0)) {
        //     if ((($this->input->post('ward_no') !== null)) &&  ($this->input->post('ward_no') == 0)) {
        //         $this->session->set_flashdata('fail', 'You cann\'t only select Ward and Panchayat as Not Applicable at same time');
        //         $this->index();
        //         return;
        //     }
        // }

        // if ((($this->input->post('panchayat') !== null)) &&  ($this->input->post('panchayat') != 0)) {
        //     if ((($this->input->post('ward_no') !== null)) &&  ($this->input->post('ward_no') != 0)) {
        //         $this->session->set_flashdata('fail', 'You cann\'t only select ward no as Not Applicable');
        //         $this->index();
        //         return;
        //     }
        // }

        $this->form_validation->set_rules('application_type', 'Application Type', 'trim|required|xss_clean|strip_tags|max_length[30]');
        $this->form_validation->set_rules('case_type', 'Case Type', 'trim|required|xss_clean|strip_tags|max_length[30]');
        $this->form_validation->set_rules('house_no', 'House no. should be alpha-numeric', 'trim|xss_clean|strip_tags|regex_match[/^[ 0-9a-zA-Z]*$/]');
        if ((!empty($this->input->post('ertp'))) &&  ($this->input->post('ertp') == "yes")) {

            $this->form_validation->set_rules('technical_person_name', 'Technical Person Name', 'trim|required|xss_clean|strip_tags|max_length[200]');  
            $this->form_validation->set_rules('district_emp_tech', 'District for Empanelled Registered Technical Person', 'trim|required|xss_clean|strip_tags|max_length[30]'); 
            $this->form_validation->set_rules('district_emp_tech_name', 'District Name for Empanelled Registered Technical Person', 'trim|required|xss_clean|strip_tags|max_length[200]'); 
            $this->form_validation->set_rules('empanelled_reg_tech_person', 'Empanelled Registered Technical Person', 'trim|required|xss_clean|strip_tags|max_length[30]'); 
            $this->form_validation->set_rules('empanelled_reg_tech_person_name', 'Empanelled Registered Technical Person Name', 'trim|required|xss_clean|strip_tags|max_length[200]'); 

            $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('district_name', 'District Name', 'trim|required|xss_clean|strip_tags|max_length[200]');
            //$this->form_validation->set_rules('house_no', 'House No', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('mst_pln_dev_auth', 'Master Plan/Development Authority', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('mst_pln_dev_auth_name', 'Master Plan/Development Authority Name', 'trim|required|xss_clean|strip_tags|max_length[200]');
            $this->form_validation->set_rules('name_of_road', 'Road name should be alpha-numeric', 'trim|required|xss_clean|strip_tags|max_length[200]|regex_match[/^[ 0-9a-zA-Z.,]*$/]');
            $this->form_validation->set_rules('panchayat_ulb', 'ULB/Panchayat', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('panchayat_ulb_name', 'ULB/Panchayat Name', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('site_pin_code', 'Site Pincode is not valid', 'trim|required|xss_clean|strip_tags|max_length[30]|regex_match[/^(([7][8][0-9][0-9][0-9][0-9]){0,1})/]');
            $this->form_validation->set_rules('revenue_village', 'Revenue Village', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('revenue_village_name', 'Revenue Village Name', 'trim|required|xss_clean|strip_tags|max_length[200]');
            $this->form_validation->set_rules('old_dag_no', 'Old Dag No', 'trim|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('ward_no', 'Ward No', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('ward_no_name', 'Ward No Name', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('new_dag_no', 'New Dag No', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('old_patta_no', 'Old Patta No', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('new_patta_no', 'New Patta No', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('mouza', 'Mouza', 'trim|required|xss_clean|strip_tags|max_length[30]');
            $this->form_validation->set_rules('mouza_name', 'Mouza Name', 'trim|required|xss_clean|strip_tags|max_length[30]');
        }
        if ((!empty($this->input->post('case_type'))) &&  ($this->input->post('case_type') == "2")) {
            $this->form_validation->set_rules('any_old_permission', 'Any Old Permission Selection', 'trim|required|xss_clean|strip_tags|max_length[30]');  
        }
        if ((!empty($this->input->post('any_old_permission'))) &&  ($this->input->post('any_old_permission') == "yes")) {
            $this->form_validation->set_rules('old_permission_no', 'Old Permission is required', 'trim|required|xss_clean|strip_tags|max_length[30]');  
        }

        $this->form_validation->set_rules('applicant_name', 'Applicant Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('applicant_gender', 'Applicant Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('father_name', 'Father Name', 'trim|required|regex_match[/^[a-zA-Z ]*$/]|xss_clean|strip_tags|max_length[200]');
        $this->form_validation->set_rules('mother_name', 'Mother Name', 'trim|required|regex_match[/^[a-zA-Z ]*$/]|xss_clean|strip_tags|max_length[200]');
        $this->form_validation->set_rules('spouse_name', 'Spouse Name', 'trim|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[200]');
        $this->form_validation->set_rules('permanent_address', 'Permanent Address', 'trim|required|xss_clean|strip_tags|max_length[200]|regex_match[/^[a-zA-Z ]*$/]');
        $this->form_validation->set_rules('mobile', 'Mobile No', 'trim|required|xss_clean|strip_tags|max_length[10]|numeric');
        $this->form_validation->set_rules('pin_code', 'PIN Code', 'trim|required|xss_clean|strip_tags|max_length[6]');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|strip_tags|valid_email');
        $this->form_validation->set_rules('pan_no', 'PAN number is alpha-numeric', 'trim|required|xss_clean|strip_tags|max_length[10]|regex_match[/^[a-zA-Z0-9]{10}/]');
        $this->form_validation->set_rules('monthly_income', 'Monthly Income', 'trim|required|xss_clean|strip_tags|max_length[10]');

        $this->form_validation->set_rules('owner_name[]', 'Owner name is mandatory and contains alphabets only', 'trim|required|xss_clean|strip_tags|max_length[100]|regex_match[/^[ a-zA-Z]+$/]');
        $this->form_validation->set_rules('owner_gender[]', 'Owner Genders', 'trim|required|xss_clean|strip_tags|max_length[10]');
         
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
 
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->queryform($obj_id);
        } else {
            if (!empty($this->input->post('empanelled_reg_tech_person'))) {
                $url = $this->config->item('building_permission_url')."ertp-data?rtpId=".$this->input->post('empanelled_reg_tech_person')."";
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                curl_close($curl);
                
                if (!empty($response)) {
                    $response = json_decode($response);
                    if ($response->isActive === 'N') {
                        $this->session->set_flashdata('error', 'Selected ERTP is under suspension for 6 months ');
                        $obj_id = strlen($objId) ? $objId : null;
                        $this->queryform($obj_id);
                        return;
                    }
                }else{
                    $this->session->set_flashdata('error', 'No ERTP found against uploaded ERTP No.');
                    $obj_id = strlen($objId) ? $objId : null;
                    $this->queryform($obj_id);
                    return;
                }
            }

            $sessionUser = $this->session->userdata();
 
            $owner_name =  $this->input->post("owner_name");
            $owner_gender =  $this->input->post("owner_gender");                       
            $owner_details = array();
            if(count($owner_name)) {
                foreach($owner_name as $k=>$on) {
 
                    $owner_detail = array(
                        "owner_name" => $owner_name[$k],
                        "owner_gender" => $owner_gender[$k]
                    );
                    $owner_details[] = $owner_detail;
                }//End of foreach()        
            }

            $dbRow1 = $this->registration_model->get_by_doc_id($objId);
            if(count((array)$dbRow1)) {
                $data = array(
                    'form_data.application_type' => $this->input->post("application_type"),
                    'form_data.case_type' => $this->input->post("case_type"),
                    'form_data.any_old_permission' => $this->input->post("any_old_permission"),
                    'form_data.old_permission_no' => $this->input->post("old_permission_no"),
    
                    'form_data.district' => $this->input->post("district"),
                    'form_data.district_name' => $this->input->post("district_name"),
                    'form_data.house_no' => $this->input->post("house_no"),
                    'form_data.mst_pln_dev_auth' => $this->input->post("mst_pln_dev_auth"),
                    'form_data.mst_pln_dev_auth_name' => $this->input->post("mst_pln_dev_auth_name"),
                    'form_data.name_of_road' => $this->input->post("name_of_road"),
                    'form_data.panchayat_ulb' => $this->input->post("panchayat_ulb"),
                    'form_data.panchayat_ulb_name' => $this->input->post("panchayat_ulb_name"),
                    'form_data.site_pin_code' => $this->input->post("site_pin_code"),
                    'form_data.revenue_village' => $this->input->post("revenue_village"),
                    'form_data.revenue_village_name' => $this->input->post("revenue_village_name"),
                    'form_data.old_dag_no' => $this->input->post("old_dag_no"),
                    'form_data.ward_no' => $this->input->post("ward_no"),
                    'form_data.ward_no_name' => $this->input->post("ward_no_name"),
                    'form_data.new_dag_no' => $this->input->post("new_dag_no"),
                    'form_data.mouza' => $this->input->post("mouza"),
                    'form_data.mouza_name' => $this->input->post("mouza_name"),
                    'form_data.old_patta_no' => $this->input->post("old_patta_no"),  
                    'form_data.new_patta_no' => $this->input->post("new_patta_no"),
    
                    'form_data.applicant_name' => $this->input->post("applicant_name"),
                    'form_data.applicant_gender' => $this->input->post("applicant_gender"),
                    'form_data.father_name' => $this->input->post("father_name"),
                    'form_data.mother_name' => $this->input->post("mother_name"),
                    'form_data.spouse_name' => $this->input->post("spouse_name"),
                    'form_data.permanent_address' => $this->input->post("permanent_address"),
                    'form_data.pin_code' => $this->input->post("pin_code"),
                    'form_data.mobile' => $this->input->post("mobile"),
                    'form_data.monthly_income' => $this->input->post("monthly_income"),
                    'form_data.pan_no' => $this->input->post("pan_no"),
                    'form_data.email' => $this->input->post("email"),
    
                    'form_data.owner_details' => $owner_details,
                );

                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

                $dbRow = $this->registration_model->get_by_doc_id($objId);
                $owners = array();
                if(count($dbRow->form_data->owner_details)) {
                    foreach($dbRow->form_data->owner_details as $key => $owner_detail) {
    
                        $owner_detail = array(
                            "ownerName" => $owner_detail->owner_name,
                            "gender" => $owner_detail->owner_gender,
                        );
                        $owners[] = $owner_detail;
                    }//End of foreach()        
                }//End of if

                $postdata = array(
                    "applicationRefNo" => $dbRow->service_data->appl_ref_no,
                    "rtpId" => $dbRow->form_data->empanelled_reg_tech_person,
                    "applicationTypeId" => $dbRow->form_data->application_type,
                    "caseTypeId" => $dbRow->form_data->case_type,
                    "hasAssistantArchitect" => $dbRow->form_data->ertp,
                    "architectOnRecord" => $dbRow->form_data->technical_person_name,
                    "hasOldPermission" => $dbRow->form_data->any_old_permission,
                    "oldPermissionNo" => $dbRow->form_data->old_permission_no,
                    "villageId" => $dbRow->form_data->revenue_village,
                    "mouzaId" => $dbRow->form_data->mouza,
                    "incomeId" => $dbRow->form_data->monthly_income,
                    "fatherName" => $dbRow->form_data->father_name,
                    "motherName" => $dbRow->form_data->mother_name,
                    "spouseName" => $dbRow->form_data->spouse_name,
                    "panNo" => $dbRow->form_data->pan_no,
                    "owners" => $owners,
                    "wardId" => $dbRow->form_data->ward_no,
                    "houseNo" => $dbRow->form_data->house_no,
                    "roadName" => $dbRow->form_data->name_of_road,
                    "dagNo" => $dbRow->form_data->old_dag_no,
                    "newDagNo" => $dbRow->form_data->new_dag_no,
                    "sitePinCode" => $dbRow->form_data->site_pin_code,
                    "pattaNo" => $dbRow->form_data->old_patta_no,
                    "newPattaNo" => $dbRow->form_data->new_patta_no,
                    "applicantName" => $dbRow->form_data->applicant_name,
                    "mobileNo" => $dbRow->form_data->mobile,
                    "permanentAddress" => $dbRow->form_data->permanent_address,
                    "email" => $dbRow->form_data->email,
                    "pinCode" => $dbRow->form_data->pin_code,
                    "districtId" => $dbRow->form_data->district,
                    "developmentAuthorityId" => $dbRow->form_data->mst_pln_dev_auth,
                    "ulbPanchayatId" => $dbRow->form_data->panchayat_ulb,

                    "recordType" => "MODIFY",
                    
                    'spId' => array('applId' => $dbRow->service_data->appl_id)
                ); 
         
                $json_data = json_encode($postdata);
                $decodedText = stripslashes(html_entity_decode($json_data));
                $hmac_value = hash_hmac('sha256', $decodedText, $this->secret_key);
                 
                $url=$this->config->item('building_permission_query_submit_url');
                $curl = curl_init($url); 
                //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));                 
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
                    'applJson' => $decodedText,
                    'hmac' => urlencode($hmac_value),
                )));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }
                curl_close($curl);
 
                log_response($dbRow->service_data->appl_ref_no, $response);
 
                if(isset($error_msg)) {
                    die("CURL ERROR : ".$error_msg);
                } elseif ($response) {
                    $response = json_decode($response, true);  //pre($response);
                    if ($response["ref"]["status"] === "success") {
 
                        $processing_history = $dbRow->processing_history??array();
                        $processing_history[] = array(
                            "processed_by" => "Query submitted by ".$dbRow->form_data->applicant_name,
                            "action_taken" => "Query submitted",
                            "remarks" => "Query submitted by ".$dbRow->form_data->applicant_name,
                            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        );
 
                        $data = array(
                            "service_data.appl_status" => "QA",
                            'processing_history' => $processing_history,
                        );
         
                        $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);
 
                        $this->session->set_flashdata('success','Your application has been successfully updated');
                        redirect('spservices/buildingpermission/registration/preview/'.$objId);
                    } else {
                        $this->session->set_flashdata('fail','Unable to update data!!! Please try again.');
                        $this->queryform($objId);
                    }//End of if else
                }//End of if
            } else {
                $this->session->set_flashdata('fail','Unable to update data!!! Please try again.');
                $this->queryform();
            }//End of if else
        } //End of if else
    } //End of querysubmit()

    public function preview($objId = null) {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('buildingpermission/buildingpermissionpreview_view.php', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/buildingpermission/registration/');
        } //End of if else
    }//End of preview()

    public function applicationpreview($objId = null) {

        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('buildingpermission/buildingpermissionapplication_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/buildingpermission/registration/');
        } //End of if else
    }

    public function submit_to_backend($obj,$show_ack=false){
        if($obj){
            $dbRow = $this->registration_model->get_by_doc_id($obj);

            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }

            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")){
                //procesing data
                $processing_history = $dbRow->processing_history??array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
                
                $owners = array();
                if(count($dbRow->form_data->owner_details)) {
                    foreach($dbRow->form_data->owner_details as $key => $owner_detail) {
    
                        $owner_detail = array(
                            "ownerName" => $owner_detail->owner_name,
                            "gender" => $owner_detail->owner_gender,
                        );
                        $owners[] = $owner_detail;
                    }//End of foreach()        
                }//End of if

                $recordType = "";
                if((isset($dbRow->form_data->old_permit_appl_ref_no)) && (!empty($dbRow->form_data->old_permit_appl_ref_no))){
                    $recordType = "REAPPLY";
                } else {
                    $recordType = "FRESH";
                }

                $postdata = array(
                    "applicationRefNo" => $dbRow->service_data->appl_ref_no,
                    "rtpId" => $dbRow->form_data->empanelled_reg_tech_person,
                    "applicationTypeId" => $dbRow->form_data->application_type,
                    "caseTypeId" => $dbRow->form_data->case_type,
                    "hasAssistantArchitect" => $dbRow->form_data->ertp,
                    "architectOnRecord" => $dbRow->form_data->technical_person_name,
                    "hasOldPermission" => $dbRow->form_data->any_old_permission,
                    "oldPermissionNo" => $dbRow->form_data->old_permission_no,
                    "villageId" => $dbRow->form_data->revenue_village,
                    "mouzaId" => $dbRow->form_data->mouza,
                    "incomeId" => $dbRow->form_data->monthly_income,
                    "fatherName" => $dbRow->form_data->father_name,
                    "motherName" => $dbRow->form_data->mother_name,
                    "spouseName" => $dbRow->form_data->spouse_name,
                    "panNo" => $dbRow->form_data->pan_no,
                    "owners" => $owners,
                    "wardId" => $dbRow->form_data->ward_no,
                    "houseNo" => $dbRow->form_data->house_no,
                    "roadName" => $dbRow->form_data->name_of_road,
                    "dagNo" => $dbRow->form_data->old_dag_no,
                    "newDagNo" => $dbRow->form_data->new_dag_no,
                    "sitePinCode" => $dbRow->form_data->site_pin_code,
                    "pattaNo" => $dbRow->form_data->old_patta_no,
                    "newPattaNo" => $dbRow->form_data->new_patta_no,
                    "applicantName" => $dbRow->form_data->applicant_name,
                    "mobileNo" => $dbRow->form_data->mobile,
                    "permanentAddress" => $dbRow->form_data->permanent_address,
                    "email" => $dbRow->form_data->email,
                    "pinCode" => $dbRow->form_data->pin_code,
                    "districtId" => $dbRow->form_data->district,
                    "developmentAuthorityId" => $dbRow->form_data->mst_pln_dev_auth,
                    "ulbPanchayatId" => $dbRow->form_data->panchayat_ulb,

                    "recordType" => $recordType,
                    
                    'spId' => array('applId' => $dbRow->service_data->appl_id)
                );

                if((isset($dbRow->form_data->old_permit_appl_ref_no)) && (!empty($dbRow->form_data->old_permit_appl_ref_no))){
                    $postdata['oldApplicationRefNo'] = $dbRow->form_data->old_permit_appl_ref_no;
                }
    
                if(!empty($dbRow->form_data->technical_person_document)){
                    $technical_person_document = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->technical_person_document));

                    $postdata['architectQualificationDoc'] = $technical_person_document;
                }

                if(!empty($dbRow->form_data->old_permission_copy)){
                    $old_permission_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->old_permission_copy));

                    $postdata['oldPermissionDoc'] = $old_permission_copy;
                }

                if(!empty($dbRow->form_data->old_drawing)){
                    $old_drawing = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->old_drawing));

                    $postdata['oldDrawingDoc'] = $old_drawing;
                }

                if(!empty($dbRow->form_data->drawing)){
                    $drawing = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->drawing));

                    $attachment_one = array(
                        "encl" =>  $drawing,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->drawing_type,
                        "enclType" => $dbRow->form_data->drawing_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentOne'] = $attachment_one;
                }

                if(!empty($dbRow->form_data->trace_map)){
                    $trace_map = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->trace_map));

                    $attachment_two = array(
                        "encl" =>  $trace_map,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->trace_map_type,
                        "enclType" => $dbRow->form_data->trace_map_type,
                        "id" => "93964",
                        "doctypecode" => "8257",
                        "docRefId" => "8258",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentTwo'] = $attachment_two;
                }

                if(!empty($dbRow->form_data->key_plan)){
                    $key_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->key_plan));

                    $attachment_three = array(
                        "encl" =>  $key_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->key_plan_type,
                        "enclType" => $dbRow->form_data->key_plan_type,
                        "id" => "93965",
                        "doctypecode" => "8259",
                        "docRefId" => "8260",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentThree'] = $attachment_three;
                }

                if(!empty($dbRow->form_data->site_plan)){
                    $site_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->site_plan));

                    $attachment_four = array(
                        "encl" =>  $site_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->site_plan_type,
                        "enclType" => $dbRow->form_data->site_plan_type,
                        "id" => "93966",
                        "doctypecode" => "8290",
                        "docRefId" => "8261",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentFour'] = $attachment_four;
                }

                if(!empty($dbRow->form_data->building_plan)){
                    $building_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->building_plan));

                    $attachment_five = array(
                        "encl" =>  $building_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->building_plan_type,
                        "enclType" => $dbRow->form_data->building_plan_type,
                        "id" => "93966",
                        "doctypecode" => "8290",
                        "docRefId" => "8261",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentFive'] = $attachment_five;
                }

                if(!empty($dbRow->form_data->certificate_of_supervision)){
                    $certificate_of_supervision = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->certificate_of_supervision));

                    $attachment_six = array(
                        "encl" =>  $certificate_of_supervision,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->certificate_of_supervision_type,
                        "enclType" => $dbRow->form_data->certificate_of_supervision_type,
                        "id" => "93968",
                        "doctypecode" => "8265",
                        "docRefId" => "8292",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentSix'] = $attachment_six;
                }

                if(!empty($dbRow->form_data->area_statement)){
                    $area_statement = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->area_statement));

                    $attachment_seven = array(
                        "encl" =>  $area_statement,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->area_statement_type,
                        "enclType" => $dbRow->form_data->area_statement_type,
                        "id" => "93969",
                        "doctypecode" => "8255",
                        "docRefId" => "8256",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentSeven'] = $attachment_seven;
                }

                if(!empty($dbRow->form_data->amended_byelaws)){
                    $amended_byelaws = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->amended_byelaws));

                    $attachment_eight = array(
                        "encl" =>  $amended_byelaws,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->amended_byelaws_type,
                        "enclType" => $dbRow->form_data->amended_byelaws_type,
                        "id" => "93969",
                        "doctypecode" => "8255",
                        "docRefId" => "8256",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentEight'] = $attachment_eight;
                }

                if(!empty($dbRow->form_data->form_no_six)){
                    $form_no_six = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->form_no_six));

                    $attachment_nine = array(
                        "encl" =>  $form_no_six,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->form_no_six_type,
                        "enclType" => $dbRow->form_data->form_no_six_type,
                        "id" => "93971",
                        "doctypecode" => "8268",
                        "docRefId" => "8269",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentNine'] = $attachment_nine;
                }

                if(!empty($dbRow->form_data->indemnity_bond)){
                    $indemnity_bond = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->indemnity_bond));

                    $attachment_nine = array(
                        "encl" =>  $indemnity_bond,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->indemnity_bond_type,
                        "enclType" => $dbRow->form_data->indemnity_bond_type,
                        "id" => "93972",
                        "doctypecode" => "8270",
                        "docRefId" => "8271",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentTen'] = $attachment_nine;
                }

                if(!empty($dbRow->form_data->undertaking_signed)){
                    $undertaking_signed = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->undertaking_signed));

                    $attachment_eleven = array(
                        "encl" =>  $undertaking_signed,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->undertaking_signed_type,
                        "enclType" => $dbRow->form_data->undertaking_signed_type,
                        "id" => "93972",
                        "doctypecode" => "8270",
                        "docRefId" => "8271",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentEleven'] = $attachment_eleven;
                }

                if(!empty($dbRow->form_data->party_applicant_form)){
                    $party_applicant_form = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->party_applicant_form));

                    $attachment_twelve = array(
                        "encl" =>  $party_applicant_form,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->party_applicant_form_type,
                        "enclType" => $dbRow->form_data->party_applicant_form_type,
                        "id" => "93974",
                        "doctypecode" => "8288",
                        "docRefId" => "8289",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentTwelve'] = $attachment_twelve;
                }

                if(!empty($dbRow->form_data->date_property_tax)){
                    $date_property_tax = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->date_property_tax));

                    $attachment_thirteen = array(
                        "encl" =>  $date_property_tax,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->date_property_tax_type,
                        "enclType" => $dbRow->form_data->date_property_tax_type,
                        "id" => "93975",
                        "doctypecode" => "8275",
                        "docRefId" => "8276",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentThirteen'] = $attachment_thirteen;
                }

                if(!empty($dbRow->form_data->service_plan)){
                    $service_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->service_plan));

                    $attachment_fourteen = array(
                        "encl" =>  $service_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->service_plan_type,
                        "enclType" => $dbRow->form_data->service_plan_type,
                        "id" => "93976",
                        "doctypecode" => "8278",
                        "docRefId" => "8279",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentFourteen'] = $attachment_fourteen;
                }

                if(!empty($dbRow->form_data->parking_plan)){
                    $parking_plan = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->parking_plan));

                    $attachment_fifteen = array(
                        "encl" =>  $parking_plan,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->parking_plan_type,
                        "enclType" => $dbRow->form_data->parking_plan_type,
                        "id" => "93977",
                        "doctypecode" => "8280",
                        "docRefId" => "8281",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentFifteen'] = $attachment_fifteen;
                }

                if(!empty($dbRow->form_data->ownership_document_of_land)){
                    $ownership_document_of_land = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->ownership_document_of_land));

                    $attachment_sixteen = array(
                        "encl" =>  $ownership_document_of_land,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->ownership_document_of_land_type,
                        "enclType" => $dbRow->form_data->ownership_document_of_land_type,
                        "id" => "93978",
                        "doctypecode" => "8282",
                        "docRefId" => "8283",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentSixteen'] = $attachment_sixteen;
                }

                if(!empty($dbRow->form_data->any_other_document)){
                    $any_other_document = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->any_other_document));

                    $attachment_seventeen = array(
                        "encl" =>  $any_other_document,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->any_other_document_type,
                        "enclType" => $dbRow->form_data->any_other_document_type,
                        "id" => "93978",
                        "doctypecode" => "8282",
                        "docRefId" => "8283",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentSeventeen'] = $attachment_seventeen;
                }

                if(!empty($dbRow->form_data->construction_estimate)){
                    $construction_estimate = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->construction_estimate));

                    $attachment_eighteen = array(
                        "encl" =>  $construction_estimate,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->construction_estimate_type,
                        "enclType" => $dbRow->form_data->construction_estimate_type,
                        "id" => "93980",
                        "doctypecode" => "8286",
                        "docRefId" => "8287",
                        "enclExtn" => "pdf"
                    );

                    $postdata['attachmentList']['attachmentEighteen'] = $attachment_eighteen;
                }

                // pre($postdata);

                // if(!empty($dbRow->form_data->soft_copy)){
                //     $soft_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->soft_copy));

                //     $attachment_six = array(
                //         "encl" => $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload the Soft  Copy of Application Form",
                //         "enclType" => "Upload the Soft  Copy of Application Form",
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );

                //     $postdata['AttachmentSix'] = $attachment_six;
                // }

                // if(!empty($dbRow->form_data->soft_copy)){
                //     $soft_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->soft_copy));

                //     $attachment_six = array(
                //         "encl" => $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload the Soft  Copy of Application Form",
                //         "enclType" => "Upload the Soft  Copy of Application Form",
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );

                //     $postdata['AttachmentSix'] = $attachment_six;
                // }

                // $json = json_encode($postdata);
                // pre($json);
                // $buffer = preg_replace( "/\r|\n/", "", $json );
                // $myfile = fopen("D:\\TESTDATA\\newjson1.txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $buffer);
                // fclose($myfile);
                // die;

                $json_data = json_encode($postdata);
                $decodedText = stripslashes(html_entity_decode($json_data));
                $hmac_value = hash_hmac('sha256', $decodedText, $this->secret_key);
    
                $url = $this->config->item('building_permission_post_url');
                $curl = curl_init($url);
                //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                    'applJson' => $decodedText,
                    'hmac' => urlencode($hmac_value),
                ));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                curl_close($curl);
                // pre($response);

                // $var = http_build_query(array(
                //     'applJson' => $decodedText,
                //     'hmac' => urlencode($hmac_value),
                // ));
                // $myfile = fopen("D:\\TESTDATA\\hmac1234555555.txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $var);
                // fclose($myfile);
                // die;

                log_response($dbRow->service_data->appl_ref_no, $response);

                if($response){
                    $response = json_decode($response);

                    // pre($response);
                    
                    if($response->ref->status === "success"){
                        $data_to_update=array(
                            'service_data.appl_status'=>'submitted',
                            'service_data.submission_date'=>new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            'processing_history'=>$processing_history
                        );
                        $this->registration_model->update($obj,$data_to_update);

                        //Sending submission SMS
                        $nowTime = date('Y-m-d H:i:s');
                        $sms = array(
                            "mobile" => (int)$dbRow->form_data->mobile,
                            "applicant_name" => $dbRow->form_data->applicant_name,
                            "service_name" => 'Appl.for Bulding Permission',
                            "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                            "app_ref_no" => $dbRow->service_data->appl_ref_no
                        );
                        sms_provider("submission", $sms);
                        redirect('spservices/applications/acknowledgement/' . $obj);

                        // return $this->output
                        // ->set_content_type('application/json')
                        // ->set_status_header(200)
                        // ->set_output(json_encode(array("status"=>true)));
                    }else{
                        $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>'.$dbRow->service_data->appl_ref_no.'</b>, Please try again.');
                        $this->my_transactions();
                        return;
                        // return $this->output
                        // ->set_content_type('application/json')
                        // ->set_status_header(401)
                        // ->set_output(json_encode(array("status"=>false)));
                    }
                } else {
                    $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                    $this->my_transactions();
                    return;
                }
            } else {
                $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                $this->my_transactions();
                return;
            }
        }

        $this->my_transactions();
    }
    
    function createcaptcha() {
        $captchaDir = "storage/captcha/";
        array_map('unlink', glob("$captchaDir*.jpg"));

        $this->load->helper('captcha');
        $config = array(
            'img_path' => './storage/captcha/',
            'img_url' => base_url('storage/captcha/'),
            'font_path' => APPPATH.'sys/fonts/texb.ttf',
            'img_width' => '150',
            'img_height' => 30,
            'expiration' => 7200,
            'word_length' => 6,
            'font_size' => 16,
            'img_id' => 'capimg',
            'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(94, 20, 38),
                'text' => array(0, 0, 0),
                'grid' => array(178, 184, 194)
            )
        );
        $captcha = create_captcha($config);        
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode', $captcha['word']);
        echo $captcha['image'];
    }//End of createcaptcha()
    
    function getID($length) {
        $rtps_trans_id = $this->generateID($length);
        while ($this->registration_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        }//End of while()
        return $rtps_trans_id;
    }//End of getID()

    public function generateID($length) {
        $date = date('Y');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-PPBP/" . $date."/" .$number;
        return $str;
    } //End of generateID()

    public function track($objId=null) {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
                "service_data.service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('castecertificate/castecertificatetrack_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/castecertificate/');
        }//End of if else
    }

    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
           return false;
        }//End of if else
    }//End of checkObjectId()

    private function my_transactions(){
        $user=$this->session->userdata();
        if(isset($user['role']) && !empty($user['role'])){
          redirect(base_url('iservices/admin/my-transactions'));
        }else{
          redirect(base_url('iservices/transactions'));
        }
    }

    public function service_sp_list() {
        $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});

        if (isset($user->type_of_kiosk)) {
            if ($user->type_of_kiosk == "eDistrict") {
                // pre($user->type_of_kiosk);
                $this->my_transactions();
                return;
            }
        }

        $user1=$this->session->userdata();
        $sign_role = "";
        $sign_no = "";
        if(isset($user1['role']) && !empty($user1['role'])){
            if($this->session->userdata('role')->slug == "PFC"){
                $userInfo = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
                $sign_no = $userInfo->email;
                $sign_role = "PFC";
            }
            
            if($this->session->userdata('role')->slug == "CSC"){
                $sign_no = $user1['userId'];
                $sign_role = "CSC";
            }
        }
        else{
            $sign_no = $this->session->userdata("mobile");
            $sign_role = "CTZN";
            if(empty($this->session->userdata("mobile"))){
                $this->session->set_flashdata('error', 'Mobile No doesn\'t exit');
                // $this->my_transactions();
                redirect(base_url('spservices/cancellation-building-permission-service-list'));
                return;
            }
        }

        $json_obj = array(
            "sign_no" => '9365500582',
            "sign_role" =>  'CTZN'
        );

        // $json_obj = array(
        //     "sign_no" => $sign_no,
        //     "sign_role" =>  $sign_role
        // );
        $postdata = json_encode($json_obj);
        
        $curl = curl_init('https://rtpsprod.assam.statedatacenter.in/tools/rtps_id_labels/src/api/external_apis.php/get_gmda_submitted_applicationseee');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer |0VW?z,-w2w"6;{b8v}K6A5+Fdf@l-',
        ));
        $data = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($data);
        if (!empty($data->status)) {
            return $data->data->applications;
        } else 
            return false;
    }
}//End of BuildingPermissioncertificate
