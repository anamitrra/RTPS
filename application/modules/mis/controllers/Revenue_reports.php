<?php
require_once APPPATH."/third_party/libs/autoload.php";
require_once APPPATH."/third_party/simple_html_dom.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Revenue_reports extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('citizen_info_access_log_model');
        $this->load->model('kiosk_mapping_model');
    }

    public function index(){
        $this->load->model('location_model');
        $this->load->model('services_model');

        $locationList = $this->location_model->get_where([]);
        $serviceList = $this->services_model->all([]);
        $data = [
            'locationList' => $locationList,
            'serviceList'=>$serviceList
        ];
        $this->load->view('includes/header',['pageTitle' => 'MIS | Revenue Report']);
        $this->load->view('revenue/index',$data);
        $this->load->view('includes/footer');
    }
    
    //for citizen

    public function citizen(){
        $this->load->model('location_model');
        $this->load->model('services_model');

        $locationList = $this->location_model->get_where([]);
        $serviceList = $this->services_model->all([]);
        $data = [
            'locationList' => $locationList,
            'serviceList'=>$serviceList
        ];
        $this->load->view('includes/header',['pageTitle' => 'MIS | Citizen Payment Report']);
        $this->load->view('revenue/citizen',$data);
        $this->load->view('includes/footer');
    }

    public function citizen_get_records(){
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $submission_location = $this->input->post('submission_location');
        $payment_mode = $this->input->post('payment_mode');
        $service_id = $this->input->post('service_id');
        // $gender = $this->input->post('gender');
        // $application_status = $this->input->post('application_status');
        $columns = array(
            0 => "#",
            1 => "#",
            2 => "#",
            3 => "#",
            4 => "#",
            5 => "initiated_data.submission_date",
            6 => "#",
            7 => "#",
            8 => "#",
            9 => "#",
            10 => "#",
            11 => "#",
            12 => "#",
            13 => "#",
        );
      
        $matchArray = [];
       
        if($submission_location){
            $matchArray[]['initiated_data.submission_location'] = $submission_location;
        }
         if($payment_mode ){
            $matchArray[]['initiated_data.payment_mode'] = $payment_mode;
        } 
        if($service_id ){
            $matchArray[]['initiated_data.base_service_id'] = strval($service_id);
        }
    
        // if($application_status){
        //     if($application_status === 'under_process'){
        //         $matchArray[]['execution_data.0.official_form_details.action'] = ['$nin' => ['Reject','Deliver']];
        //     }else{
        //         $matchArray[]['execution_data.0.official_form_details.action'] = $application_status;
        //     }
        // }
        if($startDate && $endDate){
            $matchArray[]['initiated_data.submission_mode'] = ["\$ne"=>'kiosk'];
            $matchArray[]["initiated_data.submission_date"] = [
                "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($startDate) * 1000),
                "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($endDate)) * 1000)
            ];
        }
        // pre( $matchArray);
        $projectionArray = [
            'initiated_data.appl_ref_no' => 1,
            'initiated_data.appl_id' => 1,
            'initiated_data.applied_by' => 1,
            'initiated_data.submission_mode' => 1,
            'initiated_data.base_service_id' => 1,
            'initiated_data.submission_location' => 1,
            'initiated_data.submission_date' => 1,
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
                'initiated_data.submission_location' => $search,
                // 'initiated_data.attribute_details.applicant_gender' => $search,
                // 'initiated_data.attribute_details.mobile_number' => $search,
                // 'initiated_data.attribute_details.e-mail' => $search,
                // 'initiated_data.service_name' => $search,
            ];
            $records = $this->applications_model->get_filtered_citizen($projectionArray,$matchArray,$searchArray,$start,$limit,$orderArray);
        }
        $totalFiltered = count((array)$this->applications_model->get_filtered_citizen($projectionArray,$matchArray));
        $this->session->set_userdata('citizen_info_match_array',$matchArray);
//      pre($totalFiltered);
        //fetch payment details from postgss
        $payment_info=$this->get_payment_information($records);
    //    pre(  $payment_info);
        $data = array();
        if (!empty($records)) {
            $sl_no = 0;
            foreach ($records as $objs) {
                
                $row = $objs->initiated_data;
                if(isset($payment_info[$row->appl_id])){
                    $payment_data=$this->cal_amount($payment_info[$row->appl_id]);
                }else{
                    $payment_data=array();
                }
               
                $nestedData["#"] = ++$sl_no;
                $nestedData["submission_location"]     = $row->submission_location;
                $nestedData["agency_id"]               = 'Unknown';
                $nestedData["PFC_CSC_ID"]              = 'Unknown';
                $nestedData["operator_id"]             = $row->applied_by ?? 'N/A';
                $nestedData["service_id"]              = $row->service_name;
                $nestedData["RTPS_NO"]              = $row->appl_ref_no ? '<a class="app_ref_no" data-appl_ref_no="'.$row->appl_ref_no.'" data-id="' . $objs->{'_id'}->{'$id'} . '"  >'.$row->appl_ref_no.'</a>' : '';
                $nestedData["user_govt_charge"]              = $payment_data['govt_charge'] ?? 0;
                
                $nestedData["service_charge"]          = 0;
                $nestedData["scanning_charge"]          = 0;
                $nestedData["printing_charge"]          = 0;
                $nestedData["submission_date"]         = format_mongo_date($row->submission_date,'d-m-Y g:i a');
                // if(property_exists($row->attribute_details,"total_printing_charge" )){
                //     $nestedData["printing_charge"]   =  $row->attribute_details->total_printing_charge ;
                // }elseif(property_exists($row->attribute_details,"total_printing_charges" )){
                //     $nestedData["printing_charge"]   = $row->attribute_details->total_printing_charges;
                // }
                // else{
                //     $nestedData["printing_charge"]   =   0;
                // }

                // if(property_exists($row->attribute_details,"total_scanning_charge" )){
                //     $nestedData["scanning_charge"]   =  $row->attribute_details->total_scanning_charge;
                
                // }elseif(property_exists($row->attribute_details,"total_scanning_charges" )){
                //     $nestedData["scanning_charge"]   = $row->attribute_details->total_scanning_charges;
                // }
                // elseif(property_exists($row->attribute_details,"scanning_charge" )){
                //     $nestedData["scanning_charge"]   = $row->attribute_details->scanning_charge;
                // }
                // else{
                //     $nestedData["scanning_charge"]   =  0;
                // }


                switch($row->submission_mode){
                    case 'kiosk':
                        $modeOfApplication = 'PFC/CSC';
                        // $nestedData["service_charge"] = 30;
                      
                        break;
                    case 'in person':
                        $modeOfApplication = 'Concerned Office';
                        break;
                    default:
                        $modeOfApplication = 'Self';
                        break;
                }
                $nestedData["mode_of_application"] = $modeOfApplication;
                $nestedData["transaction_amount_h"] = !empty($payment_info[$row->appl_id]) ? $payment_info[$row->appl_id]['transaction_amount'] : "0";
                // $nestedData["service_charge"] = !empty($payment_info[$row->appl_id]) ? $payment_info[$row->appl_id]['html']['TOTAL_NON_TREASURY_AMOUNT'] : "0";
                $nestedData["service_charge"] = $payment_data['service_charge'] ?? 0;

                $nestedData["total_charge"]              = floatval($nestedData["user_govt_charge"])  +    floatval($nestedData["service_charge"]);
                $btns = '<a href="#!" class="btn btn-primary btn_account_info" data-appl_ref_no="' . $row->appl_ref_no . '" title="View"><span class="fa fa-eye" aria-hidden="true"></span>Account Info</a> ';
                // $nestedData["action"] = $btns;
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
    private function get_payment_information($records){
        $app_ids=array();
        foreach ($records as $objs) {
            array_push($app_ids,$objs->initiated_data->appl_id);
        }
        if($app_ids){

            $payment_info= $this->connect_postgresql(implode(",",$app_ids));
            // $payment_info= $this->connect_postgresql("18431231,18431199,
            // 17876451,
            // 18457357,
            // 18456938,
            // 18477259,
            // 18466674,
            // 17874870,
            // 17876507");
            return $payment_info;
        }else{
            return array();
        }
    }
    /**
     * @return array
     */
    public function get_records(){
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        $submission_location = $this->input->post('submission_location');
        $payment_mode = $this->input->post('payment_mode');
        $service_id = $this->input->post('service_id');
        // $gender = $this->input->post('gender');
        // $application_status = $this->input->post('application_status');
        $columns = array(
            0 => "#",
            1 => "#",
            2 => "#",
            3 => "#",
            4 => "#",
            5 => "initiated_data.submission_date",
            6 => "#",
            7 => "#",
            8 => "#",
            9 => "#",
            10 => "#",
            11 => "#",
            12 => "#",
            13 => "#",
        );
      
        $matchArray = [];
       
        if($submission_location){
            $matchArray[]['initiated_data.submission_location'] = $submission_location;
        }
         if($payment_mode ){
            $matchArray[]['initiated_data.payment_mode'] = $payment_mode;
        } 
        if($service_id ){
            $matchArray[]['initiated_data.base_service_id'] = $service_id;
        }
    
        // if($application_status){
        //     if($application_status === 'under_process'){
        //         $matchArray[]['execution_data.0.official_form_details.action'] = ['$nin' => ['Reject','Deliver']];
        //     }else{
        //         $matchArray[]['execution_data.0.official_form_details.action'] = $application_status;
        //     }
        // }
        if($startDate && $endDate){
            $matchArray[]['initiated_data.submission_mode'] = 'kiosk';
            $matchArray[]["initiated_data.submission_date"] = [
                "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($startDate) * 1000),
                "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($endDate)) * 1000)
            ];
        }
        $projectionArray = [
            'initiated_data.appl_ref_no' => 1,
            'initiated_data.appl_id' => 1,
            'initiated_data.applied_by' => 1,
            'initiated_data.submission_mode' => 1,
            'initiated_data.submission_location' => 1,
            'initiated_data.base_service_id' => 1,
            'initiated_data.submission_date' => 1,
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
                'initiated_data.submission_location' => $search,
                // 'initiated_data.attribute_details.applicant_gender' => $search,
                // 'initiated_data.attribute_details.mobile_number' => $search,
                // 'initiated_data.attribute_details.e-mail' => $search,
                // 'initiated_data.service_name' => $search,
            ];
            $records = $this->applications_model->get_filtered_citizen($projectionArray,$matchArray,$searchArray,$start,$limit,$orderArray);
        }
        $totalFiltered = count((array)$this->applications_model->get_filtered_citizen($projectionArray,$matchArray));
        $this->session->set_userdata('citizen_info_match_array',$matchArray);
//      pre($totalFiltered);
        //fetch payment details from postgss
        $payment_info=$this->get_payment_information($records);
    //    pre(  $payment_info);
        $data = array();
        if (!empty($records)) {
            $sl_no = 0;
            foreach ($records as $objs) {
                
                $row = $objs->initiated_data;
                if(isset($payment_info[$row->appl_id])){
                    $payment_data=$this->cal_amount($payment_info[$row->appl_id]);
                }else{
                    $payment_data=array();
                }
               
                $nestedData["#"] = ++$sl_no;
                $nestedData["submission_location"]     = "Unknown";//$row->submission_location;
                $nestedData["agency_id"]               = 'Unknown';
                $nestedData["PFC_CSC_ID"]              = 'Unknown';
                $nestedData["operator_id"]             = $row->applied_by ?? 'N/A';
                $nestedData["service_id"]              = $row->service_name;
                $nestedData["RTPS_NO"]              = $row->appl_ref_no ? '<a class="app_ref_no" data-appl_ref_no="'.$row->appl_ref_no.'" data-id="' . $objs->{'_id'}->{'$id'} . '"  >'.$row->appl_ref_no.'</a>' : '';
                $nestedData["user_govt_charge"]              = $payment_data['govt_charge'] ?? 0;
                
                $nestedData["service_charge"]          = 0;
                $nestedData["scanning_charge"]          = 0;
                $nestedData["printing_charge"]          = 0;
                $nestedData["submission_date"]         = format_mongo_date($row->submission_date,'d-m-Y g:i a');
                // if(property_exists($row->attribute_details,"total_printing_charge" )){
                //     $nestedData["printing_charge"]   =  $row->attribute_details->total_printing_charge ;
                // }elseif(property_exists($row->attribute_details,"total_printing_charges" )){
                //     $nestedData["printing_charge"]   = $row->attribute_details->total_printing_charges;
                // }
                // else{
                //     $nestedData["printing_charge"]   =   0;
                // }

                // if(property_exists($row->attribute_details,"total_scanning_charge" )){
                //     $nestedData["scanning_charge"]   =  $row->attribute_details->total_scanning_charge;
                
                // }elseif(property_exists($row->attribute_details,"total_scanning_charges" )){
                //     $nestedData["scanning_charge"]   = $row->attribute_details->total_scanning_charges;
                // }
                // elseif(property_exists($row->attribute_details,"scanning_charge" )){
                //     $nestedData["scanning_charge"]   = $row->attribute_details->scanning_charge;
                // }
                // else{
                //     $nestedData["scanning_charge"]   =  0;
                // }


                switch($row->submission_mode){
                    case 'kiosk':
                        $modeOfApplication = 'PFC/CSC';
                        // $nestedData["service_charge"] = 30;
                      
                        break;
                    case 'in person':
                        $modeOfApplication = 'Concerned Office';
                        break;
                    default:
                        $modeOfApplication = 'Self';
                        break;
                }
                $nestedData["mode_of_application"] = $modeOfApplication;
                $nestedData["transaction_amount_h"] = !empty($payment_info[$row->appl_id]) ? $payment_info[$row->appl_id]['transaction_amount'] : "0";
                // $nestedData["service_charge"] = !empty($payment_info[$row->appl_id]) ? $payment_info[$row->appl_id]['html']['TOTAL_NON_TREASURY_AMOUNT'] : "0";
                $nestedData["service_charge"] = $payment_data['service_charge'] ?? 0;

                $nestedData["total_charge"]              = floatval($nestedData["user_govt_charge"])  +    floatval($nestedData["service_charge"]);
                $btns = '<a href="#!" class="btn btn-primary btn_account_info" data-appl_ref_no="' . $row->appl_ref_no . '" title="View"><span class="fa fa-eye" aria-hidden="true"></span>Account Info</a> ';
                // $nestedData["action"] = $btns;
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
    private function checkAccount($data){
      //  check for amount of non pfc account
        $this->load->config("mis_config");
        $acc=$this->config->item('rtps_pfc_accounts');
        $amount=0;
        $temp=array(
            "ACCOUNT1"=>"AC1_AMOUNT",
            "ACCOUNT2"=>"AC2_AMOUNT",
            "ACCOUNT3"=>"AC3_AMOUNT",
            "ACCOUNT4"=>"AC4_AMOUNT",
            "ACCOUNT5"=>"AC5_AMOUNT",
            "ACCOUNT6"=>"AC6_AMOUNT",
            "ACCOUNT7"=>"AC7_AMOUNT",
            "ACCOUNT8"=>"AC8_AMOUNT",
            "ACCOUNT9"=>"AC9_AMOUNT",
            "ACCOUNT10"=>"AC10_AMOUNT",
        );
        foreach($temp as $key=>$a){
            if(!empty($data[$key])){
                if(!in_array($data[$key],$acc)){
                    $amount +=  $data[$a];
                }
            }
            
        }
     //   sum of all account amount which doesn't belongs to any pfc
        return $amount;
    }
    private function cal_amount($data){
        // pre($res);
        $gov_amount=0;
        $service_amount=0;
       

        $response=array();
        if(isset($data['html']['CHALLAN_AMOUNT']) && ($data['html']['CHALLAN_AMOUNT'] == 0 || $data['html']['CHALLAN_AMOUNT'] === "")){
            $am=$this->checkAccount($data['html']);
            if($am > 0){
                //case 1
                $gov_amount=$am;
                if(isset($data['html']['TOTAL_NON_TREASURY_AMOUNT'])){
                    $service_amount = floatval($data['html']['TOTAL_NON_TREASURY_AMOUNT'] ) - $am;
                }else{
                    $service_amount = 0;
                }
                
            }else{
                $gov_amount=0;
                $service_amount = isset($data['html']['TOTAL_NON_TREASURY_AMOUNT']) ? $data['html']['TOTAL_NON_TREASURY_AMOUNT'] : 0 ;
            }
           
            
        }else{
            // case 2
            $gov_amount=isset($data['html']['CHALLAN_AMOUNT'])?$data['html']['CHALLAN_AMOUNT']:0;
            $service_amount=isset($data['html']['TOTAL_NON_TREASURY_AMOUNT'])?$data['html']['TOTAL_NON_TREASURY_AMOUNT']:0;
        }
        $response['govt_charge']=$gov_amount;
        $response['service_charge']=$service_amount;
        return $response;
    }
    private function find_amount($data){
        $amount=0;
        $this->load->config("mis_config");
        $acc=$this->config->item('rtps_pfc_accounts');
        $temp=array(
            "ACCOUNT1"=>"AC1_AMOUNT",
            "ACCOUNT2"=>"AC2_AMOUNT",
            "ACCOUNT3"=>"AC3_AMOUNT",
            "ACCOUNT4"=>"AC4_AMOUNT",
            "ACCOUNT5"=>"AC5_AMOUNT",
            "ACCOUNT6"=>"AC6_AMOUNT",
            "ACCOUNT7"=>"AC7_AMOUNT",
            "ACCOUNT8"=>"AC8_AMOUNT",
            "ACCOUNT9"=>"AC9_AMOUNT",
            "ACCOUNT10"=>"AC10_AMOUNT",
        );
        if($data){
            if($data['MULTITRANSFER'] === "Y"){
                foreach(  $acc as $c){
                    $key= array_search($c,$data,true);
                    if($key){
                        $amount=$data[$temp[$key]];
                        break;
                    }
                }
               
            }
        }
        return $amount;
        // rtps_pfc_accounts
    }
    public function download_excel(){
        $this->load->model('applications_model');
        $matchArray = $this->session->userdata('citizen_info_match_array');
     //  pre($matchArray);
        if(!empty($matchArray)){
            $records = $this->applications_model->get_filtered(['$and' => $matchArray]);
        }else{
            $records = array();
        }
        $this->session->unset_userdata('citizen_info_match_array');

        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Oparator');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'District');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Agency');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'PFC/CSC');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Date of application');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Service');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'RTPS No');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Govt Charge');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Service Charge');
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Total Charge');
        // set Row
        $rowCount = 2;
        $payment_info=$this->get_payment_information($records);
        
        foreach ($records as $objs) {
            $row = $objs->initiated_data;
            if(isset($payment_info[$row->appl_id])){
                $payment_data=$this->cal_amount($payment_info[$row->appl_id]);
            }else{
                $payment_data=array();
            }

            // $service_charge=!empty($payment_info[$row->appl_id]) ? $this->find_amount($payment_info[$row->appl_id]['html']) : "0";
            $service_charge= $payment_data['service_charge'] ?? 0;
            $user_govt_charge=$payment_data['govt_charge'] ?? 0;

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->applied_by ?? 'N/A');
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, 'Unknown');
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, 'unkown');
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, 'unkown');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, format_mongo_date($row->submission_date,'d-m-Y g:i a'));
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $row->service_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $row->appl_ref_no);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $user_govt_charge);

  
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $service_charge);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, floatval($user_govt_charge)  +    floatval($service_charge));
            $rowCount++;
        }

        $fileName = 'Payment Report.xlsx';
        $writer = new Xlsx($objPHPExcel);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $fileName.'"');
        $writer->save('php://output');
    }
    public function citizen_download_excel(){
        $projectionArray = [
            'initiated_data.appl_ref_no' => 1,
            'initiated_data.appl_id' => 1,
            'initiated_data.applied_by' => 1,
            'initiated_data.submission_mode' => 1,
            'initiated_data.submission_location' => 1,
            'initiated_data.base_service_id' => 1,
            'initiated_data.submission_date' => 1,
            'initiated_data.service_name' => 1,
            'execution_data' => 1
        ];
        $this->load->model('applications_model');
        $matchArray = $this->session->userdata('citizen_info_match_array');
     //  pre($matchArray);
        if(!empty($matchArray)){
            $records = $this->applications_model->get_filtered_citizen(  $projectionArray,$matchArray);
        }else{
            $records = array();
        }
        $this->session->unset_userdata('citizen_info_match_array');

        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Submission Location');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Date of application');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Service');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'RTPS No');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Govt Charge');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Service Charge');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Total Charge');
        // set Row
        $rowCount = 2;
        $payment_info=$this->get_payment_information($records);
        
        foreach ($records as $objs) {
            $row = $objs->initiated_data;
            if(isset($payment_info[$row->appl_id])){
                $payment_data=$this->cal_amount($payment_info[$row->appl_id]);
            }else{
                $payment_data=array();
            }

            // $service_charge=!empty($payment_info[$row->appl_id]) ? $this->find_amount($payment_info[$row->appl_id]['html']) : "0";
            $service_charge= $payment_data['service_charge'] ?? 0;
            $user_govt_charge=$payment_data['govt_charge'] ?? 0;

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $row->submission_location ?? 'N/A');
            
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, format_mongo_date($row->submission_date,'d-m-Y g:i a'));
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $row->service_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $row->appl_ref_no);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $user_govt_charge);

  
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $service_charge);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, floatval($user_govt_charge)  +    floatval($service_charge));
            $rowCount++;
        }

        $fileName = 'Citzen Payment Report.xlsx';
        $writer = new Xlsx($objPHPExcel);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $fileName.'"');
        $writer->save('php://output');
    }

    public function get_account_info($data){
      
        $html_str= $data;
        $html_dom = str_get_html($html_str);     // create DOM
        $inpt_elms = $html_dom->find('input[name]');     // Get all input[name] 
        foreach ($inpt_elms as $element) {
            if (isset($element->name)) {
                $result[$element->name] = $element->value;
            }
        }
    return $result;
    //    $this->load->view("revenue/account_info", array('data' => $result,"ref_no"=>$ref_no));
    
    }

    private function connect_postgresql($application_ids){
            $host        = "host=10.194.162.120";//"host = 127.0.0.1";
            // $host        = "host = 127.0.0.1";
            $port        = "port=5432";//"port = 5432";
            $dbname      = "dbname = rtps_prod";
            $credentials = "user = serviceplusrole password=Artps@p05tgres"; //"user = postgres password=admin";
            // $credentials = "user = postgres password=admin";

            $db = pg_connect( "$host $port $dbname $credentials"  );
            if(!$db) {
                echo "Error : Unable to open database\n";
                return array();
            } else {
                // echo "Opened database successfully\n";
                $apps = [];
                $query="SELECT application_id,request_html,transaction_amount FROM schm_sp.egras_txn_log_assam where  application_id IN ( $application_ids ) AND dv_txn_status='Y' "  ;
                    $ret = pg_query($db, $query);
                    if (!$ret) {
                        return $apps;
                      }
                   
                    while ($row = pg_fetch_row($ret)) {
                        $apps[$row[0]] = array("html"=>$this->get_account_info($row[1]),"transaction_amount"=>$row[2]);
                      }
                return $apps;
                    
            }

            
    }

    public function get_arr(){
      
        $html_str= "  <input type='hidden' name='DEPT_CODE' value='ITD' /> <!-- * -->  <input type='hidden' name='OFFICE_CODE' value='ITD019' /> <!-- * -->  <input type='hidden' name='REC_FIN_YEAR' value='2021-2022' /> <!-- * -->  <input type='hidden' name='PERIOD' value='O' /> <!-- * -->  <input type='hidden' name='FROM_DATE' value='01/04/2021' /> <!-- * -->  <input type='hidden' name='TO_DATE' value='31/03/2099' /> <!-- * -->  <input type='hidden' name='TAX_ID' value='' /> <!-- * -->  <input type='hidden' name='PAN_NO' value='' /> <!-- * -->  <input type='hidden' name='PARTY_NAME' value='Manglien Haolai' /> <!-- * -->  <input type='hidden' name='ADDRESS1' value='' /> <!-- * -->  <input type='hidden' name='ADDRESS2' value='' /> <!-- * -->  <input type='hidden' name='ADDRESS3' value='' /> <!-- * -->  <input type='hidden' name='PIN_NO' value='' /> <!-- * -->  <input type='hidden' name='MOBILE_NO' value='9954979379' /><!-- * -->  <input type='hidden' name='DEPARTMENT_ID' value='ITDRP7248' /><!-- * -->  <input type='hidden' name='REMARKS' value='' /> <!-- * -->  <input type='hidden' name='SUB_SYSTEM' value='REV-SP|https://www.rtps.assam.gov.in/egrasASResponse.do' /> <!-- * -->  <input type='hidden' name='FORM_ID' value='TR04A' /> <!-- * -->  <input type='hidden' name='PAYMENT_TYPE' value='' /> <!-- * -->  <input type='hidden' name='TREASURY_CODE' value='' /> <!-- * -->  <input type='hidden' name='MAJOR_HEAD' value='' /> <!-- * -->  <input type='hidden' name='AMOUNT1' value='' /> <!-- * -->  <input type='hidden' name='HOA1' value='' /> <!-- * -->  <input type='hidden' name='AMOUNT2' value='' /> <!-- * -->  <input type='hidden' name='HOA2' value='' /> <!-- * -->  <input type='hidden' name='AMOUNT3' value='' /> <!-- * -->  <input type='hidden' name='HOA3' value='' /> <!-- * -->  <input type='hidden' name='AMOUNT4' value='' /> <!-- * -->  <input type='hidden' name='HOA4' value='' /> <!-- * -->  <input type='hidden' name='AMOUNT5' value='' /> <!-- * -->  <input type='hidden' name='HOA5' value='' /> <!-- * -->  <input type='hidden' name='AMOUNT6' value='' /> <!-- * -->  <input type='hidden' name='HOA6' value='' /> <!-- * -->  <input type='hidden' name='AMOUNT7' value='' /> <!-- * -->  <input type='hidden' name='HOA7' value='' /> <!-- * -->  <input type='hidden' name='AMOUNT8' value='' /> <!-- * -->  <input type='hidden' name='HOA8' value='' /> <!-- * -->  <input type='hidden' name='AMOUNT9' value='' /> <!-- * -->  <input type='hidden' name='HOA9' value='' /> <!-- * -->  <input type='hidden' name='CHALLAN_AMOUNT' value='0' /> <!-- * --><input type='hidden' name='AC1_AMOUNT' value='110.0' /> <!-- * --><input type='hidden' name='ACCOUNT1' value='ITD51366'  /> <!-- * --><input type='hidden' name='AC2_AMOUNT' value=''  /> <!-- * --><input type='hidden' name='ACCOUNT2' value=''  /> <!-- * --><input type='hidden' name='AC3_AMOUNT' value=''  /> <!-- * --><input type='hidden' name='ACCOUNT3' value=''  /> <!-- * --><input type='hidden' name='AC4_AMOUNT' value=''  /> <!-- * --><input type='hidden' name='ACCOUNT4' value=''  /> <!-- * --><input type='hidden' name='AC5_AMOUNT' value=''  /> <!-- * --><input type='hidden' name='ACCOUNT5' value=''  /> <!-- * --><input type='hidden' name='AC6_AMOUNT' value=''  /> <!-- * --><input type='hidden' name='ACCOUNT6' value=''  /> <!-- * --><input type='hidden' name='AC7_AMOUNT' value=''  /> <!-- * --><input type='hidden' name='ACCOUNT7' value=''  /> <!-- * --><input type='hidden' name='AC8_AMOUNT' value=''  /> <!-- * --><input type='hidden' name='ACCOUNT8' value=''  /> <!-- * --><input type='hidden' name='AC9_AMOUNT' value=''  /> <!-- * --><input type='hidden' name='ACCOUNT9' value=''  /> <!-- * --><input type='hidden' name='AC10_AMOUNT' value=''  /> <!-- * --><input type='hidden' name='ACCOUNT10' value=''  /> <!-- * --><input type='hidden' name='TOTAL_NON_TREASURY_AMOUNT' value='110.0'  /> <!-- * -->  <input type='hidden' name='NON_TREASURY_PAYMENT_TYPE' value='21' /> <!-- * -->  <input type='hidden' name='MULTITRANSFER' value='Y' /> <!-- * -->";
        $html_dom = str_get_html($html_str);     // create DOM
        $inpt_elms = $html_dom->find('input[name]');     // Get all input[name] 
        foreach ($inpt_elms as $element) {
            if (isset($element->name)) {
                $result[$element->name] = $element->value;
            }
        }
    pre( $result);
    //    $this->load->view("revenue/account_info", array('data' => $result,"ref_no"=>$ref_no));
    
    }
    public function get_vendors(){
        $vendors=$this->kiosk_mapping_model->get_vendor_by_dept($this->input->get("dept_id"));
        if($vendors){ ?>
                <option value="">Select Vendor</option>
            <?php 
            foreach($vendors as $ven){?>
                <option value="<?=$ven->vendor_id?>"><?=$ven->vendor_name?></option>
        <?php } }else{ ?>
            <option value="">No Vendors Found</option>
        <?php }
    }  
    public function get_agency(){
        $vendors=$this->kiosk_mapping_model->get_agency_by_vendor($this->input->get("vendor_id"));
        if($vendors){ ?>
                <option value="">Select Agency</option>
            <?php 
            foreach($vendors as $ven){?>
                <option value="<?=$ven->agency_id?>"><?=$ven->agency_name?></option>
        <?php } }else{ ?>
            <option value="">No Agency Found</option>
        <?php }
    } 
    public function get_kiosk(){
        $vendors=$this->kiosk_mapping_model->get_kiosk_by_agency($this->input->get("agency_id"));
        if($vendors){ ?>
                <option value="">Select Kiosk</option>
            <?php 
            foreach($vendors as $ven){?>
                <option value="<?=$ven->kiosk_id?>"><?=$ven->kiosk_name?></option>
        <?php } }else{ ?>
            <option value="">No Kiosk Found</option>
        <?php }
    }
}