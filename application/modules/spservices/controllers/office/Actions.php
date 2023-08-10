
<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Actions extends Rtps
{

  //put your code here
  public function __construct()
  {
    parent::__construct();
    $this->load->model("office/application_model");
    $this->load->model('office/task_model');
    $this->load->helper("cifileupload");
    $this->load->library('../modules/spservices/controllers/office/sms_scheduler');

    if ($this->session->userdata()) {
      if ($this->session->userdata('isAdmin') === TRUE) {
      } else {
        $this->session->sess_destroy();
        redirect('spservices/mcc/user-login');
      }
    } else {
      redirect('spservices/mcc/user-login');
    }
  }

  public function index($id)
  {
    $ref_no = base64_decode($id);
    $application_data = (array)$this->application_model->get_single_application($ref_no);
    $service_id = $application_data[0]->service_data->service_id;
    foreach ($application_data as $val) {
      if (count($val->execution_data) == 1) {
        $task_id = '2';
      } else {
        $task_id = $application_data[0]->execution_data[0]->task_details->task_id;
      }
    }
    $filter = array('task_id' => $task_id, 'service_id' => $service_id);
    $task_data = (array)$this->mongo_db->where($filter)->get('task_list');
    $form_action = 'spservices/office/applications/save-action';

    $this->load->view("includes/office_includes/header", array("pageTitle" => "Action Form"));
    $this->load->view("office/action_form", array('application_data' => $application_data, 'task_data' => $task_data, 'action' => $form_action));
    $this->load->view("includes/office_includes/footer");
  }


  public function save_action()
  {
    $task_no = base64_decode($this->input->post('task_no'));
    $appl_no = base64_decode($this->input->post('appl_no'));
    $service_id = base64_decode($this->input->post('service'));
    $action_type = $this->input->post('action_type');
    $forward = 'Forward';
    $official = 'Official';
    $applicant = 'Applicant';
    $dbrow = $this->check_first_entry($appl_no);
    $exe_data = $dbrow['dbrow']->{'0'}->execution_data;
    // task no 2 
    // DPS task 
    if ($task_no == '2') {
      $execution_data_count = $this->check_first_entry($appl_no);
      // if application is submitted for the first time 
      if ($execution_data_count['count'] == 1) {
        $first_exedata = $execution_data_count['dbrow']->{'0'}->execution_data[0];
        $first_task_data = $this->get_task_details($service_id, ($task_no));
        $dataToInsert_first = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $first_task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $first_task_data[0]->task_name,
            "user_type" => $official,
            "user_name" => $this->session->userdata('name'),
            "action_taken" => "Y",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $this->session->userdata('userId')->{'$id'},
              "user_name" =>  $this->session->userdata('name'),
              "sign_no" => "",
              "mobile_no" =>  $this->session->userdata('mobile'),
              "circle" => "",
              "district" => $this->session->userdata('district_name'),
              "email" => $this->session->userdata('email'),
              "designation" => $this->session->userdata('designation'),
              "role" => $this->session->userdata('user_role'),
              "role_slug" => $this->session->userdata('role_slug'),
            ],
          ],
        ];

        // insert the DPS default task 
        // $this->insert_next_task($appl_no, $dataToInsert_first);

        // Action forward 
        if ($action_type == 'F') {
          // insert for next task 
          $next_task_data = $this->get_task_details($service_id, ($task_no + 1));
          $filter['_id'] = new ObjectId($this->input->post('users'));
          $next_user = $this->get_user($filter);
          $dataToInsert = [
            "task_details" => [
              "appl_no" => $appl_no,
              "task_id" => $next_task_data[0]->task_id,
              "action_no" => "",
              "task_name" => $next_task_data[0]->task_name,
              "user_type" => $official,
              "user_name" => $next_user[0]->name,
              "action_taken" => "N",
              "payment_date" => "",
              "payment_mode" => "",
              "pull_user_id" => "",
              "executed_time" => "",
              "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
              "user_detail" => [
                "user_id" => $next_user[0]->_id->{'$id'},
                "user_name" => $next_user[0]->name,
                "sign_no" => "",
                "mobile_no" => $next_user[0]->mobile,
                "location_id" => "",
                "location_name" => "",
                "circle" => $next_user[0]->circle_name,
                "district" => $next_user[0]->district_name,
                "email" => $next_user[0]->email,
                "designation" => $next_user[0]->designation,
                "role" => $next_user[0]->user_role,
                "role_slug" => $next_user[0]->role_slug_name,
              ],
            ],
            "official_form_details" => []
          ];

          $dataToupdate_official_form = [
            "action" => "Forward",
            "documents" => null,
            "remarks" => $this->input->post('remarks'),
            "status" => "F"
          ];
          $dataToInsert_first['official_form_details'] = $dataToupdate_official_form;
          $insertData = array(
            $dataToInsert,
            $dataToInsert_first,
            $first_exedata
          );
          $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $insertData, 'service_data.appl_status' => 'UNDER_PROCESSING'])->update('sp_applications');
        }
        // Action reject
        elseif ($action_type == 'R') {
          $dataToInsert_first['official_form_details'] = [
            "action" => "Rejected",
            "documents" => null,
            "remarks" => $this->input->post('remarks'),
            'reject_reason' => $this->input->post('reject_reason'),
            "status" => "R"
          ];
          $insertData = array(
            $dataToInsert_first,
            $first_exedata
          );
          $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $insertData, 'service_data.appl_status' => 'REJECTED', 'reject_reason' => $this->input->post('reject_reason')])->update('sp_applications');
          $this->sms_scheduler->send_reject_sms($appl_no);
        }
        // Action revert back to applicant
        elseif ($action_type == 'RA') {
          $task_data = $this->get_task_details($service_id, 11);
          $task_data1 = $this->get_task_details($service_id, 13);
          $dataToInsert = [
            "task_details" => [
              "appl_no" => $appl_no,
              "task_id" => $task_data[0]->task_id,
              "action_no" => "",
              "task_name" => $task_data[0]->task_name,
              "user_type" => $official,
              "user_name" => $this->session->userdata('name'),
              "action_taken" => "Y",
              "payment_date" => "",
              "payment_mode" => "",
              "pull_user_id" => "",
              "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
              "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
              "user_detail" => [
                "user_id" => $this->session->userdata('userId')->{'$id'},
                "user_name" =>  $this->session->userdata('name'),
                "sign_no" => "",
                "mobile_no" =>  $this->session->userdata('mobile'),
                "circle" => "",
                "district" => $this->session->userdata('district_name'),
                "email" => $this->session->userdata('email'),
                "designation" => $this->session->userdata('designation'),
                "role" => $this->session->userdata('user_role'),
                "role_slug" => $this->session->userdata('role_slug'),
              ],
            ],
            "official_form_details" => []
          ];

          $dataToInsert_next = [
            "task_details" => [
              "appl_no" => $appl_no,
              "task_id" => $task_data1[0]->task_id,
              "action_no" => "",
              "task_name" => $task_data1[0]->task_name,
              "user_type" => $applicant,
              "user_name" => "",
              "action_taken" => "N",
              "payment_date" => "",
              "payment_mode" => "",
              "pull_user_id" => "",
              "executed_time" => "",
              "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
              "user_detail" => [],
            ],
            "official_form_details" => []
          ];

          $dataToInsert_first['official_form_details'] = [
            "action" => "Query to Applicant",
            "documents" => null,
            "remarks" => $this->input->post('applicant_query'),
            "status" => "QA"
          ];
          $insertData = array(
            $dataToInsert_next,
            $dataToInsert,
            $dataToInsert_first,
            $first_exedata
          );
          $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set([
            'execution_data' => $insertData, 'service_data.appl_status' => 'QUERY_ARISE',
            'applicant_query' => true,
            'query_asked' => $this->input->post('applicant_query'),
          ])->update('sp_applications');
          $this->sms_scheduler->send_query_sms($appl_no);
        }
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      }
      // if application is not submitted for the first time 
      else {
        $check_exist = (array)$this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->get('sp_applications');
        if ($check_exist[0]->execution_data[0]->task_details->user_detail->user_id == $this->session->userdata('userId')->{'$id'}) {
          // Action forward 
          if ($action_type == 'F') {
            $task_data = $this->get_task_details($service_id, ($task_no + 1));
            $filter['_id'] = new ObjectId($this->input->post('users'));
            $next_user = $this->get_user($filter);

            $dataToInsert = [
              "task_details" => [
                "appl_no" => $appl_no,
                "task_id" => $task_data[0]->task_id,
                "action_no" => "",
                "task_name" => $task_data[0]->task_name,
                "user_type" => $official,
                "user_name" => $next_user[0]->name,
                "action_taken" => "N",
                "payment_date" => "",
                "payment_mode" => "",
                "pull_user_id" => "",
                "executed_time" => "",
                "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "user_detail" => [
                  "user_id" => $next_user[0]->_id->{'$id'},
                  "user_name" => $next_user[0]->name,
                  "sign_no" => "",
                  "mobile_no" => $next_user[0]->mobile,
                  "location_id" => "",
                  "location_name" => "",
                  "circle" => $next_user[0]->circle_name,
                  "district" => $next_user[0]->district_name,
                  "email" => $next_user[0]->email,
                  "designation" => $next_user[0]->designation,
                  "role" => $next_user[0]->user_role,
                  "role_slug" => $next_user[0]->role_slug_name,
                ],
              ],
              "official_form_details" => []
            ];
            $exe_data[0]->task_details->action_taken = 'Y';
            $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
            $exe_data[0]->official_form_details = [
              "action" => "Forward",
              "documents" => null,
              "remarks" => $this->input->post('remarks'),
              "status" => "F"
            ];
            $nextTask = [$dataToInsert];
            $newdata = array_merge($nextTask, $exe_data);
            // update existing data
            $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata, 'service_data.appl_status' => 'UNDER_PROCESSING', 'applicant_query' => false])->update('sp_applications');
            $this->session->set_flashdata('success', 'Action taken successfully !!!');
            redirect('spservices/office/pending-applications');
          }
          // Action reject
          elseif ($action_type == 'R') {
            $updateData = [
              'service_data.appl_status' => 'REJECTED',
              'reject_reason' => $this->input->post('reject_reason'),
              'execution_data.0.task_details.action_taken' =>  'Y',
              'execution_data.0.task_details.executed_time' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
              "execution_data.0.official_form_details" => [
                "action" => "Rejected",
                "documents" => null,
                "remarks" => $this->input->post('remarks'),
                'reject_reason' => $this->input->post('reject_reason'),
                "status" => "R"
              ]
            ];
            // update existing data 
            $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set($updateData)->update('sp_applications');
            $this->sms_scheduler->send_reject_sms($appl_no);
            $this->session->set_flashdata('success', 'Action taken successfully !!!');
            redirect('spservices/office/pending-applications');
          }
          // Action revert back to applicant
          elseif ($action_type == 'RA') {
            $task_data = $this->get_task_details($service_id, 11);
            $task_data1 = $this->get_task_details($service_id, 13);
            $dataToInsert = [
              "task_details" => [
                "appl_no" => $appl_no,
                "task_id" => $task_data[0]->task_id,
                "action_no" => "",
                "task_name" => $task_data[0]->task_name,
                "user_type" => $official,
                "user_name" => $this->session->userdata('name'),
                "action_taken" => "Y",
                "payment_date" => "",
                "payment_mode" => "",
                "pull_user_id" => "",
                "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "user_detail" => [
                  "user_id" => $this->session->userdata('userId')->{'$id'},
                  "user_name" =>  $this->session->userdata('name'),
                  "sign_no" => "",
                  "mobile_no" =>  $this->session->userdata('mobile'),
                  "circle" => "",
                  "district" => $this->session->userdata('district_name'),
                  "email" => $this->session->userdata('email'),
                  "designation" => $this->session->userdata('designation'),
                  "role" => $this->session->userdata('user_role'),
                  "role_slug" => $this->session->userdata('role_slug'),
                ],
              ],
              "official_form_details" => []
            ];

            $dataToInsert_next = [
              "task_details" => [
                "appl_no" => $appl_no,
                "task_id" => $task_data1[0]->task_id,
                "action_no" => "",
                "task_name" => $task_data1[0]->task_name,
                "user_type" => $applicant,
                "user_name" => "",
                "action_taken" => "N",
                "payment_date" => "",
                "payment_mode" => "",
                "pull_user_id" => "",
                "executed_time" => "",
                "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "user_detail" => [],
              ],
              "official_form_details" => []
            ];

            $exe_data[0]->task_details->action_taken = 'Y';
            $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
            $exe_data[0]->official_form_details = [
              "action" => "Query to Applicant",
              "documents" => null,
              "remarks" => $this->input->post('applicant_query'),
              "status" => "QA"
            ];
            $nextTask = [$dataToInsert_next, $dataToInsert];
            $newdata = array_merge($nextTask, $exe_data);
            // update existing data 
            $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set([
              'execution_data' => $newdata, 'service_data.appl_status' => 'QUERY_ARISE',
              'applicant_query' => true,
              'query_asked' => $this->input->post('applicant_query'),
            ])->update('sp_applications');
            $this->sms_scheduler->send_query_sms($appl_no);
            $this->session->set_flashdata('success', 'Action taken successfully !!!');
            redirect('spservices/office/pending-applications');
          }
        } else {
          $this->session->set_flashdata('error', 'Application has already processed.');
          redirect('spservices/office/pending-applications');
        }
      }
    }
    // task no 3 
    // DA task 
    if ($task_no == '3') {
      if ($action_type == 'F') {
        $dist = $this->session->userdata('district_name');
        if (($dist == 'Karbi Anglong') || ($dist == 'West Karbi Anglong') || ($dist == 'Dima Hasao')) {
          $task_data = $this->get_task_details($service_id, (16));
        } else {
          $task_data = $this->get_task_details($service_id, ($task_no + 1));
        }
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $next_user = $this->get_user($filter);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data[0]->task_name,
            "user_type" => $official,
            "user_name" => $next_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $next_user[0]->_id->{'$id'},
              "user_name" => $next_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $next_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $next_user[0]->circle_name,
              "district" => $next_user[0]->district_name,
              "email" => $next_user[0]->email,
              "designation" => $next_user[0]->designation,
              "role" => $next_user[0]->user_role,
              "role_slug" => $next_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];

        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Forward",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "F"
        ];
        $nextTask = [$dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      } elseif ($action_type == 'RO') {
        $task_data = $this->get_task_details($service_id, 14);
        $task_data1 = $this->get_task_details($service_id, 2);
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $prev_user = $this->get_user($filter);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data[0]->task_name . ' - to DPS',
            "user_type" => $official,
            "user_name" => $this->session->userdata('name'),
            "action_taken" => "Y",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $this->session->userdata('userId')->{'$id'},
              "user_name" =>  $this->session->userdata('name'),
              "sign_no" => "",
              "mobile_no" =>  $this->session->userdata('mobile'),
              "circle" => "",
              "district" => $this->session->userdata('district_name'),
              "email" => $this->session->userdata('email'),
              "designation" => $this->session->userdata('designation'),
              "role" => $this->session->userdata('user_role'),
              "role_slug" => $this->session->userdata('role_slug'),
            ],
          ],
          "official_form_details" => []
        ];

        $dataToInsert_next = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data1[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data1[0]->task_name,
            "user_type" => $official,
            "user_name" => $prev_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $prev_user[0]->_id->{'$id'},
              "user_name" => $prev_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $prev_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $prev_user[0]->circle_name,
              "district" => $prev_user[0]->district_name,
              "email" => $prev_user[0]->email,
              "designation" => $prev_user[0]->designation,
              "role" => $prev_user[0]->user_role,
              "role_slug" => $prev_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];
        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Revert to DPS",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "RO"
        ];
        $nextTask = [$dataToInsert_next, $dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      }
    }

    // task no 4
    // CO task 
    if ($task_no == '4'  || $task_no == '16') {
      if ($action_type == 'F') {
        $task_data = $this->get_task_details($service_id, ($task_no + 1));
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $next_user = $this->get_user($filter);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data[0]->task_name,
            "user_type" => $official,
            "user_name" => $next_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $next_user[0]->_id->{'$id'},
              "user_name" => $next_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $next_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $next_user[0]->circle_name,
              "district" => $next_user[0]->district_name,
              "email" => $next_user[0]->email,
              "designation" => $next_user[0]->designation,
              "role" => $next_user[0]->user_role,
              "role_slug" => $next_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
          // "applicant_task_details" => null,
        ];
        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Forward",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "F"
        ];
        $nextTask = [$dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      } elseif ($action_type == 'RO') {
        $task_data = $this->get_task_details($service_id, 14);
        $task_data1 = $this->get_task_details($service_id, 3);
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $prev_user = $this->get_user($filter);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data[0]->task_name . ' - to DA',
            "user_type" => $official,
            "user_name" => $this->session->userdata('name'),
            "action_taken" => "Y",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $this->session->userdata('userId')->{'$id'},
              "user_name" =>  $this->session->userdata('name'),
              "sign_no" => "",
              "mobile_no" =>  $this->session->userdata('mobile'),
              "circle" => "",
              "district" => $this->session->userdata('district_name'),
              "email" => $this->session->userdata('email'),
              "designation" => $this->session->userdata('designation'),
              "role" => $this->session->userdata('user_role'),
              "role_slug" => $this->session->userdata('role_slug'),
            ],
          ],
          "official_form_details" => []
        ];

        $dataToInsert_next = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data1[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data1[0]->task_name,
            "user_type" => $official,
            "user_name" => $prev_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $prev_user[0]->_id->{'$id'},
              "user_name" => $prev_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $prev_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $prev_user[0]->circle_name,
              "district" => $prev_user[0]->district_name,
              "email" => $prev_user[0]->email,
              "designation" => $prev_user[0]->designation,
              "role" => $prev_user[0]->user_role,
              "role_slug" => $prev_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];
        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Revert to DA",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "RO"
        ];
        $nextTask = [$dataToInsert_next, $dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      }
    }

    // task no 5 
    // SK task 
    // if ($task_no == '5' || $task_no == '16') {
    if ($task_no == '5') {
      if ($action_type == 'F') {
        $task_data = $this->get_task_details($service_id, ($task_no + 1));
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $next_user = $this->get_user($filter);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data[0]->task_name,
            "user_type" => $official,
            "user_name" => $next_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $next_user[0]->_id->{'$id'},
              "user_name" => $next_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $next_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $next_user[0]->circle_name,
              "district" => $next_user[0]->district_name,
              "email" => $next_user[0]->email,
              "designation" => $next_user[0]->designation,
              "role" => $next_user[0]->user_role,
              "role_slug" => $next_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];
        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Forward",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "F"
        ];
        $nextTask = [$dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      } elseif ($action_type == 'RO') {
        $task_data = $this->get_task_details($service_id, 14);
        $task_data1 = $this->get_task_details($service_id, 4);
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $prev_user = $this->get_user($filter);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data[0]->task_name . ' - to CO',
            "user_type" => $official,
            "user_name" => $this->session->userdata('name'),
            "action_taken" => "Y",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $this->session->userdata('userId')->{'$id'},
              "user_name" =>  $this->session->userdata('name'),
              "sign_no" => "",
              "mobile_no" =>  $this->session->userdata('mobile'),
              "circle" => "",
              "district" => $this->session->userdata('district_name'),
              "email" => $this->session->userdata('email'),
              "designation" => $this->session->userdata('designation'),
              "role" => $this->session->userdata('user_role'),
              "role_slug" => $this->session->userdata('role_slug'),
            ],
          ],
          "official_form_details" => []
        ];

        $dataToInsert_next = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data1[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data1[0]->task_name,
            "user_type" => "Official",
            "user_name" => $prev_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $prev_user[0]->_id->{'$id'},
              "user_name" => $prev_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $prev_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $prev_user[0]->circle_name,
              "district" => $prev_user[0]->district_name,
              "email" => $prev_user[0]->email,
              "designation" => $prev_user[0]->designation,
              "role" => $prev_user[0]->user_role,
              "role_slug" => $prev_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];
        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Revert to CO",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "RO"
        ];
        $nextTask = [$dataToInsert_next, $dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      }
    }

    // task no 6
    // LM task 
    if ($task_no == '6' || $task_no == '17') {
      if ($action_type == 'F') {
        $task_data = $this->get_task_details($service_id, ($task_no + 1));
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $next_user = $this->get_user($filter);
        $report_file = cifileupload("report_file");
        $report = $report_file["upload_status"] ? $report_file["uploaded_path"] : null;
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data[0]->task_name,
            "user_type" => $official,
            "user_name" => $next_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $next_user[0]->_id->{'$id'},
              "user_name" => $next_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $next_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $next_user[0]->circle_name,
              "district" => $next_user[0]->district_name,
              "email" => $next_user[0]->email,
              "designation" => $next_user[0]->designation,
              "role" => $next_user[0]->user_role,
              "role_slug" => $next_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];
        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Forward",
          "documents" => $report,
          "remarks" => $this->input->post('remarks'),
          "status" => "F"
        ];
        $nextTask = [$dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set([
          'execution_data' => $newdata, 'service_data.appl_status' => 'UNDER_PROCESSING',
        ])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      } elseif ($action_type == 'RO') {
        $task_data = $this->get_task_details($service_id, 14);
        $task_data1 = $this->get_task_details($service_id, ($task_no - 1));
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $prev_user = $this->get_user($filter);
        $task_name = '';
        $dist = $this->session->userdata('district_name');
        if (($dist == 'Karbi Anglong') || ($dist == 'West Karbi Anglong') || ($dist == 'Dima Hasao')) {
          $task_name = $task_data[0]->task_name . ' - RO/ARO';
        } else {
          $task_name = $task_data[0]->task_name . ' - to SK';
        }
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_name,
            "user_type" => $official,
            "user_name" => $this->session->userdata('name'),
            "action_taken" => "Y",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $this->session->userdata('userId')->{'$id'},
              "user_name" =>  $this->session->userdata('name'),
              "sign_no" => "",
              "mobile_no" =>  $this->session->userdata('mobile'),
              "circle" => "",
              "district" => $this->session->userdata('district_name'),
              "email" => $this->session->userdata('email'),
              "designation" => $this->session->userdata('designation'),
              "role" => $this->session->userdata('user_role'),
              "role_slug" => $this->session->userdata('role_slug'),
            ],
          ],
          "official_form_details" => []
        ];
        $dataToInsert_next = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data1[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data1[0]->task_name,
            "user_type" => $official,
            "user_name" => $prev_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $prev_user[0]->_id->{'$id'},
              "user_name" => $prev_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $prev_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $prev_user[0]->circle_name,
              "district" => $prev_user[0]->district_name,
              "email" => $prev_user[0]->email,
              "designation" => $prev_user[0]->designation,
              "role" => $prev_user[0]->user_role,
              "role_slug" => $prev_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];
        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Revert to SK",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "RO"
        ];
        $nextTask = [$dataToInsert_next, $dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set([
          'execution_data' => $newdata, 'service_data.appl_status' => 'UNDER_PROCESSING',
        ])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      } elseif ($action_type == 'RA') {
        $task_name = '';
        $dist = $this->session->userdata('district_name');
        if (($dist == 'Karbi Anglong') || ($dist == 'West Karbi Anglong') || ($dist == 'Dima Hasao')) {
          $task_data = $this->get_task_details($service_id, 19);
        } else {
          $task_data = $this->get_task_details($service_id, 12);
        }
        $task_data1 = $this->get_task_details($service_id, 13);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data[0]->task_name,
            "user_type" => $official,
            "user_name" => $this->session->userdata('name'),
            "action_taken" => "Y",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $this->session->userdata('userId')->{'$id'},
              "user_name" =>  $this->session->userdata('name'),
              "sign_no" => "",
              "mobile_no" =>  $this->session->userdata('mobile'),
              "circle" => "",
              "district" => $this->session->userdata('district_name'),
              "email" => $this->session->userdata('email'),
              "designation" => $this->session->userdata('designation'),
              "role" => $this->session->userdata('user_role'),
              "role_slug" => $this->session->userdata('role_slug'),
            ],
          ],
          "official_form_details" => []
        ];

        $dataToInsert_next = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data1[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data1[0]->task_name,
            "user_type" => $applicant,
            "user_name" => "",
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [],
          ],
          "official_form_details" => []
        ];
        $exe_data = $dbrow['dbrow']->{'0'}->execution_data;
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Query to Applicant",
          "documents" => null,
          "remarks" => $this->input->post('applicant_query'),
          "status" => "QA"
        ];
        $insertData = array(
          $dataToInsert_next,
          $dataToInsert
        );
        $newdata = array_merge($insertData, $exe_data);
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set([
          'execution_data' => $newdata, 'service_data.appl_status' => 'QUERY_ARISE',
          'applicant_query' => true,
          'query_asked' => $this->input->post('applicant_query'),
        ])->update('sp_applications');
        $this->sms_scheduler->send_query_sms($appl_no);
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      }
    }
    // task no 7
    // SK task 
    if ($task_no == '7') {
      if ($action_type == 'F') {
        $task_data = $this->get_task_details($service_id, ($task_no + 1));
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $next_user = $this->get_user($filter);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data[0]->task_name,
            "user_type" => $official,
            "user_name" => $next_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $next_user[0]->_id->{'$id'},
              "user_name" => $next_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $next_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $next_user[0]->circle_name,
              "district" => $next_user[0]->district_name,
              "email" => $next_user[0]->email,
              "designation" => $next_user[0]->designation,
              "role" => $next_user[0]->user_role,
              "role_slug" => $next_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];
        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Forward",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "F"
        ];
        $nextTask = [$dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      } elseif ($action_type == 'RO') {
        $task_data = $this->get_task_details($service_id, 14);
        $task_data1 = $this->get_task_details($service_id, ($task_no - 1));
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $prev_user = $this->get_user($filter);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data[0]->task_name . ' - to LM',
            "user_type" => $official,
            "user_name" => $this->session->userdata('name'),
            "action_taken" => "Y",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $this->session->userdata('userId')->{'$id'},
              "user_name" =>  $this->session->userdata('name'),
              "sign_no" => "",
              "mobile_no" =>  $this->session->userdata('mobile'),
              "circle" => "",
              "district" => $this->session->userdata('district_name'),
              "email" => $this->session->userdata('email'),
              "designation" => $this->session->userdata('designation'),
              "role" => $this->session->userdata('user_role'),
              "role_slug" => $this->session->userdata('role_slug'),
            ],
          ],
          "official_form_details" => []
        ];
        $dataToInsert_next = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data1[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data1[0]->task_name,
            "user_type" => $official,
            "user_name" => $prev_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $prev_user[0]->_id->{'$id'},
              "user_name" => $prev_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $prev_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $prev_user[0]->circle_name,
              "district" => $prev_user[0]->district_name,
              "email" => $prev_user[0]->email,
              "designation" => $prev_user[0]->designation,
              "role" => $prev_user[0]->user_role,
              "role_slug" => $prev_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];
        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Revert to LM",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "RO"
        ];
        $nextTask = [$dataToInsert_next, $dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      }
    }

    // task no 8
    // CO task 
    if ($task_no == '8' || $task_no == '18') {
      if ($action_type == 'F') {
        $dist = $this->session->userdata('district_name');
        if (($dist == 'Karbi Anglong') || ($dist == 'West Karbi Anglong') || ($dist == 'Dima Hasao')) {
          $task_data = $this->get_task_details($service_id, 9);
        } else {
          $task_data = $this->get_task_details($service_id, ($task_no + 1));
        }
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $next_user = $this->get_user($filter);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data[0]->task_name,
            "user_type" => $official,
            "user_name" => $next_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $next_user[0]->_id->{'$id'},
              "user_name" => $next_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $next_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $next_user[0]->circle_name,
              "district" => $next_user[0]->district_name,
              "email" => $next_user[0]->email,
              "designation" => $next_user[0]->designation,
              "role" => $next_user[0]->user_role,
              "role_slug" => $next_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];
        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Forward",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "F"
        ];
        $nextTask = [$dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      } elseif ($action_type == 'RO') {
        $task_data = $this->get_task_details($service_id, 14);
        $task_data1 = $this->get_task_details($service_id, ($task_no - 1));
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $prev_user = $this->get_user($filter);
        $task_name = '';
        $dist = $this->session->userdata('district_name');
        if (($dist == 'Karbi Anglong') || ($dist == 'West Karbi Anglong') || ($dist == 'Dima Hasao')) {
          $task_name = $task_data[0]->task_name . ' - LP';
        } else {
          $task_name = $task_data[0]->task_name . ' - to SK';
        }
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_name,
            "user_type" => $official,
            "user_name" => $this->session->userdata('name'),
            "action_taken" => "Y",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $this->session->userdata('userId')->{'$id'},
              "user_name" =>  $this->session->userdata('name'),
              "sign_no" => "",
              "mobile_no" =>  $this->session->userdata('mobile'),
              "circle" => "",
              "district" => $this->session->userdata('district_name'),
              "email" => $this->session->userdata('email'),
              "designation" => $this->session->userdata('designation'),
              "role" => $this->session->userdata('user_role'),
              "role_slug" => $this->session->userdata('role_slug'),
            ],
          ],
          "official_form_details" => []
        ];
        $dataToInsert_next = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data1[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data1[0]->task_name,
            "user_type" => $official,
            "user_name" => $prev_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $prev_user[0]->_id->{'$id'},
              "user_name" => $prev_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $prev_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $prev_user[0]->circle_name,
              "district" => $prev_user[0]->district_name,
              "email" => $prev_user[0]->email,
              "designation" => $prev_user[0]->designation,
              "role" => $prev_user[0]->user_role,
              "role_slug" => $prev_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];

        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Revert to SK",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "RO"
        ];
        $nextTask = [$dataToInsert_next, $dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      }
    }

    // task no 9
    // DA task 
    if ($task_no == '9') {
      if ($action_type == 'F') {
        $task_data = $this->get_task_details($service_id, ($task_no + 1));
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $next_user = $this->get_user($filter);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data[0]->task_name,
            "user_type" => $official,
            "user_name" => $next_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $next_user[0]->_id->{'$id'},
              "user_name" => $next_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $next_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $next_user[0]->circle_name,
              "district" => $next_user[0]->district_name,
              "email" => $next_user[0]->email,
              "designation" => $next_user[0]->designation,
              "role" => $next_user[0]->user_role,
              "role_slug" => $next_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];
        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Forward",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "F"
        ];
        $nextTask = [$dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      } elseif ($action_type == 'RO') {
        $task_data = $this->get_task_details($service_id, 14);
        $task_name = '';
        $dist = $this->session->userdata('district_name');
        if (($dist == 'Karbi Anglong') || ($dist == 'West Karbi Anglong') || ($dist == 'Dima Hasao')) {
          $task_name = $task_data[0]->task_name . ' - to RO/ARO';
        } else {
          $task_name = $task_data[0]->task_name . ' - to CO';
        }
        if (($dist == 'Karbi Anglong') || ($dist == 'West Karbi Anglong') || ($dist == 'Dima Hasao')) {
          $task_data1 = $this->get_task_details($service_id, 18);
        } else {
          $task_data1 = $this->get_task_details($service_id, ($task_no - 1));
        }
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $prev_user = $this->get_user($filter);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_name,
            "user_type" => $official,
            "user_name" => $this->session->userdata('name'),
            "action_taken" => "Y",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $this->session->userdata('userId')->{'$id'},
              "user_name" =>  $this->session->userdata('name'),
              "sign_no" => "",
              "mobile_no" =>  $this->session->userdata('mobile'),
              "circle" => "",
              "district" => $this->session->userdata('district_name'),
              "email" => $this->session->userdata('email'),
              "designation" => $this->session->userdata('designation'),
              "role" => $this->session->userdata('user_role'),
              "role_slug" => $this->session->userdata('role_slug'),
            ],
          ],
          "official_form_details" => []
        ];
        $dataToInsert_next = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data1[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data1[0]->task_name,
            "user_type" => $official,
            "user_name" => $prev_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $prev_user[0]->_id->{'$id'},
              "user_name" => $prev_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $prev_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $prev_user[0]->circle_name,
              "district" => $prev_user[0]->district_name,
              "email" => $prev_user[0]->email,
              "designation" => $prev_user[0]->designation,
              "role" => $prev_user[0]->user_role,
              "role_slug" => $prev_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];

        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Revert to CO",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "RO"
        ];
        $nextTask = [$dataToInsert_next, $dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      }
    }

    // task no 10
    // DPS task 
    if ($task_no == '10') {
      if ($action_type == 'D') {
        require FCPATH . 'vendor/autoload.php';
        $this->load->library('phpqrcode/qrlib');
        $get_appl_data = $this->mongo_db->where(['service_data.appl_ref_no' => $appl_no])->get('sp_applications');
        $obj = $get_appl_data->{'0'}->_id->{'$id'};
        $link = base_url('spservices/verify-certificate/' . $obj);
        $certificate_no = $this->getID(7);
        $filename = str_replace("/", "-", $appl_no);
        $qrcode_path = 'storage/docs/mcc_qr/' . date("Y") . '/' . date("m") . '/' . date("d") . '/';
        $pathname = FCPATH . $qrcode_path;
        if (!is_dir($pathname)) {
          mkdir($pathname, 0777, true);
          file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
        }
        $qrname = $filename . ".png";
        $file_name = $qrcode_path . $qrname;
        QRcode::png($link, $file_name);
        $pdata = (array)$this->application_model->get_single_application($appl_no);
        $html = $this->load->view('office/certificates/minority_certificate', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no), true);
        $this->load->library('dpdf');
        $pdf_path = $this->dpdf->createPDF($html, $filename, 'A4', 'landscape', 'docs/mcc/');
        // action taken data 
        $dataToupdate = [
          'form_data.certificate_no' =>  $certificate_no,
        ];
        // update existing data 
        $this->update_existing($appl_no, $dataToupdate);
        redirect('spservices/office/dscsign/index/' . base64_encode($appl_no) . '/' . base64_encode($pdf_path));
      } elseif ($action_type == 'RD') {
        $task_data = $this->get_task_details($service_id, 14);
        $task_data1 = $this->get_task_details($service_id, ($task_no - 1));
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $prev_user = $this->get_user($filter);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data[0]->task_name . ' - to DA',
            "user_type" => $official,
            "user_name" => $this->session->userdata('name'),
            "action_taken" => "Y",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $this->session->userdata('userId')->{'$id'},
              "user_name" =>  $this->session->userdata('name'),
              "sign_no" => "",
              "mobile_no" =>  $this->session->userdata('mobile'),
              "circle" => "",
              "district" => $this->session->userdata('district_name'),
              "email" => $this->session->userdata('email'),
              "designation" => $this->session->userdata('designation'),
              "role" => $this->session->userdata('user_role'),
              "role_slug" => $this->session->userdata('role_slug'),
            ],
          ],
          "official_form_details" => []
        ];
        $dataToInsert_next = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data1[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data1[0]->task_name,
            "user_type" => $official,
            "user_name" => $prev_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $prev_user[0]->_id->{'$id'},
              "user_name" => $prev_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $prev_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $prev_user[0]->circle_name,
              "district" => $prev_user[0]->district_name,
              "email" => $prev_user[0]->email,
              "designation" => $prev_user[0]->designation,
              "role" => $prev_user[0]->user_role,
              "role_slug" => $prev_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];

        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Revert to DA",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "RO"
        ];
        $nextTask = [$dataToInsert_next, $dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      } elseif ($action_type == 'RC') {
        $task_data = $this->get_task_details($service_id, 14);
        $task_name = '';
        $remarks_action = '';
        $dist = $this->session->userdata('district_name');
        if (($dist == 'Karbi Anglong') || ($dist == 'West Karbi Anglong') || ($dist == 'Dima Hasao')) {
          $task_name = $task_data[0]->task_name . ' - to RO/ARO';
          $remarks_action = 'Revert to RO/ARO';
        } else {
          $task_name = $task_data[0]->task_name . ' - to CO';
          $remarks_action = 'Revert to CO';
        }
        if (($dist == 'Karbi Anglong') || ($dist == 'West Karbi Anglong') || ($dist == 'Dima Hasao')) {
          $task_data1 = $this->get_task_details($service_id, 18);
        } else {
          $task_data1 = $this->get_task_details($service_id, 8);
        }
        $filter['_id'] = new ObjectId($this->input->post('users'));
        $prev_user = $this->get_user($filter);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data[0]->task_id,
            "action_no" => "",
            "task_name" => $task_name,
            "user_type" => $official,
            "user_name" => $this->session->userdata('name'),
            "action_taken" => "Y",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $this->session->userdata('userId')->{'$id'},
              "user_name" =>  $this->session->userdata('name'),
              "sign_no" => "",
              "mobile_no" =>  $this->session->userdata('mobile'),
              "circle" => "",
              "district" => $this->session->userdata('district_name'),
              "email" => $this->session->userdata('email'),
              "designation" => $this->session->userdata('designation'),
              "role" => $this->session->userdata('user_role'),
              "role_slug" => $this->session->userdata('role_slug'),
            ],
          ],
          "official_form_details" => []
        ];
        $dataToInsert_next = [
          "task_details" => [
            "appl_no" => $appl_no,
            "task_id" => $task_data1[0]->task_id,
            "action_no" => "",
            "task_name" => $task_data1[0]->task_name,
            "user_type" => $official,
            "user_name" => $prev_user[0]->name,
            "action_taken" => "N",
            "payment_date" => "",
            "payment_mode" => "",
            "pull_user_id" => "",
            "executed_time" => "",
            "received_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "user_detail" => [
              "user_id" => $prev_user[0]->_id->{'$id'},
              "user_name" => $prev_user[0]->name,
              "sign_no" => "",
              "mobile_no" => $prev_user[0]->mobile,
              "location_id" => "",
              "location_name" => "",
              "circle" => $prev_user[0]->circle_name,
              "district" => $prev_user[0]->district_name,
              "email" => $prev_user[0]->email,
              "designation" => $prev_user[0]->designation,
              "role" => $prev_user[0]->user_role,
              "role_slug" => $prev_user[0]->role_slug_name,
            ],
          ],
          "official_form_details" => []
        ];

        // action taken data 
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => $remarks_action,
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "status" => "RO"
        ];
        $nextTask = [$dataToInsert_next, $dataToInsert];
        $newdata = array_merge($nextTask, $exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata])->update('sp_applications');
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      }
      // Action reject
      elseif ($action_type == 'R') {
        $exe_data[0]->task_details->action_taken = 'Y';
        $exe_data[0]->task_details->executed_time = new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000);
        $exe_data[0]->official_form_details = [
          "action" => "Rejected",
          "documents" => null,
          "remarks" => $this->input->post('remarks'),
          "reject_reason" => $this->input->post('reject_reason'),
          "status" => "R"
        ];
        $newdata = array_merge($exe_data);
        // update existing data
        $this->mongo_db->where(array('service_data.appl_ref_no' => $appl_no))->set(['execution_data' => $newdata, 'service_data.appl_status' => 'REJECTED', 'reject_reason' => $this->input->post('reject_reason')])->update('sp_applications');
        $this->sms_scheduler->send_reject_sms($appl_no);
        $this->session->set_flashdata('success', 'Action taken successfully !!!');
        redirect('spservices/office/pending-applications');
      }
    }
  }

  private function check_first_entry($appl_no)
  {
    $appl_no = strval($appl_no);
    $filter = array('service_data.appl_ref_no' => $appl_no);
    $data = $this->mongo_db->where($filter)->get('sp_applications');
    foreach ($data as $val) {
      $count = count($val->execution_data);
    }
    // return $count;
    return array('count' => $count, 'dbrow' => $data);
  }

  private function get_task_details($service_id, $task_id)
  {
    $task = strval($task_id);
    $filter = array('task_id' => $task, 'service_id' => $service_id);
    return (array)$this->mongo_db->where($filter)->get('task_list');
  }

  private function get_user($filter)
  {
    return (array)$this->mongo_db->where($filter)->get('office_users');
  }

  private function update_existing($ref_no, $dataToupdate, $dataToupdate_official_form = '')
  {
    $option = array('upsert' => true);
    $this->mongo_db->where(array('service_data.appl_ref_no' => $ref_no))->set($dataToupdate)->update('sp_applications', $option);
    if (!empty($dataToupdate_official_form)) {
      $this->mongo_db->where(array('service_data.appl_ref_no' => $ref_no))->set($dataToupdate_official_form)->update('sp_applications');
    }
  }

  private function insert_next_task($ref_no, $data)
  {
    $this->mongo_db->command(array(
      'update' => 'sp_applications',
      'updates' => [
        array(
          'q' => array('service_data.appl_ref_no' => $ref_no),
          'u' => array('$push' => array(
            'execution_data' => array(
              '$each' => [$data],
              '$position' => 0
            )
          ))
        ),
      ],
    ));
  }

  function getID($length)
  {
    $certificate_no = $this->generateID($length);
    while ($this->application_model->get_row(["certificate_no" => $certificate_no])) {
      $certificate_no = $this->generateID($length);
    } //End of while()
    return $certificate_no;
  } //End of getID()

  public function generateID($length)
  {
    $number = '';
    for ($i = 0; $i < $length; $i++) {
      $number .= rand(0, 9);
    }
    $str = "CERT-MCC/" . date('Y') . "/" . $number;
    return $str;
  } //End of generateID()


  public function regenerate_certificate($id)
  {
    $ref_no = base64_decode($id);
    $role = $this->session->userdata('role_slug') ?? '';
    if ($role != 'DPS') {
      redirect('spservices/office/pending-applications');
    } else {
      require FCPATH . 'vendor/autoload.php';
      $this->load->library('phpqrcode/qrlib');
      $get_appl_data = $this->mongo_db->where(['service_data.appl_ref_no' => $ref_no])->get('sp_applications');
      $obj = $get_appl_data->{'0'}->_id->{'$id'};
      $link = base_url('spservices/verify-certificate/' . $obj);
      $filename = str_replace("/", "-", $ref_no) . time();

      $qrcode_path = 'storage/docs/mcc_qr/' . date("Y") . '/' . date("m") . '/' . date("d") . '/';
      $pathname = FCPATH . $qrcode_path;
      if (!is_dir($pathname)) {
        mkdir($pathname, 0777, true);
        file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
      }

      $qrname = $filename . ".png";
      $file_name = $qrcode_path . $qrname;
      QRcode::png($link, $file_name);

      $pdata = (array)$this->application_model->get_single_application($ref_no);
      $html = $this->load->view('office/certificates/minority_certificate', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $pdata[0]->form_data->certificate_no), true);

      $this->load->library('dpdf');
      $pdf_path = $this->dpdf->createPDF($html, $filename, 'A4', 'landscape', 'docs/mcc/');
      redirect('spservices/office/dscsign/index/' . base64_encode($ref_no) . '/' . base64_encode($pdf_path));
    }
  }
  public function test()
  {
    $appl_no = '';
    require FCPATH . 'vendor/autoload.php';
    $this->load->library('phpqrcode/qrlib');
    $get_appl_data = $this->mongo_db->where(['service_data.appl_ref_no' => $appl_no])->get('sp_applications');
    $obj = $get_appl_data->{'0'}->_id->{'$id'};
    $link = base_url('spservices/verify-certificate/' . $obj);
    $certificate_no = $this->getID(7);
    $filename = str_replace("/", "-", $appl_no);
    $qrcode_path = 'storage/docs/mcc_qr/' . date("Y") . '/' . date("m") . '/' . date("d") . '/';
    $pathname = FCPATH . $qrcode_path;
    if (!is_dir($pathname)) {
      mkdir($pathname, 0777, true);
      file_put_contents($pathname . "index.html", '<html><head></head><body>Silence is golden</body></html>');
    }
    $qrname = $filename . ".png";
    $file_name = $qrcode_path . $qrname;
    QRcode::png($link, $file_name);
    $pdata = (array)$this->application_model->get_single_application($appl_no);
    $html = $this->load->view('office/certificates/minority_certificate', array('data' => $pdata, 'qr' => $file_name, 'certificate_no' => $certificate_no), true);
    $this->load->library('dpdf');
    $pdf_path = $this->dpdf->createPDF($html, $filename, 'A4', 'landscape', 'docs/mcc/');
    // action taken data 
    $dataToupdate = [
      'form_data.certificate_no' =>  $certificate_no,
    ];
    // update existing data 
    $this->update_existing($appl_no, $dataToupdate);
    redirect('spservices/office/dscsign/index/' . base64_encode($appl_no) . '/' . base64_encode($pdf_path));
  }
}
