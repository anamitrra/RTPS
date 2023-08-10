<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Sro extends Rtps {
    public function __construct() {
        parent::__construct();
        $this->load->model('sros_model');
    }//End of __construct()

    public function index() {
        $data["sro_dist_list"] = $this->sros_model->sro_dist_list();
        //pre(  $data["sro_dist_list"]);
        // print_r($data); die();
        $this->load->view('includes/frontend/header');
        $this->load->view('sro_list_update',$data);
        $this->load->view('includes/frontend/footer');
    }
    public function list(){
        $data["list"] = $this->sros_model->get_all([]);
        
        // print_r($data); die();
        $this->load->view('includes/frontend/header');
        $this->load->view('sro_list',$data);
        $this->load->view('includes/frontend/footer');
    }
    public function getlocation(){
        $id=$_GET['id'];
        if( $id){
            $data = $this->sros_model->get_sro_list( $id);
            if( $data){
                echo json_encode( $data);
            }else{
                echo json_encode(array());
            }
        }
    }
    public function getExtradata(){
        $id=$_GET['id'];
        if( $id){
            $data = $this->sros_model->get_sro_extra_data( $id);
            if( $data){
                echo json_encode( $data);
            }else{
                echo json_encode(array());
            }
        }
    }

    public function update_sro(){
        $objId = $this->input->post("obj_id");
        $pa_district = $this->input->post("pa_district");
        $sro_code = $this->input->post("sro_code");
        $office_code = $this->input->post("office_code");
        $tcode = $this->input->post("tcode");

        $this->form_validation->set_rules('pa_district', 'District', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('sro_code', 'Submission Loacation', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('office_code', 'Office code', 'required');
        $this->form_validation->set_rules('tcode', 'Treasury code', 'required');

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            redirect('spservices/sro');
        } else {  
            $data = array(
                'office_code' => $office_code,
                'treasury_code' => $tcode
            );
        
    //    echo new ObjectId($objId);

        // Where Condition, if any
        $this->mongo_db->where(array('_id' => new ObjectId($objId)));
        // Update Data Array
        $this->mongo_db->set($data); 
        // Set Options
        $option = array('upsert' => true);
        // Call Update Function
        if($this->mongo_db->update('sro_list', $option)){
            $this->session->set_flashdata('success','Record updated successfully.');
            redirect('spservices/sro');
        }

        }
    }

    public function update_sro_ajax(){
        $sro_code = $this->input->post("sro_code");
        $office_code = $this->input->post("office_code");
        $tcode = $this->input->post("tcode");
        $this->form_validation->set_rules('office_code', 'Office code', 'required');
        $this->form_validation->set_rules('tcode', 'Treasury code', 'required');

        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
            $status["status"] = false;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($status));
        } else {  
            $data = array(
                'office_code' => $office_code,
                'treasury_code' => $tcode
            );
        

            $res=$this->sros_model->update_row(array('org_unit_code' => $sro_code),$data);
            if($res){
                $status["msg"] = "Record updated successfully";
               
                $status["status"] = true;
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($status));
            }else{
                $status["msg"] = "Record not updated";
              
                $status["status"] = false;
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($status));
            }

        }
    }
}