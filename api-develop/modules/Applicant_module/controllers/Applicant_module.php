<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_module extends MX_Controller {

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

	public function get_applicant_by_id($parameter = array(), $applicant_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Applicant_model");

		$applicant = $this->Applicant_model->get_applicant_by_id($applicant_id, $graph->select);

		if (!isset($applicant))
		{
			modules::run("Error_module/set_error", "Applicant not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		if (isset($applicant->signature)) {
			$applicant->signature = "data:image/png;base64,".base64_encode($applicant->signature);
		}

		if (isset($applicant->picture)) $applicant->picture = "/users/".$applicant->user_id."/picture";
		if (isset($applicant->date_of_birth)) {
			$applicant->m_date_of_birth =  date("d-m-Y", strtotime($applicant->date_of_birth));
			$applicant->date_of_birth =  date("Y-m-d", strtotime($applicant->date_of_birth));
		}
		else $applicant->m_date_of_birth =  NULL;
		if (isset($applicant->nik_photo)) $applicant->nik_photo = base64_encode($applicant->nik_photo);
		if (isset($applicant->npwp_photo)) $applicant->npwp_photo = base64_encode($applicant->npwp_photo);

		return $applicant;
	}

	public function get_applicant_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Applicant_model");

		$applicant = $this->Applicant_model->get_applicant_list($graph);

		foreach ($applicant as $key => $value) 
		{
			if (isset($applicant[$key]->signature)) {
				$applicant[$key]->signature = "data:image/png;base64,".base64_encode($applicant[$key]->signature);
			}
			if (isset($applicant[$key]->picture)) $applicant[$key]->picture = "/users/".$applicant[$key]->user_id."/picture";
			if (isset($applicant[$key]->date_of_birth)) {
				$applicant[$key]->m_date_of_birth =  date("d-m-Y", strtotime($applicant[$key]->date_of_birth));
				$applicant[$key]->date_of_birth =  date("Y-m-d", strtotime($applicant[$key]->date_of_birth));
			}
			else $applicant[$key]->m_date_of_birth =  NULL;
			if (isset($applicant[$key]->nik_photo)) $applicant[$key]->nik_photo = base64_encode($applicant[$key]->nik_photo);
			if (isset($applicant[$key]->npwp_photo)) $applicant[$key]->npwp_photo = base64_encode($applicant[$key]->npwp_photo);
			if (isset($applicant[$key]->last_education_certificate_photo)) base64_encode($applicant[$key]->last_education_certificate_photo);
			if (isset($applicant[$key]->training_certificate_photo)) base64_encode($applicant[$key]->training_certificate_photo);
			if (isset($applicant[$key]->colored_photo)) base64_encode($applicant[$key]->colored_photo);
			if (isset($applicant[$key]->family_card_photo)) base64_encode($applicant[$key]->family_card_photo);
		}

		$applicant_count = $this->get_applicant_count($parameter);
		$graph_pagination = $this->get_graph_pagination($applicant_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $applicant_count->count,
			'data' => $applicant,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_applicant_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Applicant_model");

		$applicant_count = $this->Applicant_model->get_applicant_count($graph);

		return $applicant_count;
	}

	public function create_applicant($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if ($this->validate_input("create_applicant") === FALSE) return FALSE;
		
		$this->load->model("Applicant_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$applicant_id = $this->Applicant_model->create_applicant($this->my_parameter, $auto_commit);

		return $applicant_id;
	}

	public function update_applicant($parameter, $user_id, $modified_by)
	{
		// mark this function for optimise transaction in future
		$rules = modules::run("User_module/get_config", "rules");
		$rules = array_column($rules["update_user"], "field");
		
		$user_parameter = array();
		$applicant_parameter = array();

		foreach ($parameter as $key => $value) {
			if (in_array($key, $rules)) $user_parameter[$key] = $value;
			else $applicant_parameter[$key] = $value;
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

		if (!empty($applicant_parameter))
		{
			if (!empty($applicant_parameter['signature'])) {
				$applicant_parameter['signature'] = base64_decode($applicant_parameter['signature']);
			}
			
			$affected_row = modules::run("Applicant_module/update_applicant_by_id", $user_id, $applicant_parameter, $modified_by);
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
								"domain" => "APPLICANT",
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

	public function update_applicant_by_id($applicant_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_applicant") === FALSE) return FALSE;
		
		$this->load->model("Applicant_model");

		// add extra parameter
		$applicant_id = intval($applicant_id);
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Applicant_model->update_applicant_by_id($this->my_parameter, $applicant_id, $auto_commit);

		return $affected_row;
	}

	public function delete_applicant_by_user_id($user_id, $auto_commit = TRUE)
	{
		if (!is_array($user_id))
		{
			$user_id = array_map("trim", explode(",", $user_id));
		}

		$user_id = array_map("intval", $user_id);

		$applicants = array();
		$now = date('Y-m-d H:i:s');

		foreach ($user_id as $key => $value) {
			$applicants[$key] = array(
				'user_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Applicant_model");
		$affected_row = $this->Applicant_model->delete_applicant_by_user_id($applicants, $auto_commit);

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