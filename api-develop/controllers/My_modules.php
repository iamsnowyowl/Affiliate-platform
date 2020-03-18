<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_modules extends MX_Controller {
	
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

	public function get_module_detail($row_id)
	{
		modules::run("Permission_module/require_permission", "MODULE_LIST");

		$row_id = intval($row_id);
		$data = $this->module_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_module_list() 
	{
		modules::run("Permission_module/require_permission", "MODULE_LIST");
		$data = $this->module_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function module_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$modules = modules::run("Module_module/get_module_by_id", $this->input->get(NULL, TRUE), $row_id);

			$this->load->helper("url");

			if ($modules === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "MODULE",
								"reason" => "ModuleNotFound"
							),
						)
					)
				);
			}
			$data[$this->node] = $modules;
		}
		else
		{
			$modules = array();
			$count = NULL;

			$data = modules::run("Module_module/get_module_list", $this->input->get(NULL, TRUE));
			$data[$this->node] = $data['data'];
			unset($data['data']);
		}

		return $data;
	}

	public function module_count()
	{
		modules::run("Permission_module/require_permission", "MODULE_LIST");

		$count = modules::run("Module_module/get_module_count", $this->input->get(NULL, TRUE));
		response(200, array_merge(array("responseStatus" => "SUCCESS"), (array) $count));
	}

	# begin create module
	public function create_module_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_module();
	}

	public function create_module_session()
	{
		$this->my_parameter = $this->parameter;

		modules::run("Permission_module/require_permission", "MODULE_CREATE");
		$created_by = $this->userdata['user_id'];

		$this->create_module($created_by);
	}

	protected function create_module($created_by = 0)
	{
		$row_id = modules::run("Module_module/create_module", $this->my_parameter, $created_by);
			
		if ($row_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "MODULE",
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

	public function update_module_by_id($row_id)
	{
		modules::run("Permission_module/require_permission", "MODULE_UPDATE");
		
		$row_id = intval($row_id);
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$this->update_module($row_id, $modified_by);
	}

	protected function update_module($row_id, $modified_by)
	{
		$affected_row = modules::run("Module_module/update_module_by_id", $row_id, $this->my_parameter, $modified_by);
			
		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "MODULE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_module_by_id()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$modules = array_map("trim", $segs);

		// require access delete module
		modules::run("Permission_module/require_permission", "MODULE_DELETE");

		$affected_row = modules::run("Module_module/delete_module_by_id", $modules);
		if ($affected_row != count($modules))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "MODULE",
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


