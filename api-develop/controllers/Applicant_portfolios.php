<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_portfolios extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function read_applicant_applicant_portfolio_detail($assessment_id, $assessment_applicant_id, $applicant_portfolio_id)
	{
		$this->my_parameter = $this->parameter;

		$this->my_parameter["assessment_id"] = $assessment_id;
		$this->my_parameter["assessment_applicant_id"] = $assessment_applicant_id;

		$data = $this->applicant_portfolio_detail($applicant_portfolio_id);

		if (!file_exists($data["data"]->form_value))
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "form_value not found",
						"errors" => array(
							"domain" => "TENANT_RELATION_MEDIA",
							"reason" => "FileNotFound"
						),
					)
				)
			);
		}

		if (file_exists($data["data"]->form_value)) 
		{
			$data_src = file_get_contents($data["data"]->form_value);
		    $this->output // You could also use ".jpeg" which will have the full stop removed before looking in config/mimes.php
			->set_output(file_get_contents($data_src))->set_content_type($data["data"]->mime_type)->_display();
		}
		return;
	}

	public function get_applicant_applicant_portfolio_detail($assessment_id, $assessment_applicant_id, $applicant_portfolio_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else {
			switch ($this->userdata["role_code"]) {
				case 'ACS':
					$assessments = modules::run("Assessment_module/non_admin_get_assessment_by_id", array("identifier" => $this->userdata["user_id"]), $assessment_id);
					if (empty($assessments["count"])){
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
		$this->my_parameter["assessment_applicant_id"] = $assessment_applicant_id;

		$data = $this->applicant_portfolio_detail($applicant_portfolio_id);

		if (!empty($data["data"]->applicant_portfolio_id) && !empty($data["data"]->form_value) && $data["data"]->form_type == "file") $data["data"]->form_value = "/public/assessments/$assessment_id/applicants/$assessment_applicant_id/portfolios/$applicant_portfolio_id/reads";
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_applicant_portfolio_list($assessment_id) 
	{
		$assessment_applicant_data = modules::run("Assessment_applicant_module/get_assessment_applicant_list", array("assessment_id" => $assessment_id, "applicant_id" => $this->userdata["user_id"], "limit" => 1));
		$assessments = modules::run("Assessment_module/get_assessment_by_id", [], $assessment_id);

		$this->my_parameter = $this->parameter;
		$this->my_parameter["assessment_applicant_id"] = $assessment_applicant_data["data"][0]->assessment_applicant_id;
		if (!empty($assessments->assessment_id))
		{
			switch ($this->userdata['role_code']) {
				case 'APL':
					$this->my_parameter["apl_document_state"] = "ALL,".$assessments->last_activity_state;
					break;
				case 'ACS':
					$this->my_parameter["acs_document_state"] = "ALL,".$assessments->last_activity_state;
					break;
				default:
					$this->my_parameter["document_state"] = (!empty($this->my_parameter["document_state"])) ? $this->my_parameter["document_state"] : "ALL,".$assessments->last_activity_state;
					# code...
					break;
			}
		}
		$this->my_parameter["assessment_id"] = $assessment_id;
		$data = $this->applicant_portfolio_list();
		
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_applicant_applicant_portfolio_list($assessment_id, $assessment_applicant_id) 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else {
			switch ($this->userdata["role_code"]) {
				case 'ACS':
					$assessments = modules::run("Assessment_module/non_admin_get_assessment_list", array("assessment_id" => $assessment_id, "identifier" => $this->userdata["user_id"]));
					$this->my_parameter["acs_document_state"] = "ALL,".$assessments["data"][0]->last_activity_state;
					if (empty($assessments["count"])){
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
		$this->my_parameter["assessment_applicant_id"] = $assessment_applicant_id;
		$this->my_parameter["sort"] = "form_description";
		
		$data = $this->applicant_portfolio_list();
		
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function applicant_portfolio_detail($applicant_portfolio_id)
	{
		$applicant_portfolios = modules::run("Applicant_portfolio_module/get_applicant_portfolio_by_id", $this->my_parameter, $applicant_portfolio_id);

		$this->load->helper("url");

		if ($applicant_portfolios === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "Applicant_portfolioNotFound"
						),
					)
				)
			);
		}

		return array("data" => $applicant_portfolios);
	}

	protected function applicant_portfolio_list()
	{
		return modules::run("Applicant_portfolio_module/get_applicant_portfolio_list", $this->my_parameter);
	}

	public function get_applicant_applicant_portfolio_count($assessment_id, $assessment_applicant_id) 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"];

		$this->my_parameter["assessment_id"] = $assessment_id;
		$this->my_parameter["assessment_applicant_id"] = $assessment_applicant_id;

		$data = $this->applicant_portfolio_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function applicant_portfolio_count()
	{
		$count = modules::run("Applicant_portfolio_module/get_applicant_portfolio_count", $this->my_parameter);
		return (array) $count;
	}

	public function create_applicant_applicant_portfolio_session($assessment_id, $assessment_applicant_id)
	{
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE");
		
		$this->my_parameter = $this->parameter;
		$this->my_parameter["assessment_id"] = $assessment_id;
		$this->my_parameter["assessment_applicant_id"] = $assessment_applicant_id;

		$created_by = $this->userdata['user_id'];

		$this->create_applicant_portfolio($created_by);
	}
	
	public function create_applicant_portfolio_session($assessment_id)
	{
		$assessment_applicant_data = modules::run("Assessment_applicant_module/get_assessment_applicant_list", array("assessment_id" => $assessment_id, "applicant_id" => $this->userdata["user_id"]));
		
		if (empty($assessment_applicant_data["data"][0])){
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "applicant_portfolio_id not found.",
						"errors" => array(
							"domain" => "ASSESSMENT_APPLICANT",
							"reason" => "Assessment_applicantNotFound"
						),
					)
				)
			);
		}

		$this->my_parameter = $this->parameter;
		
		$this->my_parameter["assessment_id"] = $assessment_id;
		$this->my_parameter["assessment_applicant_id"] = $assessment_applicant_data["data"][0]->assessment_applicant_id;

		$created_by = $this->userdata['user_id'];

		$this->create_applicant_portfolio($created_by);
	}

	public function create_applicant_applicant_custom_portfolio_session($assessment_id, $assessment_applicant_id)
	{
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE");
		
		$this->my_parameter = $this->parameter;
		$this->my_parameter["assessment_id"] = $assessment_id;
		$this->my_parameter["assessment_applicant_id"] = $assessment_applicant_id;

		$assessment_applicant = modules::run("Assessment_applicant_module/get_assessment_applicant_by_id", array(), $assessment_applicant_id);
		$this->my_parameter["applicant_id"] = $assessment_applicant->applicant_id;

		$created_by = $this->userdata['user_id'];

		$this->create_applicant_custom_portfolio($created_by);
	}

	protected function create_applicant_portfolio($created_by = 0)
	{
		if (!empty($this->my_parameter["filename"])) {
			$this->my_parameter["filename"] = strtolower(str_replace(' ', '', $this->my_parameter["filename"]));
			$this->my_parameter["filename"] = str_replace(',', '', $this->my_parameter["filename"]);
		}
		
		$applicant_portfolio_id = modules::run("Applicant_portfolio_module/create_applicant_portfolio", $this->my_parameter, $created_by);
			
		if ($applicant_portfolio_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("applicant_portfolio_id" => $applicant_portfolio_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function create_applicant_custom_portfolio($created_by = 0)
	{
		$applicant_portfolio_id = modules::run("Applicant_portfolio_module/create_applicant_custom_portfolio", $this->my_parameter, $created_by);
			
		if ($applicant_portfolio_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("applicant_portfolio_id" => $applicant_portfolio_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_applicant_portfolio_by_id($assessment_id, $applicant_portfolio_id)
	{
		$this->my_parameter = $this->parameter;
		$assessment_applicant_data = modules::run("Assessment_applicant_module/get_assessment_applicant_list", array("assessment_id" => $assessment_id, "applicant_id" => $this->userdata["user_id"]));

		if (empty($assessment_applicant_data["data"][0])){
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "applicant_portfolio_id not found.",
						"errors" => array(
							"domain" => "ASSESSMENT_APPLICANT",
							"reason" => "Assessment_applicantNotFound"
						),
					)
				)
			);
		}

		$assessment_applicant_id = $assessment_applicant_data["data"][0]->assessment_applicant_id;
		
		$modified_by = $this->userdata['user_id'];

		$affected_row = $this->update_applicant_portfolio($assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function update_applicant_applicant_portfolio_by_id($assessment_id, $assessment_applicant_id, $applicant_portfolio_id)
	{
		$this->my_parameter = $this->parameter;

		switch ($this->userdata["role_code"]) {
			case 'ACS':
				$assessments = modules::run("Assessment_module/non_admin_get_assessment_list", array("assessment_id" => $assessment_id, "identifier" => $this->userdata["user_id"]));
				if (empty($assessments["count"])){
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
				$applicant_portfolio = modules::run("Applicant_portfolio_module/get_applicant_portfolio_by_id", array("assessment_id" => $assessment_id, "assessment_applicant_id" => $assessment_applicant_id), $applicant_portfolio_id);
				if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $applicant_portfolio->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
				break;
		}
		
		
		$modified_by = $this->userdata['user_id'];

		$affected_row = $this->update_applicant_portfolio($assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_applicant_portfolio($assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $modified_by)
	{
		return modules::run("Applicant_portfolio_module/update_applicant_portfolio_by_id", $assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $this->my_parameter, $modified_by);
	}

	public function delete_hard_applicant_portfolio_by_id($assessment_id, $applicant_portfolio_id)
	{
		$assessment_applicant_data = modules::run("Assessment_applicant_module/get_assessment_applicant_list", array("assessment_id" => $assessment_id));

		if (empty($assessment_applicant_data["data"][0])){
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "applicant_portfolio_id not found.",
						"errors" => array(
							"domain" => "ASSESSMENT_APPLICANT",
							"reason" => "Assessment_applicantNotFound"
						),
					)
				)
			);
		}

		$assessment_applicant_id = $assessment_applicant_data["data"][0]->assessment_applicant_id;

		$applicant_portfolio = modules::run("Applicant_portfolio_module/get_applicant_portfolio_by_id", array("assessment_id" => $assessment_id, "assessment_applicant_id" => $assessment_applicant_id), $applicant_portfolio_id);

		// quick hack. just update portfolio to default value empty case if there is not empty applicant_potfolio. since id applicant portfolio will break by group concat. then we can assume its mutiple and save to delete
		if (!empty($applicant_portfolio))
		{
			$affected_rows = modules::run("Applicant_portfolio_module/delete_last_portfolio_by_id", $assessment_id, $assessment_applicant_id, $applicant_portfolio_id);
		}
		else{
			$modified_by = $this->userdata['user_id'];
			$affected_rows = $this->delete_hard_applicant_portfolio($assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $modified_by);
		}

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICANT_PORTFOLIO",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_hard_applicant_applicant_portfolio_by_id($assessment_id, $assessment_applicant_id, $applicant_portfolio_id)
	{
		$applicant_portfolio = modules::run("Applicant_portfolio_module/get_applicant_portfolio_by_id", array("assessment_id" => $assessment_id, "assessment_applicant_id" => $assessment_applicant_id), $applicant_portfolio_id);

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else {
			switch ($this->userdata["role_code"]) {
				case 'ADT':
					$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
					$assessments = modules::run("Assessment_module/get_assessment_list", array("assessment_id" => $assessment_id, "tuk_id" => $admin_data->tuk_id));
					
					if (!(!empty($assessments["data"][0]->assessment_id) && !empty($applicant_portfolio->assessment_id))) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");		
					break;
				
				default:
					modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
					break;
			}
		}

		// quick hack. just update portfolio to default value empty case if there is not empty applicant_potfolio. since id applicant portfolio will break by group concat. then we can assume its mutiple and save to delete
		if (!empty($applicant_portfolio))
		{
			$affected_rows = modules::run("Applicant_portfolio_module/delete_last_portfolio_by_id", $assessment_id, $assessment_applicant_id, $applicant_portfolio_id);
		}
		else{
			$modified_by = $this->userdata['user_id'];
			$affected_rows = $this->delete_hard_applicant_portfolio($assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $modified_by);
		}

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICANT_PORTFOLIO",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function download_apl01($applicant_id)
	{
		modules::run("Applicant_portfolio_module/download_apl01", $applicant_id, "IAB0062018", "S");
	}

	protected function delete_hard_applicant_portfolio($assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $modified_by = 0)
	{
		return modules::run("Applicant_portfolio_module/delete_hard_applicant_portfolio_by_id", $assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $modified_by);
	}
}


