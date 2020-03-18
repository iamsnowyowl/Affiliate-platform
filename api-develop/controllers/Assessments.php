<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assessments extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_draft_assessment_list() 
	{
		$this->my_parameter = $this->parameter;
		switch ($this->userdata["role_code"]) {
			case 'DEV':
				if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
				$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
				$this->my_parameter["tuk_id"] = $admin_data->tuk_id;
				$this->my_parameter["request_date"] = "2000-01-01 00:00:00";
				break;
			default:
				if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
				break;
		}

		$data = $this->assessment_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function owner_get_assessment_detail($assessment_id)
	{
		$this->my_parameter = $this->parameter;

		switch ($this->userdata["role_code"]) {
			case 'ADT':
				if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
				$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
				$this->my_parameter["tuk_id"] = $admin_data->tuk_id; 
				break;
			case 'ACS':
				$this->my_parameter["identifier"] = intval($this->userdata["user_id"]);
				break;
			case 'APL':
				$this->my_parameter["applicant_id"] = intval($this->userdata["user_id"]);
				break;
			default:
				if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
				break;
		}
		$this->my_parameter["request_date"] = "(".strtotime("2000-01-01 00:00:01").",".time().")";

		$data = $this->assessment_detail($assessment_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_detail($assessment_id)
	{
		$this->my_parameter = $this->parameter;

		switch ($this->userdata["role_code"]) {
			case 'ADT':
				if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
				$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
				$this->my_parameter["tuk_id"] = $admin_data->tuk_id; 
				break;
			case 'ACS':
				$this->my_parameter["identifier"] = intval($this->userdata["user_id"]);
				break;
			case 'APL':
				$this->my_parameter["applicant_id"] = intval($this->userdata["user_id"]);
				break;
			default:
				if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
				break;
		}
		$this->my_parameter["request_date"] = "(".strtotime("2000-01-01 00:00:01").",".time().")";

		$data = $this->assessment_detail($assessment_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function owner_get_assessment_list() 
	{
		$this->my_parameter = $this->parameter;

		switch ($this->userdata["role_code"]) {
			
			case 'APL':
				$this->my_parameter["applicant_id"] = intval($this->userdata["user_id"]);
				// $this->my_parameter["last_activity_state"] = "ADMIN_CONFIRM_FORM,REAL_ASSESSMENT";
				break;
		}

		$this->my_parameter["request_date"] = "(".strtotime("2000-01-01 00:00:01").",".time().")";

		$data = $this->assessment_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}
	
	public function get_assessment_list() 
	{		
		$this->my_parameter = $this->parameter;
	
		switch ($this->userdata["role_code"]) {
			case 'ADT':
				if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
				$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
				$this->my_parameter["tuk_id"] = $admin_data->tuk_id; 
			break;
			case 'ACS':
				$this->my_parameter["identifier"] = intval($this->userdata["user_id"]);
			break;
			case 'SUP':
				// $this->my_parameter["identifier"] = intval($this->userdata["user_id"]);
			break;
			case 'APL':
				// $this->my_parameter["last_activity_state"] = "ADMIN_CONFIRM_FORM,REAL_ASSESSMENT";
			break;
			default:
				if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		break;
	}
	
	$this->my_parameter["request_date"] = "(".strtotime("2000-01-01 00:00:01").",".time().")";

	$data = $this->assessment_list();
	response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function assessment_detail($assessment_id)
	{
		switch ($this->userdata["role_code"]) {
			case 'ACS':
				$assessments = modules::run("Assessment_module/non_admin_get_assessment_by_id", $this->my_parameter, $assessment_id);
				break;
			case 'APL':
			if (empty($this->my_parameter["applicant_id"])) $assessments = modules::run("Assessment_module/get_assessment_by_id", $this->my_parameter, $assessment_id);
				else $assessments = modules::run("Assessment_applicant_module/get_assessment_by_id", $this->my_parameter, $assessment_id);
				// $assessments = modules::run("Assessment_applicant_module/get_assessment_by_id", $this->my_parameter, $assessment_id);
				break;
			default:
				$assessments = modules::run("Assessment_module/get_assessment_by_id", $this->my_parameter, $assessment_id);
				break;
		}

		$this->load->helper("url");

		if ($assessments === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT",
							"reason" => "AssessmentNotFound"
						),
					)
				)
			);
		}

		return array("data" => $assessments);
	}

	protected function assessment_list()
	{
		switch ($this->userdata["role_code"]) {
			case 'ACS':
				return modules::run("Assessment_module/non_admin_get_assessment_list", $this->my_parameter);
				break;
			// case 'SUP':
			// 		return modules::run("Assessment_module/non_admin_get_assessment_list", $this->my_parameter);
			// 		break;
			case 'APL':
				if (empty($this->my_parameter["applicant_id"])) return modules::run("Assessment_module/get_assessment_list", $this->my_parameter);
				else return modules::run("Assessment_applicant_module/get_assessment_list", $this->my_parameter);
				break;
			default:
				return modules::run("Assessment_module/get_assessment_list", $this->my_parameter);
				break;
		}
	}

	public function get_assessment_count() 
	{
		$this->my_parameter = $this->parameter;

		switch ($this->userdata["role_code"]) {
			case 'ADT':
				if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
				$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
				$this->my_parameter["tuk_id"] = $admin_data->tuk_id; 
				break;
			case 'ACS':
			case 'APL':
				$this->my_parameter["identifier"] = intval($this->userdata["user_id"]);
				break;
			default:
				if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
				break;
		}

		$data = $this->assessment_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function assessment_count()
	{
		switch ($this->userdata["role_code"]) {
			case 'ACS':
				$count = modules::run("Assessment_module/non_admin_get_assessment_count", $this->my_parameter);
				break;
			default:
				$count = modules::run("Assessment_module/get_assessment_count", $this->my_parameter);
				break;
		}
		return (array) $count;
	}

	# begin create assessment
	public function create_assessment_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_assessment();
	}

	public function create_assessment_session()
	{
		$this->my_parameter = $this->parameter;

		switch ($this->userdata["role_code"]) {
			case 'ADT':
				if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
				$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
				$this->my_parameter["tuk_id"] = $admin_data->tuk_id;
				break;
			case 'ACS':
				$this->my_parameter["identifier"] = intval($this->userdata["user_id"]);
				break;
			case 'APL':
				$this->my_parameter["identifier"] = intval($this->userdata["user_id"]);
				break;
			default:
				$this->my_parameter["request_date"] = date("Y-m-d H:i:s");
				if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
				break;
		}

		$created_by = $this->userdata['user_id'];

		$this->create_assessment($created_by);
	}

	public function send_draft_assessment_by_id()
	{
		switch ($this->userdata["role_code"]) 
		{
			case 'ADT':
				$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
				if (!empty($this->parameter["assessment_id"]) && is_array($this->parameter["assessment_id"])) {
					foreach ($this->parameter["assessment_id"] as $key => $value) {
						$assessment = modules::run("Assessment_module/get_assessment_by_id", array(), $value);
						if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment->tuk_id == $admin_data->tuk_id)) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
					}
				}
				break;
			default:
				$code = 400;
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => "You are not allowed to access this api",
							"errors" => array(
								"domain" => "ASSESSMENT",
								"reason" => "UpdateErrorException"
							),
						)
					)
				);
				break;
		}

		$modified_by = $this->userdata['user_id'];

		foreach ($this->parameter["assessment_id"] as $key => $assessment_id) 
		{
			$ttd_image = "ttd_".time();
			$file_ttd = getcwd()."/pic_$ttd_image".".png";

			$this->load->helper("file");

			store_file_from_base64($this->parameter["signature"], $file_ttd);

			$assessment = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id, "default_system", "optional_system");

			$gdrive_object = modules::run("Letter_module/create_request_letter", $assessment_id, $assessment->gdrive_file_id, $ttd_image);

			unlink($file_ttd);

			$this->my_parameter["request_date"] = date("Y-m-d\TH:i:sP");
			$this->my_parameter["last_activity_state"] = "TUK_SEND_REQUEST_ASSESSMENT";
			$this->my_parameter["request_letter_url"] = $gdrive_object->webViewLink;

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

			// get all fcm user admin lsp.
			$send = modules::run("Assessment_module/notify_alladmin_request_assessment", $assessment);
			$letter_parameter = array(
				"assessment_id" => $assessment_id,
				"assessment_letter_name" => "Surat Permohonan Assessment",
				"reference_id" => 0,
				"letter_type" => "SURAT_PERMOHONAN_ASSESSMENT",
				"url" => $gdrive_object->webViewLink
			);

			modules::run("Assessment_letter_module/create_assessment_letter", $letter_parameter, $this->userdata["user_id"]);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function create_assessment($created_by = 0)
	{
		$applicant_non_account = [];
		if (!empty($this->my_parameter["applicant_non_account"]) && is_array($this->my_parameter["applicant_non_account"])) {
			$applicant_non_account = $this->my_parameter["applicant_non_account"];
			unset($this->my_parameter["applicant_non_account"]);
		}

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
							"reason" => "CreateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$gdrive_assessment_dir = modules::run("Google_module/gdrive_create_folder", $this->parameter["title"]);

		$permission = array(
			'type' => 'anyone',
			'role' => 'writer',
			'allowFileDiscovery' => FALSE
		);

		modules::run("Google_module/gdrive_permission", $gdrive_assessment_dir->id, $permission);

		$gdrive_letter_dir = modules::run("Google_module/gdrive_create_folder", "SURAT ASSESSMENT", $gdrive_assessment_dir->id);

		modules::run("Assessment_module/update_assessment_by_id", $assessment_id, array("gdrive_file_id" => $gdrive_assessment_dir->id, "gdrive_letter_id" => $gdrive_letter_dir->id), $this->userdata["user_id"]);

		$data = array("data" => array("assessment_id" => $assessment_id));

		if (!empty($applicant_non_account))
		{
			for ($i=0; $i < count($applicant_non_account); $i++) 
			{
				if (empty($applicant_non_account[$i]) || !is_array($applicant_non_account[$i])) continue;
				
				$applicant_non_account[$i]["assessment_id"] = $assessment_id;
				$applicant_non_account[$i]["sub_schema_number"] = (!empty($this->my_parameter["sub_schema_number"])) ? $this->my_parameter["sub_schema_number"] : "";
				$assessment_applicant_id = modules::run("Assessment_applicant_module/create_assessment_applicant", $applicant_non_account[$i], "create_assessment_applicant_non_account", $created_by);
				
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
									"reason" => "CreateErrorException",
									"extra" => modules::run("Error_module/get_error_extra")
								),
							)
						)
					);
				}
				$parameter_applicant = array(
					"assessment_id" => $assessment_id,
					"applicant_id" => 0,
					"tuk_id" => $this->my_parameter["tuk_id"],
					"sub_schema_number" => $this->my_parameter["sub_schema_number"]
				);
		
				$parameter_applicant["assessment_applicant_id"] = $assessment_applicant_id;
		
				// create default applicant portfolio
				modules::run("Applicant_portfolio_module/create_default_applicant_portfolio", $parameter_applicant, 0, FALSE);
			}
		}

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_assessment_by_id($assessment_id)
	{
		$this->my_parameter = $this->parameter;

		$assessment = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);

		switch ($this->userdata["role_code"]) 
		{
			case 'ADT':
				$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
				if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment->tuk_id == $admin_data->tuk_id)) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
				break;
			default:
				if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
				break;
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

	public function admin_change_state($assessment_id, $state)
	{
		$state_param = array();

		switch ($state) {
			case 'ON_REVISION':
				$state_param["last_activity_description"] = "TUK harus merevisi form assessment";
				simple_curl("POST", $this->config->config["base_url_tas"].'/v1/webhooks/assessments/'.$assessment_id."/flow/0?api_key=".$this->config->config["tas_api_key"], array("status" => "direvisi"));
				break;
			case 'TUK_COMPLETE_FORM':
				$state_param["last_activity_description"] = "TUK telah menyelesaikan form request assessment";
				break;
			case 'ADMIN_CONFIRM_FORM':
				$state_param["last_activity_description"] = "Admin Mengkonfirmasi form request assessment";

				break;
			case 'PORTFOLIO_APPLICANT_COMPLETED':
				$state_param["last_activity_description"] = "Portofolio peserta lengkap";
				break;
			case 'ASSESSOR_READY':
				$state_param["last_activity_description"] = "Assessor menyetujui permintaan assessment";
				break;
			case 'ADMIN_READY':
				$state_param["last_activity_description"] = "Admin menyetujui permintaan assessment";
				break;
			case 'DATE_PLACE_FIXED':
				$state_param["last_activity_description"] = "Tanggal dan tempat assessment sudah tetap";
				break;
			case 'ALL_LETTER_COMPLETED':
				$state_param["last_activity_description"] = "Semua surat assessment lengkap";
				break;
			case 'ON_REVIEW_APPLICANT_DOCUMENT':
				$state_param["last_activity_description"] = "Sedang dalam tahap pengecekan dokumen assessment";
				modules::run("Letter_module/create_assignment_assessor", $assessment_id);
				// modules::run("Letter_module/create_assignment_admin", $assessment_id);
				break;
			case 'ON_COMPLETED_REPORT':
				$state_param["last_activity_description"] = "Sedang dalam tahap penyelesaian laporan";
			break;
			case 'REAL_ASSESSMENT':
				$state_param["last_activity_description"] = "Sedang dalam tahap Real Assessment";
				simple_curl("POST", $this->config->config["base_url_tas"].'/v1/webhooks/assessments/'.$assessment_id."/flow/4?api_key=".$this->config->config["tas_api_key"], array("status" => "real assessment"));
			break;
			case 'PLENO_DOCUMENT_COMPLETED':
				// modules::run("Letter_module/create_assignment_pleno", $assessment_id);
				modules::run("Letter_module/create_report_assessment", $assessment_id);
				$state_param["last_activity_description"] = "Semua dokument pleno siap";
				break;
			case 'PLENO_REPORT_READY':
				modules::run("Letter_module/create_baps", $assessment_id);
				$state_param["last_activity_description"] = "Berita Acara Sertifikat diterbitkan";
				break;
			case 'REQUEST_BLANKO_SENDING':
				$state_param["last_activity_description"] = "Permohonan blanko sudah dikirimkan ke BNSP";
				break;
			case 'PRINT_CERTIFICATE':
				modules::run("Assessment_certificate_module/create_all_certificate_for_assessment", $assessment_id);
				$state_param["last_activity_description"] = "Sertifikat sudah dicetak";
				simple_curl("POST", $this->config->config["base_url_tas"].'/v1/webhooks/assessments/'.$assessment_id."/flow/6?api_key=".$this->config->config["tas_api_key"], array("status" => "lengkap"));
				break;
			case 'ASSESSMENT_REJECTED':
				$state_param["last_activity_description"] = (!empty($this->parameter["last_activity_description"])) ? $this->parameter["last_activity_description"] : "Assessment ditolak";
				simple_curl("POST", $this->config->config["base_url_tas"].'/v1/webhooks/assessments/'.$assessment_id."/flow/99?api_key=".$this->config->config["tas_api_key"], array("status" => "ditolak"));
				break;
			case 'COMPLETED':
				$state_param["last_activity_description"] = "Assessment Completed";
				break;
			default:
				$code = 400;
				response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "Invalid state",
						"errors" => array(
							"domain" => "ASSESSMENT",
							"reason" => "UpdateErrorException"
						),
					)
				));
				break;
		}
		
		$state_param["last_activity_state"] = $state;

		$this->change_state_assessment_by_id($assessment_id, $state_param);
	}

	protected function change_state_assessment_by_id($assessment_id, $state)
	{
		$assessment = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);
		
		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
		
		$this->my_parameter = $state;
		
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

	public function delete_soft_assessment_by_id($assessment_id)
	{
		$assessment = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);

		switch ($this->userdata["role_code"]) 
		{
			case 'ADT':
				$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
				if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment->tuk_id == $admin_data->tuk_id)) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
				break;
			default:
				if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
				break;
		}

		// if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_DELETE");

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

	public function delete_hard_assessment_by_id($assessment_id, $confirmation)
	{
		$assessment = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);

		switch ($this->userdata["role_code"]) 
		{
			case 'ADT':
				$admin_data = modules::run("Admintuk_module/get_admintuk_by_id", array(), $this->userdata["user_id"]);
				if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment->tuk_id == $admin_data->tuk_id)) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
				break;
			default:
				if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
				break;
		}

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_assessment($assessment_id, $confirmation, $modified_by);

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

	protected function delete_hard_assessment($assessment_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Assessment_module/delete_hard_assessment_by_id", $assessment_id, $confirmation, $modified_by);
	}

	public function get_assessment_deleted_list()
	{
		$this->my_parameter = $this->parameter;
		// switch ($this->userdata["role_code"]){
			// case 'DEV':
				// $this->my_parameter["identifier"] = intval($this->userdata["user_id"]);
			// break;
			// case 'SUP':
				// $this->my_parameter["identifier"] = intval($this->userdata["user_id"]);
			// break;
			// default:
			// if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		// }

		$data = $this->assessment_deleted_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}
	
	protected function assessment_deleted_list()
	{
		switch ($this->userdata["role_code"]) {
			case 'DEV':
				return modules::run("Assessment_module/get_assessment_deleted_list", $this->my_parameter);
			break;
			case 'SUP':
				return modules::run("Assessment_module/get_assessment_deleted_list", $this->my_parameter);
			break;
			default:
			modules::run("Permission_module/require_permission", "ASSESSMENT_DELETED_LIST");
		break;
		
		}
	}
	
	public function update_deleted_by_id($assessment_id)
	{	
		$modified_by = $this->userdata['user_id'];
		
		$affected_rows = $this->update_deleted_list($assessment_id, $modified_by);

		if ($affected_rows === FALSE) {
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

		// $data = $this->update_deleted_list($assessment_id);
		// response(200, array_merge(array("responseStatus" => "SUCCESS", "data" => $data)));
	}

	protected function update_deleted_list($assessment_id, $modified_by)
	{
		switch ($this->userdata["role_code"]) {
			case 'DEV' :
				return modules::run("Assessment_module/update_deleted_list", $assessment_id, $modified_by);
			break;
			case 'SUP':
				return modules::run("Assessment_module/update_deleted_list", $assessment_id, $modified_by);
			break;
			default:
			modules::run("Permission_module/require_permission", "ASSESSMENT_DELETED_LIST");
		break;
		}
		

	}
}



