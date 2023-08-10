<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Basundhara extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('intermediator_model');
        $this->load->model('portals_model');
        $this->config->load('rtps_services');
        $this->load->library('AES');
        $this->encryption_key=$this->config->item("encryption_key");
        if(!empty($this->session->userdata('role'))){
          $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        }else{
          $this->slug = "user";
        }
    

    }

    public function guidelines($service_id=null,$portal_no=null)
    {
      check_application_count_for_citizen();
      if(empty($service_id) || empty($portal_no)){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'response_data' => "Invalid service",

            )));
            return;
      }
      $data=array("pageTitle" => "Guidelines");
      $data['service_id']=$service_id;
      $data['portal_no']=$portal_no;

      $guidelines=$this->portals_model->get_guidelines($service_id);
      if (property_exists($guidelines, "status") && !$guidelines->status) {
        exit("Service Under Maintenance");
        return;
      }
      if($guidelines){
        $data['service_name']=$guidelines->service_name;
        $data['guidelines']=isset($guidelines->guidelines) ? $guidelines->guidelines : array();
        $data['url']=isset($guidelines->url) ? $guidelines->url : '';
        if(!empty($this->session->userdata('role')) && ($this->slug === "SA" || $this->slug === "PFC" || $this->slug === "CSC")){
          $data['apply_by']="PFC";
          $this->load->view('includes/header');
          $this->load->view('basundhara/admin_guidelines',$data);
          $this->load->view('includes/footer');
        }else{
          $this->load->view('includes/frontend/header');
          $this->load->view('basundhara/guidelines',$data);
          $this->load->view('includes/frontend/footer');
        }
       
      }else {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'response_data' => "Invalid service",

            )));
            return;
      }


    }
    
    public function acknowledgement(){

      if(empty($_GET['app_ref_no'])){
        redirect(base_url("iservices/transactions"));
      }

      $app_ref_no=$_GET['app_ref_no'];
      $application_details=$this->intermediator_model->get_application_details(array("app_ref_no"=>$app_ref_no));
      // var_dump($application_details);die;
      if($application_details->service_id)
        $departmental_data=$this->portals_model->get_departmental_data($application_details->service_id);
      else
        redirect('iservices/transactions');
      $data=array();
      $data['response']=$application_details;
      $data['timeline_days']=$departmental_data->timeline_days;
      $data['department_name']=$departmental_data->department_name;
      $data['service_name']=$departmental_data->service_name;

      $this->load->view('includes/frontend/header');
      $this->load->view('noc_ack1',$data);
      $this->load->view('includes/frontend/footer');

    }

    function generateRandomString($length = 7) {
            $number = '';
            for ($i = 0; $i < $length; $i++){
                $number .= rand(0,9);
            }
            return (int)$number;
    }

    public function generate_id(){
      $date=date('ydm');
      $str="AS".$date."A".$this->generateRandomString(7);
      return $str;
    }
    public function retry($rtps_trans_id,$service_id,$portal_no){
      if($rtps_trans_id && $service_id){
        $guidelines=$this->portals_model->get_guidelines($service_id);
        $external_service_id=property_exists($guidelines,"external_service_id") ? $guidelines->external_service_id : $guidelines->service_id;
        $transaction_data=$this->intermediator_model->get_by_rtps_id($rtps_trans_id);
        $target_url=isset($guidelines->url) ? $guidelines->url : '';
        $user_type= property_exists($transaction_data,'applied_by') ? "PFC":"CITIZEN";
        $input_array=array(
            "rtps_trans_id"=>$rtps_trans_id,
            "user_id"=>$transaction_data->user_id,
            "mobile"=>$transaction_data->mobile,
            "service_id"=>intval($external_service_id),
            "portal_no"=>$portal_no,
            "process"=>"I",
            "user_type"=>$user_type,
            "response_url"=>base_url("iservices/get/basundhara/response")
        );
        $input_array=json_encode($input_array);
        $aes = new AES($input_array, $this->encryption_key);
        $enc = $aes->encrypt();
        $data=array(
          "data"=>$enc,
          "action"=>$target_url
        );
      //  $url=$target_url."?data=".urlencode($enc);
        // redirect($url);
        $this->load->view("retry",$data);
      }else {
        redirect(base_url("iservices/transactions"));
      }


    }
    public function procced(){
      $service_id=$this->input->post('service_id');
      $service_name=$this->input->post('service_name');
      $portal_no=$this->input->post('portal_no');
      $target_url=$this->input->post('url');
      $user=$this->session->userdata();

      $service_details=$this->portals_model->get_guidelines($service_id);
      if(empty($service_details)){
        return false;
      }
     
      $external_service_id=property_exists($service_details,"external_service_id") ? $service_details->external_service_id : $service_details->service_id;
     
      $date=date('ydm');
      $rtps_trans_id=$this->generate_id();
      A1:
      if($this->intermediator_model->is_exist_transaction_no($rtps_trans_id)){
        $rtps_trans_id=$this->generate_id();
        goto A1;
      }


      $input_array=array(
          "rtps_trans_id"=>$rtps_trans_id,
          "user_id"=>$user['userId']->{'$id'},
          "mobile"=>$this->session->userdata("mobile"),
          "service_id"=>intval($external_service_id),
          "portal_no"=>$portal_no,
          "process"=>"N",
          "user_type"=>"CITIZEN",
          "response_url"=>base_url("iservices/get/basundhara/response")
      );
      $input_array=json_encode($input_array);
      $aes = new AES($input_array, $this->encryption_key);
      $enc = $aes->encrypt();
      // $url=base_url()."request/create?data=".urlencode($enc);//var_dump($url);die;
      $url=$target_url."?data=".urlencode($enc);
      //AS200226V0313962/AS
      // 9x5CCz4G5GnxwULPdHXcPmkH9Xdg9KjENTl9OuKf3tk
      $user_data=array(
        'user_id'=>$user['userId']->{'$id'},
        'mobile'=>$user['mobile'],
        'rtps_trans_id'=>$rtps_trans_id,
        "service_name"=>$service_name,
        "portal_no"=>$portal_no,
        "service_id"=>intval($service_id),
        "external_service_id"=>$external_service_id,
        'target_url'=>$url,
        'return_url'=>base_url("iservices/get/basundhara/response"),
        "status"=>"",
        "rtps_con_fee_required"=>true,
        "createdDtm"=> new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
      );
      $res=$this->intermediator_model->insert($user_data);
      $status=array();
      if($res){
          $status["status"] = true;
          $status["url"] = $url;
          $status["encrypted_data"] = $enc;
          $status["message"] = "Need to redirect to third party urls";
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
      }else {
          $status["status"] = false;
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
      }
    }


    public function admin_procced(){
      $service_id=$this->input->post('service_id');
      $service_name=$this->input->post('service_name');
      $portal_no=$this->input->post('portal_no');
      $target_url=$this->input->post('url');

      $mobile=$this->input->post('user_mobile');
      $applicant_name=$this->input->post('applicant_name');
      $address1=$this->input->post('address1');
      $address2=$this->input->post('address2');
      $address3=$this->input->post('address3');
      $pin_code=$this->input->post('pin_code');

      $this->form_validation->set_rules('user_mobile', 'user_mobile', 'required|trim');
      $this->form_validation->set_rules('applicant_name', 'applicant_name', 'required|trim');
      $this->form_validation->set_rules('address1', 'address1', 'required|trim');
      $this->form_validation->set_rules('address2', 'address2', 'required|trim');
      $this->form_validation->set_rules('pin_code', 'pin_code', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
          $status["status"] = false;
          $status["error_msg"] = "Please enter input for all require fields";
          return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
        }

        $service_details=$this->portals_model->get_guidelines($service_id);
        if(empty($service_details)){
          return false;
        }
       
        $external_service_id=property_exists($service_details,"external_service_id") ? $service_details->external_service_id : $service_details->service_id;
       
      $user=$this->session->userdata();
      $date=date('ydm');
      $rtps_trans_id=$this->generate_id();
      A1:
      if($this->intermediator_model->is_exist_transaction_no($rtps_trans_id)){
        $rtps_trans_id=$this->generate_id();
        goto A1;
      }


      $input_array=array(
          "rtps_trans_id"=>$rtps_trans_id,
          'user_id'=>$this->slug === "CSC" ? $user['userId'] : $user['userId']->{'$id'},
          "service_id"=>intval($external_service_id),
          "portal_no"=>$portal_no,
          'mobile'=>$mobile,
          "process"=>"N",
          "user_type"=>"PFC",
          "response_url"=>base_url("iservices/get/basundhara/response")
      );
      $input_array=json_encode($input_array);
      $aes = new AES($input_array, $this->encryption_key);
      $enc = $aes->encrypt();
      // $url=base_url()."request/create?data=".urlencode($enc);//var_dump($url);die;
      $url=$target_url."?data=".urlencode($enc);
      //AS200226V0313962/AS
      // 9x5CCz4G5GnxwULPdHXcPmkH9Xdg9KjENTl9OuKf3tk
      $user_data=array(
        'user_id'=>$this->slug === "CSC" ? $user['userId'] : $user['userId']->{'$id'},

        'mobile'=>$mobile,
        'applicant_name'=>$applicant_name,
        'address1'=>$address1,
        'address2'=>$address2,
        'address3'=>$address3,
        'pin_code'=>$pin_code,

        'rtps_trans_id'=>$rtps_trans_id,
        "service_name"=>$service_name,
        "portal_no"=>$portal_no,
        "service_id"=>intval($service_id),
        "external_service_id"=>$external_service_id,
        'target_url'=>$url,
        "kiosk_type"=> $this->slug ==="CSC" ? "CSC" : "PFC",
        'return_url'=>base_url("iservices/get/basundhara/response"),
        "status"=>"",
        "rtps_con_fee_required"=>true,
        "applied_by"=>$this->slug === "CSC" ? $user['userId'] : new ObjectId($this->session->userdata('userId')->{'$id'}),
        "createdDtm"=> new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
      );

      if(empty($user_data['user_id']) || empty($user_data['applied_by'])){
        $status["status"] = false;
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
      }
      
      $res=$this->intermediator_model->insert($user_data);
      $status=array();
      if($res){
          $status["status"] = true;
          $status["url"] = $url;
          $status["encrypted_data"] = $enc;
          $status["message"] = "Need to redirect to third party urls";
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
      }else {
          $status["status"] = false;
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
      }
    }


    //tracking

    public function check_status(){
      $status_url=$this->config->item("basundhara_status_url");
      // $status_url="https://10.177.15.156/basundhara/welcome/trackApplicationView";
      $outputdata=array();
      if(!isset($_GET['app_ref_no']) || empty($_GET['app_ref_no'])){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => "something went wrong",

            )));
            return;
      }
      $app_ref_no=$_GET['app_ref_no'];
      $res=$this->intermediator_model->get_userid_by_application_ref($app_ref_no);
      // pre( $res);
      if(!property_exists($res,"delivery_status") ||  ($res->delivery_status !=='D' && $res->delivery_status !=='R' ) ){
        $ref=modules::load('iservices/Schedulers');
        $ref->basundhara_status_by_app($app_ref_no);
      }
      // $user_mobile=$res->mobile;
      $user_mobile=isset($res->applicant_details) ? $res->applicant_details[0]->mobile_number : '';
      // $service_id=$res->external_service_id;
      if(empty($status_url)){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => "Status url not define",

            )));
            return;
      }

      $input=array(
        "application_no"=>$app_ref_no,
        "mobile"=>$user_mobile,
        // "service_id"=>$service_id
      );
      
      $input_array=json_encode($input);
      $aes = new AES($input_array, $this->encryption_key);
      $enc = $aes->encrypt();
      
      $data['url']=$status_url;
      $data['enc']=  $enc ;

      if(!empty($this->session->userdata('role')) && ($this->slug === "SA" || $this->slug === "PFC" || $this->slug === "CSC")){
          $this->load->view('includes/header');
          $this->load->view('basundhara/track',$data);
          $this->load->view('includes/footer');
        }else {
          $this->load->view('includes/frontend/header');
          $this->load->view('basundhara/track',$data);
          $this->load->view('includes/frontend/footer');
        }


    }
    private function my_transactions()
    {
      $user = $this->session->userdata();
      if (isset($user['role']) && !empty($user['role'])) {
        redirect(base_url('iservices/admin/my-transactions'));
      } else {
        redirect(base_url('iservices/transactions'));
      }
    }
    //download certificate
  public function download($rtps_trans_id)
  {
    $type = isset($_GET['type']) ? $_GET['type'] : false;
    if (!$type) {
      $this->session->set_flashdata('errmessage', 'Invalid document type');
      $this->my_transactions();
    }
    $user = $this->session->userdata();
    if ($this->slug === "PFC") {
      $applied_by = $this->session->userdata('userId')->{'$id'};
      $transaction_data = $this->intermediator_model->get_row(array('rtps_trans_id' => $rtps_trans_id, 'applied_by' => new ObjectId($applied_by)));
    } else if ($this->slug === "CSC") {
      $transaction_data = $this->intermediator_model->get_row(array('rtps_trans_id' => $rtps_trans_id, 'applied_by' => $user['userId']));
    } else if ($this->slug === "user") {
      $transaction_data = $this->intermediator_model->get_row(array('rtps_trans_id' => $rtps_trans_id, 'mobile' => $user['mobile']));
    } else if ($this->slug === "SA") {
      $transaction_data = $this->intermediator_model->get_row(array('rtps_trans_id' => $rtps_trans_id));
    } else {
      $transaction_data = array();
    }

    if ($transaction_data) {

      $url = "https://basundhara.assam.gov.in/rtpsmb/getDeliveryDoc";
      $postdata = array(
        'application_no' => $transaction_data->app_ref_no,
        'doc_type' => $type,
      );
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
      $response = curl_exec($curl);

      if ($response) {

        $res = json_decode($response, true);
        if (intval($res['responsetType']) === 2) {
          $base64 = $res['data'][0];
          $decoded = base64_decode($base64, true);
  

        $decodedImage = base64_decode($base64);
        $ext = (explode('/', finfo_buffer(finfo_open(), $decodedImage, FILEINFO_MIME_TYPE))[1]);

          $upload_dir = FCPATH . 'storage/docs/basundhara_temp/';
          if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
     
          $file = $upload_dir.$rtps_trans_id.'.'.$ext;
          file_put_contents($file, $decoded);
          if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            unlink($file);
            exit;
          }
        }
      }
    }
  }

}
