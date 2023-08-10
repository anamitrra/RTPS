<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Fetch_gmda_applications extends frontend
{
    private $serviceName = "Building Permission upto Ground+2 storied under Mukhya Mantrir Sohoj Griha Nirman Achoni";
    private $serviceId = "PPBP";
    private $departmentId = "810";
    private $departmentName = "Department of Housing and Urban Affairs";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('income/track_model');
        $this->load->model('income/income_model');
        $this->load->model('buildingpermission/registration_model');
        $this->load->config('spconfig');
        $this->load->helper("log");
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


    public function retrive_gmda_applications()
    {
        // pre('From CMD');
        // php .\cli.php  spservices/Trackapplication retrive_edist_applications

        $CI = &get_instance();
        $collection = 'gmda_pending_applications';
        $operations = array(
            array(
                '$match' => array(
                    "insert_data" => array('$exists' => false),
                )
            ),
            array(
                '$project' => [
                    'ref_no' => 1,
                    'application_id' => 1,
                    'submission_date' => 1,
                    'sign_no' => 1,
                    'mobile_no' => 1
                ]
            )
        );
        //pre($operations);
        $dbrows = $CI->mongo_db->aggregate($collection, $operations);
        if (empty($dbrows)) {
            return false;
        }
        $total_appl = sizeof((array) $dbrows);
        // pre($dbrows);

        echo "Total data to check: {$total_appl}" . PHP_EOL;

        foreach ($dbrows as $document) {
            $rtps_ref_no = $document->ref_no;
            $sign_no = $document->sign_no;
            $mobile_no = $document->mobile_no;
            $data_to_update = array();
            echo "Working on {$rtps_ref_no}" . PHP_EOL;
            $certificate = $this->check_status($rtps_ref_no, $sign_no, $mobile_no);
            if ($certificate) {

                    $data_to_update['insert_data'] = true;

            } else {
                $data_to_update['insert_data'] = false;
            }
            // $CI->mongo_db->set($data_to_update);
            // $CI->mongo_db->where(array('ref_no' => $rtps_ref_no));
            // $CI->mongo_db->update('gmda_pending_applications');

            $total_appl--;
            echo "Total remainings: {$total_appl}" . PHP_EOL;
            echo PHP_EOL;
            //break;
        }

    }

    //Check eDistrict application status
    private function check_status($obj_id = null, $sp_sign_no = null, $sp_mobile_no = null)
    {
        //$obj_id = "RTPS-PPBP/2022/00091";
        $token = "|0VW?z,-w2w\"6;{b8v}K6A5+Fdf@l-";
        $ch = curl_init('https://sewasetu.assam.gov.in/tools/rtps_id_labels/src/api/external_apis.php/get_track_data?appl_ref_no=' . $obj_id . '&service_id=1886');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Accept: application/json",
            'Authorization: Bearer ' . $token
        ));
        $data = curl_exec($ch);
        curl_close($ch);
        // pre($data);
        if (!empty($data)) {
            $user_record = json_decode($data);
            $objectId = "";
            if (is_object($user_record->data)) {
                $appl_ref_no = $this->getID(8);

                $owner_details = array();
                $owner_detail = $user_record->data->initiated_data->attribute_details->ownership_information;
                if (count($owner_detail) > 0) {
                    foreach ($owner_detail as $key => $value) {

                        $owner_detail1 = array(
                            "owner_name" => !empty($value->owner_name) ? $value->owner_name : "",
                            "owner_gender" => !empty($value->owner_gender) ? $value->owner_gender : "",
                        );
                        $owner_details[] = $owner_detail1;
                    } //End of foreach()        
                }

                // if ($this->slug === "CSC") {
                //     $apply_by = $sessionUser['userId'];
                // } else {
                //     $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
                // }
                $apply_by = '';
                //Generate appl_id
                while (1) {
                    $appl_id = rand(1000000000, 9999999999);
                    $filter = array(
                        "service_data.appl_id_new" => $appl_id,
                    );
                    $rows = $this->registration_model->get_row($filter);
                    if ($rows === false) break;
                }
                $submission_date = DateTime::createFromFormat('d-m-Y H:i:s', $user_record->data->initiated_data->submission_date, new DateTimeZone('UTC'));

                $service_data = [
                    "department_id" => $this->departmentId,
                    "department_name" => $this->departmentName,
                    "service_id" => $this->serviceId,
                    "service_name" => $this->serviceName,
                    "appl_id" => $user_record->data->initiated_data->appl_id,
                    "appl_ref_no" => $user_record->data->initiated_data->appl_ref_no,
                    "submission_mode" => "SAVE", //kiosk, online, in-person
                    "applied_by" => !empty($user_record->data->initiated_data->attribute_details->applied_by) ? $user_record->data->initiated_data->attribute_details->applied_by : "",
                    "submission_location" => "Guwahati Metropolitan Development Authority", // office name
                    "submission_date" => new MongoDB\BSON\UTCDateTime($submission_date->getTimestamp() * 1000),
                    "service_timeline" => "30",
                    "appl_status" => "submitted",
                    "district" => "KAMRUP METRO",
                    "appl_id_new" => $appl_id,
                    "appl_ref_no_new" => $appl_ref_no,
                    "sp_sign_no" => $sp_sign_no,
                    "sp_mobile_no"=> $sp_mobile_no,
                    "gmda_pending_application"=> true

                ];

                $form_data = [
                    'application_type' => !empty($user_record->data->initiated_data->attribute_details->application_type) ? $user_record->data->initiated_data->attribute_details->application_type : "",
                    'case_type' => "",
                    'ertp' => !empty($user_record->data->initiated_data->attribute_details->do_you_have_any_technical__person_to_assist_other_than_ertp) ? strtolower($user_record->data->initiated_data->attribute_details->do_you_have_any_technical__person_to_assist_other_than_ertp) : "",
                    'any_old_permission' => !empty($user_record->data->initiated_data->attribute_details->do_you_have_any_old_permission) ? strtolower($user_record->data->initiated_data->attribute_details->do_you_have_any_old_permission) : "",
                    'technical_person_name' => !empty($user_record->data->initiated_data->attribute_details->technical_person_name) ? $user_record->data->initiated_data->attribute_details->technical_person_name : "",
                    'old_permission_no' => !empty($user_record->data->initiated_data->attribute_details->old_permission_no) ? $user_record->data->initiated_data->attribute_details->old_permission_no : "",
                    'district_emp_tech' => "19",
                    'district_emp_tech_name' => "KAMRUP (METRO)",
                    'empanelled_reg_tech_person' => "NA",
                    'empanelled_reg_tech_person_name' => !empty($user_record->data->initiated_data->attribute_details->empanelled_registered_technical_person) ? $user_record->data->initiated_data->attribute_details->empanelled_registered_technical_person : "",
                    'district' => "",
                    'district_name' => "",
                    'house_no' => !empty($user_record->data->initiated_data->attribute_details->house_no_landmark) ? $user_record->data->initiated_data->attribute_details->house_no_landmark : "",
                    'mst_pln_dev_auth' => "",
                    'mst_pln_dev_auth_name' => "",
                    'name_of_road' => !empty($user_record->data->initiated_data->attribute_details->name_of_road) ? $user_record->data->initiated_data->attribute_details->name_of_road : "",
                    'panchayat_ulb' => "",
                    'panchayat_ulb_name' => "",
                    'site_pin_code' => "",
                    'revenue_village' => "",
                    'revenue_village_name' => "",
                    'old_dag_no' => !empty($user_record->data->initiated_data->attribute_details->dag_no) ? $user_record->data->initiated_data->attribute_details->dag_no : "",
                    'ward_no' => "",
                    'ward_no_name' => "",
                    'new_dag_no' => !empty($user_record->data->initiated_data->attribute_details->new_dag_no) ? $user_record->data->initiated_data->attribute_details->new_dag_no : "",
                    'mouza' => "",
                    'mouza_name' => "",
                    'old_patta_no' => !empty($user_record->data->initiated_data->attribute_details->patta_no) ? $user_record->data->initiated_data->attribute_details->patta_no : "",
                    'new_patta_no' => !empty($user_record->data->initiated_data->attribute_details->new_patta_no) ? $user_record->data->initiated_data->attribute_details->new_patta_no : "",

                    'applicant_name' => !empty($user_record->data->initiated_data->attribute_details->applicant_name) ? $user_record->data->initiated_data->attribute_details->applicant_name : "",
                    'applicant_gender' => "",
                    'father_name' => !empty($user_record->data->initiated_data->attribute_details->fathers_name) ? $user_record->data->initiated_data->attribute_details->fathers_name : "",
                    'mother_name' => !empty($user_record->data->initiated_data->attribute_details->mother_name) ? $user_record->data->initiated_data->attribute_details->mother_name : "",
                    'spouse_name' => !empty($user_record->data->initiated_data->attribute_details->spouse_name) ? $user_record->data->initiated_data->attribute_details->spouse_name : "",
                    'permanent_address' => !empty($user_record->data->initiated_data->attribute_details->permanent_address) ? $user_record->data->initiated_data->attribute_details->permanent_address : "",
                    'pin_code' => !empty($user_record->data->initiated_data->attribute_details->pin_code) ? $user_record->data->initiated_data->attribute_details->pin_code : "",
                    'mobile' => (string) $sp_mobile_no,
                    'applicant_mobile' => !empty($user_record->data->initiated_data->attribute_details->mobile_number) ? $user_record->data->initiated_data->attribute_details->mobile_number : "",
                    'monthly_income' => !empty($user_record->data->initiated_data->attribute_details->monthly_income) ? $user_record->data->initiated_data->attribute_details->monthly_income : "",
                    'pan_no' => !empty($user_record->data->initiated_data->attribute_details->pan_number) ? $user_record->data->initiated_data->attribute_details->pan_number : "",
                    'email' => "",
                    'owner_details' => $owner_details,
                    'user_id' => '',
                    'user_type' => 'user',
                    'service_type' => "PPBP",
                    'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                ];
                $processing_history = array();
                $processing_history[] = array(
                    "processed_by" => "Application submitted by ".$user_record->data->initiated_data->attribute_details->applicant_name,
                    "action_taken" => "Application Submition",
                    "remarks" => "Application submitted successfully",
                    "processing_time" => new MongoDB\BSON\UTCDateTime($submission_date->getTimestamp() * 1000),
                );

                $inputs = [
                    'service_data' => $service_data,
                    'form_data' => $form_data,
                    'processing_history' => $processing_history
                ];
                // var_dump($inputs);
                $insert = $this->registration_model->insert($inputs);
                if ($insert) {
                    echo 'Saved successfully!';
                    return;
                    ///$objectId = $insert['_id']->{'$id'};
                    // $filter_new = array(
                    //     "_id" => $objectId ? new ObjectId($objectId) : NULL
                    // );
                    // $user_record = $this->registration_model->get_row($filter_new);
                    //$this->index($objectId);
                    //return;
                } else {
                    echo 'Unable to save!';
                    return;
                }
            } else {
                echo 'No record found';
                return;
            }
        } else {
            echo 'No record found';
            return;
        }
    }


    function getID($length) {
        $rtps_trans_id = $this->generateID($length);
        while ($this->registration_model->get_row(["service_data.appl_ref_no" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        }//End of while()
        return $rtps_trans_id;
    }//End of getID()

    public function generateID($length) {
        $date = date('Y');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-PPBP/" . $date."/" .$number;
        return $str;
    } //End of generateID()
}//End of reinitiate_applications
