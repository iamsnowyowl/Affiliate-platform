<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_fcm_broadcast.fcm_broadcast_id' => 'fcm_broadcast_id',
		'v_fcm_broadcast.user_id' => 'user_id',
		'v_fcm_broadcast.mac_address' => 'mac_address',
		'v_fcm_broadcast.register_id' => 'register_id',
		'v_fcm_broadcast.topic' => 'topic',
		'v_fcm_broadcast.click_action' => 'click_action',
		'v_fcm_broadcast.title' => 'title',
		'v_fcm_broadcast.message' => 'message',
		'v_fcm_broadcast.data' => 'data',
		'v_fcm_broadcast.send_date' => 'send_date',
		'v_fcm_broadcast.schedule_send_date' => 'schedule_send_date'
	),
	"optional" => array(
		'v_fcm_broadcast.created_date' => 'created_date',
		'v_fcm_broadcast.modified_date' => 'modified_date'
	)
);