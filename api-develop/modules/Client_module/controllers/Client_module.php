<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_module extends MX_Controller {

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
		
		$this->config->load($definition_name, TRUE, TRUE);

		$this->definition = $this->config->item($definition_name);
		
		$this->node = strtolower(get_class($this));
	}

	public function get_api_key_information($parameter = array(), $api_key, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);

		$this->load->model("Client_model");

		$api_info = $this->Client_model->get_api_key_information($api_key);

		if (!isset($api_info))
		{
			modules::run("Error_module/set_error", "Invalid api_key");
			modules::run("Error_module/set_error_code", 401);
			return FALSE;
		}

		return $api_info;
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
}