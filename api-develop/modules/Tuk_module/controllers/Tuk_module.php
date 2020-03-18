<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tuk_module extends MX_Controller {

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
		$this->load->model("Tuk_model");

		$tuk = $this->Tuk_model->check($check, $graph);

		if (!isset($tuk))
		{
			modules::run("Error_module/set_error", "Tuk not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $tuk;
	}

	public function get_tuk_by_id($parameter = array(), $tuk_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Tuk_model");

		$tuk = $this->Tuk_model->get_tuk_by_id($tuk_id, $graph);

		if (!isset($tuk))
		{
			modules::run("Error_module/set_error", "Tuk not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $tuk;
	}

	public function get_tuk_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Tuk_model");

		$tuk = $this->Tuk_model->get_tuk_list($graph);
		$tuk_count = $this->get_tuk_count($parameter);
		$graph_pagination = $this->get_graph_pagination($tuk_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $tuk_count->count,
			'data' => $tuk,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_tuk_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Tuk_model");

		$tuk_count = $this->Tuk_model->get_tuk_count($graph);

		return $tuk_count;
	}

	public function get_tuk_deleted_list($parameter = array(), $default = "default_deleted_list", $optional = "optional_deleted_list")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Tuk_model");

		$tuk = $this->Tuk_model->get_tuk_deleted_list($graph);
		$tuk_count = $this->get_tuk_deleted_count($parameter);
		$graph_pagination = $this->get_graph_pagination($tuk_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $tuk_count,
			'data' => $tuk,
			'pagination' => $graph_pagination
		);

		return $data;
	}

	public function get_tuk_deleted_count($parameter = array(), $default = "default_deleted_list", $optional = "optional_deleted_list")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Tuk_model");

		$tuk_count = $this->Tuk_model->get_tuk_deleted_count($graph);

		return $tuk_count;
	}

	public function create_tuk($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;
		$logo = "";

		if (!$this->configuration["pk_use_ai"]){
			$this->my_parameter["tuk_id"] = guidv4(random_bytes(16));
		}

		if (!empty($this->my_parameter["logo"])) {
			$logo = $this->my_parameter["logo"];
			unset($this->my_parameter["logo"]);
		}

		$this->my_parameter["api_key"] = generate_random_base62_string(64);

		if ($this->validate_input("create_tuk") === FALSE) return FALSE;
		
		// check is tuk already created or not
		if ($this->configuration["check_unique"])
		{
			$check = modules::run("Tuk_module/check", NULL, $this->my_parameter['tuk_name']);
			if (!empty($check->tuk_id)){
				modules::run("Error_module/set_error", "Tuk already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		$this->load->model("Tuk_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$tuk_id = $this->Tuk_model->create_tuk($this->my_parameter, $auto_commit);

		$tuk_id = (!$this->configuration["pk_use_ai"] && !empty($tuk_id)) ? $this->my_parameter["tuk_id"] : $tuk_id;

		if (!empty($logo))
		{
			// storing logo
			if (!file_exists($this->configuration["base_path"].$this->configuration["file_path"])) {
				mkdir($this->configuration["base_path"].$this->configuration["file_path"], 0755, TRUE);
			}

			$fullpath = $this->configuration["base_path"].$this->configuration["file_path"]."/".$tuk_id;

			file_put_contents($fullpath, base64_decode($logo));
			unset($logo);

			$this->update_tuk_by_id($tuk_id, array("logo" => $fullpath));
		}

	 	return $tuk_id; 
	}

	public function update_tuk_by_id($tuk_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_tuk") === FALSE) return FALSE;

		if (!empty($this->my_parameter["logo"])) 
		{
			// storing logo
			if (!file_exists($this->configuration["base_path"].$this->configuration["file_path"])) {
				mkdir($this->configuration["base_path"].$this->configuration["file_path"], 0755, TRUE);
			}

			$fullpath = $this->configuration["base_path"].$this->configuration["file_path"]."/".$tuk_id;

			file_put_contents($fullpath, base64_decode($this->my_parameter["logo"]));

			$this->my_parameter["logo"] = $fullpath;
		}
		
		$this->load->model("Tuk_model");

		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Tuk_model->update_tuk_by_id($this->my_parameter, $tuk_id, $auto_commit);

		return $affected_row;
	}

	public function delete_soft_tuk_by_id($tuk_id, $auto_commit = TRUE)
	{
		if (!is_array($tuk_id))
		{
			$tuk_id = array_map("trim", explode(",", $tuk_id));
		}

		$tuks = array();
		$now = date('Y-m-d H:i:s');

		foreach ($tuk_id as $key => $value) {
			$tuks[$key] = array(
				'tuk_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Tuk_model");

		$affected_row = $this->Tuk_model->delete_soft_tuk_by_id($tuks, $auto_commit);

		return $affected_row;
	}

	public function delete_hard_tuk_by_id($tuk_id, $confirmation, $auto_commit = TRUE)
	{
		$this->load->model("Tuk_model");

		if ($this->configuration["hard_delete_word"] == "tuk_name")
		{
			$parameter = array("tuk_name" => $confirmation);

			$tuk = $this->get_tuk_by_id($parameter, $tuk_id);

			if (empty($tuk->tuk_id)) {
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

		$affected_row = $this->Tuk_model->delete_hard_tuk_by_id($tuk_id, $auto_commit);

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

	public function update_deleted_list($tuk_id, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = array(
			"deleted_at" => "2000-01-01 00:00:00"
		);
		$this->my_parameter['modified_by'] = intval($modified_by);

		if ($this->validate_input("update_tuk") === FALSE) return FALSE;

		$this->load->model("Tuk_model");

		$affected_rows = $this->Tuk_model->update_deleted_list($tuk_id, $this->my_parameter, $auto_commit);

		return $affected_rows;
	}
}