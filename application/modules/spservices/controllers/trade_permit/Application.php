<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Application extends Rtps {
    
    private $serviceName = "APPLICATION FORM FOR TRADING PERMIT TO CARRY ON BUSINESS FOR TRIBAL";
    private $serviceId = "NC-TP";

    public function __construct() {
        parent::__construct();
        $this->load->model('trade_permit/tradepermit_model');
        $this->load->model('trade_permit/circle_model');
        $this->load->model('trade_permit/state_model');
        $this->load->model('trade_permit/district_model');
        $this->load->model('trade_permit/tradearea_model');
        
        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper('smsprovider');       
        
        if($this->session->role){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
    }//End of __construct()

    public function index($objId=null) {
        $data=array(
            "obj_id" => $objId,
            "serviceservice_name"=>$this->serviceName
        );
        $dbRow = $this->tradepermit_model->get_row(array('_id' => new ObjectId($objId)));

        //pre($dbRow);
        if($dbRow) {
            $data["dbrow"] = $this->tradepermit_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        }//End of if else  
        //echo $data["form_status"];      
        //die;
        $this->load->view('includes/frontend/header');
        $this->load->view('trade_permit/application',$data);
        $this->load->view('includes/frontend/footer');
    }//End of index()
    
    public function submit(){
        //data validation
        $objId = $this->input->post("obj_id");
        $formStatus = $this->input->post("form_status");

        $this->form_validation->set_rules('first_name', 'first_name','trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('last_name', 'last_name','trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('father_name', 'Father', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('contact_number','Contact number', 'trim|integer|required|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('emailid', 'Email id', 'valid_email|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('age', 'Age','trim|required|xss_clean|strip_tags|max_length[3]');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('profession', 'Profession', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('nationality', 'Nationality', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('community', 'Community', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('pa_address_line_1','Address Line 1','trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_address_line_2', 'Address Line 2', 'trim|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('pa_address_line_3', 'Address Line 3', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_post_office', 'Post Office', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('pa_pin_code', 'Pin Code', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('pa_village', 'Village', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('pa_police_station','Police Station','trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_circle','Revenue Circle','trim|required|xss_clean|strip_tags');  
        $this->form_validation->set_rules('pa_state', 'State', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('pa_country','Country','trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_district','District','trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('ca_address_line_1','Address Line 1','trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_address_line_2', 'Address Line 2', 'trim|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('ca_address_line_3', 'Address Line 3', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_post_office', 'Post Office', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('ca_pin_code', 'Pin Code', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('ca_village', 'Village', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('ca_police_station','Police Station','trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_country','Country','trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_district','District','trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('market_place', 'Market Place', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('commodities','Commodities','trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('location','Location','trim|required|xss_clean|strip_tags');  
        $this->form_validation->set_rules('govt_emp','Government Employee','trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('trade_area','Trade Area','trim|required|xss_clean|strip_tags');

        //enclosure validation
        if($this->slug !== 'user'){
        $this->form_validation->set_rules('soft_doc_type', 'Soft Copy of Application type', 'required');
        }
        $this->form_validation->set_rules('passport_pic_type', 'Photo','required');
        $this->form_validation->set_rules('doc_type', 'Document type','required');
        
        //file validation
        
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $softdoc = cifileupload("soft_doc");
        $soft_doc = $softdoc["upload_status"]?$softdoc["uploaded_path"]:null;
        $passportpic = cifileupload("passport_pic");
        $passport_pic = $passportpic["upload_status"]?$passportpic["uploaded_path"]:null;
        $sdoc = cifileupload("doc");
        $doc = $sdoc["upload_status"]?$sdoc["uploaded_path"]:null;
        
        $uploadedFiles = array(
            "soft_doc_old" => strlen($soft_doc)?$soft_doc:$this->input->post("soft_doc_old"),  
            "passport_pic_old" => strlen($passport_pic)?$passport_pic:$this->input->post("passport_pic_old"),
            "doc_old" => strlen($doc)?$doc:$this->input->post("doc_old")
        );
       //pre($uploadedFiles);
        if($this->slug !== 'user'){
            if(empty($uploadedFiles["soft_doc_old"])){
                $this->form_validation->set_rules('soft_doc', 'Soft Document','required');
            }
        }
        if(empty($uploadedFiles["doc_old"])){
            $this->form_validation->set_rules('doc', 'Documents', 'required');
        }
        if(empty($uploadedFiles["passport_pic_old"])){
            $this->form_validation->set_rules('passport_pic', 'Photo', 'required');
        }
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
        } 

        else {
            if($formStatus === "QS"){
                if ($objId) {
                    $dbrow = $this->tradepermit_model->get_by_doc_id($objId);
                    $appl_ref_no = $dbrow->service_data->appl_ref_no;
                }
            }
            else{
                $appl_ref_no = $this->getID(7);
            }

            $sessionUser = $this->session->userdata();

            if ($this->slug === "CSC") {
                $apply_by = $sessionUser['userId'];
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            } //End of if else

            if($formStatus === "QS"){
                if ($objId) {
                    $dbrow = $this->tradepermit_model->get_by_doc_id($objId);
                    $app_id = $dbrow->service_data->appl_id;
                }
            }
            else{
                while(1){
                    $app_id = rand(100000000, 999999999);
                    $filter = array( 
                        "service_data.appl_id" => $app_id
                    );
                    $rows = $this->tradepermit_model->get_row($filter);
                    
                    if($rows == false)
                        break;
                }
            }
        //ends

        $service_data = [
                "department_id" => "991",
                "department_name" => "NCHAC",
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => "", //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => $this->input->post("circle_id"), // office name
                "submission_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "service_timeline" => "14 Days",
                "appl_status" => $formStatus,
        ];

        $form_data = [
                'user_id' => $sessionUser['userId']->{'$id'},
                'user_type' => $this->slug,

                'applied_user_type' => $this->slug,
                'service_name' => $this->serviceName,
                'service_id' => $this->serviceId,
                'status' => $formStatus, 
                'rtps_trans_id' => $appl_ref_no,
                'circle_id' => $this->input->post("circle_id"),
                        
                'first_name' => $this->input->post("first_name"),
                'father_name' => $this->input->post("father_name"),
                'last_name' => $this->input->post("last_name"),
                'mobile_number' => $this->input->post("contact_number"),
                'email' => $this->input->post("emailid"),
                'age' => $this->input->post("age"),
                'community' => $this->input->post("community"),
                'profession' => $this->input->post("profession"),
                'nationality' => $this->input->post("nationality"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                 
                'pa_address_line_1' => $this->input->post("pa_address_line_1"),
                'pa_address_line_2' => $this->input->post("pa_address_line_2"),
                'pa_address_line_3' => $this->input->post("pa_address_line_3"),
                'pa_post_office' => $this->input->post("pa_post_office"),
                'pa_pin_code' => $this->input->post("pa_pin_code"),
                'pa_village' => $this->input->post("pa_village"),
                'pa_police_station' => $this->input->post("pa_police_station"),
                'pa_circle' => $this->input->post("pa_circle"),
                'pa_district' => $this->input->post("pa_district"),

                'ca_address_line_1' => $this->input->post("ca_address_line_1"),
                'ca_address_line_2' => $this->input->post("ca_address_line_2"),
                'ca_address_line_3' => $this->input->post("ca_address_line_3"),
                'ca_post_office' => $this->input->post("ca_post_office"),
                'ca_pin_code' => $this->input->post("ca_pin_code"),
                'ca_village' => $this->input->post("ca_village"),
                'ca_police_station' => $this->input->post("ca_police_station"),
                'ca_district' => $this->input->post("ca_district"),
                'district_id_ca' => $this->input->post("district_id_ca"),
                'ca_state'=>$this->input->post("ca_state"),

                'market_place' => $this->input->post("market_place"),
                'commodities' => $this->input->post("commodities"),
                'location' => $this->input->post("location"),
                'govt_emp' => $this->input->post("govt_emp"),
                'trade_area' => $this->input->post("trade_area"),
                'address_same' =>$this->input->post("address_same"),

                 //enclosures
                'soft_doc_type' => $this->input->post("soft_doc_type"),
                'soft_doc' => strlen($soft_doc)?$soft_doc:$this->input->post("soft_doc_old"),
               
                'passport_pic_type' => $this->input->post("passport_pic_type"),
                'passport_pic' => strlen($passport_pic)?$passport_pic:$this->input->post("passport_pic_old"),
                
                'doc_type' => $this->input->post("doc_type"),
                'doc' => strlen($doc)?$doc:$this->input->post("doc_old"),
                //enclosure upload ends

                'query_answered' => $this->input->post("query_answered"),
        ];

        $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data
            ];

        //pre($inputs);

        if(strlen($objId)) {
                $this->tradepermit_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                $this->session->set_flashdata('success','Your application has been successfully updated');
                redirect('spservices/trade_permit/Application/preview/'.$objId);
        } 
        else {
            $insert=$this->tradepermit_model->insert($inputs);
             
            if($insert){
                $objectId=$insert['_id']->{'$id'};
                $this->session->set_flashdata('success','Your application has been successfully submitted');
                redirect('spservices/trade_permit/Application/preview/'.$objectId);
                exit();
            }
            else {
                $this->session->set_flashdata('fail','Unable to submit data!!! Please try again.');
                $this->index();
            }//End of if else
        }
    }
        
    }//End of submit()
        
    public function preview($objId=null) { //die($objId);
        $dbRow = $this->tradepermit_model->get_by_doc_id($objId);

        if(count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );

            $this->load->view('includes/frontend/header');
            $this->load->view('spservices/trade_permit/applicationpreview',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','Records does not exist');
            redirect('spservices/trade_permit/Application');
        }//End of if else
    }//End of preview()

    
    public function view($objId=null) { //die($objId);
        $dbRow = $this->tradepermit_model->get_by_doc_id($objId);

        if(count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );

            $this->load->view('includes/frontend/header');
            $this->load->view('spservices/trade_permit/applicationview',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','Records does not exist');
            redirect('spservices/trade_permit/Application');
        }//End of if else
    }//End of preview()

    public function finalsubmition($objId=null)
    {
        //$obj = $this->input->post('obj');
        if ($objId) {
            $dbrow = $this->tradepermit_model->get_by_doc_id($objId);
            $processing_history = $dbrow->processing_history ?? array();
           
            if($dbrow->service_data->appl_status === "QS"){
                 $endIndex = count($dbrow->processing_history);
                 $processing_history[$endIndex] = array(
                    "processed_by" => "Query Answered by " . $dbrow->form_data->first_name,
                    "action_taken" => "Query Answered",
                    "remarks" => "Query submitted by " . $dbrow->form_data->first_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
            }
            else{    
                //procesing data
                $processing_history[] = array(
                    "processed_by" => "Application submitted by " . $dbrow->form_data->first_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by " . $dbrow->form_data->first_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
            }
     
        
        $postdata = array(
               'application_ref_no' => $dbrow->service_data->appl_ref_no,
               'first_name'=> $dbrow->form_data->first_name,
               'last_name' =>$dbrow->form_data->last_name,
               'father_name'=>$dbrow->form_data->father_name,
               'mobile_no'=>$dbrow->form_data->mobile_number,
               'email_id'=>$dbrow->form_data->email,
               //'gender'=>$dbrow->form_data->applicant_gender,
               'gender'=>"1",
               'Profession'=>$dbrow->form_data->profession,
               'age'=>$dbrow->form_data->age,
               'nationality'=>$dbrow->form_data->nationality,
               'community'=>$dbrow->form_data->community,

               'p_address1'=>$dbrow->form_data->pa_address_line_1,
               'p_address2'=>$dbrow->form_data->pa_address_line_2,
               'p_address3'=>$dbrow->form_data->pa_address_line_3,
               'p_country'=>"356",//india
               'p_state'=>"18",//Assam
               'p_district'=>"282",//
               'p_pincode'=>$dbrow->form_data->pa_pin_code,
               'p_village'=>$dbrow->form_data->pa_village,
               'p_postoffice'=>$dbrow->form_data->pa_post_office,
               'p_policestation'=>$dbrow->form_data->pa_police_station,

               'c_address1'=>$dbrow->form_data->ca_address_line_1,
               'c_address2'=>$dbrow->form_data->ca_address_line_2,
               'c_address3'=>$dbrow->form_data->ca_address_line_3,
               'c_country'=>"356",
               'c_state'=>"18",
               'c_district'=>"618",
               'c_pincode'=>$dbrow->form_data->ca_pin_code,
               'c_village'=>$dbrow->form_data->ca_village,
               'c_postoffice'=>$dbrow->form_data->ca_post_office,
               'c_policestation'=>$dbrow->form_data->ca_police_station,

               'market'=>$dbrow->form_data->market_place,
               'commodities'=>$dbrow->form_data->commodities,
               'workingunit'=>$dbrow->form_data->location,
               'employees'=>$dbrow->form_data->govt_emp,
               'tradingarea'=>$dbrow->form_data->trade_area,
               //'employees'=>"1",
               //'tradingarea'=>"1",
               'sub_loc'=>$dbrow->form_data->circle_id,
               'spId' => array('applId' => $dbrow->service_data->appl_id),
            );

        if($dbrow->service_data->appl_status === "QS"){
           $postdata['revert'] = "NA";
        }
        if(!empty($dbrow->form_data->passport_pic)){
                $passport_pic = base64_encode(file_get_contents(FCPATH.$dbrow->form_data->passport_pic));

                $passportpic = array(
                    "encl" =>  $passport_pic,
                    "docType" => "image/jpeg",
                    "enclFor" => "Passport size photograph",
                    "enclType" => $dbrow->form_data->passport_pic_type,
                    "id" => "83941",
                    "doctypecode" => "5314",
                    "docRefId" => "5325",
                    "enclExtn" => "jpeg"
                );

                $postdata['passportpic'] = $passportpic;
            }

            if(!empty($dbrow->form_data->soft_doc)){
                $soft_doc = base64_encode(file_get_contents(FCPATH.$dbrow->form_data->soft_doc));

                $softdoc = array(
                    "encl" =>  $soft_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Upload Scanned Copy of the Application Form",
                    "enclType" => $dbrow->form_data->soft_doc_type,
                    "id" => "85317",
                    "doctypecode" => "5317",
                    "docRefId" => "5317",
                    "enclExtn" => "pdf"
                );

                $postdata['softdoc'] = $softdoc;
            }

            if(!empty($dbrow->form_data->doc)){
                $doc = base64_encode(file_get_contents(FCPATH.$dbrow->form_data->doc));

                $documents = array(
                    "encl" =>  $doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Document",
                    "enclType" => $dbrow->form_data->doc_type,
                    "id" => "83940",
                    "doctypecode" => "8293",
                    "docRefId" => "6981",
                    "enclExtn" => "pdf"
                );

                $postdata['documents'] = $documents;
            }

            //POST APPLICATION to NCHAC 
            $url = $this->config->item('nctp_post_url');
            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
            //pre(json_encode($postdata));

            curl_close($curl);
            if ($response) {
                $response = json_decode($response);
                //pre($response);
                log_response($dbrow->service_data->appl_ref_no,$response);//log the response from external API
                if ($response->ref->status === "success") {
                    $data_to_update = array(
                        'form_data.status'=>'submitted',
                        'form_data.edistrict_ref_no'=>$response->ref->edistrict_ref_no,
                        'service_data.appl_status' => 'submitted',
                        'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        'processing_history' => $processing_history
                    );
                    $this->tradepermit_model->update($objId, $data_to_update);

                    //Sending submission SMS
                    $nowTime = date('Y-m-d H:i:s');
                    $sms = array(
                        "mobile" => (int)$dbrow->form_data->mobile_number,
                        "applicant_name" => $dbrow->form_data->first_name,
                        "service_name" => $serviceName,
                        "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                        "app_ref_no" => $dbrow->service_data->appl_ref_no
                    );
                    sms_provider("submission", $sms);
                    
                    //generate acknowlegement if success
                     if($dbrow->service_data->appl_status === "QS"){
                        $this->session->set_flashdata('success','Query Replied Successfully');
                        redirect('spservices/trade_permit/Application/view/'.$objId);
                    }
                    else{
                        //generate acknowlegement if success
                        redirect('spservices/trade_permit/Acknowledgement/acknowledgement/'.$objId);
                    }
                    
                } else {
                    //redierct to application page if failure
                    $this->session->set_flashdata('error','Something went wrong please try again.');
                    redirect('spservices/trade_permit/Application/index/'.$objId);
                }
            }
        }
    }

    function getID($length)
    {
        $rtps_trans_id = $this->generateID($length);
        while ($this->tradepermit_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        } //End of while()
        return $rtps_trans_id;
    } //End of getID()

    public function generateID($length)
    {
        $date = date('Y');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-NC-TP/" . $date . "/" . $number;
        return $str;
    } //End of generateID()

    public function query($objId=null) {
        if ($this->checkObjectId($objId)) {

            //$filter = array("_id"=> new ObjectId($objId), "appl_status"=>"QS","service_id" => $this->serviceId);
            $filter = array("_id"=> new ObjectId($objId));
            $dbRow = $this->tradepermit_model->get_row($filter);
            //pre($dbRow);
            if($dbRow) {
                //pre($dbRow);
                $data=array(
                    "service_name"=>$this->serviceName,
                    "dbrow"=>$dbRow
                );
                //$data["sro_dist_list"] = $this->sros_model->sro_dist_list();
                $this->load->view('includes/frontend/header');
                $this->load->view('trade_permit/application',$data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found in query mode against object id : '.$objId);
                redirect('spservices/trade_permit/application');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/trade_permit/application');
        }//End of if else
    }//End of query()

    public function track($objId=null) {
        $dbRow = $this->tradepermit_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('trade_permit/tptrack_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/trade_permit/');
        }//End of if else
    }//End of track()

    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
           return false;
        }//End of if else
    }//End of checkObjectId()

    public function querypaymentsubmit($obj = null){
        if($obj){
            $dbRow = $this->tradepermit_model->get_by_doc_id($obj);
            //pre($dbRow);
            if(count((array)$dbRow)) {

                if ($dbRow->service_data->appl_status == "QA") {
                    $this->my_transactions();
                }

                /*
                $processing_history = $dbRow->processing_history??array();
                    $processing_history[] = array(
                        "processed_by" => "Payment Query submitted by ".$dbRow->form_data->first_name,
                        "action_taken" => "Payment Query submitted",
                        "remarks" => "Payment Query submitted by ".$dbRow->form_data->first_name." and <a href=\"".base_url('spservices/trade_permit/Acknowledgement/payment_acknowledgement/'.$obj)."\" target=\"_blank\"><b>Payment Acknowledment</b></a>",
                            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
 
                    $data = array(
                        "service_data.appl_status" => "QA",
                        'processing_history' => $processing_history,
                    );
         
                    $this->tradepermit_model->update_where(['_id' => new ObjectId($obj)], $data);
 
                    $this->session->set_flashdata('success','Your application has been successfully updated');
                    redirect('spservices/trade_permit/Acknowledgement/payment_acknowledgement/'.$obj);
                */


                $postdata = array(
                    "payment_ref_number" => $dbRow->form_data->query_payment_response->GRN,
                    "fee_paid" => $dbRow->service_data->amount,
                    "application_ref_no" => $dbRow->service_data->appl_ref_no,
                    "payment_mode" =>"online",
                    'spId' => array('applId' => $dbRow->service_data->appl_id)
                ); 
                //POST PAYMENT INFO to NCHAC
                $json_obj = json_encode($postdata);
                //pre($json_obj); 
                $url=$this->config->item('nctp_payment_url');
                //$curl = curl_init("https://nchacartps.in/testsite/update_tp.php");
                $curl = curl_init($url); 
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));                 
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
 
                log_response($dbRow->service_data->appl_ref_no, $response);
 
                if(isset($error_msg)) {
                    die("CURL ERROR : ".$error_msg);
                } elseif ($response) {
                    $response = json_decode($response, true);  //pre($response);
                    if ($response["ref"]["status"] === "success") {
                    //pre($response);
                    $processing_history = $dbRow->processing_history??array();
                    $processing_history[] = array(
                        "processed_by" => "Payment Query submitted by ".$dbRow->form_data->first_name,
                        "action_taken" => "Payment Query submitted",
                        "remarks" => "Payment Query submitted by ".$dbRow->form_data->first_name." and <a href=\"".base_url('spservices/trade_permit/Acknowledgement/payment_acknowledgement/'.$obj)."\" target=\"_blank\"><b>Payment Acknowledment</b></a>",
                            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
 
                    $data = array(
                        "service_data.appl_status" => "QA",
                        'processing_history' => $processing_history,
                    );
         
                    $this->tradepermit_model->update_where(['_id' => new ObjectId($obj)], $data);
 
                    $this->session->set_flashdata('success','Your application has been successfully updated');
                    redirect('spservices/trade_permit/Acknowledgement/payment_acknowledgement/'.$obj);
                    } else {
                        $this->session->set_flashdata('fail','Unable to update data!!! Please try again.');
                        $this->queryform($obj);
                    }//End of if else
                }//End of if
            } else {
                $this->session->set_flashdata('fail','Unable to update data!!! Please try again.');
                $this->index();
            }//End of if else
        }

        redirect('iservices/transactions');
    }

     function get_districts() {
            $field_name = "ca_district";
            $slc = (int)$this->input->post("field_value"); 

            echo '<select name="'.$field_name.'" id="'.$field_name.'" class="form-control ca_district">';
            if(strlen($slc)) {
                $this->load->model('trade_permit/District_model');
                $districts = $this->district_model->get_distinct_results(array("slc" => $slc));
                if ($districts) {
                    echo "<option value=''>Please Select</option>";
                    foreach ($districts as $district) {
                        echo '<option value="' . $district->district_name_english . '" >' . $district->district_name_english . '</option>';
                    }//End of foreach()
                } else {
                    echo "<option value=''>No records found</option>";
                }//End of if else
            } else {
                echo "<option value=''>Country cannot be empty</option>";
            }//End of if else
            echo '</select>';
    }//End of get_districts()


}//End of Registration
