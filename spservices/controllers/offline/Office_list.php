
<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime; 

class Office_list extends Rtps
{
  //put your code here
  public function __construct()
  {
    parent::__construct();
    $this->load->model("offline/office_list_model");
    if ($this->session->userdata()) {
      if ($this->session->userdata('isAdmin') === TRUE) {
      } else {
        $this->session->sess_destroy();
        redirect('spservices/mcc/user-login');
      }
    } else {
      redirect('spservices/mcc/user-login');
    }
  }
 
  public function form($obj_id=null)
  {
    if(!empty($obj_id)){
        $row=$this->office_list_model->get_row(array("_id"=>new ObjectId($obj_id)));
        $data['action_btn']="Update";
    }else{
        $row=array();
        $data['action_btn']="Add";
    }
    $data['dbrow']=$row;
    $this->load->view("includes/office_includes/header", array("pageTitle" => "Action Form"));
    $this->load->view("offline/office_form",$data);
    $this->load->view("includes/office_includes/footer");
  }
  public function generate_id(){
    $number = '';
    for ($i = 0; $i < 3; $i++){
        $number .= rand(0,3);
    }
    return $number;
  }
 
  public function action(){
    //   pre($this->session->userdata());
    $this->form_validation->set_rules("office_name", "office Name", "required|max_length[255]");
   
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    if ($this->form_validation->run() == FALSE) {
        $this->form();
        return;
    }
        
    $office_id=$this->generate_id();
    A1:
    if($this->office_list_model->is_exist_office_id($office_id)){
      $office_id=$this->generate_id();
      goto A1;
    }
    $obj_id=$this->input->post('obj_id');
    if(!empty($obj_id)){
        $data = array(
            "office_name"=>$this->input->post("office_name"),
            "office_timeline"=>$this->input->post("timeline")
        
        );
   
        $res=$this->office_list_model->update_row(array("_id"=>new ObjectId($obj_id)) , $data);
       
        $message="office updated successfully";
    }else{
        $data = array(
            "office_id"=>$office_id,
            "office_name"=>$this->input->post("office_name"),
            "is_offline"=>true,
            "createdDtm"=> new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000))
        
        );
   
        $res=$this->office_list_model->insert($data);
        $message="office added successfully";
    }
  
    if($res){
        $this->session->set_flashdata('message', $message );
        redirect(base_url("spservices/offline/office_list"));
        return;
       
    }
    redirect(base_url("spservices/offline/acknowledgement/form"));
  }
  
  public function index(){
    $this->load->view('includes/office_includes/header');
    // $this->load->view('admin/transactions',$data);
    $this->load->view('offline/offices');
    $this->load->view('includes/office_includes/footer');
  }
  public function get_records()
  {
      $this->load->model('applications_model');
    
      $apply_by = $this->session->userdata('userId')->{'$id'};
   
   
      $columns = array(
          '_id'
      );
      $limit = $this->input->post("length");
      $start = $this->input->post("start");
      $order = $columns[$this->input->post("order")[0]["column"]];
      $dir = $this->input->post("order")[0]["dir"];
      $totalData=0;
      $totalData = $this->office_list_model->total_app_rows();
      $totalFiltered = $totalData;
      if (empty($this->input->post("search")["value"])) {
          $records = $this->office_list_model->applications_filter($limit, $start, $columns, $dir,);
          
      } else {
          $search = trim($this->input->post("search")["value"]);
          $records = $this->office_list_model->application_search_rows($limit, $start, $search, $columns, $dir );
          // $totalFiltered = $this->official_details_model->official_details_tot_search_rows($search);
      }
      
      // pre($records );
      $data = array();
      if (!empty($records)) {
          $sl = 1;
          foreach ($records as $rows) {
              $rows=(array ) $rows;
              $nestedData["sl_no"] = $sl;
              $obj_id=$rows['_id']->{'$id'};
              $nestedData["office_name"] =  $rows['office_name'];
              $nestedData["office_id"] =  $rows['office_id'];
              $btns = '<a href="' . base_url("spservices/offline/office_list/form/". $obj_id) .'" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-sm btn-primary mb-1" >Edit</a> ';
           
             
            
             $nestedData['action']=$btns;
            
              //$nestedData["action"] = $btns;
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
      echo json_encode($json_data);
  }



}
