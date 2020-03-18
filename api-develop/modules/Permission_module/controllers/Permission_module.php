<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission_module extends MX_Controller {

	protected $error;
	protected $error_code;
	protected $definition;
	protected $rules;
	protected $node;
	protected $my_parameter;


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

	public function get_permission_by_role_code($role_code, $select = "*")
	{
		$this->load->model('Permission_model');

		// set user permission table
		$list_permission = $this->Permission_model->get_permission_by_role_code($role_code, $select = "*");
		return $list_permission;
	}

	public function get_user_permission_by_id($user_id, $select = "*")
	{
        $this->load->model('Permission_model');

        // set user permission table
        $list_permission = $this->Permission_model->get_user_permission_by_id($user_id, $select = "*");
        return $list_permission;
	}

	public function get_permission_by_id($parameter = array(), $permission_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Permission_model");

		$permission = $this->Permission_model->get_permission_by_id($permission_id, $graph->select);

		if (!isset($permission))
		{
			modules::run("Error_module/set_error", "Permission not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $permission;
	}

	public function get_permission_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Permission_model");

		$permission = $this->Permission_model->get_permission_list($graph);
		$permission_count = $this->get_permission_count($parameter);
		$graph_pagination = $this->get_graph_pagination($permission_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $permission_count->count,
			'data' => $permission,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_permission_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Permission_model");

		$permission_count = $this->Permission_model->get_permission_count($graph);

		return $permission_count;
	}

	public function create_permission($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if ($this->validate_input("create_permission") === FALSE) return FALSE;
		
		// check is permission already created or not
		$check = modules::run("Permission_module/get_permission_by_name", NULL, $this->my_parameter['permission_name']);

		if (!empty($check->permission_id))
		{
			modules::run("Error_module/set_error", "Permission already exist");
			modules::run("Error_module/set_error_code", 409);
			return FALSE;
		}

		$this->load->model("Permission_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$permission_id = $this->Permission_model->create_permission($this->my_parameter, $auto_commit);

		return $permission_id;
	}

	public function update_permission_by_id($permission_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input("update_permission") === FALSE) return FALSE;
		
		$this->load->model("Permission_model");

		// add extra parameter
		$permission_id = intval($permission_id);
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Permission_model->update_permission_by_id($this->my_parameter, $permission_id, $auto_commit);

		return $affected_row;
	}

	public function delete_permission_by_id($permission_id, $auto_commit = TRUE)
	{
		if (!is_array($permission_id))
		{
			$permission_id = array_map("trim", explode(",", $permission_id));
		}

		$permission_id = array_map("intval", $permission_id);

		$permissions = array();
		$now = date('Y-m-d H:i:s');

		foreach ($permission_id as $key => $value) {
			$permissions[$key] = array(
				'permission_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("Permission_model");

		$affected_row = $this->Permission_model->delete_permission_by_id($permissions, $auto_commit);

		return $affected_row;
	}

	public function require_permission($permission_code, $strict = TRUE)
	{
		modules::run("Authentication_module/is_user_login");

		$require_permission = array();

		if (is_array($permission_code))
		{
			foreach ($permission_code as $key => $value) 
			{
				$require_permission[] = $value;
			}
		}
		else
		{
			$require_permission = array_map("trim", explode(",", $permission_code));
		}

		$missing = array_diff($require_permission, array_column($this->userdata['permission'], "sub_module_code"));

		$granted = (boolean) (empty($missing)) ? TRUE : FALSE;
		
		if (!$strict) return $granted;

		if (!$granted)
		{
			get_instance()->load->helper('http');
			response(401, array(
					"responseStatus" => "ERROR",
					"code" => 401,
					"error" => array(
						"message" => "User need permission ".implode("`,`", $missing),
						"errors" => array(
							"domain" => "AUTHENTICATION",
							"reason" => "UserNeedPermission"
						),
					)
				)
			);
		}
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
		$this->form_validation->set_rules($this->rules[$group]);
		if (!empty($extra_rules)) $this->form_validation->set_rules($extra_rules);
		$this->form_validation->set_data($this->my_parameter, TRUE);

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