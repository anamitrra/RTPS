<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


require_once FCPATH.'/application/third_party/autoloader.php';
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Test_app extends Rtps{
    public function __construct(){
        parent::__construct();
    }

    public function import_view(){
        $this->load->view('includes/frontend/header');
        $this->load->view('test/import/view');
        $this->load->view('includes/frontend/footer');
    }

    public function import_data()
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($_FILES['excel']['tmp_name']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        pre($sheetData);
        $deptArray = [];
        foreach($sheetData as $key => $row){
            if($key !== 0){
                $deptArray[] = $row[1];
            }
        }
        $deptArray = array_filter($deptArray,function($value){
            return !empty($value) || $value === 0;
        });
        $deptArray = array_values(array_unique($deptArray));
        // pre($deptArray);
        // pre(json_encode($deptArray));
        $inputDept2 =[
            0 => [
                'department_name' => 'Revenue and Disaster Management Department (BTC)',
                'department_code' => 'RDMAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            1 => [
                'department_name' => 'Transport Department (BTC)',
                'department_code' => 'TRNAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            2 => [
                'department_name' => 'Health and Family Welfare Department (BTC)',
                'department_code' => 'HFWAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            3 => [
                'department_name' => 'Labour and Employment Department (BTC)',
                'department_code' => 'LEDAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            4 => [
                'department_name' => 'Cooperation Department (BTC)',
                'department_code' => 'COPAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            5 => [
                'department_name' => 'Animal Husbandry and Veterinary Department (BTC)',
                'department_code' => 'AHVAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            6 => [
                'department_name' => 'Skill Employment and Entrepreneurship (BTC)',
                'department_code' => 'SEEAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            6 => [
                'department_name' => 'Urban Development Department (BTC)',
                'department_code' => 'UDDAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ]
        ];

        $inputDept =[
            0 => [
                'department_name' => 'Revenue and Disaster Management Department',
                'department_code' => 'RDMAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            1 => [
                'department_name' => 'Secondary Education Department',
                'department_code' => 'SEDAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            2 => [
                'department_name' => 'Guwahati Development Department',
                'department_code' => 'GDDAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            3 => [
                'department_name' => 'Transport Department',
                'department_code' => 'TRNAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            4 => [
                'department_name' => 'Health and Family Welfare Department',
                'department_code' => 'HFWAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            5 => [
                'department_name' => 'WPT and BC Department',
                'department_code' => 'TADAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            6 => [
                'department_name' => 'General Administration Department',
                'department_code' => 'TADAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            7 => [
                'department_name' => 'Labour and Employment Department',
                'department_code' => 'LEDAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            8 => [
                'department_name' => 'Cooperation Department',
                'department_code' => 'COPAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            9 => [
                'department_name' => 'Home and Political Department',
                'department_code' => 'HOMAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            10 => [
                'department_name' => 'Finance Department',
                'department_code' => 'FINAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            11 => [
                'department_name' => 'Animal Husbandry and Veterinary Department',
                'department_code' => 'AHVAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            12 => [
                'department_name' => 'Urban Development Department',
                'department_code' => 'UDDAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            13 => [
                'department_name' => 'Agriculture Department',
                'department_code' => 'AGRAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
            14 => [
                'department_name' => 'Environment and Forest Department',
                'department_code' => 'FORAS',
                'department_id' => '',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ],
        ];

        // $this->mongo_db->batch_insert('departments',$inputDept2);

        die('test done');
        [
            "GMDA",// 
            "GMC", //
            "GMDA & SB", //
            "GMC\/GDD", //
            "IWT",//
        ];
	}

	public function export_data(){
        $this->load->model('department_model');
        $services = $this->department_model->get_where([]);
        pre(json_encode($services));
        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        pre($objPHPExcel);
        try {
            pre('test');
            $objPHPExcel->setActiveSheetIndex(0);
            // set Header
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Department ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Service ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Service Name');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Service Timeline');
//            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Process Status');
            // set Row
            $rowCount = 2;
            foreach ($services as $appealApplication) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $appealApplication['department_id']);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $appealApplication['service_id']);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $appealApplication['service_name']);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $appealApplication['service_timeline']);
//                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $appealApplication->process_status);
                $rowCount++;
            }
            $filename = "service_list" . date("Y-m-d-H-i-s") . ".csv";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
            $objWriter->save('php://output');
            pre('test success');
        } catch (PHPExcel_Exception $e) {
            pre('test fail');
        }
    }
}