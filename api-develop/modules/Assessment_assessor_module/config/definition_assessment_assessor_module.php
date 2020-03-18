<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_assessment_assessor.assessment_id' => 'assessment_id',
		'v_assessment_assessor.assessment_assessor_id' => 'assessment_assessor_id',
		'v_assessment_assessor.assessor_id' => 'assessor_id',
		'v_assessment_assessor.first_name' => 'first_name',
		'v_assessment_assessor.last_name' => 'last_name',
		'v_assessment_assessor.contact' => 'contact',
		'v_assessment_assessor.registration_number' => 'registration_number'
	),
	"optional" => array(
		'v_assessment_assessor.created_by' => 'created_by',
		'v_assessment_assessor.modified_by' => 'modified_by',
		'v_assessment_assessor.created_date' => 'created_date',
		'v_assessment_assessor.modified_date' => 'modified_date'
	),
	"default_assessment_by_assessor" => array(
		'v_assessment_by_assessor.assessment_id' => 'assessment_id',
		'v_assessment_by_assessor.tuk_id' => 'tuk_id',
		'v_assessment_by_assessor.tuk_name' => 'tuk_name',
		'v_assessment_by_assessor.title' => 'title',
		'v_assessment_by_assessor.notes' => 'notes',
		'v_assessment_by_assessor.last_activity_state' => 'last_activity_state',
		'v_assessment_by_assessor.last_activity_description' => 'last_activity_description',
		'v_assessment_by_assessor.address' => 'address',
		'v_assessment_by_assessor.longitude' => 'longitude',
		'v_assessment_by_assessor.latitude' => 'latitude',
		'v_assessment_by_assessor.start_date' => 'start_date',
		'v_assessment_by_assessor.end_date' => 'end_date',
		'v_assessment_by_assessor.request_date' => 'request_date'
	),
	"optional_assessment_by_assessor" => array(
		'v_assessment_by_assessor.created_by' => 'created_by',
		'v_assessment_by_assessor.modified_by' => 'modified_by',
		'v_assessment_by_assessor.created_date' => 'created_date',
		'v_assessment_by_assessor.modified_date' => 'modified_date'
	),
	"default_user_assessor" => array(
		"v_user_accessor.user_id" => "user_id", 
		"v_user_accessor.role_code" => "role_code", 
		"v_user_accessor.group_id" => "group_id", 
		"v_user_accessor.username" => "username", 
		"v_user_accessor.email" => "email", 
		"v_user_accessor.first_name" => "first_name", 
		"v_user_accessor.contact" => "contact", 
		"v_user_accessor.place_of_birth" => "place_of_birth", 
		"v_user_accessor.gender_code" => "gender_code", 
		"v_user_accessor.nik" => "nik", 
		"v_user_accessor.npwp" => "npwp", 
		"v_user_accessor.picture" => "picture"
	),
	"optional_user_assessor" => array(
		"v_user_accessor.date_of_birth" => "date_of_birth", 
		"v_user_accessor.address" => "address",
		"v_user_accessor.last_name" => "last_name",
		"v_user_accessor.signature_flag" => "signature_flag",
		"v_user_accessor.integrity_pact_flag" => "integrity_pact_flag",
		"v_user_accessor.place_of_birth" => "place_of_birth",
		"v_user_accessor.last_login" => "last_login", 
		"v_user_accessor.nik_photo" => "nik_photo",
		"v_user_accessor.npwp_photo" => "npwp_photo",
		"v_user_accessor.created_by" => "created_by", 
		"v_user_accessor.modified_by" => "modified_by", 
		"v_user_accessor.activated_date" => "activated_date", 
		"v_user_accessor.created_date" => "created_date", 
		"v_user_accessor.modified_date" => "modified_date"
	)
);