<?php 
  use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
  
}

class Services_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('services');
    }

     // get all
    function all() {
        return $this->mongo_db->get("services");
    }

    function all_dept() {
        return $this->mongo_db->get("departments");
    }

// delete data
  function delete_service($id) {
    $this->mongo_db->where('_id', $id);
    $this->mongo_db->delete("services");
  }

// get collection data
public function get_service($id)
{
    $this->mongo_db->where("_id", $id); 
    return $this->mongo_db->get("services");   
}

public function get_service_by_service_id($id)
{
    $this->mongo_db->where("service_id", $id); 
    return $this->mongo_db->get("services");   
}

public function get_service_name($id)
{
    $this->mongo_db->where("_id", new ObjectId($id));
    return $this->mongo_db->get($this->table);
}


  public function get_mapped_service_list(){
    return $this->mongo_db->aggregate($this->table, array(
            array(
              '$match'=>[
                "service_timeline"=>['$nin'=>['0',0]]
              ]
            ),
            array(
                '$lookup' => [
                    'from' => 'official_details',
                    'localField' => '_id',
                    'foreignField' => 'service_id',
                    'as' => 'filltered_services'
                ]
            ),
            array('$unwind' => '$filltered_services'),
            array(
              '$group'=>[
                '_id'=>'$_id',
                'service_id'=> ['$first' => '$service_id'],
                'service_name'=> ['$first' => '$service_name'],
                "service_timeline"=>['$first' => '$service_timeline'],
              ]
              ),
            // array(
            //     '$project' => [
            //         '_id' => 1,
            //         'service_name' => 1,
            //     ]
            // ),
            array(
                '$sort' => [ "service_name" => 1 ]
            )
        ));
      }


      public function service_search_rows($limit, $start, $keyword, $col, $dir,$filter=null)
      {
          
          $orWhereFilter = [
                'service_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i'],
                // 'email' =>['$regex'=>'^' . $keyword . '','$options' => 'i'],
                // 'mobile' =>['$regex'=>'^' . $keyword . '','$options' => 'i'],
          ];
          $this->mongo_db->limit($limit, $start);
          $this->mongo_db->order_by($col, $dir); 
          if(!empty($filter)){
          $this->mongo_db->where($filter);
          }
          $this->mongo_db->where_or($orWhereFilter);
          return $this->mongo_db->get($this->table);
      }
  
}