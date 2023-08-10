<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Site extends frontend
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return JSON
     */
    public function index()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type' => 1));
        if ($result) {
            $response['status'] = true;
            $response['data'] = $result->data;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        }
    }
    /**
     * @return JSON
     */
    public function gender_wise_application_count()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type' => 2));
        if ($result) {
            $response['status'] = true;
            $response['data'] = $result->data;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        }
    }
    /**
     * @return JSON
     */
    public function all_service_status()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type' => 3));
        if ($result) {
            $response['status'] = true;
            $response['data'] = $result->data;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        }
    }
    /**
     * @return JSON
     */
    public function officewise()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type' => 4));
        if ($result) {
            $response['status'] = true;
            $response['data'] = $result->data;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        }
    }
    /**
     * @return JSON
     */
    public function officewise_appilcation_for_ovca()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type' => 5));
        if ($result) {
            $response['status'] = true;
            $response['data'] = $result->data;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        }
    }
    /**
     * @return JSON
     */
    public function officewise_appilcation_for_ovca_v2()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type' => 6));
        if ($result) {
            $response['status'] = true;
            $response['data'] = $result->data;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        }
    }
    /**
     * @return JSON
     */
    public function officewise_appilcation_for_ovca_total()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type' => 7));
        if ($result) {
            $response['status'] = true;
            $response['data'] = $result->data;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        }
    }

    /* Get popular services */
    public function popular_services()
    {
        $this->load->model("stored_api_model");
        $response['status'] = true;
        $response['data'] = ($this->stored_api_model->last_where(array('type' => 100)))->data ?? [];

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }
}
