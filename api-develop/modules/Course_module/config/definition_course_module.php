<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_course.course_id' => 'course_id',
		'v_course.course_name' => 'course_name'
	),
	"optional" => array(
		'v_course.created_by' => 'created_by',
		'v_course.modified_by' => 'modified_by',
		'v_course.created_date' => 'created_date',
		'v_course.modified_date' => 'modified_date'
	)
);