<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment_flows extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_assessment_flow_detail($assessment_flow_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->assessment_flow_detail($assessment_flow_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_flow_list() 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->assessment_flow_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_flow_detail($assessment_id, $assessment_flow_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$this->my_parameter["assessment_id"] = $assessment_id;

		$data = $this->assessment_flow_detail($assessment_flow_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_flow_list($assessment_id) 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$this->my_parameter["assessment_id"] = $assessment_id;
		
		$data = $this->assessment_flow_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function assessment_flow_detail($assessment_flow_id)
	{
		$assessment_flows = modules::run("Assessment_flow_module/get_assessment_flow_by_id", $this->my_parameter, $assessment_flow_id);

		$this->load->helper("url");

		if ($assessment_flows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_FLOW",
							"reason" => "Assessment_flowNotFound"
						),
					)
				)
			);
		}

		return array("data" => $assessment_flows);
	}

	protected function assessment_flow_list()
	{
		return modules::run("Assessment_flow_module/get_assessment_flow_list", $this->my_parameter);
	}

	public function get_assessment_flow_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->assessment_flow_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_flow_count($assessment_id) 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"];

		$this->my_parameter["assessment_id"] = $assessment_id;

		$data = $this->assessment_flow_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function assessment_flow_count()
	{
		$count = modules::run("Assessment_flow_module/get_assessment_flow_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create assessment_flow
	public function create_assessment_assessment_flow_public($assessment_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter["assessment_id"] = $assessment_id;
		
		$this->create_assessment_flow();
	}

	public function create_assessment_assessment_flow_session($assessment_id)
	{
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE");
		
		$this->my_parameter = $this->parameter;
		$this->my_parameter["assessment_id"] = $assessment_id;

		$created_by = $this->userdata['user_id'];

		$this->create_assessment_flow($created_by);
	}

	protected function create_assessment_flow($created_by = 0)
	{
		$assessment_flow_id = modules::run("Assessment_flow_module/create_assessment_flow", $this->my_parameter, $created_by);
			
		if ($assessment_flow_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_FLOW",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("assessment_flow_id" => $assessment_flow_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_assessment_assessment_flow_by_id($assessment_id, $assessment_flow_id)
	{
		$assessment_flow = modules::run("Assessment_flow_module/get_assessment_flow_by_id", array("assessment_id" => $assessment_id), $assessment_flow_id);
		
		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment_flow->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
		
		$this->my_parameter = $this->parameter;

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_assessment_flow($assessment_id, $assessment_flow_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_FLOW",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_assessment_flow($assessment_id, $assessment_flow_id, $modified_by)
	{
		return modules::run("Assessment_flow_module/update_assessment_flow_by_id", $assessment_id, $assessment_flow_id, $this->my_parameter, $modified_by);
	}

	public function delete_soft_assessment_assessment_flow_by_id($assessment_id, $assessment_flow_id)
	{
		$assessment_flow = modules::run("Assessment_flow_module/get_assessment_flow_by_id", array("assessment_id" => $assessment_id), $assessment_flow_id);

		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_assessment_flow($assessment_id, $assessment_flow_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_FLOW",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_assessment_flow($assessment_id, $assessment_flow_id, $modified_by = 0)
	{
		return modules::run("Assessment_flow_module/delete_soft_assessment_flow_by_id", $assessment_id, $assessment_flow_id, $modified_by);
	}

	public function delete_hard_assessment_assessment_flow_by_id($assessment_id, $assessment_flow_id, $confirmation)
	{
		$assessment_flow = modules::run("Assessment_flow_module/get_assessment_flow_by_id", array("assessment_id" => $assessment_id), $assessment_flow_id);

		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_assessment_flow($assessment_id, $assessment_flow_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_FLOW",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_assessment_flow($assessment_id, $assessment_flow_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Assessment_flow_module/delete_hard_assessment_flow_by_id", $assessment_id, $assessment_flow_id, $confirmation, $modified_by);
	}
}


