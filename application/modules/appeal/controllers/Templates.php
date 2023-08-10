<?php


use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Templates extends Rtps
{
    /**
     * Templates constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('template_model');
        $this->load->library('form_validation');
        $this->load->helper('app');
        $role = $this->session->userdata("role");
        if (!in_array($role->slug,['SA','ADMIN','DA','RR','RA','MOC','AA'])) {
            redirect(base_url("appeal/login/logout"));
        }
    }

    public function index(){

//        if($this->input->is_ajax_request()){
//
//
//            $columns = array(
//                0 => "initiated_data.appl_ref_no",
//                1 => "initiated_data.applied_by",
//                2 => "initiated_data.submission_date",
//                3 => "initiated_data.submission_location",
//                5 => "initiated_data.version_no",
//            );
//            $limit = $this->input->post("length");
//            $start = $this->input->post("start");
//            $order = $columns[$this->input->post("order")[0]["column"]];
//            $dir = $this->input->post("order")[0]["dir"];
//            $totalData = $this->appeal_application_model->total_rows();
//            $totalFiltered = $totalData;
//            $filter = array();
//            if (empty($this->input->post("search")["value"])) {
//                $this->session->set_userdata($filter);
//                $records = $this->appeal_application_model->appeals_filter($limit, $start, $filter, $order, $dir);
//                $totalFiltered = $this->appeal_application_model->appeals_filter_count();
//            } else {
//                $search = trim($this->input->post("search")["value"]);
//                $records = $this->appeal_application_model->appeals_search_rows($limit, $start, $search, $order, $dir);
//                $totalFiltered = $this->appeal_application_model->appeals_tot_search_rows($search);
//            }
//            $data = array();
//            //pre($records);
//            if (!empty($records)) {
//                $sl = 1;
//                foreach ($records as $rows) {
//                    if ($appealType == 'first' && isset($rows->ref_appeal_id)) {
//                        continue;
//                    }
//                    if ($appealType == 'second' && !isset($rows->ref_appeal_id)) {
//                        continue;
//                    }
//                    $btns = '<a href="#!" class="btn btn-sm btn-warning" data-appl_ref_no="' . $rows->appeal_id . '" data-id="' . $rows->{'_id'}->{'$id'} . '" title="View" class="modal-show">View</a>';
//                    $btns = '<a href="' . base_url('ams/process/view/' . $rows->{'_id'}->{'$id'}) . '" class="btn btn-sm btn-info" title="Process">Process</a> ';
//                    // <a href="'.base_url("users/update/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Edit" class=""><span class="fa fa-edit" aria-hidden="true"></span></a>
//                    // <a href="'.base_url("users/delete/$rows->t_id").'" data-toggle="tooltip" data-placement="top" title="Delete" class="" onclick="return confirm(\'Are You Sure you want to delete?\')"><span class="fas fa-trash" aria-hidden="true"></span></a>
//                    $nestedData["sl_no"] = $sl;
//                    $nestedData["appeal_id"] = strtoupper($rows->appeal_id);
//                    $nestedData["name"] = $rows->applicant_name;
//                    $nestedData["contact_no"] = ($rows->contact_in_addition_contact_number) ? $rows->additional_contact_number : $rows->contact_number;
//                    $nestedData["appeal_date"] = format_mongo_date($rows->created_at);
//                    switch ($rows->process_status) {
//                        case 'reply':
//                            $process_status = '<span class="badge badge-info">Reply</span>';
//                            break;
//                        case 'completed':
//                            $process_status = '<span class="badge badge-success">Resolved</span>';
//                            break;
//                        case 'remark':
//                            $process_status = '<span class="badge badge-warning">Remark</span>';
//                            break;
//                        case 'penalize':
//                            $process_status = '<span class="badge badge-primary">Penalized</span>';
//                            break;
//                        case 'forward':
//                            $process_status = '<span class="badge badge-secondary">Forwarded</span>';
//                            break;
//                        case 'in-progress':
//                            $process_status = '<span class="badge badge-wrapper">In Progress</span>';
//                            break;
//                        default:
//                            $process_status = '<span class="badge badge-secondary">Initiated</span>';
//                            break;
//                    }
//                    $nestedData["process_status"] = $process_status;
//                    $nestedData["action"] = $btns;
//                    $data[] = $nestedData;
//                    $sl++;
//                }
//            }
//            $json_data = array(
//                "draw" => intval($this->input->post("draw")),
//                "recordsTotal" => intval($totalData),
//                "recordsFiltered" => intval($totalFiltered),
//                "data" => $data,
//            );
//            //echo json_encode($json_data);
//            return $this->output
//                ->set_content_type('application/json')
//                ->set_status_header(200)
//                ->set_output(json_encode($json_data));
//        }

        $templateList = $this->template_model->get_all([]);
        $this->load->model('action_type_model');
        $action_types = $this->action_type_model->get_all([]);

        $data = compact(array('action_types','templateList'));

        $this->load->view('includes/header');
        $this->load->view('templates/index',$data);
        $this->load->view('includes/footer');
    }

    public function store(){
        if($this->input->method(true) !== 'POST'){
            show_error('Method Not Allowed','403','Invalid Method');
        }
        $this->form_validation->set_rules('template_name', 'Template Name', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('appeal_type', 'Appeal Type', 'trim|required|xss_clean|strip_tags|alpha');
        $this->form_validation->set_rules('action_type', 'Action Type', 'trim|required|xss_clean|strip_tags');
        $this->form_validation->set_rules('template_status', 'Template Status', 'trim|required|xss_clean|strip_tags|alpha');
        $this->form_validation->set_rules('template_summernote_content', 'Template Content', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            show_error("error",'403',validation_errors());
            redirect(base_url('appeal/templates'));
            exit(500);
        }
        $template_name = $this->input->post('template_name',true);
        $action_type = $this->input->post('action_type',true);
        $appeal_type = $this->input->post('appeal_type',true);
        $template_status = $this->input->post('template_status',true);
        $this->load->helper('app');
        $template_content = html_encode($_POST['template_summernote_content']);
        $templateInput = array(
            'template_name'    => $template_name,
            'action_type_id'   => new ObjectId($action_type),
            'appeal_type'      => $this->input->post('appeal_type'),
            'template_status'  => $template_status,
            'template_content' => $template_content,
        );
        if($this->input->post('template_id')){ // update
            $templateInput['updated_at'] = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000);
            $this->template_model->update($this->input->post('template_id',true),$templateInput);
            $successMsg = array('success' => 'Template updated successfully');
        }else{ // insert
            $templateInput['created_at'] = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000);
            $this->template_model->insert($templateInput);
            $successMsg = array('success' => 'Template stored successfully');
        }

        $this->session->set_flashdata($successMsg);
        redirect(base_url('appeal/templates'));

        exit(200);
    }

    public function show($templateId){
        if($this->input->is_ajax_request()){
            $template = $this->template_model->get_by_doc_id($templateId);
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => true,
                    'template' => array(
                        'ref'              => $template->{'_id'}->{'$id'},
                        'action_type_id'   => $template->action_type_id,
                        'appeal_type'      => $template->appeal_type,
                        'template_name'    => $template->template_name,
                        'template_content' => html_decode($template->template_content),
                        'template_status'  => $template->template_status,
                    )
                )));
        }
        exit(404);
    }

    public function generate(){
        if($this->input->method(true) === 'GET'){ // !$this->input->is_ajax_request()
            $action = $this->input->get('action');
            $actionTo = '';
            if(count(explode('-',$action)) > 1){
                if(explode('-',$action)[0] == 'penalize'){
                    $actionTo = explode('-',$action)[1];
                    $action = explode('-',$action)[0];
                }
            }
            $notifiable = $this->input->get('notifiable');
            $appeal_id = $this->input->get('appeal_id');
            $appeal_type = $this->input->get('appeal_type');
            $this->load->model('appeal_application_model');
            $appeal_application = $this->appeal_application_model->get_with_related_by_appeal_id_no_application_data($appeal_id);
            $date_of_hearing = format_mongo_date($appeal_application[0]->date_of_hearing ?? $appeal_application[0]->tentative_hearing_date,'d-m-Y');
            $this->load->helper('model');
            $appellateDetails = getAppellateDetailsFromAppeal($appeal_application);
            $dpsDetails = getDPSDetailsFromAppeal($appeal_application);

            $searchNReplaceArray = array(
                '{name_of_the_office}'       => $appellateDetails->office_name,
//                '{aaidr_no}'                 => '__123__',
                '{dated}'                    => date('d-m-Y'),
                '{address_of_the_office}'    => $appellateDetails->office_address,
                '{name_of_the_appellant}'    => $appeal_application[0]->applicant_name,
                '{address_of_the_appellant}' => $appeal_application[0]->address_of_the_person,
                '{applied_service_name}'     => $appeal_application[0]->name_of_service,
                '{artps_no}'                 => $appeal_application[0]->appl_ref_no,
                '{ground_for_appeal}'        => $appeal_application[0]->ground_for_appeal,
                '{appeal_description}'       => $appeal_application[0]->appeal_description,
                '{appellate_designation}'    => $appellateDetails->designation
            );
            $this->load->model('action_type_model');
            $this->load->model('template_model');

            $templateFilter['appeal_type'] = $appeal_type;
            $templateFilter['template_status'] = 'active';

            switch ($action){
                case 'generate-disposal-order':
                    $templateFileName = 'appeal_disposal_order_'.uniqid();
                    $actionTypeFilter = array('slug' => 'DO');
                    $actionType = $this->action_type_model->first_where($actionTypeFilter);
                    $templateFilter['action_type_id'] = new ObjectId($actionType->{'_id'}->{'$id'});
                    $template = $this->template_model->first_where($templateFilter);
                    $searchNReplaceArray['{date_of_appeal}'] = format_mongo_date($appeal_application[0]->created_at,'d-m-Y');
                    $this->load->model('appeal_process_model');
                    $appealDateOfHearingFilter = array(
                        'appeal_id' => $appeal_application[0]->appeal_id,
                        'action' => 'provide-hearing-date'
                    );
                    if($appeal_type === 'second'){
                        $searchNReplaceArray['{date_of_hearing}'] = $date_of_hearing;
                    }else{
                        $forDateOfHearing = $this->appeal_process_model->first_where($appealDateOfHearingFilter);

                        if(empty((array)$forDateOfHearing)){

                            $searchNReplaceArray['{date_of_hearing}'] = format_mongo_date($appeal_application[0]->tentative_hearing_date,'d-m-Y');
//                        return $this->output
//                            ->set_content_type('application/json')
//                            ->set_status_header(200)
//                            ->set_output(json_encode(array(
//                                'success' => false,
//                                'msg' => 'No hearing date found as this appeal was not processed for hearing!!!'
//                            )));
                        }else{
                            $searchNReplaceArray['{date_of_hearing}'] = format_mongo_date($forDateOfHearing->date_of_hearing,'d-m-Y');
                        }
                    }
                    $searchNReplaceArray['{name_of_the_dps}'] = $dpsDetails->name;
                    $searchNReplaceArray['{dps_designation}'] = $dpsDetails->designation;
                    break;
                case 'generate-rejection-order':
                    $templateFileName = 'appeal_rejection_order_'.uniqid();
                    $actionTypeFilter = array('slug' => 'RA');
                    $actionType = $this->action_type_model->first_where($actionTypeFilter);
                    $templateFilter['action_type_id'] = new ObjectId($actionType->{'_id'}->{'$id'});
                    $template = $this->template_model->first_where($templateFilter);
                    break;
                case 'generate-penalty-order':
                    if($actionTo === 'appellate'){
                        $templateFileName = 'penalty_order_to_appellate_'.uniqid();
                        $actionTypeFilter = array('slug' => 'POTA');
                    }else{
                        $templateFileName = 'penalty_order_to_dps_'.uniqid();
                        $actionTypeFilter = array('slug' => 'PO');
                    }
                    $actionType = $this->action_type_model->first_where($actionTypeFilter);
                    $templateFilter['action_type_id'] = new ObjectId($actionType->{'_id'}->{'$id'});
                    $template = $this->template_model->first_where($templateFilter);

                    $this->load->model('appeal_process_model');
                    $appealDateOfHearingFilter = array(
                        'appeal_id' => $appeal_application[0]->appeal_id,
                        'action' => 'hearing'
                        );
                    if($appeal_type === 'second'){
                        $searchNReplaceArray['{date_of_hearing}'] = $date_of_hearing;
                    }else{

                        $forDateOfHearing = $this->appeal_process_model->first_where($appealDateOfHearingFilter);

                        if(empty((array)$forDateOfHearing)){
                            $searchNReplaceArray['{date_of_hearing}'] = '...';
//                        return $this->output
//                            ->set_content_type('application/json')
//                            ->set_status_header(200)
//                            ->set_output(json_encode(array(
//                                'success' => false,
//                                'msg' => 'No hearing date found as this appeal was not processed for hearing!!!'
//                            )));
                        }else{
                            $searchNReplaceArray['{date_of_hearing}'] = format_mongo_date($forDateOfHearing->date_of_hearing,'d-m-Y');
                        }
                    }
                    $searchNReplaceArray['{date_of_appeal}'] = format_mongo_date($appeal_application[0]->created_at);
                    $searchNReplaceArray['{name_of_the_dps}'] = $dpsDetails->name;
                    $searchNReplaceArray['{dps_designation}'] = $dpsDetails->designation;
                    $searchNReplaceArray['{penalty_amount}'] = $this->input->get('contentToReplace',true)['penalty_amount'];
                    $searchNReplaceArray['{number_of_days_of_delay}'] = $this->input->get('contentToReplace',true)['number_of_days_of_delay'];
                    $searchNReplaceArray['{penalty_should_by_paid_within_days}'] = $this->input->get('contentToReplace',true)['penalty_should_by_paid_within_days'];
                    $searchNReplaceArray['{certificate_to_be_issued_within_days}'] = $this->input->get('contentToReplace',true)['certificate_to_be_issued_within_days'];

                    $inputsForGenerateTemplate['penalty_amount']=$this->input->get('contentToReplace',true)['penalty_amount'];
                    $inputsForGenerateTemplate['number_of_days_of_delay']=$this->input->get('contentToReplace',true)['number_of_days_of_delay'];
                    $inputsForGenerateTemplate['penalty_should_by_paid_within_days']=$this->input->get('contentToReplace',true)['penalty_should_by_paid_within_days'];
                    $inputsForGenerateTemplate['certificate_to_be_issued_within_days']=$this->input->get('contentToReplace',true)['certificate_to_be_issued_within_days'];
                    $inputsForGenerateTemplate['total_penalty_amount']=$this->input->get('contentToReplace',true)['total_penalty_amount'];
                    break;
//                case 'seek-info':
//                    $actionTypeFilter = array('slug' => 'NFHTA');
//                    $actionType = $this->action_type_model->first_where($actionTypeFilter);
//                    $templateFilter = array('action_type_id' => $actionType->{'_id'}->{'$id'}, 'template_status' => 'active');
//                    $template = $this->template_model->first_where($templateFilter);
//                    pre($template);
//                    $searchArray = array();
//                    $replaceArray = array();
//                    $generateTemplate = str_replace($searchArray,$replaceArray,html_decode($template->template_content));
//                    break;
                case 'generate-hearing-order':

                    $templateFileName = 'hearing_order_'.uniqid();
                    if($notifiable == 'appellant'){
                        $actionTypeFilter = array('slug' => 'NFHTA');
                    }else{
                        $actionTypeFilter = array('slug' => 'NFHTD');
                        if($appeal_application[0]->appeal_type == '2'){
                            $searchNReplaceArray['{name_of_the_appellate}'] = $appellateDetails->name;
                            $searchNReplaceArray['{address_of_the_appellate}'] = $appellateDetails->office_address;
                            $templateFilter['appeal_type'] = 'second';
                        }else{
                            $templateFilter['appeal_type'] = 'first';
                        }
                    }
                    $actionType = $this->action_type_model->first_where($actionTypeFilter);
                    $templateFilter['action_type_id'] = new ObjectId($actionType->{'_id'}->{'$id'});
                    $template = $this->template_model->first_where($templateFilter);

                    if(in_array($notifiable,['dps','both'])){
                        $searchNReplaceArray['{name_of_the_dps}'] = $dpsDetails->name;
                        $searchNReplaceArray['{address_of_the_dps}'] = $dpsDetails->office_address;
                    }
                    break;
//                case 'issue-order':
//                    $actionTypeFilter = array('slug' => 'NFHTA');
//                    $actionType = $this->action_type_model->first_where($actionTypeFilter);
//                    $templateFilter = array('action_type_id' => $actionType->{'_id'}, 'template_status' => 'active');
//                    $template = $this->template_model->first_where($templateFilter);
//                    $searchArray = array();
//                    $replaceArray = array();
//                    $generateTemplate = str_replace($searchArray,$replaceArray,html_decode($template->template_content));
//                    break;
                default:
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array(
                            'success' => false
                        )));
            }
            switch ($action){
                case "generate-hearing-order":

                    // get date of hearing
                    $this->load->model('appeal_process_model');
                    $latestProvideHearingDateProcess = $this->appeal_process_model->last_where(['action' => 'provide-hearing-date']);
                    if($appeal_type === 'second'){
                        $searchNReplaceArray['{date_of_hearing}'] = $date_of_hearing;
                    }else {
                        $searchNReplaceArray['{date_of_hearing}'] = format_mongo_date($latestProvideHearingDateProcess->date_of_hearing, 'd-m-Y');
                    }
                    $searchNReplaceArray['{documents_for_hearing}'] = $this->input->get('contentToReplace',true)['documents_for_hearing'] ?? '';
                    break;
                case "generate-rejection-order":
                    $searchNReplaceArray['{reason_for_rejection}'] = $this->input->get('contentToReplace',true)['reason_for_rejection'];
                    break;
                default:
                    if(strpos($action,'generate-penalty-order')){
                        $searchNReplaceArray['{penalty_amount}'] = $this->input->get('contentToReplace',true)['penalty_amount'];
                        $searchNReplaceArray['{penalty_should_by_paid_within_days}'] = $this->input->get('contentToReplace',true)['penalty_should_by_paid_within_days'];
                        $searchNReplaceArray['{certificate_to_be_issued_within_days}'] = $this->input->get('contentToReplace',true)['certificate_to_be_issued_within_days'];
                    }
                    break;
            }
            if (!in_array($action, ['in-progress', 'forward','seek-info','issue-order',''])) {
                $searchNReplaceArray['{order_no}'] = $this->input->get('contentToReplace',true)['order_no'];
            }
            $searchNReplaceArray['{additional_content}'] = $this->input->get('contentToReplace',true)['additional_content'];

            $generateTemplate = str_replace(array_keys($searchNReplaceArray),array_values($searchNReplaceArray),html_decode($template->template_content));
//            echo $generateTemplate;die();
//            $generateTemplate = "<style>table, tr, td {border: none; border-collapse: collapse;}</style>".$generateTemplate;
//            $generateTemplate = $this->load->view('includes/printables/header',"",TRUE).$generateTemplate.$this->load->view('includes/printables/footer',"",TRUE);
            $this->load->library('pdf');
            $this->load->helper('app');

            // check last process and update the last one if same action type or save new for new action type
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $appeal_application[0]->appeal_id]);

            if($notifiable !== ''){
                $inputsForGenerateTemplate['templateContent'][$notifiable] = $generateTemplate;
            }else{
                $inputsForGenerateTemplate['templateContent']['order'] = $generateTemplate;
            }
            
            if($latestProcess->action === $action){
                // update generated document
                if( $notifiable !== ''){
                    foreach ($latestProcess->templateContent as $forUserType => $tContent){
                        if($forUserType !== $notifiable){
                            $inputsForGenerateTemplate['templateContent'][$forUserType] = $tContent;
                        }
                    }
                }
                $this->appeal_process_model->update_where(['_id' => new ObjectId($latestProcess->{'_id'}->{'$id'})],$inputsForGenerateTemplate);
            }else{
                // create new document
                $actionTakenBy = $this->session->userdata('userId')->{'$id'};

                $inputsForGenerateTemplate['appeal_id']       = $appeal_application[0]->appeal_id;
                $inputsForGenerateTemplate['action']          = $action;
                $inputsForGenerateTemplate['action_taken_by'] = new ObjectId($actionTakenBy);
                $inputsForGenerateTemplate['message']         = 'Order Generated.';
                $inputsForGenerateTemplate['created_at']      = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000);
                $this->appeal_process_model->insert($inputsForGenerateTemplate);
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => true,
                    'generated_template' => $this->pdf->generate($generateTemplate,$templateFileName)
                )));
        }
        show_404();
        exit();
    }

    public function view_order(){
        //TODO:need to modify
        if($this->input->method(true) === 'GET'){ // !$this->input->is_ajax_request()
            $action = $this->input->get('action');
            $actionTo = '';
            if(count(explode('-',$action)) > 1){
                if(explode('-',$action)[0] == 'penalize'){
                    $actionTo = explode('-',$action)[1];
                    $action = explode('-',$action)[0];
                }
            }
            $notifiable = $this->input->get('notifiable');
            $appeal_id = $this->input->get('appeal_id');
            $appeal_type = $this->input->get('appeal_type');
            $this->load->model('appeal_application_model');
            $appeal_application = $this->appeal_application_model->get_with_related_by_appeal_id_no_application_data($appeal_id);
             $date_of_hearing = format_mongo_date($appeal_application[0]->date_of_hearing ?? $appeal_application[0]->tentative_hearing_date,'d-m-Y');
            $this->load->helper('model');
            $appellateDetails = getAppellateDetailsFromAppeal($appeal_application);
            $dpsDetails = getDPSDetailsFromAppeal($appeal_application);

            $searchNReplaceArray = array(
                '{name_of_the_office}'       => $appellateDetails->office_name,
//                '{aaidr_no}'                 => '__123__',
                '{dated}'                    => date('d-m-Y'),
                '{address_of_the_office}'    => $appellateDetails->office_address,
                '{name_of_the_appellant}'    => $appeal_application[0]->applicant_name,
                '{address_of_the_appellant}' => $appeal_application[0]->address_of_the_person,
                '{applied_service_name}'     => $appeal_application[0]->name_of_service,
                '{artps_no}'                 => $appeal_application[0]->appl_ref_no,
                '{ground_for_appeal}'        => $appeal_application[0]->ground_for_appeal,
                '{appeal_description}'       => $appeal_application[0]->appeal_description,
                '{appellate_designation}'    => $appellateDetails->designation
            );
            $this->load->model('action_type_model');
            switch($action){
                case 'modify-hearing-order':
                case 'generate-hearing-order':

                $hearingGenerationProcesses = $this->mongo_db->where_in('action',['generate-hearing-order','modify-hearing-order','second-appeal-confirm-hearing-date'])->where('appeal_id',$appeal_id)->get('appeal_processes');
                foreach ($hearingGenerationProcesses as $hProcess){
                    switch ($hProcess->action){
                        case 'generate-hearing-order':
                        case 'modify-hearing-order':
                            $action = 'modify-hearing-order';
                            $msg = 'Order Modified.';
                            $latestProcess = $hProcess;
                            $lastHearingGenerationProcess = $hProcess;
                            $latestHearingOrderGenerationCreatedAt = $hProcess->created_at;
                            $latestProcessId = $hProcess->{'_id'}->{'$id'};
                            break;
                        case 'second-appeal-confirm-hearing-date':
                            $latestConfirmHearingProcess = $hProcess;
                            break;
                    }
                }
                if(isset($latestHearingOrderGenerationCreatedAt) && isset($latestConfirmHearingProcess) && strval($latestConfirmHearingProcess->created_at) < strval($latestHearingOrderGenerationCreatedAt)){
                    $this->load->model('order_model');
                    $filterLastHearingTemplate = [
                        'confirmed_hearing_date_process_id' => new ObjectId($latestConfirmHearingProcess->_id->{'$id'}),
                        'order_type' => 'hearing-order'
                    ];
                    $lastHearingTemplate = $this->order_model->first_where($filterLastHearingTemplate)->templateContent;
                }
                    $templateFileName = 'hearing_order_'.uniqid();
                    if($notifiable == 'appellant'){
                        $actionTypeFilter = array('slug' => 'NFHTA');
                    }else{
                        $actionTypeFilter = array('slug' => 'NFHTD');
                        if($appeal_application[0]->appeal_type == '2'){
                            $searchNReplaceArray['{name_of_the_appellate}'] = $appellateDetails->name;
                            $searchNReplaceArray['{address_of_the_appellate}'] = $appellateDetails->office_address;
                            $templateFilter['appeal_type'] = 'second';
                        }else{
                            $templateFilter['appeal_type'] = 'first';
                        }
                    }
                    $actionType = $this->action_type_model->first_where($actionTypeFilter);
                    $templateFilter['action_type_id'] = new ObjectId($actionType->{'_id'}->{'$id'});
                    $template = $this->template_model->first_where($templateFilter);

                    if(in_array($notifiable,['dps','both'])){
                        $searchNReplaceArray['{name_of_the_dps}'] = $dpsDetails->name;
                        $searchNReplaceArray['{address_of_the_dps}'] = $dpsDetails->office_address;
                    }
                    // get date of hearing
                    $this->load->model('appeal_process_model');
                    $latestProvideHearingDateProcess = $this->appeal_process_model->last_where(['action' => 'provide-hearing-date']);
                    if($appeal_type === 'second'){
                        $searchNReplaceArray['{date_of_hearing}'] = $date_of_hearing;
                    }else {
                        $searchNReplaceArray['{date_of_hearing}'] = format_mongo_date($latestProvideHearingDateProcess->date_of_hearing, 'd-m-Y');
                    }
                    $searchNReplaceArray['{documents_for_hearing}'] = $this->input->get('contentToReplace',true)['documents_for_hearing'] ?? '';

                    break;
                case 'generate-penalty-order':
                    $templateFileName = 'penalty_order_'.uniqid();
                    $searchNReplaceArray['{date_of_appeal}'] = format_mongo_date($appeal_application[0]->created_at);
                    $searchNReplaceArray['{name_of_the_dps}'] = $dpsDetails->name;
                    $searchNReplaceArray['{dps_designation}'] = $dpsDetails->designation;
                    $searchNReplaceArray['{penalty_amount}'] = $this->input->get('contentToReplace',true)['penalty_amount'];
                    $searchNReplaceArray['{number_of_days_of_delay}'] = $this->input->get('contentToReplace',true)['number_of_days_of_delay'];
                    $searchNReplaceArray['{penalty_should_by_paid_within_days}'] = $this->input->get('contentToReplace',true)['penalty_should_by_paid_within_days'];
                    $searchNReplaceArray['{certificate_to_be_issued_within_days}'] = $this->input->get('contentToReplace',true)['certificate_to_be_issued_within_days'];
                    $searchNReplaceArray['{remarks}'] = $this->input->get('contentToReplace',true)['remarks'];

                    $actionTypeFilter['slug']='PO';

                    $data['penalty_amount']=$this->input->get('contentToReplace',true)['penalty_amount'];
                    $data['number_of_days_of_delay']=$this->input->get('contentToReplace',true)['number_of_days_of_delay'];
                    $data['penalty_should_by_paid_within_days']=$this->input->get('contentToReplace',true)['penalty_should_by_paid_within_days'];
                    $data['certificate_to_be_issued_within_days']=$this->input->get('contentToReplace',true)['certificate_to_be_issued_within_days'];
                    $data['total_penalty_amount']=$this->input->get('contentToReplace',true)['total_penalty_amount'];
                    break;
                case 'generate-disposal-order':
                    $this->load->model('order_model');
                    $filterLastHearingTemplate = [
                        'appeal_id' => $appeal_id,
                        'order_type' => 'disposal-order'
                    ];
                    $lastHearingTemplate = $this->order_model->first_where($filterLastHearingTemplate);
                    if(!empty((array)$lastHearingTemplate)){
                        $lastHearingTemplate = $lastHearingTemplate->templateContent;
                    }
                    $templateFileName = 'appeal_disposal_order_'.uniqid();
                    $actionTypeFilter = array('slug' => 'DO');
                    $actionType = $this->action_type_model->first_where($actionTypeFilter);
                    $templateFilter['action_type_id'] = new ObjectId($actionType->{'_id'}->{'$id'});
                    $template = $this->template_model->first_where($templateFilter);
                    $searchNReplaceArray['{date_of_appeal}'] = format_mongo_date($appeal_application[0]->created_at,'d-m-Y');
                    $this->load->model('appeal_process_model');
                    $appealDateOfHearingFilter = array(
                        'appeal_id' => $appeal_application[0]->appeal_id,
                        'action' => 'provide-hearing-date'
                    );
                    if($appeal_type === 'second'){
                        $searchNReplaceArray['{date_of_hearing}'] = $date_of_hearing;
                    }else{
                        $forDateOfHearing = $this->appeal_process_model->first_where($appealDateOfHearingFilter);

                        if(empty((array)$forDateOfHearing)){

                            $searchNReplaceArray['{date_of_hearing}'] = format_mongo_date($appeal_application[0]->tentative_hearing_date,'d-m-Y');
//                        return $this->output
//                            ->set_content_type('application/json')
//                            ->set_status_header(200)
//                            ->set_output(json_encode(array(
//                                'success' => false,
//                                'msg' => 'No hearing date found as this appeal was not processed for hearing!!!'
//                            )));
                        }else{
                            $searchNReplaceArray['{date_of_hearing}'] = format_mongo_date($forDateOfHearing->date_of_hearing,'d-m-Y');
                        }
                    }
                    $searchNReplaceArray['{name_of_the_dps}'] = $dpsDetails->name;
                    $searchNReplaceArray['{dps_designation}'] = $dpsDetails->designation;
                    break;
                case 'generate-rejection-order':
                    $this->load->model('order_model');
                    $filterLastHearingTemplate = [
                        'appeal_id' => $appeal_id,
                        'order_type' => 'rejection-order'
                    ];
                    $lastHearingTemplate = $this->order_model->first_where($filterLastHearingTemplate);
                    if(!empty((array)$lastHearingTemplate)){
                        $lastHearingTemplate = $lastHearingTemplate->templateContent;
                    }
                    $templateFileName = 'appeal_rejection_order_'.uniqid();
                    $actionTypeFilter = array('slug' => 'RO');
                    $actionType = $this->action_type_model->first_where($actionTypeFilter);
                    $templateFilter['action_type_id'] = new ObjectId($actionType->{'_id'}->{'$id'});
                    $template = $this->template_model->first_where($templateFilter);
                    $searchNReplaceArray['{reason_for_rejection}'] = $this->input->get('contentToReplace',true)['reason_for_rejection'];
                    break;
                default:
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array(
                            'success' => false
                        )));
            }

            // pre($searchNReplaceArray);

            $this->load->model('action_type_model');
            $this->load->model('template_model');

            $templateFilter['appeal_type'] = $appeal_type;
            $templateFilter['template_status'] = 'active';

            $actionType = $this->action_type_model->first_where($actionTypeFilter);
            $templateFilter['action_type_id'] = new ObjectId($actionType->{'_id'}->{'$id'});
            $template = $this->template_model->first_where($templateFilter);
            
            if(in_array($notifiable,['dps','both'])){
                $searchNReplaceArray['{name_of_the_dps}'] = $dpsDetails->name;
                $searchNReplaceArray['{address_of_the_dps}'] = $dpsDetails->office_address;
            }

            $this->load->model('appeal_process_model');
            $latestProvideHearingDateProcess = $this->appeal_process_model->last_where(['action' => 'provide-hearing-date']);
            if($appeal_type === 'second'){
                $searchNReplaceArray['{date_of_hearing}'] = format_mongo_date($appeal_application[0]->date_of_hearing ?? $appeal_application[0]->tentative_hearing_date,'d-m-Y');
            }else {
                $searchNReplaceArray['{date_of_hearing}'] = format_mongo_date($latestProvideHearingDateProcess->date_of_hearing, 'd-m-Y');
            }
            $searchNReplaceArray['{documents_for_hearing}'] = $this->input->get('contentToReplace',true)['documents_for_hearing'] ?? '';
            
            
            if (!in_array($action, ['in-progress', 'forward','seek-info','issue-order',''])) {
                $searchNReplaceArray['{order_no}'] = $this->input->get('contentToReplace',true)['order_no'];
            }
            $searchNReplaceArray['{additional_content}'] = $this->input->get('contentToReplace',true)['additional_content'];

            $generateTemplate = str_replace(array_keys($searchNReplaceArray),array_values($searchNReplaceArray),html_decode($template->template_content));

            // $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $appeal_id]);
        
            // $data['generated_appellant']=false;
            // $data['generated_dps']=false;
            // if(!empty($latestProcess)){
            //     if(!empty($latestProcess->templateContent->appellant)){
            //         $data['generated_appellant']=true;
            //     }
            //     if(!empty($latestProcess->templateContent->dps)){
            //         $data['generated_dps']=true;
            //     }
                
            // }
//            $action="generate-hearing-order";
            $data['appeal_id']=$appeal_id;
            $data['docField']="";//$latestProcess->_id->{'$id'};
            $data['notifiable']=$notifiable;
            if(isset($lastHearingTemplate) && property_exists($lastHearingTemplate,$notifiable)){
                $data['content'] = $lastHearingTemplate->$notifiable;
            }elseif(isset($lastHearingTemplate) && property_exists($lastHearingTemplate,'order')){
                $data['content'] = $lastHearingTemplate->order;
            }else{
                $data['content'] = $generateTemplate;
            }
            return $this->output
                ->set_content_type('html')
                ->set_status_header(200)
                ->set_output($this->load->view('templates/edit',$data,true));
        }
        show_404();
        exit();
    }

    public function edit(){
        if($this->input->method(true) === 'GET'){

            $appeal_id = $this->input->get('appeal_id');
            $appeal_type = $this->input->get('appeal_type');
            $notifiable = $this->input->get('notifiable');
            $action = $this->input->get('action');
            if($action === 'upload-hearing-order'){
                $action = 'generate-hearing-order';
            }
            if($action === 'upload-disposal-order'){
                $action = 'generate-disposal-order';
            }
            if($action === 'upload-rejection-order'){
                $action = 'generate-rejection-order';
            }
          //  pre($action);
            $this->load->model('appeal_process_model');
            // $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $appeal_id]);
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $appeal_id,'action' => $action]);
            //pre($latestProcess);
            // pre($latestProcess->templateContent->$notifiable);
           // pre($latestProcess);
            $data['appeal_id']=$appeal_id;
            $data['docField']=$latestProcess->_id->{'$id'};
            $data['notifiable']=$notifiable;
            $data['content']=$latestProcess->templateContent->$notifiable;
             // $this->load->view('includes/header');
             $this->load->view('templates/edit',$data);
             $this->load->view('includes/footer');

            return;

        }
        show_404();
        exit();
    }

    function update_order(){
      // pre($this->input->post());
      $appeal_id=$this->input->post('appeal_id');
      $docField=$this->input->post('docField');
      $notifiable=$this->input->post('notifiable');
      $template_summernote_content= $_POST['template_summernote_content'];
    //  pre($template_summernote_content);
      $this->load->model('appeal_process_model');
      // $inputsForGenerateTemplate['templateContent'][$notifiable] = $template_summernote_content;
      $this->appeal_process_model->update_where(['_id' => new ObjectId($docField)],array("templateContent.".$notifiable=>$template_summernote_content));
      exit("Successfully updated");
    }
    function view_and_download_penalty_order(){
      
        // pre($this->input->post());
        $appeal_id=$this->input->post('appeal_id');
        $docField=$this->input->post('docField');
        $notifiable=$this->input->post('notifiable');
        $template_summernote_content= $_POST['template_summernote_content'];
      // pre($template_summernote_content);
      $templateFileName = 'penalty_order_'.$notifiable.'_'.uniqid();
     // $generateTemplate = $this->load->view('includes/printables/header',"",TRUE).$generateTemplate.$this->load->view('includes/printables/footer',"",TRUE);
      $this->load->library('pdf');
      $this->load->helper('app');
      $this->pdf->generate($template_summernote_content,$templateFileName,'D');
       
            // check last process and update the last one if same action type or save new for new action type
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $appeal_id]);
            $action="generate-penalty-order";
            $inputsForGenerateTemplate['templateContent'] = $template_summernote_content;
            $inputsForGenerateTemplate['penalty_amount'] = $this->input->post('penalty_amount');
            $inputsForGenerateTemplate['number_of_days_of_delay'] = $this->input->post('number_of_days_of_delay');
            $inputsForGenerateTemplate['penalty_should_by_paid_within_days'] = $this->input->post('penalty_should_by_paid_within_days');
            $inputsForGenerateTemplate['certificate_to_be_issued_within_days'] = $this->input->post('certificate_to_be_issued_within_days');
            $totalPenaltyAmount=$this->input->post('penalty_amount')*$this->input->post('number_of_days_of_delay');
            if($totalPenaltyAmount > 25000 ){
                $totalPenaltyAmount=25000;
            }
            $inputsForGenerateTemplate['total_penalty_amount'] = $totalPenaltyAmount;
            
            
            if($latestProcess->action === $action){
                // update generated document
               
                $this->appeal_process_model->update_where(['_id' => new ObjectId($latestProcess->{'_id'}->{'$id'})],$inputsForGenerateTemplate);
            }else{
                // create new document
                $actionTakenBy = $this->session->userdata('userId')->{'$id'};

                $inputsForGenerateTemplate['appeal_id']       = $appeal_id;
                $inputsForGenerateTemplate['action']          = $action;
                $inputsForGenerateTemplate['action_taken_by'] = new ObjectId($actionTakenBy);
                $inputsForGenerateTemplate['message']         = 'Order Generated.';
                $inputsForGenerateTemplate['created_at']      = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000);
                $this->appeal_process_model->insert($inputsForGenerateTemplate);
            }
    }
    function view_and_download($no_download = ''){
        try {
            $appeal_id=$this->input->post('appeal_id');
            $this->load->model('appeal_application_model');
            $appeal = $this->appeal_application_model->first_where(['appeal_id' => $appeal_id]);
            $docField=$this->input->post('docField');
            $notifiable=$this->input->post('notifiable');
            $template_summernote_content= $_POST['template_summernote_content'];
            // pre($template_summernote_content);
            $templateFileName = 'hearing_order_'.$notifiable.'_'.uniqid();
            // $generateTemplate = $this->load->view('includes/printables/header',"",TRUE).$generateTemplate.$this->load->view('includes/printables/footer',"",TRUE);
            $this->load->library('pdf');
            $this->load->helper('app');

            $actionExt = '';
            $hearingDateNotProvidedMsg = 'Hearing date not approved yet!!!';
            $actionFilterArray = ['generate-hearing-order','modify-hearing-order'];
            if(property_exists($appeal,'ref_appeal_id')){
//                $actionExt = 'second-appeal-';
                $hearingDateNotProvidedMsg = 'Hearing date not provided yet!!!';
                $actionFilterArray[] = 'second-appeal-confirm-hearing-date';
            }else{
                $actionFilterArray[] = 'provide-hearing-date';
            }

            // check last process and update the last one if same action type or save new for new action type
            $this->load->model('appeal_process_model');
            $hearingGenerationProcesses = $this->mongo_db->where_in('action',$actionFilterArray)->where('appeal_id',$appeal_id)->get('appeal_processes');

            $action = $actionExt.'generate-hearing-order';
            $msg = 'Order Generated.';
            $shouldUpdateProcess = false;
            foreach ($hearingGenerationProcesses as $hProcess){
                switch ($hProcess->action){
                    case 'generate-hearing-order':
                    case 'modify-hearing-order':
//                    case 'second-appeal-generate-hearing-order':
//                    case 'second-appeal-modify-hearing-order':
                        $action = $actionExt.'modify-hearing-order';
                        $msg = 'Order Modified.';
                        $latestProcess = $hProcess;
                        $lastHearingGenerationProcess = $hProcess;
                        $latestHearingOrderGenerationCreatedAt = $hProcess->created_at;
                        $latestProcessId = $hProcess->{'_id'}->{'$id'};
                        break;
                    case 'second-appeal-confirm-hearing-date':
                    case 'provide-hearing-date':
                        $latestConfirmHearingProcess = $hProcess;
                        break;
                }
            }
            $firstTimeGeneration =
                isset($latestConfirmHearingProcess) && (
                    isset($latestHearingGenerationProcess) ||
                    isset($latestHearingModificationProcess)
                );

            if($notifiable !== ''){
                $inputsForGenerateTemplate['templateContent'][$notifiable] = $template_summernote_content;
            }else{
                $inputsForGenerateTemplate['templateContent']['order'] = $template_summernote_content;
            }
            $actionTakenBy = $this->session->userdata('userId')->{'$id'};
            //if hearing generated before confirmation
            if(!isset($latestConfirmHearingProcess)){
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode(array(
                        'success' => false,
                        'msg' => $hearingDateNotProvidedMsg,
                    )));
            }

            if(!$firstTimeGeneration && isset($latestHearingOrderGenerationCreatedAt) && strval($latestConfirmHearingProcess->created_at) < strval($latestHearingOrderGenerationCreatedAt)){
                // then regenerate template
//                pre('if');
                $inputsForGenerateTemplate['appeal_id'] = $appeal_id;
                $inputsForGenerateTemplate['action'] = $action;
                $inputsForGenerateTemplate['action_taken_by'] = new ObjectId($actionTakenBy);
                $inputsForGenerateTemplate['message'] = $msg;
                $inputsForGenerateTemplate['created_at'] = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000);
                if( $notifiable !== ''){
                    foreach ($latestProcess->templateContent as $forUserType => $content){
                        if($forUserType !== $notifiable){
                            $inputsForGenerateTemplate['templateContent'][$forUserType] = $content;
                        }
                    }
                }
                if(isset($latestProcessId) && (strval($lastHearingGenerationProcess->action_taken_by) === $this->session->userdata('userId')->{'$id'})){
//                    pre('update');
                    // update generated document
                    $action = $inputsForGenerateTemplate['action'] = $lastHearingGenerationProcess->action;
                    $this->appeal_process_model->update_where(['_id' => new ObjectId($latestProcessId)],$inputsForGenerateTemplate);
                }else{
//                    pre('insert');
                    $this->appeal_process_model->insert($inputsForGenerateTemplate);
                }
            }else{
//                pre('else');
                // if hearing generated after confirmation then save content as current process
                // and use process name modify-hearing-order
//                if(isset($latestProcess) && $latestProcess->action === $action){
//                    pre('update');
//                    // update generated document
//                    if( $notifiable !== ''){
//                        foreach ($latestProcess->templateContent as $forUserType => $template_summernote_content){
//                            if($forUserType !== $notifiable){
//                                $inputsForGenerateTemplate['templateContent'][$forUserType] = $template_summernote_content;
//                            }
//                        }
//                    }
//                    $this->appeal_process_model->update_where(['_id' => new ObjectId($latestProcess->{'_id'}->{'$id'})],$inputsForGenerateTemplate);
//                }else{
//                    pre('insert');
                    // create new document
                    $actionTakenBy = $this->session->userdata('userId')->{'$id'};

                    $inputsForGenerateTemplate['appeal_id']       = $appeal_id;
//                    $inputsForGenerateTemplate['action']          = property_exists($appeal,'ref_appeal_id') ? 'second-appeal-generate-hearing-order':'generate-hearing-order';//$action;
                    $inputsForGenerateTemplate['action']          = 'generate-hearing-order';//$action;
                    $inputsForGenerateTemplate['action_taken_by'] = new ObjectId($actionTakenBy);
                    $inputsForGenerateTemplate['message']         = 'Order Generated.';
                    $inputsForGenerateTemplate['created_at']      = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000);
                    $this->appeal_process_model->insert($inputsForGenerateTemplate);
//                }
            }
            $this->load->model('appeal_application_model');
            $this->appeal_application_model->update_where(['appeal_id' => $appeal_id],['process_status' => $action]);
            $latestUpdatedProcess  = $this->appeal_process_model->last_where(['appeal_id' => $appeal_id, 'action' => $action]);
            $findOrderFilter = [
                'confirmed_hearing_date_process_id' => new ObjectId($latestConfirmHearingProcess->_id->{'$id'}),
            ];
            $inputForOrder = [
//                'action_taken_by' => new ObjectId($actionTakenBy),
                'appeal_id' => $appeal_id,
                'templateContent' => $inputsForGenerateTemplate['templateContent'],
                'confirmed_hearing_date_process_id' => new ObjectId($latestConfirmHearingProcess->_id->{'$id'}),
                'order_type' => 'hearing-order',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ];
            // check and get existing order by appeal id , order type and confirm hearing date process id
            $this->load->model('order_model');
            $existingOrder = $this->order_model->first_where($findOrderFilter);
            if(!empty((array)$existingOrder)){
//                pre('if order');
                // update order by find order type
                $this->order_model->update_where(['_id' => new ObjectId($existingOrder->_id->{'$id'})],$inputForOrder);
            }else{
//                pre('else order');
                // create new order
                $this->order_model->insert($inputForOrder);
            }

            if(!$no_download){
                $this->pdf->generate($template_summernote_content,$templateFileName,'D');
            }

            $res =  $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => true,
                    'msg' => 'Order saved successfully',
                )));
        }catch (Exception $e){
            $res =  $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => true,
                    'msg' => 'Failed to save order!!!',
                )));
        }

        if($this->input->is_ajax_request()){
            return $res;
        }
        return true;
    }

    function view_and_download_disposal_order($no_download = ''){
        try{
            //         pre($this->input->post());
            $appeal_id=$this->input->post('appeal_id');
            $docField=$this->input->post('docField');
            $notifiable=$this->input->post('notifiable');
            $template_summernote_content= $_POST['template_summernote_content'];
            // pre($template_summernote_content);
            $templateFileName = 'disposal_order_'.$notifiable.'_'.uniqid();
            // $generateTemplate = $this->load->view('includes/printables/header',"",TRUE).$generateTemplate.$this->load->view('includes/printables/footer',"",TRUE);
            if(!$no_download){
                $this->load->library('pdf');
                $this->load->helper('app');
                $this->pdf->generate($template_summernote_content,$templateFileName,'D');
            }

            // check last process and update the last one if same action type or save new for new action type
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $appeal_id,'action' => 'generate-disposal-order']);
            $action="generate-disposal-order";
            $inputsForGenerateTemplate['templateContent'] = new stdClass();
            $inputsForGenerateTemplate['templateContent']->order = $template_summernote_content;
            $actionTakenBy = $this->session->userdata('userId')->{'$id'};
            if(!empty((array)$latestProcess)){
                $inputsForGenerateTemplate['action_taken_by'] = new ObjectId($actionTakenBy);
                $inputsForGenerateTemplate['message']         = 'Order Updated.';
                $inputsForGenerateTemplate['updated_at']      = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000);
                $this->appeal_process_model->update($latestProcess->{'_id'}->{'$id'},$inputsForGenerateTemplate);
            }else{
                $inputsForGenerateTemplate['appeal_id']       = $appeal_id;
                $inputsForGenerateTemplate['action']          = $action;
                $inputsForGenerateTemplate['action_taken_by'] = new ObjectId($actionTakenBy);
                $inputsForGenerateTemplate['message']         = 'Order Generated.';
                $inputsForGenerateTemplate['created_at']      = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000);
                $this->appeal_process_model->insert($inputsForGenerateTemplate);
            }
            $findOrderFilter = [
                'appeal_id' => $appeal_id,
                'order_type' => 'disposal-order',
            ];
            $inputForOrder = [
//                'action_taken_by' => new ObjectId($actionTakenBy),
                'appeal_id' => $appeal_id,
                'templateContent' => $inputsForGenerateTemplate['templateContent'],
                'order_type' => 'disposal-order'
            ];
            // check and get existing order by appeal id , order type and confirm hearing date process id
            $this->load->model('order_model');
            $existingOrder = $this->order_model->first_where($findOrderFilter);
            if(!empty((array)$existingOrder)){
                $inputForOrder['updated_at'] = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000);
//                pre('if order');
                // update order by find order type
                $this->order_model->update_where(['_id' => new ObjectId($existingOrder->_id->{'$id'})],$inputForOrder);
            }else{
                $inputForOrder['created_at'] = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000);
//                pre('else order');
                // create new order
                $this->order_model->insert($inputForOrder);
            }
            $res =  $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => true,
                    'msg' => 'Order saved successfully',
                )));
        }catch (Exception $e){
            $res =  $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
            'success' => true,
            'msg' => 'Failed to save order!!!',
            )));
        }

        if($this->input->is_ajax_request()){
            return $res;
        }
        return true;
    }

    function view_and_download_rejection_order($no_download = ''){
        try{
            //         pre($this->input->post());
            $appeal_id=$this->input->post('appeal_id');
            $docField=$this->input->post('docField');
            $notifiable=$this->input->post('notifiable');
            $template_summernote_content= $_POST['template_summernote_content'];
            // pre($template_summernote_content);
            $templateFileName = 'rejection_order_'.$notifiable.'_'.uniqid();
            // $generateTemplate = $this->load->view('includes/printables/header',"",TRUE).$generateTemplate.$this->load->view('includes/printables/footer',"",TRUE);
            if(!$no_download){
                $this->load->library('pdf');
                $this->load->helper('app');
                $this->pdf->generate($template_summernote_content,$templateFileName,'D');
            }

            // check last process and update the last one if same action type or save new for new action type
            $this->load->model('appeal_process_model');
            $latestProcess = $this->appeal_process_model->last_where(['appeal_id' => $appeal_id,'action' => 'generate-rejection-order']);
            $action="generate-rejection-order";
            $inputsForGenerateTemplate['templateContent'] = new stdClass();
            $inputsForGenerateTemplate['templateContent']->order = $template_summernote_content;
            $actionTakenBy = $this->session->userdata('userId')->{'$id'};
            if(!empty((array)$latestProcess)){
                $inputsForGenerateTemplate['action_taken_by'] = new ObjectId($actionTakenBy);
                $inputsForGenerateTemplate['message']         = 'Order Updated.';
                $inputsForGenerateTemplate['updated_at']      = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000);
                $this->appeal_process_model->update($latestProcess->{'_id'}->{'$id'},$inputsForGenerateTemplate);
            }else{
                $inputsForGenerateTemplate['appeal_id']       = $appeal_id;
                $inputsForGenerateTemplate['action']          = $action;
                $inputsForGenerateTemplate['action_taken_by'] = new ObjectId($actionTakenBy);
                $inputsForGenerateTemplate['message']         = 'Order Generated.';
                $inputsForGenerateTemplate['created_at']      = new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000);
                $this->appeal_process_model->insert($inputsForGenerateTemplate);
            }
            $findOrderFilter = [
                'appeal_id' => $appeal_id,
                'order_type' => 'rejection-order',
            ];
            $inputForOrder = [
//                'action_taken_by' => new ObjectId($actionTakenBy),
                'appeal_id' => $appeal_id,
                'templateContent' => $inputsForGenerateTemplate['templateContent'],
                'order_type' => 'rejection-order',
                'created_at' => new UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000)
            ];
            // check and get existing order by appeal id , order type and confirm hearing date process id
            $this->load->model('order_model');
            $existingOrder = $this->order_model->first_where($findOrderFilter);
            if(!empty((array)$existingOrder)){
//                pre('if order');
                // update order by find order type
                $this->order_model->update_where(['_id' => new ObjectId($existingOrder->_id->{'$id'})],$inputForOrder);
            }else{
//                pre('else order');
                // create new order
                $this->order_model->insert($inputForOrder);
            }
            $res =  $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => true,
                    'msg' => 'Order saved successfully',
                )));
        }catch (Exception $e){
            $res =  $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode(array(
                    'success' => true,
                    'msg' => 'Failed to save order!!!',
                )));
        }

        if($this->input->is_ajax_request()){
            return $res;
        }
        return true;
    }
    /*
     * *-DB Schema-*
     * template Name
     * template Type
     * content
     * created at
     * updated at
     *
     * */
}
