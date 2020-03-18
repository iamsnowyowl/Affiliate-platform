<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_permission" => array(
		array(
			'field' => 'permission_name',
			'rules' => 'trim|required'
		)
	),
	"update_permission" => array(
		array(
			'field' => 'permission_name',
			'rules' => 'trim'
		)
	)
);