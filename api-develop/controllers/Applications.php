<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applications extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_application_detail($application_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICATION_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->application_detail($application_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_application_list() 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICATION_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->application_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function application_detail($application_id)
	{
		$applications = modules::run("Application_module/get_application_by_id", $this->my_parameter, $application_id);

		$this->load->helper("url");

		if ($applications === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICATION",
							"reason" => "ApplicationNotFound"
						),
					)
				)
			);
		}

		return array("data" => $applications);
	}

	protected function application_list()
	{
		return modules::run("Application_module/get_application_list", $this->my_parameter);
	}

	public function get_application_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICATION_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->application_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function application_count()
	{
		$count = modules::run("Application_module/get_application_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create application
	public function create_application_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_application();
	}

	public function create_application_session()
	{
		if (!modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICATION_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_application($created_by);
	}

	protected function create_application($created_by = 0)
	{
		$application_id = modules::run("Application_module/create_application", $this->my_parameter, $created_by);
			
		if ($application_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICATION",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("application_id" => $application_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_application_by_id($application_id)
	{
		$application = modules::run("Application_module/get_application_by_id", array(), $application_id);
		
		if (!(modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE) && $application->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "APPLICATION_UPDATE");
		
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_application($application_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICATION",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_application($application, $modified_by)
	{
		return modules::run("Application_module/update_application_by_id", $application, $this->my_parameter, $modified_by);
	}

	public function delete_soft_application_by_id($application_id)
	{
		$application = modules::run("Application_module/get_application_by_id", array(), $application_id);

		if (!(modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "APPLICATION_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_application($application_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICATION",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_application($application_id, $modified_by = 0)
	{
		return modules::run("Application_module/delete_soft_application_by_id", $application_id, $modified_by);
	}

	public function delete_hard_application_by_id($application_id, $confirmation)
	{
		$application = modules::run("Application_module/get_application_by_id", array(), $application_id);

		if (!(modules::run("Permission_module/require_permission", "APPLICATION_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "APPLICATION_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_application($application_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICATION",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_application($application_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Application_module/delete_hard_application_by_id", $application_id, $confirmation, $modified_by);
	}
}


