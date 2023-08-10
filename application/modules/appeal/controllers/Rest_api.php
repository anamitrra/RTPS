<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/RestController.php');
require_once APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class Rest_api extends RestController
{
    public function __construct() {
        parent::__construct();
//        $this->load->model('user_model');

    }
    public function user_get(){
        pre('test');
        $r = $this->user_model->read();
        $this->response($r);
    }
    public function user_put(){
        $id = $this->uri->segment(3);

        $data = array('name' => $this->input->get('name'),
            'pass' => $this->input->get('pass'),
            'type' => $this->input->get('type')
        );

        $r = $this->user_model->update($id,$data);
        $this->response($r);
    }

    public function user_post(){
        $data = array('name' => $this->input->post('name'),
            'pass' => $this->input->post('pass'),
            'type' => $this->input->post('type')
        );
        $r = $this->user_model->insert($data);
        $this->response($r);
    }
    public function user_delete(){
        $id = $this->uri->segment(3);
        $r = $this->user_model->delete($id);
        $this->response($r);
    }
}