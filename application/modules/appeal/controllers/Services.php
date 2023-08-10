<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Services extends Rtps
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('services_model');
        $role = $this->session->userdata("role");
        if (!in_array($role->slug,['SA','ADMIN'])) {
            redirect(base_url("appeal/login/logout"));
        }
        $this->load->library('form_validation');
    }

    /**
     * index
     *
     * @return void
     */
    public function index(){
        $services = $this->services_model->all();
        $departments = $this->services_model->all_dept();

        // pre(new MongoDB\BSON\ObjectId($services->{'0'}->{'_id'}->{'$id'}));

        $data = [
            'services' => $services,
            'departments'   => $departments,
        ];

        // pre($data['services']);
        $this->load->view('includes/header');
        $this->load->view('services/index', $data);
        $this->load->view('includes/footer');
    }

    public function get_records()
    {
        $columns = array(
            "0"=>"sl_no",
            "1"=>"service_id", 
            "2"=>"service_name",
            "3"=>"department_id",
            "4"=>"timeline",
            );
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[(int) $this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->services_model->total_rows();
        $totalFiltered = $totalData;
        $filter=array();
        $dep=$this->input->post('dept_id');
        if(!empty($dep)){
            $filter=array('department_id'=>$dep);
        }
        if (empty($this->input->post("search")["value"])) {
            $records = $this->services_model->all_rows($limit, $start, $order, $dir,$filter);
          
        } else {
          $search = trim($this->input->post("search")["value"]);
          $records = $this->services_model->service_search_rows($limit, $start, $search, $order, $dir,$filter);
          $totalFiltered = $this->services_model->tot_search_rows(array("keyword"=>$search));
          $totalFiltered = count((array) $totalFiltered);
        }
        $data = array();

        if (!empty($records)) {
          $sl = 1;
          foreach ($records as $rows) {
            $objId = $rows->{"_id"}->{'$id'};
            // $btns = '<a  data_id="' . base_url("services/read/$objId") . '" href="#!" data-toggle="tooltip" data-placement="top" title="Edit" class="deleteService" ><span class="fa fa-eye" aria-hidden="true"></span></a> ';
            $btns = '<a data-id="' . $objId . '" href="#!" data-toggle="tooltip" data-placement="top" title="Edit" class="editService"><span class="fa fa-edit" aria-hidden="true"></span></a>';
            //$btns .= '<a data-id="' . $objId . '" href="#!" data-toggle="tooltip" data-placement="top" title="Delete" class="deleteService"><span class="fas fa-trash" aria-hidden="true"></span></a>';
            $nestedData["sl_no"] = $sl;
            $nestedData["service_id"] = (isset($rows->service_id))?$rows->service_id:"";;
            $nestedData["service_name"] = (isset($rows->service_name))?$rows->service_name:"";
            $nestedData["dept_name"] = $this->get_departmen((isset($rows->department_id))?$rows->department_id:"");
            $nestedData["timeline"] = (isset($rows->service_timeline))?$rows->service_timeline:"N/A";;
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
    public function get_service_info()
    {
        
        $doc_id=$this->input->post("service_id");
        $json_data=array(
            "status"=>true,
            "data"=>$this->services_model->get_by_doc_id($doc_id)
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

        $this->form_validation->set_rules('service_name', 'Service name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dept_id', ' Department Id', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('service_timeline', 'Service timeline', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('service_id', 'Service Id', 'trim|required|xss_clean|alpha_numeric');
        $this->form_validation->set_rules('dept_parent', 'Parent dept', 'trim|required|xss_clean|alpha');


        if ($this->form_validation->run() == false) {
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => false,
                'error_msg' => validation_errors()
            )));
        }else{
            if(!$this->custom_alpha($this->input->post('service_name'))){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'status' => false,
                    'error_msg' => "Service name should not contain special characters expcet '(,),/,-'"
                )));
            }

            $dept_id = $this->input->post("dept_id", TRUE);
            $service_name = $this->input->post("service_name", TRUE);
            $service_timeline = $this->input->post("service_timeline", TRUE);
            $service_id = $this->input->post("service_id", TRUE);
            $dept_parent = $this->input->post("dept_parent", TRUE);

            $data = [];
            if($dept_id == null || $service_name == null ||  $service_id == null || $service_timeline == null){
                $data['status'] = false;
            }else{
                $data = [];
                $data['status'] = false;
                $post_data = array(
                    "department_id" =>$dept_id,
                    "dept_parent" =>$dept_parent,
                    "service_timeline" =>$service_timeline,
                    "service_id" =>$service_id,
                    "service_name" => $service_name,
                    "created_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
                );
                $this->services_model->insert($post_data);
                $data['status'] = true;
            }

            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
        }


    }
    private function get_departmen($dept_id = NULL)
    {
        if ($dept_id != NULL) {
            $filter = array(
                "department_id" => "" . $dept_id . ""
            );
            $this->mongo_db->where($filter);
            $dept = $this->mongo_db->find_one("departments");
          
            if ( property_exists($dept,"department_name")) {
                return  $dept->department_name;
            } else {
                return FALSE;
            }
        }
    }

   
    private function custom_alpha($str) 
    {
        if ( !preg_match('/^[a-z 0-9 (),\/\-]+$/i',$str) )
        {
            return false;
        }else{
            return TRUE;
        }
    }
    //Update Service
    /**
     * update
     *
     * @return void
     */
    public function update(){
        $this->form_validation->set_rules('service_name', 'Service name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dept_id', ' Department Id', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('service_timeline', 'Service timeline', 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('service_id', 'Service Id', 'trim|required|xss_clean|alpha_numeric');
        $this->form_validation->set_rules('dept_parent', 'Parent dept Id', 'trim|required|xss_clean|alpha');


        if ($this->form_validation->run() == false) {
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => false,
                'error_msg' => validation_errors()
            )));
        }else{
        if(!$this->custom_alpha($this->input->post('service_name'))){
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => false,
                'error_msg' => "Service name should not contain special characters expcet '(,),/,-'"
            )));
        }
        $dept_id = $this->input->post("dept_id", TRUE);
        $service_name = $this->input->post("service_name", TRUE);
        $service_id = $this->input->post("service_id", TRUE);
        $service_obj_id = $this->input->post("service_obj_id", TRUE);
        $service_timeline = $this->input->post("service_timeline", TRUE);
        $dept_parent = $this->input->post("dept_parent", TRUE);
        
        $data = [];
        if($dept_id == null || $service_name == null || $service_id == null || $service_timeline == null){
            $data['status'] = false;
        }
        else{
            $this->services_model->update($service_obj_id, array(
                'department_id'  => $dept_id,
                'dept_parent'  => $dept_parent,
                'service_name'  => $service_name,
                'service_timeline'=>$service_timeline,
                'service_id'=>$service_id,
                "updated_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            )
        );

            $data['status'] = true;
        }

        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($data));
    }
    }

}