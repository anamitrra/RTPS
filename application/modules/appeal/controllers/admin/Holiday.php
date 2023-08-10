<?php

use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Holiday extends Rtps{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('holiday_model');
        $role = $this->session->userdata("role");
        if (!in_array($role->slug,['SA','ADMIN'])) {
            redirect(base_url("appeal/login/logout"));
        }
    }

    public function index(){
        $this->load->view('includes/header');
        $this->load->view('holiday/index');
        $this->load->view('includes/footer');
    }

    public function get_records(){
        $columns = array(
            0 => "title",
            1 => "dates",
            2 => "year",
        );
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->holiday_model->total_rows();
        $totalFiltered = $totalData;
        $filter = array();
        if (empty($this->input->post("search")["value"])) {
            $this->session->set_userdata($filter);
            $records = $this->holiday_model->all_rows($limit, $start, $order, $dir);
        } else {
            $search = trim($this->input->post("search")["value"]);
            $temp = array(
                '$or'=>[
                    ['title' =>['$regex'=>'^' . $search . '','$options' => 'i']],
//                    ['dates' =>['$regex'=>'^' . $search . '','$options' => 'i']],
                    ['year' =>['$regex'=>'^' . $search . '','$options' => 'i']],
                ]
            );
            $records = $this->holiday_model->search_rows($limit, $start, $temp, $order, $dir);
            $totalFiltered = $this->holiday_model->tot_search_rows($temp);
        }
        $data =[];
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                $btns = '';
                $objId = $rows->{"_id"}->{'$id'};
                $btns .= '<a href="#" data-toggle="tooltip" data-placement="top" title="Edit" onclick="openEditHolidayModal(this)" data-id="'.$objId.'" class="btn-sm btn-outline-info"><span class="fa fa-edit" aria-hidden="true"></span></a>';
                $btns .= '<a href="' . base_url("appeal/holiday/delete/$objId") . '" data-toggle="tooltip" data-placement="top" title="Delete" class="btn-sm btn-outline-danger" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>';
                $nestedData["sl_no"] = $sl;
                $nestedData["title"] = $rows->title;
                $nestedData["year"] = $rows->year;
                $nestedData["dates"] = implode(', ',$rows->dates);
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

    public function add(){
        $this->form_validation->set_rules('title','Title','trim|required');
        $this->form_validation->set_rules('year','Year','trim|required|exact_length[4]');
        $this->form_validation->set_rules('date','Date','trim|required');
        if($this->form_validation->run() == false){
            $this->session->set_flashdata('errors',validation_errors());
            redirect(base_url('appeal/holiday'));
        }
        $nowDb = date('d-m-Y H:i');
        $check=$this->valid_dates(explode(',',$this->input->post('date')));
        if(!$check){
            $this->session->set_flashdata('fail','Invalid Date!!! Please try again.');
            redirect(base_url('appeal/holiday'));
            return;
        }
        $holidayInput = [
            'title' => $this->input->post('title'),
            'year' => $this->input->post('year'),
            'dates' => explode(',',$this->input->post('date')),
            'created_at' => new UTCDateTime(strtotime($nowDb) * 1000),
        ];

        if($this->holiday_model->insert($holidayInput)){
            $this->session->set_flashdata('success','Holiday added successfully.');
        }else{
            $this->session->set_flashdata('fail','Failed to add holiday!!! Please try again.');
        }
        redirect(base_url('appeal/holiday'));
    }
    private function valid_dates($data){
       // pre($data);
       $res=true;
        foreach($data as $dates){
            $d= $this->check_date($dates);
            if( $d != 1){
                $res=false;
            break;
            }
        }
       return $res;
    }
    public function check_date($date) {
        $d = DateTime::createFromFormat('d-m-Y', $date);
        return $d && $d->format('d-m-Y') === $date;
    }

    public function update(){
        $this->form_validation->set_rules('title','Title','trim|required');
        $this->form_validation->set_rules('year','Year','trim|required|exact_length[4]');
        $this->form_validation->set_rules('date','Date','trim|required');
        if($this->form_validation->run() == false){
            $this->session->set_flashdata('errors',validation_errors());
            redirect(base_url('appeal/holiday'));
        }
        $nowDb = date('d-m-Y H:i');
        $check=$this->valid_dates(explode(',',$this->input->post('date')));
        if(!$check){
            $this->session->set_flashdata('fail','Invalid Date!!! Please try again.');
            redirect(base_url('appeal/holiday'));
            return;
        }
        $holidayInput = [
            'title' => $this->input->post('title'),
            'year' => $this->input->post('year'),
            'dates' => explode(',',$this->input->post('date')),
            'updated_at' => new UTCDateTime(strtotime($nowDb) * 1000),
        ];

        if($this->holiday_model->update($this->input->post('id'),$holidayInput)){
            $this->session->set_flashdata('success','Holiday updated successfully.');
        }else{
            $this->session->set_flashdata('fail','Failed to update holiday!!! Please try again.');
        }
        redirect(base_url('appeal/holiday'));
    }

    public function delete($id){
        if($this->holiday_model->delete($id)) {
            $this->session->set_flashdata('success','Holiday deleted successfully.');
        }else{
            $this->session->set_flashdata('fail','Failed to deleted holiday!!! Please try again.');
        }
        redirect(base_url('appeal/holiday'));
    }
}