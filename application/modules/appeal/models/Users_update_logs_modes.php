<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Users_update_logs_modes extends Mongo_model
{

    function __construct()
    {
        parent::__construct();
        parent::__construct();
        $this->set_collection("users_update_logs");
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
    public function users_search_rows($limit, $start, $keyword, $col, $dir,$filter=null)
    {
        // $temp = array(
        //     '$or'=>[
        //       ['name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
        //       ['email' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
        //       ['mobile' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
        //     ]
        // );
        $orWhereFilter = [
              'name' =>['$regex'=>'^' . $keyword . '','$options' => 'i'],
              'email' =>['$regex'=>'^' . $keyword . '','$options' => 'i'],
              'mobile' =>['$regex'=>'^' . $keyword . '','$options' => 'i'],
        ];
       
     
        $this->mongo_db->limit($limit, $start);
        $this->mongo_db->order_by($col, $dir); 
        if(!empty($filter)){
        $this->mongo_db->where($filter);
        }
        $this->mongo_db->where_or($orWhereFilter);
        return $this->mongo_db->get($this->table);

        //print_r($temp);
      // return $this->search_rows($limit, $start, $temp, $col, $dir);
    }

    /**
     * users_tot_search_rows
     *
     * @param mixed $keyword
     * @return void
     */
    public function users_tot_search_rows($keyword,$filter=null)
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
                'department'     => 1,
                'photo'          => 1,
                'office_address' => 1,
                'office_name'    => 1,
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
                'department'     => 1,
                'photo'          => 1,
                'office_address' => 1,
                'office_name'    => 1,
                'user_role'      => '$user_role',
            ))
        );
        //pre($operation);
        return $this->mongo_db->aggregate($this->table,$operation);
    }

    public function check_role_exits($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
             $this->mongo_db->where("_id",new ObjectId($obj));
            return $this->mongo_db->find_one("roles");
           
        }else{
            return false;
        }
       
    }
    public function check_dept_exits($obj){
        if($obj){
            $this->mongo_db->where("department_id",$obj);
            return $this->mongo_db->find_one("departments");
        }else{
            return false;
        }
       
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

    // for inactive user
    public function total_rows_inactive($filter)
    {
        $this->mongo_db->where($filter);
        return $this->mongo_db->count($this->table);
        
    }
    public function verify_user($obj,$data){
        // $this->mongo_db->where("_id",new ObjectId($obj));
        // $this->mongo_db->update($this->table);

        $this->mongo_db->set($data);
        $this->mongo_db->where("_id",new ObjectId($obj));
        return  $this->mongo_db->update($this->table);
    }

    public function get_by_name($name)
    {
        $this->mongo_db->where("name", $name);
        return $this->mongo_db->get($this->table);
    }

    public function getUserEmailAddress($email,$userId=null)
	{
        if($userId){
            $data = $this->mongo_db->select(['email'])->where(['email'=>$email,'_id'=>['$ne'=>new ObjectId($userId)]])->get("users");
            return $data;
        }else{
            $data = $this->mongo_db->select(['email'])->where(['email'=>$email])->get("users");
            return $data;
        }
		

	}

    public function get_by_id($userId)
	{
            $data = $this->mongo_db->select(['email','name','mobile'])->where(['_id'=>new ObjectId($userId)])->get("users");
            return $data;

	}


}
/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */
