<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Flows extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_flow_detail($flow_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "FLOW_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "FLOW_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->flow_detail($flow_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_flow_list() 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "FLOW_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "FLOW_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->flow_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function flow_detail($flow_id)
	{
		$flows = modules::run("Flow_module/get_flow_by_id", $this->my_parameter, $flow_id);

		$this->load->helper("url");

		if ($flows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "FLOW",
							"reason" => "FlowNotFound"
						),
					)
				)
			);
		}

		return array("data" => $flows);
	}

	protected function flow_list()
	{
		return modules::run("Flow_module/get_flow_list", $this->my_parameter);
	}

	public function get_flow_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "FLOW_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "FLOW_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->flow_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function flow_count()
	{
		$count = modules::run("Flow_module/get_flow_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create flow
	public function create_flow_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_flow();
	}

	public function create_flow_session()
	{
		if (!modules::run("Permission_module/require_permission", "FLOW_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "FLOW_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_flow($created_by);
	}

	protected function create_flow($created_by = 0)
	{
		$flow_id = modules::run("Flow_module/create_flow", $this->my_parameter, $created_by);
			
		if ($flow_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "FLOW",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("flow_id" => $flow_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_flow_by_id($flow_id)
	{
		$flow = modules::run("Flow_module/get_flow_by_id", array(), $flow_id);
		
		if (!(modules::run("Permission_module/require_permission", "FLOW_CREATE_OWN", FALSE) && $flow->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "FLOW_UPDATE");
		
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_flow($flow_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "FLOW",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_flow($flow, $modified_by)
	{
		return modules::run("Flow_module/update_flow_by_id", $flow, $this->my_parameter, $modified_by);
	}

	public function delete_soft_flow_by_id($flow_id)
	{
		$flow = modules::run("Flow_module/get_flow_by_id", array(), $flow_id);

		if (!(modules::run("Permission_module/require_permission", "FLOW_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "FLOW_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_flow($flow_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "FLOW",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_flow($flow_id, $modified_by = 0)
	{
		return modules::run("Flow_module/delete_soft_flow_by_id", $flow_id, $modified_by);
	}

	public function delete_hard_flow_by_id($flow_id, $confirmation)
	{
		$flow = modules::run("Flow_module/get_flow_by_id", array(), $flow_id);

		if (!(modules::run("Permission_module/require_permission", "FLOW_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "FLOW_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_flow($flow_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "FLOW",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_flow($flow_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Flow_module/delete_hard_flow_by_id", $flow_id, $confirmation, $modified_by);
	}
}


