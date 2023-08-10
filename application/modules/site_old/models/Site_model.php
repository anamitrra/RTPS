<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Site_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('services');
    }

    public function get_services($is_recent = false, $lang = 'as')
    {
        $this->mongo_db->select(array('service_name', 'seo_url'), array('_id'));

        if ($is_recent) {
            $filter = array('online' => true, 'is_new' => true);

            return $this->mongo_db->where($filter)->order_by(array('_id' => 'DESC'))->get($this->table);
        } else {
            $filter = array('online' => true);

            return $this->mongo_db->where($filter)->order_by(array('service_name.' . $lang => 'ASC'))->get($this->table);
        }
    }


    public function get_search($service_name, $lang = 'as')
    {
        return $this->mongo_db->aggregate($this->table, array(
            array(
                '$match' => [
                    'online' => true,
                    '$or' => [
                        ['service_name.en' => ['$regex' => $service_name . '', '$options' => 'mi']],
                        ['service_name.as' => ['$regex' => $service_name . '', '$options' => 'mi']],
                        ['service_name.bn' => ['$regex' => $service_name . '', '$options' => 'mi']],
                    ]
                ]
            ),
            array(
                '$lookup' => [
                    'from' => 'service_categories',
                    'localField' => 'cat_id',
                    'foreignField' => 'cat_id',
                    'as' => 'categ'
                ]
            ),
            array(
                '$lookup' => [
                    'from' => 'departments',
                    'localField' => 'department_id',
                    'foreignField' => 'department_id',
                    'as' => 'dept'
                ]
            ),
            array(
                '$project' => [
                    "service_name.$lang" => 1,
                    'seo_url' => 1,
                    'categ' => 1,
                    'dept' => 1,
                    '_id' => false
                ]
            ),
            array(
                '$sort' => ["service_name.$lang" => 1]
            )
        ));
    }

    public function get_services_list($lang = 'as')
    {
        $this->mongo_db->order_by(array('service_name.' . $lang => 'ASC'));
        $this->mongo_db->select(array('service_name.' . $lang), array('_id'));
        return $this->mongo_db->where(array('online' => true))->get($this->table);
    }

    public function get_services_by_categ($cat_id, $lang = "as")
    {
        return $this->mongo_db->aggregate($this->table, array(
            array(
                '$match' => ['cat_id' => $cat_id, 'online' => TRUE]
            ),
            array(
                '$lookup' => [
                    'from' => 'service_categories',
                    'localField' => 'cat_id',
                    'foreignField' => 'cat_id',
                    'as' => 'categ'
                ]
            ),
            array(
                '$lookup' => [
                    'from' => 'departments',
                    'localField' => 'department_id',
                    'foreignField' => 'department_id',
                    'as' => 'dept'
                ]
            ),
            array(
                '$project' => [
                    'service_id' => 1,
                    "service_name.$lang" => 1,
                    'seo_url' => 1,
                    'categ' => 1,
                    'dept' => 1,
                    '_id' => false
                ]
            ),
            array(
                '$sort' => ["service_name.$lang" => 1]
            )
        ));
    }

    public function get_popular_services($lang = 'as', $filter = array())
    {
        //$this->mongo_db->order_by(array('service_name.' . $lang => 'ASC'));
        //$this->mongo_db->select(array('service_name.' . $lang, 'seo_url' ), array('_id'));

        $this->mongo_db->select(array('service_name.' . $lang, 'seo_url', 'service_id', 'service_id_aadhar'), array('_id'));




        //       return $this->mongo_db->where_in('service_id', $filter)->get($this->table);


        return $this->get_all(
            array(
                '$or' => [
                    array('service_id' => array('$in' => $filter)),
                    array('service_id_aadhar' => array('$in' => $filter)),
                ]
            )
        );
    }


    public function add_new_service($data)
    {
        return $this->insert($data);
    }

    public function update_service_guidelines($service_ob_id, $data)
    {
        return $this->update_where(array("_id" => new MongoDB\BSON\ObjectId("$service_ob_id")), array("guide_lines" => $data));
    }
    public function update_service_requirements($service_ob_id, $data)
    {
        return $this->update_where(array("_id" => new MongoDB\BSON\ObjectId("$service_ob_id")), array("requirements" => $data));
    }

    public function update_service_notice($service_ob_id, $data)
    {
        return $this->update_where(array("_id" => new MongoDB\BSON\ObjectId("$service_ob_id")), array("notice" => $data));
    }

    public function update_service_documents($service_ob_id, $data)
    {
        return $this->mongo_db
            ->where(array("_id" => new MongoDB\BSON\ObjectId("$service_ob_id")))
            ->push('documents', $data)
            ->update($this->table);
    }

    public function get_all_services()
    {
        return $this->mongo_db->aggregate($this->table, array(
            array(
                '$lookup' => [
                    'from' => 'service_categories',
                    'localField' => 'cat_id',
                    'foreignField' => 'cat_id',
                    'as' => 'category'
                ]
            ),
            array(
                '$unwind' => '$category'
            ),
            array(
                '$lookup' => [
                    'from' => 'departments',
                    'localField' => 'department_id',
                    'foreignField' => 'department_id',
                    'as' => 'department'
                ]
            ),
            array(
                '$unwind' => '$department'
            ),
            array(
                '$project' => [
                    'service_id' => 1,
                    'service_name' => 1,
                    'online' => 1,
                    'department' => 1,
                    'category' => 1,
                    'is_new' => 1
                ]
            ),
            array(
                '$sort' => ["service_name.en" => 1]
            )
        ));
    }

    public function make_services_online($dept_id, $enable)
    {
        return $this->update_where(
            array("department_id" => $dept_id),
            array("online" => $enable)
        );
    }

    public function get_service_by_serviceID($service_id)
    {
        return  (array)$this->mongo_db->get_where($this->table, array('service_id' => $service_id));
    }

    public function get_service_by_objectID($ob_id)
    {
        return $this->get_by_doc_id($ob_id);
    }

    public function update_service_info($object_id, $data)
    {
        return $this->update($object_id, $data);
    }

    public function delete_service_doc($object_id, $doc_path)
    {
        return $this->mongo_db->command(array(
            'update' => $this->table,
            'updates' => [

                array(
                    'q' => array('_id' => new MongoDB\BSON\ObjectId("$object_id")),

                    'u' => array('$pull' => array('documents' => array('path' =>  $doc_path)))
                ),
            ],

        ));
    }

    public function delete_services_by_filter($filter)
    {
        return $this->delete_by_filter($filter);
    }

    public function update_service_by_dept($old_department_id = '', $data)
    {
        //    pre($data);
        return $this->update_where(array("department_id" => $old_department_id), array("department_id" => $data));
    }

    //Get Services by Kiosk type
    public function get_services_by_kiosk($kiosk_type, $lang = 'as')
    {
        return $this->mongo_db->aggregate($this->table, array(
            array(
                '$match' => ['kiosk_availability' => ['$in' => ['ALL', $kiosk_type]], 'online' => TRUE]
            ),
            array(
                '$lookup' => [
                    'from' => 'departments',
                    'let' => array('department_id' => '$department_id',),   // services fields
                    'pipeline' => array(
                        // departments fields
                        array(

                            '$match' => array(

                                '$expr' => array(
                                    '$and' => array(
                                        array('$eq' => ['$department_id', '$$department_id']),
                                        array('$eq' => ['$online', TRUE]),

                                    )
                                )

                            )
                        ),
                    ),
                    'as' => 'dept'
                ]
            ),
            array(
                '$unwind' => [
                    'path' => '$dept',
                    'preserveNullAndEmptyArrays' => true,
                ]
            ),
            array(
                '$project' => [
                    'service_id' => 1,
                    "service_name.$lang" => 1,
                    'seo_url' => 1,
                    'dept' => 1,
                    '_id' => false
                ]
            ),
            array(
                '$sort' => ["service_name.$lang" => 1]
            )
        ));
    }

    // Get PFC Locations
    public function get_pfc_locations()
    {
        // $this->mongo_db->order_by(array('PFC_Name' => 'ASC', 'PFC_Location' => 'ASC'));
        // return $this->mongo_db->get('pfc_locations');
        $this->mongo_db->order_by(array('District' => 'ASC', 'Sanctioned_PFC' => 'ASC'));
        return $this->mongo_db->get('pfc_list');
    }
    public function get_pfc_districts()
    {
        return $this->mongo_db->command(array(
            'distinct' => 'pfc_list',
            'key' =>  'District',
        ));
    }
    public function get_pfcs_for_district($district = '')
    {
        return $this->mongo_db->command(array(
            'find' => 'pfc_list',
            'filter' => ['District' => $district],
            'sort' => ['Sanctioned_PFC' => 1, 'District' => 1],
            'projection' => ['_id' => 0, 'Sl_No' => 0],
        ));
    }

    // Get All Basundhara services
    public function get_basundhara_services($lang = 'as')
    {
        return $this->mongo_db->aggregate($this->table, array(
            array(
                '$match' => ['service_id' => ['$in' => ["00014", "0005", "0006", "0007", "0008", "0009", "00010", "00011", "00012", "00013", "1410", "1207"]], 'online' => TRUE]
            ),
            array(
                '$lookup' => [
                    'from' => 'departments',
                    'let' => array('department_id' => '$department_id',),   // services fields
                    'pipeline' => array(
                        // departments fields
                        array(

                            '$match' => array(

                                '$expr' => array(
                                    '$and' => array(
                                        array('$eq' => ['$department_id', '$$department_id']),
                                        array('$eq' => ['$online', TRUE]),

                                    )
                                )

                            )
                        ),
                    ),
                    'as' => 'dept'
                ]
            ),
            array(
                '$unwind' => [
                    'path' => '$dept',
                    'preserveNullAndEmptyArrays' => true,
                ]
            ),
            array(
                '$project' => [
                    'service_id' => 1,
                    "service_name" => 1,
                    'seo_url' => 1,
                    'dept' => 1,
                    '_id' => false
                ]
            ),
            array(
                '$sort' => ["service_name.$lang" => 1]
            )
        ));
    }


        // Get All Utility services
        public function get_utility_services($lang = 'as')
        {
            return $this->mongo_db->aggregate($this->table, array(
                array(
                    '$match' => ['utility' => TRUE]
                ),
                array(
                    '$lookup' => [
                        'from' => 'departments',
                        'let' => array('department_id' => '$department_id',),   // services fields
                        'pipeline' => array(
                            // departments fields
                            array(
    
                                '$match' => array(
    
                                    '$expr' => array(
                                        '$and' => array(
                                            array('$eq' => ['$department_id', '$$department_id']),
                                            array('$eq' => ['$online', TRUE]),
    
                                        )
                                    )
    
                                )
                            ),
                        ),
                        'as' => 'dept'
                    ]
                ),
                array(
                    '$unwind' => [
                        'path' => '$dept',
                        'preserveNullAndEmptyArrays' => true,
                    ]
                ),
                array(
                    '$project' => [
                        'service_id' => 1,
                        "service_name" => 1,
                        'seo_url' => 1,
                        'dept' => 1,
                        '_id' => false
                    ]
                ),
                array(
                    '$sort' => ["service_name.$lang" => 1]
                )
            ));
        }
}
