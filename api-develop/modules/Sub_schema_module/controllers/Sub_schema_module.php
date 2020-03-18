<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_schema_module extends MX_Controller {

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
		$this->load->model("Sub_schema_model");

		$sub_schema = $this->Sub_schema_model->check($check, $graph);

		if (!isset($sub_schema))
		{
			modules::run("Error_module/set_error", "Sub_schema not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $sub_schema;
	}

	public function get_sub_schema_by_id($parameter = array(), $sub_schema_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Sub_schema_model");

		$sub_schema = $this->Sub_schema_model->get_sub_schema_by_id($sub_schema_id, $graph);

		if (!isset($sub_schema))
		{
			modules::run("Error_module/set_error", "Sub_schema not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $sub_schema;
	}

	public function get_sub_schema_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Sub_schema_model");

		$sub_schema = $this->Sub_schema_model->get_sub_schema_list($graph);
		$sub_schema_count = $this->get_sub_schema_count($parameter);
		$graph_pagination = $this->get_graph_pagination($sub_schema_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $sub_schema_count->count,
			'data' => $sub_schema,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_sub_schema_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Sub_schema_model");

		$sub_schema_count = $this->Sub_schema_model->get_sub_schema_count($graph);

		return $sub_schema_count;
	}

	public function get_full_schema_by_id($parameter = array(), $sub_schema_id, $default = "default_full_schema", $optional = "optional_full_schema")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Sub_schema_model");

		$sub_schema = $this->Sub_schema_model->get_full_schema_by_id($sub_schema_id, $graph);

		if (!isset($sub_schema))
		{
			modules::run("Error_module/set_error", "Sub_schema not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $sub_schema;
	}

	public function get_full_schema_list($parameter = array(), $default = "default_full_schema", $optional = "optional_full_schema")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Sub_schema_model");

		$sub_schema = $this->Sub_schema_model->get_full_schema_list($graph);
		$sub_schema_count = $this->get_full_schema_count($parameter);
		$graph_pagination = $this->get_graph_pagination($sub_schema_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $sub_schema_count->count,
			'data' => $sub_schema,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_full_schema_count($parameter = array(), $default = "default_full_schema", $optional = "optional_full_schema")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Sub_schema_model");

		$sub_schema_count = $this->Sub_schema_model->get_full_schema_count($graph);

		return $sub_schema_count;
	}

	public function get_full_schema_list_tree($parameter = array(), $default = "default_full_schema", $optional = "optional_full_schema")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Sub_schema_model");

		$sub_schema = $this->Sub_schema_model->get_full_schema_list($graph);
		$sub_schema_count = $this->get_full_schema_count($parameter);
		$graph_pagination = $this->get_graph_pagination($sub_schema_count->count);

		for ($i=0; $i < count($sub_schema); $i++) { 
			$unit_competence = modules::run("Unit_competence_module/get_unit_competence_list", ["limit" => 100, "sub_schema_number" => $sub_schema[$i]->sub_schema_number]);
			$sub_schema[$i]->children = (!empty($unit_competence["data"])) ? $unit_competence["data"] : [];
		}

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $sub_schema_count->count,
			'data' => $sub_schema,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function create_sub_schema($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (!$this->configuration["pk_use_ai"]){
			$this->my_parameter["sub_schema_id"] = guidv4(random_bytes(16));
		}

		if ($this->validate_input("create_sub_schema") === FALSE) return FALSE;
		
		// check is sub_schema already created or not
		if ($this->configuration["check_unique"])
		{
			$check = modules::run("Sub_schema_module/check", array("schema_id" => $this->my_parameter["schema_id"]), $this->my_parameter['sub_schema_name']);
			if (!empty($check->sub_schema_id)){
				modules::run("Error_module/set_error", "Sub_schema already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		$this->load->model("Sub_schema_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$sub_schema_id = $this->Sub_schema_model->create_sub_schema($this->my_parameter, $auto_commit);

	 	return (!$this->configuration["pk_use_ai"] && !empty($sub_schema_id)) ? $this->my_parameter["sub_schema_id"] : $sub_schema_id; 
	}

	public function update_sub_schema_by_id($schema_id, $sub_schema_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_sub_schema") === FALSE) return FALSE;

		$this->load->model("Transaction_model");

		$this->Transaction_model->trans_start();
		
		$this->load->model("Sub_schema_model");
		
		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		if (isset($this->my_parameter["sub_schema_number"]) && $this->my_parameter["sub_schema_number"] == "") {
			modules::run("Error_module/set_error", "sub_schema_number tidak boleh kosong");
			modules::run("Error_module/set_error_code", 400);
			return FALSE;
		}

		if (!empty($this->my_parameter["sub_schema_number"])){
			$sub_schema_data = $this->get_sub_schema_by_id([], $sub_schema_id);

			if (!empty($sub_schema_data->sub_schema_number)) {
				modules::run("Assessment_module/update_assessment", ["sub_schema_number" => $this->my_parameter["sub_schema_number"]], ["sub_schema_number" => $sub_schema_data->sub_schema_number], FALSE);
				modules::run("Assessment_applicant_module/update_assessment_applicant", ["sub_schema_number" => $this->my_parameter["sub_schema_number"]], ["sub_schema_number" => $sub_schema_data->sub_schema_number], FALSE);
				modules::run("Applicant_portfolio_module/update_applicant_portfolio", ["sub_schema_number" => $this->my_parameter["sub_schema_number"]], ["sub_schema_number" => $sub_schema_data->sub_schema_number], FALSE);
				modules::run("Join_request_module/update_join_request", ["sub_schema_number" => $this->my_parameter["sub_schema_number"]], ["sub_schema_number" => $sub_schema_data->sub_schema_number], FALSE);
			}

		}

		$affected_row = $this->Sub_schema_model->update_sub_schema_by_id($this->my_parameter, $schema_id, $sub_schema_id, FALSE);
		
		// update on assessment
		$this->Transaction_model->trans_complete();

		return $affected_row;
	}

	public function delete_soft_sub_schema_by_id($schema_id, $sub_schema_number, $auto_commit = TRUE)
	{
		if (!is_array($sub_schema_number))
		{
			$sub_schema_number = array_map("trim", explode(",", $sub_schema_number));
		}

		// check is there any assessment use this sub schema. if yes then don't delete if assessment is still on progress
		for ($i=0; $i < count($sub_schema_number); $i++) { 
			$assessments = modules::run("Assessment_module/get_assessment_list", ["sub_schema_number" => $sub_schema_number[$i], "-last_activity_state" => "COMPLETED"]);
			if (!empty($assessments["data"][0])) {
				modules::run("Error_module/set_error", "Sedang ada assessment berjalan pada skema dengan nomor:". $sub_schema_number[$i].". tidak dapat menghapus skema");
				modules::run("Error_module/set_error_code", 400);
				return FALSE;
			}
		}

		$sub_schemas = array();
		$now = date('Y-m-d H:i:s');

		foreach ($sub_schema_number as $key => $value) {
			$sub_schemas[$key] = array(
				'schema_id' => $schema_id,
				'sub_schema_number' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Sub_schema_model");

		$affected_row = $this->Sub_schema_model->delete_soft_sub_schema_by_id($sub_schemas, $auto_commit);

		return $affected_row;
	}

	public function delete_hard_sub_schema_by_id($schema_id, $sub_schema_id, $confirmation, $auto_commit = TRUE)
	{
		$this->load->model("Sub_schema_model");

		if ($this->configuration["hard_delete_word"] == "sub_schema_name")
		{
			$parameter = array(
				"schema_id" => $schema_id,
				"sub_schema_name" => $confirmation,
			);

			$sub_schema = $this->get_sub_schema_by_id($parameter, $sub_schema_id);

			if (empty($sub_schema->sub_schema_id)) {
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

		$affected_row = $this->Sub_schema_model->delete_hard_sub_schema_by_id($schema_id, $sub_schema_id, $auto_commit);

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