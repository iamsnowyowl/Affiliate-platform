<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lsps extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_own_lsp_detail($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$data = $this->lsp_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_own_lsp_list() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];
		$data = $this->lsp_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_lsp_detail($row_id)
	{
		modules::run("Permission_module/require_permission", "LSP_LIST");
		$this->my_parameter = $this->parameter;

		$row_id = intval($row_id);
		$data = $this->lsp_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_lsp_list() 
	{
		modules::run("Permission_module/require_permission", "LSP_LIST");
		$this->my_parameter = $this->parameter;
		$data = $this->lsp_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function lsp_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$data = modules::run("Lsp_module/get_lsp_by_id", $this->my_parameter, $row_id);

			$this->load->helper("url");

			if ($data === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "LSP",
								"reason" => "LspNotFound"
							),
						)
					)
				);
			}
		}
		else
		{
			$data = modules::run("Lsp_module/get_lsp_list", $this->my_parameter);
		}

		return $data;
	}

	public function get_own_lsp_count() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$data = $this->lsp_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_lsp_count() 
	{
		modules::run("Permission_module/require_permission", "LSP_LIST");
		$this->my_parameter = $this->parameter;

		$data = $this->lsp_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function lsp_count()
	{
		$count = modules::run("Lsp_module/get_lsp_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create lsp
	public function create_lsp_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_lsp();
	}

	public function create_lsp_session()
	{
		modules::run("Permission_module/require_permission", "LSP_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_lsp($created_by);
	}

	protected function create_lsp($created_by = 0)
	{
		$row_id = modules::run("Lsp_module/create_lsp", $this->my_parameter, $created_by);
			
		if ($row_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "LSP",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("lsp_code" => $row_id));

		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_own_lsp_by_id($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_lsp($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "LSP",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function update_lsp_by_id($row_id)
	{
		modules::run("Permission_module/require_permission", "LSP_UPDATE");
		$this->my_parameter = $this->parameter;
		
		$row_id = intval($row_id);
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_lsp($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "LSP",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_lsp($row_id, $modified_by)
	{
		return modules::run("Lsp_module/update_lsp_by_id", $row_id, $this->my_parameter, $modified_by);
	}

	public function delete_own_lsp_by_id()
	{
		modules::run("Permission_module/require_permission", "LSP_DELETE");

		$affected_row = $this->delete_lsp();

		if ($affected_row != count($lsps))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "LSP",
							"reason" => "UpdateErrorException",
							"extra" => array("counter_deleted" => $affected_row)
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_lsp_by_id()
	{
		modules::run("Permission_module/require_permission", "LSP_DELETE");

		$affected_rows = $this->delete_lsp();

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_lsp()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$lsps = array_map("trim", $segs);

		$affected_rows = modules::run("Lsp_module/delete_lsp_by_id", $lsps);

		if ($affected_rows != count($lsps))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "LSP",
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


