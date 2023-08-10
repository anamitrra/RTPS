<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Intermediator_model extends Mongo_model
{
 /**
  * __construct
  *
  * @return void
  */
  function __construct()
  {
    parent::__construct();
    $this->set_collection("intermediate_ids");
  }
  function is_exist_transaction_no($rtps_trans_id){

        $count = $this->mongo_db->mongo_like_count(array('rtps_trans_id' => $rtps_trans_id), 'intermediate_ids');
        if($count > 0){
          return true;
        }else {
          return false;
        }
  }
  public function update_payment_status($rtps_trans_id,$data){
    $this->mongo_db->set($data);
    $this->mongo_db->where(array("rtps_trans_id"=> $rtps_trans_id));
    return $this->mongo_db->update('intermediate_ids');
  }
  public function add_param($rtps_trans_id,$data){
    $this->mongo_db->set($data);
    $this->mongo_db->where(array("rtps_trans_id"=> $rtps_trans_id));
    return $this->mongo_db->update('intermediate_ids');
  }
  public function get_userid_by_application_ref($app_ref_no){
    $this->mongo_db->select(array("mobile","service_id","rtps_trans_id","external_service_id","applicant_details","delivery_status"));
    $this->mongo_db->where(array("app_ref_no"=>$app_ref_no));
    $res=$this->mongo_db->find_one("intermediate_ids");
    return $res;
  }
  public function get_transaction_detail($rtps_trans_id){
    $this->mongo_db->select('*');
    $this->mongo_db->where(array("rtps_trans_id"=>$rtps_trans_id));
    $res=$this->mongo_db->find_one("intermediate_ids");
    return $res;
  }
  public function get_application_details($data){
    $this->mongo_db->select('*');
    $this->mongo_db->where($data);
    $res=$this->mongo_db->find_one("intermediate_ids");
    return $res;
  }
  public function get_application_details_array($data){
    $this->mongo_db->select('*');
    $this->mongo_db->where($data);
    $res=$this->mongo_db->get("intermediate_ids");
    return $res;
  }
  public function get_by_rtps_id($id){
    $this->mongo_db->select("*");
    $this->mongo_db->where(array("rtps_trans_id"=>$id));
    $res=$this->mongo_db->find_one("intermediate_ids");
    return $res;
  }
  public function get_row($fillter){
    $this->mongo_db->select("*");
    $this->mongo_db->where($fillter);
    $res=$this->mongo_db->find_one("intermediate_ids");
    return $res;
  }
  public function update_row($fillter,$data){
    $this->mongo_db->set($data);
    $this->mongo_db->where($fillter);
    return $this->mongo_db->update('intermediate_ids');
  }
  public function get_noc_pending_application(){

    $operations = 
      array(
          '$and' => array(
            [
            'portal_no'=>['$in'=>["1",1]],
            'status'=>'S',
            '$or'=>array(
              array("delivery_status" => array("\$exists" => false)),
              array("delivery_status" => array("\$exists" => true,'$ne'=>'D')),
            
             
            )
            ]
          )
             
  );
  
    $collection = 'intermediate_ids';
    $this->mongo_db->select(array("rtps_trans_id","mobile","service_id","app_ref_no","service_name","submission_date","applicant_details.applicant_name","execution_data","sms_delivery_index"));
    return $this->mongo_db->get_data_like($operations, $collection);
    
  } 
   
  public function get_noc_queryable_application(){
    $operations=
    array(
      '$and'=>array(
        [
          'portal_no'=>['$in'=>["1",1]],
          "status"=>"S",
          "delivery_status" => ['$ne'=>'D'],
          "execution_data"=>['$elemMatch'=>['action'=>'Query']]
        ]
      )
        );
        $collection = 'intermediate_ids';

        // service_name,applicant_name,number,submission_date,app_ref_no,submission_office
        $this->mongo_db->select(array("rtps_trans_id","mobile","app_ref_no","service_name","submission_date","applicant_details.applicant_name"));
        return $this->mongo_db->get_data_like($operations, $collection);
  }
  
  public function get_noc_delivered_application(){
    $operations=
    array(
      '$and'=>array(
        [
          'portal_no'=>['$in'=>["1",1]],
          "delivery_status" => 'D',
          '$or'=>array(
            array("is_delivery_sms_send" => array("\$exists" => false)),
            array("is_delivery_sms_send" => array("\$exists" => true,'$ne'=>true)),
          
           
          )
        ]
      )
        );
        $collection = 'intermediate_ids';

        // service_name,applicant_name,number,submission_date,app_ref_no,submission_office
        $this->mongo_db->select(array("rtps_trans_id","mobile","app_ref_no","service_name","submission_date","applicant_details.applicant_name"));
        return $this->mongo_db->get_data_like($operations, $collection);
  }
  public function get_vahan_pending_application(){

    $operations = 
      array(
          '$and' => array(
            [
            'portal_no'=>['$in'=>["2",2]],
            'status'=>'S',
            '$or'=>array(
              array("delivery_status" => array("\$exists" => false)),
              array("delivery_status" => array("\$exists" => true,'$ne'=>'D')),
            
             
            )
            ]
          )
             
  );
  
    $collection = 'intermediate_ids';
    $this->mongo_db->select(array("rtps_trans_id","vahan_app_no","applied_by","pfc_payment_status"));
    return $this->mongo_db->get_data_like($operations, $collection);
    
  }

  public function get_sarathi_pending_application(){

    $operations = 
      array(
          '$and' => array(
            [
            'portal_no'=>['$in'=>["4",4]],
            'status'=>'S',
            '$or'=>array(
              array("delivery_status" => array("\$exists" => false)),
              array("delivery_status" => array("\$exists" => true,'$ne'=>'D')),
            
             
            )
            ]
          )
             
  );
  
    $collection = 'intermediate_ids';
    $this->mongo_db->select(array("rtps_trans_id","app_ref_no","applied_by","pfc_payment_status"));
    return $this->mongo_db->get_data_like($operations, $collection);
    
  }

  public function get_my_transactions($mobile){
    $collection = 'intermediate_ids';

    $fillter=  array(
              '$and' => array(
                [
                'mobile'=>$mobile,
                '$or'=>array(
                  array("is_archived" => array("\$exists" => false)),
                  array("is_archived" => array("\$exists" => true,'$ne'=>true)),
                )
                ]
              )
                 
      );
    $query =  $this->mongo_db->where($fillter);
    return $query->get($collection);
  
  }  
  
  public function get_my_archived_transactions($mobile){
    $collection = 'intermediate_ids';

    $fillter=  array(
              '$and' => array(
                [
                'mobile'=>$mobile,
                "is_archived" => array("\$exists" => true,'$eq'=>true)
                ]
              )
                 
      );
    $query =  $this->mongo_db->where($fillter);
    return $query->get($collection);
  
  }  
  public function get_admin_archived_transactions($applied_by,$role='pfc'){
    $collection = 'intermediate_ids';

    $fillter=  array(
              '$and' => array(
                [
                'applied_by'=> $role ==="pfc" ? new ObjectId($applied_by) : $applied_by,
                "is_archived" => array("\$exists" => true,'$eq'=>true)
                ]
              )
                 
      );
    $query =  $this->mongo_db->where($fillter);
    return $query->get($collection);
  
  }  
  public function get_admin_transactions($applied_by,$role='pfc'){
    $collection = 'intermediate_ids';

    $fillter=  array(
              '$and' => array(
                [
                'applied_by'=> $role === "pfc" ? new ObjectId($applied_by):$applied_by,
                '$or'=>array(
                  array("is_archived" => array("\$exists" => false)),
                  array("is_archived" => array("\$exists" => true,'$ne'=>true)),
                )
                ]
              )
                 
      );
    $query =  $this->mongo_db->where($fillter);
    return $query->get($collection);
  
  }
  
  public function get_admin_pending_transactions($applied_by,$role='pfc'){
    $collection = 'intermediate_ids';

    // $fillter=  array(
    //           '$and' => array(
    //             [
    //             'applied_by'=> $role === "pfc" ? new ObjectId($applied_by):$applied_by,
    //             '$or'=>array(
    //               array("is_archived" => array("\$exists" => false)),
    //               array("is_archived" => array("\$exists" => true,'$ne'=>true)),
    //             )
    //             ]
    //           )
                 
    //   );
        $fillter=  array(
          '$and' => array(
            [
            'applied_by'=> $role === "pfc" ? new ObjectId($applied_by):$applied_by,
            '$or'=>array(
              array("delivery_status" => array("\$exists" => false)),
              array("delivery_status" => array("\$exists" => true,'$ne'=>"D")),
            )
            ]
          )
            
        );
    $query =  $this->mongo_db->where($fillter);
    return $query->get($collection);
  
  }

  public function get_payment_pending_applications(){
    $collection = 'intermediate_ids';

    $fillter=  array(
              '$and' => array(
                [
                'pfc_payment_response'=>array("\$exists"=>false),
                 'payment_params'=>array("\$exists"=>true),
                
                ]
              )
                 
      );
    $this->mongo_db->select(array('department_id','payment_params'));
    $this->mongo_db->where($fillter);
    return $this->mongo_db->get($collection);
  }

  public function get_basundhara_pending_application(){

    $operations = 
      array(
          '$and' => array(
            [
            'portal_no'=>['$in'=>["5",5]],
            'status'=>'S',
            '$or'=>array(
              array("delivery_status" => array("\$exists" => false)),
              array("delivery_status" => array("\$exists" => true,'$nin'=>['D', 'R'])),
            
             
            )
            ]
          )
             
  );
  
    $collection = 'intermediate_ids';
    $this->mongo_db->select(array("rtps_trans_id","mobile","app_ref_no","service_name","submission_date","applicant_details.applicant_name","execution_data","sms_delivery_index"));
    return $this->mongo_db->get_data_like($operations, $collection);
    
  } 

  public function get_basundhara_pending_application_service($service_id,$start_date=null,$end_date=null){
    // echo "strd=".$start_date." endd=".$end_date." service_id=".$service_id;die;
    if(!empty($start_date) && !empty($end_date) ){
    
      $operations = 
      array(
          '$and' => array(
            [
            'portal_no'=>['$in'=>["5",5]],
            'status'=>'S',
            'service_id'=>['$in'=>[intval($service_id),$service_id]],
            'createdDtm'=> [
              "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($start_date) * 1000),
              "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($end_date)) * 1000)
            ],
            '$or'=>array(
              array("delivery_status" => array("\$exists" => false)),
              array("delivery_status" => array("\$exists" => true,'$nin'=>['D', 'R'])),
            
             
            )
            ]
          )
             
  );
    }else{
      $operations = 
      array(
          '$and' => array(
            [
            'portal_no'=>['$in'=>["5",5]],
            'status'=>'S',
            'service_id'=>['$in'=>[intval($service_id),$service_id]],
            '$or'=>array(
              array("delivery_status" => array("\$exists" => false)),
              array("delivery_status" => array("\$exists" => true,'$nin'=>['D', 'R'])),
            
             
            )
            ]
          )
             
  );
    }
  
  // pre( $operations);
    $collection = 'intermediate_ids';
    $this->mongo_db->select(array("rtps_trans_id","mobile","app_ref_no","service_name","submission_date","applicant_details.applicant_name","execution_data","sms_delivery_index"));
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

  public function get_pending_payment_status_update(){

    $operations = 
      array(
          '$and' => array(
            [
            'portal_no'=>['$in'=>["5",5]],
            'status'=>'S',
            'pfc_payment_status'=>'Y',
            '$or'=>array(
              array("payment_status_updated_on" => array("\$exists" => false)),
              array("payment_status_updated_on" => array("\$exists" => true,'$eq'=>false)),
            
             
            )
            ]
          )
             
  );
  
    $collection = 'intermediate_ids';
    $this->mongo_db->select(array("rtps_trans_id","department_id"));
    return $this->mongo_db->get_data_like($operations, $collection);
    
  } 


   public function get_noc_pending_payment_status(){
          $operations = 
          array(
              '$and' => array(
                [
                'portal_no'=>['$in'=>["1",1]],
                'status'=>'S',
                'pfc_payment_status'=>'Y',
                'payment_rtps_end'=>true,
                '$or'=>array(
                  array("payment_status_updated_on_noc" => array("\$exists" => false)),
                  array("payment_status_updated_on_noc" => array("\$exists" => true,'$eq'=>false)),
                )
                ]
              )
                
      );

        $collection = 'intermediate_ids';
        $this->mongo_db->select(array("rtps_trans_id","department_id"));
        return $this->mongo_db->get_data_like($operations, $collection);
   }
   public function get_pending_application_by_portal($portal){


        $filter=array(

          array(
            '$match'=>array(
              '$and' => array(
                [
                'portal_no'=>['$in'=>$portal],
                'status'=>'S',
                '$or'=>array(
                  array("delivery_status" => array("\$exists" => false)),
                  array("delivery_status" => array("\$exists" => true,'$ne'=>'D')),
                
                
                )
                ]
              )
                
                )
        ),
              array(
                '$project' => array(
                  'service_id'=>  ['$toString'=>'$service_id'],
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

   public function get_others_application($app_ref_no){
    $filter=array(
      array(
          '$match' => [
            'app_ref_no' => $app_ref_no
          ]
            ),
          array(
            '$project' => array(
              'service_id'=>  ['$toString'=>'$service_id'],
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
        array('$unwind' => array('path'=>'$portal','preserveNullAndEmptyArrays'=> true)),
      );
    $collection = 'intermediate_ids';
    $data = $this->mongo_db->aggregate($collection, $filter);
    if(  $data ){
      $data=(array)$data;
      return $data[0];
    }else{
      return array();
    }
    

}

   public function get_row_data_by_app_ref_no($app_ref_no,$data){
    $this->mongo_db->select($data);
    $this->mongo_db->where(array("app_ref_no"=>$app_ref_no));
    $res=$this->mongo_db->find_one("intermediate_ids");
    return $res;
  }
  public function get_row_data_by_rtps_trans_no($rtps_trans_id,$data){
    $this->mongo_db->select($data);
    $this->mongo_db->where(array("rtps_trans_id"=>$rtps_trans_id));
    $res=$this->mongo_db->find_one("intermediate_ids");
    return $res;
  }

  public function get_bas_payment_pending_applications(){
      $filter= array(
        'service_id' => array('$in' => array(243, 244, 245, 246, 247, 248)),
        'pfc_payment_status' => array('$in' => array('', 'P'))
    );

    $collection = 'intermediate_ids';
    $this->mongo_db->select(array('rtps_trans_id','department_id','payment_params'));
    // $this->mongo_db->limit(5);
    return $this->mongo_db->get_data_like($filter, $collection);

  }
  public function check_if_pending($rtps_trans_id){
    $filter= array(
      'rtps_trans_id' => $rtps_trans_id,
      'delivery_status' => array('$ne' => 'D')
  );

    $this->mongo_db->select(array('rtps_trans_id','app_ref_no','mobile'));
    $this->mongo_db->where($filter);
    $res=$this->mongo_db->find_one("intermediate_ids");
    return $res;
  }


  public function apps_ref($app_ref_no){
    $operations=array(

      array(
        '$match'=>array(
          '$or'=>array(
            array("app_ref_no" => $app_ref_no),
            array("vahan_app_no" => $app_ref_no)
            )
            )
          ),
          array(
            '$project' => array(
                'service_id'=>  ['$toString'=>'$service_id'],
                'rtps_trans_id'       => 1,
                'mobile'        => 1,
                'portal_no'        => 1,
                'applicant_name'        => 1,
                'app_ref_no'            => 1,
                'vahan_app_no'            => 1,
                'submission_date'            => 1,
                'status'            => 1,
                'service_name'            => 1,
                'applicant_details'            => 1,
                'applied_by'            => 1,
                'pfc_payment_status'            => 1,
                'portal.timeline_days'=>1,
                'createdDtm'=>1
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
          array('$unwind' => array('path'=>'$portal','preserveNullAndEmptyArrays'=> true)),

       );
        $collection = 'intermediate_ids';

        $data = $this->mongo_db->aggregate($collection, $operations);
    if(  $data ){
      $data=(array)$data;
      return $data[0];
    }else{
      return array();
    }
  }

  public function myapps($filter,$limit){
    $operations=array(

      array(
        '$match'=>$filter
          ),
          array(
            '$project' => array(
                'service_id'=>  ['$toString'=>'$service_id'],
                'rtps_trans_id'       => 1,
                'mobile'        => 1,
                'portal_no'        => 1,
                'applicant_name'        => 1,
                'app_ref_no'            => 1,
                'vahan_app_no'            => 1,
                'submission_date'            => 1,
                'status'            => 1,
                'service_name'            => 1,
                'delivery_status' =>1,
                'applicant_details'            => 1,
                'applied_by'            => 1,
                'pfc_payment_status'            => 1,
                'portal.timeline_days'=>1,
                'createdDtm'=>1
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
          array('$unwind' => array('path'=>'$portal','preserveNullAndEmptyArrays'=> true)),
          [
            '$sort' => [
                'createdDtm' => -1
            ]
          ],
       );
       if($limit){
        $operations[]=array('$limit'=>$limit);
       }
     
        $collection = 'intermediate_ids';

        $data = $this->mongo_db->aggregate($collection, $operations);
    if(  $data ){
      $data=(array)$data;
      return $data;
    }else{
      return array();
    }
  }

  public function get_my_transactions_by_trans($rtps_trans_id){
    $collection = 'intermediate_ids';

    $fillter=  array(
              '$and' => array(
                [
                'rtps_trans_id'=>$rtps_trans_id,
                '$or'=>array(
                  array("is_archived" => array("\$exists" => false)),
                  array("is_archived" => array("\$exists" => true,'$ne'=>true)),
                )
                ]
              )
                 
      );
    $query =  $this->mongo_db->where($fillter);
    return $query->get($collection);
  
  }  

}
