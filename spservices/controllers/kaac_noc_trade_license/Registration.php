<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps
{
    private $serviceId = "NOCTL";
    private $base_serviceId = "1878";
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
        if ($obj_id == $this->serviceId || $obj_id == null) {
            $data = array(
                "pageTitle" => "Issuance of NOC for Trade License",
                "pageTitleId" => $this->serviceId
            );
            $data["dbrow"] = null;
        } else if ($this->checkObjectId($obj_id)) {
            $filter = array("_id" => new ObjectId($obj_id), "service_data.appl_status" => "DRAFT");
            $data["dbrow"] = $this->kaac_registration_model->get_row($filter);
        }

        $data['usser_type'] = $this->slug;

        $this->load->view('includes/frontend/header');
        $this->load->view('kaac_noc_trade_license/kaac_noctl_registration_form', $data);
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

        // $this->form_validation->set_rules('firm_name', 'Firm Name', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('proprietor_name', 'Name of the Proprietor', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('community', 'Community', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('occupation_trade', 'Occupation/Trade', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean|strip_tags');

        // $this->form_validation->set_rules('place_of_business', 'Place of Business', 'trim|required|xss_clean|strip_tags');



        // $this->form_validation->set_rules('periodic_patta_no', 'Annual Patta No', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('patta_type', 'Patta Type', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('land_area_katha', 'Kotha', 'trim|required|integer|xss_clean|strip_tags');
        // $this->form_validation->set_rules('land_area_bigha', 'Bigha', 'trim|required|integer|xss_clean|strip_tags');
        // $this->form_validation->set_rules('land_area_loosa', 'Loosa', 'trim|required|xss_clean|strip_tags');
        // $this->form_validation->set_rules('land_area_sq_ft', 'Land Area', 'trim|required|xss_clean|strip_tags');



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

            // pre($app_id);
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
                'applicant_title' => $this->input->post("applicant_title"),
                'first_name' => $this->input->post("first_name"),
                'last_name' => $this->input->post("last_name"),
                'applicant_name' => $this->input->post("applicant_title") . ' ' . $this->input->post("first_name") . ' ' . $this->input->post("last_name"),
                'applicant_gender' => $this->input->post("gender"),
                'mobile' => $this->input->post("mobile"),
                'email' => $this->input->post("email"),
                'father_title' => $this->input->post("father_title"),
                'father_name' => $this->input->post("father_name"),
                'firm_name' => $this->input->post("firm_name"),
                'proprietor_name' => $this->input->post("proprietor_name"),
                'community' => $this->input->post("community"),
                'occupation_trade' => $this->input->post("occupation_trade"),
                'place_of_business' => $this->input->post("place_of_business"),
                'class_of_business' => $this->input->post("class_of_business"),
                'address' => $this->input->post("address"),
                'reason' => $this->input->post("reason"),
                'room_occupied_text' => $this->input->post("room_occupied_text"),
                'room_occupied' => $this->input->post("room_occupied"),
                'declaration' => $this->input->post("declaration"),

                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];



            if (strlen($objId)) {
                // pre($this->input->post("business_reg_certificate_type"));
                $form_data["address_proof_type"] = $this->input->post("address_proof_type");
                $form_data["address_proof_type_text"] = $this->input->post("address_proof_type_text");
                $form_data["address_proof"] = $this->input->post("address_proof");
                $form_data["identity_proof_type"] = $this->input->post("identity_proof_type");
                $form_data["identity_proof_type_text"] = $this->input->post("identity_proof_type_text");
                $form_data["identity_proof"] = $this->input->post("identity_proof");
                $form_data["passport_photo_type"] = $this->input->post("passport_photo_type");
                $form_data["passport_photo_type_text"] = $this->input->post("passport_photo_type_text");
                $form_data["passport_photo"] = $this->input->post("passport_photo");

                if ($this->input->post("business_reg_certificate_type") != "" && $this->input->post("business_reg_certificate") != "") {
                    // pre("asdasd");
                    $form_data['business_reg_certificate_type'] = $this->input->post("business_reg_certificate_type");
                    $form_data['business_reg_certificate_type_text'] = $this->input->post("business_reg_certificate_type_text");
                    $form_data['business_reg_certificate'] = $this->input->post("business_reg_certificate");
                }

                if ($this->input->post("mbtc_room_rent_deposit_type")  != "" && $this->input->post("mbtc_room_rent_deposit")  != "") {
                    // pre("asdkajsdk");
                    $form_data['mbtc_room_rent_deposit_type'] = $this->input->post("mbtc_room_rent_deposit_type");
                    $form_data['mbtc_room_rent_deposit_type_text'] = $this->input->post("mbtc_room_rent_deposit_type_text");
                    $form_data['mbtc_room_rent_deposit'] = $this->input->post("mbtc_room_rent_deposit");
                }

                if ($this->input->post("consideration_letter_type") != "" && $this->input->post("consideration_letter") != "") {

                    $form_data['consideration_letter_type'] = $this->input->post("consideration_letter_type");
                    $form_data['consideration_letter_type_text'] = $this->input->post("consideration_letter_type_text");
                    $form_data['consideration_letter'] = $this->input->post("consideration_letter");
                }

                if ($this->input->post("signature_type") != "" && $this->input->post("signature") != "") {

                    $form_data['signature_type'] = $this->input->post("signature_type");
                    $form_data['signature_type_text'] = $this->input->post("signature_type_text");
                    $form_data['signature'] = $this->input->post("signature");
                }
                // pre($this->input->post("house_tax_receipt_type"));
                if ($this->input->post("house_tax_receipt_type") != "" && $this->input->post("house_tax_receipt") != "") {
                    // pre("hiiiiiiiiiiiiiiiiiii");
                    $form_data['house_tax_receipt_type'] = $this->input->post("house_tax_receipt_type");
                    $form_data['house_tax_receipt_type_text'] = $this->input->post("house_tax_receipt_type_text");
                    $form_data['house_tax_receipt'] = $this->input->post("house_tax_receipt");
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

                redirect('spservices/kaac_noc_trade_license/registration/fileuploads/' . $objId);
                exit();
            } else {
                $insert = $this->kaac_registration_model->insert($inputs);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    // pre($inputs);
                    redirect('spservices/kaac_noc_trade_license/registration/fileuploads/' . $objectId);
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
            $this->load->view('kaac_noc_trade_license/kaac_noctl_fileuploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaac_noc_trade_license/registration/');
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

        $this->form_validation->set_rules('passport_photo_type', 'Passport Photo type', 'required');
        if (empty($this->input->post("passport_photo_old"))) {
            if ((empty($this->input->post("passport_photo_temp"))) && (($_FILES['passport_photo']['name'] == ""))) {
                $this->form_validation->set_rules('passport_photo', 'Passport Photo document', 'required');
            }

            if ((!empty($this->input->post("passport_photo_type"))) && (($_FILES['passport_photo']['name'] == "") && (empty($this->input->post("passport_photo_temp"))))) {
                $this->form_validation->set_rules('passport_photo', 'Passport Photo document', 'required');
            }
        }

        // $this->form_validation->set_rules('updated_land_revenue_receipt_type', 'Updated Land Revenue Receipt type', 'required');
        // if (empty($this->input->post("updated_land_revenue_receipt_old"))) {

        //     if ((empty($this->input->post("updated_land_revenue_receipt_type"))) && (($_FILES['updated_land_revenue_receipt']['name'] != "") || (!empty($this->input->post("updated_land_revenue_receipt_temp"))))) {
        //         // pre("sadasd");
        //         $this->form_validation->set_rules('updated_land_revenue_receipt_type', 'Updated Land Revenue Receipt type', 'required');
        //     }

        //     if ((!empty($this->input->post("updated_land_revenue_receipt_type"))) && (($_FILES['updated_land_revenue_receipt']['name'] == "") && (empty($this->input->post("updated_land_revenue_receipt_temp"))))) {
        //         $this->form_validation->set_rules('updated_land_revenue_receipt ', 'Updated Land revenue receipt Document', 'required');
        //     }
        // }


        // $this->form_validation->set_rules('salary_slip_type', 'Salary Slip', 'required');
        // if (empty($this->input->post("salary_slip_old"))) {
        //     if ((empty($this->input->post("salary_slip_type"))) && (($_FILES['salary_slip']['name'] != "") || (!empty($this->input->post("salary_slip_temp"))))) {
        //         $this->form_validation->set_rules('salary_slip_type', 'Salary Slip type', 'required');
        //     }

        //     if ((!empty($this->input->post("salary_slip_type"))) && (($_FILES['salary_slip']['name'] == "") && (empty($this->input->post("salary_slip_temp"))))) {
        //         $this->form_validation->set_rules('salary_slip', 'Salary Slip', 'required');
        //     }
        // }

        // $this->form_validation->set_rules('other_doc_type', 'Salary Slip', 'required');
        // if (empty($this->input->post("other_doc_old"))) {
        //     if ((empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] != "") || (!empty($this->input->post("other_doc_temp"))))) {
        //         $this->form_validation->set_rules('other_doc_type', 'Document type', 'required');
        //     }

        //     if ((!empty($this->input->post("other_doc_type"))) && (($_FILES['other_doc']['name'] == "") && (empty($this->input->post("other_doc_temp"))))) {
        //         $this->form_validation->set_rules('other_doc', 'Document', 'required');
        //     }
        // }


        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $address_proof = "";
        if ($_FILES['address_proof']['name'] != "") {
            $addressProof = cifileupload("address_proof");
            $address_proof = $addressProof["upload_status"] ? $addressProof["uploaded_path"] : null;
        } else if (!empty($this->input->post("address_proof_temp"))) {
            $addressProof = movedigilockerfile($this->input->post('address_proof_temp'));
            $address_proof = $addressProof["upload_status"] ? $addressProof["uploaded_path"] : null;
        }
        // pre($addressProof);
        $identity_proof = "";
        if ($_FILES['identity_proof']['name'] != "") {
            $identityProof = cifileupload("identity_proof");
            $identity_proof = $identityProof["upload_status"] ? $identityProof["uploaded_path"] : null;
        } else if (!empty($this->input->post("identity_proof_temp"))) {
            $identityProof = movedigilockerfile($this->input->post('identity_proof_temp'));
            $identity_proof = $identityProof["upload_status"] ? $identityProof["uploaded_path"] : null;
        }


        $passport_photo = "";
        if ($_FILES['passport_photo']['name'] != "") {
            $passportPhoto = cifileupload("passport_photo");
            $passport_photo = $passportPhoto["upload_status"] ? $passportPhoto["uploaded_path"] : null;
        } else if (!empty($this->input->post("passport_photo_temp"))) {
            $passportPhoto = movedigilockerfile($this->input->post('passport_photo_temp'));
            $passport_photo = $passportPhoto["upload_status"] ? $passportPhoto["uploaded_path"] : null;
        }

        $house_tax_receipt = "";
        if ($_FILES['house_tax_receipt']['name'] != "") {
            // pre("jhhj");
            $houseTaxReceipt = cifileupload("house_tax_receipt");
            $house_tax_receipt = $houseTaxReceipt["upload_status"] ? $houseTaxReceipt["uploaded_path"] : null;
        } else if (!empty($this->input->post("house_tax_receipt_temp"))) {
            $houseTaxReceipt = movedigilockerfile($this->input->post('house_tax_receipt_temp'));
            $house_tax_receipt = $houseTaxReceipt["upload_status"] ? $houseTaxReceipt["uploaded_path"] : null;
        }


        $business_reg_certificate = "";
        if ($_FILES['business_reg_certificate']['name'] != "") {
            // pre("jhhj");
            $businessRegistrationCertificate = cifileupload("business_reg_certificate");
            $business_reg_certificate = $businessRegistrationCertificate["upload_status"] ? $businessRegistrationCertificate["uploaded_path"] : null;
        } else if (!empty($this->input->post("business_reg_certificate_temp"))) {
            $businessRegistrationCertificate = movedigilockerfile($this->input->post('business_reg_certificate_temp'));
            $business_reg_certificate = $businessRegistrationCertificate["upload_status"] ? $businessRegistrationCertificate["uploaded_path"] : null;
        }


        $mbtc_room_rent_deposit = "";
        $consideration_letter = "";

        if ($_FILES['mbtc_room_rent_deposit']['name'] != "") {
            $mbtcRoomRentDeposit = cifileupload("mbtc_room_rent_deposit");
            $mbtc_room_rent_deposit = $mbtcRoomRentDeposit["upload_status"] ? $mbtcRoomRentDeposit["uploaded_path"] : null;
        } else if (!empty($this->input->post("mbtc_room_rent_deposit_temp"))) {
            $mbtcRoomRentDeposit = movedigilockerfile($this->input->post('mbtc_room_rent_deposit_temp'));
            $mbtc_room_rent_deposit = $mbtcRoomRentDeposit["upload_status"] ? $mbtcRoomRentDeposit["uploaded_path"] : null;
        }

        if ($_FILES['consideration_letter']['name'] != "") {
            $considerationLetter = cifileupload("consideration_letter");
            $consideration_letter = $considerationLetter["upload_status"] ? $considerationLetter["uploaded_path"] : null;
        } else if (!empty($this->input->post("consideration_letter_temp"))) {
            $considerationLetter = movedigilockerfile($this->input->post('consideration_letter_temp'));
            $consideration_letter = $considerationLetter["upload_status"] ? $considerationLetter["uploaded_path"] : null;
        }

        $signature = "";

        $signatureImg = cifileupload("signature");
        if ($_FILES['signature']['name'] != "") {
            $signature = $signatureImg["upload_status"] ? $signatureImg["uploaded_path"] : null;
        } else if (!empty($this->input->post("signature_temp"))) {
            $signatureImg = movedigilockerfile($this->input->post('signature_temp'));
            $signature = $signatureImg["upload_status"] ? $signatureImg["uploaded_path"] : null;
        }


        // pre($address_proof);

        $uploadedFiles = array(
            "address_proof_old" => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
            "identity_proof_old" => strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old"),
            "passport_photo_old" => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
            "house_tax_receipt_old" => strlen($house_tax_receipt) ? $house_tax_receipt : $this->input->post("house_tax_receipt_old"),
            "business_reg_certificate_old" => strlen($business_reg_certificate) ? $business_reg_certificate : $this->input->post("business_registration_certificate_old"),
            "mbtc_room_rent_deposit_old" => strlen($mbtc_room_rent_deposit) ? $mbtc_room_rent_deposit : $this->input->post("mbtc_room_rent_deposit_old"),
            "consideration_letter_old" => strlen($consideration_letter) ? $consideration_letter : $this->input->post("consideration_letter_old"),
            "signature_old" => strlen($signature) ? $signature : $this->input->post("signature_old")
        );
        // pre($uploadedFiles);
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($objId);
        } else {




            if (!empty($this->input->post("address_proof_type"))) {
                $data["form_data.address_proof_type"] = $this->input->post("address_proof_type");
                $data["form_data.address_proof_type_text"] = "Address Proof of the Applicant";
                $data["form_data.address_proof"] = strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old");
            }
            if (!empty($this->input->post("identity_proof_type"))) {
                $data["form_data.identity_proof_type"] = $this->input->post("identity_proof_type");
                $data["form_data.identity_proof_type_text"] = "Identity Proof";
                $data["form_data.identity_proof"] = strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old");
            }

            if (!empty($this->input->post("passport_photo_type"))) {
                $data["form_data.passport_photo_type"] = $this->input->post("passport_photo_type");
                $data["form_data.passport_photo_type_text"] = "Passport size photograph";
                $data["form_data.passport_photo"] = strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old");
            }

            if (!empty($this->input->post("house_tax_receipt_type"))) {
                $data["form_data.house_tax_receipt_type"] = $this->input->post("house_tax_receipt_type");
                $data["form_data.house_tax_receipt_type_text"] = "House Tax Receipt";
                $data["form_data.house_tax_receipt"] = strlen($house_tax_receipt) ? $house_tax_receipt : $this->input->post("house_tax_receipt_old");
            }


            if (!empty($this->input->post("business_reg_certificate_type"))) {
                $data["form_data.business_reg_certificate_type"] = $this->input->post("business_reg_certificate_type");
                $data["form_data.business_reg_certificate_type_text"] = "Copy of current Business Registration Certificate";
                $data["form_data.business_reg_certificate"] = strlen($business_reg_certificate) ? $business_reg_certificate : $this->input->post("house_tax_receipt_old");
            }

            if (!empty($this->input->post("mbtc_room_rent_deposit_type"))) {
                $data["form_data.mbtc_room_rent_deposit_type"] = $this->input->post("mbtc_room_rent_deposit_type");
                $data["form_data.mbtc_room_rent_deposit_type_text"] = "Valid MBTC Room rent deposit";
                $data["form_data.mbtc_room_rent_deposit"] = strlen($mbtc_room_rent_deposit) ? $mbtc_room_rent_deposit : $this->input->post("mbtc_room_rent_deposit_old");
            }

            if (!empty($this->input->post("consideration_letter_type"))) {
                $data["form_data.consideration_letter_type"] = $this->input->post("consideration_letter_type");
                $data["form_data.consideration_letter_type_text"] = "Special reason for Consideration letter";
                $data["form_data.consideration_letter"] = strlen($consideration_letter) ? $consideration_letter : $this->input->post("consideration_letter_old");
            }

            if (!empty($this->input->post("signature_type"))) {
                $data["form_data.signature_type"] = $this->input->post("signature_type");
                $data["form_data.signature_type_text"] = "Signature";
                $data["form_data.signature"] = strlen($signature) ? $signature : $this->input->post("signature_old");
            }





            $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $data);

            //....................
            $this->session->set_flashdata('success', 'Your application has been successfully submitted');

            // pre($data);
            // exit();
            redirect('spservices/kaac_noc_trade_license/registration/preview/' . $objId);
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
            $this->load->view('kaac_noc_trade_license/kaac_noctl_preview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaac_noc_trade_license/registration/');
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
            $this->load->view('kaac_noc_trade_license/kaac_noctl_track_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaac_noc_trade_license/registration/');
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
                $this->load->view('kaac_noc_trade_license/kaac_noctl_query_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                redirect('spservices/kaac_noc_trade_license/registration');
            } //End of if else
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/kaac_noc_trade_license/registration');
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

            $this->form_validation->set_rules('passport_photo_type', 'Passport Photo type', 'required');
            if (empty($this->input->post("passport_photo_old"))) {
                if ((empty($this->input->post("passport_photo_temp"))) && (($_FILES['passport_photo']['name'] == ""))) {
                    $this->form_validation->set_rules('passport_photo', 'Passport Photo document', 'required');
                }

                if ((!empty($this->input->post("passport_photo_type"))) && (($_FILES['passport_photo']['name'] == "") && (empty($this->input->post("passport_photo_temp"))))) {
                    $this->form_validation->set_rules('passport_photo', 'Passport Photo document', 'required');
                }
            }

            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");



            $address_proof = "";
            if ($_FILES['address_proof']['name'] != "") {
                $addressProof = cifileupload("address_proof");
                $address_proof = $addressProof["upload_status"] ? $addressProof["uploaded_path"] : null;
            } else if (!empty($this->input->post("address_proof_temp"))) {
                $addressProof = movedigilockerfile($this->input->post('address_proof_temp'));
                $address_proof = $addressProof["upload_status"] ? $addressProof["uploaded_path"] : null;
            }
            // pre($addressProof);
            $identity_proof = "";
            if ($_FILES['identity_proof']['name'] != "") {
                $identityProof = cifileupload("identity_proof");
                $identity_proof = $identityProof["upload_status"] ? $identityProof["uploaded_path"] : null;
            } else if (!empty($this->input->post("identity_proof_temp"))) {
                $identityProof = movedigilockerfile($this->input->post('identity_proof_temp'));
                $identity_proof = $identityProof["upload_status"] ? $identityProof["uploaded_path"] : null;
            }


            $passport_photo = "";
            if ($_FILES['passport_photo']['name'] != "") {
                $passportPhoto = cifileupload("passport_photo");
                $passport_photo = $passportPhoto["upload_status"] ? $passportPhoto["uploaded_path"] : null;
            } else if (!empty($this->input->post("passport_photo_temp"))) {
                $passportPhoto = movedigilockerfile($this->input->post('passport_photo_temp'));
                $passport_photo = $passportPhoto["upload_status"] ? $passportPhoto["uploaded_path"] : null;
            }

            $house_tax_receipt = "";
            if ($_FILES['house_tax_receipt']['name'] != "") {
                // pre("jhhj");
                $houseTaxReceipt = cifileupload("house_tax_receipt");
                $house_tax_receipt = $houseTaxReceipt["upload_status"] ? $houseTaxReceipt["uploaded_path"] : null;
            } else if (!empty($this->input->post("house_tax_receipt_temp"))) {
                $houseTaxReceipt = movedigilockerfile($this->input->post('house_tax_receipt_temp'));
                $house_tax_receipt = $houseTaxReceipt["upload_status"] ? $houseTaxReceipt["uploaded_path"] : null;
            }


            $business_reg_certificate = "";
            if ($_FILES['business_reg_certificate']['name'] != "") {
                // pre("jhhj");
                $businessRegistrationCertificate = cifileupload("business_reg_certificate");
                $business_reg_certificate = $businessRegistrationCertificate["upload_status"] ? $businessRegistrationCertificate["uploaded_path"] : null;
            } else if (!empty($this->input->post("business_reg_certificate_temp"))) {
                $businessRegistrationCertificate = movedigilockerfile($this->input->post('business_reg_certificate_temp'));
                $business_reg_certificate = $businessRegistrationCertificate["upload_status"] ? $businessRegistrationCertificate["uploaded_path"] : null;
            }


            $mbtc_room_rent_deposit = "";
            $consideration_letter = "";

            if ($_FILES['mbtc_room_rent_deposit']['name'] != "") {
                $mbtcRoomRentDeposit = cifileupload("mbtc_room_rent_deposit");
                $mbtc_room_rent_deposit = $mbtcRoomRentDeposit["upload_status"] ? $mbtcRoomRentDeposit["uploaded_path"] : null;
            } else if (!empty($this->input->post("mbtc_room_rent_deposit_temp"))) {
                $mbtcRoomRentDeposit = movedigilockerfile($this->input->post('mbtc_room_rent_deposit_temp'));
                $mbtc_room_rent_deposit = $mbtcRoomRentDeposit["upload_status"] ? $mbtcRoomRentDeposit["uploaded_path"] : null;
            }

            if ($_FILES['consideration_letter']['name'] != "") {
                $considerationLetter = cifileupload("consideration_letter");
                $consideration_letter = $considerationLetter["upload_status"] ? $considerationLetter["uploaded_path"] : null;
            } else if (!empty($this->input->post("consideration_letter_temp"))) {
                $considerationLetter = movedigilockerfile($this->input->post('consideration_letter_temp'));
                $consideration_letter = $considerationLetter["upload_status"] ? $considerationLetter["uploaded_path"] : null;
            }

            $signature = "";

            $signatureImg = cifileupload("signature");
            if ($_FILES['signature']['name'] != "") {
                $signature = $signatureImg["upload_status"] ? $signatureImg["uploaded_path"] : null;
            } else if (!empty($this->input->post("signature_temp"))) {
                $signatureImg = movedigilockerfile($this->input->post('signature_temp'));
                $signature = $signatureImg["upload_status"] ? $signatureImg["uploaded_path"] : null;
            }


            // pre($address_proof);

            $uploadedFiles = array(
                "address_proof_old" => strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old"),
                "identity_proof_old" => strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old"),
                "passport_photo_old" => strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old"),
                "house_tax_receipt_old" => strlen($house_tax_receipt) ? $house_tax_receipt : $this->input->post("house_tax_receipt_old"),
                "business_reg_certificate_old" => strlen($business_reg_certificate) ? $business_reg_certificate : $this->input->post("business_registration_certificate_old"),
                "mbtc_room_rent_deposit_old" => strlen($mbtc_room_rent_deposit) ? $mbtc_room_rent_deposit : $this->input->post("mbtc_room_rent_deposit_old"),
                "consideration_letter_old" => strlen($consideration_letter) ? $consideration_letter : $this->input->post("consideration_letter_old"),
                "signature_old" => strlen($signature) ? $signature : $this->input->post("signature_old")
            );


            $this->session->set_flashdata('uploaded_files', $uploadedFiles);

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $this->fileuploads($objId);
            } else {


                if (!empty($this->input->post("address_proof_type"))) {
                    $data["form_data.address_proof_type"] = $this->input->post("address_proof_type");
                    $data["form_data.address_proof_type_text"] = "Address Proof of the Applicant";
                    $data["form_data.address_proof"] = strlen($address_proof) ? $address_proof : $this->input->post("address_proof_old");
                }
                if (!empty($this->input->post("identity_proof_type"))) {
                    $data["form_data.identity_proof_type"] = $this->input->post("identity_proof_type");
                    $data["form_data.identity_proof_type_text"] = "Identity Proof";
                    $data["form_data.identity_proof"] = strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old");
                }

                if (!empty($this->input->post("passport_photo_type"))) {
                    $data["form_data.passport_photo_type"] = $this->input->post("passport_photo_type");
                    $data["form_data.passport_photo_type_text"] = "Passport size photograph";
                    $data["form_data.passport_photo"] = strlen($passport_photo) ? $passport_photo : $this->input->post("passport_photo_old");
                }

                if (!empty($this->input->post("house_tax_receipt_type"))) {
                    $data["form_data.house_tax_receipt_type"] = $this->input->post("house_tax_receipt_type");
                    $data["form_data.house_tax_receipt_type_text"] = "House Tax Receipt";
                    $data["form_data.house_tax_receipt"] = strlen($house_tax_receipt) ? $house_tax_receipt : $this->input->post("house_tax_receipt_old");
                }


                if (!empty($this->input->post("business_reg_certificate_type"))) {
                    $data["form_data.business_reg_certificate_type"] = $this->input->post("business_reg_certificate_type");
                    $data["form_data.business_reg_certificate_type_text"] = "Copy of current Business Registration Certificate";
                    $data["form_data.business_reg_certificate"] = strlen($business_reg_certificate) ? $business_reg_certificate : $this->input->post("house_tax_receipt_old");
                }

                if (!empty($this->input->post("mbtc_room_rent_deposit_type"))) {
                    $data["form_data.mbtc_room_rent_deposit_type"] = $this->input->post("mbtc_room_rent_deposit_type");
                    $data["form_data.mbtc_room_rent_deposit_type_text"] = "Valid MBTC Room rent deposit";
                    $data["form_data.mbtc_room_rent_deposit"] = strlen($mbtc_room_rent_deposit) ? $mbtc_room_rent_deposit : $this->input->post("mbtc_room_rent_deposit_old");
                }

                if (!empty($this->input->post("consideration_letter_type"))) {
                    $data["form_data.consideration_letter_type"] = $this->input->post("consideration_letter_type");
                    $data["form_data.consideration_letter_type_text"] = "Special reason for Consideration letter";
                    $data["form_data.consideration_letter"] = strlen($consideration_letter) ? $consideration_letter : $this->input->post("consideration_letter_old");
                }

                if (!empty($this->input->post("signature_type"))) {
                    $data["form_data.signature_type"] = $this->input->post("signature_type");
                    $data["form_data.signature_type_text"] = "Signature";
                    $data["form_data.signature"] = strlen($signature) ? $signature : $this->input->post("signature_old");
                }

                $this->kaac_registration_model->update_where(['_id' => new ObjectId($objId)], $data);
                //post data to KAAC

                $dbrow = $this->kaac_registration_model->get_by_doc_id($objId);
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
                $postdata['address_proof'] = $attachment_one;

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

                $postdata['identity_proof'] = $attachment_two;


                if (isset($dbrow->form_data->house_tax_receipt)) {
                    $house_tax_receipt = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->house_tax_receipt));
                    $house_tax_receipt_type = explode('-', $dbrow->form_data->house_tax_receipt_type);

                    $attachment_three = array(
                        "encl" =>  $house_tax_receipt,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->house_tax_receipt_type_text,
                        "enclType" => $dbrow->form_data->house_tax_receipt_type_text,
                        "id" => "93963",
                        "doctypecode" => $house_tax_receipt_type[0],
                        "docRefId" => $house_tax_receipt_type[1],
                        "enclExtn" => "pdf"
                    );

                    $postdata['house_tax_receipt'] = $attachment_three;
                }
                if (isset($dbrow->form_data->business_reg_certificate)) {
                    $current_business_reg_certificate = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->business_reg_certificate));

                    $attachment_four = array(
                        "encl" =>  $current_business_reg_certificate,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->business_reg_certificate_type,
                        "enclType" => $dbrow->form_data->business_reg_certificate_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['current_business_reg_certificate'] = $attachment_four;
                }
                // pre('sadasd');
                if (isset($dbrow->form_data->mbtc_room_rent_deposit)) {

                    $mbtc_room_rent = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->mbtc_room_rent_deposit));

                    $attachment_five = array(
                        "encl" =>  $mbtc_room_rent,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->mbtc_room_rent_deposit_type,
                        "enclType" => $dbrow->form_data->mbtc_room_rent_deposit_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['mbtc_room_rent'] = $attachment_five;
                }
                if (isset($dbrow->form_data->consideration_letter)) {
                    $special_reason_letter = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->consideration_letter));

                    $attachment_six = array(
                        "encl" =>  $special_reason_letter,
                        "docType" => "application/pdf",
                        "enclFor" => $dbrow->form_data->consideration_letter_type,
                        "enclType" => $dbrow->form_data->consideration_letter_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['special_reason_letter'] = $attachment_six;
                }

                if (isset($dbrow->form_data->passport_photo)) {
                    $passport_photo = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->passport_photo));

                    $attachment_seven = array(
                        "encl" =>  $passport_photo,
                        "docType" => "image/jpeg",
                        "enclFor" => $dbrow->form_data->passport_photo_type,
                        "enclType" => $dbrow->form_data->passport_photo_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['passport_photo'] = $attachment_seven;
                }
                if (isset($dbrow->form_data->signature)) {
                    $signature = base64_encode(file_get_contents(FCPATH . $dbrow->form_data->signature));
                    $attachment_eight = array(
                        "encl" =>  $signature,
                        "docType" => "image/jpeg",
                        "enclFor" => $dbrow->form_data->signature_type,
                        "enclType" => $dbrow->form_data->signature_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['signature'] = $attachment_eight;
                }

                $spId = array(
                    "applId" => $dbrow->service_data->appl_id
                );

                $postdata['spId'] = $spId;

                //end code

                // pre($postdata);
                $url = $this->config->item('kaac_post_url');


                if ($dbrow->form_data->service_id == $this->serviceId) {

                    $curl = curl_init($url . "municipal/NOC/update_certicopy.php");
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

                log_response($dbrow->service_data->appl_ref_no, $response);

                // $response = 1;

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
                redirect('spservices/kaac_noc_trade_license/registration/preview/' . $objId);
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
                    "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $postdata = array(
                    "application_ref_no" => $dbRow->service_data->appl_ref_no,
                    "applicant_prefix" => $dbRow->form_data->applicant_title,
                    "applicant_first_name" => $dbRow->form_data->first_name,
                    "applicant_last_name" => $dbRow->form_data->last_name,
                    "applicant_mobile_no" => $dbRow->form_data->mobile,
                    "applicant_email" => $dbRow->form_data->email,
                    "gender" => $dbRow->form_data->applicant_gender,
                    "firm_name" => $dbRow->form_data->firm_name,
                    "father_name" => $dbRow->form_data->father_name,
                    "proprietor_name" => $dbRow->form_data->proprietor_name,
                    "community" => $dbRow->form_data->community,
                    "occupation" => $dbRow->form_data->occupation_trade,
                    "address" => $dbRow->form_data->address,
                    "business_class" => $dbRow->form_data->class_of_business,
                    "father_prefix" => $dbRow->form_data->father_title,
                    "place_of_business" => $dbRow->form_data->place_of_business,
                    "special_reason" => $dbRow->form_data->reason,
                    "room_occupied" => $dbRow->form_data->room_occupied,

                );

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

                $postdata['identity_proof'] = $attachment_two;


                if (isset($dbRow->form_data->house_tax_receipt)) {
                    $house_tax_receipt = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->house_tax_receipt));
                    $house_tax_receipt_type = explode('-', $dbRow->form_data->house_tax_receipt_type);

                    $attachment_three = array(
                        "encl" =>  $house_tax_receipt,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->house_tax_receipt_type_text,
                        "enclType" => $dbRow->form_data->house_tax_receipt_type_text,
                        "id" => "93963",
                        "doctypecode" => $house_tax_receipt_type[0],
                        "docRefId" => $house_tax_receipt_type[1],
                        "enclExtn" => "pdf"
                    );

                    $postdata['house_tax_receipt'] = $attachment_three;
                }
                if (isset($dbRow->form_data->business_reg_certificate)) {
                    $current_business_reg_certificate = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->business_reg_certificate));

                    $attachment_four = array(
                        "encl" =>  $current_business_reg_certificate,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->business_reg_certificate_type,
                        "enclType" => $dbRow->form_data->business_reg_certificate_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['current_business_reg_certificate'] = $attachment_four;
                }
                // pre('sadasd');
                if (isset($dbRow->form_data->mbtc_room_rent_deposit)) {

                    $mbtc_room_rent = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->mbtc_room_rent_deposit));

                    $attachment_five = array(
                        "encl" =>  $mbtc_room_rent,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->mbtc_room_rent_deposit_type,
                        "enclType" => $dbRow->form_data->mbtc_room_rent_deposit_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['mbtc_room_rent'] = $attachment_five;
                }
                if (isset($dbRow->form_data->consideration_letter)) {
                    $special_reason_letter = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->consideration_letter));

                    $attachment_six = array(
                        "encl" =>  $special_reason_letter,
                        "docType" => "application/pdf",
                        "enclFor" => $dbRow->form_data->consideration_letter_type,
                        "enclType" => $dbRow->form_data->consideration_letter_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['special_reason_letter'] = $attachment_six;
                }

                if (isset($dbRow->form_data->passport_photo)) {
                    $passport_photo = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->passport_photo));

                    $attachment_seven = array(
                        "encl" =>  $passport_photo,
                        "docType" => "image/jpeg",
                        "enclFor" => $dbRow->form_data->passport_photo_type,
                        "enclType" => $dbRow->form_data->passport_photo_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['passport_photo'] = $attachment_seven;
                }
                if (isset($dbRow->form_data->signature)) {
                    $signature = base64_encode(file_get_contents(FCPATH . $dbRow->form_data->signature));
                    $attachment_eight = array(
                        "encl" =>  $signature,
                        "docType" => "image/jpeg",
                        "enclFor" => $dbRow->form_data->signature_type,
                        "enclType" => $dbRow->form_data->signature_type,
                        "id" => "93963",
                        "doctypecode" => "6863",
                        "docRefId" => "8301",
                        "enclExtn" => "pdf"
                    );

                    $postdata['signature'] = $attachment_eight;
                }
                $spId = array(
                    "applId" => $dbRow->service_data->appl_id
                );

                $postdata['spId'] = $spId;


                // pre(json_encode($postdata));

                $url = $this->config->item('kaac_post_url');


                if ($dbRow->form_data->service_id == "NOCTL") {

                    $curl = curl_init($url . "municipal/NOC/post_certicopy.php");
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

                // $response = 1;

                if ($response) {
                    // $response = json_decode($response);

                    // if ($response->ref->status === "success") {
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
                    redirect('spservices/applications/acknowledgement/' . $obj);
                    // redirect('spservices/kaac_noc_trade_license/registration/acknowledgement/' . $obj);
                    // } else {
                    //     $this->session->set_flashdata('errmessage', 'U nable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                    //     $this->my_transactions();
                    //     return;
                    // }
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
            $this->load->view('kaac_noc_trade_license/kaac_noctl_preview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
            redirect('spservices/kaac_noc_trade_license/');
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
            $this->load->view('/kaac_noc_trade_license/kaac_noctl_first_ack', $data);
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
        $this->load->view('kaac_noc_trade_license/kaac_noctl_query_charge_template', $data);
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


                // if ($dbRow->form_data->service_id == "ICERT") {

                //     $curl = curl_init($url . "income/income_certificate/update_certicopy.php");
                // } else {
                //     $this->my_transactions();
                // }
                // // pre($url . "encumbrance/non_encumbrance/post_certicopy.php");
                // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                // curl_setopt($curl, CURLOPT_POST, true);
                // curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                // $response = curl_exec($curl);
                // curl_close($curl);

                // log_response($dbRow->service_data->appl_ref_no, $response);

                $response = 1;
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

                    $this->session->set_flashdata('success', 'Your application has been successfully updated');

                    // pre("Success");
                    redirect('spservices/kaac_noc_trade_license/registration/payment_acknowledgement/' . $obj);
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
        $this->load->view('kaac_noc_trade_license/kaac_noctl_second_ack', $data);
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
