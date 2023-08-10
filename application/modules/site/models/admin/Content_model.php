<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Content_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection('settings');
    }

    public function get_about()
    {
        $data = $this->get_where('name', 'about');
        return $data;
    }

    public function update_about($data)
    {

        $this->update_where(
            array("name" => "about"),
            array("content" => $data)
        );
    }
    public function get_faq()
    {
        $data = $this->get_where('name', 'faq');
        return $data;
    }

    public function update_faq($data)
    {
        $this->update_where(
            array("name" => "faq"),
            array("content" => $data)
        );
    }
    public function get_contact()
    {
        $data = $this->get_where('name', 'contact');
        return $data;
    }

    public function update_contact($data)
    {
        $this->update_where(
            array("name" => "contact"),
            array("content" => $data)
        );
    }
    public function get_access()
    {
        $data = $this->get_where('name', 'accessibility');
        return $data;
    }
    public function update_access($data)
    {
        $this->update_where(
            array("name" => "accessibility"),
            array("content" => $data)
        );
    }

    public function get_banners()
    {
        // $this->get_selected(['banners'], ['_id']);
        $this->mongo_db->select(array('banners'), array('_id'));
        $data = $this->get_where('name', 'index');
        //pre($data);
        return $data;
    }

    public function delete_banners($doc_path, $lang_arr)
    {
        // pre([$doc_path, $lang_arr]);
        return $this->mongo_db->command(array(
            'update' => $this->table,
            'updates' => [

                array(
                    'q' => array('name' => 'index'),
                    'u' => array('$pull' => array('banners.' . $lang_arr => $doc_path))
                ),
            ],

        ));
    }
    public function update_banners($lang_arr, $path, $position)
    {
        // pre([$lang_arr, $path, $position]);

        return $this->mongo_db->command(array(
            'update' => $this->table,
            'updates' => [

                array(
                    'q' => array('name' => 'index'),
                    'u' => array('$push' => array(
                        'banners.' . $lang_arr => array(
                            '$each' => [$path],
                            '$position' => $position
                        )
                    ))
                ),
            ],

        ));
    }



    public function site_text()
    {

        $query = (array) $this->mongo_db->select(array('name'), ['_id'])->get($this->table);

        return $query;
    }

    public function get_text($name = '')
    {
        // $this->mongo_db->select(array('_id'),array(), );
        // $datax = $this->get_where('name', $name);
        // pre($data);


        $this->mongo_db->select(array(), array('_id'));
        $data = $this->get_where('name', $name);
        //     return array('n'=>$datax,
        //                  'a'=>$data                
        // );
        //  pre($data);
        if (count((array) $data) > 0 && !empty($data)) {
            return $data->{0};
        } else {
            return false;
        }
    }

    public function update_text($name, $adata)
    {
        // pre([$name, $adata]);
        // return $this->update_where(
        //     array("name" => $name),
        //     $adata
        // );

        return $this->mongo_db->command(array(
            'update' => $this->table,
            'updates' => [
                array(
                    'q' => array('name' => $name),
                    'u' => $adata,
                    'upsert' => false,
                    'multi' => false,
                ),
            ],

        ));
    }


    public function add_text($data)
    {

        return $this->insert($data);
    }

    public function get_name($chk_name)
    {
        return (array)$this->mongo_db->get_where($this->table, array('name' => $chk_name));
    }
}
