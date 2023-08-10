<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Profile extends Rtps
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
        $this->load->model('dashboard/roles_model');
        $this->load->model('dashboard/login_model');
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
        $s_user = $this->session->userdata('userId');
        $id = $s_user->{'$id'};
        $user = $this->users_model->get_by_doc_id($id);
        $data = [];

        if (!empty($this->session->flashdata('success'))) {

            $data['success'] = $this->session->flashdata('success');
        } elseif (!empty($this->session->flashdata('error'))) {

            $data['error'] = $this->session->flashdata('error');
        }

        $data['name']  = $user->name;
        $data['email']  = $user->email;
        $data['mobile']  = $user->mobile;
        $data['photo']  = $user->photo ?? '';
        // pre($data);

        $this->load->view('includes/header');
        $this->load->view('profiles/index', $data);
        $this->load->view('includes/footer');
    }

    //Update Profile
    public function update(){

        //Validation Rules
        $this->form_validation->set_rules("email", "Email Address", "required|valid_email|trim");
        $this->form_validation->set_rules("name", "Name", "required");
        $this->form_validation->set_rules("phone", "Phone Number", "required|numeric|max_length[10]|min_length[10]");

        if($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata('error', validation_errors());
            redirect('profile');
        }
        else{
            $this->load->helper("fileupload");
            $file=moveFile(0, "avatar");
            if($file){
                $this->session->set_userdata("image",base_url($file[0]));
            }
            $user_data = array(
                'email' => $this->input->post('email'),
                'mobile' => $this->input->post('phone'),
                'name' => $this->input->post('name'),
                'photo'=>$file[0],
                'updated_at' => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            );

            $s_user = $this->session->userdata('userId');
            $id = $s_user->{'$id'};
            $user = $this->users_model->update($id, $user_data);
            $this->update_user_session($id);
            $this->session->set_flashdata('success', 'Profile has been successfully updated');
            redirect('profile');
        }
    }
    //update session
    public function update_user_session($id){
      $user = $this->users_model->get_by_doc_id($id);
      $dept = $this->login_model->getDepartment($user->department[0]);
      $sessionArray = array(
        'userId' => $user->_id,
        'role' => $this->roles_model->get_role_info($user->roleId),
        'image' => (isset($user->photo))?base_url($user->photo):base_url("storage/images/avatar.jpg"),
        'name' => $user->name,
        'isLoggedIn' => TRUE,
        'department_name' => $dept->department_name,
        'department_id' => $dept->department_id,
      );
    //  pre($sessionArray);die;
      $this->session->set_userdata($sessionArray);
    }

    //Chnage password
    public function password(){
        $this->load->view('includes/header');
        $this->load->view('profiles/password_change');
        $this->load->view('includes/footer');
    }

    //Update password
    public function password_update(){
         //Validation Rules
         $this->form_validation->set_rules("old_pass", "Old Password", "required");
         $this->form_validation->set_rules("password", "Password", "required");
         $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

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

            $this->load->view('includes/header');
            $this->load->view('profiles/password_change', $data);
            $this->load->view('includes/footer');
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
    public function upload_Profile(){
      $this->load->helper("fileupload");
      $file_name=$this->input->post("filename");
      fileUpload($file_name);
    }


}
