<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Employment_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("sp_applications");
    }//End of __construct()
       
  
    public function get_row($filter = null) {
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->find_one($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_row()
    
    public function get_rows($filter = null) {
        $this->mongo_db->order_by('created_at', 'DESC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()


    //kiosk get rows

     public function kiosk_get_rows($filter = null) {
        // Calculate the date one month ago
        $oneMonthAgo = date('Y-m-d', strtotime('-1 month'));
    
        // Construct the filter
        
        $datefilter = [
            'form_data.submission_date' => [
                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($oneMonthAgo) * 1000)
            ]
        ];
        $filter = array_merge($datefilter, $filter);
        //pre($filter);
        $this->mongo_db->order_by('created_at', 'DESC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
     }//End of get_rows()


    public function add_param($where,$params){
        $this->mongo_db->set($params);
        $this->mongo_db->where($where);
        return $this->mongo_db->update($this->table);
    } 
    public function update_row($where,$params){
        $this->mongo_db->set($params);
        $this->mongo_db->where($where);
        return $this->mongo_db->update($this->table);
    }

    public function update_payment_status($appl_ref_no,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where(array("service_data.appl_ref_no"=> $appl_ref_no));
        return $this->mongo_db->update('sp_applications');
    }
    public function update_payment($rtps_trans_id,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where(array("form_data.rtps_trans_id"=> $rtps_trans_id));
        return $this->mongo_db->update($this->table);
    }
    public function checkPFCPaymentIntitateTimeNew($dept_id)
    {
      $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
      $operations = array(
        [
          '$match' => [
            'form_data.department_id' => $dept_id
          ]
        ],
  
        [
          '$project' => [
            'item' => 1,
            'dateDifference' => array('$subtract' => [
              $current_time, '$createdDtm'
            ])
          ]
        ]
  
      );
  
      $data = $this->mongo_db->aggregate("pfc_payment_history", $operations);
      // pre($data);
      $arr = (array) $data;
      if (!empty($arr) && count($arr) > 0) {
        if(!empty($arr[0]->dateDifference)){
          $miliseconds=$arr[0]->dateDifference;
          $min=$miliseconds/(1000*60);
          return round($min);
        }else{
          return "N";
        }
      
      } else {
          return "N";
      }
    }
    //renewal get reg no.
    public function get_previous_data($reg_no,$reg_date){
        $toDate = $reg_date."T00:00:00.000Z";
        $fromDate = $reg_date."T23:59:59.000Z";
        $fillter=  array(
                '$and' => array(
                  [
                  'form_data.registration_no'=>$reg_no,
                  'form_data.submission_date' => array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($toDate) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime($fromDate) * 1000)
                  )      
                ]
                )       
        );
        $this->mongo_db->select('*');
        $this->mongo_db->where($fillter);
        return $this->mongo_db->get('sp_applications');
    }

    public function get_previous_data_nonaadhar($reg_no,$reg_date){
        $toDate = $reg_date."T00:00:00.000Z";
        $fromDate = $reg_date."T23:59:59.000Z";
        $fillter=  array(
                '$and' => array(
                  [
                  'form_data.registration_no'=>$reg_no,
                  'form_data.date_of_reg' => array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($toDate) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime($fromDate) * 1000)
                  )      
                ]
                )       
        );
        $this->mongo_db->select('*');
        $this->mongo_db->where($fillter);
        return $this->mongo_db->get('sp_applications');
    }

    public function check_old_data($reg_no,$reg_date,$service_id){
      $toDate = $reg_date."T00:00:00.000Z";
      $fromDate = $reg_date."T23:59:59.000Z";
      $fillter=  array(
              '$and' => array(
                [
                'form_data.service_id'=>$service_id,
                'form_data.registration_no'=>$reg_no,
                'form_data.submission_date' => array(
                  "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($toDate) * 1000),
                  "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime($fromDate) * 1000)
                )      
              ]
              )       
      );
      $this->mongo_db->select('*');
      $this->mongo_db->where($fillter);
      return $this->mongo_db->get('sp_applications');
  }

  public function get_district_from_eeo($employment_exchange_office = null) {
    
    $match = [
        '$match' => [
            'employment_exchange_office' => $employment_exchange_office
        ]
    ];

    $lookup = [
        '$lookup' => [
            'from' => 'emp_district',
            'localField' => 'district_id',
            'foreignField' => 'id',
            'as' => 'district_info'
        ]
    ];

    $unwind = ['$unwind' => '$district_info'];

    $project = [
        '$project' => [
            'district_name' => '$district_info.district',
            '_id' => 0 // Exclude the _id field from the result
        ]
    ];

    $pipeline = [$match, $lookup, $unwind, $project];

   $res = (array)$this->mongo_db->aggregate('emp_district_employment_exchange', $pipeline);

    if (count($res) > 0) {
        return $res[0]->district_name;
    } else {
        return "CENTRAL";
    }
}


}
