<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment_pleno_module extends MX_Controller {

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
		$this->load->model("Assessment_pleno_model");

		$assessment_pleno = $this->Assessment_pleno_model->check($check, $graph);

		if (!isset($assessment_pleno))
		{
			modules::run("Error_module/set_error", "Assessment_pleno not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $assessment_pleno;
	}

	public function get_assessment_pleno_by_id($parameter = array(), $assessment_pleno_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_pleno_model");

		$assessment_pleno = $this->Assessment_pleno_model->get_assessment_pleno_by_id($assessment_pleno_id, $graph);
		if (isset($assessment_pleno->signature)) {
			$assessment_pleno->signature = "data:image/png;base64,".base64_encode($assessment_pleno->signature);
		}
		if (!isset($assessment_pleno))
		{
			modules::run("Error_module/set_error", "Assessment_pleno not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $assessment_pleno;
	}

	public function get_assessment_pleno_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_pleno_model");

		$assessment_pleno = $this->Assessment_pleno_model->get_assessment_pleno_list($graph);
		$ca = 1;
		foreach ($assessment_pleno as $key => $value) 
		{
			if (isset($assessment_pleno[$key]->signature)) {
				$assessment_pleno[$key]->signature = "data:image/png;base64,".base64_encode($assessment_pleno[$key]->signature);
			}

			if (isset($assessment_pleno[$key]->position) && strtolower($assessment_pleno[$key]->position) == "anggota") {
				$assessment_pleno[$key]->position .= " $ca";
				$ca++;
			}
		}

		$assessment_pleno_count = $this->get_assessment_pleno_count($parameter);
		$graph_pagination = $this->get_graph_pagination($assessment_pleno_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $assessment_pleno_count->count,
			'data' => $assessment_pleno,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_assessment_pleno_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_pleno_model");

		$assessment_pleno_count = $this->Assessment_pleno_model->get_assessment_pleno_count($graph);

		return $assessment_pleno_count;
	}

	public function create_assessment_pleno($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (!$this->configuration["pk_use_ai"]){
			$this->my_parameter["assessment_pleno_id"] = guidv4(random_bytes(16));
		}

		if ($this->validate_input("create_assessment_pleno") === FALSE) return FALSE;

		if ($this->my_parameter["position"] == "ketua"){

			$position = modules::run("Assessment_pleno_module/get_assessment_pleno_list", ["assessment_id" => $this->my_parameter["assessment_id"], "position" => "ketua"]);
			
			if(!empty($position['data']))
			{
				modules::run("Error_module/set_error", "Ketua already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}
		
		// check is assessment_pleno already created or not
		if ($this->configuration["check_unique"])
		{
			$check = modules::run("Assessment_pleno_module/check", array("assessment_id" => $this->my_parameter["assessment_id"]), $this->my_parameter['pleno_id']);
			if (!empty($check->assessment_pleno_id)){
				modules::run("Error_module/set_error", "Assessment_pleno already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		$this->load->model("Assessment_pleno_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$assessment_pleno_id = $this->Assessment_pleno_model->create_assessment_pleno($this->my_parameter, $auto_commit);

	 	return (!$this->configuration["pk_use_ai"] && !empty($assessment_pleno_id)) ? $this->my_parameter["assessment_pleno_id"] : $assessment_pleno_id; 
	}

	public function update_assessment_pleno_by_id($assessment_id, $assessment_pleno_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_assessment_pleno") === FALSE) return FALSE;
		
		$this->load->model("Assessment_pleno_model");

		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Assessment_pleno_model->update_assessment_pleno_by_id($this->my_parameter, $assessment_id, $assessment_pleno_id, $auto_commit);

		return $affected_row;
	}

	public function delete_soft_assessment_pleno_by_id($assessment_id, $assessment_pleno_id, $auto_commit = TRUE)
	{
		if (!is_array($assessment_pleno_id))
		{
			$assessment_pleno_id = array_map("trim", explode(",", $assessment_pleno_id));
		}

		$assessment_plenos = array();
		$now = date('Y-m-d H:i:s');

		foreach ($assessment_pleno_id as $key => $value) {
			$assessment_plenos[$key] = array(
				'assessment_id' => $assessment_id,
				'assessment_pleno_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Assessment_pleno_model");

		$affected_row = $this->Assessment_pleno_model->delete_soft_assessment_pleno_by_id($assessment_plenos, $auto_commit);

		return $affected_row;
	}

	public function delete_hard_assessment_pleno_by_id($assessment_id, $assessment_pleno_id, $confirmation, $auto_commit = TRUE)
	{
		$this->load->model("Assessment_pleno_model");

		if ($this->configuration["hard_delete_word"] == "pleno_id")
		{
			$parameter = array(
				"assessment_id" => $assessment_id,
				"pleno_id" => $confirmation,
			);

			$assessment_pleno = $this->get_assessment_pleno_by_id($parameter, $assessment_pleno_id);

			if (empty($assessment_pleno->assessment_pleno_id)) {
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

		$affected_row = $this->Assessment_pleno_model->delete_hard_assessment_pleno_by_id($assessment_id, $assessment_pleno_id, $auto_commit);

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