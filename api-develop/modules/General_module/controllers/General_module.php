<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_module extends MX_Controller {

	protected $error;
	protected $error_code;
	protected $definition;


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->config->load('definition', TRUE, TRUE);

		$definition_name = 'definition_'.strtolower(get_class($this));
		$this->config->load($definition_name, TRUE, TRUE);
		$this->definition = $this->config->item($definition_name);
	}

	public function login($parameter)
	{
		$group_name = "login";
		if ($this->validate_input($parameter, $group_name, array()) === FALSE) return FALSE;
		return modules::run("Authentication_module/check", $parameter['email'], $parameter['password']);
	}

	public function get_user_by_email($parameter = array(), $email, $default = "default", $optional = "optional")
	{
		$this->load->helper('common');
		
		if (!valid_email($email))
		{
			modules::run("Error_module/set_error", "Email not a valid format");
			modules::run("Error_module/set_error_code", 400);
			return FALSE;
		}

		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("User_model");

		$user = $this->User_model->get_user_by_email($email, $graph->select);

		if (!isset($user))
		{
			modules::run("Error_module/set_error", "User not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $user;
	}

	public function get_user_by_id($parameter = array(), $user_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("User_model");

		$user = $this->User_model->get_user_by_id($user_id, $graph->select);

		if (!isset($user))
		{
			modules::run("Error_module/set_error", "User not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $user;
	}

	public function get_user_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("User_model");

		$user = $this->User_model->get_user_list($graph);
		$user_count = $this->get_user_count($parameter);
		$graph_pagination = $this->get_graph_pagination($user_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $user_count->count,
			'data' => $user,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_user_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("User_model");

		$user_count = $this->User_model->get_user_count($graph);

		return $user_count;
	}

	public function create_user($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$role_list = modules::run("Role_module/get_role_list", "role_code");
		$available_role = implode(",", array_map("trim", array_column($role_list, 'role_code')));
		$group_name = "create_user";

		$rules = array(
			array(
				'field' => 'role_code',
				'rules' => 'trim|required|in_list['.$available_role.']'
			)
		);

		if ($this->validate_input($parameter, $group_name, $rules) === FALSE) return FALSE;
		
		// check is user already created or not
		$check = modules::run("User_module/get_user_by_email", NULL, $parameter['email']);

		if (!empty($check->user_id))
		{
			modules::run("Error_module/set_error", "User already exist");
			modules::run("Error_module/set_error_code", 409);
			return FALSE;
		}

		$this->load->model("User_model");

		// add parameter created_by
		$parameter['created_by'] = intval($created_by);
		
		// convert password to bcrypt
		if (!empty($parameter['password']))
		{
			$parameter['password'] = password_hash($parameter['password'], PASSWORD_BCRYPT);
			unset($parameter['passconf']);
		}

		$user_id = $this->User_model->create_user($parameter, $auto_commit);

		if ($user_id > 0)
		{
			// create default permission
			$status = $this->create_default_permission($user_id, $parameter['role_code']);	
			if (empty($status))
			{
				if (!empty($check->user_id))
				{
					modules::run("Error_module/set_error", "Failed to create permisison");
					modules::run("Error_module/set_error_code", 500);
					return FALSE;
				}
			}
		}

		return $user_id;
	}

	public function update_user_by_id($user_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		if (empty($parameter))
		{
			return TRUE;
		}

		$group_name = "update_user";

		if ($this->validate_input($parameter, $group_name, NULL) === FALSE) return FALSE;
		
		$this->load->model("User_model");

		// add extra parameter
		$user_id = intval($user_id);
		$parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->User_model->update_user_by_id($parameter, $user_id, $auto_commit);

		// check is user update the role and status update success?
		if (!empty($parameter['role_code']) && $affected_row > 0)
		{
			// delete permission
			$this->load->model("Transaction_model");

			// mark transaction to start
			$this->Transaction_model->trans_start();
	
			$status_delete = modules::run("Permission_module/delete_user_permission_by_user_id", $user_id, FALSE); // disable autocommit
			$status_create = $this->create_default_permission($user_id, $parameter['role_code'], FALSE);			
			if (empty($status_delete) && empty($status_create))
			{
				modules::run("Error_module/set_error", "Failed to create permisison");
				modules::run("Error_module/set_error_code", 500);
				$this->Transaction_model->trans_rollback();
				return FALSE;
			}
			$this->Transaction_model->trans_complete();
		}
		return $affected_row;
	}

	public function update_user_password_by_id($user_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		if (empty($parameter))
		{
			return TRUE;
		}

		$group_name = "update_user_password";
		if ($this->validate_input($parameter, $group_name, array()) === FALSE) return FALSE;

		// checking old password
		$userdata = $this->get_user_by_id(array(), $user_id, FALSE);
		// user exist. now verify password
		if (password_verify($parameter['oldpassword'], $userdata->password) === FALSE)
		{
			modules::run("Error_module/set_error", "Old password doesn`t match.");
			modules::run("Error_module/set_error_code", 422);
			modules::run("Error_module/set_error_extra", array("password" => "invalidauthentication"));
			return FALSE;
		}

		unset($parameter['oldpassword']);

		$this->load->model("User_model");

		// add extra parameter
		$user_id = intval($user_id);
		$parameter['modified_by'] = intval($modified_by);
		
		// convert password to bcrypt
		if (!empty($parameter['password']))
		{
			$parameter['password'] = password_hash($parameter['password'], PASSWORD_BCRYPT);
			unset($parameter['passconf']);
		}

		$affected_row = $this->User_model->update_user_by_id($parameter, $user_id, $auto_commit);
		return $affected_row;
	}

	protected function create_default_permission($user_id, $role_code, $auto_commit = TRUE)
	{
		// get default permission
		$permission = modules::run("Permission_module/get_schema_permission_by_role_code", 	$role_code);

		if (empty($permission)) return FALSE;
		
		$new_permission = array();
		foreach ($permission as $key => $value) {
			$new_permission[] = array(
				'user_id' => $user_id,
				'module_code' => $value->module_code,
				'sub_module_code' => $value->sub_module_code
			);
		}
		$status = modules::run("Permission_module/create_user_permission", $new_permission, $auto_commit);
		return $status;
	}


	public function delete_user_by_id($user_id, $auto_commit = TRUE)
	{
		if (!is_array($user_id))
		{
			$user_id = array_map("trim", explode(",", $user_id));
		}

		$user_id = array_map("intval", $user_id);

		$users = array();
		$now = date('Y-m-d H:i:s');

		foreach ($user_id as $key => $value) {
			$users[$key] = array(
				'user_id' => $value,
				'deleted_at' => $now
			);
		}

		$this->load->model("User_model");

		$affected_row = $this->User_model->delete_user_by_id($users, $auto_commit);

		if ($affected_row = count($users))
		{
			foreach ($users as $key => $value) 
			{
				modules::run("Permission_module/delete_user_permission_by_user_id", $users[$key]['user_id']);
			}
		}

		return $affected_row;
	}

	public function update_last_login($user_id)
	{
		$this->load->model("User_model");

		$status = $this->User_model->update_last_login($user_id);
		return $status;
	}

	protected function get_graph_result($parameter = array(), $default = "default", $optional = "optional")
	{
		$default = $this->definition[$default];
		$optional = $this->definition[$optional];

		$this->load->library("graph");
		// check whether graph validation error or not
		if (!$this->graph->initialize($parameter, $default, $optional, "users"))
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

		return $this->graph->get_compile_result("users");
	}

	protected function get_graph_pagination($count)
	{
		$this->load->library("graph");
		// check whether graph validation error or not
		$this->graph->initialize_pagination($count);

		return $this->graph->get_compile_result_pagination();
	}

	protected function validate_update_user_by_id($rules)
	{
		$this->load->library('form_validation');	 	

		$this->form_validation->set_data($this->parameter, TRUE);
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == FALSE && $this->form_validation->error_array())
		{
			modules::run("Error_module/set_error", "error validation on input data");
			modules::run("Error_module/set_error_code", 400);
			$extra = (!is_array($this->form_validation->error_array())) ? array('invalid_field' => $this->form_validation->error_array()) : $this->form_validation->error_array(); 
			modules::run("Error_module/set_error_extra", $extra);
			return FALSE;
		}
		return TRUE;
	}

	protected function validate_input($parameter, $group, $rules = array())
	{
		$this->load->library('form_validation');	 	
		
		$this->form_validation->set_data($parameter, TRUE);
		if (!empty($rules)) $this->form_validation->set_rules($rules);

		if ($this->form_validation->run($group) == FALSE)
		{
			if ($this->form_validation->run() == FALSE && $this->form_validation->error_array())
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
}