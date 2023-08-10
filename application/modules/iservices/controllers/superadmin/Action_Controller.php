
<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Action_Controller extends Rtps {

    //put your code here
    public function __construct()
    {
      parent::__construct();
     $this->load->model("superadmin/application_model");
     $this->load->model("superadmin/action_task_model");
     $this->load->model("superadmin/User_model");
     $this->load->model('intermediator_model');
     $this->load->model('superadmin/Task_list_model');
     $this->load->helper("cifileupload");
    }
    // rename to save_action()
    public function save_action(){
      $collection = 'action_task_model';
      $task_no = base64_decode($this->input->post('task_no'));
      $appl_no = base64_decode($this->input->post('appl_no'));
      $service_id = base64_decode($this->input->post('service'));
      $action_type = $this->input->post('action_type');
      // pre($task_no);
      $task_name = $this->Task_list_model->get_task_name($service_id, $task_no);
      // pre($service_id);
      $task_name = $task_name[0]->task_name;
      if($task_no == '1') {
        $receive_date = $this->input->post('receive_date');
        $users = $this->input->post('users');
        foreach($users as $us){
          $user_list[] = new ObjectId($us);
        }
        
        if($action_type == 'R'){
          $dataToInsert = [
            "task_details" => [
              "appl_no" => $appl_no,
              "remarks" => null,
              "task_id" => null,
              "action_no" => $task_no,
              "task_name" => $task_name,
              "task_type" => 1,
              "user_name" => $this->session->userdata("name"),
              "service_id" => "WPTBC-01",
              "user_detail" => $this->session->userdata("name"),
              "action_taken" => "Y",
              "payment_date" => null,
              "payment_mode" => null,
              "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
              "next_users" => null,
              "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
              "received_time" => $receive_date,
            ],
            "official_form_details" => [ 
              "action" => "Rejected",
              "documents" => null ,
              "remarks" => $this->input->post('remarks'),
              "status" => "R"
            ],
            "applicant_task_details" => null,
        ];

        $data = array(
          'status' => 'REJECTED',
          'task_no' => null,
          'assigned_user'=> null,
          'assigned_ids'=> null,
          'action_taken_by'=>[
            'name'=> $this->session->userdata("name"),
            'user_id'=>new ObjectId($this->session->userdata('userId')->{'$id'})
          ]
        );
        }
        elseif($action_type == 'F'){
          
            $dataToInsert = [
              "task_details" => [
                "appl_no" => $appl_no,
                "remarks" => null,
                "task_id" => null,
                "action_no" => $task_no,
                "task_name" => $task_name,
                "task_type" => 1,
                "user_name" => $this->session->userdata("name"),
                "service_id" => "WPTBC-01",
                "user_detail" => $this->session->userdata("name"),
                "action_taken" => "Y",
                "payment_date" => null,
                "payment_mode" => null,
                "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                "next_users" => $user_list,
                "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "received_time" => $receive_date,
              ],
              "official_form_details" => [ 
                "action" => "Forward",
                "documents" => null ,
                "remarks" => $this->input->post('remarks'),
                "status" => "F"
              ],
              "applicant_task_details" => null,
          ];
          $data = array(
            'status' => 'UNDER PROCESS',
            'task_no' => '2',
            'assigned_user'=> 'DA',
            'assigned_ids'=> $user_list
          );
          }
          elseif($action_type == 'RA'){
            $dataToInsert = [
              "task_details" => [
                "appl_no" => $appl_no,
                "remarks" => $this->input->post('applicant_query'),
                "task_id" => null,
                "action_no" => $task_no,
                "task_name" => $task_name,
                "task_type" => 1,
                "user_name" => $this->session->userdata("name"),
                "service_id" => "WPTBC-01",
                "user_detail" => $this->session->userdata("name"),
                "action_taken" => "Y",
                "payment_date" => null,
                "payment_mode" => null,
                "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                "next_users" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "received_time" => $receive_date,
              ],
              "official_form_details" => [ 
                "action" => "Revert",
                "documents" => null ,
                "remarks" => $this->input->post('remarks'),
                "status" => "RA"
              ],
              "applicant_task_details" => null,
          ];
          $data = array(
            'status' => 'QUERY',
            'task_no' => null,
            'assigned_user'=> null,
            'assigned_ids'=> null,
            'action_taken_by'=>[
              'name'=> $this->session->userdata("name"),
              'user_id'=>new ObjectId($this->session->userdata('userId')->{'$id'})
            ]
          );
          }
        }      
      elseif($task_no == '2'){
        $users = $this->input->post('users');
        foreach($users as $us){
          $user_list[] = new ObjectId($us);
        }
        if($action_type == 'F'){
            $dataToInsert = [
              "task_details" => [
                "appl_no" => $appl_no,
                "remarks" => null,
                "task_id" => null,
                "action_no" => $task_no,
                "task_name" => $task_name,
                "task_type" => 1,
                "user_name" => $this->session->userdata("name"),
                "service_id" => "WPTBC-01",
                "user_detail" => $this->session->userdata("name"),
                "action_taken" => "Y",
                "payment_date" => null,
                "payment_mode" => null,
                "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                "next_users" => $user_list,
                "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "received_time" => $receive_date,
              ],
              "official_form_details" => [ 
                "action" => "Forward",
                "documents" => null ,
                "remarks" => $this->input->post('remarks'),
                "status" => "F"
              ],
              "applicant_task_details" => null,
          ];
          $data = array(
            'status' => 'UNDER PROCESS',
            'task_no' => '3',
            'assigned_user'=> 'CO',
            'assigned_ids'=> $user_list
          );
      }
      elseif($action_type == 'RO'){
        $prev_users = $this->action_task_model->get_prev_users(new ObjectId($this->session->userdata('userId')->{'$id'}), $appl_no);
        $prev_user[]= new ObjectId($prev_users[0]->task_details->pull_user_id);
          $dataToInsert = [
            "task_details" => [
              "appl_no" => $appl_no,
              "remarks" => null,
              "task_id" => null,
              "action_no" => $task_no,
              "task_name" => $task_name.' - revert to previous',
              "task_type" => 1,
              "user_name" => $this->session->userdata("name"),
              "service_id" => "WPTBC-01",
              "user_detail" => $this->session->userdata("name"),
              "action_taken" => "Y",
              "payment_date" => null,
              "payment_mode" => null,
              "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
              "next_users" => $prev_user,
              "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
              "received_time" => $receive_date,
            ],
            "official_form_details" => [ 
              "action" => "Revert to Official",
              "documents" => null ,
              "remarks" => $this->input->post('remarks'),
              "status" => "RO"
            ],
            "applicant_task_details" => null,
        ];
        $data = array(
          'status' => 'UNDER PROCESS',
          // from 3 to 1
          'task_no' => '1',
          'assigned_user'=> 'DPS',
          'assigned_ids'=> $prev_user
        );
      }
      }
      elseif($task_no == '3'){
        $users = $this->input->post('users');
        foreach($users as $us){
          $user_list[] = new ObjectId($us);
        }
        if($action_type == 'F'){
              $dataToInsert = [
                "task_details" => [
                  "appl_no" => $appl_no,
                  "remarks" => $this->input->post('remarks'),
                  "task_id" => null,
                  "action_no" => $task_no,
                  "task_name" => $task_name,
                  "task_type" => 1,
                  "user_name" => $this->session->userdata("name"),
                  "service_id" => "WPTBC-01",
                  "user_detail" => $this->session->userdata("name"),
                  "action_taken" => "Y",
                  "payment_date" => null,
                  "payment_mode" => null,
                  "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                  "next_users" => $user_list,
                  "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                  "received_time" => $receive_date,
                ],
                "official_form_details" => [ 
                  "action" => "Forward",
                  "documents" => null ,
                  "remarks" => $this->input->post('remarks'),
                  "status" => "F"
                ],
                "applicant_task_details" => null,
            ];
            $data = array(
              'status' => 'UNDER PROCESS',
              'task_no' => '4',
              'assigned_user'=> 'SK',
              'assigned_ids'=> $user_list
            );
          }
          elseif($action_type == 'RA'){
              $dataToInsert = [
                "task_details" => [
                  "appl_no" => $appl_no,
                  "remarks" => $this->input->post('applicant_query'),
                  "task_id" => null,
                  "action_no" => $task_no,
                  "task_name" => $task_name,
                  "task_type" => 1,
                  "user_name" => $this->session->userdata("name"),
                  "service_id" => "WPTBC-01",
                  "user_detail" => $this->session->userdata("name"),
                  "action_taken" => "Y",
                  "payment_date" => null,
                  "payment_mode" => null,
                  "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                  "next_users" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                  "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                  "received_time" => $receive_date,
                ],
                "official_form_details" => [ 
                  "action" => "Revert",
                  "documents" => null ,
                  "remarks" => $this->input->post('remarks'),
                  "status" => "RA"
                ],
                "applicant_task_details" => null,
            ];
            $data = array(
              'status' => 'QUERY',
              'task_no' => null,
              'assigned_user'=> null,
              'assigned_ids'=> null,
              'action_taken_by'=>[
                'name'=> $this->session->userdata("name"),
                'user_id'=>new ObjectId($this->session->userdata('userId')->{'$id'})
              ]
            );
          }
      }
      elseif($task_no == '4'){
        $users = $this->input->post('users');
        foreach($users as $us){
          $user_list[] = new ObjectId($us);
        }
        if($action_type == 'F'){
            $dataToInsert = [
              "task_details" => [
                "appl_no" => $appl_no,
                "remarks" => null,
                "task_id" => null,
                "action_no" => $task_no,
                "task_name" => $task_name,
                "task_type" => 1,
                "user_name" => $this->session->userdata("name"),
                "service_id" => "WPTBC-01",
                "user_detail" => $this->session->userdata("name"),
                "action_taken" => "Y",
                "payment_date" => null,
                "payment_mode" => null,
                "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                "next_users" => $user_list,
                "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "received_time" => $receive_date,
              ],
              "official_form_details" => [ 
                "action" => "Forward",
                "documents" => null ,
                "remarks" => $this->input->post('remarks'),
                "status" => "F"
              ],
              "applicant_task_details" => null,
          ];
          $data = array(
            'task_no' => '5',
            'assigned_user'=> 'LM',
            'assigned_ids'=> $user_list
          );
        }
        elseif($action_type == 'RO'){
          $prev_users = $this->action_task_model->get_prev_users(new ObjectId($this->session->userdata('userId')->{'$id'}), $appl_no);
          $prev_user[]= new ObjectId($prev_users[0]->task_details->pull_user_id);
          $dataToInsert = [
            "task_details" => [
              "appl_no" => $appl_no,
              "remarks" => null,
              "task_id" => null,
              "action_no" => $task_no,
              "task_name" => $task_name.' - revert to previous',
              "task_type" => 1,
              "user_name" => $this->session->userdata("name"),
              "service_id" => "WPTBC-01",
              "user_detail" => $this->session->userdata("name"),
              "action_taken" => "Y",
              "payment_date" => null,
              "payment_mode" => null,
              "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
              "next_users" => $prev_users,
              "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
              "received_time" => $receive_date,
            ],
            "official_form_details" => [ 
              "action" => "Revert to Official",
              "documents" => null ,
              "remarks" => $this->input->post('remarks'),
              "status" => "RO"
            ],
            "applicant_task_details" => null,
        ];
        $data = array(
          'task_no' => '3',
          'assigned_user'=> 'CO',
          'assigned_ids'=> $prev_users
        );
        }
      }
      elseif($task_no == '5'){
        if($action_type == 'F'){
              $report_file = cifileupload("report_file");
              $report = $report_file["upload_status"]?$report_file["uploaded_path"]:null;
              $prev_users = $this->action_task_model->get_prev_users(new ObjectId($this->session->userdata('userId')->{'$id'}), $appl_no);
              $prev_user[]= new ObjectId($prev_users[0]->task_details->pull_user_id);
              $dataToInsert = [
                "task_details" => [
                  "appl_no" => $appl_no,
                  "remarks" => $this->input->post('remarks'),
                  "task_id" => null,
                  "action_no" => $task_no,
                  "task_name" => $task_name,
                  "task_type" => 1,
                  "user_name" => $this->session->userdata("name"),
                  "service_id" => "WPTBC-01",
                  "user_detail" => $this->session->userdata("name"),
                  "action_taken" => "Y",
                  "payment_date" => null,
                  "payment_mode" => null,
                  "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                  "next_users" => $prev_user,
                  "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                  "received_time" => $receive_date,
                ],
                "official_form_details" => [ 
                  "action" => "Forward",
                  "documents" => $report ,
                  "remarks" => $this->input->post('remarks'),
                  "status" => "F"
                ],
                "applicant_task_details" => null,
            ];
            $data = array(
              'task_no' => '6',
              'assigned_user'=> 'SK',
              'assigned_ids'=> $prev_user
            );
          }
          elseif($action_type == 'RA'){
            $dataToInsert = [
              "task_details" => [
                "appl_no" => $appl_no,
                "remarks" => $this->input->post('applicant_query'),
                "task_id" => null,
                "action_no" => $task_no,
                "task_name" => $task_name,
                "task_type" => 1,
                "user_name" => $this->session->userdata("name"),
                "service_id" => "WPTBC-01",
                "user_detail" => $this->session->userdata("name"),
                "action_taken" => "Y",
                "payment_date" => null,
                "payment_mode" => null,
                "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                "next_users" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "received_time" => $receive_date,
              ],
              "official_form_details" => [ 
                "action" => "Revert",
                "documents" => null ,
                "remarks" => $this->input->post('remarks'),
                "status" => "RA"
              ],
              "applicant_task_details" => null,
          ];
          $data = array(
            'status' => 'QUERY',
            'task_no' => null,
            'assigned_user'=> null,
            'assigned_ids'=> null,
            'action_taken_by'=>[
              'name'=> $this->session->userdata("name"),
              'user_id'=>new ObjectId($this->session->userdata('userId')->{'$id'})
            ]
          );
          }
          elseif($action_type == 'RO'){
              $prev_users = $this->action_task_model->get_prev_users(new ObjectId($this->session->userdata('userId')->{'$id'}), $appl_no);
              $prev_user[]= new ObjectId($prev_users[0]->task_details->pull_user_id);
              $dataToInsert = [
                "task_details" => [
                  "appl_no" => $appl_no,
                  "remarks" => null,
                  "task_id" => null,
                  "action_no" => $task_no,
                  "task_name" => $task_name.' - revert to previous',
                  "task_type" => 1,
                  "user_name" => $this->session->userdata("name"),
                  "service_id" => "WPTBC-01",
                  "user_detail" => $this->session->userdata("name"),
                  "action_taken" => "Y",
                  "payment_date" => null,
                  "payment_mode" => null,
                  "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                  "next_users" => $prev_users,
                  "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                  "received_time" => $receive_date,
                ],
                "official_form_details" => [ 
                  "action" => "Revert to Official",
                  "documents" => null ,
                  "remarks" => $this->input->post('remarks'),
                  "status" => "RO"
                ],
                "applicant_task_details" => null,
            ];
            $data = array(
              'task_no' => '4',
              'assigned_user'=> 'SK',
              'assigned_ids'=> $prev_users
            );
          }
          
      }
      elseif($task_no == '6'){

        $prev_users = $this->action_task_model->get_prev_users(new ObjectId($this->session->userdata('userId')->{'$id'}), $appl_no);
        $prev_user[]= new ObjectId($prev_users[0]->task_details->pull_user_id);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "remarks" => null,
            "task_id" => null,
            "action_no" => $task_no,
            "task_name" => $task_name,
            "task_type" => 1,
            "user_name" => $this->session->userdata("name"),
            "service_id" => "WPTBC-01",
            "user_detail" => $this->session->userdata("name"),
            "action_taken" => "Y",
            "payment_date" => null,
            "payment_mode" => null,
            "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
            "next_users" => $prev_user,
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => $receive_date,
          ],
          "official_form_details" => [ 
            "action" => "Forward",
            "documents" => null ,
            "remarks" => $this->input->post('remarks'),
            "status" => "F"
          ],
          "applicant_task_details" => null,
      ];
      $data = array(
        'task_no' => '7',
        'assigned_user'=> 'CO',
        'assigned_ids'=> $prev_user
      );
      }
      elseif($task_no == '7'){

        $prev_users = $this->action_task_model->get_prev_users(new ObjectId($this->session->userdata('userId')->{'$id'}), $appl_no);
        $prev_user[]= new ObjectId($prev_users[0]->task_details->pull_user_id);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "remarks" => null,
            "task_id" => null,
            "action_no" => $task_no,
            "task_name" => $task_name,
            "task_type" => 1,
            "user_name" => $this->session->userdata("name"),
            "service_id" => "WPTBC-01",
            "user_detail" => $this->session->userdata("name"),
            "action_taken" => "Y",
            "payment_date" => null,
            "payment_mode" => null,
            "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
            "next_users" => $prev_user,
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => $receive_date,
          ],
          "official_form_details" => [ 
            "action" => "Forward",
            "documents" => null ,
            "remarks" => $this->input->post('remarks'),
            "status" => "F"
          ],
          "applicant_task_details" => null,
      ];
      $data = array(
        'task_no' => '8',
        'assigned_user'=> 'DA',
        'assigned_ids'=> $prev_user
      );
      }
      elseif($task_no == '8'){

        $prev_users = $this->action_task_model->get_prev_users(new ObjectId($this->session->userdata('userId')->{'$id'}), $appl_no);
        $prev_user[]= new ObjectId($prev_users[0]->task_details->pull_user_id);
        $dataToInsert = [
          "task_details" => [
            "appl_no" => $appl_no,
            "remarks" => null,
            "task_id" => null,
            "action_no" => $task_no,
            "task_name" => $task_name,
            "task_type" => 1,
            "user_name" => $this->session->userdata("name"),
            "service_id" => "WPTBC-01",
            "user_detail" => $this->session->userdata("name"),
            "action_taken" => "Y",
            "payment_date" => null,
            "payment_mode" => null,
            "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
            "next_users" => $prev_user,
            "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "received_time" => $receive_date,
          ],
          "official_form_details" => [ 
            "action" => "Forward",
            "documents" => null ,
            "remarks" => $this->input->post('remarks'),
            "status" => "F"
          ],
          "applicant_task_details" => null,
      ];
      $data = array(
        'task_no' => '9',
        'assigned_user'=> 'DPS',
        'assigned_ids'=> $prev_user
      );
      }
      elseif($task_no == '9'){

      $action_type = $this->input->post('action_type');
      if($action_type == 'D'){
        $this->load->library('phpqrcode/qrlib');
        $SERVERFILEPATH = 'storage/qrcode/';
    
          $text = $appl_no;
          $text1= substr($text, 0,9);
          $folder = $SERVERFILEPATH;
          $file_name1 = $text1.".png";
          $file_name = $folder.$file_name1;
          QRcode::png($text,$file_name);
      $mpdf = new \Mpdf\Mpdf(['tempDir' => sys_get_temp_dir().DIRECTORY_SEPARATOR.'mpdf', 'format' => 'A4-P']);
      $mpdf->autoScriptToLang = true;
      $mpdf->autoLangToFont = true;
    
      $certificate_no = 'CERT/NCL/'.$appl_no;
      $pdata = $this->application_model->get_single_application($appl_no);
      $html = $this->load->view('superadmin/certificates/schedule_caste',array('data'=>$pdata, 'qr'=>$file_name, 'certificate_no'=>$certificate_no), true);

      $mpdf->WriteHTML($html);
      $mpdf->Output('storage/output_certificate/'.$appl_no.'.pdf','F'); 
      $file_name = 'storage/output_certificate/'.$appl_no.'.pdf';
          $dataToInsert = [
            "task_details" => [
              "appl_no" => $appl_no,
              "remarks" => null,
              "task_id" => null,
              "action_no" => $task_no,
              "task_name" => $task_name,
              "task_type" => 1,
              "user_name" => $this->session->userdata("name"),
              "service_id" => "WPTBC-01",
              "user_detail" => $this->session->userdata("name"),
              "action_taken" => "Y",
              "payment_date" => null,
              "payment_mode" => null,
              "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
              "next_users" => null,
              "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
              "received_time" => $receive_date,
            ],
            "official_form_details" => [ 
              "action" => "Delivered",
              "documents" => $file_name ,
              "remarks" => $this->input->post('remarks'),
              "status" => "D"
            ],
            "applicant_task_details" => null,
        ];

        $data = array(
          'certificate_no' => $certificate_no,
          'documents' =>$file_name,
          'isSigned' => false,
          'status' => 'DELIVERED',
          'task_no' => null,
          'assigned_user'=> null,
          'assigned_ids'=> null,
          'action_taken_by'=>[
            'name'=> $this->session->userdata("name"),
            'user_id'=>new ObjectId($this->session->userdata('userId')->{'$id'}),
            'action_date'=>new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
          ]
        );
        }
        elseif($action_type == 'R'){
          $dataToInsert = [
            "task_details" => [
              "appl_no" => $appl_no,
              "remarks" => null,
              "task_id" => null,
              "action_no" => $task_no,
              "task_name" => $task_name,
              "task_type" => 1,
              "user_name" => $this->session->userdata("name"),
              "service_id" => "WPTBC-01",
              "user_detail" => $this->session->userdata("name"),
              "action_taken" => "Y",
              "payment_date" => null,
              "payment_mode" => null,
              "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
              "next_users" => null,
              "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
              "received_time" => $receive_date,
            ],
            "official_form_details" => [ 
              "action" => "Rejected",
              "documents" => 'file' ,
              "remarks" => $this->input->post('remarks'),
              "status" => "R"
            ],
            "applicant_task_details" => null,
        ];
        $data = array(
          'status' => 'REJECTED',
          'task_no' => null,
          'assigned_user'=> null,
          'assigned_ids'=> null,
          'action_taken_by'=>[
            'name'=> $this->session->userdata("name"),
            'user_id'=>new ObjectId($this->session->userdata('userId')->{'$id'})
          ]
        );
        }
        elseif($action_type == 'RA'){
          $dataToInsert = [
            "task_details" => [
              "appl_no" => $appl_no,
              "remarks" => $this->input->post('applicant_query'),
              "task_id" => null,
              "action_no" => $task_no,
              "task_name" => $task_name,
              "task_type" => 1,
              "user_name" => $this->session->userdata("name"),
              "service_id" => "WPTBC-01",
              "user_detail" => $this->session->userdata("name"),
              "action_taken" => "Y",
              "payment_date" => null,
              "payment_mode" => null,
              "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
              "next_users" => null,
              "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
              "received_time" => $receive_date,
            ],
            "official_form_details" => [ 
              "action" => "Revert",
              "documents" => 'file' ,
              "remarks" => $this->input->post('remarks'),
              "status" => "RA"
            ],
            "applicant_task_details" => null,
        ];
        $data = array(
          'status' => 'QUERY',
          'task_no' => null,
          'assigned_user'=> null,
          'assigned_ids'=> null,
          'action_taken_by'=>[
            'name'=> $this->session->userdata("name"),
            'user_id'=>new ObjectId($this->session->userdata('userId')->{'$id'})
          ]
        );
        }


      }
      // $insert = $this->mongo_db->where(['rtps_trans_id' => $appl_no])->push("execution_data", $dataToInsert)->update("intermediate_ids");

      $insert = $this->action_task_model->insert($dataToInsert);
      if($insert){
        $this->application_model->update_where(['rtps_trans_id' => $appl_no], $data);
        if($task_no == '9'){
          redirect('iservices/wptbc/dscsign/index/'.$appl_no);
        }
        else{
          $this->session->set_flashdata('success', 'Action taken successfully !!!');
          redirect('iservices/superadmin/all-applications');
        }
      }
    }




    // for testing new format data
    public function save_action_testing(){
      $collection = 'action_task_model';
      $task_no = base64_decode($this->input->post('task_no'));
      $appl_no = base64_decode($this->input->post('appl_no'));
      $service_id = base64_decode($this->input->post('service'));
      $action_type = $this->input->post('action_type');
      // pre($task_no);
      $task_name = $this->Task_list_model->get_task_name($service_id, $task_no);
      // pre($service_id);
      $task_name = $task_name[0]->task_name;
      if($task_no == '1') {
        $receive_date = $this->input->post('receive_date');
        $users = $this->input->post('users');
        foreach($users as $us){
          $user_list[] = new ObjectId($us);
        }
        
        if($action_type == 'R'){
          $dataToInsert = [
            "task_details" => [
              "appl_no" => $appl_no,
              "remarks" => null,
              "task_id" => null,
              "action_no" => $task_no,
              "task_name" => $task_name,
              "task_type" => 1,
              "user_name" => $this->session->userdata("name"),
              "service_id" => "WPTBC-01",
              "user_detail" => $this->session->userdata("name"),
              "action_taken" => "Y",
              "payment_date" => null,
              "payment_mode" => null,
              "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
              "next_users" => null,
              "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
              "received_time" => $receive_date,
            ],
            "official_form_details" => [ 
              "action" => "Rejected",
              "documents" => null ,
              "remarks" => $this->input->post('remarks'),
              "status" => "R"
            ],
            "applicant_task_details" => null,
        ];

        $data = array(
          'status' => 'REJECTED',
          'task_no' => null,
          'assigned_user'=> null,
          'assigned_ids'=> null,
          'action_taken_by'=>[
            'name'=> $this->session->userdata("name"),
            'user_id'=>new ObjectId($this->session->userdata('userId')->{'$id'})
          ]
        );
        }
        elseif($action_type == 'F'){
          
            $dataToInsert = [
              "task_details" => [
                "appl_no" => $appl_no,
                "remarks" => null,
                "task_id" => null,
                "action_no" => $task_no,
                "task_name" => $task_name,
                "task_type" => 1,
                "user_name" => $this->session->userdata("name"),
                "service_id" => "WPTBC-01",
                "user_detail" => $this->session->userdata("name"),
                "action_taken" => "Y",
                "payment_date" => null,
                "payment_mode" => null,
                "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                "next_users" => $user_list,
                "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "received_time" => $receive_date,
              ],
              "official_form_details" => [ 
                "action" => "Forward",
                "documents" => null ,
                "remarks" => $this->input->post('remarks'),
                "status" => "F"
              ],
              "applicant_task_details" => null,
          ];
          $data = array(
            'status' => 'UNDER PROCESS',
            'task_no' => '2',
            'assigned_user'=> 'DA',
            'assigned_ids'=> $user_list
          );
          }
          elseif($action_type == 'RA'){
            $dataToInsert = [
              "execution_data" => [
              "task_details" => [
                "appl_no" => $appl_no,
                "remarks" => $this->input->post('applicant_query'),
                "task_id" => null,
                "action_no" => $task_no,
                "task_name" => $task_name,
                "task_type" => 1,
                "user_name" => $this->session->userdata("name"),
                "service_id" => "WPTBC-01",
                "user_detail" => $this->session->userdata("name"),
                "action_taken" => "Y",
                "payment_date" => null,
                "payment_mode" => null,
                "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                "next_users" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "received_time" => $receive_date,
              ],
              "official_form_details" => [ 
                "action" => "Revert",
                "documents" => null ,
                "remarks" => $this->input->post('remarks'),
                "status" => "RA"
              ],
              "applicant_task_details" => null,
          ]];
          $data = array(
            'status' => 'QUERY',
            'task_no' => null,
            'assigned_user'=> null,
            'assigned_ids'=> null,
            'action_taken_by'=>[
              'name'=> $this->session->userdata("name"),
              'user_id'=>new ObjectId($this->session->userdata('userId')->{'$id'})
            ]
          );
          }
        }  
        elseif($task_no == '2'){
          $users = $this->input->post('users');
          foreach($users as $us){
            $user_list[] = new ObjectId($us);
          }
          if($action_type == 'F'){
              $dataToInsert = [
                "task_details" => [
                  "appl_no" => $appl_no,
                  "remarks" => null,
                  "task_id" => null,
                  "action_no" => $task_no,
                  "task_name" => $task_name,
                  "task_type" => 1,
                  "user_name" => $this->session->userdata("name"),
                  "service_id" => "WPTBC-01",
                  "user_detail" => $this->session->userdata("name"),
                  "action_taken" => "Y",
                  "payment_date" => null,
                  "payment_mode" => null,
                  "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                  "next_users" => $user_list,
                  "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                  "received_time" => $receive_date,
                ],
                "official_form_details" => [ 
                  "action" => "Forward",
                  "documents" => null ,
                  "remarks" => $this->input->post('remarks'),
                  "status" => "F"
                ],
                "applicant_task_details" => null,
            ];
            $data = array(
              'status' => 'UNDER PROCESS',
              'task_no' => '3',
              'assigned_user'=> 'CO',
              'assigned_ids'=> $user_list
            );
        }
        elseif($action_type == 'RO'){
          $prev_users = $this->action_task_model->get_prev_users(new ObjectId($this->session->userdata('userId')->{'$id'}), $appl_no);
          $prev_user[]= new ObjectId($prev_users[0]->task_details->pull_user_id);
            $dataToInsert = [
              "task_details" => [
                "appl_no" => $appl_no,
                "remarks" => null,
                "task_id" => null,
                "action_no" => $task_no,
                "task_name" => $task_name.' - revert to previous',
                "task_type" => 1,
                "user_name" => $this->session->userdata("name"),
                "service_id" => "WPTBC-01",
                "user_detail" => $this->session->userdata("name"),
                "action_taken" => "Y",
                "payment_date" => null,
                "payment_mode" => null,
                "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
                "next_users" => $prev_user,
                "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "received_time" => $receive_date,
              ],
              "official_form_details" => [ 
                "action" => "Revert to Official",
                "documents" => null ,
                "remarks" => $this->input->post('remarks'),
                "status" => "RO"
              ],
              "applicant_task_details" => null,
          ];
          $data = array(
            'status' => 'UNDER PROCESS',
            // from 3 to 1
            'task_no' => '1',
            'assigned_user'=> 'DPS',
            'assigned_ids'=> $prev_user
          );
        }
        }

        // { $push: { <field>: { $each: [ <value1>, <value2> ... ] } } }
      // $insert = $this->mongo_db->where(['rtps_trans_id' => $appl_no])->push("execution_data", $dataToInsert, 0)->update("intermediate_ids");
      // $this->application_model->update_where(['rtps_trans_id' => $appl_no], $data);
      // $data = $this->mongo_db->aggregate($collection, $operations, $options);
        $test_data  = [
          "task_details" => [
            "appl_no" => $appl_no,
            "remarks" => null,
            "task_id" => null,
            "action_no" => $task_no,
            "task_name" => $task_name.' - revert to previous',
            "task_type" => 1,
            "user_name" => $this->session->userdata("name"),
            "service_id" => "WPTBC-01",
            "user_detail" => $this->session->userdata("name"),
            "action_taken" => "Y",
            "payment_date" => null,
            "payment_mode" => null,
            // "pull_user_id" => new ObjectId($this->session->userdata('userId')->{'$id'}),
            // "next_users" => $prev_user,
            // "executed_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            // "received_time" => $receive_date,
          ]];
      // $query = array('rtps_trans_id'=>$appl_no);
      // $historyDoc = array('_id' => '123', 
      //               'count' => 1,
      //               '$push' => array('events' => array('name'=>'abc')));

      //               $this->application_model->update($query, $historyDoc,
      //        array('safe'=>true,'timeout'=>5000,'upsert'=>true));

      //        $data = array(
      //         "domain"=>"superduperyoyo.com",
      //         "number"=>123,
      //         "week"=>5,
      //         "year"=>2012
      // );
      // $this->application_model->update(
      //         array( 'rtps_trans_id' => 'WPTBC2226044896382'),
      //         array( '$set' => array( 'data' => array( 201205123 => $data )))
      // );

      // $this->application_model->update($query,
      // array('$push'=>array('test_data'=>$test_data)),
      // array('safe'=>true,'timeout'=>5000,'upsert'=>true));
      // $insert = $this->mongo_db->where(['rtps_trans_id' => $appl_no])->push("execution_data", $dataToInsert, 0)->update("intermediate_ids");

      // $this->mongo_db->update(array("rtps_trans_id"=>'WPTBC2226044896382'),array('$push' => array("done_by"=>"2")));
      // $db = new MongoDB\Driver\BulkWrite();
      // $collection = $this->mongo_db->db->selectCollection('intermediate_ids');
      // $data = array('sitename'=> 'surfinme', 'title' => 'Mongodb');
      // $collection->update(array('rtps_trans_id' => 'WPTBC2226044896382'), array('$set' => $data), array("upsert" => false));

      }
}
