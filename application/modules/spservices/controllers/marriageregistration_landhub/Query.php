<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Query extends Rtps {

    private $serviceName = "Application for Marriage Registration";
    Private $serviceId = "MARRIAGE_REGISTRATION";

    public function __construct() {
        parent::__construct();
        $this->load->model('marriageregistration/marriageregistrations_model');
        $this->load->model('sros_model');       
        $this->load->config('spconfig');
        
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
    }//End of __construct()
    
    public function index($objId=null) {
        if ($this->checkObjectId($objId)) {
            if($this->slug === "CSC"){                
                $apply_by = $this->session->userId;
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            }//End of if else
            
            $filter = array("_id"=> new ObjectId($objId), "status"=>"QS",'applied_by' => $apply_by);
            $dbRow = $this->marriageregistrations_model->get_row($filter);
            if($dbRow) {
                $data = array("service_name" => $this->serviceName, "dbrow"=>$dbRow);
                $data["sro_dist_list"] = $this->sros_model->sro_dist_list();
                $this->load->view('includes/frontend/header');
                $this->load->view('marriageregistration_landhub/applicantdetails_view',$data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found in query mode against object id : '.$objId);
                redirect('spservices/marriageregistration_landhub/');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/marriageregistration_landhub/');
        }//End of if else
    }//End of index()
    

    public function submit($objId=null) {
        //$objId = $this->input->post('obj_id');
        $nowTime = date('Y-m-d H:i:s');
        if ($this->checkObjectId($objId)) {
            $filter = array("_id"=> new ObjectId($objId));
            $dbrow = $this->marriageregistrations_model->get_row($filter);
            if (count((array) $dbrow)) {
                $obj_id = $dbrow->{'_id'}->{'$id'};           
                $rtps_trans_id = $dbrow->rtps_trans_id;    
                
                $backupRow = (array)$dbrow;
                unset($backupRow["_id"]);

                $url = $this->config->item('url');
                $postUrl = $url . "mrg/updatemarriage.php";
                $newStatus = "QA";
                $data_to_update = array(
                    'rtps_trans_id' => $rtps_trans_id,
                    'status' => $newStatus,
                    'data_before_query' => $backupRow
                );

                $bride_children = $dbrow->bride_children;
                $brideChildren = array();
                if (count($bride_children)) {
                    foreach ($bride_children as $child) {
                        $brideChild = array(
                            "application_ref_no" => $rtps_trans_id,
                            "brideEarlierFN" => $child->bride_child_first_name,
                            "brideEarlierMN" => $child->bride_child_middle_name,
                            "brideEarlierLN" => $child->bride_child_last_name,
                            "brideEarlierDOB" => date("Y-m-d", strtotime($child->bride_child_dob)),
                            "brideEarlierPA" => $child->bride_child_address
                        );
                        $brideChildren[] = $brideChild;
                    }//End of foreach()
                }//End of if

                $bride_dependents = $dbrow->bride_dependents;
                $brideDependents = array();
                if (count($bride_dependents)) {
                    foreach ($bride_dependents as $dependent) {
                        $brideDependent = array(
                            "application_ref_no" => $rtps_trans_id,
                            "brideDependentFN" => $dependent->bride_dependent_first_name,
                            "brideDependentMN" => $dependent->bride_dependent_middle_name,
                            "brideDependentLN" => $dependent->bride_dependent_last_name,
                            "brideDependentDOB" => date("Y-m-d", strtotime($dependent->bride_dependent_dob)),
                            "brideDependentPA" => $dependent->bride_dependent_address
                        );
                        $brideDependents[] = $brideDependent;
                    }//End of foreach()
                }//End of if

                $groom_children = $dbrow->groom_children;
                $groomChildren = array();
                if (count($groom_children)) {
                    foreach ($groom_children as $child) {
                        $groomChild = array(
                            "application_ref_no" => $rtps_trans_id,
                            "groomEarlierFN" => $child->groom_child_first_name,
                            "groomEarlierMN" => $child->groom_child_middle_name,
                            "groomEarlierLN" => $child->groom_child_last_name,
                            "groomEarlierDOB" => date("Y-m-d", strtotime($child->groom_child_dob)),
                            "groomEarlierPA" => $child->groom_child_address
                        );
                        $groomChildren[] = $groomChild;
                    }//End of foreach()
                }//End of if

                $groom_dependents = $dbrow->groom_dependents;
                $groomDependents = array();
                if (count($groom_dependents)) {
                    foreach ($groom_dependents as $dependent) {
                        $groomDependent = array(
                            "application_ref_no" => $rtps_trans_id,
                            "groomDependentFN" => $dependent->groom_dependent_first_name,
                            "groomDependentMN" => $dependent->groom_dependent_middle_name,
                            "groomDependentLN" => $dependent->groom_dependent_last_name,
                            "groomDependentDOB" => date("Y-m-d", strtotime($dependent->groom_dependent_dob)),
                            "groomDependentPA" => $dependent->groom_dependent_address
                        );
                        $groomDependents[] = $groomDependent;
                    }//End of foreach()
                }//End of if

                $processing_history = $dbrow->processing_history??array();
                $processing_history[] = array(
                    "processed_by" => "Query replied by ".$dbrow->applicant_first_name." ".$dbrow->applicant_first_name,
                    "action_taken" => "Query replied",
                    "remarks" => "Query replied by ".$dbrow->applicant_first_name." ".$dbrow->applicant_first_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                );

                $brideIdproof = $dbrow->bride_idproof ? base64_encode(file_get_contents(FCPATH . $dbrow->bride_idproof)) : null;
                $groomIdproof = $dbrow->groom_idproof ? base64_encode(file_get_contents(FCPATH . $dbrow->groom_idproof)) : null;
                $brideAgeproof = $dbrow->bride_ageproof ? base64_encode(file_get_contents(FCPATH . $dbrow->bride_ageproof)) : null;
                $marriageNotice = $dbrow->marriage_notice ? base64_encode(file_get_contents(FCPATH . $dbrow->marriage_notice)) : null;
                $groomAgeproof = $dbrow->groom_ageproof ? base64_encode(file_get_contents(FCPATH . $dbrow->groom_ageproof)) : null;
                $bridePresentaddressproof = $dbrow->bride_presentaddressproof ? base64_encode(file_get_contents(FCPATH . $dbrow->bride_presentaddressproof)) : null;
                $bridePermanentaddressproof = $dbrow->bride_permanentaddressproof ? base64_encode(file_get_contents(FCPATH . $dbrow->bride_permanentaddressproof)) : null;
                $groomPresentaddressproof = $dbrow->groom_presentaddressproof ? base64_encode(file_get_contents(FCPATH . $dbrow->groom_presentaddressproof)) : null;
                $groomPermanentaddressproof = $dbrow->groom_permanentaddressproof ? base64_encode(file_get_contents(FCPATH . $dbrow->groom_permanentaddressproof)) : null;
                $brideSign = $dbrow->bride_sign ? base64_encode(file_get_contents(FCPATH . $dbrow->bride_sign)) : null;
                $groomSign = $dbrow->groom_sign ? base64_encode(file_get_contents(FCPATH . $dbrow->groom_sign)) : null;
                $declarationCertificate = $dbrow->declaration_certificate ? base64_encode(file_get_contents(FCPATH . $dbrow->declaration_certificate)) : null;
                $marriageCard = $dbrow->marriage_card ? base64_encode(file_get_contents(FCPATH . $dbrow->marriage_card)) : null;
                $data = array(
                    "sro_code" => $dbrow->sro_code,
                    "oldnew" => $dbrow->marriage_type->mt_id,
                    
                    //Unused
                    "mrgAct" => $dbrow->marriage_act->ma_id,
                    "applNamePrfx" => $dbrow->applicant_prefix,
                    "applFname" => $dbrow->applicant_first_name,
                    "applMname" => $dbrow->applicant_middle_name,
                    "applLname" => $dbrow->applicant_last_name,
                    "applGender" => $dbrow->applicant_gender,
                    "Email" => $dbrow->applicant_email_id,
                    "Mobile" => $dbrow->applicant_mobile_number, 
                    "MTYPEdateOfMarriage" => (strlen($dbrow->ceremony_date)==10)?date("Y-m-d", strtotime($dbrow->ceremony_date)):'',
                    "relationship_of_parties" => ($dbrow->relationship_before === 'YES')?1:2,
                    
                    "bridesalutation" => $dbrow->bride_prefix,
                    "bridename" => $dbrow->bride_first_name,
                    "bridemiddlename" => $dbrow->bride_middle_name,
                    "bridelastname" => $dbrow->bride_last_name,
                    "bridefather_salutation" => $dbrow->bride_father_prefix,
                    "bridefather" => $dbrow->bride_father_first_name,
                    "bridefather_middlename" => $dbrow->bride_father_middle_name,
                    "bridefather_lastname" => $dbrow->bride_father_last_name,
                    "bridemother_salutation" => $dbrow->bride_mother_prefix,
                    "bridemother_firstname" => $dbrow->bride_mother_first_name,
                    "bridemother_middlename" => $dbrow->bride_mother_middle_name,
                    "bridemother_lastname" => $dbrow->bride_mother_last_name,
                    "bridetown" => $dbrow->bride_present_city,
                    "brideps" => strlen($dbrow->bride_present_address1)?$dbrow->bride_present_address1:$dbrow->bride_present_ps,
                    "bridepo" => strlen($dbrow->bride_present_address2)?$dbrow->bride_present_address2:$dbrow->bride_present_po,
                    "bridedistrict" => $dbrow->bride_present_district,
                    "bridestate" => strlen($dbrow->bride_present_state_foreign)?$dbrow->bride_present_state_foreign.'-'.$dbrow->bride_present_country:$dbrow->bride_present_state_name,
                    "bridepin" => $dbrow->bride_present_pin,
                    "bride_lac" => $dbrow->bride_lac->lac_id??'',
                    "brideTownPrmnt" => $dbrow->bride_permanent_city,
                    "bridePSPrmnt" => strlen($dbrow->bride_permanent_address1)?$dbrow->bride_permanent_address1:$dbrow->bride_permanent_ps,
                    "bridePOPrmnt" => strlen($dbrow->bride_permanent_address2)?$dbrow->bride_permanent_address2:$dbrow->bride_permanent_po,
                    "brideDistPrmnt" => $dbrow->bride_permanent_district,
                    "brideStatePrmnt" => strlen($dbrow->bride_permanent_state_foreign)?$dbrow->bride_permanent_state_foreign.'-'.$dbrow->bride_permanent_country:$dbrow->bride_permanent_state_name,
                    "bridePinPrmnt" => $dbrow->bride_permanent_pin,                    
                    "brdLenRes" => array(array("bride_res_month" => $dbrow->bride_present_period_months, "bride_res_year" => $dbrow->bride_present_period_years)),
                    "bridedob" => $dbrow->bride_dob,
                    "bridecondi" => $dbrow->bride_status,
                    "brideoccu" => str_replace(' ', '', $dbrow->bride_occupation),
                    "bridemobile" => $dbrow->bride_mobile_number,
                    "brideemail" => $dbrow->bride_email_id,
                    //"brideAadhaar" => '',
                    "bridecategory" => $dbrow->bride_category,
                    "bridedisability_flag" => $dbrow->bride_disability,
                    "bridedisability_type" => ($dbrow->bride_disability_type === 'Differently Abled')?'Differentlyabled':str_replace(' ', '', $dbrow->bride_disability_type),
                    "bridefamily_income" => $this->get_income_id($dbrow->bride_dependent_income),
                    "bgroom_salutation" => $dbrow->groom_prefix,
                    "bgroomname" => $dbrow->groom_first_name,
                    "bgroom_middlename" => $dbrow->groom_middle_name,
                    "bgroom_lastname" => $dbrow->groom_last_name,
                    "bgroomfather_salutation" => $dbrow->groom_father_prefix,
                    "bgroomfather" => $dbrow->groom_father_first_name,
                    "bgroomfather_middlename" => $dbrow->groom_father_middle_name,
                    "bgroomfather_lastname" => $dbrow->groom_father_last_name,
                    "bgroommother_salutation" => $dbrow->groom_mother_prefix,
                    "bgroommother_firstname" => $dbrow->groom_mother_first_name,
                    "bgroommother_middlename" => $dbrow->groom_mother_middle_name,
                    "bgroommother_lastname" => $dbrow->groom_mother_last_name,
                    "bgroomtown" => $dbrow->groom_present_city,
                    "bgroomps" => strlen($dbrow->groom_present_address1)?$dbrow->groom_present_address1:$dbrow->groom_present_ps,
                    "bgroompo" => strlen($dbrow->groom_present_address2)?$dbrow->groom_present_address2:$dbrow->groom_present_po,
                    "bgroomdistrict" => strlen($dbrow->groom_present_district)?$dbrow->groom_present_district:'',
                    "bgroomstate" => strlen($dbrow->groom_present_state_foreign)?$dbrow->groom_present_state_foreign.'-'.$dbrow->groom_present_country:$dbrow->groom_present_state_name,
                    "bgroompin" => $dbrow->groom_present_pin,
                    "grmTownPrmnt" => $dbrow->groom_permanent_city,
                    "grmPSPrmnt" => strlen($dbrow->groom_permanent_address1)?$dbrow->groom_permanent_address1:$dbrow->groom_permanent_ps,
                    "grmPOPrmnt" => strlen($dbrow->groom_permanent_address2)?$dbrow->groom_permanent_address2:$dbrow->groom_permanent_po,
                    "grmDistPrmnt" => $dbrow->groom_permanent_district,
                    "grmStatePrmnt" => strlen($dbrow->groom_permanent_state_foreign)?$dbrow->groom_permanent_state_foreign.'-'.$dbrow->groom_permanent_country:$dbrow->groom_permanent_state_name,
                    "grmPinPrmnt" => $dbrow->groom_permanent_pin,
                    "grmLenRes" => array(array("bgroom_res_month" => $dbrow->groom_present_period_months, "bgroom_res_year" => $dbrow->groom_present_period_years)),
                    "bgroomdob" => $dbrow->groom_dob,
                    "bgroomcondi" => $dbrow->groom_status,
                    "bgroomoccu" => str_replace(' ', '', $dbrow->groom_occupation),
                    "bgroom_mobile" => $dbrow->groom_mobile_number,
                    "bgroom_email" => $dbrow->groom_email_id,
                    //"bgroomAadhaar" => "",//$dbrow->email,
                    "bgroom_lac" => $dbrow->groom_lac->lac_id??'',
                    "bgroom_category" => $dbrow->groom_category,
                    "bgroom_disability_flag" => $dbrow->groom_disability,
                    "bgroom_disability_type" => ($dbrow->groom_disability_type === 'Differently Abled')?'Differentlyabled':str_replace(' ', '', $dbrow->groom_disability_type),
                    "application_ref_no" => $rtps_trans_id,
                    "spId" => array("applId" => $obj_id),
                    
                    //Unused
                    "brideChildren" => $brideChildren,
                    "brideDependent" => $brideDependents,
                    "bgroomChildren" => $groomChildren,
                    "bgroomDependent" => $groomDependents,
                    
                    "IdProof_groom" => $groomIdproof,
                    "IdProof_bride" => $brideIdproof,
                    "AgeProof_groom" => $groomAgeproof,
                    "AgeProof_bride" => $brideAgeproof,
                    "AddressProof_bride" => $bridePresentaddressproof,
                    "AddressProof_groom" => $groomPresentaddressproof,
                    "Paddressproof_bride" => $bridePermanentaddressproof,
                    "Paddressproof_groom" => $groomPermanentaddressproof,
                    "declCrtfctbyParties" => $declarationCertificate,
                    "marriageCard" => $marriageCard,
                    "bride_sign" => $brideSign,
                    "groom_sign" => $groomSign,
                    "MarriageNotice" => $marriageNotice,
                    "dtappl"=>date('Y-m-d')
                );            
                $json_obj = json_encode($data); //pre($json_obj);
                //$this->output->set_content_type('application/json')->set_output($json_obj); die;

                $curl = curl_init($postUrl);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }//End of if
                curl_close($curl);
                //pre($postUrl." => ".$response);
                if(isset($error_msg)) {
                    die("Error in server communication ".$error_msg);
                } elseif ($response) {
                    $response = json_decode($response);
                    $res_status = $response->status??null;
                    if ($res_status === "success") {
                        $data_to_update['processing_history'] = $processing_history;
                        $this->marriageregistrations_model->update($obj_id, $data_to_update);
                        $this->session->set_flashdata('pay_message', 'Your application has been succeessfully updated');
                        if($this->session->role) {
                            redirect(base_url('iservices/admin/my-transactions'));
                        } else {
                            redirect(base_url('iservices/transactions'));
                        }//End of if else
                    } else {
                        $this->session->set_flashdata('error', 'Error in posting data');
                        redirect('spservices/marriageregistration_landhub/');
                    }//End of if else
                }//End of if
            } else {
                $this->session->set_flashdata('error', 'No records found against');
                redirect('spservices/marriageregistration_landhub/');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/marriageregistration_landhub/');
        }//End of if else
    }//End of submit()

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        }//End of if else
    }//End of checkObjectId()
    
    function get_income_id($par){
        switch ($par) {
            case 'Rs. 1 to Rs. 50000':
                $action = 1;
                break;
            case 'Rs. 50001 to Rs. 100000':
                $action = 2;
                break;
            case 'Rs. 100001 to Rs. 200000':
                $action = 2;
                break;
            case 'Rs. 200001 to Rs. 300000':
                $action = 4;
                break;
            case 'Rs. 300001 to Rs. 400000':
                $action = 5;
                break;
            case 'Rs. 400001 to Rs. 500000':
                $action = 6;
                break;
            case 'Rs. 500000 or more':
                $action = 7;
                break;
            default:
                $action =0;
                break;
        }//End of switch
        return $action;
    } // End of get_income_id()
}//End of Query