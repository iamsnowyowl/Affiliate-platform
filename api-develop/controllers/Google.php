<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Google extends MX_Controller {
	
	protected $my_parameter;
	protected $google_config;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_calendar_indonesia_holiday(){
		$holiday = modules::run("Google_module/get_calendar_indonesia_holiday");
		response(200, array_merge(array("responseStatus" => "SUCCESS"), array("count"=> count($holiday), "data" => $holiday)));
	}

	public function broadcast_google_cloud_message()
	{
		$message = array(
			"title" => "Lets sing",
			"body" => "The song",
		);
		$data = modules::run("Google_module/send_fcm_message", $message);
		response(200, array_merge(array("responseStatus" => "SUCCESS"), array("data" => $data)));
	}
}


