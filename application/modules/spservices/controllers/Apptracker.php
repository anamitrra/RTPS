<?php
if (!defined('BASEPATH'))exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
class Apptracker extends Rtps {

    public function __construct() {
        parent::__construct();
        $this->load->model('necertificates_model');
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
    }//End of __construct()

    public function index() {
        if (($this->session->userdata('role')->slug === "SA") || ($this->session->userdata('role')->slug === "PFC")) {
            $this->load->view('includes/header');
            $this->load->view('nec/apptracker_view');
            $this->load->view('includes/footer');
        } else {
            $this->session->set_flashdata('error', 'Only super admin has the access');
            redirect('spservices/necertificate/');
        }//End of if else        
    }//End of index()

    public function callapi() {
        $searchBy = $this->input->post("search_by");
        $postUrl = base_url('spservices/trackallapps/get_records');
        $json_obj = json_encode(array("app_ref_no" => $searchBy, "user_type" => "SA"));
        $authorization = "Authorization: Bearer 080042cad6356ad5dc0a720c18b53b8e53d4c274";

        $curl = curl_init($postUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }//End of if
        curl_close($curl);
        if (isset($error_msg)) {
            echo '<h2 style="text-align:center">Error in server communication ' . $error_msg . '</h2>';
        } elseif ($response) {
            $res = json_decode($response);
            if ($res->status) {                
                $data = $res->data;
                if (count($data)) {
                    echo '<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Applicant name</th>
                                <th>Ref. No.</th>
                                <th>Service name</th>
                                <th>Submission date</th>
                                <th>Current status</th>
                                <th>Payment status</th>
                            </tr>
                        </thead>
                        <tbody>';
                        foreach ($data as $datum) {
                            $iData = $datum->initiated_data;
                            $OFFICE_CODE = $iData->payment_params->OFFICE_CODE ?? "";
                            $DEPARTMENT_ID = $iData->pfc_payment_response->DEPARTMENT_ID ?? "";
                            $AMOUNT = $iData->pfc_payment_response->AMOUNT ?? "";
                            $ppr_status = $iData->pfc_payment_response->STATUS??'';
                            $aData = $datum->action_data;
                            $status_code = $iData->status_code??'';
                            $btns = array();
                            if ($aData) {
                                foreach ($aData as $aDatatum) {
                                    if ($aDatatum->name === 'Verify Payment') {
                                        $btns[] = "<form method='post' name='getGRN' id='getGRN' action='{$this->config->item('egras_grn_cin_url')}' >
                                                <input type='hidden' id ='DEPARTMENT_ID' name='DEPARTMENT_ID' value='{$DEPARTMENT_ID}' />
                                                <input type='hidden' id ='OFFICE_CODE' name='OFFICE_CODE' value='{$OFFICE_CODE}' />
                                                <input type='hidden' id ='AMOUNT' name='AMOUNT' value='{$AMOUNT}' />
                                                <input type='hidden' id ='ACTION_CODE' name='ACTION_CODE' value='GETCIN' readonly/>
                                                <input type='hidden' id ='SUB_SYSTEM' name='SUB_SYSTEM' value='ARTPS-SP|{$aDatatum->url}' />
                                                <input type='submit' style='margin-top: 3px;color:white' id ='submit' class='btn btn-sm btn-warning mbtn' name='submit' target = '_BLANK' value='{$aDatatum->name}' />
                                            </form>";
                                    } elseif ((in_array($status_code, ["payment_initiated", "F"])) && ($ppr_status == "Y")) {
                                        $btns[] = "<a href='{$aDatatum->url}' class='btn btn-info btn-sm mbtn mb-1' target='_blank'>GET ACKNOWLEDGEMENT</a>";
                                    } else {
                                        $btns[] = "<a href='{$aDatatum->url}' class='btn btn-info btn-sm mbtn mb-1' target='_blank'>{$aDatatum->name}</a>";
                                    }//End of if else
                                }//End of foreach()
                            }//End of if
                            $myBtns = implode('&nbsp;', $btns);
                            echo "<tr>"
                            . "<td>{$iData->applicant_name}</td>"
                            . "<td>{$iData->appl_ref_no}</td>"
                            . "<td>{$iData->service_name}</td>"
                            . "<td>{$iData->submission_date}</td>"
                            . "<td>{$iData->status}</td>"
                            . "<td>{$myBtns}</td>"
                            . "</tr>";
                        }//End of foreach()
                        echo '</tbody>
                    </table>';
                        
                    $dbRow = $this->necertificates_model->get_row(array("rtps_trans_id" => $searchBy));
                    if ($dbRow == false) {
                        $dbRow = $this->necertificates_model->get_row(array("service_data.appl_ref_no" => $searchBy));
                    }//End of if
                    echo "<pre style='background-color: #444; color:#fff;'>";
                    echo json_encode($dbRow, JSON_PRETTY_PRINT);
                    echo "</pre>";
                } else {
                    echo '<h2 style="text-align:center">No data found</h2>';
                }//End of if else
            } else {
                echo '<h2 style="text-align:center">' . $res->message ?? "No records found" . '</h2>';
            }//End of if else            
        }
    }//End of callapi()
}//End of Apptracker
