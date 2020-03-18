<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_sub_schema" => array(
		array(
			'field' => 'schema_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'sub_schema_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'sub_schema_name',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'skkni',
			'rules' => 'trim'
		),
		array(
			'field' => 'skkk_year',
			'rules' => 'trim'
		),
		array(
			'field' => 'template',
			'rules' => 'trim'
		)
	),
	"update_sub_schema" => array(
		array(
			'field' => 'sub_schema_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'sub_schema_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim'
		),
		array(
			'field' => 'skkni',
			'rules' => 'trim'
		),
		array(
			'field' => 'skkk_year',
			'rules' => 'trim'
		),
		array(
			'field' => 'template',
			'rules' => 'trim'
		)
	)
);