<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_module extends MX_Controller {

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

	public function get_setting_by_name($parameter = array(), $name, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Setting_model");

		$setting = $this->Setting_model->get_setting_by_name($name, $graph->select);

		if (!isset($setting))
		{
			modules::run("Error_module/set_error", "Setting not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $setting;
	}

	public function get_setting_by_id($parameter = array(), $setting_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Setting_model");

		$setting = $this->Setting_model->get_setting_by_id($setting_id, $graph->select);

		if (!isset($setting))
		{
			modules::run("Error_module/set_error", "Setting not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $setting;
	}

	public function get_setting_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Setting_model");

		$setting = $this->Setting_model->get_setting_list($graph);
		$setting_count = $this->get_setting_count($parameter);
		$graph_pagination = $this->get_graph_pagination($setting_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $setting_count->count,
			'data' => $setting,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_setting_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Setting_model");

		$setting_count = $this->Setting_model->get_setting_count($graph);

		return $setting_count;
	}

	public function create_setting($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if ($this->validate_input("create_setting") === FALSE) return FALSE;
		
		// check is setting already created or not
		$check = modules::run("Setting_module/get_setting_by_name", NULL, $this->my_parameter['setting_name']);

		if (!empty($check->setting_id))
		{
			modules::run("Error_module/set_error", "Setting already exist");
			modules::run("Error_module/set_error_code", 409);
			return FALSE;
		}

		$this->load->model("Setting_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$setting_id = $this->Setting_model->create_setting($this->my_parameter, $auto_commit);

		return $setting_id;
	}

	public function update_setting_by_id($setting_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_setting") === FALSE) return FALSE;
		
		$this->load->model("Setting_model");

		// add extra parameter
		$setting_id = intval($setting_id);
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Setting_model->update_setting_by_id($this->my_parameter, $setting_id, $auto_commit);

		return $affected_row;
	}

	public function delete_setting_by_id($setting_id, $auto_commit = TRUE)
	{
		if (!is_array($setting_id))
		{
			$setting_id = array_map("trim", explode(",", $setting_id));
		}

		$setting_id = array_map("intval", $setting_id);

		$settings = array();
		$now = date('Y-m-d H:i:s');

		foreach ($setting_id as $key => $value) {
			$settings[$key] = array(
				'setting_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Setting_model");

		$affected_row = $this->Setting_model->delete_setting_by_id($settings, $auto_commit);

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