<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Offices extends Rtps {
 /**
  * __construct
  *
  * @return void
  */
  function __construct() {
    parent::__construct();
   
    $this->load->model('offices_model');
    $this->load->model('department_model');
    $this->load->library('form_validation');
    
    $roleList = getRoles();
    $role = $this->session->userdata("role");
    if ($role->role_name != "System Administrator") {
      redirect(base_url("appeal/login/logout"));
    }
  }
  /**
   * get_records
   *
   * @return void
   */
  public function get_records() {
    $this->load->model('offices_model'); 
    // $this->load->library("datatables");
    $columns = array("officeId", "office_name");
    $limit = $this->input->post("length");
    $start = $this->input->post("start");
    $order = $columns[(int) $this->input->post("order")[0]["column"]];
    $dir = $this->input->post("order")[0]["dir"];
    $totalData = $this->offices_model->total_rows();
    $totalFiltered = $totalData;
   
    if (empty($this->input->post("search")["value"])) {
      $records = $this->offices_model->all_rows($limit, $start, $order, $dir);
    } else {
      $search = trim($this->input->post("search")["value"]);
      $records = $this->offices_model->users_search_rows($limit, $start, $search, $order, $dir);
      $totalFiltered = $this->offices_model->users_tot_search_rows($search);
      $totalFiltered = count((array) $totalFiltered);
    }
    $data = array();

    if (!empty($records)) {
      $sl = 1;
      foreach ($records as $rows) {
        $objId = $rows->{"_id"}->{'$id'};
       
        $btns = '<a href="' . base_url("mis/offices/update/$objId") . '" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>';
        $btns .= '<a href="' . base_url("mis/offices/delete/$objId") . '" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>';
        $dept_obj=$this->department_model->get_services($rows->department_id);
        $dis_id=property_exists($rows,'district_id') ? $rows->district_id : "";
     
        $district_obj=$this->offices_model->get_district_name($dis_id);
    //    var_dump($district_obj);
        $nestedData["officeId"] = $sl;
        $nestedData["office_name"] = $rows->office_name;
        $nestedData["district"] = !empty( $district_obj) ?  $district_obj->distname : "";
        $nestedData["department"] = ($dept_obj)?$dept_obj->department_name:"";
        $nestedData["action"] = $btns;
        $data[] = $nestedData;
        $sl++;
      }
    }

    // pre($data);
    $json_data = array(
      "draw" => intval($this->input->post("draw")),
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data" => $data,
    );
    echo json_encode($json_data);
  }

  /**
   * index
   *
   * @return void
   */
  public function index() {
    $this->load->view('includes/header', array("pageTitle" => "Mis | Offices"));
    $this->load->view('offices/list');
    $this->load->view('includes/footer');
  }

  

  /**
   * create
   *
   * @return void
   */
  public function create() {
    
    
    $data = array(
      'button' => 'Create',
      'action' => base_url('mis/offices/create_action'),
      'officeId' => set_value('officeId'),
      'office_name' => set_value('office_name'),
      'departments' => $this->department_model->get_departments(),
      'districts' => $this->offices_model->get_districts(),
    );
    $this->load->view('includes/header', array('pageTitle' => 'Mis | Create Office'));
    $this->load->view('offices/form', $data);
    $this->load->view('includes/footer');
  }

  /**
   * create_action
   *
   * @return void
   */
  public function create_action() {
      
   
    $this->form_validation->set_rules('office_name', 'Office Name', 'required');//|callback_username_check
    $this->form_validation->set_rules('department', 'Department', 'required');

    if ($this->form_validation->run() == FALSE) {
       
      $this->create();
    } else {
       
      $data = array(
        
        'office_name' => $this->input->post('office_name', TRUE),
        'department_id' => $this->input->post('department', TRUE),
        'district_id'=> $this->input->post('district_id', TRUE),
        'createdDtm' => date("Y-m-d h:i:s"),
        'updatedDtm' => date("Y-m-d h:i:s"),
        
      );
      $this->offices_model->insert($data);
      $this->session->set_flashdata('message', 'Create Record Success');
      redirect(base_url('mis/offices'));
    }
  }

  /**
   * update
   *
   * @param mixed $id
   * @return void
   */
  public function update($id) {
    $row = $this->offices_model->get_by_doc_id($id);
    
    
    if ($row) {
      $data = array(
        'breadcrumb_item' => 'Update Office',
        'button' => 'Update',
        'action' => base_url('mis/offices/update_action'),
        'officeId' => $row->{"_id"}->{'$id'},
        'office_name' => $row->office_name,
        'departments' => $this->department_model->get_departments(),
        'department_id' => set_value('department', $row->department_id),
        'districts' => $this->offices_model->get_districts(),
        'district_id'=> set_value('district_id', $row->district_id??""),
      );
      //pre($data);
      $this->load->view('includes/header', array('pageTitle' => 'Mis | Update office'));
      $this->load->view('offices/form', $data);
      $this->load->view("includes/footer");
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('offices'));
    }
  }

  /**
   * update_action
   *
   * @return void
   */
  public function update_action() {
    $this->form_validation->set_rules('office_name', 'Office Name', 'required');//|callback_username_check
    $this->form_validation->set_rules('department', 'Department', 'required');
    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('officeId', TRUE));
    } else {
        $data = array(
           
            'office_name' => $this->input->post('office_name', TRUE),
            'updatedDtm' => date("Y-m-d h:i:s"),
            'department_id' => $this->input->post('department', TRUE),
            'district_id'=> (int)$this->input->post('district_id', TRUE),
        );
    
      $this->offices_model->update($this->input->post('officeId', TRUE), $data);
      $this->session->set_flashdata('message', 'Update Record Success');
      redirect(base_url('mis/offices'));
    }
  }
  /**
   * delete
   *
   * @param mixed $id
   * @return void
   */
  public function delete($id) {
    $row = $this->offices_model->get_by_doc_id($id);
    if ($row) {
      $this->offices_model->delete($id);
      $this->session->set_flashdata('message', 'Delete Record Success');
      redirect(base_url('mis/offices'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('mis/offices'));
    }
  }

  public function get_record_office($depart) {
   // die('here');
    $this->mongo_db->switch_db("mis");
    $this->load->model('offices_model'); 
   // $this->offices_model->set_collection("offices");
    // $this->load->library("datatables");
    $columns = array(
      "0"=>"office_name",
      );
    $dpt = array(
      "department_id"=>$depart
  );  
    $limit = $this->input->post("length");
    $start = $this->input->post("start");
    $order = $columns[(int) $this->input->post("order")[0]["column"]];
    $dir = $this->input->post("order")[0]["dir"];
   
    $totalData = $this->offices_model->tot_search_rows($dpt);
    $totalFiltered =(array) $totalData;
 
    if (empty($this->input->post("search")["value"])) {
      $records = $this->offices_model->search_rows($limit, $start,$dpt, $order, $dir);
      
    } else {
      $search = trim($this->input->post("search")["value"]);
      $records = $this->offices_model->search_rows($limit, $start, $search, $order, $dir);
          $totalFiltered = $this->offices_model->tot_search_rows($search);
      $totalFiltered = count((array) $totalFiltered);
    }
    $data = array();

    if (!empty($records)) {
      $sl = 1;
      foreach ($records as $rows) {
        $objId = $rows->{"_id"}->{'$id'};
        $nestedData["sl_no"] = $sl;
        $nestedData["office_name"] = $rows->office_name;
        $data[] = $nestedData;
        $sl++;
      }
    }
//pre($data);

    $json_data = array(
      "draw" => intval($this->input->post("draw")),
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data" => $data,
    );
    echo json_encode($json_data);
  }
 
 




}
/* End of file Users.php */
/* Location: ./application/controllers/Users.php */
