<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kuks extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_kuk_detail($kuk_id)
	{
		$this->my_parameter = $this->parameter;

		// if (!modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->kuk_detail($kuk_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_kuk_list() 
	{
		$this->my_parameter = $this->parameter;
		
		// if (!modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->kuk_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function kuk_detail($kuk_id)
	{
		$kuks = modules::run("Kuk_module/get_kuk_by_id", $this->my_parameter, $kuk_id);

		$this->load->helper("url");

		if ($kuks === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK",
							"reason" => "KukNotFound"
						),
					)
				)
			);
		}

		return array("data" => $kuks);
	}

	protected function kuk_list()
	{
		return modules::run("Kuk_module/get_kuk_list", $this->my_parameter);
	}

	public function get_kuk_count() 
	{
		$this->my_parameter = $this->parameter;

		// if (!modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->kuk_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function kuk_count()
	{
		$count = modules::run("Kuk_module/get_kuk_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create kuk
	public function create_kuk_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_kuk();
	}

	public function create_kuk_session()
	{
		// if (!modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_kuk($created_by);
	}

	protected function create_kuk($created_by = 0)
	{
		$kuk_id = modules::run("Kuk_module/create_kuk", $this->my_parameter, $created_by);
			
		if ($kuk_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("kuk_id" => $kuk_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_kuk_by_id($kuk_id)
	{
		$kuk = modules::run("Kuk_module/get_kuk_by_id", array(), $kuk_id);
		
		// if (!(modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE) && $kuk->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "KUK_UPDATE");
		
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_kuk($kuk_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_kuk($kuk, $modified_by)
	{
		return modules::run("Kuk_module/update_kuk_by_id", $kuk, $this->my_parameter, $modified_by);
	}

	public function delete_soft_kuk_by_id($kuk_id)
	{
		$kuk = modules::run("Kuk_module/get_kuk_by_id", array(), $kuk_id);

		// if (!(modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "KUK_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_kuk($kuk_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_kuk($kuk_id, $modified_by = 0)
	{
		return modules::run("Kuk_module/delete_soft_kuk_by_id", $kuk_id, $modified_by);
	}

	public function delete_hard_kuk_by_id($kuk_id, $confirmation)
	{
		$kuk = modules::run("Kuk_module/get_kuk_by_id", array(), $kuk_id);

		// if (!(modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "KUK_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_kuk($kuk_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_kuk($kuk_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Kuk_module/delete_hard_kuk_by_id", $kuk_id, $confirmation, $modified_by);
	}
}


