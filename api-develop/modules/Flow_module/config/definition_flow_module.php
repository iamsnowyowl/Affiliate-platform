<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_flow.flow_id' => 'flow_id',
		'v_flow.priority' => 'priority',
		'v_flow.flow_state' => 'flow_state',
		'v_flow.flow_name' => 'flow_name',
		'v_flow.flow_rules' => 'flow_rules'
	),
	"optional" => array(
		'v_flow.created_by' => 'created_by',
		'v_flow.modified_by' => 'modified_by',
		'v_flow.created_date' => 'created_date',
		'v_flow.modified_date' => 'modified_date'
	)
);