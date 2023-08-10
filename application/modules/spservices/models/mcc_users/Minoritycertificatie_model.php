<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Minoritycertificatie_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("minoritycertificates");
    }//End of __construct()
    


        function trasfer_to($userUniqueId,$userToUniqueId) {

            // Getting unique_user_id from office_users collection.(eg. RgvG1)
            $from_id = $userUniqueId[0]['unique_user_id'];
            $to_id = $userToUniqueId[0]['unique_user_id'];


pre($to_id);
            // Transfer from
            // Finding minoritycertificate with execution_data.task_details.user_detail.user_id == from_id.(eg. RgvG1)
            $this->mongo_db->where('execution_data.0.task_details.user_detail.user_id', $from_id);
            $this->mongo_db->where('execution_data.0.task_details.action_taken', 'N');
            $data1 = $this->mongo_db->get('minoritycertificates');



            $dataTransferFrom = json_decode(json_encode($data1), true);



            // Trasnfer to
            // Finding minoritycertificate with execution_data.task_details.user_detail.user_id == to_id.(eg. RgvG1)
             $this->mongo_db->where('execution_data.0.task_details.user_detail.user_id', $to_id);
             $data2 = $this->mongo_db->get('minoritycertificates');

             $dataTransferTo = json_decode(json_encode($data2), true);


             if(!isset($dataTransferTo[0])){
                $this->session->set_flashdata('error','Application can not be transfered!');
                return redirect(base_url().'spservices/office_users/admin/application_transfer');

            }
           



                // Data we need to change
                // pre($dataTransferFrom[0]['execution_data'][0]['task_details']['user_detail']['user_name']);

        


                // Data we need to exchange with
                // pre($dataTransferTo[0]['execution_data'][0]['task_details']['user_detail']);
                $id = $dataTransferTo[0]['execution_data'][0]['task_details']['user_detail']['user_id'];

                

             
             

        
                    $user_details_by_user_id = $this->mongo_db->where(['execution_data.0.task_details.user_detail.user_id'=>$from_id])->get($this->table);

                   $total_chages = json_decode(json_encode($user_details_by_user_id), true);

                 
                   
                
            
                
                // Getting data which are need to exchange
                $user_name = $dataTransferTo[0]['execution_data'][0]['task_details']['user_detail']['user_name'];
                $sign_no = $dataTransferTo[0]['execution_data'][0]['task_details']['user_detail']['sign_no'];
                $mobile_no = $dataTransferTo[0]['execution_data'][0]['task_details']['user_detail']['mobile_no'];
                $location_id = $dataTransferTo[0]['execution_data'][0]['task_details']['user_detail']['location_id'];
                $location_name = $dataTransferTo[0]['execution_data'][0]['task_details']['user_detail']['location_name'];
                $circle = $dataTransferTo[0]['execution_data'][0]['task_details']['user_detail']['circle'];
                $district = $dataTransferTo[0]['execution_data'][0]['task_details']['user_detail']['district'];
                $email = $dataTransferTo[0]['execution_data'][0]['task_details']['user_detail']['email'];
                $designation = $dataTransferTo[0]['execution_data'][0]['task_details']['user_detail']['designation'];
                $role = $dataTransferTo[0]['execution_data'][0]['task_details']['user_detail']['role'];
                $role_slug = $dataTransferTo[0]['execution_data'][0]['task_details']['user_detail']['role_slug'];
              

                $data = [
                    'execution_data.0.task_details.user_detail.user_name' => $user_name,
                    'execution_data.0.task_details.user_detail.sign_no'=> $sign_no,
                    'execution_data.0.task_details.user_detail.mobile_no'=> $mobile_no,
                    'execution_data.0.task_details.user_detail.location_id'=> $location_id,
                    'execution_data.0.task_details.user_detail.location_name'=> $location_name,
                    'execution_data.0.task_details.user_detail.circle'=> $circle,
                    'execution_data.0.task_details.user_detail.district'=> $district,
                    'execution_data.0.task_details.user_detail.email'=> $email,
                    'execution_data.0.task_details.user_detail.designation'=> $designation,
                    'execution_data.0.task_details.user_detail.role'=> $role,
                    'execution_data.0.task_details.user_detail.role_slug'=> $role_slug
                    
                ];

            
                
               
        

                    $this->mongo_db->where(["execution_data.0.task_details.user_detail.user_id"=>$from_id ]);
                    $this->mongo_db->set($data);
                    $this->mongo_db->update_all($this->table);
                     
                  

         }



}
