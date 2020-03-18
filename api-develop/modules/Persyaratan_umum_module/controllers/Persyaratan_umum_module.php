<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persyaratan_umum_module extends MX_Controller {

	protected $error;
	protected $error_code;
	protected $definition;
	protected $rules;
	protected $configuration;
	protected $my_parameter;
	protected $node;
	protected $_mimes;


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
		$this->_mimes =& get_mimes();
	}

	public function check($parameter = array(), $check, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Persyaratan_umum_model");

		$persyaratan_umum = $this->Persyaratan_umum_model->check($check, $graph);

		if (!isset($persyaratan_umum))
		{
			modules::run("Error_module/set_error", "Persyaratan_umum not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $persyaratan_umum;
	}

	public function get_persyaratan_umum_by_id($parameter = array(), $persyaratan_umum_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Persyaratan_umum_model");

		$persyaratan_umum = $this->Persyaratan_umum_model->get_persyaratan_umum_by_id($persyaratan_umum_id, $graph);

		if (!isset($persyaratan_umum))
		{
			modules::run("Error_module/set_error", "Persyaratan_umum not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $persyaratan_umum;
	}

	public function get_persyaratan_umum_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$this->load->model("Persyaratan_umum_model");
		$parameter_persyaratan = [];
		if (!empty($parameter["created_by"])) {
			$parameter_persyaratan["created_by"] = $parameter["created_by"];
			unset($parameter["created_by"]);
		}

		if (!empty($parameter["applicant_id"])) {
			$parameter_persyaratan["applicant_id"] = $parameter["applicant_id"];
			unset($parameter["applicant_id"]);
		}

		$portfolio = modules::run("Master_portfolio_module/get_master_portfolio_list", array_merge($parameter, ["type" => "UMUM", "limit" => 100]));

		for ($i=0; $i < count($portfolio["data"]); $i++) {
			$parameter_persyaratan["master_portfolio_id"] = $portfolio["data"][$i]->master_portfolio_id;
			$graph = $this->get_graph_result($parameter_persyaratan, $default, $optional);
			$persyaratan_umum = $this->Persyaratan_umum_model->get_persyaratan_umum_list($graph);
			$portfolio["data"][$i]->persyaratan = $persyaratan_umum;
		}

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		return $portfolio;
	}

	public function create_persyaratan_umum($parameter = array(), $created_by = 0, $validation_name = "create_persyaratan_umum", $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (!$this->configuration["pk_use_ai"]){
			$this->my_parameter["persyaratan_umum_id"] = guidv4(random_bytes(16));
		}

		$rules = NULL;
		
		// get master_portfolio
		$portfolio = modules::run("Master_portfolio_module/get_master_portfolio_by_id", [], $parameter["master_portfolio_id"]);

		if (empty($portfolio->master_portfolio_id)){
			modules::run("Error_module/set_error", "Invalid Master Portfolio ID");
			modules::run("Error_module/set_error_code", 400);
			return FALSE;
		}

		if ($portfolio->form_type == "file") {
			$rules = [
				array(
					'field' => 'filename',
					'rules' => 'trim|required'
				)
			];
		}

		if ($this->validate_input($validation_name, $rules) === FALSE) return FALSE;

		// check is persyaratan_umum already created or not
		if ($this->configuration["check_unique"])
		{
			$check = modules::run("Persyaratan_umum_module/check", NULL, $this->my_parameter['persyaratan_umum_name']);
			if (!empty($check->persyaratan_umum_id)){
				modules::run("Error_module/set_error", "Persyaratan_umum already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		$this->load->model("Persyaratan_umum_model");
		
		if ($portfolio->form_type == "file" && $this->my_parameter["master_portfolio_id"] != "b5a1d6c3-a625-46e7-9ca4-543e5a8022d6") {
			$this->load->helper("file");
			$this->my_parameter["form_value"] = getenv("BASE_FILE_PATH").$this->configuration["persyaratan_umum_path"]."/".$parameter["applicant_id"]."/".$parameter["filename"];
			store_file_from_base64($parameter["form_value"], $this->configuration["file_path"].$this->configuration["persyaratan_umum_path"]."/".$parameter["applicant_id"]."/".$parameter["filename"], TRUE);
			$expl_filename = explode(".",$parameter["filename"]);
			$ext = array_pop($expl_filename);
			$this->my_parameter["ext"] = $ext;
			$this->my_parameter["mime_type"] = (!empty($this->_mimes[$ext][0])) ? $this->_mimes[$ext][0] : "";
		}
		else {
			$expl_filename = explode(".",$parameter["filename"]);
			$ext = array_pop($expl_filename);
			$this->my_parameter["ext"] = $ext;
			$this->my_parameter["mime_type"] = (!empty($this->_mimes[$ext][0])) ? $this->_mimes[$ext][0] : "";
		}

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$persyaratan_umum_id = $this->Persyaratan_umum_model->create_persyaratan_umum($this->my_parameter, $auto_commit);

	 	return (!$this->configuration["pk_use_ai"] && !empty($persyaratan_umum_id)) ? $this->my_parameter["persyaratan_umum_id"] : $persyaratan_umum_id; 
	}

	public function update_persyaratan_umum_by_id($persyaratan_umum_id, $parameter, $modified_by = 0, $validation_name = "update_persyaratan_umum", $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input($validation_name) === FALSE) return FALSE;
		
		$this->load->model("Persyaratan_umum_model");

		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Persyaratan_umum_model->update_persyaratan_umum_by_id($this->my_parameter, $persyaratan_umum_id, $auto_commit);

		return $affected_row;
	}

	public function delete_soft_persyaratan_umum_by_id($persyaratan_umum_id, $auto_commit = TRUE)
	{
		if (!is_array($persyaratan_umum_id))
		{
			$persyaratan_umum_id = array_map("trim", explode(",", $persyaratan_umum_id));
		}

		$persyaratan_umums = array();
		$now = date('Y-m-d H:i:s');

		foreach ($persyaratan_umum_id as $key => $value) {
			$persyaratan_umums[$key] = array(
				'persyaratan_umum_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Persyaratan_umum_model");

		$affected_row = $this->Persyaratan_umum_model->delete_soft_persyaratan_umum_by_id($persyaratan_umums, $auto_commit);

		return $affected_row;
	}

	public function delete_hard_persyaratan_umum_by_id($persyaratan_umum_id, $confirmation, $auto_commit = TRUE)
	{
		$this->load->model("Persyaratan_umum_model");

		if ($this->configuration["hard_delete_word"] == "persyaratan_umum_name")
		{
			$parameter = array("persyaratan_umum_name" => $confirmation);

			$persyaratan_umum = $this->get_persyaratan_umum_by_id($parameter, $persyaratan_umum_id);

			if (empty($persyaratan_umum->persyaratan_umum_id)) {
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

		$affected_row = $this->Persyaratan_umum_model->delete_hard_persyaratan_umum_by_id($persyaratan_umum_id, $auto_commit);

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