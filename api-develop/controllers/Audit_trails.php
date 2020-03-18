<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_trails extends MX_Controller {
	
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

	public function get_own_audit_trail_detail($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$data = $this->audit_trail_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_own_audit_trail_list() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];
		$data = $this->audit_trail_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_audit_trail_detail($row_id)
	{
		modules::run("Permission_module/require_permission", "AUDIT_TRAIL_LIST");
		$this->my_parameter = $this->parameter;

		$row_id = intval($row_id);
		$data = $this->audit_trail_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_audit_trail_list() 
	{
		modules::run("Permission_module/require_permission", "AUDIT_TRAIL_LIST");
		$this->my_parameter = $this->parameter;
		$data = $this->audit_trail_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function audit_trail_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$audit_trails = modules::run("Audit_trail_module/get_audit_trail_by_id", $this->my_parameter, $row_id);

			$this->load->helper("url");

			if ($audit_trails === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "AUDIT_TRAIL",
								"reason" => "Audit_trailNotFound"
							),
						)
					)
				);
			}
			$data[$this->node] = $audit_trails;
		}
		else
		{
			$audit_trails = array();
			$count = NULL;

			$data = modules::run("Audit_trail_module/get_audit_trail_list", $this->my_parameter);
			$data[$this->node] = $data['data'];
			unset($data['data']);
		}

		return $data;
	}

	public function get_own_audit_trail_count() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$data = $this->audit_trail_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_audit_trail_count() 
	{
		modules::run("Permission_module/require_permission", "AUDIT_TRAIL_LIST");
		$this->my_parameter = $this->parameter;

		$data = $this->audit_trail_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function audit_trail_count()
	{
		$count = modules::run("Audit_trail_module/get_audit_trail_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create audit_trail
	public function create_audit_trail_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_audit_trail();
	}

	public function create_audit_trail_session()
	{
		modules::run("Permission_module/require_permission", "AUDIT_TRAIL_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_audit_trail($created_by);
	}

	protected function create_audit_trail($created_by = 0)
	{
		$row_id = modules::run("Audit_trail_module/create_audit_trail", $this->my_parameter, $created_by);
			
		if ($row_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "AUDIT_TRAIL",
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

	public function update_own_audit_trail_by_id($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_audit_trail($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "AUDIT_TRAIL",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function update_audit_trail_by_id($row_id)
	{
		modules::run("Permission_module/require_permission", "AUDIT_TRAIL_UPDATE");
		$this->my_parameter = $this->parameter;
		
		$row_id = intval($row_id);
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_audit_trail($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "AUDIT_TRAIL",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_audit_trail($row_id, $modified_by)
	{
		return modules::run("Audit_trail_module/update_audit_trail_by_id", $row_id, $this->my_parameter, $modified_by);
	}

	public function delete_own_audit_trail_by_id()
	{
		modules::run("Permission_module/require_permission", "AUDIT_TRAIL_DELETE");

		$affected_row = $this->delete_audit_trail();

		if ($affected_row != count($audit_trails))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "AUDIT_TRAIL",
							"reason" => "UpdateErrorException",
							"extra" => array("counter_deleted" => $affected_row)
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_audit_trail_by_id()
	{
		modules::run("Permission_module/require_permission", "AUDIT_TRAIL_DELETE");

		$affected_rows = $this->delete_audit_trail();

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_audit_trail()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$audit_trails = array_map("trim", $segs);

		$affected_rows = modules::run("Audit_trail_module/delete_audit_trail_by_id", $audit_trails);

		if ($affected_rows != count($audit_trails))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "AUDIT_TRAIL",
							"reason" => "UpdateErrorException",
							"extra" => array("counter_deleted" => $affected_rows)
						),
					)
				)
			);
		}

		return $affected_rows;
	}
}


