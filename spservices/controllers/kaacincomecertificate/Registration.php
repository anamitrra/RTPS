<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use GuzzleHttp\Psr7\Response;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps
{
    private $serviceId = "ICERT";
    private $base_serviceId = "1773";
    private $departmentName = "Karbi Anglong (AC)";
    private $departmentId = "2100";
    // private $districtName = "";
    // private $submmissionLocation = "";

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

    public function index($obj_id = null)
    {
        // pre($obj_id);
        if ($obj_id == $this->serviceId || $obj_id == null) {
            $data = array(
                "pageTitle" => "Issuance of Income Certificate - KAAC",
                "pageTitleId" => $this->serviceId
            );
            $data["dbrow"] = null;
        } else if ($this->checkObjectId($obj_id)) {
            $filter = array("_id" => new ObjectId($obj_id), "service_data.appl_status" => "DRAFT");
            $data["dbrow"] = $this->kaac_registration_model->get_row($filter);
        }

        $data['usser_type'] = $this->slug;

        $this->load->view('includes/frontend/header');
        $this->load->view('kaacincomecertificate/kaac_income_registration_form', $data);
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

        // pre($_POST);

        $objId = $this->input->post("obj_id");
        // pre($objId);
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");
        $this->serviceName = $this->input->post("service_name");
        $this->serviceId = $this->input->post("service_id");
        //$this->form_validation->set_rules('language', 'Language', 'trim|required|xss_clean|strip_tags|max_length[30]');
        $this->form_validation->set_rules('applicant_title', 'Title', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('father_title', 'Title', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('father_name', 'Father name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        // $this->form_validation->set_rules('mother_name', 'Mother name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
        $this->form_validation->set_rules('mobile', 'Contact number', 'trim|integer|required|exact_length[10]|xss_clean|strip_tags');
        $this->form_validation->set_rules('email', 'Email id', 'valid_email|trim|xss_clean|strip_tags');

        $this->form_validation->set_rules('circle_office', 'Circle Office', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('mouza_name', 'Mouza', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('revenue_village', 'Revenue Village', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('police_station', 'Police Station', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('post_office', 'Post office', 'trim|required|xss_clean|strip_tags');

        $this->form_validation->set_rules('applicant_category', 'Applicant Category', 'trim|required|xss_clean|strip_tags');

        if ($this->input->post("applicant_category") == 1 || $this->input->post("applicant_category") == 2) {

            $this->form_validation->set_rules('dag_no', 'Dag No', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('periodic_patta_no', 'Annual Patta No', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('patta_type', 'Patta Type', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('land_area_katha', 'Kotha', 'trim|required|integer|xss_clean|strip_tags');
            $this->form_validation->set_rules('land_area_bigha', 'Bigha', 'trim|required|integer|xss_clean|strip_tags');
            $this->form_validation->set_rules('land_area_loosa', 'Loosa', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('land_area_sq_ft', 'Land Area', 'trim|required|xss_clean|strip_tags');
        }


        // $this->form_validation->set_rules('inputcaptcha', 'Captcha', 'required|exact_length[6]');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : $this->serviceId;
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
                $app_id = rand(1000000000, 9999999999);
                $filter = array(
                    "service_data.appl_id" => $app_id,
                    // "service_data.service_id" => $this->serviceId
                    "form_data.service_id" => $this->serviceId
                );
                $rows = $this->kaac_registration_model->get_row($filter);

                if ($rows == false)
                    break;
            }



            // pre($this->input->post("circle_office"));
            if($this->input->post("circle_office") == 1383118 || $this->input->post("circle_office") == 1383120 || $this->input->post("circle_office") == 1383121){
                // pre("jfh");
                $this->districtName = "KARBI ANGLONG";
                $this->submmissionLocation = "Revenue Office KAAC(Revenue Office-KAAC- ".$this->input->post("circle_office_name")." )";
            }
            elseif($this->input->post("circle_office") == 1383119){
                // pre("mvb");
                $this->districtName = "WEST KARBI ANGLONG";
                $this->submmissionLocation = "Revenue Office KAAC(Revenue Office-KAAC- ".$this->input->post("circle_office_name")." )";
            }

            $service_data = [
                "department_id" => $this->departmentId,
                "department_name" => $this->departmentName,
                "service_id" => $this->base_serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "applied_by" => $apply_by,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "submission_location" => $this->submmissionLocation, // office name
                "submission_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "service_timeline" => "7 Days",
                "appl_status" => "DRAFT",
                "district" => $this->districtName
            ];

            $form_data = [
                'user_id' => $sessionUser['userId']->{'$id'},
                'user_type' => $this->slug,
                "service_id" => $this->serviceId,
                'applicant_name' => $this->input->post("applicant_title").' '.$this->input->post("first_name").' '.$this->input->post("last_name"),
                'applicant_title' => $this->input->post("applicant_title"),
                'first_name' => $this->input->post("first_name"),
                'last_name' => $this->input->post("last_name"),
                'applicant_gender' => $this->input->post("gender"),
                'mobile' => $this->input->post("mobile"),
                'email' => $this->input->post("email"),
                'father_title' => $this->input->post("father_title"),
                'father_name' => $this->input->post("father_name"),
                'circle_office' => $this->input->post("circle_office"),
                'circle_office_name' => $this->input->post("circle_office_name"),
                'mouza_name' => $this->input->post("mouza_name"),
                'mouza_office_name' => $this->input->post("mouza_office_name"),
                'revenue_village' => $this->input->post("revenue_village"),
                'revenue_village_name' => $this->input->post("revenue_village_name"),
                'police_station' => $this->input->post("police_station"),
                'police_station_name' => $this->input->post("police_station_name"),
                'post_office' => $this->input->post("post_office"),
                'applicant_category' => $this->input->post("applicant_category"),
                'applicant_category_text' => $this->input->post("applicant_category_text"),

                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];

            if ($this->input->post("applicant_category") == 1 || $this->input->post("applicant_category") == 2) {
                $form_data['dag_no'] = $this->input->post("dag_no");
                $form_data['periodic_patta_no'] = $this->input->post("periodic_patta_no");
                $form_data['patta_type']  = $this->input->post("patta_type");
                $form_data['patta_type_name']  = $this->input->post("patta_type_name");
                $form_data['land_area_bigha'] = $this->input->post("land_area_bigha");
                $form_data['land_area_katha'] = $this->input->post("land_area_katha");
                $form_data['land_area_loosa'] = $this->input->post("land_area_loosa");
                $form_data['land_area_sq_ft'] = $this->input->post("land_area_sq_ft");
            }



            if (strlen($objId)) {

                // pre($this->input->post("applicant_category"));

                $form_data["address_proof_type"] = $this->input->post("address_proof_type");
                $form_data["address_proof"] = $this->input->post("address_proof");
                $form_data["identity_proof_type"] = $this->input->post("identity_proof_type");
                $form_data["identity_proof"] = $this->input->post("identity_proof");
                $form_data['land_patta_copy_type'] = $this->input->post("land_patta_copy_type");
                $form_data['land_patta_copy'] = $this->input->post("land_patta_copy");
                $form_data['updated_land_revenue_receipt_type'] = $this->input->post("updated_land_revenue_receipt_type");
                $form_data['updated_land_revenue_receipt'] = $this->input->post("updated_land_revenue_receipt");


                if ($this->input->post("applicant_category") == 1 || $this->input->post("applicant_category") == 3) {
                    $form_data['salary_slip_type'] = $this->input->post("salary_slip_type");
                    $form_data['salary_slip'] = $this->input->post("salary_slip");
                } elseif ($this->input->post("applicant_category") == 2 || $this->input->post("applicant_category") == 4) {
                    $form_data['other_doc_type'] = $this->input->post("other_doc_type");
                    $form_data['other_doc'] = $this->input->post("other_doc");
                }
            }

            $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data
            ];
            // pre($inputs);
            // exit();
            if (strlen($objId)) {
                //   pre($objId);
                $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                // pre($objId);
                redirect('spservices/kaacincomecertificate/registration/fileuploads/' . $objId);
                exit();
            } else {
                $insert = $this->kaac_registration_model->insert($inputs);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    // pre($objectId);
                    redirect('spservices/kaacincomecertificate/registration/fileuploads/' . $objectId);
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
        // pre("Files upload");
        $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);
        // pre($dbRow);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $dbRow->service_data->service_name,
                "pageTitle" => "Attached Enclosures for " . $dbRow->service_data->service_name,
                "pageTitleBaseId" => $dbRow->service_data->service_id,
                "pageTitleId" => $dbRow->form_data->service_id,
                "obj_id" => $objId,
                "dbrow" => $dbRow
            );
            // pre($data);
            $this->load->view('includes/frontend/header');
            $this->load->view('kaacincomecertificate/kaac_income_fileuploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaacincomecertificate/registration/');
        } //End of if else
    } //End of fileuploads()

    public function submitfiles()
    {

        // pre($_FILES);
        $objId = $this->input->post("obj_id");
        if (empty($objId)) {
            $this->my_transactions();
        }
        $dbrow = $this->kaac_registration_model->get_by_doc_id($objId);


        $this->form_validation->set_rules('address_proof_type', 'Address Proof Type', 'required');
        if (empty($this->input->post("address_proof_old"))) {
            if ((empty($this->input->post("address_proof_temp"))) && (($_FILES['address_proof']['name'] == ""))) {
                $this->form_validation->set_rules('address_proof', 'Address Proof document', 'trim|required|xss_clean|strip_tags');
            }

            if ((!empty($this->input->post("address_proof_type"))) && (($_FILES['address_proof']['name'] == "") && (empty($this->input->post("address_proof_temp"))))) {
                $this->form_validation->set_rules('address_proof', 'Address Proof', 'required');
            }
        }
        $this->form_validation->set_rules('identity_proof_type', 'Identity Proof Type', 'required');
        if (empty($this->input->post("identity_proof_old"))) {
            if ((empty($this->input->post("identity_proof_temp"))) && (($_FILES['identity_proof']['name'] == ""))) {
                $this->form_validation->set_rules('identity_proof', 'Identity Proof document', 'trim|required|xss_clean|strip_tags');
            }

            if ((!empty($this->input->post("identity_proof_type"))) && (($_FILES['identity_proof']['name'] == "") && (empty($this->input->post("identity_proof_temp"))))) {
                $this->form_validation->set_rules('identity_proof', 'Identity Proof', 'required');
            }
        }
        if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 2) {
            $this->form_validation->set_rules('land_patta_copy_type', 'Land Patta Copy type', 'required');
            if (empty($this->input->post("land_patta_copy_old"))) {
                if ((empty($this->input->post("land_patta_copy_temp"))) && (($_FILES['land_patta_copy']['name'] == ""))) {
                    $this->form_validation->set_rules('land_patta_copy', 'Land Patta Copy document', 'required');
                }

                if ((!empty($this->input->post("land_patta_copy_type"))) && (($_FILES['land_patta_copy']['name'] == "") && (empty($this->input->post("land_patta_copy_temp"))))) {
                    $this->form_validation->set_rules('land_patta_copy', 'Land Patta Copy document', 'required');
                }
            }

            $this->form_validation->set_rules('updated_land_revenue_receipt_type', 'Updated Land Revenue Receipt type', 'required');
            if (empty($this->input->post("updated_land_revenue_receipt_old"))) {

                if ((empty($this->input->post("updated_land_revenue_receipt_type"))) && (($_FILES['updated_land_revenue_receipt']['name'] != "") || (!empty($this->input->post("updated_land_revenue_receipt_temp"))))) {
                    // pre("sadasd");
                    $this->form_validation->set_rules('updated_land_revenue_receipt_type', 'Updated Land Revenue Receipt type', 'required');
                }

                if ((!empty($this->input->post("updated_land_revenue_receipt_type"))) && (($_FILES['updated_land_revenue_receipt']['name'] == "") && (empty($this->input->post("updated_land_revenue_receipt_temp"))))) {
                    $this->form_validation->set_rules('updated_land_revenue_receipt ', 'Updated Land revenue receipt Document', 'required');
                }
            }
        }
        if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 3) {
            $this->form_validation->set_rules('salary_slip_type', 'Salary Slip', 'required');
            if (empty($this->input->post("salary_slip_old"))) {
                if ((empty($this->input->post("salary_slip_type"))) && (($_FILES['salary_slip']['name'] != "") || (!empty($this->input->post("salary_slip_temp"))))) {
                    $this->form_validation->set_rules('salary_slip_type', 'Salary Slip type', 'required');
                }

                if ((!empty($this->input->post("salary_slip_type"))) && (($_FILES['salary_slip']['name'] == "") && (empty($this->input->post("salary_slip_temp"))))) {
                    $this->form_validation->set_rules('salary_slip', 'Salary Slip', 'required');
                }
            }
        } else if ($dbrow->form_data->applicant_category == 4) {
            $this->form_validation->set_rules('other_doc_type', 'Salary Slip', 'required');
            if (empty($this->input->post("other_doc_old"))) {
                if ((empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] != "") || (!empty($this->input->post("other_doc_temp"))))) {
                    $this->form_validation->set_rules('other_doc_type', 'Document type', 'required');
                }

                if ((!empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] == "") && (empty($this->input->post("other_doc_temp"))))) {
                    $this->form_validation->set_rules('other_doc', 'Document', 'required');
                }
            }
        } else {
            return error_reporting(E_ALL);;
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $address_proof = "";
        $identity_proof = "";
        if ($_FILES['address_proof']['name'] != "") {
            $addressProof = cifileupload("address_proof");
            $address_proof = $addressProof["upload_status"] ? $addressProof["uploaded_path"] : null;
        } else if (!empty($this->input->post("address_proof_temp"))) {
            $addressProof = movedigilockerfile($this->input->post('address_proof_temp'));
            $address_proof = $addressProof["upload_status"] ? $addressProof["uploaded_path"] : null;
        }
        // pre($addressProof);
        if ($_FILES['identity_proof']['name'] != "") {
            $identityProof = cifileupload("identity_proof");
            $identity_proof = $identityProof["upload_status"] ? $identityProof["uploaded_path"] : null;
        } else if (!empty($this->input->post("identity_proof_temp"))) {
            $identityProof = movedigilockerfile($this->input->post('identity_proof_temp'));
            $identity_proof = $identityProof["upload_status"] ? $identityProof["uploaded_path"] : null;
        }


        $land_patta_copy = "";
        $updated_land_revenue_receipt = "";
        if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 2) {
            if ($_FILES['land_patta_copy']['name'] != "") {
                $landPattaCopy = cifileupload("land_patta_copy");
                $land_patta_copy = $landPattaCopy["upload_status"] ? $landPattaCopy["uploaded_path"] : null;
            } else if (!empty($this->input->post("land_patta_copy_temp"))) {
                $landPattaCopy = movedigilockerfile($this->input->post('land_patta_copy_temp'));
                $land_patta_copy = $landPattaCopy["upload_status"] ? $landPattaCopy["uploaded_path"] : null;
            }

            if ($_FILES['updated_land_revenue_receipt']['name'] != "") {
                // pre("jhhj");
                $updatedLandRevenueReceipt = cifileupload("updated_land_revenue_receipt");
                $updated_land_revenue_receipt = $updatedLandRevenueReceipt["upload_status"] ? $updatedLandRevenueReceipt["uploaded_path"] : null;
            } else if (!empty($this->input->post("updated_land_revenue_receipt_temp"))) {
                $updatedLandRevenueReceipt = movedigilockerfile($this->input->post('updated_land_revenue_receipt_temp'));
                $updated_land_revenue_receipt = $updatedLandRevenueReceipt["upload_status"] ? $updatedLandRevenueReceipt["uploaded_path"] : null;
            }
        }

        $salary_slip = "";
        $other_doc = "";
        if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 3) {
            if ($_FILES['salary_slip']['name'] != "") {
                $salarySlip = cifileupload("salary_slip");
                $salary_slip = $salarySlip["upload_status"] ? $salarySlip["uploaded_path"] : null;
            } else if (!empty($this->input->post("salary_slip_temp"))) {
                $salarySlip = movedigilockerfile($this->input->post('salary_slip_temp'));
                $salary_slip = $salarySlip["upload_status"] ? $salarySlip["uploaded_path"] : null;
            }
        } else if ($dbrow->form_data->applicant_category == 4) {
            if ($_FILES['other_doc']['name'] != "") {
                $otherDoc = cifileupload("other_doc");
                $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;
            } else if (!empty($this->input->post("other_doc_temp"))) {
                $otherDoc = movedigilockerfile($this->input->post('other_doc_temp'));
                $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;
            }
        }

        // pre($address_proof);

        $uploadedFiles = array(
            "address_proof_old" => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
            "identity_proof_old" => strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old"),
            "land_patta_copy_old" => strlen($land_patta_copy) ? $land_patta_copy : $this->input->post("land_patta_copy_old"),
            "updated_land_revenue_receipt_old" => strlen($updated_land_revenue_receipt) ? $updated_land_revenue_receipt : $this->input->post("updated_land_revenue_receipt_old"),
            "salary_slip_old" => strlen($salary_slip) ? $salary_slip : $this->input->post("salary_slip_old"),
            "other_doc_old" => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old")
        );
        // pre($uploadedFiles);
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {

            if (!empty($this->input->post("address_proof_type"))) {
                $data["form_data.address_proof_type"] = $this->input->post("address_proof_type");
                $data["form_data.address_proof"] = strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old");
            }
            if (!empty($this->input->post("identity_proof_type"))) {
                $data["form_data.identity_proof_type"] = $this->input->post("identity_proof_type");
                $data["form_data.identity_proof"] = strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old");
            }


            if($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 2){
                if (!empty($this->input->post("land_patta_copy_type"))) {
                    $data["form_data.land_patta_copy_type"] = $this->input->post("land_patta_copy_type");
                    $data["form_data.land_patta_copy"] = strlen($land_patta_copy) ? $land_patta_copy : $this->input->post("land_patta_copy_old");
                }
    
                if (!empty($this->input->post("updated_land_revenue_receipt_type"))) {
                    $data["form_data.updated_land_revenue_receipt_type"] = $this->input->post("updated_land_revenue_receipt_type");
                    $data["form_data.updated_land_revenue_receipt"] = strlen($updated_land_revenue_receipt) ? $updated_land_revenue_receipt : $this->input->post("updated_land_revenue_receipt_old");
                }
            }


            if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 3) {
                if (!empty($this->input->post("salary_slip_type"))) {
                    $data["form_data.salary_slip_type"] = $this->input->post("salary_slip_type");
                    $data["form_data.salary_slip"] = strlen($salary_slip) ? $salary_slip : $this->input->post("salary_slip_old");
                }
            } else if ($dbrow->form_data->applicant_category == 2 || $dbrow->form_data->applicant_category == 4) {
                if (!empty($this->input->post("other_doc_type"))) {
                    $data["form_data.other_doc_type"] = $this->input->post("other_doc_type");
                    $data["form_data.other_doc"] = strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old");
                }
            }

// pre($data);


            $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $data);

            //....................
            $this->session->set_flashdata('success', 'Your application has been successfully submitted');

            // pre($data);
            // exit();
            redirect('spservices/kaacincomecertificate/registration/preview/' . $objId);
        } //End of if else
    } //End of submitfiles()

    public function preview($objId = null)
    {
        $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);
        //var_dump($dbRow); die;
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $dbRow->service_data->service_name,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('kaacincomecertificate/kaac_income_preview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaacincomecertificate/registration/');
        } //End of if else
    } //End of preview()

    public function download_certificate($objId = null)
    {
        if (!empty($objId)) {
            $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);
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
    } //End of download_certificate()

    public function track($objId = null)
    {
        $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_data.service_name" => $dbRow->form_data->service_id,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('kaacincomecertificate/kaac_income_track_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaacincomecertificate/registration/');
        } //End of if else
    } //End of track()

    function getID($length)
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

            if ($dbRow) {
                $data = array(
                    "dbrow" => $dbRow
                );

                $this->load->view('includes/frontend/header');
                $this->load->view('kaacincomecertificate/kaac_income_query_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/kaacincomecertificate/registration');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/kaacincomecertificate/registration');
        } //End of if else
    } //End of query()

    public function querysubmit()
    {
        // pre($_POST);
        $objId = $this->input->post("obj_id");
        // pre($objId);
        if (empty($objId)) {
            $this->my_transactions();
        }
        $dbrow = $this->kaac_registration_model->get_by_doc_id($objId);
        // pre($_POST);
        if (count((array)$dbrow)) {

            $this->form_validation->set_rules('address_proof_type', 'Address Proof Type', 'required');
            if (empty($this->input->post("address_proof_old"))) {
                if ((empty($this->input->post("address_proof_temp"))) && (($_FILES['address_proof']['name'] == ""))) {
                    $this->form_validation->set_rules('address_proof', 'Address Proof document', 'trim|required|xss_clean|strip_tags');
                }

                if ((!empty($this->input->post("address_proof_type"))) && (($_FILES['address_proof']['name'] == "") && (empty($this->input->post("address_proof_temp"))))) {
                    $this->form_validation->set_rules('address_proof', 'Address Proof', 'required');
                }
            }
            $this->form_validation->set_rules('identity_proof_type', 'Identity Proof Type', 'required');
            if (empty($this->input->post("identity_proof_old"))) {
                if ((empty($this->input->post("identity_proof_temp"))) && (($_FILES['identity_proof']['name'] == ""))) {
                    $this->form_validation->set_rules('identity_proof', 'Identity Proof document', 'trim|required|xss_clean|strip_tags');
                }

                if ((!empty($this->input->post("identity_proof_type"))) && (($_FILES['identity_proof']['name'] == "") && (empty($this->input->post("identity_proof_temp"))))) {
                    $this->form_validation->set_rules('identity_proof', 'Identity Proof', 'required');
                }
            }
            if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 2) {
                $this->form_validation->set_rules('land_patta_copy_type', 'Land Patta Copy type', 'required');
                if (empty($this->input->post("land_patta_copy_old"))) {
                    if ((empty($this->input->post("land_patta_copy_temp"))) && (($_FILES['land_patta_copy']['name'] == ""))) {
                        $this->form_validation->set_rules('land_patta_copy', 'Land Patta Copy document', 'required');
                    }

                    if ((!empty($this->input->post("land_patta_copy_type"))) && (($_FILES['land_patta_copy']['name'] == "") && (empty($this->input->post("land_patta_copy_temp"))))) {
                        $this->form_validation->set_rules('land_patta_copy', 'Land Patta Copy document', 'required');
                    }
                }

                $this->form_validation->set_rules('updated_land_revenue_receipt_type', 'Updated Land Revenue Receipt type', 'required');
                if (empty($this->input->post("updated_land_revenue_receipt_old"))) {

                    if ((empty($this->input->post("updated_land_revenue_receipt_type"))) && (($_FILES['updated_land_revenue_receipt']['name'] != "") || (!empty($this->input->post("updated_land_revenue_receipt_temp"))))) {
                        // pre("sadasd");
                        $this->form_validation->set_rules('updated_land_revenue_receipt_type', 'Updated Land Revenue Receipt type', 'required');
                    }

                    if ((!empty($this->input->post("updated_land_revenue_receipt_type"))) && (($_FILES['updated_land_revenue_receipt']['name'] == "") && (empty($this->input->post("updated_land_revenue_receipt_temp"))))) {
                        $this->form_validation->set_rules('updated_land_revenue_receipt ', 'Updated Land revenue receipt Document', 'required');
                    }
                }
            }
            if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 3) {
                $this->form_validation->set_rules('salary_slip_type', 'Salary Slip', 'required');
                if (empty($this->input->post("salary_slip_old"))) {
                    if ((empty($this->input->post("salary_slip_type"))) && (($_FILES['salary_slip']['name'] != "") || (!empty($this->input->post("salary_slip_temp"))))) {
                        $this->form_validation->set_rules('salary_slip_type', 'Salary Slip type', 'required');
                    }

                    if ((!empty($this->input->post("salary_slip_type"))) && (($_FILES['salary_slip']['name'] == "") && (empty($this->input->post("salary_slip_temp"))))) {
                        $this->form_validation->set_rules('salary_slip', 'Salary Slip', 'required');
                    }
                }
            } else if ($dbrow->form_data->applicant_category == 4) {
                $this->form_validation->set_rules('other_doc_type', 'Salary Slip', 'required');
                if (empty($this->input->post("other_doc_old"))) {
                    if ((empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] != "") || (!empty($this->input->post("other_doc_temp"))))) {
                        $this->form_validation->set_rules('other_doc_type', 'Document type', 'required');
                    }

                    if ((!empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] == "") && (empty($this->input->post("other_doc_temp"))))) {
                        $this->form_validation->set_rules('other_doc', 'Document', 'required');
                    }
                }
            } else {
                return error_reporting(E_ALL);;
            }

            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");


            $address_proof = "";
            $identity_proof = "";
            if ($_FILES['address_proof']['name'] != "") {
                $addressProof = cifileupload("address_proof");
                $address_proof = $addressProof["upload_status"] ? $addressProof["uploaded_path"] : null;
            } else if (!empty($this->input->post("address_proof_temp"))) {
                $addressProof = movedigilockerfile($this->input->post('address_proof_temp'));
                $address_proof = $addressProof["upload_status"] ? $addressProof["uploaded_path"] : null;
            }
            // pre($addressProof);
            if ($_FILES['identity_proof']['name'] != "") {
                $identityProof = cifileupload("identity_proof");
                $identity_proof = $identityProof["upload_status"] ? $identityProof["uploaded_path"] : null;
            } else if (!empty($this->input->post("identity_proof_temp"))) {
                $identityProof = movedigilockerfile($this->input->post('identity_proof_temp'));
                $identity_proof = $identityProof["upload_status"] ? $identityProof["uploaded_path"] : null;
            }


            $land_patta_copy = "";
            $updated_land_revenue_receipt = "";
            if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 2) {
                if ($_FILES['land_patta_copy']['name'] != "") {
                    $landPattaCopy = cifileupload("land_patta_copy");
                    $land_patta_copy = $landPattaCopy["upload_status"] ? $landPattaCopy["uploaded_path"] : null;
                } else if (!empty($this->input->post("land_patta_copy_temp"))) {
                    $landPattaCopy = movedigilockerfile($this->input->post('land_patta_copy_temp'));
                    $land_patta_copy = $landPattaCopy["upload_status"] ? $landPattaCopy["uploaded_path"] : null;
                }

                if ($_FILES['updated_land_revenue_receipt']['name'] != "") {
                    // pre("jhhj");
                    $updatedLandRevenueReceipt = cifileupload("updated_land_revenue_receipt");
                    $updated_land_revenue_receipt = $updatedLandRevenueReceipt["upload_status"] ? $updatedLandRevenueReceipt["uploaded_path"] : null;
                } else if (!empty($this->input->post("updated_land_revenue_receipt_temp"))) {
                    $updatedLandRevenueReceipt = movedigilockerfile($this->input->post('updated_land_revenue_receipt_temp'));
                    $updated_land_revenue_receipt = $updatedLandRevenueReceipt["upload_status"] ? $updatedLandRevenueReceipt["uploaded_path"] : null;
                }
            }

            $salary_slip = "";
            $other_doc = "";
            if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 3) {
                if ($_FILES['salary_slip']['name'] != "") {
                    $salarySlip = cifileupload("salary_slip");
                    $salary_slip = $salarySlip["upload_status"] ? $salarySlip["uploaded_path"] : null;
                } else if (!empty($this->input->post("salary_slip_temp"))) {
                    $salarySlip = movedigilockerfile($this->input->post('salary_slip_temp'));
                    $salary_slip = $salarySlip["upload_status"] ? $salarySlip["uploaded_path"] : null;
                }
            } else if ($dbrow->form_data->applicant_category == 4) {
                if ($_FILES['other_doc']['name'] != "") {
                    $otherDoc = cifileupload("other_doc");
                    $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;
                } else if (!empty($this->input->post("other_doc_temp"))) {
                    $otherDoc = movedigilockerfile($this->input->post('other_doc_temp'));
                    $other_doc = $otherDoc["upload_status"] ? $otherDoc["uploaded_path"] : null;
                }
            }

            // pre($address_proof);

            $uploadedFiles = array(
                "address_proof_old" => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
                "identity_proof_old" => strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old"),
                "land_patta_copy_old" => strlen($land_patta_copy) ? $land_patta_copy : $this->input->post("land_patta_copy_old"),
                "updated_land_revenue_receipt_old" => strlen($updated_land_revenue_receipt) ? $updated_land_revenue_receipt : $this->input->post("updated_land_revenue_receipt_old"),
                "salary_slip_old" => strlen($salary_slip) ? $salary_slip : $this->input->post("salary_slip_old"),
                "other_doc_old" => strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old")
            );
            // pre($uploadedFiles);
            $this->session->set_flashdata('uploaded_files', $uploadedFiles);

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $this->fileuploads($objId);
            } else {


                if (!empty($this->input->post("address_proof_type"))) {
                    $data["form_data.address_proof_type"] = $this->input->post("address_proof_type");
                    $data["form_data.address_proof"] = strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old");
                }
                if (!empty($this->input->post("identity_proof_type"))) {
                    $data["form_data.identity_proof_type"] = $this->input->post("identity_proof_type");
                    $data["form_data.identity_proof"] = strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old");
                }



                if (!empty($this->input->post("land_patta_copy_type"))) {
                    $data["form_data.land_patta_copy_type"] = $this->input->post("land_patta_copy_type");
                    $data["form_data.land_patta_copy"] = strlen($land_patta_copy) ? $land_patta_copy : $this->input->post("land_patta_copy_old");
                }



                if (!empty($this->input->post("updated_land_revenue_receipt_type"))) {
                    $data["form_data.updated_land_revenue_receipt_type"] = $this->input->post("updated_land_revenue_receipt_type");
                    $data["form_data.updated_land_revenue_receipt"] = strlen($updated_land_revenue_receipt) ? $updated_land_revenue_receipt : $this->input->post("updated_land_revenue_receipt_old");
                }


                if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 3) {
                    if (!empty($this->input->post("salary_slip_type"))) {
                        $data["form_data.salary_slip_type"] = $this->input->post("salary_slip_type");
                        $data["form_data.salary_slip"] = strlen($salary_slip) ? $salary_slip : $this->input->post("salary_slip_old");
                    }
                } else if ($dbrow->form_data->applicant_category == 2 || $dbrow->form_data->applicant_category == 4) {
                    if (!empty($this->input->post("other_doc_type"))) {
                        $data["form_data.other_doc_type"] = $this->input->post("other_doc_type");
                        $data["form_data.other_doc"] = strlen($other_doc) ? $other_doc : $this->input->post("other_doc_old");
                    }
                }

                $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $data);
                //post data to KAAC

                $dbrow = $this->kaac_registration_model->get_by_doc_id($objId);

                //new code
                $postdata = array();


                $address_proof = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->address_proof));

                $attachment_one = array(
                    "encl" =>  $address_proof,
                    "docType" => "application/pdf",
                    "enclFor" => $dbrow->form_data->address_proof_type,
                    "enclType" => $dbrow->form_data->address_proof_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf"
                );
                $postdata['address_proof_doc'] = $attachment_one;

                $identity_proof = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->identity_proof));

                $attachment_two = array(
                    "encl" =>  $identity_proof,
                    "docType" => "application/pdf",
                    "enclFor" => $dbrow->form_data->identity_proof_type,
                    "enclType" => $dbrow->form_data->identity_proof_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf"
                );

                $postdata['identity_proof_doc'] = $attachment_two;

                if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 2) {

                    $land_document = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->land_patta_copy));

                    $attachment_three = array(
                        "encl" =>  $land_document,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->land_patta_copy_type,
                        "enclType" => $dbrow->form_data->land_patta_copy_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['land_patta_doc'] = $attachment_three;

                    $land_revenue_receipt = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->updated_land_revenue_receipt));

                    $attachment_four = array(
                        "encl" =>  $land_revenue_receipt,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->land_revenue_receipt_type,
                        "enclType" => $dbrow->form_data->land_revenue_receipt_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['land_receipt_rev_doc'] = $attachment_four;
                }
                if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 3) {

                    $salary_slip = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->salary_slip));

                    $attachment_five = array(
                        "encl" =>  $salary_slip,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->land_patta_copy_type,
                        "enclType" => $dbrow->form_data->land_patta_copy_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['salary_slip_doc'] = $attachment_five;
                }
                if ($dbrow->form_data->applicant_category == 4) {

                    $other_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->other_doc));

                    $attachment_six = array(
                        "encl" =>  $other_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->other_doc_type,
                        "enclType" => $dbrow->form_data->other_doc_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['other_doc'] = $attachment_six;
                }

                $spId = array(
                    "applId" => $dbrow->service_data->appl_id
                );

                $postdata['spId'] = $spId;

                //end code

                // pre($postdata);
                $url = $this->config->item('kaac_post_url');


                if ($dbrow->form_data->service_id == "ICERT") {

                    $curl = curl_init($url . "");
                } else {
                    $this->my_transactions();
                }
                // pre($url . "landholding/land_holding/update_certicop");
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
                    // pre($data["service_data.appl_status"]);
                    // exit();
                }
                redirect('spservices/kaacincomecertificate/registration/preview/' . $objId);
            } //End of if else
        } else {
            $this->session->set_flashdata('fail', 'Unable to update data, please try again!');
            $this->queryform($objId);
        }
    } //End of querysubmit()

    public function submit_to_backend($obj, $show_ack = false)
    {

        // pre($obj);
        if ($obj) {
            $dbRow = $this->kaac_registration_model->get_by_doc_id($obj);

            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }
            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")) {

                //procesing data
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by " . $dbrow->form_data->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by " . $dbrow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $postdata = array(
                    "application_ref_no" => $dbRow->service_data->appl_ref_no,
                    "applicant_title" => $dbRow->form_data->applicant_title,
                    "first_name" => $dbRow->form_data->first_name,
                    "last_name" => $dbRow->form_data->last_name,
                    "mobile_number" => $dbRow->form_data->mobile,
                    "email" => $dbRow->form_data->email,
                    "gender" => $dbRow->form_data->applicant_gender,
                    "category" => $dbRow->form_data->applicant_category,
                    "father_name" => $dbRow->form_data->father_name,
                    "circle_office" => $dbRow->form_data->circle_office,
                    "mouza_name" => $dbRow->form_data->mouza_name,
                    "revenue_village" => $dbRow->form_data->revenue_village,
                    "post_office" => $dbRow->form_data->post_office,
                    "police_station" => $dbRow->form_data->police_station,
                    "father_title" => $dbRow->form_data->father_title,
                );


                // pre("asdasd");
                if ($dbRow->form_data->applicant_category == 1 || $dbRow->form_data->applicant_category == 2) {
                    // pre("Hello");
                    $postdata["dag_no"] = $dbRow->form_data->dag_no;
                    $postdata["land_area_bigha"] = $dbRow->form_data->land_area_bigha;
                    $postdata["land_area_katha"] = $dbRow->form_data->land_area_katha;
                    $postdata["land_area_loosa"] = $dbRow->form_data->land_area_loosa;
                    $postdata["land_area_sq_ft"] = $dbRow->form_data->land_area_sq_ft;
                    $postdata["patta_type"] = $dbRow->form_data->patta_type;
                    $postdata['periodic_patta_no'] = $dbRow->form_data->periodic_patta_no;
                }

                $address_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->address_proof));

                $attachment_one = array(
                    "encl" =>  $address_proof,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->address_proof_type,
                    "enclType" => $dbRow->form_data->address_proof_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf"
                );
                $postdata['address_proof_doc'] = $attachment_one;

                $identity_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->identity_proof));

                $attachment_two = array(
                    "encl" =>  $identity_proof,
                    "docType" => "application/pdf",
                    "enclFor" => $dbRow->form_data->identity_proof_type,
                    "enclType" => $dbRow->form_data->identity_proof_type,
                    "id" => "93963",
                    "doctypecode" => "6863",
                    "docRefId" => "8301",
                    "enclExtn" => "pdf"
                );

                $postdata['identity_proof_doc'] = $attachment_two;

                if ($dbRow->form_data->applicant_category == 1 || $dbRow->form_data->applicant_category == 2) {

                    $land_document = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->land_patta_copy));

                    $attachment_three = array(
                        "encl" =>  $land_document,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->land_patta_copy_type,
                        "enclType" => $dbRow->form_data->land_patta_copy_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['land_patta_doc'] = $attachment_three;

                    $land_revenue_receipt = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->updated_land_revenue_receipt));

                    $attachment_four = array(
                        "encl" =>  $land_revenue_receipt,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->updated_land_revenue_receipt_type,
                        "enclType" => $dbRow->form_data->updated_land_revenue_receipt_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['land_receipt_rev_doc'] = $attachment_four;
                }
                if ($dbRow->form_data->applicant_category == 1 || $dbRow->form_data->applicant_category == 3) {

                    $salary_slip = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->salary_slip));

                    $attachment_five = array(
                        "encl" =>  $salary_slip,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->salary_slip_type,
                        "enclType" => $dbRow->form_data->salary_slip_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['salary_slip_doc'] = $attachment_five;
                }
                if ($dbRow->form_data->applicant_category == 4) {

                    $other_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->other_doc));

                    $attachment_six = array(
                        "encl" =>  $other_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->other_doc_type,
                        "enclType" => $dbRow->form_data->other_doc_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['other_doc'] = $attachment_six;
                }
                $spId = array(
                    "applId" => $dbRow->service_data->appl_id
                );

                $postdata['spId'] = $spId;


                // pre($dbRow->form_data->service_id);
                $url = $this->config->item('kaac_post_url');

                // pre(json_encode($postdata));

                if ($dbRow->form_data->service_id == "ICERT") {

                    $curl = curl_init($url . "income/income_certificate/post_certicopy.php");
                } else {
                    $this->my_transactions();
                }
                // pre($url . "income/income_certificate/post_certicopy.php");
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
                            "service_name" => 'Appl.for Income Cetificate',
                            "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                            "app_ref_no" => $dbRow->service_data->appl_ref_no
                        );
                        sms_provider("submission", $sms);
                        redirect('spservices/kaacincomecertificate/registration/acknowledgement/' . $obj);
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
            } else {
                $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                $this->my_transactions();
            }
        }
        redirect('iservices/transactions');
    }


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
        $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);
        //var_dump($dbRow); die;
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $dbRow->service_data->service_name,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('kaacincomecertificate/kaac_income_preview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaacincomecertificate/');
        } //End of if else
    } //End of preview()

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


                $rtps_trans_id = $applicationRow->service_data->appl_ref_no;


                $applicationRowData = [
                    "rtps_trans_id" => $rtps_trans_id,
                    "submission_date" => $applicationRow->service_data->submission_date,
                    "applicant_first_name" => $applicationRow->form_data->first_name,
                    "applicant_last_name" => $applicationRow->form_data->last_name,
                    "service_name" =>  $applicationRow->service_data->service_name,
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

            $data['pageTitle'] = "Acknowledgement";
            $this->load->view('includes/frontend/header');
            $this->load->view('/kaacincomecertificate/kaac_income_first_ack', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            redirect('spservices/applications/');
        } //End of if else
    }

    public function query_payment_break_down($obj_id = null)
    {
        // $user = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});

        $filter = array(
            "_id" => $obj_id ? new ObjectId($obj_id) : NULL,
            "service_data.appl_status" => "FRS"
        );

        $dbrow = $this->kaac_registration_model->get_row($filter);

        if (!empty($dbrow)) {
            $data['obj_id'] = $obj_id;
            $data['amount'] = $dbrow->form_data->frs_request->amount;
        } else {
            $this->my_transactions();
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('kaacincomecertificate/kaac_query_charge_template', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function querypaymentsubmit($obj = null)
    {
        // pre($obj);
        if ($obj) {
            $dbRow = $this->kaac_registration_model->get_by_doc_id($obj);
            if (count((array)$dbRow)) {

                if ($dbRow->service_data->appl_status == "QA") {
                    $this->my_transactions();
                }


                $postdata = array(
                    "application_ref_no" => $dbRow->service_data->appl_ref_no,
                    "fee_paid" => $dbRow->form_data->query_payment_response->AMOUNT,
                    "payment_mode" => "Online",
                    "payment_ref_number" => $dbRow->form_data->query_payment_response->GRN,

                );

                $spId = array(
                    "applId" => $dbRow->service_data->appl_id
                );

                $postdata['spId'] = $spId;

                $url = $this->config->item('kaac_post_url');


                if ($dbRow->form_data->service_id == "ICERT") {

                    $curl = curl_init($url . "income/income_certificate/update_certicopy.php");
                } else {
                    $this->my_transactions();
                }
                // pre($url . "encumbrance/non_encumbrance/post_certicopy.php");
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
                } elseif ($response) {
                    $response = json_decode($response, true);  //pre($response);
                    // if ($response["ref"]["status"] === "success") {

                    $processing_history = $dbRow->processing_history ?? array();
                    $processing_history[] = array(
                        "processed_by" => "Payment Query submitted by " . $dbrow->form_data->applicant_name,
                        "action_taken" => "Payment Query submitted",
                        "remarks" => "Payment Query submitted by " . $dbrow->form_data->applicant_name,
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );

                    $data = array(
                        "service_data.appl_status" => "QA",
                        'processing_history' => $processing_history,
                    );

                    $this->kaac_registration_model->update_where(['_id' => new ObjectId($obj)], $data);

                    $this->session->set_flashdata('success', 'Your application has been successfully updated');

                    // pre("Success");
                    redirect('spservices/kaacincomecertificate/registration/payment_acknowledgement/' . $obj);
                } else {
                    $this->session->set_flashdata('errmessage', 'Unable to update data!!! Please try again.');
                    $this->my_transactions();
                } //End of if else
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
            "_id" => $obj_id ? new ObjectId($obj_id) : NULL
        );

        $dbrow = $this->kaac_registration_model->get_row($filter);
        $data['dbrow'] = $dbrow;

        $this->load->view('includes/frontend/header');
        $this->load->view('kaacincomecertificate/kaac_second_ack', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function mouza()
    {
        $id = $this->input->post('jsonbody');
        $endpoint = 'https://artpskaac.in/mouza_list';
        $b = "%22circle_id%22%3A%20%22{$id}%22";

        $params = 'jsonbody={' . $b . '}';
        
        $url = $endpoint . '?' . $params;

        $response = $this->kaac_registration_model->apiCall($url);

        // pre($response);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    
    public function revenue_village()
    {

        $id = $this->input->post('jsonbody');
        $endpoint = 'https://artpskaac.in/village_list';
        $b = "%22mouza_id%22%3A%20%22{$id}%22";

        $params = 'jsonbody={' . $b . '}';
        
        $url = $endpoint . '?' . $params;

        $response = $this->kaac_registration_model->apiCall($url);

        // pre($response);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }
}
