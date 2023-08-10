<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Necertificate extends Rtps {

    private $serviceName = "Application form for Non-Encumbrance Certificate<br> বোজমুক্ত প্ৰমাণ পত্ৰৰ বাবে আবেদন";
    private $serviceId = "NECERTIFICATE";

    public function __construct() {
        parent::__construct();
        $this->load->model('necertificates_model');
        $this->load->model('sros_model');
        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');

        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else
        
        if($this->slug === "CSC"){                
            $this->apply_by = $this->session->userId;
        } else {
            $this->apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        }//End of if else
    }//End of __construct()

    public function index($objId = null) {        
        //check_application_count_for_citizen();
        $data = array(
            "obj_id" => $objId,
            "service_name" => $this->serviceName
        );
        if ($this->checkObjectId($objId)) {            
            $dbFilter = array(
                '_id'=>new ObjectId($objId),
                'applied_by' => $this->apply_by,
                'status' => 'DRAFT'
            );
            $data["dbrow"] = $this->necertificates_model->get_row($dbFilter);
        } else {
            $data["dbrow"] = false;
        }//End of if else 
        $data["sro_dist_list"] = $this->sros_model->sro_dist_list();
        $this->load->view('includes/frontend/header');
        $this->load->view('nec/necertificate_view', $data);
        $this->load->view('includes/frontend/footer');
    }//End of index()

    public function getlocation($unitCode = null) {
        if ($unitCode) {
            $data = $this->sros_model->get_rows(array("parent_org_unit_code" => $unitCode));
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(array());
            } //End of if else
        } //End of if
    }//End of getlocation()

    public function submit() {
        $objId = $this->input->post("obj_id");
        $rtps_trans_id = $this->input->post("rtps_trans_id");
        $submitMode = $this->input->post("submit_mode");

        $this->form_validation->set_rules('applicant_name', 'Applicant name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('father_name', 'Father', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('applicant_address', 'Address', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|integer|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|strip_tags|valid_email');

        $this->form_validation->set_rules('office_district', 'Office', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('sro_code', 'SRO', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('circle', 'Circle', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mouza', 'Mouza', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('village', 'Village', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('searched_from', 'Searched from', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('searched_to', 'Searched to', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('land_doc_ref_no', 'Ref no.', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('land_doc_reg_year', 'Reg. year', 'trim|required|integer|exact_length[4]|xss_clean|strip_tags');
        //$this->form_validation->set_rules('delivery_mode', 'Delivery mode', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
        } else {
            $rtps_trans_id = $this->getID(7);
            $sessionUser = $this->session->userdata();

            $patta_nos = $this->input->post("patta_nos");
            $dag_nos = $this->input->post("dag_nos");
            $land_areas = $this->input->post("land_areas");
            $patta_types = $this->input->post("patta_types");
            $plots = array();
            if (count($patta_nos)) {
                foreach ($patta_nos as $k => $patta_no) {
                    $plot = array(
                        "patta_no" => $patta_no,
                        "dag_no" => $dag_nos[$k],
                        "land_area" => $land_areas[$k],
                        "patta_type" => $patta_types[$k]
                    );
                    $plots[] = $plot;
                } //End of foreach()        
            } //End of if

            $inputs = [
                'rtps_trans_id' => $rtps_trans_id,
                'app_ref_no' => uniqid(),
                'user_id' => $sessionUser['userId']->{'$id'},
                'service_name' => $this->serviceName,
                'service_id' => $this->serviceId,
                'status' => 'DRAFT',
                'user_type' => $this->slug,
                'applied_by' => $this->apply_by,
                'applicant_name' => $this->input->post("applicant_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'father_name' => $this->input->post("father_name"),
                'applicant_address' => $this->input->post("applicant_address"),
                'mobile' => $this->input->post("mobile"),
                'email' => $this->input->post("email"),
                'office_district' => $this->input->post("office_district"),
                'district_name' => trim($this->input->post("district_name")),
                'sro_code' => $this->input->post("sro_code"),
                'office_name' => $this->input->post("office_name"),
                'circle' => $this->input->post("circle"),
                'circle_name' => $this->input->post("circle_name"),
                'mouza' => $this->input->post("mouza"),
                'mouza_name' => $this->input->post("mouza_name"),
                'village' => $this->input->post("village"),
                'village_name' => $this->input->post("village_name"),
                'plots' => $plots,
                'searched_from' => $this->input->post("searched_from"),
                'searched_to' => $this->input->post("searched_to"),
                'land_doc_ref_no' => $this->input->post("land_doc_ref_no"),
                'land_doc_reg_year' => $this->input->post("land_doc_reg_year"),
                'delivery_mode' => $this->input->post("delivery_mode"),
                'submit_mode' => $submitMode,
                'created_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];

            if (strlen($objId)) {
                $this->necertificates_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/necertificate/fileuploads/' . $objId);
            } else {
                $insert = $this->necertificates_model->insert($inputs);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    redirect('spservices/necertificate/fileuploads/' . $objectId);
                    exit();
                } else {
                    $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                    $this->index();
                } //End of if else
            } //End of if else
        } //End of if else
    }//End of submit()

    public function fileuploads($objId = null) {
        if ($this->checkObjectId($objId)) {            
            $dbFilter = array(
                '_id'=>new ObjectId($objId),
                'applied_by' => $this->apply_by,
                'status' => 'DRAFT'
            );
            $dbRow = $this->necertificates_model->get_row($dbFilter);
            if ($dbRow) {
                $data = array(
                    "service_name" => $this->serviceName,
                    "pageTitle" => "Attached Enclosures for " . $this->serviceName,
                    "obj_id" => $objId,
                    "dbrow" => $dbRow
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('nec/necertificateuploads_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
                redirect('spservices/necertificate/');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id : ' . $objId);
            redirect('spservices/necertificate/');
        }//End of if else
    }//End of fileuploads()

    public function submitfiles() {
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules('land_patta_type', 'Caste certificate', 'required');
        $this->form_validation->set_rules('khajna_receipt_type', 'Gaonbura report', 'required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $landPatta = cifileupload("land_patta");
        $land_patta = $landPatta["upload_status"] ? $landPatta["uploaded_path"] : null;

        $khajnaReceipt = cifileupload("khajna_receipt");
        $khajna_receipt = $khajnaReceipt["upload_status"] ? $khajnaReceipt["uploaded_path"] : null;

        $softCopy = cifileupload("soft_copy");
        $soft_copy = $softCopy["upload_status"] ? $softCopy["uploaded_path"] : null;

        $uploadedFiles = array(
            "land_patta_old" => strlen($land_patta) ? $land_patta : $this->input->post("land_patta_old"),
            "khajna_receipt_old" => strlen($khajna_receipt) ? $khajna_receipt : $this->input->post("khajna_receipt_old"),
            "soft_copy_old" => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        $land_patta_final = strlen($land_patta) ? $land_patta : $this->input->post("land_patta_old");
        $khajna_receipt_final = strlen($khajna_receipt) ? $khajna_receipt : $this->input->post("khajna_receipt_old");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } elseif ((strlen($land_patta_final) < 10) || (strlen($khajna_receipt_final) < 10)) {
            $this->session->set_flashdata('error', 'File(s) cannot be empty ');
            $this->fileuploads($objId);
        } else {
            $data = array(
                'land_patta_type' => $this->input->post("land_patta_type"),
                'khajna_receipt_type' => $this->input->post("khajna_receipt_type"),
                'soft_copy_type' => $this->input->post("soft_copy_type"),
                'land_patta' => $land_patta_final,
                'khajna_receipt' => $khajna_receipt_final,
                'soft_copy' => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
            );
            $this->necertificates_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/necertificate/preview/' . $objId);
        } //End of if else
    }//End of submitfiles()

    public function preview($objId = null) {
        $dbRow = $this->necertificates_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('nec/necertificatepreview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/necertificate/');
        } //End of if else
    }//End of preview()

    public function query($objId = null) {
        if ($this->checkObjectId($objId)) {            
            $filter = array(
                "_id" => new ObjectId($objId),
                "status" => "QS",
                "applied_by" => $this->apply_by
            );
            $dbRow = $this->necertificates_model->get_row($filter);
            if ($dbRow) {

                $data = array(
                    "service_name" => $this->serviceName,
                    "dbrow" => $dbRow
                );
                $data["sro_dist_list"] = $this->sros_model->sro_dist_list();
                $this->load->view('includes/frontend/header');
                $this->load->view('nec/necertificatequery_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/necertificate/');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/necertificate/');
        } //End of if else
    }//End of query()

    public function querysubmit() {
        $objId = $this->input->post("obj_id");
        $rtps_trans_id = $this->input->post("rtps_trans_id");

        $this->form_validation->set_rules('applicant_name', 'Applicant name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('father_name', 'Father', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('applicant_address', 'Address', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|integer|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|valid_email|strip_tags');

        $this->form_validation->set_rules('office_district', 'Office', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('sro_code', 'SRO', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('circle', 'Circle', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mouza', 'Mouza', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('village', 'Village', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('searched_from', 'Searched from', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('searched_to', 'Searched to', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('land_doc_ref_no', 'Ref no.', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('land_doc_reg_year', 'Reg. year', 'trim|required|integer|exact_length[4]|xss_clean|strip_tags');
        //$this->form_validation->set_rules('delivery_mode', 'Delivery mode', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $landPatta = cifileupload("land_patta");
        $land_patta = $landPatta["upload_status"] ? $landPatta["uploaded_path"] : $this->input->post("land_patta_old");

        $khajnaReceipt = cifileupload("khajna_receipt");
        $khajna_receipt = $khajnaReceipt["upload_status"] ? $khajnaReceipt["uploaded_path"] : $this->input->post("khajna_receipt_old");

        $softCopy = cifileupload("soft_copy");
        $soft_copy = $softCopy["upload_status"] ? $softCopy["uploaded_path"] : null;

        $uploadedFiles = array(
            "land_patta_old" => strlen($land_patta) ? $land_patta : $this->input->post("land_patta_old"),
            "khajna_receipt_old" => strlen($khajna_receipt) ? $khajna_receipt : $this->input->post("khajna_receipt_old"),
            "soft_copy_old" => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->query($objId);
        } else {
            $patta_nos = $this->input->post("patta_nos");
            $dag_nos = $this->input->post("dag_nos");
            $land_areas = $this->input->post("land_areas");
            $patta_types = $this->input->post("patta_types");

            $plots = array();
            $myPlots = array();
            if (count($patta_nos)) {
                foreach ($patta_nos as $k => $patta_no) {
                    $plot = array(
                        "patta_no" => $patta_no,
                        "dag_no" => $dag_nos[$k],
                        "land_area" => $land_areas[$k],
                        "patta_type" => $patta_types[$k]
                    );
                    $plots[] = $plot;
                    $myPlot = array(
                        "application_ref_no" => $rtps_trans_id,
                        "pattano" => $patta_no,
                        "daag" => $dag_nos[$k],
                        "land" => $land_areas[$k],
                        "mouza" => $this->input->post("circle"),
                        "village" => $this->input->post("village"),
                        "pattatype" => $patta_types[$k]
                    );
                    $myPlots[] = $myPlot;
                } //End of foreach()        
            } //End of if

            $dbrow = $this->necertificates_model->get_by_doc_id($objId);
            if (count((array) $dbrow)) {
                $processing_history = $dbrow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Query submitted by " . $dbrow->applicant_name,
                    "action_taken" => "Query submitted",
                    "remarks" => "Query submitted by " . $dbrow->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $backupRow = (array) $dbrow;
                unset($backupRow["_id"]);
                $inputs = [
                    'status' => 'QA',
                    'user_type' => $this->slug,
                    'applicant_name' => $this->input->post("applicant_name"),
                    'applicant_gender' => $this->input->post("applicant_gender"),
                    'father_name' => $this->input->post("father_name"),
                    'applicant_address' => $this->input->post("applicant_address"),
                    'mobile' => $this->input->post("mobile"),
                    'email' => $this->input->post("email"),
                    'office_district' => $this->input->post("office_district"),
                    'district_name' => trim($this->input->post("district_name")),
                    'sro_code' => $this->input->post("sro_code"),
                    'circle' => $this->input->post("circle"),
                    'circle_name' => $this->input->post("circle_name"),
                    'mouza' => $this->input->post("mouza"),
                    'mouza_name' => $this->input->post("mouza_name"),
                    'village' => $this->input->post("village"),
                    'village_name' => $this->input->post("village_name"),
                    'plots' => $plots,
                    'searched_from' => $this->input->post("searched_from"),
                    'searched_to' => $this->input->post("searched_to"),
                    'land_doc_ref_no' => $this->input->post("land_doc_ref_no"),
                    'land_doc_reg_year' => $this->input->post("land_doc_reg_year"),
                    'delivery_mode' => $this->input->post("delivery_mode"),
                    'land_patta_type' => $this->input->post("land_patta_type"),
                    'khajna_receipt_type' => $this->input->post("khajna_receipt_type"),
                    'soft_copy_type' => $this->input->post("soft_copy_type"),
                    'land_patta' => strlen($land_patta) ? $land_patta : $this->input->post("land_patta_old"),
                    'khajna_receipt' => strlen($khajna_receipt) ? $khajna_receipt : $this->input->post("khajna_receipt_old"),
                    'soft_copy' => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old"),
                    'updated_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'processing_history' => $processing_history,
                    'data_before_query' => $backupRow
                ];
                //$this->necertificates_model->update_where(['_id' => new ObjectId($objId)], $inputs);

                //update to server
                $landPatta = strlen($land_patta) ? base64_encode(file_get_contents(FCPATH . $land_patta)) : null;
                $khajnaReceipt = strlen($khajna_receipt) ? base64_encode(file_get_contents(FCPATH . $khajna_receipt)) : null;
                //$data["Ref"] = array(
                $data = array(
                    "application_ref_no" => $rtps_trans_id,
                    "sro_code" => $this->input->post("sro_code"),
                    "applicant_name" => $this->input->post("applicant_name"),
                    "applicants_father_name" => $this->input->post("father_name"),
                    "address" => $this->input->post("applicant_address"),
                    "mobile" => $this->input->post("mobile"),
                    "email" => $this->input->post("email"),
                    "searched_from" => $this->input->post("searched_from"),
                    "searched_to" => $this->input->post("searched_to"),
                    "land_doc_no" => $this->input->post("land_doc_ref_no"),
                    "land_doc_year" => $this->input->post("land_doc_reg_year"),
                    "service_mode" => 'G',
                    "circle" => $this->input->post("circle"),
                    "village" => $this->input->post("village"),
                    "plots" => $myPlots,
                    "land_doc" => $landPatta,
                    "id_proof" => $khajnaReceipt
                );

                $data["spId"] = array(
                    "applId" => $objId
                );
                $data["payment_ref_no"] = "PAY1212";
                $data["payment_date"] = date('Y-m-d H:i:s');

                $json_obj = json_encode($data);
                //pre($json_obj);
                $url = $this->config->item('url');
                $curl = curl_init($url . "nec/update_nec.php");
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }
                curl_close($curl);
                if (isset($error_msg)) {
                    die("CURL ERROR : " . $error_msg);
                } elseif ($response) {
                    $response = json_decode($response, true);  //pre($response);
                    if ($response["status"] === "success") {
                        $this->necertificates_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                        $this->session->set_flashdata('success', 'Your application has been successfully updated');
                        redirect('spservices/necertificate/preview/' . $objId);
                    } else {
                        return $this->output
                                        ->set_content_type('application/json')
                                        ->set_status_header(401)
                                        ->set_output(json_encode(array("status" => false)));
                    } //End of if else
                } //End of if
            } else {
                $this->session->set_flashdata('fail', 'Unable to update data!!! Please try again.');
                $this->index();
            } //End of if else
        } //End of if else      
    }//End of querysubmit()

    public function track($objId = null) {
        $dbRow = $this->necertificates_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('nec/necertificatetrack_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/necertificate/');
        } //End of if else
    }//End of track()

    function getID($length) {
        $rtps_trans_id = $this->generateID($length);
        while ($this->necertificates_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        } //End of while()
        return $rtps_trans_id;
    }//End of getID()

    public function generateID($length) {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-NEC/" . date('Y') . "/" . $number;
        return $str;
    }//End of generateID()

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        } //End of if else
    }//End of checkObjectId()
}//End of Necertificate