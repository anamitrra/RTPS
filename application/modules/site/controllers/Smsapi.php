<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Smsapi extends Frontend
{
      /**
   * __construct
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }
  
  // public function index(){
  //       $mobile_numbers=array('9508133232','7002266283','7002882210');


  //       $input=$this->input->get();
  //       $cpu=isset($input['cpu'])?$input['cpu']:'';
  //       $memory=isset($input['memory'])?$input['memory']:'';
  //       $disk=isset($input['disk'])?$input['disk']:'';
  //       $dbconnection=isset($input['dbconnection'])?$input['dbconnection']:'';
  //       $status=isset($input['status'])?$input['status']:'';

      
  //       if(!empty($input)){
  //           if(!empty($cpu) && !empty($memory) && !empty($disk) && !empty($dbconnection)) {
  //               if(!empty($mobile_numbers)){
  //                   foreach($mobile_numbers as $number){
                        
  //                       if($this->validate_mobile($number)){
  //                           $this->send($number,$cpu,$memory,$disk,$dbconnection,$status);
  //                         }else{
  //                               exit("Invalid Mobile Number");
  //                       }
  //                   }
  //               }
              
  //           }else{
  //               exit("No Data Found");
  //           }  
  //       }else{
  //           exit("No Data Found");
  //       }
  //   }
    public function testsms(){
        $mobile_numbers=array('9508133232');


        $input=$this->input->get();
        $cpu=isset($input['cpu'])?$input['cpu']:'';
        $memory=isset($input['memory'])?$input['memory']:'';
        $disk=isset($input['disk'])?$input['disk']:'';
        $dbconnection=isset($input['dbconnection'])?$input['dbconnection']:'';
        $status=isset($input['status'])?$input['status']:'';

      
        if(!empty($input)){
            if(!empty($cpu) && !empty($memory) && !empty($disk) && !empty($dbconnection)) {
                if(!empty($mobile_numbers)){
                    foreach($mobile_numbers as $number){
                        
                        if($this->validate_mobile($number)){
                            $this->send($number,$cpu,$memory,$disk,$dbconnection,$status);
                          }else{
                                exit("Invalid Mobile Number");
                        }
                    }
                }
              
            }else{
                exit("No Data Found");
            }  
        }else{
            exit("No Data Found");
        }
    }
    private function validate_mobile($number)
      {
          return preg_match('/^[6-9]\d{9}$/', $number);
      }
     //send SMS

     private function send($number, $cpu,$memory,$disk,$dbconnection,$status) {
        
      $dlt_template_id="1007164086220096768";
      $ch = curl_init();
      $message_body='Resource alert: CPU: '.$cpu.', MEMORY: '.$memory.', DISK: '.$disk.', DB CONNECTIONS: '.$dbconnection.', SERVER PING STATUS:'.$status.' - ARTPS Admin';
      $message_body = str_replace(" ", "%20", $message_body);
      $url = "https://smsgw.sms.gov.in/failsafe/MLink?username=rtpsasm.otp&pin=P4%26fO3%23xF4&message=" . $message_body . "&mnumber=" . $number . "&signature=ARTPSA&dlt_entity_id=1001367290000017171&dlt_template_id=" . $dlt_template_id;
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      $head = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      //var_dump($head);
      return $head;
      }//End of sendSms()
}
