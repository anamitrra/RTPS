<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registereddeed extends Rtps {
    private $serviceName = "Application for Certified Copy of Registered Deed";
    Private $serviceId = "CRCPY";
    Private $url = "https://ngdrs.assam.gov.in/NGDRS_ASM_DEMO/CertifiedCopy/getApplicationDetails/";
    public function __construct() {
        parent::__construct();
        $this->load->model('registered_deed_model');
        $this->load->model('necprocessing_model');
        $this->load->helper("cifileupload");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
          }else{
            $this->slug = "user";
          }

    }//End of __construct()

  

    public function index($obj_id=null) {
        check_application_count_for_citizen();       
        $data=array("pageTitle" => "Application for Certified Copy of Registered Deed");
        $filter = array("_id" =>new ObjectId($obj_id), "status" => "DRAFT");
        $data["dbrow"] = $this->registered_deed_model->get_row($filter);
        $data['usser_type']=$this->slug;
        $data["sro_dist_list"] = $this->registered_deed_model->sro_dist_list();
       
        $this->load->view('includes/frontend/header');
        $this->load->view('deed/registered_deed',$data);
        $this->load->view('includes/frontend/footer');
    }//End of index()
    public function getlocation(){
        $id=$_GET['id'];
        if( $id){
            $data = $this->registered_deed_model->get_sro_list( $id);
            if( $data){
                echo json_encode( $data);
            }else{
                echo json_encode(array());
            }
        }
       
    }
    public function submit(){
        $objId = $this->input->post("obj_id");
        $rtps_trans_id = $this->input->post("rtps_trans_id");
        $submitMode = $this->input->post("submit_mode");
        $this->form_validation->set_rules('applicant_name', 'Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
 
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean|strip_tags');
        
        // $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('relation', 'Relation', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('deed_nature', 'Nature of deed', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('service_mode', 'Service Mode', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_district', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('sro_code', 'Submission Loacation', 'trim|required|xss_clean|strip_tags');
        
        // $this->form_validation->set_rules('inputcaptcha', 'Captcha', 'required|exact_length[6]');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $obj_id = strlen($objId)?$objId:null;
            $this->index($obj_id);
        } else {                               
            
            $inputCaptcha = $this->input->post("inputcaptcha");
            $sessCaptcha = $this->session->userdata('captchaCode');
            
            $rtps_trans_id = $this->getID(7); 
            $sessionUser=$this->session->userdata();
            $input= $this->input->post();
            $input['applId']=$objId;//uniqid();
            $input['service_name']="Application for Certified Copy of Registered Deed";
            $input['service_id']="CRCPY";
            $input['user_type']=$this->slug;
            $input['status']="DRAFT";
            $input['submit_mode']=$submitMode;
            $input['rtps_trans_id']=$rtps_trans_id;
            if($this->slug !== "user"){
                if($this->slug === "CSC"){
                    $input['applied_by'] = $sessionUser['userId'];
                } else {
                    $input['applied_by'] = new ObjectId($this->session->userdata('userId')->{'$id'});
                }
            }
            $input['created_at'] =  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
            
             unset($input['obj_id']);
                          
                if(strlen($objId)) {
                    $this->registered_deed_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                    $this->session->set_flashdata('success','Your application has been successfully submitted');
                    // redirect('spservices/registereddeed/fileuploads/'.$objId);
                    redirect('spservices/registereddeed/preview/'.$objId);

                } else {
                    $insert=$this->registered_deed_model->insert($input);
                    if($insert){
                        $objectId=$insert['_id']->{'$id'};
                        $this->registered_deed_model->update_where(['_id' => new ObjectId($objectId)], array('applId'=>$objectId));
                        $this->session->set_flashdata('success','Your application has been successfully submitted');
                        // redirect('spservices/registereddeed/fileuploads/'.$objectId);
                        redirect('spservices/registereddeed/preview/'.$objectId);
                        exit();
                    } else {
                        $this->session->set_flashdata('fail','Unable to submit data!!! Please try again.');
                        $this->index();
                    }//End of if else
                }//End of if else
            
        }//End of if else
    }//End of submit()
    
    public function fileuploads($objId=null) {     
        $dbRow = $this->registered_deed_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
                "pageTitle" => "Upload Attachments for Application for Issuance Of Scheduled Caste Certificate",
                "obj_id"=>$objId,
                "rtps_trans_id" => $dbRow->rtps_trans_id
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('deed/registered_deep_enclosure',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/registereddeed/');
        }//End of if else
    }//End of fileuploads()

    public function submit_to_backend($obj,$show_ack=false){
        if($obj){
            $dbRow = $this->registered_deed_model->get_by_doc_id($obj);

             //procesing data
             $processing_history = $dbRow->processing_history??array();
             $processing_history[] = array(
                 "processed_by" => "Application submitted & payment made by KIOSK for ".$dbRow->applicant_name,
                 "action_taken" => "Application Submition",
                 "remarks" => "Application submitted & payment made by KIOSK for ".$dbRow->applicant_name,
                 "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
             );
             if(property_exists($dbRow,"serial_registration_not_availabe") && $dbRow->serial_registration_not_availabe === "1"){
                 $additional_data=json_encode( array(
                     'year_from'=>$dbRow->year_from,
                     'year_to'=>$dbRow->year_to,
                     'party_name'=>$dbRow->deed_party_name,
                     'patta_no'=>$dbRow->deed_patta_no,
                     "daag_no"=>$dbRow->deed_dag_no,
                     "land_area"=>$dbRow->deed_total_land_area

                 ));
              
                $reg_no_available_flag=false;
                $deedno='';
             }else{
                 $reg_no_available_flag=true;
                 $additional_data='';
                $deedno= $dbRow->year_of_registration.'/'.$dbRow->deedno ;//"SL No: ".$dbRow->deedno.",Reg No: ".$dbRow->year_of_registration;
             }
            $postdata=array(
                'deed_no'=> $deedno,
                'reg_no_available_flag'=>$reg_no_available_flag,
                'additional_data'=>$additional_data,
                'applicant_name'=>$dbRow->applicant_name,
                'mobile'=>$dbRow->mobile,
                'address'=>$dbRow->address,
                'relation'=>$dbRow->relation,
                'date_of_application'=>date('Y-m-d'),
                'service_mode'=>$dbRow->service_mode,
                'application_ref_no'=>$dbRow->rtps_trans_id,
                'sro_code'=>!empty($dbRow->sro_code) ? $dbRow->sro_code : "",
                'spId'=>array('applId'=>$dbRow->applId)
            );
            $url=$this->url;//'https://ngdrs.assam.gov.in/NGDRS_ASM_DEMO/CertifiedCopy/getApplicationDetails/'; //$this->config->item('url');
            // $curl = curl_init($url."cercpy/post_certicopy.php");
            $curl = curl_init($url);
             curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
          

            curl_close($curl);
           if($response){
            $response = json_decode($response);
           
            if($response->ref->status === "success"){
                $data_to_update=array(
                    'status'=>'submitted',
                    'submission_date'=>new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'processing_history'=>$processing_history
                );
                $this->registered_deed_model->update($obj,$data_to_update);
                 //Add to processing history        
                if($show_ack){
                    return true;
                }
            }
               
           }
        }
        redirect('iservices/transactions');
    }
    public function finalsubmition(){
         if( $this->slug !== "user"){
            return false;
        }
        $obj=$this->input->post('obj');
        if($obj){
            $dbRow = $this->registered_deed_model->get_by_doc_id($obj);
               //procesing data
               $processing_history = $dbRow->processing_history??array();
               $processing_history[] = array(
                   "processed_by" => "Application submitted by ".$dbRow->applicant_name,
                   "action_taken" => "Application Submition",
                   "remarks" => "Application submitted by ".$dbRow->applicant_name,
                   "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
               );
           
             if(property_exists($dbRow,"serial_registration_not_availabe") && $dbRow->serial_registration_not_availabe === "1"){
                $additional_data=json_encode( array(
                    'year_from'=>$dbRow->year_from,
                    'year_to'=>$dbRow->year_to,
                    'party_name'=>$dbRow->deed_party_name,
                    'patta_no'=>$dbRow->deed_patta_no,
                    "daag_no"=>$dbRow->deed_dag_no,
                    "land_area"=>$dbRow->deed_total_land_area

                ));
             
               $reg_no_available_flag=false;
               $deedno='';
            }else{
                $reg_no_available_flag=true;
                $additional_data='';
               $deedno= $dbRow->year_of_registration.'/'.$dbRow->deedno ;//"SL No: ".$dbRow->deedno.",Reg No: ".$dbRow->year_of_registration;
            }
            $postdata=array(
                'deed_no'=> $deedno,
                'reg_no_available_flag'=>$reg_no_available_flag,
                'additional_data'=>$additional_data,
                'applicant_name'=>$dbRow->applicant_name,
                'mobile'=>$dbRow->mobile,
                'address'=>$dbRow->address,
                'relation'=>$dbRow->relation,
                'date_of_application'=>date('Y-m-d'),
                'service_mode'=>$dbRow->service_mode,
                'application_ref_no'=>$dbRow->rtps_trans_id,
                'sro_code'=>!empty($dbRow->sro_code) ? $dbRow->sro_code : "",
                'spId'=>array('applId'=>$dbRow->applId)
            );

            $url=$this->url;
           // $url='https://ngdrs.assam.gov.in/NGDRS_ASM_DEMO/CertifiedCopy/getApplicationDetails/'; //$this->config->item('url');
            // $curl = curl_init($url."cercpy/post_certicopy.php");
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
           

            curl_close($curl);
           if($response){
            $response = json_decode($response);
           
            if($response->ref->status === "success"){
                $data_to_update=array(
                    'status'=>'submitted',
                    'submission_date'=>new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'processing_history'=>$processing_history
                );
                $this->registered_deed_model->update($obj,$data_to_update);

                return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array("status"=>true)));
            }else{
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(array("status"=>false)));
            }
               
           }
        }
        // return json_encode(array("resp"=>"dd"));
        //pre($this->input->post());
    }
    public function submitfiles() {        
        $objId = $this->input->post("obj_id");
        $rtps_trans_id = $this->input->post("rtps_trans_id");
        $this->form_validation->set_rules('soft_copy_type', 'Soft copy', 'required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->fileuploads($objId);
        } else {
         
          
            $softCopy = cifileupload("soft_copy");
            $soft_copy = $softCopy["upload_status"]?$softCopy["uploaded_path"]:null;

            $data = array(
                'soft_copy_type' => $this->input->post("soft_copy_type"),
                "soft_copy" => $soft_copy
            );
            $this->registered_deed_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success','Your application has been successfully submitted');
            redirect('spservices/registereddeed/preview/'.$objId);
        }//End of if else
    }//End of submitfiles()
    
    public function preview($objId=null) { 
        $filter = array("_id" => new ObjectId($objId));
        $dbRow = $this->registered_deed_model->get_row($filter);
        if(count((array)$dbRow)) {
            $data=array(
                "pageTitle" => "Application for Certified Copy of Registered Deed",
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('deed/registereddeed_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/registereddeed/');
        }//End of if else
    }//End of preview()
    
    function createcaptcha() {
        $captchaDir = "storage/captcha/";
        array_map('unlink', glob("$captchaDir*.jpg"));

        $this->load->helper('captcha');
        $config = array(
            'img_path' => './storage/captcha/',
            'img_url' => base_url('storage/captcha/'),
            'font_path' => APPPATH.'sys/fonts/texb.ttf',
            'img_width' => '150',
            'img_height' => 30,
            'expiration' => 7200,
            'word_length' => 6,
            'font_size' => 16,
            'img_id' => 'capimg',
            'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(94, 20, 38),
                'text' => array(0, 0, 0),
                'grid' => array(178, 184, 194)
            )
        );
        $captcha = create_captcha($config);        
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode', $captcha['word']);
        echo $captcha['image'];
    }//End of createcaptcha()
    
    function getID($length) {
        $rtps_trans_id = $this->generateID($length);
        while ($this->registered_deed_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        }//End of while()
        return $rtps_trans_id;
    }//End of getID()

    public function generateID($length) {
        $date = date('Y');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-CRCPY/" . $date."/" .$number;
        return $str;
    }//End of generateID()
    public function query($objId=null) {
        $filter = array("_id" =>new ObjectId($objId),"service_id"=>'CRCPY','status'=>'QS');
        $data["dbrow"] = $this->registered_deed_model->get_row($filter);
        $data['serial_registration_not_availabe']=( property_exists($data["dbrow"] , 'serial_registration_not_availabe') && $data["dbrow"]->serial_registration_not_availabe === "1" ) ? true:false;
       $remarkds= isset($data["dbrow"]->normal_query->wsResponse) ? json_decode($data["dbrow"]->normal_query->wsResponse) : array();
       $data['remark']=isset($remarkds->remark) ? $remarkds->remark : '';
        $data["sro_dist_list"] = $this->registered_deed_model->sro_dist_list();
        // pre( $dbRow);
        if( $data["dbrow"]) {
            $this->load->view('includes/frontend/header');
            $this->load->view('deed/query_perview',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/registereddeed/');
        }//End of if else
     }//End of query()
    public function querysubmit() {
        $objId = $this->input->post("obj_id");
        $applId = $this->input->post("applId");
        $rtps_trans_id = $this->input->post("rtps_trans_id");
        $this->form_validation->set_rules('applicant_name', 'Name', 'trim|required|xss_clean|strip_tags|max_length[255]');
     
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean|strip_tags');
        
        // $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('relation', 'Relation', 'trim|required|xss_clean|strip_tags');
       
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->query($objId);
        } else {

            // $inputCaptcha = $this->input->post("inputcaptcha");
            // $sessCaptcha = $this->session->userdata('captchaCode');

            // if($sessCaptcha !== $inputCaptcha) {
            //     $this->session->set_flashdata("error", "Captcha does not mached!. Please try again");
            //     $this->query($objId);
            //     return;
            // }

            $attachment1 = cifileupload("attachment1");
            $attachment_1 = $attachment1["upload_status"]?$attachment1["uploaded_path"]:null;
         
            $data_to_update = array(
                'applicant_name' => $this->input->post("applicant_name"),
                'mobile' => $this->input->post("mobile"),
                'address' => $this->input->post("address"),
                'email' => $this->input->post("email"),
                'relation' => $this->input->post("relation"),
                'deedno' => $this->input->post('deedno'),
                'year_of_registration' => $this->input->post("year_of_registration"),
                "attachment1" => $attachment_1,
            );

            $res=$this->registered_deed_model->update($objId, $data_to_update);
            if( $res->getMatchedCount()){
                $dbRow = $this->registered_deed_model->get_by_doc_id($objId);
                // var_dump(FCPATH);
                // var_dump("Echhh    " );
                // var_dump($attachment_1);die;
                    //  $landPatta = $dbrow->land_patta?base64_encode(file_get_contents(FCPATH.$dbrow->land_patta)):null;
                      
                    if(property_exists($dbRow,"serial_registration_not_availabe") && $dbRow->serial_registration_not_availabe === "1"){
                        $deedno="Year From: ".$this->input->post("year_from").",Year To: ".$this->input->post("year_to").",Party Name: ".$this->input->post("deed_party_name").",Patta No: ".$this->input->post("deed_patta_no").",Daag No: ".$this->input->post("deed_dag_no").",Land Area: ".$this->input->post("deed_total_land_area");
                    }else{
                        $deedno="SL No: ".$this->input->post("deedno").",Reg Year: ".$this->input->post("year_of_registration");
                    }
                    $postdata=array(
                        "Ref"=>array(
                         "applicant_name"=>$this->input->post("applicant_name"),
                         "deed_no"=>$deedno,
                         "application_status"=>$dbRow->status,
                        //  "deed_date"=>"EGRAS Assam",
                         "address"=>$this->input->post("address"),
                         "relation"=>$this->input->post("relation"),
                         "mobile"=>$this->input->post("mobile"),
                         "email"=>$this->input->post("email"),
                         "application_ref_no"=>$rtps_trans_id,
                         "attachment1"=>$attachment_1  ? base64_encode(file_get_contents(FCPATH.$attachment_1)):null,
                        ),
                        "spId"=>array(
                          "applId"=>$applId
                        )
                        );
                        // pre($postdata);
                     $url="https://ngdrs.assam.gov.in/NGDRS_ASM_DEMO/CertifiedCopy/getQueryResponse";
                    //$this->config->item('url');
                    //  $curl = curl_init($url."cercpy/fee_paid_status.php");
                     $curl = curl_init($url);
                      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                     curl_setopt($curl, CURLOPT_POST, true);
                     curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                     $res= curl_exec($curl);
                    
                  
                     curl_close($curl);
                 
                     if($res){
                       $res = json_decode($res);
                      
                       if($res->ref->status === "success"){
                         $this->registered_deed_model->update_row(
                           array('rtps_trans_id' => $rtps_trans_id),
                           array(
                             "qs_updated_on_backend" => true,
                             "status"=>'QA'
                           )
                         );

                       
                           //procesing data
                          
                            $processing_history = $dbRow->processing_history??array();
                            $processing_history[] = array(
                                "processed_by" => "Query Replied by ".$dbRow->applicant_name,
                                "action_taken" => "Query Replied By APPLICANT",
                                "remarks" => "Query Replied by ".$dbRow->applicant_name,
                                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            );
                       }
                       $this->session->set_flashdata('message','Query response submitted successfully');
                       redirect('iservices/transactions');
                     }
            }else{
                $this->session->set_flashdata('error','Something went wrong. Please try again');
            }
          
            redirect('spservices/registereddeed/query/'.$objId);
        }//End of if else        
    }//End of querysubmit()
   

}//End of Castecertificate
