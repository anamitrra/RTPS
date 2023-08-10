<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Services extends Rtps
{
    /**
     * 
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // $this->mongo_db->switch_db("appeal");
        $this->load->model('services_model');
    }

    /**
     * index
     *
     * @return void
     */
    public function index(){
        $services = $this->services_model->all();
        $departments = $this->services_model->all_dept();
      //  $districts = $this->services_model->all_dist();
       // pre($districts);

        // pre(new MongoDB\BSON\ObjectId($services->{'0'}->{'_id'}->{'$id'}));

        $data = [
            'services' => $services,
            'departments'   => $departments,            
        ];

        // pre($data);
        $this->load->view('includes/header',array('pageTitle'=>'Mis | Services'));
        $this->load->view('services/index', $data);
        $this->load->view('includes/footer');
    }

    public function department_service(){
        $services = $this->services_model->all();
        $departments = $this->services_model->all_dept();
      //  $districts = $this->services_model->all_dist();
       // pre($districts);

        // pre(new MongoDB\BSON\ObjectId($services->{'0'}->{'_id'}->{'$id'}));

        $data = [
            'services' => $services,
            'departments'   => $departments,            
        ];

        // pre($data);
        $this->load->view('includes/header',array('pageTitle'=>'Mis | Services'));
        $this->load->view('services/department_services', $data);
        $this->load->view('includes/footer');
    }
    public function office_service(){
        
       // $services = $this->services_model->all();
        $departments = $this->services_model->all_dept();
      //  $districts = $this->services_model->all_dist();
       // pre($districts);

        // pre(new MongoDB\BSON\ObjectId($services->{'0'}->{'_id'}->{'$id'}));

        $data = [
            'departments'   => $departments,            
        ];

        // pre($data);
        $this->load->view('includes/header',array('pageTitle'=>'Mis | Services'));
        $this->load->view('services/office_services', $data);
        $this->load->view('includes/footer');
    }
    public function get_records()
    {
        $columns = array(
           // "0"=>"sl_no",
            "0"=>"service_id", 
            "1"=>"service_name",
            "2"=>"department_id",
            "3"=>"timeline",
            );
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[(int) $this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->services_model->total_rows();
        $totalFiltered = $totalData;
        if (empty($this->input->post("search")["value"])) {
          $records = $this->services_model->all_rows($limit, $start, $order, $dir);
        } else {
          $search = trim($this->input->post("search")["value"]);
          $records = $this->services_model->search_rows($limit, $start, $search, $order, $dir);
          $totalFiltered = $this->services_model->tot_search_rows($search);
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
            $nestedData["dept_name"] = (isset($rows->department_id))?$rows->department_id:"";;
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
    public function get_record_dapt($depart)
    {
        $columns = array(
            "0"=>"sl_no",
            "1"=>"service_id", 
            "2"=>"service_name",
            "3"=>"department_id",
            "4"=>"timeline",
            );
        $dpt = array(
            "department_id"=>$depart,
        );    
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[(int) $this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->services_model->tot_search_rows($dpt);
        $totalFiltered = $totalData;
        if (empty($this->input->post("search")["value"])) {
          //  die('here');
          $records = $this->services_model->search_rows($limit, $start,$dpt, $order, $dir);
        } else {
           
          $search = trim($this->input->post("search")["value"]);
          $records = $this->services_model->search_rows($limit, $start, $search, $order, $dir);
          $totalFiltered = $this->services_model->tot_search_rows($search);
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
            $nestedData["dept_name"] = (isset($rows->department_id))?$rows->department_id:"";;
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
    public function get_record_office($depart)
    {
        $columns = array(
            "0"=>"sl_no",
            "1"=>"office_id", 
            "2"=>"office_name",
            );
        $dpt = array(
            "department_id"=>$depart,
        );    
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[(int) $this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->services_model->tot_search_rows($dpt);
        $totalFiltered = $totalData;
        if (empty($this->input->post("search")["value"])) {
          //  die('here');
          $records = $this->services_model->search_rows($limit, $start,$dpt, $order, $dir);
        } else {
           
          $search = trim($this->input->post("search")["value"]);
          $records = $this->services_model->search_rows($limit, $start, $search, $order, $dir);
          $totalFiltered = $this->services_model->tot_search_rows($search);
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
            $nestedData["dept_name"] = (isset($rows->department_id))?$rows->department_id:"";;
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
        $dept_id = $this->input->post("dept_id", TRUE);
        $service_name = $this->input->post("service_name", TRUE);
        $service_timeline = $this->input->post("service_timeline", TRUE);
        $service_id = $this->input->post("service_id", TRUE);

        $data = [];
        if($dept_id == null || $service_name == null ||  $service_id == null || $service_timeline == null){
            $data['status'] = false;
        }else{
            $data = [];
            $data['status'] = false;
            $post_data = array(
                "department_id" =>$dept_id,
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
    //Update Service
    /**
     * update
     *
     * @return void
     */
    public function update(){
        $dept_id = $this->input->post("dept_id", TRUE);
        $service_name = $this->input->post("service_name", TRUE);
        $service_id = $this->input->post("service_id", TRUE);
        $service_obj_id = $this->input->post("service_obj_id", TRUE);
        $service_timeline = $this->input->post("service_timeline", TRUE);
        
        $data = [];
        if($dept_id == null || $service_name == null || $service_id == null || $service_timeline == null){
            $data['status'] = false;
        }
        else{
            $this->services_model->update($service_obj_id, array(
                'department_id'  => $dept_id,
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