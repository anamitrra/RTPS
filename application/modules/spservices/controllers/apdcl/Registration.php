<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps {
    private $serviceName = "Application for new Low Tension connection (APDCL)";

    public function __construct() {
        parent::__construct();
        $this->load->model('apdcl/registration_model');
        $this->load->helper("cifileupload");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->library('AES');
		    $this->encryption_key = $this->config->item("encryption_key");
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
          }else{
            $this->slug = "user";
          }
    }

    public function index($obj_id=null) {
       
        $data=array("pageTitle" => "Application for new Low Tension Connection (APDCL)");
        $filter = array("_id" =>new ObjectId($obj_id), "service_data.appl_status" => array('$in'=>["DRAFT","PR"]) );
        $data["dbrow"] = $this->registration_model->get_row($filter);
        $data['usser_type']=$this->slug;
        if(isset($data["dbrow"]->service_data) && $data["dbrow"]->service_data->appl_status === "PR" ){
          $this->submit_to_backend($obj_id);
          return;
        }
        check_application_count_for_citizen();
        //Get District API
        //$url = "https://uat.apdclrms.com/cbs/RestAPI/getAllDistricts";
        $url = "https://www.apdclrms.com/cbs/RestAPI/getAllDistricts";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $dist = curl_exec($curl);
        curl_close($curl);
        $dist = json_decode($dist);

        //Get Category API
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://www.apdclrms.com/cbs/RestAPI/crm/getAllCategoryList',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_HTTPHEADER => array(
            'Cookie: JSESSIONID=7F8234FDF30B6D1F7DB3858D314493D4; ARMSSESSIONID=YWVkNGVhMjktNTY1NC00OTk1LWFjM2UtYjQ1MGM2ZWFhMzM5'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $categories = json_decode($response, true);

        
        $this->load->view('includes/frontend/header');
        $this->load->view('apdcl/apdcl_form',array('data'=>$data, 'districts' => $dist, 'categories' => $categories));
        $this->load->view('includes/frontend/footer');
    }//End of index()

    public function submit_to_backend($obj_id){
      $this->registration_model->update_where(['_id' => new ObjectId($obj_id)],array("service_data.appl_status" => "PR"));
     // on success of ajax
      redirect('spservices/apdcl/registration/payment_success/'.$obj_id);
    }

    public function payment_success($obj_id){
      $dbRow = $this->registration_model->get_by_doc_id($obj_id);
      $data = array("obj_id"=>$obj_id, "dbrow"=>$dbRow);
      $this->load->view('includes/frontend/header');
      $this->load->view('apdcl/payment_success',$data);
    }

  public function submit(){

      $objId = $this->input->post("obj_id");
      $rtps_trans_id = $this->input->post("appl_ref_no");
      $submitMode = $this->input->post("submit_mode");
      
      $this->form_validation->set_rules('district_name','District name','required|xss_clean');
      $this->form_validation->set_rules('sub_division','Sub division','required|xss_clean');
      $this->form_validation->set_rules('appl_category','Applied category','required|xss_clean');
      $this->form_validation->set_rules('appl_load','Applied load','required|xss_clean');
      $this->form_validation->set_rules('no_of_days','No of days','required|less_than_equal_to[90]|xss_clean');
      $this->form_validation->set_rules('applicant_name','Applicant name','required|max_length[100]|xss_clean');
      $this->form_validation->set_rules('fathers_name','Fathers name','required|max_length[50]|xss_clean');
      $this->form_validation->set_rules('applicant_type','Applicant type','required|xss_clean');
      $this->form_validation->set_rules('gstn','gstn','reg_match[/^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([A-Z]{4}[0-9A-Z]{1}[0-9]{4}[A-Z]{1}[1-9]{1}[A-Z]{1}[0-9A-Z]{1})+$/]|xss_clean');
      $this->form_validation->set_rules('gmc','GMC','xss_clean');
      $this->form_validation->set_rules('assessment_id','Assessment id','xss_clean');
      $this->form_validation->set_rules('house_number','House number','max_length[14]|xss_clean');
      $this->form_validation->set_rules('by_lane','By lane','max_length[20]|xss_clean');
      $this->form_validation->set_rules('road','Road','max_length[20]|xss_clean');
      $this->form_validation->set_rules('area','Area','required|max_length[100]|xss_clean');
      $this->form_validation->set_rules('village_town','Village town','required|max_length[80]|xss_clean');
      $this->form_validation->set_rules('post_office','Post office','required|max_length[20]|xss_clean');
      $this->form_validation->set_rules('police_station','Police station','required|max_length[20]|xss_clean');
      $this->form_validation->set_rules('district','District','required|max_length[30]|xss_clean');
      $this->form_validation->set_rules('pin','Pin Code','required|exact_length[6]|xss_clean');
      $this->form_validation->set_rules('mobile_number','Mobile number','required|regex_match[/^[0-9]{10}$/]|xss_clean');
      $this->form_validation->set_rules('e_mail','Email','valid_email|xss_clean');
      $this->form_validation->set_rules('pan_no','PAN no','regex_match[/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/]|xss_clean');
      $this->form_validation->set_rules('premise_owner','Premise owner','required|xss_clean');
      $this->form_validation->set_rules('distance_pole_30','Distance of pole','required|xss_clean');
      $this->form_validation->set_rules('electricity_due','Electricity due','required|xss_clean');
      $this->form_validation->set_rules('existing_connection','Existing connection','required|xss_clean');	
      $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
      
      if ($this->form_validation->run() == FALSE) {
          $this->session->set_flashdata('error',validation_errors());
          $obj_id = strlen($objId)?$objId:null;
          $this->index($obj_id);
      } else {  
        $sessionUser=$this->session->userdata();
        $rtps_trans_id = $this->getID(7);
          if($this->slug === "CSC"){
                $apply_by = $sessionUser['userId'];
          } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
          }
        $submission_location = $this->input->post("sub_division");

        $service_data = [
          "appl_ref_no" => $rtps_trans_id,
          'user_id' => $sessionUser['userId']->{'$id'},
          "department_id" => 'apdcl',
          "department_name" => 'Assam Power Distribution Company Limited',
          "service_name" => "Application for new Low Tension connection (APDCL)",
          "service_id" => "apdcl1",
          "appl_status" => "DRAFT",
          "user_type" => $this->slug, 
          "submission_mode" => $submitMode, 
          "applied_by" => $apply_by,
          "submission_location" => $submission_location,
          "submission_date" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
          "service_timeline" => "30",
          ];
        $appl_category = substr($this->input->post("appl_category", TRUE),0,2);
        $sub_division = substr($this->input->post("sub_division", TRUE),0,3);
        
        $post_data = [
            "district_name" => $this->input->post("district_name"),
            "sub_division" => $sub_division,
            "appl_category" => $appl_category,
            "appl_load" => $this->input->post("appl_load"),
            "no_of_days" =>  $this->input->post("no_of_days"),
            "applicant_name" => $this->input->post("applicant_name"),
            "fathers_name" => $this->input->post("fathers_name"),
            "applicant_type" => $this->input->post("applicant_type"),
            "gstn" => $this->input->post("gstn"),
            "gmc" => $this->input->post("gmc"),
            "assessment_id" => $this->input->post("assessment_id"),
            "house_number" => $this->input->post("house_number"),
            "by_lane" => $this->input->post("by_lane"),
            "road" => $this->input->post("road"),
            "area" => $this->input->post("area"),
            "village_town" => $this->input->post("village_town"),
            "post_office" => $this->input->post("post_office"),
            "police_station" => $this->input->post("police_station"),
            "district" => $this->input->post("district"),
            "pin" => $this->input->post("pin"),
            "mobile_number" => $this->input->post("mobile_number"),
            "e_mail" => $this->input->post("e_mail"),
            "pin" =>  $this->input->post("pin"),
            "pan_no" =>  $this->input->post("pan_no"),
            "nearest_consumer_no" => $this->input->post("nearest_consumer_no"),
            "premise_owner" =>  $this->input->post("premise_owner"),
            "distance_pole_30" =>  $this->input->post("distance_pole_30"),
            "electricity_due" =>  $this->input->post("electricity_due"),
            "existing_connection" =>  $this->input->post("existing_connection"),
            "existing_cons_no" =>  $this->input->post("existing_cons_no"),
            "existing_connected_load" =>  $this->input->post("existing_connected_load"),  
            "created_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            "message" => "",
            "application_no" => ""
        ]; 
        if (strlen($objId)) {
          $post_data["identity_attach"] = $this->input->post("identity_attach");
          $post_data["address_attach"] = $this->input->post("address_attach");
          $post_data["land_attach"] = $this->input->post("land_attach");
          $post_data["identityFile"] = $this->input->post("identityFile");
          $post_data["addressFile"] = $this->input->post("addressFile");
          $post_data["selffAttestedFile"] = $this->input->post("selffAttestedFile");
          $post_data["testReportFile"] = $this->input->post("testReportFile");
          $post_data["scannedPhoto"] = $this->input->post("scannedPhoto");
          if (!empty($this->input->post("gmcFile"))) {
                $form_data["gmcFile"] = $this->input->post("gmcFile");
          }
          if (!empty($this->input->post("nocFile"))) {
                $form_data["nocFile"] = $this->input->post("nocFile");
        }
        }
        
        $inputs = [
          'service_data'=>$service_data,
          'form_data' => $post_data
        ];
        if(strlen($objId)){
            $this->registration_model->update_where(['_id' => new ObjectId($objId)],$inputs);
            $this->session->set_flashdata('success','Your application has been successfully submitted');
            redirect('spservices/apdcl/registration/fileUpload/'.$objId);
        }else{
            $insert=$this->registration_model->insert($inputs);
            if($insert){
                $objectId=$insert['_id']->{'$id'};
                $this->session->set_flashdata('success','Your application has been successfully submitted');
                redirect('spservices/apdcl/registration/fileUpload/'.$objectId);
                exit();
            } else {
                $this->session->set_flashdata('fail','Unable to submit data!!! Please try again.');
                $this->index();
            }
        }
          
      }
  }

  public function db_update(){
         $txnNo =  $this->input->post('txnNo');        
		     $appNo =  $this->input->post('appNo');
         $message =  $this->input->post('message');
         $newData = array(
              "form_data.application_no" => $appNo,
              "form_data.applStatus"=> "Application Received",
              "service_data.appl_status" => "submitted",
              "form_data.message" => $message,
              "service_data.submission_date" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
         );
		 $this->registration_model->update_where(['_id' => new ObjectId($txnNo)],$newData);
     
  }

  function getID($length) { 
    $rtps_trans_id = $this->generateID($length);
    while ($this->registration_model->get_row(["appl_ref_no" => $rtps_trans_id])) {
        $rtps_trans_id = $this->generateID($length);
    }
    return $rtps_trans_id;
  }

  public function generateID($length) {
    $date = date('Y');
    $number = '';
    for ($i = 0; $i < $length; $i++) {
        $number .= rand(0, 9);
    }
    $str = "RTPS-APDCL/" . $date."/" .$number;
    return $str;
  }   

  public function fileUpload($objId=null){
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
               "service_name"=>$this->serviceName,
               "pageTitle" => "Attached Enclosures for ".$this->serviceName,
                "obj_id"=>$objId,               
                "dbrow" => $dbRow
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('apdcl/apdcl_fileupload',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/apdcl/registration');
        }       
  }

  public function submitFiles(){

        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules('identity_attach','Identity attach','required');
        $this->form_validation->set_rules('address_attach','Address attach','required');
        $this->form_validation->set_rules('land_attach','Land attach','required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $identityFile = cifileupload_apdcl("identityFile");
        $identity_File = $identityFile["upload_status"]?$identityFile["uploaded_path"]:null;

        $addressFile = cifileupload_apdcl("addressFile");
        $address_File = $addressFile["upload_status"]?$addressFile["uploaded_path"]:null;

        $selffAttestedFile = cifileupload_apdcl("selffAttestedFile");
        $selffAttested_File = $selffAttestedFile["upload_status"]?$selffAttestedFile["uploaded_path"]:null;

        $testReportFile = cifileupload_apdcl("testReportFile");
        $testReport_File = $testReportFile["upload_status"]?$testReportFile["uploaded_path"]:null;

        $scannedPhoto = cifileupload_apdcl("scannedPhoto");
        $scanned_Photo = $scannedPhoto["upload_status"]?$scannedPhoto["uploaded_path"]:null;

        $gmcFile = cifileupload_apdcl("gmcFile");
        $gmc_File = $gmcFile["upload_status"]?$gmcFile["uploaded_path"]:null;

        $nocFile = cifileupload_apdcl("nocFile");
        $noc_File = $nocFile["upload_status"]?$nocFile["uploaded_path"]:null;

        $uploadedFiles = array(
          "identityFile_old" => strlen($identity_File)?$identity_File:$this->input->post("death_proof_old"),
          "addressFile_old" => strlen($address_File)?$address_File:$this->input->post("doc_for_relationship_old"),
          "selffAttestedFile_old" => strlen($selffAttested_File)?$selffAttested_File:$this->input->post("affidavit_old"),
          "testReportFile_old" => strlen($testReport_File)?$testReport_File:$this->input->post("soft_copy_old"),
          "scannedPhoto_old" => strlen($scanned_Photo)?$scanned_Photo:$this->input->post("soft_copy_old"),
          "gmcFile_old" => strlen($gmc_File)?$gmc_File:$this->input->post("soft_copy_old"),
          "nocFile_old" => strlen($noc_File)?$noc_File:$this->input->post("soft_copy_old"),
      );
      $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
           $this->session->set_flashdata('error',validation_errors());
           $this->fileUpload($objId);
        } else {
              $data = array(
                  'form_data.identity_attach' => $this->input->post("identity_attach"),
                  'form_data.identityFile' => strlen($identity_File)?$identity_File:$this->input->post("identityFile_old"),
                  'form_data.address_attach' => $this->input->post("address_attach"),
                  'form_data.addressFile' => strlen($address_File)?$address_File:$this->input->post("addressFile_old"),
                  'form_data.land_attach' => $this->input->post("land_attach"),
                  'form_data.selffAttestedFile' => strlen($selffAttested_File)?$selffAttested_File:$this->input->post("selffAttestedFile_old"),
                  'form_data.testReportFile' => strlen($testReport_File)?$testReport_File:$this->input->post("testReportFile_old"),
                  'form_data.scannedPhoto' =>strlen($scanned_Photo)?$scanned_Photo:$this->input->post("scannedPhoto_old"),
                  'form_data.gmcFile' =>strlen($gmc_File)?$gmc_File:$this->input->post("gmcFile_old"),
                  'form_data.nocFile' =>strlen($noc_File)?$noc_File:$this->input->post("nocFile_old")
              );
             
            $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success','Your application has been successfully submitted');
            redirect('spservices/apdcl/registration/preview/'.$objId);
        }
  }

  public function preview($objId=null) {
    $dbRow = $this->registration_model->get_by_doc_id($objId);
    if(count((array)$dbRow)) {
        $data=array(
            "service_name"=>$this->serviceName,
            "dbrow"=>$dbRow,
            "user_type"=> $this->slug
        );
        $this->load->view('includes/frontend/header');
        $this->load->view('apdcl/apdcl_preview',$data);
        $this->load->view('includes/frontend/footer');
    } else {
        $this->session->set_flashdata('error','No records found against object id : '.$objId);
        redirect('spservices/apdcl/registration');
    }
  }

  public function getDbData($objId) 
  {      
      $result = $this->registration_model->get_by_doc_id($objId);

      $identity = base64_encode(file_get_contents(FCPATH . $result->form_data->identityFile));
      $address = base64_encode(file_get_contents(FCPATH . $result->form_data->addressFile));
      $self = base64_encode(file_get_contents(FCPATH . $result->form_data->selffAttestedFile));
      $test = base64_encode(file_get_contents(FCPATH . $result->form_data->testReportFile));
      $photo = base64_encode(file_get_contents(FCPATH . $result->form_data->scannedPhoto));
      $gmc = strlen($result->form_data->gmcFile) ? base64_encode(file_get_contents(FCPATH . $result->form_data->gmcFile)) : '';
      $noc = strlen($result->form_data->nocFile) ? base64_encode(file_get_contents(FCPATH . $result->form_data->nocFile)) : '';

      //$gmc = isset($result->form_data->gmcFile) ? base64_encode(file_get_contents(FCPATH . $result->form_data->gmcFile)) : '';
      //$noc = isset($result->form_data->nocFile) ? base64_encode(file_get_contents(FCPATH . $result->form_data->nocFile)) : '';

      $data = [
        "ObjectId" => $objId,
        "sub_division" => $result->form_data->sub_division,
        "appl_category" => $result->form_data->appl_category,
        "appl_load" => $result->form_data->appl_load,
        "applicant_name" => $result->form_data->applicant_name,
        "fathers_name" => $result->form_data->fathers_name,
        "applicant_name" => $result->form_data->applicant_name,
        "applicant_type" => $result->form_data->applicant_type,
        "no_of_days" => $result->form_data->no_of_days,
        "gstn" => $result->form_data->gstn,
        "gmc" => $result->form_data->gmc,
        "assessment_id" => $result->form_data->assessment_id,
        "house_number" => $result->form_data->house_number,
        "by_lane" => $result->form_data->by_lane,
        "road" => $result->form_data->road,
        "area" => $result->form_data->area,
        "village_town" => $result->form_data->village_town,
        "post_office" => $result->form_data->post_office,
        "police_station" => $result->form_data->police_station,
        "district" => $result->form_data->district,
        "pin" => $result->form_data->pin,
        "mobile_number" => $result->form_data->mobile_number,
        "e_mail" => $result->form_data->e_mail,
        "pan_no" => $result->form_data->pan_no,
        "nearest_consumer_no" => $result->form_data->nearest_consumer_no,
        "premise_owner" => $result->form_data->premise_owner,
        "distance_pole_30" => $result->form_data->distance_pole_30,
        "electricity_due" => $result->form_data->electricity_due,
        "existing_connection" => $result->form_data->existing_connection,
        "existing_cons_no" => $result->form_data->existing_cons_no,
        "existing_connected_load" => $result->form_data->existing_connected_load,
        "identity_attach" => $result->form_data->identity_attach,
        "address_attach" => $result->form_data->address_attach,
        "land_attach" => $result->form_data->land_attach,
        "identityFile" =>  $identity,
        "addressFile" => $address,
        "selffAttestedFile" => $self,
        "testReportFile" => $test,
        "scannedPhoto" => $photo,
        "gmcFile" => $gmc,
        "nocFile" => $noc
        
      ];
      //pre($data);
      echo json_encode($data);   
  }

  public function trackAPI($obj_id){

      $dbrow = $this->registration_model->get_by_doc_id($obj_id);
      
      $applNo = isset($dbrow->form_data->application_no) ? $dbrow->form_data->application_no : ''; 
      $subDiv = isset( $dbrow->form_data->sub_division) ?  $dbrow->form_data->sub_division :'';
      
      if((isset($dbrow->service_data->appl_status) && ($dbrow->service_data->appl_status ==="D" || $dbrow->service_data->appl_status==="R")) || empty($applNo) ){
     
        redirect('spservices/apdcl/registration/tracking/'.$obj_id);
        return;
      }
      //Track API
     $url = 'https://www.apdclrms.com/cbs/onlinecrm/applicationStatus?applNo='.$applNo.'&applType=NSC&subDiv='.$subDiv;
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $track = curl_exec($curl);
      curl_close($curl);
      $track = json_decode($track,true);
     
      if(empty($track)){
        redirect('spservices/apdcl/registration/tracking/'.$obj_id);
        return;
      }
      foreach($track as $key)
      {
          $data = array(
            "applStatus" => $key['applStatus'],
            "applStatusId" => $key['applStatusId'],
            "bill_amount" => $key['bill_amount'],
            "billNo" => $key['billNo'], 
            "isBillPaid" => $key['isBillPaid'],
            "document" => $key['document'],
            "paymentLink" => $key['paymentLink'],
            "applView" => $key['applView'],
            "remarks" => $key['remarks'],
            "billDeskMsgUrl" => $key['billDeskMsgUrl'],
            "consNo" => $key['consNo'],
            "applNo" => $key['applNo'],
            "applHistory"=>$key['applHistory'],
            "processing_time" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
          );
      }

      if($data['applStatusId'] ==="16" || $data['applStatusId'] === 16){
        $st="R";
      }else if($data['applStatus']==="Connection Processed Successfully & CLosed"){
          $st="D";
      }else{
          $st=$dbrow->service_data->appl_status;
      }
      $history=$track[0]['applHistory'];
        $processing_history=array();
        foreach( $history as $his){
            array_push($processing_history,array(
                "processed_by" => "",
                "action_taken" => $his['action'],
                "remarks" => !empty($his['remarks']) ? $his['remarks']: $his['action'],
                'processing_time' => new UTCDateTime(strtotime( $his['executed_time']) * 1000))
            );
        }

      $processing_history_raw[] = $data;
      $data_to_update = [
        'service_data.appl_status'=>$st,
        'processing_history'=> $processing_history,
        'processing_history_raw'=> $processing_history_raw
      ];
     
      $this->registration_model->update($obj_id,$data_to_update);

      redirect('spservices/apdcl/registration/tracking/'.$obj_id);
  }

  public function tracking($obj_id){
       $dbrow = $this->registration_model->get_by_doc_id($obj_id);
       $data=array("dbrow"=>$dbrow);
        $this->load->view('includes/frontend/header');
        $this->load->view('apdcl/tracking_view',$data);
        $this->load->view('includes/frontend/footer');
  }

}
