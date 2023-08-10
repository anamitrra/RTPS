<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Office_users_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("office_users");
    }//End of __construct()
    
    public function get_row($filter = null) {
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->find_one($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_row()
    
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
    
   

    public function getUserPhoneNumber($phoneNumber)
	{

		$data = $this->mongo_db->select(['mobile','is_active'])->where(['mobile'=>$phoneNumber])->get("office_users");
        return $data;

	}

    public function getUserUniqueId($userUniqueId)
	{

		$data = $this->mongo_db->select(['_id'])->where(['unique_user_id'=>$userUniqueId])->get("office_users");
        return $data;

	}


    function getOfficeUsers($user_role, $dist_id, $cir_name) {

        if(strlen($cir_name) == 0){
            $data = $this->mongo_db->select(['name','mobile','user_role','district_name','circle_name'])->where(['district_id'=>$dist_id, 'user_role'=>$user_role])->get("office_users");   
           
            return $data;
        }


        else{
            $data = $this->mongo_db->select(['name','mobile','user_role','district_name','circle_name'])->where(['district_name'=>$dist_name, 'user_role'=>$user_role,'circle_name'=>$cir_name ])->get("office_users");    
            return $data;
        }



        
    
        }


        function transfer($user_role, $dist_id) {

  
                $data = $this->mongo_db->select(['_id'])->where(['district_id'=>$dist_id, 'user_role'=>$user_role,'is_active'=>1])->get("office_users");   
               
                return $data;
            
    
        
        
            }



            function getOfficeUsersList($user_role, $dist_id) {

     
                    $data = $this->mongo_db->select(['name','mobile','user_role','district_name','circle_name'])->where(['district_id'=>$dist_id, 'user_role'=>$user_role])->get("office_users");   
               
                   
                    return $data;
                
            }

            function getOfficeUsersListByCircle($user_role, $dist_id, $user_circle) {

                $data = $this->mongo_db->select(['name','mobile','user_role','district_name','circle_name'])->where(['district_id'=>$dist_id, 'user_role'=>$user_role,'circle_name'=>$user_circle ])->get("office_users");    
       
               
                return $data;
            
        }

        // Active office user

        function active_office_user_list($user_role, $dist_id) {

  
            $data = $this->mongo_db->select(['_id','name'])->where(['district_id'=>$dist_id, 'user_role'=>$user_role,'is_active'=>1])->get("office_users");   
           
            return $data;
    
        }

        function active_office_user_list_by_circle($user_role, $dist_id, $user_circle) {

  
            $data = $this->mongo_db->select(['_id','name'])->where(['district_id'=>$dist_id, 'user_role'=>$user_role,'circle_name'=>$user_circle ,'is_active'=>1])->get("office_users");   
           
            return $data;
    
        }


        // Finding transfer from office user from minoritycertificates collection

        function trasferFrom($transferFrom) {

  
            $data = $this->mongo_db->select(['_id','name'])->where(['district_id'=>$dist_id, 'user_role'=>$user_role,'is_active'=>1])->get("office_users");   
           
            return $data;
    
        }



}
