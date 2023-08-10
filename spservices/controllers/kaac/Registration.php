<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

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

    public function index($obj_id = null)
    {
        // pre($obj_id);
        // check_application_count_for_citizen(); 
        if ($obj_id == "DCTH") {
            $data = array("pageTitleId" => "DCTH", "pageTitle" => "Issuance of Certified Copies of Chitha - KAAC");
            $data["dbrow"] = null;
        } else if ($obj_id == "CCJ") {
            $data = array("pageTitleId" => "CCJ", "pageTitle" => "Issuance of Certified Copies of Jamabandi - KAAC");
            $data["dbrow"] = null;
        } else if ($obj_id == "CCM") {
            $data = array("pageTitleId" => "CCM", "pageTitle" => "Certified Copy of Mutation - KAAC");
            $data["dbrow"] = null;
        } else if ($obj_id == "DLP") {
            $data = array("pageTitleId" => "DLP", "pageTitle" => "Issuance of Duplicate Copies of Land Patta - KAAC");
            $data["dbrow"] = null;
        } else if ($obj_id == "ITMKA") {
            $data = array("pageTitleId" => "ITMKA", "pageTitle" => "Issuance of Trace Map - KAAC");
            $data["dbrow"] = null;
        } else if ($obj_id == "LHOLD") {
            $data = array("pageTitleId" => "LHOLD", "pageTitle" => "Issuance of Land Holding Certificate - KAAC");
            $data["dbrow"] = null;
        } else if ($obj_id == "LRCC") {
            $data = array("pageTitleId" => "LRCC", "pageTitle" => "Issuance of Land Revenue Clearance Certificate - KAAC");
            $data["dbrow"] = null;
        } else if ($obj_id == "LVC") {
            $data = array("pageTitleId" => "LVC", "pageTitle" => "Issuance of Land Valuation Certificate - KAAC");
            $data["dbrow"] = null;
        } else if ($obj_id == "NECKA") {
            $data = array("pageTitleId" => "NECKA", "pageTitle" => "Issuance of Non-Encumbrance Certificate - KAAC");
            $data["dbrow"] = null;
        } else if ($this->checkObjectId($obj_id)) {

            $filter = array("_id" => new ObjectId($obj_id), "service_data.appl_status" => "DRAFT");
            $data["dbrow"] = $this->kaac_registration_model->get_row($filter);
        } else {
            $this->my_transactions();
        }
        $data['usser_type'] = $this->slug;
        // $data["sessions"] = $this->fetchsessions(15);
        $this->load->view('includes/frontend/header');
        $this->load->view('kaac/first_group/kaac_registration_form', $data);
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

        // pre($this->input->post());
        $objId = $this->input->post("obj_id");
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
        $this->form_validation->set_rules('dag_no', 'Dag No', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('periodic_patta_no', 'Annual Patta No', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('patta_type', 'Patta Type', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('land_area_katha', 'Kotha', 'trim|required|integer|xss_clean|strip_tags');
        $this->form_validation->set_rules('land_area_bigha', 'Bigha', 'trim|required|integer|xss_clean|strip_tags');
        $this->form_validation->set_rules('land_area_loosa', 'Loosa', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('land_area_sq_ft', 'Land Area', 'trim|required|xss_clean|strip_tags');

        if ($this->serviceId == "CCM") {
            $this->form_validation->set_rules('mut_name_title', 'Title', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
            $this->form_validation->set_rules('mut_first_name', 'First Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
            $this->form_validation->set_rules('mut_last_name', 'Last Name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
            $this->form_validation->set_rules('mut_father_title', 'Title', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
            $this->form_validation->set_rules('mut_gender', 'Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
            $this->form_validation->set_rules('mut_father_name', 'Father name', 'trim|required|xss_clean|strip_tags|regex_match[/^[a-zA-Z ]*$/]|max_length[255]');
            $this->form_validation->set_rules('mut_mobile', 'Contact number', 'trim|integer|required|exact_length[10]|xss_clean|strip_tags');
            $this->form_validation->set_rules('mut_email', 'Email id', 'valid_email|trim|xss_clean|strip_tags');
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
                    // "form_data.service_id" => $this->serviceId
                );
                $rows = $this->kaac_registration_model->get_row($filter);

                if ($rows == false)
                    break;
            }


            if ($this->serviceId == "DCTH") {
                $this->base_serviceId = "1775";
            } else if ($this->serviceId == "CCJ") {
                $this->base_serviceId = "1618";
            } else if ($this->serviceId == "CCM") {
                $this->base_serviceId = "1854";
            } else if ($this->serviceId == "DLP") {
                $this->base_serviceId = "1619";
            } else if ($this->serviceId == "ITMKA") {
                $this->base_serviceId = "1853";
            } else if ($this->serviceId == "LHOLD") {
                $this->base_serviceId = "1774";
            } else if ($this->serviceId == "LRCC") {
                $this->base_serviceId = "1852";
            } else if ($this->serviceId == "LVC") {
                $this->base_serviceId = "1620";
            } else if ($this->serviceId == "NECKA") {
                $this->base_serviceId = "1622";
            }

            // pre($this->serviceId);
            if($this->input->post("circle_office") == 1383118 || $this->input->post("circle_office") == 1383120 || $this->input->post("circle_office") == 1383121){
                $this->districtName = "KARBI ANGLONG";
                $this->submmissionLocation = "Revenue Office KAAC(Revenue Office-KAAC- ".$this->input->post("circle_office_name")." )";
            }
            elseif($this->input->post("circle_office") == 1383119){
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
                "submission_location" => $this->submmissionLocation,
                "submission_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "service_timeline" => "7 Days",
                "appl_status" => "DRAFT",
                "district" => $this->districtName
            ];

            $form_data = [
                'user_id' => $sessionUser['userId']->{'$id'},
                'user_type' => $this->slug,
                "service_id" => $this->serviceId,
                "applicant_name" => $this->input->post("applicant_title").' '.$this->input->post("first_name").' '.$this->input->post("last_name"),
                'first_name' => $this->input->post("first_name"),
                'last_name' => $this->input->post("last_name"),
                'applicant_gender' => $this->input->post("gender"),
                'mobile' => $this->input->post("mobile"),
                'email' => $this->input->post("email"),
                'father_name' => $this->input->post("father_name"),
                'father_title' => $this->input->post("father_title"),
                'applicant_title' => $this->input->post("applicant_title"),
                'circle_office' => $this->input->post("circle_office"),
                'circle_office_name' => $this->input->post("circle_office_name"),
                'mouza_name' => $this->input->post("mouza_name"),
                'mouza_office_name' => $this->input->post("mouza_office_name"),
                'revenue_village' => $this->input->post("revenue_village"),
                'revenue_village_name' => $this->input->post("revenue_village_name"),
                'police_station' => $this->input->post("police_station"),
                'police_station_name' => $this->input->post("police_station_name"),
                'post_office' => $this->input->post("post_office"),
                'dag_no' => $this->input->post("dag_no"),
                'periodic_patta_no' => $this->input->post("periodic_patta_no"),
                'patta_type' => $this->input->post("patta_type"),
                'patta_type_name' => $this->input->post("patta_type_name"),
                'land_area_bigha' => $this->input->post("land_area_bigha"),
                'land_area_katha' => $this->input->post("land_area_katha"),
                'land_area_loosa' => $this->input->post("land_area_loosa"),
                'land_area_sq_ft' => $this->input->post("land_area_sq_ft"),
                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];
            
            if ($this->serviceId == "CCM") {

                $form_data['mut_name_title'] = $this->input->post("mut_name_title");
                $form_data['mut_first_name'] = $this->input->post("mut_first_name");
                $form_data['mut_last_name'] = $this->input->post("mut_last_name");
                $form_data['mut_gender'] = $this->input->post("mut_gender");
                $form_data['mut_father_title'] = $this->input->post("mut_father_title");
                $form_data['mut_father_name'] = $this->input->post("mut_father_name");
                $form_data['mut_mobile'] = $this->input->post("mut_mobile");
                $form_data['mut_email'] = $this->input->post("mut_email");
            }

            if (strlen($objId)) {

                // pre($this->input->post("address_proof_type"));
                if ($this->serviceId == "DCTH" || $this->serviceId == "CCM" || $this->serviceId == "ITMKA" || $this->serviceId == "LHOLD") {
                    // pre("sdaasd");
                    $form_data["address_proof_type"] = $this->input->post("address_proof_type");
                    $form_data["address_proof"] = $this->input->post("address_proof");
                    $form_data["identity_proof_type"] = $this->input->post("identity_proof_type");
                    $form_data["identity_proof"] = $this->input->post("identity_proof");
                }

                if ($this->serviceId == "DCTH" || $this->serviceId == "LRCC" || $this->serviceId == "ITMKA" || $this->serviceId == "LHOLD") {

                    $form_data['land_patta_copy_type'] = $this->input->post("land_patta_copy_type");
                    $form_data['land_patta_copy'] = $this->input->post("land_patta_copy");
                }

                if ($this->serviceId == "DCTH" || $this->serviceId == "LHOLD") {
                    $form_data['updated_land_revenue_receipt_type'] = $this->input->post("updated_land_revenue_receipt_type");
                    $form_data['updated_land_revenue_receipt'] = $this->input->post("updated_land_revenue_receipt");
                }

                if ($this->serviceId == "CCJ" || $this->serviceId == "LVC" || $this->serviceId == "NECKA") {
                    $form_data['Up_to_date_original_land_documents_type'] = $this->input->post("Up_to_date_original_land_documents_type");
                    $form_data['Up_to_date_original_land_documents'] = $this->input->post("Up_to_date_original_land_documents");
                }

                if ($this->serviceId == "CCJ" || $this->serviceId == "DLP" || $this->serviceId == "NECKA" || $this->serviceId == "LVC") {
                    $form_data['up_to_date_khajna_receipt_type'] = $this->input->post("up_to_date_khajna_receipt_type");
                    $form_data['up_to_date_khajna_receipt'] = $this->input->post("up_to_date_khajna_receipt");
                }

                if ($this->serviceId == "CCM") {
                    $form_data['copy_of_jamabandi_type'] = $this->input->post("copy_of_jamabandi_type");
                    $form_data['copy_of_jamabandi'] = $this->input->post("copy_of_jamabandi");

                    $form_data['revenue_patta_copy_type'] = $this->input->post("revenue_patta_copy_type");
                    $form_data['revenue_patta_copy'] = $this->input->post("revenue_patta_copy");

                    $form_data['copy_of_chitha_type'] = $this->input->post("copy_of_chitha_type");
                    $form_data['copy_of_chitha'] = $this->input->post("copy_of_chitha");
                }

                if ($this->serviceId == "CCM" || $this->serviceId == "ITMKA") {
                    $form_data['settlement_land_patta_copy_type'] = $this->input->post("settlement_land_patta_copy_type");
                    $form_data['settlement_land_patta_copy'] = $this->input->post("settlement_land_patta_copy");

                    $form_data['land_revenue_receipt_type'] = $this->input->post("land_revenue_receipt_type");
                    $form_data['land_revenue_receipt'] = $this->input->post("land_revenue_receipt");
                }

                if ($this->serviceId == "DLP") {
                    $form_data['police_verification_report_type'] = $this->input->post("police_verification_report_type");
                    $form_data['police_verification_report'] = $this->input->post("police_verification_report");

                    $form_data['photocopy_of_existing_land_documents_type'] = $this->input->post("photocopy_of_existing_land_documents_type");
                    $form_data['photocopy_of_existing_land_documents'] = $this->input->post("photocopy_of_existing_land_documents");

                    $form_data['no_dues_certificate_from_bank_type'] = $this->input->post("no_dues_certificate_from_bank_type");
                    $form_data['no_dues_certificate_from_bank'] = $this->input->post("no_dues_certificate_from_bank");
                }

                if ($this->serviceId == "LRCC") {
                    $form_data['last_time_paid_Land_revenue_receipt_type'] = $this->input->post("last_time_paid_Land_revenue_receipt_type");
                    $form_data['last_time_paid_Land_revenue_receipt'] = $this->input->post("last_time_paid_Land_revenue_receipt");
                }
            }

            $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data
            ];
            // pre($inputs);
            // exit();
            if (strlen($objId)) {
                $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $inputs);
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                // pre($objId);
                redirect('spservices/kaac/registration/fileuploads/' . $objId);
                exit();
            } else {
                $insert = $this->kaac_registration_model->insert($inputs);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    // pre($objectId);
                    redirect('spservices/kaac/registration/fileuploads/' . $objectId);
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
            $this->load->view('kaac/first_group/kaac_fileuploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaac/registration/');
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

        // $this->form_validation->set_rules('passport_photo_type', 'Passport photo type', 'required');
        // $this->form_validation->set_rules('signature_type', 'Applicant signature', 'required');
        // $this->form_validation->set_rules('registration_card_type', 'Registration card type', 'required');
        // $this->form_validation->set_rules('admit_card_type', 'Admit card type', 'required');

        // if (empty($this->input->post("registration_card_old"))) {
        //     if ((empty($this->input->post("registration_card_temp"))) && (($_FILES['registration_card']['name'] == ""))) {
        //         $this->form_validation->set_rules('registration_card', 'Registration card document', 'required');
        //     }
        // }

        // if (empty($this->input->post("admit_card_old"))) {
        //     if ((empty($this->input->post("admit_card_temp"))) && (($_FILES['admit_card']['name'] == ""))) {
        //         $this->form_validation->set_rules('admit_card', 'Admit card document', 'required');
        //     }
        // }

        if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "CCM" || $dbrow->form_data->service_id == "ITMKA" || $dbrow->form_data->service_id == "LHOLD") {
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
        }

        if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LRCC" || $dbrow->form_data->service_id == "ITMKA" || $dbrow->form_data->service_id == "LHOLD") {
            $this->form_validation->set_rules('land_patta_copy_type', 'Land Patta Copy type', 'required');
            if (empty($this->input->post("land_patta_copy_old"))) {
                if ((empty($this->input->post("land_patta_copy_temp"))) && (($_FILES['land_patta_copy']['name'] == ""))) {
                    $this->form_validation->set_rules('land_patta_copy', 'Land Patta Copy document', 'required');
                }

                if ((!empty($this->input->post("land_patta_copy_type"))) && (($_FILES['land_patta_copy']['name'] == "") && (empty($this->input->post("land_patta_copy_temp"))))) {
                    $this->form_validation->set_rules('land_patta_copy', 'Land Patta Copy document', 'required');
                }
            }
        }

        if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LHOLD") {
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

        if ($dbrow->form_data->service_id == "CCJ" || $dbrow->form_data->service_id == "LVC" || $dbrow->form_data->service_id == "NECKA") {
            $this->form_validation->set_rules('Up_to_date_original_land_documents_type', 'Marksheet type', 'required');
            if (empty($this->input->post("Up_to_date_original_land_documents_old"))) {
                if ((empty($this->input->post("Up_to_date_original_land_documents_type"))) && (($_FILES['Up_to_date_original_land_documents']['name'] != "") || (!empty($this->input->post("Up_to_date_original_land_documents_temp"))))) {
                    $this->form_validation->set_rules('Up_to_date_original_land_documents_type', 'Updated Land revenue receipt type', 'required');
                }

                if ((!empty($this->input->post("Up_to_date_original_land_documents_type"))) && (($_FILES['Up_to_date_original_land_documents']['name'] == "") && (empty($this->input->post("Up_to_date_original_land_documents_temp"))))) {
                    $this->form_validation->set_rules('Up_to_date_original_land_documents', 'Updated Land revenue receipt', 'required');
                }
            }
        }


        if ($dbrow->form_data->service_id == "CCJ" || $dbrow->form_data->service_id == "DLP" || $dbrow->form_data->service_id == "NECKA" || $dbrow->form_data->service_id == "LVC") {
            $this->form_validation->set_rules('up_to_date_khajna_receipt_type', 'Up To Date Khajna Receipt type', 'required');
            if (empty($this->input->post("up_to_date_khajna_receipt_old"))) {
                if ((empty($this->input->post("up_to_date_khajna_receipt_type"))) && (($_FILES['up_to_date_khajna_receipt']['name'] != "") || (!empty($this->input->post("up_to_date_khajna_receipt_temp"))))) {
                    $this->form_validation->set_rules('up_to_date_khajna_receipt_type', 'Updated Land revenue receipt type', 'required');
                }

                if ((!empty($this->input->post("up_to_date_khajna_receipt_type"))) && (($_FILES['up_to_date_khajna_receipt']['name'] == "") && (empty($this->input->post("up_to_date_khajna_receipt_temp"))))) {
                    $this->form_validation->set_rules('up_to_date_khajna_receipt', 'Updated Land revenue receipt', 'required');
                }
            }
        }


        if ($dbrow->form_data->service_id == "CCM") {
            $this->form_validation->set_rules('copy_of_jamabandi_type', 'Copy of Jamabandi type', 'required');
            if (empty($this->input->post("copy_of_jamabandi_old"))) {
                if ((empty($this->input->post("copy_of_jamabandi_type"))) && (($_FILES['copy_of_jamabandi']['name'] != "") || (!empty($this->input->post("copy_of_jamabandi_temp"))))) {
                    $this->form_validation->set_rules('copy_of_jamabandi_type', 'Copy of Jamabandi type', 'required');
                }

                if ((!empty($this->input->post("copy_of_jamabandi_type"))) && (($_FILES['copy_of_jamabandi']['name'] == "") && (empty($this->input->post("copy_of_jamabandi_temp"))))) {
                    $this->form_validation->set_rules('copy_of_jamabandi', 'Copy of Jamabandi', 'required');
                }
            }


            $this->form_validation->set_rules('revenue_patta_copy_type', 'Revenue Patta Copy type', 'required');
            if (empty($this->input->post("revenue_patta_copy_old"))) {
                if ((empty($this->input->post("revenue_patta_copy_type"))) && (($_FILES['revenue_patta_copy']['name'] != "") || (!empty($this->input->post("revenue_patta_copy_temp"))))) {
                    $this->form_validation->set_rules('revenue_patta_copy_type', 'Revenue Patta Copy type', 'required');
                }

                if ((!empty($this->input->post("revenue_patta_copy_type"))) && (($_FILES['revenue_patta_copy']['name'] == "") && (empty($this->input->post("revenue_patta_copy_temp"))))) {
                    $this->form_validation->set_rules('revenue_patta_copy', 'Revenue Patta Copy', 'required');
                }
            }

            $this->form_validation->set_rules('copy_of_chitha_type', 'Copy of Chitha type', 'required');
            if (empty($this->input->post("copy_of_chitha_old"))) {
                if ((empty($this->input->post("copy_of_chitha_type"))) && (($_FILES['copy_of_chitha']['name'] != "") || (!empty($this->input->post("copy_of_chitha_temp"))))) {
                    $this->form_validation->set_rules('copy_of_chitha_type', 'Copy of Chitha type', 'required');
                }

                if ((!empty($this->input->post("copy_of_chitha_type"))) && (($_FILES['copy_of_chitha']['name'] == "") && (empty($this->input->post("copy_of_chitha_temp"))))) {
                    $this->form_validation->set_rules('copy_of_chitha', 'Copy of Chitha receipt', 'required');
                }
            }
        }

        if ($dbrow->form_data->service_id == "CCM" || $dbrow->form_data->service_id == "ITMKA") {
            $this->form_validation->set_rules('settlement_land_patta_copy_type', 'Setteltment Land Patta Copy type', 'required');
            if (empty($this->input->post("settlement_land_patta_copy_old"))) {
                if ((empty($this->input->post("settlement_land_patta_copy_type"))) && (($_FILES['settlement_land_patta_copy']['name'] != "") || (!empty($this->input->post("settlement_land_patta_copy_temp"))))) {
                    $this->form_validation->set_rules('settlement_land_patta_copy_type', 'Setteltment Land Patta Copy type', 'required');
                }

                if ((!empty($this->input->post("settlement_land_patta_copy_type"))) && (($_FILES['settlement_land_patta_copy']['name'] == "") && (empty($this->input->post("settlement_land_patta_copy_temp"))))) {
                    $this->form_validation->set_rules('settlement_land_patta_copy', 'Setteltment Land Patta Copy', 'required');
                }
            }
            $this->form_validation->set_rules('land_revenue_receipt_type', 'Land Revenue Reciept type', 'required');
            if (empty($this->input->post("land_revenue_receipt_old"))) {
                if ((empty($this->input->post("land_revenue_receipt_type"))) && (($_FILES['land_revenue_receipt']['name'] != "") || (!empty($this->input->post("land_revenue_receipt_temp"))))) {
                    $this->form_validation->set_rules('land_revenue_receipt_type', 'Land Revenue Reciept type', 'required');
                }

                if ((!empty($this->input->post("land_revenue_receipt_type"))) && (($_FILES['land_revenue_receipt']['name'] == "") && (empty($this->input->post("land_revenue_receipt_temp"))))) {
                    $this->form_validation->set_rules('land_revenue_receipt', 'Land Revenue Reciept', 'required');
                }
            }
        }

        if ($dbrow->form_data->service_id == "DLP") {
            $this->form_validation->set_rules('police_verification_report_type', 'Police Verification Report type', 'required');
            if (empty($this->input->post("police_verification_report_old"))) {
                if ((empty($this->input->post("police_verification_report_type"))) && (($_FILES['police_verification_report']['name'] != "") || (!empty($this->input->post("police_verification_report_temp"))))) {
                    $this->form_validation->set_rules('police_verification_report_type', 'Police Verification Report type', 'required');
                }

                if ((!empty($this->input->post("police_verification_report_type"))) && (($_FILES['police_verification_report']['name'] == "") && (empty($this->input->post("police_verification_report_temp"))))) {
                    $this->form_validation->set_rules('police_verification_report', 'Police Verification Report', 'required');
                }
            }
            $this->form_validation->set_rules('photocopy_of_existing_land_documents_type', 'Photocopy of Existing Land Documents type', 'required');
            if (empty($this->input->post("photocopy_of_existing_land_documents_old"))) {
                if ((empty($this->input->post("photocopy_of_existing_land_documents_type"))) && (($_FILES['photocopy_of_existing_land_documents']['name'] != "") || (!empty($this->input->post("photocopy_of_existing_land_documents_temp"))))) {
                    $this->form_validation->set_rules('photocopy_of_existing_land_documents_type', 'Photocopy of Existing Land Documents type', 'required');
                }

                if ((!empty($this->input->post("photocopy_of_existing_land_documents_type"))) && (($_FILES['photocopy_of_existing_land_documents']['name'] == "") && (empty($this->input->post("photocopy_of_existing_land_documents_temp"))))) {
                    $this->form_validation->set_rules('photocopy_of_existing_land_documents', 'Photocopy of Existing Land Documents', 'required');
                }
            }
            $this->form_validation->set_rules('no_dues_certificate_from_bank_type', 'No Dues Certificate_from Bank type', 'required');
            if (empty($this->input->post("no_dues_certificate_from_bank_old"))) {
                if ((empty($this->input->post("no_dues_certificate_from_bank_type"))) && (($_FILES['no_dues_certificate_from_bank']['name'] != "") || (!empty($this->input->post("no_dues_certificate_from_bank_temp"))))) {
                    $this->form_validation->set_rules('no_dues_certificate_from_bank_type', 'No Dues Certificate_from Bank type', 'required');
                }

                if ((!empty($this->input->post("no_dues_certificate_from_bank_type"))) && (($_FILES['no_dues_certificate_from_bank']['name'] == "") && (empty($this->input->post("no_dues_certificate_from_bank_temp"))))) {
                    $this->form_validation->set_rules('no_dues_certificate_from_bank', 'No Dues Certificate_from Bank', 'required');
                }
            }
        }

        if ($dbrow->form_data->service_id == "LRCC") {
            $this->form_validation->set_rules('last_time_paid_Land_revenue_receipt_type', 'Last Time Paid Land Revenue Receipt type', 'required');
            if (empty($this->input->post("last_time_paid_Land_revenue_receipt_old"))) {
                if ((empty($this->input->post("last_time_paid_Land_revenue_receipt_type"))) && (($_FILES['last_time_paid_Land_revenue_receipt']['name'] != "") || (!empty($this->input->post("last_time_paid_Land_revenue_receipt_temp"))))) {
                    $this->form_validation->set_rules('last_time_paid_Land_revenue_receipt_type', 'Last Time Paid Land Revenue Receipt type', 'required');
                }

                if ((!empty($this->input->post("last_time_paid_Land_revenue_receipt_type"))) && (($_FILES['last_time_paid_Land_revenue_receipt']['name'] == "") && (empty($this->input->post("last_time_paid_Land_revenue_receipt_temp"))))) {
                    $this->form_validation->set_rules('last_time_paid_Land_revenue_receipt', 'Last Time Paid Land Revenue Receipt', 'required');
                }
            }
        }

        // if (empty($this->input->post("soft_copy_old")) && $this->slug !== 'user') {
        //     if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {

        //         $this->form_validation->set_rules('soft_copy_type', 'Soft Copy Type required', 'required');
        //         $this->form_validation->set_rules('soft_copy', 'Soft Copy document required', 'required');
        //     }
        // }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $address_proof = "";
        $identity_proof = "";
        if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "CCM" || $dbrow->form_data->service_id == "ITMKA" || $dbrow->form_data->service_id == "LHOLD") {

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
        }

        $land_patta_copy = "";
        if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LRCC" || $dbrow->form_data->service_id == "ITMKA" || $dbrow->form_data->service_id == "LHOLD") {
            if ($_FILES['land_patta_copy']['name'] != "") {
                $landPattaCopy = cifileupload("land_patta_copy");
                $land_patta_copy = $landPattaCopy["upload_status"] ? $landPattaCopy["uploaded_path"] : null;
            } else if (!empty($this->input->post("land_patta_copy_temp"))) {
                $landPattaCopy = movedigilockerfile($this->input->post('land_patta_copy_temp'));
                $land_patta_copy = $landPattaCopy["upload_status"] ? $landPattaCopy["uploaded_path"] : null;
            }
        }

        $updated_land_revenue_receipt = "";
        if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LHOLD") {

            if ($_FILES['updated_land_revenue_receipt']['name'] != "") {
                // pre("jhhj");
                $updatedLandRevenueReceipt = cifileupload("updated_land_revenue_receipt");
                $updated_land_revenue_receipt = $updatedLandRevenueReceipt["upload_status"] ? $updatedLandRevenueReceipt["uploaded_path"] : null;
            } else if (!empty($this->input->post("updated_land_revenue_receipt_temp"))) {
                $updatedLandRevenueReceipt = movedigilockerfile($this->input->post('updated_land_revenue_receipt_temp'));
                $updated_land_revenue_receipt = $updatedLandRevenueReceipt["upload_status"] ? $updatedLandRevenueReceipt["uploaded_path"] : null;
            }
        }

        $Up_to_date_original_land_documents = "";
        if ($dbrow->form_data->service_id == "CCJ" || $dbrow->form_data->service_id == "LVC" || $dbrow->form_data->service_id == "NECKA") {

            if ($_FILES['Up_to_date_original_land_documents']['name'] != "") {
                $UpToDateOriginalLandDocuments = cifileupload("Up_to_date_original_land_documents");
                $Up_to_date_original_land_documents = $UpToDateOriginalLandDocuments["upload_status"] ? $UpToDateOriginalLandDocuments["uploaded_path"] : null;
            } else if (!empty($this->input->post("Up_to_date_original_land_documents_temp"))) {
                $UpToDateOriginalLandDocuments = movedigilockerfile($this->input->post('Up_to_date_original_land_documents_temp'));
                $Up_to_date_original_land_documents = $UpToDateOriginalLandDocuments["upload_status"] ? $UpToDateOriginalLandDocuments["uploaded_path"] : null;
            }
        }

        $up_to_date_khajna_receipt = "";
        if ($dbrow->form_data->service_id == "CCJ" || $dbrow->form_data->service_id == "DLP" || $dbrow->form_data->service_id == "NECKA" || $dbrow->form_data->service_id == "LVC") {
            if ($_FILES['up_to_date_khajna_receipt']['name'] != "") {
                $upToDateKhajnaReceipt = cifileupload("up_to_date_khajna_receipt");
                $up_to_date_khajna_receipt = $upToDateKhajnaReceipt["upload_status"] ? $upToDateKhajnaReceipt["uploaded_path"] : null;
            } else if (!empty($this->input->post("up_to_date_khajna_receipt_temp"))) {
                $upToDateKhajnaReceipt = movedigilockerfile($this->input->post('up_to_date_khajna_receipt_temp'));
                $up_to_date_khajna_receipt = $upToDateKhajnaReceipt["upload_status"] ? $upToDateKhajnaReceipt["uploaded_path"] : null;
            }
        }


        $copy_of_jamabandi = "";
        $revenue_patta_copy = "";
        $copy_of_chitha = "";
        if ($dbrow->form_data->service_id == "CCM") {
            if ($_FILES['copy_of_jamabandi']['name'] != "") {
                $copyOfJamabandi = cifileupload("copy_of_jamabandi");
                $copy_of_jamabandi = $copyOfJamabandi["upload_status"] ? $copyOfJamabandi["uploaded_path"] : null;
            } else if (!empty($this->input->post("copy_of_jamabandi_temp"))) {
                $copyOfJamabandi = movedigilockerfile($this->input->post('copy_of_jamabandi_temp'));
                $copy_of_jamabandi = $copyOfJamabandi["upload_status"] ? $copyOfJamabandi["uploaded_path"] : null;
            }

            // pre($copy_of_jamabandi);
            if ($_FILES['revenue_patta_copy']['name'] != "") {
                $revenuePattaCopy = cifileupload("revenue_patta_copy");
                $revenue_patta_copy = $revenuePattaCopy["upload_status"] ? $revenuePattaCopy["uploaded_path"] : null;
            } else if (!empty($this->input->post("revenue_patta_copy_temp"))) {
                $revenuePattaCopy = movedigilockerfile($this->input->post('revenue_patta_copy_temp'));
                $revenue_patta_copy = $revenuePattaCopy["upload_status"] ? $revenuePattaCopy["uploaded_path"] : null;
            }


            if ($_FILES['copy_of_chitha']['name'] != "") {
                $copyOfChitha = cifileupload("copy_of_chitha");
                $copy_of_chitha = $copyOfChitha["upload_status"] ? $copyOfChitha["uploaded_path"] : null;
            } else if (!empty($this->input->post("copy_of_chitha_temp"))) {
                $copyOfChitha = movedigilockerfile($this->input->post('copy_of_chitha_temp'));
                $copy_of_chitha = $copyOfChitha["upload_status"] ? $copyOfChitha["uploaded_path"] : null;
            }
        }

        $settlement_land_patta_copy = "";
        $land_revenue_receipt = "";
        if ($dbrow->form_data->service_id == "CCM" || $dbrow->form_data->service_id == "ITMKA") {
            if ($_FILES['settlement_land_patta_copy']['name'] != "") {
                $settlementLandPattaCopy = cifileupload("settlement_land_patta_copy");
                $settlement_land_patta_copy = $settlementLandPattaCopy["upload_status"] ? $settlementLandPattaCopy["uploaded_path"] : null;
            } else if (!empty($this->input->post("settlement_land_patta_copy_temp"))) {
                $settlementLandPattaCopy = movedigilockerfile($this->input->post('settlement_land_patta_copy_temp'));
                $settlement_land_patta_copy = $settlementLandPattaCopy["upload_status"] ? $settlementLandPattaCopy["uploaded_path"] : null;
            }


            if ($_FILES['land_revenue_receipt']['name'] != "") {
                $landRevenueReceipt = cifileupload("land_revenue_receipt");
                $land_revenue_receipt = $landRevenueReceipt["upload_status"] ? $landRevenueReceipt["uploaded_path"] : null;
            } else if (!empty($this->input->post("land_revenue_receipt_temp"))) {
                $landRevenueReceipt = movedigilockerfile($this->input->post('land_revenue_receipt_copy_temp'));
                $land_revenue_receipt = $landRevenueReceipt["upload_status"] ? $landRevenueReceipt["uploaded_path"] : null;
            }
        }


        $photocopy_of_existing_land_documents = "";
        $police_verification_report = "";
        $no_dues_certificate_from_bank = "";
        if ($dbrow->form_data->service_id == "DLP") {
            if ($_FILES['photocopy_of_existing_land_documents']['name'] != "") {
                $photocopyOfExistingLandDocuments = cifileupload("photocopy_of_existing_land_documents");
                $photocopy_of_existing_land_documents = $photocopyOfExistingLandDocuments["upload_status"] ? $photocopyOfExistingLandDocuments["uploaded_path"] : null;
            } else if (!empty($this->input->post("photocopy_of_existing_land_documents_temp"))) {
                $photocopyOfExistingLandDocuments = movedigilockerfile($this->input->post('photocopy_of_existing_land_documents_copy_temp'));
                $photocopy_of_existing_land_documents = $photocopyOfExistingLandDocuments["upload_status"] ? $photocopyOfExistingLandDocuments["uploaded_path"] : null;
            }


            if ($_FILES['police_verification_report']['name'] != "") {
                $policeVerificationReport = cifileupload("police_verification_report");
                $police_verification_report = $policeVerificationReport["upload_status"] ? $policeVerificationReport["uploaded_path"] : null;
            } else if (!empty($this->input->post("police_verification_report_temp"))) {
                $policeVerificationReport = movedigilockerfile($this->input->post('police_verification_report_copy_temp'));
                $police_verification_report = $policeVerificationReport["upload_status"] ? $policeVerificationReport["uploaded_path"] : null;
            }


            if ($_FILES['no_dues_certificate_from_bank']['name'] != "") {
                $noDuesCertificateFromBank = cifileupload("no_dues_certificate_from_bank");
                $no_dues_certificate_from_bank = $noDuesCertificateFromBank["upload_status"] ? $noDuesCertificateFromBank["uploaded_path"] : null;
            } else if (!empty($this->input->post("no_dues_certificate_from_bank_temp"))) {
                $noDuesCertificateFromBank = movedigilockerfile($this->input->post('no_dues_certificate_from_bank_copy_temp'));
                $no_dues_certificate_from_bank = $noDuesCertificateFromBank["upload_status"] ? $noDuesCertificateFromBank["uploaded_path"] : null;
            }
        }

        $last_time_paid_Land_revenue_receipt = "";
        if ($dbrow->form_data->service_id == "LRCC") {
            if ($_FILES['last_time_paid_Land_revenue_receipt']['name'] != "") {
                $lastTimePaidLandRevenueReceipt = cifileupload("last_time_paid_Land_revenue_receipt");
                $last_time_paid_Land_revenue_receipt = $lastTimePaidLandRevenueReceipt["upload_status"] ? $lastTimePaidLandRevenueReceipt["uploaded_path"] : null;
            } else if (!empty($this->input->post("last_time_paid_Land_revenue_receipt_temp"))) {
                $lastTimePaidLandRevenueReceipt = movedigilockerfile($this->input->post('last_time_paid_Land_revenue_receipt_temp'));
                $last_time_paid_Land_revenue_receipt = $lastTimePaidLandRevenueReceipt["upload_status"] ? $lastTimePaidLandRevenueReceipt["uploaded_path"] : null;
            }
        }

        // pre($address_proof);

        $uploadedFiles = array(
            "address_proof_old" => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
            "identity_proof_old" => strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old"),
            "land_patta_copy_old" => strlen($land_patta_copy) ? $land_patta_copy : $this->input->post("land_patta_copy_old"),
            "updated_land_revenue_receipt_old" => strlen($updated_land_revenue_receipt) ? $updated_land_revenue_receipt : $this->input->post("updated_land_revenue_receipt_old"),
            "Up_to_date_original_land_documents_old" => strlen($Up_to_date_original_land_documents) ? $Up_to_date_original_land_documents : $this->input->post("Up_to_date_original_land_documents_old"),
            "up_to_date_khajna_receipt_old" => strlen($up_to_date_khajna_receipt) ? $up_to_date_khajna_receipt : $this->input->post("up_to_date_khajna_receipt_old"),
            "copy_of_jamabandi_old" => strlen($copy_of_jamabandi) ? $copy_of_jamabandi : $this->input->post("copy_of_jamabandi_old"),


            "revenue_patta_copy_old" => strlen($revenue_patta_copy) ? $revenue_patta_copy : $this->input->post("revenue_patta_copy_old"),
            "copy_of_chitha_old" => strlen($copy_of_chitha) ? $copy_of_chitha : $this->input->post("copy_of_chitha_old"),
            "settlement_land_patta_copy_old" => strlen($settlement_land_patta_copy) ? $settlement_land_patta_copy : $this->input->post("settlement_land_patta_copy_old"),


            "land_revenue_receipt_old" => strlen($land_revenue_receipt) ? $land_revenue_receipt : $this->input->post("land_revenue_receipt_old"),
            "photocopy_of_existing_land_documents_old" => strlen($photocopy_of_existing_land_documents) ? $photocopy_of_existing_land_documents : $this->input->post("photocopy_of_existing_land_documents_old"),
            "police_verification_report_old" => strlen($police_verification_report) ? $police_verification_report : $this->input->post("police_verification_report_old"),


            "no_dues_certificate_from_bank_old" => strlen($no_dues_certificate_from_bank) ? $no_dues_certificate_from_bank : $this->input->post("no_dues_certificate_from_bank_old"),
            "last_time_paid_Land_revenue_receipt_old" => strlen($last_time_paid_Land_revenue_receipt) ? $last_time_paid_Land_revenue_receipt : $this->input->post("last_time_paid_Land_revenue_receipt_old"),
        );
        // pre($uploadedFiles);
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {


            if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "CCM" || $dbrow->form_data->service_id == "ITMKA" || $dbrow->form_data->service_id == "LHOLD") {
                if (!empty($this->input->post("address_proof_type"))) {
                    $data["form_data.address_proof_type"] = $this->input->post("address_proof_type");
                    $data["form_data.address_proof"] = strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old");
                }
                if (!empty($this->input->post("identity_proof_type"))) {
                    $data["form_data.identity_proof_type"] = $this->input->post("identity_proof_type");
                    $data["form_data.identity_proof"] = strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old");
                }
            }

            if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LRCC" || $dbrow->form_data->service_id == "ITMKA" || $dbrow->form_data->service_id == "LHOLD") {
                if (!empty($this->input->post("land_patta_copy_type"))) {
                    $data["form_data.land_patta_copy_type"] = $this->input->post("land_patta_copy_type");
                    $data["form_data.land_patta_copy"] = strlen($land_patta_copy) ? $land_patta_copy : $this->input->post("land_patta_copy_old");
                }
            }

            if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LHOLD") {
                if (!empty($this->input->post("updated_land_revenue_receipt_type"))) {
                    $data["form_data.updated_land_revenue_receipt_type"] = $this->input->post("updated_land_revenue_receipt_type");
                    $data["form_data.updated_land_revenue_receipt"] = strlen($updated_land_revenue_receipt) ? $updated_land_revenue_receipt : $this->input->post("updated_land_revenue_receipt_old");
                }
            }
            if ($dbrow->form_data->service_id == "CCJ" || $dbrow->form_data->service_id == "LVC" || $dbrow->form_data->service_id == "NECKA") {
                if (!empty($this->input->post("Up_to_date_original_land_documents_type"))) {
                    $data["form_data.Up_to_date_original_land_documents_type"] = $this->input->post("Up_to_date_original_land_documents_type");
                    $data["form_data.Up_to_date_original_land_documents"] = strlen($Up_to_date_original_land_documents) ? $Up_to_date_original_land_documents : $this->input->post("Up_to_date_original_land_documents_old");
                }
            }
            if ($dbrow->form_data->service_id == "CCJ" || $dbrow->form_data->service_id == "DLP" || $dbrow->form_data->service_id == "NECKA" || $dbrow->form_data->service_id == "LVC") {
                if (!empty($this->input->post("up_to_date_khajna_receipt_type"))) {
                    $data["form_data.up_to_date_khajna_receipt_type"] = $this->input->post("up_to_date_khajna_receipt_type");
                    $data["form_data.up_to_date_khajna_receipt"] = strlen($up_to_date_khajna_receipt) ? $up_to_date_khajna_receipt : $this->input->post("up_to_date_khajna_receipt_old");
                }
            }
            if ($dbrow->form_data->service_id == "CCM") {
                if (!empty($this->input->post("copy_of_jamabandi_type"))) {
                    $data["form_data.copy_of_jamabandi_type"] = $this->input->post("copy_of_jamabandi_type");
                    $data["form_data.copy_of_jamabandi"] = strlen($copy_of_jamabandi) ? $copy_of_jamabandi : $this->input->post("copy_of_jamabandi_old");
                }
                if (!empty($this->input->post("revenue_patta_copy_type"))) {
                    $data["form_data.revenue_patta_copy_type"] = $this->input->post("revenue_patta_copy_type");
                    $data["form_data.revenue_patta_copy"] = strlen($revenue_patta_copy) ? $revenue_patta_copy : $this->input->post("revenue_patta_copy_old");
                }
                if (!empty($this->input->post("copy_of_chitha_type"))) {
                    $data["form_data.copy_of_chitha_type"] = $this->input->post("copy_of_chitha_type");
                    $data["form_data.copy_of_chitha"] = strlen($copy_of_chitha) ? $copy_of_chitha : $this->input->post("copy_of_chitha_old");
                }
            }
            if ($dbrow->form_data->service_id == "CCM" || $dbrow->form_data->service_id == "ITMKA") {
                if (!empty($this->input->post("settlement_land_patta_copy_type"))) {
                    $data["form_data.settlement_land_patta_copy_type"] = $this->input->post("settlement_land_patta_copy_type");
                    $data["form_data.settlement_land_patta_copy"] = strlen($settlement_land_patta_copy) ? $settlement_land_patta_copy : $this->input->post("settlement_land_patta_copy_old");
                }

                if (!empty($this->input->post("land_revenue_receipt_type"))) {
                    $data["form_data.land_revenue_receipt_type"] = $this->input->post("land_revenue_receipt_type");
                    $data["form_data.land_revenue_receipt"] = strlen($land_revenue_receipt) ? $land_revenue_receipt : $this->input->post("land_revenue_receipt_old");
                }
            }
            if ($dbrow->form_data->service_id == "DLP") {
                if (!empty($this->input->post("photocopy_of_existing_land_documents_type"))) {
                    $data["form_data.photocopy_of_existing_land_documents_type"] = $this->input->post("photocopy_of_existing_land_documents_type");
                    $data["form_data.photocopy_of_existing_land_documents"] = strlen($photocopy_of_existing_land_documents) ? $photocopy_of_existing_land_documents : $this->input->post("photocopy_of_existing_land_documents_old");
                }
                if (!empty($this->input->post("police_verification_report_type"))) {
                    $data["form_data.police_verification_report_type"] = $this->input->post("police_verification_report_type");
                    $data["form_data.police_verification_report"] = strlen($police_verification_report) ? $police_verification_report : $this->input->post("police_verification_report_old");
                }
                if (!empty($this->input->post("no_dues_certificate_from_bank_type"))) {
                    $data["form_data.no_dues_certificate_from_bank_type"] = $this->input->post("no_dues_certificate_from_bank_type");
                    $data["form_data.no_dues_certificate_from_bank"] = strlen($no_dues_certificate_from_bank) ? $no_dues_certificate_from_bank : $this->input->post("no_dues_certificate_from_bank_old");
                }
            }
            if ($dbrow->form_data->service_id == "LRCC") {
                if (!empty($this->input->post("last_time_paid_Land_revenue_receipt_type"))) {
                    $data["form_data.last_time_paid_Land_revenue_receipt_type"] = $this->input->post("last_time_paid_Land_revenue_receipt_type");
                    $data["form_data.last_time_paid_Land_revenue_receipt"] = strlen($last_time_paid_Land_revenue_receipt) ? $last_time_paid_Land_revenue_receipt : $this->input->post("last_time_paid_Land_revenue_receipt_old");
                }
            }



            $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $data);

            //....................
            //$this->session->set_flashdata('success', 'Your application has been successfully submitted');

            // pre($data);
            // exit();
            redirect('spservices/kaac/registration/preview/' . $objId);
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
            $this->load->view('kaac/first_group/kaac_preview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaac/registration/');
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
        $dbRow = $this->kaac_registration_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                "service_data.service_name" => $dbRow->form_data->service_id,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('kaac/first_group/kaac_track_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaac/registration/');
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
                // $data["sessions"] = $this->fetchsessions(15);
                $this->load->view('includes/frontend/header');
                $this->load->view('kaac/first_group/kaac_query_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/kaac/registration/queryform');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/kaac/registration/queryform');
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
            if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "CCM" || $dbrow->form_data->service_id == "ITMKA" || $dbrow->form_data->service_id == "LHOLD") {
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
            }

            if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LRCC" || $dbrow->form_data->service_id == "ITMKA" || $dbrow->form_data->service_id == "LHOLD") {
                $this->form_validation->set_rules('land_patta_copy_type', 'Land Patta Copy type', 'required');
                if (empty($this->input->post("land_patta_copy_old"))) {
                    if ((empty($this->input->post("land_patta_copy_temp"))) && (($_FILES['land_patta_copy']['name'] == ""))) {
                        $this->form_validation->set_rules('land_patta_copy', 'Land Patta Copy document', 'required');
                    }

                    if ((!empty($this->input->post("land_patta_copy_type"))) && (($_FILES['land_patta_copy']['name'] == "") && (empty($this->input->post("land_patta_copy_temp"))))) {
                        $this->form_validation->set_rules('land_patta_copy', 'Land Patta Copy document', 'required');
                    }
                }
            }

            if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LHOLD") {
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

            if ($dbrow->form_data->service_id == "CCJ" || $dbrow->form_data->service_id == "LVC" || $dbrow->form_data->service_id == "NECKA") {
                $this->form_validation->set_rules('Up_to_date_original_land_documents_type', 'Marksheet type', 'required');
                if (empty($this->input->post("Up_to_date_original_land_documents_old"))) {
                    if ((empty($this->input->post("Up_to_date_original_land_documents_type"))) && (($_FILES['Up_to_date_original_land_documents']['name'] != "") || (!empty($this->input->post("Up_to_date_original_land_documents_temp"))))) {
                        $this->form_validation->set_rules('Up_to_date_original_land_documents_type', 'Updated Land revenue receipt type', 'required');
                    }

                    if ((!empty($this->input->post("Up_to_date_original_land_documents_type"))) && (($_FILES['Up_to_date_original_land_documents']['name'] == "") && (empty($this->input->post("Up_to_date_original_land_documents_temp"))))) {
                        $this->form_validation->set_rules('Up_to_date_original_land_documents', 'Updated Land revenue receipt', 'required');
                    }
                }
            }


            if ($dbrow->form_data->service_id == "CCJ" || $dbrow->form_data->service_id == "DLP" || $dbrow->form_data->service_id == "NECKA" || $dbrow->form_data->service_id == "LVC") {
                $this->form_validation->set_rules('up_to_date_khajna_receipt_type', 'Up To Date Khajna Receipt type', 'required');
                if (empty($this->input->post("up_to_date_khajna_receipt_old"))) {
                    if ((empty($this->input->post("up_to_date_khajna_receipt_type"))) && (($_FILES['up_to_date_khajna_receipt']['name'] != "") || (!empty($this->input->post("up_to_date_khajna_receipt_temp"))))) {
                        $this->form_validation->set_rules('up_to_date_khajna_receipt_type', 'Updated Land revenue receipt type', 'required');
                    }

                    if ((!empty($this->input->post("up_to_date_khajna_receipt_type"))) && (($_FILES['up_to_date_khajna_receipt']['name'] == "") && (empty($this->input->post("up_to_date_khajna_receipt_temp"))))) {
                        $this->form_validation->set_rules('up_to_date_khajna_receipt', 'Updated Land revenue receipt', 'required');
                    }
                }
            }


            if ($dbrow->form_data->service_id == "CCM") {
                $this->form_validation->set_rules('copy_of_jamabandi_type', 'Copy of Jamabandi type', 'required');
                if (empty($this->input->post("copy_of_jamabandi_old"))) {
                    if ((empty($this->input->post("copy_of_jamabandi_type"))) && (($_FILES['copy_of_jamabandi']['name'] != "") || (!empty($this->input->post("copy_of_jamabandi_temp"))))) {
                        $this->form_validation->set_rules('copy_of_jamabandi_type', 'Copy of Jamabandi type', 'required');
                    }

                    if ((!empty($this->input->post("copy_of_jamabandi_type"))) && (($_FILES['copy_of_jamabandi']['name'] == "") && (empty($this->input->post("copy_of_jamabandi_temp"))))) {
                        $this->form_validation->set_rules('copy_of_jamabandi', 'Copy of Jamabandi', 'required');
                    }
                }


                $this->form_validation->set_rules('revenue_patta_copy_type', 'Revenue Patta Copy type', 'required');
                if (empty($this->input->post("revenue_patta_copy_old"))) {
                    if ((empty($this->input->post("revenue_patta_copy_type"))) && (($_FILES['revenue_patta_copy']['name'] != "") || (!empty($this->input->post("revenue_patta_copy_temp"))))) {
                        $this->form_validation->set_rules('revenue_patta_copy_type', 'Revenue Patta Copy type', 'required');
                    }

                    if ((!empty($this->input->post("revenue_patta_copy_type"))) && (($_FILES['revenue_patta_copy']['name'] == "") && (empty($this->input->post("revenue_patta_copy_temp"))))) {
                        $this->form_validation->set_rules('revenue_patta_copy', 'Revenue Patta Copy', 'required');
                    }
                }

                $this->form_validation->set_rules('copy_of_chitha_type', 'Copy of Chitha type', 'required');
                if (empty($this->input->post("copy_of_chitha_old"))) {
                    if ((empty($this->input->post("copy_of_chitha_type"))) && (($_FILES['copy_of_chitha']['name'] != "") || (!empty($this->input->post("copy_of_chitha_temp"))))) {
                        $this->form_validation->set_rules('copy_of_chitha_type', 'Copy of Chitha type', 'required');
                    }

                    if ((!empty($this->input->post("copy_of_chitha_type"))) && (($_FILES['copy_of_chitha']['name'] == "") && (empty($this->input->post("copy_of_chitha_temp"))))) {
                        $this->form_validation->set_rules('copy_of_chitha', 'Copy of Chitha receipt', 'required');
                    }
                }
            }

            if ($dbrow->form_data->service_id == "CCM" || $dbrow->form_data->service_id == "ITMKA") {
                $this->form_validation->set_rules('settlement_land_patta_copy_type', 'Setteltment Land Patta Copy type', 'required');
                if (empty($this->input->post("settlement_land_patta_copy_old"))) {
                    if ((empty($this->input->post("settlement_land_patta_copy_type"))) && (($_FILES['settlement_land_patta_copy']['name'] != "") || (!empty($this->input->post("settlement_land_patta_copy_temp"))))) {
                        $this->form_validation->set_rules('settlement_land_patta_copy_type', 'Setteltment Land Patta Copy type', 'required');
                    }

                    if ((!empty($this->input->post("settlement_land_patta_copy_type"))) && (($_FILES['settlement_land_patta_copy']['name'] == "") && (empty($this->input->post("settlement_land_patta_copy_temp"))))) {
                        $this->form_validation->set_rules('settlement_land_patta_copy', 'Setteltment Land Patta Copy', 'required');
                    }
                }
                $this->form_validation->set_rules('land_revenue_receipt_type', 'Land Revenue Reciept type', 'required');
                if (empty($this->input->post("land_revenue_receipt_old"))) {
                    if ((empty($this->input->post("land_revenue_receipt_type"))) && (($_FILES['land_revenue_receipt']['name'] != "") || (!empty($this->input->post("land_revenue_receipt_temp"))))) {
                        $this->form_validation->set_rules('land_revenue_receipt_type', 'Land Revenue Reciept type', 'required');
                    }

                    if ((!empty($this->input->post("land_revenue_receipt_type"))) && (($_FILES['land_revenue_receipt']['name'] == "") && (empty($this->input->post("land_revenue_receipt_temp"))))) {
                        $this->form_validation->set_rules('land_revenue_receipt', 'Land Revenue Reciept', 'required');
                    }
                }
            }

            if ($dbrow->form_data->service_id == "DLP") {
                $this->form_validation->set_rules('police_verification_report_type', 'Police Verification Report type', 'required');
                if (empty($this->input->post("police_verification_report_old"))) {
                    if ((empty($this->input->post("police_verification_report_type"))) && (($_FILES['police_verification_report']['name'] != "") || (!empty($this->input->post("police_verification_report_temp"))))) {
                        $this->form_validation->set_rules('police_verification_report_type', 'Police Verification Report type', 'required');
                    }

                    if ((!empty($this->input->post("police_verification_report_type"))) && (($_FILES['police_verification_report']['name'] == "") && (empty($this->input->post("police_verification_report_temp"))))) {
                        $this->form_validation->set_rules('police_verification_report', 'Police Verification Report', 'required');
                    }
                }
                $this->form_validation->set_rules('photocopy_of_existing_land_documents_type', 'Photocopy of Existing Land Documents type', 'required');
                if (empty($this->input->post("photocopy_of_existing_land_documents_old"))) {
                    if ((empty($this->input->post("photocopy_of_existing_land_documents_type"))) && (($_FILES['photocopy_of_existing_land_documents']['name'] != "") || (!empty($this->input->post("photocopy_of_existing_land_documents_temp"))))) {
                        $this->form_validation->set_rules('photocopy_of_existing_land_documents_type', 'Photocopy of Existing Land Documents type', 'required');
                    }

                    if ((!empty($this->input->post("photocopy_of_existing_land_documents_type"))) && (($_FILES['photocopy_of_existing_land_documents']['name'] == "") && (empty($this->input->post("photocopy_of_existing_land_documents_temp"))))) {
                        $this->form_validation->set_rules('photocopy_of_existing_land_documents', 'Photocopy of Existing Land Documents', 'required');
                    }
                }
                $this->form_validation->set_rules('no_dues_certificate_from_bank_type', 'No Dues Certificate_from Bank type', 'required');
                if (empty($this->input->post("no_dues_certificate_from_bank_old"))) {
                    if ((empty($this->input->post("no_dues_certificate_from_bank_type"))) && (($_FILES['no_dues_certificate_from_bank']['name'] != "") || (!empty($this->input->post("no_dues_certificate_from_bank_temp"))))) {
                        $this->form_validation->set_rules('no_dues_certificate_from_bank_type', 'No Dues Certificate_from Bank type', 'required');
                    }

                    if ((!empty($this->input->post("no_dues_certificate_from_bank_type"))) && (($_FILES['no_dues_certificate_from_bank']['name'] == "") && (empty($this->input->post("no_dues_certificate_from_bank_temp"))))) {
                        $this->form_validation->set_rules('no_dues_certificate_from_bank', 'No Dues Certificate_from Bank', 'required');
                    }
                }
            }

            if ($dbrow->form_data->service_id == "LRCC") {
                $this->form_validation->set_rules('last_time_paid_Land_revenue_receipt_type', 'Last Time Paid Land Revenue Receipt type', 'required');
                if (empty($this->input->post("last_time_paid_Land_revenue_receipt_old"))) {
                    if ((empty($this->input->post("last_time_paid_Land_revenue_receipt_type"))) && (($_FILES['last_time_paid_Land_revenue_receipt']['name'] != "") || (!empty($this->input->post("last_time_paid_Land_revenue_receipt_temp"))))) {
                        $this->form_validation->set_rules('last_time_paid_Land_revenue_receipt_type', 'Last Time Paid Land Revenue Receipt type', 'required');
                    }

                    if ((!empty($this->input->post("last_time_paid_Land_revenue_receipt_type"))) && (($_FILES['last_time_paid_Land_revenue_receipt']['name'] == "") && (empty($this->input->post("last_time_paid_Land_revenue_receipt_temp"))))) {
                        $this->form_validation->set_rules('last_time_paid_Land_revenue_receipt', 'Last Time Paid Land Revenue Receipt', 'required');
                    }
                }
            }

            // if (empty($this->input->post("soft_copy_old")) && $this->slug !== 'user') {
            //     if ((empty($this->input->post("soft_copy_type"))) || (($_FILES['soft_copy']['name'] == ""))) {

            //         $this->form_validation->set_rules('soft_copy_type', 'Soft Copy Type required', 'required');
            //         $this->form_validation->set_rules('soft_copy', 'Soft Copy document required', 'required');
            //     }
            // }

            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

            $address_proof = "";
            $identity_proof = "";
            if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "CCM" || $dbrow->form_data->service_id == "ITMKA" || $dbrow->form_data->service_id == "LHOLD") {

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
            }

            $land_patta_copy = "";
            if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LRCC" || $dbrow->form_data->service_id == "ITMKA" || $dbrow->form_data->service_id == "LHOLD") {
                if ($_FILES['land_patta_copy']['name'] != "") {
                    $landPattaCopy = cifileupload("land_patta_copy");
                    $land_patta_copy = $landPattaCopy["upload_status"] ? $landPattaCopy["uploaded_path"] : null;
                } else if (!empty($this->input->post("land_patta_copy_temp"))) {
                    $landPattaCopy = movedigilockerfile($this->input->post('land_patta_copy_temp'));
                    $land_patta_copy = $landPattaCopy["upload_status"] ? $landPattaCopy["uploaded_path"] : null;
                }
            }

            $updated_land_revenue_receipt = "";
            if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LHOLD") {

                if ($_FILES['updated_land_revenue_receipt']['name'] != "") {
                    // pre("jhhj");
                    $updatedLandRevenueReceipt = cifileupload("updated_land_revenue_receipt");
                    $updated_land_revenue_receipt = $updatedLandRevenueReceipt["upload_status"] ? $updatedLandRevenueReceipt["uploaded_path"] : null;
                } else if (!empty($this->input->post("updated_land_revenue_receipt_temp"))) {
                    $updatedLandRevenueReceipt = movedigilockerfile($this->input->post('updated_land_revenue_receipt_temp'));
                    $updated_land_revenue_receipt = $updatedLandRevenueReceipt["upload_status"] ? $updatedLandRevenueReceipt["uploaded_path"] : null;
                }
            }

            $Up_to_date_original_land_documents = "";
            if ($dbrow->form_data->service_id == "CCJ" || $dbrow->form_data->service_id == "LVC" || $dbrow->form_data->service_id == "NECKA") {

                if ($_FILES['Up_to_date_original_land_documents']['name'] != "") {
                    $UpToDateOriginalLandDocuments = cifileupload("Up_to_date_original_land_documents");
                    $Up_to_date_original_land_documents = $UpToDateOriginalLandDocuments["upload_status"] ? $UpToDateOriginalLandDocuments["uploaded_path"] : null;
                } else if (!empty($this->input->post("Up_to_date_original_land_documents_temp"))) {
                    $UpToDateOriginalLandDocuments = movedigilockerfile($this->input->post('Up_to_date_original_land_documents_temp'));
                    $Up_to_date_original_land_documents = $UpToDateOriginalLandDocuments["upload_status"] ? $UpToDateOriginalLandDocuments["uploaded_path"] : null;
                }
            }

            $up_to_date_khajna_receipt = "";
            if ($dbrow->form_data->service_id == "CCJ" || $dbrow->form_data->service_id == "DLP" || $dbrow->form_data->service_id == "NECKA" || $dbrow->form_data->service_id == "LVC") {
                if ($_FILES['up_to_date_khajna_receipt']['name'] != "") {
                    $upToDateKhajnaReceipt = cifileupload("up_to_date_khajna_receipt");
                    $up_to_date_khajna_receipt = $upToDateKhajnaReceipt["upload_status"] ? $upToDateKhajnaReceipt["uploaded_path"] : null;
                } else if (!empty($this->input->post("up_to_date_khajna_receipt_temp"))) {
                    $upToDateKhajnaReceipt = movedigilockerfile($this->input->post('up_to_date_khajna_receipt_temp'));
                    $up_to_date_khajna_receipt = $upToDateKhajnaReceipt["upload_status"] ? $upToDateKhajnaReceipt["uploaded_path"] : null;
                }
            }


            $copy_of_jamabandi = "";
            $revenue_patta_copy = "";
            $copy_of_chitha = "";
            if ($dbrow->form_data->service_id == "CCM") {
                if ($_FILES['copy_of_jamabandi']['name'] != "") {
                    $copyOfJamabandi = cifileupload("copy_of_jamabandi");
                    $copy_of_jamabandi = $copyOfJamabandi["upload_status"] ? $copyOfJamabandi["uploaded_path"] : null;
                } else if (!empty($this->input->post("copy_of_jamabandi_temp"))) {
                    $copyOfJamabandi = movedigilockerfile($this->input->post('copy_of_jamabandi_temp'));
                    $copy_of_jamabandi = $copyOfJamabandi["upload_status"] ? $copyOfJamabandi["uploaded_path"] : null;
                }

                // pre($copy_of_jamabandi);
                if ($_FILES['revenue_patta_copy']['name'] != "") {
                    $revenuePattaCopy = cifileupload("revenue_patta_copy");
                    $revenue_patta_copy = $revenuePattaCopy["upload_status"] ? $revenuePattaCopy["uploaded_path"] : null;
                } else if (!empty($this->input->post("revenue_patta_copy_temp"))) {
                    $revenuePattaCopy = movedigilockerfile($this->input->post('revenue_patta_copy_temp'));
                    $revenue_patta_copy = $revenuePattaCopy["upload_status"] ? $revenuePattaCopy["uploaded_path"] : null;
                }


                if ($_FILES['copy_of_chitha']['name'] != "") {
                    $copyOfChitha = cifileupload("copy_of_chitha");
                    $copy_of_chitha = $copyOfChitha["upload_status"] ? $copyOfChitha["uploaded_path"] : null;
                } else if (!empty($this->input->post("copy_of_chitha_temp"))) {
                    $copyOfChitha = movedigilockerfile($this->input->post('copy_of_chitha_temp'));
                    $copy_of_chitha = $copyOfChitha["upload_status"] ? $copyOfChitha["uploaded_path"] : null;
                }
            }

            $settlement_land_patta_copy = "";
            $land_revenue_receipt = "";
            if ($dbrow->form_data->service_id == "CCM" || $dbrow->form_data->service_id == "ITMKA") {
                if ($_FILES['settlement_land_patta_copy']['name'] != "") {
                    $settlementLandPattaCopy = cifileupload("settlement_land_patta_copy");
                    $settlement_land_patta_copy = $settlementLandPattaCopy["upload_status"] ? $settlementLandPattaCopy["uploaded_path"] : null;
                } else if (!empty($this->input->post("settlement_land_patta_copy_temp"))) {
                    $settlementLandPattaCopy = movedigilockerfile($this->input->post('settlement_land_patta_copy_temp'));
                    $settlement_land_patta_copy = $settlementLandPattaCopy["upload_status"] ? $settlementLandPattaCopy["uploaded_path"] : null;
                }


                if ($_FILES['land_revenue_receipt']['name'] != "") {
                    $landRevenueReceipt = cifileupload("land_revenue_receipt");
                    $land_revenue_receipt = $landRevenueReceipt["upload_status"] ? $landRevenueReceipt["uploaded_path"] : null;
                } else if (!empty($this->input->post("land_revenue_receipt_temp"))) {
                    $landRevenueReceipt = movedigilockerfile($this->input->post('land_revenue_receipt_copy_temp'));
                    $land_revenue_receipt = $landRevenueReceipt["upload_status"] ? $landRevenueReceipt["uploaded_path"] : null;
                }
            }


            $photocopy_of_existing_land_documents = "";
            $police_verification_report = "";
            $no_dues_certificate_from_bank = "";
            if ($dbrow->form_data->service_id == "DLP") {
                if ($_FILES['photocopy_of_existing_land_documents']['name'] != "") {
                    $photocopyOfExistingLandDocuments = cifileupload("photocopy_of_existing_land_documents");
                    $photocopy_of_existing_land_documents = $photocopyOfExistingLandDocuments["upload_status"] ? $photocopyOfExistingLandDocuments["uploaded_path"] : null;
                } else if (!empty($this->input->post("photocopy_of_existing_land_documents_temp"))) {
                    $photocopyOfExistingLandDocuments = movedigilockerfile($this->input->post('photocopy_of_existing_land_documents_copy_temp'));
                    $photocopy_of_existing_land_documents = $photocopyOfExistingLandDocuments["upload_status"] ? $photocopyOfExistingLandDocuments["uploaded_path"] : null;
                }


                if ($_FILES['police_verification_report']['name'] != "") {
                    $policeVerificationReport = cifileupload("police_verification_report");
                    $police_verification_report = $policeVerificationReport["upload_status"] ? $policeVerificationReport["uploaded_path"] : null;
                } else if (!empty($this->input->post("police_verification_report_temp"))) {
                    $policeVerificationReport = movedigilockerfile($this->input->post('police_verification_report_copy_temp'));
                    $police_verification_report = $policeVerificationReport["upload_status"] ? $policeVerificationReport["uploaded_path"] : null;
                }


                if ($_FILES['no_dues_certificate_from_bank']['name'] != "") {
                    $noDuesCertificateFromBank = cifileupload("no_dues_certificate_from_bank");
                    $no_dues_certificate_from_bank = $noDuesCertificateFromBank["upload_status"] ? $noDuesCertificateFromBank["uploaded_path"] : null;
                } else if (!empty($this->input->post("no_dues_certificate_from_bank_temp"))) {
                    $noDuesCertificateFromBank = movedigilockerfile($this->input->post('no_dues_certificate_from_bank_copy_temp'));
                    $no_dues_certificate_from_bank = $noDuesCertificateFromBank["upload_status"] ? $noDuesCertificateFromBank["uploaded_path"] : null;
                }
            }

            $last_time_paid_Land_revenue_receipt = "";
            if ($dbrow->form_data->service_id == "LRCC") {
                if ($_FILES['last_time_paid_Land_revenue_receipt']['name'] != "") {
                    $lastTimePaidLandRevenueReceipt = cifileupload("last_time_paid_Land_revenue_receipt");
                    $last_time_paid_Land_revenue_receipt = $lastTimePaidLandRevenueReceipt["upload_status"] ? $lastTimePaidLandRevenueReceipt["uploaded_path"] : null;
                } else if (!empty($this->input->post("last_time_paid_Land_revenue_receipt_temp"))) {
                    $lastTimePaidLandRevenueReceipt = movedigilockerfile($this->input->post('last_time_paid_Land_revenue_receipt_temp'));
                    $last_time_paid_Land_revenue_receipt = $lastTimePaidLandRevenueReceipt["upload_status"] ? $lastTimePaidLandRevenueReceipt["uploaded_path"] : null;
                }
            }

            // pre($address_proof);

            $uploadedFiles = array(
                "address_proof_old" => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
                "identity_proof_old" => strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old"),
                "land_patta_copy_old" => strlen($land_patta_copy) ? $land_patta_copy : $this->input->post("land_patta_copy_old"),
                "updated_land_revenue_receipt_old" => strlen($updated_land_revenue_receipt) ? $updated_land_revenue_receipt : $this->input->post("updated_land_revenue_receipt_old"),
                "Up_to_date_original_land_documents_old" => strlen($Up_to_date_original_land_documents) ? $Up_to_date_original_land_documents : $this->input->post("Up_to_date_original_land_documents_old"),
                "up_to_date_khajna_receipt_old" => strlen($up_to_date_khajna_receipt) ? $up_to_date_khajna_receipt : $this->input->post("up_to_date_khajna_receipt_old"),
                "copy_of_jamabandi_old" => strlen($copy_of_jamabandi) ? $copy_of_jamabandi : $this->input->post("copy_of_jamabandi_old"),


                "revenue_patta_copy_old" => strlen($revenue_patta_copy) ? $revenue_patta_copy : $this->input->post("revenue_patta_copy_old"),
                "copy_of_chitha_old" => strlen($copy_of_chitha) ? $copy_of_chitha : $this->input->post("copy_of_chitha_old"),
                "settlement_land_patta_copy_old" => strlen($settlement_land_patta_copy) ? $settlement_land_patta_copy : $this->input->post("settlement_land_patta_copy_old"),


                "land_revenue_receipt_old" => strlen($land_revenue_receipt) ? $land_revenue_receipt : $this->input->post("land_revenue_receipt_old"),
                "photocopy_of_existing_land_documents_old" => strlen($photocopy_of_existing_land_documents) ? $photocopy_of_existing_land_documents : $this->input->post("photocopy_of_existing_land_documents_old"),
                "police_verification_report_old" => strlen($police_verification_report) ? $police_verification_report : $this->input->post("police_verification_report_old"),


                "no_dues_certificate_from_bank_old" => strlen($no_dues_certificate_from_bank) ? $no_dues_certificate_from_bank : $this->input->post("no_dues_certificate_from_bank_old"),
                "last_time_paid_Land_revenue_receipt_old" => strlen($last_time_paid_Land_revenue_receipt) ? $last_time_paid_Land_revenue_receipt : $this->input->post("last_time_paid_Land_revenue_receipt_old"),
            );
            // pre($uploadedFiles);
            $this->session->set_flashdata('uploaded_files', $uploadedFiles);

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $this->fileuploads($objId);
            } else {


                if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "CCM" || $dbrow->form_data->service_id == "ITMKA" || $dbrow->form_data->service_id == "LHOLD") {
                    if (!empty($this->input->post("address_proof_type"))) {
                        $data["form_data.address_proof_type"] = $this->input->post("address_proof_type");
                        $data["form_data.address_proof"] = strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old");
                    }
                    if (!empty($this->input->post("identity_proof_type"))) {
                        $data["form_data.identity_proof_type"] = $this->input->post("identity_proof_type");
                        $data["form_data.identity_proof"] = strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old");
                    }
                }

                if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LRCC" || $dbrow->form_data->service_id == "ITMKA" || $dbrow->form_data->service_id == "LHOLD") {
                    if (!empty($this->input->post("land_patta_copy_type"))) {
                        $data["form_data.land_patta_copy_type"] = $this->input->post("land_patta_copy_type");
                        $data["form_data.land_patta_copy"] = strlen($land_patta_copy) ? $land_patta_copy : $this->input->post("land_patta_copy_old");
                    }
                }

                if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LHOLD") {
                    if (!empty($this->input->post("updated_land_revenue_receipt_type"))) {
                        $data["form_data.updated_land_revenue_receipt_type"] = $this->input->post("updated_land_revenue_receipt_type");
                        $data["form_data.updated_land_revenue_receipt"] = strlen($updated_land_revenue_receipt) ? $updated_land_revenue_receipt : $this->input->post("updated_land_revenue_receipt_old");
                    }
                }
                if ($dbrow->form_data->service_id == "CCJ" || $dbrow->form_data->service_id == "LVC" || $dbrow->form_data->service_id == "NECKA") {
                    if (!empty($this->input->post("Up_to_date_original_land_documents_type"))) {
                        $data["form_data.Up_to_date_original_land_documents_type"] = $this->input->post("Up_to_date_original_land_documents_type");
                        $data["form_data.Up_to_date_original_land_documents"] = strlen($Up_to_date_original_land_documents) ? $Up_to_date_original_land_documents : $this->input->post("Up_to_date_original_land_documents_old");
                    }
                }
                if ($dbrow->form_data->service_id == "CCJ" || $dbrow->form_data->service_id == "DLP" || $dbrow->form_data->service_id == "NECKA" || $dbrow->form_data->service_id == "LVC") {
                    if (!empty($this->input->post("up_to_date_khajna_receipt_type"))) {
                        $data["form_data.up_to_date_khajna_receipt_type"] = $this->input->post("up_to_date_khajna_receipt_type");
                        $data["form_data.up_to_date_khajna_receipt"] = strlen($up_to_date_khajna_receipt) ? $up_to_date_khajna_receipt : $this->input->post("up_to_date_khajna_receipt_old");
                    }
                }
                if ($dbrow->form_data->service_id == "CCM") {
                    if (!empty($this->input->post("copy_of_jamabandi_type"))) {
                        $data["form_data.copy_of_jamabandi_type"] = $this->input->post("copy_of_jamabandi_type");
                        $data["form_data.copy_of_jamabandi"] = strlen($copy_of_jamabandi) ? $copy_of_jamabandi : $this->input->post("copy_of_jamabandi_old");
                    }
                    if (!empty($this->input->post("revenue_patta_copy_type"))) {
                        $data["form_data.revenue_patta_copy_type"] = $this->input->post("revenue_patta_copy_type");
                        $data["form_data.revenue_patta_copy"] = strlen($revenue_patta_copy) ? $revenue_patta_copy : $this->input->post("revenue_patta_copy_old");
                    }
                    if (!empty($this->input->post("copy_of_chitha_type"))) {
                        $data["form_data.copy_of_chitha_type"] = $this->input->post("copy_of_chitha_type");
                        $data["form_data.copy_of_chitha"] = strlen($copy_of_chitha) ? $copy_of_chitha : $this->input->post("copy_of_chitha_old");
                    }
                }
                if ($dbrow->form_data->service_id == "CCM" || $dbrow->form_data->service_id == "ITMKA") {
                    if (!empty($this->input->post("settlement_land_patta_copy_type"))) {
                        $data["form_data.settlement_land_patta_copy_type"] = $this->input->post("settlement_land_patta_copy_type");
                        $data["form_data.settlement_land_patta_copy"] = strlen($settlement_land_patta_copy) ? $settlement_land_patta_copy : $this->input->post("settlement_land_patta_copy_old");
                    }

                    if (!empty($this->input->post("land_revenue_receipt_type"))) {
                        $data["form_data.land_revenue_receipt_type"] = $this->input->post("land_revenue_receipt_type");
                        $data["form_data.land_revenue_receipt"] = strlen($land_revenue_receipt) ? $land_revenue_receipt : $this->input->post("land_revenue_receipt_old");
                    }
                }
                if ($dbrow->form_data->service_id == "DLP") {
                    if (!empty($this->input->post("photocopy_of_existing_land_documents_type"))) {
                        $data["form_data.photocopy_of_existing_land_documents_type"] = $this->input->post("photocopy_of_existing_land_documents_type");
                        $data["form_data.photocopy_of_existing_land_documents"] = strlen($photocopy_of_existing_land_documents) ? $photocopy_of_existing_land_documents : $this->input->post("photocopy_of_existing_land_documents_old");
                    }
                    if (!empty($this->input->post("police_verification_report_type"))) {
                        $data["form_data.police_verification_report_type"] = $this->input->post("police_verification_report_type");
                        $data["form_data.police_verification_report"] = strlen($police_verification_report) ? $police_verification_report : $this->input->post("police_verification_report_old");
                    }
                    if (!empty($this->input->post("no_dues_certificate_from_bank_type"))) {
                        $data["form_data.no_dues_certificate_from_bank_type"] = $this->input->post("no_dues_certificate_from_bank_type");
                        $data["form_data.no_dues_certificate_from_bank"] = strlen($no_dues_certificate_from_bank) ? $no_dues_certificate_from_bank : $this->input->post("no_dues_certificate_from_bank_old");
                    }
                }
                if ($dbrow->form_data->service_id == "LRCC") {
                    if (!empty($this->input->post("last_time_paid_Land_revenue_receipt_type"))) {
                        $data["form_data.last_time_paid_Land_revenue_receipt_type"] = $this->input->post("last_time_paid_Land_revenue_receipt_type");
                        $data["form_data.last_time_paid_Land_revenue_receipt"] = strlen($last_time_paid_Land_revenue_receipt) ? $last_time_paid_Land_revenue_receipt : $this->input->post("last_time_paid_Land_revenue_receipt_old");
                    }
                }

                $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $data);
                //post data to KAAC

                $dbrow = $this->kaac_registration_model->get_by_doc_id($objId);

                //new code
                $postdata = array();

                if ((($dbrow->form_data->service_id == "ITMKA") || ($dbrow->form_data->service_id == "DCTH") || ($dbrow->form_data->service_id == "CCM")) && (!empty($dbrow->form_data->address_proof))) {

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
                    $postdata['address_proof'] = $attachment_one;
                }
                if ((($dbrow->form_data->service_id == "ITMKA") || ($dbrow->form_data->service_id == "DCTH")) && (!empty($dbrow->form_data->identity_proof))) {

                    $identity_proof = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->identity_proof));

                    $attachment_one = array(
                        "encl" =>  $identity_proof,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->identity_proof_type,
                        "enclType" => $dbrow->form_data->identity_proof_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['identity_proof'] = $attachment_one;
                }
                if (($dbrow->form_data->service_id == "ITMKA") && (!empty($dbrow->form_data->land_patta_copy))) {

                    $land_document = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->land_patta_copy));

                    $attachment_one = array(
                        "encl" =>  $land_document,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->land_patta_copy_type,
                        "enclType" => $dbrow->form_data->land_patta_copy_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['land_document'] = $attachment_one;
                }
                if (($dbrow->form_data->service_id == "LRCC") && (!empty($dbrow->form_data->land_patta_copy))) {

                    $land_document = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->land_patta_copy));

                    $attachment_one = array(
                        "encl" =>  $land_document,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->land_patta_copy_type,
                        "enclType" => $dbrow->form_data->land_patta_copy_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['land_patta_document'] = $attachment_one;
                }
                if ((($dbrow->form_data->service_id == "ITMKA") || ($dbrow->form_data->service_id == "LRCC")) && (!empty($dbrow->form_data->land_revenue_receipt))) {

                    $land_revenue_receipt = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->land_revenue_receipt));

                    $attachment_one = array(
                        "encl" =>  $land_revenue_receipt,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->land_revenue_receipt_type,
                        "enclType" => $dbrow->form_data->land_revenue_receipt_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['land_revenue_receipt'] = $attachment_one;
                }
                // if ((($dbrow->form_data->service_id == "ITMKA") || ($dbrow->form_data->service_id == "LRCC")) && (!empty($dbrow->form_data->settlement_land_patta_copy))) {

                //     $settlement_land_patta_copy = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->settlement_land_patta_copy));

                //     $attachment_one = array(
                //         "encl" =>  $settlement_land_patta_copy,
                //         "docType" => "application/pdf",
                //         "enclFor" => $dbrow->form_data->settlement_land_patta_copy_type,
                //         "enclType" => $dbrow->form_data->settlement_land_patta_copy_type,
                //         "id" => "93963",
                //         "doctypecode" => "6863",
                //         "docRefId" => "8301",
                //         "enclExtn" => "pdf"
                //     );

                //     $postdata['soft_copy'] = $attachment_one;
                // }
                if ((($dbrow->form_data->service_id == "DCTH") || ($dbrow->form_data->service_id == "CCJ") || ($dbrow->form_data->service_id == "DLP") || ($dbrow->form_data->service_id == "LHOLD") || ($dbrow->form_data->service_id == "LVC") || ($dbrow->form_data->service_id == "NECKA"))) {

                    if (($dbrow->form_data->service_id == "DLP")) {

                        $land_patta_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->photocopy_of_existing_land_documents));
                        $enclFor = $dbrow->form_data->photocopy_of_existing_land_documents;
                    } elseif ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LHOLD") {

                        $land_patta_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->land_patta_copy));
                        $enclFor = $dbrow->form_data->land_patta_copy_type;
                    } elseif (($dbrow->form_data->service_id == "CCJ") || ($dbrow->form_data->service_id == "LVC") || ($dbrow->form_data->service_id == "NECKA")) {
                        $land_patta_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->Up_to_date_original_land_documents));
                        $enclFor = $dbrow->form_data->Up_to_date_original_land_documents_type;
                    }
                    // elseif ($dbrow->form_data->service_id == "CCM") {

                    //     $land_patta_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->settlement_land_patta_copy));
                    //     $enclFor = $dbrow->form_data->settlement_land_patta_copy_type;
                    // }


                    $attachment_one = array(
                        "encl" =>  $land_patta_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $enclFor,
                        "enclType" => $enclFor,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );
                    // pre($attachment_one);
                    $postdata['land_patta_doc'] = $attachment_one;
                }

                if (($dbrow->form_data->service_id == "DCTH") || ($dbrow->form_data->service_id == "CCJ") || ($dbrow->form_data->service_id == "LHOLD") || ($dbrow->form_data->service_id == "LVC") || ($dbrow->form_data->service_id == "NECKA")) {


                    if ($dbrow->form_data->service_id == "DCTH" || $dbrow->form_data->service_id == "LHOLD") {

                        $land_receipt_rev_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->updated_land_revenue_receipt));
                        $enclFor = $dbrow->form_data->updated_land_revenue_receipt_type;
                    } else if ($dbrow->form_data->service_id == "CCJ" || $dbrow->form_data->service_id == "NECKA" || $dbrow->form_data->service_id == "LVC") {

                        $land_receipt_rev_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->up_to_date_khajna_receipt));
                        $enclFor = $dbrow->form_data->up_to_date_khajna_receipt_type;
                    }


                    $attachment_one = array(
                        "encl" =>  $land_receipt_rev_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $enclFor,
                        "enclType" => $enclFor,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['land_receipt_rev_doc'] = $attachment_one;
                }

                if ($dbrow->form_data->service_id == "CCM") {

                    $address_proof_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->address_proof));

                    $attachment_one = array(
                        "encl" =>  $address_proof_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->address_proof_type,
                        "enclType" => $dbrow->form_data->address_proof_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );


                    $id_proof_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->identity_proof));

                    $attachment_two = array(
                        "encl" =>  $id_proof_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->identity_proof_type,
                        "enclType" => $dbrow->form_data->identity_proof_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );


                    $rev_receipt_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->land_revenue_receipt));

                    $attachment_three = array(
                        "encl" =>  $rev_receipt_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->land_revenue_receipt_type,
                        "enclType" => $dbrow->form_data->land_revenue_receipt_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $jamabandi = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->copy_of_jamabandi));

                    $attachment_four = array(
                        "encl" =>  $jamabandi,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->copy_of_jamabandi_type,
                        "enclType" => $dbrow->form_data->copy_of_jamabandi_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );
                    $chitha = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->copy_of_chitha));

                    $attachment_five = array(
                        "encl" =>  $chitha,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->copy_of_chitha_type,
                        "enclType" => $dbrow->form_data->copy_of_chitha_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );
                    $revenue_patta_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->settlement_land_patta_copy));

                    $attachment_six = array(
                        "encl" =>  $revenue_patta_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->settlement_land_patta_copy_type,
                        "enclType" => $dbrow->form_data->settlement_land_patta_copy_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['address_proof'] = $attachment_one;
                    $postdata['id_proof_doc'] = $attachment_two;
                    $postdata['rev_receipt_doc'] = $attachment_three;
                    $postdata['jamabandi'] = $attachment_four;
                    $postdata['chitha'] = $attachment_five;
                    $postdata['revenue_patta_doc'] = $attachment_six;
                }

                if ($dbrow->form_data->service_id == "DLP") {

                    $police_verification_report = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->police_verification_report));

                    $attachment_one = array(
                        "encl" =>  $police_verification_report,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->police_verification_report_type,
                        "enclType" => $dbrow->form_data->police_verification_report_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    // pre($dbrow->form_data->up_to_date_khajna_receipt);
                    $up_to_date_khajna_reciept = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->up_to_date_khajna_receipt));

                    $attachment_two = array(
                        "encl" =>  $up_to_date_khajna_reciept,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->up_to_date_khajna_receipt_type,
                        "enclType" => $dbrow->form_data->up_to_date_khajna_receipt_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );


                    $no_due_certificate_from_bank = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->no_dues_certificate_from_bank));

                    $attachment_three = array(
                        "encl" =>  $no_due_certificate_from_bank,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->no_dues_certificate_from_bank_type,
                        "enclType" => $dbrow->form_data->no_dues_certificate_from_bank_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['police_verification_report'] = $attachment_one;
                    $postdata['up_to_date_khajna_reciept'] = $attachment_two;
                    $postdata['no_due_certificate_from_bank'] = $attachment_three;
                }
                if ($dbrow->form_data->service_id == "LHOLD") {

                    $address_proof_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->address_proof));

                    $attachment_one = array(
                        "encl" =>  $address_proof_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->address_proof_type,
                        "enclType" => $dbrow->form_data->address_proof_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );


                    $identity_proof_doc = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->identity_proof));

                    $attachment_two = array(
                        "encl" =>  $identity_proof_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->identity_proof_type,
                        "enclType" => $dbrow->form_data->identity_proof_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['address_proof_doc'] = $attachment_one;
                    $postdata['identity_proof_doc'] = $attachment_two;
                }

                $spId = array(
                    "applId" => $dbrow->service_data->appl_id
                );

                $postdata['spId'] = $spId;

                //end code

                // pre($postdata);
                $url = $this->config->item('kaac_post_url');


                if ($dbrow->form_data->service_id == "DCTH") {

                    $curl = curl_init($url . "chitha/chitha_copy/update_certicopy.php");
                } elseif ($dbrow->form_data->service_id == "CCJ") {

                    $curl = curl_init($url . "ccsdp/certified_copy/update_certicopy.php");
                } elseif ($dbrow->form_data->service_id == "CCM") {

                    $curl = curl_init($url . "mutation/certified_copy/update_certicopy.php");
                } elseif ($dbrow->form_data->service_id == "DLP") {

                    $curl = curl_init($url . "patta/duplicate_patta/update_certicopy.php");
                } elseif ($dbrow->form_data->service_id == "ITMKA") {

                    $curl = curl_init($url . "tracemap/certified_copy/update_certicopy.php");
                } elseif ($dbrow->form_data->service_id == "LHOLD") {
                    // test link not available
                    $curl = curl_init($url . "landholding/land_holding/update_certicop");
                } elseif ($dbrow->form_data->service_id == "LRCC") {

                    $curl = curl_init($url . "khajana/revenue_certificate/update_certicopy.php");
                } elseif ($dbrow->form_data->service_id == "LVC") {

                    $curl = curl_init($url . "artps/land_valuation/update_certicopy.php");
                } elseif ($dbrow->form_data->service_id == "NECKA") {

                    $curl = curl_init($url . "encumbrance/non_encumbrance/update_certicopy");
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
                    // pre($data["service_data.appl_status"]);
                    // exit();
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
                    "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $postdata = array(

                    "father_name" => $dbRow->form_data->father_name,
                    "dag_no" => $dbRow->form_data->dag_no,
                    "land_area_bigha" => $dbRow->form_data->land_area_bigha,
                    // "land_area_katha" => $dbRow->form_data->land_area_katha,
                    "patta_type" => $dbRow->form_data->patta_type,
                    "post_office" => $dbRow->form_data->post_office,
                    "police_station" => $dbRow->form_data->police_station,
                    "father_title" => $dbRow->form_data->father_title,

                );
                if($dbRow->form_data->service_id == "DCTH" || $dbRow->form_data->service_id == "CCJ" || $dbRow->form_data->service_id == "CCM" || $dbRow->form_data->service_id == "DLP" || $dbRow->form_data->service_id == "LHOLD" || $dbRow->form_data->service_id == "ITMKA" || $dbRow->form_data->service_id == "LVC" || $dbRow->form_data->service_id == "NECKA"){
                    $postdata['land_area_katha'] = $dbRow->form_data->land_area_katha;
                }
                if($dbRow->form_data->service_id == "LRCC"){
                    $postdata['land_area_kotha'] = $dbRow->form_data->land_area_katha;
                }

                if ($dbRow->form_data->service_id == "DCTH" || $dbRow->form_data->service_id == "CCJ" || $dbRow->form_data->service_id == "CCM" || $dbRow->form_data->service_id == "DLP" || $dbRow->form_data->service_id == "LHOLD" || $dbRow->form_data->service_id == "ITMKA" || $dbRow->form_data->service_id == "LVC" || $dbRow->form_data->service_id == "NECKA") {
                    $postdata['application_ref_no'] = $dbRow->service_data->appl_ref_no;
                } elseif ($dbRow->form_data->service_id == "LRCC") {
                    $postdata['application_ref_number'] = $dbRow->service_data->appl_ref_no;
                }

                // pre("asdasd");
                if ($dbRow->form_data->service_id == "DCTH" || $dbRow->form_data->service_id == "CCJ" || $dbRow->form_data->service_id == "CCM" || $dbRow->form_data->service_id == "DLP" || $dbRow->form_data->service_id == "LHOLD" || $dbRow->form_data->service_id == "LRCC" || $dbRow->form_data->service_id == "LVC" || $dbRow->form_data->service_id == "NECKA") {
                    // pre("Hello");
                    $postdata['periodic_patta_no'] = $dbRow->form_data->periodic_patta_no;
                    $postdata['circle_office'] = $dbRow->form_data->circle_office;
                    $postdata['mouza_name'] = $dbRow->form_data->mouza_name;
                    $postdata['revenue_village'] = $dbRow->form_data->revenue_village;
                }
                if ($dbRow->form_data->service_id == "DCTH" || $dbRow->form_data->service_id == "CCJ" || $dbRow->form_data->service_id == "DLP" || $dbRow->form_data->service_id == "LHOLD" || $dbRow->form_data->service_id == "LRCC" || $dbRow->form_data->service_id == "LVC" || $dbRow->form_data->service_id == "NECKA") {
                    // pre("Hello");
                    $postdata['first_name'] = $dbRow->form_data->first_name;
                    $postdata['last_name'] = $dbRow->form_data->last_name;
                }

                if ($dbRow->form_data->service_id == "DCTH" || $dbRow->form_data->service_id == "CCJ" || $dbRow->form_data->service_id == "ITMKA" || $dbRow->form_data->service_id == "LHOLD" || $dbRow->form_data->service_id == "LRCC" || $dbRow->form_data->service_id == "LVC" || $dbRow->form_data->service_id == "NECKA") {

                    $postdata['mobile_number'] = $dbRow->form_data->mobile;
                }
                if ($dbRow->form_data->service_id == "DCTH" || $dbRow->form_data->service_id == "CCJ" || $dbRow->form_data->service_id == "CCM" || $dbRow->form_data->service_id == "DLP" || $dbRow->form_data->service_id == "LHOLD" || $dbRow->form_data->service_id == "LRCC" || $dbRow->form_data->service_id == "LVC" || $dbRow->form_data->service_id == "NECKA") {

                    // $postdata["dag_no"] = $dbRow->form_data->dag_no;
                    $postdata["periodic_patta_no"] = $dbRow->form_data->periodic_patta_no;
                    $postdata["land_area_loosa"] = $dbRow->form_data->land_area_loosa;
                    $postdata["land_area_sq_ft"] = $dbRow->form_data->land_area_sq_ft;
                }

                if ($dbRow->form_data->service_id == "DCTH" || $dbRow->form_data->service_id == "CCJ" || $dbRow->form_data->service_id == "ITMKA" || $dbRow->form_data->service_id == "DLP" || $dbRow->form_data->service_id == "LHOLD" || $dbRow->form_data->service_id == "LRCC" || $dbRow->form_data->service_id == "LVC" || $dbRow->form_data->service_id == "NECKA") {
                    $postdata["email"] = $dbRow->form_data->email;
                    $postdata["gender"] = $dbRow->form_data->applicant_gender;
                    $postdata["applicant_title"] = $dbRow->form_data->applicant_title;
                }
                // pre($postdata);
                if ($dbRow->form_data->service_id == "DCTH" || $dbRow->form_data->service_id == "CCJ" || $dbRow->form_data->service_id == "CCM" || $dbRow->form_data->service_id == "LHOLD" || $dbRow->form_data->service_id == "LRCC" || $dbRow->form_data->service_id == "LVC" || $dbRow->form_data->service_id == "NECKA") {
                    $postdata['father_title'] = $dbRow->form_data->father_title;
                }
                if ($dbRow->form_data->service_id == "ITMKA") {
                    $postdata['land_area_lesa'] = $dbRow->form_data->land_area_loosa;
                    $postdata['applicant_first_name'] = $dbRow->form_data->first_name;
                    $postdata['applicant_last_name'] = $dbRow->form_data->last_name;
                    $postdata['circle'] = $dbRow->form_data->circle_office;
                    $postdata['village'] = $dbRow->form_data->revenue_village;
                    $postdata['mouza'] = $dbRow->form_data->mouza_name;
                    $postdata['ap_pp_no'] = $dbRow->form_data->periodic_patta_no;
                }
                if ($dbRow->form_data->service_id == "DLP") {
                    $postdata['applicant_mobile_number'] = $dbRow->form_data->mobile;
                }
                if ($dbRow->form_data->service_id == "CCM") {


                    $postdata['applicantFirstName'] = $dbRow->form_data->first_name;
                    $postdata['applicantLastName'] = $dbRow->form_data->last_name;
                    $postdata['applicantMobileNo'] = $dbRow->form_data->mobile;
                    $postdata['emailId'] = $dbRow->form_data->email;
                    $postdata['applicantGender'] = $dbRow->form_data->applicant_gender;
                    $postdata['mut_name_title'] = $dbRow->form_data->mut_name_title;
                    $postdata['mut_first_name'] = $dbRow->form_data->mut_first_name;
                    $postdata['mut_last_name'] = $dbRow->form_data->mut_last_name;
                    $postdata['mut_father_title'] = $dbRow->form_data->mut_father_title;
                    $postdata['mut_father_name'] = $dbRow->form_data->mut_father_name;
                    $postdata['mut_gender'] = $dbRow->form_data->mut_gender;
                    $postdata['mut_mobile'] = $dbRow->form_data->mut_mobile;
                    $postdata['mut_email'] = $dbRow->form_data->mut_email;
                }

                if ((($dbRow->form_data->service_id == "ITMKA") || ($dbRow->form_data->service_id == "DCTH")) && (!empty($dbRow->form_data->address_proof))) {

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
                    $postdata['address_proof'] = $attachment_one;
                }
                if ((($dbRow->form_data->service_id == "ITMKA") || ($dbRow->form_data->service_id == "DCTH")) && (!empty($dbRow->form_data->identity_proof))) {

                    $identity_proof = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->identity_proof));

                    $attachment_one = array(
                        "encl" =>  $identity_proof,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->identity_proof_type,
                        "enclType" => $dbRow->form_data->identity_proof_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['identity_proof'] = $attachment_one;
                }
                if (($dbRow->form_data->service_id == "ITMKA") && (!empty($dbRow->form_data->land_patta_copy))) {

                    $land_document = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->land_patta_copy));

                    $attachment_one = array(
                        "encl" =>  $land_document,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->land_patta_copy_type,
                        "enclType" => $dbRow->form_data->land_patta_copy_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['land_document'] = $attachment_one;
                }

                // pre($dbRow->form_data->service_id);
                if ((($dbRow->form_data->service_id == "ITMKA") || ($dbRow->form_data->service_id == "LRCC"))) {

                    if ($dbRow->form_data->service_id == "ITMKA") {

                        $land_revenue_receipt = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->land_revenue_receipt));
                        $encl = $dbRow->form_data->land_revenue_receipt_type;
                    } elseif ($dbRow->form_data->service_id == "LRCC") {
                        $land_revenue_receipt = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->last_time_paid_Land_revenue_receipt));
                        $encl = $dbRow->form_data->last_time_paid_Land_revenue_receipt_type;
                    }

                    $attachment_one = array(
                        "encl" =>  $land_revenue_receipt,
                        "docType" => "application/pdf",
                        "enclFor" => $encl,
                        "enclType" => $encl,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['land_revenue_receipt'] = $attachment_one;
                }
                if ((($dbRow->form_data->service_id == "ITMKA")) && (!empty($dbRow->form_data->settlement_land_patta_copy))) {

                    // $settlement_land_patta_copy = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->settlement_land_patta_copy));

                    // $attachment_one = array(
                    //     "encl" =>  $settlement_land_patta_copy,
                    //     "docType" => "application/pdf",
                    //     "enclFor" => $dbRow->form_data->settlement_land_patta_copy_type,
                    //     "enclType" => $dbRow->form_data->settlement_land_patta_copy_type,
                    //     "id" => "93963",
                    //     "doctypecode" => "6863",
                    //     "docRefId" => "8301",
                    //     "enclExtn" => "pdf"
                    // );

                    // $postdata['soft_copy'] = $attachment_one;
                }
                if ((($dbRow->form_data->service_id == "DCTH") || ($dbRow->form_data->service_id == "CCJ") || ($dbRow->form_data->service_id == "CCM") || ($dbRow->form_data->service_id == "DLP") || ($dbRow->form_data->service_id == "LHOLD") ||  ($dbRow->form_data->service_id == "LVC") || ($dbRow->form_data->service_id == "NECKA"))) {

                    if (($dbRow->form_data->service_id == "DLP")) {

                        $land_patta_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->photocopy_of_existing_land_documents));
                        $enclFor = $dbRow->form_data->photocopy_of_existing_land_documents_type;
                    } elseif ($dbRow->form_data->service_id == "DCTH" || $dbRow->form_data->service_id == "LHOLD") {

                        $land_patta_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->land_patta_copy));
                        $enclFor = $dbRow->form_data->land_patta_copy_type;
                    } elseif (($dbRow->form_data->service_id == "CCJ") || ($dbRow->form_data->service_id == "LVC") || ($dbRow->form_data->service_id == "NECKA")) {
                        $land_patta_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->Up_to_date_original_land_documents));
                        $enclFor = $dbRow->form_data->Up_to_date_original_land_documents_type;
                    } elseif ($dbRow->form_data->service_id == "CCM") {

                        $land_patta_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->settlement_land_patta_copy));
                        $enclFor = $dbRow->form_data->settlement_land_patta_copy_type;
                    }


                    $attachment_one = array(
                        "encl" =>  $land_patta_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $enclFor,
                        "enclType" => $enclFor,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['land_patta_doc'] = $attachment_one;
                }

                if (($dbRow->form_data->service_id == "DCTH") || ($dbRow->form_data->service_id == "CCJ") || ($dbRow->form_data->service_id == "LHOLD") || ($dbRow->form_data->service_id == "LVC") || ($dbRow->form_data->service_id == "NECKA")) {


                    if ($dbRow->form_data->service_id == "DCTH" || $dbRow->form_data->service_id == "LHOLD") {

                        $land_receipt_rev_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->updated_land_revenue_receipt));
                        $enclFor = $dbRow->form_data->updated_land_revenue_receipt_type;
                    } else if ($dbRow->form_data->service_id == "CCJ" || $dbRow->form_data->service_id == "NECKA" || $dbRow->form_data->service_id == "LVC") {

                        $land_receipt_rev_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->up_to_date_khajna_receipt));
                        $enclFor = $dbRow->form_data->up_to_date_khajna_receipt_type;
                    }


                    $attachment_one = array(
                        "encl" =>  $land_receipt_rev_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $enclFor,
                        "enclType" => $enclFor,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['land_receipt_rev_doc'] = $attachment_one;
                }

                if ($dbRow->form_data->service_id == "CCM") {

                    $address_proof_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->address_proof));

                    $attachment_one = array(
                        "encl" =>  $address_proof_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->address_proof_type,
                        "enclType" => $dbRow->form_data->address_proof_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );


                    $id_proof_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->identity_proof));

                    $attachment_two = array(
                        "encl" =>  $id_proof_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->identity_proof_type,
                        "enclType" => $dbRow->form_data->identity_proof_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );


                    $rev_receipt_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->land_revenue_receipt));

                    $attachment_three = array(
                        "encl" =>  $rev_receipt_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->land_revenue_receipt_type,
                        "enclType" => $dbRow->form_data->land_revenue_receipt_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $jamabandi = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->copy_of_jamabandi));

                    $attachment_four = array(
                        "encl" =>  $jamabandi,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->copy_of_jamabandi_type,
                        "enclType" => $dbRow->form_data->copy_of_jamabandi_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );
                    $chitha = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->copy_of_chitha));

                    $attachment_five = array(
                        "encl" =>  $chitha,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->copy_of_chitha_type,
                        "enclType" => $dbRow->form_data->copy_of_chitha_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );
                    $revenue_patta_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->settlement_land_patta_copy));

                    $attachment_six = array(
                        "encl" =>  $revenue_patta_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->settlement_land_patta_copy_type,
                        "enclType" => $dbRow->form_data->settlement_land_patta_copy_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['address_proof_doc'] = $attachment_one;
                    $postdata['id_proof_doc'] = $attachment_two;
                    $postdata['rev_receipt_doc'] = $attachment_three;
                    // $postdata['land_rev_receipt'] = $attachment_three;

                    $postdata['jamabandi'] = $attachment_four;
                    $postdata['chitha'] = $attachment_five;

                    $postdata['revenue_patta_doc'] = $attachment_six;
                    // $postdata['revenue_patta_doc'] = $attachment_six;
                }

                if ($dbRow->form_data->service_id == "DLP") {

                    $police_verification_report = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->police_verification_report));

                    $attachment_one = array(
                        "encl" =>  $police_verification_report,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->police_verification_report_type,
                        "enclType" => $dbRow->form_data->police_verification_report_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    // pre($dbRow->form_data->up_to_date_khajna_receipt);
                    $up_to_date_khajna_reciept = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->up_to_date_khajna_receipt));

                    $attachment_two = array(
                        "encl" =>  $up_to_date_khajna_reciept,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->up_to_date_khajna_receipt_type,
                        "enclType" => $dbRow->form_data->up_to_date_khajna_receipt_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );


                    $no_due_certificate_from_bank = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->no_dues_certificate_from_bank));

                    $attachment_three = array(
                        "encl" =>  $no_due_certificate_from_bank,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->no_dues_certificate_from_bank_type,
                        "enclType" => $dbRow->form_data->no_dues_certificate_from_bank_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['police_verification_report'] = $attachment_one;
                    $postdata['up_to_date_khajna_reciept'] = $attachment_two;
                    $postdata['no_due_certificate_from_bank'] = $attachment_three;
                }
                if ($dbRow->form_data->service_id == "LHOLD") {

                    $address_proof_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->address_proof));

                    $attachment_one = array(
                        "encl" =>  $address_proof_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->address_proof_type,
                        "enclType" => $dbRow->form_data->address_proof_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );


                    $identity_proof_doc = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->identity_proof));

                    $attachment_two = array(
                        "encl" =>  $identity_proof_doc,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->identity_proof_type,
                        "enclType" => $dbRow->form_data->identity_proof_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['address_proof_doc'] = $attachment_one;
                    $postdata['identity_proof_doc'] = $attachment_two;
                }
                if (($dbRow->form_data->service_id == "LRCC") && (!empty($dbRow->form_data->land_patta_copy))) {

                    $land_document = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->land_patta_copy));

                    $attachment_one = array(
                        "encl" =>  $land_document,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->land_patta_copy_type,
                        "enclType" => $dbRow->form_data->land_patta_copy_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['land_patta_document'] = $attachment_one;
                }

                $spId = array(
                    "applId" => $dbRow->service_data->appl_id
                );

                $postdata['spId'] = $spId;


                // pre(json_encode($postdata));
                $url = $this->config->item('kaac_post_url');


                if ($dbRow->form_data->service_id == "DCTH") {

                    $curl = curl_init($url . "chitha/chitha_copy/post_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "CCJ") {

                    $curl = curl_init($url . "ccsdp/certified_copy/post_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "CCM") {

                    $curl = curl_init($url . "mutation/certified_copy/post_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "DLP") {

                    $curl = curl_init($url . "patta/duplicate_patta/post_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "ITMKA") {

                    $curl = curl_init($url . "tracemap/certified_copy/post_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "LHOLD") {
                    // test link not available
                    $curl = curl_init($url . "landholding/land_holding/post_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "LRCC") {

                    $curl = curl_init($url . "khajana/revenue_certificate/post_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "LVC") {

                    $curl = curl_init($url . "artps/land_valuation/post_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "NECKA") {

                    $curl = curl_init($url . "encumbrance/non_encumbrance/post_certicopy.php");
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
                            "service_name" => 'Appl.for Bulding Permission',
                            "submission_date" => date("d-m-Y h:i a", strtotime($nowTime)),
                            "app_ref_no" => $dbRow->service_data->appl_ref_no
                        );
                        sms_provider("submission", $sms);
                        redirect('spservices/kaac/registration/acknowledgement/' . $obj);
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
            $this->load->view('kaac/first_group/kaac_view_form_data', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaac/registration/queryform');
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
            // $data['back_to_dasboard'] = '<a href="' . base_url('spservices/applications/') . '" class="btn btn-primary mb-2 pull-right"  >Back To Dashboard</a>';
            $data['pageTitle'] = "Acknowledgement";
            $this->load->view('includes/frontend/header');
            $this->load->view('/kaac/first_group/ack', $data);
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
        $this->load->view('kaac/first_group/query_charge_template', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function querypaymentsubmit($obj = null)
    {
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

                );
                if (($dbRow->form_data->service_id == "ITMKA") || ($dbRow->form_data->service_id == "DCTH") || ($dbRow->form_data->service_id == "CCM") || $dbRow->form_data->service_id == "CCJ" || $dbRow->form_data->service_id == "NECKA" || $dbRow->form_data->service_id == "LHOLD" || $dbRow->form_data->service_id == "LVC" || $dbRow->form_data->service_id == "DLP") {
                    $postdata['payment_ref_number'] = $dbRow->form_data->query_payment_response->GRN;
                } elseif ($dbRow->form_data->service_id == "LRCC") {
                    $postdata['payment_ref_no'] = $dbRow->form_data->query_payment_response->GRN;
                }

                $spId = array(
                    "applId" => $dbRow->service_data->appl_id
                );

                $postdata['spId'] = $spId;

                $url = $this->config->item('kaac_post_url');


                if ($dbRow->form_data->service_id == "DCTH") {

                    $curl = curl_init($url . "chitha/chitha_copy/update_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "CCJ") {

                    $curl = curl_init($url . "ccsdp/certified_copy/update_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "CCM") {

                    $curl = curl_init($url . "mutation/certified_copy/update_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "DLP") {

                    $curl = curl_init($url . "patta/duplicate_patta/update_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "ITMKA") {

                    $curl = curl_init($url . "tracemap/certified_copy/update_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "LHOLD") {
                    // test link not available
                    $curl = curl_init($url . "landholding/land_holding/update_certicop");
                } elseif ($dbRow->form_data->service_id == "LRCC") {

                    $curl = curl_init($url . "khajana/revenue_certificate/update_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "LVC") {

                    $curl = curl_init($url . "artps/land_valuation/update_certicopy.php");
                } elseif ($dbRow->form_data->service_id == "NECKA") {

                    $curl = curl_init($url . "encumbrance/non_encumbrance/update_certicopy");
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
                        "processed_by" => "Payment Query submitted by " . $dbRow->form_data->applicant_name,
                        "action_taken" => "Payment Query submitted",
                        "remarks" => "Payment Query submitted by " . $dbRow->form_data->applicant_name,
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );

                    $data = array(
                        "service_data.appl_status" => "QA",
                        'processing_history' => $processing_history,
                    );

                    $this->kaac_registration_model->update_where(['_id' => new ObjectId($obj)], $data);

                    // $this->session->set_flashdata('success','Your application has been successfully updated');

                    // pre("Success");
                    redirect('spservices/kaac/registration/payment_acknowledgement/' . $obj);
                } else {
                    $this->session->set_flashdata('errmessage', 'Unable to update data!!! Please try again.');
                    $this->my_transactions();
                } //End of if else
            }

            $this->my_transactions();
        }
    }
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
    }//End of download_certificate()

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
        $this->load->view('kaac/applications/ack', $data);
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
