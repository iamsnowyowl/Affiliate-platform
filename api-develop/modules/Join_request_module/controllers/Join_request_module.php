<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Join_request_module extends MX_Controller {

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

	public function check($parameter = array(), $applicant_id, $sub_schema_number, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Join_request_model");

		$join_request = $this->Join_request_model->check($applicant_id, $sub_schema_number, $graph);

		if (!isset($join_request))
		{
			modules::run("Error_module/set_error", "Join_request not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $join_request;
	}

	public function get_join_request_by_id($parameter = array(), $join_request_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Join_request_model");

		$join_request = $this->Join_request_model->get_join_request_by_id($join_request_id, $graph);

		if (!isset($join_request))
		{
			modules::run("Error_module/set_error", "Join_request not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $join_request;
	}

	public function get_join_request_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Join_request_model");

		$join_request = $this->Join_request_model->get_join_request_list($graph);
		$join_request_count = $this->get_join_request_count($parameter);
		$graph_pagination = $this->get_graph_pagination($join_request_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $join_request_count->count,
			'data' => $join_request,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_join_request_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Join_request_model");

		$join_request_count = $this->Join_request_model->get_join_request_count($graph);

		return $join_request_count;
	}

	public function create_join_request($parameter = array(), $created_by = 0, $validation_name = "create_join_request", $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (!$this->configuration["pk_use_ai"]){
			$this->my_parameter["join_request_id"] = guidv4(random_bytes(16));
		}

		if ($this->validate_input($validation_name) === FALSE) return FALSE;
		
		// check is join_request already created or not
		if ($this->configuration["check_unique"])
		{
			$check = modules::run("Join_request_module/check", NULL, $this->my_parameter['applicant_id'], $this->my_parameter['sub_schema_number']);
			if (!empty($check->join_request_id)){
				modules::run("Error_module/set_error", "Join_request already exist");
				modules::run("Error_module/set_error_code", 409);
				return FALSE;
			}
		}

		$this->load->model("Join_request_model");

		// add parameter created_by
		$this->my_parameter['created_by'] = intval($created_by);
		
		$join_request_id = $this->Join_request_model->create_join_request($this->my_parameter, $auto_commit);

	 	return (!$this->configuration["pk_use_ai"] && !empty($join_request_id)) ? $this->my_parameter["join_request_id"] : $join_request_id; 
	}

	public function update_join_request_by_id($join_request_id, $parameter, $modified_by = 0, $validation_name = "update_join_request", $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input($validation_name) === FALSE) return FALSE;
		
		$this->load->model("Join_request_model");

		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Join_request_model->update_join_request_by_id($this->my_parameter, $join_request_id, $auto_commit);

		return $affected_row;
	}

	public function update_join_request($parameter, $condition, $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}
		
		if ($this->validate_input("update_join_request") === FALSE) return FALSE;
		
		$this->load->model("Join_request_model");

		$affected_row = $this->Join_request_model->update_join_request($this->my_parameter, $condition, $auto_commit);

		return $affected_row;
	}

	public function get_apl01($join_request_id)
	{
		$join_request = modules::run("Join_request_module/get_join_request_by_id", [], $join_request_id);

		if (empty($join_request)){
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "join request not found",
						"errors" => array(
							"domain" => "JOIN_REQUEST",
							"reason" => "JoinRequestNotFound"
						)
					)
				)
			);
		}

		$applicant_data = modules::run("Applicant_module/get_applicant_by_id", [], $join_request->applicant_id);

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
		// $user_data = modules::run("User_module/get_user_by_id", [], $this->userdata['user_id']);		

		// $assessment_system = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id, "default_system", "optional_system");
		// $gdrive_letter_id = $assessment_system->gdrive_letter_id;
		// $parameter["GLOBALS"]["tanggal_hari_ini"] = date("j F Y", time());
		// $parameter["GLOBALS"]["nomor_surat_permohonan"] = "X/LSP/199";
		// $parameter["GLOBALS"]["skema_text"] = $assessment->schema_text;
		// $parameter["GLOBALS"]["nama_tuk"] = ucfirst($tuk->tuk_name);
		// $parameter["GLOBALS"]["tanggal_assessment"] = date("j F Y", strtotime($assessment->start_date));
		// $parameter["GLOBALS"]["alamat"] = $assessment->address;
		// $parameter["GLOBALS"]["total_peserta"] = $assessment->notes;
		// $parameter["GLOBALS"]["alamat_tuk"] = $tuk->address;
		// $parameter["GLOBALS"]["nomor_telpon_fax"] = $tuk->contact;
		// $parameter["GLOBALS"]["email_tuk"] = $tuk->email;
		// $parameter["GLOBALS"]["ttd_name"] = ucwords($this->userdata["first_name"]." ".$this->userdata["last_name"]);
		// $parameter["GLOBALS"]["ttd_role"] = $this->userdata["role_name"];
		// $parameter["GLOBALS"]["ttd_image"] = $ttd_image;
		// $parameter["GLOBALS"]["email_tuk"] = $tuk->email;
		$nama_lengkap = strtoupper($applicant_data->first_name." ".$applicant_data->last_name);
		$gender = ($applicant_data->gender_code == "M") ? "Pria" : (($applicant_data->gender_code == "F") ? "Wanita" :"-");

		// $ttd_image_admin = "ttd_".time();
		// $file_ttd_admin = getcwd()."/pic_$ttd_image_admin".".png";

		$this->load->helper("file");

		// store_file_from_base64($user_data->signature, $file_ttd_admin);

		$ttd_image_asesi = "ttd_".time();
		$file_ttd_asesi = getcwd()."/pic_$ttd_image_asesi"."png";

		store_file_from_base64($applicant_data->signature, $file_ttd_asesi);

		$parameter["GLOBALS"]["nama_lengkap"] = $nama_lengkap;
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
		$parameter["GLOBALS"]["nama_asesi"] = $nama_lengkap;
		$parameter["GLOBALS"]["nama_skema"] = $join_request->sub_schema_name;
		$parameter["GLOBALS"]["nomor_sub_skema"] = $join_request->sub_schema_number;
		$parameter["GLOBALS"]["request_status"] = (strtoupper($join_request->request_status) == "SU") ? "Sertifikasi Ulang" : "Sertifikasi";
		$parameter["GLOBALS"]["tanggal_sekarang"] = date("j F Y", time());
		$parameter["GLOBALS"]["nama_asesi"] = $nama_lengkap;
		$parameter["GLOBALS"]["ttd_asesi_image"] = $ttd_image_asesi;
		// $parameter["GLOBALS"]["ttd_admin_image"] = $ttd_image_admin;

		// $admin["data"] = array(
		// 	"nik_admin_lsp" => $user_data->nik,
		// 	"nama_admin_lsp" => strtoupper($user_data->first_name." ".$user_data->last_name),
		// 	"ttd_admin_image" => $ttd_image_admin			
		// );

		$unit_competence = modules::run("Unit_competence_module/get_unit_competence_list", ["sub_schema_number" => $join_request->sub_schema_number, "limit" => 100]);
		
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

		// $parameter["merge_block"][] = array(
		// 	"name" => "admin",
		// 	"data" => $admin
		// );
		

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

		unlink($file_ttd_asesi);
		// $this->load->helper('download');
		// force_download("APL01.docx", file_get_contents($file));
		
		$metadata = array(
			'name' => $configuration['apl01']['filename'],
			'mimeType' => 'application/vnd.google-apps.document'
		);

		$fileobject = array(
			'data' => file_get_contents($file),
			'mimeType' => 'application/vnd.openxmlformats-officedocument',
			'uploadType' => 'multipart',
			'fields' => '*'
		);

		unlink($file);

		$gdrive_object = modules::run("Google_module/gdrive_create_file", $metadata, $fileobject);

		$letter_parameter = array(
			"file_id" => $gdrive_object->id,
			"url" => $gdrive_object->webViewLink
		);
		if (empty($gdrive_object)) return FALSE;

		$permission = array(
			'type' => 'anyone',
			'role' => 'writer',
			'allowFileDiscovery' => FALSE
		);

		modules::run("Google_module/gdrive_permission", $gdrive_object->id, $permission);
		
		return $gdrive_object;
	}
	public function delete_soft_join_request_by_id($join_request_id, $auto_commit = TRUE)
	{
		// there is no soft delete implementation in this module. then just hard delete
		$this->delete_hard_join_request_by_id($join_request_id, "HAPUS");
	}

	public function delete_hard_join_request_by_id($join_request_id, $confirmation, $auto_commit = TRUE)
	{
		$this->load->model("Join_request_model");

		if ($this->configuration["hard_delete_word"] == "join_request_name")
		{
			$parameter = array("join_request_name" => $confirmation);

			$join_request = $this->get_join_request_by_id($parameter, $join_request_id);

			if (empty($join_request->join_request_id)) {
				modules::run("Error_module/set_error", "Invalid value confirmation");
				modules::run("Error_module/set_error_code", 400);
				return FALSE;
			} 
		}
		else if ($this->configuration["hard_delete_word"] != $confirmation) {
			modules::run("Error_module/set_error", "Invalid value confirmation");
			modules::run("Error_module/set_error_code", 400);
			return FALSE;
		}

		$affected_row = $this->Join_request_model->delete_hard_join_request_by_id($join_request_id, $auto_commit);

		return $affected_row;
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