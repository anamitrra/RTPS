<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Pfc_payment_history_model extends Mongo_model
{

    function __construct()
    {
        parent::__construct();
        parent::__construct();
        $this->set_collection("pfc_payment_history");
    }
}
/* End of file Pfc_payment_history_model.php */
/* Location: ./application/models/admin/Pfc_payment_history_model.php */
