<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Log extends Rtps
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // $data["dbrow"] = (array)$this->mongo_db->get('digilocker_push_log');  
        $this->load->view('includes/frontend/header');
        $this->load->view('digilockermaster_view/log_view');
        $this->load->view('includes/frontend/footer');
    }

    public function get_records()
    {
        $draw = $this->input->post("draw", TRUE);
        $limit = $this->input->post("length", TRUE);
        $start = $this->input->post("start", TRUE);
        $search = ($this->input->post("search")["value"]);
        if (strlen($search)) {
            if ($search == 'true') {
                $total = (array)$this->mongo_db->where(['push_status' => true])->get('digilocker_push_log');
                $dbrow = (array)$this->mongo_db->where(['push_status' => true])->limit($limit, $start)->get("digilocker_push_log");
            } else if ($search == 'false') {
                $total = (array)$this->mongo_db->where(['push_status' => false])->get('digilocker_push_log');
                $dbrow = (array)$this->mongo_db->where(['push_status' => false])->limit($limit, $start)->get("digilocker_push_log");
            } else {
                $total = (array)$this->mongo_db->where(['rtps_ref_no' => $search])->get('digilocker_push_log');
                $dbrow = (array)$this->mongo_db->where(['rtps_ref_no' => $search])->limit($limit, $start)->get("digilocker_push_log");
            }
        } else {
            $total = (array)$this->mongo_db->get('digilocker_push_log');
            $dbrow = (array)$this->mongo_db->limit($limit, $start)->get("digilocker_push_log");
        }

        $sl = 1;
        $data = array();
        foreach ($dbrow as $val) {
            $nestedData["sl_no"] = $sl;
            $nestedData["ref_no"] = $val->rtps_ref_no;
            $nestedData["status"] = $val->push_status ? 'true' : 'false';
            $nestedData["digilocker_id"] = $val->digilocker_id ?? '';
            $nestedData["uri"] = $val->uri ?? '';
            $nestedData["doctype"] = $val->doctype ?? '';
            $nestedData["description"] = $val->description ?? '';
            $nestedData["error_response"] = $val->error_response ?? '';
            $nestedData["attempt_on"] = format_mongo_date($val->create_at ?? '');
            $data[] = $nestedData;
            $sl++;
        }
        $json_data = array(
            "draw" => $draw,
            "recordsTotal" => count($total),
            "recordsFiltered" => count($total),
            "data" => $data,
        );
        echo json_encode($json_data);
    }
}
