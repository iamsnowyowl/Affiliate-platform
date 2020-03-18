<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fcm_user_module extends MX_Controller {

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

	public function get_fcm_user_by_id($parameter = array(), $fcm_user_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Fcm_user_model");

		$fcm_user = $this->Fcm_user_model->get_fcm_user_by_id($fcm_user_id, $graph->select);

		if (!isset($fcm_user))
		{
			modules::run("Error_module/set_error", "Fcm_user not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $fcm_user;
	}

	public function get_fcm_user_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Fcm_user_model");

		$fcm_user = $this->Fcm_user_model->get_fcm_user_list($graph);
		$fcm_user_count = $this->get_fcm_user_count($parameter);
		$graph_pagination = $this->get_graph_pagination($fcm_user_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $fcm_user_count->count,
			'data' => $fcm_user,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_fcm_user_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Fcm_user_model");

		$fcm_user_count = $this->Fcm_user_model->get_fcm_user_count($graph);

		return $fcm_user_count;
	}

	public function create_fcm_user($parameter = array(), $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if ($this->validate_input("create_fcm_user") === FALSE) return FALSE;

		$this->delete_hard_fcm_register_id($this->my_parameter["register_id"]);

		$fcm_user_data = $this->get_fcm_user_list(array("mac_address" => $this->my_parameter["mac_address"]));
		
		if (!empty($fcm_user_data["data"])) {
			for ($i=0; $i < count($fcm_user_data["data"]); $i++) { 
				$this->delete_hard_fcm_register_id($fcm_user_data["data"][$i]->register_id);
			}
		}

		$this->load->model("Fcm_user_model");

		$fcm_user_id = $this->Fcm_user_model->create_fcm_user($this->my_parameter, $auto_commit);

		return $fcm_user_id;
	}

	public function update_fcm_user_by_id($fcm_user_id, $parameter, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_fcm_user") === FALSE) return FALSE;
		
		$this->load->model("Fcm_user_model");

		// add extra parameter
		$fcm_user_id = intval($fcm_user_id);
		
		$affected_row = $this->Fcm_user_model->update_fcm_user_by_id($this->my_parameter, $fcm_user_id, $auto_commit);

		return $affected_row;
	}

	public function clear_failed_token($register_id, $result)
	{
		if (!empty($result)) 
		{
			$data_result = json_decode($result);
			$error = array_column($data_result->results, "error");
			if (empty($error)) return;
			for ($i=0; $i < count($error); $i++) {
				if (!empty($register_id[$i])) $this->delete_hard_fcm_register_id($register_id[$i]);
			}
		}
	}

	public function delete_hard_fcm_register_id($register_id, $auto_commit = TRUE)
	{
		$this->load->model("Fcm_user_model");
		
		$affected_row = $this->Fcm_user_model->delete_hard_fcm_register_id($register_id, $auto_commit);

		return $affected_row;
	}

	public function delete_fcm_user_by_id($fcm_user_id, $auto_commit = TRUE)
	{
		if (!is_array($fcm_user_id))
		{
			$fcm_user_id = array_map("trim", explode(",", $fcm_user_id));
		}

		$fcm_user_id = array_map("intval", $fcm_user_id);

		$fcm_users = array();
		$now = date('Y-m-d H:i:s');

		foreach ($fcm_user_id as $key => $value) {
			$fcm_users[$key] = array(
				'fcm_user_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Fcm_user_model");

		$affected_row = $this->Fcm_user_model->delete_fcm_user_by_id($fcm_users, $auto_commit);

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