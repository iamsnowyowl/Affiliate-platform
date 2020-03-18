<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_assessment_flow" => array(
		array(
			'field' => 'assessment_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'assessment_flow_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'priority',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'flow_state',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'flow_name',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'flow_rules',
			'rules' => 'trim|required'
		)
	),
	"update_assessment_flow" => array(
		array(
			'field' => 'flow_status',
			'rules' => 'trim'
		)
	)
);