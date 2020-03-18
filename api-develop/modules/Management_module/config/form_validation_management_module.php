<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_management" => array(
		array(
			'field' => 'user_id',
			'rules' => 'trim|is_natural|required'
		),
		array(
			'field' => 'signature',
			'rules' => 'trim'
		),
		array(
			'field' => 'level',
			'rules' => 'trim'
		)
	),
	"update_management" => array(
		array(
			'field' => 'signature',
			'rules' => 'trim'
		),
		array(
			'field' => 'level',
			'rules' => 'trim'
		)
	)
);