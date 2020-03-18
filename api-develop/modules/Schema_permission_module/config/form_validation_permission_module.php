<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_schema_permission" => array(
		array(
			'field' => 'schema_permission_name',
			'rules' => 'trim|required'
		)
	),
	"update_schema_permission" => array(
		array(
			'field' => 'schema_permission_name',
			'rules' => 'trim'
		)
	)
);