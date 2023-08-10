<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * This function is used to print the content of any data
 */
if (!function_exists('pre')) {
    function pre($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die;
    }
}
if (!function_exists('get_appeal_management_system_roles')) {
    function get_appeal_management_system_roles($role = NULL)
    {
        $role_array = array(
            "DPS" => "5f1abe880873000087002831",
            "Appellate_Authority" => "5f1abe940873000087002834",
            "Reviewing_Authority" => "5f1abe9e0873000087002837",
        );
        if ($role != NULL && !empty($role)) {
            if (array_key_exists($role, $role_array)) {
                return $role_array[$role];
            } else {
                return FALSE;
            }
        } else {
            return $role_array;
        }

    }
}
if (!function_exists('hasPermission')) {
    function hasPermission($class = NULL, $method = NULL)
    {
        $CI = get_instance();
        $role = $CI->session->userdata("role");
        $permissions = $role->permissions_array;
        if (!empty($permissions) && isset($permissions)) {

            if (empty($class)) {
                $class = $CI->router->fetch_class();
            }
            if (empty($method)) {
                $method = $CI->router->fetch_method();
            }
            foreach ($permissions as $permission) {
                //check user has access permission for class and method
                if (strtolower($permission->class) == strtolower($class) && strtolower($permission->method) == strtolower($method)) {
                    //user has permission to access this page
                    return true;
                } else {
                    redirect('dashboard/access_denied');
                }
            }
            //user has not permission to access this page
            //redirect('dashboard/access_denied');
        }
        //redirect('dashboard/access_denied');
    }
}

/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 */
if (!function_exists('getHashedPassword')) {
    function getHashedPassword($plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }
}
/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 * @param {string} $hashedPassword : This is hashed password
 */
if (!function_exists('verifyHashedPassword')) {
    function verifyHashedPassword($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword) ? true : false;
    }
}
/**
 * This method used to get current browser agent
 */
if (!function_exists('getBrowserAgent')) {
    function getBrowserAgent()
    {
        $CI = get_instance();
        $CI->load->library('user_agent');
        $agent = '';
        if ($CI->agent->is_browser()) {
            $agent = $CI->agent->browser() . ' ' . $CI->agent->version();
        } elseif ($CI->agent->is_robot()) {
            $agent = $CI->agent->robot();
        } elseif ($CI->agent->is_mobile()) {
            $agent = $CI->agent->mobile();
        } else {
            $agent = 'Unidentified User Agent';
        }
        return $agent;
    }
}

if (!function_exists('clean_data')) {
    function clean_data($data)
    {
        $found = strstr($data, "~");
        if ($found) {
            return explode("~", $data)[1];
        }
        return $data;
    }
}
if (!function_exists('get_service_status')) {
    function get_service_status($status)
    {
        $status = intval($status);
        $string = "";
        switch ($status) {
            case 1:
                $string = "";
                break;
            case 2:
                $string = "Deliver";
                break;
            case 3:
                $string = "Reject";
                break;
        }
        return $string;
    }
}
if (!function_exists('format_mongo_date')) {
    function format_mongo_date($dateString, $format = 'd-m-Y g:i a')
    {
        $ci = get_instance();
        return date($format, strtotime($ci->mongo_db->getDateTime($dateString)));
    }
}
if (!function_exists('convertToMongoObId')) {
    function convertToMongoObId($id)
    {
        return new MongoDB\BSON\ObjectId($id);
    }
}

if (!function_exists('resetPasswordEmail')) {
    function resetPasswordEmail($data)
    {
        // store data to reset_password collection
        $ci = get_instance();
        $ci->load->model('reset_password_model');
        if ($ci->reset_password_model->insert($data)) {
            // send mail

            $body = '<p>You have requested for a password reset link. <a href="' . $data['reset_link'] . '">Click Here</a> to reset your password.</p>';
            $ci->load->library('remail');
            $ci->remail->sendemail($data['email'], $data['message'], $body);
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('date_cal')) {
    function date_cal($startDate = NULL, $i = 8)
    {
        if($startDate === NULL){
            $startDate = date('d-m-Y');
        }
        while ($i > 0) {
            $startDate = get_next_date($startDate);
            $i--;
        }
        return $startDate;

    }
}
if (!function_exists('get_next_date')) {
    function get_next_date($startDate)
    {
        $CI = &get_instance();
        $CI->load->model('holiday_model');
        $holidaysList = $CI->holiday_model->get_where_in('year',[date('Y'),date('Y')+1],['dates']);
        $holidays = [];
        foreach ($holidaysList as $dates){
            $holidays = array_merge($holidays,$dates->dates);
        }
//        pre($holidays);
        $nextBusinessDay = date('d-m-Y', strtotime($startDate . '+1 day'));
        if (in_array($nextBusinessDay, $holidays)) {
            return get_next_date($nextBusinessDay);
        } else {
            return $nextBusinessDay;
        }
    }
}

if(!function_exists('getProcessStatus')){
    function getProcessStatus($status): string
    {
        switch ($status) {
            case 'reply':
                $process_status = '<span class="badge badge-info">Reply</span>';
                break;
            case 'forward-to-aa':
            case 'second-appeal-forward-to-aa':
                $process_status = '<span class="badge badge-secondary">Forwarded to AA</span>';
                break;
            case 'second-appeal-forward-to-registrar':
                $process_status = '<span class="badge badge-secondary">Forwarded To Registrar</span>';
                break;
            case 'second-appeal-forward-to-moc':
                $process_status = '<span class="badge badge-secondary">Forwarded To Member of Commission</span>';
                break;
            case 'second-appeal-final-verdict':
                $process_status = '<span class="badge badge-success">Final Verdict</span>';
                break;
            case 'second-appeal-forward-to-chairman':
                $process_status = '<span class="badge badge-secondary">Forwarded To Chairman</span>';
                break;
            case 'revert-back-to-da':
            case 'second-appeal-revert-back-to-da':
                $process_status = '<span class="badge badge-info">Reverted to DA</span>';
                break;
            case 'second-appeal-revert-back-to-rr':
                $process_status = '<span class="badge badge-info">Reverted to Registrar</span>';
                break;
            case 'comment-by-bench-member':
                $process_status = '<span class="badge badge-info">Comment by Bench Member</span>';
                break;
            case 'generate-disposal-order':
                $process_status = '<span class="badge badge-success">Disposal Order Generated</span>';
                break;
            case 'upload-disposal-order':
            case 'second-appeal-upload-hearing-order':
                $process_status = '<span class="badge badge-success">Disposal Order Uploaded</span>';
                break;
            case 'second-appeal-approve-disposal-order':
                $process_status = '<span class="badge badge-success">Disposal Order Approved</span>';
                break;
            case 'resolved':
            case 'second-appeal-issue-disposal-order':
                $process_status = '<span class="badge badge-success">Resolved</span>';
                break;
            case 'generate-rejection-order':
                $process_status = '<span class="badge badge-danger">Rejection Order Generated</span>';
                break;
            case 'upload-rejection-order':
                $process_status = '<span class="badge badge-danger">Rejection Order Uploaded</span>';
                break;
            case 'second-appeal-approve-rejection-order':
                $process_status = '<span class="badge badge-success">Rejection Order Approved</span>';
                break;
            case 'rejected':
            case 'second-appeal-issue-rejection-order':
                $process_status = '<span class="badge badge-danger">Rejected</span>';
                break;
            case 'penalize':
                $process_status = '<span class="badge badge-primary">Penalized</span>';
                break;
            case 'generate-penalty-order':
                $process_status = '<span class="badge badge-warning">Penalty Order Generated</span>';
                break;
            case 'upload-penalty-order':
                $process_status = '<span class="badge badge-warning">Penalty Order Uploaded</span>';
                break;
            case 'approve-penalty-order':
                $process_status = '<span class="badge badge-info">Penalty Order Approved</span>';
                break;
            case 'reassign':
                $process_status = '<span class="badge badge-info">Reassigned</span>';
                break;
            case 'remark':
                $process_status = '<span class="badge badge-warning">Remark</span>';
                break;
            case 'in-progress':
                $process_status = '<span class="badge badge-dark">In Progress</span>';
                break;
            case 'provide-hearing-date':
                $process_status = '<span class="badge badge-info">Hearing Date Provided</span>';
                break;
            case 'second-appeal-change-hearing-date':
                $process_status = '<span class="badge badge-info">Hearing Date Changed</span>';
                break;
            case 'second-appeal-confirm-hearing-date':
                $process_status = '<span class="badge badge-info">Hearing Date Approved</span>';
                break;
            case 'generate-hearing-order':
            case 'second-appeal-generate-hearing-order':
                $process_status = '<span class="badge badge-warning">Hearing Order Generated</span>';
                break;
            case 'modify-hearing-order':
            case 'second-appeal-modify-hearing-order':
                $process_status = '<span class="badge badge-warning">Hearing Order Modified</span>';
                break;
            case 'upload-hearing-order':
                $process_status = '<span class="badge badge-warning">Hearing Order Uploaded</span>';
                break;
            case 'approve-hearing-order':
            case 'second-appeal-approve-hearing-order':
                $process_status = '<span class="badge badge-info">Hearing Order Approved</span>';
                break;
            case 'second-appeal-issue-hearing-order':
                $process_status = '<span class="badge badge-success">Hearing Order Issued</span>';
                break;
            case 'second-appeal-create-bench':
                $process_status = '<span class="badge badge-warning">Bench Formed</span>';
                break;
            case 'second-appeal-seek-info':
            case 'seek-info':
                $process_status = '<span class="badge badge-primary">Seeking Info</span>';
                break;
            case 'issue-order':
                $process_status = '<span class="badge badge-info">Order Issued</span>';
                $generatedMsgPreText = 'Order Issued to ';
                break;
            case 'dps-reply':
                $process_status = '<span class="badge badge-info">DPS replied</span>';
                break;
            case 'appellate-reply':
                $process_status = '<span class="badge badge-info">Appellate Authority replied</span>';
                break; 
           
            default:
                $process_status = '<span class="badge badge-secondary">initiated</span>';
                break;
        }
        return $process_status;
    }
}

if(!function_exists('getHolidaysArray')){
    function getHolidaysArray(): array
    {
        $holidaysArray = [];
        $CI = &get_instance();
        $currentYear = date('Y');
        $CI->load->model('holiday_model');
        $holidayList = $CI->mongo_db->where_in('year',[$currentYear-1,$currentYear,$currentYear+1])
            ->select(['dates'])
            ->get('holiday_list');
        foreach ($holidayList as $holiday){
            array_push($holidaysArray,$holiday->dates);
        }
        return array_merge(...$holidaysArray);
    }
}
if(!function_exists('processHierarchy')){
    function processHierarchy($status,$appeal_id,$slug): array
    {
        $disableViewEditHearing = false;
        $isFinalHearing=false;
        $isHearingOrderIssued=false;
        $isRejectionGenerated = false;
        $isDisposalGenerated = false;
        $isApprovedDisposalOrRjectionOrder=false;
        $isDisposalApproved = false;
        $isRejectionApproved = false;
        $isHearingOrderApproved = false;
        $CI = &get_instance();
        $CI->load->model('appeal_process_model');
        $processHistory=$CI->appeal_process_model->get_where(array('appeal_id' =>$appeal_id));
        if(!empty($processHistory)){
           foreach($processHistory as $key=>$value){
               if($value->action === "second-appeal-issue-hearing-order"){
                $isHearingOrderIssued=true;
               }

               if($value->action === "second-appeal-approve-disposal-order"){
                $isDisposalApproved=true;
               } 
               if($value->action === "second-appeal-approve-rejection-order"){
                $isRejectionApproved=true;
               }  
               if($value->action === "second-appeal-approve-hearing-order"){
                $isHearingOrderApproved=true;
               }

                if(property_exists($value,'is_final_hearing') && $value->is_final_hearing){
                   $isFinalHearing = true;
               }
                if(isset($value->templateContent) && property_exists($value->templateContent,'appellant') && property_exists($value->templateContent,'dps')){
                    $disableViewEditHearing = true;
                }
             
           }
        }
        //second-appeal-issue-hearing-order
        if(empty($status)){
            $status="initiated";
        }
        $process = array(
            "initiated"=>array(
                "second-appeal-forward-to-registrar"=>"Forward to Registrar",
                "second-appeal-seek-info-from-appellant"=>"Seeking Information"
            ),
           
            "second-appeal-forward-to-registrar"=>array(
                "second-appeal-revert-back-to-da"=>"Revert Back To DA",
                "second-appeal-forward-to-chairman"=>"Forward to Chairman",
                "second-appeal-change-hearing-date"=>"Change Hearing Date"
            ),
            "second-appeal-revert-back-to-da"=>array(
                "second-appeal-forward-to-registrar"=>"Forward to Registrar",
                "second-appeal-seek-info-from-appellant"=>"Seeking Information"
            ),
           
            "second-appeal-create-bench"=>
            array(
                "second-appeal-create-bench"=>"Create Bench/ Update Bench",
                "second-appeal-revert-back-to-rr"=>"Revert Back to Registrar",
                "second-appeal-change-hearing-date"=>"Change Hearing Date",
                "second-appeal-confirm-hearing-date"=>"Approve Hearing Date",
            ),
             "second-appeal-confirm-hearing-date"=>
            array(
                "second-appeal-upload-hearing-order"=>"View/ Edit Hearing Order",
                
            ),

            "second-appeal-approve-hearing-order"=>array(
                "second-appeal-revert-back-to-rr"=>"Revert Back to Registrar",
//                "penalize"=>"Penalize",
            ),
           
            "second-appeal-issue-hearing-order"=>array(
                "second-appeal-forward-to-chairman"=>"Forward to Chairman"
            ),
          
    
            );

        if($slug == 'RR'){
            $process["second-appeal-change-hearing-date"] =
                array(
                    "second-appeal-forward-to-chairman"=>"Forward to Chairman",
                    "second-appeal-change-hearing-date"=>"Change Hearing Date",
                );
        }else{
            $process["second-appeal-change-hearing-date"] =
            array(
                "second-appeal-revert-back-to-rr"=>"Revert Back to Registrar",
                "second-appeal-change-hearing-date"=>"Change Hearing Date",
                "second-appeal-confirm-hearing-date"=>"Approve Hearing Date",
            );
        }

        if( $disableViewEditHearing){
            $process["generate-hearing-order"]=array(
                "second-appeal-upload-hearing-order"=>"View/ Edit Hearing Order",
                "second-appeal-approve-hearing-order"=>"Approve Hearing Order",
            );
        }else{
            $process["generate-hearing-order"]=array(
                "second-appeal-upload-hearing-order"=>"View/ Edit Hearing Order",
            );
        }
           if($isHearingOrderApproved){
            $process["second-appeal-revert-back-to-rr"]=array(
                "second-appeal-issue-hearing-order"=>"Issue Hearing Order",
            );
            $process["appellant-dsc-sign-generated"]=array(
                "second-appeal-issue-hearing-order"=>"Issue Hearing Order",
            );
           
            $process["dps-dsc-sign-generated"]=array(
                "second-appeal-issue-hearing-order"=>"Issue Hearing Order",
            );
           }
        if(!$isHearingOrderApproved)
            $process["second-appeal-revert-back-to-rr"]=array(
                "second-appeal-forward-to-chairman"=>"Forward to Chairman",
                "second-appeal-upload-hearing-order"=>"View/ Edit Hearing Order",
            );

           if($isDisposalApproved ){
            $process["second-appeal-revert-back-to-rr"]=array(
                "second-appeal-issue-disposal-order"=>"Issue Disposal Order",
            );
           }

           if($isDisposalApproved ){
            $process["disposal-order-dsc-sign-generated"]=array(
                "second-appeal-issue-disposal-order"=>"Issue Disposal Order",
            );
           }

           if($isRejectionApproved){
            $process["second-appeal-revert-back-to-rr"]=array(
                "second-appeal-issue-rejection-order"=>"Issue Rejection Order",
            );
           }
            if($isRejectionApproved){
            $process["rejection-order-dsc-sign-generated"]=array(
                "second-appeal-issue-rejection-order"=>"Issue Rejection Order",
            );
           }

            if($slug !== "DPS"){
                $process["second-appeal-final-verdict"]=array(
                    "second-appeal-upload-disposal-order"=> $slug === "DA" ? "Upload disposal order":"View/ Edit Disposal Order",
                    "second-appeal-upload-rejection-order"=> $slug === "DA" ? "Upload rejection order":"View/ Edit Rejection Order"
                );
               
                // if(!$isRejectionGenerated){
                //     $process["second-appeal-final-verdict"]=array(
                //         "second-appeal-upload-disposal-order"=> $slug === "DA" ? "Upload disposal order":"View/ Edit Disposal Order",
                     
                //     );
                // }
                // if(!$isDisposalGenerated){
                //     $process["second-appeal-final-verdict"]=array(
                //         "second-appeal-upload-rejection-order"=> $slug === "DA" ? "Upload rejection order":"View/ Edit Rejection Order"
                //     );
                // }

            }
        
            $process["second-appeal-upload-disposal-order"]=array(
                "second-appeal-approve-disposal-order"=> "Approve Disposal Order"
            );
            $process["second-appeal-upload-rejection-order"]=array(
                "second-appeal-approve-rejection-order"=> "Approve Rejection Order"
            );
            $process["generate-disposal-order"]=array(
                "second-appeal-approve-disposal-order"=> "Approve Disposal Order",
                "second-appeal-upload-disposal-order"=> $slug === "DA" ? "Upload disposal order":"View/ Edit Disposal Order"
            );
            $process["generate-rejection-order"]=array(
                "second-appeal-approve-rejection-order"=> "Approve Rejection Order",
                "second-appeal-upload-rejection-order"=> $slug === "DA" ? "Upload rejection order":"View/ Edit Rejection Order"
            );
            $process["second-appeal-approve-disposal-order"]=array(
                "second-appeal-revert-back-to-rr"=> "Revert Back to Registrar"
            );
            $process["second-appeal-approve-rejection-order"]=array(
                "second-appeal-revert-back-to-rr"=> "Revert Back to Registrar"
            );
            if($slug === "DPS"){
                $process["second-appeal-seek-info"]=array(
                    "dps-reply"=>"Reply"
                );
            }else{
                $process["second-appeal-seek-info"]=array(
                    "second-appeal-forward-to-registrar"=>"Forward to Registrar",
                    "second-appeal-seek-info-from-appellant"=>"Seeking Information"
                );
            }
            if(in_array($slug,["RA","MOC"])){
                if($isFinalHearing){
                    $process["comment-by-bench-member"]=array(
                        "second-appeal-final-verdict"=>"Final Verdict",
                    );
                }else{
                    $process["comment-by-bench-member"]=array(
                        "second-appeal-final-verdict"=>"Final Verdict",
                        "second-appeal-change-hearing-date"=>"Change Hearing Date",
                    );
                }
                
            }
            if($slug === "RR"){
                $process["comment-by-bench-member"]=array(
                    "second-appeal-forward-to-chairman"=>"Forward to Chairman"
                );
            }
            if($isHearingOrderIssued){
                if($isFinalHearing){
                    $process['second-appeal-forward-to-chairman']=array(
                        "second-appeal-final-verdict"=>"Final Verdict",
                    );
                }else{
                    $process['second-appeal-forward-to-chairman']=array(
                        "second-appeal-final-verdict"=>"Final Verdict",
                        "second-appeal-change-hearing-date"=>"Change Hearing Date",
                    );
                }
              
            }else{
                // here
                $process['second-appeal-forward-to-chairman']=array(
                    "second-appeal-revert-back-to-rr"=>"Revert Back to Registrar",
                    "second-appeal-create-bench"=>"Create Bench/ Update Bench",
                    "second-appeal-upload-rejection-order"=>"View/ Edit Rejection Order",
                );
            }

         
            return !empty($process[$status])?$process[$status]:array();
        
    }
}

if(!function_exists('create_mongo_date')){
    function create_mongo_date($inputDate){
        return new MongoDB\BSON\UTCDateTime(strtotime($inputDate) * 1000);
    }
}

if(!function_exists('get_grievance_category_text_by_slug')){
    function get_grievance_category_text_by_slug($slug): string
    {
        switch ($slug){
            case 'service-related':
                $text = 'Service Related';
                break;
            case 'pfc-related':
                $text = 'PFC Related';
                break;
            case 'portal-related':
                $text = 'Portal Related';
                break;
            default:
                $text = 'Others';
                break;
        }
        return $text;
    }
}

if(!function_exists('check_application_count_for_citizen')){
    function check_application_count_for_citizen(){

       

        
        $CI = &get_instance();
        $user =  $CI->session->userdata();
      
        if($user['isLoggedIn'] && (!isset($user['role']) && empty($user['role'])) ){
           $user_mobile= $user['mobile'];
           //please don't change this url and push to github 
           $url = "localhost/mis/api/misapi/get_citizen_application_count";
           $postdata = array(
               'secret' => "rtpsapi#!@",
               'mobile' => $user_mobile,
           );
           $json_obj = json_encode($postdata);
           $curl = curl_init($url);
           curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
           curl_setopt($curl, CURLOPT_POST, true);
           curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
           curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
           curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
           curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
           curl_setopt($curl, CURLOPT_FAILONERROR, true);
           $response = curl_exec($curl);
           if (curl_errno($curl)) {
               $error_msg = curl_error($curl);
               return true;
           }
           curl_close($curl);
           if ($response) {
            $response = json_decode($response, true);
            if(!$response['status']){
                //exceed ! restrict the user to apply new application
                $CI->session->set_flashdata('errmessage', $response['message']);
                redirect(base_url('iservices/transactions'));
            }else{
                //allowed to  apply new application
                return true;
            }
            
            }else{
                return;
            }

        }
        

    }

}

if (!function_exists('get_financial_year')) {
    function get_financial_year()
    {
        $cyr = date('Y');
        $from_date = '01/04/' . $cyr;

        $yr = date('Y');
        $yr1 = $yr + 1;
        $mt = date('m');

        if ($mt > 3) {
            $rec_fin_year = $yr . '-' . $yr1;
        } else if ($mt <= 3) {
            $yr2 = $yr1 - 1;
            $rec_fin_year = $yr2 . '-' . $yr1;
        }
        return array("financial_year" => $rec_fin_year, "from_date" => $from_date);
    }
}