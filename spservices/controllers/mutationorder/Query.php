<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Query extends Rtps {

    private $serviceName = "Issuance of Certified Copy of Mutation Order";
    Private $serviceId = "MUTATION_ORDER";

    public function __construct() {
        parent::__construct();
        $this->load->model('mutationorder/mutationorders_model');
        $this->load->model('sros_model');       
        $this->load->config('spconfig');
        
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else        
        
        if($this->slug === "CSC"){                
            $this->apply_by = $this->session->userId;
        } else {
            $this->apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        }//End of if else
    }//End of __construct()
    
    public function index($objId=null) {
        if ($this->checkObjectId($objId)) {            
            $filter = array(
                "_id"=> new ObjectId($objId), 
                "service_data.appl_status"=>"QS",
                'service_data.applied_by' => $this->apply_by
            );
            $dbRow = $this->mutationorders_model->get_row($filter);
            if($dbRow) {
                $data = array("service_name" => $this->serviceName, "dbrow"=>$dbRow);
                $data["sro_dist_list"] = $this->sros_model->sro_dist_list();
                $this->load->view('includes/frontend/header');
                $this->load->view('mutationorder/query_view',$data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found in query mode against object id : '.$objId);
                redirect('spservices/mutationorder/');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/mutationorder/');
        }//End of if else
    }//End of index()
    

    public function submit() {
        $objId = $this->input->post('obj_id');
        $filter = array(
            "_id"=> new ObjectId($objId), 
            "service_data.appl_status"=>"QS",
            'service_data.applied_by' => $this->apply_by
        );
        $dbRow = $this->mutationorders_model->get_row($filter);
        if($dbRow) {
            $this->load->helper("cifileupload");
            $postUrl = $this->config->item('dhar_api')."mutation_order/enclosure_data_mutation_order.php";
            $this->form_validation->set_rules('mutation_doc_type', 'Caste certificate', 'required');
            $this->form_validation->set_rules('revenue_receipt_type', 'Revenue receipt', 'required');
            $landPatta = cifileupload("mutation_doc");
            $mutation_doc = $landPatta["upload_status"] ? $landPatta["uploaded_path"] : null;

            $khajnaReceipt = cifileupload("revenue_receipt");
            $revenue_receipt = $khajnaReceipt["upload_status"] ? $khajnaReceipt["uploaded_path"] : null;

            $uploadedFiles = array(
                "mutation_doc_old" => strlen($mutation_doc) ? $mutation_doc : $this->input->post("mutation_doc_old"),
                "revenue_receipt_old" => strlen($revenue_receipt) ? $revenue_receipt : $this->input->post("revenue_receipt_old")
            );
            $this->session->set_flashdata('uploaded_files', $uploadedFiles);

            $mutation_doc_final = strlen($mutation_doc) ? $mutation_doc : $this->input->post("mutation_doc_old");
            $revenue_receipt_final = strlen($revenue_receipt) ? $revenue_receipt : $this->input->post("revenue_receipt_old");
            
            $mutationDoc = strlen($mutation_doc_final)?base64_encode(file_get_contents(FCPATH.$mutation_doc_final)) : null;
            $revenueReceipt = strlen($revenue_receipt_final)?base64_encode(file_get_contents(FCPATH.$revenue_receipt_final)) : null;

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $this->index($objId);
            } else {            
                $data_to_update = array(
                    'form_data.mutation_doc_type' => $this->input->post("mutation_doc_type"),
                    'form_data.revenue_receipt_type' => $this->input->post("revenue_receipt_type"),
                    'form_data.mutation_doc' => $mutation_doc_final,
                    'form_data.revenue_receipt' => $revenue_receipt_final,
                    'form_data.query_remark' => $this->input->post("remarks"),
                    'service_data.appl_status' => 'QA',
                    'form_data.files_before_query' => json_decode(html_entity_decode($this->input->post("old_files")))
                );

                $processing_history = $dbRow->processing_history??array();
                $processing_history[] = array(
                    "processed_by" => "Query replied by ".$dbRow->form_data->applicant_name,
                    "action_taken" => "Query replied",
                    "remarks" => "Query replied by ".$dbRow->form_data->applicant_name,
                    "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                );                
                $data = array(
                    "spId" => array("applId" => $objId),
                    "land_revenue_receipt" => $revenueReceipt,//array("encl" => $revenueReceipt),
                    "mutation_order_recpt" => $mutationDoc,//array("encl" => $mutationDoc),
                );            
                $json_obj = json_encode($data); //pre($data);

                $curl = curl_init($postUrl);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $error_msg = curl_error($curl);
                }//End of if
                curl_close($curl); //echo $postUrl." : <br>"; echo '<pre>'; var_dump($data); echo '<pre>'; pre($response);            
                if(isset($error_msg)) {
                    die("Error in server communication ".$error_msg);
                } elseif ($response) {
                    $response = json_decode($response);
                    $res_status = $response->ref->status??null;
                    if ($res_status === "success") {
                        $data_to_update['processing_history'] = $processing_history;
                        $this->mutationorders_model->update($objId, $data_to_update);
                        $this->session->set_flashdata('pay_message', 'Query for your application has been succeessfully submitted');
                        if($this->session->role) {
                            redirect(base_url('iservices/admin/my-transactions'));
                        } else {
                            redirect(base_url('iservices/transactions'));
                        }//End of if else
                    } else {
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(401)
                            ->set_output(json_encode(array("status" => false)));
                    }//End of if else
                }//End of if
            }//End of if else
        } else {
            $this->session->set_flashdata('error','No records found in query mode against object id : '.$objId);
            redirect('spservices/mutationorder/');
        }//End of if else
    }//End of submit()

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Query