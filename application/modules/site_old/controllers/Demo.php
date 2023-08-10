<?php
defined('BASEPATH') or exit('No direct script access allowed');

/* A Demo Contoller for testing */

class Demo extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = $this->input->post(NULL, TRUE);

        if ($this->input->method(TRUE) == 'POST' && !empty($data)) {

            // Use Test DB
            $this->mongo_db->switch_db('nic_db');
            $result = $this->mongo_db->insert('demo_collection', $data);
            // redirect(base_url('site/demo'));

            $response['status'] = true;
            $response['data'] = $result;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(201)
                ->set_output(json_encode($response));
        } else {
            $this->render_view_new('demo', []);
        }
    }
}
