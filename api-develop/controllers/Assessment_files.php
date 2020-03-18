<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment_files extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_assessment_file_detail($assessment_file_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->assessment_file_detail($assessment_file_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_file_list() 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->assessment_file_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_file_detail($assessment_id, $assessment_file_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$this->my_parameter["assessment_id"] = $assessment_id;

		$data = $this->assessment_file_detail($assessment_file_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_file_list($assessment_id) 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$this->my_parameter["assessment_id"] = $assessment_id;
		
		$data = $this->assessment_file_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function assessment_file_detail($assessment_file_id)
	{
		$assessment_files = modules::run("Assessment_file_module/get_assessment_file_by_id", $this->my_parameter, $assessment_file_id);

		$this->load->helper("url");

		if ($assessment_files === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_FILE",
							"reason" => "Assessment_fileNotFound"
						),
					)
				)
			);
		}

		return array("data" => $assessment_files);
	}

	protected function assessment_file_list()
	{
		return modules::run("Assessment_file_module/get_assessment_file_list", $this->my_parameter);
	}

	public function get_assessment_file_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->assessment_file_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_file_count($assessment_id) 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"];

		$this->my_parameter["assessment_id"] = $assessment_id;

		$data = $this->assessment_file_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function assessment_file_count()
	{
		$count = modules::run("Assessment_file_module/get_assessment_file_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create assessment_file
	public function create_assessment_assessment_file_public($assessment_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter["assessment_id"] = $assessment_id;
		
		$this->create_assessment_file();
	}

	public function create_assessment_assessment_file_session($assessment_id)
	{
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE");
		
		$this->my_parameter = $this->parameter;
		$this->my_parameter["assessment_id"] = $assessment_id;

		$created_by = $this->userdata['user_id'];

		$this->create_assessment_file($created_by);
	}

	protected function create_assessment_file($created_by = 0)
	{
		$assessment_file_id = modules::run("Assessment_file_module/create_assessment_file", $this->my_parameter, $created_by);
			
		if ($assessment_file_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_FILE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("assessment_file_id" => $assessment_file_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_assessment_assessment_file_by_id($assessment_id, $assessment_file_id)
	{
		$assessment_file = modules::run("Assessment_file_module/get_assessment_file_by_id", array("assessment_id" => $assessment_id), $assessment_file_id);
		
		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment_file->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
		
		$this->my_parameter = $this->parameter;

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_assessment_file($assessment_id, $assessment_file_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_FILE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_assessment_file($assessment_id, $assessment_file_id, $modified_by)
	{
		return modules::run("Assessment_file_module/update_assessment_file_by_id", $assessment_id, $assessment_file_id, $this->my_parameter, $modified_by);
	}

	public function delete_soft_assessment_assessment_file_by_id($assessment_id, $assessment_file_id)
	{
		$assessment_file = modules::run("Assessment_file_module/get_assessment_file_by_id", array("assessment_id" => $assessment_id), $assessment_file_id);

		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_assessment_file($assessment_id, $assessment_file_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_FILE",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_assessment_file($assessment_id, $assessment_file_id, $modified_by = 0)
	{
		return modules::run("Assessment_file_module/delete_soft_assessment_file_by_id", $assessment_id, $assessment_file_id, $modified_by);
	}

	public function delete_hard_assessment_assessment_file_by_id($assessment_id, $assessment_file_id, $confirmation)
	{
		$assessment_file = modules::run("Assessment_file_module/get_assessment_file_by_id", array("assessment_id" => $assessment_id), $assessment_file_id);

		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_assessment_file($assessment_id, $assessment_file_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_FILE",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_assessment_file($assessment_id, $assessment_file_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Assessment_file_module/delete_hard_assessment_file_by_id", $assessment_id, $assessment_file_id, $confirmation, $modified_by);
	}
}


