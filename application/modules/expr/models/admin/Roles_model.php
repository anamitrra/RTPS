<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Roles_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("roles");
    }

    /**
     * roles_search_rows
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $keyword
     * @param mixed $col
     * @param mixed $dir
     * @return void
     */
    public function roles_search_rows($limit, $start, $keyword, $col, $dir)
    {
        $temp = array(
            '$or' => [
                ['role_name' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['slug' => ['$regex' => '^' . $keyword . '', '$options' => 'i']]
            ]
        );
        //print_r($temp);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }


    /**
     * roles_tot_search_rows
     *
     * @param mixed $keyword
     * @return void
     */
    public function roles_tot_search_rows($keyword)
    {
        $temp = array(
            '$or' => [
                ['role_name' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['slug' => ['$regex' => '^' . $keyword . '', '$options' => 'i']]
            ]
        );
        //print_r($temp);
        return $this->tot_search_rows($temp);
    }

    public function get_all_access_list()
    {
        $this->set_collection("access_list");
        return $this->get_all([]);
    }

    public function get_role_info($doc_id)
    {

        $collection = 'roles';
        $operations = array(
            array(
                '$match' => array('$expr' => array(
                    '$eq' => array('$_id', new ObjectId($doc_id))
                ))
            ),
            // array(
            //     '$lookup' => array(
            //         'from' => 'access_list',
            //         'let' => array('permissions' => '$permissions'),
            //         'pipeline' => array(array(
            //             '$match' => array('$expr' => array(
            //                 '$in' => array('$_id', '$$permissions')
            //             )
            //             )
            //         )
            //         ),
            //         'as' => 'permissions_array'
            //     )
            // ),
            array(
                '$project' => array(
                    '_id' => 1,
                    'role_name' => 1,
                    'slug' => 1,
                    // 'permissions_array' => 1,
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        $data = (array)$data;
        //        pre($data);
        return $data[0];
    }

    public function get_role_wise_user_list($filter)
    {
        $operation = array(
            array(
                '$match' => [
                    '$and' => [$filter]
                ]
            ),
            array(
                '$lookup' => array(
                    'from' => 'users',
                    'localField' => '_id',
                    'foreignField' => 'roleId',
                    'as' => 'users',
                )
            ),
//          array('$unwind' => '$users'),
            array('$project' => array(
                '_id' => 0,
                'slug' => 1,
                'role_name' => 1,
                'permissions' => 1,
                'users' => '$users',
            ))
        );
        //pre($operation);
        return $this->mongo_db->aggregate($this->table, $operation);
    }

    public function get_role_wise_permission($filter)
    {
        $collection = 'roles';
        $operations = array(
            array(
                '$match' => $filter
            ),
            array(
                '$lookup' => array(
                    'from' => 'access_list',
                    'let' => array('permissions' => '$permissions'),
                    'pipeline' => array(array(
                        '$match' => array('$expr' => array(
                            '$in' => array('$_id', '$$permissions')
                        )
                        )
                    )
                    ),
                    'as' => 'permissions_array'
                )
            ),
            array('$unwind' => '$permissions_array'),
            array('$group' => [
                '_id' => ['role_slug' => '$slug'],
                'permissions' => ['$addToSet' => '$permissions_array.slug']
            ]),
            array(
                '$sort' => ['permissions' => 1]
            )

        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        //$data = (array)$data;
        //        pre($data);
        return $data;
    }
    public function get_by_slug($slug){
      $this->mongo_db->select(array("_id"));
      $this->mongo_db->where('slug', $slug);
      return $this->mongo_db->find_one('roles');
    }
}
