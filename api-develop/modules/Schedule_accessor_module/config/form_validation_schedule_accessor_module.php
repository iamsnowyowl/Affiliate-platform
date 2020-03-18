<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_schedule_accessor" => array(
		array(
			'field' => 'accessor_id',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'CalendarDay',
			'rules' => 'trim|required|valid_date[Y-m-d]'
		)
	),
	"update_schedule_accessor" => array(
		array(
			'field' => 'CalendarDay',
			'rules' => 'trim'
		)
	)
);