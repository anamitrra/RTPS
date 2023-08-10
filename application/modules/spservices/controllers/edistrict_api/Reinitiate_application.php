<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Reinitiate_application extends frontend
{

    //private $secret_key = "s696onad8s8m"; //For Prod
    private $secret_key = "s786odty6t7x"; //For UAT
    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->config('spconfig');
        $this->load->helper("log");
    } //End of __construct()



    public function get_request()
    {
        $decodedText = '';
        $wsResponse =  $this->input->post("applJson");
        $hmac =  $this->input->post("hmac");
        $submission_type = $this->input->post("submission_type");
        edistrict_log_response("edist_reinitiate",$_POST);
        if (!empty($wsResponse) && !empty($hmac)) {
            $decodedText = stripslashes(html_entity_decode($wsResponse));
            $hmac = hash_hmac('sha256', $decodedText, $this->secret_key); //PROD_KYE: s696onad8s8m
            if ($this->input->post("hmac") != $hmac) {
                $resArr = array(
                    'message' => 'Invalid request!',
                    'status' => false,
                );
            } else {
                $json_obj = json_decode($decodedText);
                $data = array(
                    "service_id" => $json_obj->service_id ?? '',
                    "edistrict_ref_no" => $json_obj->edist_ack_no ?? '',
                    "submission_type" => $submission_type ?? '',
                );

                //Set validation rules
                $this->form_validation->set_data($data);
                $this->form_validation->set_rules("edistrict_ref_no", "Application ref. no", "required");
                $this->form_validation->set_rules("service_id", "Service id", "required");
                if ($this->form_validation->run() == FALSE) {
                    $resArr = array(
                        'message' => 'Validation errors : ' . validation_errors(),
                        'status' => false,
                    );
                } else {
                    // edistrict_log_response($data["edistrict_ref_no"],$_POST);
                    if ($data["service_id"] == "INC") {
                        $ref = modules::load('spservices/income/reinitiate_application_inc');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "SCC" || $data["service_id"] == "SCTZN") {
                        $ref = modules::load('spservices/seniorcitizen/reinitiate_application_scc');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "NOK" || $data["service_id"] == "NOKIN") {
                        $ref = modules::load('spservices/nextofkin/reinitiate_application_nokin');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "PDBR") {
                        $ref = modules::load('spservices/delayedbirth/reinitiate_application_pdbr');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "PDDR") {
                        $ref = modules::load('spservices/delayeddeath/reinitiate_application_pddr');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "BAK" || $data["service_id"] == "BAKCL") {
                        $ref = modules::load('spservices/bakijai/reinitiate_application_bakcl');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "CASTE") {
                        $ref = modules::load('spservices/castecertificate/reinitiate_application_caste');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "PRC") {
                        $ref = modules::load('spservices/prc/reinitiate_application_prc');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    }else if ($data["service_id"] == "NCL") {
                        $ref = modules::load('spservices/noncreamylayercertificate/reinitiate_application_ncl');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]); 
                    }
                    else {
                        $resArr = array(
                            'message' => 'Service id mismatched...! ',
                            'status' => false,
                        );
                    }
                    edistrict_log_response($data["edistrict_ref_no"], json_encode($resArr));
                }
            }
        } else {
            $resArr = array(
                'message' => 'Invalid request!',
                'status' => false,
            );
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($resArr));
    }

    public function manually_initiate()
    {
        $decodedText = '';
        $wsResponse =  $this->input->post("applJson");
        $hmac =  $this->input->post("hmac");
        $submission_type = $this->input->post("submission_type");
        if (!empty($wsResponse) && !empty($hmac)) {
            $decodedText = stripslashes(html_entity_decode($wsResponse));
            $hmac = hash_hmac('sha256', $decodedText, $this->secret_key); //PROD_KYE: s696onad8s8m
            if ($this->input->post("hmac") != $hmac) {
                $resArr = array(
                    'message' => 'Invalid request!',
                    'status' => false,
                );
            } else {
                $json_obj = json_decode($decodedText);
                $data = array(
                    "service_id" => $json_obj->service_id ?? '',
                    "edistrict_ref_no" => $json_obj->edist_ack_no ?? '',
                    "submission_type" => $submission_type ?? '',
                );

                //Set validation rules
                $this->form_validation->set_data($data);
                $this->form_validation->set_rules("edistrict_ref_no", "Application ref. no", "required");
                $this->form_validation->set_rules("service_id", "Service id", "required");
                if ($this->form_validation->run() == FALSE) {
                    $resArr = array(
                        'message' => 'Validation errors : ' . validation_errors(),
                        'status' => false,
                    );
                } else {
                    if ($data["service_id"] == "INC") {
                        $ref = modules::load('spservices/income/reinitiate_application_inc');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "SCC" || $data["service_id"] == "SCTZN") {
                        $ref = modules::load('spservices/seniorcitizen/reinitiate_application_scc');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "NOK" || $data["service_id"] == "NOKIN") {
                        $ref = modules::load('spservices/nextofkin/reinitiate_application_nokin');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "PDBR") {
                        $ref = modules::load('spservices/delayedbirth/reinitiate_application_pdbr');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "PDDR") {
                        $ref = modules::load('spservices/delayeddeath/reinitiate_application_pddr');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "BAK" || $data["service_id"] == "BAKCL") {
                        $ref = modules::load('spservices/bakijai/reinitiate_application_bakcl');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "CASTE") {
                        $ref = modules::load('spservices/castecertificate/reinitiate_application_caste');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    } else if ($data["service_id"] == "PRC") {
                        $ref = modules::load('spservices/prc/reinitiate_application_prc');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]);
                    }else if ($data["service_id"] == "NCL") {
                        echo("NCL");
                        $ref = modules::load('spservices/noncreamylayercertificate/reinitiate_application_ncl');
                        $resArr = $ref->post_data($data["edistrict_ref_no"]); 
                    } 
                    else {
                        $resArr = array(
                            'message' => 'Service id mismatched..! ',
                            'status' => false,
                        );
                    }
                }
            }
        } else {
            $resArr = array(
                'message' => 'Invalid request!',
                'status' => false,
            );
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($resArr));
    }
}//End of Necapi