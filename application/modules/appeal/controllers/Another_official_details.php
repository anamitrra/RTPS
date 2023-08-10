<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Another_official_details extends Rtps
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
        $this->load->model('official_details_model_another');
        $this->load->model('users_model');
        $this->load->model('services_model');
        $this->load->model('department_model');
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

        


        $official_data = $this->official_details_model_another->get_official_data();
        $official_data = json_decode(json_encode($official_data), true);

        $totalData = count(($official_data));
    

        if($totalData >= 1){
   
        $office_data = $this->department_model->get_department_id_by_service_id($official_data[0]['service_id']['$oid']);



        $office_data = $this->department_model->get_department_id_by_service_id($official_data[0]['service_id']['$oid']);


        $official_detailss = [];

        $services_names = [];
        $department_names = [];

        $officee_data = [];
        

        $length = count($official_data);
        
            for ($i = 0; $i < $length; $i++) {


            $official_dataa = $this->department_model->get_department_id_by_service_id($official_data[$i]['service_id']['$oid']);

            
            $location_name = $this->location_model->get_location_name($official_data[$i]['location_id']['$oid']);

            $ids = ($official_data[$i]['_id']['$id']);

            $is_draft = ($official_data[$i]['is_draft']);


            array_push ( $official_detailss, ['department_names'=> $official_dataa, 'location'=> $location_name,'ids'=>$ids,'is_draft'=>$is_draft]);

            $official_detailss = json_decode(json_encode($official_detailss), true);



        }

              
            $data['official_details']= $official_detailss;

    
       
        $this->load->view('includes/header');
        $this->load->view('another_official_details/index',$data);
        $this->load->view('includes/footer');
        }
        else{
            $data['official_details']= [];
        $this->load->view('includes/header');
        $this->load->view('another_official_details/index',$data);
        $this->load->view('includes/footer');
        }

        return;


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
            '_id'
        );
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->official_details_model_another->total_rows();
        $totalFiltered = $totalData;
        if (empty($this->input->post("search")["value"])) {
            $records = $this->official_details_model_another->official_details_with_related($limit, $start,$order, $dir);
//            $records = $this->official_details_model->official_details_filter_agregate($limit, $start,$order, $dir);
            $totalFiltered = $this->official_details_model_another->official_details_filter_count();
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->official_details_model_another->official_details_search_rows_with_related($limit, $start, $search, $order, $dir);
//            $records = $this->official_details_model->official_details_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->official_details_model_another->official_details_tot_search_rows($search);
        }
        $data = array();
        $locationList = $this->location_model->all();
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
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

                $nestedData["da_array"] = implode(', ',$da_array);
                $nestedData["dps_name"] = $rows->dps_details->name;
                $nestedData["appellate_auth"] = $rows->appellate_details->name;
                $nestedData["reviewing_auth"] = property_exists($rows,"reviewing_details") ? $rows->reviewing_details->name : "";
                $nestedData["registrar"] =  implode(', ',$rr_array);
                $nestedData["da_tribunal_array"] =  implode(', ',$da_tribunal_array);
                $nestedData["service_name"] = $rows->service->service_name;
                $nestedData['location_id'] = $rows->location->location_name;
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
    public function create()
    {

        $this->load->library('form_validation');
        $this->lang->load('appeal');
        $this->form_validation->set_rules('dept_id', 'Department', 'trim|required|alpha_numeric');

        $this->form_validation->set_rules('service_id[]', 'Service', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('location_id', 'Location ID', 'trim|required|alpha_numeric');

        $this->form_validation->set_error_delimiters('<span class="text-white">', '</span>');
        if ($this->form_validation->run() == false) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => validation_errors()
                )));
        } else {


            foreach($this->input->post("service_id") as $ser){

            
            $da_id_array = [];
            $da_id_tribunal_array = [];
            $rr_id_array = [];
            $service_array=[];

            $this->load->model('commission_model');
            $activeCommission = $this->commission_model->first_where([]);

            if(!empty($activeCommission)){
                $rr_id_array[] = new ObjectId(strval($activeCommission->registrar));
                $reviewing_id = new ObjectId(strval($activeCommission->reviewing_authority));
            }else{
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array(
                        'success' => false,
                        'error_msg' => 'Commission not found!!! Please add Chairman and Registrar in commission to proceed.'
                    )));
            }

            //check valid dept and service
            $check_service_exits =$this->official_details_model->check_service_exits($ser);
            if(empty($check_service_exits)){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => false,
                    'error_msg' => "Invalid Service"
                )));
            }
            $check_service_exits =$this->official_details_model->check_dept_exits($this->input->post('dept_id'));
            if(empty($check_service_exits)){
                return $this->errorResponse("Invalid Department");
              
            }
 
          if(!$this->checkObjectId($this->input->post('location_id'))){
            return $this->errorResponse("Invalid Location Id");
            
          }
          
            $data = array(
                "dept_id" => new ObjectId($this->input->post("dept_id", TRUE)),

                "service_id" => new ObjectId($ser),

                "location_id" => new ObjectId($this->input->post("location_id", TRUE)),
                "is_draft" => 1,
     
                'created_at' => new MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y H:i')) * 1000),
            );
            $this->official_details_model->set_collection("official_details_another");

   

            $this->official_details_model->insert($data);



        }
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => true,
                    'error_msg' => ""
                )));
        }
    }

public function final_submit(){

    $this->load->library('form_validation');
    $this->lang->load('appeal');
    $this->form_validation->set_rules('service_idd', 'Service', 'trim|required|alpha_numeric');
    $this->form_validation->set_rules('da_tribunal', 'Tribunal Dealing Assistant', 'trim|required|alpha_numeric');
    $this->form_validation->set_rules('dps_id', 'DPS Name', 'trim|required|alpha_numeric');
    $this->form_validation->set_rules('app_auth', '', 'trim|required|alpha_numeric');
   
    $this->form_validation->set_rules(' da_app_auth', '', 'trim|required|alpha_numeric');
    $this->form_validation->set_rules('appellate_id', 'Appellate Authority', 'trim|required|alpha_numeric');
    $this->form_validation->set_rules('loc_id', 'Location ID', 'trim|required|alpha_numeric');




    $service_id = ($this->input->post('service_idd'));
    $location_id = ($this->input->post('location_idd'));
    $da_id_array[] = new ObjectId($this->input->post('da_app_auth'));
    $appellate_id = ($this->input->post('app_auth'));
    $dps_id = ($this->input->post('dps_id'));
    $da_id_tribunal_array[] = new ObjectId($this->input->post('da_tribunal'));

    
    
    $registrar_id = $this->users_model->get_by_name('Registrar One');
    $registrar_id = json_decode(json_encode($registrar_id), true);
    $registrar_id = $registrar_id[0]['_id']['$id'];

    $registrar_id_array[] = new ObjectId($registrar_id);


    $reviewing_id = $this->users_model->get_by_name('Chairman 1');
    $reviewing_id = json_decode(json_encode($reviewing_id), true);
    $reviewing_id = $reviewing_id[0]['_id']['$id'];

    $reviewing_id_array[] = new ObjectId($reviewing_id);



        // Department id
        $dept_id = $this->services_model->get_service_name($service_id);
        $dept_id = json_decode(json_encode($dept_id), true);
        $dept_id = ($dept_id[0]['department_id']);

        $dept_id = $this->department_model->get_department_doc_id_by_id($dept_id);
        $dept_id = json_decode(json_encode($dept_id), true);
        // print_r($dept_id[0]['_id']['$id']);
        $dept_id = ($dept_id[0]['_id']['$id']);


        
        $officialFilter = array(
            'service_id' =>$this->checkObjectId($this->input->post('service_idd')) ?  new ObjectId($this->input->post('service_idd')) : "",
            'location_id' =>$this->checkObjectId($this->input->post('location_idd')) ?  new ObjectId($this->input->post('location_idd')) : ""
        );
        //      $this->load->model('official_details');
        $officialDetails = $this->official_details_model->first_where($officialFilter);





            $data = array(
                         "service_id" => new ObjectId($service_id),
                         "dept_id" => new ObjectId($dept_id),
                         "da_id_tribunal_array" => $da_id_tribunal_array,
                        "dps_id" => new ObjectId($dps_id),
                        "appellate_id" => new ObjectId($appellate_id),
                        "reviewing_id" =>$reviewing_id_array,
                        "location_id"=> new ObjectId($location_id),
                        "da_id_array" => $da_id_array,
                        "registrar_id_array" => $registrar_id_array,
                        // "is_draft" => 0,
                        'created_at' => new MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y H:i')) * 1000),
                        'created_by' =>  new ObjectId($this->session->userdata('userId')->{'$id'})
                    );

                    // print_r($data);
                    // return;
                    $this->official_details_model->set_collection("official_details");



                    
            if (!empty((array)$officialDetails)) {
                // echo "Yes";
                $update = $this->official_details_model->update_where(['_id' => new ObjectId($officialDetails->{'_id'}->{'$id'})],$data);
               
                    $data1 = array(
                        "is_draft" => 0
                    );
    
                     $this->official_details_model_another->update_where(['_id' => new ObjectId($this->input->post('draft_id'))], $data1);
    
                
              
                 
                $this->session->set_flashdata('draft_submit','draft has been successfully submitted');
                redirect(base_url('appeal/officials_draft'));

            }else{
                // echo "No";
                $inserted = $this->official_details_model->insert($data);

                if($inserted){
             

                    $data1 = array(
                       "is_draft" => 0
                   );

                    $update = $this->official_details_model_another->update_where(['_id' => new ObjectId($this->input->post('draft_id'))], $data1);

                  
                }

                $this->session->set_flashdata('draft_submit','draft has been successfully submitted');
                redirect(base_url('appeal/officials_draft'));
            }
        
           
                
            
}


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
}
