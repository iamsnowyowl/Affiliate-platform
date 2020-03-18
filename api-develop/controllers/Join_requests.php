<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Join_requests extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_join_request_detail($join_request_id)
	{
		$this->my_parameter = $this->parameter;

		switch ($this->userdata['user_id']) {
			case 'APL':
				$this->my_parameter["applicant_id"] = $this->userdata["user_id"]; 
				break;
			
			default:
				if (!modules::run("Permission_module/require_permission", "JOIN_REQUEST_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "JOIN_REQUEST_LIST");
				else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
			break;
		}

		$data = $this->join_request_detail($join_request_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function download_join_request($join_request_id)
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
		$user_data = modules::run("User_module/get_user_by_id", [], $this->userdata['user_id']);		

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

		$ttd_image_admin = "ttd_".time();
		$file_ttd_admin = getcwd()."/pic_$ttd_image_admin".".png";

		$this->load->helper("file");

		store_file_from_base64($user_data->signature, $file_ttd_admin);

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
		$parameter["GLOBALS"]["request_status"] = (strtoupper($join_request->request_status) == "S") ? "Sertifikasi Ulang" : "Sertifikasi Baru";
		$parameter["GLOBALS"]["tanggal_sekarang"] = date("j F Y", time());
		$parameter["GLOBALS"]["nama_asesi"] = $nama_lengkap;
		$parameter["GLOBALS"]["ttd_asesi_image"] = $ttd_image_asesi;
		// $parameter["GLOBALS"]["ttd_admin_image"] = $ttd_image_admin;

		$admin["data"] = array(
			"nik_admin_lsp" => $user_data->nik,
			"nama_admin_lsp" => strtoupper($user_data->first_name." ".$user_data->last_name),
			"ttd_admin_image" => $ttd_image_admin			
		);

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

		$this->load->helper('download');
		force_download("APL01.docx", file_get_contents($file));

	}

	public function get_join_request_list() 
	{
		$this->my_parameter = $this->parameter;
		
		switch ($this->userdata['user_id']) {
			case 'APL':
				$this->my_parameter["applicant_id"] = $this->userdata["user_id"]; 
				break;
			
			default:
				if (!modules::run("Permission_module/require_permission", "JOIN_REQUEST_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "JOIN_REQUEST_LIST");
				else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
				break;
		}

		$data = $this->join_request_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function join_request_detail($join_request_id)
	{
		$join_requests = modules::run("Join_request_module/get_join_request_by_id", $this->my_parameter, $join_request_id);

		$this->load->helper("url");

		if ($join_requests === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "JOIN_REQUEST",
							"reason" => "Join_requestNotFound"
						),
					)
				)
			);
		}

		return array("data" => $join_requests);
	}

	protected function join_request_list()
	{
		return modules::run("Join_request_module/get_join_request_list", $this->my_parameter);
	}

	public function get_join_request_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "JOIN_REQUEST_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "JOIN_REQUEST_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->join_request_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function join_request_count()
	{
		$count = modules::run("Join_request_module/get_join_request_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create join_request
	public function create_join_request_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_join_request();
	}

	public function create_join_request_session()
	{
		$this->my_parameter = $this->parameter;

		switch ($this->userdata['role_code']) {
			case 'APL':
				$this->my_parameter["applicant_id"] = $this->userdata["user_id"];
				break;
			
				default:
				modules::run("Permission_module/require_permission", "JOIN_REQUEST_CREATE");
				break;
		}
		
		$created_by = $this->userdata['user_id'];

		$this->create_join_request($created_by);
	}

	protected function create_join_request($created_by = 0)
	{
		$join_request_id = modules::run("Join_request_module/create_join_request", $this->my_parameter, $created_by);

		if ($join_request_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "JOIN_REQUEST",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$gdrive_object = modules::run("Join_request_module/get_apl01", $join_request_id);

		$parameter = array(
			'master_portfolio_id' => "b5a1d6c3-a625-46e7-9ca4-543e5a8022d6",
			'applicant_id' => (!empty($this->my_parameter["applicant_id"])) ? $this->my_parameter["applicant_id"] : $this->userdata["user_id"],
			'form_value' => $gdrive_object->webViewLink."&sub_schema_number=".$this->my_parameter["sub_schema_number"],
			'filename' => 'APL01-'.$this->my_parameter['sub_schema_number'].'.docx'
		);

		modules::run("Persyaratan_umum_module/create_persyaratan_umum", $parameter);

		$data = array("data" => array("join_request_id" => $join_request_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_join_request_by_id($join_request_id)
	{
		$join_request = modules::run("Join_request_module/get_join_request_by_id", array(), $join_request_id);
		
		if (!(modules::run("Permission_module/require_permission", "JOIN_REQUEST_CREATE_OWN", FALSE) && $join_request->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "JOIN_REQUEST_UPDATE");
		
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_join_request($join_request_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "JOIN_REQUEST",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_join_request($join_request, $modified_by)
	{
		return modules::run("Join_request_module/update_join_request_by_id", $join_request, $this->my_parameter, $modified_by);
	}

	public function delete_soft_join_request_by_id($join_request_id)
	{
		$join_request = modules::run("Join_request_module/get_join_request_by_id", array(), $join_request_id);

		if (!(modules::run("Permission_module/require_permission", "JOIN_REQUEST_CREATE_OWN", FALSE) && $join_request->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "JOIN_REQUEST_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_join_request($join_request_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "JOIN_REQUEST",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_join_request($join_request_id, $modified_by = 0)
	{
		return modules::run("Join_request_module/delete_soft_join_request_by_id", $join_request_id, $modified_by);
	}

	public function delete_hard_join_request_by_id($join_request_id, $confirmation)
	{
		$join_request = modules::run("Join_request_module/get_join_request_by_id", array(), $join_request_id);

		if (!(modules::run("Permission_module/require_permission", "JOIN_REQUEST_CREATE_OWN", FALSE) && $join_request->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "JOIN_REQUEST_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_join_request($join_request_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "JOIN_REQUEST",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_join_request($join_request_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Join_request_module/delete_hard_join_request_by_id", $join_request_id, $confirmation, $modified_by);
	}
}


