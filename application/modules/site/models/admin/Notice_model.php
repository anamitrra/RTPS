<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notice_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('settings');
    }


    // Add notice new
    public function add_new_notice($data)
    {

        $this->mongo_db->command(array(
            'update' => 'settings',
            'updates' => [
                array(
                    'q' => array('name' => "footer"),
                    'u' => array('$push' => array(
                        'notice.notices' => array(
                            '$each' => [$data],
                            '$position' => 0
                        )
                    ))
                ),
            ],
        ));
    }



    // update notice new
    public function update_notice($edit_url, $data, $index)
    {
        $this->mongo_db->command(array(
            'update' => 'settings',
            'updates' => [
                array(
                    'q' => array('name' => "footer"),
                    'u' => array('$set' => array(
                        "notice.notices.$index" => $data,

                    ))
                ),
            ],
        ));
    }

    // Delete notice new
    public function delete_notice($notice_url)
    {
        // query
        // db.footer.updateMany({name:"home_new"}, {$pull:{"notice.notices":{notice_url:"ko"}}})

        $this->mongo_db->command(array(
            'update' => 'settings',
            'updates' => [
                array(
                    'q' => array('name' => "footer"),
                    'u' => array('$pull' => array(
                        'notice.notices' => array(
                            'link.url' => $notice_url,
                        )
                    ))
                ),
            ],
        ));
    }



    // Get all notice new
    public function get_all_notices()
    {
        $this->mongo_db->where("name", "footer");
        $res = $this->mongo_db->get($this->table);
        // pre($res);

        // $res = json_decode(json_encode($res), true);
        if (count($res->{'0'}->notice->notices)) {
            // return $res[0]['notice']['notices'];
            return $res->{'0'}->notice->notices;
        } else {
            return false;
        }
    }

    // Get a single notice new
    public function get_single_notice($index)
    {
        $this->mongo_db->where("name", "footer");
        $res = $this->mongo_db->get($this->table);
        // $res = json_decode(json_encode($res), true);
        if (count($res->{'0'}->notice->notices)) {
            // return $res[0]['notice']['notices'][0];
            return $res->{'0'}->notice->notices[$index];
        } else {
            return false;
        }
    }
}
