<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_competences extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_unit_competence_detail($unit_competence_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->unit_competence_detail($unit_competence_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_unit_competence_list() 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->unit_competence_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function unit_competence_detail($unit_competence_id)
	{
		$unit_competences = modules::run("Unit_competence_module/get_unit_competence_by_id", $this->my_parameter, $unit_competence_id);

		$this->load->helper("url");

		if ($unit_competences === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "UNIT_COMPETENCE",
							"reason" => "Unit_competenceNotFound"
						),
					)
				)
			);
		}

		return array("data" => $unit_competences);
	}

	protected function unit_competence_list()
	{
		return modules::run("Unit_competence_module/get_unit_competence_list", $this->my_parameter);
	}

	public function get_unit_competence_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->unit_competence_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function unit_competence_count()
	{
		$count = modules::run("Unit_competence_module/get_unit_competence_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create unit_competence
	public function create_unit_competence_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_unit_competence();
	}

	public function create_unit_competence_session()
	{
		if (!modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_unit_competence($created_by);
	}

	protected function create_unit_competence($created_by = 0)
	{
		$unit_competence_id = modules::run("Unit_competence_module/create_unit_competence", $this->my_parameter, $created_by);
			
		if ($unit_competence_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "UNIT_COMPETENCE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("unit_competence_id" => $unit_competence_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_unit_competence_by_id($unit_competence_id)
	{
		$unit_competence = modules::run("Unit_competence_module/get_unit_competence_by_id", array(), $unit_competence_id);
		
		if (!(modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_CREATE_OWN", FALSE) && $unit_competence->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_UPDATE");
		
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_unit_competence($unit_competence_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "UNIT_COMPETENCE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_unit_competence($unit_competence, $modified_by)
	{
		return modules::run("Unit_competence_module/update_unit_competence_by_id", $unit_competence, $this->my_parameter, $modified_by);
	}

	public function delete_soft_unit_competence_by_id($unit_competence_id)
	{
		$unit_competence = modules::run("Unit_competence_module/get_unit_competence_by_id", array(), $unit_competence_id);

		if (!(modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_CREATE_OWN", FALSE) && $unit_competence->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_unit_competence($unit_competence_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "UNIT_COMPETENCE",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_unit_competence($unit_competence_id, $modified_by = 0)
	{
		return modules::run("Unit_competence_module/delete_soft_unit_competence_by_id", $unit_competence_id, $modified_by);
	}

	public function delete_hard_unit_competence_by_id($unit_competence_id, $confirmation)
	{
		$unit_competence = modules::run("Unit_competence_module/get_unit_competence_by_id", array(), $unit_competence_id);

		if (!(modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_CREATE_OWN", FALSE) && $unit_competence->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "UNIT_COMPETENCE_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_unit_competence($unit_competence_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "UNIT_COMPETENCE",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_unit_competence($unit_competence_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Unit_competence_module/delete_hard_unit_competence_by_id", $unit_competence_id, $confirmation, $modified_by);
	}
}


