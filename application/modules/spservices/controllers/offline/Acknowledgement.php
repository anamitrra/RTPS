<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Acknowledgement extends Rtps
{
  //put your code here
  public function __construct()
  {
    parent::__construct();
    $this->load->model("offline/acknowledgement_model");
    $this->load->model("offline/service_model");
    $this->load->model("minoritycertificates/districts_model");
    $this->load->helper("cifileupload");
    $this->lang->load('mcc_registration', $this->session->mcc_lang);
    if (!empty($this->session->userdata('role'))) {
      $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
    } else {
      $this->slug = "user";
    }
  }

  public function form($obj_id = null)
  {
   
    $sessionUser = $this->session->userdata();
    $data['districts'] = $this->districts_model->get_rows(array("state_id" => 1));
    // $data['service_list'] = $this->service_model->get_offline_services();
    $filter = array("_id" => new ObjectId($obj_id), "service_data.appl_status" => "DRAFT");
    $data["dbrow"] = strlen($obj_id) ? $this->acknowledgement_model->get_row($filter) : array();
    $data['usser_type'] = $this->slug;

    if( $data["dbrow"] ){
      $service_code=$data["dbrow"]->service_data->service_id;
    }else{
      $service_code=$_GET['scode'];
    }
    $data['service_details']=$this->service_model->get_service_details($service_code);
    if(empty($data['service_details'])){
      $this->session->set_flashdata('errmessage', 'No service found');
      redirect('iservices/transactions');
    }
    $data['office_list']= $this->service_model->get_offline_office_by_services($service_code);
    if (!empty($obj_id) && empty($data["dbrow"])) {
      $this->session->set_flashdata('errmessage', 'No application found');
      redirect('iservices/transactions');
    }
    if ($this->slug === "user") {
      $data['user_mobile'] = $sessionUser['mobile'];
    } else {
      $data['user_mobile'] = "";
    }
    $this->load->view("includes/frontend/header", array("pageTitle" => "Action Form"));
    $this->load->view("offline/ack_form", $data);
    $this->load->view("includes/frontend/footer");
  }
  public function generate_id($service_code = "OFF")
  {
    $number = '';
    for ($i = 0; $i < 7; $i++) {
      $number .= rand(0, 9);
    }
    $date = date('Y');
    $str = "RTPS-" . strtoupper($service_code) . "/" . $date . "/" . $number;
    return $str;
  }
  public function action()
  {
    $objId = $this->input->post("obj_id");
    $rtps_trans_id = $this->input->post("rtps_trans_id");
    $submitMode = $this->input->post("submit_mode");
    $sessionUser = $this->session->userdata();
    // $this->form_validation->set_rules("service_name", "Service Name", "required|max_length[255]");
    $this->form_validation->set_rules("service_id", "Service Name", "required|max_length[255]");
    // $this->form_validation->set_rules("date_of_application", "Date of Application", "required|max_length[255]");
    $this->form_validation->set_rules("applicant_name", "Applicant Name", "required|max_length[255]");
    $this->form_validation->set_rules("gender", "Gender", "required|max_length[255]");
    $this->form_validation->set_rules("age", "Age", "required|numeric");
    $this->form_validation->set_rules("mobile", "Mobile Number", "required|max_length[10]");
    $this->form_validation->set_rules("timeline", "Stipulated Timeline for service delivery(days)", "required|max_length[255]");
    $this->form_validation->set_rules("office_code", "Applied Office", "required|max_length[255]");
    // $this->form_validation->set_rules("department_name", "Department Name", "required|max_length[255]");
    // $this->form_validation->set_rules("amount", "User Charges", "required|max_length[255]");
    $this->form_validation->set_rules('pa_house_no', 'House no.', 'trim|xss_clean|strip_tags');
    $this->form_validation->set_rules('pa_street', 'Street', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('pa_village', 'Village', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('pa_post_office', 'Post oddice', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('pa_pin_code', 'Pin code', 'trim|required|integer|exact_length[6]|xss_clean|strip_tags');
    $this->form_validation->set_rules('pa_state', 'State', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('pa_district_id', 'District', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('pa_circle', 'Circle', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('pa_police_station', 'Police station', 'trim|required|xss_clean|strip_tags');

    $this->form_validation->set_rules('address_same', 'Is same address', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('ca_house_no', 'House no.', 'trim|xss_clean|strip_tags');
    $this->form_validation->set_rules('ca_street', 'Street', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('ca_village', 'Village', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('ca_post_office', 'Post oddice', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('ca_pin_code', 'Pin code', 'trim|required|integer|exact_length[6]|xss_clean|strip_tags');
    $this->form_validation->set_rules('ca_state', 'State', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('ca_district_id', 'District', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('ca_circle', 'Circle', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('ca_police_station', 'Police station', 'trim|required|xss_clean|strip_tags');

    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    if ($this->form_validation->run() == FALSE) {
      $obj_id = strlen($objId) ? $objId : null;
      $this->form($obj_id);
      return;
    }
    $pa_district_name = $this->input->post("pa_district_name");

    //check office is exist or not
    $userFilter = array('user_services.service_code' => $this->input->post("service_id"), 'offices_info.office_code' => $this->input->post("office_code"));
    $current_users = $this->get_upms_user($userFilter);
    if (empty($current_users)) {
      $this->session->set_flashdata('error', 'No department user found. Please contact support team or choose another office');
      redirect('spservices/offline/acknowledgement/form/' . $objId);
    }

    if (strlen($objId)) {
      $data_to_update = array(
        "service_data.service_id" => $this->input->post("service_id"),
        "service_data.service_name" => $this->input->post("service_name"),
        "service_data.submission_mode" => $submitMode,
        // "service_data.submission_date" => $this->input->post("date_of_application"),
        "service_data.service_timeline" => $this->input->post("timeline"),

        "form_data.applicant_name" => $this->input->post("applicant_name"),
        "form_data.mobile" => $this->input->post("mobile"),
        "form_data.applicant_gender" => $this->input->post("gender"),
        "form_data.age" => $this->input->post("age"),
        "form_data.pa_house_no" => $this->input->post("pa_house_no"),
        "form_data.pa_street" => $this->input->post("pa_street"),
        "form_data.pa_village" => $this->input->post("pa_village"),
        "form_data.pa_post_office" => $this->input->post("pa_post_office"),
        "form_data.pa_pin_code" => $this->input->post("pa_pin_code"),
        "form_data.pa_state" => $this->input->post("pa_state"),
        "form_data.pa_district_id" => $this->input->post("pa_district_id"),
        "form_data.pa_district_name" => $pa_district_name,
        "form_data.pa_circle" => $this->input->post("pa_circle"),
        "form_data.pa_police_station" => $this->input->post("pa_police_station"),
        "form_data.pa_police_station" => $this->input->post("pa_police_station"),
        "form_data.address_same" => $this->input->post("address_same"),
        "form_data.ca_house_no" => $this->input->post("ca_house_no"),
        "form_data.ca_street" => $this->input->post("ca_street"),
        "form_data.ca_village" => $this->input->post("ca_village"),
        "form_data.ca_post_office" => $this->input->post("ca_post_office"),
        "form_data.ca_pin_code" => $this->input->post("ca_pin_code"),
        "form_data.ca_state" => $this->input->post("ca_state"),
        "form_data.ca_district_id" => $this->input->post("ca_district_id"),
        "form_data.ca_district_name" => $this->input->post("ca_district_name"),
        "form_data.ca_circle" => $this->input->post("ca_circle"),
        "form_data.ca_police_station" => $this->input->post("ca_police_station"),
        "form_data.ca_police_station" => $this->input->post("ca_police_station"),
        // "form_data.amount"=>$this->input->post("amount"),
        "form_data.office_code" => $this->input->post("office_code"),
      );
      $this->acknowledgement_model->update_row(['_id' => new ObjectId($objId)], $data_to_update);
      $this->session->set_flashdata('success', 'Your application has been successfully saved');
      redirect('spservices/offline/acknowledgement/fileuploads/' . $objId . '?type=application_form');
    } else {
      $rtps_trans_id = $this->generate_id($this->input->post("service_id"));
      A1:
      if ($this->acknowledgement_model->is_exist_transaction_no($rtps_trans_id)) {
        $rtps_trans_id = $this->generate_id();
        goto A1;
      }
      if ($this->slug === "CSC") {
        $applied_by = $sessionUser['userId'];
      } else {
        $applied_by = new ObjectId($this->session->userdata('userId')->{'$id'});
      }
      $data = array(
        "service_data" => array(
          // "department_id"=>$this->input->post("department_id"),
          // "department_name"=>$this->input->post("department_name"),
          "service_id" => $this->input->post("service_id"),
          "service_name" => $this->input->post("service_name"),
          "appl_ref_no" => $rtps_trans_id,
          "submission_mode" => $submitMode,
          "applied_by" => $applied_by,
          // "submission_date" => $this->input->post("date_of_application"),
          "service_timeline" => $this->input->post("timeline"),
          "appl_status" => "DRAFT",
          "submission_location"=> $this->input->post("office_name")

        ),

        "form_data" => array(
          'user_id' => $sessionUser['userId']->{'$id'},
          'user_type' => $this->slug,
          "rtps_trans_id" => $rtps_trans_id,
          "applicant_name" => $this->input->post("applicant_name"),
          "mobile" => $this->input->post("mobile"),
          "applicant_gender" => $this->input->post("gender"),
          "age" => $this->input->post("age"),

          'pa_house_no' => $this->input->post("pa_house_no"),
          'pa_street' => $this->input->post("pa_street"),
          'pa_village' => $this->input->post("pa_village"),
          'pa_post_office' => $this->input->post("pa_post_office"),
          'pa_pin_code' => $this->input->post("pa_pin_code"),
          'pa_state' => $this->input->post("pa_state"),
          'pa_district_id' => $this->input->post("pa_district_id"),
          'pa_district_name' => $pa_district_name,
          'pa_circle' => $this->input->post("pa_circle"),
          'pa_police_station' => $this->input->post("pa_police_station"),

          'address_same' => $this->input->post("address_same"),
          'ca_house_no' => $this->input->post("ca_house_no"),
          'ca_street' => $this->input->post("ca_street"),
          'ca_village' => $this->input->post("ca_village"),
          'ca_post_office' => $this->input->post("ca_post_office"),
          'ca_pin_code' => $this->input->post("ca_pin_code"),
          'ca_state' => $this->input->post("ca_state"),
          'ca_district_id' => $this->input->post("ca_district_id"),
          'ca_district_name' => $this->input->post("ca_district_name"),
          'ca_circle' => $this->input->post("ca_circle"),
          'ca_police_station' => $this->input->post("ca_police_station"),
          'office_code' => $this->input->post("office_code"),

          "amount" => $this->input->post("amount"),
          "is_offline" => true,
          "step1" => true,
          "createdDtm" => new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000))
        )
      );

      $insert = $this->acknowledgement_model->insert($data);
      if ($insert) {
        $objectId = $insert['_id']->{'$id'};
        $this->session->set_flashdata('success', 'Your application has been successfully saved');
        redirect('spservices/offline/acknowledgement/fileuploads/' . $objectId . '?type=application_form');
        exit();
      } else {
        $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
        $this->form();
      } //End of if else
    } //End of if else


  }


  public function fileuploads($objId = null)
  {
    $dbRow = $this->acknowledgement_model->get_by_doc_id($objId);
    $type = $_GET['type'];
    if (count((array)$dbRow) &&  !empty($type)) {
      $data = array(
        "pageTitle" => "Upload Attachments",
        "servive_name" => "Application for " . $dbRow->service_data->service_name,
        "application_form_path" => isset($dbRow->form_data->application_form_path) ?  $dbRow->form_data->application_form_path : false,
        "supporting_docs" => isset($dbRow->form_data->supporting_docs) ?  $dbRow->form_data->supporting_docs : false,
        "obj_id" => $objId,
        "rtps_trans_id" => $dbRow->form_data->rtps_trans_id,
        'action' => ($type === "application_form") ? base_url("spservices/offline/acknowledgement/upload_application_form") : base_url("spservices/offline/acknowledgement/submitfiles")
      );
      $this->load->view('includes/frontend/header');
      if ($type === "application_form") {
        $this->load->view('offline/upload_application_form', $data);
      } else {
        $this->load->view('offline/enclosure', $data);
      }

      $this->load->view('includes/frontend/footer');
    } else {
      $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
      redirect('spservices/offline/acknowledgement/form/');
    } //End of if else
  } //End of fileuploads()


  public function upload_application_form()
  {
    $objId = $this->input->post("obj_id");

    if ($_FILES['application_form']['size'] === 0 && empty($this->input->post('existing_file_path'))) {
      $this->session->set_flashdata('error', 'Please upload the application form ');
      redirect('spservices/offline/acknowledgement/fileuploads/' . $objId . "?type=application_form");
    }
    if ($_FILES['application_form']['size'] > 0) {
      $application_form = cifileupload("application_form");
      $upload_path = $application_form["upload_status"] ? $application_form["uploaded_path"] : null;

      $data = array(
        "form_data.application_form_path" => $upload_path,
        "form_data.step2" => true,
      );
      $this->acknowledgement_model->update_where(['_id' => new ObjectId($objId)], $data);
    }

    $this->session->set_flashdata('success', 'File has been successfully uploaded');
    redirect('spservices/offline/acknowledgement/fileuploads/' . $objId . "?type=docs");
  } //End of submitfiles()
  public function submitfiles()
  {
    $objId = $this->input->post("obj_id");
    $is_file_uploaded = (!empty($_FILES['files']['name']) && count(array_filter($_FILES['files']['name'])) > 0) ? true : false;
    if (!$is_file_uploaded && empty($this->input->post('existing_file_path'))) {
      $this->session->set_flashdata('error', 'Please upload atleast one document');
      redirect('spservices/offline/acknowledgement/fileuploads/' . $objId . "?type=doc");
    } else if ($is_file_uploaded) {

      $dbRow = $this->acknowledgement_model->get_by_doc_id($objId);
      $supporting_doc = $dbRow->form_data->supporting_docs ?? false;
      $error = false;
      $file_types = $_POST['file_types'];
      $errorUploadType = $statusMsg = '';
      $folder_path = 'storage/doc/' . date("Y") . '/' . date("m") . '/' . date("d") . '/';
      $pathname =  $folder_path;
      if (!is_dir($pathname)) {
        mkdir($pathname, 0777, true);
        file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
      }
      if ($is_file_uploaded) {
        $filesCount = count($_FILES['files']['name']);
        for ($i = 0; $i < $filesCount; $i++) {
          $_FILES['file']['name']     = $_FILES['files']['name'][$i];
          $_FILES['file']['type']     = $_FILES['files']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
          $_FILES['file']['error']     = $_FILES['files']['error'][$i];
          $_FILES['file']['size']     = $_FILES['files']['size'][$i];

          // File upload configuration 

          $config['upload_path'] = $pathname;
          $config['allowed_types'] = 'jpg|jpeg|pdf';
          $config['encrypt_name'] = TRUE;
          // $config['max_size']    = '100'; 
          //$config['max_width'] = '1024'; 
          //$config['max_height'] = '768'; 

          // Load and initialize upload library 
          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          // Upload file to server 
          if ($this->upload->do_upload('file')) {
            // Uploaded file data 
            $fileData = $this->upload->data();
            $uploadData[$i]['doc_type'] =   $file_types[$i];
            $uploadData[$i]['file_name'] = $pathname . $fileData['file_name'];
            $uploadData[$i]['uploaded_on'] = date("Y-m-d H:i:s");
          } else {
            $errorUploadType .= $_FILES['file']['name'] . ' | ';
          }
        }
        $errorUploadType = !empty($errorUploadType) ? '<br/>File Type Error: ' . trim($errorUploadType, ' | ') : '';
        if (!empty($uploadData)) {
          // Insert files data into the database 

          $this->acknowledgement_model->update_where(['_id' => new ObjectId($objId)], array(
            "form_data.step3" => true,
            "form_data.supporting_docs" => $supporting_doc ? array_merge($supporting_doc, $uploadData) : $uploadData
          ));
          // Upload status message 
          $statusMsg = 'Files uploaded successfully!';
        } else {
          $error = true;
          $statusMsg = "Sorry, there was an error uploading your file." . $errorUploadType;
        }
      } else {
        $error = true;
        $statusMsg = 'Please select a files to upload.';
      }
      if ($error) {
        $this->session->set_flashdata('error', $statusMsg);
        redirect('spservices/offline/acknowledgement/fileuploads/' . $objId . "?type=doc");
      } else {
        $this->session->set_flashdata('success', $statusMsg);
        redirect(base_url("spservices/offline/acknowledgement/preview/" . $objId));
      }
    } else {
      redirect(base_url("spservices/offline/acknowledgement/preview/" . $objId));
    }
  } //End of submitfiles()
  public function get_upms_user($filter = null)
  {
    $this->mongo_db->order_by('created_at', 'DESC');
    $this->mongo_db->where($filter);
    $userRows = $this->mongo_db->get("upms_users");
    if (count((array)$userRows)) {
     
      $current_users = array();
      if ($userRows) {
        foreach ($userRows as $key => $userRow) {
          $current_user = array(
            'login_username' => $userRow->login_username,
            'email_id' => $userRow->email_id,
            'mobile_number' => $userRow->mobile_number,
            'user_level_no' => $userRow->user_levels->level_no,
            'user_fullname' => $userRow->user_fullname,
          );
          $current_users[] = $current_user;
        } //End of foreach() 
      }
      return $current_users;
    } else {
      return false;
    } //End of if else        
  } //End of get_rows()
  public function form_submit($objId = null)
  {
    if ($objId) {
      $dbRow = $this->acknowledgement_model->get_by_doc_id($objId);
      if ($dbRow) {
        if ($dbRow->form_data->step1 && $dbRow->form_data->step2 && $dbRow->form_data->step3 && !empty($dbRow->form_data->office_code)) {
          $userFilter = array('user_services.service_code' => $dbRow->service_data->service_id, 'offices_info.office_code' => $dbRow->form_data->office_code);
          $current_users = $this->get_upms_user($userFilter);
          if (empty($current_users)) {
            $this->session->set_flashdata('error', 'No department user found. Please contact support team');
            redirect('spservices/offline/acknowledgement/form/' . $objId);
          }
          $processing_history = $dbRow->processing_history ?? array();
          $processing_history[] = array(
            "processed_by" => "Application submitted by " . $dbRow->form_data->applicant_name,
            "action_taken" => "Application Submition",
            "remarks" => "Application submitted by " . $dbRow->form_data->applicant_name,
            "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
          );
          $this->acknowledgement_model->update_where(['_id' => new ObjectId($objId)], array(
            "service_data.appl_status" => "submitted",
            'service_data.submission_date'=> new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
            "processing_history" => $processing_history,
            "current_users" => $current_users
          ));

          redirect(base_url("spservices/offline/acknowledgement/download/" . $objId));
        } else {

          $this->session->set_flashdata('error', 'Please fill up the application form ');
          redirect('spservices/offline/acknowledgement/form/' . $objId);
        }
      } else {
        $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
        redirect('spservices/offline/acknowledgement/form');
      }
    }
  }
  public function preview($objId = null)
  {
    $filter = array("_id" => new ObjectId($objId));
    $dbRow = $this->acknowledgement_model->get_row($filter);
    if (count((array)$dbRow)) {
      if ($dbRow->service_data->appl_status === "submitted") {
        redirect(base_url("spservices/offline/acknowledgement/download/" . $objId));
      }
      $data = array(
        "pageTitle" => "Offline services",
        "dbrow" => $dbRow,
        "user_type" => $this->slug
      );
      $this->load->view('includes/frontend/header');
      $this->load->view('offline/ack_form_preview', $data);
      $this->load->view('includes/frontend/footer');
    } else {
      $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
      redirect('spservices/offline/acknowledgement/form');
    } //End of if else
  } //End of preview()
  function get_offices()
  {
    $service_code = $this->input->post('service_code');
    $dbRow = $this->service_model->get_offline_office_by_services($service_code);
    ?>
    <select name="office_code" id="office_code" class="form-control">
      <option value="">Select a Line Office </option>
      <?php if ($dbRow) {
        foreach ($dbRow as $office) {
          echo '<option value="' . $office['office_code'] . '">' . $office['office_name'] . '</option>';
        } //End of foreach()
      } //End of if 
      ?>
    </select>
    <?php
  } //End of get_circles()


            public function query($objId = null)
            {
              $filter = array("_id" => new ObjectId($objId));
              $dbRow = $this->acknowledgement_model->get_row($filter);
              if (count((array)$dbRow)) {
                if ($dbRow->service_data->appl_status !== "QS") {
                  $this->session->set_flashdata('errmessage', 'There is no query found');
                  redirect(base_url("iservices/transactions"));
                }
                $data = array(
                  "pageTitle" => "Query Form",
                  "dbrow" => $dbRow,
                  "user_type" => $this->slug
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('offline/query_perview', $data);
                $this->load->view('includes/frontend/footer');
              } else {
                $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
                redirect('spservices/offline/acknowledgement/form');
              } //End of if else
            }


            public function querysubmit()
            {
              $objId = $this->input->post("obj_id");
              $this->form_validation->set_rules('remarks', 'Query Remaks', 'trim|required|xss_clean|strip_tags|max_length[255]');

              $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");

              if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $this->query($objId);
              } else {

                $dbRow = $this->acknowledgement_model->get_by_doc_id($objId);
                $attachment1 = cifileupload("attachment1");
                $attachment_1 = $attachment1["upload_status"] ? $attachment1["uploaded_path"] : null;
                $processing_history = $dbRow->processing_history ?? array();
                $processing_history[] = array(
                  "processed_by" => "Query Replied by " . $dbRow->form_data->applicant_name,
                  "action_taken" => "Query Replied By APPLICANT",
                  "remarks" =>  $this->input->post('remarks'),
                  "file_uploaded" => $attachment_1 ? $attachment_1 : "",
                  "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                );

                $data_to_update = array(
                  "service_data.appl_status" => "QA",
                  "status" => "QUERY_ANSWERED",
                  "processing_history" => $processing_history
                );
                $res = $this->acknowledgement_model->update_row(['_id' => new ObjectId($objId)], $data_to_update);
                if ($res->getMatchedCount()) {

                  $this->session->set_flashdata('message', 'Query response submitted successfully');
                  redirect('iservices/transactions');
                } else {
                  $this->session->set_flashdata('error', 'Something went wrong. Please try again');
                }

                redirect('spservices/offline/acknowledgement/query/' . $objId);
              } //End of if else        
            }

            public function track($objId)
            {
              if ($objId) {
                $dbRow = $this->acknowledgement_model->get_by_doc_id($objId);
                if (count((array)$dbRow)) {
                  $data = array(
                    "pageTitle" => "Query Form",
                    "dbrow" => $dbRow,
                    "user_type" => $this->slug
                  );
                  $this->load->view('includes/frontend/header');
                  $this->load->view('offline/track', $data);
                  $this->load->view('includes/frontend/footer');
                } else {
                  $this->session->set_flashdata('errmessage', 'No records found.');
                  redirect('iservices/transactions');
                }
              } else {
                $this->session->set_flashdata('errmessage', 'No records found.');
                redirect('iservices/transactions');
              }
            }

            public function download($objid)
            {
              if ($objid) {
                if ($this->slug === "user") {
                  $applied_by = new ObjectId($this->session->userdata('userId')->{'$id'});
                  $back_to_dasboard = "<a class='btn btn-primary' href=" . base_url("iservices/transactions") . ">Back to applications</a>";
                } else {
                  if ($this->slug === "CSC") {
                    $applied_by = $sessionUser['userId'];
                  } else {
                    $applied_by = new ObjectId($this->session->userdata('userId')->{'$id'});
                  }
                  $back_to_dasboard = "<a   class='btn btn-primary' href=" . base_url("iservices/admin/my-transactions") . ">Back to applications</a>";
                }

                $filter = array(
                  '_id' => new \MongoDB\BSON\ObjectId($objid),
                  'service_data.applied_by' => $applied_by,
                  'service_data.appl_status' => "submitted"
                );
                $application = $this->acknowledgement_model->get_row($filter);
                if ($application) {
                  $this->load->view("includes/frontend/header", array("pageTitle" => "Action Form"));
                  $this->load->view("offline/ack", array('response' => $application, "back_to_dasboard" => $back_to_dasboard));
                  $this->load->view("includes/frontend/footer");
                } else {
                  $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
                  redirect(base_url("spservices/offline/acknowledgement/form"));
                }
              } else {
                $this->session->set_flashdata('error', 'No records found');
                redirect(base_url("spservices/offline/acknowledgement/form"));
              }
            }
            public function upload_certificate($objid)
            {
              die("not exists");
              if ($this->input->server('REQUEST_METHOD') == "POST") {
                if (empty($_FILES)) {
                  redirect(base_url("spservices/offline/acknowledgement/myapplications"));
                  return;
                }

                $certificate = cifileupload("certificate");
                $certificate_path = $certificate["upload_status"] ? $certificate["uploaded_path"] : null;
                if ($certificate_path) {
                  $filter = array('_id' => new \MongoDB\BSON\ObjectId($objid));
                  $data = array('form_data.certificate' => $certificate_path, 'service_data.appl_status' => "D");
                  $this->acknowledgement_model->update_row($filter, $data);
                  $this->session->set_flashdata('message', 'Certificate has been uploaded successfully');
                  redirect(base_url("spservices/offline/acknowledgement/myapplications"));
                } else {
                  $this->session->set_flashdata('message', 'Something went wrong try again.');
                  redirect(base_url("spservices/offline/acknowledgement/myapplications"));
                }
              }

              if ($objid) {
                $filter = array(
                  '_id' => new \MongoDB\BSON\ObjectId($objid),
                  'official_userid' => new \MongoDB\BSON\ObjectId(strval($this->session->userdata('userId')->{'$id'}))
                );
                $application = $this->acknowledgement_model->get_row($filter);
                $data['objId'] = $objid;
                $data['response'] = $application;

                //    pre(   $application->rtps_trans_id);
                $this->load->view("includes/office_includes/header", array("pageTitle" => "Action Form"));
                $this->load->view("offline/certificate_upload", $data);
                $this->load->view("includes/office_includes/footer");
              } else {
                redirect(base_url("spservices/dashboard"));
              }
            }


            public function myapplications()
            {
              die("not exists");
              $this->load->view('includes/office_includes/header');
              // $this->load->view('admin/transactions',$data);
              $this->load->view('offline/my_application');
              $this->load->view('includes/office_includes/footer');
            }
            public function get_records()
            {
              die("not exists");
              $this->load->model('applications_model');

              $apply_by = $this->session->userdata('userId')->{'$id'};


              $columns = array(
                '_id'
              );
              $limit = $this->input->post("length");
              $start = $this->input->post("start");
              $order = $columns[$this->input->post("order")[0]["column"]];
              $dir = $this->input->post("order")[0]["dir"];
              $totalData = 0;
              $totalData = $this->acknowledgement_model->total_app_rows($apply_by);
              $totalFiltered = $totalData;
              if (empty($this->input->post("search")["value"])) {
                $records = $this->acknowledgement_model->applications_filter($limit, $start, $apply_by, $columns, $dir,);
              } else {
                $search = trim($this->input->post("search")["value"]);
                $records = $this->acknowledgement_model->application_search_rows($limit, $start, $search, $columns, $dir, $apply_by);
                // $totalFiltered = $this->official_details_model->official_details_tot_search_rows($search);
              }

              // pre($records );
              $data = array();
              if (!empty($records)) {
                $sl = 1;
                foreach ($records as $rows) {
                  $rows = (array) $rows;
                  $nestedData["sl_no"] = $sl;
                  $obj_id = $rows['_id']->{'$id'};
                  $nestedData["service_name"] =  $rows['service_data']->service_name;
                  $nestedData["applicant_name"] =  $rows['form_data']->applicant_name;
                  $nestedData["mobile"] =  $rows['form_data']->mobile_number;
                  $nestedData["rtps_trans_id"] =  $rows['form_data']->rtps_trans_id;
                  $nestedData["app_ref_no"] =  isset($rows['service_data']->appl_ref_no) ? $rows['service_data']->appl_ref_no : '';

                  $nestedData["status"] = $rows['service_data']->appl_status;
                  $btns = '<a target="_blank" href="' . base_url("spservices/offline/acknowledgement/download/" . $obj_id) . '" data-toggle="tooltip" data-placement="top" title="Acknowledgement" class="btn btn-sm btn-success mb-1" >Acknowledgement</a> ';

                  if (isset($rows['form_data']->certificate) && !empty($rows['form_data']->certificate)) {
                    $btns .= '<a target="_blank" href="' . base_url($rows['form_data']->certificate) . '" data-toggle="tooltip" data-placement="top" title="Acknowledgement" class="btn btn-sm btn-primary mb-1" >View Certificate</a> ';
                  } else {
                    $btns .= '<a target="_blank" href="' . base_url("spservices/offline/acknowledgement/upload_certificate/" . $obj_id) . '" data-toggle="tooltip" data-placement="top" title="Acknowledgement" class="btn btn-sm btn-primary mb-1" >Upload Certificate</a> ';
                  }


                  $nestedData['action'] = $btns;

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
          }
