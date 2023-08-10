<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Myapplications extends Rtps
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('intermediator_model');
        $this->load->model('portals_model');
        $this->config->load('rtps_services');
        $this->load->helper("minoritycertificate");
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
            if($this->slug == "PFC"){
                $this->load->model('admin/Users_model');
                $this->pfc=$this->Users_model->get_by_id($this->session->userdata('userId')->{'$id'});
            }
          }else{
            $this->slug = "USER";
          }
    }
    public function pending(){
        $user = $this->session->userdata();
        if ($user['role'] === "csc") {
            $apply_by = $user['userId'];
            $role = "csc";
        } else {
            $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            $role = "pfc";
        }
        $this->load->model('spservices/minoritycertificates/minoritycertificates_model');
        $this->load->model('spservices/necertificates_model');
        $this->load->model('spservices/marriageregistration/marriageregistrations_model');        
        $this->load->model('spservices/appointmentbooking/appointmentbookings_model');
        $this->load->model('spservices/incomecertificate/income_registration_model');
        $this->load->model('spservices/prc/applications_model');
        $this->load->model('spservices/noncreamylayercertificate/ncl_model');
        $this->load->model('spservices/trade_permit/tradePermit_model');

        $this->load->helper("minoritycertificate");
        $this->load->helper("appstatus");
       
        $data["minoritycertificates"] = $this->minoritycertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "MCC","service_data.appl_status"=>array('$ne'=>'D')));

        //NE Cerificate 
        $data["necertificaties"] = $this->necertificates_model->get_rows(array("applied_by" => $apply_by, "service_id" => "NECERTIFICATE","status"=>array('$ne'=>'D')));

        //Marriage Registration
        $data["marriageregistrations"] = $this->marriageregistrations_model->get_rows(array("applied_by" => $apply_by, "service_id" => "MARRIAGE_REGISTRATION","status"=>array('$ne'=>'D')));

        //Appoint Booking
        $data["appointmentbookings"] = $this->appointmentbookings_model->get_rows(array("form_data.user_id" => $apply_by, "service_data.service_id" => "APPOINTMENT_BOOKING","service_data.appl_status"=>array('$ne'=>'D')));

        // $data['intermediate_ids']=$this->intermediator_model->get_where(array('applied_by'=>$apply_by));
        $data["certifiedcopies"] = $this->necertificates_model->get_rows(array("applied_by" => $apply_by, "service_id" => "CRCPY","status"=>array('$ne'=>'D')));
        $data["seniorcitizencertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "SCTZN","service_data.appl_status"=>array('$ne'=>'D')));
        $data["delayedbirthregistration"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "PDBR","service_data.appl_status"=>array('$ne'=>'D')));

        $data["nextofkincertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "NOKIN","service_data.appl_status"=>array('$ne'=>'D')));

        $data["delayeddeathcertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "PDDR","service_data.appl_status"=>array('$ne'=>'D')));

        $data["apdcl"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "apdcl1","service_data.appl_status"=>array('$ne'=>'D')));
        // $data["prc"] = $this->applications_model->get_rows(array("service_data.applied_by"=> $apply_by,"service_data.service_id"=>"PRC")); 

        $data["prc"] = $this->applications_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "PRC","service_data.appl_status"=>array('$ne'=>'D')));

         $data["tp"] = $this->tradePermit_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "NC-TP","service_data.appl_status"=>array('$ne'=>'D')));

        $data["ncl"] = $this->ncl_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "NCL","service_data.appl_status"=>array('$ne'=>'D')));
        //income certificate
        $data["incomecertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by" => $apply_by, "service_data.service_id" => "INC","service_data.appl_status"=>array('$ne'=>'D')));

        $data["castecertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by"=> $apply_by,"service_data.service_id"=>"CASTE","service_data.appl_status"=>array('$ne'=>'D')));

        $data["bakijai"] = $this->necertificates_model->get_rows(array("service_data.applied_by"=> $apply_by,"service_data.service_id"=>"BAKCL","service_data.appl_status"=>array('$ne'=>'D')));

        $data["buildingpermissioncertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by"=> $apply_by,"service_data.service_id"=>"PPBP","service_data.appl_status"=>array('$ne'=>'D')));


        $data['intermediate_ids'] = $this->intermediator_model->get_admin_pending_transactions($apply_by, $role);
        $data['intermediate_ids'] = $this->prepare_transactions($data['intermediate_ids']);
        $data['pageTitle'] = "Transactions";

        $this->load->view('includes/header');
        // $this->load->view('admin/transactions',$data);
        $this->load->view('admin/mytransactions/transactions', $data);
        $this->load->view('includes/footer');
    }
    public function prepare_transactions($data)
    {
        $service = array();
        $vahan_services = array();
        $noc_services = array();
        $sarathi_services = array();
        $other_services = array();
        $basundhara_services = array();
        if (!empty($data)) {
            foreach ($data as $trans) {
                if ($trans->portal_no === "1" || $trans->portal_no == 1) {
                    array_push($noc_services, $trans);
                } else if ($trans->portal_no === "2" || $trans->portal_no == 2) {
                    array_push($vahan_services, $trans);
                } else if ($trans->portal_no === "4" || $trans->portal_no == 4) {
                    array_push($sarathi_services, $trans);
                } else if ($trans->portal_no === "5" || $trans->portal_no == 5) {
                    array_push($basundhara_services, $trans);
                } else {
                    array_push($other_services, $trans);
                }
            }
        }
        return array(
            "vahan_services" => $vahan_services,
            "noc_services" => $noc_services,
            "sarathi_services" => $sarathi_services,
            "other_services" => $other_services,
            "basundhara_services" => $basundhara_services,
        );
    }
    public function delivered(){
       
        $list1=$this->portals_model->get_all_services([]);
        $list2=$this->portals_model->get_all_sp_services([]);
       
        array_push($list1,(Object)array("service_name"=>"Application form for Non-Encumbrance Certificate","service_id"=>"NECERTIFICATE","collection"=>"sp_applications","applied_by_path"=>"applied_by","mobile_path"=>"mobile","service_id_path"=>"service_id","service_name_path"=>"service_name","delivery_status_path"=>"status"));
        array_push($list1,(Object)array("service_name"=>"Application for Certified Copy of Registered Deed","service_id"=>"CRCPY","collection"=>"sp_applications","applied_by_path"=>"applied_by","mobile_path"=>"mobile","service_id_path"=>"service_id","service_name_path"=>"service_name","delivery_status_path"=>"status"));
        array_push($list1,(Object)array("service_name"=>"Application for Marriage Registration","service_id"=>"MARRIAGE_REGISTRATION","collection"=>"sp_applications","applied_by_path"=>"applied_by","mobile_path"=>"applicant_mobile_number","service_id_path"=>"service_id","service_name_path"=>"service_name","delivery_status_path"=>"status"));
        // array_push($portals,(Object)array("service_name"=>"Application for Income Certificate","service_id"=>"INC","collection"=>"sp_applications","applied_by_path"=>"service_data.applied_by","mobile_path"=>"form_data.mobile","service_id_path"=>"service_data.service_id","service_name_path"=>"service_data->service_name","delivery_status_path"=>"service_data.appl_status"));
        // array_push($portals,(Object)array("service_name"=>"castecertificate","service_id"=>"CASTE","collection"=>"sp_applications","applied_by_path"=>"service_data.applied_by","mobile_path"=>"form_data.mobile","service_id_path"=>"service_data.service_id","service_name_path"=>"service_data->service_name","delivery_status_path"=>"service_data.appl_status"));
        
        // $data["castecertificate"] = $this->necertificates_model->get_rows(array("service_data.applied_by"=> $apply_by,"service_data.service_id"=>"CASTE"));
        $portals=array_merge($list1,$list2);
       
        // $this->load->model('spservices/minoritycertificates/minoritycertificates_model');
        // $this->load->model('spservices/necertificates_model');
        // $this->load->model('spservices/marriageregistration/marriageregistrations_model');
        // $this->load->model('spservices/incomecertificate/income_registration_model');
        // $this->load->model('spservices/prc/applications_model');
        // $this->load->model('spservices/noncreamylayercertificate/ncl_model');

        $this->load->helper("minoritycertificate");
        $this->load->helper("appstatus");
       
        $data['pageTitle'] = "Transactions";
        $data['service_list'] = $portals;
        if(  $this->slug === "USER"){
            $this->load->view('includes/frontend/header');
            // $this->load->view('admin/transactions',$data);
            $this->load->view('successful_application', $data);
            $this->load->view('includes/frontend/footer');
        }else{
            $this->load->view('includes/header');
            // $this->load->view('admin/transactions',$data);
            $this->load->view('successful_application', $data);
            $this->load->view('includes/footer');
        }
      
    }

    public function get_records()
    {
        $this->load->model('applications_model');
        $user = $this->session->userdata();
        if ($this->slug  === "CSC") {
            $apply_by = $user['userId'];
        }else if($this->slug  === "PFC"){
            $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        } else if($this->slug  === "USER"){
            $apply_by=$user['mobile'];
        }
        else if($this->slug  === "SA"){
            $apply_by="admin";
        }

        // pre($apply_by);
        // pre( $this->input->post());
        $filter_date=$this->input->post("filter_date"); //pre(  $filter_date);
        // pre($filter_date);
        $columns = array(
            '_id'
        );
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        //$order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData=0;
        if(!empty( $filter_date)){
            //Fetching service plus applications
            if (!$this->session->userdata('curl_executed_sp_rtps_d')) {
                $ref = modules::load('iservices/serviceplus/sprtps');
                $ref->get_sp_delivered_applications('RTPS');
            }
            $totalData = $this->applications_model->total_app_rows($apply_by , $filter_date['service_id_path'],$filter_date['service_id'],$filter_date['applied_by_path'],$filter_date['delivery_status_path'],$filter_date['collection'],$filter_date['mobile_path'],$this->slug);
            $totalFiltered = $totalData;
            // pre($totalData);
            if (empty($this->input->post("search")["value"])) {
                $records = $this->applications_model->applications_filter($limit, $start, $apply_by , $columns, $dir, $filter_date['service_id_path'],$filter_date['service_id'],$filter_date['applied_by_path'],$filter_date['delivery_status_path'],$filter_date['collection'],$filter_date['mobile_path'],$this->slug);
                
            } else {
                $search = trim($this->input->post("search")["value"]);
                $records = $this->applications_model->application_search_rows($limit, $start, $search, $columns, $dir,$apply_by , $filter_date['service_id_path'],$filter_date['service_id'],$filter_date['applied_by_path'],$filter_date['delivery_status_path'],$filter_date['collection'],$filter_date['mobile_path'],$this->slug);
                // $totalFiltered = $this->official_details_model->official_details_tot_search_rows($search);
            }
        }else{
            $records =array(); 

            $totalFiltered=0;
        }
        
        // pre($records );
        $data = array();
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                $rows=(array ) $rows;
                // pre( $rows['certificate']);
                // pre($filter_date['service_name_path']);
                // pre( $rows['service_data']->service_name);
                $nestedData["sl_no"] = $sl;
                $obj_id=$rows['_id']->{'$id'};
                if($filter_date['collection'] === "sp_applications"){
                    if(($filter_date['service_id'] === "NECERTIFICATE") || ($filter_date['service_id'] === "CRCPY") ){
                        $nestedData["service_name"] =  $rows['service_name'];
                        $nestedData["mobile"] =  $rows['mobile'];
                        $nestedData["rtps_trans_id"] =  $rows['rtps_trans_id'];
                        $nestedData["app_ref_no"] =  $rows['rtps_trans_id'];
                        $nestedData["status"] =  "DELIVERED";//$rows['status'];
                        $btns = '<a target="_blank" href="' . base_url("spservices/applications/acknowledgement/$obj_id") . '" data-toggle="tooltip" data-placement="top" title="Acknowledgement" class="btn btn-sm btn-primary mb-1" >Acknowledgement</a> ';
                  
                            if(($filter_date['service_id'] === "NECERTIFICATE") && !empty($rows['certificate'])){
                                $certificatePath = (strlen($rows['certificate'])?base_url($rows['certificate']):'#');
                                $btns .= ' <a target="_blank" href="' .$certificatePath. '" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-sm btn-success">Download</a>';
                            }
                            if(($filter_date['service_id'] === "CRCPY" && !empty($rows['certificate_path']))){
                                $certificatePath = (strlen($rows['certificate_path'])?base_url($rows['certificate_path']):'#');
                                $btns .= ' <a target="_blank" href="' .$certificatePath. '" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-sm btn-success">Download</a>';
                            }
                            if(isset($rows['form_data']->service_plus_data) && $rows['form_data']->service_plus_data ){
                                $btns = ' <a target="_blank" href="' .base_url("iservices/serviceplus/rtps_track/$obj_id"). '" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-sm btn-success">Track/Download</a>';
                            }
                           
                    }elseif($filter_date['service_id'] === "MARRIAGE_REGISTRATION"){
                        $nestedData["service_name"] =  $rows['service_name'];
                        $nestedData["mobile"] = isset( $rows['applicant_mobile_number']) ? $rows['applicant_mobile_number'] : '';
                        $nestedData["rtps_trans_id"] =  $rows['rtps_trans_id'];
                        $nestedData["app_ref_no"] =  $rows['rtps_trans_id'];
                        $nestedData["status"] =  "DELIVERED";//$rows['status'];
                        $btns = '<a target="_blank" href="' . base_url("spservices/applications/acknowledgement/$obj_id") . '" data-toggle="tooltip" data-placement="top" title="Acknowledgement" class="btn btn-sm btn-primary mb-1" >Acknowledgement</a> ';
                  
                            if(($filter_date['service_id'] === "MARRIAGE_REGISTRATION") && !empty($rows['certificate'])){
                                $certificatePath = (strlen($rows['certificate'])?base_url($rows['certificate']):'#');
                                $btns .= ' <a target="_blank" href="' .$certificatePath. '" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-sm btn-success">Download</a>';
                            }
                            if(isset($rows['form_data']->service_plus_data) && $rows['form_data']->service_plus_data ){
                                $btns = ' <a target="_blank" href="' .base_url("iservices/serviceplus/rtps_track/$obj_id"). '" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-sm btn-primary">Track/Download</a>';
                            }
                    }
                    else{

                        $nestedData["service_name"] =  $rows['service_data']->service_name;
                        $nestedData["mobile"] =  isset($rows['form_data']->mobile) ? $rows['form_data']->mobile : "";
                        $nestedData["rtps_trans_id"] =  $rows['service_data']->appl_ref_no;
                        $nestedData["app_ref_no"] =  $rows['service_data']->appl_ref_no;
                        $nestedData["status"] =  "DELIVERED";//$rows['service_data']->appl_status;

                        if(isset($rows['form_data']->service_plus_data) && $rows['form_data']->service_plus_data ){
                            $btns = ' <a target="_blank" href="' .base_url("iservices/serviceplus/rtps_track/$obj_id"). '" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-sm btn-success">Track/Download</a>';
                        }else{
                            $btns = '<a target="_blank" href="' . base_url("spservices/applications/acknowledgement/$obj_id") . '" data-toggle="tooltip" data-placement="top" title="Acknowledgement" class="btn btn-sm btn-primary mb-1" >Acknowledgement</a> ';
                            if(!empty($rows['form_data']->certificate)){
                                $certificatePath = (strlen($rows['form_data']->certificate)?base_url($rows['form_data']->certificate):'#');
                                $btns .= ' <a target="_blank" href="' .$certificatePath. '" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-sm btn-success">Download1</a>';
                            }
                        }
                       
                        
                    }
                   
                    // $rows[$filter_date['mobile_path']];
                }else{
                    $nestedData["service_name"] =  $rows['service_name'];
                    $nestedData["mobile"] =  $rows['mobile'];
                    $nestedData["rtps_trans_id"] =  $rows['rtps_trans_id'];
                    $nestedData["app_ref_no"] =  isset($rows['app_ref_no']) ? $rows['app_ref_no'] :( isset( $rows['vahan_app_no']) ?  $rows['vahan_app_no'] : '');
                 
                    $nestedData["status"] = "DELIVERED";// $rows['delivery_status'];
                    if($filter_date['portal_no'] === "2"){
                        $btns = '<a target="_blank" href="' . base_url("iservices/v-acknowledgement/". $nestedData["app_ref_no"]) .'" data-toggle="tooltip" data-placement="top" title="Acknowledgement" class="btn btn-sm btn-primary mb-1" >Acknowledgement</a> ';
                    }else{
                        
                        $btns = '<a target="_blank" href="' . base_url("iservices/o-acknowledgement?app_ref_no=". $rows['app_ref_no']) .'" data-toggle="tooltip" data-placement="top" title="Acknowledgement" class="btn btn-sm btn-success mb-1" >Acknowledgement</a> ';
                        if($rows['service_id']  === 242 || $rows['service_id'] ==="242"){
                            $btns .= '<a target="_blank" href="' .base_url("iservices/basundhara/status/check-status?app_ref_no=". $rows['app_ref_no']) .'" data-toggle="tooltip" data-placement="top" title="Track / Download" class="btn btn-sm btn-primary mb-1" >Track & Download</a> ';
                        }
                        if($filter_date['portal_no'] === "1" || $filter_date['portal_no'] === 1 || $filter_date['portal_no'] === "8" || $filter_date['portal_no'] === 8){
                            $btns .= '<a target="_blank" href="' .base_url("iservices/status/check-status?app_ref_no=". $rows['app_ref_no']) .'" data-toggle="tooltip" data-placement="top" title="Track / Download" class="btn btn-sm btn-primary mb-1" >Track & Download</a> ';
                        }
                    }
                  
                }
               
                
              
              
               $nestedData['action']=$btns;
              
                //$nestedData["action"] = $btns;
                $data[] = $nestedData;
                $sl++;
            }
        }
        $json_data = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        echo json_encode($json_data);
    }

    public function list(){
        $data['pageTitle'] = "Transactions";

        $this->load->view('includes/frontend/header');
        // $this->load->view('transactions',$data);
        $this->load->view('mytransactions/list', array());
        $this->load->view('includes/frontend/footer');
    }

    public function switchview($type){
        set_custom_cookie('trans_view', $type);
        if($type == 'old'){
            redirect(base_url('iservices/transactions'));
        }else{
            redirect(base_url('iservices/myapplications/list')); 
        }
    }
    public function get_latest_app_status($data,$pfc_email='')
    {
        // https://rtps.assam.gov.in/spservices/trackallapps/get_records
        // http://localhost/rtps/iservices/api/track/myapplications
        // https://sewasetu.assam.gov.in/tools/rtps_id_labels/src/api/external_apis.php/capi_get_rtps_applications

      $urls=array(
          array('url'=>base_url("spservices/trackallapps/get_records"),
                'headers'=>array('Content-Type: application/json', "Authorization: Bearer 080042cad6356ad5dc0a720c18b53b8e53d4c274"),
                'filter'=>$data
                ),
          array('url'=>base_url('iservices/api/track/myapplications'),
                'headers'=>array(
                                 'Authorization: rtpsapi#!@',
                                 'Content-Type: application/json'),
                'filter'=>$data
        ),
         array('url'=>base_url("tools/rtps_id_labels/src/api/external_apis.php/capi_get_rtps_applications"),
               'headers'=>array('Content-Type: application/json', 'Authorization: Bearer |0VW?z,-w2w"6;{b8v}K6A5+Fdf@l-'),
               'filter'=> array(
                "user_type"=>  $data["user_type"],
                "mobile"=> $data["mobile"],
                "pfc_id"=>$pfc_email,
                "csc_id"=> $data["csc_id"],
                "app_ref_no"=> $data["app_ref_no"],
                "from_date"=> $data["from_date"],
                "end_date"=> $data["end_date"],
                "status"=> $data["status"],
               )
              
         ),
         );

      
        $multi_handle = curl_multi_init();
        $handles = [];

        foreach ($urls as $key => $value) {
            $handle = curl_init();
            curl_setopt_array($handle, array(
                CURLOPT_URL => $value['url'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT_MS => 7000,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,    // disable SSL certificate verification
                CURLOPT_SSL_VERIFYHOST => false,    // disable hostname verification
                CURLOPT_FAILONERROR => true,
                CURLOPT_POSTFIELDS => json_encode($value['filter']),
                CURLOPT_HTTPHEADER => $value['headers'],
            ));

        

            $handles[] = $handle;

            curl_multi_add_handle($multi_handle, $handle);
        }

        // execute the requests in parallel
        do {
            curl_multi_exec($multi_handle, $running);
        } while ($running);


        if (curl_multi_errno($multi_handle)) {
           
            return [
                'status' => false,
                'message' => "error in curl",
            ];
        }
        // retrieve the response data from each handle
        $responses = [];
        foreach ($handles as $handle) {
            $response = curl_multi_getcontent($handle);
            // For only success response
            if (!curl_errno($handle) && curl_getinfo($handle, CURLINFO_HTTP_CODE) <= 300) {
                $responses[] = json_decode($response, true);
            }
        }
       

        // close the handles and multi-handle
        foreach ($handles as $handle) {
            curl_multi_remove_handle($multi_handle, $handle);
            curl_close($handle);
        }
        curl_multi_close($multi_handle);
        // do something with the response data
        // Return the first response data with status true
        $combinedArray=array();
        foreach ($responses as $key => $value) {
            if (!empty($value['status'])) {
                $combinedArray = array_merge_recursive($combinedArray,  $value['data']);
            }
        }
     //   pre(  $combinedArray);
        return $combinedArray;

     
    }
    public function getMyApps(){
        $app_ref_no=$this->input->post('app_ref_no');
        $status=$this->input->post('status');
        $user = $this->session->userdata();
        if(empty($status)){
            $status="P";
        }
        $mobile="";
        $apply_by="";
        if(!empty($this->input->post('from_date')) && !empty($this->input->post('end_date'))){
            $from_date=$this->input->post('from_date');
            $end_date=$this->input->post('end_date');
        }else{
            $from_date="";//date('Y-m-d',strtotime('-29days') );
            $end_date="";//date('Y-m-d');
        }
        $pfc_email='';
        if ( $this->slug === "CSC") {
            $apply_by = $user['userId'];
        } else if($this->slug === "PFC") {
            $apply_by = $this->session->userdata('userId')->{'$id'};
            $pfc_email=$this->pfc->email;
        }else{
            $mobile=$user['mobile'];
        }
        $data=array(
            "user_type"=>$this->slug,"mobile"=> $mobile,"pfc_id"=>$apply_by,"csc_id"=>$apply_by,
            "app_ref_no"=>$app_ref_no,
            "from_date"=> !empty($app_ref_no) ? "": $from_date,
            "end_date"=> !empty($app_ref_no) ? "": $end_date,
            'status'=> !empty($app_ref_no) ? "" : $status
        );
      
      
        $response=$this->get_latest_app_status($data,$pfc_email);

        if($response){
            ?>
            <div  style="font-size: small;font-weight: bold;font-style: italic;padding: 5px;"> <?= $app_ref_no ? "Result showing for application no".$app_ref_no :"Result showing from ".$from_date." to ".$end_date ?></div>
           
             <table class="table" id="example">
                   <thead>
                       <tr>
                           <th>Transaction Id</th>
                           <th>Service Name</th>
                           <th>Application No</th>
                           <th>Initiate Date</th>
                           <th>Submission Date</th>
                           <th>Status</th>
                           <th>Action</th>
                       </tr>
                   </thead>
                   <tbody id="">
             <?php 
            foreach($response as $value){
                ?>
                <tr>
                       <td><?= $value['initiated_data']['rtps_trans_id'] ?></td>
                       <td><?=isset($value['initiated_data']['service_name']) ? $value['initiated_data']['service_name']:'' ?></td>
                       <td><?=isset($value['initiated_data']['appl_ref_no']) ? $value['initiated_data']['appl_ref_no'] : '' ?></td>
                       <td><?=$value['initiated_data']['createdDtm']?></td>
                       <td><?=isset($value['initiated_data']['submission_date'])? $value['initiated_data']['submission_date']: ""?></td>
                       <td><?=isset($value['initiated_data']['status'])? $value['initiated_data']['status']: ""?></td>
                       <td>
                           <?php if( is_array($value['action_data'])){
                               foreach($value['action_data'] as $btn){
                                   $cl="btn btn-primary btn-sm mbtn";
                                   switch($btn['name']){
                                       case 'Complete Payment':
                                            $cl="btn btn-warning btn-sm mbtn";
                                        break;
                                        case 'Track status':
                                            $cl="btn btn-success btn-sm mbtn";
                                        break;
                                        case 'Acknowledgement':
                                            $cl="btn btn-success btn-sm mbtn";
                                        break; 
                                        case 'Preview':
                                            $cl="btn btn-primary btn-sm mbtn";
                                        break; 
                                        case 'Verify Payment':
                                            $cl="btn btn-warning btn-sm mbtn";
                                        break; 
                                        case 'Make query payment':
                                            $cl="btn btn-warning btn-sm mbtn";
                                        break;
                                        case 'Detail':
                                            $cl="btn btn-primary btn-sm mbtn";
                                        break;
                                        default : 
                                           $cl="btn btn-primary btn-sm mbtn";
                                       break;
                                   }
                                   ?>
                                      <a target="_blank" class='<?=$cl?>' href="<?=$btn['url']?>" ><?=$btn['name']?></a>
                                   <?php 
                               }
                           } ?>
                      
                       </td>

                <?php 
            }
            ?>
             </tbody>
                 </table>
                 <br/>
                 <br/>
                 <br/>
                 <br/>

            <?php
        }else{ ?>
         <div  style="font-size: small;font-weight: bold;font-style: italic;padding: 5px;"> <?= $app_ref_no ? "Result showing for application no".$app_ref_no : "Result showing from ".$from_date." to ".$end_date ?></div>
         <div  style="font-size: small;font-weight: bold;font-style: italic;padding: 5px;"><?=$app_ref_no ? "No Records Found with ref no.".$app_ref_no :"No Records Found"?></div>
                 <br/>
                 <br/>
                 <br/>
                 <br/>
        <?php }



    }
}