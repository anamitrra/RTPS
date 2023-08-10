<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Thirdpartyverification extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
        $this->load->model('settings_model');
        $this->load->model('site_model');
    } //End of __construct()

    public function index()
    {
        $data['settings'] = $this->settings_model->get_settings('thirdpartyverification');
        $this->render_view_new('thired_party_verification', $data, array());
    } //End of index()

    public function get_details()
    {
        $ref_no = trim($this->input->post('ref_no', true));  //EPASS/2020/43008

        $this->mongo_db->switch_db('mis');
        $row = $this->mongo_db->where(array('initiated_data.appl_ref_no' => $ref_no))->get('applications')->{'0'} ?? null;

        // pre($row);

        if ($row) {
            $date_of_submission = $this->mongo_db->getDateTime($row->initiated_data->submission_date);
            $applicant_name = $row->initiated_data->attribute_details->applicant_name;
            $service_name = $row->initiated_data->service_name;
            $mobile_number = $row->initiated_data->attribute_details->mobile_number;
            $email = $row->initiated_data->attribute_details->{'e-mail'} ?? '';
            $gender = $row->initiated_data->attribute_details->applicant_gender;
            $current_status = $this->getstatusdetails($row->initiated_data->appl_status);
            $stipulated_delivery_date = date('d-m-Y', strtotime($date_of_submission . ' + ' . $row->initiated_data->service_timeline . ' days'));
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
                        <td>Mobile Number</td>
                        <td style="font-weight: bold;"><?= $mobile_number ?></td>
                        <td>Email</td>
                        <td style="font-weight: bold"><?= $email ?></td>

                    </tr>

                    <tr>

                        <td>Gender</td>
                        <td style="font-weight: bold"><?= $gender ?></td>
                        <td>Stipulated Delivery Date</td>
                        <td style="font-weight: bold;"><?= $stipulated_delivery_date ?></td>
                    </tr>

                    <tr>

                        <td colspan="2">Current Status</td>
                        <td colspan="2" style="font-weight: bold"><?= $current_status ?></td>
                    </tr>
                </tbody>
            </table>

            <?php if ($row->execution_data ?? null) { ?>
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
                        <?php foreach ($row->execution_data as $edata) { ?>
                            <tr>
                                <td><?= (isset($edata->task_details->executed_time)) ? $this->mongo_db->getDateTime($edata->task_details->executed_time) : ((isset($edata->task_details->received_time)) ? $this->mongo_db->getDateTime($edata->task_details->received_time) : '')  ?></td>
                                <td><?= $edata->task_details->task_name ?? '' ?></td>
                                <td><?= $edata->official_form_details->remarks ?? $edata->official_form_details->remark ?? '' ?></td>
                            </tr>
                        <?php } //End of foreach() 
                        ?>

                    </tbody>
                </table>
<?php } //End of if
        } else {
            echo '';
        } //End of if else
    } //End of get_details()

    private function getstatusdetails($par = "D")
    {
        switch ($par) {
            case 'R':
                $action = '<span class="text-danger">REJECTED</span>';
                break;
            case 'D':
                $action = '<span class="text-success">DELIVERED</span>';
                break;
            default:
                $action = '<span class="text-info">UNDER PROCESS</span>';
                break;
        } //End of getstatusdetails()
        return $action;
    } // End of getstatusdetails()
}//End of Trackappstatus
