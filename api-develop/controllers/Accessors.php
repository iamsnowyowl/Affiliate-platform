<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accessors extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function read_accessor_data($user_id, $field_name)
	{
		$allowed = array("nik_photo","npwp_photo","certificate");
		if (!in_array($field_name, $allowed)){
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "only allowed field name".implode(",", $allowed),
						"errors" => array(
							"domain" => "ACCESSOR",
							"reason" => "InvalidationLoadData"
						),
					)
				)
			);
		}

		$accessor = modules::run("Accessor_module/read_accessor_data", array("fields" => $field_name), $user_id);

		if (!empty($accessor->$field_name))
		{
			$f = finfo_open();
			$data_src = $accessor->$field_name;
			$mime_type = finfo_buffer($f, $data_src, FILEINFO_MIME_TYPE);

			finfo_close($f);
			if ($mime_type != "application/octet-stream") $this->output->set_output($data_src)->set_content_type($mime_type)->_display();
			else $this->output->set_output($data_src)->_display();
			return;
		}

		$config_file = 'image';
		$this->config->load($config_file, TRUE);

		$base_path = $this->config->item('base_path', $config_file);

		$f = finfo_open();
		$data_src = file_get_contents($base_path.$this->config->item('default_img_user', $config_file));
		$mime_type = finfo_buffer($f, $data_src, FILEINFO_MIME_TYPE);
		finfo_close($f);
		
		$this->output // You could also use ".jpeg" which will have the full stop removed before looking in config/mimes.php
		->set_output($data_src)->set_content_type($mime_type)->_display();
		return;
		

	}

	public function get_own_accessor_detail($user_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$user_id = intval($user_id);
		$data = $this->accessor_list($user_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_own_accessor_list() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];
		$data = $this->accessor_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_public_accessor_detail($user_id)
	{
		if ($this->config->item('app_key') != $this->input->get("app_key", TRUE)) {
			modules::run("Permission_module/require_permission", "ACCESSOR_LIST");
		}

		$this->my_parameter = $this->parameter;
		unset($this->my_parameter["app_key"]);

		$user_id = intval($user_id);
		$data = $this->public_accessor_list($user_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_public_accessor_list() 
	{
		if ($this->config->item('app_key') != $this->input->get("app_key", TRUE)) {
			modules::run("Permission_module/require_permission", "ACCESSOR_LIST");
		}

		$this->my_parameter = $this->parameter;
		unset($this->my_parameter["app_key"]);

		$data = $this->public_accessor_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function public_accessor_list($user_id = NULL)
	{
		$data = array();
		if (!empty($user_id))
		{
			$accessor = modules::run("Accessor_module/get_accessor_by_id", $this->my_parameter, $user_id);

			$this->load->helper("url");

			if ($accessor === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "ACCESSOR",
								"reason" => "AccessorNotFound"
							),
						)
					)
				);
			}

			$data['data'] = $accessor;
		}
		else
		{
			$data = modules::run("Accessor_module/get_accessor_list", $this->my_parameter);
		}

		return $data;
	}

	public function get_accessor_detail($user_id)
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_LIST");
		$this->my_parameter = $this->parameter;

		$user_id = intval($user_id);
		$data = $this->accessor_list($user_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_accessor_list() 
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_LIST");
		$this->my_parameter = $this->parameter;
		$data = $this->accessor_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function accessor_list($user_id = NULL)
	{
		$data = array();
		if (!empty($user_id))
		{
			$accessor = modules::run("Accessor_module/get_accessor_by_id", $this->my_parameter, $user_id);

			$this->load->helper("url");

			if ($accessor === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "ACCESSOR",
								"reason" => "AccessorNotFound"
							),
						)
					)
				);
			}

			$data['data'] = $accessor;
		}
		else
		{
			$data = modules::run("Accessor_module/get_accessor_list", $this->my_parameter);
		}

		return $data;
	}

	public function get_own_accessor_count() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$data = $this->accessor_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_accessor_count() 
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_LIST");
		$this->my_parameter = $this->parameter;

		$data = $this->accessor_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function accessor_count()
	{
		$count = modules::run("Accessor_module/get_accessor_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create accessor
	public function create_accessor_public()
	{
		$this->my_parameter = $this->parameter;
		
		$user_id = $this->create_accessor();
		$data = array('data' => array("user_id" => $user_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function create_accessor_session()
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_CREATE");
		
		$this->my_parameter = $this->parameter;
		$this->my_parameter["activated_date"] = date("Y-m-d H:i:s");
		$created_by = $this->userdata['user_id'];

		$user_id = $this->create_accessor($created_by);
		$data = array('data' => array("user_id" => $user_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function create_accessor($created_by = 0)
	{
		// mark this function for optimise transaction in future
		$rules = modules::run("User_module/get_config", "rules");
		$rules = array_column($rules["create_user"], "field");

		// add role_code rules
		$rules[] = "role_code";

		$user_parameter = array();
		$accessor_parameter = array();

		// every user here must become accessor
		$this->my_parameter['role_code'] = "ACS";

		if (!empty($this->my_parameter["picture"])) unset($this->my_parameter["picture"]);
		if (!empty($this->my_parameter['signature'])) {
			$expl = explode(",", $this->my_parameter['signature']);
			$this->my_parameter['signature'] = base64_decode(array_pop($expl));
		}
		if (!empty($this->my_parameter["image_b64"])) unset($this->my_parameter["image_b64"]);

		foreach ($this->my_parameter as $key => $value) {
			if (in_array($key, $rules)) $user_parameter[$key] = $value;
			else $accessor_parameter[$key] = $value;
		}

		$this->load->model("Transaction_model");

		$this->Transaction_model->trans_start();

		if (!empty($user_parameter))
		{
			$user_id = modules::run("User_module/create_user", $user_parameter, $created_by, FALSE);

			if ($user_id === FALSE)
			{
				// $this->Transaction_model->trans_rollback();
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "USER",
								"reason" => "UserCreateErrorException",
								"extra" => modules::run("Error_module/get_error_extra")
							),
						)
					)
				);
			}
		}

		$accessor_parameter["user_id"] = $user_id;

		if (!empty($accessor_parameter))
		{
			if (!empty($accessor_parameter['npwp_photo'])) {
				$accessor_parameter['npwp_photo'] = base64_decode($accessor_parameter['npwp_photo']);
			}

			if (!empty($accessor_parameter['nik_photo'])) {
				$accessor_parameter['nik_photo'] = base64_decode($accessor_parameter['nik_photo']);
			}

			if (!empty($accessor_parameter['certificate'])) {
				$accessor_parameter['certificate'] = base64_decode($accessor_parameter['certificate']);
			}

			$accessor_id = modules::run("Accessor_module/create_accessor", $accessor_parameter, $created_by, FALSE);
			
			if ($accessor_id === FALSE)
			{
				// $this->Transaction_model->trans_rollback();

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

		if (!empty($_FILES['picture']) && !empty($this->parameter["image_b64"]))
		{
			$upload = modules::run("User_module/upload_user_picture", $user_id, $created_by);

			if ($upload === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "ACCESSOR",
								"reason" => "UploadImageErrorException"
							),
						)
					)
				);
			}	
		}

		if (!empty($this->my_parameter["email"])) modules::run("User_module/reset_password", array("email" => $this->my_parameter["email"]));

		$this->Transaction_model->trans_complete();

		return $user_id;
	}

	public function update_own_accessor_by_id($user_id)
	{
		// check is user already exist or not
		if ($user_id != $this->userdata['user_id'])
		{
			modules::run("Permission_module/require_permission", "ACCESSOR_UPDATE,USER_UPDATE");
		}

		$userdata = modules::run("Accessor_module/get_accessor_by_id", NULL, $user_id);

		if (empty($userdata)){
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ACCESSOR",
							"reason" => "AccessorResourceNotFound",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];
		
		$user_id = intval($user_id);
		$modified_by = $this->userdata['user_id'];
		modules::run("Accessor_module/update_accessor", $this->my_parameter, $user_id, $modified_by);
	}

	public function update_accessor_by_id($user_id)
	{
		if ($user_id != $this->userdata['user_id'])
		{
			modules::run("Permission_module/require_permission", "ACCESSOR_UPDATE,USER_UPDATE");
		}

		// check is user already exist or not
		$userdata = modules::run("Accessor_module/get_accessor_by_id", NULL, $user_id);

		if (empty($userdata))
		{
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ACCESSOR",
							"reason" => "AccessorResourceNotFound",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$this->my_parameter = $this->parameter;
		
		$user_id = intval($user_id);
		$modified_by = $this->userdata['user_id'];
		modules::run("Accessor_module/update_accessor", $this->my_parameter, $user_id, $modified_by);
	}

	public function delete_own_accessor_by_id()
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_DELETE");

		$affected_row = $this->delete_accessor();

		if ($affected_row != count($accessors))
		{
			$code = modules::run("Error_module/get_error_code");
			response(400, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 400,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "ACCESSOR",
							"reason" => "UpdateErrorException",
							"extra" => array("counter_deleted" => $affected_row)
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_accessor_by_id($user_id)
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_DELETE");

		$affected_rows = $this->delete_accessor($user_id);

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_accessor($user_id)
	{
		$parameter = array($user_id);

		$this->load->model("Transaction_model");
		$this->Transaction_model->trans_start();
		$deleted_user = modules::run("User_module/delete_user_by_id", $parameter, FALSE);
		$deleted_accessor = modules::run("Accessor_module/delete_accessor_by_user_id", $parameter, FALSE);

		if (!($deleted_user && $deleted_accessor)) {
			// $this->Transaction_model->trans_rollback();
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "USER",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}
		$this->Transaction_model->trans_complete();

		return $deleted_user;
	}
}


