<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Application extends Rtps
{
    private $serviceName = "Application for Trade Licence";
    private $serviceId = "TRADE";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tradelicence/licence_model');
        $this->load->model('tradelicence/ulb_list');
        $this->load->model('tradelicence/tradefees');  
        $this->load->helper("cifileupload");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->helper("log");
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
    } //End of __construct()



    public function index($obj_id = null)
    {
        $data = array("pageTitle" => "Application for Trade Licence");
        $filter = array("_id" => new ObjectId($obj_id), "service_data.appl_status" => "DRAFT");
        $data["dbrow"] = $this->licence_model->get_row($filter);
        $data['usser_type'] = $this->slug;
        //$data["sro_dist_list"] = $this->licence->sro_dist_list();

        $data['ulb_list'] = (array)$this->ulb_list->get_ulb_list();
        $ulb_list_array = json_decode(json_encode($data['ulb_list']), true);
        $data['ulb_list'] = $ulb_list_array;

        $data['tradefees'] = (array)$this->tradefees->get_tradefees();
        $tradefees_array = json_decode(json_encode($data['tradefees']), true);
        $data['tradefees'] = $tradefees_array;

        // print_r($data['ulb_list']);
        // return;
        

        $this->load->view('includes/frontend/header');
        $this->load->view('tradelicence/application', $data);
        $this->load->view('includes/frontend/footer');
    } //End of index()
    public function getlocation()
    {
        $id = $_GET['id'];
        if ($id) {
            $data = $this->licence_model->get_sro_list($id);
            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode(array());
            }
        }
    }

    public function acknowledgement($obj)
    {
        $applicationRow = $this->licence_model->get_by_doc_id($obj);
        if ($applicationRow) {
            $service_id = $applicationRow->service_data->service_id;
            $data['response'] = $applicationRow;
            //$data['service_row'] = $this->services_model->get_row(array("service_id"=>$service_id));
            $data['back_to_dasboard'] = '<a href="' . base_url('spservices/tradelicence/acknowledgement') . '" class="btn btn-primary mb-2 pull-right"  >Back To Dashboard</a>';
            $data['pageTitle'] = "Acknowledgement";
            $this->load->view('includes/frontend/header');
            $this->load->view('tradelicence/ack', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            redirect('spservices/tradelicence/acknowledgement/');
        } //End of if else
    }

    public function certificate($obj)
    {

        $applicationRow = $this->licence_model->get_by_doc_id($obj);
        if ($applicationRow) {
            //pre($applicationRow);
            $service_id = $applicationRow->service_data->service_id;
            $data['response'] = $applicationRow;
            //   $data['service_row'] = $this->services_model->get_row(array("service_id"=>$service_id));
            $data['back_to_dasboard'] = '<a href="' . base_url('spservices/tradelicence/certificate') . '" class="btn btn-primary mb-2 pull-right"  >Back To Dashboard</a>';
            $data['pageTitle'] = "Certificate";
            $this->load->view('includes/frontend/header');
            $this->load->view('tradelicence/certificate', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            redirect('spservices/tradelicence/certificate/');
        } //End of if else
    }
    public function submit()
    {
        
        $obj = $this->input->post("obj_id");
        $appl_ref_no = $this->input->post("appl_ref_no");
        $submissionMode = $this->input->post("submission_mode");
        $rtps_trans_id = $this->input->post("rtps_trans_id");
        $certificate_no = $this->input->post("certificate_no");
        //$this->form_validation->set_rules('appref_no', 'appref_no', 'trim|integer|required|xss_clean|strip_tags');      
        //$this->form_validation->set_rules('mobile', 'Mobile', 'trim|integer|required|exact_length[10]|xss_clean|strip_tags');
        //$this->form_validation->set_rules('ubin', 'ubin', 'trim|integer|required|xss_clean|strip_tags');
        //$this->form_validation->set_rules('applicant_name', 'applicant_name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        //$this->form_validation->set_rules('applicant_gender', 'Gender', 'trim|required|xss_clean|strip_tags|max_length[255]');
        //$this->form_validation->set_rules('father_name', 'father_name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        //$this->form_validation->set_rules('ulb', 'ulb', 'trim|required|xss_clean|strip_tags|max_length[255]');
        //$this->form_validation->set_rules('email', 'Email', 'valid_email|required|trim|xss_clean|strip_tags');
        //$this->form_validation->set_rules('area', 'Area', 'trim|required|xss_clean|strip_tags|max_length[255]');
        //$this->form_validation->set_rules('mouza', 'mouza', 'trim|required|xss_clean|strip_tags|max_length[255]');
        //$this->form_validation->set_rules('post_office', 'post_office', 'trim|required|xss_clean|strip_tags|max_length[255]');
        //$this->form_validation->set_rules('country', 'country', 'trim|required|xss_clean|strip_tags|max_length[255]');
        //$this->form_validation->set_rules('state', 'state', 'trim|required|xss_clean|strip_tags|max_length[255]');
        //$this->form_validation->set_rules('district', 'district', 'trim|required|xss_clean|strip_tags|max_length[255]');
        //$this->form_validation->set_rules('pin_code', 'pin_code', 'trim|integer|required|exact_length[6]|xss_clean|strip_tags');
        //$this->form_validation->set_rules('police_station', 'police_station', 'trim|required|xss_clean|strip_tags|max_length[255]');
       // $this->form_validation->set_rules('ward_no', 'ward_no', 'trim|integer|required|xss_clean|strip_tags');
       // $this->form_validation->set_rules('applicant_age', 'applicant_age', 'trim|integer|xss_clean|strip_tags');
        //$this->form_validation->set_rules('business_est', 'business_est', 'trim|required|xss_clean|strip_tags|max_length[255]');
        //$this->form_validation->set_rules('business_name', 'business_name', 'trim|required|xss_clean|strip_tags|max_length[255]');
       // $this->form_validation->set_rules('business_type', 'business_type', 'trim|required|xss_clean|strip_tags|max_length[255]');
        //$this->form_validation->set_rules('owner_name', 'owner_name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        //$this->form_validation->set_rules('commencement_business', 'commencement_business', 'trim|required|xss_clean|//strip_tags|max_length[255]');
       // $this->form_validation->set_rules('ward_no2', 'ward_no2', 'trim|integer|required|xss_clean|strip_tags');
        //$this->form_validation->set_rules('holding_no', 'holding_no', 'trim|integer|required|xss_clean|strip_tags');
        //$this->form_validation->set_rules('trade_name', 'trade_name', 'trim|required|xss_clean|strip_tags|max_length[255]');
        $this->form_validation->set_rules('fees', 'fees', 'trim|integer|required|xss_clean|strip_tags');
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $obj_id = strlen($obj) ? $obj : null;
            $this->index($obj_id);
        } else {

            $appl_ref_no = $this->getID(7);
            $certificate_no = $this->getID(7);
            $sessionUser = $this->session->userdata();


            if ($this->slug === "CSC") {
                $apply_by = $sessionUser['userId'];
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            } //End of if else

            while (1) {
                $app_id = rand(1000000, 9999999);
                $filter = array(
                    "service_data.appl_id" => $app_id
                );
                $rows = $this->licence_model->get_row($filter);

                if ($rows == false)
                    break;
            }

            $transId = strlen($obj) ? $rtps_trans_id : $this->getID(7);
            $CertificateId = strlen($obj) ? $CertificateId : $this->getCertificateNo(7);

            $service_data = [
                "department_id" => "",
                "department_name" => "",
                "service_id" => $this->serviceId,
                "service_name" => $this->serviceName,
                "appl_id" => $app_id,
                "appl_ref_no" => $appl_ref_no,
                "submission_mode" => $submissionMode, //kiosk, online, in-person
                "applied_by" => $apply_by,
                "submission_location" => "General Administration Department (STATE)", // office name
                "submission_date" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "service_timeline" => "20 Days",
                "appl_status" => "DRAFT",
                "rtps_trans_id" => $transId,
                "certificate_no" => $CertificateId,
            ];


            $form_data = [
                'appref_no' => $this->input->post("appref_no"),
                'mobile' => $this->input->post("mobile"),
                'ubin' => $this->input->post("ubin"),
                'father_name' => $this->input->post("father_name"),
                'applicant_name' => $this->input->post("applicant_name"),
                'applicant_gender' => $this->input->post("applicant_gender"),
                'ulb' => $this->input->post("ulb"),
                'ulb_id' => $this->input->post("ulb_id"),
                'email' => $this->input->post("email"),
                'area' => $this->input->post("area"),
                'mouza' => $this->input->post("mouza"),
                'post_office' => $this->input->post("post_office"),
                'country' => $this->input->post("country"),
                'state' => $this->input->post("state"),
                'district' => $this->input->post("district"),
                'pin_code' => $this->input->post("pin_code"),
                'police_station' => $this->input->post("police_station"),
                'ward_no' => $this->input->post("ward_no"),
                'applicant_age' => $this->input->post("applicant_age"),
                'business_est' => $this->input->post("business_est"),
                'business_name' => $this->input->post("business_name"),
                'business_type' => $this->input->post("business_type"),
                'reason' => $this->input->post("reason"),
                'business_ownership' => $this->input->post("business_ownership"),
                'owner_name' => $this->input->post("owner_name"),
                'father_name2' => $this->input->post("father_name2"),
                'commencement_business' => $this->input->post("commencement_business"),

                'ward_no2' => $this->input->post("ward_no2"),
                'other_business' => $this->input->post("other_business"),
                'holding_no' => $this->input->post("holding_no"),
                'road_name' => $this->input->post("road_name"),
                'trade_name' => $this->input->post("trade_name"),
                'fees' => $this->input->post("fees"),
                'area_in_square' => $this->input->post("area_in_square"),
                'created_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
            ];


            if (strlen($obj)) {
                $form_data["proof_ownership_type"] = $this->input->post("proof_ownership_type");
                $form_data["proof_ownership"] = $this->input->post("proof_ownership");

                $form_data["identity_proof_type"] = $this->input->post("identity_proof_type");
                $form_data["identity_proof"] = $this->input->post("identity_proof");
                $form_data["ubin_certificate"] = $this->input->post("ubin_certificate");
                $form_data["ubin_certificate_type"] = $this->input->post("ubin_certificate_type");
            }

            $inputs = [
                'service_data' => $service_data,
                'form_data' => $form_data
            ];

            if (strlen($obj)) {
                $this->licence_model->update_where(['_id' => new ObjectId($obj)], $inputs);
                $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                redirect('spservices/tradelicence/application/fileuploads/' . $obj);
            } else {
                $insert = $this->licence_model->insert($inputs);
                if ($insert) {
                    $objectId = $insert['_id']->{'$id'};
                    $this->session->set_flashdata('success', 'Your application has been successfully submitted');
                    redirect('spservices/tradelicence/application/fileuploads/' . $objectId);
                    exit();
                } else {
                    $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                    $this->index();
                } //End of if else
            } //End of if else

        } //End of if else
    } //End of submit()


    public function fileuploads($obj = null)
    {
        $dbRow = $this->licence_model->get_by_doc_id($obj);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "pageTitle" => "Attached Enclosures for " . $this->serviceName,
                "obj_id" => $obj,
                "dbrow" => $dbRow
            );
            $this->load->view('includes/frontend/header');
            //$this->load->view('nec/necertificateuploads_view',$data);
            //  $this->load->view('seniorcitizen/sccertificateuploads_view', $data);
            $this->load->view('tradelicence/tradeuploads_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $obj);
            redirect('spservices/tradelicence/');
        } //End of if else
    } //End of fileuploads()

   
    private function my_transactions()
    {
        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
            redirect(base_url('iservices/admin/my-transactions'));
        } else {
            redirect(base_url('iservices/transactions'));
        }
    }

           // $url = $this->config->item('edistrict_base_url');
            //$curl = curl_init($url . "postApplication?apiKey=un5pdxOw1glKratBgIlL5w&rprm=xyz");


            //   pre(json_encode($postdata));
            // $buffer = preg_replace( "/\r|\n/", "", $json );
            // $myfile = fopen("D:\\TESTDATA\\abcd1234.txt", "a") or die("Unable to open file!");
            // fwrite($myfile, $buffer);
            // fclose($myfile);
            //  die;



            //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
           // curl_setopt($curl, CURLOPT_POST, true);
            //curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
            //curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            //$response = curl_exec($curl);
            //curl_close($curl);

            //if ($response) {
               // $response = json_decode($response);
                //if ($response->ref->status === "success") {
                   // $data_to_update = array(
                     //   'service_data.appl_status' => 'submitted',
                       // 'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                        //'processing_history' => $processing_history
                    //);
                    //$this->income_registration_model->update($obj, $data_to_update);
                    //generate acknowlegement
                    //redirect('spservices/incomecertificate/acknowledgement/acknowledgement/' . $obj);
                //} else {
                    //redierct to applivation page
                  //  $this->session->set_flashdata('error', 'Something went wrong please try again.');
                    //redirect('spservices/incomecertificate/registration1/index/' . $obj);
                //}

            //}
        //}
    //}

    public function submitfiles()
    {
        $obj = $this->input->post("obj_id");
        $this->form_validation->set_rules('proof_ownership_type', 'Proof of ownership', 'required');
        $this->form_validation->set_rules('identity_proof_type', 'Identity Proof', 'required');
        $this->form_validation->set_rules('ubin_certificate_type', 'UBIN Certificate', 'required');
        
        $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

        $proof_ownership = cifileupload("proof_ownership");
        $proof_ownership = $proof_ownership["upload_status"] ? $proof_ownership["uploaded_path"] : null;
        $identity_proof = cifileupload("identity_proof");
        $identity_proof = $identity_proof["upload_status"] ? $identity_proof["uploaded_path"] : null;
        $ubin_certificate = cifileupload("ubin_certificate");
        $ubin_certificate = $ubin_certificate["upload_status"] ? $ubin_certificate["uploaded_path"] : null;
        


        $uploadedFiles = array(
            "proof_ownership_old" => strlen($proof_ownership) ? $proof_ownership : $this->input->post("proof_ownership_old"),
            "identity_proof_old" => strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old"),
            "ubin_certificate_old" => strlen($ubin_certificate) ? $ubin_certificate : $this->input->post("ubin_certificate"),
           
        );
        $this->session->set_flashdata('uploaded_files', $uploadedFiles);

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
            $this->fileuploads($obj);
        } else {

            $data = array(
                'form_data.proof_ownership_type' => $this->input->post("proof_ownership_type"),
                'form_data.identity_proof_type' => $this->input->post("identity_proof_type"),
                'form_data.proof_ownership' => $this->input->post("proof_ownership"),
                'form_data.identity_proof' => $this->input->post("identity_proof"),
                'form_data.ubin_certificate' => $this->input->post("ubin_certificate"),
                'form_data.ubin_certificate_type' => $this->input->post("ubin_certificate_type"),             
                'form_data.proof_ownership' => strlen($proof_ownership) ? $proof_ownership : $this->input->post("proof_ownership_old"),
                'form_data.identity_proof' => strlen($identity_proof) ? $identity_proof : $this->input->post("identity_proof_old"),
                'form_data.ubin_certificate' => strlen($ubin_certificate) ? $ubin_certificate : $this->input->post("ubin_certificate_old"),
                 );
            $this->licence_model->update_where(['_id' => new ObjectId($obj)], $data);
            $this->session->set_flashdata('success', 'Your application has been successfully submitted');
            redirect('spservices/tradelicence/Application/preview/' . $obj);
        } //End of if else
    } //End of submitfiles()


    public function preview($obj = null)
    {

        $dbRow = $this->licence_model->get_by_doc_id($obj);
        // pre($dbRow);
        if (count((array)$dbRow)) {
            $data = array(
                "service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('tradelicence/tradepreview_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $obj);
            redirect('spservices/tradelicence');
        } //End of if else
    } //End of preview()
    
    function createcaptcha()
    {
        $captchaDir = "storage/captcha/";
        array_map('unlink', glob("$captchaDir*.jpg"));

        $this->load->helper('captcha');
        $config = array(
            'img_path' => './storage/captcha/',
            'img_url' => base_url('storage/captcha/'),
            'font_path' => APPPATH . 'sys/fonts/texb.ttf',
            'img_width' => '150',
            'img_height' => 30,
            'expiration' => 7200,
            'word_length' => 6,
            'font_size' => 16,
            'img_id' => 'capimg',
            'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(94, 20, 38),
                'text' => array(0, 0, 0),
                'grid' => array(178, 184, 194)
            )
        );
        $captcha = create_captcha($config);
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode', $captcha['word']);
        echo $captcha['image'];
    } //End of createcaptcha()


    public function track($obj = null)
    {
        $dbRow = $this->licence_model->get_by_doc_id($obj);
        if (count((array)$dbRow)) {
            $data = array(
                "service_data.service_name" => $this->serviceName,
                "dbrow" => $dbRow,
                "user_type" => $this->slug
            );
            $this->load->view('includes/frontend/header');
            $this->load->view('tradelicence/tradelicencetrack_view', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            $this->session->set_flashdata('error', 'No records found against object id : ' . $obj);
            redirect('spservices/tradelicence/');
        } //End of if else
    } //End of track()


    function getID($length)
    {
        $rtps_trans_id = $this->generateID($length);
        while ($this->licence_model->get_row(["rtps_trans_id" => $rtps_trans_id])) {
            $rtps_trans_id = $this->generateID($length);
        } //End of while()
        return $rtps_trans_id;
    } //End of getID()
    function getCertificateNo($length)
    {
        $certificate_no = $this->generateCertificateNo($length);
        while ($this->licence_model->get_row(["certificate_no" => $certificate_no])) {
            $certificate_no = $this->generateCertificateNo($length);
        } //End of while()
        return $certificate_no;
    } //End of getID()
    
    public function finalsubmition($obj = null)
    {
        
        if ($obj) {
            $dbRow = $this->licence_model->get_by_doc_id($obj);
            //procesing data
            $processing_history = $dbRow->processing_history ?? array();
            $processing_history[] = array(
                "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
                "action_taken" => "Application Submition",
                "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
                "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            );

            $data_to_update = array(
                'service_data.appl_status' => 'submitted',
                'service_data.submission_date' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                'processing_history' => $processing_history
            );
            $this->licence_model->update($obj, $data_to_update);
            //generate acknowlegement
            redirect('spservices/tradelicence/acknowledgement/acknowledgement/' . $obj);
            }
    }

   
    public function generateID($length)
    {
        $date = date('Y');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "RTPS-TRADE/" . $date . "/" . $number;
        return $str;
    } //End of generateID()
    public function generateCertificateNo($length)
    {
        $date = date('Y');
        $number = '';
        for ($i = 0; $i < $length; $i++) {
            $number .= rand(0, 9);
        }
        $str = "UDD/AS/TRADE/" . $date . "/" . $number;
        return $str;
    } //End of generateID()
    public function get_fees()
    {
        $ulb_id = $this->input->post('ulb_id');
        $trade = $this->input->post('trade');
        $ulb_data = (array)$this->get_ulb($ulb_id);
        //pre($ulb_data);
        if(count($ulb_data)){
            $population = $ulb_data[0]->population;
            $trade_data = (array)$this->get_trade($trade);
            $response['flag'] = isset($trade_data[0]->flag) ? $trade_data[0]->flag : '';
            if($population < 10000){
                $response['fees'] = $trade_data[0]->upto_10000;
            }
            else if ($population > 10000 && $population < 30000) {
                $response['fees'] = $trade_data[0]->between_10000_to_30000;

            }
            else if ($population > 30000 && $population <50000) {
                $response['fees'] = $trade_data[0]->between_30000_to_50000;
            } 
            else if ($population > 50000) {
                $response['fees'] = $trade_data[0]->above_50000;
            }
        }
        else{

        }
        echo json_encode(['data'=>$response]);
    } //End of get_fees()
    
    public function get_ulb($ulb){
        return $this->mongo_db->where(array('ulb_id'=> intval($ulb)))->get('ulb_list');
    }
    public function get_trade($trade_name)
    {
        return $this->mongo_db->where(array('trade_name' => $trade_name))->get('tradefees');
    }
    
}