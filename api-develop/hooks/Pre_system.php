<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pre_system {


	public function initialize()
	{
		// register_shutdown_function([$this, 'handleShutdownForLogFatalEror']);
	}

	function handleShutdownForLogFatalEror() {
		// $CI =& get_instance();
		// if ($CI){
		// 	$CI->load->database();
		// 	$CI->load->model('Audit_trail_model');
		// 	$CI->request_info['response_code'] = http_response_code();
		// 	$CI->Audit_trail_model->add($CI->request_info);
		// }
		return;
	}
}