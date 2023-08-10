<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class user_registration extends Frontend {
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
    
   // $roleList = getRoles();
  }
  
  
 public function unsetValue(array $array, $value, $strict = TRUE)
{
  $list=array();
  $arr=array('DPS','AA','RA','PFC','DA','RR','MOC');
    foreach($array as $key =>$role){
        if( in_array($role->slug,$arr)){
          array_push($list,$array[$key]);
        }
        
    }
    return $list;
}
  /**
   * create
   *
   * @return void
   */
  public function create() {
   
    $this->load->model("roles_model");
    $roles=$this->roles_model->get_all([]);
    $new_roles=$this->unsetValue((array)$roles,array("slug"=>"SA"));
   
    $data = array(
      'button' => 'Create',
      'action' => base_url('appeal/user_registration/create_action'),
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
      'roles' => $new_roles,
      'roles_array' => [],
    );
    $this->load->view('includes/frontend/header', array('pageTitle' => 'Create User'));
    $this->load->view('users_registration/users_form', $data);
    $this->load->view('includes/frontend/footer');
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
      $check_user_exist=$this->users_model->get_by_email( $email);
      if(!empty($check_user_exist)){
        $this->session->set_flashdata('class', 'alert-danger');
        $this->session->set_flashdata('message', 'User already exist.');
        redirect(base_url('appeal/user_registration/status'));
      }
      $role=$this->users_model->check_role_exits($this->input->post('roleId', TRUE));
      if(empty($role)){
        $this->session->set_flashdata('class', 'alert-danger');
        $this->session->set_flashdata('message', 'Invalid Role');
        redirect(base_url('appeal/user_registration/status'));
      }
      $check_dept=$this->users_model->check_dept_exits($this->input->post('department', TRUE));
      if(empty($check_dept)){
        $this->session->set_flashdata('class', 'alert-danger');
        $this->session->set_flashdata('message', 'Invalid Department');
        redirect(base_url('appeal/user_registration/status'));
      }
      $data = array(
        'email' => $email,
        'password' => getHashedPassword($this->input->post('password', TRUE)),
        'name' => $this->input->post('name', TRUE),
        'designation' => $this->input->post('designation', TRUE),
        'office_name' => $this->input->post('office_name', TRUE),
        'office_address' => $this->input->post('office_address', TRUE),
        'mobile' => $this->input->post('mobile', TRUE),
        'roleId' => new ObjectId($this->input->post('roleId', TRUE)),
        'isDeleted' => '0',
        'createdBy' => $this->input->post('roleId', TRUE),
        'createdDtm' => date("Y-m-d h:i:s"),
        'updatedBy' => $this->input->post('roleId', TRUE),
        'updatedDtm' => date("Y-m-d h:i:s"),
        'department' => array($this->input->post('department', TRUE)),
        'is_verified' => false
//        'roles' => $this->input->post('roles', TRUE), // for multi role
      );
    //  pre($data);
      $insert_id=$this->users_model->insert($data);
    //   if($insert_id){
    //     $this->session->set_flashdata('message', 'Something went wrong! Please try again');
    //     redirect(base_url('appeal/user_registration/status'));
    //   }else{
    //     $this->session->set_flashdata('message', 'Registration Successfull. Your Account will active soon');
    //     redirect(base_url('appeal/user_registration/status'));
    //   }
    $this->session->set_flashdata('class', 'alert-success');
    $this->session->set_flashdata('message', 'Registration Successfull. Your Account will active soon');
        redirect(base_url('appeal/user_registration/status'));
      
    }
  }

 

  /**
   * _rules
   *
   * @return void
   */
  public function _rules() {
    //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[tbl_users.email]',array('is_unique'=>'This %s already exists.'));
    
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]', array('min_length' => 'Please provide minimum 5 characters.', 'required' => 'This Field is required'));
    $this->form_validation->set_rules('name', 'Name', 'trim|required|alpha_numeric_spaces');
    $this->form_validation->set_rules('designation', 'Designation', 'trim|required|alpha_numeric_spaces');
    $this->form_validation->set_rules('office_name', 'Office Name', 'trim|required|alpha_numeric_spaces');
    $this->form_validation->set_rules('office_address', 'Office Address', 'trim|required|alpha_numeric_spaces');
    $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|exact_length[10]|numeric');
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
  public function status(){
    $this->load->view('includes/frontend/header', array('pageTitle' => 'Status'));
    $this->load->view('users_registration/status',);
    $this->load->view('includes/frontend/footer');
  }
}
