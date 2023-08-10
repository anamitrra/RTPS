<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\UTCDateTime;
class Trackapplicationstatus extends frontend {
        
    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->library('AES');
        $this->encryption_key = $this->config->item("encryption_key");                
        $this->load->model('necertificates_model');
    }//End of __construct()
    
    public function byrefno() {
        $filter = file_get_contents('php://input');
        if (!empty($filter)) {
            $data = json_decode($filter, true);
            if ($data['secret'] === "rtpsapi#!@") {
                $applRefNo = trim($data['app_ref_no']);
                $serviceCode = substr($applRefNo,5, 3);
                switch($serviceCode) {
                    case "NEC":
                        $this->get_nec_data($applRefNo);
                        break;
                    case "CRC":
                        $this->get_nec_data($applRefNo);
                        break;
                    case "MRG":
                        $this->get_mrg_data($applRefNo);
                        break;
                    case "MCC":
                        $this->get_mcc_data($applRefNo);
                        break;
                    default:
                        $this->get_other_data($applRefNo);
                }//End of switch()                
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array("status" => false, "message" => "Unauthorized")));
            }//End of if else
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "No data found")));
        }//End of if else
    }//End of byrefno()
        
    private function get_nec_data($applRefNo) {
        $dbFilter = array(
            'rtps_trans_id' => $applRefNo
        );
        $dbRow = $this->necertificates_model->get_row($dbFilter);
        if($dbRow) { //die("here");
            $this->load->helper('appstatus');            
            $obj_id = $dbRow->_id->{'$id'};
            $certificate = $dbRow->certificate ?? '';
            $payment_status = $dbRow->payment_status ?? '';
            $ppr_grn = isset($dbRow->pfc_payment_response->GRN) ? $dbRow->pfc_payment_response->GRN : '';
            $ppr_status = isset($dbRow->pfc_payment_response->STATUS) ? $dbRow->pfc_payment_response->STATUS : '';
            $appl_status = $dbRow->status ?? '';
            
            $initiated_data = array(
                "appl_ref_no" => $dbRow->rtps_trans_id,
                "submission_date" => isset($dbRow->submission_date)?format_mongo_date($dbRow->submission_date, 'Y-m-d H:i:s'):'',
                "status_code" => $appl_status,
                "payment_status" =>  $payment_status,//PAID
                "status" => getstatusname($appl_status),
                "applicant_name" => $dbRow->applicant_name,
                "service_name" => $dbRow->service_name,
                "service_timeline" => 30//'30 days'
            );
            $execution_data = array();
            if(isset($dbRow->processing_history)) {
                foreach ($dbRow->processing_history as $prow) {
                    $processRow = array(
                        "processed_by" => $prow->processed_by,
                        "action_taken" => $prow->action_taken,
                        "remarks" => $prow->remarks??'',
                        "processing_time" => format_mongo_date($prow->processing_time, 'Y-m-d H:i:s')
                    );
                    $execution_data[] = $processRow;
                }//End of foreach()
            }//End of if
            
            /*** START action_data ***/
            /*$action_data = array(
                'application_url' => base_url('spservices/necertificate/index/'.$obj_id),//URL for Complete Your Application
                'preview_url' => base_url("spservices/necertificate/preview/".$obj_id),// URL for Application Preview
                'acknowledgement_url' => base_url("spservices/applications/acknowledgement/".$obj_id),// URL for Acknowledgement view
                'query_url' => base_url('spservices/necertificate/query/'.$obj_id),// URL for Reply to query
                'querypayment_url' => base_url('spservices/necertificate/querypayment/'.$obj_id),// URL for Reply to query
                'delivery_url' => (strlen($certificate) ? base_url($certificate) : '#'),// URL for Certificate Download/View
                'trackstatus_url' => base_url('spservices/necertificate/track/'.$obj_id),// URL for Track status
                'makepayment_url' => base_url('spservices/necpayment/initiate/'.$obj_id),// URL for Make payment
                'cinresponse_url' => base_url('spservices/necresponse/cin_response')// URL for Verify Payment
            );*/
                        
            if ($payment_status == "PAID") {
                $action_data["trackstatus_url"] = base_url('spservices/necertificate/track/'.$obj_id); // URL for Track status
                $action_data["acknowledgement_url"] = base_url("spservices/applications/acknowledgement/".$obj_id); // URL for Acknowledgement view
                $action_data["preview_url"] = base_url("spservices/necertificate/preview/".$obj_id); // URL for Application Preview
            } elseif(((strlen($ppr_grn)) && ($ppr_status === "P")) || ((strlen($ppr_grn)) && ($ppr_status === ""))) {
                $action_data["cinresponse_url"] = base_url('spservices/necresponse/cin_response'); // URL for Verify Payment
            } else {
                $action_data["makepayment_url"] = base_url('spservices/necpayment/initiate/'.$obj_id); // URL for Make payment
            }//End of if else
            
            if (strtolower($appl_status) == "draft") {
                $action_data["application_url"] = base_url('spservices/necertificate/index/'.$obj_id); //URL for Complete Your Application
            } elseif ($appl_status == "QS") {
                $action_data["query_url"] = base_url('spservices/necertificate/query/'.$obj_id); // URL for Reply to query
            } elseif ($appl_status == "D") {
                $action_data["delivery_url"] = (strlen($certificate) ? base_url($certificate) : '#'); // URL for Certificate Download/View
            }//End of if
            /*** END action_data ***/
            
            $data = array(
                "status" => true,
                "data" => array(
                    "initiated_data" => $initiated_data,
                    "execution_data" => $execution_data,
                    "action_data" => $action_data
                )
            );                    
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($data));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no. ".$applRefNo)));
        }//End of if else        
    }//End of get_nec_data()
    
    private function get_mrg_data($applRefNo) {
        $dbFilter = array(
            'service_id' => 'MARRIAGE_REGISTRATION',
            'rtps_trans_id' => $applRefNo
        );
        $dbRow = $this->necertificates_model->get_row($dbFilter);
        if($dbRow) {            
            $this->load->helper('appstatus');
            $obj_id = $dbRow->_id->{'$id'};
            $certificate = $dbRow->certificate ?? '';
            $payment_status = $dbRow->payment_status ?? '';
            $ppr_grn = isset($dbRow->pfc_payment_response->GRN) ? $dbRow->pfc_payment_response->GRN : '';
            $ppr_status = isset($dbRow->pfc_payment_response->STATUS) ? $dbRow->pfc_payment_response->STATUS : '';
            $appl_status = $dbRow->status ?? '';
            $applicant_name = '';
            if(isset($dbRow->applicant_first_name) && strlen($dbRow->applicant_first_name)) {
                $applicant_name = $applicant_name.' '.$dbRow->applicant_first_name;
            }
            if(isset($dbRow->applicant_middle_name) && strlen($dbRow->applicant_middle_name)) {
                $applicant_name = $applicant_name.' '.$dbRow->applicant_middle_name;
            }
            if(isset($dbRow->applicant_last_name) && strlen($dbRow->applicant_last_name)) {
                $applicant_name = $applicant_name.' '.$dbRow->applicant_last_name;
            }
            $initiated_data = array(
                "appl_ref_no" => $dbRow->rtps_trans_id,
                "submission_date" => isset($dbRow->submission_date)?format_mongo_date($dbRow->submission_date, 'Y-m-d H:i:s'):'',
                "status_code" => $appl_status,
                "payment_status" =>  $payment_status, //PAID
                "status" => getstatusname($appl_status),
                "applicant_name" => $applicant_name,
                "service_name" => $dbRow->service_name,
                "service_id" => $dbRow->service_id,
                "service_timeline" => 30//'30 days'
            );
            $execution_data = array();
            if(isset($dbRow->processing_history)) {
                foreach ($dbRow->processing_history as $prow) {
                    $processRow = array(
                        "processed_by" => $prow->processed_by,
                        "action_taken" => $prow->action_taken,
                        "remarks" => $prow->remarks??'',
                        "processing_time" => format_mongo_date($prow->processing_time, 'Y-m-d H:i:s')
                    );
                    $execution_data[] = $processRow;
                }//End of foreach()
            }//End of if
            
            /*** START action_data ***/
            $action_data = array();            
            if ($payment_status == "PAID") {
                $action_data["trackstatus_url"] = base_url('spservices/marriageregistration/track/index/'.$obj_id); // URL for Track status
                $action_data["acknowledgement_url"] = base_url("spservices/marriageregistration/preview/acknowledgement/".$obj_id); // URL for Acknowledgement view
                $action_data["preview_url"] = base_url("spservices/marriageregistration/preview/index/".$obj_id); // URL for Application Preview
            } elseif(((strlen($ppr_grn)) && ($ppr_status === "P")) || ((strlen($ppr_grn)) && ($ppr_status === ""))) {
                $action_data["cinresponse_url"] = base_url('spservices/marriageregistration/paymentresponse/cin_response'); // URL for Verify Payment
            } elseif (strtolower($appl_status) !== "draft") {
                $action_data["makepayment_url"] = base_url('spservices/marriageregistration/payment/initiate/'.$obj_id); // URL for Make payment
            }//End of if else
            
            if (strtolower($appl_status) == "draft") {
                $action_data["application_url"] = base_url('spservices/marriageregistration/applicantdetails/index/'.$obj_id); //URL for Complete Your Application
            } elseif ($appl_status == "QS") {
                $action_data["query_url"] = base_url('spservices/marriageregistration/query/index/'.$obj_id); // URL for Reply to query
            } elseif ($appl_status == "D") {
                $action_data["delivery_url"] = base_url('spservices/marriageregistration/track/delivered/'.$obj_id); // URL for Certificate Download/View
            }//End of if
            /*** END action_data ***/
            
            $data = array(
                "status" => true,
                "data" => array(
                    "initiated_data" => $initiated_data,
                    "execution_data" => $execution_data,
                    "action_data" => $action_data
                )                    
            );                    
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($data));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no. ".$applRefNo)));
        }//End of if else        
    }//End of get_mrg_data()
    
    private function get_mcc_data($applRefNo) {
        $dbFilter = array(
            'service_data.service_id' => 'MCC',
            'service_data.appl_ref_no' => $applRefNo
        );
        $dbRow = $this->necertificates_model->get_row($dbFilter);
        if($dbRow) {                    
            $this->load->helper('minoritycertificate');
            $obj_id = $dbRow->_id->{'$id'};
            $payment_status = $dbRow->form_data->payment_status??'';
            $appl_status = $dbRow->service_data->appl_status;
            $certificate = $dbRow->execution_data[0]->official_form_details->output_certificate??'';
            $ppr_grn = isset($dbRow->form_data->pfc_payment_response->GRN)?$dbRow->form_data->pfc_payment_response->GRN:'';
            $ppr_status = isset($dbRow->form_data->pfc_payment_response->STATUS)?$dbRow->form_data->pfc_payment_response->STATUS:'';
                                
            $initiated_data = array(
                "appl_ref_no" => $applRefNo,
                "submission_date" => isset($dbRow->service_data->submission_date)?format_mongo_date($dbRow->service_data->submission_date, 'Y-m-d H:i:s'):'',    // Format:  YYYY-mm-dd HH:ii:ss (24 hr)
                "status_code" => $appl_status,
                "payment_status" =>  $dbRow->form_data->payment_status,//PAYMENT_COMPLETED
                "status" => getappstatus($appl_status),
                "applicant_name" => $dbRow->form_data->applicant_name,
                "service_name" => $dbRow->service_data->service_name,
                "service_id" => $dbRow->service_data->service_id,
                "service_timeline" => (int)$dbRow->service_data->service_timeline
            );
            $execution_data = array();
            if(isset($dbRow->execution_data)) {
                foreach ($dbRow->execution_data as $prow) {
                    $processingTime = (isset($prow->task_details->executed_time) && (strlen($prow->task_details->executed_time)))?format_mongo_date($prow->task_details->executed_time, 'Y-m-d H:i:s'):'';
                    $processRow = array(
                        "processed_by" => $prow->task_details->user_type,
                        "action_taken" => $prow->task_details->task_name,
                        "remarks" => $prow->task_details->remarks??'',
                        "processing_time" => $processingTime
                    );
                    $execution_data[] = $processRow;
                }//End of foreach()
            }//End of if
            
            /*** START action_data ***/
            $action_data["trackstatus_url"] = base_url('spservices/minoritycertificates/track/status?id='.$applRefNo); // URL for Track status
            if ($payment_status == "PAID") {
                $action_data["acknowledgement_url"] = base_url("spservices/minority-certificate-payment-acknowledgement/".$obj_id); // URL for Acknowledgement view
                $action_data["preview_url"] = base_url("spservices/minority-certificate-preview/".$obj_id); // URL for Application Preview
            } elseif(((strlen($ppr_grn)) && ($ppr_status === "P")) || ((strlen($ppr_grn)) && ($ppr_status === ""))) {
                $action_data["cinresponse_url"] = base_url('spservices/minoritycertificates/paymentresponse/cin_response'); // URL for Verify Payment
            } else {
                $action_data["makepayment_url"] = base_url('spservices/minority-certificate-payment/'.$obj_id); // URL for Make payment
            }//End of if else
            
            if (strtolower($appl_status) == "draft") {
                $action_data["application_url"] = base_url('spservices/minority-certificate/'.$obj_id); //URL for Complete Your Application
            } elseif ($appl_status == "QS") {
                $action_data["query_url"] = base_url('spservices/minority-certificate-query/'.$obj_id); // URL for Reply to query
            } elseif ($appl_status == "D") {
                $action_data["delivery_url"] = (strlen($certificate)?base_url($certificate):'#'); // URL for Certificate Download/View
            }//End of if
            /*** END action_data ***/
            
            $data = array(
                "status" => true,
                "data" => array(
                    "initiated_data" => $initiated_data,
                    "execution_data" => $execution_data,
                    "action_data" => $action_data
                )
            );                    
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($data));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no. ".$applRefNo)));
        }//End of if else
    }//End of get_mcc_data()
    
    private function get_other_data($applRefNo) {
        $dbFilter = array(
            //'service_data.service_id' => 'MCC',
            'service_data.appl_ref_no' => $applRefNo
        );
        $dbRow = $this->necertificates_model->get_row($dbFilter);        
        if($dbRow) {                    
            if((property_exists($dbRow, "service_data") && $dbRow->service_data->service_id ==="apdcl1") || (property_exists($dbRow->form_data, "service_plus_data") && $dbRow->form_data->service_plus_data == true)){
                return $this->output
                  ->set_content_type('application/json')
                  ->set_status_header(200)
                  ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no. ".$applRefNo)));
            } else {
                $this->load->helper('appstatus');
                $initiated_data = array(
                    "appl_ref_no" => $dbRow->service_data->appl_ref_no??'',
                    "submission_date" => isset($dbRow->service_data->submission_date)?format_mongo_date($dbRow->service_data->submission_date, 'Y-m-d H:i:s'):'',    // Format:  YYYY-mm-dd HH:ii:ss (24 hr)
                    "status_code" => $dbRow->service_data->appl_status??'',
                    "payment_status" =>  $dbRow->form_data->payment_status??'',//PAID
                    "status" => isset($dbRow->service_data->appl_status)?getstatusname($dbRow->service_data->appl_status):'',
                    "applicant_name" => $dbRow->form_data->applicant_name??'',
                    "service_name" => $dbRow->service_data->service_name??'',
                    "service_id" => $dbRow->service_data->service_id??'',
                    "service_timeline" => isset($dbRow->service_data->service_timeline)?(int)$dbRow->service_data->service_timeline:''
                );
                $execution_data = array();
                if(isset($dbRow->processing_history)) {
                    foreach ($dbRow->processing_history as $prow) {
                        $processRow = array(
                            "processed_by" => $prow->processed_by??'',
                            "action_taken" => $prow->action_taken??'',
                            "remarks" => $prow->remarks??'',
                            "processing_time" => isset($prow->processing_time)?format_mongo_date($prow->processing_time, 'Y-m-d H:i:s'):''
                        );
                        $execution_data[] = $processRow;
                    }//End of foreach()
                }//End of if
                $data = array(
                    "status" => true,
                    "data" => array(
                        "initiated_data" => $initiated_data,
                        "execution_data" => $execution_data,
                    )
                );                    
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($data));
            }//End of if else                
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no. ".$applRefNo)));
        }//End of if else        
    }//End of get_other_data()
}//End of Trackapplicationstatus