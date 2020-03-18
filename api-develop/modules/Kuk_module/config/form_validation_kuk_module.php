<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_kuk" => array(
		array(
			'field' => 'kuk_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'kuk_number',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'kuk_title',
			'rules' => 'trim|required'
		)
	),
	"update_kuk" => array(
		array(
			'field' => 'kuk_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'kuk_title',
			'rules' => 'trim'
		)
	)
);