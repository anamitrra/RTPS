<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Users_model extends Mongo_model
{
    public $table = 'users';
    public $id = 'userId';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
   /**
    * get_all
    *
    * @return void
    */
//    function get_all()
//    {
//        $this->mongo_db->order_by($this->id, $this->order);
//        return $this->mongo_db->get($this->table);
//    }
    // get all
   /**
    * get_users_of_role
    *
    * @return void
    */
    function get_users_of_role()
    {
        $roles=get_appeal_management_system_roles();
        $filter=[
            '$or'=>[
                ['roleId'=>new ObjectId($roles["DPS"])],
                ['roleId'=>new ObjectId($roles["Appellate_Authority"])],
                ['roleId'=>new ObjectId($roles["Reviewing_Authority"])],
            ]
        ];
        $this->mongo_db->where($filter);
        return $this->mongo_db->get($this->table);
    }

    // get data by id
   /**
    * get_by_id
    *
    * @param mixed $id
    * @return void
    */
    function get_by_id($id)
    {
        $this->mongo_db->where($this->id, $id);
        $obj = $this->mongo_db->find_one($this->table);
        // $temp = (array) $obj;
        // return $temp[0];

        return $obj;
    }

   /**
    * get_by_email
    *
    * @param mixed $email
    * @return void
    */
    function get_by_email($email)
    {
        $this->mongo_db->select(array("email"));
        $this->mongo_db->where("email", $email);
        $obj = $this->mongo_db->find_one($this->table);
        return $obj;
    }

   /**
    * get_by_doc_id
    *
    * @param mixed $id
    * @return void
    */
    function get_by_doc_id($id)
    {
        $this->mongo_db->where("_id", new MongoDB\BSON\ObjectId($id));
        $obj = $this->mongo_db->find_one($this->table);
        return $obj;

        /*         var_dump($obj);
        $temp=(array)$obj;
        return $temp[0]; */
    }

//    // get total rows
//   /**
//    * total_rows
//    *
//    * @return void
//    */
//    function total_rows()
//    {
//        return $this->mongo_db->mongo_count($this->table);
//    }

   /**
    * all_rows
    *
    * @param mixed $limit
    * @param mixed $start
    * @param mixed $col
    * @param mixed $dir
    * @param mixed $filter
    * @return void
    */
    function all_rows($limit, $start, $col, $dir, $filter = NULL)
    {
        $this->mongo_db->limit($limit, $start);
        $this->mongo_db->order_by($col, $dir);
        $query = $this->mongo_db->get($this->table);
        return $query;
    }
//
//    // get search total rows
//   /**
//    * search_rows
//    *
//    * @param mixed $keyword
//    * @return void
//    */
//    function search_rows($keyword = NULL)
//    {
//        $this->mongo_db->like('name', $keyword);
//        $this->mongo_db->like('mobile', $keyword);
//        $this->mongo_db->limit($limit, $start);
//        $this->mongo_db->order_by($col, $dir);
//        return $this->mongo_db->get_like($this->table);
//    }

    // get search data with limit
   /**
    * tot_search_rows
    *
    * @param mixed $limit
    * @param mixed $start
    * @param mixed $keyword
    * @return void
    */
    function tot_search_rows($limit, $start = 0, $keyword = NULL)
    {
        $this->mongo_db->like('appl_ref_no', $keyword);
        $this->mongo_db->like('name_of_traveller', $keyword);
        $this->mongo_db->like('mobile_no_of_traveller', $keyword);
        $this->mongo_db->like('category_of_travelers', $keyword);
        $this->mongo_db->like('travelling_as', $keyword);
        $this->mongo_db->like('travel_arrangement', $keyword);
        return $this->mongo_db->get_like($this->table);
    }

    // insert data
   /**
    * insert
    *
    * @param mixed $data
    * @return void
    */
    function insert($data)
    {
        $this->mongo_db->insert($this->table, $data);
    }

    // update data
   /**
    * update
    *
    * @param mixed $id
    * @param mixed $data
    * @return void
    */
    function update($id, $data)
    {

        $this->mongo_db->set($data);
        $this->mongo_db->where("_id", new MongoDB\BSON\ObjectId($id));
        $this->mongo_db->update($this->table, $data);
    }

    // delete data
   /**
    * delete
    *
    * @param mixed $id
    * @return void
    */
    function delete($id)
    {
        $this->mongo_db->where("_id", new MongoDB\BSON\ObjectId($id));
        $this->mongo_db->delete($this->table);
    }

    // get where in array
    /**
     * get_where_in
     *
     * @param mixed $whereInConditions
     * @param mixed $userListFilter
     * @param mixed $columns
     * @return void
     */
    public function get_where_in($whereInConditions, $userListFilter = [], $columns = [])
    {
        $rows = $this->mongo_db;
        if (!empty($whereInConditions)) {
            foreach ($whereInConditions as $field => $inArray) {
                $rows = $rows->where_in($field, $inArray);
            }
        }
        if (!empty($userListFilter)) {
            $rows = $rows->where($userListFilter);
        }
        if (!empty($columns)) {
            $rows = $rows->select($columns);
        }
        return $rows->get($this->table);
    }

    /**
     * get_where
     *
     * @param mixed $filter
     * @param mixed $value
     * @return void
     */
    public function get_where($filter, $value = '')
    {
        if (is_array($filter)) {
            $this->mongo_db->where($filter);
        } else {
            $this->mongo_db->where($filter, $value);
        }
        return $this->mongo_db->get($this->table);
    }
}
/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */
