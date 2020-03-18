<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System_cron extends MX_Controller {
	
	protected $cfg = array();
	protected $user_assign = array();
	protected $fetch_config_agent = array(
		"offset" => 0,
		"limit" => 10,
		"role_code" => "AGN"
	);
	
	protected $fetch_page_count_agent = 0;
	protected $fetch_page_cursor_agent = 0;
	protected $my_parameter = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function populate_agent_progress()
	{
		$today = date("Y-m-d");
		$count = modules::run("User_module/get_user_assign_count", NULL);
		$this->fetch_page_count_agent = ceil($count->count/$this->fetch_config_agent['limit']);
		$this->_fetch_user_assign();

		foreach ($this->user_assign as $key => $value) 
		{
			// get assignment
			$parameter = array(
				"user_id" => $value->user_id
			);
			$assignment = $this->get_assignment_list($parameter);

			$area = array();

			for ($i=0; $i < count($assignment); $i++) {
				$area[] = array("region" => $assignment[$i]->region, "block" => $assignment[$i]->block);
			}

			$parameter = array('area' => $area);

			$count_assignment = modules::run("Customer_module/get_customer_count", $parameter);

			$parameter = array(
				"created_by" => $value->user_id,
				"created_date" => strtotime($today.' 00:00:01').",".strtotime($today.' 23:59:59')
			);

			$count_progress = modules::run("Report_module/get_report_count", $parameter);

			$parameter = array(
				"user_id" => $value->user_id,
				"count_assignment" => $count_assignment->count,
				"count_progress" => $count_progress->count,
				"percentage" => ($count_progress->count/$count_assignment->count) * 100,
				"has_progress" => (!empty(($count_progress->count/$count_assignment->count) * 100)) ? 1 : 0, 
				"created_date" => $today." 21:00:00"
			);

			$result = modules::run("Evaluation_agent_module/create_evaluation_agent", $parameter);
			if ($result === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "USER",
								"reason" => "UserCreateErrorException",
								"extra" => modules::run("Error_module/get_error_extra")
							),
						)
					)
				);
			}
		}

		echo "finish";
	}

	protected function _fetch_user_assign(){
		if ($this->fetch_page_cursor_agent < $this->fetch_page_count_agent === FALSE) return;

		$data = modules::run("User_module/get_user_assign_list", $this->fetch_config_agent);
		$this->fetch_page_cursor_agent ++;

		for ($i=0; $i < count($data['data']); $i++) { 
			$this->user_assign[] = $data['data'][$i];
		}

		if (!empty($data['pagination']->next) && $data['pagination']->next != "") {
			$this->fetch_config_agent["offset"] = $data['pagination']->current_number * $this->fetch_config_agent['limit'];
			$this->_fetch_user_assign();
			return $this;
		}
		return $this;
	}

	protected function get_assignment_list($parameter)
	{
		// get_schema_permission
		$list_assignment = array();
		$count_assignment = modules::run("Assignment_module/get_assignment_count", $parameter);

		if (!empty($count_assignment->count))
		{
			$limit = 100;
			$count = ceil($count_assignment->count/$limit);
			for ($i=0; $i < $count; $i++) 
			{ 
				$list_parameter = array_merge(array(
						"offset" => $i*$limit,
						"limit" => $limit
					), $parameter);
				$list = modules::run("Assignment_module/get_assignment_list", 
					
				);
				$list_assignment += $list['data'];
			}
		}
		return $list_assignment;
	}
}


