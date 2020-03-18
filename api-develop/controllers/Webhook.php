<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function create_assessment_session()
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$this->my_parameter["tuk_id"] = $api_info->tuk_id;

		$data = $this->create_assessment();
		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function create_assessment($created_by = 0)
	{

		$assessment_id = modules::run("Assessment_module/create_assessment", $this->my_parameter, $created_by);
			
		if ($assessment_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		// assessment has been made by consumer. update last_state_activity
		// review is data ready for flag

		if (!empty($this->my_parameter["address"]) && !empty($this->my_parameter["start_date"]) && !empty($this->my_parameter["end_date"])) {
			$check = modules::run("Assessment_module/update_assessment_by_id", $assessment_id, array("last_activity_state" => "TUK_COMPLETE_FORM", "last_activity_description" => "TUK mengirim permintaan untuk melakukan kegiatan"));
			
			// push notification to all admin
		}

		return array("data" => array("assessment_id" => $assessment_id));
	}

	public function create_assessment_assessment_applicant_session($assessment_id)
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$assessments = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);

		if (empty($assessments->assessment_id)){
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "AssessmentNotFound",
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "AssessmentNotFound"
						),
					)
				)
			);
		}

		if ($assessments->tuk_id != $api_info->tuk_id){
			$code = 401;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "You are not owner of this assessment",
						"errors" => array(
							"domain" => "ASSESSMENT_PORTFOLIO",
							"reason" => "Applicant_portfolioNotFound"
						),
					)
				)
			);
		}

		$this->my_parameter["username"] = "user";
		if (!empty($this->my_parameter["contact"])) {
			$this->my_parameter["username"] = $this->my_parameter["contact"];
		}
		else if (!empty($this->my_parameter["email"])) {
			$expl = explode("@", $this->my_parameter["email"]);
			$this->my_parameter["username"] = $expl[0];
		}

		if (!empty($this->my_parameter["full_name"])){
			$expl = explode(" ", $this->my_parameter["full_name"]);
			if (count($expl) > 1) {
				$this->my_parameter["last_name"] = array_pop($expl); 
				$this->my_parameter["first_name"] = implode(" ", $expl); 
			}
			else $this->my_parameter["first_name"] = implode(" ", $expl); 
			unset($this->my_parameter["full_name"]);
		}

		$data = $this->create_assessment_applicant($assessment_id, $api_info->tuk_id);
		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function create_assessment_applicant($assessment_id, $tuk_id)
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
			$user_id = $this->check_user_is_exist($user_parameter); 
			
			if (empty($user_id)) 
			{
				// user not exist on database. then create
				$user_id = modules::run("User_module/create_user", $user_parameter, 0, FALSE);

				if ($user_id === FALSE)
				{
					$code = 400;
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

					$applicant_parameter["tuk_id"] = $tuk_id;

					if (!empty($applicant_parameter["sub_schema_number"])) unset($applicant_parameter["sub_schema_number"]);

					$applicant_id = modules::run("Applicant_module/create_applicant", $applicant_parameter, 0, FALSE);
					
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
			}

		}

		if (!empty($_FILES['picture']) && !empty($this->parameter["image_b64"]))
		{
			$upload = modules::run("User_module/upload_user_picture", $user_id, 0);

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

		// if (!empty($this->my_parameter["email"])) modules::run("User_module/reset_password", array("email" => $this->my_parameter["email"]));
		$parameter_applicant = array(
			"assessment_id" => $assessment_id,
			"applicant_id" => $user_id,
			"tuk_id" => $tuk_id,
			"sub_schema_number" => $this->my_parameter["sub_schema_number"]
		);

		$assessment_applicant_id = modules::run("Assessment_applicant_module/create_assessment_applicant", $parameter_applicant, 0, FALSE);
			
		if ($assessment_applicant_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_APPLICANT",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("assessment_applicant_id" => $assessment_applicant_id));

		$parameter_applicant["assessment_applicant_id"] = $assessment_applicant_id;

		// create default applicant portfolio
		modules::run("Applicant_portfolio_module/create_default_applicant_portfolio", $parameter_applicant, 0, FALSE);		

		$this->Transaction_model->trans_complete();

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_assessment_by_id($assessment_id)
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$assessment = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);

		if ($assessment->tuk_id != $api_info->tuk_id){
			$code = 401;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "You are not owner of this data"
					)
				)
			);
		}

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_assessment($assessment_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_assessment($assessment, $modified_by)
	{
		return modules::run("Assessment_module/update_assessment_by_id", $assessment, $this->my_parameter, $modified_by);
	}

	public function update_assessment_assessment_applicant_by_id($assessment_id, $assessment_applicant_id)
	{
		$this->my_parameter = $this->parameter;
		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$assessment_applicant = modules::run("Assessment_applicant_module/get_assessment_applicant_by_id", array("assessment_id" => $assessment_id, "tuk_id" => $api_info->tuk_id), $assessment_applicant_id);

		if (empty($assessment_applicant->applicant_id)) {
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "assessment applicant not found"
					)
				)
			);
		}
		

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_assessment_applicant($assessment_id, $assessment_applicant_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_APPLICANT",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_assessment_applicant($assessment_id, $assessment_applicant_id, $modified_by)
	{
		return modules::run("Assessment_applicant_module/update_assessment_applicant_by_id", $assessment_id, $assessment_applicant_id, $this->my_parameter, $modified_by);
	}

	public function delete_soft_assessment_by_id($assessment_id)
	{
		$this->my_parameter = $this->parameter;

		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$assessment = modules::run("Assessment_module/get_assessment_by_id", array("tuk_id" => $api_info->tuk_id), $assessment_id);

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_assessment($assessment_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_assessment($assessment_id, $modified_by = 0)
	{
		return modules::run("Assessment_module/delete_soft_assessment_by_id", $assessment_id, $modified_by);
	}

	public function delete_soft_assessment_assessment_applicant_by_id($assessment_id, $assessment_applicant_id)
	{
		$this->my_parameter = $this->parameter;

		$api_info = modules::run("Client_module/get_api_key_information", array(), $this->request_info["api_key"]);

		if ($api_info === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error")
					)
				)
			);
		}

		$assessment_applicant = modules::run("Assessment_applicant_module/get_assessment_applicant_by_id", array("assessment_id" => $assessment_id, "tuk_id" => $api_info->tuk_id), $assessment_applicant_id);

		if (empty($assessment_applicant->assessment_applicant_id)){
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "resource not found"
					)
				)
			);
		}


		$modified_by = 0;
		$affected_rows = $this->delete_soft_assessment_applicant($assessment_id, $assessment_applicant_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_APPLICANT",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_assessment_applicant($assessment_id, $assessment_applicant_id, $modified_by = 0)
	{
		return modules::run("Assessment_applicant_module/delete_soft_assessment_applicant_by_id", $assessment_id, $assessment_applicant_id, $modified_by);
	}

	protected function check_user_is_exist($user_parameter)
	{
		// user already exist. no need to insert. now get user id of user
		$user_id = 0;

		if (!empty($user_parameter["email"]))
		{
			$check = modules::run("User_module/get_user_by_email", NULL, $user_parameter['email']);

			if (!empty($check->user_id))
			{
				$user_id = $check->user_id;
				// check is user is register as another role?
				if ($check->role_code != "APL"){
					$code = 400;
					response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => "User email already register as another role",
							"errors" => array(
								"domain" => "USER",
								"reason" => "UserCreateErrorException"
							),
						)
					));
				}
			}
		}

		// check is user already created or not
		if (!empty($user_parameter["username"]))
		{
			$check = modules::run("User_module/get_user_by_username", NULL, $user_parameter['username']);

			if (!empty($check->user_id))
			{
				$user_id = $check->user_id;

				// check is user is register as another role?
				if ($check->role_code != "APL"){
					$code = 400;
					response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => "User username already register as another role",
							"errors" => array(
								"domain" => "USER",
								"reason" => "UserCreateErrorException"
							),
						)
					));
				}
			}
		}

		return $user_id;
	}
}