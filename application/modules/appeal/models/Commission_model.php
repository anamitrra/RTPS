<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Commission_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('commission');
    }


    public function get_active_commissionar_name(){
        // $data=$this->first_where([]);
        // pre( $data);

        $operations = array(
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'reviewing_authority',
                    'foreignField' => '_id',
                    'as'           => 'reviewing_authority_user'
                )
            ),
            array('$unwind' => '$reviewing_authority_user'),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'registrar',
                    'foreignField' => '_id',
                    'as'           => 'registrar_user'
                )
            ),
            array('$unwind' => '$registrar_user'),
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($this->table, $operations);
        //        pre($data);
        $data = (array)$data;
       
        return $data[0];

    }
}