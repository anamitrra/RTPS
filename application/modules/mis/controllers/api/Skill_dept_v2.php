<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');

class Skill_dept_v2 extends frontend
{
    protected $department_id = '2193';

    public function __construct()
    {
        parent::__construct();
        $this->load->model("skill_dept_model_v2");
        $this->load->helper("mis");

        // Enable CORS
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

        // Department ID :: 2193
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

        $input_data = $this->_read_user_input();

        try {
            // $data = $this->skill_dept_model_v2->all_exchanges($this->department_id, $input_data['service_ids'], $input_data['from'], $input_data['to']);

            $data = $this->skill_dept_model_v2->get_exchangewise_report($this->department_id, $input_data['service_ids'], $input_data['from'], $input_data['to']);

            // pre($data);

            // foreach ($data as $key => $exchange) {
            //     $gender = $this->skill_dept_model_v2->gender_exchangewise($this->department_id, $input_data['service_ids'], $exchange->_id, $input_data['from'], $input_data['to']);

            //     $exchange->caste_wise = $gender;

            // pre($exchange);

            // replace gender info.
            // foreach ($gender as $g) {
            //     foreach ($exchange->caste_wise as $e) {

            //         if ($g->_id == $e->_id) {
            //             $e->male = $g->male;
            //             $e->female = $g->female;
            //             $e->others = $g->others;

            //             break;
            //         }
            //     }
            // }
            // }

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

        $input_data = $this->_read_user_input();


        try {
            $data = $this->skill_dept_model_v2->all_qualifications($this->department_id, $input_data['service_ids'], $input_data['from'], $input_data['to']);

            // pre($data);

            foreach ($data as $key => $qualification) {
                $gender = $this->skill_dept_model_v2->gender_qualificationwise($this->department_id, $input_data['service_ids'], $qualification->_id, $input_data['from'], $input_data['to']);

                // pre($gender);

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

        $input_data = $this->_read_user_input();

        try {

            $data = $this->skill_dept_model_v2->all_employments($this->department_id, $input_data['service_ids'], $input_data['from'], $input_data['to']);

            foreach ($data as $key => $emp) {
                $gender = $this->skill_dept_model_v2->gender_employmentwise($this->department_id, $input_data['service_ids'], $emp->_id, $input_data['from'], $input_data['to']);

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

    private function _validate_dates($from = '', $to = '')
    {
        $this->form_validation->set_data(array(
            'from' => $from,
            'to' => $to,
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


    private function _read_user_input()
    {
        // Read JSON input
        $input_data = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);

        // All input fields are mandatory
        if (empty($input_data) || !array_key_exists('service_ids', $input_data) || !array_key_exists('from', $input_data) || !array_key_exists('to', $input_data)) {
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error' => 'input data missing',
                )))
                ->_display();;

            exit();
        }

        // All the input parameter exist. Now check input values
        if (!(is_array($input_data['service_ids']) && !empty($input_data['service_ids']))) {
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

        $this->_validate_dates($input_data['from'], $input_data['to']);


        // covert service_ids into array of strings
        $input_data['service_ids'] = array_map(function ($val) {
            return strval($val);
        }, $input_data['service_ids']);

        return $input_data;
    }

    /* Utitlity function to count from JSON */
    public function test_count()
    {
        $data = json_decode('{}');

        $total = 0;
        foreach ($data as $key => $value) {
            $total += ($value->total_male + $value->total_female + $value->total_others);
        }

        pre($total);
    }
}
