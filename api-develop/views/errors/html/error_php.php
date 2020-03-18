<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$code = 500;
http_response_code($code);
$debug_backtrace = array();

if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE)
{
	foreach (debug_backtrace() as $error)
	{
		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0)
		{
			$backtrace["File"] = $error['file'];
			$backtrace["Line"] = $error['line'];
			$backtrace["Function"] = $error['function'];
			$debug_backtrace[] = $backtrace;
		}
	}
}

$error = array(
	"apiVersion" => API_VERSION,
	"requestTime" => 0,
	"responseStatus" => "ERROR",
	"error" => array(
		"code" => $code,
		"message" => strip_tags($message),
		"errors" => array(
			"domain" => "ERRORSYSTEM",
			"reason" => "ErrorSystemException",
			"extra" => array(
				"message" => $message,
				"severity" => $severity,
				"fileName" => $filepath,
				"lineNumber" => $line,
				"debugBacktrace" => $debug_backtrace
			)
		)
	)
);
header("Content-Type: application/json");
exit(json_encode($error));
?>