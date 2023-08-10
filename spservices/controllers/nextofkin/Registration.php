<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps {
    
    private $serviceName = "Issuance of Next of Kin Certificate";
    Private $serviceId = "NOKIN";
 
    public function __construct() {
        parent::__construct();
        $this->load->model('nextofkin/registration_model');
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

        $data=array("pageTitle" => "Issuance of Next of Kin Certificate");
        $filter = array(
            "_id" =>new ObjectId($obj_id), 
            "service_data.appl_status" => "DRAFT"
        );
        $data["dbrow"] = $this->registration_model->get_row($filter);
        $data['usser_type']=$this->slug;
       
        $this->load->view('includes/frontend/header');
        $this->load->view('nextofkin/nextofkin',$data);
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
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|xss_clean|strip_tags|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]|max_length[10]');
        $this->form_validation->set_rules('aadhar_no', 'Aadhar No.', 'trim|xss_clean|strip_tags|integer|exact_length[12]');
        $this->form_validation->set_rules('father_name', 'Father Name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mother_name', 'Mother Name', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('spouse_name', 'Spouse Name', 'trim|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('sub_division', 'Sub-Division', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('revenue_circle', 'Revenue Circle', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('mouza', 'Mouza', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('post_office', 'Post Office', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('village_town', 'Village/ Town', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('pin_code', 'PIN Code', 'trim|required|xss_clean|strip_tags|integer|exact_length[6]');
        $this->form_validation->set_rules('police_station', 'Police Station', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('house_no', 'House No', 'trim|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('landline_number', 'Landline Number', 'trim|xss_clean|strip_tags|integer'); 
        $this->form_validation->set_rules('name_of_deceased', 'Name Of Deceased', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('deceased_gender', 'Deceased Gender', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('deceased_dob', 'Date of Birth of the deceased', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('deceased_dod', 'Date of Death of the deceased', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('reason_of_death', 'Reason Of Death', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('place_of_death', 'Place Of Death', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('other_place_of_death', 'Other place of death', 'trim|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('guardian_name_of_deceased', 'Guardian name of the deceased', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('father_name_of_deceased', 'Father name of the deceased', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('mother_name_of_deceased', 'Mother name of deceased', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('spouse_name_of_deceased', 'Spouse name of deceased', 'trim|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('relationship_with_deceased', 'Relationship with deceased', 'trim|required|xss_clean|strip_tags'); 

        if((!empty($this->input->post("relationship_with_deceased"))) && ($this->input->post("relationship_with_deceased") == "Other"))
            $this->form_validation->set_rules('other_relation', 'Other Relation (If Any)', 'trim|required|xss_clean|strip_tags'); 

        $this->form_validation->set_rules('deceased_district', 'District of the deceased', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('deceased_sub_division', 'Sub-Division of the deceased', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('deceased_revenue_circle', 'Revenue Circle of the deceased', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('deceased_mouza', 'Mouza of the deceased', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('deceased_post_office', 'Post Office of the deceased', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('deceased_village', 'Village of the deceased', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('deceased_town', 'Town of the deceased', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('deceased_pin_code', 'Pin Code of the deceased', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('deceased_police_station', 'Police Station of the deceased', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('deceased_house_no', 'House No of the deceased', 'trim|xss_clean|strip_tags');

        $this->form_validation->set_rules('name_of_kins[]', 'Name of Kins', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('relations[]', 'Relation', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('age_y_on_the_date_of_applications[]', 'ge(Y) on the Date of Application', 'trim|required|xss_clean|strip_tags|integer');
        $this->form_validation->set_rules('age_m_on_the_date_of_applications[]', 'Age(M) on the Date of Application', 'trim|required|xss_clean|strip_tags|integer');
        $this->form_validation->set_rules('kin_alive_deads[]', 'Kin Alive or Dead', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $obj_id = strlen($objId)?$objId:null;
            $this->index($obj_id);
        } else {            
            $appl_ref_no = $this->getID(7); 
            $sessionUser=$this->session->userdata();
            
            $name_of_kins =  $this->input->post("name_of_kins");
            $relations =  $this->input->post("relations");                        
            $age_y_on_the_date_of_applications =  $this->input->post("age_y_on_the_date_of_applications");
            $age_m_on_the_date_of_applications =  $this->input->post("age_m_on_the_date_of_applications");
            $kin_alive_deads =  $this->input->post("kin_alive_deads");
            $family_details = array();
            if(count($name_of_kins)) {
                foreach($name_of_kins as $k=>$name_of_kin) {

                    if ($kin_alive_deads[$k] == "Expired") {
                        $age_y_on_the_date_of_applicationsVal = 0;
                        $age_m_on_the_date_of_applicationsVal = 0;
                    } else {
                        $age_y_on_the_date_of_applicationsVal = $age_y_on_the_date_of_applications[$k];
                        $age_m_on_the_date_of_applicationsVal = $age_m_on_the_date_of_applications[$k];
                    }

                    $family_detail = array(
                        "name_of_kin" => $name_of_kin,
                        "relation" => $relations[$k],
                        "age_y_on_the_date_of_application" => $age_y_on_the_date_of_applicationsVal,
                        "age_m_on_the_date_of_application" => $age_m_on_the_date_of_applicationsVal,
                        "kin_alive_dead" => $kin_alive_deads[$k]
                    );
                    $family_details[] = $family_detail;
                }//End of foreach()        
            }//End of if
            
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
                "department_id" => "1469",
                "department_name" => "General Administration Department",
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => "General Administration Department (STATE)", // office name
                "submission_date" => "",
                "service_timeline" => "15 Days",
                "appl_status" => "DRAFT",
                "district" => explode("/", $this->input->post("deceased_district"))[0],
            ];
           
            $form_data = [     
                'applicant_name' => $this->input->post("applicant_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'mobile' => $this->input->post("mobile"),
                'email' => $this->input->post("email"),
                'dob' => $this->input->post("dob"),
                'pan_no' => trim($this->input->post("pan_no")),
                'aadhar_no' => $this->input->post("aadhar_no"),
                'father_name' => $this->input->post("father_name"),
                'mother_name' => $this->input->post("mother_name"),
                'spouse_name' => trim($this->input->post("spouse_name")),
                'state' => $this->input->post("state"),
                'district' => explode("/", $this->input->post("district"))[0],
                'district_id' => explode("/", $this->input->post("district"))[1],
                'sub_division' => explode("/", $this->input->post("sub_division"))[0],
                'sub_division_id' => explode("/", $this->input->post("sub_division"))[1],
                'revenue_circle' => explode("/", $this->input->post("revenue_circle"))[0],
                'revenue_circle_id' => explode("/", $this->input->post("revenue_circle"))[1],
                
                'mouza' => $this->input->post("mouza"),
                'post_office' => $this->input->post("post_office"),
                'village_town' => $this->input->post("village_town"),
                'pin_code' => $this->input->post("pin_code"),      
                'police_station' => $this->input->post("police_station"),
                'house_no' => $this->input->post("house_no"),
                'landline_number' => $this->input->post("landline_number"),
                'name_of_deceased' => $this->input->post("name_of_deceased"),
                'deceased_gender' => $this->input->post("deceased_gender"),
                'deceased_dob' => $this->input->post("deceased_dob"),
                'deceased_dod' => $this->input->post("deceased_dod"),
                'reason_of_death' => $this->input->post("reason_of_death"),
                'place_of_death' => $this->input->post("place_of_death"),
                'other_place_of_death' => $this->input->post("other_place_of_death"),
                'guardian_name_of_deceased' => $this->input->post("guardian_name_of_deceased"),
                'father_name_of_deceased' => $this->input->post("father_name_of_deceased"),
                'mother_name_of_deceased' => $this->input->post("mother_name_of_deceased"),
                'spouse_name_of_deceased' => $this->input->post("spouse_name_of_deceased"),
                'relationship_with_deceased' => $this->input->post("relationship_with_deceased"),
                'deceased_district' => explode("/", $this->input->post("deceased_district"))[0],
                'deceased_district_id' => explode("/", $this->input->post("deceased_district"))[1],
                'deceased_sub_division' => explode("/", $this->input->post("deceased_sub_division"))[0],
                'deceased_sub_division_id' => explode("/", $this->input->post("deceased_sub_division"))[1],
                'deceased_revenue_circle' => explode("/", $this->input->post("deceased_revenue_circle"))[0],
                'deceased_revenue_circle_id' => explode("/", $this->input->post("deceased_revenue_circle"))[1],
                'deceased_mouza' => $this->input->post("deceased_mouza"),
                'deceased_post_office' => $this->input->post("deceased_post_office"),
                'deceased_village' => $this->input->post("deceased_village"),
                'deceased_town' => $this->input->post("deceased_town"),
                'deceased_pin_code' => $this->input->post("deceased_pin_code"),
                'deceased_police_station' => $this->input->post("deceased_police_station"),
                'deceased_house_no' => $this->input->post("deceased_house_no"),
                'family_details' => $family_details,

                'user_id' => $sessionUser['userId']->{'$id'},    
                'user_type' => $this->slug,

                //'affidavit_type' => $this->input->post("affidavit_type"),
                //'affidavit' => $this->input->post("affidavit"),

                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];

            if((!empty($this->input->post("relationship_with_deceased"))) && ($this->input->post("relationship_with_deceased") == "Other"))
                $form_data["other_relation"] = $this->input->post("other_relation"); 
            else
                $form_data["other_relation"] = ""; 

            if(strlen($objId)) {
                $form_data["affidavit_type"] = $this->input->post("affidavit_type");
                $form_data["affidavit"] = $this->input->post("affidavit");
                $form_data["death_proof_type"] = $this->input->post("death_proof_type");
                $form_data["death_proof"] = $this->input->post("death_proof");
                $form_data["doc_for_relationship_type"] = $this->input->post("doc_for_relationship_type");
                $form_data["doc_for_relationship"] = $this->input->post("doc_for_relationship");
                
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
                redirect('spservices/nextofkin/registration/fileuploads/'.$objId);
            } else {
                $insert=$this->registration_model->insert($inputs);
                if($insert){
                    $objectId=$insert['_id']->{'$id'};
                    $this->session->set_flashdata('success','Your application has been successfully submitted');
                    redirect('spservices/nextofkin/registration/fileuploads/'.$objectId);
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
            $this->load->view('nextofkin/nextofkinuploads_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/nextofkin/registration');
        }//End of if else
    }//End of fileuploads()

    public function submitfiles() { 
        //pre($this->input->post("others_type"));       
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }

        $this->form_validation->set_rules('death_proof_type', 'Death Proof', 'required');
        $this->form_validation->set_rules('doc_for_relationship_type', 'Document for relationship proof', 'required');
        $this->form_validation->set_rules('affidavit_type', 'Affidavit', 'required');

        if (empty($this->input->post("death_proof_old"))) {
            if(((empty($this->input->post("death_proof_type"))) && (($_FILES['death_proof']['name'] != "") || (!empty($this->input->post("death_proof_temp"))))) || ((!empty($this->input->post("death_proof_type"))) && (($_FILES['death_proof']['name'] == "") && (empty($this->input->post("death_proof_temp")))))) {
            
                $this->form_validation->set_rules('death_proof_type', 'Death Proof Type', 'required');
                $this->form_validation->set_rules('death_proof', 'Death Proof Document', 'required');
            }
        }

        if (empty($this->input->post("doc_for_relationship_old"))) {
            if(((empty($this->input->post("doc_for_relationship_type"))) && (($_FILES['doc_for_relationship']['name'] != "") || (!empty($this->input->post("doc_for_relationship_temp"))))) || ((!empty($this->input->post("doc_for_relationship_type"))) && (($_FILES['doc_for_relationship']['name'] == "") && (empty($this->input->post("doc_for_relationship_temp")))))) {
            
                $this->form_validation->set_rules('doc_for_relationship_type', 'Document for relationship proof Type', 'required');
                $this->form_validation->set_rules('doc_for_relationship', 'Document for relationship proof Document', 'required');
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

        if (strlen($this->input->post("death_proof_temp")) > 0) {
            $deathProof = movedigilockerfile($this->input->post('death_proof_temp'));
            $death_proof = $deathProof["upload_status"]?$deathProof["uploaded_path"]:null;
        } else {
            $deathProof = cifileupload("death_proof");
            $death_proof = $deathProof["upload_status"]?$deathProof["uploaded_path"]:null;
        }

        if (strlen($this->input->post("doc_for_relationship_temp")) > 0) {
            $docForRelationship = movedigilockerfile($this->input->post('doc_for_relationship_temp'));
            $doc_for_relationship = $docForRelationship["upload_status"]?$docForRelationship["uploaded_path"]:null;
        } else {
            $docForRelationship = cifileupload("doc_for_relationship");
            $doc_for_relationship = $docForRelationship["upload_status"]?$docForRelationship["uploaded_path"]:null;
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
            "death_proof_old" => strlen($death_proof)?$death_proof:$this->input->post("death_proof_old"),
            "doc_for_relationship_old" => strlen($doc_for_relationship)?$doc_for_relationship:$this->input->post("doc_for_relationship_old"),
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
                'form_data.death_proof_type' => $this->input->post("death_proof_type"),
                'form_data.doc_for_relationship_type' => $this->input->post("doc_for_relationship_type"),
                'form_data.affidavit_type' => $this->input->post("affidavit_type"),
                //'form_data.others_type' => $this->input->post("others_type"),
                'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),
                'form_data.death_proof' => strlen($death_proof)?$death_proof:$this->input->post("death_proof_old"),
                'form_data.doc_for_relationship' => strlen($doc_for_relationship)?$doc_for_relationship:$this->input->post("doc_for_relationship_old"),
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
            redirect('spservices/nextofkin/registration/preview/'.$objId);
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
            $this->load->view('nextofkin/nextofkinpreview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/nextofkin/registration');
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
            $this->load->view('nextofkin/nextofkinapplication_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/nextofkin/registration');
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

            $FamilyDetails = array();
            if(count($dbRow->form_data->family_details)) {
                foreach($dbRow->form_data->family_details as $key => $family_detail) {

                    $family_detail = array(
                        "nameOfKin" => $family_detail->name_of_kin,
                        "relationOfKin" => $family_detail->relation,
                        "ageOfKinYear" => $family_detail->age_y_on_the_date_of_application,
                        "ageOfKinMonths" =>$family_detail->age_m_on_the_date_of_application,
                    );
                    $FamilyDetails[] = $family_detail;
                }//End of foreach()        
            }//End of if

            $postdata=array(
                "dateOfBirth" => $dbRow->form_data->dob,
                "cscid" => "RTPS1234",
                "cscoffice" => "NA",
                "circleOffice" => $dbRow->form_data->revenue_circle,
                "applicantName" => $dbRow->form_data->applicant_name,
                "applicantGender" => $dbRow->form_data->applicant_gender,
                "applicantMobileNo" => $dbRow->form_data->mobile,
                "emailId" => $dbRow->form_data->email,
                "districtofDeceased" => $dbRow->form_data->deceased_district,
                "subdivisionDeceased" => $dbRow->form_data->deceased_sub_division,
                "villageofDeceased" => $dbRow->form_data->deceased_village,
                "townPermanent" => $dbRow->form_data->deceased_town,
                "pinPermanent" => $dbRow->form_data->deceased_pin_code,
                "FamilyDetails" => $FamilyDetails,
                "application_ref_no" => $dbRow->service_data->appl_ref_no,
                "service_type" => "NOK",
                "district" => $dbRow->form_data->district,
                "subDivision" => $dbRow->form_data->sub_division,
                "revenueCircleofDeceased" => $dbRow->form_data->deceased_revenue_circle,
                "policeStationPermanent" => $dbRow->form_data->deceased_police_station,
                "postOfficePermanent" => $dbRow->form_data->deceased_post_office,
                "mauzaPermanent" => $dbRow->form_data->deceased_mouza,
                "state" => "Assam",
                "panNo" => $dbRow->form_data->pan_no,
                "aadharNo" => $dbRow->form_data->aadhar_no,
                "DeceasedName" => $dbRow->form_data->name_of_deceased,
                "deceasedGender" => $dbRow->form_data->deceased_gender,
                "dateOfDeath" => $dbRow->form_data->deceased_dod,
                "DeathReason" => $dbRow->form_data->reason_of_death,
                "PlaceofDeath" => $dbRow->form_data->place_of_death,
                "fatherofDeceased" => $dbRow->form_data->father_name_of_deceased,
                "fatherName" => $dbRow->form_data->father_name,
                "motherName" => $dbRow->form_data->mother_name,
                "husbandName" => $dbRow->form_data->spouse_name,
                "Relation" => $dbRow->form_data->relationship_with_deceased,
                "fillUpLanguage" => "English",

                'spId'=>array('applId'=>$dbRow->service_data->appl_id)
            );

            if(!empty($dbRow->form_data->other_relation)){
                $postdata['relationWithDeceasedOther'] = $dbRow->form_data->other_relation;
            }

            if(!empty($dbRow->form_data->affidavit)){
                $affidavit = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->affidavit));

                $attachment_one = array(
                    "encl" =>  $affidavit,
                    "docType" => "application/pdf",
                    "enclFor" => "Affidavit",
                    "enclType" => $dbRow->form_data->affidavit_type,
                    "id" => "65441673",
                    "doctypecode" => "7503",
                    "docRefId" => "7504",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentOne'] = $attachment_one;
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

            if(!empty($dbRow->form_data->death_proof)){
                $death_proof = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->death_proof));

                $attachment_three = array(
                    "encl" =>  $death_proof,
                    "docType" => "application/pdf",
                    "enclFor" => "Death Proof",
                    "enclType" => $dbRow->form_data->death_proof_type,
                    "id" => "65441673",
                    "doctypecode" => "7503",
                    "docRefId" => "7504",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentThree'] = $attachment_three;
            }

            if(!empty($dbRow->form_data->doc_for_relationship)){
                $doc_for_relationship = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->doc_for_relationship));

                $attachment_two = array(
                    "encl" => $doc_for_relationship,
                    "docType" => "application/pdf",
                    "enclFor" => "Document for relationship proof",
                    "enclType" => $dbRow->form_data->doc_for_relationship_type,
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
            //  die;

            $url=$this->config->item('next_of_kin_url');
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
                        "service_name" => 'Next of Kin Certificate',
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
            $this->load->view('nextofkin/nokcertificatetrack_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$objId);
            redirect('spservices/necertificate/');
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
                $this->load->view('nextofkin/nextofkinquery_view',$data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found in query mode against object id : '.$objId);
                redirect('spservices/nextofkin/registration');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/nextofkin/registration');
        }//End of if else
    }//End of query()

    public function querysubmit() {        
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");

        $this->form_validation->set_rules('death_proof_type', 'Death Proof', 'required');
        $this->form_validation->set_rules('doc_for_relationship_type', 'Document for relationship proof', 'required');
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

        $deathProof = cifileupload("death_proof");
        $death_proof = $deathProof["upload_status"]?$deathProof["uploaded_path"]:$this->input->post("death_proof_old");

        $docForRelationship = cifileupload("doc_for_relationship");
        $doc_for_relationship = $docForRelationship["upload_status"]?$docForRelationship["uploaded_path"]:$this->input->post("doc_for_relationship_old");
        
        $softCopy = cifileupload("soft_copy");
        $soft_copy = $softCopy["upload_status"]?$softCopy["uploaded_path"]:null;
        
        $uploadedFiles = array(
            "affidavit_old" => strlen($affidavit)?$affidavit:$this->input->post("affidavit_old"),
            "others_old" => strlen($others)?$others:$this->input->post("others_old"),
            "death_proof_old" => strlen($death_proof)?$death_proof:$this->input->post("death_proof_old"),
            "doc_for_relationship_old" => strlen($doc_for_relationship)?$doc_for_relationship:$this->input->post("doc_for_relationship_old"),
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
                    'form_data.death_proof_type' => $this->input->post("death_proof_type"),
                    'form_data.doc_for_relationship_type' => $this->input->post("doc_for_relationship_type"),
                    'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),
                    'form_data.affidavit' => strlen($affidavit)?$affidavit:$this->input->post("affidavit_old"),
                    //'form_data.others' => strlen($others)?$others:$this->input->post("others_old"),
                    'form_data.death_proof' => strlen($death_proof)?$death_proof:$this->input->post("death_proof_old"),
                    'form_data.doc_for_relationship' => strlen($doc_for_relationship)?$doc_for_relationship:$this->input->post("doc_for_relationship_old"), 
                    'form_data.soft_copy' => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old"),               
                    'form_data.updated_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                );

                if (!empty($this->input->post("others_type"))) {
                    $data["form_data.others_type"] = $this->input->post("others_type");
                    $data["form_data.others"] = strlen($others)?$others:$this->input->post("others_old");
                }

                $this->registration_model->update_where(['_id' => new ObjectId($objId)], $data);

                //update to server
                $FamilyDetails = array();
                if(count($dbRow->form_data->family_details)) {
                    foreach($dbRow->form_data->family_details as $key => $family_detail) {
        
                        $family_detail = array(
                            "nameOfKin" => $family_detail->name_of_kin,
                            "relationOfKin" => $family_detail->relation,
                            "ageOfKinYear" => $family_detail->age_y_on_the_date_of_application,
                            "ageOfKinMonths" =>$family_detail->age_m_on_the_date_of_application,
                        );
                        $FamilyDetails[] = $family_detail;
                    }//End of foreach()        
                }//End of if
        
                $postdata=array(
                    "dateOfBirth" => $dbRow->form_data->dob,
                    "cscid" => "RTPS1234",
                    "cscoffice" => "NA",
                    "revert" => "NA",
                    "circleOffice" => $dbRow->form_data->revenue_circle,
                    "applicantName" => $dbRow->form_data->applicant_name,
                    "applicantGender" => $dbRow->form_data->applicant_gender,
                    "applicantMobileNo" => $dbRow->form_data->mobile,
                    "emailId" => $dbRow->form_data->email,
                    "districtofDeceased" =>$dbRow->form_data->deceased_district,
                    "subdivisionDeceased" => $dbRow->form_data->deceased_sub_division,
                    "villageofDeceased" => $dbRow->form_data->deceased_village,
                    "townPermanent" => $dbRow->form_data->deceased_town,
                    "pinPermanent" => $dbRow->form_data->deceased_pin_code,
                    "FamilyDetails" => $FamilyDetails,
                    "application_ref_no" => $dbRow->service_data->appl_ref_no,
                    "service_type" => "NOK",
                    "district" => $dbRow->form_data->district,
                    "subDivision" => $dbRow->form_data->sub_division,
                    "revenueCircleofDeceased" => $dbRow->form_data->deceased_revenue_circle,
                    "policeStationPermanent" => $dbRow->form_data->deceased_police_station,
                    "postOfficePermanent" => $dbRow->form_data->deceased_post_office,
                    "mauzaPermanent" => $dbRow->form_data->deceased_mouza,
                    "state" => "Assam",
                    "panNo" => $dbRow->form_data->pan_no,
                    "aadharNo" => $dbRow->form_data->aadhar_no,
                    "DeceasedName" => $dbRow->form_data->name_of_deceased,
                    "deceasedGender" => $dbRow->form_data->deceased_gender,
                    "dateOfDeath" => $dbRow->form_data->deceased_dod,
                    "DeathReason" => $dbRow->form_data->reason_of_death,
                    "PlaceofDeath" => $dbRow->form_data->place_of_death,
                    "fatherofDeceased" => $dbRow->form_data->father_name_of_deceased,
                    "fatherName" => $dbRow->form_data->father_name,
                    "motherName" => $dbRow->form_data->mother_name,
                    "husbandName" => $dbRow->form_data->spouse_name,
                    "Relation" => $dbRow->form_data->relationship_with_deceased,
                    "fillUpLanguage" => "English",
        
                    'spId'=>array('applId'=>$dbRow->service_data->appl_id)
                );

                if(!empty($dbRow->form_data->other_relation)){
                    $postdata['relationWithDeceasedOther'] = $dbRow->form_data->other_relation;
                }
        
                if(strlen($affidavit)){
                    $affidavit_type = (!empty($this->input->post("affidavit_type")))?$this->input->post("affidavit_type"):$dbRow->form_data->affidavit_type;
                    $affidavit = strlen($affidavit)?base64_encode(file_get_contents(FCPATH.$affidavit)):null;

        
                    $attachment_one = array(
                        "encl" =>  $affidavit,
                        "docType" => "application/pdf",
                        "enclFor" => "Affidavit",
                        "enclType" => $affidavit_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
        
                    $postdata['AttachmentOne'] = $attachment_one;
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
        
                if(strlen($death_proof)){
                    $death_proof_type = (!empty($this->input->post("death_proof_type")))?$this->input->post("death_proof_type"):$dbRow->form_data->death_proof_type;
                    $deathProof = strlen($death_proof)?base64_encode(file_get_contents(FCPATH.$death_proof)):null;
        
                    $attachment_three = array(
                        "encl" =>  $deathProof,
                        "docType" => "application/pdf",
                        "enclFor" => "Death Proof",
                        "enclType" => $death_proof_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
        
                    $postdata['AttachmentThree'] = $attachment_three;
                }
        
                if(strlen($doc_for_relationship)){
                    $doc_for_relationship_type = (!empty($this->input->post("doc_for_relationship_type")))?$this->input->post("doc_for_relationship_type"):$dbRow->form_data->doc_for_relationship_type;
                    $docForRelationship = strlen($doc_for_relationship)?base64_encode(file_get_contents(FCPATH.$doc_for_relationship)):null;
        
                    $attachment_two = array(
                        "encl" => $docForRelationship,
                        "docType" => "application/pdf",
                        "enclFor" => "Document for relationship proof",
                        "enclType" => $doc_for_relationship_type,
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
        
                $json_obj = json_encode($postdata);
                
                $url=$this->config->item('next_of_kin_url');
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
                        redirect('spservices/nextofkin/registration/preview/'.$objId);
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
        $str = "RTPS-NOKIN/" . $date."/" .$number;
        return $str;
    }//End of generateID()    

    public function get_age(){
        $dob = $this->input->post("dob");        
        if(strlen($dob) == 10) {
            $date_of_birth = new DateTime($dob);
            $nowTime = new DateTime();
            $interval = $date_of_birth->diff($nowTime);
            echo $interval->format('%y Years %m Months and %d Days');
        } else {
            echo "Invalid date format";
        }//End of if else
    }//End of get_age()

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
                
                $FamilyDetails = array();
                if(count($dbRow->form_data->family_details)) {
                    foreach($dbRow->form_data->family_details as $key => $family_detail) {
    
                        $family_detail = array(
                            "nameOfKin" => $family_detail->name_of_kin,
                            "relationOfKin" => $family_detail->relation,
                            "ageOfKinYear" => $family_detail->age_y_on_the_date_of_application,
                            "ageOfKinMonths" =>$family_detail->age_m_on_the_date_of_application,
                        );
                        $FamilyDetails[] = $family_detail;
                    }//End of foreach()        
                }//End of if
    
                $postdata=array(
                    "dateOfBirth" => $dbRow->form_data->dob,
                    "cscid" => "RTPS1234",
                    "cscoffice" => "NA",
                    "circleOffice" => $dbRow->form_data->revenue_circle,
                    "applicantName" => $dbRow->form_data->applicant_name,
                    "applicantGender" => $dbRow->form_data->applicant_gender,
                    "applicantMobileNo" => $dbRow->form_data->mobile,
                    "emailId" => $dbRow->form_data->email,
                    "districtofDeceased" => $dbRow->form_data->deceased_district,
                    "subdivisionDeceased" => $dbRow->form_data->deceased_sub_division,
                    "villageofDeceased" => $dbRow->form_data->deceased_village,
                    "townPermanent" => $dbRow->form_data->deceased_town,
                    "pinPermanent" => $dbRow->form_data->deceased_pin_code,
                    "FamilyDetails" => $FamilyDetails,
                    "application_ref_no" => $dbRow->service_data->appl_ref_no,
                    "service_type" => "NOK",
                    "district" => $dbRow->form_data->district,
                    "subDivision" => $dbRow->form_data->sub_division,
                    "revenueCircleofDeceased" => $dbRow->form_data->deceased_revenue_circle,
                    "policeStationPermanent" => $dbRow->form_data->deceased_police_station,
                    "postOfficePermanent" => $dbRow->form_data->deceased_post_office,
                    "mauzaPermanent" => $dbRow->form_data->deceased_mouza,
                    "state" => "Assam",
                    "panNo" => $dbRow->form_data->pan_no,
                    "aadharNo" => $dbRow->form_data->aadhar_no,
                    "DeceasedName" => $dbRow->form_data->name_of_deceased,
                    "deceasedGender" => $dbRow->form_data->deceased_gender,
                    "dateOfDeath" => $dbRow->form_data->deceased_dod,
                    "DeathReason" => $dbRow->form_data->reason_of_death,
                    "PlaceofDeath" => $dbRow->form_data->place_of_death,
                    "fatherofDeceased" => $dbRow->form_data->father_name_of_deceased,
                    "fatherName" => $dbRow->form_data->father_name,
                    "motherName" => $dbRow->form_data->mother_name,
                    "husbandName" => $dbRow->form_data->spouse_name,
                    "Relation" => $dbRow->form_data->relationship_with_deceased,
                    "fillUpLanguage" => "English",
    
                    'spId'=>array('applId'=>$dbRow->service_data->appl_id)
                );

                if(!empty($dbRow->form_data->other_relation)){
                    $postdata['relationWithDeceasedOther'] = $dbRow->form_data->other_relation;
                }
    
                if(!empty($dbRow->form_data->affidavit)){
                    $affidavit = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->affidavit));
    
                    $attachment_one = array(
                        "encl" =>  $affidavit,
                        "docType" => "application/pdf",
                        "enclFor" => "Affidavit",
                        "enclType" => $dbRow->form_data->affidavit_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
    
                    $postdata['AttachmentOne'] = $attachment_one;
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
    
                if(!empty($dbRow->form_data->death_proof)){
                    $death_proof = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->death_proof));
    
                    $attachment_three = array(
                        "encl" =>  $death_proof,
                        "docType" => "application/pdf",
                        "enclFor" => "Death Proof",
                        "enclType" => $dbRow->form_data->death_proof_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
    
                    $postdata['AttachmentThree'] = $attachment_three;
                }
    
                if(!empty($dbRow->form_data->doc_for_relationship)){
                    $doc_for_relationship = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->doc_for_relationship));
    
                    $attachment_two = array(
                        "encl" => $doc_for_relationship,
                        "docType" => "application/pdf",
                        "enclFor" => "Document for relationship proof",
                        "enclType" => $dbRow->form_data->doc_for_relationship_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );
    
                    $postdata['AttachmentTwo'] = $attachment_two;
                }

                // pre($postdata);
    
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
    
                $url=$this->config->item('next_of_kin_url');
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
                            "service_name" => 'Next of Kin Certificate',
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
