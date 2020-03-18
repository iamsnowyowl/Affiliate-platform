<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_assessment_admin.assessment_id' => 'assessment_id',
		'v_assessment_admin.assessment_admin_id' => 'assessment_admin_id',
		'v_assessment_admin.admin_id' => 'admin_id',
		'v_assessment_admin.first_name' => 'first_name',
		'v_assessment_admin.last_name' => 'last_name',
		'v_assessment_admin.contact' => 'contact'
	),
	"optional" => array(
		'v_assessment_admin.created_by' => 'created_by',
		'v_assessment_admin.modified_by' => 'modified_by',
		'v_assessment_admin.created_date' => 'created_date',
		'v_assessment_admin.modified_date' => 'modified_date'
	),
	"default_assessment_by_admin" => array(
		'v_assessment_by_admin.assessment_id' => 'assessment_id',
		'v_assessment_by_admin.tuk_id' => 'tuk_id',
		'v_assessment_by_admin.tuk_name' => 'tuk_name',
		'v_assessment_by_admin.title' => 'title',
		'v_assessment_by_admin.notes' => 'notes',
		'v_assessment_by_admin.last_activity_state' => 'last_activity_state',
		'v_assessment_by_admin.last_activity_description' => 'last_activity_description',
		'v_assessment_by_admin.address' => 'address',
		'v_assessment_by_admin.longitude' => 'longitude',
		'v_assessment_by_admin.latitude' => 'latitude',
		'v_assessment_by_admin.start_date' => 'start_date',
		'v_assessment_by_admin.end_date' => 'end_date'
	),
	"optional_assessment_by_admin" => array(
		'v_assessment_by_admin.created_by' => 'created_by',
		'v_assessment_by_admin.modified_by' => 'modified_by',
		'v_assessment_by_admin.created_date' => 'created_date',
		'v_assessment_by_admin.modified_date' => 'modified_date'
	),
	"default_user_admin" => array(
		"v_user.user_id" => "user_id", 
		"v_user.role_code" => "role_code", 
		"v_user.group_id" => "group_id", 
		"v_user.username" => "username", 
		"v_user.email" => "email", 
		"v_user.first_name" => "first_name", 
		"v_user.contact" => "contact", 
		"v_user.place_of_birth" => "place_of_birth", 
		"v_user.gender_code" => "gender_code", 
		"v_user.picture" => "picture"
	),
	"optional_user_admin" => array(
		"v_user.date_of_birth" => "date_of_birth", 
		"v_user.address" => "address",
		"v_user.last_name" => "last_name",
		"v_user.last_login" => "last_login", 
		"v_user.created_by" => "created_by", 
		"v_user.modified_by" => "modified_by", 
		"v_user.activated_date" => "activated_date", 
		"v_user.created_date" => "created_date", 
		"v_user.modified_date" => "modified_date"
	)
);