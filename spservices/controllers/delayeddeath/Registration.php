<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps {
    
    private $serviceName = "Permission for Delayed Death Registration";
    Private $serviceId = "PDDR";

    public function __construct() {
        parent::__construct();
        $this->load->model('delayeddeath/registration_model');
        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->helper('smsprovider');
        $this->load->helper('log');
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        }else{
            $this->slug = "user";
        }
    }//End of __construct()

    public function index($obj_id=null) {

        check_application_count_for_citizen();

        $data=array("pageTitle" => "Permission for Delayed Death Registration");
        $filter = array(
            "_id" =>new ObjectId($obj_id), 
            "service_data.appl_status" => "DRAFT"
        );
        $data["dbrow"] = $this->registration_model->get_row($filter);
        $data['usser_type']=$this->slug;
       
        $this->load->view('includes/frontend/header');
        $this->load->view('delayeddeath/delayeddeath',$data);
        $this->load->view('includes/frontend/footer');
    }//End of index()

    public function submit(){
       // pre($this->input->post("others_type"));
        $objId = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");
        
        $this->form_validation->set_rules('applicant_name', 'Applicant name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|integer|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|strip_tags|valid_email');
        $this->form_validation->set_rules('relation_with_deceased', 'Relation with Deceased', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|xss_clean|strip_tags|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]|max_length[10]');
        $this->form_validation->set_rules('aadhar_no', 'Aadhar No.', 'trim|xss_clean|strip_tags|exact_length[12]');
        $this->form_validation->set_rules('father_name', 'Father Name', 'trim|required|xss_clean|strip_tags');

        if ($this->input->post("relation_with_deceased") == "Other") {
            $this->form_validation->set_rules('other_relation', 'Other Relation (If any)', 'trim|required|xss_clean|strip_tags');
        }

        $this->form_validation->set_rules('name_of_deceased', 'Name Of Deceased', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('deceased_gender', 'Deceased Gender', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('deceased_dod', 'Date of Death of the deceased', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('age_of_deceased', 'Age of the deceased', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('place_of_death', 'Place Of Death', 'trim|required|xss_clean|strip_tags'); 
        

        if (($this->input->post("place_of_death") == "Hospital") || ($this->input->post("place_of_death") == "House")) {
            $this->form_validation->set_rules('address_of_hospital_home', 'Address of deceased', 'trim|required|xss_clean|strip_tags'); 
        } elseif($this->input->post("place_of_death") == "Other"){
            $this->form_validation->set_rules('other_place_of_death', 'Other place of death', 'trim|required|xss_clean|strip_tags'); 
        }

        $this->form_validation->set_rules('reason_for_late', 'Reason for Late', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('father_name_of_deceased', 'Father name of the deceased', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('mother_name_of_deceased', 'Mother name of deceased', 'trim|required|xss_clean|strip_tags'); 
        
        $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('sub_division', 'Sub-Division', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('revenue_circle', 'Revenue Circle', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('village_town', 'Village/ Town', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('pin_code', 'PIN Code', 'trim|required|xss_clean|strip_tags|integer|exact_length[6]');

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $obj_id = strlen($objId)?$objId:null;
            $this->index($obj_id);
        } else {            
            $appl_ref_no = $this->getID(7); 
            $sessionUser=$this->session->userdata();
            
            if($this->slug === "CSC"){
                $apply_by = $sessionUser['userId'];
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            }//End of if else

            while(1){
                $app_id = rand(100000000, 999999999);
                $filter = array( 
                    "service_data.appl_id" => $app_id,
                    //"service_data.service_id" => $this->serviceId
                );
                $rows = $this->registration_model->get_row($filter);
                
                if($rows == false)
                    break;
            }

            // var_dump($rows);
            // exit();

            $service_data = [
                "department_id" => "1421",
                "department_name" => "Department of Health and Family Welfare",
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => "Department of Health and Family Welfare", // office name
                "submission_date" => "",
                "service_timeline" => "15 Days",
                "appl_status" => "DRAFT",
                "district" => explode("/", $this->input->post("district"))[0],
            ];
           
            $form_data = [     
                'applicant_name' => $this->input->post("applicant_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'mobile' => $this->input->post("mobile"),
                //'email' => $this->input->post("email"),
                //'other_relation' => $this->input->post("other_relation"),
                //'pan_no' => trim($this->input->post("pan_no")),
                //'aadhar_no' => $this->input->post("aadhar_no"),
                'father_name' => $this->input->post("father_name"),
                'relation_with_deceased' => trim($this->input->post("relation_with_deceased")),

                'name_of_deceased' => $this->input->post("name_of_deceased"),
                'deceased_gender' => $this->input->post("deceased_gender"),
                'deceased_dod' => $this->input->post("deceased_dod"),
                'age_of_deceased' => $this->input->post("age_of_deceased"),
                'place_of_death' => $this->input->post("place_of_death"),
                //'address_of_hospital_home' => $this->input->post("address_of_hospital_home"),
                //'other_place_of_death' => $this->input->post("other_place_of_death"),
                'reason_for_late' => $this->input->post("reason_for_late"),
                'father_name_of_deceased' => $this->input->post("father_name_of_deceased"),
                'mother_name_of_deceased' => $this->input->post("mother_name_of_deceased"),

                'state' => $this->input->post("state"),
                'district' => explode("/", $this->input->post("district"))[0],
                'district_id' => explode("/", $this->input->post("district"))[1],
                'sub_division' => explode("/", $this->input->post("sub_division"))[0],
                'sub_division_id' => explode("/", $this->input->post("sub_division"))[1],
                'revenue_circle' => explode("/", $this->input->post("revenue_circle"))[0],
                'revenue_circle_id' => explode("/", $this->input->post("revenue_circle"))[1],
                
                'village_town' => $this->input->post("village_town"),
                'pin_code' => $this->input->post("pin_code"),      

                'user_id' => $sessionUser['userId']->{'$id'},    
                'user_type' => $this->slug,

                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];

            if(!empty($this->input->post("place_of_death"))){
                if (($this->input->post("place_of_death") == "Hospital") || ($this->input->post("place_of_death") == "House")) {
                    $form_data['address_of_hospital_home'] = $this->input->post("address_of_hospital_home"); 
                } elseif($this->input->post("place_of_death") == "Other"){
                    $form_data['other_place_of_death'] = $this->input->post("other_place_of_death"); 
                }
            }

            if ($this->input->post("relation_with_deceased") == "Other") {
                $form_data["other_relation"] = $this->input->post("other_relation");
            }

            if (!empty($this->input->post("pan_no"))) {
                $form_data["pan_no"] = $this->input->post("pan_no");
            }

            if (!empty($this->input->post("aadhar_no"))) {
                $form_data["aadhar_no"] = $this->input->post("aadhar_no");
            }

            if (!empty($this->input->post("email"))) {
                $form_data["email"] = $this->input->post("email");
            }

            if(strlen($objId)) {
                $form_data["affidavit_type"] = $this->input->post("affidavit_type");
                $form_data["affidavit"] = $this->input->post("affidavit");
                $form_data["doctor_certificate_type"] = $this->input->post("doctor_certificate_type");
                $form_data["doctor_certificate"] = $this->input->post("doctor_certificate");
                $form_data["proof_residence_type"] = $this->input->post("proof_residence_type");
                $form_data["proof_residence"] = $this->input->post("proof_residence");
                
                $form_data["soft_copy_type"] = $this->input->post("soft_copy_type");
                $form_data["soft_copy"] = $this->input->post("soft_copy");

                if (!empty($this->input->post("others_type"))) {
                    $form_data["others_type"] = $this->input->post("others_type");
                    $form_data["others"] = $this->input->post("others");
                }
            }

            $inputs = [
                'service_data'=>$service_data,
                'form_data' => $form_data
            ];
                
            if(strlen($objId)) {

                //pre($inputs);
                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                $this->session->set_flashdata('success','Your application has been successfully submitted');
                redirect('spservices/delayeddeath/registration/fileuploads/'.$objId);
            } else {
                $insert=$this->registration_model->insert($inputs);
                if($insert){
                    $objectId=$insert['_id']->{'$id'};
                    $this->session->set_flashdata('success','Your application has been successfully submitted');
                    redirect('spservices/delayeddeath/registration/fileuploads/'.$objectId);
                    exit();
                } else {
                    $this->session->set_flashdata('fail','Unable to submit data!!! Please try again.');
                    $this->index();
                }//End of if else
            }//End of if else
        }//End of if else
    }//End of submit()

    public function fileuploads($objId=null) {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "pageTitle" => "Attached Enclosures for ".$this->serviceName,
                "obj_id"=>$objId,               
                "dbrow" => $dbRow
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('delayeddeath/delayeddeathuploads_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/delayeddeath/registration');
        }//End of if else
    }//End of fileuploads()

    public function submitfiles() { 
        //pre($this->input->post("others_type"));       
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $this->form_validation->set_rules('doctor_certificate_type', 'Hospital or Doctor\'s Certificate regarding Death ', 'required');
        $this->form_validation->set_rules('proof_residence_type', 'Proof of Resident', 'required');
        $this->form_validation->set_rules('affidavit_type', 'Affidavit', 'required');

        if (empty($this->input->post("proof_residence_old"))) {
            if(((empty($this->input->post("proof_residence_type"))) && (($_FILES['proof_residence']['name'] != "") || (!empty($this->input->post("proof_residence_temp"))))) || ((!empty($this->input->post("proof_residence_type"))) && (($_FILES['proof_residence']['name'] == "") && (empty($this->input->post("proof_residence_temp")))))) {
            
                $this->form_validation->set_rules('proof_residence_type', 'Proof of Resident Type', 'required');
                $this->form_validation->set_rules('proof_residence', 'Proof of Resident Document', 'required');
            }
        }

        if (empty($this->input->post("doctor_certificate_old"))) {
            if(((empty($this->input->post("doctor_certificate_type"))) && (($_FILES['doctor_certificate']['name'] != "") || (!empty($this->input->post("doctor_certificate_temp"))))) || ((!empty($this->input->post("doctor_certificate_type"))) && (($_FILES['doctor_certificate']['name'] == "") && (empty($this->input->post("doctor_certificate_temp")))))) {
            
                $this->form_validation->set_rules('doctor_certificate_type', 'Hospital or Doctor\'s Certificate regarding Death Type', 'required');
                $this->form_validation->set_rules('doctor_certificate', 'Hospital or Doctor\'s Certificate regarding Death Document', 'required');
            }
        }

        if (empty($this->input->post("affidavit_old"))) {
            if(((empty($this->input->post("affidavit_type"))) && (($_FILES['affidavit']['name'] != "") || (!empty($this->input->post("affidavit_temp"))))) || ((!empty($this->input->post("affidavit_type"))) && (($_FILES['affidavit']['name'] == "") && (empty($this->input->post("affidavit_temp")))))) {
            
                $this->form_validation->set_rules('affidavit_type', 'Affidavit Type', 'required');
                $this->form_validation->set_rules('affidavit', 'Affidavit Document', 'required');
            }
        }

        // if (empty($this->input->post("soft_copy_old")) && $this->slug !== 'user') {
        //     if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {
                
        //         $this->form_validation->set_rules('soft_copy_type', 'Soft Copy Type required', 'required');
        //         $this->form_validation->set_rules('soft_copy', 'Soft Copy document required', 'required');
        //     }
        // }

        if (empty($this->input->post("others_old"))) {
            if ((empty($this->input->post("others_type"))) && (($_FILES['others']['name'] != "") || (!empty($this->input->post("others_temp"))))) {
            
                $this->form_validation->set_rules('others_type', 'Others Type', 'required');
            }
    
            if ((!empty($this->input->post("others_type"))) && (($_FILES['others']['name'] == "") && (empty($this->input->post("others_temp"))))) {
                $this->form_validation->set_rules('others', 'Others', 'required');
            }
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if (strlen($this->input->post("doctor_certificate_temp")) > 0) {
            $doctorCertificate = movedigilockerfile($this->input->post('doctor_certificate_temp'));
            $doctor_certificate = $doctorCertificate["upload_status"]?$doctorCertificate["uploaded_path"]:null;
        } else {
            $doctorCertificate = cifileupload("doctor_certificate");
            $doctor_certificate = $doctorCertificate["upload_status"]?$doctorCertificate["uploaded_path"]:null;
        }

        if (strlen($this->input->post("proof_residence_temp")) > 0) {
            $proofResidence = movedigilockerfile($this->input->post('proof_residence_temp'));
            $proof_residence = $proofResidence["upload_status"]?$proofResidence["uploaded_path"]:null;
        } else {
            $proofResidence = cifileupload("proof_residence");
            $proof_residence = $proofResidence["upload_status"]?$proofResidence["uploaded_path"]:null;
        }

        if (strlen($this->input->post("affidavit_temp")) > 0) {
            $affidavit = movedigilockerfile($this->input->post('affidavit_temp'));
            $affidavit = $affidavit["upload_status"]?$affidavit["uploaded_path"]:null;
        } else {
            $affidavit = cifileupload("affidavit");
            $affidavit = $affidavit["upload_status"]?$affidavit["uploaded_path"]:null;
        }

        if (strlen($this->input->post("others_temp")) > 0) {
            $others = movedigilockerfile($this->input->post('others_temp'));
            $others = $others["upload_status"]?$others["uploaded_path"]:null;
        } else {
            $others = cifileupload("others");
            $others = $others["upload_status"]?$others["uploaded_path"]:null;
        }

        $softCopy = cifileupload("soft_copy");
        $soft_copy = $softCopy["upload_status"]?$softCopy["uploaded_path"]:null;
        
        $uploadedFiles = array(
            "doctor_certificate_old" => strlen($doctor_certificate)?$doctor_certificate:$this->input->post("doctor_certificate_old"),
            "proof_residence_old" => strlen($proof_residence)?$proof_residence:$this->input->post("proof_residence_old"),
            "affidavit_old" => strlen($affidavit)?$affidavit:$this->input->post("affidavit_old"),
            "others_old" => strlen($others)?$others:$this->input->post("others_old"),
            "soft_copy_old" => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.doctor_certificate_type' => $this->input->post("doctor_certificate_type"),
                'form_data.proof_residence_type' => $this->input->post("proof_residence_type"),
                'form_data.affidavit_type' => $this->input->post("affidavit_type"),
                //'form_data.others_type' => $this->input->post("others_type"),
                'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),
                'form_data.doctor_certificate' => strlen($doctor_certificate)?$doctor_certificate:$this->input->post("doctor_certificate_old"),
                'form_data.proof_residence' => strlen($proof_residence)?$proof_residence:$this->input->post("proof_residence_old"),
                'form_data.affidavit' => strlen($affidavit)?$affidavit:$this->input->post("affidavit_old"),
                //'form_data.others' => strlen($others)?$others:$this->input->post("others_old"),
                'form_data.soft_copy' => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old")
            );

            if (!empty($this->input->post("others_type"))) {
                $data["form_data.others_type"] = $this->input->post("others_type");
                $data["form_data.others"] = strlen($others)?$others:$this->input->post("others_old");
            }
            
            $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success','Your application has been successfully submitted');
            redirect('spservices/delayeddeath/registration/preview/'.$objId);
        }//End of if else
    }//End of submitfiles()

    public function preview($objId=null) {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('delayeddeath/delayeddeathpreview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/delayeddeath/registration');
        }//End of if else
    }//End of preview()

    public function applicationpreview($objId=null) {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if(count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('delayeddeath/delayeddeathapplication_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/delayeddeath/registration');
        }//End of if else
    }

    public function finalsubmition(){
        $obj=$this->input->post('obj');
        if($obj){
            $dbRow = $this->registration_model->get_by_doc_id($obj);

            if ($dbRow->service_data->appl_status == "submitted") {
                $this->my_transactions();
            }

            //procesing data
            $processing_history = $dbRow->processing_history??array();
            $processing_history[] = array(
                "processed_by" => "Application submitted by ".$dbRow->form_data->applicant_name,
                "action_taken" => "Application Submition",
                "remarks" => "Application submitted by ".$dbRow->form_data->applicant_name,
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            );

            $postdata=array(
                "cscid" => "RTPS1234",
                "cscoffice" => "NA",
                "applicantName" => $dbRow->form_data->applicant_name,
                "applicantMobileNo" => $dbRow->form_data->mobile,
                "applicantGender" => $dbRow->form_data->applicant_gender,
                "relationWithDecease" => $dbRow->form_data->relation_with_deceased,
                //"relationWithDeceasedOther" => $dbRow->form_data->other_relation,
                //"emailId" => $dbRow->form_data->email,
                //"panNo" => $dbRow->form_data->pan_no,
                //"aadharNo" => $dbRow->form_data->aadhar_no,

                "deceasedName" => $dbRow->form_data->name_of_deceased,
                "deceasedFatherName" => $dbRow->form_data->father_name_of_deceased,
                "deceasedMotherName" => $dbRow->form_data->mother_name_of_deceased,
                "dateofDeath" => $dbRow->form_data->deceased_dod,
                "ageofDeceased	" => $dbRow->form_data->age_of_deceased,
                "deceasedGender" => $dbRow->form_data->deceased_gender,
                "placeofDeath" => $dbRow->form_data->place_of_death,
                //"addressHospital" => $dbRow->form_data->address_of_hospital_home,
                //"placeofDeathOther" => $dbRow->form_data->other_place_of_death,
                "reasonForLate" => $dbRow->form_data->reason_for_late,

                "state" => "Assam",
                "district" => $dbRow->form_data->district,
                "subDivision" => $dbRow->form_data->sub_division,
                "circleOffice" => $dbRow->form_data->revenue_circle,
                "deceasedVillageorTown" => $dbRow->form_data->village_town,
                "deceasedPin" => $dbRow->form_data->pin_code,

                "application_ref_no" => $dbRow->service_data->appl_ref_no,
                "service_type" => "PDDR",
                "fillUpLanguage" => "English",

                'spId'=>array('applId'=>$dbRow->service_data->appl_id)
            );

            if(!empty($dbRow->form_data->other_relation))
                $postdata['relationWithDeceasedOther'] = $dbRow->form_data->other_relation;

            if(!empty($dbRow->form_data->place_of_death)){
                if (($dbRow->form_data->place_of_death == "Hospital") || ($dbRow->form_data->place_of_death == "House")) {
                    $postdata['addressHospital'] = $dbRow->form_data->address_of_hospital_home; 
                } elseif($dbRow->form_data->place_of_death == "Other"){
                    $postdata['placeofDeathOther'] = $dbRow->form_data->other_place_of_death; 
                }
            }

            if(!empty($dbRow->form_data->pan_no))
                $postdata['panNo'] = $dbRow->form_data->pan_no;

            if(!empty($dbRow->form_data->aadhar_no))
                $postdata['aadharNo'] = $dbRow->form_data->aadhar_no;

            if(!empty($dbRow->form_data->email))
                $postdata['emailId'] = $dbRow->form_data->email;

            if(!empty($dbRow->form_data->affidavit)){
                $affidavit = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->affidavit));

                $attachment_three = array(
                    "encl" =>  $affidavit,
                    "docType" => "application/pdf",
                    "enclFor" => "Affidavit",
                    "enclType" => $dbRow->form_data->affidavit_type,
                    "id" => "65441673",
                    "doctypecode" => "7503",
                    "docRefId" => "7504",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentThree'] = $attachment_three;
            }

            if(!empty($dbRow->form_data->others)){
                $others = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->others));

                $attachment_four = array(
                    "encl" =>  $others,
                    "docType" => "application/pdf",
                    "enclFor" => "Others",
                    "enclType" => $dbRow->form_data->others_type,
                    "id" => "65441673",
                    "doctypecode" => "7503",
                    "docRefId" => "7504",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentFour'] = $attachment_four;
            }

            if(!empty($dbRow->form_data->doctor_certificate)){
                $doctor_certificate = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->doctor_certificate));

                $attachment_one = array(
                    "encl" =>  $doctor_certificate,
                    "docType" => "application/pdf",
                    "enclFor" => "Hospital or Doctor Certificate regarding Death",
                    "enclType" => $dbRow->form_data->doctor_certificate_type,
                    "id" => "65441673",
                    "doctypecode" => "7503",
                    "docRefId" => "7504",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentOne'] = $attachment_one;
            }

            if(!empty($dbRow->form_data->proof_residence)){
                $proof_residence = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->proof_residence));

                $attachment_two = array(
                    "encl" => $proof_residence,
                    "docType" => "application/pdf",
                    "enclFor" => "Proof of Resident",
                    "enclType" => $dbRow->form_data->proof_residence_type,
                    "id" => "65441673",
                    "doctypecode" => "7503",
                    "docRefId" => "7504",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentTwo'] = $attachment_two;
            }

            // if(!empty($dbRow->form_data->soft_copy)){
            //     $soft_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->soft_copy));

            //     $attachment_zero = array(
            //         "encl" => $soft_copy,
            //         "docType" => "application/pdf",
            //         "enclFor" => "Upload the Soft  Copy of Application Form",
            //         "enclType" => "Upload the Soft  Copy of Application Form",
            //         "id" => "65441673",
            //         "doctypecode" => "7503",
            //         "docRefId" => "7504",
            //         "enclExtn" => "pdf"
            //     );

            //     $postdata['AttachmentZero'] = $attachment_zero;
            // }

            // $json = json_encode($postdata);
            // $buffer = preg_replace( "/\r|\n/", "", $json );
            // $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
            // fwrite($myfile, $buffer);
            // fclose($myfile);
            // die;

            $url=$this->config->item('delayed_death_url');
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
           
            curl_close($curl);

            log_response($dbRow->service_data->appl_ref_no, $response);

            // var_dump($response);
            // exit();
            
            if($response){
                $response = json_decode($response);
           
                if($response->ref->status === "success"){
                    $data_to_update=array(
                        'form_data.edistrict_ref_no' => $response->ref->edistrict_ref_no,
                        'service_data.appl_status'=>'submitted',
                        'service_data.submission_date'=>new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        'processing_history'=>$processing_history
                    );
                    $this->registration_model->update($obj,$data_to_update);

                    $nowTime = date('Y-m-d H:i:s');
                    $sms = array(
                        "mobile" => (int)$dbRow->form_data->mobile,
                        "applicant_name" => $dbRow->form_data->applicant_name,
                        "service_name" => 'PermissionFor DelayedDeath Reg',
                        "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                        "app_ref_no" => $dbRow->service_data->appl_ref_no
                    );
                    sms_provider("submission", $sms);

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

    public function track($objId=null) {
        $dbRow = $this->registration_model->get_by_doc_id($objId);
        if(isset($dbRow->form_data->edistrict_ref_no ) && !empty($dbRow->form_data->edistrict_ref_no )){
            $this->load->helper('trackstatus');
            fetchEdistrictData($dbRow->form_data->edistrict_ref_no);
            $dbRow = $this->registration_model->get_by_doc_id($objId);
        }
        if(count((array)$dbRow)) {
            $data=array(
                "service_data.service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('delayeddeath/delayeddeathcertificatetrack_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/delayeddeath/');
        }//End of if else
    }//End of track()

    public function queryform($objId=null) {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->registration_model->get_row(array("_id"=> new ObjectId($objId), "service_data.appl_status"=>"QS"));
            if($dbRow) {
                $data=array(
                    "service_data.service_name"=>$this->serviceName,
                    "dbrow"=>$dbRow
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('delayeddeath/delayeddeathquery_view',$data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found in query mode against object id : '.$objId);
                redirect('spservices/delayeddeath/registration');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/delayeddeath/registration');
        }//End of if else
    }//End of query()

    public function querysubmit() {        
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");

        $this->form_validation->set_rules('doctor_certificate_type', 'Hospital or Doctor\'s Certificate regarding Death ', 'required');
        $this->form_validation->set_rules('proof_residence_type', 'Proof of Resident', 'required');
        $this->form_validation->set_rules('affidavit_type', 'Affidavit', 'required');

        if (empty($this->input->post("others_old"))) {
            if ((empty($this->input->post("others_type"))) && (($_FILES['others']['name'] != ""))) {
                $this->form_validation->set_rules('others_type', 'Others Type', 'required');
            }
    
            if ((!empty($this->input->post("others_type"))) && (($_FILES['others']['name'] == ""))) {
                $this->form_validation->set_rules('others', 'Others', 'required');
            }
        }

        $this->form_validation->set_rules('appl_ref_no', 'Application Ref No.', 'trim|xss_clean|strip_tags|max_length[255]');
        
        // $this->form_validation->set_rules('applicant_name', 'Applicant name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        // $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|integer|exact_length[10]|xss_clean|strip_tags');
        // $this->form_validation->set_rules('email', 'Email', 'trim|xss_clean|strip_tags|valid_email');
        // $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('pan_no', 'Pan No', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('aadhar_no', 'Aadhar No.', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('father_name', 'Father Name', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('mother_name', 'Mother Name', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('spouse_name', 'Spouse Name', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('sub_division', 'Sub-Division', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('revenue_circle', 'Revenue Circle', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('mouza', 'Mouza', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('post_office', 'Post Office', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('village_town', 'Village/ Town', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('pin_code', 'Pin Code', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('police_station', 'Police Station', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('house_no', 'House No', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('landline_number', 'Landline Number', 'trim|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('name_of_deceased', 'Name Of Deceased', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('deceased_gender', 'Deceased Gender', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('deceased_dob', 'Date of Birth of the deceased', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('deceased_dod', 'Date of Death of the deceased', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('reason_of_death', 'Reason Of Death', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('place_of_death', 'Place Of Death', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('other_place_of_death', 'Other place of death', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('guardian_name_of_deceased', 'Guardian name of the deceased', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('father_name_of_deceased', 'Father name of the deceased', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('mother_name_of_deceased', 'Mother name of deceased', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('spouse_name_of_deceased', 'Spouse name of deceased', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('relationship_with_deceased', 'Relationship with deceased', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('deceased_district', 'District of the deceased', 'trim|required|xss_clean|strip_tags'); 
        // $this->form_validation->set_rules('deceased_sub_division', 'Sub-Division of the deceased', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('deceased_revenue_circle', 'Revenue Circle of the deceased', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('deceased_mouza', 'Mouza of the deceased', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('deceased_post_office', 'Post Office of the deceased', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('deceased_village', 'Village of the deceased', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('deceased_town', 'Town of the deceased', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('deceased_pin_code', 'Pin Code of the deceased', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('deceased_police_station', 'Police Station of the deceased', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('deceased_house_no', 'House No of the deceased', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        $affidavit = cifileupload("affidavit");
        $affidavit = $affidavit["upload_status"]?$affidavit["uploaded_path"]:$this->input->post("affidavit_old");

        $others = cifileupload("others");
        $others = $others["upload_status"]?$others["uploaded_path"]:$this->input->post("others_old");

        $doctorCertificate = cifileupload("doctor_certificate");
        $doctor_certificate = $doctorCertificate["upload_status"]?$doctorCertificate["uploaded_path"]:$this->input->post("doctor_certificate_old");

        $proofResidence = cifileupload("proof_residence");
        $proof_residence = $proofResidence["upload_status"]?$proofResidence["uploaded_path"]:$this->input->post("proof_residence_old");
        
        $softCopy = cifileupload("soft_copy");
        $soft_copy = $softCopy["upload_status"]?$softCopy["uploaded_path"]:null;
        
        $uploadedFiles = array(
            "affidavit_old" => strlen($affidavit)?$affidavit:$this->input->post("affidavit_old"),
            "others_old" => strlen($others)?$others:$this->input->post("others_old"),
            "doctor_certificate_old" => strlen($doctor_certificate)?$doctor_certificate:$this->input->post("doctor_certificate_old"),
            "proof_residence_old" => strlen($proof_residence)?$proof_residence:$this->input->post("proof_residence_old"),
            "soft_copy_old" => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $this->queryform($objId);
        } else {            
            // $name_of_kins =  $this->input->post("name_of_kins");
            // $relations =  $this->input->post("relations");                        
            // $age_y_on_the_date_of_applications =  $this->input->post("age_y_on_the_date_of_applications");
            // $age_m_on_the_date_of_applications =  $this->input->post("age_m_on_the_date_of_applications");
            // $kin_alive_deads =  $this->input->post("kin_alive_deads");
            // $family_details = array();
            // if(count($name_of_kins)) {
            //     foreach($name_of_kins as $k=>$name_of_kin) {
            //         $family_detail = array(
            //             "name_of_kin" => $name_of_kin,
            //             "relation" => $relations[$k],
            //             "age_y_on_the_date_of_application" => $age_y_on_the_date_of_applications[$k],
            //             "age_m_on_the_date_of_application" => $age_m_on_the_date_of_applications[$k],
            //             "kin_alive_dead" => $kin_alive_deads[$k]
            //         );
            //         $family_details[] = $family_detail;
            //     }//End of foreach()        
            // }//End of if

            $dbRow = $this->registration_model->get_by_doc_id($objId);
          //  pre($dbRow);
              //  exit();
            if(count((array)$dbRow)) {
                
                //$backupRow = (array)$dbRow;
                //unset($backupRow["_id"]);

                $data = array(
                    'form_data.affidavit_type' => $this->input->post("affidavit_type"),
                    //'form_data.others_type' => $this->input->post("others_type"),
                    'form_data.doctor_certificate_type' => $this->input->post("doctor_certificate_type"),
                    'form_data.proof_residence_type' => $this->input->post("proof_residence_type"),
                    'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),
                    'form_data.affidavit' => strlen($affidavit)?$affidavit:$this->input->post("affidavit_old"),
                    //'form_data.others' => strlen($others)?$others:$this->input->post("others_old"),
                    'form_data.doctor_certificate' => strlen($doctor_certificate)?$doctor_certificate:$this->input->post("doctor_certificate_old"),
                    'form_data.proof_residence' => strlen($proof_residence)?$proof_residence:$this->input->post("proof_residence_old"), 
                    'form_data.soft_copy' => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old"),               
                    'form_data.updated_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                );

                if (!empty($this->input->post("others_type"))) {
                    $data["form_data.others_type"] = $this->input->post("others_type");
                    $data["form_data.others"] = strlen($others)?$others:$this->input->post("others_old");
                }

                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);
        
                $postdata=array(
                    "cscid" => "RTPS1234",
                    "cscoffice" => "NA",
                    "revert" => "NA",

                    "applicantName" => $dbRow->form_data->applicant_name,
                    "applicantMobileNo" => $dbRow->form_data->mobile,
                    "applicantGender" => $dbRow->form_data->applicant_gender,
                    "relationWithDecease" => $dbRow->form_data->relation_with_deceased,
                    //"relationWithDeceasedOther" => $dbRow->form_data->other_relation,
                    //"emailId" => $dbRow->form_data->email,
                    //"panNo" => $dbRow->form_data->pan_no,
                    //"aadharNo" => $dbRow->form_data->aadhar_no,
    
                    "deceasedName" => $dbRow->form_data->name_of_deceased,
                    "deceasedFatherName" => $dbRow->form_data->father_name_of_deceased,
                    "deceasedMotherName" => $dbRow->form_data->mother_name_of_deceased,
                    "dateofDeath" => $dbRow->form_data->deceased_dod,
                    "ageofDeceased	" => $dbRow->form_data->age_of_deceased,
                    "deceasedGender" => $dbRow->form_data->deceased_gender,
                    "placeofDeath" => $dbRow->form_data->place_of_death,
                    //"addressHospital" => $dbRow->form_data->address_of_hospital_home,
                    //"placeofDeathOther" => $dbRow->form_data->other_place_of_death,
                    "reasonForLate" => $dbRow->form_data->reason_for_late,
    
                    "state" => "Assam",
                    "district" => $dbRow->form_data->district,
                    "subDivision" => $dbRow->form_data->sub_division,
                    "circleOffice" => $dbRow->form_data->revenue_circle,
                    "deceasedVillageorTown" => $dbRow->form_data->village_town,
                    "deceasedPin" => $dbRow->form_data->pin_code,
    
                    "application_ref_no" => $dbRow->service_data->appl_ref_no,
                    "service_type" => "PDDR",
                    "fillUpLanguage" => "English",
    
                    'spId'=>array('applId'=>$dbRow->service_data->appl_id)
                );

                if(!empty($dbRow->form_data->other_relation))
                    $postdata['relationWithDeceasedOther'] = $dbRow->form_data->other_relation;

                if(!empty($dbRow->form_data->place_of_death)){
                    if (($dbRow->form_data->place_of_death == "Hospital") || ($dbRow->form_data->place_of_death == "House")) {
                        $postdata['addressHospital'] = $dbRow->form_data->address_of_hospital_home; 
                    } elseif($dbRow->form_data->place_of_death == "Other"){
                        $postdata['placeofDeathOther'] = $dbRow->form_data->other_place_of_death; 
                    }
                }
        
                if(!empty($dbRow->form_data->pan_no))
                    $postdata['panNo'] = $dbRow->form_data->pan_no;
        
                if(!empty($dbRow->form_data->aadhar_no))
                    $postdata['aadharNo'] = $dbRow->form_data->aadhar_no;
        
                if(!empty($dbRow->form_data->email))
                    $postdata['emailId'] = $dbRow->form_data->email;
                    
        
                if(strlen($affidavit)){
                    $affidavit_type = (!empty($this->input->post("affidavit_type")))?$this->input->post("affidavit_type"):$dbRow->form_data->affidavit_type;
                    $affidavit = strlen($affidavit)?base64_encode(file_get_contents(FCPATH.$affidavit)):null;

        
                    $attachment_three = array(
                        "encl" =>  $affidavit,
                        "docType" => "application/pdf",
                        "enclFor" => "Affidavit",
                        "enclType" => $affidavit_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
        
                    $postdata['AttachmentThree'] = $attachment_three;
                }

                if(strlen($others)){
                    $others_type = (!empty($this->input->post("others_type")))?$this->input->post("others_type"):$dbRow->form_data->others_type;
                    $others = strlen($others)?base64_encode(file_get_contents(FCPATH.$others)):null;

        
                    $attachment_four = array(
                        "encl" =>  $others,
                        "docType" => "application/pdf",
                        "enclFor" => "Others",
                        "enclType" => $others_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
        
                    $postdata['AttachmentFour'] = $attachment_four;
                }
        
                if(!empty($doctor_certificate)){
                    $doctor_certificate_type = (!empty($this->input->post("doctor_certificate_type")))?$this->input->post("doctor_certificate_type"):$dbRow->form_data->doctor_certificate_type;
                    $doctor_certificate = strlen($doctor_certificate)?base64_encode(file_get_contents(FCPATH.$doctor_certificate)):null;
    
                    $attachment_one = array(
                        "encl" =>  $doctor_certificate,
                        "docType" => "application/pdf",
                        "enclFor" => "Hospital or Doctor Certificate regarding Death",
                        "enclType" => $dbRow->form_data->doctor_certificate_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
    
                    $postdata['AttachmentOne'] = $attachment_one;
                }
        
                if(!empty($proof_residence)){
                    $proof_residence_type = (!empty($this->input->post("proof_residence_type")))?$this->input->post("proof_residence_type"):$dbRow->form_data->proof_residence_type;
                    $proof_residence = strlen($proof_residence)?base64_encode(file_get_contents(FCPATH.$proof_residence)):null;
    
                    $attachment_two = array(
                        "encl" => $proof_residence,
                        "docType" => "application/pdf",
                        "enclFor" => "Proof of Resident",
                        "enclType" => $dbRow->form_data->proof_residence_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
    
                    $postdata['AttachmentTwo'] = $attachment_two;
                }
        
                // if(strlen($soft_copy)){
                //     $softCopy = strlen($soft_copy)?base64_encode(file_get_contents(FCPATH.$soft_copy)):null;
        
                //     $attachment_zero = array(
                //         "encl" => $softCopy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload the Soft  Copy of Application Form",
                //         "enclType" => "Upload the Soft  Copy of Application Form",
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );
        
                //     $postdata['AttachmentZero'] = $attachment_zero;
                // }

                // $json = json_encode($postdata);
                // $buffer = preg_replace( "/\r|\n/", "", $json );
                // $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $buffer);
                // fclose($myfile);
                // die;
        
                $json_obj = json_encode($postdata);
                
                $url=$this->config->item('delayed_death_url');
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

                // var_dump($response);
                // exit();

                if(isset($error_msg)) {
                    die("CURL ERROR : ".$error_msg);
                } elseif ($response) {
                    $response = json_decode($response, true);  //pre($response);
                    if ($response["ref"]["status"] === "success") {

                        $processing_history = $dbRow->processing_history??array();
                        $processing_history[] = array(
                            "processed_by" => "Query submitted by ".$dbRow->form_data->applicant_name,
                            "action_taken" => "Query submitted",
                            "remarks" => "Query submitted by ".$dbRow->form_data->applicant_name,
                            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        );

                        $data = array(
                            "service_data.appl_status" => "QA",
                            'processing_history' => $processing_history,
                        );
        
                        $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

                        $this->session->set_flashdata('success','Your application has been successfully updated');
                        redirect('spservices/delayeddeath/registration/preview/'.$objId);
                    } else {
                        $this->session->set_flashdata('fail','Unable to update data!!! Please try again.');
                        $this->queryform($objId);
                        // return $this->output
                        //     ->set_content_type('application/json')
                        //     ->set_status_header(401)
                        //     ->set_output(json_encode(array("status" => false)));
                    }//End of if else
                }//End of if
            } else {
                $this->session->set_flashdata('fail','Unable to update data!!! Please try again.');
                $this->index();
            }//End of if else
        }//End of if else      
    }//End of querysubmit()

    function getID($length) { 
        $rtps_trans_id = $this->generateID($length);
        while ($this->registration_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
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
        $str = "RTPS-PDDR/" . $date."/" .$number;
        return $str;
    }//End of generateID()    

    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
           return false;
        }//End of if else
    }//End of checkObjectId()

    public function submit_to_backend($obj,$show_ack=false){
        if($obj){
            $dbRow = $this->registration_model->get_by_doc_id($obj);

            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }

            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")){

                //procesing data
                $processing_history = $dbRow->processing_history??array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by ".$dbRow->form_data->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by ".$dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
                
    
                $postdata=array(
                    "cscid" => "RTPS1234",
                    "cscoffice" => "NA",
                    "applicantName" => $dbRow->form_data->applicant_name,
                    "applicantMobileNo" => $dbRow->form_data->mobile,
                    "applicantGender" => $dbRow->form_data->applicant_gender,
                    "relationWithDecease" => $dbRow->form_data->relation_with_deceased,
                    //"relationWithDeceasedOther" => $dbRow->form_data->other_relation,
                    //"emailId" => $dbRow->form_data->email,
                    //"panNo" => $dbRow->form_data->pan_no,
                    //"aadharNo" => $dbRow->form_data->aadhar_no,

                    "deceasedName" => $dbRow->form_data->name_of_deceased,
                    "deceasedFatherName" => $dbRow->form_data->father_name_of_deceased,
                    "deceasedMotherName" => $dbRow->form_data->mother_name_of_deceased,
                    "dateofDeath" => $dbRow->form_data->deceased_dod,
                    "ageofDeceased	" => $dbRow->form_data->age_of_deceased,
                    "deceasedGender" => $dbRow->form_data->deceased_gender,
                    "placeofDeath" => $dbRow->form_data->place_of_death,
                    //"addressHospital" => $dbRow->form_data->address_of_hospital_home,
                    //"placeofDeathOther" => $dbRow->form_data->other_place_of_death,
                    "reasonForLate" => $dbRow->form_data->reason_for_late,

                    "state" => "Assam",
                    "district" => $dbRow->form_data->district,
                    "subDivision" => $dbRow->form_data->sub_division,
                    "circleOffice" => $dbRow->form_data->revenue_circle,
                    "deceasedVillageorTown" => $dbRow->form_data->village_town,
                    "deceasedPin" => $dbRow->form_data->pin_code,

                    "application_ref_no" => $dbRow->service_data->appl_ref_no,
                    "service_type" => "PDDR",
                    "fillUpLanguage" => "English",

                    'spId'=>array('applId'=>$dbRow->service_data->appl_id)
                );

                if(!empty($dbRow->form_data->other_relation))
                    $postdata['relationWithDeceasedOther'] = $dbRow->form_data->other_relation;

                if (!empty($this->input->post("pan_no"))) {
                    $postdata['panNo'] = $this->input->post("pan_no");
                }
        
                if (!empty($this->input->post("aadhar_no"))) {
                    $postdata['aadharNo'] = $this->input->post("aadhar_no");
                }

                if(!empty($dbRow->form_data->email))
                    $postdata['emailId'] = $dbRow->form_data->email;

                if(!empty($dbRow->form_data->place_of_death)){
                    if (($dbRow->form_data->place_of_death == "Hospital") || ($dbRow->form_data->place_of_death == "House")) {
                        $postdata['addressHospital'] = $dbRow->form_data->address_of_hospital_home; 
                    } elseif($dbRow->form_data->place_of_death == "Other"){
                        $postdata['placeofDeathOther'] = $dbRow->form_data->other_place_of_death; 
                    }
                }
    
                if(!empty($dbRow->form_data->affidavit)){
                    $affidavit = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->affidavit));

                    $attachment_three = array(
                        "encl" =>  $affidavit,
                        "docType" => "application/pdf",
                        "enclFor" => "Affidavit",
                        "enclType" => $dbRow->form_data->affidavit_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentThree'] = $attachment_three;
                }

                if(!empty($dbRow->form_data->others)){
                    $others = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->others));

                    $attachment_four = array(
                        "encl" =>  $others,
                        "docType" => "application/pdf",
                        "enclFor" => "Others",
                        "enclType" => $dbRow->form_data->others_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentFour'] = $attachment_four;
                }

                if(!empty($dbRow->form_data->doctor_certificate)){
                    $doctor_certificate = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->doctor_certificate));

                    $attachment_one = array(
                        "encl" =>  $doctor_certificate,
                        "docType" => "application/pdf",
                        "enclFor" => "Hospital or Doctor Certificate regarding Death",
                        "enclType" => $dbRow->form_data->doctor_certificate_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentOne'] = $attachment_one;
                }

                if(!empty($dbRow->form_data->proof_residence)){
                    $proof_residence = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->proof_residence));

                    $attachment_two = array(
                        "encl" => $proof_residence,
                        "docType" => "application/pdf",
                        "enclFor" => "Proof of Resident",
                        "enclType" => $dbRow->form_data->proof_residence_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentTwo'] = $attachment_two;
                }

                // if(!empty($dbRow->form_data->soft_copy)){
                //     $soft_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->soft_copy));

                //     $attachment_zero = array(
                //         "encl" => $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload the Soft  Copy of Application Form",
                //         "enclType" => "Upload the Soft  Copy of Application Form",
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );

                //     $postdata['AttachmentZero'] = $attachment_zero;
                // }

                //  $json = json_encode($postdata);
                // $buffer = preg_replace( "/\r|\n/", "", $json );
                // $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $buffer);
                // fclose($myfile);
                //  die;
    
                $url=$this->config->item('delayed_death_url');
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                
                curl_close($curl);
    
                log_response($dbRow->service_data->appl_ref_no, $response);

                if($response){
                    $response = json_decode($response);
                    
                    //pre($response);
                    if($response->ref->status === "success"){
                        $data_to_update=array(
                            'form_data.edistrict_ref_no' => $response->ref->edistrict_ref_no,
                            'service_data.appl_status'=>'submitted',
                            'service_data.submission_date'=>new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            'processing_history'=>$processing_history
                        );
                        $this->registration_model->update($obj,$data_to_update);

                        //Sending submission SMS
                        $nowTime = date('Y-m-d H:i:s');
                        $sms = array(
                            "mobile" => (int)$dbRow->form_data->mobile,
                            "applicant_name" => $dbRow->form_data->applicant_name,
                            "service_name" => 'PermissionFor DelayedDeath Reg',
                            "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                            "app_ref_no" => $dbRow->service_data->appl_ref_no
                        );
                        sms_provider("submission", $sms);
                        redirect('spservices/applications/acknowledgement/' . $obj);

                        // return $this->output
                        // ->set_content_type('application/json')
                        // ->set_status_header(200)
                        // ->set_output(json_encode(array("status"=>true)));
                    }else{
                        $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>'.$dbRow->service_data->appl_ref_no.'</b>, Please try again.');
                        $this->my_transactions();

                        // return $this->output
                        // ->set_content_type('application/json')
                        // ->set_status_header(401)
                        // ->set_output(json_encode(array("status"=>false)));
                    }
                }
            } else {
                $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                $this->my_transactions();
            }
        }

        redirect('iservices/transactions');
    }

    private function my_transactions(){
        $user=$this->session->userdata();
        if(isset($user['role']) && !empty($user['role'])){
          redirect(base_url('iservices/admin/my-transactions'));
        }else{
          redirect(base_url('iservices/transactions'));
        }
    }

    public function download_certificate($objId = null)
    {
        if (!empty($objId)) {
            $dbRow = $this->registration_model->get_by_doc_id($objId);
            // var_dump($dbRow); die;
            if (count((array)$dbRow) && isset($dbRow->form_data->certificate)) {
                if (file_exists($dbRow->form_data->certificate)) {
                    cifiledownload($dbRow->form_data->certificate);
                }
            } else {
                $this->session->set_flashdata('errmessage', 'Something went wrong!, Please try later.');
                $this->my_transactions();
            }
        }
    }//End of download_certificate()
}//End of Castecertificate
