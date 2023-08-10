<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Feedback_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('feedbacks');
    }
}
