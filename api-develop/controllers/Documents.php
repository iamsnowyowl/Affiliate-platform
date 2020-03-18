<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function view_integrity_pact()
	{
		$this->output->set_output(modules::run("Document_module/view_integrity_pact"))->set_content_type("text/html; charset=UTF-8")->_display();
	}

	public function get_own_integrity_pact()
	{
		$this->output->set_status_header(200)->set_content_type('text/html', 'utf-8')->set_output($this->_get_integrity_pact_by_user_id($this->userdata['user_id']))->_display();
		exit;
	}

	public function get_integrity_pact_by_user_id($user_id)
	{
		$this->output->set_status_header(200)->set_content_type('text/html', 'utf-8')->set_output($this->_get_integrity_pact_by_user_id($user_id))->_display();
		exit;
	}

	public function sign_own_integrity_pact_signature()
	{
		if (empty($this->parameter["longitude"]) && empty($this->parameter["latitude"]))
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "longitude and latitude field required",
						"errors" => array(
							"domain" => "DOCUMENT",
							"reason" => "SignDocumentException"
						),
					)
				)
			);
		}

		$affected_row = modules::run("Document_module/sign_own_integrity_pact_signature", intval($this->userdata["user_id"]), $this->parameter["latitude"], $this->parameter["longitude"]);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "DOCUMENT",
							"reason" => "SignDocumentException"
						),
					)
				)
			);
		}
		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function _get_integrity_pact_by_user_id($user_id)
	{
		return modules::run("Document_module/get_integrity_pact_by_user_id", intval($user_id));
	}

	public function view_assessment_assignment($assessment_id, $user_id)
	{
		$data = modules::run("User_module/get_user_by_id", array(), $user_id);
		$this->output->set_output(modules::run("Document_module/view_assessment_assignment", $data));
	}
}


