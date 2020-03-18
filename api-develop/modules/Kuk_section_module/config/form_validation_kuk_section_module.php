<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_kuk_section" => array(
		array(
			'field' => 'kuk_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'kuk_section_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'kuk_section_name',
			'rules' => 'trim|required'
		)
	),
	"update_kuk_section" => array(
		array(
			'field' => 'kuk_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'kuk_section_name',
			'rules' => 'trim'
		)
	)
);