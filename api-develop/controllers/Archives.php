<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Archives extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_archive_detail($archive_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ARCHIVE_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ARCHIVE_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->archive_detail($archive_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_archive_list() 
	{
		$this->my_parameter = $this->parameter;
		if (!modules::run("Permission_module/require_permission", "ARCHIVE_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ARCHIVE_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->archive_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function download_archive($archive_id) {
		$archive = modules::run("Archive_module/get_archive_by_id", [], $archive_id);

		if (empty($archive)){
			$code = 404;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "archive not found",
						"errors" => array(
							"domain" => "ARCHIVE",
							"reason" => "ArchiveNotFound"
						)
					)
				)
			);
		}
		$this->load->library('zip');
		$configuration_name = 'configuration_background_worker_module';
		$this->config->load($configuration_name, TRUE, TRUE);
		$config = $this->config->item($configuration_name);
		$basepath = $config["archive"]["base_file"]."/".$config["archive"]["root_dir_name"];
		$this->zip->read_dir($config["archive"]["base_file"]."/".$config["archive"]["root_dir_name"]."/".$archive->assessment_id, FALSE);
		$this->zip->download($archive->title.'.zip');
	}

	protected function archive_detail($archive_id)
	{
		$archives = modules::run("Archive_module/get_archive_by_id", $this->my_parameter, $archive_id);

		$this->load->helper("url");

		if ($archives === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ARCHIVE",
							"reason" => "ArchiveNotFound"
						),
					)
				)
			);
		}

		return array("data" => $archives);
	}

	protected function archive_list()
	{
		return modules::run("Archive_module/get_archive_list", $this->my_parameter);
	}

	public function get_archive_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "ARCHIVE_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "ARCHIVE_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->archive_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function archive_count()
	{
		$count = modules::run("Archive_module/get_archive_count", $this->my_parameter);
		return (array) $count;
	}

	public function delete_hard_archive_by_id($archive_id, $confirmation)
	{
		$archive = modules::run("Archive_module/get_archive_by_id", array(), $archive_id);

		if (!(modules::run("Permission_module/require_permission", "ARCHIVE_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "ARCHIVE_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_archive($archive_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "ARCHIVE",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_archive($archive_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Archive_module/delete_hard_archive_by_id", $archive_id, $confirmation, $modified_by);
	}
}


