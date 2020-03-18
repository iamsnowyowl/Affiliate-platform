<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Managements extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_own_management_detail($user_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$user_id = intval($user_id);
		$data = $this->management_list($user_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_own_management_list() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];
		$data = $this->management_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_management_detail($user_id)
	{
		modules::run("Permission_module/require_permission", "USER_LIST");
		$this->my_parameter = $this->parameter;

		$user_id = intval($user_id);
		$data = $this->management_list($user_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_management_list() 
	{
		modules::run("Permission_module/require_permission", "USER_LIST");
		$this->my_parameter = $this->parameter;
		$data = $this->management_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function management_list($user_id = NULL)
	{
		$data = array();
		if (!empty($user_id))
		{
			$management = modules::run("Management_module/get_management_by_id", $this->my_parameter, $user_id);

			$this->load->helper("url");

			if ($management === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "USER",
								"reason" => "ManagementNotFound"
							),
						)
					)
				);
			}

			$data['data'] = $management;
		}
		else
		{
			$data = modules::run("Management_module/get_management_list", $this->my_parameter);
		}

		return $data;
	}

	public function get_own_management_count() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$data = $this->management_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_management_count() 
	{
		modules::run("Permission_module/require_permission", "USER_LIST");
		$this->my_parameter = $this->parameter;

		$data = $this->management_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function management_count()
	{
		$count = modules::run("Management_module/get_management_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create management
	public function create_management_public()
	{
		$this->my_parameter = $this->parameter;
		
		$user_id = $this->create_management();
		$data = array('data' => array("user_id" => $user_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function create_management_session()
	{
		modules::run("Permission_module/require_permission", "USER_CREATE");
		
		$this->my_parameter = $this->parameter;
		$this->my_parameter["activated_date"] = date("Y-m-d H:i:s");
		$created_by = $this->userdata['user_id'];

		$user_id = $this->create_management($created_by);
		$data = array('data' => array("user_id" => $user_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function create_management($created_by = 0)
	{
		// mark this function for optimise transaction in future
		$rules = modules::run("User_module/get_config", "rules");
		$rules = array_column($rules["create_user"], "field");

		// add role_code rules
		$rules[] = "role_code";

		$user_parameter = array();
		$management_parameter = array();

		// every user here must become management
		$this->my_parameter['role_code'] = "MAG";

		if (!empty($this->my_parameter["picture"])) unset($this->my_parameter["picture"]);
		if (!empty($this->my_parameter["image_b64"])) unset($this->my_parameter["image_b64"]);

		foreach ($this->my_parameter as $key => $value) {
			if (in_array($key, $rules)) $user_parameter[$key] = $value;
			else $management_parameter[$key] = $value;
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

		$management_parameter["user_id"] = $user_id;

		if (!empty($management_parameter))
		{
			$management_id = modules::run("Management_module/create_management", $management_parameter, $created_by, FALSE);
			
			if ($management_id === FALSE)
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
								"domain" => "USER",
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

	public function update_own_management_by_id($user_id)
	{
		// check is user already exist or not
		if ($user_id != $this->userdata['user_id'])
		{
			modules::run("Permission_module/require_permission", "USER_UPDATE,USER_UPDATE");
		}

		$userdata = modules::run("Management_module/get_management_by_id", NULL, $user_id);

		if (empty($userdata)){
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "USER",
							"reason" => "ManagementResourceNotFound",
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
		modules::run("Management_module/update_management", $this->my_parameter, $user_id, $modified_by);
	}

	public function update_management_by_id($user_id)
	{
		if ($user_id != $this->userdata['user_id'])
		{
			modules::run("Permission_module/require_permission", "USER_UPDATE,USER_UPDATE");
		}

		// check is user already exist or not
		$userdata = modules::run("Management_module/get_management_by_id", NULL, $user_id);

		if (empty($userdata))
		{
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "USER",
							"reason" => "ManagementResourceNotFound",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$this->my_parameter = $this->parameter;
		
		$user_id = intval($user_id);
		$modified_by = $this->userdata['user_id'];
		modules::run("Management_module/update_management", $this->my_parameter, $user_id, $modified_by);
	}

	public function delete_own_management_by_id()
	{
		modules::run("Permission_module/require_permission", "USER_DELETE");

		$affected_row = $this->delete_management();

		if ($affected_row != count($managements))
		{
			$code = modules::run("Error_module/get_error_code");
			response(400, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 400,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "USER",
							"reason" => "UpdateErrorException",
							"extra" => array("counter_deleted" => $affected_row)
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_management_by_id($user_id)
	{
		modules::run("Permission_module/require_permission", "USER_DELETE");

		$affected_rows = $this->delete_management($user_id);

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_management($user_id)
	{
		$parameter = array($user_id);

		$this->load->model("Transaction_model");
		$this->Transaction_model->trans_start();
		$deleted_user = modules::run("User_module/delete_user_by_id", $parameter, FALSE);
		$deleted_management = modules::run("Management_module/delete_management_by_user_id", $parameter, FALSE);

		if (!($deleted_user && $deleted_management)) {
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


