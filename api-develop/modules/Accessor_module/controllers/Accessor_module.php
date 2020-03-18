<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accessor_module extends MX_Controller {

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

	public function read_accessor_data($parameter = array(), $accessor_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Accessor_model");

		$accessor = $this->Accessor_model->get_accessor_by_id($accessor_id, $graph->select);

		if (!isset($accessor))
		{
			modules::run("Error_module/set_error", "Accessor not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $accessor;
	}

	public function get_accessor_by_id($parameter = array(), $accessor_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Accessor_model");

		$accessor = $this->Accessor_model->get_accessor_by_id($accessor_id, $graph->select);

		if (!isset($accessor))
		{
			modules::run("Error_module/set_error", "Accessor not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		if (isset($accessor->signature)) {
			$accessor->signature = "data:image/png;base64,".base64_encode($accessor->signature);
		}

		if (isset($accessor->picture)) $accessor->picture = "/users/".$accessor->user_id."/picture";
		if (isset($accessor->date_of_birth)) {
			$accessor->m_date_of_birth =  date("d-m-Y", strtotime($accessor->date_of_birth));
			$accessor->date_of_birth =  date("Y-m-d", strtotime($accessor->date_of_birth));
		}
		else $accessor->m_date_of_birth =  NULL;
		if (isset($accessor->nik_photo)) $accessor->nik_photo = "/public/users/".$accessor->user_id."/accessors/nik_photo";
		if (isset($accessor->npwp_photo)) $accessor->npwp_photo = "/public/users/".$accessor->user_id."/accessors/npwp_photo";
		if (isset($accessor->certificate)) $accessor->certificate = "/public/users/".$accessor->user_id."/accessors/certificate";

		return $accessor;
	}

	public function get_all_accessor_by_id($parameter = array(), $accessor_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Accessor_model");

		$accessor = $this->Accessor_model->get_all_accessor_by_id($accessor_id, $graph->select);

		if (!isset($accessor))
		{
			modules::run("Error_module/set_error", "Accessor not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		if (isset($accessor->signature)) {
			$accessor->signature = "data:image/png;base64,".base64_encode($accessor->signature);
		}

		if (isset($accessor->picture)) $accessor->picture = "/users/".$accessor->user_id."/picture";
		if (isset($accessor->date_of_birth)) {
			$accessor->m_date_of_birth =  date("d-m-Y", strtotime($accessor->date_of_birth));
			$accessor->date_of_birth =  date("Y-m-d", strtotime($accessor->date_of_birth));
		}
		else $accessor->m_date_of_birth =  NULL;
		if (isset($accessor->nik_photo)) $accessor->nik_photo = "/public/users/".$accessor->user_id."/accessors/nik_photo";
		if (isset($accessor->npwp_photo)) $accessor->npwp_photo = "/public/users/".$accessor->user_id."/accessors/npwp_photo";
		if (isset($accessor->certificate)) $accessor->certificate = "/public/users/".$accessor->user_id."/accessors/certificate";

		return $accessor;
	}

	public function get_accessor_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Accessor_model");

		$accessor = $this->Accessor_model->get_accessor_list($graph);

		foreach ($accessor as $key => $value) 
		{
			if (isset($accessor[$key]->signature)) {
				$accessor[$key]->signature = "data:image/png;base64,".base64_encode($accessor[$key]->signature);
			}
			if (isset($accessor[$key]->picture)) $accessor[$key]->picture = "/users/".$accessor[$key]->user_id."/picture";
			if (isset($accessor[$key]->date_of_birth)) {
				$accessor[$key]->m_date_of_birth =  date("d-m-Y", strtotime($accessor[$key]->date_of_birth));
				$accessor[$key]->date_of_birth =  date("Y-m-d", strtotime($accessor[$key]->date_of_birth));
			}
			else $accessor[$key]->m_date_of_birth =  NULL;
			if (isset($accessor[$key]->nik_photo)) $accessor[$key]->nik_photo = "/public/users/".$accessor[$key]->user_id."/accessors/nik_photo";
			if (isset($accessor[$key]->npwp_photo)) $accessor[$key]->npwp_photo = "/public/users/".$accessor[$key]->user_id."/accessors/npwp_photo";
			if (isset($accessor[$key]->certificate)) $accessor[$key]->certificate = "/public/users/".$accessor[$key]->user_id."/accessors/certificate";
		}

		$accessor_count = $this->get_accessor_count($parameter);
		$graph_pagination = $this->get_graph_pagination($accessor_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $accessor_count->count,
			'data' => $accessor,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_accessor_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Accessor_model");

		$accessor_count = $this->Accessor_model->get_accessor_count($graph);

		return $accessor_count;
	}

	public function create_accessor($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if ($this->validate_input("create_accessor") === FALSE) return FALSE;
		
		$this->load->model("Accessor_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$accessor_id = $this->Accessor_model->create_accessor($this->my_parameter, $auto_commit);

		return $accessor_id;
	}

	public function update_accessor($parameter, $user_id, $modified_by)
	{
		// mark this function for optimise transaction in future
		$rules = modules::run("User_module/get_config", "rules");
		$rules = array_column($rules["update_user"], "field");
		
		$user_parameter = array();
		$accessor_parameter = array();

		foreach ($parameter as $key => $value) {
			if (in_array($key, $rules)) $user_parameter[$key] = $value;
			else $accessor_parameter[$key] = $value;
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

		if (!empty($accessor_parameter))
		{
			if (!empty($accessor_parameter['nik_photo'])) {
				$accessor_parameter['nik_photo'] = base64_decode($accessor_parameter['nik_photo']);
			}

			if (!empty($accessor_parameter['npwp_photo'])) {
				$accessor_parameter['npwp_photo'] = base64_decode($accessor_parameter['npwp_photo']);
			}

			if (!empty($accessor_parameter['certificate'])) {
				$accessor_parameter['certificate'] = base64_decode($accessor_parameter['certificate']);
			}
			
			$affected_row = modules::run("Accessor_module/update_accessor_by_id", $user_id, $accessor_parameter, $modified_by);
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
								"domain" => "ACCESSOR",
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

	public function update_accessor_by_id($accessor_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_accessor") === FALSE) return FALSE;
		
		$this->load->model("Accessor_model");

		// add extra parameter
		$accessor_id = intval($accessor_id);
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Accessor_model->update_accessor_by_id($this->my_parameter, $accessor_id, $auto_commit);

		return $affected_row;
	}

	public function delete_accessor_by_user_id($user_id, $auto_commit = TRUE)
	{
		if (!is_array($user_id))
		{
			$user_id = array_map("trim", explode(",", $user_id));
		}

		$user_id = array_map("intval", $user_id);

		$accessors = array();
		$now = date('Y-m-d H:i:s');

		foreach ($user_id as $key => $value) {
			$accessors[$key] = array(
				'user_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Accessor_model");
		$affected_row = $this->Accessor_model->delete_accessor_by_user_id($accessors, $auto_commit);

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