<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accessor_schedules extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_own_accessor_schedule_detail($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$data = $this->accessor_schedule_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_own_accessor_schedule_list() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];
		$data = $this->accessor_schedule_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_accessor_schedule_detail($row_id)
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_LIST,SCHEDULE_ACCESSOR_LIST");
		$this->my_parameter = $this->parameter;

		$row_id = intval($row_id);
		$data = $this->accessor_schedule_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_accessor_schedule_list() 
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_LIST,SCHEDULE_ACCESSOR_LIST");
		$this->my_parameter = $this->parameter;
		$data = $this->accessor_schedule_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function accessor_schedule_list($row_id = NULL)
	{
		$data = array();

		$date_formater = function($date) {
		    return date("Y-m-d", strtotime($date));
		};

		if (!empty($row_id))
		{
			$accessor_schedules = modules::run("Accessor_schedule_module/get_accessor_schedule_by_id", $this->my_parameter, $row_id);

			$this->load->helper("url");

			if ($accessor_schedules === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "ACCESSOR_SCHEDULE",
								"reason" => "Accessor_scheduleNotFound"
							),
						)
					)
				);
			}
			$data["data"] = $accessor_schedules;
			$data['data']->picture = "/users/".$data['data']->accessor_id."/picture";
			
			$data['data']->CalendarDay = $date_formater(explode(",", $data['data']->CalendarDay));
		}
		else
		{
			$data = modules::run("Accessor_schedule_module/get_accessor_schedule_list", $this->my_parameter);
			foreach ($data["data"] as $index => $value) {
				$data['data'][$index]->picture = "/users/".$data['data'][$index]->accessor_id."/picture";
				$data['data'][$index]->CalendarDay = array_map($date_formater, explode(",", $data["data"][$index]->CalendarDay));
			}
		}

		return $data;
	}

	public function get_own_accessor_schedule_count() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['created_by'] = $this->userdata['user_id'];

		$data = $this->accessor_schedule_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_accessor_schedule_count() 
	{
		modules::run("Permission_module/require_permission", "ACCESSOR_LIST,SCHEDULE_ACCESSOR_LIST");
		$this->my_parameter = $this->parameter;

		$data = $this->accessor_schedule_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function accessor_schedule_count()
	{
		$count = modules::run("Accessor_schedule_module/get_accessor_schedule_count", $this->my_parameter);
		return (array) $count;
	}
}


