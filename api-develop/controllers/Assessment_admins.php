<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment_admins extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_assessment_admin_detail($assessment_admin_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->assessment_admin_detail($assessment_admin_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_admin_list() 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->assessment_admin_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_admin_detail($assessment_id, $assessment_admin_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$this->my_parameter["assessment_id"] = $assessment_id;

		$data = $this->assessment_admin_detail($assessment_admin_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_admin_list($assessment_id) 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$this->my_parameter["assessment_id"] = $assessment_id;
		
		$data = $this->assessment_admin_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_find_not_assign_admin_list($assessment_id) 
	{
		$this->my_parameter = $this->parameter;

		modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");

		$assessment = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);

		if (empty($assessment->assessment_id)){
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "Resource not found",
						"errors" => array(
							"domain" => "ASSESSMENT_ASSESSOR",
							"reason" => "AssessmentNotFound"
						),
					)
				)
			);
		}

		$data = $this->find_not_assign_admin_list($assessment->start_date, $assessment->end_date);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function assessment_admin_detail($assessment_admin_id)
	{
		$assessment_admins = modules::run("Assessment_admin_module/get_assessment_admin_by_id", $this->my_parameter, $assessment_admin_id);

		$this->load->helper("url");

		if ($assessment_admins === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_ADMIN",
							"reason" => "Assessment_adminNotFound"
						),
					)
				)
			);
		}

		return array("data" => $assessment_admins);
	}

	protected function assessment_admin_list()
	{
		return modules::run("Assessment_admin_module/get_assessment_admin_list", $this->my_parameter);
	}

	protected function find_not_assign_admin_list($start_date, $end_date)
	{
		return modules::run("Assessment_admin_module/find_not_assign_admin_list", $this->my_parameter, $start_date, $end_date);
	}

	public function get_assessment_admin_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->assessment_admin_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_admin_count($assessment_id) 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"];

		$this->my_parameter["assessment_id"] = $assessment_id;

		$data = $this->assessment_admin_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function assessment_admin_count()
	{
		$count = modules::run("Assessment_admin_module/get_assessment_admin_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create assessment_admin
	public function create_assessment_assessment_admin_public($assessment_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter["assessment_id"] = $assessment_id;
		
		$this->create_assessment_admin();
	}

	public function create_assessment_assessment_admin_session($assessment_id)
	{
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE");
		
		$this->my_parameter = $this->parameter;
		$this->my_parameter["assessment_id"] = $assessment_id;

		$created_by = $this->userdata['user_id'];

		$this->create_assessment_admin($created_by);
	}

	protected function create_assessment_admin($created_by = 0)
	{
		$assessment_admin_id = modules::run("Assessment_admin_module/create_assessment_admin", $this->my_parameter, $created_by);
			
		if ($assessment_admin_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_ADMIN",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("assessment_admin_id" => $assessment_admin_id));

		$admin = modules::run("User_module/get_user_by_id", array(), $this->my_parameter["admin_id"]);

		$letter_parameter = array(
			"assessment_id" => $this->my_parameter["assessment_id"],
			"assessment_letter_name" => "Surat Tugas Admin(".ucwords($admin->first_name." ".$admin->last_name).")",
			"reference_id" => $this->my_parameter["admin_id"],
			"letter_type" => "SURAT_TUGAS_ADMIN"
		);

		modules::run("Letter_module/create_assignment_admin", $letter_parameter);

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_assessment_assessment_admin_by_id($assessment_id, $assessment_admin_id)
	{
		$assessment_admin = modules::run("Assessment_admin_module/get_assessment_admin_by_id", array("assessment_id" => $assessment_id), $assessment_admin_id);
		
		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment_admin->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
		
		$this->my_parameter = $this->parameter;

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_assessment_admin($assessment_id, $assessment_admin_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_ADMIN",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_assessment_admin($assessment_id, $assessment_admin_id, $modified_by)
	{
		return modules::run("Assessment_admin_module/update_assessment_admin_by_id", $assessment_id, $assessment_admin_id, $this->my_parameter, $modified_by);
	}

	public function delete_soft_assessment_assessment_admin_by_id($assessment_id, $assessment_admin_id)
	{
		$assessment_admin = modules::run("Assessment_admin_module/get_assessment_admin_by_id", array("assessment_id" => $assessment_id), $assessment_admin_id);

		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment_admin->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_DELETE");

		if (empty($assessment_admin->admin_id))
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "Resource admin_id not found",
						"errors" => array(
							"domain" => "ASSESSMENT_ADMIN",
							"reason" => "DeleteErrorException"
						),
					)
				)
			);
		}

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_assessment_admin($assessment_id, $assessment_admin_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_ADMIN",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		$assessment_letter_data = modules::run("Assessment_letter_module/get_assessment_letter_list", array("assessment_id" => $assessment_id, "reference_id" => $assessment_admin->admin_id)); 

		if (!empty($assessment_letter_data["data"])){
			for ($i=0; $i < count($assessment_letter_data["data"]); $i++) { 
				modules::run("Assessment_letter_module/delete_soft_assessment_letter_by_id", $assessment_id, $assessment_letter_data["data"][$i]->assessment_letter_id);
			}
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_assessment_admin($assessment_id, $assessment_admin_id, $modified_by = 0)
	{
		return modules::run("Assessment_admin_module/delete_soft_assessment_admin_by_id", $assessment_id, $assessment_admin_id, $modified_by);
	}

	public function delete_hard_assessment_assessment_admin_by_id($assessment_id, $assessment_admin_id, $confirmation)
	{
		$assessment_admin = modules::run("Assessment_admin_module/get_assessment_admin_by_id", array("assessment_id" => $assessment_id), $assessment_admin_id);

		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment_admin->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_assessment_admin($assessment_id, $assessment_admin_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_ADMIN",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_assessment_admin($assessment_id, $assessment_admin_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Assessment_admin_module/delete_hard_assessment_admin_by_id", $assessment_id, $assessment_admin_id, $confirmation, $modified_by);
	}
}


