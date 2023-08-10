<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Portals extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('portals_model');
        $this->load->library('form_validation');
        if(!in_array($this->session->userdata('role')->slug,['SA'])){
          redirect('iservices/admin/dashboard/access_denied');
      }
    }

    public function index()
    {

 $data['portals'] = $this->portals_model->get_all_portal_list();

//  pre($data);

        $this->load->view('includes/header');
        $this->load->view('portals/list',$data);
        $this->load->view('includes/footer');
    }
    public function detail($id){
      $data['detail']=$this->portals_model->get_portal_details($id);
      $this->load->view('includes/header');
      $this->load->view('portals/detail',$data);
      $this->load->view('includes/footer');
    }
    public function edit($id){
      $data['editData']=$this->portals_model->get_portal_details($id);
      // var_dump($data['editData']->_id->{'$id'});die;
      $this->load->view('includes/header');
      $this->load->view('portals/edit',$data);
      $this->load->view('includes/footer');
    }

    public function get_records()
    {

        $columns = array(
            "0" => "sl_no",
            "1" => "portal_name",
            "2" => "portal_no",
            "3" => "service_id",
            "4" => "url",
            "5" => "external_service_id",
            "6" => "service_name",
            "7" => "timeline_days",
            "8" => "action",
        );
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[(int) $this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->portals_model->application_total_rows();
        $totalFiltered = $totalData;
        if (empty($this->input->post("search")["value"])) {
            $records = $this->portals_model->application_all_rows($limit, $start, $order, $dir);
        } else {
            $search = (array)trim($this->input->post("search")["value"]);
            $records = $this->portals_model->search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->portals_model->portal_tot_search_rows($search);
            $totalFiltered = count((array) $totalFiltered);
            
        }
        $data = array();

        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {

                  $objId = $rows->{"_id"}->{'$id'};
                  $btnForward="";
                  $edit_btn = '<a data-id="' . $objId . '" href="'.base_url().'iservices/portals/edit/'.$objId.'" data-toggle="tooltip" data-placement="top" title="Edit Portal" class="btn btn-info btn-sm">Edit</a>';
                  $btns = '<a data-id="' . $objId . '" href="'.base_url().'iservices/portals/detail/'.$objId.'" data-toggle="tooltip" data-placement="top" title="View Portal" class="btn btn-primary btn-sm">View</a>';



                  $nestedData["sl_no"] = $sl;
                  $nestedData["portal_name"] =(isset($rows->portal_name)) ? $rows->portal_name : "";;
                  $nestedData["service_id"] =$rows->service_id;
                  $nestedData["external_service_id"] =property_exists($rows,"external_service_id")? $rows->external_service_id:"";
                  $nestedData["portal_no"] = $rows->portal_no;
                  $nestedData["service_name"] = $rows->service_name;
                  $nestedData["url"] = $rows->url;
                  $nestedData["timeline_days"] = $rows->timeline_days;
                  $nestedData["payment_required"] = isset($rows->payment_required)?$rows->payment_required:"";
                  $nestedData["status"] = isset($rows->status)?$rows->status:"";
                  

                  $nestedData["action"] = $btns." ".$edit_btn;
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

    public function create(){
      $this->load->view('includes/header');
      $this->load->view('portals/form');
      $this->load->view('includes/footer');
    }
    public function add_action(){
      $service_id=$this->input->post('service_id');
      if(!$this->portals_model->existServiceID($service_id)){
          if(($this->input->post('ep_payment_collect_on_rtps') === "1") && empty($this->input->post('ep_payment_account_code')) ){
            $this->session->set_flashdata(array("message"=>"Please provide EP payment account","status"=>"error"));
            redirect(base_url("iservices/portals"));
            return;
          }
           $input_data=$this->input->post();
           
           $status=$this->input->post('status');
           $payment_required=$this->input->post('payment_required');
          $input_data['status']= $status === "1" ? true : false;
          $input_data['payment_required']= $payment_required === "1" ? true : false;
          $this->portals_model->insert($input_data);
          $this->session->set_flashdata(array("message"=>"Records inserted successfully","status"=>"success"));

      }else {
        $this->session->set_flashdata(array("message"=>"Service id already exist","status"=>"error"));

      }

      redirect(base_url("iservices/portals"));
    }
    public function edit_action($id){
      $service_id=$this->input->post('service_id');

      $input_data=$this->input->post();
           
      $status=$this->input->post('status');
      $input_data['status']= $status === "1" ? true : false;
      $input_data['payment_required']= $this->input->post('payment_required') === "1" ? true : false;
      $this->portals_model->update($id,$input_data);
      // if(!$this->portals_model->existServiceID($service_id)){
      //     $this->portals_model->insert($this->input->post());
      //     $this->session->set_flashdata(array("message"=>"Records inserted successfully","status"=>"success"));
      //
      // }else {
      //   $this->session->set_flashdata(array("message"=>"Service id already exist","status"=>"error"));
      //
      // }
      $this->session->set_flashdata(array("message"=>"Records updated successfully","status"=>"success"));
      redirect(base_url("iservices/portals"));
    }
    public function _rules()
    {
        $this->form_validation->set_rules('role', ' ', 'trim|required');

        $this->form_validation->set_rules('roleId', 'roleId', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

};
