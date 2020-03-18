<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_assessment_file.row_id' => 'row_id',
		'v_assessment_file.assessment_id' => 'assessment_id',
		'v_assessment_file.assessment_file_id' => 'assessment_file_id',
		'v_assessment_file.assessment_file_name' => 'assessment_file_name'
	),
	"optional" => array(
		'v_assessment_file.created_by' => 'created_by',
		'v_assessment_file.modified_by' => 'modified_by',
		'v_assessment_file.created_date' => 'created_date',
		'v_assessment_file.modified_date' => 'modified_date'
	)
);