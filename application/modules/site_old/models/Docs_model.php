<?php

class Docs_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
    */
    function __construct()
    {
        parent::__construct();
        $this->set_collection('documents');
    }

    public function get_docs_by_category($category, $lang='as')
    {
        $this->mongo_db->select([], ['_id']);
        $this->mongo_db->order_by(array('name.' . $lang => 'ASC'));
        return (array)$this->get_all(array('category' => $category)) ;
    }
    
    public function upload_document($ob_id=NULL, $data)
    {
        if ($ob_id) {
            
            // update the doc
            return $this->update($ob_id, $data);
        }

        // insert new doc
        return $this->insert($data);
    }

    public function get_all_docs()
    {
        return (array)$this->get_all([]) ;
    }

    public function update_doc_info($ob_id, $data)
    {
        return $this->update_where(array("_id" => new MongoDB\BSON\ObjectId("$ob_id")), $data);
    }

    public function del_doc()
    {
        return $this->mongo_db->where("_id", new MongoDB\BSON\ObjectId($id));
    }

    public function update_docs_by_categ($cat_old='', $data)
    {
        return $this->update_where(array("category" => $cat_old), $data);

    }
}