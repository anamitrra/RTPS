<?php

use PHPHtmlParser\Dom;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Test extends MY_Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->load->model('application_processing_json_model');
        $log = $this->application_processing_json_model->my_get_where('*',['application_id' => [29485,30035]]);
        echo '<pre>';
        print_r(($log[0]));
        pre(json_decode($log[1]->initiated_data));
        pre(json_decode($log[1]->execution_data));
        $dom = new Dom();
        $contents = $dom->loadStr($log[1]->request_html);
//        pre($contents->outerHtml);
//        pre(count($contents));
        foreach ($dom->find('input') as $x){
            echo $x->name.' : '.$x->value.'<br>';
        }
        exit();
        echo '<form method="POST" enctype="multipart/form-data" action="'.base_url('service_plus/test/process_excel').'">
        <input type="file" name="excel_file" >
        <button>Submit</button>
</form>';
    }

    public function process_excel(){
//        $columnNames = $sheetData[0];
//        $dataToInsert = [];
//        foreach ($sheetData as $key => $data){
//            if($key === 0)
//                continue;
//            $dataToInsert[] = [
////                $columnNames[0] => $data[0],
//                $columnNames[1] => $data[1],
//                $columnNames[2] => $data[2],
//                $columnNames[3] => $data[3],
//                $columnNames[4] => $data[4],
//                $columnNames[5] => $data[5],
//                $columnNames[6] => $data[6],
//                $columnNames[7] => $data[7],
//                $columnNames[8] => $data[8],
//                $columnNames[9] => $data[9],
//                $columnNames[10] => $data[10],
//                $columnNames[11] => $data[11],
//                $columnNames[12] => $data[12],
//                $columnNames[13] => $data[13],
//                $columnNames[14] => $data[14],
//                $columnNames[15] => $data[15],
//                $columnNames[16] => $data[16],
//                $columnNames[17] => $data[17],
//                $columnNames[18] => $data[18],
//                $columnNames[19] => $data[19],
//                $columnNames[20] => $data[20],
//                $columnNames[21] => $data[21],
//            ];
//        }
////        pre($dataToInsert);
//        $this->load->model('egras_txn_log_assam_model');
//        pre($this->egras_txn_log_assam_model->my_insert($dataToInsert,true));
//        $inputToInsert = [];
//        $x = [];
//        foreach ($sheetData as $key => $row){
//            if($key === 0)
//                continue;
//            $mobileStr = [];
//            if(strpos( $row[4],'/ ')){
//                $mobileStr = explode('/ ',$row[4]);
//            }
//            if(strpos( $row[4],', ')){
//                $mobileStr = explode(', ',$row[4]);
//            }
//            $mobileNumber = (count($mobileStr))?trim($mobileStr[0]):trim($row[4]);
//            $additionalMobileNumber = count($mobileStr) > 1 ? trim($mobileStr[1]):'';
//            $inputToInsert[] = [
//                'name' => trim($row[1]),
//                'registration_id' => trim($row[2]),
//                'address' => trim($row[3]),
//                'mobile_number' => $mobileNumber,// (count(explode('/ ',$row[4])) > 1) ? trim(explode('/ ',$row[4])[0]): trim($row[4]),
//                'email_id' => trim($row[5]),
//                'kiosk_type' => trim($row[6]),
//                'additional_info_1' => trim($row[7]),
//                'additional_info_2' => trim($row[8]),
//                'additional_contact_number' => $additionalMobileNumber,//(count(explode('/ ',$row[4])) > 1) ? 'contact number : '.trim(explode('/ ',$row[4])[1]): ''
//            ];
////            $x[] = strlen(trim(explode('/ ',$row[4])[0]));
//        }
//        pre($inputToInsert);
//        pre($this->kiosk_operator_model->my_insert($inputToInsert,true));
    }

}