<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Application extends Rtps
{

    private $serviceName = "Application for Permanent Residence Certificate for Higher Education";
    private $serviceId = "PRC";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('prc/applications_model');

        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper('smsprovider');
        $this->load->helper("cifileuploaddigilocker");

        if ($this->session->role) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else
    } //End of __construct()

    public function index($objId = null)
    {
        //check_application_count_for_citizen();
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
        );
        $dbRow = $this->applications_model->get_row(array('_id' => new ObjectId($objId)));

        //pre($dbRow);
        if ($dbRow) {
            $data["dbrow"] = $this->applications_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else
        //echo $data["form_status"];
        //die;
        $this->load->view('includes/frontend/header');
        $this->load->view('prc/application', $data);
        $this->load->view('includes/frontend/footer');
    } //End of index()

    public function submit()
    {
        //data validation
        $objId = $this->input->post("obj_id");
        $formStatus = $this->input->post("form_status");

        //$this->form_validation->set_rules('certificate_language', 'certificate Language','trim|required|xss_clean|strip_tags|max_length[255]');

        $this->form_validation->set_rules('applicant_name', 'Applicant name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('father_name', 'Father', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mother_name', 'Mother', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('contact_number', 'Contact number', 'trim|integer|required|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('emailid', 'Email id', 'valid_email|trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('dob', 'DOB', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('spouse_name', 'Spouse Name', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('passport_no', 'Passport No', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('aadhar_no', 'Aadhar Number', 'trim|xss_clean|strip_tags|min_length[12]|max_length[12]');
        $this->form_validation->set_rules('pan', 'PAN Number', 'trim|xss_clean|strip_tags');

        $this->form_validation->set_rules('pa_house_no', 'House No.', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_village', 'Village', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_mouza', 'Mouza', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_post_office', 'Post Office', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_pin_code', 'Pin Code.', 'trim|required|xss_clean|strip_tags|min_length[6]|max_length[6]');
        $this->form_validation->set_rules('pa_state', 'State', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_subdivision', 'Subdivision', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_country', 'Country', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_district', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_revenuecircle', 'Circle', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_police_station', 'Police Station', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_police_station_code', 'Police Station Code', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_year', 'Year', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('pa_month', 'Month', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('ca_house_no', 'House No.', 'trim|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_village', 'Village', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_mouza', 'Mouza', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_post_office', 'Post Office', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_pin_code', 'Pin Code.', 'trim|required|xss_clean|strip_tags|min_length[6]|max_length[6]');
        $this->form_validation->set_rules('ca_state', 'State', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_subdivision', 'Subdivision', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_country', 'Country', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_district', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_revenuecircle', 'Circle', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_police_station', 'Police Station', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_police_station_code', 'Police Station Code', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_year', 'Year', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('ca_month', 'Month', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('purpose', 'Purpose', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('institute_name', 'Institute Name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('criminal_rec', 'Criminal Record', 'trim|required|xss_clean|strip_tags');

        //enclosure validation
        //if($this->slug !== 'user'){
        //$this->form_validation->set_rules('soft_doc_type', 'Soft Copy of Application type', 'required');
        //}
        $this->form_validation->set_rules('birth_doc_type', 'Birth Certificate doc type', 'required');
        $this->form_validation->set_rules('address_doc_type', 'Address Proof doc type', 'required');
        $this->form_validation->set_rules('forefathers_doc_type', 'Forefather Proof doc type', 'required');
        $this->form_validation->set_rules('property_doc_type', 'Property Proof doc type', 'required');
        $this->form_validation->set_rules('voter_doc_type', 'Voter ID doc type', 'required');
        $this->form_validation->set_rules('passport_pic_type', 'Photo', 'required');
        $this->form_validation->set_rules('admit_doc_type', 'Admit doc type', 'required');

        //file validation

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        //$softdoc = cifileupload("soft_doc");
        //$soft_doc = $softdoc["upload_status"]?$softdoc["uploaded_path"]:null;

        if (strlen($this->input->post("birth_doc_temp")) > 0) {
            $birthdoc = movedigilockerfile($this->input->post('birth_doc_temp'));
            $birth_doc = $birthdoc["upload_status"] ? $birthdoc["uploaded_path"] : null;
        } else {
            $birthdoc = cifileupload("birth_doc");
            $birth_doc = $birthdoc["upload_status"] ? $birthdoc["uploaded_path"] : null;
        }
        if (strlen($this->input->post("passport_doc_temp")) > 0) {
            $passportdoc = movedigilockerfile($this->input->post('passport_doc_temp'));
            $passport_doc = $passportdoc["upload_status"] ? $passportdoc["uploaded_path"] : null;
        } else {
            $passportdoc = cifileupload("passport_doc");
            $passport_doc = $passportdoc["upload_status"] ? $passportdoc["uploaded_path"] : null;
        }

        if (strlen($this->input->post("emp_doc_temp")) > 0) {
            $empdoc = movedigilockerfile($this->input->post('emp_doc_temp'));
            $emp_doc = $empdoc["upload_status"] ? $empdoc["uploaded_path"] : null;
        } else {
            $empdoc = cifileupload("emp_doc");
            $emp_doc = $empdoc["upload_status"] ? $empdoc["uploaded_path"] : null;
        }

        if (strlen($this->input->post("address_doc_temp")) > 0) {
            $addressdoc = movedigilockerfile($this->input->post('address_doc_temp'));
            $address_doc = $addressdoc["upload_status"] ? $addressdoc["uploaded_path"] : null;
        } else {
            $addressdoc = cifileupload("address_doc");
            $address_doc = $addressdoc["upload_status"] ? $addressdoc["uploaded_path"] : null;
        }

        if (strlen($this->input->post("forefathers_doc_temp")) > 0) {
            $forefathersdoc = movedigilockerfile($this->input->post('forefathers_doc_temp'));
            $forefathers_doc = $forefathersdoc["upload_status"] ? $forefathersdoc["uploaded_path"] : null;
        } else {
            $forefathersdoc = cifileupload("forefathers_doc");
            $forefathers_doc = $forefathersdoc["upload_status"] ? $forefathersdoc["uploaded_path"] : null;
        }

        if (strlen($this->input->post("property_doc_temp")) > 0) {
            $propertydoc = movedigilockerfile($this->input->post('property_doc_temp'));
            $property_doc = $propertydoc["upload_status"] ? $propertydoc["uploaded_path"] : null;
        } else {
            $propertydoc = cifileupload("property_doc");
            $property_doc = $propertydoc["upload_status"] ? $propertydoc["uploaded_path"] : null;
        }
        if (strlen($this->input->post("voter_doc_temp")) > 0) {
            $voterdoc = movedigilockerfile($this->input->post('voter_doc_temp'));
            $voter_doc = $voterdoc["upload_status"] ? $voterdoc["uploaded_path"] : null;
        } else {
            $voterdoc = cifileupload("voter_doc");
            $voter_doc = $voterdoc["upload_status"] ? $voterdoc["uploaded_path"] : null;
        }

        $passportpic = cifileupload("passport_pic");
        $passport_pic = $passportpic["upload_status"] ? $passportpic["uploaded_path"] : null;

        if (strlen($this->input->post("prc_doc_temp")) > 0) {
            $prcdoc = movedigilockerfile($this->input->post('prc_doc_temp'));
            $prc_doc = $prcdoc["upload_status"] ? $prcdoc["uploaded_path"] : null;
        } else {
            $prcdoc = cifileupload("prc_doc");
            $prc_doc = $prcdoc["upload_status"] ? $prcdoc["uploaded_path"] : null;
        }

        if (strlen($this->input->post("admit_doc_temp")) > 0) {
            $admitdoc = movedigilockerfile($this->input->post('admit_doc_temp'));
            $admit_doc = $admitdoc["upload_status"] ? $admitdoc["uploaded_path"] : null;
        } else {
            $admitdoc = cifileupload("admit_doc");
            $admit_doc = $admitdoc["upload_status"] ? $admitdoc["uploaded_path"] : null;
        }

        //pre($this->input->post("property_doc_old"));
        $uploadedFiles = array(
            //"soft_doc_old" => strlen($soft_doc)?$soft_doc:$this->input->post("soft_doc_old"),
            "birth_doc_old" => strlen($birth_doc) ? $birth_doc : $this->input->post("birth_doc_old"),
            "passport_doc_old" => strlen($passport_doc) ? $passport_doc : $this->input->post("passport_doc_old"),
            "emp_doc_old" => strlen($emp_doc) ? $emp_doc : $this->input->post("emp_doc_old"),
            "address_doc_old" => strlen($address_doc) ? $address_doc : $this->input->post("address_doc_old"),
            "forefathers_doc_old" => strlen($forefathers_doc) ? $forefathers_doc : $this->input->post("forefathers_doc_old"),
            "property_doc_old" => strlen($property_doc) ? $property_doc : $this->input->post("property_doc_old"),
            "voter_doc_old" => strlen($voter_doc) ? $voter_doc : $this->input->post("voter_doc_old"),
            "passport_pic_old" => strlen($passport_pic) ? $passport_pic : $this->input->post("passport_pic_old"),
            "prc_doc_old" => strlen($prc_doc) ? $prc_doc : $this->input->post("prc_doc_old"),
            "admit_doc_old" => strlen($admit_doc) ? $admit_doc : $this->input->post("admit_doc_old"),
        );
        //pre($uploadedFiles);
        /*if($this->slug !== 'user'){
        if(empty($uploadedFiles["soft_doc_old"])){
        $this->form_validation->set_rules('soft_doc', 'Soft Document','required');
        }
        }*/
        if (empty($uploadedFiles["birth_doc_old"])) {
            $this->form_validation->set_rules('birth_doc', 'Birth Proof Document', 'required');
        }
        if (empty($uploadedFiles["address_doc_old"])) {
            $this->form_validation->set_rules('address_doc', 'Address Proof Document', 'required');
        }
        if (empty($uploadedFiles["forefathers_doc_old"])) {
            $this->form_validation->set_rules('forefathers_doc', 'Forefather Proof Document', 'required');
        }
        if (empty($uploadedFiles["property_doc_old"])) {
            $this->form_validation->set_rules('property_doc', 'Property Document', 'required');
        }
        if (empty($uploadedFiles["admit_doc_old"])) {
            $this->form_validation->set_rules('admit_doc', 'Admit Card Documents', 'required');
        }
        if (empty($uploadedFiles["passport_pic_old"])) {
            $this->form_validation->set_rules('passport_pic', 'Photo', 'required');
        }
        if (empty($uploadedFiles["voter_doc_old"])) {
            $this->form_validation->set_rules('voter_doc', 'Voter ID card', 'required');
        }

        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == false) {
            //pre(validation_errors());
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
        } else {
            if ($formStatus === "QS") {
                if ($objId) {
                    $dbrow = $this->applications_model->get_by_doc_id($objId);
                    $appl_ref_no = $dbrow->service_data->appl_ref_no;

                }
            } else {
                $appl_ref_no = $this->getID(7);
            }

            $sessionUser = $this->session->userdata();

            if ($this->slug === "CSC") {
                $apply_by = $sessionUser['userId'];
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            } //End of if else

            if ($formStatus === "QS") {
                if ($objId) {
                    $dbrow = $this->applications_model->get_by_doc_id($objId);
                    $app_id = $dbrow->service_data->appl_id;
                }
            } else {
                while (1) {
                    $app_id = rand(1000000, 9999999);
                    $filter = array(
                        "service_data.appl_id" => $app_id,
                    );
                    $rows = $this->applications_model->get_row($filter);

                    if ($rows == false) {
                        break;
                    }

                }
            }
            //ends

            $service_data = [
                "department_id" => "991",
                "department_name" => "Home & Political",
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => "online", //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => $this->input->post("pa_district"), // office name
                "submission_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "service_timeline" => "14 Days",
                "appl_status" => $formStatus,
            ];

            $form_data = [
                'user_id' => $sessionUser['userId']->{'$id'},
                'user_type' => $this->slug,
                'certificate_language' => $this->input->post("certificate_language"),

                'applied_user_type' => $this->slug,
                'service_name' => $this->serviceName,
                'service_id' => $this->serviceId,
                'status' => $formStatus,
                'rtps_trans_id' => $appl_ref_no,

                'applicant_name' => $this->input->post("applicant_name"),
                'father_name' => $this->input->post("father_name"),
                'mother_name' => $this->input->post("mother_name"),
                'mobile_number' => $this->input->post("contact_number"),
                'email' => $this->input->post("emailid"),
                'dob' => $this->input->post("dob"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'spouse_name' => $this->input->post("spouse_name"),
                'passport_no' => $this->input->post("passport_no"),
                'pan' => $this->input->post("pan"),
                'aadhar_no' => $this->input->post("aadhar_no"),

                'pa_house_no' => $this->input->post("pa_house_no"),
                'pa_village' => $this->input->post("pa_village"),
                'pa_mouza' => $this->input->post("pa_mouza"),
                'pa_post_office' => $this->input->post("pa_post_office"),
                'pa_pin_code' => $this->input->post("pa_pin_code"),
                'pa_state' => $this->input->post("pa_state"),
                'pa_subdivision' => $this->input->post("pa_subdivision"),
                'pa_country' => $this->input->post("pa_country"),
                'pa_district' => $this->input->post("pa_district"),
                'pa_revenuecircle' => $this->input->post("pa_revenuecircle"),
                'pa_police_station' => $this->input->post("pa_police_station"),
                'pa_year' => $this->input->post("pa_year"),
                'pa_month' => $this->input->post("pa_month"),
                'pa_police_station_code' => $this->input->post("pa_police_station_code"),
                'address_same' => $this->input->post("address_same"),
                'district_id' => $this->input->post("district_id"),
                'subdivison_id' => $this->input->post("subdivison_id"),
                'circle_id' => $this->input->post("circle_id"),

                'ca_house_no' => $this->input->post("ca_house_no"),
                'ca_village' => $this->input->post("ca_village"),
                'ca_mouza' => $this->input->post("ca_mouza"),
                'ca_post_office' => $this->input->post("ca_post_office"),
                'ca_pin_code' => $this->input->post("ca_pin_code"),
                'ca_state' => $this->input->post("ca_state"),
                'ca_subdivision' => $this->input->post("ca_subdivision"),
                'ca_country' => $this->input->post("ca_country"),
                'ca_district' => $this->input->post("ca_district"),
                'ca_revenuecircle' => $this->input->post("ca_revenuecircle"),
                'ca_police_station' => $this->input->post("ca_police_station"),
                'ca_year' => $this->input->post("ca_year"),
                'ca_month' => $this->input->post("ca_month"),
                'ca_police_station_code' => $this->input->post("ca_police_station_code"),
                'district_id_ca' => $this->input->post("district_id_ca"),
                'subdivison_id_ca' => $this->input->post("subdivison_id_ca"),
                'circle_id_ca' => $this->input->post("circle_id_ca"),

                'purpose' => $this->input->post("purpose"),
                'criminal_rec' => $this->input->post("criminal_rec"),
                'institute_name' => $this->input->post("institute_name"),

                //'query_doc' => $this->input->post("query_doc"),
                //'query_answered' => $this->input->post("query_answered"),
                //enclosures
                //'soft_doc_type' => $this->input->post("soft_doc_type"),
                //'soft_doc' => strlen($soft_doc)?$soft_doc:$this->input->post("soft_doc_old"),
                'birth_doc_type' => $this->input->post("birth_doc_type"),
                'birth_doc' => strlen($birth_doc) ? $birth_doc : $this->input->post("birth_doc_old"),
                'passport_doc_type' => $this->input->post("passport_doc_type"),
                'passport_doc' => strlen($passport_doc) ? $passport_doc : $this->input->post("passport_doc_old"),
                'emp_proof_type' => $this->input->post("emp_proof_type"),
                'emp_doc' => strlen($emp_doc) ? $emp_doc : $this->input->post("emp_doc_old"),
                'address_doc_type' => $this->input->post("address_doc_type"),
                'address_doc' => strlen($address_doc) ? $address_doc : $this->input->post("address_doc_old"),
                'forefathers_doc_type' => $this->input->post("forefathers_doc_type"),
                'forefathers_doc' => strlen($forefathers_doc) ? $forefathers_doc : $this->input->post("forefathers_doc_old"),
                'property_doc_type' => $this->input->post("property_doc_type"),
                'property_doc' => strlen($property_doc) ? $property_doc : $this->input->post("property_doc_old"),
                'voter_doc_type' => $this->input->post("voter_doc_type"),
                'voter_doc' => strlen($voter_doc) ? $voter_doc : $this->input->post("voter_doc_old"),
                'passport_pic_type' => $this->input->post("passport_pic_type"),
                'passport_pic' => strlen($passport_pic) ? $passport_pic : $this->input->post("passport_pic_old"),
                'prc_doc_type' => $this->input->post("prc_doc_type"),
                'prc_doc' => strlen($prc_doc) ? $prc_doc : $this->input->post("prc_doc_old"),
                'admit_doc_type' => $this->input->post("admit_doc_type"),
                'admit_doc' => strlen($admit_doc) ? $admit_doc : $this->input->post("admit_doc_old"),
                //enclosure upload ends
            ];

            $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data,
            ];

            //pre($inputs);

            if (strlen($objId)) {
                $this->applications_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                //if($formStatus === "QS"){
                $data_to_update = array(
                    'form_data.convenience_charge' => $dbrow->form_data->convenience_charge,
                    'form_data.department_id' => $dbrow->form_data->department_id,
                    'form_data.payment_params' => $dbrow->form_data->payment_params,
                    'form_data.payment_status' => $dbrow->form_data->payment_status,
                    'form_data.pfc_payment_response' => $dbrow->form_data->pfc_payment_response,
                    'form_data.pfc_payment_status' => $dbrow->form_data->pfc_payment_status,
                );
                //}
                $this->applications_model->update($objId, $data_to_update);

                $this->session->set_flashdata('success', 'Your application has been successfully updated');
                redirect('spservices/prc/Application/preview/' . $objId);
            } else {
                $insert = $this->applications_model->insert($inputs);

                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    redirect('spservices/prc/Application/preview/' . $objectId);
                    exit();
                } else {
                    $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                    $this->index();
                } //End of if else
            }
        }

    } //End of submit()

    public function preview($objId = null)
    { //die($objId);
        $dbRow = $this->applications_model->get_by_doc_id($objId);

        if (count((array) $dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
            );

            $this->load->view('includes/frontend/header');
            $this->load->view('spservices/prc/applicationpreview', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'Records does not exist');
            redirect('spservices/prc/Application');
        } //End of if else
    } //End of preview()

    public function view($objId = null)
    { //die($objId);
        $dbRow = $this->applications_model->get_by_doc_id($objId);

        if (count((array) $dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
            );

            $this->load->view('includes/frontend/header');
            $this->load->view('spservices/prc/applicationview', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'Records does not exist');
            redirect('spservices/prc/Application');
        } //End of if else
    } //End of preview()

    public function finalsubmition($objId = null)
    {
        //$obj = $this->input->post('obj');
        if ($objId) {
            $dbrow = $this->applications_model->get_by_doc_id($objId);
            $processing_history = $dbrow->processing_history ?? array();

            if ($dbrow->service_data->appl_status === "QS") {
                $endIndex = count($dbrow->processing_history);
                $processing_history[$endIndex] = array(
                    "processed_by" => "Query Answered by " . $dbrow->form_data->applicant_name,
                    "action_taken" => "Query Answered",
                    "remarks" => "Query submitted by " . $dbrow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
            } else {
                //procesing data
                $processing_history[] = array(
                    "processed_by" => "Application submitted by " . $dbrow->form_data->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by " . $dbrow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
            }
            //pre(strval($dbrow->form_data->pa_month));
            if (strlen($dbrow->form_data->pa_month) < 2) {
                $pa_month = "0" . $dbrow->form_data->pa_month;
            } else {
                $pa_month = $dbrow->form_data->pa_month;
            }
            if (strlen($dbrow->form_data->pa_year) < 2) {
                $pa_year = "0" . $dbrow->form_data->pa_year;
            } else {
                $pa_year = $dbrow->form_data->pa_year;
            }
            if (strlen($dbrow->form_data->ca_month) < 2) {
                $ca_month = "0" . $dbrow->form_data->ca_month;
            } else {
                $ca_month = $dbrow->form_data->ca_month;
            }
            if (strlen($dbrow->form_data->ca_year) < 2) {
                $ca_year = "0" . $dbrow->form_data->ca_year;
            } else {
                $ca_year = $dbrow->form_data->ca_year;
            }

            $stayDurationPermanent = $pa_month . " " . $pa_year;
            $stayDurationPresent = $ca_month . " " . $ca_year;

            $postdata = array(

                'application_ref_no' => $dbrow->service_data->appl_ref_no,
                'state' => $dbrow->form_data->pa_state,
                'district' => $dbrow->form_data->pa_district,
                'subDivision' => $dbrow->form_data->pa_subdivision,
                'circleOffice' => $dbrow->form_data->pa_revenuecircle,
                'applicantName' => $dbrow->form_data->applicant_name,
                'applicantGender' => $dbrow->form_data->applicant_gender,
                'applicantMobileNo' => $dbrow->form_data->mobile_number,
                'applicantMobileNo' => $dbrow->form_data->mobile_number,
                'emailId' => $dbrow->form_data->email,
                'panNo' => $dbrow->form_data->pan,
                'aadharNo' => $dbrow->form_data->aadhar_no,
                'fatherName' => $dbrow->form_data->father_name,
                'motherName' => $dbrow->form_data->mother_name,
                'dateOfBirth' => $dbrow->form_data->dob,
                'husbandName' => $dbrow->form_data->spouse_name,
                'passportNumber' => $dbrow->form_data->passport_no,
                'houseNoPermanent' => $dbrow->form_data->pa_house_no,
                'townPermanent' => $dbrow->form_data->pa_village,
                'postOfficePermanent' => $dbrow->form_data->pa_post_office,
                'pinPermanent' => $dbrow->form_data->pa_pin_code,
                'policeStationPermanent' => $dbrow->form_data->pa_police_station,
                'stayDurationPermanent' => $stayDurationPermanent,
                'mauzaPermanent' => $dbrow->form_data->pa_mouza,
                'pscodePermanent' => $dbrow->form_data->pa_police_station_code,
                'pscodePresent' => $dbrow->form_data->ca_police_station_code,
                'IsPermAddrSame' => $dbrow->form_data->address_same,
                'houseNoPresent' => $dbrow->form_data->ca_house_no,
                'townPresent' => $dbrow->form_data->ca_village,
                'postOfficePresent' => $dbrow->form_data->ca_post_office,
                'pinPresent' => $dbrow->form_data->ca_pin_code,
                'statePresent' => $dbrow->form_data->ca_state,
                'districtPresent' => $dbrow->form_data->ca_district,
                'policeStationPresent' => $dbrow->form_data->ca_police_station,
                'subdivisionPresent' => $dbrow->form_data->ca_subdivision,
                'revenueCirclePresent' => $dbrow->form_data->ca_revenuecircle,
                'stayDurationPresent' => $stayDurationPresent,
                'mauzaPresent' => $dbrow->form_data->ca_mouza,
                'ReasonOfApplication' => $dbrow->form_data->purpose,
                'lastInsiName' => $dbrow->form_data->institute_name,
                'isAnyCriminalRecord' => $dbrow->form_data->criminal_rec,
                'cscid' => "RTPS1234",
                'fillUpLanguage' => $dbrow->form_data->certificate_language,
                'service_type' => "PRC",
                'cscoffice' => "NA",
                'spId' => array('applId' => $dbrow->service_data->appl_id),
                'countryPermanent' => $dbrow->form_data->ca_country,
                'countryPresent' => $dbrow->form_data->pa_country,
                //'certificate_language'=>$dbrow->form_data->certificate_language,
                'criminalRecordDetails' => "NA",
            );
            //pre($postdata);

            if ($dbrow->service_data->appl_status === "QS") {
                $postdata['revert'] = "NA";
            }
            if (!empty($dbrow->form_data->passport_pic)) {
                $passport_pic = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->passport_pic));

                $attachment_zero = array(
                    "encl" => $passport_pic,
                    "docType" => "image/jpeg",
                    "enclFor" => "Passport size photograph",
                    "enclType" => $dbrow->form_data->passport_pic_type,
                    "id" => "78010001",
                    "doctypecode" => "7801",
                    "docRefId" => "7801",
                    "enclExtn" => "jpeg",
                );

                $postdata['photo'] = $attachment_zero;
            }

            if (!empty($dbrow->form_data->voter_doc)) {
                $voter_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->voter_doc));

                $attachment_one = array(
                    "encl" => $voter_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Certified copy of the voters list to check the linkage",
                    "enclType" => $dbrow->form_data->voter_doc_type,
                    "id" => "78010002",
                    "doctypecode" => "7802",
                    "docRefId" => "7802",
                    "enclExtn" => "pdf",
                );

                $postdata['AttachmentOne'] = $attachment_one;
            }

            if (!empty($dbrow->form_data->property_doc)) {
                $property_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->property_doc));

                $attachment_two = array(
                    "encl" => $property_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Records of Immovable Property, if any, with up-to-date Land Revenue Paid receipt.",
                    "enclType" => $dbrow->form_data->property_doc_type,
                    "id" => "78010003",
                    "doctypecode" => "7803",
                    "docRefId" => "7803",
                    "enclExtn" => "pdf",
                );

                $postdata['AttachmentTwo'] = $attachment_two;
            }
            if (!empty($dbrow->form_data->forefathers_doc)) {
                $forefathers_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->forefathers_doc));

                $attachment_three = array(
                    "encl" => $forefathers_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Documents related to parents and forefathers having continuously resided in Assam for a minimum period of 50 years or Documents related to guardian having continuously resided in Assam for a minimum period of 20 years",
                    "enclType" => $dbrow->form_data->forefathers_doc_type,
                    "id" => "78010004",
                    "doctypecode" => "7804",
                    "docRefId" => "7804",
                    "enclExtn" => "pdf",
                );

                $postdata['AttachmentThree'] = $attachment_three;
            }

            if (!empty($dbrow->form_data->address_doc)) {
                $address_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->address_doc));

                $attachment_four = array(
                    "encl" => $address_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Upload One Address proof documents of Self or Parent’s",
                    "enclType" => $dbrow->form_data->address_doc_type,
                    "id" => "78010005",
                    "doctypecode" => "7805",
                    "docRefId" => "7805",
                    "enclExtn" => "pdf",
                );

                $postdata['AttachmentFour'] = $attachment_four;
            }
            if (!empty($dbrow->form_data->emp_doc)) {
                $emp_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->emp_doc));

                $attachment_five = array(
                    "encl" => $emp_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Employment Certificate issued by the employer showing joining in present place of posting, if any",
                    "enclType" => $dbrow->form_data->emp_proof_type,
                    "id" => "78010006",
                    "doctypecode" => "7806",
                    "docRefId" => "7806",
                    "enclExtn" => "pdf",
                );

                $postdata['AttachmentFive'] = $attachment_five;
            }
            if (!empty($dbrow->form_data->passport_doc)) {
                $passport_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->passport_doc));

                $attachment_six = array(
                    "encl" => $passport_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Copy of Indian Passport or Certified copy of the NRC 1951",
                    "enclType" => $dbrow->form_data->passport_doc_type,
                    "id" => "78010007",
                    "doctypecode" => "7807",
                    "docRefId" => "7507",
                    "enclExtn" => "pdf",
                );

                $postdata['AttachmentSix'] = $attachment_six;
            }

            if (!empty($dbrow->form_data->birth_doc)) {
                $birth_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->birth_doc));

                $attachment_seven = array(
                    "encl" => $birth_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Copy of the Birth Certificate issued by competent authority",
                    "enclType" => $dbrow->form_data->birth_doc_type,
                    "id" => "78010008",
                    "doctypecode" => "7808",
                    "docRefId" => "7808",
                    "enclExtn" => "pdf",
                );

                $postdata['AttachmentSeven'] = $attachment_seven;
            }
            /*if(!empty($dbrow->form_data->soft_doc)){
            $soft_doc = base64_encode(file_get_contents(FCPATH.$dbrow->form_data->soft_doc));

            $attachment_eight = array(
            "encl" =>  $soft_doc,
            "docType" => "application/pdf",
            "enclFor" => "Upload Scanned Copy of the Application Form",
            "enclType" => $dbrow->form_data->soft_doc_type,
            "id" => "78010009",
            "doctypecode" => "7809",
            "docRefId" => "7809",
            "enclExtn" => "pdf"
            );

            $postdata['AttachmentEight'] = $attachment_eight;
            }*/

            if (!empty($dbrow->form_data->prc_doc)) {
                $prc_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->prc_doc));

                $attachment_nine = array(
                    "encl" => $prc_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Copy of PRC of any member of family of the Applicant stating relationship, if any",
                    "enclType" => $dbrow->form_data->prc_doc_type,
                    "id" => "78010010",
                    "doctypecode" => "7810",
                    "docRefId" => "7810",
                    "enclExtn" => "pdf",
                );

                $postdata['AttachmentNine'] = $attachment_nine;
            }

            if (!empty($dbrow->form_data->admit_doc)) {
                $admit_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->admit_doc));

                $attachment_ten = array(
                    "encl" => $admit_doc,
                    "docType" => "application/pdf",
                    "enclFor" => "Copy of HSLC Certificate/Admit Card",
                    "enclType" => $dbrow->form_data->admit_doc_type,
                    "id" => "78010011",
                    "doctypecode" => "7811",
                    "docRefId" => "7811",
                    "enclExtn" => "pdf",
                );

                $postdata['AttachmentTen'] = $attachment_ten;
            }

            //$url = $this->config->item('edistrict_base_url');
            //$curl = curl_init($url . "postApplicationRTPSServices?apiKey=txoc1yv7sqq6cbc2xie0&rprm=xyz");
            //pre(json_encode($postdata));
            $prc_url='http://103.8.249.191:9080/RTPSWebService/postApplicationRTPSServices?apiKey=txoc1yv7sqq6cbc2xie0';
            //pre ($prc_url);
            $url = $this->config->item('prc_url');
            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($curl);
            //pre($response);
            //pre("STOP");
            //pre(json_encode($postdata));
            curl_close($curl);
            if ($response) {
                $response = json_decode($response);
                log_response($dbrow->service_data->appl_ref_no, $response); //log the response from external API
                if ($response->ref->status === "success") {
                    $data_to_update = array(
                        'form_data.status' => 'submitted',
                        'form_data.edistrict_ref_no' => $response->ref->edistrict_ref_no,
                        'service_data.appl_status' => 'submitted',
                        'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        'processing_history' => $processing_history,
                    );
                    $this->applications_model->update($objId, $data_to_update);

                    //Sending submission SMS
                    $nowTime = date('Y-m-d H:i:s');
                    $sms = array(
                        "mobile" => (int) $dbrow->form_data->mobile_number,
                        "applicant_name" => $dbrow->form_data->applicant_name,
                        "service_name" => 'Permanent Residence Certificate',
                        "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                        "app_ref_no" => $dbrow->service_data->appl_ref_no,
                    );
                    sms_provider("submission", $sms);

                    if ($dbrow->service_data->appl_status === "QS") {
                        $this->session->set_flashdata('success', 'Query Replied Successfully');
                        redirect('spservices/prc/Application/view/' . $objId);
                    } else {
                        //generate acknowlegement if success
                        redirect('spservices/prc/Acknowledgement/acknowledgement/' . $objId);
                    }

                } else {
                    //redierct to application page if failure
                    $this->session->set_flashdata('error', 'Something went wrong please try again.');
                    redirect('spservices/prc/Application/index/' . $objId);
                }
            }
        }
    }

    public function getID($length)
    {
        $rtps_trans_id = $this->generateID($length);
        while ($this->applications_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
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
        $str = "RTPS-PRC/" . $date . "/" . $number;
        return $str;
    } //End of generateID()

    public function query($objId = null)
    {
        if ($this->checkObjectId($objId)) {

            //$filter = array("_id"=> new ObjectId($objId), "appl_status"=>"QS","service_id" => $this->serviceId);
            $filter = array("_id" => new ObjectId($objId));
            $dbRow = $this->applications_model->get_row($filter);
            //pre($dbRow);
            if ($dbRow) {
                //pre($dbRow);
                $data = array(
                    "service_name" => $this->serviceName,
                    "dbrow" => $dbRow,
                );
                //$data["sro_dist_list"] = $this->sros_model->sro_dist_list();
                $this->load->view('includes/frontend/header');
                $this->load->view('prc/application', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/prc/application');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/prc/application');
        } //End of if else
    } //End of query()

    public function track($objId = null)
    {
        $dbRow = $this->applications_model->get_by_doc_id($objId);
        if (isset($dbRow->form_data->edistrict_ref_no) && !empty($dbRow->form_data->edistrict_ref_no)) {
            $this->load->helper('trackstatus');
            fetchEdistrictData($dbRow->form_data->edistrict_ref_no);
            $dbRow = $this->applications_model->get_by_doc_id($objId);
        }

        if (count((array) $dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('prc/prctrack_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/prc/');
        } //End of if else
    } //End of track()

    private function checkObjectId($obj)
    {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        } //End of if else
    } //End of checkObjectId()

    public function download_certificate($objId = null)
    {
        if (!empty($objId)) {
            $dbRow = $this->applications_model->get_by_doc_id($objId);
            // var_dump($dbRow); die;
            if (count((array) $dbRow) && isset($dbRow->form_data->certificate)) {
                if (file_exists($dbRow->form_data->certificate)) {
                    cifiledownload($dbRow->form_data->certificate);
                }
            } else {
                $this->session->set_flashdata('errmessage', 'Something went wrong!, Please try later.');
                $this->my_transactions();
            }
        }
    }

} //End of Registration
