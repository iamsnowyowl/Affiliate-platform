<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$code = 500;
http_response_code($code);
$error = array(
	"apiVersion" => API_VERSION,
	"requestTime" => time(),
	"responseStatus" => "ERROR",
	"error" => array(
		"code" => $code,
		"message" => strip_tags($message),
		"errors" => array(
			"domain" => "ERROR_GENERAL",
			"reason" => "GeneralErrorsException",
		)
	)
);
header("Content-Type: application/json");
exit(json_encode($error));