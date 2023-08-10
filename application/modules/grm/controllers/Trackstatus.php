<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trackstatus extends frontend {

    public function __construct() {
        parent::__construct();
        $this->load->model('public_grievance_model');
        $this->load->model('view_status_model');
        $this->load->library('form_validation');
    }//End of __construct()
  
    public function index() {
        $this->load->model('site/settings_model');
        $data = ['grievanceId' => $this->session->userdata('grievanceId')];
        $data['form_label'] = $this->settings_model->get_settings('form_label');
        $this->load->view('includes/frontend/header');
        $this->load->view('grievances/trackstatus_view', $data);
        $this->load->view('includes/frontend/footer');
    }//End of index()

    public function get_records() {
        $mobileNumber = $this->input->post('mobileNumber');        
        $filterArray["MobileNumber"] = $mobileNumber;
        $records = $this->public_grievance_model->get_filtered_rows($filterArray); ?>
        <script type="text/javascript">
            $(document).ready(function () {                
                $("#dtbl").DataTable({
                    "order": [[0, 'asc']],
                    "lengthMenu": [[10, 20, 50, 100, 200], [10, 20, 50, 100, 200]]
                });
            });
        </script>
        <table id="dtbl" class="table table-bordered table-hover table-striped" style="width:100%">
            <thead>
                <tr class="table-header">
                    <th>#</th>
                    <th>Registration Number</th>
                    <th>Name</th>
                    <th>Grievance Category</th>
                    <th>Date of Receipt</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="small-text">
                <?php if($records) {
                    $sl_no = 0;
                    foreach ($records as $row) {
                        $objId = $row->{"_id"}->{'$id'};
                        $this->getnupdate_status($objId);
                        $sl_no = $sl_no+1;
                        $registration_no = $row->registration_no;
                        $name = $row->Name;
                        $grievanceCategory = property_exists($row, 'grievanceCategory') ? $row->grievanceCategory : 'NA';
                        $grievanceCategory = get_grievance_category_text_by_slug($grievanceCategory);
                        $DateOfReceipt = date('d-m-Y g:i a', intval(strval($row->DateOfReceipt)) / 1000);
                        $action = '<button class="btn btn-sm btn-outline-primary" onclick="showGrievanceDetails(\'' . urlencode($row->registration_no) . '\',\'' . $mobileNumber . '\')"> <i class="fa fa-eye"></i> View Status</button>';
                        ?>
                        <tr>
                            <td><?=$sl_no?></td>
                            <td><?=$registration_no?></td>
                            <td><?=$name?></td>
                            <td><?=$grievanceCategory?></td>
                            <td><?=$DateOfReceipt?></td>
                            <td><?=$action?></td>                                    
                        </tr><?php
                    }//End of foreach()
                }//End of if ?>
            </tbody>
        </table>
        <?php
    }//End of get_records()
    
    public function get_details() {
        $regNo = urldecode($this->input->post('registration_number'));
        $email_or_mobile = $this->input->post('email_or_mobile');
        $filter = array(
            'registration_no' => $regNo,
            'MobileNumber' => $email_or_mobile
        );      
        $record = $this->public_grievance_model->get_row($filter);
        if($record) {
            $data_location = $record->data_location??"";
            if($data_location === 'RTPS_ONLY') {
                $ReceivingOrg = 'HELP DESK';
                $OfficerName = 'RTPS SUPPORT TEAM';
                $OfficerDesignation = 'TECHNICAL SUPPORT';
                $OfficerAddress = 'Assam';
                $OfficerEmail = 'adm.rtps@gmail.com';
            } else {
                $ReceivingOrg = $record->ReceivingOrg??'';
                $OfficerName = $record->OfficerName??'';
                $OfficerDesignation = $record->OfficerDesignation??'';
                $OfficerAddress = $record->OfficerAddress??'';
                $OfficerEmail = $record->OfficerEmail??'';
            }//End of if else
            $grievanceDoc = $record->Document??'';
            $replyDoc = $record->reply_document??''; ?>
            <table class="table table-bordered table-success table-striped">
                <thead>
                    <tr>
                        <th colspan="4" style="text-align: center; text-transform: uppercase">Basic Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width:200px">Name : </td>
                        <td style="font-weight: bold"><?=$record->Name??''?></td>
                        <td>Registration Number : </td>
                        <td style="font-weight: bold"><?=$regNo?></td>
                    </tr>
                    <tr>
                        <td>Grievance Details : </td>
                        <td style="font-weight: bold" colspan="3"><?=$record->SubjectContent??''?></td>
                    </tr>
                    <tr>
                        <td>Grievance Document : </td>
                        <td colspan="3">
                            <?php if(strlen($grievanceDoc) > 10) {
                                echo '<a href="'.base_url('storage/uploads/grievance/attachments/'.$grievanceDoc).'" class="btn btn-primary" target="_blank">View File</a>';
                            } else {
                                echo "NA";
                            }//End of if else ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Date Of Receipt : </td>
                        <td style="font-weight: bold"><?=date("d/m/Y", strtotime($this->mongo_db->getDateTime($record->DateOfReceipt)))?></td>
                        <td>Receiving Organization : </td>
                        <td style="font-weight: bold"><?=$ReceivingOrg?></td>
                    </tr>
                </tbody>
            </table>
        
            <table class="table table-bordered table-warning table-striped">
                <thead>
                    <tr>
                        <th colspan="4" style="text-align: center; text-transform: uppercase">Processing Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Current status : </td>
                        <td style="font-weight: bold; font-size: 18px" class="text-danger"><?=$record->current_status??'UNDER PROCESSING'?></td>
                        <td>Date Of Action : </td>
                        <td style="font-weight: bold"><?=date("d/m/Y", strtotime($record->date_of_action))?></td>
                    </tr>
                    <tr>
                        <td>Officer Name : </td>
                        <td style="font-weight: bold"><?=$OfficerName?></td>
                        <td>Officer Designation : </td>
                        <td style="font-weight: bold"><?=$OfficerDesignation?></td>
                    </tr>
                    <tr>
                        <td>Officer Address : </td>
                        <td style="font-weight: bold"><?=$OfficerAddress?></td>
                        <td>Officer Email : </td>
                        <td style="font-weight: bold"><?=$OfficerEmail?></td>
                    </tr>
                    <tr>
                        <td>Reply Document : </td>
                        <td colspan="3">
                            <?php if(strlen($replyDoc) > 100) {
                                $filePath = 'storage/uploads/ReplyDocument.pdf';
                                file_put_contents(FCPATH.$filePath, base64_decode($record->ReplyDocument));
                                echo '<a href="'.base_url($filePath).'" class="btn btn-success" target="_blank">View File</a>';
                            } else {
                                echo "NA";
                            }//End of if else ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>Remarks : </td>
                        <td style="font-weight: bold" colspan="3"><?=$record->reply_remark??''?></td>
                    </tr>
                    <?php if(isset($record->reason)) { ?>
                        <tr>
                            <td>Reason : </td>
                            <td style="font-weight: bold" colspan="3"><?=$record->reason?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else {
            echo "<h3 class='text-center'>No records found</h3>";
        }//End of if else
    }//End of get_details()    
    
    public function getnupdate_status($object_id = null) {
        $record = $this->public_grievance_model->get_by_doc_id($object_id);
        if($record) {
            $registration_no=$record->registration_no;
            $MobileNumber=$record->MobileNumber;
            $filter = array(
                "RegistrationNumber" => $registration_no,
                "EmailOrMobile" => $MobileNumber
            );     
            $this->load->library('sadbhawana_cpgrams', ['type' => 'view-status']);        
            $apiResponse = $this->sadbhawana_cpgrams->post($filter);
            $apiResponseObj = json_decode($apiResponse);
            $current_status = isset($apiResponseObj->CurrentStatus)?$apiResponseObj->CurrentStatus:null;
            $replyDocument = isset($apiResponseObj->ReplyDocument)?$apiResponseObj->ReplyDocument:null;
            $rem = isset($apiResponseObj->Remark)?$apiResponseObj->Remark:null;
            $remark = ($rem=="Auto Forwarded - RI Web API")?"UNDER PROCESS":$rem;
            $reason = isset($apiResponseObj->Reason)?$apiResponseObj->Reason:null;
            $date_of_action = isset($apiResponseObj->DateOfAction)?date("d-m-Y", strtotime($apiResponseObj->DateOfAction)):date('d-m-Y');
                
            $data = array(
                "OfficerName" => $apiResponseObj->OfficerName??'',
                "OfficerDesignation" => $apiResponseObj->OfficerDesignation??'',
                "OfficerAddress" => $apiResponseObj->OfficerAddress??'',
                "OfficerEmail" => $apiResponseObj->OfficerEmail??'',
                "ReceivingOrg" => $apiResponseObj->ReceivingOrg??'',
                "current_status"=>$current_status,
                "reply_document" => $replyDocument,
                "reply_remark" => $remark,
                "reason" => $reason,
                "date_of_action" => $date_of_action
                );
            $this->public_grievance_model->update($object_id, $data);
        } else {
            echo "No records found!";
        }//End of if else
    }//End of getnupdate_status()
}//End of Trackstatus