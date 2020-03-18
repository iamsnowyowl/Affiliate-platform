<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_trail {

	public $parameter;
	public $request_info;

	public function init_audit()
	{
        date_default_timezone_set("Asia/Jakarta");

		$CI =& get_instance();
		$CI->benchmark->mark('execution_start_time_ms');

		$CI->load->helper(array('http', 'url'));

		$config_file = 'user_authentication';
        $CI->config->load($config_file, TRUE);
        $config_header = $CI->config->item('header', $config_file);

		$params = array();
		$parameter = NULL;
		
		// setup language
		if (!empty($_GET['lang']))
		{
			$CI->config->set_item('language', $_GET['lang']);
			unset($_GET['lang']);
		}
		
		$params["api_key"] = $CI->input->get_request_header($config_header['api_key'], TRUE);
		$params[$config_header['authorization']] = $CI->input->get_request_header($config_header['authorization'], TRUE);
		$params[$config_header['date']] = $CI->input->get_request_header($config_header['date'], TRUE);
		$params['method'] = $CI->input->method(TRUE);
		$params['path'] = $CI->input->server('PATH_INFO', TRUE);
		$params['path'] = "/".uri_string();
		$params['user_agent'] = $CI->input->get_request_header('User-Agent', TRUE);
		$params['ip_address'] = $CI->input->ip_address();

		if (strtolower($params['method']) == "get")
		{
			$parameter = $CI->input->get(NULL, TRUE);
		}
		else
		{
			$parameter = $CI->input->raw_input_stream;
			$body = "";
			if (!empty($parameter) && ($parameter = json_decode($parameter, TRUE)))
	        {
	            $CI->request_info = $params;
	            if (!empty($parameter['body'])) $body = $parameter['body'];
	            // $parameter = $CI->security->xss_clean($parameter);
				$CI->parameter = $parameter;
        		if (!empty($parameter['body'])) $CI->parameter['body'] = $body;

	            if (!is_array($parameter)) {
	            	$code = 400;
					response($code, array(
							"responseStatus" => "ERROR",
							"error" => array(
								"code" => $code,
								"message" => "invalid format request. request should be object or array",
								"errors" => array(
									"domain" => "MIDDLEWARE",
									"reason" => "OnCheckRequestFormat",
								),
							)
						)
					);
	            }
	             
				return;
	        }

			if (strtolower($params['method']) == "post")
			{
				$parameter = $CI->input->post(NULL, FALSE);
	            if (!empty($parameter['body'])) $body = $parameter['body'];
			}
			else
			{
				$parameter = $CI->input->input_stream(NULL, FALSE);
	            if (!empty($parameter['body'])) $body = $parameter['body'];
			}
		}

		if (isset($parameter["{}"])) unset($parameter["{}"]);
		
		$CI->request_info = $params;
		$CI->parameter = $parameter;

        if (!empty($parameter['body'])) $CI->parameter['body'] = $body;
		return;
	}

	public function save_audit()
	{
		$CI =& get_instance();
		$CI->load->database();
		$CI->load->model('Audit_trail_model');
		$CI->request_info['response_code'] = http_response_code();
		$CI->request_info['data'] = (!empty(is_array($CI->parameter))) ? json_encode($CI->parameter) : "";
		$CI->benchmark->mark('execution_end_time_ms');

		$date_start = DateTime::createFromFormat('U.u', $CI->benchmark->marker['execution_start_time_ms']);
		$date_start->setTimezone(new DateTimeZone('Asia/Jakarta'));

		$date_end = DateTime::createFromFormat('U.u', $CI->benchmark->marker['execution_end_time_ms']);
		$date_end->setTimezone(new DateTimeZone('Asia/Jakarta'));

		$CI->request_info['execution_start_time_ms'] = $date_start->format("Y-m-d H:i:s.u");
		$CI->request_info['execution_end_time_ms'] = $date_end->format("Y-m-d H:i:s.u");
		$CI->request_info['usage_time_ms'] = $CI->benchmark->elapsed_time('execution_start_time_ms', 'execution_end_time_ms');
		$CI->request_info['usage_memory'] = memory_get_usage(TRUE);
		$CI->Audit_trail_model->add($CI->request_info);
		return;
	}
}