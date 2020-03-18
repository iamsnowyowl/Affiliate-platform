<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends MX_Controller {
	
	protected $my_parameter;
	protected $node;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_permission_detail($row_id)
	{
		modules::run("Permission_module/require_permission", "PERMISSION_LIST");

		$row_id = intval($row_id);
		$data = $this->permission_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_permission_list() 
	{
		modules::run("Permission_module/require_permission", "PERMISSION_LIST");
		$data = $this->permission_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function permission_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$permissions = modules::run("Permission_module/get_permission_by_id", $this->input->get(NULL, TRUE), $row_id);

			$this->load->helper("url");

			if ($permissions === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "PERMISSION",
								"reason" => "PermissionNotFound"
							),
						)
					)
				);
			}
			$data[$this->node] = $permissions;
		}
		else
		{
			$permissions = array();
			$count = NULL;

			$data = modules::run("Permission_module/get_permission_list", $this->input->get(NULL, TRUE));
			$data[$this->node] = $data['data'];
			unset($data['data']);
		}

		return $data;
	}

	public function permission_count()
	{
		modules::run("Permission_module/require_permission", "PERMISSION_LIST");

		$count = modules::run("Permission_module/get_permission_count", $this->input->get(NULL, TRUE));
		response(200, array_merge(array("responseStatus" => "SUCCESS"), (array) $count));
	}

	# begin create permission
	public function create_permission_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_permission();
	}

	public function create_permission_session()
	{
		$this->my_parameter = $this->parameter;

		modules::run("Permission_module/require_permission", "PERMISSION_CREATE");
		$created_by = $this->userdata['user_id'];

		$this->create_permission($created_by);
	}

	protected function create_permission($created_by = 0)
	{
		$row_id = modules::run("Permission_module/create_permission", $this->my_parameter, $created_by);
			
		if ($row_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "PERMISSION",
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

	public function update_permission_by_id($row_id)
	{
		modules::run("Permission_module/require_permission", "PERMISSION_UPDATE");
		
		$row_id = intval($row_id);
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$this->update_permission($row_id, $modified_by);
	}

	protected function update_permission($row_id, $modified_by)
	{
		$affected_row = modules::run("Permission_module/update_permission_by_id", $row_id, $this->my_parameter, $modified_by);
			
		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "PERMISSION",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_permission_by_id()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$permissions = array_map("trim", $segs);

		// require access delete permission
		modules::run("Permission_module/require_permission", "PERMISSION_DELETE");

		$affected_row = modules::run("Permission_module/delete_permission_by_id", $permissions);
		if ($affected_row != count($permissions))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "PERMISSION",
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


