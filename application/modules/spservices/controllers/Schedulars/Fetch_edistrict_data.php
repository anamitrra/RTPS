<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Fetch_edistrict_data extends frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper('trackstatus');
    } //End of __construct()


    public function fetch_update()
    {
        // pre('From CMD');
        // php .\cli.php  spservices/Trackapplication retrive_edist_applications

        $CI = &get_instance();

        $command = array(
            'distinct' => 'edistrict_fetch_data',
            'key' => 'edistrict_ref_no',
            'query' => array(
                'sl_no' => array('$exists' => true, '$lte' =>70000),
                'pull_status' => array('$exists' => false),
            )
        );
        
        $ssdgRefNos = $this->mongo_db->command($command); 
        //pre($ssdgRefNos);

        foreach ($ssdgRefNos as $valuess) {
            $result = $valuess->values;
            $ssdgRefNos = array_values($result);
        }
        $total_appl = sizeof($ssdgRefNos);
        echo "Total data to check: {$total_appl}" . PHP_EOL;
        for($i=0; $i<1; $i++) {
            $edistrict_ref_no = $ssdgRefNos[$i]; 
            $data_to_update = array();
            echo "Working on {$edistrict_ref_no}" . PHP_EOL;
            $result = fetchEdistrictData($edistrict_ref_no);
            if ($result instanceof MongoDB\Driver\WriteResult) {
                $matchedCount = $result->getMatchedCount();
                $modifiedCount = $result->getModifiedCount();
                if($matchedCount==1 && $modifiedCount==1){
                    $data_to_update['pull_status'] = true;
                    $data_to_update['pull_remark'] = "Successfully updated";
                    echo "Successfully updated.!" . PHP_EOL;
                }else if($matchedCount==1 && $modifiedCount==0){
                    $data_to_update['pull_status'] = true;
                    $data_to_update['pull_remark'] = "Already updated";
                    echo "Already updated.!" . PHP_EOL;
                }
            
            } else {
                $data_to_update['pull_status'] = false;
                $data_to_update['pull_remark'] = "Update operation failed";
                echo "Update operation failed." . PHP_EOL;
            }

            $CI->mongo_db->set($data_to_update);
            $CI->mongo_db->where(array('edistrict_ref_no' => $edistrict_ref_no));
            $CI->mongo_db->update('edistrict_fetch_data');

            $total_appl--;
            echo "Total remainings: {$total_appl}" . PHP_EOL;
            echo PHP_EOL;
        }

    }

}//End of reinitiate_applications
