<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MX_Controller {
	
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

	public function get_setting_detail($row_id)
	{
		modules::run("Permission_module/require_permission", "SETTING_LIST");

		$row_id = intval($row_id);
		$data = $this->setting_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_setting_list() 
	{
		modules::run("Permission_module/require_permission", "SETTING_LIST");
		$data = $this->setting_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function setting_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$settings = modules::run("Setting_module/get_setting_by_id", $this->input->get(NULL, TRUE), $row_id);

			$this->load->helper("url");

			if ($settings === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "SETTING",
								"reason" => "SettingNotFound"
							),
						)
					)
				);
			}
			$data[$this->node] = $settings;
		}
		else
		{
			$settings = array();
			$count = NULL;

			$data = modules::run("Setting_module/get_setting_list", $this->input->get(NULL, TRUE));
			$data[$this->node] = $data['data'];
			unset($data['data']);
		}

		return $data;
	}

	public function setting_count()
	{
		modules::run("Permission_module/require_permission", "SETTING_LIST");

		$count = modules::run("Setting_module/get_setting_count", $this->input->get(NULL, TRUE));
		response(200, array_merge(array("responseStatus" => "SUCCESS"), (array) $count));
	}

	# begin create setting
	public function create_setting_public()
	{
		$this->my_parameter = $this->parameter;
		
		$this->create_setting();
	}

	public function create_setting_session()
	{
		$this->my_parameter = $this->parameter;

		modules::run("Permission_module/require_permission", "SETTING_CREATE");
		$created_by = $this->userdata['user_id'];

		$this->create_setting($created_by);
	}

	protected function create_setting($created_by = 0)
	{
		$row_id = modules::run("Setting_module/create_setting", $this->my_parameter, $created_by);
			
		if ($row_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SETTING",
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

	public function update_setting_by_id($row_id)
	{
		modules::run("Permission_module/require_permission", "SETTING_UPDATE");
		
		$row_id = intval($row_id);
		$this->my_parameter = $this->parameter;
		
		$modified_by = $this->userdata['user_id'];
		$this->update_setting($row_id, $modified_by);
	}

	protected function update_setting($row_id, $modified_by)
	{
		$affected_row = modules::run("Setting_module/update_setting_by_id", $row_id, $this->my_parameter, $modified_by);
			
		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SETTING",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_setting_by_id()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$settings = array_map("trim", $segs);

		// require access delete setting
		modules::run("Permission_module/require_permission", "SETTING_DELETE");

		$affected_row = modules::run("Setting_module/delete_setting_by_id", $settings);
		if ($affected_row != count($settings))
		{
			$code = modules::run("Error_module/get_error_code");
			response(500, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => 500,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "SETTING",
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


