<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Eodb extends Rtps
{
  private $splusEncryptionApi;
  private $rtpsAuthenticationApi;
  private $eodbAuthenticationApi;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('eodb/eodb_intermediator_model');
    $this->load->model('eodb/portals_model');
    $this->load->model('iservices/admin/users_model');
    $this->config->load('rtps_services');
    $this->splusEncryptionApi = $this->config->item('serviceplus_encryption_api');
    $this->rtpsAuthenticationApi = $this->config->item('rtpsAuthenticationApi');
    $this->eodbAuthenticationApi = $this->config->item('eodbAuthenticationApi');
    $this->load->library('AES');
    $this->load->helper("log");
    $this->encryption_key = $this->config->item("encryption_key");
    $this->load->helper('download');
    if (!empty($this->session->userdata('role'))) {
      $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
    } else {
      $this->slug = "user";
    }
  }

  public function guidelines($service_id = null, $portal_no = null)
  {
    if (empty($service_id) || empty($portal_no) || ($portal_no != 12 && $portal_no != 13)) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'response_data' => "Invalid service",

        )));
      return;
    }
    $data = array("pageTitle" => "Guidelines");
    $data['service_id'] = $service_id;
    $data['portal_no'] = $portal_no;
    $data['obj_id'] = '';

    $guidelines = $this->portals_model->get_guidelines($service_id);
    if ($guidelines) {
      $get_token = $this->authentication_api_call($service_id, $portal_no);
      if (isset($get_token['reference_no']) && isset($get_token['token'])) {
        $data['reference_no'] = $get_token['reference_no'];
        $data['token'] = $get_token['token'];
        $data['service_name'] = $guidelines->service_name;
        $data['guidelines'] = isset($guidelines->guidelines) ? $guidelines->guidelines : array();
        $data['url'] = isset($guidelines->url) ? $guidelines->url : '';
        // if (!empty($this->session->userdata('role')) && ($this->slug === "SA" || $this->slug === "PFC" || $this->slug === "CSC")) {
        //   $data['apply_by'] = "PFC";
        //   $this->load->view('includes/header');
        //   $this->load->view('eodb/admin_guidelines', $data);
        //   $this->load->view('includes/footer');
        // } else {
        $this->load->view('includes/frontend/header');
        $this->load->view('eodb/guidelines', $data);
        $this->load->view('includes/frontend/footer');
        //}
      } else {
        die("ERROR-0006 : Authentication failed, please try later!");
      }
    } else {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'response_data' => "Invalid service",

        )));
      return;
    }
  }

  public function incomplete_application($obj_id = null)
  {
    if ($this->checkObjectId($obj_id)) {
      //$filter = array("_id" => new ObjectId($obj_id), "appl_status" => "S");
      $filter = array("_id" => new ObjectId($obj_id),  "appl_status" => ['$in' => ["S", "W"]]);

      $dbRow = $this->eodb_intermediator_model->get_row($filter);
      if (!empty($dbRow)) {
        $guidelines = $this->portals_model->get_guidelines($dbRow->service_id);
        if ($guidelines) {
          $get_token = $this->authentication_api_call($dbRow->service_id, $dbRow->portal_no);
          if (isset($get_token['reference_no']) && isset($get_token['token'])) {
            $data['reference_no'] = $get_token['reference_no'];
            $data['token'] = $get_token['token'];
            $data['service_name'] = $dbRow->service_name;
            $data['service_id'] = $dbRow->service_id;
            $data['portal_no'] = $dbRow->portal_no;
            $data['guidelines'] = isset($dbRow->guidelines) ? $dbRow->guidelines : array();
            $data['url'] = isset($guidelines->url) ? $guidelines->url : '';

            $data['obj_id'] = $obj_id;
            if (!empty($this->session->userdata('role')) && ($this->slug === "SA" || $this->slug === "PFC" || $this->slug === "CSC")) {
              $data['apply_by'] = "PFC";
              // $this->load->view('includes/header');
              // $this->load->view('eodb/admin_guidelines', $data);
              // $this->load->view('includes/footer');
              $this->load->view('includes/frontend/header');
              $this->load->view('eodb/guidelines', $data);
              $this->load->view('includes/frontend/footer');
            } else {
              $this->load->view('includes/frontend/header');
              $this->load->view('eodb/guidelines', $data);
              $this->load->view('includes/frontend/footer');
            }
          } else {
            $this->session->set_flashdata('error', 'Authentication failed, please try later!');
            $this->my_transactions();
          }
        } else {
          $this->session->set_flashdata('error', 'Invalid Client Id!');
          $this->my_transactions();
        }
      } else {
        $this->session->set_flashdata('error', 'No application found in draft mood!');
        $this->my_transactions();
      }
    } else {
      $this->session->set_flashdata('error', 'Invalid Application Id!');
      $this->my_transactions();
    }
  }

  function generateRandomString($length = 7)
  {
    $number = '';
    for ($i = 0; $i < $length; $i++) {
      $number .= rand(0, 9);
    }
    return (int)$number;
  }

  public function generate_id()
  {
    $date = date('ydm');
    $str = "AS" . $date . "A" . $this->generateRandomString(7);
    return $str;
  }

  public function procced()
  {
    $service_id = $this->input->post('service_id');
    $service_name = $this->input->post('service_name');
    $portal_no = $this->input->post('portal_no');
    $reference_no = $this->input->post('reference_no');
    $token = $this->input->post('token');
    $base_url = $this->input->post('url');
    $obj_id = $this->input->post('obj_id');
    $user = $this->session->userdata();

    $service_details = $this->portals_model->get_guidelines($service_id);
    if (empty($service_details)) {
      return false;
    }
    $external_service_id = property_exists($service_details, "external_service_id") ? $service_details->external_service_id : $service_details->service_id;
    if (!empty($this->session->userdata('role'))) {
      if ($this->slug === "CSC") {
        $role_id = "CSC";
        $kiosk_type_id = "1510";
        $apply_by = $user['userId'];
      } else {
        $kiosk = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
        if ($kiosk->type_of_kiosk == "eDistrict") {
          $role_id = "eDistrict";
          $kiosk_type_id = "1700";
        } else {
          $role_id = "PFC";
          $kiosk_type_id = "1600";
        }
        $apply_by = $this->session->userdata('userId')->{'$id'};
      }
    } else {
      $apply_by = $this->session->userdata('userId')->{'$id'};
      $role_id = "";
      $kiosk_type_id = "";
    }
    $dbRow = "";
    if (!empty($obj_id)) {
      $filter = array("_id" => new ObjectId($obj_id), "appl_status" => ['$in' => ["S", "W"]]);
      $dbRow = $this->eodb_intermediator_model->get_row($filter);
    }
    if (!empty($dbRow)) {
      $data = array("input" => array(
        "serviceID" => $external_service_id,
        "Data" => array("role_id" => $role_id, "kiosk_type_id" => $kiosk_type_id, "apply_by" => $apply_by),
        "Draft" => $dbRow->draft_application->draft_ref_no,
      ));

      //pre($json_obj);
      //to be called the encrypted_string API here..!
      $json_data = json_encode($data);
      $enc = $this->serviceplus_encryption_api($json_data, $token);
      $post_params = "?reference_no=" . $reference_no . "&encrypted_details=" . $enc['encryted_string'] . "&pick_from_draft=Y";
      $target_url = $base_url . $post_params;

      $updated_data = array(
        'reference_no' => $reference_no,
        'token' => $token,
        'target_url' => $target_url,
      );
      $res = $this->eodb_intermediator_model->update_where(['_id' => new ObjectId($obj_id)], $updated_data);
      $res = true;
    } else {
      $data = array("input" => array(
        "serviceID" => $external_service_id,
        "Data" => array("role_id" => $role_id, "kiosk_type_id" => $kiosk_type_id, "apply_by" => $apply_by),
      ));
      //to be called the encrypted_string API here..!
      $json_data = json_encode($data);
      // pre($json_data);
      $enc = $this->serviceplus_encryption_api($json_data, $token);
      $post_params = "?reference_no=" . $reference_no . "&encrypted_details=" . $enc['encryted_string'];
      $target_url = $base_url . $post_params;
      $user_data = array(
        //'user_id' => $user['userId']->{'$id'},
        //'user_id' => $apply_by,
        //'user_id' => new ObjectId($apply_by), 
        'user_id' => $this->slug === "CSC" ? $user['userId'] : new ObjectId($apply_by), 
        'user_type' => $this->slug,
        'role_id' => $role_id,
        'kiosk_type_id' => $kiosk_type_id,
        'mobile' => isset($user['mobile']) ? $user['mobile'] : "NA",
        "service_name" => $service_name,
        "portal_no" => $portal_no,
        "service_id" => intval($service_id),
        "external_service_id" => $external_service_id,
        'reference_no' => $reference_no,
        'token' => $token,
        'target_url' => $target_url,
        'appl_wise_tiny_url' => "",
        "appl_status" => "P",
        "createdDtm" => new \MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
      );
      $res = $this->eodb_intermediator_model->insert($user_data);
    }

    $status = array();
    if ($res) {
      $status["status"] = true;
      $status["url"] = $base_url;
      $status["encrypted_data"] = $post_params;
      $status["message"] = "Need to redirect to third party urls";
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    } else {
      $status["status"] = false;
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    }
  }

  private function checkObjectId($obj)
  {
    if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
      return true;
    } else {
      return false;
    } //End of if else
  } //End of checkObjectId()

  private function authentication_api_call($service_id = null, $portal_no = null)
  {
    // Prepare new cURL resource
    if ($portal_no == 12)
      $curl = curl_init($this->eodbAuthenticationApi);
    else if ($portal_no == 13)
      $curl = curl_init($this->rtpsAuthenticationApi);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json",
      "client_id: " . $service_id,
      "username: 25718",
    ));

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
      $error_msg = curl_error($curl);
    }
    curl_close($curl);
    if (isset($error_msg)) {
      die("ERROR-0001 : " . $error_msg);
    } else if (!empty($response)) {
      $response_arr = json_decode($response);
      if (isset($response_arr->reference_no) && isset($response_arr->token)) {
        return  array(
          'reference_no' => $response_arr->reference_no,
          'token' => $response_arr->token
        );
      }
    } else {
      die("ERROR-0002 : Authentication failed, please try later!");
    }
  }

  private function serviceplus_encryption_api($json_data = null, $token = null)
  {
    $json_obj = array(
      "data" =>  $json_data,
      "token" => $token
    );
    $postdata = json_encode($json_obj);
    $curl = curl_init($this->splusEncryptionApi);
    //$curl = curl_init('http://localhost:8080/EncryptionModule/api/encrypt/');
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

    // $buffer = preg_replace("/\r|\n/", "", $postdata);
    // $myfile = fopen("D:\\TESTDATA\\serviceplus_encryption_api.txt", "a") or die("Unable to open file!");
    // fwrite($myfile, $buffer);
    // fclose($myfile);
    // die;

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
      $error_msg = curl_error($curl);
    }
    curl_close($curl);
    if (isset($error_msg)) {
      die("ERROR-0003 : " . $error_msg);
    } else if ($response) {
      $response_arr = json_decode($response);
      if (!empty($response_arr)) {
        if (isset($response_arr->resString)) {
          return  array(
            'encryted_string' => $response_arr->resString,
          );
        } else {
          die("ERROR-0007 : Authentication failed, please try later!");
        }
      } else {
        die("ERROR-0004 : Authentication failed, please try later!");
      }
    } else {
      die("ERROR-0005 : Authentication failed, please try later!");
    }
  }

  public function update_tiny_url($appl_id = null)
  {
    if (!empty($appl_id)) {
      $ref = modules::load('iservices/eodb/eodb_response');
      $action_tiny_url = $ref->get_tiny_url($appl_id);
      if (isset($action_tiny_url['appl_wise_tiny_url'])) {
        $inputs = array(
          "appl_wise_tiny_url" => $action_tiny_url['appl_wise_tiny_url']
        );
        $dbrow = $this->eodb_intermediator_model->get_row(array('submitted_application.application_id' => (int)$appl_id));
        if (!empty($dbrow)) {         
          $this->eodb_intermediator_model ->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $inputs);
          $this->session->set_flashdata('success', 'Your application has been successfully submitted.!');
          $this->my_transactions();
        }
      } else {
        $this->session->set_flashdata('error', 'Process not completed. Please retry to submit!');
        $this->my_transactions();
      }
    }
  }

  public function track_application($refNo = null)
  {
    //pre($this->input->GET('appl_ref_no'));
    $dbRow = $this->eodb_intermediator_model->get_row(["reference_no" => $refNo]);
    if (!empty($dbRow)) {
      pre($dbRow);
    } else {
      pre('No records found against object id : ' . $refNo);
    } //End of if else
  }

  public function track($objId = null)
  {
    // pre($objId);
    $dbRow = $this->eodb_intermediator_model->get_by_doc_id($objId);
    if (count((array)$dbRow) && isset($dbRow->submitted_application->draft_ref_no)) {
      $processing_history = $this->retrive_processing_history($dbRow->submitted_application->draft_ref_no, $dbRow->external_service_id);
      if (isset($processing_history->data->initiated_data) && isset($processing_history->data->execution_data) && !empty($dbRow->appl_wise_tiny_url)) {
        $this->load->view('includes/frontend/header');
        $this->load->view('eodb/eodbtrack_view', array('data' => $processing_history->data->initiated_data, 'execution_data' => $processing_history->data->execution_data, 'action_tiny_url' => $dbRow->appl_wise_tiny_url, 'object_id' => $objId));
        $this->load->view('includes/frontend/footer');
      } else {
        $this->session->set_flashdata('error', 'Unable to track your application, please try after sometime!');
        $this->my_transactions();
      }
    } else {
      $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
      $this->my_transactions();
    } //End of if else
  } //End of track()

  public function view_form_data($objId = null)
  {
    $dbRow = $this->eodb_intermediator_model->get_by_doc_id($objId);
    if (count((array)$dbRow) && isset($dbRow->submitted_application->draft_ref_no)) {
      $processing_history = $this->retrive_processing_history($dbRow->submitted_application->draft_ref_no, $dbRow->external_service_id);
      if (isset($processing_history->data->initiated_data->attribute_details) && isset($processing_history->data->initiated_data->enclosure_details) && ($processing_history->data->initiated_data)) {
        $this->load->view('includes/frontend/header');
        $this->load->view("eodb/attributedetailss", array('data' => $processing_history->data->initiated_data, "attribute_details" => $processing_history->data->initiated_data->attribute_details, "enclosure_details" => $processing_history->data->initiated_data->enclosure_details, 'object_id' => $objId));
        $this->load->view('includes/frontend/footer');
      } else {
        $this->session->set_flashdata('error', 'No records found.!');
        $this->my_transactions();
      }
    } else {
      $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
      $this->my_transactions();
    } //End of if else

  }

  public function download_certificate($current_process_id = null, $objId = null)
  {
    if (!empty($current_process_id) && !empty($objId)) {
      $dbRow = $this->eodb_intermediator_model->get_by_doc_id($objId);
      if (count((array)$dbRow) && isset($dbRow->submitted_application->draft_ref_no)) {
        $processing_history = $this->retrive_processing_history($dbRow->submitted_application->draft_ref_no, $dbRow->external_service_id);

        if (isset($processing_history->data->initiated_data)) {
          $i = 0;
          $certs = $processing_history->data->initiated_data->certs;
          foreach ($certs as $cert) {
            if ($cert->application_current_process_id == $current_process_id && file_exists($cert->file_name)) {
              $doc_file_path =  $cert->file_name;
              $doc_file_name =  $cert->application_current_process_id;
              $b64encode = base64_encode(file_get_contents($doc_file_path));
              $b64decode = base64_decode($b64encode);

              $finfo = finfo_open();
              $mime_type = finfo_buffer($finfo, $b64decode, FILEINFO_MIME_TYPE);
              finfo_close($finfo);
              $ext = (get_file_extension($mime_type) == 'N/A') ? '' : get_file_extension($mime_type);

              // redirect output to client browser
              header("Content-type: {$mime_type}");
              header('Content-Disposition: attachment;filename="cert_' . $doc_file_name . ".{$ext}");
              header('Cache-Control: max-age=0');
              echo $b64decode;
              //force_download($doc_file_name, $b64decode);
              $i = 1;
              break;
            }
          }
          if ($i == 0) {
            $this->session->set_flashdata('errmessage', 'File does not exist.!');
            redirect('iservices/serviceplus/track/' . $objId);
          }
        } else {
          $this->session->set_flashdata('errmessage', 'Unable to view/download the document !');
          redirect('iservices/serviceplus/track/' . $objId);
        }
      } else {
        $this->session->set_flashdata('errmessage', 'No records found !');
        redirect('iservices/serviceplus/track/' . $objId);
      }
    }
  }

  public function download_annexures($iterate_val = null, $objId = null)
  {
    if (!empty($iterate_val) && !empty($objId)) {
      $dbRow = $this->eodb_intermediator_model->get_by_doc_id($objId);
      if (count((array)$dbRow) && isset($dbRow->submitted_application->draft_ref_no)) {
        $processing_history = $this->retrive_processing_history($dbRow->submitted_application->draft_ref_no, $dbRow->external_service_id);
        if (isset($processing_history->data->initiated_data->enclosure_details)) {
          $enclosures = $processing_history->data->initiated_data->enclosure_details;
          $i = 1;
          $j = 0;
          foreach ($enclosures as $key => $value) {
            if ($iterate_val == $i) {
              foreach ($value as $sub_key => $sub_val) {
                $sub_val = trim($sub_val);
                if ($sub_key == "file_path" && file_exists($sub_val)) {
                  $annex_file_path =  $sub_val; //file_path
                  //$annex_file_name =  'Annexure_' . $i;
                  $b64encode = base64_encode(file_get_contents($annex_file_path));
                  $b64decode = base64_decode($b64encode);

                  $finfo = finfo_open();
                  $mime_type = finfo_buffer($finfo, $b64decode, FILEINFO_MIME_TYPE);
                  finfo_close($finfo);
                  $ext = (get_file_extension($mime_type) == 'N/A') ? '' : get_file_extension($mime_type);
    
                  // redirect output to client browser
                  header("Content-type: {$mime_type}");
                  header('Content-Disposition: attachment;filename="Annexure_' . $i . ".{$ext}");
                  header('Cache-Control: max-age=0');
                  echo $b64decode;

                  //force_download($annex_file_name, $b64decode);
                  $j = 1;
                  break;
                }
              }
            }
            $i++;
          }
          if ($j == 0) {
            $this->session->set_flashdata('errmessage', 'File does not exist.!');
            redirect('iservices/serviceplus/view_form_data/' . $objId);
          }
        } else {
          $this->session->set_flashdata('errmessage', 'Unable to view/download the document !');
          redirect('iservices/serviceplus/view_form_data/' . $objId);
        }
      } else {
        $this->session->set_flashdata('errmessage', 'No records found !');
        redirect('iservices/serviceplus/view_form_data/' . $objId);
      }
    }
  }

  public function retrive_processing_history($appl_ref_no = null, $service_id = null)
  {
    $url = "https://rtps.assam.gov.in/tools/rtps_id_labels/src/api/external_apis.php/get_track_data";
    $data = http_build_query(array(
      'appl_ref_no' => $appl_ref_no,
      'service_id'  => $service_id
    ));
    $getUrl = $url . "?" . $data;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: multipart/form-data',
      'Authorization: Bearer |0VW?z,-w2w"6;{b8v}K6A5+Fdf@l-',
    ));
    //curl_setopt($curl, CURLOPT_POST, true);
    //curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($curl, CURLOPT_URL, $getUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    $response = curl_exec($curl);
    if (curl_errno($curl)) {
      $error_msg = curl_error($curl);
    }
    curl_close($curl);
    log_response($appl_ref_no, $response);
    if (isset($error_msg)) {
      die("ERROR-0008 : " . $error_msg);
    } elseif ($response) {
      $response_arr = json_decode($response);
      if (!empty($response_arr) && isset($response_arr->data)) {
        return  $response_arr;
      }
    } else {
      die("ERROR-0009 : Connection Failed..!");
    }
  }

  public function my_transactions()
  {
    $user = $this->session->userdata();
    if (isset($user['role']) && !empty($user['role'])) {
      redirect(base_url('iservices/admin/my-transactions'));
    } else {
      redirect(base_url('iservices/transactions'));
    }
  }
}
