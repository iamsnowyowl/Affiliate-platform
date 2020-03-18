<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_assessment_detail($assessment_id)
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$this->my_parameter["tuk_id"] = $api_info->tuk_id;

		$data = $this->assessment_detail($assessment_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function assessment_detail($assessment_id)
	{
		$assessments = modules::run("Assessment_module/get_assessment_by_id", $this->my_parameter, $assessment_id);

		$this->load->helper("url");

		if ($assessments === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT",
							"reason" => "AssessmentNotFound"
						),
					)
				)
			);
		}

		return array("data" => $assessments);
	}

	public function get_assessment_list()
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$this->my_parameter["tuk_id"] = $api_info->tuk_id;

		$data = $this->assessment_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function assessment_list()
	{
		return modules::run("Assessment_module/get_assessment_list", $this->my_parameter);
	}

	public function get_assessment_count() 
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$this->my_parameter["tuk_id"] = $api_info->tuk_id;

		$data = $this->assessment_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function assessment_count()
	{
		$count = modules::run("Assessment_module/get_assessment_count", $this->my_parameter);
		return (array) $count;
	}

	public function get_assessment_applicant_detail($assessment_applicant_id)
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$this->my_parameter["tuk_id"] = $api_info->tuk_id;

		$data = $this->assessment_applicant_detail($assessment_applicant_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_applicant_list() 
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$this->my_parameter["tuk_id"] = $api_info->tuk_id;
		
		$data = $this->assessment_applicant_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_applicant_detail($assessment_id, $assessment_applicant_id)
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$this->my_parameter["tuk_id"] = $api_info->tuk_id;

		$this->my_parameter["assessment_id"] = $assessment_id;

		$data = $this->assessment_applicant_detail($assessment_applicant_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_applicant_list($assessment_id) 
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$this->my_parameter["tuk_id"] = $api_info->tuk_id; 

		$this->my_parameter["assessment_id"] = $assessment_id;
		
		$data = $this->assessment_applicant_list();
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
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$this->my_parameter["tuk_id"] = $api_info->tuk_id;

		$data = $this->assessment_applicant_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_applicant_count($assessment_id) 
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$this->my_parameter["tuk_id"] = $api_info->tuk_id;
		$this->my_parameter["assessment_id"] = $assessment_id;

		$data = $this->assessment_applicant_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function assessment_applicant_count()
	{
		$count = modules::run("Assessment_applicant_module/get_assessment_applicant_count", $this->my_parameter);
		return (array) $count;
	}

	public function get_applicant_applicant_portfolio_detail($assessment_id, $assessment_applicant_id, $applicant_portfolio_id)
	{
		// check is assessment own by tuk
		$this->my_parameter = $this->parameter;
		
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$assessments = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);

		if (empty($assessments->assessment_id)){
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "AssessmentNotFound",
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "AssessmentNotFound"
						),
					)
				)
			);
		}

		if ($assessments->tuk_id != $api_info->tuk_id){
			$code = 401;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "You are not owner of this assessment",
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "Applicant_portfolioNotFound"
						),
					)
				)
			);
		}

		$this->my_parameter["assessment_id"] = $assessment_id;
		$this->my_parameter["assessment_applicant_id"] = $assessment_applicant_id;

		$data = $this->applicant_portfolio_detail($applicant_portfolio_id);
		if (!empty($data["data"]->applicant_portfolio_id) && !empty($data["data"]->form_value) && $data["data"]->form_type == "file") $data["data"]->form_value = "/public/clients/assessments/$assessment_id/applicants/$assessment_applicant_id/portfolios/$applicant_portfolio_id/reads";
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_applicant_applicant_portfolio_list($assessment_id, $assessment_applicant_id) 
	{
		$this->my_parameter = $this->parameter;

		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$assessments = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);

		if (empty($assessments->assessment_id)){
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "AssessmentNotFound",
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "AssessmentNotFound"
						),
					)
				)
			);
		}

		if ($assessments->tuk_id != $api_info->tuk_id){
			$code = 401;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "You are not owner of this assessment",
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "Applicant_portfolioNotFound"
						),
					)
				)
			);
		}

		$this->my_parameter["assessment_id"] = $assessment_id;
		$this->my_parameter["assessment_applicant_id"] = $assessment_applicant_id;
		
		$data = $this->applicant_portfolio_list();
		if (!empty($data["data"])){
			for ($i=0; $i < count($data["data"]); $i++) { 
				if (!empty($data["data"][$i]->form_value) && $data["data"][$i]->form_type == "file") $data["data"][$i]->form_value = "/public/assessments/$assessment_id/applicants/$assessment_applicant_id/portfolios/".$data["data"][$i]->applicant_portfolio_id."/reads";
			}
		}
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

	public function update_applicant_applicant_portfolio_by_id($assessment_id, $assessment_applicant_id, $applicant_portfolio_id)
	{
		$this->my_parameter = $this->parameter;

		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$assessments = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);

		if (empty($assessments->assessment_id)){
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "AssessmentNotFound",
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "AssessmentNotFound"
						),
					)
				)
			);
		}

		if ($assessments->tuk_id != $api_info->tuk_id){
			$code = 401;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "You are not owner of this assessment",
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "Applicant_portfolioNotFound"
						),
					)
				)
			);
		}

		$modified_by = 0;

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

	public function create_applicant_applicant_portfolio($assessment_id, $assessment_applicant_id)
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$assessments = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);

		if (empty($assessments->assessment_id)){
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "AssessmentNotFound",
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "AssessmentNotFound"
						),
					)
				)
			);
		}

		if ($assessments->tuk_id != $api_info->tuk_id){
			$code = 401;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "You are not owner of this assessment",
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "Applicant_portfolioNotFound"
						),
					)
				)
			);
		}

		$this->my_parameter["assessment_id"] = $assessment_id;
		$this->my_parameter["assessment_applicant_id"] = $assessment_applicant_id;

		$this->create_applicant_portfolio();
	}

	protected function create_applicant_portfolio($created_by = 0)
	{
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

	public function delete_hard_applicant_applicant_portfolio_by_id($assessment_id, $assessment_applicant_id, $applicant_portfolio_id)
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$assessments = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);

		if (empty($assessments->assessment_id)){
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "AssessmentNotFound",
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "AssessmentNotFound"
						),
					)
				)
			);
		}

		if ($assessments->tuk_id != $api_info->tuk_id){
			$code = 401;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "You are not owner of this assessment",
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "Applicant_portfolioNotFound"
						),
					)
				)
			);
		}

		$affected_rows = $this->delete_hard_applicant_portfolio($assessment_id, $assessment_applicant_id, $applicant_portfolio_id);

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

	protected function delete_hard_applicant_portfolio($assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $modified_by = 0)
	{
		return modules::run("Applicant_portfolio_module/delete_hard_applicant_portfolio_by_id", $assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $modified_by);
	}
}