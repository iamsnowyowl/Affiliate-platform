<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_portfolios extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function test_drive()
	{
		$assessment = "Sertimedia Test Drive";
		$gdrive_assessment_dir = modules::run("Google_module/gdrive_create_folder", $assessment);
		
		$metadata = array(
			'name' => 'Test_Permission_domain.docx',
			'mimeType' => 'application/vnd.google-apps.document',
			'parents' => array($gdrive_assessment_dir->id)
		);

		$fileobject = array(
			'data' => file_get_contents(getenv("FILE_PROTECTED_PATH")."/letter_template/SURAT_TUGAS_ADMIN.docx"),
			'mimeType' => 'application/vnd.openxmlformats-officedocument',
			'uploadType' => 'multipart',
			'fields' => '*'
		);

		$gdrive_surat = modules::run("Google_module/gdrive_create_file", $metadata, $fileobject);

		$permission = array(
			'type' => 'anyone',
			'role' => 'writer',
			'allowFileDiscovery' => FALSE
		);
		modules::run("Google_module/gdrive_permission", $gdrive_assessment_dir->id, $permission);
		// $content_zip = modules::run("Google_module/gdrive_export", $gdrive_assessment_dir->id, "application/vnd.google-apps.folder", array("alt" => "media"));
		// $this->load->helper('download');
		// force_download("assessment.zip", $content_zip);
		// $this->download($gdrive_assessment_dir->id);
		debug($gdrive_surat);

		// $data = $this->master_portfolio_detail($master_portfolio_id);
		// response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function download($file_id) {

	}

	public function get_master_portfolio_detail($master_portfolio_id)
	{
		$this->my_parameter = $this->parameter;

		$data = $this->master_portfolio_detail($master_portfolio_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_master_portfolio_list() 
	{
		$this->my_parameter = $this->parameter;
		
		$data = $this->master_portfolio_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function master_portfolio_detail($master_portfolio_id)
	{
		$master_portfolios = modules::run("Master_portfolio_module/get_master_portfolio_by_id", $this->my_parameter, $master_portfolio_id);

		$this->load->helper("url");

		if ($master_portfolios === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "MASTER_PORTFOLIO",
							"reason" => "Master_portfolioNotFound"
						),
					)
				)
			);
		}

		return array("data" => $master_portfolios);
	}

	protected function master_portfolio_list()
	{
		return modules::run("Master_portfolio_module/get_master_portfolio_list", $this->my_parameter);
	}

	public function get_master_portfolio_count() 
	{
		$this->my_parameter = $this->parameter;

		$data = $this->master_portfolio_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function master_portfolio_count()
	{
		$count = modules::run("Master_portfolio_module/get_master_portfolio_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create master_portfolio
	public function create_master_portfolio_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_master_portfolio();
	}

	public function create_master_portfolio_session()
	{
		if (!modules::run("Permission_module/require_permission", "MASTER_PORTFOLIO_CREATE_OWN", FALSE)) modules::run("Permission_module/require_permission", "MASTER_PORTFOLIO_CREATE");
		
		$this->my_parameter = $this->parameter;

		if (!empty($this->my_parameter["document_state"]))
		{
			if (is_string($this->my_parameter["document_state"])) $this->my_parameter["document_state"] = explode(",", $this->my_parameter["document_state"]);
			$this->my_parameter["document_state"] = json_encode($this->my_parameter["document_state"]);
		}

		if (!empty($this->my_parameter["apl_document_state"]))
		{
			if (is_string($this->my_parameter["apl_document_state"])) $this->my_parameter["apl_document_state"] = explode(",", $this->my_parameter["apl_document_state"]);
			$this->my_parameter["apl_document_state"] = json_encode($this->my_parameter["apl_document_state"]);
		}

		if (!empty($this->my_parameter["acs_document_state"]))
		{
			if (is_string($this->my_parameter["acs_document_state"])) $this->my_parameter["acs_document_state"] = explode(",", $this->my_parameter["acs_document_state"]);
			$this->my_parameter["acs_document_state"] = json_encode($this->my_parameter["acs_document_state"]);
		}

		$created_by = $this->userdata['user_id'];

		$this->create_master_portfolio($created_by);
	}

	protected function create_master_portfolio($created_by = 0)
	{
		$master_portfolio_id = modules::run("Master_portfolio_module/create_master_portfolio", $this->my_parameter, $created_by);
			
		if ($master_portfolio_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "MASTER_PORTFOLIO",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		$data = array("data" => array("master_portfolio_id" => $master_portfolio_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function update_master_portfolio_by_id($master_portfolio_id)
	{
		$master_portfolio = modules::run("Master_portfolio_module/get_master_portfolio_by_id", array(), $master_portfolio_id);
		
		if (!(modules::run("Permission_module/require_permission", "MASTER_PORTFOLIO_CREATE_OWN", FALSE) && $master_portfolio->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "MASTER_PORTFOLIO_UPDATE");
		
		$this->my_parameter = $this->parameter;

		if (!empty($this->my_parameter["document_state"]))
		{
			if (is_string($this->my_parameter["document_state"])) $this->my_parameter["document_state"] = explode(",", $this->my_parameter["document_state"]);
			$this->my_parameter["document_state"] = json_encode($this->my_parameter["document_state"]);
		}

		if (!empty($this->my_parameter["apl_document_state"]))
		{
			if (is_string($this->my_parameter["apl_document_state"])) $this->my_parameter["apl_document_state"] = explode(",", $this->my_parameter["apl_document_state"]);
			$this->my_parameter["apl_document_state"] = json_encode($this->my_parameter["apl_document_state"]);
		}

		if (!empty($this->my_parameter["acs_document_state"]))
		{
			if (is_string($this->my_parameter["acs_document_state"])) $this->my_parameter["acs_document_state"] = explode(",", $this->my_parameter["acs_document_state"]);
			$this->my_parameter["acs_document_state"] = json_encode($this->my_parameter["acs_document_state"]);
		}
		
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_master_portfolio($master_portfolio_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "MASTER_PORTFOLIO",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_master_portfolio($master_portfolio, $modified_by)
	{
		return modules::run("Master_portfolio_module/update_master_portfolio_by_id", $master_portfolio, $this->my_parameter, $modified_by);
	}

	public function delete_soft_master_portfolio_by_id($master_portfolio_id)
	{
		$master_portfolio = modules::run("Master_portfolio_module/get_master_portfolio_by_id", array(), $master_portfolio_id);

		if (!(modules::run("Permission_module/require_permission", "MASTER_PORTFOLIO_CREATE_OWN", FALSE) && $master_portfolio->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "MASTER_PORTFOLIO_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_soft_master_portfolio($master_portfolio_id, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "MASTER_PORTFOLIO",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_soft_master_portfolio($master_portfolio_id, $modified_by = 0)
	{
		return modules::run("Master_portfolio_module/delete_soft_master_portfolio_by_id", $master_portfolio_id, $modified_by);
	}

	public function delete_hard_master_portfolio_by_id($master_portfolio_id, $confirmation)
	{
		$master_portfolio = modules::run("Master_portfolio_module/get_master_portfolio_by_id", array(), $master_portfolio_id);

		if (!(modules::run("Permission_module/require_permission", "MASTER_PORTFOLIO_CREATE_OWN", FALSE) && $master_portfolio->created_by == $this->userdata["user_id"])) modules::run("Permission_module/require_permission", "MASTER_PORTFOLIO_DELETE");

		$modified_by = $this->userdata['user_id'];
		$affected_rows = $this->delete_hard_master_portfolio($master_portfolio_id, $confirmation, $modified_by);

		if ($affected_rows === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "MASTER_PORTFOLIO",
							"reason" => "UpdateErrorException"
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_hard_master_portfolio($master_portfolio_id, $confirmation, $modified_by = 0)
	{
		return modules::run("Master_portfolio_module/delete_hard_master_portfolio_by_id", $master_portfolio_id, $confirmation, $modified_by);
	}
}


