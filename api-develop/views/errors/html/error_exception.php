<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$code = 500;
http_response_code($code);
$debug_backtrace = array();

if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE)
{
	foreach ($exception->getTrace() as $error)
	{
		$backtrace["File"] = (!empty($error['file'])) ? $error['file'] : "";
		$backtrace["Line"] = (!empty($error['line'])) ? $error['line'] : "";
		$backtrace["Function"] = (!empty($error['function'])) ? $error['function'] : "";
		$debug_backtrace[] = $backtrace;
	}
}

$error = array(
	"apiVersion" => API_VERSION,
	"requestTime" => (function_exists("get_request_time")) ? get_request_time() : 0,
	"responseStatus" => "ERROR",
	"error" => array(
		"code" => $code,
		"message" => strip_tags($message),
		"errors" => array(
			"domain" => "ERROR_EXCEPTION",
			"reason" => "ErrorException",
			"extra" => array(
				"type" => get_class($exception),
				"message" => $message,
				"fileName" => $exception->getFile(),
				"lineNumber" => $exception->getLine(),
				"debugBacktrace" => $debug_backtrace
			)
		)
	)
);
header("Content-Type: application/json");
exit(json_encode($error));