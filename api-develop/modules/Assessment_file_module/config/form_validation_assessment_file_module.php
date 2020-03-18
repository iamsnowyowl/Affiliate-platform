<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_assessment_file" => array(
		array(
			'field' => 'assessment_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_file_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_file_name',
			'rules' => 'trim|required'
		)
	),
	"update_assessment_file" => array(
		array(
			'field' => 'assessment_file_name',
			'rules' => 'trim'
		)
	)
);