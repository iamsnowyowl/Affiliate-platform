<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'row_id' => 'row_id',
		'archive_id' => 'archive_id',
		'assessment_id' => 'assessment_id',
		'title' => 'title',
		'notes' => 'notes',
		'last_activity_state' => 'last_activity_state',
		'last_activity_description' => 'last_activity_description',
		'address' => 'address',
		'longitude' => 'longitude',
		'latitude' => 'latitude',
		'schema_text' => 'schema_text',
		'created_by' => 'created_by',
		'modified_by' => 'modified_by',
		'start_date' => 'start_date',
		'end_date' => 'end_date',
		'pleno_date' => 'pleno_date',
		'request_date' => 'request_date',
		'tuk_name' => 'tuk_name'
	),
	"optional" => array(
		'created_date' => 'created_date',
		'modified_date' => 'modified_date'
	)
);