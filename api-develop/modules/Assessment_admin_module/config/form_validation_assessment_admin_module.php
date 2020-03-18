<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_assessment_admin" => array(
		array(
			'field' => 'assessment_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_admin_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'admin_id',
			'rules' => 'trim|required'
		)
	),
	"update_assessment_admin" => array(
		array(
			'field' => 'admin_id',
			'rules' => 'trim'
		)
	)
);