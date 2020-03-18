<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Background_workers extends MX_Controller {
	
	protected $my_parameter;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http', 'permission', 'common'));
		$this->load->database();
		$this->output->enable_profiler(FALSE);
		$configuration_name = 'configuration_background_worker_module';
		$this->config->load($configuration_name, TRUE, TRUE);
		$this->configuration = $this->config->item($configuration_name);
	}

	public function reset_password() {
		// get only email with count_retry_email > configuration max_retry_email
		$time_run = time();
		$time_stop = strtotime("+1 hour");
		do{
			$time_run = time();
			$data = modules::run("User_module/get_list_hash_reset_password", $this->configuration["reset_password"]["email"]["max_retry_email"]);

			modules::run("Background_worker_module/send_email_reset_password", $data);

			sleep(1);
		}
		while ($time_run < $time_stop);
	}

	public function sending_fcm_broadcast_register_id() {
		$time_run = time();
		$time_stop = strtotime("+1 hour");

		do{
			$time_run = time();

			modules::run("Background_worker_module/sending_fcm_broadcast_register_id", $this->configuration["fcm_broadcast"]["limit"]);

			sleep(1);
		}
		while ($time_run < $time_stop);
	}

	public function archive() {
		$time_run = time();
		$time_stop = strtotime("+1 hour");

		do{
			$time_run = time();

			$status = modules::run("Background_worker_module/archive", $this->configuration["archive"]["limit"]);
			
			if ($status === FALSE)
			{
				$code = modules::run("Error_module/get_error_code");
				response($code, array(
						"responseStatus" => "ERROR",
						"error" => array(
							"code" => $code,
							"message" => modules::run("Error_module/get_error"),
							"errors" => array(
								"domain" => "ARCHIVE",
								"reason" => "UpdateErrorException",
								"extra" => modules::run("Error_module/get_error_extra")
							),
						)
					)
				);
			}

			sleep(300);
		}
		while ($time_run < $time_stop);
	}

	protected function log_message(){
		file_put_contents('', $log, FILE_APPEND);
	}
}


