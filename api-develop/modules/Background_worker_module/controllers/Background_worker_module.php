<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Background_worker_module extends MX_Controller {

	protected $error;
	protected $error_code;
	protected $definition;
	protected $configuration;
	protected $rules;
	protected $my_parameter;
	protected $node;


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
		$definition_name = 'definition_'.strtolower(get_class($this));
		$rules_name = 'form_validation_'.strtolower(get_class($this));
		$configuration_name = 'configuration_background_worker_module';
		$this->config->load($definition_name, TRUE, TRUE);
		$this->config->load($rules_name, TRUE, TRUE);
		$this->config->load($configuration_name, TRUE, TRUE);
		$this->definition = $this->config->item($definition_name);
		$this->rules = $this->config->item($rules_name);
		$this->configuration = $this->config->item($configuration_name);
		$this->node = strtolower(get_class($this));
		$basepath = $this->configuration["archive"]["base_file"]."/".$this->configuration["archive"]["root_dir_name"];
		if (!file_exists($basepath)) mkdir($basepath, 0755); // create archive dir
	}

	public function send_email_reset_password($data)
	{
		$this->load->library("email", $this->configuration["reset_password"]["email"]);
		$result = array();
		
		for ($i=0; $i < count($data); $i++) 
		{ 
			$this->email->from($this->configuration["reset_password"]["email"]["smtp_user"]);
			$this->email->to($data[$i]->email);

			$this->email->subject('Request reset password');

			$content = array(
				"token" => $this->configuration["reset_password"]["url"]."/".$data[$i]->token, 
				"date" => $data[$i]->expired_date
			);

			$message = $this->load->view('reset_password', $content, TRUE);

			$this->email->message($message);

			$this->email->set_newline("\r\n");


			if ( ! $this->email->send())
			{
				// increase counter retry
				$count = intval($data[$i]->count_retry_email);
				$count++;
				$parameter = array(
					"count_retry_email" => $count
				);

				modules::run("User_module/update_reset_password_by_hash", hash("SHA512", $data[$i]->token, FALSE), $parameter);

				$result[] = array(
					"status" => FALSE,
					"message" => $this->email->print_debugger()
				);

			}
			else{
				// mark email is send
				$parameter = array(
					"is_email_send" => "1"
				);

				modules::run("User_module/update_reset_password_by_hash", hash("SHA512", $data[$i]->token, FALSE), $parameter);
				
				$result[] = array(
					"status" => TRUE,
					"message" => "Success"
				);
			}
		}
		return $result;
	}

	public function sending_fcm_broadcast_register_id($limit) 
	{
		$data = modules::run("Fcm_broadcast_module/get_fcm_broadcast_list");
		$result = array();
		$fcm_broadcast_id = array();

		foreach ($data["data"] as $key => $value) {
			# important to set default. since this will cause over memory limit...
			$parameter = array();
			$register_id = "";

			$fcm_broadcast_id[] = $value->fcm_broadcast_id;
			$parameter["data"] = json_decode($value->data);
			$parameter["notification"] = array(
				"title" => $value->title,
				"body" => $value->message,
				"click_action" => $value->click_action
			);

			$register_id = (!empty($value->topic) && strtoupper($value->topic) != "NONE") ? $value->topic : explode(",",$value->register_id);

			$result = modules::run("Google_module/send_fcm_message", $parameter, $register_id);

			modules::run("Fcm_user_module/clear_failed_token", $register_id, $result);
		}
		
		modules::run("Fcm_broadcast_module/update_fcm_broadcast_send_date_by_id", $fcm_broadcast_id);
		return $result;
	}

	public function archive($limit) 
	{
		$data = modules::run("Assessment_module/get_assessment_list", ["archive_flag" => 0, "last_activity_state" => "COMPLETED", "limit" => $limit]);
		$errors = [];
		if (empty($data["data"])) 
		{
			return TRUE;
		}

		for ($i=0; $i < count($data["data"]); $i++) { 

			// get assessment_applicant
			$errors[] = $this->archive_assessment_applicant($data["data"][$i]->assessment_id);
			
			$parameter = array(
				"assessment_id" => $data["data"][$i]->assessment_id,
				"title" => $data["data"][$i]->title,
				"notes" => $data["data"][$i]->notes,
				"last_activity_state" => $data["data"][$i]->last_activity_state,
				"last_activity_description" => $data["data"][$i]->last_activity_description,
				"tuk_id" => $data["data"][$i]->tuk_id,
				"tuk_name" => $data["data"][$i]->tuk_name,
				"address" => $data["data"][$i]->address,
				"sub_schema_number" => $data["data"][$i]->sub_schema_number,
				"schema_label" => $data["data"][$i]->schema_label,
				"schema_text" => $data["data"][$i]->schema_text,
				"longitude" => $data["data"][$i]->longitude,
				"latitude" => $data["data"][$i]->latitude,
				"start_date" => $data["data"][$i]->start_date,
				"end_date" => $data["data"][$i]->end_date,
				"pleno_date" => $data["data"][$i]->pleno_date,
				"request_date" => $data["data"][$i]->request_date,
				"created_by" => $data["data"][$i]->created_by,
				"modified_by" => $data["data"][$i]->modified_by,
				"created_date" => $data["data"][$i]->created_date,
				"modified_date" => $data["data"][$i]->modified_date
			);

			$archive = modules::run("Archive_module/create_archive", $parameter);
			
			if ($archive === FALSE) return FALSE;
		}
	}

	protected function archive_assessment_applicant($assessment_id)
	{
		$basepath = $this->configuration["archive"]["base_file"]."/".$this->configuration["archive"]["root_dir_name"]."/".$assessment_id;
		
		if (!file_exists($basepath)) mkdir($basepath, 0755); // create assessment dir
		$assessment_applicant = modules::run("Assessment_applicant_module/get_assessment_applicant_list", ["assessment_id" => $assessment_id, "limit" => 100]);
		$errors = [];
		$errors["fail_copy"] = [];
		$errors["file_nf"] = [];
		for ($i=0; $i < count($assessment_applicant["data"]); $i++) { 
			$user_full_name = ucwords($assessment_applicant["data"][$i]->first_name)." ".ucwords($assessment_applicant["data"][$i]->last_name);

			if (!file_exists($basepath."/peserta/".$user_full_name)) mkdir($basepath."/peserta/".$user_full_name, 0755, TRUE); // create applicant dir
			
			$csv = [];
			// each portfolio
			$applicant_portfolio = modules::run("Applicant_portfolio_module/get_applicant_portfolio_list", [
				"assessment_id" => $assessment_id, 
				"assessment_applicant_id" => $assessment_applicant["data"][$i]->assessment_applicant_id, 
				"limit" => 100
				]
			);

			if (empty($applicant_portfolio["data"][0])) continue;
			// put a raw log file 
			$keys = array_keys((array) $applicant_portfolio["data"][0]);
			$fp = fopen($basepath."/peserta/".$user_full_name.'/file.csv', 'w');
			fputcsv($fp, $keys, $this->configuration["archive"]["column_separator"]);

			for ($j=0; $j < count($applicant_portfolio["data"]); $j++) { 

				if (isset($applicant_portfolio["data"][$j]->form_type) 
				&& $applicant_portfolio["data"][$j]->form_type == "file"
				&& !empty($applicant_portfolio["data"][$j]->applicant_portfolio)) {
					for ($k=0; $k < count($applicant_portfolio["data"][$j]->applicant_portfolio); $k++) { 
						if (empty($applicant_portfolio["data"][$j]->applicant_portfolio[$k]["form_value"])) continue;

						// copy the file into user root dir
						$f = getenv("VOLUME_PATH").$applicant_portfolio["data"][$j]->applicant_portfolio[$k]["form_value"];
						if (file_exists($f)){
							$expl_filename = explode("/", $f);
							$filename = array_pop($expl_filename);

							if (!copy($f, $basepath."/peserta/".$user_full_name."/".$filename)) {
								$errors["fail_copy"][] = $f.",".$basepath."/peserta/".$user_full_name."/".$filename;
							}
						}
						else $errors["file_nf"][] = $f;
						// $basepath."/peserta/".$user_full_name
					}
				}
				
				$data = (array) $applicant_portfolio["data"][$j];
				$data["applicant_portfolio"] = (isset($data["applicant_portfolio"])) ? json_encode($data["applicant_portfolio"]) : "";

				$data = array_map(function($v){return trim(preg_replace('/\s+/', ' ', $v));}, $data);

				fputcsv($fp, $data, $this->configuration["archive"]["column_separator"]);
			}
		}

		// mark archive on assessment
		modules::run("Assessment_module/update_assessment_by_id", $assessment_id, ["archive_flag" => 1]);
		modules::run("Assessment_module/delete_soft_assessment_by_id", $assessment_id);

		return $errors;
	}

	protected function call_api($path)
	{
		$ch = curl_init();
		$agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36';
		curl_setopt($ch, CURLOPT_URL, "http://sertimedia.com/api/v1$path");
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Disabling SSL Certificate support temporarly
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		return curl_exec ($ch);
	}
}