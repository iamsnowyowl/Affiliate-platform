<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_join_request" => array(
		array(
			'field' => 'applicant_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'request_status',
			'rules' => 'trim|required'
		)
	),
	"update_join_request" => array(
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'request_status',
			'rules' => 'trim'
		)
	)
);