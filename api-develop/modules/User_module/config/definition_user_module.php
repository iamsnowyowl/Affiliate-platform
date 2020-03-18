<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$config = array(
	"default" => array(
		'v_user.user_id' => 'user_id',
		'v_user.group_id' => 'group_id',
		'v_role.role_code' => 'role_code',
		'v_user.username' => 'username',
		'v_user.email' => 'email',
		'v_user.picture' => 'picture'
	),
	"optional" => array(
		'v_role.role_name' => 'role_name',
		'v_user.nik' => 'nik',
		'v_user.first_name' => 'first_name',
		'v_user.last_name' => 'last_name',
		'v_user.gender_code' => 'gender_code',
		'v_user.contact' => 'contact',
		'v_user.address' => 'address',
		'v_user.jobs_code' => 'jobs_code',
		'v_user.jobs_name' => 'jobs_name',
		'v_user.signature' => 'signature',
		'v_user.date_of_birth' => 'date_of_birth',
		'v_user.last_login' => 'last_login',
		'v_user.place_of_birth' => 'place_of_birth',
		'v_user.created_by' => 'created_by',
		'v_user.modified_by' => 'modified_by',
		'v_user.created_date' => 'created_date',
		'v_user.expired_date' => 'expired_date',
		'v_user.modified_date' => 'modified_date'
	),
	"default_deleted_list" => array(
		'v_user_deleted.user_id' => 'user_id',	
		'v_user_deleted.role_code' => 'role_code',
		'v_user_deleted.group_id' => 'group_id',
		'v_user_deleted.username' => 'username',
		'v_user_deleted.email' => 'email',
		'v_user_deleted.password' => 'password',
		'v_user_deleted.picture' => 'picture',
		'v_user_deleted.first_name' => 'first_name',
		'v_user_deleted.last_name' => 'last_name',
		'v_user_deleted.contact' => 'contact',
		'v_user_deleted.address' => 'address',
		'v_user_deleted.gender_code' => 'gender_code',
		'v_user_deleted.date_of_birth' => 'date_of_birth'
	),
	"optional_deleted_list" => array(
		'v_user_deleted.created_by' => 'created_by',
		'v_user_deleted.modified_by' => 'modified_by',
		'v_user_deleted.last_login' => 'last_login',
		'v_user_deleted.activated_date' => 'activated_date',
		'v_user_deleted.deleted_at' => 'deleted_at',
		'v_user.expired_date' => 'expired_date',
		'v_user_deleted.created_date' => 'created_date',
		'v_user_deleted.modified_date' => 'modified_date'
	)
);

$config['default_with_password'] = array_merge(
	$config['default'], 
	array('v_user.password' => 'password')
);