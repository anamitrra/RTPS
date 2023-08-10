<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Apptracker extends Upms {

    public function __construct() {
        parent::__construct();
        $this->isloggedin();
        $this->load->model('upms/applications_model'); 
        $this->load->helper("appstatus");
    }//End of __construct()
  
    public function index() {
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : Track status"]);
        $this->load->view('upms/apptracker_view');
        $this->load->view('upms/includes/footer');
    }//End of index()
    
    public function get_details() {
        $searchBy = $this->input->post("search_by");
        $dbRows = $this->applications_model->get_rows(array("service_data.appl_ref_no" => $searchBy));        
        if($dbRows) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Submission Date</th>
                        <th>Service Name</th>
                        <th>Applicant Name</th>
                        <th>Application Ref No</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="">
                    <?php
                    foreach ($dbRows as $row):
                        $obj_id = $row->_id->{'$id'};
                        $appl_ref_no = $row->service_data->appl_ref_no??'';
                        $service_name = $row->service_data->service_name??'';
                        $appl_status = $row->service_data->appl_status??'';
                        $submission_date = $row->service_data->submission_date??'';
                        $applicant_name = $row->form_data->applicant_name??''; ?>
                        <tr>
                            <td><?=strlen($submission_date) ? $submission_date : "--"?></td>
                            <td><?=$service_name?></td>
                            <td><?=$applicant_name?></td>
                            <td><?=$appl_ref_no?></td>
                            <td><?=getstatusname($appl_status)?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <pre style="background-color: #444; color:#fff;"><?=json_encode($dbRows, JSON_PRETTY_PRINT)?></pre>
        <?php } else {
            echo '<h2 class="text-center">No records found</2>';
        }//End of if else
    }//End of get_details()
}//End of Apptracker