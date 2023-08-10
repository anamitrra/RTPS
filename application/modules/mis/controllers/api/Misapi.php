<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Misapi extends frontend
{
    public function __construct()
    {
        parent::__construct();
    }
    public function department_list()
    {        
        $this->load->model("department_model");
        $result = $this->department_model->get_departments();
        if($result){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($result));
        }
    }

    public function get_citizen_application_count(){
        $filter = file_get_contents('php://input');
        if (!empty(  $filter)) {
            $data = json_decode(  $filter, true);
            if($data['secret'] !=="rtpsapi#!@"  || empty($data['mobile'])){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array("status" => false, "message" => "Unauthorized")));
            }

            $apps=$this->get_app_count_by_citizen($data['mobile']);
            if($apps >=5){
                $response_arr=array('message'=>"Dear User, you have already submitted 5 applications in current month. As per Government order, RTPS portal is allowing upto 5 application submissions in a month. Regards, Team RTPS","status"=>false,"application_count"=> $apps);
            }else
            $response_arr=array('message'=>"User application count not exceed 5 for this month ","status"=>true,"application_count"=> $apps);
         
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response_arr));
           
        }else{
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array("status" => false, "message" => "No Data Found")));
        }

    }
    private function get_app_count_by_citizen($mobile){
        $current_month = date('n'); //2;//// without leading zero
        $current_year = date('Y'); //2022;
        $operations = [
            [
                '$match' => [
                    'initiated_data.attribute_details.mobile_number' => $mobile,
                    'initiated_data.submission_mode'=>['$ne'=>'kiosk']
                ]
            ],
            [
                '$addFields' => [
                    'month' => ['$month' => '$initiated_data.submission_date'],
                ]
            ],
            [
                '$addFields' => [
                    'year' => ['$year' => '$initiated_data.submission_date'],
                ]
            ],
            ['$match' => ['month' => $current_month, 'year' => $current_year]],
            [
                '$project' => [
                    'submission_date' => '$initiated_data.submission_date',
                    'month' => 1,
                    'year' => 1,
                    'mobile' => '$initiated_data.attribute_details.mobile_number',
                ]
              ],
              ['$count' => 'total_rows'],
        ];

        $data = $this->mongo_db->aggregate("applications", $operations);
        $data = (array) $data;
        if (count($data)>0) {
            return  $data[0]->total_rows;
        }else{
            return 0;
        }
    }

    
}
