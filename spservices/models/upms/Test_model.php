<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->config = $this->config->item('mongo_db');
        $this->no_auth = trim($this->config[$this->activate]['no_auth']);
        $this->hostname = trim($this->config[$this->activate]['hostname']);
        $this->port = trim($this->config[$this->activate]['port']);
        $this->username = trim($this->config[$this->activate]['username']);
        $this->password = trim($this->config[$this->activate]['password']);
        $this->database = trim($this->config[$this->activate]['database']);
    }//End of __construct()
    
    public function update() {
        $this->config = $this->CI->config->item('mongo_db');
        
        $dns = "mongodb://{$this->hostname}:{$this->port}/{$this->database}";
        if (isset($this->config[$this->activate]['no_auth']) == TRUE && $this->config[$this->activate]['no_auth'] == TRUE) {
            $options = array();
        } else {
            $options = array('username' => $this->username, 'password' => $this->password);
        }
        //$this->connect = $this->CI->db = new MongoDB\Driver\Manager($dns, $options);
        $this->connect = $this->db = new MongoDB\Driver\Manager($dns, $options);
    }//End of update()
}//End of Levels_model