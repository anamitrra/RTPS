<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;

class Public_grievance_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("public_grievances");
    }//End of __construct()

    public function get_filtered_rows($filter) {
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        $resultsArray = (array) $res;
        if (count($resultsArray)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_filtered_rows()

    public function get_with_status($filter, $searchArray = [], $start = false, $limit = false, $orderByArray = false): array {
        $matchArray['$and'] = array($filter);

        if (!empty($searchArray)) {
            $searchAnd = [];
            foreach ($searchArray as $searchKey => $dataToSearchFor) {
                $searchAnd[] = [$searchKey => ['$regex' => '^' . $dataToSearchFor . '', '$options' => 'i']];
            }
        }
        if (!empty($searchAnd)) {
            $matchArray['$or'] = $searchAnd;
        }
        $operations = array(
            array(
                '$match' => $matchArray
            ),
            array(
                '$lookup' => array(
                    'from' => 'view_status',
                    'localField' => 'registration_no',
                    'foreignField' => 'RegistrationNumber',
                    'as' => 'grievance_data'
                )
            ),
            array('$unwind' => '$grievance_data'),
            array(
                '$project' => array(
                    '_id' => 0,
                    "registration_no" => 1,
                    "GrievanceReferenceNumber" => 1,
                    "Name" => 1,
                    "EmailAddress" => 1,
                    "MobileNumber" => 1,
                    "grievanceCategory" => 1,
                    "DateOfReceipt" => 1,
                    "grievance_data" => '$grievance_data',
                )
            )
        );
        if ($start !== false && $limit !== false) {
            $operations[] = ['$skip' => intval($start)];
            $operations[] = ['$limit' => intval($limit)];
        }
        if ($orderByArray) {
            $operations[] = ['$sort' => $orderByArray];
        }
        $data = $this->mongo_db->aggregate($this->table, $operations);
        return (array) $data;
    }//End of get_with_status()
    
    public function get_row($filter = null) {
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->find_one($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_row()
    
    public function get_rows($filter = null) {
        $this->mongo_db->order_by('created_at', 'DESC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()

}
