<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sadbhawana_cpgrams
{
    //Testing
    /*private $host = 'http://164.100.230.114/';
    private $key = 'GOVAS';
    private $token = '7bf1179e37024b9745c41f5186f277ac7f895c8c6fe4663bff410f178b94e6a6';*/

    private $apiURL;
    private $section;
    private $host = 'https://pgportal.gov.in/RIServices';
    private $key = 'GOVAS';
    private $token = '601372b8b272d3d195d8e6b34eff1192842ca1967602cd515ef67d6c0baa41ef';

    public function __construct($params)
    {
        $this->set_apiURL($params);
    }

    function get($param = null)
    {
        if ($param == null) {
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $this->apiURL);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            $buffer = curl_exec($curl_handle);
            return $buffer;
        } else {
            $params = '?';
            foreach ($param as $key => $value) {
                $params .= $key . '=' . $value . '&';
            }
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $this->apiURL . $params);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            $buffer = curl_exec($curl_handle);
            return $buffer;
        }
    }

    function post($array)
    {
        $payload = json_encode($array);
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $this->apiURL);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 600); //timeout in seconds
        $buffer = curl_exec($curl_handle);
        if ($buffer === false) {
            echo 'Curl error: ' . curl_error($curl_handle);
            die;
        } else {
            return $buffer;
        } //End of if else        
    }

    function put($array)
    {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $this->apiURL);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($array));
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "PUT");
        $buffer = curl_exec($curl_handle);
        return $buffer;
    }

    function delete($id)
    {
        $array = array("item_id" => $id);
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $this->apiURL);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($array));
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "DELETE");
        $buffer = curl_exec($curl_handle);
        return $buffer;
    }

    /**
     * @param $params
     */
    public function set_apiURL($params): void
    {
        switch ($params['type']) {
            case 'register-grievance':
                $this->section = 'RegisterGrievance/RegisterGrievance';
                break;
            case 'register-reminder':
                $this->section = 'RegisterReminder/RegisterReminder';
                break;
            case 'get-final-closure-list':
                $this->section = 'FinalClosureList/GetFinalClosureList';
                break;
            case 'confirm-closure-receipt':
                $this->section = 'ConfirmClosureReceipt/ConfirmClosureReceipt';
                break;
            case 'country-list':
                $this->section = 'CountryList/CountryList';
                break;
            case 'state-list':
                $this->section = 'StateList/StateList';
                break;
            case 'district-list':
                $this->section = 'DistrictList/DistrictList';
                break;
            case 'view-status':
                $this->section = 'ViewStatus/ViewStatus';
                break;
            default:
                $this->section = '';
                break;
        }
        $this->apiURL = $url = $this->host . '/api/' . $this->section . '?key=' . $this->key . '&token=' . $this->token;
        //$this->apiURL = $url = $this->host . 'riservicetest/api/' . $this->section . '?key=' . $this->key . '&token=' . $this->token; //For Testing
    }
}
