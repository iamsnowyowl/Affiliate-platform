<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_schema" => array(
		array(
			'field' => 'schema_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'skkni',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'skkni_year',
			'rules' => 'trim'
		),
		array(
			'field' => 'total_uk',
			'rules' => 'trim'
		),
		array(
			'field' => 'schema_name',
			'rules' => 'trim|required'
		)
	),
	"update_schema" => array(
		array(
			'field' => 'schema_name',
			'rules' => 'trim'
		),
		array(
			'field' => 'skkni',
			'rules' => 'trim'
		),
		array(
			'field' => 'skkni_year',
			'rules' => 'trim'
		),
		array(
			'field' => 'total_uk',
			'rules' => 'trim'
		)
	)
);