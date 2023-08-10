
<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Digilocker_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        // $this->set_collection("digilocker_uri");
    }//End of __construct()
}