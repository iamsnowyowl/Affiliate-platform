<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function user_login()
	{
		// get user_login by email and password
		$parameter = $this->parameter;

		$user_data = modules::run("User_module/login", $parameter);

		if ($user_data === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "AUTHENTICATION",
							"reason" => "UnprocessableEntity",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		if (isset($user_data->signature)) unset($user_data->signature);

		// generate user session
		$secret_key = modules::run("Session_module/generate", $parameter['username_email']);

		if (empty($secret_key))
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"code" => $code,
					"error" => array(
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "AUTHENTICATION",
							"reason" => "UnprocessableEntity",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		// get_schema_permission
		$list_permission_code = modules::run("Permission_module/get_permission_by_role_code", $user_data->role_code);

		// set login flag
		$user_data->logged_in = TRUE;
		
		// set user permission table
		$user_data->permission = $list_permission_code;
		// set userdata
		$this->load->config('user_authentication');
		$index_secret_key = $this->config->item('user_authentication')['secret_key']['key'];

		// override user profile url
		$this->load->helper('url');

		$user_data->picture = "/users/".$user_data->user_id."/picture";
		
		$this->load->helper("email");

		$data = array(
			$index_secret_key => $secret_key,
			'data' => $user_data,
			'identity_type' => (valid_email($parameter['username_email'])) ? "email" : "username"
		);

		$sess_data = (array) $user_data;

		if (!empty($user_data->expired_date) && $user_data->expired_date != "2000-01-01 00:00:00") {
			$expired_time = strtotime($user_data->expired_date);
			$current_time = time();
			if ($current_time > $expired_time) {
				$code = 419;
				$message = "Your account already expired. please contact Administrator.";
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => $message,
							"errors" => array(
								"domain" => "AUTHENTICATION",
								"reason" => "ExpireaEntity",
							),
						)
						), $message
				);
			}
		}
		
		$sess_data[$index_secret_key] = $secret_key;

		$this->session->set_userdata($sess_data);

		session_write_close();

		modules::run("User_module/update_login_information", $user_data->user_id);

		$sending = modules::run("Fcm_broadcast_module/create_fcm_broadcast", array(
			"user_id" => $user_data->user_id,
			"click_action" => "WELCOME_MESSAGE",
			"title" => "Welcome",
			"message" => "Selamat Datang Kembali ".ucwords("$user_data->first_name $user_data->last_name"),
			"data" => json_encode(array("first_name" => $user_data->first_name, "last_name" => $user_data->last_name))
			// "scheduled_send_date" => date("Y-m-d H:i:s", strtotime("+ 15 seconds"))
		));

		if ($sending === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "AUTHENTICATION",
							"reason" => "UnprocessableEntity",
							"extra" => $this->form_validation->error_array()
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function setup_password($token)
	{
		$this->load->helper(array("url", "form"));
		$this->load->library('form_validation');
		
		$data = modules::run("User_module/get_hash_reset_password", hash("SHA512", $token, FALSE));
		if (empty($data)){
			redirect($this->config->item('cms_url'));
		}

		$this->form_validation->set_rules('password', 'New Password', 'trim|required|min_length[6]|max_length[100]');
		$this->form_validation->set_rules('passconf', 'Confirm Password', 'trim|required|min_length[6]|max_length[100]|matches[password]');


		if ($this->form_validation->run() == FALSE)
        {
                $this->load->view('setup_password', array("token" => $token));
        }
        else
        {
        	// success upadte delete flag
        	$user = modules::run("User_module/get_user_by_email", array(), $data->email);
        	$affected_row = modules::run("User_module/update_user_password_by_id", $user->user_id, $this->parameter, $user->user_id, FALSE);
        	
        	if ($affected_row === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "USER",
								"reason" => "RequestForgotPassword",
								"extra" => modules::run("Error_module/get_error_extra")
							),
						)
					)
				);
			}

			// deleted successfuly. remove link
        	$parameter = array(
				"deleted_at" => date("Y-m-d H:i:s")
			);

			modules::run("User_module/update_reset_password_by_hash", hash("SHA512", $token, FALSE), $parameter);
            $this->load->view('setup_password_success');
        }
	}

	public function forgot_password()
	{
		if (empty($this->parameter["email"]))
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "error validation on input data",
						"errors" => array(
							"domain" => "USER",
							"reason" => "RequestForgotPassword",
							"extra" => array(
								"email" => "email parameter required"
							)
						),
					)
				)
			);
		}
		
		$user = modules::run("User_module/get_user_by_email", array(), $this->parameter["email"]);

		if ($user !== FALSE)
		{
			$result = modules::run("User_module/reset_password", $this->parameter);

			if ($result === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "USER",
								"reason" => "RequestForgotPassword",
								"extra" => modules::run("Error_module/get_error_extra")
							),
						)
					)
				);
			}
		}


		response(200, array("responseStatus" => "SUCCESS"));
	}

	public function refresh_token($token)
	{
		$parameter = $this->parameter;
		$parameter["user_id"] = $this->userdata["user_id"];
		$parameter["mac_address"] = $this->mac_address;
		$parameter["register_id"] = $token;

		$affected_row = modules::run("Fcm_user_module/create_fcm_user", $parameter);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "FCM",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		if (in_array($this->userdata["role_code"], array("DEV", "ADM", "SUP"))){
			modules::run("Google_module/register_fcm_topic", $token, getenv("TOPIC_ADMIN_LSP"));
		}

		$notification = array();
		$data = array("first_name" => ucwords($this->userdata["first_name"]), "last_name" => ucwords($this->userdata["last_name"]));
		$notification["click_action"] = "WELCOME_MESSAGE";
		$notification["title"] = "Welcome";
		$notification["body"] = "Selamat Datang Kembali ".ucwords($this->userdata["first_name"]." ".$this->userdata["last_name"]);
		$send = modules::run("Google_module/send_fcm_message", array(
			"data" => $data,
			"notification" => $notification
		), ["$token"]);

		response(200, array("responseStatus" => "SUCCESS"));
	}

	public function get_user_profile()
	{
		$this->my_parameter = $this->parameter;
		$profile = array();
		switch ($this->userdata["role_code"]) 
		{
			case 'ACS':
				$profile["data"] = modules::run("Accessor_module/get_accessor_by_id", $this->my_parameter, $this->userdata["user_id"]);
				break;
			case 'APL':
				$profile["data"] = modules::run("Applicant_module/get_applicant_by_id", $this->my_parameter, $this->userdata["user_id"]);
				break;
			case 'ADT':
				$profile["data"] = modules::run("Admintuk_module/get_admintuk_by_id", $this->my_parameter, $this->userdata["user_id"]);
				break;
			case 'MAG':
				$profile["data"] = modules::run("Management_module/get_management_by_id", $this->my_parameter, $this->userdata["user_id"]);
				break;
			default:
				$profile = $this->user_list($this->userdata["user_id"]);
				break;
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $profile));
	}

	public function user_logout()
	{
		$this->load->helper('common');

        $this->session->sess_destroy();

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function get_user_detail($user_id)
	{
		$user_id = intval($user_id);
		
		if ($user_id != $this->userdata['user_id'])
		{
			modules::run("Permission_module/require_permission", "USER_LIST");
		}

		$data = $this->user_list($user_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_user_list() 
	{
		modules::run("Permission_module/require_permission", "USER_LIST");
		$data = $this->user_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function user_list($user_id = NULL)
	{
		$data = array();
		if (!empty($user_id))
		{
			$users = modules::run("User_module/get_user_by_id", $this->input->get(NULL, TRUE), $user_id);

			$this->load->helper("url");

			if ($users === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "USER",
								"reason" => "UserNotFound"
							),
						)
					)
				);
			}
			
			$data['data'] = $users;
		}
		else
		{
			$data = modules::run("User_module/get_user_list", $this->input->get(NULL, TRUE));
		}

		return $data;
	}

	public function user_count()
	{
		modules::run("Permission_module/require_permission", "USER_LIST");

		$count = modules::run("User_module/get_user_count", $this->input->get(NULL, TRUE));
		response(200, array_merge(array("responseStatus" => "SUCCESS"), (array) $count));
	}

	# begin create user
	public function create_user_public()
	{
		$this->my_parameter = $this->parameter;
		
		if (empty($this->my_parameter['role_code']))
		{
			$this->my_parameter['role_code'] = "ANY";
		}

		$user_id = $this->create_user();

		$data = array('data' => array("user_id" => $user_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function create_user_session()
	{
		
		$this->my_parameter = $this->parameter;

		if (!empty($this->my_parameter["role_code"]) && in_array($this->my_parameter["role_code"], array("ACS", "APL", "ADT", "MAG"))) {
			response(400, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 400,
						"message" => "ACS Or APL are not allowed here",
						"errors" => array(
							"domain" => "USER",
							"reason" => "UserCreateErrorException"
						),
					)
				)
			);
		}
		
		$this->my_parameter["activated_date"] = date("Y-m-d H:i:s");

		modules::run("Permission_module/require_permission", "USER_CREATE");
		$created_by = $this->userdata['user_id'];

		$user_id = $this->create_user($created_by);

		$data = array('data' => array("user_id" => $user_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function create_user($created_by = 0)
	{
		if (!empty($this->my_parameter["picture"])) unset($this->my_parameter["picture"]);
		if (!empty($this->my_parameter["image_b64"])) unset($this->my_parameter["image_b64"]);
		if (!empty($this->my_parameter['signature'])) {
			$expl = explode(",", $this->my_parameter['signature']);
			$this->my_parameter['signature'] = base64_decode(array_pop($expl));
		}

		$user_id = modules::run("User_module/create_user", $this->my_parameter, $created_by);
			
		if ($user_id === FALSE)
		{
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

		if (!empty($_FILES['picture'])) $this->upload_user_picture($user_id);
		if (!empty($this->parameter["image_b64"])) $this->upload_user_picture($user_id);


		if (!empty($this->my_parameter["email"])) modules::run("User_module/reset_password", array("email" => $this->my_parameter["email"]));

		return $user_id;
	}

	public function update_profile()
	{
		$this->my_parameter = $this->parameter;
		$modified_by = $this->userdata['user_id'];
		
		switch ($this->userdata["role_code"]) 
		{
			case 'ACS':
				modules::run("Accessor_module/update_accessor", $this->my_parameter, $modified_by, $modified_by);
				break;
			case 'APL':
				modules::run("Applicant_module/update_applicant", $this->my_parameter, $modified_by, $modified_by);
			break;
			case 'ADT':
				modules::run("Admintuk_module/update_admintuk", $this->my_parameter, $modified_by, $modified_by);
				break;
			case 'MAG':
				modules::run("Management_module/update_management", $this->my_parameter, $modified_by, $modified_by);
				break;
			default:
				$this->update_user($modified_by, $modified_by);
				break;
		}
	}

	public function update_user_by_id($user_id)
	{
		$user_id = intval($user_id);
		
		if ($user_id != $this->userdata['user_id'])
		{
			modules::run("Permission_module/require_permission", "USER_UPDATE");
		}
		
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$this->update_user($user_id, $modified_by);
	}

	protected function update_user($user_id, $modified_by)
	{
		if (!empty($this->my_parameter['role_code']) && $user_id == 1)
		{
			response(400, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 400,
						"message" => "you can't demote creator",
						"errors" => array(
							"domain" => "USER",
							"reason" => "DeleteErrorException"
						),
					)
				)
			);
		}

		$affected_row = modules::run("User_module/update_user_by_id", $user_id, $this->my_parameter, $modified_by);
			
		if ($affected_row === FALSE)
		{
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

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function update_password_profile()
	{
		$this->my_parameter = $this->parameter;

		$modified_by = $this->userdata['user_id'];
		$this->update_user_password($this->userdata['user_id'], $modified_by);
	}

	public function update_user_password_by_id($user_id)
	{
		$user_id = intval($user_id);
		$old_password = TRUE;
		if ($user_id != $this->userdata['user_id'])
		{
			modules::run("Permission_module/require_permission", "USER_UPDATE");
			$old_password = FALSE;
		}
		
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$this->update_user_password($user_id, $modified_by, $old_password);
	}

	public function update_user_password($user_id, $modified_by, $old_password = FALSE)
	{
		$affected_row = modules::run("User_module/update_user_password_by_id", $user_id, $this->my_parameter, $modified_by, $old_password);
			
		if ($affected_row === FALSE)
		{
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

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_user_by_id()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$users = array_map("trim", $segs);

		if (in_array($this->userdata['user_id'], $users))
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "user can't self delete",
						"errors" => array(
							"domain" => "USER",
							"reason" => "DeleteErrorException"
						),
					)
				)
			);
		}

		if (in_array(1, $users))
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "you can't delete creator",
						"errors" => array(
							"domain" => "USER",
							"reason" => "DeleteErrorException"
						),
					)
				)
			);
		}

		// require access delete user
		modules::run("Permission_module/require_permission", "USER_DELETE");

		$affected_row = modules::run("User_module/delete_user_by_id", $users);
		if ($affected_row != count($users))
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
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

	public function user_picture($user_id)
	{
		$user_id = intval($user_id);
		
		$picture = modules::run("User_module/get_user_picture_by_id", array(), $user_id);
		if ($picture === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "USER",
							"reason" => "UserNotFound"
						),
					)
				)
			);
		}

		$user_id = md5($user_id);

		$this->load->helper('url');
		$config_file = 'image';
		$this->config->load($config_file, TRUE);

		$base_path = $this->config->item('base_path', $config_file);
		$thumb_path = $base_path.rtrim($this->config->item('thumb_destination_path_profile', $config_file), "/")."/";

		$image_src = $base_path.$picture;

		if (!file_exists($image_src))
		{
			$image_src = $base_path.$this->config->item('default_img_user', $config_file);
		}

		$config['image_library'] = $this->config->item('image_library', $config_file);
		$config['source_image'] = $image_src;
		$config['maintain_ratio'] = $this->config->item('maintain_ratio', $config_file);

		if (!$this->input->get($this->config->item('thumb', $config_file), TRUE))
		{
			if ($this->input->get($this->config->item('width', $config_file), TRUE) && $this->input->get($this->config->item('height', $config_file), TRUE))
			{
				$config['width'] = $this->input->get($this->config->item('width', $config_file), TRUE);
				$config['height'] = $this->input->get($this->config->item('height', $config_file), TRUE);
				$config['maintain_ratio'] = FALSE;
				$config['dynamic_output'] = TRUE;
			}
		}
		else
		{
			$config['maintain_ratio'] = FALSE;
			$config['width'] = $this->config->item('default_width', $config_file);
			$config['height'] = $this->config->item('default_height', $config_file);
			$expl = explode(".", $image_src);
			$config['new_image'] = $thumb_path.$user_id.".".end($expl);
			$image_src = $config['new_image'];
		}

		$this->load->library('image_lib');
		$this->image_lib->initialize($config);

		if ( ! $this->image_lib->resize())
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => $this->image_lib->display_errors(),
						"errors" => array(
							"domain" => "USER",
							"reason" => "ImageError"
						),
					)
				)
			);
		}
		$this->output // You could also use ".jpeg" which will have the full stop removed before looking in config/mimes.php
		->set_output(file_get_contents($image_src))->_display();
		return;
	}

	public function update_profile_picture()
	{
		$this->upload_user_picture($this->userdata['user_id']);
		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function upload_user_picture($user_id)
	{
		$user_id = intval($user_id);
		$modified_by = $user_id;
		
		if ($user_id != $this->userdata['user_id'])
		{
			modules::run("Permission_module/require_permission", "USER_UPDATE");
			$modified_by = $this->userdata["user_id"];
		}

		$result = modules::run("User_module/upload_user_picture", $user_id, $modified_by);

		if ($result === FALSE)
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

		return $result;
	}

	public function get_user_deleted_list()
	{
		$this->my_parameter = $this->parameter;

		$data = $this->user_deleted_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS", "data" => $data)));
	}

	protected function user_deleted_list()
	{
		switch ($this->userdata["role_code"]) {
			case 'DEV':
				return modules::run("User_module/get_user_deleted_list", $this->my_parameter);
			break;
			case 'SUP':
				return modules::run("User_module/get_user_deleted_list", $this->my_parameter);
			break;
			default:
				modules::run("Permission_module/require_permission", "USER_DELETED_LIST");
		break;
		}
	}

	public function update_deleted_by_id($user_id)
	{
		$modified_by = $this->userdata['user_id'];

		$affected_rows = $this->update_deleted_list($user_id, $modified_by);

		if ($affected_rows === FALSE) {
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "USERS",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}
		
		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_deleted_list($user_id, $modified_by)
	{
		switch ($this->userdata["role_code"]) {
			case 'DEV':
				return modules::run("User_module/update_deleted_list", $user_id, $modified_by);
			break;
			case 'SUP':
				return modules::run("User_module/update_deleted_list", $user_id, $modified_by);
			break;
			default:
			modules::run("Permission_module/require_permission", "USER_DELETED_LIST");
		break;
		}
	}
}


