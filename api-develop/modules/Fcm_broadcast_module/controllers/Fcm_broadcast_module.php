<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fcm_broadcast_module extends MX_Controller {

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

	public function get_fcm_broadcast_by_id($parameter = array(), $fcm_broadcast_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Fcm_broadcast_model");

		$fcm_broadcast = $this->Fcm_broadcast_model->get_fcm_broadcast_by_id($fcm_broadcast_id, $graph->select);

		if (!isset($fcm_broadcast))
		{
			modules::run("Error_module/set_error", "Fcm_broadcast not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $fcm_broadcast;
	}

	public function get_fcm_broadcast_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Fcm_broadcast_model");

		$fcm_broadcast = $this->Fcm_broadcast_model->get_fcm_broadcast_list($graph);
		$fcm_broadcast_count = $this->get_fcm_broadcast_count($parameter);
		$graph_pagination = $this->get_graph_pagination($fcm_broadcast_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $fcm_broadcast_count->count,
			'data' => $fcm_broadcast,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_fcm_broadcast_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Fcm_broadcast_model");

		$fcm_broadcast_count = $this->Fcm_broadcast_model->get_fcm_broadcast_count($graph);

		return $fcm_broadcast_count;
	}

	public function create_fcm_broadcast($parameter = array(), $auto_commit = TRUE)
	{
		$notification_list = modules::run("Notification_module/get_list_action");
		$available_notification_list = implode(",", $notification_list);

		$rules = array(
			array(
				'field' => 'click_action',
				'rules' => "trim|required"
			)
		);

		$this->my_parameter = $parameter;

		if ($this->validate_input("create_fcm_broadcast", $rules) === FALSE) return FALSE;
		
		$this->load->model("Fcm_broadcast_model");

		$fcm_broadcast_id = $this->Fcm_broadcast_model->create_fcm_broadcast($this->my_parameter, $auto_commit);

		return $fcm_broadcast_id;
	}

	public function update_fcm_broadcast_by_id($fcm_broadcast_id, $parameter, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_fcm_broadcast") === FALSE) return FALSE;
		
		$this->load->model("Fcm_broadcast_model");

		// add extra parameter
		$fcm_broadcast_id = intval($fcm_broadcast_id);
		
		$affected_row = $this->Fcm_broadcast_model->update_fcm_broadcast_by_id($this->my_parameter, $fcm_broadcast_id, $auto_commit);

		return $affected_row;
	}

	public function update_fcm_broadcast_send_date_by_id($fcm_broadcast_id, $auto_commit = TRUE)
	{
		if (!is_array($fcm_broadcast_id))
		{
			$fcm_broadcast_id = array_map("trim", explode(",", $fcm_broadcast_id));
		}

		$fcm_broadcast_id = array_map("intval", $fcm_broadcast_id);

		$fcm_broadcasts = array();
		$now = date('Y-m-d H:i:s');

		foreach ($fcm_broadcast_id as $key => $value) {
			$fcm_broadcasts[$key] = array(
				'fcm_broadcast_id' => $value,
				'send_date' => $now
			);
		}

		$this->load->model("Fcm_broadcast_model");

		$affected_row = $this->Fcm_broadcast_model->update_fcm_broadcast_send_date_by_id($fcm_broadcasts, $auto_commit);

		return $affected_row;
	}

	public function delete_fcm_broadcast_by_id($fcm_broadcast_id, $auto_commit = TRUE)
	{
		if (!is_array($fcm_broadcast_id))
		{
			$fcm_broadcast_id = array_map("trim", explode(",", $fcm_broadcast_id));
		}

		$fcm_broadcast_id = array_map("intval", $fcm_broadcast_id);

		$fcm_broadcasts = array();
		$now = date('Y-m-d H:i:s');

		foreach ($fcm_broadcast_id as $key => $value) {
			$fcm_broadcasts[$key] = array(
				'fcm_broadcast_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Fcm_broadcast_model");

		$affected_row = $this->Fcm_broadcast_model->delete_fcm_broadcast_by_id($fcm_broadcasts, $auto_commit);

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