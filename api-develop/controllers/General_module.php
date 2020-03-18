<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General_module extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_general_detail($row_id)
	{
		modules::run("Permission_module/require_permission", "GENERAL_LIST");

		$row_id = intval($row_id);
		$data = $this->general_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_general_list() 
	{
		modules::run("Permission_module/require_permission", "GENERAL_LIST");
		$data = $this->general_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function general_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$generals = modules::run("General_module/get_general_by_id", $this->input->get(NULL, TRUE), $row_id);

			$this->load->helper("url");

			if ($generals === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "GENERAL",
								"reason" => "GeneralNotFound"
							),
						)
					)
				);
			}
			$data['data'] = $generals;
		}
		else
		{
			$generals = array();
			$count = NULL;

			$data = modules::run("General_module/get_general_list", $this->input->get(NULL, TRUE));
		}

		return $data;
	}

	public function general_count()
	{
		modules::run("Permission_module/require_permission", "GENERAL_LIST");

		$count = modules::run("General_module/get_general_count", $this->input->get(NULL, TRUE));
		response(200, array_merge(array("responseStatus" => "SUCCESS"), (array) $count));
	}

	# begin create general
	public function create_general_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_general();
	}

	public function create_general_session()
	{
		$this->my_parameter = $this->parameter;

		modules::run("Permission_module/require_permission", "GENERAL_CREATE");
		$created_by = $this->userdata['user_id'];

		$this->create_general($created_by);
	}

	protected function create_general($created_by = 0)
	{
		$row_id = modules::run("General_module/create_general", $this->my_parameter, $created_by);
			
		if ($row_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "GENERAL",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("row_id" => $row_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_general_by_id($row_id)
	{
		modules::run("Permission_module/require_permission", "GENERAL_UPDATE");
		
		$row_id = intval($row_id);
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$this->update_general($row_id, $modified_by);
	}

	protected function update_general($row_id, $modified_by)
	{
		$affected_row = modules::run("General_module/update_general_by_id", $row_id, $this->my_parameter, $modified_by);
			
		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "GENERAL",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_general_by_id()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$generals = array_map("trim", $segs);

		if (in_array($this->userdata['user_id'], $generals))
		{
			response(400, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 400,
						"message" => "general can't self delete",
						"errors" => array(
							"domain" => "GENERAL",
							"reason" => "DeleteErrorException"
						),
					)
				)
			);
		}

		// require access delete general
		modules::run("Permission_module/require_permission", "GENERAL_DELETE");

		$affected_row = modules::run("General_module/delete_general_by_id", $generals);
		if ($affected_row != count($generals))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "GENERAL",
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


