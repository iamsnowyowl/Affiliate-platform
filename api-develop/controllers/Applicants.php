<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applicants extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_applicant_detail($applicant_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "APPLICANT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICANT_LIST");
		else {
			switch ($this->userdata["role_code"]) {
				case 'ADT':
					$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
					$this->my_parameter["tuk_id"] = $admin_data->tuk_id; 
					break;
				default:
					$this->my_parameter["created_by"] = $this->userdata["user_id"]; 
					break;
			}
		}

		$data = $this->applicant_detail($applicant_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_applicant_list() 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "APPLICANT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICANT_LIST");
		else {
			switch ($this->userdata["role_code"]) {
				case 'ADT':
					$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
					$this->my_parameter["tuk_id"] = $admin_data->tuk_id; 
					break;
				default:
					$this->my_parameter["created_by"] = $this->userdata["user_id"]; 
					break;
			}
		}

		$data = $this->applicant_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function applicant_detail($applicant_id)
	{
		$applicants = modules::run("Applicant_module/get_applicant_by_id", $this->my_parameter, $applicant_id);

		$this->load->helper("url");

		if ($applicants === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICANT",
							"reason" => "ApplicantNotFound"
						),
					)
				)
			);
		}

		return array("data" => $applicants);
	}

	protected function applicant_list()
	{
		return modules::run("Applicant_module/get_applicant_list", $this->my_parameter);
	}

	public function get_applicant_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "APPLICANT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICANT_LIST");
		else {
			switch ($this->userdata["role_code"]) {
				case 'ADT':
					$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
					$this->my_parameter["tuk_id"] = $admin_data->tuk_id; 
					break;
				default:
					$this->my_parameter["created_by"] = $this->userdata["user_id"]; 
					break;
			}
		}

		$data = $this->applicant_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function applicant_count()
	{
		$count = modules::run("Applicant_module/get_applicant_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create applicant
	public function create_applicant_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_applicant();
	}

	public function create_applicant_session()
	{

		$this->my_parameter = $this->parameter;
		$this->my_parameter["activated_date"] = date("Y-m-d H:i:s");
		
		if (!modules::run("Permission_module/require_permission", "APPLICANT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "APPLICANT_LIST");
		else {
			switch ($this->userdata["role_code"]) {
				case 'ADT':
					$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
					$this->my_parameter["tuk_id"] = $admin_data->tuk_id; 
					break;
			}
		}
		
		$created_by = $this->userdata['user_id'];

		$user_id = $this->create_applicant($created_by);
		$data = array('data' => array("user_id" => $user_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function create_applicant($created_by = 0)
	{
		// mark this function for optimise transaction in future
		$rules = modules::run("User_module/get_config", "rules");
		$rules = array_column($rules["create_user"], "field");

		// add role_code rules
		$rules[] = "role_code";

		$user_parameter = array();
		$applicant_parameter = array();

		// every user here must become applicant
		$this->my_parameter['role_code'] = "APL";

		if (!empty($this->my_parameter["picture"])) unset($this->my_parameter["picture"]);
		if (!empty($this->my_parameter["image_b64"])) unset($this->my_parameter["image_b64"]);

		foreach ($this->my_parameter as $key => $value) {
			if (in_array($key, $rules)) $user_parameter[$key] = $value;
			else $applicant_parameter[$key] = $value;
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

		$applicant_parameter["user_id"] = $user_id;

		if (!empty($applicant_parameter))
		{
			if (!empty($applicant_parameter['nik_photo'])) {
				$applicant_parameter['nik_photo'] = base64_decode($applicant_parameter['nik_photo']);
			}

			if (!empty($applicant_parameter['last_education_certificate_photo'])) {
				$applicant_parameter['last_education_certificate_photo'] = base64_decode($applicant_parameter['last_education_certificate_photo']);
			}

			if (!empty($applicant_parameter['training_certificate_photo'])) {
				$applicant_parameter['training_certificate_photo'] = base64_decode($applicant_parameter['training_certificate_photo']);
			}

			if (!empty($applicant_parameter['colored_photo'])) {
				$applicant_parameter['colored_photo'] = base64_decode($applicant_parameter['colored_photo']);
			}

			if (!empty($applicant_parameter['family_card_photo'])) {
				$applicant_parameter['family_card_photo'] = base64_decode($applicant_parameter['family_card_photo']);
			}
			
			if (!empty($applicant_parameter['npwp_photo'])) {
				$applicant_parameter['npwp_photo'] = base64_decode($applicant_parameter['npwp_photo']);
			}

			$applicant_id = modules::run("Applicant_module/create_applicant", $applicant_parameter, $created_by, FALSE);
			
			if ($applicant_id === FALSE)
			{
				// $this->Transaction_model->trans_rollback();

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
								"domain" => "APPLICANT",
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

	public function update_applicant_by_id($applicant_id)
	{
		$this->my_parameter = $this->parameter;

		$applicant = modules::run("Applicant_module/get_applicant_by_id", array(), $applicant_id);

		switch ($this->userdata["role_code"]) 
		{
			case 'ADT':
				$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
				if (!(modules::run("Permission_module/require_permission", "APPLICANT_CREATE_OWN", FALSE) && $applicant->tuk_id == $admin_data->tuk_id)) modules::run("Permission_module/require_permission", "APPLICANT_UPDATE");
				break;
			default:
				if (!(modules::run("Permission_module/require_permission", "APPLICANT_CREATE_OWN", FALSE) && $applicant->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "APPLICANT_UPDATE");
				break;
		}

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_applicant($applicant_id, $modified_by);

		if ($affected_row === FALSE)
		{
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

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_applicant($applicant, $modified_by)
	{
		return modules::run("Applicant_module/update_applicant", $this->my_parameter, $applicant, $modified_by);
	}

	public function delete_soft_applicant_by_id($applicant_id)
	{
		$applicant = modules::run("Applicant_module/get_applicant_by_id", array(), $applicant_id);
		switch ($this->userdata["role_code"]) 
		{
			case 'ADT':
				$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
				$this->my_parameter["tuk_id"] = $admin_data->tuk_id; 
				if (!(modules::run("Permission_module/require_permission", "APPLICANT_CREATE_OWN", FALSE) && $applicant->tuk_id == $admin_data->tuk_id)) modules::run("Permission_module/require_permission", "APPLICANT_UPDATE");
				break;
			default:
				if (!(modules::run("Permission_module/require_permission", "APPLICANT_CREATE_OWN", FALSE) && $applicant->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "APPLICANT_UPDATE");
				break;
		}

		// if (!(modules::run("Permission_module/require_permission", "APPLICANT_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "APPLICANT_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_row = modules::run("User_module/delete_user_by_id", [$applicant_id]);
		$affected_rows = $this->delete_soft_applicant($applicant_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICANT",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_applicant($applicant_id, $modified_by = 0)
	{
		return modules::run("Applicant_module/delete_applicant_by_user_id", $applicant_id, $modified_by);
	}

	public function delete_hard_applicant_by_id($applicant_id, $confirmation)
	{
		$applicant = modules::run("Applicant_module/get_applicant_by_id", array(), $applicant_id);

		switch ($this->userdata["role_code"]) 
		{
			case 'ADT':
				$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
				$this->my_parameter["tuk_id"] = $admin_data->tuk_id; 
				if (!(modules::run("Permission_module/require_permission", "APPLICANT_CREATE_OWN", FALSE) && $applicant->tuk_id == $admin_data->tuk_id)) modules::run("Permission_module/require_permission", "APPLICANT_UPDATE");
				break;
			default:
				if (!(modules::run("Permission_module/require_permission", "APPLICANT_CREATE_OWN", FALSE) && $applicant->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "APPLICANT_UPDATE");
				break;
		}

		// if (!(modules::run("Permission_module/require_permission", "APPLICANT_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "APPLICANT_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_applicant($applicant_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "APPLICANT",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_applicant($applicant_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Applicant_module/delete_hard_applicant_by_id", $applicant_id, $confirmation, $modified_by);
	}
}