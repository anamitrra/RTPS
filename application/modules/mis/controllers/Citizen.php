<?php
require_once APPPATH."/third_party/libs/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Citizen extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('citizen_info_access_log_model');
    }

    public function index(){
//        $x = ["ARCS Sub Div Office(ARCS - Sub Distt Office- ARCS - Gossaigaon )",
//		"ARCS Sub Div Office(ARCS - Sub Distt Office- ARCS - Kokrajhar )",
//		"ARCS Sub Div Office(ARCS - Sub Distt Office- ARCS - Udalguri )",
//		"Addl RCS - BTAD",
//		"Circle Offices( SUBDISTRICT - Boitamari )",
//		"Circle Offices( SUBDISTRICT - Dalgaon (Pt) )",
//		"Circle Offices( SUBDISTRICT - Nagaon )",
//		"Circle Offices( SUBDISTRICT - North Guwahati (Pt) )",
//		"Circle Offices( SUBDISTRICT - Palasbari )",
//		"Circle Offices( SUBDISTRICT - Ramkrishna Nagar )",
//		"Circle Offices( SUBDISTRICT - Rangia Pt )",
//		"Circle Offices( SUBDISTRICT - SOUTH SALMARA MANKACHAR )",
//		"LO Office(Kokrajhar )",
//		"Labour Inspector(Gossaigaon )",
//		"Labour Inspector(Khoirabari )",
//		"Labour Inspector(Majbat )",
//		"Municipal Corporation(Guwahati )",
//		"Revenue Office(Revenue Office- Diyungbra )",
//		"Revenue Office(Revenue Office- Harangajao )",
//		"Revenue Office(Revenue Office- Mahur )",
//		"Revenue Office(Revenue Office- Maibang )",
//		"Revenue Office(Revenue Office- Umrangso )",
//		"Sub Districts L II( SUBDISTRICT - Agamoni )",
//		"Sub Districts L II( SUBDISTRICT - Algapur )",
//		"Sub Districts L II( SUBDISTRICT - Azara )",
//		"Sub Districts L II( SUBDISTRICT - Bongaigaon (Pt) )",
//		"Sub Districts L II( SUBDISTRICT - Chabua )",
//		"Sub Districts L II( SUBDISTRICT - Chandrapur )",
//		"Sub Districts L II( SUBDISTRICT - Dhubri )",
//		"Sub Districts L II( SUBDISTRICT - Dispur )",
//		"Sub Districts L II( SUBDISTRICT - Golaghat )",
//		"Sub Districts L II( SUBDISTRICT - Guwahati )",
//		"Sub Districts L II( SUBDISTRICT - Lanka )",
//		"Sub Districts L II( SUBDISTRICT - Majuli )",
//		"Sub Districts L II( SUBDISTRICT - Mankachar )",
//		"Sub Districts L II( SUBDISTRICT - Margherita )",
//		"Sub Districts L II( SUBDISTRICT - Na-Duar )",
//		"Sub Districts L II( SUBDISTRICT - Nalbari )",
//		"Sub Districts L II( SUBDISTRICT - Sadiya )",
//		"Sub Districts L II( SUBDISTRICT - Srijangram )",
//		"Sub Districts L II( SUBDISTRICT - Tengakhat )",
//		"Sub Districts L II( SUBDISTRICT - Tinsukia )",
//		"Sub Divisions(Sub-Division- Biswanath )",
//		"Sub Divisions(Sub-Division- Dhubri Sadar )",
//		"Sub Divisions(Sub-Division- Diphu )",
//		"Sub Divisions(Sub-Division- Guwahati )",
//		"Sub Divisions(Sub-Division- Hailakandi )",
//		"Sub Divisions(Sub-Division- Hojai )",
//		"Sub Divisions(Sub-Division- North Salmara )",
//		"Sub Registrar Office(Sub Registrar- Abhayapuri )",
//		"Sub Registrar Office(Sub Registrar- Belsor )",
//		"Sub Registrar Office(Sub Registrar- Bilashipara )",
//		"Sub Registrar Office(Sub Registrar- Boko )",
//		"Sub Registrar Office(Sub Registrar- Bongaigaon )",
//		"Sub Registrar Office(Sub Registrar- Dalgaon )",
//		"Sub Registrar Office(Sub Registrar- Dhekiajuli )",
//		"Sub Registrar Office(Sub Registrar- Dhemaji )",
//		"Sub Registrar Office(Sub Registrar- Dhubri )",
//		"Sub Registrar Office(Sub Registrar- Dibrugarh )",
//		"Sub Registrar Office(Sub Registrar- Golaghat )",
//		"Sub Registrar Office(Sub Registrar- Guwahati )",
//		"Sub Registrar Office(Sub Registrar- Hojai )",
//		"Sub Registrar Office(Sub Registrar- Jorhat )",
//		"Sub Registrar Office(Sub Registrar- Kaliabor )",
//		"Sub Registrar Office(Sub Registrar- Kamrup Sadar )",
//		"Sub Registrar Office(Sub Registrar- Karimganj )",
//		"Sub Registrar Office(Sub Registrar- Lakhipur )",
//		"Sub Registrar Office(Sub Registrar- Mangaldoi )",
//		"Sub Registrar Office(Sub Registrar- Margherita )",
//		"Sub Registrar Office(Sub Registrar- Morigaon )",
//		"Sub Registrar Office(Sub Registrar- Nagaon )",
//		"Sub Registrar Office(Sub Registrar- Nagaon Deputy )",
//		"Sub Registrar Office(Sub Registrar- Patharkandi )",
//		"Sub Registrar Office(Sub Registrar- R K Nagar )",
//		"Sub Registrar Office(Sub Registrar- Sivasagar )",
//		"Sub Registrar Office(Sub Registrar- Tezpur )",
//		"Veterinary Branch(Veterinary Branch- Veterinary Branch Lakhtokia )"
//	];
//        $locations = [];
//        foreach ($x as $a){
//            $locations[] = [
//                'location_id' => mt_rand(1000,1099),
//                'location_name' => $a,
//                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
//            ];
//        }
//        $this->mongo_db->batch_insert('locations',$locations);
        $this->load->model('location_model');
        $this->load->model('services_model');
        // store audit trail
        $citizen_info_log = [
            'user_id'           => new ObjectId($this->session->userdata('userId')->{'$id'}),
            'name'              => $this->session->userdata('name'),
            'client_ip'         => $this->input->ip_address(),
            'client_user_agent' => $this->input->user_agent(),
            'uri'               => $this->uri->uri_string,
            'method'            => $this->input->method(),
            'data'              =>  'NA',
            'created_at'        => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
        ];
        $this->citizen_info_access_log_model->insert($citizen_info_log);

        $locationList = $this->location_model->get_where([]);
        $serviceList = $this->services_model->all([]);
        $data = [
            'locationList' => $locationList,
            'serviceList'=>$serviceList
        ];
        $this->load->view('includes/header',['pageTitle' => 'MIS | Citizen Information Report']);
        $this->load->view('citizen/index',$data);
        $this->load->view('includes/footer');
    }

    /**
     * @return array
     */
    public function get_records(){
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $submission_location = $this->input->post('submission_location');
        $gender = $this->input->post('gender');
        $application_status = $this->input->post('application_status');
        $mode_of_application_filter = $this->input->post('mode_of_application');
        $service_id_filter = $this->input->post('service_id');
        $columns = array(
            0 => "initiated_data.attribute_details.applicant_name",
            1 => "initiated_data.attribute_details.applicant_gender",
            2 => "initiated_data.attribute_details.mobile_number",
            3 => "initiated_data.attribute_details.e-mail",
            4 => "initiated_data.service_name",
            5 => "initiated_data.submission_date",
            6 => "execution_data.0.official_form_details.action"
        );
        // store audit trail
        $citizen_info_log = [
            'user_id'           => new ObjectId($this->session->userdata('userId')->{'$id'}),
            'name'              => $this->session->userdata('name'),
            'client_ip'         => $this->input->ip_address(),
            'client_user_agent' => $this->input->user_agent(),
            'uri'               => $this->uri->uri_string,
            'method'            => $this->input->method(),
            'data'              => json_encode($this->input->post()),
            'created_at'        => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
        ];
        $this->citizen_info_access_log_model->insert($citizen_info_log);
        $matchArray = [];
        if($submission_location){
            $matchArray[]['initiated_data.submission_location'] = $submission_location;
        }
        if($mode_of_application_filter){
            $matchArray[]['initiated_data.submission_mode'] = $mode_of_application_filter;
        } 
        if($service_id_filter){
            $matchArray[]['initiated_data.base_service_id'] = $service_id_filter;
        }
        if($gender){
            if($gender === 'Unknown'){
                $matchArray[]['initiated_data.attribute_details.applicant_gender'] = ['$exists' => false];
            }else{
                $matchArray[]['initiated_data.attribute_details.applicant_gender'] = ['$regex' => '^' . $gender . '', '$options' => 'i'];
            }
        }
        if($application_status){
            if($application_status === 'under_process'){
                $matchArray[]['execution_data.0.official_form_details.action'] = ['$nin' => ['Reject','Deliver']];
            }else{
                $matchArray[]['execution_data.0.official_form_details.action'] = $application_status;
            }
        }
        if($startDate && $endDate){
            $matchArray[]["initiated_data.submission_date"] = [
                "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($startDate) * 1000),
                "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($endDate)) * 1000)
            ];
        }
        $projectionArray = [
            'initiated_data.appl_ref_no' => 1,
            'initiated_data.submission_mode' => 1,
            'initiated_data.submission_location' => 1,
            'initiated_data.submission_date' => 1,
            'initiated_data.attribute_details.applicant_name' => 1,
            'initiated_data.attribute_details.applicant_gender' => 1,
            'initiated_data.attribute_details.mobile_number' => 1,
            'initiated_data.attribute_details.e-mail' => 1,
            'initiated_data.service_name' => 1,
            'execution_data' => 1
        ];
        $limit = $this->input->post("length", TRUE);
        $start = $this->input->post("start", TRUE);
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $orderArray = [$order => $dir === 'asc' ? 1 : -1];
        $this->load->model('applications_model');
        $totalData = $this->applications_model->total_rows();
        if (empty($this->input->post("search")["value"])) {
//          echo 'if';
            $records = $this->applications_model->get_filtered_citizen($projectionArray,$matchArray,[],$start,$limit,$orderArray);
        } else {
//          echo 'else';
            $search = trim($this->input->post("search")["value"]);
            $searchArray = [
                'initiated_data.attribute_details.applicant_name' => $search,
                'initiated_data.attribute_details.applicant_gender' => $search,
                'initiated_data.attribute_details.mobile_number' => $search,
                'initiated_data.attribute_details.e-mail' => $search,
                'initiated_data.service_name' => $search,
            ];
            $records = $this->applications_model->get_filtered_citizen($projectionArray,$matchArray,$searchArray,$start,$limit,$orderArray);
        }
        $totalFiltered = count((array)$this->applications_model->get_filtered_citizen($projectionArray,$matchArray));
        $this->session->set_userdata('citizen_info_match_array',$matchArray);
//      pre($totalFiltered);
        $data = array();
        if (!empty($records)) {
            $sl_no = 0;
            foreach ($records as $objs) {
                $row = $objs->initiated_data;
                $nestedData["#"] = ++$sl_no;
                $nestedData["applicant_name"]     = $row->attribute_details->applicant_name;
                $nestedData["applicant_gender"]   = $row->attribute_details->applicant_gender ?? 'Unknown';
                $nestedData["mobile_number"]      = $row->attribute_details->mobile_number;
                $nestedData["email"]              = $row->attribute_details->{'e-mail'} ?? 'NA';
                $nestedData["service_name"]       = $row->service_name;
                $nestedData["submission_date"]    = format_mongo_date($row->submission_date,'d-m-Y g:i a');
                if(!empty($objs->execution_data)){
                    
                    if(!empty($objs->execution_data[0]->official_form_details->action)){
                        $application_status_res = !in_array($objs->execution_data[0]->official_form_details->action,['Reject','Deliver']) ? 'Under Process' : $objs->execution_data[0]->official_form_details->action;
                    }else{
                        $application_status_res = 'initiated';
                    }
                    
                }else{
                    $application_status_res = 'initiated';
                }
                $nestedData["application_status"] = $application_status_res;
                switch($row->submission_mode){
                    case 'kiosk':
                        $modeOfApplication = 'PFC/CSC';
                        break;
                    case 'in person':
                        $modeOfApplication = 'Concerned Office';
                        break;
                    default:
                        $modeOfApplication = 'Self';
                        break;
                }
                $nestedData["mode_of_application"] = $modeOfApplication;
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        echo json_encode($json_data);
    }

    public function view_access_log(){
        $this->load->view('includes/header',['pageTitle' => 'MIS | Citizen Information Access Log']);
        $this->load->view('citizen/access_log');
        $this->load->view('includes/footer');
    }

    public function get_access_log(){

        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $matchArray = [];
        if($startDate && $endDate){
            $matchArray[]['created_at'] = [
                "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($startDate) * 1000),
                "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($endDate)) * 1000)
            ];
        }
//pre($matchArray);
        $columns = array(
            0 => "name",
            1 => "client_ip",
            2 => "client_user_agent",
            3 => "uri",
            4 => "method",
            5 => "data",
            6 => "created_at"
        );
        $limit = $this->input->post("length", TRUE);
        $start = $this->input->post("start", TRUE);
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $this->load->model('applications_model');
        $totalData = $this->citizen_info_access_log_model->total_rows();
        $filter = [];
        if (empty($this->input->post("search")["value"])) {
//          echo 'if';
            $records = $this->citizen_info_access_log_model->search_rows($limit, $start, $matchArray, $order, $dir);
        } else {
//          echo 'else';
            $search = trim($this->input->post("search")["value"]);

            $search = ['$regex' => '^' . $search . '','$options' => 'i'];
            $searchArray['$or'] = [
                ['name'              => $search],
                ['client_ip'         => $search],
                ['client_user_agent' => $search],
                ['uri'               => $search],
                ['method'            => $search],
                ['data'              => $search],
                ['created_at'        => $search],
            ];
            if(!empty($matchArray)){
                $matchArray = array_merge($searchArray,$matchArray);
            }else{
                $matchArray = $searchArray;
            }
            $records = $this->citizen_info_access_log_model->search_rows($limit, $start, $matchArray, $order, $dir);
        }
        if(!empty($matchArray)) {
            $totalFiltered = count((array)$this->citizen_info_access_log_model->get_where($matchArray));
        }else{
            $totalFiltered = count((array)$this->citizen_info_access_log_model->search_rows($limit, $start, [], $order, $dir));
        }
        $data = array();
        if (!empty($records)) {
            $sl_no = 0;
            foreach ($records as $row) {
                $nestedData["#"]          = ++$sl_no;
                $nestedData["name"]       = $row->name;
                $nestedData["ip"]         = $row->client_ip;
                $nestedData["user_agent"] = $row->client_user_agent;
                $nestedData["uri"]        = $row->uri;
                $nestedData["method"]     = $row->method;
                $nestedData["data"]       = $row->data;
                $nestedData["created_at"] = format_mongo_date($row->created_at,'d-m-Y g:i a');
                $data[]                   = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        echo json_encode($json_data);
    }

    public function download_excel(){
        $this->load->model('applications_model');
        $matchArray = $this->session->userdata('citizen_info_match_array');
//        pre($matchArray);
        if(!empty($matchArray)){
            $records = $this->applications_model->get_filtered(['$and' => $matchArray]);
        }else{
            $records = $this->applications_model->get_all([]);
        }
        $this->session->unset_userdata('citizen_info_match_array');

        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Name of the applicant');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Gender');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Phone Number');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Email ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Name of service Applied');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Date of application');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Status of the application');
        // set Row
        $rowCount = 2;
        foreach ($records as $objs) {
            $row = $objs->initiated_data;
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->attribute_details->applicant_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $row->attribute_details->applicant_gender ?? 'Unknown');
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row->attribute_details->mobile_number);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->attribute_details->{'e-mail'} ?? 'NA');
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $row->service_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, format_mongo_date($row->submission_date,'d-m-Y g:i a'));
            if(!empty($objs->execution_data) && isset($objs->execution_data[0]->official_form_details) && isset($objs->execution_data[0]->official_form_details->action)){
                $application_status_res = !in_array($objs->execution_data[0]->official_form_details->action,['Reject','Deliver']) ? 'Under Process' : $objs->execution_data[0]->official_form_details->action;
            }else{
                $application_status_res = 'initiated';
            }
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $application_status_res);
            $rowCount++;
        }

        $fileName = 'Citizen Info Report.xlsx';
        $writer = new Xlsx($objPHPExcel);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $fileName.'"');
        $writer->save('php://output');
    }
}