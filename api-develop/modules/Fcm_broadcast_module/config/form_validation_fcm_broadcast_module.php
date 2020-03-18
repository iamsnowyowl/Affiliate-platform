<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_fcm_broadcast" => array(
		array(
			'field' => 'user_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'topic',
			'rules' => 'trim'
		),
		array(
			'field' => 'title',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'message',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'data',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'schedule_send_date',
			'rules' => 'trim'
		)
	),
	"update_fcm_broadcast" => array(
		array(
			'field' => 'user_id',
			'rules' => 'trim'
		),
		array(
			'field' => 'topic',
			'rules' => 'trim'
		),
		array(
			'field' => 'click_action',
			'rules' => 'trim'
		),
		array(
			'field' => 'title',
			'rules' => 'trim'
		),
		array(
			'field' => 'message',
			'rules' => 'trim'
		),
		array(
			'field' => 'data',
			'rules' => 'trim'
		),
		array(
			'field' => 'send_date',
			'rules' => 'trim'
		),
		array(
			'field' => 'schedule_send_date',
			'rules' => 'trim'
		)
	)
);