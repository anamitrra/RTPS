<?php


class Second_appeal_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('second_appeal_otp');
    }

    // insert otp
    public function set_otp($data)
    {
        $this->insert($data);
    }

    /**
     * check_if_otp_time_expired
     *
     * @param $appeal_id
     * @param $mobile
     * @param $otp
     * @return void
     */
    public function check_if_otp_time_expired($appeal_id, $mobile, $otp)
    {
        // '{ $subtract: [ new Date(), "$created_at" ] } '
        $collection="second_appeal_otp";
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true)*1000);
        $operations = array(
            array(
                '$match'=>array(
                    "\$and"=>array(
                        array("appeal_id"=>array("\$eq"=>$appeal_id),),
                        array("mobile"=>array("\$eq"=>$mobile),),
                        array("otp"=>array("\$eq"=>$otp),),
                    )
                ),
            ),
            array(
                '$project' => array(
                    'dateDifference'    => array("\$subtract"=>array($current_time,"\$created_at"))
                ),

            )
        );
        //pre($operations);
        $data=$this->mongo_db->aggregate($collection,$operations);
        if(isset($data->{'0'})){
            $minutes_10=10*60*1000;//milliseconds
            if($data->{'0'}->dateDifference > $minutes_10){
                return FALSE;
            }else{
                return $data->{'0'}->{'_id'}->{'$id'};
            }
        }else{
            return FALSE;
        }

    }
}