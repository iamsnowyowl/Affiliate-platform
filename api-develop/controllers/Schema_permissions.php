<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schema_permissions extends MX_Controller {
	
	protected $my_parameter;
	protected $node;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
		$this->node = strtolower(get_class($this));
	}

	public function get_schema_permission_detail($row_id)
	{
		modules::run("Permission_module/require_permission", "SCHEMA_PERMISSION_LIST");

		$row_id = intval($row_id);
		$data = $this->schema_permission_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_schema_permission_list() 
	{
		modules::run("Permission_module/require_permission", "SCHEMA_PERMISSION_LIST");
		$data = $this->schema_permission_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function schema_permission_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$schema_permissions = modules::run("Schema_permission_module/get_schema_permission_by_id", $this->input->get(NULL, TRUE), $row_id);

			$this->load->helper("url");

			if ($schema_permissions === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "SCHEMA_PERMISSION",
								"reason" => "Schema_permissionNotFound"
							),
						)
					)
				);
			}
			$data[$this->node] = $schema_permissions;
		}
		else
		{
			$schema_permissions = array();
			$count = NULL;

			$data = modules::run("Schema_permission_module/get_schema_permission_list", $this->input->get(NULL, TRUE));
			$data[$this->node] = $data['data'];
			unset($data['data']);
		}

		return $data;
	}

	public function schema_permission_count()
	{
		modules::run("Permission_module/require_permission", "SCHEMA_PERMISSION_LIST");

		$count = modules::run("Schema_permission_module/get_schema_permission_count", $this->input->get(NULL, TRUE));
		response(200, array_merge(array("responseStatus" => "SUCCESS"), (array) $count));
	}

	# begin create schema_permission
	public function create_schema_permission_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_schema_permission();
	}

	public function create_schema_permission_session()
	{
		$this->my_parameter = $this->parameter;

		modules::run("Permission_module/require_permission", "SCHEMA_PERMISSION_CREATE");
		$created_by = $this->userdata['user_id'];

		$this->create_schema_permission($created_by);
	}

	protected function create_schema_permission($created_by = 0)
	{
		$row_id = modules::run("Schema_permission_module/create_schema_permission", $this->my_parameter, $created_by);
			
		if ($row_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SCHEMA_PERMISSION",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array($this->node => array("row_id" => $row_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_schema_permission_by_id($row_id)
	{
		modules::run("Permission_module/require_permission", "SCHEMA_PERMISSION_UPDATE");
		
		$row_id = intval($row_id);
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$this->update_schema_permission($row_id, $modified_by);
	}

	protected function update_schema_permission($row_id, $modified_by)
	{
		$affected_row = modules::run("Schema_permission_module/update_schema_permission_by_id", $row_id, $this->my_parameter, $modified_by);
			
		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SCHEMA_PERMISSION",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_schema_permission_by_id()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$schema_permissions = array_map("trim", $segs);

		// require access delete schema_permission
		modules::run("Permission_module/require_permission", "SCHEMA_PERMISSION_DELETE");

		$affected_row = modules::run("Schema_permission_module/delete_schema_permission_by_id", $schema_permissions);
		if ($affected_row != count($schema_permissions))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "SCHEMA_PERMISSION",
							"reason" => "UpdateErrorException",
							"extra" => array("counter_deleted" => $affected_row)
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}
}


