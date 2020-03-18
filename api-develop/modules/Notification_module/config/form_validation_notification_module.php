<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_notification" => array(
		array(
			'field' => 'user_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'message',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'title',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'data',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'time_stamp',
			'rules' => 'trim|required'
		)
	),
	"update_notification" => array(
		array(
			'field' => 'user_id',
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
			'field' => 'time_stamp',
			'rules' => 'trim'
		),
		array(
			'field' => 'is_read',
			'rules' => 'trim|in_list[0,1]'
		)
	)
);