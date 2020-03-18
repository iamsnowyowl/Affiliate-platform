<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kuk_section_details extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_kuk_section_detail_detail($kuk_section_detail_id)
	{
		$this->my_parameter = $this->parameter;

		// if (!modules::run("Permission_module/require_permission", "KUK_SECTION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_SECTION_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->kuk_section_detail_detail($kuk_section_detail_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_kuk_section_detail_list() 
	{
		$this->my_parameter = $this->parameter;
		
		// if (!modules::run("Permission_module/require_permission", "KUK_SECTION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_SECTION_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->kuk_section_detail_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_kuk_section_kuk_section_detail_detail($kuk_section_id, $kuk_section_detail_id)
	{
		$this->my_parameter = $this->parameter;

		// if (!modules::run("Permission_module/require_permission", "KUK_SECTION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_SECTION_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$this->my_parameter["kuk_section_id"] = $kuk_section_id;

		$data = $this->kuk_section_detail_detail($kuk_section_detail_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_kuk_section_kuk_section_detail_list($kuk_section_id) 
	{
		$this->my_parameter = $this->parameter;
		
		// if (!modules::run("Permission_module/require_permission", "KUK_SECTION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_SECTION_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$this->my_parameter["kuk_section_id"] = $kuk_section_id;
		
		$data = $this->kuk_section_detail_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function kuk_section_detail_detail($kuk_section_detail_id)
	{
		$kuk_section_details = modules::run("Kuk_section_detail_module/get_kuk_section_detail_by_id", $this->my_parameter, $kuk_section_detail_id);

		$this->load->helper("url");

		if ($kuk_section_details === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK_SECTION_DETAIL",
							"reason" => "Kuk_section_detailNotFound"
						),
					)
				)
			);
		}

		return array("data" => $kuk_section_details);
	}

	protected function kuk_section_detail_list()
	{
		return modules::run("Kuk_section_detail_module/get_kuk_section_detail_list", $this->my_parameter);
	}

	public function get_kuk_section_detail_count() 
	{
		$this->my_parameter = $this->parameter;

		// if (!modules::run("Permission_module/require_permission", "KUK_SECTION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_SECTION_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->kuk_section_detail_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_kuk_section_kuk_section_detail_count($kuk_section_id) 
	{
		$this->my_parameter = $this->parameter;

		// if (!modules::run("Permission_module/require_permission", "KUK_SECTION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_SECTION_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"];

		$this->my_parameter["kuk_section_id"] = $kuk_section_id;

		$data = $this->kuk_section_detail_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function kuk_section_detail_count()
	{
		$count = modules::run("Kuk_section_detail_module/get_kuk_section_detail_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create kuk_section_detail
	public function create_kuk_section_kuk_section_detail_public($kuk_section_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter["kuk_section_id"] = $kuk_section_id;
		
		$this->create_kuk_section_detail();
	}

	public function create_kuk_section_kuk_section_detail_session($kuk_section_id)
	{
		// if (!modules::run("Permission_module/require_permission", "KUK_SECTION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "KUK_SECTION_CREATE");
		
		$this->my_parameter = $this->parameter;
		$this->my_parameter["kuk_section_id"] = $kuk_section_id;

		$created_by = $this->userdata['user_id'];

		$this->create_kuk_section_detail($created_by);
	}

	protected function create_kuk_section_detail($created_by = 0)
	{
		$kuk_section_detail_id = modules::run("Kuk_section_detail_module/create_kuk_section_detail", $this->my_parameter, $created_by);
			
		if ($kuk_section_detail_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK_SECTION_DETAIL",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("kuk_section_detail_id" => $kuk_section_detail_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_kuk_section_kuk_section_detail_by_id($kuk_section_id, $kuk_section_detail_id)
	{
		$kuk_section_detail = modules::run("Kuk_section_detail_module/get_kuk_section_detail_by_id", array("kuk_section_id" => $kuk_section_id), $kuk_section_detail_id);
		
		// if (!(modules::run("Permission_module/require_permission", "KUK_SECTION_CREATE_OWN", FALSE) && $kuk_section_detail->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "KUK_SECTION_UPDATE");
		
		$this->my_parameter = $this->parameter;

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_kuk_section_detail($kuk_section_id, $kuk_section_detail_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK_SECTION_DETAIL",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_kuk_section_detail($kuk_section_id, $kuk_section_detail_id, $modified_by)
	{
		return modules::run("Kuk_section_detail_module/update_kuk_section_detail_by_id", $kuk_section_id, $kuk_section_detail_id, $this->my_parameter, $modified_by);
	}

	public function delete_soft_kuk_section_kuk_section_detail_by_id($kuk_section_id, $kuk_section_detail_id)
	{
		$kuk_section_detail = modules::run("Kuk_section_detail_module/get_kuk_section_detail_by_id", array("kuk_section_id" => $kuk_section_id), $kuk_section_detail_id);

		// if (!(modules::run("Permission_module/require_permission", "KUK_SECTION_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "KUK_SECTION_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_kuk_section_detail($kuk_section_id, $kuk_section_detail_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK_SECTION_DETAIL",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_kuk_section_detail($kuk_section_id, $kuk_section_detail_id, $modified_by = 0)
	{
		return modules::run("Kuk_section_detail_module/delete_soft_kuk_section_detail_by_id", $kuk_section_id, $kuk_section_detail_id, $modified_by);
	}

	public function delete_hard_kuk_section_kuk_section_detail_by_id($kuk_section_id, $kuk_section_detail_id, $confirmation)
	{
		$kuk_section_detail = modules::run("Kuk_section_detail_module/get_kuk_section_detail_by_id", array("kuk_section_id" => $kuk_section_id), $kuk_section_detail_id);

		// if (!(modules::run("Permission_module/require_permission", "KUK_SECTION_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "KUK_SECTION_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_kuk_section_detail($kuk_section_id, $kuk_section_detail_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "KUK_SECTION_DETAIL",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_kuk_section_detail($kuk_section_id, $kuk_section_detail_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Kuk_section_detail_module/delete_hard_kuk_section_detail_by_id", $kuk_section_id, $kuk_section_detail_id, $confirmation, $modified_by);
	}
}


