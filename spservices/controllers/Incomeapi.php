<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Incomeapi extends frontend {
    private $serviceId = "NECERTIFICATE";
    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('necertificates_model');
        $this->load->config('spconfig');  
        $this->load->helper('smsprovider');
    }//End of __construct()

    public function update_data() { //echo "<pre>"; var_dump($_POST); echo "</pre>";
        $applId =  $this->input->post("applId");
        $wsResponse =  $this->input->post("wsResponse");
        $decodedText = stripslashes(html_entity_decode($wsResponse));
        $resObj = json_decode($decodedText);
        
        if ($this->checkObjectId($applId)) {
            $dbrow = $this->necertificates_model->get_by_doc_id($applId); //pre($dbrow);
            if(count((array)$dbrow)) {
                $processing_history = $dbrow->processing_history??array();
                $rtps_trans_id = $dbrow->rtps_trans_id;
                $status = $resObj->Status??'';
                $remarks = $resObj->Remarks??'';
                $certificate = $resObj->certificate??'';
                if($status === 'QS') {
                    $processing_history[] = array(
                        "processed_by" => "Query made by Department",
                        "action_taken" => "Query made",
                        "remarks" => "Query made by Department",
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
                    $data = array(
                        'remarks' => $remarks,
                        'status' => $status,
                        'processing_history' => $processing_history,
                        'query_time' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                    );
                    $this->necertificates_model->update_where(['_id' => new ObjectId($applId)], $data);

                    //Sending Query SMS
                    $sms=array(
                        "mobile" => (int)$dbrow->mobile,
                        "applicant_name" => $dbrow->applicant_name,
                        "service_name" => 'Non-Encumbrance Certificate',
                        "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->submission_date))),
                        "app_ref_no" => $dbrow->rtps_trans_id,
                        "rtps_trans_id" => $dbrow->rtps_trans_id
                    );
                    sms_provider("query",$sms);
                    $resPost = array('status' => true, 'message' => 'Query sent successfully');
                } elseif($status === 'D') {
                    $fileName = str_replace('/', '-', $rtps_trans_id).'.pdf';
                    $dirPath = 'storage/docs/'.$this->serviceId.'/';
                    if (!is_dir($dirPath)) {
                        mkdir($dirPath, 0777, true);
                        file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for NEC only</body></html>');
                    }
                    $filePath = $dirPath.$fileName;
                    file_put_contents(FCPATH.$filePath, base64_decode($certificate));
                    //echo '<a href="'.base_url($filePath).'" class="btn btn-success" target="_blank">View File</a>'; die;
                    //Update status and certificate
                    $processing_history[] = array(
                        "processed_by" => "Application delivered by Department",
                        "action_taken" => "Application delivered",
                        "remarks" => "Application delivered by Department",
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
                    $data = array(
                        'remarks' => $remarks,
                        'status' => $status,
                        'certificate' => $filePath,
                        'processing_history' => $processing_history,
                        'generated_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                    );
                    $this->necertificates_model->update_where(['_id' => new ObjectId($applId)], $data);

                    //Sending delivered SMS
                    $sms=array(
                        "mobile" => (int)$dbrow->mobile,
                        "applicant_name" => $dbrow->applicant_name,
                        "service_name" => 'Non-Encumbrance Certificate',
                        "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->submission_date))),
                        "app_ref_no" => $dbrow->rtps_trans_id,
                        "rtps_trans_id" => $dbrow->rtps_trans_id
                    );
                    sms_provider("delivery",$sms);

                    $resPost = array('status' => true, 'message' => 'Certificate updated successfully');
                } else {
                    $resPost = array('status' => false, 'message' => 'Status code does not matched');
                }//End of if else
            } else {
                $resPost = array('status' => false, 'message' => 'No records found');
            }//End of if else
        } else {
            $resPost = array('status' => false, 'message' => 'Invalid application id');
        }//End of if else
        $json_obj = json_encode($resPost);
        $this->output->set_content_type('application/json')->set_output($json_obj);
    }//End of update_data()
        
    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
           return false;
        }//End of if else
    }//End of checkObjectId()
    
    public function get_mouzas() {
        $sro_code = $this->input->post('sro_code');//"1267573"
        $data = array("sro_code"=>$sro_code);
        $json_obj = json_encode($data);
        $serverUrl = $this->config->item('url');
        $getUrl = $serverUrl . "nec/mouza_list.php?jsonbody=".$json_obj; ///die($getUrl);
        $curl = curl_init($getUrl );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl); //pre($json_obj);        
        if(isset($error_msg)) {
            echo '<select name="circle" id="circle" class="form-control"><option value="">'."CURL ERROR : ".$error_msg.'</option></select>';
        } elseif ($response) {//pre( $response);
            $response = json_decode($response); ?>                   
            <select name="circle" id="circle" class="form-control">
                <option value="">Select a circle </option>
                <?php if($response->Result) { 
                    foreach($response->Result as $rows) {
                        echo '<option value="'.$rows->loc_code.'">'.$rows->loc_name.'</option>';                   
                    }//End of foreach()
                }//End of if ?>
            </select><?php
        } else {
            echo '<select name="circle" id="circle" class="form-control"><option value="">Something went wrong</option></select>';
        }//End of if else
    }//End of get_mouzas()
    
    public function get_villages() {
        $sro_code = $this->input->post('sro_code');//"1267573"
        $vlcode = $this->input->post('vlcode');//"1267573"
        $data = array("sro_code"=>$sro_code, "vlcode" => $vlcode);
        $json_obj = json_encode($data);
        $serverUrl = $this->config->item('url');
        $getUrl = $serverUrl . "nec/village_list.php?jsonbody=".$json_obj; //die($getUrl);
        $curl = curl_init($getUrl );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl); //pre($json_obj);        
        if(isset($error_msg)) {
            echo '<select name="village" id="village" class="form-control"><option value="">'."CURL ERROR : ".$error_msg.'</option></select>';
        } elseif ($response) {//pre( $response);
            $response = json_decode($response); ?>                   
            <select name="village" id="village" class="form-control">
                <option value="">Select a village </option>
                <?php if($response->Result) { 
                    foreach($response->Result as $rows) {
                        echo '<option value="'.$rows->loc_code.'">'.$rows->loc_name.'</option>';                   
                    }//End of foreach()
                }//End of if ?>
            </select><?php
        } else {
            echo '<select name="village" id="village" class="form-control"><option value="">Something went wrong</option></select>';
        }//End of if else
    }//End of get_villages()

}//End of Necapi