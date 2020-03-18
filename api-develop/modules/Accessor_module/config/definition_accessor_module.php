<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
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
		"v_user_accessor.registration_number" => "registration_number", 
		"v_user_accessor.picture" => "picture"
	),
	"optional" => array(
		"v_user_accessor.date_of_birth" => "date_of_birth", 
		"v_user_accessor.address" => "address",
		"v_user_accessor.last_name" => "last_name",
		"v_user_accessor.signature" => "signature",
		"v_user_accessor.signature_flag" => "signature_flag",
		"v_user_accessor.integrity_pact_flag" => "integrity_pact_flag",
		"v_user_accessor.place_of_birth" => "place_of_birth",
		"v_user_accessor.last_login" => "last_login", 
		"v_user_accessor.nik_photo" => "nik_photo",
		"v_user_accessor.npwp_photo" => "npwp_photo",
		"v_user_accessor.certificate" => "certificate",
		"v_user_accessor.created_by" => "created_by", 
		"v_user_accessor.modified_by" => "modified_by", 
		"v_user_accessor.activated_date" => "activated_date", 
		"v_user_accessor.created_date" => "created_date", 
		"v_user_accessor.modified_date" => "modified_date"
	),
	"default_integrity_pact" => array(
		"v_user_accessor.user_id" => "user_id", 
		"v_user_accessor.username" => "username", 
		"v_user_accessor.email" => "email", 
		"v_user_accessor.first_name" => "first_name", 
		"v_user_accessor.signature" => "signature",
		"v_user_accessor.integrity_pact" => "integrity_pact",
		"v_user_accessor.signature_flag" => "signature_flag", 
		"v_user_accessor.integrity_pact_flag" => "integrity_pact_flag", 
		"v_user_accessor.date_of_birth" => "date_of_birth",
		"v_user_accessor.place_of_birth" => "place_of_birth"
	),
	"optional_integrity_pact" => array(
		"v_user_accessor.address" => "address",
		"v_user_accessor.picture" => "picture",
		"v_user_accessor.last_name" => "last_name", 
		"v_user_accessor.created_by" => "created_by", 
		"v_user_accessor.modified_by" => "modified_by", 
		"v_user_accessor.created_date" => "created_date", 
		"v_user_accessor.modified_date" => "modified_date"
	)
);