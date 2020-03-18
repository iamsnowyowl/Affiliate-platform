<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_module extends MX_Controller {

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
		$this->load->model("Article_model");

		$article = $this->Article_model->check($check, $graph);

		if (!isset($article))
		{
			modules::run("Error_module/set_error", "Article not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $article;
	}

	public function get_article_by_id($parameter = array(), $article_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Article_model");

		$article = $this->Article_model->get_article_by_id($article_id, $graph);

		if (!isset($article))
		{
			modules::run("Error_module/set_error", "Article not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $article;
	}

	public function get_article_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Article_model");

		$article = $this->Article_model->get_article_list($graph);
		$article_count = $this->get_article_count($parameter);
		$graph_pagination = $this->get_graph_pagination($article_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $article_count->count,
			'data' => $article,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_article_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Article_model");

		$article_count = $this->Article_model->get_article_count($graph);

		return $article_count;
	}

	public function create_article($parameter = array(), $created_by = 0, $validation_name = "create_article", $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (!$this->configuration["pk_use_ai"]){
			$this->my_parameter["article_id"] = guidv4(random_bytes(16));
		}

		if ($this->validate_input($validation_name) === FALSE) return FALSE;
		
		// check is article already created or not
		if ($this->configuration["check_unique"])
		{
			$check = modules::run("Article_module/check", NULL, $this->my_parameter['title']);
			if (!empty($check->article_id)){
				modules::run("Error_module/set_error", "Article already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		$this->load->model("Article_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$article_id = $this->Article_model->create_article($this->my_parameter, $auto_commit);

	 	return (!$this->configuration["pk_use_ai"] && !empty($article_id)) ? $this->my_parameter["article_id"] : $article_id; 
	}

	public function update_article_by_id($article_id, $parameter, $modified_by = 0, $validation_name = "update_article", $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input($validation_name) === FALSE) return FALSE;
		
		$this->load->model("Article_model");

		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Article_model->update_article_by_id($this->my_parameter, $article_id, $auto_commit);

		return $affected_row;
	}

	public function delete_soft_article_by_id($article_id, $auto_commit = TRUE)
	{
		if (!is_array($article_id))
		{
			$article_id = array_map("trim", explode(",", $article_id));
		}

		$articles = array();
		$now = date('Y-m-d H:i:s');

		foreach ($article_id as $key => $value) {
			$articles[$key] = array(
				'article_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Article_model");

		$affected_row = $this->Article_model->delete_soft_article_by_id($articles, $auto_commit);

		return $affected_row;
	}

	public function delete_hard_article_by_id($article_id, $confirmation, $auto_commit = TRUE)
	{
		$this->load->model("Article_model");

		if ($this->configuration["hard_delete_word"] == "title")
		{
			$parameter = array("title" => $confirmation);

			$article = $this->get_article_by_id($parameter, $article_id);

			if (empty($article->article_id)) {
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

		$affected_row = $this->Article_model->delete_hard_article_by_id($article_id, $auto_commit);

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