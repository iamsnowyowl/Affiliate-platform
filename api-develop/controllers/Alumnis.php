<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alumnis extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_alumni_detail($alumni_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "USER_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "USER_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->alumni_detail($alumni_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_alumni_list() 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "USER_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "USER_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->alumni_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function alumni_detail($alumni_id)
	{
		$alumnis = modules::run("Alumni_module/get_alumni_by_id", $this->my_parameter, $alumni_id);

		$this->load->helper("url");

		if ($alumnis === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ALUMNI",
							"reason" => "AlumniNotFound"
						),
					)
				)
			);
		}

		return array("data" => $alumnis);
	}

	protected function alumni_list()
	{
		return modules::run("Alumni_module/get_alumni_list", $this->my_parameter);
	}

	public function get_alumni_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "USER_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "USER_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->alumni_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function alumni_count()
	{
		$count = modules::run("Alumni_module/get_alumni_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create alumni
	public function create_alumni_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_alumni();
	}

	public function create_alumni_session()
	{
		if (!modules::run("Permission_module/require_permission", "USER_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "USER_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_alumni($created_by);
	}

	protected function create_alumni($created_by = 0)
	{
		$alumni_id = modules::run("Alumni_module/create_alumni", $this->my_parameter, $created_by);
			
		if ($alumni_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ALUMNI",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("alumni_id" => $alumni_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_alumni_by_id($alumni_id)
	{
		$alumni = modules::run("Alumni_module/get_alumni_by_id", array(), $alumni_id);
		
		if (!(modules::run("Permission_module/require_permission", "USER_CREATE_OWN", FALSE) && $alumni->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "USER_UPDATE");
		
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_alumni($alumni_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ALUMNI",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_alumni($alumni, $modified_by)
	{
		return modules::run("Alumni_module/update_alumni_by_id", $alumni, $this->my_parameter, $modified_by);
	}

	public function delete_soft_alumni_by_id($alumni_id)
	{
		$alumni = modules::run("Alumni_module/get_alumni_by_id", array(), $alumni_id);

		if (!(modules::run("Permission_module/require_permission", "USER_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "USER_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_alumni($alumni_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ALUMNI",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_alumni($alumni_id, $modified_by = 0)
	{
		return modules::run("Alumni_module/delete_soft_alumni_by_id", $alumni_id, $modified_by);
	}

	public function delete_hard_alumni_by_id($alumni_id, $confirmation)
	{
		$alumni = modules::run("Alumni_module/get_alumni_by_id", array(), $alumni_id);

		if (!(modules::run("Permission_module/require_permission", "USER_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "USER_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_alumni($alumni_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ALUMNI",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_alumni($alumni_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Alumni_module/delete_hard_alumni_by_id", $alumni_id, $confirmation, $modified_by);
	}
}


