<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_accessor_schedule" => array(
		array(
			'field' => 'accessor_schedule_name',
			'rules' => 'trim|required'
		)
	),
	"update_accessor_schedule" => array(
		array(
			'field' => 'accessor_schedule_name',
			'rules' => 'trim'
		)
	)
);