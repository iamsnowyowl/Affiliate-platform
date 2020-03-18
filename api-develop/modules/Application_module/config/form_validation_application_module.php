<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_application" => array(
		array(
			'field' => 'application_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'application_name',
			'rules' => 'trim|required'
		)
	),
	"update_application" => array(
		array(
			'field' => 'application_name',
			'rules' => 'trim'
		)
	)
);