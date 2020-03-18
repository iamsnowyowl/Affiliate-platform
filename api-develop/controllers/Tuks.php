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

	public function view_tuk_logo($tuk_id)
	{
		$tuk_data = modules::run("Tuk_module/get_tuk_by_id", array(), $tuk_id);

		$fullpath = "";

		$this->load->helper("url");

		if (!empty($tuk_data->tuk_id) && file_exists($tuk_data->logo)) {
			$data_src = file_get_contents($tuk_data->logo);
		}
		else {
			$data_src = file_get_contents(site_url("/files/content/default/default_image.jpg"));
		}

		$f = finfo_open();
		$mime_type = finfo_buffer($f, $data_src, FILEINFO_MIME_TYPE);
		finfo_close($f);

	    $this->output // You could also use ".jpeg" which will have the full stop removed before looking in config/mimes.php
		->set_output($data_src)->set_content_type($mime_type)->_display();
	}

	public function get_tuk_detail($tuk_id)
	{
		$this->my_parameter = $this->parameter;
		
		// if (!modules::run("Permission_module/require_permission", "TUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "TUK_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

		$data = $this->tuk_detail($tuk_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_tuk_list() 
	{
		$this->my_parameter = $this->parameter;
		
		// if (!modules::run("Permission_module/require_permission", "TUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "TUK_LIST");
		// else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 
		
		$data = $this->tuk_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_tuk_deleted_list()
	{
		$this->my_parameter = $this->parameter;

		$data = $this->tuk_deleted_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function tuk_deleted_list()
	{
		switch($this->userdata["role_code"]){
			case 'DEV':
				return modules::run("Tuk_module/get_tuk_deleted_list", $this->my_parameter);
			break;
			case 'SUP':
				return modules::run("Tuk_module/get_tuk_deleted_list", $this->my_parameter);
			break;
			default:
			modules::run("Permission_module/require_permission", "TUK_DELETED_LIST");
		break;
		}
	}
	protected function tuk_detail($tuk_id)
	{
		$tuks = modules::run("Tuk_module/get_tuk_by_id", $this->my_parameter, $tuk_id);

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

		return array("data" => $tuks);
	}

	protected function tuk_list()
	{
		return modules::run("Tuk_module/get_tuk_list", $this->my_parameter);
	}

	public function get_tuk_count() 
	{
		$this->my_parameter = $this->parameter;

		if (!modules::run("Permission_module/require_permission", "TUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "TUK_LIST");
		else $this->my_parameter["created_by"] = $this->userdata["user_id"]; 

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
		if (!modules::run("Permission_module/require_permission", "TUK_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "TUK_CREATE");
		
		$this->my_parameter = $this->parameter;
		$created_by = $this->userdata['user_id'];

		$this->create_tuk($created_by);
	}

	protected function create_tuk($created_by = 0)
	{
		$tuk_id = modules::run("Tuk_module/create_tuk", $this->my_parameter, $created_by);
			
		if ($tuk_id === FALSE)
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

		$data = array("data" => array("tuk_id" => $tuk_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_tuk_by_id($tuk_id)
	{
		$tuk = modules::run("Tuk_module/get_tuk_by_id", array(), $tuk_id);
		
		if (!(modules::run("Permission_module/require_permission", "TUK_CREATE_OWN", FALSE) && $tuk->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "TUK_UPDATE");
		
		$this->my_parameter = $this->parameter;

		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_tuk($tuk_id, $modified_by);

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

	protected function update_tuk($tuk, $modified_by)
	{
		return modules::run("Tuk_module/update_tuk_by_id", $tuk, $this->my_parameter, $modified_by);
	}

	public function delete_soft_tuk_by_id($tuk_id)
	{
		$tuk = modules::run("Tuk_module/get_tuk_by_id", array(), $tuk_id);

		if (!(modules::run("Permission_module/require_permission", "TUK_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "TUK_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_tuk($tuk_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "TUK",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_tuk($tuk_id, $modified_by = 0)
	{
		return modules::run("Tuk_module/delete_soft_tuk_by_id", $tuk_id, $modified_by);
	}

	public function delete_hard_tuk_by_id($tuk_id, $confirmation)
	{
		$tuk = modules::run("Tuk_module/get_tuk_by_id", array(), $tuk_id);

		if (!(modules::run("Permission_module/require_permission", "TUK_CREATE_OWN", FALSE) && $gen->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "TUK_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_tuk($tuk_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "TUK",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_tuk($tuk_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Tuk_module/delete_hard_tuk_by_id", $tuk_id, $confirmation, $modified_by);
	}

	public function update_deleted_by_id($tuk_id)
	{
		$modified_by = $this->userdata['user_id'];

		$affected_rows = $this->update_deleted_list($tuk_id, $modified_by);

		if ($affected_rows === FALSE) {
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "TUKS",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));

	}

	protected function update_deleted_list($tuk_id, $modified_by)
	{
		switch ($this->userdata["role_code"]) {
			case 'DEV':
				return modules::run("Tuk_module/update_deleted_list", $tuk_id, $modified_by);
			break;
			case 'SUP':
				return modules::run("Tuk_module/update_deleted_list", $tuk_id, $modified_by);
			break;
			default:
			modules::run("Permission_module/require_permission", "TUK_DELETED_LIST");
		break;
		}
	}
}


