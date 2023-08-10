<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Eodb_intermediator_model extends Mongo_model
{
  /**
   * __construct
   *
   * @return void
   */
  function __construct()
  {
    parent::__construct();
    $this->set_collection("sp_applications");
  }
  function is_exist_transaction_no($rtps_trans_id)
  {

    $count = $this->mongo_db->mongo_like_count(array('rtps_trans_id' => $rtps_trans_id), 'intermediate_ids');
    if ($count > 0) {
      return true;
    } else {
      return false;
    }
  }
  public function update_payment_status($rtps_trans_id, $data)
  {
    $this->mongo_db->set($data);
    $this->mongo_db->where(array("rtps_trans_id" => $rtps_trans_id));
    return $this->mongo_db->update('intermediate_ids');
  }
  public function add_param($rtps_trans_id, $data)
  {
    $this->mongo_db->set($data);
    $this->mongo_db->where(array("rtps_trans_id" => $rtps_trans_id));
    return $this->mongo_db->update('intermediate_ids');
  }
  public function get_userid_by_application_ref($app_ref_no)
  {
    $this->mongo_db->select(array("mobile", "service_id", "rtps_trans_id", "external_service_id", "applicant_details"));
    $this->mongo_db->where(array("app_ref_no" => $app_ref_no));
    $res = $this->mongo_db->find_one("intermediate_ids");
    return $res;
  }
  public function get_transaction_detail($rtps_trans_id)
  {
    $this->mongo_db->select('*');
    $this->mongo_db->where(array("rtps_trans_id" => $rtps_trans_id));
    $res = $this->mongo_db->find_one("intermediate_ids");
    return $res;
  }
  public function get_application_details($data)
  {
    $this->mongo_db->select('*');
    $this->mongo_db->where($data);
    $res = $this->mongo_db->find_one("intermediate_ids");
    return $res;
  }
  public function get_application_details_array($data)
  {
    $this->mongo_db->select('*');
    $this->mongo_db->where($data);
    $res = $this->mongo_db->get("sp_applications");
    return $res;
  }
  public function get_by_rtps_id($id)
  {
    $this->mongo_db->select("*");
    $this->mongo_db->where(array("rtps_trans_id" => $id));
    $res = $this->mongo_db->find_one("intermediate_ids");
    return $res;
  }
  
  // public function get_row($fillter)
  // {
  //   $this->mongo_db->select("*");
  //   $this->mongo_db->where($fillter);
  //   $res = $this->mongo_db->find_one("sp_applications");
  //   return $res;
  // }

  public function get_row($filter = null)
  {
    $this->mongo_db->where($filter);
    $res = $this->mongo_db->find_one($this->table);
    if (count((array)$res)) {
      return $res;
    } else {
      return false;
    } //End of if else        
  } //End of get_row()
  
  public function get_rows($filter = null)
  {
    $this->mongo_db->order_by('createdDtm', 'DESC');
    $this->mongo_db->where($filter);
    $res = $this->mongo_db->get($this->table);
    if (count((array)$res)) {
      return $res;
    } else {
      return false;
    }
  }

  public function update_row($fillter, $data)
  {
    $this->mongo_db->set($data);
    $this->mongo_db->where($fillter);
    return $this->mongo_db->update('intermediate_ids');
  }
  public function get_noc_pending_application()
  {

    $operations =
      array(
        '$and' => array(
          [
            'portal_no' => ['$in' => ["1", 1]],
            'status' => 'S',
            '$or' => array(
              array("delivery_status" => array("\$exists" => false)),
              array("delivery_status" => array("\$exists" => true, '$ne' => 'D')),


            )
          ]
        )

      );

    $collection = 'intermediate_ids';
    $this->mongo_db->select(array("rtps_trans_id", "mobile", "service_id", "app_ref_no", "service_name", "submission_date", "applicant_details.applicant_name", "execution_data", "sms_delivery_index"));
    return $this->mongo_db->get_data_like($operations, $collection);
  }

  public function get_noc_queryable_application()
  {
    $operations =
      array(
        '$and' => array(
          [
            'portal_no' => ['$in' => ["1", 1]],
            "status" => "S",
            "delivery_status" => ['$ne' => 'D'],
            "execution_data" => ['$elemMatch' => ['action' => 'Query']]
          ]
        )
      );
    $collection = 'intermediate_ids';

    // service_name,applicant_name,number,submission_date,app_ref_no,submission_office
    $this->mongo_db->select(array("rtps_trans_id", "mobile", "app_ref_no", "service_name", "submission_date", "applicant_details.applicant_name"));
    return $this->mongo_db->get_data_like($operations, $collection);
  }

  public function get_noc_delivered_application()
  {
    $operations =
      array(
        '$and' => array(
          [
            'portal_no' => ['$in' => ["1", 1]],
            "delivery_status" => 'D',
            '$or' => array(
              array("is_delivery_sms_send" => array("\$exists" => false)),
              array("is_delivery_sms_send" => array("\$exists" => true, '$ne' => true)),


            )
          ]
        )
      );
    $collection = 'intermediate_ids';

    // service_name,applicant_name,number,submission_date,app_ref_no,submission_office
    $this->mongo_db->select(array("rtps_trans_id", "mobile", "app_ref_no", "service_name", "submission_date", "applicant_details.applicant_name"));
    return $this->mongo_db->get_data_like($operations, $collection);
  }
  public function get_vahan_pending_application()
  {

    $operations =
      array(
        '$and' => array(
          [
            'portal_no' => ['$in' => ["2", 2]],
            'status' => 'S',
            '$or' => array(
              array("delivery_status" => array("\$exists" => false)),
              array("delivery_status" => array("\$exists" => true, '$ne' => 'D')),


            )
          ]
        )

      );

    $collection = 'intermediate_ids';
    $this->mongo_db->select(array("rtps_trans_id", "vahan_app_no"));
    return $this->mongo_db->get_data_like($operations, $collection);
  }

  public function get_my_transactions($mobile)
  {
    $collection = 'sp_applications';

    $fillter =  array(
      '$and' => array(
        [
          'mobile' => $mobile,
          '$or' => array(
            array("is_archived" => array("\$exists" => false)),
            array("is_archived" => array("\$exists" => true, '$ne' => true)),
          )
        ]
      )

    );
    $query =  $this->mongo_db->where($fillter);
    return $query->get($collection);
  }

  public function get_my_archived_transactions($mobile)
  {
    $collection = 'intermediate_ids';

    $fillter =  array(
      '$and' => array(
        [
          'mobile' => $mobile,
          "is_archived" => array("\$exists" => true, '$eq' => true)
        ]
      )

    );
    $query =  $this->mongo_db->where($fillter);
    return $query->get($collection);
  }
  public function get_admin_archived_transactions($applied_by, $role = 'pfc')
  {
    $collection = 'intermediate_ids';

    $fillter =  array(
      '$and' => array(
        [
          'applied_by' => $role === "pfc" ? new ObjectId($applied_by) : $applied_by,
          "is_archived" => array("\$exists" => true, '$eq' => true)
        ]
      )

    );
    $query =  $this->mongo_db->where($fillter);
    return $query->get($collection);
  }
  public function get_admin_transactions($applied_by, $role = 'pfc')
  {
    $collection = 'intermediate_ids';

    $fillter =  array(
      '$and' => array(
        [
          'applied_by' => $role === "pfc" ? new ObjectId($applied_by) : $applied_by,
          '$or' => array(
            array("is_archived" => array("\$exists" => false)),
            array("is_archived" => array("\$exists" => true, '$ne' => true)),
          )
        ]
      )

    );
    $query =  $this->mongo_db->where($fillter);
    return $query->get($collection);
  }

  public function get_payment_pending_applications()
  {
    $collection = 'intermediate_ids';

    $fillter =  array(
      '$and' => array(
        [
          'pfc_payment_response' => array("\$exists" => false),
          'payment_params' => array("\$exists" => true),

        ]
      )

    );
    $this->mongo_db->select(array('department_id', 'payment_params'));
    $this->mongo_db->where($fillter);
    return $this->mongo_db->get($collection);
  }

  public function get_basundhara_pending_application()
  {

    $operations =
      array(
        '$and' => array(
          [
            'portal_no' => ['$in' => ["5", 5]],
            'status' => 'S',
            '$or' => array(
              array("delivery_status" => array("\$exists" => false)),
              array("delivery_status" => array("\$exists" => true, '$ne' => 'D')),


            )
          ]
        )

      );

    $collection = 'intermediate_ids';
    $this->mongo_db->select(array("rtps_trans_id", "mobile", "app_ref_no", "service_name", "submission_date", "applicant_details.applicant_name", "execution_data", "sms_delivery_index"));
    return $this->mongo_db->get_data_like($operations, $collection);
  }
  public function checkPaymentIntitateTime($dept_id)
  {
    $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
    $operations = array(
      [
        '$match' => [
          'department_id' => $dept_id
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
      if (!empty($arr[0]->dateDifference)) {
        $miliseconds = $arr[0]->dateDifference;
        $min = $miliseconds / (1000 * 60);
        return round($min);
      } else {
        return "N";
      }
    } else {
      return "N";
    }
  }

  public function get_pending_payment_status_update()
  {

    $operations =
      array(
        '$and' => array(
          [
            'portal_no' => ['$in' => ["5", 5]],
            'status' => 'S',
            'pfc_payment_status' => 'Y',
            '$or' => array(
              array("payment_status_updated_on" => array("\$exists" => false)),
              array("payment_status_updated_on" => array("\$exists" => true, '$eq' => false)),


            )
          ]
        )

      );

    $collection = 'intermediate_ids';
    $this->mongo_db->select(array("rtps_trans_id", "department_id"));
    return $this->mongo_db->get_data_like($operations, $collection);
  }


  public function get_noc_pending_payment_status()
  {
    $operations =
      array(
        '$and' => array(
          [
            'portal_no' => ['$in' => ["1", 1]],
            'status' => 'S',
            'pfc_payment_status' => 'Y',
            'payment_rtps_end' => true,
            '$or' => array(
              array("payment_status_updated_on_noc" => array("\$exists" => false)),
              array("payment_status_updated_on_noc" => array("\$exists" => true, '$eq' => false)),
            )
          ]
        )

      );

    $collection = 'intermediate_ids';
    $this->mongo_db->select(array("rtps_trans_id", "department_id"));
    return $this->mongo_db->get_data_like($operations, $collection);
  }
  public function get_pending_application_by_portal($portal)
  {


    $filter = array(

      array(
        '$match' => array(
          '$and' => array(
            [
              'portal_no' => ['$in' => $portal],
              'status' => 'S',
              '$or' => array(
                array("delivery_status" => array("\$exists" => false)),
                array("delivery_status" => array("\$exists" => true, '$ne' => 'D')),


              )
            ]
          )

        )
      ),
      array(
        '$project' => array(
          'service_id' =>  ['$toString' => '$service_id'],
          'rtps_trans_id'       => 1,
          'mobile'        => 1,
          'app_ref_no'            => 1
        )
      ),
      array(
        '$lookup'  => array(
          'from'         => 'portals',
          'localField'   => 'service_id',
          'foreignField' => 'service_id',
          'as'           => 'portal'
        ),
      ),
      array('$unwind' => '$portal')


    );



    $collection = 'intermediate_ids';
    $data = $this->mongo_db->aggregate($collection, $filter);
    //pre($data);
    return (array)$data;
  }
}
