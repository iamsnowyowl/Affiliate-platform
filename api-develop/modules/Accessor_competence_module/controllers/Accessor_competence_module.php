<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accessor_competence_module extends MX_Controller {

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

	public function get_accessor_competence_by_user_id_and_cfc($parameter = array(), $competence_field_code, $user_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Accessor_competence_model");

		$accessor_competence = $this->Accessor_competence_model->get_accessor_competence_by_user_id_and_cfc($competence_field_code, $user_id, $graph->select);

		if (!isset($accessor_competence))
		{
			modules::run("Error_module/set_error", "Accessor_competence not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		if (!empty($accessor_competence->certificate_file)) $accessor_competence->certificate_file = getenv("BASE_FILE_PATH").$accessor_competence->certificate_file;

		return $accessor_competence;
	}

	public function get_accessor_competence_by_id($parameter = array(), $accessor_competence_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Accessor_competence_model");

		$accessor_competence = $this->Accessor_competence_model->get_accessor_competence_by_id($accessor_competence_id, $graph->select);

		if (!isset($accessor_competence))
		{
			modules::run("Error_module/set_error", "Accessor_competence not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		if (!empty($accessor_competence->certificate_file)) $accessor_competence->certificate_file = getenv("BASE_FILE_PATH").$accessor_competence->certificate_file;

		return $accessor_competence;
	}

	public function get_accessor_competence_by_code($parameter = array(), $accessor_competence_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Accessor_competence_model");

		$accessor_competence = $this->Accessor_competence_model->get_accessor_competence_by_code($accessor_competence_id, $graph->select);
		if (!isset($accessor_competence))
		{
			modules::run("Error_module/set_error", "Accessor_competence not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		
		if (!empty($accessor_competence->certificate_file)) $accessor_competence->certificate_file = getenv("BASE_FILE_PATH").$accessor_competence->certificate_file;

		return $accessor_competence;
	}

	public function get_accessor_competence_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Accessor_competence_model");

		$accessor_competence = $this->Accessor_competence_model->get_accessor_competence_list($graph);

		for ($i=0; $i < count($accessor_competence); $i++) { 
			$accessor_competence[$i]->certificate_file = getenv("BASE_FILE_PATH").$accessor_competence[$i]->certificate_file;
		}
		$accessor_competence_count = $this->get_accessor_competence_count($parameter);
		$graph_pagination = $this->get_graph_pagination($accessor_competence_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $accessor_competence_count->count,
			'data' => $accessor_competence,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_accessor_competence_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Accessor_competence_model");

		$accessor_competence_count = $this->Accessor_competence_model->get_accessor_competence_count($graph);

		return $accessor_competence_count;
	}

	public function create_accessor_competence($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if ($this->validate_input("create_accessor_competence") === FALSE) return FALSE;
		
		// check is accessor_competence already created or not
		// $check = modules::run("Accessor_competence_module/get_accessor_competence_by_user_id_and_cfc", NULL, $this->my_parameter['competence_field_code'], $this->my_parameter['user_id']);

		// if (!empty($check->accessor_competence_id))
		// {
		// 	modules::run("Error_module/set_error", "Accessor_competence already exist");
		// 	modules::run("Error_module/set_error_code", 409);
		// 	return FALSE;
		// }

		$this->load->model("Accessor_competence_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$accessor_competence_id = $this->Accessor_competence_model->create_accessor_competence($this->my_parameter, $auto_commit);

		return $accessor_competence_id;
	}

	public function update_accessor_competence_by_id($accessor_competence_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_accessor_competence") === FALSE) return FALSE;
		
		$this->load->model("Accessor_competence_model");

		// add extra parameter
		$accessor_competence_id = intval($accessor_competence_id);
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Accessor_competence_model->update_accessor_competence_by_id($this->my_parameter, $accessor_competence_id, $auto_commit);

		return $affected_row;
	}

	public function delete_accessor_competence_by_code($accessor_competence_code, $auto_commit = TRUE)
	{
		if (!is_array($accessor_competence_code))
		{
			$accessor_competence_code = array_map("trim", explode(",", $accessor_competence_code));
		}

		$accessor_competence_code = array_map("intval", $accessor_competence_code);

		$accessor_competences = array();
		$now = date('Y-m-d H:i:s');

		foreach ($accessor_competence_code as $key => $value) {
			$accessor_competences[$key] = array(
				'accessor_competence_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Accessor_competence_model");

		$affected_row = $this->Accessor_competence_model->delete_accessor_competence_by_code($accessor_competences, $auto_commit);

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