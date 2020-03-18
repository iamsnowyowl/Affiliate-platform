<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_portfolio_module extends MX_Controller {

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
		$this->load->model("Applicant_portfolio_model");

		$applicant_portfolio = $this->Applicant_portfolio_model->check($check, $graph);

		if (!isset($applicant_portfolio))
		{
			modules::run("Error_module/set_error", "Applicant_portfolio not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $applicant_portfolio;
	}

	public function get_applicant_portfolio_by_id($parameter = array(), $applicant_portfolio_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Applicant_portfolio_model");

		$applicant_portfolio = $this->Applicant_portfolio_model->get_applicant_portfolio_by_id($applicant_portfolio_id, $graph);

		if (!isset($applicant_portfolio))
		{
			modules::run("Error_module/set_error", "Applicant_portfolio not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $applicant_portfolio;
	}

	public function get_applicant_portfolio_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Applicant_portfolio_model");

		$applicant_portfolio = $this->Applicant_portfolio_model->get_applicant_portfolio_list($graph);
		
		$applicant_portfolio_count = $this->get_applicant_portfolio_count($parameter);
		$graph_pagination = $this->get_graph_pagination($applicant_portfolio_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $applicant_portfolio_count->count,
			'data' => $applicant_portfolio,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_applicant_portfolio_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Applicant_portfolio_model");

		$applicant_portfolio_count = $this->Applicant_portfolio_model->get_applicant_portfolio_count($graph);

		return $applicant_portfolio_count;
	}

	public function create_applicant_portfolio($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		$this->load->model("Applicant_portfolio_model");

		// check applicant_id. if not exist then retrieve from existing
		if (!empty($this->my_parameter["assessment_applicant_id"]) && !empty($this->my_parameter["master_portfolio_id"])) {
			
			$check_parameter = array(
				"assessment_applicant_id" => $this->my_parameter["assessment_applicant_id"],
				"master_portfolio_id" => $this->my_parameter["master_portfolio_id"]
			);

			$graph = $this->get_graph_result($check_parameter);

			$applicant_portfolio = $this->Applicant_portfolio_model->get_applicant_portfolio_list($graph);

			if (!empty($applicant_portfolio[0]->applicant_portfolio) && is_array($applicant_portfolio[0]->applicant_portfolio) && count($applicant_portfolio[0]->applicant_portfolio) > 0) 
			{
				$applicant_portfolio = $applicant_portfolio[0];

				$this->my_parameter["applicant_id"] = $applicant_portfolio->applicant_id;

				if (empty($applicant_portfolio->applicant_portfolio[0]["form_value"])) {
					// case update
					$update_parameter = array(
						"form_value" => (!empty($this->my_parameter["form_value"])) ? $this->my_parameter["form_value"] : NULL
					);

					if (!empty($this->my_parameter["filename"])) $update_parameter["filename"] = $this->my_parameter["filename"];

					if ($this->update_applicant_portfolio_by_id($applicant_portfolio->assessment_id, $this->my_parameter["assessment_applicant_id"], $applicant_portfolio->applicant_portfolio[0]["applicant_portfolio_id"], $update_parameter) === FALSE){
						return FALSE;	
					}

					return $applicant_portfolio->applicant_portfolio[0]["applicant_portfolio_id"];
				}

				$this->my_parameter["sub_schema_number"] = $applicant_portfolio->sub_schema_number;
				$this->my_parameter["type"] = $applicant_portfolio->type;
				$this->my_parameter["form_type"] = $applicant_portfolio->form_type;
				$this->my_parameter["form_name"] = $applicant_portfolio->form_name;
				$this->my_parameter["form_description"] = $applicant_portfolio->form_description;

				if (isset($this->my_parameter["form_value"]) && $applicant_portfolio->form_type == "file")
				{
					$f = finfo_open();
					if (!empty($this->my_parameter["form_value"])) $this->my_parameter["mime_type"] = finfo_buffer($f, base64_decode($this->my_parameter["form_value"]), FILEINFO_MIME_TYPE);
					finfo_close($f);

					// save file to our files dir
					$this->load->library("image_lib");
					$config = $this->configuration;
					$config["unique_path"] = $config["unique_path"]."/".$applicant_portfolio->assessment_id;
					$config['filename'] = $this->my_parameter["filename"];

					// unset filename 
					$this->my_parameter["form_value"] = $this->image_lib->store_file_from_base64($this->my_parameter["form_value"], $config);
					
					if ($this->my_parameter["form_value"] === FALSE)
					{
						modules::run("Error_module/set_error", "File parsing failure");
						modules::run("Error_module/set_error_code", 400);
						return FALSE;
					}

					$this->my_parameter["form_value"] = $this->configuration["path_destination"].$config["unique_path"]."/".$config["filename"];
					$this->my_parameter["ext"] = pathinfo($this->my_parameter["form_value"], PATHINFO_EXTENSION);
				}
				else $this->my_parameter["form_value"] = $this->my_parameter["form_value"];
			}
		}

		if (!$this->configuration["pk_use_ai"]){
			$this->my_parameter["applicant_portfolio_id"] = guidv4(random_bytes(16));
		}

		$rules = (!empty($this->my_parameter["form_type"]) && $this->my_parameter["form_type"] == "file") ? array(
			array(
				'field' => 'mime_type',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'filename',
				'rules' => 'trim|required'
			),
			array(
				'field' => 'ext',
				'rules' => 'trim|required'
			)
		) : array();

		if ($this->validate_input("create_applicant_portfolio", $rules) === FALSE) return FALSE;
		
		// check is applicant_portfolio already created or not
		if ($this->configuration["check_unique"])
		{
			$check = modules::run("Applicant_portfolio_module/check", array("applicant_id" => $this->my_parameter["applicant_id"]), $this->my_parameter['applicant_portfolio_name']);
			if (!empty($check->applicant_portfolio_id)){
				modules::run("Error_module/set_error", "Applicant_portfolio already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		$applicant_portfolio_id = $this->Applicant_portfolio_model->create_applicant_portfolio($this->my_parameter, $auto_commit);

	 	return (!$this->configuration["pk_use_ai"] && !empty($applicant_portfolio_id)) ? $this->my_parameter["applicant_portfolio_id"] : $applicant_portfolio_id; 
	}

	public function create_applicant_custom_portfolio($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (!$this->configuration["pk_use_ai"]){
			$this->my_parameter["applicant_portfolio_id"] = guidv4(random_bytes(16));
			$this->my_parameter["master_portfolio_id"] = guidv4(random_bytes(16));
		}

		if ($this->validate_input("create_custom_applicant_portfolio") === FALSE) return FALSE;
		
		// check is applicant_portfolio already created or not
		if ($this->configuration["check_unique"])
		{
			$check = modules::run("Applicant_portfolio_module/check", array("applicant_id" => $this->my_parameter["applicant_id"]), $this->my_parameter['applicant_portfolio_name']);
			if (!empty($check->applicant_portfolio_id)){
				modules::run("Error_module/set_error", "Applicant_portfolio already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		$this->load->model("Applicant_portfolio_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$applicant_portfolio_id = $this->Applicant_portfolio_model->create_applicant_portfolio($this->my_parameter, $auto_commit);

	 	return (!$this->configuration["pk_use_ai"] && !empty($applicant_portfolio_id)) ? $this->my_parameter["applicant_portfolio_id"] : $applicant_portfolio_id; 
	}

	public function create_default_applicant_portfolio($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		// get list requirement
		$this->my_parameter = $parameter;

		if (empty($parameter["applicant_id"])) {
			$requirement_umum = modules::run("Master_portfolio_module/get_master_portfolio_list", array("limit" => 100, "type" => "UMUM"));
			$requirement_dasar = modules::run("Master_portfolio_module/get_master_portfolio_list", array("limit" => 100, "sub_schema_number" => $parameter["sub_schema_number"]));
			$requirement = array_merge($requirement_umum["data"], $requirement_dasar["data"]);
		}
		else {
			$requirement_dasar = modules::run("Master_portfolio_module/get_master_portfolio_list", array("limit" => 100, "sub_schema_number" => $parameter["sub_schema_number"]));
			$requirement = $requirement_dasar["data"];
			
			$persyaratan_umum_id = modules::run("Persyaratan_umum_module/create_persyaratan_umum", [
				'master_portfolio_id' => 'b5a1d6c3-a625-46e7-9ca4-543e5a8022d6',
				'filename' => 'APL01.docx',
				'applicant_id' => $parameter['applicant_id'],
				'form_value' => "/persyaratan_umums/".$parameter["applicant_id"]."/apl01/".$parameter["sub_schema_number"]."?admin_id=".$this->userdata["user_id"]
			], $created_by);

			if (empty($persyaratan_umum_id))
			{
				$code = 400;
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "ASSESSMENT_APPLICANT",
								"reason" => "CreateErrorExceptionOnCreateCustomPortofolio",
								"extra" => modules::run("Error_module/get_error_extra")
							),
						)
					)
				);
			}
		}

		if (empty($requirement))
		{
			return FALSE;
		}

		$this->load->model("Applicant_portfolio_model");

		for ($i=0; $i < count($requirement); $i++) 
		{
			$this->my_parameter = array(
				"assessment_id" => $parameter["assessment_id"],
				"applicant_id" => $parameter["applicant_id"],
				"assessment_applicant_id" => $parameter["assessment_applicant_id"],
				"sub_schema_number" => $parameter["sub_schema_number"],
				"master_portfolio_id" => $requirement[$i]->master_portfolio_id,
				"is_multiple" => $requirement[$i]->is_multiple,
				"type" => $requirement[$i]->type,
				"document_state" => (!empty($requirement[$i]->document_state)) ? json_encode($requirement[$i]->document_state) : "[ALL]",
				"form_type" => $requirement[$i]->form_type,
				"form_name" => $requirement[$i]->form_name,
				"form_value" => (!empty($requirement[$i]->form_value)) ? $requirement[$i]->form_value : "",
				"form_description" => $requirement[$i]->form_description
			);

			if (!empty($requirement[$i]->acs_document_state)){
				$this->my_parameter["acs_document_state"] = json_encode($requirement[$i]->acs_document_state);
			}

			if (!empty($requirement[$i]->apl_document_state)){
				$this->my_parameter["apl_document_state"] = json_encode($requirement[$i]->apl_document_state);
			}

			if (!$this->configuration["pk_use_ai"]){
				$this->my_parameter["applicant_portfolio_id"] = guidv4(random_bytes(16));
			}

			// if ($this->validate_input("create_applicant_portfolio") === FALSE) {
			// 	return FALSE;
			// }
			// check is applicant_portfolio already created or not
			if ($this->configuration["check_unique"])
			{
				$check = modules::run("Applicant_portfolio_module/check", array("applicant_id" => $this->my_parameter["applicant_id"]), $parameter['applicant_portfolio_name']);
				if (!empty($check->applicant_portfolio_id)){
					modules::run("Error_module/set_error", "Applicant_portfolio already exist");
					modules::run("Error_module/set_error_code", 409);
					return FALSE;
				}
			}

			// add parameter created_by
			$this->my_parameter["created_by"] = 0;
			$applicant_portfolio_id = $this->Applicant_portfolio_model->create_applicant_portfolio($this->my_parameter, $auto_commit);
		}

	 	return TRUE;
	}

	public function create_applicant_portfolio_system($parameter = array(), $created_by = 0, $auto_commit = TRUE)
	{
		// get list requirement
		$this->my_parameter = $parameter;

		if (!$this->configuration["pk_use_ai"]){
			$this->my_parameter["applicant_portfolio_id"] = guidv4(random_bytes(16));
		}

		// if ($this->validate_input("create_applicant_portfolio") === FALSE) {
		// 	return FALSE;
		// }
		// check is applicant_portfolio already created or not
		if ($this->configuration["check_unique"])
		{
			$check = modules::run("Applicant_portfolio_module/check", array("applicant_id" => $this->my_parameter["applicant_id"]), $parameter['applicant_portfolio_name']);
			if (!empty($check->applicant_portfolio_id)){
				modules::run("Error_module/set_error", "Applicant_portfolio already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		$this->load->model("Applicant_portfolio_model");

		$this->my_parameter["created_by"] = 0;
		$applicant_portfolio_id = $this->Applicant_portfolio_model->create_applicant_portfolio($this->my_parameter, $auto_commit);
	}

	public function update_applicant_portfolio_by_id($assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $parameter, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;
		if (empty($this->my_parameter) || !isset($this->my_parameter["form_value"]))
		{
			return TRUE;
		}

		$this->load->model("Applicant_portfolio_model");

		$detail_data = $this->Applicant_portfolio_model->get_ungroup_applicant_portfolio_by_id($applicant_portfolio_id);

		if (empty($detail_data->applicant_portfolio_id)){
			modules::run("Error_module/set_error", "Applicant portfolio not found");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		if (isset($this->my_parameter["form_value"]) && $detail_data->form_type == "file")
		{
			$f = finfo_open();
			if (!empty($this->my_parameter["form_value"])) $this->my_parameter["mime_type"] = finfo_buffer($f, base64_decode($this->my_parameter["form_value"]), FILEINFO_MIME_TYPE);
			finfo_close($f);

			$rules = array(
				array(
					'field' => 'mime_type',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'filename',
					'rules' => 'trim|required'
				)
			);

			if ($this->validate_input("update_applicant_portfolio", $rules) === FALSE) return FALSE;

			// save file to our files dir
			$this->load->library("image_lib");
			$config = $this->configuration;
			$config["unique_path"] = $config["unique_path"]."/".$assessment_id;
			$config['filename'] = $this->my_parameter["filename"];

			// unset filename 
			$this->my_parameter["form_value"] = $this->image_lib->store_file_from_base64($this->my_parameter["form_value"], $config);
			
			if ($this->my_parameter["form_value"] === FALSE)
			{
				modules::run("Error_module/set_error", "File parsing failure");
				modules::run("Error_module/set_error_code", 400);
				return FALSE;
			}

			$this->my_parameter["ext"] = pathinfo($this->my_parameter["form_value"], PATHINFO_EXTENSION);
			$this->my_parameter["form_value"] = $this->configuration["path_destination"].$config["unique_path"]."/".$config["filename"];
		}
		else $this->my_parameter["form_value"] = $this->my_parameter["form_value"];

		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);

		$affected_row = $this->Applicant_portfolio_model->update_applicant_portfolio_by_id($this->my_parameter, $assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $auto_commit);

		return $affected_row;
	}

	public function update_applicant_portfolio($parameter, $condition, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}
		
		if ($this->validate_input("update_applicant_portfolio") === FALSE) return FALSE;
		
		$this->load->model("Applicant_portfolio_model");

		$affected_row = $this->Applicant_portfolio_model->update_applicant_portfolio($this->my_parameter, $condition, $auto_commit);

		return $affected_row;
	}

	public function delete_last_portfolio_by_id($assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $auto_commit = TRUE)
	{
		$this->load->model("Applicant_portfolio_model");

		$parameter = array(
			"form_value" => "",
			"mime_type" => "",
			"filename" => "",
			"ext" => ""
		);

		$affected_row = $this->Applicant_portfolio_model->update_applicant_portfolio_by_id($parameter, $assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $auto_commit);

		return $affected_row;
	}

	public function delete_hard_applicant_portfolio_by_id($assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $confirmation, $auto_commit = TRUE)
	{
		$this->load->model("Applicant_portfolio_model");

		$affected_row = $this->Applicant_portfolio_model->delete_hard_applicant_portfolio_by_id($assessment_id, $assessment_applicant_id, $applicant_portfolio_id, $auto_commit);

		return $affected_row;
	}

	public function download_apl01($applicant_id, $sub_schema_number, $request_status = "S")
	{
		$applicant_data = modules::run("Applicant_module/get_applicant_by_id", [], $applicant_id);
		
		if (empty($applicant_data->user_id)) {
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "user data not found",
						"errors" => array(
							"domain" => "JOIN_REQUEST",
							"reason" => "JoinRequesApplicanttNotFound"
						)
					)
				)
			);
		}

		
		if (!empty($this->parameter["admin_id"])) {
			$user_data = modules::run("User_module/get_user_by_id", [], $this->parameter["admin_id"]);
		}

		if (empty($user_data->user_id)){
			$user_data = new stdClass();
			$user_data->signature = "";
			$user_data->nik = "";
			$user_data->first_name = "";
			$user_data->last_name = "";
		}
		
		$nama_lengkap_asesi = strtoupper($applicant_data->first_name." ".$applicant_data->last_name);
		// $nama_lengkap_admin = strtoupper($user_data->first_name." ".$user_data->last_name);
		$gender = ($applicant_data->gender_code == "M") ? "Pria" : (($applicant_data->gender_code == "F") ? "Wanita" :"-");

		$schema = modules::run("Sub_schema_module/get_full_schema_list", ["sub_schema_number" => $sub_schema_number, "limit" => 1]);
		$sub_schema_name = "";
		if (!empty($schema["data"][0]->sub_schema_name)){
			$sub_schema_name = $schema["data"][0]->sub_schema_name;
		}

		$ttd_image_admin = "ttd_".time();
		$file_ttd_admin = getcwd()."/picadmin_$ttd_image_admin".".png";

		$this->load->helper("file");
		$signature_user = (isset($user_data->signature))? $user_data->signature : "";
		store_file_from_base64($signature_user, $file_ttd_admin);

		$ttd_image_asesi = "ttd_".time();
		$file_ttd_asesi = getcwd()."/picasesi_$ttd_image_asesi".".png";

		$applicant_signature = (isset($applicant_data->signature)) ? $applicant_data->signature : "";
		store_file_from_base64($applicant_signature, $file_ttd_asesi);

		$parameter["GLOBALS"]["nama_lengkap"] = $nama_lengkap_asesi;
		$parameter["GLOBALS"]["tempat_lahir"] = (!empty($applicant_data->place_of_birth)) ? $applicant_data->place_of_birth : "";
		$parameter["GLOBALS"]["tanggal_lahir"] = (!empty($applicant_data->date_of_birth)) ? $applicant_data->date_of_birth : "";
		$parameter["GLOBALS"]["jenis_kelamin"] = $gender;
		$parameter["GLOBALS"]["kebangsaan"] = (!empty($applicant_data->kebangsaan)) ? $applicant_data->kebangsaan : "";
		$parameter["GLOBALS"]["alamat_rumah"] = (!empty($applicant_data->address)) ? $applicant_data->address : "";
		$parameter["GLOBALS"]["nomor_telepon"] = (!empty($applicant_data->contact)) ? $applicant_data->contact : "";
		$parameter["GLOBALS"]["email"] = (!empty($applicant_data->email)) ? $applicant_data->email : "";
		$parameter["GLOBALS"]["pendidikan_terakhir"] = (!empty($applicant_data->pendidikan_terakhir)) ? $applicant_data->pendidikan_terakhir : "";
		$parameter["GLOBALS"]["nama_instansi"] = (!empty($applicant_data->institution)) ? $applicant_data->institution : "";
		$parameter["GLOBALS"]["jabatan_asesi"] = (!empty($applicant_data->jabatan)) ? $applicant_data->jabatan : "";
		$parameter["GLOBALS"]["alamat_kantor"] = (!empty($applicant_data->alamat_pekerjaan)) ? $applicant_data->alamat_pekerjaan : "";
		$parameter["GLOBALS"]["kode_pos_pekerjaan"] = (!empty($applicant_data->kode_pos_pekerjaan)) ? $applicant_data->kode_pos_pekerjaan : "";
		$parameter["GLOBALS"]["nama_asesi"] = $nama_lengkap_asesi;
		$parameter["GLOBALS"]["nama_skema"] = $sub_schema_name;
		$parameter["GLOBALS"]["nomor_sub_skema"] = $sub_schema_number;
		$parameter["GLOBALS"]["request_status"] = (strtoupper($request_status) == "S") ? "Sertifikasi Ulang" : "Sertifikasi Baru";
		$parameter["GLOBALS"]["tanggal_sekarang"] = date("j F Y", time());
		$parameter["GLOBALS"]["ttd_asesi_image"] = $ttd_image_asesi;
		// $parameter["GLOBALS"]["ttd_admin_image"] = $ttd_image_admin;

		$admin["data"] = array(
			"nik_admin_lsp" => $user_data->nik,
			"nama_admin_lsp" => strtoupper($user_data->first_name." ".$user_data->last_name),
			"ttd_admin_image" => $ttd_image_admin			
		);

		$unit_competence = modules::run("Unit_competence_module/get_unit_competence_list", ["sub_schema_number" => $sub_schema_number, "limit" => 100]);
		
		for ($i=0; $i < count($unit_competence["data"]); $i++) { 
			$unit_competence["data"][$i] = [
				"kode_unit" => $unit_competence["data"][$i]->unit_code,
				"judul_unit" => $unit_competence["data"][$i]->title,
				"skkni" => $unit_competence["data"][$i]->skkni
			];				
		}

		$unit_competence = $unit_competence["data"];

		$parameter["merge_block"][] = array(
			"name" => "unit_kompetensi",
			"data" => $unit_competence
		);

		$parameter["merge_block"][] = array(
			"name" => "admin",
			"data" => $admin
		);

		$file = getenv("TMP_PATH")."/".time().".docx";

		$configuration = modules::run("Letter_module/get_configuration");

		if (!modules::run("Common_module/tbs_merge", $parameter, $configuration["apl01"]["template"], $file))
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "LETTER",
							"reason" => "GenerateErrorException"
						),
					)
				)
			);
		}

		unlink($file_ttd_admin);
		unlink($file_ttd_asesi);

		$this->load->helper('download');
		force_download("APL01.docx", file_get_contents($file));
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