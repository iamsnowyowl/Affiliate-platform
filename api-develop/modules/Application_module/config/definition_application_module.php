<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'tbl_application.row_id' => 'row_id',
		'tbl_application.application_id' => 'application_id',
		'tbl_application.application_name' => 'application_name'
	),
	"optional" => array(
		'tbl_application.created_by' => 'created_by',
		'tbl_application.modified_by' => 'modified_by',
		'tbl_application.created_date' => 'created_date',
		'tbl_application.modified_date' => 'modified_date'
	)
);