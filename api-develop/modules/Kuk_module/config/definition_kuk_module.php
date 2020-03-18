<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    "default" => array(
		'tbl_kuk.row_id' => 'row_id',
		'tbl_kuk.kuk_id' => 'kuk_id',
		'tbl_kuk.kuk_number' => 'kuk_number',
		'tbl_kuk.title' => 'title'
	),
	"optional" => array(
		'tbl_kuk.created_by' => 'created_by',
		'tbl_kuk.modified_by' => 'modified_by',
		'tbl_kuk.created_date' => 'created_date',
		'tbl_kuk.modified_date' => 'modified_date'
	)
);