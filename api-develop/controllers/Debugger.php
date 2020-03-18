<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debugger extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('http'));
	}

	public function index()
	{	
		// debug(modules::run("Letter_module/create_report_assessment", "d36b7252-e963-4eb1-b6dc-608c846544a9"));
		// die("END");
		
		// $data = array("first_name" => "Ari", "last_name" => "djemana");
		// $time = time();
		// $notification = array();
		// $notification["click_action"] = "WELCOME_MESSAGE";
		// $notification["title"] = "Welcome";
		// $notification["body"] = "Selamat Datang Kembali ";
		// $send = modules::run("Google_module/send_fcm_message", array(
		// 	"data" => $data,
		// 	"notification" => $notification
		// 	// "scheduled_send_date" => date("Y-m-d H:i:s", strtotime("+ 15 seconds"))
		// ), [$token]);
		// debug($send);
		// die;
		$only_output = (!empty($this->input->get('only_output'))) ? TRUE : FALSE;
		if (!$only_output) header("Content-Type: text/html");
		else header("Content-Type: text/html");
		// else header("Content-Type: application/json");

		$upn = "Lsp";
		$input = "RAW";
		$method = (!empty($this->input->get("method"))) ? strtoupper($this->input->get("method")) : "GET";
		$path = "/articles";
		$path = (!empty($this->input->get("path"))) ? $this->input->get("path") : $path;

		$upn_key = 'username_email';
		$token_key = 'secret_key';
		$exist_secret_key = "bQ7xGSya5r7mFvcXDL6Dc5NKkdUkBDxl47XQH4dtaJeU0chUYFqkAZ2f7mgtPbobMuFRd97QfgQnAobG4rlC7mGZqAljfOGnVwNp4G44GjgHuhSfdrCYgdbSg8PdAJxw";

		
		$body = array(
			"jobs_code" => "Tidak Bekerja",
			"jobs_name" => ""
		);
		$params = $body;

		$urlParams = (!empty($this->input->get("urlparams"))) ? $this->input->get("urlparams") : "";
		$urlParams = "?lang=indonesia&modified_date=".strtotime("2017-10-26 14:52:18").",".strtotime("2017-10-26 15:05:33");
		$urlParams = "?modified_date=1,1511181724";
		$urlParams = "?offset=0&limit=10&modified_date=1513099149";
		$urlParams = "";

		$base_url = "nginx-central";
		// $base_url = "https://api-lspabi.sertimedia.com";
		$url = $base_url.$path;

		$agent = 'bMozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36';
		$agent = 'Lsp-agent-x1-aaaa121-001';
		if (empty($exist_secret_key))
		{

			if (empty($this->parameter[$upn_key]) OR empty($this->parameter['password'])) 
			{
				die('email or password required');
			}
			else
			{
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $base_url.'/users/login');
				curl_setopt($ch, CURLOPT_USERAGENT, $agent);
				
				$fields = array($upn_key => $this->parameter[$upn_key], 'password' => $this->parameter['password']);

				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				// Disabling SSL Certificate support temporarly
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
				curl_setopt($ch, CURLINFO_HEADER_OUT, 1);


				$server_output_bef = curl_exec ($ch);
				debug($server_output_bef);

				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				$server_output = json_decode($server_output_bef, TRUE);
				if (empty($server_output[$token_key]))
				{
					if (!$only_output) echo "HTTP_CODE: <b>".$httpCode."</b>";
					if (!$only_output) echo "<pre>";
					if (!$only_output) print_r($server_output_bef);
					if (!$only_output) echo "</pre>";
					if (!$only_output) echo "<pre>";
					if (!$only_output) print_r($server_output);
					if (!$only_output) echo "</pre>";
				}
				$secretKey = (!empty($server_output[$token_key])) ? $server_output[$token_key] : "";

				if ($httpCode != '200')
				{
					if (!$only_output) echo "<pre>";
					if (!$only_output) print_r($server_output_bef);
					if (!$only_output) echo "</pre>";
					die("Invalid username/password supplied.. response httpStatusCode : ".$httpCode);
				}

				if (empty($secretKey))
				{
					die("server doen't return secretKey");
				}
			}
		}
		else
		{
			$secretKey = $exist_secret_key;
		}
		if (!$only_output) echo "<pre>";
		if (!$only_output) print_r(array('secretKey' => $secretKey));
		if (!$only_output) echo "</pre>";

		$ch = curl_init();
		
		$timezone = date('Z');
		$operator = ($timezone[0] === '-') ? '-' : '+';
		$timezone = abs($timezone);
		$timezone = floor($timezone/3600) * 100 + ($timezone % 3600) / 60;

		$date = sprintf('%s %s%04d', date('D, j M Y H:i:s'), $operator, $timezone);
		$headers = array();

		curl_setopt($ch, CURLOPT_URL, $url.$urlParams);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if ($input == "FORM") curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		if ($input == "RAW") 
		{
			$params = json_encode($params);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			$headers[] = 'Content-Type: text/html';
		}
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		// curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 0); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 0); //timeout in seconds
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		// {"method":"GET","path":"\/profile","Authorization":"Sinarmas sinarmas02:meMBN9vzzNTdme6ajcmFakw6jCQ=","Date":"Fri, 03 Jul 2015 15:29:24 +0700","digest":"meMBN9vzzNTdme6ajcmFakw6jCQ=","hash":"dcOlWo5t6lQ3zSNIjiNFaOODV3U="}

		$data = $method.'+'.$path.'+'.$date;
		// $data = 'GET+/profile+Fri, 03 Jul 2015 15:29:24 +0700';
		// GET+/profile+2015-04-23 11:36:20
		// $secretKey = "";
		// 5hMJoGHKo6gG2R7
		$hash_hmac = hash_hmac('SHA512', $data, $secretKey, FALSE);
		$digest = base64_encode($hash_hmac);
		// var_dump(hash_hmac('SHA1', "abc", "secret", TRUE));
		$headers[] = "Authorization: ".$upn." ".$this->parameter[$upn_key].":".$digest;

		$headers[] = "X-".$upn."-Date: ".$date;
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if (!$only_output) echo "URL: ".$url;
		if (!$only_output) echo "<pre>";
		if (!$only_output) print_r($headers);
		if (!$only_output) echo "</pre>";
		$server_output = curl_exec ($ch);
		// $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		// $header = substr($server_output, 0, $header_size);
		if (!$only_output) echo "Response:<br>";
		if (!$only_output) echo "<iframe width='1100px' height='1100px' srcdoc='".print_r($server_output, true)."'></iframe>";
		else echo $server_output; 
		curl_close ($ch);
	}

	protected function secondsToTime($seconds) {
		$dtF = new \DateTime('@0');
		$dtT = new \DateTime("@$seconds");
		return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
	}

	protected function get_file_info($file_name)
	{
		$data = array();
		$structure = array(
			"device_name",
			"channel",
			"stream",
			"start_date",
			"end_date"
		);
		// explode name
		$expl_filename = explode("_", $file_name);
		for ($i=0; $i < count($structure); $i++) 
		{ 
			$data[$structure[$i]] = $expl_filename[$i];
		}
		$data['end_date'] = explode(".", $data['end_date']);
		$data['end_date'] = $data['end_date'][0];
		return $data;
	}

	protected function human_filesize($bytes, $decimals = 2) {
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
	}
}


