<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_schemas extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_sub_schema_detail($sub_schema_id)
	{
		$this->my_parameter = $this->parameter;

		$data = $this->sub_schema_detail($sub_schema_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_sub_schema_list() 
	{
		$this->my_parameter = $this->parameter;
		
		$data = $this->sub_schema_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_schema_sub_schema_detail($schema_id, $sub_schema_id)
	{
		$this->my_parameter = $this->parameter;

		$this->my_parameter["schema_id"] = $schema_id;

		$data = $this->sub_schema_detail($sub_schema_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_schema_sub_schema_list($schema_id) 
	{
		$this->my_parameter = $this->parameter;
		
		$this->my_parameter["schema_id"] = $schema_id;
		
		$data = $this->sub_schema_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function sub_schema_detail($sub_schema_id)
	{
		$sub_schemas = modules::run("Sub_schema_module/get_sub_schema_by_id", $this->my_parameter, $sub_schema_id);

		$this->load->helper("url");

		if ($sub_schemas === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SUB_SCHEMA",
							"reason" => "Sub_schemaNotFound"
						),
					)
				)
			);
		}

		return array("data" => $sub_schemas);
	}

	protected function sub_schema_list()
	{
		return modules::run("Sub_schema_module/get_sub_schema_list", $this->my_parameter);
	}

	public function get_sub_schema_count() 
	{
		$this->my_parameter = $this->parameter;

		$data = $this->sub_schema_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_schema_sub_schema_count($schema_id) 
	{
		$this->my_parameter = $this->parameter;

		$this->my_parameter["schema_id"] = $schema_id;

		$data = $this->sub_schema_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function sub_schema_count()
	{
		$count = modules::run("Sub_schema_module/get_sub_schema_count", $this->my_parameter);
		return (array) $count;
	}

	## begin full schema
	public function get_schema_full_schema_detail($sub_schema_id)
	{
		$this->my_parameter = $this->parameter;

		$data = $this->full_schema_detail($sub_schema_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_schema_full_schema_list() 
	{
		$this->my_parameter = $this->parameter;
		
		$data = $this->full_schema_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_schema_full_schema_list_tree() 
	{
		$this->my_parameter = $this->parameter;
		
		$data = modules::run("Sub_schema_module/get_full_schema_list_tree", $this->my_parameter);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function full_schema_detail($sub_schema_id)
	{
		$sub_schemas = modules::run("Sub_schema_module/get_full_schema_by_id", $this->my_parameter, $sub_schema_id);

		$this->load->helper("url");

		if ($sub_schemas === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SUB_SCHEMA",
							"reason" => "Sub_schemaNotFound"
						),
					)
				)
			);
		}

		return array("data" => $sub_schemas);
	}

	protected function full_schema_list()
	{
		return modules::run("Sub_schema_module/get_full_schema_list", $this->my_parameter);
	}

	public function get_schema_full_schema_count() 
	{
		$this->my_parameter = $this->parameter;

		$data = $this->full_schema_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function full_schema_count()
	{
		$count = modules::run("Sub_schema_module/get_full_schema_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create sub_schema
	public function create_schema_sub_schema_public($schema_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter["schema_id"] = $schema_id;
		
		$this->create_sub_schema();
	}

	public function create_schema_sub_schema_session($schema_id)
	{
		if (!modules::run("Permission_module/require_permission", "SCHEMA_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "SCHEMA_CREATE");
		
		$this->my_parameter = $this->parameter;
		$this->my_parameter["schema_id"] = $schema_id;

		$created_by = $this->userdata['user_id'];

		$this->create_sub_schema($created_by);
	}

	protected function create_sub_schema($created_by = 0)
	{
		$template = null;
		if (!empty($this->my_parameter["template"])) {
			$expl = explode(",", $this->my_parameter['template']);
			$template = base64_decode(array_pop($expl));
			unset($this->my_parameter["template"]);
		}

		$sub_schema_id = modules::run("Sub_schema_module/create_sub_schema", $this->my_parameter, $created_by);
			
		if ($sub_schema_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SUB_SCHEMA",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("sub_schema_id" => $sub_schema_id));

		if (!empty($template)) {
			$tmp_file_name = "/schema/Form_APL_01_02_".str_replace(" ", "_", $this->my_parameter["sub_schema_number"]).".xls";
			if (!file_exists(getenv("FILE_PATH")."/schema")) {
				mkdir(getenv("FILE_PATH")."/schema", 0755, true);
			}
			file_put_contents(getenv("FILE_PATH").$tmp_file_name, $template);

			$schema_id = $this->my_parameter["schema_id"];

			$this->my_parameter = array("template" => "/files".$tmp_file_name);

			$this->update_sub_schema($schema_id, $sub_schema_id, $created_by);
		}

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_schema_sub_schema_by_id($schema_id, $sub_schema_id)
	{
		$sub_schema = modules::run("Sub_schema_module/get_sub_schema_by_id", array("schema_id" => $schema_id), $sub_schema_id);
		
		if (!(modules::run("Permission_module/require_permission", "SCHEMA_CREATE_OWN", FALSE) && $sub_schema->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "SCHEMA_UPDATE");
		
		$this->my_parameter = $this->parameter;


		$modified_by = $this->userdata['user_id'];

		if (!empty($this->my_parameter["template"])) {
			$expl = explode(",", $this->my_parameter["template"]);
			$this->my_parameter["template"] = base64_decode(array_pop($expl));
			
			$tmp_file_name = "/schema/Form_APL_01_02_".str_replace(" ", "_", $sub_schema->sub_schema_number).".xls";

			if (!file_exists(getenv("FILE_PATH")."/schema")) {
				mkdir(getenv("FILE_PATH")."/schema", 0755, true);
			}
			file_put_contents(getenv("FILE_PATH").$tmp_file_name, $this->my_parameter["template"]);

			$this->my_parameter["template"] = "/files".$tmp_file_name;
		}

		$affected_row = $this->update_sub_schema($schema_id, $sub_schema_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SUB_SCHEMA",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_sub_schema($schema_id, $sub_schema_id, $modified_by)
	{
		return modules::run("Sub_schema_module/update_sub_schema_by_id", $schema_id, $sub_schema_id, $this->my_parameter, $modified_by);
	}

	public function delete_soft_schema_sub_schema_by_id($schema_id, $sub_schema_number)
	{
		$sub_schema = modules::run("Sub_schema_module/get_sub_schema_list", array("schema_id" => $schema_id, "sub_schema_number" => $sub_schema_number));
		if (empty($sub_schema["data"][0])){
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "sub schema with number not found",
						"errors" => array(
							"domain" => "SUB_SCHEMA",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		if (!(modules::run("Permission_module/require_permission", "SCHEMA_CREATE_OWN", FALSE) && $sub_schema["data"][0]->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "SCHEMA_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_sub_schema($schema_id, $sub_schema_number, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SUB_SCHEMA",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_sub_schema($schema_id, $sub_schema_number, $modified_by = 0)
	{
		return modules::run("Sub_schema_module/delete_soft_sub_schema_by_id", $schema_id, $sub_schema_number, $modified_by);
	}

	public function delete_hard_schema_sub_schema_by_id($schema_id, $sub_schema_id, $confirmation)
	{
		$sub_schema = modules::run("Sub_schema_module/get_sub_schema_by_id", array("schema_id" => $schema_id), $sub_schema_id);

		if (!(modules::run("Permission_module/require_permission", "SCHEMA_CREATE_OWN", FALSE) && $sub_schema->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "SCHEMA_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_sub_schema($schema_id, $sub_schema_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SUB_SCHEMA",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_sub_schema($schema_id, $sub_schema_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Sub_schema_module/delete_hard_sub_schema_by_id", $schema_id, $sub_schema_id, $confirmation, $modified_by);
	}
}


