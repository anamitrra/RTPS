<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Sms_model extends Mongo_model
{
   /**
    * __construct
    *
    * @return void
    */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("sms");
    }

        /**
     * resend_failed_emails
     *
     * @return void
     */
    public function resend_failed_sms()
    {
        $min_5=1000*60*60*3;//3hour
        $date_expire = new MongoDB\BSON\UTCDateTime((microtime(true)*1000-$min_5));
        $data=["sending_status"=>"pending"];
        $filter=array(
            '$and'=>[
                ['processed_date'=>['$gt'=>$date_expire]],["sending_status"=>"sending"]
            ]
        );
        $this->update_where($filter,$data);
    }
}