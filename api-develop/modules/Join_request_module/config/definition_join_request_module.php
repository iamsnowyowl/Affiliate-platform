<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'tbl_join_request.join_request_id' => 'join_request_id',
		'tbl_join_request.applicant_id' => 'applicant_id',
		'tbl_user.first_name' => 'first_name',
		'tbl_user.last_name' => 'last_name',
		'tbl_join_request.sub_schema_number' => 'sub_schema_number',
		'v_schema_full_wd.sub_schema_name' => 'sub_schema_name',
		'tbl_join_request.request_status' => 'request_status'
	),
	"optional" => array(
		'tbl_join_request.created_by' => 'created_by',
		'tbl_join_request.modified_by' => 'modified_by',
		'tbl_join_request.created_date' => 'created_date',
		'tbl_join_request.modified_date' => 'modified_date'
	)
);