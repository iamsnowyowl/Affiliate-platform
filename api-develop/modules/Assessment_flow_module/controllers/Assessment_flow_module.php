<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment_flow_module extends MX_Controller {

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

	public function check($parameter = array(), $check, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_flow_model");

		$assessment_flow = $this->Assessment_flow_model->check($check, $graph);

		if (!isset($assessment_flow))
		{
			modules::run("Error_module/set_error", "Assessment_flow not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $assessment_flow;
	}

	public function get_assessment_flow_by_id($parameter = array(), $assessment_flow_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_flow_model");

		$assessment_flow = $this->Assessment_flow_model->get_assessment_flow_by_id($assessment_flow_id, $graph);

		if (!isset($assessment_flow))
		{
			modules::run("Error_module/set_error", "Assessment_flow not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $assessment_flow;
	}

	public function get_assessment_flow_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_flow_model");

		$assessment_flow = $this->Assessment_flow_model->get_assessment_flow_list($graph);
		$assessment_flow_count = $this->get_assessment_flow_count($parameter);
		$graph_pagination = $this->get_graph_pagination($assessment_flow_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $assessment_flow_count->count,
			'data' => $assessment_flow,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_assessment_flow_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_flow_model");

		$assessment_flow_count = $this->Assessment_flow_model->get_assessment_flow_count($graph);

		return $assessment_flow_count;
	}

	public function create_assessment_flow($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (!$this->configuration["pk_use_ai"]){
			$this->my_parameter["assessment_flow_id"] = guidv4(random_bytes(16));
		}

		if ($this->validate_input("create_assessment_flow") === FALSE) return FALSE;
		
		// check is assessment_flow already created or not
		if ($this->configuration["check_unique"])
		{
			$check = modules::run("Assessment_flow_module/check", array("assessment_id" => $this->my_parameter["assessment_id"]), $this->my_parameter['flow_name']);
			if (!empty($check->assessment_flow_id)){
				modules::run("Error_module/set_error", "Assessment_flow already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		$this->load->model("Assessment_flow_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$assessment_flow_id = $this->Assessment_flow_model->create_assessment_flow($this->my_parameter, $auto_commit);

	 	return (!$this->configuration["pk_use_ai"] && !empty($assessment_flow_id)) ? $this->my_parameter["assessment_flow_id"] : $assessment_flow_id; 
	}

	public function update_assessment_flow_by_id($assessment_id, $assessment_flow_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_assessment_flow") === FALSE) return FALSE;
		
		$this->load->model("Assessment_flow_model");

		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Assessment_flow_model->update_assessment_flow_by_id($this->my_parameter, $assessment_id, $assessment_flow_id, $auto_commit);

		return $affected_row;
	}

	public function delete_soft_assessment_flow_by_id($assessment_id, $assessment_flow_id, $auto_commit = TRUE)
	{
		if (!is_array($assessment_flow_id))
		{
			$assessment_flow_id = array_map("trim", explode(",", $assessment_flow_id));
		}

		$assessment_flows = array();
		$now = date('Y-m-d H:i:s');

		foreach ($assessment_flow_id as $key => $value) {
			$assessment_flows[$key] = array(
				'assessment_id' => $assessment_id,
				'assessment_flow_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Assessment_flow_model");

		$affected_row = $this->Assessment_flow_model->delete_soft_assessment_flow_by_id($assessment_flows, $auto_commit);

		return $affected_row;
	}

	public function delete_hard_assessment_flow_by_id($assessment_id, $assessment_flow_id, $confirmation, $auto_commit = TRUE)
	{
		$this->load->model("Assessment_flow_model");

		if ($this->configuration["hard_delete_word"] == "flow_name")
		{
			$parameter = array(
				"assessment_id" => $assessment_id,
				"flow_name" => $confirmation,
			);

			$assessment_flow = $this->get_assessment_flow_by_id($parameter, $assessment_flow_id);

			if (empty($assessment_flow->assessment_flow_id)) {
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

		$affected_row = $this->Assessment_flow_model->delete_hard_assessment_flow_by_id($assessment_id, $assessment_flow_id, $auto_commit);

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
}