<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_dashboard" => array(
		array(
			'field' => 'dashboard_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'dashboard_name',
			'rules' => 'trim|required'
		)
	),
	"update_dashboard" => array(
		array(
			'field' => 'dashboard_name',
			'rules' => 'trim'
		)
	)
);