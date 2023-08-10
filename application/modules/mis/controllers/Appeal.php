<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Appeal extends Rtps
{
    //put your code here
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //hasPermission();
        $role = $this->session->userdata("role");
        $data = [];
        $this->load->view("includes/header", array("pageTitle" => "Appeal"));
        $this->load->view("appeal/index", $data);
        $this->load->view("includes/footer");
    }

    public function count_appeals_location()
    {

            
        if($this->session->userdata['role']->slug === "SA"){
            $this->load->model('appeal_stored_api_model');
            $appeal_count = $this->appeal_stored_api_model->last_where(['api_type' => 'count_appeals']);
            
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'total' => $appeal_count->total ?? 0,
                    'new' => $appeal_count->new??0,
                    'pending_appeals_beyond_30days_not_beyond_45days' =>$appeal_count->pending_appeals_beyond_30days_not_beyond_45days??0,
                    'pending_appeals_beyond_45days' =>$appeal_count->pending_appeals_beyond_45days??0,
                    'disposed_appeals_within_30days' =>$appeal_count->disposed_appeals_within_30days??0,
                    'resolved' => $appeal_count->resolved??0,
                    'rejected' => $appeal_count->rejected??0,
    
                )));
        }else{
            if(!empty($this->session->userdata['location'])){
          
                $this->load->model('appeal_stored_api_model');
                $appeal_count = $this->appeal_stored_api_model->last_where(['api_type' => 'count_appeals_location','location_id'=>new ObjectId($this->session->userdata['location'])]);
                
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array(
                        'total' => $appeal_count->total ?? 0,
                        'new' => $appeal_count->new??0,
                        'pending_appeals_beyond_30days_not_beyond_45days' =>$appeal_count->pending_appeals_beyond_30days_not_beyond_45days??0,
                        'pending_appeals_beyond_45days' =>$appeal_count->pending_appeals_beyond_45days??0,
                        'disposed_appeals_within_30days' =>$appeal_count->disposed_appeals_within_30days??0,
                        'resolved' => $appeal_count->resolved??0,
                        'rejected' => $appeal_count->rejected??0,
        
                    )));
            }
        }
     
      
    }

    public function find_appeal()
    {
        $appeal_id=$this->input->post('appeal_id');
        $this->load->model('appeal_application_model');
        if($appeal_id){
            $ref_id=$this->appeal_application_model->get_ref_id_by_appeal_id($appeal_id);
            if(!empty($ref_id)){
                $appeal = $this->appeal_application_model->get_by_doc_id($ref_id);
                $this->load->helper('model');
                $appealApplication = getAppealApplications($appeal, $appeal->{'_id'}->{'$id'});
                if (!isset($appealApplication) && empty($appealApplication)) {
                   echo "No Appeal Found";
                }
                $data = [
                    'appealApplication' => $appealApplication,
                ];
                echo $this->load->view("appeal/view_appeal_application" , $data);
            }else{
                echo "No Appeal Found";
            }
           
        }else{
            echo "No Appeal Found";
        }
       
       
    }

    public function first_appeal(){
        $this->load->model("department_model");
        $data = [];
          $data['departments']=$this->department_model->get_departments();
       
        $this->load->view("includes/header", array("pageTitle" => "Appeal"));
        $this->load->view("appeal/first_appeal", $data);
        $this->load->view("includes/footer");
    }
    public function find_first_appeal(){
      $this->load->model('api_model');
       $this->load->model('services_model');
       $service_id=false;
       $service_name="No Services Selected";
       if(!empty($this->input->post('service_id'))){
           $res=$this->services_model->get_service_doc_id($this->input->post('service_id'));
           if(!empty($res)){
            $service_id= $res->_id->{'$id'};
            $service_name=$res->service_name;
         }else{
            $service_id= false;
         }
       }
       $data= $this->api_model->get_first_appeal_count_by_service($service_id);
       if(!empty($data)){
        echo json_encode($data);
       }else{
        echo json_encode(array(
            "service_id"=>"",
            "service_name"=> $service_name,
            "total_appeal"=>0,
            "delivered_within_timeline"=>0,
            "rejected_within_timeline"=>0,
            "delivered_after_timeline"=>0,
            "rejected_after_timeline"=>0,
            "pending_within_timeline"=>0,
            "pending_after_timeline"=>0,
        ));
       }
       
    }

    public function first_appeal_count_by_disttrict(){
        
        $this->load->model("department_model");
        $this->load->model("appeal_application_model");
        $data = [];
          $data['departments']=$this->department_model->get_departments();
          $data['districts']=$this->appeal_application_model->get_districts();
      
        $this->load->view("includes/header", array("pageTitle" => "Appeal"));
        $this->load->view("appeal/first_appeal_by_district", $data);
        $this->load->view("includes/footer");
    }

    public function find_first_appeal_count_by_district(){
        $this->load->model('api_model');
         $this->load->model('services_model');
         $service_id=false;
         $service_name="No Services Selected";
         if(!empty($this->input->post('service_id'))){
             $res=$this->services_model->get_service_doc_id($this->input->post('service_id'));
             if(!empty($res)){
              $service_id= $res->_id->{'$id'};
              $service_name=$res->service_name;
           }else{
              $service_id= false;
           }
         }
         $district=$this->input->post('district');
         $data= $this->api_model->get_first_appeal_count_by_district($service_id,$district);
         if(!empty($data)){
          echo json_encode($data);
         }else{
          echo json_encode(array(
              "service_id"=>"",
              "service_name"=> $service_name,
              "total_appeal"=>0,
              "delivered_within_timeline"=>0,
              "rejected_within_timeline"=>0,
              "delivered_after_timeline"=>0,
              "rejected_after_timeline"=>0,
              "pending_within_timeline"=>0,
              "pending_after_timeline"=>0,
          ));
         }
         
      }
      public function second_appeal_count_by_disttrict(){
        
        $this->load->model("department_model");
        $this->load->model("appeal_application_model");
        $data = [];
          $data['departments']=$this->department_model->get_departments();
          $data['districts']=$this->appeal_application_model->get_districts();
      
        $this->load->view("includes/header", array("pageTitle" => "Appeal"));
        $this->load->view("appeal/second_appeal_by_district", $data);
        $this->load->view("includes/footer");
    }

    public function find_second_appeal_count_by_district(){
        $this->load->model('api_model');
         $this->load->model('services_model');
         $service_id=false;
         $service_name="No Services Selected";
         if(!empty($this->input->post('service_id'))){
             $res=$this->services_model->get_service_doc_id($this->input->post('service_id'));
             if(!empty($res)){
              $service_id= $res->_id->{'$id'};
              $service_name=$res->service_name;
           }else{
              $service_id= false;
           }
         }
         $district=$this->input->post('district');
         $data= $this->api_model->get_second_appeal_count_by_district($service_id,$district);
         if(!empty($data)){
          echo json_encode($data);
         }else{
          echo json_encode(array(
              "service_id"=>"",
              "service_name"=> $service_name,
              "total_appeal"=>0,
              "delivered_within_timeline"=>0,
              "rejected_within_timeline"=>0,
              "delivered_after_timeline"=>0,
              "rejected_after_timeline"=>0,
              "pending_within_timeline"=>0,
              "pending_after_timeline"=>0,
          ));
         }
         
      }


     public function second_appeal_count_by_service(){
        $this->load->model("department_model");
        $data = [];
          $data['departments']=$this->department_model->get_departments();
       
        $this->load->view("includes/header", array("pageTitle" => "Appeal"));
        $this->load->view("appeal/second_appeal_by_service", $data);
        $this->load->view("includes/footer");
    }
    public function find_second_appeal_count_by_service(){
      $this->load->model('api_model');
       $this->load->model('services_model');
       $service_id=false;
       $service_name="No Services Selected";
       if(!empty($this->input->post('service_id'))){
           $res=$this->services_model->get_service_doc_id($this->input->post('service_id'));
           if(!empty($res)){
            $service_id= $res->_id->{'$id'};
            $service_name=$res->service_name;
         }else{
            $service_id= false;
         }
       }
       $data= $this->api_model->get_second_appeal_count_by_service($service_id);
       if(!empty($data)){
        echo json_encode($data);
       }else{
        echo json_encode(array(
            "service_id"=>"",
            "service_name"=> $service_name,
            "total_appeal"=>0,
            "delivered_within_timeline"=>0,
            "rejected_within_timeline"=>0,
            "delivered_after_timeline"=>0,
            "rejected_after_timeline"=>0,
            "pending_within_timeline"=>0,
            "pending_after_timeline"=>0,
        ));
       }
       
    }

  
}
