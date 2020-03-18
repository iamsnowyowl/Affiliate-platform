<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_module extends MX_Controller {

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

	public function get_assessment_by_id($parameter = array(), $assessment_id, $default = "default_assessment", $optional = "optional_assessment")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Dashboard_model");

		$assessment = $this->Dashboard_model->get_assessment_by_id($assessment_id, $graph);

		if (!isset($assessment))
		{
			modules::run("Error_module/set_error", "Assessment not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $assessment;
	}

	public function get_assessment_list($parameter = array(), $default = "default_assessment", $optional = "optional_assessment")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Dashboard_model");

		$assessment = $this->Dashboard_model->get_assessment_list($graph);
		$assessment_count = $this->get_assessment_count($parameter);
		$graph_pagination = $this->get_graph_pagination($assessment_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $assessment_count->count,
			'data' => $assessment,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_assessment_count($parameter = array(), $default = "default_assessment", $optional = "optional_assessment")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Dashboard_model");

		$assessment_count = $this->Dashboard_model->get_assessment_count($graph);

		return $assessment_count;
	}

	protected function get_graph_result($parameter = array(), $default = "default_assessment", $optional = "optional_assessment")
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