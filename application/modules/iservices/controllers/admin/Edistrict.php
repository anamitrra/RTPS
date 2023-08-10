<?php

/**
 * Description of Dashboard
 *
 * @author 
 * Sayeed Akhtar Rahman
 */
defined('BASEPATH') or exit('No direct script access allowed');
class Edistrict extends Rtps
{

    private $secret_key = "s786odty6t7x"; //For UAT
    //private $secret_key = "s696onad8s8m"; //For Prod
    //put your code here
    public function __construct()
    {
        parent::__construct();
        $this->config->load('rtps_services');
    }
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        if ($this->session->userdata('role')->slug === "SA") {
            $this->load->view("includes/header", array("pageTitle" => "Dashboard"));
            $this->load->view("admin/dashboards/edistrict");
            $this->load->view("includes/footer");
        }
    }

    public function submit()
    {
        if ($this->session->userdata('role')->slug === "SA") {
            $service_id = $this->input->post('service_id');
            $edistrict_ref_no = $this->input->post('edistrict_ref_no');
            $submission_type = $this->input->post('submission_type');

            $validate_data = array(
                "service_id" => $service_id ?? '',
                "edistrict_ref_no" => $edistrict_ref_no ?? '',
                "submission_type" => $submission_type ?? '',
            );

            //Set validation rules
            $this->form_validation->set_data($validate_data);
            $this->form_validation->set_rules("edistrict_ref_no", "Application ref. no", "required");
            $this->form_validation->set_rules("service_id", "Service name required", "required");
            $this->form_validation->set_rules("submission_type", "Submission type required", "required");
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $this->index();
            } else {

                $applJosn = array(
                    'service_id' => $service_id,
                    'edist_ack_no' => $edistrict_ref_no
                );
                $json_data = json_encode($applJosn);
                $decodedText = stripslashes(html_entity_decode($json_data));
                $hmac_value = hash_hmac('sha256', $decodedText, $this->secret_key);
                //$url = "https://rtps.assam.gov.in/app_test/spservices/edistrict_api/reinitiate_application";
                //$url = "https://rtps.assam.gov.in/spservices/edistrict_api/reinitiate_application";
                $url = "http://localhost/rtps/spservices/edistrict_api/manually_initiate";
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
                    'applJson' => $decodedText,
                    'hmac' => urlencode($hmac_value),
                    'submission_type' => urlencode($submission_type),
                )));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }
                curl_close($curl);
                if (isset($error_msg)) {
                    die("CURL ERROR : " . $error_msg);
                } elseif (!empty($response)) {
                    $response = json_decode($response);
                    if (isset($response->status) && $response->status == true) {
                        $this->session->set_flashdata('success', $response->message);
                        $this->index();
                    } else if (isset($response->status) && $response->status == false) {
                        $this->session->set_flashdata('error', $response->message);
                        $this->index();
                    }
                } else {
                    $this->session->set_flashdata('error', 'No response received from the eDistrict POST API!');
                    $this->index();
                    //redirect('iservices/admin/dashboard/edistrict');
                    //exit();
                }
            }
        }
    }
}
