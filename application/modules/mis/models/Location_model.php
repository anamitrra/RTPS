<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Location_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->mongo_db->switch_db("appeal");
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
                ['location_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
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
}