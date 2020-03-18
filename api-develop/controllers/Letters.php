<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Letters extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_letter_detail($letter_id)
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "LETTER_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "LETTER_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->letter_detail($letter_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_letter_list() 
	{
		$this->my_parameter = $this->parameter;
		
		if (!modules::run("Permission_module/require_permission", "LETTER_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "LETTER_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->letter_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function letter_detail($letter_id)
	{
		$letters = modules::run("Letter_module/get_letter_by_id", $this->my_parameter, $letter_id);

		$this->load->helper("url");

		if ($letters === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "LETTER",
							"reason" => "LetterNotFound"
						),
					)
				)
			);
		}

		return array("data" => $letters);
	}

	protected function letter_list()
	{
		return modules::run("Letter_module/get_letter_list", $this->my_parameter);
	}

	public function get_letter_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "LETTER_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "LETTER_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->letter_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function letter_count()
	{
		$count = modules::run("Letter_module/get_letter_count", $this->my_parameter);
		return (array) $count;
	}

	public function get_letter_deleted_list()
	{
		$data = $this->letter_deleted_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function letter_deleted_list()
	{
		switch ($this->userdata["role_code"])
		{
			case 'DEV':
				return modules::run("Letter_module/get_letter_deleted_list", $this->my_parameter);
			break;
			case 'SUP':
				return modules::run("Letter_module/get_letter_deleted_list", $this->my_parameter);
			break;
			default:
			modules::run("Permission_module/require_permission", "LETTER_DELETED_LIST");
		break;
		}
	}

	public function update_letter_by_id($letter_id)
	{
		$letter = modules::run("Letter_module/get_letter_by_id", array(), $letter_id);
		
		if (!(modules::run("Permission_module/require_permission", "LETTER_CREATE_OWN", FALSE) && $letter->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "LETTER_UPDATE");
		
		$this->my_parameter = $this->parameter;
		if (!empty($this->parameter["file"]) && !empty($this->parameter["filename"])) {
			// store file
			$this->load->helper("file");
			$ext = pathinfo($this->parameter["filename"], PATHINFO_EXTENSION);
			$this->my_parameter["file"] = getenv("FILE_PROTECTED_PATH")."/letter_template/$letter->letter_name.$ext";
			store_file_from_base64($this->parameter["file"], $this->my_parameter["file"], TRUE);
		}

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_letter($letter_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "LETTER",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function download_letter_by_id($letter_id)
	{
		$letter = modules::run("Letter_module/get_letter_by_id", array(), $letter_id, "default_download", "optional_download");
		
		if (!(modules::run("Permission_module/require_permission", "LETTER_CREATE_OWN", FALSE) && $letter->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "LETTER_UPDATE");
		
		$this->load->helper('download');

		if (!empty($letter->file) && file_exists($letter->file)){
			force_download($letter->file, NULL);
			return;
		}
		$code = 400;
		response($code, array(
				"responseStatus" => "ERROR",
				"error" => array(
					"code" => $code,
					"message" => "Failed to download file",
					"errors" => array(
						"domain" => "LETTER",
						"reason" => "DownloadErrorException"
					)
				)
			)
		);
	}

	protected function update_letter($letter, $modified_by)
	{
		return modules::run("Letter_module/update_letter_by_id", $letter, $this->my_parameter, $modified_by);
	}

	public function update_deleted($letter_id)
	{
		$modified_by = $this->userdata['user_id'];

		$affected_rows = $this->update_deleted_by_id($letter_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
				"responseStatus" => "ERROR",
				"error" => array(
					"code" => $code,
					"message" => modules::run("Error_module/get_error"),
					"errors" => array(
						"domain" => "LETTERS",
						"reason" => "UpdateErrorException",
						"extra" => modules::run("Error_module/get_error_extra")
					),
				)
			)
		);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}
	
	protected function update_deleted_by_id($letter_id, $modified_by)
	{
		switch ($this->userdata['role_code']) {
			case 'DEV':
				return modules::run("Letter_module/update_deleted_by_id", $letter_id, $modified_by);
			break;
			case 'SUP':
				return modules::run("Letter_module/update_deleted_by_id", $letter_id, $modified_by);
			break;
			default:
			modules::run("Permission_module/require_permission", "LETTER_DELETED_LIST");
		break;
		}
	}
}


