<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Trackappstatus extends Site_Controller
{
    private const MB2_SERVICES = ["243", "244", "245", "246", "247", "248", "249"];
    private $urls = [];

    private $settings;   // page settings

    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
        $this->load->model('settings_model');
        $this->load->model('site_model');

        $this->settings = $this->settings_model->get_settings('citizen_track');

        // API urls

        $this->urls[] = (object)['url' => 'http://localhost/spservices/trackapplicationstatus/byrefno', 'secret' => 'rtpsapi#!@'];
        $this->urls[] = (object)['url' => 'http://localhost/iservices/misapi/get_edistric_app_status', 'secret' => 'rtpsapi#!@'];
        $this->urls[] = (object)['url' => 'http://localhost/iservices/misapi/update_app_statusv2', 'secret' => 'rtpsapi#!@'];
        $this->urls[] = (object)['url' => 'http://localhost/iservices/misapi/get_apdcl_app_status', 'secret' => 'rtpsapi#!@'];
        $this->urls[] = (object)['url' => 'http://localhost/tools/rtps_id_labels/src/api/external_apis.php/get_sp_data', 'secret' => 'rtpsapi#!@', 'header_token' => '|0VW?z,-w2w"6;{b8v}K6A5+Fdf@l-'];
        $this->urls[] = (object)['url' => 'http://localhost/tools/rtps_id_labels/src/api/external_apis.php/get_mis_data', 'secret' => 'rtpsapi#!@', 'header_token' => '|0VW?z,-w2w"6;{b8v}K6A5+Fdf@l-'];
    } //End of __construct()

    public function index()
    {
        $data['data'] = $this->settings;
        $this->render_view_new('trackappstatus_view', $data, array());
    } //End of index()

    public function get_details()
    {
        $ref_no = trim($this->input->post("ref_no", true));
        $row = $this->get_latest_app_status($ref_no);

        // pre($row);

        if (!empty($row['status'])) {

            // For spservice API,  {'data'} field is missing in the response object, 😤😖😩

            if (!empty($row['data'])) {
                $row['initiated_data'] = $row['data']['initiated_data'] ?? [];
                $row['execution_data'] = $row['data']['execution_data'] ?? [];
            }

            $ref_no = $row['initiated_data']['appl_ref_no'];
            $date_of_submission = $row['initiated_data']['submission_date'];
            $applicant_name = $row['initiated_data']['applicant_name'];
            $service_name = $row['initiated_data']['service_name'];
            $current_status = $this->getstatusdetails($row['initiated_data']['status']);
            $service_timeline = explode(' ', trim($row['initiated_data']['service_timeline']))[0];
            $service_id = trim($row['initiated_data']['service_id']);



            // For Basundhara v2 services
            if (in_array($service_id, self::MB2_SERVICES, false)) {
                $stipulated_delivery_date = 'By 5th August, 2023';
            } else {
                $stipulated_delivery_date = (\DateTime::createFromFormat('Y-m-d H:i:s', $row['initiated_data']['submission_date']))->add(new DateInterval("P{$service_timeline}D"))
                    ->format('Y-m-d H:i:s');
            }


            $recordFound = true;
        } else {
            $recordFound = false;
        } //End of if else

        if ($recordFound) { ?>
            <table class="table table-bordered mt-5 border-warning">
                <thead>
                    <tr>
                        <th colspan="4" class="text-bold bg-warning">Application tracking details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2" style="width:50%;">
                            <span style="float:left;">Reference no. : <strong><?= $ref_no ?></strong></span>
                        </td>
                        <td colspan="2">
                            <span style="float:right;">Submitted on : <strong><?= $date_of_submission ?></strong></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Applicant Name</td>
                        <td style="font-weight: bold;"><?= $applicant_name ?></td>
                        <td>Service Name</td>
                        <td style="font-weight: bold"><?= $service_name ?></td>
                    </tr>
                    <tr>
                        <td>Stipulated Delivery Date</td>
                        <td style="font-weight: bold;"><?= $stipulated_delivery_date ?></td>
                        <td>Current Status</td>
                        <td style="font-weight: bold"><?= $current_status ?></td>
                    </tr>

                    <!-- For APDCL application display Consumer No. & Application No. -->

                    <?php if ($row['initiated_data']['service_id'] == 'apdcl1' || $row['initiated_data']['service_id'] == '100001') : ?>

                        <tr>
                            <td>Application No.</td>
                            <td style="font-weight: bold;"><?= $row['initiated_data']['application_no'] ?? 'N/A' ?></td>
                            <td>Consumer No.</td>
                            <td style="font-weight: bold"><?= $row['initiated_data']['consNo'] ?? $row['initiated_data']['consumer_no'] ?? 'N/A' ?></td>
                        </tr>

                    <?php endif; ?>


                </tbody>
            </table>

            <?php if (!empty($row['execution_data'])) { ?>
                <table class="table table-bordered mt-2 border-info">
                    <thead>
                        <tr>
                            <th colspan="3" class="text-bold bg-info">Application processing history</th>
                        </tr>
                        <tr>
                            <th>Date &amp; Time</th>
                            <th>Task details</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($row['execution_data'] as $edata) { ?>
                            <tr>
                                <td><?= $edata['processing_time'] ?? ''  ?></td>
                                <td><?= "{$edata['action_taken']},  {$edata['processed_by']}" ?></td>
                                <td><?= $edata['remarks'] ?? '' ?></td>
                            </tr>
                        <?php } //End of foreach() 
                        ?>

                    </tbody>
                </table>
            <?php } //End of if 
            ?>

            <p class="text-md-start fw-bold text-danger my-4"><?= $this->settings->info_msg->{"$this->lang"} ?></p>

            <!-- Display Appeal, grievance, Login button  -->
            <div style="display:block;" class="text-center">
                <tr>
                    <td colspan="3" style="text-align: center;">

                        <!-- Grievance btn -->
                        <?php if (!in_array($service_id, self::MB2_SERVICES, false)) : ?>

                            <a href="<?= base_url('grm') ?>" class="btn btn-primary" target="_blank">
                                <i class="fa fa-inbox"></i> <?= $this->settings->grivance_btn->{"$this->lang"} ?>
                            </a>

                        <?php endif; ?>


                        <!-- Appeal Button -->
                        <?php if (
                            !in_array($service_id, self::MB2_SERVICES, false)
                            &&
                            (!$this->check_in_beyond_time($row))
                        ) { ?>
                            <a href="<?= base_url('appeal') ?>" class="btn btn-success" target="_blank">
                                <i class="fa fa-reply-all"></i> <?= $this->settings->appeal_btn->{"$this->lang"} ?>
                            </a>
                        <?php } //End of if 
                        ?>


                        <!-- Login button -->

                        <?php if (
                            empty($row['data']['source'])
                        ) {

                            echo '<a href=" ' . base_url("iservices") . '" class="btn rtps-btn fw-bold" target="_blank">
                            <i class="fa fa-sign-in me-1"></i>' .  $this->settings->login_btn->{"$this->lang"} . '</a>';
                        } else {
                            echo '<a href="#" class="btn rtps-btn-alt fw-bold servicePluslogin" onClick=" event.preventDefault();loadDynamicContentModal(' . $service_id . ');"><i class="fa fa-sign-in me-1"></i>' . $this->settings->login_btn->{"$this->lang"} .  '</a>';
                        }
                        ?>



                    </td>
                </tr>

            </div>



<?php        } else {
            // echo "<h2 class=\"my-4 text-capitalize text-center\" >No records found!</h2>";

            echo '<h2 class="my-4 py-3 bg-light text-muted text-capitalize text-center" >' . $row['message'] ?? '' . '</h2>';
        } //End of if else
    } //End of get_details()


    private function getstatusdetails($par = "D")
    {
        switch (strtoupper($par)) {
            case 'R':
            case 'REJECTED':
                $action = '<span class="text-danger">REJECTED</span>';
                break;
            case 'D':
            case 'DELIVERED':
                $action = '<span class="text-success">DELIVERED</span>';
                break;
            default:
                $action = '<span class="text-info">UNDER PROCESS</span>';
                break;
        }

        return $action;
    } // End of getstatusdetails()

    private function in_byond_time_check($sd_str = '', $ed_str = '', $tl = 1)
    {
        // pre([$sd_str, $ed_str, $tl]);

        $exp_date = (DateTime::createFromFormat('Y-m-d H:i:s', $sd_str))->add(new DateInterval("P{$tl}D"));
        $exec_date = DateTime::createFromFormat('Y-m-d H:i:s', $ed_str);

        return $exec_date <= $exp_date;
    }



    private function check_in_beyond_time($appl)    // True: in_time, False: beyond_time
    {
        // pre($appl);

        $sub_date = $appl['initiated_data']['submission_date'];
        $action_date = empty($appl['execution_data']) ?  $appl['initiated_data']['submission_date'] : (end($appl['execution_data']))['processing_time'] ?? '';

        // In case 'processing_time' is empty
        if (empty($action_date)) {
            $action_date =  $sub_date;
        }
        $tl = $appl['initiated_data']['service_timeline'];

        // Check Status
        switch (strtoupper($appl['initiated_data']['status'])) {
            case 'D':
            case 'DELIVERED':
            case 'R':
            case 'REJECTED':

                // for both deliver & reject
                return $this->in_byond_time_check($sub_date, $action_date, $tl);

            default:
                // pending
                return $this->in_byond_time_check($sub_date, date('Y-m-d H:i:s'), $tl);
        }
    }


    public function get_latest_app_status($ref_no = '')
    {
        // $ref_no = 'RTPS-CASTE/2022/9999766';
        // RTPS-BAKCL/2022/00838, RTPS-CASTE/2022/9999766, AS21100743822021, RTPS-RREA/2022/30424, RTPS-MCC/2023/0592832
        // RTPS-MRG/2020/00218, NOC/21/10706/2021, RTPS/SOT/2022/1300, RTPS/SAPH/2022/2453

        // curl muti request, i.e. simultaneous curl calls

        $multi_handle = curl_multi_init();
        $handles = [];

        foreach ($this->urls as $key => $value) {
            $handle = curl_init();
            curl_setopt_array($handle, array(
                CURLOPT_URL => $value->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT_MS => 7000,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,    // disable SSL certificate verification
                CURLOPT_SSL_VERIFYHOST => false,    // disable hostname verification
                CURLOPT_POSTFIELDS => json_encode(array(
                    'app_ref_no' => $ref_no,
                    'secret' => $value->secret,
                )),
                CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            ));

            if (!empty($value->header_token)) {

                curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer {$value->header_token}"));
            }

            $handles[] = $handle;

            curl_multi_add_handle($multi_handle, $handle);
        }

        // execute the requests in parallel
        do {
            curl_multi_exec($multi_handle, $running);
        } while ($running);

        if (curl_multi_errno($multi_handle)) {
            return [
                'status' => false,
                'message' => $this->settings->error_msg->api->{"$this->lang"},
            ];
        }

        // retrieve the response data from each handle
        $responses = [];
        foreach ($handles as $handle) {
            $response = curl_multi_getcontent($handle);

            // $responses[] = $response;

            // For only success response
            if (!curl_errno($handle) && curl_getinfo($handle, CURLINFO_HTTP_CODE) <= 300) {
                $responses[] = json_decode($response, true);
            }
            // pre([curl_errno($handle), curl_getinfo($handle, CURLINFO_HTTP_CODE), $response]);
        }

        // close the handles and multi-handle
        foreach ($handles as $handle) {
            curl_multi_remove_handle($multi_handle, $handle);
            curl_close($handle);
        }
        curl_multi_close($multi_handle);

        // pre($responses);

        // do something with the response data
        // Return the first response data with status true

        foreach ($responses as $key => $value) {
            if (!empty($value['status'])) {
                return $value;
            }
        }

        return [
            'status' => false,
            'message' => $this->settings->error_msg->not_found->{"$this->lang"},
        ];
    }
} //End of Trackappstatus 
?>