<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Feedback_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("feedback");
    }
    public function get_applications_all(){
        return $this->mongo_db->get('applications');
    }
    public function check_feedback_by_appl_ref_no($appl_ref_no,$type)
    {
    
        $filter['feedback_on'] = $type;
        $filter['appl_ref_no'] = $appl_ref_no;
        return $this->mongo_db->where($filter)->find_one($this->table);
    } 
    public function get_applications_submitted_on_today($is_test=false)
    {
        if($is_test){
            return $this->mongo_db->where(['initiated_data.appl_ref_no'=>'RTPS-REESA/2021/01430'])->get('applications');
        }else{
            $startDate=date('Y-m-d');
            $collection = 'applications';
            $operations = array(
                array(
                    '$match' => [
                        '$expr' => [
                            '$gte' => ['$initiated_data.submission_date', new MongoDB\BSON\UTCDateTime(strtotime($startDate) * 1000)]
                        ]
    
                    ]
                        ),
                        array(
                            '$match' => [
                                '$expr' => [
                                    '$lt' => ['$initiated_data.submission_date', new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($startDate)) * 1000)]
                                ]
            
                            ]
                        )
            );
            $data = $this->mongo_db->aggregate($collection, $operations);
            return $data;
        }
      
        
    }
    public function get_applications_delivered_on_today($is_test=false)
    {
        if($is_test){
            return $this->mongo_db->where(['initiated_data.appl_ref_no'=>'RTPS-REESA/2021/01430'])->get('applications');
        }else{
            $startDate=date('Y-m-d');
            $filter['execution_data.0.official_form_details.action'] = ['$eq' => 'Deliver'];
            $filter["execution_data.0.task_details.executed_time"] = array(
                "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($startDate) * 1000),
                "\$lt" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($startDate)) * 1000)
            );
    
            $this->mongo_db->where($filter);
            return $this->mongo_db->get('applications');
        }
        
        
    }
    public function applications_search_rows($limit, $start, $keyword, $col, $dir,$match)
    {

      

        $temp = array(
            '$or'=>[
              ['appl_ref_no' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['service_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['department_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['submission_location' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['feedback_on' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            ]
        );

        if(!empty($match)){
            if(array_key_exists(0,$match)){
                $temp['$and'] = $match;
            }else{
                $temp['$and'] = array($match);
            }
        }
      //  pre($temp);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }

    /**
     * users_tot_search_rows
     *
     * @param mixed $keyword
     * @return void
     */
    public function applications_tot_search_rows($keyword,$match)
    {       $temp = array();
            // if(!empty($keyword)){
            //     $temp = array(
            //         '$or'=>[
            //         ['appl_ref_no' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            //         ['service_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            //         ['department_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            //         ['submission_location' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            //         ['feedback_on' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            //         ]
            //     );
            // }
           

            // if(!empty($match)){
            //     if(array_key_exists(0,$match)){
            //         $temp['$and'] = $match;
            //     }else{
            //         $temp['$and'] = array($match);
            //     }
            // }
         
            return $this->tot_search_rows($temp);

     }

     public function get_filtered_feedback($projectionArray,$match = [],$searchArray = [],$start = false,$limit = false, $orderByArray = []): array
     {
         if(!empty($match)){
             if(array_key_exists(0,$match)){
                 $matchArray['$and'] = $match;
             }else{
                 $matchArray['$and'] = array($match);
             }
         }
         if(!empty($searchArray)){
             $searchAnd = [];
             foreach ($searchArray as $searchKey => $dataToSearchFor){
                 $searchAnd[] = [$searchKey => ['$regex' => '^' . $dataToSearchFor . '', '$options' => 'i']];
             }
         }
         if(!empty($searchAnd)){
             $matchArray['$or'] = $searchAnd;
         }
         $operations = array(
             array(
                 '$project' => $projectionArray
             )
         );
         if(isset($matchArray)){
             $operations[] = array(
                 '$match'  => $matchArray
             );
         }
 //        pre($operations);
         if($start !== false && $limit !== false){
             $operations[] = ['$skip' => intval($start)];
             $operations[] = ['$limit' => intval($limit)];
         }
 //        pre($orderByArray);
         if($orderByArray){
             $operations[] = ['$sort' => $orderByArray];
         }
 //        pre($operations);
         $data = $this->mongo_db->aggregate($this->table, $operations);
         //pre($data);
         $data = (array)$data;
         return $data;
 
     }


     public function get_service(){
        // $filter['service_id'] = "1859";
        // return $this->mongo_db->where($filter)->find_one($this->table);

        $collection = 'feedback';
        $operations = array(
            array(
                '$match' => array(
                    'service_id' => "1859",
                )
            )
        );
       return $this->mongo_db->aggregate($collection, $operations);



     }
     public function get_average(){
        $collection = 'feedback';
        $operations_delivered = array(
                array(
                    '$group'=>[
                        '_id'=>'$feedback_on',
                        'avg_rating'=>['$avg'=>['$toInt'=>'$stars']]
                    ]
                )
        );
        $data = $this->mongo_db->aggregate($collection, $operations_delivered);
        return $data;
    }
     
}