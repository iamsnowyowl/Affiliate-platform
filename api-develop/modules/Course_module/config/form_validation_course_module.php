<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
	"create_course" => array(
		array(
			'field' => 'course_name',
			'rules' => 'trim|required'
		)
	),
	"update_course" => array(
		array(
			'field' => 'course_name',
			'rules' => 'trim'
		)
	)
);