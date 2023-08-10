<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_scheduler extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (! $this->input->is_cli_request())
			show_404();

		$this->load->library('remail');

	}

	public function index()
	{
		// Huh?
		show_404();
	}

	public function send_queue()
	{
		$this->remail->send_queue();
	}

	public function retry_queue()
	{
		$this->remail->retry_queue();
	}
}