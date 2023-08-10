<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sms_scheduler extends frontend
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->input->is_cli_request())
			show_404();

		$this->load->library('sms');
	}

	public function index()
	{
		show_404();
	}

	public function send_queue()
	{
		$this->sms->send_queue();
	}
}
