<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function call_soap($config, $params){
	ini_set('soap.wsdl_cache_enabled', 0);
	ini_set('soap.wsdl_cache_ttl', 900);
	ini_set('default_socket_timeout', 15);

	$options = array(
			'uri'=>'http://schemas.xmlsoap.org/soap/envelope/',
			'style'=>SOAP_RPC,
			'use'=>SOAP_ENCODED,
			'soap_version'=>SOAP_1_1,
			'cache_wsdl'=>WSDL_CACHE_NONE,
			'connection_timeout'=>15,
			'trace'=>true,
			'encoding'=>'UTF-8',
			'exceptions'=>true,
		);
	try {
		$soap = new SoapClient($config['url'], $options);
		$data = $soap->GetRekening($params);
	}
	catch(Exception $e) {
		die($e->getMessage());
	}

	return $data;	  
}