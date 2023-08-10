<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\UTCDateTime;

class Rtpsapi extends frontend
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('intermediator_model');
        // $this->load->model('portals_model');
        $this->config->load('rtps_services');
        $this->load->library('AES');
        $this->encryption_key = $this->config->item("encryption_key");
    }

    private function get_app_status($task_details){
        $data=false;
        $status=array("Reject","reject",'delivered','FINISHED','Delivered','complete','Complete','completed','Completed');
        if($task_details){
            foreach($task_details as $task){
              if(in_array( $task->action,$status )){
              $data=$task;
              break; 
              }
            }
        }
        return $data;
      }

    public function push_app_status()
    {
        $json = file_get_contents('php://input');
        if (!empty($json)) {
            $data = json_decode($json, true);
            if (isset($data['data'])) {
                $aes = new AES($data['data'], $this->encryption_key);
                $dec = $aes->decrypt();

                if (!empty($dec)) {
                    $input = json_decode($dec);

                    if (empty($input->task_details) || empty($input->app_ref_no)) {
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => false, "message" => "Invalid Parameters")));
                    }

                    $dbRow = $this->intermediator_model->get_row(array('app_ref_no' => $input->app_ref_no));

                    if (empty($dbRow)) {
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no")));
                    }

                    $task_details = $input->task_details;
                    $data_to_save = array();
                    if ($dbRow  && $task_details) {
                        $status = 'P';


                        $latest_task=$this->get_app_status($task_details);
                        if($latest_task){
                          $final_task=$latest_task;
                        }else{
                          $task1=array_reverse($task_details);
                          $final_task=$task1[0];
                        }

                        if(gettype($final_task)  !=="object"){
                            return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(array("status" => false, "message" => "Invalid task details. It shoud be array of objects")));
                        }
                        if ($final_task) {
                            if ($final_task->action === "Reject" || $final_task->action === "reject")
                                $status = 'R';

                            if ($final_task->action === "Delivered" || $final_task->action === "delivered" || $final_task->action === "FINISHED")
                                $status = 'D';
                            if ($final_task->action === "Complete" || $final_task->action === "complete")
                                $status = 'D';

                            if ($final_task->action === "Query" || $final_task->action === "query")
                                $status = 'Q';

                            if ($final_task->action === "Forward" || $final_task->action === "forward")
                                $status = 'F';
                        }

                        $data_to_save['execution_data'] = $task_details;
                        $data_to_save['delivery_status'] = $status;
                        $data_to_save['execution_date'] = isset($final_task->executed_time) ? $final_task->executed_time : '';
                        if(empty($final_task->executed_time)){
                            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => false, 'message' => "Execution date can not be empty")));
                        }
                        
                        $res = $this->intermediator_model->update_row(array('app_ref_no' => $input->app_ref_no), $data_to_save);
                        // pre($res);
                        if ($res->getMatchedCount()) {
                            return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(array("status" => true,'delivery_status'=>$status, 'message' => "Successfully data saved")));
                        } else {
                            return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(array("status" => false, 'message' => "something went wrong")));
                        }
                    }
                }
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array("status" => false, "message" => "No data found")));
            }
        }else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "No data found")));
        }
        
    }



    public function get_app_certificate(){
            $status_url="external url";
            if (!isset($_GET['app_ref_no']) || empty($_GET['app_ref_no'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                'status' => "something went wrong",

                )));
            return;
            }
            $app_ref_no = $_GET['app_ref_no'];
            $res = $this->intermediator_model->get_userid_by_application_ref($app_ref_no);
            $user_mobile = $res->mobile;
           
            if (empty($status_url)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                'status' => "Status url not define",

                )));
            return;
            }

            $data = array(
            "app_ref_no" => $app_ref_no, //'NOC/05/143/2020'
            "mobile" => $user_mobile //"9435347177"
            );
           
            $input_array = json_encode($data);
            $aes = new AES($input_array, $this->encryption_key);
            $enc = $aes->encrypt();
            //curl request

            $post_data = array('data' => $enc);
            $curl = curl_init($status_url);
            // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);


            curl_close($curl);

            if ($response) {
            $response = json_decode($response);
            }
            // var_dump($response);die;
            //decryption
            if (isset($response->data) && !empty($response->data)) {
            $aes->setData($response->data);
            $dec = $aes->decrypt();
            $outputdata = json_decode($dec);
            }
    }
    public function test_post()
    {
        $task_details[] = array(
            "user_name" => "Test 1",
            "user_designation" => "dsd",
            "user_office" => "ss",
            "action" => "forward",
            "received_time" => "",
            "executed_time" => "",
            "remark" => "",
            "url" => ""
        );
        $task_details[] = array(
            "user_name" => "Test 2",
            "user_designation" => "dsd",
            "user_office" => "ss",
            "action" => "query",
            "received_time" => "",
            "executed_time" => "2002/02/2",
            "remark" => "",
            "url" => ""
        );
        $data_to_post = array(
            "app_ref_no" => "SCAN004/tmpr0030",
            "task_details" => $task_details
        );

        $input_array = json_encode($data_to_post);
        $aes = new AES($input_array, $this->encryption_key);
        $enc = $aes->encrypt();
        pre($enc);
        // $data=array(
        //   "data"=>$enc
        // );
    }


    public function get_basundhara_payment_status($rtps_trans_id)
    {
      
        if (!empty($rtps_trans_id)) {
            $dbRow = $this->intermediator_model->get_row(array('rtps_trans_id' => $rtps_trans_id,"portal_no"=>"5"));

           
            if (empty($dbRow)) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(array("status" => false, "message" => "No records found with this RTPS no")));
            }
            $st='';
            if(property_exists($dbRow,"pfc_payment_response") ){
                if(!empty($dbRow->pfc_payment_response->STATUS)){
                    $st=$dbRow->pfc_payment_response->STATUS;
                }else{
                    $st="P"; 
                }
            }
            $data=array(
                "status" => true,
                "data"=>property_exists($dbRow,"pfc_payment_response") ? $dbRow->pfc_payment_response  : '',
                'payment_status'=>  $st,
                "message" => "",
            );

            return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($data));
            
        }else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array("status" => false, "message" => "No data found")));
        }
        
    }

    public function get_basundhara_query_payment_status($rtps_trans_id)
    {
      
        if (!empty($rtps_trans_id)) {
            $dbRow = $this->intermediator_model->get_row(array('rtps_trans_id' => $rtps_trans_id,"portal_no"=>"5"));

           
            if (empty($dbRow) || !property_exists($dbRow,"query_payment_config")) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(array("status" => false, "message" => "No records found with this RTPS no")));
            }
            $st='P';
            if(property_exists($dbRow,"query_payment_response") ){
                if(!empty($dbRow->query_payment_response->STATUS)){
                    $st=$dbRow->query_payment_response->STATUS;
                }
            }
            $data=array(
                "status" => true,
                "data"=>property_exists($dbRow,"query_payment_response") ? $dbRow->query_payment_response  : '',
                'payment_status'=>  $st,
                "message" => "",
            );

            return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($data));
            
        }else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array("status" => false, "message" => "No data found")));
        }
        
    }


    public function get_noc_payment_status($rtps_trans_id)
    {
      
        if (!empty($rtps_trans_id)) {
            $filter=array(
                'rtps_trans_id' => $rtps_trans_id,
                "portal_no"=>['$in'=>["1",1]]
            );
            $dbRow = $this->intermediator_model->get_row($filter);

           
            if (empty($dbRow)) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(array("status" => false, "message" => "No records found with this RTPS no")));
            }
            if(property_exists($dbRow,"has_payment_issue") && $dbRow->has_payment_issue){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array("status" => false, "message" => "Has payment issue")));
            }
           
            if(property_exists($dbRow,"pfc_payment_response") && $dbRow->pfc_payment_response->STATUS === "Y"){
                $data=array(
                    "status" => true,
                    "data"=>property_exists($dbRow,"pfc_payment_response") ? $dbRow->pfc_payment_response  : '',
                    "message" => "",
                );
    
                return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($data));
            }else{
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array("status" => false, "message" => "payment not yet done")));
            }
          
            
        }else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array("status" => false, "message" => "No data found")));
        }
        
    }

    public function push_disposal_app_status()
    {
        $json = file_get_contents('php://input');
        if (!empty($json)) {
            $data = json_decode($json, true);
            if (isset($data['data'])) {
                $aes = new AES($data['data'], $this->encryption_key);
                $dec = $aes->decrypt();

                if (!empty($dec)) {
                    $input = json_decode($dec);

                    if (empty($input->task_details) || empty($input->app_ref_no)) {
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => false, "message" => "Invalid Parameters")));
                    }

                    $dbRow = $this->intermediator_model->get_row(array('app_ref_no' => $input->app_ref_no));
                 
                    if (empty($dbRow)) {
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no")));
                    }

                    $task_details = $input->task_details;
                    $data_to_save = array();
                    if ($dbRow  && $task_details) {
                        $status = 'P';


                        $final_task = array_reverse($task_details);
                        if(gettype($final_task[0])  !=="object"){
                            return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(array("status" => false, "message" => "Invalid task details. It shoud be array of objects")));
                        }
                        if ($final_task) {
                            if ($final_task[0]->action === "Reject" || $final_task[0]->action === "reject")
                                $status = 'R';

                            if ($final_task[0]->action === "Delivered" || $final_task[0]->action === "delivered" || $final_task[0]->action === "FINISHED")
                                $status = 'D';
                            if ($final_task[0]->action === "Complete" || $final_task[0]->action === "complete")
                                $status = 'D';

                            if ($final_task[0]->action === "Query" || $final_task[0]->action === "query")
                                $status = 'Q';

                            if ($final_task[0]->action === "Forward" || $final_task[0]->action === "forward")
                                $status = 'F';
                        }

                        $data_to_save['execution_data'] = $task_details;
                        $data_to_save['delivery_status'] = $status;
                        $data_to_save['execution_date'] = isset($final_task[0]->executed_time) ? $final_task[0]->executed_time : '';
                        if(empty($final_task[0]->executed_time)){
                            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => false,'delivery_status'=>$status, 'message' => "Execution date can't be empty")));
                        }
                        if( $status  === "R" ||  $status  === "D"){
                            $res = $this->intermediator_model->update_row(array('app_ref_no' => $input->app_ref_no), $data_to_save);
                            if ($res->getMatchedCount()) {
                                return $this->output
                                    ->set_content_type('application/json')
                                    ->set_status_header(200)
                                    ->set_output(json_encode(array("status" => true, 'delivery_status'=>$status,'message' => "Successfully data saved")));
                            } else {
                                return $this->output
                                    ->set_content_type('application/json')
                                    ->set_status_header(200)
                                    ->set_output(json_encode(array("status" => false,'delivery_status'=>$status, 'message' => "something went wrong")));
                            }
                        }else{
                            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => false,'delivery_status'=>$status, 'message' => "Application status should be delivered or rejected")));
                        }
                       
                       
                      
                    }
                }
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array("status" => false, "message" => "No data found")));
            }
        }else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "No data found")));
        }
        
    }


    public function get_rtps_payment_status(){
        $json = file_get_contents('php://input');
        if (!empty($json)) {
            $data = json_decode($json, true);
           
            if (isset($data['data'])) {
               
                $aes = new AES($data['data'], $this->encryption_key);
                $dec = $aes->decrypt();
             
                if (!empty($dec)) {
                    $input = json_decode($dec);

                    if ( empty($input->app_ref_no)) {
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => false, "message" => "Invalid Parameters")));
                    }

                    $dbRow = $this->intermediator_model->get_row(array('app_ref_no' => $input->app_ref_no));
                    if (empty($dbRow)) {
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => false, "message" => "No records found with this app ref no")));
                    }

                    $payment_data=property_exists($dbRow,"pfc_payment_response") ?   $dbRow->pfc_payment_response : false;
                    $payment_status=$payment_data ? $payment_data->STATUS : "P";
                    $data_to_response=array(
                        "app_ref_no"=>$input->app_ref_no,
                        "payment_status"=>!empty($payment_status) ? $payment_status : "P",
                        "payment_data"=>$payment_data ? $payment_data : []
                    );
          
 
                    $aes = new AES(json_encode( $data_to_response), $this->encryption_key);
                    $enc_data = $aes->encrypt();
                    return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array( "data" => $enc_data )));
                   
                }
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array("status" => false, "message" => "No data found")));
            }
        }else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "No data found")));
        }
    }
}
