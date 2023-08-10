<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Office_user_manage_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("office_users");
    }//End of __construct()



    function getOfficeUsers() {
	// $data = $this->mongo_db->select(['circle_id'])->where(['circle_name'=>$cir])->get("circles");
    // $users = $cursor->toArray();
    //     return $users;

    // $users = ['a','b','c'];
    // return $users;

    // $applications = $this->mongo_db->order_by('_id', 'DESC')->get($this->table);
    $users = $this->mongo_db->order_by('_id', 'DESC')->get($this->table);
 
    return $users;

    }


    function get_single_office_user($id){
        $user = $this->mongo_db->where(["_id" => new MongoDB\BSON\ObjectID($id)])->get($this->table);
        return $user;
    }


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



    

    function hello() {
        $data = "Hello World";
        return $data;
    

    }

    // public function updateUser()
	// {
	

    //     $this->mongo_db->updateMany(
    //         [ '_id' => "62ce5230b9cf6ac3ec0c941c" ],
    //         [ '$set' => [ 'name' => 'bjd' ]]
    //         );
	// }


    public function updateUser($data,$id) {
        
        // $data = [
        //     'name' => 'The One and Only King',
        // ];
        $option = array('upsert' => true);

        $this->mongo_db->where(["_id"=> new ObjectId($id)]);
        $this->mongo_db->set($data);

        return $this->mongo_db->update($this->table, $option);

          

    }

    


    function updateUser1() {
        try {
            $result = $this->mongo_db->updat(
                ['_id' => new \MongoDB\BSON\ObjectId("62ce5230b9cf6ac3ec0c941c")],
                ['$set' => [
                    'name' => 'bjd',
                    
                ]]
            );

            if($result->getModifiedCount()) {
                return true;
            }

            return false;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while updating a book with ID: ' . $id . $ex->getMessage(), 500);
        }
    }

 


}
