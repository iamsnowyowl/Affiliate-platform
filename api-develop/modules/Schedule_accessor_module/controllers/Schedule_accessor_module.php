<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_accessor_module extends MX_Controller {

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

	public function get_schedule_accessor_by_date($parameter = array(), $date, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Schedule_accessor_model");

		$schedule_accessor = $this->Schedule_accessor_model->get_schedule_accessor_by_date($date, $graph->select);

		if (!isset($schedule_accessor))
		{
			modules::run("Error_module/set_error", "Schedule_accessor not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $schedule_accessor;
	}

	public function get_schedule_accessor_by_id($parameter = array(), $schedule_accessor_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Schedule_accessor_model");

		$schedule_accessor = $this->Schedule_accessor_model->get_schedule_accessor_by_id($schedule_accessor_id, $graph->select);

		if (!isset($schedule_accessor))
		{
			modules::run("Error_module/set_error", "Schedule_accessor not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $schedule_accessor;
	}

	public function get_schedule_accessor_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Schedule_accessor_model");

		$schedule_accessor = $this->Schedule_accessor_model->get_schedule_accessor_list($graph);
		$schedule_accessor_count = $this->get_schedule_accessor_count($parameter);
		$graph_pagination = $this->get_graph_pagination($schedule_accessor_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $schedule_accessor_count->count,
			'data' => $schedule_accessor,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_schedule_accessor_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Schedule_accessor_model");

		$schedule_accessor_count = $this->Schedule_accessor_model->get_schedule_accessor_count($graph);

		return $schedule_accessor_count;
	}

	public function create_schedule_accessor($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if ($this->validate_input("create_schedule_accessor") === FALSE) return FALSE;
		
		$this->load->model("Schedule_accessor_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		$this->my_parameter['schedule_accessor_id'] = md5(intval($this->my_parameter['accessor_id']).$this->my_parameter['CalendarDay']);

		// since we are delete before inserting then we need to set date to our default date. milenium year
		$this->my_parameter['deleted_at'] = $this->config->item('deleted_at');

		$schedule_accessor_id = $this->Schedule_accessor_model->create_schedule_accessor($this->my_parameter, $auto_commit);

		return $this->my_parameter['schedule_accessor_id'];
	}

	public function update_schedule_accessor_by_id($schedule_accessor_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_schedule_accessor") === FALSE) return FALSE;
		
		$this->load->model("Schedule_accessor_model");

		// add extra parameter
		$schedule_accessor_id = intval($schedule_accessor_id);
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Schedule_accessor_model->update_schedule_accessor_by_id($this->my_parameter, $schedule_accessor_id, $auto_commit);

		return $affected_row;
	}

	public function delete_schedule_accessor_by_id($schedule_accessor_id, $auto_commit = TRUE)
	{
		if (!is_array($schedule_accessor_id))
		{
			$schedule_accessor_id = array_map("trim", explode(",", $schedule_accessor_id));
		}

		$schedule_accessor_id = array_map("intval", $schedule_accessor_id);

		$schedule_accessors = array();
		$now = date('Y-m-d H:i:s');

		foreach ($schedule_accessor_id as $key => $value) {
			$schedule_accessors[$key] = array(
				'schedule_accessor_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Schedule_accessor_model");

		$affected_row = $this->Schedule_accessor_model->delete_schedule_accessor_by_id($schedule_accessors, $auto_commit);

		return $affected_row;
	}

	public function delete_schedule_accessor_by_accessor_id($accessor_id, $auto_commit = TRUE)
	{
		if (!is_array($accessor_id))
		{
			$accessor_id = array_map("trim", explode(",", $accessor_id));
		}

		$accessor_id = array_map("intval", $accessor_id);

		$accessor_ids = array();
		$now = date('Y-m-d H:i:s');

		foreach ($accessor_id as $key => $value) {
			$accessor_ids[$key] = array(
				'accessor_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Schedule_accessor_model");

		$affected_row = $this->Schedule_accessor_model->delete_schedule_accessor_by_accessor_id($accessor_ids, $auto_commit);

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