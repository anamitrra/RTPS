<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Appeal_reports_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("appeal_applications");
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
    public function appeals_search_rows($limit, $start, $keyword, $col, $dir,$temp)
    {
    
        // $temp = array(
        //     '$or'=>[
        //         ['name_of_service' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
        //         ['appeal_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
        //       ]
        //     );
        // return $this->search_rows($limit, $start, $temp, $col, $dir);


        $collection = 'appeal_applications';
        if($dir=='desc'){
            $dir=-1;
        }else{
            $dir=1;
        }

        $project=array(
            '_id'               => 1,
            'location_id'       => 1,
            // 'service_id'        => 1,
            'appl_ref_no'            => 1,
            'appeal_id'            => 1,
            'applicant_name'            => 1,
            'gender'            => 1,
            'contact_number'            => 1,
            'contact_in_addition_contact_number'            => 1,
            'additional_contact_number'            => 1,
            'date_of_application'            => 1,
            'email_id'            => 1,
            'appeal_type'            => 1,
            'appeal_description'            => 1,
            'applied_by'            => 1,
            'process_status'            => 1,
            'name_of_service'            => 1,
            'created_at'            => 1,
            'tentative_hearing_date'            => 1,
            'date_of_hearing'            => 1,
            'disposal_date'            => 1,
            'second_appeal_applied'            => 1,
            'district'      => 1,
            'location'          => '$location'
        );
        
        if (count($temp) == 0) {

            $filter=array();
            if(!empty($keyword)){
                $filter["\$or"] = array(
                    
                        ['name_of_service' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
                        ['appeal_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
                    
                    );
            }


            $operations = array(
                array(
                    '$match'=>$filter
                ),
                array(
                    '$lookup'  => array(
                        'from'         => 'locations',
                        'localField'   => 'location_id',
                        'foreignField' => '_id',
                        'as'           => 'location'
                    ),
                ),
                array('$unwind' => '$location'),
                array('$skip' => intval($start)),
                array('$limit' => intval($limit)),
                array('$sort'=>array($col=>$dir)),
                array(
                    '$project' => $project
                )
            );
        } else {
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["created_at"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }
            $arr = array();
            if (isset($temp["services"]) &&  $temp["services"] != "") {
                array_push($arr, array("service_id" => new ObjectId($temp["services"])));
            }
            $or_arr=array();
            if (isset($temp["user"]) &&  $temp["user"] != "") {
                array_push($or_arr, array('process_users.userId' => new ObjectId($temp["user"])));
                // array_push($or_arr, array('dps_id' => new ObjectId($temp["user"])));
                // array_push($or_arr, array('appellate_id' => new ObjectId($temp["user"])));
                // array_push($or_arr, array('reviewing_id' => new ObjectId($temp["user"])));
            }
            if(!empty($arr)){
                $filter["\$and"] = $arr;
            }
            if(!empty($or_arr)){
                $filter["\$or"] = $or_arr;
            }
            if(!empty($keyword)){
                $filter["\$or"] = array(
                    
                        ['name_of_service' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
                        ['appeal_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
                    
                    );
            }
            $operations = array(
                array(
                    '$match'=>$filter
                ),
                array(
                    '$lookup'  => array(
                        'from'         => 'locations',
                        'localField'   => 'location_id',
                        'foreignField' => '_id',
                        'as'           => 'location'
                    ),
                ),
                array('$unwind' => '$location'),
                array('$skip' => intval($start)),
                array('$limit' => intval($limit)),
                array('$sort'=>array($col=>$dir)),
                array(
                    '$project' => $project
                )
            );
        }


       
        // pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;



        
    }
    /**
     * appeals_tot_search_rows
     *
     * @param mixed $keyword
     * @return void
     */
    public function appeals_tot_search_rows($keyword)
    {
        // array(
        //     '$match' => array(
        //         '$or'=>[
        //           ['service.service_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
        //           ['location.location_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
        //         ])
        // ),

        $temp = array(
            '$or'=>[
                ['name_of_service' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
                ['appeal_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ]
            );
        
        // print_r($temp);
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
            return $this->all_rows($limit, $start, $col, $dir);
        } else {
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["created_at"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }
            $arr = array();
            if (isset($temp["services"]) &&  $temp["services"] != "") {
                array_push($arr, array("service_id" => new ObjectId($temp["services"])));
            }
            $or_arr=array();
            if (isset($temp["user"]) &&  $temp["user"] != "") {
                array_push($or_arr, array('process_users.userId' => new ObjectId($temp["user"])));
                // array_push($or_arr, array('dps_id' => new ObjectId($temp["user"])));
                // array_push($or_arr, array('appellate_id' => new ObjectId($temp["user"])));
                // array_push($or_arr, array('reviewing_id' => new ObjectId($temp["user"])));
            }
            if(!empty($arr)){
                $filter["\$and"] = $arr;
            }
            if(!empty($or_arr)){
                $filter["\$or"] = $or_arr;
            }
           // pre($filter);
            $col = "created_at";
            $dir = "asc";
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        }
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
            return $this->total_rows();
        } else {
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["created_at"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }
            $arr = array();
            if (isset($temp["services"]) &&  $temp["services"] != "") {
                array_push($arr, array("service_id" => new ObjectId($temp["services"])));
            }

            if (isset($temp["appeal_type"]) &&  $temp["appeal_type"] != "") {
                array_push($arr, array("appeal_type" =>$temp["appeal_type"]));
            }
            $or_arr=array();
            if (isset($temp["user"]) &&  $temp["user"] != "") {
                array_push($or_arr, array('dps_id' => new ObjectId($temp["user"])));
                array_push($or_arr, array('appellate_id' => new ObjectId($temp["user"])));
                array_push($or_arr, array('reviewing_id' => new ObjectId($temp["user"])));
            }

                if(!empty($arr)){
                    $filter["\$and"] = $arr;
                }
                if(!empty($or_arr)){
                    $filter["\$or"] = $or_arr;
                }
           

            return $this->tot_search_rows($filter);
        }
    }



    public function get_all_appeals($limit, $start, $col = NULL, $dir = NULL,$temp=NULL)
    {
        $collection = 'appeal_applications';
        if($dir=='desc'){
            $dir=-1;
        }else{
            $dir=1;
        }

        $project=array(
            '_id'               => 1,
            'location_id'       => 1,
            // 'service_id'        => 1,
            'appl_ref_no'            => 1,
            'appeal_id'            => 1,
            'applicant_name'            => 1,
            'gender'            => 1,
            'contact_number'            => 1,
            'contact_in_addition_contact_number'            => 1,
            'additional_contact_number'            => 1,
            'date_of_application'            => 1,
            'email_id'            => 1,
            'appeal_type'            => 1,
            'appeal_description'            => 1,
            'applied_by'            => 1,
            'process_status'            => 1,
            'name_of_service'            => 1,
            'created_at'            => 1,
            'tentative_hearing_date'            => 1,
            'date_of_hearing'            => 1,
            'disposal_date'            => 1,
            'second_appeal_applied'            => 1,
            'district'      => 1,
            'location'          => '$location'
        );
        
        if (count($temp) == 0) {
            $operations = array(
                array(
                    '$lookup'  => array(
                        'from'         => 'locations',
                        'localField'   => 'location_id',
                        'foreignField' => '_id',
                        'as'           => 'location'
                    ),
                ),
                array('$unwind' => '$location'),
                array('$skip' => intval($start)),
                array('$limit' => intval($limit)),
                array('$sort'=>array($col=>$dir)),
                array(
                    '$project' => $project
                )
            );
        } else {
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["created_at"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }
            $arr = array();
            if (isset($temp["services"]) &&  $temp["services"] != "") {
                array_push($arr, array("service_id" => new ObjectId($temp["services"])));
            }
            if (isset($temp["appeal_type"]) &&  $temp["appeal_type"] != "") {
                array_push($arr, array("appeal_type" => $temp["appeal_type"]));
            }
         
            $or_arr=array();
            if (isset($temp["user"]) &&  $temp["user"] != "") {
                array_push($or_arr, array('process_users.userId' => new ObjectId($temp["user"])));
                // array_push($or_arr, array('dps_id' => new ObjectId($temp["user"])));
                // array_push($or_arr, array('appellate_id' => new ObjectId($temp["user"])));
                // array_push($or_arr, array('reviewing_id' => new ObjectId($temp["user"])));
            }
            if(!empty($arr)){
                $filter["\$and"] = $arr;
            }
            if(!empty($or_arr)){
                $filter["\$or"] = $or_arr;
            }
            $operations = array(
                array(
                    '$match'=>$filter
                ),
                array(
                    '$lookup'  => array(
                        'from'         => 'locations',
                        'localField'   => 'location_id',
                        'foreignField' => '_id',
                        'as'           => 'location'
                    ),
                ),
                array('$unwind' => '$location'),
                array('$skip' => intval($start)),
                array('$limit' => intval($limit)),
                array('$sort'=>array($col=>$dir)),
                array(
                    '$project' => $project
                )
            );
        }


       
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }

    public function get_filtered_appeals($temp=NULL)
    {
        $collection = 'appeal_applications';
      
        $project=array(
            '_id'               => 1,
            'location_id'       => 1,
            // 'service_id'        => 1,
            'appl_ref_no'            => 1,
            'appeal_id'            => 1,
            'applicant_name'            => 1,
            'gender'            => 1,
            'contact_number'            => 1,
            'contact_in_addition_contact_number'            => 1,
            'additional_contact_number'            => 1,
            'date_of_application'            => 1,
            'email_id'            => 1,
            'appeal_type'            => 1,
            'appeal_description'            => 1,
            'applied_by'            => 1,
            'process_status'            => 1,
            'name_of_service'            => 1,
            'created_at'            => 1,
            'tentative_hearing_date'            => 1,
            'date_of_hearing'            => 1,
            'disposal_date'            => 1,
            'second_appeal_applied'            => 1,
            'district'      => 1,
            'location'          => '$location',
            // 'process_users_info'=>1,
            // 'process_users_new'=>1
            'process_users_new.name'=>1,
            'process_users_new.role_slug'=>1,
            'process_users_new.active'=>1,
            'process_users_new.mobile'=>1,
            'process_users_new.email'=>1,
            'process_users_new.userId'=>1
        );
        
        if (count($temp) == 0) {
            $operations = array(
                array(
                    '$lookup'  => array(
                        'from'         => 'locations',
                        'localField'   => 'location_id',
                        'foreignField' => '_id',
                        'as'           => 'location'
                    ),
                ),
                array('$unwind' => '$location'),
                array(
                    '$project' => $project
                )
            );
        } else {
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["created_at"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }
            $arr = array();
            if (isset($temp["services"]) &&  $temp["services"] != "") {
                array_push($arr, array("service_id" => new ObjectId($temp["services"])));
            }
            if (isset($temp["appeal_type"]) &&  $temp["appeal_type"] != "") {
                array_push($arr, array("appeal_type" => $temp["appeal_type"]));
            }
         
            $or_arr=array();
            if (isset($temp["user"]) &&  $temp["user"] != "") {
                array_push($or_arr, array('process_users.userId' => new ObjectId($temp["user"])));
                // array_push($or_arr, array('dps_id' => new ObjectId($temp["user"])));
                // array_push($or_arr, array('appellate_id' => new ObjectId($temp["user"])));
                // array_push($or_arr, array('reviewing_id' => new ObjectId($temp["user"])));
            }
            if(!empty($arr)){
                $filter["\$and"] = $arr;
            }
            if(!empty($or_arr)){
                $filter["\$or"] = $or_arr;
            }
            $operations = array(
                array(
                    '$match'=>$filter
                ),
                array(
                    '$lookup'  => array(
                        'from'         => 'locations',
                        'localField'   => 'location_id',
                        'foreignField' => '_id',
                        'as'           => 'location'
                    ),
                ),
               
                array('$unwind' => '$location'),
                array(
                    '$lookup'=>array(
                        'from'=>'users',
                        // 'localField'=>'process_users.userId',
                        'localField'=>'process_users.userId',
                        'foreignField'=>'_id',
                        'as'=>'process_users_info'
                    )
                ),
                array('$set'=>[
                    'process_users_new'=>[
                        '$map'=>[
                            'input'=>'$process_users',
                            'as' => 'pro_usr',
                            'in'=>[
                                '$mergeObjects'=>[
                                    '$$pro_usr',
                                    [
                                        '$arrayElemAt'=>[
                                            [
                                                '$filter'=>[
                                                    'input'=> '$process_users_info',
                                                    'as'=>'info',
                                                    'cond'=> [ '$eq'=> ['$$info._id', '$$pro_usr.userId'] ]
                                                ]
                                            ],
                                            0
                                            // '$process_users_info',
                                            // [
                                            //     '$indexOfArray'=>['$process_users_info.userId','$$pro_usr.userId']
                                            // ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]),
                

                array(
                    '$project' => $project
                )
            );
        }


       
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }


}
