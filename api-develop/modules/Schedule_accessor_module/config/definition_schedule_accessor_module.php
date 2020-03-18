<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'v_schedule_accessor.schedule_accessor_id' => 'schedule_accessor_id',
		'v_schedule_accessor.accessor_id' => 'accessor_id',
		'v_schedule_accessor.first_name' => 'first_name',
		'v_schedule_accessor.last_name' => 'last_name',
		'v_schedule_accessor.contact' => 'contact',
		'v_schedule_accessor.address' => 'address',
		'v_schedule_accessor.CalendarDay' => 'CalendarDay'
	),
	"optional" => array(
		'v_schedule_accessor.created_by' => 'created_by',
		'v_schedule_accessor.modified_by' => 'modified_by',
		'v_schedule_accessor.created_date' => 'created_date',
		'v_schedule_accessor.modified_date' => 'modified_date'
	)
);