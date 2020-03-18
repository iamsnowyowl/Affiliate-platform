<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_accessors extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_own_schedule_accessor_detail($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$data = $this->schedule_accessor_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_own_schedule_accessor_list() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['accessor_id'] = $this->userdata['user_id'];
		$data = $this->schedule_accessor_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_schedule_accessor_detail($row_id)
	{
		modules::run("Permission_module/require_permission", "SCHEDULE_ACCESSOR_LIST");
		$this->my_parameter = $this->parameter;

		$row_id = intval($row_id);
		$data = $this->schedule_accessor_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_schedule_accessor_list() 
	{
		modules::run("Permission_module/require_permission", "SCHEDULE_ACCESSOR_LIST");
		$this->my_parameter = $this->parameter;
		$data = $this->schedule_accessor_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function schedule_accessor_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$schedule_accessors = modules::run("Schedule_accessor_module/get_schedule_accessor_by_id", $this->my_parameter, $row_id);

			$this->load->helper("url");

			if ($schedule_accessors === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "SCHEDULE_ACCESSOR",
								"reason" => "Schedule_accessorNotFound"
							),
						)
					)
				);
			}
			$data['data'] = $schedule_accessors;
		}
		else
		{
			$data = modules::run("Schedule_accessor_module/get_schedule_accessor_list", $this->my_parameter);
		}

		return $data;
	}

	public function get_own_schedule_accessor_count() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$data = $this->schedule_accessor_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_schedule_accessor_count() 
	{
		modules::run("Permission_module/require_permission", "SCHEDULE_ACCESSOR_LIST");
		$this->my_parameter = $this->parameter;

		$data = $this->schedule_accessor_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function schedule_accessor_count()
	{
		$count = modules::run("Schedule_accessor_module/get_schedule_accessor_count", $this->my_parameter);
		return (array) $count;
	}

	# begin create schedule_accessor
	public function create_schedule_accessor_public()
	{
		$this->my_parameter = $this->parameter;

		$this->load->model("Transaction_model");

		// mark transaction to start
		$this->Transaction_model->trans_start();

		$row_id = $this->create_schedule_accessor();
		
		
		// once module faiil then everything will be rolling back
		$this->Transaction_model->trans_complete();

		$data = array('data' => array("schedule_accessor_id" => $row_id));

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function create_schedule_accessor_session()
	{
		modules::run("Permission_module/require_permission", "SCHEDULE_ACCESSOR_CREATE");
		
		if ($this->userdata['role_code'] == "ACS"){
			$this->my_parameter['accessor_id'] = $this->userdata['user_id'];
		}
		
		$this->my_parameter['schedule'] = $this->parameter;

		if (empty($this->my_parameter["schedule"]) && !empty($this->my_parameter['accessor_id'])) {
			modules::run("Schedule_accessor_module/delete_schedule_accessor_by_accessor_id", $this->my_parameter['accessor_id']);
			response(201, array_merge(array("responseStatus" => "SUCCESS"), array("data" => array())));
		}
		
		$created_by = $this->userdata['user_id'];

		$this->load->library('form_validation');
		
		$this->load->model("Transaction_model");

		// mark transaction to start
		$this->Transaction_model->trans_start();

		$row_id = $this->create_schedule_accessor($created_by);

		// once module faiil then everything will be rolling back
		$this->Transaction_model->trans_complete();

		$result = array();
		foreach ($row_id as $key => $value) {
			$result[] = array("schedule_accessor_id" => $value, "CalendarDay" => $this->parameter[$key]["CalendarDay"]);
		}

		$data = array('data' => $result);

		response(201, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	protected function create_schedule_accessor($created_by = 0)
	{
		// disable autocomit
		$auto_comit = FALSE;
		if (!empty($this->my_parameter['schedule']))
		{
			if (!empty($this->my_parameter['accessor_id'])) modules::run("Schedule_accessor_module/delete_schedule_accessor_by_accessor_id", $this->my_parameter['accessor_id'], $auto_comit);
			if (is_array($this->my_parameter['schedule']))
			{
				$row_id = array();
				$parameter = $this->my_parameter;
				$this->my_parameter = array();
				foreach ($parameter['schedule'] as $key => $value) {
					if (!empty($value['CalendarDay']) && !empty($parameter['accessor_id'])) {
						$this->my_parameter = array("CalendarDay" => $value['CalendarDay'], "accessor_id" => $parameter['accessor_id']);
					}
					$row_id[] = $this->create_schedule_accessor($created_by);
				}

				return $row_id;
			}
		}

		$row_id = modules::run("Schedule_accessor_module/create_schedule_accessor", $this->my_parameter, $created_by, $auto_comit);

		if ($row_id === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SCHEDULE_ACCESSOR",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		return $row_id;
	}

	public function update_own_schedule_accessor_by_id($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_schedule_accessor($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SCHEDULE_ACCESSOR",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function update_schedule_accessor_by_id($row_id)
	{
		modules::run("Permission_module/require_permission", "SCHEDULE_ACCESSOR_UPDATE");
		$this->my_parameter = $this->parameter;
		
		$row_id = intval($row_id);
		$modified_by = $this->userdata['user_id'];
		$affected_row = $this->update_schedule_accessor($row_id, $modified_by);

		if ($affected_row === FALSE)
		{
			$code = modules::run("Error_module/get_error_code");
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => modules::run("Error_module/get_error"),
						"errors" => array(
							"domain" => "SCHEDULE_ACCESSOR",
							"reason" => "UpdateErrorException",
							"extra" => modules::run("Error_module/get_error_extra")
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function update_schedule_accessor($row_id, $modified_by)
	{
		return modules::run("Schedule_accessor_module/update_schedule_accessor_by_id", $row_id, $this->my_parameter, $modified_by);
	}

	public function delete_own_schedule_accessor_by_id()
	{
		modules::run("Permission_module/require_permission", "SCHEDULE_ACCESSOR_DELETE");

		$affected_row = $this->delete_schedule_accessor();

		if ($affected_row != count($schedule_accessors))
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "SCHEDULE_ACCESSOR",
							"reason" => "UpdateErrorException",
							"extra" => array("counter_deleted" => $affected_row)
						),
					)
				)
			);
		}

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	public function delete_schedule_accessor_by_id()
	{
		modules::run("Permission_module/require_permission", "SCHEDULE_ACCESSOR_DELETE");

		$affected_rows = $this->delete_schedule_accessor();

		response(200, array_merge(array("responseStatus" => "SUCCESS")));
	}

	protected function delete_schedule_accessor()
	{
		$segs = array_values(array_filter(array_map("intval", $this->uri->segment_array())));
		$schedule_accessors = array_map("trim", $segs);

		$affected_rows = modules::run("Schedule_accessor_module/delete_schedule_accessor_by_id", $schedule_accessors);

		if ($affected_rows != count($schedule_accessors))
		{
			$code = 400;
			response($code, array(
					"responseStatus" => "ERROR",
					"error" => array(
						"code" => $code,
						"message" => "some row not deleted",
						"errors" => array(
							"domain" => "SCHEDULE_ACCESSOR",
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


