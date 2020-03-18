<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_application_master_setting" => array(
		array(
			'field' => 'application_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'application_master_setting_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'application_master_setting_name',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'application_master_setting_type',
			'rules' => 'trim|required'
		)
	),
	"update_application_master_setting" => array(
		array(
			'field' => 'application_master_setting_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'application_master_setting_type',
			'rules' => 'trim'
		)
	)
);