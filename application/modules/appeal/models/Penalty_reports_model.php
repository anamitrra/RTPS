<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    use MongoDB\BSON\ObjectId;
class Penalty_reports_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('appeal_processes');
    }
    public function get_all_penalty_processes()
    {
        $collection = 'appeal_processes';
        $operations = array(
            array(
                '$match' => ['action' => ['$eq' => 'penalize']]
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'action_taken_by',
                    'foreignField' => '_id',
                    'as'           => 'user'
                )
            ),
            array('$unwind' => '$user'),
            array(
                '$project' => array(
                    '_id'               => 1,
                    'appeal_id'         => 1,
                    'action'            => 1,
                    'message'           => 1,
                    'comment'           => 1,
                    'documents'         => 1,
                    'created_at'        => 1,
                    'penalty_amount'    => 1,
                    'penalty_to_user'   => 1,
                    'notifiable'        => 1,
                    'date_of_hearing'   => 1,
                    'last_date_of_submission'   => 1,
                    'forward_to'        => 1,
                    'comment_documents' => 1,
                    'user'              => '$user',
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }
    /**
     * get_by_appl_ref_no
     *
     * @param mixed $appl_ref_no
     * @return void
     */
    public function get_by_appl_ref_no($appl_ref_no)
    {
        $filter['appl_ref_no'] = $appl_ref_no;
        return $this->mongo_db->where($filter)->find_one($this->table);
    }
    /**
     * get_by_appeal_id
     *
     * @param mixed $appeal_id
     * @return void
     */
    public function get_by_appeal_id($appeal_id)
    {
        $filter['appeal_id'] = $appeal_id;
        return $this->mongo_db->where($filter)->find_one($this->table);
    }
    /**
     * appeal
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $keyword
     * @param mixed $col
     * @param mixed $dir
     * @return void
     */
    public function appeals_search_rows($limit, $start, $keyword, $col, $dir)
    {
        $temp = array(
            "initiated_data.appl_ref_no" => array(
                "\$regex" => '^' . $keyword . '',
                "\$options" => 'i'
            )
        );
        //print_r($temp);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }
    /**
     * appeals_tot_search_rows
     *
     * @param mixed $keyword
     * @return void
     */
    public function appeals_tot_search_rows($keyword)
    {
        $temp = array(
            "initiated_data.appl_ref_no" => array(
                "\$regex" => '^' . $keyword . '',
                "\$options" => 'i'
            )
        );
        //print_r($temp);
        return $this->tot_search_rows($temp);
    }
    /**
     * appeals_filter
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $temp
     * @param mixed $col
     * @param mixed $dir
     * @return void
     */
    public function appeals_filter($limit, $start, $temp, $col, $dir)
    {
        if (count($temp) == 0) {
            $filter['action'] ="penalize";
            $col = "created_at";
            $dir = "asc";
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        } else {
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["created_at"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }
            $filter['action'] ="penalize"; 
            $filter['penalty_to_user'] = new ObjectId($temp["user"]);
            //pre($filter);
            $col = "created_at";
            $dir = "asc";
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        }
    }
    public function penalty_filter($limit, $start, $temp, $col, $dir)
    {
        if (count($temp) == 0) {
            $filter['action'] ="penalize";
            $col = "created_at";
            $dir = "asc";
        } else {
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["created_at"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }
            $filter['action'] ="penalize"; 
            $filter['penalty_to_user'] = new ObjectId($temp["user"]);
        }
        //pre($filter);
        $collection = 'appeal_processes';
        $operations = array(
            array(
                '$match' => $filter
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'action_taken_by',
                    'foreignField' => '_id',
                    'as'           => 'action_taken_by'
                )
            ),
            array('$unwind' => '$action_taken_by'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'penalty_to_user',
                    'foreignField' => '_id',
                    'as'           => 'penalty_to_user'
                )
            ),
            array('$unwind' => '$penalty_to_user'),
            array('$limit'=>(int)$limit),
            array('$skip'=>(int)$start),
            array(
                '$project' => array(
                    '_id'               => 1,
                    'appeal_id'         => 1,
                    'action'            => 1,
                    'message'           => 1,
                    'comment'           => 1,
                    'documents'         => 1,
                    'created_at'        => 1,
                    'penalty_amount'    => 1,
                    'penalty_to_user'   => '$penalty_to_user',
                    'notifiable'        => 1,
                    'date_of_hearing'   => 1,
                    'last_date_of_submission'   => 1,
                    'forward_to'        => 1,
                    'comment_documents' => 1,
                    'action_taken_by'              => '$action_taken_by',
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }
    /**
     * appeals_filter_count
     *
     * @param mixed $temp
     * @return void
     */
    public function appeals_filter_count($temp)
    {
        if (count($temp) == 0) {
            $filter['action'] ="penalize";
            return $this->tot_search_rows($filter);
        } else {
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["created_at"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }
            $filter['action'] ="penalize"; 
            $filter['penalty_to_user'] = new ObjectId($temp["user"]);


            return $this->tot_search_rows($filter);
        }
    }

}
