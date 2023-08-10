<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Official_details extends Rtps
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('official_details_model');
        $this->load->model('users_model');
        $this->load->model('department_model');
        $this->load->model('services_model');
        $this->load->model('location_model');
        $role = $this->session->userdata("role");
        if (!in_array($role->slug,['SA','ADMIN'])) {
            redirect(base_url("appeal/login/logout"));
        }
    }
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $this->load->model('roles_model');
        $this->load->model('commission_model');
        $activeCommission = $this->commission_model->get_active_commissionar_name();
        $roleWiseUserList = $this->roles_model->get_role_wise_user_list(['slug' => ['$in' => ['DPS', 'AA', 'RA','DA','RR']]]);
        $departmentList = $this->department_model->get_department_list();
        // $locationList = $this->location_model->all();
        $data = [
            'roleWiseUserList' => $roleWiseUserList,
            'departmentList' => $departmentList,
            // 'locationList' => $locationList,
            'reviewing_authority'=>!empty($activeCommission->reviewing_authority_user->name) ? $activeCommission->reviewing_authority_user->name : "",
            'registrar_user'=>!empty($activeCommission->registrar_user->name) ? $activeCommission->registrar_user->name : "",
        ];


        // print_r(json_decode(json_encode($data), true));

        // $datas = $this->official_details_model->official_details_info('6374a6f3f71ff8cef30877c1');
        // print_r($datas);


        
       
        $this->load->view('includes/header');
        $this->load->view('official_details/index', $data);
        $this->load->view('includes/footer');
    }
    /**
     * get_service_list_by_department_id
     *
     * @param mixed $dept_id
     * @return void
     */
    public function get_service_list_by_department_id($dept_obj_id)
    {
        if (isset($dept_obj_id)) {
            $department = $this->department_model->get_by_doc_id(new ObjectId($dept_obj_id));
           
            $base_department_id=property_exists($department,'base_department_id') ? $department->base_department_id: false;
            $serviceList = $this->department_model->get_service_list_by_department_id($department->department_id);
            // pre($base_department_id);
            // $locationList = $this->location_model->get_rows(array('department_id'=>$department->department_id));
            $locationList = $this->location_model->getLocationByDept($department->department_id,$base_department_id);
           
            $serviceListArray = array();
            $counter = 0;
            foreach ($serviceList as $application) {
                if (isset($application->service_id)) {
                    $serviceListArray[$counter]['id'] = $application->{'_id'}->{'$id'};
                }
                if (isset($application->service_name)) {
                    $serviceListArray[$counter]['text'] = $application->service_name;
                }
                $counter++;
            }
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array('success' => true, 'serviceList' => $serviceListArray,'locationList'=>$locationList)));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode(array('success' => false)));
        }
    }
    /**
     * get_records
     *
     * @return void
     */
    public function get_records()
    {
        $columns = array(
            "0" => "sl_no",
            "1" => "service_name",
            "2" => "location_id",
            "3" => "da_array",
            "4" => "appellate_auth",
            "5" => "dps_name",
            "6" => "da_tribunal_array",
            "7" => "registrar",
            "8" => "reviewing_auth",
            "9" => "action"

        );
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->official_details_model->total_rows();
        $totalFiltered = $totalData;
        if (empty($this->input->post("search")["value"])) {
            $records = $this->official_details_model->official_details_with_related($limit, $start,$order, $dir);
//            $records = $this->official_details_model->official_details_filter_agregate($limit, $start,$order, $dir);
            $totalFiltered = $this->official_details_model->official_details_filter_count();
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->official_details_model->official_details_search_rows_with_related($limit, $start, $search, $order, $dir);
//            $records = $this->official_details_model->official_details_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->official_details_model->official_details_tot_search_rows($search);
        }
        $data = array();
        $locationList = $this->location_model->all();
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                $objId = $rows->{"_id"}->{'$id'};
                $nestedData["sl_no"] = $sl;
                $da_array = [];
                $da_tribunal_array = [];
                $rr_array = [];
                foreach ($rows->da_array as $da){
                    $da_array[] = $da->name;
                }
                foreach ($rows->da_tribunal_array as $da){
                    $da_tribunal_array[] = $da->name;
                }
                if(isset($rows->registrar_array)){
                  foreach ($rows->registrar_array as $rr){
                      $rr_array[] = $rr->name;
                  }
                }
                $btns = '<a data-id="' . $objId . '" href="#!" data-toggle="tooltip" data-placement="top" title="Edit" class="editOfficialMapping"><span class="fa fa-edit" aria-hidden="true"></span></a>';
                $nestedData["da_array"] = implode(', ',$da_array);
                $nestedData["dps_name"] = $rows->dps_details->name;
                $nestedData["appellate_auth"] = $rows->appellate_details->name;
                $nestedData["reviewing_auth"] = property_exists($rows,"reviewing_details") ? $rows->reviewing_details->name : "";
                $nestedData["registrar"] =  implode(', ',$rr_array);
                $nestedData["da_tribunal_array"] =  implode(', ',$da_tribunal_array);
                $nestedData["service_name"] = $rows->service->service_name;
                $nestedData['location_id'] = $rows->location->location_name;
                $nestedData["action"] = $btns;
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
    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
       }else{
           return false;
       }
    }
    private function errorResponse($mgs=''){
        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
            'success' => false,
            'error_msg' => $mgs ? $mgs : "Invalid Data"
        )));
    }
    
    public function create() {
        $this->load->library('form_validation');
        $this->lang->load('appeal');
        $this->form_validation->set_rules('dept_id', 'Department', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('service_ids[]', 'Service', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('dps_id', 'DPS Name', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('location_id', 'Location ID', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('appellate_id', 'Appellate Authority', 'trim|required|alpha_numeric');
//        $this->form_validation->set_rules('reviewing_id', $this->lang->line('reviewing_authority_label'), 'trim|required');
        $this->form_validation->set_rules('dealing_assistant[]', 'Appellate Dealing Assistant', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('dealing_assistant_tribunal[]', 'Tribunal Dealing Assistant', 'trim|required|alpha_numeric');
        $this->form_validation->set_error_delimiters('<span class="text-white">', '</span>');
        if ($this->form_validation->run() == false) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors()
            )));
        } else {
            $counter_insert = 0;
            $counter_update = 0;
            $service_ids = $this->input->post('service_ids');
            if (is_array($service_ids) && count($service_ids)) {
                foreach ($service_ids as $service_id) {
                    $officialFilter = array(
                        'service_id' => $this->checkObjectId($service_id) ? new ObjectId($service_id) : "",
                        'location_id' => $this->checkObjectId($this->input->post('location_id')) ? new ObjectId($this->input->post('location_id')) : ""
                    );
                    //      $this->load->model('official_details');
                    $officialDetails = $this->official_details_model->first_where($officialFilter);
                    $da_id_array = [];
                    $da_id_tribunal_array = [];
                    $rr_id_array = [];
                    foreach ($this->input->post('dealing_assistant') as $dealingAssistant) {
                        if ($this->checkObjectId($dealingAssistant)) {
                            $da_id_array[] = new ObjectId($dealingAssistant);
                        } else {
                            return $this->errorResponse();
                            break;
                        }
                    }
                    foreach ($this->input->post('dealing_assistant_tribunal') as $dealingAssistant) {
                        if ($this->checkObjectId($dealingAssistant)) {
                            $da_id_tribunal_array[] = new ObjectId($dealingAssistant);
                        } else {
                            return $this->errorResponse();
                            break;
                        }
                    }
                    $this->load->model('commission_model');
                    $activeCommission = $this->commission_model->first_where([]);

                    if (!empty($activeCommission)) {
                        $rr_id_array[] = new ObjectId(strval($activeCommission->registrar));
                        $reviewing_id = new ObjectId(strval($activeCommission->reviewing_authority));
                    } else {
                        return $this->output
                                        ->set_content_type('application/json')
                                        ->set_status_header(200)
                                        ->set_output(json_encode(array(
                                            'success' => false,
                                            'error_msg' => 'Commission not found!!! Please add Chairman and Registrar in commission to proceed.'
                        )));
                    }

                    //check valid dept and service
                    $check_service_exits = $this->official_details_model->check_service_exits($service_id);
                    if (empty($check_service_exits)) {
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array(
                                'success' => false,
                                'error_msg' => "Invalid Service"
                        )));
                    }
                    $check_service_exits = $this->official_details_model->check_dept_exits($this->input->post('dept_id'));
                    if (empty($check_service_exits)) {
                        return $this->errorResponse("Invalid Department");
                    }
                    if (!$this->checkObjectId($this->input->post('dps_id'))) {
                        return $this->errorResponse("Invalid DPS");
                    }
                    if (!$this->checkObjectId($this->input->post('appellate_id'))) {
                        return $this->errorResponse("Invalid Appellate Authority");
                    }
                    if (!$this->checkObjectId($this->input->post('location_id'))) {
                        return $this->errorResponse("Invalid Location Id");
                    }

                    $data = array(
                        "dept_id" => new ObjectId($this->input->post("dept_id", TRUE)),
                        "service_id" => new ObjectId($service_id),
                        "location_id" => new ObjectId($this->input->post("location_id", TRUE)),
                        "dps_id" => new ObjectId($this->input->post("dps_id", TRUE)),
                        "appellate_id" => new ObjectId($this->input->post("appellate_id", TRUE)),
                        "reviewing_id" => $reviewing_id,
                        "da_id_array" => $da_id_array,
                        "da_id_tribunal_array" => $da_id_tribunal_array,
                        "registrar_id_array" => $rr_id_array,
                        'created_at' => new MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y H:i')) * 1000),
                            //'created_by' => $this->session->userdata('userId')
                    );
                    $this->official_details_model->set_collection("official_details");

                    if (!empty((array) $officialDetails)) {
                        $this->process_users_update($officialDetails, $data);
                        $this->official_details_model->update_where(['_id' => new ObjectId($officialDetails->{'_id'}->{'$id'})], $data);
                        $activity_description = "Update existing Official Mapping";
                        $counter_update++;
                    } else {
                        $this->official_details_model->insert($data);
                        $activity_description = "New Official Mapping added";
                        $counter_insert++;
                    }

                    //For activity logs
                    $this->load->model('useractivities_model');
                    $backupRow = (array) $officialDetails;
                    unset($backupRow["_id"]);
                    $activity_data = array(
                        'user_id' => $this->session->userId->{'$id'},
                        'activity_title' => 'Official Mapping',
                        'activity_description' => $activity_description,
                        'activity_type' => 1, //1 for insert new 2 for update
                        'data_before_update' => $backupRow,
                        'data_after_update' => $data,
                        'activity_time' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                    ); //pre($activity_data);
                    $this->useractivities_model->insert($activity_data);
                }//End of foreach()
                //die("New records added : ".$counter_insert.", Updated existing records : ".$counter_update);
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array(
                        'success' => true,
                        'error_msg' => "New records added : ".$counter_insert.", Updated existing records : ".$counter_update
                )));
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(array(
                        'success' => false,
                        'error_msg' => 'Service cannot be empty'
                )));
            }//End of if else
        }//End of if else
    }//End of create()
        
    public function process_users_update($OldOfficeDetails, $data=array()) { //pre($data);
        $tot = 0;
        if(count($data) == 0) {
            die('data cannot be empty');
        }
        $appellate_id = $data["appellate_id"];
        $dps_id = $data["dps_id"];
        $da_id_array = $data["da_id_array"];
        $daTotNew = count($da_id_array);        
        $da_id_tribunal_array = $data["da_id_tribunal_array"];
        $daTribunalTotNew = count($da_id_array);
        
        $ra_id = isset($data["reviewing_id"])?$data["reviewing_id"]:$OldOfficeDetails->reviewing_id;
        $rr_id = isset($data["registrar_id_array"][0])?$data["registrar_id_array"][0]:$OldOfficeDetails->registrar_id_array[0]; //die($ra_id.", ".$rr_id);
        $filter = array(
            'service_id' => isset($data["service_id"])?$data["service_id"]:$OldOfficeDetails->service_id,
            'location_id' => isset($data["location_id"])?$data["location_id"]:$OldOfficeDetails->location_id
        );
        $this->load->model('appeal_application_model');
        $appeals = $this->appeal_application_model->get_rows($filter);
        //pre($appeals);
        if($appeals) {
            foreach ($appeals as $appeal) {    
                $objId = $appeal->_id->{'$id'};
                if($appeal->process_users){
                    $process_users = array();
                    $da_action = array();
                    $is_da_active = false;
                    $da_tribunal_action = array();
                    $is_da_tribunal_active = false;
                    foreach ($appeal->process_users as $key=>$appealUser){   
                        //echo $appeal->applicant_name.'. '.$appealUser->role_slug.' : '.$appealUser->userId.' => '.$appellate_id.'<br>';
                        if($appealUser->role_slug === 'AA') {
                            $process_users[] = array(
                                "userId" => $appellate_id,
                                "action" => $appealUser->action,
                                "role_slug" => $appealUser->role_slug,
                                "active" => $appealUser->active
                            );
                        } elseif($appealUser->role_slug === 'DPS') {
                            $process_users[] = array(
                                "userId" => $dps_id,
                                "action" => $appealUser->action,
                                "role_slug" => $appealUser->role_slug,
                                "active" => $appealUser->active
                            );
                        } elseif($appealUser->role_slug === 'RA') {
                            $process_users[] = array(
                                "userId" => $ra_id,
                                "action" => $appealUser->action,
                                "role_slug" => $appealUser->role_slug,
                                "active" => $appealUser->active
                            );
                        } elseif($appealUser->role_slug === 'RR') {
                            $process_users[] = array(
                                "userId" => $rr_id,
                                "action" => $appealUser->action,
                                "role_slug" => $appealUser->role_slug,
                                "active" => $appealUser->active
                            );
                        } elseif($appealUser->role_slug === 'DA') {
                            if($appealUser->active) {
                                $is_da_active = true;
                                $is_da_tribunal_active = true;
                            }
                            $da_action = $appealUser->action;
                            $da_tribunal_action = $appealUser->action;                            
                        } else {
                            //die('role_slug does not matched');
                            $process_users[] = array(
                                "userId" => $appealUser->userId,
                                "action" => $appealUser->action,
                                "role_slug" => $appealUser->role_slug,
                                "active" => $appealUser->active
                            );
                        }//End of if else
                    }//End of foreach()
                    
                    if(($daTotNew>0) && ($appeal->appeal_type == 1)) {
                        for($i=0; $i < $daTotNew; $i++) {
                            $process_users[] = array(
                                "userId" => $da_id_array[$i],
                                "action" => $da_action,
                                "role_slug" => "DA",
                                "active" => $is_da_active
                            );
                        }
                    }//End of if
                    
                    if(($daTribunalTotNew > 0) && ($appeal->appeal_type == 2)) {
                        for($i=0; $i < $daTribunalTotNew; $i++) {
                            $process_users[] = array(
                                "userId" => $da_id_tribunal_array[$i],
                                "action" => $da_tribunal_action,
                                "role_slug" => "DA",
                                "active" => $is_da_tribunal_active
                            );
                        }
                    }//End of if
                    
                    $this->appeal_application_model->update_where(array('_id' => new ObjectId($objId)), array('process_users' => $process_users));
                    //echo "<pre>"; var_dump($process_users); echo "<pre>"; echo '<br><br><br>';
                    $tot++;
                }//End of if
            }//End of foreach()
        } else {
            //die('Appeal does not exists against the selected service and location');
        }//End of if else
        return $tot;
    }//End of process_users_update()
    
    private function mapping_correction(){
        $this->load->model('commission_model');
        $rr_id_array = [];
        $activeCommission = (array)$this->commission_model->get_all(array());
        $chairman=strval($activeCommission[0]->reviewing_authority);
        $rr_id_array[] = new ObjectId(strval($activeCommission[0]->registrar));
            $data= $this->official_details_model->get_all(array());
            $data=(array)$data;
            foreach($data as $mapped){
               $this->modify_map($mapped->_id->{'$id'}, $rr_id_array,$chairman);
            }
            //for
    }
    private function modify_map($obj_id,$rr_id_array,$chairman){
        $data_to_update=array(
            'reviewing_id'=>new ObjectId($chairman),
            'registrar_id_array'=> $rr_id_array

        );
        $this->mongo_db->set($data_to_update);
        $this->mongo_db->where("_id",new ObjectId($obj_id));
        return  $this->mongo_db->update("official_details");
    }

    // Edit view
    public function get_official_mapping_info()
    {

        $doc_id=$this->input->post("official_mapping_id");

        $dept_id = $this->official_details_model->get_by_doc_id($doc_id);
        $dept_id = json_decode(json_encode($dept_id), true);
        $dept_id = $dept_id['dept_id']['$oid'];

        $service_id = $this->official_details_model->get_by_doc_id($doc_id);
        $service_id = json_decode(json_encode($service_id), true);
        $service_id = $service_id['service_id']['$oid'];

        $location_id = $this->official_details_model->get_by_doc_id($doc_id);
        $location_id = json_decode(json_encode($location_id), true);
        $location_id = $location_id['location_id']['$oid'];
        
        // $department_name = $this->department_model->get_department_name($doc_id);

        // return $department_name;
    
        // $data = $this->official_details_model->official_details_info($doc_id);
        // print_r($data);
        $json_data=array(
            "status"=>true,
            "data"=>$this->official_details_model->get_by_doc_id($doc_id),
            "dept"=>$this->department_model->get_department_name($dept_id),
            "service"=>$this->services_model->get_service_name($service_id),
            "location"=>$this->location_model->get_location_name($location_id),

        );
        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($json_data)); 


    }

    public function update(){

        $edit_da_id_array = [];

        if($this->input->post('edit_dealing_assistant')) {
            foreach ($this->input->post('edit_dealing_assistant') as $dealingAssistant){
                if($this->checkObjectId($dealingAssistant)){
                    $edit_da_id_array[] = new ObjectId($dealingAssistant);
                }else{
                    return $this->errorResponse();
                     break;
                }

            }
        }


        $edit_da_id_tribunal_array = [];
        if($this->input->post('edit_dealing_assistant_tribunal')) {
            foreach ($this->input->post('edit_dealing_assistant_tribunal') as $ta){
            if($this->checkObjectId($ta)){
                $edit_da_id_tribunal_array[] = new ObjectId($ta);
            }else{
                return $this->errorResponse();
            break;
            }            
        }
        }
        

        $data1 = array(
            "dps_id" => new ObjectId($this->input->post("edit_dps_id")),
            "appellate_id" => new ObjectId($this->input->post("edit_appellate_id")),
            "da_id_array" => $edit_da_id_array,
            "da_id_tribunal_array" => $edit_da_id_tribunal_array,
        );

        // return $data1;
        $officialDetails = $this->official_details_model->first_where(['_id' => new ObjectId($this->input->post("official_details_id"))]);
        $this->process_users_update($officialDetails, $data1);
        $update = $this->official_details_model->update_where(['_id' => new ObjectId($this->input->post("official_details_id"))], $data1);

            
        //For activity logs 
        $this->load->model('useractivities_model');
        $backupRow = (array)$officialDetails;
        unset($backupRow["_id"]);
        $activity_data = array(
            'user_id' => $this->session->userId->{'$id'},
            'activity_title' => 'Official Mapping',
            'activity_description' => 'Updating existing Office Mapping',
            'activity_type' => 2, //1 for insert new 2 for update
            'data_before_update' => $backupRow,
            'data_after_update' => $data1,
            'activity_time' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
        );//pre($activity_data);
        $this->useractivities_model->insert($activity_data);
            
         return $this->output
         ->set_content_type('application/json')
         ->set_status_header(200)
         ->set_output(json_encode(array(
             'success' => true,
             'error_msg' => ""
         )));
         
    }

}
