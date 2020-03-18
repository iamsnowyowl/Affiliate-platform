<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schema_permission_module extends MX_Controller {

	protected $error;
	protected $error_code;
	protected $definition;
	protected $rules;
	protected $node;
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
		$definition_name = 'definition_'.strtolower(get_class($this));
		$rules_name = 'form_validation_'.strtolower(get_class($this));
		$this->config->load($definition_name, TRUE, TRUE);
		$this->config->load($rules_name, TRUE, TRUE);
		$this->definition = $this->config->item($definition_name);
		$this->rules = $this->config->item($rules_name);
		$this->node = strtolower(get_class($this));
	}

	public function get_schema_permission_by_name($parameter = array(), $name, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Schema_permission_model");

		$schema_permission = $this->Schema_permission_model->get_schema_permission_by_name($name, $graph->select);

		if (!isset($schema_permission))
		{
			modules::run("Error_module/set_error", "Schema_permission not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $schema_permission;
	}

	public function get_schema_permission_by_id($parameter = array(), $schema_permission_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Schema_permission_model");

		$schema_permission = $this->Schema_permission_model->get_schema_permission_by_id($schema_permission_id, $graph->select);

		if (!isset($schema_permission))
		{
			modules::run("Error_module/set_error", "Schema_permission not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $schema_permission;
	}

	public function get_schema_permission_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Schema_permission_model");

		$schema_permission = $this->Schema_permission_model->get_schema_permission_list($graph);
		$schema_permission_count = $this->get_schema_permission_count($parameter);
		$graph_pagination = $this->get_graph_pagination($schema_permission_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $schema_permission_count->count,
			'data' => $schema_permission,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_schema_permission_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Schema_permission_model");

		$schema_permission_count = $this->Schema_permission_model->get_schema_permission_count($graph);

		return $schema_permission_count;
	}

	public function create_schema_permission($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if ($this->validate_input("create_schema_permission") === FALSE) return FALSE;
		
		// check is schema_permission already created or not
		$check = modules::run("Schema_permission_module/get_schema_permission_by_name", NULL, $this->my_parameter['schema_permission_name']);

		if (!empty($check->schema_permission_id))
		{
			modules::run("Error_module/set_error", "Schema_permission already exist");
			modules::run("Error_module/set_error_code", 409);
			return FALSE;
		}

		$this->load->model("Schema_permission_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$schema_permission_id = $this->Schema_permission_model->create_schema_permission($this->my_parameter, $auto_commit);

		return $schema_permission_id;
	}

	public function update_schema_permission_by_id($schema_permission_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_schema_permission") === FALSE) return FALSE;
		
		$this->load->model("Schema_permission_model");

		// add extra parameter
		$schema_permission_id = intval($schema_permission_id);
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Schema_permission_model->update_schema_permission_by_id($this->my_parameter, $schema_permission_id, $auto_commit);

		return $affected_row;
	}

	public function delete_schema_permission_by_id($schema_permission_id, $auto_commit = TRUE)
	{
		if (!is_array($schema_permission_id))
		{
			$schema_permission_id = array_map("trim", explode(",", $schema_permission_id));
		}

		$schema_permission_id = array_map("intval", $schema_permission_id);

		$schema_permissions = array();
		$now = date('Y-m-d H:i:s');

		foreach ($schema_permission_id as $key => $value) {
			$schema_permissions[$key] = array(
				'schema_permission_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Schema_permission_model");

		$affected_row = $this->Schema_permission_model->delete_schema_permission_by_id($schema_permissions, $auto_commit);

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
		$this->form_validation->set_rules($this->rules[$group]);
		if (!empty($extra_rules)) $this->form_validation->set_rules($extra_rules);
		$this->form_validation->set_data($this->my_parameter, TRUE);

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