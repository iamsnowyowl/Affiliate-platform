<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_application_setting" => array(
		array(
			'field' => 'application_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'application_setting_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'application_master_setting_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'reference_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'setting_value',
			'rules' => 'trim|required'
		)
	),
	"update_application_setting" => array(
		array(
			'field' => 'application_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'application_setting_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'application_master_setting_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'reference_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'setting_value',
			'rules' => 'trim|required'
		)
	)
);