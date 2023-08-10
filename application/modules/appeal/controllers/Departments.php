<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Departments extends Rtps
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('department_model');
        $this->load->library('form_validation');
        $role = $this->session->userdata("role");
        if (!in_array($role->slug,['SA','ADMIN'])) {
            redirect(base_url("appeal/login/logout"));
        }
    }

    /**
     * index
     *
     * @return void
     */
    /**
     * index
     *
     * @return void
     */
    public function index(){
        // $departments = $this->department_model->get_departments();
        // $data = [
        //     'departments' => $departments,
        // ];

        // pre($data['services']);
        $this->load->view('includes/header');
        $this->load->view('departments/index');
        $this->load->view('includes/footer');
    }

    //Get departments
    public function get_records()
    {
        $columns = array(
            "0"=>"sl_no",
            "1"=>"department_id",
            "2"=>"department_name",
            "3"=>"action",
            );
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[(int) $this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->department_model->total_rows();
        $totalFiltered = $totalData;
        if (empty($this->input->post("search")["value"])) {
          $records = $this->department_model->all_rows($limit, $start, $order, $dir);
        } else {
          $search = trim($this->input->post("search")["value"]);
          $records = $this->department_model->department_search_rows($limit, $start, $search, $order, $dir);
          $totalFiltered = $this->department_model->department_tot_search_rows($search);
          $totalFiltered = count((array) $totalFiltered);
        }
        $data = array();
    
        if (!empty($records)) {
          $sl = 1;
          foreach ($records as $rows) {
            $objId = $rows->{"_id"}->{'$id'};
            $btns = '<a data-id="' . $objId . '" href="#!" data-toggle="tooltip" data-placement="top" title="Edit" class="editDepartment"><span class="fa fa-edit" aria-hidden="true"></span></a>';
            $nestedData["sl_no"] = $sl;
            $nestedData["department_id"] = (isset($rows->department_id))?$rows->department_id:"";
            $nestedData["base_department_id"] = (isset($rows->base_department_id))?$rows->base_department_id :"";
            $nestedData["department_name"] = (isset($rows->department_name))?$rows->department_name:"";
            $nestedData["parent"] = property_exists($rows,"parent") ? $rows->parent : "";
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


    public function get_department_info()
    {
        $doc_id=$this->input->post("depertment_id");

        $json_data=array(
            "status"=>true,
            "data"=>$this->department_model->get_by_doc_id($doc_id)
        );
        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($json_data)); 
    }



    /**
     * insert
     *
     * @return void
     */
    public function insert(){
        $this->form_validation->set_rules('department_name', 'Department name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('department_id', ' Department Id', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('base_department_id', ' Base Department Id', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('parent', 'Parent', 'trim|required|xss_clean|alpha');

        $department_name = $this->input->post("department_name", TRUE);
        $department_id = $this->input->post("department_id", TRUE);
        $parent = $this->input->post("parent", TRUE);
        if ($this->form_validation->run() == false) {
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => false,
                'error_msg' => validation_errors()
            )));
        }else{
            if(!$this->custom_alpha($this->input->post('department_name'))){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error_msg' => "Department name should not contain special characters expcet '(,),/,-'"
                )));
            }
            if($this->checkDeptIdExist($this->input->post('department_id'))){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error_msg' => "Department id already exits"
                )));
            }
            $data = [];
            if($department_name == null || $department_id == null){
                $data['status'] = false;
            }else{
                $post_data = array(
                    "department_id" => $department_id,
                    "base_department_id" => $this->input->post('base_department_id'),
                    "department_name" => $department_name,
                    "parent" => $parent ? $parent:"GOA",
                    "created_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
                    "updated_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
                );
                $this->department_model->insert($post_data);
                $data['status'] = true;
            }

            // pre($data);
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
        }



    }
    private function checkDeptIdExist($id){
        $this->mongo_db->where('department_id',$id);
        $dep=$this->mongo_db->get('departments');
       // pre($dep);
        if(count((array)$dep) > 0){
            return true;
        }else{
            false;
        }
    }

    //Delete Department
    /**
     * delete
     *
     * @return void
     */
    public function delete(){
        $department_id = trim($this->input->post("department_id", TRUE));
        $department_id = new MongoDB\BSON\ObjectId($department_id);
        $data = [];   
        if($department_id == null || $department_id == ''){
            $data['status']  == false;
        }
        else{
            $this->department_model->delete_department($department_id);
            $data['status'] = true;
        }    


        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($data));
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

    //Upadate Department
    /**
     * update
     *
     * @return void
     */
    public function update(){

        $this->form_validation->set_rules('department_name', 'Department name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('department_id', ' Department Id', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('base_department_id', ' Base Department Id', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('parent', ' Parent ', 'trim|required|xss_clean|alpha');


        if ($this->form_validation->run() == false) {
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => false,
                'error_msg' => validation_errors()
            )));
        }else{
            if(!$this->custom_alpha($this->input->post('department_name'))){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error_msg' => "Department name should not contain special characters expcet '(,),/,-'"
                )));
            }
            $department_name = $this->input->post("department_name", TRUE);
            $department_id = $this->input->post("department_id", TRUE);
            $base_department_id = $this->input->post("base_department_id", TRUE);
            $dept_id = $this->input->post("dept_id", TRUE);
            $parent = $this->input->post("parent", TRUE);

            $data = [];
            if($department_name == null || $department_id == null || $dept_id == null || $base_department_id == null){
                $data['status'] = false;
            }
            else
            {
                $dept_id = new MongoDB\BSON\ObjectId($dept_id);
              //  pre( $dept_id );
                // $department_data = $this->department_model->get_department($dept_id);
                $this->department_model->update($dept_id, [
                    // 'department_id'  => $department_id,
                    'base_department_id'  => $base_department_id ,
                    'department_name'  => $department_name,
                    "parent" => $parent ? $parent:"GOA",
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

    public function get_department_by_parent(){
        $parent;
        if($this->input->post('edit_parent')){
            $parent= $this->input->post('edit_parent');
        }
        else{
            $parent= $this->input->post('parent');
        }
        
        if( $parent){
            $this->mongo_db->where(array('parent'=>$parent));
            $dept = $this->mongo_db->get("departments");
            $json_data=array(
                "status"=>$dept ? true : false,
                "data"=>  $dept ? $dept : "No Records Found" 
            );
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($json_data));
        }else{
            $json_data=array(
                "status"=>false,
                "data"=>"Something went wrong! Please try again",
            );
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($json_data));
            
        }
    }
    
}