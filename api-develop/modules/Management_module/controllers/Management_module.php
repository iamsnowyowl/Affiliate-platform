<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Management_module extends MX_Controller {

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

	public function read_management_data($parameter = array(), $management_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Management_model");

		$management = $this->Management_model->get_management_by_id($management_id, $graph->select);

		if (!isset($management))
		{
			modules::run("Error_module/set_error", "Management not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $management;
	}

	public function get_management_by_id($parameter = array(), $management_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Management_model");

		$management = $this->Management_model->get_management_by_id($management_id, $graph->select);

		if (!isset($management))
		{
			modules::run("Error_module/set_error", "Management not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		if (isset($management->picture)) $management->picture = "/users/".$management->user_id."/picture";
		if (isset($management->date_of_birth)) {
			$management->m_date_of_birth =  date("d-m-Y", strtotime($management->date_of_birth));
			$management->date_of_birth =  date("Y-m-d", strtotime($management->date_of_birth));
		}
		else $management->m_date_of_birth =  NULL;
		if (isset($management->nik_photo)) $management->nik_photo = "/public/users/".$management->user_id."/managements/nik_photo";
		if (isset($management->npwp_photo)) $management->npwp_photo = "/public/users/".$management->user_id."/managements/npwp_photo";

		return $management;
	}

	public function get_management_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Management_model");

		$management = $this->Management_model->get_management_list($graph);

		foreach ($management as $key => $value) 
		{
			if (isset($management[$key]->picture)) $management[$key]->picture = "/users/".$management[$key]->user_id."/picture";
			if (isset($management[$key]->date_of_birth)) {
				$management[$key]->m_date_of_birth =  date("d-m-Y", strtotime($management[$key]->date_of_birth));
				$management[$key]->date_of_birth =  date("Y-m-d", strtotime($management[$key]->date_of_birth));
			}
			else $management[$key]->m_date_of_birth =  NULL;
			if (isset($management[$key]->nik_photo)) $management[$key]->nik_photo = "/public/users/".$management[$key]->user_id."/managements/nik_photo";
			if (isset($management[$key]->npwp_photo)) $management[$key]->npwp_photo = "/public/users/".$management[$key]->user_id."/managements/npwp_photo";
		}

		$management_count = $this->get_management_count($parameter);
		$graph_pagination = $this->get_graph_pagination($management_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $management_count->count,
			'data' => $management,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_management_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Management_model");

		$management_count = $this->Management_model->get_management_count($graph);

		return $management_count;
	}

	public function create_management($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if ($this->validate_input("create_management") === FALSE) return FALSE;
		
		$this->load->model("Management_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$management_id = $this->Management_model->create_management($this->my_parameter, $auto_commit);

		return $management_id;
	}

	public function update_management($parameter, $user_id, $modified_by)
	{
		// mark this function for optimise transaction in future
		$rules = modules::run("User_module/get_config", "rules");
		$rules = array_column($rules["update_user"], "field");
		
		$user_parameter = array();
		$management_parameter = array();

		foreach ($parameter as $key => $value) {
			if (in_array($key, $rules)) $user_parameter[$key] = $value;
			else $management_parameter[$key] = $value;
		}

		$this->load->model("Transaction_model");

		$this->Transaction_model->trans_start();

		if (!empty($user_parameter))
		{
			$affected_row = modules::run("User_module/update_user_by_id", $user_id, $user_parameter, $modified_by);

			if ($affected_row === FALSE)
			{
				$this->Transaction_model->trans_rollback();
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "USER",
								"reason" => "UpdateErrorException",
								"extra" => modules::run("Error_module/get_error_extra")
							),
						)
					)
				);
			}
		}

		if (!empty($management_parameter))
		{
			if (!empty($management_parameter['signature'])) {
				$management_parameter['signature'] = base64_decode($management_parameter['signature']);
			}

			if (!empty($management_parameter['nik_photo'])) {
				$management_parameter['nik_photo'] = base64_decode($management_parameter['nik_photo']);
			}

			if (!empty($management_parameter['npwp_photo'])) {
				$management_parameter['npwp_photo'] = base64_decode($management_parameter['npwp_photo']);
			}

			if (!empty($management_parameter['certificate'])) {
				$management_parameter['certificate'] = base64_decode($management_parameter['certificate']);
			}
			
			$affected_row = modules::run("Management_module/update_management_by_id", $user_id, $management_parameter, $modified_by);
			if ($affected_row === FALSE)
			{
				$this->Transaction_model->trans_rollback();
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "MANAGEMENT",
								"reason" => "UpdateErrorException",
								"extra" => modules::run("Error_module/get_error_extra")
							),
						)
					)
				);
			}
		}

		$this->Transaction_model->trans_complete();

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function update_management_by_id($management_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_management") === FALSE) return FALSE;
		
		$this->load->model("Management_model");

		// add extra parameter
		$management_id = intval($management_id);
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Management_model->update_management_by_id($this->my_parameter, $management_id, $auto_commit);

		return $affected_row;
	}

	public function delete_management_by_user_id($user_id, $auto_commit = TRUE)
	{
		if (!is_array($user_id))
		{
			$user_id = array_map("trim", explode(",", $user_id));
		}

		$user_id = array_map("intval", $user_id);

		$managements = array();
		$now = date('Y-m-d H:i:s');

		foreach ($user_id as $key => $value) {
			$managements[$key] = array(
				'user_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Management_model");
		$affected_row = $this->Management_model->delete_management_by_user_id($managements, $auto_commit);

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