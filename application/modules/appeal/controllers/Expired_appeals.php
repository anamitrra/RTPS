<?php

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Expired_appeals extends Rtps
{

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if($this->session->userdata('role')->slug != 'RA'){
            redirect('appeal/dashboard');
        }
    }
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $this->load->view('includes/header');
        $this->load->view('expired_appeals/index');
        $this->load->view('includes/footer');
    }
    
    /**
     * view_appeal
     *
     * @param mixed $ref_id
     * @return void
     */
    public function view_appeal($ref_id)
    {
        $appealApplication = $this->appeal_application_model->get_appeal_details_for_everyone($ref_id);
        //pre($appealApplication);
        if(!isset($appealApplication) && empty($appealApplication)){
            redirect(base_url('appeal/list'));
        }

//        if(count($appealApplication))
        $appealApplicationPrevious = isset($appealApplication->ref_appeal_id) ? $this->appeal_application_model->get_by_appeal_id($appealApplication->ref_appeal_id) : null;
        $appealProcessPreviousList = isset($appealApplication->ref_appeal_id) ? $this->appeal_process_model->get_where('appeal_id', $appealApplicationPrevious->appeal_id) : null;
        //$forwardAbleUserList = $this->users_model->get_where_in(array('roleId' => getRoleIdByKey(array('DPS', 'APPELLATE_AUTH', 'REVIEWING_AUTH')))); // don't remove
        $data = [
            'appealApplication' => $appealApplication,
            'appealApplicationPrevious' => $appealApplicationPrevious,
            'appealProcessPreviousList' => $appealProcessPreviousList,
            'applicationData' => $appealApplication->application_details,
            //'forwardAbleUserList' => $forwardAbleUserList, // don't remove
        ];
        $this->load->view('includes/header');
        $this->load->view('ams/appeal_view', $data);
        $this->load->view('includes/footer');
    }
    /**
     * get_records
     *
     * @param mixed $appealType
     * @return void
     */
    public function get_expired_appeals($appealType = 'first')
    {
        $this->load->model("expired_appeals_model");
        $columns = array(
            0 => "initiated_data.appl_ref_no",
            1 => "initiated_data.applied_by",
            2 => "initiated_data.submission_date",
            3 => "initiated_data.submission_location",
            5 => "initiated_data.version_no",
        );
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];
        $totalData = $this->expired_appeals_model->total_rows();
        $totalFiltered = $totalData;
        $filter = array();
        if (empty($this->input->post("search")["value"])) {
            $this->session->set_userdata($filter);
            $records = $this->expired_appeals_model->appeals_filter($limit, $start, $filter, $order, $dir);
            $totalFiltered = $this->expired_appeals_model->appeals_filter_count();
        } else {
            $search = trim($this->input->post("search")["value"]);
            $records = $this->expired_appeals_model->appeals_search_rows($limit, $start, $search, $order, $dir);
            $totalFiltered = $this->expired_appeals_model->appeals_tot_search_rows($search);
        }
        $data = array();
        //pre($records);
        if (!empty($records)) {
            $sl = 1;
            foreach ($records as $rows) {
                if ($appealType == 'first' && isset($rows->ref_appeal_id)) {
                    continue;
                }
                if ($appealType == 'second' && !isset($rows->ref_appeal_id)) {
                    continue;
                }
                // todo : need checking
                $btns = '<a href="#!" class="btn btn-sm btn-outline-primary" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" onclick="approveExpiredApplications(this)">Approve</a>';
                $btns.='<a href="#!" class="btn btn-sm btn-outline-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" onclick="rejectExpiredApplications(this)">Reject</a>';
//                $btns = '<a href="' . base_url('appeal/process/view/' . $rows->{'_id'}->{'$id'}) . '" class="btn btn-sm btn-info" title="Process">Process</a> ';
                $nestedData["sl_no"] = $sl;
                $nestedData["appeal_id"] = strtoupper($rows->appeal_id);
                $nestedData["name"] = $rows->applicant_name;
                $nestedData["contact_no"] = ($rows->contact_in_addition_contact_number) ? $rows->additional_contact_number : $rows->contact_number;
                $nestedData["appeal_date"] = format_mongo_date($rows->created_at);
                switch ($rows->process_status) {
                    case 'reply':
                        $process_status = '<span class="badge badge-info">Reply</span>';
                        break;
                    case 'completed':
                        $process_status = '<span class="badge badge-success">Resolved</span>';
                        break;
                    case 'remark':
                        $process_status = '<span class="badge badge-warning">Remark</span>';
                        break;
                    case 'penalize':
                        $process_status = '<span class="badge badge-primary">Penalized</span>';
                        break;
                    case 'forward':
                        $process_status = '<span class="badge badge-secondary">Forwarded</span>';
                        break;
                    case 'in-progress':
                        $process_status = '<span class="badge badge-wrapper">In Progress</span>';
                        break;
                    default:
                        $process_status = '<span class="badge badge-secondary">Initiated</span>';
                        break;
                }
                $nestedData["process_status"] = $process_status;
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
        //echo json_encode($json_data);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($json_data));
    }
    
    /**
     * excel_export
     *
     * @return void
     */
    public function excel_export()
    {
        $appeal_type = $this->input->get('appeal_type', true);
        $my_appeals_only = $this->input->get('my_appeals_only', true);
        if (isset($my_appeals_only)) {
            // set filter for id and role,
            $my_role = getRoleKeyById($this->session->userdata('role')->{'_id'}->{'$id'});
            switch ($my_role) {
                case 'DPS':
                    $filter['dps_id']       = $this->session->userdata('userId');
                    break;
                case 'AA':
                    $filter['appellate_id'] = $this->session->userdata('userId');
                    break;
                case 'RA':
                    $filter['reviewing_id'] = $this->session->userdata('userId');
                    break;
                default:
                    break;
            }
        }
        if ($appeal_type == 'second') {
            // fetch second appeal data
            $filter['ref_appeal_id'] = array('$exists' => true);
            $appealApplicationList = $this->appeal_application_model->get_where($filter);
        } else {
            // fetch first appeal data
            $filter['ref_appeal_id'] = array('$exists' => false);
            $appealApplicationList = $this->appeal_application_model->get_where($filter);
        }
        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        try {
            $objPHPExcel->setActiveSheetIndex(0);
            // set Header
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Appeal ID');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Applicant Name');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Contact Number');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Appeal Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Process Status');
            // set Row
            $rowCount = 2;
            foreach ($appealApplicationList as $appealApplication) {
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $appealApplication->appeal_id);
                $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $appealApplication->applicant_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $appealApplication->contact_number);
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $appealApplication->date_of_appeal);
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $appealApplication->process_status);
                $rowCount++;
            }
            $filename = "appeal_applications" . date("Y-m-d-H-i-s") . ".csv";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
            $objWriter->save('php://output');
        } catch (PHPExcel_Exception $e) {
            $this->session->set_flashdata('fail', 'Failed to generate excel. Please try again.');
            if ($appeal_type == 'second') {
                redirect(base_url('appeal/list'));
            } else {
                redirect(base_url('appeal/list/second'));
            }
        }
        exit(200);
    }
    /**
     * approve_expired_application
     *
     * @return void
     */
    public function approve_expired_application(){
        if($this->input->is_ajax_request()){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('approval_reason', 'Approval Reason', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags', array('required' => 'Invalid Appeal.'));
            // change expired flag to false and set the reason
            if($this->form_validation->run() === false){
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array(
                        'success' => false,
                        'validation_errors' => validation_errors('<li>', '</li>')
                    )));
            }
            $user=$this->session->userdata("userId");
            $expiredAppealInputs = array(
                'approval_reason' => $this->input->post('approval_reason',true),
                'approval_time' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
                'approved_by' =>new ObjectId($user->{'$id'}),
                'appeal_expiry_status' => false,
            );
            $appeal_id = $this->input->post('appeal_id',true);
            $this->load->model("expired_appeals_model");
            $this->expired_appeals_model->update($appeal_id,$expiredAppealInputs);

            $appealApplication = $this->expired_appeals_model->get_by_doc_id($appeal_id);

            $this->load->helper('app');
            $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

            // applicant notification
            $msg = 'Expired appeal approved for processing. Appeal ID ' . $appealApplication->appeal_id . '.';
            $msg = urlencode($msg);
            foreach ($applicantNotifiable['mobile'] as $contactMobile) {
                $this->sms->send($contactMobile, $msg);
            }
            // send email
            $emailBody = '<p>' . 'Expired appeal approved for processing. Appeal ID ' . $appealApplication->appeal_id . '</p>';
            foreach ($applicantNotifiable['email'] as $contactEmail) {
                $this->remail->sendemail($contactEmail, "Expired appeal approved", $emailBody);
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => true,
                    'msg' => 'Appeal Approved successfully.'
                )));
        }
    }
    /**
     * reject_expired_application
     *
     * @return void
     */
    public function reject_expired_application(){
        if($this->input->is_ajax_request()){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('reject_reason', 'Reject Reason', 'trim|required|xss_clean|strip_tags');
            $this->form_validation->set_rules('reject_appeal_id', 'Appeal', 'trim|required|xss_clean|strip_tags', array('required' => 'Invalid Appeal.'));
            // change expired flag to false and set the reason
            if($this->form_validation->run() === false){
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array(
                        'success' => false,
                        'validation_errors' => validation_errors('<li>', '</li>')
                    )));
            }
            $user=$this->session->userdata("userId");
            $expiredAppealInputs = array(
                'reject_reason' => $this->input->post('reject_reason',true),
                'reject_time' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
                'rejected_by' =>new ObjectId($user->{'$id'}),
                'appeal_expiry_status' => true,
                'is_rejected' => true,
            );
            $appeal_id = $this->input->post('reject_appeal_id',true);
            $this->load->model("expired_appeals_model");
            $this->expired_appeals_model->update($appeal_id,$expiredAppealInputs);

            $appealApplication = $this->expired_appeals_model->get_by_doc_id($appeal_id);

            $this->load->helper('app');
            $applicantNotifiable = getApplicantNotifiable($appealApplication->contact_number, $appealApplication->email_id, $appealApplication->contact_in_addition_contact_number, $appealApplication->additional_contact_number, $appealApplication->contact_in_addition_email, $appealApplication->additional_email_id);

            // applicant notification
            $msg = 'Expired appeal Rejected for processing. Appeal ID ' . $appeal_id . '.';
            $msg = urlencode($msg);
            foreach ($applicantNotifiable['mobile'] as $contactMobile) {
                $this->sms->send($contactMobile, $msg);
            }
            // send email
            $emailBody = '<p>' . 'Expired appeal Rejected for processing. Appeal ID ' . $appeal_id . '</p>';
            foreach ($applicantNotifiable['email'] as $contactEmail) {
                $this->remail->sendemail($contactEmail, "Expired appeal approved", $emailBody);
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => true,
                    'msg' => 'Appeal Rejected successfully.'
                )));
        }
    }
}