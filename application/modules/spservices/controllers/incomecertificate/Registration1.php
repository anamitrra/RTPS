<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration1 extends Rtps
{
    private $serviceName = "Application for Income Certificate";
    private $serviceId = "INC";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('incomecertificate/income_registration_model');
        // $this->load->model('incomeprocessing_model');
        // $this->load->model('sros_model');
        $this->load->helper("cifileupload");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
    } //End of __construct()



    public function index($obj_id = null)
    {
        $data = array("pageTitle" => "Application for Income Certificate");
        $filter = array("_id" => new ObjectId($obj_id), "service_data.appl_status" => "DRAFT");
        $data["dbrow"] = $this->income_registration_model->get_row($filter);
        $data['usser_type'] = $this->slug;
        $data["sro_dist_list"] = $this->income_registration_model->sro_dist_list();

        $this->load->view('includes/frontend/header');
        $this->load->view('incomecertificate/registration',$data);
        $this->load->view('includes/frontend/footer');
    } //End of index()
    public function getlocation()
    {
        $id = $_GET['id'];
        if ($id) {
            $data = $this->income_registration_model->get_sro_list($id);
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(array());
            }
        }
    }

    public function acknowledgement($obj) {

        $applicationRow = $this->income_registration_model->get_by_doc_id($obj);
        if ($applicationRow) {
            $service_id = $applicationRow->service_id;
            $data['response'] = $applicationRow;
         //   $data['service_row'] = $this->services_model->get_row(array("service_id"=>$service_id));
            $data['back_to_dasboard'] = '<a href="' . base_url('spservices/applications/') . '" class="btn btn-primary mb-2 pull-right"  >Back To Dashboard</a>';
            $data['pageTitle'] = "Acknowledgement";
            $this->load->view('includes/frontend/header');
            $this->load->view('ack', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            redirect('spservices/applications/');
        }//End of if else
    }

    public function submit()
    {
        $obj = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");
        $rtps_trans_id = $this->input->post("rtps_trans_id");
        $this->form_validation->set_rules('name_prefix', 'Name prefix', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('applicant_name', 'Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|integer|required|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('aadhar', 'Aadhar Number', 'trim|xss_clean|strip_tags|max_length[16]');
        $this->form_validation->set_rules('pan', 'PAN Number', 'trim|xss_clean|strip_tags|max_length[10]');
        $this->form_validation->set_rules('email', 'Email', 'valid_email|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('relation', 'Relation', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('relative', 'Relatives Name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('source_income', 'Source of Income', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('occupation', 'Occupation', 'trim|required|xss_clean|strip_tags|max_length[255]');
         $this->form_validation->set_rules('total_income', 'Total Income', 'trim|required|xss_clean|strip_tags|numeric');
       // $this->form_validation->set_rules('spouse_name', 'Spouse name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
       // $this->form_validation->set_rules('dob', 'DOB', 'trim|required|exact_length[10]|xss_clean|strip_tags');
       // $this->form_validation->set_rules('father_name', 'Father name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        //$this->form_validation->set_rules('identification_mark', 'Identification mark', 'trim|required|xss_clean|strip_tags');
       // $this->form_validation->set_rules('occupation', 'Occupation', 'trim|required|xss_clean|strip_tags');
       // $this->form_validation->set_rules('blood_group', 'Blood group', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('service_type', 'Service type', 'trim|required|xss_clean|strip_tags');
       // $this->form_validation->set_rules('pan_no', 'PAN no', 'trim|required|xss_clean|strip_tags');
       // $this->form_validation->set_rules('aadhar_no', 'Aadhar no', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('address1', 'Address Line 1', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('address2', 'Address Line 2', 'trim|required|xss_clean|strip_tags'); 
        $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('office_district', 'District', 'trim|required|xss_clean|strip_tags');
      //  $this->form_validation->set_rules('district_id', 'District', 'trim|required|xss_clean|strip_tags|numeric');
        $this->form_validation->set_rules('subdivision', 'Subdivision', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('circle_name', 'Circle Office', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mouza', 'Mouza', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('village', 'Village/Town', 'trim|required|xss_clean|strip_tags');
       // $this->form_validation->set_rules('house_no', 'House no', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('policestation', 'Police Station', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('postoffice', 'Post Office', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pincode', 'Pincode', 'trim|required|xss_clean|strip_tags|numeric|exact_length[6]');
      //  $this->form_validation->set_rules('landline_no', 'Land line no', 'trim|required|integer|exact_length[10]|xss_clean|strip_tags');

       // $this->form_validation->set_rules('caste', 'Caste', 'trim|required|xss_clean|strip_tags');
       // $this->form_validation->set_rules('ex_serviceman', 'Ex-serviceman', 'trim|required|xss_clean|strip_tags');
       // $this->form_validation->set_rules('minority', 'Minority', 'trim|required|xss_clean|strip_tags');
       // $this->form_validation->set_rules('under_bpl', 'Is Falling Under BPL', 'trim|required|xss_clean|strip_tags');
       // $this->form_validation->set_rules('allowance', 'allowance', 'trim|required|xss_clean|strip_tags');
       // if ($this->input->post("allowance") == "Yes")
          //  $this->form_validation->set_rules('allowance_details', 'Allowance details', 'trim|required|xss_clean|strip_tags');

        // $this->form_validation->set_rules('inputcaptcha', 'Captcha', 'required|exact_length[6]');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($obj) ? $obj : null;
            $this->index($obj_id);
        } else {

            $appl_ref_no = $this->getID(7);
            $sessionUser = $this->session->userdata();


            if ($this->slug === "CSC") {
                $apply_by = $sessionUser['userId'];
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            } //End of if else

            while(1){
                $app_id = rand(1000000, 9999999);
                $filter = array( 
                    "service_data.appl_id" => $app_id
                );
                $rows = $this->income_registration_model->get_row($filter);
                
                if($rows == false)
                    break;
            }

            $transId = strlen($obj) ? $rtps_trans_id : $this->getID(7);

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
                "service_timeline" => "20 Days",
                "appl_status" => "DRAFT",
                "rtps_trans_id" => $transId,
            ];


            $form_data = [
                'user_id' => $sessionUser['userId']->{'$id'},
                'user_type' => $this->slug,
                'name_prefix' => $this->input->post("name_prefix"),
                'applicant_name' => $this->input->post("applicant_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
               // 'father_name' => $this->input->post("father_name"),
               // 'spouse_name' => $this->input->post("spouse_name"),
                'mobile' => $this->input->post("mobile"),
                'aadhar' => $this->input->post("aadhar"),
                'pan' => $this->input->post("pan"),
                'email' => $this->input->post("email"),
                'relation' => $this->input->post("relation"),
                'relative' => $this->input->post("relative"), 
                'source_income' => $this->input->post("source_income"),
                //  'dob' => $this->input->post("dob"),
              //  'identification_mark' => $this->input->post("identification_mark"),
                'occupation' => trim($this->input->post("occupation")),
              //  'blood_group' => $this->input->post("blood_group"),
               // 'pan_no' => $this->input->post("pan_no"),
               // 'aadhar_no' => $this->input->post("aadhar_no"),
                'total_income' => $this->input->post("total_income"),
                'address1' => $this->input->post("address1"),
                'address2' => $this->input->post("address2"),
                'state' => $this->input->post("state"),
                'office_district' => $this->input->post("office_district"),
                'district_id' => $this->input->post("district_id"),
                'subdivision' => $this->input->post("subdivision"),
                'mouza' => $this->input->post("mouza"),
              //  'revenuecircle' => $this->input->post("revenuecircle"),
                'circle_office' => $this->input->post("circle_name"),
                'village' => $this->input->post("village"),
               // 'service_type' => $this->input->post("service_type"),
               // 'house_no' => $this->input->post("house_no"),
                'policestation' => $this->input->post("policestation"),
               // 'police_st' => $this->input->post("police_st"),
               // 'post_office' => $this->input->post("post_office"),
                'postoffice'=>$this->input->post('postoffice'),
                'pincode' => $this->input->post("pincode"),

              //  'pin_code' => $this->input->post("pin_code"),
               // 'landline_no' => $this->input->post("landline_no"),
              //  'caste' => $this->input->post("caste"),
              //  'ex_serviceman' => $this->input->post("ex_serviceman"),
               // 'minority' => $this->input->post("minority"),
              //  'under_bpl' => $this->input->post("under_bpl"),
              //  'allowance' => $this->input->post("allowance"),
              //  'allowance_details' => $this->input->post("allowance_details"),
                // 'submit_mode' => $submitMode,
                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];


            if (strlen($obj)) {
                $form_data["address_proof_type"] = $this->input->post("address_proof_type");
                $form_data["address_proof"] = $this->input->post("address_proof");

                $form_data["identity_proof_type"] = $this->input->post("identity_proof_type");
                $form_data["identity_proof"] = $this->input->post("identity_proof");
                $form_data["land_revenue_receipt_type"] = $this->input->post("land_revenue_receipt_type");
                $form_data["land_revenue_receipt"] = $this->input->post("land_revenue_receipt");
                $form_data["salary_slip_type"] = $this->input->post("salary_slip_type");
                $form_data["salary_slip"] = $this->input->post("salary_slip");
              //  $form_data["age_proof"] = $this->input->post("age_proof");
              //  $form_data["age_proof_type"] = $this->input->post("age_proof_type");
              //  $form_data["age_proof"] = $this->input->post("age_proof");
                $form_data["other_doc_type"] = $this->input->post("other_doc_type");
                $form_data["other_doc"] = $this->input->post("other_doc");
              //  $form_data["passport_photo_type"] = $this->input->post("passport_photo_type");
              //  $form_data["passport_photo"] = $this->input->post("passport_photo");
              //  $form_data["proof_of_retirement_type"] = $this->input->post("proof_of_retirement_type");
              //  $form_data["proof_of_retirement"] = $this->input->post("proof_of_retirement");
                $form_data["soft_copy_type"] = $this->input->post("soft_copy_type");
                $form_data["soft_copy"] = $this->input->post("soft_copy");
            }

            $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data
            ];

            if (strlen($obj)) {
                $this->income_registration_model->update_where(['_id' => new ObjectId($obj)], $inputs);
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/incomecertificate/registration1/fileuploads/'. $obj);
            } else {
                $insert = $this->income_registration_model->insert($inputs);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    redirect('spservices/incomecertificate/registration1/fileuploads/'.$objectId);
                    exit();
                } else {
                    $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                    $this->index();
                } //End of if else
            } //End of if else

        } //End of if else
    } //End of submit()


    public function fileuploads($obj = null)
    {
        $dbRow = $this->income_registration_model->get_by_doc_id($obj);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "pageTitle" => "Attached Enclosures for " . $this->serviceName,
                "obj_id" => $obj,
                "dbrow" => $dbRow
            );
            $this->load->view('includes/frontend/header');
            //$this->load->view('nec/necertificateuploads_view',$data);
          //  $this->load->view('seniorcitizen/sccertificateuploads_view', $data);
            $this->load->view('incomecertificate/incomecertificateupload_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $obj);
            redirect('spservices/incomecertificate/');
        } //End of if else
    } //End of fileuploads()

    public function submit_to_backend($obj,$show_ack=false){
        pre($obj);
        if($obj){
            $dbRow = $this->income_registration_model->get_by_doc_id($obj);

             //procesing data
             $processing_history = $dbRow->processing_history??array();
             $processing_history[] = array(
                 "processed_by" => "Application submitted & payment made by KIOSK for ".$dbRow->form_data->applicant_name,
                 "action_taken" => "Application Submition",
                 "remarks" => "Application submitted & payment made by KIOSK for ".$dbRow->form_data->applicant_name,
                 "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
             );
             
           //  $FamilyDetails = array();
           //  if(count($dbRow->form_data->family_details)) {
            //     foreach($dbRow->form_data->family_details as $key => $family_detail) {
 
                    // $family_detail = array(
                      //   "nameOfKin" => $family_detail->name_of_kin,
                     //    "relationOfKin" => $family_detail->relation,
                       //  "ageOfKinYear" => $family_detail->age_y_on_the_date_of_application,
                     //    "ageOfKinMonths" =>$family_detail->age_m_on_the_date_of_application,
                   //  );
                 //    $FamilyDetails[] = $family_detail;
              //   }//End of foreach()        
          //   }//End of if
 
             $postdata=array(
             //    "dateOfBirth" => $dbRow->form_data->dob,
                 "cscid" => "RTPS1234",
                 "cscoffice" => "NA",
             //    "circle_name" => $dbRow->form_data->circle_name,
                 "applicant_name" => $dbRow->form_data->applicant_name,
                 "applicant_gender" => $dbRow->form_data->applicant_gender,
                 "mobile" => $dbRow->form_data->mobile,
                 "aadhar" => $dbRow->form_data->aadhar,
                 "pan" => $dbRow->form_data->pan,
                 "email" => $dbRow->form_data->email,
                 "relation" => $dbRow->form_data->relation,
                 "relative" => $dbRow->form_data->relative,
                 "source_income" => $dbRow->form_data->source_income,
                 "occupation" => $dbRow->form_data->occupation,
                 "total_income"=> $dbRow->form_data->total_income, 
                 "address1"=> $dbRow->form_data->address1, 
                 "address2"=> $dbRow->form_data->address2, 
                 "state"=> $dbRow->form_data->state, 
                 "office_district"=> $dbRow->form_data->office_district, 
                 "subdivision" => $dbRow->form_data->subdivision, 
                 'circle_office' => $dbRow->form_data->circle_office,
                 "mouza" => $dbRow->form_data->mouza, 
                 "village"=> $dbRow->form_data->village, 
                 "policestation" => $dbRow->form_data->policestation,  
                 "postoffice"=> $dbRow->form_data->postoffice,  
                 "pincode"=> $dbRow->form_data->pincode,  

              //   "FamilyDetails" => $FamilyDetails,
              //   "appl_ref_no" => $dbRow->service_data->appl_ref_no,
             //    "service_type" => "INC",
               //  "district" => $dbRow->form_data->district,
              //   "subDivision" => $dbRow->form_data->sub_division,
               //  "revenueCircleofDeceased" => $dbRow->form_data->deceased_revenue_circle,
               //  "policeStationPermanent" => $dbRow->form_data->deceased_police_station,
                // "postOfficePermanent" => $dbRow->form_data->deceased_post_office,
            //     "mauzaPermanent" => $dbRow->form_data->deceased_mouza,
              //   "state" => "Assam",
               //  "panNo" => $dbRow->form_data->pan_no,
               //  "aadharNo" => $dbRow->form_data->aadhar_no,
               //  "DeceasedName" => $dbRow->form_data->name_of_deceased,
              //   "deceasedGender" => $dbRow->form_data->deceased_gender,
             //    "dateOfDeath" => $dbRow->form_data->deceased_dod,
              //   "DeathReason" => $dbRow->form_data->reason_of_death,
               //  "PlaceofDeath" => $dbRow->form_data->place_of_death,
              //   "fatherofDeceased" => $dbRow->form_data->father_name_of_deceased,
               //  "fatherName" => $dbRow->form_data->father_name,
               //  "motherName" => $dbRow->form_data->mother_name,
             //    "husbandName" => $dbRow->form_data->spouse_name,
             //    "Relation" => $dbRow->form_data->relationship_with_deceased,
                 "fillUpLanguage" => "English",
 
                 'spId'=>array('applId'=>$dbRow->service_data->appl_id)

             );
             
             if(!empty($dbRow->form_data->address_proof)){
                $address_proof = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->address_proof));

                $attachment_zero = array(
                    "encl" =>  $address_proof,
                    "docType" => "application/pdf",
                    "enclFor" => "Address Proof",
                    "enclType" => $dbRow->form_data->address_proof_type,
                    "id" => "65441671",
                    "doctypecode" => "7501",
                    "docRefId" => "7501",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentZero'] = $attachment_zero;
            }

            if(!empty($dbRow->form_data->identity_proof)){
                $identity_proof = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->identity_proof));

                $attachment_one = array(
                    "encl" =>  $identity_proof,
                    "docType" => "application/pdf",
                    "enclFor" => "Identity Proof",
                    "enclType" => $dbRow->form_data->identity_proof_type,
                    "id" => "65441672",
                    "doctypecode" => "7502",
                    "docRefId" => "7502",
                    "enclExtn" => "pdf"
               );

               $postdata['AttachmentOne'] = $attachment_one;
           }

            if(!empty($dbRow->form_data->land_revenue_receipt)){
                $land_revenue_receipt = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->land_revenue_receipt));

                $attachment_two = array(
                    "encl" =>  $land_revenue_receipt,
                    "docType" => "application/pdf",
                    "enclFor" => "Land Revenue Receipt",
                    "enclType" => $dbRow->form_data->land_revenue_receipt_type,
                    "id" => "65441673",
                    "doctypecode" => "7503",
                    "docRefId" => "7503",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentTwo'] = $attachment_two;
            }

            if(!empty($dbRow->form_data->salary_slip)){
                $salary_slip = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->salary_slip));

                $attachment_three = array(
                    "encl" =>  $salary_slip,
                    "docType" => "application/pdf",
                    "enclFor" => "Salary Slip",
                    "enclType" => $dbRow->form_data->salary_slip_type,
                    "id" => "65441674",
                    "doctypecode" => "7504",
                    "docRefId" => "7504",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentThree'] = $attachment_three;
            }

            if(!empty($dbRow->form_data->other_doc)){
                $other_doc = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->other_doc));

                $attachment_four = array(
                    "encl" =>  $other_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Any Other Document",
                    "enclType" => $dbRow->form_data->other_doc_type,
                    "id" => "65441675",
                    "doctypecode" => "7505",
                    "docRefId" => "7505",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentFour'] = $attachment_four;
            }
            if(!empty($dbRow->form_data->soft_copy)){
                $soft_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->soft_copy));

                $attachment_five = array(
                    "encl" =>  $soft_copy,
                    "docType" => "application/pdf",
                    "enclFor" => "Upload Scanned Copy of the Applicant form",
                    "enclType" => $dbRow->form_data->soft_copy_type,
                    "id" => "65441676",
                    "doctypecode" => "7506",
                    "docRefId" => "7506",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentFive'] = $attachment_five;
            }




           //  $json = json_encode($postdata);
           // $buffer = preg_replace( "/\r|\n/", "", $json );
           // $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
           // fwrite($myfile, $buffer);
           // fclose($myfile);
           //  die;

            $url=$this->config->item('edistrict_base_url');
            $curl = curl_init($url."postApplication?apiKey=un5pdxOw1glKratBgIlL5w&rprm=abc");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
           
            curl_close($curl);

            

           if($response){
               $response = json_decode($response);
               
               //pre($response);
               if($response->ref->status === "success"){
                   $data_to_update=array(
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
                       "service_name" => 'Income Certificate',
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

    //public function submit_to_backend($obj, $show_ack = false)
   // {
   //     if ($obj) {
       //     $dbRow = $this->income_registration_model->get_by_doc_id($obj);

            //procesing data
        //    $processing_history = $dbRow->processing_history ?? array();
        //    $processing_history[] = array(
          //      "processed_by" => "Application submitted & payment made by KIOSK for " . $dbRow->applicant_name,
           //     "action_taken" => "Application Submition",
            //    "remarks" => "Application submitted & payment made by KIOSK for " . $dbRow->applicant_name,
           //     "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
          //  );
        //    if (property_exists($dbRow, "serial_registration_not_availabe") && $dbRow->serial_registration_not_availabe === "1") {
             //   $deedno = "Year from: " . $dbRow->year_from . ",Year To: " . $dbRow->year_to . ",Party Name: " . $dbRow->deed_party_name . ",Patta No: " . $dbRow->deed_patta_no . ",Daag No: " . $dbRow->deed_dag_no . ",Land Area: " . $dbRow->deed_total_land_area;
          //  } else {
          //      $deedno = "SL No: " . $dbRow->deedno . ",Reg No: " . $dbRow->year_of_registration;
          //  }
          //  $postdata = array(
              //  'deed_no' => $deedno,
            //    'applicant_name' => $dbRow->applicant_name,
             //   'mobile' => $dbRow->mobile,
             //   'address' => $dbRow->address,
             //   'relation' => $dbRow->relation,
             //   'date_of_application' => date('Y-m-d'),
              //  'service_mode' => $dbRow->service_mode,
              //  'application_ref_no' => $dbRow->rtps_trans_id,
              //  'sro_code' => !empty($dbRow->sro_code) ? $dbRow->sro_code : "",
               // 'spId' => array('applId' => $dbRow->applId)
         //   );

        //    $url = $this->config->item('url');
         //   $curl = curl_init($url . "cercpy/post_certicopy.php");
            // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
         //   curl_setopt($curl, CURLOPT_POST, true);
        //    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
        //    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        //    $response = curl_exec($curl);


        //    curl_close($curl);
         //   if ($response) {
            //    $response = json_decode($response);

             //   if ($response->ref->status === "success") {
                  //  $data_to_update = array(
                    //    'status' => 'submitted',
                   //     'submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                 //       'processing_history' => $processing_history
                 //   );
                 //   $this->income_registration_model->update($obj, $data_to_update);
                    //Add to processing history        
                 //   if ($show_ack) {
             //           return true;
                 //   }
              //  }
          //  }
      //  }
      //  redirect('iservices/transactions');
  //  }

    public function finalsubmition($obj = null)
    {
        // $obj = $this->input->post('obj');
        if ($obj) {
            $dbRow = $this->income_registration_model->get_by_doc_id($obj);
            //procesing data
            $processing_history = $dbRow->processing_history ?? array();
            $processing_history[] = array(
                "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                "action_taken" => "Application Submition",
                "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            );

            //$postdata = [];
            $postdata = array(
                'application_ref_no' => $dbRow->service_data->appl_ref_no,
                "name_prefix" =>isset( $dbrow->form_data->name_prefix)?$dbrow->form_data->name_prefix:"",
                'applicant_name' => $dbRow->form_data->applicant_name,
                'applicantGender' => $dbRow->form_data->applicant_gender,
                'mobile' => $dbRow->form_data->mobile,
                'aadhar' => $dbRow->form_data->aadhar,
                'pan' => $dbRow->form_data->pan,
                'email' => $dbRow->form_data->email,
                'relation' => $dbRow->form_data->relation,
                'relative' => $dbRow->form_data->relative,
                'source_income' => $dbRow->form_data->source_income,
                'occupation' => $dbRow->form_data->occupation,
                'total_income' => $dbRow->form_data->total_income,

              //  'fathersName' => $dbRow->form_data->father_name,
               // 'applicantMobileNo' => $dbRow->form_data->mobile,
                //'emailId' => $dbRow->form_data->email,
                //'dateOfBirth' => $dbRow->form_data->dob,
                //'panNo' => $dbRow->form_data->pan_no,
               // 'aadharNo' => $dbRow->form_data->aadhar_no,
               // 'nameOfSpouse' => $dbRow->form_data->spouse_name,
                //'serviceType' => $dbRow->form_data->service_type,
                'address1'=> $dbRow->form_data->address1,
                'address2'=> $dbRow->form_data->address2,
               // 'addressLine1'=> $dbRow->form_data->address_line1,
               // 'addressLine2'=> $dbRow->form_data->address_line2,
                'state' => $dbRow->form_data->state,
                'office_district' => $dbRow->form_data->office_district,
                'subdivision' => $dbRow->form_data->subdivision,
                'circle_office' => $dbRow->form_data->circle_office,
                'mouza' => $dbRow->form_data->mouza,
                'village' => $dbRow->form_data->village,
                'policestation' => $dbRow->form_data->policestation,
                'postoffice' => $dbRow->form_data->postoffice,
                //'houseNo' => $dbRow->form_data->house_no,
                'pincode'=> $dbRow->form_data->pincode,
               // 'occupation'=> $dbRow->form_data->occupation,
                //'identificationMark' => $dbRow->form_data->identification_mark,
               // 'bloodGroup' => $dbRow->form_data->blood_group,
               // 'isMinority' => $dbRow->form_data->minority,
               // 'isFallingUnderBPL' => $dbRow->form_data->under_bpl,
                //'caste' => $dbRow->form_data->caste,
                //'isExserviceman' => $dbRow->form_data->ex_serviceman,
               // 'gettingAllowance' => $dbRow->form_data->allowance,
              //  'allowanceDetails' => $dbRow->form_data->allowance_details,
                'cscid' => "RTPS1234",
                'fillUpLanguage' => "English",
               'service_type' => "INC",
                'cscoffice' => "NA",
                'spId' => array('applId' => $dbRow->service_data->appl_id)
            );

           // if(!empty($dbRow->form_data->passport_photo)){
              //  $passport_photo = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->passport_photo));

              //  $attachment_zero = array(
                //    "encl" =>  $passport_photo,
                //    "docType" => "application/pdf",
                 //   "enclFor" => "Passport size photograph",
                 //   "enclType" => $dbRow->form_data->passport_photo_type,
                  //  "id" => "65441671",
                 //   "doctypecode" => "7501",
                  //  "docRefId" => "7501",
                  //  "enclExtn" => "jpg/jpeg"
                //);

              //  $postdata['AttachmentZero'] = $attachment_zero;
           // }
            if(!empty($dbRow->form_data->address_proof)){
                $address_proof = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->address_proof));

                $attachment_zero = array(
                    "encl" =>  $address_proof,
                    "docType" => "application/pdf",
                    "enclFor" => "Address Proof",
                    "enclType" => $dbRow->form_data->address_proof_type,
                    "id" => "65441671",
                    "doctypecode" => "7501",
                    "docRefId" => "7501",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentZero'] = $attachment_zero;
            }
            if(!empty($dbRow->form_data->identity_proof)){
                $identity_proof = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->identity_proof));

                $attachment_one = array(
                    "encl" =>  $identity_proof,
                    "docType" => "application/pdf",
                    "enclFor" => "Identity Proof",
                    "enclType" => $dbRow->form_data->identity_proof_type,
                    "id" => "65441672",
                    "doctypecode" => "7502",
                    "docRefId" => "7502",
                    "enclExtn" => "pdf"
                );
                $postdata['AttachmentOne'] = $attachment_one;
               // $postdata['AttachmentTwo'] = $attachment_two;
            }
            if(!empty($dbRow->form_data->land_revenue_receipt)){
                $land_revenue_receipt = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->land_revenue_receipt));

                $attachment_two = array(
                    "encl" =>  $land_revenue_receipt,
                    "docType" => "application/pdf",
                    "enclFor" => "Land Revenue Receipt",
                    "enclType" => $dbRow->form_data->land_revenue_receipt_type,
                    "id" => "65441673",
                    "doctypecode" => "7503",
                    "docRefId" => "7503",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentTwo'] = $attachment_two;
            }
            if(!empty($dbRow->form_data->salary_slip)){
                $salary_slip = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->salary_slip));

                $attachment_three = array(
                    "encl" =>  $salary_slip,
                    "docType" => "application/pdf",
                    "enclFor" => "Salary Slip",
                    "enclType" => $dbRow->form_data->salary_slip_type,
                    "id" => "65441674",
                    "doctypecode" => "7504",
                    "docRefId" => "7504",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentThree'] = $attachment_three;
            }
            if(!empty($dbRow->form_data->other_doc)){
                $other_doc = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->other_doc));

                $attachment_four = array(
                    "encl" =>  $other_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Any Other Document",
                    "enclType" => $dbRow->form_data->other_doc_type,
                    "id" => "65441675",
                    "doctypecode" => "7505",
                    "docRefId" => "7505",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentFour'] = $attachment_four;
            }
            if(!empty($dbRow->form_data->soft_copy)){
                $soft_copy = base64_encode(file_get_contents(FCPATH.$dbRow->form_data->soft_copy));

                $attachment_five = array(
                    "encl" =>  $soft_copy,
                    "docType" => "application/pdf",
                    "enclFor" => "Upload Scanned Copy of the Applicant form",
                    "enclType" => $dbRow->form_data->soft_copy_type,
                    "id" => "65441676",
                    "doctypecode" => "7506",
                    "docRefId" => "7506",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentFive'] = $attachment_five;
            }

            

            $url = $this->config->item('edistrict_base_url');
            $curl = curl_init($url . "postApplication?apiKey=un5pdxOw1glKratBgIlL5w&rprm=xyz");


         //   pre(json_encode($postdata));
            // $buffer = preg_replace( "/\r|\n/", "", $json );
            // $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
            // fwrite($myfile, $buffer);
            // fclose($myfile);
            //  die;



            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
            curl_close($curl);
            
            if ($response) {
                $response = json_decode($response);
                if ($response->ref->status === "success") {
                    $data_to_update = array(
                        'service_data.appl_status' => 'submitted',
                        'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        'processing_history' => $processing_history
                    );
                    $this->income_registration_model->update($obj, $data_to_update);
                    //generate acknowlegement
                    redirect('spservices/incomecertificate/acknowledgement/acknowledgement/'.$obj);
                } else {
                    //redierct to applivation page
                    $this->session->set_flashdata('error','Something went wrong please try again.');
                    redirect('spservices/incomecertificate/registration1/index/'.$obj);
                }


                 //   return $this->output
                   //     ->set_content_type('application/json')
                    //    ->set_status_header(200)
                     //   ->set_output(json_encode(array("status" => true)));
              //  } else {
                  //  return $this->output
                     //   ->set_content_type('application/json')
                      //  ->set_status_header(401)
                      //  ->set_output(json_encode(array("status" => false)));
            }
        }
    }

    public function submitfiles()
    {
        $obj = $this->input->post("obj_id");
        $this->form_validation->set_rules('address_proof_type', 'Address Proof', 'required');
        $this->form_validation->set_rules('identity_proof_type', 'Identity Proof', 'required');
        $this->form_validation->set_rules('land_revenue_receipt_type', 'Land Revenue Receipt', 'required');
        $this->form_validation->set_rules('salary_slip_type', 'Salary Slip', 'required');
        $this->form_validation->set_rules('other_doc_type', 'Any Other Document', 'required');
       // $this->form_validation->set_rules('passport_photo_type', 'Passport photo', 'required');
       // $this->form_validation->set_rules('proof_of_retirement_type', 'Proof of retirement', 'required');
       // $this->form_validation->set_rules('age_proof_type', 'Age proof', 'required');
      //  $this->form_validation->set_rules('address_proof_type', 'Address proof', 'required');
      //  $this->form_validation->set_rules('other_doc_type', 'Other doc', 'required');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
          
        $addressProof = cifileupload("address_proof");
        $address_proof = $addressProof["upload_status"]?$addressProof["uploaded_path"]:null;
        $identityProof = cifileupload("identity_proof");
        $identity_proof = $identityProof["upload_status"]?$identityProof["uploaded_path"]:null;
        $landrevenueReceipt = cifileupload("land_revenue_receipt");
        $land_revenue_receipt = $landrevenueReceipt["upload_status"]?$landrevenueReceipt["uploaded_path"]:null;
        $salarySlip = cifileupload("salary_slip");
        $salary_slip = $salarySlip["upload_status"]?$salarySlip["uploaded_path"]:null;
        $otherDocument = cifileupload("other_doc");
        $other_doc = $otherDocument["upload_status"]?$otherDocument["uploaded_path"]:null;
        $softCopy = cifileupload("soft_copy");
        $soft_copy = $softCopy["upload_status"]?$softCopy["uploaded_path"]:null;


      //  $passportPhoto = cifileupload("passport_photo");
       // $passport_photo = $passportPhoto["upload_status"] ? $passportPhoto["uploaded_path"] : null;

       // $proofofRetirement = cifileupload("proof_of_retirement");
       // $proof_of_retirement = $proofofRetirement["upload_status"] ? $proofofRetirement["uploaded_path"] : null;

      //  $ageProof = cifileupload("age_proof");
      //  $age_proof = $ageProof["upload_status"] ? $ageProof["uploaded_path"] : null;

      //  $addressProof = cifileupload("address_proof");
      //  $address_proof = $addressProof["upload_status"] ? $addressProof["uploaded_path"] : null;

       // $otherDoc = cifileupload("other_doc");
      //  $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;

      //  $softCopy = cifileupload("soft_copy");
      //  $soft_copy = $softCopy["upload_status"] ? $softCopy["uploaded_path"] : null;

        $uploadedFiles = array(
            "address_proof_old" => strlen($address_proof)?$address_proof:$this->input->post("address_proof_old"),
            "identity_proof_old" => strlen($identity_proof)?$identity_proof:$this->input->post("identity_proof_old"),
            "land_revenue_receipt_old" => strlen($land_revenue_receipt)?$land_revenue_receipt:$this->input->post("land_revenue_receipt_old"),
            "land_revenue_receipt_old" => strlen($land_revenue_receipt)?$land_revenue_receipt:$this->input->post("land_revenue_receipt_old"),
            "salary_slip_old" => strlen($salary_slip)?$salary_slip:$this->input->post("salary_slip_old"),
            "other_doc_old" => strlen($other_doc)?$other_doc:$this->input->post("other_doc_old"),
            "soft_copy_old" => strlen($soft_copy)?$soft_copy:$this->input->post("soft_copy_old")
           // "passport_photo_old" => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
           // "proof_of_retirement_old" => strlen($proof_of_retirement) ? $proof_of_retirement : $this->input->post("proof_of_retirement_old"),
           // "age_proof_old" => strlen($age_proof) ? $age_proof : $this->input->post("age_proof_old"),
           // "address_proof_old" => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
           // "other_doc_old" => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
            //"soft_copy_old" => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($obj);
        } else {

            $data = array(
                'form_data.address_proof_type' => $this->input->post("address_proof_type"),
                'form_data.identity_proof_type' => $this->input->post("identity_proof_type"),
                'form_data.land_revenue_receipt_type' => $this->input->post("land_revenue_receipt_type"),
                'form_data.salary_slip_type' => $this->input->post("salary_slip_type"),
                'form_data.other_doc_type' => $this->input->post("other_doc_type"), 
             //   'form_data.passport_photo_type' => $this->input->post("passport_photo_type"),
              //  'form_data.proof_of_retirement_type' => $this->input->post("proof_of_retirement_type"),
             //   'form_data.age_proof_type' => $this->input->post("age_proof_type"),
              //  'form_data.address_proof_type' => $this->input->post("address_proof_type"),
              //  'form_data.other_doc_type' => $this->input->post("other_doc_type"),
                'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),
                'form_data.address_proof' => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
                'form_data.identity_proof' => strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old"),
                'form_data.land_revenue_receipt' => strlen($land_revenue_receipt) ? $land_revenue_receipt : $this->input->post("land_revenue_receipt_old"),
                'form_data.salary_slip' => strlen($salary_slip) ? $salary_slip : $this->input->post("salary_slip_old"),
                'form_data.other_doc' => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
                'form_data.soft_copy' => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
            );
            $this->income_registration_model->update_where(['_id' => new ObjectId($obj)], $data);
            $this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/incomecertificate/registration1/preview/'.$obj);
        } //End of if else
    } //End of submitfiles()


    public function preview($obj=null) {
        
        $dbRow = $this->income_registration_model->get_by_doc_id($obj);
       // pre($dbRow);
        if(count((array)$dbRow)) {
            $data=array(
                "service_name"=>$this->serviceName,
                "dbrow"=>$dbRow,
                "user_type"=> $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('incomecertificate/incomecertificatepreview_view',$data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error','No records found against object id : '.$obj);
            redirect('spservices/incomecertificate');
        }//End of if else
    }//End of preview()
   // public function preview($obj = null)
   // {

      //  $dbRow = $this->income_registration_model->get_by_doc_id($obj);
        //var_dump($dbRow); die;
       // if (count((array)$dbRow)) {
         //   $data = array(
           //     "service_name" => $this->serviceName,
           //     "dbrow" => $dbRow,
            //    "user_type" => $this->slug
           // );
           // $this->load->view('includes/frontend/header');
            //$this->load->view('nec/necertificatepreview_view',$data);
           // $this->load->view('seniorcitizen/sccertificatepreview_view', $data);
           // $this->load->view('includes/frontend/footer');
       // } else {
         //   $this->session->set_flashdata('error', 'No records found against object id : ' . $obj);
          //  redirect('spservices/necertificate/');
      //  } //End of if else
   // } //End of preview()

    function createcaptcha()
    {
        $captchaDir = "storage/captcha/";
        array_map('unlink', glob("$captchaDir*.jpg"));

        $this->load->helper('captcha');
        $config = array(
            'img_path' => './storage/captcha/',
            'img_url' => base_url('storage/captcha/'),
            'font_path' => APPPATH . 'sys/fonts/texb.ttf',
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
    } //End of createcaptcha()


    public function track($obj = null)
    {
        $dbRow = $this->income_registration_model->get_by_doc_id($obj);
        if (count((array)$dbRow)) {
            $data = array(
                "service_data.service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('incomecertificate/incometrack_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $obj);
            redirect('spservices/necertificate/');
        } //End of if else
    } //End of track()


    function getID($length)
    {
        $rtps_trans_id = $this->generateID($length);
        while ($this->income_registration_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
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
        $str = "RTPS-INC/" . $date . "/" . $number;
        return $str;
    } //End of generateID()
    public function query($obj = null)
    {
        $filter = array("_id" => new ObjectId($obj));
        $data["dbrow"] = $this->income_registration_model->get_row($filter);
        $data['serial_registration_not_availabe'] = (property_exists($data["dbrow"], 'serial_registration_not_availabe') && $data["dbrow"]->serial_registration_not_availabe === "1") ? true : false;
        $remarkds = isset($data["dbrow"]->normal_query->wsResponse) ? json_decode($data["dbrow"]->normal_query->wsResponse) : array();
        $data['remark'] = isset($remarkds->remark) ? $remarkds->remark : '';
        $data["sro_dist_list"] = $this->income_registration_model->sro_dist_list();
        // pre( $dbRow);
        if ($data["dbrow"]) {
            $this->load->view('includes/frontend/header');
            $this->load->view('deed/query_perview', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $obj);
            redirect('spservices/registereddeed/');
        } //End of if else
    } //End of query()


    public function querysubmit()
    {
        $obj = $this->input->post("obj_id");
        $applId = $this->input->post("applId");
        $rtps_trans_id = $this->input->post("rtps_trans_id");
        $this->form_validation->set_rules('applicant_name', 'Name', 'trim|required|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean|strip_tags');

        // $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('relation', 'Relation', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->query($obj);
        } else {

            // $inputCaptcha = $this->input->post("inputcaptcha");
            // $sessCaptcha = $this->session->userdata('captchaCode');

            // if($sessCaptcha !== $inputCaptcha) {
            //     $this->session->set_flashdata("error", "Captcha does not mached!. Please try again");
            //     $this->query($obj);
            //     return;
            // }

            $attachment1 = cifileupload("attachment1");
            $attachment_1 = $attachment1["upload_status"] ? $attachment1["uploaded_path"] : null;

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

            $res = $this->income_registration_model->update($obj, $data_to_update);
            if ($res->getMatchedCount()) {
                $dbRow = $this->income_registration_model->get_by_doc_id($obj);
                // var_dump(FCPATH);
                // var_dump("Echhh    " );
                // var_dump($attachment_1);die;
                //  $landPatta = $dbrow->land_patta?base64_encode(file_get_contents(FCPATH.$dbrow->land_patta)):null;

                if (property_exists($dbRow, "serial_registration_not_availabe") && $dbRow->serial_registration_not_availabe === "1") {
                    $deedno = "Year From: " . $this->input->post("year_from") . ",Year To: " . $this->input->post("year_to") . ",Party Name: " . $this->input->post("deed_party_name") . ",Patta No: " . $this->input->post("deed_patta_no") . ",Daag No: " . $this->input->post("deed_dag_no") . ",Land Area: " . $this->input->post("deed_total_land_area");
                } else {
                    $deedno = "SL No: " . $this->input->post("deedno") . ",Reg Year: " . $this->input->post("year_of_registration");
                }
                $postdata = array(
                    "Ref" => array(
                        "applicant_name" => $this->input->post("applicant_name"),
                        "deed_no" => $deedno,
                        "application_status" => $dbRow->status,
                        //  "deed_date"=>"EGRAS Assam",
                        "address" => $this->input->post("address"),
                        "relation" => $this->input->post("relation"),
                        "mobile" => $this->input->post("mobile"),
                        "email" => $this->input->post("email"),
                        "application_ref_no" => $rtps_trans_id,
                        "attachment1" => $attachment_1  ? base64_encode(file_get_contents(FCPATH . $attachment_1)) : null,
                    ),
                    "spId" => array(
                        "applId" => $applId
                    )
                );
                 pre($postdata);
                $url = $this->config->item('url');
                $curl = curl_init($url . "cercpy/fee_paid_status.php");
                // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $res = curl_exec($curl);


                curl_close($curl);

                if ($res) {
                    $res = json_decode($res);

                    if ($res->ref->status === "success") {
                        $this->income_registration_model->update_row(
                            array('rtps_trans_id' => $rtps_trans_id),
                            array(
                                "qs_updated_on_backend" => true,
                                "status" => 'QA'
                            )
                        );


                        //procesing data

                        $processing_history = $dbRow->processing_history ?? array();
                        $processing_history[] = array(
                            "processed_by" => "Query Replied by " . $dbRow->applicant_name,
                            "action_taken" => "Query Replied By APPLICANT",
                            "remarks" => "Query Replied by " . $dbRow->applicant_name,
                            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        );
                    }
                    $this->session->set_flashdata('message', 'Query response submitted successfully');
                    redirect('iservices/transactions');
                }
            } else {
                $this->session->set_flashdata('error', 'Something went wrong. Please try again');
            }

            redirect('spservices/registereddeed/query/' . $obj);
        } //End of if else        
    } //End of querysubmit()

    public function get_age()
    {
        $dob = $this->input->post("dob");
        if (strlen($dob) == 10) {
            $date_of_birth = new DateTime($dob);
            $nowTime = new DateTime();
            $interval = $date_of_birth->diff($nowTime);
            echo $interval->format('%y Years %m Months and %d Days');
        } else {
            echo "Invalid date format";
        } //End of if else
    } //End of get_age()
}//End of Castecertificate