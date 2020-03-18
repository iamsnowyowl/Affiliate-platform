<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment_applicants extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_assessment_applicant_detail($assessment_applicant_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->assessment_applicant_detail($assessment_applicant_id);
		if (!empty($data["data"]->applicant_id))
		{
			$data["data"]->picture = '/users/'.$data["data"]->applicant_id.'/picture';
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_applicant_list() 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"];
		
		$data = $this->assessment_applicant_list();

		if (!empty($data["data"]))
		{
			for ($i=0; $i < count($data["data"]); $i++) { 
				$data["data"][$i]->picture = '/users/'.$data["data"][$i]->applicant_id.'/picture';
			}
		}
		
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function owner_get_assessment_assessment_applicant_list($assessment_id)
	{
		$this->my_parameter = $this->parameter;
		
		switch ($this->userdata["role_code"]) {
			case 'APL':
				$this->my_parameter["applicant_id"] = $this->userdata["user_id"];
				break;
			default:
				$this->get_assessment_assessment_applicant_list($assessment_id);
				return;
				break;
		}
		$this->my_parameter["assessment_id"] = $assessment_id;
		
		$data = $this->assessment_applicant_list();

		if (!empty($data["data"]))
		{
			for ($i=0; $i < count($data["data"]); $i++) { 
				$data["data"][$i]->picture = '/users/'.$data["data"][$i]->applicant_id.'/picture';
			}
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_applicant_detail($assessment_id, $assessment_applicant_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else {
			switch ($this->userdata["role_code"]) {
				case 'ACS':
					$assessment_assessors = modules::run("Assessment_assessor_module/get_assessment_assessor_list", array("assessment_id" => $assessment_id, "assessor_id" => $this->userdata["user_id"]));
					$assessment_plenos = modules::run("Assessment_pleno_module/get_assessment_pleno_list", array("assessment_id" => $assessment_id, "pleno_id" => $this->userdata["user_id"]));
					if (!(!empty($assessment_assessors["count"]) || !empty($assessment_plenos["count"])))
					{
						$code = 404;
						response($code, array(
								"responseStatus" => "ERROR",
								"error" => array(
									"code" => $code,
									"message" => "Resource not found. maybe you are not a part of this assessment",
									"errors" => array(
										"domain" => "ASSESSMENT_APPLICANT",
										"reason" => "Assessment_applicantNotFound"
									),
								)
							)
						);
					}
					break;

				case 'ADT':
					$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
					$assessments = modules::run("Assessment_module/get_assessment_list", array("assessment_id" => $assessment_id, "tuk_id" => $admin_data->tuk_id));
					if (empty($assessments["data"][0]->assessment_id)) $this->my_parameter["created_by"] = $this->userdata["user_id"];
					break;
				default:
					$this->my_parameter["created_by"] = $this->userdata["user_id"]; 
					break;
			}
		}

		$this->my_parameter["assessment_id"] = $assessment_id;

		$data = $this->assessment_applicant_detail($assessment_applicant_id);

		if (!empty($data["data"]->applicant_id))
		{
			$data["data"]->picture = '/users/'.$data["data"]->applicant_id.'/picture';
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_find_not_assign_applicant_list($assessment_id, $sub_schema_number) 
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

		$data = $this->find_not_assign_applicant_list($assessment_id, $sub_schema_number);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_applicant_list($assessment_id) 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else {
			switch ($this->userdata["role_code"]) {
				case 'ACS':
					$assessment_assessors = modules::run("Assessment_assessor_module/get_assessment_assessor_list", array("assessment_id" => $assessment_id, "assessor_id" => $this->userdata["user_id"]));
					$assessment_plenos = modules::run("Assessment_pleno_module/get_assessment_pleno_list", array("assessment_id" => $assessment_id, "pleno_id" => $this->userdata["user_id"]));

					if (!(!empty($assessment_assessors["count"]) || !empty($assessment_plenos["count"])))
					{
						$code = 404;
						response($code, array(
								"responseStatus" => "ERROR",
								"error" => array(
									"code" => $code,
									"message" => "Resource not found. maybe you are not a part of this assessment",
									"errors" => array(
										"domain" => "ASSESSMENT_APPLICANT",
										"reason" => "Assessment_applicantNotFound"
									),
								)
							)
						);
					}
					break;
				case 'ADT':
					
					break;
				case 'SUP':
						$assessment_plenos = modules::run("Assessment_pleno_module/get_assessment_pleno_list", array("assessment_id" => $assessment_id, "pleno_id" => $this->userdata["user_id"]));
						if (empty($assessment_plenos["count"]))
						{
							$code = 404;
							response($code, array(
									"responseStatus" => "ERROR",
									"error" => array(
										"code" => $code,
										"message" => "Resource not found. maybe you are not a part of this assessment",
										"errors" => array(
											"domain" => "ASSESSMENT_APPLICANT",
											"reason" => "Assessment_applicantNotFound"
										),
									)
								)
							);
						}
						break;
				default:
					$this->my_parameter["created_by"] = $this->userdata["user_id"]; 
					break;
			}
		}
		$this->my_parameter["assessment_id"] = $assessment_id;
		
		$data = $this->assessment_applicant_list();

		if (!empty($data["data"]))
		{
			for ($i=0; $i < count($data["data"]); $i++) { 
				$data["data"][$i]->picture = '/users/'.$data["data"][$i]->applicant_id.'/picture';
			}
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function assessment_applicant_detail($assessment_applicant_id)
	{
		$assessment_applicants = modules::run("Assessment_applicant_module/get_assessment_applicant_by_id", $this->my_parameter, $assessment_applicant_id);

		$this->load->helper("url");

		if ($assessment_applicants === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_APPLICANT",
							"reason" => "Assessment_applicantNotFound"
						),
					)
				)
			);
		}

		return array("data" => $assessment_applicants);
	}

	protected function assessment_applicant_list()
	{
		return modules::run("Assessment_applicant_module/get_assessment_applicant_list", $this->my_parameter);
	}

	public function get_assessment_applicant_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->assessment_applicant_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_applicant_count($assessment_id) 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"];

		$this->my_parameter["assessment_id"] = $assessment_id;

		$data = $this->assessment_applicant_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function assessment_applicant_count()
	{
		$count = modules::run("Assessment_applicant_module/get_assessment_applicant_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create assessment_applicant
	public function create_assessment_assessment_applicant_public($assessment_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter["assessment_id"] = $assessment_id;
		
		$this->create_assessment_applicant();
	}

	public function owner_create_assessment_assessment_applicant_session($assessment_id)
	{
		$assessment = modules::run("Assessment_module/get_assessment_by_id", [], $assessment_id);

		$this->my_parameter = $this->parameter;
		$this->my_parameter["assessment_id"] = $assessment_id;
		$this->my_parameter["applicant_id"] = $this->userdata["user_id"];
		$this->my_parameter["tuk_id"] = $assessment->tuk_id;
		$this->my_parameter["sub_schema_number"] = $assessment->sub_schema_number;
		$created_by = $this->userdata['user_id'];

		$this->create_assessment_applicant("create_assessment_applicant",$created_by);
	}

	public function create_assessment_assessment_applicant_session($assessment_id)
	{
		
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE");
		$this->my_parameter = $this->parameter;
		$validation_name = "create_assessment_applicant";
		
		if (!empty($this->parameter["assessor_id"]) && !empty($this->parameter["applicant_id"])) {
			$assessment_applicant = modules::run("Assessment_applicant_module/get_assessment_applicant_list", array("assessment_id" => $assessment_id, "applicant_id" => $this->parameter["applicant_id"]));
			if (!empty($assessment_applicant["data"][0]->assessment_applicant_id)) {
				$assessment_applicant_id = $assessment_applicant["data"][0]->assessment_applicant_id;
				$this->my_parameter = array();
				$this->my_parameter["applicant_id"] = $this->parameter["applicant_id"];
				$this->my_parameter["assessor_id"] = $this->parameter["assessor_id"];
				$affected_row = modules::run("Assessment_applicant_module/update_assessment_applicant_by_id", $assessment_id, $assessment_applicant_id, $this->my_parameter, $this->userdata["user_id"]);

				if ($affected_row === FALSE)
				{
					$code = modules::run("Error_module/get_error_code");
					response($code, array(
							"responseStatus" => "ERROR",
							"error" => array(
								"code" => $code,
								"message" => modules::run("Error_module/get_error"),
								"errors" => array(
									"domain" => "ASSESSMENT_APPLICANT",
									"reason" => "UpdateErrorException",
									"extra" => modules::run("Error_module/get_error_extra")
								),
							)
						)
					);
				}

				response(200, array_merge(array("responseStatus" => "SUCCESS")));
			}
		}

		$this->my_parameter["assessment_id"] = $assessment_id;
		$created_by = $this->userdata['user_id'];

		$this->create_assessment_applicant($validation_name, $created_by);
	}

	protected function create_assessment_applicant($validation_name = "create_assessment_applicant", $created_by = 0)
	{
		$assessment_applicant_id = modules::run("Assessment_applicant_module/create_assessment_applicant", $this->my_parameter, $validation_name, $created_by);
			
		if ($assessment_applicant_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_APPLICANT",
							"reason" => "CreateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("assessment_applicant_id" => $assessment_applicant_id));

		$parameter_applicant = array(
			"assessment_id" => $this->my_parameter["assessment_id"],
			"applicant_id" => $this->my_parameter['applicant_id'],
			"tuk_id" => $this->my_parameter['tuk_id'],
			"sub_schema_number" => $this->my_parameter["sub_schema_number"]
		);

		
		$parameter_applicant["assessment_applicant_id"] = $assessment_applicant_id;

		// create default applicant portfolio
		modules::run("Applicant_portfolio_module/create_default_applicant_portfolio", $parameter_applicant, 0, FALSE);

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_assessment_assessment_applicant_by_id($assessment_id, $assessment_applicant_id)
	{
		switch ($this->userdata["role_code"]) {
			case 'ACS':
				$assessment_assessors = modules::run("Assessment_assessor_module/get_assessment_assessor_list", array("assessment_id" => $assessment_id, "assessor_id" => $this->userdata["user_id"]));
				$assessment_plenos = modules::run("Assessment_pleno_module/get_assessment_pleno_list", array("assessment_id" => $assessment_id, "pleno_id" => $this->userdata["user_id"]));
					if (!(!empty($assessment_assessors["count"]) || !empty($assessment_plenos["count"])))
					{
						$code = 404;
						response($code, array(
								"responseStatus" => "ERROR",
								"error" => array(
									"code" => $code,
									"message" => "Resource not found. maybe you are not a part of this assessment",
									"errors" => array(
										"domain" => "ASSESSMENT_APPLICANT",
										"reason" => "Assessment_applicantNotFound"
									),
								)
							)
						);
					}
				break;
			
			default:
				$assessment_applicant = modules::run("Assessment_applicant_module/get_assessment_applicant_by_id", array("assessment_id" => $assessment_id), $assessment_applicant_id);
				if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment_applicant->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
				break;
		}
		
		$this->my_parameter = $this->parameter;

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_assessment_applicant($assessment_id, $assessment_applicant_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_APPLICANT",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_assessment_applicant($assessment_id, $assessment_applicant_id, $modified_by)
	{
		return modules::run("Assessment_applicant_module/update_assessment_applicant_by_id", $assessment_id, $assessment_applicant_id, $this->my_parameter, $modified_by);
	}

	public function delete_soft_assessment_assessment_applicant_by_id($assessment_id, $assessment_applicant_id)
	{
		$assessment_applicant = modules::run("Assessment_applicant_module/get_assessment_applicant_by_id", array("assessment_id" => $assessment_id), $assessment_applicant_id);

		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment_applicant->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_assessment_applicant($assessment_id, $assessment_applicant_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_APPLICANT",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_assessment_applicant($assessment_id, $assessment_applicant_id, $modified_by = 0)
	{
		return modules::run("Assessment_applicant_module/delete_soft_assessment_applicant_by_id", $assessment_id, $assessment_applicant_id, $modified_by);
	}

	public function delete_hard_assessment_assessment_applicant_by_id($assessment_id, $assessment_applicant_id, $confirmation)
	{
		$assessment_applicant = modules::run("Assessment_applicant_module/get_assessment_applicant_by_id", array("assessment_id" => $assessment_id), $assessment_applicant_id);

		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment_applicant->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_assessment_applicant($assessment_id, $assessment_applicant_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_APPLICANT",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function find_not_assign_applicant_list($assessment_id, $sub_schema_number)
	{
		return modules::run("Assessment_applicant_module/find_not_assign_applicant_list", $assessment_id, $sub_schema_number, $this->my_parameter);
	}

	protected function delete_hard_assessment_applicant($assessment_id, $assessment_applicant_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Assessment_applicant_module/delete_hard_assessment_applicant_by_id", $assessment_id, $assessment_applicant_id, $confirmation, $modified_by);
	}
}


