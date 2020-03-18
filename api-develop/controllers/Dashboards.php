<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboards extends MX_Controller {
	
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

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");

		$data = modules::run("Dashboard_module/get_assessment_by_id", $this->my_parameter, $assessment_id);

		if ($data === FALSE)
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

		response(200, array_merge(array("responseStatus" => "SUCCESS"), array("data" => $data)));
	}

	public function get_assessment_list() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		
		$data = modules::run("Dashboard_module/get_assessment_list", $this->my_parameter);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}
}


