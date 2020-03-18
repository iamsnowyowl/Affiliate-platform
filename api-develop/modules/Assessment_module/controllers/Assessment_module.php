<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment_module extends MX_Controller {

	protected $error;
	protected $error_code;
	protected $definition;
	protected $rules;
	protected $configuration;
	protected $my_parameter;
	protected $node;


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
		$definition_name = 'definition_'.strtolower(get_class($this));
		$rules_name = 'form_validation_'.strtolower(get_class($this));
		$config_name = 'configuration_'.strtolower(get_class($this));
		
		$this->config->load($definition_name, TRUE, TRUE);
		$this->config->load($rules_name, TRUE, TRUE);
		$this->config->load($config_name, TRUE, TRUE);

		$this->definition = $this->config->item($definition_name);
		$this->rules = $this->config->item($rules_name);
		$this->configuration = $this->config->item($config_name);
		
		$this->node = strtolower(get_class($this));
	}

	public function notify_alladmin_request_assessment($assessment){
		// insert into fcm_broadcast list
		$data = array(
			"assessment_id" => $assessment->assessment_id,
			"time_stamp" => time()
		);

		// # code...
		// $notification = array();
		// $notification["user_id"] = 0;
		// $notification["data"] = json_encode($data);
		// $notification["click_action"] = "INCOMING_SUBMISSION";
		// $notification["title"] = "New incoming Assessment Submission";
		// $notification["message"] = "TUK send Request Submission";
		// $notification["time_stamp"] = time();

		// $notification_id = modules::run("Notification_module/create_notification", $notification);

		// if ($notification_id === FALSE)
		// {
		// 	$code = modules::run("Error_module/get_error_code");
		// 	response($code, array(
		// 			"responseStatus" => "ERROR",
		// 			"error" => array(
		// 				"code" => $code,
		// 				"message" => modules::run("Error_module/get_error"),
		// 				"errors" => array(
		// 					"domain" => "USER",
		// 					"reason" => "UserCreateErrorException",
		// 					"extra" => modules::run("Error_module/get_error_extra")
		// 				),
		// 			)
		// 		)
		// 	);
		// }

		// $new_parameter = array();
		// $new_parameter["user_id"] = 0;
		// $new_parameter["topic"] = "adminlsp";
		// $new_parameter["click_action"] = "INCOMING_SUBMISSION";
		// $new_parameter["title"] = "New incoming Assessment Submission";
		// $new_parameter["message"] = "TUK send Request Submission";

		// $data["notification_id"] = $notification_id;
		// $data["time_stamp"] = time();
		$new_parameter["data"] = json_encode($data);

		$notification = array();
		$notification["click_action"] = "INCOMING_SUBMISSION";
		$notification["title"] = "New incoming Assessment Submission";
		$notification["body"] = "TUK send Request Submission";
		return modules::run("Google_module/send_fcm_message", array(
			"data" => $data,
			"notification" => $notification
		), getenv("TOPIC_ADMIN_LSP"), TRUE);
	}

	public function check($parameter = array(), $check, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_model");

		$assessment = $this->Assessment_model->check($check, $graph);

		if (!isset($assessment))
		{
			modules::run("Error_module/set_error", "Assessment not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $assessment;
	}

	public function get_assessment_by_id($parameter = array(), $assessment_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_model");

		$assessment = $this->Assessment_model->get_assessment_by_id($assessment_id, $graph);

		if (!isset($assessment))
		{
			modules::run("Error_module/set_error", "Assessment not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $assessment;
	}

	public function get_assessment_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_model");

		$assessment = $this->Assessment_model->get_assessment_list($graph);
		$assessment_count = $this->get_assessment_count($parameter);
		$graph_pagination = $this->get_graph_pagination($assessment_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $assessment_count->count,
			'data' => $assessment,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_assessment_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_model");

		$assessment_count = $this->Assessment_model->get_assessment_count($graph);

		return $assessment_count;
	}

	public function get_assessment_deleted_list($parameter = array(), $default = "default_deleted_list", $optional = "optional_deleted_list")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_model");
		
		$assessment = $this->Assessment_model->get_assessment_deleted_list($graph);
		$assessment_count = $this->get_assessment_deleted_count($parameter);
		$graph_pagination = $this->get_graph_pagination($assessment_count->count);
		
		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $assessment_count->count,
			'data' => $assessment,
			'pagination' => $graph_pagination
		);
		
		return $data;
	}

	public function get_assessment_deleted_count($parameter = array(), $default = "default_deleted_list", $optional = "optional_deleted_list")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_model");

		$assessment_count = $this->Assessment_model->get_assessment_deleted_count($graph);

		return $assessment_count;
	}

	public function non_admin_get_assessment_by_id($parameter = array(), $assessment_id, $default = "default_non_admin", $optional = "optional_non_admin")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_model");

		$assessment = $this->Assessment_model->non_admin_get_assessment_by_id($assessment_id, $graph);

		if (!isset($assessment))
		{
			modules::run("Error_module/set_error", "Assessment not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $assessment;
	}

	public function non_admin_get_assessment_list($parameter = array(), $default = "default_non_admin", $optional = "optional_non_admin")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_model");

		$assessment = $this->Assessment_model->non_admin_get_assessment_list($graph);

		if (!empty($assessment) && $this->userdata["role_code"] == "ACS") {

			for ($i=0; $i < count($assessment); $i++) { 
				
				$count_recomendation = modules::run("Assessment_applicant_module/get_assessment_applicant_count", 
				[
					"assessment_id" => $assessment[$i]->assessment_id, 
					"assessor_id" => $this->userdata["user_id"],
					"-status_recomendation" => "NONE"
				]);
				$assessment[$i]->count_recomendation = $count_recomendation->count;

				$count_emptyrecomendation = modules::run("Assessment_applicant_module/get_assessment_applicant_count", 
				[
					"assessment_id" => $assessment[$i]->assessment_id, 
					"assessor_id" => $this->userdata["user_id"],
					"status_recomendation" => "NONE"
				]);
				$assessment[$i]->count_emptyrecomendation = $count_emptyrecomendation->count;

				$count_graduation = modules::run("Assessment_applicant_module/get_assessment_applicant_count", 
				[
					"assessment_id" => $assessment[$i]->assessment_id, 
					"-status_graduation" => "NONE"
				]);
				$assessment[$i]->count_graduation = $count_graduation = $count_graduation->count;

				$count_emptygraduation = modules::run("Assessment_applicant_module/get_assessment_applicant_count", 
				[
					"assessment_id" => $assessment[$i]->assessment_id, 
					"status_graduation" => "NONE"
				]);
				$assessment[$i]->count_emptygraduation = $count_emptygraduation->count;
			}
		}

		$assessment_count = $this->non_admin_get_assessment_count($parameter);
		$graph_pagination = $this->get_graph_pagination($assessment_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $assessment_count->count,
			'data' => $assessment,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function non_admin_get_assessment_count($parameter = array(), $default = "default_non_admin", $optional = "optional_non_admin")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_model");

		$assessment_count = $this->Assessment_model->non_admin_get_assessment_count($graph);

		return $assessment_count;
	}

	public function create_assessment($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (!$this->configuration["pk_use_ai"]){
			$this->my_parameter["assessment_id"] = guidv4(random_bytes(16));
		}

		if ($this->validate_input("create_assessment") === FALSE) return FALSE;
		
		// check is assessment already created or not
		if ($this->configuration["check_unique"])
		{
			$check = modules::run("Assessment_module/check", NULL, $this->my_parameter['title']);
			if (!empty($check->assessment_id)){
				modules::run("Error_module/set_error", "Assessment already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		// convert_date
		$this->load->model("Assessment_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);

		if (!empty($this->my_parameter["end_date"]))
		{
			$this->my_parameter["pleno_date"] = date("Y-m-d H:i:s", strtotime($this->my_parameter["end_date"]." + 1 day"));
		}
		
		$assessment_id = $this->Assessment_model->create_assessment($this->my_parameter, $auto_commit);

	 	return (!$this->configuration["pk_use_ai"] && !empty($assessment_id)) ? $this->my_parameter["assessment_id"] : $assessment_id; 
	}

	public function update_assessment_by_id($assessment_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}
		
		if ($this->validate_input("update_assessment") === FALSE) return FALSE;
		
		$this->load->model("Assessment_model");

		if (!empty($this->my_parameter["end_date"]))
		{
			$this->my_parameter["pleno_date"] = date("Y-m-d H:i:s", strtotime($this->my_parameter["end_date"]." + 1 day"));
		}

		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Assessment_model->update_assessment_by_id($this->my_parameter, $assessment_id, $auto_commit);

		return $affected_row;
	}

	public function update_assessment($parameter, $condition, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}
		
		if ($this->validate_input("update_assessment") === FALSE) return FALSE;
		
		$this->load->model("Assessment_model");

		if (!empty($this->my_parameter["end_date"]))
		{
			$this->my_parameter["pleno_date"] = date("Y-m-d H:i:s", strtotime($this->my_parameter["end_date"]." + 1 day"));
		}

		$affected_row = $this->Assessment_model->update_assessment($this->my_parameter, $condition, $auto_commit);

		return $affected_row;
	}

	public function delete_soft_assessment_by_id($assessment_id, $auto_commit = TRUE)
	{
		if (!is_array($assessment_id))
		{
			$assessment_id = array_map("trim", explode(",", $assessment_id));
		}

		$assessments = array();
		$now = date('Y-m-d H:i:s');

		foreach ($assessment_id as $key => $value) {
			$assessments[$key] = array(
				'assessment_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Assessment_model");

		$affected_row = $this->Assessment_model->delete_soft_assessment_by_id($assessments, $auto_commit);

		return $affected_row;
	}

	public function delete_hard_assessment_by_id($assessment_id, $confirmation, $auto_commit = TRUE)
	{
		$this->load->model("Assessment_model");

		if ($this->configuration["hard_delete_word"] == "title")
		{
			$parameter = array("title" => $confirmation);

			$assessment = $this->get_assessment_by_id($parameter, $assessment_id);

			if (empty($assessment->assessment_id)) {
				modules::run("Error_module/set_error", "Invalid value confirmation");
				modules::run("Error_module/set_error_code", 400);
				return FALSE;
			} 
		}
		else if ($this->configuration["hard_delete_word"] != $confirmation) {
			modules::run("Error_module/set_error", "Invalid value confirmation");
			modules::run("Error_module/set_error_code", 400);
			return FALSE;
		}

		$affected_row = $this->Assessment_model->delete_hard_assessment_by_id($assessment_id, $auto_commit);

		return $affected_row;
	}

	protected function get_graph_result($parameter = array(), $default = "default", $optional = "optional")
	{
		$default = $this->definition[$default];
		$optional = $this->definition[$optional];

		$this->load->library("graph");
		// check whether graph validation error or not
		if (!$this->graph->initialize($parameter, $default, $optional, $this->node))
		{
			response($this->graph->get_error_code(), array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $this->graph->get_error_code(),
						"message" => $this->graph->get_error(),
						"errors" => array(
							"domain" => "GRAPH_VALIDATION",
							"reason" => "GraphError"
						),
					)
				)
			);   
		}

		return $this->graph->get_compile_result($this->node);
	}

	protected function get_graph_pagination($count)
	{
		$this->load->library("graph");
		// check whether graph validation error or not
		$this->graph->initialize_pagination($this->node, $count);

		return $this->graph->get_compile_result_pagination($this->node);
	}

	protected function validate_input($group, $extra_rules = NULL)
	{
		$this->load->library('form_validation');	 	
		
		$this->form_validation->reset_validation();
		$this->form_validation->set_data($this->my_parameter, TRUE);
		$this->form_validation->set_rules($this->rules[$group]);
		if (!empty($extra_rules)) $this->form_validation->set_rules($extra_rules);

		if ($this->form_validation->run(NULL, $this->my_parameter) == FALSE)
		{
			modules::run("Error_module/set_error", "error validation on input data");
			modules::run("Error_module/set_error_code", 400);
			$extra = (!is_array($this->form_validation->error_array())) ? array('invalid_field' => $this->form_validation->error_array()) : $this->form_validation->error_array(); 
			modules::run("Error_module/set_error_extra", $extra);
			return FALSE;
		}
		return TRUE;
	}
	
	public function update_deleted_list($assessment_id, $modified_by = 0, $auto_commit = TRUE)
	{
		
		$this->my_parameter = array(
			"deleted_at" => "2000-01-01 00:00:00"
		);
		$this->my_parameter['modified_by'] = intval($modified_by);

		if ($this->validate_input("update_assessment") === FALSE) return FALSE;
		
		$this->load->model("Assessment_model");

		$affected_rows = $this->Assessment_model->update_deleted_list($assessment_id, $this->my_parameter, $auto_commit);

		return $affected_rows;
	}

}