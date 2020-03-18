<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class Imports extends MX_Controller {
	
	protected $my_parameter;
	protected $data = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function import_data_alumni() {
		
	}

	public function import_data_user_applicant($assessment_id) 
	{
		$this->my_parameter = $this->parameter;
		$this->load->library('form_validation');

		$rules = array(
			array(
				'field' => 'file',
				'rules' => 'trim|required'
			)
		);
		
		$this->form_validation->reset_validation();
		$this->form_validation->set_data($this->my_parameter, TRUE);
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run(NULL, $this->my_parameter) == FALSE)
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "error validation on input data",
						"errors" => array(
							"domain" => "IMPORT",
							"reason" => "IMPORT_DATA_APPLICANT",
							"extra" => (!is_array($this->form_validation->error_array())) ? array('invalid_field' => $this->form_validation->error_array()) : $this->form_validation->error_array()
						),
					)
				)
			);
		}

		$expl = explode(",", $this->my_parameter["file"]);
		$this->my_parameter["file"] = base64_decode(array_pop($expl));
		$tmp_file_name = "/assessment/origin/$assessment_id/applicant_".time().".xls";
		if (!file_exists(getenv("FILE_PATH")."/assessment/origin/$assessment_id")){
			mkdir(getenv("FILE_PATH")."/assessment/origin/$assessment_id", 0755, true);
		}

		file_put_contents(getenv("FILE_PATH").$tmp_file_name, $this->my_parameter["file"]);

		$reader = ReaderFactory::create(Type::XLSX); // for XLSX
		// $reader->setShouldFormatDates(TRUE); // Formatting is enabled
		$reader->open(getenv("FILE_PATH").$tmp_file_name);
		
		$errors = array();

    	$state_portfolio = TRUE;
    	$state_bukti = 0;
    	$state_max = 80;

		foreach ($reader->getSheetIterator() as $sheet) 
		{
		    foreach ($sheet->getRowIterator() as $key => $row) {
		    	if ($key >=12 && $key <= 23) $this->extract_data("data_pribadi", $row);
		    	if ($key >=27 && $key <= 33) $this->extract_data("data_pribadi", $row);
		    	if ($key == 41) {
		    		$this->data["data_sertifikasi"]["judul"] = (isset($row[0])) ? $row[0] : "";
		    		$this->data["data_sertifikasi"]["nomor_skema"] = (isset($row[1])) ? $row[1] : "";

		    		$this->data["data_sertifikasi"]["tujuan_assessment"] = (!empty($row[2]) && strtolower($row[2]) == "x") ? "S" : ((!empty($row[5]) && strtolower($row[5]) == "x") ? "SU" : "");
		    	}

		    	if ($key >= 51 && $state_portfolio === TRUE) 
		    	{
		    		if (!empty($row[0]) && strtolower($row[0]) == "b.bukti kompetensi yang relevan :") {
		    			$state_portfolio = FALSE;
		    			continue;
		    		}

		    		$this->data["portfolio"][$row[0]] = (!empty($row[1]) && strtolower($row[1]) == "x") ? "Memenuhi Syarat" : ((!empty($row[2]) && strtolower($row[2]) == "x") ? "Tidak memenuhi Syarat" : "");
		    	}

		    	if ($state_portfolio === FALSE && strtolower($row[0]) == "rincian bukti pendidikan/pelatihan, pengalaman kerja, pengalaman hidup") $state_bukti++;
		    	if ($state_portfolio === FALSE && $state_bukti == 1 && strtolower($row[3]) == "tidak ada") $state_bukti++;

		    	if ($state_portfolio === FALSE && $state_bukti == 2)
		    	{
		    		if (!empty($row[0])) {
		    			$this->data["portfolio"][$row[0]] = (!empty($row[2]) && strtolower($row[2]) == "x") ? $row[2] : ((!empty($row[3]) && strtolower($row[3]) == "x") ? $row[3] : "");
		    		}
    				$state_bukti = 99;
		    	}
		    }
		}

		$data_pribadi = $this->data["data_pribadi"];

		$this->my_parameter = array();

		if (!empty($data_pribadi))
		{
			foreach ($data_pribadi as $key => $value) {
				$key = str_replace("(*)", "", $key);
				$this->my_parameter[$key] = $value;
			}
		}

		if (isset($this->my_parameter["tgl_lahir(mm/dd/yyyy)"]) && is_object($this->my_parameter["tgl_lahir(mm/dd/yyyy)"])) $this->my_parameter["tgl_lahir(mm/dd/yyyy)"] = $this->my_parameter["tgl_lahir(mm/dd/yyyy)"]->format('Y-m-d');
		// validate
		$this->form_validation->reset_validation();
		$this->form_validation->set_data($this->my_parameter, TRUE);
		$this->form_validation->set_rules($this->get_rules_applicant());


		if ($this->form_validation->run(NULL, $this->my_parameter) == FALSE)
		{
			$error = array_map(
				function ($el) {
					return '<font color="red" size="3">'.$el.'</font>';
				},
				(!is_array($this->form_validation->error_array())) ? array('invalid_field' => $this->form_validation->error_array()) : $this->form_validation->error_array()
			);
			$error = array_merge($this->my_parameter, $error);
			$errors[] = $error;
			// continue;
		}

		if (!empty($errors))
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "error validation on input data",
						"errors" => array(
							"domain" => "IMPORT",
							"reason" => "IMPORT_DATA_APPLICANT",
							"extra" => $errors
						),
					)
				)
			);
		}

		$this->data["data_pribadi"] = $this->transform_key_applicant($this->my_parameter);
		// continue process. transcode
		$this->data["data_pribadi"]["gender_code"] = (strtolower($this->data["data_pribadi"]["gender_code"]) == "pria") ? "M" : ((strtolower($this->data["data_pribadi"]["gender_code"]) == "wanita") ? "F" : "UNKNOWN");
		$nama_lengkap = explode(" ", $this->data["data_pribadi"]["nama_lengkap"]);
		$username = $nama_lengkap;
		$username = array_shift($username);
		$this->data["data_pribadi"]["username"] = strtolower($username)."_".time();
		$last_name = array_pop($nama_lengkap);
		$this->data["data_pribadi"]["first_name"] = (empty($nama_lengkap[0])) ? $last_name : implode(" ", $nama_lengkap);
		$this->data["data_pribadi"]["last_name"] = (empty($nama_lengkap[0])) ? "" : $last_name;
		unset($this->data["data_pribadi"]["nama_lengkap"]);
		// close reader
		$reader->close();

		// insert user to applicant
		// modules::run("Permission_module/require_permission", "APPLICANT_CREATE");
		
		$this->my_parameter = $this->parameter;
		$this->my_parameter["activated_date"] = date("Y-m-d H:i:s");
		$created_by = $this->userdata['user_id'];

		$user_id = $this->insert_data($assessment_id, "/files".$tmp_file_name, $this->data, $created_by);
		$data = array('data' => array("user_id" => $user_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function insert_data($assessment_id, $tmp_file_name, $data, $created_by)
	{
		$this->my_parameter = $data["data_pribadi"];

		$rules = modules::run("User_module/get_config", "rules");
		$rules = array_column($rules["create_user"], "field");

		// add role_code rules
		$rules[] = "role_code";

		$user_parameter = array();
		$applicant_parameter = array();

		// every user here must become applicant
		$this->my_parameter['role_code'] = "APL";

		foreach ($this->my_parameter as $key => $value) {
			if (in_array($key, $rules)) $user_parameter[$key] = $value;
			else $applicant_parameter[$key] = $value;
		}

		$this->load->model("Transaction_model");

		$this->Transaction_model->trans_start();

		$user_id = $this->check_user_is_exist($user_parameter);

		if (empty($user_id))
		{
			// user not exist on database. then create
			$user_id = modules::run("User_module/create_user", $user_parameter, 0, FALSE);

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
		}

		$applicant = modules::run("Applicant_module/get_applicant_by_id", array(), $user_id);

		if (empty($applicant->applicant_id))
		{
			$applicant_parameter["user_id"] = $user_id;

			if (!empty($applicant_parameter["sub_schema_number"])) unset($applicant_parameter["sub_schema_number"]);

			$applicant_id = modules::run("Applicant_module/create_applicant", $applicant_parameter, 0, FALSE);
			
			if ($applicant_id === FALSE)
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
		}

		if (!empty($this->my_parameter["email"])) modules::run("User_module/reset_password", array("email" => $this->my_parameter["email"]));

		// insert assessment applicant
		$parameter_applicant = array(
			"assessment_id" => $assessment_id,
			"applicant_id" => $user_id,
			"tuk_id" => 0,
			"sub_schema_number" => $data["data_sertifikasi"]["nomor_skema"]
		);

		$assessment_applicant_id = modules::run("Assessment_applicant_module/create_assessment_applicant", $parameter_applicant, $created_by);

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

		// insert portfolio
		$count = 0;
		foreach ($data["portfolio"] as $key => $value) 
		{
			$this->my_parameter = array(
				"assessment_id" => $assessment_id,
				"applicant_id" => $user_id,
				"assessment_applicant_id" => $assessment_applicant_id,
				"sub_schema_number" => $data["data_sertifikasi"]["nomor_skema"],
				"master_portfolio_id" => guidv4(random_bytes(16)),
				"is_multiple" => 0,
				"type" => "DASAR",
				"form_type" => "text",
				"form_name" => "form_".$count,
				"form_value" => $value,
				"form_description" => $key
			);

			$count++;

			modules::run("Applicant_portfolio_module/create_applicant_portfolio", $this->my_parameter, $created_by);
		}

		$expl_file_name = explode("/", $tmp_file_name);
		$file_name = array_pop($expl_file_name);

		$this->my_parameter = array(
			"assessment_id" => $assessment_id,
			"applicant_id" => $user_id,
			"assessment_applicant_id" => $assessment_applicant_id,
			"sub_schema_number" => $data["data_sertifikasi"]["nomor_skema"],
			"master_portfolio_id" => guidv4(random_bytes(16)),
			"is_multiple" => 0,
			"type" => "DASAR",
			"form_type" => "file",
			"form_name" => "form_apl0102",
			"form_value" => $tmp_file_name,
			"filename" => $file_name,
			"mime_type" => "application/vnd.ms-excel",
			"form_description" => "csv file upload"
		);

		modules::run("Applicant_portfolio_module/create_applicant_portfolio_system", $this->my_parameter, $created_by);

		$pparameter["assessment_id"] = $assessment_id;
		$pparameter["applicant_id"] = $user_id;
		$pparameter["assessment_applicant_id"] = $assessment_applicant_id; 
		$pparameter["sub_schema_number"] = $data["data_sertifikasi"]["nomor_skema"];
		
		modules::run("Applicant_portfolio_module/create_default_applicant_portfolio", $pparameter, $created_by);

		$this->Transaction_model->trans_complete();

		return $user_id;
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

	protected function transform_key_applicant($data)
	{
		$origin = array(
			"nama_lengkap" => "nama_lengkap",
			"tgl_lahir(mm/dd/yyyy)" => "date_of_birth",
			"tempat_lahir" => "place_of_birth",
			"jenis_kelamin(pria/wanita)" => "gender_code",
			"kebangsaan" => "kebangsaan",
			"alamat_rumah" => "address",
			"kode_pos" => "kode_pos",
			"email" => "email",
			"no_telepon_hp" => "contact",
			"no_telepon_rumah" => "telepon_rumah",
			"no_telepon_kantor" => "telepon_kantor",
			"pendidikan_terakhir" => "pendidikan_terakhir",
			"nama_lembaga_/_perusahaan" => "nama_lembaga",
			"jabatan" => "jabatan",
			"alamat_pekerjaan" => "alamat_pekerjaan",
			"kode_pos_pekerjaan" => "kode_pos_pekerjaan",
			"no._telepon_pekerjaan" => "telepon_pekerjaan",
			"fax_pekerjaan" => "fax_pekerjaan",
			"email_pekerjaan" => "email_pekerjaan"
		);

		$new_form = array();
		foreach ($data as $key => $value) {
			$new_form[$origin[$key]] = $value;
		}

		return $new_form;
	}

	protected function extract_data($section, $row)
	{
		if (!empty($row[0]) && isset($row[1])) {
			$row[0] = strtolower(str_replace(' ', '_', $row[0]));

			$this->data[$section][$row[0]] = $row[1];
		}
	}

	protected function get_rules_applicant()
	{
		return array(
			array(
				"field" => "nama_lengkap",
				"rules" => "trim|required"
			),
			// array(
			// 	"field" => "nomor_skema",
			// 	"rules" => "trim|required"
			// ),
			// array(
			// 	"field" => "tujuan_assessment",
			// 	"rules" => "trim|required|in_list[S,SU]"
			// ),
			array(
				"field" => "tgl_lahir(mm/dd/yyyy)",
				"rules" => "trim|required"
			),
			array(
				"field" => "tempat_lahir",
				"rules" => "trim|required"
			),
			array(
				"field" => "jenis_kelamin(pria/wanita)",
				"rules" => "trim|required|in_list[PRIA,WANITA,Pria,Wanita,pria,wanita]"
			),
			array(
				"field" => "kebangsaan",
				"rules" => "trim"
			),
			array(
				"field" => "alamat_rumah",
				"rules" => "trim|required"
			),
			array(
				"field" => "kode_pos",
				"rules" => "trim"
			),
			array(
				"field" => "email",
				"rules" => "trim|valid_email"
			),
			array(
				"field" => "no_telepon_hp",
				"rules" => "trim|required"
			),
			array(
				"field" => "no_telepon_rumah",
				"rules" => "trim"
			),
			array(
				"field" => "no_telepon_kantor",
				"rules" => "trim"
			),
			array(
				"field" => "pendidikan_terakhir",
				"rules" => "trim"
			),
			array(
				"field" => "nama_lembaga_/_perusahaan",
				"rules" => "trim"
			),
			array(
				"field" => "jabatan",
				"rules" => "trim"
			),
			array(
				"field" => "alamat_pekerjaan",
				"rules" => "trim"
			),
			array(
				"field" => "kode_pos_pekerjaan",
				"rules" => "trim"
			),
			array(
				"field" => "no._telepon_pekerjaan",
				"rules" => "trim"
			),
			array(
				"field" => "fax_pekerjaan",
				"rules" => "trim"
			),
			array(
				"field" => "email_pekerjaan",
				"rules" => "trim|valid_email"
			)
		);
	}
}


