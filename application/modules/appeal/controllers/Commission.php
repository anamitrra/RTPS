<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Commission extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $role = $this->session->userdata("role");
        if (!in_array($role->slug,['SA','ADMIN'])) {
            redirect(base_url("appeal/login/logout"));
        }
    }

    public function index(){
        $this->isloggedin();
        $this->load->model('commission_model');
        $activeCommission = $this->commission_model->first_where([]);

        $this->load->model('roles_model');
        $userList = $this->roles_model->get_role_wise_user_list(['slug' => ['$in' => ['RA','RR']]]);
        $this->load->view("includes/header", array("pageTitle" => "Commission | Appeal"));
        $this->load->view("commission/index",compact('userList','activeCommission'));
        $this->load->view("includes/footer");
    }
    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
       }else{
           return false;
       }
    }
    public function save(){
        $chairman = $this->input->post('chairman');
        $registrar = $this->input->post('registrar');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('chairman', 'Chairman', 'required|trim');
        $this->form_validation->set_rules('registrar', 'Registrar', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata("error", validation_errors());
            $this->session->set_flashdata("old", $this->input->post());
            redirect('appeal/commission');
        }
        if(!$this->checkObjectId($this->input->post('chairman'))){
            $this->session->set_flashdata("error", "Invalid Input");
            $this->session->set_flashdata("old", $this->input->post());
            redirect('appeal/commission');
        }
        if(!$this->checkObjectId($this->input->post('registrar'))){
            $this->session->set_flashdata("error", "Invalid Input");
            $this->session->set_flashdata("old", $this->input->post());
            redirect('appeal/commission');
        }
        $this->load->model('commission_model');
        $activeCommission = $this->commission_model->first_where([]);
        // $activeCommission = $this->commission_model->first_where(['reviewing_authority' => new ObjectId($chairman),'registrar' =>  new ObjectId($registrar)]);
        if(empty($activeCommission)){
            $this->commission_model->insert(['reviewing_authority' => new ObjectId($chairman),'registrar' =>  new ObjectId($registrar)]);
        }else{
            $this->commission_model->update_where(['_id' => new ObjectId($activeCommission->_id->{'$id'}) ],['reviewing_authority' => new ObjectId($chairman),'registrar' =>  new ObjectId($registrar)]);
        }
        //update the official mapping

        $rr_id_array[] = new ObjectId($registrar);
        $reviewing_id = new ObjectId($chairman);

        $data_to_update=array(
            'reviewing_id'=>$reviewing_id,
            'registrar_id_array'=> $rr_id_array

        );
        $this->mongo_db->set($data_to_update);
        $this->mongo_db->update_all("official_details");
        $this->add_to_history(array(
            "reviewing_id"=>$reviewing_id,
            "registrar_id"=>new ObjectId($registrar),
            'crerated_by'=> new ObjectId($this->session->userdata('userId')->{'$id'}),
            "created_at"=>date('Y-m-d H:i:s')
        ));
        
        $this->session->set_flashdata("success", 'Commission saved successfully.');
        redirect('appeal/commission');
    }
    private function add_to_history($data){
        $this->mongo_db->insert('commission_change_history',$data);
    }
}