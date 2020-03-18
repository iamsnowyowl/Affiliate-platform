<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_admintuk" => array(
		array(
			'field' => 'user_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'tuk_id',
			'rules' => 'trim|required'
		)
	),
	"update_admintuk" => array(
		array(
			'field' => 'tuk_id',
			'rules' => 'trim'
		)
	)
);