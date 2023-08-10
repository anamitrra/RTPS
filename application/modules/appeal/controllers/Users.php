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
    $this->load->model('Users_update_logs_modes');
    $this->load->library('form_validation');
   
    $roleList = getRoles();
    $role = $this->session->userdata("role");
    if (!in_array($role->slug,['SA','ADMIN'])) {
      redirect(base_url("appeal/login/logout"));
    }
  }
  /**
   * get_records
   *
   * @return void
   */
  public function get_records() {
    $fillter=array('is_verified'=>true);
    // $this->load->library("datatables");
    $columns = array("userId", "name", "mobile", "email");
    $limit = $this->input->post("length");
    $start = $this->input->post("start");
    $order = $columns[(int) $this->input->post("order")[0]["column"]];
    $dir = $this->input->post("order")[0]["dir"];
    $totalData = $this->users_model->total_rows_inactive($fillter);
    $totalFiltered = $totalData;
    if (empty($this->input->post("search")["value"])) {
      $records = $this->users_model->all_rows($limit, $start, $order, $dir,$fillter);
    } else {
      $search = trim($this->input->post("search")["value"]);
      $records = $this->users_model->users_search_rows($limit, $start, $search, $order, $dir,$fillter);
      $totalFiltered = $this->users_model->users_tot_search_rows($search,$fillter);
      $totalFiltered = count((array) $totalFiltered);
    }
    $data = array();

    if (!empty($records)) {
      $sl = 1;
      foreach ($records as $rows) {
        $objId = $rows->{"_id"}->{'$id'};
        $btns = '<a href="' . base_url("appeal/users/read/$objId") . '" data-toggle="tooltip" data-placement="top" title="Edit" class="" ><span class="fa fa-eye" aria-hidden="true"></span></a> ';
        $btns .= '<a href="' . base_url("appeal/users/update/$objId") . '" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>';
        // $btns .= '<a href="' . base_url("appeal/users/delete/$objId") . '" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>';
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

  public function get_inactive_records() {
   
    $fillter=array('is_verified'=>false);
    // $this->load->library("datatables");
    $columns = array("userId", "name", "mobile", "email");
    $limit = $this->input->post("length");
    $start = $this->input->post("start");
    $order = $columns[(int) $this->input->post("order")[0]["column"]];
    $dir = $this->input->post("order")[0]["dir"];
    $totalData = $this->users_model->total_rows_inactive($fillter);
    $totalFiltered = $totalData;
    if (empty($this->input->post("search")["value"])) {
      $records = $this->users_model->all_rows($limit, $start, $order, $dir,$fillter);
    } else {
      $search = trim($this->input->post("search")["value"]);
      $records = $this->users_model->users_search_rows($limit, $start, $search, $order, $dir,$fillter);
      $totalFiltered = $this->users_model->users_tot_search_rows($search,$fillter);
      $totalFiltered = count((array) $totalFiltered);
    }
    $data = array();

    if (!empty($records)) {
      $sl = 1;
      foreach ($records as $rows) {
        $objId = $rows->{"_id"}->{'$id'};
        // $btns = '<a href="' . base_url("appeal/users/read/$objId") . '" data-toggle="tooltip" data-placement="top" title="Edit" class="" ><span class="fa fa-eye" aria-hidden="true"></span></a> ';
        // $btns .= '<a href="' . base_url("appeal/users/update/$objId") . '" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>';
        $btns = '<a href="' . base_url("appeal/users/detail/$objId") . '" data-toggle="tooltip" data-placement="top"  class=""><span class="fa fa-eye" aria-hidden="true">View</span></a>';
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
    $this->load->view('includes/header', array("pageTitle" => "Users"));
    $this->load->view('users/users_list');
    $this->load->view('includes/footer');
  }

 
  public function inactive() {
    $this->load->view('includes/header', array("pageTitle" => "Users"));
    $this->load->view('users/inactive_users_list');
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
      $this->load->view('includes/header', array('pageTitle' => 'users'));
      $this->load->view('users/users_read', $data);
      $this->load->view('includes/footer');
    } else {
      $this->session->set_flashdata('errors', 'Record Not Found');
      redirect(site_url('users'));
    }
  }

  /**
   * read
   *
   * @param mixed $id
   * @return void
   */
  public function detail($id) {
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
      $this->load->view('includes/header', array('pageTitle' => 'users'));
      $this->load->view('users/users_read_inactive', $data);
      $this->load->view('includes/footer');
    } else {
      $this->session->set_flashdata('errors', 'Record Not Found');
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
      'username' => set_value('username'),
      'da_type'=>false,
      'departments' => $this->department_model->get_departments(),
      'roles' => $this->roles_model->get_all([]),
      'roles_array' => [],
    );
    $this->load->view('includes/header', array('pageTitle' => 'Create User'));
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

      // $email = $this->input->post('email', TRUE);
      // print_r($email);
      // return;

      $role=$this->users_model->check_role_exits($this->input->post('roleId', TRUE));
      if(empty($role)){
        $this->session->set_flashdata('errors', 'Invalid Role');
        redirect(base_url('appeal/users'));
      }
      $check_dept=$this->users_model->check_dept_exits($this->input->post('department', TRUE));
      if(empty($check_dept)){
        $this->session->set_flashdata('errors', 'Invalid Department');
        redirect(base_url('appeal/users'));
      }


      // User name exist?
      $getUserName =  (array)$this->users_model->getUserName($this->input->post("username"));

      // return print_r($getUserEmailAddress);
      // echo count($getUserEmailAddress);
      // return;


      if($getUserName){
              if (count($getUserName)>=1){
                $this->session->set_flashdata('errors', 'This Username is already exist...Please try with a different Username.');
                redirect(base_url('appeal/users'));
                // pre("Found");
                return;
              };
      }






      // Email exist?
              // $getUserEmailAddress =  (array)$this->users_model->getUserEmailAddress($this->input->post("email"));

              // return print_r($getUserEmailAddress);
              // echo count($getUserEmailAddress);
              // return;


              // if($getUserEmailAddress){
              //         if (count($getUserEmailAddress)>=1){
              //           $this->session->set_flashdata('errors', 'This email address already exist...Please try with a different email address.');
              //           redirect(base_url('appeal/users'));
              //         };
              // }



      $data = array(
        // 'email' => $email,
        'username' => $this->input->post('username', TRUE),
        'email' => $this->input->post('email', TRUE),
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
        'is_verified'=>true,
//        'roles' => $this->input->post('roles', TRUE), // for multi role
      );

      // return print_r($data);

      if($this->input->post('role_slug') === "DA"){
        if(empty($this->input->post('da_type'))){
          $this->session->set_flashdata('errors', 'Please select DA type');
          redirect(base_url('appeal/users'));
        }

        $data['da_type']=$this->input->post('da_type');
      }
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
    if(!$this->checkObjectId($id)){
      $this->session->set_flashdata('errors', 'Record Not Found');
      redirect(base_url('appeal/users'));
    }
    $row = $this->users_model->get_by_doc_id($id);
    // pre($row);


     if (isset($row->username)) {
      $row->username = $row->username;
    } else {
      $row->username = false;
    }
  
   
    $this->load->model("roles_model");
    $this->load->helper('form');
    $this->load->helper('url');
    if ($row) {
      $data = array(
        'breadcrumb_item' => 'Update User',
        'button' => 'Update',
        'action' => base_url('appeal/users/update_action'),
        'userId' => $row->{"_id"}->{'$id'},
        'email' => $row->email,
        'password' => '123456789',
        'name' => $row->name,
        'username'=>$row->username,
        'designation' => $row->designation,
        'office_name' => $row->office_name,
        'office_address' => $row->office_address,
        'mobile' => $row->mobile,
        'da_type' => property_exists($row,'da_type') ?  $row->da_type :false,
        'roles' => $this->roles_model->get_all([]),
        //'roles_array' => $row->roles,
        'roleId' => $row->roleId,
        'departments' => $this->department_model->get_departments(),
        'department_id' => set_value('department', $row->department[0]),
      );
      //pre($data);
      $this->load->view('includes/header', array('pageTitle' => 'Update User'));
      $this->load->view('users/users_form', $data);
      $this->load->view("includes/footer");
    } else {
      $this->session->set_flashdata('message', 'Record Not Found');
      redirect(base_url('appeal/users'));
    }
  }
  private function checkObjectId($obj){
    if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
        return true;
   }else{
       return false;
   }
}
  /**
   * update_action
   *
   * @return void
   */
  public function update_action() {
    // die("Hello");
    if(!$this->checkObjectId($this->input->post('userId', TRUE))){
      $this->session->set_flashdata('errors', 'Record Not Found');
      redirect(base_url('appeal/users'));
    }
    $this->_rules(true);
    //$this->form_validation->set_rules('roles[]', 'Role', 'required');
    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('userId', TRUE));
    } else {
      $role=$this->users_model->check_role_exits($this->input->post('roleId', TRUE));
      if(empty($role)){
        $this->session->set_flashdata('errors', 'Invalid Role');
        redirect(base_url('appeal/users'));
      }
      $check_dept=$this->users_model->check_dept_exits($this->input->post('department', TRUE));
      if(empty($check_dept)){
        $this->session->set_flashdata('errors', 'Invalid Department');
        redirect(base_url('appeal/users'));
      }

            // Email exist?
            // $getUserEmailAddress =  (array)$this->users_model->getUserEmailAddress($this->input->post("email"),$this->input->post('userId', TRUE));


            // if($getUserEmailAddress){
            //         if (count($getUserEmailAddress)>=1){
            //           $this->session->set_flashdata('errors','This email address already exist...Please try with a different email address.');
            //           redirect(base_url('appeal/users'));
            //         };          
            // }

          //  Username exist?

            $is_username_exist=$this->users_model->is_username_exist($this->input->post("username"),$this->input->post('userId', TRUE));
// pre( $is_username_exist);
           



            if($is_username_exist){
              $this->session->set_flashdata('errors','This Username is already exist...Please try with a different username.');
              redirect(base_url('appeal/users'));     
            }
// pre("hetre");
        $data = array(
            'email' => $this->input->post('email', TRUE),
            'name' => $this->input->post('name', TRUE),
            'username' => $this->input->post('username', TRUE),
            'designation' => $this->input->post('designation', TRUE),
            'office_name' => $this->input->post('office_name', TRUE),
            'office_address' => $this->input->post('office_address', TRUE),
            'mobile' => $this->input->post('mobile', TRUE),
            'roleId' => new ObjectId($this->input->post('roleId', TRUE)),
            // 'updatedBy' => $this->input->post('roleId', TRUE),
            'updatedBy' => $this->session->userdata('userId')->{'$id'},
            'updatedDtm' => date("Y-m-d h:i:s"),
            'department' => array($this->input->post('department', TRUE)),
//            'roles' => $this->input->post('roles', TRUE),
        );

        if($this->input->post('role_slug') === "DA"){
          if(empty($this->input->post('da_type'))){
            $this->session->set_flashdata('errors', 'Please select DA type');
            redirect(base_url('appeal/users'));
          }
          $data['da_type']=$this->input->post('da_type');
        }


      if ($this->input->post('password', TRUE) !== '123456789') {
        //If password is not updated then we create this data array without password
          $data['password'] = getHashedPassword($this->input->post('password', TRUE));
      }
            // Old data
            $old_data =  $this->users_model->by_id($this->input->post('userId', TRUE));
            $old_data_array = json_decode(json_encode($old_data), true);

            // pre($old_data_array);

     $res= $this->users_model->update($this->input->post('userId', TRUE), $data);
      if($res){
        $loggedUser=$this->session->userdata('userId')->{'$id'};
      // New data
      $data['old_data']=   isset($old_data_array)? $old_data_array[0] : array();
      $data['old_data']['objid']=  new ObjectId( $old_data_array[0]['_id']['$id']);
       $data['old_data']['roleId']=  new ObjectId( $old_data_array[0]['roleId']['$oid']);
      unset( $data['old_data']['_id']);
      unset( $data['old_data']['createdDtm']);
      unset( $data['old_data']['updatedDtm']);
       unset( $data['old_data']['updated_at']);
      $data['updatedBy'] = $loggedUser;
      //  pre($data);

      $this->Users_update_logs_modes->insert($data);


      $this->session->set_flashdata('message', 'Update Record Success');
      redirect(base_url('appeal/users'));
      }else{
        $this->session->set_flashdata('errors', 'Unable to update');
        redirect(base_url('appeal/users'));
      }

    }
  }
  /**
   * delete
   *
   * @param mixed $id
   * @return void
   */
  public function delete($id) {
    $row = $this->users_model->get_by_doc_id($id);
    if ($row) {
      $this->users_model->delete($id);
      $this->session->set_flashdata('message', 'Delete Record Success');
      redirect(site_url('appeal/users'));
    } else {
      $this->session->set_flashdata('errors', 'Record Not Found');
      redirect(site_url('appeal/users'));
    }
  }
  /**
   * _rules
   *
   * @return void
   */
  public function _rules($edit=false) {
    //$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[tbl_users.email]',array('is_unique'=>'This %s already exists.'));
   
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]', array('min_length' => 'Please provide minimum 5 characters.', 'required' => 'This Field is required'));
    $this->form_validation->set_rules('name', 'Name', 'trim|required|alpha_numeric_spaces');
    $this->form_validation->set_rules('designation', 'Designation', 'trim|required|alpha_numeric_spaces');
    $this->form_validation->set_rules('office_name', 'Office Name', 'trim|required|alpha_numeric_spaces');
    $this->form_validation->set_rules('office_address', 'Office Address', 'trim|required|alpha_numeric_spaces');
    $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|exact_length[10]|numeric');
    $this->form_validation->set_rules('roleId', 'Role', 'trim|required|alpha_numeric');
    $this->form_validation->set_rules('c_password', 'Password Confirmation', 'required|matches[password]');
    if($edit){
      $this->form_validation->set_rules('userId', 'Invalid User', 'required|alpha_numeric');
    }
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
      $this->load->view('includes/header');
      $this->load->view('users/profile');
      $this->load->view('includes/footer');
  }
  public function verify($id){
    $res=$this->users_model->verify_user($id,array('is_verified'=>true));
   
    if ($res) {
      $this->session->set_flashdata('message', 'User Verified Successfully');
      redirect(site_url('appeal/users/inactive'));
    } else {
      $this->session->set_flashdata('errors', 'Something went wrong');
      redirect(site_url('appeal/users/inactive'));
    }
  }

  //command for existing users verifed
  // db.users.update(
  //   {},
  //   { $set: {"is_verified": true} },
  //   false,
  //   true
  // )

}
/* End of file Users.php */
/* Location: ./application/controllers/Users.php */