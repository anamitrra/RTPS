<?php
//Author: Sayeed Akhtar
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Pdbr extends Rtps
{
    private $serviceName = "Permission for Delayed Birth Registration";
    private $serviceId = "PDBR";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('delayedbirth/delayed_birth_model');
        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->helper('smsprovider');
        $this->load->helper("log");
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
    } //End of __construct()

    public function index($obj_id = null)
    {
        check_application_count_for_citizen();
        $data = array("pageTitle" => "Permission for Delayed Birth Registration");
        $filter = array("_id" => new ObjectId($obj_id), "service_data.appl_status" => "DRAFT");
        $data["dbrow"] = $this->delayed_birth_model->get_row($filter);
        $data['usser_type'] = $this->slug;

        $this->load->view('includes/frontend/header');
        $this->load->view('delayedbirth/delayed_birth', $data);
        $this->load->view('includes/frontend/footer');
    } //End of index()

    public function submit()
    {
        $objId = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");

        $this->form_validation->set_rules('applicant_name', 'Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('mobile', 'Contact number', 'trim|integer|required|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('email', 'Email id', 'valid_email|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('pan_no', 'PAN no', 'trim|xss_clean|strip_tags|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]');
        $this->form_validation->set_rules('aadhar_no', 'Aadhar no', 'trim|integer|exact_length[12]|xss_clean|strip_tags');
        $this->form_validation->set_rules('newborn_relation', 'New born relation', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        if ($this->input->post("newborn_relation") == "Other") {
            $this->form_validation->set_rules('other_relation', 'Other relation', 'trim|required|xss_clean|strip_tags');
        }
        $this->form_validation->set_rules('newborn_name', 'Newborn name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('dob', 'DOB', 'trim|required|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('newborn_gender', 'Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('father_name', 'Father name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('mother_name', 'Mother name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('newborn_birthplace', 'Birthplace', 'trim|required|xss_clean|strip_tags');
        if ($this->input->post("newborn_birthplace") == "Hospital") {
            $this->form_validation->set_rules('hospital_name', 'Hospital Name', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('address_hospital', 'Hospital Address', 'trim|required|xss_clean|strip_tags');
        } else if ($this->input->post("newborn_birthplace") == "House") {
            $this->form_validation->set_rules('address_hospital', 'Home Address', 'trim|required|xss_clean|strip_tags');
        } else if ($this->input->post("newborn_birthplace") == "Other") {
            $this->form_validation->set_rules('other_placeofbirth', 'Other place of birth', 'trim|required|xss_clean|strip_tags');
        }

        $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('subdivision', 'Sub-division', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('revenuecircle', 'Revenue circle', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('village', 'Village', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pin_code', 'PIN code', 'trim|required|integer|exact_length[6]|xss_clean|strip_tags');

        // $this->form_validation->set_rules('inputcaptcha', 'Captcha', 'required|exact_length[6]');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
        } else {

            $appl_ref_no = $this->getID(7);
            $sessionUser = $this->session->userdata();

            if ($this->slug === "CSC") {
                $apply_by = $sessionUser['userId'];
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            } //End of if else

            while (1) {
                $app_id = rand(100000000, 999999999);
                $filter = array(
                    "service_data.appl_id" => $app_id,
                    "service_data.service_id" => $this->serviceId
                );
                $rows = $this->delayed_birth_model->get_row($filter);

                if ($rows == false)
                    break;
            }

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
                "district" => explode("/", $this->input->post("district"))[0],
                "submission_date" => "",
                "service_timeline" => "10 Days",
                "appl_status" => "DRAFT",
            ];

            $form_data = [
                'user_id' => $sessionUser['userId']->{'$id'},
                'user_type' => $this->slug,

                'applicant_name' => $this->input->post("applicant_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'mobile' => $this->input->post("mobile"),
                'email' => $this->input->post("email"),
                'pan_no' => $this->input->post("pan_no"),
                'aadhar_no' => $this->input->post("aadhar_no"),
                'newborn_relation' => $this->input->post("newborn_relation"),
                'other_relation' => $this->input->post("other_relation"),

                'newborn_name' => $this->input->post("newborn_name"),
                'dob' => $this->input->post("dob"),
                'newborn_gender' => $this->input->post("newborn_gender"),
                'father_name' => $this->input->post("father_name"),
                'mother_name' => $this->input->post("mother_name"),
                'newborn_birthplace' => $this->input->post("newborn_birthplace"),
                'hospital_name' => trim($this->input->post("hospital_name")),
                'address_hospital' => trim($this->input->post("address_hospital")),
                'other_placeofbirth' => trim($this->input->post("other_placeofbirth")),
                'late_reason' => trim($this->input->post("late_reason")),

                'state' => $this->input->post("state"),
                'district' => explode("/", $this->input->post("district"))[0],
                'district_id' => explode("/", $this->input->post("district"))[1],
                'subdivision' => explode("/", $this->input->post("subdivision"))[0],
                'subdivision_id' => explode("/", $this->input->post("subdivision"))[1],
                'revenuecircle' => explode("/", $this->input->post("revenuecircle"))[0],
                'revenuecircle_id' => explode("/", $this->input->post("revenuecircle"))[1],
                'village' => $this->input->post("village"),
                'pin_code' => $this->input->post("pin_code"),
                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];

            if (strlen($objId)) {
                $form_data["age_proof_type"] = $this->input->post("age_proof_type");
                $form_data["age_proof"] = $this->input->post("age_proof");
                $form_data["address_proof_type"] = $this->input->post("address_proof_type");
                $form_data["address_proof"] = $this->input->post("address_proof");
                $form_data["affidavit_type"] = $this->input->post("affidavit_type");
                $form_data["affidavit"] = $this->input->post("affidavit");
                // $form_data["other_doc_type"] = $this->input->post("other_doc_type");
                // $form_data["other_doc"] = $this->input->post("other_doc");
                if (!empty($this->input->post("other_doc_type"))) {
                    $form_data["other_doc_type"] = $this->input->post("other_doc_type");
                    $form_data["other_doc"] = $this->input->post("other_doc");
                }
                $form_data["soft_copy_type"] = $this->input->post("soft_copy_type");
                $form_data["soft_copy"] = $this->input->post("soft_copy");
            }

            $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data
            ];

            if (strlen($objId)) {
                $this->delayed_birth_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/delayed-birth-registration/fileuploads/' . $objId);
            } else {
                $insert = $this->delayed_birth_model->insert($inputs);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    redirect('spservices/delayed-birth-registration/fileuploads/' . $objectId);
                    exit();
                } else {
                    $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                    $this->index();
                } //End of if else
            } //End of if else

        } //End of if else
    } //End of submit()

    public function fileuploads($objId = null)
    {
        $dbRow = $this->delayed_birth_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "pageTitle" => "Attached Enclosures for " . $this->serviceName,
                "obj_id" => $objId,
                "dbrow" => $dbRow
            );
            $this->load->view('includes/frontend/header');
            //$this->load->view('nec/necertificateuploads_view',$data);
            $this->load->view('delayedbirth/pdbrcertificateuploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/delayedbirth/pdbr/');
        } //End of if else
    } //End of fileuploads()

    public function submitfiles_old()
    {
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }

        $this->form_validation->set_rules('affidavit_type', 'Affidavit', 'required');
        $this->form_validation->set_rules('age_proof_type', 'Age proof', 'required');
        $this->form_validation->set_rules('address_proof_type', 'Address proof', 'required');
        //$this->form_validation->set_rules('other_doc_type', 'Other doc');

        if (empty($this->input->post("other_doc_old"))) {
            if ((empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] != ""))) {
                $this->form_validation->set_rules('other_doc_type', 'Other document Type', 'required');
            }

            if ((!empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] == ""))) {
                $this->form_validation->set_rules('other_doc', 'Other document', 'required');
            }
        }

        // if (empty($this->input->post("soft_copy_old")) && $this->slug !== 'user') {
        //     if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {

        //         $this->form_validation->set_rules('soft_copy_type', 'Soft Copy Type required', 'required');
        //         $this->form_validation->set_rules('soft_copy', 'Soft Copy document required', 'required');
        //     }
        // }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $affidavitUpload = cifileupload("affidavit");
        $affidavit = $affidavitUpload["upload_status"] ? $affidavitUpload["uploaded_path"] : null;

        $ageProof = cifileupload("age_proof");
        $age_proof = $ageProof["upload_status"] ? $ageProof["uploaded_path"] : null;

        $addressProof = cifileupload("address_proof");
        $address_proof = $addressProof["upload_status"] ? $addressProof["uploaded_path"] : null;

        $otherDoc = cifileupload("other_doc");
        $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;

        $softCopy = cifileupload("soft_copy");
        $soft_copy = $softCopy["upload_status"] ? $softCopy["uploaded_path"] : null;

        $uploadedFiles = array(
            "affidavit_old" => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
            "age_proof_old" => strlen($age_proof) ? $age_proof : $this->input->post("age_proof_old"),
            "address_proof_old" => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
            "other_doc_old" => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
            "soft_copy_old" => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.affidavit_type' => $this->input->post("affidavit_type"),
                'form_data.age_proof_type' => $this->input->post("age_proof_type"),
                'form_data.address_proof_type' => $this->input->post("address_proof_type"),
                //'form_data.other_doc_type' => $this->input->post("other_doc_type"),
                'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),

                'form_data.affidavit' => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
                'form_data.age_proof' => strlen($age_proof) ? $age_proof : $this->input->post("age_proof_old"),
                'form_data.address_proof' => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
                //'form_data.other_doc' => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
                'form_data.soft_copy' => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
            );


            //pre($this->input->post("other_doc_type"));
            if (!empty($this->input->post("other_doc_type"))) {
                //pre($this->input->post("other_doc_type"));
                $data["form_data.other_doc_type"] = $this->input->post("other_doc_type");
                $data["form_data.other_doc"] = strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old");
            }

            $this->delayed_birth_model->update_where(['_id' => new ObjectId($objId)], $data);
            //$this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/delayedbirth/pdbr/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()

    public function submitfiles()
    {
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }

        $this->form_validation->set_rules('affidavit_type', 'Affidavit', 'required');
        $this->form_validation->set_rules('age_proof_type', 'Age proof', 'required');
        $this->form_validation->set_rules('address_proof_type', 'Address proof', 'required');

        if (empty($this->input->post("other_doc_old"))) {
            if ((empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] != "") || (!empty($this->input->post("other_doc_temp"))))) {
                $this->form_validation->set_rules('other_doc_type', 'Other document Type', 'required');
            }

            if ((!empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] == "") && (empty($this->input->post("other_doc_temp"))))) {
                $this->form_validation->set_rules('other_doc', 'Other document', 'required');
            }
        }

        if (empty($this->input->post("address_proof_old"))) {
            if ((empty($this->input->post("address_proof_temp"))) && (($_FILES['address_proof']['name'] == ""))) {
                $this->form_validation->set_rules('address_proof', 'Address Proof File', 'trim|required|xss_clean|strip_tags');
            }
        }

        if (empty($this->input->post("age_proof_old"))) {
            if ((empty($this->input->post("age_proof_temp"))) && (($_FILES['age_proof']['name'] == ""))) {
                $this->form_validation->set_rules('age_proof', 'Age Proof File', 'trim|required|xss_clean|strip_tags');
            }
        }

        if (empty($this->input->post("affidavit_old"))) {
            if ((empty($this->input->post("affidavit_temp"))) && (($_FILES['affidavit']['name'] == ""))) {
                $this->form_validation->set_rules('affidavit', 'Affidavit document', 'trim|required|xss_clean|strip_tags');
            }
        }


        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $affidavit = "";
        if ($_FILES['affidavit']['name'] != "") {
            $affidavitUpload = cifileupload("affidavit");
            $affidavit = $affidavitUpload["upload_status"] ? $affidavitUpload["uploaded_path"] : null;
        }
        if (!empty($this->input->post("affidavit_temp"))) {
            $affidavitUpload = movedigilockerfile($this->input->post('affidavit_temp'));
            $affidavit = $affidavitUpload["upload_status"] ? $affidavitUpload["uploaded_path"] : null;
        }

        $age_proof = "";
        if ($_FILES['age_proof']['name'] != "") {
            $ageProof = cifileupload("age_proof");
            $age_proof = $ageProof["upload_status"] ? $ageProof["uploaded_path"] : null;
        }
        if (!empty($this->input->post("age_proof_temp"))) {
            $ageProof = movedigilockerfile($this->input->post('age_proof_temp'));
            $age_proof = $ageProof["upload_status"] ? $ageProof["uploaded_path"] : null;
        }

        $address_proof = "";
        if ($_FILES['address_proof']['name'] != "") {
            $addressProof = cifileupload("address_proof");
            $address_proof = $addressProof["upload_status"] ? $addressProof["uploaded_path"] : null;
        }
        if (!empty($this->input->post("address_proof_temp"))) {
            $addressProof = movedigilockerfile($this->input->post('address_proof_temp'));
            $address_proof = $addressProof["upload_status"] ? $addressProof["uploaded_path"] : null;
        }

        $other_doc = "";
        if ($_FILES['other_doc']['name'] != "") {
            $otherDoc = cifileupload("other_doc");
            $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;
        }
        if (!empty($this->input->post("other_doc_temp"))) {
            $otherDoc = movedigilockerfile($this->input->post('other_doc_temp'));
            $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;
        }

        $uploadedFiles = array(
            "affidavit_old" => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
            "age_proof_old" => strlen($age_proof) ? $age_proof : $this->input->post("age_proof_old"),
            "address_proof_old" => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
            "other_doc_old" => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.affidavit_type' => $this->input->post("affidavit_type"),
                'form_data.age_proof_type' => $this->input->post("age_proof_type"),
                'form_data.address_proof_type' => $this->input->post("address_proof_type"),

                'form_data.affidavit' => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
                'form_data.age_proof' => strlen($age_proof) ? $age_proof : $this->input->post("age_proof_old"),
                'form_data.address_proof' => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
            );

            //pre($this->input->post("other_doc_type"));
            if (!empty($this->input->post("other_doc_type"))) {
                $data["form_data.other_doc_type"] = $this->input->post("other_doc_type");
                $data["form_data.other_doc"] = strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old");
            }

            $this->delayed_birth_model->update_where(['_id' => new ObjectId($objId)], $data);
            //$this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/delayedbirth/pdbr/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()

    public function preview($objId = null)
    {
        $dbRow = $this->delayed_birth_model->get_by_doc_id($objId);
        //var_dump($dbRow); die;
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('delayedbirth/pdbrcertificatepreview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/delayedbirth/pdbr/');
        } //End of if else
    } //End of preview()

    public function finalsubmition($obj = null)
    {
        //$obj = $this->input->post('obj');
        if (empty($obj)) {
            $this->my_transactions();
        }

        if ($obj) {
            $dbRow = $this->delayed_birth_model->get_by_doc_id($obj);
            if ($dbRow->service_data->appl_status == "payment_initiated" && !empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")) {

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
                    'applicantName' => $dbRow->form_data->applicant_name,
                    'applicantGender' => $dbRow->form_data->applicant_gender,
                    'applicantMobileNo' => $dbRow->form_data->mobile,
                    'emailId' => $dbRow->form_data->email,
                    'relationWithNewBorn' => $dbRow->form_data->newborn_relation,
                    'panNo' => $dbRow->form_data->pan_no,
                    'aadharNo' => $dbRow->form_data->aadhar_no,
                    'newBornName' => $dbRow->form_data->newborn_name,
                    'dateofBirth' => $dbRow->form_data->dob,
                    'newBornGender' => $dbRow->form_data->newborn_gender,
                    'fathersName' => $dbRow->form_data->father_name,
                    'mothersName' => $dbRow->form_data->mother_name,
                    'placeofBirth' => $dbRow->form_data->newborn_birthplace,
                    'reasonForLate' => $dbRow->form_data->late_reason,
                    'state' => $dbRow->form_data->state,
                    'district' => $dbRow->form_data->district,
                    'subDivision' => $dbRow->form_data->subdivision,
                    'circleOffice' => $dbRow->form_data->revenuecircle,
                    'newBornVillageorTown' => $dbRow->form_data->village,
                    'newBornPin' => $dbRow->form_data->pin_code,
                    'cscid' => "RTPS1234",
                    'fillUpLanguage' => "English",
                    'service_type' => "PDBR",
                    'cscoffice' => "NA",
                    'spId' => array('applId' => $dbRow->service_data->appl_id)
                );


                if (!empty($dbRow->form_data->other_relation))
                    $postdata['relationWithNewBornOther'] = $dbRow->form_data->other_relation;

                if ($dbRow->form_data->newborn_birthplace == "Hospital") {
                    $postdata['nameOfHospital'] = $dbRow->form_data->hospital_name;
                    $postdata['addressHospital'] = $dbRow->form_data->address_hospital;
                } else if ($dbRow->form_data->newborn_birthplace == "House") {
                    $postdata['addressHospital'] = $dbRow->form_data->address_hospital;
                } else if ($dbRow->form_data->newborn_birthplace == "Other") {
                    $postdata['placeofBirthOther'] = $dbRow->form_data->other_placeofbirth;
                }


                if (!empty($dbRow->form_data->age_proof)) {
                    $age_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->age_proof));

                    $attachment_one = array(
                        "encl" =>  $age_proof,
                        "docType" => "application/pdf",
                        "enclFor" => "Register Hospital Govt. / Pvt. Certificate regarding Birth or Age Proof (any)",
                        "enclType" => $dbRow->form_data->age_proof_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7503",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentOne'] = $attachment_one;
                }
                if (!empty($dbRow->form_data->address_proof)) {
                    $address_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->address_proof));

                    $attachment_two = array(
                        "encl" =>  $address_proof,
                        "docType" => "application/pdf",
                        "enclFor" => "School Certificate/Admit Card (for age 6 and above) or parent's details",
                        "enclType" => $dbRow->form_data->address_proof_type,
                        "id" => "65441674",
                        "doctypecode" => "7504",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentTwo'] = $attachment_two;
                }
                if (!empty($dbRow->form_data->affidavit)) {
                    $affidavit = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->affidavit));

                    $attachment_three = array(
                        "encl" =>  $affidavit,
                        "docType" => "application/pdf",
                        "enclFor" => "Affidavit",
                        "enclType" => $dbRow->form_data->affidavit_type,
                        "id" => "65441672",
                        "doctypecode" => "7502",
                        "docRefId" => "7502",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentThree'] = $attachment_three;
                }

                if (!empty($dbRow->form_data->other_doc)) {
                    $other_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->other_doc));

                    $attachment_four = array(
                        "encl" =>  $other_doc,
                        "docType" => "application/pdf",
                        "enclFor" => "Any other document",
                        "enclType" => $dbRow->form_data->other_doc_type,
                        "id" => "65441675",
                        "doctypecode" => "7505",
                        "docRefId" => "7505",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentFour'] = $attachment_four;
                }
                // if (!empty($dbRow->form_data->soft_copy)) {
                //     $soft_copy = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->soft_copy));

                //     $attachment_zero = array(
                //         "encl" =>  $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload Scanned Copy of the Application Form",
                //         "enclType" => $dbRow->form_data->soft_copy_type,
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );

                //     $postdata['AttachmentZero'] = $attachment_zero;
                // }

                $url = $this->config->item('delayed_birth_url');
                $curl = curl_init($url);


                // $json = json_encode($postdata);
                // $buffer = preg_replace("/\r|\n/", "", $json);
                // $myfile = fopen("D:\\TESTDATA\\PDBR.txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $buffer);
                // fclose($myfile);
                //die;
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                curl_close($curl);
                log_response($dbRow->service_data->appl_ref_no, $response);
                if ($response) {
                    $response = json_decode($response);
                    if ($response->ref->status === "success") {
                        $data_to_update = array(
                            'service_data.appl_status' => 'submitted',
                            'form_data.edistrict_ref_no' => $response->ref->edistrict_ref_no,
                            'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            'processing_history' => $processing_history
                        );
                        $this->delayed_birth_model->update($obj, $data_to_update);

                        //Sending submission SMS
                        $nowTime = date('Y-m-d H:i:s');
                        $sms = array(
                            "mobile" => (int)$dbRow->form_data->mobile,
                            "applicant_name" => $dbRow->form_data->applicant_name,
                            "service_name" => 'PermissionFor DelayedBirth Reg',
                            "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                            "app_ref_no" => $dbRow->service_data->appl_ref_no
                        );
                        sms_provider("submission", $sms);

                        // return $this->output
                        //     ->set_content_type('application/json')
                        //     ->set_status_header(200)
                        //     ->set_output(json_encode(array("status" => true)));
                        redirect('spservices/applications/acknowledgement/' . $obj);
                    } else {
                        // return $this->output
                        //     ->set_content_type('application/json')
                        //     ->set_status_header(401)
                        //     ->set_output(json_encode(array("status" => false)));
                        $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                        $this->my_transactions();
                    }
                }
            } else {
                $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                $this->my_transactions();
            }
        }
    }

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

    public function track($objId = null)
    {
        $dbRow = $this->delayed_birth_model->get_by_doc_id($objId);
        if(isset($dbRow->form_data->edistrict_ref_no ) && !empty($dbRow->form_data->edistrict_ref_no )){
            $this->load->helper('trackstatus');
            fetchEdistrictData($dbRow->form_data->edistrict_ref_no);
            $dbRow = $this->delayed_birth_model->get_by_doc_id($objId);
        }
        if (count((array)$dbRow)) {
            $data = array(
                "service_data.service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('delayedbirth/pdbrcertificatetrack_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/delayedbirth/pdbr');
        } //End of if else
    } //End of track()

    function getID($length)
    {
        $rtps_trans_id = $this->generateID($length);
        while ($this->delayed_birth_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
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
        $str = "RTPS-PDBR/" . $date . "/" . $number;
        return $str;
    } //End of generateID()
    public function queryform($objId = null)
    {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->delayed_birth_model->get_row(array("_id" => new ObjectId($objId), "service_data.appl_status" => "QS"));
            if ($dbRow) {
                $data = array(
                    "service_data.service_name" => $this->serviceName,
                    "dbrow" => $dbRow
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('delayedbirth/delayedbirthquery_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/delayedbirth/pdbr');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/delayedbirth/pdbr');
        } //End of if else
    } //End of query()

    public function querysubmit()
    {
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");
        $this->form_validation->set_rules('affidavit_type', 'Affidavit', 'required');
        $this->form_validation->set_rules('age_proof_type', 'Age proof', 'required');
        $this->form_validation->set_rules('address_proof_type', 'Address proof', 'required');
        $this->form_validation->set_rules('appl_ref_no', 'Application Ref No.', 'trim|xss_clean|strip_tags|max_length[255]');

        if (empty($this->input->post("other_doc_old"))) {
            if ((empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] != ""))) {
                $this->form_validation->set_rules('other_doc_type', 'Others Type', 'required');
            }

            if ((!empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] == ""))) {
                $this->form_validation->set_rules('other_doc', 'Others', 'required');
            }
        }


        // if (empty($this->input->post("soft_copy_old")) && $this->slug !== 'user') {
        //     if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {

        //         $this->form_validation->set_rules('soft_copy_type', 'Soft Copy Type required', 'required');
        //         $this->form_validation->set_rules('soft_copy', 'Soft Copy document required', 'required');
        //     }
        // }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");


        $affidavitUpload = cifileupload("affidavit");
        $affidavit = $affidavitUpload["upload_status"] ? $affidavitUpload["uploaded_path"] : $this->input->post("affidavit_old");

        $ageProof = cifileupload("age_proof");
        $age_proof = $ageProof["upload_status"] ? $ageProof["uploaded_path"] : $this->input->post("age_proof_old");

        $addressProof = cifileupload("address_proof");
        $address_proof = $addressProof["upload_status"] ? $addressProof["uploaded_path"] : $this->input->post("address_proof_old");

        $otherDoc = cifileupload("other_doc");
        $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : $this->input->post("other_doc_old");

        $softCopy = cifileupload("soft_copy");
        $soft_copy = $softCopy["upload_status"] ? $softCopy["uploaded_path"] : $this->input->post("soft_copy_old");

        $uploadedFiles = array(
            "affidavit_old" => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
            "age_proof_old" => strlen($age_proof) ? $age_proof : $this->input->post("age_proof_old"),
            "address_proof_old" => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
            "other_doc_old" => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
            "soft_copy_old" => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->queryform($objId);
        } else {
            $dbRow = $this->delayed_birth_model->get_by_doc_id($objId);
            //  pre($dbRow);
            //  exit();
            if (count((array)$dbRow)) {
                $data = array(
                    'form_data.affidavit_type' => $this->input->post("affidavit_type"),
                    'form_data.age_proof_type' => $this->input->post("age_proof_type"),
                    'form_data.address_proof_type' => $this->input->post("address_proof_type"),
                    //'form_data.other_doc_type' => $this->input->post("other_doc_type"),
                    'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),
                    'form_data.affidavit' => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
                    'form_data.age_proof' => strlen($age_proof) ? $age_proof : $this->input->post("age_proof_old"),
                    'form_data.address_proof' => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
                    //'form_data.other_doc' => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
                    'form_data.soft_copy' => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
                );



                if (!empty($this->input->post("other_doc_type"))) {
                    //pre($this->input->post("other_doc_type"));
                    $data["form_data.other_doc_type"] = $this->input->post("other_doc_type");
                    $data["form_data.other_doc"] = strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old");
                }



                $this->delayed_birth_model->update_where(['_id' => new ObjectId($objId)], $data);

                $postdata = array(
                    'application_ref_no' => $dbRow->service_data->appl_ref_no,
                    'applicantName' => $dbRow->form_data->applicant_name,
                    'applicantGender' => $dbRow->form_data->applicant_gender,
                    'applicantMobileNo' => $dbRow->form_data->mobile,
                    'emailId' => $dbRow->form_data->email,
                    'relationWithNewBorn' => $dbRow->form_data->newborn_relation,
                    'panNo' => $dbRow->form_data->pan_no,
                    'aadharNo' => $dbRow->form_data->aadhar_no,
                    'newBornName' => $dbRow->form_data->newborn_name,
                    'dateofBirth' => $dbRow->form_data->dob,
                    'newBornGender' => $dbRow->form_data->newborn_gender,
                    'fathersName' => $dbRow->form_data->father_name,
                    'mothersName' => $dbRow->form_data->mother_name,
                    'placeofBirth' => $dbRow->form_data->newborn_birthplace,
                    'reasonForLate' => $dbRow->form_data->late_reason,
                    'state' => $dbRow->form_data->state,
                    'district' => $dbRow->form_data->district,
                    'subDivision' => $dbRow->form_data->subdivision,
                    'circleOffice' => $dbRow->form_data->revenuecircle,
                    'newBornVillageorTown' => $dbRow->form_data->village,
                    'newBornPin' => $dbRow->form_data->pin_code,
                    'cscid' => "RTPS1234",
                    'fillUpLanguage' => "English",
                    'service_type' => "PDBR",
                    'cscoffice' => "NA",
                    'revert' => "NA",
                    'spId' => array('applId' => $dbRow->service_data->appl_id)
                );

                if (!empty($dbRow->form_data->other_relation))
                    $postdata['relationWithNewBornOther'] = $dbRow->form_data->other_relation;
                if ($dbRow->form_data->newborn_birthplace == "Hospital") {
                    $postdata['nameOfHospital'] = $dbRow->form_data->hospital_name;
                    $postdata['addressHospital'] = $dbRow->form_data->address_hospital;
                } else if ($dbRow->form_data->newborn_birthplace == "House") {
                    $postdata['addressHospital'] = $dbRow->form_data->address_hospital;
                } else if ($dbRow->form_data->newborn_birthplace == "Other") {
                    $postdata['placeofBirthOther'] = $dbRow->form_data->other_placeofbirth;
                }


                if (strlen($age_proof)) {
                    $age_proof_type = (!empty($this->input->post("age_proof_type"))) ? $this->input->post("age_proof_type") : $dbRow->form_data->age_proof_type;
                    $age_proof = strlen($age_proof) ? base64_encode(file_get_contents(FCPATH . $age_proof)) : null;

                    $attachment_one = array(
                        "encl" =>  $age_proof,
                        "docType" => "application/pdf",
                        "enclFor" => "Register Hospital Govt. / Pvt. Certificate regarding Birth or Age Proof (any)",
                        "enclType" => $age_proof_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7503",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentOne'] = $attachment_one;
                }

                if (strlen($address_proof)) {
                    $address_proof_type = (!empty($this->input->post("address_proof_type"))) ? $this->input->post("address_proof_type") : $dbRow->form_data->address_proof_type;
                    $address_proof = strlen($address_proof) ? base64_encode(file_get_contents(FCPATH . $address_proof)) : null;

                    $attachment_two = array(
                        "encl" =>  $address_proof,
                        "docType" => "application/pdf",
                        "enclFor" => "School Certificate/Admit Card (for age 6 and above) or parent's details",
                        "enclType" => $address_proof_type,
                        "id" => "65441674",
                        "doctypecode" => "7504",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentTwo'] = $attachment_two;
                }

                if (strlen($affidavit)) {
                    $affidavit_type = (!empty($this->input->post("affidavit_type"))) ? $this->input->post("affidavit_type") : $dbRow->form_data->affidavit_type;
                    $affidavit = strlen($affidavit) ? base64_encode(file_get_contents(FCPATH . $affidavit)) : null;

                    $attachment_three = array(
                        "encl" =>  $affidavit,
                        "docType" => "application/pdf",
                        "enclFor" => "Affidavit",
                        "enclType" => $affidavit_type,
                        "id" => "65441672",
                        "doctypecode" => "7502",
                        "docRefId" => "7502",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentThree'] = $attachment_three;
                }

                if (strlen($other_doc)) {
                    $other_doc_type = (!empty($this->input->post("other_doc_type"))) ? $this->input->post("other_doc_type") : $dbRow->form_data->other_doc_type;
                    $other_doc = strlen($other_doc) ? base64_encode(file_get_contents(FCPATH . $other_doc)) : null;

                    $attachment_four = array(
                        "encl" =>  $other_doc,
                        "docType" => "application/pdf",
                        "enclFor" => "Any other document",
                        "enclType" => $other_doc_type,
                        "id" => "65441675",
                        "doctypecode" => "7505",
                        "docRefId" => "7505",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentFour'] = $attachment_four;
                }
                // if (strlen($soft_copy)) {
                //     $soft_copy_type = (!empty($this->input->post("soft_copy_type"))) ? $this->input->post("soft_copy_type") : $dbRow->form_data->soft_copy_type;
                //     $soft_copy = strlen($soft_copy) ? base64_encode(file_get_contents(FCPATH . $soft_copy)) : null;

                //     $attachment_zero = array(
                //         "encl" =>  $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload Scanned Copy of the Application Form",
                //         "enclType" => $soft_copy_type,
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );

                //     $postdata['AttachmentZero'] = $attachment_zero;
                // }

                // $json = json_encode($postdata);
                // $buffer = preg_replace( "/\r|\n/", "", $json );
                // $myfile = fopen("D:\\TESTDATA\\SCC_Query_Post.txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $buffer);
                // fclose($myfile);
                //  die;
                $json_obj = json_encode($postdata);
                $url = $this->config->item('delayed_birth_url');
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
                if (isset($error_msg)) {
                    die("CURL ERROR : " . $error_msg);
                } elseif ($response) {
                    $response = json_decode($response, true);  //pre($response);
                    if ($response["ref"]["status"] === "success") {
                        $processing_history = $dbRow->processing_history ?? array();
                        $processing_history[] = array(
                            "processed_by" => "Query submitted by " . $dbRow->form_data->applicant_name,
                            "action_taken" => "Query submitted",
                            "remarks" => "Query submitted by " . $dbRow->form_data->applicant_name,
                            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        );
                        $data = array(
                            "service_data.appl_status" => "QA",
                            'processing_history' => $processing_history
                        );
                        $this->delayed_birth_model->update_where(['_id' => new ObjectId($objId)], $data);
                        $this->session->set_flashdata('success', 'Your application has been successfully updated');
                        redirect('spservices/delayedbirth/pdbr/preview/' . $objId);
                    } else {
                        // return $this->output
                        //     ->set_content_type('application/json')
                        //     ->set_status_header(401)
                        //     ->set_output(json_encode(array("status" => false)));
                        $this->session->set_flashdata('fail', 'Unable to update data!!! Please try again.');
                        $this->queryform($objId);
                    } //End of if else
                } //End of if
            } else {
                $this->session->set_flashdata('fail', 'Unable to update data!!! Please try again.');
                $this->index();
            } //End of if else
        } //End of if else      
    } //End of querysubmit()

    public function submit_to_backend($obj, $show_ack = false)
    {
        if ($obj) {
            $dbRow = $this->delayed_birth_model->get_by_doc_id($obj);
            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }
            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")) {

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
                    'applicantName' => $dbRow->form_data->applicant_name,
                    'applicantGender' => $dbRow->form_data->applicant_gender,
                    'applicantMobileNo' => $dbRow->form_data->mobile,
                    'emailId' => $dbRow->form_data->email,
                    'relationWithNewBorn' => $dbRow->form_data->newborn_relation,
                    'panNo' => $dbRow->form_data->pan_no,
                    'aadharNo' => $dbRow->form_data->aadhar_no,
                    'newBornName' => $dbRow->form_data->newborn_name,
                    'dateofBirth' => $dbRow->form_data->dob,
                    'newBornGender' => $dbRow->form_data->newborn_gender,
                    'fathersName' => $dbRow->form_data->father_name,
                    'mothersName' => $dbRow->form_data->mother_name,
                    'placeofBirth' => $dbRow->form_data->newborn_birthplace,
                    'reasonForLate' => $dbRow->form_data->late_reason,
                    'state' => $dbRow->form_data->state,
                    'district' => $dbRow->form_data->district,
                    'subDivision' => $dbRow->form_data->subdivision,
                    'circleOffice' => $dbRow->form_data->revenuecircle,
                    'newBornVillageorTown' => $dbRow->form_data->village,
                    'newBornPin' => $dbRow->form_data->pin_code,
                    'cscid' => "RTPS1234",
                    'fillUpLanguage' => "English",
                    'service_type' => "PDBR",
                    'cscoffice' => "NA",
                    'spId' => array('applId' => $dbRow->service_data->appl_id)
                );

                if (!empty($dbRow->form_data->other_relation))
                    $postdata['relationWithNewBornOther'] = $dbRow->form_data->other_relation;

                if ($dbRow->form_data->newborn_birthplace == "Hospital") {
                    $postdata['nameOfHospital'] = $dbRow->form_data->hospital_name;
                    $postdata['addressHospital'] = $dbRow->form_data->address_hospital;
                } else if ($dbRow->form_data->newborn_birthplace == "House") {
                    $postdata['addressHospital'] = $dbRow->form_data->address_hospital;
                } else if ($dbRow->form_data->newborn_birthplace == "Other") {
                    $postdata['placeofBirthOther'] = $dbRow->form_data->other_placeofbirth;
                }


                if (!empty($dbRow->form_data->age_proof)) {
                    $age_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->age_proof));

                    $attachment_one = array(
                        "encl" =>  $age_proof,
                        "docType" => "application/pdf",
                        "enclFor" => "Register Hospital Govt. / Pvt. Certificate regarding Birth or Age Proof (any)",
                        "enclType" => $dbRow->form_data->age_proof_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7503",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentOne'] = $attachment_one;
                }
                if (!empty($dbRow->form_data->address_proof)) {
                    $address_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->address_proof));

                    $attachment_two = array(
                        "encl" =>  $address_proof,
                        "docType" => "application/pdf",
                        "enclFor" => "School Certificate/Admit Card (for age 6 and above) or parent's details",
                        "enclType" => $dbRow->form_data->address_proof_type,
                        "id" => "65441674",
                        "doctypecode" => "7504",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentTwo'] = $attachment_two;
                }

                if (!empty($dbRow->form_data->affidavit)) {
                    $affidavit = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->affidavit));

                    $attachment_three = array(
                        "encl" =>  $affidavit,
                        "docType" => "application/pdf",
                        "enclFor" => "Affidavit",
                        "enclType" => $dbRow->form_data->affidavit_type,
                        "id" => "65441672",
                        "doctypecode" => "7502",
                        "docRefId" => "7502",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentThree'] = $attachment_three;
                }
                if (!empty($dbRow->form_data->other_doc)) {
                    $other_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->other_doc));

                    $attachment_four = array(
                        "encl" =>  $other_doc,
                        "docType" => "application/pdf",
                        "enclFor" => "Any other document",
                        "enclType" => $dbRow->form_data->other_doc_type,
                        "id" => "65441675",
                        "doctypecode" => "7505",
                        "docRefId" => "7505",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentFour'] = $attachment_four;
                }
                // if (!empty($dbRow->form_data->soft_copy)) {
                //     $soft_copy = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->soft_copy));

                //     $attachment_zero = array(
                //         "encl" =>  $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload Scanned Copy of the Application Form",
                //         "enclType" => $dbRow->form_data->soft_copy_type,
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );

                //     $postdata['AttachmentZero'] = $attachment_zero;
                // }

                $url = $this->config->item('delayed_birth_url');
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                curl_close($curl);
                log_response($dbRow->service_data->appl_ref_no, $response);
                if ($response) {
                    $response = json_decode($response);
                    if ($response->ref->status === "success") {
                        $data_to_update = array(
                            'service_data.appl_status' => 'submitted',
                            'form_data.edistrict_ref_no' => $response->ref->edistrict_ref_no,
                            'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            'processing_history' => $processing_history
                        );
                        $this->delayed_birth_model->update($obj, $data_to_update);

                        //Sending submission SMS
                        $nowTime = date('Y-m-d H:i:s');
                        $sms = array(
                            "mobile" => (int)$dbRow->form_data->mobile,
                            "applicant_name" => $dbRow->form_data->applicant_name,
                            "service_name" => 'PermissionFor DelayedBirth Reg',
                            "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                            "app_ref_no" => $dbRow->service_data->appl_ref_no
                        );
                        sms_provider("submission", $sms);
                        redirect('spservices/applications/acknowledgement/' . $obj);

                        // return $this->output
                        // ->set_content_type('application/json')
                        // ->set_status_header(200)
                        // ->set_output(json_encode(array("status"=>true)));
                    } else {
                        $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
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

    private function checkObjectId($obj)
    {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        } //End of if else
    } //End of checkObjectId()

    private function my_transactions()
    {
        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }

    public function applicationpreview($objId = null)
    {
        $dbRow = $this->delayed_birth_model->get_by_doc_id($objId);
        //var_dump($dbRow); die;
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('delayedbirth/view_form_data', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/delayedbirth/pdbr/');
        } //End of if else
    } //End of applicationpreview()

    public function download_certificate($objId = null)
    {
        if (!empty($objId)) {
            $dbRow = $this->delayed_birth_model->get_by_doc_id($objId);
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

}//End of DelayedBirth
