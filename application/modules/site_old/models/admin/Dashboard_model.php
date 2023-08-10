<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dashboard_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection('settings');
    }

    public function update_last_updated_time($update_date)
    {
        $this->update_where(
            array("name" => "footer_new"),
            array("last_update.date" => $update_date)
        );
    }

    public function daily_count()
    {
        $this->set_collection('user_portal');

        return (array) $this->mongo_db->aggregate($this->table, array(
            array(
                '$match' => array(
                    'mongo_date' => ['$gte' => new MongoDB\BSON\UTCDateTime(strtotime("-1 week") * 1000)]
                )
            ),
            array(
                '$addFields' => array(
                    'created_at' => array(
                        '$dateToString' => [
                            'format' => '%Y-%m-%d',
                            'date' => '$mongo_date'
                        ]
                    )
                )
            ),
            array(
                '$group' => array(
                    '_id' => '$created_at',
                    'count' => array('$sum' => 1),
                    'f_date' => array('$first' => ['$toDate' => '$created_at']),
                )
            ),
            array('$sort' => array('f_date' => -1)),
            array(
                '$project' => array(
                    'date' => '$_id',
                    'count' => 1,
                    '_id' => 0
                )
            ),

        ));
    }
    public function get_settings($name = "")
    {
        $this->mongo_db->select(array(), array('_id'));
        $data = $this->get_where('name', $name);
        if (count((array) $data) > 0 && !empty($data)) {
            return $data->{0};
        } else {
            return false;
        }
    }

    public function update_portal_alert($data)
    {
        $this->update_where(
            array('name' => 'footer_new'),
            array('site_alert_model.body' => $data['body'],  'site_alert_model.enable' => $data['enable'])
        );
    }

    public function request_response()
    {
        $DB = $this->load->database('rtps_prod_pgsql', TRUE);

        $sql = "select date(time_stamp) as date, count(*) as count from schm_sp.request_response where date(time_stamp) >= (CURRENT_DATE::date - 7) group by date(time_stamp) order by 1 desc";
        $query = $DB->query($sql);
        return $query->result();
    }
}
