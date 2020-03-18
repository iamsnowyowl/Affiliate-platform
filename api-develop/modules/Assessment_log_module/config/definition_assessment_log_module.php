<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_assessment_log.row_id' => 'row_id',
		'v_assessment_log.assessment_id' => 'assessment_id',
		'v_assessment_log.assessment_log_id' => 'assessment_log_id',
		'v_assessment_log.assessment_log_name' => 'assessment_log_name'
	),
	"optional" => array(
		'v_assessment_log.created_by' => 'created_by',
		'v_assessment_log.modified_by' => 'modified_by',
		'v_assessment_log.created_date' => 'created_date',
		'v_assessment_log.modified_date' => 'modified_date'
	)
);