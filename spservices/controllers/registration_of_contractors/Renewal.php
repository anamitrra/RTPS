<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Renewal extends Rtps
{
    private $serviceName = "Application for Renewal of Registration of Contractors";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('registration_of_contractors/employment_model');
        $this->load->model('registration_of_contractors/district_model');
        $this->load->model('zones_model');
        $this->load->model('zonecircles_model');
        $this->load->model('upms/users_model');

        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper('smsprovider');
        $this->load->helper('contractor');
        $this->load->model('registration_of_contractors/logreports_model');
        $this->load->helper("role");
        $this->load->library('user_agent');
        $this->load->library('Digilocker_API');

        if ($this->session->role) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
    } //End of __construct()

    public function index($objId = null)
    {
        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/fetch_licence_details');
        $this->load->view('includes/frontend/footer');
    }

    public function get_old_data()
    {
        $this->form_validation->set_rules("licence_no", "Licence Number", "required");
        $this->form_validation->set_rules("pan_number", "Pan Number", "required");

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $status["status"] = false;
            $status["msg"] = validation_errors();
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($status));
        } else {
            $licence = ($this->input->post('licence_no'));
            $pan_number = ($this->input->post('pan_number'));
            $dbRow = (array)$this->mongo_db->where(['form_data.pan_card' => $pan_number, 'form_data.registration_no' => $licence, 'service_data.appl_status' => 'D'])->get('sp_applications');
            if (count($dbRow)) {
                $status["status"] = true;
                $status["msg"] = '<div class="border border-success text-center mb-3" style="margin-top:0px">' . $this->get_old_data_view($dbRow) . '</div>';
            } else {
                $status["status"] = true;
                $status["msg"] = '<div class="border border-success text-center p-2 mb-3 text-danger" style="margin-top:0px;font-weight:bold"><p>Data not found. Please check your details.</p></div>';
            }
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($status));
        }
    }

    private function get_old_data_view($data)
    {
        $valid_to = date('d-m-Y', strtotime($this->mongo_db->getDateTime($data[0]->form_data->renewal_date)));
        $expiry_dt = new DateTime($valid_to);
        $curr_date = new DateTime(date('Y-m-d'));

        if ($expiry_dt <= $curr_date) {
            return "<div class='card' style='text-align:left'>
        <div class='card-body'>
        <table class='table table-bordered'>
            <tr>
            <th colspan='4'class='text-center text-white bg-dark'>Previous Data</th>
            </tr>
            <tr>
                <td>Registration Number</td>
                <td>" . $data[0]->form_data->registration_no . "</td>
                <td width='20%'>Licence Details</td>
                <td width='30%'>" . $data[0]->service_data->department_name . '(' . $data[0]->form_data->category . ')' . "</td>
            </tr>
            <tr>
                <td width='20%'>Applied by</td>
                <td width='30%'>" . $data[0]->form_data->applicant_name . "</td>
                <td>Valid Upto</td>
                <td>" . $valid_to . "</td> 
            </tr>
            
        </table>
        <div class='text-center'>
        <button type='button' class='btn btn-sm btn-primary' value='" . base64_encode($data[0]->form_data->registration_no) . "' onclick='saveAndProceed(this)'>Proceed</button></div>
        </div></div>";
        } else {
            return '<div class="border text-center p-2 mb-3 text-danger" style="margin-top:0px;font-weight:bold"><p>Your Licence has not expired yet.</p></div>';
        }
    }

    public function save_old_data($l_no)
    {
        // $oldRecord = $this->mongo_db->where(array('form_data.licence_no' => $l_no))->get('sp_applications');
        $oldData = array(
            "form_data.registration_no" => base64_decode($l_no)
        );
        $oldRecord = $this->employment_model->get_row($oldData);
        if (empty($oldRecord)) {
            $this->session->set_flashdata('success', 'No Record found.');
            // redirect('spservices/employment-registration-nonaadhaar-reregistration');
            // exit();
        }

        $appl_ref_no_temp = $this->getID(7);
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
            );
            $rows = $this->employment_model->get_row($filter);
            if ($rows == false)
                break;
        }

        $service_id = $this->get_serviceID($oldRecord->form_data->deptt_name, $oldRecord->form_data->category);
        $service_name = 'Application for Renewal of Registration of Contractors';
        $created_at = getISOTimestamp();
        $service_data = array(
            "department_name" => $oldRecord->service_data->department_name,
            "service_id" => $service_id,
            "service_name" => $service_name,
            "appl_id" => $app_id,
            "appl_ref_no" => $appl_ref_no_temp,
            "submission_mode" => 'online',
            "applied_by" => $this->session->userdata('userId')->{'$id'},
            "appl_status" => "DRAFT",
            "created_at" => $created_at,
        );

        $form_data = (array)$oldRecord->form_data;
        $form_data['service_name'] = $service_name;
        $form_data['service_id'] = $service_id;
        $form_data['rtps_trans_id'] = $appl_ref_no_temp;
        $form_data['old_appl_ref_no'] = $oldRecord->service_data->appl_ref_no;
        unset($form_data['licence_no']);
        unset($form_data['payment_params']);
        unset($form_data['pfc_payment_response']);
        unset($form_data['pfc_payment_status']);
        unset($form_data['submission_date']);
        unset($form_data['department_id']);
        unset($form_data['declaration']);
        $data = array('service_data' => $service_data, 'form_data' => $form_data);
        $insertedData = $this->employment_model->insert($data);
        $obj_id = ($insertedData['_id']->{'$id'});
        redirect('spservices/registration_of_contractors/renewal/personal_details/' . $obj_id);
    }

    public function get_serviceID($deptt, $category)
    {
        $service_id = '';
        if ($deptt == 'PHED') {
            if ($category == 'Class-II') {
                $service_id = 'CON_REN_PHED_2';
            } else {
                $service_id = 'CON_REN_PHED_1';
            }
        } else if ($deptt == 'PWDB') {
            if ($category == 'Class-II') {
                $service_id = 'CON_REN_PWDB_2';
            } else {
                $service_id = 'CON_REN_PWDB_1';
            }
        } else if ($deptt == 'WRD') {
            if ($category == 'Class-II') {
                $service_id = 'CON_REN_WRD_2';
            } else {
                $service_id = 'CON_REN_WRD_1';
            }
        }

        return $service_id;
    }

    public function personal_details($objId = null)
    {
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/personal_details_renewal', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function get_zonal_offices()
    {
        $deptt_code = $this->input->post("deptt");
        if ($deptt_code == 'PHED') {
            $zones = $this->zones_model->get_rows(array('dept_code' => $deptt_code));
            $zonelist = array();
            if ($zones) {
                foreach ($zones as $zn) {
                    $zonelist[] = $zn->zone_name;
                }
            }
            $zone_arr = $zonelist;
        } else if ($deptt_code == 'PWDB') {
            $zone_arr = array("Guwahati Zone", "Dibrugarh Zone");
        } else if ($deptt_code == 'WRD') {
            $zone_arr = array("Guwahati Water Resources Circle", "Lower Assam Water Resources Circle");
        }

        $resData = array(
            "status" => 1,
            "ret" => $zone_arr,
            "msg" => ""
        );
        echo json_encode($resData);
    }

    public function get_circle_offices()
    {
        $deptt_code = $this->input->post("deptt");
        $zone = $this->input->post("zone");
        if ($deptt_code == 'PHED') {
            $zcircles = array();
            $circles = $this->zonecircles_model->get_rows(array('zone_name' => $zone));
            if ($circles) {
                foreach ($circles as $cir) {
                    $zcircles[] = $cir->circle_name;
                }
            }
            $circle_arr =  $zcircles;
            // $circle_arr = array("Tezpur Circle", "Guwahati Circle");
        } else if ($deptt_code == 'PWDB') {
            $circle_arr = array("Jorhat Circle", "Dibrugarh Circle");
        }

        $resData = array(
            "status" => 1,
            "ret" => $circle_arr,
            "msg" => ""
        );
        echo json_encode($resData);
    }


    public function submit_personal_details($objId = null)
    {
        $this->session->set_userdata("applicant_type", $this->input->post('applicant_type'));
        $this->session->set_userdata("deptt_name", $this->input->post('deptt_name'));
        $this->session->set_userdata("category", $this->input->post('category'));

        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules("applicant_type", "Applicant Type", "required");
        $this->form_validation->set_rules("deptt_name", "Department Name", "required");
        $this->form_validation->set_rules("category", "Class & Category", "required");
        $this->form_validation->set_rules("mobile", "Mobile Number", "required|max_length[10]|regex_match[/^[0-9]{10}$/]");
        // $deptt_arr = array("PHED", "PWDB", "WRD");
        $deptt_arr = array("PHED", "PWDB");

        if (in_array($this->input->post("deptt_name"), $deptt_arr)) {
            if ($this->input->post("category") == 'Class-II') {
                $this->form_validation->set_rules("zone", "Zone", "required");
                $this->form_validation->set_rules("circle", "Circle", "required");
            } else {
                $this->form_validation->set_rules("zone", "Zone", "required");
            }
        } else if ($this->input->post("deptt_name") == "WRD") {
            if ($this->input->post("category") == 'Class-II') {
                $this->form_validation->set_rules("circle", "Circle", "required");
            }
        }

        if ($this->input->post('applicant_type') == 'Individual') {
            $category_of_regs = $this->input->post("category_of_regs");
            //$this->form_validation->set_rules("category_of_regs", "Category of regs.", "required");
            $this->form_validation->set_rules("applicant_name", "Applicant Name", "required|trim|xss_clean|max_length[100]|regex_match[/^[a-z\.\/\s]+$/i]");
            $this->form_validation->set_rules("father_husband_name", "Father/Husband Name", "required|xss_clean|max_length[100]|regex_match[/^[a-z\.\/\s]+$/i]");
            $this->form_validation->set_rules("applicant_gender", "Gender", "required");


            $this->form_validation->set_rules("date_of_birth", "date of birth", "required|regex_match[/\d{2}-\d{2}-\d{4}/]");
            $this->form_validation->set_rules("caste", "Caste", "required");
            $this->form_validation->set_rules("religion", "Religion", "required");
        } else {
            $category_of_regs = '';
            $this->form_validation->set_rules("owner_director_name", "Authorised Signatory", "required|trim|xss_clean|max_length[100]|regex_match[/^[a-z\.\/\s]+$/i]");
            if ($this->input->post('applicant_type') != 'Proprietorship') {
                $this->form_validation->set_rules("org_name", "Organization Name", "required|trim|xss_clean|max_length[100]|regex_match[/^[a-z\.\/\s]+$/i]");
                $this->form_validation->set_rules("date_of_deed", "Date of deed", "required|regex_match[/\d{2}-\d{2}-\d{4}/]");
                $this->form_validation->set_rules("date_of_validity", "Date of validity", "required|regex_match[/\d{2}-\d{2}-\d{4}/]");
            }
        }
        /////////////////////////////////////
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules("pan_card", "PAN", "required|regex_match[/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/]");
        $this->form_validation->set_rules("gst_no", "GST No.", "required|regex_match[/\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}[Z]{1}[A-Z\d]{1}/]");

        $this->form_validation->set_rules("bank_name", "Bank Name", "required|max_length[30]");
        $this->form_validation->set_rules("branch_name", "Branch Name", "required|max_length[30]");
        $this->form_validation->set_rules("ifsc_code", "IFSC Code", "required|alpha_numeric|max_length[11]");
        $this->form_validation->set_rules("account_no", "Account No.", "required|numeric|max_length[20]");

        $this->form_validation->set_rules("date_of_working_contractor", "Date of working as a contractor", "regex_match[/\d{2}-\d{2}-\d{4}/]");
        //-----------------25/06/2023-----------------
        $date1 = $this->input->post("date_of_working_contractor");
        $date1 = date("Y-m-d", strtotime($date1));
        $date2 = date("Y-m-d");
        $diff = abs(strtotime($date2) - strtotime($date1));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $msg = '';
        if ($this->input->post("deptt_name") != 'PWDB' && $this->input->post("deptt_name") != 'PWDNH') {
            if ($this->input->post("category_of_regs") == 'Contractor from other department') {
                if ($this->input->post("category") == 'Class-II') {
                    if ($years < 2) {
                        $msg = '2 years of experience needed';
                    }
                } else {
                    if ($years < 3) {
                        $msg = '3 years of experience needed';
                    }
                }
            }
        }
        if ($msg) {
            $this->session->set_flashdata('error', $msg);
            $obj_id = strlen($objId) ? $objId : null;
            $this->index($obj_id);
        } else {

            $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

            //////////////////
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $obj_id = strlen($objId) ? $objId : null;
                //pre($this->input->post());
                //$this->index($obj_id);
                if ($obj_id == null) {
                    redirect('spservices/renewal-of-contractors');
                } else {
                    redirect('spservices/renewal-of-contractors/personal-details/' . $obj_id);
                }
                //var_dump(validation_errors());
                exit;
            } else {

                $appl_ref_no_temp = $this->getID(7);
                $serviceId = $this->get_serviceID($this->input->post("deptt_name"), $this->input->post("category"));
                $created_at = getISOTimestamp();
                $deptt_code = $this->input->post("deptt_name");
                $deptt_name = $this->getDepttName($deptt_code);
                $service_data = array(
                    "department_name" => $deptt_name,
                    "service_id" => $serviceId,
                    "service_name" => $this->serviceName,
                    "appl_id" => $appl_ref_no_temp,
                    "appl_ref_no" => $appl_ref_no_temp,
                    "submission_mode" => 'online',
                    "applied_by" => $this->session->userdata('userId')->{'$id'},
                    "appl_status" => "DRAFT",
                    "created_at" => $created_at,
                );

                $form_data = array(
                    "user_id" => $this->session->userdata('userId')->{'$id'},
                    "user_type" => $this->slug,
                    "applied_user_type" => $this->slug,
                    "service_name" => $this->serviceName,
                    "service_id" => $serviceId,
                    "deptt_name" => $deptt_code,
                    "category" => $this->input->post("category"),
                    "zone" => $this->input->post("zone"),
                    "circle" => $this->input->post("circle"),
                    "applicant_type" => $this->input->post("applicant_type"),
                    "category_of_regs" => $category_of_regs,
                    "mobile" => $this->input->post("mobile"),
                    "land_line" => $this->input->post("land_line"),
                    "email" => $this->input->post("email"),
                    "pan_card" => $this->input->post("pan_card"),
                    "gst_no" => $this->input->post("gst_no"),
                    "bank_name" => $this->input->post("bank_name"),
                    "ifsc_code" => $this->input->post("ifsc_code"),
                    "branch_name" => $this->input->post("branch_name"),
                    "account_no" => $this->input->post("account_no"),
                    "date_of_working_contractor" => $this->input->post("date_of_working_contractor"),
                    "prev_reg_no" => $this->input->post("prev_reg_no"),
                );

                if ($this->input->post("applicant_type") == 'Individual') {

                    $form_data['applicant_name'] = $this->input->post("applicant_name");
                    $form_data['father_husband_name'] = $this->input->post("father_husband_name");
                    $form_data['applicant_gender'] = $this->input->post("applicant_gender");
                    $form_data['date_of_birth'] = $this->input->post("date_of_birth");
                    $form_data['age'] = $this->input->post("age");
                    $form_data['caste'] = $this->input->post("caste");
                    $form_data['religion'] = $this->input->post("religion");
                    $form_data['nationality'] = $this->input->post("nationality");

                    $form_data['org_name'] = '';
                    $form_data['owner_director_name'] = '';
                    $form_data['date_of_deed'] = '';
                    $form_data['date_of_validity'] = '';
                } else {

                    $form_data['owner_director_name'] = $this->input->post("owner_director_name");
                    if ($this->input->post("applicant_type") != 'Proprietorship') {
                        $form_data['org_name'] = $this->input->post("org_name");
                        $form_data['date_of_deed'] = $this->input->post("date_of_deed");
                        $form_data['date_of_validity'] = $this->input->post("date_of_validity");
                    } else {
                        $form_data['org_name'] = '';
                    }

                    $form_data['applicant_name'] = '';
                    $form_data['father_husband_name'] = '';
                    $form_data['applicant_gender'] = '';
                    $form_data['date_of_birth'] = '';
                    $form_data['caste'] = '';
                    $form_data['religion'] = '';
                    $form_data['nationality'] = '';
                }
                // $res_amt = calcSecurityAmtForContractors($deptt_code, $this->input->post("category"), $this->input->post("caste"), $category_of_regs);
                // $res_amt_arr = explode('|', $res_amt);
                // $form_data['office_payments.security_deposit'] = $res_amt_arr[0];
                // $form_data['office_payments.registration_fees'] = $res_amt_arr[1];

                $form_data['office_payments.security_deposit'] = 0;
                $form_data['office_payments.registration_fees'] = 0;
                if (strlen($objId)) {
                    $newArray = array_combine(
                        array_map(function ($key) {
                            return 'form_data.' . $key;
                        }, array_keys($form_data)),
                        $form_data
                    );
                    $newArray['service_data.department_name'] = $deptt_name;
                    $newArray['service_data.service_id'] = $serviceId;
                    $this->employment_model->update_where(['_id' => new ObjectId($objId)], $newArray);
                    $obj_id = $objId;
                    $this->session->set_flashdata('success', 'Personal Details has been successfully saved.');
                    redirect('spservices/renewal-of-contractors/address-section/' . $obj_id);
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong');
                    redirect('spservices/renewal-of-contractors');
                }
            }
        }
    }

    public function address($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
            "districts" => $this->district_model->get_rows(),
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else  

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/address_details_renewal', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_address_details()
    {
        $objId = $this->input->post("obj_id");
        $applicant_type = $this->input->post("applicant_type");

        if ($applicant_type == 'Individual') {
            $this->form_validation->set_rules("vill_town_city", "Village/Town/City", "required|max_length[50]");
            $this->form_validation->set_rules("post_office", "Post Office", "required|max_length[50]");
            $this->form_validation->set_rules("pol_station", "Police Station", "required|max_length[50]");
            // $this->form_validation->set_rules("district", "District", "required|max_length[50]");
            $this->form_validation->set_rules("pin_code", "Pin code", "required|numeric|exact_length[6]");

            $this->form_validation->set_rules("p_vill_town_city", "Village/Town/City", "required|max_length[50]");
            $this->form_validation->set_rules("p_post_office", "Post Office", "required|max_length[50]");
            $this->form_validation->set_rules("p_pol_station", "Police Station", "required|max_length[50]");
            //$this->form_validation->set_rules("p_district", "District", "required|max_length[50]");
            $this->form_validation->set_rules("p_pin_code", "Pin code", "required|numeric|exact_length[6]");
        }
        if ($applicant_type == 'Proprietorship' || $applicant_type == 'Partnership firm') {
            $this->form_validation->set_rules("vill_town_city", "Village/Town/City", "required|max_length[50]");
            $this->form_validation->set_rules("post_office", "Post Office", "required|max_length[50]");
            $this->form_validation->set_rules("pol_station", "Police Station", "required|max_length[50]");
            //$this->form_validation->set_rules("district", "District", "required|max_length[50]");
            $this->form_validation->set_rules("pin_code", "Pin code", "required|numeric|exact_length[6]");
        }
        if ($applicant_type == 'Company' || $applicant_type == 'Partnership firm') {
            $this->form_validation->set_rules("vill_town_city_ro", "Village/Town/City", "required|max_length[50]");
            $this->form_validation->set_rules("post_office_ro", "Post Office", "required|max_length[50]");
            $this->form_validation->set_rules("pol_station_ro", "Police Station", "required|max_length[50]");
            //$this->form_validation->set_rules("district_ro", "District", "required|max_length[50]");
            $this->form_validation->set_rules("pin_code_ro", "Pin code", "required|numeric|exact_length[6]");

            $this->form_validation->set_rules("vill_town_city_ownership[]", " ", "required|max_length[50]");
            $this->form_validation->set_rules("post_office_ownership[]", " ", "required|max_length[50]");
            $this->form_validation->set_rules("police_station_ownership[]", " ", "required|max_length[50]");
            $this->form_validation->set_rules("district_ownership[]", " ", "required|max_length[50]");
            $this->form_validation->set_rules("pin_code_ownership[]", " ", "required|numeric|exact_length[6]");
        }
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->address($obj_id);
        } else {
            if ($this->input->post("applicant_type") == 'Individual') {
                $data = array(
                    "form_data.communication_address.house_ward_no" => $this->input->post("house_ward_no"),
                    "form_data.communication_address.lane_road_loc" => $this->input->post("lane_road_loc"),
                    "form_data.communication_address.vill_town_city" => $this->input->post("vill_town_city"),
                    "form_data.communication_address.post_office" => $this->input->post("post_office"),
                    "form_data.communication_address.pol_station" => $this->input->post("pol_station"),
                    "form_data.communication_address.district" => $this->input->post("district"),
                    "form_data.communication_address.pin_code" => $this->input->post("pin_code"),
                    "form_data.permanent_address.p_house_ward_no" => $this->input->post("p_house_ward_no"),
                    "form_data.permanent_address.p_lane_road_loc" => $this->input->post("p_lane_road_loc"),
                    "form_data.permanent_address.p_vill_town_city" => $this->input->post("p_vill_town_city"),
                    "form_data.permanent_address.p_post_office" => $this->input->post("p_post_office"),
                    "form_data.permanent_address.p_pol_station" => $this->input->post("p_pol_station"),
                    "form_data.permanent_address.p_district" => $this->input->post("p_district"),
                    "form_data.permanent_address.p_pin_code" => $this->input->post("p_pin_code"),
                );

                $unset_data = 'form_data.authorised_signatory_address';
                $this->unsetdata($objId, $unset_data);
                $unset_data = 'form_data.regd_address';
                $this->unsetdata($objId, $unset_data);
                $unset_data = 'form_data.addresses_of_all_owners';
                $this->unsetdata($objId, $unset_data);
            } else {
                $district_ownership_count = count($this->input->post('district_ownership'));
                for ($i = 0; $i < $district_ownership_count; $i++) {
                    $district_ownership_arr[] = [
                        "house_no_ownership" => $this->input->post('house_no_ownership')[$i],
                        "lane_road_ownership" => $this->input->post('lane_road_ownership')[$i],
                        "vill_town_city_ownership" => $this->input->post('vill_town_city_ownership')[$i],
                        "post_office_ownership" => $this->input->post('post_office_ownership')[$i],
                        "police_station_ownership" => $this->input->post('police_station_ownership')[$i],
                        "district_ownership" => $this->input->post('district_ownership')[$i],
                        "pin_code_ownership" => $this->input->post('pin_code_ownership')[$i],
                    ];
                }
                $data = array(
                    "form_data.authorised_signatory_address.house_ward_no" => $this->input->post("house_ward_no"),
                    "form_data.authorised_signatory_address.lane_road_loc" => $this->input->post("lane_road_loc"),
                    "form_data.authorised_signatory_address.vill_town_city" => $this->input->post("vill_town_city"),
                    "form_data.authorised_signatory_address.post_office" => $this->input->post("post_office"),
                    "form_data.authorised_signatory_address.pol_station" => $this->input->post("pol_station"),
                    "form_data.authorised_signatory_address.district" => $this->input->post("district"),
                    "form_data.authorised_signatory_address.pin_code" => $this->input->post("pin_code"),
                    "form_data.regd_address.house_ward_no_ro" => $this->input->post("house_ward_no_ro"),
                    "form_data.regd_address.lane_road_loc_ro" => $this->input->post("lane_road_loc_ro"),
                    "form_data.regd_address.vill_town_city_ro" => $this->input->post("vill_town_city_ro"),
                    "form_data.regd_address.post_office_ro" => $this->input->post("post_office_ro"),
                    "form_data.regd_address.pol_station_ro" => $this->input->post("pol_station_ro"),
                    "form_data.regd_address.district_ro" => $this->input->post("district_ro"),
                    "form_data.regd_address.pin_code_ro" => $this->input->post("pin_code_ro"),
                    'form_data.addresses_of_all_owners' => $district_ownership_arr,
                );
            }
            if (strlen($objId)) {
                $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
            }
            $this->session->set_flashdata('success', 'Address Details has been successfully saved.');
            redirect('spservices/renewal-of-contractors/work-section/' . $objId);
        }
    }

    public function work($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
            "districts" => $this->district_model->get_rows(),
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else 

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/work_details_renewal', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_work_details()
    {
        $objId = $this->input->post("obj_id");
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        $category = $dbRow->form_data->category;
        $deptt_code = $dbRow->form_data->deptt_name;
        $category_of_regs = $dbRow->form_data->category_of_regs;

        $this->form_validation->set_rules("work_position[]", " ", "required|max_length[100]");
        $this->form_validation->set_rules("emp_name[]", " ", "required|max_length[50]");
        $this->form_validation->set_rules("emp_qualification[]", " ", "required|max_length[50]");
        $this->form_validation->set_rules("total_exp[]", " ", "required|alpha_numeric_spaces|max_length[30]");
        $this->form_validation->set_rules("with_contractor_exp[]", " ", "required|alpha_numeric_spaces|max_length[30]");

        if ($deptt_code === "PWDNH") {
            $this->form_validation->set_rules("work_value_remaining[]", " ", "regex_match[/^\d{1,3}%$/]");
            $this->form_validation->set_rules("work_unit[]", " ", "alpha_numeric");
        }

        if ($deptt_code != 'WRD') {
            if (($category_of_regs != 'Unemployed Graduate Engineer') && ($category_of_regs != 'Unemployed Diploma Engineer')) {
                $this->form_validation->set_rules("project_name[]", " ", "required|max_length[100]");
                $this->form_validation->set_rules("employer[]", " ", "required|max_length[100]");
                $this->form_validation->set_rules("work_description[]", " ", "required|max_length[100]");
                $this->form_validation->set_rules("con_wo_no[]", " ", "required|max_length[75]");
                $this->form_validation->set_rules("value_contract_p[]", " ", "required|numeric|max_length[15]");
                $this->form_validation->set_rules("wo_date_p[]", " ", "required|regex_match[/\d{2}-\d{2}-\d{4}/]");
                $this->form_validation->set_rules("st_completion_date_p[]", " ", "required|regex_match[/\d{2}-\d{2}-\d{4}/]");
                $this->form_validation->set_rules("actual_completion_date[]", " ", "required|regex_match[/\d{2}-\d{2}-\d{4}/]");
                $this->form_validation->set_rules("remarks_reasons[]", " ", "max_length[100]");
            }
        }

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->work($obj_id);
        } else {
            $work_count = count($this->input->post('desc_work'));
            for ($i = 0; $i < $work_count; $i++) {
                $ongoing_works_arr[] = [
                    "desc_work" => $this->input->post('desc_work')[$i],
                    "place_state" => $this->input->post('place_state')[$i],
                    "contract_no_date" => $this->input->post('contract_no_date')[$i],
                    "employer_name" => $this->input->post('employer_name')[$i],
                    "value_contract" => $this->input->post('value_contract')[$i],
                    "wo_date" => $this->input->post('wo_date')[$i],
                    "st_completion_date" => $this->input->post('st_completion_date')[$i],
                    "work_value_remaining" => $this->input->post('work_value_remaining')[$i],
                    "ant_date_completion" => $this->input->post('ant_date_completion')[$i],
                ];
            }

            $work_executed_count = count($this->input->post('project_name'));
            $total_work_value = 0;
            for ($i = 0; $i < $work_executed_count; $i++) {
                $executed_works_arr[] = [
                    "project_name" => $this->input->post('project_name')[$i],
                    "employer" => $this->input->post('employer')[$i],
                    "work_description" => $this->input->post('work_description')[$i],
                    "con_wo_no" => $this->input->post('con_wo_no')[$i],
                    "value_contract_p" => $this->input->post('value_contract_p')[$i],
                    "wo_date_p" => $this->input->post('wo_date_p')[$i],
                    "st_completion_date_p" => $this->input->post('st_completion_date_p')[$i],
                    "actual_completion_date" => $this->input->post('actual_completion_date')[$i],
                    "remarks_reasons" => $this->input->post('remarks_reasons')[$i],
                ];
                $total_work_value = $total_work_value + (int)$this->input->post('value_contract_p')[$i];
            }

            if (($category_of_regs != 'Unemployed Graduate Engineer') && ($category_of_regs != 'Unemployed Diploma Engineer')) {
                $res_amt = calcMinExecutedWorkValue($deptt_code, $category, $total_work_value);
                $res_amt_arr = explode('|', $res_amt);
                $amt = $res_amt_arr[0];
                $flag = $res_amt_arr[1];
            } else {
                $flag = true;
            }

            if (!$flag) {
                $this->session->set_flashdata('error_valid', 'Minimum work value is Rs.' . $amt . ' !');
                $obj_id = strlen($objId) ? $objId : null;
                $this->work($obj_id);
            } else {

                $quantities_of_works_count = count($this->input->post('work_item'));
                for ($i = 0; $i < $quantities_of_works_count; $i++) {
                    $quantities_of_works_arr[] = [
                        "work_item" => $this->input->post('work_item')[$i],
                        "work_unit" => $this->input->post('work_unit')[$i],
                        "fin_years" => $this->input->post('fin_years')[$i],
                    ];
                }
                $key_personnel_count = count($this->input->post('work_position'));
                for ($i = 0; $i < $key_personnel_count; $i++) {
                    $key_personnel_arr[] = [
                        "work_position" => $this->input->post('work_position')[$i],
                        "emp_name" => $this->input->post('emp_name')[$i],
                        "emp_qualification" => $this->input->post('emp_qualification')[$i],
                        "total_exp" => $this->input->post('total_exp')[$i],
                        "with_contractor_exp" => $this->input->post('with_contractor_exp')[$i],
                    ];
                }

                $data = array(
                    'form_data.ongoing_works' => $ongoing_works_arr,
                    'form_data.works_executed' => $executed_works_arr,
                    'form_data.quantities_of_works_executed' => $quantities_of_works_arr,
                    'form_data.key_personnel' => $key_personnel_arr,
                );

                $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
                $this->session->set_flashdata('success', 'Work Details has been successfully saved.');
                redirect('spservices/renewal-of-contractors/machinery-section/' . $objId);
            }
        }
    }

    public function machinery($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else 

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/machinery_details_renewal', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_machinery_details()
    {
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules("obj_id", " ", "required");

        // $this->form_validation->set_rules("machinery[]", " ", "required|max_length[100]");
        // $this->form_validation->set_rules("numbers_owned[]", " ", "required|numeric|max_length[50]");
        // $this->form_validation->set_rules("avg_age_condition[]", " ", "required|max_length[50]");
        // $this->form_validation->set_rules("equipment_reg_no[]", " ", "max_length[50]");
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->machinery($obj_id);
        } else {
            $machinery_count = count($this->input->post('machinery'));
            for ($i = 0; $i < $machinery_count; $i++) {
                $machinery_arr[] = [
                    "machinery" => $this->input->post('machinery')[$i],
                    "numbers_owned" => $this->input->post('numbers_owned')[$i],
                    "avg_age_condition" => $this->input->post('avg_age_condition')[$i],
                    "equipment_reg_no" => $this->input->post('equipment_reg_no')[$i],
                ];
            }
            $data = array(
                'form_data.machineries_owned' => $machinery_arr,
            );
            $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success', 'Machinery Details has been successfully saved.');
            redirect('spservices/renewal-of-contractors/turnover-section/' . $objId);
        }
    }

    public function turnover($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else 

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/turnover_details_renewal', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_turnover_details()
    {
        $objId = $this->input->post("obj_id");
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        $deptt_code = $dbRow->form_data->deptt_name;
        $category = $dbRow->form_data->category;
        $category_of_regs = $dbRow->form_data->category_of_regs;
        if (($category_of_regs != 'Unemployed Graduate Engineer') && ($category_of_regs != 'Unemployed Diploma Engineer')) {
            $this->form_validation->set_rules("fin_year_turnover[]", " ", "required|max_length[100]");
            $this->form_validation->set_rules("turnover[]", " ", "required|numeric|max_length[10]");
        } else {
            $this->form_validation->set_rules("fin_year_turnover[]", " ", "max_length[100]");
            $this->form_validation->set_rules("turnover[]", " ", "numeric|max_length[10]");
        }
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->turnover($obj_id);
            //var_dump(validation_errors());

        } else {
            $turnover_count = count($this->input->post('fin_year_turnover'));
            $flag = true;
            $amt = '';
            $total_turnover = 0;
            if (($category_of_regs != 'Unemployed Graduate Engineer') && ($category_of_regs != 'Unemployed Diploma Engineer')) {
                for ($i = 0; $i < $turnover_count; $i++) {
                    $turnover_arr[] = [
                        "fin_year_turnover" => $this->input->post('fin_year_turnover')[$i],
                        "turnover" => $this->input->post('turnover')[$i],
                    ];
                    $total_turnover = $total_turnover + (int)$this->input->post('turnover')[$i];
                    if ($deptt_code == 'PHED') {
                        $curr_turnover = 0;
                        $curr_turnover = (int)$this->input->post('turnover')[$i];

                        if ($category == 'Class-1(A)' && $curr_turnover < 20000000) {
                            $flag = false;
                            $amt = '20000000';
                            break;
                        } else if ($category == 'Class-1(B)' && $curr_turnover < 10000000) {
                            $flag = false;
                            $amt = '10000000';
                            break;
                        } else if ($category == 'Class-1(C)' && $curr_turnover < 5000000) {
                            $flag = false;
                            $amt = '5000000';
                            break;
                        } else if ($category == 'Class-II' && $curr_turnover < 1000000) {
                            $flag = false;
                            $amt = '1000000';
                            break;
                        }
                    }
                }
                if ($deptt_code == 'PWDB' || $deptt_code == 'PWDNH') {
                    $total_turnover = $total_turnover / $turnover_count;
                    if ($category == 'Class-1(A)' && $total_turnover < 20000000) {
                        $flag = false;
                        $amt = '20000000';
                    } else if ($category == 'Class-1(B)' && $total_turnover < 10000000) {
                        $flag = false;
                        $amt = '10000000';
                    } else if ($category == 'Class-1(C)' && $total_turnover < 5000000) {
                        $flag = false;
                        $amt = '5000000';
                    } else if ($category == 'Class-II' && $total_turnover < 1000000) {
                        $flag = false;
                        $amt = '1000000';
                    }
                }
            }

            if ($flag === false) {
                if (($category_of_regs != 'Unemployed Graduate Engineer') && ($category_of_regs != 'Unemployed Diploma Engineer')) {
                    $this->session->set_flashdata('error_valid', 'Annual Financial Turnover is less than Rs.' . $amt . ' !');
                    $obj_id = strlen($objId) ? $objId : null;
                    $this->turnover($obj_id);
                }
            } else {
                $data = array(
                    'form_data.financial_turnover' => $turnover_arr,
                );
                $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
                $this->session->set_flashdata('success', 'Turnover Details has been successfully saved.');
                redirect('spservices/renewal-of-contractors/history-section/' . $objId);
            }
        }
    }

    public function history($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else 

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/history_details_renewal', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_history_details()
    {
        $objId = $this->input->post("obj_id");
        $this->form_validation->set_rules("employer_dispute[]", "Employer", "xss_clean|max_length[50]|regex_match[/^[a-z\.\/\s]+$/i]");
        $this->form_validation->set_rules("cause_of_dispute[]", "Cause of dispute", "xss_clean|max_length[100]|regex_match[/^[a-z\d\.\/\s]+$/i]");
        $this->form_validation->set_rules("status[]", "Status", "xss_clean|max_length[50]|regex_match[/^[a-z\.\/\s]+$/i]");
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($objId) ? $objId : null;
            $this->history($obj_id);
        } else {
            $history_count = count($this->input->post('employer_dispute'));
            for ($i = 0; $i < $history_count; $i++) {
                $history_arr[] = [
                    "employer_dispute" => $this->input->post('employer_dispute')[$i],
                    "cause_of_dispute" => $this->input->post('cause_of_dispute')[$i],
                    "status" => $this->input->post('status')[$i],
                ];
            }
            $data = array(
                'form_data.litigation_history' => $history_arr,
            );
            $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
            $this->session->set_flashdata('success', 'History has been successfully saved.');
            redirect('spservices/renewal-of-contractors/attachments-section/' . $objId);
        }
    }

    public function attachments($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/attachments_up_renewal', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_enclosures()
    {
        $objId = $this->input->post("obj_id");

        if (strlen($this->input->post("photograph_temp")) > 0) {
            $photographFile = movedigilockerfile($this->input->post('photograph_temp'));
            $photograph = $photographFile["upload_status"] ? $photographFile["uploaded_path"] : null;
        } else {
            $photographFile = cifileupload("photograph");
            $photograph = $photographFile["upload_status"] ? $photographFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("copy_pan_card_temp")) > 0) {
            $pancardFile = movedigilockerfile($this->input->post('copy_pan_card_temp'));
            $panCard = $pancardFile["upload_status"] ? $pancardFile["uploaded_path"] : null;
        } else {
            $pancardFile = cifileupload("copy_pan_card");
            $panCard = $pancardFile["upload_status"] ? $pancardFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("caste_cert_temp")) > 0) {
            $casteCertFile = movedigilockerfile($this->input->post('caste_cert_temp'));
            $casteCert = $casteCertFile["upload_status"] ? $casteCertFile["uploaded_path"] : null;
        } else {
            $casteCertFile = cifileupload("caste_cert");
            $casteCert = $casteCertFile["upload_status"] ? $casteCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("pvr_passport_temp")) > 0) {
            $pvrPassportFile = movedigilockerfile($this->input->post('pvr_passport_temp'));
            $pvrPassport = $pvrPassportFile["upload_status"] ? $pvrPassportFile["uploaded_path"] : null;
        } else {
            $pvrPassportFile = cifileupload("pvr_passport");
            $pvrPassport = $pvrPassportFile["upload_status"] ? $pvrPassportFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("gst_reg_cert_temp")) > 0) {
            $gstRegCertFile = movedigilockerfile($this->input->post('gst_reg_cert_temp'));
            $gstRegCert = $gstRegCertFile["upload_status"] ? $gstRegCertFile["uploaded_path"] : null;
        } else {
            $gstRegCertFile = cifileupload("gst_reg_cert");
            $gstRegCert = $gstRegCertFile["upload_status"] ? $gstRegCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("bank_solvency_cert_temp")) > 0) {
            $bankSolvencyCertFile = movedigilockerfile($this->input->post('bank_solvency_cert_temp'));
            $bankSolvencyCert = $bankSolvencyCertFile["upload_status"] ? $bankSolvencyCertFile["uploaded_path"] : null;
        } else {
            $bankSolvencyCertFile = cifileupload("bank_solvency_cert");
            $bankSolvencyCert = $bankSolvencyCertFile["upload_status"] ? $bankSolvencyCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("labour_license_temp")) > 0) {
            $labourLicenseFile = movedigilockerfile($this->input->post('labour_license_temp'));
            $labourLicense = $labourLicenseFile["upload_status"] ? $labourLicenseFile["uploaded_path"] : null;
        } else {
            $labourLicenseFile = cifileupload("labour_license");
            $labourLicense = $labourLicenseFile["upload_status"] ? $labourLicenseFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("key_personnel_temp")) > 0) {
            $keyPersonnelFile = movedigilockerfile($this->input->post('key_personnel_temp'));
            $keyPersonnel = $keyPersonnelFile["upload_status"] ? $keyPersonnelFile["uploaded_path"] : null;
        } else {
            $keyPersonnelFile = cifileupload("key_personnel");
            $keyPersonnel = $keyPersonnelFile["upload_status"] ? $keyPersonnelFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("turnover_cert_temp")) > 0) {
            $turnoverCertFile = movedigilockerfile($this->input->post('turnover_cert_temp'));
            $turnoverCert = $turnoverCertFile["upload_status"] ? $turnoverCertFile["uploaded_path"] : null;
        } else {
            $turnoverCertFile = cifileupload("turnover_cert");
            $turnoverCert = $turnoverCertFile["upload_status"] ? $turnoverCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("machinery_details_temp")) > 0) {
            $machineryDetailsFile = movedigilockerfile($this->input->post('machinery_details_temp'));
            $machineryDetails = $machineryDetailsFile["upload_status"] ? $machineryDetailsFile["uploaded_path"] : null;
        } else {
            $machineryDetailsFile = cifileupload("machinery_details");
            $machineryDetails = $machineryDetailsFile["upload_status"] ? $machineryDetailsFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("tax_clearance_cert_temp")) > 0) {
            $taxClearanceCertFile = movedigilockerfile($this->input->post('tax_clearance_cert_temp'));
            $taxClearanceCert = $taxClearanceCertFile["upload_status"] ? $taxClearanceCertFile["uploaded_path"] : null;
        } else {
            $taxClearanceCertFile = cifileupload("tax_clearance_cert");
            $taxClearanceCert = $taxClearanceCertFile["upload_status"] ? $taxClearanceCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("work_completion_cert_temp")) > 0) {
            $workCompletionCertFile = movedigilockerfile($this->input->post('work_completion_cert_temp'));
            $workCompletionCert = $workCompletionCertFile["upload_status"] ? $workCompletionCertFile["uploaded_path"] : null;
        } else {
            $workCompletionCertFile = cifileupload("work_completion_cert");
            $workCompletionCert = $workCompletionCertFile["upload_status"] ? $workCompletionCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("quantities_work_cert_temp")) > 0) {
            $quantitiesWorkCertFile = movedigilockerfile($this->input->post('quantities_work_cert_temp'));
            $quantitiesWorkCert = $quantitiesWorkCertFile["upload_status"] ? $quantitiesWorkCertFile["uploaded_path"] : null;
        } else {
            $quantitiesWorkCertFile = cifileupload("quantities_work_cert");
            $quantitiesWorkCert = $quantitiesWorkCertFile["upload_status"] ? $quantitiesWorkCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("proof_of_address_temp")) > 0) {
            $proofOfAddressFile = movedigilockerfile($this->input->post('proof_of_address_temp'));
            $proofOfAddress = $proofOfAddressFile["upload_status"] ? $proofOfAddressFile["uploaded_path"] : null;
        } else {
            $proofOfAddressFile = cifileupload("proof_of_address");
            $proofOfAddress = $proofOfAddressFile["upload_status"] ? $proofOfAddressFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("power_attorney_temp")) > 0) {
            $powerOfAttorneyFile = movedigilockerfile($this->input->post('power_attorney_temp'));
            $powerOfAttorney = $powerOfAttorneyFile["upload_status"] ? $powerOfAttorneyFile["uploaded_path"] : null;
        } else {
            $powerOfAttorneyFile = cifileupload("power_attorney");
            $powerOfAttorney = $powerOfAttorneyFile["upload_status"] ? $powerOfAttorneyFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("firm_reg_cert_temp")) > 0) {
            $firmRegCertFile = movedigilockerfile($this->input->post('firm_reg_cert_temp'));
            $firmRegCert = $firmRegCertFile["upload_status"] ? $firmRegCertFile["uploaded_path"] : null;
        } else {
            $firmRegCertFile = cifileupload("firm_reg_cert");
            $firmRegCert = $firmRegCertFile["upload_status"] ? $firmRegCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("deed_maaa_cert_temp")) > 0) {
            $deedMaaaCertFile = movedigilockerfile($this->input->post('deed_maaa_cert_temp'));
            $deedMaaaCert = $deedMaaaCertFile["upload_status"] ? $deedMaaaCertFile["uploaded_path"] : null;
        } else {
            $deedMaaaCertFile = cifileupload("deed_maaa_cert");
            $deedMaaaCert = $deedMaaaCertFile["upload_status"] ? $deedMaaaCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("emp_provident_fund_temp")) > 0) {
            $empProvidentFundFile = movedigilockerfile($this->input->post('emp_provident_fund_temp'));
            $empProvidentFund = $empProvidentFundFile["upload_status"] ? $empProvidentFundFile["uploaded_path"] : null;
        } else {
            $empProvidentFundFile = cifileupload("emp_provident_fund");
            $empProvidentFund = $empProvidentFundFile["upload_status"] ? $empProvidentFundFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("affidavit_cert_temp")) > 0) {
            $affidavitCertFile = movedigilockerfile($this->input->post('affidavit_cert_temp'));
            $affidavitCert = $affidavitCertFile["upload_status"] ? $affidavitCertFile["uploaded_path"] : null;
        } else {
            $affidavitCertFile = cifileupload("affidavit_cert");
            $affidavitCert = $affidavitCertFile["upload_status"] ? $affidavitCertFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("regs_other_deptt_temp")) > 0) {
            $regsOtherDepttFile = movedigilockerfile($this->input->post('regs_other_deptt_temp'));
            $regsOtherDeptt = $regsOtherDepttFile["upload_status"] ? $regsOtherDepttFile["uploaded_path"] : null;
        } else {
            $regsOtherDepttFile = cifileupload("regs_other_deptt");
            $regsOtherDeptt = $regsOtherDepttFile["upload_status"] ? $regsOtherDepttFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("affidavit_unemployment_temp")) > 0) {
            $affidavitUnemploymentFile = movedigilockerfile($this->input->post('affidavit_unemployment_temp'));
            $affidavitUnemployment = $affidavitUnemploymentFile["upload_status"] ? $affidavitUnemploymentFile["uploaded_path"] : null;
        } else {
            $affidavitUnemploymentFile = cifileupload("affidavit_unemployment");
            $affidavitUnemployment = $affidavitUnemploymentFile["upload_status"] ? $affidavitUnemploymentFile["uploaded_path"] : null;
        }

        if (strlen($this->input->post("last_registration_copy")) > 0) {
            $last_registration_copy = movedigilockerfile($this->input->post('last_registration_copy'));
            $lastRegistration = $last_registration_copy["upload_status"] ? $last_registration_copy["uploaded_path"] : null;
        } else {
            $last_registration_copy = cifileupload("any_other_docs");
            $lastRegistration = $last_registration_copy["upload_status"] ? $last_registration_copy["uploaded_path"] : null;
        }

        if (strlen($this->input->post("any_other_docs_temp")) > 0) {
            $anyOtherDocsFile = movedigilockerfile($this->input->post('any_other_docs_temp'));
            $anyOtherDocs = $anyOtherDocsFile["upload_status"] ? $anyOtherDocsFile["uploaded_path"] : null;
        } else {
            $anyOtherDocsFile = cifileupload("any_other_docs");
            $anyOtherDocs = $anyOtherDocsFile["upload_status"] ? $anyOtherDocsFile["uploaded_path"] : null;
        }

        $uploadedFiles = array(
            "photograph_old" => strlen($photograph) ? $photograph : $this->input->post("photograph_old"),
            "copy_pan_card_old" => strlen($panCard) ? $panCard : $this->input->post("copy_pan_card_old"),
            "caste_cert_old" => strlen($casteCert) ? $casteCert : $this->input->post("caste_cert_old"),
            "pvr_passport_old" => strlen($pvrPassport) ? $pvrPassport : $this->input->post("pvr_passport_old"),
            "gst_reg_cert_old" => strlen($gstRegCert) ? $gstRegCert : $this->input->post("gst_reg_cert_old"),
            "bank_solvency_cert_old" => strlen($bankSolvencyCert) ? $bankSolvencyCert : $this->input->post("bank_solvency_cert_old"),
            "labour_license_old" => strlen($labourLicense) ? $labourLicense : $this->input->post("labour_license_old"),
            "key_personnel_old" => strlen($keyPersonnel) ? $keyPersonnel : $this->input->post("key_personnel_old"),
            "turnover_cert_old" => strlen($turnoverCert) ? $turnoverCert : $this->input->post("turnover_cert_old"),
            "machinery_details_old" => strlen($machineryDetails) ? $machineryDetails : $this->input->post("machinery_details_old"),
            "tax_clearance_cert_old" => strlen($taxClearanceCert) ? $taxClearanceCert : $this->input->post("tax_clearance_cert_old"),
            "work_completion_cert_old" => strlen($workCompletionCert) ? $workCompletionCert : $this->input->post("work_completion_cert_old"),
            "quantities_work_cert_old" => strlen($quantitiesWorkCert) ? $quantitiesWorkCert : $this->input->post("quantities_work_cert_old"),
            "proof_of_address_old" => strlen($proofOfAddress) ? $proofOfAddress : $this->input->post("proof_of_address_old"),
            "power_attorney_old" => strlen($powerOfAttorney) ? $powerOfAttorney : $this->input->post("power_attorney_old"),
            "firm_reg_cert_old" => strlen($firmRegCert) ? $firmRegCert : $this->input->post("firm_reg_cert_old"),
            "deed_maaa_cert_old" => strlen($deedMaaaCert) ? $deedMaaaCert : $this->input->post("deed_maaa_cert_old"),
            "emp_provident_fund_old" => strlen($empProvidentFund) ? $empProvidentFund : $this->input->post("emp_provident_fund_old"),
            "affidavit_cert_old" => strlen($affidavitCert) ? $affidavitCert : $this->input->post("affidavit_cert_old"),
            "regs_other_deptt_old" => strlen($regsOtherDeptt) ? $regsOtherDeptt : $this->input->post("regs_other_deptt_old"),
            "affidavit_unemployment_old" => strlen($affidavitUnemployment) ? $affidavitUnemployment : $this->input->post("affidavit_unemployment_old"),
            "last_registration_copy_old" => strlen($lastRegistration) ? $lastRegistration : $this->input->post("last_registration_copy_old"),
            "any_other_docs_old" => strlen($anyOtherDocs) ? $anyOtherDocs : $this->input->post("any_other_docs_old")
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        $data = array(
            'form_data.declaration' => $this->input->post("declaration"),
            'form_data.enclosures.photograph' => strlen($photograph) ? $photograph : $this->input->post("photograph_old"),
            'form_data.enclosures.copy_pan_card' => strlen($panCard) ? $panCard : $this->input->post("copy_pan_card_old"),
            'form_data.enclosures.caste_cert' => strlen($casteCert) ? $casteCert : $this->input->post("caste_cert_old"),
            'form_data.enclosures.pvr_passport' => strlen($pvrPassport) ? $pvrPassport : $this->input->post("pvr_passport_old"),
            'form_data.enclosures.gst_reg_cert' => strlen($gstRegCert) ? $gstRegCert : $this->input->post("gst_reg_cert_old"),
            'form_data.enclosures.bank_solvency_cert' => strlen($bankSolvencyCert) ? $bankSolvencyCert : $this->input->post("bank_solvency_cert_old"),
            'form_data.enclosures.labour_license' => strlen($labourLicense) ? $labourLicense : $this->input->post("labour_license_old"),
            'form_data.enclosures.key_personnel' => strlen($keyPersonnel) ? $keyPersonnel : $this->input->post("key_personnel_old"),
            'form_data.enclosures.turnover_cert' => strlen($turnoverCert) ? $turnoverCert : $this->input->post("turnover_cert_old"),
            'form_data.enclosures.machinery_details' => strlen($machineryDetails) ? $machineryDetails : $this->input->post("machinery_details_old"),
            'form_data.enclosures.tax_clearance_cert' => strlen($taxClearanceCert) ? $taxClearanceCert : $this->input->post("tax_clearance_cert_old"),
            'form_data.enclosures.work_completion_cert' => strlen($workCompletionCert) ? $workCompletionCert : $this->input->post("work_completion_cert_old"),
            'form_data.enclosures.quantities_work_cert' => strlen($quantitiesWorkCert) ? $quantitiesWorkCert : $this->input->post("quantities_work_cert_old"),
            'form_data.enclosures.proof_of_address' => strlen($proofOfAddress) ? $proofOfAddress : $this->input->post("proof_of_address_old"),
            'form_data.enclosures.power_attorney' => strlen($powerOfAttorney) ? $powerOfAttorney : $this->input->post("power_attorney_old"),
            'form_data.enclosures.firm_reg_cert' => strlen($firmRegCert) ? $firmRegCert : $this->input->post("firm_reg_cert_old"),
            'form_data.enclosures.deed_maaa_cert' => strlen($deedMaaaCert) ? $deedMaaaCert : $this->input->post("deed_maaa_cert_old"),
            "form_data.enclosures.emp_provident_fund" => strlen($empProvidentFund) ? $empProvidentFund : $this->input->post("emp_provident_fund_old"),
            "form_data.enclosures.affidavit_cert" => strlen($affidavitCert) ? $affidavitCert : $this->input->post("affidavit_cert_old"),
            "form_data.enclosures.regs_other_deptt" => strlen($regsOtherDeptt) ? $regsOtherDeptt : $this->input->post("regs_other_deptt_old"),
            "form_data.enclosures.affidavit_unemployment" => strlen($affidavitUnemployment) ? $affidavitUnemployment : $this->input->post("affidavit_unemployment_old"),
            "form_data.enclosures.last_registration_copy" => strlen($lastRegistration) ? $lastRegistration : $this->input->post("last_registration_copy_old"),
            "form_data.enclosures.any_other_docs" => strlen($anyOtherDocs) ? $anyOtherDocs : $this->input->post("any_other_docs_old")
        );
        $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data);
        redirect('spservices/renewal-of-contractors/preview/' . $objId);
    }

    function preview($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName,
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));
        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
            $data["form_status"] = "EDIT";
        } else {
            $data["dbrow"] = false;
            $data["form_status"] = "DRAFT";
        } //End of if else

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/renewal_preview', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit_query($objId = null)
    {
        if ($objId) {
            $dbRow = $this->employment_model->get_by_doc_id($objId);
            if (count((array)$dbRow)) {
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Query submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Query submitted",
                    "remarks" => "Query submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
                $data = array(
                    "service_data.appl_status" => "QA",
                    'processing_history' => $processing_history,
                    'status' => "QUERY_ANSWERED"
                );
                $this->employment_model->update($objId, $data);
                $this->session->set_flashdata('success', 'Query has been successfully updated');
                $this->my_transactions();
            }
        } else {
            $this->my_transactions();
        }
    }

    public function finalsubmition($objId = null)
    {
        if ($objId) {
            $dbRow = $this->employment_model->get_by_doc_id($objId);
            if ($dbRow->service_data->appl_status != "payment_initiated") {
                $this->my_transactions();
            }
            if (!empty($dbRow->form_data->pfc_payment_response->GRN) && ($dbRow->form_data->pfc_payment_response->STATUS === "Y")) {
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "action_taken" => "Application Submission",
                    "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );
                $deptt_code = $dbRow->form_data->deptt_name;
                $category = $dbRow->form_data->category;
                $serviceId = $this->get_serviceID($deptt_code, $category);
                $userFilter = array('user_services.service_code' => $serviceId, 'dept_info.dept_code' => $deptt_code, 'user_levels.level_no' => 1, 'status' => 1);
                $userRows = $this->users_model->get_rows($userFilter);
                pre($userFilter);
                if (!$userRows) {
                    $this->session->set_flashdata('error', 'Not submitted! Please verify again.');
                    if ($this->session->role) {
                        redirect('iservices/admin/my-transactions');
                    } else {
                        redirect('iservices/transactions');
                    }
                    exit;
                }

                $current_users = array();
                foreach ($userRows as $key => $userRow) {
                    $current_user = array(
                        'login_username' => $userRow->login_username,
                        'email_id' => $userRow->email_id,
                        'mobile_number' => $userRow->mobile_number,
                        'user_level_no' => $userRow->user_levels->level_no,
                        'user_fullname' => $userRow->user_fullname,
                    );
                    $current_users[] = $current_user;
                }
                //get registration number
                $regs_no = $this->getRegsID(7, $deptt_code);

                $validity_date = getFinLastYearDateYMD();
                $rnwDate = $validity_date . "T23:59:59.000Z";
                $renewal_date = new MongoDB\BSON\UTCDateTime(strtotime($rnwDate) * 1000);
                $data_to_update = array(
                    'service_data.appl_status' => 'submitted',
                    'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    'form_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    // 'form_data.registration_no' => $regs_no,
                    // 'form_data.renewal_date' => $renewal_date,
                    'current_users' => $current_users,
                    'processing_history' => $processing_history
                );

                $this->employment_model->update($objId, $data_to_update);
                $smsData = [
                    'applicant_name' => $dbRow->form_data->applicant_name,
                    'mobile' => $dbRow->form_data->mobile,
                    'service_name' => 'Employment Registration',
                    'submission_date' => format_mongo_date(new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)),
                    'app_ref_no' => $dbRow->service_data->appl_ref_no
                ];
                //sms_provider('submission', $smsData);
                redirect('spservices/renewal-of-contractors/acknowledgement/' . $objId);
            } else {
                $this->session->set_flashdata('errmessage', 'Unable to submit application form with Ref. No: <b>' . $dbRow->service_data->appl_ref_no . '</b>, Please try again.');
                $this->my_transactions();
            }
        }
    }

    public function view($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            "serviceservice_name" => $this->serviceName
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        if ($dbRow) {
            $data["dbrow"] = $this->employment_model->get_by_doc_id($objId);
        } else {
            $data["dbrow"] = false;
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/registration_preview', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function acknowledgement($objId = null)
    {
        $data = array(
            "obj_id" => $objId,
            //"serviceservice_name" => $this->serviceName
        );
        $dbRow = $this->employment_model->get_row(array('_id' => new ObjectId($objId)));

        if ($dbRow) {
            $data["response"] = $this->employment_model->get_by_doc_id($objId);
        } else {
            $data["response"] = false;
        }

        $this->load->view('includes/frontend/header');
        $this->load->view('registration_of_contractors/ack', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function generate_certificate($objId = null)
    {
        //only to view the certificate
        $data["response"] = $this->employment_model->get_by_doc_id($objId);
        $this->load->view('registration_of_contractors/output_certificate', $data);
    }

    public function track($objId = null)
    {
        $dbRow = $this->employment_model->get_by_doc_id($objId);
        if (count((array)$dbRow)) {
            $data = array(
                //"service_data.service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            // pre($data);
            $this->load->view('includes/frontend/header');
            $this->load->view('registration_of_contractors/registrationtrack_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
        } //End of if else
    } //End of track()

    function getDepttName($deptt_code)
    {
        if ($deptt_code == 'PHED') {
            $departmentName = "Public Health Engineering Department (PHED)";
        } else if ($deptt_code == 'PWDB') {
            $departmentName = "Assam Public Works (Building) Department";
        } else if ($deptt_code == 'WRD') {
            $departmentName = "Water Resource Department";
        } else {
            $departmentName = "DEPTT. NAME NOT AVAILABLE";
        }
        return $departmentName;
    }

    public function unsetdata($objId, $data)
    {
        $this->mongo_db->command(array(
            'update' => 'sp_applications',
            'updates' => [
                array(
                    'q' => array('_id' => new ObjectId($objId)),
                    'u' => array(
                        '$unset' => array($data => ''),
                    )
                ),
            ],
        ));
    }

    function getID($length)
    {
        $refID = $this->generateID($length);
        while ($this->employment_model->get_row(["service_data.appl_ref_no" => $refID])) {
            $refID = $this->generateID($length);
        } //End of while()
        return $refID;
    } //End of getID()

    public function generateID($length)
    {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-RENCON/" . date('Y') . "/" . $number;
        return $str;
    } //End of generateID()

    function getRegsID($length, $deptt_code)
    {
        $refID = $this->generateRegsID($length, $deptt_code);
        while ($this->employment_model->get_row(["form_data.registration_no" => $refID])) {
            $refID = $this->generateRegsID($length, $deptt_code);
        }
        return $refID;
    }

    public function generateRegsID($length, $deptt_code)
    {
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "REG-CON/" . $deptt_code . "/" . date('Y') . "/" . $number;
        return $str;
    }

    private function my_transactions()
    {
        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }
}
