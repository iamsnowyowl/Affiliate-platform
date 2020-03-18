<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downloads extends MX_Controller {
	
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


	public function download_apk(){
		$this->load->helper('download');
		$basepath = "/home/aridjemana/build_apk/";
		$version = $this->input->get("version", TRUE);
		if (empty($version)){
			$version = "mmr";
		}

		if (!file_exists($basepath.$version.".apk")){
			die("file doesn't exist");
		}
		force_download($basepath.$version.".apk", NULL);
	}

	public function get_own_download_detail($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$data = $this->download_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_own_download_list() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];
		$data = $this->download_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_download_detail($row_id)
	{
		modules::run("Permission_module/require_permission", "DOWNLOAD_LIST");
		$this->my_parameter = $this->parameter;

		$row_id = intval($row_id);
		$data = $this->download_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_download_list() 
	{
		modules::run("Permission_module/require_permission", "DOWNLOAD_LIST");
		$this->my_parameter = $this->parameter;
		$data = $this->download_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function download_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$downloads = modules::run("Download_module/get_download_by_id", $this->my_parameter, $row_id);

			$this->load->helper("url");

			if ($downloads === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "DOWNLOAD",
								"reason" => "DownloadNotFound"
							),
						)
					)
				);
			}
			$data[$this->node] = $downloads;
		}
		else
		{
			$downloads = array();
			$count = NULL;

			$data = modules::run("Download_module/get_download_list", $this->my_parameter);
			$data[$this->node] = $data['data'];
			unset($data['data']);
		}

		return $data;
	}

	public function get_own_download_count() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$data = $this->download_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_download_count() 
	{
		modules::run("Permission_module/require_permission", "DOWNLOAD_LIST");
		$this->my_parameter = $this->parameter;

		$data = $this->download_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function download_count()
	{
		$count = modules::run("Download_module/get_download_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create download
	public function create_download_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_download();
	}

	public function create_download_session()
	{
		modules::run("Permission_module/require_permission", "DOWNLOAD_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_download($created_by);
	}

	protected function create_download($created_by = 0)
	{
		$row_id = modules::run("Download_module/create_download", $this->my_parameter, $created_by);
			
		if ($row_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "DOWNLOAD",
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

	public function update_own_download_by_id($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_download($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "DOWNLOAD",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function update_download_by_id($row_id)
	{
		modules::run("Permission_module/require_permission", "DOWNLOAD_UPDATE");
		$this->my_parameter = $this->parameter;
		
		$row_id = intval($row_id);
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_download($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "DOWNLOAD",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_download($row_id, $modified_by)
	{
		return modules::run("Download_module/update_download_by_id", $row_id, $this->my_parameter, $modified_by);
	}

	public function delete_own_download_by_id()
	{
		modules::run("Permission_module/require_permission", "DOWNLOAD_DELETE");

		$affected_row = $this->delete_download();

		if ($affected_row != count($downloads))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "DOWNLOAD",
							"reason" => "UpdateErrorException",
							"extra" => array("counter_deleted" => $affected_row)
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_download_by_id()
	{
		modules::run("Permission_module/require_permission", "DOWNLOAD_DELETE");

		$affected_rows = $this->delete_download();

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_download()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$downloads = array_map("trim", $segs);

		$affected_rows = modules::run("Download_module/delete_download_by_id", $downloads);

		if ($affected_rows != count($downloads))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "DOWNLOAD",
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


