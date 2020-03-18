<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_module extends MX_Controller {

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
		$configuration_name = 'configuration_background_worker_module';
		$this->config->load($definition_name, TRUE, TRUE);
		$this->config->load($rules_name, TRUE, TRUE);
		$this->config->load($configuration_name, TRUE, TRUE);
		$this->definition = $this->config->item($definition_name);
		$this->rules = $this->config->item($rules_name);
		$this->configuration = $this->config->item($configuration_name);
		$this->node = strtolower(get_class($this));
	}

	public function login($parameter)
	{
		$this->my_parameter = $parameter;
		if ($this->validate_input("login") === FALSE) return FALSE;
		return modules::run("Authentication_module/check", $this->my_parameter['username_email'], $this->my_parameter['password']);
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

	public function get_user_by_username($parameter = array(), $username, $default = "default", $optional = "optional")
	{
		$this->load->helper('common');
		
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("User_model");

		$user = $this->User_model->get_user_by_username($username, $graph->select);

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

		if (isset($user->picture)) $user->picture = "/users/".$user->user_id."/picture";

		if (isset($user->signature)) {
			$user->signature = "data:image/png;base64,".base64_encode($user->signature);
		}

		if (isset($user->date_of_birth)) {
			$user->m_date_of_birth =  date("d-m-Y", strtotime($user->date_of_birth));
			$user->date_of_birth =  date("Y-m-d", strtotime($user->date_of_birth));
		}
		else $user->m_date_of_birth =  NULL;

		return $user;
	}

	public function get_user_picture_by_id($parameter = array(), $user_id, $default = "default", $optional = "optional")
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
		return $user->picture;
	}

	public function get_user_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("User_model");

		$user = $this->User_model->get_user_list($graph);

		foreach ($user as $key => $value) 
		{
			if (isset($user[$key]->signature)) {
				$user[$key]->signature = "data:image/png;base64,".base64_encode($user[$key]->signature);
			}
			if (isset($user[$key]->picture)) $user[$key]->picture = "/users/".$user[$key]->user_id."/picture";
			if (isset($user[$key]->date_of_birth)) {
				$user[$key]->m_date_of_birth =  date("d-m-Y", strtotime($user[$key]->date_of_birth));
				$user[$key]->date_of_birth =  date("Y-m-d", strtotime($user[$key]->date_of_birth));
			}
			else $user[$key]->m_date_of_birth =  NULL;
			if (isset($user[$key]->nik_photo)) $user[$key]->nik_photo = base64_encode($user[$key]->nik_photo);
			if (isset($user[$key]->npwp_photo)) $user[$key]->npwp_photo = base64_encode($user[$key]->npwp_photo);
			if (isset($user[$key]->last_education_certificate_photo)) base64_encode($user[$key]->last_education_certificate_photo);
			if (isset($user[$key]->training_certificate_photo)) base64_encode($user[$key]->training_certificate_photo);
			if (isset($user[$key]->colored_photo)) base64_encode($user[$key]->colored_photo);
			if (isset($user[$key]->family_card_photo)) base64_encode($user[$key]->family_card_photo);
		}

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

	public function get_user_deleted_list($parameter = array(), $default = "default_deleted_list" , $optional = "optional_deleted_list")
	{
		$graph = $this->get_graph_result($parameter , $default , $optional);
		$this->load->model("User_model");

		$user = $this->User_model->get_user_deleted_list($graph);
		$user_count = $this->get_user_deleted_count($parameter);
		$graph_pagination = $this->get_graph_pagination($user_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? 	http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $user_count->count,
			'data' => $user,
			'pagination' => $graph_pagination
		);

		return $data;
	}

	public function get_user_deleted_count($parameter = array(), $default = "default_deleted_list", $optional = "optional_deleted_list")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("User_model");

		$user_count = $this->User_model->get_user_deleted_count($graph);

		return $user_count;
	}
	
	public function get_user_not_assign_list($parameter = array(), $default = "default_region_list", $optional = "optional_region_list")
	{
		// count total bill status.
		$this->load->model("User_model");
		$list_user = $this->User_model->get_user_not_assign_list();

		return (array) $list_user;
	}

	public function create_user($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$role_list = modules::run("Role_module/get_role_list", "role_code");
		$available_role = implode(",", array_map("trim", array_column($role_list, 'role_code')));

		$rules = array(
			array(
				'field' => 'role_code',
				'rules' => 'trim|required|in_list['.$available_role.']'
			)
		);

		$this->my_parameter = $parameter;
		if ($this->validate_input("create_user", $rules) === FALSE) return FALSE;

		if (strpos($this->my_parameter['username'], " ") !== FALSE) {
			modules::run("Error_module/set_error", "username shouldn't have space between character");
			modules::run("Error_module/set_error_code", 400);
			return FALSE;
		}

		// check is user already created or not
		$check = modules::run("User_module/get_user_by_email", NULL, $this->my_parameter['email']);

		if (!empty($check->user_id))
		{
			modules::run("Error_module/set_error", "User already exist");
			modules::run("Error_module/set_error_code", 409);
			modules::run("Error_module/set_error_extra", array("email" => "This user already exist"));
			return FALSE;
		}

		// check is user already created or not
		$check = modules::run("User_module/get_user_by_username", NULL, $this->my_parameter['username']);

		if (!empty($check->user_id))
		{
			modules::run("Error_module/set_error", "User already exist");
			modules::run("Error_module/set_error_code", 409);
			modules::run("Error_module/set_error_extra", array("username" => "This user already exist"));
			return FALSE;
		}

		$this->load->model("User_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		// convert password to bcrypt
		if (!empty($this->my_parameter['password']))
		{
			$this->my_parameter['password'] = password_hash($this->my_parameter['password'], PASSWORD_BCRYPT);
			unset($this->my_parameter['passconf']);
		}

		$user_id = $this->User_model->create_user($this->my_parameter, $auto_commit);

		return $user_id;
	}

	public function update_user_by_id($user_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;
		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if (!empty($this->my_parameter['signature'])) {
			$expl = explode(",", $this->my_parameter['signature']);
			$this->my_parameter['signature'] = base64_decode(array_pop($expl));
		}

		if ($this->validate_input("update_user") === FALSE) return FALSE;
		
		$this->load->model("User_model");

		// add extra parameter
		$user_id = intval($user_id);
		$this->my_parameter['modified_by'] = intval($modified_by);

		$affected_row = $this->User_model->update_user_by_id($this->my_parameter, $user_id, $auto_commit);

		return $affected_row;
	}

	public function update_user_password_by_id($user_id, $parameter, $modified_by = 0, $old_password = FALSE, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;
		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($old_password)
		{
			if ($this->validate_input("update_user_password_by_old_password", array()) === FALSE) return FALSE;
			// checking old password
			$userdata = $this->get_user_by_id(array(), $user_id, FALSE);
			// user exist. now verify password
			if (password_verify($this->my_parameter['oldpassword'], $userdata->password) === FALSE)
			{
				modules::run("Error_module/set_error", "Old password doesn`t match.");
				modules::run("Error_module/set_error_code", 422);
				modules::run("Error_module/set_error_extra", array("password" => "invalidauthentication"));
				return FALSE;
			}

			unset($this->my_parameter['oldpassword']);
		}
		else
		{
			if ($this->validate_input("update_user_password", array()) === FALSE) return FALSE;
		}


		$this->load->model("User_model");

		// add extra parameter
		$user_id = intval($user_id);
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		// convert password to bcrypt
		if (!empty($this->my_parameter['password']))
		{
			$this->my_parameter['password'] = password_hash($this->my_parameter['password'], PASSWORD_BCRYPT);
			unset($this->my_parameter['passconf']);
		}

		$affected_row = $this->User_model->update_user_by_id($this->my_parameter, $user_id, $auto_commit);
		return $affected_row;
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

		return $affected_row;
	}

	public function update_login_information($user_id)
	{
		$this->load->model("User_model");

		$status = $this->User_model->update_login_information($user_id);
		return $status;
	}

	public function reset_password($parameter, $auto_commit = TRUE) {
		$this->my_parameter = $parameter;
		
		if (empty($this->my_parameter))
		{
			return FALSE;
		}
		
		if ($this->validate_input("reset_password") === FALSE) return FALSE;
		
		$this->load->model("User_model");

		do 
		{
			$token = generate_random_base62_string($this->configuration["reset_password"]["token_length"]);
			$params = array(
				"email" => $this->my_parameter["email"],
				"token" => $token,
				"hash" => hash("SHA512", $token, FALSE)
			);

			$data = $this->get_hash_reset_password($params["hash"]);
		}
		while (!empty($data));

		$expired_date = $this->configuration["reset_password"]["expired_range"];
		$params["expired_date"] = date("Y-m-d H:i:s", strtotime($expired_date));

		$this->User_model->create_hash_reset_password($params);

		return $this;
	}

	public function update_reset_password_by_hash($hash, $parameter, $auto_commit = TRUE)
	{
		$affected_row = $this->User_model->update_reset_password_by_hash($parameter, $hash, $auto_commit);

		return $affected_row;
	}

	public function get_hash_reset_password($hash) {
		$this->load->model("User_model");
		return $this->User_model->get_hash_reset_password($hash);
	}

	public function get_list_hash_reset_password($count_retry_email = 0, $limit = 10) 
	{
		$this->load->model("User_model");

		$data = $this->User_model->get_list_hash_reset_password($count_retry_email, $limit);

		return $data;
	}

	public function upload_user_picture($user_id, $modified_by)
	{
		if (!empty($this->parameter['image_b64']))
		{
			$config_file = "image_b64";
			$this->config->load($config_file, TRUE, TRUE);
			$config = $this->config->item($config_file);

			$config['user']['filename'] = md5($user_id);
			$config['user']['thumb_filename'] = md5($user_id);
			// debug($config);
			$img = $this->parameter['image_b64'];
			$this->load->library('image_lib');
			if (!$this->image_lib->store_image_from_base64($img, $config['user']))
			{
				modules::run("Error_module/set_error", "Image parsing failure");
				modules::run("Error_module/set_error_code", 400);
				return FALSE;
			}

			$path = implode(
				array(
					$config['user']['path_destination'],
					$config['user']['unique_path'],
					"/",
					$config['user']['filename'],
					".",
					$config['user']['ext']
				)
			);

			$affected_row = modules::run("User_module/update_user_by_id", $user_id, array("picture" => $path), $modified_by);

			if ($affected_row === FALSE)
			{
				modules::run("Error_module/set_error", "UpdateErrorException");
				modules::run("Error_module/set_error_code", modules::run("Error_module/get_error_code"));
				modules::run("Error_module/set_error_extra", modules::run("Error_module/get_error_extra"));
				return FALSE;
			}

			return TRUE;
		}
		else if(empty($_FILES['picture'])) 
		{
			modules::run("Error_module/set_error", "Bad parameter request");
			modules::run("Error_module/set_error_code", 400);
			modules::run("Error_module/set_error_extra", "it seems like you doesn't have file to upload");
			return FALSE;
		}

		$config_file = "image";
		$this->config->load($config_file, TRUE, TRUE);
		$config = $this->config->item($config_file);
		$path_name = "/".md5($user_id);
		$config['upload_path']          = $config['base_path'].$config['upload_destination']."/".$path_name."/";
		$config['allowed_types']        = 'jpg|png|jpeg';

		if (!file_exists($config['upload_path'])) mkdir($config['upload_path'], 0755, true);

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('picture'))
		{
			$error = array('error' => $this->upload->display_errors());
			modules::run("Error_module/set_error", "Upload error");
			modules::run("Error_module/set_error_code", 400);
			modules::run("Error_module/set_error_extra", $error);
			return FALSE;
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$parameter = array("picture"=>$config['upload_destination']."/".$path_name."/".$data['upload_data']['file_name']);
			$affected_row = modules::run("User_module/update_user_by_id", $user_id, $parameter, $modified_by);
			
			if ($affected_row === FALSE)
			{
				modules::run("Error_module/set_error", "Upload error");
				modules::run("Error_module/set_error_code", modules::run("Error_module/get_error_code"));
				modules::run("Error_module/set_error_extra", modules::run("Error_module/get_error_extra"));
				return FALSE;
			}

			return $data;
		}
	}

	public function get_config($config_name){
		return $this->$config_name;
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

	public function update_deleted_list($user_id, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = array(
			"deleted_at" => "2000-01-01 00:00:00"
		);
		$this->my_parameter['modified_by'] = intval($modified_by);

		if ($this->validate_input("update_user") === FALSE) return FALSE;

		$this->load->model("User_model");

		$affected_rows = $this->User_model->update_deleted_list($user_id, $this->my_parameter, $auto_commit);

		return $affected_rows;
	}
}