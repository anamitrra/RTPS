<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Servicewise_applications_model extends Mongo_model
{
    public function __construct()
    {
        $this->set_collection('servicewise_applications');
    }

    public function upsert_servicewise_applications($data = array())
    {
        foreach ($data as $service) {
            $this->mongo_db->command(array(
                'update' => $this->table,
                'updates' => [
                    array(
                        'q' => array('service_id' => $service->_id),
                        'u' => array(
                            'service_id' => $service->_id,
                            'service_name' => $service->service_name,
                            'total_received' => $service->total_received,
                            'delivered' => $service->delivered,
                            'rejected' => $service->rejected,
                            'rit' => $service->rit,
                            'pit' => $service->pit,
                            'pending' => $service->pending,
                            'pbt' => $service->pbt,
                            'timely_delivered' => $service->timely_delivered,
                            'department_id' => $service->department_id,
                            'min' => $service->min,
                            'median' => $service->median,
                            'service_timeline' => $service->service_timeline,
                            'minimum' => $service->minimum,
                            'maximum' => $service->maximum,
                            'dept'    => $service->dept_name,
                            'paa'     => $service->paa,
                            'parent_department_id'     => $service->parent_department_id,
                        ),
                        'upsert' => true,
                        'multi' => false
                    )

                ],

            ));
        }
    }
}
