<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');

class Skill_dept extends frontend
{
    protected $department_id = '2193';
    protected $service_reg = '1676|1859';

    public function __construct()
    {
        parent::__construct();
        $this->load->model("skill_dept_model");
        $this->load->helper("mis");

        // Enable CORS
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS");

        // Department ID :: 2193
    }

    // count total applications
    public function index()
    {
        // verify jwt
        $result = verify_jwt();
        if ($result['status'] === false) {

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode($result));
            die;
        }

        $total = $this->skill_dept_model->tot_search_rows(array(
            'initiated_data.department_id' => $this->department_id,
        ));
        $deliver = $this->skill_dept_model->tot_search_rows(array(
            'initiated_data.department_id' => $this->department_id,
            'initiated_data.appl_status' => 'D',
        ));
        $reject = $this->skill_dept_model->tot_search_rows(array(
            'initiated_data.department_id' => $this->department_id,
            'initiated_data.appl_status' => 'R',
        ));
        $pendingIT = $this->skill_dept_model->calculate_pending_in_time($this->department_id);
        $timelyDelivered = $this->skill_dept_model->calculate_delivered_in_time($this->department_id);

        $response = array(
            'status' => true,
            'data' => array(
                'received' => $total,
                'pending' => ($total - ($reject + $deliver)),
                'pending_in_time' => $pendingIT,
                'delivered' => $deliver,
                'rejected' => $reject,
                'delivered_in_time' => $timelyDelivered,
            ),
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    // gender data for all applications
    public function gender_wise_application_count()
    {
        $result = verify_jwt();
        if ($result['status'] === false) {

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode($result));
            die;
        }

        $total_received_gender_wise = $this->skill_dept_model->total_applications_gender_wise($this->department_id);
        $gender_array = array();
        $gender_array['Male'] = 0;
        $gender_array['Female'] = 0;
        $gender_array['Others'] = 0;
        // $gender_array['NA'] = 0;

        // pre($total_received_gender_wise);

        foreach ($total_received_gender_wise as $key => $val) {
            if ($val->gender == 'Male' || $val->gender == 'Male / পুৰুষ') {
                $gender_array['Male'] += $val->total_received;
            } else if ($val->gender == 'Female' || $val->gender == 'Female / মহিলা') {
                $gender_array['Female'] += $val->total_received;
            } else if ($val->gender == 'Others' || $val->gender == 'Others / অন্যান্য') {
                $gender_array['Others'] += $val->total_received;
            }
            // else {
            //     $gender_array['NA'] += $val->total_received;
            // }
        }

        $response['status'] = true;
        $response['data'] = $gender_array;
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }
    // service status for all services
    public function all_service_status()
    {
        $result = verify_jwt();
        if ($result['status'] === false) {

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode($result));
            die;
        }

        $this->load->model("api_model");
        $this->load->model("services_model");

        $received = $this->skill_dept_model->total_services_group_by_service($this->department_id);
        $delivered = $this->skill_dept_model->services_delivered_group_by_service($this->department_id);
        $rejected = $this->skill_dept_model->services_rejected_group_by_service($this->department_id);
        $pending = $this->skill_dept_model->services_pending_group_by_service($this->department_id);
        $delivered_in_time = $this->skill_dept_model->delivered_in_time_group_by_service($this->department_id);
        $rejected_in_time = $this->skill_dept_model->rejected_in_time_group_by_service($this->department_id);
        $pending_in_time = $this->skill_dept_model->pending_in_time_group_by_service($this->department_id);
        $all_services = (array) $this->services_model->get_services_by_dept($this->department_id);

        $response = array_map(function ($service) {
            return array(
                'service_id' => $service->service_id,
                'service_name' => $service->service_name,
                'gender_data' => array(
                    'Male' => 0,
                    'Female' => 0,
                    'Others' => 0,
                    // 'NA' => 0,
                ),
                'gender_data_delivered' => array(
                    'Male' => 0,
                    'Female' => 0,
                    'Others' => 0,
                    // 'NA' => 0,
                ),
                'received' => 0,
                'delivered' => 0,
                'rejected' => 0,
                'pending' => 0,
                'delivered_in_time' => 0,
                'rejected_in_time' => 0,
                'pending_in_time' => 0,
            );
        }, $all_services);

        /* Adding Service status data */

        foreach ($received as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['received'] = $value->count;
                    break;
                }
            }
        }
        foreach ($delivered as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['delivered'] = $value->count;
                    break;
                }
            }
        }
        foreach ($rejected as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['rejected'] = $value->count;
                    break;
                }
            }
        }
        foreach ($pending as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['pending'] = $value->count;
                    break;
                }
            }
        }
        foreach ($delivered_in_time as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['delivered_in_time'] = $value->count;
                    break;
                }
            }
        }
        foreach ($rejected_in_time as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['rejected_in_time'] = $value->count;
                    break;
                }
            }
        }
        foreach ($pending_in_time as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['pending_in_time'] = $value->count;
                    break;
                }
            }
        }

        // Adding Gender Data for Total Applications
        foreach ($response as $key => $value) {
            $gender_data = $this->api_model->total_services_group_by_service_gender_wise($value['service_id']);
            $gender_array = array();
            $gender_array['Male'] = 0;
            $gender_array['Female'] = 0;
            $gender_array['Others'] = 0;
            // $gender_array['NA'] = 0;

            foreach ($gender_data as $k => $val) {
                if ($val->_id == 'Male' || $val->_id == 'Male / পুৰুষ') {
                    $gender_array['Male'] += $val->total_received;
                } else if ($val->_id == 'Female' || $val->_id == 'Female / মহিলা') {
                    $gender_array['Female'] += $val->total_received;
                } else if ($val->_id == 'Others / অন্যান্য' || $val->_id == 'Others') {
                    $gender_array['Others'] += $val->total_received;
                }
                // else {
                //     $gender_array['NA'] += $val->total_received;
                // }
            }

            $response[$key]['gender_data'] = $gender_array;
        }

        // Adding Gender data for Delivered applications
        foreach ($response as $key => $value) {
            $gender_data = $this->api_model->services_group_by_gender_wise_data($value['service_id'], 'D');
            $gender_array = array();
            $gender_array['Male'] = 0;
            $gender_array['Female'] = 0;
            $gender_array['Others'] = 0;
            // $gender_array['NA'] = 0;

            foreach ($gender_data as $k => $val) {
                if ($val->_id == 'Male' || $val->_id == 'Male / পুৰুষ') {
                    $gender_array['Male'] += $val->total_received;
                } else if (
                    $val->_id == 'Female' || $val->_id == 'Female / মহিলা'
                ) {
                    $gender_array['Female'] += $val->total_received;
                } else if ($val->_id == 'Others / অন্যান্য' || $val->_id == 'Others') {
                    $gender_array['Others'] += $val->total_received;
                }
                // else {
                //     $gender_array['NA'] += $val->total_received;
                // }
            }

            $response[$key]['gender_data_delivered'] = $gender_array;
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }
    // officewise service status
    public function officewise()
    {
        $result = verify_jwt();
        if ($result['status'] === false) {

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode($result));
            die;
        }

        $received = $this->skill_dept_model->total_services_group_by_office($this->department_id);
        $delivered = $this->skill_dept_model->services_delivered_group_by_office($this->department_id);
        $rejected = $this->skill_dept_model->services_rejected_group_by_office($this->department_id);
        $delivered_in_time = $this->skill_dept_model->delivered_in_time_group_by_office($this->department_id);
        $rejected_in_time = $this->skill_dept_model->rejected_in_time_group_by_office($this->department_id);
        $pending_in_time = $this->skill_dept_model->pending_in_time_group_by_office($this->department_id);

        /* Adding Service status data */

        foreach ($received as $key => $r_val) {

            foreach ($delivered as $key => $d_val) {
                if ($d_val->_id == $r_val->_id) {
                    $r_val->delivered = isset($d_val->count) ? $d_val->count : 0;
                }
            }
            foreach ($rejected as $key => $re_val) {
                if ($re_val->_id == $r_val->_id) {
                    $r_val->rejected = isset($re_val->count) ? $re_val->count : 0;
                }
            }
            foreach ($delivered_in_time as $key => $t_val) {
                if ($t_val->_id == $r_val->_id) {
                    $r_val->delivered_in_time = isset($t_val->count) ? $t_val->count : 0;
                }
            }
            foreach ($rejected_in_time as $key => $rej_val) {
                if ($rej_val->_id == $r_val->_id) {
                    $r_val->rejected_in_time = isset($rej_val->count) ? $rej_val->count : 0;
                }
            }
            foreach ($pending_in_time as $key => $pit_val) {
                if ($pit_val->_id == $r_val->_id) {
                    $r_val->pending_in_time = isset($pit_val->count) ? $pit_val->count : 0;
                }
            }

            if (!isset($r_val->rejected)) {
                $r_val->rejected = 0;
            }
            if (!isset($r_val->delivered)) {
                $r_val->delivered = 0;
            }
            if (!isset($r_val->delivered_in_time)) {
                $r_val->delivered_in_time = 0;
            }
            if (!isset($r_val->rejected_in_time)) {
                $r_val->rejected_in_time = 0;
            }
            if (!isset($r_val->pending_in_time)) {
                $r_val->pending_in_time = 0;
            }

            $r_val->pending = $r_val->received - ($r_val->delivered + $r_val->rejected);
        }

        // pre($received);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($received));
    }

    // exchangewise data
    public function exchangewise_report()
    {
        $result = verify_jwt();
        if ($result['status'] === false) {

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode($result));
            die;
        }

        $this->_validate_dates();

        $from_date = trim($this->input->get('from', true));
        $to_date = trim($this->input->get('to', true));

        try {
            $data = $this->skill_dept_model->all_exchanges($this->department_id, $this->service_reg, $from_date, $to_date);

            // pre($data);

            foreach ($data as $key => $exchange) {
                $gender = $this->skill_dept_model->gender_exchangewise($this->department_id, $this->service_reg, $exchange->_id, $from_date, $to_date);

                // replace gender info.

                foreach ($gender as $g) {
                    foreach ($exchange->caste_wise as $e) {

                        if ($g->_id == $e->_id) {
                            $e->male = $g->male;
                            $e->female = $g->female;
                            $e->others = $g->others;

                            break;
                        }
                    }
                }
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($data));
        } catch (\Exception $ex) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error' => $ex->getMessage(),
                )));
        }
    }

    // qualificationwise data
    public function qualificationwise_report()
    {
        $result = verify_jwt();
        if ($result['status'] === false) {

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode($result));
            die;
        }

        $this->_validate_dates();

        $from_date = trim($this->input->get('from', true));
        $to_date = trim($this->input->get('to', true));

        try {
            $data = $this->skill_dept_model->all_qualifications($this->department_id, $this->service_reg, $from_date, $to_date);

            foreach ($data as $key => $qualification) {
                $gender = $this->skill_dept_model->gender_qualificationwise($this->department_id, $this->service_reg, $qualification->_id, $from_date, $to_date);

                // replace gender info.

                foreach ($gender as $g) {
                    foreach ($qualification->caste_wise as $q) {

                        if ($g->_id == $q->_id) {
                            $q->male = $g->male;
                            $q->female = $g->female;
                            $q->others = $g->others;

                            break;
                        }
                    }
                }
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($data));
        } catch (\Exception $ex) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error' => $ex->getMessage(),
                )));
        }
    }

    // employmentwise data
    public function employmentwise_report()
    {
        $result = verify_jwt();
        if ($result['status'] === false) {

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode($result));
            die;
        }

        $this->_validate_dates();

        $from_date = trim($this->input->get('from', true));
        $to_date = trim($this->input->get('to', true));

        try {

            $data = $this->skill_dept_model->all_employments($this->department_id, $this->service_reg, $from_date, $to_date);

            foreach ($data as $key => $emp) {
                $gender = $this->skill_dept_model->gender_employmentwise($this->department_id, $this->service_reg, $emp->_id, $from_date, $to_date);

                // replace gender info.

                foreach ($gender as $g) {
                    foreach ($emp->caste_wise as $e) {

                        if ($g->_id == $e->_id) {
                            $e->male = $g->male;
                            $e->female = $g->female;
                            $e->others = $g->others;

                            break;
                        }
                    }
                }
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($data));
        } catch (\Exception $ex) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error' => $ex->getMessage(),
                )));
        }
    }


    // Get Applicant details using appl_ref_no OR registration_no 
    public function get_applicant_details()
    {
        $result = verify_jwt();
        if ($result['status'] === false) {

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode($result));
            die;
        }

        $reg_no = trim($this->input->get('reg-no', true));
        $appl_ref_no = trim($this->input->get('appl-ref-no', true));

        if (!empty($reg_no)) {
            // search by reg_no
            $data = $this->skill_dept_model->get_applicant_details('REG_NO', $reg_no, $this->department_id);

            if (!empty($data)) {
                $response['status'] = true;

                $response['data']['registration_no'] = $reg_no;
                $response['data']['appl_ref_no'] = $data['initiated_data']->appl_ref_no;
                $response['data']['applicant_name'] = $data['initiated_data']->attribute_details->applicant_name;
                $response['data']['applicant_gender'] = $data['initiated_data']->attribute_details->applicant_gender;
                $response['data']['email'] = $data['initiated_data']->attribute_details->{'e-mail'} ?? 'N/A';
                $response['data']['mobile_number'] = $data['initiated_data']->attribute_details->mobile_number;
                $response['data']['jobseeker_name'] = $data['initiated_data']->attribute_details->applicant_name_jobseeker ?? 'N/A';
                $response['data']['jobseeker_gender'] =
                    $data['initiated_data']->attribute_details->gender ?? $data['initiated_data']->attribute_details->gender_jobseeker ?? 'N/A';

                // Present address
                $response['data']['address']['present']['district'] = $data['initiated_data']->attribute_details->district ?? 'N/A';
                $response['data']['address']['present']['pincode'] = $data['initiated_data']->attribute_details->pin ?? $data['initiated_data']->attribute_details->pin_code ?? 'N/A';
                $response['data']['address']['present']['post_office'] = $data['initiated_data']->attribute_details->post_office ?? 'N/A';
                $response['data']['address']['present']['police_station'] = $data['initiated_data']->attribute_details->police_station ?? 'N/A';
                $response['data']['address']['present']['vill_town_ward_city'] = $data['initiated_data']->attribute_details->vill_town_ward_city ?? 'N/A';

                // Permanent Address
                $response['data']['address']['permanent']['district'] = $data['initiated_data']->attribute_details->district_p ?? 'N/A';
                $response['data']['address']['permanent']['pincode'] = $data['initiated_data']->attribute_details->pin_p ?? $data['initiated_data']->attribute_details->pin_code_p ?? 'N/A';
                $response['data']['address']['permanent']['post_office'] = $data['initiated_data']->attribute_details->post_office_p ?? 'N/A';
                $response['data']['address']['permanent']['revenue_circle'] = $data['initiated_data']->attribute_details->revenue_circle ?? 'N/A';
                $response['data']['address']['permanent']['sub_division'] = $data['initiated_data']->attribute_details->{'sub-division'} ?? 'N/A';
                $response['data']['address']['permanent']['police_station'] = $data['initiated_data']->attribute_details->police_station_p ?? 'N/A';
                $response['data']['address']['permanent']['vill_town_ward_city'] = $data['initiated_data']->attribute_details->vill_town_ward_city_p ?? 'N/A';
                $response['data']['address']['permanent']['residence'] = $data['initiated_data']->attribute_details->residence ?? 'N/A';
            } else {
                $response['status'] = true;
                $response['data'] = $data;
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        } elseif (!empty($appl_ref_no)) {
            // search by appl_ref_no
            $data = $this->skill_dept_model->get_applicant_details('APPL_REF_NO', $appl_ref_no, $this->department_id);

            if (!empty($data)) {
                $response['status'] = true;

                $response['data']['registration_no'] = $data['initiated_data']->attribute_details->registration_no ?? $data['execution_data'][0]->official_form_details->registration_no ?? 'N/A';
                $response['data']['appl_ref_no'] = $data['initiated_data']->appl_ref_no;
                $response['data']['applicant_name'] = $data['initiated_data']->attribute_details->applicant_name;
                $response['data']['applicant_gender'] = $data['initiated_data']->attribute_details->applicant_gender;
                $response['data']['email'] = $data['initiated_data']->attribute_details->{'e-mail'} ?? 'N/A';
                $response['data']['mobile_number'] = $data['initiated_data']->attribute_details->mobile_number;
                $response['data']['jobseeker_name'] = $data['initiated_data']->attribute_details->applicant_name_jobseeker ?? 'N/A';
                $response['data']['jobseeker_gender'] =
                    $data['initiated_data']->attribute_details->gender ?? $data['initiated_data']->attribute_details->gender_jobseeker ?? 'N/A';

                // Present address
                $response['data']['address']['present']['district'] = $data['initiated_data']->attribute_details->district ?? 'N/A';
                $response['data']['address']['present']['pincode'] = $data['initiated_data']->attribute_details->pin ?? $data['initiated_data']->attribute_details->pin_code ?? 'N/A';
                $response['data']['address']['present']['post_office'] = $data['initiated_data']->attribute_details->post_office ?? 'N/A';
                $response['data']['address']['present']['police_station'] = $data['initiated_data']->attribute_details->police_station ?? 'N/A';
                $response['data']['address']['present']['vill_town_ward_city'] = $data['initiated_data']->attribute_details->vill_town_ward_city ?? 'N/A';

                // Permanent Address
                $response['data']['address']['permanent']['district'] = $data['initiated_data']->attribute_details->district_p ?? 'N/A';
                $response['data']['address']['permanent']['pincode'] = $data['initiated_data']->attribute_details->pin_p ?? $data['initiated_data']->attribute_details->pin_code_p ?? 'N/A';
                $response['data']['address']['permanent']['post_office'] = $data['initiated_data']->attribute_details->post_office_p ?? 'N/A';
                $response['data']['address']['permanent']['revenue_circle'] = $data['initiated_data']->attribute_details->revenue_circle ?? 'N/A';
                $response['data']['address']['permanent']['sub_division'] = $data['initiated_data']->attribute_details->{'sub-division'} ?? 'N/A';
                $response['data']['address']['permanent']['police_station'] = $data['initiated_data']->attribute_details->police_station_p ?? 'N/A';
                $response['data']['address']['permanent']['vill_town_ward_city'] = $data['initiated_data']->attribute_details->vill_town_ward_city_p ?? 'N/A';
                $response['data']['address']['permanent']['residence'] = $data['initiated_data']->attribute_details->residence ?? 'N/A';
            } else {
                $response['status'] = true;
                $response['data'] = $data;
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        } else {
            // parameter not found

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'status' => false,
                    'msg' => 'appl_ref_no OR registration_no not found.'
                )));
        }
    }


    // Get Data yearwise
    public function get_data_yearwise($year = '')
    {
        $result = verify_jwt();
        if ($result['status'] === false) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode($result));
            die;
        }

        $this->form_validation->set_data(array(
            'year' => $year,
        ));
        $this->form_validation->set_rules('year', 'YEAR', 'trim|required|regex_match[/^\d{4}$/]');
        if ($this->form_validation->run() == false) {

            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error' => $this->form_validation->error_array(),
                )))
                ->_display();

            exit();
        }

        // Get Service ids
        // Read JSON input
        $input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);

        if (
            !is_array($input_data) ||
            !array_key_exists('service_ids', $input_data) ||
            !is_array($input_data['service_ids']) ||
            empty($input_data['service_ids'])
        ) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error' =>  "service_ids missing",
                )))
                ->_display();

            exit();
        }

        $data = $this->skill_dept_model->get_data_monthwise_by_year($this->department_id, trim($year), $input_data['service_ids']);

        // pre($data);

        $months = [
            "", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
        ];
        $response = [];
        for ($i = 1; $i <= 12; $i++) {
            $response[] = (object) [
                '_id' => $months[$i],
                'month' => $i,
                'total' => 0,
                'delivered' => 0,
                'rejected' => 0
            ];
        }

        foreach ($response as $i => $val) {
            // Add total submission
            foreach ($data[0]->total_submission as $k => $v) {
                if ($val->_id === $v->_id) {
                    $val->total = $v->total;

                    break;
                }
            }

            // Add Delivered/Rejected
            foreach ($data[0]->total_action as $k => $v) {
                if ($val->_id === $v->_id) {
                    $val->delivered = $v->delivered;
                    $val->rejected = $v->rejected;

                    break;
                }
            }
        }

        // pre($response);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }


    private function _validate_dates()
    {
        $this->form_validation->set_data(array(
            'from' => $this->input->get('from', true),
            'to' => $this->input->get('to', true),
        ));

        $this->form_validation->set_rules('from', 'FROM date', 'trim|required|regex_match[/^(\d{4})-(\d{2})-(\d{2})$/]');
        $this->form_validation->set_rules('to', 'TO date', 'trim|required|regex_match[/^(\d{4})-(\d{2})-(\d{2})$/]');

        if ($this->form_validation->run() == false) {

            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error' => $this->form_validation->error_array(),
                )))
                ->_display();

            exit();
        }
    }
}
