<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Submodules extends MX_Controller {
	
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

	public function get_submodule_detail($row_id)
	{
		modules::run("Permission_module/require_permission", "SUBMODULE_LIST");

		$row_id = intval($row_id);
		$data = $this->submodule_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_submodule_list() 
	{
		modules::run("Permission_module/require_permission", "SUBMODULE_LIST");
		$data = $this->submodule_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function submodule_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$submodules = modules::run("Submodule_module/get_submodule_by_id", $this->input->get(NULL, TRUE), $row_id);

			$this->load->helper("url");

			if ($submodules === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "SUBMODULE",
								"reason" => "SubmoduleNotFound"
							),
						)
					)
				);
			}
			$data[$this->node] = $submodules;
		}
		else
		{
			$submodules = array();
			$count = NULL;

			$data = modules::run("Submodule_module/get_submodule_list", $this->input->get(NULL, TRUE));
			$data[$this->node] = $data['data'];
			unset($data['data']);
		}

		return $data;
	}

	public function submodule_count()
	{
		modules::run("Permission_module/require_permission", "SUBMODULE_LIST");

		$count = modules::run("Submodule_module/get_submodule_count", $this->input->get(NULL, TRUE));
		response(200, array_merge(array("responseStatus" => "SUCCESS"), (array) $count));
	}

	# begin create submodule
	public function create_submodule_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_submodule();
	}

	public function create_submodule_session()
	{
		$this->my_parameter = $this->parameter;

		modules::run("Permission_module/require_permission", "SUBMODULE_CREATE");
		$created_by = $this->userdata['user_id'];

		$this->create_submodule($created_by);
	}

	protected function create_submodule($created_by = 0)
	{
		$row_id = modules::run("Submodule_module/create_submodule", $this->my_parameter, $created_by);
			
		if ($row_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SUBMODULE",
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

	public function update_submodule_by_id($row_id)
	{
		modules::run("Permission_module/require_permission", "SUBMODULE_UPDATE");
		
		$row_id = intval($row_id);
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$this->update_submodule($row_id, $modified_by);
	}

	protected function update_submodule($row_id, $modified_by)
	{
		$affected_row = modules::run("Submodule_module/update_submodule_by_id", $row_id, $this->my_parameter, $modified_by);
			
		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SUBMODULE",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_submodule_by_id()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$submodules = array_map("trim", $segs);

		// require access delete submodule
		modules::run("Permission_module/require_permission", "SUBMODULE_DELETE");

		$affected_row = modules::run("Submodule_module/delete_submodule_by_id", $submodules);
		if ($affected_row != count($submodules))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "SUBMODULE",
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


