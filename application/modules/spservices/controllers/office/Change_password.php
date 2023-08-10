<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Change_password extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->library('form_validation');
        // if ($this->session->userdata()) {
        //     if (!$this->session->userdata('isAdmin')) {
        //         redirect(base_url('spservices/office-login'));
        //     }
        // } else {
        //     redirect(base_url('spservices/office-login'));
        // }
    }

    public function change_password()
    {
        $this->load->view("includes/office_includes/header", array("pageTitle" => "Change Password"));
        $this->load->view("office/change_password");
        $this->load->view("includes/office_includes/footer");
    }

    public function save_change_password()
    {

        $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required');
        // $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[8]');

        $this->form_validation->set_rules(
            'new_password', 'New Password',
            array(
                    'required',
                    array(
                            'new_password_callable',
                            function($new_password)
                            {
                                if (preg_match('/[0-9]/', $new_password) && preg_match('/[a-z]/', $new_password) && preg_match('/[A-Z]/', $new_password) && preg_match('/[!@#$%^&*()\-_=+{};:,<.>ยง~]/', $new_password) && strlen($new_password) >= 8 ) {
                                    return true;
                                   }
                                else{
                              
                                    $this->form_validation->set_message('new_password_callable', 'Password must contain: Atleast one number, one uppercase letter, one lowercase letter, one special character and atleast 8 characters long');
                                                    return FALSE;
                                }
                            }
                    )
            )
    );


        $this->form_validation->set_rules('conf_password', 'Password Confirmation', 'required|matches[new_password]');
        // pre($this->session->userdata('userId')->{'$id'});
        if ($this->form_validation->run() == FALSE) {
            $this->load->view("includes/office_includes/header", array("pageTitle" => "Change Password"));
            $this->load->view("office/change_password");
            $this->load->view("includes/office_includes/footer");
        } else {
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            $conf_password = $this->input->post('conf_password');
            $haspass = getHashedPassword($this->input->post('new_password', TRUE));
            $check_user = (array)$this->mongo_db->where(['_id' => new ObjectId($this->session->userdata('userId')->{'$id'})])->get('office_users');
            // pre($check_user);
            if ($check_user) {
                if (verifyHashedPassword($old_password, $check_user[0]->password)) {
                    // echo 'match';
                    $option = array('upsert' => true);
                    $this->mongo_db->where(array('_id' => new ObjectId($this->session->userdata('userId')->{'$id'})))->set(['password'=>$haspass])->update('office_users', $option);
                    $this->session->set_flashdata('success', 'Password changed successfully !!!');
                } else {
                    // echo 'not match';
                    $this->session->set_flashdata('warning', 'Opps.. Old Password does not matched !!!');
                }
            } else {
                // echo 'user not found';
                $this->session->set_flashdata('error', 'Opps.. something went wrong !!!');
            }
            redirect("spservices/office/change-password");
        }
    }
}
