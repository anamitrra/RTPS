<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Transferlog_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection("transfer_logs");
    } //End of __construct()
}
