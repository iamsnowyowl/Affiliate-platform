<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'tbl_notification.notification_id' => 'notification_id',
		'tbl_notification.user_id' => 'user_id',
		'tbl_notification.click_action' => 'click_action',
		'tbl_notification.title' => 'title',
		'tbl_notification.message' => 'message',
		'tbl_notification.is_read' => 'is_read',
		'tbl_notification.data' => 'data',
		'tbl_notification.time_stamp' => 'time_stamp'
	),
	"optional" => array(
		'tbl_notification.modified_date' => 'modified_date'
	)
);