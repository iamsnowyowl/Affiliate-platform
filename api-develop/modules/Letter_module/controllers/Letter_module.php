<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Letter_module extends MX_Controller {

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
		$this->load->model("Letter_model");

		$letter = $this->Letter_model->check($check, $graph);

		if (!isset($letter))
		{
			modules::run("Error_module/set_error", "Letter not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}

		return $letter;
	}

	public function get_letter_by_id($parameter = array(), $letter_id, $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Letter_model");

		$letter = $this->Letter_model->get_letter_by_id($letter_id, $graph);

		if (!isset($letter))
		{
			modules::run("Error_module/set_error", "Letter not found on database");
			modules::run("Error_module/set_error_code", 404);
			return FALSE;
		}
		return $letter;
	}

	public function get_letter_list($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Letter_model");

		$letter = $this->Letter_model->get_letter_list($graph);
		$letter_count = $this->get_letter_count($parameter);
		$graph_pagination = $this->get_graph_pagination($letter_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_query' => $query_url,
			'count' => $letter_count->count,
			'data' => $letter,
			'pagination' => $graph_pagination
		);
		return $data;
	}

	public function get_letter_count($parameter = array(), $default = "default", $optional = "optional")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Letter_model");

		$letter_count = $this->Letter_model->get_letter_count($graph);

		return $letter_count;
	}

	public function update_letter_by_id($letter_id, $parameter, $modified_by = 0, $validation_name = "update_letter", $auto_commit = TRUE)
	{
		$this->my_parameter = $parameter;

		if (empty($this->my_parameter))
		{
			return TRUE;
		}

		if ($this->validate_input($validation_name) === FALSE) return FALSE;
		
		$this->load->model("Letter_model");

		// add extra parameter
		$this->my_parameter['modified_by'] = intval($modified_by);
		
		$affected_row = $this->Letter_model->update_letter_by_id($this->my_parameter, $letter_id, $auto_commit);

		return $affected_row;
	}

	public function create_request_letter($assessment_id, $gdrive_file_id, $ttd_image) 
	{
		// create letter from template
		$assessment = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);
		$tuk = modules::run("Tuk_module/get_tuk_by_id", array(), $assessment->tuk_id);

		$parameter["GLOBALS"]["tanggal_hari_ini"] = date("j F Y", time());
		$parameter["GLOBALS"]["nomor_surat_permohonan"] = "X/LSP/199";
		$parameter["GLOBALS"]["skema_text"] = $assessment->schema_text;
		$parameter["GLOBALS"]["nama_tuk"] = ucfirst($tuk->tuk_name);
		$parameter["GLOBALS"]["tanggal_assessment"] = date("j F Y", strtotime($assessment->start_date));
		$parameter["GLOBALS"]["alamat"] = $assessment->address;
		$parameter["GLOBALS"]["total_peserta"] = $assessment->notes;
		$parameter["GLOBALS"]["alamat_tuk"] = $tuk->address;
		$parameter["GLOBALS"]["nomor_telpon_fax"] = $tuk->contact;
		$parameter["GLOBALS"]["email_tuk"] = $tuk->email;
		$parameter["GLOBALS"]["ttd_name"] = ucwords($this->userdata["first_name"]." ".$this->userdata["last_name"]);
		$parameter["GLOBALS"]["ttd_role"] = $this->userdata["role_name"];
		$parameter["GLOBALS"]["ttd_image"] = $ttd_image;
		$parameter["GLOBALS"]["email_tuk"] = $tuk->email;

		$file = getenv("TMP_PATH")."/".time().".docx";

		if (!modules::run("Common_module/tbs_merge", $parameter, $this->configuration["request_letter"]["template"], $file))
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

		$metadata = array(
			'name' => $this->configuration["request_letter"]["filename"],
			'mimeType' => 'application/vnd.google-apps.document',
			'parents' => array($gdrive_file_id)
		);

		$fileobject = array(
			'data' => file_get_contents($file),
			'mimeType' => 'application/vnd.openxmlformats-officedocument',
			'uploadType' => 'multipart',
			'fields' => '*'
		);

		$gdrive_object = modules::run("Google_module/gdrive_create_file", $metadata, $fileobject);
		unlink($file);
		return $gdrive_object;
	}

	public function create_assignment_assessor($assessment_id) 
	{
		// create letter from template
		$assessor = modules::run("Assessment_assessor_module/get_assessment_assessor_list", array("assessment_id" => $assessment_id, "sort" => "created_date"));
		$letter = modules::run("Assessment_letter_module/get_assessment_letter_list", array("assessment_id" => $assessment_id, "letter_type" => "SURAT_TUGAS_ASSESSOR"));
		
		$ketua_lsp = modules::run("Management_module/get_management_list", array("role_code" => "MAG", "level" => "1"));

		if (empty($ketua_lsp["data"][0])) {
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "Ketua LSP belum ditambahkan kedalam User Management.",
						"errors" => array(
							"domain" => "LETTER_MODULE",
							"reason" => "GenerateLetterErrorException"
						),
					)
				)
			);
		}

		if (empty($assessor["data"][0])) {
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "Assessor belum ditambahkan kedalam assessment.",
						"errors" => array(
							"domain" => "LETTER_MODULE",
							"reason" => "GenerateLetterErrorException"
						),
					)
				)
			);
		}

		$ttd_image = "ttd_".time();
		$file_ttd = getcwd()."/pic_$ttd_image".".png";

		$this->load->helper("file");

		store_file_from_base64($ketua_lsp["data"][0]->signature, $file_ttd);

		$assessment_system = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id, "default_system", "optional_system");
		$gdrive_letter_id = $assessment_system->gdrive_letter_id;

		$ketua_lsp = modules::run("Management_module/get_management_list", array("role_code" => "MAG", "level" => "1"));

		$assessment = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);
		$tuk = modules::run("Tuk_module/get_tuk_by_id", array(), $assessment->tuk_id);

		$parameter["GLOBALS"]["tanggal"] = date("j F Y", time());
		$parameter["GLOBALS"]["letters_number"] = (!empty($letter["data"][0]->letter_number)) ? $letter["data"][0]->letter_number : "";
		$parameter["GLOBALS"]["tempat"] = "Bandung";
		$parameter["GLOBALS"]["skema_text"] = $assessment->schema_text;
		$parameter["GLOBALS"]["alamat_tuk"] = ucfirst($tuk->address);
		$parameter["GLOBALS"]["tanggal_asesmen"] = date("j F Y", strtotime($assessment->start_date));
		$parameter["GLOBALS"]["alamat"] = $assessment->address;
		$parameter["GLOBALS"]["total_peserta"] = $assessment->notes;
		$parameter["GLOBALS"]["alamat_tuk"] = $tuk->address;
		$parameter["GLOBALS"]["nomor_telpon_fax"] = $tuk->contact;
		$parameter["GLOBALS"]["email_tuk"] = $tuk->email;
		$parameter["GLOBALS"]["nama_direktur"] = ucwords($ketua_lsp["data"][0]->first_name." ".$ketua_lsp["data"][0]->last_name);
		$parameter["GLOBALS"]["ttd_image"] = $ttd_image;
		$parameter["GLOBALS"]["email_tuk"] = $tuk->email;
		$parameter["GLOBALS"]["email_tuk"] = $tuk->email;

		foreach ($assessor["data"] as $key => $value) {
			$data[$key] = array(
				"nama" => ucwords($value->first_name." ".$value->last_name),
				"nomor" => $value->registration_number,
				"masa_berlaku" => "Desember 2022",
			);
		}

		$parameter["merge_block"][] = array(
			"name" => "asesor",
			"data" => $data
		);

		$file = getenv("TMP_PATH")."/surat_tugas_asesor_".time().".docx";

		if (!modules::run("Common_module/tbs_merge", $parameter, $this->configuration["surat_tugas_assessor"]["template"], $file))
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
		// can unlink ttd
		unlink($file_ttd);

		$metadata = array(
			'name' => $this->configuration["surat_tugas_assessor"]["filename"],
			'mimeType' => 'application/vnd.google-apps.document'
		);

		if (empty($letter["data"][0])) {
			$metadata["parents"] = array($gdrive_letter_id);
		}

		$fileobject = array(
			'data' => file_get_contents($file),
			'mimeType' => 'application/vnd.openxmlformats-officedocument',
			'uploadType' => 'multipart',
			'fields' => '*'
		);

		unlink($file);
		
		if (!empty($letter["data"][0]->file_id)) 
		{
			// already create then update file directly
			$gdrive_object = modules::run("Google_module/gdrive_update_file", $letter["data"][0]->file_id, $metadata, $fileobject);
		}
		else $gdrive_object = modules::run("Google_module/gdrive_create_file", $metadata, $fileobject);

		if (empty($gdrive_object)) return FALSE;

		if (empty($letter["data"][0])) {
			$letter_parameter = array(
				"assessment_id" => $assessment_id,
				"assessment_letter_name" => "Surat Tugas Asesor",
				"reference_id" => 0,
				"letter_type" => "SURAT_TUGAS_ASSESSOR",
				"file_id" => $gdrive_object->id,
				"url" => $gdrive_object->webViewLink
			);
			modules::run("Assessment_letter_module/create_assessment_letter", $letter_parameter, $this->userdata["user_id"]);
		}

		return $gdrive_object;
	}

	public function create_assignment_admin($letter_parameter) 
	{
		// create letter from template
		$admin = modules::run("Assessment_admin_module/get_assessment_admin_list", array("assessment_id" => $letter_parameter["assessment_id"], "sort" => "created_date"));
		$letter = modules::run("Assessment_letter_module/get_assessment_letter_list", array("assessment_id" => $letter_parameter["assessment_id"], "letter_type" => "SURAT_TUGAS_ADMIN"));
		$ketua_lsp = modules::run("Management_module/get_management_list", array("role_code" => "MAG", "level" => "1"));

		if (empty($ketua_lsp["data"][0])) {
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "Ketua LSP belum ditambahkan kedalam User Management.",
						"errors" => array(
							"domain" => "LETTER_MODULE",
							"reason" => "GenerateLetterErrorException"
						),
					)
				)
			);
		}

		if ($ketua_lsp["data"][0]->signature == "") {
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "Ketua LSP belum menambahkan tanda tangan.",
						"errors" => array(
							"domain" => "LETTER_MODULE",
							"reason" => "GenerateLetterErrorException"
						),
					)
				)
			);
		}

		if (empty($admin["data"][0])) {
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "Admin belum ditambahkan kedalam assessment.",
						"errors" => array(
							"domain" => "LETTER_MODULE",
							"reason" => "GenerateLetterErrorException"
						),
					)
				)
			);
		}

		$ttd_image = "ttd_".time();
		$file_ttd = getcwd()."/pic_$ttd_image".".png";

		$this->load->helper("file");

		store_file_from_base64($ketua_lsp["data"][0]->signature, $file_ttd);

		$assessment_system = modules::run("Assessment_module/get_assessment_by_id", array(), $letter_parameter["assessment_id"], "default_system", "optional_system");
		$gdrive_letter_id = $assessment_system->gdrive_letter_id;
		$ketua_lsp = modules::run("Management_module/get_management_list", array("role_code" => "MAG", "level" => "1"));

		$assessment = modules::run("Assessment_module/get_assessment_by_id", array(), $letter_parameter["assessment_id"]);
		$tuk = modules::run("Tuk_module/get_tuk_by_id", array(), $assessment->tuk_id);
		
		$parameter["GLOBALS"]["tanggal"] = date("j F Y", time());
		$parameter["GLOBALS"]["nomor_surat"] = (!empty($letter["data"][0]->letter_number)) ? $letter["data"][0]->letter_number : "";
		$parameter["GLOBALS"]["tempat"] = "Bandung";
		$parameter["GLOBALS"]["skema_text"] = $assessment->schema_text;
		$parameter["GLOBALS"]["alamat_tuk"] = ucfirst($tuk->address);
		$parameter["GLOBALS"]["tanggal_asesmen"] = date("j F Y", strtotime($assessment->start_date));
		$parameter["GLOBALS"]["alamat"] = $assessment->address;
		$parameter["GLOBALS"]["total_peserta"] = $assessment->notes;
		$parameter["GLOBALS"]["alamat_tuk"] = $tuk->address;
		$parameter["GLOBALS"]["nomor_telpon_fax"] = $tuk->contact;
		$parameter["GLOBALS"]["email_tuk"] = $tuk->email;
		$parameter["GLOBALS"]["nama_direktur"] = ucwords($ketua_lsp["data"][0]->first_name." ".$ketua_lsp["data"][0]->last_name);
		$parameter["GLOBALS"]["ttd_image"] = $ttd_image;
		$parameter["GLOBALS"]["email_tuk"] = $tuk->email;
		$parameter["GLOBALS"]["email_tuk"] = $tuk->email;

		foreach ($admin["data"] as $key => $value) {
			$data[$key] = array(
				"nama" => ucwords($value->first_name." ".$value->last_name),
				"telp" => $value->contact
			);
		}

		$parameter["merge_block"][] = array(
			"name" => "admin",
			"data" => $data
		);

		$file = getenv("TMP_PATH")."/surat_tugas_admin_".time().".docx";

		if (!modules::run("Common_module/tbs_merge", $parameter, $this->configuration["surat_tugas_admin"]["template"], $file))
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

		// can unlink ttd
		unlink($file_ttd);

		$metadata = array(
			'name' => $this->configuration["surat_tugas_admin"]["filename"],
			'mimeType' => 'application/vnd.google-apps.document'
		);

		if (empty($letter["data"][0])) {
			$metadata["parents"] = array($gdrive_letter_id);
		}

		$fileobject = array(
			'data' => file_get_contents($file),
			'mimeType' => 'application/vnd.openxmlformats-officedocument',
			'uploadType' => 'multipart',
			'fields' => '*'
		);
		unlink($file);

		if (!empty($letter["data"][0]->file_id)) 
		{
			// already create then update file directly
			$gdrive_object = modules::run("Google_module/gdrive_update_file", $letter["data"][0]->file_id, $metadata, $fileobject);
		}
		else $gdrive_object = modules::run("Google_module/gdrive_create_file", $metadata, $fileobject);

		if (empty($gdrive_object)) return FALSE;

		if (empty($letter["data"][0])) {
			$letters_parameter = array(
				"assessment_id" => $letter_parameter["assessment_id"],
				"assessment_letter_name" => $letter_parameter["assessment_letter_name"],
				"reference_id" => $letter_parameter["reference_id"],
				"letter_type" => $letter_parameter["letter_type"],
				"file_id" => $gdrive_object->id,
				"url" => $gdrive_object->webViewLink
			);
			modules::run("Assessment_letter_module/create_assessment_letter", $letters_parameter, $this->userdata["user_id"]);
		}

		return $gdrive_object;
	}

	public function create_assignment_pleno($assessment_id) 
	{
		// create letter from template
		$admin = modules::run("Assessment_pleno_module/get_assessment_pleno_list", array("assessment_id" => $assessment_id, "sort" => "first_name"));
		$letter = modules::run("Assessment_letter_module/get_assessment_letter_list", array("assessment_id" => $assessment_id, "letter_type" => "SURAT_TUGAS_PLENO"));

		$ketua_lsp = modules::run("Management_module/get_management_list", array("role_code" => "MAG", "level" => "1"));

		if (empty($ketua_lsp["data"][0])) {
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "Ketua LSP belum ditambahkan kedalam User Management.",
						"errors" => array(
							"domain" => "LETTER_MODULE",
							"reason" => "GenerateLetterErrorException"
						),
					)
				)
			);
		}

		if (empty($admin["data"][0])) {
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "Komite pleno belum ditambahkan kedalam assessment.",
						"errors" => array(
							"domain" => "LETTER_MODULE",
							"reason" => "GenerateLetterErrorException"
						),
					)
				)
			);
		}

		$ttd_image = "ttd_".time();
		$file_ttd = getcwd()."/pic_$ttd_image".".png";

		$this->load->helper("file");

		store_file_from_base64($ketua_lsp["data"][0]->signature, $file_ttd);

		$assessment_system = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id, "default_system", "optional_system");
		$gdrive_letter_id = $assessment_system->gdrive_letter_id;

		$assessment = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);
		$tuk = modules::run("Tuk_module/get_tuk_by_id", array(), $assessment->tuk_id);

		$parameter["GLOBALS"]["tahun_ini"] = date("Y", time());
		$parameter["GLOBALS"]["tanggal_sekarang"] = date("j F Y", time());
		$parameter["GLOBALS"]["nomor_surat"] = (!empty($letter["data"][0]->letter_number)) ? $letter["data"][0]->letter_number : "";
		$parameter["GLOBALS"]["tempat"] = "Bandung";
		$parameter["GLOBALS"]["skema_text"] = $assessment->schema_text;
		$parameter["GLOBALS"]["alamat_tuk"] = ucfirst($tuk->address);
		$parameter["GLOBALS"]["tanggal_asesmen"] = date("j F Y", strtotime($assessment->start_date));
		$parameter["GLOBALS"]["tahun_asesmen"] = date("Y", strtotime($assessment->start_date));
		$parameter["GLOBALS"]["total_peserta"] = $assessment->notes;
		$parameter["GLOBALS"]["tempat_asesmen"] = $assessment->address;
		$parameter["GLOBALS"]["nomor_telpon_fax"] = $tuk->contact;
		$parameter["GLOBALS"]["email_tuk"] = $tuk->email;
		$parameter["GLOBALS"]["nama_direktur"] = ucwords($ketua_lsp["data"][0]->first_name." ".$ketua_lsp["data"][0]->last_name);
		$parameter["GLOBALS"]["ttd_image"] = $ttd_image;
		$parameter["GLOBALS"]["email_tuk"] = $tuk->email;
		$parameter["GLOBALS"]["tipe_tuk"] = $tuk->tuk_type;
		$parameter["GLOBALS"]["nama_tuk"] = $tuk->tuk_name;

		foreach ($admin["data"] as $key => $value) {
			$data[$key] = array(
				"nama" => ucwords($value->first_name." ".$value->last_name),
				"jabatan_nomor_registrasi" => "A123",
				"posisi" => ucwords(strtolower(str_replace("_", " ", $value->position)))
			);
		}

		$parameter["merge_block"][] = array(
			"name" => "admin",
			"data" => $data
		);

		$file = getenv("TMP_PATH")."/surat_tugas_pleno_".time().".docx";

		if (!modules::run("Common_module/tbs_merge", $parameter, $this->configuration["surat_tugas_pleno"]["template"], $file))
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

		// can unlink ttd
		unlink($file_ttd);

		$metadata = array(
			'name' => $this->configuration["surat_tugas_pleno"]["filename"],
			'mimeType' => 'application/vnd.google-apps.document'
		);

		if (empty($letter["data"][0])) {
			$metadata["parents"] = array($gdrive_letter_id);
		}

		$fileobject = array(
			'data' => file_get_contents($file),
			'mimeType' => 'application/vnd.openxmlformats-officedocument',
			'uploadType' => 'multipart',
			'fields' => '*'
		);
		unlink($file);

		if (!empty($letter["data"][0]->file_id)) 
		{
			// already create then update file directly
			$gdrive_object = modules::run("Google_module/gdrive_update_file", $letter["data"][0]->file_id, $metadata, $fileobject);
		}
		else $gdrive_object = modules::run("Google_module/gdrive_create_file", $metadata, $fileobject);

		if (empty($gdrive_object)) return FALSE;

		if (empty($letter["data"][0])) {
			$letter_parameter = array(
				"assessment_id" => $assessment_id,
				"assessment_letter_name" => "Surat Tugas Pleno",
				"reference_id" => 0,
				"letter_type" => "SURAT_TUGAS_PLENO",
				"file_id" => $gdrive_object->id,
				"url" => $gdrive_object->webViewLink
			);
			modules::run("Assessment_letter_module/create_assessment_letter", $letter_parameter, $this->userdata["user_id"]);
		}

		return $gdrive_object;
	}

	public function create_baps($assessment_id) 
	{
		// create letter from template
		$applicant = modules::run("Assessment_applicant_module/get_assessment_applicant_list", array("assessment_id" => $assessment_id, "sort" => "first_name"));
		$letter = modules::run("Assessment_letter_module/get_assessment_letter_list", array("assessment_id" => $assessment_id, "letter_type" => "BAPS"));

		$ketua_lsp = modules::run("Management_module/get_management_list", array("role_code" => "MAG", "level" => "1"));

		if (empty($ketua_lsp["data"][0])) {
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "Ketua LSP belum ditambahkan kedalam User Management.",
						"errors" => array(
							"domain" => "LETTER_MODULE",
							"reason" => "GenerateLetterErrorException"
						),
					)
				)
			);
		}

		if (empty($applicant["data"][0])) {
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "Applicant tidak ditemukan.",
						"errors" => array(
							"domain" => "LETTER_MODULE",
							"reason" => "GenerateLetterErrorException"
						),
					)
				)
			);
		}

		$ttd_image = "ttd_".time();
		$file_ttd = getcwd()."/pic_$ttd_image".".png";

		$this->load->helper("file");

		store_file_from_base64($ketua_lsp["data"][0]->signature, $file_ttd);

		$assessment_system = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id, "default_system", "optional_system");
		$gdrive_letter_id = $assessment_system->gdrive_letter_id;

		$assessment = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);
		$tuk = modules::run("Tuk_module/get_tuk_by_id", array(), $assessment->tuk_id);
		foreach ($applicant["data"] as $key => $value) {
			$data[$key] = array(
				"nama" => ucwords($value->applicant_id == 0 ? $value->full_name : $value->first_name." ".$value->last_name),
				"nik" => $value->nik,
				"tempat_lahir" => ucwords($value->place_of_birth),
				"tanggal_lahir" => date("d/m/Y", strtotime($value->date_of_birth)),
				"jenis_kelamin" => ($value->gender_code == "M") ? "L" : (($value->gender_code == "M") ? "P" : "-"),
				"alamat" => ucwords($value->address),
				"kode_kota" => mb_substr($value->nik,0,4),
				"kode_provinsi" => mb_substr($value->nik,0,2),
				"telp" => ucwords($value->contact),
				"email" => ucwords($value->email),
				"kode_pendidikan" => $value->pendidikan_terakhir,
				"kode_pekerjaan" => $value->jobs_code,
				"kode_skema" => $value->sub_schema_number,
				"tanggal_uji" => date("j/m/Y", strtotime($assessment->start_date)),
				"kode_tuk" => $tuk->number_sk,
				"nomor_registrasi_asesor" => $value->registration_number,
				// "sumber_anggaran" => "",
				// "instansi_pemberi_anggaran" => "",
				"k_bk" => ($value->status_graduation == "L") ? "K" : (($value->status_graduation == "TL") ? "BK" : "-")
			);
		}

		$parameter["merge_block"][] = array(
			"name" => "peserta",
			"data" => $data
		);

		$file = getenv("TMP_PATH")."/baps_".time().".xslx";

		if (!modules::run("Common_module/tbs_merge", $parameter, $this->configuration["baps"]["template"], $file))
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

		// can unlink ttd
		unlink($file_ttd);

		$metadata = array(
			'name' => $this->configuration["baps"]["filename"],
			'mimeType' => 'application/vnd.google-apps.spreadsheet'
		);

		if (empty($letter["data"][0])) {
			$metadata["parents"] = array($gdrive_letter_id);
		}

		$fileobject = array(
			'data' => file_get_contents($file),
			'mimeType' => 'application/vnd.openxmlformats-officedocument',
			'uploadType' => 'multipart',
			'fields' => '*'
		);
		unlink($file);

		if (!empty($letter["data"][0]->file_id)) 
		{
			// already create then update file directly
			$gdrive_object = modules::run("Google_module/gdrive_update_file", $letter["data"][0]->file_id, $metadata, $fileobject);
		}
		else $gdrive_object = modules::run("Google_module/gdrive_create_file", $metadata, $fileobject);

		if (empty($gdrive_object)) return FALSE;

		if (empty($letter["data"][0])) {
			$letter_parameter = array(
				"assessment_id" => $assessment_id,
				"assessment_letter_name" => "Berita Acara Penerbitan Sertifikat",
				"reference_id" => 0,
				"letter_type" => "BAPS",
				"file_id" => $gdrive_object->id,
				"url" => $gdrive_object->webViewLink
			);
			modules::run("Assessment_letter_module/create_assessment_letter", $letter_parameter, $this->userdata["user_id"]);
		}

		return $gdrive_object;
	}

	public function create_report_assessment($assessment_id)
	{
		// create letter from template
		$assessment = modules::run("Assessment_module/get_assessment_by_id", [], $assessment_id);
		
		if (empty($assessment->assessment_id)){
			$code = 400;
			response($code, array(
				"responseStatus" => "ERROR",
				"error" => array(
					"code" => $code,
					"message" => "Assessment Tidak ditemukan.",
					"errors" => array(
						"domain" => "LETTER_MODULE",
						"reason" => "GenerateLetterErrorException"
						),
					)
				)
			);	
		}
		
		$assessor_id = $assessment->assessor_id;

		$admin = modules::run("Assessment_admin_module/get_assessment_admin_list", array("assessment_id" => $assessment_id, "sort" => "created_date"));

		$letter = modules::run("Assessment_letter_module/get_assessment_letter_list", array("assessment_id" => $assessment_id, "letter_type" => "FR_MAK_05"));
		
		$assessor = modules::run("Accessor_module/get_accessor_list", array("role_code" => "ACS", "user_id" => $assessor_id));

		$asesi = modules::run("Assessment_applicant_module/get_assessment_applicant_list", array("assessment_id" => $assessment_id, "sort" => "created_date"));	

		if (empty($asesi["data"][0])) {
			$code =400;
			response($code, array(
				"responseStatus" => "ERROR",
				"error" => array(
					"code" => $code,
					"message" => "Asesi belum ditambahkan.",
					"errors" => array(
						"domain" => "LETTER_MODULE",
						"reason" => "GenerateLetterErrorException"
						),
					)
				)
			);
		}
		if (empty($assessor["data"][0])) {
			$code = 400;
			response($code, array(
				"responseStatus" => "ERROR",
				"error" => array(
					"code" => $code,
					"message" => "Asesor belum ditambahkan kedalam User Accessor.",
					"errors" => array(
						"domain" => "LETTER_MODULE",
						"reason" => "GenerateLetterErrorException"
						),
					)
				)
			);
		}

		$ttd_image = "ttd_".time();
		$file_ttd = getcwd()."/pic_$ttd_image".".png";

		$this->load->helper("file");

		store_file_from_base64($assessor["data"][0]->signature, $file_ttd);

		$assessment_system = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id, "default_system", "optional_system");
		$gdrive_letter_id = $assessment_system->gdrive_letter_id;
		$parameter["GLOBALS"]["nama_asesor"] =ucwords($assessor["data"][0]->first_name." ".$assessor["data"][0]->last_name);
		$parameter["GLOBALS"]["nama_skema"] = $assessment->schema_label;
		$parameter["GLOBALS"]["nomor_sub_skema"] = $assessment->sub_schema_number;
		$parameter["GLOBALS"]["nama_tuk"] = $assessment->tuk_name;
		$parameter["GLOBALS"]["nama_asesor"] =ucwords($assessor["data"][0]->first_name." ".$assessor["data"][0]->last_name);
		$parameter["GLOBALS"]["tanggal_mulai"] = date("j F Y", strtotime($assessment->start_date));
		$parameter["GLOBALS"]["nomor_registrasi_asesor"] = $assessor["data"][0]->registration_number;
		$parameter["GLOBALS"]["tanggal_sekarang"] = date(" j F Y", time());
		$parameter["GLOBALS"]["nama_pj"] = ucwords($admin["data"][0]->first_name." ".$admin["data"][0]->last_name);
		$parameter["GLOBALS"]["jabatan_pj"] = "ADMIN LSP";
		$parameter["GLOBALS"]["ttd_asesor_image"] = $ttd_image;
		
		foreach	($asesi["data"] as $key => $value) {
			$data[$key] = array(
				"nama_asesi" => ucwords($value->full_name),
				"keterangan" => $value->description_for_recomendation,
				"k" => $value->status_recomendation == "K" ? "K" : "",
				"bk" => $value->status_recomendation == "BK" ? "BK" : ""

			);
		}
		
		$parameter["merge_block"][] = array(
			"name" => "asesi",
			"data" => $data
		);
		
		$file = getenv("TMP_PATH")."/fr_mak_05_".time().".doc";

		if	(!modules::run("Common_module/tbs_merge", $parameter, $this->configuration["fr_mak_05"]["template"], $file))
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

		// can unlink ttd
		unlink($file_ttd);

		$metadata = array(
			'name' => $this->configuration["fr_mak_05"]["filename"],
			'mimeType' => 'application/vnd.google-apps.document'
		);

		if	(empty($letter["data"][0])){
			$metadata["parents"] = array($gdrive_letter_id);
		}

		$fileobject = array(
			'data' => file_get_contents($file),
			'mimeType' => 'application/vnd.openxmlformats-officedocument',
			'uploadType' => 'multipart',
			'fields' => '*'
		);

		unlink($file);

		if(!empty($letter["data"][0]->file_id))
		{
			// already create then update file directly
			$gdrive_object = modules::run("Google_module/gdrive_update_file", $letter["data"][0]->file_id, $metadata, $fileobject);
		}
		else $gdrive_object = modules::run("Google_module/gdrive_create_file", $metadata, $fileobject);

		if	(empty($gdrive_object)) return FALSE;

		if	(empty($letter["data"][0])) {
			$letter_parameter = array(
				"assessment_id" => $assessment_id,
				"assessment_letter_name" => "FR-MAK-05",
				"reference_id" => 0,
				"letter_type" => "FR_MAK_05",
				"file_id" => $gdrive_object->id,
				"url" => $gdrive_object->webViewLink
			);
			modules::run("Assessment_letter_module/create_assessment_letter", $letter_parameter, $this->userdata["user_id"]);
		}
		return $gdrive_object;


	}

	public function get_configuration(){
		return $this->configuration;
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

	public function get_letter_deleted_list($parameter = array(), $default = "default_deleted_list" , $optional = "optional_deleted_list")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Letter_model");

		$letter = $this->Letter_model->get_letter_deleted_list($graph);
		$letter_count = $this->get_letter_deleted_count($parameter);
		$graph_pagination = $this->get_graph_pagination($letter_count->count);

		$this->load->helper('url');
		$query_url = (!empty($this->input->get(NULL, TRUE))) ? http_build_query($this->input->get(NULL, TRUE)) : "";
		$data = array(
			'current_url' => current_url(),
			'url_querry' => $query_url,
			'count' => $letter_count->count,
			'data' => $letter,
			'pagination' => $graph_pagination
		);

		return $data;
	}

	public function get_letter_deleted_count($parameter = array(), $default = "default_deleted_list", $optional = "optional_deleted_list")
	{
		$graph = $this->get_graph_result($parameter, $default, $optional);
		$this->load->model("Letter_model");

		$letter_count = $this->Letter_model->get_letter_deleted_count($graph);

		return $letter_count;
	}

	public function update_deleted_by_id($letter_id, $modified_by = 0, $auto_commit = TRUE)
	{
		$this->my_parameter = array(
			"deleted_at" => "2000-01-01 00:00:00"
		);
		$this->my_parameter['modified_by'] = intval($modified_by);

		if ($this->validate_input("update_deleted_by_id") === FALSE) return FALSE;

		$this->load->model("Letter_model");

		$affected_rows = $this->Letter_model->update_deleted_by_id($letter_id, $this->my_parameter, $auto_commit);

		return $affected_rows;
	}

}