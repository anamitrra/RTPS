<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Trackapplication extends frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('income/track_model');
        $this->load->model('income/income_model');
    } //End of __construct()

    public function index($objId = null)
    {
        //pre($this->input->GET('appl_ref_no'));
        $dbRow = $this->income_model->get_row(["service_data.appl_ref_no" => $this->input->GET('appl_ref_no')]);
        if (!empty($dbRow)) {
            pre($dbRow);
        } else {
            pre('No records found against object id : ' . $objId);
        } //End of if else
    } //End of index()


    public function accessLog($applId = null)
    {
        $data = array(
            "app_ref_no" => $applId,
        );
        $dbRows = $this->track_model->get_rows($data);
        if (!empty($dbRows)) {
            pre($dbRows);
        } else {
            pre('No records found against appl. id : ' . $applId);
        } //End of if else
    } //End of track()



    public function response($applId = null)
    {
        $file_name = "DRAFT_ID_REQUEST";
        $json = file_get_contents('php://input');
        $myfile = fopen("storage/docs/".$file_name.".txt", "a") or die("Unable to open file!");
		fwrite($myfile, $json);
		fclose($myfile);

    } //End of track()





}//End of Castecertificate
