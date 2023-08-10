<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Trackallapps extends frontend {
    
    private $applRefNo, $userType, $mobile, $pfcId, $cscId, $fromDate, $endDate, $statusArr;
    private $serverToken = "080042cad6356ad5dc0a720c18b53b8e53d4c274";
        
    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->library('AES');
        $this->encryption_key = $this->config->item("encryption_key");                
        $this->load->model('necertificates_model');
        $this->load->model('services_model');
    }//End of __construct()
    
    public function index() {
        exit('No direct script access allowed');
    }//End of index()

    public function get_records() {
        $filter = file_get_contents('php://input');
        if (!empty($filter)) {
            $bearerToken = $this->getBearerToken();
            if ($this->serverToken === $bearerToken) {
                $data = json_decode($filter, true);
                $this->applRefNo = isset($data['app_ref_no'])?trim($data['app_ref_no']):'';
                $this->userType = isset($data['user_type'])?trim($data['user_type']):'';
                $this->mobile = isset($data['mobile'])?trim($data['mobile']):'';
                $this->pfcId = isset($data['pfc_id'])?trim($data['pfc_id']):'';
                $this->cscId = isset($data['csc_id'])?trim($data['csc_id']):'';
                $from_date = isset($data['from_date'])?trim($data['from_date']):'';
                $end_date = isset($data['end_date'])?trim($data['end_date']):'';
                $status = isset($data['status'])?trim($data['status']):'';
                if($status === "D") {
                    $this->statusArr = array(
                        '$or'=>array(
                            ['status'=>'D'],
                            ['service_data.appl_status'=>'D']
                        )
                    );
                } else {
                    $this->statusArr = array(
                        '$or'=>array(
                            ['status'=>array('$exists' => true, '$ne' => 'D')],
                            ['service_data.appl_status'=>array('$exists' => true, '$ne' => 'D')]
                        )
                    );
                }//End of if else
                                
                if((strlen($from_date) ==10) || strlen($end_date) ==10) {
                    $this->fromDate = date('Y-m-d',strtotime($from_date));
                    $this->endDate = date('Y-m-d', strtotime($end_date . ' +1 day'));
                } else {
                    $this->endDate = date("Y-m-d", strtotime("+ 1 day")); //date("Y-m-d");
                    $this->fromDate = date('Y-m-d',strtotime('-30 days',strtotime($this->endDate)));
                }//End of if else
                if(!in_array($this->userType, ["USER","CSC","PFC"]) && (strlen($this->applRefNo)==0)) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(400)
                        ->set_output(json_encode(array("status" => false, "message" => "Please provide a valid user type(USER,CSC or PFC)","data" => [])));
                } elseif(($this->userType === "USER") && (strlen($this->mobile) != 10)) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(400)
                        ->set_output(json_encode(array("status" => false, "message" => "Please provide a valid mobile number","data" => [])));
                } elseif(($this->userType === "PFC") && (strlen($this->pfcId) < 5)) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(400)
                        ->set_output(json_encode(array("status" => false, "message" => "Please provide a valid PFC ID","data" => [])));
                } elseif(($this->userType === "CSC") && (strlen($this->cscId) < 5)) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(400)
                        ->set_output(json_encode(array("status" => false, "message" => "Please provide a valid CSC ID","data" => [])));
                } else {
                    if(strlen($this->applRefNo)) {
                        if($this->userType === "SA") {
                            $dbFilter['$or'] = array(
                                ['rtps_trans_id' => $this->applRefNo], 
                                ['service_data.appl_ref_no' => $this->applRefNo]
                            );
                        } else {
                            $dbFilter = $this->get_dbfilter();
                        }//End of if else                            
                        $dbRow = $this->necertificates_model->get_row($dbFilter);
                        if($dbRow) {
                            $serviceId_old = $dbRow->service_id??'';
                            $serviceId_New = $dbRow->service_data->service_id??'';
                            $dataSingle = array();
                            if(strlen($serviceId_old)) {
                                $dataSingle[] = $this->get_service_wise_data($serviceId_old, $dbRow);
                            } elseif(strlen($serviceId_New)) {
                                $dataSingle[] = $this->get_service_wise_data($serviceId_New, $dbRow);
                            } else {
                                $dataSingle[] = array("status" => false, "message" => "No records found!!!","data" => []);
                            }//End of if else
                            return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(array("status" => true,"data" => $dataSingle)));                            
                        } else {
                            return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(400)
                                ->set_output(json_encode(array("status" => false, "message" => "No records found!!!","data" => [])));
                        }//End of if else
                    } else {
                        $filter = $this->get_dbfilter(); //echo json_encode($filter); die;
                        $dbRows = $this->necertificates_model->get_rows($filter);
                        $this->get_allrows($dbRows);
                    }//End of if else                  
                }//End of if else             
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(401)
                    ->set_output(json_encode(array("status" => false, "message" => "Authorization token does not matched","data" => [])));
            }//End of if else
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array("status" => false, "message" => "No data passed","data" => [])));
        }//End of if else
    }//End of get_records()
    
    private function get_dbfilter() {
        if(($this->userType === 'USER') && strlen($this->applRefNo)) {
            $dbFilter = array(
                '$and' => array(
                    array(
                        '$or'=>array(
                            ['rtps_trans_id' => $this->applRefNo], 
                            ['service_data.appl_ref_no' => $this->applRefNo]
                        )
                    ),
                    array(
                        '$or'=>array(                                        
                            ['mobile' => $this->mobile], 
                            ['applicant_mobile_number' => $this->mobile],                
                            ['mobile_number' => $this->mobile],                
                            ['form_data.mobile_number' => $this->mobile],                
                            ['form_data.mobile' => $this->mobile]
                        )
                    )
                )
            );
        } elseif(($this->userType === 'CSC') && strlen($this->applRefNo)) {
            $dbFilter = array(
                '$and' => array(
                    array(
                        '$or'=>array(
                            ['rtps_trans_id' => $this->applRefNo], 
                            ['service_data.appl_ref_no' => $this->applRefNo]
                        )
                    ),
                    array(
                        '$or'=>array(                                        
                            ['applied_by' => $this->cscId], 
                            ['service_data.applied_by' => $this->cscId]
                        )
                    )
                )
            );
        } elseif(($this->userType === 'PFC') && strlen($this->applRefNo)) {
            $dbFilter = array(
                '$and' => array(
                    array(
                        '$or'=>array(
                            ['rtps_trans_id' => $this->applRefNo], 
                            ['service_data.appl_ref_no' => $this->applRefNo]
                        )
                    ),
                    array(
                        '$or'=>array(                                        
                            ['applied_by' => new ObjectId($this->pfcId)], 
                            ['service_data.applied_by' => $this->pfcId], 
                            ['service_data.applied_by' => new ObjectId($this->pfcId)]
                        )
                    )
                )
            );
        } elseif(($this->userType === 'USER') && strlen($this->mobile)) {        
            $dbFilter = array(
                '$and' => array(
                    array(
                        '$or'=>array(
                            ['mobile' => $this->mobile], 
                            ['applicant_mobile_number' => $this->mobile],                
                            ['mobile_number' => $this->mobile],                
                            ['form_data.mobile_number' => $this->mobile],                
                            ['form_data.mobile' => $this->mobile]
                        )
                    ),
                    array(
                        '$or'=>array(
                            ['created_at'=>array(
                                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($this->fromDate) * 1000), 
                                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime($this->endDate) * 1000)
                            )],
                            ['form_data.created_at'=>array(
                                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($this->fromDate) * 1000), 
                                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime($this->endDate) * 1000)
                            )],
                            ['service_data.created_at'=>array(
                                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($this->fromDate) * 1000), 
                                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime($this->endDate) * 1000)
                            )]
                        )
                    ),
                    $this->statusArr
                )
            );            
        } elseif(($this->userType === 'CSC') && strlen($this->cscId)) {
            $dbFilter = array(
                '$and' => array(
                    array(
                        '$or'=>array(
                            ['applied_by' => $this->cscId], 
                            ['service_data.applied_by' => $this->cscId]
                        )
                    ),
                    array(
                        '$or'=>array(
                            ['created_at'=>array(
                                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($this->fromDate) * 1000), 
                                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime($this->endDate) * 1000)
                            )],
                            ['form_data.created_at'=>array(
                                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($this->fromDate) * 1000), 
                                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime($this->endDate) * 1000)
                            )],
                            ['service_data.created_at'=>array(
                                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($this->fromDate) * 1000), 
                                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime($this->endDate) * 1000)
                            )]
                        )
                    ),
                    $this->statusArr
                )
            );
        } elseif(($this->userType === 'PFC') && strlen($this->pfcId)) {
            $dbFilter = array(
                '$and' => array(
                    array(
                        '$or'=>array(
                            ['applied_by' => new ObjectId($this->pfcId)], 
                            ['service_data.applied_by' => $this->pfcId], 
                            ['service_data.applied_by' => new ObjectId($this->pfcId)]
                        )
                    ),
                    array(
                        '$or'=>array(
                            ['created_at'=>array(
                                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($this->fromDate) * 1000), 
                                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime($this->endDate) * 1000)
                            )],
                            ['form_data.created_at'=>array(
                                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($this->fromDate) * 1000), 
                                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime($this->endDate) * 1000)
                            )],
                            ['service_data.created_at'=>array(
                                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($this->fromDate) * 1000), 
                                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime($this->endDate) * 1000)
                            )]
                        )
                    ),
                    $this->statusArr
                )
            );
        } else {
            $dbFilter = array();
        }//End of if else        
        return $dbFilter;
    }//End of get_filter()
    
    private function get_allrows($dbRows) {
        if($dbRows) {
            $data = array();
            foreach($dbRows as $dbRow) {
                if(isset($dbRow->service_id) && strlen($dbRow->service_id)) {
                    $data_format1 = $this->get_service_wise_data($dbRow->service_id, $dbRow);
                    if($data_format1){
                        $data[] = $data_format1;
                    }                    
                } elseif(isset($dbRow->service_data->service_id) && strlen($dbRow->service_data->service_id)) {
                    $data_format2 = $this->get_service_wise_data($dbRow->service_data->service_id, $dbRow);
                    if($data_format2){
                        $data[] = $data_format2;
                    }
                }//End of if else
            }//End of foreach()
            
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => true,"data" => $data)));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array("status" => false, "message" => "No records found","data" => [])));
        }//End of if else
    }//End of get_allrows()
    
    private function get_service_wise_data($serviceId, $dbRow) {
        switch($serviceId) {
            case "NECERTIFICATE":
                $sData = $this->get_data_nec($dbRow);
                break;
            case "CRCPY":
                $sData = $this->get_data_nec($dbRow);
                break;
            case "MARRIAGE_REGISTRATION":
                $sData = $this->get_data_mrg($dbRow);
                break;
            case "MCC":
                $sData = $this->get_data_mcc($dbRow);
                break;
            default:
                $sData = $this->get_data_other($dbRow);
        }//End of switch()
        return $sData;
    }//End of get_service_wise_data()
    
    private function get_action_urls($serviceId, $obj_id, $appl_status, $payment_status, $ppr_grn, $ppr_status, $certificate) {
        $serviceRow = $this->services_model->get_row(array("service_id" => $serviceId));
        if ($serviceRow) {
            $registration_url = $serviceRow->registration_url??'';
            $edit_url = $serviceRow->edit_url??'';
            $query_reply_url = $serviceRow->query_reply_url??'';
            $preview_url = $serviceRow->preview_url??'';
            $acknowledgement_url = $serviceRow->acknowledgement_url??'';
            $track_status_url = $serviceRow->track_status_url??'';
            $make_payment_url = $serviceRow->make_payment_url??'';
            $verify_payment_url = $serviceRow->verify_payment_url??'';
            $query_payment_url = $serviceRow->query_payment_url??'';
            $delivered_url = $serviceRow->delivered_url??'';
        } else {                
            $registration_url = '';
            $edit_url = '';
            $query_reply_url = '';
            $preview_url = '';
            $acknowledgement_url = '';
            $track_status_url = '';
            $make_payment_url = '';
            $verify_payment_url = '';
            $query_payment_url = '';
            $delivered_url = '';
        }//End of if else
        $action_data = array();
        if (strtolower($appl_status) == "payment_initiated") {
            $action_data[] = array(
                "name" => "Complete Payment",
                "url" => strlen($make_payment_url)?base_url('spservices/'.$make_payment_url.'/'.$obj_id):'#'
            );
        } elseif (($appl_status === "PR") || ($appl_status === "submitted") || ($payment_status === "PAID") || ($payment_status === "PAYMENT_COMPLETED")) {
            $action_data[] = array(
                "name" => "Track status",
                "url" => strlen($track_status_url)?base_url('spservices/'.$track_status_url.'/'.$obj_id):'#'
            );
            $action_data[] = array(
                "name" => "Acknowledgement",
                "url" => strlen($acknowledgement_url)?base_url('spservices/'.$acknowledgement_url.'/'.$obj_id):'#'
            );
            $action_data[] = array(
                "name" => "Preview",
                "url" => strlen($preview_url)?base_url('spservices/'.$preview_url.'/'.$obj_id):'#'
            );
        } elseif(((strlen($ppr_grn)) && ($ppr_status === "P")) || ((strlen($ppr_grn)) && (strlen($ppr_status) == 0))) {
            $action_data[] = array(
                "name" => "Verify Payment",
                "url" => strlen($verify_payment_url)?base_url('spservices/'.$verify_payment_url):'#'
            );
        } elseif (($appl_status === "QS") || ($appl_status === "QUERY_ARISE")) {
            $action_data[] = array(
                "name" => "Track status",
                "url" => strlen($track_status_url)?base_url('spservices/'.$track_status_url.'/'.$obj_id):'#'
            );
            $action_data[] = array(
                "name" => "Acknowledgement",
                "url" => strlen($acknowledgement_url)?base_url('spservices/'.$acknowledgement_url.'/'.$obj_id):'#'
            );
            $action_data[] = array(
                "name" => "Reply to query",
                "url" => strlen($query_reply_url)?base_url('spservices/'.$query_reply_url.'/'.$obj_id):'#'
            );
        } elseif ($appl_status === "FRS") {
            $action_data[] = array(
                "name" => "Track status",
                "url" => strlen($track_status_url)?base_url('spservices/'.$track_status_url.'/'.$obj_id):'#'
            );
            $action_data[] = array(
                "name" => "Acknowledgement",
                "url" => strlen($acknowledgement_url)?base_url('spservices/'.$acknowledgement_url.'/'.$obj_id):'#'
            );
            $action_data[] = array(
                "name" => "Make query payment",
                "url" => strlen($query_payment_url)?base_url('spservices/'.$query_payment_url.'/'.$obj_id):'#'
            );
        } elseif ($appl_status === "D") {
            $action_data[] = array(
                "name" => "Track status",
                "url" => strlen($track_status_url)?base_url('spservices/'.$track_status_url.'/'.$obj_id):'#'
            );
            $action_data[] = array(
                "name" => "Acknowledgement",
                "url" => strlen($acknowledgement_url)?base_url('spservices/'.$acknowledgement_url.'/'.$obj_id):'#'
            );
            $action_data[] = array(
                "name" => strlen($certificate) ?"Doenload certificate" : "Application Delivered",
                "url" => strlen($certificate) ? base_url($certificate) : '#'
            );
        } else {
            $action_data[] = array(
                "appl_status" => $appl_status,
                "name" => "Complete your application",
                "url" => strlen($edit_url)?base_url('spservices/'.$edit_url.'/'.$obj_id):'#'
            );
        }//End of if else
        return $action_data;
    }//End of get_action_urls()
    
    private function get_data_nec($dbRow) {
        if($dbRow) {
            $this->load->helper('appstatus');
            $obj_id = $dbRow->_id->{'$id'};
            $serviceId = $dbRow->service_id ?? '';
            $appl_status = $dbRow->status ?? '';
            $payment_status = $dbRow->payment_status ?? '';
            $ppr_grn = isset($dbRow->pfc_payment_response->GRN) ? $dbRow->pfc_payment_response->GRN : '';
            $ppr_status = isset($dbRow->pfc_payment_response->STATUS) ? $dbRow->pfc_payment_response->STATUS : '';
            $certificate_nec = $dbRow->certificate ?? '';
            $certificate_path = $dbRow->certificate_path ?? '';      
            if(strlen($certificate_nec)) {
                $certificate = $certificate_nec;
            } elseif(strlen($certificate_path)) {
                $certificate = $certificate_path;
            } else {
                $certificate = '';
            }//End of if else
            $created_at = $dbRow->created_at??'';
            $submission_date = $dbRow->submission_date??'';

            $initiated_data = array(
                "rtps_trans_id" => $dbRow->rtps_trans_id,
                "appl_ref_no" => $dbRow->rtps_trans_id,
                "createdDtm" => strlen($created_at)?format_mongo_date($created_at, 'Y-m-d H:i:s'):'',
                "submission_date" => strlen($submission_date)?format_mongo_date($submission_date, 'Y-m-d H:i:s'):'', 
                "status_code" => $appl_status,
                "payment_status" =>  $payment_status,//PAID
                "status" => getstatusname($appl_status),
                "applicant_name" => $dbRow->applicant_name,
                "applied_by" => $dbRow->applied_by??'',
                "mobile" => $dbRow->mobile,
                "service_name" => $dbRow->service_name,
                "service_id" => $dbRow->service_id,
                "payment_params" => $dbRow->payment_params??'',
                "pfc_payment_response" => $dbRow->pfc_payment_response??'',
                "service_timeline" => 30
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
            
            $data_nec = array(
                "initiated_data" => $initiated_data,
                "execution_data" => $execution_data,
                "action_data" => $this->get_action_urls($serviceId, $obj_id, $appl_status, $payment_status, $ppr_grn, $ppr_status, $certificate)
            );
            return $data_nec;            
        } else {
            return false;
        }//End of if else        
    }//End of get_data_nec()
    
    private function get_data_mrg($dbRow) {
        if($dbRow) {            
            $this->load->helper('appstatus');
            $obj_id = $dbRow->_id->{'$id'};
            $serviceId = $dbRow->service_id ?? '';
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
            $created_at = $dbRow->created_at??'';
            $submission_date = $dbRow->submission_date??'';
            
            $initiated_data = array(
                "rtps_trans_id" => $dbRow->rtps_trans_id,
                "appl_ref_no" => $dbRow->rtps_trans_id,
                "createdDtm" => strlen($created_at)?format_mongo_date($created_at, 'Y-m-d H:i:s'):'',
                "submission_date" => strlen($submission_date)?format_mongo_date($submission_date, 'Y-m-d H:i:s'):'', 
                "status_code" => $appl_status,
                "payment_status" =>  $payment_status, //PAID
                "status" => getstatusname($appl_status),
                "applicant_name" => $applicant_name,
                "mobile" => $dbRow->applicant_mobile_number,
                "applied_by" => $dbRow->applied_by??'',
                "service_name" => $dbRow->service_name,
                "service_id" => $dbRow->service_id,
                "payment_params" => $dbRow->payment_params??'',
                "pfc_payment_response" => $dbRow->pfc_payment_response??'',
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
                                    
            $data_mrg = array(
                "initiated_data" => $initiated_data,
                "execution_data" => $execution_data,
                "action_data" => $this->get_action_urls($serviceId, $obj_id, $appl_status, $payment_status, $ppr_grn, $ppr_status, $certificate)
            );                    
            return $data_mrg;            
        } else {
            return false;
        }//End of if else 
    }//End of get_data_mrg()
    
    private function get_data_mcc($dbRow) {
        if($dbRow) {                    
            $this->load->helper('minoritycertificate');
            $obj_id = $dbRow->_id->{'$id'};
            $serviceId = $dbRow->service_data->service_id ?? '';
            $payment_status = $dbRow->form_data->payment_status??'';//PAYMENT_COMPLETED
            $appl_status = $dbRow->service_data->appl_status;
            $certificate = $dbRow->execution_data[0]->official_form_details->output_certificate??'';
            $ppr_grn = isset($dbRow->form_data->pfc_payment_response->GRN)?$dbRow->form_data->pfc_payment_response->GRN:'';
            $ppr_status = isset($dbRow->form_data->pfc_payment_response->STATUS)?$dbRow->form_data->pfc_payment_response->STATUS:'';
            $created_at = $dbRow->service_data->created_at??'';
            $submission_date = $dbRow->service_data->submission_date??'';
                                
            $initiated_data = array(
                "rtps_trans_id" => $dbRow->service_data->appl_ref_no,
                "appl_ref_no" => $dbRow->service_data->appl_ref_no,
                "createdDtm" => strlen($created_at)?format_mongo_date($created_at, 'Y-m-d H:i:s'):'',
                "submission_date" => strlen($submission_date)?format_mongo_date($submission_date, 'Y-m-d H:i:s'):'',    // Format:  YYYY-mm-dd HH:ii:ss (24 hr)
                "status_code" => $appl_status,
                "payment_status" =>  $payment_status,
                "status" => getappstatus($appl_status),
                "applicant_name" => $dbRow->form_data->applicant_name,
                "mobile" => $dbRow->form_data->mobile_number,
                "applied_by" => $dbRow->service_data->applied_by??'',
                "service_name" => $dbRow->service_data->service_name,
                "service_id" => $dbRow->service_data->service_id,
                "payment_params" => $dbRow->form_data->payment_params??'',
                "pfc_payment_response" => $dbRow->form_data->pfc_payment_response??'',
                "service_timeline" => (int)$dbRow->service_data->service_timeline??''
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
                        
            $data_mcc = array(
                "initiated_data" => $initiated_data,
                "execution_data" => $execution_data,
                "action_data" => $this->get_action_urls($serviceId, $obj_id, $appl_status, $payment_status, $ppr_grn, $ppr_status, $certificate)
            );                    
            return $data_mcc;          
        } else {
            return false;
        }//End of if else 
    }//End of get_data_mcc()
    
    private function get_data_other($dbRow) {       
        if($dbRow) {                    
            $obj_id = $dbRow->_id->{'$id'};
            $serviceId = $dbRow->service_data->service_id ?? '';
            $appl_status = $dbRow->service_data->appl_status??'';
            $payment_status = $dbRow->payment_status ?? '';
            $ppr_grn = isset($dbRow->pfc_payment_response->GRN) ? $dbRow->pfc_payment_response->GRN : '';
            $ppr_status = isset($dbRow->pfc_payment_response->STATUS) ? $dbRow->pfc_payment_response->STATUS : '';
            $certificate = $dbRow->form_data->certificate ?? '';
            
            if((property_exists($dbRow->form_data, "service_plus_data") && $dbRow->form_data->service_plus_data == true)){
                /*return $this->output
                  ->set_content_type('application/json')
                  ->set_status_header(200)
                  ->set_output(json_encode(array("status" => false, "message" => "No records found")));*/
            } else {
                $this->load->helper('appstatus');
                $createdAtService = $dbRow->service_data->created_at??'';
                $createdAtForm = $dbRow->form_data->created_at??'';
                if(strlen($createdAtService)) {
                    $created_at = format_mongo_date($createdAtService, 'Y-m-d H:i:s');
                } elseif(strlen($createdAtForm)) {
                    $created_at = format_mongo_date($createdAtForm, 'Y-m-d H:i:s');
                } else {
                    $created_at = 'NA';
                }//End of if else
                
                $mob = $dbRow->form_data->mobile??'';
                $mobNo = $dbRow->form_data->mobile_number??'';
                if(strlen($mob)) {
                    $mobile_number = $mob;
                } elseif(strlen($mobNo)) {
                    $mobile_number = $mobNo;
                } else {
                    $mobile_number = '';
                }//End of if else
                
                $submission_date = $dbRow->service_data->submission_date??'';
                $initiated_data = array(
                    "rtps_trans_id" => $dbRow->service_data->appl_ref_no??'',
                    "appl_ref_no" => $dbRow->service_data->appl_ref_no??'',
                    "createdDtm" => $created_at,
                    "submission_date" => strlen($submission_date)?format_mongo_date($submission_date, 'Y-m-d H:i:s'):'',    // Format:  YYYY-mm-dd HH:ii:ss (24 hr)
                    "status_code" => $appl_status,
                    "payment_status" =>  $payment_status,
                    "status" => strlen($appl_status)?getstatusname($appl_status):'',
                    "applicant_name" => $dbRow->form_data->applicant_name??'',
                    "mobile" => $mobile_number,
                    "applied_by" => $dbRow->service_data->applied_by??'',
                    "service_name" => $dbRow->service_data->service_name??'',
                    "service_id" => $dbRow->service_data->service_id??'',
                    "payment_params" => $dbRow->form_data->payment_params??'',
                    "pfc_payment_response" => $dbRow->form_data->pfc_payment_response??'',
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
                
                $data_other = array(
                    "initiated_data" => $initiated_data,
                    "execution_data" => $execution_data,
                    "action_data" => $this->get_action_urls($serviceId, $obj_id, $appl_status, $payment_status, $ppr_grn, $ppr_status, $certificate)
                );                    
                return $data_other;
            }//End of if else                
        } else {
            return false;
        }//End of if else 
    }//End of get_data_other()
    
    private function getAuthorizationHeader() {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }//End of if 
        }//End of if else
        return $headers;
    }//End of getAuthorizationHeader()

    private function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }//End of if
        }//End of if
        return null;
    }//End of getBearerToken()
}//End of Trackallapps