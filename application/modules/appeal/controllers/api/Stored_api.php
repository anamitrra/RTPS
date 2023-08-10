<?php
use MongoDB\BSON\ObjectId;

class Stored_api extends Frontend
{

    public function __construct()
    {
        parent::__construct();
    }

    public function generate(){
        $this->count_appeals();
        $this->application_month_wise();
        $this->count_appeals_by_location();
        echo 'Data Generated Successfully';
    }
    public function count_appeals_by_location(){
        $this->load->model('location_model');
        $location=$this->location_model->get_all("locations");
        $this->load->model('appeals_model');
        // location_id
        // location_name
        foreach($location as $loc){
            $object_id=$loc->_id->{'$id'};
            $pending_appeals_beyond_30days_not_beyond_45days=$this->appeals_model->pending_appeals_beyond_30days_not_beyond_45days_by_location($object_id);
            $pending_appeals_beyond_45days=$this->appeals_model->pending_appeals_beyond_45days_by_location($object_id);
            $disposed_appeals_within_30days=$this->appeals_model->disposed_appeals_within_30days_by_location($object_id);
            $total_appeals=$this->appeals_model->total_appeals_by_location($object_id);
            $new_appeals=$this->appeals_model->total_filtered_appeals_by_location(
                array('location_id'=>new ObjectId($object_id),'process_status' => null)
             );  
             
            $resolved_appeals=$this->appeals_model->total_filtered_appeals_by_location(
                array('location_id'=>new ObjectId($object_id),'process_status' => 'resolved')
             );
            $rejected=$this->appeals_model->total_filtered_appeals_by_location(
                array('location_id'=>new ObjectId($object_id),'process_status' => 'rejected')
             );
      // pre($total_appeals);
            //$total = $this->appeals_model->total_rows();
            // $new = $this->appeals_model->tot_search_rows(array(
            //     'process_status' => null
            // ));
            // $resolved = $this->appeals_model->tot_search_rows(array(
            //     'process_status' => "resolved"
            // ));
            // $rejected = $this->appeals_model->tot_search_rows(array(
            //     'process_status' => "rejected"
            // ));
            $this->load->model('appeal_stored_api_model');
            $appeal_count = $this->appeal_stored_api_model->last_where(['api_type' => 'count_appeals_location','location_id'=>new ObjectId($object_id)]);
            $countAppealInputs = array(
                'api_type' => 'count_appeals_location',
                'location_id'=>new MongoDB\BSON\ObjectId($object_id),
                'location_name'=>$loc->location_name,
                'total' => $total_appeals ?? 0,
                'new' => $new_appeals ?? 0,
                'pending_appeals_beyond_30days_not_beyond_45days' => $pending_appeals_beyond_30days_not_beyond_45days,
                'pending_appeals_beyond_45days' => $pending_appeals_beyond_45days,
                'disposed_appeals_within_30days' => $disposed_appeals_within_30days,
                'resolved' => $resolved_appeals ?? 0,
                'rejected' => $rejected,
                'created_at' => new MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y H:i')) * 1000),
            );
            if(!empty($appeal_count)){
                // update
                $this->appeal_stored_api_model->update_where(['_id' => $appeal_count->{'_id'}->{'$id'}],$countAppealInputs);
            }else{
                // insert
                $this->appeal_stored_api_model->insert($countAppealInputs);
            }
         

        }
        return true;
       
    }
    public function count_appeals()
    {
        $this->load->model('appeals_model');
        $pending_appeals_beyond_30days_not_beyond_45days=$this->appeals_model->pending_appeals_beyond_30days_not_beyond_45days();
        $pending_appeals_beyond_45days=$this->appeals_model->pending_appeals_beyond_45days();
        $disposed_appeals_within_30days=$this->appeals_model->disposed_appeals_within_30days();
        $total = $this->appeals_model->total_rows();
        // pre($total );
        $new = $this->appeals_model->tot_search_rows(array(
            'process_status' => null
        ));
        $resolved = $this->appeals_model->tot_search_rows(array(
            'process_status' => "resolved"
        ));
        $rejected = $this->appeals_model->tot_search_rows(array(
            'process_status' => "rejected"
        ));
        $this->load->model('appeal_stored_api_model');
        $appeal_count = $this->appeal_stored_api_model->last_where(['api_type' => 'count_appeals']);
        // pre(  $appeal_count->{'_id'}->{'$id'});
        $countAppealInputs = array(
            'api_type' => 'count_appeals',
            'total' => $total ?? 0,
            'new' => $new,
            'pending_appeals_beyond_30days_not_beyond_45days' => $pending_appeals_beyond_30days_not_beyond_45days,
            'pending_appeals_beyond_45days' => $pending_appeals_beyond_45days,
            'disposed_appeals_within_30days' => $disposed_appeals_within_30days,
            'resolved' => $resolved,
            'rejected' => $rejected,
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y H:i')) * 1000),
        );
        if(!empty($appeal_count)){
            // update
            $this->appeal_stored_api_model->update_where(['_id' =>new ObjectId($appeal_count->{'_id'}->{'$id'})],$countAppealInputs);
        }else{
            // insert
            $this->appeal_stored_api_model->insert($countAppealInputs);
        }
        return true;
    }

    public function application_month_wise(){

        $application_month_wise = array();
        $months = array_reduce(range(1, 12), function ($rslt, $m) {
            $rslt[$m] = date('F', mktime(0, 0, 0, $m, 10));
            return $rslt;
        });
        $collection = "appeal_applications";
        foreach ($months as $num => $month) {
            $operations = array(
                array(
                    '$project' => array(
                        "month" => array("\$month" => "\$created_at")
                    )
                ),
                array(
                    '$match' => array(
                        "month" => $num
                    )
                ),
                array(
                    '$count' => "doc_nos"
                )
            );
            $data_total = $this->mongo_db->aggregate($collection, $operations);
            if (isset($data_total->{'0'})) {
                array_push($application_month_wise, array($month . '(' . $data_total->{'0'}->doc_nos . ')', $data_total->{'0'}->doc_nos, $data_total->{'0'}->doc_nos));
            } else {
                array_push($application_month_wise, array($month . '(0)', 0, 0));
            }
        }
        $application_month_wise_inputs = [
            'api_type' => 'application_month_wise',
            'application_month_wise' => $application_month_wise,
            'created_at' => new MongoDB\BSON\UTCDateTime(strtotime(date('d-m-Y H:i')) * 1000),
        ];
        $this->load->model('appeal_stored_api_model');
        $application_month_wise_previous = $this->appeal_stored_api_model->last_where(['api_type' => 'application_month_wise']);
        if(!empty($application_month_wise_previous)){
            $this->appeal_stored_api_model->update_where(['_id' =>new ObjectId($application_month_wise_previous->{'_id'}->{'$id'})],$application_month_wise_inputs);
        }else{
            $this->appeal_stored_api_model->insert($application_month_wise_inputs);
        }
        return true;
    }
}