<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_trail_module extends MX_Controller {

	protected $error;
	protected $error_code;
	protected $definition;
	protected $rules;
	protected $my_parameter;
	protected $node;


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

	public function get_audit_trail_by_name($parameter = array(), $name, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Audit_trail_model");

		$audit_trail = $this->Audit_trail_model->get_audit_trail_by_name($name, $graph->select);

		if (!isset($audit_trail))
		{
			modules::run("Error_module/set_error", "Audit_trail not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $audit_trail;
	}

	public function get_audit_trail_by_id($parameter = array(), $audit_trail_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Audit_trail_model");

		$audit_trail = $this->Audit_trail_model->get_audit_trail_by_id($audit_trail_id, $graph->select);

		if (!isset($audit_trail))
		{
			modules::run("Error_module/set_error", "Audit_trail not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $audit_trail;
	}

	public function get_audit_trail_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Audit_trail_model");

		$audit_trail = $this->Audit_trail_model->get_audit_trail_list($graph);
		$audit_trail_count = $this->get_audit_trail_count($parameter);
		$graph_pagination = $this->get_graph_pagination($audit_trail_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $audit_trail_count->count,
			'data' => $audit_trail,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_audit_trail_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Audit_trail_model");

		$audit_trail_count = $this->Audit_trail_model->get_audit_trail_count($graph);

		return $audit_trail_count;
	}

	public function create_audit_trail($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if ($this->validate_input("create_audit_trail") === FALSE) return FALSE;
		
		// check is audit_trail already created or not
		$check = modules::run("Audit_trail_module/get_audit_trail_by_name", NULL, $this->my_parameter['audit_trail_name']);

		if (!empty($check->audit_trail_id))
		{
			modules::run("Error_module/set_error", "Audit_trail already exist");
			modules::run("Error_module/set_error_code", 409);
			return FALSE;
		}

		$this->load->model("Audit_trail_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$audit_trail_id = $this->Audit_trail_model->create_audit_trail($this->my_parameter, $auto_commit);

		return $audit_trail_id;
	}

	public function update_audit_trail_by_id($audit_trail_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_audit_trail") === FALSE) return FALSE;
		
		$this->load->model("Audit_trail_model");

		// add extra parameter
		$audit_trail_id = intval($audit_trail_id);
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Audit_trail_model->update_audit_trail_by_id($this->my_parameter, $audit_trail_id, $auto_commit);

		return $affected_row;
	}

	public function delete_audit_trail_by_id($audit_trail_id, $auto_commit = TRUE)
	{
		if (!is_array($audit_trail_id))
		{
			$audit_trail_id = array_map("trim", explode(",", $audit_trail_id));
		}

		$audit_trail_id = array_map("intval", $audit_trail_id);

		$audit_trails = array();
		$now = date('Y-m-d H:i:s');

		foreach ($audit_trail_id as $key => $value) {
			$audit_trails[$key] = array(
				'audit_trail_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Audit_trail_model");

		$affected_row = $this->Audit_trail_model->delete_audit_trail_by_id($audit_trails, $auto_commit);

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