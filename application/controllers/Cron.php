<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends frontend
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->input->is_cli_request())
			show_404();

		
	}

	public function index()
	{
        $params=array();
		modules::run('dashboard/stored_api/generate', $params);
	}
}
