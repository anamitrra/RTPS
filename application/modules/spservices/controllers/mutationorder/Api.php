<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Api extends frontend {
    
    private $serviceName = "Issuance of Certified Copy of Mutation Order";
    private $serviceId = "MUTATION_ORDER";
    
    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('mutationorder/mutationorders_model');
        $this->load->config('spconfig');  
        $this->load->helper('smsprovider');
    }//End of __construct()
        
    public function index() { //For testing UMANG API
        exit('No direct script access allowed');
    }//End of index()
    
    //certificate file check and updated
    public function update_data() { //echo "POSTED DATA : <pre>"; var_dump($_POST); echo "</pre>";
        $applId =  $this->input->post("applId");
        $wsResponse =  $this->input->post("wsResponse");
        $decodedText = stripslashes(html_entity_decode($wsResponse));
        $resObj = json_decode($decodedText);
        
        if ($this->checkObjectId($applId)) {
            $dbrow = $this->mutationorders_model->get_by_doc_id($applId); //pre($dbrow);
            if(count((array)$dbrow)) {
                $processing_history = $dbrow->processing_history??array();
                $appl_ref_no = $dbrow->service_data->appl_ref_no;
                $status = $resObj->status??'';
                $remarks = $resObj->remark??'';
                $certificate = $resObj->certificate??'';
                if($status === 'AST') {
                    $processing_history[] = array(
                        "processed_by" => "Action taken by Assistant",
                        "action_taken" => "Action taken by Assistant",
                        "remarks" => $remarks,
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
                    $data = array(
                        'form_data.remarks' => $remarks,
                        'service_data.appl_status' => $status,
                        'processing_history' => $processing_history,
                        'form_data.query_time' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                    );
                    $this->mutationorders_model->update_where(['_id' => new ObjectId($applId)], $data);
                } elseif($status === 'QS') {
                    $processing_history[] = array(
                        "processed_by" => "Query made by Department",
                        "action_taken" => "Query made",
                        "remarks" => "Query made by Department",
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
                    $data = array(
                        'form_data.remarks' => $remarks,
                        'service_data.appl_status' => $status,
                        'processing_history' => $processing_history,
                        'form_data.query_time' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                    );
                    $this->mutationorders_model->update_where(['_id' => new ObjectId($applId)], $data);

                    //Sending Query SMS
                    $sms=array(
                        "mobile" => (int)$dbrow->form_data->mobile_number,
                        "applicant_name" => $dbrow->form_data->applicant_name,
                        "service_name" => $this->serviceName,
                        "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
                        "app_ref_no" => $dbrow->service_data->appl_ref_no,
                        "rtps_trans_id" => $dbrow->service_data->appl_ref_no
                    );
                    sms_provider("query",$sms);
                    $resPost = array('status' => true, 'message' => 'Query sent successfully');
                } elseif($status === 'D') {
                    
                    //Update data
                    $processing_history[] = array(
                        "processed_by" => "Application delivered by Department",
                        "action_taken" => "Application delivered",
                        "remarks" => "Application delivered by Department",
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
                    $data = array(
                        'form_data.remarks' => $remarks,
                        'service_data.appl_status' => $status,
                        'processing_history' => $processing_history,
                        'form_data.generated_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                    );
                    $this->mutationorders_model->update_where(['_id' => new ObjectId($applId)], $data);

                    //Update certificate
                    if(strlen($certificate) > 100) {
                        $dirPath = 'storage/docs/'.$this->serviceId.'/';
                        $fileName = str_replace('/', '-', $appl_ref_no).'.pdf';
                        if (!is_dir($dirPath)) {
                            mkdir($dirPath, 0777, true);
                            file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for NEC only</body></html>');
                        }
                        $filePath = $dirPath.$fileName;
                        file_put_contents(FCPATH.$filePath, base64_decode($certificate));
                        //echo '<a href="'.base_url($filePath).'" class="btn btn-success" target="_blank">View File</a>'; die;
                    } else {
                        $filePath = null;//$this->generatecertificate($applId);
                    }//End of if else
                    $this->mutationorders_model->update_where(['_id' => new ObjectId($applId)], array('form_data.certificate' => $filePath));
                    
                    //Sending delivered SMS
                    $sms=array(
                        "mobile" => (int)$dbrow->form_data->mobile_number,
                        "applicant_name" => $dbrow->form_data->applicant_name,
                        "service_name" => $this->serviceName,
                        "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
                        "app_ref_no" => $dbrow->service_data->appl_ref_no,
                        "rtps_trans_id" => $dbrow->service_data->appl_ref_no
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
        
    public function get_districts($selected_district = null) {
        $getUrl = $this->config->item('dhar_api')."district_list.php"; //die($getUrl);
        $curl = curl_init($getUrl );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if(isset($error_msg)) {
            echo '<select class="form-control"><option value="">'."CURL ERROR : ".$error_msg.'</option></select>';
        } elseif ($response) { //pre($response);
            $responseArr = json_decode($response, true);
            if(isset($responseArr['Result'])) {
                if(count($responseArr['Result'])) {
                    echo '<select name="district" id="district" class="form-control"><option value="">Select a district </option>';
                    foreach($responseArr['Result'] as $rows) {  
                        $selected = ($rows["district_code"] === $selected_district)?'selected':'';
                        $distJson = json_encode(array('district_code' => $rows["district_code"], 'district_name' => $rows["district_name"]), JSON_UNESCAPED_UNICODE);
                        echo "<option value='".$distJson."' ".$selected.">".$rows['district_name']."</option>";               
                    }//End of foreach()
                    echo '</select>';
                } else {
                    echo '<select class="form-control"><option value="'.$response.'">No records found</option></select>';
                }//End of if else
            } else {
                echo '<select class="form-control"><option value="'.$response.'">Error in fetching data</option></select>';
            }//End of if else
        } else {
            echo '<select class="form-control"><option value="">Something went wrong</option></select>';
        }//End of if else
    }//End of get_districts()
        
    public function get_circles($selected_circle=null) {
        $district_code = $this->input->post('district_code');//'07';
        $data = array("district_code"=>$district_code);
        $json_obj = json_encode($data);
        $getUrl = $this->config->item('dhar_api')."circle_list.php?jsonbody=".$json_obj; //die($getUrl);
        $curl = curl_init($getUrl );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if(isset($error_msg)) {
            echo '<select class="form-control"><option value="">'."CURL ERROR : ".$error_msg.'</option></select>';
        } elseif ($response) { //pre($response);
            $responseArr = json_decode($response, true);
            if(isset($responseArr['Result'])) {
                if(count($responseArr['Result'])) {
                    echo '<select name="circle" id="circle" class="form-control"><option value="">Select a circle </option>';
                    foreach($responseArr['Result'] as $rows) {  
                        $selected = ($rows["cir_code"] === $selected_circle)?'selected':'';
                        $circleJson = json_encode(array('circle_code' => $rows["cir_code"], 'circle_name' => $rows["loc_name"]), JSON_UNESCAPED_UNICODE);
                        echo "<option value='".$circleJson."' ".$selected.">".$rows['loc_name']."</option>";            
                    }//End of foreach()
                    echo '</select>';
                } else {
                    echo '<select class="form-control"><option value="'.$response.'">No records found</option></select>';
                }//End of if else
            } else {
                echo '<select class="form-control"><option value="'.$response.'">Error in fetching data</option></select>';
            }//End of if else
        } else {
            echo '<select class="form-control"><option value="">Something went wrong</option></select>';
        }//End of if else
    }//End of get_circles()
        
    public function get_villages($selected_village=null) {
        $circle_code = $this->input->post('circle_code');//'070101';
        $data = array("cir_code"=>$circle_code);
        $json_obj = json_encode($data);
        $getUrl = $this->config->item('dhar_api')."village_list.php?jsonbody=".$json_obj; //die($getUrl);
        $curl = curl_init($getUrl );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if(isset($error_msg)) {
            echo '<select class="form-control"><option value="">'."CURL ERROR : ".$error_msg.'</option></select>';
        } elseif ($response) { //pre($response);
            $responseArr = json_decode($response, true);
            if(isset($responseArr['Result'])) {
                if(count($responseArr['Result'])) {
                    echo '<select name="village" id="village" class="form-control"><option value="">Select a village </option>';
                    foreach($responseArr['Result'] as $rows) {  
                        $selected = ($rows["vill_code"] === $selected_village)?'selected':'';
                        $villageJson = json_encode(array('village_code' => $rows["vill_code"], 'village_name' => $rows["loc_name"]), JSON_UNESCAPED_UNICODE);
                        echo "<option value='".$villageJson."' ".$selected.">".$rows['loc_name']."</option>";               
                    }//End of foreach()
                    echo '</select>';
                } else {
                    echo '<select class="form-control"><option value="'.$response.'">No records found</option></select>';
                }//End of if else
            } else {
                echo '<select class="form-control"><option value="'.$response.'">Error in fetching data</option></select>';
            }//End of if else
        } else {
            echo '<select class="form-control"><option value="">Something went wrong</option></select>';
        }//End of if else
    }//End of get_villages()
        
    public function get_pattanos($selected_pattano=null) {
        $village_code = $this->input->post('village_code');//'070104010110001';
        $data = array("vill_code"=>$village_code);
        $json_obj = json_encode($data);
        $getUrl = $this->config->item('dhar_api')."patta_no_list.php?jsonbody=".$json_obj; //die($getUrl);
        $curl = curl_init($getUrl );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if(isset($error_msg)) {
            echo '<select class="form-control"><option value="">'."CURL ERROR : ".$error_msg.'</option></select>';
        } elseif ($response) { //pre($response);
            $responseArr = json_decode($response, true);
            if(isset($responseArr['Result'])) {
                if(count($responseArr['Result'])) {
                    echo '<select name="patta" id="patta" class="form-control"><option value="">Select a patta no. </option>';
                    foreach($responseArr['Result'] as $rows) {
                        $selected = ($rows["patta_no"] === $selected_pattano)?'selected':'';
                        $arr = array('patta_no' => $rows["patta_no"], 'loc_code' => $rows["loc_code"], 'details' => $rows["details"]);
                        $pattaJson = json_encode($arr, JSON_UNESCAPED_UNICODE);
                        echo "<option value='".$pattaJson."' ".$selected.">".$rows['details']."</option>";        
                    }//End of foreach()
                    echo '</select>';
                } else {
                    echo '<select class="form-control"><option value="'.$response.'">No records found</option></select>';
                }//End of if else
            } else {
                echo '<select class="form-control"><option value="'.$response.'">Error in fetching data</option></select>';
            }//End of if else
        } else {
            echo '<select class="form-control"><option value="">Something went wrong</option></select>';
        }//End of if else
    }//End of get_pattanos()
        
    public function get_casenos() {
        $loc_code = $this->input->post('loc_code');//'140101010110002';
        $patta_no = $this->input->post('patta_no');//'02022';
        $case_no = $this->input->post('case_no');
        $data = array("loc_code" => $loc_code, "patta_no" => $patta_no);
        $json_obj = json_encode($data);
        $getUrl = $this->config->item('dhar_api')."mutation/mutation_cases_list.php?jsonbody=".$json_obj; //die($getUrl);
        $curl = curl_init($getUrl );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);   
        if(isset($error_msg)) {
            echo '<select class="form-control"><option value="">'."CURL ERROR : ".$error_msg.'</option></select>';
        } elseif ($response) { //pre($response);
            $responseArr = json_decode($response, true);
            if(isset($responseArr['Result'])) {
                if(count($responseArr['Result'])) {
                    echo '<select name="case_no" id="case_no" class="form-control"><option value="">Select a case no. </option>';
                    foreach($responseArr['Result'] as $rows) {  
                        $caseNo = $rows['case_no'];
                        $selected = ($caseNo === $case_no)?'selected':'';
                        echo "<option value='".$caseNo."' ".$selected.">".$caseNo."</option>";               
                    }//End of foreach()
                    echo '</select>';
                } else {
                    echo '<select class="form-control"><option value="'.$response.'">No records found</option></select>';
                }//End of if else
            } else {
                echo '<select class="form-control"><option value="'.$response.'">Error in fetching data</option></select>';
            }//End of if else
        } else {
            echo '<select class="form-control"><option value="">Something went wrong</option></select>';
        }//End of if else
    }//End of get_casenos()
        
    public function get_pattadars() {
        $loc_code = $this->input->post('loc_code');//'140101010110002';
        $patta_no = $this->input->post('patta_no');//'02022';
        $pattadar_name = $this->input->post('pattadar_name');
        $data = array("loc_code" => $loc_code, "patta_no" => $patta_no);
        $json_obj = json_encode($data);
        $getUrl = $this->config->item('dhar_api')."ror/ror_pattadar_list.php?jsonbody=".$json_obj; //die($getUrl);
        $curl = curl_init($getUrl );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);   
        if(isset($error_msg)) {
            echo '<select class="form-control"><option value="">'."CURL ERROR : ".$error_msg.'</option></select>';
        } elseif ($response) { //pre($response);
            $responseArr = json_decode($response, true);
            if(isset($responseArr['Result'])) {
                if(count($responseArr['Result'])) {
                    echo '<select name="pattadar_name" id="pattadar_name" class="form-control"><option value="">Select a name </option>';
                    foreach($responseArr['Result'] as $rows) {       
                        $pdar_name = trim($rows['pdar_name']);
                        $selected = (utf8_encode($pdar_name) === $pattadar_name)?'selected':'';
                        echo "<option value='".$pdar_name."' ".$selected.">".$pdar_name."</option>";                  
                    }//End of foreach()
                    echo '</select>';
                } else {
                    echo '<select class="form-control"><option value="'.$response.'">No records found</option></select>';
                }//End of if else
            } else {
                echo '<select class="form-control"><option value="'.$response.'">Error in fetching data</option></select>';
            }//End of if else
        } else {
            echo '<select class="form-control"><option value="">Something went wrong</option></select>';
        }//End of if else
    }//End of get_pattadars()
            
    public function get_officecircles($selected_circle=null) {
        $district_code = $this->input->post('district_code');   
        $circle_code = $this->input->post('office_circle_code');        
        $this->load->model('circleoffices_model');
        $circles = $this->circleoffices_model->get_rows(array('district_code'=>$district_code));
        if($circles) { ?>
            <select name="office_circle" id="office_circle" class="form-control">
                <option value="">Select</option>
                <?php if($circles){
                    foreach($circles as $circle){ 
                        $treasury_code = $circle->treasury_code??'';
                        $office_code = $circle->office_code??'';
                        if(strlen($treasury_code) && strlen($office_code)) {
                            $circleArr = array(
                                "office_circle_code"=>$circle->circle_code, 
                                "office_circle_name" =>$circle->circle_name,
                            );
                            $circleJson = json_encode($circleArr);
                            $selected = ($circle_code == $circle->circle_code) ? "selected":"";
                            echo "<option value='".$circleJson."' ".$selected.">".$circle->circle_name."</option>";
                        }//End of if ?>
                    <?php }//End of foreach()
                }//End of if ?>
            </select>
        <?php } else {
             echo '<select class="form-control"><option value="">No records found</option></select>';
        }//End of if else
    }//End of get_officecircles()

}//End of Api