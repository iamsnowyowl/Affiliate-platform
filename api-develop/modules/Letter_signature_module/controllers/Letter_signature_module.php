<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Letter_signature_module extends MX_Controller {

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
		$this->load->model("Letter_signature_model");

		$letter_signature = $this->Letter_signature_model->check($check, $graph);

		if (!isset($letter_signature))
		{
			modules::run("Error_module/set_error", "Letter_signature not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $letter_signature;
	}

	public function get_letter_signature_by_id($parameter = array(), $letter_signature_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Letter_signature_model");

		$letter_signature = $this->Letter_signature_model->get_letter_signature_by_id($letter_signature_id, $graph);

		if (!isset($letter_signature))
		{
			modules::run("Error_module/set_error", "Letter_signature not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $letter_signature;
	}

	public function get_letter_signature_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Letter_signature_model");

		$letter_signature = $this->Letter_signature_model->get_letter_signature_list($graph);
		$letter_signature_count = $this->get_letter_signature_count($parameter);
		$graph_pagination = $this->get_graph_pagination($letter_signature_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $letter_signature_count->count,
			'data' => $letter_signature,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_letter_signature_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Letter_signature_model");

		$letter_signature_count = $this->Letter_signature_model->get_letter_signature_count($graph);

		return $letter_signature_count;
	}

	public function create_letter_signature($parameter = array(), $created_by = 0, $validation_name = "create_letter_signature", $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (!$this->configuration["pk_use_ai"]){
			$this->my_parameter["letter_signature_id"] = guidv4(random_bytes(16));
		}

		if ($this->validate_input($validation_name) === FALSE) return FALSE;

		// check is letter_signature already created or not
		if ($this->configuration["check_unique"])
		{
			$check = modules::run("Letter_signature_module/check", NULL, $this->my_parameter['letter_signature_name']);
			if (!empty($check->letter_signature_id)){
				modules::run("Error_module/set_error", "Letter_signature already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		$this->load->model("Letter_signature_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);

		$this->store_media();

		$letter_signature_id = $this->Letter_signature_model->create_letter_signature($this->my_parameter, $auto_commit);
		
	 	return (!$this->configuration["pk_use_ai"] && !empty($letter_signature_id)) ? $this->my_parameter["letter_signature_id"] : $letter_signature_id; 
	}

	public function update_letter_signature_by_id($letter_signature_id, $parameter, $modified_by = 0, $validation_name = "update_letter_signature", $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input($validation_name) === FALSE) return FALSE;
		
		$this->load->model("Letter_signature_model");

		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Letter_signature_model->update_letter_signature_by_id($this->my_parameter, $letter_signature_id, $auto_commit);

		return $affected_row;
	}

	public function delete_soft_letter_signature_by_id($letter_signature_id, $auto_commit = TRUE)
	{
		if (!is_array($letter_signature_id))
		{
			$letter_signature_id = array_map("trim", explode(",", $letter_signature_id));
		}

		$letter_signatures = array();
		$now = date('Y-m-d H:i:s');

		foreach ($letter_signature_id as $key => $value) {
			$letter_signatures[$key] = array(
				'letter_signature_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Letter_signature_model");

		$affected_row = $this->Letter_signature_model->delete_soft_letter_signature_by_id($letter_signatures, $auto_commit);

		return $affected_row;
	}

	public function delete_hard_letter_signature_by_id($letter_signature_id, $confirmation, $auto_commit = TRUE)
	{
		$this->load->model("Letter_signature_model");

		if ($this->configuration["hard_delete_word"] == "letter_signature_name")
		{
			$parameter = array("letter_signature_name" => $confirmation);

			$letter_signature = $this->get_letter_signature_by_id($parameter, $letter_signature_id);

			if (empty($letter_signature->letter_signature_id)) {
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

		$affected_row = $this->Letter_signature_model->delete_hard_letter_signature_by_id($letter_signature_id, $auto_commit);

		return $affected_row;
	}

	public function store_media()
	{
		if (empty($this->my_parameter[$this->configuration["media_column_name"]])) return;
		if (!is_string($this->my_parameter[$this->configuration["media_column_name"]])) return;

		$expl_media = explode(";", $this->my_parameter[$this->configuration["media_column_name"]]);
		if (count($expl_media) == 2){
			$expl_media0 = explode(":", $expl_media[0]);
			$expl_media1 = explode(",", $expl_media[1]);
			$this->my_parameter["mime_type"] = array_pop($expl_media0);
			$this->my_parameter[$this->configuration["media_column_name"]] = array_pop($expl_media1);
		}
		else {
			// we can measure the mime type. manual detect with finfo
			$f = finfo_open();
			$this->my_parameter["mime_type"] = finfo_buffer($f, base64_decode($this->my_parameter[$this->configuration["media_column_name"]]), FILEINFO_MIME_TYPE);
			finfo_close($f);

		}

		if (!$this->is_valid_base64($this->my_parameter[$this->configuration["media_column_name"]])) return;
		
		switch ($this->configuration["media_store_operation"]) {
			case 'BINARY':
				// just decode
				$this->my_parameter[$this->configuration["media_column_name"]] =  base64_decode($this->my_parameter[$this->configuration["media_column_name"]]);
				break;
			case 'FILE':
			default: 
				
				break;
		}

		return $this->my_parameter;
	}

	protected function is_valid_base64($string){
		return (base64_encode(base64_decode($string, true)) === $string);
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