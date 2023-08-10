<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps {

    private $deptName = "Revenue & Disaster Management Department";
    Private $deptId = "1234";
    private $serviceName = "Appointment booking for Marriage or Deed registration";
    Private $serviceId = "APPOINTMENT_BOOKING";

    public function __construct() {
        parent::__construct();
        $this->load->model('appointmentbooking/appointmentbookings_model');
        $this->load->model('sros_model');       
        
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
        
        if($this->slug === "CSC"){                
            $this->apply_by = $this->session->userId;
        } else {
            $this->apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        }//End of if else
    }//End of __construct()

    public function index($objId = null) {
        //check_application_count_for_citizen();
        $data = array("service_name" => $this->serviceName);
        if ($this->checkObjectId($objId)) {
            $dbFilter = array(
                '$and' => array(
                    array(
                        '_id'=>new ObjectId($objId),
                        'service_data.applied_by' => $this->apply_by,
                        '$or'=>array(
                            array("service_data.appl_status" => "DRAFT"),
                            array("service_data.appl_status" => "QS")
                        )
                    )
                )
            );
            $data["dbrow"] = $this->appointmentbookings_model->get_row($dbFilter);
        } else {
            $data["dbrow"] = false;
        }//End of if else    
        $data["user_type"] = $this->slug;
        $data["sro_dist_list"] = $this->sros_model->sro_dist_list();
        $this->load->view('includes/frontend/header');
        $this->load->view('appointmentbooking_landhub/registration_view', $data);
        $this->load->view('includes/frontend/footer');
    }//End of index()

    public function submit() {
        $this->form_validation->set_rules("applicant_name", "Applicant name", "trim|required|max_length[255]");
        $this->form_validation->set_rules("applicant_gender", "Applicant gender", "trim|required|max_length[255]");
        $this->form_validation->set_rules("fathers_name", "Fathers name", "trim|required|max_length[255]");
        $this->form_validation->set_rules("mobile_number", "Mobile number", "trim|required|exact_length[10]|numeric");
        $this->form_validation->set_rules("email_id", "E-mail", "trim|valid_email|max_length[100]");
        $this->form_validation->set_rules("applicant_address", "Applicant address", "trim|max_length[255]");
        $this->form_validation->set_rules("office_district", "Office district", "trim|required|max_length[255]");
        $this->form_validation->set_rules("sro_code", "Sro code", "trim|required|max_length[255]");
        $this->form_validation->set_rules("appointment_type", "Appointment type", "trim|required|max_length[255]");
        $this->form_validation->set_rules("appointment_date", "Appointment date", "trim|required|max_length[255]");        
          
        $objId = $this->input->post("obj_id");
        $submitMode = $this->input->post("submit_mode"); 
        $appointmentType = json_decode(html_entity_decode($this->input->post("appointment_type")));
        $at_id = $appointmentType->at_id??'';
        if ($at_id == 1) {
            $this->form_validation->set_rules("deed_type", "Deed type", "trim|required|max_length[255]");
        } elseif ($at_id == 2) {
            $this->form_validation->set_rules("appointee_ref_no", "Appointee ref no", "required|max_length[255]");
            $this->form_validation->set_rules("appointee_name", "Appointee name", "required|max_length[255]");
            $this->form_validation->set_rules("appointee_bride_name", "Appointee bride name", "required|max_length[255]");
            $this->form_validation->set_rules("appointee_groom_name", "Appointee groom name", "required|max_length[255]");
        }//End of if else
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->index($objId);
        } else {            
            $form_data = array(
                "user_id" => $this->apply_by,
                "user_type" => $this->slug,
                "applicant_name" => $this->input->post("applicant_name"),
                "applicant_gender" => $this->input->post("applicant_gender"),
                "fathers_name" => $this->input->post("fathers_name"),
                "mobile_number" => (int)$this->input->post("mobile_number"),
                "email_id" => $this->input->post("email_id"),
                "applicant_address" => $this->input->post("applicant_address"),
                "office_district" => $this->input->post("office_district"),
                "district_name" => $this->input->post("district_name"),
                "sro_code" => $this->input->post("sro_code"),
                "office_name" => $this->input->post("office_name"),
                "appointment_type" => $appointmentType,
                "appointment_date" => $this->input->post("appointment_date"),
                "appointee_ref_no" => $this->input->post("appointee_ref_no"),
                "appointee_name" => $this->input->post("appointee_name"),
                "appointee_bride_name" => $this->input->post("appointee_bride_name"),
                "appointee_groom_name" => $this->input->post("appointee_groom_name"),
                "deed_type" => $this->input->post("deed_type")
            );
            
            if (strlen($objId)) { 
                $data = array(
                    "service_data.applied_by" => $this->apply_by,
                    "service_data.district" => $this->input->post("district_name"),
                    "form_data" => $form_data
                ); //pre($data);
                $this->appointmentbookings_model->update_where(['_id' => new ObjectId($objId)], $data);
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/appointmentbooking_landhub/registration/preview/' . $objId);
            } else {
                $service_data = array(
                    "department_id" => $this->deptId,
                    "department_name" => $this->deptName,
                    "submission_location" => $this->deptName,
                    "service_id" => $this->serviceId,
                    "service_name" => $this->serviceName,
                    "applied_by" => $this->apply_by,
                    "appl_ref_no" => $this->getID(7),
                    "created_at" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    "district" => $this->input->post("district_name"),
                    "appl_status" => "DRAFT",
                    "submission_mode" => $this->slug,
                    "service_timeline" => "10 Days"
                );
                $data = array('service_data' => $service_data, 'form_data' => $form_data); //pre($data);                
                $insert = $this->appointmentbookings_model->insert($data);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $this->appointmentbookings_model->update_where(['_id' => new ObjectId($objectId)], array('service_data.appl_id'=>$objectId));
                    if ($submitMode === 'SAVE_NEXT') {
                        $this->session->set_flashdata('success', 'Your application has been successfully saved');
                        redirect('spservices/appointmentbooking_landhub/registration/preview/' . $objectId);
                    } else {
                        $this->session->set_flashdata('success', 'Your application has been successfully drafted');
                        redirect('spservices/appointmentbooking_landhub/registration/preview/' . $objectId);
                    }//End of if else
                } else {
                    $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                    $this->index();
                }//End of if else
            }//End of if else
        }//End of if else
    }//End of submit()
    
    public function preview($objId = null) {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->appointmentbookings_model->get_by_doc_id($objId);
            if (count((array) $dbRow)) {
                $data = array(
                    "service_name" => $this->serviceName,
                    "dbrow" => $dbRow,
                    "user_type" => $this->slug
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('appointmentbooking_landhub/preview_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
                redirect('spservices/appointmentbooking_landhub/');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/appointmentbooking_landhub/');
        }//End of if else
    }//End of preview()

    public function acknowledgement($objId = null) {
        if ($this->checkObjectId($objId)) {
            $this->load->model('services_model');            
            $dbFilter = array(
                '_id' => new ObjectId($objId),
                'form_data.payment_status' => 'PAID'
            );
                    
            $dbRow = $this->appointmentbookings_model->get_row($dbFilter);
            if ($dbRow) {
                $data = array(
                    "service_name" => $this->serviceName,
                    "dbrow" => $dbRow,
                    "user_type" => $this->slug
                );
                $data['service_row'] = $this->services_model->get_row(array("service_id"=> $this->serviceId));
                $data['back_to_dasboard'] = '<a href="' . base_url('spservices/applications/') . '" class="btn btn-primary mb-2 pull-right"  >Back To Dashboard</a>';
                $data['pageTitle'] = "Acknowledgement";
                $this->load->view('includes/frontend/header');
                $this->load->view('appointmentbooking_landhub/acknowledgement_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No acknowledgement found against object id : ' . $objId);
                redirect('spservices/appointmentbooking_landhub/');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/appointmentbooking_landhub/');
        }//End of if else
    }//End of acknowledgement()
    
    public function track($objId = null) {        
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->appointmentbookings_model->get_by_doc_id($objId);
            if(count((array)$dbRow)) {
                $data=array(
                    "service_name"=>$this->serviceName,
                    "dbrow"=>$dbRow,
                    "user_type"=> $this->slug
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('appointmentbooking_landhub/track_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found against object id : '.$objId);
                redirect('spservices/appointmentbooking');
            }//End of if else                
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/appointmentbooking');
        }//End of if else
    }//End of track()

    public function delivered($objId = null) {        
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->appointmentbookings_model->get_by_doc_id($objId);
            if(count((array)$dbRow)) {
                $data=array(
                    "service_name"=>$this->serviceName,
                    "dbrow"=>$dbRow,
                    "user_type"=> $this->slug
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('appointmentbooking_landhub/delivered_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found against object id : '.$objId);
                redirect('spservices/marriageregistration');
            }//End of if else                
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/marriageregistration');
        }//End of if else
    }//End of delivered()
    
    public function get_holidays() {
        $appointmentType = $this->input->post('appointment_type'); //2 for M else D=>Deed
        $data = array("date"=>$appointmentType);
        $json_obj = json_encode($data);
        $this->load->config('spconfig');
        $serverUrl = $this->config->item('url');
        $getUrl = $serverUrl . "reg/holiday.php?jsonbody=".$json_obj; //die($getUrl);
        $curl = curl_init($getUrl );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl); //echo $getUrl.' : '; pre($response);        
        if(isset($error_msg)) {
            echo '<select name="appointment_date" id="appointment_date" class="form-control"><option value="">'."CURL ERROR : ".$error_msg.'</option></select>';
        } elseif ($response) { //pre( $response);
            $response = json_decode($response);?>                   
            <select name="appointment_date" id="appointment_date" class="form-control">
                <option value="">Select a date </option>
                <?php if($response->Result) {
                    foreach($response->Result as $rows) {   
                        echo '<option value="'.$rows->date_code.'">'.date('d-m-Y', strtotime($rows->date_code)).'</option>';
                    }//End of foreach()
                }//End of if ?>
            </select><?php
        } else {
            echo '<select name="appointment_date" id="appointment_date" class="form-control"><option value="">Something went wrong</option></select>';
        }//End of if else
    }//End of get_holidays()
    
    public function get_refnos() {
        $sro_code = $this->input->post('sro_code');//"1267573";
        $data = array("sro_code"=>$sro_code);
        $json_obj = json_encode($data);
        $this->load->config('spconfig');
        $serverUrl = $this->config->item('url');
        $getUrl = $serverUrl . "reg/get_application_ref_nos.php?jsonbody=".$json_obj; //die($getUrl);
        $curl = curl_init($getUrl );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl); //echo $getUrl.' : '; pre($response);        
        if(isset($error_msg)) {
            echo '<select name="appointee_ref_no" id="appointee_ref_no" class="form-control"><option value="">'."CURL ERROR : ".$error_msg.'</option></select>';
        } elseif ($response) { //pre( $response);
            $response = json_decode($response); ?>                   
            <select name="appointee_ref_no" id="appointee_ref_no" class="form-control">
                <option value="">Select a ref. no. </option>
                <?php if($response->Result) { 
                    foreach($response->Result as $rows) {
                        echo '<option value="'.$rows->application_ref_no.'">'.$rows->application_ref_no.'</option>';                   
                    }//End of foreach()
                }//End of if ?>
            </select><?php
        } else {
            echo '<select name="appointee_ref_no" id="appointee_ref_no" class="form-control"><option value="">Something went wrong</option></select>';
        }//End of if else
    }//End of get_refnos()
    
    public function get_mrgdetails() {
        $sro_code = $this->input->post('sro_code');//"1267573";
        $application_ref_no = $this->input->post('application_ref_no');//"RTPS-MRG/2022/7066672";
        $data = array("sro_code"=>$sro_code, "application_ref_no" => $application_ref_no);
        $json_obj = json_encode($data);
        $this->load->config('spconfig');
        $serverUrl = $this->config->item('url');
        $getUrl = $serverUrl . "reg/load_marriage_appointment_details.php?jsonbody=".$json_obj; //die($getUrl);
        $curl = curl_init($getUrl );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl); //echo $getUrl.' : '; pre($response);          
        
        if(isset($error_msg)) {
            //die("CURL ERROR : ".$error_msg);
            $data = array(
                'status' => false, 
                'msg' => 'Error in curl'.$error_msg, 
                'records' => array()
            );
        } elseif ($response) { //pre( $response);
            $response = json_decode($response);
            if($response->Result) { 
                $records = array();
                foreach($response->Result as $rows) {
                    $records[] = array("appointee_name" => $rows->nmappl, "appointee_bride_name" => $rows->brideName, "appointee_groom_name" => $rows->groomName);
                }//End of foreach()
                $data = array(
                    'status' => true, 
                    'msg' => 'Data fetched successfully', 
                    'records' => $records
                );
            } else {
                $data = array(
                    'status' => false, 
                    'msg' => 'No records found', 
                    'records' => array()
                );
            }//End of if else
        } else {
            $data = array(
                'status' => false, 
                'msg' => 'Unable to fetch response', 
                'records' => array()
            );
        }//End of if else
        echo json_encode($data);
    }//End of get_mrgdetails()

    function getID($length) {
        $rtps_trans_id = $this->generateID($length);
        while ($this->appointmentbookings_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        }//End of while()
        return $rtps_trans_id;
    }//End of getID()

    public function generateID($length) {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "TEMP-REG/" . date('Y') . "/" . $number;
        return $str;
    }//End of generateID()

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Registration