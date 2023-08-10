<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
if (!function_exists('checkAndGenerateUniqueId')) {
    function checkAndGenerateUniqueId($field,$collection){

        $thisInstance = &get_instance();
        $uniqueId = strtoupper(substr(uniqid(), '6'));

        $result = $thisInstance->mongo_db->where(array($field => $uniqueId))->get($collection);
        if(count((array)$result)){
            // echo $uniqueId;die();
            checkAndGenerateUniqueId($field,$collection);
        }
        return $uniqueId;
    }
}

if(!function_exists('getAppealApplications')){
    function getAppealApplications($appeal, $ref_id)
    {
        $CI =& get_instance();
        $CI->load->model('applications_model');
        $CI->load->model('appeal_application_model');
        $application = $CI->applications_model->first_where(['initiated_data.appl_ref_no' => $appeal->appl_ref_no ?? '']);
        // todo : need optimization
        if (empty($application) && !isset($application) ) {
            $appealApplication = $CI->appeal_application_model->get_appeal_details_without_ref($ref_id);
        } else {
            $appealApplication = $CI->appeal_application_model->get_appeal_details_for_everyone($ref_id);
        }
        return $appealApplication;
    }
}

if(!function_exists('getProcessUserArrayOnForward')){
    function getProcessUserArrayOnForward($previousProcessUsers, $forwardTo): array
    {
        $CI =& get_instance();
        $newProcessUser = $previousProcessUsers;
        $userAlreadyExists = false;
        foreach ($previousProcessUsers as $key => $process_user) {
            // check if forward to user already exists
            if(gettype($forwardTo) === 'array'){
                if (in_array(strval($process_user->userId),$forwardTo)) {
                    $userAlreadyExists = true;
                }
            }else{
                if (strval($process_user->userId) === $forwardTo) {
                    $userAlreadyExists = true;
                }
            }
        }
        if ($userAlreadyExists) {
            foreach ($previousProcessUsers as $key => $process_user) {
                if ((gettype($forwardTo) === 'array' && in_array(strval($process_user->userId),$forwardTo)) || (gettype($forwardTo) === 'string' && strval($process_user->userId) === $forwardTo)) {
                    $newProcessUser[$key]->active = true;
                } else {
                    $newProcessUser[$key]->active = false;
                }
            }
        } else {
            $newProcessUser = [];
            foreach ($previousProcessUsers as $key => $process_user) {
                $newProcessUser[$key] = $process_user;
                $newProcessUser[$key]->active = false;
            }
            if(gettype($forwardTo) === 'array'){
                foreach ($forwardTo as $fwrdTo){
                    $newProcessUserObj = new stdClass();
                    $newProcessUserObj->userId = new ObjectId($fwrdTo);
                    $CI->load->model('users_model');
                    $newProcessUserInfo = $CI->users_model->get_user_info(['_id' => $newProcessUserObj->userId]);
                    $newProcessUserObj->role_slug = $newProcessUserInfo->user_role->slug;

                    $CI->load->config('first_appeal');
                    $newProcessUserObj->action = $CI->config->item('action')[$newProcessUserObj->role_slug];
                    $newProcessUserObj->active = true;
                    array_push($newProcessUser, $newProcessUserObj);
                }
            }else{
                $newProcessUserObj = new stdClass();
                $newProcessUserObj->userId = new ObjectId($forwardTo);
                $CI->load->model('users_model');
                $newProcessUserInfo = $CI->users_model->get_user_info(['_id' => $newProcessUserObj->userId]);
                $newProcessUserObj->role_slug = $newProcessUserInfo->user_role->slug;

                $CI->load->config('first_appeal');
                $newProcessUserObj->action = $CI->config->item('action')[$newProcessUserObj->role_slug];
                $newProcessUserObj->active = true;
                array_push($newProcessUser, $newProcessUserObj);
            }

        }
        return $newProcessUser;
    }
}

if(!function_exists('getAppellateDetailsFromAppeal')){
    function getAppellateDetailsFromAppeal($appealApplication): stdClass
    {
        $appellateDetails = new stdClass();
        foreach ($appealApplication as $appeal){
            if($appeal->process_users->role_slug === 'AA'){
                $appellateDetails = $appeal->process_users_data;
            }
        }
        return $appellateDetails;
    }
}
if(!function_exists('getDPSDetailsFromAppeal')){
    function getDPSDetailsFromAppeal($appealApplication): stdClass
    {
        $appellateDetails = new stdClass();
        foreach ($appealApplication as $appeal){
            if($appeal->process_users->role_slug === 'DPS'){
                $appellateDetails = $appeal->process_users_data;
            }
        }
        return $appellateDetails;
    }
}
if(!function_exists('prepareProcessUserList')){

    /**
     * @param array $roleCondition
     * @param $relatedOfficials
     * @return array
     */
    function prepareProcessUserList(array $roleCondition, $relatedOfficials,$isSecond = false): array
    {
        $CI =& get_instance();
        $CI->load->model('roles_model');
        $roleWiseActionList = $CI->roles_model->get_role_wise_permission($roleCondition);
        $process_users = [];

        $da_permissions = [];
        $rr_permissions = [];
        foreach ($roleWiseActionList as $actionList) {
            if ($actionList->_id->role_slug === 'DA') {
                $da_permissions = $actionList->permissions;
            }
            if ($actionList->_id->role_slug === 'RR' && $isSecond) {
                $rr_permissions = $actionList->permissions;
            }
            if ($actionList->_id->role_slug === 'DPS') {
                $process_users[] = [
                    'userId' => $relatedOfficials->dps_id,
                    'action' => $actionList->permissions,
                    'role_slug' => 'DPS',
                    'active' => false
                ];
            }
            if ($actionList->_id->role_slug === 'AA') {
                $process_users[] = [
                    'userId' => $relatedOfficials->appellate_id,
                    'action' => $actionList->permissions,
                    'role_slug' => 'AA',
                    'active' => false
                ];
            }
            if ($actionList->_id->role_slug === 'RA') {
                $process_users[] = [
                    'userId' => $relatedOfficials->reviewing_id,
                    'action' => $actionList->permissions,
                    'role_slug' => 'RA',
                    'active' => false
                ];
            }

        }

        if($isSecond){
            foreach ($relatedOfficials->da_id_tribunal_array as $daId) {
                $process_users[] = [
                    'userId' => $daId,
                    'action' => $da_permissions,
                    'role_slug' => 'DA',
                    'active' => true
                ];
            }
            foreach ($relatedOfficials->registrar_id_array as $rrId) {
                $process_users[] = [
                    'userId' => $rrId,
                    'action' => $rr_permissions,
                    'role_slug' => 'RR',
                    'active' => false
                ];
            }
        }else{
            foreach ($relatedOfficials->da_id_array as $daId) {
                $process_users[] = [
                    'userId' => $daId,
                    'action' => $da_permissions,
                    'role_slug' => 'DA',
                    'active' => true
                ];
            }
        }
        return $process_users;
    }
}

if(!function_exists('checkTimeLine')){
    function checkTimeLine($appeal_ref_no,$appealApplicationPrevious){
        $CI =& get_instance();
        $CI->load->model('appeal_process_model');
        $lastProcess =  $CI->appeal_process_model->last_where(array('appeal_id' => $appeal_ref_no,'action' => array('$in' => array('resolved','rejected'))));
        $data = array(
            "status" => true,
            "reason" => false,
            "error_msg" => ""
        );
        $today = date_create();
        $CI->load->config('second_appeal');
        if(count((array) $lastProcess)){
            $processDate = date('d-m-Y', strtotime($CI->mongo_db->getDateTime($lastProcess->created_at)));
            $processDate=date_create($processDate);
            $diff=date_diff($processDate,$today);
        }else{
            $dateOfAppeal = date('d-m-Y', strtotime($CI->mongo_db->getDateTime($appealApplicationPrevious->created_at)));
            $dateOfAppeal = date('d-m-Y', strtotime($dateOfAppeal. ' + '.$CI->config->item('first_appeal_processing_time').' days')) ;
            $dateOfAppeal = date_create($dateOfAppeal);
            $diff=date_diff($dateOfAppeal,$today);
        }
        $days = $diff->format("%a");

        if($days > ($CI->config->item('sap_extension_time') + $CI->config->item('sap_relaxation_time'))){
            $data['status']=false;
            $data['reason']=false;
            $data["error_msg"] = 'Your application timeline for appeal submission is expired.';
        }

        if($days > $CI->config->item('sap_relaxation_time')){
            $data['status']=true;
            $data['reason']=true;
            $data["error_msg"] = '';
        }
        return $data;
    }
}
