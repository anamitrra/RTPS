<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Locations extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('location_model');
        $this->load->model('department_model');
        $this->load->library('form_validation');
        $role = $this->session->userdata("role");
        if (!in_array($role->slug,['SA','ADMIN'])) {
            redirect(base_url("appeal/login/logout"));
        }
    }

    public function index(){

        $departments = $this->department_model->get_department_list();
        $departments = json_decode(json_encode($departments), true);

        // print_r($departments);
        $data['departments']= $departments;

        $this->load->view('includes/header');
        $this->load->view('locations/index',$data);
        $this->load->view('includes/footer');
    }

    //Get Locations
    public function get_records()
    {
        $columns = array(
            "0"=>"sl_no",
            "1"=>"location_id",
            "2"=>"location_name",
            "3"=>"department_id",
            "4"=>"action",
            );
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[(int) $this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->location_model->total_rows();
        $totalFiltered = $totalData;
        if (empty($this->input->post("search")["value"])) {
          $records = $this->location_model->all_rows($limit, $start, $order, $dir);
        } else {
          $search = trim($this->input->post("search")["value"]);
          $records = $this->location_model->locations_search_rows($limit, $start, $search, $order, $dir);
          $totalFiltered = $this->location_model->locations_tot_search_rows($search);
          $totalFiltered = count((array) $totalFiltered);
        }
        $data = array();
    
        if (!empty($records)) {
          $sl = 1;
          foreach ($records as $rows) {
            $objId = $rows->{"_id"}->{'$id'};
            $btns = '<a data-id="' . $objId . '" href="#!" data-toggle="tooltip" data-placement="top" title="Edit" class="editLocation"><span class="fa fa-edit" aria-hidden="true"></span></a>';

            //$btns .= '<a data-id="' . $objId . '" href="#!" data-toggle="tooltip" data-placement="top" title="Delete" class="deleteLocation text-danger"><i class="fas fa-trash-alt"></i></a>';

            $nestedData["sl_no"] = $sl;
            $nestedData["location_id"] = (isset($rows->location_id))?$rows->location_id:"";
            $nestedData["location_name"] = (isset($rows->location_name))?$rows->location_name:"";
            $nestedData["department_id"] = (isset($rows->department_id))?$rows->department_id:"";
            $nestedData["action"] = $btns;
            $data[] = $nestedData;
            $sl++;
          }
        }
        $json_data = array(
          "draw" => intval($this->input->post("draw")),
          "recordsTotal" => intval($totalData),
          "recordsFiltered" => intval($totalFiltered),
          "data" => $data,
        );

        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($json_data));
    }

    public function get_location_info()
    {
        $doc_id=$this->input->post("location_id");

        $json_data=array(
            "status"=>true,
            "data"=>$this->location_model->get_by_doc_id($doc_id)
        );
        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($json_data)); 
    }

    private function custom_alpha($str) 
    {
        if ( !preg_match('/^[a-z (),\/\-]+$/i',$str) )
        {
            return false;
        }else{
            return TRUE;
        }
    }
    //Insert Locations
    public function insert(){

        $this->form_validation->set_rules('location_name', 'Location name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('location_id', ' Location Id', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('department_id', 'Department name', 'trim|required|xss_clean');



        $exist_location_ids =  (array)$this->location_model->getLocationIds($this->input->post('location_id')); 
        // print_r($exist_location_ids);

       

        if ($this->form_validation->run() == false) {
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => false,
                'error_msg' => validation_errors()
            )));
        }else{

            if(!$this->custom_alpha($this->input->post('location_name'))){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error_msg' => "Location name should not contain special characters expcet '(,),/,-'"
                )));
            }

            if(count($exist_location_ids)>=1){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error_msg' => "Location ID already exist"
                )));
            }

        $location_name = trim($this->input->post("location_name", TRUE));
        $location_id   = trim($this->input->post("location_id", TRUE));
        $department_id   = trim($this->input->post("department_id", TRUE));

        $data = [];
        if(in_array(null,array($location_name,$location_id,$department_id))){
            $data['status'] = false;
        }else{
          


            $post_data = array(
                "location_id" => $location_id,
                "location_name" => $location_name,
                "department_id" => $department_id,
                "created_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
                "updated_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            );
            // print_r($post_data);
            $this->location_model->insert($post_data);
            $data['status'] = true;
        }

        // pre($data);
        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($data));
    }
    }

    //Delete Location
    public function delete(){
        $location_id = $this->input->post("location_id", TRUE);
        $location_id = new MongoDB\BSON\ObjectId($location_id);
        $data = [];   
        if($location_id == null || $location_id == ''){
            $data['status']  == false;
        }
        else{
            $this->location_model->delete_location($location_id);
            $data['status'] = true;
        }    


        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($data));
    }

    //Upadate Service
    public function update(){
        $this->form_validation->set_rules('location_name', 'Location name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('location_id', ' Location Id', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('department_id', 'Department name', 'trim|required|xss_clean');

        // $exist_location_ids =  (array)$this->location_model->getLocationIds($this->input->post('location_id')); 
        // print_r($exist_location_ids);
        // print($this->input->post('location_id'));
        // print(count($exist_location_ids));


        if ($this->form_validation->run() == false) {
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => false,
                'error_msg' => validation_errors()
            )));
        }else{
            if(!$this->custom_alpha($this->input->post('location_name'))){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error_msg' => "Location name should not contain special characters expcet '(,),/,-'"
                )));
            }

            // if(count($exist_location_ids)>=1){
            //     return $this->output
            //     ->set_content_type('application/json')
            //     ->set_status_header(200)
            //     ->set_output(json_encode(array(
            //         'status' => false,
            //         'error_msg' => "Location ID already exist"
            //     )));
            // }
            
            $location_obj_id = trim($this->input->post("location_obj_id", TRUE));
        $location_name   = trim($this->input->post("location_name", TRUE));
        $location_id     = trim($this->input->post("location_id", TRUE));
        $department_id   = trim($this->input->post("department_id", TRUE));

        
        $data = [];
        if(in_array(null,array($location_name,$location_id,$department_id, $location_obj_id))){
            $data['status'] = false;
        }
        else
        {
            $this->location_model->update($location_obj_id, [
                'location_id'    => $location_id,
                'location_name'  => $location_name,
                'department_id'  => $department_id,
                "updated_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            ]);

            $data['status'] = true;
        }

        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($data));
        }

    }
    
}