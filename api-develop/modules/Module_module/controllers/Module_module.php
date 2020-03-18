<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module_module extends MX_Controller {

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

	public function get_module_by_name($parameter = array(), $name, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Module_model");

		$module = $this->Module_model->get_module_by_name($name, $graph->select);

		if (!isset($module))
		{
			modules::run("Error_module/set_error", "Module not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $module;
	}

	public function get_module_by_id($parameter = array(), $module_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Module_model");

		$module = $this->Module_model->get_module_by_id($module_id, $graph->select);

		if (!isset($module))
		{
			modules::run("Error_module/set_error", "Module not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $module;
	}

	public function get_module_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Module_model");

		$module = $this->Module_model->get_module_list($graph);
		$module_count = $this->get_module_count($parameter);
		$graph_pagination = $this->get_graph_pagination($module_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $module_count->count,
			'data' => $module,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_module_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Module_model");

		$module_count = $this->Module_model->get_module_count($graph);

		return $module_count;
	}

	public function create_module($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if ($this->validate_input("create_module") === FALSE) return FALSE;
		
		// check is module already created or not
		$check = modules::run("Module_module/get_module_by_name", NULL, $this->my_parameter['module_name']);

		if (!empty($check->module_id))
		{
			modules::run("Error_module/set_error", "Module already exist");
			modules::run("Error_module/set_error_code", 409);
			return FALSE;
		}

		$this->load->model("Module_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$module_id = $this->Module_model->create_module($this->my_parameter, $auto_commit);

		return $module_id;
	}

	public function update_module_by_id($module_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_module") === FALSE) return FALSE;
		
		$this->load->model("Module_model");

		// add extra parameter
		$module_id = intval($module_id);
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Module_model->update_module_by_id($this->my_parameter, $module_id, $auto_commit);

		return $affected_row;
	}

	public function delete_module_by_id($module_id, $auto_commit = TRUE)
	{
		if (!is_array($module_id))
		{
			$module_id = array_map("trim", explode(",", $module_id));
		}

		$module_id = array_map("intval", $module_id);

		$modules = array();
		$now = date('Y-m-d H:i:s');

		foreach ($module_id as $key => $value) {
			$modules[$key] = array(
				'module_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Module_model");

		$affected_row = $this->Module_model->delete_module_by_id($modules, $auto_commit);

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