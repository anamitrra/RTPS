<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\UTCDateTime;
class Employment_status_model extends Mongo_model {

    const EE_DEPT_ID = '2193';

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
    
    public function update_payment($rtps_trans_id,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where(array("form_data.rtps_trans_id"=> $rtps_trans_id));
        return $this->mongo_db->update($this->table);
    }

    public function update_row($where,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where($where);
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

  public function get_doc_id($reg_no,$reg_date,$dob) {
    // pre($reg_no);
      $this->mongo_db->switch_db('mis');

    $this->set_collection("mis");

    $data=$this->mongo_db->select(['_id'])->where(array("execution_data.official_form_details.registration_no"=>strval($reg_no),"execution_data.official_form_details.reg_date"=>strval($reg_date),"execution_data.official_form_details.dob"=>strval($dob)))->get("applications");
    $this->mongo_db->switch_db('iservices');
    return $data;             
    }//End of get_row()

public function updateEmploymentStatus($filter,$data) {
       $this->mongo_db->switch_db('mis');
       $this->set_collection("applications");

      
         $this->mongo_db->where($filter);
         $this->mongo_db->set($data);
         $res = $this->mongo_db->update($this->table);
         if(count((array)$res)) {
             return $res;
         } else {
             return false;
         }//End of if else 
    }

    // Check in mis.applicatioin
  public function check_record_mis($reg_no,$reg_date,$dob)
  {
      $this->mongo_db->switch_db('mis');

      $doc = $this->mongo_db->where(array(
        'initiated_data.department_id' => self::EE_DEPT_ID ,

        '$or' => array(
          ['initiated_data.attribute_details.registration_date' => $reg_date, 'initiated_data.attribute_details.registration_no'=>$reg_no,'initiated_data.attribute_details.date_of_birth' => $dob],
          ['execution_data.0.official_form_details.registration_no'=>$reg_no,'execution_data.0.official_form_details.date_of_registration'=>$reg_date,'execution_data.0.official_form_details.date_of_birth'=>$dob],
        )
      ))->get('applications');


      // switch back to default db
      $this->mongo_db->switch_db('iservices');

      return (array) $doc;
  }


   //update where
   public function update_where_mis($filter,$data){
      $this->mongo_db->switch_db('mis');

      $this->mongo_db->set($data);   
      $this->mongo_db->where($filter);
      $this->mongo_db->update_all('applications');

      $this->mongo_db->switch_db('iservices');
   }

   public function get_row_mis($filter = null) {
    $this->mongo_db->switch_db('mis');
    $this->mongo_db->where($filter);
    $res = $this->mongo_db->find_one("applications");
    if(count((array)$res)) {
        return $res;
    } else {
        return false;
    }//End of if else 
    $this->mongo_db->switch_db('iservices');       
}//End of get_row()



//to check into sp_applications

public function check_record_iservices($reg_no, $reg_date, $dob)
{
    $this->mongo_db->switch_db('iservices');
   
    $date = str_replace('/', '-', $reg_date); 

    $formattedDate = new MongoDB\BSON\UTCDateTime(strtotime($date) * 1000);

    $dobFormatted = array(
        'form_data.date_of_birth' => array(
            '$in' => array(
                $dob,
                str_replace('/', '-', $dob)
            )
        )
    );
    
    $doc = $this->mongo_db->where(array(
        'service_data.department_id' => self::EE_DEPT_ID,
        '$or' => array(
            array(
                'form_data.submission_date' => $formattedDate,
            ),
            array(
                'form_data.registration_no' => $reg_no,
            ),
            $dobFormatted
        )
    ))->get('sp_applications');

    //$this->mongo_db->switch_db('mis');

    return (array) $doc;
}

public function update_where_iservices($filter, $data)
{
    $this->mongo_db->switch_db('iservices');
    
    $this->mongo_db->set($data);
    $this->mongo_db->where($filter);
    $this->mongo_db->update_all('sp_applications');
    
    //$this->mongo_db->switch_db('mis');
}

public function update_Employment_Status($filter, $data) {
        $this->mongo_db->switch_db('iservices');
        $this->set_collection("sp_applications");

        $this->mongo_db->where($filter);
        $this->mongo_db->set($data);
        $res = $this->mongo_db->update_all($this->table);

        //$this->mongo_db->switch_db('mis');

        return $res;
    }
}

