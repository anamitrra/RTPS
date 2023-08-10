<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Users extends Rtps {
 /**
  * __construct
  *
  * @return void
  */
  function __construct() {
    parent::__construct();
    $this->load->model('users_model');
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
    
    // $this->load->library("datatables");
    $columns = array("userId", "name", "mobile", "email");
    $limit = $this->input->post("length");
    $start = $this->input->post("start");
    $order = $columns[(int) $this->input->post("order")[0]["column"]];
    $dir = $this->input->post("order")[0]["dir"];
    $totalData = $this->users_model->total_rows();
    $totalFiltered = $totalData;
    if (empty($this->input->post("search")["value"])) {
      $records = $this->users_model->all_rows($limit, $start, $order, $dir);
    } else {
      $search = trim($this->input->post("search")["value"]);
      $records = $this->users_model->users_search_rows($limit, $start, $search, $order, $dir);
      $totalFiltered = $this->users_model->users_tot_search_rows($search);
      $totalFiltered = count((array) $totalFiltered);
    }
    $data = array();

    if (!empty($records)) {
      $sl = 1;
      foreach ($records as $rows) {
        $objId = $rows->{"_id"}->{'$id'};
        $btns = '<a href="' . base_url("appeal/users/read/$objId") . '" data-toggle="tooltip" data-placement="top" title="Edit" class="" ><span class="fa fa-eye" aria-hidden="true"></span></a> ';
        $btns .= '<a href="' . base_url("appeal/users/update/$objId") . '" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>';
        $btns .= '<a href="' . base_url("appeal/users/delete/$objId") . '" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>';
        $dept_obj=$this->department_model->get_services($rows->department[0]);
        $nestedData["userId"] = $sl;
        $nestedData["name"] = $rows->name;
        $nestedData["department"] = ($dept_obj)?$dept_obj->department_name:"";
        $nestedData["mobile"] = $rows->mobile;
        $nestedData["email"] = $rows->email;
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
    $this->load->view('includes/header', array("pageTitle" => "Dashboard | Users"));
    $this->load->view('users/users_list');
    $this->load->view('includes/footer');
  }

  /**
   * read
   *
   * @param mixed $id
   * @return void
   */
  public function read($id) {
    $this->load->model('login_model');
    $this->load->model('roles_model');
    $result = $this->users_model->get_by_doc_id($id);
    $row = $result;
    if ($row) {
      $data = array(
        'email' => $row->email,
        'name' => $row->name,
        'designation' => $row->designation,
        'office_name' => $row->office_name,
        'office_address' => $row->office_address,
        'mobile' => $row->mobile,
        'role' => $this->roles_model->get_role_info($row->roleId),
        'department' => $this->login_model->getDepartment($row->department[0]),
        'id' => $id
      );
      $this->load->view('includes/header', array('pageTitle' => 'Dashboard | Users'));
      $this->load->view('users/users_read', $data);
      $this->load->view('includes/footer');
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(site_url('users'));
    }
  }

  /**
   * create
   *
   * @return void
   */
  public function create() {
    
    $this->load->model("roles_model");
    $data = array(
      'button' => 'Create',
      'action' => base_url('appeal/users/create_action'),
      'email' => set_value('email'),
      'password' => set_value('password'),
      'name' => set_value('name'),
      'designation' => set_value('designation'),
      'office_name' => set_value('office_name'),
      'office_address' => set_value('office_address'),
      'mobile' => set_value('mobile'),
      'userId' => set_value('userId'),
      'roleId' => set_value('roleId'),
      'departments' => $this->department_model->get_departments(),
      'roles' => $this->roles_model->get_all([]),
      'roles_array' => [],
    );
    $this->load->view('includes/header', array('pageTitle' => 'Dashboard | Create User'));
    $this->load->view('users/users_form', $data);
    $this->load->view('includes/footer');
  }

  /**
   * create_action
   *
   * @return void
   */
  public function create_action() {
    $this->_rules();
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');//|callback_username_check
    $this->form_validation->set_rules('roleId', 'Role', 'required');

    if ($this->form_validation->run() == FALSE) {
      $this->create();
    } else {

      $email = $this->input->post('email', TRUE);

      $data = array(
        'email' => $email,
        'password' => getHashedPassword($this->input->post('password', TRUE)),
        'name' => $this->input->post('name', TRUE),
        'designation' => $this->input->post('designation', TRUE),
        'office_name' => $this->input->post('office_name', TRUE),
        'office_address' => $this->input->post('office_address', TRUE),
        'mobile' => $this->input->post('mobile', TRUE),
        'roleId' => $this->input->post('roleId', TRUE),
        'isDeleted' => $this->input->post('roleId', TRUE),
        'createdBy' => $this->input->post('roleId', TRUE),
        'createdDtm' => date("Y-m-d h:i:s"),
        'updatedBy' => $this->input->post('roleId', TRUE),
        'updatedDtm' => date("Y-m-d h:i:s"),
        'department' => array($this->input->post('department', TRUE)),
//        'roles' => $this->input->post('roles', TRUE), // for multi role
      );
      $this->users_model->insert($data);
      $this->session->set_flashdata('message', 'Create Record Success');
      redirect(base_url('appeal/users'));
    }
  }

  /**
   * update
   *
   * @param mixed $id
   * @return void
   */
  public function update($id) {
    $row = $this->users_model->get_by_doc_id($id);
    
    $this->load->model("roles_model");
    $this->load->helper('form');
    $this->load->helper('url');
    if ($row) {
      $data = array(
        'breadcrumb_item' => 'Update User',
        'button' => 'Update',
        'action' => base_url('dashboard/users/update_action'),
        'userId' => $row->{"_id"}->{'$id'},
        'email' => $row->email,
        'password' => '123456789',
        'name' => $row->name,
        'designation' => $row->designation,
        'office_name' => $row->office_name,
        'office_address' => $row->office_address,
        'mobile' => $row->mobile,
        'roles' => $this->roles_model->get_all([]),
        //'roles_array' => $row->roles,
        'roleId' => $row->roleId,
        'departments' => $this->department_model->get_departments(),
        'department_id' => set_value('department', $row->department[0]),
      );
      //pre($data);
      $this->load->view('includes/header', array('pageTitle' => 'Dashboard | Update User'));
      $this->load->view('users/users_form', $data);
      $this->load->view("includes/footer");
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(site_url('users'));
    }
  }

  /**
   * update_action
   *
   * @return void
   */
  public function update_action() {
    $this->_rules();
    //$this->form_validation->set_rules('roles[]', 'Role', 'required');
    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('userId', TRUE));
    } else {
        $data = array(
            'email' => $this->input->post('email', TRUE),
            'name' => $this->input->post('name', TRUE),
            'designation' => $this->input->post('designation', TRUE),
            'office_name' => $this->input->post('office_name', TRUE),
            'office_address' => $this->input->post('office_address', TRUE),
            'mobile' => $this->input->post('mobile', TRUE),
            'roleId' => new ObjectId($this->input->post('roleId', TRUE)),
            'updatedBy' => $this->input->post('roleId', TRUE),
            'updatedDtm' => date("Y-m-d h:i:s"),
            'department' => array($this->input->post('department', TRUE)),
//            'roles' => $this->input->post('roles', TRUE),
        );
      if ($this->input->post('password', TRUE) !== '123456789') {
        //If password is not updated then we create this data array without password
          $data['password'] = getHashedPassword($this->input->post('password', TRUE));
      }
      $this->users_model->update($this->input->post('userId', TRUE), $data);
      $this->session->set_flashdata('message', 'Update Record Success');
      redirect(site_url('users'));
    }
  }
  /**
   * delete
   *
   * @param mixed $id
   * @return void
   */
  public function delete($id) {
    $row = $this->users_model->get_by_id($id);
    if ($row) {
      $this->users_model->delete($id);
      $this->session->set_flashdata('message', 'Delete Record Success');
      redirect(site_url('appeal/users'));
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(site_url('appeal/users'));
    }
  }
  /**
   * _rules
   *
   * @return void
   */
  public function _rules() {
    //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[tbl_users.email]',array('is_unique'=>'This %s already exists.'));
    
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]', array('min_length' => 'Please provide minimum 5 characters.', 'required' => 'This Filed is required'));
    $this->form_validation->set_rules('name', 'Name', 'trim|required');
    $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
    $this->form_validation->set_rules('office_name', 'Office Name', 'trim|required');
    $this->form_validation->set_rules('office_address', 'Office Address', 'trim|required');
    $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|exact_length[10]');
    $this->form_validation->set_rules('roleId', 'Role', 'trim|required');
    $this->form_validation->set_rules('c_password', 'Password Confirmation', 'required|matches[password]');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
  }
  public function username_check($str) {
      return true;
      // todo :: important :: userExists function not found
//    if (userExists($str)) {
//      $this->form_validation->set_message('username_check', 'Username Already Exists');
//      return FALSE;
//    } else {
//      return TRUE;
//    }
  }

  // Change user password
  /**
   * change_user_password
   *
   * @return void
   */
  public function change_user_password() {

    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]', array('min_length' => 'Please provide minimum 5 characters.'));
    $this->form_validation->set_rules('c_password', 'Password Confirmation', 'required|matches[password]');

    if ($this->form_validation->run() === FALSE) {
      echo json_encode(array(
        'message' => validation_errors('<p class="font-weight-bold text-capitalize text-danger p-1 mb-2">', '</p>')
      ));
    } 
    else {
      // Update password

      $this->users_model->update($this->input->post('userId', TRUE), array(
        'password' => getHashedPassword($this->input->post('password', TRUE)),
      ));

      echo json_encode(array(
        'message' => '<p class="font-weight-bold text-capitalize text-success p-1 mb-2">password changed successfully.</p>'
      ));
    }
  }

  /**
   * profile
   *
   * @return void
   */
  public function profile(){
      $this->load->view('includes/header', array('pageTitle' => 'Dashboard | Profile'));
      $this->load->view('users/profile');
      $this->load->view('includes/footer');
  }

}
/* End of file Users.php */
/* Location: ./application/controllers/Users.php */
