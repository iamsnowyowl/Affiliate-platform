<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default_assessment" => array(
		'v_assessment.assessment_id' => 'assessment_id',
		'v_assessment.tuk_id' => 'tuk_id',
		'v_assessment.tuk_name' => 'tuk_name',
		'v_assessment.title' => 'title',
		'v_assessment.notes' => 'notes',
		'v_assessment.last_activity_state' => 'last_activity_state',
		'v_assessment.last_activity_description' => 'last_activity_description',
		'v_assessment.address' => 'address',
		'v_assessment.longitude' => 'longitude',
		'v_assessment.latitude' => 'latitude',
		'v_assessment.start_date' => 'start_date',
		'v_assessment.end_date' => 'end_date',
		'v_assessment.pleno_date' => 'pleno_date',
		'v_assessment.assessor_id' => 'assessor_id',
		'v_assessment.admin_id' => 'admin_id',
		'v_assessment.pleno_id' => 'pleno_id',
		'v_assessment.request_letter_url' => 'request_letter_url'
	),
	"optional_assessment" => array(
		'v_assessment.created_by' => 'created_by',
		'v_assessment.modified_by' => 'modified_by',
		'v_assessment.created_date' => 'created_date',
		'v_assessment.modified_date' => 'modified_date'
	)
);