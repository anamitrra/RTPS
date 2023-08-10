<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Department_model extends Mongo_model
{
   /**
    * __construct
    *
    * @return void
    */
    function __construct()
    {
        parent::__construct();
        // $this->mongo_db->switch_db("appeal");
        $this->set_collection("departments");
    }
    /**
     * get_services
     *
     * @param mixed $dept_id
     * @return void
     */
    public function get_services($dept_id = NULL)
    {
        if ($dept_id != NULL) {
            $filter = array(
                "department_id" => "" . $dept_id . ""
            );
            $dept = $this->get_all($filter);
            if (isset($dept->{'0'})) {
                return $dept->{'0'};
            } else {
                return FALSE;
            }
        }
    }
    /**
     * get_departments
     *
     * @return void
     */
    public function get_departments()
    {
        //$this->mongo_db->select(array("department_id","department_name"));
        $depts = $this->mongo_db->get($this->table);
        return $depts;
    }
    /**
     * department_search_rows
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $keyword
     * @param mixed $col
     * @param mixed $dir
     * @return void
     */
    public function department_search_rows($limit, $start, $keyword, $col, $dir)
    {
        $temp = array(
            '$or'=>[
              ['department_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            ]
        );
        //print_r($temp);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }
    /**
     * department_tot_search_rows
     *
     * @param mixed $keyword
     * @return void
     */
    public function department_tot_search_rows($keyword)
    {
      $temp = array(
        '$or'=>[
          ['department_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
        ]
    );
        //print_r($temp);
        return $this->tot_search_rows($temp);
    }
    /**
     * get_department_list
     *
     * @return void
     */
    public function get_department_list()
    {
        return $this->mongo_db->select(array('department_id', 'department_name'))->get($this->table);
    }
    /**
     * get_service_list_by_department_id
     *
     * @param mixed $dept_id
     * @return void
     */
    public function get_service_list_by_department_id($dept_id)
    {
        $this->set_collection("services");
        $filter = array('department_id' => strval($dept_id));
        $select = array('service_id', 'service_name');
        return (array) $this->mongo_db->select($select)->where($filter)->get($this->table);
    }
    // delete data
   /**
    * delete_department
    *
    * @param mixed $id
    * @return void
    */
    function delete_department($id)
    {
        $this->mongo_db->where('_id', $id);
        $this->mongo_db->delete($this->table);
    }
    // get collection data
    /**
     * get_department
     *
     * @param mixed $id
     * @return void
     */
    public function get_department($id)
    {
        $this->mongo_db->where("_id", $id);
        return $this->mongo_db->get($this->table);
    }
    public function get_department_by_id($department_id)
    {
        $this->mongo_db->where("department_id", $department_id);
        $data=(array) $this->mongo_db->get($this->table);

        // print_r($data);
        // exit();

        if (empty($data)) {
            return NULL;
        }

        return $data[0];
    }
}
