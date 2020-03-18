<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_competence_module extends MX_Controller {

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

	public function check($parameter = array(), $sub_schema_number, $unit_code, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Unit_competence_model");

		$unit_competence = $this->Unit_competence_model->check($sub_schema_number, $unit_code, $graph);

		if (!isset($unit_competence))
		{
			modules::run("Error_module/set_error", "Unit_competence not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $unit_competence;
	}

	public function get_unit_competence_by_id($parameter = array(), $unit_competence_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Unit_competence_model");

		$unit_competence = $this->Unit_competence_model->get_unit_competence_by_id($unit_competence_id, $graph);

		if (!isset($unit_competence))
		{
			modules::run("Error_module/set_error", "Unit_competence not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $unit_competence;
	}

	public function get_unit_competence_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Unit_competence_model");

		$unit_competence = $this->Unit_competence_model->get_unit_competence_list($graph);
		$unit_competence_count = $this->get_unit_competence_count($parameter);
		$graph_pagination = $this->get_graph_pagination($unit_competence_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $unit_competence_count->count,
			'data' => $unit_competence,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_unit_competence_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Unit_competence_model");

		$unit_competence_count = $this->Unit_competence_model->get_unit_competence_count($graph);

		return $unit_competence_count;
	}

	public function create_unit_competence($parameter = array(), $created_by = 0, $validation_name = "create_unit_competence", $auto_commit = TRUE)
	{
		if (empty($parameter["unit_competence"]) || !is_array($parameter["unit_competence"])){
			modules::run("Error_module/set_error", "Unit_competence field required and it must in array of object");
			modules::run("Error_module/set_error_code", 400);
			return FALSE;
		}

		for ($i=0; $i < count($parameter["unit_competence"]); $i++) { 
			if (!$this->configuration["pk_use_ai"]){
				$parameter["unit_competence"][$i]["unit_competence_id"] = guidv4(random_bytes(16));
			}

			$parameter["unit_competence"][$i]["sub_schema_number"] = $parameter["sub_schema_number"];

			$this->my_parameter = $parameter["unit_competence"][$i];

			if ($this->validate_input($validation_name) === FALSE) return FALSE;

			$parameter["unit_competence"][$i] = $this->my_parameter; // since my_parameter contain cast value 

			// check is unit_competence already created or not
			if ($this->configuration["check_unique"])
			{
				$check = modules::run("Unit_competence_module/check", NULL, $this->my_parameter['sub_schema_number'], $this->my_parameter['unit_code']);
				if (!empty($check->unit_competence_id)){
					modules::run("Error_module/set_error", "Unit_competence already exist");
					modules::run("Error_module/set_error_code", 409);
					return FALSE;
				}
			}
		}
		
		$this->load->model("Unit_competence_model");

		$unit_competence_id = [];

		for ($i=0; $i < count($parameter["unit_competence"]); $i++) { 
			$parameter["unit_competence"][$i]['created_by'] = intval($created_by);
			$id = $this->Unit_competence_model->create_unit_competence($parameter["unit_competence"][$i], $auto_commit);
			$unit_competence_id[] = ($this->configuration["pk_use_ai"]) ? $id : $parameter["unit_competence"][$i]["unit_competence_id"];
		}

	 	return $unit_competence_id; 
	}

	public function update_unit_competence_by_id($unit_competence_id, $parameter, $modified_by = 0, $validation_name = "update_unit_competence", $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input($validation_name) === FALSE) return FALSE;
		
		$this->load->model("Unit_competence_model");

		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Unit_competence_model->update_unit_competence_by_id($this->my_parameter, $unit_competence_id, $auto_commit);

		return $affected_row;
	}

	public function delete_soft_unit_competence_by_id($unit_competence_id, $auto_commit = TRUE)
	{
		if (!is_array($unit_competence_id))
		{
			$unit_competence_id = array_map("trim", explode(",", $unit_competence_id));
		}

		$unit_competences = array();
		$now = date('Y-m-d H:i:s');

		foreach ($unit_competence_id as $key => $value) {
			$unit_competences[$key] = array(
				'unit_competence_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Unit_competence_model");

		$affected_row = $this->Unit_competence_model->delete_soft_unit_competence_by_id($unit_competences, $auto_commit);

		return $affected_row;
	}

	public function delete_hard_unit_competence_by_id($unit_competence_id, $confirmation, $auto_commit = TRUE)
	{
		$this->load->model("Unit_competence_model");

		if ($this->configuration["hard_delete_word"] == "unit_code")
		{
			$parameter = array("unit_code" => $confirmation);

			$unit_competence = $this->get_unit_competence_by_id($parameter, $unit_competence_id);

			if (empty($unit_competence->unit_competence_id)) {
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

		$affected_row = $this->Unit_competence_model->delete_hard_unit_competence_by_id($unit_competence_id, $auto_commit);

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