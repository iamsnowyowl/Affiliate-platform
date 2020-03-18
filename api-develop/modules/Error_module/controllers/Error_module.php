<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error_module extends MX_Controller {
	public $error;
	public $error_code;

	public function __construct()
	{
		parent::__construct();
		$this->error_code = 500;
		$this->error = array();
		$this->error_extra = array();
	}

	public function get_error()
	{
		return $this->error;
	}

	public function set_error($error)
	{
		$this->error = $error;
	}

	public function get_error_code()
	{
		return $this->error_code;
	}

	public function set_error_code($error_code)
	{
		$this->error_code = $error_code;
	}

	public function get_error_extra()
	{
		return $this->error_extra;
	}

	public function set_error_extra($error_extra)
	{
		$this->error_extra = $error_extra;
	}

}