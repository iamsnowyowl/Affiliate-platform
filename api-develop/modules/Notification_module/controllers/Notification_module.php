<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_module extends MX_Controller {

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

	public function get_notification_by_id($parameter = array(), $notification_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Notification_model");

		$notification = $this->Notification_model->get_notification_by_id($notification_id, $graph->select);

		if (!isset($notification))
		{
			modules::run("Error_module/set_error", "Notification not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $notification;
	}

	public function get_notification_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Notification_model");

		$notification = $this->Notification_model->get_notification_list($graph);
		$notification_count = $this->get_notification_count($parameter);
		$graph_pagination = $this->get_graph_pagination($notification_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $notification_count->count,
			'data' => $notification,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_notification_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Notification_model");

		$notification_count = $this->Notification_model->get_notification_count($graph);

		return $notification_count;
	}

	public function create_notification($parameter = array(), $auto_commit = TRUE)
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

		if ($this->validate_input("create_notification", $rules) === FALSE) return FALSE;
		
		$this->load->model("Notification_model");

		$notification_id = $this->Notification_model->create_notification($this->my_parameter, $auto_commit);

		return $notification_id;
	}

	public function update_notification_by_id($notification_id, $parameter, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_notification") === FALSE) return FALSE;
		
		$this->load->model("Notification_model");

		// add extra parameter
		$notification_id = intval($notification_id);
		
		$affected_row = $this->Notification_model->update_notification_by_id($this->my_parameter, $notification_id, $auto_commit);

		return $affected_row;
	}

	public function delete_notification_by_id($notification_id, $auto_commit = TRUE)
	{
		if (!is_array($notification_id))
		{
			$notification_id = array_map("trim", explode(",", $notification_id));
		}

		$notification_id = array_map("intval", $notification_id);

		$notifications = array();
		$now = date('Y-m-d H:i:s');

		foreach ($notification_id as $key => $value) {
			$notifications[$key] = array(
				'notification_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Notification_model");

		$affected_row = $this->Notification_model->delete_notification_by_id($notifications, $auto_commit);

		return $affected_row;
	}

	public function get_list_action(){
		return ["WELCOME_MESSAGE", "LSPACSNTFDEF", "LSPACSNTFOFR", "LSPACSNTFCFM", "LSPACSNTFRMD"];
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