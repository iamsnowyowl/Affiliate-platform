<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_kuk_section_detail" => array(
		array(
			'field' => 'number',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'kuk_section_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'kuk_section_detail_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'question',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'answer',
			'rules' => 'trim|required'
		)
	),
	"update_kuk_section_detail" => array(
		array(
			'field' => 'number',
			'rules' => 'trim'
		),
		array(
			'field' => 'kuk_section_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'kuk_section_detail_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'question',
			'rules' => 'trim'
		),
		array(
			'field' => 'answer',
			'rules' => 'trim'
		)
	)
);