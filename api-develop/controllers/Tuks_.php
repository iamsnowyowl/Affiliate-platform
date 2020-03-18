<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tuks extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_own_tuk_detail($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$data = $this->tuk_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_own_tuk_list() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];
		$data = $this->tuk_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_tuk_detail($row_id)
	{
		$this->my_parameter = $this->parameter;

		$row_id = intval($row_id);
		$data = $this->tuk_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_tuk_list() 
	{
		$this->my_parameter = $this->parameter;
		$data = $this->tuk_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function tuk_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$tuks = modules::run("Tuk_module/get_tuk_by_id", $this->my_parameter, $row_id);

			$this->load->helper("url");

			if ($tuks === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "TUK",
								"reason" => "TukNotFound"
							),
						)
					)
				);
			}
			$data["data"] = $tuks;
		}
		else
		{
			$data = modules::run("Tuk_module/get_tuk_list", $this->my_parameter);
		}

		return $data;
	}

	public function get_own_tuk_count() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$data = $this->tuk_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_tuk_count() 
	{
		modules::run("Permission_module/require_permission", "TUK_LIST");
		$this->my_parameter = $this->parameter;

		$data = $this->tuk_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function tuk_count()
	{
		$count = modules::run("Tuk_module/get_tuk_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create tuk
	public function create_tuk_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_tuk();
	}

	public function create_tuk_session()
	{
		modules::run("Permission_module/require_permission", "TUK_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_tuk($created_by);
	}

	protected function create_tuk($created_by = 0)
	{
		$row_id = modules::run("Tuk_module/create_tuk", $this->my_parameter, $created_by);
			
		if ($row_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "TUK",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("tuk_id" => $row_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_own_tuk_by_id($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_tuk($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "TUK",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function update_tuk_by_id($row_id)
	{
		modules::run("Permission_module/require_permission", "TUK_UPDATE");
		$this->my_parameter = $this->parameter;
		
		$row_id = intval($row_id);
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_tuk($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "TUK",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_tuk($row_id, $modified_by)
	{
		return modules::run("Tuk_module/update_tuk_by_id", $row_id, $this->my_parameter, $modified_by);
	}

	public function delete_own_tuk_by_id()
	{
		modules::run("Permission_module/require_permission", "TUK_DELETE");

		$affected_row = $this->delete_tuk();

		if ($affected_row != count($tuks))
		{
			$code = modules::run("Error_module/get_error_code");
			response(400, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 400,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "TUK",
							"reason" => "UpdateErrorException",
							"extra" => array("counter_deleted" => $affected_row)
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_tuk_by_id()
	{
		modules::run("Permission_module/require_permission", "TUK_DELETE");

		$affected_rows = $this->delete_tuk();

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_tuk()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$tuks = array_map("trim", $segs);

		$affected_rows = modules::run("Tuk_module/delete_tuk_by_id", $tuks);

		if ($affected_rows != count($tuks))
		{
			$code = modules::run("Error_module/get_error_code");
			response(400, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 400,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "TUK",
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


