<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_assessment_flow.assessment_id' => 'assessment_id',
		'v_assessment_flow.assessment_flow_id' => 'assessment_flow_id',
		'v_assessment_flow.priority' => 'priority',
		'v_assessment_flow.flow_state' => 'flow_state',
		'v_assessment_flow.flow_name' => 'flow_name',
		'v_assessment_flow.flow_rules' => 'flow_rules',
		'v_assessment_flow.flow_status' => 'flow_status'
	),
	"optional" => array(
		'v_assessment_flow.created_by' => 'created_by',
		'v_assessment_flow.modified_by' => 'modified_by',
		'v_assessment_flow.created_date' => 'created_date',
		'v_assessment_flow.modified_date' => 'modified_date'
	)
);