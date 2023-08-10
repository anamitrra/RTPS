<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\UTCDateTime;

class Basundhara_query extends frontend
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('intermediator_model');
        // $this->load->model('portals_model');
        $this->config->load('rtps_services');
        $this->load->library('AES');
        $this->encryption_key=$this->config->item("encryption_key");
    }
    public function payment_details(){
        $json = file_get_contents('php://input');
        if(!empty($json)){
            $data = json_decode($json,true);
            if(isset( $data['data'])){
                $aes = new AES($data['data'], $this->encryption_key);
                $dec = $aes->decrypt();
                if(!empty($dec)){
                    $input=json_decode( $dec ,true);
                    if(empty($input['app_ref_no'] )){
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status"=>false,"message"=>"App ref no not found")));
                    }

                    $dbRow = $this->intermediator_model->get_row(array('app_ref_no'=>$input['app_ref_no'] ));
                    
                    if( empty($dbRow)){
                        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array("status"=>false,"message"=>"No records found with this app ref no")));
                    }
                    if(property_exists($dbRow,'query_payment_status') && $dbRow->query_payment_status==='Y'){
                        $res=array(
                            "app_ref_no"=>$input['app_ref_no'],
                            "egras_data"=>$dbRow->query_payment_response
                        );
                        $aes = new AES(json_encode($res), $this->encryption_key);
                        $enc = $aes->encrypt();
                        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array("status"=>true,"message"=>"","data"=> $enc)));
                    }else{
                        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array("status"=>false,"message"=>"No data found","data"=> array())));
                    }
                   
                     
                }
                
            }
        }else{
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array("status"=>false,"message"=>"No data found")));
        }
    }
    public function payment_query(){
        $json = file_get_contents('php://input');
        if(!empty($json)){
            $data = json_decode($json,true);
            if(isset( $data['data'])){
                $aes = new AES($data['data'], $this->encryption_key);
                $dec = $aes->decrypt();
                if(!empty($dec)){
                    $input=json_decode( $dec ,true);
                    if(empty($input['payment_config']) || empty($input['app_ref_no'] )){
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode(array("status"=>false,"message"=>"Invalid Parameters")));
                    }

                    $dbRow = $this->intermediator_model->get_row(array('app_ref_no'=>$input['app_ref_no'] ));
                   
                    if( empty($dbRow)){
                        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array("status"=>false,"message"=>"No records found with this app ref no")));
                    }
                    if(property_exists($dbRow,'query_payment_status') && $dbRow->query_payment_status==='Y'){
                        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(array("status"=>false,"message"=>"Already payment has been collected from citizen")));
                    }
                    $data_to_save=array(
                        'query_payment_config'=>$input['payment_config'],
                        "query_status"=>'PQ'
                    );
                       $res= $this->intermediator_model->update_row(array('app_ref_no'=>$input['app_ref_no']), $data_to_save);
                   // pre($res);
                            if( $res->getMatchedCount()){
                                $data_to_save['app_ref_no']=$input['app_ref_no'];
                                $this->mongo_db->insert('query_payment_request_history',$data_to_save);
                                return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(array("status"=>true,'message'=>"Successfully data saved")));
                             
                            }else{
                                return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200)
                                ->set_output(json_encode(array("status"=>false,'message'=>"something went wrong")));

                            }
                }
                
            }
        }else{
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array("status"=>false,"message"=>"No data found")));
        }
 
    }
    public function test_post(){
        $data_to_post=array(
            "app_ref_no"=>"RTPS/EKHU/2022/176",
            "payment_config"=>array(
            'DEPT_CODE'=>'LRS',
            'OFFICE_CODE' =>  'LRS317',
            'REC_FIN_YEAR' =>  '2022-2023',
            'FROM_DATE' =>  '01/04/2022',
            'TO_DATE' =>  '31/03/2099' ,
            'PERIOD' =>  'O' ,
            'TAX_ID' =>  'tin123' ,
            'PAN_NO' =>  '          ' ,
            'PARTY_NAME' =>  'dfgfd' ,
            'ADDRESS1' =>  'ggf' ,
            'ADDRESS2' =>  'hfghgf' ,
            'ADDRESS3' =>  'fghghf',
            'PIN_NO' =>  '781003',
            'MOBILE_NO' =>  '9435347177     ' ,
            'REMARKS' =>  'Purpose of challan',
            'FORM_ID' =>  '' ,
            'PAYMENT_TYPE' =>  '03' ,
            'TREASURY_CODE' =>  'AKM',
            'MAJOR_HEAD' =>  '0070' ,
            'AMOUNT1' =>  '500' ,
            'HOA1' =>  '0070-00-800-0000-000-01' ,
            'AMOUNT2' =>  '' ,
            'HOA2' =>  '' ,
            'AMOUNT3' =>  '' ,
            'HOA3' =>  '',
            'AMOUNT4' =>  '',
            'HOA4' =>  '',
            'AMOUNT5' =>  '',
            'HOA5' =>  '' ,
            'AMOUNT6' =>  '',
            'HOA6' =>  '',
            'AMOUNT7' =>  '' ,
            'HOA7' =>  '' ,
            'AMOUNT8' =>  '' ,
            'HOA8' =>  '' ,
            'AMOUNT9' =>  '' ,
            'HOA9' =>  '' ,
            'CHALLAN_AMOUNT' =>  '500'
            )
        );
        // pre(  $data_to_post);
        $input_array=json_encode($data_to_post);
        $aes = new AES($input_array, $this->encryption_key);
        $enc = $aes->encrypt();
        pre( $enc);
        // $data=array(
        //   "data"=>$enc
        // );
    }

    
   
}