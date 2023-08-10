<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cat_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('service_categories');
    }

    public function getcat($cat_id)
    {
        return $this->get_all(['cat_id' => $cat_id]);
    }

    public function get_all_categ($lang='as')
    {
        /* Only those categ. which have services and are ONLINE */
        /* All categories, except 'Unknown' */

        return $this->mongo_db->aggregate($this->table, array(
            array(
                '$match' => array(
                    'cat_name.en' => array(
                        '$ne' => 'Unknown'
                    )
                ),
            ),
            array(
                '$lookup' => array(
                    'from' => 'services',
                    'let' => array('cat_id' => '$cat_id'),     // service_categories' cat_id
                    'pipeline' => [
                        array(

                            '$match' => array(

                                '$expr' => array(
                                    '$and' => array(
                                        array('$eq' => ['$cat_id', '$$cat_id'] ),    
                                        array('$eq' => ['$online', TRUE] ),
                                        
                                    )
                                )
                               
                            )
                        ),
                    ],

                    'as' => 'services',
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$gt' => [array('$size' => '$services'), 0],
                    ),
                ),
            ),

            array(
                '$project' => array(
                    '_id' => 0,
                    'services' => 0,
                ),
            ),

            array(
                '$sort' => array(
                    'cat_name.en' => 1,
                ),
            ),
        ));
    }

    public function get_cat_by_obID($ob_id)
    {
        return $this->get_by_doc_id($ob_id);
    }
    public function add_new_cat($data)
    {
        $data['cat_id'] = $this->get_new_cat_id();

        return $this->insert($data);
    }

    public function get_all_cat($lang='en')
    {
        $this->mongo_db->order_by(array('cat_name.' . $lang => 'ASC'));

        return (array) $this->mongo_db->get($this->table);
    }

    public function update_cat($object_id, $data)
    {
        return $this->update($object_id, $data);
    }

    public function get_new_cat_id()
    {
        $data = $this->mongo_db->aggregate($this->table, array(
            array(
                '$sort' => array(
                    'cat_id' => -1,
                ),
            ),
            array(
                '$limit' => 1,
            ),
            array(
                '$project' => array(
                    '_id' => 0,
                    'cat_id' => 1,
                ),
            ),

        ));

        return $data->{'0'}->cat_id + 1;
    }

    public function check_category_exist($cat_name='')
    {
        // pre($cat_name);

        $filter =  array(
            '$or' => [
                ['cat_name.en' => ['$regex' => "^$cat_name$", '$options' => 'mi']],
                ['cat_name.as' => ['$regex' => "^$cat_name$", '$options' => 'mi']],
                ['cat_name.bn' => ['$regex' => "^$cat_name$", '$options' => 'mi']],
            ]
        );

        return $this->mongo_db->mongo_like_count($filter, $this->table);
    }

}
