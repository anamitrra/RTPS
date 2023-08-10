<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Profiles extends Rtps
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->model('Users_update_logs_modes');
        $this->load->library('form_validation');
        $this->load->helper('form');
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
    //    die("OK");
        $s_user = $this->session->userdata('userId');
        $id = $s_user->{'$id'};
        $user = $this->users_model->get_by_doc_id($id);
        $data = [];
            
        if (!empty($this->session->flashdata('success'))) {
            
            $data['success'] = $this->session->flashdata('success');
        } elseif (!empty($this->session->flashdata('error'))) {
            
            $data['error'] = $this->session->flashdata('error');
        }

        

        if (isset($user->username)) {
            $user->user = $user->username;
          } else {
            $user->username = false;
          }
        
          
        
        $data['name']  = $user->name;
        $data['email']  = $user->email;
        $data['mobile']  = $user->mobile;
        $data['username']  = $user->username;
        $data['photo']  = $user->photo ?? '';
    
        // print_r($data);

        $this->load->view('includes/header');
        $this->load->view('profiles/index', $data);
        $this->load->view('includes/footer');
    }

    //Update Profile
    public function update(){
        // pre("OK");
        // return;
        // return pre($this->session->userdata('userId')->{'$id'});

        // $old_data =  $this->users_model->by_id($this->session->userdata('userId')->{'$id'});
        // $old_data_array = json_decode(json_encode($old_data), true);


        // return pre($old_data);
        $this->load->model('login_model');
        $this->load->model('roles_model');
        $this->load->helper('captcha');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('captcha', 'Captcha', 'validate_captcha|trim');



        //Validation Rules
        $this->form_validation->set_rules("email", "Email Address", "required|valid_email|trim");
        $this->form_validation->set_rules("name", "Name", "required");
        $this->form_validation->set_rules("phone", "Phone Number", "required|numeric|max_length[10]|min_length[10]");
        $this->form_validation->set_rules("username", "Username", "required|trim");

        if($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata('error', validation_errors());
            redirect('appeal/profile');
        }
        else{
            $this->load->helper("fileupload");
            $file=moveFile(0, "avatar");
            if($file){
                $this->session->set_userdata("image",base_url($file[0]));
            }


                        // Email exist?
                        // $getUserEmailAddress =  (array)$this->users_model->getUserEmailAddress($this->input->post("email"),$this->session->userdata('userId')->{'$id'}); 

            
            
                        // if($getUserEmailAddress){
                        //         if (count($getUserEmailAddress)>=1){
                        //           $this->session->set_flashdata('errors','This email address already exist...Please try with a different email address.');
                        //           redirect(base_url('appeal/users'));
                        //         };           
                        // }


    // User name exist?
    //   $getUserName =  (array)$this->users_model->getUserName($this->input->post("username"));

    // //   print_r($getUserName[0]->updatedBy);
    // //   echo("   ");
    // //   echo($this->session->userdata('userId')->{'$id'});
    // //   return;



    //   if($getUserName){
    //           if (count($getUserName)>=1 && (($getUserName[0]->updatedBy) !== ($this->session->userdata('userId')->{'$id'}))){
    //             $this->session->set_flashdata('error', 'This Username is already exist...Please try with a different Username.');
    //             redirect(base_url('appeal/profile'));
    //             // pre("Found");
    //             // return;
    //           };
    //   }             

    $getUserName = (array) $this->users_model->getUserName($this->input->post("username"));

if ($getUserName) {
    if (count($getUserName) >= 1 && $getUserName[0]->updatedBy !== $this->session->userdata('userId')->{'$id'}) {
        if ($this->input->post("username") !== $this->session->userdata('username')) {
            $this->session->set_flashdata('error', 'This Username is already in use. Please try a different Username.');
            redirect(base_url('appeal/profile'));
        }
    }
}


            $s_user = $this->session->userdata('userId');
            $id = $s_user->{'$id'};


            $data = array(
                'email' => $this->input->post('email'),
                'mobile' => $this->input->post('phone'),
                'name' => $this->input->post('name'),
                'username'=> $this->input->post('username'),
                'photo'=>$file[0],
                'updatedBy' => $this->session->userdata('userId')->{'$id'},
                'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            );

            // Old data
            $old_data =  $this->users_model->by_id($this->session->userdata('userId')->{'$id'});
            $old_data_array = json_decode(json_encode($old_data), true);

            

            $s_user = $this->session->userdata('userId');
            $id = $s_user->{'$id'};
            $user = $this->users_model->update($id, $data);

            if($user){
                // New data
                // $data['old_data']=  $old_data_array;
                // $data['updatedBy'] = $this->session->userdata('userId')->{'$id'};
                $loggedUser=$this->session->userdata('userId')->{'$id'};

                $data['old_data']=   isset($old_data_array)? $old_data_array[0] : array();

                $data['old_data']['objid']=  $old_data_array[0]['_id']['$id'];

                $data['old_data']['roleId']=  $old_data_array[0]['roleId']['$oid'];

                unset( $data['old_data']['_id']);

                unset( $data['old_data']['createdDtm']);
                unset( $data['old_data']['updatedDtm']);
                 unset( $data['old_data']['updated_at']);
                $data['updatedBy'] = $loggedUser;
                //  pre($data);




                // pre($data);
                // return;

                $this->Users_update_logs_modes->insert($data);

                // $this->session->set_flashdata('success', 'Profile has been successfully updated');

                // redirect(base_url('appeal/profile'));
                $this->session->set_flashdata('success', 'Profile has been successfully updated.');
                // redirect(base_url('appeal/profile'));

                $result = $this->login_model->selectUser($this->session->userdata('userId')->{'$id'});

                // print_r($result);
                // return;


                if (isset($result) && $result != NULL) {
                    // pre($result);
                    if($result->is_verified){
                        $dept = $this->login_model->getDepartment($result->department[0]);
                        $sessionArray = array(
                            'userId' => $result->_id,
                            'role' => $this->roles_model->get_role_info($result->roleId),
                            'image' => (isset($result->photo)) ? base_url($result->photo) : base_url("storage/images/avatar.png"),
                            'name' => $result->name,
                            'username'=>$result->username,
                            'email'=>$result->email,
                            'isLoggedIn' => TRUE,
                            'department_name' => $dept->department_name,
                            'department_id' => $dept->department_id,
                        );
                        $filter = array(
                            "user_id" => $result->_id->{'$id'},
                            "role" => $sessionArray['role']->slug
                        );
                        $loc = $this->roles_model->get_user_location($filter);
                        if ($loc) {
                            $sessionArray['location'] = strval($loc->location_id);
                        }
                        // pre($sessionArray);
                        $this->session->set_userdata($sessionArray);
                        redirect(base_url() . 'appeal/dashboard/');
                    }else{
                        $this->session->set_flashdata('error', 'Your Account is not verified yet');
                        redirect(base_url() . 'appeal/admin/login');
                    }
                    
                } 
            }
            else{
                $this->session->set_flashdata('message', 'Unable to update');
                redirect(base_url('appeal/profile'));
            }

            // $this->session->set_flashdata('success', 'Profile has been successfully updated');
            // redirect('appeal/profile');
        }


        

    }

    //Chnage password
    public function password(){
        $this->load->view('includes/header');
        $this->load->view('profiles/password_change');
        $this->load->view('includes/footer');
    }

    private function checkPassword($password){

            // Validate password strength
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);
            $specialChars = preg_match('@[^\w]@', $password);

            if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                return array("status"=>false,'message'=>'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.');
                // echo 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
            }else{
                return array("status"=>true,"message"=>"");
                //echo 'Strong password.';
            }
    }
    //Update password
    public function password_update(){
        $this->load->library("encryption_custom");
        $old_pass = $this->encryption_custom->decrypt($this->input->post("old_pass"), 'asdf-ghjk-qwer-tyui');
        $password = $this->encryption_custom->decrypt($this->input->post("password"), 'asdf-ghjk-qwer-tyui');
        $confirm_password = $this->encryption_custom->decrypt($this->input->post("confirm_password"), 'asdf-ghjk-qwer-tyui');
        $_POST['old_pass'] = $old_pass;
        $_POST['password'] = $password;
        $_POST['confirm_password'] = $confirm_password;
         //Validation Rules
         $this->form_validation->set_rules("old_pass", "Old Password", "required");
         $this->form_validation->set_rules("password", "Password", 'required|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*]).{8,}$/]|differs[old_pass]');
         $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*]).{8,}$/]');

         if($this->form_validation->run() == FALSE){
            $this->load->view('includes/header');
            $this->load->view('profiles/password_change');
            $this->load->view('includes/footer');
         }
         else{

            $old_pass = $this->input->post('old_pass');
            
            $s_user = $this->session->userdata('userId');
            $id = $s_user->{'$id'};

            $user = $this->users_model->get_by_doc_id($id);

            if(verifyHashedPassword($old_pass, $user->password)){
                $newpass = getHashedPassword($this->input->post('password', TRUE));
                $this->users_model->update($id, [
                    'password' => $newpass,
                    'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
                ]);
                $data['success'] = 'Password has been successfully updated.';
            }
            else{
                $data['error'] = 'Old Password did not matched !';
            }

             $this->session->set_flashdata($data);
            redirect('appeal/password');
         }
    }

    //Upload photo
    public function upload_photo(){
        $filename = time();
        $config['upload_path']          = './storage/uploads/profiles/';
        $config['allowed_types']        = 'jpeg|jpg|png';
        $config['max_size']             = 250;
        $config['file_name']            = $filename;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('file'))
        {
                $error = array('error' => $this->upload->display_errors());
                return false;
        }
        else
        {
            $s_user = $this->session->userdata('userId');
            $id = $s_user->{'$id'};
            $user = $this->users_model->get_by_doc_id($id);
            if(isset($user->photo)){
                unlink('storage/uploads/profiles/'.$user->photo);
            }
            
            $data = $this->upload->data();
            $image_name = $filename.$data['file_ext'];
            
            $this->users_model->update($id, [
                'photo' => $image_name,
                'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
                ]);
                
                $data = [];
                $data['filename'] = $image_name;

            }
            
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data)); 
    }

    public function remove_photo(){
        $s_user = $this->session->userdata('userId');
        $id = $s_user->{'$id'};
        $user = $this->users_model->get_by_doc_id($id);

        if(isset($user->photo)){
            unlink('storage/uploads/profiles/'.$user->photo);
        }
        
        $this->users_model->update($id, [
            'photo' => null,
            'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            
        ]);

        $this->session->set_flashdata('success', 'Profile Photo has been removed.');
        redirect('ams/admin/profile');
    }

    
    
}