<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Email_model extends Mongo_model
{
   /**
    * __construct
    *
    * @return void
    */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("emails");
    }

    /**
     * resend_failed_emails
     *
     * @return void
     */
    public function resend_failed_emails()
    {
        $min_5=1000*60*60*3;//3 hour
        $date_expire = new MongoDB\BSON\UTCDateTime((microtime(true)*1000-$min_5));
        $data=["status"=>"pending"];
        $filter=array(
            '$and'=>[
                ['processed_date'=>['$gt'=>$date_expire]],["status"=>"sending"]
            ]
        );
        $this->update_where($filter,$data);
    }
}