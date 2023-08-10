<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


if (!function_exists('getApplicantNotifiable')) {
    function getApplicantNotifiable($contactNumber, $emailId, $contactInAdditionContactNumber, $additionalContactNumber, $contactInAdditionEmail, $additionalEmailId)
    {
        $applicantNotifiable = array();
        $applicantNotifiable['mobile'] = ($contactNumber != '') ? array($contactNumber) : array();
        $applicantNotifiable['email'] = ($emailId != '') ? array($emailId) : array();

        if (isset($contactInAdditionContactNumber) && isset($additionalContactNumber)) {
            array_push($applicantNotifiable['mobile'], $additionalContactNumber);
        }
        if (isset($contactInAdditionEmail) && isset($additionalEmailId)) {
            array_push($applicantNotifiable['email'], $additionalEmailId);
        }
        return $applicantNotifiable;
    }
}

if (!function_exists('objectToArray')) {
    function objectToArray($object)
    {
        if (!is_object($object) && !is_array($object)) {
            return $object;
        }
        return array_map('objectToArray', (array)$object);
    }
}

if(!function_exists('html_encode')){
    function html_encode($str){
        $str = htmlspecialchars($str);
        return htmlentities($str);
    }
}

if(!function_exists('html_decode')){
    function html_decode($str){
        $str = htmlspecialchars_decode($str);
        return html_entity_decode($str);
    }
}
if(!function_exists('validateHeader')){
    function validateHeader(){
        $ci                    =& get_instance();
        $apiKeyFilter['token'] = $ci->input->request_headers()['rtps_grievance'];
        $ci->load->model('api_key_model');
        $apiValidationQuery = $ci->api_key_model->first_where($apiKeyFilter);
//        var_dump(empty((array)$apiValidationQuery));die();
        if($apiValidationQuery == NULL || empty((array)$apiValidationQuery)){
            return $ci->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode(['status' => 'Unauthorized Request.']));
        }
        return true;
//        sha1('grievance_post_submit').md5('grievance_post_submit');
    }
}

if(!function_exists('generate_otp')){
    function generate_otp($length = 6)
    {
        $otp = "";
        $numbers = "0123456789";
        for ($i = 0; $i < $length; $i++) {
            $otp .= $numbers[rand(0, strlen($numbers) - 1)];
        }
        return $otp;
    }
}

if(!function_exists('configureForwardTo')){
    function configureForwardTo($action,$actionTo, array $processInputs, $appealApplication): array
    {
        $CI =& get_instance();
        $appealApplicationUpdateInputs = array();
        $actionToUser = false;
        $actionToException = false;
        if (isset($actionTo) && $actionTo != "") {
            $previousUser = '';
            switch ($action){
                case 'forward':
                    $processInputs['forward_to'] = $actionTo;
                    break;
                case 'reassign':
                    $processInputs['reassign_to'] = $actionTo;
                    break;
                default:
                    $actionToException = true;
                    return array($appealApplicationUpdateInputs, $processInputs, $actionToUser, $actionToException);
            }
            $actionToUser = $CI->users_model->get_by_doc_id($actionTo);
            $roleKey = getRoleKeyById($actionToUser->roleId);
            switch ($roleKey) {
                case 'DPS':
                    if (property_exists($appealApplication, 'dps_details')) {
                        $appealApplicationUpdateInputs['dps_id'] = new  MongoDB\BSON\ObjectId($actionToUser->{'_id'}->{'$id'});
                        $previousUser = $appealApplication->dps_details->{'_id'};
                    }
                    break;
                case 'AA':
                    if (property_exists($appealApplication, 'appellate_details')) {
                        $appealApplicationUpdateInputs['appellate_id'] = new  MongoDB\BSON\ObjectId($actionToUser->{'_id'}->{'$id'});
                        $previousUser = $appealApplication->appellate_details->{'_id'};
                    }
                    break;
                case 'RA':
                    if (property_exists($appealApplication, 'reviewer_details')) {
                        $appealApplicationUpdateInputs['reviewing_id'] = new  MongoDB\BSON\ObjectId($actionToUser->{'_id'}->{'$id'});
                        $previousUser = $appealApplication->reviewer_details->{'_id'};
                    }
                    break;
                default:
                    $actionToException = true;
                    break;
            }
            $processInputs['previous_user'] = $previousUser;
        }
        return array($appealApplicationUpdateInputs, $processInputs, $actionToUser, $actionToException);
    }
}