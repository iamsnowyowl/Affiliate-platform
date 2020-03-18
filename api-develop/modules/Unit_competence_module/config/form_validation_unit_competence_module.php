<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_unit_competence" => array(
		array(
			'field' => 'unit_competence_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'unit_code',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'title',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'skkni',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim|required'
		)
	),
	"update_unit_competence" => array(
		array(
			'field' => 'unit_code',
			'rules' => 'trim'
		),
		array(
			'field' => 'title',
			'rules' => 'trim'
		),
		array(
			'field' => 'skkni',
			'rules' => 'trim'
		),
		array(
			'field' => 'sub_schema_number',
			'rules' => 'trim'
		)
	)
);