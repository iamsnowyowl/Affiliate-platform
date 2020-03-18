<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admintuk_module extends MX_Controller {

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

	public function get_admintuk_by_id($parameter = array(), $admintuk_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Admintuk_model");

		$admintuk = $this->Admintuk_model->get_admintuk_by_id($admintuk_id, $graph->select);

		if (!isset($admintuk))
		{
			modules::run("Error_module/set_error", "Admintuk not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		if (isset($admintuk->picture)) $admintuk->picture = "/users/".$admintuk->user_id."/picture";
		if (isset($admintuk->date_of_birth)) {
			$admintuk->m_date_of_birth =  date("d-m-Y", strtotime($admintuk->date_of_birth));
			$admintuk->date_of_birth =  date("Y-m-d", strtotime($admintuk->date_of_birth));
		}
		else $admintuk->m_date_of_birth =  NULL;
		if (isset($admintuk->nik_photo)) $admintuk->nik_photo = base64_encode($admintuk->nik_photo);
		if (isset($admintuk->npwp_photo)) $admintuk->npwp_photo = base64_encode($admintuk->npwp_photo);

		return $admintuk;
	}

	public function get_admintuk_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Admintuk_model");

		$admintuk = $this->Admintuk_model->get_admintuk_list($graph);

		foreach ($admintuk as $key => $value) 
		{
			if (isset($admintuk[$key]->picture)) $admintuk[$key]->picture = "/users/".$admintuk[$key]->user_id."/picture";
			if (isset($admintuk[$key]->date_of_birth)) {
				$admintuk[$key]->m_date_of_birth =  date("d-m-Y", strtotime($admintuk[$key]->date_of_birth));
				$admintuk[$key]->date_of_birth =  date("Y-m-d", strtotime($admintuk[$key]->date_of_birth));
			}
			else $admintuk[$key]->m_date_of_birth =  NULL;
			if (isset($admintuk[$key]->nik_photo)) $admintuk[$key]->nik_photo = base64_encode($admintuk[$key]->nik_photo);
			if (isset($admintuk[$key]->npwp_photo)) $admintuk[$key]->npwp_photo = base64_encode($admintuk[$key]->npwp_photo);
			if (isset($admintuk[$key]->last_education_certificate_photo)) base64_encode($admintuk[$key]->last_education_certificate_photo);
			if (isset($admintuk[$key]->training_certificate_photo)) base64_encode($admintuk[$key]->training_certificate_photo);
			if (isset($admintuk[$key]->colored_photo)) base64_encode($admintuk[$key]->colored_photo);
			if (isset($admintuk[$key]->family_card_photo)) base64_encode($admintuk[$key]->family_card_photo);
		}

		$admintuk_count = $this->get_admintuk_count($parameter);
		$graph_pagination = $this->get_graph_pagination($admintuk_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $admintuk_count->count,
			'data' => $admintuk,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_admintuk_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Admintuk_model");

		$admintuk_count = $this->Admintuk_model->get_admintuk_count($graph);

		return $admintuk_count;
	}

	public function create_admintuk($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if ($this->validate_input("create_admintuk") === FALSE) return FALSE;
		
		$this->load->model("Admintuk_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$admintuk_id = $this->Admintuk_model->create_admintuk($this->my_parameter, $auto_commit);

		return $admintuk_id;
	}

	public function update_admintuk($parameter, $user_id, $modified_by)
	{
		// mark this function for optimise transaction in future
		$rules = modules::run("User_module/get_config", "rules");
		$rules = array_column($rules["update_user"], "field");
		
		$user_parameter = array();
		$admintuk_parameter = array();

		foreach ($parameter as $key => $value) {
			if (in_array($key, $rules)) $user_parameter[$key] = $value;
			else $admintuk_parameter[$key] = $value;
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

		if (!empty($admintuk_parameter))
		{
			if (!empty($admintuk_parameter['signature'])) {
				$admintuk_parameter['signature'] = base64_decode($admintuk_parameter['signature']);
			}
			
			$affected_row = modules::run("Admintuk_module/update_admintuk_by_id", $user_id, $admintuk_parameter, $modified_by);
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
								"domain" => "ADMINTUK",
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

	public function update_admintuk_by_id($admintuk_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_admintuk") === FALSE) return FALSE;
		
		$this->load->model("Admintuk_model");

		// add extra parameter
		$admintuk_id = intval($admintuk_id);
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Admintuk_model->update_admintuk_by_id($this->my_parameter, $admintuk_id, $auto_commit);

		return $affected_row;
	}

	public function delete_admintuk_by_user_id($user_id, $auto_commit = TRUE)
	{
		if (!is_array($user_id))
		{
			$user_id = array_map("trim", explode(",", $user_id));
		}

		$user_id = array_map("intval", $user_id);

		$admintuks = array();
		$now = date('Y-m-d H:i:s');

		foreach ($user_id as $key => $value) {
			$admintuks[$key] = array(
				'user_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Admintuk_model");
		$affected_row = $this->Admintuk_model->delete_admintuk_by_user_id($admintuks, $auto_commit);

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