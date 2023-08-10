<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Epanjeeyan_test extends frontend {

    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('marriageregistration/marriageregistrations_model');
        $this->load->config('spconfig');
        $this->load->helper('smsprovider');
    }//End of __construct()

    private function show_error() {
        $this->load->view('includes/frontend/header');
        $this->load->view('error');
        $this->load->view('includes/frontend/footer');
    }//End of show_error()

    private function my_transactions() {
        if ($this->session->role) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }//End of if else
    }//End of my_transactions()

    public function paymentmade() {
        $DEPARTMENT_ID = $this->input->post('DEPARTMENT_ID');
        $response = $_POST;
        $this->marriageregistrations_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_status" => $_POST['STATUS'], "pfc_payment_response" => $response));
        if ($_POST['STATUS'] === 'Y') {
            if ($this->checkgrn($DEPARTMENT_ID, true) === $_POST['GRN']) {
                $this->post_data($DEPARTMENT_ID);
            } else {
                $this->marriageregistrations_model->update_row(array('department_id' => $DEPARTMENT_ID), array("pfc_payment_status" => "P", "pfc_payment_response.STATUS" => 'P'));
                $this->show_error();
            }//End of if else
        } else {
            $this->show_error();
        }//End of if else
    }//End of paymentmade()

    public function post_data($department_id = null) {
        $nowTime = date('Y-m-d H:i:s');
        $entryTime = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $dbrow = $this->marriageregistrations_model->get_row(['department_id' => $department_id]);
        if (count((array) $dbrow)) {
            $obj_id = $dbrow->{'_id'}->{'$id'};           
            $rtps_trans_id_temp = $dbrow->rtps_trans_id;
            $rtps_trans_id = "RTPS" . substr($rtps_trans_id_temp, 4);     
            
            $url = $this->config->item('url');
            $postUrl = $url . "mrg/postmarriageapp.php";
            $newStatus = "PR";
            $data_to_update = array(
                'rtps_trans_id' => $rtps_trans_id,
                'status' => $newStatus,
                'payment_status' => 'PAID',
                'payment_time' => $entryTime,
                'submission_date' => $entryTime
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
                "processed_by" => "Payment made by ".$dbrow->applicant_first_name." ".$dbrow->applicant_first_name,
                "action_taken" => "Payment completed",
                "remarks" => "Payment made by ".$dbrow->applicant_first_name." ".$dbrow->applicant_first_name,
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
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
                "mrgAct" => $dbrow->marriage_act->ma_id,
                "applNamePrfx" => $dbrow->applicant_prefix,
                "applFname" => $dbrow->applicant_first_name,
                "applMname" => $dbrow->applicant_middle_name,
                "applLname" => $dbrow->applicant_last_name,
                "applGender" => $dbrow->applicant_gender,
                "Email" => $dbrow->applicant_email_id,
                "Mobile" => $dbrow->applicant_mobile_number,                
                "MTYPEdateOfMarriage" => (strlen($dbrow->ceremony_date)==10)?date("Y-m-d", strtotime($dbrow->ceremony_date)):'',
                "MTYPERelationshipofParties" => ($dbrow->relationship_before === 'YES')?1:2,                
                "brideNamePrfx" => $dbrow->bride_prefix,
                "brideFname" => $dbrow->bride_first_name,
                "brideMname" => $dbrow->bride_middle_name,
                "brideLname" => $dbrow->bride_last_name,
                "brideFatherPrfx" => $dbrow->bride_father_prefix,
                "brideFatherFname" => $dbrow->bride_father_first_name,
                "brideFatherMname" => $dbrow->bride_father_middle_name,
                "brideFatherLname" => $dbrow->bride_father_last_name,
                "brideMotherPrfx" => $dbrow->bride_mother_prefix,
                "brideMotherFname" => $dbrow->bride_mother_first_name,
                "brideMotherMname" => $dbrow->bride_mother_middle_name,
                "brideMotherLname" => $dbrow->bride_mother_last_name,
                "bridevillage" => $dbrow->bride_present_city,
                "brideps" => strlen($dbrow->bride_present_address1)?$dbrow->bride_present_address1:$dbrow->bride_present_ps,
                "bridepo" => strlen($dbrow->bride_present_address2)?$dbrow->bride_present_address2:$dbrow->bride_present_po,
                "bridedistrict" => strlen($dbrow->bride_present_state_name)?$dbrow->bride_present_state_name.'-'.$dbrow->bride_present_district:'',
                "bridestate" => strlen($dbrow->bride_present_state_foreign)?$dbrow->bride_present_state_foreign.'-'.$dbrow->bride_present_country:$dbrow->bride_present_state_name,
                "bridepin" => $dbrow->bride_present_pin,
                "brideLAC" => $dbrow->bride_lac->lac_id??'',
                "brideVillPrmnt" => $dbrow->bride_permanent_city,
                "bridePSPrmnt" => strlen($dbrow->bride_permanent_address1)?$dbrow->bride_permanent_address1:$dbrow->bride_permanent_ps,
                "bridePOPrmnt" => strlen($dbrow->bride_permanent_address2)?$dbrow->bride_permanent_address2:$dbrow->bride_permanent_po,
                "brideDistPrmnt" => $dbrow->bride_permanent_district,
                //"bridedisprmnt" => $dbrow->bride_permanent_district,
                "brideStatePrmnt" => strlen($dbrow->bride_permanent_state_foreign)?$dbrow->bride_permanent_state_foreign.'-'.$dbrow->bride_permanent_country:$dbrow->bride_permanent_state_name,
                "bridePinPrmnt" => $dbrow->bride_permanent_pin,
                "brdLenRes" => array(array("bridePresentMonth" => $dbrow->bride_present_period_months, "bridePresentYr" => $dbrow->bride_present_period_years)),
                "brideDOB" => $dbrow->bride_dob,
                "bridecondi" => $dbrow->bride_status,
                "brideoccu" => str_replace(' ', '', $dbrow->bride_occupation),
                "brideMobile" => $dbrow->bride_mobile_number,
                "brideEmail" => $dbrow->bride_email_id,
                //"brideAadhaar" => '',
                "brideCategory" => $dbrow->bride_category,
                "bridePWD" => $dbrow->bride_disability,
                "bridePWDifYes" => ($dbrow->bride_disability_type === 'Differently Abled')?'Differentlyabled':str_replace(' ', '', $dbrow->bride_disability_type),
                "brideParentIncome" => $this->get_income_id($dbrow->bride_dependent_income),
                "bgroomNamePrfx" => $dbrow->groom_prefix,
                "bgroomFname" => $dbrow->groom_first_name,
                "bgroomMname" => $dbrow->groom_middle_name,
                "bgroomLname" => $dbrow->groom_last_name,
                "bgroomFatherPrfx" => $dbrow->groom_father_prefix,
                "bgroomFatherFname" => $dbrow->groom_father_first_name,
                "bgroomFatherMname" => $dbrow->groom_father_middle_name,
                "bgroomFatherLname" => $dbrow->groom_father_last_name,
                "bgroomMotherPrfx" => $dbrow->groom_mother_prefix,
                "bgroomMotherFname" => $dbrow->groom_mother_first_name,
                "bgroomMotherMname" => $dbrow->groom_mother_middle_name,
                "bgroomMotherLname" => $dbrow->groom_mother_last_name,
                "bgroomvillage" => $dbrow->groom_present_city,
                "bgroomps" => strlen($dbrow->groom_present_address1)?$dbrow->groom_present_address1:$dbrow->groom_present_ps,
                "bgroompo" => strlen($dbrow->groom_present_address2)?$dbrow->groom_present_address2:$dbrow->groom_present_po,
                "bgroomdistrict" => strlen($dbrow->groom_present_district)?$dbrow->groom_present_district:'',
                "bgroomstate" => strlen($dbrow->groom_present_state_foreign)?$dbrow->groom_present_state_foreign.'-'.$dbrow->groom_present_country:$dbrow->groom_present_state_name,
                "bgroompin" => $dbrow->groom_present_pin,
                "grmVillPrmnt" => $dbrow->groom_permanent_city,
                "grmPSPrmnt" => strlen($dbrow->groom_permanent_address1)?$dbrow->groom_permanent_address1:$dbrow->groom_permanent_ps,
                "grmPOPrmnt" => strlen($dbrow->groom_permanent_address2)?$dbrow->groom_permanent_address2:$dbrow->groom_permanent_po,
                "grmDistPrmnt" => $dbrow->groom_permanent_district,
                "grmStatePrmnt" => strlen($dbrow->groom_permanent_state_foreign)?$dbrow->groom_permanent_state_foreign.'-'.$dbrow->groom_permanent_country:$dbrow->groom_permanent_state_name,
                "grmPinPrmnt" => $dbrow->groom_permanent_pin,
                "grmLenResYear" => array(array("groomPresentMonth" => $dbrow->groom_present_period_months, "groomPresentYr" => $dbrow->groom_present_period_years)),
                "bgroomDOB" => $dbrow->groom_dob,
                "bgroomcondi" => $dbrow->groom_status,
                "bgroomoccu" => str_replace(' ', '', $dbrow->groom_occupation),
                "bgroomMobile" => $dbrow->groom_mobile_number,
                "bgroomEmail" => $dbrow->groom_email_id,
                //"bgroomAadhaar" => "",//$dbrow->email,
                "groomLAC" => $dbrow->groom_lac->lac_id??'',
                "bgroomCategory" => $dbrow->groom_category,
                "bgroomPWD" => $dbrow->groom_disability,
                "bgroomPWDifYes" => ($dbrow->groom_disability_type === 'Differently Abled')?'Differentlyabled':str_replace(' ', '', $dbrow->groom_disability_type),
                "application_ref_no" => $rtps_trans_id,
                "spId" => array("applId" => $obj_id),
                "payment_ref_no" => time(),
                "payment_date" => date("Y-m-d"),
                "brideChildren" => $brideChildren,
                "brideDependent" => $brideDependents,
                "bgroomChildren" => $groomChildren,
                "bgroomDependent" => $groomDependents,
                "IdProof_groom" => array("encl" => $groomIdproof),
                "IdProof_bride" => array("encl" => $brideIdproof),
                "AgeProof_groom" => array("encl" => $groomAgeproof),
                "AgeProof_bride" => array("encl" => $brideAgeproof),
                "AddressProof_bride" => array("encl" => $bridePresentaddressproof),
                "AddressProof_groom" => array("encl" => $groomPresentaddressproof),
                "Paddressproof_bride" => array("encl" => $bridePermanentaddressproof),
                "Paddressproof_groom" => array("encl" => $groomPermanentaddressproof),
                //"brideConsentforAuth" => array("encl"=>$dbrow->),
                //"bgroomConsentforAuth" => array("encl"=>$groom),
                "declCrtfctbyParties" => array("encl" => $declarationCertificate),
                "marriageCard" => array("encl" => $marriageCard),
                "bride_sign" => array("encl" => $brideSign),
                "groom_sign" => array("encl" => $groomSign),
                "MarriageNotice" => array("encl" => $marriageNotice),
                "dtappl"=>date('Y-m-d')
            );            
            $json_obj = json_encode($data); //pre($data);
            
            $curl = curl_init($postUrl);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }//End of if
            curl_close($curl); // echo $postUrl." : "; pre($response);
            if(isset($error_msg)) {
                die("Error in server communication ".$error_msg);
            } elseif ($response) {
                $response = json_decode($response); // echo $postUrl." : "; pre($response);
                $res_status = $response->status??null;
                if ($res_status === "success") {
                    $data_to_update['processing_history'] = $processing_history;
                    $this->marriageregistrations_model->update($obj_id, $data_to_update);
                    
                    //Sending submission SMS
                    $sms = array(
                        "mobile" => (int)$dbrow->applicant_mobile_number,
                        "applicant_name" => $dbrow->applicant_first_name." ".$dbrow->applicant_last_name,
                        "service_name" => 'Application for Marriage Registration',
                        "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                        "app_ref_no" => $rtps_trans_id,
                        "rtps_trans_id" => $rtps_trans_id
                    );
                    sms_provider("submission", $sms);
                    redirect('spservices/marriageregistration/preview/acknowledgement/' . $obj_id);
                } else {
                    pre($response);
                    return $this->output
                                    ->set_content_type('application/json')
                                    ->set_status_header(401)
                                    ->set_output(json_encode(array("status" => false)));
                }//End of if else
            }//End of if
        } else {
            $this->session->set_flashdata('error', 'No records found against  : ' . $department_id);
            redirect('spservices/marriageregistration/');
        }//End of if else
    }//End of post_data()

    public function checkgrn($DEPARTMENT_ID = null, $check = false, $verify = false) { // TODO: need to check which are params to update
        $transaction_data = $this->marriageregistrations_model->get_row(array('department_id' => $DEPARTMENT_ID));
        if ($DEPARTMENT_ID) {
            $OFFICE_CODE = $transaction_data->payment_params->OFFICE_CODE;
            $am1 = isset($transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT) ? $transaction_data->payment_params->TOTAL_NON_TREASURY_AMOUNT : 0;
            $am2 = isset($transaction_data->payment_params->CHALLAN_AMOUNT) ? $transaction_data->payment_params->CHALLAN_AMOUNT : 0;
            $AMOUNT = $am1 + $am2;
            $string_field = "DEPARTMENT_ID=" . $DEPARTMENT_ID . "&OFFICE_CODE=" . $OFFICE_CODE . "&AMOUNT=" . $AMOUNT;
            $url = $this->config->item('egras_grn_cin_url');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 3);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $string_field);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            // curl_setopt ($ch, CURLOPT_CAINFO, dirname(FILE)."/123.assam.gov.in.crt");
            curl_setopt($ch, CURLOPT_FAILONERROR, FALSE);
            curl_setopt($ch, CURLOPT_NOBODY, false);
            $result = curl_exec($ch);
            curl_close($ch);
            $res = explode("$", $result); //pre($res);
            if ($check) {
                return isset($res[4]) ? $res[4] : '';
            }
            if ($res) {
                $STATUS = isset($res[16]) ? $res[16] : '';
                $GRN = isset($res[4]) ? $res[4] : '';
                //  var_dump($STATUS);var_dump($GRN);die;
                //if($STATUS === "Y"){
                $this->marriageregistrations_model->update_row(
                        array('department_id' => $DEPARTMENT_ID),
                        array(
                            // "status"=>"WS",
                            "pfc_payment_response.GRN" => $GRN,
                            "pfc_payment_response.AMOUNT" => isset($res[6]) ? $res[6] : '',
                            "pfc_payment_response.PARTYNAME" => isset($res[18]) ? $res[18] : '',
                            "pfc_payment_response.TAXID" => isset($res[20]) ? $res[20] : '',
                            "pfc_payment_response.DEPARTMENT_ID" => isset($res[2]) ? $res[2] : '',
                            "pfc_payment_response.BANKNAME" => isset($res[22]) ? $res[22] : '',
                            "pfc_payment_response.BANKCODE" => isset($res[8]) ? $res[8] : '',
                            "pfc_payment_response.ENTRY_DATE" => isset($res[24]) ? $res[24] : '',
                            "pfc_payment_response.STATUS" => $STATUS,
                            "pfc_payment_response.PRN" => isset($res[12]) ? $res[12] : '',
                            "pfc_payment_response.TRANSCOMPLETIONDATETIME" => isset($res[14]) ? $res[14] : '',
                            "pfc_payment_response.BANKCIN" => isset($res[10]) ? $res[10] : '',
                            'pfc_payment_status' => $STATUS
                        )
                );

                if ($verify) {
                    return array(
                        'GRN' => $GRN,
                        'STATUS' => $STATUS
                    );
                }
            }
        }
        $this->session->set_flashdata('pay_message', 'Payment grn has been checked and updated');
        $this->my_transactions();
    }//End of checkgrn()

    public function cin_response() {
        if (!empty($_POST)) {
            if (!empty($_POST['DEPARTMENT_ID'])) {
                $DEPARTMENT_ID = $_POST['DEPARTMENT_ID'];
                $STATUS = $_POST['STATUS'];
                $BANKCIN = $_POST['BANKCIN'];
                $this->marriageregistrations_model->update_row(array('department_id' => $DEPARTMENT_ID), array(
                    "pfc_payment_response.BANKCIN" => $BANKCIN,
                    "pfc_payment_response.STATUS" => $STATUS,
                    "pfc_payment_response.TAXID" => $_POST['TAXID'],
                    "pfc_payment_response.PRN" => $_POST['PRN'],
                    "pfc_payment_response.TRANSCOMPLETIONDATETIME" => $_POST['TRANSCOMPLETIONDATETIME'],
                    'pfc_payment_status' => $STATUS
                ));
                if($STATUS === 'Y'){
                    $this->post_data($DEPARTMENT_ID);
                }//End of if
            }//End of if
        }//End of if
        $this->session->set_flashdata('pay_message', 'Payment has been checked and updated');
        $this->my_transactions();
    }//End of cin_response()
    
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
}//End of Epanjeeyan_test