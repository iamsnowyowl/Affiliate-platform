<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http'));
	}

	public function index()
	{
		response(403, "Forbidden");
	}
}