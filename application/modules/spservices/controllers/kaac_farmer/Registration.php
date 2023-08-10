<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps
{
    private $serviceId = "FCERT";
    private $base_serviceId = "1868";
    private $departmentName = "Karbi Anglong (AC)";
    private $departmentId = "2100";

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
            'field' => 'mobile',
            'label' => 'Applicant Mobile Number',
            'rules' => 'trim|required|integer|xss_clean|strip_tags|max_length[20]',
        ),
        array(
            'field' => 'middle_name',
            'label' => 'Applicant Middle Name',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[20]',
        ),
        array(
            'field' => 'last_name',
            'label' => 'Applicant Last Name',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
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
            'field' => 'aadhar_no',
            'label' => 'Aadhar Number',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[100]',
        ),
        array(
            'field' => 'email',
            'label' => 'Email Id',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[100]',
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
            'field' => 'bank_account_no',
            'label' => 'Bank Account No',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'bank_name',
            'label' => 'Bank Name',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'bank_branch',
            'label' => 'Bank Branch',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'ifsc_code',
            'label' => 'IFSC Code',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'land_district',
            'label' => 'District',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'land_district',
            'label' => 'Sub-Division',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'circle_office',
            'label' => 'Circle',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'mouza_name',
            'label' => 'Mouza',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'revenue_village',
            'label' => 'Revenue Village',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'dag_no',
            'label' => 'Dag No.',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'patta_no',
            'label' => 'Patta No.',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'name_of_pattadar',
            'label' => 'Name of Pattadar',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'pattadar_father_name',
            'label' => 'Pattadar Father Name',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'relationship_with_pattadar',
            'label' => 'Relationship with pattadar',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'land_category',
            'label' => 'Land Category',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'cultivated_land',
            'label' => 'Cultivated Land (In Bigha Only)',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'production',
            'label' => 'Production (In Quintals Only)',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'crop_variety',
            'label' => 'Crop Variety',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'cultivator_type',
            'label' => 'Cultivator Type',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'bigha',
            'label' => 'Bigha',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'kotha',
            'label' => 'Kotha',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'loosa',
            'label' => 'Loosa',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'land_area',
            'label' => 'Land Area',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),
        array(
            'field' => 'ado_circle_office',
            'label' => 'ADO Circle Office',
            'rules' => 'trim|required|xss_clean|strip_tags|max_length[255]',
        ),

    );

    public function index($obj_id = null)
    {
        // pre($obj_id);
        if ($obj_id) {

            try {
                $objectId = new ObjectId($obj_id);
                $filter = array("_id" => $objectId,
                    "service_data.appl_status" => "DRAFT",
                );

                $dbrow = $this->kaac_registration_model->get_row($filter);
                $data = array(
                    "pageTitleId" => $this->serviceId,
                    "pageTitle" => "Application Form for Farmer's Certificate",
                    "pageTitleAssamese" => "কৃষকৰ প্ৰমাণ পত্ৰৰ বাবে আবেদন পত্ৰ",
                    "dbrow" => $dbrow,
                );
            } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
                $this->my_transactions();
            }
        } else {

            $data = array(
                "pageTitleId" => $this->serviceId,
                "pageTitle" => "Application Form for Farmer's Certificate",
                "pageTitleAssamese" => "কৃষকৰ প্ৰমাণ পত্ৰৰ বাবে আবেদন পত্ৰ",
            );
            $data["dbrow"] = null;
        }

        $data['usser_type'] = $this->slug;
        $this->load->view('includes/frontend/header');
        $this->load->view('kaac/fifth_group/kaac_registration_form', $data);
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
        // pre("ghgh");
        $this->serviceId = $this->input->post("service_id");
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules($this->sectionone);
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == false) {
            // pre("hg");
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : '';
            $this->index($obj_id);
        } else {
            // pre("gj");
            $objId = $this->saveSectionOne($this->input->post());
            if (strlen($objId)) {
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/kaac_farmer/registration/fileuploads/' . $objId);
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
        
        $objId = $data['obj_id'];
        $sessionUser = $this->session->userdata();
        $submissionMode = $data['submission_mode'] ?? null;
        if ($this->slug === "CSC") {
            $apply_by = $sessionUser['userId'];
        } else {
            $objectId = new ObjectId($obj_id);//new MongoDB\BSON\ObjectId($objId);

            $filter = array(
                "_id" => $objectId,
                "service_data.appl_status" => "DRAFT",
            );
            $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        } //End of if else

        if ($data['service_id'] == $this->serviceId) {
        } else {
            $this->my_transactions();
            exit;
        }
        if (strlen($objId)) {

            $dbrow = $this->kaac_registration_model->get_row($filter);

            $form_data = [
                'form_data.applicant_title' => $data['applicant_title'] ?? null,
                'form_data.first_name' => $data['first_name'] ?? null,
                'form_data.middle_name' => $data['middle_name'] ?? null,
                'form_data.last_name' => $data['last_name'] ?? null,
                "form_data.service_id" => $this->serviceId,
                'form_data.applicant_name' => $data['applicant_title'] . ' ' . $data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name'],

                'form_data.mobile' => $data['mobile'],
                'form_data.email' => $data['email'],
                'form_data.applicant_gender' => $data['applicant_gender'],
                'form_data.caste' => $data['caste'],
                'form_data.father_title' => $data['father_title'],
                'form_data.father_name' => $data['father_name'],
                'form_data.aadhar_no' => $data['aadhar_no'],

                'form_data.district' => $data['district'],
                'form_data.district_name' => $data['district_name'],

                'form_data.police_station' => $data['police_station'],
                'form_data.post_office' => $data['post_office'],

                'form_data.bank_account_no' => $data['bank_account_no'],
                'form_data.bank_name' => $data['bank_name'],
                'form_data.bank_branch' => $data['bank_branch'],
                'form_data.ifsc_code' => $data['ifsc_code'],

                'form_data.land_district' => $data['land_district'],
                'form_data.sub_division' => $data['sub_division'],
                'form_data.circle_office' => $data['circle_office'],
                'form_data.mouza_name' => $data['mouza_name'],
                'form_data.revenue_village' => $data['revenue_village'],

                'form_data.land_district_name' => $data['land_district_name'],
                'form_data.sub_division_name' => $data['sub_division_name'],
                'form_data.circle_office_name' => $data['circle_office_name'],
                'form_data.mouza_office_name' => $data['mouza_office_name'],
                'form_data.revenue_village_name' =>$data['revenue_village_name'],
                'form_data.patta_type_name' => $data['patta_type_name'],



                'form_data.patta_type' => $data['patta_type'],
                'form_data.dag_no' => $data['dag_no'],
                'form_data.patta_no' => $data['patta_no'],
                'form_data.name_of_pattadar' => $data['name_of_pattadar'],
                'form_data.pattadar_father_name' => $data['pattadar_father_name'],

                'form_data.relationship_with_pattadar' => $data['relationship_with_pattadar'],
                'form_data.land_category' => $data['land_category'],
                'form_data.cultivated_land' => $data['cultivated_land'],
                'form_data.name_of_pattadar' => $data['name_of_pattadar'],
                'form_data.production' => $data['production'],
                'form_data.crop_variety' => $data['crop_variety'],
                'form_data.surplus_production' => $data['surplus_production'],
                'form_data.cultivator_type' => $data['cultivator_type'],
                'form_data.crop_variety_name' => $data['crop_variety_name'],
                'form_data.cultivator_type_name' => $data['cultivator_type_name'],
                'form_data.bigha' => $data['bigha'],
                'form_data.kotha' => $data['kotha'],
                'form_data.loosa' => $data['loosa'],
                'form_data.land_area' => $data['land_area'],
                'form_data.ado_circle_office' => $data['ado_circle_office'],
                'form_data.ado_circle_office_name' => $data['ado_circle_office_name'],
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


            if($data['circle_office'] == 1383118 ||$data['circle_office'] == 1383120 || $data['circle_office'] == 1383121){
                // pre("jfh");
                $this->districtName = "KARBI ANGLONG";
                $this->submmissionLocation = "Revenue Office KAAC(Revenue Office-KAAC- ".$data['circle_office_name'] ." )";
            }
            elseif($data['circle_office']  == 1383119){
                // pre("mvb");
                $this->districtName = "WEST KARBI ANGLONG";
                $this->submmissionLocation = "Revenue Office KAAC(Revenue Office-KAAC- ".$data['circle_office_name'] ." )";
            }
            $service_data = [
                "department_id" => $this->config->item('kaac_department_id'),
                "department_name" => $this->config->item('kaac_department_name'),
                "service_id" => $this->base_serviceId,
                "service_name" => "Issuance of Farmers Certificate - KAAC",
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "applied_by" => $apply_by,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "submission_location" => $this->submmissionLocation, // office name
                "submission_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "service_timeline" => "20",
                "appl_status" => "DRAFT",
                "district"=> $this->districtName
            ];

            $form_data = [
                'applicant_title' => $data['applicant_title'] ?? null,
                'first_name' => $data['first_name'] ?? null,
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                "service_id" => $this->serviceId,
                'applicant_name' => $data['applicant_title'] . ' ' . $data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name'],
                'mobile' => $data['mobile'],
                'email' => $data['email'],
                'applicant_gender' => $data['applicant_gender'],
                'caste' => $data['caste'],
                'father_title' => $data['father_title'],
                'father_name' => $data['father_name'],
                'aadhar_no' => $data['aadhar_no'],
                'district' => $data['district'],
                'district_name' => $data['district_name'],
                'police_station' => $data['police_station'],
                'post_office' => $data['post_office'],
                'bank_account_no' => $data['bank_account_no'],
                'bank_name' => $data['bank_name'],
                'bank_branch' => $data['bank_branch'],
                'ifsc_code' => $data['ifsc_code'],
                'land_district' => $data['land_district'],
                'sub_division' => $data['sub_division'],
                'circle_office' => $data['circle_office'],
                'mouza_name' => $data['mouza_name'],
                'revenue_village' => $data['revenue_village'],
                'patta_type' => $data['patta_type'],
                
                'land_district_name' => $data['land_district_name'],
                'sub_division_name' => $data['sub_division_name'],
                'circle_office_name' => $data['circle_office_name'],
                'mouza_office_name' => $data['mouza_office_name'],
                'revenue_village_name' => $data['revenue_village_name'],
                'patta_type_name' => $data['patta_type_name'],
                'crop_variety_name' => $data['crop_variety_name'],
                'cultivator_type_name' => $data['cultivator_type_name'],

                'dag_no' => $data['dag_no'],
                'patta_no' => $data['patta_no'],
                'name_of_pattadar' => $data['name_of_pattadar'],
                'pattadar_father_name' => $data['pattadar_father_name'],
                'relationship_with_pattadar' => $data['relationship_with_pattadar'],
                'land_category' => $data['land_category'],
                'cultivated_land' => $data['cultivated_land'],
                'name_of_pattadar' => $data['name_of_pattadar'],
                'production' => $data['production'],
                'crop_variety' => $data['crop_variety'],
                'surplus_production' => $data['surplus_production'],
                'cultivator_type' => $data['cultivator_type'],
                'bigha' => $data['bigha'],
                'kotha' => $data['kotha'],
                'loosa' => $data['loosa'],
                'land_area' => $data['land_area'],
                'ado_circle_office' => $data['ado_circle_office'],
                'ado_circle_office_name' => $data['ado_circle_office_name'],

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
            $this->load->view('kaac/fifth_group/kaac_fileuploads_view', $data);
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

        $this->form_validation->set_rules('photo_type', 'Passport size photograph type', 'required');
        $this->form_validation->set_rules('signature_type', 'Upload Farmer Signature/Thumb Impression type', 'required');
        $this->form_validation->set_rules('land_owner_signature_type', 'Land Owner Signature type', 'required');
        $this->form_validation->set_rules('bank_passbook_type', 'Photocopy of Bank Passbook of Applicant type', 'required');
        $this->form_validation->set_rules('aadhaar_card_type', 'Aadhar Card  type', 'required');
        $this->form_validation->set_rules('land_records_type', 'Certificate of Non-digitization/Non-integration of Land Records type', 'required');
        $this->form_validation->set_rules('land_document_type', 'Land Document type', 'required');
        $this->form_validation->set_rules('ncla_certificate_type', 'Certificate of Non-Cadastral Land Area type', 'required');

        $photo = '';
        $sign = '';
        $land_owner_signature_card = '';
        $bank_passbook_card = '';
        $aadhaar_card_card = '';
        $land_records_card = '';
        $land_document_card = '';
        $agreement_copy_card = '';
        $ncla_certificate_card = '';

        $sign = isset($dbRow->form_data->signature) ? $dbRow->form_data->signature : '';
        $land_owner_signature_card = isset($dbRow->form_data->land_owner_signature) ? $dbRow->form_data->land_owner_signature : '';
        $bank_passbook_card = isset($dbRow->form_data->bank_passbook) ? $dbRow->form_data->bank_passbook : '';
        $aadhaar_card_card = isset($dbRow->form_data->aadhaar_card) ? $dbRow->form_data->aadhaar_card : '';
        $land_records_card = isset($dbRow->form_data->land_records) ? $dbRow->form_data->land_records : '';
        $land_document_card = isset($dbRow->form_data->land_document) ? $dbRow->form_data->land_document : '';
        $agreement_copy_card = isset($dbRow->form_data->agreement_copy) ? $dbRow->form_data->agreement_copy : '';
        $ncla_certificate_card = isset($dbRow->form_data->ncla_certificate) ? $dbRow->form_data->ncla_certificate : '';

        if (empty($this->input->post("photo_old"))) {
            if ((empty($this->input->post("photo_data"))) && ($_FILES['photo']['name'] == "")) {
                $this->form_validation->set_rules('photo', 'Photo is Required', 'required');
            }
        }

        if ($sign == null && $_FILES['signature']['name'] == "") {
            $this->form_validation->set_rules('signature', 'Signature is Required', 'required');
        }
        if ($land_owner_signature_card == null && $_FILES['land_owner_signature']['name'] == "") {
            $this->form_validation->set_rules('land_owner_signature', 'Land Owner Signature is Required', 'required');
        }

        if (empty($this->input->post("bank_passbook_old"))) {
            if (((empty($this->input->post("bank_passbook_type"))) && (($_FILES['bank_passbook']['name'] != "") || (!empty($this->input->post("bank_passbook_temp"))))) || ((!empty($this->input->post("bank_passbook_type"))) && (($_FILES['bank_passbook']['name'] == "") && (empty($this->input->post("bank_passbook_temp")))))) {
                $this->form_validation->set_rules('bank_passbook', 'Photocopy of Bank Passbook of Applicant', 'required');
            }
        }

        if (empty($this->input->post("aadhaar_card_old"))) {
            if (((empty($this->input->post("aadhaar_card_type"))) && (($_FILES['aadhaar_card']['name'] != "") || (!empty($this->input->post("aadhaar_card_temp"))))) || ((!empty($this->input->post("aadhaar_card_type"))) && (($_FILES['aadhaar_card']['name'] == "") && (empty($this->input->post("aadhaar_card_temp")))))) {

                $this->form_validation->set_rules('aadhaar_card', 'Aadhar Card', 'required');
            }
        }

        if (empty($this->input->post("land_records_old"))) {
            if (((empty($this->input->post("land_records_type"))) && (($_FILES['land_records']['name'] != "") || (!empty($this->input->post("land_records_temp"))))) || ((!empty($this->input->post("land_records_type"))) && (($_FILES['land_records']['name'] == "") && (empty($this->input->post("land_records_temp")))))) {

                $this->form_validation->set_rules('land_records', 'Certificate of Non-digitization/Non-integration of Land Records', 'required');
            }
        }

        if (empty($this->input->post("land_document_old"))) {
            if (((empty($this->input->post("land_document_type"))) && (($_FILES['land_document']['name'] != "") || (!empty($this->input->post("land_document_temp"))))) || ((!empty($this->input->post("land_document_type"))) && (($_FILES['land_document']['name'] == "") && (empty($this->input->post("land_document_temp")))))) {

                $this->form_validation->set_rules('land_document', 'Land Document', 'required');
            }
        }
        if (empty($this->input->post("ncla_certificate_old"))) {
            if (((empty($this->input->post("ncla_certificate_type"))) && (($_FILES['ncla_certificate']['name'] != "") || (!empty($this->input->post("ncla_certificate_temp"))))) || ((!empty($this->input->post("ncla_certificate_type"))) && (($_FILES['ncla_certificate']['name'] == "") && (empty($this->input->post("ncla_certificate_temp")))))) {

                $this->form_validation->set_rules('ncla_certificate', 'Certificate of Non-Cadastral Land Area', 'required');
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
            $dirPath = 'storage/docs/kacc_farmer/photos/';
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
        if ($_FILES['land_owner_signature']['name'] != "") {
            $lo_signature = cifileupload("land_owner_signature");
            $land_owner_signature_card = $lo_signature["upload_status"] ? $lo_signature["uploaded_path"] : null;
        }
        if (strlen($this->input->post("bank_passbook_temp")) > 0) {
            $bp = movedigilockerfile($this->input->post('bank_passbook_temp'));
            $bank_passbook_card = $bp["upload_status"] ? $bp["uploaded_path"] : null;
        } else {
            if ($_FILES['bank_passbook']['name'] != "") {
                $bp = cifileupload("bank_passbook");
                $bank_passbook_card = $bp["upload_status"] ? $bp["uploaded_path"] : null;
            }
        }

        if (strlen($this->input->post("aadhaar_card_temp")) > 0) {
            $aadhr = movedigilockerfile($this->input->post('aadhaar_card_temp'));
            $aadhaar_card_card = $aadhr["upload_status"] ? $aadhr["uploaded_path"] : null;
        } else {
            if ($_FILES['aadhaar_card']['name'] != "") {
                $aadhr = cifileupload("aadhaar_card");
                $aadhaar_card_card = $aadhr["upload_status"] ? $aadhr["uploaded_path"] : null;
            }
        }

        if (strlen($this->input->post("land_records_temp")) > 0) {
            $lr = movedigilockerfile($this->input->post('land_records_temp'));
            $land_records_card = $lr["upload_status"] ? $lr["uploaded_path"] : null;
        } else {
            if ($_FILES['land_records']['name'] != "") {
                $lr = cifileupload("land_records");
                $land_records_card = $lr["upload_status"] ? $lr["uploaded_path"] : null;
            }
        }

        if (strlen($this->input->post("land_document_temp")) > 0) {
            $ld = movedigilockerfile($this->input->post('land_document_temp'));
            $land_document_card = $ld["upload_status"] ? $ld["uploaded_path"] : null;
        } else {
            if ($_FILES['land_document']['name'] != "") {
                $ld = cifileupload("land_document");
                $land_document_card = $ld["upload_status"] ? $ld["uploaded_path"] : null;
            }
        }

        if (strlen($this->input->post("agreement_copy_temp")) > 0) {
            $ac = movedigilockerfile($this->input->post('agreement_copy_temp'));
            $agreement_copy_card = $ac["upload_status"] ? $ac["uploaded_path"] : null;
        } else {
            if ($_FILES['agreement_copy']['name'] != "") {
                $ac = cifileupload("agreement_copy");
                $agreement_copy_card = $ac["upload_status"] ? $ac["uploaded_path"] : null;
            }
        }

        if (strlen($this->input->post("ncla_certificate_temp")) > 0) {
            $ac = movedigilockerfile($this->input->post('ncla_certificate_temp'));
            $ncla_certificate_card = $ac["upload_status"] ? $ac["uploaded_path"] : null;
        } else {
            if ($_FILES['ncla_certificate']['name'] != "") {
                $ac = cifileupload("ncla_certificate");
                $ncla_certificate_card = $ac["upload_status"] ? $ac["uploaded_path"] : null;
            }
        }

        $uploadedFiles = array(
            "photo" => strlen($photo) ? $photo : '',
            "signature" => strlen($sign) ? $sign : '',
            "land_owner_signature" => strlen($land_owner_signature_card) ? $land_owner_signature_card : $land_owner_signature_card,
            "bank_passbook" => strlen($bank_passbook_card) ? $bank_passbook_card : '',
            "aadhaar_card" => strlen($aadhaar_card_card) ? $aadhaar_card_card : '',
            "land_records" => strlen($land_records_card) ? $land_records_card : '',
            "land_document" => strlen($land_document_card) ? $land_document_card : '',
            "agreement_copy" => strlen($agreement_copy_card) ? $agreement_copy_card : '',
            "ncla_certificate" => strlen($ncla_certificate_card) ? $ncla_certificate_card : '',

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
                'form_data.land_owner_signature_type' => $this->input->post("land_owner_signature_type"),
                'form_data.land_owner_signature' => $land_owner_signature_card,
                'form_data.aadhaar_card_type' => $this->input->post("aadhaar_card_type"),
                'form_data.aadhaar_card' => $aadhaar_card_card,
                'form_data.bank_passbook_type' => $this->input->post("bank_passbook_type"),
                'form_data.bank_passbook' => $bank_passbook_card,
                'form_data.land_records_type' => $this->input->post("land_records_type"),
                'form_data.land_records' => $land_records_card,
                'form_data.land_document_type' => $this->input->post("land_document_type"),
                'form_data.land_document' => $land_document_card,
                'form_data.agreement_copy_type' => $this->input->post("agreement_copy_type"),
                'form_data.agreement_copy' => $agreement_copy_card,
                'form_data.ncla_certificate_type' => $this->input->post("ncla_certificate_type"),
                'form_data.ncla_certificate' => $ncla_certificate_card,

            );

            $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $data);
            redirect('spservices/kaac_farmer/registration/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()

    public function preview($objId = null)
    {
        $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);

        $data = array(
            "pageTitleId" => $this->serviceId,
            "pageTitle" => "Application Form for Farmer's Certificate",
            "pageTitleAssamese" => "কৃষকৰ প্ৰমাণ পত্ৰৰ বাবে আবেদন পত্ৰ",
            "dbrow" => $dbRow,
            "user_type" => $this->slug,
        );

        if (count((array) $dbRow)) {
            $this->load->view('includes/frontend/header');
            $this->load->view('kaac/fifth_group/kaac_preview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaac_farmer/registration/');
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
            $this->load->view('kaac/fifth_group/kaac_track_view', $data);
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

            $data = array(
                "pageTitleId" => "KBRC",
                "pageTitle" => "Application Form for Farmer's Certificate",
                "pageTitleAssamese" => "কৃষকৰ প্ৰমাণ পত্ৰৰ বাবে আবেদন পত্ৰ",
                "dbrow" => $dbRow,
                "user_type" => $this->slug,
            );

            if ($dbRow) {

                $this->load->view('includes/frontend/header');
                $this->load->view('kaac/fifth_group/kaac_query_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/kaac_farmer/registration');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/kaac_farmer/registration');
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

            $this->form_validation->set_rules('photo_type', 'Passport size photograph type', 'required');
            $this->form_validation->set_rules('signature_type', 'Upload Farmer Signature/Thumb Impression type', 'required');
            $this->form_validation->set_rules('land_owner_signature_type', 'Land Owner Signature type', 'required');
            $this->form_validation->set_rules('bank_passbook_type', 'Photocopy of Bank Passbook of Applicant type', 'required');
            $this->form_validation->set_rules('aadhaar_card_type', 'Aadhar Card  type', 'required');
            $this->form_validation->set_rules('land_records_type', 'Certificate of Non-digitization/Non-integration of Land Records type', 'required');
            $this->form_validation->set_rules('land_document_type', 'Land Document type', 'required');
            $this->form_validation->set_rules('ncla_certificate_type', 'Certificate of Non-Cadastral Land Area type', 'required');

            $photo = '';
            $sign = '';
            $land_owner_signature_card = '';
            $bank_passbook_card = '';
            $aadhaar_card_card = '';
            $land_records_card = '';
            $land_document_card = '';
            $agreement_copy_card = '';
            $ncla_certificate_card = '';
            $sign = isset($dbRow->form_data->signature) ? $dbRow->form_data->signature : '';
            $land_owner_signature_card = isset($dbRow->form_data->land_owner_signature) ? $dbRow->form_data->land_owner_signature : '';
            $bank_passbook_card = isset($dbRow->form_data->bank_passbook) ? $dbRow->form_data->bank_passbook : '';
            $aadhaar_card_card = isset($dbRow->form_data->aadhaar_card) ? $dbRow->form_data->aadhaar_card : '';
            $land_records_card = isset($dbRow->form_data->land_records) ? $dbRow->form_data->land_records : '';
            $land_document_card = isset($dbRow->form_data->land_document) ? $dbRow->form_data->land_document : '';
            $agreement_copy_card = isset($dbRow->form_data->agreement_copy) ? $dbRow->form_data->agreement_copy : '';
            $ncla_certificate_card = isset($dbRow->form_data->ncla_certificate) ? $dbRow->form_data->ncla_certificate : '';

            if (empty($this->input->post("photo_old"))) {
                if ((empty($this->input->post("photo_data"))) && ($_FILES['photo']['name'] == "")) {
                    $this->form_validation->set_rules('photo', 'Photo is Required', 'required');
                }
            }
            if ($sign == null && $_FILES['signature']['name'] == "") {
                $this->form_validation->set_rules('signature', 'Signature is Required', 'required');
            }
            if ($land_owner_signature_card == null && $_FILES['land_owner_signature']['name'] == "") {
                $this->form_validation->set_rules('land_owner_signature', 'Land Owner Signature is Required', 'required');
            }

            if (empty($this->input->post("bank_passbook_old"))) {
                if (((empty($this->input->post("bank_passbook_type"))) && (($_FILES['bank_passbook']['name'] != "") || (!empty($this->input->post("bank_passbook_temp"))))) || ((!empty($this->input->post("bank_passbook_type"))) && (($_FILES['bank_passbook']['name'] == "") && (empty($this->input->post("bank_passbook_temp")))))) {
                    $this->form_validation->set_rules('bank_passbook', 'Photocopy of Bank Passbook of Applicant', 'required');
                }
            }

            if (empty($this->input->post("aadhaar_card_old"))) {
                if (((empty($this->input->post("aadhaar_card_type"))) && (($_FILES['aadhaar_card']['name'] != "") || (!empty($this->input->post("aadhaar_card_temp"))))) || ((!empty($this->input->post("aadhaar_card_type"))) && (($_FILES['aadhaar_card']['name'] == "") && (empty($this->input->post("aadhaar_card_temp")))))) {

                    $this->form_validation->set_rules('aadhaar_card', 'Aadhar Card', 'required');
                }
            }

            if (empty($this->input->post("land_records_old"))) {
                if (((empty($this->input->post("land_records_type"))) && (($_FILES['land_records']['name'] != "") || (!empty($this->input->post("land_records_temp"))))) || ((!empty($this->input->post("land_records_type"))) && (($_FILES['land_records']['name'] == "") && (empty($this->input->post("land_records_temp")))))) {

                    $this->form_validation->set_rules('land_records', 'Certificate of Non-digitization/Non-integration of Land Records', 'required');
                }
            }

            if (empty($this->input->post("land_document_old"))) {
                if (((empty($this->input->post("land_document_type"))) && (($_FILES['land_document']['name'] != "") || (!empty($this->input->post("land_document_temp"))))) || ((!empty($this->input->post("land_document_type"))) && (($_FILES['land_document']['name'] == "") && (empty($this->input->post("land_document_temp")))))) {

                    $this->form_validation->set_rules('land_document', 'Land Document', 'required');
                }
            }
            if (empty($this->input->post("ncla_certificate_old"))) {
                if (((empty($this->input->post("ncla_certificate_type"))) && (($_FILES['ncla_certificate']['name'] != "") || (!empty($this->input->post("ncla_certificate_temp"))))) || ((!empty($this->input->post("ncla_certificate_type"))) && (($_FILES['ncla_certificate']['name'] == "") && (empty($this->input->post("ncla_certificate_temp")))))) {

                    $this->form_validation->set_rules('ncla_certificate', 'Certificate of Non-Cadastral Land Area', 'required');
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
                $dirPath = 'storage/docs/kacc_farmer/photos/';
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
            if ($_FILES['land_owner_signature']['name'] != "") {
                $lo_signature = cifileupload("land_owner_signature");
                $land_owner_signature_card = $lo_signature["upload_status"] ? $lo_signature["uploaded_path"] : null;
            }
            if (strlen($this->input->post("bank_passbook_temp")) > 0) {
                $bp = movedigilockerfile($this->input->post('bank_passbook_temp'));
                $bank_passbook_card = $bp["upload_status"] ? $bp["uploaded_path"] : null;
            } else {
                if ($_FILES['bank_passbook']['name'] != "") {
                    $bp = cifileupload("bank_passbook");
                    $bank_passbook_card = $bp["upload_status"] ? $bp["uploaded_path"] : null;
                }
            }

            if (strlen($this->input->post("aadhaar_card_temp")) > 0) {
                $aadhr = movedigilockerfile($this->input->post('aadhaar_card_temp'));
                $aadhaar_card_card = $aadhr["upload_status"] ? $aadhr["uploaded_path"] : null;
            } else {
                if ($_FILES['aadhaar_card']['name'] != "") {
                    $aadhr = cifileupload("aadhaar_card");
                    $aadhaar_card_card = $aadhr["upload_status"] ? $aadhr["uploaded_path"] : null;
                }
            }

            if (strlen($this->input->post("land_records_temp")) > 0) {
                $lr = movedigilockerfile($this->input->post('land_records_temp'));
                $land_records_card = $lr["upload_status"] ? $lr["uploaded_path"] : null;
            } else {
                if ($_FILES['land_records']['name'] != "") {
                    $lr = cifileupload("land_records");
                    $land_records_card = $lr["upload_status"] ? $lr["uploaded_path"] : null;
                }
            }

            if (strlen($this->input->post("land_document_temp")) > 0) {
                $ld = movedigilockerfile($this->input->post('land_document_temp'));
                $land_document_card = $ld["upload_status"] ? $ld["uploaded_path"] : null;
            } else {
                if ($_FILES['land_document']['name'] != "") {
                    $ld = cifileupload("land_document");
                    $land_document_card = $ld["upload_status"] ? $ld["uploaded_path"] : null;
                }
            }

            if (strlen($this->input->post("agreement_copy_temp")) > 0) {
                $ac = movedigilockerfile($this->input->post('agreement_copy_temp'));
                $agreement_copy_card = $ac["upload_status"] ? $ac["uploaded_path"] : null;
            } else {
                if ($_FILES['agreement_copy']['name'] != "") {
                    $ac = cifileupload("agreement_copy");
                    $agreement_copy_card = $ac["upload_status"] ? $ac["uploaded_path"] : null;
                }
            }
            if (strlen($this->input->post("ncla_certificate_temp")) > 0) {
                $ac = movedigilockerfile($this->input->post('ncla_certificate_temp'));
                $ncla_certificate_card = $ac["upload_status"] ? $ac["uploaded_path"] : null;
            } else {
                if ($_FILES['ncla_certificate']['name'] != "") {
                    $ac = cifileupload("ncla_certificate");
                    $ncla_certificate_card = $ac["upload_status"] ? $ac["uploaded_path"] : null;
                }
            }

            $uploadedFiles = array(
                "photo" => strlen($photo) ? $photo : '',
                "signature" => strlen($sign) ? $sign : '',
                "land_owner_signature" => strlen($land_owner_signature_card) ? $land_owner_signature_card : $land_owner_signature_card,
                "bank_passbook" => strlen($bank_passbook_card) ? $bank_passbook_card : '',
                "aadhaar_card" => strlen($aadhaar_card_card) ? $aadhaar_card_card : '',
                "land_records" => strlen($land_records_card) ? $land_records_card : '',
                "land_document" => strlen($land_document_card) ? $land_document_card : '',
                "agreement_copy" => strlen($agreement_copy_card) ? $agreement_copy_card : '',
                "ncla_certificate" => strlen($ncla_certificate_card) ? $ncla_certificate_card : '',

            );
            $this->session->set_flashdata('uploaded_files', $uploadedFiles);
            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

            if ($this->form_validation->run() == false) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $this->queryform($objId);
            } else {

                $data = array(
                    'form_data.photo_type' => $this->input->post("photo_type"),
                    'form_data.photo' => $photo,
                    'form_data.signature_type' => $this->input->post("signature_type"),
                    'form_data.signature' => $sign,
                    'form_data.land_owner_signature_type' => $this->input->post("land_owner_signature_type"),
                    'form_data.land_owner_signature' => $land_owner_signature_card,
                    'form_data.aadhaar_card_type' => $this->input->post("aadhaar_card_type"),
                    'form_data.aadhaar_card' => $aadhaar_card_card,
                    'form_data.bank_passbook_type' => $this->input->post("bank_passbook_type"),
                    'form_data.bank_passbook' => $bank_passbook_card,
                    'form_data.land_records_type' => $this->input->post("land_records_type"),
                    'form_data.land_records' => $land_records_card,
                    'form_data.land_document_type' => $this->input->post("land_document_type"),
                    'form_data.land_document' => $land_document_card,
                    'form_data.agreement_copy_type' => $this->input->post("agreement_copy_type"),
                    'form_data.agreement_copy' => $agreement_copy_card,
                    'form_data.ncla_certificate_type' => $this->input->post("ncla_certificate_type"),
                    'form_data.ncla_certificate' => $ncla_certificate_card,

                );

                $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $data);
                $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);

                $postdata = array();

                //query submit pending
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

                $aadhaar_cardf = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->aadhaar_card));
                $aadhaar_card = array(
                    "encl" => $aadhaar_cardf,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->aadhaar_card_type,
                    "enclType" => $dbRow->form_data->aadhaar_card_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['adhaar_card'] = $aadhaar_card;

                $land_owner_signaturef = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->land_owner_signature));
                $land_owner_signature = array(
                    "encl" => $land_owner_signaturef,
                    "docType" => "application/jpg",
                    "enclFor" => $dbRow->form_data->land_owner_signature_type,
                    "enclType" => $dbRow->form_data->land_owner_signature_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "jpg",
                );
                $postdata['land_owner_sign_thumb'] = $land_owner_signature;

                $bank_passbookf = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->bank_passbook));
                $bank_passbook = array(
                    "encl" => $bank_passbookf,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->bank_passbook_type,
                    "enclType" => $dbRow->form_data->bank_passbook_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['bank_passbook'] = $bank_passbook;

                $agreement_copyf = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->agreement_copy));
                $agreement_copy = array(
                    "encl" => $agreement_copyf,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->agreement_copy_type,
                    "enclType" => $dbRow->form_data->agreement_copy_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['agreement_copy'] = $agreement_copy;

                $spId = array(
                    "applId" => $dbRow->service_data->appl_id,
                );

                $postdata['spId'] = $spId;
                //end query submit

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
                $curl = curl_init($url . "ccsdp/certified_copy/update_certicopy.php");

                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                curl_close($curl);
                // pre($response);

                log_response($dbrow->service_data->appl_ref_no, $response);

                if ($response) {

                    if ($response->ref->status === "success") {
                        $processing_history = $dbrow->processing_history ?? array();
                        $processing_history[] = array(
                            "processed_by" => "Query submitted by " . $dbrow->form_data->applicant_name,
                            "action_taken" => "Query submitted",
                            "remarks" => "Query submitted by " . $dbrow->form_data->applicant_name,
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
                    "title" => $dbRow->form_data->applicant_title,
                    "first_name" => $dbRow->form_data->first_name,
                    "middle_name" => $dbRow->form_data->middle_name,
                    "last_name" => $dbRow->form_data->last_name,
                    "father_name" => $dbRow->form_data->father_title . ' ' . $dbRow->form_data->father_name,
                    "email" => $dbRow->form_data->applicant_email,
                    "caste" => $dbRow->form_data->caste,
                    "aadhar_no" => $dbRow->form_data->aadhar_no,
                    "mobile_no" => $dbRow->form_data->mobile,
                    "district" => $dbRow->form_data->district,
                    "post_office" => $dbRow->form_data->post_office,
                    "police_station" => $dbRow->form_data->police_station,
                    "bank_name" => $dbRow->form_data->bank_name,
                    "bank_branch" => $dbRow->form_data->bank_branch,
                    "ifsc_code" => $dbRow->form_data->ifsc_code,
                    "bank_account_no" => $dbRow->form_data->bank_account_no,
                    "land_district" => $dbRow->form_data->land_district,
                    "land_sub_division" => $dbRow->form_data->sub_division,
                    "land_circle" => $dbRow->form_data->circle_office,
                    "land_mouza" => $dbRow->form_data->mouza_name,
                    "land_revenue_vill" => $dbRow->form_data->revenue_village,
                    "land_patta_type" => $dbRow->form_data->patta_type,
                    "land_dag_no" => $dbRow->form_data->dag_no,
                    "land_patta_no" => $dbRow->form_data->patta_no,
                    "land_pattadar_name" => $dbRow->form_data->name_of_pattadar,
                    "land_category" => $dbRow->form_data->land_category,
                    "land_pattadar_father_name" => $dbRow->form_data->pattadar_father_name,
                    "land_relationship_pattar" => $dbRow->form_data->relationship_with_pattadar,
                    "land_cultivated_land" => $dbRow->form_data->cultivated_land,
                    "land_production" => $dbRow->form_data->production,
                    "land_crop_variety" => $dbRow->form_data->crop_variety,
                    "land_surplus" => $dbRow->form_data->surplus_production,
                    "land_cultivator_type" => $dbRow->form_data->cultivator_type,
                    "bigha" => $dbRow->form_data->bigha,
                    "kotha" => $dbRow->form_data->kotha,
                    "loosa" => $dbRow->form_data->loosa,
                    "land_area" => $dbRow->form_data->land_area,
                    "ado_circle" => $dbRow->form_data->ado_circle_office,

                );

                $photo = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->photo));

                $postdata['passport_photo'] = $photo;

                $signature = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->signature));

                $postdata['applicant_signature'] = $signature;

                $land_owner_signature = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->land_owner_signature));

                $postdata['land_owner_signature'] = $land_owner_signature;

                $bank_passbookf = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->bank_passbook));
                $bank_passbook = array(
                    "encl" => $bank_passbookf,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->bank_passbook_type,
                    "enclType" => $dbRow->form_data->bank_passbook_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['bank_passbook'] = $bank_passbook;

                $aadhaar_cardf = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->aadhaar_card));
                $aadhaar_card = array(
                    "encl" => $aadhaar_cardf,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->aadhaar_card_type,
                    "enclType" => $dbRow->form_data->aadhaar_card_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['adhaar_card'] = $aadhaar_card;

                $agreement_copyf = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->agreement_copy));
                $agreement_copy = array(
                    "encl" => $agreement_copyf,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->agreement_copy_type,
                    "enclType" => $dbRow->form_data->agreement_copy_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['agreement_copy'] = $agreement_copy;
                // "mbtc_room_rent" => $dbRow->form_data->land_document,
                // "special_reason_letter" => $dbRow->form_data->consideration_letter,
                // "softcopy_application" => $dbRow->form_data->father_title,
                $land_documentf = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->land_document));
                $land_document = array(
                    "encl" => $land_documentf,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->land_document_type,
                    "enclType" => $dbRow->form_data->land_document_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['land_patta_doc'] = $land_document;
                // $postdata['softcopy_application'] = $land_document;
                //non_digit_doc,non_cadastral_doc
                $non_digit_docf = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->ncla_certificate));
                $non_digit_doc = array(
                    "encl" => $non_digit_docf,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->ncla_certificate,
                    "enclType" => $dbRow->form_data->ncla_certificate_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf",
                );
                $postdata['non_digit_doc'] = $non_digit_doc;

                $spId = array(
                    "applId" => $dbRow->service_data->appl_id,
                );

                $postdata['spId'] = $spId;

                //delete these lines- for testing only
                // pre(json_encode($postdata));

                // $data_to_update = array(
                //     'service_data.appl_status' => 'submitted',
                //     'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                //     'processing_history' => $processing_history,
                // );
                // $this->kaac_registration_model->update($obj, $data_to_update);
                // redirect('spservices/kaac_farmer/registration/acknowledgement/' . $obj);
                //delete these lines- for testing only

                //for testing only

                //remove the comments for production
                $url = $this->config->item('kaac_post_url');
                    $curl = curl_init($url . "fc/paddy/certified_copy/post_certicopy.php");
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
                            "applicant_name" => $dbRow->form_data->applicant_name,
                            "service_name" => $dbRow->service_data->service_name,
                            "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                            "app_ref_no" => $dbRow->service_data->appl_ref_no
                        );
                        sms_provider("submission", $sms);
                        redirect('spservices/kaac_farmer/registration/acknowledgement/' . $obj);
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
        //     $this->load->view('kaac/fifth_group/kaac_preview_view', $data);
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
        $this->load->view('kaac/fifth_group/query_charge_template', $data);
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

                // $postdata['spId'] = $spId;

                $postdata = array(
                    "payment_ref_number" => $dbRow->form_data->query_payment_response->GRN,
                    "fee_paid" => $dbRow->form_data->query_payment_response->AMOUNT,
                    "payment_mode" => "Online",
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
                // redirect('spservices/kaac_farmer/registration/payment_acknowledgement/' . $obj);
                //uncomment these lines for production start
                $url = $this->config->item('kaac_post_url');
                    $curl = curl_init($url . "/ccsdp/certified_copy/update_certicopy.php");

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
                        redirect('spservices/kaac_farmer/registration/payment_acknowledgement/' . $obj);
                    }else{

                        $this->session->set_flashdata('errmessage', 'Unable to update  payment query with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
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
