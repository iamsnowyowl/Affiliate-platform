<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_module" => array(
		array(
			'field' => 'module_name',
			'rules' => 'trim|required'
		)
	),
	"update_module" => array(
		array(
			'field' => 'module_name',
			'rules' => 'trim'
		)
	)
);