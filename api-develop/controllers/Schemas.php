<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schemas extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_schema_detail($schema_id)
	{
		$this->my_parameter = $this->parameter;

		$data = $this->schema_detail($schema_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_schema_list() 
	{
		$this->my_parameter = $this->parameter;
		
		$data = $this->schema_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function schema_detail($schema_id)
	{
		$schemas = modules::run("Schema_module/get_schema_by_id", $this->my_parameter, $schema_id);

		$this->load->helper("url");

		if ($schemas === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SCHEMA",
							"reason" => "SchemaNotFound"
						),
					)
				)
			);
		}

		return array("data" => $schemas);
	}

	protected function schema_list()
	{
		return modules::run("Schema_module/get_schema_list", $this->my_parameter);
	}

	public function get_schema_count() 
	{
		$this->my_parameter = $this->parameter;

		$data = $this->schema_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function schema_count()
	{
		$count = modules::run("Schema_module/get_schema_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create schema
	public function create_schema_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_schema();
	}

	public function create_schema_session()
	{
		if (!modules::run("Permission_module/require_permission", "SCHEMA_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "SCHEMA_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_schema($created_by);
	}

	protected function create_schema($created_by = 0)
	{
		$schema_id = modules::run("Schema_module/create_schema", $this->my_parameter, $created_by);
			
		if ($schema_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SCHEMA",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("schema_id" => $schema_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_schema_by_id($schema_id)
	{
		$schema = modules::run("Schema_module/get_schema_by_id", array(), $schema_id);
		
		if (!(modules::run("Permission_module/require_permission", "SCHEMA_CREATE_OWN", FALSE) && $schema->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "SCHEMA_UPDATE");
		
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_schema($schema_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SCHEMA",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_schema($schema, $modified_by)
	{
		return modules::run("Schema_module/update_schema_by_id", $schema, $this->my_parameter, $modified_by);
	}

	public function delete_soft_schema_by_id($schema_id)
	{
		$schema = modules::run("Schema_module/get_schema_by_id", array(), $schema_id);

		if (!(modules::run("Permission_module/require_permission", "SCHEMA_CREATE_OWN", FALSE) && $schema->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "SCHEMA_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_schema($schema_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SCHEMA",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_schema($schema_id, $modified_by = 0)
	{
		return modules::run("Schema_module/delete_soft_schema_by_id", $schema_id, $modified_by);
	}

	public function delete_hard_schema_by_id($schema_id, $confirmation)
	{
		$schema = modules::run("Schema_module/get_schema_by_id", array(), $schema_id);

		if (!(modules::run("Permission_module/require_permission", "SCHEMA_CREATE_OWN", FALSE) && $schema->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "SCHEMA_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_schema($schema_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SCHEMA",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_schema($schema_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Schema_module/delete_hard_schema_by_id", $schema_id, $confirmation, $modified_by);
	}
}


