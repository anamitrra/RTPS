
<?php
class CI_Mongo extends Mongo
{
    var $db;

    function CI_Mongo()
    {   
        // Fetch CodeIgniter instance
        $ci = get_instance();
        // Load Mongo configuration file
        $ci->load->config('mongo');

        // Fetch Mongo server and database configuration
        $server = $ci->config->item('localhost:27017');
        $dbname = $ci->config->item('portal');

        // Initialise Mongo
        if ($server)
        {
            parent::__construct($server);
        }
        else
        {
            parent::__construct();
        }
        $this->db = $this->$dbname;
    }
}