<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kuk_module extends MX_Controller {

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
		$this->load->model("Kuk_model");

		$kuk = $this->Kuk_model->check($check, $graph);

		if (!isset($kuk))
		{
			modules::run("Error_module/set_error", "Kuk not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $kuk;
	}

	public function get_kuk_by_id($parameter = array(), $kuk_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Kuk_model");

		$kuk = $this->Kuk_model->get_kuk_by_id($kuk_id, $graph);

		if (!isset($kuk))
		{
			modules::run("Error_module/set_error", "Kuk not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $kuk;
	}

	public function get_kuk_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Kuk_model");

		$kuk = $this->Kuk_model->get_kuk_list($graph);
		$kuk_count = $this->get_kuk_count($parameter);
		$graph_pagination = $this->get_graph_pagination($kuk_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $kuk_count->count,
			'data' => $kuk,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_kuk_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Kuk_model");

		$kuk_count = $this->Kuk_model->get_kuk_count($graph);

		return $kuk_count;
	}

	public function create_kuk($parameter = array(), $created_by = 0, $validation_name = "create_kuk", $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (!$this->configuration["pk_use_ai"]){
			$this->my_parameter["kuk_id"] = guidv4(random_bytes(16));
		}

		if ($this->validate_input($validation_name) === FALSE) return FALSE;
		
		// check is kuk already created or not
		if ($this->configuration["check_unique"])
		{
			$check = modules::run("Kuk_module/check", NULL, $this->my_parameter['kuk_name']);
			if (!empty($check->kuk_id)){
				modules::run("Error_module/set_error", "Kuk already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		$this->load->model("Kuk_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$kuk_id = $this->Kuk_model->create_kuk($this->my_parameter, $auto_commit);

	 	return (!$this->configuration["pk_use_ai"] && !empty($kuk_id)) ? $this->my_parameter["kuk_id"] : $kuk_id; 
	}

	public function update_kuk_by_id($kuk_id, $parameter, $modified_by = 0, $validation_name = "update_kuk", $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input($validation_name) === FALSE) return FALSE;
		
		$this->load->model("Kuk_model");

		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Kuk_model->update_kuk_by_id($this->my_parameter, $kuk_id, $auto_commit);

		return $affected_row;
	}

	public function delete_soft_kuk_by_id($kuk_id, $auto_commit = TRUE)
	{
		if (!is_array($kuk_id))
		{
			$kuk_id = array_map("trim", explode(",", $kuk_id));
		}

		$kuks = array();
		$now = date('Y-m-d H:i:s');

		foreach ($kuk_id as $key => $value) {
			$kuks[$key] = array(
				'kuk_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Kuk_model");

		$affected_row = $this->Kuk_model->delete_soft_kuk_by_id($kuks, $auto_commit);

		return $affected_row;
	}

	public function delete_hard_kuk_by_id($kuk_id, $confirmation, $auto_commit = TRUE)
	{
		$this->load->model("Kuk_model");

		if ($this->configuration["hard_delete_word"] == "kuk_name")
		{
			$parameter = array("kuk_name" => $confirmation);

			$kuk = $this->get_kuk_by_id($parameter, $kuk_id);

			if (empty($kuk->kuk_id)) {
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

		$affected_row = $this->Kuk_model->delete_hard_kuk_by_id($kuk_id, $auto_commit);

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