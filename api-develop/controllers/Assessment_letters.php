<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Knp\Snappy\Pdf;

class Assessment_letters extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function pdf_assessment_assessment_letter_detail($assessment_id, $assessment_letter_id)
	{
		$this->load->helper('url');
		$snappy = new Pdf(APPPATH . 'vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
		header('Content-Type: application/pdf');
		die($snappy->getOutput(site_url()."public/assessments/$assessment_id/letters/$assessment_letter_id/html"));
	}

	public function pdf_assessment_assessment_request_letter($assessment_id)
	{
		$this->load->helper('url');
		$snappy = new Pdf(APPPATH . 'vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
		header('Content-Type: application/pdf');
		die($snappy->getOutput(site_url()."public/assessments/$assessment_id/letters/html"));
	}

	public function get_assessment_letter_detail($assessment_letter_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else {
			switch ($this->userdata["role_code"]) {
				case 'ACS':
					$assessment_assessors = modules::run("Assessment_assessor_module/get_assessment_assessor_list", array("assessment_id" => $assessment_id, "assessor_id" => $this->userdata["user_id"]));
					if (empty($assessment_assessors["count"])){
						$code = 404;
						response($code, array(
								"responseStatus" => "ERROR",
								"error" => array(
									"code" => $code,
									"message" => "Resource not found. maybe you are not a part of this assessment",
									"errors" => array(
										"domain" => "ASSESSMENT_APPLICANT",
										"reason" => "Assessment_applicantNotFound"
									),
								)
							)
						);
					}
					break;
				
				default:
					$this->my_parameter["created_by"] = $this->userdata["user_id"]; 
					break;
			}
		}

		$data = $this->assessment_letter_detail($assessment_letter_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_letter_list() 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else {
			switch ($this->userdata["role_code"]) {
				case 'ACS':
					$assessment_assessors = modules::run("Assessment_assessor_module/get_assessment_assessor_list", array("assessment_id" => $assessment_id, "assessor_id" => $this->userdata["user_id"]));
					if (empty($assessment_assessors["count"])){
						$code = 404;
						response($code, array(
								"responseStatus" => "ERROR",
								"error" => array(
									"code" => $code,
									"message" => "Resource not found. maybe you are not a part of this assessment",
									"errors" => array(
										"domain" => "ASSESSMENT_APPLICANT",
										"reason" => "Assessment_applicantNotFound"
									),
								)
							)
						);
					}
					break;
				default:
					$this->my_parameter["created_by"] = $this->userdata["user_id"]; 
					break;
			}
		}
		
		$data = $this->assessment_letter_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function html_assessment_assessment_letter_detail($assessment_id, $assessment_letter_id)
	{
		$this->my_parameter = $this->parameter;

		$this->my_parameter["assessment_id"] = $assessment_id;

		$data = $this->assessment_letter_detail($assessment_letter_id);
		$assessments = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);
		
		if (empty($assessments->pleno_date)) $assessments->pleno_date = date("Y-m-d H:i:s", strtotime($assessments->end_date." + 1 day"));
		
		$assessments->year = date("Y", strtotime($assessments->start_date));

		$tuk = modules::run("Tuk_module/get_tuk_by_id", array(), $assessments->tuk_id);
		$params = array("assessment_id" => $assessment_id);
		$applicants = modules::run("Assessment_applicant_module/get_assessment_applicant_list", $params);
		$letter_number = (!empty($data["data"]->letter_number)) ? $data["data"]->letter_number : ".../LSPE/ST/X/".date("Y");

		// get ketua lsp
		$management = modules::run("Management_module/get_management_list", array("level" => 1));
		
		$ketualsp = array();
		if (!empty($management["data"][0]->user_id)) {
			$ketualsp = modules::run("User_module/get_user_by_id", array(), $management["data"][0]->user_id);
		}

		// debug($ketualsp);
		if (!empty($data["data"]))
		{
			switch ($data["data"]->letter_type) 
			{
				case 'SURAT_TUGAS_ASSESSOR':
					$parameter = modules::run("Accessor_module/get_all_accessor_by_id", array(), $data["data"]->reference_id);
					$assessor = modules::run("Assessment_assessor_module/get_assessment_assessor_list", array("assessment_id" => $assessment_id, "sort" => "created_date"));
					$parameter->is_tandem = (!empty($assessor["data"][0]->assessor_id) && $assessor["data"][0]->assessor_id != $parameter->user_id) ? "tandem" : "";
					$parameter->letter_number = $letter_number;
					$parameter->assessment_data = $assessments;
					$parameter->tuk_data = $tuk;
					$parameter->applicant_data = $applicants;
					$parameter->ketualsp = $ketualsp;

					// debug($parameter);

					$this->_read_assessment_surat_tugas_assessor($parameter);
					break;
				case 'SURAT_TUGAS_PLENO':
					$parameter["assessment"] = $assessments;
					$pleno = modules::run("Assessment_pleno_module/get_assessment_pleno_list", array("assessment_id" => $assessment_id));
					$parameter["pleno"] = $pleno["data"];
					$parameter["assessment"]->letter_number = $letter_number;
					$parameter["tuk"] = $tuk;
					$parameter["applicant"] = $applicants["data"];
					$parameter["ketualsp"] = $ketualsp;
					$this->_read_assessment_surat_tugas_pleno($parameter);
					break;
				case 'SURAT_TUGAS_ADMIN':
					$parameter = modules::run("User_module/get_user_by_id", array(), $data["data"]->reference_id);
					$parameter->letter_number = $letter_number;
					$parameter->assessment_data = $assessments;
					$parameter->tuk_data = $tuk;
					$parameter->applicant_data = $applicants;
					$parameter->ketualsp = $ketualsp;

					$this->_read_assessment_surat_tugas_admin($parameter);
					break;
				case 'BERITA_ACARA_PENERBITAN_SERTIFIKAT':
					$parameter["assessment"] = $assessments;
					$pleno = modules::run("Assessment_pleno_module/get_assessment_pleno_list", array("assessment_id" => $assessment_id));
					$assessor = modules::run("Assessment_assessor_module/get_assessment_assessor_list", array("assessment_id" => $assessment_id));
					$parameter["pleno"] = $pleno["data"];
					$parameter["assessor"] = $assessor["data"];
					$parameter["assessment"]->letter_number = $letter_number;
					$parameter["tuk"] = $tuk;
					$parameter["applicant"] = $applicants["data"];
					$parameter["ketualsp"] = $ketualsp;
					// debug($parameter);

					$this->load->view("setelah_assessment/BAPS", $parameter);
					break;
				case 'BERITA_ACARA_ASSESSOR':
					$parameter["assessment"] = $assessments;
					$pleno = modules::run("Assessment_pleno_module/get_assessment_pleno_list", array("assessment_id" => $assessment_id));
					$assessor = modules::run("Assessment_assessor_module/get_assessment_assessor_list", array("assessment_id" => $assessment_id));
					$parameter["pleno"] = $pleno["data"];
					$parameter["assessor"] = $assessor["data"];
					$parameter["assessment"]->letter_number = $letter_number;
					$parameter["tuk"] = $tuk;
					$parameter["applicant"] = $applicants["data"];
					$parameter["ketualsp"] = $ketualsp;

					$this->load->view("setelah_assessment/BAA", $parameter);
					break;
				case 'SURAT_PERMOHONAN_ASSESSMENT':
					$this->html_assessment_assessment_request_letter($assessment_id);
					
					break;
			}
		}
	}

	public function html_assessment_assessment_request_letter($assessment_id)
	{
		$assessments = modules::run("Assessment_module/get_assessment_by_id", array(), $assessment_id);

		$assessments->year = date("Y", strtotime($assessments->start_date));

		$tuk = modules::run("Tuk_module/get_tuk_by_id", array(), $assessments->tuk_id);
		$params = array("assessment_id" => $assessment_id);
		$applicants = modules::run("Assessment_applicant_module/get_assessment_applicant_list", $params);
		$letter_number = (!empty($data["data"]->letter_number)) ? $data["data"]->letter_number : ".../LSPE/ST/X/".date("Y");
		$applicant_schema = array();

		if (!empty($applicants["data"])){
			$schema = array_column($applicants["data"], "schema_label");
			$applicant_schema = array_count_values($schema);
		}

		$parameter["assessment"] = $assessments;
		$parameter["assessment"]->letter_number = $letter_number;
		$parameter["tuk"] = $tuk;
		$parameter["applicant"] = $applicants;
		$parameter["applicant_schema"] = $applicant_schema;

		// get letter
		$letter = modules::run("Assessment_letter_module/get_assessment_letter_list", array("assessment_id" => $assessment_id, "letter_type" => "SURAT_PERMOHONAN_ASSESSMENT"));
		if (!empty($letter["data"][0])) {
			$signature = modules::run("Letter_signature_module/get_letter_signature_list", array("letter_id" => $letter["data"][0]->assessment_letter_id));
		}

		$parameter["signature"] = (!empty($signature["data"][0])) ? array("media" => base64_encode($signature["data"][0]->media), "mime_type" => $signature["data"][0]->mime_type) : array("media" => "", "mime_type" => "");

		$this->load->view("assessment_letters/tuk_request_assessment", $parameter);
	}

	public function get_assessment_assessment_letter_detail($assessment_id, $assessment_letter_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else {
			switch ($this->userdata["role_code"]) {
				case 'ACS':
					$assessment_assessors = modules::run("Assessment_assessor_module/get_assessment_assessor_list", array("assessment_id" => $assessment_id, "assessor_id" => $this->userdata["user_id"]));
					if (empty($assessment_assessors["count"])){
						$code = 404;
						response($code, array(
								"responseStatus" => "ERROR",
								"error" => array(
									"code" => $code,
									"message" => "Resource not found. maybe you are not a part of this assessment",
									"errors" => array(
										"domain" => "ASSESSMENT_APPLICANT",
										"reason" => "Assessment_applicantNotFound"
									),
								)
							)
						);
					}
					break;
				
				default:
					$this->my_parameter["created_by"] = $this->userdata["user_id"]; 
					break;
			}
		}

		$this->my_parameter["assessment_id"] = $assessment_id;

		$data = $this->assessment_letter_detail($assessment_letter_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_letter_list($assessment_id) 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else {
			switch ($this->userdata["role_code"]) {
				case 'ACS':
					$assessment_assessors = modules::run("Assessment_assessor_module/get_assessment_assessor_list", array("assessment_id" => $assessment_id, "assessor_id" => $this->userdata["user_id"]));
					if (empty($assessment_assessors["count"])){
						$code = 404;
						response($code, array(
								"responseStatus" => "ERROR",
								"error" => array(
									"code" => $code,
									"message" => "Resource not found. maybe you are not a part of this assessment",
									"errors" => array(
										"domain" => "ASSESSMENT_APPLICANT",
										"reason" => "Assessment_applicantNotFound"
									),
								)
							)
						);
					}
					break;
				
				default:
					$this->my_parameter["created_by"] = $this->userdata["user_id"]; 
					break;
			}
		}

		$this->my_parameter["assessment_id"] = $assessment_id;
		
		$data = $this->assessment_letter_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function assessment_letter_detail($assessment_letter_id)
	{
		$assessment_letters = modules::run("Assessment_letter_module/get_assessment_letter_by_id", $this->my_parameter, $assessment_letter_id);

		$this->load->helper("url");

		if ($assessment_letters === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_LETTER",
							"reason" => "Assessment_letterNotFound"
						),
					)
				)
			);
		}

		return array("data" => $assessment_letters);
	}

	protected function assessment_letter_list()
	{
		return modules::run("Assessment_letter_module/get_assessment_letter_list", $this->my_parameter);
	}

	public function get_assessment_letter_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->assessment_letter_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_assessment_assessment_letter_count($assessment_id) 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"];

		$this->my_parameter["assessment_id"] = $assessment_id;

		$data = $this->assessment_letter_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function assessment_letter_count()
	{
		$count = modules::run("Assessment_letter_module/get_assessment_letter_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create assessment_letter
	public function create_assessment_assessment_letter_public($assessment_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter["assessment_id"] = $assessment_id;
		
		$this->create_assessment_letter();
	}

	public function create_assessment_assessment_letter_session($assessment_id)
	{
		if (!modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE");
		
		$this->my_parameter = $this->parameter;
		$this->my_parameter["assessment_id"] = $assessment_id;

		$created_by = $this->userdata['user_id'];

		$this->create_assessment_letter($created_by);
	}

	protected function create_assessment_letter($created_by = 0)
	{
		$assessment_letter_id = modules::run("Assessment_letter_module/create_assessment_letter", $this->my_parameter, $created_by);
			
		if ($assessment_letter_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_LETTER",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("assessment_letter_id" => $assessment_letter_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_assessment_assessment_letter_by_id($assessment_id, $assessment_letter_id)
	{
		$assessment_letter = modules::run("Assessment_letter_module/get_assessment_letter_by_id", array("assessment_id" => $assessment_id), $assessment_letter_id);
		
		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $assessment_letter->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_UPDATE");
		
		$this->my_parameter = $this->parameter;

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_assessment_letter($assessment_id, $assessment_letter_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_LETTER",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		if ($assessment_letter->letter_type == "SURAT_TUGAS_ASSESSOR") modules::run("Letter_module/create_assignment_assessor", $assessment_id);
		if ($assessment_letter->letter_type == "SURAT_TUGAS_ADMIN") modules::run("Letter_module/create_assignment_admin", $assessment_id);

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function signature_assessment_assessment_letter_by_id($assessment_id, $assessment_letter_id)
	{
		if ($this->userdata["role_code"] != "MAG")
		{
			$code = 401;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "Only user with role Management allowed this method",
						"errors" => array(
							"domain" => "ASSESSMENT_LETTER",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		$this->my_parameter = $this->parameter;

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_assessment_letter($assessment_id, $assessment_letter_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_LETTER",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_assessment_letter($assessment_id, $assessment_letter_id, $modified_by)
	{
		return modules::run("Assessment_letter_module/update_assessment_letter_by_id", $assessment_id, $assessment_letter_id, $this->my_parameter, $modified_by);
	}

	public function delete_soft_assessment_assessment_letter_by_id($assessment_id, $assessment_letter_id)
	{
		$assessment_letter = modules::run("Assessment_letter_module/get_assessment_letter_by_id", array("assessment_id" => $assessment_id), $assessment_letter_id);

		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_assessment_letter($assessment_id, $assessment_letter_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_LETTER",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_assessment_letter($assessment_id, $assessment_letter_id, $modified_by = 0)
	{
		return modules::run("Assessment_letter_module/delete_soft_assessment_letter_by_id", $assessment_id, $assessment_letter_id, $modified_by);
	}

	public function delete_hard_assessment_assessment_letter_by_id($assessment_id, $assessment_letter_id, $confirmation)
	{
		$assessment_letter = modules::run("Assessment_letter_module/get_assessment_letter_by_id", array("assessment_id" => $assessment_id), $assessment_letter_id);

		if (!(modules::run("Permission_module/require_permission", "ASSESSMENT_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ASSESSMENT_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_assessment_letter($assessment_id, $assessment_letter_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ASSESSMENT_LETTER",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_assessment_letter($assessment_id, $assessment_letter_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Assessment_letter_module/delete_hard_assessment_letter_by_id", $assessment_id, $assessment_letter_id, $confirmation, $modified_by);
	}

	protected function _read_assessment_surat_tugas_assessor($parameter)
	{
		$parameter = (array) $parameter;
		$this->load->view("surat_tugas_assessor", $parameter);		
	}

	protected function _read_assessment_surat_tugas_pleno($parameter)
	{
		$parameter = (array) $parameter;
		$this->load->view("setelah_assessment/PLENO", $parameter);		
	}

	protected function _read_assessment_surat_tugas_admin($parameter)
	{
		$parameter = (array) $parameter;
		$this->load->view("surat_tugas_admin", $parameter);		
	}
}


