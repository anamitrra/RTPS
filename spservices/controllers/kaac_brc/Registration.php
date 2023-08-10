<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps
{
    private $serviceId = "";
    private $base_serviceId = "";
    private $departmentName = "Karbi Anglong (AC)";
    private $departmentId = "2100";
    private $districtName = "";
    private $submmissionLocation = "";

    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('kaac/kaac_registration_model');
        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->model('services_model');
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->helper('smsprovider');
        $this->load->library('phpqrcode/qrlib');
        $this->load->helper("log");
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
    } //End of __construct()

    private $sectionone = array(
        array(
            'field' => 'applicant_title',
            'label' => 'Applicant Title',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'first_name',
            'label' => 'Applicant First Name',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[20]',
        ),
        array(
            'field' => 'last_name',
            'label' => 'Applicant Last Name',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'mobile',
            'label' => 'Applicant Mobile Number',
            'rules' => 'trim|required|integer|xss_clean|strip_tags|max_length[20]',
        ),
        array(
            'field' => 'applicant_gender',
            'label' => 'Gender',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'caste',
            'label' => 'Caste',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'email',
            'label' => 'Email ID',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[200]',
        ),
        array(
            'field' => 'father_title',
            'label' => 'Father/Husband Title',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'father_name',
            'label' => 'Father/Husband Name',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),

        array(
            'field' => 'district',
            'label' => 'District',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[100]',
        ),

        array(
            'field' => 'post_office',
            'label' => 'Post Office',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),

        array(
            'field' => 'police_station',
            'label' => 'Police Station',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'name_of_firm',
            'label' => 'Name of the Firm',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'name_of_proprietor',
            'label' => 'Name of Proprietor',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'occupation_trade',
            'label' => 'Occupation/Trade',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'community',
            'label' => 'Community',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'class_of_business',
            'label' => 'Class of Business',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'address',
            'label' => 'Address',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'business_locality',
            'label' => 'Business Location (By Locality)',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'business_word_no',
            'label' => 'Business Location(By Ward No)',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'reason_for_consideration',
            'label' => 'Special reason for Consideration',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'rented_owned',
            'label' => 'Building Rented/Owned',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),

    );
    private $sectionrent = array(
        array(
            'field' => 'name_of_owner',
            'label' => 'Name of Owner',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
    );
    private $sectionrenewal = array(
        array(
            'field' => 'pre_certificate_no',
            'label' => 'Registration Certificate No',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'pre_mobile_no',
            'label' => 'Mobile Number',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),

    );

    public function index($obj_id = null)
    {
        if ($obj_id == "KBRC") {
            $data = array(
                "pageTitleId" => "KBRC",
                "pageTitle" => "Application Form for Issuance Of Business Registration Certificate",
                "pageTitleAssamese" => "ব্যৱসায় পঞ্জীয়ন প্ৰমাণ পত্ৰ প্ৰদানৰ বাবে আবেদন পত্ৰ",

            );
            $data["dbrow"] = null;
        } else if ($obj_id == "KRBC") {
            $data = array("pageTitleId" => "KRBC",
                "pageTitle" => "Application Form for Renewal of Business Registration Certificate",
                "pageTitleAssamese" => "ব্যৱসায় পঞ্জীয়ন প্ৰমাণ পত্ৰ নবীকৰণৰ বাবে আবেদন পত্ৰ",
            );
            $data["dbrow"] = null;
        } else {

            try {
                $objectId = new ObjectId($obj_id);
                $filter = array("_id" => $objectId,
                    "service_data.appl_status" => "DRAFT",
                );

                $dbrow = $this->kaac_registration_model->get_row($filter);
                $data = array(
                    "pageTitleId" => $dbrow->form_data->service_id,
                    "pageTitle" => "Application Form for Issuance Of Business Registration Certificate",
                    "pageTitleAssamese" => "ব্যৱসায় পঞ্জীয়ন প্ৰমাণ পত্ৰ প্ৰদানৰ বাবে আবেদন পত্ৰ",
                    "dbrow" => $dbrow,

                );
            } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
                $this->my_transactions();
            }
        }
        $data['usser_type'] = $this->slug;
        $this->load->view('includes/frontend/header');
        $this->load->view('kaac/sec_group/kaac_registration_form', $data);
        $this->load->view('includes/frontend/footer');
    } //End of index()
    public function getlocation()
    {
        $id = $_GET['id'];
        if ($id) {
            $data = $this->kaac_registration_model->get_sro_list($id);
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(array());
            }
        }
    }

    public function submit()
    {
        $this->serviceId = $this->input->post("service_id");
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules($this->sectionone);

        if ($this->serviceId == 'KRBC') {
            $this->form_validation->set_rules($this->sectionrenewal);
        }

        if ($this->input->post('rented_owned') == 'Rent') {
            $this->form_validation->set_rules($this->sectionrent);
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : $this->serviceId;
            $this->index($obj_id);
        } else {

            $objId = $this->saveSectionOne($this->input->post());
            if (strlen($objId)) {
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/kaac_brc/registration/fileuploads/' . $objId);
            } else {
                $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                $obj_id = strlen($objId) ? $objId : $this->serviceId;
                $this->index($obj_id);
                exit;
            }
        } //End of if else

    } //End of submit()

    public function saveSectionOne($data)
    {
        $this->serviceId = $data['service_id'];

        $objId = $data['obj_id'];
        $sessionUser = $this->session->userdata();
        $submissionMode = $data['submission_mode'] ?? null;
        if ($this->slug === "CSC") {
            $apply_by = $sessionUser['userId'];
        } else {
            $objectId = new MongoDB\BSON\ObjectId($obj_id);

            $filter = array(
                "_id" => $objectId,
                "service_data.appl_status" => "DRAFT",
            );
            $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        } //End of if else

        if ($data['service_id'] == "KBRC") {
            $this->base_serviceId = "1879";
            $this->serviceName = "Issuance of Business Registration Certificate - KAAC";
        } else if ($this->serviceId == "KRBC") {
            $this->base_serviceId = "1880";
            $this->serviceName = "Issuance of Renewal of Business Registration Certificate - KAAC";
        } else {
            $this->my_transactions();
            exit;
        }
        if (strlen($objId)) {

            $dbrow = $this->kaac_registration_model->get_row($filter);
            $reg_no = $dbrow->form_data->ahsec_reg_no;

            $form_data = [
                'form_data.user_type' => $this->slug,
                'form_data.pre_certificate_no' => $data['pre_certificate_no'] ?? null,
                'form_data.pre_mobile_no' => $data['pre_mobile_no'] ?? null,
                'form_data.applicant_name' => $data['applicant_title_name'] . ' ' . $data['first_name'] . ' ' . $data['last_name'],
                'form_data.applicant_title' => $data['applicant_title'],
                'form_data.first_name' => $data['first_name'],
                'form_data.last_name' => $data['last_name'],
                'form_data.mobile' => $data['mobile'],
                'form_data.email' => $data['email'],
                'form_data.applicant_gender' => $data['applicant_gender'],
                'form_data.caste' => $data['caste'],
                'form_data.father_title' => $data['father_title'],
                'form_data.father_name' => $data['father_name'],
                'form_data.aadhar_no' => $data['aadhar_no'],
                'form_data.applicant_gender_name' => $data['gender_name'],
                'form_data.district_name' => $data['district_name'],
                'form_data.applicant_title_name' => $data['applicant_title_name'],

                'form_data.district' => $data['district'],
                'form_data.police_station' => $data['police_station'],
                'form_data.post_office' => $data['post_office'],

                'form_data.name_of_firm' => $data['name_of_firm'],
                'form_data.occupation_trade' => $data['occupation_trade'],
                'form_data.name_of_proprietor' => $data['name_of_proprietor'],
                'form_data.community' => $data['community'],
                'form_data.class_of_business' => $data['class_of_business'],
                'form_data.address' => $reg_no ?? $data['address'],
                'form_data.business_locality' => $data['business_locality'],
                'form_data.business_word_no' => $data['business_word_no'],
                'form_data.reason_for_consideration' => $data['reason_for_consideration'],
                'form_data.rented_owned' => $data['rented_owned'],
                'form_data.name_of_owner' => $data['name_of_owner'],
                'form_data.user_id' => $sessionUser['userId']->{'$id'},
                'form_data.user_type' => $this->slug,
                'form_data.updated_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            ];

            $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $form_data);
            return $objId;

        } else {

            $appl_ref_no = $this->getID(7);
            $sessionUser = $this->session->userdata();

            if ($this->slug === "CSC") {
                $apply_by = $sessionUser['userId'];
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            } //End of if else

            while (1) {
                $app_id = rand(1000000000, 9999999999);
                $filter = array(
                    "service_data.appl_id" => $app_id,
                );
                $rows = $this->kaac_registration_model->get_row($filter);
                if ($rows == false) {
                    break;
                }

            }

            $service_data = [
                "department_id" => $this->config->item('kaac_department_id'),
                "department_name" => $this->config->item('kaac_department_name'),
                "service_id" => $this->base_serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "applied_by" => $apply_by,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "submission_location" => "Revenue Department KAAC(Revenue Department-KAAC- Revenue Department-KAAC)", // office name
                "submission_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "service_timeline" => "7",
                "appl_status" => "DRAFT",
                "district" => "KARBI ANGLONG"
            ];

            $form_data = [
                'user_type' => $this->slug,
                'pre_certificate_no' => $data['pre_certificate_no'] ?? null,
                'pre_mobile_no' => $data['pre_mobile_no'] ?? null,
                "service_id" => $this->serviceId,
                'applicant_name' => $data['applicant_title_name'] . ' ' . $data['first_name'] . ' ' . $data['last_name'],
                'applicant_title' => $data['applicant_title'],
                'applicant_title_name' => $data['applicant_title_name'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'mobile' => $data['mobile'],
                'email' => $data['email'],
                'applicant_gender' => $data['applicant_gender'],
                'caste' => $data['caste'],
                'father_title' => $data['father_title'],
                'father_name' => $data['father_name'],
                'aadhar_no' => $data['aadhar_no'],
                'applicant_gender_name' => $data['gender_name'],
                'district_name' => $data['district_name'],
                'district' => $data['district'],
                'police_station' => $data['police_station'],
                'post_office' => $data['post_office'],

                'name_of_firm' => $data['name_of_firm'],
                'occupation_trade' => $data['occupation_trade'],
                'name_of_proprietor' => $data['name_of_proprietor'],
                'community' => $data['community'],
                'class_of_business' => $data['class_of_business'],
                'address' => $reg_no ?? $data['address'],
                'business_locality' => $data['business_locality'],
                'business_word_no' => $data['business_word_no'],
                'reason_for_consideration' => $data['reason_for_consideration'],
                'rented_owned' => $data['rented_owned'],
                'name_of_owner' => $data['name_of_owner'],
                'user_id' => $sessionUser['userId']->{'$id'},
                'user_type' => $this->slug,
                'created_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),

            ];
            //submit for first time
            $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data,
            ];
            $insert = $this->kaac_registration_model->insert($inputs);

            // pre($insert);
            if ($insert) {
                return $objectId = $insert['_id']->{'$id'};

            } else {
                return null;
            }

        } //End of if else
    }

    public function fileuploads($objId = null)
    {
        // pre("Files upload");
        $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array(
                "service_name" => $dbRow->service_data->service_name,
                "pageTitle" => "Attached Enclosures for " . $dbRow->service_data->service_name,
                "pageTitleBaseId" => $dbRow->service_data->service_id,
                "pageTitleId" => $dbRow->form_data->service_id,
                "obj_id" => $objId,
                "dbrow" => $dbRow,
            );

            $this->load->view('includes/frontend/header');
            $this->load->view('kaac/sec_group/kaac_fileuploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaac/registration/');
        } //End of if else
    } //End of fileuploads()

    public function submitfiles()
    {
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);

        $this->form_validation->set_rules('photo_type', 'Passport photo type', 'required');
        $this->form_validation->set_rules('signature_type', 'Signature type', 'required');
        $this->form_validation->set_rules('identity_proof_type', 'Identity Proof type', 'required');
        $this->form_validation->set_rules('address_proof_type', 'Address Proof type', 'required');
        $this->form_validation->set_rules('house_tax_reciept_type', 'House Tax Receipt', 'required');

        $photo = '';
        $sign = '';
        $identity_proof_card = '';
        $address_proof_card = '';
        $house_tax_reciept_card = '';
        $room_rent_deposite_card = '';
        $consideration_letter_card = '';
        $cur_business_copy_rc_card = '';

        $sign = isset($dbRow->form_data->signature) ? $dbRow->form_data->signature : '';

        $identity_proof_card = isset($dbRow->form_data->identity_proof) ? $dbRow->form_data->identity_proof : '';
        $address_proof_card = isset($dbRow->form_data->address_proof) ? $dbRow->form_data->address_proof : '';
        $house_tax_reciept_card = isset($dbRow->form_data->house_tax_reciept) ? $dbRow->form_data->house_tax_reciept : '';

        $room_rent_deposite_card = isset($dbRow->form_data->room_rent_deposite) ? $dbRow->form_data->room_rent_deposite : '';
        $consideration_letter_card = isset($dbRow->form_data->consideration_letter) ? $dbRow->form_data->consideration_letter : '';
        $cur_business_copy_rc_card = isset($dbRow->form_data->cur_business_copy_rc) ? $dbRow->form_data->cur_business_copy_rc : '';

        if (empty($this->input->post("photo_old"))) {
            if ((empty($this->input->post("photo_data"))) && ($_FILES['photo']['name'] == "")) {
                $this->form_validation->set_rules('photo', 'Photo is Required', 'required');
            }
        }

        if ($sign == null && $_FILES['signature']['name'] == "") {
            $this->form_validation->set_rules('signature', 'Signature is Required', 'required');
        }

        if (empty($this->input->post("identity_proof_old"))) {
            if (((empty($this->input->post("identity_proof_type"))) && (($_FILES['identity_proof']['name'] != "") || (!empty($this->input->post("identity_proof_temp"))))) || ((!empty($this->input->post("identity_proof_type"))) && (($_FILES['identity_proof']['name'] == "") && (empty($this->input->post("identity_proof_temp")))))) {
                $this->form_validation->set_rules('identity_proof', 'Identity Proof', 'required');
            }
        }

        if (empty($this->input->post("address_proof_old"))) {
            if (((empty($this->input->post("address_proof_type"))) && (($_FILES['address_proof']['name'] != "") || (!empty($this->input->post("address_proof_temp"))))) || ((!empty($this->input->post("address_proof_type"))) && (($_FILES['address_proof']['name'] == "") && (empty($this->input->post("address_proof_temp")))))) {

                $this->form_validation->set_rules('address_proof', 'Address Proof', 'required');
            }
        }

        if (empty($this->input->post("house_tax_reciept_old"))) {
            if (((empty($this->input->post("house_tax_reciept_type"))) && (($_FILES['house_tax_reciept']['name'] != "") || (!empty($this->input->post("house_tax_reciept_temp"))))) || ((!empty($this->input->post("house_tax_reciept_type"))) && (($_FILES['house_tax_reciept']['name'] == "") && (empty($this->input->post("house_tax_reciept_temp")))))) {

                $this->form_validation->set_rules('house_tax_reciept', 'House Tax Receipt', 'required');
            }
        }

        if ($_FILES['photo']['name'] != "") {
            $photo = cifileupload("photo");
            $photo = $photo["upload_status"] ? $photo["uploaded_path"] : null;

        }
        $photo_data = $this->input->post("photo_data");

        if ((strlen($photo) == '') && (strlen($photo_data) > 50)) {
            $candidatePhotoData = str_replace('data:image/jpeg;base64,', '', $photo_data);
            $candidatePhotoData2 = str_replace(' ', '+', $candidatePhotoData);
            $candidatePhotoData64 = base64_decode($candidatePhotoData2);

            $fileName = uniqid() . '.jpeg';
            $dirPath = 'storage/docs/kacc_brc/photos/';
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
            }
            $photo = $dirPath . $fileName;
            file_put_contents(FCPATH . $photo, $candidatePhotoData64);
        } else {
            $photo = strlen($photo) ? $photo : (isset($dbRow->form_data->photo) ? $dbRow->form_data->photo : '');
        }

        if ($_FILES['signature']['name'] != "") {
            $signature = cifileupload("signature");
            $sign = $signature["upload_status"] ? $signature["uploaded_path"] : null;
        }

        if (strlen($this->input->post("identity_proof_temp")) > 0) {
            $iCard = movedigilockerfile($this->input->post('identity_proof_temp'));
            $identity_proof_card = $iCard["upload_status"] ? $iCard["uploaded_path"] : null;
        } else {
            if ($_FILES['identity_proof']['name'] != "") {
                $iCard = cifileupload("identity_proof");
                $identity_proof_card = $iCard["upload_status"] ? $iCard["uploaded_path"] : null;
            }
        }

        if (strlen($this->input->post("address_proof_temp")) > 0) {
            $proof = movedigilockerfile($this->input->post('address_proof_temp'));
            $address_proof_card = $proof["upload_status"] ? $proof["uploaded_path"] : null;
        } else {
            if ($_FILES['address_proof']['name'] != "") {
                $proof = cifileupload("address_proof");
                $address_proof_card = $proof["upload_status"] ? $proof["uploaded_path"] : null;
            }
        }

        if (strlen($this->input->post("house_tax_reciept_temp")) > 0) {
            $house_tax = movedigilockerfile($this->input->post('house_tax_reciept_temp'));
            $house_tax_reciept_card = $house_tax["upload_status"] ? $house_tax["uploaded_path"] : null;
        } else {
            if ($_FILES['house_tax_reciept']['name'] != "") {
                $house_tax = cifileupload("house_tax_reciept");
                $house_tax_reciept_card = $house_tax["upload_status"] ? $house_tax["uploaded_path"] : null;
            }
        }

        if (strlen($this->input->post("room_rent_deposite_temp")) > 0) {
            $room_rent = movedigilockerfile($this->input->post('room_rent_deposite_temp'));
            $room_rent_deposite = $room_rent["upload_status"] ? $room_rent["uploaded_path"] : null;
        } else {
            if ($_FILES['room_rent_deposite']['name'] != "") {
                $room_rent = cifileupload("room_rent_deposite");
                $room_rent_deposite_card = $room_rent["upload_status"] ? $room_rent["uploaded_path"] : null;
            }
        }

        if (strlen($this->input->post("consideration_letter_temp")) > 0) {
            $con_letter = movedigilockerfile($this->input->post('consideration_letter_temp'));
            $consideration_letter = $con_letter["upload_status"] ? $con_letter["uploaded_path"] : null;
        } else {
            if ($_FILES['consideration_letter']['name'] != "") {
                $con_letter = cifileupload("consideration_letter");
                $consideration_letter_card = $con_letter["upload_status"] ? $con_letter["uploaded_path"] : null;
            }
        }

        if ($dbRow->form_data->service_id == 'KRBC') {
            if (strlen($this->input->post("cur_business_copy_rc_temp")) > 0) {
                $bus_copy_rc = movedigilockerfile($this->input->post('cur_business_copy_rc_temp'));
                $cur_business_copy_rc = $bus_copy_rc["upload_status"] ? $bus_copy_rc["uploaded_path"] : null;
            } else {
                if ($_FILES['cur_business_copy_rc']['name'] != "") {
                    $bus_copy_rc = cifileupload("cur_business_copy_rc");
                    $cur_business_copy_rc_card = $bus_copy_rc["upload_status"] ? $bus_copy_rc["uploaded_path"] : null;
                }
            }
        }

        $uploadedFiles = array(
            "photo" => strlen($photo) ? $photo : '',
            "signature" => strlen($sign) ? $sign : '',
            "identity_proof" => strlen($identity_proof_card) ? $identity_proof_card : '',
            "address_proof" => strlen($address_proof_card) ? $address_proof_card : '',
            "house_tax_reciept" => strlen($house_tax_reciept_card) ? $house_tax_reciept_card : '',
            "room_rent_deposite" => strlen($room_rent_deposite_card) ? $room_rent_deposite_card : '',
            "consideration_letter" => strlen($consideration_letter_card) ? $consideration_letter_card : '',
            "cur_business_copy_rc" => strlen($cur_business_copy_rc_card) ? $cur_business_copy_rc_card : '',

        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {

            $data = array(
                'form_data.photo_type' => $this->input->post("photo_type"),
                'form_data.photo' => $photo,
                'form_data.signature_type' => $this->input->post("signature_type"),
                'form_data.signature' => $sign,
                'form_data.identity_proof_type' => $this->input->post("identity_proof_type"),
                'form_data.identity_proof' => $identity_proof_card,
                'form_data.address_proof_type' => $this->input->post("address_proof_type"),
                'form_data.address_proof' => $address_proof_card,
                'form_data.house_tax_reciept_type' => $this->input->post("house_tax_reciept_type"),
                'form_data.house_tax_reciept' => $house_tax_reciept_card,
                'form_data.room_rent_deposite_type' => $this->input->post("room_rent_deposite_type"),
                'form_data.room_rent_deposite' => $room_rent_deposite_card,
                'form_data.consideration_letter_type' => $this->input->post("consideration_letter_type"),
                'form_data.consideration_letter' => $consideration_letter_card,
                'form_data.cur_business_copy_rc_type' => $this->input->post("cur_business_copy_rc_type"),
                'form_data.cur_business_copy_rc' => $cur_business_copy_rc_card,
            );

            $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $data);
            redirect('spservices/kaac_brc/registration/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()

    public function preview($objId = null)
    {
        $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);
        //var_dump($dbRow); die;

        if ($dbRow->form_data->service_id == "KBRC") {
            $data = array(
                "pageTitleId" => "KBRC",
                "pageTitle" => "Application Form for Issuance Of Business Registration Certificate",
                "pageTitleAssamese" => "ব্যৱসায় পঞ্জীয়ন প্ৰমাণ পত্ৰ প্ৰদানৰ বাবে আবেদন পত্ৰ",
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
            );

        } else if ($dbRow->form_data->service_id == "KRBC") {
            $data = array(
                "pageTitleId" => "KRBC",
                "pageTitle" => "Application Form for Renewal of Business Registration Certificate",
                "pageTitleAssamese" => "ব্যৱসায় পঞ্জীয়ন প্ৰমাণ পত্ৰ নবীকৰণৰ বাবে আবেদন পত্ৰ",
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
            );
        }
        if (count((array) $dbRow)) {
            $this->load->view('includes/frontend/header');
            $this->load->view('kaac/sec_group/kaac_preview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaac_brc/registration/');
        } //End of if else
    } //End of preview()

    public function track($objId = null)
    {
        $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            $data = array(
                "service_data.service_name" => $dbRow->form_data->service_id,
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('kaac/sec_group/kaac_track_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaac/registration/');
        } //End of if else
    } //End of track()

    public function getID($length)
    {
        $rtps_trans_id = $this->generateID($length);
        while ($this->kaac_registration_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
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
        $str = "RTPS-" . $this->serviceId . "/" . $date . "/" . $number;
        return $str;
    } //End of generateID()

    public function queryform($objId = null)
    {

        if ($this->checkObjectId($objId)) {
            $dbRow = $this->kaac_registration_model->get_row(array("_id" => new ObjectId($objId), "service_data.appl_status" => "QS"));

            if ($dbRow->form_data->service_id == "KBRC") {
                $data = array(
                    "pageTitleId" => "KBRC",
                    "pageTitle" => "Application Form for Issuance Of Business Registration Certificate",
                    "pageTitleAssamese" => "ব্যৱসায় পঞ্জীয়ন প্ৰমাণ পত্ৰ প্ৰদানৰ বাবে আবেদন পত্ৰ",
                    "dbrow" => $dbRow,
                    "user_type" => $this->slug,
                );

            } else if ($dbRow->form_data->service_id == "KRBC") {
                $data = array(
                    "pageTitleId" => "KRBC",
                    "pageTitle" => "Application Form for Renewal of Business Registration Certificate",
                    "pageTitleAssamese" => "ব্যৱসায় পঞ্জীয়ন প্ৰমাণ পত্ৰ নবীকৰণৰ বাবে আবেদন পত্ৰ",
                    "dbrow" => $dbRow,
                    "user_type" => $this->slug,
                );
            }

            if ($dbRow) {

                $this->load->view('includes/frontend/header');
                $this->load->view('kaac/sec_group/kaac_query_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/kaac_brc/registration');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/kaac_brc/registration');
        } //End of if else
    } //End of query()

    public function querysubmit()
    {
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);
        if (count((array) $dbRow)) {
            
        $this->form_validation->set_rules('photo_type', 'Passport photo type', 'required');
        $this->form_validation->set_rules('signature_type', 'Signature type', 'required');
        $this->form_validation->set_rules('identity_proof_type', 'Identity Proof type', 'required');
        $this->form_validation->set_rules('address_proof_type', 'Address Proof type', 'required');
        $this->form_validation->set_rules('house_tax_reciept_type', 'House Tax Receipt', 'required');

        $photo = '';
        $sign = '';
        $identity_proof_card = '';
        $address_proof_card = '';
        $house_tax_reciept_card = '';
        $room_rent_deposite_card = '';
        $consideration_letter_card = '';
        $cur_business_copy_rc_card = '';

        $sign = isset($dbRow->form_data->signature) ? $dbRow->form_data->signature : '';

        $identity_proof_card = isset($dbRow->form_data->identity_proof) ? $dbRow->form_data->identity_proof : '';
        $address_proof_card = isset($dbRow->form_data->address_proof) ? $dbRow->form_data->address_proof : '';
        $house_tax_reciept_card = isset($dbRow->form_data->house_tax_reciept) ? $dbRow->form_data->house_tax_reciept : '';

        $room_rent_deposite_card = isset($dbRow->form_data->room_rent_deposite) ? $dbRow->form_data->room_rent_deposite : '';
        $consideration_letter_card = isset($dbRow->form_data->consideration_letter) ? $dbRow->form_data->consideration_letter : '';
        $cur_business_copy_rc_card = isset($dbRow->form_data->cur_business_copy_rc) ? $dbRow->form_data->cur_business_copy_rc : '';

        if (empty($this->input->post("photo_old"))) {
            if ((empty($this->input->post("photo_data"))) && ($_FILES['photo']['name'] == "")) {
                $this->form_validation->set_rules('photo', 'Photo is Required', 'required');
            }
        }

        if ($sign == null && $_FILES['signature']['name'] == "") {
            $this->form_validation->set_rules('signature', 'Signature is Required', 'required');
        }

        if (empty($this->input->post("identity_proof_old"))) {
            if (((empty($this->input->post("identity_proof_type"))) && (($_FILES['identity_proof']['name'] != "") || (!empty($this->input->post("identity_proof_temp"))))) || ((!empty($this->input->post("identity_proof_type"))) && (($_FILES['identity_proof']['name'] == "") && (empty($this->input->post("identity_proof_temp")))))) {
                $this->form_validation->set_rules('identity_proof', 'Identity Proof', 'required');
            }
        }

        if (empty($this->input->post("address_proof_old"))) {
            if (((empty($this->input->post("address_proof_type"))) && (($_FILES['address_proof']['name'] != "") || (!empty($this->input->post("address_proof_temp"))))) || ((!empty($this->input->post("address_proof_type"))) && (($_FILES['address_proof']['name'] == "") && (empty($this->input->post("address_proof_temp")))))) {

                $this->form_validation->set_rules('address_proof', 'Address Proof', 'required');
            }
        }

        if (empty($this->input->post("house_tax_reciept_old"))) {
            if (((empty($this->input->post("house_tax_reciept_type"))) && (($_FILES['house_tax_reciept']['name'] != "") || (!empty($this->input->post("house_tax_reciept_temp"))))) || ((!empty($this->input->post("house_tax_reciept_type"))) && (($_FILES['house_tax_reciept']['name'] == "") && (empty($this->input->post("house_tax_reciept_temp")))))) {

                $this->form_validation->set_rules('house_tax_reciept', 'House Tax Receipt', 'required');
            }
        }

        if ($_FILES['photo']['name'] != "") {
            $photo = cifileupload("photo");
            $photo = $photo["upload_status"] ? $photo["uploaded_path"] : null;

        }
        $photo_data = $this->input->post("photo_data");

        if ((strlen($photo) == '') && (strlen($photo_data) > 50)) {
            $candidatePhotoData = str_replace('data:image/jpeg;base64,', '', $photo_data);
            $candidatePhotoData2 = str_replace(' ', '+', $candidatePhotoData);
            $candidatePhotoData64 = base64_decode($candidatePhotoData2);

            $fileName = uniqid() . '.jpeg';
            $dirPath = 'storage/docs/kacc_brc/photos/';
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
            }
            $photo = $dirPath . $fileName;
            file_put_contents(FCPATH . $photo, $candidatePhotoData64);
        } else {
            $photo = strlen($photo) ? $photo : (isset($dbRow->form_data->photo) ? $dbRow->form_data->photo : '');
        }

        if ($_FILES['signature']['name'] != "") {
            $signature = cifileupload("signature");
            $sign = $signature["upload_status"] ? $signature["uploaded_path"] : null;
        }

        if (strlen($this->input->post("identity_proof_temp")) > 0) {
            $iCard = movedigilockerfile($this->input->post('identity_proof_temp'));
            $identity_proof_card = $iCard["upload_status"] ? $iCard["uploaded_path"] : null;
        } else {
            if ($_FILES['identity_proof']['name'] != "") {
                $iCard = cifileupload("identity_proof");
                $identity_proof_card = $iCard["upload_status"] ? $iCard["uploaded_path"] : null;
            }
        }

        if (strlen($this->input->post("address_proof_temp")) > 0) {
            $proof = movedigilockerfile($this->input->post('address_proof_temp'));
            $address_proof_card = $proof["upload_status"] ? $proof["uploaded_path"] : null;
        } else {
            if ($_FILES['address_proof']['name'] != "") {
                $proof = cifileupload("address_proof");
                $address_proof_card = $proof["upload_status"] ? $proof["uploaded_path"] : null;
            }
        }

        if (strlen($this->input->post("house_tax_reciept_temp")) > 0) {
            $house_tax = movedigilockerfile($this->input->post('house_tax_reciept_temp'));
            $house_tax_reciept_card = $house_tax["upload_status"] ? $house_tax["uploaded_path"] : null;
        } else {
            if ($_FILES['house_tax_reciept']['name'] != "") {
                $house_tax = cifileupload("house_tax_reciept");
                $house_tax_reciept_card = $house_tax["upload_status"] ? $house_tax["uploaded_path"] : null;
            }
        }

        if (strlen($this->input->post("room_rent_deposite_temp")) > 0) {
            $room_rent = movedigilockerfile($this->input->post('room_rent_deposite_temp'));
            $room_rent_deposite = $room_rent["upload_status"] ? $room_rent["uploaded_path"] : null;
        } else {
            if ($_FILES['room_rent_deposite']['name'] != "") {
                $room_rent = cifileupload("room_rent_deposite");
                $room_rent_deposite_card = $room_rent["upload_status"] ? $room_rent["uploaded_path"] : null;
            }
        }

        if (strlen($this->input->post("consideration_letter_temp")) > 0) {
            $con_letter = movedigilockerfile($this->input->post('consideration_letter_temp'));
            $consideration_letter = $con_letter["upload_status"] ? $con_letter["uploaded_path"] : null;
        } else {
            if ($_FILES['consideration_letter']['name'] != "") {
                $con_letter = cifileupload("consideration_letter");
                $consideration_letter_card = $con_letter["upload_status"] ? $con_letter["uploaded_path"] : null;
            }
        }

        if ($dbRow->form_data->service_id == 'KRBC') {
            if (strlen($this->input->post("cur_business_copy_rc_temp")) > 0) {
                $bus_copy_rc = movedigilockerfile($this->input->post('cur_business_copy_rc_temp'));
                $cur_business_copy_rc = $bus_copy_rc["upload_status"] ? $bus_copy_rc["uploaded_path"] : null;
            } else {
                if ($_FILES['cur_business_copy_rc']['name'] != "") {
                    $bus_copy_rc = cifileupload("cur_business_copy_rc");
                    $cur_business_copy_rc_card = $bus_copy_rc["upload_status"] ? $bus_copy_rc["uploaded_path"] : null;
                }
            }
        }

        $uploadedFiles = array(
            "photo" => strlen($photo) ? $photo : '',
            "signature" => strlen($sign) ? $sign : '',
            "identity_proof" => strlen($identity_proof_card) ? $identity_proof_card : '',
            "address_proof" => strlen($address_proof_card) ? $address_proof_card : '',
            "house_tax_reciept" => strlen($house_tax_reciept_card) ? $house_tax_reciept_card : '',
            "room_rent_deposite" => strlen($room_rent_deposite_card) ? $room_rent_deposite_card : '',
            "consideration_letter" => strlen($consideration_letter_card) ? $consideration_letter_card : '',
            "cur_business_copy_rc" => strlen($cur_business_copy_rc_card) ? $cur_business_copy_rc_card : '',

        );

            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $this->queryform($objId);
            } else {

                $postdata = array(

                );


                $data = array(
                    'form_data.photo_type' => $this->input->post("photo_type"),
                    'form_data.photo' => $photo,
                    'form_data.signature_type' => $this->input->post("signature_type"),
                    'form_data.signature' => $sign,
                    'form_data.identity_proof_type' => $this->input->post("identity_proof_type"),
                    'form_data.identity_proof' => $identity_proof_card,
                    'form_data.address_proof_type' => $this->input->post("address_proof_type"),
                    'form_data.address_proof' => $address_proof_card,
                    'form_data.house_tax_reciept_type' => $this->input->post("house_tax_reciept_type"),
                    'form_data.house_tax_reciept' => $house_tax_reciept_card,
                    'form_data.room_rent_deposite_type' => $this->input->post("room_rent_deposite_type"),
                    'form_data.room_rent_deposite' => $room_rent_deposite_card,
                    'form_data.consideration_letter_type' => $this->input->post("consideration_letter_type"),
                    'form_data.consideration_letter' => $consideration_letter_card,
                    'form_data.cur_business_copy_rc_type' => $this->input->post("cur_business_copy_rc_type"),
                    'form_data.cur_business_copy_rc' => $cur_business_copy_rc_card,
                );

                //query submit pending
                $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $data);
                $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);

                //end query submit


                $address_prooff = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->address_proof));
                $address_proof = array(
                    "encl" => $address_prooff,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->address_proof_type,
                    "enclType" => $dbRow->form_data->address_proof_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['address_proof'] = $address_proof;

                $identity_prooff = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->identity_proof));
                $identity_proof = array(
                    "encl" => $identity_prooff,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->identity_proof_type,
                    "enclType" => $dbRow->form_data->identity_proof_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['identity_proof'] = $identity_proof;

               

               

                $house_tax_recieptf = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->house_tax_reciept));
                $house_tax_reciept = array(
                    "encl" => $house_tax_recieptf,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->house_tax_reciept_type,
                    "enclType" => $dbRow->form_data->house_tax_reciept_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['mbtc_tax_receipt'] = $house_tax_reciept;
                // "mbtc_room_rent" => $dbRow->form_data->room_rent_deposite,
                // "special_reason_letter" => $dbRow->form_data->consideration_letter,
                // "softcopy_application" => $dbRow->form_data->father_title,
                $room_rent_depositef = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->room_rent_deposite));
                $room_rent_deposite = array(
                    "encl" => $room_rent_depositef,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->room_rent_deposite_type,
                    "enclType" => $dbRow->form_data->room_rent_deposite_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['mbtc_rent_deposit'] = $room_rent_deposite;
                // $postdata['softcopy_application'] = $room_rent_deposite;

                $consideration_letterf = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->consideration_letter));
                $consideration_letter = array(
                    "encl" => $consideration_letterf,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->consideration_letter_type,
                    "enclType" => $dbRow->form_data->consideration_letter_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['special_consideration_letter'] = $consideration_letter;

                

                $spId = array(
                    "applId" => $dbRow->service_data->appl_id,
                );

                $postdata['spId'] = $spId;

                    // $processing_history = $dbRow->processing_history ?? array();
                    // $processing_history[] = array(
                    //     "processed_by" => "Query submitted by " . $dbRow->form_data->applicant_name,
                    //     "action_taken" => "Query submitted",
                    //     "remarks" => "Query submitted by " . $dbRow->form_data->applicant_name,
                    //     "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    // );
                    // $data["service_data.appl_status"] = "QA";
                    // $data["status"] = "QUERY_ANSWERED";
                    // $data["processing_history"] = $processing_history;
                    // $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $data);
                    // $this->session->set_flashdata('success', 'Your application has been successfully updated.');

                    // $this->my_transactions();
                    // exit();
                $url = $this->config->item('kaac_post_url');
                if ($dbRow->form_data->service_id == "KBRC") {
                        $curl = curl_init($url . "municipal/BRC/update_certicopy.php");
                    } elseif ($dbRow->form_data->service_id == "KRBC") {
                        $curl = curl_init($url . "municipal/RBRC/update_certicopy.php");
                    } else {
                        $this->my_transactions();
                    }
                
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                curl_close($curl);
                // pre($response);

                log_response($dbRow->service_data->appl_ref_no, $response);

                if ($response) {
                    if ($response->ref->status === "success") {

                        $processing_history = $dbRow->processing_history ?? array();
                        $processing_history[] = array(
                            "processed_by" => "Query submitted by " . $dbRow->form_data->applicant_name,
                            "action_taken" => "Query submitted",
                            "remarks" => "Query submitted by " . $dbRow->form_data->applicant_name,
                            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        );
                        $data["service_data.appl_status"] = "QA";
                        $data["status"] = "QUERY_ANSWERED";
                        $data["processing_history"] = $processing_history;
                        $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $data);
                        $this->session->set_flashdata('success', 'Your application has been successfully updated.');

                        $this->my_transactions();
                        exit();

                        
                    }else{

                        $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                        $this->my_transactions();
                        return;
                    }

                }
                redirect('spservices/kaac/registration/preview/' . $objId);
            } //End of if else
        } else {
            $this->session->set_flashdata('fail', 'Unable to update data, please try again!');
            $this->queryform($objId);
        }
    } //End of querysubmit()

    public function submit_to_backend($obj, $show_ack = false)
    {
        if ($obj) {
            $dbRow = $this->kaac_registration_model->get_by_doc_id($obj);

            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }
            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")) {

                //procesing data
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_title . ' ' . $dbRow->form_data->first_name . ' ' . $dbRow->form_data->last_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by " . $dbRow->form_data->first_name . " " . $dbRow->form_data->last_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $postdata = array(
                    "application_ref_no" => $dbRow->service_data->appl_ref_no,
                    "applicant_prefix" => $dbRow->form_data->applicant_title,
                    "applicant_first_name" => $dbRow->form_data->first_name,
                    "applicant_last_name" => $dbRow->form_data->last_name,
                    "father_name" => $dbRow->form_data->father_title . ' ' . $dbRow->form_data->father_name,
                    "gender" => $dbRow->form_data->applicant_gender,
                    "applicant_mobile_no" => $dbRow->form_data->mobile,
                    "firm_name" => $dbRow->form_data->name_of_firm,
                    "proprietor_name" => $dbRow->form_data->name_of_proprietor,
                    "community" => $dbRow->form_data->community,
                    "occupation" => $dbRow->form_data->occupation_trade,
                    "address" => $dbRow->form_data->address,
                    "business_class" => $dbRow->form_data->class_of_business,
                    "special_reason" => $dbRow->form_data->reason_for_consideration,
                    "applicant_email" => $dbRow->form_data->email,
                    "caste" => $dbRow->form_data->caste,
                    "aadharNo" => $dbRow->form_data->aadhar_no,
                    "district" => $dbRow->form_data->district,
                    "postOffice" => $dbRow->form_data->post_office,
                    "policeStation" => $dbRow->form_data->police_station,
                    "business_location_locality" => $dbRow->form_data->business_locality,
                    "business_location_ward" => $dbRow->form_data->business_word_no,
                    "buildingRentedOwned" => $dbRow->form_data->rented_owned,
                    "rentOwnerName" => $dbRow->form_data->name_of_owner,

                );

                // "address_proof" => $dbRow->form_data->address_proof,
                // "identity_proof" => $dbRow->form_data->identity_proof,
                // "passport_photo" => $dbRow->form_data->photo,
                // "signature" => $dbRow->form_data->signature,
                // "house_tax_receipt" => $dbRow->form_data->house_tax_reciept,
                $address_prooff = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->address_proof));
                $address_proof = array(
                    "encl" => $address_prooff,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->address_proof_type,
                    "enclType" => $dbRow->form_data->address_proof_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['address_proof'] = $address_proof;

                $identity_prooff = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->identity_proof));
                $identity_proof = array(
                    "encl" => $identity_prooff,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->identity_proof_type,
                    "enclType" => $dbRow->form_data->identity_proof_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['identity_proof'] = $identity_proof;

                $photof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->photo));
                $photo = array(
                    "encl" => $photof,
                    "docType" => "application/jpg",
                    "enclFor" => $dbRow->form_data->photo_type,
                    "enclType" => $dbRow->form_data->photo_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "jpg",
                );
                $postdata['passport_photo'] = $photo;

                $signaturef = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->signature));
                $signature = array(
                    "encl" => $signaturef,
                    "docType" => "application/jpg",
                    "enclFor" => $dbRow->form_data->signature_type,
                    "enclType" => $dbRow->form_data->signature_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "jpg",
                );
                $postdata['signature'] = $signature;

                $house_tax_recieptf = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->house_tax_reciept));
                $house_tax_reciept = array(
                    "encl" => $house_tax_recieptf,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->house_tax_reciept_type,
                    "enclType" => $dbRow->form_data->house_tax_reciept_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['house_tax_receipt'] = $house_tax_reciept;
                // "mbtc_room_rent" => $dbRow->form_data->room_rent_deposite,
                // "special_reason_letter" => $dbRow->form_data->consideration_letter,
                // "softcopy_application" => $dbRow->form_data->father_title,
                $room_rent_depositef = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->room_rent_deposite));
                $room_rent_deposite = array(
                    "encl" => $room_rent_depositef,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->room_rent_deposite_type,
                    "enclType" => $dbRow->form_data->room_rent_deposite_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['mbtc_room_rent'] = $room_rent_deposite;
                // $postdata['softcopy_application'] = $room_rent_deposite;

                $consideration_letterf = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->consideration_letter));
                $consideration_letter = array(
                    "encl" => $consideration_letterf,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->consideration_letter_type,
                    "enclType" => $dbRow->form_data->consideration_letter_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['special_reason_letter'] = $consideration_letter;

                if ($dbRow->form_data->service_id == "KRBC") {

                    $cur_business_copy_rcf = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->cur_business_copy_rc));
                    $cur_business_copy_rc = array(
                        "encl" => $cur_business_copy_rcf,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->cur_business_copy_rc_type,
                        "enclType" => $dbRow->form_data->cur_business_copy_rc_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf",
                    );
                    $postdata['current_business_reg_certificate'] = $cur_business_copy_rc;
                }

                $spId = array(
                    "applId" => $dbRow->service_data->appl_id,
                );

                $postdata['spId'] = $spId;

                //delete below these lines- for testing only

                // $data_to_update = array(
                //     'service_data.appl_status' => 'submitted',
                //     'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                //     'processing_history' => $processing_history,
                // );
                // $this->kaac_registration_model->update($obj, $data_to_update);
                // redirect('spservices/kaac_brc/registration/acknowledgement/' . $obj);
                //delete these lines- for testing only

                //for testing only

                // pre(json_encode($postdata));

                //remove the comments for production
                $url = $this->config->item('kaac_post_url');
                if ($dbRow->form_data->service_id == "KBRC") {
                    $curl = curl_init($url . "municipal/BRC/post_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "KRBC") {
                    $curl = curl_init($url . "municipal/RBRC/post_certicopy.php");
                } else {
                    $this->my_transactions();
                }
                // pre($url . "mutation/certified_copy/post_certicopy.php");
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
                            'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                            'processing_history' => $processing_history
                        );
                        $this->kaac_registration_model->update($obj, $data_to_update);

                        //Sending submission SMS
                        $nowTime = date('Y-m-d H:i:s');
                        $sms = array(
                            "mobile" => (int)$dbRow->form_data->mobile,
                            "applicant_name" => $dbRow->form_data->applicant_title.' '.$dbRow->form_data->first_name.' '.$dbRow->form_data->last_name,
                            "service_name" => $dbRow->service_data->service_name,
                            "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                            "app_ref_no" => $dbRow->service_data->appl_ref_no
                        );
                        sms_provider("submission", $sms);
                        redirect('spservices/kaac_brc/registration/acknowledgement/' . $obj);
                    } else {
                        $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                        $this->my_transactions();
                        return;
                    }

                } else {
                    $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                    $this->my_transactions();
                    return;
                }

                // uncomment till here
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
        $this->preview($objId);
        // $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);
        // if ($dbRow->form_data->service_id == "KBRC") {
        //     $data = array(
        //         "pageTitleId" => "KBRC",
        //         "pageTitle" => "Application Form for Issuance Of Business Registration Certificate",
        //         "pageTitleAssamese" => "ব্যৱসায় পঞ্জীয়ন প্ৰমাণ পত্ৰ প্ৰদানৰ বাবে আবেদন পত্ৰ",
        //         "dbrow" => $dbRow,
        //         "user_type" => $this->slug,
        //         "preview"=> 1

        //     );

        // } else if ($dbRow->form_data->service_id == "KRBC") {
        //     $data = array(
        //         "pageTitleId" => "KRBC",
        //         "pageTitle" => "Application Form for Renewal of Business Registration Certificate",
        //         "pageTitleAssamese" => "ব্যৱসায় পঞ্জীয়ন প্ৰমাণ পত্ৰ নবীকৰণৰ বাবে আবেদন পত্ৰ",
        //         "dbrow" => $dbRow,
        //         "user_type" => $this->slug,
        //     );

        // }
        // if (count((array) $dbRow)) {
        //     $this->load->view('includes/frontend/header');
        //     $this->load->view('kaac/sec_group/kaac_preview_view', $data);
        //     $this->load->view('includes/frontend/footer');
        // } else {
        // $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
        // redirect('spservices/kaccc_brc/registration/');
        // }
    } //End of preview()

    public function download_certificate($objId = null)
    {
        if (!empty($objId)) {
            $dbRowArray = array();
            $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);
            $dbRowArray[] = $dbRow;
            $qrcode_path = 'storage/docs/common_qr/';
            $filename = str_replace("/", "-", $objId);
            $qrname = $filename . ".png";
            $file_name = $qrcode_path . $qrname;

            $filter1 = array(
                "registration_number" => $dbRow->form_data->ahsec_reg_no,
                "registration_session" => $dbRow->form_data->ahsec_reg_session,
            );
            $ahsecregistration_data = $this->ahseckaac_registration_model->get_row($filter1);
            if ($dbRow->service_data->service_id == "AHSECCADM" || $dbRow->service_data->service_id == "AHSECCMRK" || $dbRow->service_data->service_id == "AHSECCPC") {
                $filter2 = array(
                    "Roll" => $dbRow->form_data->ahsec_admit_roll,
                    "No" => $dbRow->form_data->ahsec_admit_no,
                );
                $ahsecmarksheet_data = $this->ahsecmarksheet_model->get_row($filter2);

                $marksheet_data['Mark_Sheet_No'] = $ahsecmarksheet_data->Mark_Sheet_No;
                $marksheet_data['Stream'] = $ahsecmarksheet_data->Stream;
                $marksheet_data['Date'] = $ahsecmarksheet_data->Date;
                $marksheet_data['Candidate_Name'] = $ahsecmarksheet_data->Candidate_Name;
                $marksheet_data['School_Name'] = $ahsecmarksheet_data->School_Name;
                $marksheet_data['Roll'] = $ahsecmarksheet_data->Roll;
                $marksheet_data['No'] = $ahsecmarksheet_data->No;
                $marksheet_data['Remarks1'] = $ahsecmarksheet_data->Remarks1;
                $j = 1;
                for ($i = 1; $i <= 16; $i++) {
                    if (!empty($ahsecmarksheet_data->{'Sub' . $i . '_Code'})) {
                        $marksheet_data['Sub' . $j . '_Code'] = $ahsecmarksheet_data->{'Sub' . $i . '_Code'};
                        $marksheet_data['Sub' . $j . '_Pap_Indicator'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pap_Indicator'};
                        $marksheet_data['Sub' . $j . '_Name'] = $ahsecmarksheet_data->{'Sub' . $i . '_Name'};
                        $marksheet_data['Sub' . $j . '_Th_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Th_Marks'};
                        $marksheet_data['Sub' . $j . '_Pr_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Pr_Marks'};
                        $marksheet_data['Sub' . $j . '_Tot_Marks'] = $ahsecmarksheet_data->{'Sub' . $i . '_Tot_Marks'};
                        $j++;
                    }
                }
                $marksheet_data['Total_Marks_in_Words'] = $ahsecmarksheet_data->Total_Marks_in_Words;
                $marksheet_data['Total_Marks_in_Figure'] = $ahsecmarksheet_data->Total_Marks_in_Figure;
                $marksheet_data['Total_Grace_in_Figure'] = $ahsecmarksheet_data->Total_Grace_in_Figure;
                $marksheet_data['Result'] = $ahsecmarksheet_data->Result;
                $marksheet_data['Remarks2'] = $ahsecmarksheet_data->Remarks2;
                $marksheet_data['ENVE_Grade'] = $ahsecmarksheet_data->ENVE_Grade;
                $marksheet_data['Core_Indicator'] = $ahsecmarksheet_data->Core_Indicator;
            }
            if ((($dbRowArray[0]->service_data->service_id == 'AHSECCRC') || ($dbRowArray[0]->service_data->service_id == 'AHSECCADM') || ($dbRowArray[0]->service_data->service_id == 'AHSECCMRK') || ($dbRowArray[0]->service_data->service_id == 'AHSECCPC')) && $dbRowArray[0]->service_data->appl_status == "D") {
                $this->load->view('includes/frontend/header');
                if ($dbRowArray[0]->service_data->service_id == 'AHSECCRC') {
                    $this->load->view('ahsec_correction/output_certificate_rc', array('data' => $dbRowArray, 'qr' => $file_name, 'certificate_no' => $dbRowArray[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'user_type' => 'citizen'));
                } else if ($dbRowArray[0]->service_data->service_id == 'AHSECCADM') {
                    $this->load->view('ahsec_correction/output_certificate_adm', array('data' => $dbRowArray, 'qr' => $file_name, 'certificate_no' => $dbRowArray[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'citizen'));
                } else if ($dbRowArray[0]->service_data->service_id == 'AHSECCMRK') {
                    $this->load->view('ahsec_correction/output_certificate_mrk', array('data' => $dbRowArray, 'qr' => $file_name, 'certificate_no' => $dbRowArray[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'citizen'));
                } else if ($dbRowArray[0]->service_data->service_id == 'AHSECCPC') {
                    $this->load->view('ahsec_correction/output_certificate_pc', array('data' => $dbRowArray, 'qr' => $file_name, 'certificate_no' => $dbRowArray[0]->form_data->certificate_no, 'certificate_path' => null, 'reg_data' => $ahsecregistration_data, 'marksheet_data' => $marksheet_data, 'user_type' => 'citizen'));
                }
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('errmessage', 'Something went wrong!, Please try later.');
                $this->my_transactions();
            }
        }
    } //End of download_certificate()

    public function acknowledgement($objid)
    {
        $applicationRow = $this->kaac_registration_model->get_by_doc_id($objid);
        // pre($applicationRow);
        // pre($applicationRow->form_data->service_id);
        if ($applicationRow) {
            if (isset($applicationRow->service_id)) {
                $service_id = $applicationRow->service_id;
                $data['response'] = $applicationRow;
            } else {
                // for formated data
                $service_id = $applicationRow->form_data->service_id;

                // if ($service_id == 'apdcl1') {
                //     $rtps_trans_id = $applicationRow->service_data->appl_ref_no." and APDCL Application No.: ".$applicationRow->form_data->application_no;
                // }elseif (($service_id == 'CASTE') || ($service_id == 'INC') || ($service_id == 'PDDR') || ($service_id == 'NOKIN') || ($service_id == 'SCTZN') || ($service_id == 'PDBR') || ($service_id == 'BAKCL')){
                //     $rtps_trans_id = (!empty($applicationRow->form_data->edistrict_ref_no))? $applicationRow->service_data->appl_ref_no." and eDistrict Ref. No.: ".$applicationRow->form_data->edistrict_ref_no: $applicationRow->service_data->appl_ref_no;
                // } else {
                $rtps_trans_id = $applicationRow->service_data->appl_ref_no;
                // }

                $applicationRowData = [
                    "rtps_trans_id" => $rtps_trans_id,
                    "submission_date" => $applicationRow->service_data->submission_date,
                    "applicant_first_name" => $applicationRow->form_data->first_name,
                    "applicant_last_name" => $applicationRow->form_data->last_name,
                    "service_name" => $applicationRow->service_data->service_name,
                    "application_no" => isset($applicationRow->form_data->application_no) ? $applicationRow->form_data->application_no : '',
                    "service_charge" => isset($applicationRow->form_data->service_charge) ? $applicationRow->form_data->service_charge : "",
                    "rtps_convenience_fee" => isset($applicationRow->form_data->convenience_fee) ? $applicationRow->form_data->convenience_fee : "",
                    "no_printing_page" => isset($applicationRow->form_data->no_printing_page) ? $applicationRow->form_data->no_printing_page : "",
                    "printing_charge_per_page" => isset($applicationRow->form_data->printing_charge_per_page) ? $applicationRow->form_data->printing_charge_per_page : "",
                    "no_scanning_page" => isset($applicationRow->form_data->no_scanning_page) ? $applicationRow->form_data->no_scanning_page : "",
                    "scanning_charge_per_page" => isset($applicationRow->form_data->scanning_charge_per_page) ? $applicationRow->form_data->scanning_charge_per_page : "",
                    "pfc_payment_status" => isset($applicationRow->form_data->pfc_payment_status) ? $applicationRow->form_data->pfc_payment_status : "",

                ];

                if (isset($applicationRow->form_data->application_charge)) {
                    if ($applicationRow->form_data->application_charge != "0") {
                        $applicationRowData['amount'] = $applicationRow->form_data->application_charge;
                    }
                }

                $data['response'] = (object) $applicationRowData;
            }
            // pre($data['response']);
            $data['service_row'] = $this->services_model->get_row(array("service_id" => $service_id));
            // $data['back_to_dasboard'] = '<a href="' . base_url('spservices/applications/') . '" class="btn btn-primary mb-2 pull-right"  >Back To Dashboard</a>';
            $data['pageTitle'] = "Acknowledgement";
            $this->load->view('includes/frontend/header');
            $this->load->view('ack', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            redirect('spservices/applications/');
        } //End of if else
    }

    public function query_payment_break_down($obj_id = null)
    {
        // $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});

        $filter = array(
            "_id" => $obj_id ? new ObjectId($obj_id) : null,
            "service_data.appl_status" => "FRS",
        );

        $dbrow = $this->kaac_registration_model->get_row($filter);

        if (!empty($dbrow)) {
            $data['obj_id'] = $obj_id;
            $data['amount'] = $dbrow->form_data->frs_request->amount;
        } else {
            $this->my_transactions();
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('kaac/sec_group/query_charge_template', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function querypaymentsubmit($obj = null)
    {
        if ($obj) {
            $dbRow = $this->kaac_registration_model->get_by_doc_id($obj);
            if (count((array) $dbRow)) {

                if ($dbRow->service_data->appl_status == "QA") {
                    $this->my_transactions();
                }
                $spId = array(
                    "applId" => $dbRow->service_data->appl_id,
                );
                $postdata = array(
                    "application_ref_no" => $dbRow->service_data->appl_ref_no,
                    "fee_paid" => $dbRow->form_data->query_payment_response->AMOUNT,
                    "payment_mode" => "Online",
                    "payment_ref_number" => $dbRow->form_data->query_payment_response->GRN,
                    "spId" => $spId,

                );
                
                // $processing_history = $dbRow->processing_history ?? array();
                // $processing_history[] = array(
                //     "processed_by" => "Payment Query submitted by " . $dbRow->form_data->applicant_name,
                //     "action_taken" => "Payment Query submitted",
                //     "remarks" => "Payment Query submitted by " . $dbRow->form_data->applicant_name,
                //     "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                // );

                // $data = array(
                //     "service_data.appl_status" => "QA",
                //     'processing_history' => $processing_history,
                // );

                // $this->kaac_registration_model->update_where(['_id' => new ObjectId($obj)], $data);
                // redirect('spservices/kaac_brc/registration/payment_acknowledgement/' . $obj);
                //uncomment these lines for production start
                $url = $this->config->item('kaac_post_url');
                if ($dbRow->form_data->service_id == "KBRC") {
                    $curl = curl_init($url . "municipal/BRC/update_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "KRBC") {
                    $curl = curl_init($url . "municipal/RBRC/update_certicopy.php");
                } else {
                    $this->my_transactions();
                }
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                curl_close($curl);

                log_response($dbRow->service_data->appl_ref_no, $response);

                if (isset($error_msg)) {
                    die("CURL ERROR : " . $error_msg);
                }
                elseif ($response)
                {

                    if ($response->ref->status === "success") {
                        $response = json_decode($response, true); //pre($response);
                        $processing_history = $dbRow->processing_history ?? array();
                        $processing_history[] = array(
                            "processed_by" => "Payment Query submitted by " . $dbRow->form_data->first_name . " " . $dbRow->form_data->last_name,
                            "action_taken" => "Payment Query submitted",
                            "remarks" => "Payment Query submitted by " . $dbRow->form_data->applicant_name,
                            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        );

                        $data = array(
                            "service_data.appl_status" => "QA",
                            'processing_history' => $processing_history,
                        );

                        $this->kaac_registration_model->update_where(['_id' => new ObjectId($obj)], $data);
                        redirect('spservices/kaac_brc/registration/payment_acknowledgement/' . $obj);
                    }else{
                        $this->session->set_flashdata('errmessage', 'Unable to payment query with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                        $this->my_transactions();
                        return;
                        }
                                                
                } else {
                    $this->session->set_flashdata('errmessage', 'Unable to update data!!! Please try again.');
                    $this->my_transactions();
                } //End of if else

                //uncomment these lines for production end
            }

            $this->my_transactions();
        }
    }

    public function payment_acknowledgement($obj_id = null)
    {
        // $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});

        // if (isset($user->type_of_kiosk)) {
        //     if ($user->type_of_kiosk == "eDistrict") {
        //         // pre($user->type_of_kiosk);
        //         $this->my_transactions();
        //     }
        // }

        $filter = array(
            "_id" => $obj_id ? new ObjectId($obj_id) : null,
        );

        $dbrow = $this->kaac_registration_model->get_row($filter);
        $data['dbrow'] = $dbrow;

        $this->load->view('includes/frontend/header');
        $this->load->view('kaac/applications/ack', $data);
        $this->load->view('includes/frontend/footer');
    }
}
