<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'tbl_fcm_user.fcm_user_id' => 'fcm_user_id',
		'tbl_fcm_user.user_id' => 'user_id',
		'tbl_fcm_user.mac_address' => 'mac_address',
		'tbl_fcm_user.register_id' => 'register_id'
	),
	"optional" => array(
		'tbl_fcm_user.created_date' => 'created_date',
		'tbl_fcm_user.modified_date' => 'modified_date'
	)
);