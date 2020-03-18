<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_settings extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_application_setting_detail($application_setting_id)
	{
		$this->my_parameter = $this->parameter;
		if (!modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICATION_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		$data = $this->application_setting_detail($application_setting_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_application_setting_list() 
	{
		$this->my_parameter = $this->parameter;
	
		if (!modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICATION_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->application_setting_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_application_setting_custom_list() 
	{

		$this->my_parameter = $this->parameter;
	
		if (!modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICATION_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = modules::run("Application_setting_module/get_application_setting_list", $this->my_parameter, "default_custom","optional_custom");
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}


	public function get_application_application_setting_detail($application_id, $application_setting_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICATION_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$this->my_parameter["application_id"] = $application_id;

		$data = $this->application_setting_detail($application_setting_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_application_application_setting_list($application_id) 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICATION_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$this->my_parameter["application_id"] = $application_id;
		
		$data = $this->application_setting_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function application_setting_detail($application_setting_id)
	{
		$application_settings = modules::run("Application_setting_module/get_application_setting_by_id", $this->my_parameter, $application_setting_id);

		$this->load->helper("url");

		if ($application_settings === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICATION_SETTING",
							"reason" => "Application_settingNotFound"
						),
					)
				)
			);
		}

		return array("data" => $application_settings);
	}

	protected function application_setting_list()
	{
		return modules::run("Application_setting_module/get_application_setting_list", $this->my_parameter);
	}

	public function get_application_setting_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICATION_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->application_setting_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_application_application_setting_count($application_id) 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICATION_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"];

		$this->my_parameter["application_id"] = $application_id;

		$data = $this->application_setting_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function application_setting_count()
	{
		$count = modules::run("Application_setting_module/get_application_setting_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create application_setting
	public function create_application_application_setting_public($application_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter["application_id"] = $application_id;
		
		$this->create_application_setting();
	}

	public function create_application_application_setting_session($application_id)
	{
		if (!modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICATION_CREATE");
		
		$this->my_parameter = $this->parameter;
		$this->my_parameter["application_id"] = $application_id;

		$created_by = $this->userdata['user_id'];

		$this->create_application_setting($created_by);
	}

	protected function create_application_setting($created_by = 0)
	{
		$application_setting_id = modules::run("Application_setting_module/create_application_setting", $this->my_parameter, $created_by);

		if ($application_setting_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICATION_SETTING",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("application_setting_id" => $application_setting_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_application_application_setting_by_id($application_id, $application_setting_id)
	{
		$application_setting = modules::run("Application_setting_module/get_application_setting_by_id", array("application_id" => $application_id), $application_setting_id);
		
		if (!(modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE) && $application_setting->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "APPLICATION_UPDATE");
		
		$this->my_parameter = $this->parameter;

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_application_setting($application_id, $application_setting_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICATION_SETTING",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_application_setting($application_id, $application_setting_id, $modified_by)
	{
		return modules::run("Application_setting_module/update_application_setting_by_id", $application_id, $application_setting_id, $this->my_parameter, $modified_by);
	}

	public function delete_soft_application_application_setting_by_id($application_id, $application_setting_id)
	{
		$application_setting = modules::run("Application_setting_module/get_application_setting_by_id", array("application_id" => $application_id), $application_setting_id);

		if (!(modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "APPLICATION_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_application_setting($application_id, $application_setting_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICATION_SETTING",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_application_setting($application_id, $application_setting_id, $modified_by = 0)
	{
		return modules::run("Application_setting_module/delete_soft_application_setting_by_id", $application_id, $application_setting_id, $modified_by);
	}

	public function delete_hard_application_application_setting_by_id($application_id, $application_setting_id, $confirmation)
	{
		$application_setting = modules::run("Application_setting_module/get_application_setting_by_id", array("application_id" => $application_id), $application_setting_id);

		if (!(modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "APPLICATION_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_application_setting($application_id, $application_setting_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICATION_SETTING",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_application_setting($application_id, $application_setting_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Application_setting_module/delete_hard_application_setting_by_id", $application_id, $application_setting_id, $confirmation, $modified_by);
	}
}


