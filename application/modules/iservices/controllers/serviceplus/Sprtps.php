<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Sprtps extends Rtps
{
  private $splusGetPendingApplications;
  private $splusGetDeliveredApplications;
  private $splusTrackURL;
  public function __construct()
  {
    parent::__construct();
    $this->load->model('eodb/eodb_intermediator_model');
    $this->load->model('eodb/portals_model');
    $this->load->model('iservices/admin/users_model');
    $this->config->load('rtps_services');


    $this->splusGetPendingApplications = $this->config->item('splusGetPendingApplications');
    $this->splusGetDeliveredApplications = $this->config->item('splusGetDeliveredApplications');
    $this->splusTrackURL = $this->config->item('splusTrackURL');

    $this->load->library('AES');
    $this->load->helper("log");
    $this->load->helper('download');
    if (!empty($this->session->userdata('role'))) {
      $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
    } else {
      $this->slug = "user";
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

  public function get_sp_pending_applications($portal = null)
  {
    $user1 = $this->session->userdata();
    $sign_role = "";
    $sign_no = "";
    if (isset($user1['role']) && !empty($user1['role'])) {
      if ($this->slug === "CSC") {
        $sign_no = $user1['userId'];
        $sign_role = "CSC";
        $json_obj = array(
          "sign_role" =>  $sign_role,
          "sign_no" => $sign_no,
          "portal" => $portal
        );
      } else if ($this->session->userdata('role')->slug === "PFC" || $this->slug == "SA") {
        $userInfo = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
        $email = $userInfo->email;
        $sign_no = new ObjectId($this->session->userdata('userId')->{'$id'});
        $sign_role = "PFC";
        $json_obj = array(
          "sign_role" =>  $sign_role,
          "sign_no" => $email,
          "portal" => $portal
        );
      }
    } else {
      $sign_no = $this->session->userdata("mobile");
      $sign_role = "CTZN";
      $json_obj = array(
        "sign_role" =>  $sign_role,
        "sign_no" => $sign_no,
        "portal" => $portal
      );
    }

    $postdata = json_encode($json_obj); //pre($postdata);
    $curl = curl_init($this->splusGetPendingApplications);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Authorization: Bearer |0VW?z,-w2w"6;{b8v}K6A5+Fdf@l-',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

    $response = curl_exec($curl);
    // pre($response);
    if (curl_errno($curl)) {
      $error_msg = curl_error($curl);
    }
    curl_close($curl);
    if (isset($error_msg)) {
      return true;
    } else if ($response) {

      $response_arr = json_decode($response);
      if (!empty($response_arr)) {
        if ($response_arr->status == 1) {
          foreach ($response_arr->data->applications as $application) {
            $iservices_service_id = $this->getIservicesId($application->base_service_id);
            $user_data = [
              "sign_no" => $sign_no,
              "sign_role" => $sign_role
            ];
            $form_data = [
              'portal' => $portal,
              'base_service_id' => $application->base_service_id,
              'appl_id' => $application->appl_id,
              'appl_ref_no' => $application->appl_ref_no,
              'service_name' => $application->sp_service_name,
              'applicant_name' => $application->applicant_name,
              'mobile' => $application->mobile_no,
              "appl_status" => $application->appl_status,
              'application_date' => $application->spdv_application_date,
              "sign_role" => $application->sign_role,
              "service_plus_data" => true,
              "createdDtm" => new \MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            ];
            $service_data = [
              "service_id" => $iservices_service_id ?? $application->base_service_id,
              "service_name" => $application->sp_service_name,
              "appl_ref_no" => $application->appl_ref_no,
              "applied_by" => $sign_no,
              "appl_status" => $application->appl_status,
            ];
            $inputs = [
              'user_data' => $user_data,
              'service_data' => $service_data,
              'form_data' => $form_data,
            ];
            $option = array('upsert' => true);
            $this->mongo_db->where(array('form_data.portal' => $portal, 'form_data.appl_id' => $application->appl_id, 'form_data.service_plus_data' => true))->set($inputs)->update('sp_applications', $option);
          }
          if ($portal == "RTPS")
            $this->session->set_userdata('curl_executed_sp_rtps_p', true);
          else
            $this->session->set_userdata('curl_executed_sp_eodb_p', true);
        }
      }
    }
    return true;
  }

  public function get_sp_delivered_applications($portal = null)
  {
    $user1 = $this->session->userdata();
    $sign_role = "";
    $sign_no = "";
    if (isset($user1['role']) && !empty($user1['role'])) {
      //if ($this->session->userdata('role')->slug == "CSC") {
      if ($this->slug === "CSC") {
        $sign_no = $user1['userId'];
        $sign_role = "CSC";
        $json_obj = array(
          "sign_role" =>  $sign_role,
          "sign_no" => $sign_no,
          "portal" => $portal
        );
      } else if ($this->session->userdata('role')->slug === "PFC" || $this->slug == "SA") {
        $userInfo = $this->users_model->get_by_id($this->session->userdata("userId")->{'$id'});
        $email = $userInfo->email;
        // pre($email);
        $sign_no = new ObjectId($this->session->userdata('userId')->{'$id'});
        $sign_role = "PFC";
        $json_obj = array(
          "sign_role" =>  $sign_role,
          "sign_no" => $email,
          "portal" => $portal
        );
      }
    } else {
      $sign_no = $this->session->userdata("mobile");
      $sign_role = "CTZN";
      $json_obj = array(
        "sign_role" =>  $sign_role,
        "sign_no" => $sign_no,
        "portal" => $portal
      );
    }

    // pre($json_obj);
    $postdata = json_encode($json_obj);
    $curl = curl_init($this->splusGetDeliveredApplications);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Authorization: Bearer |0VW?z,-w2w"6;{b8v}K6A5+Fdf@l-',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

    $response = curl_exec($curl);
    // pre($response);
    if (curl_errno($curl)) {
      $error_msg = curl_error($curl);
    }
    curl_close($curl);
    if (isset($error_msg)) {
      return true;
    } else if ($response) {
      // pre($response);
      $response_arr = json_decode($response);
      if (!empty($response_arr)) {
        if ($response_arr->status == 1) {
          foreach ($response_arr->data->applications as $application) {
            $iservices_service_id = $this->getIservicesId($application->base_service_id);
            $user_data = [
              "sign_no" => $sign_no,
              "sign_role" => $sign_role
            ];
            $form_data = [
              'portal' => $portal,
              'base_service_id' => $application->base_service_id,
              'appl_id' => $application->appl_id,
              'appl_ref_no' => $application->appl_ref_no,
              'service_name' => $application->sp_service_name,
              'applicant_name' => $application->applicant_name,
              'mobile' => $application->mobile_no,
              "appl_status" => $application->appl_status,
              'application_date' => $application->spdv_application_date,
              "sign_role" => $application->sign_role,
              "service_plus_data" => true,
              "createdDtm" => new \MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            ];
            $service_data = [
              "service_id" => $iservices_service_id ?? $application->base_service_id,
              "service_name" => $application->sp_service_name,
              "appl_ref_no" => $application->appl_ref_no,
              "applied_by" => $sign_no,
              "appl_status" => $application->appl_status,
            ];
            $inputs = [
              'user_data' => $user_data,
              'service_data' => $service_data,
              'form_data' => $form_data,
            ];

            if ($application->base_service_id == 1104) {
              //CERTIFIED COPY OF REGISTERED DEED SERVICE
              $inputs['service_id'] = $iservices_service_id ?? $application->base_service_id;
              $inputs['service_name'] = $application->sp_service_name;
              $inputs['rtps_trans_id'] = $application->appl_ref_no;
              $inputs['mobile'] = $application->mobile_no;
              $inputs['applied_by'] = $sign_no;
              $inputs['status'] = $application->appl_status;
            } else if ($application->base_service_id == 1205) {
              //MARRIAGE CERTIFICATE
              $inputs['service_id'] = $iservices_service_id ?? $application->base_service_id;
              $inputs['service_name'] = $application->sp_service_name;
              $inputs['rtps_trans_id'] = $application->appl_ref_no;
              $inputs['applicant_mobile_number'] = $application->mobile_no;
              $inputs['applied_by'] = $sign_no;
              $inputs['status'] = $application->appl_status;
            } else if ($application->base_service_id == 1396) {
              //NEC CERTIFICATE
              $inputs['service_id'] = $iservices_service_id ?? $application->base_service_id;
              $inputs['service_name'] = $application->sp_service_name;
              $inputs['rtps_trans_id'] = $application->appl_ref_no;
              $inputs['mobile'] = $application->mobile_no;
              $inputs['applied_by'] = $sign_no;
              $inputs['status'] = $application->appl_status;
            }
            $option = array('upsert' => true);
            $this->mongo_db->where(array('form_data.portal' => $portal, 'form_data.appl_id' => $application->appl_id, 'form_data.service_plus_data' => true))->set($inputs)->update('sp_applications', $option);
          }
          if ($portal == "RTPS")
            $this->session->set_userdata('curl_executed_sp_rtps_d', true);
          else
            $this->session->set_userdata('curl_executed_sp_rtps_d', true);
        }
      }
    }
    return true;
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
          $this->eodb_intermediator_model->update_where(['_id' => new ObjectId($dbrow->_id->{'$id'})], $inputs);
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
    if ($this->checkObjectId($objId)) {
      $dbRow = $this->eodb_intermediator_model->get_by_doc_id($objId);
    } else {
      // Decode the parameters
      $decodedParams = base64_decode($objId);
      list($decodedParam1, $decodedParam2) = explode(',', $decodedParams);
      $dbRow = new stdClass();
      $formDataObject = new stdClass();
      // Set the properties of form_data
      $formDataObject->base_service_id = $decodedParam1;
      $formDataObject->appl_ref_no = $decodedParam2;

      // Set the form_data object as a property of the outer object
      $dbRow->form_data = $formDataObject;
    }
    // pre($dbRow);








    if (count((array)$dbRow) && isset($dbRow->form_data->appl_ref_no)) {
      $processing_history = $this->retrive_processing_history($dbRow->form_data->appl_ref_no, $dbRow->form_data->base_service_id);
      // var_dump($processing_history); die;
      if (isset($processing_history->data->initiated_data) && isset($processing_history->data->execution_data)) {

        $this->load->view('includes/frontend/header');
        $this->load->view('serviceplus/sprtpstrack_view', array('data' => $processing_history->data->initiated_data, 'execution_data' => $processing_history->data->execution_data, 'object_id' => $objId));
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

    // pre($objId);
    if ($this->checkObjectId($objId)) {
      $dbRow = $this->eodb_intermediator_model->get_by_doc_id($objId);
    } else {
      // Decode the parameters
      $decodedParams = base64_decode($objId);
      list($decodedParam1, $decodedParam2) = explode(',', $decodedParams);
      $dbRow = new stdClass();
      $formDataObject = new stdClass();
      // Set the properties of form_data
      $formDataObject->base_service_id = $decodedParam1;
      $formDataObject->appl_ref_no = $decodedParam2;

      // Set the form_data object as a property of the outer object
      $dbRow->form_data = $formDataObject;
    }
    // pre($dbRow);




    if (count((array)$dbRow) && isset($dbRow->form_data->appl_ref_no)) {
      $processing_history = $this->retrive_processing_history($dbRow->form_data->appl_ref_no, $dbRow->form_data->base_service_id);
      if (isset($processing_history->data->initiated_data->attribute_details) && isset($processing_history->data->initiated_data->enclosure_details) && ($processing_history->data->initiated_data)) {
        $this->load->view('includes/frontend/header');
        $this->load->view("serviceplus/rtps_attributedetailss", array('data' => $processing_history->data->initiated_data, "attribute_details" => $processing_history->data->initiated_data->attribute_details, "enclosure_details" => $processing_history->data->initiated_data->enclosure_details, 'object_id' => $objId));
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
      if ($this->checkObjectId($objId)) {
        $dbRow = $this->eodb_intermediator_model->get_by_doc_id($objId);
      } else {
        // Decode the parameters
        $decodedParams = base64_decode($objId);
        list($decodedParam1, $decodedParam2) = explode(',', $decodedParams);
        $dbRow = new stdClass();
        $formDataObject = new stdClass();
        // Set the properties of form_data
        $formDataObject->base_service_id = $decodedParam1;
        $formDataObject->appl_ref_no = $decodedParam2;
  
        // Set the form_data object as a property of the outer object
        $dbRow->form_data = $formDataObject;
      }
      // pre($dbRow);









      if (count((array)$dbRow) && isset($dbRow->form_data->appl_ref_no)) {
        $processing_history = $this->retrive_processing_history($dbRow->form_data->appl_ref_no, $dbRow->form_data->base_service_id);

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
            redirect('iservices/serviceplus/rtps_track/' . $objId);
          }
        } else {
          $this->session->set_flashdata('errmessage', 'Unable to view/download the document !');
          redirect('iservices/serviceplus/rtps_track/' . $objId);
        }
      } else {
        $this->session->set_flashdata('errmessage', 'No records found !');
        redirect('iservices/serviceplus/rtps_track/' . $objId);
      }
    }
  }

  public function download_annexures($iterate_val = null, $objId = null)
  {
    if (!empty($iterate_val) && !empty($objId)) {
      if ($this->checkObjectId($objId)) {
        $dbRow = $this->eodb_intermediator_model->get_by_doc_id($objId);
      } else {
        // Decode the parameters
        $decodedParams = base64_decode($objId);
        list($decodedParam1, $decodedParam2) = explode(',', $decodedParams);
        $dbRow = new stdClass();
        $formDataObject = new stdClass();
        // Set the properties of form_data
        $formDataObject->base_service_id = $decodedParam1;
        $formDataObject->appl_ref_no = $decodedParam2;
  
        // Set the form_data object as a property of the outer object
        $dbRow->form_data = $formDataObject;
      }
      // pre($dbRow);












      if (count((array)$dbRow) && isset($dbRow->form_data->appl_ref_no)) {
        $processing_history = $this->retrive_processing_history($dbRow->form_data->appl_ref_no, $dbRow->form_data->base_service_id);
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
            redirect('iservices/serviceplus/view_rtps_form_data/' . $objId);
          }
        } else {
          $this->session->set_flashdata('errmessage', 'Unable to view/download the document !');
          redirect('iservices/serviceplus/view_rtps_form_data/' . $objId);
        }
      } else {
        $this->session->set_flashdata('errmessage', 'No records found !');
        redirect('iservices/serviceplus/view_rtps_form_data/' . $objId);
      }
    }
  }


  public function retrive_processing_history($appl_ref_no = null, $service_id = null)
  {
    // echo $appl_ref_no; pre($service_id);
    //$url = "https://rtps.assam.gov.in/tools/rtps_id_labels/src/api/external_apis.php/get_track_data";
    $url = $this->splusTrackURL;
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
    // var_dump($response); die;
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

  function getIservicesId($base_service_id)
  {
    // Define the array as a key-value structure
    $services = array(
      1686 => 'SCTZN',
      1657 => 'NOKIN',
      1104 => 'CRCPY',
      1396 => 'NECERTIFICATE',
      1409 => 'MUTATION_ORDER',
      1881 => 'PDBR',
      1885 => 'PDDR',
      977 =>  'APPOINTMENT_BOOKING',
      1846 => 'BAKCL',
      1886 => 'PPBP',
      1205 => 'MARRIAGE_REGISTRATION',
    );

    // Check if the base_service_id key exists in the array
    if (array_key_exists($base_service_id, $services)) {
      // Return the corresponding service name value
      return $services[$base_service_id];
    } else {
      // Return null if the base_service_id is not found
      return null;
    }
  }

  public function autosubmit($objId = null)
  {
    // $this->load->view('includes/frontend/header');
    $data['user_type'] = $this->slug;
    $this->load->view("serviceplus/autosubmit", $data);
    // $this->load->view('includes/frontend/footer');
  }

  private function checkObjectId($obj)
  {
    if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
      return true;
    } else {
      return false;
    } //End of if else
  } //End of checkObjectId()
}
