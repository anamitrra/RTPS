<?php


class Services_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->mongo_db->switch_db("grievances");
        $this->set_collection('services');
    }

    // get all
    function all()
    {
        return $this->mongo_db->get("services");
    }

    function all_dept()
    {
        return $this->mongo_db->get("departments");
    }

    // delete data
    function delete_service($id)
    {
        $this->mongo_db->where('_id', $id);
        $this->mongo_db->delete("services");
    }

    // get collection data
    public function get_service($id)
    {
        $this->mongo_db->where("_id", $id);
        return $this->mongo_db->get("services");
    }

    public function get_department_by_service_id($serviceId){
        $operations = array(
            array(
                '$match'  => array(
                    '$expr'=>array(
                        '$eq' => array('$service_id',$serviceId)
                    )

                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'departments',
                    'localField'   => 'department_id',
                    'foreignField' => 'department_id',
                    'as'           => 'department'
                )
            ),
            array('$unwind' => '$department'),
            array(
                '$project' => array(
                    '_id'           => 0,
                    'service_id'    => 1,
                    'department_id' => 1,
                    'service_name'  => 1,
                    'department'    => '$department',
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($this->table, $operations);
//        pre($data);
        $data = (array)$data;
        //        pre($data);
        if(empty($data)){
            return [];
        }
        return $data[0];
    }

    public function get_with_related_dept($filter = []){

        $operations = array(
            array(
                '$match'  => (object)$filter
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'departments',
                    'localField'   => 'department_id',
                    'foreignField' => 'department_id',
                    'as'           => 'department'
                )
            ),
            array('$unwind' => '$department'),
            array(
                '$project' => array(
                    '_id'           => 0,
                    'service_id'    => 1,
                    'department_code' => 1,
                    'service_name'  => 1,
                    'department'    => '$department',
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($this->table, $operations);
//        pre($data);
        $data = (array)$data;
        //        pre($data);
        return $data;
    }
}