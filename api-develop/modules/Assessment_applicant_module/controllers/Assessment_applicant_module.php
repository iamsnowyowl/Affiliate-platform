<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment_applicant_module extends MX_Controller {

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
		$this->load->model("Assessment_applicant_model");

		$assessment_applicant = $this->Assessment_applicant_model->check($check, $graph);

		if (!isset($assessment_applicant))
		{
			modules::run("Error_module/set_error", "Assessment_applicant not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $assessment_applicant;
	}

	public function get_assessment_applicant_by_id($parameter = array(), $assessment_applicant_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_applicant_model");

		$assessment_applicant = $this->Assessment_applicant_model->get_assessment_applicant_by_id($assessment_applicant_id, $graph);

		if (!isset($assessment_applicant))
		{
			modules::run("Error_module/set_error", "Assessment_applicant not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $assessment_applicant;
	}

	public function get_assessment_by_id($parameter = array(), $assessment_id, $default = "default_assessment", $optional = "optional_assessment")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_applicant_model");

		$assessment = $this->Assessment_applicant_model->get_assessment_by_id($assessment_id, $graph);

		if (!isset($assessment))
		{
			modules::run("Error_module/set_error", "Assessment_applicant not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $assessment;
	}

	public function get_assessment_applicant_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_applicant_model");

		$assessment_applicant = $this->Assessment_applicant_model->get_assessment_applicant_list($graph);

		$ids_assessor = array_column($assessment_applicant, "assessor_id");

		$ids_assessor = array_filter($ids_assessor);

		$data_assessor = modules::run("Accessor_module/get_accessor_list", ["limit" => 100, "user_id" => implode(",",$ids_assessor)]);
		
		$index_assessor = [];

		// reduce overheat mysql query!!
		for ($i=0; $i < count($data_assessor["data"]); $i++) { 
			$index_assessor[$data_assessor["data"][$i]->user_id] = $data_assessor["data"][$i];
		}
		unset($data_assessor);

		for ($i=0; $i < count($assessment_applicant); $i++) { 
			$assessment_applicant[$i]->assessor_name = (!empty($index_assessor[$assessment_applicant[$i]->assessor_id])) 
			? $index_assessor[$assessment_applicant[$i]->assessor_id]->first_name." ".$index_assessor[$assessment_applicant[$i]->assessor_id]->last_name
			: "";
		}

		$assessment_applicant_count = $this->get_assessment_applicant_count($parameter);
		$graph_pagination = $this->get_graph_pagination($assessment_applicant_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $assessment_applicant_count->count,
			'data' => $assessment_applicant,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_assessment_applicant_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_applicant_model");

		$assessment_applicant_count = $this->Assessment_applicant_model->get_assessment_applicant_count($graph);

		return $assessment_applicant_count;
	}

	public function find_not_assign_applicant_list($assessment_id, $sub_schema_number, $parameter = array(), $default = "default_not_assign", $optional = "optional_not_assign")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_applicant_model");

		$assessment_applicant = $this->Assessment_applicant_model->find_not_assign_applicant_list($assessment_id, $sub_schema_number, $graph);

		foreach ($assessment_applicant as $key => $value) 
		{
			$assessment_applicant[$key]->picture = "/users/".$assessment_applicant[$key]->user_id."/picture";
		}
		
		$assessment_applicant_count = $this->find_not_assign_applicant_count($assessment_id, $sub_schema_number, $parameter, $default, $optional);
		$graph_pagination = $this->get_graph_pagination($assessment_applicant_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $assessment_applicant_count->count,
			'data' => $assessment_applicant,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function find_not_assign_applicant_count($assessment_id, $sub_schema_number, $parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_applicant_model");

		$assessment_applicant_count = $this->Assessment_applicant_model->find_not_assign_applicant_count($assessment_id, $sub_schema_number, $graph);

		return $assessment_applicant_count;
	}

	public function get_assessment_list($parameter = array(), $default = "default_assessment", $optional = "optional_assessment")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_applicant_model");

		$assessment_applicant = $this->Assessment_applicant_model->get_assessment_list($graph);

		if (!empty($assessment_applicant)){
			$ids_assessment = array_unique(array_column($assessment_applicant, "assessment_id"));
			$data_assessment_applicant = modules::run("Assessment_applicant_module/get_assessment_applicant_list", ["limit" => 100, "fields"=>"assessment_id", "assessment_id" => implode(",",$ids_assessment)]);
			$index_assessment_applicant = [];

			// reduce overheat mysql query!!
			for ($i=0; $i < count($data_assessment_applicant["data"]); $i++) { 
				$index_assessment_applicant[$data_assessment_applicant["data"][$i]->assessment_id] = $data_assessment_applicant["data"][$i];
			}
			unset($data_assessment_applicant);

			for ($i=0; $i < count($assessment_applicant); $i++) { 
				$assessment_applicant[$i]->status_recomendation = (!empty($index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->status_recomendation)) 
				? $index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->status_recomendation
				: "NONE";

				$assessment_applicant[$i]->status_graduation = (!empty($index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->status_graduation)) 
				? $index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->status_graduation
				: "NONE";

				$assessment_applicant[$i]->test_method = (!empty($index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->test_method)) 
				? $index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->test_method
				: "";

				// $assessment_applicant[$i]->notes = (!empty($index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->notes)) 
				// ? $index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->notes
				// : "";

				$assessment_applicant[$i]->description_for_recomendation = (!empty($index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->description_for_recomendation)) 
				? $index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->description_for_recomendation
				: "";

				$assessment_applicant[$i]->assessor_id = (!empty($index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->assessor_id)) 
				? $index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->assessor_id
				: 0;

				$assessment_applicant[$i]->assessor_name = (!empty($index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->assessor_name)) 
				? $index_assessment_applicant[$assessment_applicant[$i]->assessment_id]->assessor_name
				: "";
			}
		}
		$assessment_count = $this->get_assessment_count($parameter);
		$graph_pagination = $this->get_graph_pagination($assessment_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $assessment_count->count,
			'data' => $assessment_applicant,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_assessment_count($parameter = array(), $default = "default_assessment", $optional = "optional_assessment")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Assessment_applicant_model");

		$assessment_count = $this->Assessment_applicant_model->get_assessment_count($graph);

		return $assessment_count;
	}

	public function create_assessment_applicant($parameter = array(), $validation_name = "create_assessment_applicant", $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (!$this->configuration["pk_use_ai"]) {
			$this->my_parameter["assessment_applicant_id"] = guidv4(random_bytes(16));
		}

		if ($this->validate_input($validation_name) === FALSE) return FALSE;
		
		// check is assessment_applicant already created or not
		if ($this->configuration["check_unique"] && $validation_name == "create_assessment_applicant" )
		{
			$check = modules::run("Assessment_applicant_module/check", array("assessment_id" => $this->my_parameter["assessment_id"], "sub_schema_number" => $this->my_parameter["sub_schema_number"]), $this->my_parameter['applicant_id']);
			if (!empty($check->assessment_applicant_id)) {
				modules::run("Error_module/set_error", "Assessment_applicant already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		if ($validation_name == "create_assessment_applicant_non_account")
		{
			$ext = [
				"nik",
				"institution",
				"contact",
				"email",
				"address",
				"place_of_birth",
				"date_of_birth"
			];
			
			for ($i=0; $i < count($ext); $i++) { 
				if (isset($this->my_parameter[$ext[$i]])) {
					$this->my_parameter[$ext[$i]."_external"] = $this->my_parameter[$ext[$i]];
					unset($this->my_parameter[$ext[$i]]);
				}
			}
		}

		$this->load->model("Assessment_applicant_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);

		if (!empty($this->my_parameter["join_request_id"])){
			modules::run("Join_request_module/delete_hard_join_request_by_id", $this->my_parameter["join_request_id"], "HAPUS");
			unset($this->my_parameter["join_request_id"]);
		}
		
		$assessment_applicant_id = $this->Assessment_applicant_model->create_assessment_applicant($this->my_parameter, $auto_commit);

	 	return (!$this->configuration["pk_use_ai"] && !empty($assessment_applicant_id)) ? $this->my_parameter["assessment_applicant_id"] : $assessment_applicant_id; 
	}

	public function update_assessment_applicant_by_id($assessment_id, $assessment_applicant_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_assessment_applicant") === FALSE) return FALSE;
		
		$this->load->model("Assessment_applicant_model");

		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Assessment_applicant_model->update_assessment_applicant_by_id($this->my_parameter, $assessment_id, $assessment_applicant_id, $auto_commit);

		return $affected_row;
	}

	public function update_assessment_applicant($parameter, $condition, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}
		
		if ($this->validate_input("update_assessment_applicant") === FALSE) return FALSE;
		
		$this->load->model("Assessment_applicant_model");

		$affected_row = $this->Assessment_applicant_model->update_assessment_applicant($this->my_parameter, $condition, $auto_commit);

		return $affected_row;
	}

	public function delete_soft_assessment_applicant_by_id($assessment_id, $assessment_applicant_id, $auto_commit = TRUE)
	{
		if (!is_array($assessment_applicant_id))
		{
			$assessment_applicant_id = array_map("trim", explode(",", $assessment_applicant_id));
		}

		$assessment_applicants = array();
		$now = date('Y-m-d H:i:s');

		foreach ($assessment_applicant_id as $key => $value) {
			$assessment_applicants[$key] = array(
				'assessment_id' => $assessment_id,
				'assessment_applicant_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Assessment_applicant_model");

		$affected_row = $this->Assessment_applicant_model->delete_soft_assessment_applicant_by_id($assessment_applicants, $auto_commit);

		return $affected_row;
	}

	public function delete_hard_assessment_applicant_by_id($assessment_id, $assessment_applicant_id, $confirmation, $auto_commit = TRUE)
	{
		$this->load->model("Assessment_applicant_model");

		if ($this->configuration["hard_delete_word"] == "applicant_name")
		{
			$parameter = array(
				"assessment_id" => $assessment_id,
				"applicant_name" => $confirmation,
			);

			$assessment_applicant = $this->get_assessment_applicant_by_id($parameter, $assessment_applicant_id);

			if (empty($assessment_applicant->assessment_applicant_id)) {
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

		$affected_row = $this->Assessment_applicant_model->delete_hard_assessment_applicant_by_id($assessment_id, $assessment_applicant_id, $auto_commit);

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