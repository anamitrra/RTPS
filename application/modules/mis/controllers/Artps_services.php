<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Artps_services extends Rtps
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('artps_services_model');
    }

    /**
     * index
     *
     * @return void
     */
    public function index(){
        
        $this->load->view('includes/header',array('pageTitle'=>'Mis | Notified Services'));
        $this->load->view('services/artps_services');
        $this->load->view('includes/footer');
    }

    public function get_records()
    {
        $columns = array(
            "0"=>"sl_no",
            "1"=>"rtps_service",
            "2"=>"department",
            "3"=>"autonomous_council"
            );
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[(int) $this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->artps_services_model->total_rows();
        $totalFiltered = $totalData;
        if (empty($this->input->post("search")["value"])) {
          $records = $this->artps_services_model->all_rows($limit, $start, $order, $dir);
        } else {
          $search = trim($this->input->post("search")["value"]);
      
          $records = $this->artps_services_model->service_search_rows($limit, $start, $search, $order, $dir);
          $totalFiltered = $this->artps_services_model->service_tot_search_rows($search);
          $totalFiltered = count((array) $totalFiltered);
        }
        $data = array();

        if (!empty($records)) {
          $sl = 1;
          foreach ($records as $rows) {
            $objId = $rows->{"_id"}->{'$id'};
           
            $btns = "<a data-id='".json_encode($rows)."' href='#!' data-toggle='tooltip' data-placement='top' title='Edit' class='editService'>Details <span class='fa fa-eye' aria-hidden='true'></span></a>";
            
            $nestedData["sl_no"] = $sl;
            $nestedData["rtps_service"] = (isset($rows->rtps_service))?$rows->rtps_service:"";
            $nestedData["department"] = (isset($rows->department))?$rows->department:"";;
            $nestedData["autonomous_council"] = (isset($rows->autonomous_council))?$rows->autonomous_council:"";;
            $nestedData["stipulated_timeline"] = (isset($rows->stipulated_timeline))?$rows->stipulated_timeline:"N/A";;
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
            "data"=>$this->artps_services_model->get_by_doc_id($doc_id)
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
            $this->artps_services_model->insert($post_data);
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
            $this->artps_services_model->update($service_obj_id, array(
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