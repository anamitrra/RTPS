<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\UTCDateTime;
class Query extends frontend {

    private $ePanjayeenURL = '';
    
    public function __construct() {
        parent::__construct();
        $this->load->model('registered_deed_model');
        $this->load->config('spconfig');
        $this->ePanjayeenURL = 'https://landhub.assam.gov.in/webapi_test/webapi_land/';//$this->config->item('url');
    }

    public function post_payment_query() {
        $applId = $this->input->post('applId');
        $dbRow = $this->registered_deed_model->get_row(array('applId' => $applId));
        //processing
        $processing_history = $dbRow->processing_history ?? array();
        $processing_history[] = array(
            "processed_by" => "Payment Query made by department",
            "action_taken" => "Payment Query made",
            "remarks" => "Payment Query made by department",
            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
        );

        $res = $this->registered_deed_model->add_param(array('applId' => $applId), array("query" => $_POST, "status" => 'FRS', 'processing_history' => $processing_history));

        if ($res->getMatchedCount()) {
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => true)));
        } else {
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => "Invalid Data")));
        }
    }

    public function post_query() {

        $applId = $this->input->post('applId');
        $wsResponse = $this->input->post('wsResponse');
        $dbRow = $this->registered_deed_model->get_row(array('applId' => $applId));
        //processing
        $processing_history = $dbRow->processing_history ?? array();
        $processing_history[] = array(
            "processed_by" => "Query made by department",
            "action_taken" => "Query made",
            "remarks" => "Query made by department",
            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
        );
        $res = $this->registered_deed_model->add_param(array('applId' => $applId), array("normal_query" => $_POST, "status" => "QS", 'processing_history' => $processing_history));

        if ($res->getMatchedCount()) {
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => true)));
        } else {
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => false)));
        }
    }

    public function update_status() {
        $applId = $this->input->post('applId');
        if ($applId) {
            $application = $this->registered_deed_model->get_row(array('applId' => $applId));
            $rtps_trans_id = $application->rtps_trans_id;
            $hash_cer = json_decode($_POST['wsResponse'], true);
            if ($hash_cer) {

                $fileName = str_replace('/', '_', $rtps_trans_id) . '.pdf';
                $dirPath = 'storage/CRCPY/';
                if (!is_dir($dirPath)) {
                    mkdir($dirPath, 0777, true);
                    file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for NEC only</body></html>');
                }
                $filePath = $dirPath . $fileName;
                $res = file_put_contents(FCPATH . $filePath, base64_decode($hash_cer['certificate']));
                if ($res) {
                    //processing
                    $processing_history = $application->processing_history ?? array();
                    $processing_history[] = array(
                        "processed_by" => "Application Delivered by department",
                        "action_taken" => "Application Delivered",
                        "remarks" => "Application Delivered by department",
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
                    $result = $this->registered_deed_model->add_param(array('applId' => $applId), array("certificate_path" => $filePath, "status" => "D", 'processing_history' => $processing_history));
                    if ($result->getMatchedCount()) {
                        return $this->output
                                        ->set_content_type('application/json')
                                        ->set_status_header(200)
                                        ->set_output(json_encode(array("status" => true)));
                    }
                }
            }
        }
        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array("status" => false)));
    }

    public function application_rejected() {

        $applId = $this->input->post('applId');
        $dbRow = $this->registered_deed_model->get_row(array('applId' => $applId));
        //processing
        $processing_history = $dbRow->processing_history ?? array();
        $processing_history[] = array(
            "processed_by" => "Application Rejected by department",
            "action_taken" => "Application Rejected",
            "remarks" => "Application Rejected by department",
            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
        );

        $res = $this->registered_deed_model->add_param(array('applId' => $applId), array("status" => 'R', 'processing_history' => $processing_history));

        if ($res->getMatchedCount()) {
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => true)));
        } else {
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status" => true)));
        }
    }

    public function push_query_payment_status() {
        $rtps_trans_id = $_GET['id'];
        if ($rtps_trans_id) {
            $transaction_data = $this->registered_deed_model->get_row(array('rtps_trans_id' => $rtps_trans_id));
            if (property_exists($transaction_data, 'query') && property_exists($transaction_data, 'query_payment_response') && $transaction_data->query_payment_status === 'Y') {

                $postdata = array(
                    "Ref" => array(
                        "fee_paid" => $transaction_data->query_payment_response->GRN,
                        "test_param" => "EGRAS Assam",
                        "application_status" => "FRS"
                    ),
                    "spId" => array(
                        "applId" => $transaction_data->applId
                    )
                );
                $curl = curl_init($this->ePanjayeenURL . "cercpy/fee_paid_status.php");
                // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $res = curl_exec($curl);

                curl_close($curl);

                if ($res) {
                    $res = json_decode($res);

                    if ($res->ref->status === "success") {
                        $this->registered_deed_model->update_row(
                                array('rtps_trans_id' => $transaction_data->rtps_trans_id),
                                array(
                                    "status" => 'PR',
                                    "qps_updated_on_backend" => true
                                )
                        );
                        echo "Updated";
                    }
                } else {
                    echo "Something went wrong";
                }
            }
        }
    }

}
