<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
	}

	public function get_own_notification_detail($row_id)
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['user_id'] = $this->userdata['user_id'];

		$row_id = intval($row_id);
		$data = $this->notification_list($row_id);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function get_own_cluster_notification_list() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['user_id'] = $this->userdata['user_id'];

		$assessment_request = modules::run("Assessment_module/get_assessment_count", array("last_activity_state" => "TUK_SEND_REQUEST_ASSESSMENT"));
		$assessment_pra = modules::run("Assessment_module/get_assessment_count", array("last_activity_state" => "PRA_ASSESSMENT"));
		$assessment_real = modules::run("Assessment_module/get_assessment_count", array("last_activity_state" => "REAL_ASSESSMENT"));
		$assessment_complete = modules::run("Assessment_module/get_assessment_count", array("last_activity_state" => "ASSESSMENT_COMPLETE"));

		$data = array(
			array(
				"cluster_name" => "ASSESSMENT_REQUEST",
				"count" => $assessment_request->count
			),
			array(
				"cluster_name" => "ON_PRA_ASSESSMENT",
				"count" => $assessment_pra->count
			),
			array(
				"cluster_name" => "ON_REAL_ASSESSMENT",
				"count" => $assessment_real->count
			),
			array(
				"cluster_name" => "ASSESSMENT_COMPLETE",
				"count" => $assessment_complete->count
			)
		);

		response(200, array_merge(array("responseStatus" => "SUCCESS"), array("data" => $data)));
	}

	public function get_own_notification_list() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['user_id'] = $this->userdata['user_id'];
		$data = $this->notification_list();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function notification_list($row_id = NULL)
	{
		$data = array();
		if (!empty($row_id))
		{
			$notifications = modules::run("Notification_module/get_notification_by_id", $this->my_parameter, $row_id);
			modules::run("Notification_module/update_notification_by_id", $row_id, array("is_read" => 1));

			$this->load->helper("url");

			if ($notifications === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "NOTIFICATION",
								"reason" => "NotificationNotFound"
							),
						)
					)
				);
			}
			$data["data"] = $notifications;
		}
		else
		{
			$data = modules::run("Notification_module/get_notification_list", $this->my_parameter);
		}

		return $data;
	}

	public function get_own_notification_count() 
	{
		$this->my_parameter = $this->parameter;
		$this->my_parameter['user_id'] = $this->userdata['user_id'];

		$data = $this->notification_count();
		response(200, array_merge(array("responseStatus" => "SUCCESS"), $data));
	}

	public function notification_count()
	{
		$count = modules::run("Notification_module/get_notification_count", $this->my_parameter);
		return (array) $count;
	}
}


