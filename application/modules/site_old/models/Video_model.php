<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Video_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('videos');
    }

    public function get_videos($lang = 'as')
    {
        return $this->mongo_db->aggregate($this->table, array(

            array(
                '$sort' => ['category.name.' . $lang => 1, 'name.' . $lang => 1],
            ),
            array(
                '$group' => ['_id' => '$category.name.' . $lang, 'videos' => ['$push' => '$$ROOT']],
            ),

        ));
    }

    public function get_video_categories($lang = 'as')
    {
        $this->mongo_db->order_by(array('category.name.' . $lang => 'ASC'));
        return $this->mongo_db->command(array('distinct' => $this->table, 'key' => 'category.name.' . $lang));

    }

    public function get_videos_by_category($category='', $lang='as')
    {
        $this->mongo_db->select([], ['_id']);
        $this->mongo_db->order_by(array('name.' . $lang => 'ASC'));
        return (array) $this->get_all(array('category' => $category));
    }

    public function get_all_videos()
    {
        return (array)$this->get_all([]) ;
    }

    public function update_video_by_categ($cat_old='', $data)
    {
        return $this->update_where(array("category" => $cat_old), $data);

    }
}
