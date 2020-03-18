<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_flow" => array(
		array(
			'field' => 'flow_id',
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
	"update_flow" => array(
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
	)
);