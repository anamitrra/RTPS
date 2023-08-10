<?php

class Settings_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->set_collection("settings");
    }

    public function get_settings($name='')
    {
       // var_dump($name);die;
        $this->mongo_db->select(array(), array('_id'));
        $data = $this->get_where('name', $name);
        
        if (count((array) $data) > 0 && !empty($data)) {
            return $data->{0};
        } else {
            return false;
        }
    }

    public function get_all_cat()
    {
        $this->mongo_db->select(['categories.title', 'categories.short_name'], ['_id']);
        return (array) $this->get_all(['name' => 'documents']);
    }

    public function get_all_video_cat()
    {
        $this->mongo_db->select(['categories.title', 'categories.short_name'], ['_id']);
        return (array) $this->get_all(['name' => 'videos']);

    }

    public function update_cat($short_name)
    {
        // return $this->mongo_db->where(array('name' => 'documents'))->push('categories', $data)->update($this->table);

        return $this->mongo_db->command(array(
            'update' => $this->table,
            'updates' => [

                array(
                    'q' => array('name' => 'documents'),
                    'u' => array('$addToSet' => array('short_name' => $short_name)),
                ),
            ],

        ));
    }


    public function add_doc_category($data)
    {
        
        return $this->mongo_db->command(array(
            'update' => $this->table,
            'updates' => [

                array(
                    'q' => array('name' => 'documents'),
                    'u' => array('$addToSet' => array('categories' => $data)),
                ),
            ],

        ));
    }
    public function add_video_category($data)
    {
        
        return $this->mongo_db->command(array(
            'update' => $this->table,
            'updates' => [

                array(
                    'q' => array('name' => 'videos'),
                    'u' => array('$addToSet' => array('categories' => $data)),
                ),
            ],

        ));
    }

    public function update_doc_category($old_cat='', $data)
    {
        return $this->mongo_db->command(array(
            'update' => $this->table,
            'updates' => [

                array(
                    'q' => array('name' => 'documents'),
                    'u' => array(
                        '$set' => array(
                            'categories.$[elem].title.en'=>$data['title']['en'],
                            'categories.$[elem].title.as'=>$data['title']['as'],
                            'categories.$[elem].title.bn'=>$data['title']['bn'],
                            'categories.$[elem].short_name'=>$data['short_name']
                        )
                    ),
                    'arrayFilters' => array(
                        array(
                            'elem.short_name'=>$old_cat
                        )
                    )
                ),
            ],

        ));

    }
    public function update_video_category($old_cat='', $data)
    {
        return $this->mongo_db->command(array(
            'update' => $this->table,
            'updates' => [

                array(
                    'q' => array('name' => 'videos'),
                    'u' => array(
                        '$set' => array(
                            'categories.$[elem].title.en'=>$data['title']['en'],
                            'categories.$[elem].title.as'=>$data['title']['as'],
                            'categories.$[elem].title.bn'=>$data['title']['bn'],
                            'categories.$[elem].short_name'=>$data['short_name']
                        )
                    ),
                    'arrayFilters' => array(
                        array(
                            'elem.short_name'=>$old_cat
                        )
                    )
                ),
            ],

        ));

    }

    public function get_doc_category($short_name)
    {
        return $this->mongo_db->aggregate($this->table, array(
            array(
                '$match' => array('name' => 'documents')
            ),
            array(
                '$project' => array(
                    'categories' => array(
                        '$filter'=> array(
                            "input"=> '$$CURRENT.categories',
                            'as'=>"category",
                            'cond'=>array(
                                '$eq'=>array(
                                    '$$category.short_name',"$short_name"
                                )
                            )
                        )
                    ),
                    '_id' => 0
                )
            )
        ));
    }
    public function get_video_category($short_name)
    {
        return $this->mongo_db->aggregate($this->table, array(
            array(
                '$match' => array('name' => 'videos')
            ),
            array(
                '$project' => array(
                    'categories' => array(
                        '$filter'=> array(
                            "input"=> '$$CURRENT.categories',
                            'as'=>"category",
                            'cond'=>array(
                                '$eq'=>array(
                                    '$$category.short_name',"$short_name"
                                )
                            )
                        )
                    ),
                    '_id' => 0
                )
            )
        ));
    }

    public function count_doc_category($data)
    {
      return $this->mongo_db->mongo_like_count(
          array('name' => 'documents', "categories.short_name" => ['$regex' => '^'.$data.'$', '$options' => 'i']), 
          $this->table 
        );
    }
    public function count_video_category($data)
    {
      return $this->mongo_db->mongo_like_count(
          array('name' => 'videos', "categories.short_name" => ['$regex' => '^'.$data.'$', '$options' => 'i']), 
          $this->table 
        );
    }

    public function delete_doc_category($short_name)
    {
        return $this->mongo_db->command(array(
            'update' => $this->table,
            'updates' => [

                array(
                    'q' => array('name' => 'documents'),
                    'u' => array('$pull' => array('categories' => array('short_name' =>  $short_name) ))
                ),
            ],

        ));
    }
    public function delete_video_category($short_name)
    {
        return $this->mongo_db->command(array(
            'update' => $this->table,
            'updates' => [

                array(
                    'q' => array('name' => 'videos'),
                    'u' => array('$pull' => array('categories' => array('short_name' =>  $short_name) ))
                ),
            ],

        ));
    }

    public function update_visitors_count()
    {
        $this->mongo_db->where(array('name' => 'footer_new'))
            ->inc(array('imp_links.visitor.count' => 1))
            ->update($this->table);

    }

    public function screen()
    {
        $data = $this->get_where('name', 'screen_reader');
        return $data;
    }
    public function access()
    {
        $data = $this->get_where('name', 'accessibility');
        return $data;
    }

}
