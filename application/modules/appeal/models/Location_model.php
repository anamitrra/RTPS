<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Location_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('locations');
    }

     // get all
    function all() {
        return $this->mongo_db->get("locations");
    }
    /**
     * locations_search_rows
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $keyword
     * @param mixed $col
     * @param mixed $dir
     * @return void
     */
    public function locations_search_rows($limit, $start, $keyword, $col, $dir)
    {
        $temp = array(
            '$or'=>[
                ['location_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
                ['location_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
                ['department_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
              ]
        );
        //print_r($temp);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }
    /**
     * locations_tot_search_rows
     *
     * @param mixed $keyword
     * @return void
     */
    public function locations_tot_search_rows($keyword)
    {
      $temp = array(
        '$or'=>[
          ['location_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
          ['location_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
        ]
    );
        //print_r($temp);
        return $this->tot_search_rows($temp);
    }
    function all_dept() {
        return $this->mongo_db->get("departments");
    }

// delete data
  function delete_location($id) {
    $this->mongo_db->where('_id', $id);
    $this->mongo_db->delete("locations");
  }

// get collection data
public function get_location($id)
{
    $this->mongo_db->where("_id", $id); 
    return $this->mongo_db->get("locations");     
}

public function get_location_id_by_dept_id($id){
    $this->mongo_db->where("department_id", $id); 
    return $this->mongo_db->get("locations"); 
}

public function get_location_doc_id_by_loc_id($id){
    $this->mongo_db->where("location_id", $id); 
    return $this->mongo_db->get("locations"); 
}

public function getLocationIds($location_id)
{
    $data = $this->mongo_db->select(['location_id'])->where(['location_id'=>$location_id])->get($this->table);
    return $data;

}

public function get_location_name($id)
{
    $this->mongo_db->where("_id", new ObjectId($id)); 
    return $this->mongo_db->get("locations");     
}

public function get_mapped_location_ids($services_id){
    $this->mongo_db->where("service_id", new ObjectId($services_id)); 
    return $this->mongo_db->get("official_details");  
}
public function get_location_list_by_ids($location_id){
    return $this->get_where_in("_id",$location_id); 
}
public function get_rows($where){
    $this->mongo_db->where($where); 
    return $this->mongo_db->get("locations");     
}
public function get_mapped_location_list($services_id){
    return $this->mongo_db->aggregate($this->table, array(
        // array(
            
        //     '$match' => [
        //         '_id' => new ObjectId("5f6841091b172967105d7f28"),
        //     ]
        // ),
        // array(
        //     '$match'  => ['official_details.service_id'=>new ObjectId($services_id)]
        // ),
            array(
                '$lookup' => [
                    'from' => 'official_details',
                    // 'localField' => '_id',
                    // 'foreignField' => 'location_id',
                    'let'=>array('service_id'=>'$service_id','uId'=>'$_id'),
                    // 'let' => array('department_id' => '$department_id'),
                    'pipeline'=>array(
                        // array(
                        //     '$match'  => ['$service_id'=>new ObjectId($services_id)]
                        // )
                        array(
                            '$match' => array(
                                '$expr' => array(
                                    '$and' => array(
                                        array('$eq' => ['$service_id', new ObjectId($services_id)] ),
                                        array('$eq' => ['$location_id', '$$uId'] ),
                                        
                                    )
                                )
                               
                            )
                        )
                        ),
                    'as' => 'filltered_locations'
                ]
            ),
        //    array('$unwind' => '$filltered_locations'),
           array('$unwind' => array('path'=>'$filltered_locations','preserveNullAndEmptyArrays'=> true)),
         
            // array(
            //     '$project' => [
            //         '_id' => 1,
            //         'service_name' => 1,
            //     ]
            // ),
            array(
                '$sort' => [ "location_name" => 1 ]
            )
        ));
      }


      public function getLocationByDept($department_id,$base_department_id){
                if($base_department_id){
                    $where=array('department_id'=>$base_department_id);
                }else{
                    $where=array('department_id'=>$department_id);
                }
                $this->mongo_db->where($where); 
                 return $this->mongo_db->get("locations");   
      }
}