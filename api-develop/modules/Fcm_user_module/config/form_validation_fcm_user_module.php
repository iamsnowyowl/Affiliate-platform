<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_fcm_user" => array(
		array(
			'field' => 'user_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'mac_address',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'register_id',
			'rules' => 'trim|required'
		)
	),
	"update_fcm_user" => array(
		array(
			'field' => 'fcm_user_name',
			'rules' => 'trim'
		)
	)
);