<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    use MongoDB\BSON\ObjectId;
class official_details_model_another extends Mongo_model
{
    function __construct()
    {
        parent::__construct();
        $this->set_collection("official_details_another");
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

     public function get_official_data(){
        $official_data = $this->mongo_db->get($this->table);
        return $official_data;
     }



    public function official_details_search_rows($limit, $start=0, $keyword, $col, $dir)
    {
        $collection = 'official_details_another';
        if($dir=='desc'){
            $dir=-1;
        }else{
            $dir=1;
        }
        $operations = array(
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_details'
                )
            ),
            array('$unwind' => '$dps_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'appellate_id',
                    'foreignField' => '_id',
                    'as'           => 'appellate_auth_details'
                )
            ),
            array('$unwind' => '$appellate_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'reviewing_id',
                    'foreignField' => '_id',
                    'as'           => 'reviewing_auth_details'
                ),
            ),
            array('$unwind' => '$reviewing_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'services',
                    'localField'   => 'service_id',
                    'foreignField' => '_id',
                    'as'           => 'service'
                ),
            ),
            array('$unwind' => '$service'),
            array(
                '$match' => array(
                    'service.service_name' =>array(
                        '$regex' => '^' . $keyword . '',
                        '$options' => 'i'
                    )
                )
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
                '$project' => array(
                    '_id'               => 1,
                    'location_id'       => 1,
                    'service_id'        => 1,
                    'dps_id'            => 1,
                    'appellate_id'      => 1,
                    'reviewing_id'      => 1,
                    'dps_details'       => '$dps_details',
                    'appellate_details' => '$appellate_auth_details',
                    'reviewing_details' => '$reviewing_auth_details',
                    'service'           => '$service',
                    'location'           => '$location',
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }

    public function official_details_search_rows_with_related($limit, $start=0, $keyword, $col, $dir)
    {
        $collection = 'official_details_another';
        if($dir=='desc'){
            $dir=-1;
        }else{
            $dir=1;
        }
        $operations = array(
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_details'
                )
            ),
            array('$unwind' => '$dps_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'appellate_id',
                    'foreignField' => '_id',
                    'as'           => 'appellate_auth_details'
                )
            ),
            array('$unwind' => '$appellate_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'reviewing_id',
                    'foreignField' => '_id',
                    'as'           => 'reviewing_auth_details'
                ),
            ),
            array('$unwind' => '$reviewing_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'services',
                    'localField'   => 'service_id',
                    'foreignField' => '_id',
                    'as'           => 'service'
                ),
            ),
            array('$unwind' => '$service'),
          
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
                '$lookup'  => array(
                    'from'         => 'users',
                    // 'let'=>array('da_list'=>'$da_id'),
                    'let'=>array('da_list'=> ['$ifNull' => ['$da_id_array',[]]]),
                    'pipeline'=>array(array(
                        '$match'=>array('$expr'=>array(
                            '$in'=>array('$_id','$$da_list')
                        )
                        )
                    )
                    ),
                    'as'           => 'da_array'
                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'let'=>array('da_tribunal_list'=> ['$ifNull' => ['$da_id_tribunal_array',[]]]),
                    'pipeline'=>array(array(
                        '$match'=>array('$expr'=>array(
                            '$in'=>array('$_id','$$da_tribunal_list')
                        )
                        )
                    )
                    ),
                    'as'           => 'da_tribunal_array'
                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'let'=>array('registrar_list'=> ['$ifNull' => ['$registrar_id_array',[]]]),
                    'pipeline'=>array(array(
                        '$match'=>array('$expr'=>array(
                            '$in'=>array('$_id','$$registrar_list')
                        )
                        )
                    )
                    ),
                    'as'           => 'registrar_array'
                )
            ),
            
            array(
                '$match' => array(
                    '$or'=>[
                      ['service.service_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
                      ['location.location_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
                    ])
            ),
            array('$skip' => intval($start)),
            array('$limit' => intval($limit)),
            array('$sort'=>array($col=>$dir)),
            array(
                '$project' => array(
                    '_id'               => 1,
                    'location_id'       => 1,
                    'service_id'        => 1,
                    'dps_id'            => 1,
                    'appellate_id'      => 1,
                    'reviewing_id'      => 1,
                    'dps_details'       => '$dps_details',
                    'appellate_details' => '$appellate_auth_details',
                    'reviewing_details' => '$reviewing_auth_details',
                    'service'           => '$service',
                    'location'          => '$location',
                    'da_array'          => 1,
                    'da_tribunal_array' => 1,
                    'registrar_array'   => 1,
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }
    /**
     * official_details_tot_search_rows
     *
     * @param mixed $keyword
     * @return void
     */
    public function official_details_tot_search_rows($keyword = NULL)
    {
        $collection = 'official_details_another';
        $operations = array(
            //array('$limit'=>intval($limit+$start)),
            //array('$skip'=>intval($start)),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_details'
                )
            ),
            array('$unwind' => '$dps_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'appellate_id',
                    'foreignField' => '_id',
                    'as'           => 'appellate_auth_details'
                )
            ),
            array('$unwind' => '$appellate_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'reviewing_id',
                    'foreignField' => '_id',
                    'as'           => 'reviewing_auth_details'
                ),
            ),
            array('$unwind' => '$reviewing_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'services',
                    'localField'   => 'service_id',
                    'foreignField' => '_id',
                    'as'           => 'service'
                ),
            ),
            array('$unwind' => '$service'),
            array(
                '$match' => array(
                    'service.service_name' =>array(
                        '$regex' => '^' . $keyword . '',
                        '$options' => 'i'
                    )
                )
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
                '$count' => 'pass'
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        $arr = (array) $data;
        if (isset($arr[0])) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }

    /**
     * official_details_filter_count
     *
     * @param mixed $temp
     * @return void
     */
    public function official_details_filter_count($temp = NULL)
    {
        return $this->total_rows();
    }
    /**
     * official_details_filter_agregate
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $col=NULL
     * @param mixed $dir=NULL
     * @return void
     */
    public function official_details_filter_agregate($limit, $start, $col = NULL, $dir = NULL)
    {
        $collection = 'official_details_another';
        if($dir=='desc'){
            $dir=-1;
        }else{
            $dir=1;
        }


        $operations = array(
            //array('$limit'=>intval($limit+$start)),
            //array('$skip'=>intval($start)),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_details'
                )
            ),
            array('$unwind' => '$dps_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'appellate_id',
                    'foreignField' => '_id',
                    'as'           => 'appellate_auth_details'
                )
            ),
            array('$unwind' => '$appellate_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'reviewing_id',
                    'foreignField' => '_id',
                    'as'           => 'reviewing_auth_details'
                ),
            ),
            array('$unwind' => '$reviewing_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'services',
                    'localField'   => 'service_id',
                    'foreignField' => '_id',
                    'as'           => 'service'
                ),
            ),
            array('$unwind' => '$service'),
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
                '$project' => array(
                    '_id'               => 1,
                    'location_id'       => 1,
                    'service_id'        => 1,
                    'dps_id'            => 1,
                    'appellate_id'      => 1,
                    'reviewing_id'      => 1,
                    'dps_details'       => '$dps_details',
                    'appellate_details' => '$appellate_auth_details',
                    'reviewing_details' => '$reviewing_auth_details',
                    'service'           => '$service',
                    'location'           => '$location',
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        pre($data);
        return (array)$data;
    }


    public function official_details_with_related($limit, $start, $col = NULL, $dir = NULL)
    {
        $collection = 'official_details_another';
        if($dir=='desc'){
            $dir=-1;
        }else{
            $dir=1;
        }


        $operations = array(
            //array('$limit'=>intval($limit+$start)),
            //array('$skip'=>intval($start)),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_details'
                )
            ),
            array('$unwind' => '$dps_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'appellate_id',
                    'foreignField' => '_id',
                    'as'           => 'appellate_auth_details'
                )
            ),
            array('$unwind' => '$appellate_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'reviewing_id',
                    'foreignField' => '_id',
                    'as'           => 'reviewing_auth_details'
                ),
            ),
            array('$unwind' => '$reviewing_auth_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'services',
                    'localField'   => 'service_id',
                    'foreignField' => '_id',
                    'as'           => 'service'
                ),
            ),
            array('$unwind' => '$service'),
            array(
                '$lookup'  => array(
                    'from'         => 'locations',
                    'localField'   => 'location_id',
                    'foreignField' => '_id',
                    'as'           => 'location'
                ),
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'let'=>array('da_list'=>'$da_id_array'),
                    'pipeline'=>array(array(
                        '$match'=>array('$expr'=>array(
                            '$in'=>array('$_id','$$da_list')
                        )
                        )
                    )
                    ),
                    'as'           => 'da_array'
                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'let'=>array('da_tribunal_list'=> ['$ifNull' => ['$da_id_tribunal_array',[]]]),
                    'pipeline'=>array(array(
                        '$match'=>array('$expr'=>array(
                            '$in'=>array('$_id','$$da_tribunal_list')
                        )
                        )
                    )
                    ),
                    'as'           => 'da_tribunal_array'
                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'let'=>array('registrar_list'=> ['$ifNull' => ['$registrar_id_array',[]]]),
                    'pipeline'=>array(array(
                        '$match'=>array('$expr'=>array(
                            '$in'=>array('$_id','$$registrar_list')
                        )
                        )
                    )
                    ),
                    'as'           => 'registrar_array'
                )
            ),
            array('$unwind' => '$location'),
            array('$skip' => intval($start)),
            array('$limit' => intval($limit)),
            array('$sort'=>array($col=>$dir)),
            array(
                '$project' => array(
                    '_id'               => 1,
                    'location_id'       => 1,
                    'service_id'        => 1,
                    'dps_id'            => 1,
                    'appellate_id'      => 1,
                    'reviewing_id'      => 1,
                    'dps_details'       => '$dps_details',
                    'appellate_details' => '$appellate_auth_details',
                    'reviewing_details' => '$reviewing_auth_details',
                    'service'           => '$service',
                    'location'          => '$location',
                    'da_array'          => 1,
                    'da_tribunal_array' => 1,
                    'registrar_array'   => 1
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }

    public function get_related($filter){

//        '$match'  => ['$expr' => ['$eq' => ['$service_id', new ObjectId($serviceId)]]],
//                '$match'  => ['$expr' => ['$eq' => ['$location_id', new ObjectId($locationId)]]],
        $operations = array(
            array(
                '$match'  => $filter
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_details'
                )
            ),
            array('$unwind' => '$dps_details'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'appellate_id',
                    'foreignField' => '_id',
                    'as'           => 'appellate_auth_details'
                )
            ),
            array('$unwind' => '$appellate_auth_details'),
            array(
                '$project' => array(
                    '_id'               => 0,
                    'dept_id'           => 1,
                    'service_id'        => 1,
                    'location_id'       => 1,
                    'dps_details'       => '$dps_details',
                    'appellate_details' => '$appellate_auth_details',
                )
            )
        );
        $data = $this->mongo_db->aggregate($this->table, $operations);
        //        pre($data);
        $data = (array)$data;
        if(!empty($data)){
            return $data[0];
        }else{
            return [];
        }
    }

    public function get_with_permission_array($filter){
        $operations = array(
            array(
                '$match'  => $filter
            ),
            array('$unwind' => '$da_id_array'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'da_id_array',
                    'foreignField' => '_id',
                    'as'           => 'da_user'
                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'dps_id',
                    'foreignField' => '_id',
                    'as'           => 'dps_user'
                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'appellate_id',
                    'foreignField' => '_id',
                    'as'           => 'appellate_user'
                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'reviewing_id',
                    'foreignField' => '_id',
                    'as'           => 'reviewing_user'
                )
            ),
//            array('$unwind' => '$user_role'),
            array(
                '$project' => array(
                    '_id'               => 0,
                    'dept_id'           => 1,
                    'service_id'        => 1,
                    'location_id'       => 1,
                    'dps_id'            => 1,
                    'appellate_id'      => 1,
                    'reviewing_id'      => 1,
                    'da_id_array'       => 1,
                    'da_user'           => 1,
                    'dps_user'          => 1,
                    'appellate_user'    => 1,
                    'reviewing_user'    => 1,
                )
            )
        );
        $data = $this->mongo_db->aggregate($this->table, $operations);
        //        pre($data);
        $data = (array)$data;
        return $data;
    }

    public function check_service_exits($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
             $this->mongo_db->where("_id",new ObjectId($obj));
            return $this->mongo_db->find_one("services");
           
        }else{
            return false;
        }
       
    }
    public function check_dept_exits($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            $this->mongo_db->where("_id",new ObjectId($obj));
           return $this->mongo_db->find_one("departments");
          
       }else{
           return false;
       }
    }

}
