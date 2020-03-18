<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_setting" => array(
		array(
			'field' => 'setting_name',
			'rules' => 'trim|required'
		)
	),
	"update_setting" => array(
		array(
			'field' => 'setting_name',
			'rules' => 'trim'
		)
	)
);