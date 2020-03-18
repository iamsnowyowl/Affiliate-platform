<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		"v_user_management.user_id" => "user_id", 
		"v_user_management.role_code" => "role_code", 
		"v_user_management.group_id" => "group_id", 
		"v_user_management.username" => "username", 
		"v_user_management.email" => "email", 
		"v_user_management.first_name" => "first_name", 
		"v_user_management.contact" => "contact", 
		"v_user_management.gender_code" => "gender_code", 
		"v_user_management.picture" => "picture",
		"v_user_management.signature" => "signature"
	),
	"optional" => array(
		"v_user_management.address" => "address",
		"v_user_management.last_name" => "last_name",
		"v_user_management.signature_flag" => "signature_flag",
		"v_user_management.level" => "level",
		"v_user_management.last_login" => "last_login", 
		"v_user_management.created_by" => "created_by", 
		"v_user_management.modified_by" => "modified_by", 
		"v_user_management.activated_date" => "activated_date", 
		"v_user_management.created_date" => "created_date", 
		"v_user_management.modified_date" => "modified_date"
	)
);