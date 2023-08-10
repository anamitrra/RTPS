
<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Verifycertificate extends Frontend 
{

  //put your code here
  public function __construct()
  {
    parent::__construct();

  }

  public function index($id)
  {
    $application_data = (array)$this->mongo_db->where(['_id'=> new ObjectId($id)])->get('sp_applications');
    $this->load->view("office/certificate_verify_view", array('data' => $application_data));
  }
}