<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Users_model extends Mongo_model
{

    function __construct()
    {
        parent::__construct();
        parent::__construct();
        $this->set_collection("users");
    }

    /* get_users_of_role
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
    /**
     * users_search_rows
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $keyword
     * @param mixed $col
     * @param mixed $dir
     * @return void
     */
    public function users_search_rows($limit, $start, $keyword, $col, $dir)
    {
        $temp = array(
            '$or'=>[
              ['name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['email' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['mobile' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            ]
        );
        //print_r($temp);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }

    /**
     * users_tot_search_rows
     *
     * @param mixed $keyword
     * @return void
     */
    public function users_tot_search_rows($keyword)
    {
      $temp = array(
        '$or'=>[
            ['name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            ['email' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            ['mobile' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
          ]
    );
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

    function get_by_id($id)
    {
        $this->mongo_db->select("*");
        $this->mongo_db->where("_id", new ObjectId($id));
        $obj = $this->mongo_db->find_one($this->table);
        return $obj;
    }

    public function get_user_info($filter){
        $operation = array(
            array(
                '$match' => $filter
            ),
            array(
                '$lookup' => array(
                    'from' => 'roles',
                    'localField' => 'roleId',
                    'foreignField' => '_id',
                    'as' => 'user_role',
                )
            ),
            array('$unwind' => '$user_role'),
            array('$project'  => array(
                '_id'            => 0,
                'email'          => 1,
                'password'       => 1,
                'name'           => 1,
                'designation'    => 1,
                'mobile'         => 1,
                'roleId'         => 1,
                'photo'          => 1,
                'office_address' => 1,
                'office_code'    => 1,
                'dept_code'    => 1,
                'account1'    => 1,
                'user_role'      => '$user_role',
            ))
        );
        //pre($operation);
        return $this->mongo_db->aggregate($this->table,$operation)->{0};
    }

    public function get_user_with_role($filter){
        $operation = array(
            array(
                '$match' => ['roleId'=>new ObjectId($filter)]
            ),
            array(
                '$lookup' => array(
                    'from' => 'roles',
                    'localField' => 'roleId',
                    'foreignField' => '_id',
                    'as' => 'user_role',
                )
            ),
            array('$unwind' => '$user_role'),
            array('$project'  => array(
                '_id'            => 0,
                'email'          => 1,
                'password'       => 1,
                'name'           => 1,
                'designation'    => 1,
                'mobile'         => 1,
                'roleId'         => 1,
                // 'department'     => 1,
                'photo'          => 1,
                'office_address' => 1,
                'office_code'    => 1,
                'dept_code'    => 1,
                'account1'    => 1,
                'user_role'      => '$user_role',
            ))
        );
        //pre($operation);
        return $this->mongo_db->aggregate($this->table,$operation);
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
    // public function get_where_in($whereInConditions, $userListFilter = [], $columns = [])
    // {
    //     $rows = $this->mongo_db;
    //     if (!empty($whereInConditions)) {
    //         foreach ($whereInConditions as $field => $inArray) {
    //             $rows = $rows->where_in($field, $inArray);
    //         }
    //     }
    //     if (!empty($userListFilter)) {
    //         $rows = $rows->where($userListFilter);
    //     }
    //     if (!empty($columns)) {
    //         $rows = $rows->select($columns);
    //     }
    //     return $rows->get($this->table);
    // }
}
/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */
