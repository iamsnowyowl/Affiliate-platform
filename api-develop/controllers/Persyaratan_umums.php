<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persyaratan_umums extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function download_apl01($applicant_id, $sub_schema_number)
	{
		modules::run("Applicant_portfolio_module/download_apl01", $applicant_id, $sub_schema_number);
	}

	public function get_own_persyaratan_umum_list() 
	{
		$this->my_parameter = $this->parameter;
		
		// if (!modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_LIST");
		// else 
		$this->my_parameter["applicant_id"] = $this->userdata["user_id"]; 
		
		$data = $this->persyaratan_umum_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_persyaratan_umum_detail($persyaratan_umum_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->persyaratan_umum_detail($persyaratan_umum_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_persyaratan_umum_list() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_LIST");
		else $this->my_parameter["applicant_id"] = $this->userdata["user_id"]; 
		
		$data = $this->persyaratan_umum_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function persyaratan_umum_detail($persyaratan_umum_id)
	{
		$persyaratan_umums = modules::run("Persyaratan_umum_module/get_persyaratan_umum_by_id", $this->my_parameter, $persyaratan_umum_id);

		$this->load->helper("url");

		if ($persyaratan_umums === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "PERSYARATAN_UMUM",
							"reason" => "Persyaratan_umumNotFound"
						),
					)
				)
			);
		}

		return array("data" => $persyaratan_umums);
	}

	protected function persyaratan_umum_list()
	{
		if (empty($this->my_parameter["applicant_id"])) {
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "applicant_id required",
						"errors" => array(
							"domain" => "PERSYARATAN_UMUM",
							"reason" => "this request need applicant_id on url query by default"
						),
					)
				)
			);
		}

		return modules::run("Persyaratan_umum_module/get_persyaratan_umum_list", $this->my_parameter);
	}

	public function persyaratan_umum_count()
	{
		$count = modules::run("Persyaratan_umum_module/get_persyaratan_umum_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create persyaratan_umum
	public function create_persyaratan_umum_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_persyaratan_umum();
	}

	public function create_own_persyaratan_umum_session()
	{
		if (!modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];
		$this->my_parameter["applicant_id"] = $created_by;

		$this->create_persyaratan_umum($created_by);
	}

	public function create_persyaratan_umum_session()
	{
		if (!modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_persyaratan_umum($created_by);
	}

	protected function create_persyaratan_umum($created_by = 0)
	{
		$persyaratan_umum_id = modules::run("Persyaratan_umum_module/create_persyaratan_umum", $this->my_parameter, $created_by);
			
		if ($persyaratan_umum_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "PERSYARATAN_UMUM",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("persyaratan_umum_id" => $persyaratan_umum_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_persyaratan_umum_by_id($persyaratan_umum_id)
	{
		$persyaratan_umum = modules::run("Persyaratan_umum_module/get_persyaratan_umum_by_id", array(), $persyaratan_umum_id);
		
		if (!(modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_CREATE_OWN", FALSE) && $persyaratan_umum->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_UPDATE");
		
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_persyaratan_umum($persyaratan_umum_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "PERSYARATAN_UMUM",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_persyaratan_umum($persyaratan_umum, $modified_by)
	{
		return modules::run("Persyaratan_umum_module/update_persyaratan_umum_by_id", $persyaratan_umum, $this->my_parameter, $modified_by);
	}

	public function delete_soft_persyaratan_umum_by_id($persyaratan_umum_id)
	{
		$persyaratan_umum = modules::run("Persyaratan_umum_module/get_persyaratan_umum_by_id", array(), $persyaratan_umum_id);

		if (!(modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_CREATE_OWN", FALSE) && $persyaratan_umum->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_persyaratan_umum($persyaratan_umum_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "PERSYARATAN_UMUM",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_persyaratan_umum($persyaratan_umum_id, $modified_by = 0)
	{
		return modules::run("Persyaratan_umum_module/delete_hard_persyaratan_umum_by_id", $persyaratan_umum_id, "HAPUS", $modified_by);
	}

	public function delete_hard_persyaratan_umum_by_id($persyaratan_umum_id, $confirmation)
	{
		$persyaratan_umum = modules::run("Persyaratan_umum_module/get_persyaratan_umum_by_id", array(), $persyaratan_umum_id);

		if (!(modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_CREATE_OWN", FALSE) && $persyaratan_umum->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "PERSYARATAN_UMUM_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_persyaratan_umum($persyaratan_umum_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "PERSYARATAN_UMUM",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_persyaratan_umum($persyaratan_umum_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Persyaratan_umum_module/delete_hard_persyaratan_umum_by_id", $persyaratan_umum_id, $confirmation, $modified_by);
	}
}


