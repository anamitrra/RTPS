<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Bakcl extends Rtps
{
    private $serviceName = "Issuance of Bakijai Clearance Certificate";
    private $serviceId = "BAKCL";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('bakijai/bakijai_model');
        //$this->load->model('necprocessing_model');
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
        $data = array("pageTitle" => "Application for Bakijai Clearance Certificate");
        $filter = array("_id" => new ObjectId($obj_id), "service_data.appl_status" => "DRAFT");
        $data["dbrow"] = $this->bakijai_model->get_row($filter);
        $data['usser_type'] = $this->slug;

        $this->load->view('includes/frontend/header');
        $this->load->view('bakijai/bakijai_certificate', $data);
        $this->load->view('includes/frontend/footer');
    } //End of index()
    public function getlocation()
    {
        $id = $_GET['id'];
        if ($id) {
            $data = $this->bakijai_model->get_sro_list($id);
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(array());
            }
        }
    }
    public function submit()
    {
        $objId = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");
        $this->form_validation->set_rules('language', 'Language', 'trim|required|xss_clean|strip_tags|max_length[30]');
        $this->form_validation->set_rules('applicant_name', 'Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('father_name', 'Father name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('mobile', 'Contact number', 'trim|integer|required|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('dob', 'DOB', 'trim|required|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('email', 'Email id', 'valid_email|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('pan_no', 'PAN no', 'trim|xss_clean|strip_tags|regex_match[/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/]');
        $this->form_validation->set_rules('aadhar_no', 'Aadhar no', 'trim|integer|exact_length[12]|xss_clean|strip_tags');

        $this->form_validation->set_rules('relationship', 'Relationship', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('relativeName', 'Relative name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('relationshipStatus', 'relationshipStatus', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('address_line1', 'Address line 1', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('address_line2', 'Address line 2', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('district', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('subdivision', 'Sub-division', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('revenuecircle', 'Revenue circle', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mouza', 'Mouza', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('village', 'Village', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('house_no', 'House no', 'trim|integer|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('police_st', 'Police st', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('post_office', 'Post office', 'trim|required|xss_clean|strip_tags');
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
                $rows = $this->bakijai_model->get_row($filter);

                if ($rows == false)
                    break;
            }

            $service_data = [
                "department_id" => "1234",
                "department_name" => "Revenue & Disaster Management Department",
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => "Revenue & Disaster Management Department", // office name
                "submission_date" => "",
                "district" => explode("/", $this->input->post("district"))[0],
                "service_timeline" => "5 Days",
                "appl_status" => "DRAFT",
            ];

            $form_data = [
                'user_id' => $sessionUser['userId']->{'$id'},
                'user_type' => $this->slug,

                'fillUpLanguage' => $this->input->post("language"),
                'applicant_name' => $this->input->post("applicant_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'father_name' => $this->input->post("father_name"),
                'mobile' => $this->input->post("mobile"),
                'dob' => $this->input->post("dob"),
                'email' => $this->input->post("email"),
                'pan_no' => $this->input->post("pan_no"),
                'aadhar_no' => $this->input->post("aadhar_no"),
                'relationship' => $this->input->post("relationship"),
                'relationshipStatus' => $this->input->post("relationshipStatus"),
                'relativeName' => $this->input->post("relativeName"),

                'address_line1' => $this->input->post("address_line1"),
                'address_line2' => $this->input->post("address_line2"),
                'state' => $this->input->post("state"),
                'district' => explode("/", $this->input->post("district"))[0],
                'district_id' => explode("/", $this->input->post("district"))[1],
                'subdivision' => explode("/", $this->input->post("subdivision"))[0],
                'subdivision_id' => explode("/", $this->input->post("subdivision"))[1],
                'revenuecircle' => explode("/", $this->input->post("revenuecircle"))[0],
                'revenuecircle_id' => explode("/", $this->input->post("revenuecircle"))[1],
                'mouza' => $this->input->post("mouza"),
                'village' => $this->input->post("village"),
                'house_no' => $this->input->post("house_no"),
                'police_st' => $this->input->post("police_st"),
                'post_office' => $this->input->post("post_office"),
                'pin_code' => $this->input->post("pin_code"),
                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];

            if (strlen($objId)) {
                $form_data["passport_photo_type"] = $this->input->post("passport_photo_type");
                $form_data["passport_photo"] = $this->input->post("passport_photo");
                $form_data["affidavit_type"] = $this->input->post("affidavit_type");
                $form_data["affidavit"] = $this->input->post("affidavit");
                $form_data["court_fee_type"] = $this->input->post("court_fee_type");
                $form_data["court_fee"] = $this->input->post("court_fee");
                $form_data["paymentreceipt_type"] = $this->input->post("paymentreceipt_type");
                $form_data["paymentreceipt"] = $this->input->post("paymentreceipt");
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
                $this->bakijai_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/bakijai-clearance-certificate/fileuploads/' . $objId);
            } else {
                $insert = $this->bakijai_model->insert($inputs);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    redirect('spservices/bakijai-clearance-certificate/fileuploads/' . $objectId);
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
        $dbRow = $this->bakijai_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "pageTitle" => "Attached Enclosures for " . $this->serviceName,
                "obj_id" => $objId,
                "dbrow" => $dbRow
            );
            $this->load->view('includes/frontend/header');
            //$this->load->view('nec/necertificateuploads_view',$data);
            $this->load->view('bakijai/bakcertificateuploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/bakijai/bakcl/');
        } //End of if else
    } //End of fileuploads()

    public function finalsubmition($obj = null)
    {

        //$obj = $this->input->post('obj');
        if ($obj) {
            // pre($obj);
            $dbRow = $this->bakijai_model->get_by_doc_id($obj);
            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }
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
                'dateOfBirth' => $dbRow->form_data->dob,
                'aadharNo' => $dbRow->form_data->aadhar_no,
                'panNo' => $dbRow->form_data->pan_no,
                'relationship' => $dbRow->form_data->relationship,
                'relativeName' => $dbRow->form_data->relativeName,
                //'relationshipStatus' => $dbRow->form_data->relationshipStatus,

                'addressLine1' => $dbRow->form_data->address_line1,
                'addressLine2' => $dbRow->form_data->address_line2,
                'state' => $dbRow->form_data->state,
                'district' => $dbRow->form_data->district,
                'subDivision' => $dbRow->form_data->subdivision,
                'circleOffice' => $dbRow->form_data->revenuecircle,
                'mouza' => $dbRow->form_data->mouza,
                'houseNo' => $dbRow->form_data->house_no,
                'villageTown' => $dbRow->form_data->village,
                'policeStation' => $dbRow->form_data->police_st,
                'postOffice' => $dbRow->form_data->post_office,
                'pinCode' => $dbRow->form_data->pin_code,

                'cscid' => "RTPS1234",
                'fillUpLanguage' => $dbRow->form_data->fillUpLanguage,
                'service_type' => "BAK",
                'cscoffice' => "NA",
                'spId' => array('applId' => $dbRow->service_data->appl_id)
            );

            if (!empty($dbRow->form_data->passport_photo)) {
                $passport_photo = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->passport_photo));

                $photo = array(
                    "encl" =>  $passport_photo,
                    "docType" => "image/jpeg",
                    "enclFor" => "Passport size photograph",
                    "enclType" => $dbRow->form_data->passport_photo_type,
                    "id" => "65441671",
                    "doctypecode" => "7501",
                    "docRefId" => "7501",
                    "enclExtn" => "jpeg"
                );

                $postdata['photo'] = $photo;
            }
            if (!empty($dbRow->form_data->court_fee)) {
                $court_fee = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->court_fee));

                $attachment_one = array(
                    "encl" =>  $court_fee,
                    "docType" => "application/pdf",
                    "enclFor" => "Scan copy of Court Fee Stamp",
                    "enclType" => $dbRow->form_data->court_fee_type,
                    "id" => "65441673",
                    "doctypecode" => "7503",
                    "docRefId" => "7503",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentOne'] = $attachment_one;
            }
            if (!empty($dbRow->form_data->paymentreceipt)) {
                $paymentreceipt = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->paymentreceipt));

                $Attachment_two = array(
                    "encl" =>  $paymentreceipt,
                    "docType" => "application/pdf",
                    "enclFor" => "Scan copy of upto date and payment receipt",
                    "enclType" => $dbRow->form_data->paymentreceipt_type,
                    "id" => "65441672",
                    "doctypecode" => "7502",
                    "docRefId" => "7502",
                    "enclExtn" => "pdf"
                );

                $postdata['AttachmentTwo'] = $Attachment_two;
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

            //     $attachment_five = array(
            //         "encl" =>  $soft_copy,
            //         "docType" => "application/pdf",
            //         "enclFor" => "Upload Scanned Copy of the Application Form",
            //         "enclType" => $dbRow->form_data->soft_copy_type,
            //         "id" => "65441673",
            //         "doctypecode" => "7503",
            //         "docRefId" => "7504",
            //         "enclExtn" => "pdf"
            //     );

            //     $postdata['AttachmentFive'] = $attachment_five;
            // }

            $url = $this->config->item('bakijai_url');
            $curl = curl_init($url);


            // $json = json_encode($postdata);
            // $buffer = preg_replace( "/\r|\n/", "", $json );
            // $myfile = fopen("D:\\TESTDATA\\".$dbRow->form_data->applicant_name.".txt", "a") or die("Unable to open file!");
            // fwrite($myfile, $buffer);
            // fclose($myfile);
            // die;

            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
            curl_close($curl);

            //pre($response);
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
                    $this->bakijai_model->update($obj, $data_to_update);
                    //Sending submission SMS
                    $nowTime = date('Y-m-d H:i:s');
                    $sms = array(
                        "mobile" => (int)$dbRow->form_data->mobile,
                        "applicant_name" => $dbRow->form_data->applicant_name,
                        "service_name" => 'Bakijai Certificate',
                        "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                        "app_ref_no" => $dbRow->service_data->appl_ref_no
                    );
                    sms_provider("submission", $sms);

                    redirect('spservices/applications/acknowledgement/' . $obj);
                } else {
                    $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                    $this->my_transactions();
                }
            }
        }
    }

    public function submitfiles_old()
    {
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }

        $this->form_validation->set_rules('passport_photo_type', 'Passport photo', 'required');
        $this->form_validation->set_rules('affidavit_type', 'Affidavit', 'required');
        $this->form_validation->set_rules('paymentreceipt_type', 'Payment Receipt', 'required');
        $this->form_validation->set_rules('court_fee_type', 'Scan copy of Court Fee Stamp', 'required');
        $this->form_validation->set_rules('other_doc_type', 'Other doc', 'required');

        // if (empty($this->input->post("soft_copy_old")) && $this->slug !== 'user') {
        //     if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {

        //         $this->form_validation->set_rules('soft_copy_type', 'Soft Copy Type required', 'required');
        //         $this->form_validation->set_rules('soft_copy', 'Soft Copy document required', 'required');
        //     }
        // }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $passportPhoto = cifileupload("passport_photo");
        $passport_photo = $passportPhoto["upload_status"] ? $passportPhoto["uploaded_path"] : null;

        //pre($passport_photo);
        $affidavitUpload = cifileupload("affidavit");
        $affidavit = $affidavitUpload["upload_status"] ? $affidavitUpload["uploaded_path"] : null;

        $courtFeeUpload = cifileupload("court_fee");
        $court_fee = $courtFeeUpload["upload_status"] ? $courtFeeUpload["uploaded_path"] : null;

        $paymentReceiptUpload = cifileupload("paymentreceipt");
        $paymentreceipt = $paymentReceiptUpload["upload_status"] ? $paymentReceiptUpload["uploaded_path"] : null;

        $otherDoc = cifileupload("other_doc");
        $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;

        $softCopy = cifileupload("soft_copy");
        $soft_copy = $softCopy["upload_status"] ? $softCopy["uploaded_path"] : null;

        $uploadedFiles = array(
            "passport_photo_old" => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
            "affidavit_old" => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
            "paymentreceipt_old" => strlen($paymentreceipt) ? $paymentreceipt : $this->input->post("paymentreceipt_old"),
            "court_fee_old" => strlen($court_fee) ? $court_fee : $this->input->post("court_fee_old"),
            "other_doc_old" => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
            "soft_copy_old" => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.passport_photo_type' => $this->input->post("passport_photo_type"),
                'form_data.affidavit_type' => $this->input->post("affidavit_type"),
                'form_data.paymentreceipt_type' => $this->input->post("paymentreceipt_type"),
                'form_data.court_fee_type' => $this->input->post("court_fee_type"),
                'form_data.other_doc_type' => $this->input->post("other_doc_type"),
                'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),

                'form_data.passport_photo' => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
                'form_data.affidavit' => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
                'form_data.paymentreceipt' => strlen($paymentreceipt) ? $paymentreceipt : $this->input->post("paymentreceipt_old"),
                'form_data.court_fee' => strlen($court_fee) ? $court_fee : $this->input->post("court_fee_old"),
                'form_data.other_doc' => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
                'form_data.soft_copy' => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
            );

            $this->bakijai_model->update_where(['_id' => new ObjectId($objId)], $data);
            //$this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/bakijai/bakcl/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()

    public function submitfiles()
    {
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }

        $this->form_validation->set_rules('passport_photo_type', 'Passport photo type', 'required');
        $this->form_validation->set_rules('affidavit_type', 'Affidavit type', 'required');
        $this->form_validation->set_rules('paymentreceipt_type', 'Payment Receipt type', 'required');
        $this->form_validation->set_rules('court_fee_type', 'Scan copy of Court Fee Stamp type', 'required');
        $this->form_validation->set_rules('other_doc_type', 'Other doc type', 'required');


        // if (empty($this->input->post("passport_photo_old"))) {
        //     if ((empty($this->input->post("passport_photo_temp"))) && (($_FILES['passport_photo']['name'] == ""))) {
        //         $this->form_validation->set_rules('passport_photo', 'Passport photo', 'trim|required|xss_clean|strip_tags');
        //     }
        // }
        if (empty($this->input->post("affidavit_old"))) {
            if ((empty($this->input->post("affidavit_temp"))) && (($_FILES['affidavit']['name'] == ""))) {
                $this->form_validation->set_rules('affidavit', 'Affidavit document', 'trim|required|xss_clean|strip_tags');
            }
        }
        if (empty($this->input->post("paymentreceipt_old"))) {
            if ((empty($this->input->post("paymentreceipt_temp"))) && (($_FILES['paymentreceipt']['name'] == ""))) {
                $this->form_validation->set_rules('paymentreceipt', 'Payment Receipt document', 'trim|required|xss_clean|strip_tags');
            }
        }
        if (empty($this->input->post("court_fee_old"))) {
            if ((empty($this->input->post("court_fee_temp"))) && (($_FILES['court_fee']['name'] == ""))) {
                $this->form_validation->set_rules('court_fee', 'Court Fee Stamp document', 'trim|required|xss_clean|strip_tags');
            }
        }
        if (empty($this->input->post("other_doc_old"))) {
            if ((empty($this->input->post("other_doc_temp"))) && (($_FILES['other_doc']['name'] == ""))) {
                $this->form_validation->set_rules('other_doc', 'Other document', 'trim|required|xss_clean|strip_tags');
            }
        }


        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $passport_photo = "";
        if ($_FILES['passport_photo']['name'] != "") {
            $passportPhoto = cifileupload("passport_photo");
            $passport_photo = $passportPhoto["upload_status"] ? $passportPhoto["uploaded_path"] : null;
        }
        // if (!empty($this->input->post("passport_photo_temp"))) {
        //     $passportPhoto = movedigilockerfile($this->input->post('passport_photo_temp'));
        //     $passport_photo = $passportPhoto["upload_status"] ? $passportPhoto["uploaded_path"] : null;
        // }

        $affidavit = "";
        if ($_FILES['affidavit']['name'] != "") {
            $affidavitUpload = cifileupload("affidavit");
            $affidavit = $affidavitUpload["upload_status"] ? $affidavitUpload["uploaded_path"] : null;
        }
        if (!empty($this->input->post("affidavit_temp"))) {
            $affidavitUpload = movedigilockerfile($this->input->post('affidavit_temp'));
            $affidavit = $affidavitUpload["upload_status"] ? $affidavitUpload["uploaded_path"] : null;
        }

        $court_fee = "";
        if ($_FILES['court_fee']['name'] != "") {
            $courtFeeUpload = cifileupload("court_fee");
            $court_fee = $courtFeeUpload["upload_status"] ? $courtFeeUpload["uploaded_path"] : null;
        }
        if (!empty($this->input->post("court_fee_temp"))) {
            $courtFeeUpload = movedigilockerfile($this->input->post('court_fee_temp'));
            $court_fee = $courtFeeUpload["upload_status"] ? $courtFeeUpload["uploaded_path"] : null;
        }

        $paymentreceipt = "";
        if ($_FILES['paymentreceipt']['name'] != "") {
            $paymentReceiptUpload = cifileupload("paymentreceipt");
            $paymentreceipt = $paymentReceiptUpload["upload_status"] ? $paymentReceiptUpload["uploaded_path"] : null;
        }
        if (!empty($this->input->post("paymentreceipt_temp"))) {
            $paymentReceiptUpload = movedigilockerfile($this->input->post('paymentreceipt_temp'));
            $paymentreceipt = $paymentReceiptUpload["upload_status"] ? $paymentReceiptUpload["uploaded_path"] : null;
        }

        $paymentreceipt = "";
        if ($_FILES['paymentreceipt']['name'] != "") {
            $paymentReceiptUpload = cifileupload("paymentreceipt");
            $paymentreceipt = $paymentReceiptUpload["upload_status"] ? $paymentReceiptUpload["uploaded_path"] : null;
        }
        if (!empty($this->input->post("paymentreceipt_temp"))) {
            $paymentReceiptUpload = movedigilockerfile($this->input->post('paymentreceipt_temp'));
            $paymentreceipt = $paymentReceiptUpload["upload_status"] ? $paymentReceiptUpload["uploaded_path"] : null;
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
            "passport_photo_old" => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
            "affidavit_old" => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
            "paymentreceipt_old" => strlen($paymentreceipt) ? $paymentreceipt : $this->input->post("paymentreceipt_old"),
            "court_fee_old" => strlen($court_fee) ? $court_fee : $this->input->post("court_fee_old"),
            "other_doc_old" => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.passport_photo_type' => $this->input->post("passport_photo_type"),
                'form_data.affidavit_type' => $this->input->post("affidavit_type"),
                'form_data.paymentreceipt_type' => $this->input->post("paymentreceipt_type"),
                'form_data.court_fee_type' => $this->input->post("court_fee_type"),
                'form_data.other_doc_type' => $this->input->post("other_doc_type"),

                'form_data.passport_photo' => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
                'form_data.affidavit' => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
                'form_data.paymentreceipt' => strlen($paymentreceipt) ? $paymentreceipt : $this->input->post("paymentreceipt_old"),
                'form_data.court_fee' => strlen($court_fee) ? $court_fee : $this->input->post("court_fee_old"),
                'form_data.other_doc' => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old")
            );

            $this->bakijai_model->update_where(['_id' => new ObjectId($objId)], $data);
            //$this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/bakijai/bakcl/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()

    public function preview($objId = null)
    {
        $dbRow = $this->bakijai_model->get_by_doc_id($objId);
        //var_dump($dbRow); die;
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('bakijai/bakijaipreview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/bakijai/bakcl/');
        } //End of if else
    } //End of preview()

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
        $dbRow = $this->bakijai_model->get_by_doc_id($objId);
        if(isset($dbRow->form_data->edistrict_ref_no ) && !empty($dbRow->form_data->edistrict_ref_no )){
            $this->load->helper('trackstatus');
            fetchEdistrictData($dbRow->form_data->edistrict_ref_no);
            $dbRow = $this->bakijai_model->get_by_doc_id($objId);
        }
        if (count((array)$dbRow)) {
            $data = array(
                "service_data.service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('bakijai/bakijaitrack_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/bakijai/bakcl/');
        } //End of if else
    } //End of track()

    function getID($length)
    {
        $rtps_trans_id = $this->generateID($length);
        while ($this->bakijai_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
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
        $str = "RTPS-BAKCL/" . $date . "/" . $number;
        return $str;
    } //End of generateID()

    public function queryform($objId = null)
    {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->bakijai_model->get_row(array("_id" => new ObjectId($objId), "service_data.appl_status" => "QS"));
            if ($dbRow) {
                $data = array(
                    "service_data.service_name" => $this->serviceName,
                    "dbrow" => $dbRow
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('bakijai/bakijaiquery_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/bakijai/bakcl');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/bakijai/bakcl');
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
        $this->form_validation->set_rules('passport_photo_type', 'Passport photo', 'required');
        $this->form_validation->set_rules('affidavit_type', 'Affidavit', 'required');
        $this->form_validation->set_rules('paymentreceipt_type', 'Payment Receipt', 'required');
        $this->form_validation->set_rules('court_fee_type', 'Scan copy of Court Fee Stamp', 'required');
        $this->form_validation->set_rules('other_doc_type', 'Other doc', 'required');
        $this->form_validation->set_rules('appl_ref_no', 'Application Ref No.', 'trim|xss_clean|strip_tags|max_length[255]');

        // if (empty($this->input->post("soft_copy_old")) && $this->slug !== 'user') {
        //     if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {
        //         $this->form_validation->set_rules('soft_copy_type', 'Soft Copy Type required', 'required');
        //         $this->form_validation->set_rules('soft_copy', 'Soft Copy document required', 'required');
        //     }
        // }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");


        $passportPhoto = cifileupload("passport_photo");
        $passport_photo = $passportPhoto["upload_status"] ? $passportPhoto["uploaded_path"] : $this->input->post("passport_photo_old");

        //pre($passport_photo);
        $affidavitUpload = cifileupload("affidavit");
        $affidavit = $affidavitUpload["upload_status"] ? $affidavitUpload["uploaded_path"] : $this->input->post("affidavit_old");

        $courtFeeUpload = cifileupload("court_fee");
        $court_fee = $courtFeeUpload["upload_status"] ? $courtFeeUpload["uploaded_path"] : $this->input->post("court_fee_old");

        $paymentReceiptUpload = cifileupload("paymentreceipt");
        $paymentreceipt = $paymentReceiptUpload["upload_status"] ? $paymentReceiptUpload["uploaded_path"] : $this->input->post("paymentreceipt_old");

        $otherDoc = cifileupload("other_doc");
        $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : $this->input->post("other_doc_old");

        $softCopy = cifileupload("soft_copy");
        $soft_copy = $softCopy["upload_status"] ? $softCopy["uploaded_path"] : $this->input->post("soft_copy_old");

        $uploadedFiles = array(
            "passport_photo_old" => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
            "affidavit_old" => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
            "paymentreceipt_old" => strlen($paymentreceipt) ? $paymentreceipt : $this->input->post("paymentreceipt_old"),
            "court_fee_old" => strlen($court_fee) ? $court_fee : $this->input->post("court_fee_old"),
            "other_doc_old" => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
            "soft_copy_old" => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->queryform($objId);
        } else {
            $dbRow = $this->bakijai_model->get_by_doc_id($objId);
            //  pre($dbRow);
            //  exit();
            if (count((array)$dbRow)) {
                $data = array(
                    'form_data.passport_photo_type' => $this->input->post("passport_photo_type"),
                    'form_data.affidavit_type' => $this->input->post("affidavit_type"),
                    'form_data.paymentreceipt_type' => $this->input->post("paymentreceipt_type"),
                    'form_data.court_fee_type' => $this->input->post("court_fee_type"),
                    'form_data.other_doc_type' => $this->input->post("other_doc_type"),
                    'form_data.soft_copy_type' => $this->input->post("soft_copy_type"),

                    'form_data.passport_photo' => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
                    'form_data.affidavit' => strlen($affidavit) ? $affidavit : $this->input->post("affidavit_old"),
                    'form_data.paymentreceipt' => strlen($paymentreceipt) ? $paymentreceipt : $this->input->post("paymentreceipt_old"),
                    'form_data.court_fee' => strlen($court_fee) ? $court_fee : $this->input->post("court_fee_old"),
                    'form_data.other_doc' => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old"),
                    'form_data.soft_copy' => strlen($soft_copy) ? $soft_copy : $this->input->post("soft_copy_old")
                );

                $this->bakijai_model->update_where(['_id' => new ObjectId($objId)], $data);

                $postdata = array(
                    'application_ref_no' => $dbRow->service_data->appl_ref_no,
                    'applicantName' => $dbRow->form_data->applicant_name,
                    'applicantGender' => $dbRow->form_data->applicant_gender,
                    'applicantMobileNo' => $dbRow->form_data->mobile,
                    'emailId' => $dbRow->form_data->email,
                    'dateOfBirth' => $dbRow->form_data->dob,
                    'aadharNo' => $dbRow->form_data->aadhar_no,
                    'panNo' => $dbRow->form_data->pan_no,
                    'relationship' => $dbRow->form_data->relationship,
                    'relativeName' => $dbRow->form_data->relativeName,
                    //'relationshipStatus' => $dbRow->form_data->relationshipStatus,

                    'addressLine1' => $dbRow->form_data->address_line1,
                    'addressLine2' => $dbRow->form_data->address_line2,
                    'state' => $dbRow->form_data->state,
                    'district' => $dbRow->form_data->district,
                    'subDivision' => $dbRow->form_data->subdivision,
                    'circleOffice' => $dbRow->form_data->revenuecircle,
                    'mouza' => $dbRow->form_data->mouza,
                    'houseNo' => $dbRow->form_data->house_no,
                    'villageTown' => $dbRow->form_data->village,
                    'policeStation' => $dbRow->form_data->police_st,
                    'postOffice' => $dbRow->form_data->post_office,
                    'pinCode' => $dbRow->form_data->pin_code,

                    'cscid' => "RTPS1234",
                    'fillUpLanguage' => $dbRow->form_data->fillUpLanguage,
                    'service_type' => "BAK",
                    'cscoffice' => "NA",
                    'revert' => "NA",
                    'spId' => array('applId' => $dbRow->service_data->appl_id)
                );

                if (strlen($passport_photo)) {
                    $passport_photo_type = (!empty($this->input->post("passport_photo_type"))) ? $this->input->post("passport_photo_type") : $dbRow->form_data->passport_photo_type;
                    $passport_photo = strlen($passport_photo) ? base64_encode(file_get_contents(FCPATH . $passport_photo)) : null;

                    $photo = array(
                        "encl" =>  $passport_photo,
                        "docType" => "image/jpeg",
                        "enclFor" => "Passport size photograph",
                        "enclType" => $passport_photo_type,
                        "id" => "65441671",
                        "doctypecode" => "7501",
                        "docRefId" => "7501",
                        "enclExtn" => "jpeg"
                    );

                    $postdata['photo'] = $photo;
                }

                if (strlen($court_fee)) {
                    $court_fee_type = (!empty($this->input->post("court_fee_type"))) ? $this->input->post("court_fee_type") : $dbRow->form_data->court_fee_type;
                    $court_fee = strlen($court_fee) ? base64_encode(file_get_contents(FCPATH . $court_fee)) : null;

                    $attachment_one = array(
                        "encl" =>  $court_fee,
                        "docType" => "image/jpeg",
                        "enclFor" => "Scan copy of Court Fee Stamp",
                        "enclType" => $court_fee_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7503",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentOne'] = $attachment_one;
                }

                if (strlen($paymentreceipt)) {
                    $paymentreceipt_type = (!empty($this->input->post("paymentreceipt_type"))) ? $this->input->post("paymentreceipt_type") : $dbRow->form_data->paymentreceipt_type;
                    $paymentreceipt = strlen($paymentreceipt) ? base64_encode(file_get_contents(FCPATH . $paymentreceipt)) : null;

                    $attachment_two = array(
                        "encl" =>  $paymentreceipt,
                        "docType" => "image/jpeg",
                        "enclFor" => "Scan copy of upto date and payment receipt",
                        "enclType" => $paymentreceipt_type,
                        "id" => "65441672",
                        "doctypecode" => "7502",
                        "docRefId" => "7502",
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

                //     $attachment_five = array(
                //         "encl" =>  $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload Scanned Copy of the Application Form",
                //         "enclType" => $soft_copy_type,
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );

                //     $postdata['AttachmentFive'] = $attachment_five;
                // }

                // $json = json_encode($postdata);
                // $buffer = preg_replace("/\r|\n/", "", $json);
                // $myfile = fopen("D:\\TESTDATA\\Bakijai_Query_Post.txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $buffer);
                // fclose($myfile);
                //  die;
                $json_obj = json_encode($postdata);
                $url = $this->config->item('bakijai_url');
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
                    die("ERROR : " . $error_msg);
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
                        $this->bakijai_model->update_where(['_id' => new ObjectId($objId)], $data);
                        $this->session->set_flashdata('success', 'Your application has been successfully updated');
                        redirect('spservices/bakijai/bakcl/preview/' . $objId);
                    } else {
                        // return $this->output
                        //     ->set_content_type('application/json')
                        //     ->set_status_header(401)
                        //     ->set_output(json_encode(array("status" => false)));
                        $this->session->set_flashdata('error', 'Unable to update data!!! Please try again.');
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
            $dbRow = $this->bakijai_model->get_by_doc_id($obj);
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
                    'dateOfBirth' => $dbRow->form_data->dob,
                    'aadharNo' => $dbRow->form_data->aadhar_no,
                    'panNo' => $dbRow->form_data->pan_no,
                    'emailId' => $dbRow->form_data->email,
                    'relationship' => $dbRow->form_data->relationship,
                    'relativeName' => $dbRow->form_data->relativeName,

                    'addressLine1' => $dbRow->form_data->address_line1,
                    'addressLine2' => $dbRow->form_data->address_line2,
                    'state' => $dbRow->form_data->state,
                    'district' => $dbRow->form_data->district,
                    'subDivision' => $dbRow->form_data->subdivision,
                    'circleOffice' => $dbRow->form_data->revenuecircle,
                    'mouza' => $dbRow->form_data->mouza,
                    'houseNo' => $dbRow->form_data->house_no,
                    'villageTown' => $dbRow->form_data->village,
                    'policeStation' => $dbRow->form_data->police_st,
                    'postOffice' => $dbRow->form_data->post_office,
                    'pinCode' => $dbRow->form_data->pin_code,

                    'cscid' => "RTPS1234",
                    'fillUpLanguage' => "English",
                    'service_type' => "bakcl",
                    'cscoffice' => "NA",
                    'spId' => array('applId' => $dbRow->service_data->appl_id)
                );

                if (!empty($dbRow->form_data->address_proof)) {
                    $address_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->address_proof));

                    $attachment_zero = array(
                        "encl" =>  $address_proof,
                        "docType" => "application/pdf",
                        "enclFor" => "Address Proof",
                        "enclType" => $dbRow->form_data->address_proof_type,
                        "id" => "65441674",
                        "doctypecode" => "7504",
                        "docRefId" => "7504",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentZero'] = $attachment_zero;
                }
                if (!empty($dbRow->form_data->court_fee)) {
                    $court_fee = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->court_fee));

                    $attachment_one = array(
                        "encl" =>  $court_fee,
                        "docType" => "application/pdf",
                        "enclFor" => "Identity Proof",
                        "enclType" => $dbRow->form_data->court_fee_type,
                        "id" => "65441673",
                        "doctypecode" => "7503",
                        "docRefId" => "7503",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentOne'] = $attachment_one;
                }
                if (!empty($dbRow->form_data->salaryslip)) {
                    $salaryslip = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->salaryslip));

                    $Attachment_two = array(
                        "encl" =>  $salaryslip,
                        "docType" => "application/pdf",
                        "enclFor" => "Salary Slip",
                        "enclType" => $dbRow->form_data->salaryslip_type,
                        "id" => "65441671",
                        "doctypecode" => "7501",
                        "docRefId" => "7501",
                        "enclExtn" => "pdf"
                    );

                    $postdata['AttachmentTwo'] = $Attachment_two;
                }
                if (!empty($dbRow->form_data->paymentreceipt)) {
                    $paymentreceipt = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->paymentreceipt));

                    $attachment_three = array(
                        "encl" =>  $paymentreceipt,
                        "docType" => "application/pdf",
                        "enclFor" => "Land Revenue Receipt",
                        "enclType" => $dbRow->form_data->paymentreceipt_type,
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

                //     $attachment_five = array(
                //         "encl" =>  $soft_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => "Upload Scanned Copy of the Application Form",
                //         "enclType" => $dbRow->form_data->soft_copy_type,
                //         "id" => "65441673",
                //         "doctypecode" => "7503",
                //         "docRefId" => "7504",
                //         "enclExtn" => "pdf"
                //     );

                //     $postdata['AttachmentFive'] = $attachment_five;
                // }

                // $json = json_encode($postdata);
                // $buffer = preg_replace( "/\r|\n/", "", $json );
                // $myfile = fopen("D:\\TESTDATA\\".$dbRow->form_data->applicant_name.".txt", "a") or die("Unable to open file!");
                // fwrite($myfile, $buffer);
                // fclose($myfile);

                $url = $this->config->item('bakijai_url');
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
                        $this->bakijai_model->update($obj, $data_to_update);

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
        $dbRow = $this->bakijai_model->get_by_doc_id($objId);
        //var_dump($dbRow); die;
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('bakijai/view_form_data', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/income/inc/');
        } //End of if else
    } //End of preview()

    public function download_certificate($objId = null)
    {
        if (!empty($objId)) {
            $dbRow = $this->bakijai_model->get_by_doc_id($objId);
            // var_dump($dbRow); die;
            if (count((array)$dbRow) && isset($dbRow->form_data->certificate)) {
                if (file_exists($dbRow->form_data->certificate)) {
                    cifiledownload($dbRow->form_data->certificate);

                //     $doc_file_path =  $dbRow->form_data->certificate;
                //     $b64encode = base64_encode(file_get_contents($doc_file_path));
                //     $b64decode = base64_decode($b64encode);

                //     $finfo = finfo_open();
                //     $mime_type = finfo_buffer($finfo, $b64decode, FILEINFO_MIME_TYPE);
                //     finfo_close($finfo);
                //     $ext = (get_file_extension($mime_type) == 'N/A') ? '' : get_file_extension($mime_type);

                //     // redirect output to client browser
                //     header("Content-type: {$mime_type}");
                //     header('Content-Disposition: attachment;filename="BAKCL_' . $dbRow->service_data->appl_ref_no . ".{$ext}");
                //     header('Cache-Control: max-age=0');
                //     echo $b64decode;
                // }else {
                //     $this->session->set_flashdata('errmessage', 'Something went wrong.!, Please try later.');
                //     $this->my_transactions();
                }
            } else {
                $this->session->set_flashdata('errmessage', 'Something went wrong!, Please try later.');
                $this->my_transactions();
            }
        }
    }
}//End of Castecertificate
