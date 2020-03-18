<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kuk_sections extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_kuk_section_detail($kuk_section_id)
	{
		$this->my_parameter = $this->parameter;

		// if (!modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->kuk_section_detail($kuk_section_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_kuk_section_list() 
	{
		$this->my_parameter = $this->parameter;
		
		// if (!modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->kuk_section_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_kuk_kuk_section_detail($kuk_id, $kuk_section_id)
	{
		$this->my_parameter = $this->parameter;

		// if (!modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$this->my_parameter["kuk_id"] = $kuk_id;

		$data = $this->kuk_section_detail($kuk_section_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_kuk_kuk_section_list($kuk_id) 
	{
		$this->my_parameter = $this->parameter;
		
		// if (!modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$this->my_parameter["kuk_id"] = $kuk_id;
		
		$data = $this->kuk_section_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function kuk_section_detail($kuk_section_id)
	{
		$kuk_sections = modules::run("Kuk_section_module/get_kuk_section_by_id", $this->my_parameter, $kuk_section_id);

		$this->load->helper("url");

		if ($kuk_sections === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK_SECTION",
							"reason" => "Kuk_sectionNotFound"
						),
					)
				)
			);
		}

		return array("data" => $kuk_sections);
	}

	protected function kuk_section_list()
	{
		return modules::run("Kuk_section_module/get_kuk_section_list", $this->my_parameter);
	}

	public function get_kuk_section_count() 
	{
		$this->my_parameter = $this->parameter;

		// if (!modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->kuk_section_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_kuk_kuk_section_count($kuk_id) 
	{
		$this->my_parameter = $this->parameter;

		// if (!modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"];

		$this->my_parameter["kuk_id"] = $kuk_id;

		$data = $this->kuk_section_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function kuk_section_count()
	{
		$count = modules::run("Kuk_section_module/get_kuk_section_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create kuk_section
	public function create_kuk_kuk_section_public($kuk_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter["kuk_id"] = $kuk_id;
		
		$this->create_kuk_section();
	}

	public function create_kuk_kuk_section_session($kuk_id)
	{
		// if (!modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_CREATE");
		
		$this->my_parameter = $this->parameter;
		$this->my_parameter["kuk_id"] = $kuk_id;

		$created_by = $this->userdata['user_id'];

		$this->create_kuk_section($created_by);
	}

	protected function create_kuk_section($created_by = 0)
	{
		$kuk_section_id = modules::run("Kuk_section_module/create_kuk_section", $this->my_parameter, $created_by);
			
		if ($kuk_section_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK_SECTION",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("kuk_section_id" => $kuk_section_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_kuk_kuk_section_by_id($kuk_id, $kuk_section_id)
	{
		$kuk_section = modules::run("Kuk_section_module/get_kuk_section_by_id", array("kuk_id" => $kuk_id), $kuk_section_id);
		
		// if (!(modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE) && $kuk_section->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "KUK_UPDATE");
		
		$this->my_parameter = $this->parameter;

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_kuk_section($kuk_id, $kuk_section_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK_SECTION",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_kuk_section($kuk_id, $kuk_section_id, $modified_by)
	{
		return modules::run("Kuk_section_module/update_kuk_section_by_id", $kuk_id, $kuk_section_id, $this->my_parameter, $modified_by);
	}

	public function delete_soft_kuk_kuk_section_by_id($kuk_id, $kuk_section_id)
	{
		$kuk_section = modules::run("Kuk_section_module/get_kuk_section_by_id", array("kuk_id" => $kuk_id), $kuk_section_id);

		// if (!(modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "KUK_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_kuk_section($kuk_id, $kuk_section_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK_SECTION",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_kuk_section($kuk_id, $kuk_section_id, $modified_by = 0)
	{
		return modules::run("Kuk_section_module/delete_soft_kuk_section_by_id", $kuk_id, $kuk_section_id, $modified_by);
	}

	public function delete_hard_kuk_kuk_section_by_id($kuk_id, $kuk_section_id, $confirmation)
	{
		$kuk_section = modules::run("Kuk_section_module/get_kuk_section_by_id", array("kuk_id" => $kuk_id), $kuk_section_id);

		// if (!(modules::run("Permission_module/require_permission", "KUK_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "KUK_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_kuk_section($kuk_id, $kuk_section_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK_SECTION",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_kuk_section($kuk_id, $kuk_section_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Kuk_section_module/delete_hard_kuk_section_by_id", $kuk_id, $kuk_section_id, $confirmation, $modified_by);
	}
}


